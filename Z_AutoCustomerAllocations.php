<?php


/*
Call this page with:
	1. A DebtorNo to show all outstanding receipts or credits yet to be allocated.
*/

include('includes/DefineCustAllocsClass.php');
include('includes/session.php');
$Title = _('Automatic Customer Receipt') . '/' . _('Credit Note Allocations');

$ViewTopic= 'ARTransactions';
$BookMark = 'CustomerAllocations';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['DebtorNo'])) {
	// Page called with customer code

	$SQL = "SELECT debtortrans.id,
				debtortrans.transno,
				systypes.typename,
				debtortrans.type,
				debtortrans.debtorno,
				debtorsmaster.name,
				debtortrans.trandate,
				debtortrans.reference,
				debtortrans.rate,
				debtortrans.ovamount+debtortrans.ovgst+debtortrans.ovdiscount+debtortrans.ovfreight as total,
				debtortrans.alloc,
				currencies.decimalplaces AS currdecimalplaces,
				debtorsmaster.currcode
			FROM debtortrans INNER JOIN debtorsmaster
			ON debtortrans.debtorno=debtorsmaster.debtorno
			INNER JOIN systypes
			ON debtortrans.type=systypes.typeid
			INNER JOIN currencies
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE debtortrans.debtorno='" . $_GET['DebtorNo'] . "'
			AND ( (debtortrans.type=12 AND debtortrans.ovamount<0) OR debtortrans.type=11)
			AND debtortrans.settled=0
			ORDER BY debtortrans.id";

	$result = DB_query($SQL);

	if (DB_num_rows($result)==0) {
		prnMsg(_('No outstanding receipts or credits to be allocated for this customer'),'info');
		include('includes/footer.php');
		exit;
	}
	 echo '<table class="selection">';
	echo $TableHeader;

	while ($myrow = DB_fetch_array($result)) {
		unset($_SESSION['Alloc']->Allocs);
		unset($_SESSION['Alloc']);
		$_SESSION['Alloc'] = new Allocation;
		$_SESSION['Alloc']->AllocTrans 		= $myrow['id'];
		$_SESSION['Alloc']->DebtorNo		= $myrow['debtorno'];
		$_SESSION['Alloc']->CustomerName	= $myrow['name'];
		$_SESSION['Alloc']->TransType		= $myrow['type'];
		$_SESSION['Alloc']->TransTypeName	= $myrow['typename'];
		$_SESSION['Alloc']->TransNo		= $myrow['transno'];
		$_SESSION['Alloc']->TransExRate	= $myrow['rate'];
		$_SESSION['Alloc']->TransAmt		= $myrow['total'];
		$_SESSION['Alloc']->PrevDiffOnExch = $myrow['diffonexch'];
		$_SESSION['Alloc']->TransDate		= ConvertSQLDate($myrow['trandate']);
		$_SESSION['Alloc']->CurrDecimalPlaces = $myrow['decimalplaces'];

		// Now get invoices or neg receipts that have outstanding balances
		$SQL = "SELECT debtortrans.id,
					typename,
					transno,
					trandate,
					rate,
					ovamount+ovgst+ovfreight+ovdiscount as total,
					diffonexch,
					alloc
				FROM debtortrans INNER JOIN systypes
				ON debtortrans.type = systypes.typeid
				WHERE debtortrans.settled=0
				AND (systypes.typeid=10 OR (systypes.typeid=12 AND ovamount>0))
				AND debtorno='" . $_SESSION['Alloc']->DebtorNo . "'
				ORDER BY debtortrans.id DESC";
		$TransResult = DB_query($SQL);
		$BalToAllocate = $_SESSION['Alloc']->TransAmt - $myrow['alloc'];
		while ($myalloc=DB_fetch_array($TransResult) AND $BalToAllocate < 0) {
			if ($myalloc['total']-$myalloc['alloc']< abs($BalToAllocate)) {
				$ThisAllocation = $myalloc['total']-$myalloc['alloc'];
			} else {
				$ThisAllocation = abs($BalToAllocate);
			}
			$_SESSION['Alloc']->add_to_AllocsAllocn ($myalloc['id'],
													$myalloc['typename'],
													$myalloc['transno'],
													ConvertSQLDate($myalloc['trandate']),
													$ThisAllocation,
													$myalloc['total'],
													$myalloc['rate'],
													$myalloc['diffonexch'],
													$myalloc['diffonexch'],
													$myalloc['alloc'],
													'NA');
			$BalToAllocate += $ThisAllocation;//since $BalToAllocate is negative
		}
		DB_free_result($TransResult);

		ProcessAllocation();
	}
	echo '</table>';
}

include('includes/footer.php');

function ProcessAllocation() {
	if ($InputError==0) {
		//
		//========[ START TRANSACTION ]===========
		//
		$Error = '';
		$Result= DB_Txn_Begin();
		$AllAllocations = 0;
		$TotalDiffOnExch = 0;
		foreach ($_SESSION['Alloc']->Allocs as $AllocnItem) {

			if ($AllocnItem->AllocAmt > 0) {
				$SQL = "INSERT INTO
							custallocns (
							datealloc,
							amt,
							transid_allocfrom,
							transid_allocto
						) VALUES (
							'" . date('Y-m-d') . "',
							'" . $AllocnItem->AllocAmt . "',
							'" . $_SESSION['Alloc']->AllocTrans . "',
							'" . $AllocnItem->ID . "'
						)";
				if( !$Result = DB_query($SQL) ) {
					$Error = _('Could not insert allocation record');
				}
			}
			$NewAllocTotal = $AllocnItem->PrevAlloc + $AllocnItem->AllocAmt;
			$AllAllocations = $AllAllocations + $AllocnItem->AllocAmt;
			$Settled = (abs($NewAllocTotal-$AllocnItem->TransAmount) < 0.005) ? 1 : 0;
			$TotalDiffOnExch += $AllocnItem->DiffOnExch;

			$SQL = "UPDATE debtortrans
					SET diffonexch='" . $AllocnItem->DiffOnExch . "',
					alloc = '" . $NewAllocTotal . "',
					settled = '" . $Settled . "'
					WHERE id = '" . $AllocnItem->ID."'";
			if( !$Result = DB_query($SQL) ) {
				$Error = _('Could not update difference on exchange');
			}
		}
		if (abs($TotalAllocated + $_SESSION['Alloc']->TransAmt) < 0.01) {
			$Settled = 1;
		} else {
			$Settled = 0;
		}
		// Update the receipt or credit note
		$SQL = "UPDATE debtortrans
				SET alloc = '" .  -$AllAllocations . "',
				diffonexch = '" . -$TotalDiffOnExch . "',
				settled='" . $Settled . "'
				WHERE id = '" . $_SESSION['Alloc']->AllocTrans . "'";

		if( !$Result = DB_query($SQL) ) {
			$Error = _('Could not update receipt or credit note');
		}

		// If GLLink to debtors active post diff on exchange to GL
		$MovtInDiffOnExch = -$_SESSION['Alloc']->PrevDiffOnExch - $TotalDiffOnExch;

		if ($MovtInDiffOnExch !=0) {
			if ($_SESSION['CompanyRecord']['gllink_debtors'] == 1) {
				$PeriodNo = GetPeriod($_SESSION['Alloc']->TransDate);
				$_SESSION['Alloc']->TransDate = FormatDateForSQL($_SESSION['Alloc']->TransDate);

					$SQL = "INSERT INTO gltrans (
								type,
								typeno,
								trandate,
								periodno,
								account,
								narrative,
								amount
							) VALUES (
								'" . $_SESSION['Alloc']->TransType . "',
								'" . $_SESSION['Alloc']->TransNo . "',
								'" . $_SESSION['Alloc']->TransDate . "',
								'" . $PeriodNo . "',
								'" . $_SESSION['CompanyRecord']['exchangediffact'] . "',
								'',
								'" . $MovtInDiffOnExch . "'
							)";
				if( !$Result = DB_query($SQL) ) {
					$Error = _('Could not update exchange difference in General Ledger');
				}

		  		$SQL = "INSERT INTO gltrans (
							type,
							typeno,
							trandate,
							periodno,
							account,
							narrative,
							amount
		  				) VALUES ('" . $_SESSION['Alloc']->TransType . "',
									'" . $_SESSION['Alloc']->TransNo . "',
									'" . $_SESSION['Alloc']->TransDate . "',
									'" . $PeriodNo . "',
									'" . $_SESSION['CompanyRecord']['debtorsact'] . "',
									'',
									'" . -$MovtInDiffOnExch . "')";

				if( !$Result = DB_query($SQL) ) {
					$Error = _('Could not update debtors control in General Ledger');
				}
			}

		}

		//
		//========[ COMMIT TRANSACTION ]===========
		//
		if (empty($Error) ) {
			$Result = DB_Txn_Commit();
		} else {
			$Result = DB_Txn_Rollback();
			prnMsg($Error,'error');
		}
		unset($_SESSION['Alloc']);
		unset($_POST['AllocTrans']);
	}
}

?>

<?php
include ('includes/session.php');
$Title = _('General Ledger Transaction Inquiry');
$ViewTopic = 'GeneralLedger';
$BookMark = 'GLTransInquiry';
include ('includes/header.php');

$MenuURL = '<div><a href="' . $RootPath . '/index.php?&amp;Application=GL">' . _('General Ledger Menu') . '</a></div>';

if (!isset($_GET['TypeID']) or !isset($_GET['TransNo'])) {
	prnMsg(_('This page requires a valid transaction type and number'), 'warn');
	echo $MenuURL;
} else {
	$typeSQL = "SELECT typename,
						typeno
				FROM systypes
				WHERE typeid = '" . $_GET['TypeID'] . "'";

	$TypeResult = DB_query($typeSQL);

	if (DB_num_rows($TypeResult) == 0) {
		prnMsg(_('No transaction of this type with id') . ' ' . $_GET['TypeID'], 'error');
		echo $MenuURL;
	} else {
		$MyRow = DB_fetch_row($TypeResult);
		DB_free_result($TypeResult);
		$TransName = $MyRow[0];

		// Context Navigation and Title
		echo $MenuURL;
		//
		//========[ SHOW SYNOPSYS ]===========
		//
		echo '<p class="page_title_text"><img alt="" src="' . $RootPath, '/css/', $Theme, '/images/magnifier.png" title="' . _('General Ledger Transaction Inquiry') . '" />' . ' ' . _('General Ledger Transaction Inquiry') . '</p>';

		echo '<table class="selection">'; //Main table
		echo '<tr>
				<th colspan="7"><h2><b>' . _($TransName) . ' ' . $_GET['TransNo'] . '</b></h2></th>
			</tr>
			<tr>
				<th>' . _('Period') . '</th>
				<th>' . _('Date') . '</th>
				<th>' . _('GL Account') . '</th>
				<th>' . _('Description') . '</th>
				<th>' . _('Debits') . '</th>
				<th>' . _('Credits') . '</th>
				<th>' . _('Posted') . '</th>
			</tr>';

		$SQL = "SELECT
					gltrans.periodno,
					gltrans.trandate,
					gltrans.type,
					gltrans.account,
					chartmaster.accountname,
					gltrans.narrative,
					gltrans.amount,
					gltrans.posted,
					periods.lastdate_in_period
				FROM gltrans INNER JOIN chartmaster
				ON gltrans.account = chartmaster.accountcode
				INNER JOIN periods
				ON periods.periodno=gltrans.periodno
				WHERE gltrans.type= '" . $_GET['TypeID'] . "'
				AND gltrans.typeno = '" . $_GET['TransNo'] . "'
				ORDER BY gltrans.counterindex";
		$TransResult = DB_query($SQL);

		$Posted = _('Yes');
		$CreditTotal = 0;
		$DebitTotal = 0;
		$AnalysisCompleted = 'Not Yet';

		while ($TransRow = DB_fetch_array($TransResult)) {
			$TranDate = ConvertSQLDate($TransRow['trandate']);
			$DetailResult = false;

			if ($TransRow['amount'] > 0) {
				$DebitAmount = locale_number_format($TransRow['amount'], $_SESSION['CompanyRecord']['decimalplaces']);
				$DebitTotal+= $TransRow['amount'];
				$CreditAmount = '&nbsp;';
			} else {
				$CreditAmount = locale_number_format(-$TransRow['amount'], $_SESSION['CompanyRecord']['decimalplaces']);
				$CreditTotal+= $TransRow['amount'];
				$DebitAmount = '&nbsp;';
			}
			if ($TransRow['posted'] == 0) {
				$Posted = _('No');
			}
			if ($TransRow['account'] == $_SESSION['CompanyRecord']['debtorsact'] and $AnalysisCompleted == 'Not Yet') {
				$URL = $RootPath . '/CustomerInquiry.php?CustomerID=';
				$FromDate = '&amp;TransAfterDate=' . urlencode($TranDate);

				$DetailSQL = "SELECT debtortrans.debtorno AS otherpartycode,
										debtortrans.ovamount,
										debtortrans.ovgst,
										debtortrans.ovfreight,
										debtortrans.rate,
										debtortrans.ovdiscount,
										debtorsmaster.name AS otherparty
									FROM debtortrans INNER JOIN debtorsmaster
									ON debtortrans.debtorno = debtorsmaster.debtorno
									WHERE debtortrans.type = '" . $TransRow['type'] . "'
									AND debtortrans.transno = '" . $_GET['TransNo'] . "'";
				$DetailResult = DB_query($DetailSQL);

			} elseif ($TransRow['account'] == $_SESSION['CompanyRecord']['creditorsact'] and $AnalysisCompleted == 'Not Yet') {
				$URL = $RootPath . '/SupplierInquiry.php?SupplierID=';
				$FromDate = '&amp;FromDate=' . urlencode($TranDate);

				$DetailSQL = "SELECT supptrans.supplierno AS otherpartycode,
										supptrans.ovamount,
										supptrans.ovgst,
										supptrans.rate,
										suppliers.suppname AS otherparty
									FROM supptrans INNER JOIN suppliers
									ON supptrans.supplierno = suppliers.supplierid
									WHERE supptrans.type = '" . $TransRow['type'] . "'
									AND supptrans.transno = '" . $_GET['TransNo'] . "'";
				$DetailResult = DB_query($DetailSQL);

			} else {
				// if user is allowed to see the account we show it, other wise we show "OTHERS ACCOUNTS"
				$CheckSql = "SELECT count(*)
								 FROM glaccountusers
								 WHERE accountcode= '" . $TransRow['account'] . "'
									 AND userid = '" . $_SESSION['UserID'] . "'
									 AND canview = '1'";
				$CheckResult = DB_query($CheckSql);
				$CheckRow = DB_fetch_row($CheckResult);

				if ($CheckRow[0] > 0) {
					$AccountName = $TransRow['accountname'];
					$URL = $RootPath . '/GLAccountInquiry.php?Account=' . urlencode($TransRow['account']);
				} else {
					$AccountName = _('Other GL Accounts');
					$URL = "";
				}

				if (mb_strlen($TransRow['narrative']) == 0) {
					$TransRow['narrative'] = '&nbsp;';
				}

				echo '<tr class="striped_row">
							<td>' . MonthAndYearFromSQLDate($TransRow['lastdate_in_period']) . '</td>
							<td>' . $TranDate . '</td>';

				if ($URL == "") {
					// User is not allowed to see this GL account, don't show the details
					echo '	<td>' . $AccountName . '</td>
								<td>' . $AccountName . '</td>';
				} else {
					echo '	<td><a href="' . $URL . '">' . $AccountName . '</a></td>
								<td>' . $TransRow['narrative'] . '</td>';
				}

				echo '	<td class="number">' . $DebitAmount . '</td>
							<td class="number">' . $CreditAmount . '</td>
							<td>' . $Posted . '</td>
						</tr>';
			}

			if ($DetailResult and $AnalysisCompleted == 'Not Yet') {

				while ($DetailRow = DB_fetch_array($DetailResult)) {
					if ($TransRow['amount'] > 0) {
						if ($TransRow['account'] == $_SESSION['CompanyRecord']['debtorsact']) {
							$Debit = locale_number_format(($DetailRow['ovamount'] + $DetailRow['ovgst'] + $DetailRow['ovfreight']) / $DetailRow['rate'], $_SESSION['CompanyRecord']['decimalplaces']);
							$Credit = '&nbsp;';
						} else {
							$Debit = locale_number_format(-($DetailRow['ovamount'] + $DetailRow['ovgst']) / $DetailRow['rate'], $_SESSION['CompanyRecord']['decimalplaces']);
							$Credit = '&nbsp;';
						}
					} else {
						if ($TransRow['account'] == $_SESSION['CompanyRecord']['debtorsact']) {
							$Credit = locale_number_format(-($DetailRow['ovamount'] + $DetailRow['ovgst'] + $DetailRow['ovfreight'] + $DetailRow['ovdiscount']) / $DetailRow['rate'], $_SESSION['CompanyRecord']['decimalplaces']);
							$Debit = '&nbsp;';
						} else {
							$Credit = locale_number_format(($DetailRow['ovamount'] + $DetailRow['ovgst']) / $DetailRow['rate'], $_SESSION['CompanyRecord']['decimalplaces']);
							$Debit = '&nbsp;';
						}
					}

					echo '<tr class="striped_row">
							<td>' . MonthAndYearFromSQLDate($TransRow['lastdate_in_period']) . '</td>
							<td>' . $TranDate . '</td>
							<td><a href="' . $URL . $DetailRow['otherpartycode'] . $FromDate . '">' . $TransRow['accountname'] . ' - ' . $DetailRow['otherparty'] . '</a></td>
							<td>' . $TransRow['narrative'] . '</td>
							<td class="number">' . $Debit . '</td>
							<td class="number">' . $Credit . '</td>
							<td>' . $Posted . '</td>
						</tr>';
				}
				DB_free_result($DetailResult);
				$AnalysisCompleted = 'Done';
			}
		}
		DB_free_result($TransResult);

		echo '<tr style="background-color:#FFFFFF">
				<td class="number" colspan="4"><b>' . _('Total') . '</b></td>
				<td class="number"><b>' . locale_number_format(($DebitTotal), $_SESSION['CompanyRecord']['decimalplaces']) . '</b></td>
				<td class="number"><b>' . locale_number_format((-$CreditTotal), $_SESSION['CompanyRecord']['decimalplaces']) . '</b></td>
				<td>&nbsp;</td>
			</tr>
			</table>';
	}

}

include ('includes/footer.php');
?>

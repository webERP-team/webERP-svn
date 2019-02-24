<?php
// GLAccountInquiry.php
// Shows the general ledger transactions for a specified account over a specified range of periods.

include ('includes/session.php');
$Title = _('General Ledger Account Inquiry');
$ViewTopic = 'GeneralLedger';
$BookMark = 'GLAccountInquiry';
include('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/transactions.png" title="',// Icon image.
	_('General Ledger Account Inquiry'), '" /> ',// Icon title.
	_('General Ledger Account Inquiry'), '</p>';// Page title.

include('includes/GLPostings.inc');

if(isset($_POST['Account'])) {
	$SelectedAccount = $_POST['Account'];
} elseif(isset($_GET['Account'])) {
	$SelectedAccount = $_GET['Account'];
}

if(isset($_POST['Period'])) {
	$SelectedPeriod = $_POST['Period'];
} elseif(isset($_GET['Period'])) {
	$SelectedPeriod = array($_GET['Period']);
}

if(isset($_GET['Show'])) {
	$_POST['Show'] = $_GET['Show'];
}

/* Get the start and periods, depending on how this script was called*/
if(isset($SelectedPeriod)) { //If it was called from itself (in other words an inquiry was run and we wish to leave the periods selected unchanged
	$FirstPeriodSelected = min($SelectedPeriod);
	$LastPeriodSelected = max($SelectedPeriod);
} elseif(isset($_GET['PeriodFrom'])) { //If it was called from the Trial Balance/P&L or Balance sheet
	$FirstPeriodSelected = $_GET['PeriodFrom'];
	$LastPeriodSelected = $_GET['PeriodTo'];
	$SelectedPeriod[0] = $_GET['PeriodFrom'];
	$SelectedPeriod[1] = $_GET['PeriodTo'];
} else { // Otherwise just highlight the current period
	$FirstPeriodSelected = GetPeriod(date($_SESSION['DefaultDateFormat']));
	$LastPeriodSelected = GetPeriod(date($_SESSION['DefaultDateFormat']));
}

echo '<div class="page_help_text noprint">' . _('Use the keyboard Shift key to select multiple periods') . '</div><br />';
echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div class="noprint">';// Begin input of criteria div.
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/*Dates in SQL format for the last day of last month*/
$DefaultPeriodDate = Date ('Y-m-d', Mktime(0,0,0,Date('m'),0,Date('Y')));

/*Show a form to allow input of criteria for TB to show */
echo '<table class="selection">
		<tr>
			<td>' . _('Account').':</td>
			<td><select name="Account">';

$sql = "SELECT chartmaster.accountcode,
			bankaccounts.accountcode AS bankact,
			bankaccounts.currcode,
			chartmaster.accountname
		FROM chartmaster LEFT JOIN bankaccounts
		ON chartmaster.accountcode=bankaccounts.accountcode
		INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
		ORDER BY chartmaster.accountcode";
$Account = DB_query($sql);
while($myrow=DB_fetch_array($Account)) {
	if($myrow['accountcode'] == $SelectedAccount) {
		if(!is_null($myrow['bankact'])) {
			$BankAccount = true;
		}
		echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' ' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' ' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
	}
 }
echo '</select></td>
	</tr>';

//Select the tag
echo '<tr>
		<td>' . _('Select Tag') . ':</td>
		<td><select name="tag">';

$SQL = "SELECT tagref,
			tagdescription
		FROM tags
		ORDER BY tagref";

$result=DB_query($SQL);
echo '<option value="0">0 - '._('All tags') . '</option>';

while($myrow=DB_fetch_array($result)) {
	if(isset($_POST['tag']) and $_POST['tag']==$myrow['tagref']) {
		echo '<option selected="selected" value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	} else {
		echo '<option value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	}
}
echo '</select></td>
	</tr>';
// End select tag
echo '<tr>
		<td>' . _('For Period range').':</td>
		<td><select name="Period[]" size="12" multiple="multiple">';

$sql = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno DESC";
$Periods = DB_query($sql);
while($myrow=DB_fetch_array($Periods)) {
	if(isset($FirstPeriodSelected) AND $myrow['periodno'] >= $FirstPeriodSelected AND $myrow['periodno'] <= $LastPeriodSelected) {
		echo '<option selected="selected" value="' . $myrow['periodno'] . '">' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period'])) . '</option>';
	} else {
		echo '<option value="' . $myrow['periodno'] . '">' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period'])) . '</option>';
	}
}
echo '</select></td>
	</tr>
	</table>
	<br />
	<div class="centre">
		<input type="submit" name="Show" value="'._('Show Account Transactions').'" />
	</div>
	</div>',// End input of criteria div.
	'</form>';

/* End of the Form  rest of script is what happens if the show button is hit*/

if(isset($_POST['Show'])) {

	if(!isset($SelectedPeriod)) {
		prnMsg(_('A period or range of periods must be selected from the list box'),'info');
		include('includes/footer.php');
		exit;
	}
	/*Is the account a balance sheet or a profit and loss account */
	$result = DB_query("SELECT pandl
				FROM accountgroups
				INNER JOIN chartmaster ON accountgroups.groupname=chartmaster.group_
				WHERE chartmaster.accountcode='" . $SelectedAccount ."'");
	$PandLRow = DB_fetch_row($result);
	if($PandLRow[0]==1) {
		$PandLAccount = True;
	} else {
		$PandLAccount = False; /*its a balance sheet account */
	}

	$FirstPeriodSelected = min($SelectedPeriod);
	$LastPeriodSelected = max($SelectedPeriod);

	$sql= "SELECT counterindex,
				type,
				typename,
				gltrans.typeno,
				trandate,
				narrative,
				amount,
				periodno,
				gltrans.tag,
				tagdescription
			FROM gltrans INNER JOIN systypes
			ON systypes.typeid=gltrans.type
			LEFT JOIN tags
			ON gltrans.tag = tags.tagref
			WHERE gltrans.account = '" . $SelectedAccount . "'
			AND posted=1
			AND periodno>='" . $FirstPeriodSelected . "'
			AND periodno<='" . $LastPeriodSelected . "'";

	if($_POST['tag']!=0) {
 		$sql = $sql . " AND tag='" . $_POST['tag'] . "'";
	}

	$sql = $sql . " ORDER BY periodno, gltrans.trandate, counterindex";
	$namesql = "SELECT accountname FROM chartmaster WHERE accountcode='" . $SelectedAccount . "'";
	$nameresult = DB_query($namesql);
	$namerow=DB_fetch_array($nameresult);
	$SelectedAccountName=$namerow['accountname'];
	$ErrMsg = _('The transactions for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved because') ;
	$TransResult = DB_query($sql,$ErrMsg);
	$BankAccountInfo = isset($BankAccount)
						?	'<th>' . _('Org Currency') . '</th>
							<th>' . _('Amount in Org Currency') . '</th>
							<th>' . _('Bank Ref') . '</th>'
						:	'' ;
	echo '<br />
		<table class="selection">
		<thead>
			<tr>
				<th colspan="11"><b>', _('Transactions for account'), ' ', $SelectedAccount, ' - ', $SelectedAccountName, '</b></th>
			</tr>
			<tr>
				<th>', _('Type'), '</th>
				<th>', _('Number'), '</th>
				<th>', ('Date'), '</th>
				<th>', _('Narrative'), '</th>
				<th>', _('Debit'), '</th>
				<th>', _('Credit'), '</th>
				<th>', _('Balance'), '</th>
				<th>', _('Tag'), '</th>',
				$BankAccountInfo, '
			</tr>
		</thead><tbody>';

	if($PandLAccount==True) {
		$RunningTotal = 0;
	} else {
		// added to fix bug with Brought Forward Balance always being zero
		$Sql = "SELECT bfwd,
					actual,
					period
				FROM chartdetails
				WHERE chartdetails.accountcode='" . $SelectedAccount . "'
				AND chartdetails.period='" . $FirstPeriodSelected . "'";
		$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
		$ChartDetailsResult = DB_query($Sql, $ErrMsg);
		$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
		$RunningTotal = $ChartDetailRow['bfwd'];
		echo	'<tr>
					<td colspan="4"><b>', _('Brought Forward Balance'), '</b></td>';
		if($RunningTotal < 0 ) {// It is a credit balance b/fwd
			echo	'<td>&nbsp;</td>
					<td class="number"><b>', locale_number_format(-$RunningTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</b></td>';
		} else {// It is a debit balance b/fwd
			echo	'<td class="number"><b>', locale_number_format($RunningTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</b></td>
					<td>&nbsp;</td>';
		}
		echo		'<td colspan="5">&nbsp;</td>
				</tr>';
	}
	$PeriodTotal = 0;
	$PeriodNo = -9999;
	$ShowIntegrityReport = False;
	$j = 1;
	$IntegrityReport='';
	while($myrow=DB_fetch_array($TransResult)) {
		if($myrow['periodno']!=$PeriodNo) {
			if($PeriodNo!=-9999) { //ie its not the first time around
				/*Get the ChartDetails balance b/fwd and the actual movement in the account for the period as recorded in the chart details - need to ensure integrity of transactions to the chart detail movements. Also, for a balance sheet account it is the balance carried forward that is important, not just the transactions*/

				$sql = "SELECT bfwd,
						actual,
						period
					FROM chartdetails
					WHERE chartdetails.accountcode='" . $SelectedAccount . "'
					AND chartdetails.period='" . $PeriodNo . "'";

				$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
				$ChartDetailsResult = DB_query($sql,$ErrMsg);
				$ChartDetailRow = DB_fetch_array($ChartDetailsResult);

				echo '<tr>
					<td colspan="4"><b>' . _('Total for period') . ' ' . $PeriodNo . '</b></td>';
				if($PandLAccount==True) {
					$RunningTotal = 0;
				}
				if($PeriodTotal < 0 ) {// It is a credit balance b/fwd
					echo	'<td>&nbsp;</td>
							<td class="number"><b>', locale_number_format(-$PeriodTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</b></td>';
				} else {// It is a debit balance b/fwd
					echo	'<td class="number"><b>', locale_number_format($PeriodTotal,$_SESSION['CompanyRecord']['decimalplaces']), '</b></td>
							<td>&nbsp;</td>';
				}
				echo		'<td colspan="5">&nbsp;</td>
						</tr>';
				$IntegrityReport .= '<br />' . _('Period') . ': ' . $PeriodNo  . _('Account movement per transaction') . ': '  . locale_number_format($PeriodTotal,$_SESSION['CompanyRecord']['decimalplaces']) . ' ' . _('Movement per ChartDetails record') . ': ' . locale_number_format($ChartDetailRow['actual'],$_SESSION['CompanyRecord']['decimalplaces']) . ' ' . _('Period difference') . ': ' . locale_number_format($PeriodTotal -$ChartDetailRow['actual'],3);

				if(ABS($PeriodTotal -$ChartDetailRow['actual'])>0.01) {
					$ShowIntegrityReport = True;
				}
			}
			$PeriodNo = $myrow['periodno'];
			$PeriodTotal = 0;
		}

		$BankRef = '';
		$OrgAmt = '';
		$Currency = '';
		if($myrow['type'] == 12 OR $myrow['type'] == 22 OR $myrow['type'] == 2 OR $myrow['type'] == 1) {
			$banksql = "SELECT ref,currcode,amount FROM banktrans
				WHERE type='" .$myrow['type']."' AND transno='" . $myrow['typeno'] . "' AND bankact='" . $SelectedAccount . "'";
			$ErrMsg = _('Failed to retrieve bank data');
			$bankresult = DB_query($banksql,$ErrMsg);
			if(DB_num_rows($bankresult)>0) {
				$bankrow = DB_fetch_array($bankresult);
				$BankRef = $bankrow['ref'];
				$OrgAmt = $bankrow['amount'];
				$Currency = $bankrow['currcode'];
			} elseif($myrow['type'] == 1) {
				//We should find out when transaction happens between bank accounts;
				$bankreceivesql = "SELECT ref,type,transno,currcode,amount FROM banktrans
							WHERE ref LIKE '@%' AND transdate='" . $myrow['trandate'] . "' AND bankact='" . $SelectedAccount . "'";
				$ErrMsg = _('Failed to retrieve bank receive data');
				$bankresult = DB_query($bankreceivesql,$ErrMsg);
				if(DB_num_rows($bankresult)>0) {
					while($bankrow = DB_fetch_array($bankresult)) {
						if(substr($bankrow['ref'],1,strpos($bankrow['ref'],' ')-1) == $myrow['typeno']) {
							$BankRef = $bankrow['ref'];
							$OrgAmt = $bankrow['amount'];
							$Currency = $bankrow['currcode'];
							$BankReceipt = true;
							break;
						}
					}
				}
				if(!isset($BankReceipt)) {
					$BankRef = '';
					$OrgAmt = $myrow['amount'];
					$Currency = $_SESSION['CompanyRecord']['currencydefault'];
				}

			} elseif(isset($BankAccount)) {
				$BankRef = '';
				$OrgAmt = $myrow['amount'];
				$Currency = $_SESSION['CompanyRecord']['currencydefault'];
			}
		}


		$URL_to_TransDetail = $RootPath . '/GLTransInquiry.php?TypeID=' . urlencode($myrow['type']) . '&amp;TransNo=' . urlencode($myrow['typeno']);
		$FormatedTranDate = ConvertSQLDate($myrow['trandate']);
		if($myrow['amount']>=0) {
			$DebitAmount = locale_number_format($myrow['amount'], $_SESSION['CompanyRecord']['decimalplaces']);
			$CreditAmount = '';
		} else {
			$CreditAmount = locale_number_format(-$myrow['amount'], $_SESSION['CompanyRecord']['decimalplaces']);
			$DebitAmount = '';
		}
		$RunningTotal += $myrow['amount'];
		$PeriodTotal += $myrow['amount'];
		echo	'<tr class="striped_row">
				<td class="text">', _($myrow['typename']), '</td>
				<td class="number"><a href="', $URL_to_TransDetail, '">', $myrow['typeno'], '</a></td>
				<td class="centre">', $FormatedTranDate, '</td>
				<td class="text">', $myrow['narrative'], '</td>
				<td class="number">', $DebitAmount, '</td>
				<td class="number">', $CreditAmount, '</td>
				<td class="number">', locale_number_format($RunningTotal, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="text">', $myrow['tagdescription'], '</td>';
		if(isset($BankAccount)) {
			echo '<td class="text">', $Currency, '</td>
				<td class="number"><b>', locale_number_format($OrgAmt, $_SESSION['CompanyRecord']['decimalplaces']), '</b></td>
				<td class="text">', $BankRef, '</td>';
		}
		echo '</tr>';
	}

	echo '<tr>
			<td colspan="4"><b>';
	if($PandLAccount==True) {
		echo _('Total Period Movement');/* RChacon: "Total for period XX"? */
	} else { /*its a balance sheet account*/
		echo _('Balance C/Fwd');
	}
	echo '</b></td>';
	if($RunningTotal < 0) {// It is a debit Total Period Movement or Balance C/Fwd
		echo	'<td>&nbsp;</td>
				<td class="number"><b>', locale_number_format((-$RunningTotal), $_SESSION['CompanyRecord']['decimalplaces']), '</b></td>';
	} else {// It is a credit Total Period Movement or Balance C/Fwd
		echo	'<td class="number"><b>', locale_number_format(($RunningTotal), $_SESSION['CompanyRecord']['decimalplaces']), '</b></td>
				<td>&nbsp;</td>';
	}
	echo	'<td colspan="5">&nbsp;</td>
		</tr>
		</tbody></table>';
} /* end of if Show button hit */

if(isset($ShowIntegrityReport) AND $ShowIntegrityReport==True AND $_POST['tag']=='0') {
	if(!isset($IntegrityReport)) {
		$IntegrityReport='';
	}
	prnMsg( _('There are differences between the sum of the transactions and the recorded movements in the ChartDetails table') . '. ' . _('A log of the account differences for the periods report shows below'),'warn');
	echo '<p>' . $IntegrityReport;
}
include('includes/footer.php');
?>

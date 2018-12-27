<?php
// BankMatching.php
// Allows payments and receipts to be matched off against bank statements.

include('includes/session.php');
$Title = _('Bank Matching');
$ViewTopic = 'GeneralLedger';
$BookMark = 'BankMatching';

include('includes/header.php');

if ((isset($_GET['Type']) AND $_GET['Type']=='Receipts')
		OR (isset($_POST['Type']) AND $_POST['Type']=='Receipts')) {

	$Type = 'Receipts';
	$TypeName =_('Receipts');
	echo '<p class="page_title_text"><img alt="" src="'.$RootPath.'/css/'.$Theme.
		'/images/bank.png" title="' .
		_('Bank Matching') . '" /> ' .// Icon title.
		_('Bank Account Matching - Receipts') . '</p>';// Page title.

} elseif ((isset($_GET['Type']) AND $_GET['Type']=='Payments')
			OR (isset($_POST['Type']) AND $_POST['Type']=='Payments')) {

	$Type = 'Payments';
	$TypeName =_('Payments');
	echo '<p class="page_title_text"><img alt="" src="'.$RootPath.'/css/'.$Theme.
		'/images/bank.png" title="' .
		_('Bank Matching') . '" /> ' .// Icon title.
		_('Bank Account Matching - Payments') . '</p>';// Page title.

} else {

	prnMsg(_('This page must be called with a bank transaction type') . '. ' . _('It should not be called directly'),'error');
	include ('includes/footer.php');
	exit;
}

if (isset($_GET['Account'])) {
	$_POST['BankAccount']=$_GET['Account'];
	$_POST['ShowTransactions']=true;
	$_POST['Ostg_or_All']='Ostg';
	$_POST['First20_or_All']='All';
}

if (isset($_POST['Update']) AND $_POST['RowCounter']>1) {
	for ($Counter=1;$Counter <= $_POST['RowCounter']; $Counter++) {
		if (isset($_POST['Clear_' . $Counter]) AND $_POST['Clear_' . $Counter]==True) {
			/*Get amount to be cleared */
			$SQL = "SELECT amount,
							exrate
						FROM banktrans
						WHERE banktransid='" . $_POST['BankTrans_' . $Counter]."'";
			$ErrMsg =  _('Could not retrieve transaction information');
			$result = DB_query($SQL,$ErrMsg);
			$MyRow=DB_fetch_array($result);
			$AmountCleared = round($MyRow[0] / $MyRow[1],2);
			/*Update the banktrans recoord to match it off */
			$SQL = "UPDATE banktrans SET amountcleared= ". $AmountCleared . "
									WHERE banktransid='" . $_POST['BankTrans_' . $Counter] . "'";
			$ErrMsg =  _('Could not match off this payment because');
			$result = DB_query($SQL,$ErrMsg);

		} elseif ((isset($_POST['AmtClear_' . $Counter])
					AND filter_number_format($_POST['AmtClear_' . $Counter])<0
					AND $Type=='Payments')
					OR ($Type=='Receipts' AND isset($_POST['AmtClear_' . $Counter])
					AND filter_number_format($_POST['AmtClear_' . $Counter])>0)) {

			/*if the amount entered was numeric and negative for a payment or positive for a receipt */

			$SQL = "UPDATE banktrans SET amountcleared=" .  filter_number_format($_POST['AmtClear_' . $Counter]) . "
					 WHERE banktransid='" . $_POST['BankTrans_' . $Counter]."'";

			$ErrMsg = _('Could not update the amount matched off this bank transaction because');
			$result = DB_query($SQL,$ErrMsg);

		} elseif (isset($_POST['Unclear_' . $Counter])
					AND $_POST['Unclear_' . $Counter]==True) {

			$SQL = "UPDATE banktrans SET amountcleared = 0
					 WHERE banktransid='" . $_POST['BankTrans_' . $Counter]."'";
			$ErrMsg =  _('Could not unclear this bank transaction because');
			$result = DB_query($SQL,$ErrMsg);
		}
	}
	/*Show the updated position with the same criteria as previously entered*/
	$_POST['ShowTransactions'] = True;
}

echo '<div class="page_help_text">' . _('Use this screen to match webERP Receipts and Payments to your Bank Statement.  Check your bank statement and click the check-box when you find the matching transaction.') . '</div><br />';

echo '<form action="'. htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<input type="hidden" name="Type" value="' . $Type . '" />';

echo '<table class="selection">
		<tr>
			<td align="left">' . _('Bank Account') . ':</td>
			<td colspan="3"><select tabindex="1" autofocus="autofocus" name="BankAccount">';

$SQL = "SELECT
			bankaccounts.accountcode,
			bankaccounts.bankaccountname,
			bankaccounts.currcode
		FROM bankaccounts, bankaccountusers
		WHERE bankaccounts.accountcode=bankaccountusers.accountcode
			AND bankaccountusers.userid = '" . $_SESSION['UserID'] ."'
		ORDER BY bankaccounts.bankaccountname";
$resultBankActs = DB_query($SQL);
while ($MyRow=DB_fetch_array($resultBankActs)) {
	// Lists bank accounts order by bankaccountname
	echo '<option',
		((isset($_POST['BankAccount']) and $_POST['BankAccount'] == $MyRow['accountcode']) ? ' selected="selected"' : '' ),
		' value="', $MyRow['accountcode'], '">', $MyRow['bankaccountname'], ' - ', $MyRow['currcode'], '</option>';
}
echo '</select></td>
	</tr>';

if (!isset($_POST['BeforeDate']) OR !Is_Date($_POST['BeforeDate'])) {
	$_POST['BeforeDate'] = Date($_SESSION['DefaultDateFormat']);
}
if (!isset($_POST['AfterDate']) OR !Is_Date($_POST['AfterDate'])) {
	$_POST['AfterDate'] = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,Date('m')-3,Date('d'),Date('y')));
}

// Change to allow input of FROM DATE and then TO DATE, instead of previous back-to-front method, add datepicker
echo '<tr>
		<td>' . _('Show') . ' ' . $TypeName . ' ' . _('from') . ':</td>
		<td><input tabindex="3" type="text" name="AfterDate" class="date" size="12" maxlength="10" required="required" onchange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' . $_POST['AfterDate'] . '" /></td>
	</tr>';

echo '<tr>
        <td>' . _('to') . ':</td>
		<td><input tabindex="2" type="text" name="BeforeDate" class="date" size="12" maxlength="10" required="required" onchange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' . $_POST['BeforeDate'] . '" /></td>
	</tr>';
echo '<tr>
		<td colspan="3">' . _('Choose outstanding') . ' ' . $TypeName . ' ' . _('only or all') . ' ' . $TypeName . ' ' . _('in the date range') . ':</td>
		<td><select tabindex="4" name="Ostg_or_All">';

if ($_POST['Ostg_or_All']=='All') {
	echo '<option selected="selected" value="All">' . _('Show all') . ' ' . $TypeName . ' ' . _('in the date range') . '</option>';
	echo '<option value="Ostdg">' . _('Show unmatched') . ' ' . $TypeName . ' ' . _('only') . '</option>';
} else {
	echo '<option value="All">' . _('Show all') . ' ' . $TypeName . ' ' . _('in the date range') . '</option>';
	echo '<option selected="selected" value="Ostdg">' . _('Show unmatched') . ' ' . $TypeName . ' ' . _('only') . '</option>';
}
echo '</select></td>
	</tr>';

echo '<tr>
	<td colspan="3">' . _('Choose to display only the first 20 matching') . ' ' . $TypeName . ' ' . _('or all') . ' ' . $TypeName . ' ' . _('meeting the criteria') . ':</td>
	<td><select tabindex="5" name="First20_or_All">';
if ($_POST['First20_or_All']=='All') {
	echo '<option selected="selected" value="All">' . _('Show all') . ' ' . $TypeName . ' ' . _('in the date range') . '</option>';
	echo '<option value="First20">' . _('Show only the first 20') . ' ' . $TypeName . '</option>';
} else {
	echo '<option value="All">' . _('Show all') . ' ' . $TypeName . ' ' . _('in the date range') . '</option>';
	echo '<option selected="selected" value="First20">' . _('Show only the first 20') . ' ' . $TypeName . '</option>';
}

echo '</select></td>
	</tr>';

echo '</table>
	<br />
	<div class="centre">
		<input tabindex="6" type="submit" name="ShowTransactions" value="' . _('Show selected') . ' ' . $TypeName . '" />';

if (isset($_POST['BankAccount'])) {
   echo '<p><a href="' . $RootPath . '/BankReconciliation.php?Account=' . $_POST['BankAccount'] . '">' . _('Show reconciliation') . '</a></p>';
}

echo '</div>';

$InputError=0;
if (!Is_Date($_POST['BeforeDate'])) {
	$InputError =1;
	prnMsg(_('The date entered for the field to show') . ' ' . $TypeName . ' ' . _('before') . ', ' .
		_('is not entered in a recognised date format') . '. ' . _('Entry is expected in the format') . ' ' .
		$_SESSION['DefaultDateFormat'],'error');
}
if (!Is_Date($_POST['AfterDate'])) {
	$InputError =1;
	prnMsg( _('The date entered for the field to show') . ' ' . $Type . ' ' . _('after') . ', ' .
		_('is not entered in a recognised date format') . '. ' . _('Entry is expected in the format') . ' ' .
		$_SESSION['DefaultDateFormat'],'error');
}

if ($InputError !=1
	AND isset($_POST['BankAccount'])
	AND $_POST['BankAccount']!=''
	AND isset($_POST['ShowTransactions'])) {

	$SQLBeforeDate = FormatDateForSQL($_POST['BeforeDate']);
	$SQLAfterDate = FormatDateForSQL($_POST['AfterDate']);

	$BankResult = DB_query("SELECT decimalplaces,
									currcode
							FROM bankaccounts INNER JOIN currencies
							ON bankaccounts.currcode=currencies.currabrev
							WHERE accountcode='" . $_POST['BankAccount'] . "'");
	$BankRow = DB_fetch_array($BankResult);
	$CurrDecimalPlaces = $BankRow['decimalplaces'];
	$CurrCode = $BankRow['currcode'];

	if ($_POST['Ostg_or_All']=='All') {
		if ($Type=='Payments') {
			$SQL = "SELECT banktransid,
							ref,
							amountcleared,
							transdate,
							amount/exrate as amt,
							banktranstype
					FROM banktrans
					WHERE amount < 0
						AND transdate >= '". $SQLAfterDate . "'
						AND transdate <= '" . $SQLBeforeDate . "'
						AND bankact='" . $_POST['BankAccount'] . "'
					ORDER BY transdate";

		} else { /* Type must == Receipts */
			$SQL = "SELECT banktransid,
							ref,
							amountcleared,
							transdate,
							amount/exrate as amt,
							banktranstype
						FROM banktrans
						WHERE amount > 0
							AND transdate >= '". $SQLAfterDate . "'
							AND transdate <= '" . $SQLBeforeDate . "'
							AND bankact='" . $_POST['BankAccount'] . "'
						ORDER BY transdate";
		}
	} else { /*it must be only the outstanding bank trans required */
		if ($Type=='Payments') {
			$SQL = "SELECT banktransid,
							ref,
							amountcleared,
							transdate,
							amount/exrate as amt,
							banktranstype
						FROM banktrans
						WHERE amount < 0
							AND transdate >= '". $SQLAfterDate . "'
							AND transdate <= '" . $SQLBeforeDate . "'
							AND bankact='" . $_POST['BankAccount'] . "'
							AND  ABS(amountcleared - (amount / exrate)) > 0.009
						ORDER BY transdate";
		} else { /* Type must == Receipts */
			$SQL = "SELECT banktransid,
							ref,
							amountcleared,
							transdate,
							amount/exrate as amt,
							banktranstype
						FROM banktrans
						WHERE amount > 0
							AND transdate >= '". $SQLAfterDate . "'
							AND transdate <= '" . $SQLBeforeDate . "'
							AND bankact='" . $_POST['BankAccount'] . "'
							AND  ABS(amountcleared - (amount / exrate)) > 0.009
						ORDER BY transdate";
		}
	}
	if ($_POST['First20_or_All']!='All') {
		$SQL = $SQL . " LIMIT 20";
	}

	$ErrMsg = _('The payments with the selected criteria could not be retrieved because');
	$PaymentsResult = DB_query($SQL, $ErrMsg);

	echo '<table cellpadding="2" class="selection">
			<thead>
			<tr>
				<th class="ascending">' .  _('Cheque No') . '</th>
				<th class="ascending">' . _('Ref') . '</th>
				<th class="ascending">' . _('Date') . '</th>
				<th class="ascending">' . $TypeName . '</th>
				<th class="ascending">' . _('Amount') . '</th>
				<th class="ascending">' . _('Outstanding') . '</th>
				<th colspan="3">' . _('Clear') . ' / ' . _('Unclear') . '</th>
				</tr>
			</thead>
			<tbody>';

	$i = 1; //no of rows counter

	while ($MyRow=DB_fetch_array($PaymentsResult)) {

		$DisplayTranDate = ConvertSQLDate($MyRow['transdate']);
		$Outstanding = $MyRow['amt']- $MyRow['amountcleared'];
		if (ABS($Outstanding)<0.009) { /*the payment is cleared dont show the check box*/

			printf('<tr class="striped_row">
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td colspan="2">%s</td>
						<td><input type="checkbox" name="Unclear_%s" /><input type="hidden" name="BankTrans_%s" value="%s" /></td>
					</tr>',
						$MyRow['ref'],
						$MyRow['banktranstype'],
						$DisplayTranDate,
						locale_number_format($MyRow['amt'],$CurrDecimalPlaces),
						locale_number_format($Outstanding,$CurrDecimalPlaces),
						_('Unclear'),
						$i,
						$i,
						$MyRow['banktransid']);

		} else{
			printf('<tr class="striped_row">
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td><input type="checkbox" name="Clear_%s" /><input type="hidden" name="BankTrans_%s" value="%s" /></td>
					<td colspan="2"><input type="text" maxlength="15" size="15" class="number" name="AmtClear_%s" /></td>
				</tr>',
					$MyRow['ref'],
					$MyRow['banktranstype'],
					$DisplayTranDate,
					locale_number_format($MyRow['amt'],$CurrDecimalPlaces),
					locale_number_format($Outstanding,$CurrDecimalPlaces),
					$i,
					$i,
					$MyRow['banktransid'],
					$i
			);
		}
		$i++;
	}
	//end of while loop
	echo '</tbody>
		</table>
			<br />
			<div class="centre">
				<input type="hidden" name="RowCounter" value="' . $i . '" />
				<input type="submit" name="Update" value="' . _('Update Matching') . '" />
			</div>';
}
echo '</div>';
echo '</form>';
include('includes/footer.php');
?>

<?php
// BankReconciliation.php
// Displays the bank reconciliation for a selected bank account.

include('includes/session.php');
$Title = _('Bank Reconciliation');
$ViewTopic= 'GeneralLedger';
$BookMark = 'BankAccounts';

include('includes/header.php');

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/bank.png" title="', // Icon image.
	$Title, '" /> ', // Icon title.
	$Title, '</p>';// Page title.

if (isset($_GET['Account'])) {
	$_POST['BankAccount']=$_GET['Account'];
	$_POST['ShowRec']=true;
}

if (isset($_POST['BankStatementBalance'])) {
	$_POST['BankStatementBalance'] = filter_number_format($_POST['BankStatementBalance']);
}

if (isset($_POST['PostExchangeDifference']) AND is_numeric(filter_number_format($_POST['DoExchangeDifference']))) {

	if (!is_numeric($_POST['BankStatementBalance'])) {
		prnMsg(_('The entry in the bank statement balance is not numeric. The balance on the bank statement should be entered. The exchange difference has not been calculated and no general ledger journal has been created'),'warn');
		echo '<br />' . $_POST['BankStatementBalance'];
	} else {

		/* Now need to get the currency of the account and the current table ex rate */
		$SQL = "SELECT rate,
						bankaccountname,
						decimalplaces AS currdecimalplaces
				FROM bankaccounts INNER JOIN currencies
				ON bankaccounts.currcode=currencies.currabrev
				WHERE bankaccounts.accountcode = '" . $_POST['BankAccount']."'";

		$ErrMsg = _('Could not retrieve the exchange rate for the selected bank account');
		$CurrencyResult = DB_query($SQL);
		$CurrencyRow =  DB_fetch_array($CurrencyResult);

		$CalculatedBalance = filter_number_format($_POST['DoExchangeDifference']);

		$ExchangeDifference = ($CalculatedBalance - filter_number_format($_POST['BankStatementBalance']))/$CurrencyRow['rate'];

		include ('includes/SQL_CommonFunctions.inc');
		$ExDiffTransNo = GetNextTransNo(36);
		/*Post the exchange difference to the last day of the month prior to current date*/
		$PostingDate = Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, Date('m'), 0,Date('Y')));
		$PeriodNo = GetPeriod($PostingDate);
		$result = DB_Txn_Begin();

//yet to code the journal

		$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount)
								  VALUES (36,
									'" . $ExDiffTransNo . "',
									'" . FormatDateForSQL($PostingDate) . "',
									'" . $PeriodNo . "',
									'" . $_SESSION['CompanyRecord']['exchangediffact'] . "',
									'" . $CurrencyRow['bankaccountname'] . ' ' . _('reconciliation on') . " " .
										Date($_SESSION['DefaultDateFormat']) . "','" . $ExchangeDifference . "')";

		$ErrMsg = _('Cannot insert a GL entry for the exchange difference because');
		$DbgMsg = _('The SQL that failed to insert the exchange difference GL entry was');
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount)
								  VALUES (36,
									'" . $ExDiffTransNo . "',
									'" . FormatDateForSQL($PostingDate) . "',
									'" . $PeriodNo . "',
									'" . $_POST['BankAccount'] . "',
									'" . $CurrencyRow['bankaccountname'] . ' ' . _('reconciliation on') . ' ' . Date($_SESSION['DefaultDateFormat']) . "',
									'" . (-$ExchangeDifference) . "')";

		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		$result = DB_Txn_Commit();
		prnMsg(_('Exchange difference of') . ' ' . locale_number_format($ExchangeDifference,$_SESSION['CompanyRecord']['decimalplaces']) . ' ' . _('has been posted'),'success');
	} //end if the bank statement balance was numeric
}

echo '<table class="selection">',
		'<tr><td>', _('Bank Account'), ':</td>
			<td><select name="BankAccount" tabindex="1">';

$SQL = "SELECT
			bankaccounts.accountcode,
			bankaccounts.bankaccountname,
			bankaccounts.currcode
		FROM bankaccounts, bankaccountusers
		WHERE bankaccounts.accountcode=bankaccountusers.accountcode
			AND bankaccountusers.userid = '" . $_SESSION['UserID'] ."'
		ORDER BY bankaccounts.bankaccountname";
$ErrMsg = _('The bank accounts could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve the bank accounts was');
$AccountsResults = DB_query($SQL, $ErrMsg, $DbgMsg);

if (DB_num_rows($AccountsResults)==0) {
	echo '</select></td>
			</tr>
		</table>';
	prnMsg(_('Bank Accounts have not yet been defined. You must first') . ' <a href="' . $RootPath . '/BankAccounts.php">' . _('define the bank accounts') . '</a> ' . _('and general ledger accounts to be affected'), 'warn');
	include ('includes/footer.php');
	exit;
} else {
	while ($MyRow=DB_fetch_array($AccountsResults)) {

		// Lists bank accounts order by name
		echo '<option',
			((isset($_POST['BankAccount']) and $_POST['BankAccount'] == $MyRow['accountcode']) ? ' selected="selected"' : '' ),
			' value="', $MyRow['accountcode'], '">', $MyRow['bankaccountname'], ' - ', $MyRow['currcode'], '</option>';


		/*list the bank account names */
		if (isset($_POST['BankAccount']) and $_POST['BankAccount']==$MyRow['accountcode']) {
			echo '<option selected="selected" value="' . $MyRow['accountcode'] . '">' . $MyRow['bankaccountname'] . '</option>';
		} else {
			echo '<option value="' . $MyRow['accountcode'] . '">' . $MyRow['bankaccountname'] . '</option>';
		}
	}
	echo '</select></td>
		</tr>';
}

/*Now do the posting while the user is thinking about the bank account to select */

include ('includes/GLPostings.inc');

echo '</table>
	<br />
	<div class="centre">
		<input type="submit" tabindex="2" name="ShowRec" value="' . _('Show bank reconciliation statement') . '" />
	</div>
	<br />';


if (isset($_POST['ShowRec']) OR isset($_POST['DoExchangeDifference'])) {

/*Get the balance of the bank account concerned */

	$PeriodNo = GetPeriod(date($_SESSION['DefaultDateFormat']));

	$SQL = "SELECT bfwd+actual AS balance
			FROM chartdetails
			WHERE period='" . $PeriodNo . "'
			AND accountcode='" . $_POST['BankAccount']."'";

	$ErrMsg = _('The bank account balance could not be returned by the SQL because');
	$BalanceResult = DB_query($SQL,$ErrMsg);

	$MyRow = DB_fetch_row($BalanceResult);
	$Balance = $MyRow[0];

	/* Now need to get the currency of the account and the current table ex rate */
	$SQL = "SELECT rate,
					bankaccounts.currcode,
					bankaccounts.bankaccountname,
					currencies.decimalplaces AS currdecimalplaces
			FROM bankaccounts INNER JOIN currencies
			ON bankaccounts.currcode=currencies.currabrev
			WHERE bankaccounts.accountcode = '" . $_POST['BankAccount']."'";
	$ErrMsg = _('Could not retrieve the currency and exchange rate for the selected bank account');
	$CurrencyResult = DB_query($SQL);
	$CurrencyRow =  DB_fetch_array($CurrencyResult);


	echo '<table class="selection">
			<tr class="striped_row">
				<td colspan="6"><b>' . $CurrencyRow['bankaccountname'] . ' ' . _('Balance as at') . ' ' . Date($_SESSION['DefaultDateFormat']);

	if ($_SESSION['CompanyRecord']['currencydefault']!=$CurrencyRow['currcode']) {
		echo  ' (' . $CurrencyRow['currcode'] . ' @ ' . $CurrencyRow['rate'] .')';
	}
	echo '</b></td>
			<td valign="bottom" class="number"><b>' . locale_number_format($Balance*$CurrencyRow['rate'],$CurrencyRow['currdecimalplaces']) . '</b></td></tr>';

	$SQL = "SELECT amount/exrate AS amt,
					amountcleared,
					(amount/exrate)-amountcleared as outstanding,
					ref,
					transdate,
					systypes.typename,
					transno
				FROM banktrans,
					systypes
				WHERE banktrans.type = systypes.typeid
				AND banktrans.bankact='" . $_POST['BankAccount'] . "'
				AND amount < 0
				AND ABS((amount/exrate)-amountcleared)>0.009 ORDER BY transdate";

	echo '<tr><td><br /></td></tr>'; /*Bang in a blank line */

	$ErrMsg = _('The unpresented cheques could not be retrieved by the SQL because');
	$UPChequesResult = DB_query($SQL, $ErrMsg);

	echo '<tr>
			<td colspan="6"><b>' . _('Add back unpresented cheques') . ':</b></td>
		</tr>';

	$TableHeader = '<tr>
						<th>' . _('Date') . '</th>
						<th>' . _('Type') . '</th>
						<th>' . _('Number') . '</th>
						<th>' . _('Reference') . '</th>
						<th>' . _('Orig Amount') . '</th>
						<th>' . _('Outstanding') . '</th>
					</tr>';

	echo $TableHeader;

	$j = 1;
	$TotalUnpresentedCheques =0;

	while ($MyRow=DB_fetch_array($UPChequesResult)) {
		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				</tr>',
				ConvertSQLDate($MyRow['transdate']),
				$MyRow['typename'],
				$MyRow['transno'],
				$MyRow['ref'],
				locale_number_format($MyRow['amt'],$CurrencyRow['currdecimalplaces']),
				locale_number_format($MyRow['outstanding'],$CurrencyRow['currdecimalplaces']));

		$TotalUnpresentedCheques +=$MyRow['outstanding'];

		$j++;
		If ($j == 18) {
			$j=1;
			echo $TableHeader;
		}
	}
	//end of while loop

	echo '<tr>
             <td><br /></td>
          </tr>
			<tr class="striped_row">
				<td colspan="6">' . _('Total of all unpresented cheques') . '</td>
				<td class="number">' . locale_number_format($TotalUnpresentedCheques,$CurrencyRow['currdecimalplaces']) . '</td>
			</tr>';

	$SQL = "SELECT amount/exrate AS amt,
				amountcleared,
				(amount/exrate)-amountcleared AS outstanding,
				ref,
				transdate,
				systypes.typename,
				transno
			FROM banktrans INNER JOIN systypes
			ON banktrans.type = systypes.typeid
			WHERE banktrans.bankact='" . $_POST['BankAccount'] . "'
			AND amount > 0
			AND ABS((amount/exrate)-amountcleared)>0.009 ORDER BY transdate";

	echo '<tr><td><br /></td></tr>'; /*Bang in a blank line */

	$ErrMsg = _('The uncleared deposits could not be retrieved by the SQL because');

	$UPChequesResult = DB_query($SQL,$ErrMsg);

	echo '<tr><td colspan="6"><b>' . _('Less deposits not cleared') . ':</b></td></tr>';

	$TableHeader = '<tr>
						<th>' . _('Date') . '</th>
						<th>' . _('Type') . '</th>
						<th>' . _('Number') . '</th>
						<th>' . _('Reference') . '</th>
						<th>' . _('Orig Amount') . '</th>
						<th>' . _('Outstanding') . '</th>
					</tr>';

	echo  $TableHeader;

	$j = 1;
	$TotalUnclearedDeposits =0;

	while ($MyRow=DB_fetch_array($UPChequesResult)) {
		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				</tr>',
				ConvertSQLDate($MyRow['transdate']),
				$MyRow['typename'],
				$MyRow['transno'],
				$MyRow['ref'],
				locale_number_format($MyRow['amt'],$CurrencyRow['currdecimalplaces']),
				locale_number_format($MyRow['outstanding'],$CurrencyRow['currdecimalplaces']) );

		$TotalUnclearedDeposits +=$MyRow['outstanding'];

		$j++;
		if ($j == 18) {
			$j=1;
			echo $TableHeader;
		}
	}
	//end of while loop
	echo '<tr>
            <td><br /></td>
		</tr>
		<tr class="striped_row">
			<td colspan="6">' . _('Total of all uncleared deposits') . '</td>
			<td class="number">' . locale_number_format($TotalUnclearedDeposits,$CurrencyRow['currdecimalplaces']) . '</td>
		</tr>';
	$FXStatementBalance = ($Balance*$CurrencyRow['rate'] - $TotalUnpresentedCheques -$TotalUnclearedDeposits);
	echo '<tr>
            <td><br /></td>
		</tr>
		<tr class="striped_row">
			<td colspan="6"><b>' . _('Bank statement balance should be') . ' (' . $CurrencyRow['currcode'] . ')</b></td>
			<td class="number">' . locale_number_format($FXStatementBalance,$CurrencyRow['currdecimalplaces']) . '</td>
		</tr>';

	if (isset($_POST['DoExchangeDifference'])) {
		echo '<input type="hidden" name="DoExchangeDifference" value="' . $FXStatementBalance . '" />';
		if (!isset($_POST['BankStatementBalance'])) {
			$_POST['BankStatementBalance'] =0;
		}
		echo '<tr>
				<td colspan="6">' . _('Enter the actual bank statement balance') . ' (' . $CurrencyRow['currcode'] . ')</b></td>
				<td class="number"><input type="text" name="BankStatementBalance" class="number" autofocus="autofocus" required="required" maxlength="15" size="15" value="' . locale_number_format($_POST['BankStatementBalance'],$CurrencyRow['currdecimalplaces']) . '" /><td>
			</tr>
			<tr>
				<td colspan="7" align="center"><input type="submit" name="PostExchangeDifference" value="' . _('Calculate and Post Exchange Difference') . '" onclick="return confirm(\'' . _('This will create a general ledger journal to write off the exchange difference in the current balance of the account. It is important that the exchange rate above reflects the current value of the bank account currency') . ' - ' . _('Are You Sure?') . '\');" /></td>
			</tr>';
	}

	if ($_SESSION['CompanyRecord']['currencydefault']!=$CurrencyRow['currcode'] AND !isset($_POST['DoExchangeDifference'])) {

		echo '<tr>
				<td colspan="7"><hr /></td>
			</tr>
			<tr>
				<td colspan="7">' . _('It is normal for foreign currency accounts to have exchange differences that need to be reflected as the exchange rate varies. This reconciliation is prepared using the exchange rate set up in the currencies table (see the set-up tab). This table must be maintained with the current exchange rate before running the reconciliation. If you wish to create a journal to reflect the exchange difference based on the current exchange rate to correct the reconciliation to the actual bank statement balance click below.') . '</td>
			</tr>
			<tr>
				<td colspan="7" align="center"><input type="submit" name="DoExchangeDifference" value="' . _('Calculate and Post Exchange Difference') . '" /></td>
			</tr>';
	}
	echo '</table>';
}


if (isset($_POST['BankAccount'])) {
	echo '<div class="centre">
			<p>
			<a tabindex="4" href="' . $RootPath . '/BankMatching.php?Type=Payments&amp;Account='.$_POST['BankAccount'].'">' . _('Match off cleared payments') . '</a>
			</p>
			<br />
			<a tabindex="5" href="' . $RootPath . '/BankMatching.php?Type=Receipts&amp;Account='.$_POST['BankAccount'].'">' . _('Match off cleared deposits') . '</a>
		</div>';
} else {
	echo '<div class="centre">
			<p>
			<a tabindex="4" href="' . $RootPath . '/BankMatching.php?Type=Payments">' . _('Match off cleared payments') . '</a>
			</p>
			<br />
			<a tabindex="5" href="' . $RootPath . '/BankMatching.php?Type=Receipts">' . _('Match off cleared deposits') . '</a>
		</div>';
}
echo '</div>';
echo '</form>';
include('includes/footer.php');
?>

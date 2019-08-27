<?php
// DailyBankTransactions.php
// Allows you to view all bank transactions for a selected date range, and the inquiry can be filtered by matched or unmatched transactions, or all transactions can be chosen.
include ('includes/session.php');
$Title = _('Bank Transactions Inquiry');
$ViewTopic = 'GeneralLedger';
$BookMark = 'DailyBankTransactions';

include ('includes/header.php');

if (isset($_GET['BankAccount'])) {
	$_POST['BankAccount'] = $_GET['BankAccount'];
	$_POST['ShowType'] = 'All';
	$_POST['Show'] = True;
}

if (isset($_GET['FromTransDate'])) {
	$_POST['FromTransDate'] = $_GET['FromTransDate'];
}

if (isset($_GET['ToTransDate'])) {
	$_POST['ToTransDate'] = $_GET['ToTransDate'];
}

if (!isset($_POST['Show'])) {

	$SQL = "SELECT
				bankaccounts.bankaccountname,
				bankaccounts.accountcode,
				bankaccounts.currcode
			FROM bankaccounts
			INNER JOIN chartmaster
				ON bankaccounts.accountcode=chartmaster.accountcode
			INNER JOIN bankaccountusers
				ON bankaccounts.accountcode=bankaccountusers.accountcode
			WHERE bankaccountusers.userid = '" . $_SESSION['UserID'] . "'
			ORDER BY bankaccounts.bankaccountname";
	$ErrMsg = _('The bank accounts could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the bank accounts was');
	$AccountsResults = DB_query($SQL, $ErrMsg, $DbgMsg);

	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/bank.png" title="', // Icon image.
	$Title, '" /> ', // Icon title.
	$Title, '</p>', // Page title.
	'<form action="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '" method="post">', '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />', '<table>
			<tr>
				<td>', _('Bank Account'), ':</td>
				<td><select name="BankAccount">';

	if (DB_num_rows($AccountsResults) == 0) {
		echo '</select></td>
				</tr>
			</table>';
		prnMsg(_('Bank Accounts have not yet been defined. You must first') . ' <a href="' . $RootPath . '/BankAccounts.php">' . _('define the bank accounts') . '</a> ' . _('and general ledger accounts to be affected'), 'warn');
		include ('includes/footer.php');
		exit;
	} else {
		while ($MyRow = DB_fetch_array($AccountsResults)) {
			// Lists bank accounts order by bankaccountname
			if (!isset($_POST['BankAccount']) and $MyRow['currcode'] == $_SESSION['CompanyRecord']['currencydefault']) {
				$_POST['BankAccount'] = $MyRow['accountcode'];
			}
			echo '<option', ((isset($_POST['BankAccount']) and $_POST['BankAccount'] == $MyRow['accountcode']) ? ' selected="selected"' : ''), ' value="', $MyRow['accountcode'], '">', $MyRow['bankaccountname'], ' - ', $MyRow['currcode'], '</option>';
		}
		echo '</select>
				</td>
			</tr>';
	}
	echo '<tr>
			<td>', _('Transactions Dated From'), ':</td>
			<td><input type="text" name="FromTransDate" class="date" required="required" maxlength="10" size="11" onchange="isDate(this, this.value, ', "'", $_SESSION['DefaultDateFormat'], "'", ')" value="', date($_SESSION['DefaultDateFormat']), '" /></td>
		</tr>
		<tr>
			<td>' . _('Transactions Dated To') . ':</td>
			<td><input type="text" name="ToTransDate" class="date" required="required" maxlength="10" size="11" onchange="isDate(this, this.value, ', "'", $_SESSION['DefaultDateFormat'], "'", ')" value="', date($_SESSION['DefaultDateFormat']), '" /></td>
		</tr>
		<tr>
			<td>', _('Show transactions'), '</td>
			<td>
				<select name="ShowType">
					<option value="All">', _('All'), '</option>
					<option value="Unmatched">', _('Unmatched'), '</option>
					<option value="Matched">', _('Matched'), '</option>
				</select>
			</td>
		</tr>
		</table>
		<div class="centre">
			<input type="submit" name="Show" value="', _('Show transactions'), '" />
		</div>
		</form>';
} else {
	$SQL = "SELECT 	bankaccountname,
					bankaccounts.currcode,
					currencies.decimalplaces
			FROM bankaccounts
			INNER JOIN currencies
				ON bankaccounts.currcode = currencies.currabrev
			WHERE bankaccounts.accountcode='" . $_POST['BankAccount'] . "'";
	$BankResult = DB_query($SQL, _('Could not retrieve the bank account details'));

	$BalancesSQL = "SELECT SUM(amount) AS balance,
							SUM(amount/functionalexrate/exrate) AS fbalance
						FROM banktrans
						WHERE bankact='" . $_POST['BankAccount'] . "'
							AND transdate<'" . FormatDateForSQL($_POST['FromTransDate']) . "'";
	$BalancesResult = DB_query($BalancesSQL);
	$BalancesRow = DB_fetch_array($BalancesResult);

	$SQL = "SELECT 	banktrans.currcode,
					banktrans.amount,
					banktrans.amountcleared,
					banktrans.functionalexrate,
					banktrans.exrate,
					banktrans.banktranstype,
					banktrans.transdate,
					banktrans.transno,
					banktrans.ref,
					banktrans.chequeno,
					bankaccounts.bankaccountname,
					systypes.typename,
					systypes.typeid,
					gltrans.narrative
				FROM banktrans
				INNER JOIN bankaccounts
					ON banktrans.bankact=bankaccounts.accountcode
				INNER JOIN systypes
					ON banktrans.type=systypes.typeid
				INNER JOIN gltrans
					ON banktrans.type=gltrans.type
					AND banktrans.transno=gltrans.typeno
					AND banktrans.amount=gltrans.amount
				WHERE bankact='" . $_POST['BankAccount'] . "'
					AND transdate>='" . FormatDateForSQL($_POST['FromTransDate']) . "'
					AND transdate<='" . FormatDateForSQL($_POST['ToTransDate']) . "'
				ORDER BY banktrans.transdate ASC,
						banktrans.banktransid ASC";
	$Result = DB_query($SQL);
	if (DB_num_rows($Result) == 0) {
		echo '<p class="page_title_text">
				<img alt="" src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/bank.png" title="', _('Bank Transactions Inquiry'), '" />', _('Bank Transactions Inquiry'), '
			</p>'; // Page title.
		prnMsg(_('There are no transactions for this account in the date range selected'), 'info');
	} else {
		$BankDetailRow = DB_fetch_array($BankResult);
		echo '<p class="page_title_text">
				<img alt="" src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/bank.png" title="', _('Bank Transactions Inquiry'), '" />', _('Account Transactions For'), ' ', $BankDetailRow['bankaccountname'], ' ', _('Between'), ' ', $_POST['FromTransDate'], ' ', _('and'), ' ', $_POST['ToTransDate'], '
			</p>'; // Page title.*/
		echo '<table>
				<thead>
					<tr>
						<th>' . _('Date') . '</th>
						<th>' . _('Transaction type') . '</th>
						<th>' . _('Number') . '</th>
						<th>' . _('Type') . '</th>
						<th>' . _('Reference') . '</th>
						<th>' . _('Narrative') . '</th>
						<th>' . _('Number') . '</th>
						<th>' . _('Amount in') . ' ' . $BankDetailRow['currcode'] . '</th>
						<th>' . _('Balance') . ' ' . $BankDetailRow['currcode'] . '</th>';
		if ($BankDetailRow['currcode'] != $_SESSION['CompanyRecord']['currencydefault']) {
			echo '<th>' . _('Amount in') . ' ' . $_SESSION['CompanyRecord']['currencydefault'] . '</th>
				<th>' . _('Balance') . ' ' . $_SESSION['CompanyRecord']['currencydefault'] . '</th>';
		}
		echo '<th>' . _('Matched') . '</th>
			</tr>
		</thead>';

		$AccountCurrTotal = $BalancesRow['balance'];
		$LocalCurrTotal = $BalancesRow['fbalance'];
		if ($BankDetailRow['currcode'] != $_SESSION['CompanyRecord']['currencydefault']) {
			echo '<tr class="striped_row">
					<td colspan="8">' . _('Balances Brought Forward') . '</td>
					<td class="number">' . locale_number_format($BalancesRow['balance'], $BankDetailRow['decimalplaces']) . '</td>
					<td></td>
					<td class="number">' . locale_number_format($BalancesRow['fbalance'], $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
					<td></td>
				</tr>';
		} else {
			echo '<tr class="striped_row">
					<td colspan="8">' . _('Balances Brought Forward') . '</td>
					<td class="number">' . locale_number_format($BalancesRow['balance'], $BankDetailRow['decimalplaces']) . '</td>
					<td></td>
				</tr>';
		}

		$RowCounter = 0;

		while ($MyRow = DB_fetch_array($Result)) {

			$AccountCurrTotal+= $MyRow['amount'];
			$LocalCurrTotal+= $MyRow['amount'] / $MyRow['functionalexrate'] / $MyRow['exrate'];

			if ($MyRow['amount'] == $MyRow['amountcleared']) {
				$Matched = _('Yes');
			} else {
				$Matched = _('No');
			}

			if ($_POST['ShowType'] == 'All' or ($_POST['ShowType'] == 'Unmatched' and $Matched == _('No')) or ($_POST['ShowType'] == 'Matched' and $Matched == _('Yes'))) {
				echo '<tr class="striped_row">
						<td>' . ConvertSQLDate($MyRow['transdate']) . '</td>
						<td>' . _($MyRow['typename']) . '</td>
						<td class="number"><a href="' . $RootPath . '/GLTransInquiry.php?TypeID=' . $MyRow['typeid'] . '&amp;TransNo=' . $MyRow['transno'] . '">' . $MyRow['transno'] . '</a></td>
						<td>' . $MyRow['banktranstype'] . '</td>
						<td>' . $MyRow['ref'] . '</td>
						<td>' . $MyRow['narrative'] . '</td>
						<td>' . $MyRow['chequeno'] . '</td>
						<td class="number">' . locale_number_format($MyRow['amount'], $BankDetailRow['decimalplaces']) . '</td>
						<td class="number">' . locale_number_format($AccountCurrTotal, $BankDetailRow['decimalplaces']) . '</td>';
				if ($BankDetailRow['currcode'] != $_SESSION['CompanyRecord']['currencydefault']) {
					echo '<td class="number">' . locale_number_format($MyRow['amount'] / $MyRow['functionalexrate'] / $MyRow['exrate'], $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
						<td class="number">' . locale_number_format($LocalCurrTotal, $_SESSION['CompanyRecord']['decimalplaces']) . '</td>';
				}
				echo '<td class="number">' . $Matched . '</td>
					</tr>';
			}
		}
		if ($BankDetailRow['currcode'] != $_SESSION['CompanyRecord']['currencydefault']) {
			echo '<tr class="striped_row">
					<td colspan="8">' . _('Balances Brought Forward') . '</td>
					<td class="number">' . locale_number_format($AccountCurrTotal, $BankDetailRow['decimalplaces']) . '</td>
					<td></td>
					<td class="number">' . locale_number_format($BalancesRow['fbalance'], $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
					<td></td>
				</tr>';
		} else {
			echo '<tr class="striped_row">
					<td colspan="8">' . _('Balances Brought Forward') . '</td>
					<td class="number">' . locale_number_format($LocalCurrTotal, $BankDetailRow['decimalplaces']) . '</td>
					<td></td>
				</tr>';
		}
		echo '</table>';
	} //end if no bank trans in the range to show
	echo '<form action="' . htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8') . '" method="post">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="centre"><input type="submit" name="Return" value="' . _('Select Another Date') . '" /></div>';
	echo '</form>';
}
include ('includes/footer.php');
?>
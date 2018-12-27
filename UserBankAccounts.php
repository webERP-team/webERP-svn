<?php
// UserBankAccounts.php
// Maintains table bankaccountusers (Authorized users to work with a bank account in webERP).

include('includes/session.php');
$Title = _('Bank Account Users');
$ViewTopic = 'GeneralLedger';
$BookMark = 'UserBankAccounts';
include('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/money_add.png" title="',// Icon image.
	_('User Authorised Bank Accounts'), '" /> ',// Icon title.
	_('Bank Account Users'), '</p>';// Page title.

if (isset($_POST['SelectedBankAccount'])) {
	$SelectedBankAccount = mb_strtoupper($_POST['SelectedBankAccount']);
} elseif (isset($_GET['SelectedBankAccount'])) {
	$SelectedBankAccount = mb_strtoupper($_GET['SelectedBankAccount']);
} else {
	$SelectedBankAccount = '';
}

if (isset($_POST['SelectedUser'])) {
	$SelectedUser = $_POST['SelectedUser'];
} elseif (isset($_GET['SelectedUser'])) {
	$SelectedUser = $_GET['SelectedUser'];
}

if (isset($_POST['Cancel'])) {
	unset($SelectedUser);
	unset($SelectedBankAccount);
}

if (isset($_POST['Process'])) {
	if ($_POST['SelectedUser'] == '') {
		prnMsg(_('You have not selected any User'), 'error');
		echo '<br />';
		unset($SelectedUser);
		unset($_POST['SelectedUser']);
	}
}

if (isset($_POST['submit'])) {

	$InputError = 0;

	if ($_POST['SelectedBankAccount'] == '') {
		$InputError = 1;
		prnMsg(_('You have not selected a bank account to be authorised for this user'), 'error');
		echo '<br />';
		unset($SelectedUser);
	}

	if ($InputError != 1) {

		// First check the user is not being duplicated

		$CheckSql = "SELECT count(*)
			     FROM bankaccountusers
			     WHERE accountcode= '" . $_POST['SelectedBankAccount'] . "'
				 AND userid = '" . $_POST['SelectedUser'] . "'";

		$CheckResult = DB_query($CheckSql);
		$CheckRow = DB_fetch_row($CheckResult);

		if ($CheckRow[0] > 0) {
			$InputError = 1;
			prnMsg(_('The Bank Account') . ' ' . $_POST['SelectedBankAccount'] . ' ' . _('is already authorised for this user'), 'error');
		} else {
			// Add new record on submit
			$SQL = "INSERT INTO bankaccountusers (accountcode,
												userid)
										VALUES ('" . $_POST['SelectedBankAccount'] . "',
												'" . $_POST['SelectedUser'] . "')";

			$msg = _('User') . ': ' . $_POST['SelectedUser'] . ' ' . _('authority to use the') . ' ' . $_POST['SelectedBankAccount'] . ' ' . _('bank account has been changed');
			$Result = DB_query($SQL);
			prnMsg($msg, 'success');
			unset($_POST['SelectedBankAccount']);
		}
	}
} elseif (isset($_GET['delete'])) {
	$SQL = "DELETE FROM bankaccountusers
		WHERE accountcode='" . $SelectedBankAccount . "'
		AND userid='" . $SelectedUser . "'";

	$ErrMsg = _('The Bank account user record could not be deleted because');
	$Result = DB_query($SQL, $ErrMsg);
	prnMsg(_('User') . ' ' . $SelectedUser . ' ' . _('has had their authority to use the') . ' ' . $SelectedBankAccount . ' ' . _('bank account removed'), 'success');
	unset($_GET['delete']);
}

if (!isset($SelectedUser)) {

	/* It could still be the second time the page has been run and a record has been selected for modification - SelectedBankAccount will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
	then none of the above are true. These will call the same page again and allow update/input or deletion of the records*/
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<table class="selection">
			<tr>
				<td>' . _('Select User') . ':</td>
				<td><select name="SelectedUser">';

	$Result = DB_query("SELECT userid,
								realname
						FROM www_users
						ORDER BY userid");

	echo '<option value="">' . _('Not Yet Selected') . '</option>';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($SelectedUser) and $MyRow['userid'] == $SelectedUser) {
			echo '<option selected="selected" value="';
		} else {
			echo '<option value="';
		}
		echo $MyRow['userid'] . '">' . $MyRow['userid'] . ' - ' . $MyRow['realname'] . '</option>';

	} //end while loop

	echo '</select></td></tr>';

	echo '</table>'; // close main table
	DB_free_result($Result);

	echo '<div class="centre">
			<input type="submit" name="Process" value="' . _('Accept') . '" />
			<input type="submit" name="Cancel" value="' . _('Cancel') . '" />
		</div>';

	echo '</form>';

}

//end of ifs and buts!
if (isset($_POST['process']) or isset($SelectedUser)) {
	$SQLName = "SELECT realname
			FROM www_users
			WHERE userid='" . $SelectedUser . "'";
	$Result = DB_query($SQLName);
	$MyRow = DB_fetch_array($Result);
	$SelectedUserName = $MyRow['realname'];

	echo '<div class="centre"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">' . _('Authorised bank accounts for') . ' ' . $SelectedUserName . '</a></div>
		<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<input type="hidden" name="SelectedUser" value="' . $SelectedUser . '" />';

	$SQL = "SELECT bankaccountusers.accountcode,
					bankaccounts.bankaccountname
			FROM bankaccountusers INNER JOIN bankaccounts
			ON bankaccountusers.accountcode=bankaccounts.accountcode
			WHERE bankaccountusers.userid='" . $SelectedUser . "'
			ORDER BY bankaccounts.bankaccountname ASC";

	$Result = DB_query($SQL);

	echo '<table class="selection">';
	echo '<tr>
			<th colspan="6"><h3>' . _('Authorised bank accounts for User') . ': ' . $SelectedUserName . '</h3></th>
		</tr>';
	echo '<tr>
			<th>' . _('Code') . '</th>
			<th>' . _('Name') . '</th>
		</tr>';

	while ($MyRow = DB_fetch_array($Result)) {

		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td><a href="%s?SelectedBankAccount=%s&amp;delete=yes&amp;SelectedUser=' . $SelectedUser . '" onclick="return confirm(\'' . _('Are you sure you wish to un-authorise this bank account?') . '\');">' . _('Un-authorise') . '</a></td>
				</tr>',
				$MyRow['accountcode'],
				$MyRow['bankaccountname'],
				htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'),
				$MyRow['accountcode'],
				htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'),
				$MyRow['accountcode']);
	}
	//END WHILE LIST LOOP
	echo '</table>';

	if (!isset($_GET['delete'])) {


		echo '<table  class="selection">'; //Main table

		echo '<tr>
				<td>' . _('Select Bank Account') . ':</td>
				<td><select name="SelectedBankAccount">';

		$Result = DB_query("SELECT
								accountcode,
								bankaccountname,
								currcode
							FROM bankaccounts
							WHERE NOT EXISTS (SELECT bankaccountusers.accountcode
											FROM bankaccountusers
											WHERE bankaccountusers.userid='" . $SelectedUser . "'
												AND bankaccountusers.accountcode=bankaccounts.accountcode)
							ORDER BY bankaccountname");

		if (!isset($_POST['SelectedBankAccount'])) {
			echo '<option selected="selected" value="">' . _('Not Yet Selected') . '</option>';
		}
		while ($MyRow = DB_fetch_array($Result)) {
			// Lists bank accounts order by bankaccountname
			echo '<option',
				((isset($_POST['SelectedBankAccount']) and $MyRow['accountcode'] == $_POST['SelectedBankAccount']) ? ' selected="selected"' : '' ),
				' value="', $MyRow['accountcode'], '">', $MyRow['accountcode'], ' - ', $MyRow['bankaccountname'], ' - ', $MyRow['currcode'], '</option>';
		}// End while loop

		echo '</select>
					</td>
				</tr>
			</table>'; // close main table
		DB_free_result($Result);

		echo '<div class="centre">
				<input type="submit" name="submit" value="' . _('Accept') . '" />
				<input type="submit" name="Cancel" value="' . _('Cancel') . '" />
			</div>
			</form>';

	} // end if user wish to delete
}

include('includes/footer.php');
?>
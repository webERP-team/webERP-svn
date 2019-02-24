<?php
// PcExpenses.php
// .

include('includes/session.php');
$ViewTopic = 'PettyCash';
$BookMark = 'PCExpenses';
$Title = _('Maintenance Of Petty Cash Of Expenses');
include('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/money_add.png" title="', // Icon image.
	_('Payment Entry'), '" /> ', // Icon title.
	$Title, '</p>';// Page title.

if (isset($_POST['SelectedExpense'])) {
	$SelectedExpense = mb_strtoupper($_POST['SelectedExpense']);
} elseif (isset($_GET['SelectedExpense'])) {
	$SelectedExpense = mb_strtoupper($_GET['SelectedExpense']);
}
if (isset($_POST['Cancel'])) {
	unset($SelectedExpense);
	unset($_POST['CodeExpense']);
	unset($_POST['Description']);
	unset($_POST['GLAccount']);
	unset($_POST['Tag']);
}
if (isset($Errors)) {
	unset($Errors);
}
if (isset($_POST['submit'])) {
	//initialise no input errors assumed initially before we test
	$InputError = 0;
	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */
	//first off validate inputs sensible
	if ($_POST['CodeExpense'] == '' or $_POST['CodeExpense'] == ' ' or $_POST['CodeExpense'] == '  ') {
		$InputError = 1;
		prnMsg(_('The Expense type  code cannot be an empty string or spaces'), 'error');
	} elseif (mb_strlen($_POST['CodeExpense']) > 20) {
		$InputError = 1;
		prnMsg(_('The expense code must be twenty characters or less long'), 'error');
	} elseif (ContainsIllegalCharacters($_POST['CodeExpense'])) {
		$InputError = 1;
		prnMsg(_('The expense code cannot contain any of the following characters ') . '" \' - &amp;', 'error');
	} elseif (ContainsIllegalCharacters($_POST['Description'])) {
		$InputError = 1;
		prnMsg(_('The expense description cannot contain any of the following characters ') . '" \' - &amp;', 'error');
	} elseif (mb_strlen($_POST['Description']) > 50) {
		$InputError = 1;
		prnMsg(_('The tab code must be fifty characters or less long'), 'error');
	} elseif (mb_strlen($_POST['Description']) == 0) {
		$InputError = 1;
		echo prnMsg(_('The tab code description must be entered'), 'error');
	} elseif ($_POST['GLAccount'] == '') {
		$InputError = 1;
	} elseif ($_POST['TaxCategory'] === '0') {
		$InputError = 1;
		echo prnMsg(_('A tax category must be selected from the list'), 'error');
	}
	if (isset($SelectedExpense) and $InputError != 1) {
		$SQL = "UPDATE pcexpenses
				SET description = '" . $_POST['Description'] . "',
					glaccount = '" . $_POST['GLAccount'] . "',
					tag = '" . $_POST['Tag'] . "',
					taxcatid='" . $_POST['TaxCategory'] . "'
				WHERE codeexpense = '" . $SelectedExpense . "'";
		$Msg = _('The Expenses type') . ' ' . $SelectedExpense . ' ' . _('has been updated');
	} elseif ($InputError != 1) {
		// First check the type is not being duplicated
		$CheckSQL = "SELECT count(*)
				 FROM pcexpenses
				 WHERE codeexpense = '" . $_POST['CodeExpense'] . "'";
		$CheckResult = DB_query($CheckSQL);
		$CheckRow = DB_fetch_row($CheckResult);
		if ($CheckRow[0] > 0) {
			$InputError = 1;
			prnMsg(_('The Expense type ') . $_POST['CodeExpense'] . _(' already exists'), 'error');
		} else {
			// Add new record on submit
			$SQL = "INSERT INTO pcexpenses
						(codeexpense,
			 			 description,
			 			 glaccount,
			 			 tag,
			 			 taxcatid)
				VALUES ('" . $_POST['CodeExpense'] . "',
						'" . $_POST['Description'] . "',
						'" . $_POST['GLAccount'] . "',
						'" . $_POST['Tag'] . "',
						'" . $_POST['TaxCategory'] . "'
						)";
			$Msg = _('Expense') . ' ' . $_POST['CodeExpense'] . ' ' . _('has been created');
			$CheckSQL = "SELECT count(codeexpense)
						FROM pcexpenses";
			$Result = DB_query($CheckSQL);
			$row = DB_fetch_row($Result);
		}
	}
	if ($InputError != 1) {
		//run the SQL from either of the above possibilites
		$Result = DB_query($SQL);
		prnMsg($Msg, 'success');
		unset($SelectedExpense);
		unset($_POST['CodeExpense']);
		unset($_POST['Description']);
		unset($_POST['GLAccount']);
		unset($_POST['Tag']);
		unset($_POST['TaxGroup']);
	}
} elseif (isset($_GET['delete'])) {
	// PREVENT DELETES IF DEPENDENT RECORDS IN 'PcTabExpenses'
	$SQL = "SELECT COUNT(*)
		   FROM pctabexpenses
		   WHERE codeexpense='" . $SelectedExpense . "'";
	$ErrMsg = _('The number of type of tabs using this expense code could not be retrieved');
	$Result = DB_query($SQL, $ErrMsg);
	$MyRow = DB_fetch_row($Result);
	if ($MyRow[0] > 0) {
		prnMsg(_('Cannot delete this petty cash expense because it is used in some tab types') . '<br />' . _('There are') . ' ' . $MyRow[0] . ' ' . _('tab types using this expense code'), 'error');
	} else {
		$SQL = "DELETE FROM pcexpenses
				  WHERE codeexpense='" . $SelectedExpense . "'";
		$ErrMsg = _('The expense type record could not be deleted because');
		$Result = DB_query($SQL, $ErrMsg);
		prnMsg(_('Expense type') . ' ' . $SelectedExpense . ' ' . _('has been deleted'), 'success');
		unset($SelectedExpense);
		unset($_GET['delete']);
	} //end if tab type used in transactions
}
if (!isset($SelectedExpense)) {
	/* It could still be the second time the page has been run and a record has been selected for modification - SelectedExpense will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
	then none of the above are true and the list of sales types will be displayed with
	links to delete or edit each. These will call the same page again and allow update/input
	or deletion of the records*/
	$SQL = "SELECT codeexpense,
					description,
					glaccount,
					tag,
					taxcatid
			FROM pcexpenses";
	$Result = DB_query($SQL);
	echo '<table class="selection">
			<tr>
				<th>', _('Expense Code'), '</th>
				<th>', _('Description'), '</th>
				<th>', _('Account Code'), '</th>
				<th>', _('Account Description'), '</th>
				<th>', _('Tag'), '</th>
				<th>', _('Tax Category'), '</th>
			</tr>';

	while ($MyRow = DB_fetch_array($Result)) {
		$SQLdesc = "SELECT accountname
					FROM chartmaster
					WHERE accountcode='" . $MyRow['glaccount'] . "'";
		$ResultDes = DB_query($SQLdesc);
		$Description = DB_fetch_array($ResultDes);
		$SqlDescTag = "SELECT tagdescription
					FROM tags
					WHERE tagref='" . $MyRow['tag'] . "'";
		$ResultDesTag = DB_query($SqlDescTag);
		$DescriptionTag = DB_fetch_array($ResultDesTag);
		$SqlTaxCat = "SELECT taxcatname
					FROM taxcategories
					WHERE taxcatid='" . $MyRow['taxcatid'] . "'";
		$ResultTaxCat = DB_query($SqlTaxCat);
		$DescriptionTaxCat = DB_fetch_array($ResultTaxCat);
		echo '<tr class="striped_row">
				<td>', $MyRow['codeexpense'], '</td>
				<td>', $MyRow['description'], '</td>
				<td class="number">', $MyRow['glaccount'], '</td>
				<td>', $Description['accountname'], '</td>
				<td>', $DescriptionTag['tagdescription'], '</td>
				<td>', $DescriptionTaxCat['taxcatname'], '</td>
				<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?SelectedExpense=', $MyRow['codeexpense'], '">', _('Edit'), '</a></td>
				<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?SelectedExpense=', $MyRow['codeexpense'], '&amp;delete=yes" onclick="return MakeConfirm(\'' . _('Are you sure you wish to delete this expense code and all the details it may have set up?') . '\', \'Confirm Delete\', this);">' . _('Delete') . '</a></td>
			</tr>';
	}
	//END WHILE LIST LOOP
	echo '</table>';
}
//end of ifs and buts!
if (isset($SelectedExpense)) {
	echo '<div class="centre">
			<a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">', _('Show All Petty Cash Expenses Defined'), '</a>
		</div>';
}
if (!isset($_GET['delete'])) {
	echo '<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">';
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	// The user wish to EDIT an existing type
	if (isset($SelectedExpense) and $SelectedExpense != '') {
		$SQL = "SELECT codeexpense,
						description,
						glaccount,
						tag,
						taxcatid
					FROM pcexpenses
					WHERE codeexpense='" . $SelectedExpense . "'";
		$Result = DB_query($SQL);
		$MyRow = DB_fetch_array($Result);
		$_POST['CodeExpense'] = $MyRow['codeexpense'];
		$_POST['Description'] = $MyRow['description'];
		$_POST['GLAccount'] = $MyRow['glaccount'];
		$_POST['Tag'] = $MyRow['tag'];
		$_POST['TaxCategory'] = $MyRow['taxcatid'];
		echo '<input type="hidden" name="SelectedExpense" value="', $SelectedExpense, '" />';
		echo '<input type="hidden" name="CodeExpense" value="', $_POST['CodeExpense'], '" />';
		// We dont allow the user to change an existing type code
		echo '<table class="selection">
				<tr>
					<td>', _('Expense Code'), ':</td>
					<td>', $_POST['CodeExpense'], '</td>
				</tr>';
	} else {
		// This is a new type so the user may volunteer a type code
		echo '<table class="selection">
				<tr>
					<td>', _('Expense Code'), ':</td>
					<td><input type="text" name="CodeExpense" autofocus="autofocus" required="required" maxlength="20" /></td>
				</tr>';
	}
	if (!isset($_POST['Description'])) {
		$_POST['Description'] = '';
	}
	echo '<tr>
			<td>', _('Description'), ':</td>
			<td><input type="text" name="Description" size="50" required="required" maxlength="50" value="', $_POST['Description'], '" /></td>
		</tr>';
	echo '<tr>
			<td>', _('Account Code'), ':</td>
			<td><select required="required" name="GLAccount">';
	DB_free_result($Result);
	$SQL = "SELECT accountcode,
				accountname
			FROM chartmaster
			ORDER BY accountcode";
	$Result = DB_query($SQL);
	echo '<option value="">', _('Not Yet Selected'), '</option>';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['GLAccount']) and $MyRow['accountcode'] == $_POST['GLAccount']) {
			echo '<option selected="selected" value="', $MyRow['accountcode'], '">', $MyRow['accountcode'], ' - ', htmlspecialchars($MyRow['accountname'], ENT_QUOTES, 'UTF-8', false), '</option>';
		} else {
			echo '<option value="', $MyRow['accountcode'], '">', $MyRow['accountcode'], ' - ', htmlspecialchars($MyRow['accountname'], ENT_QUOTES, 'UTF-8', false), '</option>';
		}
	} //end while loop
	echo '</select>
			</td>
		</tr>';
	//Select the tag
	echo '<tr>
			<td>', _('Tag'), ':</td>
			<td><select name="Tag">';
	$SQL = "SELECT tagref,
					tagdescription
			FROM tags
			ORDER BY tagref";
	$Result = DB_query($SQL);
	echo '<option value="0">0 - ', _('None'), '</option>';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['Tag']) and $_POST['Tag'] == $MyRow['tagref']) {
			echo '<option selected="selected" value="', $MyRow['tagref'], '">', $MyRow['tagref'], ' - ', $MyRow['tagdescription'], '</option>';
		} else {
			echo '<option value="', $MyRow['tagref'], '">', $MyRow['tagref'], ' - ', $MyRow['tagdescription'], '</option>';
		}
	}
	echo '</select>
			</td>
		</tr>';
	// End select tag
	$SQL = "SELECT taxcatid,
					taxcatname
				FROM taxcategories";
	$Result = DB_query($SQL);
	echo '<tr>
			<td>', _('Tax Category'), ':</td>
			<td><select name="TaxCategory">';
	echo '<option value="0">0 - ', _('None'), '</option>';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['TaxCategory']) and $_POST['TaxCategory'] == $MyRow['taxcatid']) {
			echo '<option selected="selected" value="', $MyRow['taxcatid'], '">', $MyRow['taxcatid'], ' - ', $MyRow['taxcatname'], '</option>';
		} else {
			echo '<option value="', $MyRow['taxcatid'], '">', $MyRow['taxcatid'], ' - ', $MyRow['taxcatname'], '</option>';
		}
	}
	echo '</select>
			</td>
		</tr>';
	echo '</table>'; // close main table
	echo '<div class="centre">
			<input type="submit" name="submit" value="', _('Accept'), '" />
			<input type="submit" name="Cancel" value="', _('Cancel'), '" />
		</div>';
	echo '</form>';
} // end if user wish to delete
include('includes/footer.php');
?>
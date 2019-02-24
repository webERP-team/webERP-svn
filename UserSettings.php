<?php
// UserSettings.php
// Allows the user to change system wide defaults for the theme - appearance, the number of records to show in searches and the language to display messages in.

include('includes/session.php');
$Title = _('User Settings');
$ViewTopic = 'GettingStarted';
$BookMark = 'UserSettings';

include('includes/header.php');
echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/user.png" title="', // Icon image.
	$Title, '" /> ', // Icon title.
	$Title, '</p>'; // Page title.

$PDFLanguages = array(
	_('Latin Western Languages - Times'),
	_('Eastern European Russian Japanese Korean Hebrew Arabic Thai'),
	_('Chinese'),
	_('Free Serif')
);

if(isset($_POST['Modify'])) {
	// no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	if($_POST['DisplayRecordsMax'] <= 0) {
		$InputError = 1;
		prnMsg(_('The Maximum Number of Records on Display entered must not be negative') . '. ' . _('0 will default to system setting'),'error');
	}

	//!!!for the demo only - enable this check so password is not changed
	if($AllowDemoMode AND $_POST['Password'] != '') {
		$InputError = 1;
		prnMsg(_('Cannot change password in the demo or others would be locked out!'),'warn');
	}

 	$UpdatePassword = 'N';

	if($_POST['PasswordCheck'] != '') {
		if(mb_strlen($_POST['Password']) < 5) {
			$InputError = 1;
			prnMsg(_('The password entered must be at least 5 characters long'),'error');
		} elseif(mb_strstr($_POST['Password'],$_SESSION['UserID'])!= False) {
			$InputError = 1;
			prnMsg(_('The password cannot contain the user id'), 'error');
		}
		if($_POST['Password'] != $_POST['PasswordCheck']) {
			$InputError = 1;
			prnMsg(_('The password and password confirmation fields entered do not match'), 'error');
		} else {
			$UpdatePassword = 'Y';
		}
	}

	if($InputError != 1) {
		// no errors
		if($UpdatePassword != 'Y') {
			$SQL = "UPDATE www_users
					SET displayrecordsmax='" . $_POST['DisplayRecordsMax'] . "',
						theme='" . $_POST['Theme'] . "',
						language='" . $_POST['Language'] . "',
						email='" . $_POST['email'] . "',
						showpagehelp='" . $_POST['ShowPageHelp'] . "',
						showfieldhelp='" . $_POST['ShowFieldHelp'] . "',
						pdflanguage='" . $_POST['PDFLanguage'] . "'
					WHERE userid = '" . $_SESSION['UserID'] . "'";
			$ErrMsg = _('The user alterations could not be processed because');
			$DbgMsg = _('The SQL that was used to update the user and failed was');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg);
			prnMsg( _('The user settings have been updated') . '. ' . _('Be sure to remember your password for the next time you login'),'success');
		} else {
			$SQL = "UPDATE www_users
					SET displayrecordsmax='" . $_POST['DisplayRecordsMax'] . "',
						theme='" . $_POST['Theme'] . "',
						language='" . $_POST['Language'] . "',
						email='" . $_POST['email'] ."',
						showpagehelp='" . $_POST['ShowPageHelp'] . "',
						showfieldhelp='" . $_POST['ShowFieldHelp'] . "',
						pdflanguage='" . $_POST['PDFLanguage'] . "',
						password='" . CryptPass($_POST['Password']) . "'
					WHERE userid = '" . $_SESSION['UserID'] . "'";
			$ErrMsg = _('The user alterations could not be processed because');
			$DbgMsg = _('The SQL that was used to update the user and failed was');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg);
			prnMsg(_('The user settings have been updated'),'success');
		}
		// Update the session variables to reflect user changes on-the-fly:
		$_SESSION['DisplayRecordsMax'] = $_POST['DisplayRecordsMax'];
		$_SESSION['Theme'] = trim($_POST['Theme']); /*already set by session.php but for completeness */
		$Theme = $_SESSION['Theme'];
		$_SESSION['Language'] = trim($_POST['Language']);
		$_SESSION['ShowPageHelp'] = $_POST['ShowPageHelp'];
		$_SESSION['ShowFieldHelp'] = $_POST['ShowFieldHelp'];
		$_SESSION['PDFLanguage'] = $_POST['PDFLanguage'];
		include('includes/LanguageSetup.php');// After last changes in LanguageSetup.php, is it required to update?
	}
}

echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
	'<input name="FormID" value="', $_SESSION['FormID'], '" type="hidden" />';

echo '<table class="selection">
		<tr>
			<td>', _('User ID'), ':</td>
			<td>', $_SESSION['UserID'], '</td>
		</tr>
		<tr>
			<td>', _('User Name'), ':</td>
			<td>', $_SESSION['UsersRealName'], '<input name="RealName" type="hidden" value="', $_SESSION['UsersRealName'], '" /></td></tr>
		<tr>
			<td>', _('Maximum Number of Records to Display'), ':</td>
			<td><input class="integer" maxlength="3" name="DisplayRecordsMax" required="required" size="3" title="', _('The input must be positive integer'), '" type="text" value="', $_SESSION['DisplayRecordsMax'], '" /></td>
		</tr>';

// Select language:
echo '<tr>
	<td>', _('Language'), ':</td>
	<td><select name="Language">';
if(!isset($_POST['Language'])) {
	$_POST['Language'] = $_SESSION['Language'];
}
foreach($LanguagesArray as $LanguageEntry => $LanguageName) {
	echo '<option ';
	if(isset($_POST['Language']) AND $_POST['Language'] == $LanguageEntry) {
		echo 'selected="selected" ';
	}
	echo 'value="', $LanguageEntry, '">', $LanguageName['LanguageName'], '</option>';
}
echo '</select></td>
	</tr>';

// Select theme:
echo '<tr>
	<td>' . _('Theme') . ':</td>
	<td><select name="Theme">';

$ThemeDirectories = scandir('css/');

foreach ($ThemeDirectories as $ThemeName) {
	if(is_dir('css/' . $ThemeName) AND $ThemeName != '.' AND $ThemeName != '..' AND $ThemeName != '.svn') {

		if($_SESSION['Theme'] == $ThemeName) {
			echo '<option selected="selected" value="' . $ThemeName . '">' . $ThemeName . '</option>';
		} else {
			echo '<option value="' . $ThemeName . '">' . $ThemeName . '</option>';
		}
	}
}

if(!isset($_POST['PasswordCheck'])) {
	$_POST['PasswordCheck']='';
}
if(!isset($_POST['Password'])) {
	$_POST['Password']='';
}
echo '</select>
		</td>
	</tr>
	<tr>
		<td>', _('New Password'), ':</td>
		<td><input name="Password" pattern="(?!^', $_SESSION['UserID'], '$).{5,}" placeholder="', _('More than 5 characters'), '" size="20" title="', _('Must be more than 5 characters and cannot be as same as userid'), '" type="password" value="', $_POST['Password'], '" /></td>
	</tr>
	<tr>
		<td>', _('Confirm Password'), ':</td>
		<td><input name="PasswordCheck" pattern="(?!^', $_SESSION['UserID'], '$).{5,}" placeholder="', _('More than 5 characters'), '" size="20" title="', _('Must be more than 5 characters and cannot be as same as userid'), '" type="password" value="', $_POST['PasswordCheck'], '" />',
			fShowFieldHelp(_('If you leave the password boxes empty your password will not change')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		'</td>
	</tr>
	<tr>
		<td>', _('Email'), ':</td>';

$SQL = "SELECT
			email,
			showpagehelp,
			showfieldhelp
		from www_users WHERE userid = '" . $_SESSION['UserID'] . "'";
$Result = DB_query($SQL);
$MyRow = DB_fetch_array($Result);

if(!isset($_POST['email'])) {
	$_POST['email'] = $MyRow['email'];
}
$_POST['ShowPageHelp'] = $MyRow['showpagehelp'];
$_POST['ShowFieldHelp'] = $MyRow['showfieldhelp'];

echo '<td><input name="email" size="40" type="email" value="', $_POST['email'], '" /></td>
	</tr>';

// Turn off/on page help:
echo '<tr>
		<td><label for="ShowPageHelp">', _('Display page help'), ':</label></td>
		<td><select id="ShowPageHelp" name="ShowPageHelp">';
if($_POST['ShowPageHelp']==0) {
	echo '<option selected="selected" value="0">', _('No'), '</option>',
		 '<option value="1">', _('Yes'), '</option>';
} else {
	echo '<option value="0">', _('No'), '</option>',
 		 '<option selected="selected" value="1">', _('Yes'), '</option>';
}
echo '</select>', fShowFieldHelp(_('Show page help when available')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		'</td>
	</tr>';
// Turn off/on field help:
echo '<tr>
		<td><label for="ShowFieldHelp">', _('Display field help'), ':</label></td>
		<td><select id="ShowFieldHelp" name="ShowFieldHelp">';
if($_POST['ShowFieldHelp']==0) {
	echo '<option selected="selected" value="0">', _('No'), '</option>',
		 '<option value="1">', _('Yes'), '</option>';
} else {
	echo '<option value="0">', _('No'), '</option>',
 		 '<option selected="selected" value="1">', _('Yes'), '</option>';
}
echo '</select>', fShowFieldHelp(_('Show field help when available')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		'</td>
	</tr>';
// PDF Language Support:
if(!isset($_POST['PDFLanguage'])) {
	$_POST['PDFLanguage']=$_SESSION['PDFLanguage'];
}
echo '<tr>
		<td>', _('PDF Language Support'), ': </td>
		<td><select name="PDFLanguage">';
for($i=0; $i<count($PDFLanguages); $i++) {
	if($_POST['PDFLanguage'] == $i) {
		echo '<option selected="selected" value="', $i, '">', $PDFLanguages[$i], '</option>';
	} else {
		echo '<option value="', $i, '">', $PDFLanguages[$i], '</option>';
	}
}
echo '</select></td>
	</tr>
	</table>
	<br />
	<div class="centre"><input name="Modify" type="submit" value="', _('Modify'), '" /></div>
	</form>';

include('includes/footer.php');
?>
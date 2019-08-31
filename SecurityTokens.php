<?php
/* Administration of security tokens */

include('includes/session.php');
$Title = _('Maintain Security Tokens');
$ViewTopic = 'SecuritySchema';
$BookMark = 'SecurityTokens';// Pending ?
include('includes/header.php');

if($AllowDemoMode) {
	prnMsg(_('The the system is in demo mode and the security model administration is disabled'), 'warn');
	exit;
}

// Merge gets into posts:
if(isset($_GET['Action'])) {
	$_POST['Action'] = $_GET['Action'];
}
if(isset($_GET['TokenId'])) {
	$_POST['TokenId'] = $_GET['TokenId'];
}
if(isset($_GET['TokenDescription'])) {
	$_POST['TokenDescription'] = $_GET['TokenDescription'];
}

// Validate the data sent:
$InputError = 0;
if($_POST['Action']=='insert' OR $_POST['Action']=='update') {
	if(!is_numeric($_POST['TokenId'])) {
		prnMsg(_('The token ID is expected to be a number. Please enter a number for the token ID'), 'error');
		$InputError = 1;
	}
	if(mb_strlen($_POST['TokenId']) == 0) {
		prnMsg(_('A token ID must be entered'), 'error');
		$InputError = 1;
	}
	if(mb_strlen($_POST['TokenDescription']) == 0) {
		prnMsg(_('A token description must be entered'), 'error');
		$InputError = 1;
	}
}

// Execute the requested action:
switch($_POST['Action']) {
    case 'cancel':
		unset($_POST['Action']);
		unset($_POST['TokenId']);
		unset($_POST['TokenDescription']);
		break;
    case 'delete':
		$Result = DB_query("SELECT script FROM scripts WHERE pagesecurity='" . $_POST['TokenId'] . "'");
		if(DB_num_rows($Result) > 0) {
			$List = '';
			while($ScriptRow = DB_fetch_array($Result)) {
					$List .= ' ' . $ScriptRow['script'];
				}
			prnMsg(_('This security token is currently used by the following scripts and cannot be deleted') . ':' . $List, 'error');
		} else {
			$Result = DB_query("DELETE FROM securitytokens WHERE tokenid='" . $_POST['TokenId'] . "'");
			if($Result) {prnMsg(_('The security token was deleted successfully'), 'success');}
		}
		unset($_POST['Action']);
		unset($_POST['TokenId']);
		unset($_POST['TokenDescription']);
		break;
    case 'edit':
		$Result = DB_query("SELECT tokenid, tokenname FROM securitytokens WHERE tokenid='" . $_POST['TokenId'] . "'");
		$MyRow = DB_fetch_array($Result);
		// Keeps $_POST['Action']=edit, and sets $_POST['TokenId'] and $_POST['TokenDescription'].
		$_POST['TokenId'] = $MyRow['tokenid'];
		$_POST['TokenDescription'] = $MyRow['tokenname'];
		break;
    case 'insert':
		$Result = DB_query("SELECT tokenid FROM securitytokens WHERE tokenid='" . $_POST['TokenId'] . "'");
		if(DB_num_rows($Result) != 0) {
			prnMsg( _('This token ID has already been used. Please use a new one') , 'warn');
			$InputError = 1;
		}
		if($InputError == 0) {
			$Result = DB_query("INSERT INTO securitytokens values('" . $_POST['TokenId'] . "', '" . $_POST['TokenDescription'] . "')");
			if($Result) {prnMsg(_('The security token was inserted successfully'), 'success');}
			unset($_POST['Action']);
			unset($_POST['TokenId']);
			unset($_POST['TokenDescription']);
		}
		break;
    case 'update':
		if($InputError == 0) {
			$Result = DB_query("UPDATE securitytokens SET tokenname='" . $_POST['TokenDescription'] . "' WHERE tokenid='" . $_POST['TokenId'] . "'");
			if($Result) {prnMsg(_('The security token was updated successfully'), 'success');}
			unset($_POST['Action']);
			unset($_POST['TokenId']);
			unset($_POST['TokenDescription']);
		}
		break;
    default:// Unknown requested action.
		unset($_POST['Action']);
		unset($_POST['TokenId']);
		unset($_POST['TokenDescription']);
}// END switch($_POST['Action']).

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/maintenance.png" title="', // Icon image.
	$Title, '" /> ', // Icon title.
	$Title, '</p>', // Page title.
// Security Token Data table:
	'<table class="selection">
	<thead>
		<tr>
			<th>', _('Token ID'), '</th>
			<th>', _('Description'), '</th>
			<th class="noprint" colspan="2">&nbsp;</th>
		</tr>
	</thead><tbody>';
$Result = DB_query("SELECT tokenid, tokenname FROM securitytokens ORDER BY tokenid");
while($MyRow = DB_fetch_array($Result)) {
	echo '<tr>
			<td class="number">', $MyRow['tokenid'], '</td>
			<td class="text">', htmlspecialchars($MyRow['tokenname'], ENT_QUOTES, 'UTF-8'), '</td>
			<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Action=edit&amp;TokenId=', $MyRow['tokenid'], '">', _('Edit'), '</a></td>
			<td class="noprint"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Action=delete&amp;TokenId=', $MyRow['tokenid'], '" onclick="return confirm(\'', _('Are you sure you wish to delete this security token?'), '\');">', _('Delete'), '</a></td>
		</tr>';
}
echo '</tbody></table>
	<br />
	<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" id="form" method="post">
	<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />
	<table class="noprint">
	<thead>
		<tr>
			<th colspan="2">';
// Edit or New Security Token form table:
if(isset($_POST['Action']) and $_POST['Action']=='edit') {
	echo		('Edit Security Token'), '</th>',
		'</tr>
		</thead><tfoot>',
		'<tr>
			<td colspan="2" class="centre">',
				'<button name="Action" type="submit" value="update"><img alt="" src="', $RootPath, '/css/', $Theme,
					'/images/tick.svg" /> ', _('Update'), '</button>', // "Update" button.
				'<button formaction="SecurityTokens.php?Action=cancel" type="submit"><img alt="" src="', $RootPath, '/css/', $Theme,
					'/images/cross.svg" /> ', _('Cancel'), '</button>', // "Cancel" button.
				'<button onclick="window.location=\'index.php?Application=system\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme,
					'/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
			'</td>',
		'</tr>
		</tfoot><tbody>',
		'<tr>
			<td>', _('Token ID'), '</td>
			<td>', $_POST['TokenId'], '<input name="TokenId" type="hidden" value="';
} else {
	echo		('New Security Token'), '</th>',
		'</tr>
		</thead><tfoot>',
		'<tr>
			<td colspan="2" class="centre">',
				'<button name="Action" type="submit" value="insert"><img alt="" src="', $RootPath, '/css/', $Theme,
					'/images/tick.svg" /> ', _('Insert'), '</button>', // "Insert" button.
				'<button onclick="window.location=\'index.php?Application=system\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme,
					'/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
			'</td>',
		'</tr>
		</tfoot><tbody>',
		'<tr>
			<td><label for="TokenId">', _('Token ID'), '</label></td>
			<td><input autofocus="autofocus" class="number" id="TokenId" maxlength="4" name="TokenId" required="required" size="6" type="text" value="';
}
echo			$_POST['TokenId'], '" /></td>
		</tr>
		<tr>
			<td><label for="TokenDescription">', _('Description'), '</label></td>
			<td><input id="TokenDescription" maxlength="60" name="TokenDescription" required="required" size="50" title="', _('The security token description should describe which functions this token allows a user/role to access'), '" type="text" value="', $_POST['TokenDescription'], '" /></td>
		</tr>
	</table>
	</form>';

include('includes/footer.php');
?>

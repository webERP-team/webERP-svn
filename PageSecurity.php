<?php


include('includes/session.php');
$Title = _('Page Security Levels');
include('includes/header.php');

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/security.png" title="' . _('Page Security Levels') . '" alt="" />' . ' ' . $Title . '</p><br />';

if($AllowDemoMode) {
	prnMsg(_('The the system is in demo mode and the security model administration is disabled'), 'warn');
	exit;
}

if (isset($_POST['Update']) AND $AlloDemoMode!= true) {
	foreach ($_POST as $ScriptName => $PageSecurityValue) {
		if ($ScriptName!='Update' and $ScriptName!='FormID') {
			$ScriptName = mb_substr($ScriptName, 0, mb_strlen($ScriptName)-4).'.php';
			$sql="UPDATE scripts SET pagesecurity='". $PageSecurityValue . "' WHERE script='" . $ScriptName . "'";
			$UpdateResult=DB_query($sql,_('Could not update the page security value for the script because'));
		}
	}
}

$sql="SELECT script,
			pagesecurity,
			description
		FROM scripts";

$result=DB_query($sql);

echo '<br /><form method="post" id="PageSecurity" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<table class="selection">';

$TokenSql="SELECT tokenid,
					tokenname
			FROM securitytokens
			ORDER BY tokenname";
$TokenResult=DB_query($TokenSql);

while ($myrow=DB_fetch_array($result)) {
	echo '<tr>
			<td>' . $myrow['script'] . '</td>
			<td><select name="' . $myrow['script'] . '">';

	while ($myTokenRow=DB_fetch_array($TokenResult)) {
		if ($myTokenRow['tokenid']==$myrow['pagesecurity']) {
			echo '<option selected="selected" value="' . $myTokenRow['tokenid'] . '">' . $myTokenRow['tokenname'] . '</option>';
		} else {
			echo '<option value="'.$myTokenRow['tokenid'].'">' . $myTokenRow['tokenname'] . '</option>';
		}
	}
	echo '</select></td>
		</tr>';
	DB_data_seek($TokenResult, 0);
}

echo '</table><br />';

echo '<div class="centre">
		<input type="submit" name="Update" value="'._('Update Security Levels').'" />
	</div>
	<br />
    </div>
	</form>';

include('includes/footer.php');
?>
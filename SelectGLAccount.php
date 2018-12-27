<?php

include('includes/session.php');

$Title = _('Search GL Accounts');
$ViewTopic = 'GeneralLedger';
$BookMark = 'GLAccountInquiry';
include('includes/header.php');

$msg='';
unset($Result);

if (isset($_POST['Search'])){

	if (mb_strlen($_POST['Keywords']>0) AND mb_strlen($_POST['GLCode'])>0) {
		$msg=_('Account name keywords have been used in preference to the account code extract entered');
	}
	if ($_POST['Keywords']=='' AND $_POST['GLCode']=='') {
            $SQL = "SELECT chartmaster.accountcode,
                    chartmaster.accountname,
                    chartmaster.group_,
                    CASE WHEN accountgroups.pandl!=0 THEN '" . _('Profit and Loss') . "' ELSE '" . _('Balance Sheet') ."' END AS pl
                    FROM chartmaster,
                        accountgroups,
						glaccountusers
					WHERE glaccountusers.accountcode = chartmaster.accountcode
						AND glaccountusers.userid='" .  $_SESSION['UserID'] . "'
						AND glaccountusers.canview=1
						AND chartmaster.group_=accountgroups.groupname
                    ORDER BY chartmaster.accountcode";
    }
	elseif (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			$SQL = "SELECT chartmaster.accountcode,
					chartmaster.accountname,
					chartmaster.group_,
					CASE WHEN accountgroups.pandl!=0
						THEN '" . _('Profit and Loss') . "'
						ELSE '" . _('Balance Sheet') . "' END AS pl
				FROM chartmaster,
					accountgroups,
					glaccountusers
				WHERE glaccountusers.accountcode = chartmaster.accountcode
					AND glaccountusers.userid='" .  $_SESSION['UserID'] . "'
					AND glaccountusers.canview=1
					AND chartmaster.group_ = accountgroups.groupname
					AND accountname " . LIKE  . "'". $SearchString ."'
				ORDER BY accountgroups.sequenceintb,
					chartmaster.accountcode";

		} elseif (mb_strlen($_POST['GLCode'])>0){
			if (!empty($_POST['GLCode'])) {
				echo '<meta http-equiv="refresh" content="0; url=' . $RootPath . '/GLAccountInquiry.php?Account=' . $_POST['GLCode'] . '&Show=Yes">';
				exit;
			}

			$SQL = "SELECT chartmaster.accountcode,
					chartmaster.accountname,
					chartmaster.group_,
					CASE WHEN accountgroups.pandl!=0 THEN '" . _('Profit and Loss') . "' ELSE '" . _('Balance Sheet') ."' END AS pl
					FROM chartmaster,
						accountgroups,
						glaccountusers
				WHERE glaccountusers.accountcode = chartmaster.accountcode
					AND glaccountusers.userid='" .  $_SESSION['UserID'] . "'
					AND glaccountusers.canview=1
					AND chartmaster.group_=accountgroups.groupname
					AND chartmaster.accountcode >= '" . $_POST['GLCode'] . "'
					ORDER BY chartmaster.accountcode";
		}
		if (isset($SQL) and $SQL!=''){
			$Result = DB_query($SQL);
			if (DB_num_rows($Result) == 1) {
				$AccountRow = DB_fetch_row($Result);
				header('location:' . $RootPath . '/GLAccountInquiry.php?Account=' . $AccountRow[0] . '&Show=Yes');
				exit;
			}
		}
} //end of if search

$TargetPeriod = GetPeriod(date($_SESSION['DefaultDateFormat']));

if (!isset($AccountID)) {

	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ' . _('Search for General Ledger Accounts') . '</p>
		<br />
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') .  '" method="post">
		<div>
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if(mb_strlen($msg)>1){
		prnMsg($msg,'info');
	}

	echo '<table class="selection">
		<tr>
			<td>' . _('Enter extract of text in the Account name') .':</td>
			<td><input type="text" name="Keywords" size="20" maxlength="25" /></td>
			<td><b>' .  _('OR') . '</b></td>';

	$SQLAccountSelect="SELECT chartmaster.accountcode,
							chartmaster.accountname,
							chartmaster.group_
						FROM chartmaster
						INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
						INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
						ORDER BY accountgroups.sequenceintb, accountgroups.groupname, chartmaster.accountcode";

	$ResultSelection=DB_query($SQLAccountSelect);
	$OptGroup = '';
	echo '<td><select name="GLCode">';
	echo '<option value="">' . _('Select an Account Code') . '</option>';
	while ($MyRowSelection=DB_fetch_array($ResultSelection)){
		if ($OptGroup != $MyRowSelection['group_']) {
			echo '<optgroup label="' . $MyRowSelection['group_'] . '">';
			$OptGroup = $MyRowSelection['group_'];
		}
		if (isset($_POST['GLCode']) and $_POST['GLCode']==$MyRowSelection['accountcode']){
			echo '<option selected="selected" value="' . $MyRowSelection['accountcode'] . '">' . $MyRowSelection['accountcode'].' - ' .htmlspecialchars($MyRowSelection['accountname'], ENT_QUOTES,'UTF-8', false) . '</option>';
		} else {
			echo '<option value="' . $MyRowSelection['accountcode'] . '">' . $MyRowSelection['accountcode'].' - ' .htmlspecialchars($MyRowSelection['accountname'], ENT_QUOTES,'UTF-8', false)  . '</option>';
		}
	}
	echo '</select></td>';

	echo '	</tr>
		</table>
		<br />';

	echo '<div class="centre">
			<input type="submit" name="Search" value="' . _('Search Now') . '" />
			<input type="submit" name="reset" value="' . _('Reset') .'" />
		</div>';

	if (isset($Result) and DB_num_rows($Result)>0) {

		echo '<br /><table class="selection">';

		$TableHeader = '<tr>
							<th>' . _('Code') . '</th>
							<th>' . _('Account Name') . '</th>
							<th>' . _('Group') . '</th>
							<th>' . _('Account Type') . '</th>
							<th>' . _('Inquiry') . '</th>
							<th>' . _('Edit') . '</th>
						</tr>';

		echo $TableHeader;

		$j = 1;

		while ($MyRow=DB_fetch_array($Result)) {

			printf('<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href="%s/GLAccountInquiry.php?Account=%s&amp;Show=Yes&FromPeriod=%s&ToPeriod=%s"><img src="%s/css/%s/images/magnifier.png" title="' . _('Inquiry') . '" alt="' . _('Inquiry') . '" /></td>
					<td><a href="%s/GLAccounts.php?SelectedAccount=%s"><img src="%s/css/%s/images/maintenance.png" title="' . _('Edit') . '" alt="' . _('Edit') . '" /></a>
					</tr>',
					htmlspecialchars($MyRow['accountcode'],ENT_QUOTES,'UTF-8',false),
					htmlspecialchars($MyRow['accountname'],ENT_QUOTES,'UTF-8',false),
					$MyRow['group_'],
					$MyRow['pl'],
					$RootPath,
					$MyRow['accountcode'],
					$TargetPeriod,
					$TargetPeriod,
					$RootPath,
					$Theme,
					$RootPath,
					$MyRow['accountcode'],
					$RootPath,
					$Theme);

			$j++;
			if ($j == 12){
				$j=1;
				echo $TableHeader;

			}
//end of page full new headings if
		}
//end of while loop

		echo '</table>';

	}
//end if results to show

	echo '</div>
          </form>';

} //end AccountID already selected

include('includes/footer.php');
?>

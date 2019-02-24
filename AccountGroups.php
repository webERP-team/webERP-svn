<?php
/* AccountGroups.php
Defines the groupings of general ledger accounts */

include('includes/session.php');
$Title = _('Account Groups');
$ViewTopic= 'GeneralLedger';
$BookMark = 'AccountGroups';
include('includes/header.php');

include('includes/SQL_CommonFunctions.inc');


function CheckForRecursiveGroup($ParentGroupName, $GroupName) {

/* returns true ie 1 if the group contains the parent group as a child group
ie the parent group results in a recursive group structure otherwise false ie 0 */

	$ErrMsg = _('An error occurred in retrieving the account groups of the parent account group during the check for recursion');
	$DbgMsg = _('The SQL that was used to retrieve the account groups of the parent account group and that failed in the process was');
	do {
		$sql = "SELECT parentgroupname
				FROM accountgroups
				WHERE groupname='" . $GroupName ."'";

		$result = DB_query($sql,$ErrMsg,$DbgMsg);
		$myrow = DB_fetch_row($result);
		if($ParentGroupName == $myrow[0]) {
			return true;
		}
		$GroupName = $myrow[0];
	} while($myrow[0] != '');
	return false;
}// END of function CheckForRecursiveGroupName

// If $Errors is set, then unset it.
if(isset($Errors)) {
	unset($Errors);
}

$Errors = array();

if(isset($_POST['MoveGroup'])) {
	$sql="UPDATE chartmaster SET group_='" . $_POST['DestinyAccountGroup'] . "' WHERE group_='" . $_POST['OriginalAccountGroup'] . "'";
	$ErrMsg = _('An error occurred in moving the account group');
	$DbgMsg = _('The SQL that was used to move the account group was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);
	echo '<div class="centre"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">' . _('Review Account Groups') . '</a></div>';
	prnMsg( _('All accounts in the account group:') . ' ' . $_POST['OriginalAccountGroup'] . ' ' . _('have been changed to the account group:') . ' ' . $_POST['DestinyAccountGroup'],'success');
}

if(isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test

	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;

	$sql="SELECT count(groupname)
			FROM accountgroups
			WHERE groupname='" . $_POST['GroupName'] . "'";

	$DbgMsg = _('The SQL that was used to retrieve the information was');
	$ErrMsg = _('Could not check whether the group exists because');

	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	$myrow=DB_fetch_row($result);

	if($myrow[0] != 0 AND $_POST['SelectedAccountGroup'] == '') {
		$InputError = 1;
		prnMsg( _('The account group name already exists in the database'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	if(ContainsIllegalCharacters($_POST['GroupName'])) {
		$InputError = 1;
		prnMsg( _('The account group name cannot contain the character') . " '&' " . _('or the character') ."' '",'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	if(mb_strlen($_POST['GroupName'])==0) {
		$InputError = 1;
		prnMsg( _('The account group name must be at least one character long'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	if($_POST['ParentGroupName'] !='') {
		if(CheckForRecursiveGroup($_POST['GroupName'],$_POST['ParentGroupName'])) {
			$InputError =1;
			prnMsg(_('The parent account group selected appears to result in a recursive account structure - select an alternative parent account group or make this group a top level account group'),'error');
			$Errors[$i] = 'ParentGroupName';
			$i++;
		} else {
			$sql = "SELECT pandl,
						sequenceintb,
						sectioninaccounts
					FROM accountgroups
					WHERE groupname='" . $_POST['ParentGroupName'] . "'";

			$DbgMsg = _('The SQL that was used to retrieve the information was');
			$ErrMsg = _('Could not check whether the group is recursive because');

			$result = DB_query($sql,$ErrMsg,$DbgMsg);

			$ParentGroupRow = DB_fetch_array($result);
			$_POST['SequenceInTB'] = $ParentGroupRow['sequenceintb'];
			$_POST['PandL'] = $ParentGroupRow['pandl'];
			$_POST['SectionInAccounts']= $ParentGroupRow['sectioninaccounts'];
			prnMsg(_('Since this account group is a child group, the sequence in the trial balance, the section in the accounts and whether or not the account group appears in the balance sheet or profit and loss account are all properties inherited from the parent account group. Any changes made to these fields will have no effect.'),'warn');
		}
	}
	if(!ctype_digit($_POST['SectionInAccounts'])) {
		$InputError = 1;
		prnMsg( _('The section in accounts must be an integer'),'error');
		$Errors[$i] = 'SectionInAccounts';
		$i++;
	}
	if(!ctype_digit($_POST['SequenceInTB'])) {
		$InputError = 1;
		prnMsg( _('The sequence in the trial balance must be an integer'),'error');
		$Errors[$i] = 'SequenceInTB';
		$i++;
	}
	if(!ctype_digit($_POST['SequenceInTB']) OR $_POST['SequenceInTB'] > 10000) {
		$InputError = 1;
		prnMsg( _('The sequence in the TB must be numeric and less than') . ' 10,000','error');
		$Errors[$i] = 'SequenceInTB';
		$i++;
	}


	if($_POST['SelectedAccountGroup']!='' AND $InputError !=1) {

		/*SelectedAccountGroup could also exist if submit had not been clicked this code would not run in this case cos submit is false of course  see the delete code below*/
		if($_POST['SelectedAccountGroup']!==$_POST['GroupName']) {

			DB_IgnoreForeignKeys();

			$sql = "UPDATE chartmaster
					SET group_='" . $_POST['GroupName'] . "'
					WHERE group_='" . $_POST['SelectedAccountGroup'] . "'";
			$ErrMsg = _('An error occurred in renaming the account group');
			$DbgMsg = _('The SQL that was used to rename the account group was');

			$result = DB_query($sql, $ErrMsg, $DbgMsg);

			$sql = "UPDATE accountgroups
					SET parentgroupname='" . $_POST['GroupName'] . "'
					WHERE parentgroupname='" . $_POST['SelectedAccountGroup'] . "'";

			$result = DB_query($sql, $ErrMsg, $DbgMsg);

			DB_ReinstateForeignKeys();
		}

		$sql = "UPDATE accountgroups SET groupname='" . $_POST['GroupName'] . "',
										sectioninaccounts='" . $_POST['SectionInAccounts'] . "',
										pandl='" . $_POST['PandL'] . "',
										sequenceintb='" . $_POST['SequenceInTB'] . "',
										parentgroupname='" . $_POST['ParentGroupName'] . "'
									WHERE groupname = '" . $_POST['SelectedAccountGroup'] . "'";
		$ErrMsg = _('An error occurred in updating the account group');
		$DbgMsg = _('The SQL that was used to update the account group was');

		$msg = _('Record Updated');
	} elseif($InputError !=1) {

	/*Selected group is null cos no item selected on first time round so must be adding a record must be submitting new entries in the new account group form */

		$sql = "INSERT INTO accountgroups ( groupname,
											sectioninaccounts,
											sequenceintb,
											pandl,
											parentgroupname
										) VALUES (
											'" . $_POST['GroupName'] . "',
											'" . $_POST['SectionInAccounts'] . "',
											'" . $_POST['SequenceInTB'] . "',
											'" . $_POST['PandL'] . "',
											'" . $_POST['ParentGroupName'] . "')";
		$ErrMsg = _('An error occurred in inserting the account group');
		$DbgMsg = _('The SQL that was used to insert the account group was');
		$msg = _('Record inserted');
	}

	if($InputError!=1) {
		//run the SQL from either of the above possibilites
		$result = DB_query($sql,$ErrMsg,$DbgMsg);
		prnMsg($msg,'success');
		unset ($_POST['SelectedAccountGroup']);
		unset ($_POST['GroupName']);
		unset ($_POST['SequenceInTB']);
	}

} elseif(isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'ChartMaster'

	$sql= "SELECT COUNT(group_) AS groups FROM chartmaster WHERE chartmaster.group_='" . $_GET['SelectedAccountGroup'] . "'";
	$ErrMsg = _('An error occurred in retrieving the group information from chartmaster');
	$DbgMsg = _('The SQL that was used to retrieve the information was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);
	$myrow = DB_fetch_array($result);
	if($myrow['groups']>0) {
		prnMsg( _('Cannot delete this account group because general ledger accounts have been created using this group'),'warn');
		echo '<br />' . _('There are') . ' ' . $myrow['groups'] . ' ' . _('general ledger accounts that refer to this account group');
		echo '<br /><form method="post" id="AccountGroups" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';

		echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
		echo '<table class="selection">';
		echo '<input  type="hidden" name="OriginalAccountGroup" value="' . $_GET['SelectedAccountGroup'] . '" />';
		echo '<tr>
				<td>' . _('Parent Group') . ':' . '</td>
				<td><select tabindex="2" ' . (in_array('ParentGroupName',$Errors) ?  'class="selecterror"' : '' ) . '  name="DestinyAccountGroup">';

		$sql = "SELECT groupname FROM accountgroups";
		$GroupResult = DB_query($sql,$ErrMsg,$DbgMsg);
		while($GroupRow = DB_fetch_array($GroupResult) ) {

			if(isset($_POST['ParentGroupName']) AND $_POST['ParentGroupName']==$GroupRow['groupname']) {
				echo '<option selected="selected" value="'.htmlentities($GroupRow['groupname'], ENT_QUOTES,'UTF-8').'">' .htmlentities($GroupRow['groupname'], ENT_QUOTES,'UTF-8'). '</option>';
			} else {
				echo '<option value="'.htmlentities($GroupRow['groupname'], ENT_QUOTES,'UTF-8').'">' .htmlentities($GroupRow['groupname'], ENT_QUOTES,'UTF-8') . '</option>';
			}
		}
		echo '</select>';
		echo '</td></tr>';
		echo '<tr>
				<td colspan="2"><div class="centre"><input tabindex="6" type="submit" name="MoveGroup" value="' . _('Move Group') . '" /></div></td>
		  </tr>
		  </table>';

	} else {
		$sql = "SELECT COUNT(groupname) groupnames FROM accountgroups WHERE parentgroupname = '" . $_GET['SelectedAccountGroup'] . "'";
		$ErrMsg = _('An error occurred in retrieving the parent group information');
		$DbgMsg = _('The SQL that was used to retrieve the information was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);
		$myrow = DB_fetch_array($result);
		if($myrow['groupnames']>0) {
			prnMsg( _('Cannot delete this account group because it is a parent account group of other account group(s)'),'warn');
			echo '<br />' . _('There are') . ' ' . $myrow['groupnames'] . ' ' . _('account groups that have this group as its/there parent account group');

		} else {
			$sql="DELETE FROM accountgroups WHERE groupname='" . $_GET['SelectedAccountGroup'] . "'";
			$ErrMsg = _('An error occurred in deleting the account group');
			$DbgMsg = _('The SQL that was used to delete the account group was');
			$result = DB_query($sql,$ErrMsg,$DbgMsg);
			prnMsg( $_GET['SelectedAccountGroup'] . ' ' . _('group has been deleted') . '!','success');
		}

	} //end if account group used in GL accounts
}

if(!isset($_GET['SelectedAccountGroup']) AND !isset($_POST['SelectedAccountGroup'])) {

/*	An account group could be posted when one has been edited and is being updated or GOT when selected for modification
	SelectedAccountGroup will exist because it was sent with the page in a GET .
	If its the first time the page has been displayed with no parameters
	then none of the above are true and the list of account groups will be displayed with
	links to delete or edit each. These will call the same page again and allow update/input
	or deletion of the records*/

	$sql = "SELECT groupname,
					sectionname,
					sequenceintb,
					pandl,
					parentgroupname
			FROM accountgroups
			LEFT JOIN accountsection ON sectionid = sectioninaccounts
			ORDER BY sequenceintb";

	$DbgMsg = _('The sql that was used to retrieve the account group information was ');
	$ErrMsg = _('Could not get account groups because');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);
	echo '<p class="page_title_text"><img alt="" src="'.$RootPath.'/css/'.$Theme.'/images/maintenance.png" title="' . _('Search') . '" />' . ' ' . $Title . '</p><br />';

	echo '<table class="selection">
			<thead>
			<tr>
				<th class="ascending">' . _('Group Name') . '</th>
				<th class="ascending">' . _('Section') . '</th>
				<th class="ascending">' . _('Sequence In TB') . '</th>
				<th class="ascending">' . _('Profit and Loss') . '</th>
				<th class="ascending">' . _('Parent Group') . '</th>
				<th class="noprint" colspan="2">&nbsp;</th>
				</tr>
			</thead>
			<tbody>';

	while($myrow = DB_fetch_array($result)) {

		switch ($myrow['pandl']) {
		case -1:
			$PandLText=_('Yes');
			break;
		case 1:
			$PandLText=_('Yes');
			break;
		case 0:
			$PandLText=_('No');
			break;
		} //end of switch statement

		echo '<tr class="striped_row">
			<td>' . htmlspecialchars($myrow['groupname'], ENT_QUOTES,'UTF-8') . '</td>
			<td>' . $myrow['sectionname'] . '</td>
			<td class="number">' . $myrow['sequenceintb'] . '</td>
			<td>' . $PandLText . '</td>
			<td>' . $myrow['parentgroupname'] . '</td>';
		echo '<td class="noprint"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'] . '?SelectedAccountGroup=' . urlencode($myrow['groupname']), ENT_QUOTES,'UTF-8') . '">' . _('Edit') . '</a></td>';
		echo '<td class="noprint"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'] . '?SelectedAccountGroup=' . urlencode($myrow['groupname']), ENT_QUOTES,'UTF-8') . '&amp;delete=1" onclick="return confirm(\'' . _('Are you sure you wish to delete this account group?') . '\');">' . _('Delete')  . '</a></td></tr>';

	} //END WHILE LIST LOOP
	echo '</tbody>
		</table>';
} //end of ifs and buts!


if(isset($_POST['SelectedAccountGroup']) or isset($_GET['SelectedAccountGroup'])) {
	echo '<div class="centre"><br /><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">' . _('Review Account Groups') . '</a></div>';
}

if(!isset($_GET['delete'])) {

	echo '<form method="post" id="AccountGroups" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">';
    echo '<div><br />';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if(isset($_GET['SelectedAccountGroup'])) {
		//editing an existing account group

		$sql = "SELECT groupname,
						sectioninaccounts,
						sequenceintb,
						pandl,
						parentgroupname
				FROM accountgroups
				WHERE groupname='" . $_GET['SelectedAccountGroup'] ."'";

		$ErrMsg = _('An error occurred in retrieving the account group information');
		$DbgMsg = _('The SQL that was used to retrieve the account group and that failed in the process was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);
		if(DB_num_rows($result) == 0) {
			prnMsg( _('The account group name does not exist in the database'),'error');
			include('includes/footer.php');
			exit;
		}
		$myrow = DB_fetch_array($result);

		$_POST['GroupName'] = $myrow['groupname'];
		$_POST['SectionInAccounts']  = $myrow['sectioninaccounts'];
		$_POST['SequenceInTB']  = $myrow['sequenceintb'];
		$_POST['PandL']  = $myrow['pandl'];
		$_POST['ParentGroupName'] = $myrow['parentgroupname'];

		echo '<table class="selection">
			<thead>
				<tr>
					<th colspan="2">', _('Edit Account Group Details'), '</th>
				</tr>
			</thead>
			<tfoot>
			<tr>
				<td class="centre" colspan="2">',
					'<button name="submit" tabindex="6" type="submit" value="Update"><img alt="" src="', $RootPath, '/css/', $Theme,
						'/images/tick.svg" /> ', _('Update'), '</button>', // "Update" button.
					'<button onclick="window.location=\'AccountGroups.php\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme,
						'/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
				'</td>
			</tr>
			</tfoot>
			<tbody>
				<tr>
					<td><input name="SelectedAccountGroup" type="hidden" value="', $_GET['SelectedAccountGroup'], '" /></td>
				</tr>';

	} elseif(!isset($_POST['MoveGroup'])) { //end of if $_POST['SelectedAccountGroup'] only do the else when a new record is being entered

		if(!isset($_POST['SelectedAccountGroup'])) {
			$_POST['SelectedAccountGroup']='';
		}
		if(!isset($_POST['GroupName'])) {
			$_POST['GroupName']='';
		}
		if(!isset($_POST['SectionInAccounts'])) {
			$_POST['SectionInAccounts']='';
		}
		if(!isset($_POST['SequenceInTB'])) {
			$_POST['SequenceInTB']='';
		}
		if(!isset($_POST['PandL'])) {
			$_POST['PandL']='';
		}

		echo '<br />
			<table class="noprint selection">
			<thead>
				<tr>
					<th colspan="2">', _('New Account Group Details'), '</th>
				</tr>
			</thead>
			<tfoot>
			<tr>
				<td class="centre" colspan="2">',
					'<button name="submit" tabindex="6" type="submit" value="Insert"><img alt="" src="', $RootPath, '/css/', $Theme,
						'/images/tick.svg" /> ', _('Insert'), '</button>', // "Insert" button.
					'<button onclick="window.location=\'index.php?Application=GL\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme,
						'/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
				'</td>
			</tr>
			</tfoot>
			<tbody>
				<tr>
					 <td><input name="SelectedAccountGroup" type="hidden" value="', $_POST['SelectedAccountGroup'], '" /></td>
				</tr>';
	}
	echo '<tr>
			<td>', _('Account Group Name'), ':</td>
			<td><input autofocus="autofocus" data-type="no-illegal-chars" placeholder="' . _('Enter the account group name') . '" ' . (in_array('GroupName',$Errors) ?  '"class=inputerror" ' : '' ) . 'maxlength="30" minlength="3" name="GroupName" required="required" size="30" tabindex="1" type="text" value="' . $_POST['GroupName'] . '" title="' . _('A unique name for the account group must be entered - at least 3 characters long and less than 30 characters long. Only alpha numeric characters can be used.') . '" /></td>
		</tr>
		<tr>
			<td>', _('Parent Group'), ':</td>
			<td><select ',
				( in_array('ParentGroupName',$Errors) ? 'class="selecterror" ' : '' ),
				'name="ParentGroupName" tabindex="2">';
	echo '<option ',
		( !isset($_POST['ParentGroupName']) ? 'selected="selected" ' : '' ),
		'value="">', _('Top Level Group'), '</option>';

	$sql = "SELECT groupname FROM accountgroups";
	$groupresult = DB_query($sql,$ErrMsg,$DbgMsg);
	while( $grouprow = DB_fetch_array($groupresult) ) {
		if(isset($_POST['ParentGroupName']) AND $_POST['ParentGroupName']==$grouprow['groupname']) {
			echo '<option selected="selected" value="'.htmlspecialchars($grouprow['groupname'], ENT_QUOTES,'UTF-8').'">' .htmlspecialchars($grouprow['groupname'], ENT_QUOTES,'UTF-8') . '</option>';
		} else {
			echo '<option value="'.htmlspecialchars($grouprow['groupname'], ENT_QUOTES,'UTF-8').'">' .htmlspecialchars($grouprow['groupname'], ENT_QUOTES,'UTF-8') . '</option>';
		}
	}
	echo '</select></td>
		</tr>
		<tr>
			<td>', _('Section In Accounts'), ':</td>
			<td><select ',
				( in_array('SectionInAccounts',$Errors) ? 'class="selecterror" ' : '' ),
				'name="SectionInAccounts" tabindex="3">';

	$sql = "SELECT sectionid, sectionname FROM accountsection ORDER BY sectionid";
	$secresult = DB_query($sql,$ErrMsg,$DbgMsg);
	while( $secrow = DB_fetch_array($secresult) ) {
		if($_POST['SectionInAccounts']==$secrow['sectionid']) {
			echo '<option selected="selected" value="'.$secrow['sectionid'].'">' . $secrow['sectionname'].' ('.$secrow['sectionid'].')</option>';
		} else {
			echo '<option value="'.$secrow['sectionid'].'">' . $secrow['sectionname'].' ('.$secrow['sectionid'].')</option>';
		}
	}
	echo '</select></td>
		</tr>
		<tr>
			<td>', _('Profit and Loss'), ':</td>
			<td><select name="PandL" tabindex="4" title="', _('Select YES if this account group will contain accounts that will consist of only profit and loss accounts or NO if the group will contain balance sheet account'), '">';
	if($_POST['PandL']!=0 ) {
		echo '<option value="0">', _('No'), '</option>',
			 '<option selected="selected" value="1">', _('Yes'), '</option>';
	} else {
		echo '<option selected="selected" value="0">', _('No'), '</option>',
			 '<option value="1">', _('Yes'), '</option>';
	}
	echo '</select></td>
		</tr>
		<tr>
			<td>', _('Sequence In TB'), ':</td>
			<td><input class="number" maxlength="4" name="SequenceInTB" required="required" tabindex="5" type="text" value="', $_POST['SequenceInTB'], '" title="', _('Enter the sequence number that this account group and its child general ledger accounts should display in the trial balance'), '" /></td>
		</tr>
		</tbody>
	</table>
	<br />
	</div>
	</form>';

} //end if record deleted no point displaying form to add record

include('includes/footer.php');
?>

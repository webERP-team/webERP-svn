<?php


include('includes/session.inc');

$Title = _('Bill Of Materials Maintenance');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

function display_children($Parent, $Level, &$BOMTree) {

	global $i;

	// retrive all children of parent
	$c_result = DB_query("SELECT parent,
								component
						FROM bom
						WHERE parent='" . $Parent. "'
						ORDER BY sequence ASC");
	if (DB_num_rows($c_result) > 0) {

		while ($row = DB_fetch_array($c_result)) {
			//echo '<br />Parent: ' . $Parent . ' Level: ' . $Level . ' row[component]: ' . $row['component']  . '<br />';
			if ($Parent != $row['component']) {
				// indent and display the title of this child
				$BOMTree[$i]['Level'] = $Level; 		// Level
				if ($Level > 15) {
					prnMsg(_('A maximum of 15 levels of bill of materials only can be displayed'),'error');
					exit;
				}
				$BOMTree[$i]['Parent'] = $Parent;		// Assemble
				$BOMTree[$i]['Component'] = $row['component'];	// Component
				$i++;
			}
		}
	}
}


function CheckForRecursiveBOM ($UltimateParent, $ComponentToCheck) {

/* returns true ie 1 if the BOM contains the parent part as a component
ie the BOM is recursive otherwise false ie 0 */

	$sql = "SELECT component FROM bom WHERE parent='".$ComponentToCheck."'";
	$ErrMsg = _('An error occurred in retrieving the components of the BOM during the check for recursion');
	$DbgMsg = _('The SQL that was used to retrieve the components of the BOM and that failed in the process was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);

	if (DB_num_rows($result)!=0) {
		while ($myrow=DB_fetch_array($result)){
			if ($myrow['component']==$UltimateParent){
				return 1;
			}
			if (CheckForRecursiveBOM($UltimateParent, $myrow['component'])){
				return 1;
			}
		} //(while loop)
	} //end if $result is true

	return 0;

} //end of function CheckForRecursiveBOM

function DisplayBOMItems($UltimateParent, $Parent, $Component,$Level) {

		global $ParentMBflag;
		$sql = "SELECT bom.sequence,
						bom.component,
						stockmaster.description as itemdescription,
						stockmaster.units,
						locations.locationname,
						locations.loccode,
						workcentres.description as workcentrename,
						workcentres.code as workcentrecode,
						bom.quantity,
						bom.effectiveafter,
						bom.effectiveto,
						stockmaster.mbflag,
						bom.autoissue,
						stockmaster.controlled,
						locstock.quantity AS qoh,
						stockmaster.decimalplaces
				FROM bom INNER JOIN stockmaster
				ON bom.component=stockmaster.stockid
				INNER JOIN locations ON
				bom.loccode = locations.loccode
				INNER JOIN workcentres
				ON bom.workcentreadded=workcentres.code
				INNER JOIN locstock
				ON bom.loccode=locstock.loccode
				AND bom.component = locstock.stockid
				INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
				WHERE bom.component='".$Component."'
				AND bom.parent = '".$Parent."'";

		$ErrMsg = _('Could not retrieve the BOM components because');
		$DbgMsg = _('The SQL used to retrieve the components was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);

		//echo $TableHeader;
		$RowCounter =0;

		while ($myrow=DB_fetch_array($result)) {

			$Level1 = str_repeat('-&nbsp;',$Level-1).$Level;
			if( $myrow['mbflag']=='B'
				OR $myrow['mbflag']=='K'
				OR $myrow['mbflag']=='D') {

				$DrillText = '%s%s';
				$DrillLink = '<div class="centre">' . _('No lower levels') . '</div>';
				$DrillID='';
			} else {
				$DrillText = '<a href="%s&amp;Select=%s">' . _('Drill Down') . '</a>';
				$DrillLink = htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?';
				$DrillID=$myrow['component'];
			}
			if ($ParentMBflag!='M' AND $ParentMBflag!='G'){
				$AutoIssue = _('N/A');
			} elseif ($myrow['controlled']==0 AND $myrow['autoissue']==1){//autoissue and not controlled
				$AutoIssue = _('Yes');
			} elseif ($myrow['controlled']==1) {
				$AutoIssue = _('No');
			} else {
				$AutoIssue = _('N/A');
			}

			if ($myrow['mbflag']=='D' //dummy orservice
				OR $myrow['mbflag']=='K' //kit-set
				OR $myrow['mbflag']=='A'  // assembly
				OR $myrow['mbflag']=='G') /* ghost */ {

				$QuantityOnHand = _('N/A');
			} else {
				$QuantityOnHand = locale_number_format($myrow['qoh'],$myrow['decimalplaces']);
			}

			printf('<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td><a href="%s&amp;Select=%s&amp;SelectedComponent=%s">' . _('Edit') . '</a></td>
					 <td><a href="%s&amp;Select=%s&amp;SelectedComponent=%s&amp;delete=1&amp;ReSelect=%s&amp;Location=%s&amp;WorkCentre=%s" onclick="return confirm(\'' . _('Are you sure you wish to delete this component from the bill of material?') . '\');">' . _('Delete') . '</a></td>
					 </tr>',
					$myrow['sequence'],
					$myrow['component'],
					$myrow['itemdescription'],
					$myrow['locationname'],
					$myrow['workcentrename'],
					locale_number_format($myrow['quantity'],'Variable'),
					$myrow['units'],
					ConvertSQLDate($myrow['effectiveafter']),
					ConvertSQLDate($myrow['effectiveto']),
					$AutoIssue,
					$QuantityOnHand,
					htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
					$Parent,
					$myrow['component'],
					htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
					$Parent,
					$myrow['component'],
					$UltimateParent,
					$myrow['loccode'],
					$myrow['workcentrecode']);

		} //END WHILE LIST LOOP
} //end of function DisplayBOMItems

//---------------------------------------------------------------------------------

/* SelectedParent could come from a post or a get */
if (isset($_GET['SelectedParent'])){
	$SelectedParent = $_GET['SelectedParent'];
}else if (isset($_POST['SelectedParent'])){
	$SelectedParent = $_POST['SelectedParent'];
}



/* SelectedComponent could also come from a post or a get */
if (isset($_GET['SelectedComponent'])){
	$SelectedComponent = $_GET['SelectedComponent'];
} elseif (isset($_POST['SelectedComponent'])){
	$SelectedComponent = $_POST['SelectedComponent'];
}

/* delete function requires Location to be set */
if (isset($_GET['Location'])){
	$Location = $_GET['Location'];
} elseif (isset($_POST['Location'])){
	$Location = $_POST['Location'];
}

/* delete function requires WorkCentre to be set */
if (isset($_GET['WorkCentre'])){
	$WorkCentre = $_GET['WorkCentre'];
} elseif (isset($_POST['WorkCentre'])){
	$WorkCentre = $_POST['WorkCentre'];
}

if (isset($_GET['Select'])){
	$Select = $_GET['Select'];
} elseif (isset($_POST['Select'])){
	$Select = $_POST['Select'];
}


$msg='';

if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();
$InputError = 0;

if (isset($Select)) { //Parent Stock Item selected so display BOM or edit Component
	$SelectedParent = $Select;
	unset($Select);// = NULL;
	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/maintenance.png" title="' . _('Search') .
		'" alt="" />' . ' ' . $Title . '</p><br />';

	if (isset($SelectedParent) AND isset($_POST['Submit'])) {

		//editing a component need to do some validation of inputs

		$i = 1;

		if (!Is_Date($_POST['EffectiveAfter'])) {
			$InputError = 1;
			prnMsg(_('The effective after date field must be a date in the format') . ' ' .$_SESSION['DefaultDateFormat'],'error');
			$Errors[$i] = 'EffectiveAfter';
			$i++;
		}
		if (!Is_Date($_POST['EffectiveTo'])) {
			$InputError = 1;
			prnMsg(_('The effective to date field must be a date in the format')  . ' ' .$_SESSION['DefaultDateFormat'],'error');
			$Errors[$i] = 'EffectiveTo';
			$i++;
		}
		if (!is_numeric(filter_number_format($_POST['Quantity']))) {
			$InputError = 1;
			prnMsg(_('The quantity entered must be numeric'),'error');
			$Errors[$i] = 'Quantity';
			$i++;
		}
		if (filter_number_format($_POST['Quantity'])==0) {
			$InputError = 1;
			prnMsg(_('The quantity entered cannot be zero'),'error');
			$Errors[$i] = 'Quantity';
			$i++;
		}
		if(!Date1GreaterThanDate2($_POST['EffectiveTo'], $_POST['EffectiveAfter'])){
			$InputError = 1;
			prnMsg(_('The effective to date must be a date after the effective after date') . '<br />' . _('The effective to date is') . ' ' . DateDiff($_POST['EffectiveTo'], $_POST['EffectiveAfter'], 'd') . ' ' . _('days before the effective after date') . '! ' . _('No updates have been performed') . '.<br />' . _('Effective after was') . ': ' . $_POST['EffectiveAfter'] . ' ' . _('and effective to was') . ': ' . $_POST['EffectiveTo'],'error');
			$Errors[$i] = 'EffectiveAfter';
			$i++;
			$Errors[$i] = 'EffectiveTo';
			$i++;
		}
		if($_POST['AutoIssue']==1 AND isset($_POST['Component'])){
			$sql = "SELECT controlled FROM stockmaster WHERE stockid='" . $_POST['Component'] . "'";
			$CheckControlledResult = DB_query($sql);
			$CheckControlledRow = DB_fetch_row($CheckControlledResult);
			if ($CheckControlledRow[0]==1){
				prnMsg(_('Only non-serialised or non-lot controlled items can be set to auto issue. These items require the lot/serial numbers of items issued to the works orders to be specified so autoissue is not an option. Auto issue has been automatically set to off for this component'),'warn');
				$_POST['AutoIssue']=0;
			}
		}

		if (!in_array('EffectiveAfter', $Errors)) {
			$EffectiveAfterSQL = FormatDateForSQL($_POST['EffectiveAfter']);
		}
		if (!in_array('EffectiveTo', $Errors)) {
			$EffectiveToSQL = FormatDateForSQL($_POST['EffectiveTo']);
		}

		if (isset($SelectedParent) AND isset($SelectedComponent) AND $InputError != 1) {


			$sql = "UPDATE bom SET sequence='" . $_POST['Sequence'] . "',
						workcentreadded='" . $_POST['WorkCentreAdded'] . "',
						loccode='" . $_POST['LocCode'] . "',
						effectiveafter='" . $EffectiveAfterSQL . "',
						effectiveto='" . $EffectiveToSQL . "',
						quantity= '" . filter_number_format($_POST['Quantity']) . "',
						autoissue='" . $_POST['AutoIssue'] . "'
					WHERE bom.parent='" . $SelectedParent . "'
					AND bom.component='" . $SelectedComponent . "'";

			$ErrMsg =  _('Could not update this BOM component because');
			$DbgMsg =  _('The SQL used to update the component was');

			$result = DB_query($sql,$ErrMsg,$DbgMsg);
			$msg = _('Details for') . ' - ' . $SelectedComponent . ' ' . _('have been updated') . '.';
			UpdateCost($SelectedComponent);

		} elseif ($InputError !=1 AND ! isset($SelectedComponent) AND isset($SelectedParent)) {

		/*Selected component is null cos no item selected on first time round so must be adding a record must be Submitting new entries in the new component form */

		//need to check not recursive BOM component of itself!

			if (!CheckForRecursiveBOM ($SelectedParent, $_POST['Component'])) {

				/*Now check to see that the component is not already on the BOM */
				$sql = "SELECT component
						FROM bom
						WHERE parent='".$SelectedParent."'
						AND component='" . $_POST['Component'] . "'
						AND workcentreadded='" . $_POST['WorkCentreAdded'] . "'
						AND loccode='" . $_POST['LocCode'] . "'" ;

				$ErrMsg =  _('An error occurred in checking the component is not already on the BOM');
				$DbgMsg =  _('The SQL that was used to check the component was not already on the BOM and that failed in the process was');

				$result = DB_query($sql,$ErrMsg,$DbgMsg);

				if (DB_num_rows($result)==0) {

					$sql = "INSERT INTO bom (sequence,
											parent,
											component,
											workcentreadded,
											loccode,
											quantity,
											effectiveafter,
											effectiveto,
											autoissue)
							VALUES ('" . $_POST['Sequence'] . "',
								'".$SelectedParent."',
								'" . $_POST['Component'] . "',
								'" . $_POST['WorkCentreAdded'] . "',
								'" . $_POST['LocCode'] . "',
								" . filter_number_format($_POST['Quantity']) . ",
								'" . $EffectiveAfterSQL . "',
								'" . $EffectiveToSQL . "',
								" . $_POST['AutoIssue'] . ")";

					$ErrMsg = _('Could not insert the BOM component because');
					$DbgMsg = _('The SQL used to insert the component was');

					$result = DB_query($sql,$ErrMsg,$DbgMsg);

					UpdateCost($_POST['Component']);
					$msg = _('A new component part') . ' ' . $_POST['Component'] . ' ' . _('has been added to the bill of material for part') . ' - ' . $SelectedParent . '.';

				} else {

				/*The component must already be on the BOM */

					prnMsg( _('The component') . ' ' . $_POST['Component'] . ' ' . _('is already recorded as a component of') . ' ' . $SelectedParent . '.' . '<br />' . _('Whilst the quantity of the component required can be modified it is inappropriate for a component to appear more than once in a bill of material'),'error');
					$Errors[$i]='ComponentCode';
				}


			} //end of if its not a recursive BOM

		} //end of if no input errors

		if ($msg != '') {prnMsg($msg,'success');}

	} elseif (isset($_GET['delete']) AND isset($SelectedComponent) AND isset($SelectedParent)) {

	//the link to delete a selected record was clicked instead of the Submit button

		$sql="DELETE FROM bom
				WHERE parent='".$SelectedParent."'
				AND component='".$SelectedComponent."'
				AND loccode='".$Location."'
				AND workcentreadded='".$WorkCentre."'";

		$ErrMsg = _('Could not delete this BOM components because');
		$DbgMsg = _('The SQL used to delete the BOM was');
		$result = DB_query($sql,$ErrMsg,$DbgMsg);

		$ComponentSQL = "SELECT component
							FROM bom
							WHERE parent='" . $SelectedParent ."'";
		$ComponentResult = DB_query($ComponentSQL);
		$ComponentArray = DB_fetch_row($ComponentResult);
		UpdateCost($ComponentArray[0]);

		prnMsg(_('The component part') . ' - ' . $SelectedComponent . ' - ' . _('has been deleted from this BOM'),'success');
		// Now reset to enable New Component Details to display after delete
        unset($_GET['SelectedComponent']);
	} elseif (isset($SelectedParent)
		AND !isset($SelectedComponent)
		AND ! isset($_POST['submit'])) {

	/* It could still be the second time the page has been run and a record has been selected	for modification - SelectedParent will exist because it was sent with the new call. if		its the first time the page has been displayed with no parameters then none of the above		are true and the list of components will be displayed with links to delete or edit each.		These will call the same page again and allow update/input or deletion of the records*/
		//DisplayBOMItems($SelectedParent);

	} //BOM editing/insertion ifs


	if(isset($_GET['ReSelect'])) {
		$SelectedParent = $_GET['ReSelect'];
	}

	//DisplayBOMItems($SelectedParent);
	$sql = "SELECT stockmaster.description,
					stockmaster.mbflag
			FROM stockmaster
			WHERE stockmaster.stockid='" . $SelectedParent . "'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);

	$myrow=DB_fetch_row($result);

	$ParentMBflag = $myrow[1];

	switch ($ParentMBflag){
		case 'A':
			$MBdesc = _('Assembly');
			break;
		case 'B':
			$MBdesc = _('Purchased');
			break;
		case 'M':
			$MBdesc = _('Manufactured');
			break;
		case 'K':
			$MBdesc = _('Kit Set');
			break;
		case 'G':
			$MBdesc = _('Phantom');
			break;
	}

	echo '<br /><div class="centre"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('Select a Different BOM') . '</a></div><br />';
	// Display Manufatured Parent Items
	$sql = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
			FROM bom INNER JOIN locationusers ON locationusers.loccode=bom.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1, stockmaster
			WHERE bom.component='".$SelectedParent."'
			AND stockmaster.stockid=bom.parent
			AND stockmaster.mbflag='M'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	$ix = 0;
	if( DB_num_rows($result) > 0 ) {
     echo '<table class="selection">';
	 echo '<tr><td><div class="centre">' . _('Manufactured parent items').' : ';
	 while ($myrow = DB_fetch_array($result)){
	 	   echo (($ix)?', ':'') . '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select='.$myrow['parent'].'">' .
			$myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
			$ix++;
	 } //end while loop
	 echo '</div></td></tr>';
     echo '</table>';
	}
	// Display Assembly Parent Items
	$sql = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
		FROM bom INNER JOIN stockmaster
		ON bom.parent=stockmaster.stockid
		WHERE bom.component='".$SelectedParent."'
		AND stockmaster.mbflag='A'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	if( DB_num_rows($result) > 0 ) {
        echo '<table class="selection">';
		echo '<tr><td><div class="centre">' . _('Assembly parent items').' : ';
	 	$ix = 0;
	 	while ($myrow = DB_fetch_array($result)){
	 	   echo (($ix)?', ':'') . '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select='.$myrow['parent'].'">' .
			$myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
			$ix++;
	 	} //end while loop
	 	echo '</div></td></tr>';
        echo '</table>';
	}
	// Display Kit Sets
	$sql = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
			FROM bom INNER JOIN stockmaster
			ON bom.parent=stockmaster.stockid
			INNER JOIN locationusers ON locationusers.loccode=bom.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
			WHERE bom.component='".$SelectedParent."'
			AND stockmaster.mbflag='K'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	if( DB_num_rows($result) > 0 ) {
        echo '<table class="selection">';
		echo '<tr><td><div class="centre">' . _('Kit sets').' : ';
	 	$ix = 0;
	 	while ($myrow = DB_fetch_array($result)){
	 	   echo (($ix)?', ':'') . '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select='.$myrow['parent'].'">' .
			$myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
			$ix++;
	 	} //end while loop
	 	echo '</div></td></tr>';
        echo '</table>';
	}
	// Display Phantom/Ghosts
	$sql = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
			FROM bom INNER JOIN stockmaster
			ON bom.parent=stockmaster.stockid
			WHERE bom.component='".$SelectedParent."'
			AND stockmaster.mbflag='G'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$result=DB_query($sql,$ErrMsg,$DbgMsg);
	if( DB_num_rows($result) > 0 ) {
		echo '<table class="selection">
				<tr>
					<td><div class="centre">' . _('Phantom').' : ';
	 	$ix = 0;
	 	while ($myrow = DB_fetch_array($result)){
	 	   echo (($ix)?', ':'') . '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select='.$myrow['parent'].'">' .  $myrow['description'].'&nbsp;('.$myrow['parent'].')</a>';
			$ix++;
	 	} //end while loop
	 	echo '</div></td>
				</tr>
			</table>';
	}
	echo '<br />
			<table class="selection">';
	echo '<tr>
			<th colspan="13"><div class="centre"><b>' . $SelectedParent .' - ' . $myrow[0] . ' ('. $MBdesc. ') </b></div></th>
		</tr>';

	$BOMTree = array();
	//BOMTree is a 2 dimensional array with three elements for each item in the array - Level, Parent, Component
	//display children populates the BOM_Tree from the selected parent
	$i =0;
	display_children($SelectedParent, 1, $BOMTree);

	$TableHeader =  '<tr>
						<th>' . _('Sequence') . '</th>
						<th>' . _('Code') . '</th>
						<th>' . _('Description') . '</th>
						<th>' . _('Location') . '</th>
						<th>' . _('Work Centre') . '</th>
						<th>' . _('Quantity') . '</th>
						<th>' . _('UOM') . '</th>
						<th>' . _('Effective After') . '</th>
						<th>' . _('Effective To') . '</th>
						<th>' . _('Auto Issue') . '</th>
						<th>' . _('Qty On Hand') . '</th>
					</tr>';
	echo $TableHeader;
	if(count($BOMTree) == 0) {
		echo '<tr class="striped_row">
				<td colspan="8">' . _('No materials found.') . '</td>
			</tr>';
	} else {
		$UltimateParent = $SelectedParent;
		$RowCounter = 1;
		$BOMTree = arrayUnique($BOMTree);
		foreach($BOMTree as $BOMItem){
			$Level = $BOMItem['Level'];
			$Parent = $BOMItem['Parent'];
			$Component = $BOMItem['Component'];

			echo '<tr class="striped_row">';

			DisplayBOMItems($UltimateParent, $Parent, $Component, $Level);
		}
	}
	echo '</table>
		<br />';
    /* We do want to show the new component entry form in any case - it is a lot of work to get back to it otherwise if we need to add */

		echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Select=' . $SelectedParent .'">';
        echo '<div>';
		echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

		if (isset($_GET['SelectedComponent']) AND $InputError !=1) {
		//editing a selected component from the link to the line item

			$sql = "SELECT sequence,
						bom.loccode,
						effectiveafter,
						effectiveto,
						workcentreadded,
						quantity,
						autoissue
					FROM bom
					INNER JOIN locationusers ON locationusers.loccode=bom.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
					WHERE parent='".$SelectedParent."'
					AND component='".$SelectedComponent."'";

			$result = DB_query($sql);
			$myrow = DB_fetch_array($result);

			$_POST['Sequence'] = $myrow['sequence'];
			$_POST['LocCode'] = $myrow['loccode'];
			$_POST['EffectiveAfter'] = ConvertSQLDate($myrow['effectiveafter']);
			$_POST['EffectiveTo'] = ConvertSQLDate($myrow['effectiveto']);
			$_POST['WorkCentreAdded']  = $myrow['workcentreadded'];
			$_POST['Quantity'] = locale_number_format($myrow['quantity'],'Variable');
			$_POST['AutoIssue'] = $myrow['autoissue'];

			prnMsg(_('Edit the details of the selected component in the fields below') . '. <br />' . _('Click on the Enter Information button to update the component details'),'info');
			echo '<br />
					<input type="hidden" name="SelectedParent" value="' . $SelectedParent . '" />';
			echo '<input type="hidden" name="SelectedComponent" value="' . $SelectedComponent . '" />';
			echo '<table class="selection">';
			echo '<tr>
					<th colspan="13"><div class="centre"><b>' .  ('Edit Component Details')  . '</b></div></th>
				</tr>';
			echo '<tr>
					<td>' . _('Component') . ':</td>
					<td><b>' . $SelectedComponent . '</b></td>
					 <input type="hidden" name="Component" value="' . $SelectedComponent . '" />
				</tr>';

		} else { //end of if $SelectedComponent
			$_POST['Sequence'] = 0;
			echo '<input type="hidden" name="SelectedParent" value="' . $SelectedParent . '" />';
			/* echo "Enter the details of a new component in the fields below. <br />Click on 'Enter Information' to add the new component, once all fields are completed.";
			*/
			echo '<table class="selection">';
			echo '<tr>
					<th colspan="13"><div class="centre"><b>' . _('New Component Details')  . '</b></div></th>
				</tr>';
			echo '<tr>
					<td>' . _('Component code') . ':</td>
					<td>';
			echo '<select ' . (in_array('ComponentCode',$Errors) ?  'class="selecterror"' : '' ) .' tabindex="1" name="Component">';

			if ($ParentMBflag=='A'){ /*Its an assembly */
				$sql = "SELECT stockmaster.stockid,
							stockmaster.description
						FROM stockmaster INNER JOIN stockcategory
							ON stockmaster.categoryid = stockcategory.categoryid
						WHERE ((stockcategory.stocktype='L' AND stockmaster.mbflag ='D')
						OR stockmaster.mbflag !='D')
						AND stockmaster.mbflag !='K'
						AND stockmaster.mbflag !='A'
						AND stockmaster.controlled = 0
						AND stockmaster.stockid != '".$SelectedParent."'
						ORDER BY stockmaster.stockid";

			} else { /*Its either a normal manufac item, phantom, kitset - controlled items ok */
				$sql = "SELECT stockmaster.stockid,
							stockmaster.description
						FROM stockmaster INNER JOIN stockcategory
							ON stockmaster.categoryid = stockcategory.categoryid
						WHERE ((stockcategory.stocktype='L' AND stockmaster.mbflag ='D')
						OR stockmaster.mbflag !='D')
						AND stockmaster.mbflag !='K'
						AND stockmaster.mbflag !='A'
						AND stockmaster.stockid != '".$SelectedParent."'
						ORDER BY stockmaster.stockid";
			}

			$ErrMsg = _('Could not retrieve the list of potential components because');
			$DbgMsg = _('The SQL used to retrieve the list of potential components part was');
			$result = DB_query($sql,$ErrMsg, $DbgMsg);


			while ($myrow = DB_fetch_array($result)) {
				echo '<option value="' .$myrow['stockid'].'">' . str_pad($myrow['stockid'],21, '_', STR_PAD_RIGHT) . $myrow['description'] . '</option>';
			} //end while loop

			echo '</select></td>
				</tr>';
		}
		echo '<tr>
                <td>' . _('Sequence in BOM') . ':</td>
                <td><input type="text" class="integer" required="required" size="5" name="Sequence" value="' . $_POST['Sequence'] . '" /></td>
            </tr>';

		echo '<tr>
				<td>' . _('Location') . ': </td>
				<td><select tabindex="2" name="LocCode">';

		DB_free_result($result);
		$sql = "SELECT locationname,
					locations.loccode
				FROM locations
				INNER JOIN locationusers
					ON locationusers.loccode=locations.loccode
					AND locationusers.userid='" .  $_SESSION['UserID'] . "'
					AND locationusers.canupd=1
				WHERE locations.usedforwo = 1";
		$result = DB_query($sql);

		while ($myrow = DB_fetch_array($result)) {
			if (isset($_POST['LocCode']) AND $myrow['loccode']==$_POST['LocCode']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';

		} //end while loop

		DB_free_result($result);

		echo '</select></td>
			</tr>
			<tr>
				<td>' . _('Work Centre Added') . ': </td><td>';

		$sql = "SELECT code, description FROM workcentres INNER JOIN locationusers ON locationusers.loccode=workcentres.location AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
		$result = DB_query($sql);

		if (DB_num_rows($result)==0){
			prnMsg( _('There are no work centres set up yet') . '. ' . _('Please use the link below to set up work centres') . '.','warn');
			echo '<a href="' . $RootPath . '/WorkCentres.php">' . _('Work Centre Maintenance') . '</a></td></tr></table><br />';
			include('includes/footer.inc');
			exit;
		}

		echo '<select tabindex="3" name="WorkCentreAdded">';

		while ($myrow = DB_fetch_array($result)) {
			if (isset($_POST['WorkCentreAdded']) AND $myrow['code']==$_POST['WorkCentreAdded']) {
				echo '<option selected="selected" value="';
			} else {
				echo '<option value="';
			}
			echo $myrow['code'] . '">' . $myrow['description'] . '</option>';
		} //end while loop

		DB_free_result($result);

		echo '</select></td>
				</tr>
				<tr>
					<td>' . _('Quantity') . ': </td>
					<td><input ' . (in_array('Quantity',$Errors) ?  'class="inputerror"' : '' ) .' tabindex="4" type="text" class="number" required="required" name="Quantity" size="10" maxlength="8" title="' . _('Enter the quantity of this item required for the parent item') . '" value="';
		if (isset($_POST['Quantity'])){
			echo $_POST['Quantity'];
		} else {
			echo 1;
		}

		echo '" /></td>
			</tr>';

		if (!isset($_POST['EffectiveTo']) OR $_POST['EffectiveTo']=='') {
			$_POST['EffectiveTo'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m'),Date('d'),(Date('y')+20)));
		}
		if (!isset($_POST['EffectiveAfter']) OR $_POST['EffectiveAfter']=='') {
			$_POST['EffectiveAfter'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m'),Date('d')-1,Date('y')));
		}

		echo '<tr>
				<td>' . _('Effective After') . ' (' . $_SESSION['DefaultDateFormat'] . '):</td>
				<td><input ' . (in_array('EffectiveAfter',$Errors) ?  'class="inputerror"' : '' ) . ' tabindex="5" type="text" required="required" name="EffectiveAfter" class="date" size="11" maxlength="10" value="' . $_POST['EffectiveAfter'] .'" /></td>
			</tr>
			<tr>
				<td>' . _('Effective To') . ' (' . $_SESSION['DefaultDateFormat'] . '):</td>
				<td><input  ' . (in_array('EffectiveTo',$Errors) ?  'class="inputerror"' : '' ) . ' tabindex="6" type="text" name="EffectiveTo" class="date" size="11" maxlength="10" value="' . $_POST['EffectiveTo'] .'" /></td>
			</tr>';

		if ($ParentMBflag=='M' OR $ParentMBflag=='G'){
			echo '<tr>
					<td>' . _('Auto Issue this Component to Work Orders') . ':</td>
					<td>
					<select tabindex="7" name="AutoIssue">';

			if (!isset($_POST['AutoIssue'])){
				$_POST['AutoIssue'] = $_SESSION['AutoIssue'];
			}
			if ($_POST['AutoIssue']==0) {
				echo '<option selected="selected" value="0">' . _('No') . '</option>';
				echo '<option value="1">' . _('Yes') . '</option>';
			} else {
				echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
				echo '<option value="0">' . _('No') . '</option>';
			}


			echo '</select></td>
				</tr>';
		} else {
			echo '<input type="hidden" name="AutoIssue" value="0" />';
		}

		echo '</table>
			<br />
			<div class="centre">
				<input tabindex="8" type="submit" name="Submit" value="' . _('Enter Information') . '" />
			</div>
            </div>
			</form>';


	// end of BOM maintenance code - look at the parent selection form if not relevant
// ----------------------------------------------------------------------------------

} elseif (isset($_POST['Search'])){
	// Work around to auto select
	if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		$_POST['StockCode']='%';
	}
	if ($_POST['Keywords'] AND $_POST['StockCode']) {
		prnMsg( _('Stock description keywords have been used in preference to the Stock code extract entered'), 'info' );
	}
	if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		prnMsg( _('At least one stock description keyword or an extract of a stock code must be entered for the search'), 'info' );
	} else {
		if (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			$sql = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					stockmaster.mbflag,
					SUM(locstock.quantity) as totalonhand
				FROM stockmaster INNER JOIN locstock
				ON stockmaster.stockid = locstock.stockid
				WHERE stockmaster.description " . LIKE . " '".$SearchString."'
				AND (stockmaster.mbflag='M' OR stockmaster.mbflag='K' OR stockmaster.mbflag='A' OR stockmaster.mbflag='G')
				GROUP BY stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					stockmaster.mbflag
				ORDER BY stockmaster.stockid";

		} elseif (mb_strlen($_POST['StockCode'])>0){
			$sql = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.mbflag,
					stockmaster.decimalplaces,
					sum(locstock.quantity) as totalonhand
				FROM stockmaster INNER JOIN locstock
				ON stockmaster.stockid = locstock.stockid
				WHERE stockmaster.stockid " . LIKE  . "'%" . $_POST['StockCode'] . "%'
				AND (stockmaster.mbflag='M'
					OR stockmaster.mbflag='K'
					OR stockmaster.mbflag='G'
					OR stockmaster.mbflag='A')
				GROUP BY stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.mbflag,
					stockmaster.decimalplaces
				ORDER BY stockmaster.stockid";

		}

		$ErrMsg = _('The SQL to find the parts selected failed with the message');
		$result = DB_query($sql,$ErrMsg);

	} //one of keywords or StockCode was more than a zero length string
} //end of if search

if (!isset($SelectedParent)) {

	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ' . $Title . '</p>';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">' .
	'<div class="page_help_text">' .  _('Select a manufactured part') . ' (' . _('or Assembly or Kit part') . ') ' . _('to maintain the bill of material for using the options below') .  '<br />' . _('Parts must be defined in the stock item entry') . '/' . _('modification screen as manufactured') . ', ' . _('kits or assemblies to be available for construction of a bill of material')  . '</div>' .  '
    <div>
     <br />
     <table class="selection" cellpadding="3">
	<tr><td>' . _('Enter text extracts in the') . ' <b>' . _('description') . '</b>:</td>
		<td><input tabindex="1" type="text" name="Keywords" size="20" maxlength="25" /></td>
		<td><b>' . _('OR') . '</b></td>
		<td>' . _('Enter extract of the') . ' <b>' . _('Stock Code') . '</b>:</td>
		<td><input tabindex="2" type="text" name="StockCode" autofocus="autofocus" size="15" maxlength="18" /></td>
	</tr>
	</table>
	<br /><div class="centre"><input tabindex="3" type="submit" name="Search" value="' . _('Search Now') . '" /></div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($_POST['Search'])
		AND isset($result)
		AND !isset($SelectedParent)) {

		echo '<br />
			<table cellpadding="2" class="selection">';
		$TableHeader = '<tr>
							<th>' . _('Code') . '</th>
							<th>' . _('Description') . '</th>
							<th>' . _('On Hand') . '</th>
							<th>' . _('Units') . '</th>
						</tr>';

		echo $TableHeader;

		$j = 1;

		while ($myrow=DB_fetch_array($result)) {
			if ($myrow['mbflag']=='A' OR $myrow['mbflag']=='K' OR $myrow['mbflag']=='G'){
				$StockOnHand = _('N/A');
			} else {
				$StockOnHand = locale_number_format($myrow['totalonhand'],$myrow['decimalplaces']);
			}
			$tab = $j+3;
			printf('<tr class="striped_row">
					<td><input tabindex="' . $tab . '" type="submit" name="Select" value="%s" /></td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					</tr>',
					$myrow['stockid'],
					$myrow['description'],
					$StockOnHand,
					$myrow['units']);

			$j++;
	//end of page full new headings if
		}
	//end of while loop

		echo '</table>';

	}
	//end if results to show

	echo '</div>';
	echo '</form>';

	} //end StockID already selected
// This function created by Dominik Jungowski on PHP developer blog
function arrayUnique($array, $preserveKeys = false)
{
	//Unique Array for return
	$arrayRewrite = array();
	//Array with the md5 hashes
	$arrayHashes = array();
	foreach($array as $key => $item) {
		// Serialize the current element and create a md5 hash
		$hash = md5(serialize($item));
		// If the md5 didn't come up yet, add the element to
		// arrayRewrite, otherwise drop it
		if (!isset($arrayHashes[$hash])) {
			// Save the current element hash
			$arrayHashes[$hash] = $hash;
			//Add element to the unique Array
			if ($preserveKeys) {
				$arrayRewrite[$key] = $item;
			} else {
				$arrayRewrite[] = $item;
			}
		}
	}
	return $arrayRewrite;
}

include('includes/footer.inc');
?>

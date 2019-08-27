<?php
include ('includes/session.php');

$Title = _('Multi-Level Bill Of Materials Maintenance');

include ('includes/header.php');
include ('includes/SQL_CommonFunctions.inc');

function display_children($Parent, $Level, &$BOMTree) {
	global $i;

	// retrive all children of parent
	$ChildrenResult = DB_query("SELECT parent,
								component
						FROM bom WHERE parent='" . $Parent . "'
						ORDER BY sequence");
	if (DB_num_rows($ChildrenResult) > 0) {

		while ($MyRow = DB_fetch_array($ChildrenResult)) {
			if ($Parent != $MyRow['component']) {
				// indent and display the title of this child
				$BOMTree[$i]['Level'] = $Level; // Level
				if ($Level > 15) {
					prnMsg(_('A maximum of 15 levels of bill of materials only can be displayed'), 'error');
					exit;
				}
				$BOMTree[$i]['Parent'] = $Parent; // Assemble
				$BOMTree[$i]['Component'] = $MyRow['component']; // Component
				// call this function again to display this
				// child's children
				++$i;
				if (isset($_POST['ShowAllLevels']) and $_POST['ShowAllLevels'] == 'Yes') {
					display_children($MyRow['component'], $Level + 1, $BOMTree);
				}
			} else {
				prnMsg(_('The component and the parent is the same'), 'error');
				echo $MyRow['component'], '<br/>';
				include ('includes/footer.inc');
				exit;
			}
		}
	}
}

function CheckForRecursiveBOM($UltimateParent, $ComponentToCheck) {

	/* returns true ie 1 if the BOM contains the parent part as a component
	 ie the BOM is recursive otherwise false ie 0 */

	$SQL = "SELECT component FROM bom WHERE parent='" . $ComponentToCheck . "'";
	$ErrMsg = _('An error occurred in retrieving the components of the BOM during the check for recursion');
	$DbgMsg = _('The SQL that was used to retrieve the components of the BOM and that failed in the process was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

	if (DB_num_rows($Result) != 0) {
		while ($MyRow = DB_fetch_array($Result)) {
			if ($MyRow['component'] == $UltimateParent) {
				return 1;
			}
			if (CheckForRecursiveBOM($UltimateParent, $MyRow['component'])) {
				return 1;
			}
		} //(while loop)
		
	} //end if $Result is true
	return 0;

} //end of function CheckForRecursiveBOM
function DisplayBOMItems($UltimateParent, $Parent, $Component, $Level) {

	global $ParentMBflag;
	$SQL = "SELECT bom.component,
					stockcategory.categorydescription,
					stockmaster.description as itemdescription,
					stockmaster.units,
					locations.locationname,
					locations.loccode,
					workcentres.description as workcentrename,
					workcentres.code as workcentrecode,
					bom.quantity,
					bom.effectiveafter,
					bom.effectiveto,
					bom.sequence,
					stockmaster.mbflag,
					bom.autoissue,
					bom.remark,
					stockmaster.controlled,
					locstock.quantity AS qoh,
					stockmaster.decimalplaces
				FROM bom
				INNER JOIN stockmaster
					ON bom.component=stockmaster.stockid
				INNER JOIN stockcategory
					ON stockcategory.categoryid = stockmaster.categoryid
				INNER JOIN locations
					ON bom.loccode = locations.loccode
				INNER JOIN workcentres
					ON bom.workcentreadded=workcentres.code
				INNER JOIN locstock
					ON bom.loccode=locstock.loccode
					AND bom.component = locstock.stockid
				INNER JOIN locationusers
					ON locationusers.loccode=locations.loccode
					AND locationusers.userid='" . $_SESSION['UserID'] . "'
					AND locationusers.canupd=1
				WHERE bom.component='" . $Component . "'
					AND bom.parent = '" . $Parent . "'
				ORDER BY bom.sequence ASC";

	$ErrMsg = _('Could not retrieve the BOM components because');
	$DbgMsg = _('The SQL used to retrieve the components was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

	$RowCounter = 0;

	while ($MyRow = DB_fetch_array($Result)) {

		$Level1 = str_repeat('-&nbsp;', $Level - 1) . $Level;
		if ($ParentMBflag != 'M' and $ParentMBflag != 'G') {
			$AutoIssue = _('N/A');
		} elseif ($MyRow['controlled'] == 0 and $MyRow['autoissue'] == 1) { //autoissue and not controlled
			$AutoIssue = _('Yes');
		} elseif ($MyRow['controlled'] == 1) {
			$AutoIssue = _('No');
		} else {
			$AutoIssue = _('N/A');
		}

		if ($MyRow['mbflag'] == 'D' //dummy orservice
		 or $MyRow['mbflag'] == 'K' //kit-set
		 or $MyRow['mbflag'] == 'A' // assembly
		 or $MyRow['mbflag'] == 'G') /* ghost */ {

			$QuantityOnHand = _('N/A');
		} else {
			$QuantityOnHand = locale_number_format($MyRow['qoh'], $MyRow['decimalplaces']);
		}

		$TextIndent = $Level . 'em';
		if (!empty($MyRow['remark'])) {
			$MyRow['remark'] = ' **' . ' ' . $MyRow['remark'];
		}

		echo '<tr class="striped_row">
				<td class="number" style="text-align:left;text-indent:', $TextIndent, ';" >', $Level1, '</td>
				<td class="number">', $MyRow['sequence'], '</td>
				<td>', $MyRow['categorydescription'], '</td>
				<td>', $MyRow['component'], '</td>
				<td>', $MyRow['itemdescription'], '</td>
				<td>', $MyRow['locationname'], '</td>
				<td>', $MyRow['workcentrename'], '</td>
				<td class="number">', locale_number_format($MyRow['quantity'], 'Variable'), '</td>
				<td>', $MyRow['units'], '</td>
				<td class="noprint">', ConvertSQLDate($MyRow['effectiveafter']), '</td>
				<td class="noprint">', ConvertSQLDate($MyRow['effectiveto']), '</td>
				<td class="noprint">', $AutoIssue, '</td>
				<td class="number noprint">', $QuantityOnHand, '</td>
				<td class="noprint"><a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($Parent), '&SelectedComponent=', urlencode($MyRow['component']), '&Location=', urlencode($MyRow['loccode']), '&WorkCentre=', urlencode($MyRow['workcentrecode']), '&ShowAllLevels=', $_POST['ShowAllLevels'], '&Edit=Yes">', _('Edit'), '</a></td>';

		if ($MyRow['mbflag'] == 'B' or $MyRow['mbflag'] == 'K' or $MyRow['mbflag'] == 'D') {
			echo '<td class="noprint">
					<div class="centre">', _('No lower levels'), '</div>
				</td>';
		} else {
			echo '<td class="noprint">
					<a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($MyRow['component']), '&ShowAllLevels=', $_POST['ShowAllLevels'], '">' . _('Drill Down') . '</a>
				</td>';
		}

		echo '<td class="noprint">
				<a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8') . '?SelectedParent=', urlencode($Parent), '&SelectedComponent=', urlencode($MyRow['component']), '&delete=1&ReSelect=', urlencode($UltimateParent), '&Location=', urlencode($MyRow['loccode']), '&WorkCentre=', urlencode($MyRow['workcentrecode']), '&ShowAllLevels=', $_POST['ShowAllLevels'], '" onclick="return confirm(\'' . _('Are you sure you wish to delete this component from this bill of materials?') . '\', \'Confirm Delete\', this);">', _('Delete'), '</a></td>
				</tr><tr><td colspan="11" style="text-indent:', $TextIndent, ';">', $MyRow['remark'], '</td>
			 </tr>';

	} //END WHILE LIST LOOP
	
} //end of function DisplayBOMItems
/* SelectedParent could come from a post or a get */
if (isset($_GET['SelectedParent'])) {
	$SelectedParent = $_GET['SelectedParent'];
} else if (isset($_POST['SelectedParent'])) {
	$SelectedParent = $_POST['SelectedParent'];
}

if (isset($_GET['ShowAllLevels'])) {
	$_POST['ShowAllLevels'] = $_GET['ShowAllLevels'];
}

// Default behaviour is to show all levels
if (!isset($_POST['ShowAllLevels'])) {
	$_POST['ShowAllLevels'] = 'Yes';
}

if (isset($_POST['ComponentSearch']) or isset($_POST['Next']) or isset($_POST['Previous'])) {

	if (!isset($_POST['Next']) and !isset($_POST['Previous'])) {
		$_POST['Offset'] = 0;
	}

	if (isset($_POST['Previous']) and $_POST['Offset'] != 0) {
		$_POST['Offset'] = $_POST['Offset'] - 1;
	}

	if (isset($_POST['Next'])) {
		$_POST['Offset'] = $_POST['Offset'] + 1;
	}

	echo '<div class="toplink noprint">
			<a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($SelectedParent), '">', _('Return to main BOM screen'), '</a>
		</div>';

	echo '<p class="page_title_text noprint">
			<img src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/magnifier.png" title="', _('Search'), '" alt="" /> ', _('Select component to add to BOM'), '
		</p>';
	//DisplayBOMItems($SelectedParent);
	$SQL = "SELECT stockmaster.description,
					stockmaster.mbflag
			FROM stockmaster
			WHERE stockmaster.stockid='" . $SelectedParent . "'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

	$MyRow = DB_fetch_row($Result);

	$ParentMBflag = $MyRow[1];
	if ($_POST['StockCat'] == 'All') {
		$_POST['StockCat'] = '';
	}
	if ($ParentMBflag == 'A') {
		/*Its an assembly */
		$SQL = "SELECT stockmaster.stockid,
							stockmaster.description
						FROM stockmaster INNER JOIN stockcategory
							ON stockmaster.categoryid = stockcategory.categoryid
						WHERE ((stockcategory.stocktype='L' AND stockmaster.mbflag ='D')
							OR stockmaster.mbflag !='D')
							AND stockmaster.mbflag !='K'
							AND stockmaster.mbflag !='A'
							AND stockmaster.controlled = 0
							AND stockmaster.stockid != '" . $SelectedParent . "'
							AND stockmaster.stockid LIKE '%" . $_POST['StockCode'] . "%'
							AND stockmaster.description LIKE '%" . $_POST['Keywords'] . "%'
							AND stockmaster.categoryid LIKE '%" . $_POST['StockCat'] . "%'
						ORDER BY stockmaster.stockid
						LIMIT " . $_SESSION['DisplayRecordsMax'] . "
						OFFSET " . ($_POST['Offset'] * $_SESSION['DisplayRecordsMax']);

	} else {
		/*Its either a normal manufac item, phantom, kitset - controlled items ok */
		$SQL = "SELECT stockmaster.stockid,
							stockmaster.description
						FROM stockmaster INNER JOIN stockcategory
							ON stockmaster.categoryid = stockcategory.categoryid
						WHERE ((stockcategory.stocktype='L' AND stockmaster.mbflag ='D')
							OR stockmaster.mbflag !='D')
							AND stockmaster.mbflag !='K'
							AND stockmaster.mbflag !='A'
							AND stockmaster.stockid != '" . $SelectedParent . "'
							AND stockmaster.stockid LIKE '%" . $_POST['StockCode'] . "%'
							AND stockmaster.description LIKE '%" . $_POST['Keywords'] . "%'
							AND stockmaster.categoryid LIKE '%" . $_POST['StockCat'] . "%'
						ORDER BY stockmaster.stockid
						LIMIT " . $_SESSION['DisplayRecordsMax'] . "
						OFFSET " . ($_POST['Offset'] * $_SESSION['DisplayRecordsMax']);
	}

	$ErrMsg = _('Could not retrieve the list of potential components because');
	$DbgMsg = _('The SQL used to retrieve the list of potential components part was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

	echo '<form method="post" action="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($SelectedParent), '">';
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	echo '<table class="noprint">
			<tr>
				<th colspan="3">
					<input style="float:left" type="submit" name="Previous" value="<<', _('Previous'), '" />
					<input style="float:right" type="submit" name="Next" value="', _('Next'), '>>" />
				</th>
			</tr>
			<tr>
				<th>', _('Item Code'), '</th>
				<th>', _('Item Description'), '</th>
				<th></th>
			</tr>';
	while ($MyRow = DB_fetch_array($Result)) {
		echo '<tr>
				<td>', $MyRow['stockid'], '</td>
				<td>', $MyRow['description'], '</td>
				<td><a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($SelectedParent), '&SelectedComponent=', urlencode($MyRow['stockid']), '&Add=Yes">', _('Add to the BOM'), '</a></td>
			</tr>';
	}
	echo '</table>';
	echo '<input type="hidden" name="Offset" value="', $_POST['Offset'], '" />';
	echo '<input type="hidden" name="StockCode" value="', $_POST['StockCode'], '" />';
	echo '<input type="hidden" name="Keywords" value="', $_POST['Keywords'], '" />';
	echo '<input type="hidden" name="StockCat" value="', $_POST['StockCat'], '" />';
	echo '</form>';
	include ('includes/footer.php');
	exit;
}

/* SelectedComponent could also come from a post or a get */
if (isset($_GET['SelectedComponent'])) {
	$SelectedComponent = $_GET['SelectedComponent'];
} elseif (isset($_POST['SelectedComponent'])) {
	$SelectedComponent = $_POST['SelectedComponent'];
}

/* delete function requires Location to be set */
if (isset($_GET['Location'])) {
	$Location = $_GET['Location'];
} elseif (isset($_POST['Location'])) {
	$Location = $_POST['Location'];
} else {
	$Location = $_SESSION['UserStockLocation'];
}

/* delete function requires WorkCentre to be set */
if (isset($_GET['WorkCentre'])) {
	$WorkCentre = $_GET['WorkCentre'];
} elseif (isset($_POST['WorkCentre'])) {
	$WorkCentre = $_POST['WorkCentre'];
}

if (isset($_POST['Cancel'])) {
	unset($SelectedComponent);
}

//---------------------------------------------------------------------------------
if (isset($_POST['renumber'])) {
	$SQL = "SELECT parent,
					sequence,
					component,
					workcentreadded,
					loccode
				FROM bom
				WHERE parent='" . $SelectedParent . "'
				ORDER BY sequence ASC";
	$Result = DB_query($SQL);
	$Sequence = 10;
	while ($MyRow = DB_fetch_array($Result)) {
		$UpdateSQL = "UPDATE bom
						SET sequence='" . $Sequence . "'
					WHERE parent='" . $SelectedParent . "'
						AND sequence='" . $MyRow['sequence'] . "'
						AND component='" . $MyRow['component'] . "'
						AND workcentreadded='" . $MyRow['workcentreadded'] . "'
						AND loccode='" . $MyRow['loccode'] . "'";
		$UpdateResult = DB_query($UpdateSQL);
		$Sequence = $Sequence + 10;
	}
}

$Msg = '';

$InputError = 0;

if (isset($_GET['Add']) or isset($_GET['Edit'])) {
	//editing a selected component from the link to the line item
	/* We do want to show the new component entry form in any case - it is a lot of work to get back to it otherwise if we need to add */
	echo '<form method="post" action="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', $SelectedParent, '">';
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	$SQL = "SELECT stockmaster.description,
					stockmaster.mbflag
			FROM stockmaster
			WHERE stockmaster.stockid='" . $SelectedParent . "'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

	$MyRow = DB_fetch_row($Result);

	$ParentMBflag = $MyRow[1];

	echo '<p class="page_title_text noprint">
			<img src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/maintenance.png" title="', _('Search'), '" alt="" /> ', _('Component Details'), '
		</p>';

	echo '<table class="noprint">';
	if (isset($_GET['Edit'])) {
		$SQL = "SELECT bom.loccode,
						effectiveafter,
						effectiveto,
						sequence,
						workcentreadded,
						quantity,
						autoissue,
						remark
					FROM bom
					INNER JOIN locationusers
						ON locationusers.loccode=bom.loccode
						AND locationusers.userid='" . $_SESSION['UserID'] . "'
						AND locationusers.canupd=1
					WHERE parent='" . $SelectedParent . "'
						AND component='" . $SelectedComponent . "'
						AND workcentreadded='" . $WorkCentre . "'
						AND bom.loccode='" . $Location . "'";

		$Result = DB_query($SQL);
		$MyRow = DB_fetch_array($Result);

		$_POST['LocCode'] = $MyRow['loccode'];
		$_POST['EffectiveAfter'] = ConvertSQLDate($MyRow['effectiveafter']);
		$_POST['EffectiveTo'] = ConvertSQLDate($MyRow['effectiveto']);
		$_POST['Sequence'] = $MyRow['sequence'];
		$_POST['WorkCentreAdded'] = $MyRow['workcentreadded'];
		$_POST['Quantity'] = locale_number_format($MyRow['quantity'], 'Variable');
		$_POST['AutoIssue'] = $MyRow['autoissue'];
		$_POST['Comment'] = $MyRow['remark'];

		echo '<input type="hidden" name="Edit" value="Yes" />';
		echo '<tr>
				<th colsspan="2">', ('Edit Component Details'), '</th>
			</tr>';
	} else {

		$SQL = "SELECT MAX(sequence) AS seqnum FROM bom WHERE parent='" . $SelectedParent . "'";
		$Result = DB_query($SQL);
		$MyRow = DB_fetch_array($Result);

		$_POST['LocCode'] = $Location;
		$_POST['EffectiveAfter'] = date($_SESSION['DefaultDateFormat']);
		$_POST['EffectiveTo'] = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, Date('m'), Date('d'), (Date('y') + 20)));
		$_POST['Sequence'] = $MyRow['seqnum'] + 10;
		$_POST['WorkCentreAdded'] = '';
		$_POST['Quantity'] = locale_number_format(1, 'Variable');
		$_POST['AutoIssue'] = $_SESSION['AutoIssue'];
		$_POST['Comment'] = '';

		echo '<input type="hidden" name="Add" value="Yes" />';
		echo '<tr>
				<th colspan="2">', _('New Component Details'), '</th>
			</tr>';
	}

	echo '<input type="hidden" name="ShowAllLevels" value="', $_POST['ShowAllLevels'], '" />';
	echo '<input type="hidden" name="SelectedParent" value="', $SelectedParent, '" />';
	echo '<input type="hidden" name="SelectedComponent" value="', $SelectedComponent, '" />';

	echo '<tr>
			<td>', _('Component'), ':</td>
			<td>', $SelectedComponent, '<td>
		</tr>';

	echo '<tr>
			<td>', _('Sequence in BOM'), ':</td>
			<td><input type="text" class="integer" required="required" name="Sequence" size="6" autofocus="autofocus" value="', $_POST['Sequence'], '" /></td>
		</tr>';

	/* echo "Enter the details of a new component in the fields below. <br />Click on 'Enter Information' to add the new component, once all fields are completed.";
	*/

	$SQL = "SELECT locationname,
					locations.loccode
				FROM locations
				INNER JOIN locationusers
					ON locationusers.loccode=locations.loccode
					AND locationusers.userid='" . $_SESSION['UserID'] . "'
					AND locationusers.canupd=1
				WHERE locations.usedforwo = 1";
	$Result = DB_query($SQL);
	echo '<tr>
			<td>', _('Location'), ': </td>
			<td>
				<select required="required" name="LocCode">';

	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['LocCode']) and $MyRow['loccode'] == $_POST['LocCode']) {
			echo '<option selected="selected" value="', $MyRow['loccode'], '">', $MyRow['locationname'], '</option>';
		} else {
			echo '<option value="', $MyRow['loccode'], '">', $MyRow['locationname'], '</option>';
		}
	} //end while loop
	DB_free_result($Result);

	echo '</select>
		</td>
	</tr>';

	$SQL = "SELECT code,
					description
				FROM workcentres
				INNER JOIN locationusers
					ON locationusers.loccode=workcentres.location
					AND locationusers.userid='" . $_SESSION['UserID'] . "'
					AND locationusers.canupd=1";

	$Result = DB_query($SQL);

	if (DB_num_rows($Result) == 0) {
		prnMsg(_('There are no work centres set up yet') . '. ' . _('Please use the link below to set up work centres') . '.', 'warn');
		echo '<a href="', $RootPath, '/WorkCentres.php">', _('Work Centre Maintenance'), '</a></field></fieldset>';
		include ('includes/footer.php');
		exit;
	}
	echo '<tr>
			<td>', _('Work Centre Added'), ': </td>
			<td>
				<select required="required" name="WorkCentreAdded">';
	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['WorkCentreAdded']) and $MyRow['code'] == $_POST['WorkCentreAdded']) {
			echo '<option selected="selected" value="', $MyRow['code'], '">', $MyRow['description'], '</option>';
		} else {
			echo '<option value="', $MyRow['code'], '">', $MyRow['description'], '</option>';
		}
	} //end while loop
	echo '</select>
		</td>
	</tr>';

	echo '<tr>
			<td>', _('Quantity'), ': </td>
			<td><input type="text" class="number" name="Quantity" size="10" required="required" maxlength="8" value="', $_POST['Quantity'], '" /></td>
		</tr>';

	echo '<tr>
			<td>', _('Effective After'), ' (', $_SESSION['DefaultDateFormat'], '):</td>
			<td><input type="text" name="EffectiveAfter" class="date" size="11" required="required" maxlength="10" value="', $_POST['EffectiveAfter'], '" /></td>
		</tr>';

	echo '<tr>
			<td>', _('Effective To'), ' (', $_SESSION['DefaultDateFormat'], '):</td>
			<td><input type="text" name="EffectiveTo" class="date" size="11" required="required" maxlength="10" value="', $_POST['EffectiveTo'], '" /></td>
		</tr>';

	if ($ParentMBflag == 'M' or $ParentMBflag == 'G') {
		echo '<tr>
				<td>', _('Auto Issue this Component to Work Orders'), ':</td>
				<td>
					<select required="required" name="AutoIssue">';
		if ($_POST['AutoIssue'] == 0) {
			echo '<option selected="selected" value="0">', _('No'), '</option>';
			echo '<option value="1">', _('Yes'), '</option>';
		} else {
			echo '<option selected="selected" value="1">', _('Yes'), '</option>';
			echo '<option value="0">', _('No'), '</option>';
		}
		echo '</select>
			</td>
		</tr>';
	} else {
		echo '<input type="hidden" name="AutoIssue" value="0" />';
	}

	if (!isset($_POST['Comment'])) {
		$_POST['Comment'] = '';
	}

	echo '<tr>
			<td>', _('Comment'), '</td>
			<td><textarea  rows="3" col="20" name="Comment" >', $_POST['Comment'], '</textarea></td>
		</tr>';

	echo '</table>
			<div class="centre">
				<input type="submit" name="Submit" value="', _('Enter Information'), '" />
				<input type="submit" name="Cancel" value="', _('Cancel'), '" />
			</div>
		</form>';
	include ('includes/footer.php');
	exit;
}

if (isset($SelectedParent)) { //Parent Stock Item selected so display BOM or edit Component
	echo '<div class="toplink noprint">
			<a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '">', _('Select a Different BOM'), '</a>
		</div>';

	echo '<p class="page_title_text noprint">
			<img src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/maintenance.png" title="', _('Search'), '" alt="" /> ', $Title, '
		</p>';

	if (isset($SelectedParent) and isset($_POST['Submit'])) {

		//editing a component need to do some validation of inputs
		if (!is_date($_POST['EffectiveAfter'])) {
			$InputError = 1;
			prnMsg(_('The effective after date field must be a date in the format') . ' ' . $_SESSION['DefaultDateFormat'], 'error');
		}
		if (!is_date($_POST['EffectiveTo'])) {
			$InputError = 1;
			prnMsg(_('The effective to date field must be a date in the format') . ' ' . $_SESSION['DefaultDateFormat'], 'error');
		}
		if (!is_numeric(filter_number_format($_POST['Quantity']))) {
			$InputError = 1;
			prnMsg(_('The quantity entered must be numeric'), 'error');
		}
		/* Comment this out to make substittute material can be recorded in the BOM
		if (filter_number_format($_POST['Quantity']) == 0) {
		$InputError = 1;
		prnMsg(_('The quantity entered cannot be zero'), 'error');
		}
		+		 */
		if (!Date1GreaterThanDate2($_POST['EffectiveTo'], $_POST['EffectiveAfter'])) {
			$InputError = 1;
			prnMsg(_('The effective to date must be a date after the effective after date') . '<br />' . _('The effective to date is') . ' ' . DateDiff($_POST['EffectiveTo'], $_POST['EffectiveAfter'], 'd') . ' ' . _('days before the effective after date') . '! ' . _('No updates have been performed') . '.<br />' . _('Effective after was') . ': ' . $_POST['EffectiveAfter'] . ' ' . _('and effective to was') . ': ' . $_POST['EffectiveTo'], 'error');
		}
		if ($_POST['AutoIssue'] == 1 and isset($_POST['Component'])) {
			$SQL = "SELECT controlled FROM stockmaster WHERE stockid='" . $_POST['Component'] . "'";
			$CheckControlledResult = DB_query($SQL);
			$CheckControlledRow = DB_fetch_row($CheckControlledResult);
			if ($CheckControlledRow[0] == 1) {
				prnMsg(_('Only non-serialised or non-lot controlled items can be set to auto issue. These items require the lot/serial numbers of items issued to the works orders to be specified so autoissue is not an option. Auto issue has been automatically set to off for this component'), 'warn');
				$_POST['AutoIssue'] = 0;
			}
		}

		if ($_POST['SelectedComponent'] == $SelectedParent) {
			$InputError = 1;
			prnMsg(_('The component selected is the same with the parent, it is not allowed'), 'error');
		}

		$EffectiveAfterSQL = FormatDateForSQL($_POST['EffectiveAfter']);
		$EffectiveToSQL = FormatDateForSQL($_POST['EffectiveTo']);

		if (isset($SelectedParent) and isset($SelectedComponent) and isset($_POST['Edit']) and $InputError != 1) {

			$SQL = "UPDATE bom SET workcentreadded='" . $_POST['WorkCentreAdded'] . "',
						loccode='" . $_POST['LocCode'] . "',
						effectiveafter='" . $EffectiveAfterSQL . "',
						effectiveto='" . $EffectiveToSQL . "',
						sequence='" . $_POST['Sequence'] . "',
						quantity= '" . filter_number_format($_POST['Quantity']) . "',
						autoissue='" . $_POST['AutoIssue'] . "',
						remark='" . $_POST['Comment'] . "'
					WHERE bom.parent='" . $SelectedParent . "'
						AND bom.component='" . $SelectedComponent . "'
						AND workcentreadded='" . $_POST['WorkCentreAdded'] . "'
						AND loccode='" . $_POST['LocCode'] . "'";

			$ErrMsg = _('Could not update this BOM component because');
			$DbgMsg = _('The SQL used to update the component was');

			$Result = DB_query($SQL, $ErrMsg, $DbgMsg);
			$Msg = _('Details for') . ' - ' . $SelectedComponent . ' ' . _('have been updated') . '.';
			UpdateCost($SelectedComponent);

		} elseif ($InputError != 1 and isset($SelectedComponent) and isset($SelectedParent) and isset($_POST['Add'])) {

			/*Selected component is null cos no item selected on first time round so must be adding a record must be Submitting new entries in the new component form */

			//need to check not recursive BOM component of itself!
			if (!CheckForRecursiveBOM($SelectedParent, $_POST['SelectedComponent'])) {

				/*Now check to see that the component is not already on the BOM */
				$SQL = "SELECT component
						FROM bom
						WHERE parent='" . $SelectedParent . "'
						AND component='" . $_POST['SelectedComponent'] . "'
						AND workcentreadded='" . $_POST['WorkCentreAdded'] . "'
						AND loccode='" . $_POST['LocCode'] . "'";

				$ErrMsg = _('An error occurred in checking the component is not already on the BOM');
				$DbgMsg = _('The SQL that was used to check the component was not already on the BOM and that failed in the process was');

				$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

				if (DB_num_rows($Result) == 0) {

					$SQL = "INSERT INTO bom (parent,
											component,
											workcentreadded,
											loccode,
											quantity,
											sequence,
											effectiveafter,
											effectiveto,
											autoissue,
											remark)
							VALUES ('" . $SelectedParent . "',
								'" . $_POST['SelectedComponent'] . "',
								'" . $_POST['WorkCentreAdded'] . "',
								'" . $_POST['LocCode'] . "',
								" . filter_number_format($_POST['Quantity']) . ",
								" . $_POST['Sequence'] . ",
								'" . $EffectiveAfterSQL . "',
								'" . $EffectiveToSQL . "',
								" . $_POST['AutoIssue'] . ",
								'" . $_POST['Comment'] . "'
								)";

					$ErrMsg = _('Could not insert the BOM component because');
					$DbgMsg = _('The SQL used to insert the component was');

					$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

					UpdateCost($_POST['SelectedComponent']);
					$Msg = _('A new component part') . ' ' . $_POST['SelectedComponent'] . ' ' . _('has been added to the bill of material for part') . ' - ' . $SelectedParent . '.';

				} else {

					/*The component must already be on the BOM */

					prnMsg(_('The component') . ' ' . $_POST['Component'] . ' ' . _('is already recorded as a component of') . ' ' . $SelectedParent . '.' . '<br />' . _('Whilst the quantity of the component required can be modified it is inappropriate for a component to appear more than once in a bill of material'), 'error');
				}

			} //end of if its not a recursive BOM
			
		} //end of if no input errors
		unset($SelectedComponent);
		if ($Msg != '') {
			prnMsg($Msg, 'success');
		}

	} elseif (isset($_GET['delete']) and isset($SelectedComponent) and isset($SelectedParent)) {

		//the link to delete a selected record was clicked instead of the Submit button
		$SQL = "DELETE FROM bom
				WHERE parent='" . $SelectedParent . "'
				AND component='" . $SelectedComponent . "'
				AND loccode='" . $Location . "'
				AND workcentreadded='" . $WorkCentre . "'";

		$ErrMsg = _('Could not delete this BOM components because');
		$DbgMsg = _('The SQL used to delete the BOM was');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

		$ComponentSQL = "SELECT component
							FROM bom
							WHERE parent='" . $SelectedParent . "'";
		$ComponentResult = DB_query($ComponentSQL);
		$ComponentArray = DB_fetch_row($ComponentResult);
		UpdateCost($ComponentArray[0]);

		prnMsg(_('The component part') . ' - ' . $SelectedComponent . ' - ' . _('has been deleted from this BOM'), 'success');
		// Now reset to enable New Component Details to display after delete
		unset($SelectedComponent);

	} elseif (isset($SelectedParent) and !isset($SelectedComponent) and !isset($_POST['submit'])) {

		/* It could still be the second time the page has been run and a record has been selected	for modification - SelectedParent will exist because it was sent with the new call. if		its the first time the page has been displayed with no parameters then none of the above		are true and the list of components will be displayed with links to delete or edit each.		These will call the same page again and allow update/input or deletion of the records*/
		//DisplayBOMItems($SelectedParent);
		
	} //BOM editing/insertion ifs
	

	if (isset($_GET['ReSelect'])) {
		$SelectedParent = $_GET['ReSelect'];
	}

	//DisplayBOMItems($SelectedParent);
	$SQL = "SELECT stockmaster.description,
					stockmaster.mbflag
			FROM stockmaster
			WHERE stockmaster.stockid='" . $SelectedParent . "'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

	$MyRow = DB_fetch_row($Result);

	$ParentMBflag = $MyRow[1];

	switch ($ParentMBflag) {
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

	// Display Manufatured Parent Items
	$SQL = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
			FROM bom, stockmaster
			WHERE bom.component='" . $SelectedParent . "'
			AND stockmaster.stockid=bom.parent
			AND stockmaster.mbflag='M'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);
	$i = 0;
	if (DB_num_rows($Result) > 0) {
		echo '<table class="selection noprint">
				<tr>
					<th>', _('Manufactured parent items'), ' : ';
		while ($MyRow = DB_fetch_array($Result)) {
			echo (($i) ? ', ' : ''), '<a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($MyRow['parent']), '">', $MyRow['description'], '&nbsp;(', $MyRow['parent'], ')</a>';
			++$i;
		} //end while loop
		echo '</th>
			</tr>
		</table>';
	}
	// Display Assembly Parent Items
	$SQL = "SELECT bom.parent,
				stockmaster.description,
				stockmaster.mbflag
		FROM bom INNER JOIN stockmaster
		ON bom.parent=stockmaster.stockid
		WHERE bom.component='" . $SelectedParent . "'
		AND stockmaster.mbflag='A'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);
	if (DB_num_rows($Result) > 0) {
		echo '<table class="noprint">
				<tr>
					<th>', _('Assembly parent items'), ' : ';
		$i = 0;
		while ($MyRow = DB_fetch_array($Result)) {
			echo (($i) ? ', ' : ''), '<a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($MyRow['parent']), '">', $MyRow['description'], '&nbsp;(', $MyRow['parent'], ')</a>';
			++$i;
		} //end while loop
		echo '</th>
			</tr>
		</table>';
	}
	// Display Kit Sets
	$SQL = "SELECT bom.parent,
					stockmaster.description,
					stockmaster.mbflag
				FROM bom
				INNER JOIN stockmaster
					ON bom.parent=stockmaster.stockid
				INNER JOIN locationusers
					ON locationusers.loccode=bom.loccode
					AND locationusers.userid='" . $_SESSION['UserID'] . "'
					AND locationusers.canupd=1
			WHERE bom.component='" . $SelectedParent . "'
			AND stockmaster.mbflag='K'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);
	if (DB_num_rows($Result) > 0) {
		echo '<table>
				<tr>
					<th>', _('Kit sets'), ' : ';
		$i = 0;
		while ($MyRow = DB_fetch_array($Result)) {
			echo (($i) ? ', ' : ''), '<a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($MyRow['parent']), '">', $MyRow['description'], '&nbsp;(', $MyRow['parent'], ')</a>';
			++$i;
		} //end while loop
		echo '</th>
			</tr>
		</table>';
	}
	// Display Phantom/Ghosts
	$SQL = "SELECT bom.parent,
					stockmaster.description,
					stockmaster.mbflag
				FROM bom
				INNER JOIN stockmaster
					ON bom.parent=stockmaster.stockid
				INNER JOIN locationusers
					ON locationusers.loccode=bom.loccode
					AND locationusers.userid='" . $_SESSION['UserID'] . "'
					AND locationusers.canupd=1
				WHERE bom.component='" . $SelectedParent . "'
					AND stockmaster.mbflag='G'";

	$ErrMsg = _('Could not retrieve the description of the parent part because');
	$DbgMsg = _('The SQL used to retrieve description of the parent part was');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);
	if (DB_num_rows($Result) > 0) {
		echo '<table>
				<tr>
					<th>', _('Phantom'), ' : ';
		$i = 0;
		while ($MyRow = DB_fetch_array($Result)) {
			echo (($i) ? ', ' : ''), '<a href="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '?SelectedParent=', urlencode($MyRow['parent']), '">', $MyRow['description'], '&nbsp;(', $MyRow['parent'], ')</a>';
			++$i;
		} //end while loop
		echo '</th>
			</tr>
		</table>';
	}

	echo '<form action="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '" method="post">';
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';

	echo '<input type="hidden" name="SelectedParent" value="', $SelectedParent, '" />';
	echo '<table>';
	echo '<tr>
			<th colspan="15"><b>', $SelectedParent, ' - ', $MyRow[0], ' (', $MBdesc, ') </b></th>
			<th>
				<img src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/printer.png" class="PrintIcon noprint" title="', _('Print'), '" alt="', _('Print'), '" onclick="window.print();" />
			</th>
		</tr>';

	$BOMTree = array();
	//BOMTree is a 2 dimensional array with three elements for each item in the array - Level, Parent, Component
	//display children populates the BOM_Tree from the selected parent
	$i = 0;
	display_children($SelectedParent, 1, $BOMTree);

	echo '<tr>
			<th>', _('Level'), '</th>
			<th>', _('Sequence'), '</th>
			<th>', _('Category Description'), '</th>
			<th>', _('Code'), '</th>
			<th>', _('Description'), '</th>
			<th>', _('Location'), '</th>
			<th>', _('Work Centre'), '</th>
			<th>', _('Quantity'), '</th>
			<th>', _('UOM'), '</th>
			<th class="noprint">', _('Effective After'), '</th>
			<th class="noprint">', _('Effective To'), '</th>
			<th class="noprint">', _('Auto Issue'), '</th>
			<th class="noprint">', _('Qty On Hand'), '</th>
			<th colspan="3"></th>
		</tr>';
	if (count($BOMTree) == 0) {
		echo '<tr>
				<td colspan="8">', _('No materials found.'), '</td>
			</tr>';
	} else {
		$UltimateParent = $SelectedParent;
		$k = 0;
		$RowCounter = 1;
		$BOMTree = arrayUnique($BOMTree);
		foreach ($BOMTree as $BOMItem) {
			$Level = $BOMItem['Level'];
			$Parent = $BOMItem['Parent'];
			$Component = $BOMItem['Component'];
			DisplayBOMItems($UltimateParent, $Parent, $Component, $Level);
		}
	}
	echo '</table>
		<input type="submit" class="noprint" name="renumber" value="Re-Sequence the BOM" />
	</form>';

	if (!isset($SelectedComponent)) {
		echo '<form action="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '" method="post">';
		echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';

		echo '<table class="noprint">
				<tr>
					<th colspan="2">', _('Select new component for BOM'), '</th>
				</tr>';

		$SQL = "SELECT categoryid,
						categorydescription
				FROM stockcategory
				WHERE stocktype='F' OR stocktype='D' OR stocktype='L'
				ORDER BY categorydescription";

		$Result = DB_query($SQL);
		echo '<tr>
				<td>', _('Select a Stock Category'), ': </td>
				<td>
					<select name="StockCat">';
		if (!isset($_POST['StockCat']) or $_POST['StockCat'] == 'All') {
			echo '<option selected="selected" value="All">', _('All'), '</option>';
			$_POST['StockCat'] = 'All';
		} else {
			echo '<option value="All">', _('All'), '</option>';
		}
		while ($MyRow = DB_fetch_array($Result)) {
			if ($_POST['StockCat'] == $MyRow['categoryid']) {
				echo '<option selected="selected" value="', $MyRow['categoryid'], '">', $MyRow['categorydescription'], '</option>';
			} else {
				echo '<option value="', $MyRow['categoryid'], '">', $MyRow['categorydescription'], '</option>';
			}
		}
		echo '</select>
			</td>
		</tr>';

		echo '<tr>
				<td>', _('Enter text extracts in the description'), ':</td>
				<td><input type="text" name="Keywords" size="20" maxlength="25" /></td>
			</tr>
			<tr>
				<td><b>', _('OR'), '</b></td>
			</tr>
			<tr>
				<td>', _('Enter extract of the Stock Code'), ':</td>
				<td><input type="text" autofocus="autofocus" name="StockCode" size="15" maxlength="20" /></td>
			</field>
		</table>
		<input type="hidden" name="SelectedParent" value="', $SelectedParent, '" />
		<div class="centre noprint">
			<input type="submit" name="ComponentSearch" value="', _('Search Now'), '" />
		</div>
	</form>';
	}

	// end of BOM maintenance code - look at the parent selection form if not relevant
	// ----------------------------------------------------------------------------------
	
} elseif (isset($_POST['Search'])) {
	// Work around to auto select
	if ($_POST['Keywords'] == '' and $_POST['StockCode'] == '') {
		$_POST['StockCode'] = '%';
	}
	if ($_POST['Keywords'] and $_POST['StockCode']) {
		prnMsg(_('Stock description keywords have been used in preference to the Stock code extract entered'), 'info');
	}
	if ($_POST['Keywords'] == '' and $_POST['StockCode'] == '') {
		prnMsg(_('At least one stock description keyword or an extract of a stock code must be entered for the search'), 'info');
	} else {
		if (mb_strlen($_POST['Keywords']) > 0) {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					stockmaster.mbflag,
					SUM(locstock.quantity) as totalonhand
				FROM stockmaster INNER JOIN locstock
				ON stockmaster.stockid = locstock.stockid
				WHERE stockmaster.description " . LIKE . " '" . $SearchString . "'
				AND (stockmaster.mbflag='M' OR stockmaster.mbflag='K' OR stockmaster.mbflag='A' OR stockmaster.mbflag='G')
				GROUP BY stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.decimalplaces,
					stockmaster.mbflag
				ORDER BY stockmaster.stockid";

		} elseif (mb_strlen($_POST['StockCode']) > 0) {
			$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units,
					stockmaster.mbflag,
					stockmaster.decimalplaces,
					sum(locstock.quantity) as totalonhand
				FROM stockmaster INNER JOIN locstock
				ON stockmaster.stockid = locstock.stockid
				WHERE stockmaster.stockid " . LIKE . "'%" . $_POST['StockCode'] . "%'
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
		$Result = DB_query($SQL, $ErrMsg);

	} //one of keywords or StockCode was more than a zero length string
	
} //end of if search
if (!isset($SelectedParent)) {

	echo '<p class="page_title_text">
			<img src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/magnifier.png" title="', _('Search'), '" alt="" />', ' ', $Title, '
		</p>';

	echo '<form action="', htmlspecialchars(basename(__FILE__), ENT_QUOTES, 'UTF-8'), '" method="post">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<div class="page_help_text">
			', _('Select a manufactured part'), ' (', _('or Assembly or Kit part'), ') ', _('to maintain the bill of material for using the options below'), '<br />', _('Parts must be defined in the stock item entry'), '/', _('modification screen as manufactured'), ', ', _('kits or assemblies to be available for construction of a bill of material'), '
		</div>';

	echo '<table>
			<tr>
				<th colspan="2">', _('Select the parent item for the BOM'), '</th>
			</tr>
			<tr>
				<td>', _('Enter text extracts in the description'), '</td>
				<td><input type="text" name="Keywords" size="20" maxlength="25" /></td>
			</tr>
			<tr>
				<th>', _('OR'), '</th>
			</tr>
			<tr>
				<td>', _('Enter extract of the Stock Code'), '</td>
				<td><input type="text" autofocus="autofocus" name="StockCode" size="15" maxlength="18" /></td>
			</tr>';
	echo '<tr>
			<td><label for="ShowAllLevels">', _('Show all levels'), '</label></td>
			<td><select name="ShowAllLevels">';
	if (isset($_POST['ShowAllLevels']) and $_POST['ShowAllLevels'] == 'Yes') {
		echo '<option selected="selected" value="Yes">', _('Yes'), '</option>';
		echo '<option value="No">', _('No'), '</option>';
	} else {
		echo '<option value="Yes">', _('Yes'), '</option>';
		echo '<option selected="selected" value="No">', _('No'), '</option>';
	}

	echo '</select>
		</td>
	</tr>';
	echo '</table>';
	echo '<div class="centre">
			<input type="submit" name="Search" value="', _('Search Now'), '" />
		</div>';

	if (isset($_POST['Search']) and isset($Result) and !isset($SelectedParent)) {

		echo '<table cellpadding="2">
				<tr>
					<th>', _('Code'), '</th>
					<th>', _('Description'), '</th>
					<th>', _('On Hand'), '</th>
					<th>', _('Units'), '</th>
				</tr>';

		$j = 0;
		while ($MyRow = DB_fetch_array($Result)) {
			if ($MyRow['mbflag'] == 'A' or $MyRow['mbflag'] == 'K' or $MyRow['mbflag'] == 'G') {
				$StockOnHand = _('N/A');
			} else {
				$StockOnHand = locale_number_format($MyRow['totalonhand'], $MyRow['decimalplaces']);
			}
			echo '<tr class="striped_row">
					<td><input type="submit" name="SelectedParent" value="', $MyRow['stockid'], '" /></td>
					<td>', $MyRow['description'], '</td>
					<td class="number">', $StockOnHand, '</td>
					<td>', $MyRow['units'], '</td>
				</tr>';
			//end of page full new headings if
			
		}
		//end of while loop
		echo '</table>';

	}
	//end if results to show
	echo '</form>';

} //end StockID already selected
// This function created by Dominik Jungowski on PHP developer blog
function arrayUnique($Array, $PreserveKeys = false) {
	//Unique Array for return
	$ArrayRewrite = array();
	//Array with the md5 hashes
	$ArrayHashes = array();
	foreach ($Array as $Key => $Item) {
		// Serialize the current element and create a md5 hash
		$Hash = md5(serialize($Item));
		// If the md5 didn't come up yet, add the element to
		// arrayRewrite, otherwise drop it
		if (!isset($ArrayHashes[$Hash])) {
			// Save the current element hash
			$ArrayHashes[$Hash] = $Hash;
			//Add element to the unique Array
			if ($PreserveKeys) {
				$ArrayRewrite[$Key] = $Item;
			} else {
				$ArrayRewrite[] = $Item;
			}
		}
	}
	return $ArrayRewrite;
}

include ('includes/footer.php');
?>
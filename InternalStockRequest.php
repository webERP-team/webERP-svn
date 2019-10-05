<?php

include ('includes/DefineStockRequestClass.php');

include ('includes/session.php');
$Title = _('Create an Internal Materials Request');
$ViewTopic = 'Inventory';
$BookMark = 'CreateRequest';
include ('includes/header.php');
include ('includes/SQL_CommonFunctions.inc');

if (isset($_GET['New'])) {
	unset($_SESSION['Transfer']);
	$_SESSION['Request'] = new StockRequest();
}

if (isset($_POST['Update'])) {
	$InputError = 0;
	if ($_POST['Department'] == '') {
		prnMsg(_('You must select a Department for the request'), 'error');
		$InputError = 1;
	}
	if ($_POST['Location'] == '') {
		prnMsg(_('You must select a Location to request the items from'), 'error');
		$InputError = 1;
	}
	if ($InputError == 0) {
		$_SESSION['Request']->Department = $_POST['Department'];
		$_SESSION['Request']->Location = $_POST['Location'];
		$_SESSION['Request']->DispatchDate = $_POST['DispatchDate'];
		$_SESSION['Request']->Narrative = $_POST['Narrative'];
	}
}

if (isset($_POST['Edit'])) {
	$_SESSION['Request']->LineItems[$_POST['LineNumber']]->Quantity = $_POST['Quantity'];
}

if (isset($_GET['Delete'])) {
	unset($_SESSION['Request']->LineItems[$_GET['Delete']]);
	echo '<br />';
	prnMsg(_('The line was successfully deleted'), 'success');
	echo '<br />';
}

foreach ($_POST as $key => $value) {
	if (mb_strstr($key, 'StockID')) {
		$Index = mb_substr($key, 7);
		if (filter_number_format($_POST['Quantity' . $Index]) > 0) {
			$StockID = $value;
			$ItemDescription = $_POST['ItemDescription' . $Index];
			$DecimalPlaces = $_POST['DecimalPlaces' . $Index];
			$NewItem_array[$StockID] = filter_number_format($_POST['Quantity' . $Index]);
			$_POST['Units' . $StockID] = $_POST['Units' . $Index];
			$_SESSION['Request']->AddLine($StockID, $ItemDescription, $NewItem_array[$StockID], $_POST['Units' . $StockID], $DecimalPlaces);
		}
	}
}

if (isset($_POST['Submit']) and (!empty($_SESSION['Request']->LineItems))) {

	DB_Txn_Begin();
	$InputError = 0;
	if ($_SESSION['Request']->Department == '') {
		prnMsg(_('You must select a Department for the request'), 'error');
		$InputError = 1;
	}
	if ($_SESSION['Request']->Location == '') {
		prnMsg(_('You must select a Location to request the items from'), 'error');
		$InputError = 1;
	}
	if ($InputError == 0) {
		$RequestNo = GetNextTransNo(38);
		$HeaderSQL = "INSERT INTO stockrequest (dispatchid,
											loccode,
											departmentid,
											despatchdate,
											narrative,
											initiator)
										VALUES(
											'" . $RequestNo . "',
											'" . $_SESSION['Request']->Location . "',
											'" . $_SESSION['Request']->Department . "',
											'" . FormatDateForSQL($_SESSION['Request']->DispatchDate) . "',
											'" . $_SESSION['Request']->Narrative . "',
											'" . $_SESSION['UserID'] . "')";
		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The request header record could not be inserted because');
		$DbgMsg = _('The following SQL to insert the request header record was used');
		$Result = DB_query($HeaderSQL, $ErrMsg, $DbgMsg, true);

		foreach ($_SESSION['Request']->LineItems as $LineItems) {
			$LineSQL = "INSERT INTO stockrequestitems (dispatchitemsid,
													dispatchid,
													stockid,
													quantity,
													decimalplaces,
													uom)
												VALUES(
													'" . $LineItems->LineNumber . "',
													'" . $RequestNo . "',
													'" . $LineItems->StockID . "',
													'" . $LineItems->Quantity . "',
													'" . $LineItems->DecimalPlaces . "',
													'" . $LineItems->UOM . "')";
			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The request line record could not be inserted because');
			$DbgMsg = _('The following SQL to insert the request header record was used');
			$Result = DB_query($LineSQL, $ErrMsg, $DbgMsg, true);
		}

		$EmailSQL = "SELECT email
					FROM www_users, departments
					WHERE departments.authoriser = www_users.userid
						AND departments.departmentid = '" . $_SESSION['Request']->Department . "'";
		$EmailResult = DB_query($EmailSQL);
		if ($myEmail = DB_fetch_array($EmailResult)) {
			$ConfirmationText = _('An internal stock request has been created and is waiting for your authoritation');
			$EmailSubject = _('Internal Stock Request needs your authoritation');
			if ($_SESSION['SmtpSetting'] == 0) {
				mail($myEmail['email'], $EmailSubject, $ConfirmationText);
			} else {
				include ('includes/htmlMimeMail.php');
				$mail = new htmlMimeMail();
				$mail->setSubject($EmailSubject);
				$mail->setText($ConfirmationText);
				$Result = SendmailBySmtp($mail, array($myEmail['email']));
			}
		}
	}
	DB_Txn_Commit();
	prnMsg(_('The internal stock request has been entered and now needs to be authorised'), 'success');
	echo '<br /><div class="centre"><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?New=Yes">', _('Create another request'), '</a></div>';
	include ('includes/footer.php');
	unset($_SESSION['Request']);
	exit;
} elseif (isset($_POST['Submit'])) {
	prnMsg(_('There are no items added to this request'), 'error');
}

echo '<p class="page_title_text"><img src="', $RootPath, '/css/', $Theme, '/images/supplier.png" title="', _('Dispatch'), '" alt="" />', ' ', $Title, '</p>';

if (isset($_GET['Edit'])) {
	echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">';
	echo '<div>';
	echo '<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';
	echo '<table class="selection">';
	echo '<tr>
			<th colspan="2"><h4>', _('Edit the Request Line'), '</h4></th>
		</tr>';
	echo '<tr>
			<td>', _('Line number'), '</td>
			<td>', $_SESSION['Request']->LineItems[$_GET['Edit']]->LineNumber, '</td>
		</tr>
		<tr>
			<td>', _('Stock Code'), '</td>
			<td>', $_SESSION['Request']->LineItems[$_GET['Edit']]->StockID, '</td>
		</tr>
		<tr>
			<td>', _('Item Description'), '</td>
			<td>', $_SESSION['Request']->LineItems[$_GET['Edit']]->ItemDescription, '</td>
		</tr>
		<tr>
			<td>', _('Unit of Measure'), '</td>
			<td>', $_SESSION['Request']->LineItems[$_GET['Edit']]->UOM, '</td>
		</tr>
		<tr>
			<td>', _('Quantity Requested'), '</td>
			<td><input type="text" class="number" name="Quantity" value="', locale_number_format($_SESSION['Request']->LineItems[$_GET['Edit']]->Quantity, $_SESSION['Request']->LineItems[$_GET['Edit']]->DecimalPlaces), '" /></td>
		</tr>';
	echo '<input type="hidden" name="LineNumber" value="', $_SESSION['Request']->LineItems[$_GET['Edit']]->LineNumber, '" />';
	echo '</table>
		<br />';
	echo '<div class="centre">
			<input type="submit" name="Edit" value="', _('Update Line'), '" />
		</div>
        </div>
		</form>';
	include ('includes/footer.php');
	exit;
}

echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">
	<div>
	<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
	<table class="selection">
	<tr>
		<th colspan="2"><h4>', _('Internal Stock Request Details'), '</h4></th>
	</tr>
	<tr>
		<td>' . _('Department') . ':</td>';
if ($_SESSION['AllowedDepartment'] == 0) {
	// any internal department allowed
	$SQL = "SELECT departmentid,
				description
			FROM departments
			ORDER BY description";
} else {
	// just 1 internal department allowed
	$SQL = "SELECT departmentid,
				description
			FROM departments
			WHERE departmentid = '" . $_SESSION['AllowedDepartment'] . "'
			ORDER BY description";
}
$Result = DB_query($SQL);
echo '<td><select name="Department">';
while ($MyRow = DB_fetch_array($Result)) {
	if (isset($_SESSION['Request']->Department) and $_SESSION['Request']->Department == $MyRow['departmentid']) {
		echo '<option selected value="', $MyRow['departmentid'], '">', htmlspecialchars($MyRow['description'], ENT_QUOTES, 'UTF-8'), '</option>';
	} else {
		echo '<option value="', $MyRow['departmentid'], '">', htmlspecialchars($MyRow['description'], ENT_QUOTES, 'UTF-8'), '</option>';
	}
}
echo '</select></td>
	</tr>
	<tr>
		<td>' . _('Location from which to request stock') . ':</td>';
$SQL = "SELECT locations.loccode,
			locationname
		FROM locations
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" . $_SESSION['UserID'] . "' AND locationusers.canupd=1
		WHERE internalrequest = 1
		ORDER BY locationname";

$Result = DB_query($SQL);
echo '<td><select name="Location">
		<option value="">', _('Select a Location'), '</option>';
while ($MyRow = DB_fetch_array($Result)) {
	if (isset($_SESSION['Request']->Location) and $_SESSION['Request']->Location == $MyRow['loccode']) {
		echo '<option selected value="', $MyRow['loccode'], '">', $MyRow['loccode'], ' - ', htmlspecialchars($MyRow['locationname'], ENT_QUOTES, 'UTF-8'), '</option>';
	} else {
		echo '<option value="', $MyRow['loccode'], '">', $MyRow['loccode'], ' - ', htmlspecialchars($MyRow['locationname'], ENT_QUOTES, 'UTF-8'), '</option>';
	}
}
echo '</select></td>
	</tr>
	<tr>
		<td>', _('Date when required'), ':</td>
		<td><input type="text" class="date" name="DispatchDate" maxlength="10" size="11" value="', $_SESSION['Request']->DispatchDate, '" /></td>
	</tr>
	<tr>
		<td>', _('Narrative'), ':</td>
		<td><textarea name="Narrative" cols="30" rows="5">', $_SESSION['Request']->Narrative, '</textarea></td>
	</tr>
	</table>
	<br />
	<div class="centre">
		<input type="submit" name="Update" value="', _('Update'), '" />
	</div>
    </div>
	</form>';

if (!isset($_SESSION['Request']->Location)) {
	include ('includes/footer.php');
	exit;
}

echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">
	<div>
	<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
	<br />
	<table class="selection">
	<thead>
	<tr>
		<th colspan="7"><h4>', _('Details of Items Requested'), '</h4></th>
	</tr>
	<tr>
		<th>', _('Line Number'), '</th>
		<th class="ascending">', _('Item Code'), '</th>
		<th class="ascending">', _('Item Description'), '</th>
		<th class="ascending">', _('Quantity Required'), '</th>
		<th>', _('UOM'), '</th>
		</tr>
	</thead>
	<tbody>';

if (isset($_SESSION['Request']->LineItems)) {
	foreach ($_SESSION['Request']->LineItems as $LineItems) {
		echo '<tr class="striped_row">
				<td>', $LineItems->LineNumber, '</td>
				<td>', $LineItems->StockID, '</td>
				<td>', $LineItems->ItemDescription, '</td>
				<td class="number">', locale_number_format($LineItems->Quantity, $LineItems->DecimalPlaces), '</td>
				<td>', $LineItems->UOM, '</td>
				<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Edit=', urlencode($LineItems->LineNumber), '">', _('Edit'), '</a></td>
				<td><a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?Delete=', urlencode($LineItems->LineNumber), '">', _('Delete'), '</a></td>
			</tr>';
	}
}

echo '</tbody>
	</table>
	<br />
	<div class="centre">
		<input type="submit" name="Submit" value="', _('Submit'), '" />
	</div>
	<br />
    </div>
    </form>';

echo '<p class="page_title_text">
		<img src="', $RootPath, '/css/', $Theme, '/images/magnifier.png" title="', _('Search'), '" alt="" />', ' ', _('Search for Inventory Items'), '</p>
	<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">
	<div>
	<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';

$SQL = "SELECT stockcategory.categoryid,
				stockcategory.categorydescription
		FROM stockcategory
		INNER JOIN internalstockcatrole
			ON stockcategory.categoryid = internalstockcatrole.categoryid
		WHERE internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
			ORDER BY stockcategory.categorydescription";

$Result1 = DB_query($SQL);
if (DB_num_rows($Result1) == 0) {
	echo '<p class="bad">', _('Problem Report'), ':<br />', _('There are no stock categories currently defined please use the link below to set them up'), '</p>
		<br />
		<a href="', $RootPath, '/StockCategories.php">', _('Define Stock Categories'), '</a>';
	exit;
}

echo '<table class="selection">
	<tr>
		<td>' . _('In Stock Category') . ':<select name="StockCat">';

if (!isset($_POST['StockCat'])) {
	$_POST['StockCat'] = 'All';
}

if ($_POST['StockCat'] == 'All') {
	echo '<option selected value="All">' . _('All Authorized') . '</option>';
} else {
	echo '<option value="All">' . _('All Authorized') . '</option>';
}

while ($MyRow1 = DB_fetch_array($Result1)) {
	if ($MyRow1['categoryid'] == $_POST['StockCat']) {
		echo '<option selected value="', $MyRow1['categoryid'], '">', $MyRow1['categorydescription'], '</option>';
	} else {
		echo '<option value="', $MyRow1['categoryid'], '">', $MyRow1['categorydescription'], '</option>';
	}
}

echo '</select></td>
	<td>', _('Enter partial'), '<b> ', _('Description'), '</b>:</td>';

if (isset($_POST['Keywords'])) {
	echo '<td><input type="text" name="Keywords" value="', $_POST['Keywords'], '" size="20" maxlength="25" /></td>';
} else {
	echo '<td><input type="text" name="Keywords" size="20" maxlength="25" /></td>';
}

echo '</tr>
		<tr>
			<td></td>
			<td><h3>', _('OR'), ' ', '</h3>', _('Enter partial'), ' <b>', _('Stock Code'), '</b>:</td>';

if (isset($_POST['StockCode'])) {
	echo '<td><input type="text" autofocus="autofocus" name="StockCode" value="', $_POST['StockCode'], '" size="15" maxlength="18" /></td>';
} else {
	echo '<td><input type="text" name="StockCode" size="15" maxlength="18" /></td>';
}

echo '</tr>
	</table>
	<br />
	<div class="centre">
		<input type="submit" name="Search" value="', _('Search Now'), '" />
	</div>
	<br />
	</div>
	</form>';

if (isset($_POST['Search']) or isset($_POST['Next']) or isset($_POST['Previous'])) {

	if ($_POST['Keywords'] != '' and $_POST['StockCode'] == '') {
		prnMsg(_('Order Item description has been used in search'), 'warn');
	} elseif ($_POST['StockCode'] != '' and $_POST['Keywords'] == '') {
		prnMsg(_('Stock Code has been used in search'), 'warn');
	} elseif ($_POST['Keywords'] == '' and $_POST['StockCode'] == '') {
		prnMsg(_('Stock Category has been used in search'), 'warn');
	}

	if (isset($_POST['Keywords']) and mb_strlen($_POST['Keywords']) > 0) {
		//insert wildcard characters in spaces
		$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.description " . LIKE . " '" . $SearchString . "'
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}

	} elseif (mb_strlen($_POST['StockCode']) > 0) {

		$_POST['StockCode'] = mb_strtoupper($_POST['StockCode']);
		$SearchString = '%' . $_POST['StockCode'] . '%';

		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}

	} else {
		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
							stockmaster.description,
							stockmaster.units as stockunits,
							stockmaster.decimalplaces
					FROM stockmaster
					INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN internalstockcatrole
						ON stockcategory.categoryid = internalstockcatrole.categoryid
					WHERE stockmaster.mbflag <>'G'
						AND stockmaster.discontinued=0
						AND internalstockcatrole.secroleid= " . $_SESSION['AccessLevel'] . "
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}
	}

	if (isset($_POST['Next'])) {
		$Offset = $_POST['NextList'];
	}
	if (isset($_POST['Previous'])) {
		$Offset = $_POST['PreviousList'];
	}
	if (!isset($Offset) or $Offset < 0) {
		$Offset = 0;
	}
	$SQL = $SQL . ' LIMIT ' . $_SESSION['DisplayRecordsMax'] . ' OFFSET ' . ($_SESSION['DisplayRecordsMax'] * $Offset);

	$ErrMsg = _('There is a problem selecting the part records to display because');
	$DbgMsg = _('The SQL used to get the part selection was');
	$SearchResult = DB_query($SQL, $ErrMsg, $DbgMsg);

	if (DB_num_rows($SearchResult) == 0) {
		prnMsg(_('There are no products available meeting the criteria specified'), 'info');
	}
	if (DB_num_rows($SearchResult) < $_SESSION['DisplayRecordsMax']) {
		$Offset = 0;
	}

} //end of if search
if (isset($SearchResult)) {
	$j = 1;
	echo '<br />
		<div class="page_help_text">', _('Select an item by entering the quantity required.  Click Order when ready.'), '</div>
		<br />
		<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post" id="orderform">
		<div>
		<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
		<table class="table1">
		<thead>
		<tr>
			<td>
					<input type="hidden" name="PreviousList" value="', ($Offset - 1), '" />
					<input tabindex="', ($j + 8), '" type="submit" name="Previous" value="', _('Previous'), '" /></td>
				<td class="centre" colspan="6">
				<input type="hidden" name="order_items" value="1" />
					<input tabindex="', ($j + 9), '" type="submit" value="', _('Add to Requisition'), '" /></td>
			<td>
					<input type="hidden" name="NextList" value="', ($Offset + 1), '" />
					<input tabindex="', ($j + 10), '" type="submit" name="Next" value="', _('Next'), '" /></td>
			</tr>
			<tr>
				<th class="ascending">', _('Code'), '</th>
				<th class="ascending">', _('Description'), '</th>
				<th>', _('Units'), '</th>
				<th class="ascending">', _('On Hand'), '</th>
				<th class="ascending">', _('On Demand'), '</th>
				<th class="ascending">', _('On Order'), '</th>
				<th class="ascending">', _('Available'), '</th>
				<th class="ascending">', _('Quantity'), '</th>
			</tr>
		</thead>
		<tbody>';

	$ImageSource = _('No Image');

	$i = 0;
	while ($MyRow = DB_fetch_array($SearchResult)) {
		if ($MyRow['decimalplaces'] == '') {
			/* This REALLY seems to be a redundant (unnecessary) re-query?
			 * The default on stockmaster is 0, so an empty string should never
			 * be true, as decimalplaces is in all queries from lines 382-482.
			*/
			$DecimalPlacesSQL = "SELECT decimalplaces
								FROM stockmaster
								WHERE stockid='" . $MyRow['stockid'] . "'";
			$DecimalPlacesResult = DB_query($DecimalPlacesSQL);
			$DecimalPlacesRow = DB_fetch_array($DecimalPlacesResult);
			$DecimalPlaces = $DecimalPlacesRow['decimalplaces'];
		} else {
			$DecimalPlaces = $MyRow['decimalplaces'];
		}

		$QOHSQL = "SELECT sum(locstock.quantity) AS qoh
							   FROM locstock
					WHERE locstock.stockid='" . $MyRow['stockid'] . "'
						AND loccode = '" . $_SESSION['Request']->Location . "'";
		$QOHResult = DB_query($QOHSQL);
		$QOHRow = DB_fetch_array($QOHResult);
		$QOH = $QOHRow['qoh'];

		// Find the quantity on outstanding sales orders
		$SQL = "SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
				FROM salesorderdetails
				INNER JOIN salesorders
				 ON salesorders.orderno = salesorderdetails.orderno
				 WHERE salesorders.fromstkloc='" . $_SESSION['Request']->Location . "'
				 AND salesorderdetails.completed=0
				 AND salesorders.quotation=0
				 AND salesorderdetails.stkcode='" . $MyRow['stockid'] . "'";
		$ErrMsg = _('The demand for this product from') . ' ' . $_SESSION['Request']->Location . ' ' . _('cannot be retrieved because');
		$DemandResult = DB_query($SQL, $ErrMsg);

		$DemandRow = DB_fetch_row($DemandResult);
		if ($DemandRow[0] != null) {
			$DemandQty = $DemandRow[0];
		} else {
			$DemandQty = 0;
		}

		$PurchQty = GetQuantityOnOrderDueToPurchaseOrders($MyRow['stockid'], '');
		$WoQty = GetQuantityOnOrderDueToWorkOrders($MyRow['stockid'], '');

		$OnOrder = $PurchQty + $WoQty;
		$Available = $QOH - $DemandQty + $OnOrder;

		echo '<tr class="striped_row">
				<td>', $MyRow['stockid'], '</td>
				<td>', $MyRow['description'], '</td>
				<td>', $MyRow['stockunits'], '</td>
				<td class="number">', locale_number_format($QOH, $DecimalPlaces), '</td>
				<td class="number">', locale_number_format($DemandQty, $DecimalPlaces), '</td>
				<td class="number">', locale_number_format($OnOrder, $DecimalPlaces), '</td>
				<td class="number">', locale_number_format($Available, $DecimalPlaces), '</td>
				<td><input class="number" ', ($i == 0 ? 'autofocus="autofocus"' : ''), ' tabindex="', ($j + 7), '" type="text" size="6" name="Quantity', $i, '" value="0" />
				<input type="hidden" name="StockID', $i, '" value="', $MyRow['stockid'], '" />
				</td>
			</tr>
			<input type="hidden" name="DecimalPlaces', $i, '" value="', $MyRow['decimalplaces'], '" />
			<input type="hidden" name="ItemDescription', $i, '" value="', $MyRow['description'], '" />
			<input type="hidden" name="Units', $i, '" value="', $MyRow['stockunits'], '" />';
		$i++;
	}
	#end of while loop
	echo '</tbody>
		<tfoot>
			<tr>
				<td><input type="hidden" name="PreviousList" value="', ($Offset - 1), '" />
					<input tabindex="', ($j + 7), '" type="submit" name="Previous" value="', _('Previous'), '" /></td>
			<td class="centre" colspan="6"><input type="hidden" name="order_items" value="1" />
					<input tabindex="', ($j + 8), '" type="submit" value="', _('Add to Requisition'), '" /></td>
				<td><input type="hidden" name="NextList" value="', ($Offset + 1), '" />
					<input tabindex="', ($j + 9), '" type="submit" name="Next" value="', _('Next'), '" /></td>
			</tr>
		</tfoot>
		</table>
       </div>
       </form>';
} #end if SearchResults to show
//*********************************************************************************************************
include ('includes/footer.php');
?>

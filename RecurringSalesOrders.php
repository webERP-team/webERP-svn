<?php
/* This is where the details specific to the recurring order are entered and the template committed to the database once the Process button is hit */

include('includes/DefineCartClass.php');

/* Session started in header.php for password checking the session will contain the details of the order from the Cart class object. The details of the order come from SelectOrderItems.php */
/* webERP manual links before header.php */
$ViewTopic= 'SalesOrders';
$BookMark = 'RecurringSalesOrders';

include('includes/session.php');
$Title = _('Recurring Orders');


/* webERP manual links before header.php */
$ViewTopic= 'SalesOrders';
$BookMark = 'RecurringSalesOrders';

include('includes/header.php');

if (empty($_GET['identifier'])) {
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

if (isset($_GET['NewRecurringOrder'])){
	$NewRecurringOrder ='Yes';
} elseif (isset($_POST['NewRecurringOrder'])){
	$NewRecurringOrder ='Yes';
} else {
	$NewRecurringOrder ='No';
	if (isset($_GET['ModifyRecurringSalesOrder'])){

		$_POST['ExistingRecurrOrderNo'] = $_GET['ModifyRecurringSalesOrder'];

		/*Need to read in the existing recurring order template */

		$_SESSION['Items'.$identifier] = new cart;

		/*read in all the guff from the selected order into the Items cart  */

		$OrderHeaderSQL = "SELECT recurringsalesorders.debtorno,
									debtorsmaster.name,
									recurringsalesorders.branchcode,
									recurringsalesorders.customerref,
									recurringsalesorders.comments,
									recurringsalesorders.orddate,
									recurringsalesorders.ordertype,
									salestypes.sales_type,
									recurringsalesorders.shipvia,
									recurringsalesorders.deliverto,
									recurringsalesorders.deladd1,
									recurringsalesorders.deladd2,
									recurringsalesorders.deladd3,
									recurringsalesorders.deladd4,
									recurringsalesorders.deladd5,
									recurringsalesorders.deladd6,
									recurringsalesorders.contactphone,
									recurringsalesorders.contactemail,
									recurringsalesorders.freightcost,
									debtorsmaster.currcode,
									recurringsalesorders.fromstkloc,
									recurringsalesorders.frequency,
									recurringsalesorders.stopdate,
									recurringsalesorders.lastrecurrence,
									recurringsalesorders.autoinvoice
								FROM recurringsalesorders
								INNER JOIN debtorsmaster
								ON recurringsalesorders.debtorno = debtorsmaster.debtorno
								INNER JOIN salestypes
								ON recurringsalesorders.ordertype=salestypes.typeabbrev
								WHERE recurringsalesorders.recurrorderno = '" . $_GET['ModifyRecurringSalesOrder'] . "'";

		$ErrMsg =  _('The order cannot be retrieved because');
		$GetOrdHdrResult = DB_query($OrderHeaderSQL,$ErrMsg);

		if (DB_num_rows($GetOrdHdrResult)==1) {

			$myrow = DB_fetch_array($GetOrdHdrResult);

			$_SESSION['Items'.$identifier]->DebtorNo = $myrow['debtorno'];
	/*CustomerID defined in header.php */
			$_SESSION['Items'.$identifier]->Branch = $myrow['branchcode'];
			$_SESSION['Items'.$identifier]->CustomerName = $myrow['name'];
			$_SESSION['Items'.$identifier]->CustRef = $myrow['customerref'];
			$_SESSION['Items'.$identifier]->Comments = $myrow['comments'];

			$_SESSION['Items'.$identifier]->DefaultSalesType =$myrow['ordertype'];
			$_SESSION['Items'.$identifier]->SalesTypeName =$myrow['sales_type'];
			$_SESSION['Items'.$identifier]->DefaultCurrency = $myrow['currcode'];
			$_SESSION['Items'.$identifier]->ShipVia = $myrow['shipvia'];
			$BestShipper = $myrow['shipvia'];
			$_SESSION['Items'.$identifier]->DeliverTo = $myrow['deliverto'];
			//$_SESSION['Items'.$identifier]->DeliveryDate = ConvertSQLDate($myrow['deliverydate']);
			$_SESSION['Items'.$identifier]->DelAdd1 = $myrow['deladd1'];
			$_SESSION['Items'.$identifier]->DelAdd2 = $myrow['deladd2'];
			$_SESSION['Items'.$identifier]->DelAdd3 = $myrow['deladd3'];
			$_SESSION['Items'.$identifier]->DelAdd4 = $myrow['deladd4'];
			$_SESSION['Items'.$identifier]->DelAdd5 = $myrow['deladd5'];
			$_SESSION['Items'.$identifier]->DelAdd6 = $myrow['deladd6'];
			$_SESSION['Items'.$identifier]->PhoneNo = $myrow['contactphone'];
			$_SESSION['Items'.$identifier]->Email = $myrow['contactemail'];
			$_SESSION['Items'.$identifier]->Location = $myrow['fromstkloc'];
			$_SESSION['Items'.$identifier]->Quotation = 0;
			$FreightCost = $myrow['freightcost'];
			$_SESSION['Items'.$identifier]->Orig_OrderDate = $myrow['orddate'];
			$_POST['StopDate'] = ConvertSQLDate($myrow['stopdate']);
			$_POST['StartDate'] = ConvertSQLDate($myrow['lastrecurrence']);
			$_POST['Frequency'] = $myrow['frequency'];
			$_POST['AutoInvoice'] = $myrow['autoinvoice'];

	/*need to look up customer name from debtors master then populate the line items array with the sales order details records */
			$LineItemsSQL = "SELECT recurrsalesorderdetails.stkcode,
									stockmaster.description,
									stockmaster.longdescription,
									stockmaster.volume,
									stockmaster.grossweight,
									stockmaster.units,
									recurrsalesorderdetails.unitprice,
									recurrsalesorderdetails.quantity,
									recurrsalesorderdetails.discountpercent,
									recurrsalesorderdetails.narrative,
									locstock.quantity as qohatloc,
									stockmaster.mbflag,
									stockmaster.discountcategory,
									stockmaster.decimalplaces
									FROM recurrsalesorderdetails INNER JOIN stockmaster
									ON recurrsalesorderdetails.stkcode = stockmaster.stockid
									INNER JOIN locstock ON locstock.stockid = stockmaster.stockid
									WHERE  locstock.loccode = '" . $myrow['fromstkloc'] . "'
									AND recurrsalesorderdetails.recurrorderno ='" . $_GET['ModifyRecurringSalesOrder'] . "'";

			$ErrMsg = _('The line items of the order cannot be retrieved because');
			$LineItemsResult = DB_query($LineItemsSQL,$ErrMsg);
			if (DB_num_rows($LineItemsResult)>0) {

				while ($myrow=DB_fetch_array($LineItemsResult)) {
					$_SESSION['Items'.$identifier]->add_to_cart($myrow['stkcode'],
																$myrow['quantity'],
																$myrow['description'],
																$myrow['longdescription'],
																$myrow['unitprice'],
																$myrow['discountpercent'],
																$myrow['units'],
																$myrow['volume'],
																$myrow['grossweight'],
																$myrow['qohatloc'],
																$myrow['mbflag'],
																'',
																0,
																$myrow['discountcategory'],
																0,	/*Controlled*/
																0,	/*Serialised */
																$myrow['decimalplaces'],
																$myrow['narrative']);
					/*Just populating with existing order - no DBUpdates */

				} /* line items from sales order details */
			} //end of checks on returned data set
		}
	}
}

if ((!isset($_SESSION['Items'.$identifier]) OR $_SESSION['Items'.$identifier]->ItemsOrdered == 0) AND $NewRecurringOrder=='Yes'){
	prnMsg(_('A new recurring order can only be created if an order template has already been created from the normal order entry screen') . '. ' . _('To enter an order template select sales order entry from the orders tab of the main menu'),'error');
	include('includes/footer.php');
	exit;
}


if (isset($_POST['DeleteRecurringOrder'])){
	$sql = "DELETE FROM recurrsalesorderdetails WHERE recurrorderno='" . $_POST['ExistingRecurrOrderNo'] . "'";
	$ErrMsg = _('Could not delete recurring sales order lines for the recurring order template') . ' ' . $_POST['ExistingRecurrOrderNo'];
	$result = DB_query($sql,$ErrMsg);

	$sql = "DELETE FROM recurringsalesorders WHERE recurrorderno='" . $_POST['ExistingRecurrOrderNo'] . "'";
	$ErrMsg = _('Could not delete the recurring sales order template number') . ' ' . $_POST['ExistingRecurrOrderNo'];
	$result = DB_query($sql,$ErrMsg);

	prnMsg(_('Successfully deleted recurring sales order template number') . ' ' . $_POST['ExistingRecurrOrderNo'],'success');

	echo '<p><a href="'.$RootPath.'/SelectRecurringSalesOrder.php">' .  _('Select A Recurring Sales Order Template')  . '</a>';

	unset($_SESSION['Items'.$identifier]->LineItems);
	unset($_SESSION['Items'.$identifier]);
	include('includes/footer.php');
	exit;
}
If (isset($_POST['Process'])) {
	$Result = DB_Txn_Begin();
	$InputErrors =0;
	If (!Is_Date($_POST['StartDate'])){
		$InputErrors =1;
		prnMsg(_('The last recurrence or start date of this recurring order must be a valid date in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
	}
	If (!Is_Date($_POST['StopDate'])){
		$InputErrors =1;
		prnMsg(_('The end date of this recurring order must be a valid date in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
	}
	If (Date1GreaterThanDate2 ($_POST['StartDate'],$_POST['StopDate'])){
		$InputErrors =1;
		prnMsg(_('The end date of this recurring order must be after the start date'),'error');
	}
	if (isset($_POST['MakeRecurringOrder']) AND $_POST['Quotation']==1){
		$InputErrors =1;
		prnMsg( _('A recurring order cannot be made from a quotation'),'error');
	}

	if ($InputErrors == 0 ){  /*Error checks above all passed ok so lets go*/


		if ($NewRecurringOrder=='Yes'){

			/* finally write the recurring order header to the database and then the line details*/
			$DelDate = FormatDateforSQL($_SESSION['Items'.$identifier]->DeliveryDate);

			$HeaderSQL = "INSERT INTO recurringsalesorders (
										debtorno,
										branchcode,
										customerref,
										comments,
										orddate,
										ordertype,
										deliverto,
										deladd1,
										deladd2,
										deladd3,
										deladd4,
										deladd5,
										deladd6,
										contactphone,
										contactemail,
										freightcost,
										fromstkloc,
										shipvia,
										lastrecurrence,
										stopdate,
										frequency,
										autoinvoice)
									values (
										'" . $_SESSION['Items'.$identifier]->DebtorNo . "',
										'" . $_SESSION['Items'.$identifier]->Branch . "',
										'". $_SESSION['Items'.$identifier]->CustRef ."',
										'". $_SESSION['Items'.$identifier]->Comments ."',
										'" . Date('Y-m-d H:i') . "',
										'" . $_SESSION['Items'.$identifier]->DefaultSalesType . "',
										'" . $_SESSION['Items'.$identifier]->DeliverTo . "',
										'" . $_SESSION['Items'.$identifier]->DelAdd1 . "',
										'" . $_SESSION['Items'.$identifier]->DelAdd2 . "',
										'" . $_SESSION['Items'.$identifier]->DelAdd3 . "',
										'" . $_SESSION['Items'.$identifier]->DelAdd4 . "',
										'" . $_SESSION['Items'.$identifier]->DelAdd5 . "',
										'" . $_SESSION['Items'.$identifier]->DelAdd6 . "',
										'" . $_SESSION['Items'.$identifier]->PhoneNo . "',
										'" . $_SESSION['Items'.$identifier]->Email . "',
										'" . $_SESSION['Items'.$identifier]->FreightCost ."',
										'" . $_SESSION['Items'.$identifier]->Location ."',
										'" . $_SESSION['Items'.$identifier]->ShipVia ."',
										'" . FormatDateforSQL($_POST['StartDate']) . "',
										'" . FormatDateforSQL($_POST['StopDate']) . "',
										'" . $_POST['Frequency'] ."',
										'" . $_POST['AutoInvoice'] . "')";

			$ErrMsg = _('The recurring order cannot be added because');
			$DbgMsg = _('The SQL that failed was');
			$InsertQryResult = DB_query($HeaderSQL,$ErrMsg,$DbgMsg,true);

			$RecurrOrderNo = DB_Last_Insert_ID('recurringsalesorders','recurrorderno');
			echo 'xxx'.$RecurrOrderNo;
			$StartOf_LineItemsSQL = "INSERT INTO recurrsalesorderdetails (recurrorderno,
																			stkcode,
																			unitprice,
																			quantity,
																			discountpercent,
																			narrative)
																		VALUES ('";

			foreach ($_SESSION['Items'.$identifier]->LineItems as $StockItem) {

				$LineItemsSQL = $StartOf_LineItemsSQL .
								$RecurrOrderNo . "',
								'" . $StockItem->StockID . "',
								'". filter_number_format($StockItem->Price) . "',
								'" . filter_number_format($StockItem->Quantity) . "',
								'" . filter_number_format($StockItem->DiscountPercent) . "',
								'" . $StockItem->Narrative . "')";
				$Ins_LineItemResult = DB_query($LineItemsSQL,$ErrMsg,$DbgMsg,true);

			} /* inserted line items into sales order details */

			$result = DB_Txn_Commit();
			prnmsg(_('The new recurring order template has been added'),'success');

		} else { /* must be updating an existing recurring order */
			$HeaderSQL = "UPDATE recurringsalesorders SET
						stopdate =  '" . FormatDateforSQL($_POST['StopDate']) . "',
						frequency = '" . $_POST['Frequency'] . "',
						autoinvoice = '" . $_POST['AutoInvoice'] . "'
					WHERE recurrorderno = '" . $_POST['ExistingRecurrOrderNo'] . "'";

			$ErrMsg = _('The recurring order cannot be updated because');
			$UpdateQryResult = DB_query($HeaderSQL,$ErrMsg);
			prnmsg(_('The recurring order template has been updated'),'success');
		}

	echo '<p><a href="'.$RootPath.'/SelectOrderItems.php?NewOrder=Yes">' .  _('Enter New Sales Order')  . '</a>';

	echo '<p><a href="'.$RootPath.'/SelectRecurringSalesOrder.php">' .  _('Select A Recurring Sales Order Template')  . '</a>';

	unset($_SESSION['Items'.$identifier]->LineItems);
	unset($_SESSION['Items'.$identifier]);
	include('includes/footer.php');
	exit;

	}
}

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/customer.png" title="' . _('Search') .
		'" alt="" /><b>' . ' '. _('Recurring Order for Customer') .' : ' . $_SESSION['Items'.$identifier]->CustomerName  . '</b></p>';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?identifier=' . urlencode($identifier) . '" method="post">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<table cellpadding="2" class="selection">';
echo '<tr><th colspan="7"><b>' . _('Order Line Details') . '</b></th></tr>';
echo '<tr>
	<th>' .  _('Item Code')  . '</th>
	<th>' .  _('Item Description')  . '</th>
	<th>' .  _('Quantity')  . '</th>
	<th>' .  _('Unit')  . '</th>
	<th>' .  _('Price')  . '</th>
	<th>' .  _('Discount') .' %</th>
	<th>' .  _('Total')  . '</th>
</tr>';

$_SESSION['Items'.$identifier]->total = 0;
$_SESSION['Items'.$identifier]->totalVolume = 0;
$_SESSION['Items'.$identifier]->totalWeight = 0;

foreach ($_SESSION['Items'.$identifier]->LineItems as $StockItem) {

	$LineTotal = $StockItem->Quantity * $StockItem->Price * (1 - $StockItem->DiscountPercent);
	$DisplayLineTotal = locale_number_format($LineTotal,$_SESSION['Items'.$identifier]->CurrDecimalPlaces);
	$DisplayPrice = locale_number_format($StockItem->Price,$_SESSION['Items'.$identifier]->CurrDecimalPlaces);
	$DisplayQuantity = locale_number_format($StockItem->Quantity,$StockItem->DecimalPlaces);
	$DisplayDiscount = locale_number_format(($StockItem->DiscountPercent * 100),2);


	echo '<tr class="striped_row">
			<td>' . $StockItem->StockID . '</td>
			<td title="'. $StockItem->LongDescription . '">' . $StockItem->ItemDescription . '</td>
			<td class="number">' . $DisplayQuantity . '</td>
			<td>' . $StockItem->Units . '</td>
			<td class="number">' . $DisplayPrice . '</td>
			<td class="number">' . $DisplayDiscount . '</td>
			<td class="number">' . $DisplayLineTotal . '</td>
			</tr>';

	$_SESSION['Items'.$identifier]->total += $LineTotal;
	$_SESSION['Items'.$identifier]->totalVolume += ($StockItem->Quantity * $StockItem->Volume);
	$_SESSION['Items'.$identifier]->totalWeight += ($StockItem->Quantity * $StockItem->Weight);
}

$DisplayTotal = locale_number_format($_SESSION['Items'.$identifier]->total,$_SESSION['Items'.$identifier]->CurrDecimalPlaces);
echo '<tr>
		<td colspan="6" class="number"><b>' .  _('TOTAL Excl Tax/Freight')  . '</b></td>
		<td class="number">' . $DisplayTotal . '</td>
	</tr>
	</table>';

echo '<br /><table class="selection">';
echo '<tr><th colspan="7"><h3>' . _('Order Header Details') . '</h3></th></tr>';

echo '<tr>
	<td>' .  _('Deliver To') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->DeliverTo . '</td></tr>';

echo '<tr>
	<td>' .  _('Deliver from the warehouse at') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->Location . '</td></tr>';

echo '<tr>
	<td>' .  _('Street') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->DelAdd1 . '</td></tr>';

echo '<tr>
	<td>' .  _('Suburb') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->DelAdd2 . '</td></tr>';

echo '<tr>
	<td>' .  _('City') . '/' . _('Region') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->DelAdd3 . '</td></tr>';

echo '<tr>
	<td>' .  _('Post Code') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->DelAdd4 . '</td></tr>';

echo '<tr>
	<td>' .  _('Contact Phone Number') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->PhoneNo . '</td></tr>';

echo '<tr><td>' . _('Contact Email') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->Email . '</td></tr>';

echo '<tr><td>' .  _('Customer Reference') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->CustRef . '</td></tr>';

echo '<tr>
	<td>' .  _('Comments') .':</td>
	<td>' . $_SESSION['Items'.$identifier]->Comments  . '</td></tr>';

if (!isset($_POST['StartDate'])){
	$_POST['StartDate'] = date($_SESSION['DefaultDateFormat']);
}

if ($NewRecurringOrder=='Yes'){
	echo '<tr>
	<td>' .  _('Start Date') .':</td>
	<td><input type="text" class="date" name="StartDate" size="11" maxlength="10" value="' . $_POST['StartDate'] .'" /></td></tr>';
} else {
	echo '<tr>
	<td>' .  _('Last Recurrence') . ':</td>
	<td>' . $_POST['StartDate'];
	echo '<input type="hidden" name="StartDate" value="' . $_POST['StartDate'] . '" /></td></tr>';
}

if (!isset($_POST['StopDate'])){
   $_POST['StopDate'] = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,Date('m'),Date('d')+1,Date('y')+1));
}

echo '<tr>
	<td>' .  _('Finish Date') .':</td>
	<td><input type="text" class="date" name="StopDate" size="11" maxlength="10" value="' . $_POST['StopDate'] .'" /></td></tr>';

echo '<tr>
	<td>' .  _('Frequency of Recurrence') .':</td>
	<td><select name="Frequency">';

if (isset($_POST['Frequency']) and $_POST['Frequency']==52){
	echo '<option selected="selected" value="52">' . _('Weekly') . '</option>';
} else {
	echo '<option value="52">' . _('Weekly') . '</option>';
}
if (isset($_POST['Frequency']) and $_POST['Frequency']==26){
	echo '<option selected="selected" value="26">' . _('Fortnightly') . '</option>';
} else {
	echo '<option value="26">' . _('Fortnightly') . '</option>';
}
if (isset($_POST['Frequency']) and $_POST['Frequency']==12){
	echo '<option selected="selected" value="12">' . _('Monthly') . '</option>';
} else {
	echo '<option value="12">' . _('Monthly') . '</option>';
}
if (isset($_POST['Frequency']) and $_POST['Frequency']==6){
	echo '<option selected="selected" value="6">' . _('Bi-monthly') . '</option>';
} else {
	echo '<option value="6">' . _('Bi-monthly') . '</option>';
}
if (isset($_POST['Frequency']) and $_POST['Frequency']==4){
	echo '<option selected="selected" value="4">' . _('Quarterly') . '</option>';
} else {
	echo '<option value="4">' . _('Quarterly') . '</option>';
}
if (isset($_POST['Frequency']) and $_POST['Frequency']==2){
	echo '<option selected="selected" value="2">' . _('Bi-Annually') . '</option>';
} else {
	echo '<option value="2">' . _('Bi-Annually') . '</option>';
}
if (isset($_POST['Frequency']) and $_POST['Frequency']==1){
	echo '<option selected="selected" value="1">' . _('Annually') . '</option>';
} else {
	echo '<option value="1">' . _('Annually') . '</option>';
}
echo '</select></td></tr>';


if ($_SESSION['Items'.$identifier]->AllDummyLineItems()==true){

	echo '<tr><td>' . _('Invoice Automatically') . ':</td>
		<td><select name="AutoInvoice">';
	if ($_POST['AutoInvoice']==0){
		echo '<option selected="selected" value="0">' . _('No') . '</option>';
		echo '<option value="1">' . _('Yes') . '</option>';
	} else {
		echo '<option value="0">' . _('No') . '</option>';
		echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
	}
	echo '</select></td></tr></table>';
} else {
    echo '</table>';
	echo '<input type="hidden" name="AutoInvoice" value="0" />';
}

echo '<br /><div class="centre">';
if ($NewRecurringOrder=='Yes'){
	echo '<input type="hidden" name="NewRecurringOrder" value="Yes" />';
	echo '<input type="submit" name="Process" value="' . _('Create Recurring Order') . '" />';
} else {
	echo '<input type="hidden" name="NewRecurringOrder" value="No" />';
	echo '<input type="hidden" name="ExistingRecurrOrderNo" value="' . $_POST['ExistingRecurrOrderNo'] . '" />';

	echo '<input type="submit" name="Process" value="' . _('Update Recurring Order Details') . '" />';
	echo '<hr />';
	echo '<br /><br /><input type="submit" name="DeleteRecurringOrder" value="' . _('Delete Recurring Order') . ' ' . $_POST['ExistingRecurrOrderNo'] . '" onclick="return confirm(\'' . _('Are you sure you wish to delete this recurring order template?') . '\');" />';
}

echo '</div>';
echo '</div>
      </form>';
include('includes/footer.php');
?>
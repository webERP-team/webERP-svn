<?php
/* GoodsReceived.php */
/* Entry of items received against purchase orders */

/* Session started in header.php for password checking and authorisation level check */
include('includes/DefinePOClass.php');
include('includes/DefineSerialItems.php');
include('includes/session.php');
include('includes/SQL_CommonFunctions.inc');

/*The identifier makes this goods received session unique so cannot get confused
 * with other sessions of goods received on the same machine/browser
 * The identifier only needs to be unique for this php session, so a
 * unix timestamp will be sufficient.
 */

if (empty($_GET['identifier'])) {
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}
$Title = _('Receive Purchase Orders');
$ViewTopic = 'Inventory';
$BookMark = 'GoodsReceived';
include('includes/header.php');

echo '<a href="'. $RootPath . '/PO_SelectOSPurchOrder.php">' . _('Back to Purchase Orders'). '</a>
	<br />';

if (isset($_GET['PONumber']) AND $_GET['PONumber']<=0 AND !isset($_SESSION['PO'.$identifier])) {
	/* This page can only be called with a purchase order number for invoicing*/
	echo '<div class="centre">
			<a href= "' . $RootPath . '/PO_SelectOSPurchOrder.php">' . _('Select a purchase order to receive') . '</a>
		</div>
		<br />' .  _('This page can only be opened if a purchase order has been selected. Please select a purchase order first');

	include ('includes/footer.php');
	exit;
} elseif (isset($_GET['PONumber'])
			AND !isset($_POST['Update'])) {
/*Update only occurs if the user hits the button to refresh the data and recalc the value of goods recd*/

	$_GET['ModifyOrderNumber'] = intval($_GET['PONumber']);
	include('includes/PO_ReadInOrder.inc');
} elseif (isset($_POST['Update'])
			OR isset($_POST['ProcessGoodsReceived'])) {

/* if update quantities button is hit page has been called and ${$Line->LineNo} would have be
 set from the post to the quantity to be received */

	foreach ($_SESSION['PO'.$identifier]->LineItems as $Line) {
		$RecvQty = round(filter_number_format($_POST['RecvQty_' . $Line->LineNo]),$Line->DecimalPlaces);
		if (!is_numeric($RecvQty)) {
			$RecvQty = 0;
		}
		$_SESSION['PO'.$identifier]->LineItems[$Line->LineNo]->ReceiveQty = $RecvQty;
		if (isset($_POST['Complete_' . $Line->LineNo])) {
			$_SESSION['PO'.$identifier]->LineItems[$Line->LineNo]->Completed = 1;
		} else {
			$_SESSION['PO'.$identifier]->LineItems[$Line->LineNo]->Completed = 0;
		}
	}
}

if ($_SESSION['PO'.$identifier]->Status != 'Printed') {
	prnMsg( _('Purchase orders must have a status of Printed before they can be received').'.<br />' .
		_('Order number') . ' ' . $_GET['PONumber'] . ' ' . _('has a status of') . ' ' . _($_SESSION['PO'.$identifier]->Status), 'warn');
	include('includes/footer.php');
	exit;
}

// Always display quantities received and recalc balance for all items on the order
echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/supplier.png" title="', // Icon image.
	_('Receive'), '" /> ', // Icon title.
	_('Receive Purchase Order'), ' : ', $_SESSION['PO'.$identifier]->OrderNo, ' ', _('from'), ' ', $_SESSION['PO'.$identifier]->SupplierName, '</p>';// Page title.

echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?identifier=', urlencode($identifier), '" id="form1" method="post">',
	'<div>',
	'<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($_POST['ProcessGoodsReceived'])) {
	if (!isset($_POST['DefaultReceivedDate']) AND !isset($_SESSION['PO' . $identifier]->DefaultReceivedDate)) {
		/* This is meant to be the date the goods are received - it does not make sense to set this to the date that we requested delivery in the purchase order - I have not applied your change here Tim for this reason - let me know if I have it wrong - Phil */
		$_POST['DefaultReceivedDate'] = Date($_SESSION['DefaultDateFormat']);
		$_SESSION['PO' . $identifier]->DefaultReceivedDate = $_POST['DefaultReceivedDate'];
	} else {
		if (isset($_POST['DefaultReceivedDate']) AND is_date($_POST['DefaultReceivedDate'])) {
			$_SESSION['PO' . $identifier]->DefaultReceivedDate = $_POST['DefaultReceivedDate'];
		} elseif(isset($_POST['DefaultReceivedDate']) AND !is_date($_POST['DefaultReceivedDate'])) {
			prnMsg(_('The default received date is not a date format'),'error');
			$_POST['DefaultReceivedDate'] = Date($_SESSION['DefaultDateFormat']);
		}
	}
	if (!isset($_POST['SupplierReference'])) {
		$_POST['SupplierReference'] = '';
	} else {
		if (isset($_POST['SupplierReference']) AND mb_strlen(trim($_POST['SupplierReference']))>30) {
			prnMsg(_('The supplier\'s delivery note no should not be more than 30 characters'),'error');
		} else {
			$_SESSION['PO' . $identifier]->SupplierReference = $_POST['SupplierReference'];
		}
	}
	$SupplierReference = isset($_SESSION['PO' . $identifier]->SupplierReference)? $_SESSION['PO' . $identifier]->SupplierReference: $_POST['SupplierReference'];

	echo '<table class="selection">
			<tr>
				<td>' .  _('Date Goods/Service Received'). ':</td>
				<td><input type="text" class="date" maxlength="10" size="11" onchange="return isDate(this, this.value, '."'".
			$_SESSION['DefaultDateFormat']."'".')" name="DefaultReceivedDate" value="' . $_SESSION['PO' . $identifier]->DefaultReceivedDate . '" /></td>
				<td>' . _("Supplier's Reference") . ':</td>
				<td><input type="text" name="SupplierReference" value="' . $SupplierReference. '" maxlength="30" size="20"  onchange="ReloadForm(form1.Update)"/></td>
			</tr>
		</table>
		<br />',
		'<table cellpadding="2" class="selection">
			<tr><th colspan="2">&nbsp;</th>
				<th class="centre" colspan="4"><b>', _('Supplier Units'), '</b></th>
				<th>&nbsp;</th>
				<th class="centre" colspan="6"><b>', _('Our Receiving Units'), '</b></th>';
	if ($_SESSION['ShowValueOnGRN'] == 1) {
		echo '<th colspan="2">&nbsp;</th>';
	}
	echo	'</tr>
			<tr>
				<th>', _('Item Code'), '</th>
				<th>', _('Supplier') . '<br />'. _('Item'), '</th>
				<th>', _('Description'), '</th>
				<th>', _('Quantity Ordered'), '</th>
				<th>', _('Units'), '</th>
				<th>', _('Already Received'), '</th>
				<th>', _('Conversion Factor'), '</th>
				<th>', _('Quantity Ordered'), '</th>
				<th>', _('Units'), '</th>
				<th>', _('Already Received'), '</th>
				<th>', _('Delivery Date'), '</th>
				<th>', _('This Delivery') . '<br />' . _('Quantity'), '</th>
				<th>', _('Completed'), '</th>';

	if ($_SESSION['ShowValueOnGRN'] == 1) {
		echo '<th>', _('Price'), '</th>
				<th>', _('Total Value') . '<br />' . _('Received'), '</th>';
	}

	echo '</tr>';
	/*show the line items on the order with the quantity being received for modification */

	$_SESSION['PO'.$identifier]->Total = 0;
}

if (count($_SESSION['PO'.$identifier]->LineItems)>0 and !isset($_POST['ProcessGoodsReceived'])) {

	foreach ($_SESSION['PO'.$identifier]->LineItems as $LnItm) {

	/*  if ($LnItm->ReceiveQty==0) {   /*If no quantities yet input default the balance to be received
			$LnItm->ReceiveQty = $LnItm->QuantityOrd - $LnItm->QtyReceived;
		}
	*/

	/*Perhaps better to default quantities to 0 BUT.....if you wish to have the receive quantities
	default to the balance on order then just remove the comments around the 3 lines above */

	//Setup & Format values for LineItem display

		$LineTotal = ($LnItm->ReceiveQty * $LnItm->Price );
		$_SESSION['PO'.$identifier]->Total = $_SESSION['PO'.$identifier]->Total + $LineTotal;
		$DisplaySupplierQtyOrd = locale_number_format($LnItm->Quantity/$LnItm->ConversionFactor,$LnItm->DecimalPlaces);
		$DisplaySupplierQtyRec = locale_number_format($LnItm->QtyReceived/$LnItm->ConversionFactor,$LnItm->DecimalPlaces);
		$DisplayQtyOrd = locale_number_format($LnItm->Quantity,$LnItm->DecimalPlaces);
		$DisplayQtyRec = locale_number_format($LnItm->QtyReceived,$LnItm->DecimalPlaces);
		$DisplayLineTotal = locale_number_format($LineTotal,$_SESSION['PO'.$identifier]->CurrDecimalPlaces);
		 if ($LnItm->Price > 1) {
			$DisplayPrice = locale_number_format($LnItm->Price,$_SESSION['PO'.$identifier]->CurrDecimalPlaces);
		} else {
			$DisplayPrice = locale_number_format($LnItm->Price,4);
		}


		//Now Display LineItem
		$SupportedImgExt = array('png','jpg','jpeg');
		$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $LnItm->StockID . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
		if ($imagefile) {
			$ImageLink = '<a href="' . $imagefile . '" target="_blank">' .  $LnItm->StockID . '</a>';
		} else {
			$ImageLink = $LnItm->StockID;
		}

		echo '<tr class="striped_row">
			<td>' . $ImageLink . '</td>
			<td>' . $LnItm->Suppliers_PartNo . '</td>
			<td>' . $LnItm->ItemDescription . '</td>
			<td class="number">' . $DisplaySupplierQtyOrd . '</td>
			<td>' . $LnItm->SuppliersUnit . '</td>
			<td class="number">' . $DisplaySupplierQtyRec . '</td>
			<td class="number">' . $LnItm->ConversionFactor . '</td>
			<td class="number">' . $DisplayQtyOrd . '</td>
			<td>' . $LnItm->Units . '</td>
			<td class="number">' . $DisplayQtyRec . '</td>
			<td>' . $LnItm->ReqDelDate . '</td>
			<td class="number">';

		if ($LnItm->Controlled == 1) {

			echo '<input type="hidden" name="RecvQty_' . $LnItm->LineNo . '" autofocus="autofocus" value="' . locale_number_format($LnItm->ReceiveQty,$LnItm->DecimalPlaces) . '" /><a href="GoodsReceivedControlled.php?identifier=' . $identifier . '&amp;LineNo=' . $LnItm->LineNo . '">' . locale_number_format($LnItm->ReceiveQty,$LnItm->DecimalPlaces) . '</a></td>';

		} else {
			echo '<input type="text" class="number" name="RecvQty_' . $LnItm->LineNo . '" pattern="(?:^\d{1,3}(?:\.?\d{3})*(?:,\d{1,})?$)|(?:^\d{1,3}(?:,?\d{3})*(?:\.\d{1,})?$)|(?:^\d{1,3}(?:\s?\d{3})*(?:\.\d{1,})?$)|(?:^\d{1,3}(?:\s?\d{3})*(?:,\d{1,})?$)|(?:^(\d{1,2},)?(\d{2},)*(\d{3})(\.\d+)?|(\d{1,3})(\.\d+)?$)" title="' . _('Enter the quantity to receive against this order line as a number') . '"
maxlength="10" size="10" value="' . locale_number_format(round($LnItm->ReceiveQty,$LnItm->DecimalPlaces),$LnItm->DecimalPlaces) . '" /></td>';
		}
		echo '<td><input type="checkbox" name="Complete_'. $LnItm->LineNo . '"';
		if ($LnItm->Completed ==1) {
			echo ' checked';
		}
		echo ' /></td>';

		if ($_SESSION['ShowValueOnGRN'] == 1) {
			echo '<td class="number">', $DisplayPrice, '</td>',
				'<td class="number">', $DisplayLineTotal, '</td>';
		}


		if ($LnItm->Controlled == 1) {
			if ($LnItm->Serialised==1) {
				echo '<td><a href="GoodsReceivedControlled.php?identifier=' . $identifier . '&amp;LineNo=' . $LnItm->LineNo . '">' .
					_('Enter Serial Nos'). '</a></td>';
			} else {
				echo '<td><a href="GoodsReceivedControlled.php?identifier=' . $identifier . '&amp;LineNo=' . $LnItm->LineNo . '">' .
					_('Enter Batches'). '</a></td>';
			}
		}
		echo '</tr>';
	}//foreach(LineItem)
	$DisplayTotal = locale_number_format($_SESSION['PO'.$identifier]->Total,$_SESSION['PO'.$identifier]->CurrDecimalPlaces);
	if ($_SESSION['ShowValueOnGRN'] == 1) {
		echo '<tr>
				<td class="number" colspan="14"><b>', _('Total value of goods received'), '</b></td>
				<td class="number"><b>',  $DisplayTotal, '</b></td>
			</tr>';
	}
	echo '</table>';

}//If count(LineItems) > 0


/************************* LINE ITEM VALIDATION ************************/

/* Check whether trying to deliver more items than are recorded on the purchase order
(+ overreceive allowance) */

$DeliveryQuantityTooLarge = 0;
$NegativesFound = false;
$InputError = false;

if (isset($_POST['DefaultReceivedDate']) AND !is_date($_POST['DefaultReceivedDate'])) {
	$InputError = true;
	prnMsg(_('The goods received date is not a date format'),'error');

}

if (isset($_POST['SupplierReference']) AND mb_strlen(trim($_POST['SupplierReference']))>30) {
	$InputError = true;
	prnMsg(_('The delivery note of suppliers should not be more than 30 characters'),'error');
}
if (count($_SESSION['PO'.$identifier]->LineItems)>0) {

	foreach ($_SESSION['PO'.$identifier]->LineItems as $OrderLine) {

		if ($OrderLine->ReceiveQty+$OrderLine->QtyReceived > $OrderLine->Quantity * (1+ ($_SESSION['OverReceiveProportion'] / 100))) {
			$DeliveryQuantityTooLarge =1;
			$InputError = true;
		}
		if ($OrderLine->ReceiveQty < 0 AND $_SESSION['ProhibitNegativeStock']==1) {

			$SQL = "SELECT locstock.quantity
						FROM locstock
						WHERE locstock.stockid='" . $OrderLine->StockID . "'
						AND loccode= '" . $_SESSION['PO'.$identifier]->Location . "'";

			$CheckNegResult = DB_query($SQL);
			$CheckNegRow = DB_fetch_row($CheckNegResult);
			if ($CheckNegRow[0]+$OrderLine->ReceiveQty<0) {
				$NegativesFound=true;
				prnMsg(_('Receiving a negative quantity that results in negative stock is prohibited by the parameter settings. This delivery of stock cannot be processed until the stock of the item is corrected.'),'error',$OrderLine->StockID . ' Cannot Go Negative');
			}
		} /*end if ReceiveQty negative and not allowed negative stock */
	} /* end loop around the items received */
} /* end if there are lines received */

if ($_SESSION['PO'.$identifier]->SomethingReceived()==0 AND isset($_POST['ProcessGoodsReceived'])) { /*Then dont bother proceeding cos nothing to do ! */

	prnMsg(_('There is nothing to process') . '. ' . _('Please enter valid quantities greater than zero'),'warn');
	echo '<div class="centre"><input type="submit" name="Update" value="' . _('Update') . '" /></div>';

} elseif ($NegativesFound) {

	prnMsg(_('Negative stocks would result by processing a negative delivery - quantities must be changed or the stock quantity of the item going negative corrected before this delivery will be processed.'),'error');

	echo '<div class="centre"><input type="submit" name="Update" value="' . _('Update') . '" />';

} elseif ($DeliveryQuantityTooLarge==1 AND isset($_POST['ProcessGoodsReceived'])) {

	prnMsg(_('Entered quantities cannot be greater than the quantity entered on the purchase invoice including the allowed over-receive percentage'). ' ' . '(' . $_SESSION['OverReceiveProportion'] .'%)','error');
	echo '<br />';
	prnMsg(_('Modify the ordered items on the purchase invoice if you wish to increase the quantities'),'info');
	echo '<div class="centre"><input type="submit" name="Update" value="' . _('Update') . '" />';

}  elseif (isset($_POST['ProcessGoodsReceived']) AND $_SESSION['PO'.$identifier]->SomethingReceived()==1 AND $InputError == false) {

/* SQL to process the postings for goods received... */
/* Company record set at login for information on GL Links and debtors GL account*/


	if ($_SESSION['CompanyRecord']==0) {
		/*The company data and preferences could not be retrieved for some reason */
        echo '</div>';
        echo '</form>';
		prnMsg(_('The company information and preferences could not be retrieved') . ' - ' . _('see your system administrator') , 'error');
		include('includes/footer.php');
		exit;
	}

/*Now need to check that the order details are the same as they were when they were read into the Items array. If they have changed then someone else must have altered them */
// Otherwise if you try to fullfill item quantities separately will give error.
	$SQL = "SELECT itemcode,
					glcode,
					quantityord,
					quantityrecd,
					qtyinvoiced,
					shiptref,
					jobref
			FROM purchorderdetails
			WHERE orderno='" . (int) $_SESSION['PO'.$identifier]->OrderNo . "'
			AND completed=0
			ORDER BY podetailitem";

	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not check that the details of the purchase order had not been changed by another user because'). ':';
	$DbgMsg = _('The following SQL to retrieve the purchase order details was used');
	$Result=DB_query($SQL, $ErrMsg, $DbgMsg);

	$Changes=0;
	$LineNo=1;
	if(DB_num_rows($Result)==0) {//Those goods must have been received by another user. So should destroy the session data and show warning to users
		prnMsg(_('This order has been changed or invoiced since this delivery was started to be actioned').' . '._('Processing halted'),'error');
		echo '<div class="centre"><a href="' . $RootPath . '/PO_SelectOSPurchOrder.php">' .
			_('Select a different purchase order for receiving goods against') . '</a></div>';
		unset($_SESSION['PO'.$identifier]->LineItems);
		unset($_SESSION['PO'.$identifier]);
		unset($_POST['ProcessGoodsReceived']);
		echo '</div>';
		echo '</form>';
		include ('includes/footer.php');
		exit;
	}
	while ($myrow = DB_fetch_array($Result)) {

		if ($_SESSION['PO'.$identifier]->LineItems[$LineNo]->GLCode != $myrow['glcode'] OR
			$_SESSION['PO'.$identifier]->LineItems[$LineNo]->ShiptRef != $myrow['shiptref'] OR
			$_SESSION['PO'.$identifier]->LineItems[$LineNo]->JobRef != $myrow['jobref'] OR
			$_SESSION['PO'.$identifier]->LineItems[$LineNo]->QtyInv != $myrow['qtyinvoiced'] OR
			$_SESSION['PO'.$identifier]->LineItems[$LineNo]->StockID != $myrow['itemcode'] OR
			$_SESSION['PO'.$identifier]->LineItems[$LineNo]->Quantity != $myrow['quantityord'] OR
			$_SESSION['PO'.$identifier]->LineItems[$LineNo]->QtyReceived != $myrow['quantityrecd']) {


			prnMsg(_('This order has been changed or invoiced since this delivery was started to be actioned') . '. ' . _('Processing halted') . '. ' . _('To enter a delivery against this purchase order') . ', ' . _('it must be re-selected and re-read again to update the changes made by the other user'),'warn');

			if ($debug==1) {
				echo '<table class="selection">
					<tr>
						<td>' . _('GL Code of the Line Item') . ':</td>
						<td>' . $_SESSION['PO'.$identifier]->LineItems[$LineNo]->GLCode . '</td>
						<td>' . $myrow['glcode'] . '</td>
					</tr>
					<tr>
						<td>' . _('ShiptRef of the Line Item') . ':</td>
						<td>' . $_SESSION['PO'.$identifier]->LineItems[$LineNo]->ShiptRef . '</td>
						<td>' . $myrow['shiptref'] . '</td>
					</tr>
					<tr>
						<td>' . _('Contract Reference of the Line Item') . ':</td>
						<td>' . $_SESSION['PO'.$identifier]->LineItems[$LineNo]->JobRef . '</td>
						<td>' . $myrow['jobref'] . '</td>
					</tr>
					<tr>
						<td>' . _('Quantity Invoiced of the Line Item') . ':</td>
						<td>' . locale_number_format($_SESSION['PO'.$identifier]->LineItems[$LineNo]->QtyInv,$_SESSION['PO'.$identifier]->LineItems[$LineNo]->DecimalPlaces) . '</td>
						<td>' . $myrow['qtyinvoiced'] . '</td>
					</tr>
					<tr>
						<td>' . _('Stock Code of the Line Item') . ':</td>
						<td>' .  $_SESSION['PO'.$identifier]->LineItems[$LineNo]->StockID . '</td>
						<td>' . $myrow['itemcode'] . '</td>
					</tr>
					<tr>
						<td>' . _('Order Quantity of the Line Item') . ':</td>
						<td>' . locale_number_format($_SESSION['PO'.$identifier]->LineItems[$LineNo]->Quantity,$_SESSION['PO'.$identifier]->LineItems[$LineNo]->DecimalPlaces) . '</td>
						<td>' . $myrow['quantityord'] . '</td>
					</tr>
					<tr>
						<td>' . _('Quantity of the Line Item Already Received') . ':</td>
						<td>' . locale_number_format($_SESSION['PO'.$identifier]->LineItems[$LineNo]->QtyReceived,$_SESSION['PO'.$identifier]->LineItems[$LineNo]->DecimalPlaces) . '</td>
						<td>' . locale_number_format($myrow['quantityrecd'],$_SESSION['PO'.$identifier]->LineItems[$LineNo]->DecimalPlaces) . '</td>
					</tr>
					</table>';
			}
			echo '<div class="centre"><a href="' . $RootPath . '/PO_SelectOSPurchOrder.php">' .
				_('Select a different purchase order for receiving goods against') . '</a></div>';
			echo '<div class="centre"><a href="' . $RootPath . '/GoodsReceived.php?PONumber=' . $_SESSION['PO'.$identifier]->OrderNo . '">' .  _('Re-read the updated purchase order for receiving goods against'). '</a></div>';
			unset($_SESSION['PO'.$identifier]->LineItems);
			unset($_SESSION['PO'.$identifier]);
			unset($_POST['ProcessGoodsReceived']);
            echo '</div>';
            echo '</form>';
			include ('includes/footer.php');
			exit;
		}
		$LineNo++;
	} /*loop through all line items of the order to ensure none have been invoiced */

	DB_free_result($Result);

/* *********************** BEGIN SQL TRANSACTIONS *********************** */

	$Result = DB_Txn_Begin();
/*Now Get the next GRN - function in SQL_CommonFunctions*/
	$GRN = GetNextTransNo(25);

	$PeriodNo = GetPeriod($_POST['DefaultReceivedDate']);
	$_POST['DefaultReceivedDate'] = FormatDateForSQL($_POST['DefaultReceivedDate']);
	$OrderCompleted = true; //assume all received and completed - now test in case not
	foreach ($_SESSION['PO'.$identifier]->LineItems as $OrderLine) {
		if ($OrderLine->Completed ==0) {
			$OrderCompleted = false;
		}
		if ($OrderLine->ReceiveQty !=0 AND $OrderLine->ReceiveQty!='' AND isset($OrderLine->ReceiveQty)) {

			$LocalCurrencyPrice = ($OrderLine->Price / $_SESSION['PO'.$identifier]->ExRate);

			if ($OrderLine->StockID!='') { //Its a stock item line
				/*Need to get the current standard cost as it is now so we can process GL jorunals later*/
				$SQL = "SELECT materialcost + labourcost + overheadcost as stdcost,mbflag
							FROM stockmaster
							WHERE stockid='" . $OrderLine->StockID . "'";
				$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The standard cost of the item being received cannot be retrieved because');
				$DbgMsg = _('The following SQL to retrieve the standard cost was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				$myrow = DB_fetch_row($Result);
				if($myrow[1] != 'D') {
					if ($OrderLine->QtyReceived==0) { //its the first receipt against this line
						$_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost = $myrow[0];
					}
					$CurrentStandardCost = $myrow[0];
					/*Set the purchase order line stdcostunit = weighted average / standard cost used for all receipts of this line
				 		This assures that the quantity received against the purchase order line multiplied by the weighted average of standard
				 		costs received = the total of standard cost posted to GRN suspense*/
					$_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost = (($CurrentStandardCost * $OrderLine->ReceiveQty) + ($_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost * $OrderLine->QtyReceived)) / ($OrderLine->ReceiveQty + $OrderLine->QtyReceived);
				} elseif ($myrow[1] == 'D') { //it's a dummy part which without stock.
					$Dummy = true;
					if($OrderLine->QtyReceived == 0) {//There is
						$_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost = $LocalCurrencyPrice;
					}
				}

			} elseif ($OrderLine->QtyReceived==0 AND $OrderLine->StockID=='') {
				/*Its a nominal item being received */
				/*Need to record the value of the order per unit in the standard cost field to ensure GRN account entries clear */
				$_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost = $LocalCurrencyPrice;
			}

			if ($OrderLine->StockID=='' OR !empty($Dummy)) { /*Its a NOMINAL item line */
				$CurrentStandardCost = $_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost;
			}

/*Now the SQL to do the update to the PurchOrderDetails */

			if ($OrderLine->ReceiveQty >= ($OrderLine->Quantity - $OrderLine->QtyReceived)) {
				$SQL = "UPDATE purchorderdetails SET quantityrecd = quantityrecd + '" . $OrderLine->ReceiveQty . "',
													stdcostunit='" . $_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost . "',
													completed=1
						WHERE podetailitem = '" . $OrderLine->PODetailRec . "'";
			} else {
				$SQL = "UPDATE purchorderdetails SET
												quantityrecd = quantityrecd + '" . $OrderLine->ReceiveQty . "',
												stdcostunit='" . $_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost . "',
												completed='" . $_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->Completed . "'
										WHERE podetailitem = '" . $OrderLine->PODetailRec . "'";
			}

			$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The purchase order detail record could not be updated with the quantity received because');
			$DbgMsg = _('The following SQL to update the purchase order detail record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);


			if ($OrderLine->StockID !='' AND !isset($Dummy)) { /*Its a stock item so use the standard cost for the journals */
				$UnitCost = $CurrentStandardCost;
			} else {  /*otherwise its a nominal PO item so use the purchase cost converted to local currency */
				$UnitCost = $OrderLine->Price / $_SESSION['PO'.$identifier]->ExRate;
			}

/*Need to insert a GRN item */

			$SQL = "INSERT INTO grns (grnbatch,
									podetailitem,
									itemcode,
									itemdescription,
									deliverydate,
									qtyrecd,
									supplierid,
									stdcostunit,
									supplierref)
							VALUES ('" . $GRN . "',
								'" . $OrderLine->PODetailRec . "',
								'" . $OrderLine->StockID . "',
								'" . DB_escape_string($OrderLine->ItemDescription) . "',
								'" . $_POST['DefaultReceivedDate'] . "',
								'" . $OrderLine->ReceiveQty . "',
								'" . $_SESSION['PO'.$identifier]->SupplierID . "',
								'" . $CurrentStandardCost . "',
								'" . trim($_POST['SupplierReference']) ."')";

			$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('A GRN record could not be inserted') . '. ' . _('This receipt of goods has not been processed because');
			$DbgMsg =  _('The following SQL to insert the GRN record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			if ($OrderLine->StockID!='') { /* if the order line is in fact a stock item */

/* Update location stock records - NB  a PO cannot be entered for a dummy/assembly/kit parts */

/* Need to get the current location quantity will need it later for the stock movement */
				$SQL="SELECT locstock.quantity
								FROM locstock
								WHERE locstock.stockid='" . $OrderLine->StockID . "'
								AND loccode= '" . $_SESSION['PO'.$identifier]->Location . "'";

				$Result = DB_query($SQL);
				if (DB_num_rows($Result)==1) {
					$LocQtyRow = DB_fetch_row($Result);
					$QtyOnHandPrior = $LocQtyRow[0];
				} else {
					/*There must actually be some error this should never happen */
					$QtyOnHandPrior = 0;
				}

				$SQL = "UPDATE locstock
							SET quantity = locstock.quantity + '" . $OrderLine->ReceiveQty . "'
						WHERE locstock.stockid = '" . $OrderLine->StockID . "'
						AND loccode = '" . $_SESSION['PO'.$identifier]->Location . "'";

				$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
				$DbgMsg =  _('The following SQL to update the location stock record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

	/* Insert stock movements - with unit cost */

				$SQL = "INSERT INTO stockmoves (stockid,
												type,
												transno,
												loccode,
												trandate,
												userid,
												price,
												prd,
												reference,
												qty,
												standardcost,
												newqoh)
									VALUES (
										'" . $OrderLine->StockID . "',
										25,
										'" . $GRN . "',
										'" . $_SESSION['PO'.$identifier]->Location . "',
										'" . $_POST['DefaultReceivedDate'] . "',
										'" . $_SESSION['UserID'] . "',
										'" . $LocalCurrencyPrice . "',
										'" . $PeriodNo . "',
										'" . $_SESSION['PO'.$identifier]->SupplierID . " (" . DB_escape_string($_SESSION['PO'.$identifier]->SupplierName) . ") - " .$_SESSION['PO'.$identifier]->OrderNo . "',
										'" . $OrderLine->ReceiveQty . "',
										'" . $_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->StandardCost . "',
										'" . ($QtyOnHandPrior + $OrderLine->ReceiveQty) . "'
										)";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted because');
				$DbgMsg =  _('The following SQL to insert the stock movement records was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				/*Get the ID of the StockMove... */
				$StkMoveNo = DB_Last_Insert_ID('stockmoves','stkmoveno');
				/* Do the Controlled Item INSERTS HERE */

				if ($OrderLine->Controlled ==1) {
					foreach($OrderLine->SerialItems as $Item) {
						/* we know that StockItems return an array of SerialItem (s)
						 We need to add the StockSerialItem record and
						 The StockSerialMoves as well */
						//need to test if the controlled item exists first already
							$SQL = "SELECT COUNT(*) FROM stockserialitems
									WHERE stockid='" . $OrderLine->StockID . "'
									AND loccode = '" . $_SESSION['PO'.$identifier]->Location . "'
									AND serialno = '" . $Item->BundleRef . "'";
							$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not check if a batch or lot stock item already exists because');
							$DbgMsg =  _('The following SQL to test for an already existing controlled but not serialised stock item was used');
							$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
							$AlreadyExistsRow = DB_fetch_row($Result);
							if (trim($Item->BundleRef) != '') {
								if ($AlreadyExistsRow[0]>0) {
									if ($OrderLine->Serialised == 1) {
										$SQL = "UPDATE stockserialitems SET quantity = '" . $Item->BundleQty . "'";
									} else {
										$SQL = "UPDATE stockserialitems SET quantity = quantity + '" . $Item->BundleQty . "'";
									}
									$SQL .= "WHERE stockid='" . $OrderLine->StockID . "'
											 AND loccode = '" . $_SESSION['PO'.$identifier]->Location . "'
											 AND serialno = '" . $Item->BundleRef . "'";
								} else {
									$SQL = "INSERT INTO stockserialitems (stockid,
																			loccode,
																			serialno,
																			qualitytext,
																			expirationdate,
																			quantity)
																		VALUES ('" . $OrderLine->StockID . "',
																			'" . $_SESSION['PO'.$identifier]->Location . "',
																			'" . $Item->BundleRef . "',
																			'',
																			'" . FormatDateForSQL($Item->ExpiryDate) . "',
																			'" . $Item->BundleQty . "')";
								}

								$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be inserted because');
								$DbgMsg =  _('The following SQL to insert the serial stock item records was used');
								$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

								/* end of handle stockserialitems records */

							/** now insert the serial stock movement **/
							$SQL = "INSERT INTO stockserialmoves (stockmoveno,
																	stockid,
																	serialno,
																	moveqty)
															VALUES (
																'" . $StkMoveNo . "',
																'" . $OrderLine->StockID . "',
																'" . $Item->BundleRef . "',
																'" . $Item->BundleQty . "'
																)";
							$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
							$DbgMsg = _('The following SQL to insert the serial stock movement records was used');
							$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
							if ($_SESSION['QualityLogSamples']==1) {
								CreateQASample($OrderLine->StockID,$Item->BundleRef, '', 'Created from Purchase Order', 0, 0);
							}
						}//non blank BundleRef
					} //end foreach
				}
			} /*end of its a stock item - updates to locations and insert movements*/

			/* Check to see if the line item was flagged as the purchase of an asset */
			if ($OrderLine->AssetID !='' AND $OrderLine->AssetID !='0') { //then it is an asset

				/*first validate the AssetID and if it doesn't exist treat it like a normal nominal item  */
				$CheckAssetExistsResult = DB_query("SELECT assetid,
															datepurchased,
															costact
													FROM fixedassets
													INNER JOIN fixedassetcategories
													ON fixedassets.assetcategoryid=fixedassetcategories.categoryid
													WHERE assetid='" . $OrderLine->AssetID . "'");
				if (DB_num_rows($CheckAssetExistsResult)==1) { //then work with the assetid provided

					/*Need to add a fixedassettrans for the cost of the asset being received */
					$SQL = "INSERT INTO fixedassettrans (assetid,
														transtype,
														transno,
														transdate,
														periodno,
														inputdate,
														fixedassettranstype,
														amount)
									VALUES ('" . $OrderLine->AssetID . "',
											25,
											'" . $GRN . "',
											'" . $_POST['DefaultReceivedDate'] . "',
											'" . $PeriodNo . "',
											'" . Date('Y-m-d') . "',
											'" . _('cost') . "',
											'" . $CurrentStandardCost * $OrderLine->ReceiveQty . "')";
					$ErrMsg = _('CRITICAL ERROR! NOTE DOWN THIS ERROR AND SEEK ASSISTANCE The fixed asset transaction could not be inserted because');
					$DbgMsg = _('The following SQL to insert the fixed asset transaction record was used');
					$Result = DB_query($SQL,$ErrMsg, $DbgMsg, true);

					/*Now get the correct cost GL account from the asset category */
					$AssetRow = DB_fetch_array($CheckAssetExistsResult);
					/*Over-ride any GL account specified in the order with the asset category cost account */
					$_SESSION['PO'.$identifier]->LineItems[$OrderLine->LineNo]->GLCode = $AssetRow['costact'];
					/*Now if there are no previous additions to this asset update the date purchased */
					if ($AssetRow['datepurchased']=='0000-00-00') {
						/* it is a new addition as the date is set to 0000-00-00 when the asset record is created
						 * before any cost is added to the asset
						 */
						$SQL = "UPDATE fixedassets
									SET datepurchased='" . $_POST['DefaultReceivedDate'] . "',
										cost = cost + " . ($CurrentStandardCost * $OrderLine->ReceiveQty)  . "
									WHERE assetid = '" . $OrderLine->AssetID . "'";
					} else {
							$SQL = "UPDATE fixedassets SET cost = cost + " . ($CurrentStandardCost * $OrderLine->ReceiveQty)  . "
									WHERE assetid = '" . $OrderLine->AssetID . "'";
					}
					$ErrMsg = _('CRITICAL ERROR! NOTE DOWN THIS ERROR AND SEEK ASSISTANCE. The fixed asset cost and date purchased was not able to be updated because:');
					$DbgMsg = _('The following SQL was used to attempt the update of the cost and the date the asset was purchased');
					$Result = DB_query($SQL,$ErrMsg, $DbgMsg, true);

				} //assetid provided doesn't exist so ignore it and treat as a normal nominal item
			} //assetid is set so the nominal item is an asset

			/* If GLLink_Stock then insert GLTrans to debit the GL Code  and credit GRN Suspense account at standard cost*/
			if ($_SESSION['PO'.$identifier]->GLLink==1 AND $OrderLine->GLCode !=0) {
				/*GLCode is set to 0 when the GLLink is not activated this covers a situation where the GLLink is now active but it wasn't when this PO was entered */

				/*first the debit using the GLCode in the PO detail record entry*/
				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES (
											25,'" .
											$GRN . "','" .
											$_POST['DefaultReceivedDate'] . "','" .
											$PeriodNo . "','" .
											$OrderLine->GLCode . "','" .
											_('PO') . ' ' . $_SESSION['PO'.$identifier]->OrderNo . ': ' . $_SESSION['PO'.$identifier]->SupplierID . ' - ' . $OrderLine->StockID . ' - ' . DB_escape_string($OrderLine->ItemDescription) . ' x ' . $OrderLine->ReceiveQty . " @ " . locale_number_format($CurrentStandardCost,$_SESSION['CompanyRecord']['decimalplaces']) . "','" .
											$CurrentStandardCost * $OrderLine->ReceiveQty . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The purchase GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the purchase GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg, $DbgMsg, true);

				/* If the CurrentStandardCost != UnitCost (the standard at the time the first delivery was booked in,  and its a stock item, then the difference needs to be booked in against the purchase price variance account */

				/*now the GRN suspense entry*/
				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES (25,'" .
											$GRN . "','" .
											$_POST['DefaultReceivedDate'] . "','" .
											$PeriodNo . "','" .
											$_SESSION['CompanyRecord']['grnact'] . "','" .
											_('PO') . ' ' . $_SESSION['PO'.$identifier]->OrderNo . ': ' . $_SESSION['PO'.$identifier]->SupplierID . ' - ' . $OrderLine->StockID . ' - ' . DB_escape_string($OrderLine->ItemDescription) . ' x ' . $OrderLine->ReceiveQty . ' @ ' . locale_number_format($UnitCost,$_SESSION['CompanyRecord']['decimalplaces']) . "','" .
											-$UnitCost * $OrderLine->ReceiveQty . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GRN suspense side of the GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GRN Suspense GLTrans record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg,true);

			} /* end of if GL and stock integrated and standard cost !=0 */
		} /*Quantity received is != 0 */
	} /*end of OrderLine loop */

	if ($_SESSION['PO'.$identifier]->AllLinesReceived()==1 OR $OrderCompleted) { //all lines on the purchase order are now completed
		$StatusComment=date($_SESSION['DefaultDateFormat']) .' - ' . _('Order Completed on entry of GRN')  . '<br />' . $_SESSION['PO'.$identifier]->StatusComments;
		$sql="UPDATE purchorders
				SET status='Completed',
				stat_comment='" . $StatusComment . "'
				WHERE orderno='" . $_SESSION['PO'.$identifier]->OrderNo . "'";
		$result=DB_query($sql);
	}

	if ($_SESSION['PO'.$identifier]->GLLink==1) {
		EnsureGLEntriesBalance(25, $GRN);
	}

	$Result = DB_Txn_Commit();
	$PONo = $_SESSION['PO'.$identifier]->OrderNo;
	unset($_SESSION['PO'.$identifier]->LineItems);
	unset($_SESSION['PO'.$identifier]);
	unset($_POST['ProcessGoodsReceived']);

	echo '<br />
		<div class="centre">
			'. prnMsg(_('GRN number'). ' '. $GRN .' '. _('has been processed'),'success') . '
			<br />
			<br />
			<a href="PDFGrn.php?GRNNo='.$GRN .'&amp;PONo='.$PONo.'">' .  _('Print this Goods Received Note (GRN)') . '</a>
			<br />
			<br />
			<a href="PDFQALabel.php?GRNNo='.$GRN .'&amp;PONo='.$PONo.'">' .  _('Print QA Labels for this Receipt') . '</a>
			<br />
			<br />
			<a href="' . $RootPath . '/PO_SelectOSPurchOrder.php">' . _('Select a different purchase order for receiving goods against'). '</a>
		</div>';
/*end of process goods received entry */
    echo '</div>';
    echo '</form>';
	include('includes/footer.php');
	exit;

} else { /*Process Goods received not set so show a link to allow mod of line items on order and allow input of date goods received*/

	echo '<br />
		<div class="centre">
			<a href="' . $RootPath . '/PO_Header.php?ModifyOrderNumber=' .$_SESSION['PO'.$identifier]->OrderNo . '">' . _('Modify Order Items'). '</a>
		</div>
		<br />
		<div class="centre">
			<input type="submit" name="Update" value="' . _('Update') . '" />
			<br />
			<br />
			<input type="submit" name="ProcessGoodsReceived" value="' . _('Process Goods Received') . '" />
		</div>';
}
echo '</div>';
echo '</form>';
include('includes/footer.php');
?>

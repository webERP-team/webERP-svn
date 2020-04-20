<?php
/* ConfirmDispatch_Invoice.php */
/* Creates sales invoices from entered sales orders based on the quantities dispatched that can be modified */

/* Session started in session.php for password checking and authorisation level check */
include ('includes/DefineCartClass.php');
include ('includes/DefineSerialItems.php');

include ('includes/session.php');
$Title = _('Confirm Dispatches and Invoice An Order');
$ViewTopic = 'ARTransactions';
$BookMark = 'ConfirmInvoice';
include ('includes/header.php');

include ('includes/CurrenciesArray.php');
include ('includes/SQL_CommonFunctions.inc');
include ('includes/FreightCalculation.inc');
include ('includes/GetSalesTransGLCodes.inc');

if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other order entry sessions on the same machine  */
	$identifier = date('U');
} else {
	$identifier = $_GET['identifier'];
}

if (!isset($_GET['OrderNumber']) and !isset($_SESSION['ProcessingOrder'])) {
	/* This page can only be called with an order number for invoicing*/
	echo '<div class="centre">
			<a href="' . $RootPath . '/SelectSalesOrder.php">' . _('Select a sales order to invoice') . '</a>
		</div>
		<br />';
	prnMsg(_('This page can only be opened if an order has been selected Please select an order first from the delivery details screen click on Confirm for invoicing'), 'error');
	include ('includes/footer.php');
	exit;
} elseif (isset($_GET['OrderNumber']) and $_GET['OrderNumber'] > 0) {

	unset($_SESSION['Items' . $identifier]->LineItems);
	unset($_SESSION['Items' . $identifier]);

	$_SESSION['ProcessingOrder'] = (int)$_GET['OrderNumber'];
	$_GET['OrderNumber'] = (int)$_GET['OrderNumber'];
	$_SESSION['Items' . $identifier] = new cart;

	/*read in all the guff from the selected order into the Items cart */

	$OrderHeaderSQL = "SELECT salesorders.orderno,
								salesorders.debtorno,
								debtorsmaster.name,
								salesorders.branchcode,
								salesorders.customerref,
								salesorders.comments,
								salesorders.internalcomment,
								salesorders.orddate,
								salesorders.ordertype,
								salesorders.shipvia,
								salesorders.deliverto,
								salesorders.deladd1,
								salesorders.deladd2,
								salesorders.deladd3,
								salesorders.deladd4,
								salesorders.deladd5,
								salesorders.deladd6,
								salesorders.contactphone,
								salesorders.contactemail,
								salesorders.salesperson,
								salesorders.freightcost,
								salesorders.deliverydate,
								debtorsmaster.currcode,
								salesorders.fromstkloc,
								locations.taxprovinceid,
								custbranch.taxgroupid,
								currencies.rate as currency_rate,
								currencies.decimalplaces,
								custbranch.defaultshipvia,
								custbranch.specialinstructions,
								pickreq.consignment,
								pickreq.packages
						FROM salesorders
						INNER JOIN debtorsmaster
							ON salesorders.debtorno = debtorsmaster.debtorno
						INNER JOIN custbranch
							ON salesorders.branchcode = custbranch.branchcode
							AND salesorders.debtorno = custbranch.debtorno
						INNER JOIN currencies
							ON debtorsmaster.currcode = currencies.currabrev
						INNER JOIN locations
							ON locations.loccode=salesorders.fromstkloc
						INNER JOIN locationusers
							ON locationusers.loccode=salesorders.fromstkloc
							AND locationusers.userid='" . $_SESSION['UserID'] . "'
							AND locationusers.canupd=1
						LEFT OUTER JOIN pickreq
							ON pickreq.orderno=salesorders.orderno
							AND pickreq.closed=0
						WHERE salesorders.orderno = '" . $_GET['OrderNumber'] . "'";

	if ($_SESSION['SalesmanLogin'] != '') {
		$OrderHeaderSQL.= " AND salesorders.salesperson='" . $_SESSION['SalesmanLogin'] . "'";
	}

	$ErrMsg = _('The order cannot be retrieved because');
	$DbgMsg = _('The SQL to get the order header was');
	$GetOrdHdrResult = DB_query($OrderHeaderSQL, $ErrMsg, $DbgMsg);

	if (DB_num_rows($GetOrdHdrResult) == 1) {

		$MyRow = DB_fetch_array($GetOrdHdrResult);

		$_SESSION['Items' . $identifier]->DebtorNo = $MyRow['debtorno'];
		$_SESSION['Items' . $identifier]->OrderNo = $MyRow['orderno'];
		$_SESSION['Items' . $identifier]->Branch = $MyRow['branchcode'];
		$_SESSION['Items' . $identifier]->CustomerName = $MyRow['name'];
		$_SESSION['Items' . $identifier]->CustRef = $MyRow['customerref'];
		$_SESSION['Items' . $identifier]->Comments = $MyRow['comments'];
		$_SESSION['Items' . $identifier]->DefaultSalesType = $MyRow['ordertype'];
		$_SESSION['Items' . $identifier]->DefaultCurrency = $MyRow['currcode'];
		$_SESSION['Items' . $identifier]->CurrDecimalPlaces = $MyRow['decimalplaces'];
		$BestShipper = $MyRow['shipvia'];
		$_SESSION['Items' . $identifier]->ShipVia = $MyRow['shipvia'];
		$_SESSION['Items' . $identifier]->InternalComments = reverse_escape($MyRow['internalcomment']);
		$_SESSION['Items' . $identifier]->Consignment = $MyRow['consignment'];
		$_SESSION['Items' . $identifier]->Packages = $MyRow['packages'];

		if (is_null($BestShipper)) {
			$BestShipper = 0;
		}
		$_SESSION['Items' . $identifier]->DeliverTo = $MyRow['deliverto'];
		$_SESSION['Items' . $identifier]->DeliveryDate = ConvertSQLDate($MyRow['deliverydate']);
		$_SESSION['Items' . $identifier]->BrAdd1 = $MyRow['deladd1'];
		$_SESSION['Items' . $identifier]->BrAdd2 = $MyRow['deladd2'];
		$_SESSION['Items' . $identifier]->BrAdd3 = $MyRow['deladd3'];
		$_SESSION['Items' . $identifier]->BrAdd4 = $MyRow['deladd4'];
		$_SESSION['Items' . $identifier]->BrAdd5 = $MyRow['deladd5'];
		$_SESSION['Items' . $identifier]->BrAdd6 = $MyRow['deladd6'];
		$_SESSION['Items' . $identifier]->PhoneNo = $MyRow['contactphone'];
		$_SESSION['Items' . $identifier]->Email = $MyRow['contactemail'];
		$_SESSION['Items' . $identifier]->SalesPerson = $MyRow['salesperson'];

		$_SESSION['Items' . $identifier]->Location = $MyRow['fromstkloc'];
		$_SESSION['Items' . $identifier]->FreightCost = $MyRow['freightcost'];
		$_SESSION['Old_FreightCost'] = $MyRow['freightcost'];
		//		$_POST['ChargeFreightCost'] = $_SESSION['Old_FreightCost'];
		$_SESSION['Items' . $identifier]->Orig_OrderDate = $MyRow['orddate'];
		$_SESSION['CurrencyRate'] = $MyRow['currency_rate'];
		$_SESSION['Items' . $identifier]->TaxGroup = $MyRow['taxgroupid'];
		$_SESSION['Items' . $identifier]->DispatchTaxProvince = $MyRow['taxprovinceid'];
		$_SESSION['Items' . $identifier]->GetFreightTaxes();
		$_SESSION['Items' . $identifier]->SpecialInstructions = $MyRow['specialinstructions'];

		DB_free_result($GetOrdHdrResult);

		/*now populate the line items array with the sales order details records */

		$LineItemsSQL = "SELECT stkcode,
								stockmaster.description,
								stockmaster.longdescription,
								stockmaster.controlled,
								stockmaster.serialised,
								stockmaster.volume,
								stockmaster.grossweight,
								stockmaster.units,
								stockmaster.decimalplaces,
								stockmaster.mbflag,
								stockmaster.taxcatid,
								stockmaster.discountcategory,
								salesorderdetails.unitprice,
								salesorderdetails.quantity,
								salesorderdetails.discountpercent,
								salesorderdetails.actualdispatchdate,
								salesorderdetails.qtyinvoiced,
								salesorderdetails.narrative,
								salesorderdetails.orderlineno,
								salesorderdetails.poline,
								salesorderdetails.itemdue,
								stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost AS standardcost
							FROM salesorderdetails INNER JOIN stockmaster
							 	ON salesorderdetails.stkcode = stockmaster.stockid
							WHERE salesorderdetails.orderno ='" . $_GET['OrderNumber'] . "'
							AND salesorderdetails.quantity - salesorderdetails.qtyinvoiced >0
							ORDER BY salesorderdetails.orderlineno";

		$ErrMsg = _('The line items of the order cannot be retrieved because');
		$DbgMsg = _('The SQL that failed was');
		$LineItemsResult = DB_query($LineItemsSQL, $ErrMsg, $DbgMsg);

		if (DB_num_rows($LineItemsResult) > 0) {

			while ($MyRow = DB_fetch_array($LineItemsResult)) {
				$QOHSQL = "SELECT quantity FROM locstock WHERE stockid='" . $MyRow['stkcode'] . "' and loccode='" . $_SESSION['Items' . $identifier]->Location . "'";
				$QOHResult = DB_query($QOHSQL);
				$QOHRow = DB_fetch_array($QOHResult);

				$_SESSION['Items' . $identifier]->add_to_cart($MyRow['stkcode'], $MyRow['quantity'], $MyRow['description'], $MyRow['longdescription'], $MyRow['unitprice'], $MyRow['discountpercent'], $MyRow['units'], $MyRow['volume'], $MyRow['grossweight'], $QOHRow['quantity'], $MyRow['mbflag'], $MyRow['actualdispatchdate'], $MyRow['qtyinvoiced'], $MyRow['discountcategory'], $MyRow['controlled'], $MyRow['serialised'], $MyRow['decimalplaces'], htmlspecialchars_decode($MyRow['narrative']), 'No', $MyRow['orderlineno'], $MyRow['taxcatid'], '', $MyRow['itemdue'], $MyRow['poline'], $MyRow['standardcost']); /*NB NO Updates to DB */

				/*Calculate the taxes applicable to this line item from the customer branch Tax Group and Item Tax Category */

				$_SESSION['Items' . $identifier]->GetTaxes($MyRow['orderlineno']);
				$SerialItemsSQL = "SELECT pickreqdetails.qtypicked,
										pickserialdetails.stockid,
										serialno,
										moveqty
									FROM pickreq
									INNER JOIN pickreqdetails
										ON pickreqdetails.prid=pickreq.prid
									LEFT OUTER JOIN pickserialdetails
										ON pickserialdetails.detailno=pickreqdetails.detailno
									WHERE pickreq.orderno ='" . $_GET['OrderNumber'] . "'
										AND pickreq.closed=0
										AND pickreqdetails.orderlineno='" . $MyRow['orderlineno'] . "'";

				$ErrMsg = _('The serial items of the pick list cannot be retrieved because');
				$DbgMsg = _('The SQL that failed was');
				$SerialItemsResult = DB_query($SerialItemsSQL, $ErrMsg, $DbgMsg);

				if (DB_num_rows($SerialItemsResult) > 0) {
					$InOutModifier = 1;
					while ($MySerial = DB_fetch_array($SerialItemsResult)) {
						if (isset($MySerial['serialno'])) {
							$_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->SerialItems[$MySerial['serialno']] = new SerialItem($MySerial['serialno'], ($InOutModifier > 0 ? 1 : 1) * filter_number_format($MySerial['moveqty']));
						} else {
							if ($_SESSION['RequirePickingNote'] == 1) {
								$_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->QtyDispatched = $MySerial['qtypicked'];
							}
						}
					}
				}
			} /* line items from sales order details */
		} else { /* there are no line items that have a quantity to deliver */
			echo '<br />';
			prnMsg(_('There are no ordered items with a quantity left to deliver. There is nothing left to invoice'));
			include ('includes/footer.php');
			exit;

		} //end of checks on returned data set
		DB_free_result($LineItemsResult);

	} else { // End if the order was returned successfully.
		echo '<br />' . prnMsg(_('This order item could not be retrieved. Please select another order'), 'warn');
		include ('includes/footer.php');
		exit;
	} //valid order returned from the entered order number
	
} else {

	/* if processing, a dispatch page has been called and ${$StkItm->LineNumber} would have been set from the post
	 set all the necessary session variables changed by the POST */
	if (isset($_POST['ShipVia'])) {
		$_SESSION['Items' . $identifier]->ShipVia = $_POST['ShipVia'];
	}
	if (isset($_POST['ChargeFreightCost'])) {
		$_SESSION['Items' . $identifier]->FreightCost = filter_number_format($_POST['ChargeFreightCost']);
	}
	if (isset($_POST['InternalComments'])) {
		$_SESSION['Items' . $identifier]->InternalComments = $_POST['InternalComments'];
	}
	$i = 1;
	foreach ($_SESSION['Items' . $identifier]->FreightTaxes as $FreightTaxLine) {
		if (isset($_POST['FreightTaxRate' . $i])) {
			$_SESSION['Items' . $identifier]->FreightTaxes[$i]->TaxRate = filter_number_format($_POST['FreightTaxRate' . $i]) / 100;
		}
		$i++;
	}

	foreach ($_SESSION['Items' . $identifier]->LineItems as $Itm) {
		if (sizeOf($Itm->SerialItems) > 0) {
			$_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyDispatched = 0; //initialise QtyDispatched
			foreach ($Itm->SerialItems as $SerialItem) { //calculate QtyDispatched from bundle quantities
				$_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyDispatched+= $SerialItem->BundleQty;
			}
			//Preventing from dispatched more than ordered. Since it's controlled items, users must select the batch/lot again.
			if ($_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyDispatched > ($_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->Quantity - $_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyInv)) {
				prnMsg(_('Dispatched Quantity should not be more than order balanced quantity') . '. ' . _('To dispatch quantity is') . ' ' . $_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyDispatched . ' ' . _('And the order balance is ') . ' ' . ($_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->Quantity - $_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyInv), 'error');
				include ('includes/footer.php');
				exit;
			}
		} elseif (isset($_POST[$Itm->LineNumber . '_QtyDispatched'])) {
			if (is_numeric(filter_number_format($_POST[$Itm->LineNumber . '_QtyDispatched'])) and filter_number_format($_POST[$Itm->LineNumber . '_QtyDispatched']) <= ($_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->Quantity - $_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyInv)) {

				$_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyDispatched = round(filter_number_format($_POST[$Itm->LineNumber . '_QtyDispatched']), $Itm->DecimalPlaces);
			}
		}
		$i = 1;
		foreach ($Itm->Taxes as $TaxLine) {
			if (isset($_POST[$Itm->LineNumber . $i . '_TaxRate'])) {
				$_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->Taxes[$i]->TaxRate = filter_number_format($_POST[$Itm->LineNumber . $i . '_TaxRate']) / 100;
			}
			$i++;
		}
	} //end foreach lineitem
	
}

/* Always display dispatch quantities and recalc freight for items being dispatched */

if ($_SESSION['Items' . $identifier]->SpecialInstructions) {
	prnMsg($_SESSION['Items' . $identifier]->SpecialInstructions, 'warn');
}

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/inventory.png" title="', // Icon image.
_('Confirm Dispatch and Invoice'), '" /> ', // Icon title.
_('Confirm Dispatch and Invoice'), '</p>', // Page title.
'<table class="selection">
		<tr>
			<td>', _('Customer Code'), '</td>
			<td class="text">', $_SESSION['Items' . $identifier]->DebtorNo, '</td>
		</tr>
		<tr>
			<td>', _('Customer Name'), '</td>
			<td class="text">', $_SESSION['Items' . $identifier]->CustomerName, '</td>
		</tr>
		<tr>
			<td>', _('Invoice amounts stated in'), '</td>
			<td class="text">', $_SESSION['Items' . $identifier]->DefaultCurrency, ' - ', $CurrencyName[$_SESSION['Items' . $identifier]->DefaultCurrency], '</td>
		</tr>
	</table>
	<br />';

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?identifier=' . $identifier . '" method="post">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/***************************************************************
	Line Item Display
***************************************************************/
echo '<table class="selection">
	<thead>
	<tr>
		<th>' . _('Item Code') . '</th>
		<th>' . _('Item Description') . '</th>
		<th>' . _('Ordered') . '</th>
		<th>' . _('Units') . '</th>
		<th>' . _('Already') . '<br />' . _('Sent') . '</th>
		<th>' . _('This Dispatch') . '</th>
		<th>' . _('Price') . '</th>
		<th>' . _('Discount') . '</th>
		<th>' . _('Total') . '<br />' . _('Excl Tax') . '</th>
		<th>' . _('Tax Authority') . '</th>
		<th>' . _('Tax %') . '</th>
		<th>' . _('Tax Amount') . '</th>
		<th>' . _('Total') . '<br />' . _('Incl Tax') . '</th>
	</tr>
	</thead><tbody>';

$_SESSION['Items' . $identifier]->total = 0;
$_SESSION['Items' . $identifier]->totalVolume = 0;
$_SESSION['Items' . $identifier]->totalWeight = 0;
$TaxTotals = array();
$TaxGLCodes = array();
$TaxTotal = 0;

/*show the line items on the order with the quantity being dispatched available for modification */

$j = 0; // Used for autofocus on first field.
foreach ($_SESSION['Items' . $identifier]->LineItems as $LnItm) {
	if ($LnItm->QOHatLoc < $LnItm->Quantity and ($LnItm->MBflag == 'B' or $LnItm->MBflag == 'M')) {
		/*There is a stock deficiency in the stock location selected */
		$RowStarter = '<tr style="background:#FF0000;color:#FFC0CB">'; //rows show red where stock deficiency
		
	} else {
		$RowStarter = '<tr class="striped_row">';
	}
	if (sizeOf($LnItm->SerialItems) > 0) {
		$_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyDispatched = 0; //initialise QtyDispatched
		foreach ($LnItm->SerialItems as $SerialItem) { //calculate QtyDispatched from bundle quantities
			$_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyDispatched+= $SerialItem->BundleQty;
		}
	} elseif (isset($_POST[$LnItm->LineNumber . '_QtyDispatched'])) {
		if (is_numeric(filter_number_format($_POST[$LnItm->LineNumber . '_QtyDispatched'])) and filter_number_format($_POST[$LnItm->LineNumber . '_QtyDispatched']) <= ($_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->Quantity - $_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyInv)) {

			$_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyDispatched = round(filter_number_format($_POST[$LnItm->LineNumber . '_QtyDispatched']), $LnItm->DecimalPlaces);
		}
	}

	$LineTotal = $LnItm->QtyDispatched * $LnItm->Price * (1 - $LnItm->DiscountPercent);
	$_SESSION['Items' . $identifier]->total+= $LineTotal;
	$_SESSION['Items' . $identifier]->totalVolume+= ($LnItm->QtyDispatched * $LnItm->Volume);
	$_SESSION['Items' . $identifier]->totalWeight+= ($LnItm->QtyDispatched * $LnItm->Weight);

	echo $RowStarter;
	echo '<td>' . $LnItm->StockID . '</td>
		<td class="text" title="' . $LnItm->LongDescription . '">' . $LnItm->ItemDescription . '</td>
		<td class="number">' . locale_number_format($LnItm->Quantity, $LnItm->DecimalPlaces) . '</td>
		<td class="text">' . $LnItm->Units . '</td>
		<td class="number">' . locale_number_format($LnItm->QtyInv, $LnItm->DecimalPlaces) . '</td>';

	if ($LnItm->Controlled == 1) {

		if (isset($_POST['ProcessInvoice'])) {
			echo '<td class="number">' . locale_number_format($LnItm->QtyDispatched, $LnItm->DecimalPlaces) . '</td>';
		} else {
			echo '<td class="number"><input type="hidden" name="' . $LnItm->LineNumber . '_QtyDispatched" required="required" maxlength="11"  value="' . $LnItm->QtyDispatched . '" /><a href="' . $RootPath . '/ConfirmDispatchControlled_Invoice.php?identifier=' . urlencode($identifier) . '&LineNo=' . urlencode($LnItm->LineNumber) . '">' . locale_number_format($LnItm->QtyDispatched, $LnItm->DecimalPlaces) . '</a></td>';
		}
	} else {
		if (isset($_POST['ProcessInvoice'])) {
			echo '<td class="number">', locale_number_format($LnItm->QtyDispatched, $LnItm->DecimalPlaces), '</td>';
		} else {
			echo '<td class="number"><input ', (++$j == 1 ? 'autofocus="autofocus" ' : ''), 'class="number" maxlength="12" name="', $LnItm->LineNumber, '_QtyDispatched" required="required" title="', _('Enter the quantity to charge the customer for, that has been dispatched'), '" type="text" size="12" value="', locale_number_format($LnItm->QtyDispatched, $LnItm->DecimalPlaces), '" /></td>';
		}
	}
	$DisplayDiscountPercent = locale_number_format($LnItm->DiscountPercent * 100, 2) . '%';
	$DisplayLineNetTotal = locale_number_format($LineTotal, $_SESSION['Items' . $identifier]->CurrDecimalPlaces);
	$DisplayPrice = locale_number_format($LnItm->Price, $_SESSION['Items' . $identifier]->CurrDecimalPlaces);
	echo '<td class="number">' . $DisplayPrice . '</td>
		<td class="number">' . $DisplayDiscountPercent . '</td>
		<td class="number">' . $DisplayLineNetTotal . '</td>';

	/*Need to list the taxes applicable to this line */
	echo '<td>';
	$i = 0;
	foreach ($_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->Taxes AS $Tax) {
		if ($i > 0) {
			echo '<br />';
		}
		echo $Tax->TaxAuthDescription;
		$i++;
	}
	echo '</td>
		<td class="number">';

	$i = 1; // initialise the number of taxes iterated through
	$TaxLineTotal = 0; //initialise tax total for the line
	

	foreach ($LnItm->Taxes AS $Tax) {
		if (empty($TaxTotals[$Tax->TaxAuthID])) {
			$TaxTotals[$Tax->TaxAuthID] = 0;
		}
		if ($i > 1) {
			echo '<br />';
		}
		if (isset($_POST['ProcessInvoice'])) {
			echo $Tax->TaxRate * 100;
		} else {
			echo '<input type="text" class="number" required="required" title="' . _('Enter the tax rate applicable as a number') . '" name="' . $LnItm->LineNumber . $i . '_TaxRate" maxlength="4" size="4" value="' . $Tax->TaxRate * 100 . '" />';
		}
		$i++;
		if ($Tax->TaxOnTax == 1) {
			$TaxTotals[$Tax->TaxAuthID]+= ($Tax->TaxRate * ($LineTotal + $TaxLineTotal));
			$TaxLineTotal+= ($Tax->TaxRate * ($LineTotal + $TaxLineTotal));
		} else {
			$TaxTotals[$Tax->TaxAuthID]+= ($Tax->TaxRate * $LineTotal);
			$TaxLineTotal+= ($Tax->TaxRate * $LineTotal);
		}
		$TaxGLCodes[$Tax->TaxAuthID] = $Tax->TaxGLCode;
	}
	echo '</td>';

	$TaxTotal+= $TaxLineTotal;

	$DisplayTaxAmount = locale_number_format($TaxLineTotal, $_SESSION['Items' . $identifier]->CurrDecimalPlaces);

	$DisplayGrossLineTotal = locale_number_format($LineTotal + $TaxLineTotal, $_SESSION['Items' . $identifier]->CurrDecimalPlaces);

	echo '<td class="number">' . $DisplayTaxAmount . '</td>
			<td class="number">' . $DisplayGrossLineTotal . '</td>';

	if ($LnItm->Controlled == 1) {
		if (!isset($_POST['ProcessInvoice'])) {
			echo '<td><a href="' . $RootPath . '/ConfirmDispatchControlled_Invoice.php?identifier=' . urlencode($identifier) . '&LineNo=' . urlencode($LnItm->LineNumber) . '">';
			if ($LnItm->Serialised == 1) {
				echo _('Enter Serial Numbers');
			} else { /*Just batch/roll/lot control */
				echo _('Enter Batch/Roll/Lot #');
			}
			echo '</a></td>';
		}
	}
	echo '</tr>';
	if (mb_strlen($LnItm->Narrative) > 1) {
		$Narrative = str_replace('\r\n', '<br />', $LnItm->Narrative);
		echo $RowStarter . '<td colspan="12">' . stripslashes($Narrative) . '</td></tr>';
	}
} //end foreach ($line)
/*Don't re-calculate freight if some of the order has already been delivered -
depending on the business logic required this condition may not be required.
It seems unfair to charge the customer twice for freight if the order
was not fully delivered the first time ?? */

if (!isset($_SESSION['Items' . $identifier]->FreightCost) or $_SESSION['Items' . $identifier]->FreightCost == 0) {
	if ($_SESSION['DoFreightCalc'] == True) {
		list($FreightCost, $BestShipper) = CalcFreightCost($_SESSION['Items' . $identifier]->total, $_SESSION['Items' . $identifier]->BrAdd2, $_SESSION['Items' . $identifier]->BrAdd3, $_SESSION['Items' . $identifier]->BrAdd4, $_SESSION['Items' . $identifier]->BrAdd5, $_SESSION['Items' . $identifier]->BrAdd6, $_SESSION['Items' . $identifier]->totalVolume, $_SESSION['Items' . $identifier]->totalWeight, $_SESSION['Items' . $identifier]->Location, $_SESSION['Items' . $identifier]->DefaultCurrency);
		$_SESSION['Items' . $identifier]->ShipVia = $BestShipper;
	}
	if (isset($FreightCost) and is_numeric($FreightCost)) {
		$FreightCost = $FreightCost / $_SESSION['CurrencyRate'];
	} else {
		$FreightCost = 0;
	}
	if (!is_numeric($BestShipper)) {
		$SQL = "SELECT shipper_id FROM shippers WHERE shipper_id='" . $_SESSION['Default_Shipper'] . "'";
		$ErrMsg = _('There was a problem testing for a default shipper because');
		$TestShipperExists = DB_query($SQL, $ErrMsg);
		if (DB_num_rows($TestShipperExists) == 1) {
			$BestShipper = $_SESSION['Default_Shipper'];
		} else {
			$SQL = "SELECT shipper_id FROM shippers";
			$ErrMsg = _('There was a problem testing for a default shipper');
			$TestShipperExists = DB_query($SQL, $ErrMsg);
			if (DB_num_rows($TestShipperExists) >= 1) {
				$ShipperReturned = DB_fetch_row($TestShipperExists);
				$BestShipper = $ShipperReturned[0];
			} else {
				prnMsg(_('There are no shippers defined') . '. ' . _('Please use the link below to set up shipping freight companies, the system expects the shipping company to be selected or a default freight company to be used'), 'error');
				echo '<a href="' . $RootPath . 'Shippers.php">' . _('Enter') . '/' . _('Amend Freight Companies') . '</a>';
			}
		}
	}
}

if (isset($_POST['ChargeFreightCost']) and !is_numeric(filter_number_format($_POST['ChargeFreightCost']))) {
	$_POST['ChargeFreightCost'] = 0;
}

echo '<tr>
	<td class="number" colspan="2">', _('Order Freight Cost'), '</td>
	<td class="number">', locale_number_format($_SESSION['Old_FreightCost'], $_SESSION['Items' . $identifier]->CurrDecimalPlaces), '</td>';

if ($_SESSION['DoFreightCalc'] == True) {
	echo '<td class="number" colspan="2">', _('Recalculated Freight Cost'), '</td>
		<td class="number">', locale_number_format($FreightCost, $_SESSION['Items' . $identifier]->CurrDecimalPlaces), '</td>';
} else {
	//	echo '<td colspan="1"></td>';// Should be?:	echo '<td colspan="3">&nbsp;</td>';
	
}
if (!isset($_POST['ChargeFreightCost'])) {
	$_POST['ChargeFreightCost'] = 0;
}
echo '<td class="number" colspan="2">', _('Charge Freight Cost ex Tax'), '</td>';
if ($_SESSION['Items' . $identifier]->Any_Already_Delivered() == 1 and (!isset($_SESSION['Items' . $identifier]->FreightCost) or $_POST['ChargeFreightCost'] == 0)) {

	echo '<td><input class="number" maxlength="12" name="ChargeFreightCost" required="required" size="10" type="text" value="0" /></td>';
	$_SESSION['Items' . $identifier]->FreightCost = 0;
} else {
	if (isset($_POST['ProcessInvoice'])) {
		echo '<td class="number">' . locale_number_format($_SESSION['Items' . $identifier]->FreightCost, $_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>';
	} else {
		echo '<td class="number"><input class="number" maxlength="12" name="ChargeFreightCost" size="10" type="text" value="' . locale_number_format($_SESSION['Items' . $identifier]->FreightCost, $_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '" /></td>';
	}
	$_POST['ChargeFreightCost'] = locale_number_format($_SESSION['Items' . $identifier]->FreightCost, $_SESSION['Items' . $identifier]->CurrDecimalPlaces);
}

$FreightTaxTotal = 0; // initialise tax total
echo '<td>';

$i = 0; // initialise the number of taxes iterated through
foreach ($_SESSION['Items' . $identifier]->FreightTaxes as $FreightTaxLine) {
	if ($i > 0) {
		echo '<br />';
	}
	echo $FreightTaxLine->TaxAuthDescription;
	$i++;
}

echo '</td><td class="number">';

$i = 0;
foreach ($_SESSION['Items' . $identifier]->FreightTaxes as $FreightTaxLine) {
	if ($i > 0) {
		echo '<br />';
	}

	if (isset($_POST['ProcessInvoice'])) {
		echo $FreightTaxLine->TaxRate * 100;
	} else {
		echo '<input class="number" maxlength="4" name="FreightTaxRate' . $FreightTaxLine->TaxCalculationOrder . '" size="4" type="text" value="' . locale_number_format($FreightTaxLine->TaxRate * 100, $_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '" />';
	}

	if ($FreightTaxLine->TaxOnTax == 1) {
		$TaxTotals[$FreightTaxLine->TaxAuthID]+= ($FreightTaxLine->TaxRate * ($_SESSION['Items' . $identifier]->FreightCost + $FreightTaxTotal));
		$FreightTaxTotal+= ($FreightTaxLine->TaxRate * ($_SESSION['Items' . $identifier]->FreightCost + $FreightTaxTotal));
	} else {
		$TaxTotals[$FreightTaxLine->TaxAuthID]+= ($FreightTaxLine->TaxRate * $_SESSION['Items' . $identifier]->FreightCost);
		$FreightTaxTotal+= ($FreightTaxLine->TaxRate * $_SESSION['Items' . $identifier]->FreightCost);
	}
	$i++;
	$TaxGLCodes[$FreightTaxLine->TaxAuthID] = $FreightTaxLine->TaxGLCode;
}
echo '</td>';

echo '<td class="number">' . locale_number_format($FreightTaxTotal, $_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>
	<td class="number">' . locale_number_format($FreightTaxTotal + filter_number_format($_POST['ChargeFreightCost']), $_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>
	</tr>';

$TaxTotal+= $FreightTaxTotal;

$DisplaySubTotal = locale_number_format(($_SESSION['Items' . $identifier]->total + filter_number_format($_POST['ChargeFreightCost'])), $_SESSION['Items' . $identifier]->CurrDecimalPlaces);

echo '<tr>
	<td colspan="8" class="number">' . _('Invoice Totals') . '</td>
	<td class="number"><hr /><b>' . $DisplaySubTotal . '</b><hr /></td>
	<td colspan="2"></td>
	<td class="number"><hr /><b>' . locale_number_format($TaxTotal, $_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</b><hr /></td>
	<td class="number"><hr /><b>' . locale_number_format($TaxTotal + ($_SESSION['Items' . $identifier]->total + $_POST['ChargeFreightCost']), $_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</b><hr /></td>
</tr>';

if (!isset($_POST['DispatchDate']) or !Is_Date($_POST['DispatchDate'])) {
	$DefaultDispatchDate = Date($_SESSION['DefaultDateFormat'], CalcEarliestDispatchDate());
} else {
	$DefaultDispatchDate = $_POST['DispatchDate'];
}

echo '<tbody></table><br />';

if (isset($_POST['ProcessInvoice']) and $_POST['ProcessInvoice'] != '') {

	/* SQL to process the postings for sales invoices...
	
	/*First check there are lines on the dipatch with quantities to invoice
	invoices can have a zero amount but there must be a quantity to invoice */

	$QuantityInvoicedIsPositive = false;

	foreach ($_SESSION['Items' . $identifier]->LineItems as $OrderLine) {
		if ($OrderLine->QtyDispatched > 0) {
			$QuantityInvoicedIsPositive = true;
		}
	}
	if (!$QuantityInvoicedIsPositive) {
		prnMsg(_('There are no lines on this order with a quantity to invoice') . '. ' . _('No further processing has been done'), 'error');
		include ('includes/footer.php');
		exit;
	}

	if ($_SESSION['ProhibitNegativeStock'] == 1) { // checks for negative stock after processing invoice
		//sadly this check does not combine quantities occuring twice on and order and each line is considered individually :-(
		$NegativesFound = false;
		foreach ($_SESSION['Items' . $identifier]->LineItems as $OrderLine) {
			$SQL = "SELECT stockmaster.description,
							locstock.quantity,
					 		stockmaster.mbflag
		 			FROM locstock
		 			INNER JOIN stockmaster
					ON stockmaster.stockid=locstock.stockid
					WHERE stockmaster.stockid='" . $OrderLine->StockID . "'
					AND locstock.loccode='" . $_SESSION['Items' . $identifier]->Location . "'";

			$ErrMsg = _('Could not retrieve the quantity left at the location once this order is invoiced (for the purposes of checking that stock will not go negative because)');
			$Result = DB_query($SQL, $ErrMsg);
			$CheckNegRow = DB_fetch_array($Result);
			if (($CheckNegRow['mbflag'] == 'B' or $CheckNegRow['mbflag'] == 'M') and mb_substr($OrderLine->StockID, 0, 4) != 'ASSET') {
				if ($CheckNegRow['quantity'] < $OrderLine->QtyDispatched) {
					prnMsg(_('Invoicing the selected order would result in negative stock. The system parameters are set to prohibit negative stocks from occurring. This invoice cannot be created until the stock on hand is corrected.'), 'error', $OrderLine->StockID . ' ' . $CheckNegRow['description'] . ' - ' . _('Negative Stock Prohibited'));
					$NegativesFound = true;
				}
			} elseif ($CheckNegRow['mbflag'] == 'A') {

				/*Now look for assembly components that would go negative */
				$SQL = "SELECT bom.component,
							stockmaster.description,
							locstock.quantity-(" . $OrderLine->QtyDispatched . "*bom.quantity) AS qtyleft
						FROM bom
						INNER JOIN locstock
						ON bom.component=locstock.stockid
						INNER JOIN stockmaster
						ON stockmaster.stockid=bom.component
						WHERE bom.parent='" . $OrderLine->StockID . "'
						AND locstock.loccode='" . $_SESSION['Items' . $identifier]->Location . "'
						AND effectiveafter <= CURRENT_DATE
						AND effectiveto > CURRENT_DATE";

				$ErrMsg = _('Could not retrieve the component quantity left at the location once the assembly item on this order is invoiced (for the purposes of checking that stock will not go negative because)');
				$Result = DB_query($SQL, $ErrMsg);
				while ($NegRow = DB_fetch_array($Result)) {
					if ($NegRow['qtyleft'] < 0) {
						prnMsg(_('Invoicing the selected order would result in negative stock for a component of an assembly item on the order. The system parameters are set to prohibit negative stocks from occurring. This invoice cannot be created until the stock on hand is corrected.'), 'error', $NegRow['component'] . ' ' . $NegRow['description'] . ' - ' . _('Negative Stock Prohibited'));
						$NegativesFound = true;
					} // end if negative would result
					
				} //loop around the components of an assembly item
				
			} //end if its an assembly item - check component stock
			
		} //end of loop around items on the order for negative check
		if ($NegativesFound) {
			echo '</div>';
			echo '</form>';
			echo '<div class="centre">
					<input type="submit" name="Update" value="' . _('Update') . '" /></div>';
			include ('includes/footer.php');
			exit;
		}

	} //end of testing for negative stocks
	

	/* Now Get the area where the sale is to from the branches table */

	$SQL = "SELECT area,
					defaultshipvia
			FROM custbranch
			WHERE custbranch.debtorno ='" . $_SESSION['Items' . $identifier]->DebtorNo . "'
			AND custbranch.branchcode = '" . $_SESSION['Items' . $identifier]->Branch . "'";

	$ErrMsg = _('We were unable to load Area where the Sale is to from the BRANCHES table') . '. ' . _('Please remedy this');
	$Result = DB_query($SQL, $ErrMsg);
	$MyRow = DB_fetch_row($Result);
	$Area = $MyRow[0];
	$DefaultShipVia = $MyRow[1];
	DB_free_result($Result);

	/*company record read in on login with info on GL Links and debtors GL account*/

	if ($_SESSION['CompanyRecord'] == 0) {
		/*The company data and preferences could not be retrieved for some reason */
		prnMsg(_('The company information and preferences could not be retrieved') . ' - ' . _('see your system administrator'), 'error');
		include ('includes/footer.php');
		exit;
	}

	/*Now need to check that the order details are the same as they were when they were read into the Items array. If they've changed then someone else may have invoiced them */

	$SQL = "SELECT stkcode,
					quantity,
					qtyinvoiced,
					orderlineno
				FROM salesorderdetails
				WHERE completed=0 AND quantity-qtyinvoiced > 0
				AND orderno = '" . $_SESSION['ProcessingOrder'] . "'";

	$Result = DB_query($SQL);

	if (DB_num_rows($Result) != count($_SESSION['Items' . $identifier]->LineItems)) {

		/*there should be the same number of items returned from this query as there are lines on the invoice - if not 	then someone has already invoiced or credited some lines */

		if ($debug == 1) {
			echo '<br />' . $SQL;
			echo '<br />' . _('Number of rows returned by SQL') . ':' . DB_num_rows($Result);
			echo '<br />' . _('Count of items in the session') . ' ' . count($_SESSION['Items' . $identifier]->LineItems);
		}

		echo '<br />';
		prnMsg(_('This order has been changed or invoiced since this delivery was started to be confirmed') . '. ' . _('Processing halted') . '. ' . _('To enter and confirm this dispatch') . '/' . _('invoice the order must be re-selected and re-read again to update the changes made by the other user'), 'error');

		unset($_SESSION['Items' . $identifier]->LineItems);
		unset($_SESSION['Items' . $identifier]);
		unset($_SESSION['ProcessingOrder']);
		include ('includes/footer.php');
		exit;
	}

	$Changes = 0;

	while ($MyRow = DB_fetch_array($Result)) {

		if ($_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->Quantity != $MyRow['quantity'] or $_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->QtyInv != $MyRow['qtyinvoiced']) {

			echo '<br />' . _('Orig order for') . ' ' . $MyRow['orderlineno'] . ' ' . _('has a quantity of') . ' ' . $MyRow['quantity'] . ' ' . _('and an invoiced qty of') . ' ' . $MyRow['qtyinvoiced'] . ' ' . _('the session shows quantity of') . ' ' . $_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->Quantity . ' ' . _('and quantity invoice of') . ' ' . $_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->QtyInv;

			prnMsg(_('This order has been changed or invoiced since this delivery was started to be confirmed') . ' ' . _('Processing halted.') . ' ' . _('To enter and confirm this dispatch, it must be re-selected and re-read again to update the changes made by the other user'), 'error');

			echo '<br />';

			echo '<div class="centre"><a href="' . $RootPath . '/SelectSalesOrder.php">' . _('Select a sales order for confirming deliveries and invoicing') . '</a></div>';

			unset($_SESSION['Items' . $identifier]->LineItems);
			unset($_SESSION['Items' . $identifier]);
			unset($_SESSION['ProcessingOrder']);
			include ('includes/footer.php');
			exit;
		}
	} /*loop through all line items of the order to ensure none have been invoiced since started looking at this order*/

	DB_free_result($Result);

	// *************************************************************************
	//   S T A R T   O F   I N V O I C E   S Q L   P R O C E S S I N G
	// *************************************************************************
	/*Now Get the next invoice number - function in SQL_CommonFunctions*/

	$InvoiceNo = GetNextTransNo(10);
	$PeriodNo = GetPeriod($DefaultDispatchDate);

	$_SESSION['Items' . $identifier]->total = round($_SESSION['Items' . $identifier]->total, $_SESSION['Items' . $identifier]->CurrDecimalPlaces);
	$TaxTotal = round($TaxTotal, $_SESSION['Items' . $identifier]->CurrDecimalPlaces);

	/*Start an SQL transaction */
	DB_Txn_Begin();

	if ($DefaultShipVia != $_SESSION['Items' . $identifier]->ShipVia) {
		$SQL = "UPDATE custbranch
				SET defaultshipvia ='" . $_SESSION['Items' . $identifier]->ShipVia . "'
				WHERE debtorno='" . $_SESSION['Items' . $identifier]->DebtorNo . "'
				AND branchcode='" . $_SESSION['Items' . $identifier]->Branch . "'";
		$ErrMsg = _('Could not update the default shipping carrier for this branch because');
		$DbgMsg = _('The SQL used to update the branch default carrier was');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
	}

	$DefaultDispatchDate = FormatDateForSQL($DefaultDispatchDate);

	/*Update order header for invoice charged on */
	$SQL = "UPDATE salesorders
			SET comments = CONCAT(comments,' Inv ','" . $InvoiceNo . "'),
			internalcomment = '" . $_POST['InternalComments'] . "',
			printedpackingslip=0
			WHERE orderno= '" . $_SESSION['ProcessingOrder'] . "'";

	$ErrMsg = _('CRITICAL ERROR') . ' ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales order header could not be updated with the invoice number');
	$DbgMsg = _('The following SQL to update the sales order was used');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

	/*Now insert the DebtorTrans */

	$SQL = "INSERT INTO debtortrans (transno,
									type,
									debtorno,
									branchcode,
									trandate,
									inputdate,
									prd,
									reference,
									tpe,
									order_,
									ovamount,
									ovgst,
									ovfreight,
									rate,
									invtext,
									shipvia,
									consignment,
									packages,
									salesperson )
								VALUES (
									'" . $InvoiceNo . "',
									10,
									'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
									'" . $_SESSION['Items' . $identifier]->Branch . "',
									'" . $DefaultDispatchDate . "',
									'" . date('Y-m-d H-i-s') . "',
									'" . $PeriodNo . "',
									'" . $_SESSION['Items' . $identifier]->CustRef . "',
									'" . $_SESSION['Items' . $identifier]->DefaultSalesType . "',
									'" . $_SESSION['ProcessingOrder'] . "',
									'" . $_SESSION['Items' . $identifier]->total . "',
									'" . $TaxTotal . "',
									'" . filter_number_format($_POST['ChargeFreightCost']) . "',
									'" . $_SESSION['CurrencyRate'] . "',
									'" . $_POST['InvoiceText'] . "',
									'" . $_SESSION['Items' . $identifier]->ShipVia . "',
									'" . $_POST['Consignment'] . "',
									'" . $_POST['Packages'] . "',
									'" . $_SESSION['Items' . $identifier]->SalesPerson . "' )";

	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction record could not be inserted because');
	$DbgMsg = _('The following SQL to insert the debtor transaction record was used');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
	$DebtorTransID = DB_Last_Insert_ID('debtortrans', 'id');

	/* Insert the tax totals for each tax authority where tax was charged on the invoice */
	foreach ($TaxTotals AS $TaxAuthID => $TaxAmount) {

		$SQL = "INSERT INTO debtortranstaxes (debtortransid,
											taxauthid,
											taxamount)
								VALUES ('" . $DebtorTransID . "',
									'" . $TaxAuthID . "',
									'" . $TaxAmount / $_SESSION['CurrencyRate'] . "')";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction taxes records could not be inserted because');
		$DbgMsg = _('The following SQL to insert the debtor transaction taxes record was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
	}

	/* If balance of the order cancelled update sales order details quantity. Also insert log records for OrderDeliveryDifferencesLog */

	foreach ($_SESSION['Items' . $identifier]->LineItems as $OrderLine) {

		/*Test to see if the item being sold is an asset */
		if (mb_substr($OrderLine->StockID, 0, 6) == 'ASSET-') {
			$IsAsset = true;
			$HyphenOccursAt = mb_strpos($OrderLine->StockID, '-', 6);
			if ($HyphenOccursAt == false) {
				$AssetNumber = intval(mb_substr($OrderLine->StockID, 6));
			} else {
				$AssetNumber = intval(mb_substr($OrderLine->StockID, 6, mb_strlen($OrderLine->StockID) - $HyphenOccursAt - 1));
			}
			prnMsg(_('The asset number being disposed of is:') . ' ' . $AssetNumber, 'info');
		} else {
			$IsAsset = false;
			$AssetNumber = 0;
		}

		if ($_POST['BOPolicy'] == 'CAN') {

			$SQL = "UPDATE salesorderdetails
					SET quantity = quantity - " . ($OrderLine->Quantity - $OrderLine->QtyDispatched - $OrderLine->QtyInv) . "
					WHERE orderno = '" . $_SESSION['ProcessingOrder'] . " '
						AND orderlineno = '" . $OrderLine->LineNumber . "'";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales order detail record could not be updated because');
			$DbgMsg = _('The following SQL to update the sales order detail record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			if (($OrderLine->Quantity - $OrderLine->QtyDispatched) > 0) {

				$SQL = "INSERT INTO orderdeliverydifferenceslog (orderno,
															invoiceno,
															stockid,
															quantitydiff,
															debtorno,
															branch,
															can_or_bo)
														VALUES (
															'" . $_SESSION['ProcessingOrder'] . "',
															'" . $InvoiceNo . "',
															'" . $OrderLine->StockID . "',
															'" . ($OrderLine->Quantity - $OrderLine->QtyDispatched - $OrderLine->QtyInv) . "',
															'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
															'" . $_SESSION['Items' . $identifier]->Branch . "',
															'CAN')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The order delivery differences log record could not be inserted because');
				$DbgMsg = _('The following SQL to insert the order delivery differences record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			}

		} elseif (($OrderLine->Quantity - $OrderLine->QtyDispatched) > 0 and DateDiff(ConvertSQLDate($DefaultDispatchDate), $_SESSION['Items' . $identifier]->DeliveryDate, 'd') > 0) {

			/*The order is being short delivered after the due date - need to insert a delivery differnce log */

			$SQL = "INSERT INTO orderdeliverydifferenceslog (orderno,
															invoiceno,
															stockid,
															quantitydiff,
															debtorno,
															branch,
															can_or_bo
														)
												VALUES (
													'" . $_SESSION['ProcessingOrder'] . "',
													'" . $InvoiceNo . "',
													'" . $OrderLine->StockID . "',
													'" . ($OrderLine->Quantity - $OrderLine->QtyDispatched - $OrderLine->QtyInv) . "',
													'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
													'" . $_SESSION['Items' . $identifier]->Branch . "',
													'BO'
												)";

			$ErrMsg = '<br />' . _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The order delivery differences log record could not be inserted because');
			$DbgMsg = _('The following SQL to insert the order delivery differences record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
		} /*end of order delivery differences log entries */

		/*Now update SalesOrderDetails for the quantity invoiced and the actual dispatch dates. */

		if ($OrderLine->QtyDispatched != 0 and $OrderLine->QtyDispatched != '' and $OrderLine->QtyDispatched) {

			// Test above to see if the line is completed or not
			if ($OrderLine->QtyDispatched >= ($OrderLine->Quantity - $OrderLine->QtyInv) or $_POST['BOPolicy'] == 'CAN') {
				$SQL = "UPDATE salesorderdetails
							SET qtyinvoiced = qtyinvoiced + " . $OrderLine->QtyDispatched . ",
								actualdispatchdate = '" . $DefaultDispatchDate . "',
								completed=1
							WHERE orderno = '" . $_SESSION['ProcessingOrder'] . "'
							AND orderlineno = '" . $OrderLine->LineNumber . "'";
			} else {
				$SQL = "UPDATE salesorderdetails
							SET qtyinvoiced = qtyinvoiced + " . $OrderLine->QtyDispatched . ",
								actualdispatchdate = '" . $DefaultDispatchDate . "'
							WHERE orderno = '" . $_SESSION['ProcessingOrder'] . "'
							AND orderlineno = '" . $OrderLine->LineNumber . "'";

			}

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales order detail record could not be updated because');
			$DbgMsg = _('The following SQL to update the sales order detail record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			/*update any open pickreqdetails*/
			$LineItemsSQL = "SELECT pickreqdetails.detailno
							FROM pickreqdetails INNER JOIN pickreq ON pickreq.prid=pickreqdetails.prid
							INNER JOIN salesorderdetails
								ON salesorderdetails.orderno = pickreq.orderno
								AND salesorderdetails.orderlineno=pickreqdetails.orderlineno
							WHERE pickreq.orderno ='" . $_SESSION['ProcessingOrder'] . "'
							AND pickreq.closed=0
							AND salesorderdetails.orderlineno='" . $OrderLine->LineNumber . "'";

			$ErrMsg = _('The line items of the pick list cannot be retrieved because');
			$DbgMsg = _('The SQL that failed was');
			$LineItemsResult = DB_query($LineItemsSQL, $ErrMsg, $DbgMsg);

			$MyLine = DB_fetch_array($LineItemsResult);
			$DetailNo = $MyLine['detailno'];
			$SQL = "UPDATE pickreqdetails
					SET invoicedqty='" . $OrderLine->QtyDispatched . "'
					WHERE detailno='" . $DetailNo . "'";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The pickreqdetail record could not be inserted because');
			$DbgMsg = _('The following SQL to insert the pickreqdetail records was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			/* Update location stock records if not a dummy stock item
			 need the MBFlag later too so save it to $MBFlag */
			$Result = DB_query("SELECT mbflag
								FROM stockmaster
								WHERE stockid = '" . $OrderLine->StockID . "'", _('Cannot retrieve the mbflag'));

			$MyRow = DB_fetch_row($Result);
			$MBFlag = $MyRow[0];

			if ($MBFlag == 'B' or $MBFlag == 'M') {
				$Assembly = False;

				/* Need to get the current location quantity
				 will need it later for the stock movement */
				$SQL = "SELECT locstock.quantity
						FROM locstock
						WHERE locstock.stockid='" . $OrderLine->StockID . "'
						AND loccode= '" . $_SESSION['Items' . $identifier]->Location . "'";
				$ErrMsg = _('WARNING') . ': ' . _('Could not retrieve current location stock');
				$Result = DB_query($SQL, $ErrMsg);

				if (DB_num_rows($Result) == 1) {
					$LocQtyRow = DB_fetch_row($Result);
					$QtyOnHandPrior = $LocQtyRow[0];
				} else {
					/* There must be some error this should never happen */
					$QtyOnHandPrior = 0;
				}

				$SQL = "UPDATE locstock
						SET quantity = locstock.quantity - " . $OrderLine->QtyDispatched . "
						WHERE locstock.stockid = '" . $OrderLine->StockID . "'
						AND loccode = '" . $_SESSION['Items' . $identifier]->Location . "'";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Location stock record could not be updated because');
				$DbgMsg = _('The following SQL to update the location stock record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			} elseif ($MBFlag == 'A') { /* its an assembly */
				/*Need to get the BOM for this part and make
				 stock moves for the components then update the Location stock balances */
				$Assembly = True;
				$StandardCost = 0; /*To start with - accumulate the cost of the comoponents for use in journals later on */
				$SQL = "SELECT bom.component,
								bom.quantity,
								stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standard
							FROM bom INNER JOIN stockmaster
							ON bom.component=stockmaster.stockid
							WHERE bom.parent='" . $OrderLine->StockID . "'
								AND bom.effectiveto > CURRENT_DATE
								AND bom.effectiveafter <= CURRENT_DATE";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not retrieve assembly components from the database for') . ' ' . $OrderLine->StockID . _('because') . ' ';
				$DbgMsg = _('The SQL that failed was');
				$AssResult = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				while ($AssParts = DB_fetch_array($AssResult)) {

					$StandardCost+= ($AssParts['standard'] * $AssParts['quantity']);
					/* Need to get the current location quantity
					 will need it later for the stock movement */
					$SQL = "SELECT locstock.quantity
							FROM locstock
							WHERE locstock.stockid='" . $AssParts['component'] . "'
							AND loccode= '" . $_SESSION['Items' . $identifier]->Location . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Can not retrieve assembly components location stock quantities because ');
					$DbgMsg = _('The SQL that failed was');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					if (DB_num_rows($Result) == 1) {
						$LocQtyRow = DB_fetch_row($Result);
						$QtyOnHandPrior = $LocQtyRow[0];
					} else {
						/*There must be some error this should never happen */
						$QtyOnHandPrior = 0;
					}
					if (empty($AssParts['standard'])) {
						$AssParts['standard'] = 0;
					}
					$SQL = "INSERT INTO stockmoves (stockid,
													type,
													transno,
													loccode,
													trandate,
													userid,
													debtorno,
													branchcode,
													prd,
													reference,
													qty,
													standardcost,
													show_on_inv_crds,
													newqoh)
										VALUES ('" . $AssParts['component'] . "',
												 10,
												 '" . $InvoiceNo . "',
												 '" . $_SESSION['Items' . $identifier]->Location . "',
												 '" . $DefaultDispatchDate . "',
												 '" . $_SESSION['UserID'] . "',
												 '" . $_SESSION['Items' . $identifier]->DebtorNo . "',
												 '" . $_SESSION['Items' . $identifier]->Branch . "',
												 '" . $PeriodNo . "',
												 '" . _('Assembly') . ': ' . $OrderLine->StockID . ' ' . _('Order') . ': ' . $_SESSION['ProcessingOrder'] . "',
												 '" . -$AssParts['quantity'] * $OrderLine->QtyDispatched . "',
												 '" . $AssParts['standard'] . "',
												 0,
												 '" . ($QtyOnHandPrior - $AssParts['quantity'] * $OrderLine->QtyDispatched) . "'	)";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records for the assembly components of') . ' ' . $OrderLine->StockID . ' ' . _('could not be inserted because');
					$DbgMsg = _('The following SQL to insert the assembly components stock movement records was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					$SQL = "UPDATE locstock
							SET quantity = locstock.quantity - " . ($AssParts['quantity'] * $OrderLine->QtyDispatched) . "
							WHERE locstock.stockid = '" . $AssParts['component'] . "'
							AND loccode = '" . $_SESSION['Items' . $identifier]->Location . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Location stock record could not be updated for an assembly component because');
					$DbgMsg = _('The following SQL to update the locations stock record for the component was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				} /* end of assembly explosion and updates */

				/*Update the cart with the recalculated standard cost from the explosion of the assembly's components*/
				$_SESSION['Items' . $identifier]->LineItems[$OrderLine->LineNumber]->StandardCost = $StandardCost;
				$OrderLine->StandardCost = $StandardCost;
			} /* end of its an assembly */

			// Insert stock movements - with unit cost
			//$LocalCurrencyPrice = round(($OrderLine->Price / $_SESSION['CurrencyRate']),$_SESSION['CompanyRecord']['decimalplaces']); change decimalplaces to 5 to avoid price or lines total variance on invoice. And the decimal places should not be over 5 since the stockmoves table defined it as decimal(21,5) now.
			$LocalCurrencyPrice = round(($OrderLine->Price / $_SESSION['CurrencyRate']), 5);

			if (empty($OrderLine->StandardCost)) {
				$OrderLine->StandardCost = 0;
			}
			if ($MBFlag == 'B' or $MBFlag == 'M') {
				$SQL = "INSERT INTO stockmoves (stockid,
														type,
														transno,
														loccode,
														trandate,
														userid,
														debtorno,
														branchcode,
														price,
														prd,
														reference,
														qty,
														discountpercent,
														standardcost,
														newqoh,
														narrative )
													VALUES ('" . $OrderLine->StockID . "',
														10,
														'" . $InvoiceNo . "',
														'" . $_SESSION['Items' . $identifier]->Location . "',
														'" . $DefaultDispatchDate . "',
														'" . $_SESSION['UserID'] . "',
														'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
														'" . $_SESSION['Items' . $identifier]->Branch . "',
														'" . $LocalCurrencyPrice . "',
														'" . $PeriodNo . "',
														'" . DB_escape_string($_SESSION['ProcessingOrder']) . "',
														'" . -$OrderLine->QtyDispatched . "',
														'" . $OrderLine->DiscountPercent . "',
														'" . $OrderLine->StandardCost . "',
														'" . ($QtyOnHandPrior - $OrderLine->QtyDispatched) . "',
														'" . DB_escape_string($OrderLine->Narrative) . "' )";
			} else {
				// its an assembly or dummy and assemblies/dummies always have nil stock (by definition they are made up at the time of dispatch so new qty on hand will be nil
				if (empty($OrderLine->StandardCost)) {
					$OrderLine->StandardCost = 0;
				}
				$SQL = "INSERT INTO stockmoves (stockid,
												type,
												transno,
												loccode,
												trandate,
												userid,
												debtorno,
												branchcode,
												price,
												prd,
												reference,
												qty,
												discountpercent,
												standardcost,
												narrative )
											VALUES ('" . $OrderLine->StockID . "',
												10,
												'" . $InvoiceNo . "',
												'" . $_SESSION['Items' . $identifier]->Location . "',
												'" . $DefaultDispatchDate . "',
												'" . $_SESSION['UserID'] . "',
												'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
												'" . $_SESSION['Items' . $identifier]->Branch . "',
												'" . $LocalCurrencyPrice . "',
												'" . $PeriodNo . "',
												'" . DB_escape_string($_SESSION['ProcessingOrder']) . "',
												'" . -$OrderLine->QtyDispatched . "',
												'" . $OrderLine->DiscountPercent . "',
												'" . $OrderLine->StandardCost . "',
												'" . DB_escape_string($OrderLine->Narrative) . "')";
			}

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records could not be inserted because');
			$DbgMsg = _('The following SQL to insert the stock movement records was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			/*Get the ID of the StockMove... */
			$StkMoveNo = DB_Last_Insert_ID('stockmoves', 'stkmoveno');

			/*Insert the taxes that applied to this line */
			foreach ($OrderLine->Taxes as $Tax) {

				$SQL = "INSERT INTO stockmovestaxes (stkmoveno,
													taxauthid,
													taxrate,
													taxcalculationorder,
													taxontax)
										VALUES ('" . $StkMoveNo . "',
											'" . $Tax->TaxAuthID . "',
											'" . $Tax->TaxRate . "',
											'" . $Tax->TaxCalculationOrder . "',
											'" . $Tax->TaxOnTax . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Taxes and rates applicable to this invoice line item could not be inserted because');
				$DbgMsg = _('The following SQL to insert the stock movement tax detail records was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			}

			/* Insert the StockSerialMovements and update the StockSerialItems  for controlled items*/

			if ($OrderLine->Controlled == 1) {
				foreach ($OrderLine->SerialItems as $Item) {
					/*We need to add the StockSerialItem record and the StockSerialMoves as well */

					$SQL = "UPDATE stockserialitems	SET quantity= quantity - " . $Item->BundleQty . "
							WHERE stockid='" . $OrderLine->StockID . "'
							AND loccode='" . $_SESSION['Items' . $identifier]->Location . "'
							AND serialno='" . $Item->BundleRef . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated because');
					$DbgMsg = _('The following SQL to update the serial stock item record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					/* now insert the serial stock movement */

					$SQL = "INSERT INTO stockserialmoves (stockmoveno,
														stockid,
														serialno,
														moveqty)
									VALUES ('" . $StkMoveNo . "',
											'" . $OrderLine->StockID . "',
											'" . $Item->BundleRef . "',
											'" . -$Item->BundleQty . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
					$DbgMsg = _('The following SQL to insert the serial stock movement records was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				} /* foreach controlled item in the serialitems array */
			} /*end if the orderline is a controlled item */

			/*Insert Sales Analysis records */

			$SalesValue = 0;
			if ($_SESSION['CurrencyRate'] > 0) {
				$SalesValue = $OrderLine->Price * $OrderLine->QtyDispatched / $_SESSION['CurrencyRate'];
			}

			$SQL = "SELECT COUNT(*),
						salesanalysis.stockid,
						salesanalysis.stkcategory,
						salesanalysis.cust,
						salesanalysis.custbranch,
						salesanalysis.area,
						salesanalysis.periodno,
						salesanalysis.typeabbrev,
						salesanalysis.salesperson
					FROM salesanalysis
					INNER JOIN custbranch
						ON salesanalysis.cust=custbranch.debtorno
						AND salesanalysis.custbranch=custbranch.branchcode
						AND salesanalysis.area=custbranch.area
					INNER JOIN stockmaster
					ON salesanalysis.stkcategory=stockmaster.categoryid
					WHERE salesanalysis.salesperson='" . $_SESSION['Items' . $identifier]->SalesPerson . "'
						AND salesanalysis.typeabbrev ='" . $_SESSION['Items' . $identifier]->DefaultSalesType . "'
						AND salesanalysis.periodno='" . $PeriodNo . "'
						AND salesanalysis.cust='" . $_SESSION['Items' . $identifier]->DebtorNo . "'
						AND salesanalysis.custbranch='" . $_SESSION['Items' . $identifier]->Branch . "'
						AND salesanalysis.stockid='" . $OrderLine->StockID . "'
						AND salesanalysis.budgetoractual=1
					GROUP BY salesanalysis.stockid,
						salesanalysis.stkcategory,
						salesanalysis.cust,
						salesanalysis.custbranch,
						salesanalysis.area,
						salesanalysis.periodno,
						salesanalysis.typeabbrev,
						salesanalysis.salesperson,
						salesanalysis.budgetoractual";

			$ErrMsg = _('The count of existing Sales analysis records could not run because');
			$DbgMsg = '<br />' . _('SQL to count the no of sales analysis records');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			$MyRow = DB_fetch_row($Result);

			if ($MyRow[0] > 0) { /*Update the existing record that already exists */

				$SQL = "UPDATE salesanalysis SET amt=amt+" . round(($SalesValue), $_SESSION['CompanyRecord']['decimalplaces']) . ",
												cost=cost+" . round(($OrderLine->StandardCost * $OrderLine->QtyDispatched), $_SESSION['CompanyRecord']['decimalplaces']) . ",
												qty=qty +" . $OrderLine->QtyDispatched . ",
												disc=disc+" . round(($OrderLine->DiscountPercent * $SalesValue), $_SESSION['CompanyRecord']['decimalplaces']) . "
								WHERE salesanalysis.area='" . $MyRow[5] . "'
								AND salesanalysis.salesperson='" . $MyRow[8] . "'
								AND typeabbrev ='" . $_SESSION['Items' . $identifier]->DefaultSalesType . "'
								AND periodno = '" . $PeriodNo . "'
								AND cust " . LIKE . " '" . $_SESSION['Items' . $identifier]->DebtorNo . "'
								AND custbranch " . LIKE . " '" . $_SESSION['Items' . $identifier]->Branch . "'
								AND stockid " . LIKE . " '" . $OrderLine->StockID . "'
								AND salesanalysis.stkcategory ='" . $MyRow[2] . "'
								AND budgetoractual=1";

			} else { /* insert a new sales analysis record */

				$SQL = "INSERT INTO salesanalysis (typeabbrev,
												periodno,
												amt,
												cost,
												cust,
												custbranch,
												qty,
												disc,
												stockid,
												area,
												budgetoractual,
												salesperson,
												stkcategory )
								SELECT '" . $_SESSION['Items' . $identifier]->DefaultSalesType . "',
										'" . $PeriodNo . "',
										'" . round(($SalesValue), $_SESSION['CompanyRecord']['decimalplaces']) . "',
										'" . round(($OrderLine->StandardCost * $OrderLine->QtyDispatched), $_SESSION['CompanyRecord']['decimalplaces']) . "',
										'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
										'" . $_SESSION['Items' . $identifier]->Branch . "',
										'" . ($OrderLine->QtyDispatched) . "',
										'" . round(($OrderLine->DiscountPercent * $SalesValue), $_SESSION['CompanyRecord']['decimalplaces']) . "',
										'" . $OrderLine->StockID . "',
										custbranch.area,
										1,
										'" . $_SESSION['Items' . $identifier]->SalesPerson . "',
										stockmaster.categoryid
								FROM stockmaster, custbranch
								WHERE stockmaster.stockid = '" . $OrderLine->StockID . "'
								AND custbranch.debtorno = '" . $_SESSION['Items' . $identifier]->DebtorNo . "'
								AND custbranch.branchcode='" . $_SESSION['Items' . $identifier]->Branch . "'";
			}

			$ErrMsg = _('Sales analysis record could not be added or updated because');
			$DbgMsg = _('The following SQL to insert the sales analysis record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			/* If GLLink_Stock then insert GLTrans to credit stock and debit cost of sales at standard cost*/

			if ($_SESSION['CompanyRecord']['gllink_stock'] == 1 and $OrderLine->StandardCost != 0 and !$IsAsset) {

				/*first the cost of sales entry - GL accounts are retrieved using the function GetCOGSGLAccount from includes/GetSalesTransGLCodes.inc */

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES (
										10,
										'" . $InvoiceNo . "',
										'" . $DefaultDispatchDate . "',
										'" . $PeriodNo . "',
										'" . GetCOGSGLAccount($Area, $OrderLine->StockID, $_SESSION['Items' . $identifier]->DefaultSalesType) . "',
										'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $OrderLine->StockID . " x " . $OrderLine->QtyDispatched . " @ " . $OrderLine->StandardCost . "',
										'" . round(($OrderLine->StandardCost * $OrderLine->QtyDispatched), $_SESSION['CompanyRecord']['decimalplaces']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The cost of sales GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				/*now the stock entry - this is set to the cost act in the case of a fixed asset disposal */
				$StockGLCode = GetStockGLCode($OrderLine->StockID);

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES (
										10,
										'" . $InvoiceNo . "',
										'" . $DefaultDispatchDate . "',
										'" . $PeriodNo . "',
										'" . $StockGLCode['stockact'] . "',
										'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $OrderLine->StockID . " x " . $OrderLine->QtyDispatched . " @ " . $OrderLine->StandardCost . "',
										'" . round((-$OrderLine->StandardCost * $OrderLine->QtyDispatched), $_SESSION['CompanyRecord']['decimalplaces']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock side of the cost of sales GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			} /* end of if GL and stock integrated and standard cost !=0 and not an asset */

			if ($_SESSION['CompanyRecord']['gllink_debtors'] == 1 and $OrderLine->Price != 0) {

				if (!$IsAsset) { // its a normal stock item
					//Post sales transaction to GL credit sales
					$SalesGLAccounts = GetSalesGLAccount($Area, $OrderLine->StockID, $_SESSION['Items' . $identifier]->DefaultSalesType);

					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount )
										VALUES (
											10,
											'" . $InvoiceNo . "',
											'" . $DefaultDispatchDate . "',
											'" . $PeriodNo . "',
											'" . $SalesGLAccounts['salesglcode'] . "',
											'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $OrderLine->StockID . " x " . $OrderLine->QtyDispatched . " @ " . $OrderLine->Price . "',
											'" . (-$OrderLine->Price * $OrderLine->QtyDispatched / $_SESSION['CurrencyRate']) . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales GL posting could not be inserted because');
					$DbgMsg = '<br />' . _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					if ($OrderLine->DiscountPercent != 0) {

						$SQL = "INSERT INTO gltrans (type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount)
												VALUES (
													10,
													'" . $InvoiceNo . "',
													'" . $DefaultDispatchDate . "',
													'" . $PeriodNo . "',
													'" . $SalesGLAccounts['discountglcode'] . "',
													'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $OrderLine->StockID . " @ " . ($OrderLine->DiscountPercent * 100) . "%',
													'" . ($OrderLine->Price * $OrderLine->QtyDispatched * $OrderLine->DiscountPercent / $_SESSION['CurrencyRate']) . "')";

						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales discount GL posting could not be inserted because');
						$DbgMsg = _('The following SQL to insert the GLTrans record was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					} /*end of if discount !=0 */

				} else {
					/* then the item being sold is an asset disposal
					 * the cost of sales account will be the gain or loss on disposal account
					 * from the fixed asset categories table */
					$SQL = "SELECT cost,
									accumdepn,
									costact,
									accumdepnact,
									disposalact
						FROM fixedassetcategories INNER JOIN fixedassets
						ON fixedassetcategories.categoryid = fixedassets.assetcategoryid
						WHERE assetid ='" . $AssetNumber . "'";
					$ErrMsg = _('The asset disposal GL posting details could not be retrieved because');
					$DbgMsg = _('The following SQL was used to get the asset posting details');
					$DisposalResult = DB_query($SQL, $ErrMsg, $DbgMsg);
					$DisposalRow = DB_fetch_array($DisposalResult);

					/* Need to :
					 * 1.) Debit the accumulated depreciation account with whole amount of accumulated depreciation
					 * 2.) Credit the cost account with the whole amount of the cost
					 * 3.) Debit the disposal account with the NBV
					 * 4.) Credit the disposal account with the sale proceeds net of discounts */

					// 1.) Debit the accumulated depreciation account:
					if ($DisposalRow['accumdepn'] != 0) {
						$SQL = "INSERT INTO gltrans (type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount)
											VALUES (
												10,
												'" . $InvoiceNo . "',
												'" . $DefaultDispatchDate . "',
												'" . $PeriodNo . "',
												'" . $DisposalRow['accumdepnact'] . "',
												'" . $_SESSION['Items' . $identifier]->DebtorNo . ' - ' . $OrderLine->StockID . ' ' . _('accumulated depreciation disposal') . "',
												'" . $DisposalRow['accumdepn'] . "')";

						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The reversal of accumulated depreciation GL posting on disposal could not be inserted because');
						$DbgMsg = _('The following SQL to insert the GLTrans record was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					}
					// 2.) Credit the cost account:
					if ($DisposalRow['cost'] != 0) {
						$SQL = "INSERT INTO gltrans (
									type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount
								) VALUES (
									10,'" . $InvoiceNo . "','" . $DefaultDispatchDate . "','" . $PeriodNo . "','" . $DisposalRow['costact'] . "','" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $OrderLine->StockID . ' ' . _('cost disposal') . "','" . -$DisposalRow['cost'] . "')";
						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The reversal of asset cost on disposal GL posting could not be inserted because');
						$DbgMsg = _('The following SQL to insert the GLTrans record was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					}
					// 3.) Debit the disposal account with the NBV:
					if ($DisposalRow['cost'] - $DisposalRow['accumdepn'] != 0) {
						$SQL = "INSERT INTO gltrans (type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount )
											VALUES (
												10,
												'" . $InvoiceNo . "',
												'" . $DefaultDispatchDate . "',
												'" . $PeriodNo . "',
												'" . $DisposalRow['disposalact'] . "',
												'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $OrderLine->StockID . ' ' . _('net book value disposal') . "',
												'" . ($DisposalRow['cost'] - $DisposalRow['accumdepn']) . "')";

						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The disposal net book value GL posting could not be inserted because');
						$DbgMsg = '<br />' . _('The following SQL to insert the GLTrans record was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					}
					//4. Credit the disposal account with the proceeds
					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount )
										VALUES (
											10,
											'" . $InvoiceNo . "',
											'" . $DefaultDispatchDate . "',
											'" . $PeriodNo . "',
											'" . $DisposalRow['disposalact'] . "',
											'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $OrderLine->StockID . ' ' . _('asset disposal proceeds') . "',
											'" . (-$OrderLine->Price * $OrderLine->QtyDispatched * (1 - $OrderLine->DiscountPercent) / $_SESSION['CurrencyRate']) . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The disposal proceeds GL posting could not be inserted because');
					$DbgMsg = '<br />' . _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				} // End if the item being sold was an asset.
				
			} /*end of if sales integrated with debtors */

			if ($IsAsset) {
				/* then the item being sold is an asset disposal
				 * need to create fixedassettrans
				 * set disposal date and proceeds
				*/
				$SQL = "INSERT INTO fixedassettrans (assetid,
													transtype,
													transno,
													periodno,
													inputdate,
													fixedassettranstype,
													amount,
													transdate)
										VALUES ('" . $AssetNumber . "',
												10,
												'" . $InvoiceNo . "',
												'" . $PeriodNo . "',
												CURRENT_DATE,
												'disposal',
												'" . round(($OrderLine->Price * $OrderLine->QtyDispatched * (1 - $OrderLine->DiscountPercent) / $_SESSION['CurrencyRate']), $_SESSION['CompanyRecord']['decimalplaces']) . "',
												'" . $DefaultDispatchDate . "')";
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The fixed asset transaction could not be inserted because');
				$DbgMsg = '<br />' . _('The following SQL to insert the fixed asset transaction record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				$SQL = "UPDATE fixedassets
						SET disposalproceeds ='" . round(($OrderLine->Price * $OrderLine->QtyDispatched * (1 - $OrderLine->DiscountPercent) / $_SESSION['CurrencyRate']), $_SESSION['CompanyRecord']['decimalplaces']) . "',
							disposaldate ='" . $DefaultDispatchDate . "'
						WHERE assetid ='" . $AssetNumber . "'";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The fixed asset record could not be updated for the disposal because');
				$DbgMsg = '<br />' . _('The following SQL to update the fixed asset record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			}
		} /*Quantity dispatched is more than 0 */
	} /*end of OrderLine loop */

	/*update any open pick list*/
	$SQL = "UPDATE pickreq
			SET status = 'Invoiced',
				closed='1'
			WHERE orderno= '" . $_SESSION['ProcessingOrder'] . "'
			AND closed=0";
	$ErrMsg = _('CRITICAL ERROR') . ' ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The pick list header could not be updated');
	$DbgMsg = _('The following SQL to update the pick list was used');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

	if ($_SESSION['CompanyRecord']['gllink_debtors'] == 1) {

		/*Post debtors transaction to GL debit debtors, credit freight re-charged and credit sales */
		if (($_SESSION['Items' . $identifier]->total + $_SESSION['Items' . $identifier]->FreightCost + $TaxTotal) != 0) {
			$SQL = "INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
									VALUES (
										10,
										'" . $InvoiceNo . "',
										'" . $DefaultDispatchDate . "',
										'" . $PeriodNo . "',
										'" . $_SESSION['CompanyRecord']['debtorsact'] . "',
										'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
										'" . (($_SESSION['Items' . $identifier]->total + $_SESSION['Items' . $identifier]->FreightCost + $TaxTotal) / $_SESSION['CurrencyRate']) . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The total debtor GL posting could not be inserted because');
			$DbgMsg = _('The following SQL to insert the total debtors control GLTrans record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
		}

		/*Could do with setting up a more flexible freight posting schema that looks at the sales type and area of the customer branch to determine where to post the freight recovery */

		if ($_SESSION['Items' . $identifier]->FreightCost != 0) {
			$SQL = "INSERT INTO gltrans (
						type,
						typeno,
						trandate,
						periodno,
						account,
						narrative,
						amount	)
				VALUES (
					10,
					'" . $InvoiceNo . "',
					'" . $DefaultDispatchDate . "',
					'" . $PeriodNo . "',
					'" . $_SESSION['CompanyRecord']['freightact'] . "',
					'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
					'" . (-$_SESSION['Items' . $identifier]->FreightCost / $_SESSION['CurrencyRate']) . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The freight GL posting could not be inserted because');
			$DbgMsg = _('The following SQL to insert the GLTrans record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
		}
		foreach ($TaxTotals as $TaxAuthID => $TaxAmount) {
			if ($TaxAmount != 0) {
				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
										VALUES (
											10,
											'" . $InvoiceNo . "',
											'" . $DefaultDispatchDate . "',
											'" . $PeriodNo . "',
											'" . $TaxGLCodes[$TaxAuthID] . "',
											'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
											'" . (-$TaxAmount / $_SESSION['CurrencyRate']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The tax GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			}
		}
	} /*end of if Sales and GL integrated */

	DB_Txn_Commit();
	EnsureGLEntriesBalance(10, $InvoiceNo);
	// *************************************************************************
	//   E N D   O F   I N V O I C E   S Q L   P R O C E S S I N G
	// *************************************************************************
	unset($_SESSION['Items' . $identifier]->LineItems);
	unset($_SESSION['Items' . $identifier]);
	unset($_SESSION['ProcessingOrder']);

	echo prnMsg(_('Invoice number') . ' ' . $InvoiceNo . ' ' . _('processed'), 'success');

	echo '<br /><div class="centre">';

	if ($_SESSION['InvoicePortraitFormat'] == 0) {
		echo '<img src="' . $RootPath . '/css/' . $Theme . '/images/printer.png" title="' . _('Print') . '" alt="" />' . ' ' . '<a target="_blank" href="' . $RootPath . '/PrintCustTrans.php?FromTransNo=' . $InvoiceNo . '&amp;InvOrCredit=Invoice&amp;PrintPDF=True">' . _('Print this invoice') . ' (' . _('Landscape') . ')</a><br /><br />';
	} else {
		echo '<img src="' . $RootPath . '/css/' . $Theme . '/images/printer.png" title="' . _('Print') . '" alt="" />' . ' ' . '<a target="_blank" href="' . $RootPath . '/PrintCustTransPortrait.php?FromTransNo=' . $InvoiceNo . '&amp;InvOrCredit=Invoice&amp;PrintPDF=True">' . _('Print this invoice') . ' (' . _('Portrait') . ')</a><br /><br />';
	}
	echo '<a href="' . $RootPath . '/SelectSalesOrder.php">' . _('Select another order for invoicing') . '</a><br /><br />';
	echo '<a href="' . $RootPath . '/SelectOrderItems.php?NewOrder=Yes">' . _('Sales Order Entry') . '</a></div><br />';
	/*end of process invoice */

} else { /*Process Invoice not set so allow input of invoice data */

	if (!isset($_POST['Consignment'])) {
		if ($_SESSION['Items' . $identifier]->Consignment != '') {
			$_POST['Consignment'] = $_SESSION['Items' . $identifier]->Consignment;
		} else {
			$_POST['Consignment'] = '';
		}
	}
	if (!isset($_POST['Packages'])) {
		if ($_SESSION['Items' . $identifier]->Packages) {
			$_POST['Packages'] = $_SESSION['Items' . $identifier]->Packages;
		} else {
			$_POST['Packages'] = '1';
		}
	}
	if (!isset($_POST['InvoiceText'])) {
		$_POST['InvoiceText'] = '';
	}

	echo '<table class="selection">
		<tr>
			<td>', _('Date On Invoice'), ':</td>
			<td><input class="date" maxlength="10" name="DispatchDate" required="required" size="11" type="text" value="', $DefaultDispatchDate, '" /></td>
		</tr>
		<tr>
			<td>', _('Consignment Note Ref'), ':</td>
			<td><input data-type="no-illegal-chars" maxlength="20" name="Consignment" size="20" title="', _('Enter the consignment note reference to enable tracking of the delivery in the event of customer proof of delivery issues'), '" type="text" value="', $_POST['Consignment'], '" /></td>
		</tr>
		<tr>
			<td>', _('No Of Packages in Delivery'), ':</td>
			<td><input class="integer" maxlength="6" name="Packages" size="6" type="number" value="', $_POST['Packages'], '" /></td>
		</tr>
		<tr>
			<td>', _('Action For Balance'), ':</td>
			 <td>
				<select name="BOPolicy" required="required">
					<option selected="selected" value="BO">', _('Automatically put balance on back order'), '</option>
					<option value="CAN">', _('Cancel any quantities not delivered'), '</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>', _('Invoice Text'), ':</td>
			<td><textarea name="InvoiceText" pattern=".{0,20}" cols="31" rows="5">', reverse_escape($_POST['InvoiceText']), '</textarea></td>
		</tr>
		<tr>
			<td>', _('Internal Comments'), ':</td>
			<td><textarea name="InternalComments" pattern=".{0,20}" cols="31" rows="5">', reverse_escape($_SESSION['Items' . $identifier]->InternalComments), '</textarea></td>
		</tr>
	</table>
		<br />
		<div class="centre">
			<input name="Update" type="submit" value="', _('Update'), '" />
			<input name="ProcessInvoice" type="submit" value="', _('Process Invoice'), '" />
		</div>
		<input type="hidden" name="ShipVia" value="' . $_SESSION['Items' . $identifier]->ShipVia . '" />';
}

echo '</div>';
echo '</form>';

include ('includes/footer.php');
?>

<?php
/* Picking List Maintenance */

/* Session started in session.php for password checking and authorisation level check */
include('includes/DefineCartClass.php');
include('includes/DefineSerialItems.php');

include('includes/session.php');
$Title = _('Picking List Maintenance');
$ViewTopic = '';
$BookMark = 'PickingLists';

$ARSecurity = 3;

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other order entry sessions on the same machine  */
	$identifier = date('U');
} else {
	$identifier = $_GET['identifier'];
}

if (!isset($_GET['Prid']) and !isset($_SESSION['ProcessingPick'])) {
	/* This page can only be called with a pick list # */
	echo '<div class="centre">
			<a href="' . $RootPath . '/SelectPickingLists.php">' . _('Select a Pick List') . '</a>
		</div>
		<br />
		<br />';
	prnMsg(_('This page can only be opened if a pick list has been selected Please select a pick list first'), 'error');
	include('includes/footer.php');
	exit;
} elseif (isset($_GET['Prid']) and $_GET['Prid'] > 0) {

	unset($_SESSION['Items' . $identifier]->LineItems);
	unset($_SESSION['Items' . $identifier]);

	$_SESSION['ProcessingPick'] = (int) $_GET['Prid'];
	$_GET['Prid'] = (int) $_GET['Prid'];
	$_SESSION['Items' . $identifier] = new cart;

	/*read in all the guff from the selected order into the Items cart  */

	$OrderHeaderSQL = "SELECT pickreq.prid,
								pickreq.consignment,
								pickreq.packages,
								pickreq.status,
								pickreq.comments,
								salesorders.orderno,
								salesorders.debtorno,
								debtorsmaster.name,
								salesorders.branchcode,
								salesorders.customerref,
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
								custbranch.specialinstructions
						FROM pickreq INNER JOIN salesorders
							ON salesorders.orderno=pickreq.orderno INNER JOIN debtorsmaster
							ON salesorders.debtorno = debtorsmaster.debtorno
						INNER JOIN custbranch
							ON salesorders.branchcode = custbranch.branchcode
							AND salesorders.debtorno = custbranch.debtorno
						INNER JOIN currencies
							ON debtorsmaster.currcode = currencies.currabrev
						INNER JOIN locations
							ON locations.loccode=salesorders.fromstkloc
						INNER JOIN locationusers ON locationusers.loccode=salesorders.fromstkloc AND locationusers.userid='" . $_SESSION['UserID'] . "' AND locationusers.canupd=1
						WHERE pickreq.prid = '" . $_GET['Prid'] . "'
							AND pickreq.closed=0";

	if ($_SESSION['SalesmanLogin'] != '') {
		$OrderHeaderSQL .= " AND salesorders.salesperson='" . $_SESSION['SalesmanLogin'] . "'";
	}

	$ErrMsg = _('The pick list cannot be retrieved because');
	$DbgMsg = _('The SQL to get the order header was');
	$GetOrdHdrResult = DB_query($OrderHeaderSQL, $ErrMsg, $DbgMsg);

	if (DB_num_rows($GetOrdHdrResult) == 1) {

		$MyRow = DB_fetch_array($GetOrdHdrResult);

		$_SESSION['ProcessingPick'] = $MyRow['prid'];
		$_SESSION['Items' . $identifier]->Status = $MyRow['status'];
		$_SESSION['Items' . $identifier]->DebtorNo = $MyRow['debtorno'];
		$_SESSION['Items' . $identifier]->OrderNo = $MyRow['orderno'];
		$_SESSION['Items' . $identifier]->Branch = $MyRow['branchcode'];
		$_SESSION['Items' . $identifier]->CustomerName = $MyRow['name'];
		$_SESSION['Items' . $identifier]->CustRef = $MyRow['customerref'];
		$_SESSION['Items' . $identifier]->Comments = reverse_escape($MyRow['comments']);
		$_SESSION['Items' . $identifier]->InternalComments = reverse_escape($MyRow['internalcomment']);
		$_SESSION['Items' . $identifier]->DefaultSalesType = $MyRow['ordertype'];
		$_SESSION['Items' . $identifier]->DefaultCurrency = $MyRow['currcode'];
		$_SESSION['Items' . $identifier]->CurrDecimalPlaces = $MyRow['decimalplaces'];
		$BestShipper = $MyRow['shipvia'];
		$_SESSION['Items' . $identifier]->ShipVia = $MyRow['shipvia'];
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

		$LineItemsSQL = "SELECT pickreqdetails.detailno,
								pickreqdetails.qtypicked,
								pickreqdetails.shipqty,
								pickreqdetails.detailno,
								stkcode,
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
							FROM pickreqdetails
							INNER JOIN pickreq
								ON pickreq.prid=pickreqdetails.prid
							INNER JOIN salesorderdetails
								ON salesorderdetails.orderno = pickreq.orderno
								AND salesorderdetails.orderlineno=pickreqdetails.orderlineno
							INNER JOIN stockmaster
							 	ON salesorderdetails.stkcode = stockmaster.stockid
							WHERE pickreqdetails.prid ='" . $_GET['Prid'] . "'
								AND salesorderdetails.quantity - salesorderdetails.qtyinvoiced >0
							ORDER BY salesorderdetails.orderlineno";

		$ErrMsg = _('The line items of the pick list cannot be retrieved because');
		$DbgMsg = _('The SQL that failed was');
		$LineItemsResult = DB_query($LineItemsSQL, $ErrMsg, $DbgMsg);

		if (DB_num_rows($LineItemsResult) > 0) {

			while ($MyRow = DB_fetch_array($LineItemsResult)) {

				$_SESSION['Items' . $identifier]->add_to_cart($MyRow['stkcode'], $MyRow['quantity'], $MyRow['description'], $MyRow['longdescription'], $MyRow['unitprice'], $MyRow['discountpercent'], $MyRow['units'], $MyRow['volume'], $MyRow['grossweight'], 0, $MyRow['mbflag'], $MyRow['actualdispatchdate'], $MyRow['qtyinvoiced'], $MyRow['discountcategory'], $MyRow['controlled'], $MyRow['serialised'], $MyRow['decimalplaces'], htmlspecialchars_decode($MyRow['narrative']), 'No', $MyRow['orderlineno'], $MyRow['taxcatid'], '', $MyRow['itemdue'], $MyRow['poline'], $MyRow['standardcost']);
				/*NB NO Updates to DB */

				$SerialItemsSQL = "SELECT pickserialdetails.stockid,
										serialno,
										moveqty
									FROM pickserialdetails
									INNER JOIN pickreqdetails
										ON pickreqdetails.detailno=pickserialdetails.detailno
									WHERE pickreqdetails.prid ='" . $_GET['Prid'] . "'
										AND pickserialdetails.detailno='" . $MyRow['detailno'] . "'";

				$ErrMsg = _('The serial items of the pick list cannot be retrieved because');
				$DbgMsg = _('The SQL that failed was');
				$SerialItemsResult = DB_query($SerialItemsSQL, $ErrMsg, $DbgMsg);
				if (DB_num_rows($SerialItemsResult) > 0) {
					$InOutModifier = 1;
					while ($myserial = DB_fetch_array($SerialItemsResult)) {
						$_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->SerialItems[$myserial['serialno']] = new SerialItem($myserial['serialno'], ($InOutModifier > 0 ? 1 : 1) * filter_number_format($myserial['moveqty']));
					}
				} else {
					$_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->QtyDispatched = $MyRow['qtypicked'];
				}
			}
			/* line items from sales order details */

		} else {
			/* there are no line items that have a quantity to deliver */
			echo '<div class="centre">
					<a href="' . $RootPath . '/SelectPickingLists.php">' . _('Select a Pick List') . '</a>
				</div>';
			prnMsg(_('There are no ordered items with a quantity left to deliver. There is nothing left to invoice'));
			include('includes/footer.php');
			exit;

		} //end of checks on returned data set
		DB_free_result($LineItemsResult);

	} else {
		/*end if the order was returned sucessfully */

		echo '<div class="centre">
				<a href="' . $RootPath . '/SelectPickingLists.php">' . _('Select a Pick List') . '</a>
			</div>';
		prnMsg(_('This pick list item could not be retrieved. Please select another pick list'), 'warn');
		include('includes/footer.php');
		exit;
	} //valid order returned from the entered pick number
}
else {
	/* if processing, a dispatch page has been called and ${$StkItm->LineNumber} would have been set from the post
	set all the necessary session variables changed by the POST  */
	if (isset($_POST['ShipVia'])) {
		$_SESSION['Items' . $identifier]->ShipVia = $_POST['ShipVia'];
	}

	if (isset($_POST['InternalComments'])) {
		$_SESSION['Items' . $identifier]->InternalComments = $_POST['InternalComments'];
	}

	if (isset($_POST['Comments'])) {
		$_SESSION['Items' . $identifier]->Comments = $_POST['Comments'];
	}

	foreach ($_SESSION['Items' . $identifier]->LineItems as $Itm) {

		if (sizeOf($Itm->SerialItems) > 0) {
			$_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyDispatched = 0; //initialise QtyDispatched
			foreach ($Itm->SerialItems as $SerialItem) { //calculate QtyDispatched from bundle quantities
				$_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyDispatched += $SerialItem->BundleQty;
			}
		} else if (isset($_POST[$Itm->LineNumber . '_QtyDispatched'])) {
			if (is_numeric(filter_number_format($_POST[$Itm->LineNumber . '_QtyDispatched'])) and filter_number_format($_POST[$Itm->LineNumber . '_QtyDispatched']) <= ($_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->Quantity - $_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyInv)) {

				$_SESSION['Items' . $identifier]->LineItems[$Itm->LineNumber]->QtyDispatched = round(filter_number_format($_POST[$Itm->LineNumber . '_QtyDispatched']), $Itm->DecimalPlaces);
			}
		}
	} //end foreach lineitem

}

if ($_SESSION['Items' . $identifier]->SpecialInstructions) {
	prnMsg($_SESSION['Items' . $identifier]->SpecialInstructions, 'warn');
}

echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/inventory.png" title="' . _('Pick List Maintenance') . '" alt="" />' . ' ' . _('Pick List: ') . str_pad($_SESSION['ProcessingPick'], 10, '0', STR_PAD_LEFT) . _(' for Order No: ') . $_SESSION['Items' . $identifier]->OrderNo . '</p>';

echo '<div class="toplink">
		<a href="' . $RootPath . '/SelectPickingLists.php">' . _('Back to Pick Lists') . '</a>
	</div>';

echo '<table class="selection">
			<tr>
				<th><img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/customer.png" title="' . _('Customer') . '" alt="" />' . ' ' . _('Customer Code') . ' :<b> ' . $_SESSION['Items' . $identifier]->DebtorNo . '</b></th>
				<th>' . _('Customer Name') . ' :<b> ' . $_SESSION['Items' . $identifier]->CustomerName . '</b></th>
			</tr>
		</table>';

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?identifier=' . urlencode($identifier) . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/***************************************************************
Line Item Display
***************************************************************/
echo '<table width="90%" cellpadding="2" class="selection">
	<tr>
		<th>' . _('Item Code') . '</th>
		<th>' . _('Item Description') . '</th>
		<th>' . _('Ordered') . '</th>
		<th>' . _('Units') . '</th>
		<th>' . _('Already') . '<br />' . _('Sent') . '</th>
		<th>' . _('Qty Picked') . '</th>
	</tr>';

/*show the line items on the order with the quantity being dispatched available for modification */

$j = 0;
foreach ($_SESSION['Items' . $identifier]->LineItems as $LnItm) {
	++$j;

	if (sizeOf($LnItm->SerialItems) > 0) {
		$_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyDispatched = 0; //initialise QtyDispatched
		foreach ($LnItm->SerialItems as $SerialItem) { //calculate QtyDispatched from bundle quantities
			$_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyDispatched += $SerialItem->BundleQty;
		}
	} else if (isset($_POST[$LnItm->LineNumber . '_QtyDispatched'])) {
		if (is_numeric(filter_number_format($_POST[$LnItm->LineNumber . '_QtyDispatched'])) and filter_number_format($_POST[$LnItm->LineNumber . '_QtyDispatched']) <= ($_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->Quantity - $_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyInv)) {

			$_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyDispatched = round(filter_number_format($_POST[$LnItm->LineNumber . '_QtyDispatched']), $LnItm->DecimalPlaces);
		}
	}

	echo '<tr class="striped_row">
		<td>' . $LnItm->StockID . '</td>
		<td title="' . $LnItm->LongDescription . '">' . $LnItm->ItemDescription . '</td>
		<td class="number">' . locale_number_format($LnItm->Quantity, $LnItm->DecimalPlaces) . '</td>
		<td>' . $LnItm->Units . '</td>
		<td class="number">' . locale_number_format($LnItm->QtyInv, $LnItm->DecimalPlaces) . '</td>';

	if ($LnItm->Controlled == 1) {
		if (isset($_POST['ProcessPickList'])) {
			echo '<td class="number">' . locale_number_format($LnItm->QtyDispatched, $LnItm->DecimalPlaces) . '</td>';
		} else {
			echo '<td class="number"><input type="hidden" name="' . $LnItm->LineNumber . '_QtyDispatched"  value="' . $LnItm->QtyDispatched . '" /><a href="' . $RootPath . '/PickingListsControlled.php?identifier=' . $identifier . '&amp;LineNo=' . $LnItm->LineNumber . '">' . locale_number_format($LnItm->QtyDispatched, $LnItm->DecimalPlaces) . '</a></td>';
		}
	} else {
		if (isset($_POST['ProcessPickList'])) {
			echo '<td class="number">' . locale_number_format($LnItm->QtyDispatched, $LnItm->DecimalPlaces) . '</td>';
		} else {
			echo '<td class="number"><input tabindex="' . $j . '" type="text" ' . ($j == 1 ? 'autofocus="autofocus" ' : '') . ' class="number" required="required" title="' . _('Enter the quantity to charge the customer for, that has been dispatched') . '" name="' . $LnItm->LineNumber . '_QtyDispatched" maxlength="12" size="12" value="' . locale_number_format($LnItm->QtyDispatched, $LnItm->DecimalPlaces) . '" /></td>';
		}
	}

	echo '<td class="number">' . locale_number_format($_SESSION['Items' . $identifier]->LineItems[$LnItm->LineNumber]->QtyShipped, $LnItm->DecimalPlaces) . '</td>';

	if ($LnItm->Controlled == 1) {
		if (!isset($_POST['ProcessPickList'])) {
			echo '<td><a href="' . $RootPath . '/PickingListsControlled.php?identifier=' . $identifier . '&amp;LineNo=' . $LnItm->LineNumber . '">';
			if ($LnItm->Serialised == 1) {
				echo _('Enter Serial Numbers');
			} else {
				/*Just batch/roll/lot control */
				echo _('Enter Batch/Roll/Lot #');
			}
			echo '</a></td>';
		}
	}
	echo '</tr>';

	if (mb_strlen($LnItm->Narrative) > 1) {
		$Narrative = str_replace('\r\n', '<br />', $LnItm->Narrative);
		echo '<tr class="striped_row">
				<td colspan="6">' . stripslashes($Narrative) . '</td>
			</tr>';
	}
} //end foreach ($line)

if (!isset($_POST['DispatchDate']) or !is_date($_POST['DispatchDate'])) {
	$DefaultDispatchDate = Date($_SESSION['DefaultDateFormat'], CalcEarliestDispatchDate());
} else {
	$DefaultDispatchDate = $_POST['DispatchDate'];
}

echo '</table>';

if (isset($_POST['ProcessPickList']) and $_POST['ProcessPickList'] != '') {

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
							AND effectiveafter <'" . Date('Y-m-d') . "'
							AND effectiveto >='" . Date('Y-m-d') . "'";

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
			echo '</form>';
			echo '<div class="centre">
					<input type="submit" name="Update" value="' . _('Update') . '" />
				</div>';
			include('includes/footer.php');
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
		include('includes/footer.php');
		exit;
	}

	/*Now need to check that the order details are the same as they were when they were read into the Items array. If they've changed then someone else may have invoiced them */

	$SQL = "SELECT stkcode,
					quantity,
					qtyinvoiced,
					pickreqdetails.orderlineno
				FROM pickreqdetails
				INNER JOIN pickreq
					ON pickreq.prid=pickreqdetails.prid
				INNER JOIN salesorderdetails
					ON salesorderdetails.orderno=pickreq.orderno
					AND salesorderdetails.orderlineno=pickreqdetails.orderlineno
					AND salesorderdetails.completed=0 AND salesorderdetails.quantity-salesorderdetails.qtyinvoiced > 0
				WHERE pickreqdetails.prid = '" . $_SESSION['ProcessingPick'] . "'";

	$Result = DB_query($SQL);

	if (DB_num_rows($Result) != count($_SESSION['Items' . $identifier]->LineItems)) {
		/*there should be the same number of items returned from this query as there are lines on the invoice - if  not 	then someone has already invoiced or credited some lines */

		if ($debug == 1) {
			echo '<br />' . $SQL;
			echo '<br />' . _('Number of rows returned by SQL') . ':' . DB_num_rows($Result);
			echo '<br />' . _('Count of items in the session') . ' ' . count($_SESSION['Items' . $identifier]->LineItems);
		}

		echo '<br />';
		prnMsg(_('This order has been changed or invoiced since this delivery was started to be confirmed') . '. ' . _('Processing halted') . '. ' . _('To enter and confirm this dispatch') . _(' the order must be re-selected and re-read again to update the changes made by the other user'), 'error');

		unset($_SESSION['Items' . $identifier]->LineItems);
		unset($_SESSION['Items' . $identifier]);
		unset($_SESSION['ProcessingPick']);
		include('includes/footer.php');
		exit;
	}

	while ($MyRow = DB_fetch_array($Result)) {
		$TotalQtyInv += $_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->QtyDispatched; //need total qty later to distribute freight equally
		if ($_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->Quantity != $MyRow['quantity'] or $_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->QtyInv != $MyRow['qtyinvoiced']) {

			echo '<br />' . _('Orig order for') . ' ' . $MyRow['orderlineno'] . ' ' . _('has a quantity of') . ' ' . $MyRow['quantity'] . ' ' . _('and an invoiced qty of') . ' ' . $MyRow['qtyinvoiced'] . ' ' . _('the session shows quantity of') . ' ' . $_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->Quantity . ' ' . _('and quantity invoice of') . ' ' . $_SESSION['Items' . $identifier]->LineItems[$MyRow['orderlineno']]->QtyInv;

			prnMsg(_('This order has been changed or invoiced since this delivery was started to be confirmed') . ' ' . _('Processing halted.') . ' ' . _('To enter and confirm this dispatch, it must be re-selected and re-read again to update the changes made by the other user'), 'error');

			echo '<div class="centre"><a href="' . $RootPath . '/SelectPickiingLists.php">' . _('Select a pick list to maintain') . '</a></div>';

			unset($_SESSION['Items' . $identifier]->LineItems);
			unset($_SESSION['Items' . $identifier]);
			unset($_SESSION['ProcessingPick']);
			include('includes/footer.php');
			exit;
		}
	}
	/*loop through all line items of the order to ensure none have been invoiced since started looking at this order*/

	DB_free_result($Result);

	// *************************************************************************
	//   S T A R T   O F   S Q L   P R O C E S S I N G
	// *************************************************************************

	/*Start an SQL transaction */

	DB_Txn_Begin();

	$DefaultDispatchDate = FormatDateForSQL($DefaultDispatchDate);

/*remove existing pickserialdetails records*/
	$SQL = "DELETE pickserialdetails
				FROM pickserialdetails
				INNER JOIN pickreqdetails
					ON pickreqdetails.detailno=pickserialdetails.detailno
				WHERE prid='" . $_SESSION['ProcessingPick'] . "'";
	$ErrMsg = _('CRITICAL ERROR') . ' ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The pickserialdetails could not be deleted');
	$DbgMsg = _('The following SQL to delete them was used');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

	/*Update order header for invoice charged on */
	$ExtraUpdSQL = '';
	if ($_POST['Status'] == 'Shipped') {
		$ExtraUpdSQL = ",shippedby='" . $_SESSION['UserID'] . "',
					shipdate='" . $DefaultDispatchDate . "'";
		$ExtraLineSQL = ",shipqty=qtypicked";
	} else {
		$ExtraUpdSQL = ",shippedby='',
					shipdate='0000-00-00'";
		$ExtraLineSQL = ",shipqty=0";
	}

	if ($_POST['Status'] == 'Cancelled') {
		$ExtraUpdSQL .= ",closed='1'";
	}

	$SQL = "UPDATE salesorders, pickreq
			SET internalcomment = '" . $_POST['InternalComments'] . "',
				pickreq.comments= '" . $_POST['Comments'] . "',
				status = '" . $_POST['Status'] . "',
				consignment = '" . $_POST['Consignment'] . "',
				packages = '" . $_POST['Packages'] . "'
				" . $ExtraUpdSQL . "
			WHERE prid= '" . $_SESSION['ProcessingPick'] . "'
			AND salesorders.orderno=pickreq.orderno";

	$ErrMsg = _('CRITICAL ERROR') . ' ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales order header could not be updated with the internal comments');
	$DbgMsg = _('The following SQL to update the order was used');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

	foreach ($_SESSION['Items' . $identifier]->LineItems as $OrderLine) {
		$LineItemsSQL = "SELECT pickreqdetails.detailno
						FROM pickreqdetails INNER JOIN pickreq ON pickreq.prid=pickreqdetails.prid
						INNER JOIN salesorderdetails
							ON salesorderdetails.orderno = pickreq.orderno
							AND salesorderdetails.orderlineno=pickreqdetails.orderlineno
						WHERE pickreqdetails.prid ='" . $_SESSION['ProcessingPick'] . "'
						AND salesorderdetails.orderlineno='" . $OrderLine->LineNumber . "'";

		$ErrMsg = _('The line items of the pick list cannot be retrieved because');
		$DbgMsg = _('The SQL that failed was');
		$LineItemsResult = DB_query($LineItemsSQL, $ErrMsg, $DbgMsg);
		$MyLine = DB_fetch_array($LineItemsResult);
		$DetailNo = $MyLine['detailno'];
		$SQL = "UPDATE pickreqdetails
				SET qtypicked='" . $OrderLine->QtyDispatched . "'
				" . $ExtraLineSQL . "
				WHERE detailno='" . $DetailNo . "'";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The pickreqdetail record could not be inserted because');
		$DbgMsg = _('The following SQL to insert the pickreqdetail records was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

		if ($OrderLine->Controlled == 1) {
			foreach($OrderLine->SerialItems as $Item) {
				/* now insert the serial records */
				$SQL = "INSERT INTO pickserialdetails (detailno,
													stockid,
													serialno,
													moveqty)
								VALUES ('" . $DetailNo . "',
										'" . $OrderLine->StockID . "',
										'" . $Item->BundleRef . "',
										'" . $Item->BundleQty . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
				$DbgMsg = _('The following SQL to insert the serial stock movement records was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			}/* foreach controlled item in the serialitems array */
		} /*end if the orderline is a controlled item */

	}
	/*end of OrderLine loop */

	DB_Txn_Commit();
	// *************************************************************************
	//   E N D   O F  S Q L   P R O C E S S I N G
	// *************************************************************************
	echo prnMsg(_('PickList ') . ' ' . $_SESSION['ProcessingPick'] . ' ' . _('processed'), 'success');

	if ($_SESSION['PackNoteFormat'] == 1) {
		/*Laser printed A4 default */
		$PrintDispatchNote = $RootPath . '/PrintCustOrder_generic.php?TransNo=' . $_SESSION['Items' . $identifier]->OrderNo;
	} else {
		/*pre-printed stationery default */
		$PrintDispatchNote = $RootPath . '/PrintCustOrder.php?TransNo=' . $_SESSION['Items' . $identifier]->OrderNo;
	}

	$PrintLabels = $RootPath . '/PDFShipLabel.php?Type=Sales&ORD=' . $_SESSION['Items' . $identifier]->OrderNo;
	unset($_SESSION['Items' . $identifier]->LineItems);
	unset($_SESSION['Items' . $identifier]);
	unset($_SESSION['ProcessingPick']);

	echo '<br /><div class="centre">';

	echo '<a target="_blank" href="' . $PrintDispatchNote . '">' . _('Print Packing Slip') . '<img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/pdf.png" title="' . _('Click for PDF') . '" alt="" /></a><br />
		<a target="_blank" href="' . $PrintLabels . '">' . _('Print Customer Labels') . '</a><br /><br />';

	echo '<a href="' . $RootPath . '/SelectPickingLists.php">' . _('Select another pick list for processing') . '</a><br /><br />';
	/*end of process invoice */
}
else {
	/*Process Invoice not set so allow input of invoice data */

	if (!isset($_POST['Consignment'])) {
		$_POST['Consignment'] = $_SESSION['Items' . $identifier]->Consignment;
	}
	if (!isset($_POST['Packages'])) {
		$_POST['Packages'] = $_SESSION['Items' . $identifier]->Packages;
	}
	if (!isset($_POST['InvoiceText'])) {
		$_POST['InvoiceText'] = '';
	}
	if (!isset($_POST['Status'])) {
		$_POST['Status'] = $_SESSION['Items' . $identifier]->Status;
	}

	++$j;

	echo '<table class="selection"
		<tr>
			<td>' . _('Pick List Status') . ':</td>
			<td><select name="Status">';

	if (($_SESSION['Items' . $identifier]->Status != 'Shipped') or (in_array($ARSecurity, $_SESSION['AllowedPageSecurityTokens']))) {
		//only allow A/R to change status on an already shipped Pick, we expect to invoice, we need A/R intervention to prevent ship, cancel, no invoice, lost money
		if ($_POST['Status'] == 'Picked') {
			echo '<option selected="selected" value="Picked">' . _('Picked') . '</option>';
		} else {
			echo '<option value="Picked">' . _('Picked') . '</option>';
		}
	}

	if ($_POST['Status'] == 'Shipped') {
		echo '<option selected="selected" value="Shipped">' . _('Shipped') . '</option>';
	} else {
		echo '<option value="Shipped">' . _('Shipped') . '</option>';
	}

	if (($_SESSION['Items' . $identifier]->Status != 'Shipped') or (in_array($ARSecurity, $_SESSION['AllowedPageSecurityTokens']))) {
		//only allow A/R to cancel an already shipped Pick, we expect to invoice, we need A/R intervention to prevent ship, cancel, no invoice, lost money
		if ($_POST['Status'] == 'Cancelled') {
			echo '<option selected="selected" value="Cancelled">' . _('Cancelled') . '</option>';
		} else {
			echo '<option value="Cancelled">' . _('Cancelled') . '</option>';
		}
	}

	echo '</select></td></tr>';
	echo '<tr>
			<td>' . _('Consignment Note Ref') . ':</td>
			<td><input tabindex="' . $j . '" type="text" data-type="no-illegal-chars" title="' . _('Enter the consignment note reference to enable tracking of the delivery in the event of customer proof of delivery issues') . '" maxlength="15" size="15" name="Consignment" value="' . $_POST['Consignment'] . '" /></td>
		</tr>';
	++$j;

	echo '<tr>
			<td>' . _('No Of Packages in Delivery') . ':</td>
			<td><input tabindex="' . $j . '" type="number" maxlength="6" size="6" class="integer" name="Packages" value="' . $_POST['Packages'] . '" /></td>
		</tr>';

	++$j;
	echo '<tr>
			<td>' . _('Pick List Comments') . ':</td>
			<td><textarea tabindex="' . $j . '" name="Comments" pattern=".{0,20}" cols="31" rows="5">' . reverse_escape($_SESSION['Items' . $identifier]->Comments) . '</textarea></td>
		</tr>';

	++$j;
	echo '<tr>
			<td>' . _('Order Internal Comments') . ':</td>
			<td><textarea tabindex="' . $j . '" name="InternalComments" pattern=".{0,20}" cols="31" rows="5">' . reverse_escape($_SESSION['Items' . $identifier]->InternalComments) . '</textarea></td>
		</tr>';

	++$j;
	echo '</table>';

	if (($_SESSION['Items' . $identifier]->Status != 'Shipped') or (in_array($ARSecurity, $_SESSION['AllowedPageSecurityTokens']))) {
		//only allow A/R to change status on an already shipped Pick, we expect to invoice, we need A/R intervention to prevent ship, cancel, no invoice, lost money
		echo '<div class="centre">
				<input type="submit" tabindex="' . $j . '" name="Update" value="' . _('Update') . '" />
				<br />';
		++$j;
		echo '<br />
				<input type="submit" tabindex="' . $j . '" name="ProcessPickList" value="' . _('Process Pick List') . '" />
			</div>
			<input type="hidden" name="ShipVia" value="' . $_SESSION['Items' . $identifier]->ShipVia . '" />';
	}
}
echo '</form>';

include('includes/footer.php');
?>

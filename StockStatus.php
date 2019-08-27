<?php

$PricesSecurity = 12;//don't show pricing info unless security token 12 available to user
include('includes/session.php');

$Title = _('Stock Status');

include('includes/header.php');
include ('includes/SQL_CommonFunctions.inc');

if (isset($_GET['StockID'])){
	$StockID = trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
	$StockID = trim(mb_strtoupper($_POST['StockID']));
} else {
	$StockID = '';
}

if (isset($_POST['UpdateBinLocations'])){
	foreach ($_POST as $PostVariableName => $Bin) {
		if (mb_substr($PostVariableName,0,11) == 'BinLocation') {
			$sql = "UPDATE locstock SET bin='" . strtoupper($Bin) . "' WHERE loccode='" . mb_substr($PostVariableName,11) . "' AND stockid='" . $StockID . "'";
			$result = DB_query($sql);
		}
	}
}
$result = DB_query("SELECT description,
						   units,
						   mbflag,
						   decimalplaces,
						   serialised,
						   controlled
					FROM stockmaster
					WHERE stockid='".$StockID."'",
					_('Could not retrieve the requested item'),
					_('The SQL used to retrieve the items was'));

$myrow = DB_fetch_array($result);

$DecimalPlaces = $myrow['decimalplaces'];
$Serialised = $myrow['serialised'];
$Controlled = $myrow['controlled'];

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Inventory') .
	'" alt="" /><b>' . ' ' . $StockID . ' - ' . $myrow['description'] . ' : ' . _('in units of') . ' : ' . $myrow['units'] . '</b></p>';

$Its_A_KitSet_Assembly_Or_Dummy =False;
if ($myrow[2]=='K'){
	$Its_A_KitSet_Assembly_Or_Dummy =True;
	prnMsg( _('This is a kitset part and cannot have a stock holding') . ', ' . _('only the total quantity on outstanding sales orders is shown'),'info');
} elseif ($myrow[2]=='A'){
	$Its_A_KitSet_Assembly_Or_Dummy =True;
	prnMsg(_('This is an assembly part and cannot have a stock holding') . ', ' . _('only the total quantity on outstanding sales orders is shown'),'info');
} elseif ($myrow[2]=='D'){
	$Its_A_KitSet_Assembly_Or_Dummy =True;
	prnMsg( _('This is an dummy part and cannot have a stock holding') . ', ' . _('only the total quantity on outstanding sales orders is shown'),'info');
}

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<div class="centre"><input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo _('Stock Code') . ':<input type="text" data-type="no-illegal-chars" title ="'._('Input the stock code to inquire upon. Only alpha-numeric characters are allowed in stock codes with no spaces punctuation or special characters. Underscore or dashes are allowed.').'" placeholder="'._('Alpha-numeric only').'" required="required" name="StockID" size="21" value="' . $StockID . '" maxlength="20" />';

echo ' <input type="submit" name="ShowStatus" value="' . _('Show Stock Status') . '" />';

$sql = "SELECT locstock.loccode,
				locations.locationname,
				locstock.quantity,
				locstock.reorderlevel,
				locstock.bin,
				locations.managed,
				canupd
		FROM locstock INNER JOIN locations
		ON locstock.loccode=locations.loccode
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
		WHERE locstock.stockid = '" . $StockID . "'
		ORDER BY locations.locationname";

$ErrMsg = _('The stock held at each location cannot be retrieved because');
$DbgMsg = _('The SQL that was used to update the stock item and failed was');
$LocStockResult = DB_query($sql, $ErrMsg, $DbgMsg);

echo '<br />
		<table class="selection"><tbody>';
	echo '<thead>';

if ($Its_A_KitSet_Assembly_Or_Dummy == True){
	echo '<tr>
						<th class="ascending">' . _('Location') . '</th>
						<th class="ascending">' . _('Demand') . '</th>
					</tr>';
} else {
	echo '<tr>
						<th class="ascending">' . _('Location') . '</th>
						<th class="ascending">' . _('Bin Location') . '</th>
						<th class="ascending">' . _('Quantity On Hand') . '</th>
						<th class="ascending">' . _('Re-Order Level') . '</th>
						<th class="ascending">' . _('Demand') . '</th>
						<th class="ascending">' . _('In Transit') . '</th>
						<th class="ascending">' . _('Available') . '</th>
						<th class="ascending">' . _('On Order') . '</th>
					</tr>';
}

echo '</thead>
		<tbody>';

while ($myrow=DB_fetch_array($LocStockResult)) {

	$sql = "SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
			FROM salesorderdetails INNER JOIN salesorders
			ON salesorders.orderno = salesorderdetails.orderno
			WHERE salesorders.fromstkloc='" . $myrow['loccode'] . "'
			AND salesorderdetails.completed=0
			AND salesorders.quotation=0
			AND salesorderdetails.stkcode='" . $StockID . "'";

	$ErrMsg = _('The demand for this product from') . ' ' . $myrow['loccode'] . ' ' . _('cannot be retrieved because');
	$DemandResult = DB_query($sql,$ErrMsg,$DbgMsg);

	if (DB_num_rows($DemandResult)==1){
	  $DemandRow = DB_fetch_row($DemandResult);
	  $DemandQty =  $DemandRow[0];
	} else {
	  $DemandQty =0;
	}

	//Also need to add in the demand as a component of an assembly items if this items has any assembly parents.
	$sql = "SELECT SUM((salesorderdetails.quantity-salesorderdetails.qtyinvoiced)*bom.quantity) AS dem
			FROM salesorderdetails INNER JOIN salesorders
			ON salesorders.orderno = salesorderdetails.orderno
			INNER JOIN bom
			ON salesorderdetails.stkcode=bom.parent
			INNER JOIN stockmaster
			ON stockmaster.stockid=bom.parent
			WHERE salesorders.fromstkloc='" . $myrow['loccode'] . "'
			AND salesorderdetails.quantity-salesorderdetails.qtyinvoiced > 0
			AND bom.component='" . $StockID . "'
			AND stockmaster.mbflag='A'
			AND salesorders.quotation=0";

	$ErrMsg = _('The demand for this product from') . ' ' . $myrow['loccode'] . ' ' . _('cannot be retrieved because');
	$DemandResult = DB_query($sql,$ErrMsg,$DbgMsg);

	if (DB_num_rows($DemandResult)==1){
		$DemandRow = DB_fetch_row($DemandResult);
		$DemandQty += $DemandRow[0];
	}

	//Also the demand for the item as a component of works orders

	$sql = "SELECT SUM(qtypu*(woitems.qtyreqd - woitems.qtyrecd)) AS woqtydemo
			FROM woitems INNER JOIN worequirements
			ON woitems.stockid=worequirements.parentstockid
			INNER JOIN workorders
			ON woitems.wo=workorders.wo
			AND woitems.wo=worequirements.wo
			WHERE workorders.loccode='" . $myrow['loccode'] . "'
			AND worequirements.stockid='" . $StockID . "'
			AND workorders.closed=0";

	$ErrMsg = _('The workorder component demand for this product from') . ' ' . $myrow['loccode'] . ' ' . _('cannot be retrieved because');
	$DemandResult = DB_query($sql,$ErrMsg,$DbgMsg);

	if (DB_num_rows($DemandResult)==1){
		$DemandRow = DB_fetch_row($DemandResult);
		$DemandQty += $DemandRow[0];
	}

	if ($Its_A_KitSet_Assembly_Or_Dummy == False){
		// Get the QOO due to Purchase orders for all locations. Function defined in SQL_CommonFunctions.inc
		$QOO = GetQuantityOnOrderDueToPurchaseOrders($StockID, $myrow['loccode']);
		// Get the QOO dues to Work Orders for all locations. Function defined in SQL_CommonFunctions.inc
		$QOO += GetQuantityOnOrderDueToWorkOrders($StockID, $myrow['loccode']);

		$InTransitSQL="SELECT SUM(shipqty-recqty) as intransit
						FROM loctransfers
						WHERE stockid='" . $StockID . "'
							AND shiploc='".$myrow['loccode']."'";
		$InTransitResult=DB_query($InTransitSQL);
		$InTransitRow=DB_fetch_array($InTransitResult);
		if ($InTransitRow['intransit']!='') {
			$InTransitQuantityOut=-$InTransitRow['intransit'];
		} else {
			$InTransitQuantityOut=0;
		}

		$InTransitSQL="SELECT SUM(-shipqty+recqty) as intransit
						FROM loctransfers
						WHERE stockid='" . $StockID . "'
							AND recloc='".$myrow['loccode']."'";
		$InTransitResult=DB_query($InTransitSQL);
		$InTransitRow=DB_fetch_array($InTransitResult);
		if ($InTransitRow['intransit']!='') {
			$InTransitQuantityIn=-$InTransitRow['intransit'];
		} else {
			$InTransitQuantityIn=0;
		}

		if (($InTransitQuantityIn+$InTransitQuantityOut) < 0) {
			$Available = $myrow['quantity'] - $DemandQty + ($InTransitQuantityIn+$InTransitQuantityOut);
		} else {
			$Available = $myrow['quantity'] - $DemandQty;
		}

		echo '<tr class="striped_row">';
		if ($myrow['canupd']==1) {
			echo '<td>' . $myrow['locationname'] . '</td>
				<td><input type="text" name="BinLocation' . $myrow['loccode'] . '" value="' . $myrow['bin'] . '" maxlength="10" size="11" onchange="ReloadForm(UpdateBinLocations)"/></td>';
		} else {
			echo '<td>' . $myrow['locationname'] . '</td>
				<td> ' . $myrow['bin'] . '</td>';
		}

		printf('<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>',
				locale_number_format($myrow['quantity'], $DecimalPlaces),
				locale_number_format($myrow['reorderlevel'], $DecimalPlaces),
				locale_number_format($DemandQty, $DecimalPlaces),
				locale_number_format($InTransitQuantityIn+$InTransitQuantityOut, $DecimalPlaces),
				locale_number_format($Available, $DecimalPlaces),
				locale_number_format($QOO, $DecimalPlaces)
				);

		if ($Serialised ==1){ /*The line is a serialised item*/

			echo '<td><a target="_blank" href="' . $RootPath . '/StockSerialItems.php?Serialised=Yes&amp;Location=' . $myrow['loccode'] . '&amp;StockID=' .$StockID . '">' . _('Serial Numbers') . '</tr>';
		} elseif ($Controlled==1){
			echo '<td><a target="_blank" href="' . $RootPath . '/StockSerialItems.php?Location=' . $myrow['loccode'] . '&amp;StockID=' .$StockID . '">' . _('Batches') . '</a></td></tr>';
		}else{
			echo '</tr>';
		}

	} else {
	/* It must be a dummy, assembly or kitset part */

		printf('<tr class="striped_row">
				<td>%s</td>
				<td class="number">%s</td>
				</tr>',
				$myrow['locationname'],
				locale_number_format($DemandQty, $DecimalPlaces));
	}
//end of page full new headings if
}
//end of while loop
echo '</tbody>
	<tr>
		<td></td>
		<td><input type="submit" name="UpdateBinLocations" value="' . _('Update Bins') . '" /></td>
	</tr>
	</table>';

if (isset($_GET['DebtorNo'])){
	$DebtorNo = trim(mb_strtoupper($_GET['DebtorNo']));
} elseif (isset($_POST['DebtorNo'])){
	$DebtorNo = trim(mb_strtoupper($_POST['DebtorNo']));
} elseif (isset($_SESSION['CustomerID'])){
	$DebtorNo=$_SESSION['CustomerID'];
}

if ($DebtorNo) { /* display recent pricing history for this debtor and this stock item */

	$sql = "SELECT stockmoves.trandate,
				stockmoves.qty,
				stockmoves.price,
				stockmoves.discountpercent
			FROM stockmoves
			WHERE stockmoves.debtorno='" . $DebtorNo . "'
				AND stockmoves.type=10
				AND stockmoves.stockid = '" . $StockID . "'
				AND stockmoves.hidemovt=0
			ORDER BY stockmoves.trandate DESC";

	/* only show pricing history for sales invoices - type=10 */

	$ErrMsg = _('The stock movements for the selected criteria could not be retrieved because') . ' - ';
	$DbgMsg = _('The SQL that failed was');

	$MovtsResult = DB_query($sql, $ErrMsg, $DbgMsg);

	$k=1;
	while ($myrow=DB_fetch_array($MovtsResult)) {
	  if ($LastPrice != $myrow['price']
			OR $LastDiscount != $myrow['discount']) { /* consolidate price history for records with same price/discount */
	    if (isset($qty)) {
	    	$DateRange=ConvertSQLDate($FromDate);
	    	if ($FromDate != $ToDate) {
	        	$DateRange .= ' - ' . ConvertSQLDate($ToDate);
	     	}
	    	$PriceHistory[] = array($DateRange, $qty, $LastPrice, $LastDiscount);
	    	$k++;
	    	if ($k > 9) {
                  break; /* 10 price records is enough to display */
                }
	    	if ($myrow['trandate'] < FormatDateForSQL(DateAdd(date($_SESSION['DefaultDateFormat']),'y', -1))) {
	    	  break; /* stop displaying price history more than a year old once we have at least one  to display */
   	        }
	    }
	    $LastPrice = $myrow['price'];
	    $LastDiscount = $myrow['discountpercent'];
	    $ToDate = $myrow['trandate'];
	    $qty = 0;
	  }
	  $qty += $myrow['qty'];
	  $FromDate = $myrow['trandate'];
	} //end of while loop

	if (isset($qty)) {
		$DateRange = ConvertSQLDate($FromDate);
		if ($FromDate != $ToDate) {
	   		$DateRange .= ' - '.ConvertSQLDate($ToDate);
		}
		$PriceHistory[] = array($DateRange, $qty, $LastPrice, $LastDiscount);
	}

	if (isset($PriceHistory)) {
	  echo '<br />
			<table class="selection">
			<thead>
			<tr>
				<th colspan="4"><font color="navy" size="2">' . _('Pricing history for sales of') . ' ' . $StockID . ' ' . _('to') . ' ' . $DebtorNo . '</font></th>
				</tr>
				<tr>
						<th class="ascending">' . _('Date Range') . '</th>
						<th class="ascending">' . _('Quantity') . '</th>
						<th class="ascending">' . _('Price') . '</th>
						<th class="ascending">' . _('Discount') . '</th>
				</tr>
			</thead>
			<tbody>';

	  foreach($PriceHistory as $PreviousPrice) {

		printf('<tr class="striped_row">
				<td>%s</td>
					<td class="number">%s</td>
					<td class="number">%s</td>
					<td class="number">%s%%</td>
					</tr>',
					$PreviousPrice[0],
					locale_number_format($PreviousPrice[1],$DecimalPlaces),
					locale_number_format($PreviousPrice[2],$_SESSION['CompanyRecord']['decimalplaces']),
					locale_number_format($PreviousPrice[3]*100,2));
		} // end foreach
	 echo '</tbody></table>';
	 }
	else {
	  echo '<p>' . _('No history of sales of') . ' ' . $StockID . ' ' . _('to') . ' ' . $DebtorNo;
	}
}//end of displaying price history for a debtor

echo '<br /><a href="' . $RootPath . '/StockMovements.php?StockID=' . $StockID . '">' . _('Show Movements') . '</a>
	<br /><a href="' . $RootPath . '/StockUsage.php?StockID=' . $StockID . '">' . _('Show Usage') . '</a>
	<br /><a href="' . $RootPath . '/SelectSalesOrder.php?SelectedStockItem=' . $StockID . '">' . _('Search Outstanding Sales Orders') . '</a>
	<br /><a href="' . $RootPath . '/SelectCompletedOrder.php?SelectedStockItem=' . $StockID . '">' . _('Search Completed Sales Orders') . '</a>';
if ($Its_A_KitSet_Assembly_Or_Dummy ==False){
	echo '<br /><a href="' . $RootPath . '/PO_SelectOSPurchOrder.php?SelectedStockItem=' . $StockID . '">' . _('Search Outstanding Purchase Orders') . '</a>';
}

echo '</div></form>';
include('includes/footer.php');

?>

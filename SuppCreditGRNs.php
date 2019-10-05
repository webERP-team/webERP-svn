<?php


/*The supplier transaction uses the SuppTrans class to hold the information about the credit note
the SuppTrans class contains an array of GRNs objects - containing details of GRNs for invoicing and also
an array of GLCodes objects - only used if the AP - GL link is effective */


include('includes/DefineSuppTransClass.php');
/* Session started in header.php for password checking and authorisation level check */
include('includes/session.php');

$Title = _('Enter Supplier Credit Note Against Goods Received');

include('includes/header.php');

echo '<p class="page_title_text">
		<img src="'.$RootPath.'/css/'.$Theme.'/images/magnifier.png" title="' . _('Dispatch') . '" alt="" />' . ' ' . $Title . '
	</p>';

if (!isset($_SESSION['SuppTrans'])){
	prnMsg(_('To enter a supplier transactions the supplier must first be selected from the supplier selection screen') . ', ' . _('then the link to enter a supplier credit note must be clicked on'),'info');
	echo '<br />
		<a href="' . $RootPath . '/SelectSupplier.php">' . _('Select A Supplier to Enter a Transaction For') . '</a>';
	include('includes/footer.php');
	exit;
	/*It all stops here if there aint no supplier selected and credit note initiated ie $_SESSION['SuppTrans'] started off*/
}

/*If the user hit the Add to Credit Note button then process this first before showing all GRNs on the credit note otherwise it wouldnt show the latest addition*/

if (isset($_POST['AddGRNToTrans'])){

	$InputError=False;

	$Complete = False;
        // Validate Credit Quantity to prevent from credit quantity more than quantity invoiced
	if (!is_numeric(filter_number_format($_POST['This_QuantityCredited']))
		or ($_POST['Prev_QuantityInv'] - filter_number_format($_POST['This_QuantityCredited']))<0){

		$InputError = True;
		prnMsg(_('The credit quantity is not numeric or the quantity to credit is more that quantity invoiced') . '. ' . _('The goods received cannot be credited by this quantity'),'error');
		}

	if (!is_numeric(filter_number_format($_POST['ChgPrice']))
		or filter_number_format($_POST['ChgPrice'])<0){

		$InputError = True;
		prnMsg(_('The price charged in the suppliers currency is either not numeric or negative') . '. ' . _('The goods received cannot be credited at this price'),'error');
	}

	if ($InputError==False){

		$_SESSION['SuppTrans']->Add_GRN_To_Trans($_POST['GRNNumber'],
												$_POST['PODetailItem'],
												$_POST['ItemCode'],
												$_POST['ItemDescription'],
												$_POST['QtyRecd'],
												$_POST['Prev_QuantityInv'],
												filter_number_format($_POST['This_QuantityCredited']),
												$_POST['OrderPrice'],
												filter_number_format($_POST['ChgPrice']),
												$Complete,
												$_POST['StdCostUnit'],
												$_POST['ShiptRef'],
												$_POST['JobRef'],
												$_POST['GLCode'],
												$_POST['PONo'],
												$_POST['AssetID'],
												0,
												$_POST['DecimalPlaces'],
												$_POST['GRNBatchNo'],
												$_SESSION['SuppTrans']->SuppReference);
	}
}

if (isset($_GET['Delete'])){

	$_SESSION['SuppTrans']->Remove_GRN_From_Trans($_GET['Delete']);

}

/*Show all the selected GRNs so far from the SESSION['SuppTrans']->GRNs array */

echo '<table class="selection">';
echo '<tr>
		<th colspan="6"><h3>' . _('Credits Against Goods Received Selected') . '</h3></th>
	</tr></table><table class="selection">';
$TableHeader = '<tr>
					<th>' . _('GRN') . '</th>
					<th>' . _('Item Code') . '</th>
					<th>' . _('Description') . '</th>
					<th>' . _('Quantity Credited') . '</th>
					<th>' . _('Price Credited in') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
					<th>' . _('Line Value in') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
				</tr>';

echo $TableHeader;

$TotalValueCharged=0;
$i=0;

foreach ($_SESSION['SuppTrans']->GRNs as $EnteredGRN){
    if ($EnteredGRN->ChgPrice > 1) {
        $DisplayPrice = locale_number_format($EnteredGRN->ChgPrice,$_SESSION['SuppTrans']->CurrDecimalPlaces);
    } else {
        $DisplayPrice = locale_number_format($EnteredGRN->ChgPrice,4);
    }

	echo '<tr>
			<td>' . $EnteredGRN->GRNNo . '</td>
			<td>' . $EnteredGRN->ItemCode . '</td>
			<td>' . $EnteredGRN->ItemDescription . '</td>
			<td class="number">' . locale_number_format($EnteredGRN->This_QuantityInv,$EnteredGRN->DecimalPlaces) . '</td>
			<td class="number">' . $DisplayPrice . '</td>
			<td class="number">' . locale_number_format($EnteredGRN->ChgPrice * $EnteredGRN->This_QuantityInv,$_SESSION['SuppTrans']->CurrDecimalPlaces) . '</td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=' . $EnteredGRN->GRNNo . '">' . _('Delete') . '</a></td>
		</tr>';

	$TotalValueCharged = $TotalValueCharged + ($EnteredGRN->ChgPrice * $EnteredGRN->This_QuantityInv);

	$i++;
	if ($i>15){
		$i=0;
		echo $TableHeader;
	}
}

echo '<tr>
		<td colspan="5" class="number"><h4>' . _('Total Value Credited Against Goods') . ':</h4></td>
		<td class="number"><h4>' . locale_number_format($TotalValueCharged,$_SESSION['SuppTrans']->CurrDecimalPlaces) . '</h4></td>
          </tr>';
echo '</table>
	<br />
	<div class="centre">
		<a href="' . $RootPath . '/SupplierCredit.php?">' . _('Back to Credit Note Entry') . '</a>
	</div>';

/* Now get all the GRNs for this supplier from the database
after the date entered */
if (!isset($_POST['Show_Since'])){
	$_POST['Show_Since'] =  Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m')-2,Date('d'),Date('Y')));
}

$SQL = "SELECT grnno,
			purchorderdetails.orderno,
			purchorderdetails.unitprice,
			purchorderdetails.actprice,
			grns.itemcode,
			grns.deliverydate,
			grns.itemdescription,
			grns.qtyrecd,
			grns.quantityinv,
			purchorderdetails.stdcostunit,
			purchorderdetails.assetid,
			stockmaster.decimalplaces
		FROM grns INNER JOIN purchorderdetails
		ON grns.podetailitem=purchorderdetails.podetailitem
		LEFT JOIN stockmaster
		ON purchorderdetails.itemcode=stockmaster.stockid
		WHERE grns.supplierid ='" . $_SESSION['SuppTrans']->SupplierID . "'
		AND grns.deliverydate >= '" . FormatDateForSQL($_POST['Show_Since']) . "'
		ORDER BY grns.grnno";
$GRNResults = DB_query($SQL);

if (DB_num_rows($GRNResults)==0){
	prnMsg(_('There are no goods received records for') . ' ' . $_SESSION['SuppTrans']->SupplierName . ' ' . _('since') . ' ' . $_POST['Show_Since'] . '<br /> ' . _('To enter a credit against goods received') . ', ' . _('the goods must first be received using the link below to select purchase orders to receive'),'info');
	echo '<br />
	<a href="' . $RootPath . '/PO_SelectOSPurchOrder.php?SupplierID=' . $_SESSION['SuppTrans']->SupplierID . '">' . _('Select Purchase Orders to Receive') . '</a>';
}


/*Set up a table to show the GRNs outstanding for selection */
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
	<div>
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<br />
	<table class="selection">
	<tr>
			<th colspan="10"><h3>' . _('Show Goods Received Since') . ':&nbsp;</h3>
			<input type="text" name="Show_Since" maxlength="11" size="12" class="date" value="' . $_POST['Show_Since'] . '" />
		<input type="submit" name="FindGRNs" value="' . _('Display GRNs') . '" />
		<h3> ' . _('From') . ' ' . $_SESSION['SuppTrans']->SupplierName . '</h3></th>
		</tr>
	</table>';

if (DB_num_rows($GRNResults)>0){
	echo '<table class="selection">
		<thead>
			<tr>
						<th class="ascending">' . _('GRN') . '</th>
						<th class="ascending">' . _('Order') . '</th>
						<th class="ascending">' . _('Item Code') . '</th>
						<th class="ascending">' . _('Description') . '</th>
						<th class="ascending">' . _('Delivered') . '</th>
						<th class="ascending">' . _('Total Qty') . '<br />' . _('Received') . '</th>
						<th class="ascending">' . _('Qty Invoiced') . '</th>
						<th class="ascending">' . _('Qty Yet') . '<br />' . _('invoice') . '</th>
						<th class="ascending">' . _('Price') . '<br />' . $_SESSION['SuppTrans']->CurrCode . '</th>
						<th class="ascending">' . _('Line Value') . '<br />' . _('In') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
			</tr>
		</thead>
		<tbody>';

	while ($myrow=DB_fetch_array($GRNResults)){

		$GRNAlreadyOnCredit = False;

		foreach ($_SESSION['SuppTrans']->GRNs as $EnteredGRN){
			if ($EnteredGRN->GRNNo == $myrow['grnno']) {
				$GRNAlreadyOnCredit = True;
			}
		}
		if ($GRNAlreadyOnCredit == False){

			if ($myrow['actprice']<>0){
				$Price = $myrow['actprice'];
			} else {
				$Price = $myrow['unitprice'];
			}
			if ($myrow['decimalplaces']==''){
				$myrow['decimalplaces'] =2;
			}

			if ($Price > 1) {
                $DisplayPrice = locale_number_format($Price,$_SESSION['SuppTrans']->CurrDecimalPlaces);
            } else {
                $DisplayPrice = locale_number_format($Price,4);
            }

			echo '<tr>
					<td><input type="submit" name="GRNNo" value="' . $myrow['grnno'] . '" /></td>
					<td>' . $myrow['orderno'] . '</td>
					<td>' . $myrow['itemcode'] . '</td>
					<td>' . $myrow['itemdescription'] . '</td>
					<td>' . ConvertSQLDate($myrow['deliverydate']) . '</td>
					<td class="number">' . locale_number_format($myrow['qtyrecd'],$myrow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($myrow['quantityinv'],$myrow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($myrow['qtyrecd'] - $myrow['quantityinv'],$myrow['decimalplaces']) . '</td>
					<td class="number">' . $DisplayPrice . '</td>
					<td class="number">' . locale_number_format($Price*($myrow['qtyrecd'] - $myrow['quantityinv']),$_SESSION['SuppTrans']->CurrDecimalPlaces) . '</td>
	              	</tr>';
	}
	} // end loop.

	echo '</tbody></table>';

	if (isset($_POST['GRNNo']) AND $_POST['GRNNo']!=''){

		$SQL = "SELECT grnno,
						grns.grnbatch,
						grns.podetailitem,
						purchorderdetails.orderno,
						purchorderdetails.unitprice,
						purchorderdetails.actprice,
						purchorderdetails.glcode,
						grns.itemcode,
						grns.deliverydate,
						grns.itemdescription,
						grns.quantityinv,
						grns.qtyrecd,
						grns.qtyrecd - grns.quantityinv
						AS qtyostdg,
						purchorderdetails.stdcostunit,
						purchorderdetails.shiptref,
						purchorderdetails.jobref,
						shipments.closed,
						purchorderdetails.assetid,
						stockmaster.decimalplaces
				FROM grns INNER JOIN purchorderdetails
				ON grns.podetailitem=purchorderdetails.podetailitem
				LEFT JOIN shipments ON purchorderdetails.shiptref=shipments.shiptref
				LEFT JOIN stockmaster ON purchorderdetails.itemcode=stockmaster.stockid
				WHERE grns.grnno='" .$_POST['GRNNo'] . "'";

		$GRNEntryResult = DB_query($SQL);
		$myrow = DB_fetch_array($GRNEntryResult);

		echo '<br />
			<table class="selection">';
		echo '<tr>
				<th colspan="6"><h3>' . _('GRN Selected For Adding To A Suppliers Credit Note') . '</h3></th>
			</tr>';
		echo '<tr>
				<th>' . _('GRN') . '</th>
				<th>' . _('Item') . '</th>
				<th>' . _('Quantity') . '<br />' . _('Outstanding') . '</th>
				<th>' . _('Quantity') . '<br />' . _('credited') . '</th>
				<th>' . _('Supplier') . '<br />' . _('Price') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
				<th>' . _('Credit') . '<br />' . _('Price') . ' ' . $_SESSION['SuppTrans']->CurrCode . '</th>
			</tr>';
		if ($myrow['actprice']<>0){
			$Price = $myrow['actprice'];
		} else {
			$Price = $myrow['unitprice'];
		}
		if ($myrow['decimalplaces']==''){
			$myrow['decimalplaces'] =2;
		}
        if ($Price > 1) {
            $DisplayPrice = locale_number_format($Price,$_SESSION['SuppTrans']->CurrDecimalPlaces);
        } else {
            $DisplayPrice = locale_number_format($Price,4);
        }
		echo '<tr>
				<td>' . $_POST['GRNNo'] . '</td>
				<td>' . $myrow['itemcode'] . ' ' . $myrow['itemdescription'] . '</td>
				<td class="number">' . locale_number_format($myrow['qtyostdg'],$myrow['decimalplaces']) . '</td>
				<td><input type="text" class="number" name="This_QuantityCredited" value="' . locale_number_format($myrow['qtyostdg'],$myrow['decimalplaces']) . '" size="11" maxlength="10" /></td>
				<td class="number">' . $DisplayPrice . '</td>
				<td><input type="text" class="number" name="ChgPrice" value="' . locale_number_format($Price,$_SESSION['SuppTrans']->CurrDecimalPlaces) . '" size="11" maxlength="10" /></td>
			</tr>
			</table>';

		if ($myrow['closed']==1){ /*Shipment is closed so pre-empt problems later by warning the user - need to modify the order first */
			echo '<input type="hidden" name="ShiptRef" value="" />';
			prnMsg(_('Unfortunately the shipment that this purchase order line item was allocated to has been closed') . ' - ' . _('if you add this item to the transaction then no shipments will not be updated') . '. ' . _('If you wish to allocate the order line item to a different shipment the order must be modified first'),'error');
		} else {
			echo '<input type="hidden" name="ShiptRef" value="' . $myrow['shiptref'] . '" />';
		}

		echo '<br />
			<div class="centre">
				<input type="submit" name="AddGRNToTrans" value="' . _('Add to Credit Note') . '" />
			</div>';

		echo '<input type="hidden" name="GRNNumber" value="' . $_POST['GRNNo'] . '" />';
		echo '<input type="hidden" name="ItemCode" value="' . $myrow['itemcode'] . '" />';
		echo '<input type="hidden" name="ItemDescription" value="' . $myrow['itemdescription'] . '" />';
		echo '<input type="hidden" name="QtyRecd" value="' . $myrow['qtyrecd'] . '" />';
		echo '<input type="hidden" name="Prev_QuantityInv" value="' . $myrow['quantityinv'] . '" />';
		echo '<input type="hidden" name="OrderPrice" value="' . $myrow['unitprice'] . '" />';
		echo '<input type="hidden" name="StdCostUnit" value="' . $myrow['stdcostunit'] . '" />';

		echo '<input type="hidden" name="JobRef" value="' . $myrow['jobref'] . '" />';
		echo '<input type="hidden" name="GLCode" value="' . $myrow['glcode'] . '" />';
		echo '<input type="hidden" name="PODetailItem" value="' . $myrow['podetailitem'] . '" />';
		echo '<input type="hidden" name="PONo" value="' . $myrow['orderno'] . '" />';
		echo '<input type="hidden" name="AssetID" value="' . $myrow['assetid'] . '" />';
		echo '<input type="hidden" name="DecimalPlaces" value="' . $myrow['decimalplaces'] . '" />';
		echo '<input type="hidden" name="GRNBatchNo" value="' . $myrow['grnbatch'] . '" />';
	}
} //end if there were GRNs to select

echo '</div>
      </form>';
include('includes/footer.php');
?>

<?php


include('includes/DefineShiptClass.php');
include('includes/session.php');
$Title = _('Shipments');
include('includes/header.php');

include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['NewShipment']) and $_GET['NewShipment']=='Yes'){
	unset($_SESSION['Shipment']->LineItems);
	unset($_SESSION['Shipment']);
}

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/magnifier.png" title="' . _('Search') .
	'" alt="" />' . ' ' . $Title . '</p>';

if (!isset($_SESSION['SupplierID']) AND !isset($_SESSION['Shipment']) AND !isset($_GET['SelectedShipment'])){
	prnMsg( _('To set up a shipment') . ', ' . _('the supplier must first be selected from the Select Supplier page'), 'error');
        echo '<table class="selection">
                <tr><td class="menu_group_item">
                <li><a href="'. $RootPath . '/SelectSupplier.php">' . _('Select the Supplier') . '</a></li>
                </td></tr></table></div>';
        include('includes/footer.php');
        exit;
}

if (isset($_GET['SelectedShipment'])){

	if (isset($_SESSION['Shipment'])){
              unset ($_SESSION['Shipment']->LineItems);
              unset ($_SESSION['Shipment']);
	}

       $_SESSION['Shipment'] = new Shipment;

/*read in all the guff from the selected shipment into the Shipment Class variable - the class code is included in the main script before this script is included  */

       $ShipmentHeaderSQL = "SELECT shipments.supplierid,
				       				suppliers.suppname,
								shipments.eta,
								suppliers.currcode,
								shipments.vessel,
								shipments.voyageref,
								shipments.closed
							FROM shipments INNER JOIN suppliers
								ON shipments.supplierid = suppliers.supplierid
							WHERE shipments.shiptref = '" . $_GET['SelectedShipment'] . "'";

       $ErrMsg = _('Shipment').' '. $_GET['SelectedShipment'] . ' ' . _('cannot be retrieved because a database error occurred');
       $GetShiptHdrResult = DB_query($ShipmentHeaderSQL, $ErrMsg);

       if (DB_num_rows($GetShiptHdrResult)==0) {
		prnMsg ( _('Unable to locate Shipment') . ' '. $_GET['SelectedShipment'] . ' ' . _('in the database'), 'error');
	        include('includes/footer.php');
        	exit();
	}

       if (DB_num_rows($GetShiptHdrResult)==1) {

              $myrow = DB_fetch_array($GetShiptHdrResult);

	      if ($myrow['closed']==1){
			prnMsg( _('Shipment No.') .' '. $_GET['SelectedShipment'] .': '.
				_('The selected shipment is already closed and no further modifications to the shipment are possible'), 'error');
			include('includes/footer.php');
			exit;
	      }
              $_SESSION['Shipment']->ShiptRef = $_GET['SelectedShipment'];
              $_SESSION['Shipment']->SupplierID = $myrow['supplierid'];
              $_SESSION['Shipment']->SupplierName = $myrow['suppname'];
              $_SESSION['Shipment']->CurrCode = $myrow['currcode'];
              $_SESSION['Shipment']->ETA = $myrow['eta'];
              $_SESSION['Shipment']->Vessel = $myrow['vessel'];
              $_SESSION['Shipment']->VoyageRef = $myrow['voyageref'];

/*now populate the shipment details records */

              $LineItemsSQL = "SELECT purchorderdetails.podetailitem,
				      				purchorders.orderno,
									purchorderdetails.itemcode,
									purchorderdetails.itemdescription,
									purchorderdetails.deliverydate,
									purchorderdetails.glcode,
									purchorderdetails.qtyinvoiced,
									purchorderdetails.unitprice,
									stockmaster.units,
									purchorderdetails.quantityord,
									purchorderdetails.quantityrecd,
									purchorderdetails.stdcostunit,
									stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost as stdcost,
									purchorders.intostocklocation
							FROM purchorderdetails INNER JOIN stockmaster
								ON purchorderdetails.itemcode=stockmaster.stockid
							INNER JOIN purchorders
								ON purchorderdetails.orderno=purchorders.orderno
							WHERE purchorderdetails.shiptref='" . $_GET['SelectedShipment'] . "'";
	      $ErrMsg = _('The lines on the shipment cannot be retrieved because'). ' - ' . DB_error_msg();
              $LineItemsResult = DB_query($LineItemsSQL, $ErrMsg);

        if (DB_num_rows($GetShiptHdrResult)==0) {
                prnMsg ( _('Unable to locate lines for Shipment') . ' '. $_GET['SelectedShipment'] . ' ' . _('in the database'), 'error');
                include('includes/footer.php');
                exit();
        }

        if (DB_num_rows($LineItemsResult) > 0) {

			while ($myrow=DB_fetch_array($LineItemsResult)) {

				if ($myrow['stdcostunit']==0){
					$StandardCost =$myrow['stdcost'];
				} else {
					$StandardCost =$myrow['stdcostunit'];
				}

				$_SESSION['Shipment']->LineItems[$myrow['podetailitem']] = new LineDetails(
																		 $myrow['podetailitem'],
																		 $myrow['orderno'],
																		 $myrow['itemcode'],
																		 $myrow['itemdescription'],
																		 $myrow['qtyinvoiced'],
																		 $myrow['unitprice'],
																		 $myrow['units'],
																		 $myrow['deliverydate'],
																		 $myrow['quantityord'],
																		 $myrow['quantityrecd'],
																		 $StandardCost);
		   } /* line Shipment from shipment details */

		   DB_data_Seek($LineItemsResult,0);
		   $myrow=DB_fetch_array($LineItemsResult);
		   $_SESSION['Shipment']->StockLocation = $myrow['intostocklocation'];

              } //end of checks on returned data set
       }
} // end of reading in the existing shipment


if (!isset($_SESSION['Shipment'])){

	$_SESSION['Shipment'] = new Shipment;

	$sql = "SELECT suppname,
					currcode,
					decimalplaces AS currdecimalplaces
		FROM suppliers INNER JOIN currencies
		ON suppliers.currcode=currencies.currabrev
		WHERE supplierid='" . $_SESSION['SupplierID'] . "'";

	$ErrMsg = _('The supplier details for the shipment could not be retrieved because');
	$result = DB_query($sql,$ErrMsg);
	$myrow = DB_fetch_array($result);

	$_SESSION['Shipment']->SupplierID = $_SESSION['SupplierID'];
	$_SESSION['Shipment']->SupplierName = $myrow['suppname'];
	$_SESSION['Shipment']->CurrCode = $myrow['currcode'];
	$_SESSION['Shipment']->CurrDecimalPlaces = $myrow['currdecimalplaces'];
	$_SESSION['Shipment']->ShiptRef = GetNextTransNo (31);
}



if (isset($_POST['Update'])
	OR (isset($_GET['Add'])
	AND $_SESSION['Shipment']->Closed==0)) { //user hit the update button

	$InputError = 0;
	if (isset($_POST['Update'])){

		if (!Is_Date($_POST['ETA'])){
			$InputError=1;
			prnMsg( _('The date of expected arrival of the shipment must be entered in the format') . ' ' .$_SESSION['DefaultDateFormat'], 'error');
		} elseif (Date1GreaterThanDate2($_POST['ETA'],Date($_SESSION['DefaultDateFormat']))==0){
			$InputError=1;
			prnMsg( _('An expected arrival of the shipment must be a date after today'), 'error');
		} else {
			$_SESSION['Shipment']->ETA = FormatDateForSQL($_POST['ETA']);
		}

		if (mb_strlen($_POST['Vessel'])<2){
			prnMsg( _('A reference to the vessel of more than 2 characters is expected'), 'error');
		}
		if (mb_strlen($_POST['VoyageRef'])<2){
			prnMsg( _('A reference to the voyage (or HAWB in the case of air-freight) of more than 2 characters is expected'), 'error');
		}
	} elseif(mb_strlen($_SESSION['Shipment']->Vessel)<2
			OR mb_strlen($_SESSION['Shipment']->VoyageRef)<2){
		prnMsg(_('Cannot add purchase order lines to the shipment unless the shipment is first initiated - hit update to setup the shipment first'),'info');
		$InputError = 1;
	}
	if ($InputError==0 AND !isset($_GET['Add'])){ //don't update vessel and voyage on adding a new PO line to the shipment
		$_SESSION['Shipment']->Vessel = $_POST['Vessel'];
		$_SESSION['Shipment']->VoyageRef = $_POST['VoyageRef'];
	}
/*The user hit the update the shipment button and there are some lines on the shipment*/
	if ($InputError == 0 AND (count($_SESSION['Shipment']->LineItems) > 0 OR isset($_GET['Add']))){

		$sql = "SELECT shiptref FROM shipments WHERE shiptref =" . $_SESSION['Shipment']->ShiptRef;
		$result = DB_query($sql);
		if (DB_num_rows($result)==1){
			$sql = "UPDATE shipments SET vessel='" . $_SESSION['Shipment']->Vessel . "',
										voyageref='".  $_SESSION['Shipment']->VoyageRef . "',
										eta='" .  $_SESSION['Shipment']->ETA . "'
					WHERE shiptref ='" .  $_SESSION['Shipment']->ShiptRef . "'";

		} else {

			$sql = "INSERT INTO shipments (shiptref,
							vessel,
							voyageref,
							eta,
							supplierid)
					VALUES ('" . $_SESSION['Shipment']->ShiptRef . "',
						'" . $_SESSION['Shipment']->Vessel . "',
						'".  $_SESSION['Shipment']->VoyageRef . "',
						'" . $_SESSION['Shipment']->ETA . "',
						'" . $_SESSION['Shipment']->SupplierID . "')"  ;

		}
		/*now update or insert as necessary */
		$result = DB_query($sql);

		/*now check that the delivery date of all PODetails are the same as the ETA as the shipment */
		foreach ($_SESSION['Shipment']->LineItems as $LnItm) {

			if (DateDiff(ConvertSQLDate($LnItm->DelDate),ConvertSQLDate($_SESSION['Shipment']->ETA),'d')!=0){

				$sql = "UPDATE purchorderdetails
						SET deliverydate ='" . $_SESSION['Shipment']->ETA . "'
						WHERE podetailitem='" . $LnItm->PODetailItem . "'";

				$result = DB_query($sql);

				$_SESSION['Shipment']->LineItems[$LnItm->PODetailItem]->DelDate = $_SESSION['Shipment']->ETA;
			}
		}
		prnMsg( _('Updated the shipment record and delivery dates of order lines as necessary'), 'success');
		echo '<br />';
	} //error traps all passed ok

} //user hit Update

if (isset($_GET['Add'])
	AND $_SESSION['Shipment']->Closed==0
	AND $InputError==0){

	$sql = "SELECT purchorderdetails.orderno,
					purchorderdetails.itemcode,
					purchorderdetails.itemdescription,
					purchorderdetails.unitprice,
					purchorderdetails.stdcostunit,
					stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost as stdcost,
					purchorderdetails.quantityord,
					purchorderdetails.quantityrecd,
					purchorderdetails.deliverydate,
					stockmaster.units,
					stockmaster.decimalplaces,
					purchorderdetails.qtyinvoiced
			FROM purchorderdetails INNER JOIN stockmaster
			ON purchorderdetails.itemcode=stockmaster.stockid
			WHERE purchorderdetails.podetailitem='" . $_GET['Add'] . "'";

	$result = DB_query($sql);
	$myrow = DB_fetch_array($result);

/*The variable StdCostUnit gets set when the item is first received and stored for all future transactions with this purchase order line - subsequent changes to the standard cost will not therefore stuff up variances resulting from the line which may have several entries in GL for each delivery drop if it has already been set from a delivery then use it otherwise use the current system standard */

	if ($myrow['stdcostunit']==0){
		$StandardCost = $myrow['stdcost'];
	}else {
		$StandardCost = $myrow['stdcostunit'];
	}

	$_SESSION['Shipment']->Add_To_Shipment($_GET['Add'],
											$myrow['orderno'],
											$myrow['itemcode'],
											$myrow['itemdescription'],
											$myrow['qtyinvoiced'],
											$myrow['unitprice'],
											$myrow['units'],
											$myrow['deliverydate'],
											$myrow['quantityord'],
											$myrow['quantityrecd'],
											$StandardCost,
											$myrow['decimalplaces']);
}

if (isset($_GET['Delete']) AND $_SESSION['Shipment']->Closed==0){ //shipment is open and user hit delete on a line
	$_SESSION['Shipment']->Remove_From_Shipment($_GET['Delete']);
}



echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<table class="selection">
		<tr>
			<td><b>' .  _('Shipment').': </b></td>
			<td><b>' . $_SESSION['Shipment']->ShiptRef . '</b></td>
			<td><b>' .  _('From'). ' ' . $_SESSION['Shipment']->SupplierName . '</b></td>
		</tr>';

echo '<tr>
		<td>' .  _('Vessel Name /Transport Agent'). ': </td>
		<td colspan="3"><input type="text" name="Vessel" maxlength="50" size="50" value="' . $_SESSION['Shipment']->Vessel . '" /></td>
		<td>' . _('Voyage Ref / Consignment Note').': </td>
		<td><input type="text" name="VoyageRef" maxlength="20" size="20" value="' . $_SESSION['Shipment']->VoyageRef . '" /></td>
	</tr>';

if (isset($_SESSION['Shipment']->ETA)){
	$ETA = ConvertSQLDate($_SESSION['Shipment']->ETA);
} else {
	$ETA ='';
}

echo '<tr><td>' .  _('Expected Arrival Date (ETA)'). ': </td>';
if (isset($_SESSION['Shipment']->ETA)) {
	echo '<td><input type="text" name="ETA" class="date"  maxlength="10" size="11" value="' . $ETA . '" /></td>';
} else {
	echo '<td><input type="text" class="date" name="ETA" maxlength="10" size="11" value="' . Date($_SESSION['DefaultDateFormat']) . '" /></td>';
}
echo '<td>' .  _('Into').' ';

if (count($_SESSION['Shipment']->LineItems)>0){

	if (!isset($_SESSION['Shipment']->StockLocation)){

		$sql = "SELECT purchorders.intostocklocation
				FROM purchorders INNER JOIN purchorderdetails
				ON purchorders.orderno=purchorderdetails.orderno AND podetailitem = '" . key($_SESSION['Shipment']->LineItems) . "'";

		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);

		$_SESSION['Shipment']->StockLocation = $myrow[0];
		$_POST['StockLocation']=$_SESSION['Shipment']->StockLocation;

   } else {

	$_POST['StockLocation']=$_SESSION['Shipment']->StockLocation;
   }
}


if (!isset($_SESSION['Shipment']->StockLocation)){

	echo _('Stock Location').': <select name="StockLocation">';

	$sql = "SELECT loccode, locationname FROM locations";

	$resultStkLocs = DB_query($sql);

	while ($myrow=DB_fetch_array($resultStkLocs)){

		if (isset($_POST['StockLocation'])){
			if ($myrow['loccode'] == $_POST['StockLocation']){
				echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			} else {
				echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			}
		} elseif ($myrow['loccode']==$_SESSION['UserStockLocation']){
			echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		} else {
			echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
		}
	}

	if (!isset($_POST['StockLocation'])){
		$_POST['StockLocation'] = $_SESSION['UserStockLocation'];
	}

	echo '</select>';

} else {
	$sql = "SELECT locationname FROM locations WHERE loccode='" . $_SESSION['Shipment']->StockLocation . "'";
	$resultStkLocs = DB_query($sql);
	$myrow=DB_fetch_array($resultStkLocs);
	echo '<input type="hidden" name="StockLocation" value="'.$_SESSION['Shipment']->StockLocation.'" />';
 	echo $myrow['locationname'];
}

echo '</td></tr></table>';

if (count($_SESSION['Shipment']->LineItems)>0){
	/* Always display all shipment lines */

	echo '<br /><table class="selection">';
	echo '<tr><th colspan="9"><h3>' .  _('Order Lines On This Shipment'). '</h3></th></tr>';

	$TableHeader = '<tr>
						<th>' .  _('Order'). '</th>
						<th>' .  _('Item'). '</th>
						<th>' .  _('Quantity'). '<br />' .  _('Ordered'). '</th>
						<th>' .  _('Units'). '</th>
						<th>' .  _('Quantity') . '<br />' .  _('Received'). '</th>
						<th>' .  _('Quantity') . '<br />' .  _('Invoiced'). '</th>
						<th>' .  $_SESSION['Shipment']->CurrCode .' '. _('Price') . '</th>
						<th>' .  _('Current'). '<br />' .  _('Std Cost'). '</th>
					</tr>';

	echo  $TableHeader;

	/*show the line items on the shipment with the quantity being received for modification */

	$RowCounter =0;

	foreach ($_SESSION['Shipment']->LineItems as $LnItm) {

		if ($RowCounter==15){
			echo $TableHeader;
			$RowCounter =0;
		}
		$RowCounter++;

		echo '<tr class="striped_row">
			<td>' . $LnItm->OrderNo . '</td>
			<td>' .  $LnItm->StockID .' - '. $LnItm->ItemDescription. '</td><td class="number">' . locale_number_format($LnItm->QuantityOrd,$LnItm->DecimalPlaces) . '</td>
			<td>' .  $LnItm->UOM  . '</td>
			<td class="number">' . locale_number_format($LnItm->QuantityRecd,$LnItm->DecimalPlaces) . '</td>
			<td class="number">' . locale_number_format($LnItm->QtyInvoiced,$LnItm->DecimalPlaces) . '</td>
			<td class="number">' . locale_number_format($LnItm->UnitPrice, $_SESSION['Shipment']->CurrDecimalPlaces) . '</td>
			<td class="number">' . locale_number_format($LnItm->StdCostUnit,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=' . $LnItm->PODetailItem . '">' .  _('Delete'). '</a></td>
			</tr>';
	}//for each line on the shipment
echo '</table>';
}//there are lines on the shipment

echo '<br />
		<div class="centre">
			<input type="submit" name="Update" value="'. _('Update Shipment Details') . '" />
		</div>';

if (!isset($_POST['StockLocation'])) {
	$_POST['StockLocation'] =$_SESSION['Shipment']->StockLocation;
}

$sql = "SELECT purchorderdetails.podetailitem,
				purchorders.orderno,
				purchorderdetails.itemcode,
				purchorderdetails.itemdescription,
				purchorderdetails.unitprice,
				purchorderdetails.quantityord,
				purchorderdetails.quantityrecd,
				purchorderdetails.deliverydate,
				stockmaster.units,
				stockmaster.decimalplaces
			FROM purchorderdetails INNER JOIN purchorders
				ON purchorderdetails.orderno=purchorders.orderno
				INNER JOIN stockmaster
			ON purchorderdetails.itemcode=stockmaster.stockid
			WHERE qtyinvoiced=0
			AND purchorders.status <> 'Cancelled'
			AND purchorders.status <> 'Rejected'
			AND purchorders.supplierno ='" . $_SESSION['Shipment']->SupplierID . "'
			AND purchorderdetails.shiptref=0
			AND purchorders.intostocklocation='" . $_POST['StockLocation'] . "'";

$result = DB_query($sql);

if (DB_num_rows($result)>0){

	echo '<table cellpadding="2" class="selection">';
	echo '<tr>
			<th colspan="7"><h3>' .  _('Possible Order Lines To Add To This Shipment') . '</h3></th>
		</tr>';

	$TableHeader = '<tr>
						<th>' .  _('Order') . '</th>
						<th>' .  _('Item') . '</th>
						<th>' .  _('Quantity') . '<br />' .  _('Ordered') . '</th>
						<th>' .  _('Units') . '</th>
						<th>' .  _('Quantity') . '<br />' .  _('Received') . '</th>
						<th>' .  _('Delivery') . '<br />' .  _('Date') . '</th>
					</tr>';

	echo  $TableHeader;

	/*show the PO items that could be added to the shipment */

	$RowCounter =0;

	while ($myrow=DB_fetch_array($result)){

		if ($RowCounter==15){
			echo $TableHeader;
			$RowCounter =0;
		}
		$RowCounter++;

		echo '<tr class="striped_row">
				<td>' . $myrow['orderno'] . '</td>
				<td>' . $myrow['itemcode'] . ' - ' . $myrow['itemdescription'] . '</td>
				<td class="number">' . locale_number_format($myrow['quantityord'],$myrow['decimalplaces']) . '</td>
				<td>' . $myrow['units'] . '</td>
				<td class="number">' . locale_number_format($myrow['quantityrecd'],$myrow['decimalplaces']) . '</td>
				<td class="number">' . ConvertSQLDate($myrow['deliverydate']) . '</td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?' . 'Add=' . $myrow['podetailitem'] . '">' .  _('Add') . '</a></td>
			</tr>';

	}
	echo '</table>';
}

echo '</div>
      </form>';

include('includes/footer.php');
?>

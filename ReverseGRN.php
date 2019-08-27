<?php

include ('includes/DefineSerialItems.php');
include ('includes/SQL_CommonFunctions.inc');
include ('includes/session.php');

$Title = _('Reverse Goods Received');

include ('includes/header.php');

if ((isset($_SESSION['SupplierID']) and $_SESSION['SupplierID'] != '') or (!isset($_POST['SupplierID']) or $_POST['SupplierID']) == '') {

	$_POST['SupplierID'] = $_SESSION['SupplierID'];

}

if (!isset($_POST['SupplierID']) or $_POST['SupplierID'] == "") {
	echo '<br />' . _('This page is expected to be called after a supplier has been selected');
	echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/SelectSupplier.php">';
	exit;
} elseif (!isset($_POST['SuppName']) or $_POST['SuppName'] == "") {
	$SQL = "SELECT suppname FROM suppliers WHERE supplierid='" . $_SESSION['SupplierID'] . "'";
	$SuppResult = DB_query($SQL, _('Could not retrieve the supplier name for') . ' ' . $_SESSION['SupplierID']);
	$SuppRow = DB_fetch_row($SuppResult);
	$_POST['SuppName'] = $SuppRow[0];
}

echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/supplier.png" title="' . _('Sales') . '" alt="" />' . ' ' . _('Reverse Goods Received from') . ' ' . $_POST['SuppName'] . '</p> ';

if (isset($_GET['GRNNo']) and isset($_POST['SupplierID'])) {
	/* SQL to process the postings for the GRN reversal.. */

	//Get the details of the GRN item and the cost at which it was received and other PODetail info
	$SQL = "SELECT grns.podetailitem,
					grns.grnbatch,
					grns.itemcode,
					grns.itemdescription,
					grns.deliverydate,
					grns.supplierref,
					purchorderdetails.glcode,
					purchorderdetails.assetid,
					grns.qtyrecd,
					grns.quantityinv,
					purchorderdetails.stdcostunit,
					purchorders.intostocklocation,
					purchorders.orderno
			FROM grns INNER JOIN purchorderdetails
			ON grns.podetailitem=purchorderdetails.podetailitem
			INNER JOIN purchorders
			ON purchorderdetails.orderno = purchorders.orderno
			INNER JOIN locationusers ON locationusers.loccode=purchorders.intostocklocation AND locationusers.userid='" . $_SESSION['UserID'] . "' AND locationusers.canupd=1
			WHERE grnno='" . $_GET['GRNNo'] . "'";

	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not get the details of the GRN selected for reversal because') . ' ';
	$DbgMsg = _('The following SQL to retrieve the GRN details was used') . ':';

	$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

	$GRN = DB_fetch_array($Result);
	$QtyToReverse = $GRN['qtyrecd'] - $GRN['quantityinv'];

	if ($QtyToReverse == 0) {
		echo '<br />
				<br />' . _('The GRN') . ' ' . $_GET['GRNNo'] . ' ' . _('has already been reversed or fully invoiced by the supplier - it cannot be reversed - stock quantities must be corrected by stock adjustments - the stock is paid for');
		include ('includes/footer.php');
		exit;
	}

	/*If the item is a stock item then need to check for Controlled or not ...
	 if its controlled then need to check existence of the controlled items
	 that came in with this GRN */

	$SQL = "SELECT stockmaster.controlled
			FROM stockmaster WHERE stockid ='" . $GRN['itemcode'] . "'";
	$CheckControlledResult = DB_query($SQL, '<br />' . _('Could not determine if the item was controlled or not because') . ' ');
	$ControlledRow = DB_fetch_row($CheckControlledResult);
	if ($ControlledRow[0] == 1) { /*Then its a controlled item */
		$Controlled = true;
		/*So check to ensure the serial items received on this GRN are still there */
		/*First get the StockMovement Reference for the GRN */
		$SQL = "SELECT stockserialmoves.serialno,
				stockserialmoves.moveqty
		        FROM stockmoves INNER JOIN stockserialmoves
				ON stockmoves.stkmoveno= stockserialmoves.stockmoveno
				WHERE stockmoves.stockid='" . $GRN['itemcode'] . "'
				AND stockmoves.type =25
				AND stockmoves.transno='" . $GRN['grnbatch'] . "'";
		$GetStockMoveResult = DB_query($SQL, _('Could not retrieve the stock movement reference number which is required in order to retrieve details of the serial items that came in with this GRN'));

		while ($SerialStockMoves = DB_fetch_array($GetStockMoveResult)) {

			$SQL = "SELECT stockserialitems.quantity
			        FROM stockserialitems
					WHERE stockserialitems.stockid='" . $GRN['itemcode'] . "'
					AND stockserialitems.loccode ='" . $GRN['intostocklocation'] . "'
					AND stockserialitems.serialno ='" . $SerialStockMoves['serialno'] . "'";
			$GetQOHResult = DB_query($SQL, _('Unable to retrieve the quantity on hand of') . ' ' . $GRN['itemcode'] . ' ' . _('for Serial No') . ' ' . $SerialStockMoves['serialno']);
			$GetQOH = DB_fetch_row($GetQOHResult);
			if ($GetQOH[0] < $SerialStockMoves['moveqty']) {
				/*Then some of the original goods received must have been sold
				 or transfered so cannot reverse the GRN */
				prnMsg(_('Unfortunately, of the original number') . ' (' . $SerialStockMoves['moveqty'] . ') ' . _('that were received on serial number') . ' ' . $SerialStockMoves['serialno'] . ' ' . _('only') . ' ' . $GetQOH[0] . ' ' . _('remain') . '. ' . _('The GRN can only be reversed if all the original serial number items are still in stock in the location they were received into'), 'error');
				include ('includes/footer.php');
				exit;
			}
		}
		/*reset the pointer on this resultset ... will need it later */
		DB_data_seek($GetStockMoveResult, 0);
	} else {
		$Controlled = false;
	}

	/*Start an SQL transaction */

	$Result = DB_Txn_Begin();

	$PeriodNo = GetPeriod(ConvertSQLDate($GRN['deliverydate']));

	/*Now the SQL to do the update to the PurchOrderDetails */

	$SQL = "UPDATE purchorderdetails
			SET quantityrecd = quantityrecd - '" . $QtyToReverse . "',
			completed=0
			WHERE purchorderdetails.podetailitem = '" . $GRN['podetailitem'] . "'";

	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The purchase order detail record could not be updated with the quantity reversed because');
	$DbgMsg = _('The following SQL to update the purchase order detail record was used');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

	/*Now the purchorder header status in case it was completed  - now incomplete - just printed */
	$SQL = "UPDATE purchorders
			SET status = 'Printed',
				stat_comment = CONCAT('" . Date($_SESSION['DefaultDateFormat']) . ' ' . _('GRN Reversed for') . ' ' . DB_escape_string(stripslashes($GRN['itemdescription'])) . ' ' . _('by') . ' ' . $_SESSION['UsersRealName'] . "<br />', stat_comment )
			WHERE orderno = '" . $GRN['orderno'] . "'";

	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The purchase order statusand status comment could not be changed because');
	$DbgMsg = _('The following SQL to update the purchase order header record was used');
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

	/*Need to update or delete the existing GRN item */
	if ($QtyToReverse == $GRN['qtyrecd']) { //then ok to delete the whole thing
		/* if this is not deleted then the purchorderdetail line cannot be deleted subsequentely */
		//remove suppinvtogrns first;
		$SQL = "DELETE FROM suppinvstogrn WHERE grnno='" . $_GET['GRNNo'] . "'";
		$ErrMsg = _('Failed to delete the grn from supplier invoice record');
		$Result = DB_query($SQL, $ErrMsg, '', true);

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GRN record could not be deleted because');
		$DbgMsg = _('The following SQL to delete the GRN record was used');
		$Result = DB_query("DELETE FROM grns WHERE grnno='" . $_GET['GRNNo'] . "'", $ErrMsg, $DbgMsg, true);
	} else {
		$SQL = "UPDATE grns	SET qtyrecd = qtyrecd - " . $QtyToReverse . "
				WHERE grns.grnno='" . $_GET['GRNNo'] . "'";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GRN record could not be updated') . '. ' . _('This reversal of goods received has not been processed because');
		$DbgMsg = _('The following SQL to insert the GRN record was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
	}
	/*If the GRN being reversed is an asset - reverse the fixedassettrans record */
	if ($GRN['assetid'] != '0') {
		$SQL = "INSERT INTO fixedassettrans (assetid,
											transtype,
											transno,
											transdate,
											periodno,
											inputdate,
											fixedassettranstype,
											cost)
						VALUES ('" . $GRN['assetid'] . "',
								'25',
								'" . $_GET['GRNNo'] . "',
								'" . $GRN['deliverydate'] . "',
								'" . $PeriodNo . "',
								CURRENT_DATE,
								'" . _('cost') . "',
								'" . (-$GRN['stdcostunit'] * $QtyToReverse) . "')";
		$ErrMsg = _('CRITICAL ERROR! NOTE DOWN THIS ERROR AND SEEK ASSISTANCE The fixed asset transaction could not be inserted because');
		$DbgMsg = _('The following SQL to insert the fixed asset transaction record was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

		/*now reverse the cost put to fixedassets */
		$SQL = "UPDATE fixedassets SET cost = cost - " . $GRN['stdcostunit'] * $QtyToReverse . "
				WHERE assetid = '" . $GRN['assetid'] . "'";
		$ErrMsg = _('CRITICAL ERROR! NOTE DOWN THIS ERROR AND SEEK ASSISTANCE. The fixed asset cost addition could not be reversed:');
		$DbgMsg = _('The following SQL was used to attempt the reduce the cost of the asset was:');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

	} //end of if it is an asset
	$SQL = "SELECT stockmaster.controlled
			FROM stockmaster
			WHERE stockmaster.stockid = '" . $GRN['itemcode'] . "'";
	$Result = DB_query($SQL, _('Could not determine if the item exists because'), '<br />' . _('The SQL that failed was') . ' ', true);

	if (DB_num_rows($Result) == 1) { /* if the GRN is in fact a stock item being reversed */

		$StkItemExists = DB_fetch_row($Result);
		$Controlled = $StkItemExists[0];

		/* Update location stock records - NB  a PO cannot be entered for a dummy/assembly/kit parts */
		/*Need to get the current location quantity will need it later for the stock movement */
		$SQL = "SELECT quantity
				FROM locstock
				WHERE stockid='" . $GRN['itemcode'] . "'
				AND loccode= '" . $GRN['intostocklocation'] . "'";

		$Result = DB_query($SQL, _('Could not get the quantity on hand of the item before the reversal was processed'), _('The SQL that failed was'), true);
		if (DB_num_rows($Result) == 1) {
			$LocQtyRow = DB_fetch_row($Result);
			$QtyOnHandPrior = $LocQtyRow[0];
		} else {
			/*There must actually be some error this should never happen */
			$QtyOnHandPrior = 0;
		}

		$SQL = "UPDATE locstock
				SET quantity = quantity - " . $QtyToReverse . "
				WHERE stockid = '" . $GRN['itemcode'] . "'
				AND loccode = '" . $GRN['intostocklocation'] . "'";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
		$DbgMsg = _('The following SQL to update the location stock record was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

		/* If its a stock item .... Insert stock movements - with unit cost */
		$NewQtyOnHand = $QtyOnHandPrior - $QtyToReverse;
		$SQL = "INSERT INTO stockmoves (stockid,
										type,
										transno,
										loccode,
										trandate,
										userid,
										prd,
										reference,
										qty,
										standardcost,
										newqoh)
									VALUES (
										'" . $GRN['itemcode'] . "',
										25,
										'" . $_GET['GRNNo'] . "',
										'" . $GRN['intostocklocation'] . "',
										'" . $GRN['deliverydate'] . "',
										'" . $_SESSION['UserID'] . "',
										'" . $PeriodNo . "',
										'" . _('Reversal') . ' - ' . $_POST['SupplierID'] . ' - ' . $GRN['orderno'] . "',
										'" . -$QtyToReverse . "',
										'" . $GRN['stdcostunit'] . "',
										'" . $NewQtyOnHand . "'
										)";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records could not be inserted because');
		$DbgMsg = _('The following SQL to insert the stock movement records was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

		$StkMoveNo = DB_Last_Insert_ID('stockmoves', 'stkmoveno');

		if ($Controlled == true) {
			while ($SerialStockMoves = DB_fetch_array($GetStockMoveResult)) {
				$SQL = "INSERT INTO stockserialmoves (
						stockmoveno,
						stockid,
						serialno,
						moveqty)
					VALUES (
						'" . $StkMoveNo . "',
						'" . $GRN['itemcode'] . "',
						'" . $SerialStockMoves['serialno'] . "',
						'" . -$SerialStockMoves['moveqty'] . "')";

				$Result = DB_query($SQL, _('Could not insert the reversing stock movements for the batch/serial numbers'), _('The SQL used but failed was') . ':', true);

				$SQL = "UPDATE stockserialitems
					SET quantity=quantity - " . $SerialStockMoves['moveqty'] . "
					WHERE stockserialitems.stockid='" . $GRN['itemcode'] . "'
					AND stockserialitems.loccode ='" . $GRN['intostocklocation'] . "'
					AND stockserialitems.serialno = '" . $SerialStockMoves['serialno'] . "'";
				$Result = DB_query($SQL, _('Could not update the batch/serial stock records'), _('The SQL used but failed was') . ':', true);
			}
		}
	} /*end of its a stock item - updates to locations and insert movements*/

	/* If GLLink_Stock then insert GLTrans to debit the GL Code  and credit GRN Suspense account at standard cost*/

	if ($_SESSION['CompanyRecord']['gllink_stock'] == 1 and $GRN['glcode'] != 0 and $GRN['stdcostunit'] != 0) {

		/*GLCode is set to 0 when the GLLink is not activated
		this covers a situation where the GLLink is now active  but it wasn't when this PO was entered
		
		First the credit using the GLCode in the PO detail record entry*/

		$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount)
								VALUES (
									25,
									'" . $_GET['GRNNo'] . "',
									'" . $GRN['deliverydate'] . "',
									'" . $PeriodNo . "',
									'" . $GRN['glcode'] . "',
									'" . _('GRN Reversal for PO') . ": " . $GRN['orderno'] . " " . $_POST['SupplierID'] . " - " . $GRN['itemcode'] . "-" . DB_escape_string($GRN['itemdescription']) . " x " . $QtyToReverse . " @ " . locale_number_format($GRN['stdcostunit'], $_SESSION['CompanyRecord']['decimalplaces']) . "',
									'" . -($GRN['stdcostunit'] * $QtyToReverse) . "')";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The purchase GL posting could not be inserted for the reversal of the received item because');
		$DbgMsg = _('The following SQL to insert the purchase GLTrans record was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

		/*now the GRN suspense entry*/
		$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount)
							VALUES (
								25,
								'" . $_GET['GRNNo'] . "',
								'" . $GRN['deliverydate'] . "',
								'" . $PeriodNo . "',
								'" . $_SESSION['CompanyRecord']['grnact'] . "', '" . _('GRN Reversal PO') . ': ' . $GRN['orderno'] . " " . $_POST['SupplierID'] . " - " . $GRN['itemcode'] . "-" . DB_escape_string($GRN['itemdescription']) . " x " . $QtyToReverse . " @ " . locale_number_format($GRN['stdcostunit'], $_SESSION['CompanyRecord']['decimalplaces']) . "',
								'" . $GRN['stdcostunit'] * $QtyToReverse . "'
								)";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GRN suspense side of the GL posting could not be inserted because');
		$DbgMsg = _('The following SQL to insert the GRN Suspense GLTrans record was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
	} /* end of if GL and stock integrated*/

	$Result = DB_Txn_Commit();

	echo '<br />' . _('GRN number') . ' ' . $_GET['GRNNo'] . ' ' . _('for') . ' ' . $QtyToReverse . ' x ' . $GRN['itemcode'] . ' - ' . $GRN['itemdescription'] . ' ' . _('has been reversed') . '<br />';
	unset($_GET['GRNNo']); // to ensure it cant be done again!!
	echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">' . _('Select another GRN to Reverse') . '</a>';
	/*end of Process Goods Received Reversal entry */

} else {
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
	echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (!isset($_POST['RecdAfterDate']) or !Is_Date($_POST['RecdAfterDate'])) {
		$_POST['RecdAfterDate'] = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, Date("m") - 3, Date("d"), Date("Y")));
	}
	echo '<input type="hidden" name="SupplierID" value="' . $_POST['SupplierID'] . '" />';
	echo '<input type="hidden" name="SuppName" value="' . $_POST['SuppName'] . '" />';
	echo '<table class="selection"><tr>';
	echo '<td>' . _('Show all goods received after') . ': </td>
			<td><input type="text" class="date" name="RecdAfterDate" value="' . $_POST['RecdAfterDate'] . '" maxlength="10" size="11" /></td>
		</tr>
		</table>
		<br />
		<div class="centre">
			<input type="submit" name="ShowGRNS" value="' . _('Show Outstanding Goods Received') . '" />
		</div>
		</div>
	</form>';

	if (isset($_POST['ShowGRNS'])) {

		$SQL = "SELECT grnno,
						grnbatch,
						grns.itemcode,
						grns.itemdescription,
						grns.deliverydate,
						grns.supplierref,
						qtyrecd,
						quantityinv,
						qtyrecd-quantityinv AS qtytoreverse
				FROM grns
				INNER JOIN purchorderdetails ON purchorderdetails.podetailitem=grns.podetailitem
				INNER JOIN purchorders on purchorders.orderno = purchorderdetails.orderno
				INNER JOIN locationusers ON locationusers.loccode=purchorders.intostocklocation AND locationusers.userid='" . $_SESSION['UserID'] . "' AND locationusers.canupd=1
				WHERE grns.supplierid = '" . $_POST['SupplierID'] . "'
				AND (grns.qtyrecd-grns.quantityinv) >0
				AND grns.deliverydate>='" . FormatDateForSQL($_POST['RecdAfterDate']) . "'";

		$ErrMsg = _('An error occurred in the attempt to get the outstanding GRNs for') . ' ' . $_POST['SuppName'] . '. ' . _('The message was') . ':';
		$DbgMsg = _('The SQL that failed was') . ':';
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

		if (DB_num_rows($Result) == 0) {
			prnMsg(_('There are no outstanding goods received yet to be invoiced for') . ' ' . $_POST['SuppName'] . '.<br />' . _('To reverse a GRN that has been invoiced first it must be credited'), 'warn');
		} else { //there are GRNs to show
			echo '<br /><table cellpadding="2" class="selection">';
			$TableHeader = '<tr>
								<th>' . _('GRN') . ' #</th>
								<th>' . _('GRN Batch') . '</th>
								<th>' . _('Supplier\' Ref') . '</th>
								<th>' . _('Item Code') . '</th>
								<th>' . _('Description') . '</th>
								<th>' . _('Date') . '<br />' . _('Received') . '</th>
								<th>' . _('Quantity') . '<br />' . _('Received') . '</th>
								<th>' . _('Quantity') . '<br />' . _('Invoiced') . '</th>
								<th>' . _('Quantity To') . '<br />' . _('Reverse') . '</th>
							</tr>';

			echo $TableHeader;

			/* show the GRNs outstanding to be invoiced that could be reversed */
			$RowCounter = 0;
			while ($MyRow = DB_fetch_array($Result)) {

				$DisplayQtyRecd = locale_number_format($MyRow['qtyrecd'], 'Variable');
				$DisplayQtyInv = locale_number_format($MyRow['quantityinv'], 'Variable');
				$DisplayQtyRev = locale_number_format($MyRow['qtytoreverse'], 'Variable');
				$DisplayDateDel = ConvertSQLDate($MyRow['deliverydate']);
				$LinkToRevGRN = '<a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?GRNNo=' . $MyRow['grnno'] . '">' . _('Reverse') . '</a>';

				printf('<tr class="striped_row">
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td>%s</td>
						</tr>', $MyRow['grnno'], $MyRow['grnbatch'], $MyRow['supplierref'], $MyRow['itemcode'], $MyRow['itemdescription'], $DisplayDateDel, $DisplayQtyRecd, $DisplayQtyInv, $DisplayQtyRev, $LinkToRevGRN);

				$RowCounter++;
				if ($RowCounter > 20) {
					$RowCounter = 0;
					echo $TableHeader;
				}
			}

			echo '</table>';

		}
	}
}
include ('includes/footer.php');
?>

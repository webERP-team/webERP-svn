<?php


include('includes/DefineContractClass.php');
include('includes/session.php');
$Title = _('Contract Costing');
/* Session started in header.php for password checking and authorisation level check */
include('includes/header.php');

if (empty($_GET['identifier'])) {
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

if (!isset($_GET['SelectedContract'])){
	echo '<br />';
	prnMsg( _('This page is expected to be called with the contract reference to show the costing for'), 'error');
	include ('includes/footer.php');
	exit;
} else {
	$ContractRef = $_GET['SelectedContract'];
	$_SESSION['Contract'.$identifier] = new Contract;
	include('includes/Contract_Readin.php');
}

/*Now read in actual usage of stock */
$sql = "SELECT stockmoves.stockid,
				stockmaster.description,
				stockmaster.units,
				stockmaster.decimalplaces,
				SUM(stockmoves.qty) AS quantity,
				SUM(stockmoves.qty*stockmoves.standardcost) AS totalcost
		FROM stockmoves INNER JOIN stockmaster
		ON stockmoves.stockid=stockmaster.stockid
		WHERE stockmoves.type=28
		AND stockmoves.reference='" . $_SESSION['Contract'.$identifier]->WO . "'
		GROUP BY stockmoves.stockid,
				stockmaster.description,
				stockmaster.units,
				stockmaster.decimalplaces";
$ErrMsg = _('Could not get the inventory issues for this contract because');
$InventoryIssuesResult = DB_query($sql,$ErrMsg);
$InventoryIssues = array();
while ($InventoryIssuesRow = DB_fetch_array($InventoryIssuesResult)){
	$InventoryIssues[$InventoryIssuesRow['stockid']]['StockID'] = $InventoryIssuesRow['stockid'];
	$InventoryIssues[$InventoryIssuesRow['stockid']]['Description'] = $InventoryIssuesRow['description'];
	$InventoryIssues[$InventoryIssuesRow['stockid']]['Quantity'] = $InventoryIssuesRow['quantity'];
	$InventoryIssues[$InventoryIssuesRow['stockid']]['TotalCost'] = $InventoryIssuesRow['totalcost'];
	$InventoryIssues[$InventoryIssuesRow['stockid']]['Units'] = $InventoryIssuesRow['units'];
	$InventoryIssues[$InventoryIssuesRow['stockid']]['DecimalPlaces'] = $InventoryIssuesRow['decimalplaces'];
	$InventoryIssues[$InventoryIssuesRow['stockid']]['Matched'] = 0;

}

echo '<p class="page_title_text">
			<img src="'.$RootPath.'/css/'.$Theme.'/images/contract.png" title="' . _('Contract') . '" alt="" />';
if ($_SESSION['Contract'.$identifier]->Status==3){
	echo _('Closed')  . ' ';
} elseif ($_SESSION['Contract'.$identifier]->Status==2){
	echo _('Current Confirmed')  . ' ';
} elseif ($_SESSION['Contract'.$identifier]->Status==1){
	echo _('Quoted')  . ' ';
}
echo _('Contract') . '<br />' . $_SESSION['Contract'.$identifier]->CustomerName . '<br />' . $_SESSION['Contract'.$identifier]->ContractDescription . '</p>';

echo '<table class="selection">
	<tr>
		<th colspan="6">' . _('Original Costing')  . '</th>
		<th colspan="6">' . _('Actual Costs')   . '</th>
	</tr>';

echo '<tr>
		<th colspan="12">'  . _('Inventory Required') . '</th>
	</tr>';

echo '<tr>
		<th>' . _('Item Code') . '</th>
		<th>' . _('Item Description') . '</th>
		<th>' . _('Quantity') . '</th>
		<th>' . _('Unit') . '</th>
		<th>' . _('Unit Cost') . '</th>
		<th>' . _('Total Cost') . '</th>
		<th>' . _('Item Code') . '</th>
		<th>' . _('Item Description') . '</th>
		<th>' . _('Quantity') . '</th>
		<th>' . _('Unit') . '</th>
		<th>' . _('Unit Cost') . '</th>
		<th>' . _('Total Cost') . '</th>
		</tr>';

$ContractBOMBudget = 0;
$ContractBOMActual = 0;
foreach ($_SESSION['Contract'.$identifier]->ContractBOM as $Component) {
	echo '<tr>
			<td>' . $Component->StockID . '</td>
			<td>' . $Component->ItemDescription . '</td>
			<td class="number">' . locale_number_format($Component->Quantity,$Component->DecimalPlaces) . '</td>
			<td>' . $Component->UOM . '</td>
			<td class="number">' . locale_number_format($Component->ItemCost,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format(($Component->ItemCost * $Component->Quantity),$_SESSION['CompanyRecord']['decimalplaces']) . '</td>';

	$ContractBOMBudget += ($Component->ItemCost *  $Component->Quantity);

	if (isset($InventoryIssues[$Component->StockID])){
		$InventoryIssues[$Component->StockID]['Matched']=1;
		echo '<td colspan="2" align="center">' . _('Actual usage') . '</td>
			<td class="number">' . locale_number_format(-$InventoryIssues[$Component->StockID]['Quantity'],$Component->DecimalPlaces) . '</td>
			<td>' . $InventoryIssues[$Component->StockID]['Units'] . '</td>
			<td class="number">' . locale_number_format($InventoryIssues[$Component->StockID]['TotalCost']/$InventoryIssues[$Component->StockID]['Quantity'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td>' . locale_number_format(-$InventoryIssues[$Component->StockID]['TotalCost'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			</tr>';
	} else {
		echo '<td colspan="6"></td>
			</tr>';
	}
}

foreach ($InventoryIssues as $Component) { //actual inventory components used
	$ContractBOMActual -=$Component['TotalCost'];
	if ($Component['Matched'] == 0) { //then its a component that wasn't budget for
		echo '<tr>
				<td colspan="6"></td>
				<td>' . $Component['StockID'] . '</td>
				<td>' . $Component['Description'] . '</td>
				<td class="number">' . locale_number_format(-$Component['Quantity'],$Component['DecimalPlaces']) . '</td>
				<td>' . $Component['Units'] . '</td>
				<td class="number">' . locale_number_format($Component['TotalCost']/$Component['Quantity'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				<td class="number">' . locale_number_format(-$Component['TotalCost'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			</tr>';
	} //end if its a component not originally budgeted for
}

echo '<tr>
		<td class="number" colspan="5">' . _('Total Inventory Budgeted Cost') . ':</td>
		<td class="number">' . locale_number_format($ContractBOMBudget,$_SESSION['CompanyRecord']['decimalplaces'])  . '</td>
		<td class="number" colspan="5">' . _('Total Inventory Actual Cost') . ':</td>
		<td class="number">' . locale_number_format($ContractBOMActual,$_SESSION['CompanyRecord']['decimalplaces'])  . '</td>
	</tr>';

echo '<tr>
		<th colspan="12" align="center">'  . _('Other Costs') . '</th>
	</tr>';

$OtherReqtsBudget = 0;
//other requirements budget sub-table
echo '<tr>
		<td colspan="6"><table class="selection">
		<tr>
			<th>' . _('Requirement') . '</th>
			<th>' . _('Quantity') . '</th>
			<th>' . _('Unit Cost') . '</th>
			<th>' . _('Total Cost') . '</th>
		</tr>';

foreach ($_SESSION['Contract'.$identifier]->ContractReqts as $Requirement) {
	echo '<tr><td>' . $Requirement->Requirement . '</td>
			<td class="number">' . locale_number_format($Requirement->Quantity,'Variable') . '</td>
			<td class="number">' . locale_number_format($Requirement->CostPerUnit,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format(($Requirement->CostPerUnit * $Requirement->Quantity),$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
		</tr>';
	$OtherReqtsBudget += ($Requirement->CostPerUnit * $Requirement->Quantity);
}
echo '<tr>
		<th colspan="3" align="right"><b>' . _('Budgeted Other Costs') . '</b></th>
		<th class="number"><b>' . locale_number_format($OtherReqtsBudget,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></th>
	</tr>
	</table></td>';

//Now other requirements actual in a sub table
echo '<td colspan="6">
			<table class="selection">
			<tr>
				<th>' . _('Supplier') . '</th>
				<th>' . _('Reference') . '</th>
				<th>' . _('Date') . '</th>
				<th>' . _('Requirement') . '</th>
				<th>' . _('Total Cost') . '</th>
				<th>' . _('Anticipated') . '</th>
			 </tr>';

/*Now read in the actual other items charged to the contract */
$sql = "SELECT supptrans.supplierno,
				supptrans.suppreference,
				supptrans.trandate,
				contractcharges.amount,
				contractcharges.narrative,
				contractcharges.anticipated
		FROM supptrans INNER JOIN contractcharges
		ON supptrans.type=contractcharges.transtype
		AND supptrans.transno=contractcharges.transno
		WHERE contractcharges.contractref='" . $ContractRef . "'
		ORDER BY contractcharges.anticipated";
$ErrMsg = _('Could not get the other charges to the contract because');
$OtherChargesResult = DB_query($sql,$ErrMsg);
$OtherReqtsActual =0;
while ($OtherChargesRow=DB_fetch_array($OtherChargesResult)) {
	if ($OtherChargesRow['anticipated']==0){
		$Anticipated = _('No');
	} else {
		$Anticipated = _('Yes');
	}
	echo '<tr>
			<td>' . $OtherChargesRow['supplierno'] . '</td>
			<td>' . $OtherChargesRow['suppreference'] . '</td>
			<td>' .ConvertSQLDate($OtherChargesRow['trandate']) . '</td>
			<td>' . $OtherChargesRow['narrative'] . '</td>
			<td class="number">' . locale_number_format($OtherChargesRow['amount'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td>' . $Anticipated . '</td>
		</tr>';
	$OtherReqtsActual +=$OtherChargesRow['amount'];
}
echo '<tr>
		<th colspan="4" align="right"><b>' . _('Actual Other Costs') . '</b></th>
		<th class="number"><b>' . locale_number_format($OtherReqtsActual,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></th>
	</tr>
	</table></td>
	</tr>
	<tr>
		<td colspan="5"><b>' . _('Total Budget Contract Cost') . '</b></td>
		<td class="number"><b>' . locale_number_format($OtherReqtsBudget+$ContractBOMBudget,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></td>
		<td colspan="5"><b>' . _('Total Actual Contract Cost') . '</b></td>
		<td class="number"><b>' . locale_number_format($OtherReqtsActual+$ContractBOMActual,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></td>
	</tr>
	</table>';


//Do the processing here after the variances are all calculated above
if (isset($_POST['CloseContract']) AND $_SESSION['Contract'.$identifier]->Status==2){

	include('includes/SQL_CommonFunctions.inc');

	$GLCodes = GetStockGLCode($_SESSION['Contract'.$identifier]->ContractRef);
//Compare actual costs to original budgeted contract costs - if actual > budgeted - CR WIP and DR usage variance
	$Variance =  ($OtherReqtsBudget+$ContractBOMBudget)-($OtherReqtsActual+$ContractBOMActual);

	$ContractCloseNo = GetNextTransNo( 32 );
	$PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']));

	DB_Txn_Begin();

	$SQL = "INSERT INTO gltrans (type,
								typeno,
								trandate,
								periodno,
								account,
								narrative,
								amount)
					VALUES ( 32,
							'" . $ContractCloseNo . "',
							'" . Date('Y-m-d') . "',
							'" . $PeriodNo . "',
							'" . $GLCodes['wipact'] . "',
							'" . _('Variance on contract') . ' ' . $_SESSION['Contract'.$identifier]->ContractRef . "',
							'" . -$Variance . "')";

	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The gl entry of WIP for the variance on closing the contract could not be inserted because');
	$DbgMsg = _('The following SQL to insert the GLTrans record was used');
	$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
	$SQL = "INSERT INTO gltrans (type,
								typeno,
								trandate,
								periodno,
								account,
								narrative,
								amount)
					VALUES ( 32,
							'" . $ContractCloseNo . "',
							'" . Date('Y-m-d') . "',
							'" . $PeriodNo . "',
							'" . $GLCodes['materialuseagevarac'] . "',
							'" . _('Variance on contract') . ' ' . $_SESSION['Contract'.$identifier]->ContractRef . "',
							'" . $Variance . "')";

	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The gl entry of WIP for the variance on closing the contract could not be inserted because');
	$DbgMsg = _('The following SQL to insert the GLTrans record was used');
	$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

//Now update the status of the contract to closed
	$SQL = "UPDATE contracts
				SET status=3
				WHERE contractref='" . $_SESSION['Contract'.$identifier]->ContractRef . "'";
	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The status of the contract could not be updated to closed because');
	$DbgMsg = _('The following SQL to change the status of the contract was used');
	$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

/*Check if the contract work order is still open */
	$CheckIfWOOpenResult = DB_query("SELECT closed
									FROM workorders
									WHERE wo='" . $_SESSION['Contract'.$identifier]->WO . "'");
	$CheckWORow=DB_fetch_row($CheckIfWOOpenResult);
	if ($CheckWORow[0]==0){
		//then close the work order
		$CloseWOResult =DB_query("UPDATE workorders
									SET closed=1
									WHERE wo='" . $_SESSION['Contract'.$identifier]->WO . "'",
									_('Could not update the work order to closed because:'),
									_('The SQL used to close the work order was:'),
									true);


	/* Check if the contract BOM has received the contract item manually
	 * If not then process this as by closing the contract the user is saying it is complete
	 *  If work done on the contract is a write off then the user must also write off the stock of the contract item as a separate job
	 */

		$result =DB_query("SELECT qtyrecd FROM woitems
							WHERE stockid='" . $_SESSION['Contract'.$identifier]->ContractRef . "'
							AND wo='" . $_SESSION['Contract'.$identifier]->WO . "'");
		if (DB_num_rows($result)==1) {
			$myrow=DB_fetch_row($result);
			if ($myrow[0]==0){ //then the contract wo has not been received (it will only ever be for 1 item)

				$WOReceiptNo = GetNextTransNo(26);

				/* Need to get the current location quantity will need it later for the stock movement */
				$SQL = "SELECT locstock.quantity
						FROM locstock
						WHERE locstock.stockid='" . $_SESSION['Contract'.$identifier]->ContractRef . "'
						AND loccode= '" . $_SESSION['Contract'.$identifier]->LocCode . "'";

				$Result = DB_query($SQL);
				if (DB_num_rows($Result)==1){
					$LocQtyRow = DB_fetch_row($Result);
					$QtyOnHandPrior = $LocQtyRow[0];
				} else {
				/*There must actually be some error this should never happen */
					$QtyOnHandPrior = 0;
				}

				$SQL = "UPDATE locstock
						SET quantity = locstock.quantity + 1
						WHERE locstock.stockid = '" . $_SESSION['Contract'.$identifier]->ContractRef . "'
						AND loccode= '" . $_SESSION['Contract'.$identifier]->LocCode . "'";

				$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
				$DbgMsg =  _('The following SQL to update the location stock record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					/*Insert stock movements - with unit cost */

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
							VALUES ('" . $_SESSION['Contract'.$identifier]->ContractRef . "',
									26,
									'" . $WOReceiptNo . "',
									'"  . $_SESSION['Contract'.$identifier]->LocCode . "',
									'" . Date('Y-m-d') . "',
									'" . $_SESSION['UserID'] . "',
									'" . ($OtherReqtsBudget+$ContractBOMBudget) . "',
									'" . $PeriodNo . "',
									'" .  $_SESSION['Contract'.$identifier]->WO . "',
									1,
									'" .  ($OtherReqtsBudget+$ContractBOMBudget)  . "',
									'" . ($QtyOnHandPrior + 1) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted when processing the work order receipt because');
				$DbgMsg =  _('The following SQL to insert the stock movement records was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				/*Get the ID of the StockMove... */
				$StkMoveNo = DB_Last_Insert_ID('stockmoves','stkmoveno');

				/* If GLLink_Stock then insert GLTrans to debit the GL Code  and credit GRN Suspense account at standard cost*/
				if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND ($OtherReqtsBudget+$ContractBOMBudget)!=0){
				/*GL integration with stock is activated so need the GL journals to make it so */
				/*first the debit the finished stock of the item received from the WO
				  the appropriate account was already retrieved into the $StockGLCode variable as the Processing code is kicked off
				  it is retrieved from the stock category record of the item by a function in SQL_CommonFunctions.inc*/

					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
									VALUES (26,
											'" . $WOReceiptNo . "',
											'" . Date('Y-m-d') . "',
											'" . $PeriodNo . "',
											'" . $GLCodes['stockact'] . "',
											'" . $_SESSION['Contract'.$identifier]->WO . ' ' . $_SESSION['Contract'.$identifier]->ContractRef  . ' -  x 1 @ ' . locale_number_format(($OtherReqtsBudget+$ContractBOMBudget),2) . "',
											'" . ($OtherReqtsBudget+$ContractBOMBudget) . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The receipt of contract work order finished stock GL posting could not be inserted because');
					$DbgMsg = _('The following SQL to insert the work order receipt of finished items GLTrans record was used');
					$Result = DB_query($SQL,$ErrMsg, $DbgMsg, true);

					/*now the credit WIP entry*/
					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
										VALUES (26,
											'" . $WOReceiptNo . "',
											'" . Date('Y-m-d') . "',
											'" . $PeriodNo . "',
											'" . $GLCodes['wipact'] . "',
											'" . $_SESSION['Contract'.$identifier]->WO . ' ' . $_SESSION['Contract'.$identifier]->ContractRef  . ' -  x 1 @ ' . locale_number_format(($OtherReqtsBudget+$ContractBOMBudget),2) . "',
											'" . -($OtherReqtsBudget+$ContractBOMBudget) . "')";

					$ErrMsg =   _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The WIP credit on receipt of finished items from a work order GL posting could not be inserted because');
					$DbgMsg =  _('The following SQL to insert the WIP GLTrans record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg,true);

				} /* end of if GL and stock integrated and standard cost !=0 */

				//update the wo with the new qtyrecd
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('Could not update the work order item record with the total quantity received because');
				$DbgMsg = _('The following SQL was used to update the work order');
				$UpdateWOResult =DB_query("UPDATE woitems
										SET qtyrecd=qtyrecd+1
										WHERE wo='" . $_SESSION['Contract'.$identifier]->WO . "'
										AND stockid='" . $_SESSION['Contract'.$identifier]->ContractRef . "'",
										$ErrMsg,
										$DbgMsg,
										true);
			}//end if the contract wo was not received - work order item received/processed above if not
		}//end if there was a row returned from the woitems query
	} //end if the work order was still open (so end of closing it and processing receipt if necessary)

	DB_Txn_Commit();

	$_SESSION['Contract'.$identifier]->Status=3;
	prnMsg(_('The contract has been closed. No further charges can be posted against this contract.'),'success');

} //end if Closing the contract Close Contract button hit

if ($_SESSION['Contract'.$identifier]->Status ==2){//the contract is an order being processed now

	echo '<form  method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?SelectedContract=' . urlencode($_SESSION['Contract'.$identifier]->ContractRef) . '&amp;identifier=' . urlencode($identifier) . '">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<br />
		<div class="centre">
			<input type="submit" name="CloseContract" value="' . _('Close Contract') .  '" onclick="return confirm(\'' . _('Closing the contract will prevent further stock being issued to it and charges being made against it. Variances will be taken to the profit and loss account. Are You Sure?') . '\');" />
		</div>
        </div>
		</form>';
}

include('includes/footer.php');
?>
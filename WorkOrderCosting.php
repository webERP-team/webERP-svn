<?php

include('includes/session.php');
$Title = _('Work Order Costing');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');


if (isset($_GET['WO'])) {
	$SelectedWO = $_GET['WO'];
} elseif (isset($_POST['WO'])){
	$SelectedWO = $_POST['WO'];
} else {
	unset($SelectedWO);
}

echo '<a href="'. $RootPath . '/SelectWorkOrder.php">' . _('Back to Work Orders'). '</a>
	<br />
	<p class="page_title_text">
		<img src="'.$RootPath.'/css/'.$Theme.'/images/maintenance.png" title="' .
	_('Search') . '" alt="" />' . ' ' . $Title . '
	</p>';

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($SelectedWO)) {
	/* This page can only be called with a work order number */
	echo '<div class="centre><a href="' . $RootPath . '/SelectWorkOrder.php">' .
		_('Select a work order') . '</a></div>';
	prnMsg(_('This page can only be opened if a work order has been selected.'),'info');
	include ('includes/footer.php');
	exit;
} else {
	echo '<input type="hidden" name="WO" value="' .$SelectedWO . '" />';
	$_POST['WO']=$SelectedWO;
}


$ErrMsg = _('Could not retrieve the details of the selected work order');
$WOResult = DB_query("SELECT workorders.loccode,
							locations.locationname,
							workorders.requiredby,
							workorders.startdate,
							workorders.closed,
							closecomments
						FROM workorders INNER JOIN locations
						ON workorders.loccode=locations.loccode
						INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
						WHERE workorders.wo='" . $_POST['WO'] . "'",
						$ErrMsg);

if (DB_num_rows($WOResult)==0){
	prnMsg(_('The selected work order item cannot be retrieved from the database'),'info');
	include('includes/footer.php');
	exit;
}
$WorkOrderRow = DB_fetch_array($WOResult);


echo '<table class="selection">
	<tr>
		<td class="label">' . _('Work order') . ':</td>
		<td>' . $_POST['WO']  . '</td>
	 	<td class="label">' . _('Manufactured at') . ':</td>
		<td>' . $WorkOrderRow['locationname'] . '</td>
		<td class="label">' . _('Required By') . ':</td>
		<td>' . ConvertSQLDate($WorkOrderRow['requiredby']) . '</td>
	</tr>
	</table>
	<br />';


$WOItemsResult = DB_query("SELECT woitems.stockid,
									stockmaster.description,
									stockmaster.decimalplaces,
									stockmaster.units,
									stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS currcost,
									woitems.qtyreqd,
									woitems.qtyrecd,
									woitems.stdcost,
									stockcategory.materialuseagevarac,
									stockcategory.purchpricevaract,
									stockcategory.wipact,
									stockcategory.stockact
							FROM woitems INNER JOIN stockmaster
							ON woitems.stockid=stockmaster.stockid
							INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
							WHERE woitems.wo='". $_POST['WO'] . "'",
							$ErrMsg);

echo  '<table class="selection">
		<tr>
			<th>' . _('Item') . '</th>
			<th>' . _('Description') . '</th>
			<th>' . _('Quantity Required') . '</th>
			<th>' . _('Units') . '</th>
			<th>' . _('Quantity Received') . '</th>
			<th>' . _('Status') . '</th>
			<th>' . _('Receive') . '</th>
			<th>' . _('Issue') . '</th>
		</tr>';

$TotalStdValueRecd =0;
while ($WORow = DB_fetch_array($WOItemsResult)){

	 echo '<tr>
				<td>' . $WORow['stockid'] . '</td>
	 			<td>' . $WORow['description'] . '</td>
	 			<td class="number">' . locale_number_format($WORow['qtyreqd'],$WORow['decimalplaces']) . '</td>
	 			<td>' . $WORow['units'] . '</td>
	 			<td class="number">' . locale_number_format($WORow['qtyrecd'],$WORow['decimalplaces']) . '</td>
	 			<td class="number"><a href="'. $RootPath . '/WorkOrderStatus.php?WO=' . $_POST['WO'] . '&amp;StockID=' . $WORow['stockid'] . '">' . _('Status') . '</a></td>
	 			<td class="number"><a href="'. $RootPath . '/WorkOrderReceive.php?WO=' . $_POST['WO'] . '&amp;StockID=' . $WORow['stockid'] . '">' . _('Receive') . '</a></td>
	 			<td class="number"><a href="'. $RootPath . '/WorkOrderIssue.php?WO=' . $_POST['WO'] . '&amp;StockID=' . $WORow['stockid'] . '">' . _('Issue') . '</a></td>
 			</tr>';

	$TotalStdValueRecd +=($WORow['stdcost']*$WORow['qtyrecd']);

}
echo '</table>
	<br />
	<table class="selection">';

echo '<tr>
		<th>' . _('Item') . '</th>
		<th>' . _('Description') . '</th>
		<th>' . _('Qty Reqd') . '</th>
		<th>' . _('Cost Reqd') . '</th>
		<th>' . _('Date Issued') . '</th>
		<th>' . _('Issued Qty') . '</th>
		<th>' . _('Issued Cost') . '</th>
		<th>' . _('Usage Variance') . '</th>
		<th>' . _('Cost Variance') . '</th>
	</tr>';

$RequirementsResult = DB_query("SELECT worequirements.stockid,
									   stockmaster.description,
 									   stockmaster.decimalplaces,
 									   worequirements.stdcost,
									   SUM(worequirements.qtypu*woitems.qtyrecd) AS requiredqty,
									   SUM(worequirements.stdcost*worequirements.qtypu*woitems.qtyrecd) AS expectedcost,
									   AVG(worequirements.qtypu) as qtypu
								FROM worequirements INNER JOIN stockmaster
								 ON worequirements.stockid=stockmaster.stockid
								 INNER JOIN woitems ON woitems.stockid=worequirements.parentstockid AND woitems.wo=worequirements.wo
								WHERE worequirements.wo='" . $_POST['WO'] . "'
								GROUP BY worequirements.stockid,
										stockmaster.description,
										stockmaster.decimalplaces,
										worequirements.stdcost
								ORDER BY worequirements.stockid");

$TotalUsageVar =0;
$TotalCostVar =0;
$TotalIssuedCost=0;
$TotalReqdCost=0;
$RequiredItems =array();
$RequiredQty = array();
$RequiredStocks = array();
$CostVariedStocks = array();
$CurrStock = '';
$CurrQty = 0;
while ($RequirementsRow = DB_fetch_array($RequirementsResult)){
	if ($CurrStock == '') {
		$CurrStock = $RequirementsRow['stockid'];
	}
	if ($CurrStock == $RequirementsRow['stockid']) {
		if (in_array($RequirementsRow['stockid'],$RequiredStocks)){
			$CurrQty += $RequirementsRow['requiredqty'];
		} else {
			$CurrQty = $RequirementsRow['requiredqty'];
		}

	} else {//stock changed 
		$CostVariedStocks[] = array('stockid'=>$CurrStock,'totalreqqty'=>$CurrQty);
		$CurrStock = $RequirementsRow['stockid'];
		$CurrQty = $RequirementsRow['requiredqty'];

	}
	$RequiredStocks[] = $RequirementsRow['stockid'];
}

DB_data_seek($RequirementsResult,0);

while ($RequirementsRow = DB_fetch_array($RequirementsResult)){
	$RequiredItems[] = $RequirementsRow['stockid'];

	echo '<tr class="striped_row">
		<td>' .  $RequirementsRow['stockid'] . '</td>
		<td>' .  $RequirementsRow['description'] . '</td>
        </tr>';

	$IssuesResult = DB_query("SELECT stockmoves.trandate,
									stockmoves.qty,
									stockmoves.standardcost,
									stockmaster.decimalplaces
								FROM stockmoves INNER JOIN stockmaster
								ON stockmoves.stockid = stockmaster.stockid
								WHERE stockmoves.type=28
								AND stockmoves.reference = '" . $_POST['WO'] . "'
								AND stockmoves.stockid = '" . $RequirementsRow['stockid'] . "'",
								_('Could not retrieve the issues of the item because:'));
	$IssueQty =0;
	$IssueCost=0;

	if (DB_num_rows($IssuesResult)>0){
		while ($IssuesRow = DB_fetch_array($IssuesResult)){
			echo '<tr class="striped_row">
				<td colspan="4"></td>
				<td>' . ConvertSQLDate($IssuesRow['trandate']) . '</td>
				<td class="number">' . locale_number_format(-$IssuesRow['qty'],$RequirementsRow['decimalplaces']) . '</td>
				<td class="number">' . locale_number_format(-($IssuesRow['qty']*$IssuesRow['standardcost']),$IssuesRow['decimalplaces']) . '</td>
			</tr>';
			$IssueQty -= $IssuesRow['qty'];// because qty for the stock movement will be negative
			$IssueCost -= ($IssuesRow['qty']*$IssuesRow['standardcost']);

		}

		echo '<tr class="striped_row">
				<td colspan="9"><hr /></td>
			</tr>';
	}

	if ($IssueQty != 0){
		$MultiCost = false;
		foreach ($CostVariedStocks as $key=>$value){
			if ($value['stockid'] == $RequirementsRow['stockid']){
				if ($value['totalreqqty']!=0){
					$CostVar = $IssueQty * $RequirementsRow['requiredqty']/$value['totalreqqty']*(($RequirementsRow['stdcost']) -($IssueCost/$IssueQty));
				} else {
					$CostVar = 0;
				}
				$MultiCost = true;
				break;

			}
		}
		if (!$MultiCost){
			$CostVar = $IssueQty *(($RequirementsRow['stdcost']) -($IssueCost/$IssueQty));
		}
	} else {
		$CostVar = 0;
	}
	/*Required quantity is the quantity required of the component based on the quantity of the finished item received */
	$UsageVar =($RequirementsRow['requiredqty']-$IssueQty)*($RequirementsRow['stdcost']);

	echo '<tr class="striped_row">
			<td colspan="2"></td>
			<td class="number">'  . locale_number_format($RequirementsRow['requiredqty'],$RequirementsRow['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format($RequirementsRow['expectedcost'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td></td>
			<td class="number">' . locale_number_format($IssueQty,$RequirementsRow['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format($IssueCost,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format($UsageVar,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format($CostVar,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
		</tr>';
	$TotalReqdCost += $RequirementsRow['expectedcost'];
	$TotalIssuedCost += $IssueCost;
	$TotalCostVar += $CostVar;
	$TotalUsageVar += $UsageVar;

	echo '<tr>
			<td colspan="9"><hr /></td>
		</tr>';
}


//Now need to run through the issues to the work order that weren't in the requirements

$sql = "SELECT stockmoves.stockid,
				stockmaster.description,
				stockmaster.decimalplaces,
				trandate,
				qty,
				stockmoves.standardcost
		FROM stockmoves INNER JOIN stockmaster
		ON stockmoves.stockid=stockmaster.stockid
		WHERE stockmoves.type=28
		AND reference = '" . $_POST['WO'] . "'
		AND stockmoves.stockid NOT IN
					(SELECT worequirements.stockid
						FROM worequirements
					WHERE worequirements.wo='" . $_POST['WO'] . "')";

$WOIssuesResult = DB_query($sql,_('Could not get issues that were not required by the BOM because'));

if (DB_num_rows($WOIssuesResult)>0){
	while ($WOIssuesRow = DB_fetch_array($WOIssuesResult)){

		echo '<tr class="striped_row">
				<td>' .  $WOIssuesRow['stockid'] . '</td>
				<td>' .  $WOIssuesRow['description'] . '</td>
				<td class="number">0</td>
				<td class="number">0</td>
				<td>' . ConvertSQLDate($WOIssuesRow['trandate']) . '</td>
				<td class="number">' . locale_number_format(-$WOIssuesRow['qty'],$WOIssuesRow['decimalplaces'])   . '</td>
				<td class="number">' . locale_number_format(-$WOIssuesRow['qty']*$WOIssuesRow['standardcost'],$_SESSION['CompanyRecord']['decimalplaces'])   . '</td>
				<td class="number">' . locale_number_format($WOIssuesRow['qty']*$WOIssuesRow['standardcost'],$_SESSION['CompanyRecord']['decimalplaces'])   . '</td>
				<td class="number">0</td>
			</tr>';

		$TotalUsageVar += ($WOIssuesRow['qty']*$WOIssuesRow['standardcost']);
	}
}
# <!--	<td colspan="5"></td> -->
echo '<tr>
		<td colspan="3"></td>
		<td><hr/></td>
		<td colspan="2"></td>
		<td colspan="3"><hr /></td>
	</tr>';
echo '<tr>
		<td colspan="2" class="number">' . _('Totals') . '</td>
		<td></td>
		<td class="number">' . locale_number_format($TotalReqdCost,$_SESSION['CompanyRecord']['decimalplaces'])  . '</td>
		<td></td><td></td>
		<td class="number">' . locale_number_format($TotalIssuedCost,$_SESSION['CompanyRecord']['decimalplaces'])  . '</td>
		<td class="number">' . locale_number_format($TotalUsageVar,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
		<td class="number">' . locale_number_format($TotalCostVar,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
	</tr>';

echo '<tr>
		<td colspan="3"></td>
		<td><hr/></td>
		<td colspan="2"></td>
		<td colspan="3"><hr /></td>
	</tr>';

If (isset($_POST['Close'])) {

	DB_data_seek($WOItemsResult,0);
	$NoItemsOnWO = DB_num_rows($WOItemsResult);
	$TotalVariance = $TotalUsageVar + $TotalCostVar;
	$PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']));
	$WOCloseNo = GetNextTransNo(29);
	$TransResult = DB_Txn_Begin();

	while ($WORow = DB_fetch_array($WOItemsResult)){
		if ($TotalStdValueRecd==0){
			$ShareProportion = 1/$NoItemsOnWO;
		} else {
			$ShareProportion = ($WORow['stdcost']*$WORow['qtyrecd'])/$TotalStdValueRecd;
		}
 		if ($_SESSION['WeightedAverageCosting']==1){
			//we need to post the variances to stock and update the weighted average cost

			/*  need to get the current total quantity on hand
			if the quantity on hand is less than the quantity received on the work order
			then some of the variance needs to be written off to P & L and only the proportion
			of the variance relating to the stock still on hand should be posted to the stock value
			*/

			$TotOnHandResult =DB_query("SELECT SUM(quantity)
										FROM locstock
										WHERE stockid='" . $WORow['stockid'] . "'");
			$TotOnHandRow = DB_fetch_row($TotOnHandResult);
			$TotalOnHand = $TotOnHandRow[0];

			if ($TotalOnHand >= $WORow['qtyrecd']){
				$ProportionOnHand = 1;
			}else {
				$ProportionOnHand = 1 - (($WORow['qtyrecd']- $TotalOnHand)/$WORow['qtyrecd']);
			}

			if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $TotalVariance!=0){

				//need to get the current cost of the item
				if ($ProportionOnHand < 1){

					$SQL = "INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
									VALUES (29,
										'" . $WOCloseNo . "',
										'" . Date('Y-m-d') . "',
										'" . $PeriodNo . "',
										'" . $WORow['materialuseagevarac'] . "',
										'" . $_POST['WO'] . ' - ' . $WORow['stockid'] . ' ' . _('share of variance') . "',
										'" .round((-$TotalCostVar*$ShareProportion*(1-$ProportionOnHand)),$_SESSION['CompanyRecord']['decimalplaces']) . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL posting for the work order variance could not be inserted because');
					$DbgMsg = _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
				}


				$SQL = "INSERT INTO gltrans (type,
							typeno,
							trandate,
							periodno,
							account,
							narrative,
							amount)
						VALUES (29,
							'" . $WOCloseNo . "',
							'" . Date('Y-m-d') . "',
							'" . $PeriodNo . "',
							'" . $WORow['stockact'] . "',
							'" . $_POST['WO'] . ' - ' . $WORow['stockid'] . ' ' . _('share of variance') . "',
							'" . round((-$TotalCostVar*$ShareProportion*$ProportionOnHand),$_SESSION['CompanyRecord']['decimalplaces']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL posting for the work order variance could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				$SQL = "INSERT INTO gltrans (type,
							typeno,
							trandate,
							periodno,
							account,
							narrative,
							amount)
						VALUES (29,
							'" . $WOCloseNo . "',
							'" . Date('Y-m-d') . "',
							'" . $PeriodNo . "',
							'" . $WORow['wipact'] . "',
							'" . $_POST['WO'] . ' - ' . $WORow['stockid'] . ' ' . _('share of variance') . "',
							'" . round(($TotalCostVar*$ShareProportion),$_SESSION['CompanyRecord']['decimalplaces']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL posting for the WIP side of the work order variance posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			}
			if ($TotalOnHand>0) {//to avoid negative quantity make cost data abnormal
				$NewCost = $WORow['currcost'] +(-$TotalCostVar* $ShareProportion *$ProportionOnHand)/$TotalOnHand;
			} else {
				$NewCost = $WORow['currcost'];
			}

			$SQL = "UPDATE stockmaster SET
						materialcost='" . $NewCost . "',
						labourcost=0,
						overheadcost=0,
						lastcost='" . $WORow['currcost'] . "',
						lastcostupdate = '" . Date('Y-m-d') . "'
					WHERE stockid='" . $WORow['stockid'] . "'";

			$ErrMsg = _('The cost details for the stock item could not be updated because');
			$DbgMsg = _('The SQL that failed was');
			$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		} else { //we are standard costing post the variances
			if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $TotalUsageVar!=0){

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
						VALUES (29,
							'" . $WOCloseNo . "',
							'" . Date('Y-m-d') . "',
							'" . $PeriodNo . "',
							'" . $WORow['materialuseagevarac'] . "',
							'" . $_POST['WO'] . ' - ' . $WORow['stockid'] . ' ' . _('share of usage variance') . "',
							'" . round((-$TotalUsageVar*$ShareProportion),$_SESSION['CompanyRecord']['decimalplaces']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL posting for the material usage variance could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
						VALUES (29,
							'" . $WOCloseNo . "',
							'" . Date('Y-m-d') . "',
							'" . $PeriodNo . "',
							'" . $WORow['wipact'] . "',
							'" . $_POST['WO'] . ' - ' . $WORow['stockid'] . ' ' . _('share of usage variance') . "',
							'" . round(($TotalUsageVar*$ShareProportion),$_SESSION['CompanyRecord']['decimalplaces']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL posting for the WIP side of the usage variance posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			}//end if gl-stock linked and a usage variance exists

			if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $TotalCostVar!=0){

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
							amount)
						VALUES (29,
							'" . $WOCloseNo . "',
							'" . Date('Y-m-d') . "',
							'" . $PeriodNo . "',
							'" . $WORow['purchpricevaract'] . "',
							'" . $_POST['WO'] . ' - ' . $WORow['stockid'] . ' ' . _('share of cost variance') . "',
							'" . round((-$TotalCostVar*$ShareProportion),$_SESSION['CompanyRecord']['decimalplaces']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL posting for the cost variance could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
						VALUES (29,
							'" . $WOCloseNo . "',
							'" . Date('Y-m-d') . "',
							'" . $PeriodNo . "',
							'" . $WORow['wipact'] . "',
							'" . $_POST['WO'] . ' - ' . $WORow['stockid'] . ' ' . _('share of cost variance') . "',
							'" . round(($TotalCostVar*$ShareProportion),$_SESSION['CompanyRecord']['decimalplaces']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The GL posting for the WIP side of the cost variance posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			} //end of if gl-stock integrated and there's a cost variance
		} //end of standard costing section
	} // end loop around the items on the work order

	$CloseWOResult =DB_query("UPDATE workorders SET closed=1, closecomments = '". $_POST['CloseComments'] ."' WHERE wo='" .$_POST['WO'] . "'",
				_('Could not update the work order to closed because:'),
				_('The SQL used to close the work order was:'),
				true);
	$DeleteAnyWOSerialNos = DB_query("DELETE FROM woserialnos WHERE wo='" . $_POST['WO'] . "'",
									_('Could not delete the predefined work order serial numbers'),
									_('The SQL used to delete the predefined serial numbers was:'),
									true);
	$TransResult = DB_Txn_Commit();
	if ($_SESSION['CompanyRecord']['gllink_stock']==1){
		if ($_SESSION['WeightedAverageCosting']==1){
			prnMsg(_('The item cost as calculated from the work order has been applied against the weighted average cost and the necessary GL journals created to update stock as a result of closing this work order'),'success');
		} else {
			prnMsg(_('The work order has been closed and general ledger entries made for the variances on the work order'),'success');
		}
	} else {
		if ($_SESSION['WeightedAverageCosting']==1){
			prnMsg(_('The item costs resulting from the work order have been applied against the weighted average stock value of the items on the work order, and the work order has been closed'),'success');
		} else {
			prnMsg(_('The work order has been closed'),'success');
		}
	}
	$WorkOrderRow['closed']=1;
}//end close button hit by user

if ($WorkOrderRow['closed']==0){
	$ReadOnly='';
} else{
	$ReadOnly='readonly';
	if (!isset($_POST['CloseComments'])) {
		$_POST['CloseComments'] = $WorkOrderRow['closecomments'];
	}
}

echo 	'<tr>
			<td colspan="9">
				<div class="centre">
					<textarea ' . $ReadOnly . ' style="width:100%" rows="5" cols="80" name="CloseComments" >' . $_POST['CloseComments'] . '</textarea>
				</div>
			</td>
		</tr>';

if ($WorkOrderRow['closed']==0){
	echo '<tr>
			<td colspan="9">
				<div class="centre">
					<input type="submit" name="Close" value="' . _('Close This Work Order') . '" onclick="return confirm(\'' . _('Closing the work order takes the variances to the general ledger (if integrated). The work order will no longer be able to have manufactured goods received entered against it or materials issued to it.') . '  ' . _('Are You Sure?') . '\');" />
				</div>
			</td>
		</tr>';
} else {
	echo '<tr>
			<td colspan="9">' . _('This work order is closed and cannot accept additional issues of materials or receipts of manufactured items') . '</td>
		</tr>';
}
echo '</table>
	</div>
	</form>';

include('includes/footer.php');
?>

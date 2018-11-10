<?php
// MRPPlannedWorkOrders.php - Report of manufactured parts that MRP has determined should have
// work orders created for them

include('includes/session.php');

if ( !DB_table_exists('mrprequirements') ) {
	$Title=_('MRP error');
	include('includes/header.php');
	echo '<br />';
	prnMsg( _('The MRP calculation must be run before you can run this report') . '<br />' .
			_('To run the MRP calculation click') . ' ' . '<a href="' . $RootPath . '/MRP.php">' . _('here') . '</a>', 'error');
	include('includes/footer.php');
	exit;
}

if ( isset($_POST['PrintPDF']) OR isset($_POST['Review']) ) {

	$WhereDate = ' ';
	$ReportDate = ' ';
	if (Is_Date($_POST['cutoffdate'])) {
		$FormatDate = FormatDateForSQL($_POST['cutoffdate']);
		$WhereDate = " AND duedate <= '" . $FormatDate . "' ";
		$ReportDate = ' ' . _('Through') . ' ' . $_POST['cutoffdate'];
	}

	if ($_POST['Consolidation'] == 'None') {
		$sql = "SELECT mrpplannedorders.*,
					   stockmaster.stockid,
					   stockmaster.description,
					   stockmaster.mbflag,
					   stockmaster.decimalplaces,
					   stockmaster.actualcost,
					(stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost ) as computedcost
				FROM mrpplannedorders
				INNER JOIN stockmaster
					ON mrpplannedorders.part = stockmaster.stockid
				WHERE stockmaster.mbflag = 'M' " . $WhereDate . "
				ORDER BY mrpplannedorders.part,mrpplannedorders.duedate";
	} elseif ($_POST['Consolidation'] == 'Weekly') {
		$sql = "SELECT mrpplannedorders.part,
					   SUM(mrpplannedorders.supplyquantity) as supplyquantity,
					   TRUNCATE(((TO_DAYS(duedate) - TO_DAYS(CURRENT_DATE)) / 7),0) AS weekindex,
					   MIN(mrpplannedorders.duedate) as duedate,
					   MIN(mrpplannedorders.mrpdate) as mrpdate,
					   COUNT(*) AS consolidatedcount,
					   stockmaster.stockid,
					   stockmaster.description,
					   stockmaster.mbflag,
					   stockmaster.decimalplaces,
					   stockmaster.actualcost,
					(stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost ) as computedcost
				FROM mrpplannedorders
				INNER JOIN stockmaster
					ON mrpplannedorders.part = stockmaster.stockid
				WHERE stockmaster.mbflag = 'M' " . $WhereDate . "
				GROUP BY mrpplannedorders.part,
						 weekindex,
						 stockmaster.stockid,
						 stockmaster.description,
						 stockmaster.mbflag,
						 stockmaster.decimalplaces,
						 stockmaster.actualcost,
						 stockmaster.materialcost,
						 stockmaster.labourcost,
						 stockmaster.overheadcost,
						 computedcost
				ORDER BY mrpplannedorders.part,weekindex";
	} else {  // This else consolidates by month
		$sql = "SELECT mrpplannedorders.part,
					   SUM(mrpplannedorders.supplyquantity) as supplyquantity,
					   EXTRACT(YEAR_MONTH from duedate) AS yearmonth,
					   MIN(mrpplannedorders.duedate) as duedate,
					   MIN(mrpplannedorders.mrpdate) as mrpdate,
					   COUNT(*) AS consolidatedcount,
					   	stockmaster.stockid,
					   stockmaster.description,
					   stockmaster.mbflag,
					   stockmaster.decimalplaces,
					   stockmaster.actualcost,
					(stockmaster.materialcost + stockmaster.labourcost + stockmaster.overheadcost ) as computedcost
				FROM mrpplannedorders
				INNER JOIN stockmaster
					ON mrpplannedorders.part = stockmaster.stockid
				WHERE stockmaster.mbflag = 'M' " . $WhereDate . "
				GROUP BY mrpplannedorders.part,
						 yearmonth,
					   	 stockmaster.stockid,
						 stockmaster.description,
						 stockmaster.mbflag,
						 stockmaster.decimalplaces,
						 stockmaster.actualcost,
						 stockmaster.materialcost,
						 stockmaster.labourcost,
						 stockmaster.overheadcost,
						 computedcost
				ORDER BY mrpplannedorders.part,yearmonth";
	}
	$result = DB_query($sql,'','',false,true);

	if (DB_error_no() !=0) {
	  $Title = _('MRP Planned Work Orders') . ' - ' . _('Problem Report');
	  include('includes/header.php');
	   prnMsg( _('The MRP planned work orders could not be retrieved by the SQL because') . ' '  . DB_error_msg(),'error');
	   echo '<br /><a href="' .$RootPath .'/index.php">' . _('Back to the menu') . '</a>';
	   if ($debug==1){
		  echo '<br />' . $sql;
	   }
	   include('includes/footer.php');
	   exit;
	}

	if (DB_num_rows($result)==0){ //then there is nothing to print
		$Title = _('MRP Planned Work Orders');
		include('includes/header.php');
		prnMsg(_('There were no items with demand greater than supply'),'info');
		echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a>';
		include('includes/footer.php');
		exit;
	}

	if (isset($_POST['PrintPDF'])) { // Print planned work orders

		include('includes/PDFStarter.php');

		$pdf->addInfo('Title',_('MRP Planned Work Orders Report'));
		$pdf->addInfo('Subject',_('MRP Planned Work Orders'));

		$FontSize=9;
		$PageNumber=1;
		$line_height=12;
		$Xpos = $Left_Margin+1;

		PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
					$Page_Width,$Right_Margin,$_POST['Consolidation'],$ReportDate);

		$Partctr = 0;
		$fill = false;
		$pdf->SetFillColor(224,235,255);  // Defines color to make alternating lines highlighted
		$FontSize=8;
		$HoldPart = ' ';
		$HoldDescription = ' ';
		$HoldMBFlag = ' ';
		$HoldCost = ' ';
		$HoldDecimalPlaces = 0;
		$TotalPartQty = 0;
		$TotalPartCost = 0;
		$Total_ExtCost = 0;

		while ($myrow = DB_fetch_array($result)){
				$YPos -=$line_height;

				// Print information on part break
				if ($Partctr > 0 AND $HoldPart != $myrow['part']) {
					$pdf->addTextWrap(50,$YPos,130,$FontSize,$HoldDescription,'',0,$fill);
					$pdf->addTextWrap(180,$YPos,50,$FontSize,_('Unit Cost: '),'center',0,$fill);
					$pdf->addTextWrap(220,$YPos,40,$FontSize,locale_number_format($HoldCost,$_SESSION['CompanyRecord']['decimalplaces']),'right',0,$fill);
					$pdf->addTextWrap(260,$YPos,50,$FontSize,locale_number_format($TotalPartQty, $HoldDecimalPlaces),'right',0,$fill);
					$pdf->addTextWrap(310,$YPos,60,$FontSize,locale_number_format($TotalPartCost,$_SESSION['CompanyRecord']['decimalplaces']),'right',0,$fill);
					$pdf->addTextWrap(370,$YPos,30,$FontSize,_('M/B: '),'right',0,$fill);
					$pdf->addTextWrap(400,$YPos,15,$FontSize,$HoldMBFlag,'right',0,$fill);
					$TotalPartCost = 0;
					$TotalPartQty = 0;
					$YPos -= (2*$line_height);

					// Use to alternate between lines with transparent and painted background
					if ($_POST['Fill'] == 'yes'){
						$fill=!$fill;
					}
				}

				// Parameters for addTextWrap are defined in /includes/class.pdf.php
				$FormatedSupDueDate = ConvertSQLDate($myrow['duedate']);
				$FormatedSupMRPDate = ConvertSQLDate($myrow['mrpdate']);
				$ExtCost = $myrow['supplyquantity'] * $myrow['computedcost'];
				$pdf->addTextWrap($Left_Margin,$YPos,110,$FontSize,$myrow['part'],'',0,$fill);
				$pdf->addTextWrap(150,$YPos,50,$FontSize,$FormatedSupDueDate,'right',0,$fill);
				$pdf->addTextWrap(200,$YPos,60,$FontSize,$FormatedSupMRPDate,'right',0,$fill);
				$pdf->addTextWrap(260,$YPos,50,$FontSize,locale_number_format($myrow['supplyquantity'], $myrow['decimalplaces']),'right',0,$fill);
				$pdf->addTextWrap(310,$YPos,60,$FontSize,locale_number_format($ExtCost,$_SESSION['CompanyRecord']['decimalplaces']),'right',0,$fill);

				if ($_POST['Consolidation'] == 'None'){
					$pdf->addTextWrap(370,$YPos,80,$FontSize,$myrow['ordertype'],'right',0,$fill);
					$pdf->addTextWrap(450,$YPos,80,$FontSize,$myrow['orderno'],'right',0,$fill);
				} else {
					$pdf->addTextWrap(370,$YPos,100,$FontSize,$myrow['consolidatedcount'],'right',0,$fill);
				}

				$HoldDescription = $myrow['description'];
				$HoldPart = $myrow['part'];
				$HoldMBFlag = $myrow['mbflag'];
				$HoldCost = $myrow['computedcost'];
				$HoldDecimalPlaces = $myrow['decimalplaces'];
				$TotalPartCost += $ExtCost;
				$TotalPartQty += $myrow['supplyquantity'];

				$Total_ExtCost += $ExtCost;
				$Partctr++;

				if ($YPos < $Bottom_Margin + $line_height){
				   PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
							   $Right_Margin,$_POST['Consolidation'],$ReportDate);
				}
		} /*end while loop */

		// Print summary information for last part
		$YPos -=$line_height;
		$pdf->addTextWrap(50,$YPos,130,$FontSize,$HoldDescription,'',0,$fill);
		$pdf->addTextWrap(180,$YPos,50,$FontSize,_('Unit Cost: '),'center',0,$fill);
		$pdf->addTextWrap(220,$YPos,40,$FontSize,locale_number_format($HoldCost,$_SESSION['CompanyRecord']['decimalplaces']),'right',0,$fill);
		$pdf->addTextWrap(260,$YPos,50,$FontSize,locale_number_format($TotalPartQty,$HoldDecimalPlaces),'right',0,$fill);
		$pdf->addTextWrap(310,$YPos,60,$FontSize,locale_number_format($TotalPartCost,$_SESSION['CompanyRecord']['decimalplaces']),'right',0,$fill);
		$pdf->addTextWrap(370,$YPos,30,$FontSize,_('M/B: '),'right',0,$fill);
		$pdf->addTextWrap(400,$YPos,15,$FontSize,$HoldMBFlag,'right',0,$fill);
		$FontSize =8;
		$YPos -= (2*$line_height);

		if ($YPos < $Bottom_Margin + $line_height){
			   PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
						   $Right_Margin,$_POST['Consolidation'],$ReportDate);
		}

		/*Print out the grand totals */
		$pdf->addTextWrap($Left_Margin,$YPos,120,$FontSize,_('Number of Work Orders: '), 'left');
		$pdf->addTextWrap(150,$YPos,30,$FontSize,$Partctr, 'left');
		$pdf->addTextWrap(200,$YPos,100,$FontSize,_('Total Extended Cost:'), 'right');
		$DisplayTotalVal = locale_number_format($Total_ExtCost,$_SESSION['CompanyRecord']['decimalplaces']);
		$pdf->addTextWrap(310,$YPos,60,$FontSize,$DisplayTotalVal, 'right');

		$pdf->OutputD($_SESSION['DatabaseName'] . '_MRP_Planned_Work_Orders_' . Date('Y-m-d') . '.pdf');
		$pdf->__destruct();

	} else { // Review planned work orders

		$Title = _('Review/Convert MRP Planned Work Orders');
		include('includes/header.php');
		echo '<p class="page_title_text">
				<img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Inventory') . '" alt="" />' . ' ' . $Title . '</p>';

		echo '<form action="MRPConvertWorkOrders.php" method="post">
			<div>
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<table class="selection">
			<tr>
				<th colspan="9">
					<h3>' . _('Consolidation') . ': ' . $_POST['Consolidation'] . '&nbsp;&nbsp;&nbsp;&nbsp;'
						  . _('Cutoff Date') . ': ' . $_POST['cutoffdate'] . '</h3>
				</th>
			</tr>
			<tr>
				<th></th>
				<th>' . _('Code') . '</th>
				<th>' . _('Description') . '</th>
				<th>' . _('MRP Date') . '</th>
				<th>' . _('Due Date') . '</th>
				<th>' . _('Quantity') . '</th>
				<th>' . _('Unit Cost') . '</th>
				<th>' . _('Ext. Cost') . '</th>';
			if ($_POST['Consolidation'] != 'None') {
				echo '<th>' . _('Consolidations') . '</th>';
			}
		echo '</tr>';

		$TotalPartQty = 0;
		$TotalPartCost = 0;
		$Total_ExtCost = 0;
		$j=1; //row ID

		while ($myrow = DB_fetch_array($result)){

			echo '<tr class="striped_row">
				<td><a href="' . $RootPath . '/WorkOrderEntry.php?NewItem=' . urlencode($myrow['part']) . '&amp;ReqQty=' . urlencode($myrow['supplyquantity']) . '&amp;ReqDate=' . urlencode($myrow['duedate']) . '&amp;StartDate=' . urlencode($myrow['mrpdate']) . '">' . _('Convert') . '</a></td>
				<td>' . '<a href="' . $RootPath . '/SelectProduct.php?StockID=' . urlencode($myrow['part']) . '">' . $myrow['part'] . '</a>' .  '<input type="hidden" name="' . $j . '_part" value="' . $myrow['part']. '" /></td>
				<td>' . $myrow['description'] . '</td>
				<td>' . ConvertSQLDate($myrow['mrpdate']) . '</td>
				<td>' . ConvertSQLDate($myrow['duedate']) . '</td>
				<td class="number">' . locale_number_format($myrow['supplyquantity'],$myrow['decimalplaces']) . '</td>
				<td class="number">' . locale_number_format($myrow['computedcost'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				<td class="number">' . locale_number_format($myrow['supplyquantity'] * $myrow['computedcost'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>';

			if ($_POST['Consolidation'] != 'None') {
				echo '<td class="number">' . $myrow['consolidatedcount'] . '</td>';
			}
			echo '</tr>';

			$j++;
			$Total_ExtCost += ( $myrow['supplyquantity'] * $myrow['computedcost'] );

		} // end while loop

		// Print out the grand totals
		echo '<tr>
				<td colspan="3" class="number">' . _('Number of Work Orders') .': ' . ($j-1) . '</td>
				<td colspan="5" class="number">' . _('Total Extended Cost') . ': ' . locale_number_format($Total_ExtCost,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>
			</tr>
			</table>
			</div>
              </form>';

		echo '<br /><a class="noprint" href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">', _('Select different criteria.'), '</a>';
		include('includes/footer.php');

	} // end Review planned work orders
} else { /*The option to print PDF was not hit so display form */

	$Title=_('MRP Planned Work Orders Reporting');
	include('includes/header.php');
	echo '<p class="page_title_text">
			<img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Inventory') . '" alt="" />' . ' ' . $Title . '</p>';

	echo '<br /><br />
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
		<div>
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<table class="selection">
			<tr>
                  <td>' . _('Consolidation') . ':</td>
				<td>
					<select required="required" name="Consolidation">
                         <option selected="selected" value="None">' . _('None') . '</option>
                         <option value="Weekly">' . _('Weekly') . '</option>
                         <option value="Monthly">' . _('Monthly') . '</option>
					</select>
				</td>
             </tr>
             <tr>
                 <td>' . _('Print Option') . ':</td>
				<td>
					<select name="Fill">
                       <option selected="selected" value="yes">' . _('Print With Alternating Highlighted Lines') . '</option>
                       <option value="no">' . _('Plain Print') . '</option>
					</select>
				</td>
             </tr>
             <tr>
                 <td>' . _('Cut Off Date') . ':</td>
				<td>
					<input type="text" required="required" class="date" name="cutoffdate" autofocus="autofocus" maxlength="10" size="11" value="' . date($_SESSION['DefaultDateFormat']) . '" />
				</td>
	     </tr>
             </table>
             <div class="centre">
                  <input type="submit" name="Review" value="' . _('Review') . '" /> <input type="submit" name="PrintPDF" value="' . _('Print PDF') . '" />
             </div>
             </div>
          </form>';

	include('includes/footer.php');

} /*end of else not PrintPDF */

function PrintHeader(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
					 $Page_Width,$Right_Margin,$consolidation,$ReportDate) {

	/*PDF page header for MRP Planned Work Orders report */
	if ($PageNumber>1){
		$pdf->newPage();
	}
	$line_height=12;
	$FontSize=9;
	$YPos= $Page_Height-$Top_Margin;

	$pdf->addTextWrap($Left_Margin,$YPos,300,$FontSize,$_SESSION['CompanyRecord']['coyname']);

	$YPos -=$line_height;

	$pdf->addTextWrap($Left_Margin,$YPos,150,$FontSize,_('MRP Planned Work Orders Report'));
	$pdf->addTextWrap(190,$YPos,100,$FontSize,$ReportDate);
	$pdf->addTextWrap($Page_Width-$Right_Margin-150,$YPos,160,$FontSize,_('Printed') . ': ' .
		 Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber,'left');
	$YPos -= $line_height;

	if ($consolidation == 'None') {
		$displayconsolidation = _('None');
	} elseif ($consolidation == 'Weekly') {
		$displayconsolidation = _('Weekly');
	} else {
		$displayconsolidation = _('Monthly');
	}

	$pdf->addTextWrap($Left_Margin,$YPos,65,$FontSize,_('Consolidation').':');
	$pdf->addTextWrap(110,$YPos,40,$FontSize,$displayconsolidation);

	$YPos -=(2*$line_height);

	/*set up the headings */
	$Xpos = $Left_Margin+1;

	$pdf->addTextWrap($Xpos,$YPos,150,$FontSize,_('Part Number'), 'left');
	$pdf->addTextWrap(150,$YPos,50,$FontSize,_('Due Date'), 'right');
	$pdf->addTextWrap(200,$YPos,60,$FontSize,_('MRP Date'), 'right');
	$pdf->addTextWrap(260,$YPos,50,$FontSize,_('Quantity'), 'right');
	$pdf->addTextWrap(310,$YPos,60,$FontSize,_('Ext. Cost'), 'right');

	if ($consolidation == 'None') {
		$pdf->addTextWrap(370,$YPos,80,$FontSize,_('Source Type'), 'right');
		$pdf->addTextWrap(450,$YPos,80,$FontSize,_('Source Order'), 'right');
	} else {
		$pdf->addTextWrap(370,$YPos,100,$FontSize,_('Consolidation Count'), 'right');
	}

	$FontSize=8;
	$YPos =$YPos - (2*$line_height);
	$PageNumber++;
} // End of PrintHeader function
?>
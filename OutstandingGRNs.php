<?php


include('includes/session.php');

if (isset($_POST['FromCriteria'])
	AND mb_strlen($_POST['FromCriteria'])>=1
	AND isset($_POST['ToCriteria'])
	AND mb_strlen($_POST['ToCriteria'])>=1){

/*Now figure out the data to report for the criteria under review */

	$SQL = "SELECT grnno,
					purchorderdetails.orderno,
					grns.supplierid,
					suppliers.suppname,
					grns.itemcode,
					grns.itemdescription,
					qtyrecd,
					quantityinv,
					grns.stdcostunit,
					actprice,
					unitprice,
					suppliers.currcode,
					currencies.rate,
					currencies.decimalplaces as currdecimalplaces,
					stockmaster.decimalplaces as itemdecimalplaces
				FROM grns INNER JOIN purchorderdetails
				ON grns.podetailitem = purchorderdetails.podetailitem
				INNER JOIN suppliers
				ON grns.supplierid=suppliers.supplierid
				INNER JOIN currencies
				ON suppliers.currcode=currencies.currabrev
				LEFT JOIN stockmaster
				ON grns.itemcode=stockmaster.stockid
				WHERE qtyrecd-quantityinv>0
				AND grns.supplierid >='" . $_POST['FromCriteria'] . "'
				AND grns.supplierid <='" . $_POST['ToCriteria'] . "'
				ORDER BY supplierid,
					grnno";

	$GRNsResult = DB_query($SQL,'','',false,false);

	if (DB_error_no() !=0) {
	  $Title = _('Outstanding GRN Valuation') . ' - ' . _('Problem Report');
	  include('includes/header.php');
	  prnMsg(_('The outstanding GRNs valuation details could not be retrieved by the SQL because') . ' - ' . DB_error_msg(),'error');
	   echo '<br /><a href="' .$RootPath .'/index.php">' . _('Back to the menu') . '</a>';
		/*
	   if ($debug==1){
		  echo '<br />' . $SQL;
	   }
	   * */
	   include('includes/footer.php');
	   exit;
	}
	if (DB_num_rows($GRNsResult) == 0) {
	  $Title = _('Outstanding GRN Valuation') . ' - ' . _('Problem Report');
	  include('includes/header.php');
	  prnMsg(_('No outstanding GRNs valuation details retrieved'), 'warn');
	   echo '<br /><a href="' .$RootPath .'/index.php">' . _('Back to the menu') . '</a>';
		/*
	   if ($debug==1){
		  echo '<br />' . $SQL;
	   }
	   * */
	   include('includes/footer.php');
	   exit;
	}
}


If (isset($_POST['PrintPDF']) AND DB_num_rows($GRNsResult)>0){

	include('includes/PDFStarter.php');
	$pdf->addInfo('Title',_('Outstanding GRNs Report'));
	$pdf->addInfo('Subject',_('Outstanding GRNs Valuation'));
	$FontSize=10;
	$PageNumber=1;
	$line_height=12;
	$Left_Margin=30;

	include ('includes/PDFOstdgGRNsPageHeader.inc');

	$Tot_Val=0;
	$Supplier = '';
	$SuppTot_Val=0;
	While ($GRNs = DB_fetch_array($GRNsResult)){

		if ($Supplier!=$GRNs['supplierid']){

			if ($Supplier!=''){ /*Then it's NOT the first time round */
				/* need to print the total of previous supplier */
               if ($YPos < $Bottom_Margin + $line_height * 5){
                  include('includes/PDFOstdgGRNsPageHeader.inc');
               }
				$YPos -= (2*$line_height);
				$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,260-$Left_Margin,$FontSize,_('Total for') . ' ' . $Supplier . ' - ' . $SupplierName);
				$DisplaySuppTotVal = locale_number_format($SuppTot_Val,$GRNs['currdecimalplaces']);
				$LeftOvers = $pdf->addTextWrap(500,$YPos,60,$FontSize,$DisplaySuppTotVal, 'right');
				$YPos -=$line_height;
				$pdf->line($Left_Margin, $YPos+$line_height-2,$Page_Width-$Right_Margin, $YPos+$line_height-2);
				$YPos -=(2*$line_height);
				$SuppTot_Val=0;
			}
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,260-$Left_Margin,$FontSize,$GRNs['supplierid'] . ' - ' . $GRNs['suppname']);
			$Supplier = $GRNs['supplierid'];
			$SupplierName = $GRNs['suppname'];
		}
		$YPos -=$line_height;

		if ($GRNs['itemdecimalplaces']==null){
			$ItemDecimalPlaces = 2;
		} else {
			$ItemDecimalPlaces = $GRNs['itemdecimalplaces'];
		}
		$LeftOvers = $pdf->addTextWrap(32,$YPos,40,$FontSize,$GRNs['grnno']);
		$LeftOvers = $pdf->addTextWrap(70,$YPos,40,$FontSize,$GRNs['orderno']);
		$LeftOvers = $pdf->addTextWrap(110,$YPos,200,$FontSize,$GRNs['itemcode'] . ' - ' . $GRNs['itemdescription']);
		$DisplayStdCost = locale_number_format($GRNs['stdcostunit'],$_SESSION['CompanyRecord']['decimalplaces']);
		$DisplayQtyRecd = locale_number_format($GRNs['qtyrecd'],$ItemDecimalPlaces);
		$DisplayQtyInv = locale_number_format($GRNs['quantityinv'],$ItemDecimalPlaces);
		$DisplayQtyOstg = locale_number_format($GRNs['qtyrecd']- $GRNs['quantityinv'],$ItemDecimalPlaces);
		$LineValue = ($GRNs['qtyrecd']- $GRNs['quantityinv'])*$GRNs['stdcostunit'];
		$DisplayValue = locale_number_format($LineValue,$_SESSION['CompanyRecord']['decimalplaces']);

		$LeftOvers = $pdf->addTextWrap(310,$YPos,50,$FontSize,$DisplayQtyRecd,'right');
		$LeftOvers = $pdf->addTextWrap(360,$YPos,50,$FontSize,$DisplayQtyInv, 'right');
		$LeftOvers = $pdf->addTextWrap(410,$YPos,50,$FontSize,$DisplayQtyOstg, 'right');
		$LeftOvers = $pdf->addTextWrap(460,$YPos,50,$FontSize,$DisplayStdCost, 'right');
		$LeftOvers = $pdf->addTextWrap(510,$YPos,50,$FontSize,$DisplayValue, 'right');

		$Tot_Val += $LineValue;
		$SuppTot_Val += $LineValue;

		if ($YPos < $Bottom_Margin + $line_height){
		   include('includes/PDFOstdgGRNsPageHeader.inc');
		}

	} /*end while loop */


/*Print out the supplier totals */
	$YPos -=$line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,260-$Left_Margin,$FontSize,_('Total for') . ' ' . $Supplier . ' - ' . $SupplierName, 'left');

	$DisplaySuppTotVal = locale_number_format($SuppTot_Val,2);
	$LeftOvers = $pdf->addTextWrap(500,$YPos,60,$FontSize,$DisplaySuppTotVal, 'right');

	/*draw a line under the SUPPLIER TOTAL*/
	$pdf->line($Left_Margin, $YPos+$line_height-2,$Page_Width-$Right_Margin, $YPos+$line_height-2);
	$YPos -=(2*$line_height);

	$YPos -= (2*$line_height);

/*Print out the grand totals */
	$LeftOvers = $pdf->addTextWrap(80,$YPos,260-$Left_Margin,$FontSize,_('Grand Total Value'), 'right');
	$DisplayTotalVal = locale_number_format($Tot_Val,2);
	$LeftOvers = $pdf->addTextWrap(500,$YPos,60,$FontSize,$DisplayTotalVal, 'right');
	$pdf->line($Left_Margin, $YPos+$line_height-2,$Page_Width-$Right_Margin, $YPos+$line_height-2);
	$YPos -=(2*$line_height);

	$pdf->OutputD($_SESSION['DatabaseName'] . '_OSGRNsValuation_' . date('Y-m-d').'.pdf');
	$pdf->__destruct();
} elseif (isset($_POST['ShowOnScreen'])  AND DB_num_rows($GRNsResult)>0) {
	$Title=_('Outstanding GRNs Report');
	include('includes/header.php');

	echo '<p class="page_title_text"><strong>' . _('Goods Received but not invoiced Yet') . '</strong></p>';

	echo '<div class="page_help_text">' . _('Shows the list of goods received not yet invoiced, both in supplier currency and home currency. When run for all suppliers, the total in home curency should match the GL Account for Goods received not invoiced.') . '</div>';

	echo '<div>';
	echo '<table class="selection">';
	$TableHeader = '<tr>
						<th>' . _('Supplier') . '</th>
						<th>' . _('Supplier Name') . '</th>
						<th>' . _('PO#') . '</th>
						<th>' . _('Item Code') . '</th>
						<th>' . _('Qty Received') . '</th>
						<th>' . _('Qty Invoiced') . '</th>
						<th>' . _('Qty Pending') . '</th>
						<th>' . _('Unit Price') . '</th>
						<th>' .'' . '</th>
						<th>' . _('Line Total') . '</th>
						<th>' . '' . '</th>
						<th>' . _('Line Total') . '</th>
						<th>' . '' . '</th>
					</tr>';
	echo $TableHeader;
	$i = 1;
	$TotalHomeCurrency = 0;
	while ($GRNs = DB_fetch_array($GRNsResult) ){
		$QtyPending = $GRNs['qtyrecd'] - $GRNs['quantityinv'];
		$TotalHomeCurrency = $TotalHomeCurrency + ($QtyPending * $GRNs['stdcostunit']);
		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td>%s</td>
				</tr>',
				$GRNs['supplierid'],
				$GRNs['suppname'],
				$GRNs['orderno'],
				$GRNs['itemcode'],
				$GRNs['qtyrecd'],
				$GRNs['quantityinv'],
				$QtyPending,
				locale_number_format($GRNs['unitprice'],$GRNs['decimalplaces']),
				$GRNs['currcode'],
				locale_number_format(($QtyPending * $GRNs['unitprice']),$GRNs['decimalplaces']),
				$GRNs['currcode'],
				locale_number_format(($GRNs['qtyrecd'] - $GRNs['quantityinv'])*$GRNs['stdcostunit'],$_SESSION['CompanyRecord']['decimalplaces']),
				$_SESSION['CompanyRecord']['currencydefault']);

		if ($i==15){
			$i=0;
			echo $TableHeader;
		} else {
			$i++;
		}
	}
	printf('<tr><td colspan="10">%s</td>
			<td>%s</td>
			<td class="number">%s</td>
			<td>%s</td>
			</tr>',
			'',
			_('Total').':',
			locale_number_format($TotalHomeCurrency,$_SESSION['CompanyRecord']['decimalplaces']),
			$_SESSION['CompanyRecord']['currencydefault']);

	echo '</table>
			</div>';

	include('includes/footer.php');

} else { /*Neither the print PDF nor show on scrren option was hit */

	$Title=_('Outstanding GRNs Report');
	include('includes/header.php');

	echo '<p class="page_title_text"><strong>' . $Title . '</strong></p>';

	echo '<div class="page_help_text">' . _('Shows the list of goods received not yet invoiced, both in supplier currency and home currency. When run for all suppliers the total in home curency should match the GL Account for Goods received not invoiced.') . '</div>';

	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
          <div>';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<table class="selection">';

	echo '<tr>
			<td>' . _('From Supplier Code') . ':</td>
			<td><input type="text" name="FromCriteria" required="required" autofocus="autofocus" data-type="no-illegal-chars" value="0" /></td>
		</tr>
		<tr>
			<td>' . _('To Supplier Code'). ':</td>
			<td><input type="text" name="ToCriteria" required="required" data-type="no-illegal-chars"  value="zzzzzzz" /></td>
		</tr>
		</table>
		<br />
		<div class="centre">
			<input type="submit" name="PrintPDF" value="' . _('Print PDF') . '" />
			<input type="submit" name="ShowOnScreen" value="' . _('Show On Screen') . '" />
		</div>
        </div>
        </form>';

	include('includes/footer.php');

} /*end of else not PrintPDF */

?>

<?php
//	PDFPriceList.php
//	Script to print a price list by inventory category.

/*
Output column sizes:
	* stockmaster.stockid, varchar(20), len = 20chr
	* stockmaster.description, varchar(50), len = 50chr
	* prices.startdate, date, len = 10chr
	* prices.enddate, date/'No End Date', len = 12chr
	* custbranch.brname, varchar(40), len = 40chr
	* Gross Profit, calculated, len = 8chr
	* prices.price, decimal(20,4), len = 20chr + 4spaces
Please note that addTextWrap() YPos is a font-size-height further down than addText() and other functions. Use addText() instead of addTextWrap() to print left aligned elements.
All coordinates are measured from the lower left corner of the sheet to the top left corner of the element.
*/

// BEGIN: Functions division ---------------------------------------------------
function PageHeader() {
	global $pdf;
	global $Page_Width;
	global $Page_Height;
	global $Top_Margin;
	global $Bottom_Margin;
	global $Left_Margin;
	global $Right_Margin;
	global $PageNumber;
	global $YPos;
	global $FontSize;
	global $line_height;
	global $SalesTypeName;
	global $CustomerName;

	$PageNumber ++;// Increments $PageNumber before printing.
	if ($PageNumber>1) {// Inserts a page break if it is not the first page.
		$pdf->newPage();
	}

	$YPos = $Page_Height-$Top_Margin;
	$FontSizeLast = $FontSize;// To preserve the main font size.
	$FontSize = 10;
	$pdf->addText($Left_Margin, $YPos, $FontSize,
		$_SESSION['CompanyRecord']['coyname']);// Company name.
	$pdf->addTextWrap($Page_Width-$Right_Margin-140, $YPos-$FontSize, 140, $FontSize,
		_('Page'). ' ' . $PageNumber, 'right');// Page number.

	$YPos -= $FontSize;
	//Note, this is ok for multilang as this is the value of a Select, text in option is different
	if ($_POST['CustomerSpecials']==_('Customer Special Prices Only')) {
		$pdf->addText($Left_Margin, $YPos, $FontSize, _('Price List') . ': ' . $CustomerName);
	} else {
		$pdf->addText($Left_Margin, $YPos, $FontSize, _('Price List') . ': ' . $SalesTypeName);
	}
	$pdf->addTextWrap($Page_Width-$Right_Margin-140, $YPos-$FontSize, 140, $FontSize,
		_('Printed') . ': ' . date($_SESSION['DefaultDateFormat']), 'right');// Date printed.

	$YPos -= $FontSize;
	$pdf->addText($Left_Margin, $YPos, $FontSize, _('Effective As At') . ' ' . $_POST['EffectiveDate']);
	$pdf->addTextWrap($Page_Width-$Right_Margin-140, $YPos-$FontSize, 140, $FontSize,
		date('H:i:s'), 'right');// Time printed.

	$YPos -=(2*$line_height);
	// Draws a rectangle to put the headings in:
	$pdf->Rectangle(
		$Left_Margin,// Rectangle $XPos.
		$YPos,// Rectangle $YPos.
		$Page_Width-$Left_Margin-$Right_Margin,// Rectangle $Width.
		$line_height*2);// Rectangle $Height.

	$YPos -= $line_height;

	/*set up the headings */
	$LeftOvers = $pdf->addTextWrap($Left_Margin, $YPos, 80, $FontSize, _('Item Code'));// 20chr @ 8dpi.
	if ($LeftOvers != '') {// If translated text is greater than column width, prints remainder.
		$LeftOvers = $pdf->addTextWrap($Left_Margin, $YPos-$FontSize, 80, $FontSize, $LeftOvers);
	}
	$LeftOvers = $pdf->addTextWrap($Left_Margin+80, $YPos, 200,$FontSize, _('Item Description'));// 50chr @ 8dpi.
	if ($LeftOvers != '') {// If translated text is greater than column width, prints remainder.
		$LeftOvers = $pdf->addTextWrap($Left_Margin+80, $YPos-$FontSize, 200, $FontSize, $LeftOvers);
	}
	$LeftOvers = $pdf->addTextWrap($Left_Margin+280, $YPos, 96, $FontSize, _('Effective Date Range'), 'center');// (10+2+12)chr @ 8dpi.
	if ($LeftOvers != '') {// If translated text is greater than column width, prints remainder.
		$LeftOvers = $pdf->addTextWrap($Left_Margin+280, $YPos-$FontSize, 96, $FontSize, $LeftOvers, 'center');
	}

	if ($_POST['CustomerSpecials']=='Customer Special Prices Only') {
		$LeftOvers = $pdf->addTextWrap($Left_Margin+376, $YPos, 160, $FontSize, _('Branch'));// 40chr @ 8dpd.
	}

	if ($_POST['ShowGPPercentages']=='Yes') {
		$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-128, $YPos, 32, $FontSize, _('Gross Profit'), 'right');// 8chr @ 8dpi.
		if ($LeftOvers != '') {// If translated text is greater than column width, prints remainder.
			$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-128, $YPos-$FontSize, 32, $FontSize, $LeftOvers, 'right');
		}
	}
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-96, $YPos, 96, $FontSize, _('Price') , 'right');// 24chr @ 8dpd.
	$YPos -= $FontSize;

	// In some countries it is mandatory to clarify that prices do not include taxes:
	$pdf->addText($Left_Margin, $YPos, $FontSize, '* ' . _('Prices excluding tax'));// Warning text.

	$YPos -= $FontSize;// End-of-line line-feed.*/

/*	$YPos -= $FontSize;// Jumps additional line after the table headings.*/

	$FontSize = $FontSizeLast;// Resets to the main font size.
}
// END: Functions division -----------------------------------------------------

// BEGIN: Procedure division ---------------------------------------------------
include('includes/session.php');

// Merges gets into posts:
if (isset($_GET['ShowObsolete'])) {// Option to show obsolete items.
	$_POST['ShowObsolete'] = $_GET['ShowObsolete'];
}
if (isset($_GET['ItemOrder'])) {// Option to select the order of the items in the report.
	$_POST['ItemOrder'] = $_GET['ItemOrder'];
}

// Main code:
If (isset($_POST['PrintPDF'])) {

/*	if ($_POST['CustomerSpecials']=='Customer Special Prices Only') {
		// To do: For special prices, change from portrait to landscape orientation.
	}*/
	include('includes/PDFStarter.php');// Sets $PageNumber, page width, page height, top margin, bottom margin, left margin and right margin.

	$pdf->addInfo('Title', _('Price list by inventory category') );
	$pdf->addInfo('Subject', _('Price List') );

	$FontSize=10;
	$line_height=12;
	// Option to select currency:
	$WhereCurrency = '';
	if ($_POST['Currency'] != "All") {
		$WhereCurrency = " AND prices.currabrev = '" . $_POST['Currency'] ."' ";// Query element to select a currency.
	}
	// Option to show obsolete items:
	$ShowObsolete = ' AND `stockmaster`.`discontinued` != 1 ';// Query element to exclude obsolete items.
	if ($_POST['ShowObsolete']) {
		$ShowObsolete = '';// Cleans the query element to exclude obsolete items.
	}
	// Option to select the order of the items in the report:
	$ItemOrder = 'stockmaster.stockid';// Query element to sort by currency, item_stock_category, and item_code.
	if ($_POST['ItemOrder'] == 'Description') {
		$ItemOrder = 'stockmaster.description';// Query element to sort by currency, item_stock_category, and item_description.
	}

	/*Now figure out the inventory data to report for the category range under review */
	if ($_POST['CustomerSpecials']==_('Customer Special Prices Only')) {

		if ($_SESSION['CustomerID']=='') {
			$Title = _('Special price List - No Customer Selected');
			$ViewTopic = 'SalesTypes';// Filename in ManualContents.php's TOC.
			$BookMark = 'PDFPriceList';// Anchor's id in the manual's html document.
			include('includes/header.php');
			echo '<br />';
			prnMsg( _('The customer must first be selected from the select customer link') . '. ' . _('Re-run the price list once the customer has been selected') );
			echo '<br /><br /><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('Back') . '</a>';
			include('includes/footer.php');
			exit;
		}
		if (!Is_Date($_POST['EffectiveDate'])) {
			$Title = _('Special price List - No Customer Selected');
			$ViewTopic = 'SalesTypes';// Filename in ManualContents.php's TOC.
			$BookMark = 'PDFPriceList';// Anchor's id in the manual's html document.
			include('includes/header.php');
			prnMsg(_('The effective date must be entered in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
			echo '<br /><br /><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('Back') . '</a>';
			include('includes/footer.php');
			exit;
		}

		$SQL = "SELECT
					debtorsmaster.name,
					debtorsmaster.salestype
				FROM debtorsmaster
				WHERE debtorno = '" . $_SESSION['CustomerID'] . "'";
		$CustNameResult = DB_query($SQL);
		$CustNameRow = DB_fetch_row($CustNameResult);
		$CustomerName = $CustNameRow[0];
		$SalesType = $CustNameRow[1];
		$SQL = "SELECT
					prices.typeabbrev,
					prices.stockid,
					stockmaster.description,
					stockmaster.longdescription,
					prices.currabrev,
					prices.startdate,
					prices.enddate,
					prices.price,
					stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standardcost,
					stockmaster.categoryid,
					stockcategory.categorydescription,
					prices.debtorno,
					prices.branchcode,
					custbranch.brname,
					currencies.decimalplaces
				FROM stockmaster
					INNER JOIN stockcategory ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN prices ON stockmaster.stockid=prices.stockid
					INNER JOIN currencies ON prices.currabrev=currencies.currabrev
					LEFT JOIN custbranch ON prices.debtorno=custbranch.debtorno AND prices.branchcode=custbranch.branchcode
				WHERE prices.typeabbrev = '" . $SalesType . "'
					AND stockmaster.categoryid IN ('". implode("','",$_POST['Categories'])."')
					AND prices.debtorno='" . $_SESSION['CustomerID'] . "'
					AND prices.startdate<='" . FormatDateForSQL($_POST['EffectiveDate']) . "'
					AND (prices.enddate='0000-00-00' OR prices.enddate >'" . FormatDateForSQL($_POST['EffectiveDate']) . "')" .
					$WhereCurrency .
					$ShowObsolete . "
				ORDER BY
					prices.currabrev,
					stockcategory.categorydescription," .
					$ItemOrder;

	} else { /* the sales type list only */

		$SQL = "SELECT sales_type FROM salestypes WHERE typeabbrev='" . $_POST['SalesType'] . "'";
		$SalesTypeResult = DB_query($SQL);
		$SalesTypeRow = DB_fetch_row($SalesTypeResult);
		$SalesTypeName = $SalesTypeRow[0];

		$SQL = "SELECT
					prices.typeabbrev,
					prices.stockid,
					prices.startdate,
					prices.enddate,
					stockmaster.description,
					stockmaster.longdescription,
					prices.currabrev,
					prices.price,
					stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost as standardcost,
					stockmaster.categoryid,
					stockcategory.categorydescription,
					currencies.decimalplaces
				FROM stockmaster
					INNER JOIN stockcategory ON stockmaster.categoryid=stockcategory.categoryid
					INNER JOIN prices ON stockmaster.stockid=prices.stockid
					INNER JOIN currencies ON prices.currabrev=currencies.currabrev
				WHERE stockmaster.categoryid IN ('". implode("','",$_POST['Categories'])."')
					AND prices.typeabbrev='" . $_POST['SalesType'] . "'
					AND prices.startdate<='" . FormatDateForSQL($_POST['EffectiveDate']) . "'
					AND (prices.enddate='0000-00-00' OR prices.enddate>'" . FormatDateForSQL($_POST['EffectiveDate']) . "')" .
					$WhereCurrency .
					$ShowObsolete . "
					AND prices.debtorno=''
				ORDER BY
					prices.currabrev,
					stockcategory.categorydescription," .
					$ItemOrder;
	}
	$PricesResult = DB_query($SQL,'','',false,false);

	if (DB_error_no() !=0) {
		$Title = _('Price List') . ' - ' . _('Problem Report....');
		include('includes/header.php');
		prnMsg( _('The Price List could not be retrieved by the SQL because'). ' - ' . DB_error_msg(), 'error');
		echo '<br /><a href="' .$RootPath .'/index.php">' . _('Back to the menu'). '</a>';
		if ($debug==1) {
			prnMsg(_('For debugging purposes the SQL used was:') . $SQL,'error');
		}
		include('includes/footer.php');
		exit;
	}
	if (DB_num_rows($PricesResult)==0) {
		$Title = _('Print Price List Error');
		include('includes/header.php');
		prnMsg(_('There were no price details to print out for the customer or category specified'),'warn');
		echo '<br /><a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('Back') . '</a>';
		include('includes/footer.php');
		exit;
	}

	PageHeader();

	$CurrCode ='';
	$Category = '';
	$CatTot_Val=0;

	require_once('includes/CurrenciesArray.php');// To get the currency name from the currency code.
	While ($PriceList = DB_fetch_array($PricesResult)) {

		if ($CurrCode != $PriceList['currabrev']) {
			$FontSize = 10;
			if ($YPos < $Bottom_Margin + $FontSize*3) {// If the next line reaches the bottom margin, do PageHeader().
				PageHeader();
			}
			$YPos -= $FontSize;// Jumps additional line before.
			$pdf->addText($Left_Margin, $YPos, $FontSize,
				$PriceList['currabrev'] . ' - ' . _($CurrencyName[$PriceList['currabrev']]));
			$CurrCode = $PriceList['currabrev'];
			$YPos -= $FontSize;// End-of-line line-feed.
		}

		if ($Category != $PriceList['categoryid']) {
			$FontSize = 10;
			if ($YPos < $Bottom_Margin + $FontSize*3) {// If the next line reaches the bottom margin, do PageHeader().
				PageHeader();
			}
			$YPos -= $FontSize;// Jumps additional line before.
			$pdf->addText($Left_Margin, $YPos, $FontSize,
				$PriceList['categoryid'] . ' - ' . $PriceList['categorydescription']);
			$Category = $PriceList['categoryid'];
			$YPos -= $FontSize;// End-of-line line-feed.
		}

		$FontSize = 8;
		$pdf->addText($Left_Margin, $YPos, $FontSize, $PriceList['stockid']);
		$pdf->addText($Left_Margin+80, $YPos, $FontSize, $PriceList['description']);
		$pdf->addText($Left_Margin+280, $YPos, $FontSize, ConvertSQLDate($PriceList['startdate']));
		if ($PriceList['enddate']!='0000-00-00') {
			$DisplayEndDate = ConvertSQLDate($PriceList['enddate']);
		} else {
			$DisplayEndDate = _('No End Date');
		}
		$pdf->addText($Left_Margin+320, $YPos, $FontSize, $DisplayEndDate);

		// Shows gross profit percentage:
		if ($_POST['ShowGPPercentages']=='Yes') {
			$DisplayGPPercent = '-';
			if ($PriceList['price']!=0) {
				$DisplayGPPercent = locale_number_format((($PriceList['price']-$PriceList['standardcost'])*100/$PriceList['price']), 2) . '%';
			}
			$pdf->addTextWrap($Page_Width-$Right_Margin-128, $YPos-$FontSize, 32, $FontSize,
				$DisplayGPPercent, 'right');
		}
		// Displays unit price:
		$pdf->addTextWrap($Page_Width-$Right_Margin-96, $YPos-$FontSize, 96, $FontSize,
			locale_number_format($PriceList['price'],$PriceList['decimalplaces']), 'right');

		if ($_POST['CustomerSpecials']=='Customer Special Prices Only') {
			/*Need to show to which branch the price relates */
			if ($PriceList['branchcode']!='') {
				$pdf->addText($Left_Margin+376, $YPos, $FontSize, $PriceList['brname']);
			} else {
				$pdf->addText($Left_Margin+376, $YPos, $FontSize, _('All'));
			}
			$YPos -= $FontSize;// End-of-line line-feed.

		} elseif ($_POST['CustomerSpecials']=='Full Description') {
			$YPos -= $FontSize;

			// Prints item image:
			$SupportedImgExt = array('png','jpg','jpeg');
			$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $PriceList['stockid'] . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
			$YPosImage = $YPos;// Initializes the image bottom $YPos.
			if (file_exists($imagefile) ) {
				if ($YPos-36 < $Bottom_Margin) {// If the image bottom reaches the bottom margin, do PageHeader().
					PageHeader();
				}
				$LeftOvers = $pdf->Image($imagefile,$Left_Margin+3, $Page_Height-$YPos, 36, 36);
				$YPosImage = $YPos-36;// Stores the $YPos of the image bottom (see bottom).
			}
			// Prints stockmaster.longdescription:
			$XPos = $Left_Margin+80;// Takes out this calculation from the loop.
			$Width = $Page_Width-$Right_Margin-$XPos;// Takes out this calculation from the loop.
			$FontSize2 = $FontSize*0.80;// Font size and line height of Full Description section.
			$Split = explode("\r\n", $PriceList['longdescription']);
			foreach ($Split as $LeftOvers) {
				$LeftOvers = stripslashes($LeftOvers);
				while(mb_strlen($LeftOvers)>1) {
					if ($YPos < $Bottom_Margin) {// If the description line reaches the bottom margin, do PageHeader().
						PageHeader();
						$YPosImage = $YPos;// Resets the image bottom $YPos.
					}
					$LeftOvers = $pdf->addTextWrap($XPos, $YPos-$FontSize2, $Width, $FontSize2, $LeftOvers);
					$YPos -= $FontSize2;
				}
			}

			// Assigns to $YPos the lowest $YPos value between the image and the description:
			$YPos = min($YPosImage, $YPos);
			$YPos -= $FontSize;// Jumps additional line after the image and the description.
		} else {
			$YPos -= $FontSize;// End-of-line line-feed.

		}/* Endif full descriptions*/

		if ($YPos < $Bottom_Margin + $line_height) {
			PageHeader();
		}
	} /*end inventory valn while loop */

	// Warns if obsolete items are included:
	if ($_POST['ShowObsolete']) {
		$FontSize = 8;
		$YPos -= $FontSize;// Jumps additional line.
		if ($YPos < $Bottom_Margin + $FontSize) {
			PageHeader();
		}
		$pdf->addText($Left_Margin, $YPos, $FontSize, _('* Obsolete items included.'));// Warning text.
	}

	$FontSize = 10;
	$FileName = $_SESSION['DatabaseName'] . '_' . _('Price_List') . '_' . date('Y-m-d') . '.pdf';
	ob_clean();
	$pdf->OutputD($FileName);
	$pdf->__destruct();

} else { /*The option to print PDF was not hit */
	$Title = _('Price Listing');
	$ViewTopic = 'SalesTypes';
	$BookMark = 'PDFPriceList';
	include('includes/header.php');

	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
		'/images/customer.png" title="', // Icon image.
		_('Price List'), '" /> ', // Icon title.
		_('Print a price list by inventory category'), '</p>';// Page title.

	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
	echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<table class="selection">
		<tr>',
			'<td><label for="Categories">', _('Select Inventory Categories'), ':</label></td>',
			'<td>
				<select autofocus="autofocus" id="Categories" minlength="1" multiple="multiple" name="Categories[]" required="required" size="12">';
	$SQL = 'SELECT categoryid, categorydescription
			FROM stockcategory
			ORDER BY categorydescription';
	$CatResult = DB_query($SQL);
	while ($MyRow = DB_fetch_array($CatResult)) {
		echo '<option' ;
		if (isset($_POST['Categories']) AND in_array($MyRow['categoryid'], $_POST['Categories'])) {
			echo ' selected="selected"';
		}
		echo ' value="', $MyRow['categoryid'], '">', $MyRow['categorydescription'], '</option>';
	}
	echo		'</select>
			</td>
		</tr>
		<tr><td>', _('For Sales Type/Price List'), ':</td>
			 <td><select name="SalesType">';
	$sql = "SELECT sales_type, typeabbrev FROM salestypes";
	$SalesTypesResult=DB_query($sql);

	while ($myrow=DB_fetch_array($SalesTypesResult)) {
		echo '<option value="', $myrow['typeabbrev'], '">', $myrow['sales_type'], '</option>';
	}
	echo '</select></td>
		</tr>
		<tr><td>', _('For Currency'), ':</td>
			 <td><select name="Currency">';
	$sql = "SELECT currabrev, currency FROM currencies ORDER BY currency";
	$CurrencyResult=DB_query($sql);
	echo '<option selected="selected" value="All">', _('All'), '</option>';
	while ($myrow=DB_fetch_array($CurrencyResult)) {
		echo '<option value="', $myrow['currabrev'], '">', $myrow['currency'], '</option>';
	}
	echo '</select>
			</td>
		</tr>
		<tr>
			<td>', _('Show Gross Profit %'), ':</td>
			<td><select name="ShowGPPercentages">
				<option selected="selected" value="No">', _('Prices Only'), '</option>
				<option value="Yes">', _('Show GP % too'), '</option>
				</select></td>
		</tr>
		<tr>
			<td>', _('Price Listing Type'), ':</td><td><select name="CustomerSpecials">
				<option selected="selected" value="Sales Type Prices">', _('Default Sales Type Prices'), '</option>
				<option value="Customer Special Prices Only">', _('Customer Special Prices Only'), '</option>
				<option value="Full Description">', _('Full Description'), '</option>
				</select></td>
		</tr>
		<tr>
			<td>', _('Effective As At'), ':</td>
			<td><input type="text" required="required" maxlength="10" size="11" class="date" name="EffectiveDate" value="', Date($_SESSION['DefaultDateFormat']), '" /></td>
		</tr>',
	// Option to show obsolete items:
		'<tr>',
			'<td><label for="ShowObsolete">', _('Show obsolete items'), ':</label></td>',
	 		'<td>',
				'<input',(($_POST['ShowObsolete']) ? ' checked="checked"' : ''), ' id="ShowObsolete" name="ShowObsolete" type="checkbox" />', // "Checked" if ShowObsolete is TRUE.
					fShowFieldHelp(_('Check this box to show the obsolete items')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
			'</td>',
	 	'</tr>',
	// Option to select the order of the items in the report:
		'<tr>',
			'<td><label for="ItemOrder">', _('Sort items by'), ':</label></td>',
	 		'<td>',
				'<input checked="checked" id="ItemOrder" name="ItemOrder" type="radio" value="Code">', _('Currency, category and code'), '<br>',
				'<input name="ItemOrder" type="radio" value="Description">', _('Currency, category and description'), '<br>',
					fShowFieldHelp(_('Select the order of the items in the report')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
			'</td>',
	 	'</tr>',

		'</table>
		<br />',
		'<div class="centre noprint">', // Form buttons:
			'<button name="PrintPDF" type="submit" value="', _('Print PDF'), '"><img alt="" src="', $RootPath, '/css/', $Theme,
				'/images/pdf.png" /> ', _('PDF'), '</button>', // "PDF" button.
			'<button onclick="window.location=\'index.php?Application=Sales\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme,
				'/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
		'</div>',
		'</div>
	</form>';

	include('includes/footer.php');
} /*end of else not PrintPDF */
// END: Procedure division -----------------------------------------------------

?>
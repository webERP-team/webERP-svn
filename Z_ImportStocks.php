 <?php

include('includes/session.php');
$Title = _('Import Items');
include('includes/header.php');
echo '<p class="page_title_text"><img alt="" src="' . $RootPath . '/css/' . $Theme .
		'/images/inventory.png" title="' .
		_('Import Stock Items from .csv') . '" />' . ' ' .
		_('Import Stock Items from .csv') . '</p>';

// If this script is called with a file object, then the file contents are imported
// If this script is called with the gettemplate flag, then a template file is served
// Otherwise, a file upload form is displayed

// The CSV file must be saved in a format like the template in the import module I.E. "RECVALUE","RECVALUE2". The CSV file needs ANSI encoding for the import to work properly.


$FieldHeadings = array(
	'StockID',         	//  0 'STOCKID',
	'Description',     	//  1 'DESCRIPTION',
	'LongDescription', 	//  2 'LONGDESCRIPTION',
	'CategoryID',      	//  3 'CATEGORYID',
	'Units',           	//  4 'UNITS',
	'MBFlag',          	//  5 'MBFLAG',
	'EOQ',             	//  6 'EOQ',
	'Discontinued',    	//  7 'DISCONTINUED',
	'Controlled',      	//  8 'CONTROLLED',
	'Serialised',      	//  9 'SERIALISED',
	'Perishable',      	// 10 'PERISHABLE',
	'Volume',          	// 11 'VOLUME',
	'grossweight',		// 12 'grossweight',
	'BarCode',         	// 13 'BARCODE',
	'DiscountCategory',	// 14 'DISCOUNTCATEGORY',
	'TaxCat',          	// 15 'TAXCAT',
	'DecimalPlaces',   	// 16 'DECIMALPLACES',
	'ItemPDF'          	// 17 'ITEMPDF'
);

if (isset($_FILES['userfile']) and $_FILES['userfile']['name']) { //start file processing

	//initialize
	$FieldTarget = 18;
	$InputError = 0;

	//check file info
	$FileName = $_FILES['userfile']['name'];
	$TempName  = $_FILES['userfile']['tmp_name'];
	$FileSize = $_FILES['userfile']['size'];

	//get file handle
	$FileHandle = fopen($TempName, 'r');

	//get the header row
	$headRow = fgetcsv($FileHandle, 10000, ",",'"');  // Modified to handle " "" " enclosed csv - useful if you need to include commas in your text descriptions

	//check for correct number of fields
	if ( count($headRow) != count($FieldHeadings) ) {
		prnMsg (_('File contains '. count($headRow). ' columns, expected '. count($FieldHeadings). '. Try downloading a new template.'),'error');
		fclose($FileHandle);
		include('includes/footer.php');
		exit;
	}

	//test header row field name and sequence
	$head = 0;
	foreach ($headRow as $headField) {
		if ( mb_strtoupper($headField) != mb_strtoupper($FieldHeadings[$head]) ) {
			prnMsg (_('File contains incorrect headers '. mb_strtoupper($headField). ' != '. mb_strtoupper($FieldHeadings[$head]). '. Try downloading a new template.'),'error');  //Fixed $FieldHeadings from $headings
			fclose($FileHandle);
			include('includes/footer.php');
			exit;
		}
		$head++;
	}

	//start database transaction
	DB_Txn_Begin();

	//loop through file rows
	$row = 1;
	while ( ($myrow = fgetcsv($FileHandle, 10000, ",")) !== FALSE ) {

		//check for correct number of fields
		$fieldCount = count($myrow);
		if ($fieldCount != $FieldTarget){
			prnMsg (_($FieldTarget. ' fields required, '. $fieldCount. ' fields received'),'error');
			fclose($FileHandle);
			include('includes/footer.php');
			exit;
		}

		// cleanup the data (csv files often import with empty strings and such)
		$StockID = mb_strtoupper($myrow[0]);
		foreach ($myrow as &$value) {
			$value = trim($value);
		}

		//first off check if the item already exists
		$sql = "SELECT COUNT(stockid) FROM stockmaster WHERE stockid='".$StockID."'";
		$result = DB_query($sql);
		$testrow = DB_fetch_row($result);
		if ($testrow[0] != 0) {
			$InputError = 1;
			prnMsg (_('Stock item "'. $StockID. '" already exists'),'error');
		}

		//next validate inputs are sensible
		if (!$myrow[1] or mb_strlen($myrow[1]) > 50 OR mb_strlen($myrow[1])==0) {
			$InputError = 1;
			prnMsg (_('The stock item description must be entered and be fifty characters or less long') . '. ' . _('It cannot be a zero length string either') . ' - ' . _('a description is required'). ' ("'. implode('","',$myrow). $stockid. '") ','error');
		}
		if (mb_strlen($myrow[2])==0) {
			$InputError = 1;
			prnMsg (_('The stock item description cannot be a zero length string') . ' - ' . _('a long description is required'),'error');
		}
		if (mb_strlen($StockID) ==0) {
			$InputError = 1;
			prnMsg (_('The Stock Item code cannot be empty'),'error');
		}
		if (ContainsIllegalCharacters($StockID) OR mb_strstr($StockID,' ')) {
			$InputError = 1;
			prnMsg(_('The stock item code cannot contain any of the following characters') . " ' & + \" \\ " . _('or a space'). " (". $StockID. ")",'error');
			$StockID='';
		}
		if (mb_strlen($myrow[4]) >20) {
			$InputError = 1;
			prnMsg(_('The unit of measure must be 20 characters or less long'),'error');
		}
		if (mb_strlen($myrow[13]) >20) {
			$InputError = 1;
			prnMsg(_('The barcode must be 20 characters or less long'),'error');
		}
		if ($myrow[10]!=0 AND $myrow[10]!=1) {
			$InputError = 1;
			prnMsg (_('Values in the Perishable field must be either 0 (No) or 1 (Yes)') ,'error');
		}
		if (!is_numeric($myrow[11])) {
			$InputError = 1;
			prnMsg (_('The volume of the packaged item in cubic metres must be numeric') ,'error');
		}
		if ($myrow[11] <0) {
			$InputError = 1;
			prnMsg(_('The volume of the packaged item must be a positive number'),'error');
		}
		if (!is_numeric($myrow[12])) {
			$InputError = 1;
			prnMsg(_('The weight of the packaged item in KGs must be numeric'),'error');
		}
		if ($myrow[12]<0) {
			$InputError = 1;
			prnMsg(_('The weight of the packaged item must be a positive number'),'error');
		}
		if (!is_numeric($myrow[6])) {
			$InputError = 1;
			prnMsg(_('The economic order quantity must be numeric'),'error');
		}
		if ($$myrow[6] <0) {
			$InputError = 1;
			prnMsg (_('The economic order quantity must be a positive number'),'error');
		}
		if ($myrow[8]==0 AND $myrow[9]==1){
			$InputError = 1;
			prnMsg(_('The item can only be serialised if there is lot control enabled already') . '. ' . _('Batch control') . ' - ' . _('with any number of items in a lot/bundle/roll is enabled when controlled is enabled') . '. ' . _('Serialised control requires that only one item is in the batch') . '. ' . _('For serialised control') . ', ' . _('both controlled and serialised must be enabled'),'error');
		}

		$mbflag = $myrow[5];
		if ($mbflag!='M' and $mbflag!='K' and $mbflag!='A' and $mbflag!='B' and $mbflag!='D' and $mbflag!='G') {
			$InputError = 1;
			prnMsg(_('Items must be of MBFlag type Manufactured(M), Assembly(A), Kit-Set(K), Purchased(B), Dummy(D) or Phantom(G)'),'error');
		}
		if (($mbflag=='A' OR $mbflag=='K' OR $mbflag=='D' OR $mbflag=='G') AND $myrow[8]==1){
			$InputError = 1;
			prnMsg(_('Assembly/Kitset/Phantom/Service items cannot also be controlled items') . '. ' . _('Assemblies, Dummies and Kitsets are not physical items and batch/serial control is therefore not appropriate'),'error');
		}
		if ($myrow[3]==''){
			$InputError = 1;
			prnMsg(_('There are no inventory categories defined. All inventory items must belong to a valid inventory category,'),'error');
		}
		if ($myrow[17]==''){
			$InputError = 1;
			prnMsg(_('ItemPDF must contain either a filename, or the keyword `none`'),'error');
		}

		if ($InputError !=1){
			if ($myrow[9]==1){ /*Not appropriate to have several dp on serial items */
				$myrow[16]=0;
			}

			//attempt to insert the stock item
			$sql = "
				INSERT INTO stockmaster (
					stockid,
					description,
					longdescription,
					categoryid,
					units,
					mbflag,
					eoq,
					discontinued,
					controlled,
					serialised,
					perishable,
					volume,
					grossweight,
					barcode,
					discountcategory,
					taxcatid,
					decimalplaces)
				VALUES (
					'$StockID',
					'" . $myrow[1]	. "',
					'" . $myrow[2]	. "',
					'" . $myrow[3]	. "',
					'" . $myrow[4]	. "',
					'" . $myrow[5]	. "',
					"  . $myrow[6]	. ",
					"  . $myrow[7]	. ",
					"  . $myrow[8]	. ",
					"  . $myrow[9]	. ",
					"  . $myrow[10]	. ",
					"  . $myrow[11]	. ",
					"  . $myrow[12]	. ",
					'" . $myrow[13]	. "',
					'" . $myrow[14]	. "',
					"  . $myrow[15]	. ",
					"  . $myrow[16]	. "
				);
			";

			$ErrMsg =  _('The item could not be added because');
			$DbgMsg = _('The SQL that was used to add the item failed was');
			$result = DB_query($sql, $ErrMsg, $DbgMsg);

			if (DB_error_no() ==0) { //the insert of the new code worked so bang in the stock location records too

				$sql = "INSERT INTO locstock (loccode,
												stockid)
									SELECT locations.loccode,
									'" . $StockID . "'
									FROM locations";

				$ErrMsg =  _('The locations for the item') . ' ' . $StockID .  ' ' . _('could not be added because');
				$DbgMsg = _('NB Locations records can be added by opening the utility page') . ' <i>Z_MakeStockLocns.php</i> ' . _('The SQL that was used to add the location records that failed was');
				$InsResult = DB_query($sql,$ErrMsg,$DbgMsg);

				if (DB_error_no() ==0) {
					prnMsg( _('New Item') .' ' . $StockID  . ' '. _('has been added to the transaction'),'info');
				} else { //location insert failed so set some useful error info
					$InputError = 1;
					prnMsg(_($InsResult),'error');
				}

			} else { //item insert failed so set some useful error info
				$InputError = 1;
				prnMsg(_($InsResult),'error');
			}

		}

		if ($InputError == 1) { //this row failed so exit loop
			break;
		}

		$row++;

	}

	if ($InputError == 1) { //exited loop with errors so rollback
		prnMsg(_('Failed on row '. $row. '. Batch import has been rolled back.'),'error');
		DB_Txn_Rollback();
	} else { //all good so commit data transaction
		DB_Txn_Commit();
		prnMsg( _('Batch Import of') .' ' . $FileName  . ' '. _('has been completed. All transactions committed to the database.'),'success');
	}

	fclose($FileHandle);

} elseif ( isset($_POST['gettemplate']) || isset($_GET['gettemplate']) ) { //download an import template

	echo '<br /><br /><br />"'. implode('","',$FieldHeadings). '"<br /><br /><br />';

} else { //show file upload form

	echo '
		<br />
		<a href="Z_ImportStocks.php?gettemplate=1">Get Import Template</a>
		<br />
		<br />';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" enctype="multipart/form-data">';
    echo '<div class="centre">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />' .
			_('Upload file') . ': <input name="userfile" type="file" />
			<input type="submit" value="' . _('Send File') . '" />
        </div>
		</form>';

}


include('includes/footer.php');
?>

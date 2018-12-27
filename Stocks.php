<?php
// Stocks.php
// Defines an item - maintenance and addition of new parts.

include('includes/session.php');
$Title = _('Item Maintenance');
$ViewTopic = 'Inventory';
$BookMark = 'InventoryAddingItems';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

/*If this form is called with the StockID then it is assumed that the stock item is to be modified */

if (isset($_GET['StockID'])){
	$StockID =trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
	$StockID =trim(mb_strtoupper($_POST['StockID']));
} else {
	$StockID = '';
}

$ItemDescriptionLanguagesArray = explode(',',$_SESSION['ItemDescriptionLanguages']);//WARNING: if the last character is a ",", there are n+1 languages.

$hasNext = true;
$hasPrev = true;

if (isset($_POST['NextItem'])){
	$Result = DB_query("SELECT stockid FROM stockmaster WHERE stockid>'" . $StockID . "' ORDER BY stockid ASC LIMIT 1");

	// Only change the StockID if we find a row.
	// If not, the StockID is 'clobbered' with null and causes form havoc.
	if ( DB_num_rows( $Result ) > 0 ) {
	$NextItemRow = DB_fetch_row($Result);
	$StockID = $NextItemRow[0];
	}
	else {
		$hasNext = false;
	}

	foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
		unset($_POST['Description_' . str_replace('.','_',$LanguageId)]);
	}
}
if (isset($_POST['PreviousItem'])){
	$Result = DB_query("SELECT stockid FROM stockmaster WHERE stockid<'" . $StockID . "' ORDER BY stockid DESC LIMIT 1");

	// Only change the StockID if we find a row.
	// If not, the StockID is 'clobbered' with null and causes form havoc.
	if ( DB_num_rows( $Result ) > 0 ) {
	$PreviousItemRow = DB_fetch_row($Result);
	$StockID = $PreviousItemRow[0];
	}
	else {
		$hasPrev = false;
	}

	foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
		unset($_POST['Description_' . str_replace('.','_',$LanguageId)]);
	}
}

if (isset($StockID) AND !isset($_POST['UpdateCategories'])) {
	$sql = "SELECT COUNT(stockid)
			FROM stockmaster
			WHERE stockid='".$StockID."'
			GROUP BY stockid";

	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]==0) {
		$New=1;
	} else {
		$New=0;
	}
}

if (isset($_POST['New'])) {
	$New=$_POST['New'];
}

echo '<a href="' . $RootPath . '/SelectProduct.php">' . _('Back to Items') . '</a>
	<br />',
	'<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/inventory.png" title="', // Icon image.
	$Title, '" /> ', // Icon title.
	$Title, '</p>';// Page title.

$SupportedImgExt = array('png','jpg','jpeg');

if (isset($_FILES['ItemPicture']) AND $_FILES['ItemPicture']['name'] !='') {
	$ImgExt = pathinfo($_FILES['ItemPicture']['name'], PATHINFO_EXTENSION);

	$result	= $_FILES['ItemPicture']['error'];
 	$UploadTheFile = 'Yes'; //Assume all is well to start off with
	$filename = $_SESSION['part_pics_dir'] . '/' . $StockID . '.' . $ImgExt;
	 //But check for the worst
	if (!in_array ($ImgExt, $SupportedImgExt)) {
		prnMsg(_('Only ' . implode(", ", $SupportedImgExt) . ' files are supported - a file extension of ' . implode(", ", $SupportedImgExt) . ' is expected'),'warn');
		$UploadTheFile ='No';
	} elseif ( $_FILES['ItemPicture']['size'] > ($_SESSION['MaxImageSize']*1024)) { //File Size Check
		prnMsg(_('The file size is over the maximum allowed. The maximum size allowed in KB is') . ' ' . $_SESSION['MaxImageSize'],'warn');
		$UploadTheFile ='No';
	} elseif ( $_FILES['ItemPicture']['type'] == 'text/plain' ) {  //File Type Check
		prnMsg( _('Only graphics files can be uploaded'),'warn');
		 	$UploadTheFile ='No';
    } elseif ( $_FILES['ItemPicture']['error'] == 6 ) {  //upload temp directory check
		prnMsg( _('No tmp directory set. You must have a tmp directory set in your PHP for upload of files. '),'warn');
		 	$UploadTheFile ='No';
	}
	foreach ($SupportedImgExt as $ext) {
		$file = $_SESSION['part_pics_dir'] . '/' . $StockID . '.' . $ext;
		if (file_exists ($file) ) {
			$result = unlink($file);
			if (!$result){
				prnMsg(_('The existing image could not be removed'),'error');
				$UploadTheFile ='No';
			}
		}
	}

	if ($UploadTheFile=='Yes'){
		$result  =  move_uploaded_file($_FILES['ItemPicture']['tmp_name'], $filename);
		$message = ($result)?_('File url')  . '<a href="' . $filename .'">' .  $filename . '</a>' : _('Something is wrong with uploading a file');
	}
}

if (isset($Errors)) {
	unset($Errors);
}
$Errors = array();
$InputError = 0;

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;


	if (!isset($_POST['Description']) OR mb_strlen($_POST['Description']) > 50 OR mb_strlen($_POST['Description'])==0) {
		$InputError = 1;
		prnMsg (_('The stock item description must be entered and be fifty characters or less long') . '. ' . _('It cannot be a zero length string either') . ' - ' . _('a description is required'),'error');
		$Errors[$i] = 'Description';
		$i++;
	}
	if (mb_strlen($_POST['LongDescription'])==0) {
		$InputError = 1;
		prnMsg (_('The stock item description cannot be a zero length string') . ' - ' . _('a long description is required'),'error');
		$Errors[$i] = 'LongDescription';
		$i++;
	}
	if (mb_strlen($StockID) ==0) {
		$InputError = 1;
		prnMsg (_('The Stock Item code cannot be empty'),'error');
		$Errors[$i] = 'StockID';
		$i++;
	}
	if (ContainsIllegalCharacters($StockID) OR mb_strpos($StockID,' ')) {
		$InputError = 1;
		prnMsg(_('The stock item code cannot contain any of the following characters') . " - ' &amp; + \" \\ ." . _('or a space'),'error');
		$Errors[$i] = 'StockID';
		$i++;
		$StockID='';
	}
	if (mb_strlen($_POST['Units']) >20) {
		$InputError = 1;
		prnMsg(_('The unit of measure must be 20 characters or less long'),'error');
		$Errors[$i] = 'Units';
		$i++;
	}
	if (mb_strlen($_POST['BarCode']) >20) {
		$InputError = 1;
		prnMsg(_('The barcode must be 20 characters or less long'),'error');
		$Errors[$i] = 'BarCode';
		$i++;
	}
	if (!is_numeric(filter_number_format($_POST['Volume']))) {
		$InputError = 1;
		prnMsg (_('The volume of the packaged item in cubic metres must be numeric') ,'error');
		$Errors[$i] = 'Volume';
		$i++;
	}
	if (filter_number_format($_POST['Volume']) <0) {
		$InputError = 1;
		prnMsg(_('The volume of the packaged item must be a positive number'),'error');
		$Errors[$i] = 'Volume';
		$i++;
	}
	if (!is_numeric(filter_number_format($_POST['GrossWeight']))) {
		$InputError = 1;
		prnMsg(_('The weight of the packaged item in Gross Weight must be numeric'),'error');
		$Errors[$i] = 'GrossWeight';
		$i++;
	}
	if (filter_number_format($_POST['GrossWeight'])<0) {
		$InputError = 1;
		prnMsg(_('The weight of the packaged item must be a positive number'),'error');
		$Errors[$i] = 'GrossWeight';
		$i++;
	}
	if (!is_numeric(filter_number_format($_POST['NetWeight']))) {
		$InputError = 1;
		prnMsg(_('The net weight of the item in Net Weight must be numeric'),'error');
		$Errors[$i] = 'NetWeight';
		$i++;
	}
	if (filter_number_format($_POST['NetWeight'])<0) {
		$InputError = 1;
		prnMsg(_('The net weight of the item must be a positive number'),'error');
		$Errors[$i] = 'NetWeight';
		$i++;
	}
	if (!is_numeric(filter_number_format($_POST['EOQ']))) {
		$InputError = 1;
		prnMsg(_('The economic order quantity must be numeric'),'error');
		$Errors[$i] = 'EOQ';
		$i++;
	}
	if (filter_number_format($_POST['EOQ']) <0) {
		$InputError = 1;
		prnMsg (_('The economic order quantity must be a positive number'),'error');
		$Errors[$i] = 'EOQ';
		$i++;
	}
	if ($_POST['Controlled']==0 AND $_POST['Serialised']==1){
		$InputError = 1;
		prnMsg(_('The item can only be serialised if there is lot control enabled already') . '. ' . _('Batch control') . ' - ' . _('with any number of items in a lot/bundle/roll is enabled when controlled is enabled') . '. ' . _('Serialised control requires that only one item is in the batch') . '. ' . _('For serialised control') . ', ' . _('both controlled and serialised must be enabled'),'error');
		$Errors[$i] = 'Serialised';
		$i++;
	}
	if ($_POST['NextSerialNo']!=0 AND $_POST['Serialised']==0){
		$InputError = 1;
		prnMsg(_('The item can only have automatically generated serial numbers if it is a serialised item'),'error');
		$Errors[$i] = 'NextSerialNo';
		$i++;
	}
	if ($_POST['NextSerialNo']!=0 AND $_POST['MBFlag']!='M'){
		$InputError = 1;
		prnMsg(_('The item can only have automatically generated serial numbers if it is a manufactured item'),'error');
		$Errors[$i] = 'NextSerialNo';
		$i++;
	}
	if (($_POST['MBFlag']=='A'
			OR $_POST['MBFlag']=='K'
			OR $_POST['MBFlag']=='D'
			OR $_POST['MBFlag']=='G')
		AND $_POST['Controlled']==1){

		$InputError = 1;
		prnMsg(_('Assembly/Kitset/Phantom/Service/Labour items cannot also be controlled items') . '. ' . _('Assemblies/Dummies/Phantom and Kitsets are not physical items and batch/serial control is therefore not appropriate'),'error');
		$Errors[$i] = 'Controlled';
		$i++;
	}
	if (trim($_POST['CategoryID'])==''){
		$InputError = 1;
		prnMsg(_('There are no inventory categories defined. All inventory items must belong to a valid inventory category,'),'error');
		$Errors[$i] = 'CategoryID';
		$i++;
	}
	if (!is_numeric(filter_number_format($_POST['Pansize']))) {
		$InputError = 1;
		prnMsg(_('Pansize quantity must be numeric'),'error');
		$Errors[$i] = 'Pansize';
		$i++;
	}
	if (!is_numeric(filter_number_format($_POST['ShrinkFactor']))) {
		$InputError = 1;
		prnMsg(_('Shrinkage factor quantity must be numeric'),'error');
		$Errors[$i] = 'ShrinkFactor';
		$i++;
	}

	if ($InputError !=1){
		if ($_POST['Serialised']==1){ /*Not appropriate to have several dp on serial items */
			$_POST['DecimalPlaces']=0;
		}
		if ($New==0) { /*so its an existing one */

			/*first check on the changes being made we must disallow:
			- changes from manufactured or purchased to Service, Assembly or Kitset if there is stock			- changes from manufactured, kitset or assembly where a BOM exists
			*/
			$sql = "SELECT mbflag,
							controlled,
							serialised,
							materialcost+labourcost+overheadcost AS itemcost,
							stockcategory.stockact,
							stockcategory.wipact,
							description,
							longdescription
					FROM stockmaster
					INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE stockid = '".$StockID."'";
			$MBFlagResult = DB_query($sql);
			$myrow = DB_fetch_row($MBFlagResult);
			$OldMBFlag = $myrow[0];
			$OldControlled = $myrow[1];
			$OldSerialised = $myrow[2];
			$UnitCost = $myrow[3];
			$OldStockAccount = $myrow[4];
			$OldWIPAccount = $myrow[5];
			$OldDescription = $myrow[6];
			$OldLongDescription = $myrow[7];


			$sql = "SELECT SUM(locstock.quantity)
					FROM locstock
					WHERE stockid='".$StockID."'
					GROUP BY stockid";
			$result = DB_query($sql);
			$StockQtyRow = DB_fetch_row($result);

			/*Now check the GL account of the new category to see if it is different to the old stock gl account */

			$result = DB_query("SELECT stockact,
										wipact
								FROM stockcategory
								WHERE categoryid='" . $_POST['CategoryID'] . "'");
			$NewStockActRow = DB_fetch_array($result);
			$NewStockAct = $NewStockActRow['stockact'];
			$NewWIPAct = $NewStockActRow['wipact'];

			if ($OldMBFlag != $_POST['MBFlag']){
				if (($OldMBFlag == 'M' OR $OldMBFlag=='B') AND ($_POST['MBFlag']=='A' OR $_POST['MBFlag']=='K' OR $_POST['MBFlag']=='D' OR $_POST['MBFlag']=='G')){ /*then need to check that there is no stock holding first */
					/* stock holding OK for phantom (ghost) items */
					if ($StockQtyRow[0]!=0 AND $OldMBFlag!='G'){
						$InputError=1;
						prnMsg( _('The make or buy flag cannot be changed from') . ' ' . $OldMBFlag . ' ' . _('to') . ' ' . $_POST['MBFlag'] . ' ' . _('where there is a quantity of stock on hand at any location') . '. ' . _('Currently there are') . ' ' . $StockQtyRow[0] .  ' ' . _('on hand') , 'errror');
					}
					/* don't allow controlled/serialized  */
					if ($_POST['Controlled']==1){
						$InputError=1;
						prnMsg( _('The make or buy flag cannot be changed from') . ' ' . $OldMBFlag . ' ' . _('to') . ' ' . $_POST['MBFlag'] . ' ' . _('where the item is to be lot controlled') . '. ' . _('Kitset, phantom, dummy and assembly items cannot be lot controlled'), 'error');
					}
				}
				/*now check that if the item is being changed to a kitset, there are no items on sales orders or purchase orders*/
				if ($_POST['MBFlag']=='K') {
					$sql = "SELECT quantity-qtyinvoiced
							FROM salesorderdetails
							WHERE stkcode = '".$StockID."'
							AND completed=0";

					$result = DB_query($sql);
					$ChkSalesOrds = DB_fetch_row($result);
					if ($ChkSalesOrds[0]!=0){
						$InputError = 1;
						prnMsg( _('The make or buy flag cannot be changed to a kitset where there is a quantity outstanding to be delivered on sales orders') . '. ' . _('Currently there are') .' ' . $ChkSalesOrds[0] . ' '. _('outstanding'), 'error');
					}
				}
				/*now check that if it is to be a kitset or assembly or dummy there is no quantity on purchase orders outstanding*/
				if ($_POST['MBFlag']=='K' OR $_POST['MBFlag']=='A' OR $_POST['MBFlag']=='D') {

					$sql = "SELECT quantityord-quantityrecd
							FROM purchorderdetails INNER JOIN purchorders
							ON purchorders.orderno=purchorderdetails.orderno
							WHERE itemcode = '".$StockID."'
							AND purchorderdetails.completed=0
							AND purchorders.status<>'Cancelled'
							AND purchorders.status<>'Completed'
							AND purchorders.status<>'Rejected'";

					$result = DB_query($sql);
					$ChkPurchOrds = DB_fetch_row($result);
					if ($ChkPurchOrds[0]!=0){
						$InputError = 1;
						prnMsg( _('The make or buy flag cannot be changed to'). ' ' . $_POST['MBFlag'] . ' '. _('where there is a quantity outstanding to be received on purchase orders') . '. ' . _('Currently there are'). ' ' . $ChkPurchOrds[0] . ' '. _('yet to be received'). 'error');
					}
				}

				/*now check that if it was a Manufactured, Kitset, Phantom or Assembly and is being changed to a purchased or dummy - that no BOM exists */
				if (($OldMBFlag=='M' OR $OldMBFlag =='K' OR $OldMBFlag=='A' OR $OldMBFlag=='G') AND ($_POST['MBFlag']=='B' OR $_POST['MBFlag']=='D')) {
					$sql = "SELECT COUNT(*)
							FROM bom
							WHERE parent = '".$StockID."'
							GROUP BY parent";
					$result = DB_query($sql);
					$ChkBOM = DB_fetch_row($result);
					if ($ChkBOM[0]!=0){
						$InputError = 1;
						prnMsg( _('The make or buy flag cannot be changed from manufactured, kitset or assembly to'). ' ' . $_POST['MBFlag'] . ' '. _('where there is a bill of material set up for the item') . '. ' . _('Bills of material are not appropriate for purchased or dummy items'), 'error');
					}
				}

				/*now check that if it was Manufac, Phantom or Purchased and is being changed to assembly or kitset, it is not a component on an existing BOM */
				if (($OldMBFlag=='M' OR $OldMBFlag =='B' OR $OldMBFlag=='D' OR $OldMBFlag=='G') AND ($_POST['MBFlag']=='A' OR $_POST['MBFlag']=='K')) {
					$sql = "SELECT COUNT(*)
							FROM bom
							WHERE component = '".$StockID."'
							GROUP BY component";
					$result = DB_query($sql);
					$ChkBOM = DB_fetch_row($result);
					if ($ChkBOM[0]!=0){
						$InputError = 1;
						prnMsg( _('The make or buy flag cannot be changed from manufactured, purchased or dummy to a kitset or assembly where the item is a component in a bill of material') . '. ' . _('Assembly and kitset items are not appropriate as components in a bill of materials'), 'error');
					}
				}
			}

			/* Do some checks for changes in the Serial & Controlled setups */
			if ($OldControlled != $_POST['Controlled'] AND $StockQtyRow[0]!=0){
				$InputError=1;
				prnMsg( _('You can not change a Non-Controlled Item to Controlled (or back from Controlled to non-controlled when there is currently stock on hand for the item') , 'error');

			}
			if ($OldSerialised != $_POST['Serialised'] AND $StockQtyRow[0]!=0){
				$InputError=1;
				prnMsg( _('You can not change a Serialised Item to Non-Serialised (or vice-versa) when there is a quantity on hand for the item') , 'error');
			}
			/* Do some check for property input */

			for ($i=0;$i<$_POST['PropertyCounter'];$i++){
				if ($_POST['PropNumeric' .$i]==1){
					if ( filter_number_format($_POST['PropValue' . $i]) < $_POST['PropMin' . $i] OR filter_number_format($_POST['PropValue' . $i]) > $_POST['PropMax' . $i]){
							$InputError = 1;
							prnMsg(_('The property value should between') . ' '.$_POST['PropMin' . $i] . ' ' . _('and') . $_POST['PropMax' . $i],'error');
					}
				}
			}

			if ($InputError == 0){

				DB_Txn_Begin();

				$sql = "UPDATE stockmaster
						SET longdescription='" . $_POST['LongDescription'] . "',
							description='" . $_POST['Description'] . "',
							discontinued='" . $_POST['Discontinued'] . "',
							controlled='" . $_POST['Controlled'] . "',
							serialised='" . $_POST['Serialised']."',
							perishable='" . $_POST['Perishable']."',
							categoryid='" . $_POST['CategoryID'] . "',
							units='" . $_POST['Units'] . "',
							mbflag='" . $_POST['MBFlag'] . "',
							eoq='" . filter_number_format($_POST['EOQ']) . "',
							volume='" . filter_number_format($_POST['Volume']) . "',
							grossweight='" . filter_number_format($_POST['GrossWeight']) . "',
							netweight='" . filter_number_format($_POST['NetWeight']) . "',
							barcode='" . $_POST['BarCode'] . "',
							discountcategory='" . $_POST['DiscountCategory'] . "',
							taxcatid='" . $_POST['TaxCat'] . "',
							decimalplaces='" . $_POST['DecimalPlaces'] . "',
							shrinkfactor='" . filter_number_format($_POST['ShrinkFactor']) . "',
							pansize='" . filter_number_format($_POST['Pansize']) . "',
							nextserialno='" . $_POST['NextSerialNo'] . "'
					WHERE stockid='".$StockID."'";

				$ErrMsg = _('The stock item could not be updated because');
				$DbgMsg = _('The SQL that was used to update the stock item and failed was');
				$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

				$ErrMsg = _('Could not update the language description because');
				$DbgMsg = _('The SQL that was used to update the language description and failed was');

				if (count($ItemDescriptionLanguagesArray)>0){
					foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
						if ($LanguageId != ''){
							$result = DB_query("DELETE FROM stockdescriptiontranslations WHERE stockid='" . $StockID . "' AND language_id='" . $LanguageId . "'", $ErrMsg, $DbgMsg, true);
							$result = DB_query("INSERT INTO stockdescriptiontranslations (stockid,
																						language_id,
																						descriptiontranslation,
																						longdescriptiontranslation)
												VALUES('" . $StockID . "','" .
															$LanguageId . "', '" .
															$_POST['Description_' . str_replace('.','_',$LanguageId)]  . "', '" .
															$_POST['LongDescription_' . str_replace('.','_',$LanguageId)].
															"')",$ErrMsg,$DbgMsg,true);
						}
					}
					/*
					foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
						$DescriptionTranslation = $_POST['Description_' . str_replace('.', '_', $LanguageId)];
							//WARNING: It DOES NOT update if database row DOES NOT exist.
							$sql = "UPDATE stockdescriptiontranslations " .
									"SET descriptiontranslation='" . $DescriptionTranslation . "' " .
									"WHERE stockid='" . $StockID . "' AND (language_id='" . $LanguageId. "')";
							$result = DB_query($sql, $ErrMsg, $DbgMsg, true);
					}
					*/

				}

				/* Activate the needs revision flag for translations for modified descriptions */
				if ($OldDescription != $_POST['Description'] OR $OldLongDescription != $_POST['LongDescription']){
					$sql = "UPDATE stockdescriptiontranslations
						SET needsrevision = '0'
						WHERE stockid='".$StockID."'";
					$ErrMsg = _('The stock description translations could not be updated because');
					$DbgMsg = _('The SQL that was used to set the flag for translation revision failed was');
					$result = DB_query($sql,$ErrMsg,$DbgMsg,true);
				}


				//delete any properties for the item no longer relevant with the change of category
				$result = DB_query("DELETE FROM stockitemproperties WHERE stockid ='" . $StockID . "'",$ErrMsg, $DbgMsg, true);

				//now insert any item properties
				for ($i=0;$i<$_POST['PropertyCounter'];$i++){

					if ($_POST['PropType' . $i] ==2){
						if ($_POST['PropValue' . $i]=='on'){
							$_POST['PropValue' . $i]=1;
						} else {
							$_POST['PropValue' . $i]=0;
						}
					}
					if ($_POST['PropNumeric' .$i]==1){
							$_POST['PropValue' . $i]=filter_number_format($_POST['PropValue' . $i]);
					} else {
						$_POST['PropValue' . $i]=$_POST['PropValue' . $i];
					}
					$result = DB_query("INSERT INTO stockitemproperties (stockid,
																		stkcatpropid,
																		value)
														VALUES ('" . $StockID . "',
																'" . $_POST['PropID' . $i] . "',
																'" . $_POST['PropValue' . $i] . "')",
										$ErrMsg,$DbgMsg,true);
				} //end of loop around properties defined for the category

				if ($OldStockAccount != $NewStockAct AND $_SESSION['CompanyRecord']['gllink_stock']==1) {
				/*Then we need to make a journal to transfer the cost to the new stock account */
					$JournalNo = GetNextTransNo(0); //enter as a journal
					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
										VALUES ( 0,
												'" . $JournalNo . "',
												'" . Date('Y-m-d') . "',
												'" . GetPeriod(Date($_SESSION['DefaultDateFormat'])) . "',
												'" . $NewStockAct . "',
												'" . $StockID . ' ' . _('Change stock category') . "',
												'" . ($UnitCost* $StockQtyRow[0]) . "')";
					$ErrMsg =  _('The stock cost journal could not be inserted because');
					$DbgMsg = _('The SQL that was used to create the stock cost journal and failed was');
					$result = DB_query($SQL, $ErrMsg, $DbgMsg,true);
					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
										VALUES ( 0,
												'" . $JournalNo . "',
												'" . Date('Y-m-d') . "',
												'" . GetPeriod(Date($_SESSION['DefaultDateFormat'])) . "',
												'" . $OldStockAccount . "',
												'" . $StockID . ' ' . _('Change stock category') . "',
												'" . (-$UnitCost* $StockQtyRow[0]) . "')";
					$result = DB_query($SQL, $ErrMsg, $DbgMsg,true);

				} /* end if the stock category changed and forced a change in stock cost account */
				if ($OldWIPAccount != $NewWIPAct AND $_SESSION['CompanyRecord']['gllink_stock']==1) {
				/*Then we need to make a journal to transfer the cost  of WIP to the new WIP account */
				/*First get the total cost of WIP for this category */

					$WOCostsResult = DB_query("SELECT workorders.costissued,
													SUM(woitems.qtyreqd * woitems.stdcost) AS costrecd
												FROM woitems INNER JOIN workorders
												ON woitems.wo = workorders.wo
												INNER JOIN stockmaster
												ON woitems.stockid=stockmaster.stockid
												WHERE stockmaster.stockid='". $StockID . "'
												AND workorders.closed=0
												GROUP BY workorders.costissued",
												_('Error retrieving value of finished goods received and cost issued against work orders for this item'));
					$WIPValue = 0;
					while ($WIPRow=DB_fetch_array($WOCostsResult)){
						$WIPValue += ($WIPRow['costissued']-$WIPRow['costrecd']);
					}
					if ($WIPValue !=0){
						$JournalNo = GetNextTransNo(0); //enter as a journal
						$SQL = "INSERT INTO gltrans (type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount)
											VALUES ( 0,
													'" . $JournalNo . "',
													'" . Date('Y-m-d') . "',
													'" . GetPeriod(Date($_SESSION['DefaultDateFormat'])) . "',
													'" . $NewWIPAct . "',
													'" . $StockID . ' ' . _('Change stock category') . "',
													'" . $WIPValue . "')";
						$ErrMsg =  _('The WIP cost journal could not be inserted because');
						$DbgMsg = _('The SQL that was used to create the WIP cost journal and failed was');
						$result = DB_query($SQL, $ErrMsg, $DbgMsg,true);
						$SQL = "INSERT INTO gltrans (type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount)
											VALUES ( 0,
													'" . $JournalNo . "',
													'" . Date('Y-m-d') . "',
													'" . GetPeriod(Date($_SESSION['DefaultDateFormat'])) . "',
													'" . $OldWIPAccount . "',
													'" . $StockID . ' ' . _('Change stock category') . "',
													'" . (-$WIPValue) . "')";
						$result = DB_query($SQL, $ErrMsg, $DbgMsg,true);
					}
				} /* end if the stock category changed and forced a change in WIP account */
				DB_Txn_Commit();
				prnMsg( _('Stock Item') . ' ' . $StockID . ' ' . _('has been updated'), 'success');
				echo '<br />';
			}

		} else { //it is a NEW part
			//but lets be really sure here
			$result = DB_query("SELECT stockid
								FROM stockmaster
								WHERE stockid='" . $StockID ."'");

			if (DB_num_rows($result)==1){
				prnMsg(_('The stock code entered is actually already in the database - duplicate stock codes are prohibited by the system. Try choosing an alternative stock code'),'error');
				$InputError = 1;
				$Errors[$i] = 'StockID';
				$i++;
			} else {
				DB_Txn_Begin();
				$sql = "INSERT INTO stockmaster (stockid,
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
												netweight,
												barcode,
												discountcategory,
												taxcatid,
												decimalplaces,
												shrinkfactor,
												pansize)
							VALUES ('".$StockID."',
								'" . $_POST['Description'] . "',
								'" . $_POST['LongDescription'] . "',
								'" . $_POST['CategoryID'] . "',
								'" . $_POST['Units'] . "',
								'" . $_POST['MBFlag'] . "',
								'" . filter_number_format($_POST['EOQ']) . "',
								'" . $_POST['Discontinued'] . "',
								'" . $_POST['Controlled'] . "',
								'" . $_POST['Serialised']. "',
								'" . $_POST['Perishable']. "',
								'" . filter_number_format($_POST['Volume']) . "',
								'" . filter_number_format($_POST['GrossWeight']) . "',
								'" . filter_number_format($_POST['NetWeight']) . "',
								'" . $_POST['BarCode'] . "',
								'" . $_POST['DiscountCategory'] . "',
								'" . $_POST['TaxCat'] . "',
								'" . $_POST['DecimalPlaces']. "',
								'" . filter_number_format($_POST['ShrinkFactor']) . "',
								'" . filter_number_format($_POST['Pansize']) . "')";

				$ErrMsg =  _('The item could not be added because');
				$DbgMsg = _('The SQL that was used to add the item failed was');
				$result = DB_query($sql, $ErrMsg, $DbgMsg,'',true);
				if (DB_error_no() ==0) {
					//now insert the language descriptions
					$ErrMsg = _('Could not update the language description because');
					$DbgMsg = _('The SQL that was used to update the language description and failed was');
					if (count($ItemDescriptionLanguages)>0){
						foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
							if ($LanguageId != '' AND $_POST['Description_' . str_replace('.','_',$LanguageId)] != ''){
								$result = DB_query("INSERT INTO stockdescriptiontranslations (stockid,
																							language_id,
																							descriptiontranslation,
																							longdescriptiontranslation)
													VALUES('" . $StockID . "','" .
																$LanguageId . "', '" .
																$_POST['Description_' . str_replace('.','_',$LanguageId)]  . "', '" .
																$_POST['longDescription_' . str_replace('.','_',$LanguageId)].
																"')",$ErrMsg,$DbgMsg,true);
							}
						}
					}
					//now insert any item properties
					for ($i=0;$i<$_POST['PropertyCounter'];$i++){

						if ($_POST['PropType' . $i] ==2){
							if ($_POST['PropValue' . $i]=='on'){
								$_POST['PropValue' . $i]=1;
							} else {
								$_POST['PropValue' . $i]=0;
							}
			}

						if ($_POST['PropNumeric' .$i]==1){
							$_POST['PropValue' . $i]=filter_number_format($_POST['PropValue' . $i]);
						} else {
							$_POST['PropValue' . $i]=$_POST['PropValue' . $i];
						}

					$result = DB_query("INSERT INTO stockitemproperties (stockid,
													stkcatpropid,
													value)
													VALUES ('" . $StockID . "',
														'" . $_POST['PropID' . $i] . "',
														'" . $_POST['PropValue' . $i] . "')",
								$ErrMsg,$DbgMsg,true);
					} //end of loop around properties defined for the category

					//Add data to locstock

					$sql = "INSERT INTO locstock (loccode,
													stockid)
										SELECT locations.loccode,
										'" . $StockID . "'
										FROM locations";

					$ErrMsg =  _('The locations for the item') . ' ' . $StockID .  ' ' . _('could not be added because');
					$DbgMsg = _('NB Locations records can be added by opening the utility page') . ' <i>Z_MakeStockLocns.php</i> ' . _('The SQL that was used to add the location records that failed was');
					$InsResult = DB_query($sql,$ErrMsg,$DbgMsg,true);
					DB_Txn_Commit();
					if (DB_error_no() ==0) {
						prnMsg( _('New Item') .' ' . '<a href="SelectProduct.php?StockID=' . $StockID . '">' . $StockID . '</a> '. _('has been added to the database') .
							'<br />' . _('NB: The item cost and pricing must also be setup') .
							'<br />' . '<a target="_blank" href="StockCostUpdate.php?StockID=' . $StockID . '">' . _('Enter Item Cost') . '</a>
							<br />' . '<a target="_blank" href="Prices.php?Item=' . $StockID . '">' . _('Enter Item Prices') . '</a> ','success');
						echo '<br />';
						unset($_POST['Description']);
						unset($_POST['LongDescription']);
						unset($_POST['EOQ']);
// Leave Category ID set for ease of batch entry
//						unset($_POST['CategoryID']);
						unset($_POST['Units']);
						unset($_POST['MBFlag']);
						unset($_POST['Discontinued']);
						unset($_POST['Controlled']);
						unset($_POST['Serialised']);
						unset($_POST['Perishable']);
						unset($_POST['Volume']);
						unset($_POST['GrossWeight']);
						unset($_POST['NetWeight']);
						unset($_POST['BarCode']);
						unset($_POST['ReorderLevel']);
						unset($_POST['DiscountCategory']);
						unset($_POST['DecimalPlaces']);
						unset($_POST['ShrinkFactor']);
						unset($_POST['Pansize']);
						unset($StockID);
						foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
							unset($_POST['Description_' . str_replace('.','_',$LanguageId)]);
						}
						$New=1;
					}//ALL WORKED SO RESET THE FORM VARIABLES
				}//THE INSERT OF THE NEW CODE WORKED SO BANG IN THE STOCK LOCATION RECORDS TOO
			}//END CHECK FOR ALREADY EXISTING ITEM OF THE SAME CODE
		}


	} else {
		echo '<br />' .  "\n";
		prnMsg( _('Validation failed, no updates or deletes took place'), 'error');
	}

} elseif (isset($_POST['delete']) AND mb_strlen($_POST['delete']) >1 ) {
//the button to delete a selected record was clicked instead of the submit button

	$CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS IN 'StockMoves'

	$sql= "SELECT COUNT(*) FROM stockmoves WHERE stockid='".$StockID."' GROUP BY stockid";
	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		$CancelDelete = 1;
		prnMsg( _('Cannot delete this stock item because there are stock movements that refer to this item'),'warn');
		echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('stock movements that refer to this item');

	} else {
		$sql= "SELECT COUNT(*) FROM bom WHERE component='".$StockID."' GROUP BY component";
		$result = DB_query($sql);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			$CancelDelete = 1;
			prnMsg( _('Cannot delete this item record because there are bills of material that require this part as a component'),'warn');
			echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('bills of material that require this part as a component');
		} else {
			$sql= "SELECT COUNT(*) FROM salesorderdetails WHERE stkcode='".$StockID."' GROUP BY stkcode";
			$result = DB_query($sql);
			$myrow = DB_fetch_row($result);
			if ($myrow[0]>0) {
				$CancelDelete = 1;
				prnMsg( _('Cannot delete this item record because there are existing sales orders for this part'),'warn');
				echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('sales order items against this part');
			} else {
				$sql= "SELECT COUNT(*) FROM salesanalysis WHERE stockid='".$StockID."' GROUP BY stockid";
				$result = DB_query($sql);
				$myrow = DB_fetch_row($result);
				if ($myrow[0]>0) {
					$CancelDelete = 1;
					prnMsg(_('Cannot delete this item because sales analysis records exist for it'),'warn');
					echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('sales analysis records against this part');
				} else {
					$sql= "SELECT COUNT(*) FROM purchorderdetails WHERE itemcode='".$StockID."' GROUP BY itemcode";
					$result = DB_query($sql);
					$myrow = DB_fetch_row($result);
					if ($myrow[0]>0) {
						$CancelDelete = 1;
						prnMsg(_('Cannot delete this item because there are existing purchase order items for it'),'warn');
						echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('purchase order item record relating to this part');
					} else {
						$sql = "SELECT SUM(quantity) AS qoh FROM locstock WHERE stockid='".$StockID."' GROUP BY stockid";
						$result = DB_query($sql);
						$myrow = DB_fetch_row($result);
						if ($myrow[0]!=0) {
							$CancelDelete = 1;
							prnMsg( _('Cannot delete this item because there is currently some stock on hand'),'warn');
							echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('on hand for this part');
						} else {
							$sql = "SELECT COUNT(*) FROM offers WHERE stockid='".$StockID."' GROUP BY stockid";
							$result = DB_query($sql);
							$myrow = DB_fetch_row($result);
							if ($myrow[0]!=0) {
								$CancelDelete = 1;
								prnMsg( _('Cannot delete this item because there are offers for this item'),'warn');
								echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('offers from suppliers for this part');
							} else {
								$sql = "SELECT COUNT(*) FROM tenderitems WHERE stockid='".$StockID."' GROUP BY stockid";
								$result = DB_query($sql);
								$myrow = DB_fetch_row($result);
								if ($myrow[0]!=0) {
									$CancelDelete = 1;
									prnMsg( _('Cannot delete this item because there are tenders for this item'),'warn');
									echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('tenders from suppliers for this part');
								}
							}
						}
					}
				}
			}
		}

	}
	if ($CancelDelete==0) {
		$result = DB_Txn_Begin();

			/*Deletes LocStock records*/
			$sql ="DELETE FROM locstock WHERE stockid='".$StockID."'";
			$result=DB_query($sql,_('Could not delete the location stock records because'),'',true);
			/*Deletes Price records*/
			$sql ="DELETE FROM prices WHERE stockid='".$StockID."'";
			$result=DB_query($sql,_('Could not delete the prices for this stock record because'),'',true);
			/*and cascade deletes in PurchData */
			$sql ="DELETE FROM purchdata WHERE stockid='".$StockID."'";
			$result=DB_query($sql,_('Could not delete the purchasing data because'),'',true);
			/*and cascade delete the bill of material if any */
			$sql = "DELETE FROM bom WHERE parent='".$StockID."'";
			$result=DB_query($sql,_('Could not delete the bill of material because'),'',true);
			//and cascade delete the item properties
			$sql="DELETE FROM stockitemproperties WHERE stockid='".$StockID."'";
			$result=DB_query($sql, _('Could not delete the item properties'),'',true);
			//and cascade delete the item descriptions in other languages
			$sql = "DELETE FROM stockdescriptiontranslations WHERE stockid='" . $StockID . "'";
			$result=DB_query($sql,_('Could not delete the item language descriptions'),'',true);
			$sql="DELETE FROM stockmaster WHERE stockid='".$StockID."'";
			$result=DB_query($sql, _('Could not delete the item record'),'',true);

		$result = DB_Txn_Commit();

		prnMsg(_('Deleted the stock master record for') . ' ' . $StockID . '....' .
		'<br />. . ' . _('and all the location stock records set up for the part') .
		'<br />. . .' . _('and any bill of material that may have been set up for the part') .
		'<br /> . . . .' . _('and any purchasing data that may have been set up for the part') .
		'<br /> . . . . .' . _('and any prices that may have been set up for the part'),'success');
		echo '<br />';
		unset($_POST['LongDescription']);
		unset($_POST['Description']);
		unset($_POST['EOQ']);
		unset($_POST['CategoryID']);
		unset($_POST['Units']);
		unset($_POST['MBFlag']);
		unset($_POST['Discontinued']);
		unset($_POST['Controlled']);
		unset($_POST['Serialised']);
		unset($_POST['Perishable']);
		unset($_POST['Volume']);
		unset($_POST['GrossWeight']);
		unset($_POST['NetWeight']);
		unset($_POST['BarCode']);
		unset($_POST['ReorderLevel']);
		unset($_POST['DiscountCategory']);
		unset($_POST['TaxCat']);
		unset($_POST['DecimalPlaces']);
		unset($_SESSION['SelectedStockItem']);
		foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
			unset($_POST['Description_' . str_replace('.','_',$LanguageId)]);
		}
		unset($StockID);

		$New=1;
	} //end if Delete Part
}


echo '<form name="ItemForm" enctype="multipart/form-data" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div>
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<input type="hidden" name="New" value="'.$New.'" />';

if (isset($StockID) && $StockID != '' && $InputError == 0){
	echo '<table width="100%">
			<tr>
				<td>',
					'<button ', ($hasPrev ? '' : 'disabled'), ' name="PreviousItem" type="submit" value="">',
						'<img alt="" src="', $RootPath, '/css/', $Theme, '/images/previous.svg" />',
						/*_('Previous Item'),*/
					'</button>', // "Previous" button.
				'</td>',
				'<td width="90%">&nbsp;</td>',
				'<td>',
					'<button ', ($hasNext ? '' : 'disabled'), ' name="NextItem" type="submit" value="">',
						/*_('Next Item'),*/
						'<img alt="" src="', $RootPath, '/css/', $Theme, '/images/next.svg" />',
					'</button>', // "Next" button.
				'</td>
			</tr>
		</table>';
}

echo '<table class="selection">';

if (!isset($StockID) OR $StockID=='' or isset($_POST['UpdateCategories'])) {

/*If the page was called without $StockID passed to page then assume a new stock item is to be entered show a form with a part Code field other wise the form showing the fields with the existing entries against the part will show for editing with only a hidden StockID field. New is set to flag that the page may have called itself and still be entering a new part, in which case the page needs to know not to go looking up details for an existing part*/
	if (!isset($StockID)) {
		$StockID='';
	}
	if ($New==1) {
		echo '<tr>
				<td>' .  _('Item Code'). ':</td>
				<td><input type="text" ' . (in_array('StockID',$Errors) ?  'class="inputerror"' : '' ) . ' data-type="no-illegal-chars" autofocus="autofocus" required="required"  value="'.$StockID.'" name="StockID" size="20" maxlength="20"  title ="'._('Input the stock code, the following characters are prohibited:') . ' \' &quot; + . &amp; \\ &gt; &lt;" placeholder="'._('alpha-numeric only').'" /></td>
			</tr>';
	} else {
		echo '<tr>
				<td>' .  _('Item Code'). ':</td>
				<td>' . $StockID . '<input type="hidden" name ="StockID" value="' . $StockID . '" /></td>
			</tr>';
	}

} elseif (!isset($_POST['UpdateCategories']) AND $InputError!=1) { // Must be modifying an existing item and no changes made yet

	$sql = "SELECT stockid,
					description,
					longdescription,
					categoryid,
					units,
					mbflag,
					discontinued,
					controlled,
					serialised,
					perishable,
					eoq,
					volume,
					grossweight,
					netweight,
					barcode,
					discountcategory,
					taxcatid,
					decimalplaces,
					nextserialno,
					pansize,
					shrinkfactor
			FROM stockmaster
			WHERE stockid = '".$StockID."'";

	$result = DB_query($sql);
	$myrow = DB_fetch_array($result);

	$_POST['LongDescription'] = $myrow['longdescription'];
	$_POST['Description'] = $myrow['description'];
	$_POST['EOQ']  = $myrow['eoq'];
	$_POST['CategoryID']  = $myrow['categoryid'];
	$_POST['Units']  = $myrow['units'];
	$_POST['MBFlag']  = $myrow['mbflag'];
	$_POST['Discontinued']  = $myrow['discontinued'];
	$_POST['Controlled']  = $myrow['controlled'];
	$_POST['Serialised']  = $myrow['serialised'];
	$_POST['Perishable']  = $myrow['perishable'];
	$_POST['Volume']  = $myrow['volume'];
	$_POST['GrossWeight']  = $myrow['grossweight'];
	$_POST['NetWeight']  = $myrow['netweight'];
	$_POST['BarCode']  = $myrow['barcode'];
	$_POST['DiscountCategory']  = $myrow['discountcategory'];
	$_POST['TaxCat'] = $myrow['taxcatid'];
	$_POST['DecimalPlaces'] = $myrow['decimalplaces'];
	$_POST['NextSerialNo'] = $myrow['nextserialno'];
	$_POST['Pansize'] = $myrow['pansize'];
	$_POST['ShrinkFactor'] = $myrow['shrinkfactor'];


	$sql = "SELECT descriptiontranslation, longdescriptiontranslation, language_id FROM stockdescriptiontranslations WHERE stockid='" . $StockID . "' AND (";
	foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
		$sql .= "language_id='" . $LanguageId ."' OR ";
	}
	$sql = mb_substr($sql,0,mb_strlen($sql)-3) . ')';
	$result = DB_query($sql);
	while ($myrow = DB_fetch_array($result)){
		$_POST['Description_' . str_replace('.','_',$myrow['language_id'])] = $myrow['descriptiontranslation'];
		$_POST['LongDescription_' . str_replace('.','_',$myrow['language_id'])] = $myrow['longdescriptiontranslation'];
	}

	echo '<tr><td>' . _('Item Code') . ':</td>
			<td>' . $StockID . '<input type="hidden" name="StockID" value="' . $StockID . '" /></td>
			</tr>';

} else { // some changes were made to the data so don't re-set form variables to DB ie the code above
	echo '<tr>
			<td>' . _('Item Code') . ':</td>
			<td>' . $StockID . '<input type="hidden" name="StockID" value="' . $StockID . '" /></td>
		</tr>';
}

if (isset($_POST['Description'])) {
	$Description = $_POST['Description'];
} else {
	$Description ='';
}
echo '<tr>
		<td>' . _('Part Description') . ' (' . _('short') . '):</td>
		<td><input ' . (in_array('Description',$Errors) ?  'class="inputerror"' : '' ) .' type="text" ' . ($New==0?'autofocus="autofocus"':'') . ' name="Description" required="required" size="52" maxlength="50" value="' . stripslashes($Description) . '" /></td>
	</tr>';

foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
	if ($LanguageId!=''){
		//unfortunately cannot have points in POST variables so have to mess with the language id
		$PostVariableName = 'Description_' . str_replace('.','_',$LanguageId);
		if (!isset($_POST[$PostVariableName])){
			$_POST[$PostVariableName] ='';
		}
		echo '<tr>
				<td>' . $LanguagesArray[$LanguageId]['LanguageName'] . ' ' . _('Description') . ':</td>
				<td><input type="text" name="'. $PostVariableName . '" size="52" maxlength="50" value="' . $_POST[$PostVariableName] . '" title="' . _('This language translation of the item will be used in invoices and credits to customers who are defined to use this language. The language translations to maintain here can be configured in the system parameters page') .  '" /></td>
			</tr>';
	}
}

if (isset($_POST['LongDescription'])) {
	$LongDescription = AddCarriageReturns($_POST['LongDescription']);
} else {
	$LongDescription ='';
}
echo '<tr>
		<td>' . _('Part Description') . ' (' . _('long') . '):</td>
		<td><textarea ' . (in_array('LongDescription',$Errors) ?  'class="texterror"' : '' ) .'  name="LongDescription" cols="40" rows="3">' . stripslashes($LongDescription) . '</textarea></td>
	</tr>';

foreach ($ItemDescriptionLanguagesArray as $LanguageId) {
	if ($LanguageId!=''){
		//unfortunately cannot have points in POST variables so have to mess with the language id
		$PostVariableName = 'LongDescription_' . str_replace('.','_',$LanguageId);
		if (!isset($_POST[$PostVariableName])){
			$_POST[$PostVariableName] ='';
		}
		echo '<tr>
				<td>' . $LanguagesArray[$LanguageId]['LanguageName'] . ' ' . _('Long Description') . ':</td>
				<td><textarea name="'. $PostVariableName . '" cols="40" rows="3">' . stripslashes(AddCarriageReturns($_POST[$PostVariableName])) . '</textarea></td>
			</tr>';
	}
}

echo '<tr>
		<td>' .  _('Image File (' . implode(", ", $SupportedImgExt) . ')') . ':</td>
		<td><input type="file" id="ItemPicture" name="ItemPicture" />
		<br /><input type="checkbox" name="ClearImage" id="ClearImage" value="1" > '._('Clear Image').'
		</td>';

$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $StockID . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
if (extension_loaded('gd') && function_exists('gd_info') && isset($StockID) && !empty($StockID)){
	$StockImgLink = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
		'&amp;StockID='.urlencode($StockID).
		'&amp;text='.
		'&amp;width=64'.
		'&amp;height=64'.
		'" alt="" />';
} else if (file_exists ($imagefile)) {
	$StockImgLink = '<img src="' . $imagefile . '" height="64" width="64" />';
} else {
	$StockImgLink = _('No Image');
}

if ($StockImgLink!=_('No Image')) {
	echo '<td>' . _('Image') . '<br />' . $StockImgLink . '</td>';
}

if (isset($_POST['ClearImage']) ) {
	foreach ($SupportedImgExt as $ext) {
		$file = $_SESSION['part_pics_dir'] . '/' . $StockID . '.' . $ext;
		if (file_exists ($file) ) {
			//workaround for many variations of permission issues that could cause unlink fail
			@unlink($file);
			if(is_file($imagefile)) {
               prnMsg(_('You do not have access to delete this item image file.'),'error');
			} else {
				$StockImgLink = _('No Image');
			}
		}
	}
}
echo '</tr>';

echo '<tr>
		<td>' . _('Category') . ':</td>
		<td><select name="CategoryID" onchange="ReloadForm(ItemForm.UpdateCategories)">';

$sql = "SELECT categoryid, categorydescription FROM stockcategory";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$ErrMsg,$DbgMsg);

while ($myrow=DB_fetch_array($result)){
	if (!isset($_POST['CategoryID']) OR  $myrow['categoryid']==$_POST['CategoryID']){
		echo '<option selected="selected" value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
	} else {
		echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
	}
	$Category=$myrow['categoryid'];
}

if (!isset($_POST['CategoryID'])) {
	$_POST['CategoryID']=$Category;
}

echo '</select><a target="_blank" href="'. $RootPath . '/StockCategories.php">' . _('Add or Modify Stock Categories') . '</a></td>
	</tr>';

if (!isset($_POST['EOQ']) OR $_POST['EOQ']==''){
	$_POST['EOQ']=0;
}

if (!isset($_POST['Volume']) OR $_POST['Volume']==''){
	$_POST['Volume']=0;
}
if (!isset($_POST['GrossWeight']) OR $_POST['GrossWeight']==''){
	$_POST['GrossWeight']=0;
}
if (!isset($_POST['NetWeight']) OR $_POST['NetWeight']==''){
	$_POST['NetWeight']=0;
}
if (!isset($_POST['Controlled']) OR $_POST['Controlled']==''){
	$_POST['Controlled']=0;
}
if (!isset($_POST['Serialised']) OR  $_POST['Serialised']=='' || $_POST['Controlled']==0){
	$_POST['Serialised']=0;
}
if (!isset($_POST['DecimalPlaces']) OR  $_POST['DecimalPlaces']==''){
	$_POST['DecimalPlaces']=0;
}
if (!isset($_POST['Discontinued']) OR  $_POST['Discontinued']==''){
	$_POST['Discontinued']=0;
}
if (!isset($_POST['Pansize'])) {
	$_POST['Pansize']=0;
}
if (!isset($_POST['ShrinkFactor'])) {
	$_POST['ShrinkFactor']=0;
}
if (!isset($_POST['NextSerialNo'])) {
	$_POST['NextSerialNo']=0;
}


echo '<tr>
		<td>' . _('Economic Order Quantity') . ':</td>
		<td><input ' . (in_array('EOQ',$Errors) ?  'class="inputerror"' : '' ) .'   type="text" class="number" name="EOQ" size="12" maxlength="10" value="' . locale_number_format($_POST['EOQ'],'Variable') . '" /></td></tr>';

echo '<tr>
		<td>' . _('Packaged Volume (metres cubed)') . ':</td>
		<td><input ' . (in_array('Volume',$Errors) ?  'class="inputerror"' : '' ) .'   type="text" class="number" name="Volume" size="12" maxlength="10" value="' . locale_number_format($_POST['Volume'],'Variable') . '" /></td>
	</tr>';

echo '<tr>
		<td>' . _('Packaged Gross Weight (KGs)') . ':</td><td><input ' . (in_array('GrossWeight',$Errors) ?  'class="inputerror"' : '' ) .'   type="text" class="number" name="GrossWeight" size="12" maxlength="10" value="' . locale_number_format($_POST['GrossWeight'],'Variable') . '" /></td>
	</tr>';

echo '<tr>
		<td>' . _('Net Weight (KGs)') . ':</td><td><input ' . (in_array('NetWeight',$Errors) ?  'class="inputerror"' : '' ) .'   type="text" class="number" name="NetWeight" size="12" maxlength="10" value="' . locale_number_format($_POST['NetWeight'],'Variable') . '" /></td>
	</tr>';

	echo '<tr>
		<td>' . _('Units of Measure') . ':</td>
		<td><select ' . (in_array('Description',$Errors) ?  'class="selecterror"' : '' ) .'  name="Units">';

$sql = "SELECT unitname FROM unitsofmeasure ORDER by unitname";
$UOMResult = DB_query($sql);

if (!isset($_POST['Units'])) {
	$UOMrow['unitname']=_('each');
}
while( $UOMrow = DB_fetch_array($UOMResult) ) {
	 if (isset($_POST['Units']) AND $_POST['Units']==$UOMrow['unitname']){
		echo '<option selected="selected" value="' . $UOMrow['unitname'] . '">' . $UOMrow['unitname'] . '</option>';
	 } else {
		echo '<option value="' . $UOMrow['unitname'] . '">' . $UOMrow['unitname']  . '</option>';
	 }
}

echo '</select></td>
	</tr>';

echo '<tr>
		<td>' . _('Assembly, Kit, Manufactured or Service/Labour') . ':</td>
		<td><select name="MBFlag">';
if ($_POST['MBFlag']=='A'){
	echo '<option selected="selected" value="A">' . _('Assembly') . '</option>';
} else {
	echo '<option value="A">' . _('Assembly') . '</option>';
}
if (!isset($_POST['MBFlag']) OR  $_POST['MBFlag']=='K'){
	echo '<option selected="selected" value="K">' . _('Kit') . '</option>';
} else {
	echo '<option value="K">' . _('Kit') . '</option>';
}
if (!isset($_POST['MBFlag']) OR  $_POST['MBFlag']=='M'){
	echo '<option selected="selected" value="M">' . _('Manufactured') . '</option>';
} else {
	echo '<option value="M">' . _('Manufactured') . '</option>';
}
if (!isset($_POST['MBFlag']) OR  $_POST['MBFlag']=='G' OR !isset($_POST['MBFlag']) OR $_POST['MBFlag']==''){
	echo '<option selected="selected" value="G">' . _('Phantom') . '</option>';
} else {
	echo '<option value="G">' . _('Phantom') . '</option>';
}
if (!isset($_POST['MBFlag']) OR  $_POST['MBFlag']=='B' OR !isset($_POST['MBFlag']) OR $_POST['MBFlag']==''){
	echo '<option selected="selected" value="B">' . _('Purchased') . '</option>';
} else {
	echo '<option value="B">' . _('Purchased') . '</option>';
}

if (isset($_POST['MBFlag']) AND $_POST['MBFlag']=='D'){
	echo '<option selected="selected" value="D">' . _('Service/Labour') . '</option>';
} else {
	echo '<option value="D">' . _('Service/Labour') . '</option>';
}

echo '</select></td>
	</tr>';

echo '<tr>
		<td>' . _('Current or Obsolete') . ':</td>
		<td><select name="Discontinued">';

if ($_POST['Discontinued']==0){
	echo '<option selected="selected" value="0">' . _('Current') . '</option>';
} else {
	echo '<option value="0">' . _('Current') . '</option>';
}
if ($_POST['Discontinued']==1){
	echo '<option selected="selected" value="1">' . _('Obsolete') . '</option>';
} else {
	echo '<option value="1">' . _('Obsolete') . '</option>';
}
echo '</select></td>
		</tr>';

echo '<tr>
		<td>' . _('Batch, Serial or Lot Control') . ':</td>
		<td><select name="Controlled">';

if ($_POST['Controlled']==0){
	echo '<option selected="selected" value="0">' . _('No Control') . '</option>';
} else {
		echo '<option value="0">' . _('No Control') . '</option>';
}
if ($_POST['Controlled']==1){
	echo '<option selected="selected" value="1">' . _('Controlled'). '</option>';
} else {
	echo '<option value="1">' . _('Controlled'). '</option>';
}
echo '</select></td></tr>';

echo '<tr><td>' . _('Serialised') . ':</td><td><select ' . (in_array('Serialised',$Errors) ?  'class="selecterror"' : '' ) .'  name="Serialised">';

if ($_POST['Serialised']==0){
		echo '<option selected="selected" value="0">' . _('No'). '</option>';
} else {
		echo '<option value="0">' . _('No'). '</option>';
}
if ($_POST['Serialised']==1){
		echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
} else {
		echo '<option value="1">' . _('Yes'). '</option>';
}
echo '</select><i>' . _('Note') . ', ' . _('this has no effect if the item is not Controlled') . '</i></td>
	</tr>';

if ($_POST['Serialised']==1 AND $_POST['MBFlag']=='M'){
	echo '<tr>
			<td>' . _('Next Serial No (>0 for auto numbering)') . ':</td>
			<td><input ' . (in_array('NextSerialNo',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="NextSerialNo" size="15" maxlength="15" value="' . $_POST['NextSerialNo'] . '" /></td></tr>';
} else {
	echo '<tr><td><input type="hidden" name="NextSerialNo" value="0" /></td></tr>';
}

echo '<tr>
		<td>' . _('Perishable') . ':</td>
		<td><select name="Perishable">';

if (!isset($_POST['Perishable']) OR  $_POST['Perishable']==0){
		echo '<option selected="selected" value="0">' . _('No'). '</option>';
} else {
		echo '<option value="0">' . _('No'). '</option>';
}
if (isset($_POST['Perishable']) AND $_POST['Perishable']==1){
		echo '<option selected="selected" value="1">' . _('Yes'). '</option>';
} else {
		echo '<option value="1">' . _('Yes'). '</option>';
}
echo '</select></td></tr>';

echo '<tr>
		<td>' . _('Decimal Places for display Quantity') . ':</td>
		<td><input type="text" class="number" name="DecimalPlaces" size="1" maxlength="1" value="' . $_POST['DecimalPlaces'] . '" /></td>
	</tr>';

if (isset($_POST['BarCode'])) {
	$BarCode = $_POST['BarCode'];
} else {
	$BarCode='';
}
echo '<tr>
		<td>' . _('Bar Code') . ':</td>
		<td><input ' . (in_array('BarCode',$Errors) ?  'class="inputerror"' : '' ) .'  type="text" name="BarCode" size="22" maxlength="20" value="' . $BarCode . '" /></td>
	</tr>';

if (isset($_POST['DiscountCategory'])) {
	$DiscountCategory = $_POST['DiscountCategory'];
} else {
	$DiscountCategory='';
}
echo '<tr>
		<td>' . _('Discount Category') . ':</td>
		<td><input type="text" name="DiscountCategory" size="2" maxlength="2" value="' . $DiscountCategory . '" /></td>
	</tr>';

echo '<tr>
		<td>' . _('Tax Category') . ':</td>
		<td><select name="TaxCat">';
$sql = "SELECT taxcatid, taxcatname FROM taxcategories ORDER BY taxcatname";
$result = DB_query($sql);

if (!isset($_POST['TaxCat'])){
	$_POST['TaxCat'] = $_SESSION['DefaultTaxCategory'];
}

while ($myrow = DB_fetch_array($result)) {
	if ($_POST['TaxCat'] == $myrow['taxcatid']){
		echo '<option selected="selected" value="' . $myrow['taxcatid'] . '">' . $myrow['taxcatname'] . '</option>';
	} else {
		echo '<option value="' . $myrow['taxcatid'] . '">' . $myrow['taxcatname'] . '</option>';
	}
} //end while loop

echo '</select></td>
	</tr>';

echo '<tr>
		<td><label for="PanSize">' . _('Pan Size') . ':</label></td>
		<td><input class="number" id="PanSize" maxlength="6" name="Pansize" size="6" title="' . _('Order multiple. It is the minimum packing quantity.') . '" type="text" value="' . locale_number_format($_POST['Pansize'],0) . '" /></td>
	</tr>
	 <tr>
		<td><label for="ShrinkageFactor">' . _('Shrinkage Factor') . ':</label></td>
		<td><input class="number" id="ShrinkageFactor" maxlength="6" name="ShrinkFactor" size="6" title="' . _('Amount by which an output falls short of the estimated or planned output.') . '" type="text" value="' . locale_number_format($_POST['ShrinkFactor'],'Variable') . '" /></td>
	</tr>';

echo '</table>
	<div class="centre">';

if (!isset($_POST['CategoryID'])) {
	$_POST['CategoryID'] = '';
}

$sql = "SELECT stkcatpropid,
				label,
				controltype,
				defaultvalue,
				numericvalue,
				minimumvalue,
				maximumvalue
		FROM stockcatproperties
		WHERE categoryid ='" . $_POST['CategoryID'] . "'
		AND reqatsalesorder =0
		ORDER BY stkcatpropid";

$PropertiesResult = DB_query($sql);
$PropertyCounter = 0;
$PropertyWidth = array();

if (DB_num_rows($PropertiesResult)>0) {
echo '<br />
    <table class="selection">';
	echo '<tr>
			<th colspan="2">' . _('Item Category Properties') . '</th>
		</tr>';

while ($PropertyRow=DB_fetch_array($PropertiesResult)){

	if (isset($StockID)) {
		$PropValResult = DB_query("SELECT value FROM
									stockitemproperties
									WHERE stockid='" . $StockID . "'
									AND stkcatpropid ='" . $PropertyRow['stkcatpropid']."'");
		$PropValRow = DB_fetch_row($PropValResult);
		$PropertyValue = $PropValRow[0];
	} else {
		$PropertyValue =  '';
	}
    echo '<tr>
            <td>';
  	        echo '<input type="hidden" name="PropID' . $PropertyCounter . '" value="' .$PropertyRow['stkcatpropid'] .'" />';
	        echo '<input type="hidden" name="PropNumeric' . $PropertyCounter . '" value="' .$PropertyRow['numericvalue'] .'" />';
            echo $PropertyRow['label'] . '</td>

			<td>';
	switch ($PropertyRow['controltype']) {
	 	case 0; //textbox
	 		if ($PropertyRow['numericvalue']==1) {
				echo '<input type="hidden" name="PropMin' . $PropertyCounter . '" value="' . $PropertyRow['minimumvalue'] . '" />';
				echo '<input type="hidden" name="PropMax' . $PropertyCounter . '" value="' . $PropertyRow['maximumvalue'] . '" />';

				echo '<input type="text" class="number" name="PropValue' . $PropertyCounter . '" size="20" maxlength="100" value="' . locale_number_format($PropertyValue,'Variable') . '" />';
				echo _('A number between') . ' ' . locale_number_format($PropertyRow['minimumvalue'],'Variable') . ' ' . _('and') . ' ' . locale_number_format($PropertyRow['maximumvalue'],'Variable') . ' ' . _('is expected');
			} else {
				echo '<input type="text" name="PropValue' . $PropertyCounter . '" size="20" maxlength="100" value="' . $PropertyValue . '" />';
			}
	 		break;
	 	case 1; //select box
	 		$OptionValues = explode(',',$PropertyRow['defaultvalue']);
			echo '<select name="PropValue' . $PropertyCounter . '">';
			foreach ($OptionValues as $PropertyOptionValue){
				if ($PropertyOptionValue == $PropertyValue){
					echo '<option selected="selected" value="' . $PropertyOptionValue . '">' . $PropertyOptionValue . '</option>';
				} else {
					echo '<option value="' . $PropertyOptionValue . '">' . $PropertyOptionValue . '</option>';
				}
			}
			echo '</select>';
			break;
		case 2; //checkbox
			echo '<input type="checkbox" name="PropValue' . $PropertyCounter . '"';
			if ($PropertyValue==1){
				echo 'checked';
			}
			echo ' />';
			break;
	} //end switch
	echo '<input type="hidden" name="PropType' . $PropertyCounter .'" value="' . $PropertyRow['controltype'] . '" />';
	echo '</td></tr>';
	$PropertyCounter++;

} //end loop round properties for the item category
unset($StockID);
echo '</table>';
}
echo '<input type="hidden" name="PropertyCounter" value="' . $PropertyCounter . '" />';
echo '<br />';

if ($New==1) {
	echo '<input type="submit" name="submit" value="' . _('Insert New Item') . '" />';
	echo '<input type="submit" name="UpdateCategories" style="visibility:hidden;width:1px" value="' . _('Categories') . '" />';

} else {

	// Now the form to enter the item properties

	echo '<input type="submit" name="submit" value="' . _('Update') . '" />';
	echo '<input type="submit" name="UpdateCategories" style="visibility:hidden;width:1px" value="' . _('Categories') . '" />';
	echo '<br /><br />';
	prnMsg( _('Only click the Delete button if you are sure you wish to delete the item!') . '<br />' . _('Checks will be made to ensure that there are no stock movements, sales analysis records, sales order items or purchase order items for the item') . '. ' . _('No deletions will be allowed if they exist') . '.', 'warn', _('WARNING'));
	echo '<br />
		<input type="submit" name="delete" value="' . _('Delete This Item') . '" onclick="return confirm(\'' . _('Are You Sure?') . '\');" />';
}

echo '</div>
    </div>
	</form>';
include('includes/footer.php');
?>

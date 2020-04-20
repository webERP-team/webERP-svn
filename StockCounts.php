<?php

include('includes/session.php');

$Title = _('Stock Check Sheets Entry');

include('includes/header.php');

echo '<form name="EnterCountsForm" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post" enctype="multipart/form-data">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' .
	_('Inventory Adjustment') . '" alt="" />' . ' ' . $Title . '</p>';

if (!isset($_POST['Action']) AND !isset($_GET['Action'])) {
	$_GET['Action'] = 'Enter';
}
if (isset($_POST['Action'])) {
	$_GET['Action'] = $_POST['Action'];
}

if ($_GET['Action']!='View' AND $_GET['Action']!='Enter'){
	$_GET['Action'] = 'Enter';
}

echo '<table class="selection"><tr>';
if ($_GET['Action']=='View'){
	echo '<td><a href="' . $RootPath . '/StockCounts.php?&amp;Action=Enter">' . _('Resuming Entering Counts') . '</a> </td><td>' . _('Viewing Entered Counts') . '</td>';
} else {
	echo '<td>' . _('Entering Counts')  . '</td><td> <a href="' . $RootPath . '/StockCounts.php?&amp;Action=View">' . _('View Entered Counts') . '</a></td>';
}
echo '</tr></table><br />';

$FieldHeadings = array(
	'StockCode',       	//  0 'STOCKCODE',
	'QtyCounted',	 	//  1 'QTYCOUNTED',
	'Reference'      	//  2 'REFERENCE'
);

if (isset($_GET['gettemplate'])) //download an import template
{

	// clean up any previous outputs
	ob_clean();

	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");

	// disposition / encoding on response body
	header("Content-Disposition: attachment; filename=ImportTemplate.csv");
	header("Content-Transfer-Encoding: binary");

	echo '"' . implode('","',$FieldHeadings) . '"';

	// exit cleanly to prevent any unwanted outputs
	exit;
}

if ($_GET['Action'] == 'Enter'){

	if (isset($_POST['EnterCounts'])){

		$Added=0;
		$Counter = isset($_POST['RowCount'])?$_POST['RowCount'] : 10; // Arbitrary number of 10 hard coded as default as originally used - should there be a setting?
			for ($i=1;$i<=$Counter;$i++){
			$InputError =False; //always assume the best to start with

			$Quantity = 'Qty_' . $i;
			$BarCode = 'BarCode_' . $i;
			$StockID = 'StockID_' . $i;
			$Reference = 'Ref_' . $i;

			if (strlen($_POST[$BarCode])>0){
				$sql = "SELECT stockmaster.stockid
								FROM stockmaster
								WHERE stockmaster.barcode='". $_POST[$BarCode] ."'";

				$ErrMsg = _('Could not determine if the part being ordered was a kitset or not because');
				$DbgMsg = _('The sql that was used to determine if the part being ordered was a kitset or not was ');
				$KitResult = DB_query($sql,$ErrMsg,$DbgMsg);
				$myrow=DB_fetch_array($KitResult);

				$_POST[$StockID] = strtoupper($myrow['stockid']);
			}

			if (mb_strlen($_POST[$StockID])>0){
				if (!is_numeric($_POST[$Quantity])){
					$InputError=True;
				}
			$SQL = "SELECT stockid FROM stockcheckfreeze WHERE stockid='" . $_POST[$StockID] . "'";
				$result = DB_query($SQL);
				if (DB_num_rows($result)==0){
					prnMsg( _('The stock code entered on line') . ' ' . $i . ' ' . _('is not a part code that has been added to the stock check file') . ' - ' . _('the code entered was') . ' ' . $_POST[$StockID] . '. ' . _('This line will have to be re-entered'),'warn');
					$InputError = True;
				}

				if ($InputError==False){
					$Added++;
					$sql = "INSERT INTO stockcounts (stockid,
									loccode,
									qtycounted,
									reference)
								VALUES ('" . $_POST[$StockID] . "',
									'" . $_POST['Location'] . "',
									'" . $_POST[$Quantity] . "',
									'" . $_POST[$Reference] . "')";

					$ErrMsg = _('The stock count line number') . ' ' . $i . ' ' . _('could not be entered because');
					$EnterResult = DB_query($sql,$ErrMsg);
				}
			}
		} // end of loop
		prnMsg($Added . _(' Stock Counts Entered'), 'success' );
		unset($_POST['EnterCounts']);
	} // end of if enter counts button hit
	else if(isset($_FILES['userfile']) and $_FILES['userfile']['name'])
	{
		//initialize
		$FieldTarget = count($FieldHeadings);
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

			//first off check if the item is in freeze
			$sql = "SELECT stockid FROM stockcheckfreeze WHERE stockid='" . $StockID . "'";
			$result = DB_query($sql);
			if (DB_num_rows($result)==0){
				$InputError = 1;
				prnMsg( _('Stock item "'. $StockID. '" is not a part code that has been added to the stock check file'),'warn');
			}

			//next validate inputs are sensible
			if (mb_strlen($myrow[2]) >20) {
				$InputError = 1;
				prnMsg(_('The reference field must be 20 characters or less long'),'error');
			}
			else if (!is_numeric($myrow[1])) {
				$InputError = 1;
				prnMsg (_('The quantity counted must be numeric') ,'error');
			}
			else if ($myrow[1] < 0) {
				$InputError = 1;
				prnMsg(_('The quantity counted must be zero or a positive number'),'error');
			}

			if ($InputError !=1){

				//attempt to insert the stock item
				$sql = "INSERT INTO stockcounts (stockid,
									loccode,
									qtycounted,
									reference)
								VALUES ('" . $myrow[0] . "',
									'" . $_POST['Location'] . "',
									'" . $myrow[1] . "',
									'" . $myrow[2] . "')";

				$ErrMsg = _('The stock count line number') . ' ' . $row . ' ' . _('could not be entered because');
				$DbgMsg = _('The SQL that was used to add the item failed was');
				$EnterResult = DB_query($sql,$ErrMsg,$DbgMsg,true);

				if (DB_error_no() != 0) {
					$InputError = 1;
					prnMsg(_($EnterResult),'error');
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
	} // end of if import file button hit

	$CatsResult = DB_query("SELECT DISTINCT stockcategory.categoryid,
								categorydescription
						FROM stockcategory INNER JOIN stockmaster
							ON stockcategory.categoryid=stockmaster.categoryid
							INNER JOIN stockcheckfreeze
							ON stockmaster.stockid=stockcheckfreeze.stockid");

	if (DB_num_rows($CatsResult) ==0) {
		prnMsg(_('The stock check sheets must be run first to create the stock check. Only once these are created can the stock counts be entered. Currently there is no stock check to enter counts for'),'error');
		echo '<div class="center"><a href="' . $RootPath . '/StockCheck.php">' . _('Create New Stock Check') . '</a></div>';
	} else {
		echo '<table cellpadding="2" class="selection">';
		echo '<tr>
				<th colspan="3">' ._('Stock Check Counts at Location') . ':<select name="Location">';
		$sql = "SELECT locations.loccode, locationname FROM locations
				INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
		$result = DB_query($sql);

		while ($myrow=DB_fetch_array($result)){

			if (isset($_POST['Location']) AND $myrow['loccode']==$_POST['Location']){
				echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			} else {
				echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			}
		}
		echo '</select>&nbsp;<input type="submit" name="EnterByCat" value="' . _('Enter By Category') . '" /><select name="StkCat" onChange="ReloadForm(EnterCountsForm.EnterByCat)" >';

		echo '<option value="">' . _('Not Yet Selected') . '</option>';

		while ($myrow=DB_fetch_array($CatsResult)){
			if ($_POST['StkCat']==$myrow['categoryid']) {
				echo '<option selected="selected" value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
			} else {
				echo '<option value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
			}
		}
		echo '</select></th></tr>';

		echo '<tr>
				<td></td><td>OR</td>
			</tr>
			<tr>
				<th colspan="3">
					<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
					' . _('Upload file') . ': <input name="userfile" type="file" />
					<input type="submit" value="' . _('Send File') . '" />
				</th>
				<td><a href="StockCounts.php?gettemplate=1">Get Import Template</a></td>
			</tr>
			<tr><td></td></tr>';

		if (isset($_POST['EnterByCat'])){

			$StkCatResult = DB_query("SELECT categorydescription FROM stockcategory WHERE categoryid='" . $_POST['StkCat'] . "'");
			$StkCatRow = DB_fetch_row($StkCatResult);

			echo '<tr>
					<th colspan="4">' . _('Entering Counts For Stock Category') . ': ' . $StkCatRow[0] . '</th>
				</tr>
				<tr>
					<th>' . _('Stock Code') . '</th>
					<th>' . _('Description') . '</th>
					<th>' . _('Quantity') . '</th>
					<th>' . _('Reference') . '</th>
				</tr>';
			$StkItemsResult = DB_query("SELECT stockcheckfreeze.stockid,
												description
										FROM stockcheckfreeze INNER JOIN stockmaster
										ON stockcheckfreeze.stockid=stockmaster.stockid
										WHERE categoryid='" . $_POST['StkCat'] . "' AND loccode = '" . $_POST['Location'] . "'
										ORDER BY stockcheckfreeze.stockid");

			$RowCount=1;
			while ($StkRow = DB_fetch_array($StkItemsResult)) {
				echo '<tr>
						<td><input type="hidden" name="StockID_' . $RowCount . '" value="' . $StkRow['stockid'] . '" />' . $StkRow['stockid'] . '</td>
						<td>' . $StkRow['description'] . '</td>
						<td><input type="text" name="Qty_' . $RowCount . '" maxlength="10" size="10" /></td>
						<td><input type="text" name="Ref_' . $RowCount . '" maxlength="20" size="20" /></td>
					</tr>';
				$RowCount++;
			}

		} else {
			echo '<tr>
					<th>' . _('Bar Code') . '</th>
					<th>' . _('Stock Code') . '</th>
					<th>' . _('Quantity') . '</th>
					<th>' . _('Reference') . '</th>
				</tr>';

			for ($RowCount=1;$RowCount<=10;$RowCount++){

				echo '<tr>
						<td><input type="text" name="BarCode_' . $RowCount . '" maxlength="20" size="20" /></td>
						<td><input type="text" name="StockID_' . $RowCount . '" maxlength="20" size="20" /></td>
						<td><input type="text" name="Qty_' . $RowCount . '" maxlength="10" size="10" /></td>
						<td><input type="text" name="Ref_' . $RowCount . '" maxlength="20" size="20" /></td>
					</tr>';

			}
		}

		echo '</table>
				<br />
				<div class="centre">
					<input type="hidden" name="RowCount" value="' .$RowCount . '" />
					<input type="submit" name="EnterCounts" value="' . _('Enter Above Counts') . '" />
				</div>';
	} // there is a stock check to enter counts for
//END OF action=ENTER
} elseif ($_GET['Action']=='View'){

	if (isset($_POST['DEL']) AND is_array($_POST['DEL']) ){
		foreach ($_POST['DEL'] as $id=>$val){
			if ($val == 'on'){
				$sql = "DELETE FROM stockcounts WHERE id='".$id."'";
				$ErrMsg = _('Failed to delete StockCount ID #').' '.$i;
				$EnterResult = DB_query($sql,$ErrMsg);
				prnMsg( _('Deleted Id #') . ' ' . $id, 'success');
			}
		}
	}

	//START OF action=VIEW
	$SQL = "select stockcounts.*,
					canupd from stockcounts
					INNER JOIN locationusers ON locationusers.loccode=stockcounts.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1";
	$result = DB_query($SQL);
	echo '<input type="hidden" name="Action" value="View" />';
	echo '<table cellpadding="2" class="selection">';
	echo '<tr>
			<th>' . _('Stock Code') . '</th>
			<th>' . _('Location') . '</th>
			<th>' . _('Qty Counted') . '</th>
			<th>' . _('Reference') . '</th>
			<th>' . _('Delete?') . '</th></tr>';
	while ($myrow=DB_fetch_array($result)){
		echo '<tr>
			<td>'.$myrow['stockid'].'</td>
			<td>'.$myrow['loccode'].'</td>
			<td>'.$myrow['qtycounted'].'</td>
			<td>'.$myrow['reference'].'</td>
			<td>';
		if ($myrow['canupd']==1) {
			echo '<input type="checkbox" name="DEL[' . $myrow['id'] . ']" maxlength="20" size="20" />';

		}
		echo '</td></tr>';

	}
	echo '</table><br /><div class="centre"><input type="submit" name="SubmitChanges" value="' . _('Save Changes') . '" /></div>';

//END OF action=VIEW
}

echo '</div>
      </form>';
include('includes/footer.php');
?>
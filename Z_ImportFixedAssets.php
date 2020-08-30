<?php
/* Script to import fixed assets into a specified period*/

include('includes/session.php');
$Title = _('Import Fixed Assets');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
echo '<p class="page_title_text"><img alt="" src="' . $RootPath . '/css/' . $Theme .
		'/images/fixed_assets.png" title="' .
		_('Import Fixed Assets from .csv file') . '" />' . ' ' .
		_('Import Fixed Assets from .csv file') . '</p>';

// If this script is called with a file object, then the file contents are imported
// If this script is called with the gettemplate flag, then a template file is served
// Otherwise, a file upload form is displayed

$FieldNames = array(
	'Description',			//  0 'Title of the fixed asset',
	'LongDescription',		//  1 'Description of the fixed asset',
	'AssetCategoryID',		//  2 'Asset category id',
	'SerialNo',				//  3 'Serial number',
	'BarCode',				//  4 'Bar code',
	'AssetLocationCode',	//  5 'Asset location code',
	'Cost',					//  6 'Cost',
	'AccumDepn',			//  7 'Accumulated depreciation',
	'DepnType',				//  8 'Depreciation type - SL or DV',
	'DepnRate',				//  9 'Depreciation rate',
	'DatePurchased'			// 10 'Date of purchase',
);

if ($_FILES['SelectedAssetFile']['name']) { //start file processing

	//initialize
	$InputError = false;

/*
	if ($_FILES['SelectedAssetFile']['type'] != 'text/csv') {
		prnMsg (_('File has type') . ' ' . $_FILES['SelectedAssetFile']['type'] . ', ' . _('but only "text/csv" is allowed.'),'error');
		include('includes/footer.php');
		exit;
	}
*/
	//get file handle
	$FileHandle = fopen($_FILES['SelectedAssetFile']['tmp_name'], 'r');

	//get the header row
	$HeaderRow = fgetcsv($FileHandle, 10000, ",");

	//check for correct number of fields
	if ( count($HeaderRow) != count($FieldNames) ) {
		prnMsg (_('File contains') . ' '. count($HeaderRow). ' ' . _('columns, expected') . ' '. count($FieldNames). '. ' . _('Study a downloaded template to see the format for the file'),'error');
		fclose($FileHandle);
		include('includes/footer.php');
		exit;
	}

	//test header row field name and sequence
	$i = 0;
	foreach ($HeaderRow as $FieldName) {
		if ( mb_strtoupper($FieldName) != mb_strtoupper($FieldNames[$i]) ) {
			prnMsg (_('The selected file contains fields in the incorrect order ('. mb_strtoupper($FieldName). ' != '. mb_strtoupper($FieldNames[$i]). '. ' ._('Download a template and ensure that fields are in the same sequence as the template.')),'error');
			fclose($FileHandle);
			include('includes/footer.php');
			exit;
		}
		$i++;
	}

	//start database transaction
	DB_Txn_Begin();

	//loop through file rows
	$Row = 1;
	while ( ($myrow = fgetcsv($FileHandle, 10000, ',')) !== FALSE ) {

		//check for correct number of fields
		$FieldCount = count($myrow);
		if ($FieldCount != count($FieldNames)){
			prnMsg (count($FieldNames) . ' ' . _('fields are required, but') . ' '. $FieldCount . ' ' . _('fields were received'),'error');
			fclose($FileHandle);
			include('includes/footer.php');
			exit;
		}

		// cleanup the data (csv files often import with empty strings and such)
		for ($i=0; $i<count($myrow);$i++) {
			$myrow[$i] = trim($myrow[$i]);
			switch ($i) {
				case 0:
					$Description = $myrow[$i];
					break;
				case 1:
					$LongDescription = $myrow[$i];
					break;
				case 2:
					$AssetCategoryID = $myrow[$i];
					break;
				case 3:
					$SerialNo = $myrow[$i];
					break;
				case 4:
					$BarCode = $myrow[$i];
					break;
				case 5:
					$AssetLocationCode = $myrow[$i];
					break;
				case 6:
					$Cost = $myrow[$i];
					break;
				case 7:
					$AccumDepn = $myrow[$i];
					break;
				case 8:
					$DepnType = mb_strtoupper($myrow[$i]);
					break;
				case 9:
					$DepnRate= $myrow[$i];
					break;
				case 10:
					$DatePurchased= $myrow[$i];
					break;
			} //end switch
		} //end loop around fields from import

		if (mb_strlen($Description)==0 OR mb_strlen($Description)>50){
			prnMsg('The description of the asset is expected to be more than 3 characters long and less than 50 characters long','error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid Description:') . ' ' . $Description;
			$InputError=true;
		}
		if (!is_numeric($DepnRate)){
			prnMsg(_('The depreciation rate is expected to be numeric'),'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid Depreciation Rate:') . ' ' . $DepnRate;
			$InputError=true;
		}elseif ($DepnRate<0 OR $DepnRate>100){
			prnMsg(_('The depreciation rate is expected to be a number between 0 and 100'),'error');
			echo '<br />' .  _('Row:') . $Row . ' - ' ._('Invalid Depreciation Rate:') . ' ' . $DepnRate;
			$InputError=true;
		}
		if (!is_numeric($AccumDepn)){
			prnMsg(_('The accumulated depreciation is expected to be numeric'),'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid Accumulated Depreciation:') . ' ' . $AccumDepn;
			$InputError=true;
		} elseif ($AccumDepn<0){
			 prnMsg(_('The accumulated depreciation is expected to be either zero or a positive number'),'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid Accumulated Depreciation:') . ' ' . $AccumDepn;
			$InputError=true;
		}
		if (!is_numeric($Cost)){
			prnMsg(_('The cost is expected to be numeric'),'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid Cost:') . ' ' . $Cost;
			$InputError=true;
		} elseif ($Cost<=0){
			 prnMsg(_('The cost is expected to be a positive number'),'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid Cost:') . ' ' . $AccumDepn;
			$InputError=true;
		}
		if ($DepnType !='SL' AND $DepnType!='DV'){
			prnMsg(_('The depreciation type must be either "SL" - Straight Line or "DV" - Diminishing Value'),'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid depreciation type:') . ' ' . $DepnType;
			$InputError = true;
		}
		$result = DB_query("SELECT categoryid FROM fixedassetcategories WHERE categoryid='" . $AssetCategoryID . "'");
		if (DB_num_rows($result)==0){
			$InputError = true;
			prnMsg(_('The asset category code entered must be exist in the assetcategories table'),'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid asset category:') . ' ' . $AssetCategoryID;
		}
		$result = DB_query("SELECT locationid FROM fixedassetlocations WHERE locationid='" . $AssetLocationCode . "'");
		if (DB_num_rows($result)==0){
			$InputError = true;
			prnMsg(_('The asset location code entered must be exist in the asset locations table'),'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid asset location code:') . ' ' . $AssetLocationCode;
		}
		if (!Is_Date($DatePurchased)){
			$InputError = true;
			prnMsg(_('The date purchased must be entered in the format:') . ' ' . $_SESSION['DefaultDateFormat'],'error');
			echo '<br />' . _('Row:') . $Row . ' - ' . _('Invalid date format:') . ' ' . $DatePurchased;
		}
		if ($DepnType=='DV'){
			$DepnType=1;
		} else {
			$DepnType=0;
		}

		if ($InputError == false){ //no errors

			$TransNo = GetNextTransNo(49);
			$PeriodNo = GetPeriod(ConvertSQLDate($_POST['DateToEnter']));

			//attempt to insert the stock item
			$sql = "INSERT INTO fixedassets (description,
											longdescription,
											assetcategoryid,
											serialno,
											barcode,
											assetlocation,
											cost,
											accumdepn,
											depntype,
											depnrate,
											datepurchased)
							VALUES ('" . $Description . "',
									'" . $LongDescription . "',
									'" . $AssetCategoryID . "',
									'" . $SerialNo . "',
									'" . $BarCode . "',
									'" . $AssetLocationCode . "',
									'" . $Cost . "',
									'" . $AccumDepn . "',
									'" . $DepnType . "',
									'" . $DepnRate . "',
									'" . FormatDateForSQL($DatePurchased) . "')";

			$ErrMsg =  _('The asset could not be added because');
			$DbgMsg = _('The SQL that was used to add the asset and failed was');
			$result = DB_query($sql, $ErrMsg, $DbgMsg);

			if (DB_error_no() ==0) { //the insert of the new code worked so bang in the fixedassettrans records too


				$AssetID = DB_Last_Insert_ID('fixedassets','assetid');
				$sql = "INSERT INTO fixedassettrans ( assetid,
												transtype,
												transno,
												transdate,
												periodno,
												inputdate,
												fixedassettranstype,
												amount)
									VALUES ( '" . $AssetID . "',
											'49',
											'" . $TransNo . "',
											'" . $_POST['DateToEnter'] . "',
											'" . $PeriodNo . "',
											'" . Date('Y-m-d') . "',
											'cost',
											'" . $Cost . "')";

				$ErrMsg =  _('The transaction for the cost of the asset could not be added because');
				$DbgMsg = _('The SQL that was used to add the fixedasset trans record that failed was');
				$InsResult = DB_query($sql,$ErrMsg,$DbgMsg);

				$sql = "INSERT INTO fixedassettrans ( assetid,
													transtype,
													transno,
													transdate,
													periodno,
													inputdate,
													fixedassettranstype,
													amount)
									VALUES ( '" . $AssetID . "',
											'49',
											'" . $TransNo . "',
											'" . $_POST['DateToEnter'] . "',
											'" . $PeriodNo . "',
											'" . Date('Y-m-d') . "',
											'depn',
											'" . $AccumDepn . "')";

				$ErrMsg =  _('The transaction for the cost of the asset could not be added because');
				$DbgMsg = _('The SQL that was used to add the fixedasset trans record that failed was');
				$InsResult = DB_query($sql,$ErrMsg,$DbgMsg);

				if (DB_error_no() ==0) {
					prnMsg( _('Inserted the new asset:') . ' ' . $Description,'info');
				}
			}
		} // there were errors checking the row so no inserts
		$Row++;
	}

	if ($InputError == 1) { //exited loop with errors so rollback
		prnMsg(_('Failed on row '. $Row. '. Batch import has been rolled back.'),'error');
		DB_Txn_Rollback();
	} else { //all good so commit data transaction
		DB_Txn_Commit();
		prnMsg( _('Batch Import of') .' ' . $_FILES['SelectedAssetFile']['name']  . ' '. _('has been completed. All assets in the file have been committed to the database.'),'success');
	}

	fclose($FileHandle);

} elseif ( isset($_POST['gettemplate']) OR isset($_GET['gettemplate']) ) { //download an import template

	echo '<br /><br /><br />"'. implode('","',$FieldNames). '"<br /><br /><br />';

} else { //show file upload form

	echo '
		<br />
		<a href="Z_ImportFixedAssets.php?gettemplate=1">' . _('Get Import Template') . '</a>
		<br />
		<br />
	';
	echo '<form enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
    echo '<div class="centre">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />';
	echo '<table class="selection">
					<tr><td>' . _('Select Date to Upload B/Fwd Assets To:') . '</td>
							<td><select name="DateToEnter">';
	$PeriodsResult = DB_query("SELECT lastdate_in_period FROM periods ORDER BY periodno");
	while ($PeriodRow = DB_fetch_row($PeriodsResult)){
		echo '<option value="' . $PeriodRow[0] . '">' . ConvertSQLDate($PeriodRow[0]) . '</option>';
	}
	echo '</select></td></tr>';
	echo '<tr><td>' . _('Fixed Assets Upload file:') . '</td><td><input name="SelectedAssetFile" type="file" /></td></tr></table>
			<input type="submit" value="' . _('Send File') . '" />
        </div>
		</form>';

}

include('includes/footer.php');
?>

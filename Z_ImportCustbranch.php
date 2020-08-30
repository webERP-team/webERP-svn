<?php

include('includes/session.php');
$Title = _('Import Debtors And branches');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

if(!isset($_POST['UpdateIfExists'])) {
	$_POST['UpdateIfExists']=0;
}else{
	$_POST['UpdateIfExists']=1;
}

// If this script is called with a file object, then the file contents are imported
// If this script is called with the gettemplate flag, then a template file is served
// Otherwise, a file upload form is displayed
$FieldHeadings = array(
	'branchcode',//0
	'debtorno',//1
	'brname',//2
	'braddress1',//3
	'braddress2',//4
	'braddress3',//5
	'braddress4',//6
	'braddress5',//7
	'braddress6',//8
	'lat',//9
	'lng',//10
	'estdeliverydays',//11
	'area',//12
	'salesman',//13
	'fwddate',//14
	'phoneno',//15
	'faxno',//16
	'contactname',//17
	'email',//18
	'defaultlocation',//19
	'taxgroupid',//20
	'defaultshipvia',//21
	'deliverblind',//22
	'disabletrans',//23
	'brpostaddr1',//24
	'brpostaddr2',//25
	'brpostaddr3',//26
	'brpostaddr4',//27
	'brpostaddr5',//28
	'specialinstructions',//29
	'custbranchcode',//30
);

if (isset($_FILES['userfile']) and $_FILES['userfile']['name']) { //start file processing

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
	$headRow = fgetcsv($FileHandle, 10000, ",");
	//check for correct number of fields
	if ( count($headRow) != count($FieldHeadings)) {
		prnMsg (_('File contains '. count($headRow). ' columns, expected '. count($FieldHeadings). '. Try downloading a new template.'),'error');
		fclose($FileHandle);
		include('includes/footer.php');
		exit;
	}
	$Salesmen=array();
	$sql = "SELECT salesmancode
				     FROM salesman";
	$result=DB_query($sql);
	while ($myrow = DB_fetch_array($result)) {
		$Salesmen[]=$myrow['salesmancode'];
	}
	$Areas=array();
	$sql = "SELECT areacode
				     FROM areas";
	$result=DB_query($sql);
	while ($myrow = DB_fetch_array($result)) {
		$Areas[]=$myrow['areacode'];
	}
	$Locations=array();
	$sql = "SELECT loccode
				     FROM locations";
	$result=DB_query($sql);
	while ($myrow = DB_fetch_array($result)) {
		$Locations[]=$myrow['loccode'];
	}
	$Shippers=array();
	$sql = "SELECT shipper_id
				     FROM shippers";
	$result=DB_query($sql);
	while ($myrow = DB_fetch_array($result)) {
		$Shippers[]=$myrow['shipper_id'];
	}
	$Taxgroups=array();
	$sql = "SELECT taxgroupid
				     FROM taxgroups";
	$result=DB_query($sql);
	while ($myrow = DB_fetch_array($result)) {
		$Taxgroups[]=$myrow['taxgroupid'];
	}

	//test header row field name and sequence
	$head = 0;
	foreach ($headRow as $headField) {
		if ( mb_strtoupper($headField) != mb_strtoupper($FieldHeadings[$head])) {
			prnMsg (_('File contains incorrect headers ('. mb_strtoupper($headField). ' != '. mb_strtoupper($header[$head]). '. Try downloading a new template.'),'error');
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
	$UpdatedNum=0;
	$InsertNum=0;
	$ExistDebtorNos=array();
	$NotExistDebtorNos=array();
	$ExistedBranches = array();
	while ( ($filerow = fgetcsv($FileHandle, 10000, ",")) !== FALSE ) {

		//check for correct number of fields
		$fieldCount = count($filerow);
		if ($fieldCount != $FieldTarget) {
			prnMsg (_($FieldTarget. ' fields required, '. $fieldCount. ' fields received'),'error');
			fclose($FileHandle);
			include('includes/footer.php');
			exit;
		}

		// cleanup the data (csv files often import with empty strings and such)
		foreach ($filerow as &$value) {
			$value = trim($value);
		}
		$_POST['BranchCode']=$filerow[0];
		$_POST['DebtorNo']=$filerow[1];
		$_POST['BrName']=$filerow[2];
		$_POST['BrAddress1']=$filerow[3];
		$_POST['BrAddress2']=$filerow[4];
		$_POST['BrAddress3']=$filerow[5];
		$_POST['BrAddress4']=$filerow[6];
		$_POST['BrAddress5']=$filerow[7];
		$_POST['BrAddress6']=$filerow[8];
		$Latitude=$filerow[9];
		$Longitude=$filerow[10];
		$_POST['SpecialInstructions']=$filerow[29];
		$_POST['EstDeliveryDays']=$filerow[11];
		$_POST['FwdDate']=$filerow[14];
		$_POST['Salesman']=$filerow[13];
		$_POST['PhoneNo']=$filerow[15];
		$_POST['FaxNo']=$filerow[16];
		$_POST['ContactName']=$filerow[17];
		$_POST['Area']=$filerow[12];
		$_POST['Email']=$filerow[18];
		$_POST['TaxGroup']=$filerow[20];
		$_POST['DefaultLocation']=$filerow[19];
		$_POST['BrPostAddr1']=$filerow[24];
		$_POST['BrPostAddr2']=$filerow[25];
		$_POST['BrPostAddr3']=$filerow[26];
		$_POST['BrPostAddr4']=$filerow[27];
		$_POST['BrPostAddr5']=$filerow[28];
		$_POST['DisableTrans']=$filerow[23];
		$_POST['DefaultShipVia']=$filerow[21];
		$_POST['CustBranchCode']=$filerow[30];
		$_POST['DeliverBlind']=$filerow[22];

		$i=0;

		if (ContainsIllegalCharacters($_POST['BranchCode']) OR mb_strstr($_POST['BranchCode'],' ') OR mb_strstr($_POST['BranchCode'],'-')) {
			$InputError = 1;
			prnMsg(_('The Branch code cannot contain any of the following characters')." -  &amp; \' &lt; &gt;",'error');
			$Errors[$i] = 'BranchCode';
			$i++;
		}
		if (ContainsIllegalCharacters($_POST['DebtorNo'])) {
			$InputError = 1;
			prnMsg(_('The Debtor No cannot contain any of the following characters')." - &amp; \' &lt; &gt;",'error');
			$Errors[$i] = 'Debtor No';
			$i++;
		}
		if (mb_strlen($_POST['BranchCode'])==0 OR mb_strlen($_POST['BranchCode'])>10) {
			$InputError = 1;
			prnMsg(_('The Branch code must be at least one character long and cannot be more than 10 characters'),'error');
			$Errors[$i] = 'BranchCode';
			$i++;
		}
		for ($c=1;$c<7;$c++) { //Branch address validataion
			$Lenth = 40;
			if($c == 4) {
				$Lenth = 50;
			}
			if($c == 5) {
				$Lenth = 20;
			}
			if (isset($_POST['BrAddress'.$c]) AND mb_strlen($_POST['BrAddress'.$c])>$Lenth) {
				$InputError = 1;
				prnMsg(_('The Branch address1 must be no more than') . ' ' . $Lenth . ' '. _('characters'),'error');
				$Errors[$i] = 'BrAddress'.$c;
				$i++;
		} 		}
		if($Latitude !== null AND !is_numeric($Latitude)) {
			$InputError = 1;
			prnMsg(_('The latitude is expected to be a numeric'),'error');
			$Errors[$i] = 'Latitude';
			$i++;
		}
		if($Longitude !== null AND !is_numeric($Longitude)) {
			$InputError = 1;
			prnMsg(_('The longitude is expected to be a numeric'),'error');
		       	$Errors[$i] = 'Longitued';
			$i++;
		}
		if (!is_numeric($_POST['FwdDate'])) {
			$InputError = 1;
			prnMsg(_('The date after which invoices are charged to the following month is expected to be a number and a recognised number has not been entered'),'error');
			$Errors[$i] = 'FwdDate';
			$i++;
		}
		if ($_POST['FwdDate'] >30) {
			$InputError = 1;
			prnMsg(_('The date (in the month) after which invoices are charged to the following month should be a number less than 31'),'error');
			$Errors[$i] = 'FwdDate';
			$i++;
		}
		if (!is_numeric(filter_number_format($_POST['EstDeliveryDays']))) {
			$InputError = 1;
			prnMsg(_('The estimated delivery days is expected to be a number and a recognised number has not been entered'),'error');
			$Errors[$i] = 'EstDeliveryDays';
			$i++;
		}
		if (filter_number_format($_POST['EstDeliveryDays']) >60) {
			$InputError = 1;
			prnMsg(_('The estimated delivery days should be a number of days less than 60') . '. ' . _('A package can be delivered by seafreight anywhere in the world normally in less than 60 days'),'error');
			$Errors[$i] = 'EstDeliveryDays';
			$i++;
		}
		if(empty($_POST['Salesman']) OR !in_array($_POST['Salesman'],$Salesmen,true)) {
			$InputError = 1;
			prnMsg(_('The salesman not empty and must exist.'),'error');
			$Errors[$i] = 'Salesman';
			$i++;
		}
		if($_POST['PhoneNo'] !== null AND preg_match('/[^\d+()\s-]/',$_POST['PhoneNo'])) {
			$InputError = 1;
			prnMsg(_('The phone no should not contains characters other than digital,parenthese,space,minus and plus sign'),'error');
			$Errors[$i] = 'Phone No';
			$i++;
		}
		if($_POST['FaxNo'] !== null AND preg_match('/[^\d+()\s-]/',$_POST['FaxNo'])) {
			$InputError = 1;
			prnMsg(_('The fax no should not contains characters other than digital,parenthese,space,minus and plus sign'),'error');
			$Errors[$i] = 'FaxNo';
			$i++;
		}
		if($_POST['ContactName'] !== null AND mb_strlen($_POST['ContactName']) > 30) {
			$InputError = 1;
			prnMsg(_('The contact name must not be over 30 characters'),'error');
			$Errors[$i] = 'ContactName';
			$i++;
		}
		if($_POST['Email'] !== null AND !filter_var($_POST['Email'],FILTER_VALIDATE_EMAIL)) {
			$InputError = 1;
			prnMsg(_('The email address is not valid'),'error');
			$Errors[$i] = 'Email';
			$i++;
		}

		if(ContainsIllegalCharacters($_POST['BrName']) OR mb_strlen($_POST['BrName']) >40) {
			$InputError = 1;
			prnMsg(_('The Branch code cannot contain any of the following characters')." -  &amp; \' &lt; &gt;" .' ' . _('Or length is over 40'),'error');
			$Errors[$i] = 'BrName';
			$i++;
		}
		if(empty($_POST['Area']) OR !in_array($_POST['Area'],$Areas,true)) {
			$InputError = 1;
			prnMsg(_('The sales area not empty and must exist.'),'error');
			$Errors[$i] = 'Area';
			$i++;
		}
		if(empty($_POST['DefaultLocation']) OR !in_array($_POST['DefaultLocation'],$Locations,true)) {
			$InputError = 1;
			prnMsg(_('The default location not empty and must exist.'),'error');
			$Errors[$i] = 'DefaultLocation';
			$i++;
		}
		if(empty($_POST['DefaultShipVia']) OR !in_array($_POST['DefaultShipVia'],$Shippers,true)) {
			$InputError = 1;
			prnMsg(_('The default shipper not empty and must exist.'),'error');
			$Errors[$i] = 'DefaultShipVia';
			$i++;
		}
		if(empty($_POST['TaxGroup']) OR !in_array($_POST['TaxGroup'],$Taxgroups,true)) {
			$InputError = 1;
			prnMsg(_('The taxgroup not empty and must exist.'),'error');
			$Errors[$i] = 'TaxGroup';
			$i++;
		}
		if(!isset($_POST['DeliverBlind']) OR ($_POST['DeliverBlind'] !=1 AND $_POST['DeliverBlind'] != 2)) {
			$InputError = 1;
			prnMsg(_('The Deliver Blind must be set as 2 or 1'),'error');
			$Errors[$i] = 'DeliverBlind';
			$i++;
		}
		if(!isset($_POST['DisableTrans']) OR ($_POST['DisableTrans'] != 0 AND $_POST['DisableTrans'] != 1)) {
			$InputError = 1;
			prnMsg(_('The Disable Trans status should be 0 or 1'),'error');
			$Errors[$i] = 'DisableTrans';
			$i++;
		}
		for($c=1;$c<6;$c++) {
			$Lenth = 40;
			if($c == 4) {
				$Lenth = 50;
			}
			if($c == 5) {
				$Lenth = 20;
			}
			if (isset($_POST['BrPostAddr'.$c]) AND mb_strlen($_POST['BrPostAddr'.$c])>$Lenth) {
				$InputError = 1;
				prnMsg(_('The Branch Post Address') . ' ' . $c . ' ' . _('must be no more than') . ' ' . $Lenth . ' '. _('characters'),'error');
				$Errors[$i] = 'BrPostAddr'.$c;
				$i++;
			}

		}
		if(isset($_POST['CustBranchCode']) AND mb_strlen($_POST['CustBranchCode']) > 30) {
			$InputError = 1;
			prnMsg(_('The Cust branch code for EDI must be less than 30 characters'),'error');
			$Errors[$i] = 'CustBranchCode';
			$i++;
		}

		if ($InputError !=1) {
			if (DB_error_no() ==0) {

				if(in_array($_POST['DebtorNo'],$NotExistDebtorNos,true)) {
					continue;
				}else{
					$sql = "SELECT 1
						 FROM debtorsmaster
						 WHERE debtorno='".$_POST['DebtorNo']."' LIMIT 1";
					$result=DB_query($sql);
					$DebtorExists=(DB_num_rows($result)>0);
					if ($DebtorExists) {
						$ExistDebtorNos[]=$_POST['DebtorNo'];
					}else{
						$NotExistDebtorNos[]=$_POST['DebtorNo'];
						prnMsg(_('The Debtor No') . $_POST['DebtorNo'] . ' ' . _('has not existed, and its branches data cannot be imported'),'error');
						include('includes/footer.php');
						exit;
					}
				}
				$sql = "SELECT 1
				     FROM custbranch
           			 WHERE debtorno='".$_POST['DebtorNo']."' AND
				           branchcode='".$_POST['BranchCode']."' LIMIT 1";
				$result=DB_query($sql);
				$BranchExists=(DB_num_rows($result)>0);
				if ($BranchExists AND $_POST['UpdateIfExists']!=1) {
					$ExistedBranches[] = array('debtor'=>$_POST['DebtorNo'],
								'branch'=>$_POST['BranchCode']);
					$UpdatedNum++;
				}else{

					if (!isset($_POST['EstDeliveryDays'])) {
						$_POST['EstDeliveryDays']=1;
					}
					if (!isset($Latitude)) {
						$Latitude=0.0;
						$Longitude=0.0;
					}
					if ($BranchExists) {
						$UpdatedNum++;
						$sql = "UPDATE custbranch SET brname = '" . $_POST['BrName'] . "',
									braddress1 = '" . $_POST['BrAddress1'] . "',
									braddress2 = '" . $_POST['BrAddress2'] . "',
									braddress3 = '" . $_POST['BrAddress3'] . "',
									braddress4 = '" . $_POST['BrAddress4'] . "',
									braddress5 = '" . $_POST['BrAddress5'] . "',
									braddress6 = '" . $_POST['BrAddress6'] . "',
									lat = '" . $Latitude . "',
									lng = '" . $Longitude . "',
									specialinstructions = '" . $_POST['SpecialInstructions'] . "',
									phoneno='" . $_POST['PhoneNo'] . "',
									faxno='" . $_POST['FaxNo'] . "',
									fwddate= '" . $_POST['FwdDate'] . "',
									contactname='" . $_POST['ContactName'] . "',
									salesman= '" . $_POST['Salesman'] . "',
									area='" . $_POST['Area'] . "',
									estdeliverydays ='" . filter_number_format($_POST['EstDeliveryDays']) . "',
									email='" . $_POST['Email'] . "',
									taxgroupid='" . $_POST['TaxGroup'] . "',
									defaultlocation='" . $_POST['DefaultLocation'] . "',
									brpostaddr1 = '" . $_POST['BrPostAddr1'] . "',
									brpostaddr2 = '" . $_POST['BrPostAddr2'] . "',
									brpostaddr3 = '" . $_POST['BrPostAddr3'] . "',
									brpostaddr4 = '" . $_POST['BrPostAddr4'] . "',
									brpostaddr5 = '" . $_POST['BrPostAddr5'] . "',
									disabletrans='" . $_POST['DisableTrans'] . "',
									defaultshipvia='" . $_POST['DefaultShipVia'] . "',
									custbranchcode='" . $_POST['CustBranchCode'] ."',
									deliverblind='" . $_POST['DeliverBlind'] . "'
								WHERE branchcode = '".$_POST['BranchCode']."' AND debtorno='".$_POST['DebtorNo']."'";

					} else {
						$InsertNum++;
						$sql = "INSERT INTO custbranch (branchcode,
										debtorno,
										brname,
										braddress1,
										braddress2,
										braddress3,
										braddress4,
										braddress5,
										braddress6,
										lat,
										lng,
										specialinstructions,
										estdeliverydays,
										fwddate,
										salesman,
										phoneno,
										faxno,
										contactname,
										area,
										email,
										taxgroupid,
										defaultlocation,
										brpostaddr1,
										brpostaddr2,
										brpostaddr3,
										brpostaddr4,
										brpostaddr5,
										disabletrans,
										defaultshipvia,
										custbranchcode,
										deliverblind)
								VALUES ('" . $_POST['BranchCode'] . "',
									'" . $_POST['DebtorNo'] . "',
									'" . $_POST['BrName'] . "',
									'" . $_POST['BrAddress1'] . "',
									'" . $_POST['BrAddress2'] . "',
									'" . $_POST['BrAddress3'] . "',
									'" . $_POST['BrAddress4'] . "',
									'" . $_POST['BrAddress5'] . "',
									'" . $_POST['BrAddress6'] . "',
									'" . $Latitude . "',
									'" . $Longitude . "',
									'" . $_POST['SpecialInstructions'] . "',
									'" . filter_number_format($_POST['EstDeliveryDays']) . "',
									'" . $_POST['FwdDate'] . "',
									'" . $_POST['Salesman'] . "',
									'" . $_POST['PhoneNo'] . "',
									'" . $_POST['FaxNo'] . "',
									'" . $_POST['ContactName'] . "',
									'" . $_POST['Area'] . "',
									'" . $_POST['Email'] . "',
									'" . $_POST['TaxGroup'] . "',
									'" . $_POST['DefaultLocation'] . "',
									'" . $_POST['BrPostAddr1'] . "',
									'" . $_POST['BrPostAddr2'] . "',
									'" . $_POST['BrPostAddr3'] . "',
									'" . $_POST['BrPostAddr4'] . "',
									'" . $_POST['BrPostAddr5'] . "',
									'" . $_POST['DisableTrans'] . "',
									'" . $_POST['DefaultShipVia'] . "',
									'" . $_POST['CustBranchCode'] ."',
									'" . $_POST['DeliverBlind'] . "')";
					}

					//run the SQL from either of the above possibilites
					$ErrMsg = _('The branch record could not be inserted or updated because');
					$result = DB_query($sql, $ErrMsg);

					if (DB_error_no() ==0) {
						prnMsg( _('New branch of debtor') .' ' .$_POST['DebtorNo'] . ' ' ._('with branch code') .' ' . $_POST['BranchCode'] . ' ' . $_POST['BrName']  . ' '. _('has been passed validation'),'info');
					} else { //location insert failed so set some useful error info
						$InputError = 1;
						prnMsg(_($result),'error');
					}
				}
			} else { //item insert failed so set some useful error info
				$InputError = 1;
				prnMsg(_($result),'error');
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
		if($_POST['UpdateIfExists']==1) {
			prnMsg( _('Updated brances total:') .' ' . $UpdatedNum .' '._('Insert branches total:'). $InsertNum,'success' );
		}else{
			prnMsg( _('Exist branches total:') .' ' . $UpdatedNum .' '._('Inserted branches total:'). $InsertNum,'info');
			if($UpdatedNum){
				echo '	<p>' . _('Branches not updated').'</p>
					<table class="selection">
					<tr><th>'._('Debtor No').'</th><th>' . _('Branch Code').'</th></tr>';
				foreach($ExistedBranches as $key=>$value){
					echo '<tr><td>'.$value['debtor'].'</td><td>'.$value['branch'].'</td></tr>';
				}
				echo '</table>';
			}
		}
	}

	fclose($FileHandle);

} elseif ( isset($_POST['gettemplate']) OR isset($_GET['gettemplate'])) { //download an import template

	echo '<br /><br /><br />"'. implode('","',$FieldHeadings). '"<br /><br /><br />';

} else { //show file upload form

	prnMsg(_('Please ensure that your csv file is encoded in UTF-8, otherwise the input data will not store correctly in database'),'warn');

	echo '
		<br />
		<a href="Z_ImportCustbranch.php?gettemplate=1">Get Import Template</a>
		<br />
		<br />';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" enctype="multipart/form-data">';
    echo '<div class="centre">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />' .
			_('Upload file') . ': <input name="userfile" type="file" />
			<input type="submit" value="' . _('Send File') . '" />';
	echo '<br/>',_('Update if Customer Branch exists'),':<input type="checkbox" name="UpdateIfExists">';
	echo'</div>
		</form>';

}


include('includes/footer.php');
?>

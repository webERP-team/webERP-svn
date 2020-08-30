<?php
/* Import debtors by csv file */

include('includes/session.php');
$Title = _('Import Debtors And branches');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

if(isset($_POST['FormID'])) {
	if(!isset($_POST['AutoDebtorNo'])) {
		$_POST['AutoDebtorNo']=0;
	} else {
		$_POST['AutoDebtorNo']=1;
	}
	if($_POST['AutoDebtorNo']==1) {
		$_POST['UpdateIfExists']=0;
	} else {
		if(!isset($_POST['UpdateIfExists'])) {
			$_POST['UpdateIfExists']=0;
		} else {
			$_POST['UpdateIfExists']=1;
		}
	}
} else {
	$_POST['AutoDebtorNo']=$_SESSION['AutoDebtorNo'];
	$_POST['UpdateIfExists']=0;
}

// If this script is called with a file object, then the file contents are imported
// If this script is called with the gettemplate flag, then a template file is served
// Otherwise, a file upload form is displayed
$FieldHeadings = array(
	'debtorno',	//0
	'name',	//1
	'address1',	//2
	'address2',	//3
	'address3',	//4
	'address4',	//5
	'address5',	//6
	'address6',	//7
	'currcode',	//8
	'salestype',	//9
	'clientsince',	//10
	'holdreason',	//11
	'paymentterms',	//12
	'discount',	//13
	'pymtdiscount',	//14
	'lastpaid',	//15
	'lastpaiddate',	//16
	'creditlimit',	//17
	'invaddrbranch',	//18
	'discountcode',	//19
	'Languageid',//20
	'ediinvoices',	//21
	'ediorders',	//22
	'edireference',	//23
	'editransport',	//24
	'ediaddress',	//25
	'ediserveruser',	//26
	'ediserverpwd',	//27
	'taxref',	//28
	'customerpoline',	//29
	'typeid',	//30
	'lat',	//31
	'lng',	//32
	'estdeliverydays',	//33
	'area',	//34
	'salesman',	//35
	'fwddate',	//36
	'phoneno',	//37
	'faxno',	//38
	'contactname',	//39
	'email',	//40
	'defaultlocation',	//41
	'taxgroupid',	//42
	'defaultshipvia',	//43
	'deliverblind',	//44
	'disabletrans',	//45
	'brpostaddr1',	//46
	'brpostaddr2',	//47
	'brpostaddr3',	//48
	'brpostaddr4',	//49
	'brpostaddr5',	//50
	'brpostaddr6',	//51
	'specialinstructions',	//52
	'custbranchcode',	//53
);

if(isset($_FILES['userfile']) and $_FILES['userfile']['name']) { //start file processing

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
	if( count($headRow) != count($FieldHeadings) ) {
		prnMsg (_('File contains '. count($headRow). ' columns, expected '. count($FieldHeadings). '. Try downloading a new template.'),'error');
		fclose($FileHandle);
		include('includes/footer.php');
		exit;
	}

	//test header row field name and sequence
	$head = 0;
	foreach($headRow as $headField) {
		if( mb_strtoupper($headField) != mb_strtoupper($FieldHeadings[$head]) ) {
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
	while( ($filerow = fgetcsv($FileHandle, 10000, ",")) !== FALSE ) {

		//check for correct number of fields
		$fieldCount = count($filerow);
		if($fieldCount != $FieldTarget) {
			prnMsg (_($FieldTarget. ' fields required, '. $fieldCount. ' fields received'),'error');
			fclose($FileHandle);
			include('includes/footer.php');
			exit;
		}

		// cleanup the data (csv files often import with empty strings and such)
		foreach($filerow as &$value) {
			$value = trim($value);
		}

		$_POST['DebtorNo']=$filerow[0];
		$_POST['CustName']=$filerow[1];
		$_POST['Address1']=$filerow[2];
		$_POST['Address2']=$filerow[3];
		$_POST['Address3']=$filerow[4];
		$_POST['Address4']=$filerow[5];
		$_POST['Address5']=$filerow[6];
		$_POST['Address6']=$filerow[7];
		$_POST['CurrCode']=$filerow[8];
		$_POST['SalesType']=$filerow[9];
		$_POST['ClientSince']=$filerow[10];
		$_POST['HoldReason']=$filerow[11];
		$_POST['PaymentTerms']=$filerow[12];
		$_POST['Discount']=$filerow[13];
		$_POST['PymtDiscount']=$filerow[14];
		$_POST['lastpaid']=$filerow[15];
		$_POST['lastpaiddate']=$filerow[16];
		$_POST['CreditLimit']=$filerow[17];
		$_POST['InvAddrBranch']=$filerow[18];
		$_POST['DiscountCode']=$filerow[19];
		$_POST['LanguageID']=$filerow[20];
		$_POST['EDIInvoices']=$filerow[21];
		$_POST['EDIOrders']=$filerow[22];
		$_POST['EDIReference']=$filerow[23];
		$_POST['EDITransport']=$filerow[24];
		$_POST['EDIAddress']=$filerow[25];
		$_POST['EDIServerUser']=$filerow[26];
		$_POST['EDIServerPwd']=$filerow[27];
		$_POST['TaxRef']=$filerow[28];
		$_POST['CustomerPOLine']=$filerow[29];
		$_POST['typeid']=$filerow[30];

		if($_POST['AutoDebtorNo']== 1) {
			$_POST['DebtorNo'] = GetNextTransNo(500);
		} else {
			$_POST['DebtorNo'] = mb_strtoupper($_POST['DebtorNo']);
		}

		//$_POST['DebtorNo']=$_POST['DebtorNo'];
		$_POST['BranchCode']=$_POST['DebtorNo'];
		$_POST['BrName']=$_POST['CustName'];
		$_POST['BrAddress1']=$_POST['Address1'];
		$_POST['BrAddress2']=$_POST['Address2'];
		$_POST['BrAddress3']=$_POST['Address3'];
		$_POST['BrAddress4']=$_POST['Address4'];
		$_POST['BrAddress5']=$_POST['Address5'];
		$_POST['BrAddress6']=$_POST['Address6'];
		$Latitude=$filerow[31];
		$Longitude=$filerow[32];
		$_POST['EstDeliveryDays']=$filerow[33];
		$_POST['Area']=$filerow[34];
		$_POST['Salesman']=$filerow[35];
		$_POST['FwdDate']=$filerow[36];
		$_POST['PhoneNo']=$filerow[37];
		$_POST['FaxNo']=$filerow[38];
		$_POST['ContactName']=$filerow[39];
		$_POST['Email']=$filerow[40];
		$_POST['DefaultLocation']=$filerow[41];
		$_POST['TaxGroup']=$filerow[42];
		$_POST['DefaultShipVia']=$filerow[43];
		$_POST['DeliverBlind']=$filerow[44];
		$_POST['DisableTrans']=$filerow[45];
		$_POST['BrPostAddr1']=$filerow[46];
		$_POST['BrPostAddr2']=$filerow[47];
		$_POST['BrPostAddr3']=$filerow[48];
		$_POST['BrPostAddr4']=$filerow[49];
		$_POST['BrPostAddr5']=$filerow[50];
		$_POST['CustBranchCode']=$filerow[51];
		$_POST['SpecialInstructions']=$filerow[52];

		$i=0;
		if($_POST['AutoDebtorNo']==0 AND mb_strlen($_POST['DebtorNo']) ==0) {
			$InputError = 1;
			prnMsg( _('The debtor code cannot be empty'),'error');
			$Errors[$i] = 'DebtorNo';
			$i++;
		} elseif($_POST['AutoDebtorNo']==0 AND (ContainsIllegalCharacters($_POST['DebtorNo']) OR mb_strpos($_POST['DebtorNo'], ' '))) {
			$InputError = 1;
			prnMsg( _('The customer code cannot contain any of the following characters') . " . - ' &amp; + \" " . _('or a space'),'error');
			$Errors[$i] = 'DebtorNo';
			$i++;
		}
		if(mb_strlen($_POST['CustName']) > 40 OR mb_strlen($_POST['CustName'])==0) {
			$InputError = 1;
			prnMsg( _('The customer name must be entered and be forty characters or less long'),'error');
			$Errors[$i] = 'CustName';
			$i++;
		} elseif(mb_strlen($_POST['Address1']) >40) {
			$InputError = 1;
			prnMsg( _('The Line 1 of the address must be forty characters or less long'),'error');
			$Errors[$i] = 'Address1';
			$i++;
		} elseif(mb_strlen($_POST['Address2']) >40) {
			$InputError = 1;
			prnMsg( _('The Line 2 of the address must be forty characters or less long'),'error');
			$Errors[$i] = 'Address2';
			$i++;
		} elseif(mb_strlen($_POST['Address3']) >40) {
			$InputError = 1;
			prnMsg( _('The Line 3 of the address must be forty characters or less long'),'error');
			$Errors[$i] = 'Address3';
			$i++;
		} elseif(mb_strlen($_POST['Address4']) >50) {
			$InputError = 1;
			prnMsg( _('The Line 4 of the address must be fifty characters or less long'),'error');
			$Errors[$i] = 'Address4';
			$i++;
		} elseif(mb_strlen($_POST['Address5']) >20) {
			$InputError = 1;
			prnMsg( _('The Line 5 of the address must be twenty characters or less long'),'error');
			$Errors[$i] = 'Address5';
			$i++;
		} elseif(!is_numeric(filter_number_format($_POST['CreditLimit']))) {
			$InputError = 1;
			prnMsg( _('The credit limit must be numeric'),'error');
			$Errors[$i] = 'CreditLimit';
			$i++;
		} elseif(!is_numeric(filter_number_format($_POST['PymtDiscount']))) {
			$InputError = 1;
			prnMsg( _('The payment discount must be numeric'),'error');
			$Errors[$i] = 'PymtDiscount';
			$i++;
		} elseif(!Is_Date($_POST['ClientSince'])) {
			$InputError = 1;
			prnMsg( _('The customer since field must be a date in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
			$Errors[$i] = 'ClientSince';
			$i++;
		} elseif(!is_numeric(filter_number_format($_POST['Discount']))) {
			$InputError = 1;
			prnMsg( _('The discount percentage must be numeric'),'error');
			$Errors[$i] = 'Discount';
			$i++;
		} elseif(filter_number_format($_POST['CreditLimit']) <0) {
			$InputError = 1;
			prnMsg( _('The credit limit must be a positive number'),'error');
			$Errors[$i] = 'CreditLimit';
			$i++;
		} elseif((filter_number_format($_POST['PymtDiscount'])> 10) OR (filter_number_format($_POST['PymtDiscount']) <0)) {
			$InputError = 1;
			prnMsg( _('The payment discount is expected to be less than 10% and greater than or equal to 0'),'error');
			$Errors[$i] = 'PymtDiscount';
			$i++;
		} elseif((filter_number_format($_POST['Discount'])> 100) OR (filter_number_format($_POST['Discount']) <0)) {
			$InputError = 1;
			prnMsg( _('The discount is expected to be less than 100% and greater than or equal to 0'),'error');
			$Errors[$i] = 'Discount';
			$i++;
		}

		if(ContainsIllegalCharacters($_POST['EDIReference'])
			OR mb_strstr($_POST['EDIReference'],' ')) {
			$InputError = 1;
			prnMsg(_('The customers EDI reference code cannot contain any of the following characters') .' - \' &amp; + \" ' . _('or a space'),'warn');
		}
		if(mb_strlen($_POST['EDIReference'])<4 AND ($_POST['EDIInvoices']==1 OR $_POST['EDIOrders']==1)) {
			$InputError = 1;
			prnMsg(_('The customers EDI reference code must be set when EDI Invoices or EDI orders are activated'),'warn');
			$Errors[$i] = 'EDIReference';
			$i++;
		}
		if(mb_strlen($_POST['EDIAddress'])<4 AND $_POST['EDIInvoices']==1) {
			$InputError = 1;
			prnMsg(_('The customers EDI email address or FTP server address must be entered if EDI Invoices are to be sent'),'warn');
			$Errors[$i] = 'EDIAddress';
			$i++;
		}


		if($InputError !=1) {
			$sql="SELECT 1 FROM debtorsmaster WHERE debtorno='".$_POST['DebtorNo']."' LIMIT 1";
			$result=DB_query($sql);
			$DebtorExists=(DB_num_rows($result)>0);
			if($DebtorExists AND $_POST['UpdateIfExists']!=1) {
				$UpdatedNum++;
			} else {

				$SQL_ClientSince = FormatDateForSQL($_POST['ClientSince']);

				if($DebtorExists) {//update
					$UpdatedNum++;
					$sql = "SELECT 1
							  FROM debtortrans
							where debtorno = '" . $_POST['DebtorNo'] . "' LIMIT 1";
					$result = DB_query($sql);

					$curr=false;
					if(DB_num_rows($result) == 0) {
						$curr=true;
					} else {
						$CurrSQL = "SELECT currcode
							FROM debtorsmaster
							where debtorno = '" . $_POST['DebtorNo'] . "'";
						$CurrResult = DB_query($CurrSQL);
						$CurrRow = DB_fetch_array($CurrResult);
						$OldCurrency = $CurrRow[0];
						if($OldCurrency != $_POST['CurrCode']) {
							prnMsg( _('The currency code cannot be updated as there are already transactions for this customer'),'info');
						}
					}

					$sql = "UPDATE debtorsmaster SET
							name='" . $_POST['CustName'] . "',
							address1='" . $_POST['Address1'] . "',
							address2='" . $_POST['Address2'] . "',
							address3='" . $_POST['Address3'] ."',
							address4='" . $_POST['Address4'] . "',
							address5='" . $_POST['Address5'] . "',
							address6='" . $_POST['Address6'] . "',";

					if($curr)
						$sql .= "currcode='" . $_POST['CurrCode'] . "',";

					$sql .=	"clientsince='" . $SQL_ClientSince. "',
							holdreason='" . $_POST['HoldReason'] . "',
							paymentterms='" . $_POST['PaymentTerms'] . "',
							discount='" . filter_number_format($_POST['Discount'])/100 . "',
							discountcode='" . $_POST['DiscountCode'] . "',
							pymtdiscount='" . filter_number_format($_POST['PymtDiscount'])/100 . "',
							creditlimit='" . filter_number_format($_POST['CreditLimit']) . "',
							salestype = '" . $_POST['SalesType'] . "',
							invaddrbranch='" . $_POST['AddrInvBranch'] . "',
							taxref='" . $_POST['TaxRef'] . "',
							customerpoline='" . $_POST['CustomerPOLine'] . "',
							typeid='" . $_POST['typeid'] . "',
							language_id='" . $_POST['LanguageID'] . "'
						  WHERE debtorno = '" . $_POST['DebtorNo'] . "'";

					$ErrMsg = _('The customer could not be updated because');
					$result = DB_query($sql,$ErrMsg);

				} else { //insert
					$InsertNum++;
					$sql = "INSERT INTO debtorsmaster (
							debtorno,
							name,
							address1,
							address2,
							address3,
							address4,
							address5,
							address6,
							currcode,
							clientsince,
							holdreason,
							paymentterms,
							discount,
							discountcode,
							pymtdiscount,
							creditlimit,
							salestype,
							invaddrbranch,
							taxref,
							customerpoline,
							typeid,
							language_id)
						VALUES ('" . $_POST['DebtorNo'] ."',
							'" . $_POST['CustName'] ."',
							'" . $_POST['Address1'] ."',
							'" . $_POST['Address2'] ."',
							'" . $_POST['Address3'] . "',
							'" . $_POST['Address4'] . "',
							'" . $_POST['Address5'] . "',
							'" . $_POST['Address6'] . "',
							'" . $_POST['CurrCode'] . "',
							'" . $SQL_ClientSince . "',
							'" . $_POST['HoldReason'] . "',
							'" . $_POST['PaymentTerms'] . "',
							'" . filter_number_format($_POST['Discount'])/100 . "',
							'" . $_POST['DiscountCode'] . "',
							'" . filter_number_format($_POST['PymtDiscount'])/100 . "',
							'" . filter_number_format($_POST['CreditLimit']) . "',
							'" . $_POST['SalesType'] . "',
							'" . $_POST['AddrInvBranch'] . "',
							'" . $_POST['TaxRef'] . "',
							'" . $_POST['CustomerPOLine'] . "',
							'" . $_POST['typeid'] . "',
							'" . $_POST['LanguageID'] . "')";

					$ErrMsg = _('This customer could not be added because');
					$result = DB_query($sql,$ErrMsg);
				}
			}

		} else {

			break;
		}

		$i=0;

		if(ContainsIllegalCharacters($_POST['BranchCode']) OR mb_strstr($_POST['BranchCode'],' ')) {
			$InputError = 1;
			prnMsg(_('The Branch code cannot contain any of the following characters')." -  &amp; \' &lt; &gt;",'error');
			$Errors[$i] = 'BranchCode';
			$i++;
		}
		if(mb_strlen($_POST['BranchCode'])==0) {
			$InputError = 1;
			prnMsg(_('The Branch code must be at least one character long'),'error');
			$Errors[$i] = 'BranchCode';
			$i++;
		}
		if(!is_numeric($_POST['FwdDate'])) {
			$InputError = 1;
			prnMsg(_('The date after which invoices are charged to the following month is expected to be a number and a recognised number has not been entered'),'error');
			$Errors[$i] = 'FwdDate';
			$i++;
		}
		if($_POST['FwdDate'] >30) {
			$InputError = 1;
			prnMsg(_('The date (in the month) after which invoices are charged to the following month should be a number less than 31'),'error');
			$Errors[$i] = 'FwdDate';
			$i++;
		}
		if(!is_numeric(filter_number_format($_POST['EstDeliveryDays']))) {
			$InputError = 1;
			prnMsg(_('The estimated delivery days is expected to be a number and a recognised number has not been entered'),'error');
			$Errors[$i] = 'EstDeliveryDays';
			$i++;
		}
		if(filter_number_format($_POST['EstDeliveryDays']) >60) {
			$InputError = 1;
			prnMsg(_('The estimated delivery days should be a number of days less than 60') . '. ' . _('A package can be delivered by seafreight anywhere in the world normally in less than 60 days'),'error');
			$Errors[$i] = 'EstDeliveryDays';
			$i++;
		}

		if($InputError !=1) {
			if(DB_error_no() ==0) {

				$sql = "SELECT 1
				     FROM custbranch
           			 WHERE debtorno='".$_POST['DebtorNo']."' AND
				           branchcode='".$_POST['BranchCode']."' LIMIT 1";
				$result=DB_query($sql);
				$BranchExists=(DB_num_rows($result)>0);
				if($BranchExists AND $_POST['UpdateIfExists']!=1) {
					//do nothing
				} else {

					if(!isset($_POST['EstDeliveryDays'])) {
						$_POST['EstDeliveryDays']=1;
					}
					if(!isset($Latitude)) {
						$Latitude=0.0;
						$Longitude=0.0;
					}
					if($BranchExists) {
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


					if(DB_error_no() ==0) {
						prnMsg( _('New Item') .' ' . $StockID  . ' '. _('has been added to the transaction'),'info');
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

		if($InputError == 1) { //this row failed so exit loop
			break;
		}

		$row++;
	}

	if($InputError == 1) { //exited loop with errors so rollback
		prnMsg(_('Failed on row '. $row. '. Batch import has been rolled back.'),'error');
		DB_Txn_Rollback();
	} else { //all good so commit data transaction
		DB_Txn_Commit();
		prnMsg( _('Batch Import of') .' ' . $FileName  . ' '. _('has been completed. All transactions committed to the database.'),'success');
		if($_POST['UpdateIfExists']==1) {
			prnMsg( _('Updated:') .' ' . $UpdatedNum .' '._('Insert') . ':' . $InsertNum );
		} else {
			prnMsg( _('Exist:') .' ' . $UpdatedNum .' '. _('Insert') . ':' . $InsertNum );
		}
	}

	fclose($FileHandle);

} elseif( isset($_POST['gettemplate']) || isset($_GET['gettemplate']) ) { //download an import template

	echo '<br /><br /><br />"'. implode('","',$FieldHeadings). '"<br /><br /><br />';

} else { //show file upload form

	prnMsg(_('Please ensure that your csv file is encoded in UTF-8, otherwise the input data will not store correctly in database'),'warn');

	echo '
		<br />
		<a href="Z_ImportDebtors.php?gettemplate=1">Get Import Template</a>
		<br />
		<br />';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" enctype="multipart/form-data">';
    echo '<div class="centre">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />' .
			_('Upload file') . ': <input name="userfile" type="file" />
			<input type="submit" value="' . _('Send File') . '" />';
	echo '<br/>',_('Create Debtor Codes Automatically'),':<input type="checkbox" name="AutoDebtorNo" ';
	if($_POST['AutoDebtorNo']==1)echo 'checked="checked"';
	echo '>';
	echo '<br/>',_('Update if DebtorNo exists'),':<input type="checkbox" name="UpdateIfExists">';
	echo'</div>
		</form>';

}


include('includes/footer.php');
?>

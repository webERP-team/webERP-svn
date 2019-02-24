<?php
// SpecialOrder.php
// Allows for a sales order to be created and an indent order to be created on a supplier for a one off item that may never be purchased again. A dummy part is created based on the description and cost details given.

include('includes/DefineSpecialOrderClass.php');
/* Session started in header.php for password checking and authorisation level check */
include('includes/session.php');

include('includes/SQL_CommonFunctions.inc');

$ViewTopic = '';/* ?????????? */
$BookMark = 'SpecialOrder';
$Title = _('Special Order Entry');
include('includes/header.php');

if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other supplier tender sessions on the same machine  */
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'].'?identifier='.$identifier) . '" method="post">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($_GET['NewSpecial']) and $_GET['NewSpecial']=='yes'){
	unset($_SESSION['SPL'.$identifier]);
}

if (!isset($_SESSION['SupplierID'])){
	echo '<br /><br />';
	prnMsg(_('To set up a special') . ', ' . _('the supplier must first be selected from the Select Supplier page'),'info');
	echo '<br /><a href="' . $RootPath . '/SelectSupplier.php">' . _('Select the supplier now') . '</a>';
	include('includes/footer.php');
	exit;
}

if (!isset($_SESSION['CustomerID']) or $_SESSION['CustomerID']==''){
	echo '<br />
		<br />' . _('To set up a special') . ', ' . _('the customer must first be selected from the Select Customer page') . '
		<br />
		<a href="' . $RootPath . '/SelectCustomer.php">' . _('Select the customer now') . '</a>';
	include('includes/footer.php');
	exit;
}

if (isset($_POST['Cancel'])){
	unset($_SESSION['SPL'.$identifier]);
}


if (!isset($_SESSION['SPL'.$identifier])){
	/* It must be a new special order being created $_SESSION['SPL'.$identifier] would be set up from the order modification code above if a modification to an existing order.  */

	$_SESSION['SPL'.$identifier] = new SpecialOrder;

}


/*if not already done populate the SPL object with supplier data */
if (!isset($_SESSION['SPL'.$identifier]->SupplierID)){
	$sql = "SELECT suppliers.suppname,
					suppliers.currcode,
					currencies.rate,
					currencies.decimalplaces
				FROM suppliers INNER JOIN currencies
					ON suppliers.currcode=currencies.currabrev
				WHERE supplierid='" . $_SESSION['SupplierID'] . "'";
	$ErrMsg = _('The supplier record of the supplier selected') . ": " . $_SESSION['SupplierID']  . ' ' . _('cannot be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the supplier details and failed was');
	$result =DB_query($sql,$ErrMsg,$DbgMsg);

	$myrow = DB_fetch_array($result);
	$_SESSION['SPL'.$identifier]->SupplierID = $_SESSION['SupplierID'];
	$_SESSION['SPL'.$identifier]->SupplierName = $myrow['suppname'];
	$_SESSION['SPL'.$identifier]->SuppCurrCode = $myrow['currcode'];
	$_SESSION['SPL'.$identifier]->SuppCurrExRate = $myrow['rate'];
	$_SESSION['SPL'.$identifier]->SuppCurrDecimalPlaces = $myrow['decimalplaces'];
}
if (!isset($_SESSION['SPL'.$identifier]->CustomerID)){
	// Now check to ensure this account is not on hold */
	$sql = "SELECT debtorsmaster.name,
					holdreasons.dissallowinvoices,
					debtorsmaster.currcode,
					currencies.rate,
					currencies.decimalplaces
			FROM debtorsmaster INNER JOIN holdreasons
			ON debtorsmaster.holdreason=holdreasons.reasoncode
			INNER JOIN currencies
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE debtorsmaster.debtorno = '" . $_SESSION['CustomerID'] . "'";

	$ErrMsg = _('The customer record for') . ' : ' . $_SESSION['CustomerID']  . ' ' . _('cannot be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the customer details and failed was');
	$result =DB_query($sql,$ErrMsg,$DbgMsg);

	$myrow = DB_fetch_array($result);
	if ($myrow['dissallowinvoices'] != 1){
		if ($myrow['dissallowinvoices']==2){
			prnMsg(_('The') . ' ' . $myrow['name'] . ' ' . _('account is currently flagged as an account that needs to be watched. Please contact the credit control personnel to discuss'),'warn');
		}
	}
	$_SESSION['SPL'.$identifier]->CustomerID = $_SESSION['CustomerID'];
	$_SESSION['SPL'.$identifier]->CustomerName = $myrow['name'];
	$_SESSION['SPL'.$identifier]->CustCurrCode = $myrow['currcode'];
	$_SESSION['SPL'.$identifier]->CustCurrExRate = $myrow['rate'];
	$_SESSION['SPL'.$identifier]->CustCurrDecimalPlaces = $myrow['decimalplaces'];
}

if (isset($_POST['SelectBranch'])){

	$sql = "SELECT brname
			FROM custbranch
			WHERE debtorno='" . $_SESSION['SPL'.$identifier]->CustomerID . "'
			AND branchcode='" . $_POST['SelectBranch'] . "'";
	$BranchResult = DB_query($sql);
	$myrow=DB_fetch_array($BranchResult);
	$_SESSION['SPL'.$identifier]->BranchCode = $_POST['SelectBranch'];
	$_SESSION['SPL'.$identifier]->BranchName = $myrow['brname'];
}
echo '<div class="centre">';
if (!isset($_SESSION['SPL'.$identifier]->BranchCode)){
	echo '<br />
		<h2>' . htmlspecialchars(_('Purchase from') . ' ' . $_SESSION['SPL'.$identifier]->SupplierName . ' ' . _('in') . ' ' . $_SESSION['SPL'.$identifier]->SuppCurrCode . ' ' . _('for') . ' ' . $_SESSION['SPL'.$identifier]->CustomerName . ' (' . $_SESSION['SPL'.$identifier]->CustCurrCode . ')', ENT_QUOTES, 'UTF-8', false);
} else {
	echo '<br />
		<h2>' . htmlspecialchars(_('Purchase from') . ' ' . $_SESSION['SPL'.$identifier]->SupplierName . ' ' . _('in') . ' ' . $_SESSION['SPL'.$identifier]->SuppCurrCode . ' ' . _('for') . ' ' . $_SESSION['SPL'.$identifier]->CustomerName . ' (' . $_SESSION['SPL'.$identifier]->CustCurrCode . ') - ' . _('delivered to') . ' ' . $_SESSION['SPL'.$identifier]->BranchName . ' ' . _('branch'), ENT_QUOTES, 'UTF-8', false);
}
echo '</h2></div>';
/*if the branch details and delivery details have not been entered then select them from the list */
if (!isset($_SESSION['SPL'.$identifier]->BranchCode)){

	$sql = "SELECT branchcode,
					brname
			FROM custbranch
			WHERE debtorno='" . $_SESSION['CustomerID'] . "'";
	$BranchResult = DB_query($sql);

	if (DB_num_rows($BranchResult)>0) {

		echo '<div class="centre">';
		echo '<br />
				<br />' . _('Select the customer branch to deliver the special to from the list below');

		echo '</div>
			<br />
			<table class="selection">';

		$TableHeader = '<tr>
							<th>' ._('Code') . '</th>
							<th>' . _('Branch Name') . '</th>
						</tr>';
		echo $TableHeader;

		$j = 1;

		while ($myrow=DB_fetch_array($BranchResult)) {

			printf('<tr class="striped_row">
					<td><input type="submit" name="SelectBranch" value="%s" /></td>
					<td>%s</td>
					</tr>',
				$myrow['branchcode'],
				htmlspecialchars($myrow['brname'], ENT_QUOTES, 'UTF-8', false));

//end of page full new headings if
		}
//end of while loop

		echo '</table>';
		echo '</div>
              </form>';
		include('includes/footer.php');
		exit;

	} else {
		prnMsg( _('There are no branches defined for the customer selected') . '. ' . _('Please select a customer that has branches defined'),'info');
		include('includes/footer.php');
		exit;
	}
}


If(isset($_GET['Delete'])){  /*User hit the delete link on a line */
	$_SESSION['SPL'.$identifier]->remove_from_order($_GET['Delete']);
}


If(isset($_POST['EnterLine'])){

/*Add the header info to the session variable in any event */

	if (mb_strlen($_POST['QuotationRef'])<3){
		prnMsg(_('The reference for this order is less than 3 characters') . ' - ' . _('a reference more than 3 characters is required before the order can be added'),'warn');
	}
	if ($_POST['Initiator']==''){
		prnMsg( _('The person entering this order must be specified in the initiator field') . ' - ' . _('a blank initiator is not allowed'),'warn');
	}

	$AllowAdd = True; /*always assume the best */

	/*THEN CHECK FOR THE WORST */

	if (!is_numeric(filter_number_format($_POST['Qty']))){
		$AllowAdd = False;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The quantity of the order item must be numeric'),'warn');
	}

	if (filter_number_format($_POST['Qty'])<0){
		$AllowAdd = False;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The quantity of the ordered item entered must be a positive amount'),'warn');
	}

	if (!is_numeric(filter_number_format($_POST['Price']))){
		$AllowAdd = False;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The price entered must be numeric'),'warn');
	}

	if (!is_numeric(filter_number_format($_POST['Cost']))){
		$AllowAdd = False;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The cost entered must be numeric'),'warn');
	}

	if (((filter_number_format($_POST['Price'])/$_SESSION['SPL'.$identifier]->CustCurrExRate)-(filter_number_format($_POST['Cost'])/$_SESSION['SPL'.$identifier]->SuppCurrExRate))<0){
		$AllowAdd = False;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The sale is at a lower price than the cost'),'warn');
	}

	if (!Is_Date($_POST['ReqDelDate'])){
		$AllowAdd = False;
		prnMsg( _('Cannot Enter this order line') . '<br />' . _('The date entered must be in the format') . ' ' . $_SESSION['DefaultDateFormat'],'warn');
	}
	If ($AllowAdd == True){

		$_SESSION['SPL'.$identifier]->add_to_order ($_POST['LineNo'],
										filter_number_format($_POST['Qty']),
										$_POST['ItemDescription'],
										filter_number_format($_POST['Price']),
										filter_number_format($_POST['Cost']),
										$_POST['StkCat'],
										$_POST['ReqDelDate']);

		unset($_POST['Price']);
		unset($_POST['Cost']);
		unset($_POST['ItemDescription']);
		unset($_POST['StkCat']);
		unset($_POST['ReqDelDate']);
		unset($_POST['Qty']);
	}
}

if (isset($_POST['StkLocation'])) {
	$_SESSION['SPL'.$identifier]->StkLocation = $_POST['StkLocation'];
}
if (isset($_POST['Initiator'])) {
	$_SESSION['SPL'.$identifier]->Initiator = $_POST['Initiator'];
}
if (isset($_POST['QuotationRef'])) {
	$_SESSION['SPL'.$identifier]->QuotationRef = $_POST['QuotationRef'];
}
if (isset($_POST['Comments'])) {
	$_SESSION['SPL'.$identifier]->Comments = $_POST['Comments'];
}
if (isset($_POST['CustRef'])) {
	$_SESSION['SPL'.$identifier]->CustRef = $_POST['CustRef'];
}

if (isset($_POST['Commit'])){ /*User wishes to commit the order to the database */

 /*First do some validation
	  Is the delivery information all entered*/
	$InputError=0; /*Start off assuming the best */
	if ($_SESSION['SPL'.$identifier]->StkLocation==''
		or ! isset($_SESSION['SPL'.$identifier]->StkLocation)){
		prnMsg( _('The purchase order can not be committed to the database because there is no stock location specified to book any stock items into'),'error');
		$InputError=1;
	} elseif ($_SESSION['SPL'.$identifier]->LinesOnOrder <=0){
		$InputError=1;
		prnMsg(_('The purchase order can not be committed to the database because there are no lines entered on this order'),'error');
	}elseif (mb_strlen($_POST['QuotationRef'])<3){
		$InputError=1;
		prnMsg( _('The reference for this order is less than 3 characters') . ' - ' . _('a reference more than 3 characters is required before the order can be added'),'error');
	}elseif ($_POST['Initiator']==''){
		$InputError=1;
		prnMsg( _('The person entering this order must be specified in the initiator field') . ' - ' . _('a blank initiator is not allowed'),'error');
	}

	if ($InputError!=1){

		if (IsEmailAddress($_SESSION['UserEmail'])){
			$UserDetails  = ' <a href="mailto:' . $_SESSION['UserEmail'] . '">' . $_SESSION['UsersRealName']. '</a>';
		} else {
			$UserDetails  = ' ' . $_SESSION['UsersRealName'] . ' ';
		}

		if ($_SESSION['AutoAuthorisePO']==1) {
			//if the user has authority to authorise the PO then it will automatically be authorised
			$AuthSQL ="SELECT authlevel
						FROM purchorderauth
						WHERE userid='".$_SESSION['UserID']."'
						AND currabrev='".$_SESSION['SPL'.$identifier]->SuppCurrCode."'";

			$AuthResult=DB_query($AuthSQL);
			$AuthRow=DB_fetch_array($AuthResult);

			if (DB_num_rows($AuthResult) > 0
				and $AuthRow['authlevel'] > $_SESSION['SPL'.$identifier]->Order_Value()) { //user has authority to authrorise as well as create the order
				$StatusComment=date($_SESSION['DefaultDateFormat']).' - ' . _('Order Created and Authorised by') . $UserDetails . '<br />';
				$_SESSION['SPL'.$identifier]->AllowPrintPO=1;
				$_SESSION['SPL'.$identifier]->Status = 'Authorised';
			} else { // no authority to authorise this order
				if (DB_num_rows($AuthResult) ==0){
					$AuthMessage = _('Your authority to approve purchase orders in') . ' ' . $_SESSION['SPL'.$identifier]->SuppCurrCode . ' ' . _('has not yet been set up') . '<br />';
				} else {
					$AuthMessage = _('You can only authorise up to').' '.$_SESSION['SPL'.$identifier]->SuppCurrCode.' '.$AuthRow['authlevel'] .'.<br />';
				}

				prnMsg( _('You do not have permission to authorise this purchase order').'.<br />' .  _('This order is for').' '. $_SESSION['SPL'.$identifier]->SuppCurrCode . ' '. $_SESSION['SPL'.$identifier]->Order_Value() .'. '. $AuthMessage . _('If you think this is a mistake please contact the systems administrator') . '<br />' .  _('The order will be created with a status of pending and will require authorisation'), 'warn');

				$StatusComment=date($_SESSION['DefaultDateFormat']).' - ' . _('Order Created by') . $UserDetails;
				$_SESSION['SPL'.$identifier]->Status = 'Pending';
			}
		} else { //auto authorise is set to off
			$StatusComment=date($_SESSION['DefaultDateFormat']).' - ' . _('Order Created by') . $UserDetails;
			$_SESSION['SPL'.$identifier]->Status = 'Pending';
		}

		$sql = "SELECT contact,
						deladd1,
						deladd2,
						deladd3,
						deladd4,
						deladd5,
						deladd6
				FROM locations
				WHERE loccode='" . $_SESSION['SPL'.$identifier]->StkLocation . "'";

		$StkLocAddResult = DB_query($sql);
		$StkLocAddress = DB_fetch_array($StkLocAddResult);

		 $result = DB_Txn_Begin();

		 /*Insert to purchase order header record */
		 $sql = "INSERT INTO purchorders (supplierno,
					 					comments,
										orddate,
										rate,
										initiator,
										requisitionno,
										intostocklocation,
										deladd1,
										deladd2,
										deladd3,
										deladd4,
										deladd5,
										deladd6,
										contact,
										status,
										stat_comment,
										allowprint,
										revised,
										deliverydate)
							VALUES ('" . $_SESSION['SPL'.$identifier]->SupplierID . "',
							 		'" . $_SESSION['SPL'.$identifier]->Comments . "',
									'" . Date('Y-m-d') . "',
									'" . $_SESSION['SPL'.$identifier]->SuppCurrExRate . "',
									'" . $_SESSION['SPL'.$identifier]->Initiator . "',
									'" . $_SESSION['SPL'.$identifier]->QuotationRef . "',
									'" . $_SESSION['SPL'.$identifier]->StkLocation . "',
									'" . $StkLocAddress['deladd1'] . "',
									'" . $StkLocAddress['deladd2'] . "',
									'" . $StkLocAddress['deladd3'] . "',
									'" . $StkLocAddress['deladd4'] . "',
									'" . $StkLocAddress['deladd5'] . "',
									'" . $StkLocAddress['deladd6'] . "',
									'" . $StkLocAddress['contact'] . "',
									'" . $_SESSION['SPL'.$identifier]->Status . "',
									'" . htmlspecialchars($StatusComment, ENT_QUOTES,'UTF-8')  . "',
									'" . $_SESSION['SPL'.$identifier]->AllowPrintPO . "',
									'" . Date('Y-m-d') . "',
									'" . Date('Y-m-d') . "')";


		$ErrMsg = _('The purchase order header record could not be inserted into the database because');
		$DbgMsg = _('The SQL statement used to insert the purchase order header record and failed was');
 		$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

 		$_SESSION['SPL'.$identifier]->PurchOrderNo = GetNextTransNo(18);

		/*Insert the purchase order detail records */
		foreach ($_SESSION['SPL'.$identifier]->LineItems as $SPLLine) {

			/*Set up the part codes required for this order */

			$PartCode = "*" . $_SESSION['SPL'.$identifier]->PurchOrderNo . "_" . $SPLLine->LineNo;

			$PartAlreadyExists =True; /*assume the worst */
			$Counter = 0;
			While ($PartAlreadyExists==True) {
				$sql = "SELECT COUNT(*) FROM stockmaster WHERE stockid = '" . $PartCode . "'";
				$PartCountResult = DB_query($sql);
				$PartCount = DB_fetch_row($PartCountResult);
				if ($PartCount[0]!=0){
					$PartAlreadyExists =True;
					if (mb_strlen($PartCode)==20){
						$PartCode = '*' . mb_strtoupper(mb_substr($_SESSION['SPL'.$identifier]->PurchOrderNo,0,13)) . '_' . $SPLLine->LineNo;
					}
					$PartCode = $PartCode . $Counter;
					$Counter++;
				} else {
					$PartAlreadyExists =False;
				}
			}

			$_SESSION['SPL'.$identifier]->LineItems[$SPLLine->LineNo]->PartCode = $PartCode;

			$sql = "INSERT INTO stockmaster (stockid,
							categoryid,
							description,
							longdescription,
							materialcost)
					VALUES ('" . $PartCode . "',
						'" . $SPLLine->StkCat . "',
						'" . $SPLLine->ItemDescription . "',
						'" .  $SPLLine->ItemDescription . "',
						'" . $SPLLine->Cost . "')";


			$ErrMsg = _('The item record for line') . ' ' . $SPLLine->LineNo . ' ' . _('could not be created because');
			$DbgMsg = _('The SQL statement used to insert the item and failed was');

			$result =DB_query($sql,$ErrMsg,$DbgMsg,true);

			$sql = "INSERT INTO locstock (loccode, stockid)
					SELECT loccode,'" . $PartCode . "' FROM locations";
			$ErrMsg = _('The item stock locations for the special order line') . ' ' . $SPLLine->LineNo . ' ' ._('could not be created because');
			$DbgMsg = _('The SQL statement used to insert the location stock records and failed was');
			$result =DB_query($sql,$ErrMsg,$DbgMsg,true);

			/*need to get the stock category GL information */
			$sql = "SELECT stockact FROM stockcategory WHERE categoryid = '" . $SPLLine->StkCat . "'";
			$ErrMsg = _('The item stock category information for the special order line') . ' ' . $SPLLine->LineNo . ' ' . _('could not be retrieved because');
			$DbgMsg = _('The SQL statement used to get the category information and that failed was');
			$result =DB_query($sql,$ErrMsg,$DbgMsg,true);

			$StkCatGL=DB_fetch_row($result);
			$GLCode = $StkCatGL[0];

			$OrderDate = FormatDateForSQL($SPLLine->ReqDelDate);

			$sql = "INSERT INTO purchorderdetails (orderno,
								itemcode,
								deliverydate,
								itemdescription,
								glcode,
								unitprice,
								quantityord)
					VALUES ('";
			$sql = $sql . $_SESSION['SPL'.$identifier]->PurchOrderNo . "',
					'" . $PartCode . "',
					'" . $OrderDate . "',
					'" . $SPLLine->ItemDescription . "',
					'" . $GLCode . "',
					'" . $SPLLine->Cost . "',
					'" . $SPLLine->Quantity . "')";

			$ErrMsg = _('One of the purchase order detail records could not be inserted into the database because');
			$DbgMsg = _('The SQL statement used to insert the purchase order detail record and failed was');
			$result = DB_query($sql,$ErrMsg,$DbgMsg,true);

		} /* end of the loop round the detail line items on the order */

		echo '<br /><br />' . _('Purchase Order') . ' ' . $_SESSION['SPL'.$identifier]->PurchOrderNo . ' ' . _('on') . ' ' . $_SESSION['SPL'.$identifier]->SupplierName . ' ' . _('has been created');
		echo '<br /><a href="' . $RootPath . '/PO_PDFPurchOrder.php?OrderNo=' . $_SESSION['SPL'.$identifier]->PurchOrderNo . '">' . _('Print Purchase Order') . '</a>';

/*Now insert the sales order too */

		/*First get the customer delivery information */
		$sql = "SELECT salestype,
					brname,
					braddress1,
					braddress2,
					braddress3,
					braddress4,
					braddress5,
					braddress6,
					defaultshipvia,
					email,
					phoneno
				FROM custbranch INNER JOIN debtorsmaster
					ON custbranch.debtorno=debtorsmaster.debtorno
				WHERE custbranch.debtorno='" . $_SESSION['SPL'.$identifier]->CustomerID . "'
				AND custbranch.branchcode = '" . $_SESSION['SPL'.$identifier]->BranchCode . "'";

		$ErrMsg = _('The delivery and sales type for the customer could not be retrieved for this special order') . ' ' . $SPLLine->LineNo . ' ' . _('because');
		$DbgMsg = _('The SQL statement used to get the delivery details and that failed was');
		$result =DB_query($sql,$ErrMsg,$DbgMsg,true);

		$BranchDetails=DB_fetch_array($result);
		$SalesOrderNo=GetNextTransNo (30);
		$HeaderSQL = "INSERT INTO salesorders (orderno,
											debtorno,
											branchcode,
											customerref,
											orddate,
											ordertype,
											shipvia,
											deliverto,
											deladd1,
											deladd2,
											deladd3,
											deladd4,
											deladd5,
											deladd6,
											contactphone,
											contactemail,
											fromstkloc,
											deliverydate)
					VALUES ('" . $SalesOrderNo."',
							'" . $_SESSION['SPL'.$identifier]->CustomerID . "',
							'" . $_SESSION['SPL'.$identifier]->BranchCode . "',
							'" . $_SESSION['SPL'.$identifier]->CustRef ."',
							'" . Date('Y-m-d') . "',
							'" . $BranchDetails['salestype'] . "',
							'" . $BranchDetails['defaultshipvia'] ."',
							'" . $BranchDetails['brname'] . "',
							'" . $BranchDetails['braddress1'] . "',
							'" . $BranchDetails['braddress2'] . "',
							'" . $BranchDetails['braddress3'] . "',
							'" . $BranchDetails['braddress4'] . "',
							'" . $BranchDetails['braddress5'] . "',
							'" . $BranchDetails['braddress6'] . "',
							'" . $BranchDetails['phoneno'] . "',
							'" . $BranchDetails['email'] . "',
							'" . $_SESSION['SPL'.$identifier]->StkLocation ."',
							'" . $OrderDate . "')";

		$ErrMsg = _('The sales order cannot be added because');
		$InsertQryResult = DB_query($HeaderSQL,$ErrMsg, $DbgMsg);

		$StartOf_LineItemsSQL = "INSERT INTO salesorderdetails (orderno,
																stkcode,
																unitprice,
																quantity,
																orderlineno)
						VALUES ('" .  $SalesOrderNo . "'";

		$ErrMsg = _('There was a problem inserting a line into the sales order because');

		foreach ($_SESSION['SPL'.$identifier]->LineItems as $StockItem) {

			$LineItemsSQL = $StartOf_LineItemsSQL . ",
							'" . $StockItem->PartCode . "',
							'". $StockItem->Price . "',
							'" . $StockItem->Quantity . "',
							'" . $StockItem->LineNo . "')";
			$Ins_LineItemResult = DB_query($LineItemsSQL,$ErrMsg);

		} /* inserted line items into sales order details */

		unset($_SESSION['SPL'.$identifier]);
		prnMsg(_('Sales Order Number') . ' ' . $SalesOrderNo . ' ' . _('has been entered') . '. <br />' .
			_('Orders created on a cash sales account may need the delivery details for the order to be modified') . '. <br /><br />' .
				_('A freight charge may also be applicable'),'success');

		if (count($_SESSION['AllowedPageSecurityTokens'])>1){

			/* Only allow print of packing slip for internal staff - customer logon's cannot go here */
			echo '<p><a href="' . $RootPath . '/PrintCustOrder.php?TransNo=' . $SalesOrderNo . '">' . _('Print packing slip') . ' (' . _('Preprinted stationery') . ')</a></p>';
			echo '<p><a href="' . $RootPath . '/PrintCustOrder_generic.php?TransNo=' . $SalesOrderNo . '">' . _('Print packing slip') . ' (' . _('Laser') . ')</a></p>';

		}

		$Result = DB_Txn_Commit();
		unset($_SESSION['SPL'.$identifier]); /*Clear the PO data to allow a newy to be input*/
		echo '<br /><br /><a href="' . $RootPath . '/SpecialOrder.php">' . _('Enter A New Special Order') . '</a>';
		exit;
	} /*end if there were no input errors trapped */
} /* end of the code to do transfer the SPL object to the database  - user hit the place Order*/


/*Show the header information for modification */

echo '<table><tr><td>' . _('Receive Purchase Into and Sell From') . ': <select name="StkLocation">';

$sql = "SELECT locations.loccode, locationname FROM locations
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
$LocnResult = DB_query($sql);
if (!isset($_SESSION['SPL'.$identifier]->StkLocation) or $_SESSION['SPL'.$identifier]->StkLocation==''){ /*If this is the first time the form loaded set up defaults */
	$_SESSION['SPL'.$identifier]->StkLocation = $_SESSION['UserStockLocation'];
}

while ($LocnRow=DB_fetch_array($LocnResult)){
	if ($_SESSION['SPL'.$identifier]->StkLocation == $LocnRow['loccode']){
		echo '<option selected="selected" value="' . $LocnRow['loccode'] . '">' . $LocnRow['locationname'] . '</option>';
	} else {
		echo '<option value="' . $LocnRow['loccode'] . '">' . $LocnRow['locationname'] . '</option>';
	}
}
echo '</select></td>';

echo '<td>' . _('Initiated By') . ': <input type="text" name="Initiator" size="11" maxlength="10" value="' . $_SESSION['SPL'.$identifier]->Initiator . '" /></td>
	<td>' . _('Special Ref') . ': <input type="text" name="QuotationRef" size="16" maxlength="15" value="' . $_SESSION['SPL'.$identifier]->QuotationRef . '" /></td>
	<td>' . _('Customer Ref') . ': <input type="text" name="CustRef" size="11" maxlength="10" value="' . $_SESSION['SPL'.$identifier]->CustRef . '" /></td>
	</tr>
	<tr>
		<td valign="top" colspan="2">' . _('Comments') . ': <textarea name="Comments" cols="70" rows="2">' . $_SESSION['SPL'.$identifier]->Comments . '</textarea></td>
	</tr>
</table>
<hr />'; /* Rule off the header */

/*Now show the order so far */

if (count($_SESSION['SPL'.$identifier]->LineItems)>0){

	echo '<div class="centre"><b>' . _('Special Order Summary') . '</b></div>';
	echo '<table class="selection" cellpadding="2" border="1">';

	echo '<tr>
			<th>' . _('Item Description') . '</th>
			<th>' . _('Delivery') . '</th>
			<th>' . _('Quantity') . '</th>
			<th>' . _('Purchase Cost') . '<br />' . $_SESSION['SPL'.$identifier]->SuppCurrCode . '</th>
			<th>' . _('Sell Price') . '<br />' . $_SESSION['SPL'.$identifier]->CustCurrCode . '</th>
			<th>' . _('Total Cost') . '<br />' . $_SESSION['SPL'.$identifier]->SuppCurrCode .  '</th>
			<th>' . _('Total Price') . '<br />' . $_SESSION['SPL'.$identifier]->CustCurrCode .  '</th>
			<th>' . _('Total Cost') . '<br />' . $_SESSION['CompanyRecord']['currencydefault'] .  '</th>
			<th>' . _('Total Price') . '<br />' . $_SESSION['CompanyRecord']['currencydefault'] .  '</th>
		</tr>';

	$_SESSION['SPL'.$identifier]->total = 0;

	foreach ($_SESSION['SPL'.$identifier]->LineItems as $SPLLine) {

		$LineTotal = $SPLLine->Quantity * $SPLLine->Price;
		$LineCostTotal = $SPLLine->Quantity * $SPLLine->Cost;
		$DisplayLineTotal = locale_number_format($LineTotal,$_SESSION['SPL'.$identifier]->CustCurrDecimalPlaces);
		$DisplayLineCostTotal = locale_number_format($LineCostTotal,$_SESSION['SPL'.$identifier]->SuppCurrDecimalPlaces);
		$DisplayLineTotalCurr = locale_number_format($LineTotal/$_SESSION['SPL'.$identifier]->CustCurrExRate,$_SESSION['CompanyRecord']['decimalplaces']);
		$DisplayLineCostTotalCurr = locale_number_format($LineCostTotal/$_SESSION['SPL'.$identifier]->SuppCurrExRate,$_SESSION['CompanyRecord']['decimalplaces']);
		$DisplayCost = locale_number_format($SPLLine->Cost,$_SESSION['SPL'.$identifier]->SuppCurrDecimalPlaces);
		$DisplayPrice = locale_number_format($SPLLine->Price,$_SESSION['SPL'.$identifier]->CustCurrDecimalPlaces);
		$DisplayQuantity = locale_number_format($SPLLine->Quantity,'Variable');

		echo '<tr class="striped_row">
			<td>' . $SPLLine->ItemDescription . '</td>
			<td>' . $SPLLine->ReqDelDate . '</td>
			<td class="number">' . $DisplayQuantity . '</td>
			<td class="number">' . $DisplayCost . '</td>
			<td class="number">' . $DisplayPrice . '</td>
			<td class="number">' . $DisplayLineCostTotal . '</td>
			<td class="number">' . $DisplayLineTotal . '</td>
			<td class="number">' . $DisplayLineCostTotalCurr . '</td>
			<td class="number">' . $DisplayLineTotalCurr . '</td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier=' . $identifier . '&Delete=' . $SPLLine->LineNo . '">' . _('Delete') . '</a></td>
		</tr>';

		$_SESSION['SPL'.$identifier]->total += ($LineTotal/$_SESSION['SPL'.$identifier]->CustCurrExRate);
	}

	$DisplayTotal = locale_number_format($_SESSION['SPL'.$identifier]->total,$_SESSION['SPL'.$identifier]->CustCurrDecimalPlaces);
	echo '<tr>',
/*		'<td colspan="8" class="number">' . _('TOTAL Excl Tax') . '</td>',*/
		'<td class="number" colspan="8">', _('Total Excluding Tax'), '</td>',
		'<td class="number"><b>', $DisplayTotal, '</b></td>
	</tr>
	</table>';

}

/*Set up the form to enter new special items into */

echo '<input type="hidden" name="LineNo" value="' . ($_SESSION['SPL'.$identifier]->LinesOnOrder + 1) .'" />';

if (!isset($_POST['ItemDescription'])) {
	$_POST['ItemDescription']='';
}

echo '<table>';
echo '<tr>
		<td>' . _('Ordered item Description') . ':</td>
		<td><input type="text" name="ItemDescription" size="40" maxlength="40" value="' . $_POST['ItemDescription'] . '" /></td>
	</tr>';

echo '<tr>
		<td>' . _('Category') . ':</td>
		<td><select name="StkCat">';

$sql = "SELECT categoryid, categorydescription FROM stockcategory";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql, $ErrMsg, $DbgMsg);

while ($myrow=DB_fetch_array($result)){
	if (isset($_POST['StkCat']) and $myrow['categoryid']==$_POST['StkCat']){
		echo '<option selected="selected" value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
	} else {
		echo '<option value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
	}
}
echo '</select></td>
	</tr>';

/*default the order quantity to 1 unit */
$_POST['Qty'] = 1;

echo '<tr>
		<td>' . _('Order Quantity') . ':</td>
		<td><input type="text" class="number" size="7" maxlength="6" name="Qty" value="' . locale_number_format($_POST['Qty'],'Variable') . '" /></td>
	</tr>';

if (!isset($_POST['Cost'])) {
	$_POST['Cost']=0;
}
echo '<tr>
		<td>' . _('Unit Cost') . ':</td>
		<td><input type="text" class="number" size="15" maxlength="14" name="Cost" value="' . locale_number_format($_POST['Cost'],$_SESSION['SPL'.$identifier]->SuppCurrDecimalPlaces) . '" /></td>
	</tr>';

if (!isset($_POST['Price'])) {
	$_POST['Price']=0;
}
echo '<tr>
		<td>' . _('Unit Price') . ':</td>
		<td><input type="text" class="number" size="15" maxlength="14" name="Price" value="' . locale_number_format($_POST['Price'],$_SESSION['SPL'.$identifier]->CustCurrDecimalPlaces) . '" /></td>
	</tr>';

/*Default the required delivery date to tomorrow as a starting point */
$_POST['ReqDelDate'] = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,Date('m'),Date('d')+1,Date('y')));

echo '<tr>
		<td>' . _('Required Delivery Date') . ':</td>
		<td><input type="text" class="date" size="11" maxlength="10" name="ReqDelDate" value="' . $_POST['ReqDelDate'] . '" /></td>
	</tr>';

echo '</table>'; /* end of main table */

echo '<div class="centre">
		<input type="submit" name="EnterLine" value="' . _('Add Item to Order') . '" />
		<br />
		<br />
		<input type="submit" name="Cancel" value="' . _('Start Again') . '" />
		<br />
		<br />
		<input type="submit" name="Commit" value="' . _('Process This Order') . '" />
	</div>
    </div>
	</form>';

include('includes/footer.php');
?>
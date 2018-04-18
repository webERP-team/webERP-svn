<?php

include('includes/DefineCartClass.php');

/* Session started in session.php for password checking and authorisation level check
config.php is in turn included in session.php*/

include('includes/session.php');

if (isset($_GET['ModifyOrderNumber'])) {
	$Title = _('Modifying Order') . ' ' . $_GET['ModifyOrderNumber'];
} else {
	$Title = _('Select Order Items');
}
/* webERP manual links before header.php */
$ViewTopic= 'SalesOrders';
$BookMark = 'SalesOrderEntry';

include('includes/header.php');
include('includes/GetPrice.inc');
include('includes/SQL_CommonFunctions.inc');

if (isset($_POST['QuickEntry'])){
	unset($_POST['PartSearch']);
}

if (isset($_POST['SelectingOrderItems'])){
	foreach ($_POST as $FormVariable => $Quantity) {
		if (mb_strpos($FormVariable,'OrderQty')!==false) {
			$NewItemArray[$_POST['StockID' . mb_substr($FormVariable,8)]] = filter_number_format($Quantity);
		}
	}
}

if (isset($_POST['UploadFile'])) {
	if (isset($_FILES['CSVFile']) and $_FILES['CSVFile']['name']) {
		//check file info
		$FileName = $_FILES['CSVFile']['name'];
		$TempName = $_FILES['CSVFile']['tmp_name'];
		$FileSize = $_FILES['CSVFile']['size'];
		//get file handle
		$FileHandle = fopen($TempName, 'r');
		$Row = 0;
		$InsertNum = 0;
		while (($FileRow = fgetcsv($FileHandle, 10000, ",")) !== False) {
			/* Check the stock code exists */
			++$Row;
			$SQL = "SELECT stockid FROM stockmaster WHERE stockid='" . $FileRow[0] . "'";
			$Result = DB_query($SQL);
			if (DB_num_rows($Result) > 0) {
				$NewItemArray[$FileRow[0]] = filter_number_format($FileRow[1]);
				++$InsertNum;
			}
		}
	}
	$_POST['SelectingOrderItems'] = 1;
	if (sizeof($NewItemArray) == 0) {
		prnMsg(_('There are no items that can be imported'), 'error');
	} else {
		prnMsg($InsertNum . ' ' . _('of') . ' ' . $Row . ' ' . _('rows have been added to the order'), 'info');
	}
}

if (isset($_GET['NewItem'])){
	$NewItem = trim($_GET['NewItem']);
}

if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other order entry sessions on the same machine  */
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

if (isset($_GET['NewOrder'])){
  /*New order entry - clear any existing order details from the Items object and initiate a newy*/
	 if (isset($_SESSION['Items'.$identifier])){
		unset ($_SESSION['Items'.$identifier]->LineItems);
		$_SESSION['Items'.$identifier]->ItemsOrdered=0;
		unset ($_SESSION['Items'.$identifier]);
	}

	$_SESSION['ExistingOrder' .$identifier]=0;
	$_SESSION['Items'.$identifier] = new cart;

	if ($CustomerLogin==1){ //its a customer logon
		$_SESSION['Items'.$identifier]->DebtorNo=$_SESSION['CustomerID'];
		$_SESSION['Items'.$identifier]->BranchCode=$_SESSION['UserBranch'];
		$SelectedCustomer = $_SESSION['CustomerID'];
		$SelectedBranch = $_SESSION['UserBranch'];
		$_SESSION['RequireCustomerSelection'] = 0;
	} else {
		$_SESSION['Items'.$identifier]->DebtorNo='';
		$_SESSION['Items'.$identifier]->BranchCode='';
		$_SESSION['RequireCustomerSelection'] = 1;
	}

}

if (isset($_GET['ModifyOrderNumber'])
	AND $_GET['ModifyOrderNumber']!=''){

/* The delivery check screen is where the details of the order are either updated or inserted depending on the value of ExistingOrder */

	if (isset($_SESSION['Items'.$identifier])){
		unset ($_SESSION['Items'.$identifier]->LineItems);
		unset ($_SESSION['Items'.$identifier]);
	}
	$_SESSION['ExistingOrder'.$identifier]=$_GET['ModifyOrderNumber'];
	$_SESSION['RequireCustomerSelection'] = 0;
	$_SESSION['Items'.$identifier] = new cart;

/*read in all the guff from the selected order into the Items cart  */

	$OrderHeaderSQL = "SELECT salesorders.debtorno,
			 				  debtorsmaster.name,
							  salesorders.branchcode,
							  salesorders.customerref,
							  salesorders.comments,
							  salesorders.orddate,
							  salesorders.ordertype,
							  salestypes.sales_type,
							  salesorders.shipvia,
							  salesorders.deliverto,
							  salesorders.deladd1,
							  salesorders.deladd2,
							  salesorders.deladd3,
							  salesorders.deladd4,
							  salesorders.deladd5,
							  salesorders.deladd6,
							  salesorders.contactphone,
							  salesorders.contactemail,
							  salesorders.salesperson,
							  salesorders.freightcost,
							  salesorders.deliverydate,
							  debtorsmaster.currcode,
							  currencies.decimalplaces,
							  paymentterms.terms,
							  salesorders.fromstkloc,
							  salesorders.printedpackingslip,
							  salesorders.datepackingslipprinted,
							  salesorders.quotation,
							  salesorders.quotedate,
							  salesorders.confirmeddate,
							  salesorders.deliverblind,
							  debtorsmaster.customerpoline,
							  locations.locationname,
							  custbranch.estdeliverydays,
							  custbranch.salesman
						FROM salesorders
						INNER JOIN debtorsmaster
						ON salesorders.debtorno = debtorsmaster.debtorno
						INNER JOIN salestypes
						ON salesorders.ordertype=salestypes.typeabbrev
						INNER JOIN custbranch
						ON salesorders.debtorno = custbranch.debtorno
						AND salesorders.branchcode = custbranch.branchcode
						INNER JOIN paymentterms
						ON debtorsmaster.paymentterms=paymentterms.termsindicator
						INNER JOIN locations
						ON locations.loccode=salesorders.fromstkloc
						INNER JOIN currencies
						ON debtorsmaster.currcode=currencies.currabrev
						INNER JOIN locationusers ON locationusers.loccode=salesorders.fromstkloc AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
						WHERE salesorders.orderno = '" . $_GET['ModifyOrderNumber'] . "'";

	$ErrMsg =  _('The order cannot be retrieved because');
	$GetOrdHdrResult = DB_query($OrderHeaderSQL,$ErrMsg);

	if (DB_num_rows($GetOrdHdrResult)==1) {

		$myrow = DB_fetch_array($GetOrdHdrResult);
		if ($_SESSION['SalesmanLogin']!='' AND $_SESSION['SalesmanLogin']!=$myrow['salesman']){
			prnMsg(_('Your account is set up to see only a specific salespersons orders. You are not authorised to modify this order'),'error');
			include('includes/footer.php');
			exit;
		}
		if ($CustomerLogin == 1 AND $_SESSION['CustomerID'] != $myrow['debtorno']) {
			echo '<p class="bad">' . _('This transaction is addressed to another customer and cannot be displayed for privacy reasons') . '. ' . _('Please select only transactions relevant to your company').'</p>';
			include('includes/footer.inc');
			exit;

		}
		$_SESSION['Items'.$identifier]->OrderNo = $_GET['ModifyOrderNumber'];
		$_SESSION['Items'.$identifier]->DebtorNo = $myrow['debtorno'];
		$_SESSION['Items'.$identifier]->CreditAvailable = GetCreditAvailable($_SESSION['Items'.$identifier]->DebtorNo);
/*CustomerID defined in header.php */
		$_SESSION['Items'.$identifier]->Branch = $myrow['branchcode'];
		$_SESSION['Items'.$identifier]->CustomerName = $myrow['name'];
		$_SESSION['Items'.$identifier]->CustRef = $myrow['customerref'];
		$_SESSION['Items'.$identifier]->Comments = stripcslashes($myrow['comments']);
		$_SESSION['Items'.$identifier]->PaymentTerms =$myrow['terms'];
		$_SESSION['Items'.$identifier]->DefaultSalesType =$myrow['ordertype'];
		$_SESSION['Items'.$identifier]->SalesTypeName =$myrow['sales_type'];
		$_SESSION['Items'.$identifier]->DefaultCurrency = $myrow['currcode'];
		$_SESSION['Items'.$identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];
		$_SESSION['Items'.$identifier]->ShipVia = $myrow['shipvia'];
		$BestShipper = $myrow['shipvia'];
		$_SESSION['Items'.$identifier]->DeliverTo = $myrow['deliverto'];
		$_SESSION['Items'.$identifier]->DeliveryDate = ConvertSQLDate($myrow['deliverydate']);
		$_SESSION['Items'.$identifier]->DelAdd1 = $myrow['deladd1'];
		$_SESSION['Items'.$identifier]->DelAdd2 = $myrow['deladd2'];
		$_SESSION['Items'.$identifier]->DelAdd3 = $myrow['deladd3'];
		$_SESSION['Items'.$identifier]->DelAdd4 = $myrow['deladd4'];
		$_SESSION['Items'.$identifier]->DelAdd5 = $myrow['deladd5'];
		$_SESSION['Items'.$identifier]->DelAdd6 = $myrow['deladd6'];
		$_SESSION['Items'.$identifier]->PhoneNo = $myrow['contactphone'];
		$_SESSION['Items'.$identifier]->Email = $myrow['contactemail'];
		$_SESSION['Items'.$identifier]->SalesPerson = $myrow['salesperson'];
		$_SESSION['Items'.$identifier]->Location = $myrow['fromstkloc'];
		$_SESSION['Items'.$identifier]->LocationName = $myrow['locationname'];
		$_SESSION['Items'.$identifier]->Quotation = $myrow['quotation'];
		$_SESSION['Items'.$identifier]->QuoteDate = ConvertSQLDate($myrow['quotedate']);
		$_SESSION['Items'.$identifier]->ConfirmedDate = ConvertSQLDate($myrow['confirmeddate']);
		$_SESSION['Items'.$identifier]->FreightCost = $myrow['freightcost'];
		$_SESSION['Items'.$identifier]->Orig_OrderDate = $myrow['orddate'];
		$_SESSION['PrintedPackingSlip'] = $myrow['printedpackingslip'];
		$_SESSION['DatePackingSlipPrinted'] = $myrow['datepackingslipprinted'];
		$_SESSION['Items'.$identifier]->DeliverBlind = $myrow['deliverblind'];
		$_SESSION['Items'.$identifier]->DefaultPOLine = $myrow['customerpoline'];
		$_SESSION['Items'.$identifier]->DeliveryDays = $myrow['estdeliverydays'];

		//Get The exchange rate used for GPPercent calculations on adding or amending items
		if ($_SESSION['Items'.$identifier]->DefaultCurrency != $_SESSION['CompanyRecord']['currencydefault']){
			$ExRateResult = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['Items'.$identifier]->DefaultCurrency . "'");
			if (DB_num_rows($ExRateResult)>0){
				$ExRateRow = DB_fetch_row($ExRateResult);
				$ExRate = $ExRateRow[0];
			} else {
				$ExRate =1;
			}
		} else {
			$ExRate = 1;
		}

/*need to look up customer name from debtors master then populate the line items array with the sales order details records */

			$LineItemsSQL = "SELECT salesorderdetails.orderlineno,
									salesorderdetails.stkcode,
									stockmaster.description,
									stockmaster.longdescription,
									stockmaster.volume,
									stockmaster.grossweight,
									stockmaster.units,
									stockmaster.serialised,
									stockmaster.nextserialno,
									stockmaster.eoq,
									salesorderdetails.unitprice,
									salesorderdetails.quantity,
									salesorderdetails.discountpercent,
									salesorderdetails.actualdispatchdate,
									salesorderdetails.qtyinvoiced,
									salesorderdetails.narrative,
									salesorderdetails.itemdue,
									salesorderdetails.poline,
									locstock.quantity as qohatloc,
									stockmaster.mbflag,
									stockmaster.discountcategory,
									stockmaster.decimalplaces,
									stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standardcost,
									salesorderdetails.completed
								FROM salesorderdetails INNER JOIN stockmaster
								ON salesorderdetails.stkcode = stockmaster.stockid
								INNER JOIN locstock ON locstock.stockid = stockmaster.stockid
								WHERE  locstock.loccode = '" . $myrow['fromstkloc'] . "'
								AND salesorderdetails.orderno ='" . $_GET['ModifyOrderNumber'] . "'
								ORDER BY salesorderdetails.orderlineno";

		$ErrMsg = _('The line items of the order cannot be retrieved because');
		$LineItemsResult = DB_query($LineItemsSQL,$ErrMsg);
		if (DB_num_rows($LineItemsResult)>0) {

			while ($myrow=DB_fetch_array($LineItemsResult)) {
					if ($myrow['completed']==0){
						$_SESSION['Items'.$identifier]->add_to_cart($myrow['stkcode'],
																	$myrow['quantity'],
																	$myrow['description'],
																	$myrow['longdescription'],
																	$myrow['unitprice'],
																	$myrow['discountpercent'],
																	$myrow['units'],
																	$myrow['volume'],
																	$myrow['grossweight'],
																	$myrow['qohatloc'],
																	$myrow['mbflag'],
																	$myrow['actualdispatchdate'],
																	$myrow['qtyinvoiced'],
																	$myrow['discountcategory'],
																	0,	/*Controlled*/
																	$myrow['serialised'],
																	$myrow['decimalplaces'],
																	$myrow['narrative'],
																	'No', /* Update DB */
																	$myrow['orderlineno'],
																	0,
																	'',
																	ConvertSQLDate($myrow['itemdue']),
																	$myrow['poline'],
																	$myrow['standardcost'],
																	$myrow['eoq'],
																	$myrow['nextserialno'],
																	$ExRate,
																	$identifier );

				/*Just populating with existing order - no DBUpdates */
					}
					$LastLineNo = $myrow['orderlineno'];
			} /* line items from sales order details */
			 $_SESSION['Items'.$identifier]->LineCounter = $LastLineNo+1;
		} //end of checks on returned data set
	}
}


if (!isset($_SESSION['Items'.$identifier])){
	/* It must be a new order being created $_SESSION['Items'.$identifier] would be set up from the order
	modification code above if a modification to an existing order. Also $ExistingOrder would be
	set to 1. The delivery check screen is where the details of the order are either updated or
	inserted depending on the value of ExistingOrder */

	$_SESSION['ExistingOrder'.$identifier]=0;
	$_SESSION['Items'.$identifier] = new cart;
	$_SESSION['PrintedPackingSlip'] = 0; /*Of course cos the order aint even started !!*/



	if (in_array($_SESSION['PageSecurityArray']['ConfirmDispatch_Invoice.php'], $_SESSION['AllowedPageSecurityTokens'])
		AND ($_SESSION['Items'.$identifier]->DebtorNo==''
		OR !isset($_SESSION['Items'.$identifier]->DebtorNo))){

	/* need to select a customer for the first time out if authorisation allows it and if a customer
	 has been selected for the order or not the session variable CustomerID holds the customer code
	 already as determined from user id /password entry  */
		$_SESSION['RequireCustomerSelection'] = 1;
	} else {
		$_SESSION['RequireCustomerSelection'] = 0;
	}
}

if (isset($_POST['ChangeCustomer']) AND $_POST['ChangeCustomer']!=''){

	if ($_SESSION['Items'.$identifier]->Any_Already_Delivered()==0){
		$_SESSION['RequireCustomerSelection']=1;
	} else {
		prnMsg(_('The customer the order is for cannot be modified once some of the order has been invoiced'),'warn');
	}
}

//Customer logins are not allowed to select other customers hence in_array($_SESSION['PageSecurityArray']['ConfirmDispatch_Invoice.php'], $_SESSION['AllowedPageSecurityTokens'])
if (isset($_POST['SearchCust'])
	AND $_SESSION['RequireCustomerSelection']==1
	AND in_array($_SESSION['PageSecurityArray']['ConfirmDispatch_Invoice.php'], $_SESSION['AllowedPageSecurityTokens'])){

		$SQL = "SELECT custbranch.brname,
					custbranch.contactname,
					custbranch.phoneno,
					custbranch.faxno,
					custbranch.branchcode,
					custbranch.debtorno,
					debtorsmaster.name
				FROM custbranch
				LEFT JOIN debtorsmaster
				ON custbranch.debtorno=debtorsmaster.debtorno
				WHERE custbranch.disabletrans=0 ";

	if (($_POST['CustKeywords']=='') AND ($_POST['CustCode']=='')  AND ($_POST['CustPhone']=='')) {
		$SQL .= "";
	} else {
		//insert wildcard characters in spaces
		$_POST['CustKeywords'] = mb_strtoupper(trim($_POST['CustKeywords']));
		$SearchString = str_replace(' ', '%', $_POST['CustKeywords']) ;

		$SQL .= "AND custbranch.brname " . LIKE . " '%" . $SearchString . "%'
				AND custbranch.branchcode " . LIKE . " '%" . mb_strtoupper(trim($_POST['CustCode'])) . "%'
				AND custbranch.phoneno " . LIKE . " '%" . trim($_POST['CustPhone']) . "%'";

	} /*one of keywords or custcode was more than a zero length string */
	if ($_SESSION['SalesmanLogin']!=''){
		$SQL .= " AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "'";
	}
	$SQL .=	" ORDER BY custbranch.debtorno,
					custbranch.branchcode";

	$ErrMsg = _('The searched customer records requested cannot be retrieved because');
	$result_CustSelect = DB_query($SQL,$ErrMsg);

	if (DB_num_rows($result_CustSelect)==1){
		$myrow=DB_fetch_array($result_CustSelect);
		$SelectedCustomer = $myrow['debtorno'];
		$SelectedBranch = $myrow['branchcode'];
	} elseif (DB_num_rows($result_CustSelect)==0){
		prnMsg(_('No Customer Branch records contain the search criteria') . ' - ' . _('please try again') . ' - ' . _('Note a Customer Branch Name may be different to the Customer Name'),'info');
	}
} /*end of if search for customer codes/names */

if (isset($_POST['JustSelectedACustomer'])){

	/*Need to figure out the number of the form variable that the user clicked on */
	for ($i=0;$i<count($_POST);$i++){ //loop through the returned customers
		if(isset($_POST['SubmitCustomerSelection'.$i])){
			break;
		}
	}
	if ($i==count($_POST) AND !isset($SelectedCustomer)){//if there is ONLY one customer searched at above, the $SelectedCustomer already setup, then there is a wrong warning
		prnMsg(_('Unable to identify the selected customer'),'error');
	} elseif(!isset($SelectedCustomer)) {
		$SelectedCustomer = $_POST['SelectedCustomer'.$i];
		$SelectedBranch = $_POST['SelectedBranch'.$i];
	}
}

/* will only be true if page called from customer selection form or set because only one customer
 record returned from a search so parse the $SelectCustomer string into customer code and branch code */
if (isset($SelectedCustomer)) {

	$_SESSION['Items'.$identifier]->DebtorNo = trim($SelectedCustomer);
	$_SESSION['Items'.$identifier]->Branch = trim($SelectedBranch);

	// Now check to ensure this account is not on hold */
	$sql = "SELECT debtorsmaster.name,
					holdreasons.dissallowinvoices,
					debtorsmaster.salestype,
					salestypes.sales_type,
					debtorsmaster.currcode,
					debtorsmaster.customerpoline,
					paymentterms.terms,
					currencies.decimalplaces
			FROM debtorsmaster INNER JOIN holdreasons
			ON debtorsmaster.holdreason=holdreasons.reasoncode
			INNER JOIN salestypes
			ON debtorsmaster.salestype=salestypes.typeabbrev
			INNER JOIN paymentterms
			ON debtorsmaster.paymentterms=paymentterms.termsindicator
			INNER JOIN currencies
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE debtorsmaster.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo. "'";

	$ErrMsg = _('The details of the customer selected') . ': ' .  $_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('cannot be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the customer details and failed was') . ':';
	$result =DB_query($sql,$ErrMsg,$DbgMsg);

	$myrow = DB_fetch_array($result);
	if ($myrow[1] != 1){
		if ($myrow[1]==2){
			prnMsg(_('The') . ' ' . htmlspecialchars($myrow[0], ENT_QUOTES, 'UTF-8', false) . ' ' . _('account is currently flagged as an account that needs to be watched. Please contact the credit control personnel to discuss'),'warn');
		}

		$_SESSION['RequireCustomerSelection']=0;
		$_SESSION['Items'.$identifier]->CustomerName = $myrow['name'];

# the sales type determines the price list to be used by default the customer of the user is
# defaulted from the entry of the userid and password.

		$_SESSION['Items'.$identifier]->DefaultSalesType = $myrow['salestype'];
		$_SESSION['Items'.$identifier]->SalesTypeName = $myrow['sales_type'];
		$_SESSION['Items'.$identifier]->DefaultCurrency = $myrow['currcode'];
		$_SESSION['Items'.$identifier]->DefaultPOLine = $myrow['customerpoline'];
		$_SESSION['Items'.$identifier]->PaymentTerms = $myrow['terms'];
		$_SESSION['Items'.$identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];

# the branch was also selected from the customer selection so default the delivery details from the customer branches table CustBranch. The order process will ask for branch details later anyway
		$result = GetCustBranchDetails($identifier);

		if (DB_num_rows($result)==0){

			prnMsg(_('The branch details for branch code') . ': ' . $_SESSION['Items'.$identifier]->Branch . ' ' . _('against customer code') . ': ' . $_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('could not be retrieved') . '. ' . _('Check the set up of the customer and branch'),'error');

			if ($debug==1){
				prnMsg( _('The SQL that failed to get the branch details was') . ':<br />' . $sql . 'warning');
			}
			include('includes/footer.php');
			exit;
		}
		// add echo
		echo '<br />';
		$myrow = DB_fetch_array($result);
		if ($_SESSION['SalesmanLogin']!=NULL AND $_SESSION['SalesmanLogin']!=$myrow['salesman']){
			prnMsg(_('Your login is only set up for a particular salesperson. This customer has a different salesperson.'),'error');
			include('includes/footer.php');
			exit;
		}
		$_SESSION['Items'.$identifier]->DeliverTo = $myrow['brname'];
		$_SESSION['Items'.$identifier]->DelAdd1 = $myrow['braddress1'];
		$_SESSION['Items'.$identifier]->DelAdd2 = $myrow['braddress2'];
		$_SESSION['Items'.$identifier]->DelAdd3 = $myrow['braddress3'];
		$_SESSION['Items'.$identifier]->DelAdd4 = $myrow['braddress4'];
		$_SESSION['Items'.$identifier]->DelAdd5 = $myrow['braddress5'];
		$_SESSION['Items'.$identifier]->DelAdd6 = $myrow['braddress6'];
		$_SESSION['Items'.$identifier]->PhoneNo = $myrow['phoneno'];
		$_SESSION['Items'.$identifier]->Email = $myrow['email'];
		$_SESSION['Items'.$identifier]->Location = $myrow['defaultlocation'];
		$_SESSION['Items'.$identifier]->ShipVia = $myrow['defaultshipvia'];
		$_SESSION['Items'.$identifier]->DeliverBlind = $myrow['deliverblind'];
		$_SESSION['Items'.$identifier]->SpecialInstructions = $myrow['specialinstructions'];
		$_SESSION['Items'.$identifier]->DeliveryDays = $myrow['estdeliverydays'];
		$_SESSION['Items'.$identifier]->LocationName = $myrow['locationname'];
		if ($_SESSION['SalesmanLogin']!= NULL AND $_SESSION['SalesmanLogin']!=''){
			$_SESSION['Items'.$identifier]->SalesPerson = $_SESSION['SalesmanLogin'];
		} else {
			$_SESSION['Items'.$identifier]->SalesPerson = $myrow['salesman'];
		}
		if ($_SESSION['Items'.$identifier]->SpecialInstructions)
		  prnMsg($_SESSION['Items'.$identifier]->SpecialInstructions,'warn');

		if ($_SESSION['CheckCreditLimits'] > 0){  /*Check credit limits is 1 for warn and 2 for prohibit sales */
			$_SESSION['Items'.$identifier]->CreditAvailable = GetCreditAvailable($_SESSION['Items'.$identifier]->DebtorNo);

			if ($_SESSION['CheckCreditLimits']==1 AND $_SESSION['Items'.$identifier]->CreditAvailable <=0){
				prnMsg(_('The') . ' ' . htmlspecialchars($myrow[0], ENT_QUOTES, 'UTF-8', false) . ' ' . _('account is currently at or over their credit limit'),'warn');
			} elseif ($_SESSION['CheckCreditLimits']==2 AND $_SESSION['Items'.$identifier]->CreditAvailable <=0){
				prnMsg(_('No more orders can be placed by') . ' ' . htmlspecialchars($myrow[0], ENT_QUOTES, 'UTF-8', false) . ' ' . _(' their account is currently at or over their credit limit'),'warn');
				include('includes/footer.php');
				exit;
			}
		}

	} else {
		prnMsg(_('The') . ' ' . htmlspecialchars($myrow[0], ENT_QUOTES, 'UTF-8', false) . ' ' . _('account is currently on hold please contact the credit control personnel to discuss'),'warn');
	}

} elseif (!$_SESSION['Items'.$identifier]->DefaultSalesType
			OR $_SESSION['Items'.$identifier]->DefaultSalesType=='')	{

#Possible that the check to ensure this account is not on hold has not been done
#if the customer is placing own order, if this is the case then
#DefaultSalesType will not have been set as above

	$sql = "SELECT debtorsmaster.name,
					holdreasons.dissallowinvoices,
					debtorsmaster.salestype,
					debtorsmaster.currcode,
					currencies.decimalplaces,
					debtorsmaster.customerpoline
			FROM debtorsmaster
			INNER JOIN holdreasons
			ON debtorsmaster.holdreason=holdreasons.reasoncode
			INNER JOIN currencies
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE debtorsmaster.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo . "'";

	$ErrMsg = _('The details for the customer selected') . ': ' .$_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('cannot be retrieved because');
	$DbgMsg = _('SQL used to retrieve the customer details was') . ':<br />' . $sql;
	$result =DB_query($sql,$ErrMsg,$DbgMsg);

	$myrow = DB_fetch_array($result);
	if ($myrow[1] == 0){

		$_SESSION['Items'.$identifier]->CustomerName = $myrow[0];

# the sales type determines the price list to be used by default the customer of the user is
# defaulted from the entry of the userid and password.

		$_SESSION['Items'.$identifier]->DefaultSalesType = $myrow['salestype'];
		$_SESSION['Items'.$identifier]->DefaultCurrency = $myrow['currcode'];
		$_SESSION['Items'.$identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];
		$_SESSION['Items'.$identifier]->Branch = $_SESSION['UserBranch'];
		$_SESSION['Items'.$identifier]->DefaultPOLine = $myrow['customerpoline'];

	// the branch would be set in the user data so default delivery details as necessary. However,
	// the order process will ask for branch details later anyway

		$result = GetCustBranchDetails($identifier);
		$myrow = DB_fetch_array($result);
		$_SESSION['Items'.$identifier]->DeliverTo = $myrow['brname'];
		$_SESSION['Items'.$identifier]->DelAdd1 = $myrow['braddress1'];
		$_SESSION['Items'.$identifier]->DelAdd2 = $myrow['braddress2'];
		$_SESSION['Items'.$identifier]->DelAdd3 = $myrow['braddress3'];
		$_SESSION['Items'.$identifier]->DelAdd4 = $myrow['braddress4'];
		$_SESSION['Items'.$identifier]->DelAdd5 = $myrow['braddress5'];
		$_SESSION['Items'.$identifier]->DelAdd6 = $myrow['braddress6'];
		$_SESSION['Items'.$identifier]->PhoneNo = $myrow['phoneno'];
		$_SESSION['Items'.$identifier]->Email = $myrow['email'];
		$_SESSION['Items'.$identifier]->Location = $myrow['defaultlocation'];
		$_SESSION['Items'.$identifier]->DeliverBlind = $myrow['deliverblind'];
		$_SESSION['Items'.$identifier]->DeliveryDays = $myrow['estdeliverydays'];
		$_SESSION['Items'.$identifier]->LocationName = $myrow['locationname'];
		if ($_SESSION['SalesmanLogin']!= NULL AND $_SESSION['SalesmanLogin']!=''){
			$_SESSION['Items'.$identifier]->SalesPerson = $_SESSION['SalesmanLogin'];
		} else {
			$_SESSION['Items'.$identifier]->SalesPerson = $myrow['salesman'];
		}
	} else {
		prnMsg(_('Sorry, your account has been put on hold for some reason, please contact the credit control personnel.'),'warn');
		include('includes/footer.php');
		exit;
	}
}

if ($_SESSION['RequireCustomerSelection'] ==1
	OR !isset($_SESSION['Items'.$identifier]->DebtorNo)
	OR $_SESSION['Items'.$identifier]->DebtorNo=='') {

	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' .
	' ' . _('Enter an Order or Quotation') . ' : ' . _('Search for the Customer Branch.') . '</p>';
	echo '<div class="page_help_text">' . _('Orders/Quotations are placed against the Customer Branch. A Customer may have several Branches.') . '</div>';
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier . '" method="post">
		 <div>
			 <input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
			 <table cellpadding="3" class="selection">
				<tr>
				<td>' . _('Part of the Customer Branch Name') . ':</td>
				<td><input type="text" autofocus="autofocus" name="CustKeywords" size="20" maxlength="25" title="' . _('Enter a text extract of the customer\'s name, then click Search Now to find customers matching the entered name') . '" /></td>
				<td><b>' . _('OR') . '</b></td>
				<td>' . _('Part of the Customer Branch Code') . ':</td>
				<td><input type="text" name="CustCode" size="15" maxlength="18" title="' . _('Enter a part of a customer code that you wish to search for then click the Search Now button to find matching customers') . '" /></td>
				<td><b>' . _('OR') . '</b></td>
				<td>' . _('Part of the Branch Phone Number') . ':</td>
				<td><input type="text" name="CustPhone" size="15" maxlength="18" title="' . _('Enter a part of a customer\'s phone number that you wish to search for then click the Search Now button to find matching customers') . '"/></td>
				</tr>

			</table>

			<div class="centre">
				<input type="submit" name="SearchCust" value="' . _('Search Now') . '" />
				<input type="submit" name="reset" value="' .  _('Reset') . '" />
			</div>
		</div>';

	if (isset($result_CustSelect)) {

        echo '<div>
					<input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
					<input name="JustSelectedACustomer" type="hidden" value="Yes" />
					<br />
			<table class="selection">
			<thead>
				<tr>
				<th class="ascending" >' . _('Customer') . '</th>
				<th class="ascending" >' . _('Branch') . '</th>
				<th class="ascending" >' . _('Contact') . '</th>
				<th>' . _('Phone') . '</th>
				<th>' . _('Fax') . '</th>
				</tr>
			</thead>
			<tbody>';

		$j = 1;
		$LastCustomer='';
		while ($myrow=DB_fetch_array($result_CustSelect)) {

			echo '<tr class="striped_row">
					<td>' . htmlspecialchars($myrow['name'], ENT_QUOTES, 'UTF-8', false) . '</td>
					<td><input type="submit" name="SubmitCustomerSelection' . $j .'" value="' . htmlspecialchars($myrow['brname'], ENT_QUOTES, 'UTF-8', false). '" />
					<input name="SelectedCustomer' . $j .'" type="hidden" value="'.$myrow['debtorno'].'" />
					<input name="SelectedBranch' . $j .'" type="hidden" value="'. $myrow['branchcode'].'" /></td>
					<td>' . $myrow['contactname'] . '</td>
					<td>' . $myrow['phoneno'] . '</td>
					<td>' . $myrow['faxno'] . '</td>
				</tr>';
			$LastCustomer=$myrow['name'];
			$j++;
		}
//end of while loop
		echo '</tbody>
			</table>
			</div>';
	}//end if results to show
	echo '</form>';
//end if RequireCustomerSelection
} else { //dont require customer selection
// everything below here only do if a customer is selected

 	if (isset($_POST['CancelOrder'])) {
		$OK_to_delete=1;	//assume this in the first instance

		if($_SESSION['ExistingOrder' . $identifier]!=0) { //need to check that not already dispatched

			$sql = "SELECT qtyinvoiced
					FROM salesorderdetails
					WHERE orderno='" . $_SESSION['ExistingOrder' . $identifier] . "'
					AND qtyinvoiced>0";

			$InvQties = DB_query($sql);

			if (DB_num_rows($InvQties)>0){

				$OK_to_delete=0;

				prnMsg( _('There are lines on this order that have already been invoiced. Please delete only the lines on the order that are no longer required') . '<p>' . _('There is an option on confirming a dispatch/invoice to automatically cancel any balance on the order at the time of invoicing if you know the customer will not want the back order'),'warn');
			}
		}

		if ($OK_to_delete==1){
			if($_SESSION['ExistingOrder' . $identifier]!=0){

				$SQL = "DELETE FROM salesorderdetails WHERE salesorderdetails.orderno ='" . $_SESSION['ExistingOrder' . $identifier] . "'";
				$ErrMsg =_('The order detail lines could not be deleted because');
				$DelResult=DB_query($SQL,$ErrMsg);

				$SQL = "DELETE FROM salesorders WHERE salesorders.orderno='" . $_SESSION['ExistingOrder' . $identifier] . "'";
				$ErrMsg = _('The order header could not be deleted because');
				$DelResult=DB_query($SQL,$ErrMsg);

				$_SESSION['ExistingOrder' . $identifier]=0;
			}

			unset($_SESSION['Items'.$identifier]->LineItems);
			$_SESSION['Items'.$identifier]->ItemsOrdered=0;
			unset($_SESSION['Items'.$identifier]);
			$_SESSION['Items'.$identifier] = new cart;

			if (in_array($_SESSION['PageSecurityArray']['ConfirmDispatch_Invoice.php'], $_SESSION['AllowedPageSecurityTokens'])){
				$_SESSION['RequireCustomerSelection'] = 1;
			} else {
				$_SESSION['RequireCustomerSelection'] = 0;
			}
			echo '<br /><br />';
			prnMsg(_('This sales order has been cancelled as requested'),'success');
			include('includes/footer.php');
			exit;
		}
	} else { /*Not cancelling the order */

		echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Order') . '" alt="" />' . ' ';

		if ($_SESSION['Items'.$identifier]->Quotation==1){
			echo _('Quotation for customer') . ' ';
		} else {
			echo _('Order for customer') . ' ';
		}

		echo ':<b> ' . $_SESSION['Items'.$identifier]->DebtorNo  . ' ' . _('Customer Name') . ': ' . htmlspecialchars($_SESSION['Items'.$identifier]->CustomerName, ENT_QUOTES, 'UTF-8', false);
		echo '</b></p><div class="page_help_text">' . '<b>' . _('Default Options (can be modified during order):') . '</b><br />' . _('Deliver To') . ':<b> ' . htmlspecialchars($_SESSION['Items'.$identifier]->DeliverTo, ENT_QUOTES, 'UTF-8', false);
		echo '</b>&nbsp;' . _('From Location') . ':<b> ' . $_SESSION['Items'.$identifier]->LocationName;
		echo '</b><br />' . _('Sales Type') . '/' . _('Price List') . ':<b> ' . $_SESSION['Items'.$identifier]->SalesTypeName;
		echo '</b><br />' . _('Terms') . ':<b> ' . $_SESSION['Items'.$identifier]->PaymentTerms;
		echo '</b></div>';
	}
	$msg ='';
	if (isset($_POST['Search']) OR isset($_POST['Next']) OR isset($_POST['Previous'])){
		if(!empty($_POST['RawMaterialFlag'])){
			$RawMaterialSellable = " OR stockcategory.stocktype='M'";
		}else{
			$RawMaterialSellable = '';
		}
		if(!empty($_POST['CustItemFlag'])){
			$IncludeCustItem = " INNER JOIN custitem ON custitem.stockid=stockmaster.stockid
								AND custitem.debtorno='" .  $_SESSION['Items'.$identifier]->DebtorNo . "' ";
		} else {
			$IncludeCustItem = " LEFT OUTER JOIN custitem ON custitem.stockid=stockmaster.stockid
								AND custitem.debtorno='" .  $_SESSION['Items'.$identifier]->DebtorNo . "' ";
		}

		if ($_POST['Keywords']!='' AND $_POST['StockCode']=='') {
			$msg='<div class="page_help_text">' . _('Order Item description has been used in search') . '.</div>';
		} elseif ($_POST['StockCode']!='' AND $_POST['Keywords']=='') {
			$msg='<div class="page_help_text">' . _('Stock Code has been used in search') . '.</div>';
		} elseif ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
			$msg='<div class="page_help_text">' . _('Stock Category has been used in search') . '.</div>';
		}
		$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						custitem.cust_part,
						custitem.cust_description
				FROM stockmaster INNER JOIN stockcategory
				ON stockmaster.categoryid=stockcategory.categoryid
				" . $IncludeCustItem . "
				WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D' OR stockcategory.stocktype='L' " . $RawMaterialSellable . ")
				AND stockmaster.mbflag <>'G'
				AND stockmaster.discontinued=0 ";

		if (isset($_POST['Keywords']) AND mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			if ($_POST['StockCat']=='All'){
				$SQL .= "AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					ORDER BY stockmaster.stockid";
			} else {
				$SQL .= "AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
			}

		} elseif (mb_strlen($_POST['StockCode'])>0){

			$_POST['StockCode'] = mb_strtoupper($_POST['StockCode']);
			$SearchString = '%' . $_POST['StockCode'] . '%';

			if ($_POST['StockCat']=='All'){
				$SQL .= "AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					ORDER BY stockmaster.stockid";
			} else {
				$SQL .= "AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					 AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					 ORDER BY stockmaster.stockid";
			}

		} else {
			if ($_POST['StockCat']=='All'){
				$SQL .= "ORDER BY stockmaster.stockid";
			} else {
				$SQL .= "AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					 ORDER BY stockmaster.stockid";
			  }
		}

		if (isset($_POST['Next'])) {
			$Offset = $_POST['NextList'];
		}
		if (isset($_POST['Previous'])) {
			$Offset = $_POST['PreviousList'];
		}
		if (!isset($Offset) OR $Offset < 0) {
			$Offset=0;
		}

		$SQL = $SQL . " LIMIT " . $_SESSION['DisplayRecordsMax'] . " OFFSET " . strval($_SESSION['DisplayRecordsMax'] * $Offset);

		$ErrMsg = _('There is a problem selecting the part records to display because');
		$DbgMsg = _('The SQL used to get the part selection was');

		$SearchResult = DB_query($SQL,$ErrMsg, $DbgMsg);

		if (DB_num_rows($SearchResult)==0 ){
			prnMsg (_('There are no products available meeting the criteria specified'),'info');
		}
		if (DB_num_rows($SearchResult)==1){
			$myrow=DB_fetch_array($SearchResult);
			$NewItem = $myrow['stockid'];
			DB_data_seek($SearchResult,0);
		}
		if (DB_num_rows($SearchResult) < $_SESSION['DisplayRecordsMax']){
			$Offset=0;
		}
	} //end of if search

#Always do the stuff below if not looking for a customerid

	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier . '" id="SelectParts" method="post" enctype="multipart/form-data">';
    echo '<div>';
	echo '<input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />';

//Get The exchange rate used for GPPercent calculations on adding or amending items
	if ($_SESSION['Items'.$identifier]->DefaultCurrency != $_SESSION['CompanyRecord']['currencydefault']){
		$ExRateResult = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['Items'.$identifier]->DefaultCurrency . "'");
		if (DB_num_rows($ExRateResult)>0){
			$ExRateRow = DB_fetch_row($ExRateResult);
			$ExRate = $ExRateRow[0];
		} else {
			$ExRate =1;
		}
	} else {
		$ExRate = 1;
	}


	/*Process Quick Entry */
	/* If enter is pressed on the quick entry screen, the default button may be Recalculate */
	 if (isset($_POST['SelectingOrderItems'])
			OR isset($_POST['QuickEntry'])
			OR isset($_POST['Recalculate'])){

		 /* get the item details from the database and hold them in the cart object */

		 /*Discount can only be set later on  -- after quick entry -- so default discount to 0 in the first place */
		$Discount = 0;
		$AlreadyWarnedAboutCredit = false;
		 $i=1;
		  while ($i<=$_SESSION['QuickEntries'] AND isset($_POST['part_' . $i]) AND $_POST['part_' . $i]!='') {
			$QuickEntryCode = 'part_' . $i;
			$QuickEntryQty = 'qty_' . $i;
			$QuickEntryPOLine = 'poline_' . $i;
			$QuickEntryItemDue = 'itemdue_' . $i;

			$i++;

			if (isset($_POST[$QuickEntryCode])) {
				$NewItem = mb_strtoupper($_POST[$QuickEntryCode]);
			}
			if (isset($_POST[$QuickEntryQty])) {
				$NewItemQty = filter_number_format($_POST[$QuickEntryQty]);
			}
			if (isset($_POST[$QuickEntryItemDue])) {
				$NewItemDue = $_POST[$QuickEntryItemDue];
			} else {
				$NewItemDue = DateAdd (Date($_SESSION['DefaultDateFormat']),'d', $_SESSION['Items'.$identifier]->DeliveryDays);
			}
			if (isset($_POST[$QuickEntryPOLine])) {
				$NewPOLine = $_POST[$QuickEntryPOLine];
			} else {
				$NewPOLine = 0;
			}

			if (!isset($NewItem)){
				unset($NewItem);
				break;	/* break out of the loop if nothing in the quick entry fields*/
			}

			if(!Is_Date($NewItemDue)) {
				prnMsg(_('An invalid date entry was made for ') . ' ' . $NewItem . ' ' . _('The date entry') . ' ' . $NewItemDue . ' ' . _('must be in the format') . ' ' . $_SESSION['DefaultDateFormat'],'warn');
				//Attempt to default the due date to something sensible?
				$NewItemDue = DateAdd (Date($_SESSION['DefaultDateFormat']),'d', $_SESSION['Items'.$identifier]->DeliveryDays);
			}
			/*Now figure out if the item is a kit set - the field MBFlag='K'*/
			$sql = "SELECT stockmaster.mbflag
					FROM stockmaster
					WHERE stockmaster.stockid='". $NewItem ."'";

			$ErrMsg = _('Could not determine if the part being ordered was a kitset or not because');
			$DbgMsg = _('The sql that was used to determine if the part being ordered was a kitset or not was ');
			$KitResult = DB_query($sql,$ErrMsg,$DbgMsg);


			if (DB_num_rows($KitResult)==0){
				prnMsg( _('The item code') . ' ' . $NewItem . ' ' . _('could not be retrieved from the database and has not been added to the order'),'warn');
			} elseif ($myrow=DB_fetch_array($KitResult)){
				if ($myrow['mbflag']=='K'){	/*It is a kit set item */
					$sql = "SELECT bom.component,
							bom.quantity
							FROM bom
							WHERE bom.parent='" . $NewItem . "'
                            AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                            AND bom.effectiveto > '" . date('Y-m-d') . "'";

					$ErrMsg =  _('Could not retrieve kitset components from the database because') . ' ';
					$KitResult = DB_query($sql,$ErrMsg,$DbgMsg);

					$ParentQty = $NewItemQty;
					while ($KitParts = DB_fetch_array($KitResult)){
						$NewItem = $KitParts['component'];
						$NewItemQty = $KitParts['quantity'] * $ParentQty;
						$NewPOLine = 0;
						include('includes/SelectOrderItems_IntoCart.inc');
					}

				} elseif ($myrow['mbflag']=='G'){
					prnMsg(_('Phantom assemblies cannot be sold, these items exist only as bills of materials used in other manufactured items. The following item has not been added to the order:') . ' ' . $NewItem, 'warn');
				} else { /*Its not a kit set item*/
					include('includes/SelectOrderItems_IntoCart.inc');
				}
			}
		 }
		 unset($NewItem);
	 } /* end of if quick entry */

	if (isset($_POST['AssetDisposalEntered'])){ //its an asset being disposed of
		if ($_POST['AssetToDisposeOf'] == 'NoAssetSelected'){ //don't do anything unless an asset is disposed of
			prnMsg(_('No asset was selected to dispose of. No assets have been added to this customer order'),'warn');
		} else { //need to add the asset to the order
			/*First need to create a stock ID to hold the asset and record the sale - as only stock items can be sold
			 * 		and before that we need to add a disposal stock category - if not already created
			 * 		first off get the details about the asset being disposed of */
			 $AssetDetailsResult = DB_query("SELECT  fixedassets.description,
													fixedassets.longdescription,
													fixedassets.barcode,
													fixedassetcategories.costact,
													fixedassets.cost-fixedassets.accumdepn AS nbv
											FROM fixedassetcategories INNER JOIN fixedassets
											ON fixedassetcategories.categoryid=fixedassets.assetcategoryid
											WHERE fixedassets.assetid='" . $_POST['AssetToDisposeOf'] . "'");
			$AssetRow = DB_fetch_array($AssetDetailsResult);

			/* Check that the stock category for disposal "ASSETS" is defined already */
			$AssetCategoryResult = DB_query("SELECT categoryid FROM stockcategory WHERE categoryid='ASSETS'");
			if (DB_num_rows($AssetCategoryResult)==0){
				/*Although asset GL posting will come from the asset category - we should set the GL codes to something sensible
				 * based on the category of the asset under review at the moment - this may well change for any other assets sold subsequentely */

				/*OK now we can insert the stock category for this asset */
				$InsertAssetStockCatResult = DB_query("INSERT INTO stockcategory ( categoryid,
																				categorydescription,
																				stockact)
														VALUES ('ASSETS',
																'" . _('Asset Disposals') . "',
																'" . $AssetRow['costact'] . "')");
			}

			/*First check to see that it doesn't exist already assets are of the format "ASSET-" . $AssetID
			 */
			 $TestAssetExistsAlreadyResult = DB_query("SELECT stockid
														FROM stockmaster
														WHERE stockid ='ASSET-" . $_POST['AssetToDisposeOf']  . "'");
			 $j=0;
			while (DB_num_rows($TestAssetExistsAlreadyResult)==1) { //then it exists already ... bum
				$j++;
				$TestAssetExistsAlreadyResult = DB_query("SELECT stockid
														FROM stockmaster
														WHERE stockid ='ASSET-" . $_POST['AssetToDisposeOf']  . '-' . $j . "'");
			}
			if ($j>0){
				$AssetStockID = 'ASSET-' . $_POST['AssetToDisposeOf']  . '-' . $j;
			} else {
				$AssetStockID = 'ASSET-' . $_POST['AssetToDisposeOf'];
			}
			if ($AssetRow['nbv']==0){
				$NBV = 0.001; /* stock must have a cost to be invoiced if the flag is set so set to 0.001 */
			} else {
				$NBV = $AssetRow['nbv'];
			}
			/*OK now we can insert the item for this asset */
			$InsertAssetAsStockItemResult = DB_query("INSERT INTO stockmaster ( stockid,
																				description,
																				longdescription,
																				categoryid,
																				mbflag,
																				controlled,
																				serialised,
																				taxcatid,
																				materialcost)
										VALUES ('" . $AssetStockID . "',
												'" . DB_escape_string($AssetRow['description']) . "',
												'" . DB_escape_string($AssetRow['longdescription']) . "',
												'ASSETS',
												'D',
												'0',
												'0',
												'" . $_SESSION['DefaultTaxCategory'] . "',
												'". $NBV . "')");
			/*not forgetting the location records too */
			$InsertStkLocRecsResult = DB_query("INSERT INTO locstock (loccode,
																	stockid)
												SELECT loccode, '" . $AssetStockID . "'
												FROM locations");
			/*Now the asset has been added to the stock master we can add it to the sales order */
			$NewItemDue = date($_SESSION['DefaultDateFormat']);
			if (isset($_POST['POLine'])){
				$NewPOLine = $_POST['POLine'];
			} else {
				$NewPOLine = 0;
			}
			$NewItem = $AssetStockID;
			include('includes/SelectOrderItems_IntoCart.inc');
		} //end if adding a fixed asset to the order
	} //end if the fixed asset selection box was set

	 /*Now do non-quick entry delete/edits/adds */

	if ((isset($_SESSION['Items'.$identifier])) OR isset($NewItem)){

		if(isset($_GET['Delete'])){
			//page called attempting to delete a line - GET['Delete'] = the line number to delete
			$QuantityAlreadyDelivered = $_SESSION['Items'.$identifier]->Some_Already_Delivered($_GET['Delete']);
			if($QuantityAlreadyDelivered == 0){
				$_SESSION['Items'.$identifier]->remove_from_cart($_GET['Delete'], 'Yes', $identifier);  /*Do update DB */
			} else {
				$_SESSION['Items'.$identifier]->LineItems[$_GET['Delete']]->Quantity = $QuantityAlreadyDelivered;
			}
		}

		$AlreadyWarnedAboutCredit = false;

		foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine) {

			if (isset($_POST['Quantity_' . $OrderLine->LineNumber])){

				$Quantity = round(filter_number_format($_POST['Quantity_' . $OrderLine->LineNumber]),$OrderLine->DecimalPlaces);

				if (ABS($OrderLine->Price - filter_number_format($_POST['Price_' . $OrderLine->LineNumber]))>0.01){
					/*There is a new price being input for the line item */
					$Price = filter_number_format($_POST['Price_' . $OrderLine->LineNumber]);
					if (isset($_POST['Discount_' . $OrderLine->LineNumber]) AND is_numeric(filter_number_format($_POST['Discount_' . $OrderLine->LineNumber]))) {
							if ($_POST['Discount_' . $OrderLine->LineNumber] < 100) {//to avoid divided by zero error
								$_POST['GPPercent_' . $OrderLine->LineNumber] = (($Price*(1-(filter_number_format($_POST['Discount_' . $OrderLine->LineNumber])/100))) - $OrderLine->StandardCost*$ExRate)/($Price *(1-filter_number_format($_POST['Discount_' . $OrderLine->LineNumber])/100)/100);
							} else {
								$_POST['GPPercent_' . $OrderLine->LineNumber] = 0;
							}
					} else {
							$_POST['GPPercent_' . $OrderLine->LineNumber] = ($Price - $OrderLine->StandardCost*$ExRate)*100/$Price;
					}


				} elseif (isset($_POST['GPPercent_'.$OrderLine->LineNumber]) AND ABS($OrderLine->GPPercent - filter_number_format($_POST['GPPercent_' . $OrderLine->LineNumber]))>=0.01) {
					/* A GP % has been input so need to do a recalculation of the price at this new GP Percentage */


					prnMsg(_('Recalculated the price from the GP % entered - the GP % was') . ' ' . $OrderLine->GPPercent . '  the new GP % is ' . filter_number_format($_POST['GPPercent_' . $OrderLine->LineNumber]),'info');


					$Price = ($OrderLine->StandardCost*$ExRate)/(1 -((filter_number_format($_POST['GPPercent_' . $OrderLine->LineNumber]) + filter_number_format($_POST['Discount_' . $OrderLine->LineNumber]))/100));
				} else {
					$Price = filter_number_format($_POST['Price_' . $OrderLine->LineNumber]);
					if (isset($_POST['Discount_' . $OrderLine->LineNumber]) AND is_numeric(filter_number_format($_POST['Discount_' . $OrderLine->LineNumber]))) {
							if ($_POST['Discount_' . $OrderLine->LineNumber] < 100) {//to avoid divided by zero error
								$_POST['GPPercent_' . $OrderLine->LineNumber] = (($Price*(1-(filter_number_format($_POST['Discount_' . $OrderLine->LineNumber])/100))) - $OrderLine->StandardCost*$ExRate)/($Price *(1-filter_number_format($_POST['Discount_' . $OrderLine->LineNumber])/100)/100);
							} else {
								$_POST['GPPercent_' . $OrderLine->LineNumber] = 0;
							}
					} else {
							$_POST['GPPercent_' . $OrderLine->LineNumber] = ($Price - $OrderLine->StandardCost*$ExRate)*100/$Price;
					}
				}
				$DiscountPercentage = isset($_POST['Discount_' . $OrderLine->LineNumber])?filter_number_format($_POST['Discount_' . $OrderLine->LineNumber]):0;
				if ($_SESSION['AllowOrderLineItemNarrative'] == 1) {
					$Narrative = $_POST['Narrative_' . $OrderLine->LineNumber];
				} else {
					$Narrative = '';
				}

				if (!isset($OrderLine->DiscountPercent)) {
					$OrderLine->DiscountPercent = 0;
				}

				if(!Is_Date($_POST['ItemDue_' . $OrderLine->LineNumber])) {
					prnMsg(_('An invalid date entry was made for ') . ' ' . $NewItem . ' ' . _('The date entry') . ' ' . $ItemDue . ' ' . _('must be in the format') . ' ' . $_SESSION['DefaultDateFormat'],'warn');
					//Attempt to default the due date to something sensible?
					$_POST['ItemDue_' . $OrderLine->LineNumber] = DateAdd (Date($_SESSION['DefaultDateFormat']),'d', $_SESSION['Items'.$identifier]->DeliveryDays);
				}
				if ($Quantity<0 OR $Price <0 OR $DiscountPercentage >100 OR $DiscountPercentage <0){
					prnMsg(_('The item could not be updated because you are attempting to set the quantity ordered to less than 0 or the price less than 0 or the discount more than 100% or less than 0%'),'warn');
				} elseif($_SESSION['Items'.$identifier]->Some_Already_Delivered($OrderLine->LineNumber)!=0 AND $_SESSION['Items'.$identifier]->LineItems[$OrderLine->LineNumber]->Price != $Price) {
					prnMsg(_('The item you attempting to modify the price for has already had some quantity invoiced at the old price the items unit price cannot be modified retrospectively'),'warn');
				} elseif($_SESSION['Items'.$identifier]->Some_Already_Delivered($OrderLine->LineNumber)!=0 AND $_SESSION['Items'.$identifier]->LineItems[$OrderLine->LineNumber]->DiscountPercent != ($DiscountPercentage/100)) {

					prnMsg(_('The item you attempting to modify has had some quantity invoiced at the old discount percent the items discount cannot be modified retrospectively'),'warn');

				} elseif ($_SESSION['Items'.$identifier]->LineItems[$OrderLine->LineNumber]->QtyInv > $Quantity){
					prnMsg( _('You are attempting to make the quantity ordered a quantity less than has already been invoiced') . '. ' . _('The quantity delivered and invoiced cannot be modified retrospectively'),'warn');
				} elseif ($OrderLine->Quantity !=$Quantity
							OR $OrderLine->Price != $Price
							OR ABS($OrderLine->DiscountPercent - $DiscountPercentage/100) >0.001
							OR $OrderLine->Narrative != $Narrative
							OR $OrderLine->ItemDue != $_POST['ItemDue_' . $OrderLine->LineNumber]
							OR $OrderLine->POLine != $_POST['POLine_' . $OrderLine->LineNumber]) {

					$WithinCreditLimit = true;

					if ($_SESSION['CheckCreditLimits'] > 0 AND $AlreadyWarnedAboutCredit==false){
						/*Check credit limits is 1 for warn breach their credit limit and 2 for prohibit sales */
						$DifferenceInOrderValue = ($Quantity*$Price*(1-$DiscountPercentage/100)) - ($OrderLine->Quantity*$OrderLine->Price*(1-$OrderLine->DiscountPercent));
						$_SESSION['Items'.$identifier]->CreditAvailable -= $DifferenceInOrderValue;

						if ($_SESSION['CheckCreditLimits']==1 AND $_SESSION['Items'.$identifier]->CreditAvailable <=0){
							prnMsg(_('The customer account will breach their credit limit'),'warn');
							$AlreadyWarnedAboutCredit = true;
						} elseif ($_SESSION['CheckCreditLimits']==2 AND $_SESSION['Items'.$identifier]->CreditAvailable <=0){
							prnMsg(_('This change would put the customer over their credit limit and is prohibited'),'warn');
							$WithinCreditLimit = false;
							$_SESSION['Items'.$identifier]->CreditAvailable += $DifferenceInOrderValue;
							$AlreadyWarnedAboutCredit = true;
						}
					}
					/* The database data will be updated at this step, it will make big mistake if users do not know this and change the quantity to zero, unfortuately, the appearance shows that this change not allowed but the sales order details' quantity has been changed to zero in database. Must to filter this out! A zero quantity order line means nothing */
					if ($WithinCreditLimit AND $Quantity >0){
						$_SESSION['Items'.$identifier]->update_cart_item($OrderLine->LineNumber,
																		$Quantity,
																		$Price,
																		($DiscountPercentage/100),
																		$Narrative,
																		'Yes', /*Update DB */
																		$_POST['ItemDue_' . $OrderLine->LineNumber],
																		$_POST['POLine_' . $OrderLine->LineNumber],
																		filter_number_format($_POST['GPPercent_' . $OrderLine->LineNumber]),
																		$identifier);
					} //within credit limit so make changes
				} //there are changes to the order line to process
			} //page not called from itself - POST variables not set
		} // Loop around all items on the order


		/* Now Run through each line of the order again to work out the appropriate discount from the discount matrix */
		$DiscCatsDone = array();
		foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine) {

			if ($OrderLine->DiscCat !='' AND ! in_array($OrderLine->DiscCat,$DiscCatsDone)){
				$DiscCatsDone[]=$OrderLine->DiscCat;
				$QuantityOfDiscCat = 0;

				foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine_2) {
					/* add up total quantity of all lines of this DiscCat */
					if ($OrderLine_2->DiscCat==$OrderLine->DiscCat){
						$QuantityOfDiscCat += $OrderLine_2->Quantity;
					}
				}
				$result = DB_query("SELECT MAX(discountrate) AS discount
									FROM discountmatrix
									WHERE salestype='" .  $_SESSION['Items'.$identifier]->DefaultSalesType . "'
									AND discountcategory ='" . $OrderLine->DiscCat . "'
									AND quantitybreak <= '" . $QuantityOfDiscCat ."'");
				$myrow = DB_fetch_row($result);
				if ($myrow[0]==NULL){
					$DiscountMatrixRate = 0;
				} else {
					$DiscountMatrixRate = $myrow[0];
				}
				if ($DiscountMatrixRate!=0){ /* need to update the lines affected */
					foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine_2) {
						if ($OrderLine_2->DiscCat==$OrderLine->DiscCat){
							$_SESSION['Items'.$identifier]->LineItems[$OrderLine_2->LineNumber]->DiscountPercent = $DiscountMatrixRate;
							$_SESSION['Items'.$identifier]->LineItems[$OrderLine_2->LineNumber]->GPPercent = (($_SESSION['Items'.$identifier]->LineItems[$OrderLine_2->LineNumber]->Price*(1-$DiscountMatrixRate)) - $_SESSION['Items'.$identifier]->LineItems[$OrderLine_2->LineNumber]->StandardCost*$ExRate)/($_SESSION['Items'.$identifier]->LineItems[$OrderLine_2->LineNumber]->Price *(1-$DiscountMatrixRate)/100);
						}
					}
				}
			}
		} /* end of discount matrix lookup code */
	} // the order session is started or there is a new item being added
	if (isset($_POST['DeliveryDetails'])){
		echo '<meta http-equiv="refresh" content="0; url=' . $RootPath . '/DeliveryDetails.php?identifier='.$identifier . '">';
		prnMsg(_('You should automatically be forwarded to the entry of the delivery details page') . '. ' . _('if this does not happen') . ' (' . _('if the browser does not support META Refresh') . ') ' .
		   '<a href="' . $RootPath . '/DeliveryDetails.php?identifier='.$identifier . '">' . _('click here') . '</a> ' . _('to continue'), 'info');
	   	exit;
	}


	if (isset($NewItem)){
/* get the item details from the database and hold them in the cart object make the quantity 1 by default then add it to the cart */
/*Now figure out if the item is a kit set - the field MBFlag='K'*/
		$sql = "SELECT stockmaster.mbflag
		   		FROM stockmaster
				WHERE stockmaster.stockid='". $NewItem ."'";

		$ErrMsg =  _('Could not determine if the part being ordered was a kitset or not because');

		$KitResult = DB_query($sql,$ErrMsg);

		$NewItemQty = 1; /*By Default */
		$Discount = 0; /*By default - can change later or discount category override */

		if ($myrow=DB_fetch_array($KitResult)){
		   	if ($myrow['mbflag']=='K'){	/*It is a kit set item */
				$sql = "SELECT bom.component,
							bom.quantity
						FROM bom
						WHERE bom.parent='" . $NewItem . "'
                        AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                        AND bom.effectiveto > '" . date('Y-m-d') . "'";

				$ErrMsg = _('Could not retrieve kitset components from the database because');
				$KitResult = DB_query($sql,$ErrMsg);

				$ParentQty = $NewItemQty;
				while ($KitParts = DB_fetch_array($KitResult)){
					$NewItem = $KitParts['component'];
					$NewItemQty = $KitParts['quantity'] * $ParentQty;
					$NewPOLine = 0;
					$NewItemDue = date($_SESSION['DefaultDateFormat']);
					include('includes/SelectOrderItems_IntoCart.inc');
				}

			} else { /*Its not a kit set item*/
				$NewItemDue = date($_SESSION['DefaultDateFormat']);
				$NewPOLine = 0;

				include('includes/SelectOrderItems_IntoCart.inc');
			}

		} /* end of if its a new item */

	} /*end of if its a new item */

	if (isset($NewItemArray) AND isset($_POST['SelectingOrderItems'])){
/* get the item details from the database and hold them in the cart object make the quantity 1 by default then add it to the cart */
/*Now figure out if the item is a kit set - the field MBFlag='K'*/
		$AlreadyWarnedAboutCredit = false;
		foreach($NewItemArray as $NewItem => $NewItemQty) {
			if($NewItemQty > 0)	{
				$sql = "SELECT stockmaster.mbflag
						FROM stockmaster
						WHERE stockmaster.stockid='". $NewItem ."'";

				$ErrMsg =  _('Could not determine if the part being ordered was a kitset or not because');

				$KitResult = DB_query($sql,$ErrMsg);

				//$NewItemQty = 1; /*By Default */
				$Discount = 0; /*By default - can change later or discount category override */

				if ($myrow=DB_fetch_array($KitResult)){
					if ($myrow['mbflag']=='K'){	/*It is a kit set item */
						$sql = "SELECT bom.component,
										bom.quantity
								FROM bom
								WHERE bom.parent='" . $NewItem . "'
                                AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                                AND bom.effectiveto > '" . date('Y-m-d') . "'";

						$ErrMsg = _('Could not retrieve kitset components from the database because');
						$KitResult = DB_query($sql,$ErrMsg);

						$ParentQty = $NewItemQty;
						while ($KitParts = DB_fetch_array($KitResult)){
							$NewItem = $KitParts['component'];
							$NewItemQty = $KitParts['quantity'] * $ParentQty;
							$NewItemDue = date($_SESSION['DefaultDateFormat']);
							$NewPOLine = 0;
							include('includes/SelectOrderItems_IntoCart.inc');
						}

					} else { /*Its not a kit set item*/
						$NewItemDue = date($_SESSION['DefaultDateFormat']);
						$NewPOLine = 0;
						include('includes/SelectOrderItems_IntoCart.inc');
					}
				} /* end of if its a new item */
			} /*end of if its a new item */
		}/* loop through NewItem array */
	} /* if the NewItem_array is set */

	/* Run through each line of the order and work out the appropriate discount from the discount matrix */
	$DiscCatsDone = array();
	$counter =0;
	foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine) {

		if ($OrderLine->DiscCat !="" AND ! in_array($OrderLine->DiscCat,$DiscCatsDone)){
			$DiscCatsDone[$counter]=$OrderLine->DiscCat;
			$QuantityOfDiscCat =0;

			foreach ($_SESSION['Items'.$identifier]->LineItems as $StkItems_2) {
				/* add up total quantity of all lines of this DiscCat */
				if ($StkItems_2->DiscCat==$OrderLine->DiscCat){
					$QuantityOfDiscCat += $StkItems_2->Quantity;
				}
			}
			$result = DB_query("SELECT MAX(discountrate) AS discount
								FROM discountmatrix
								WHERE salestype='" .  $_SESSION['Items'.$identifier]->DefaultSalesType . "'
								AND discountcategory ='" . $OrderLine->DiscCat . "'
								AND quantitybreak <= '" . $QuantityOfDiscCat . "'");
			$myrow = DB_fetch_row($result);
			if ($myrow[0] == NULL){
				$DiscountMatrixRate = 0;
			} else {
				$DiscountMatrixRate = $myrow[0];
			}
			if ($DiscountMatrixRate != 0) {
				foreach ($_SESSION['Items'.$identifier]->LineItems as $StkItems_2) {
					if ($StkItems_2->DiscCat==$OrderLine->DiscCat){
						$_SESSION['Items'.$identifier]->LineItems[$StkItems_2->LineNumber]->DiscountPercent = $DiscountMatrixRate;
					}
				}
			}
		}
	} /* end of discount matrix lookup code */

	if (count($_SESSION['Items'.$identifier]->LineItems)>0){ /*only show order lines if there are any */

/* This is where the order as selected should be displayed  reflecting any deletions or insertions*/

	 	if($_SESSION['Items'.$identifier]->DefaultPOLine ==1) {// Does customer require PO Line number by sales order line?
			$ShowPOLine=1;// Show one additional column:  'PO Line'.
		} else {
			$ShowPOLine=0;// Do NOT show 'PO Line'.
		}

		if(in_array($_SESSION['PageSecurityArray']['OrderEntryDiscountPricing'], $_SESSION['AllowedPageSecurityTokens'])) {//Is it an internal user with appropriate permissions?
			$ShowDiscountGP=2;// Show two additional columns: 'Discount' and 'GP %'.
		} else {
			$ShowDiscountGP=0;// Do NOT show 'Discount' and 'GP %'.
		}

        echo '<div class="page_help_text">' . _('Quantity (required) - Enter the number of units ordered.  Price (required) - Enter the unit price.  Discount (optional) - Enter a percentage discount.  GP% (optional) - Enter a percentage Gross Profit (GP) to add to the unit cost.  Due Date (optional) - Enter a date for delivery.') . '</div><br />';
		echo '<br />
				<table width="90%" cellpadding="2">
				<tr class="tableheader">';
/*		if($_SESSION['Items'.$identifier]->DefaultPOLine == 1){*/
		if($ShowPOLine) {
			echo '<th>' . _('PO Line') . '</th>';
		}
		echo '<th>' . _('Item Code') . '</th>
				<th>' . _('Item Description') . '</th>
				<th>' . _('Quantity') . '</th>
				<th>' . _('QOH') . '</th>
				<th>' . _('Unit') . '</th>
				<th>' . _('Price') . '</th>';

/*		if (in_array($_SESSION['PageSecurityArray']['OrderEntryDiscountPricing'], $_SESSION['AllowedPageSecurityTokens'])){*/
		if($ShowDiscountGP) {
			echo '<th>' . _('Discount') . '</th>
					<th>' . _('GP %') . '</th>';
		}
		echo '<th>' . _('Total') . '</th>
			<th>' . _('Due Date') . '</th>
			<th>&nbsp;</th></tr>';

		$_SESSION['Items'.$identifier]->total = 0;
		$_SESSION['Items'.$identifier]->totalVolume = 0;
		$_SESSION['Items'.$identifier]->totalWeight = 0;

		foreach ($_SESSION['Items'.$identifier]->LineItems as $OrderLine) {

			$LineTotal = $OrderLine->Quantity * $OrderLine->Price * (1 - $OrderLine->DiscountPercent);
			$DisplayLineTotal = locale_number_format($LineTotal,$_SESSION['Items'.$identifier]->CurrDecimalPlaces);
			$DisplayDiscount = locale_number_format(($OrderLine->DiscountPercent * 100),2);
			$QtyOrdered = $OrderLine->Quantity;
			$QtyRemain = $QtyOrdered - $OrderLine->QtyInv;

			if ($OrderLine->QOHatLoc < $OrderLine->Quantity AND ($OrderLine->MBflag=='B' OR $OrderLine->MBflag=='M')) {
				/*There is a stock deficiency in the stock location selected */
				$RowStarter = '<tr style="background-color:#EEAABB">'; //rows show red where stock deficiency
			} else {
				$RowStarter = '<tr class="striped_row">';
			}

			echo $RowStarter;
            echo '<td>';
/*			if($_SESSION['Items'.$identifier]->DefaultPOLine ==1){ //show the input field only if required*/
			if($ShowPOLine) {// Show the input field only if required.
				echo '<input maxlength="20" name="POLine_' . $OrderLine->LineNumber . '" size="20" title="' . _('Enter the customer\'s purchase order reference if required by the customer') . '" type="text" value="' . $OrderLine->POLine . '" /></td><td>';
			} else {
				echo '<input name="POLine_' . $OrderLine->LineNumber . '" type="hidden" value="" />';
			}

			echo '<a href="' . $RootPath . '/StockStatus.php?identifier='.$identifier . '&amp;StockID=' . $OrderLine->StockID . '&amp;DebtorNo=' . $_SESSION['Items'.$identifier]->DebtorNo . '" target="_blank">' . $OrderLine->StockID . '</a></td>
				<td title="' . $OrderLine->LongDescription . '">' . $OrderLine->ItemDescription . '</td>';

			echo '<td><input class="number" maxlength="8" name="Quantity_' . $OrderLine->LineNumber . '" required="required" size="6" title="' . _('Enter the quantity of this item ordered by the customer') . '" type="text" value="' . locale_number_format($OrderLine->Quantity,$OrderLine->DecimalPlaces) . '" />';
			if ($QtyRemain != $QtyOrdered){
				echo '<br />' . locale_number_format($OrderLine->QtyInv,$OrderLine->DecimalPlaces) .' ' . _('of') . ' ' . locale_number_format($OrderLine->Quantity,$OrderLine->DecimalPlaces).' ' . _('invoiced');
			}
			echo '</td>
					<td class="number">' . locale_number_format($OrderLine->QOHatLoc,$OrderLine->DecimalPlaces) . '</td>
					<td>' . $OrderLine->Units . '</td>';

			/*OK to display with discount if it is an internal user with appropriate permissions */
/*			if (in_array($_SESSION['PageSecurityArray']['OrderEntryDiscountPricing'], $_SESSION['AllowedPageSecurityTokens'])){*/
			if ($ShowDiscountGP){
				echo '<td><input class="number" maxlength="16" name="Price_' . $OrderLine->LineNumber . '" required="required" size="16" title="' . _('Enter the price to charge the customer for this item') . '" type="text" value="' . locale_number_format($OrderLine->Price,$_SESSION['Items'.$identifier]->CurrDecimalPlaces)  . '" /></td>
					<td><input class="number" maxlength="4" name="Discount_' . $OrderLine->LineNumber . '" required="required" size="5" title="' . _('Enter the discount percentage to apply to the price for this item') . '" type="text" value="' . locale_number_format(($OrderLine->DiscountPercent * 100),2) . '" /></td>
					<td><input class="number" maxlength="40" name="GPPercent_' . $OrderLine->LineNumber . '" required="required" size="4" value="' . locale_number_format($OrderLine->GPPercent,2) . '" title="' . _('Enter a gross profit percentage to use as the basis to calculate the price to charge the customer for this line item') . '" type="text" /></td>';
			} else {
				echo '<td class="number">' . locale_number_format($OrderLine->Price,$_SESSION['Items'.$identifier]->CurrDecimalPlaces);
				echo '<input name="Price_' . $OrderLine->LineNumber . '" type="hidden" value="' . locale_number_format($OrderLine->Price,$_SESSION['Items'.$identifier]->CurrDecimalPlaces) . '" /></td>';
				echo '<input class="number" maxlength="4" name="Discount_' . $OrderLine->LineNumber . '" required="required" size="5" title="' . _('Enter the discount percentage to apply to the price for this item') . '" type="hidden" value="' . locale_number_format(($OrderLine->DiscountPercent * 100),2) . '" />';
				echo '<input class="number" maxlength="40" name="GPPercent_' . $OrderLine->LineNumber . '" required="required" size="4" value="' . locale_number_format($OrderLine->GPPercent,2) . '" title="' . _('Enter a gross profit percentage to use as the basis to calculate the price to charge the customer for this line item') . '" type="hidden" />';
			}

			if ($_SESSION['Items'.$identifier]->Some_Already_Delivered($OrderLine->LineNumber)){
				$RemTxt = _('Clear Remaining');
			} else {
				$RemTxt = _('Delete');
			}
			echo '<td class="number">' . $DisplayLineTotal . '</td>';
			$LineDueDate = $OrderLine->ItemDue;
			if (!Is_Date($OrderLine->ItemDue)){
				$LineDueDate = DateAdd (Date($_SESSION['DefaultDateFormat']),'d', $_SESSION['Items'.$identifier]->DeliveryDays);
				$_SESSION['Items'.$identifier]->LineItems[$OrderLine->LineNumber]->ItemDue= $LineDueDate;
			}

			echo '<td><input class="date" maxlength="10" name="ItemDue_' . $OrderLine->LineNumber . '" size="10" type="text" value="' . $LineDueDate . '" /></td>';

			echo '<td rowspan="2"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier=' . $identifier . '&amp;Delete=' . $OrderLine->LineNumber . '" onclick="return confirm(\'' . _('Are You Sure?') . '\');">' . $RemTxt . '</a></td></tr>';

			if ($_SESSION['AllowOrderLineItemNarrative'] == 1){
				$varColSpan=8+$ShowPOLine+$ShowDiscountGP;
				echo $RowStarter .
						'<td colspan="' . $varColSpan . '">' . _('Narrative') . ':<textarea name="Narrative_' . $OrderLine->LineNumber . '" cols="100%" rows="1" title="' . _('Enter any narrative to describe to the customer the nature of the charge for this line') . '" >' . stripslashes(AddCarriageReturns($OrderLine->Narrative)) . '</textarea><br /></td>
					</tr>';
			} else {
				echo '<tr>
						<td><input name="Narrative" type="hidden" value="" /></td>
					</tr>';
			}

			$_SESSION['Items'.$identifier]->total = $_SESSION['Items'.$identifier]->total + $LineTotal;
			$_SESSION['Items'.$identifier]->totalVolume = $_SESSION['Items'.$identifier]->totalVolume + $OrderLine->Quantity * $OrderLine->Volume;
			$_SESSION['Items'.$identifier]->totalWeight = $_SESSION['Items'.$identifier]->totalWeight + $OrderLine->Quantity * $OrderLine->Weight;

		} /* end of loop around items */

		$DisplayTotal = locale_number_format($_SESSION['Items'.$identifier]->total,$_SESSION['Items'.$identifier]->CurrDecimalPlaces);
/*		if (in_array($_SESSION['PageSecurityArray']['OrderEntryDiscountPricing'], $_SESSION['AllowedPageSecurityTokens'])){
			$ColSpanNumber = 2;
		} else {
			$ColSpanNumber = 1;
		}*/
		$varColSpan=1+$ShowPOLine+$ShowDiscountGP;
		echo '<tr class="striped_row">
				<td class="number" colspan="6"><b>' . _('TOTAL Excl Tax/Freight') . '</b></td>
				<td colspan="' . $varColSpan . '" class="number"><b>' . $DisplayTotal . '</b></td>
			</tr>
			</table>';

		$DisplayVolume = locale_number_format($_SESSION['Items'.$identifier]->totalVolume,2);
		$DisplayWeight = locale_number_format($_SESSION['Items'.$identifier]->totalWeight,2);
		echo '<table>
					<tr class="striped_row"><td>' . _('Total Weight') . ':</td>
						 <td>' . $DisplayWeight . '</td>
						 <td>' . _('Total Volume') . ':</td>
						 <td>' . $DisplayVolume . '</td>
					</tr>
				</table>
				<br />
				<div class="centre">
					<input type="submit" name="Recalculate" value="' . _('Re-Calculate') . '" />
					<input type="submit" name="DeliveryDetails" value="' . _('Enter Delivery Details and Confirm Order') . '" />
				</div>
				<br />';
	} # end of if lines

/* Now show the stock item selection search stuff below */

	 if ((!isset($_POST['QuickEntry'])
			AND !isset($_POST['SelectAsset']))){

		echo '<input name="PartSearch" type="hidden" value="' .  _('Yes Please') . '" />';

		if ($_SESSION['FrequentlyOrderedItems']>0){ //show the Frequently Order Items selection where configured to do so

// Select the most recently ordered items for quick select
			$SixMonthsAgo = DateAdd (Date($_SESSION['DefaultDateFormat']),'m',-6);

			$SQL="SELECT stockmaster.units,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.stockid,
						salesorderdetails.stkcode,
						SUM(qtyinvoiced) salesqty
					FROM `salesorderdetails`INNER JOIN `stockmaster`
					ON  salesorderdetails.stkcode = stockmaster.stockid
					WHERE ActualDispatchDate >= '" . FormatDateForSQL($SixMonthsAgo) . "'
					GROUP BY stkcode
					ORDER BY salesqty DESC
					LIMIT " . $_SESSION['FrequentlyOrderedItems'];

			$result2 = DB_query($SQL);
			echo '<p class="page_title_text">
					<img src="'.$RootPath.'/css/'.$Theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' .
					' ' . _('Frequently Ordered Items') .
					'</p>
					<br />
					<div class="page_help_text">' . _('Frequently Ordered Items') . _(', shows the most frequently ordered items in the last 6 months.  You can choose from this list, or search further for other items') .
					'.</div>
					<br />
					<table class="table1">
					<thead>
					<tr>
						<th class="ascending" >' . _('Code') . '</th>
						<th class="ascending" >' . _('Description') . '</th>
						<th>' . _('Units') . '</th>
						<th class="ascending" >' . _('On Hand') . '</th>
						<th class="ascending" >' . _('On Demand') . '</th>
						<th class="ascending" >' . _('On Order') . '</th>
						<th class="ascending" >' . _('Available') . '</th>
						<th class="ascending" >' . _('Quantity') . '</th>
						</tr>
					</thead>
					<tbody>';
			$i=0;
			$j=1;

			while ($myrow=DB_fetch_array($result2)) {
// This code needs sorting out, but until then :
				$ImageSource = _('No Image');
// Find the quantity in stock at location
				$QOHSQL = "SELECT sum(locstock.quantity) AS qoh,decimalplaces
							FROM locstock INNER JOIN stockmaster ON stockmaster.stockid=locstock.stockid
							WHERE locstock.stockid='" .$myrow['stockid'] . "'
							AND loccode = '" . $_SESSION['Items'.$identifier]->Location . "'";
				$QOHResult =  DB_query($QOHSQL);
				$QOHRow = DB_fetch_array($QOHResult);
				$QOH = $QOHRow['qoh'];

				// Find the quantity on outstanding sales orders
				$sql = "SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
						FROM salesorderdetails INNER JOIN salesorders
						ON salesorders.orderno = salesorderdetails.orderno
						WHERE salesorders.fromstkloc='" . $_SESSION['Items'.$identifier]->Location . "'
						AND salesorderdetails.completed=0
						AND salesorders.quotation=0
						AND salesorderdetails.stkcode='" . $myrow['stockid'] . "'";

				$ErrMsg = _('The demand for this product from') . ' ' . $_SESSION['Items'.$identifier]->Location . ' ' .
					 _('cannot be retrieved because');
				$DemandResult = DB_query($sql,$ErrMsg);

				$DemandRow = DB_fetch_row($DemandResult);
				if ($DemandRow[0] != null){
				  $DemandQty =  $DemandRow[0];
				} else {
				  $DemandQty = 0;
				}
				// Get the QOO due to Purchase orders for all locations. Function defined in SQL_CommonFunctions.inc
				$PurchQty = GetQuantityOnOrderDueToPurchaseOrders($myrow['stockid'], '');
				// Get the QOO dues to Work Orders for all locations. Function defined in SQL_CommonFunctions.inc
				$WoQty = GetQuantityOnOrderDueToWorkOrders($myrow['stockid'], '');

				$OnOrder = $PurchQty + $WoQty;

				$Available = $QOH - $DemandQty + $OnOrder;

				printf('<tr class="striped_row">
						<td>%s</td>
						<td title="%s">%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td><input class="number" ' . ($j==0 ? 'autofocus="autofocus"':'') . ' type="text" required="required" size="6" name="OrderQty%s" value="0" />
						<input name="StockID%s" type="hidden" value="%s" />
						</td>
						</tr>',
						$myrow['stockid'],
						$myrow['longdescription'],
						$myrow['description'],
						$myrow['units'],
						locale_number_format($QOH, $QOHRow['decimalplaces']),
						locale_number_format($DemandQty, $QOHRow['decimalplaces']),
						locale_number_format($OnOrder, $QOHRow['decimalplaces']),
						locale_number_format($Available, $QOHRow['decimalplaces']),
						$j,
						$j,
						$myrow['stockid']);
				$j++;
				$i++;
#end of page full new headings if
			}
#end of while loop for Frequently Ordered Items
			echo '</tbody>
				<tr>
					<td class="centre" colspan="8"><input name="SelectingOrderItems" type="hidden" value="1" /><input type="submit" value="'._('Add to Sales Order').'" /></td>
				</tr>
				</table>';
		} //end of if Frequently Ordered Items > 0
		echo '<br /><div class="centre">' . $msg;
		echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ';
		echo _('Search for Order Items') . '</p></div>';
		echo '<div class="page_help_text">' . _('Search for Order Items') . _(', Searches the database for items, you can narrow the results by selecting a stock category, or just enter a partial item description or partial item code') . '.</div><br />';
		echo '<table class="selection">
				<tr>
					<td><b>' . _('Select a Stock Category') . ': </b><select name="StockCat">';

		if (!isset($_POST['StockCat']) OR $_POST['StockCat']=='All'){
			echo '<option selected="selected" value="All">' . _('All') . '</option>';
			$_POST['StockCat'] = 'All';
		} else {
			echo '<option value="All">' . _('All') . '</option>';
		}
		$SQL="SELECT categoryid,
						categorydescription
				FROM stockcategory
				WHERE stocktype='F' OR stocktype='D' OR stocktype='L'
				ORDER BY categorydescription";

		$result1 = DB_query($SQL);
		while ($myrow1 = DB_fetch_array($result1)) {
			if ($_POST['StockCat']==$myrow1['categoryid']){
				echo '<option selected="selected" value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
			} else {
				echo '<option value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
			}
		}

		echo '</select></td>
			<td><b>' . _('Enter partial Description') . ':</b><input type="text" name="Keywords" size="20" maxlength="25" value="' ;

        if (isset($_POST['Keywords'])) {
             echo $_POST['Keywords'] ;
        }
        echo '" /></td>';

		echo '<td align="right"><b>' . _('OR') .  ' ' . _('Enter extract of the Stock Code') . ':</b><input type="text" ' . (!isset($_POST['PartSearch']) ? 'autofocus="autofocus"' :'') . ' name="StockCode" size="15" maxlength="18" value="';
        if (isset($_POST['StockCode'])) {
            echo  $_POST['StockCode'];
        }
	echo '" /></td>

		<td><input type="checkbox" name="RawMaterialFlag" value="M" />'._('Raw material flag').'&nbsp;&nbsp;<br/><span class="dpTbl">'._('If checked, Raw material will be shown on search result').'</span> </td>
		<td><input type="checkbox" name="CustItemFlag" value="C" />'._('Customer Item flag').'&nbsp;&nbsp;<br/><span class="dpTbl">'._('If checked, only items for this customer will show').'</span> </td>
			</tr>';

		echo '<tr>
			<td class="centre" colspan="1"><input type="submit" name="Search" value="' . _('Search Now') . '" /></td>
			<td class="centre" colspan="1"><input type="submit" name="QuickEntry" value="' .  _('Use Quick Entry') . '" /></td>';

		if (in_array($_SESSION['PageSecurityArray']['ConfirmDispatch_Invoice.php'], $_SESSION['AllowedPageSecurityTokens'])){ //not a customer entry of own order
			echo '<td class="centre" colspan="1"><input type="submit" name="ChangeCustomer" value="' . _('Change Customer') . '" /></td>
			<td class="centre" colspan="1"><input type="submit" name="SelectAsset" value="' . _('Fixed Asset Disposal') . '" /></td>';
		}
		echo '<tr>
				<td colspan="10">
					<div class="centre">
						<h2>' . _('Or') . '</h2>
						' . _('Upload items from csv file') . '<input type="file" name="CSVFile" />
						<input type="submit" name="UploadFile" value="' . _('Upload File') . '" />
					</div>
				</td>
			</tr>
			</table>
			<br />
			</div>';
		echo '<div class="page_help_text">' . _('The csv file should have exactly 2 columns, part code and quantity.') . '</div>';
		if (isset($SearchResult)) {
			echo '<br />';
			echo '<div class="page_help_text">' . _('Select an item by entering the quantity required.  Click Order when ready.') . '</div>';
			echo '<br />';

            echo '<div>';
			echo '<input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />';
			echo '<table class="table1">';
			echo '<thead>
					<tr>
					<td colspan="1"><input name="PreviousList" type="hidden" value="'.strval($Offset-1).'" /><input type="submit" name="Previous" value="'._('Previous').'" /></td>
					<td class="centre" colspan="6"><input name="SelectingOrderItems" type="hidden" value="1" /><input type="submit" value="'._('Add to Sales Order').'" /></td>
					<td colspan="1"><input name="NextList" type="hidden" value="'.strval($Offset+1).'" /><input name="Next" type="submit" value="'._('Next').'" /></td>
					</tr>
					<tr>
					<th class="ascending" >' . _('Code') . '</th>
		   			<th class="ascending" >' . _('Description') . '</th>
					<th class="ascending" >' . _('Customer Item') . '</th>
		   			<th>' . _('Units') . '</th>
		   			<th class="ascending" >' . _('On Hand') . '</th>
		   			<th class="ascending" >' . _('On Demand') . '</th>
		   			<th class="ascending" >' . _('On Order') . '</th>
		   			<th class="ascending" >' . _('Available') . '</th>
		   			<th>' . _('Quantity') . '</th>
					</tr>
				</thead>
				<tbody>';
			$ImageSource = _('No Image');
			$i=0;

			while ($myrow=DB_fetch_array($SearchResult)) {

				// Find the quantity in stock at location
				$QOHSQL = "SELECT quantity AS qoh,
									stockmaster.decimalplaces
							   FROM locstock INNER JOIN stockmaster
							   ON locstock.stockid = stockmaster.stockid
							   WHERE locstock.stockid='" .$myrow['stockid'] . "' AND
							   loccode = '" . $_SESSION['Items'.$identifier]->Location . "'";
				$QOHResult =  DB_query($QOHSQL);
				$QOHRow = DB_fetch_array($QOHResult);
				$QOH = $QOHRow['qoh'];

				// Find the quantity on outstanding sales orders
				$sql = "SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
						FROM salesorderdetails INNER JOIN salesorders
						ON salesorders.orderno = salesorderdetails.orderno
						 WHERE  salesorders.fromstkloc='" . $_SESSION['Items'.$identifier]->Location . "'
						 AND salesorderdetails.completed=0
						 AND salesorders.quotation=0
						 AND salesorderdetails.stkcode='" . $myrow['stockid'] . "'";

				$ErrMsg = _('The demand for this product from') . ' ' . $_SESSION['Items'.$identifier]->Location . ' ' . _('cannot be retrieved because');
				$DemandResult = DB_query($sql,$ErrMsg);

				$DemandRow = DB_fetch_row($DemandResult);
				if ($DemandRow[0] != null){
					$DemandQty =  $DemandRow[0];
				} else {
					$DemandQty = 0;
				}

				// Get the QOO due to Purchase orders for all locations. Function defined in SQL_CommonFunctions.inc
				$PurchQty = GetQuantityOnOrderDueToPurchaseOrders($myrow['stockid'], '');
				// Get the QOO dues to Work Orders for all locations. Function defined in SQL_CommonFunctions.inc
				$WoQty = GetQuantityOnOrderDueToWorkOrders($myrow['stockid'], '');

				$OnOrder = $PurchQty + $WoQty;
				$Available = $QOH - $DemandQty + $OnOrder;

				printf('<tr class="striped_row">
						<td>%s</td>
						<td title="%s">%s</td>
						<td>%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td><input class="number" type="text" size="6" name="OrderQty%s"  ' . ($i==0 ? 'autofocus="autofocus"':'') . ' value="0" min="0"/>
						<input name="StockID%s" type="hidden" value="%s" />
						</td>
						</tr>',
						$myrow['stockid'],
						$myrow['longdescription'],
						$myrow['description'],
						$myrow['cust_part'] . '-' . $myrow['cust_description'],
						$myrow['units'],
						locale_number_format($QOH,$QOHRow['decimalplaces']),
						locale_number_format($DemandQty,$QOHRow['decimalplaces']),
						locale_number_format($OnOrder,$QOHRow['decimalplaces']),
						locale_number_format($Available,$QOHRow['decimalplaces']),
						$j,
						$j,
						$myrow['stockid'] );
				$i++;
				$j++;
	#end of page full new headings if
			}
	#end of while loop
			echo '</tbody>';
			echo '<tfoot>
					<tr>
					<td><input name="PreviousList" type="hidden" value="'. strval($Offset-1).'" /><input type="submit" name="Previous" value="'._('Previous').'" /></td>
					<td class="centre" colspan="6"><input name="SelectingOrderItems" type="hidden" value="1" /><input type="submit" value="'._('Add to Sales Order').'" /></td>
					<td><input name="NextList" type="hidden" value="'.strval($Offset+1).'" /><input name="Next" type="submit" value="'._('Next').'" /></td>
					</tr>
				</tfoot>
				</table>';

		}#end if SearchResults to show
	} /*end of PartSearch options to be displayed */
	   elseif( isset($_POST['QuickEntry'])) { /* show the quick entry form variable */
		  /*FORM VARIABLES TO POST TO THE ORDER  WITH PART CODE AND QUANTITY */
	   	echo '<div class="page_help_text"><b>' . _('Use this screen for the '). _('Quick Entry')._(' of products to be ordered') . '</b></div><br />
		 			<table class="selection">
					<tr>';
			/*do not display colum unless customer requires po line number by sales order line*/
		 	if($_SESSION['Items'.$identifier]->DefaultPOLine ==1){
				echo	'<th>' . _('PO Line') . '</th>';
			}
			echo '<th>' . _('Part Code') . '</th>
				  <th>' . _('Quantity') . '</th>
				  <th>' . _('Due Date') . '</th>
				  </tr>';
			$DefaultDeliveryDate = DateAdd(Date($_SESSION['DefaultDateFormat']),'d',$_SESSION['Items'.$identifier]->DeliveryDays);
			for ($i=1;$i<=$_SESSION['QuickEntries'];$i++){

		 		echo '<tr class="OddTableRow">';
		 		/* Do not display colum unless customer requires po line number by sales order line*/
		 		if($_SESSION['Items'.$identifier]->DefaultPOLine > 0){
					echo '<td><input type="text" name="poline_' . $i . '" size="21" maxlength="20" title="' . _('Enter the customer purchase order reference') . '" /></td>';
				}
				echo '<td><input type="text" name="part_' . $i . '" size="21" maxlength="20" title="' . _('Enter the item code ordered') . '" /></td>
						<td><input class="number" type="text" name="qty_' . $i . '" size="6" maxlength="6" title="' . _('Enter the quantity of the item ordered by the customer') . '" /></td>
						<td><input type="text" class="date" name="itemdue_' . $i . '" size="25" maxlength="25"
                        value="' . $DefaultDeliveryDate . '" title="' . _('Enter the date that the customer requires delivery by') . '" /></td>
                      </tr>';
	   		}
			echo '</table>
					<br />
					<div class="centre">
						<input type="submit" name="QuickEntry" value="' . _('Quick Entry') . '" />
						<input type="submit" name="PartSearch" value="' . _('Search Parts') . '" />
					</div>';
	  	} elseif (isset($_POST['SelectAsset'])){

			echo '<div class="page_help_text"><b>' . _('Use this screen to select an asset to dispose of to this customer') . '</b></div>
					<br />
		 			<table border="1">';
			/*do not display colum unless customer requires po line number by sales order line*/
		 	if($_SESSION['Items'.$identifier]->DefaultPOLine ==1){
				echo	'<tr>
							<td>' . _('PO Line') . '</td>
							<td><input type="text" name="poline" size="21" maxlength="20" title="' . _('Enter the customer\'s purchase order reference') . '" /></td>
						</tr>';
			}
			echo '<tr>
					<td>' . _('Asset to Dispose Of') . ':</td>
					<td><select name="AssetToDisposeOf">';
			$AssetsResult = DB_query("SELECT assetid, description FROM fixedassets WHERE disposaldate='0000-00-00'");
			echo '<option selected="selected" value="NoAssetSelected">' . _('Select Asset To Dispose of From the List Below') . '</option>';
			while ($AssetRow = DB_fetch_array($AssetsResult)){
				echo '<option value="' . $AssetRow['assetid'] . '">' . $AssetRow['assetid'] . ' - ' . $AssetRow['description'] . '</option>';
			}
			echo '</select></td>
				</tr>
				</table>
				<br />
				<div class="centre">
					<input type="submit" name="AssetDisposalEntered" value="' . _('Add Asset To Order') . '" />
					<input type="submit" name="PartSearch" value="' . _('Search Parts') . '" />
			</div>';

		} //end of if it is a Quick Entry screen/part search or asset selection form to display

	echo '</div></form>';

	/* Know that the closing tag pair above was extracted from three mutually
	 * exclusive functions above:
	 *
	 *      if ((!isset($_POST['QuickEntry'])
	 *                AND !isset($_POST['SelectAsset']))){
	 *      }
	 *      else if( isset($_POST['QuickEntry'])) {
	 *      } elseif (isset($_POST['SelectAsset'])){
	 *
	 * To have them in ONE place to ease tag matching.
	 * As of Feb 20, 2018 there are three forms in this file.
	 */

		if ($_SESSION['Items'.$identifier]->ItemsOrdered >=1){
		echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier . '" method="post" name="deleteform">
			<div>
			<input name="FormID" type="hidden" value="' . $_SESSION['FormID'] . '" />
				<br />
				<div class="centre">
					<input name="CancelOrder" type="submit" value="' . _('Cancel Whole Order') . '" onclick="return confirm(\'' . _('Are you sure you wish to cancel this entire order?') . '\');" />
				</div>
                </div>
				</form>';
		}
}#end of else not selecting a customer

include('includes/footer.php');

function GetCustBranchDetails($identifier) {
		$sql = "SELECT custbranch.brname,
						custbranch.branchcode,
						custbranch.braddress1,
						custbranch.braddress2,
						custbranch.braddress3,
						custbranch.braddress4,
						custbranch.braddress5,
						custbranch.braddress6,
						custbranch.phoneno,
						custbranch.email,
						custbranch.defaultlocation,
						custbranch.defaultshipvia,
						custbranch.deliverblind,
						custbranch.specialinstructions,
						custbranch.estdeliverydays,
						locations.locationname,
						custbranch.salesman
					FROM custbranch
					INNER JOIN locations
					ON custbranch.defaultlocation=locations.loccode
					WHERE custbranch.branchcode='" . $_SESSION['Items'.$identifier]->Branch . "'
					AND custbranch.debtorno = '" . $_SESSION['Items'.$identifier]->DebtorNo . "'";

		$ErrMsg = _('The customer branch record of the customer selected') . ': ' . $_SESSION['Items'.$identifier]->DebtorNo . ' ' . _('cannot be retrieved because');
		$DbgMsg = _('SQL used to retrieve the branch details was') . ':';
		$result = DB_query($sql,$ErrMsg,$DbgMsg);
		return $result;
}
?>

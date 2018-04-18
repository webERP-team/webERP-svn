<?php


// InsertSalesOrderHeader and ModifySalesOrderHeader have date fields
// which need to be converted to the appropriate format.  This is
// a list of such fields used to detect date values and format appropriately.
$SOH_DateFields = array ('orddate',
						'deliverydate',
						'datepackingslipprinted',
						'quotedate',
						'confirmeddate' );

/* Check that the custmerref field is 50 characters or less long */
	function VerifyCustomerRef($customerref, $i, $Errors) {
		if (mb_strlen($customerref)>50) {
			$Errors[$i] = InvalidCustomerRef;
		}
		return $Errors;
	}

/* Check that the buyername field is 50 characters or less long */
	function VerifyBuyerName($buyername, $i, $Errors) {
		if (mb_strlen($buyername)>50) {
			$Errors[$i] = InvalidBuyerName;
		}
		return $Errors;
	}

/* Check that the comments field is 256 characters or less long */
	function VerifyComments($comments, $i, $Errors) {
		if (mb_strlen($comments)>256) {
			$Errors[$i] = InvalidComments;
		}
		return $Errors;
	}

/* Check that the order date is a valid date. The date
 * must be in the same format as the date format specified in the
 * target webERP company */
	function VerifyOrderDate($orddate, $i, $Errors) {
		$sql="SELECT confvalue FROM config WHERE confname='DefaultDateFormat'";
		$result=api_DB_query($sql);
		$myrow=DB_fetch_array($result);
		$DateFormat=$myrow[0];
		if (mb_strstr($orddate,"/")) {
			$DateArray = explode('/',$orddate);
		} elseif (mb_strstr($orddate,".")) {
			$DateArray = explode('.',$orddate);
		}
		if ($DateFormat=='d/m/Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='m/d/Y') {
			$Day=$DateArray[1];
			$Month=$DateArray[0];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='Y/m/d') {
			$Day=$DateArray[2];
			$Month=$DateArray[1];
			$Year=$DateArray[0];
		} elseif ($DateFormat=='d.m.Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		}
		if (!checkdate(intval($Month), intval($Day), intval($Year))) {
			$Errors[$i] = InvalidOrderDate;
		}
		return $Errors;
	}

/* Check that the order type is set up in the weberp database */
	function VerifyOrderType($ordertype, $i, $Errors) {
		$Searchsql = "SELECT COUNT(typeabbrev)
					 FROM salestypes
					 WHERE typeabbrev='" . $ordertype."'";
		$SearchResult=api_DB_query($Searchsql);
		$answer = DB_fetch_row($SearchResult);
		if ($answer[0] == 0) {
			$Errors[$i] = SalesTypeNotSetup;
		}
		return $Errors;
	}

/* Check that the delivery name field is 40 characters or less long */
	function VerifyDeliverTo($delverto, $i, $Errors) {
		if (mb_strlen($delverto)>40) {
			$Errors[$i] = InvalidDeliverTo;
		}
		return $Errors;
	}

/* Verify that the last freight cost is numeric */
	function VerifyFreightCost($freightcost, $i, $Errors) {
		if (!is_numeric($freightcost)) {
			$Errors[$i] = InvalidFreightCost;
		}
		return $Errors;
	}

/* Check that the from stock location is set up in the weberp database */
	function VerifyFromStockLocation($FromStockLocn, $i, $Errors) {
		$Searchsql = "SELECT COUNT(loccode)
					 FROM locations
					  WHERE loccode='". $FromStockLocn."'";
		$SearchResult=api_DB_query($Searchsql);
		$answer = DB_fetch_row($SearchResult);
		if ($answer[0] == 0) {
			$Errors[$i] = LocationCodeNotSetup;
		}
		return $Errors;
	}

/* Check that the delivery date is a valid date. The date
 * must be in the same format as the date format specified in the
 * target webERP company */
	function VerifyDeliveryDate($DeliveryDate, $i, $Errors) {
		$sql="SELECT confvalue FROM config WHERE confname='DefaultDateFormat'";
		$result=api_DB_query($sql);
		$myrow=DB_fetch_array($result);
		$DateFormat=$myrow[0];
		if (mb_strstr($DeliveryDate,'/')) {
			$DateArray = explode('/',$DeliveryDate);
		} elseif (mb_strstr($PeriodEnd,'.')) {
			$DateArray = explode('.',$DeliveryDate);
		}
		if ($DateFormat=='d/m/Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='m/d/Y') {
			$Day=$DateArray[1];
			$Month=$DateArray[0];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='Y/m/d') {
			$Day=$DateArray[2];
			$Month=$DateArray[1];
			$Year=$DateArray[0];
		} elseif ($DateFormat=='d.m.Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		}
		if (!checkdate(intval($Month), intval($Day), intval($Year))) {
			$Errors[$i] = InvalidDeliveryDate;
		}
		return $Errors;
	}

/* Verify that the quotation flag is a 1 or 0 */
	function VerifyQuotation($quotation, $i, $Errors) {
		if ($quotation!=0 and $quotation!=1) {
			$Errors[$i] = InvalidQuotationFlag;
		}
		return $Errors;
	}

/* Fetch the next line number */
	function GetOrderLineNumber($OrderNo, $i, $Errors) {
		$linesql = "SELECT MAX(orderlineno)
					FROM salesorderdetails
					 WHERE orderno='" . $OrderNo . "'";
		$lineresult = api_DB_query($linesql);
		if ($myrow=DB_fetch_row($lineresult)) {
			return $myrow[0] + 1;
		} else {
			return 1;
		}
	}

/* Check that the order header already exists */
	function VerifyOrderHeaderExists($OrderNo, $i, $Errors) {
		$Searchsql = "SELECT COUNT(orderno)
					 FROM salesorders
					  WHERE orderno='".$OrderNo."'";
		$SearchResult=api_DB_query($Searchsql);
		$answer = DB_fetch_row($SearchResult);
		if ($answer[0] == 0) {
			$Errors[$i] = OrderHeaderNotSetup;
		}
		return $Errors;
	}

/* Verify that the unit price is numeric */
	function VerifyUnitPrice($unitprice, $i, $Errors) {
		if (!is_numeric($unitprice)) {
			$Errors[$i] = InvalidUnitPrice;
		}
		return $Errors;
	}

/* Verify that the quantity is numeric */
	function VerifyQuantity($quantity, $i, $Errors) {
		if (!is_numeric($quantity)) {
			$Errors[$i] = InvalidQuantity;
		}
		return $Errors;
	}

/* Verify that the discount percent is numeric */
	function VerifyDiscountPercent($discountpercent, $i, $Errors) {
		if (!is_numeric($discountpercent) or $discountpercent>100) {
			$Errors[$i] = InvalidDiscountPercent;
		}
		return $Errors;
	}

/* Check that the narrative field is 256 characters or less long */
	function VerifyNarrative($narrative, $i, $Errors) {
		if (mb_strlen($narrative)>256) {
			$Errors[$i] = InvalidNarrative;
		}
		return $Errors;
	}

/* Check that the poline field is 10 characters or less long */
	function VerifyPOLine($poline, $i, $Errors) {
		if (mb_strlen($poline)>10) {
			$Errors[$i] = InvalidPOLine;
		}
		return $Errors;
	}

/* Check that the item due date is a valid date. The date
 * must be in the same format as the date format specified in the
 * target webERP company */
	function VerifyItemDueDate($ItemDue, $i, $Errors) {
		$sql="SELECT confvalue FROM config WHERE confname='DefaultDateFormat'";
		$result=api_DB_query($sql);
		$myrow=DB_fetch_array($result);
		$DateFormat=$myrow[0];
		if (mb_strstr($ItemDue,'/')) {
			$DateArray = explode('/',$ItemDue);
		} elseif (mb_strstr($PeriodEnd,'.')) {
			$DateArray = explode('.',$ItemDue);
		}
		if ($DateFormat=='d/m/Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='m/d/Y') {
			$Day=$DateArray[1];
			$Month=$DateArray[0];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='Y/m/d') {
			$Day=$DateArray[2];
			$Month=$DateArray[1];
			$Year=$DateArray[0];
		} elseif ($DateFormat=='d.m.Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		}
		if (!checkdate(intval($Month), intval($Day), intval($Year))) {
			$Errors[$i] = InvalidItemDueDate;
		}
		return $Errors;
	}

/* Create a customer sales order header in webERP. If successful
 * returns $Errors[0]=0 and $Errors[1] will contain the order number.
 */
	function InsertSalesOrderHeader($OrderHeader, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		foreach ($OrderHeader as $key => $value) {
			$OrderHeader[$key] = DB_escape_string($value);
		}
		$Errors=VerifyDebtorExists($OrderHeader['debtorno'], sizeof($Errors), $Errors);
		$Errors=VerifyBranchNoExists($OrderHeader['debtorno'],$OrderHeader['branchcode'], sizeof($Errors), $Errors);
		if (isset($OrderHeader['customerref'])){
			$Errors=VerifyCustomerRef($OrderHeader['customerref'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['buyername'])){
			$Errors=VerifyBuyerName($OrderHeader['buyername'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['comments'])){
			$Errors=VerifyComments($OrderHeader['comments'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['orddate'])){
			$Errors=VerifyOrderDate($OrderHeader['orddate'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['ordertype'])){
			$Errors=VerifyOrderType($OrderHeader['ordertype'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['shipvia'])){
			$Errors=VerifyShipVia($OrderHeader['shipvia'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd1'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd1'], 40, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd2'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd2'], 40, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd3'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd3'], 40, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd4'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd4'], 40, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd5'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd5'], 20, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd6'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd6'], 15, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['contactphone'])){
			$Errors=VerifyPhoneNumber($OrderHeader['contactphone'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['contactemail'])){
			$Errors=VerifyEmailAddress($OrderHeader['contactemail'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deliverto'])){
			$Errors=VerifyDeliverTo($OrderHeader['deliverto'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deliverblind'])){
			$Errors=VerifyDeliverBlind($OrderHeader['deliverblind'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['freightcost'])){
			$Errors=VerifyFreightCost($OrderHeader['freightcost'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['fromstkloc'])){
			$Errors=VerifyFromStockLocation($OrderHeader['fromstkloc'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deliverydate'])){
			$Errors=VerifyDeliveryDate($OrderHeader['deliverydate'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['quotation'])){
			$Errors=VerifyQuotation($OrderHeader['quotation'], sizeof($Errors), $Errors);
		}
		$FieldNames='';
		$FieldValues='';
		global  $SOH_DateFields;
		$OrderHeader['orderno'] = GetNextTransNo(30);
		foreach ($OrderHeader as $key => $value) {
			$FieldNames.=$key.', ';
			if (in_array($key, $SOH_DateFields) ) {
			    $value = FormatDateforSQL($value);	// Fix dates
			}
			$FieldValues.="'".$value."', ";
		}
		$sql = "INSERT INTO salesorders (" . mb_substr($FieldNames,0,-2) . ")
					VALUES (" . mb_substr($FieldValues,0,-2). ")";
		if (sizeof($Errors)==0) {

			$result = api_DB_Query($sql);

			if (DB_error_no() != 0) {
				//$Errors[0] = DatabaseUpdateFailed;
				$Errors[0] = $sql;
			} else {
				$Errors[0]=0;
				$Errors[1]=$OrderHeader['orderno'];
			}
		}
		return $Errors;
	}

/* Modify a customer sales order header in webERP.
 */
	function ModifySalesOrderHeader($OrderHeader, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		foreach ($OrderHeader as $key => $value) {
			$OrderHeader[$key] = DB_escape_string($value);
		}
		$Errors=VerifyOrderHeaderExists($OrderHeader['orderno'], sizeof($Errors), $Errors);
		$Errors=VerifyDebtorExists($OrderHeader['debtorno'], sizeof($Errors), $Errors);
		$Errors=VerifyBranchNoExists($OrderHeader['debtorno'],$OrderHeader['branchcode'], sizeof($Errors), $Errors);
		if (isset($OrderHeader['customerref'])){
			$Errors=VerifyCustomerRef($OrderHeader['customerref'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['buyername'])){
			$Errors=VerifyBuyerName($OrderHeader['buyername'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['comments'])){
			$Errors=VerifyComments($OrderHeader['comments'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['orddate'])){
			$Errors=VerifyOrderDate($OrderHeader['orddate'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['ordertype'])){
			$Errors=VerifyOrderType($OrderHeader['ordertype'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['shipvia'])){
			$Errors=VerifyShipVia($OrderHeader['shipvia'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd1'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd1'], 40, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd2'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd2'], 40, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd3'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd3'], 40, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd4'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd4'], 40, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd5'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd5'], 20, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deladd6'])){
			$Errors=VerifyAddressLine($OrderHeader['deladd6'], 15, sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['contactphone'])){
			$Errors=VerifyPhoneNumber($OrderHeader['contactphone'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['contactemail'])){
			$Errors=VerifyEmailAddress($OrderHeader['contactemail'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deliverto'])){
			$Errors=VerifyDeliverTo($OrderHeader['deliverto'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deliverblind'])){
			$Errors=VerifyDeliverBlind($OrderHeader['deliverblind'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['freightcost'])){
			$Errors=VerifyFreightCost($OrderHeader['freightcost'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['fromstkloc'])){
			$Errors=VerifyFromStockLocation($OrderHeader['fromstkloc'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['deliverydate'])){
			$Errors=VerifyDeliveryDate($OrderHeader['deliverydate'], sizeof($Errors), $Errors);
		}
		if (isset($OrderHeader['quotation'])){
			$Errors=VerifyQuotation($OrderHeader['quotation'], sizeof($Errors), $Errors);
		}
		global  $SOH_DateFields;
		$sql='UPDATE salesorders SET ';
		foreach ($OrderHeader as $key => $value) {
			if (in_array($key, $SOH_DateFields) ) {
			    $value = FormatDateforSQL($value);	// Fix dates
			}
			$sql .= $key.'="'.$value.'", ';
		}
		$sql = mb_substr($sql,0,-2). " WHERE orderno='" . $OrderHeader['orderno']. "'";
		if (sizeof($Errors)==0) {
			$result = api_DB_Query($sql);
			echo DB_error_no();
			if (DB_error_no() != 0) {
				$Errors[0] = DatabaseUpdateFailed;
			} else {
				$Errors[0]=0;
			}
		}
		return $Errors;
	}

/* Create a customer sales order line in webERP. The order header must
 * already exist in webERP.
 */
	function InsertSalesOrderLine($OrderLine, $user, $password) {

		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		foreach ($OrderLine as $key => $value) {
			$OrderLine[$key] = DB_escape_string($value);
		}
		$OrderLine['orderlineno'] = GetOrderLineNumber($OrderLine['orderno'], sizeof($Errors), $Errors);
		$Errors=VerifyOrderHeaderExists($OrderLine['orderno'], sizeof($Errors), $Errors);
		$Errors=VerifyStockCodeExists($OrderLine['stkcode'], sizeof($Errors), $Errors);
		if (isset($OrderLine['unitprice'])){
			$Errors=VerifyUnitPrice($OrderLine['unitprice'], sizeof($Errors), $Errors);
		}
		if (isset($OrderLine['quantity'])){
			$Errors=VerifyQuantity($OrderLine['quantity'], sizeof($Errors), $Errors);
		}
		if (isset($OrderLine['discountpercent'])){
			//$OrderLine['discountpercent'] = $OrderLine['discountpercent'] * 100;
			$Errors=VerifyDiscountPercent($OrderLine['discountpercent'], sizeof($Errors), $Errors);
			$OrderLine['discountpercent'] = $OrderLine['discountpercent']/100;
		}
		if (isset($OrderLine['narrative'])){
			$Errors=VerifyNarrative($OrderLine['narrative'], sizeof($Errors), $Errors);
		}
		/*
		 * Not sure why the verification of itemdue doesn't work
		if (isset($OrderLine['itemdue'])){
			$Errors=VerifyItemDueDate($OrderLine['itemdue'], sizeof($Errors), $Errors);
		}
		*/
		if (isset($OrderLine['poline'])){
			$Errors=VerifyPOLine($OrderLine['poline'], sizeof($Errors), $Errors);
		}
		$FieldNames='';
		$FieldValues='';
		foreach ($OrderLine as $key => $value) {
			$FieldNames.=$key.', ';
			if ($key == 'actualdispatchdate') {
			    $value = FormatDateWithTimeForSQL($value);
			} elseif ($key == 'itemdue') {
			    $value = FormatDateForSQL($value);
			}
			$FieldValues.= "'" . $value . "', ";
		}

		$sql = "INSERT INTO salesorderdetails (" . mb_substr($FieldNames,0,-2) . ")
			VALUES (" . mb_substr($FieldValues,0,-2) . ")";

		if (sizeof($Errors)==0) {
			$result = api_DB_Query($sql);
			if (DB_error_no() != 0) {
				$Errors[0] = DatabaseUpdateFailed;
			} else {
				$Errors[0]=0;
			}
		}
		return $Errors;
	}

/* Modify a customer sales order line in webERP. The order header must
 * already exist in webERP.
 */
	function ModifySalesOrderLine($OrderLine, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		foreach ($OrderLine as $key => $value) {
			$OrderLine[$key] = DB_escape_string($value);
		}
		$Errors=VerifyOrderHeaderExists($OrderLine['orderno'], sizeof($Errors), $Errors);
		$Errors=VerifyStockCodeExists($OrderLine['stkcode'], sizeof($Errors), $Errors);
		if (isset($OrderLine['unitprice'])){
			$Errors=VerifyUnitPrice($OrderLine['unitprice'], sizeof($Errors), $Errors);
		}
		if (isset($OrderLine['quantity'])){
			$Errors=VerifyQuantity($OrderLine['quantity'], sizeof($Errors), $Errors);
		}
		if (isset($OrderLine['discountpercent'])){
			//$OrderLine['discountpercent'] = $OrderLine['discountpercent'] * 100;
			$Errors=VerifyDiscountPercent($OrderLine['discountpercent'], sizeof($Errors), $Errors);
			$OrderLine['discountpercent'] = $OrderLine['discountpercent']/100;
		}
		if (isset($OrderLine['narrative'])){
			$Errors=VerifyNarrative($OrderLine['narrative'], sizeof($Errors), $Errors);
		}
		if (isset($OrderLine['itemdue'])){
			$Errors=VerifyItemDueDate($OrderLine['itemdue'], sizeof($Errors), $Errors);
		}
		if (isset($OrderLine['poline'])){
			$Errors=VerifyPOLine($OrderLine['poline'], sizeof($Errors), $Errors);
		}
		$sql='UPDATE salesorderdetails SET ';
		foreach ($OrderLine as $key => $value) {
			if ($key == 'actualdispatchdate') {
			    $value = FormatDateWithTimeForSQL($value);
			}
			elseif ($key == 'itemdue')
			    $value = FormatDateForSQL($value);
			$sql .= $key.'="'.$value.'", ';
		}
		//$sql = mb_substr($sql,0,-2).' WHERE orderno="'.$OrderLine['orderno'].'" and
			//	" orderlineno='.$OrderLine['orderlineno'];
		$sql = mb_substr($sql,0,-2)." WHERE orderno='" . $OrderLine['orderno']."' AND stkcode='" . $OrderLine['stkcode']."'";
				//echo $sql;
				//exit;
		if (sizeof($Errors)==0) {
			$result = api_DB_Query($sql);
			echo DB_error_no();
			if (DB_error_no() != 0) {
				$Errors[0] = DatabaseUpdateFailed;
			} else {
				$Errors[0]=0;
			}
		}
		return $Errors;
	}

/* This function takes a Order Header ID  and returns an associative array containing
   the database record for that Order. If the Order Header ID doesn't exist
   then it returns an $Errors array.
*/
	function GetSalesOrderHeader($OrderNo, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$Errors=VerifyOrderHeaderExists($OrderNo, sizeof($Errors), $Errors);
		if (sizeof($Errors)!=0) {
			return $Errors;
		}
		$sql="SELECT * FROM salesorders WHERE orderno='".$OrderNo."'";
		$result = api_DB_Query($sql);
		if (sizeof($Errors)==0) {
			return DB_fetch_array($result);
		} else {
			return $Errors;
		}
	}

/* This function takes a Order Header ID  and returns an associative array containing
   the database record for that Order. If the Order Header ID doesn't exist
   then it returns an $Errors array.
*/
	function GetSalesOrderLine($OrderNo, $user, $password) {

		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$Errors=VerifyOrderHeaderExists($OrderNo, sizeof($Errors), $Errors);
		if (sizeof($Errors)!=0) {
			return $Errors;
		}
		$sql="SELECT * FROM salesorderdetails WHERE orderno='" . $OrderNo . "'";
		$result = api_DB_query($sql);
		if (sizeof($Errors)==0) {
			return DB_fetch_array($result);
		} else {
			return $Errors;
		}
	}


	function InvoiceSalesOrder($OrderNo, $User, $Password) {

		$Errors = array();
		$db = db($User, $Password);
		if (gettype($db)=='integer') {
			$Errors[]=NoAuthorisation;
			return $Errors;
		}
		$Errors=VerifyOrderHeaderExists($OrderNo, sizeof($Errors), $Errors);
		if (sizeof($Errors)!=0) {
			return $Errors;
		}
		/*Does not deal with assembly items or serialise/lot track items - for use by POS */
		/*Get Company Defaults */
		$ReadCoyResult = api_DB_query("SELECT debtorsact,
												freightact,
												gllink_debtors,
												gllink_stock
										FROM companies
										WHERE coycode=1");

		$CompanyRecord = DB_fetch_array($ReadCoyResult);
		if (DB_error_no() != 0) {
			$Errors[] = NoCompanyRecord;
		}

		$OrderHeaderSQL = "SELECT salesorders.debtorno,
				 				  debtorsmaster.name,
								  salesorders.branchcode,
								  salesorders.customerref,
								  salesorders.orddate,
								  salesorders.ordertype,
								  salesorders.shipvia,
								  custbranch.area,
								  custbranch.taxgroupid,
								  debtorsmaster.currcode,
								  currencies.rate,
								  salesorders.fromstkloc,
								  custbranch.salesman
							FROM salesorders
							INNER JOIN debtorsmaster
							ON salesorders.debtorno = debtorsmaster.debtorno
							INNER JOIN custbranch
							ON salesorders.debtorno = custbranch.debtorno
							AND salesorders.branchcode = custbranch.branchcode
							INNER JOIN locations
							ON locations.loccode=salesorders.fromstkloc
							INNER JOIN currencies
							ON debtorsmaster.currcode=currencies.currabrev
							WHERE salesorders.orderno = '" . $OrderNo . "'";

		$OrderHeaderResult = api_DB_query($OrderHeaderSQL);
		if (DB_error_no() != 0) {
			$Errors[] = NoReadOrder;
		}

		$OrderHeader = DB_fetch_array($OrderHeaderResult);

		$TaxProvResult = api_DB_query("SELECT taxprovinceid FROM locations WHERE loccode='" . $OrderHeader['fromstkloc'] ."'");
		if (DB_error_no() != 0) {
			$Errors[] = NoTaxProvince;
		}
		$myrow = DB_fetch_row($TaxProvResult);
		$DispTaxProvinceID = $myrow[0];

		$LineItemsSQL = "SELECT stkcode,
								unitprice,
								quantity,
								discountpercent,
								taxcatid,
								mbflag,
								materialcost+labourcost+overheadcost AS standardcost
						FROM salesorderdetails INNER JOIN stockmaster
						ON salesorderdetails.stkcode = stockmaster.stockid
						WHERE orderno ='" . $OrderNo . "'
						AND completed=0";

		$LineItemsResult = api_DB_query($LineItemsSQL);
		if (DB_error_no() != 0 OR DB_num_rows($LineItemsResult)==0) {
			$Errors[] = NoReadOrderLines;
			return $Errors;
		}

	/*Start an SQL transaction */
		$result = DB_Txn_Begin();
	/*Now Get the next invoice number - function in SQL_CommonFunctions*/
		$InvoiceNo = GetNextTransNo(10);
		$PeriodNo = GetCurrentPeriod();

		$TaxTotals =array();

		$TotalFXNetInvoice = 0;
		$TotalFXTax = 0;
		$LineCounter =0;

		while ($OrderLineRow = DB_fetch_array($LineItemsResult)) {

			$StandardCost = $OrderLineRow['standardcost'];
			$LocalCurrencyPrice= ($OrderLineRow['unitprice'] *(1- floatval($OrderLineRow['discountpercent'])))/ $OrderHeader['rate'];
			$LineNetAmount = $OrderLineRow['unitprice'] * $OrderLineRow['quantity'] *(1- floatval($OrderLineRow['discountpercent']));

			/*Gets the Taxes and rates applicable to this line from the TaxGroup of the branch and TaxCategory of the item
			and the taxprovince of the dispatch location */

			$SQL = "SELECT taxgrouptaxes.calculationorder,
							taxauthorities.description,
							taxgrouptaxes.taxauthid,
							taxauthorities.taxglcode,
							taxgrouptaxes.taxontax,
							taxauthrates.taxrate
					FROM taxauthrates INNER JOIN taxgrouptaxes ON
						taxauthrates.taxauthority=taxgrouptaxes.taxauthid
						INNER JOIN taxauthorities ON
						taxauthrates.taxauthority=taxauthorities.taxid
					WHERE taxgrouptaxes.taxgroupid='" . $OrderHeader['taxgroupid'] . "'
					AND taxauthrates.dispatchtaxprovince='" . $DispTaxProvinceID . "'
					AND taxauthrates.taxcatid = '" . $OrderLineRow['taxcatid'] . "'
					ORDER BY taxgrouptaxes.calculationorder";

			$GetTaxRatesResult = api_DB_query($SQL);

			if (DB_error_no() != 0) {
				$Errors[] = TaxRatesFailed;
			}
			$LineTaxAmount = 0;
			while ($myrow = DB_fetch_array($GetTaxRatesResult)){

				if (!isset($TaxTotals[$myrow['taxauthid']]['FXAmount'])) {
					$TaxTotals[$myrow['taxauthid']]['FXAmount']=0;
				}
				$TaxAuthID=$myrow['taxauthid'];
				$TaxTotals[$myrow['taxauthid']]['GLCode'] = $myrow['taxglcode'];
				$TaxTotals[$myrow['taxauthid']]['TaxRate'] = $myrow['taxrate'];
				$TaxTotals[$myrow['taxauthid']]['TaxAuthDescription'] = $myrow['description'];

				if ($myrow['taxontax'] ==1){
					$TaxAuthAmount = ($LineNetAmount+$LineTaxAmount) * $myrow['taxrate'];
				} else {
					$TaxAuthAmount =  $LineNetAmount * $myrow['taxrate'];
				}
				$TaxTotals[$myrow['taxauthid']]['FXAmount'] += $TaxAuthAmount;

				/*Make an array of the taxes and amounts including GLcodes for later posting - need debtortransid
				so can only post once the debtor trans is posted - can only post debtor trans when all tax is calculated */
				$LineTaxes[$LineCounter][$myrow['calculationorder']] = array('TaxCalculationOrder' =>$myrow['calculationorder'],
												'TaxAuthID' =>$myrow['taxauthid'],
												'TaxAuthDescription'=>$myrow['description'],
												'TaxRate'=>$myrow['taxrate'],
												'TaxOnTax'=>$myrow['taxontax'],
												'TaxAuthAmount'=>$TaxAuthAmount);
				$LineTaxAmount += $TaxAuthAmount;

			}//end loop around Taxes

			$TotalFXNetInvoice += $LineNetAmount;
			$TotalFXTax += $LineTaxAmount;

			/*Now update SalesOrderDetails for the quantity invoiced and the actual dispatch dates. */
			$SQL = "UPDATE salesorderdetails
					SET qtyinvoiced = qtyinvoiced + " . $OrderLineRow['quantity'] . ",
						actualdispatchdate = '" . $OrderHeader['orddate'] .  "',
						completed='1'
					WHERE orderno = '" . $OrderNo . "'
					AND stkcode = '" . $OrderLineRow['stkcode'] . "'";

			$Result = api_DB_query($SQL,'','',true);


			if ($OrderLineRow['mbflag']=='B' OR $OrderLineRow['mbflag']=='M') {
				$Assembly = False;

				/* Need to get the current location quantity
				will need it later for the stock movement */
               	$SQL="SELECT locstock.quantity
						FROM locstock
						WHERE locstock.stockid='" . $OrderLineRow['stkcode'] . "'
						AND loccode= '" . $OrderHeader['fromstkloc'] . "'";
				$Result = api_DB_query($SQL);

				if (DB_num_rows($Result)==1){
					$LocQtyRow = DB_fetch_row($Result);
					$QtyOnHandPrior = $LocQtyRow[0];
				} else {
					/* There must be some error this should never happen */
					$QtyOnHandPrior = 0;
				}

				$SQL = "UPDATE locstock
						SET quantity = locstock.quantity - " . $OrderLineRow['quantity'] . "
						WHERE locstock.stockid = '" . $OrderLineRow['stkcode'] . "'
						AND loccode = '" . $OrderHeader['fromstkloc'] . "'";
				$Result = api_DB_query($SQL,'','',true);

				$SQL = "INSERT INTO stockmoves (stockid,
												type,
												transno,
												loccode,
												trandate,
												debtorno,
												branchcode,
												price,
												prd,
												reference,
												qty,
												discountpercent,
												standardcost,
												newqoh)
						VALUES ('" . $OrderLineRow['stkcode'] . "',
								'10',
								'" . $InvoiceNo . "',
								'" . $OrderHeader['fromstkloc'] . "',
								'" . $OrderHeader['orddate'] . "',
								'" . $OrderHeader['debtorno'] . "',
								'" . $OrderHeader['branchcode'] . "',
								'" . $LocalCurrencyPrice . "',
								'" . $PeriodNo . "',
								'" . $OrderNo . "',
								'" . -$OrderLineRow['quantity'] . "',
								'" . $OrderLineRow['discountpercent'] . "',
								'" . $StandardCost . "',
								'" . ($QtyOnHandPrior - $OrderLineRow['quantity']) . "' )";

				$Result = api_DB_query($SQL,'','',true);

			} else if ($OrderLineRow['mbflag']=='A'){ /* its an assembly */
				/*Need to get the BOM for this part and make
				stock moves for the components then update the Location stock balances */
				$Assembly=True;
				$StandardCost =0; /*To start with - accumulate the cost of the comoponents for use in journals later on */
				$SQL = "SELECT bom.component,
								bom.quantity,
								stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standard
							FROM bom INNER JOIN stockmaster
							ON bom.component=stockmaster.stockid
							WHERE bom.parent='" . $OrderLineRow['stkcode'] . "'
                            AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                            AND bom.effectiveto > '" . date('Y-m-d') . "'";

				$AssResult = api_DB_query($SQL);

				while ($AssParts = DB_fetch_array($AssResult)){

					$StandardCost += ($AssParts['standard'] * $AssParts['quantity']) ;
					/* Need to get the current location quantity
					will need it later for the stock movement */
					$SQL="SELECT locstock.quantity
							FROM locstock
							WHERE locstock.stockid='" . $AssParts['component'] . "'
							AND loccode= '" . $OrderHeader['fromstkloc'] . "'";

					$Result = api_DB_query($SQL);
					if (DB_num_rows($Result)==1){
						$LocQtyRow = DB_fetch_row($Result);
	                  	$QtyOnHandPrior = $LocQtyRow[0];
					} else {
						/*There must be some error this should never happen */
						$QtyOnHandPrior = 0;
					}
					if (empty($AssParts['standard'])) {
						$AssParts['standard']=0;
					}
					$SQL = "INSERT INTO stockmoves (stockid,
													type,
													transno,
													loccode,
													trandate,
													debtorno,
													branchcode,
													prd,
													reference,
													qty,
													standardcost,
													show_on_inv_crds,
													newqoh)
										VALUES ('" . $AssParts['component'] . "',
												 10,
												 '" . $InvoiceNo . "',
												 '" . $OrderHeader['fromstkloc'] . "',
												 '" . $DefaultDispatchDate . "',
												 '" . $OrderHeader['debtorno'] . "',
												 '" . $OrderHeader['branchcode'] . "',
												 '" . $PeriodNo . "',
												 '" . _('Assembly') . ': ' . $OrderLineRow['stkcode'] . ' ' . _('Order') . ': ' . $OrderNo . "',
												 '" . -$AssParts['quantity'] * $OrderLineRow['quantity'] . "',
												 '" . $AssParts['standard'] . "',
												 0,
												 '" . ($QtyOnHandPrior - $AssParts['quantity'] * $OrderLineRow['quantity']) . "'	)";

					$Result = DB_query($SQL,'','',true);

					$SQL = "UPDATE locstock
							SET quantity = locstock.quantity - " . ($AssParts['quantity'] * $OrderLineRow['quantity']) . "
							WHERE locstock.stockid = '" . $AssParts['component'] . "'
							AND loccode = '" . $OrderHeader['fromlocstk'] . "'";

					$Result = DB_query($SQL,'','',true);
				} /* end of assembly explosion and updates */
			} /* end of its an assembly */


			if ($OrderLineRow['mbflag']=='A' OR $OrderLineRow['mbflag']=='D'){
				/*it's a Dummy/Service item or an Assembly item - still need stock movement record
				 * but quantites on hand are always nil */
				$SQL = "INSERT INTO stockmoves (stockid,
												type,
												transno,
												loccode,
												trandate,
												debtorno,
												branchcode,
												price,
												prd,
												reference,
												qty,
												discountpercent,
												standardcost,
												newqoh)
						VALUES ('" . $OrderLineRow['stkcode'] . "',
								'10',
								'" . $InvoiceNo . "',
								'" . $OrderHeader['fromstkloc'] . "',
								'" . $OrderHeader['orddate'] . "',
								'" . $OrderHeader['debtorno'] . "',
								'" . $OrderHeader['branchcode'] . "',
								'" . $LocalCurrencyPrice . "',
								'" . $PeriodNo . "',
								'" . $OrderNo . "',
								'" . -$OrderLineRow['quantity'] . "',
								'" . $OrderLineRow['discountpercent'] . "',
								'" . $StandardCost . "',
								'0' )";

				$Result = api_DB_query($SQL,'','',true);
			}
			/*Get the ID of the StockMove... */
			$StkMoveNo = DB_Last_Insert_ID('stockmoves','stkmoveno');
			/*Insert the taxes that applied to this line */
			foreach ($LineTaxes[$LineCounter] as $Tax) {

				$SQL = "INSERT INTO stockmovestaxes (stkmoveno,
									taxauthid,
									taxrate,
									taxcalculationorder,
									taxontax)
						VALUES ('" . $StkMoveNo . "',
							'" . $Tax['TaxAuthID'] . "',
							'" . $Tax['TaxRate'] . "',
							'" . $Tax['TaxCalculationOrder'] . "',
							'" . $Tax['TaxOnTax'] . "')";

				$Result = DB_query($SQL,'','',true);
			}

			/*Insert Sales Analysis records */

			$SQL="SELECT COUNT(*),
						salesanalysis.stkcategory,
						salesanalysis.area,
						salesanalysis.salesperson,
						salesanalysis.periodno,
						salesanalysis.typeabbrev,
						salesanalysis.cust,
						salesanalysis.custbranch,
						salesanalysis.stockid
					FROM salesanalysis,
						custbranch,
						stockmaster
					WHERE salesanalysis.stkcategory=stockmaster.categoryid
					AND salesanalysis.stockid=stockmaster.stockid
					AND salesanalysis.cust=custbranch.debtorno
					AND salesanalysis.custbranch=custbranch.branchcode
					AND salesanalysis.area=custbranch.area
					AND salesanalysis.salesperson=custbranch.salesman
					AND salesanalysis.typeabbrev ='" . $OrderHeader['ordertype'] . "'
					AND salesanalysis.periodno='" . $PeriodNo . "'
					AND salesanalysis.cust " . LIKE . "  '" . $OrderHeader['debtorno'] . "'
					AND salesanalysis.custbranch  " . LIKE . " '" . $OrderHeader['branchcode'] . "'
					AND salesanalysis.stockid  " . LIKE . " '" . $OrderLineRow['stkcode'] . "'
					AND salesanalysis.budgetoractual='1'
					GROUP BY salesanalysis.stockid,
						salesanalysis.stkcategory,
						salesanalysis.cust,
						salesanalysis.custbranch,
						salesanalysis.area,
						salesanalysis.periodno,
						salesanalysis.typeabbrev,
						salesanalysis.salesperson";

			$ErrMsg = _('The count of existing Sales analysis records could not run because');
			$DbgMsg = _('SQL to count the no of sales analysis records');
			$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			$myrow = DB_fetch_row($Result);

			if ($myrow[0]>0){  /*Update the existing record that already exists */

				$SQL = "UPDATE salesanalysis
						SET amt=amt+" . filter_number_format($OrderLineRow['unitprice'] * $OrderLineRow['quantity'] / $OrderHeader['rate']) . ",
						qty=qty +" . $OrderLineRow['quantity'] . ",
						disc=disc+" . filter_number_format($OrderLineRow['discountpercent'] * $OrderLineRow['unitprice'] * $OrderLineRow['quantity'] / $OrderHeader['rate']) . "
						WHERE salesanalysis.area='" . $myrow[2] . "'
						AND salesanalysis.salesperson='" . $myrow[3] . "'
						AND typeabbrev ='" . $OrderHeader['ordertype'] . "'
						AND periodno = '" . $PeriodNo . "'
						AND cust  " . LIKE . " '" . $OrderHeader['debtorno'] . "'
						AND custbranch  " . LIKE . "  '" . $OrderHeader['branchcode'] . "'
						AND stockid  " . LIKE . " '" . $OrderLineRow['stkcode'] . "'
						AND salesanalysis.stkcategory ='" . $myrow[1] . "'
						AND budgetoractual='1'";

			} else { /* insert a new sales analysis record */

				$SQL = "INSERT INTO salesanalysis (	typeabbrev,
													periodno,
													amt,
													cost,
													cust,
													custbranch,
													qty,
													disc,
													stockid,
													area,
													budgetoractual,
													salesperson,
													stkcategory )
								SELECT '" . $OrderHeader['ordertype']. "',
									'" . $PeriodNo . "',
									'" . $OrderLineRow['unitprice'] * $OrderLineRow['quantity'] / $OrderHeader['rate'] . "',
									0,
									'" . $OrderHeader['debtorno'] . "',
									'" . $OrderHeader['branchcode'] . "',
									'" . $OrderLineRow['quantity'] . "',
									'" . $OrderLineRow['discountpercent'] * $OrderLineRow['unitprice'] * $OrderLineRow['quantity'] / $OrderHeader['rate'] . "',
									'" . $OrderLineRow['stkcode'] . "',
									custbranch.area,
									1,
									custbranch.salesman,
									stockmaster.categoryid
								FROM stockmaster, custbranch
								WHERE stockmaster.stockid = '" . $OrderLineRow['stkcode'] . "'
								AND custbranch.debtorno = '" . $OrderHeader['debtorno'] . "'
								AND custbranch.branchcode='" . $OrderHeader['branchcode'] . "'";

			}

			$Result = api_DB_query($SQL,'','',true);

			if ($CompanyRecord['gllink_stock']==1 AND $StandardCost !=0){

/*first the cost of sales entry - GL accounts are retrieved using the function GetCOGSGLAccount from includes/GetSalesTransGLCodes.inc  */

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES (10,
										'" . $InvoiceNo . "',
										'" . $OrderHeader['orddate'] . "',
										'" . $PeriodNo . "',
										'" . GetCOGSGLAccount($OrderHeader['area'], $OrderLineRow['stkcode'], $OrderHeader['ordertype']) . "',
										'" . $OrderHeader['debtorno'] . " - " . $OrderLineRow['stkcode'] . " x " . $OrderLineRow['quantity'] . " @ " . $StandardCost . "',
										'" . ($StandardCost * $OrderLineRow['quantity']) . "')";

				$Result = api_DB_query($SQL,'','',true);

/*now the stock entry - this is set to the cost act in the case of a fixed asset disposal */
				$StockGLCode = GetStockGLCode($OrderLineRow['stkcode']);

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES (10,
										'" . $InvoiceNo . "',
										'" . $OrderHeader['orddate'] . "',
										'" . $PeriodNo . "',
										'" . $StockGLCode['stockact'] . "',
										'" . $OrderHeader['debtorno'] . " - " . $OrderLineRow['stkcode'] . " x " . $OrderLineRow['quantity'] . " @ " . $StandardCost . "',
										'" . (-$StandardCost * $OrderLineRow['quantity']) . "')";

				$Result = api_DB_query($SQL,'','',true);

			} /* end of if GL and stock integrated and standard cost !=0  and not an asset */

			if ($CompanyRecord['gllink_debtors']==1 AND $OrderLineRow['unitprice'] !=0){

				//Post sales transaction to GL credit sales
				$SalesGLAccounts = GetSalesGLAccount($OrderHeader['area'], $OrderLineRow['stkcode'], $OrderHeader['ordertype']);

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount )
					VALUES ('10',
						'" . $InvoiceNo . "',
						'" . $OrderHeader['orddate'] . "',
						'" . $PeriodNo . "',
						'" . $SalesGLAccounts['salesglcode'] . "',
						'" . $OrderHeader['debtorno'] . " - " . $OrderLineRow['stkcode'] . " x " . $OrderLineRow['quantity'] . " @ " . $OrderLineRow['unitprice'] . "',
						'" . -$OrderLineRow['unitprice'] * $OrderLineRow['quantity']/$OrderHeader['rate'] . "'
					)";
				$Result = api_DB_query($SQL,'','',true);

				if ($OrderLineRow['discountpercent'] !=0){

					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
							VALUES (10,
								'" . $InvoiceNo . "',
								'" . $OrderHeader['orddate'] . "',
								'" . $PeriodNo . "',
								'" . $SalesGLAccounts['discountglcode'] . "',
								'" . $OrderHeader['debtorno'] . " - " . $OrderLineRow['stkcode'] . " @ " . ($OrderLineRow['discountpercent'] * 100) . "%',
								'" . ($OrderLineRow['unitprice'] * $OrderLineRow['quantity'] * $OrderLineRow['discountpercent']/$OrderHeader['rate']) . "')";

					$Result = DB_query($SQL,'','',true);
				} /*end of if discount !=0 */

			} /*end of if sales integrated with gl */

			$LineCounter++; //needed for the array of taxes by line
		} /*end of OrderLine loop */

		$TotalInvLocalCurr = ($TotalFXNetInvoice + $TotalFXTax)/$OrderHeader['rate'];

		if ($CompanyRecord['gllink_debtors']==1){

			/*Now post the tax to the GL at local currency equivalent */
			if ($CompanyRecord['gllink_debtors']==1 AND $TaxAuthAmount !=0) {

				/*Loop through the tax authorities array to post each total to the taxauth glcode */
				foreach ($TaxTotals as $Tax){
					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount )
											VALUES (10,
											'" . $InvoiceNo . "',
											'" . $OrderHeader['orddate']. "',
											'" . $PeriodNo . "',
											'" . $Tax['GLCode'] . "',
											'" . $OrderHeader['debtorno'] . "-" . $Tax['TaxAuthDescription'] . "',
											'" . -$Tax['FXAmount']/$OrderHeader['rate'] . "' )";

					$Result = api_DB_query($SQL,'','',true);
				}
			}

			/*Post debtors transaction to GL debit debtors, credit freight re-charged and credit sales */
			if (($TotalInvLocalCurr) !=0) {
				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
									VALUES ('10',
										'" . $InvoiceNo . "',
										'" . $OrderHeader['orddate'] . "',
										'" . $PeriodNo . "',
										'" . $CompanyRecord['debtorsact'] . "',
										'" . $OrderHeader['debtorno'] . "',
										'" . $TotalInvLocalCurr . "')";

				$Result = api_DB_query($SQL,'','',true);
			}
			EnsureGLEntriesBalance(10,$InvoiceNo);

		} /*end of if Sales and GL integrated */

	/*Update order header for invoice charged on */
		$SQL = "UPDATE salesorders SET comments = CONCAT(comments,' Inv ','" . $InvoiceNo . "') WHERE orderno= '" . $OrderNo . "'";
		$Result = api_DB_query($SQL,'','',true);

	/*Now insert the DebtorTrans */

		$SQL = "INSERT INTO debtortrans (transno,
										type,
										debtorno,
										branchcode,
										trandate,
										inputdate,
										prd,
										reference,
										tpe,
										order_,
										ovamount,
										ovgst,
										rate,
										shipvia,
										salesperson)
									VALUES (
										'". $InvoiceNo . "',
										10,
										'" . $OrderHeader['debtorno'] . "',
										'" . $OrderHeader['branchcode'] . "',
										'" . $OrderHeader['orddate'] . "',
										'" . date('Y-m-d H-i-s') . "',
										'" . $PeriodNo . "',
										'" . $OrderHeader['customerref'] . "',
										'" . $OrderHeader['ordertype'] . "',
										'" . $OrderNo . "',
										'" . $TotalFXNetInvoice . "',
										'" . $TotalFXTax . "',
										'" . $OrderHeader['rate'] . "',
										'" . $OrderHeader['shipvia'] . "',
										'" . $OrderHeader['salesman'] . "')";

		$Result = api_DB_query($SQL,'','',true);

		$DebtorTransID = DB_Last_Insert_ID('debtortrans','id');

		/*for each Tax - need to insert into debtortranstaxes */
		foreach ($TaxTotals AS $TaxAuthID => $Tax) {

			$SQL = "INSERT INTO debtortranstaxes (debtortransid,
												taxauthid,
												taxamount)
								VALUES ('" . $DebtorTransID . "',
										'" . $TaxAuthID . "',
										'" . $Tax['FXAmount']/$OrderHeader['rate'] . "')";
			$Result = api_DB_query($SQL,'','',true);
		}

		if (sizeof($Errors)==0) {

			$Result = DB_Txn_Commit();
			$Errors[0]=0;
			$Errors[1]=$InvoiceNo;
		} else {
			$Result = DB_Txn_Rollback();
		}
		return $Errors;
	} //end InvoiceSalesOrder function


	function GetCurrentPeriod () {

		$TransDate = time(); //The current date to find the period for
		/* Find the unix timestamp of the last period end date in periods table */
		$sql = "SELECT MAX(lastdate_in_period), MAX(periodno) from periods";
		$result = DB_query($sql);
		$myrow=DB_fetch_row($result);

		if (is_null($myrow[0])){
			$InsertFirstPeriodResult = api_DB_query("INSERT INTO periods VALUES (0,'" . Date('Y-m-d',mktime(0,0,0,Date('m')+1,0,Date('Y'))) . "')");
			$InsertFirstPeriodResult = api_DB_query("INSERT INTO periods VALUES (1,'" . Date('Y-m-d',mktime(0,0,0,Date('m')+2,0,Date('Y'))) . "')");
			$LastPeriod=1;
			$LastPeriodEnd = mktime(0,0,0,Date('m')+2,0,Date('Y'));
		} else {
			$Date_Array = explode('-', $myrow[0]);
			$LastPeriodEnd = mktime(0,0,0,$Date_Array[1]+1,0,(int)$Date_Array[0]);
			$LastPeriod = $myrow[1];
		}
		/* Find the unix timestamp of the first period end date in periods table */
		$sql = "SELECT MIN(lastdate_in_period), MIN(periodno) from periods";
		$result = api_DB_query($sql);
		$myrow=DB_fetch_row($result);
		$Date_Array = explode('-', $myrow[0]);
		$FirstPeriodEnd = mktime(0,0,0,$Date_Array[1],0,(int)$Date_Array[0]);
		$FirstPeriod = $myrow[1];

		/* If the period number doesn't exist */
		if (!PeriodExists($TransDate)) {
			/* if the transaction is after the last period */
			if ($TransDate > $LastPeriodEnd) {

				$PeriodEnd = mktime(0,0,0,Date('m', $TransDate)+1, 0, Date('Y', $TransDate));

				while ($PeriodEnd >= $LastPeriodEnd) {
					if (Date('m', $LastPeriodEnd)<=13) {
						$LastPeriodEnd = mktime(0,0,0,Date('m', $LastPeriodEnd)+2, 0, Date('Y', $LastPeriodEnd));
					} else {
						$LastPeriodEnd = mktime(0,0,0,2, 0, Date('Y', $LastPeriodEnd)+1);
					}
					$LastPeriod++;
					CreatePeriod($LastPeriod, $LastPeriodEnd);
				}
			} else {
			/* The transaction is before the first period */
				$PeriodEnd = mktime(0,0,0,Date('m', $TransDate), 0, Date('Y', $TransDate));
				$Period = $FirstPeriod - 1;
				while ($FirstPeriodEnd > $PeriodEnd) {
					CreatePeriod($Period, $FirstPeriodEnd);
					$Period--;
					if (Date('m', $FirstPeriodEnd)>0) {
						$FirstPeriodEnd = mktime(0,0,0,Date('m', $FirstPeriodEnd), 0, Date('Y', $FirstPeriodEnd));
					} else {
						$FirstPeriodEnd = mktime(0,0,0,13, 0, Date('Y', $FirstPeriodEnd));
					}
				}
			}
		} else if (!PeriodExists(mktime(0,0,0,Date('m',$TransDate)+1,Date('d',$TransDate),Date('Y',$TransDate)))) {
			/* Make sure the following months period exists */
			$sql = "SELECT MAX(lastdate_in_period), MAX(periodno) from periods";
			$result = DB_query($sql);
			$myrow=DB_fetch_row($result);
			$Date_Array = explode('-', $myrow[0]);
			$LastPeriodEnd = mktime(0,0,0,$Date_Array[1]+2,0,(int)$Date_Array[0]);
			$LastPeriod = $myrow[1];
			CreatePeriod($LastPeriod+1, $LastPeriodEnd);
		}

		/* Now return the period number of the transaction */

		$MonthAfterTransDate = Mktime(0,0,0,Date('m',$TransDate)+1,Date('d',$TransDate),Date('Y',$TransDate));
		$GetPrdSQL = "SELECT periodno
						FROM periods
						WHERE lastdate_in_period < '" . Date('Y-m-d', $MonthAfterTransDate) . "'
						AND lastdate_in_period >= '" . Date('Y-m-d', $TransDate) . "'";

		$ErrMsg = _('An error occurred in retrieving the period number');
		$GetPrdResult = DB_query($GetPrdSQL,$ErrMsg);
		$myrow = DB_fetch_row($GetPrdResult);

		return $myrow[0];
	}

?>
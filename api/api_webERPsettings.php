<?php

/* This function returns the default currency code in webERP.
 */

	function GetDefaultCurrency($user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$sql = "SELECT currencydefault FROM companies WHERE coycode=1";
		$result = DB_query($sql);
		$answer=DB_fetch_array($result);
		$ReturnValue[0]=0;
		$ReturnValue[1]=$answer;
		return $ReturnValue;
	}

/* This function returns the default sales type in webERP.
 */

	function GetDefaultPriceList($user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$sql = "SELECT confvalue FROM config WHERE confname='DefaultPriceList'";
		$result = DB_query($sql);
		$answer=DB_fetch_array($result);
		$ReturnValue[0]=0;
		$ReturnValue[1]=$answer;
		return $ReturnValue;
	}

/* This function returns the default date format in webERP.
 */

	function GetDefaultDateFormat($user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$sql = "SELECT confvalue FROM config WHERE confname='DefaultDateFormat'";
		$result = DB_query($sql);
		$answer=DB_fetch_array($result);
		$ReturnValue[0]=0;
		$ReturnValue[1]=$answer;
		return $ReturnValue;
	}

/* This function returns the reports directory of the webERP installation for the company in api/api_php.php */

	function GetReportsDirectory($user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$sql = "SELECT confvalue FROM config WHERE confname='reports_dir'";
		$result = DB_query($sql);
		$answer=DB_fetch_array($result);
		$ReturnValue[0]=0;
		$ReturnValue[1]=$answer;
		return $ReturnValue;
	}

/* This function returns the default location of the weberp user being used */

	function GetDefaultLocation($user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$sql = "select defaultlocation from www_users where userid='".$user."'";
		$result = DB_query($sql);
		$answer=DB_fetch_array($result);
		$ReturnValue[0]=0;
		$ReturnValue[1]=$answer;
		return $ReturnValue;
	}

/* This function returns the default shipper in webERP.
 */

	function GetDefaultShipper($user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$sql = "SELECT confvalue from config WHERE confname='Default_Shipper'";
		$result = DB_query($sql);
		$answer=DB_fetch_array($result);
		$ReturnValue[0]=0;
		$ReturnValue[1]=$answer;
		return $ReturnValue;
	}

	/* This function creates a POS zipped update file */


	function CreatePOSDataFull($POSDebtorNo, $POSBranchCode, $User, $Password) {
		$Errors = array();
		$db = db($User, $Password);
		if (gettype($db)=='integer') {
			 return NoAuthorisation;
		}
		$Result = Create_POS_Data_Full($POSDebtorNo,$POSBranchCode,dirname(__FILE__).'/../');
		if ($Result==1) {
			$ReturnValue=0;
		} else {
			$ReturnValue=$Result;
		}
		return $ReturnValue;
	}

	function DeletePOSData($User, $Password) {
		$Errors = array();
		$db = db($User, $Password);
		if (gettype($db)=='integer') {
			return NoAuthorisation;
		}
		$Result = Delete_POS_Data(dirname(__FILE__).'/../');
		if ($Result==1){
			return 0;
		} else {
			return $Result;
		}
	}

?>
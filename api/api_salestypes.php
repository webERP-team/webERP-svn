<?php

/* This function returns a list of the sales type abbreviations
 * currently setup on webERP
 */

	function GetSalesTypeList($user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$sql = "SELECT typeabbrev FROM salestypes";
		$result = DB_query($sql);
		$i=0;
		while ($myrow=DB_fetch_array($result)) {
			$SalesTypeList[$i]=$myrow[0];
			$i++;
		}
		$Errors[0]=0;
		$Errors[1]=$SalesTypeList;
		return $Errors;
	}

/* This function takes as a parameter a sales type abbreviation
 * and returns an array containing the details of the selected
 * sales type.
 */

	function GetSalesTypeDetails($salestype, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$Errors = VerifySalesType($salestype, sizeof($Errors), $Errors);
		if (sizeof($Errors)==0) {
			$sql = "SELECT * FROM salestypes WHERE typeabbrev='".$salestype."'";
			$result = DB_query($sql);
			$Errors[0]=0;
			$Errors[1]=DB_fetch_array($result);
			return $Errors;
		} else {
			return $Errors;
		}
	}

/* This function takes as a parameter an array of sales type details
 * to be inserted into webERP.
 */

	function InsertSalesType($SalesTypeDetails, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}

		$FieldNames='';
		$FieldValues='';
		foreach ($SalesTypeDetails as $key => $value) {
			$FieldNames.=$key.', ';
			$FieldValues.='"'.$value.'", ';
		}
		$sql = "INSERT INTO salestypes ('" . mb_substr($FieldNames,0,-2) . "')
				VALUES ('" . mb_substr($FieldValues,0,-2) . "') ";
		if (sizeof($Errors)==0) {
			$result = DB_query($sql);
			if (DB_error_no() != 0) {
				$Errors[0] = DatabaseUpdateFailed;
			} else {
				$Errors[0]=0;
			}
		}
		return $Errors;
	}

?>
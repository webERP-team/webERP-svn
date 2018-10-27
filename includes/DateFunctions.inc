<?php

/*
date validation and parsing functions

These functions refer to the session variable defining the date format
The date format is defined in SystemParameters called DefaultDateFormat
this can be a string either 'd/m/Y' for UK/Australia/New Zealand dates or
'm/d/Y' for US/Canada format dates

or Y/m/d  for Sweden ;) Anders Eriksson anders@weberp.se.

or d.m.Y  for Germany ;) Juergen Ruemmler heinrich@ruemmler.net

*/

function Is_date($DateEntry) {

	$DateEntry =Trim($DateEntry);

	//echo '<BR>The date entered is ' . $DateEntry;

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	} elseif (mb_strlen($DateEntry)==6) {
		$Date_Array[0]= mb_substr($DateEntry,0,2);
		$Date_Array[1]= mb_substr($DateEntry,2,2);
		$Date_Array[2]= mb_substr($DateEntry,4,2);
	} elseif (mb_strlen($DateEntry)==8) {
		$Date_Array[0]= mb_substr($DateEntry,0,2);
		$Date_Array[1]= mb_substr($DateEntry,2,2);
		$Date_Array[2]= mb_substr($DateEntry,4,4);
	}

	if (!isset($Date_Array) or sizeof($Date_Array)<3) {
		return 0;
	}

	if ((int)$Date_Array[2] >9999) {
		return 0;
	}


	if (is_long((int)$Date_Array[0]) AND is_long((int)$Date_Array[1]) AND is_long((int)$Date_Array[2])) {

		if (($_SESSION['DefaultDateFormat']=='d/m/Y') OR ($_SESSION['DefaultDateFormat']=='d.m.Y')){

			if (checkdate((int)$Date_Array[1],(int)$Date_Array[0],(int)$Date_Array[2])){
				return 1;
			} else {
				return 0;
			}
		} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){

			if (checkdate((int)$Date_Array[0],(int)$Date_Array[1],(int)$Date_Array[2])){
				return 1;
			} else {
				return 0;
			}
		} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d'){

			if (checkdate((int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0])){
				return 1;
			} else {
				return 0;
			}
		} elseif ($_SESSION['DefaultDateFormat']=='Y-m-d'){
			if (checkdate((int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0])){
				return 1;
			} else {
				return 0;
			}
		} else { /*Can't be in an appropriate DefaultDateFormat */
			return 0;
		}
	}
} //end of Is_Date function


function MonthAndYearFromSQLDate($DateEntry) {

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	}

	if (mb_strlen($Date_Array[2])>4) {
		$Date_Array[2]= mb_substr($Date_Array[2],0,2);
	}

	$MonthName = GetMonthText(date('n', mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0])));
	return $MonthName . ' ' . date('Y', mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0]));

}

function GetMonthText($MonthNumber){
	switch ($MonthNumber) {
		case 1:
			$Month = _('January');
			break;
		case 2:
			$Month = _('February');
			break;
		case 3:
			$Month = _('March');
			break;
		case 4:
			$Month = _('April');
			break;
		case 5:
			$Month = _('May');
			break;
		case 6:
			$Month = _('June');
			break;
		case 7:
			$Month = _('July');
			break;
		case 8:
			$Month = _('August');
			break;
		case 9:
			$Month = _('September');
			break;
		case 10:
			$Month = _('October');
			break;
		case 11:
			$Month = _('November');
			break;
		case 12:
			$Month = _('December');
			break;
		default:
			$Month = _('error');
			break;
	}
	return $Month;
}

function GetWeekDayText ($WeekDayNumber){
	$Day='';
	switch ($WeekDayNumber) {
		case 0:
			$Day = _('Sunday');
			break;
		case 1:
			$Day = _('Monday');
			break;
		case 2:
			$Day = _('Tuesday');
			break;
		case 3:
			$Day = _('Wednesday');
			break;
		case 4:
			$Day = _('Thursday');
			break;
		case 5:
			$Day = _('Friday');
			break;
		case 6:
			$Day = _('Saturday');
			break;
	}
	return $Day;
}

function DisplayDateTime() {
	// Long date and time in locale format.
	// Could be replace by IntlDateFormatter (available on PHP 5.3.0 or later). See http://php.net/manual/en/class.intldateformatter.php
	switch ($_SESSION['Language']) {
		case 'en_GB.utf8':
			$long_datetime = GetWeekDayText(date('w')) . ' ' . date('j') . ' ' . GetMonthText(date('n')) . ' ' . date('Y') . ' ' . date('G:i');
			break;
		case 'en_US.utf8':
			$long_datetime = GetWeekDayText(date('w')) . ', ' . GetMonthText(date('n')) . ' ' . date('j') . ', '. date('Y') . ' ' . date('G:i');
			break;
		case 'es_ES.utf8':
			$long_datetime = GetWeekDayText(date('w')) . ' ' . date('j') . ' de ' . GetMonthText(date('n')) . ' de ' . date('Y') . ' ' . date('G:i');
			break;
		case 'fr_FR.utf8':
			$long_datetime = GetWeekDayText(date('w')) . ' ' . date('j') . ' ' . GetMonthText(date('n')) . ' ' . date('Y') . ' ' . date('G:i');
			break;
		default:
			$long_datetime = GetWeekDayText(date('w')) . ' ' . date('j') . ' ' . GetMonthText(date('n')) . ' ' . date('Y') . ' ' . date('G:i');
			break;
	}
	return $long_datetime;
/*	return GetWeekDayText(date('w')) . ' ' . date('j') . ' ' . GetMonthText(date('n')) . ' ' . date('G:i') ;*/
}

function DayOfWeekFromSQLDate($DateEntry) {

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	}

	if (mb_strlen($Date_Array[2])>4) {
		$Date_Array[2]= mb_substr($Date_Array[2],0,2);
	}

	return date( 'w', mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0]));

}


function DayOfMonthFromSQLDate($DateEntry) {

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	}

	if (mb_strlen($Date_Array[2])>4) {
		$Date_Array[2]= mb_substr($Date_Array[2],0,2);
	}

	return date( 'j', mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0]));

}


// Returns the timestamp for the financial year end. To find other year ends,
// use $YearIncrement to move back and forward. in -1 gives last year end, 1
// gives next year end.

function YearEndDate($MonthNo, $YearIncrement) {
	if (Date('m') > $MonthNo) {
		$Year = Date('Y')+1+$YearIncrement;
	} else {
		$Year = Date('Y')+$YearIncrement;
	}
	return mktime(0,0,0,$MonthNo+1,0,$Year);
}

function ConvertSQLDate($DateEntry) {

//for MySQL dates are in the format YYYY-mm-dd


	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	} else {
		prnMsg(_('The date does not appear to be in a valid format. The date being converted from SQL format was:') . ' ' . $DateEntry,'error');
		switch ($_SESSION['DefaultDateFormat']) {
			case 'd/m/Y':
				return '0/0/000';
				break;
			case 'd.m.Y':
				return '0.0.000';
				break;
			case 'm/d/Y':
				return '0/0/0000';
				break;
			case 'Y/m/d':
				return '0000/0/0';
				break;
			case 'Y-m-d':
				return '0000-0-0';
				break;
		}
	}

	if (mb_strlen($Date_Array[2])>4) {  /*chop off the time stuff */
		$Date_Array[2]= mb_substr($Date_Array[2],0,2);
	}

	if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
		return $Date_Array[2].'/'.$Date_Array[1].'/'.$Date_Array[0];
	} elseif ($_SESSION['DefaultDateFormat']=='d.m.Y'){
		return $Date_Array[2].'.'.$Date_Array[1].'.'.$Date_Array[0];
	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
		return $Date_Array[1].'/'.$Date_Array[2].'/'.$Date_Array[0];
	} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d'){
		return $Date_Array[0].'/'.$Date_Array[1].'/'.$Date_Array[2];
	} elseif ($_SESSION['DefaultDateFormat']=='Y-m-d'){
		return $Date_Array[0].'-'.$Date_Array[1].'-'.$Date_Array[2];
	}
} // end function ConvertSQLDate

function ConvertSQLDateTime($DateEntry) {

//for MySQL dates are in the format YYYY-mm-dd H:i:s


	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	} else {
		prnMsg(_('The date does not appear to be in a valid format. The date being converted from SQL format was:') . ' ' . $DateEntry,'error');
		switch ($_SESSION['DefaultDateFormat']) {
			case 'd/m/Y':
				return '0/0/000';
				break;
			case 'd.m.Y':
				return '0.0.000';
				break;
			case 'm/d/Y':
				return '0/0/0000';
				break;
			case 'Y/m/d':
				return '0000/0/0';
				break;
		}
	}

	if (mb_strlen($Date_Array[2])>4) {
		$Time = mb_substr($Date_Array[2],3,8);
		$Date_Array[2]= mb_substr($Date_Array[2],0,2);
	}

	if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
		return $Date_Array[2].'/'.$Date_Array[1].'/'.$Date_Array[0] . ' ' . $Time;
	} elseif ($_SESSION['DefaultDateFormat']=='d.m.Y'){
		return $Date_Array[2].'.'.$Date_Array[1].'.'.$Date_Array[0] . ' ' . $Time;
	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
		return $Date_Array[1].'/'.$Date_Array[2].'/'.$Date_Array[0] . ' ' . $Time;
	} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d'){
		return $Date_Array[0].'/'.$Date_Array[1].'/'.$Date_Array[2] . ' ' . $Time;
	}

} // end function ConvertSQLDate

function SQLDateToEDI($DateEntry) {

//for MySQL dates are in the format YYYY-mm-dd
//EDI format 102 dates are in the format CCYYMMDD - just need to lose the seperator

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	}

	if (mb_strlen($Date_Array[2])>4) {  /*chop off the time stuff */
		$Date_Array[2]= mb_substr($Date_Array[2],0,2);
	}

	return $Date_Array[0].$Date_Array[1].$Date_Array[2];

} // end function SQLDateToEDI

function ConvertToEDIDate($DateEntry) {

/* takes a date in a the format specified in $_SESSION['DefaultDateFormat']
and converts to a yyyymmdd - EANCOM format 102*/


	$DateEntry = trim($DateEntry);

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	} elseif (mb_strlen($DateEntry)==6) {
		$Date_Array[0]= mb_substr($DateEntry,0,2);
		$Date_Array[1]= mb_substr($DateEntry,2,2);
		$Date_Array[2]= mb_substr($DateEntry,4,2);
	} elseif (mb_strlen($DateEntry)==8) {
		$Date_Array[0]= mb_substr($DateEntry,0,2);
		$Date_Array[1]= mb_substr($DateEntry,2,2);
		$Date_Array[2]= mb_substr($DateEntry,4,4);
	}


//to modify assumption in 2030

	if ((int)$Date_Array[2] <60) {
		$Date_Array[2] = '20'.$Date_Array[2];
	} elseif ((int)$Date_Array[2] >59 AND (int)$Date_Array[2] <100) {
		$Date_Array[2] = '19'.$Date_Array[2];
	} elseif ((int)$Date_Array[2] >9999) {
		return 0;
	}

	if (($_SESSION['DefaultDateFormat']=='d/m/Y') || ($_SESSION['DefaultDateFormat']=='d.m.Y')){
		return $Date_Array[2].$Date_Array[1].$Date_Array[0];

	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
		return $Date_Array[2].$Date_Array[0].$Date_Array[1];

	} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d') {
		return $Date_Array[1].$Date_Array[2].$Date_Array[0];

	}

} // end function to convert DefaultDateFormat Date to EDI format 102

function ConvertEDIDate($DateEntry, $EDIFormatCode) {

	/*EDI Format codes:
		102  -  CCYYMMDD
		203  -  CCYYMMDDHHMM
		616  -  CCYYWW  - cant handle the week number
		718  -  CCYYMMDD-CCYYMMDD  can't handle this either a date range
	*/


	switch ($EDIFormatCode) {
	case 102:
		if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
			return mb_substr($DateEntry,6,2).'/'.mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,0,4);

		} elseif ($_SESSION['DefaultDateFormat']=='d.m.Y') {
						return mb_substr($DateEntry,6,2).'.'.mb_substr($DateEntry,4,2).'.'.mb_substr($DateEntry,0,4);

		} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
						return mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,6,2).'/'.mb_substr($DateEntry,0,4);

		} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d') {
						return mb_substr($DateEntry,0,4).'/'.mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,6,2);

		}
		break;
	case 203:
		if ($_SESSION['DefaultDateFormat']=='d/m/Y') {
			return mb_substr($DateEntry,6,2).'/'.mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,0,4).' ' . mb_substr($DateEntry,6,2).':' . mb_substr($DateEntry,8,2);

		} elseif ($_SESSION['DefaultDateFormat']=='d.m.Y') {
						return mb_substr($DateEntry,6,2).'.'.mb_substr($DateEntry,4,2).'.'.mb_substr($DateEntry,0,4).' ' . mb_substr($DateEntry,6,2).':' . mb_substr($DateEntry,8,2);

		} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
						return mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,6,2).'/'.mb_substr($DateEntry,0,4).' ' . mb_substr($DateEntry,6,2).':' . mb_substr($DateEntry,8,2);

		} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d') {
						return mb_substr($DateEntry,0,4).'/'.mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,6,2).' ' . mb_substr($DateEntry,6,2).':' . mb_substr($DateEntry,8,2);

		}
		break;
	case 616:
		/*multiply the week number by 7 and add to the 1/1/CCYY */
		return date($_SESSION['DefaultDateFormat'], mktime(0,0,0, 1,1+(7*(int)mb_substr($DateEntry,4,2)),mb_substr($DateEntry,0,4)));
		break;
	case 718:
		if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
			return mb_substr($DateEntry,6,2).'/'.mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,0,4) . ' - '. mb_substr($DateEntry,15,2).'/'.mb_substr($DateEntry,13,2).'/'.mb_substr($DateEntry,9,4);
		} elseif ($_SESSION['DefaultDateFormat']=='d.m.Y') {
						return mb_substr($DateEntry,6,2).'.'.mb_substr($DateEntry,4,2).'.'.mb_substr($DateEntry,0,4) . ' - '. mb_substr($DateEntry,15,2).'.'.mb_substr($DateEntry,13,2).'.'.mb_substr($DateEntry,9,4);
		} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
						return mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,6,2).'/'.mb_substr($DateEntry,0,4).' - '. mb_substr($DateEntry,13,2).'/'.mb_substr($DateEntry,15,2).'/'.mb_substr($DateEntry,9,4);
		} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d') {
						return mb_substr($DateEntry,0,4).'/'.mb_substr($DateEntry,4,2).'/'.mb_substr($DateEntry,6,2).' - '. mb_substr($DateEntry,13,2).'/'.mb_substr($DateEntry,15,2).'/'.mb_substr($DateEntry,9,4);

		}

		break;
	}


}



function Format_Date($DateEntry) {

	$DateEntry =trim($DateEntry);

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	} elseif (mb_strlen($DateEntry)==6) {
		$Date_Array[0]= mb_substr($DateEntry,0,2);
		$Date_Array[1]= mb_substr($DateEntry,2,2);
		$Date_Array[2]= mb_substr($DateEntry,4,2);
	} elseif (mb_strlen($DateEntry)==8) {
		$Date_Array[0]= mb_substr($DateEntry,0,2);
		$Date_Array[1]= mb_substr($DateEntry,2,2);
		$Date_Array[2]= mb_substr($DateEntry,4,4);
	}

//to modify assumption in 2030

	if ((int)$Date_Array[2] <60) {
		$Date_Array[2] = '20'.$Date_Array[2];
	} elseif ((int)$Date_Array[2] >59 AND (int)$Date_Array[2] <100){
		$Date_Array[2] = '19'.$Date_Array[2];
	} elseif ((int)$Date_Array[2] >9999) {
		return 0;
	}

	if (is_long((int)$Date_Array[0]) AND is_long((int)$Date_Array[1]) AND is_long((int)$Date_Array[2])) {
		if ($_SESSION['DefaultDateFormat']=='d/m/Y'){
			if (checkdate((int)$Date_Array[1],(int)$Date_Array[0],(int)$Date_Array[2])){
				return $Date_Array[0].'/'.$Date_Array[1].'/'.$Date_Array[2];
			}
		} elseif ($_SESSION['DefaultDateFormat']=='d.m.Y'){
			if (checkdate((int)$Date_Array[1],(int)$Date_Array[0],(int)$Date_Array[2])){
				return $Date_Array[0].'.'.$Date_Array[1].'.'.$Date_Array[2];
			}
		} elseif ($_SESSION['DefaultDateFormat']='m/d/Y'){
			if (checkdate((int)$Date_Array[0],(int)$Date_Array[1],(int)$Date_Array[2])){
				return $Date_Array[0].'/'.$Date_Array[1].'/'.$Date_Array[2];
			}
		} elseif ($_SESSION['DefaultDateFormat']='Y/m/d'){
			if (checkdate((int)$Date_Array[2],(int)$Date_Array[0],(int)$Date_Array[1])){
				return $Date_Array[0].'/'.$Date_Array[1].'/'.$Date_Array[2];
			}
		} elseif ($_SESSION['DefaultDateFormat']='Y-m-d'){
			if (checkdate((int)$Date_Array[2],(int)$Date_Array[0],(int)$Date_Array[1])){
				return $Date_Array[0].'-'.$Date_Array[1].'-'.$Date_Array[2];
			}
		} // end if check date
	} else { // end if all numeric inputs
		return 0;
	}
}// end of function

function FormatDateForSQL($DateEntry) {

/* takes a date in a the format specified in $_SESSION['DefaultDateFormat']
and converts to a yyyy/mm/dd format */
	$Date_Array = array();
	$DateEntry = trim($DateEntry);

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	} elseif (mb_strlen($DateEntry)==6) {
		$Date_Array[0]= mb_substr($DateEntry,0,2);
		$Date_Array[1]= mb_substr($DateEntry,2,2);
		$Date_Array[2]= mb_substr($DateEntry,4,2);
	} elseif (mb_strlen($DateEntry)==8) {
		$Date_Array[0]= mb_substr($DateEntry,0,4);
		$Date_Array[1]= mb_substr($DateEntry,4,2);
		$Date_Array[2]= mb_substr($DateEntry,6,2);
	}

	if ($_SESSION['DefaultDateFormat']=='Y/m/d' OR $_SESSION['DefaultDateFormat']=='Y-m-d') {
		if (mb_strlen($Date_Array[0])==2) {
			if ((int)$Date_Array[0] <=60) {
				$Date_Array[0] = '20'.$Date_Array[2];
			} elseif ((int)$Date_Array[0] >60 AND (int)$Date_Array[2] <100) {
				$Date_Array[0] = '19'.$Date_Array[2];
			}
		}
		return $Date_Array[0].'-'.$Date_Array[1].'-'.$Date_Array[2];

	} elseif (($_SESSION['DefaultDateFormat']=='d/m/Y')
				OR $_SESSION['DefaultDateFormat']=='d.m.Y'){
		if (mb_strlen($Date_Array[2])==2) {
			if ((int)$Date_Array[2] <=60) {
				$Date_Array[2] = '20'.$Date_Array[2];
			} elseif ((int)$Date_Array[2] >60 AND (int)$Date_Array[2] <100) {
				$Date_Array[2] = '19'. $Date_Array[2];
			}
		}
		/* echo '<BR>The date returned is ' . $Date_Array[2].'/'.$Date_Array[1].'/'.$Date_Array[0]; */
		return $Date_Array[2].'-'.$Date_Array[1].'-'.$Date_Array[0];

	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
		if (mb_strlen($Date_Array[2])==2) {
			if ((int)$Date_Array[2] <=60) {
				$Date_Array[2] = '20'.$Date_Array[2];
			} elseif ((int)$Date_Array[2] >60 AND (int)$Date_Array[2] <100) {
				$Date_Array[2] = '19'.$Date_Array[2];
			}
		}
		return $Date_Array[2].'-'.$Date_Array[0].'-'.$Date_Array[1];
	}

}// end of function

function FormatDateWithTimeForSQL ($datetime) {
    //  Split the time off, fix date and add the time to returned value.
    $dt = explode(' ', $datetime);

    return FormatDateForSQL( $dt[0] ) . ' ' . $dt[1];
}

function LastDayOfMonth ( $DateEntry) {
	/*Expects a date in DefaultDateFormat and
	 * Returns the last day of the month in the entered date
	 * in the DefaultDateFormat
	 *
	 * mktime (0,0,0 month, day, year)
	 */

	$DateEntry = trim($DateEntry);

	if (mb_strpos($DateEntry,'/')) {
		$Date_Array = explode('/',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'-')) {
		$Date_Array = explode('-',$DateEntry);
	} elseif (mb_strpos ($DateEntry,'.')) {
		$Date_Array = explode('.',$DateEntry);
	} elseif (mb_strlen($DateEntry)==6) {
		$Date_Array[0]= mb_substr($DateEntry,0,2);
		$Date_Array[1]= mb_substr($DateEntry,2,2);
		$Date_Array[2]= mb_substr($DateEntry,4,2);
	} elseif (mb_strlen($DateEntry)==8) {
		$Date_Array[0]= mb_substr($DateEntry,0,4);
		$Date_Array[1]= mb_substr($DateEntry,4,2);
		$Date_Array[2]= mb_substr($DateEntry,6,2);
	}

	if ($_SESSION['DefaultDateFormat']=='Y/m/d' OR $_SESSION['DefaultDateFormat']=='Y-m-d') {
		if (mb_strlen($Date_Array[0])==2) {
			if ((int)$Date_Array[0] <=60) {
				$Date_Array[0] = '20'.$Date_Array[2];
			} elseif ((int)$Date_Array[0] >60 AND (int)$Date_Array[2] <100) {
				$Date_Array[0] = '19'.$Date_Array[2];
			}
		}

		$DateStamp =  mktime(0,0,0, $Date_Array[1]+1, 0, $Date_Array[0]);

	}elseif (($_SESSION['DefaultDateFormat']=='d/m/Y') OR $_SESSION['DefaultDateFormat']=='d.m.Y'){
		if (mb_strlen($Date_Array[2])==2) {
			if ((int)$Date_Array[2] <=60) {
				$Date_Array[2] = '20'.$Date_Array[2];
			} elseif ((int)$Date_Array[2] >60 AND (int)$Date_Array[2] <100) {
				$Date_Array[2] = '19'.$Date_Array[2];
			}
		}
		$DateStamp =  mktime(0,0,0, $Date_Array[1]+1, 0, $Date_Array[2]);


	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y') {
		if (mb_strlen($Date_Array[2])==2) {
			if ((int)$Date_Array[2] <=60) {
				$Date_Array[2] = '20'.$Date_Array[2];
			} elseif ((int)$Date_Array[2] >60 AND (int)$Date_Array[2] <100) {
				$Date_Array[2] = '19'.$Date_Array[2];
			}
		}
		return $Date_Array[2].'-'.$Date_Array[0].'-'.$Date_Array[1];
		$DateStamp =  mktime(0,0,0, $Date_Array[0]+1, 0, $Date_Array[2]);
	}
	return Date($_SESSION['DefaultDateFormat'],$DateStamp);
}// end of Last Day in the month function



function Date1GreaterThanDate2 ($Date1, $Date2) {

/* returns true (1) if Date1 is greater than Date2 */

	$Date1 = trim($Date1);
	$Date2 = trim($Date2);

	/* Get date elements */
	if ($_SESSION['DefaultDateFormat']=='d.m.Y' )  {
		list($Day1,$Month1,$Year1) = explode('.', $Date1);
		list($Day2,$Month2,$Year2) = explode('.', $Date2);
	} elseif ($_SESSION['DefaultDateFormat'] =='d/m/Y'){
		list($Day1,$Month1,$Year1) = explode('/', $Date1);
		list($Day2,$Month2,$Year2) = explode('/', $Date2);
	} elseif ($_SESSION['DefaultDateFormat'] =='m/d/Y'){
		list($Month1,$Day1,$Year1) = explode('/', $Date1);
		list($Month2,$Day2,$Year2) = explode('/', $Date2);
	} elseif ($_SESSION['DefaultDateFormat'] =='Y/m/d' ){
		list($Year1,$Month1,$Day1) = explode('/', $Date1);
		list($Year2,$Month2,$Day2) = explode('/', $Date2);
	} elseif ($_SESSION['DefaultDateFormat'] =='Y-m-d' ){
		list($Year1,$Month1,$Day1) = explode('-', $Date1);
		list($Year2,$Month2,$Day2) = explode('-', $Date2);
	}

	/*Try to make the year of each date comparable - if one date is specified as just
	 * 2 characters and the other >2 then then make them both 4 characters long. Assume
	 *  a date >50 to be 1900's and less than to be 2000's
	 */

	if (mb_strlen($Year1)>2 AND mb_strlen($Year2)==2){
		if ($Year2>50) {
			$Year2=1900+$Year2;
		} else {
			$Year2=2000+$Year2;
		}
	}
	if (mb_strlen($Year2)>2 AND mb_strlen($Year1)==2){
		if ($Year1>50) {
			$Year1=1900+$Year1;
		} else {
			$Year1=2000+$Year1;
		}
	}

	/* Compare years */
	if ($Year1>$Year2){
		return 1;
	} elseif ($Year2>$Year1){
		return 0;
	}

	/* Compare months. Years are equal*/
	if ($Month1>$Month2){
		return 1;
	} elseif ($Month2>$Month1){
		return 0;
	}

	/* Compare days. Years and months are equal */
	if ($Day1>$Day2){
		return 1;
	} elseif ($Day2>$Day1){
		return 0;
	}
	/* The dates are equal, so return false as date 1 is NOT greater than date 2 */
	return 0;
}

function CalcDueDate($TranDate, $DayInFollowingMonth, $DaysBeforeDue){

	$TranDate = trim($TranDate);

	if (mb_strpos($TranDate,'/')) {
		$Date_Array = explode('/',$TranDate);
	} elseif (mb_strpos ($TranDate,'-')) {
		$Date_Array = explode('-',$TranDate);
	} elseif (mb_strpos ($TranDate,'.')) {
		$Date_Array = explode('.',$TranDate);
  }

	if (($_SESSION['DefaultDateFormat']=='d/m/Y') OR ($_SESSION['DefaultDateFormat']=='d.m.Y')){
		if ($DayInFollowingMonth==0){ /*then it must be set up for DaysBeforeDue type */

			$DayDue = $Date_Array[0]+$DaysBeforeDue;
			$MonthDue = $Date_Array[1];
			$YearDue = $Date_Array[2];

		} elseif($DayInFollowingMonth>=29) { //take the last day of month
			if ($DayInFollowingMonth <= 31) {
				$DayDue = 0;
			} else {
				$DayDue = $DayInFollowingMonth-31;
			}
			$MonthDue = $Date_Array[1]+2;
			$YearDue = $Date_Array[2];
		} else {
			$DayDue = $DayInFollowingMonth;
			$MonthDue = $Date_Array[1]+1;
			$YearDue = $Date_Array[2];

		}
	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
		if ($DayInFollowingMonth==0){ /*then it must be set up for DaysBeforeDue type */
			$DayDue = $Date_Array[1]+$DaysBeforeDue;
			$MonthDue = $Date_Array[0];
			$YearDue = $Date_Array[2];

		} elseif($DayInFollowingMonth>=29) { //take the last day of month
			if ($DayInFollowingMonth <= 31) {
				$DayDue = 0;
			} else {
				$DayDue = $DayInFollowingMonth-31;
			}
			$MonthDue = $Date_Array[0]+2;
			$YearDue = $Date_Array[2];
		} else {
			$DayDue = $DayInFollowingMonth;
			$MonthDue = $Date_Array[0]+1;
			$YearDue = $Date_Array[2];
		}
	} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d' OR $_SESSION['DefaultDateFormat']=='Y-m-d'){
		if ($DayInFollowingMonth==0){ /*then it must be set up for DaysBeforeDue type */
			$DayDue = $Date_Array[2]+$DaysBeforeDue;
			$MonthDue = $Date_Array[1];
			$YearDue = $Date_Array[0];

		} elseif($DayInFollowingMonth>=29) { //take the last day of month

			if ($DayInFollowingMonth <= 31) {
				$DayDue = 0;
			} else {
				$DayDue = $DayInFollowingMonth-31;
			}
			$MonthDue = $Date_Array[1]+2;
			$YearDue = $Date_Array[0];
		} else {
			$DayDue = $DayInFollowingMonth;
			$MonthDue = $Date_Array[1]+1;
			$YearDue = $Date_Array[0];
		}
	}
	return Date($_SESSION['DefaultDateFormat'], mktime(0,0,0, $MonthDue, $DayDue,$YearDue));

}

function DateAdd ($DateToAddTo,$PeriodString,$NumberPeriods){
	/*Takes
	 * DateToAddTo in $_SESSION['DefaultDateFormat'] format
	 * $PeriodString is one of:
	 * d - days
	 * w - weeks
	 * m - months
	 * y - years
	 * $NumberPeriods is an integer positve or negative */
  $DateToAddTo = trim($DateToAddTo);

	if (mb_strpos($DateToAddTo,'/')) {
		$Date_Array = explode('/',$DateToAddTo);
	} elseif (mb_strpos ($DateToAddTo,'-')) {
		$Date_Array = explode('-',$DateToAddTo);
	} elseif (mb_strpos ($DateToAddTo,'.')) {
		$Date_Array = explode('.',$DateToAddTo);
  }

	if (($_SESSION['DefaultDateFormat']=='d/m/Y') OR ($_SESSION['DefaultDateFormat']=='d.m.Y')){

		switch ($PeriodString) {
		case 'd': //Days
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[0]+$NumberPeriods ,(int)$Date_Array[2]));
			break;
		case 'w': //weeks
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[0]+($NumberPeriods*7),(int)$Date_Array[2]));
			break;
		case 'm': //months
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[1]+$NumberPeriods,(int)$Date_Array[0],(int)$Date_Array[2]));
			break;
		case 'y': //years
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[0],(int)$Date_Array[2]+$NumberPeriods));
			break;
		default:
			return 0;
		}
	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){

		switch ($PeriodString) {
		case 'd':
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[0],(int)$Date_Array[1]+$NumberPeriods,(int)$Date_Array[2]));
			break;
		case 'w':
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[0],(int)$Date_Array[1]+($NumberPeriods*7),(int)$Date_Array[2]));
			break;
		case 'm':
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[0]+$NumberPeriods,(int)$Date_Array[1],(int)$Date_Array[2]));
			break;
		case 'y':
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[0],(int)$Date_Array[1],(int)$Date_Array[2]+$NumberPeriods));
			break;
		default:
			return 0;
		}
	} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d' OR $_SESSION['DefaultDateFormat']=='Y-m-d'){

		switch ($PeriodString) {
		case 'd':
/* Fix up the Y/m/d calculation */
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[2]+$NumberPeriods,(int)$Date_Array[0]));
			break;
		case 'w':
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[2]+($NumberPeriods*7),(int)$Date_Array[0]));
			break;
		case 'm':
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[1]+$NumberPeriods,(int)$Date_Array[2],(int)$Date_Array[0]));
			break;
		case 'y':
			return Date($_SESSION['DefaultDateFormat'],mktime(0,0,0, (int)$Date_Array[1],(int)$Date_Array[2],(int)$Date_Array[0]+$NumberPeriods));
			break;
		default:
			return 0;
		}
	}
}

function DateDiff ($Date1, $Date2, $Period) {

/* expects dates in the format specified in $_SESSION['DefaultDateFormat'] - period can be one of 'd','w','y','m'
months are assumed to be 30 days and years 365.25 days This only works
provided that both dates are after 1970. Also only works for dates up to the year 2035 ish */

	$Date1 = trim($Date1);
	$Date2 = trim($Date2);

	if (mb_strpos($Date1,'/')) {
		$Date1_array = explode('/',$Date1);
	} elseif (mb_strpos ($Date1,'-')) {
		$Date1_array = explode('-',$Date1);
	} elseif (mb_strpos ($Date1,'.')) {
		$Date1_array = explode('.',$Date1);
  }
	if (mb_strpos($Date2,'/')) {
		$Date2_array = explode('/',$Date2);
	} elseif (mb_strpos ($Date2,'-')) {
		$Date2_array = explode('-',$Date2);
	} elseif (mb_strpos ($Date2,'.')) {
		$Date2_array = explode('.',$Date2);
  }

	if (($_SESSION['DefaultDateFormat']=='d/m/Y') or ($_SESSION['DefaultDateFormat']=='d.m.Y')){
		$Date1_Stamp = mktime(0,0,0, (int)$Date1_array[1],(int)$Date1_array[0],(int)$Date1_array[2]);
		$Date2_Stamp = mktime(0,0,0, (int)$Date2_array[1],(int)$Date2_array[0],(int)$Date2_array[2]);
	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
		$Date1_Stamp = mktime(0,0,0, (int)$Date1_array[0],(int)$Date1_array[1],(int)$Date1_array[2]);
		$Date2_Stamp = mktime(0,0,0, (int)$Date2_array[0],(int)$Date2_array[1],(int)$Date2_array[2]);
	} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d' OR $_SESSION['DefaultDateFormat']=='Y-m-d'){
		$Date1_Stamp = mktime(0,0,0, (int)$Date1_array[1],(int)$Date1_array[2],(int)$Date1_array[0]);//Changeorder of entries to match Y/M/D format
		$Date2_Stamp = mktime(0,0,0, (int)$Date2_array[1],(int)$Date2_array[2],(int)$Date2_array[0]); //Changeorder of entries to match Y/M/D format
	}
	$Difference = $Date1_Stamp - $Date2_Stamp;

/* Difference is the number of seconds between each date negative if Date 2 > Date 1 */

	switch ($Period) {
	case 'd':
		return (int) ($Difference/(24*60*60));
		break;
	case 'w':
		return (int) ($Difference/(24*60*60*7));
		break;
	case 'm':
		return (int) ($Difference/(24*60*60*30));
		break;
	case 's':
		return $Difference;
		break;
	case 'y':
		return (int) ($Difference/(24*60*60*365.25));
		break;
	default:
		return 0;
	}

}


function CalcEarliestDispatchDate (){

/*There is a system parameter that allows the number of working days to be defined $_SESSION['WorkingDaysWeek'] if this is set to 5 then the assumption is no delivery on Sat and Sun
 * if the number of working days is 6 then it is assumed that there is no delivery on Sunday
 * if the number of working days is 7 then there are deliveries every day
 * */

	$EarliestDispatch = time();
	/* If the hour is after Dispatch Cut Off Time default dispatch date to tomorrow */
	$EarliestDispatch = (Date('H')>=$_SESSION['DispatchCutOffTime'])?($EarliestDispatch+24*60*60):$EarliestDispatch;

	if ((Date('w',$EarliestDispatch)==0) AND ($_SESSION['WorkingDaysWeek'] != '7')){

/*if today is a sunday AND the company does NOT work 7 days a week, the dispatch date must be tomorrow (Monday) or after */

		$EarliestDispatch = Mktime(0,0,0,Date('m',$EarliestDispatch),Date('d',$EarliestDispatch)+1,Date('y',$EarliestDispatch));

	} elseif ((Date('w',$EarliestDispatch)==6) AND ($_SESSION['WorkingDaysWeek'] != '6') AND ($_SESSION['WorkingDaysWeek'] != '7')){

/*if today is a saturday AND the company does NOT work at least 6 days a week, the dispatch date must be Monday or after */

		$EarliestDispatch = Mktime(0,0,0,Date('m',$EarliestDispatch),Date('d',$EarliestDispatch)+2,Date('y',$EarliestDispatch));

	}else {

		$EarliestDispatch = Mktime(0,0,0,Date('m'),Date('d'),Date('y'));
	}
	return $EarliestDispatch;
}

function CreatePeriod($PeriodNo, $PeriodEnd) {
				$GetPrdSQL = "INSERT INTO periods (periodno,
													lastdate_in_period)
												VALUES (
													'" . $PeriodNo . "',
													'" . Date('Y-m-d', $PeriodEnd) . "')";
				$ErrMsg = _('An error occurred in adding a new period number');
				$GetPrdResult = DB_query($GetPrdSQL, $ErrMsg);

}

function PeriodExists($TransDate) {

	/* Find the date a month on */
	$MonthAfterTransDate = Mktime(0,0,0,Date('m',$TransDate)+1,Date('d',$TransDate),Date('Y',$TransDate));

	$GetPrdSQL = "SELECT periodno FROM periods WHERE lastdate_in_period < '" . Date('Y/m/d', $MonthAfterTransDate) . "' AND lastdate_in_period >= '" . Date('Y/m/d', $TransDate) . "'";

	$ErrMsg = _('An error occurred in retrieving the period number');
	$GetPrdResult = DB_query($GetPrdSQL,$ErrMsg);

	if (DB_num_rows($GetPrdResult)==0) {
		return false;
	} else {
		return true;
	}

}

function GetPeriod ($TransDate, $UseProhibit=true) {

	/* Convert the transaction date into a unix time stamp.*/

	if (mb_strpos($TransDate,'/')) {
		$Date_Array = explode('/',$TransDate);
	} elseif (mb_strpos ($TransDate,'-')) {
		$Date_Array = explode('-',$TransDate);
	} elseif (mb_strpos ($TransDate,'.')) {
		$Date_Array = explode('.',$TransDate);
	}

	if (($_SESSION['DefaultDateFormat']=='d/m/Y') or ($_SESSION['DefaultDateFormat']=='d.m.Y')){
		$TransDate = mktime(0,0,0,$Date_Array[1],$Date_Array[0],$Date_Array[2]);
	} elseif ($_SESSION['DefaultDateFormat']=='m/d/Y'){
		$TransDate = mktime(0,0,0,$Date_Array[0],$Date_Array[1],$Date_Array[2]);
	} elseif ($_SESSION['DefaultDateFormat']=='Y/m/d' OR $_SESSION['DefaultDateFormat']=='Y-m-d'){
		$TransDate = mktime(0,0,0,$Date_Array[1],$Date_Array[2],$Date_Array[0]);
	}

	if (Is_Date(ConvertSQLDate($_SESSION['ProhibitPostingsBefore'])) AND $UseProhibit){ //then the ProhibitPostingsBefore configuration is set
		$Date_Array = explode('-', $_SESSION['ProhibitPostingsBefore']); //its in ANSI SQL format
		$ProhibitPostingsBefore = mktime(0,0,0,$Date_Array[1],$Date_Array[2],$Date_Array[0]);

		/* If transaction date is in a closed period use the month end of that period */
		if ($TransDate < $ProhibitPostingsBefore) {
			$TransDate = $ProhibitPostingsBefore;
		}
	}
	/* Find the unix timestamp of the last period end date in periods table */
	$sql = "SELECT MAX(lastdate_in_period), MAX(periodno) from periods";
	$result = DB_query($sql);
	$myrow=DB_fetch_row($result);

	if (is_null($myrow[0])){ //then no periods are currently defined - so set a couple up starting at 0
		$InsertFirstPeriodResult = DB_query("INSERT INTO periods VALUES (0,'" . Date('Y-m-d',mktime(0,0,0,Date('m')+1,0,Date('Y'))) . "')",_('Could not insert first period'));
		$InsertFirstPeriodResult = DB_query("INSERT INTO periods VALUES (1,'" . Date('Y-m-d',mktime(0,0,0,Date('m')+2,0,Date('Y'))) . "')",_('Could not insert second period'));
		$LastPeriod=1;
		$LastPeriodEnd = mktime(0,0,0,Date('m')+2,0,Date('Y'));
	} else {
		$Date_Array = explode('-', $myrow[0]);
		$LastPeriodEnd = mktime(0,0,0,$Date_Array[1]+1,0,(int)$Date_Array[0]);
		$LastPeriod = $myrow[1];
	}
	/* Find the unix timestamp of the first period end date in periods table */
	$sql = "SELECT MIN(lastdate_in_period), MIN(periodno) from periods";
	$result = DB_query($sql);
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

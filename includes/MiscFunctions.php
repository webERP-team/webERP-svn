<?php
// MiscFunctions.php
/*
 * included from includes/ConnectDB.inc
 * ******************************************  */
/** STANDARD MESSAGE HANDLING & FORMATTING **/
/*  ******************************************  */

function prnMsg($Msg, $Type = 'info', $Prefix = '') {
	global $Messages;
	$Messages[] = array($Msg, $Type, $Prefix);
}

function reverse_escape($str) {
	$search = array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"');
	$replace = array("\\", "\0", "\n", "\r", "\x1a", "'", '"');
	return str_replace($search, $replace, $str);
}

function IsEmailAddress($Email) {
	$AtIndex = strrpos($Email, "@");
	if ($AtIndex == false) {
		return false; // No @ sign is not acceptable.
	}
	if (preg_match('/\\.\\./', $Email)) {
		return false; // > 1 consecutive dot is not allowed.
	}
	//  Check component length limits
	$Domain = mb_substr($Email, $AtIndex + 1);
	$Local = mb_substr($Email, 0, $AtIndex);
	$LocalLen = mb_strlen($Local);
	$DomainLen = mb_strlen($Domain);
	if ($LocalLen < 1 || $LocalLen > 64) {
		// local part length exceeded
		return false;
	}
	if ($DomainLen < 1 || $DomainLen > 255) {
		// domain part length exceeded
		return false;
	}
	if ($Local[0] == '.' or $Local[$LocalLen - 1] == '.') {
		// local part starts or ends with '.'
		return false;
	}
	if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $Domain)) {
		// character not valid in domain part
		return false;
	}
	if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $Local))) {
		// character not valid in local part unless local part is quoted
		if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $Local))) {
			return false;
		}
	}
	//  Check for a DNS 'MX' or 'A' record.
	//  Windows supported from PHP 5.3.0 on - so check.
	$Ret = true;
	/*  Apparentely causes some problems on some versions - perhaps bleeding edge just yet
	if (version_compare(PHP_VERSION, '5.3.0') >= 0 or mb_strtoupper(mb_substr(PHP_OS, 0, 3) !== 'WIN')) {
		$Ret = checkdnsrr($Domain, 'MX') or checkdnsrr($Domain, 'A');
	}
	*/
	return $Ret;
}

function ContainsIllegalCharacters($CheckVariable) {
	if (mb_strstr($CheckVariable, "'") or mb_strstr($CheckVariable, '+') or mb_strstr($CheckVariable, '?') or mb_strstr($CheckVariable, '.') or mb_strstr($CheckVariable, "\"") or mb_strstr($CheckVariable, '&') or mb_strstr($CheckVariable, "\\") or mb_strstr($CheckVariable, '"') or mb_strstr($CheckVariable, '>') or mb_strstr($CheckVariable, '<')) {
		return true;
	} else {
		return false;
	}
}

function pre_var_dump(&$var) {
	echo '<div align=left><pre>';
	var_dump($var);
	echo '</pre></div>';
}

class XmlElement {
	var $name;
	var $attributes;
	var $content;
	var $children;
}

function GetECBCurrencyRates() {
	/* See http://www.ecb.int/stats/exchange/eurofxref/html/index.en.html
	for detail of the European Central Bank rates - published daily */
	if (http_file_exists('http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml')) {
		$xml = file_get_contents('http://www.ecb.int/stats/eurofxref/eurofxref-daily.xml');
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $xml, $tags);
		xml_parser_free($parser);

		$elements = array(); // the currently filling [child] XmlElement array
		$stack = array();
		foreach ($tags as $tag) {
			$index = count($elements);
			if ($tag['type'] == 'complete' or $tag['type'] == 'open') {
				$elements[$index] = new XmlElement;
				$elements[$index]->name = $tag['tag'];
				if (isset($tag['attributes'])) {
					$elements[$index]->attributes = $tag['attributes'];
				}
				if (isset($tag['value'])) {
					$elements[$index]->content = $tag['value'];
				}
				if ($tag['type'] == 'open') { // push
					$elements[$index]->children = array();
					$stack[count($stack)] = & $elements;
					$elements = & $elements[$index]->children;
				}
			}
			if ($tag['type'] == 'close') { // pop
				$elements = & $stack[count($stack) - 1];
				unset($stack[count($stack) - 1]);
			}
		}
		$Currencies = array();
		foreach ($elements[0]->children[2]->children[0]->children as $CurrencyDetails) {
			$Currencies[$CurrencyDetails->attributes['currency']] = $CurrencyDetails->attributes['rate'];
		}
		$Currencies['EUR'] = 1; //ECB delivers no rate for Euro
		//return an array of the currencies and rates
		return $Currencies;
	} else {
		return false;
	}
}

function GetCurrencyRate($CurrCode, $CurrenciesArray) {
	if ((!isset($CurrenciesArray[$CurrCode]) or !isset($CurrenciesArray[$_SESSION['CompanyRecord']['currencydefault']])) and $_SESSION['UpdateCurrencyRatesDaily'] != '0') {
		return quote_oanda_currency($CurrCode);
	} elseif ($CurrCode == 'EUR') {
		if ($CurrenciesArray[$_SESSION['CompanyRecord']['currencydefault']] == 0) {
			return 0;
		} else {
			return 1 / $CurrenciesArray[$_SESSION['CompanyRecord']['currencydefault']];
		}
	} else {
		if ($CurrenciesArray[$_SESSION['CompanyRecord']['currencydefault']] == 0) {
			return 0;
		} else {
			return $CurrenciesArray[$CurrCode] / $CurrenciesArray[$_SESSION['CompanyRecord']['currencydefault']];
		}
	}
}

function quote_oanda_currency($CurrCode) {
	if (http_file_exists('http://www.oanda.com/convert/fxdaily?value=1&redirected=1&exch=' . $CurrCode . '&format=CSV&dest=Get+Table&sel_list=' . $_SESSION['CompanyRecord']['currencydefault'])) {
		$page = file('http://www.oanda.com/convert/fxdaily?value=1&redirected=1&exch=' . $CurrCode . '&format=CSV&dest=Get+Table&sel_list=' . $_SESSION['CompanyRecord']['currencydefault']);
		$match = array();
		preg_match('/(.+),(\w{3}),([0-9.]+),([0-9.]+)/i', implode('', $page), $match);
		if (sizeof($match) > 0) {
			return $match[3];
		} else {
			return false;
		}
	}
}

function google_currency_rate($CurrCode) {
	$Rate = 0;
	$PageLines = file('http://www.google.com/finance/converter?a=1&from=' . $_SESSION['CompanyRecord']['currencydefault'] . '&to=' . $CurrCode);
	foreach ($PageLines as $Line) {
		if (mb_strpos($Line, 'currency_converter_result')) {
			$Length = mb_strpos($Line, '</span>') - 58;
			$Rate = floatval(mb_substr($Line, 58, $Length));
		}
	}
	return $Rate;
}

function AddCarriageReturns($str) {
	return str_replace('\r\n', chr(10), $str);
}

function wikiLink($WikiType, $WikiPageID) {
	if (strstr($_SESSION['WikiPath'], 'http:')) {
		$WikiPath = $_SESSION['WikiPath'];
	} else {
		$WikiPath = '../' . $_SESSION['WikiPath'] . '/';
	}
	if ($_SESSION['WikiApp'] == _('WackoWiki')) {
		echo '<a target="_blank" href="' . $WikiPath . $WikiType . $WikiPageID . '">' . _('Wiki ' . $WikiType . ' Knowledge Base') . ' </a>  <br />';
	} elseif ($_SESSION['WikiApp'] == _('MediaWiki')) {
		echo '<a target="_blank" href="' . $WikiPath . 'index.php?title=' . $WikiType . '/' . $WikiPageID . '">' . _('Wiki ' . $WikiType . ' Knowledge Base') . '</a><br />';
	} elseif ($_SESSION['WikiApp'] == _('DokuWiki')) {
		echo '<a target="_blank" href="' . $WikiPath . '/doku.php?id=' . $WikiType . ':' . $WikiPageID . '">' . _('Wiki ' . $WikiType . ' Knowledge Base') . '</a><br />';
	}
}

//  Lindsay debug stuff
function LogBackTrace($dest = 0) {
	error_log("***BEGIN STACK BACKTRACE***", $dest);

	$stack = debug_backtrace();
	//  Leave out our frame and the topmost - huge for xmlrpc!
	for ($ii = 1;$ii < count($stack) - 3;$ii++) {
		$frame = $stack[$ii];
		$msg = "FRAME " . $ii . ": ";
		if (isset($frame['file'])) {
			$msg.= "; file=" . $frame['file'];
		}
		if (isset($frame['line'])) {
			$msg.= "; line=" . $frame['line'];
		}
		if (isset($frame['function'])) {
			$msg.= "; function=" . $frame['function'];
		}
		if (isset($frame['args'])) {
			// Either function args, or included file name(s)
			$msg.= ' (';
			foreach ($frame['args'] as $val) {
				$typ = gettype($val);
				switch ($typ) {
					case 'array':
						$msg.= '[ ';
						foreach ($val as $v2) {
							if (gettype($v2) == 'array') {
								$msg.= '[ ';
								foreach ($v2 as $v3) $msg.= $v3;
								$msg.= ' ]';
							} else {
								$msg.= $v2 . ', ';
							}
							$msg.= ' ]';
							break;
						}
					case 'string':
						$msg.= $val . ', ';
						break;

					case 'integer':
						$msg.= sprintf("%d, ", $val);
						break;

					default:
						$msg.= '<' . gettype($val) . '>, ';
						break;

					}
					$msg.= ' )';
			}
		}
		error_log($msg, $dest);
	}

	error_log('++++END STACK BACKTRACE++++', $dest);

	return;
}

function http_file_exists($url) {
	$f = @fopen($url, 'r');
	if ($f) {
		fclose($f);
		return true;
	}
	return false;
}

/*Functions to display numbers in locale of the user */

function locale_number_format($Number, $DecimalPlaces = 0) {
	global $DecimalPoint;
	global $ThousandsSeparator;
	if (substr($_SESSION['Language'], 3, 2) == 'IN') { // If country is India (??_IN.utf8). See Indian Numbering System in Manual, Multilanguage, Technical Overview.
		return indian_number_format(floatval($Number), $DecimalPlaces);
	} else {
		if (!is_numeric($DecimalPlaces) and $DecimalPlaces == 'Variable') {
			$DecimalPlaces = mb_strlen($Number) - mb_strlen(intval($Number));
			if ($DecimalPlaces > 0) {
				$DecimalPlaces--;
			}
		}
		return number_format(floatval($Number), $DecimalPlaces, $DecimalPoint, $ThousandsSeparator);
	}
}

/* and to parse the input of the user into useable number */

function filter_number_format($Number) {
	global $DecimalPoint;
	global $ThousandsSeparator;
	$SQLFormatNumber = str_replace($DecimalPoint, '.', str_replace($ThousandsSeparator, '', trim($Number)));
	/*It is possible if the user entered the $DecimalPoint as a thousands separator and the $DecimalPoint is a comma that the result of this could contain several periods "." so need to ditch all but the last "." */
	if (mb_substr_count($SQLFormatNumber, '.') > 1) {
		return str_replace('.', '', mb_substr($SQLFormatNumber, 0, mb_strrpos($SQLFormatNumber, '.'))) . mb_substr($SQLFormatNumber, mb_strrpos($SQLFormatNumber, '.'));

		echo '<br /> Number of periods: ' . $NumberOfPeriods . ' $SQLFormatNumber = ' . $SQLFormatNumber;

	} else {
		return $SQLFormatNumber;
	}
}

function indian_number_format($Number, $DecimalPlaces) {
	$IntegerNumber = intval($Number);
	$DecimalValue = $Number - $IntegerNumber;
	if ($DecimalPlaces != 'Variable') {
		$DecimalValue = round($DecimalValue, $DecimalPlaces);
	}
	if ($DecimalPlaces != 'Variable' and strlen(substr($DecimalValue, 2)) > 0) {
		/*If the DecimalValue is longer than '0.' then chop off the leading 0*/
		$DecimalValue = substr($DecimalValue, 1);
		if ($DecimalPlaces > 0) {
			$DecimalValue = str_pad($DecimalValue, $DecimalPlaces, '0');
		} else {
			$DecimalValue = '';
		}
	} else {
		if ($DecimalPlaces != 'Variable' and $DecimalPlaces > 0) {
			$DecimalValue = '.' . str_pad($DecimalValue, $DecimalPlaces, '0');
		} elseif ($DecimalPlaces == 0) {
			$DecimalValue = '';
		}
	}
	if (strlen($IntegerNumber) > 3) {
		$LastThreeNumbers = substr($IntegerNumber, strlen($IntegerNumber) - 3, strlen($IntegerNumber));
		$RestUnits = substr($IntegerNumber, 0, strlen($IntegerNumber) - 3); // extracts the last three digits
		$RestUnits = ((strlen($RestUnits) % 2) == 1) ? '0' . $RestUnits : $RestUnits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
		$FirstPart = '';
		$ExplodedUnits = str_split($RestUnits, 2);
		for ($i = 0;$i < sizeof($ExplodedUnits);$i++) {
			if ($i == 0) {
				$FirstPart.= intval($ExplodedUnits[$i]) . ','; // creates each of the 2's group and adds a comma to the end

			} else {
				$FirstPart.= $ExplodedUnits[$i] . ',';

			}
		}
		return $FirstPart . $LastThreeNumbers . $DecimalValue;
	} else {
		return $IntegerNumber . $DecimalValue;
	}
}

function SendMailBySmtp(&$mail, $To) {
	if (IsEmailAddress($_SESSION['SMTPSettings']['username'])) { //user has set the fully mail address as user name
		$SendFrom = $_SESSION['SMTPSettings']['username'];
	} else { //user only set it's name instead of fully mail address
		if (strpos($_SESSION['SMTPSettings']['host'], 'mail') !== false) {
			$SubStr = 'mail';

		} elseif (strpos($_SESSION['SMTPSettings']['host'], 'smtp') !== false) {
			$SubStr = 'smtp';
		}

		$Domain = substr($_SESSION['SMTPSettings']['host'], strpos($_SESSION['SMTPSettings']['host'], $SubStr) + 5);
		$SendFrom = $_SESSION['SMTPSettings']['username'] . '@' . $Domain;
	}
	$mail->setFrom($SendFrom);
	$Result = $mail->send($To, 'smtp');
	return $Result;
}

function GetMailList($MailGroup) {
	$ToList = array();
	$SQL = "SELECT email,realname
			FROM mailgroupdetails INNER JOIN www_users
			ON www_users.userid=mailgroupdetails.userid
			WHERE mailgroupdetails.groupname='" . $MailGroup . "'";
	$ErrMsg = _('Failed to retrieve mail lists');
	$Result = DB_query($SQL, $ErrMsg);
	if (DB_num_rows($Result) != 0) {
		//Create the string which meets the Recipients requirements
		while ($MyRow = DB_fetch_array($Result)) {
			$ToList[] = $MyRow['realname'] . '<' . $MyRow['email'] . '>';
		}
	}
	return $ToList;
}

function ChangeFieldInTable($TableName, $FieldName, $OldValue, $NewValue) {
	/* Used in Z_ scripts to change one field across the table.
	*/
	echo '<br />' . _('Changing') . ' ' . $TableName . ' ' . _('records');
	$SQL = "UPDATE " . $TableName . " SET " . $FieldName . " ='" . $NewValue . "' WHERE " . $FieldName . "='" . $OldValue . "'";
	$DbgMsg = _('The SQL statement that failed was');
	$ErrMsg = _('The SQL to update' . ' ' . $TableName . ' ' . _('records failed'));
	$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
	echo ' ... ' . _('completed');
}

/* Used in report scripts for standard periods.
 * Parameter $Choice is from the 'Period' combobox value.
*/
function ReportPeriodList($Choice, $Options = array('t', 'l', 'n')) {
	$Periods = array();

	if (in_array('t', $Options)) {
		$Periods[] = _('This Month');
		$Periods[] = _('This Year');
		$Periods[] = _('This Financial Year');
	}

	if (in_array('l', $Options)) {
		$Periods[] = _('Last Month');
		$Periods[] = _('Last Year');
		$Periods[] = _('Last Financial Year');
	}

	if (in_array('n', $Options)) {
		$Periods[] = _('Next Month');
		$Periods[] = _('Next Year');
		$Periods[] = _('Next Financial Year');
	}

	$Count = count($Periods);

	$HTML = '<select name="Period">
				<option value=""></option>';

	for ($x = 0;$x < $Count;++$x) {
		if (!empty($Choice) && $Choice == $Periods[$x]) {
			$HTML.= '<option value="' . $Periods[$x] . '" selected>' . $Periods[$x] . '</option>';
		} else {
			$HTML.= '<option value="' . $Periods[$x] . '">' . $Periods[$x] . '</option>';
		}
	}

	$HTML.= '</select>';

	return $HTML;
}

function ReportPeriod($PeriodName, $FromOrTo) {
	/* Used in report scripts to determine period.
	*/
	$ThisMonth = date('m');
	$ThisYear = date('Y');
	$LastMonth = $ThisMonth - 1;
	$LastYear = $ThisYear - 1;
	$NextMonth = $ThisMonth + 1;
	$NextYear = $ThisYear + 1;
	// Find total number of days in this month:
	$TotalDays = cal_days_in_month(CAL_GREGORIAN, $ThisMonth, $ThisYear);
	// Find total number of days in last month:
	$TotalDaysLast = cal_days_in_month(CAL_GREGORIAN, $LastMonth, $ThisYear);
	// Find total number of days in next month:
	$TotalDaysNext = cal_days_in_month(CAL_GREGORIAN, $NextMonth, $ThisYear);
	switch ($PeriodName) {
		case _('This Month'):
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $ThisMonth, 1, $ThisYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $ThisMonth, $TotalDays, $ThisYear));
		break;
		case _('This Quarter'):
			$QtrStrt = intval(($ThisMonth - 1) / 3) * 3 + 1;
			$QtrEnd = intval(($ThisMonth - 1) / 3) * 3 + 3;
			if ($QtrEnd == 4 or $QtrEnd == 6 or $QtrEnd == 9 or $QtrEnd == 11) {
				$TotalDays = 30;
			}
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $QtrStrt, 1, $ThisYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $QtrEnd, $TotalDays, $ThisYear));
		break;
		case _('This Year'):
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, 1, 1, $ThisYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, 12, 31, $ThisYear));
		break;
		case _('This Financial Year'):
			if (Date('m') > $_SESSION['YearEnd']) {
				$DateStart = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, $_SESSION['YearEnd'] + 1, 1, Date('Y')));
			} else {
				$DateStart = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, $_SESSION['YearEnd'] + 1, 1, Date('Y') - 1));
			}
			$DateEnd = date($_SESSION['DefaultDateFormat'], YearEndDate($_SESSION['YearEnd'], 0));
		break;
		case _('Last Month'):
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $LastMonth, 1, $ThisYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $LastMonth, $TotalDaysLast, $ThisYear));
		break;
		case _('Last Quarter'):
			$QtrStrt = intval(($ThisMonth - 1) / 3) * 3 - 2;
			$QtrEnd = intval(($ThisMonth - 1) / 3) * 3 + 0;
			if ($QtrEnd == 4 or $QtrEnd == 6 or $QtrEnd == 9 or $QtrEnd == 11) {
				$TotalDays = 30;
			}
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $QtrStrt, 1, $ThisYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $QtrEnd, $TotalDays, $ThisYear));
		break;
		case _('Last Year'):
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, 1, 1, $LastYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, 12, 31, $LastYear));
		break;
		case _('Last Financial Year'):
			if (Date('m') > $_SESSION['YearEnd']) {
				$DateStart = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, $_SESSION['YearEnd'] + 1, 1, Date('Y') - 1));
			} else {
				$DateStart = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, $_SESSION['YearEnd'] + 1, 1, Date('Y') - 2));
			}
			$DateEnd = date($_SESSION['DefaultDateFormat'], YearEndDate($_SESSION['YearEnd'], -1));
		break;
		case _('Next Month'):
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $NextMonth, 1, $ThisYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $NextMonth, $TotalDaysNext, $ThisYear));
		break;
		case _('Next Quarter'):
			$QtrStrt = intval(($ThisMonth - 1) / 3) * 3 + 4;
			$QtrEnd = intval(($ThisMonth - 1) / 3) * 3 + 6;
			if ($QtrEnd == 4 or $QtrEnd == 6 or $QtrEnd == 9 or $QtrEnd == 11) {
				$TotalDays = 30;
			}
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $QtrStrt, 1, $ThisYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $QtrEnd, $TotalDays, $ThisYear));
		break;
		case _('Next Year'):
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, 1, 1, $NextYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, 12, 31, $NextYear));
		break;
		case _('Next Financial Year'):
			if (Date('m') > $_SESSION['YearEnd']) {
				$DateStart = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, $_SESSION['YearEnd'] + 1, 1, Date('Y') + 1));
			} else {
				$DateStart = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, $_SESSION['YearEnd'] + 1, 1, Date('Y')));
			}
			$DateEnd = date($_SESSION['DefaultDateFormat'], YearEndDate($_SESSION['YearEnd'], 1));
		break;
		default:
			$DateStart = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $LastMonth, 1, $ThisYear));
			$DateEnd = date($_SESSION['DefaultDateFormat'], mktime(0, 0, 0, $LastMonth, $TotalDaysLast, $ThisYear));
		break;
	}
	if ($FromOrTo == 'From') {
		$Period = GetPeriod($DateStart);
	} else {
		$Period = GetPeriod($DateEnd);
	}
	return $Period;
}

function FYStartPeriod($PeriodNumber) {
	$SQL = "SELECT lastdate_in_period FROM periods WHERE periodno='" . $PeriodNumber . "'";
	$Result = DB_query($SQL);
	$MyRow = DB_fetch_array($Result);
	$DateArray = explode('-', $MyRow['lastdate_in_period']);
	if ($DateArray[1] > $_SESSION['YearEnd']) {
		$DateStart = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, $_SESSION['YearEnd'] + 1, 1, $DateArray[0]));
	} else {
		$DateStart = Date($_SESSION['DefaultDateFormat'], Mktime(0, 0, 0, $_SESSION['YearEnd'] + 1, 1, $DateArray[0] - 1));
	}
	$StartPeriod = GetPeriod($DateStart);
	return $StartPeriod;
}

function fShowFieldHelp($HelpText) {
	// Shows field help text if $_SESSION['ShowFieldHelp'] is TRUE or is not set.
	if ($_SESSION['ShowFieldHelp'] || !isset($_SESSION['ShowFieldHelp'])) {
		echo '<span class="field_help_text">', $HelpText, '</span>';
	}
	return;
}

function fShowPageHelp($HelpText) {
	// Shows page help text if $_SESSION['ShowFieldHelp'] is TRUE or is not set.
	if ($_SESSION['ShowPageHelp'] || !isset($_SESSION['ShowPageHelp'])) {
		echo '<div class="page_help_text">', $HelpText, '</div><br />';
	}
	return;
}

?>
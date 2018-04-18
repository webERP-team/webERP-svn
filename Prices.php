<?php

include('includes/session.php');
$Title = _('Item Prices');
$ViewTopic = 'Prices';
/*$BookMark = '';// Anchor's id in the manual's html document.*/
include('includes/header.php');
echo '<p class="page_title_text"><img alt="" src="' . $RootPath . '/css/' . $Theme .
		'/images/money_add.png" title="' .
		_('Search') . '" />' . ' ' .
		$Title . '</p>';

echo '<a href="' . $RootPath . '/SelectProduct.php">' . _('Back to Items') . '</a><br />';

include('includes/SQL_CommonFunctions.inc');

//initialise no input errors assumed initially before we test
$InputError = 0;

if (isset($_GET['Item'])) {
	$Item = trim(mb_strtoupper($_GET['Item']));
} elseif (isset($_POST['Item'])) {
	$Item = trim(mb_strtoupper($_POST['Item']));
}

if (!isset($_POST['TypeAbbrev']) OR $_POST['TypeAbbrev']=='') {
	$_POST['TypeAbbrev'] = $_SESSION['DefaultPriceList'];
}

if (!isset($_POST['CurrAbrev'])) {
	$_POST['CurrAbrev'] = $_SESSION['CompanyRecord']['currencydefault'];
}

$result = DB_query("SELECT stockmaster.description,
							stockmaster.mbflag
					FROM stockmaster
					WHERE stockmaster.stockid='".$Item."'");
$myrow = DB_fetch_row($result);

if (DB_num_rows($result)==0) {
	prnMsg( _('The part code entered does not exist in the database') . '. ' . _('Only valid parts can have prices entered against them'),'error');
	$InputError=1;
}

if (!isset($Item)) {
	echo '<p>';
	prnMsg (_('An item must first be selected before this page is called') . '. ' . _('The product selection page should call this page with a valid product code'),'error');
	include('includes/footer.php');
	exit;
}

$PartDescription = $myrow[0];

if ($myrow[1]=='K') {
	prnMsg(_('The part selected is a kit set item') .', ' . _('these items explode into their components when selected on an order') . ', ' . _('prices must be set up for the components and no price can be set for the whole kit'),'error');
	exit;
}

if (isset($_POST['submit'])) {

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	// This gives some date in 1999?? $ZeroDate = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,0,0,0));

	if (!is_numeric(filter_number_format($_POST['Price'])) OR $_POST['Price']=='') {
		$InputError = 1;
		prnMsg( _('The price entered must be numeric'),'error');
	}
	if (! Is_Date($_POST['StartDate'])){
		$InputError =1;
		prnMsg (_('The date this price is to take effect from must be entered in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
	}
	if ($_POST['EndDate']!='') {
		if (FormatDateForSQL($_POST['EndDate'])!='9999-12-31'){
			if (! Is_Date($_POST['EndDate']) AND $_POST['EndDate']!=''){
				$InputError =1;
				prnMsg (_('The date this price is be in effect to must be entered in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
			}
			if (Date1GreaterThanDate2($_POST['StartDate'],$_POST['EndDate']) AND $_POST['EndDate']!='' AND FormatDateForSQL($_POST['EndDate'])!='9999-12-31'){
				$InputError =1;
				prnMsg (_('The end date is expected to be after the start date, enter an end date after the start date for this price'),'error');
			}
			if (Date1GreaterThanDate2(Date($_SESSION['DefaultDateFormat']),$_POST['EndDate']) AND $_POST['EndDate']!='' AND FormatDateForSQL($_POST['EndDate'])!='9999-12-31'){
				$InputError =1;
				prnMsg(_('The end date is expected to be after today. There is no point entering a new price where the effective date is before today!'),'error');
			}
		}
	}
	if (Is_Date($_POST['EndDate'])){
		$SQLEndDate = FormatDateForSQL($_POST['EndDate']);
	} else {
		$SQLEndDate = '9999-12-31';
	}

	$sql = "SELECT COUNT(typeabbrev)
				FROM prices
			WHERE prices.stockid='".$Item."'
			AND startdate='" .FormatDateForSQL($_POST['StartDate']) . "'
			AND enddate ='" . $SQLEndDate . "'
			AND prices.typeabbrev='" . $_POST['TypeAbbrev'] . "'
			AND prices.currabrev='" . $_POST['CurrAbrev'] . "'";

	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);

	if ($myrow[0]!=0 AND !isset($_POST['OldTypeAbbrev']) AND !isset($_POST['OldCurrAbrev'])) {
		prnMsg( _('This price has already been entered. To change it you should edit it') , 'warn');
		$InputError =1;
	}


	if (isset($_POST['OldTypeAbbrev']) AND isset($_POST['OldCurrAbrev']) AND mb_strlen($Item)>1 AND $InputError !=1) {

		/* Need to see if there is also a price entered that has an end date after the start date of this price and if so we will need to update it so there is no ambiguity as to which price will be used*/

		//editing an existing price
		$sql = "UPDATE prices SET
					typeabbrev='" . $_POST['TypeAbbrev'] . "',
					currabrev='" . $_POST['CurrAbrev'] . "',
					price='" . filter_number_format($_POST['Price']) . "',
					startdate='" . FormatDateForSQL($_POST['StartDate']) . "',
					enddate='" . $SQLEndDate . "'
				WHERE prices.stockid='".$Item."'
				AND startdate='" .$_POST['OldStartDate'] . "'
				AND enddate ='" . $_POST['OldEndDate'] . "'
				AND prices.typeabbrev='" . $_POST['OldTypeAbbrev'] . "'
				AND prices.currabrev='" . $_POST['OldCurrAbrev'] . "'
				AND prices.debtorno=''";

		$ErrMsg = _('Could not be update the existing prices');
		$result = DB_query($sql,$ErrMsg);

		ReSequenceEffectiveDates ($Item, $_POST['TypeAbbrev'], $_POST['CurrAbrev']) ;

		prnMsg(_('The price has been updated'),'success');

	} elseif ($InputError !=1) {

	/*Selected price is null cos no item selected on first time round so must be adding a	record must be submitting new entries in the new price form */

		$sql = "INSERT INTO prices (stockid,
									typeabbrev,
									currabrev,
									startdate,
									enddate,
									price)
							VALUES ('" . $Item . "',
								'" . $_POST['TypeAbbrev'] . "',
								'" . $_POST['CurrAbrev'] . "',
								'" . FormatDateForSQL($_POST['StartDate']) . "',
								'" . $SQLEndDate. "',
								'" . filter_number_format($_POST['Price']) . "')";
		$ErrMsg = _('The new price could not be added');
		$result = DB_query($sql,$ErrMsg);

		ReSequenceEffectiveDates ($Item, $_POST['TypeAbbrev'], $_POST['CurrAbrev']) ;
		prnMsg(_('The new price has been inserted'),'success');
	}

	unset($_POST['Price']);
	unset($_POST['StartDate']);
	unset($_POST['EndDate']);

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

	$sql="DELETE FROM prices
			WHERE prices.stockid = '". $Item ."'
			AND prices.typeabbrev='". $_GET['TypeAbbrev'] ."'
			AND prices.currabrev ='". $_GET['CurrAbrev'] ."'
			AND  prices.startdate = '" .$_GET['StartDate'] . "'
			AND  prices.enddate = '" . $_GET['EndDate'] . "'
			AND prices.debtorno=''";
	$ErrMsg = _('Could not delete this price');
	$result = DB_query($sql,$ErrMsg);
	prnMsg( _('The selected price has been deleted'),'success');

}

//Always do this stuff

$sql = "SELECT
		currencies.currency,
        salestypes.sales_type,
		prices.price,
		prices.stockid,
		prices.typeabbrev,
		prices.currabrev,
		prices.startdate,
		prices.enddate,
		currencies.decimalplaces AS currdecimalplaces
	FROM prices
	INNER JOIN salestypes
		ON prices.typeabbrev = salestypes.typeabbrev
	INNER JOIN currencies
		ON prices.currabrev=currencies.currabrev
	WHERE prices.stockid='".$Item."'
	AND prices.debtorno=''
	ORDER BY prices.currabrev,
		prices.typeabbrev,
		prices.startdate";

$result = DB_query($sql);
require_once('includes/CurrenciesArray.php');
if (DB_num_rows($result) > 0) {
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
		<div>
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<table class="selection">
		<thead>
			<tr>
				<th colspan="7">' .
				_('Pricing for part') . ':
				<input type="text" required="required" autofocus="autofocus" name="Item" size="22" value="' . $Item . '" maxlength="20" />
				<input type="submit" name="NewPart" value="' . _('Review Prices') . '" /></th>
			</tr>
			<tr><th class="ascending">' . _('Currency') . '</th>
				<th class="ascending">' . _('Sales Type') . '</th>
				<th class="ascending">' . _('Price') . '</th>
				<th class="ascending">' . _('Start Date') . ' </th>
				<th class="ascending">' . _('End Date') . '</th>';
	if (in_array(5, $_SESSION['AllowedPageSecurityTokens'])) { // If is allow to modify prices.
		echo   '<th colspan="2">' . _('Maintenance') . '</th>';
	}
	echo '</tr>
		</thead>
		<tbody>';

	while ($myrow = DB_fetch_array($result)) {
		if ($myrow['enddate']=='9999-12-31'){
			$EndDateDisplay = _('No End Date');
		} else {
			$EndDateDisplay = ConvertSQLDate($myrow['enddate']);
		}

		echo '<tr class="striped_row">
				<td>' . $CurrencyName[$myrow['currabrev']] . '</td>
				<td>' .  $myrow['sales_type'] . '</td>
				<td class="number">' . locale_number_format($myrow['price'], $myrow['currdecimalplaces']+2) . '</td>
				<td>' . ConvertSQLDate($myrow['startdate']) . '</td>
				<td>' . $EndDateDisplay . '</td>';

		/*Only allow access to modify prices if securiy token 5 is allowed */
		if (in_array(5, $_SESSION['AllowedPageSecurityTokens'])) {
			echo '<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Item=' . $myrow['stockid'] . '&amp;TypeAbbrev=' . $myrow['typeabbrev'] . '&amp;CurrAbrev=' . $myrow['currabrev'] . '&amp;Price=' . locale_number_format($myrow['price'],$myrow['currdecimalplaces']) . '&amp;StartDate=' . $myrow['startdate'] . '&amp;EndDate=' . $myrow['enddate'] . '&amp;Edit=1">' . _('Edit') . '</a></td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Item=' . $myrow['stockid'] . '&amp;TypeAbbrev=' . $myrow['typeabbrev'] . '&amp;CurrAbrev=' . $myrow['currabrev'] . '&amp;StartDate=' . $myrow['startdate'] . '&amp;EndDate=' . $myrow['enddate'] . '&amp;delete=yes" onclick="return confirm(\'' . _('Are you sure you wish to delete this price?') . '\');">' . _('Delete') . '</a></td>';
		}
		echo '</tr>';

	}
	//END WHILE LIST LOOP
	echo '</tbody>
		</table><br />
		</div>
		  </form>';
} else {
	prnMsg(_('There are no prices set up for this part'),'warn');
}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
if (isset($_GET['Edit'])){
	echo '<input type="hidden" name="OldTypeAbbrev" value="' . $_GET['TypeAbbrev'] .'" />';
	echo '<input type="hidden" name="OldCurrAbrev" value="' . $_GET['CurrAbrev'] . '" />';
	echo '<input type="hidden" name="OldStartDate" value="' . $_GET['StartDate'] . '" />';
	echo '<input type="hidden" name="OldEndDate" value="' . $_GET['EndDate'] . '" />';
	$_POST['CurrAbrev'] = $_GET['CurrAbrev'];
	$_POST['TypeAbbrev'] = $_GET['TypeAbbrev'];
	/*the price sent with the get is sql format price so no need to filter */
	$_POST['Price'] = $_GET['Price'];
	$_POST['StartDate'] = ConvertSQLDate($_GET['StartDate']);
	if ($_GET['EndDate']=='' OR $_GET['EndDate']=='9999-12-31'){
		$_POST['EndDate'] = '';
	} else {
		$_POST['EndDate'] = ConvertSQLDate($_GET['EndDate']);
	}
}

$SQL = "SELECT currabrev FROM currencies";
$result = DB_query($SQL);

echo '<br /><table class="selection">';
echo '<tr><th colspan="5"><h3>' . $Item . ' - ' . $PartDescription . '</h3></th></tr>';
echo '<tr><td>' . _('Currency') . ':</td>
			<td><select name="CurrAbrev">';
while ($myrow = DB_fetch_array($result)) {
	echo '<option ';
	if ($myrow['currabrev']==$_POST['CurrAbrev']) {
		echo 'selected="selected" ';
	}
	echo 'value="' . $myrow['currabrev'] . '">' . $CurrencyName[$myrow['currabrev']] . '</option>';
}// End while loop
echo			'</select></td></tr>';

DB_free_result($result);

echo '<tr>
			<td>' . _('Sales Type Price List') . ':</td>
			<td><select name="TypeAbbrev">';

$SQL = "SELECT typeabbrev, sales_type FROM salestypes";
$result = DB_query($SQL);

while ($myrow = DB_fetch_array($result)) {
	echo '<option ';
	if ($myrow['typeabbrev']==$_POST['TypeAbbrev']) {
		echo 'selected="selected" ';
	}
	echo 'value="' . $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
}// End while loop
echo			'</select></td></tr>';

DB_free_result($result);

if (!isset($_POST['StartDate'])){
	$_POST['StartDate'] = Date($_SESSION['DefaultDateFormat']);
}
if (!isset($_POST['EndDate'])){
	$_POST['EndDate'] = '';
}
echo '<tr><td>' . _('Price Effective From Date')  . ':</td>
			<td><input type="text" class="date" name="StartDate" required="required" size="10" maxlength="10" title="' . _('Enter the date from which this price should take effect.') . '" value="' . $_POST['StartDate'] . '" /></td></tr>';
echo '<tr><td>' . _('Price Effective To Date')  . ':</td>
			<td><input type="text" class="date" name="EndDate" size="10" maxlength="10" title="' . _('Enter the date to which this price should be in effect to, or leave empty if the price should continue indefinitely') . '" value="' . $_POST['EndDate'] . '" />';
echo '<input type="hidden" name="Item" value="' . $Item.'" /></td></tr>';
echo '<tr><td>' . _('Price') . ':</td>
          <td>
          <input type="text" class="number" required="required" name="Price" size="12" maxlength="11" value="';
          if (isset($_POST['Price'])) {
	         echo $_POST['Price'];
          }
          echo '" />
     </td></tr>
</table>
<br /><div class="centre">
<input type="submit" name="submit" value="' . _('Enter') . '/' . _('Amend Price') . '" />
</div>';


echo '</div>
      </form>';
include('includes/footer.php');


function ReSequenceEffectiveDates ($Item, $PriceList, $CurrAbbrev) {

	/*This is quite complicated - the idea is that prices set up should be unique and there is no way two prices could be returned as valid - when getting a price in includes/GetPrice.inc the logic is to first look for a price of the salestype/currency within the effective start and end dates - then if not get the price with a start date prior but a blank end date (the default price). We would not want two prices where one price falls inside another effective date range except in the case of a blank end date - ie no end date - the default price for the currency/salestype.
	I first thought that we would need to update the previous default price (blank end date), when a new default price is entered, to have an end date of the startdate of this new default price less 1 day - but this is  converting a default price into a special price which could result in having two special prices over the same date range - best to leave it unchanged and use logic in the GetPrice.inc to ensure the correct default price is returned
	*
	* After further discussion (Ricard) if the new price has a blank end date - i.e. no end then the pre-existing price with no end date should be changed to have an end date just prior to the new default (no end date) price commencing
	*/
	//this is just the case where debtorno='' - see the Prices_Customer.php script for customer special prices
		$SQL = "SELECT price,
						startdate,
						enddate
				FROM prices
				WHERE debtorno=''
				AND stockid='" . $Item . "'
				AND currabrev='" . $CurrAbbrev . "'
				AND typeabbrev='" . $PriceList . "'
				AND enddate <>'9999-12-31'
				ORDER BY startdate, enddate";
		$result = DB_query($SQL);

		while ($myrow = DB_fetch_array($result)){
			if (isset($NextStartDate)){
				if (Date1GreaterThanDate2(ConvertSQLDate($myrow['startdate']),$NextStartDate)){
					$NextStartDate = ConvertSQLDate($myrow['startdate']);
					//Only if the previous enddate is after the new start date do we need to look at updates
					if (Date1GreaterThanDate2(ConvertSQLDate($EndDate),ConvertSQLDate($myrow['startdate']))) {
						/*Need to make the end date the new start date less 1 day */
						$SQL = "UPDATE prices SET enddate = '" . FormatDateForSQL(DateAdd($NextStartDate,'d',-1))  . "'
										WHERE stockid ='" .$Item . "'
										AND currabrev='" . $CurrAbbrev . "'
										AND typeabbrev='" . $PriceList . "'
										AND startdate ='" . $StartDate . "'
										AND enddate = '" . $EndDate . "'
										AND debtorno =''";
						$UpdateResult = DB_query($SQL);
					}
				} //end of if startdate  after NextStartDate - we have a new NextStartDate
			} //end of if set NextStartDate
				else {
					$NextStartDate = ConvertSQLDate($myrow['startdate']);
			}
			$StartDate = $myrow['startdate'];
			$EndDate = $myrow['enddate'];
		} // end of loop around all prices

		//Now look for duplicate prices with no end
		$SQL = "SELECT price,
						startdate,
						enddate
					FROM prices
					WHERE debtorno=''
					AND stockid='" . $Item . "'
					AND currabrev='" . $CurrAbbrev . "'
					AND typeabbrev='" . $PriceList . "'
					AND enddate ='9999-12-31'
					ORDER BY startdate";
		$result = DB_query($SQL);

		while ($myrow = DB_fetch_array($result)) {
			if (isset($OldStartDate)){
			/*Need to make the end date the new start date less 1 day */
				$NewEndDate = FormatDateForSQL(DateAdd(ConvertSQLDate($myrow['startdate']),'d',-1));
				$SQL = "UPDATE prices SET enddate = '" . $NewEndDate  . "'
							WHERE stockid ='" .$Item . "'
							AND currabrev='" . $CurrAbbrev . "'
							AND typeabbrev='" . $PriceList . "'
							AND startdate ='" . $OldStartDate . "'
							AND enddate = '9999-12-31'
							AND debtorno =''";
				$UpdateResult = DB_query($SQL);
			}
			$OldStartDate = $myrow['startdate'];
		} // end of loop around duplicate no end date prices

} // end function ReSequenceEffectiveDates

?>

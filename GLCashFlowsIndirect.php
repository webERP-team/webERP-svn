<?php
// GLCashFlowsIndirect.php
// Shows a statement of cash flows for the period using the indirect method.
// This program is under the GNU General Public License, last version. 2016-10-08.
// This creative work is under the CC BY-NC-SA, last version. 2016-10-08.

/*
Info about a statement of cash flows using the indirect method: IAS 7 - Statement of Cash Flows.

Parameters:
	PeriodFrom: Select the beginning of the reporting period.
	PeriodTo: Select the end of the reporting period.
	Period: Select a period instead of using the beginning and end of the reporting period.
	ShowBudget: Check this box to show the budget for the period.
{	ShowDetail: Check this box to show all accounts instead a summary. Not used in this script.}
	ShowZeroBalance: Check this box to show all accounts including those with zero balance.
	ShowCash: Check this box to show cash and cash equivalents accounts.
	NewReport: Click this button to start a new report.
	IsIncluded: Parameter to indicate that a script is included within another.
*/

// BEGIN: Functions division ===================================================
function CashFlowsActivityName($Activity) {
	// Converts the cash flow activity number to an activity text.
	switch($Activity) {
		case -1: return _('Not set up');
		case 0: return _('No effect on cash flow');
		case 1: return _('Operating activities');
		case 2: return _('Investing activities');
		case 3: return _('Financing activities');
		case 4: return _('Cash or cash equivalent');
		default: return _('Unknown');
	}
}
function colDebitCredit($Amount) {
	// Function to display in debit or Credit columns in a HTML table.
	if($Amount < 0) {
		return '<td class="number">' . locale_number_format($Amount, $_SESSION['CompanyRecord']['decimalplaces']) . '</td><td>&nbsp;</td>';// Outflow.
	} else {
		return '<td>&nbsp;</td><td class="number">' . locale_number_format($Amount, $_SESSION['CompanyRecord']['decimalplaces']) . '</td>';// Inflow.
	}
}
// END: Functions division =====================================================

// BEGIN: Procedure division ===================================================
if(!isset($IsIncluded)) {// Runs normally if this script is NOT included in another.
	include('includes/session.php');
}
$Title = _('Statement of Cash Flows, Indirect Method');
if(!isset($IsIncluded)) {// Runs normally if this script is NOT included in another.
	$ViewTopic = 'GeneralLedger';
	$BookMark = 'GLCashFlowsIndirect';
	include('includes/header.php');
}

// Merges gets into posts:
if(isset($_GET['PeriodFrom'])) {
	$_POST['PeriodFrom'] = $_GET['PeriodFrom'];
}
if(isset($_GET['PeriodTo'])) {
	$_POST['PeriodTo'] = $_GET['PeriodTo'];
}
if(isset($_GET['ShowBudget'])) {
	$_POST['ShowBudget'] = $_GET['ShowBudget'];
}
if(isset($_GET['ShowZeroBalance'])) {
	$_POST['ShowZeroBalance'] = $_GET['ShowZeroBalance'];
}
if(isset($_GET['ShowCash'])) {
	$_POST['ShowCash'] = $_GET['ShowCash'];
}

// Sets PeriodFrom and PeriodTo from Period:
if($_POST['Period'] != '') {
	$_POST['PeriodFrom'] = ReportPeriod($_POST['Period'], 'From');
	$_POST['PeriodTo'] = ReportPeriod($_POST['Period'], 'To');
}

// Validates the data submitted in the form:
if($_POST['PeriodFrom'] > $_POST['PeriodTo']) {
	// The beginning is after the end.
	$_POST['NewReport'] = 'on';
	prnMsg(_('The beginning of the period should be before or equal to the end of the period. Please reselect the reporting period.'), 'error');
}
if($_POST['PeriodTo']-$_POST['PeriodFrom']+1 > 12) {
	// The reporting period is greater than 12 months.
	$_POST['NewReport'] = 'on';
	prnMsg(_('The period should be 12 months or less in duration. Please select an alternative period range.'), 'error');
}

// Main code:
if(isset($_POST['PeriodFrom']) AND isset($_POST['PeriodTo']) AND !$_POST['NewReport']) {
	// If PeriodFrom and PeriodTo are set and it is not a NewReport, generates the report:
	echo '<div class="sheet">';// Division to identify the report block.
	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/gl.png" title="', // Icon image.
		$Title, '" /> ', // Icon title.
		// Page title as IAS1 numerals 10 and 51:
		$Title, '<br />', // Page title, reporting statement.
		stripslashes($_SESSION['CompanyRecord']['coyname']), '<br />'; // Page title, reporting entity.
	$Result = DB_query('SELECT lastdate_in_period FROM `periods` WHERE `periodno`=' . $_POST['PeriodFrom']);
	$PeriodFromName = DB_fetch_array($Result);
	$Result = DB_query('SELECT lastdate_in_period FROM `periods` WHERE `periodno`=' . $_POST['PeriodTo']);
	$PeriodToName = DB_fetch_array($Result);
	echo _('From'), ' ', MonthAndYearFromSQLDate($PeriodFromName['lastdate_in_period']), ' ', _('to'), ' ', MonthAndYearFromSQLDate($PeriodToName['lastdate_in_period']), '<br />'; // Page title, reporting period.
	include_once('includes/CurrenciesArray.php');// Array to retrieve currency name.
	echo _('All amounts stated in'), ': ', _($CurrencyName[$_SESSION['CompanyRecord']['currencydefault']]), '</p>';// Page title, reporting presentation currency and level of rounding used.
	echo '<table class="selection">',
		// Content of the header and footer of the output table:
		'<thead>
			<tr>
				<th>', _('Account'), '</th>
				<th>', _('Account Name'), '</th>
				<th colspan="2">', _('Period Actual'), '</th>';
	// Initialise section accumulators:
	$ActualSection = 0;
	$ActualTotal = 0;
	$LastSection = 0;
	$LastTotal = 0;

	// Gets the net profit for the period GL account:
	if(!isset($_SESSION['PeriodProfitAccount'])) {
		$_SESSION['PeriodProfitAccount'] = '';
		$MyRow = DB_fetch_array(DB_query("SELECT confvalue FROM `config` WHERE confname ='PeriodProfitAccount'"));
		if($MyRow) {
			$_SESSION['PeriodProfitAccount'] = $MyRow['confvalue'];
		}
	}
	// Gets the retained earnings GL account:
	if(!isset($_SESSION['RetainedEarningsAccount'])) {
		$_SESSION['RetainedEarningsAccount'] = '';
/*		$MyRow = DB_fetch_array(DB_query("SELECT confvalue FROM `config` WHERE confname ='RetainedEarningsAccount'"));// RChacon: Standardise to config table? */
		$Result = DB_query("SELECT retainedearnings FROM companies WHERE coycode = 1");
		$MyRow = DB_fetch_array($Result);
		if($MyRow) {
/*			$_SESSION['RetainedEarningsAccount'] = $MyRow['confvalue'];// RChacon: See above comment */
			$_SESSION['RetainedEarningsAccount'] = $MyRow['retainedearnings'];
		}
	}
	include('includes/GLPostings.inc');// Posts pending GL transactions.
	// Outputs the table:
	if($_POST['ShowBudget']) {
		// Parameters: PeriodFrom, PeriodTo, ShowBudget=ON, ShowZeroBalance=on/off, ShowCash=on/off.
		// BEGIN Outputs the table with budget.
		// Code maintenance note: To update 'Outputs the table withOUT budget', copy 'Outputs the table with budget' and remove lines with 'budget'.
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++>>
		$BudgetSection = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
		$BudgetTotal = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
		$Columns = 8;// ShowBudget=ON vs. ShowBudget=OFF.*/
		$TableHead = '<th colspan="2">' . _('Period Budget') . '</th>' .
					'<th colspan="2">' . _('Last Year') . '</th>';// ShowBudget=ON vs. ShowBudget=OFF.*/
		echo $TableHead, '</tr>
			</thead><tfoot>
				<tr>',
					'<td class="text" colspan="',  $Columns, '">',// Prints an explanation of signs in actual and relative changes:
						'<br /><b>', _('Notes'), ':</b><br />',
						_('Cash flows signs: a negative number indicates a cash flow used in activities; a positive number indicates a cash flow provided by activities.'), '<br />';
		if($_POST['ShowCash']) {
			echo		_('Cash and cash equivalents signs: a negative number indicates a cash outflow; a positive number indicates a cash inflow.'), '<br />';
		}
		echo		'</td>
				</tr>
			</tfoot><tbody>';
		// Net profit − dividends = Retained earnings:
		echo '<tr>
				<td class="text" colspan="',  $Columns, '"><br /><h2>', _('Net profit and dividends'), '</h2></td>
			</tr>
			<tr class="striped_row">
				<td>&nbsp;</td>
				<td class="text">', _('Net profit for the period'), '</td>';
		// Net profit for the period:
		$SelectNetProfit = "Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN -chartdetails.budget ELSE 0 END) AS BudgetProfit,";// ShowBudget=ON vs. ShowBudget=OFF.*/
		$Sql = "SELECT
					Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN -chartdetails.actual ELSE 0 END) AS ActualProfit," .
					$SelectNetProfit .
					"Sum(CASE WHEN (chartdetails.period >= '" . ($_POST['PeriodFrom']-12) . "' AND chartdetails.period <= '" . ($_POST['PeriodTo']-12) . "') THEN -chartdetails.actual ELSE 0 END) AS LastProfit
				FROM chartmaster
					INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
					INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
				WHERE accountgroups.pandl=1";
		$Result = DB_query($Sql);
		$MyRow1 = DB_fetch_array($Result);
		echo	colDebitCredit($MyRow1['ActualProfit']),
				colDebitCredit($MyRow1['BudgetProfit']),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($MyRow1['LastProfit']),
			'</tr>
			<tr class="striped_row">
				<td>&nbsp;</td>
				<td class="text">', _('Dividends'), '</td>';
		// Dividends:
		$SelectDividends = "Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN chartdetails.budget ELSE 0 END) AS BudgetRetained,";// ShowBudget=ON vs. ShowBudget=OFF.*/
		$Sql = "SELECT
					Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN chartdetails.actual ELSE 0 END) AS ActualRetained," .
					$SelectDividends .
					"Sum(CASE WHEN (chartdetails.period >= '" . ($_POST['PeriodFrom']-12) . "' AND chartdetails.period <= '" . ($_POST['PeriodTo']-12) . "') THEN chartdetails.actual ELSE 0 END) AS LastRetained
				FROM chartmaster
					INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
					INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
				WHERE accountgroups.pandl=0
					AND chartdetails.accountcode!='" . $_SESSION['PeriodProfitAccount'] . "'
					AND chartdetails.accountcode!='" . $_SESSION['RetainedEarningsAccount'] . "'";// Gets retained earnings by the complement method to include differences. The complement method: Changes(retained earnings) = -Changes(other accounts).
		$Result = DB_query($Sql);
		$MyRow2 = DB_fetch_array($Result);
		echo	colDebitCredit($MyRow2['ActualRetained'] - $MyRow1['ActualProfit']),
				colDebitCredit($MyRow2['BudgetRetained'] - $MyRow1['BudgetProfit']),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($MyRow2['LastRetained'] - $MyRow1['LastProfit']),
			'</tr><tr>',
				'<td class="text" colspan="2">', _('Retained earnings'), '</td>',
		// Retained earnings changes:
					colDebitCredit($MyRow2['ActualRetained']),
					colDebitCredit($MyRow2['BudgetRetained']),// ShowBudget=ON vs. ShowBudget=OFF.*/
					colDebitCredit($MyRow2['LastRetained']),
			'</tr>';
		$ActualTotal += $MyRow2['ActualRetained'];
		$BudgetTotal += $MyRow2['BudgetRetained'];// ShowBudget=ON vs. ShowBudget=OFF.*/
		$LastTotal += $MyRow2['LastRetained'];
		// Cash flows sections:
		$SelectCashFlows = "Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN -chartdetails.budget ELSE 0 END) AS BudgetAmount,";// ShowBudget=ON vs. ShowBudget=OFF.*/
		$Sql = "SELECT
					chartmaster.cashflowsactivity,
					chartdetails.accountcode,
					chartmaster.accountname,
					Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN -chartdetails.actual ELSE 0 END) AS ActualAmount," .
					$SelectCashFlows .
					"Sum(CASE WHEN (chartdetails.period >= '" . ($_POST['PeriodFrom']-12) . "' AND chartdetails.period <= '" . ($_POST['PeriodTo']-12) . "') THEN -chartdetails.actual ELSE 0 END) AS LastAmount
				FROM chartmaster
					INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
					INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
				WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity!=4
				GROUP BY
					chartdetails.accountcode
				ORDER BY
					chartmaster.cashflowsactivity,
					chartdetails.accountcode";
		$Result = DB_query($Sql);
		$IdSection = -1;
		// Looks for an account without setting up:
		$NeedSetup = FALSE;
		while($MyRow = DB_fetch_array($Result)) {
			if($MyRow['cashflowsactivity'] == -1) {
				$NeedSetup = TRUE;
				echo '<tr><td colspan="',  $Columns, '">&nbsp;</td></tr>';
				break;
			}
		}
		DB_data_seek($Result,0);
		while($MyRow = DB_fetch_array($Result)) {
			if($IdSection <> $MyRow['cashflowsactivity']) {
				// Prints section total:
				echo '<tr>
			    	<td class="text" colspan="2">', CashFlowsActivityName($IdSection), '</td>',
					colDebitCredit($ActualSection),
					colDebitCredit($BudgetSection),// ShowBudget=ON vs. ShowBudget=OFF.*/
					colDebitCredit($LastSection),
			    '</tr>';
				// Resets section totals:
				$ActualSection = 0;
				$BudgetSection = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
				$LastSection = 0;
				$IdSection = $MyRow['cashflowsactivity'];
				// Prints next section title:
				echo '<tr>
			    		<td class="text" colspan="',  $Columns, '"><br /><h2>', CashFlowsActivityName($IdSection), '</h2></td>
			    	</tr>';
			}
			if($MyRow['ActualAmount']<>0
				OR $MyRow['BudgetAmount']<>0// ShowBudget=ON vs. ShowBudget=OFF.*/
				OR $MyRow['LastAmount']<>0 OR isset($_POST['ShowZeroBalance'])) {

				echo '<tr class="striped_row">
						<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?PeriodFrom=', $_POST['PeriodFrom'], '&amp;PeriodTo=', $_POST['PeriodTo'], '&amp;Account=', $MyRow['accountcode'], '">', $MyRow['accountcode'], '</a></td>',
						'<td class="text">', $MyRow['accountname'], '</td>',
						colDebitCredit($MyRow['ActualAmount']),
						colDebitCredit($MyRow['BudgetAmount']),// ShowBudget=ON vs. ShowBudget=OFF.*/
						colDebitCredit($MyRow['LastAmount']),
					'</tr>';
				$ActualSection += $MyRow['ActualAmount'];
				$ActualTotal += $MyRow['ActualAmount'];
				$BudgetSection += $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
				$BudgetTotal += $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
				$LastSection += $MyRow['LastAmount'];
				$LastTotal += $MyRow['LastAmount'];
			}
		}
		// Prints the last section total:
		echo '<tr>
				<td class="text" colspan="2">', CashFlowsActivityName($IdSection), '</td>',
				colDebitCredit($ActualSection),
				colDebitCredit($BudgetSection),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastSection),
			'</tr>
			<tr><td colspan="',  $Columns, '">&nbsp;</td></tr>',
		// Prints Net increase in cash and cash equivalents:
			'<tr>
				<td class="text" colspan="2"><b>', _('Net increase in cash and cash equivalents'), '</b></td>',
				colDebitCredit($ActualTotal),
				colDebitCredit($BudgetTotal),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastTotal),
			'</tr>';
		// Prints Cash and cash equivalents at beginning of period:
		if($_POST['ShowCash']) {
			// Prints a detail of Cash and cash equivalents at beginning of period (Parameters: PeriodFrom, PeriodTo, ShowBudget=ON, ShowZeroBalance=on/off, ShowCash=ON):
			echo '<tr><td colspan="',  $Columns, '">&nbsp;</td></tr>';
			$ActualBeginning = 0;
			$BudgetBeginning = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
			$LastBeginning = 0;
			$SelectCashEquivalentsBeginning = "Sum(CASE WHEN (chartdetails.period = '" . $_POST['PeriodFrom'] . "') THEN chartdetails.bfwdbudget ELSE 0 END) AS BudgetAmount,";// ShowBudget=ON vs. ShowBudget=OFF.*/
			$Sql = "SELECT
						chartdetails.accountcode,
						chartmaster.accountname,
						Sum(CASE WHEN (chartdetails.period = '" . $_POST['PeriodFrom'] . "') THEN chartdetails.bfwd ELSE 0 END) AS ActualAmount," .
						$SelectCashEquivalentsBeginning .
						"Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodFrom']-12) . "') THEN chartdetails.bfwd ELSE 0 END) AS LastAmount
					FROM chartmaster
						INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
						INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
					WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity=4
					GROUP BY chartdetails.accountcode
					ORDER BY chartdetails.accountcode";
			$Result = DB_query($Sql);
			while($MyRow = DB_fetch_array($Result)) {
				if($MyRow['ActualAmount']<>0
					OR $MyRow['BudgetAmount']<>0// ShowBudget=ON vs. ShowBudget=OFF.*/
					OR $MyRow['LastAmount']<>0 OR isset($_POST['ShowZeroBalance'])) {

					echo '<tr class="striped_row">
							<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?Period=', $_POST['PeriodFrom'], '&amp;Account=', $MyRow['accountcode'], '">', $MyRow['accountcode'], '</a></td>',
							'<td class="text">', $MyRow['accountname'], '</td>',
							colDebitCredit($MyRow['ActualAmount']),
							colDebitCredit($MyRow['BudgetAmount']),// ShowBudget=ON vs. ShowBudget=OFF.*/
							colDebitCredit($MyRow['LastAmount']),
						'</tr>';
					$ActualBeginning += $MyRow['ActualAmount'];
					$BudgetBeginning += $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
					$LastBeginning += $MyRow['LastAmount'];
				}
			}
		} else {
			// Prints a summary of Cash and cash equivalents at beginning of period (Parameters: PeriodFrom, PeriodTo, ShowBudget=ON, ShowZeroBalance=on/off, ShowCash=OFF):
			$SelectCashEquivalentsBeginning = "Sum(CASE WHEN (chartdetails.period = '" . $_POST['PeriodFrom'] . "') THEN chartdetails.bfwdbudget ELSE 0 END) AS BudgetAmount,";// ShowBudget=ON vs. ShowBudget=OFF.*/

			$Sql = "SELECT
						Sum(CASE WHEN (chartdetails.period = '" . $_POST['PeriodFrom'] . "') THEN chartdetails.bfwd ELSE 0 END) AS ActualAmount," .
						$SelectCashEquivalentsBeginning .
						"Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodFrom']-12) . "') THEN chartdetails.bfwd ELSE 0 END) AS LastAmount
					FROM chartmaster
						INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
						INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
					WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity=4";
			$Result = DB_query($Sql);
			$MyRow = DB_fetch_array($Result);
			$ActualBeginning = $MyRow['ActualAmount'];
			$BudgetBeginning = $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
			$LastBeginning = $MyRow['LastAmount'];
		}
		echo '<tr>
				<td class="text" colspan="2"><b>', _('Cash and cash equivalents at beginning of period'), '</b></td>',
				colDebitCredit($ActualBeginning),
				colDebitCredit($BudgetBeginning),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastBeginning),
			'</tr>';
		// Prints Cash and cash equivalents at end of period:
		if($_POST['ShowCash']) {
			// Prints a detail of Cash and cash equivalents at end of period (Parameters: PeriodFrom, PeriodTo, ShowBudget=ON, ShowZeroBalance=on/off, ShowCash=ON):
			echo '<tr><td colspan="',  $Columns, '">&nbsp;</td></tr>';
			$SelectCashEquivalentsEnd = "Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodTo']+1) . "') THEN chartdetails.bfwdbudget ELSE 0 END) AS BudgetAmount,";
			$Sql = "SELECT
						chartdetails.accountcode,
						chartmaster.accountname,
						Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodTo']+1) . "') THEN chartdetails.bfwd ELSE 0 END) AS ActualAmount," .
						$SelectCashEquivalentsEnd .
						"Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodTo']-11) . "') THEN chartdetails.bfwd ELSE 0 END) AS LastAmount
					FROM chartmaster
						INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
						INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
					WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity=4
					GROUP BY chartdetails.accountcode
					ORDER BY chartdetails.accountcode";
			$Result = DB_query($Sql);
			while($MyRow = DB_fetch_array($Result)) {
				if($MyRow['ActualAmount']<>0
					OR $MyRow['BudgetAmount']<>0// ShowBudget=ON vs. ShowBudget=OFF.*/
					OR $MyRow['LastAmount']<>0 OR isset($_POST['ShowZeroBalance'])) {

					echo '<tr class="striped_row">
							<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?Period=', $_POST['PeriodTo'], '&amp;Account=', $MyRow['accountcode'], '">', $MyRow['accountcode'], '</a></td>
							<td class="text">', $MyRow['accountname'], '</td>',
							colDebitCredit($MyRow['ActualAmount']),
							colDebitCredit($MyRow['BudgetAmount']),// ShowBudget=ON vs. ShowBudget=OFF.*/
							colDebitCredit($MyRow['LastAmount']),
						'</tr>';
				}
			}
		}
		// Prints Cash and cash equivalents at end of period total:
		echo '<tr>
				<td class="text" colspan="2"><b>', _('Cash and cash equivalents at end of period'), '</b></td>',
				colDebitCredit($ActualTotal+$ActualBeginning),
				colDebitCredit($BudgetTotal+$BudgetBeginning),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastTotal+$LastBeginning),
			'</tr>';
		// Prints 'Cash or cash equivalent' section if selected (Parameters: PeriodFrom, PeriodTo, ShowBudget=ON, ShowZeroBalance=on/off, ShowCash=ON):
		if($_POST['ShowCash']) {
			// Prints 'Cash or cash equivalent' section title:
			echo '<tr><td colspan="',  $Columns, '">&nbsp</td><tr>
				<tr>
		    		<td class="text" colspan="',  $Columns, '"><br /><h2>', CashFlowsActivityName(4), '</h2></td>
		    	</tr>';
			// Initialise 'Cash or cash equivalent' section accumulators:
			$ActualCash = 0;
			$BudgetCash = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
			$LastCash = 0;
			$SelectCashEquivalentsShow = "Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN chartdetails.budget ELSE 0 END) AS BudgetAmount,";
			$Sql = "SELECT
				chartdetails.accountcode,
				chartmaster.accountname,
				Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN chartdetails.actual ELSE 0 END) AS ActualAmount," .
				$SelectCashEquivalentsShow .
				"Sum(CASE WHEN (chartdetails.period >= '" . ($_POST['PeriodFrom']-12) . "' AND chartdetails.period <= '" . ($_POST['PeriodTo']-12) . "') THEN chartdetails.actual ELSE 0 END) AS LastAmount
			FROM chartmaster
				INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
				INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
			WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity=4
			GROUP BY chartdetails.accountcode
			ORDER BY
				chartdetails.accountcode";
			$Result = DB_query($Sql);
			while($MyRow = DB_fetch_array($Result)) {
				if($MyRow['ActualAmount']<>0
					OR $MyRow['BudgetAmount']<>0// ShowBudget=ON vs. ShowBudget=OFF.*/
					OR $MyRow['LastAmount']<>0 OR isset($_POST['ShowZeroBalance'])) {

					echo '<tr class="striped_row">
							<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?PeriodFrom=', $_POST['PeriodFrom'], '&amp;PeriodTo=', $_POST['PeriodTo'], '&amp;Account=', $MyRow['accountcode'], '">', $MyRow['accountcode'], '</a></td>
							<td class="text">', $MyRow['accountname'], '</td>',
							colDebitCredit($MyRow['ActualAmount']),
							colDebitCredit($MyRow['BudgetAmount']),// ShowBudget=ON vs. ShowBudget=OFF.*/
							colDebitCredit($MyRow['LastAmount']),
						'</tr>';
					$ActualCash += $MyRow['ActualAmount'];
					$BudgetCash += $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
					$LastCash += $MyRow['LastAmount'];
				}
			}
			// Prints 'Cash or cash equivalent' section total:
			echo '<tr>
		    	<td class="text" colspan="2">', CashFlowsActivityName(4), '</td>',
				colDebitCredit($ActualCash),
				colDebitCredit($BudgetCash),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastCash),
		    '</tr>';
		}
//<<++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// END Outputs the table with budget.
	} else {
		// Parameters: PeriodFrom, PeriodTo, ShowBudget=OFF, ShowZeroBalance=on/off, ShowCash=on/off.
		// BEGIN Outputs the table without budget.
		// Code maintenance note: To update 'Outputs the table withOUT budget', copy 'Outputs the table with budget' and remove lines with 'budget'.
//---------------------------------------------------------------------------->>
/*		$BudgetSection = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
/*		$BudgetTotal = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
		$Columns = 6;// ShowBudget=ON vs. ShowBudget=OFF.*/
		$TableHead = '<th colspan="2">' . _('Last Year') . '</th>';// ShowBudget=ON vs. ShowBudget=OFF.
		echo $TableHead, '</tr>
			</thead><tfoot>
				<tr>',
					'<td class="text" colspan="',  $Columns, '">',// Prints an explanation of signs in actual and relative changes:
						'<br /><b>', _('Notes'), ':</b><br />',
						_('Cash flows signs: a negative number indicates a cash flow used in activities; a positive number indicates a cash flow provided by activities.'), '<br />';
		if($_POST['ShowCash']) {
			echo		_('Cash and cash equivalents signs: a negative number indicates a cash outflow; a positive number indicates a cash inflow.'), '<br />';
		}
		echo		'</td>
				</tr>
			</tfoot><tbody>';
		// Net profit − dividends = Retained earnings:
		echo '<tr>
				<td class="text" colspan="',  $Columns, '"><br /><h2>', _('Net profit and dividends'), '</h2></td>
			</tr>
			<tr class="striped_row">
				<td>&nbsp;</td>
				<td class="text">', _('Net profit for the period'), '</td>';
		// Net profit for the period:
		$SelectNetProfit = "";// ShowBudget=ON vs. ShowBudget=OFF.
		$Sql = "SELECT
					Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN -chartdetails.actual ELSE 0 END) AS ActualProfit," .
					$SelectNetProfit .
					"Sum(CASE WHEN (chartdetails.period >= '" . ($_POST['PeriodFrom']-12) . "' AND chartdetails.period <= '" . ($_POST['PeriodTo']-12) . "') THEN -chartdetails.actual ELSE 0 END) AS LastProfit
				FROM chartmaster
					INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
					INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
				WHERE accountgroups.pandl=1";
		$Result = DB_query($Sql);
		$MyRow1 = DB_fetch_array($Result);
		echo	colDebitCredit($MyRow1['ActualProfit']),
/*				colDebitCredit($MyRow1['BudgetProfit']),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($MyRow1['LastProfit']),
			'</tr>
			<tr class="striped_row">
				<td>&nbsp;</td>
				<td class="text">', _('Dividends'), '</td>';
		// Dividends:
		$SelectDividends = "";// ShowBudget=ON vs. ShowBudget=OFF.
		$Sql = "SELECT
					Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN chartdetails.actual ELSE 0 END) AS ActualRetained," .
					$SelectDividends .
					"Sum(CASE WHEN (chartdetails.period >= '" . ($_POST['PeriodFrom']-12) . "' AND chartdetails.period <= '" . ($_POST['PeriodTo']-12) . "') THEN chartdetails.actual ELSE 0 END) AS LastRetained
				FROM chartmaster
					INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
					INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
				WHERE accountgroups.pandl=0
					AND chartdetails.accountcode!='" . $_SESSION['PeriodProfitAccount'] . "'
					AND chartdetails.accountcode!='" . $_SESSION['RetainedEarningsAccount'] . "'";// Gets retained earnings by the complement method to include differences. The complement method: Changes(retained earnings) = -Changes(other accounts).
		$Result = DB_query($Sql);
		$MyRow2 = DB_fetch_array($Result);
		echo	colDebitCredit($MyRow2['ActualRetained'] - $MyRow1['ActualProfit']),
/*				colDebitCredit($MyRow2['BudgetRetained'] - $MyRow1['BudgetProfit']),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($MyRow2['LastRetained'] - $MyRow1['LastProfit']),
			'</tr><tr>',
				'<td class="text" colspan="2">', _('Retained earnings'), '</td>',
		// Retained earnings changes:
					colDebitCredit($MyRow2['ActualRetained']),
/*					colDebitCredit($MyRow2['BudgetRetained']),// ShowBudget=ON vs. ShowBudget=OFF.*/
					colDebitCredit($MyRow2['LastRetained']),
			'</tr>';
		$ActualTotal += $MyRow2['ActualRetained'];
/*		$BudgetTotal += $MyRow2['BudgetRetained'];// ShowBudget=ON vs. ShowBudget=OFF.*/
		$LastTotal += $MyRow2['LastRetained'];
		// Cash flows sections:
		$SelectCashFlows = "";// ShowBudget=ON vs. ShowBudget=OFF.*/
		$Sql = "SELECT
					chartmaster.cashflowsactivity,
					chartdetails.accountcode,
					chartmaster.accountname,
					Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN -chartdetails.actual ELSE 0 END) AS ActualAmount," .
					$SelectCashFlows .
					"Sum(CASE WHEN (chartdetails.period >= '" . ($_POST['PeriodFrom']-12) . "' AND chartdetails.period <= '" . ($_POST['PeriodTo']-12) . "') THEN -chartdetails.actual ELSE 0 END) AS LastAmount
				FROM chartmaster
					INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
					INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
				WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity!=4
				GROUP BY
					chartdetails.accountcode
				ORDER BY
					chartmaster.cashflowsactivity,
					chartdetails.accountcode";
		$Result = DB_query($Sql);
		$IdSection = -1;
		// Looks for an account without setting up:
		$NeedSetup = FALSE;
		while($MyRow = DB_fetch_array($Result)) {
			if($MyRow['cashflowsactivity'] == -1) {
				$NeedSetup = TRUE;
				echo '<tr><td colspan="',  $Columns, '">&nbsp;</td></tr>';
				break;
			}
		}
		DB_data_seek($Result,0);
		while($MyRow = DB_fetch_array($Result)) {
			if($IdSection <> $MyRow['cashflowsactivity']) {
				// Prints section total:
				echo '<tr>
			    	<td class="text" colspan="2">', CashFlowsActivityName($IdSection), '</td>',
					colDebitCredit($ActualSection),
/*					colDebitCredit($BudgetSection),// ShowBudget=ON vs. ShowBudget=OFF.*/
					colDebitCredit($LastSection),
			    '</tr>';
				// Resets section totals:
				$ActualSection = 0;
/*				$BudgetSection = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
				$LastSection = 0;
				$IdSection = $MyRow['cashflowsactivity'];
				// Prints next section title:
				echo '<tr>
			    		<td class="text" colspan="',  $Columns, '"><br /><h2>', CashFlowsActivityName($IdSection), '</h2></td>
			    	</tr>';
			}
			if($MyRow['ActualAmount']<>0
/*				OR $MyRow['BudgetAmount']<>0// ShowBudget=ON vs. ShowBudget=OFF.*/
				OR $MyRow['LastAmount']<>0 OR isset($_POST['ShowZeroBalance'])) {

				echo '<tr class="striped_row">
						<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?PeriodFrom=', $_POST['PeriodFrom'], '&amp;PeriodTo=', $_POST['PeriodTo'], '&amp;Account=', $MyRow['accountcode'], '">', $MyRow['accountcode'], '</a></td>
						<td class="text">', $MyRow['accountname'], '</td>',
						colDebitCredit($MyRow['ActualAmount']),
/*						colDebitCredit($MyRow['BudgetAmount']),// ShowBudget=ON vs. ShowBudget=OFF.*/
						colDebitCredit($MyRow['LastAmount']),
					'</tr>';
				$ActualSection += $MyRow['ActualAmount'];
				$ActualTotal += $MyRow['ActualAmount'];
/*				$BudgetSection += $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
/*				$BudgetTotal += $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
				$LastSection += $MyRow['LastAmount'];
				$LastTotal += $MyRow['LastAmount'];
			}
		}
		// Prints the last section total:
		echo '<tr>
				<td class="text" colspan="2">', CashFlowsActivityName($IdSection), '</td>',
				colDebitCredit($ActualSection),
/*				colDebitCredit($BudgetSection),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastSection),
			'</tr>
			<tr><td colspan="',  $Columns, '">&nbsp;</td></tr>',
		// Prints Net increase in cash and cash equivalents:
			'<tr>
				<td class="text" colspan="2"><b>', _('Net increase in cash and cash equivalents'), '</b></td>',
				colDebitCredit($ActualTotal),
/*				colDebitCredit($BudgetTotal),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastTotal),
			'</tr>';
		// Prints Cash and cash equivalents at beginning of period:
		if($_POST['ShowCash']) {
			// Prints a detail of Cash and cash equivalents at beginning of period (Parameters: PeriodFrom, PeriodTo, ShowBudget=OFF, ShowZeroBalance=on/off, ShowCash=ON):
			echo '<tr><td colspan="',  $Columns, '">&nbsp;</td></tr>';
			$ActualBeginning = 0;
/*			$BudgetBeginning = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
			$LastBeginning = 0;
			$SelectCashEquivalentsBeginning = "";// ShowBudget=ON vs. ShowBudget=OFF.*/
			$Sql = "SELECT
						chartdetails.accountcode,
						chartmaster.accountname,
						Sum(CASE WHEN (chartdetails.period = '" . $_POST['PeriodFrom'] . "') THEN chartdetails.bfwd ELSE 0 END) AS ActualAmount," .
						$SelectCashEquivalentsBeginning .
						"Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodFrom']-12) . "') THEN chartdetails.bfwd ELSE 0 END) AS LastAmount
					FROM chartmaster
						INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
						INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
					WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity=4
					GROUP BY chartdetails.accountcode
					ORDER BY chartdetails.accountcode";
			$Result = DB_query($Sql);
			while($MyRow = DB_fetch_array($Result)) {
				if($MyRow['ActualAmount']<>0
/*					OR $MyRow['BudgetAmount']<>0// ShowBudget=ON vs. ShowBudget=OFF.*/
					OR $MyRow['LastAmount']<>0 OR isset($_POST['ShowZeroBalance'])) {

					echo '<tr class="striped_row">
							<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?Period=', $_POST['PeriodFrom'], '&amp;Account=', $MyRow['accountcode'], '">', $MyRow['accountcode'], '</a></td>
							<td class="text">', $MyRow['accountname'], '</td>',
							colDebitCredit($MyRow['ActualAmount']),
/*							colDebitCredit($MyRow['BudgetAmount']),// ShowBudget=ON vs. ShowBudget=OFF.*/
							colDebitCredit($MyRow['LastAmount']),
						'</tr>';
					$ActualBeginning += $MyRow['ActualAmount'];
/*					$BudgetBeginning += $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
					$LastBeginning += $MyRow['LastAmount'];
				}
			}
		} else {
			// Prints a summary of Cash and cash equivalents at beginning of period (Parameters: PeriodFrom, PeriodTo, ShowBudget=OFF, ShowZeroBalance=on/off, ShowCash=OFF):
			$SelectCashEquivalentsBeginning = "";
			$Sql = "SELECT
						Sum(CASE WHEN (chartdetails.period = '" . $_POST['PeriodFrom'] . "') THEN chartdetails.bfwd ELSE 0 END) AS ActualAmount," .
						$SelectCashEquivalentsBeginning .
						"Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodFrom']-12) . "') THEN chartdetails.bfwd ELSE 0 END) AS LastAmount
					FROM chartmaster
						INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
						INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
					WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity=4";
			$Result = DB_query($Sql);
			$MyRow = DB_fetch_array($Result);
			$ActualBeginning = $MyRow['ActualAmount'];
/*			$BudgetBeginning = $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
			$LastBeginning = $MyRow['LastAmount'];
		}
		echo '<tr>
				<td class="text" colspan="2"><b>', _('Cash and cash equivalents at beginning of period'), '</b></td>',
				colDebitCredit($ActualBeginning),
/*				colDebitCredit($BudgetBeginning),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastBeginning),
			'</tr>';
		// Prints Cash and cash equivalents at end of period:
		if($_POST['ShowCash']) {
			// Prints a detail of Cash and cash equivalents at end of period (Parameters: PeriodFrom, PeriodTo, ShowBudget=OFF, ShowZeroBalance=on/off, ShowCash=ON):
			echo '<tr><td colspan="',  $Columns, '">&nbsp;</td></tr>';
			$SelectCashEquivalentsEnd = "";
			$Sql = "SELECT
						chartdetails.accountcode,
						chartmaster.accountname,
						Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodTo']+1) . "') THEN chartdetails.bfwd ELSE 0 END) AS ActualAmount," .
						$SelectCashEquivalentsEnd .
						"Sum(CASE WHEN (chartdetails.period = '" . ($_POST['PeriodTo']-11) . "') THEN chartdetails.bfwd ELSE 0 END) AS LastAmount
					FROM chartmaster
						INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
						INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
					WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity=4
					GROUP BY chartdetails.accountcode
					ORDER BY chartdetails.accountcode";
			$Result = DB_query($Sql);
			while($MyRow = DB_fetch_array($Result)) {
				if($MyRow['ActualAmount']<>0
/*					OR $MyRow['BudgetAmount']<>0// ShowBudget=ON vs. ShowBudget=OFF.*/
					OR $MyRow['LastAmount']<>0 OR isset($_POST['ShowZeroBalance'])) {

					echo '<tr class="striped_row">
							<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?Period=', $_POST['PeriodTo'], '&amp;Account=', $MyRow['accountcode'], '">', $MyRow['accountcode'], '</a></td>
							<td class="text">', $MyRow['accountname'], '</td>',
							colDebitCredit($MyRow['ActualAmount']),
/*							colDebitCredit($MyRow['BudgetAmount']),// ShowBudget=ON vs. ShowBudget=OFF.*/
							colDebitCredit($MyRow['LastAmount']),
						'</tr>';
				}
			}
		}
		// Prints Cash and cash equivalents at end of period total:
		echo '<tr>
				<td class="text" colspan="2"><b>', _('Cash and cash equivalents at end of period'), '</b></td>',
				colDebitCredit($ActualTotal+$ActualBeginning),
/*				colDebitCredit($BudgetTotal+$BudgetBeginning),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastTotal+$LastBeginning),
			'</tr>';
		// Prints 'Cash or cash equivalent' section if selected (Parameters: PeriodFrom, PeriodTo, ShowBudget=OFF, ShowZeroBalance=on/off, ShowCash=ON):
		if($_POST['ShowCash']) {
			// Prints 'Cash or cash equivalent' section title:
			echo '<tr><td colspan="',  $Columns, '">&nbsp</td><tr>
				<tr>
		    		<td class="text" colspan="',  $Columns, '"><br /><h2>', CashFlowsActivityName(4), '</h2></td>
		    	</tr>';
			// Initialise 'Cash or cash equivalent' section accumulators:
			$ActualCash = 0;
/*			$BudgetCash = 0;// ShowBudget=ON vs. ShowBudget=OFF.*/
			$LastCash = 0;
			$SelectCashEquivalentsShow = "";
			$Sql = "SELECT
				chartdetails.accountcode,
				chartmaster.accountname,
				Sum(CASE WHEN (chartdetails.period >= '" . $_POST['PeriodFrom'] . "' AND chartdetails.period <= '" . $_POST['PeriodTo'] . "') THEN chartdetails.actual ELSE 0 END) AS ActualAmount," .
				$SelectCashEquivalentsShow .
				"Sum(CASE WHEN (chartdetails.period >= '" . ($_POST['PeriodFrom']-12) . "' AND chartdetails.period <= '" . ($_POST['PeriodTo']-12) . "') THEN chartdetails.actual ELSE 0 END) AS LastAmount
			FROM chartmaster
				INNER JOIN chartdetails ON chartmaster.accountcode=chartdetails.accountcode
				INNER JOIN accountgroups ON chartmaster.group_=accountgroups.groupname
			WHERE accountgroups.pandl=0 AND chartmaster.cashflowsactivity=4
			GROUP BY chartdetails.accountcode
			ORDER BY
				chartdetails.accountcode";
			$Result = DB_query($Sql);
			while($MyRow = DB_fetch_array($Result)) {
				if($MyRow['ActualAmount']<>0
/*					OR $MyRow['BudgetAmount']<>0// ShowBudget=ON vs. ShowBudget=OFF.*/
					OR $MyRow['LastAmount']<>0 OR isset($_POST['ShowZeroBalance'])) {

					echo '<tr class="striped_row">
							<td class="text"><a href="', $RootPath, '/GLAccountInquiry.php?PeriodFrom=', $_POST['PeriodFrom'], '&amp;PeriodTo=', $_POST['PeriodTo'], '&amp;Account=', $MyRow['accountcode'], '">', $MyRow['accountcode'], '</a></td>
							<td class="text">', $MyRow['accountname'], '</td>',
							colDebitCredit($MyRow['ActualAmount']),
/*							colDebitCredit($MyRow['BudgetAmount']),// ShowBudget=ON vs. ShowBudget=OFF.*/
							colDebitCredit($MyRow['LastAmount']),
						'</tr>';
					$ActualCash += $MyRow['ActualAmount'];
/*					$BudgetCash += $MyRow['BudgetAmount'];// ShowBudget=ON vs. ShowBudget=OFF.*/
					$LastCash += $MyRow['LastAmount'];
				}
			}
			// Prints 'Cash or cash equivalent' section total:
			echo '<tr>
		    	<td class="text" colspan="2">', CashFlowsActivityName(4), '</td>',
				colDebitCredit($ActualCash),
/*				colDebitCredit($BudgetCash),// ShowBudget=ON vs. ShowBudget=OFF.*/
				colDebitCredit($LastCash),
		    '</tr>';
		}
//<<----------------------------------------------------------------------------
		// END Outputs the table without budget.
	}
	echo '</tbody></table>',
		'</div>';// div id="Report".
	if(!isset($IsIncluded)) {// Runs normally if this script is NOT included in another.
		echo // Shows a form to select an action after the report was shown:
			'<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
			'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
			// Resend report parameters:
			'<input name="PeriodFrom" type="hidden" value="', $_POST['PeriodFrom'], '" />',
			'<input name="PeriodTo" type="hidden" value="', $_POST['PeriodTo'], '" />',
			'<input name="ShowBudget" type="hidden" value="', $_POST['ShowBudget'], '" />',
/*			'<input name="ShowDetail" type="hidden" value="', $_POST['ShowDetail'], '" />',*/
			'<input name="ShowZeroBalance" type="hidden" value="', $_POST['ShowZeroBalance'], '" />',
			'<input name="ShowCash" type="hidden" value="', $_POST['ShowCash'], '" />',
			'<div class="centre noprint">'; // Form buttons:
		if($NeedSetup) {
			echo '<button onclick="window.location=\'GLCashFlowsSetup.php\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/maintenance.png" /> ', _('Run Setup'), '</button>'; // "Run Setup" button.
		}
		echo	'<button onclick="window.print()" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/printer.png" /> ', _('Print'), '</button>', // "Print" button.
				'<button name="NewReport" type="submit" value="on"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/reports.png" /> ', _('New Report'), '</button>', // "New Report" button.
				'<button onclick="window.location=\'index.php?Application=GL\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
			'</div>';
	}
} else {// If one or more parameters are NOT set or NOT valid, shows a parameters input form:
	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/printer.png" title="', // Icon image.
		$Title, '" /> ', // Icon title.
		$Title, '</p>';// Page title.
	fShowPageHelp(// Shows the page help text if $_SESSION['ShowFieldHelp'] is TRUE or is not set
		_('The statement of cash flows, also known as the successor of the old source and application of funds statement, reports how changes in balance sheet accounts and income affect cash and cash equivalents, and breaks the analysis down to operating, investing and financing activities.') . '<br />' .
		_('The purpose of the statement of cash flows is to show where the company got their money from and how it was spent during the period being reported for a user selectable range of periods.') . '<br />' .
		_('The statement of cash flows represents a period of time. This contrasts with the statement of financial position, which represents a single moment in time.') . '<br />' .
		_('webERP is an "accrual" based system (not a "cash based" system). Accrual systems include items when they are invoiced to the customer, and when expenses are owed based on the supplier invoice date.'));// Function fShowPageHelp() in ~/includes/MiscFunctions.php
	echo // Shows a form to input the report parameters:
		'<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />', // Input table:
		// Input table:
		'<table class="selection">', // Content of the header and footer of the input table:
/*		'<thead>
			<tr>
				<th colspan="2">', _('Report Parameters'), '</th>
			</tr>
		</thead>',*/
		'<tfoot>
			<tr>
				<td colspan="2">',
					'<div class="centre">',
						'<button name="Submit" type="submit" value="', _('Submit'), '"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/tick.svg" /> ', _('Submit'), '</button>', // "Submit" button.
						'<button onclick="window.location=\'index.php?Application=GL\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
					'</div>',
				'</td>
			</tr>
		</tfoot><tbody>',
		// Content of the body of the input table:
			// Select period from:
			'<tr>',
				'<td><label for="PeriodFrom">', _('Select period from'), '</label></td>
		 		<td><select id="PeriodFrom" name="PeriodFrom" required="required">';
	$Periods = DB_query('SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno ASC');
	if(!isset($_POST['PeriodFrom'])) {
		$BeginMonth = ($_SESSION['YearEnd']==12 ? 1 : $_SESSION['YearEnd']+1);// Sets January as the month that follows December.
		if($BeginMonth <= date('n')) {// It is a month in the current year.
			$BeginDate = mktime(0, 0, 0, $BeginMonth, 1, date('Y'));
		} else {// It is a month in the previous year.
			$BeginDate = mktime(0, 0, 0, $BeginMonth, 1, date('Y')-1);
		}
		$_POST['PeriodFrom'] = GetPeriod(date($_SESSION['DefaultDateFormat'], $BeginDate));
	}
	while($MyRow = DB_fetch_array($Periods)) {
	    echo			'<option',($MyRow['periodno'] == $_POST['PeriodFrom'] ? ' selected="selected"' : '' ), ' value="', $MyRow['periodno'], '">', MonthAndYearFromSQLDate($MyRow['lastdate_in_period']), '</option>';
	}
	echo			'</select>', fShowFieldHelp(_('Select the beginning of the reporting period')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
	// Select period to:
			'<tr>',
				'<td><label for="PeriodTo">', _('Select period to'), '</label></td>
		 		<td><select id="PeriodTo" name="PeriodTo" required="required">';
	if(!isset($_POST['PeriodTo'])) {
		$_POST['PeriodTo'] = GetPeriod(date($_SESSION['DefaultDateFormat']));
	}
	DB_data_seek($Periods, 0);
	while($MyRow = DB_fetch_array($Periods)) {
	    echo			'<option',($MyRow['periodno'] == $_POST['PeriodTo'] ? ' selected="selected"' : '' ), ' value="', $MyRow['periodno'], '">', MonthAndYearFromSQLDate($MyRow['lastdate_in_period']), '</option>';
	}
	echo			'</select>', fShowFieldHelp(_('Select the end of the reporting period')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>';
	// OR Select period:
	if(!isset($_POST['Period'])) {
		$_POST['Period'] = '';
	}
	echo	'<tr>
				<td>
					<h3>', _('OR'), '</h3>
				</td>
			</tr>
			<tr>
				<td>', _('Select Period'), '</td>
				<td>', ReportPeriodList($_POST['Period'], array('l', 't')), fShowFieldHelp(_('Select a period instead of using the beginning and end of the reporting period.')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
				'</td>
			</tr>',
	// Show the budget:
			'<tr>',
			 	'<td><label for="ShowBudget">', _('Show the budget'), '</label></td>
			 	<td><input', ($_POST['ShowBudget'] ? ' checked="checked"' : ''), ' id="ShowBudget" name="ShowBudget" type="checkbox">', // "Checked" if ShowBudget is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show the budget for the period')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
	// Show accounts with zero balance:
			'<tr>',
			 	'<td><label for="ShowZeroBalance">', _('Show accounts with zero balance'), '</label></td>
			 	<td><input', ($_POST['ShowZeroBalance'] ? ' checked="checked"' : ''), ' id="ShowZeroBalance" name="ShowZeroBalance" type="checkbox">', // "Checked" if ShowZeroBalance is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show all accounts including those with zero balance')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
	// Show cash and cash equivalents accounts:
			'<tr>',
			 	'<td><label for="ShowCash">', _('Show cash and cash equivalents accounts'), '</label></td>
			 	<td><input',($_POST['ShowCash'] ? ' checked="checked"' : ''), ' id="ShowCash" name="ShowCash" type="checkbox">', // "Checked" if ShowZeroBalance is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show cash and cash equivalents accounts')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
		 '</tbody></table>';
}
echo	'</form>';

if(!isset($IsIncluded)) {// Runs normally if this script is NOT included in another.
	include('includes/footer.php');
}
?>
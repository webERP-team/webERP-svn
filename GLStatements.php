<?php
/*	GLStatements.php
	Shows a set of financial statements.
	This program is under the GNU General Public License, last version. 2016-10-08.
	This creative work is under the CC BY-NC-SA, last version. 2016-10-08.


Info about financial statements: IAS 1 - Presentation of Financial Statements.

Parameters:
	PeriodFrom: Select the beginning of the reporting period.
	PeriodTo: Select the end of the reporting period.
	Period: Select a period instead of using the beginning and end of the reporting period.
	ShowBudget: Check this box to show the budget.
	ShowDetail: Check this box to show all accounts instead a summary.
	ShowZeroBalance: Check this box to show accounts with zero balance.
	ShowFinancialPosition: Check this box to show the statement of financial position as at the end and at the beginning of the period;
	ShowComprehensiveIncome: Check this box to show the statement of comprehensive income;
	ShowChangesInEquity: Check this box to show the statement of changes in equity;
	ShowCashFlows: Check this box to show the statement of cash flows; and
	ShowNotes: Check this box to show the notes that summarize the significant accounting policies and other explanatory information.
	NewReport: Click this button to start a new report.
	IsIncluded: Parameter to indicate that a script is included within another.
*/

// BEGIN: Functions division ===================================================
// END: Functions division =====================================================

// BEGIN: Procedure division ===================================================
include('includes/session.php');
$Title = _('Financial Statements');
$ViewTopic = 'GeneralLedger';
$BookMark = 'GLStatements';

include('includes/header.php');

// Merges gets into posts:
if(isset($_GET['PeriodFrom'])) {
	$_POST['PeriodFrom'] = $_GET['PeriodFrom'];
}
if(isset($_GET['PeriodTo'])) {
	$_POST['PeriodTo'] = $_GET['PeriodTo'];
}
if(isset($_GET['Period'])) {
	$_POST['Period'] = $_GET['Period'];
}
if(isset($_GET['ShowBudget'])) {
	$_POST['ShowBudget'] = $_GET['ShowBudget'];
}
if(isset($_GET['ShowZeroBalance'])) {
	$_POST['ShowZeroBalance'] = $_GET['ShowZeroBalance'];
}
if(isset($_GET['ShowFinancialPosition'])) {
	$_POST['ShowFinancialPosition'] = $_GET['ShowFinancialPosition'];
}
if(isset($_GET['ShowComprehensiveIncome'])) {
	$_POST['ShowComprehensiveIncome'] = $_GET['ShowComprehensiveIncome'];
}
if(isset($_GET['ShowChangesInEquity'])) {
	$_POST['ShowChangesInEquity'] = $_GET['ShowChangesInEquity'];
}
if(isset($_GET['ShowCashFlows'])) {
	$_POST['ShowCashFlows'] = $_GET['ShowCashFlows'];
}
if(isset($_GET['ShowNotes'])) {
	$_POST['ShowNotes'] = $_GET['ShowNotes'];
}
if(isset($_GET['NewReport'])) {
	$_POST['NewReport'] = $_GET['NewReport'];
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
if(isset($_POST['PeriodFrom']) AND isset($_POST['PeriodTo']) AND !($_POST['ShowFinancialPosition']) AND !($_POST['ShowComprehensiveIncome']) AND !($_POST['ShowChangesInEquity']) AND !($_POST['ShowCashFlows']) AND !($_POST['ShowNotes'])) {
	// No financial statement was selected.
	$_POST['NewReport'] = 'on';
	prnMsg(_('You must select at least one financial statement. Please select financial statements.'), 'error');
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
	echo // Index of this report:
		'<p>', _('In this set of financial statements:'),
		(($_POST['ShowFinancialPosition']) ? '<br />* ' . _('Statement of financial position') . '.' : ''),
		(($_POST['ShowComprehensiveIncome']) ? '<br />* ' . _('Statement of comprehensive income') . '.' : ''),
		(($_POST['ShowChangesInEquity']) ? '<br />* ' . _('Statement of changes in equity') . '.' : ''),
		(($_POST['ShowCashFlows']) ? '<br />* ' . _('Statement of cash flows') . '.' : ''),
		(($_POST['ShowNotes']) ? '<br />* ' . _('Notes') . '.' : ''),
		'<p>';
	echo '</div>';// div id="Report".
	$IsIncluded = TRUE;
	$PageBreak = '<hr class="PageBreak"/>' . chr(12);// Marker to indicate that the content that follows is part of a new page.
	// Displays the statements using the corresponding scripts:
	$_POST['ShowDetail'] = 'Detailed';
	if($_POST['ShowFinancialPosition']) {
		echo $PageBreak;
		include('GLBalanceSheet.php');
	}
	if($_POST['ShowComprehensiveIncome']) {
		echo $PageBreak;
		include('GLProfit_Loss.php');
	}
	if($_POST['ShowChangesInEquity']) {
		echo $PageBreak;
		include('GLChangesInEquity.php');
	}
	if($_POST['ShowCashFlows']) {
		echo $PageBreak;
		include('GLCashFlowsIndirect.php');
	}
	if($_POST['ShowNotes']) {
		echo $PageBreak;
		include('GLNotes.php');
	}
	echo // Shows a form to select an action after the report was shown:
		'<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
		// Resend report parameters:
		'<input name="PeriodFrom" type="hidden" value="', $_POST['PeriodFrom'], '" />',
		'<input name="PeriodTo" type="hidden" value="', $_POST['PeriodTo'], '" />',
		'<input name="ShowBudget" type="hidden" value="', $_POST['ShowBudget'], '" />',
		'<input name="ShowZeroBalance" type="hidden" value="', $_POST['ShowZeroBalance'], '" />',
		'<input name="ShowFinancialPosition" type="hidden" value="', $_POST['ShowFinancialPosition'], '" />',
		'<input name="ShowComprehensiveIncome" type="hidden" value="', $_POST['ShowComprehensiveIncome'], '" />',
		'<input name="ShowChangesInEquity" type="hidden" value="', $_POST['ShowChangesInEquity'], '" />',
		'<input name="ShowCashFlows" type="hidden" value="', $_POST['ShowCashFlows'], '" />',
		'<input name="ShowNotes" type="hidden" value="', $_POST['ShowNotes'], '" />',
		'<div class="centre noprint">', // Form buttons:
			'<button onclick="window.print()" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/printer.png" /> ', _('Print'), '</button>', // "Print" button.
			'<button name="NewReport" type="submit" value="on"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/reports.png" /> ', _('New Report'), '</button>', // "New Report" button.
			'<button onclick="window.location=\'index.php?Application=GL\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
		'</div>',
		'</form>';
} else {
	// If PeriodFrom or PeriodTo are NOT set or it is a NewReport, shows a parameters input form:
	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/gl.png" title="', // Icon image.
		$Title, '" /> ', // Icon title.
		$Title, '</p>';// Page title.
	fShowPageHelp(// Shows the page help text if $_SESSION['ShowFieldHelp'] is TRUE or is not set
		_('Shows a set of financial statements.') . '<br />' .
		_('A complete set of financial statements comprises:(a) a statement of financial position as at the end and at the beginning of the period;(b) a statement of comprehensive income for the period;(c) a statement of changes in equity for the period;(d) a statement of cash flows for the period; and(e) notes that summarize the significant accounting policies and other explanatory information.') . '<br />' .
		_('webERP is an "accrual" based system (not a "cash based" system). Accrual systems include items when they are invoiced to the customer, and when expenses are owed based on the supplier invoice date.'));// Function fShowPageHelp() in ~/includes/MiscFunctions.php
	echo // Shows a form to input the report parameters:
		'<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />', // Input table:
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
			 		fShowFieldHelp(_('Check this box to show the budget')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
	// Show accounts with zero balance:
			'<tr>',
			 	'<td><label for="ShowZeroBalance">', _('Show accounts with zero balance'), '</label></td>
			 	<td><input', ($_POST['ShowZeroBalance'] ? ' checked="checked"' : ''), ' id="ShowZeroBalance" name="ShowZeroBalance" type="checkbox">', // "Checked" if ShowZeroBalance is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show accounts with zero balance')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
	// Show the statement of financial position:
			'<tr>',
			 	'<td><label for="ShowFinancialPosition">', _('Show the statement of financial position'), '</label></td>
			 	<td><input', ($_POST['ShowFinancialPosition'] ? ' checked="checked"' : ''), ' id="ShowFinancialPosition" name="ShowFinancialPosition" type="checkbox">', // "Checked" if ShowFinancialPosition is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show the statement of financial position')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
	// Show the statement of comprehensive income:
			'<tr>',
			 	'<td><label for="ShowComprehensiveIncome">', _('Show the statement of comprehensive income'), '</label></td>
			 	<td><input', ($_POST['ShowComprehensiveIncome'] ? ' checked="checked"' : ''), ' id="ShowComprehensiveIncome" name="ShowComprehensiveIncome" type="checkbox">', // "Checked" if ShowComprehensiveIncome is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show the statement of comprehensive income')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>';
	// Show the statement of changes in equity:
	if(file_exists('GLChangesInEquity.php')) {// Provisional, to be replaced by a rights verification.
		echo
			'<tr>',
			 	'<td><label for="ShowChangesInEquity">', _('Show the statement of changes in equity'), '</label></td>
			 	<td><input', ($_POST['ShowChangesInEquity'] ? ' checked="checked"' : ''), ' id="ShowChangesInEquity" name="ShowChangesInEquity" type="checkbox">', // "Checked" if ShowChangesInEquity is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show the statement of changes in equity')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>';
	}
	// Show the statement of cash flows:
	echo	'<tr>',
			 	'<td><label for="ShowCashFlows">', _('Show the statement of cash flows'), '</label></td>
			 	<td><input', ($_POST['ShowCashFlows'] ? ' checked="checked"' : ''), ' id="ShowCashFlows" name="ShowCashFlows" type="checkbox">', // "Checked" if ShowCashFlows is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show the statement of cash flows')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>';
	// Show the notes:
	if(file_exists('GLNotes.php')) {// Provisional, to be replaced by a rights verification.
		echo
			'<tr>',
			 	'<td><label for="ShowNotes">', _('Show the notes'), '</label></td>
			 	<td><input', ($_POST['ShowNotes'] ? ' checked="checked"' : ''), ' id="ShowNotes" name="ShowNotes" type="checkbox">', // "Checked" if ShowNotes is set AND it is TRUE.
			 		fShowFieldHelp(_('Check this box to show the notes that summarize the significant accounting policies and other explanatory information')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>';
	}
	echo
		'</tbody></table>';
		'</form>';
}

include('includes/footer.php');
?>
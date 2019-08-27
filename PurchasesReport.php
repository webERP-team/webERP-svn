<?php
/* PurchasesReport.php
Shows a report of purchases from suppliers for the range of selected dates.
This program is under the GNU General Public License, last version. 2016-12-18.
This creative work is under the CC BY-NC-SA, last version. 2016-12-18.

This script is "mirror-symmetric" to script SalesReport.php.
*/

// BEGIN: Functions division ===================================================
// END: Functions division =====================================================

// BEGIN: Procedure division ===================================================
include('includes/session.php');
$Title = _('Purchases from Suppliers');
$ViewTopic = 'PurchaseOrdering';
$BookMark = 'PurchasesReport';

include('includes/header.php');

// Merges gets into posts:
if(isset($_GET['PeriodFrom'])) {
	$_POST['PeriodFrom'] = $_GET['PeriodFrom'];
}
if(isset($_GET['PeriodTo'])) {
	$_POST['PeriodTo'] = $_GET['PeriodTo'];
}
if(isset($_GET['ShowDetails'])) {
	$_POST['ShowDetails'] = $_GET['ShowDetails'];
}

// Validates the data submitted in the form:
if(isset($_POST['PeriodFrom']) AND isset($_POST['PeriodTo'])) {
	if(Date1GreaterThanDate2($_POST['PeriodFrom'], $_POST['PeriodTo'])) {
		// The beginning is after the end.
		$_POST['NewReport'] = 'on';
		prnMsg(_('The beginning of the period should be before or equal to the end of the period. Please reselect the reporting period.'), 'error');
	}
}

// Main code:
if(isset($_POST['PeriodFrom']) AND isset($_POST['PeriodTo']) AND !$_POST['NewReport']) {
	// If PeriodFrom and PeriodTo are set and it is not a NewReport, generates the report:
	echo '<div class="sheet">', // Division to identify the report block.
		'<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/reports.png" title="', // Icon image.
		$Title, '" /> ', // Icon title.
		$Title, '</p>', // Page title.
		'<p>', _('Period from'), ': ', $_POST['PeriodFrom'],
		'<br />', _('Period to'), ': ', $_POST['PeriodTo'], '</p>',
		'<table class="selection">
		<thead>
			<tr>';
	// $CommonHead is the common table head between ShowDetails=off and ShowDetails=on:
	$CommonHead =
				'<th>' . _('Original Overall Amount') . '</th>' .
				'<th>' . _('Original Overall Taxes') . '</th>' .
				'<th>' . _('Original Overall Total') . '</th>' .
				'<th>' . _('GL Overall Amount') . '</th>' .
				'<th>' . _('GL Overall Taxes') . '</th>' .
				'<th>' . _('GL Overall Total') . '</th>' .
			'</tr>' .
		'</thead><tfoot>' .
			'<tr>' .
				'<td colspan="9"><br /><b>' .
					_('Notes') . '</b><br />' .
					_('Original amounts in the supplier\'s currency. GL amounts in the functional currency.') .
				'</td>' .
			'</tr>' .
		'</tfoot><tbody>';
	$TotalGlAmount = 0;
	$TotalGlTax = 0;
	$PeriodFrom = FormatDateForSQL($_POST['PeriodFrom']);
	$PeriodTo = FormatDateForSQL($_POST['PeriodTo']);
	if($_POST['ShowDetails']) {// Parameters: PeriodFrom, PeriodTo, ShowDetails=on.
		echo		'<th>', _('Date'), '</th>',
					'<th>', _('Purchase Invoice'), '</th>',
					'<th>', _('Reference'), '</th>',
					$CommonHead;
		// Includes $CurrencyName array with currency three-letter alphabetic code and name based on ISO 4217:
		include('includes/CurrenciesArray.php');
		$SupplierId = '';
		$SupplierOvAmount = 0;
		$SupplierOvTax = 0;
		$SupplierGlAmount = 0;
		$SupplierGlTax = 0;
		$SQL = "SELECT
					supptrans.supplierno,
					suppliers.suppname,
					suppliers.currcode,
					supptrans.trandate,
					supptrans.suppreference,
					supptrans.transno,
					supptrans.ovamount,
					supptrans.ovgst,
					supptrans.rate
				FROM supptrans
					INNER JOIN suppliers ON supptrans.supplierno=suppliers.supplierid
				WHERE supptrans.trandate>='" . $PeriodFrom . "'
					AND supptrans.trandate<='" . $PeriodTo . "'
					AND supptrans.`type`=20
				ORDER BY supptrans.supplierno, supptrans.trandate";
		$Result = DB_query($SQL);
		foreach($Result as $MyRow) {
			if($MyRow['supplierno'] != $SupplierId) {// If different, prints supplier totals:
				if($SupplierId != '') {// If NOT the first line.
					echo '<tr>',
							'<td colspan="3">&nbsp;</td>',
							'<td class="number">', locale_number_format($SupplierOvAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td class="number">', locale_number_format($SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td class="number">', locale_number_format($SupplierOvAmount+$SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td class="number">', locale_number_format($SupplierGlAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td class="number">', locale_number_format($SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
							'<td class="number">', locale_number_format($SupplierGlAmount+$SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
						'</tr>';
				}
				echo '<tr><td colspan="9">&nbsp;</td></tr>';
				echo '<tr><td class="text" colspan="9"><a href="', $RootPath, '/SupplierInquiry.php?SupplierID=', $MyRow['supplierno'], '">', $MyRow['supplierno'], ' - ', $MyRow['suppname'], '</a> - ', $MyRow['currcode'], ' ', $CurrencyName[$MyRow['currcode']], '</td></tr>';
				$TotalGlAmount += $SupplierGlAmount;
				$TotalGlTax += $SupplierGlTax;
				$SupplierId = $MyRow['supplierno'];
				$SupplierOvAmount = 0;
				$SupplierOvTax = 0;
				$SupplierGlAmount = 0;
				$SupplierGlTax = 0;
			}

			$GlAmount = $MyRow['ovamount']/$MyRow['rate'];
			$GlTax = $MyRow['ovgst']/$MyRow['rate'];
			echo '<tr class="striped_row">
					<td class="centre">', $MyRow['trandate'], '</td>',
					'<td class="number">', $MyRow['transno'], '</td>',
					'<td class="text">', $MyRow['suppreference'], '</td>',
					'<td class="number">', locale_number_format($MyRow['ovamount'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number">', locale_number_format($MyRow['ovgst'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number"><a href="', $RootPath, '/SuppWhereAlloc.php?TransType=20&TransNo=', $MyRow['transno'], '&amp;ScriptFrom=PurchasesReport" target="_blank" title="', _('Click to view where allocated'), '">', locale_number_format($MyRow['ovamount']+$MyRow['ovgst'], $_SESSION['CompanyRecord']['decimalplaces']), '</a></td>',
					'<td class="number">', locale_number_format($GlAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number">',	locale_number_format($GlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number"><a href="', $RootPath, '/GLTransInquiry.php?TypeID=20&amp;TransNo=', $MyRow['transno'], '&amp;ScriptFrom=PurchasesReport" target="_blank" title="', _('Click to view the GL entries'), '">', locale_number_format($GlAmount+$GlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</a></td>', // RChacon: Should be "Click to view the General Ledger transaction" instead?
				'</tr>';
			$SupplierOvAmount += $MyRow['ovamount'];
			$SupplierOvTax += $MyRow['ovgst'];
			$SupplierGlAmount += $GlAmount;
			$SupplierGlTax += $GlTax;
		}

		// Prints last supplier total:
		echo '<tr>',
				'<td colspan="3">&nbsp;</td>',
				'<td class="number">', locale_number_format($SupplierOvAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td class="number">', locale_number_format($SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td class="number">', locale_number_format($SupplierOvAmount+$SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td class="number">', locale_number_format($SupplierGlAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td class="number">', locale_number_format($SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'<td class="number">', locale_number_format($SupplierGlAmount+$SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
			'</tr>',
			'<tr><td colspan="9">&nbsp;</td></tr>';

		$TotalGlAmount += $SupplierGlAmount;
		$TotalGlTax += $SupplierGlTax;

	} else {// Parameters: PeriodFrom, PeriodTo, ShowDetails=off.
		// RChacon: Needs to update the table_sort function to use in this table.
		echo		'<th>', _('Supplier Code'), '</th>',
					'<th>', _('Supplier Name'), '</th>',
					'<th>', _('Supplier\'s Currency'), '</th>',
					$CommonHead;
		$SQL = "SELECT
					supptrans.supplierno,
					suppliers.suppname,
					suppliers.currcode,
					SUM(supptrans.ovamount) AS SupplierOvAmount,
					SUM(supptrans.ovgst) AS SupplierOvTax,
					SUM(supptrans.ovamount/supptrans.rate) AS SupplierGlAmount,
					SUM(supptrans.ovgst/supptrans.rate) AS SupplierGlTax
				FROM supptrans
					INNER JOIN suppliers ON supptrans.supplierno=suppliers.supplierid
				WHERE supptrans.trandate>='" . $PeriodFrom . "'
					AND supptrans.trandate<='" . $PeriodTo . "'
					AND supptrans.`type`=20
				GROUP BY
					supptrans.supplierno
				ORDER BY supptrans.supplierno, supptrans.trandate";
		$Result = DB_query($SQL);
		foreach($Result as $MyRow) {
			echo '<tr class="striped_row">',
					'<td class="text">', $MyRow['supplierno'], '</td>',
					'<td class="text"><a href="', $RootPath, '/SupplierInquiry.php?SupplierID=', $MyRow['supplierno'], '">', $MyRow['suppname'], '</a></td>',
					'<td class="text">', $MyRow['currcode'], '</td>',
					'<td class="number">', locale_number_format($MyRow['SupplierOvAmount'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number">', locale_number_format($MyRow['SupplierOvTax'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number">', locale_number_format($MyRow['SupplierOvAmount']+$MyRow['SupplierOvTax'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number">', locale_number_format($MyRow['SupplierGlAmount'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number">', locale_number_format($MyRow['SupplierGlTax'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
					'<td class="number">', locale_number_format($MyRow['SupplierGlAmount']+$MyRow['SupplierGlTax'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>',
				'</tr>';
			$TotalGlAmount += $MyRow['SupplierGlAmount'];
			$TotalGlTax += $MyRow['SupplierGlTax'];
		}
	}
	// Prints all suppliers total:
	echo	'<tr>
				<td class="text" colspan="6">&nbsp;</td>
				<td class="number">', locale_number_format($TotalGlAmount, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($TotalGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($TotalGlAmount+$TotalGlTax, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
			</tr>',
		'</tbody></table>',
		'</div>', // div id="Report".
	// Shows a form to select an action after the report was shown:
		'<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
	// Resend report parameters:
		'<input name="PeriodFrom" type="hidden" value="', $_POST['PeriodFrom'], '" />',
		'<input name="PeriodTo" type="hidden" value="', $_POST['PeriodTo'], '" />',
		'<input name="ShowDetails" type="hidden" value="', $_POST['ShowDetails'], '" />',
		'<div class="centre noprint">', // Form buttons:
			'<button onclick="window.print()" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/printer.png" /> ', _('Print'), '</button>', // "Print" button.
			'<button name="NewReport" type="submit" value="on"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/reports.png" /> ', _('New Report'), '</button>', // "New Report" button.
			'<button onclick="window.location=\'index.php?Application=PO\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
		'</div>';
} else {
	// If PeriodFrom or PeriodTo are NOT set or it is a NewReport, shows a parameters input form:
	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/gl.png" title="', // Icon image.
		$Title, '" /> ', // Icon title.
		$Title, '</p>';// Page title.
	fShowPageHelp(// Shows the page help text if $_SESSION['ShowFieldHelp'] is TRUE or is not set
		_('Shows a report of purchases from suppliers for the range of selected dates.'));// Function fShowPageHelp() in ~/includes/MiscFunctions.php
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
						'<button name="Submit" type="submit" value="submit"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/tick.svg" /> ', _('Submit'), '</button>', // "Submit" button.
						'<button onclick="window.location=\'index.php?Application=PO\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
					'</div>',
				'</td>
			</tr>
		</tfoot><tbody>',
	// Content of the body of the input table:
	// Select period from:
			'<tr>',
				'<td><label for="PeriodFrom">', _('Period from'), '</label></td>';
	if(!isset($_POST['PeriodFrom'])) {
		$_POST['PeriodFrom'] = date($_SESSION['DefaultDateFormat'], strtotime("-1 year", time()));// One year before current date.
	}
	echo 		'<td><input class="date" id="PeriodFrom" maxlength="10" name="PeriodFrom" required="required" size="11" type="text" value="', $_POST['PeriodFrom'], '" />',
					fShowFieldHelp(_('Select the beginning of the reporting period')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
			// Select period to:
			'<tr>',
				'<td><label for="PeriodTo">', _('Period to'), '</label></td>';
	if(!isset($_POST['PeriodTo'])) {
		$_POST['PeriodTo'] = date($_SESSION['DefaultDateFormat']);
	}
	echo 		'<td><input class="date" id="PeriodTo" maxlength="10" name="PeriodTo" required="required" size="11" type="text" value="', $_POST['PeriodTo'], '" />',
					fShowFieldHelp(_('Select the end of the reporting period')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
	// Show the budget for the period:
			'<tr>',
			 	'<td><label for="ShowDetails">', _('Show details'), '</label></td>',
			 	'<td>',
				 	'<input', (isset($_POST['ShowDetails']) && $_POST['ShowDetails'] ? ' checked="checked"' : ''), ' id="ShowDetails" name="ShowDetails" type="checkbox">', // If $_POST['ShowDetails'] is set AND it is TRUE, shows this input checked.
					fShowFieldHelp(_('Check this box to show purchase invoices')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
		 		'</td>
			</tr>',
		 '</tbody></table>';
}
echo	'</form>';
include('includes/footer.php');
// END Procedure division ======================================================
?>
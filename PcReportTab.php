<?php
// PcReportTab.php
// .

include ('includes/session.php');
$ViewTopic = 'PettyCash';
$BookMark = 'PcReportTab';
$Title = _('Petty Cash Management Report');

include ('includes/SQL_CommonFunctions.inc');

if (isset($_POST['SelectedTabs'])){
	$SelectedTabs = mb_strtoupper($_POST['SelectedTabs']);
} elseif (isset($_GET['SelectedTabs'])){
	$SelectedTabs = mb_strtoupper($_GET['SelectedTabs']);
}

if ((! isset($_POST['FromDate']) AND ! isset($_POST['ToDate'])) OR isset($_POST['SelectDifferentDate'])) {

	include  ('includes/header.php');

	echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/money_add.png" title="' . _('Payment Entry')
	. '" alt="" />' . ' ' . $Title . '</p>';

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
		<div>
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (!isset($_POST['FromDate'])){
		$_POST['FromDate'] = Date($_SESSION['DefaultDateFormat'], mktime(0,0,0,Date('m'),1,Date('Y')));
	}

	if (!isset($_POST['ToDate'])){
		$_POST['ToDate'] = Date($_SESSION['DefaultDateFormat']);
	}

	/*Show a form to allow input of criteria for Tabs to show */
	echo '<table class="selection">
		<tr>
			<td>' . _('Petty Cash Tab') . ':</td>
			<td><select name="SelectedTabs">';

	$SQL = "SELECT tabcode
				FROM pctabs
				WHERE ( authorizer = '" . $_SESSION['UserID'] .
					"' OR usercode = '" . $_SESSION['UserID'].
					"' OR assigner = '" . $_SESSION['UserID'] . "' )
				ORDER BY tabcode";
	$Result = DB_query($SQL);

	while ($MyRow = DB_fetch_array($Result)) {
		if (isset($_POST['SelectedTabs']) and $MyRow['tabcode'] == $_POST['SelectedTabs']) {
			echo '<option selected="selected" value="', $MyRow['tabcode'], '">', $MyRow['tabcode'], '</option>';
		} else {
			echo '<option value="', $MyRow['tabcode'], '">', $MyRow['tabcode'], '</option>';
		}
	} //end while loop get type of tab

	DB_free_result($Result);


	echo '</select></td>
		</tr>
		<tr>
			<td>', _('From Date'), ':</td>
			<td><input tabindex="2" class="date" type="text" name="FromDate" maxlength="10" size="11" value="' . $_POST['FromDate'] . '" /></td>
		</tr>
		<tr>
			<td>', _('To Date'), ':</td>
			<td><input tabindex="3" class="date" type="text" name="ToDate" maxlength="10" size="11" value="' . $_POST['ToDate'] . '" /></td>
		</tr>
		</table>
		<br />
		<div class="centre">
			<input type="submit" name="ShowTB" value="' . _('Show HTML') . '" />
			<input type="submit" name="PrintPDF" value="' . _('Print PDF') . '" />
		</div>
		</div>
	</form>';

} elseif (isset($_POST['PrintPDF'])) {

	include('includes/PDFStarter.php');
	$PageNumber = 0;
	$FontSize = 10;
	$pdf->addInfo('Title', _('Petty Cash Report Of Tab') );
	$pdf->addInfo('Subject', _('Petty Cash Report Of Tab') );
	$line_height = 12;

	$SQLFromDate = FormatDateForSQL($_POST['FromDate']);
	$SQLToDate = FormatDateForSQL($_POST['ToDate']);

	$SQL = "SELECT counterindex,
					tabcode,
					tag,
					date,
					codeexpense,
					amount,
					authorized,
					posted,
					purpose,
					notes
			FROM pcashdetails
			WHERE tabcode = '" . $SelectedTabs . "'
			AND date >= '" . $SQLFromDate . "' AND date <= '" . $SQLToDate . "'
			ORDER BY date, counterindex ASC";

	$TabDetail = DB_query($SQL);

	$SQLDecimalPlaces = "SELECT decimalplaces
					FROM currencies,pctabs
					WHERE currencies.currabrev = pctabs.currency
						AND tabcode='" . $SelectedTabs . "'";
		$Result = DB_query($SQLDecimalPlaces);
		$MyRow = DB_fetch_array($Result);
		$CurrDecimalPlaces = $MyRow['decimalplaces'];

	if (DB_error_no() != 0){
		include('includes/header.php');
		prnMsg(_('An error occurred getting the orders details'),'',_('Database Error'));
		if ($debug == 1){
			prnMsg( _('The SQL used to get the orders that failed was') . '<br />' . $SQL, '',_('Database Error'));
		}
		include ('includes/footer.php');
		exit;
	} elseif (DB_num_rows($TabDetail) == 0){
	  	include('includes/header.php');
		prnMsg(_('There were no expenses found in the database within the period from') . ' ' . $_POST['FromDate'] . ' ' . _('to') . ' ' . $_POST['ToDate'] . '. ' . _('Please try again selecting a different date range'),'warn');
		if ($debug == 1) {
			prnMsg(_('The SQL that returned no rows was') . '<br />' . $SQL,'',_('Database Error'));
		}
		include('includes/footer.php');
		exit;
	}

	include('includes/PDFTabReportHeader.inc');

	$SQLTabs = "SELECT tabcode,
						usercode,
						typetabcode,
						currency,
						tablimit,
						assigner,
						authorizer,
						authorizerexpenses,
						glaccountassignment,
						glaccountpcash,
						defaulttag,
						taxgroupid
				FROM pctabs
				WHERE tabcode='" . $SelectedTabs . "'";

	$TabResult = DB_query($SQLTabs, _('No Petty Cash tabs were returned by the SQL because'), _('The SQL that failed was:'));

	$Tabs = DB_fetch_array($TabResult);

	$SQLBalance = "SELECT SUM(amount) FROM pcashdetails
					WHERE tabcode = '" . $SelectedTabs . "'
					AND date < '" . $SQLFromDate . "'";

	$TabBalance = DB_query($SQLBalance);

	$Balance = DB_fetch_array($TabBalance);

	if( !isset($Balance['0'])){
		$Balance['0'] = 0;
	}

	$YPos -= (2 * $line_height);
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Tab Code :'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+100,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+110,$YPos,70,$FontSize,$SelectedTabs);
	$LeftOvers = $pdf->addTextWrap($Left_Margin+290,$YPos,70,$FontSize,_('From') . ' ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+320,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+340,$YPos,70,$FontSize,$_POST['FromDate']);

	$YPos -= $line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('User '));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+100,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+110,$YPos,70,$FontSize,$Tabs['usercode']);
	$LeftOvers = $pdf->addTextWrap($Left_Margin+290,$YPos,70,$FontSize,_('To') . ' ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+320,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+340,$YPos,70,$FontSize,$_POST['ToDate']);

	$YPos -= $line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Currency') . ' ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+100,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+110,$YPos,70,$FontSize,$Tabs['currency']);

	$YPos -= $line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Cash Assigner'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+100,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+110,$YPos,70,$FontSize,$Tabs['assigner']);

	$YPos -= $line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Authoriser - Cash'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+100,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+110,$YPos,70,$FontSize,$Tabs['authorizer']);

	$YPos -= $line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,80,$FontSize,_('Authoriser - Expenses'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+100,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+110,$YPos,70,$FontSize,$Tabs['authorizer']);

	$YPos -= $line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,50,$FontSize,_('Balance before '));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+55,$YPos,70,$FontSize,$_POST['FromDate']);
	$LeftOvers = $pdf->addTextWrap($Left_Margin+100,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+110,$YPos,70,$FontSize,locale_number_format($Balance['0'], $CurrDecimalPlaces));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+150,$YPos,70,$FontSize,$Tabs['currency']);

	$YPos -= (2 * $line_height);
	$pdf->line($Page_Width-$Right_Margin, $YPos+$line_height,$Left_Margin, $YPos+$line_height);

	$YPos -= (2 * $line_height);
	$FontSize = 8;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,70,$FontSize,_('Date of Expense'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,100,$FontSize,_('Expense Code'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+160,$YPos,100,$FontSize,_('Gross Amount'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+210,$YPos,100,$FontSize,_('Tax'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+255,$YPos,100,$FontSize,_('Tax Group'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+320,$YPos,100,$FontSize,_('Tag'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+380,$YPos,100,$FontSize,_('Notes'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+465,$YPos,100,$FontSize,_('Date Authorised'));
	$YPos -= (2 * $line_height);

	while ($MyRow = DB_fetch_array($TabDetail)) {

		$SQLDes = "SELECT description
					FROM pcexpenses
					WHERE codeexpense = '" . $MyRow[codeexpense] . "'";

		$ResultDes = DB_query($SQLDes);
		$Description = DB_fetch_array($ResultDes);
		if (!isset($Description['0'])){
			$Description['0']='ASSIGNCASH';
		}

		$TagSQL = "SELECT tagdescription FROM tags WHERE tagref='" . $MyRow['tag'] . "'";
		$TagResult = DB_query($TagSQL);
		$TagRow = DB_fetch_array($TagResult);
		if ($MyRow['tag'] == 0) {
			$TagRow['tagdescription'] = _('None');
		}
		$TagTo = $MyRow['tag'];
		$TagDescription = $TagTo . ' - ' . $TagRow['tagdescription'];

		$TaxesDescription = '';
		$TaxesTaxAmount = '';
		$TaxSQL = "SELECT counterindex,
								pccashdetail,
								calculationorder,
								description,
								taxauthid,
								purchtaxglaccount,
								taxontax,
								taxrate,
								amount
							FROM pcashdetailtaxes
							WHERE pccashdetail='" . $MyRow['counterindex'] . "'";
		$TaxResult = DB_query($TaxSQL);

		while ($MyTaxRow = DB_fetch_array($TaxResult)) {
				$TaxesDescription .= $MyTaxRow['description'] . "\n"; //Line breaks not working !?
				$TaxesTaxAmount .= locale_number_format($MyTaxRow['amount'], $CurrDecimalPlaces) . "\n"; //Line breaks not working !?
		}

		if ($MyRow['authorized'] == '0000-00-00') {
					$AuthorisedDate = _('Unauthorised');
				} else {
					$AuthorisedDate = ConvertSQLDate($MyRow['authorized']);
				}

		// Print total for each account
		$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,70,$FontSize,ConvertSQLDate($MyRow['date']));
		$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,130,$FontSize,$Description[0]);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+160,$YPos,50,$FontSize,locale_number_format($MyRow['amount'], $CurrDecimalPlaces),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+210,$YPos,50,$FontSize,$TaxesTaxAmount);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+255,$YPos,50,$FontSize,$TaxesDescription);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+320,$YPos,50,$FontSize,$TagDescription);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+380,$YPos,60,$FontSize,$MyRow['notes']);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+465,$YPos,70,$FontSize,$AuthorisedDate);
		$YPos -= $line_height;

	}  //end of while loop

	$SQLAmount="SELECT sum(amount)
				FROM pcashdetails
				WHERE tabcode = '" . $SelectedTabs . "'
				AND date <= '" . $SQLToDate . "'";

	$ResultAmount = DB_query($SQLAmount);
	$Amount = DB_fetch_array($ResultAmount);

	if (!isset($Amount[0])) {
		$Amount[0] = 0;
	}

	$YPos -= (2 * $line_height);
	$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
	$LeftOvers = $pdf->addTextWrap($Left_Margin+70,$YPos,100,$FontSize,_('Balance at'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+110,$YPos,70,$FontSize,$_POST['ToDate']);
	$LeftOvers = $pdf->addTextWrap($Left_Margin+160,$YPos,20,$FontSize,': ');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+160,$YPos,70,$FontSize,locale_number_format($Amount[0], $CurrDecimalPlaces),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+240,$YPos,70,$FontSize,$Tabs['currency']);
	$pdf->line($Page_Width-$Right_Margin, $YPos+$line_height,$Left_Margin, $YPos+$line_height);

	$pdf->OutputD($_SESSION['DatabaseName'] . '_PettyCash_Tab_Report_' . date('Y-m-d') . '.pdf');
	$pdf->__destruct();
	exit;
} else {

	include('includes/header.php');

	echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/money_add.png" title="' . _('Payment Entry')
	. '" alt="" />' . ' ' . $Title . '</p>';

	$SQLFromDate = FormatDateForSQL($_POST['FromDate']);
	$SQLToDate = FormatDateForSQL($_POST['ToDate']);

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<input type="hidden" name="FromDate" value="' . $_POST['FromDate'] . '" />
			<input type="hidden" name="ToDate" value="' . $_POST['ToDate'] . '" />';

	$SQLTabs = "SELECT tabcode,
						usercode,
						typetabcode,
						currency,
						tablimit,
						assigner,
						authorizer,
						authorizerexpenses,
						glaccountassignment,
						glaccountpcash,
						defaulttag,
						taxgroupid
			FROM pctabs
			WHERE tabcode = '" . $SelectedTabs . "'";

	$TabResult = DB_query($SQLTabs,
						 _('No Petty Cash Tabs were returned by the SQL because'),
						 _('The SQL that failed was:'));

	$Tabs = DB_fetch_array($TabResult);

	$SQLDecimalPlaces = "SELECT decimalplaces
					FROM currencies,pctabs
					WHERE currencies.currabrev = pctabs.currency
						AND tabcode='" . $SelectedTabs . "'";
		$Result = DB_query($SQLDecimalPlaces);
		$MyRow = DB_fetch_array($Result);
		$CurrDecimalPlaces = $MyRow['decimalplaces'];

	echo '<br /><table class="selection">';

	echo '<tr>
			  <td>' . _('Tab Code') . ':</td>
			  <td></td>
			  <td>' . '' . $SelectedTabs . '</td>
		  </tr>';

	echo '<tr>
			  <td>' . _('From') . ':</td>
			  <td></td>
			  <td>' . '' . $_POST['FromDate'] . '</td>
		  </tr>';

	echo '<tr>
			  <td>' . _('User') . ':</td>
			  <td></td>
			  <td>' . '' . $Tabs['usercode'] . '</td>
		  </tr>';

	echo '<tr>
			  <td>' . _('To') . ':</td>
			  <td></td>
			  <td>' . '' . $_POST['ToDate'] . '</td>
		  </tr>';

	echo '<tr>
			<td>' . _('Authoriser') . ':</td>
			<td></td>
			<td>' . '' . $Tabs['authorizer'] . '</td>
		  </tr>';
	echo '<tr>
			<td>' . _('Currency') . ':</td>
			<td></td>
			<td>' . '' . $Tabs['currency'] . '</td>
		  </tr>';

	$SQLBalance = "SELECT SUM(amount)
			FROM pcashdetails
			WHERE tabcode = '" . $SelectedTabs . "'
			AND date < '" . $SQLFromDate . "'";

	$TabBalance = DB_query($SQLBalance);

	$Balance = DB_fetch_array($TabBalance);

	if( !isset($Balance['0'])){
		$Balance['0'] = 0;
	}

	echo '<tr><td>' . _('Balance before ') . '' . $_POST['FromDate'] . ':</td>
				<td></td>
				<td>' . locale_number_format($Balance['0'],$_SESSION['CompanyRecord']['decimalplaces']) . ' ' . $Tabs['currency'] . '</td>
			</tr>';

	$SQLBalanceNotAut = "SELECT SUM(amount)
			FROM pcashdetails
			WHERE tabcode = '" . $SelectedTabs . "'
			AND authorized = '0000-00-00'
			AND date < '" . $SQLFromDate . "'";

	$TabBalanceNotAut = DB_query($SQLBalanceNotAut);

	$BalanceNotAut = DB_fetch_array($TabBalanceNotAut);

	if( !isset($BalanceNotAut['0'])){
		$BalanceNotAut['0'] = 0;
	}

	echo '<tr><td>' . _('Total not authorised before ') . '' . $_POST['FromDate'] . ':</td>
			  <td></td>
			  <td>' . '' . locale_number_format($BalanceNotAut['0'],$_SESSION['CompanyRecord']['decimalplaces']) . ' ' . $Tabs['currency'] . '</td>
		  </tr>';


	echo '</table>';

	/*show a table of the accounts info returned by the SQL
	Account Code ,   Account Name , Month Actual, Month Budget, Period Actual, Period Budget */

	$SQL = "SELECT counterindex,
					tabcode,
					tag,
					date,
					codeexpense,
					amount,
					authorized,
					posted,
					purpose,
					notes
			FROM pcashdetails
			WHERE tabcode = '" . $SelectedTabs . "'
				AND date >= '" . $SQLFromDate . "'
				AND date <= '" . $SQLToDate . "'
			ORDER BY date, counterindex Asc";

	$TabDetail = DB_query($SQL,
						_('No Petty Cash movements for this tab were returned by the SQL because'),
						_('The SQL that failed was:'));

	echo '<br />
		<table class="selection">
			<thead>
				<tr>
					<th class="ascending">' . _('Date of Expense') . '</th>
					<th class="ascending">' . _('Expense Code') . '</th>
					<th class="ascending">' . _('Gross Amount') . '</th>
					<th>' . _('Tax') . '</th>
					<th>' . _('Tax Group') . '</th>
					<th>' . _('Tag') . '</th>
					<th>' . _('Business Purpose') . '</th>
					<th>' . _('Notes') . '</th>
					<th>' . _('Receipt Attachment') . '</th>
					<th class="ascending">' . _('Date Authorised') . '</th>
				</tr>
			</thead>
			</tbody>';

	while ($MyRow = DB_fetch_array($TabDetail)) {

		$TagSQL = "SELECT tagdescription FROM tags WHERE tagref='" . $MyRow['tag'] . "'";
		$TagResult = DB_query($TagSQL);
		$TagRow = DB_fetch_array($TagResult);
		if ($MyRow['tag'] == 0) {
			$TagRow['tagdescription'] = _('None');
		}
		$TagTo = $MyRow['tag'];
		$TagDescription = $TagTo . ' - ' . $TagRow['tagdescription'];

		$TaxesDescription = '';
		$TaxesTaxAmount = '';
		$TaxSQL = "SELECT counterindex,
							pccashdetail,
							calculationorder,
							description,
							taxauthid,
							purchtaxglaccount,
							taxontax,
							taxrate,
							amount
						FROM pcashdetailtaxes
						WHERE pccashdetail='" . $MyRow['counterindex'] . "'";
		$TaxResult = DB_query($TaxSQL);

		while ($MyTaxRow = DB_fetch_array($TaxResult)) {
			$TaxesDescription .= $MyTaxRow['description'] . '<br />';
			$TaxesTaxAmount .= locale_number_format($MyTaxRow['amount'], $CurrDecimalPlaces) . '<br />';
		}

		//Generate download link for expense receipt, or show text if no receipt file is found.
		$ReceiptSupportedExt = array('png','jpg','jpeg','pdf','doc','docx','xls','xlsx'); //Supported file extensions
		$ReceiptDir = $PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/expenses_receipts/'; //Receipts upload directory
		$ReceiptSQL = "SELECT hashfile,
								extension
								FROM pcreceipts
								WHERE pccashdetail='" . $MyRow['counterindex'] . "'";
		$ReceiptResult = DB_query($ReceiptSQL);
		$ReceiptRow = DB_fetch_array($ReceiptResult);
		if (DB_num_rows($ReceiptResult) > 0) { //If receipt exists in database
			$ReceiptHash = $ReceiptRow['hashfile'];
			$ReceiptExt = $ReceiptRow['extension'];
			$ReceiptFileName = $ReceiptHash . '.' . $ReceiptExt;
			$ReceiptPath = $ReceiptDir . $ReceiptFileName;
			$ReceiptText = '<a href="' . $ReceiptPath . '" download="ExpenseReceipt-' . mb_strtolower($SelectedTabs) . '-[' . $MyRow['date'] . ']-[' . $MyRow['counterindex'] . ']">' . _('Download attachment') . '</a>';
		} else {
			$ReceiptText = _('No attachment');
		}

		if ($MyRow['authorized'] == '0000-00-00') {
					$AuthorisedDate = _('Unauthorised');
				} else {
					$AuthorisedDate = ConvertSQLDate($MyRow['authorized']);
				}

		$SQLDes = "SELECT description
					FROM pcexpenses
					WHERE codeexpense = '" . $MyRow['codeexpense'] . "'";

		$ResultDes = DB_query($SQLDes);
		$Description=DB_fetch_array($ResultDes);
		if (!isset($Description[0])) {
				$ExpenseCodeDes = 'ASSIGNCASH';
		} else {
				$ExpenseCodeDes = $MyRow['codeexpense'] . ' - ' . $Description[0];
		}

		echo '<tr class="striped_row">
				<td>', ConvertSQLDate($MyRow['date']), '</td>
				<td>', $ExpenseCodeDes, '</td>
				<td class="number">', locale_number_format($MyRow['amount'], $CurrDecimalPlaces), '</td>
				<td class="number">', $TaxesTaxAmount, '</td>
				<td>', $TaxesDescription, '</td>
				<td>', $TagDescription, '</td>
				<td>', $MyRow['purpose'], '</td>
				<td>', $MyRow['notes'], '</td>
				<td>', $ReceiptText, '</td>
				<td>', $AuthorisedDate, '</td>
			</tr>';
	}

	$SQLAmount="SELECT sum(amount)
				FROM pcashdetails
				WHERE tabcode = '" . $SelectedTabs . "'
				AND date <= '" . $SQLToDate . "'";

	$ResultAmount = DB_query($SQLAmount);
	$Amount = DB_fetch_array($ResultAmount);

	if (!isset($Amount[0])) {
		$Amount[0] = 0;
	}

	echo '</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="number">' . _('Balance at') . ' ' .$_POST['ToDate'] . ':</td>
				<td>' . locale_number_format($Amount[0],$_SESSION['CompanyRecord']['decimalplaces']) . ' </td>
				<td>' . $Tabs['currency'] . '</td>
			</tr>
		</tfoot>';

	echo '</table>';
	echo '<br /><div class="centre"><input type="submit" name="SelectDifferentDate" value="' . _('Select A Different Date') . '" /></div>';
    echo '</div>
          </form>';
}
include('includes/footer.php');

?>
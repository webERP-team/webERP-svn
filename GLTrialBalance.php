<?php
// GLTrialBalance.php
// Shows the trial balance for the month and the for the period selected together with the budgeted trial balances.

/*Through deviousness AND cunning, this system allows trial balances for any date range that recalculates the P&L balances
and shows the balance sheets as at the end of the period selected - so first off need to show the input of criteria screen
while the user is selecting the criteria the system is posting any unposted transactions */

include ('includes/session.php');
$Title = _('Trial Balance');
$ViewTopic = 'GeneralLedger';
$BookMark = 'TrialBalance';

include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.php'); // This loads the $Sections variable

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

// Sets PeriodFrom and PeriodTo from Period:
if($_POST['Period'] != '') {
	$_POST['PeriodFrom'] = ReportPeriod($_POST['Period'], 'From');
	$_POST['PeriodTo'] = ReportPeriod($_POST['Period'], 'To');
}

// Validates the data submitted in the form:
/*if (isset($_POST['PeriodFrom'])
	AND isset($_POST['PeriodTo'])
	AND $_POST['PeriodFrom'] > $_POST['PeriodTo']) {
	prnMsg(_('The selected period from is actually after the period to! Please re-select the reporting period'),'error');
	$_POST['NewReport'] = 'on';
}*/
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
if ((! isset($_POST['PeriodFrom'])
	AND ! isset($_POST['PeriodTo']))
	OR isset($_POST['NewReport'])) {

	// If PeriodFrom or PeriodTo are NOT set or it is a NewReport, shows a parameters input form:
	include('includes/header.php');
	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/printer.png" title="', // Icon image.
		_('Print Trial Balance'), '" /> ', // Icon title.
		$Title, '</p>', // Page title.
	// Shows a form to input the report parameters:
		'<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
	// Input table:
		'<table class="selection">',
	// Content of the body of the input table:
	// Select period from:
			'<tr>',
				'<td><label for="PeriodFrom">', _('Select period from'), '</label></td>
		 		<td><select id="PeriodFrom" name="PeriodFrom" required="required">';
	if(!isset($_POST['PeriodFrom'])) {
		$BeginMonth = ($_SESSION['YearEnd']==12 ? 1 : $_SESSION['YearEnd']+1);// Sets January as the month that follows December.
		if($BeginMonth <= date('n')) {// It is a month in the current year.
			$BeginDate = mktime(0, 0, 0, $BeginMonth, 1, date('Y'));
		} else {// It is a month in the previous year.
			$BeginDate = mktime(0, 0, 0, $BeginMonth, 1, date('Y')-1);
		}
		$_POST['PeriodFrom'] = GetPeriod(date($_SESSION['DefaultDateFormat'], $BeginDate));
	}
	$NextYear = date('Y-m-d', strtotime('+1 Year'));
	$SQL = "SELECT periodno, lastdate_in_period
			FROM periods
			WHERE lastdate_in_period < '" . $NextYear . "'
			ORDER BY periodno DESC";
	$Periods = DB_query($SQL);
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
				<td>', _('Select Period'), '</td>',
				'<td>', ReportPeriodList($_POST['Period'], array('l', 't')), fShowFieldHelp(_('Select a period instead of using the beginning and end of the reporting period.')), // Function fShowFieldHelp() in ~/includes/MiscFunctions.php
				'</td>
			</tr>',
		'</table>';

	echo '<div class="centre">
			<input type="submit" name="ShowTB" value="' . _('Show Trial Balance') .'" />
			<input type="submit" name="PrintPDF" value="'._('PrintPDF').'" />
		</div>',
		'</form>';

	// Now do the posting while the user is thinking about the period to select:
	include ('includes/GLPostings.inc');

} else if (isset($_POST['PrintPDF'])) {

	include('includes/PDFStarter.php');

	$pdf->addInfo('Title', _('Trial Balance') );
	$pdf->addInfo('Subject', _('Trial Balance') );
	$PageNumber = 0;
	$FontSize = 10;
	$line_height = 12;

	$NumberOfMonths = $_POST['PeriodTo'] - $_POST['PeriodFrom'] + 1;

	$SQL = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['PeriodTo'] . "'";
	$PrdResult = DB_query($SQL);
	$MyRow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($MyRow[0]);

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];

	$SQL = "SELECT accountgroups.groupname,
			accountgroups.parentgroupname,
			accountgroups.pandl,
			chartdetails.accountcode ,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodFrom'] . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodFrom'] . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodTo'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodTo'] . "' THEN chartdetails.actual ELSE 0 END) AS monthactual,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodTo'] . "' THEN chartdetails.budget ELSE 0 END) AS monthbudget,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodTo'] . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
		FROM chartmaster
			INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
			INNER JOIN chartdetails ON chartmaster.accountcode= chartdetails.accountcode
			INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" . $_SESSION['UserID'] . "' AND glaccountusers.canview=1
		GROUP BY accountgroups.groupname,
				accountgroups.parentgroupname,
				accountgroups.pandl,
				accountgroups.sequenceintb,
				chartdetails.accountcode,
				chartmaster.accountname
		ORDER BY accountgroups.pandl desc,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";

	$AccountsResult = DB_query($SQL);
	if (DB_error_no() !=0) {
		$Title = _('Trial Balance') . ' - ' . _('Problem Report') . '....';
		include('includes/header.php');
		prnMsg( _('No general ledger accounts were returned by the SQL because') . ' - ' . DB_error_msg() );
		echo '<br /><a href="' .$RootPath, '/index.php">' .  _('Back to the menu'). '</a>';
		if ($debug==1) {
			echo '<br />' .  $SQL;
		}
		include('includes/footer.php');
		exit;
	}
	if (DB_num_rows($AccountsResult)==0) {
		$Title = _('Print Trial Balance Error');
		include('includes/header.php');
		echo '<p>';
		prnMsg( _('There were no entries to print out for the selections specified') );
		echo '<br /><a href="'. $RootPath, '/index.php">' .  _('Back to the menu'). '</a>';
		include('includes/footer.php');
		exit;
	}

	include('includes/PDFTrialBalancePageHeader.inc');

	$Level = 1;
	$ActGrp = '';
	$ParentGroups = array();
	$ParentGroups[$Level] = '';
	$GrpActual = array(0);
	$GrpBudget = array(0);
	$GrpPrdActual = array(0);
	$GrpPrdBudget = array(0);
	$PeriodProfitLoss = 0;
	$PeriodBudgetProfitLoss = 0;
	$MonthProfitLoss = 0;
	$MonthBudgetProfitLoss = 0;
	$BFwdProfitLoss = 0;
	$CheckMonth = 0;
	$CheckBudgetMonth = 0;
	$CheckPeriodActual = 0;
	$CheckPeriodBudget = 0;

	while ($MyRow=DB_fetch_array($AccountsResult)) {

		if ($MyRow['groupname']!= $ActGrp) {

			if ($ActGrp !='') {

				// Print heading if at end of page
				if ($YPos < ($Bottom_Margin+ (2 * $line_height))) {
					include('includes/PDFTrialBalancePageHeader.inc');
				}
				if ($MyRow['parentgroupname']==$ActGrp) {
					$Level++;
					$ParentGroups[$Level]=$MyRow['groupname'];
				}elseif ($MyRow['parentgroupname']==$ParentGroups[$Level]) {
					$YPos -= (.5 * $line_height);
					$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
					$pdf->setFont('','B');
					$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Total'));
					$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$ParentGroups[$Level]);
					$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
					$YPos -= (2 * $line_height);
					$pdf->setFont('','');
					$ParentGroups[$Level] = $MyRow['groupname'];
					$GrpActual[$Level] = 0;
					$GrpBudget[$Level] = 0;
					$GrpPrdActual[$Level] = 0;
					$GrpPrdBduget[$Level] = 0;

				} else {
					do {
						$YPos -= $line_height;
						$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
						$pdf->setFont('','B');
						$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Total'));
						$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$ParentGroups[$Level]);
						$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
						$YPos -= (2 * $line_height);
						$pdf->setFont('','');
						$ParentGroups[$Level]='';
						$GrpActual[$Level] = 0;
						$GrpBudget[$Level] = 0;
						$GrpPrdActual[$Level] = 0;
						$GrpPrdBduget[$Level] = 0;
						$Level--;
					} while ($Level>0 AND $MyRow['parentgroupname']!=$ParentGroups[$Level]);

					if ($Level>0) {
						$YPos -= $line_height;
						$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
						$pdf->setFont('','B');
						$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Total'));
						$LeftOvers = $pdf->addTextWrap($Left_Margin+60, $YPos, 190, $FontSize, $ParentGroups[$Level]);
						$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
						$YPos -= (2 * $line_height);
						$pdf->setFont('','');
						$GrpActual[$Level] = 0;
						$GrpBudget[$Level] = 0;
						$GrpPrdActual[$Level] = 0;
						$GrpPrdBduget[$Level] = 0;
					} else {
						$Level =1;
					}
				}
			}
			$YPos -= (2 * $line_height);
				// Print account group name
			$pdf->setFont('','B');
			$ActGrp = $MyRow['groupname'];
			$ParentGroups[$Level]=$MyRow['groupname'];
			$FontSize = 10;
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$MyRow['groupname']);
			$FontSize = 8;
			$pdf->setFont('','');
			$YPos -= (2 * $line_height);
		}

		if ($MyRow['pandl']==1) {

			$AccountPeriodActual = $MyRow['lastprdcfwd'] - $MyRow['firstprdbfwd'];
			$AccountPeriodBudget = $MyRow['lastprdbudgetcfwd'] - $MyRow['firstprdbudgetbfwd'];

			$PeriodProfitLoss += $AccountPeriodActual;
			$PeriodBudgetProfitLoss += $AccountPeriodBudget;
			$MonthProfitLoss += $MyRow['monthactual'];
			$MonthBudgetProfitLoss += $MyRow['monthbudget'];
			$BFwdProfitLoss += $MyRow['firstprdbfwd'];
		} else { /*PandL ==0 its a balance sheet account */
			if ($MyRow['accountcode']==$RetainedEarningsAct) {
				$AccountPeriodActual = $BFwdProfitLoss + $MyRow['lastprdcfwd'];
				$AccountPeriodBudget = $BFwdProfitLoss + $MyRow['lastprdbudgetcfwd'] - $MyRow['firstprdbudgetbfwd'];
			} else {
				$AccountPeriodActual = $MyRow['lastprdcfwd'];
				$AccountPeriodBudget = $MyRow['firstprdbfwd'] + $MyRow['lastprdbudgetcfwd'] - $MyRow['firstprdbudgetbfwd'];
			}

		}
		for ($i=0;$i<=$Level;$i++) {
			if (!isset($GrpActual[$i])) {
				$GrpActual[$i]=0;
			}
			$GrpActual[$i] +=$MyRow['monthactual'];
			if (!isset($GrpBudget[$i])) {
				$GrpBudget[$i]=0;
			}
			$GrpBudget[$i] +=$MyRow['monthbudget'];
			if (!isset($GrpPrdActual[$i])) {
				$GrpPrdActual[$i]=0;
			}
			$GrpPrdActual[$i] +=$AccountPeriodActual;
			if (!isset($GrpPrdBudget[$i])) {
				$GrpPrdBudget[$i]=0;
			}
			$GrpPrdBudget[$i] +=$AccountPeriodBudget;
		}

		$CheckMonth += $MyRow['monthactual'];
		$CheckBudgetMonth += $MyRow['monthbudget'];
		$CheckPeriodActual += $AccountPeriodActual;
		$CheckPeriodBudget += $AccountPeriodBudget;

		// Print heading if at end of page
		if ($YPos < ($Bottom_Margin)) {
			include('includes/PDFTrialBalancePageHeader.inc');
		}

		// Print total for each account
		$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,$MyRow['accountcode']);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$MyRow['accountname']);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($MyRow['monthactual'], $_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($MyRow['monthbudget'], $_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($AccountPeriodActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($AccountPeriodBudget, $_SESSION['CompanyRecord']['decimalplaces']),'right');
		$YPos -= $line_height;

	}  //end of while loop


	while ($Level>0 AND $MyRow['parentgroupname']!=$ParentGroups[$Level]) {

		$YPos -= (.5 * $line_height);
		$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
		$pdf->setFont('','B');
		$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Total'));
		$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$ParentGroups[$Level]);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
		$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
		$YPos -= (2 * $line_height);
		$ParentGroups[$Level]='';
		$GrpActual[$Level] = 0;
		$GrpBudget[$Level] = 0;
		$GrpPrdActual[$Level] = 0;
		$GrpPrdBduget[$Level] = 0;
		$Level--;
	}


	$YPos -= (2 * $line_height);
	$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Check Totals'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($CheckMonth, $_SESSION['CompanyRecord']['decimalplaces']),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($CheckBudgetMonth, $_SESSION['CompanyRecord']['decimalplaces']),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($CheckPeriodActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($CheckPeriodBudget, $_SESSION['CompanyRecord']['decimalplaces']),'right');
	$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);

	$pdf->OutputD($_SESSION['DatabaseName'] . '_GL_Trial_Balance_' . Date('Y-m-d') . '.pdf');
	$pdf->__destruct();
	exit;

} else {
	include('includes/header.php');
	// If PeriodFrom and PeriodTo are set and it is not a NewReport, generates the report:
	echo '<div class="sheet">', // Division to identify the report block.
		'<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/gl.png" title="', // Icon image.
		$Title, '" /> '; // Icon title.

	$SQL = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['PeriodTo'] . "'";
	$Result = DB_query($SQL);
	$MyRow = DB_fetch_row($Result);
	$PeriodToDate = MonthAndYearFromSQLDate($MyRow[0]);
	echo _('Trial Balance for the month of '), $PeriodToDate, '<br />';

	$NumberOfMonths = $_POST['PeriodTo'] - $_POST['PeriodFrom'] + 1;
	echo _(' AND for the '), $NumberOfMonths, ' ', _('months to'), ' ', $PeriodToDate;// Page title.

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];

	$SQL = "SELECT accountgroups.groupname,
			accountgroups.parentgroupname,
			accountgroups.pandl,
			chartdetails.accountcode ,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodFrom'] . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodFrom'] . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodTo'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodTo'] . "' THEN chartdetails.actual ELSE 0 END) AS monthactual,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodTo'] . "' THEN chartdetails.budget ELSE 0 END) AS monthbudget,
			Sum(CASE WHEN chartdetails.period='" . $_POST['PeriodTo'] . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
		FROM chartmaster
			INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
			INNER JOIN chartdetails ON chartmaster.accountcode= chartdetails.accountcode
			INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" . $_SESSION['UserID'] . "' AND glaccountusers.canview=1
		GROUP BY accountgroups.groupname,
				accountgroups.pandl,
				accountgroups.sequenceintb,
				accountgroups.parentgroupname,
				chartdetails.accountcode,
				chartmaster.accountname
		ORDER BY accountgroups.pandl desc,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";


	$AccountsResult = DB_query($SQL, _('No general ledger accounts were returned by the SQL because'), _('The SQL that failed was:'));


	/*show a table of the accounts info returned by the SQL
	Account Code, Account Name, Month Actual, Month Budget, Period Actual, Period Budget */

	echo '<table cellpadding="2" class="selection"><tbody>';

	$TableHeader = '<tr>
						<th>' . _('Account') . '</th>
						<th>' . _('Account Name') . '</th>
						<th>' . _('Month Actual') . '</th>
						<th>' . _('Month Budget') . '</th>
						<th>' . _('Period Actual') . '</th>
						<th>' . _('Period Budget')  . '</th>
					</tr>';// RChacon: Can be part of a <thead>.*************

	$ActGrp ='';
	$ParentGroups = array();
	$Level =1; //level of nested sub-groups
	$ParentGroups[$Level]='';
	$GrpActual =array(0);
	$GrpBudget =array(0);
	$GrpPrdActual =array(0);
	$GrpPrdBudget =array(0);

	$PeriodProfitLoss = 0;
	$PeriodBudgetProfitLoss = 0;
	$MonthProfitLoss = 0;
	$MonthBudgetProfitLoss = 0;
	$BFwdProfitLoss = 0;
	$CheckMonth = 0;
	$CheckBudgetMonth = 0;
	$CheckPeriodActual = 0;
	$CheckPeriodBudget = 0;

	while ($MyRow=DB_fetch_array($AccountsResult)) {

		if ($MyRow['groupname']!= $ActGrp ) {
			if ($ActGrp !='') { //so its not the first account group of the first account displayed
				if ($MyRow['parentgroupname']==$ActGrp) {
					$Level++;
					$ParentGroups[$Level]=$MyRow['groupname'];
					$GrpActual[$Level] = 0;
					$GrpBudget[$Level] = 0;
					$GrpPrdActual[$Level] = 0;
					$GrpPrdBudget[$Level] = 0;
					$ParentGroups[$Level]='';
				} elseif ($ParentGroups[$Level]==$MyRow['parentgroupname']) {
					printf('<tr>
						<td colspan="2"><i>%s ' . _('Total') . ' </i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						</tr>',
						$ParentGroups[$Level],
						locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']));

					$GrpActual[$Level] = 0;
					$GrpBudget[$Level] = 0;
					$GrpPrdActual[$Level] = 0;
					$GrpPrdBudget[$Level] = 0;
					$ParentGroups[$Level]=$MyRow['groupname'];
				} else {
					do {
						printf('<tr>
							<td colspan="2"><i>%s ' . _('Total') . ' </i></td>
							<td class="number"><i>%s</i></td>
							<td class="number"><i>%s</i></td>
							<td class="number"><i>%s</i></td>
							<td class="number"><i>%s</i></td>
							</tr>',
							$ParentGroups[$Level],
							locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
							locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
							locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
							locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']));

						$GrpActual[$Level] = 0;
						$GrpBudget[$Level] = 0;
						$GrpPrdActual[$Level] = 0;
						$GrpPrdBudget[$Level] = 0;
						$ParentGroups[$Level]='';
						$Level--;

					} while ($Level>0 AND $MyRow['groupname']!=$ParentGroups[$Level]);

					if ($Level>0) {
						printf('<tr>
						<td colspan="2"><i>%s ' . _('Total') . ' </i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						</tr>',
						$ParentGroups[$Level],
						locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']));

						$GrpActual[$Level] = 0;
						$GrpBudget[$Level] = 0;
						$GrpPrdActual[$Level] = 0;
						$GrpPrdBudget[$Level] = 0;
						$ParentGroups[$Level]='';
					} else {
						$Level=1;
					}
				}
			}
			$ParentGroups[$Level]=$MyRow['groupname'];
			$ActGrp = $MyRow['groupname'];
			echo '<tr>
					<td colspan="6"><h2>', $MyRow['groupname'], '</h2></td>
				</tr>';
			echo $TableHeader;// RChacon: Can be part of a <thead>.*************
		}

		/*MonthActual, MonthBudget, FirstPrdBFwd, FirstPrdBudgetBFwd, LastPrdBudgetCFwd, LastPrdCFwd */

		if ($MyRow['pandl']==1) {

			$AccountPeriodActual = $MyRow['lastprdcfwd'] - $MyRow['firstprdbfwd'];
			$AccountPeriodBudget = $MyRow['lastprdbudgetcfwd'] - $MyRow['firstprdbudgetbfwd'];

			$PeriodProfitLoss += $AccountPeriodActual;
			$PeriodBudgetProfitLoss += $AccountPeriodBudget;
			$MonthProfitLoss += $MyRow['monthactual'];
			$MonthBudgetProfitLoss += $MyRow['monthbudget'];
			$BFwdProfitLoss += $MyRow['firstprdbfwd'];
		} else { /*PandL ==0 its a balance sheet account */
			if ($MyRow['accountcode']==$RetainedEarningsAct) {
				$AccountPeriodActual = $BFwdProfitLoss + $MyRow['lastprdcfwd'];
				$AccountPeriodBudget = $BFwdProfitLoss + $MyRow['lastprdbudgetcfwd'] - $MyRow['firstprdbudgetbfwd'];
			} else {
				$AccountPeriodActual = $MyRow['lastprdcfwd'];
				$AccountPeriodBudget = $MyRow['firstprdbfwd'] + $MyRow['lastprdbudgetcfwd'] - $MyRow['firstprdbudgetbfwd'];
			}

		}

		if (!isset($GrpActual[$Level])) {
			$GrpActual[$Level]=0;
		}
		if (!isset($GrpBudget[$Level])) {
			$GrpBudget[$Level]=0;
		}
		if (!isset($GrpPrdActual[$Level])) {
			$GrpPrdActual[$Level]=0;
		}
		if (!isset($GrpPrdBudget[$Level])) {
			$GrpPrdBudget[$Level]=0;
		}
		$GrpActual[$Level] +=$MyRow['monthactual'];
		$GrpBudget[$Level] +=$MyRow['monthbudget'];
		$GrpPrdActual[$Level] +=$AccountPeriodActual;
		$GrpPrdBudget[$Level] +=$AccountPeriodBudget;

		$CheckMonth += $MyRow['monthactual'];
		$CheckBudgetMonth += $MyRow['monthbudget'];
		$CheckPeriodActual += $AccountPeriodActual;
		$CheckPeriodBudget += $AccountPeriodBudget;

		echo '<tr class="striped_row">
				<td><a href="', $RootPath, '/GLAccountInquiry.php?PeriodFrom=', $_POST['PeriodFrom'], '&amp;PeriodTo=', $_POST['PeriodTo'], '&amp;Account=', $MyRow['accountcode'], '&amp;Show=Yes">', $MyRow['accountcode'], '</a></td>
				<td>', htmlspecialchars($MyRow['accountname'], ENT_QUOTES,'UTF-8', false), '</td>
				<td class="number">', locale_number_format($MyRow['monthactual'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($MyRow['monthbudget'], $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($AccountPeriodActual, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($AccountPeriodBudget, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
			</tr>';
	}
	//end of while loop

	if ($ActGrp !='') { //so its not the first account group of the first account displayed
		if ($MyRow['parentgroupname']==$ActGrp) {
			$Level++;
			$ParentGroups[$Level]=$MyRow['groupname'];
		} elseif ($ParentGroups[$Level]==$MyRow['parentgroupname']) {
			echo '<tr>
					<td colspan="2"><i>', $ParentGroups[$Level], ' ', _('Total') . ' </i></td>
					<td class="number"><i>', locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
					<td class="number"><i>', locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
					<td class="number"><i>', locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
					<td class="number"><i>', locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
				</tr>';
			$GrpActual[$Level] = 0;
			$GrpBudget[$Level] = 0;
			$GrpPrdActual[$Level] = 0;
			$GrpPrdBudget[$Level] = 0;
			$ParentGroups[$Level] = $MyRow['groupname'];
		} else {
			do {
				echo '<tr>
						<td colspan="2"><i>', $ParentGroups[$Level], ' ' . _('Total') . ' </i></td>
						<td class="number"><i>', locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
						<td class="number"><i>', locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
						<td class="number"><i>', locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
						<td class="number"><i>', locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
					</tr>';
				$GrpActual[$Level] = 0;
				$GrpBudget[$Level] = 0;
				$GrpPrdActual[$Level] = 0;
				$GrpPrdBudget[$Level] = 0;
				$ParentGroups[$Level] = '';
				$Level--;
			} while (isset($ParentGroups[$Level]) AND ($MyRow['groupname']!=$ParentGroups[$Level] AND $Level>0));

			if ($Level >0) {
				echo '<tr>
						<td colspan="2"><i>', $ParentGroups[$Level], ' ', _('Total'), ' </i></td>
						<td class="number"><i>', locale_number_format($GrpActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
						<td class="number"><i>', locale_number_format($GrpBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
						<td class="number"><i>', locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
						<td class="number"><i>', locale_number_format($GrpPrdBudget[$Level], $_SESSION['CompanyRecord']['decimalplaces']), '</i></td>
					</tr>';
				$GrpActual[$Level] = 0;
				$GrpBudget[$Level] = 0;
				$GrpPrdActual[$Level] = 0;
				$GrpPrdBudget[$Level] = 0;
				$ParentGroups[$Level] = '';
			} else {
				$Level =1;
			}
		}
	}

	echo	'<tr style="background-color:#ffffff">
				<td colspan="2"><b>', _('Check Totals'), '</b></td>
				<td class="number">', locale_number_format($CheckMonth, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($CheckBudgetMonth, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($CheckPeriodActual, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
				<td class="number">', locale_number_format($CheckPeriodBudget, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
			</tr>',
			'</tbody></table>',
		'</div>';// div id="Report".
	echo // Shows a form to select an action after the report was shown:
		'<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">',
		'<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />',
		// Resend report parameters:
		'<input name="PeriodFrom" type="hidden" value="', $_POST['PeriodFrom'], '" />',
		'<input name="PeriodTo" type="hidden" value="', $_POST['PeriodTo'], '" />',
		'<div class="centre noprint">', // Form buttons:
			'<button onclick="window.print()" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/printer.png" /> ', _('Print'), '</button>', // "Print" button.
			'<button name="NewReport" type="submit" value="on"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/reports.png" /> ', _('New Report'), '</button>', // "New Report" button.
			'<button onclick="window.location=\'index.php?Application=GL\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
		'</div>',
		'</form>';
}

include('includes/footer.php');
?>

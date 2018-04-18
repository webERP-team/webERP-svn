<?php
/* Shows the trial balance for the month and the for the period selected together with the budgeted trial balances. */

/*Through deviousness AND cunning, this system allows trial balances for any date range that recalculates the P&L balances
and shows the balance sheets as at the end of the period selected - so first off need to show the input of criteria screen
while the user is selecting the criteria the system is posting any unposted transactions */


include ('includes/session.php');
$Title = _('Trial Balance');// Screen identification.
$ViewTopic= 'GeneralLedger';// Filename's id in ManualContents.php's TOC.
$BookMark = 'TrialBalance';// Anchor's id in the manual's html document.

include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.php'); // This loads the $Sections variable


if (isset($_POST['FromPeriod'])
	AND isset($_POST['ToPeriod'])
	AND $_POST['FromPeriod'] > $_POST['ToPeriod']){

	prnMsg(_('The selected period from is actually after the period to! Please re-select the reporting period'),'error');
	$_POST['SelectADifferentPeriod']=_('Select A Different Period');
}

if ($_POST['Period'] != '') {
	$_POST['FromPeriod'] = ReportPeriod($_POST['Period'], 'From');
	$_POST['ToPeriod'] = ReportPeriod($_POST['Period'], 'To');
}

if ((! isset($_POST['FromPeriod'])
	AND ! isset($_POST['ToPeriod']))
	OR isset($_POST['SelectADifferentPeriod'])){

	include  ('includes/header.php');
	echo '<p class="page_title_text"><img alt="" src="'.$RootPath.'/css/'.$Theme.
		'/images/printer.png" title="' .// Icon image.
		_('Print Trial Balance') . '" /> ' .// Icon title.
		_('Trial Balance') . '</p>';// Page title.

	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (Date('m') > $_SESSION['YearEnd']){
		/*Dates in SQL format */
		$DefaultFromDate = Date ('Y-m-d', Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')));
		$FromDate = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')));
	} else {
		$DefaultFromDate = Date ('Y-m-d', Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')-1));
		$FromDate = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')-1));
	}
	/*GetPeriod function creates periods if need be the return value is not used */
	$NotUsedPeriodNo = GetPeriod($FromDate);

	/*Show a form to allow input of criteria for TB to show */
	echo '<table class="selection">
			<tr>
				<td>' . _('Select Period From') . ':</td>
				<td><select name="FromPeriod">';
	$NextYear = date('Y-m-d',strtotime('+1 Year'));
	$sql = "SELECT periodno,
					lastdate_in_period
				FROM periods
				WHERE lastdate_in_period < '" . $NextYear . "'
				ORDER BY periodno DESC";
	$Periods = DB_query($sql);


	while ($myrow=DB_fetch_array($Periods)){
		if(isset($_POST['FromPeriod']) AND $_POST['FromPeriod']!=''){
			if( $_POST['FromPeriod']== $myrow['periodno']){
				echo '<option selected="selected" value="' . $myrow['periodno'] . '">' .MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		} else {
			if($myrow['lastdate_in_period']==$DefaultFromDate){
				echo '<option selected="selected" value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		}
	}

	echo '</select></td>
		</tr>';
	if (!isset($_POST['ToPeriod']) OR $_POST['ToPeriod']==''){
		$DefaultToPeriod = GetPeriod(date($_SESSION['DefaultDateFormat'],mktime(0,0,0,Date('m')+1,0,Date('Y'))));
	} else {
		$DefaultToPeriod = $_POST['ToPeriod'];
	}

	echo '<tr>
			<td>' . _('Select Period To')  . ':</td>
			<td><select name="ToPeriod">';

	$RetResult = DB_data_seek($Periods,0);

	while ($myrow=DB_fetch_array($Periods)){

		if($myrow['periodno']==$DefaultToPeriod){
			echo '<option selected="selected" value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		} else {
			echo '<option value ="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		}
	}

	if ( !isset($_POST['Period']) ) {
		$_POST['Period'] = '';
	}

	echo '</select></td>
		</tr>
		<tr>
			<td>
				<h3>', _('OR'), '</h3>
			</td>
		</tr>
		<tr>
			<td>' . _('Select Period') . ':</td>
			<td>' . ReportPeriodList( $_POST['Period'] ) . '</td>
		</tr>
		</table>
		<br />';

	echo '<div class="centre">
			<input type="submit" name="ShowTB" value="' . _('Show Trial Balance') .'" />
			<input type="submit" name="PrintPDF" value="'._('PrintPDF').'" />
		</div>';

/*Now do the posting while the user is thinking about the period to select */

	include ('includes/GLPostings.inc');

} else if (isset($_POST['PrintPDF'])) {

	include('includes/PDFStarter.php');

	$pdf->addInfo('Title', _('Trial Balance') );
	$pdf->addInfo('Subject', _('Trial Balance') );
	$PageNumber = 0;
	$FontSize = 10;
	$line_height = 12;

	$NumberOfMonths = $_POST['ToPeriod'] - $_POST['FromPeriod'] + 1;

	$sql = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['ToPeriod'] . "'";
	$PrdResult = DB_query($sql);
	$myrow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($myrow[0]);

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];

	$SQL = "SELECT accountgroups.groupname,
			accountgroups.parentgroupname,
			accountgroups.pandl,
			chartdetails.accountcode ,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.actual ELSE 0 END) AS monthactual,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.budget ELSE 0 END) AS monthbudget,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
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
		echo '<br /><a href="' .$RootPath .'/index.php">' .  _('Back to the menu'). '</a>';
		if ($debug==1){
			echo '<br />' .  $SQL;
		}
		include('includes/footer.php');
		exit;
	}
	if (DB_num_rows($AccountsResult)==0){
		$Title = _('Print Trial Balance Error');
		include('includes/header.php');
		echo '<p>';
		prnMsg( _('There were no entries to print out for the selections specified') );
		echo '<br /><a href="'. $RootPath.'/index.php">' .  _('Back to the menu'). '</a>';
		include('includes/footer.php');
		exit;
	}

	include('includes/PDFTrialBalancePageHeader.inc');

	$Level = 1;
	$ActGrp = '';
	$ParentGroups = array();
	$ParentGroups[$Level]='';
	$GrpActual =array(0);
	$GrpBudget = array(0);
	$GrpPrdActual = array(0);
	$GrpPrdBudget = array(0);
	$PeriodProfitLoss = 0;
	$PeriodBudgetProfitLoss = 0;
	$MonthProfitLoss = 0;
	$MonthBudgetProfitLoss= 0;
	$BFwdProfitLoss = 0;
	$CheckMonth = 0;
	$CheckBudgetMonth = 0;
	$CheckPeriodActual = 0;
	$CheckPeriodBudget = 0;

	while ($myrow=DB_fetch_array($AccountsResult)) {

		if ($myrow['groupname']!= $ActGrp){

			if ($ActGrp !=''){

				// Print heading if at end of page
				if ($YPos < ($Bottom_Margin+ (2 * $line_height))) {
					include('includes/PDFTrialBalancePageHeader.inc');
				}
				if ($myrow['parentgroupname']==$ActGrp){
					$Level++;
					$ParentGroups[$Level]=$myrow['groupname'];
				}elseif ($myrow['parentgroupname']==$ParentGroups[$Level]){
					$YPos -= (.5 * $line_height);
					$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
					$pdf->setFont('','B');
					$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Total'));
					$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$ParentGroups[$Level]);
					$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
					$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
					$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
					$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
					$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
					$YPos -= (2 * $line_height);
					$pdf->setFont('','');
					$ParentGroups[$Level]=$myrow['groupname'];
					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBduget[$Level] =0;

				} else {
					do {
						$YPos -= $line_height;
						$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
						$pdf->setFont('','B');
						$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Total'));
						$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$ParentGroups[$Level]);
						$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
						$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
						$YPos -= (2 * $line_height);
						$pdf->setFont('','');
						$ParentGroups[$Level]='';
						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBduget[$Level] =0;
						$Level--;
					} while ($Level>0 AND $myrow['parentgroupname']!=$ParentGroups[$Level]);

					if ($Level>0){
						$YPos -= $line_height;
						$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
						$pdf->setFont('','B');
						$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Total'));
						$LeftOvers = $pdf->addTextWrap($Left_Margin+60, $YPos, 190, $FontSize, $ParentGroups[$Level]);
						$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
						$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
						$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
						$YPos -= (2 * $line_height);
						$pdf->setFont('','');
						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBduget[$Level] =0;
					} else {
						$Level =1;
					}
				}
			}
			$YPos -= (2 * $line_height);
				// Print account group name
			$pdf->setFont('','B');
			$ActGrp = $myrow['groupname'];
			$ParentGroups[$Level]=$myrow['groupname'];
			$FontSize = 10;
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$myrow['groupname']);
			$FontSize = 8;
			$pdf->setFont('','');
			$YPos -= (2 * $line_height);
		}

		if ($myrow['pandl']==1){

			$AccountPeriodActual = $myrow['lastprdcfwd'] - $myrow['firstprdbfwd'];
			$AccountPeriodBudget = $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];

			$PeriodProfitLoss += $AccountPeriodActual;
			$PeriodBudgetProfitLoss += $AccountPeriodBudget;
			$MonthProfitLoss += $myrow['monthactual'];
			$MonthBudgetProfitLoss += $myrow['monthbudget'];
			$BFwdProfitLoss += $myrow['firstprdbfwd'];
		} else { /*PandL ==0 its a balance sheet account */
			if ($myrow['accountcode']==$RetainedEarningsAct){
				$AccountPeriodActual = $BFwdProfitLoss + $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $BFwdProfitLoss + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			} else {
				$AccountPeriodActual = $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $myrow['firstprdbfwd'] + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			}

		}
		for ($i=0;$i<=$Level;$i++){
			if (!isset($GrpActual[$i])) {
				$GrpActual[$i]=0;
			}
			$GrpActual[$i] +=$myrow['monthactual'];
			if (!isset($GrpBudget[$i])) {
				$GrpBudget[$i]=0;
			}
			$GrpBudget[$i] +=$myrow['monthbudget'];
			if (!isset($GrpPrdActual[$i])) {
				$GrpPrdActual[$i]=0;
			}
			$GrpPrdActual[$i] +=$AccountPeriodActual;
			if (!isset($GrpPrdBudget[$i])) {
				$GrpPrdBudget[$i]=0;
			}
			$GrpPrdBudget[$i] +=$AccountPeriodBudget;
		}

		$CheckMonth += $myrow['monthactual'];
		$CheckBudgetMonth += $myrow['monthbudget'];
		$CheckPeriodActual += $AccountPeriodActual;
		$CheckPeriodBudget += $AccountPeriodBudget;

		// Print heading if at end of page
		if ($YPos < ($Bottom_Margin)){
			include('includes/PDFTrialBalancePageHeader.inc');
		}

		// Print total for each account
		$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,$myrow['accountcode']);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$myrow['accountname']);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($myrow['monthactual'],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($myrow['monthbudget'],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($AccountPeriodActual,$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($AccountPeriodBudget,$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$YPos -= $line_height;

	}  //end of while loop


	while ($Level>0 AND $myrow['parentgroupname']!=$ParentGroups[$Level]) {

		$YPos -= (.5 * $line_height);
		$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
		$pdf->setFont('','B');
		$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Total'));
		$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$ParentGroups[$Level]);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
		$YPos -= (2 * $line_height);
		$ParentGroups[$Level]='';
		$GrpActual[$Level] =0;
		$GrpBudget[$Level] =0;
		$GrpPrdActual[$Level] =0;
		$GrpPrdBduget[$Level] =0;
		$Level--;
	}


	$YPos -= (2 * $line_height);
	$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Check Totals'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,locale_number_format($CheckMonth,$_SESSION['CompanyRecord']['decimalplaces']),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($CheckBudgetMonth,$_SESSION['CompanyRecord']['decimalplaces']),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,locale_number_format($CheckPeriodActual,$_SESSION['CompanyRecord']['decimalplaces']),'right');
	$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,locale_number_format($CheckPeriodBudget,$_SESSION['CompanyRecord']['decimalplaces']),'right');
	$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);

	$pdf->OutputD($_SESSION['DatabaseName'] . '_GL_Trial_Balance_' . Date('Y-m-d') . '.pdf');
	$pdf->__destruct();
	exit;
} else {

	include('includes/header.php');
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
		<div>
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<input type="hidden" name="FromPeriod" value="' . $_POST['FromPeriod'] . '" />
			<input type="hidden" name="ToPeriod" value="' . $_POST['ToPeriod'] . '" />';

	if ($_POST['Period'] != '') {
		$_POST['FromPeriod'] = ReportPeriod($_POST['Period'], 'From');
		$_POST['ToPeriod'] = ReportPeriod($_POST['Period'], 'To');
	}

	echo '<input type="hidden" name="Period" value="' . $_POST['Period'] . '" />';

	$NumberOfMonths = $_POST['ToPeriod'] - $_POST['FromPeriod'] + 1;

	$sql = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['ToPeriod'] . "'";
	$PrdResult = DB_query($sql);
	$myrow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($myrow[0]);

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];

	$SQL = "SELECT accountgroups.groupname,
			accountgroups.parentgroupname,
			accountgroups.pandl,
			chartdetails.accountcode ,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.actual ELSE 0 END) AS monthactual,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.budget ELSE 0 END) AS monthbudget,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
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

	echo '<p class="page_title_text"><img alt="" src="'.$RootPath.'/css/'.$Theme.
		'/images/gl.png" title="' .// Icon image.
		_('Trial Balance') . '" /> ' .// Icon title.
		_('Trial Balance for the month of ') . $PeriodToDate . '<br />' .
		_(' AND for the ') . $NumberOfMonths . ' ' . _('months to') . ' ' . $PeriodToDate;// Page title.

	/*show a table of the accounts info returned by the SQL
	Account Code, Account Name, Month Actual, Month Budget, Period Actual, Period Budget */

	echo '<table cellpadding="2" class="selection">';

	$TableHeader = '<tr>
						<th>' . _('Account') . '</th>
						<th>' . _('Account Name') . '</th>
						<th>' . _('Month Actual') . '</th>
						<th>' . _('Month Budget') . '</th>
						<th>' . _('Period Actual') . '</th>
						<th>' . _('Period Budget')  . '</th>
					</tr>';

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

	while ($myrow=DB_fetch_array($AccountsResult)) {

		if ($myrow['groupname']!= $ActGrp ){
			if ($ActGrp !=''){ //so its not the first account group of the first account displayed
				if ($myrow['parentgroupname']==$ActGrp){
					$Level++;
					$ParentGroups[$Level]=$myrow['groupname'];
					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBudget[$Level] =0;
					$ParentGroups[$Level]='';
				} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
					printf('<tr>
						<td colspan="2"><i>%s ' . _('Total') . ' </i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						</tr>',
						$ParentGroups[$Level],
						locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));

					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBudget[$Level] =0;
					$ParentGroups[$Level]=$myrow['groupname'];
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
							locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
							locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
							locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
							locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));

						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBudget[$Level] =0;
						$ParentGroups[$Level]='';
						$Level--;

					} while ($Level>0 AND $myrow['groupname']!=$ParentGroups[$Level]);

					if ($Level>0){
						printf('<tr>
						<td colspan="2"><i>%s ' . _('Total') . ' </i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						</tr>',
						$ParentGroups[$Level],
						locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));

						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBudget[$Level] =0;
						$ParentGroups[$Level]='';
					} else {
						$Level=1;
					}
				}
			}
			$ParentGroups[$Level]=$myrow['groupname'];
			$ActGrp = $myrow['groupname'];
			printf('<tr>
						<td colspan="6"><h2>%s</h2></td>
					</tr>',
					$myrow['groupname']);
			echo $TableHeader;

		}

		/*MonthActual, MonthBudget, FirstPrdBFwd, FirstPrdBudgetBFwd, LastPrdBudgetCFwd, LastPrdCFwd */

		if ($myrow['pandl']==1){

			$AccountPeriodActual = $myrow['lastprdcfwd'] - $myrow['firstprdbfwd'];
			$AccountPeriodBudget = $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];

			$PeriodProfitLoss += $AccountPeriodActual;
			$PeriodBudgetProfitLoss += $AccountPeriodBudget;
			$MonthProfitLoss += $myrow['monthactual'];
			$MonthBudgetProfitLoss += $myrow['monthbudget'];
			$BFwdProfitLoss += $myrow['firstprdbfwd'];
		} else { /*PandL ==0 its a balance sheet account */
			if ($myrow['accountcode']==$RetainedEarningsAct){
				$AccountPeriodActual = $BFwdProfitLoss + $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $BFwdProfitLoss + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			} else {
				$AccountPeriodActual = $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $myrow['firstprdbfwd'] + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
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
		$GrpActual[$Level] +=$myrow['monthactual'];
		$GrpBudget[$Level] +=$myrow['monthbudget'];
		$GrpPrdActual[$Level] +=$AccountPeriodActual;
		$GrpPrdBudget[$Level] +=$AccountPeriodBudget;

		$CheckMonth += $myrow['monthactual'];
		$CheckBudgetMonth += $myrow['monthbudget'];
		$CheckPeriodActual += $AccountPeriodActual;
		$CheckPeriodBudget += $AccountPeriodBudget;

		$ActEnquiryURL = '<a href="'. $RootPath . '/GLAccountInquiry.php?FromPeriod=' . $_POST['FromPeriod'] . '&amp;ToPeriod=' . $_POST['ToPeriod'] . '&amp;Account=' . $myrow['accountcode'] . '&amp;Show=Yes">' . $myrow['accountcode'] . '</a>';

		printf('<tr class="striped_row">
				<td>%s</td>
				<td>%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				</tr>',
				$ActEnquiryURL,
				htmlspecialchars($myrow['accountname'], ENT_QUOTES,'UTF-8', false),
				locale_number_format($myrow['monthactual'],$_SESSION['CompanyRecord']['decimalplaces']),
				locale_number_format($myrow['monthbudget'],$_SESSION['CompanyRecord']['decimalplaces']),
				locale_number_format($AccountPeriodActual,$_SESSION['CompanyRecord']['decimalplaces']),
				locale_number_format($AccountPeriodBudget,$_SESSION['CompanyRecord']['decimalplaces']));
	}
	//end of while loop


	if ($ActGrp !=''){ //so its not the first account group of the first account displayed
		if ($myrow['parentgroupname']==$ActGrp){
			$Level++;
			$ParentGroups[$Level]=$myrow['groupname'];
		} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
			printf('<tr>
					<td colspan="2"><i>%s ' . _('Total') . ' </i></td>
					<td class="number"><i>%s</i></td>
					<td class="number"><i>%s</i></td>
					<td class="number"><i>%s</i></td>
					<td class="number"><i>%s</i></td>
					</tr>',
					$ParentGroups[$Level],
					locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
					locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
					locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
					locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));

			$GrpActual[$Level] =0;
			$GrpBudget[$Level] =0;
			$GrpPrdActual[$Level] =0;
			$GrpPrdBudget[$Level] =0;
			$ParentGroups[$Level]=$myrow['groupname'];
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
						locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));

				$GrpActual[$Level] =0;
				$GrpBudget[$Level] =0;
				$GrpPrdActual[$Level] =0;
				$GrpPrdBudget[$Level] =0;
				$ParentGroups[$Level]='';
				$Level--;

			} while (isset($ParentGroups[$Level]) AND ($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0));

			if ($Level >0){
				printf('<tr>
						<td colspan="2"><i>%s ' . _('Total') . ' </i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						<td class="number"><i>%s</i></td>
						</tr>',
						$ParentGroups[$Level],
						locale_number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
						locale_number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));

				$GrpActual[$Level] =0;
				$GrpBudget[$Level] =0;
				$GrpPrdActual[$Level] =0;
				$GrpPrdBudget[$Level] =0;
				$ParentGroups[$Level]='';
			} else {
				$Level =1;
			}
		}
	}



	printf('<tr style="background-color:#ffffff">
				<td colspan="2"><b>' . _('Check Totals') . '</b></td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
				<td class="number">%s</td>
			</tr>',
			locale_number_format($CheckMonth,$_SESSION['CompanyRecord']['decimalplaces']),
			locale_number_format($CheckBudgetMonth,$_SESSION['CompanyRecord']['decimalplaces']),
			locale_number_format($CheckPeriodActual,$_SESSION['CompanyRecord']['decimalplaces']),
			locale_number_format($CheckPeriodBudget,$_SESSION['CompanyRecord']['decimalplaces']));

	echo '</table><br />';

	echo '<div class="centre noprint">', // Form buttons:
			'<button onclick="javascript:window.print()" type="button"><img alt="" src="', $RootPath, '/css/', $Theme,
				'/images/printer.png" /> ', _('Print'), '</button>', // "Print" button.
			'<button name="SelectADifferentPeriod" type="submit" value="'. _('Select A Different Period') .'"><img alt="" src="'.$RootPath.'/css/'.$Theme.
				'/images/gl.png" /> ' . _('Select A Different Period') . '</button>'.// "Select A Different Period" button.
			'<button onclick="window.location=\'index.php?Application=GL\'" type="button"><img alt="" src="'.$RootPath.'/css/'.$Theme.
				'/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
		'</div>';
}
echo '</div>
	</form>';
include('includes/footer.php');

?>

<?php
// GLTagProfit_Loss.php
//

include ('includes/session.php');
$Title = _('Income and Expenditure by Tag');
include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.php'); // This loads the $Sections variable

$ViewTopic= 'GeneralLedger';
$BookMark = 'TagReports';

if (isset($_POST['PeriodFrom']) AND ($_POST['PeriodFrom'] > $_POST['PeriodTo'])) {
	prnMsg(_('The selected period from is actually after the period to') . '! ' . _('Please reselect the reporting period'),'error');
	$_POST['NewReport']='Select A Different Period';
}

if ($_POST['Period'] != '') {
	$_POST['PeriodFrom'] = ReportPeriod($_POST['Period'], 'From');
	$_POST['PeriodTo'] = ReportPeriod($_POST['Period'], 'To');
}

if ((!isset($_POST['PeriodFrom']) AND !isset($_POST['PeriodTo'])) OR isset($_POST['NewReport'])) {
	// If PeriodFrom or PeriodTo are NOT set or it is a NewReport, shows a parameters input form:

	include('includes/header.php');
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<p class="page_title_text">
			<img src="'.$RootPath, '/css/', $Theme, '/images/printer.png" title="' . _('Print') . '" alt="" />' . ' ' . $Title . '
		</p>';

	if (Date('m') > $_SESSION['YearEnd']) {
		/*Dates in SQL format */
		$DefaultFromDate = Date ('Y-m-d', Mktime(0,0,0, $_SESSION['YearEnd'] + 2,0,Date('Y')));
		$FromDate = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0, $_SESSION['YearEnd'] + 2,0,Date('Y')));
	} else {
		$DefaultFromDate = Date ('Y-m-d', Mktime(0,0,0, $_SESSION['YearEnd'] + 2,0,Date('Y')-1));
		$FromDate = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0, $_SESSION['YearEnd'] + 2,0,Date('Y')-1));
	}
	$period=GetPeriod($FromDate);

	/*Show a form to allow input of criteria for profit and loss to show */
	echo '<table class="selection">
			<tr>
				<td>' . _('Select Period From') . ':</td>
				<td><select name="PeriodFrom">';

	$SQL = "SELECT periodno,
					lastdate_in_period
			FROM periods
			ORDER BY periodno DESC";
	$Periods = DB_query($SQL);


	while ($MyRow=DB_fetch_array($Periods)) {
		if(isset($_POST['PeriodFrom']) AND $_POST['PeriodFrom']!='') {
			if( $_POST['PeriodFrom']== $MyRow['periodno']) {
				echo '<option selected="selected" value="' . $MyRow['periodno'] . '">' .MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
			}
		} else {
			if($MyRow['lastdate_in_period']==$DefaultFromDate) {
				echo '<option selected="selected" value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
			}
		}
	}

	echo '</select></td>
		</tr>';
	if (!isset($_POST['PeriodTo']) OR $_POST['PeriodTo']=='') {
		$LastDate = date('Y-m-d',mktime(0,0,0,Date('m')+1,0,Date('Y')));
		$SQL = "SELECT periodno FROM periods where lastdate_in_period = '" . $LastDate . "'";
		$MaxPrd = DB_query($SQL);
		$MaxPrdrow = DB_fetch_row($MaxPrd);
		$DefaultPeriodTo = (int) ($MaxPrdrow[0]);

	} else {
		$DefaultPeriodTo = $_POST['PeriodTo'];
	}

	echo '<tr>
			<td>' . _('Select Period To') . ':</td>
			<td><select name="PeriodTo">';

	$RetResult = DB_data_seek($Periods,0);

	while ($MyRow=DB_fetch_array($Periods)) {

		if($MyRow['periodno']==$DefaultPeriodTo) {
			echo '<option selected="selected" value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
		} else {
			echo '<option value="' . $MyRow['periodno'] . '">' . MonthAndYearFromSQLDate($MyRow['lastdate_in_period']) . '</option>';
		}
	}
	echo '</select></td></tr>';

	echo '<tr>
			<td>
				<h3>', _('OR'), '</h3>
			</td>
		</tr>';

	if (!isset($_POST['Period'])) {
		$_POST['Period'] = '';
	}

	echo '<tr>
			<td>', _('Select Period'), '</td>
			<td>', ReportPeriodList($_POST['Period'], array('l', 't')), '</td>
		</tr>';

	//Select the tag
	echo '<tr>
			<td>' . _('Select tag') . '</td>
			<td><select name="tag">';

	$SQL = "SELECT tagref,
				tagdescription
				FROM tags
				ORDER BY tagref";

	$result=DB_query($SQL);
	echo '<option value="0">0 - ' . _('None') . '</option>';
	while ($MyRow=DB_fetch_array($result)) {
    	if (isset($_POST['tag']) and $_POST['tag']==$MyRow['tagref']) {
			echo '<option selected="selected" value="' . $MyRow['tagref'] . '">' . $MyRow['tagref'].' - ' .$MyRow['tagdescription'] . '</option>';
    	} else {
			echo '<option value="' . $MyRow['tagref'] . '">' . $MyRow['tagref'].' - ' .$MyRow['tagdescription'] . '</option>';
    	}
	}
	echo '</select></td></tr>';
// End select tag

	echo '<tr>
			<td><label for="ShowDetail">', _('Detail or summary'), '</label></td>
			<td><select name="Detail">
				<option selected="selected" value="Summary">' . _('Summary') . '</option>
				<option selected="selected" value="Detailed">' . _('All Accounts') . '</option>
				</select>
			</td>
		</tr>
		</table>
		<br />',

		'<div class="centre noprint">', // Form buttons:
			'<button name="ShowPL" type="submit" value="', _('Show Statement of Income and Expenditure'), '"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/reports.png" /> ', _('Show Statement of Income and Expenditure'), '</button>', // "Show Statement of Income and Expenditure" button.
			'<button name="PrintPDF" type="submit" value="', _('PrintPDF'), '"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/pdf.png" /> ', _('PrintPDF'), '</button>', // "PrintPDF" button.
			'<button onclick="window.location=\'index.php?Application=GL\'" type="button"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
		'</div>',
		'</form>';

	// Now do the posting while the user is thinking about the period to select:
	include ('includes/GLPostings.inc');

} else if (isset($_POST['PrintPDF'])) {

	$tagsql="SELECT tagdescription FROM tags WHERE tagref='".$_POST['tag']."'";
	$tagresult=DB_query($tagsql);
	$tagrow=DB_fetch_array($tagresult);
	$Tag=$tagrow['tagdescription'];
	include('includes/PDFStarter.php');
	$pdf->addInfo('Title', _('Income and Expenditure') );
	$pdf->addInfo('Subject', _('Income and Expenditure') );
	$PageNumber = 0;
	$FontSize = 10;
	$line_height = 12;

	$NumberOfMonths = $_POST['PeriodTo'] - $_POST['PeriodFrom'] + 1;

	if ($NumberOfMonths > 12) {
		include('includes/header.php');
		prnMsg(_('A period up to 12 months in duration can be specified') . ' - ' . _('the system automatically shows a comparative for the same period from the previous year') . ' - ' . _('it cannot do this if a period of more than 12 months is specified') . '. ' . _('Please select an alternative period range'),'error');
		include('includes/footer.php');
		exit;
	}

	$SQL = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['PeriodTo'] . "'";
	$PrdResult = DB_query($SQL);
	$MyRow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($MyRow[0]);


	$SQL = "SELECT accountgroups.sectioninaccounts,
					accountgroups.groupname,
					accountgroups.parentgroupname,
					gltrans.account ,
					chartmaster.accountname,
					Sum(CASE WHEN (gltrans.periodno>='" . $_POST['PeriodFrom'] . "' and gltrans.periodno<='" . $_POST['PeriodTo'] . "') THEN gltrans.amount ELSE 0 END) AS TotalAllPeriods,
					Sum(CASE WHEN (gltrans.periodno='" . $_POST['PeriodTo'] . "') THEN gltrans.amount ELSE 0 END) AS TotalThisPeriod
			FROM chartmaster
				INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
				INNER JOIN gltrans ON chartmaster.accountcode= gltrans.account
				INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
			WHERE accountgroups.pandl=1
			AND gltrans.tag='" . $_POST['tag'] . "'
			GROUP BY accountgroups.sectioninaccounts,
					accountgroups.groupname,
					accountgroups.parentgroupname,
					gltrans.account,
					chartmaster.accountname,
					accountgroups.sequenceintb
			ORDER BY accountgroups.sectioninaccounts,
					accountgroups.sequenceintb,
					accountgroups.groupname,
					gltrans.account";

	$AccountsResult = DB_query($SQL);

	if (DB_error_no() != 0) {
		$Title = _('Income and Expenditure') . ' - ' . _('Problem Report') . '....';
		include('includes/header.php');
		prnMsg( _('No general ledger accounts were returned by the SQL because') . ' - ' . DB_error_msg() );
		echo '<br />
				<a href="' .$RootPath, '/index.php">' .  _('Back to the menu'). '</a>';
		if ($debug == 1) {
			echo '<br />' .  $SQL;
		}
		include('includes/footer.php');
		exit;
	}
	if (DB_num_rows($AccountsResult)==0) {
		$Title = _('Print Income and Expenditure Error');
		include('includes/header.php');
		echo '<br />';
		prnMsg( _('There were no entries to print out for the selections specified'),'info');
		echo '<br />
				<a href="'. $RootPath, '/index.php">' .  _('Back to the menu'). '</a>';
		include('includes/footer.php');
		exit;
	}

	include('includes/PDFTagProfitAndLossPageHeader.inc');

	$Section = '';
	$SectionPrdActual = 0;

	$ActGrp = '';
	$ParentGroups = array();
	$Level = 0;
	$ParentGroups[$Level]='';
	$GrpPrdActual = array(0);
	$PeriodProfitLoss = 0;
	while ($MyRow = DB_fetch_array($AccountsResult)) {

		// Print heading if at end of page
		if ($YPos < ($Bottom_Margin)) {
			include('includes/PDFTagProfitAndLossPageHeader.inc');
		}

		if ($MyRow['groupname'] != $ActGrp) {
			if ($ActGrp != '') {
				if ($MyRow['parentgroupname']!=$ActGrp) {
					while ($MyRow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
						if ($_POST['Detail'] == 'Detailed') {
							$ActGrpLabel = $ParentGroups[$Level] . ' ' . _('total');
						} else {
							$ActGrpLabel = $ParentGroups[$Level];
						}
						if ($Section == 1) { /*Income */
							$LeftOvers = $pdf->addTextWrap($Left_Margin +($Level*10),$YPos,200 -($Level*10),$FontSize,$ActGrpLabel);
							$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
							$YPos -= (2 * $line_height);
						} else { /*Costs */
							$LeftOvers = $pdf->addTextWrap($Left_Margin +($Level*10),$YPos,200 -($Level*10),$FontSize,$ActGrpLabel);
							$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
							$YPos -= (2 * $line_height);
						}
						$GrpPrdLY[$Level] = 0;
						$GrpPrdActual[$Level] = 0;
						$GrpPrdBudget[$Level] = 0;
						$ParentGroups[$Level] ='';
						$Level--;
// Print heading if at end of page
						if ($YPos < ($Bottom_Margin + (2*$line_height))) {
							include('includes/PDFTagProfitAndLossPageHeader.inc');
						}
					} //end of loop
					//still need to print out the group total for the same level
					if ($_POST['Detail'] == 'Detailed') {
						$ActGrpLabel = $ParentGroups[$Level] . ' ' . _('total');
					} else {
						$ActGrpLabel = $ParentGroups[$Level];
					}
					if ($Section == 1) { /*Income */
						$LeftOvers = $pdf->addTextWrap($Left_Margin +($Level*10),$YPos,200 -($Level*10),$FontSize,$ActGrpLabel); $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$YPos -= (2 * $line_height);
					} else { /*Costs */
						$LeftOvers = $pdf->addTextWrap($Left_Margin +($Level*10),$YPos,200 -($Level*10),$FontSize,$ActGrpLabel);
						$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
						$YPos -= (2 * $line_height);
					}
					$GrpPrdActual[$Level] = 0;
					$ParentGroups[$Level] ='';
				}
			}
		}

		// Print heading if at end of page
		if ($YPos < ($Bottom_Margin +(2 * $line_height))) {
			include('includes/PDFTagProfitAndLossPageHeader.inc');
		}

		if ($MyRow['sectioninaccounts'] != $Section) {

			$pdf->setFont('','B');
			$FontSize =10;
			if ($Section != '') {
				$pdf->line($Left_Margin+310, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
				$pdf->line($Left_Margin+310, $YPos,$Left_Margin+500, $YPos);
				if ($Section == 1) { /*Income*/

					$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$Sections[$Section]);
					$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$YPos -= (2 * $line_height);

					$TotalIncome = -$SectionPrdActual;
					$TotalBudgetIncome = -$SectionPrdBudget;
					$TotalLYIncome = -$SectionPrdLY;
				} else {
					$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$Sections[$Section]);
					$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(-$SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$YPos -= (2 * $line_height);
				}
				if ($Section == 2) { /*Cost of Sales - need sub total for Gross Profit*/
					$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,_('Gross Profit'));
					$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($TotalIncome - $SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$pdf->line($Left_Margin+310, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
					$pdf->line($Left_Margin+310, $YPos,$Left_Margin+500, $YPos);
					$YPos -= (2 * $line_height);

					if ($TotalIncome != 0) {
						$PrdGPPercent = 100 *($TotalIncome - $SectionPrdActual) / $TotalIncome;
					} else {
						$PrdGPPercent = 0;
					}
					$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,_('Gross Profit Percent'));
					$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($PrdGPPercent,1) . '%','right');
					$YPos -= (2 * $line_height);
				}
			}
			$SectionPrdActual = 0;
			$SectionPrdBudget = 0;
			$SectionPrdLY = 0;

			$Section = $MyRow['sectioninaccounts'];

			if ($_POST['Detail'] == 'Detailed') {
				$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$Sections[$MyRow['sectioninaccounts']]);
				$YPos -= (2 * $line_height);
			}
			$FontSize =8;
			$pdf->setFont('','');
		}

		if ($MyRow['groupname'] != $ActGrp) {
			if ($MyRow['parentgroupname']==$ActGrp AND $ActGrp !='') { //adding another level of nesting
					$Level++;
			}
			$ActGrp = $MyRow['groupname'];
			$ParentGroups[$Level]=$ActGrp;
			if ($_POST['Detail'] == 'Detailed') {
				$FontSize =10;
				$pdf->setFont('','B');
				$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$MyRow['groupname']);
				$YPos -= (2 * $line_height);
				$FontSize =8;
				$pdf->setFont('','');
			}
		}

		$AccountPeriodActual = $MyRow['TotalAllPeriods'];
		$PeriodProfitLoss += $AccountPeriodActual;

		for ($i=0;$i<=$Level;$i++) {
//			$GrpPrdLY[$i] +=$AccountPeriodLY;
		}


		$SectionPrdActual +=$AccountPeriodActual;

		if ($_POST['Detail'] == _('Detailed')) {
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,$MyRow['account']);
			$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$MyRow['accountname']);
			if ($Section == 1) { /*Income*/
				$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($AccountPeriodActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
			} else {
				$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(-$AccountPeriodActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
			}
			$YPos -= $line_height;
		}
	}
	//end of loop

	if ($ActGrp != '') {

		if ($MyRow['parentgroupname']!=$ActGrp) {

			while ($MyRow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
				if ($_POST['Detail'] == 'Detailed') {
					$ActGrpLabel = $ParentGroups[$Level] . ' ' . _('total');
				} else {
					$ActGrpLabel = $ParentGroups[$Level];
				}
				if ($Section == 1) { /*Income */
					$LeftOvers = $pdf->addTextWrap($Left_Margin +($Level*10),$YPos,200 -($Level*10),$FontSize,$ActGrpLabel);
					$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$YPos -= (2 * $line_height);
				} else { /*Costs */
					$LeftOvers = $pdf->addTextWrap($Left_Margin +($Level*10),$YPos,200 -($Level*10),$FontSize,$ActGrpLabel);
					$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
					$YPos -= (2 * $line_height);
				}
				$GrpPrdActual[$Level] = 0;
				$ParentGroups[$Level] ='';
				$Level--;
				// Print heading if at end of page
				if ($YPos < ($Bottom_Margin + (2*$line_height))) {
					include('includes/PDFTagProfitAndLossPageHeader.inc');
				}
			}
			//still need to print out the group total for the same level
			if ($_POST['Detail'] == 'Detailed') {
				$ActGrpLabel = $ParentGroups[$Level] . ' ' . _('total');
			} else {
				$ActGrpLabel = $ParentGroups[$Level];
			}
			if ($Section == 1) { /*Income */
				$LeftOvers = $pdf->addTextWrap($Left_Margin +($Level*10),$YPos,200 -($Level*10),$FontSize,$ActGrpLabel); $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
				$YPos -= (2 * $line_height);
			} else { /*Costs */
				$LeftOvers = $pdf->addTextWrap($Left_Margin +($Level*10),$YPos,200 -($Level*10),$FontSize,$ActGrpLabel);
				$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']),'right');
				$YPos -= (2 * $line_height);
			}
			$GrpPrdActual[$Level] = 0;
			$ParentGroups[$Level] ='';
		}
	}
	// Print heading if at end of page
	if ($YPos < ($Bottom_Margin + (2*$line_height))) {
		include('includes/PDFTagProfitAndLossPageHeader.inc');
	}
	if ($Section != '') {

		$pdf->setFont('','B');
		$pdf->line($Left_Margin+310, $YPos+10,$Left_Margin+500, $YPos+10);
		$pdf->line($Left_Margin+310, $YPos,$Left_Margin+500, $YPos);

		if ($Section == 1) { /*Income*/
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize,$Sections[$Section]);
			$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(-$SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
			$YPos -= (2 * $line_height);

			$TotalIncome = -$SectionPrdActual;
			$TotalBudgetIncome = -$SectionPrdBudget;
			$TotalLYIncome = -$SectionPrdLY;
		} else {
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,$Sections[$Section]);
			$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
			$YPos -= (2 * $line_height);
		}
		if ($Section == 2) { /*Cost of Sales - need sub total for Gross Profit*/
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Gross Profit'));
			$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format($TotalIncome - $SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']),'right');
			$YPos -= (2 * $line_height);

			$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(100*($TotalIncome - $SectionPrdActual)/$TotalIncome,1) . '%','right');
			$YPos -= (2 * $line_height);
		}
	}

	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Profit').' - '._('Loss'));
	$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,locale_number_format(-$PeriodProfitLoss, $_SESSION['CompanyRecord']['decimalplaces']),'right');

	$pdf->line($Left_Margin+310, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
	$pdf->line($Left_Margin+310, $YPos,$Left_Margin+500, $YPos);

	$pdf->OutputD($_SESSION['DatabaseName'] . '_' .'Tag_Income_Statement_' . date('Y-m-d').'.pdf');
	$pdf->__destruct();
	exit;

} else {
	include('includes/header.php');

	$NumberOfMonths = $_POST['PeriodTo'] - $_POST['PeriodFrom'] + 1;

	if ($NumberOfMonths >12) {
		echo '<br />';
		prnMsg(_('A period up to 12 months in duration can be specified') . ' - ' . _('the system automatically shows a comparative for the same period from the previous year') . ' - ' . _('it cannot do this if a period of more than 12 months is specified') . '. ' . _('Please select an alternative period range'),'error');
		include('includes/footer.php');
		exit;
	}

	$SQL = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['PeriodTo'] . "'";
	$PrdResult = DB_query($SQL);
	$MyRow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($MyRow[0]);


	$SQL = "SELECT accountgroups.sectioninaccounts,
					accountgroups.groupname,
					accountgroups.parentgroupname,
					gltrans.account,
					chartmaster.accountname,
					Sum(CASE WHEN (gltrans.periodno>='" . $_POST['PeriodFrom'] . "' AND gltrans.periodno<='" . $_POST['PeriodTo'] . "') THEN gltrans.amount ELSE 0 END) AS TotalAllPeriods,
					Sum(CASE WHEN (gltrans.periodno='" . $_POST['PeriodTo'] . "') THEN gltrans.amount ELSE 0 END) AS TotalThisPeriod
			FROM chartmaster INNER JOIN accountgroups
			ON chartmaster.group_ = accountgroups.groupname INNER JOIN gltrans
			ON chartmaster.accountcode= gltrans.account
			WHERE accountgroups.pandl=1
			AND gltrans.tag='" . $_POST['tag'] . "'
			GROUP BY accountgroups.sectioninaccounts,
					accountgroups.groupname,
					accountgroups.parentgroupname,
					gltrans.account,
					chartmaster.accountname
			ORDER BY accountgroups.sectioninaccounts,
					accountgroups.sequenceintb,
					accountgroups.groupname,
					gltrans.account";


	$AccountsResult = DB_query($SQL,_('No general ledger accounts were returned by the SQL because'),_('The SQL that failed was'));
	$SQL="SELECT tagdescription FROM tags WHERE tagref='".$_POST['tag'] . "'";
	$result=DB_query($SQL);
	$MyRow=DB_fetch_row($result);

	/*show a table of the accounts info returned by the SQL
	Account Code ,   Account Name , Month Actual, Month Budget, Period Actual, Period Budget */
	echo '<p class="page_title_text"><img src="'.$RootPath, '/css/', $Theme, '/images/printer.png" title="'
		. _('Print') . '" alt="" />' . ' ' . $Title . '</p>';

	echo '<table cellpadding="2" class="selection">';
	echo '<tr>
			<th colspan="9">
				<div class="centre">
					<h2><b>' . _('Statement of Income and Expenditure for Tag'). ' ' . $MyRow[0]. ' ' . _('during the'). ' ' . $NumberOfMonths . ' ' . _('months to'). ' ' . $PeriodToDate . '</b></h2>
				</div>
			</th>
		</tr>';

	if ($_POST['Detail']=='Detailed') {
		$TableHeader = '<tr>
							<th>' . _('Account') . '</th>
							<th>' . _('Account Name') . '</th>
							<th colspan="2">' . _('Period Actual') . '</th>
						</tr>';
	} else { /*summary */
		$TableHeader = '<tr>
							<th colspan="2"></th>
							<th colspan="2">' . _('Period Actual') . '</th>
						</tr>';
	}



	$Section='';
	$SectionPrdActual= 0;
	$SectionPrdLY 	 = 0;
	$SectionPrdBudget= 0;

	$PeriodProfitLoss = 0;
	$PeriodLYProfitLoss = 0;
	$PeriodBudgetProfitLoss = 0;

	$ActGrp = '';
	$ParentGroups = array();
	$Level = 0;
	$ParentGroups[$Level]= '';
	$GrpPrdActual = array(0);
	$GrpPrdLY = array(0);
	$GrpPrdBudget = array(0);
	$TotalIncome = 0;

	while ($MyRow=DB_fetch_array($AccountsResult)) {


		if ($MyRow['groupname']!= $ActGrp) {
			if ($MyRow['parentgroupname']!=$ActGrp AND $ActGrp!='') {
					while ($MyRow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
					if ($_POST['Detail']=='Detailed') {
						echo '<tr>
								<td colspan="2"></td>
								<td colspan="6"><hr /></td>
							</tr>';
						$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . ' ' . _('total');
					} else {
						$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
					}

				if ($Section ==3) { /*Income */
						printf('<tr>
									<td colspan="2"><h4><i>%s </i></h4></td>
									<td></td>
									<td class="number">%s</td>
								</tr>',
								$ActGrpLabel,
								locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']));
					} else { /*Costs */
						printf('<tr>
									<td colspan="2"><h4><i>%s </i></h4></td>
									<td class="number">%s</td>
									<td></td>
								</tr>',
								$ActGrpLabel,
								locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']));
					}
					$GrpPrdLY[$Level] = 0;
					$GrpPrdActual[$Level] = 0;
					$GrpPrdBudget[$Level] = 0;
					$ParentGroups[$Level] ='';
					$Level--;
				}//end while
				//still need to print out the old group totals
				if ($_POST['Detail']=='Detailed') {
						echo '<tr>
								<td colspan="2"></td>
								<td colspan="6"><hr /></td>
							</tr>';
						$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . ' ' . _('total');
					} else {
						$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
					}

				if ($Section ==4) { /*Income */
					printf('<tr>
								<td colspan="2"><h4><i>%s </i></h4></td>
								<td></td>
								<td class="number">%s</td>
							</tr>',
							$ActGrpLabel,
							locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']));
				} else { /*Costs */
					printf('<tr>
								<td colspan="2"><h4><i>%s</i></h4></td>
								<td class="number">%s</td>
								<td></td>
							</tr>',
							$ActGrpLabel,
							locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']));
				}
				$GrpPrdActual[$Level] = 0;
				$ParentGroups[$Level] ='';
			}
		}

		if ($MyRow['sectioninaccounts']!= $Section) {

			if ($SectionPrdLY+$SectionPrdActual+$SectionPrdBudget !=0) {
				if ($Section==4) { /*Income*/

					echo '<tr>
							<td colspan="2"></td>
							<td><hr /></td>
							<td></td>
							<td><hr /></td>
						</tr>';

					printf('<tr>
								<td colspan="2"><h2>%s</h2></td>
								<td></td>
								<td class="number">%s</td>
							</tr>',
							$Sections[$Section],
							locale_number_format($SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']));

				} else {
					echo '<tr>
							<td colspan="2"></td>
							<td colspan="2"><hr /></td>
						</tr>';
					printf('<tr>
								<td colspan="2"><h2>%s</h2></td>
								<td></td>
								<td class="number">%s</td>
							</tr>',
							$Sections[$Section],
							locale_number_format($SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']));
				}
				if ($Section==1) {
					$TotalIncome+=$SectionPrdActual;
				}

				if ($Section==2) { /*Cost of Sales - need sub total for Gross Profit*/
					echo '<tr>
							<td colspan="2"></td>
							<td colspan="5"><hr /></td>
						</tr>';
					printf('<tr>
								<td colspan="2"><h2>' . _('Gross Profit') . '</h2></td>
								<td></td>
								<td class="number">%s</td>
							</tr>',
							locale_number_format($TotalIncome + $SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']));

					if ($TotalIncome !=0) {
						$PrdGPPercent = 100*($TotalIncome + $SectionPrdActual)/$TotalIncome;
					} else {
						$PrdGPPercent = 0;
					}
					echo '<tr>
							<td colspan="2"></td>
							<td colspan="6"><hr /></td>
						</tr>';
					printf('<tr>
							<td colspan="2"><h4><i>' . _('Gross Profit Percent') . '</i></h4></td>
							<td></td>
							<td class="number"><i>%s</i></td>
							</tr><tr><td colspan="6"> </td></tr>',
							locale_number_format($PrdGPPercent,1) . '%');
				}
			}
			$SectionPrdActual = 0;

			$Section = $MyRow['sectioninaccounts'];

			if ($_POST['Detail']=='Detailed') {
				printf('<tr>
							<td colspan="6"><h2><b>%s</b></h2></td>
						</tr>',
						$Sections[$MyRow['sectioninaccounts']]);
			}
		}

		if ($MyRow['groupname']!= $ActGrp) {

			if ($MyRow['parentgroupname']==$ActGrp AND $ActGrp !='') { //adding another level of nesting
				$Level++;
			}

			$ParentGroups[$Level] = $MyRow['groupname'];
			$ActGrp = $MyRow['groupname'];
			if ($_POST['Detail']=='Detailed') {
				printf('<tr>
							<td colspan="6"><h4><b>%s</b></h4></td>
						</tr>',
						$MyRow['groupname']);

				echo $TableHeader;
			}
		}

		$AccountPeriodActual = $MyRow['TotalAllPeriods'];
		if ($Section==4) {
			$PeriodProfitLoss -= $AccountPeriodActual;
		} else {
			$PeriodProfitLoss -= $AccountPeriodActual;
		}

		for ($i=0;$i<=$Level;$i++) {
			if (!isset($GrpPrdActual[$i])) {$GrpPrdActual[$i]=0;}
			$GrpPrdActual[$i] +=$AccountPeriodActual;
		}
		$SectionPrdActual -=$AccountPeriodActual;

		if ($_POST['Detail']==_('Detailed')) {

			$ActEnquiryURL = '<a href="' . $RootPath . '/GLAccountInquiry.php?PeriodFrom=' . urlencode($_POST['PeriodFrom']) . '&amp;PeriodTo=' . urlencode($_POST['PeriodTo']) . '&amp;Account=' . urlencode($MyRow['account']) . '&amp;Show=Yes">' . $MyRow['account'] . '</a>';

			if ($Section ==4) {
				 printf('<tr class="striped_row">
						<td>%s</td>
						<td>%s</td>
						<td></td>
						<td class="number">%s</td>
						</tr>',
						$ActEnquiryURL,
						htmlspecialchars($MyRow['accountname'], ENT_QUOTES,'UTF-8', false),
						locale_number_format(-$AccountPeriodActual, $_SESSION['CompanyRecord']['decimalplaces']));
			} else {
				printf('<tr class="striped_row">
						<td>%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						</tr>',
						$ActEnquiryURL,
						htmlspecialchars($MyRow['accountname'], ENT_QUOTES,'UTF-8', false),
						locale_number_format(-$AccountPeriodActual, $_SESSION['CompanyRecord']['decimalplaces']));
			}
		}
	}
	//end of loop


	if ($MyRow['groupname']!= $ActGrp) {
		if ($MyRow['parentgroupname']!=$ActGrp AND $ActGrp!='') {
			while ($MyRow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
				if ($_POST['Detail']=='Detailed') {
					echo '<tr>
							<td colspan="2"></td>
							<td colspan="4"><hr /></td>
						</tr>';
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . ' ' . _('total');
				} else {
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
				}
				if ($Section ==4) { /*Income */
					echo '<tr>
							<td colspan="2"><h4><i>' . $ActGrpLabel . '</i></h4></td>
							<td></td>
							<td class="number">' . locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
						</tr>';
				} else { /*Costs */
					echo '<tr>
							<td colspan="2"><h4><i>' . $ActGrpLabel . '</i></h4></td>
							<td class="number">' . locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
						</tr>';
				}
				$GrpPrdActual[$Level] = 0;
				$ParentGroups[$Level] ='';
				$Level--;
			}//end while
			//still need to print out the old group totals
			if ($_POST['Detail']=='Detailed') {
					echo '<tr>
							<td colspan="2"></td>
							<td colspan="4"><hr /></td>
						</tr>';
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . ' ' . _('total');
				} else {
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
				}

			if ($Section ==4) { /*Income */
				printf('<tr>
						<td colspan="2"><h4><i>%s</i></h4></td>
						<td></td>
						<td class="number">%s</td>
						</tr>',
						$ActGrpLabel,
						locale_number_format(-$GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']));
			} else { /*Costs */
				printf('<tr>
						<td colspan="2"><h4><i>%s </i></h4></td>
						<td class="number">%s</td>
						<td></td>
						</tr>',
						$ActGrpLabel,
						locale_number_format($GrpPrdActual[$Level], $_SESSION['CompanyRecord']['decimalplaces']));
			}
			$GrpPrdActual[$Level] = 0;
			$ParentGroups[$Level] ='';
		}
	}

	if ($MyRow['sectioninaccounts']!= $Section) {

		if ($Section==4) { /*Income*/

			echo '<tr>
					<td colspan="2"></td>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td colspan="2"><h2>' . $Sections[$Section] . '</h2></td>
					<td></td>
					<td class="number">' . locale_number_format($SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				</tr>';
				$TotalIncome = $SectionPrdActual;
		} else {
			echo '<tr>
					<td colspan="2"></td>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td colspan="2"><h2>' . $Sections[$Section] . '</h2></td>
					<td></td>
					<td class="number">' . locale_number_format(-$SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				</tr>';
		}
		if ($Section==2) { /*Cost of Sales - need sub total for Gross Profit*/
			echo '<tr>
					<td colspan="2"></td>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td colspan="2"><h2>' . _('Gross Profit') . '</h2></td>
					<td></td>
					<td class="number">' . locale_number_format($TotalIncome - $SectionPrdActual, $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
				</tr>';

			if ($TotalIncome !=0) {
				$PrdGPPercent = 100*($TotalIncome - $SectionPrdActual)/$TotalIncome;
			} else {
				$PrdGPPercent = 0;
			}
			echo '<tr>
					<td colspan="2"></td>
					<td colspan="2"><hr /></td>
				</tr>
				<tr>
					<td colspan="2"><h4><i>' . _('Gross Profit Percent') . '</i></h4></td>
					<td></td>
					<td class="number"><i>' . locale_number_format($PrdGPPercent,1) . '%</i></td>
					<td></td>
				</tr>';
		}

		$SectionPrdActual = 0;

		$Section = $MyRow['sectioninaccounts'];

		if ($_POST['Detail']=='Detailed' AND isset($Sections[$MyRow['sectioninaccounts']])) {
			echo '<tr>
					<td colspan="6"><h2><b>' . $Sections[$MyRow['sectioninaccounts']] . '</b></h2></td>
				</tr>';
		}
	}

	echo '<tr>
			<td colspan="2"></td>
			<td colspan="2"><hr /></td>
		</tr>',
		'<tr style="background-color:#ffffff">
			<td colspan="2"><h2><b>' . _('Surplus').' - '._('Deficit') . '</b></h2></td>
			<td>&nbsp;</td>
			<td class="number">', locale_number_format($PeriodProfitLoss, $_SESSION['CompanyRecord']['decimalplaces']), '</td>
		</tr>',
		'<tr>
			<td colspan="2"></td>
			<td colspan="4"><hr /></td>
		</tr>
		</table>';

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
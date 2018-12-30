<?php

/* Timesheet Entry */

include('includes/session.php');
$Title = _('Timesheet Entry');// Screen identification.
$ViewTopic = 'Labour';// Filename's id in ManualContents.php's TOC.
$BookMark = 'Timesheets';// Anchor's id in the manual's html document.


$MaxHours = 15; // perhaps this should be a configuration option??

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/clock.png" title="',// Icon image.
	_('Timesheets'), '" /> ',// Icon title.
	_('Timesheet Entry'), '</p>';// Page title.

//try to set some sensible defaults
$LatestWeekEndingDate = Date($_SESSION['DefaultDateFormat'],mktime(0,0,0,date('n'),date('j')-(date('w')+$_SESSION['LastDayOfWeek'])+7,date('Y')));

if(isset($_GET['SelectedEmployee'])) { 
	if ($_GET['SelectedEmployee']=='NewSelection'){
		unset($SelectedEmployee);
	} else {
		$SelectedEmployee = $_GET['SelectedEmployee'];
	}
} elseif(isset($_POST['SelectedEmployee'])) {
	$SelectedEmployee = $_POST['SelectedEmployee'];
} else {
	$CheckUserResult = DB_query("SELECT id FROM employees WHERE userid='" . $_SESSION['UserID'] . "'");
	if (DB_num_rows($CheckUserResult)>0) { // then there is an employee match with the logged in user - in which case assume we are inputting their timesheet
		$LoggedInEmployeeRow = DB_fetch_array($CheckUserResult);
		$SelectedEmployee = $LoggedInEmployeeRow['id'];
	}
}

if (isset($_GET['WeekEnding'])) {
	$_POST['WeekEnding'] = $_GET['WeekEnding'];
} elseif (!isset($_POST['WeekEnding'])) {
	$_POST['WeekEnding'] = $LatestWeekEndingDate;
}

if (isset($SelectedEmployee)) { //Get the employee's details
	$sql = "SELECT id,
					surname,
					firstname,
					employees.stockid,
					manager,
					normalhours,
					userid,
					email,
					decimalplaces
			FROM employees
			INNER JOIN stockmaster
			ON employees.stockid=stockmaster.stockid
			WHERE employees.id='" . $SelectedEmployee . "'";
	
	$EmployeeResult = DB_query($sql);
	$EmployeeRow = DB_fetch_array($EmployeeResult);
	
	if ($EmployeeRow['userid']!='') { //get the employee's location if they are a user set up in webERP
		$LocationResult = DB_query("SELECT defaultlocation FROM www_users WHERE userid='" . $EmployeeRow['userid'] ."'");
		$EmployeeLocationRow = DB_fetch_array($LocationResult);
		$EmployeeLocation = $EmployeeLocationRow['defaultlocation'];
	} else {
		$EmployeeLocation ='';
	}
}


if((isset($_POST['Enter']) OR isset($_POST['ApproveTimesheet']) OR isset($_POST['SubmitForApproval'])) AND isset($SelectedEmployee) AND isset($_POST['WeekEnding'])) {
	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */


	/* First off - run through the previously entered rows to update any changes in hours entered */
	if (isset($_POST['Rows']) AND $_POST['Rows'] > 0) {
		for ($Row=0; $Row < $_POST['Rows']; $Row++) {
			$InputError = 0;
			if (!is_numeric($_POST['Day1_' . $Row])){
				$_POST['Day1_' . $Row] = 0;
			}
			if (!is_numeric($_POST['Day2_' . $Row])){
				$_POST['Day2_' . $Row] = 0;
			}
			if (!is_numeric($_POST['Day3_' . $Row])){
				$_POST['Day3_' . $Row] = 0;
			}
			if (!is_numeric($_POST['Day4_' . $Row])){
				$_POST['Day4_' . $Row] = 0;
			}
			if (!is_numeric($_POST['Day5_' . $Row])){
				$_POST['Day5_' . $Row] = 0;
			}
			if (!is_numeric($_POST['Day6_' . $Row])){
				$_POST['Day6_' . $Row] = 0;
			}
			if (!is_numeric($_POST['Day7_' . $Row])){
				$_POST['Day7_' . $Row] = 0;
			}
			if (($_POST['Day1_' . $Row]+$_POST['Day2_' . $Row]+$_POST['Day3_' . $Row]+$_POST['Day4_' . $Row]+$_POST['Day5_' . $Row]+$_POST['Day6_' . $Row]+$_POST['Day7_' . $Row]) == 0){
				$InputError = 1;
				prnMsg(_('The total hours entered are zero for this line - so better to delete the line'),'error');
			}

			
			if ($_POST['Day1_' . $Row] > $MaxHours OR $_POST['Day1_' . $Row] < -$MaxHours) {
				$InputError = 1;
				prnMsg(_('The hours entered for day 1 look to be too high - this is probably an error'),'error');
			}
			if ($_POST['Day2_' . $Row] > $MaxHours OR $_POST['Day2_' . $Row] < -$MaxHours) {
				$InputError = 1;
				prnMsg(_('The hours entered for day 2 look to be too high - this is probably an error'),'error');
			}
			if ($_POST['Day3_' . $Row] > $MaxHours OR $_POST['Day3_' . $Row] < -$MaxHours) {
				$InputError = 1;
				prnMsg(_('The hours entered for day 3 look to be too high - this is probably an error'),'error');
			}
			if ($_POST['Day4_' . $Row] > $MaxHours OR $_POST['Day4_' . $Row] < -$MaxHours) {
				$InputError = 1;
				prnMsg(_('The hours entered for day 4 look to be too high - this is probably an error'),'error');
			}
			if ($_POST['Day5_' . $Row] > $MaxHours OR $_POST['Day5_' . $Row] < -$MaxHours) {
				$InputError = 1;
				prnMsg(_('The hours entered for day 5 look to be too high - this is probably an error'),'error');
			}
			if (!$_POST['Day6_' . $Row] > $MaxHours OR $_POST['Day6_' . $Row] < -$MaxHours) {
				$InputError = 1;
				prnMsg(_('The hours entered for day 6 look to be too high - this is probably an error'),'error');
			}
			if (!$_POST['Day7_' . $Row] > $MaxHours OR $_POST['Day7_' . $Row] < -$MaxHours) {
				$InputError = 1;
				prnMsg(_('The hours entered for day 7 look to be too high - this is probably an error'),'error');
			}
			if ($InputError == 0 ) { //error free :-)

				$RowUpdateResult = DB_query("UPDATE timesheets
											SET day1 ='" . $_POST['Day1_' . $Row] . "',
												day2 ='" . $_POST['Day2_' . $Row] . "',
												day3 ='" . $_POST['Day3_' . $Row] . "',
												day4 ='" . $_POST['Day4_' . $Row] . "',
												day5 ='" . $_POST['Day5_' . $Row] . "',
												day6 ='" . $_POST['Day6_' . $Row] . "',
												day7 ='" . $_POST['Day7_' . $Row] . "'
											WHERE id='" . $_POST['id_' . $Row] . "'");
			}
		} //end of for loop


	} /*end if there were existing timesheet recorded for the employee/weekending to update */

	/* Now error trapping for the any new entry */
	if ($_POST['WO'] == '0' AND $_POST['WorkCentre'] != '0') {
		prnMsg(_('If the time is non-chargable then both the work order and the work centre must reflect this. Only if a work order is selected can a work centre be set'),'error');
		$InputError = 1;
	}
	if ($_POST['WO'] != '0' AND $_POST['WorkCentre'] == '0') {
		prnMsg(_('If a work order is selected then the work centre must be set'),'error');
		$InputError = 1;
	}
	if (!is_numeric(filter_number_format($_POST['Day1']))){
		$_POST['Day1'] = 0;
	}
	if (!is_numeric(filter_number_format($_POST['Day2']))){
		$_POST['Day2'] = 0;
	}
	if (!is_numeric(filter_number_format($_POST['Day3']))){
		$_POST['Day3'] = 0;
	}
	if (!is_numeric(filter_number_format($_POST['Day4']))){
		$_POST['Day4'] = 0;
	}
	if (!is_numeric(filter_number_format($_POST['Day5']))){
		$_POST['Day5'] = 0;
	}
	if (!is_numeric(filter_number_format($_POST['Day6']))){
		$_POST['Day6'] = 0;
	}
	if (!is_numeric(filter_number_format($_POST['Day7']))){
		$_POST['Day7'] = 0;
	}

	if ((filter_number_format($_POST['Day1'])+filter_number_format($_POST['Day2'])+filter_number_format($_POST['Day3'])+filter_number_format($_POST['Day4'])+filter_number_format($_POST['Day5'])+filter_number_format($_POST['Day6'])+filter_number_format($_POST['Day7'])) == 0 ){
		$InputError = 1; //just ignore it quietly
	}

	
	if (filter_number_format($_POST['Day1']) > $MaxHours OR filter_number_format($_POST['Day1']) < -$MaxHours) {
		$InputError = 1;
		prnMsg(_('The hours entered for day 1 look to be too high - this is probably an error'),'error');
	}
	if (filter_number_format($_POST['Day2']) > $MaxHours OR filter_number_format($_POST['Day2']) < -$MaxHours) {
		$InputError = 1;
		prnMsg(_('The hours entered for day 2 look to be too high - this is probably an error'),'error');
	}
	if (filter_number_format($_POST['Day3']) > $MaxHours OR filter_number_format($_POST['Day3']) < -$MaxHours) {
		$InputError = 1;
		prnMsg(_('The hours entered for day 3 look to be too high - this is probably an error'),'error');
	}
	if (filter_number_format($_POST['Day4']) > $MaxHours OR filter_number_format($_POST['Day4']) < -$MaxHours) {
		$InputError = 1;
		prnMsg(_('The hours entered for day 4 look to be too high - this is probably an error'),'error');
	}
	if (filter_number_format($_POST['Day5']) > $MaxHours OR filter_number_format($_POST['Day5']) < -$MaxHours) {
		$InputError = 1;
		prnMsg(_('The hours entered for day 5 look to be too high - this is probably an error'),'error');
	}
	if (filter_number_format($_POST['Day6']) > $MaxHours OR filter_number_format($_POST['Day6']) < -$MaxHours) {
		$InputError = 1;
		prnMsg(_('The hours entered for day 6 look to be too high - this is probably an error'),'error');
	}
	if (filter_number_format($_POST['Day7']) > $MaxHours OR filter_number_format($_POST['Day7']) < -$MaxHours) {
		$InputError = 1;
		prnMsg(_('The hours entered for day 7 look to be too high - this is probably an error'),'error');
	}

	if ($InputError==0) { //no errors were reported :-)
	/*Now check to see if there is already a line for the same weekending/work order/work centre combo and update the existing line rather than inserting a new record */
		$CheckTimesheetResult = DB_query("SELECT id FROM timesheets
											WHERE employeeid='" . $SelectedEmployee . "'
											AND wo='" . $_POST['WO'] . "'
											AND weekending='" . FormatDateForSQL($_POST['WeekEnding']) . "'
											AND workcentre='" . $_POST['WorkCentre'] . "'");
		if (DB_num_rows($CheckTimesheetResult)==1) {
			$ExistingTimesheetRow = DB_fetch_array($CheckTimesheetResult);
			$UpdateExistingResult = DB_query("UPDATE timesheets SET day1=day1+" . filter_number_format($_POST['Day1']) .",
																	day2=day2+" . filter_number_format($_POST['Day2']) .",
																	day3=day3+" . filter_number_format($_POST['Day3']) .",
																	day4=day4+" . filter_number_format($_POST['Day4']) .",
																	day5=day5+" . filter_number_format($_POST['Day5']) .",
																	day6=day6+" . filter_number_format($_POST['Day6']) .",
																	day7=day7+" . filter_number_format($_POST['Day7']) ."
												WHERE id ='" . $ExistingTimesheetRow['id'] . "'");
			prnMsg(_('An existing timesheet record for the same work order, week ending and work centre was updated with these hours'),'info');
			
		} else {
		
			$InsertTimsheetResult = DB_query("INSERT INTO timesheets (wo,
																	employeeid,
																	workcentre,
																	weekending,
																	day1,
																	day2,
																	day3,
																	day4,
																	day5,
																	day6,
																	day7)
													VALUES ('" . $_POST['WO'] . "',
															'" . $SelectedEmployee . "',
															'" . $_POST['WorkCentre'] . "',
															'" . FormatDateForSQL($_POST['WeekEnding']) . "',
															'" . filter_number_format($_POST['Day1']) . "',
															'" . filter_number_format($_POST['Day2']) . "',
															'" . filter_number_format($_POST['Day3']) . "',
															'" . filter_number_format($_POST['Day4']) . "',
															'" . filter_number_format($_POST['Day5']) . "',
															'" . filter_number_format($_POST['Day6']) . "',
															'" . filter_number_format($_POST['Day7']) . "')",
												_('Could not add this timesheet record'));
			
			
			prnMsg(_('Timesheet record added'),'info');
		}

		unset($_POST['WO']);
		unset($_POST['WorkCentre']);
		unset($_POST['Day1']);
		unset($_POST['Day2']);
		unset($_POST['Day3']);
		unset($_POST['Day4']);
		unset($_POST['Day5']);
		unset($_POST['Day6']);
		unset($_POST['Day7']);
	}//end of inserts and reset of data
} //end of the update/inserts

if(isset($_POST['SubmitForApproval'])) {

	$WeekTimeTotalResult = DB_query("SELECT employeeid,
									SUM(day1+day2+day3+day4+day5+day6+day7) as totalweekhours
							FROM timesheets 
							WHERE employeeid ='" . $SelectedEmployee . "'
							AND weekending ='" . FormatDateForSQL($_POST['WeekEnding']) ."'
							GROUP BY employeeid");

	$WeekTimeTotalRow = DB_fetch_array($WeekTimeTotalResult);
	
	//Check that there is a full weeks worth of time before allowing the timesheet to be submitted
	if ($WeekTimeTotalRow['totalweekhours'] < $EmployeeRow['normalhours']) {
		prnMsg(_('This timesheet cannot be submitted until your full working week hours are accounted for'),'error', $EmployeeRow['normalhours'] . ' ' . _('hours or more must be entered'));
	} else {
		//change the status of the timesheet to submitted and email the manager
		$SubmittedTimesheetResult = DB_query("UPDATE timesheets
												SET status=1
											WHERE employeeid='" . $SelectedEmployee . "'
											AND status=0
											AND weekending='" . FormatDateForSQL($_POST['WeekEnding']) . "'");
											
		$ManagerResult = DB_query("SELECT email
									FROM employees
									WHERE employees.id='" . $EmployeeRow['manager'] . "'");
		$ManagerRow = DB_fetch_array($ManagerResult);
		$Recipients = array($ManagerRow['email']);
		include('includes/htmlMimeMail.php');
		$mail = new htmlMimeMail();
		$mail->setText($EmployeeRow['firstname'] . ' ' . $EmployeeRow['firstname'] . ' ' . _('timesheet submitted for the week ending') . ' ' . $_POST['WeekEnding']);
		$mail->SetSubject('<a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?SelectedEmployee=' . $SelectedEmployee  . '&WeekEnding=' . $_POST['WeekEnding'] . '" ' . _('Review and approve this timesheet') . '</a>');
	
		if($_SESSION['SmtpSetting']==0){
			$mail->setFrom($_SESSION['CompanyRecord']['coyname'] . '<' . $_SESSION['CompanyRecord']['email'] . '>');
			$result = $mail->send($Recipients);
		}else{
			$result = SendmailBySmtp($mail,$Recipients);
		}
		prnMsg(_('This timesheet has been submitted to your manager for approval'),'success', _('Timesheet submitted'));
	}
} // end submit for approval

if(isset($_POST['ApproveTimesheet'])) {
	//need to check again we have the full week!
	$WeekTimeTotalResult = DB_query("SELECT employeeid,
											employees.stockid,
											materialcost+labourcost+overheadcost AS labourcost,
											SUM(day1+day2+day3+day4+day5+day6+day7) as totalweekhours
									FROM timesheets INNER JOIN employees
									ON timesheets.employeeid=employees.id
									INNER JOIN stockmaster ON
									employees.stockid=stockmaster.stockid
									WHERE employeeid ='" . $SelectedEmployee . "'
									AND weekending ='" . FormatDateForSQL($_POST['WeekEnding']) ."'
									GROUP BY employeeid,
											employees.stockid,
											 labourcost");

	$WeekTimeTotalRow = DB_fetch_array($WeekTimeTotalResult);
	
	//Check that there is a full weeks worth of time before allowing the timesheet to be submitted
	if ($WeekTimeTotalRow['totalweekhours'] < $EmployeeRow['normalhours']) {
		prnMsg(_('This timesheet cannot be submitted until your full working week hours are accounted for'),'error', $EmployeeRow['normalhours'] . ' ' . _('hours or more must be entered'));
	} elseif ($WeekTimeTotalRow['labourcost']==0) {
		prnMsg(_('This timesheet cannot be submitted until a cost is set up for the item defined for this employee'),'error', _('This employees labour item has no cost entered'));
	} else {
		/* Now we are into posting the time to the work orders NB: only open work orders!! and only time that has not already been posted */
		$WeekTimeResult = DB_query("SELECT timesheets.wo,
											timesheets.workcentre,
											employees.stockid as issueitem,
											employees.surname,
											employees.firstname,
											materialcost+labourcost+overheadcost AS labourcost,
											workorders.loccode,
											SUM(day1+day2+day3+day4+day5+day6+day7) as totalweekhours
									FROM timesheets INNER JOIN employees
									ON timesheets.employeeid=employees.id
									INNER JOIN stockmaster
									ON employees.stockid=stockmaster.stockid
									INNER JOIN workorders
									ON timesheets.wo=workorders.wo
									WHERE employeeid ='" . $SelectedEmployee . "'
									AND weekending ='" . FormatDateForSQL($_POST['WeekEnding']) ."'
									AND workorders.closed = '0'
									AND timesheets.status <> '2'
									GROUP BY wo,
										workcentre,
										issueitem,
										surname,
										firstname,
										labourcost,
										loccode");

		if (DB_num_rows($WeekTimeResult)==0) {
			prnMsg(_('No more time to post for this timesheet'),'error');
		} else {
			/*Now Get the next WO Issue transaction type 28 - function in SQL_CommonFunctions*/
			$WOIssueNo = GetNextTransNo(28);
			$PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']));
	
			$Result = DB_Txn_Begin();
			
			while ($WeekTimeRow = DB_fetch_array($WeekTimeResult)) {
	
				/*Insert 'stock' movements - with unit cost */
				
				$SQL = "INSERT INTO stockmoves (stockid,
												type,
												transno,
												loccode,
												trandate,
												userid,
												price,
												prd,
												reference,
												qty,
												standardcost,
												newqoh,
												narrative)
							VALUES ('" . $WeekTimeRow['issueitem'] . "',
									28,
									'" . $WOIssueNo . "',
									'" . $WeekTimeRow['loccode'] . "',
									'" . FormatDateForSQL($_POST['WeekEnding']) . "',
									'" . $_SESSION['UserID'] . "',
									'" . $WeekTimeRow['labourcost'] . "',
									'" . $PeriodNo . "',
									'" . $WeekTimeRow['wo'] . "',
									'" . -$WeekTimeRow['totalweekhours'] . "',
									'" . $WeekTimeRow['labourcost'] . "',
									'0',
									'" .  _('Timesheet for the week ending') . ' ' . $_POST['WeekEnding'] . ': ' . $WeekTimeRow['firstname'] . ' ' . $WeekTimeRow['surname'] . "')";
			
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('labour stock movement records could not be inserted when processing the timesheet because');
				$DbgMsg =  _('The following SQL to insert the labour stock movement records was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			
				
				if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND ($WeekTimeRow['labourcost'] * $WeekTimeRow['totalweekhours']) != 0) {
	
				/*GL integration with stock is activated so need the GL journals to make it so */
	
				/*first the debit the WIP of the item being manufactured from the WO - there could be several items being made on the WO but the WIP account of only the first item on the WO is used*/
					$GetWIPGLAccountResult = DB_query("SELECT wipact
														FROM stockcategory INNER JOIN stockmaster
														ON stockcategory.categoryid=stockmaster.categoryid
														INNER JOIN woitems
														ON stockmaster.stockid=woitems.stockid
														WHERE woitems.wo='" . $WeekTimeRow['wo'] . "'");
					$GetWIPAccountRow = DB_fetch_array($GetWIPGLAccountResult);
					
					$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount)
							VALUES (28,
								'" . $WOIssueNo . "',
								'" . FormatDateForSQL($_POST['WeekEnding']) . "',
								'" . $PeriodNo . "',
								'" . $GetWIPAccountRow['wipact'] . "',
								'" . _('WO') . ':' . $WeekTimeRow['wo'] . ' ' . _('Work Centre') . ': ' . $WeekTimeRow['workcentre'] . ' ' . $WeekTimeRow['firstname'] . ' ' . $WeekTimeRow['surname'] . ' ' . _('as') . ' ' . $WeekTimeRow['issueitem'] . ' x ' . $WeekTimeRow['totalweekhours'] . ' ' . _('hours') . ' @ ' . locale_number_format($WeekTimeRow['labourcost'], $_SESSION['CompanyRecord']['decimalplaces']) . "',
								'" . ($WeekTimeRow['labourcost'] * $WeekTimeRow['totalweekhours']) . "')";
			
					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The labour cost posting to a work order GL posting could not be inserted because');
					$DbgMsg = _('The following SQL to insert the work order issue GLTrans record was used');
					$Result = DB_query($SQL,$ErrMsg, $DbgMsg, true);
			
				/*now the credit labour recovery entry - the GetSockGLCode actually returns the labour recovery GL account for labour type stock categories */
				$ItemGLAccounts = GetStockGLCode($WeekTimeRow['issueitem']);
				
					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
							VALUES (28,
								'" . $WOIssueNo . "',
								'" . FormatDateForSQL($_POST['WeekEnding']) . "',
								'" . $PeriodNo . "',
								'" . $ItemGLAccounts['stockact'] . "',
								'" . _('WO') . ':' . $WeekTimeRow['wo'] . ' ' . _('Work Centre') . ': ' . $WeekTimeRow['workcentre'] . ' ' . $WeekTimeRow['firstname'] . ' ' . $WeekTimeRow['surname'] . ' ' . _('as') . ' ' . $WeekTimeRow['issueitem'] . ' x ' . $WeekTimeRow['totalweekhours'] . ' ' . _('hours') . ' @ ' . locale_number_format($WeekTimeRow['labourcost'], $_SESSION['CompanyRecord']['decimalplaces']) . "',
								'" . -($WeekTimeRow['labourcost'] * $WeekTimeRow['totalweekhours']) . "')";
			
					$ErrMsg =   _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The labour recovery account credit on the approval of a timesheet GL posting could not be inserted because');
					$DbgMsg =  _('The following SQL to insert the labour gltrans record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg,true);
			
				} /* end of if GL and stock integrated and standard cost !=0 */
			
			
				//update the wo with new cost issued
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('Could not update the work order labour cost because');
				$DbgMsg = _('The following SQL was used to update the work order');
				$UpdateWOResult =DB_query("UPDATE workorders
											SET costissued=costissued+" . ($WeekTimeRow['labourcost'] * $WeekTimeRow['totalweekhours']) . "
											WHERE wo='" . $WeekTimeRow['wo'] . "'",
											$ErrMsg,
											$DbgMsg,
											true);
			} //end loop through the WOs entered on this timesheet
	
		//change the status of the timesheet to approved
			$SubmittedTimesheetResult = DB_query("UPDATE timesheets
													SET status=2
												WHERE employeeid='" . $SelectedEmployee . "'
												AND weekending='" . FormatDateForSQL($_POST['WeekEnding']) . "'",
												_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' ._('Could not change the timesheet status to approved because'),
												_('The following SQL was used to update the work order'),
												true);
			$Result = DB_Txn_Commit();
			prnMsg(_('Timesheet posted'),'success');
		} //end of if there is unposted in this week to post
	} //end of if the timesheet has a full working week
} // end approval



if(!isset($SelectedEmployee) AND in_array(20, $_SESSION['AllowedPageSecurityTokens'])) {

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedEmployee will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then none of the above are true and the list of employees will be displayed with links to select one. These will call the same page again and allow input of the timesheet or deletion of the records*/

	
	$sql = "SELECT employees.id,
					employees.surname,
					employees.firstname,
					employees.stockid,
					employees.manager,
					employees2.firstname as managerfirstname,
					employees2.surname as managersurname,
					employees.normalhours,
					employees.email,
					employees.userid
			FROM employees LEFT JOIN employees AS employees2
			ON employees.manager=employees2.id";

	$result = DB_query($sql);
	if (DB_num_rows($result) > 0) {
		echo '<table class="selection">
			<thead>
			<tr class="striped_row">
				<th class="ascending">', _('ID'), '</th>
				<th class="ascending">', _('First name'), '</th>
				<th class="ascending">', _('Surname'), '</th>
				<th class="ascending">', _('Type'), '</th>
				<th class="ascending">', _('Manager'), '</th>
				<th class="ascending">', _('Email'), '</th>
				<th class="noprint" colspan="2">&nbsp;</th>
				</tr>
			</thead>
			<tbody>';
	
	while ($myrow = DB_fetch_array($result)) {
	
		printf('<tr class="striped_row">
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href="mailto:%s">%s</a></td>
					<td class="noprint"><a href="%sSelectedEmployee=%s">' . _('Select') . '</a></td>
					<td class="noprint"><a href="Employees.php?SelectedEmployee=%s">' . _('Edit') . '</a></td>
				</tr>',
				$myrow['id'],
				$myrow['firstname'],
				$myrow['surname'],
				$myrow['stockid'],
				$myrow['managerfirstname'] . ' ' . $myrow['managersurname'],
				$myrow['email'],
				$myrow['email'],
				htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?',
				$myrow['id'],
				$myrow['id']);
		}
		//END WHILE LIST LOOP
		echo '</tbody></table>';
	} else {
		prnMsg(_('No employees have been set up yet'),'info');
	}
	echo '<br />';
} elseif (in_array(20, $_SESSION['AllowedPageSecurityTokens']) AND isset($SelectedEmployee)) {
	echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?SelectedEmployee=NewSelection">' . _('Select a different employee') . '</a>';
} elseif(!isset($SelectedEmployee)) {
	prnMsg(_('Only employees set up to enter timesheets can use this script - please see the timesheet administrator'),'info');
}

if(isset($_GET['Delete'])) {
	$DeleteTimesheetRow = DB_query("DELETE FROM timesheets WHERE id='" . $_GET['Delete'] . "'");
	prnMsg(_('Timesheet row deleted'),'success');
}

if (isset($SelectedEmployee)){
	echo '<form id="TimesheetForm" method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
		<div>
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<input type="hidden" name="SelectedEmployee" value="' . $SelectedEmployee . '" />';
	
	//Entry of Timesheets - populate the employee's details
	
	
	
	echo '<h2>' . _('For') . ' ' . $EmployeeRow['firstname'] . ' ' . $EmployeeRow['surname'] . ' ' . _('For the week ending') . ': <select name="WeekEnding" onChange="ReloadForm(TimesheetForm.RefreshWeek)" >';
	
	
	
	if (!isset($_POST['WeekEnding'])) {
		echo '<option selected="selected" value="' . $LatestWeekEndingDate . '">' . $LatestWeekEndingDate . '</option>';
	} else {
		echo '<option value="' . $LatestWeekEndingDate . '">' . $LatestWeekEndingDate . '</option>';
	}
	
	
	for ($i=-1;$i>-26;$i--) {
		$ProposedWeekEndingDate = DateAdd($LatestWeekEndingDate,'w',$i);
		if ($ProposedWeekEndingDate == $_POST['WeekEnding']) {
			echo '<option selected="selected" value="' . $ProposedWeekEndingDate . '">' . $ProposedWeekEndingDate . '</option>';
		} else {
			echo '<option value="' . $ProposedWeekEndingDate . '">' . $ProposedWeekEndingDate . '</option>';
		}
	} //end for loop
	
	echo '</select><input type="submit" name="RefreshWeek" value="Refresh" /></h2>
			<hr />';
	
	if ($_SESSION['LastDayOfWeek']==6) {
		$FirstDayNumber = 0;
	} else {
		$FirstDayNumber = $_SESSION['LastDayOfWeek']+1;
	}
	
	echo '<table>
		<tr>
			<th>' . _('Work Order') . '#</th>
			<th>' . _('Work Centre') . '</th>';
			
	for ($i=0;$i<7;$i++) {
		if ($FirstDayNumber +$i >6){
			$DayNumber = $FirstDayNumber + $i - 7;
		} else {
			$DayNumber = $FirstDayNumber + $i;
		}
		echo '<th>' . GetWeekDayText($DayNumber) . '</th>';
	}
	echo '<th>' . _('Total') . '</th>
		</tr>';
		
	$Day1 = 0;
	$Day2 = 0;
	$Day3 = 0;
	$Day4 = 0;
	$Day5 = 0;
	$Day6 = 0;
	$Day7 = 0;
	
	$EditableRowNo = 0;
	$PostedRowNo = 0;
	
	if (isset($_POST['WeekEnding'])){
		/* Populate with any pre-existing entries */
		$TimesheetResult = DB_query("SELECT id,
											wo,
											workcentre,
											workcentres.description as workcentrename,
											day1,
											day2,
											day3,
											day4,
											day5,
											day6,
											day7,
											status
									FROM timesheets LEFT JOIN workcentres
									ON timesheets.workcentre=workcentres.code
									WHERE employeeid ='" . $SelectedEmployee . "'
									AND weekending ='" . FormatDateForSQL($_POST['WeekEnding']) ."'");
		if (DB_num_rows($TimesheetResult) > 0) {
			while ($TimesheetRow = DB_fetch_array($TimesheetResult)) {
				if ($TimesheetRow['status'] == 2) { //the timesheet is already posted - no changes are now possible
					$PostedRowNo++;
					echo '<tr class="striped_row">
							<td>' . (($TimesheetRow['wo']=='0') ? _('Non-chargable') : $TimesheetRow['wo']) . '</td>
							<td>' . $TimesheetRow['workcentrename'] . '</td>
							<td class="number">' . locale_number_format($TimesheetRow['day1'],$EmployeeRow['decimalplaces']) . '</td>
							<td class="number">' . locale_number_format($TimesheetRow['day2'],$EmployeeRow['decimalplaces']) . '</td>
							<td class="number">' . locale_number_format($TimesheetRow['day3'],$EmployeeRow['decimalplaces']) . '</td>
							<td class="number">' . locale_number_format($TimesheetRow['day4'],$EmployeeRow['decimalplaces']) . '</td>
							<td class="number">' . locale_number_format($TimesheetRow['day5'],$EmployeeRow['decimalplaces']) . '</td>
							<td class="number">' . locale_number_format($TimesheetRow['day6'],$EmployeeRow['decimalplaces']) . '</td>
							<td class="number">' . locale_number_format($TimesheetRow['day7'],$EmployeeRow['decimalplaces']) . '</td>
							<td class="number">' . locale_number_format(($TimesheetRow['day1']+$TimesheetRow['day2']+$TimesheetRow['day3']+$TimesheetRow['day4']+$TimesheetRow['day5']+$TimesheetRow['day6']+$TimesheetRow['day7']),$EmployeeRow['decimalplaces']) . '</td>
							<td>' . _('Posted') . '</td>
						</tr>';
				} else { //yet to be posted so allow edits
					echo '<tr class="striped_row">
							<td><input type="hidden" name="id_' . $EditableRowNo . '" value="' . $TimesheetRow['id'] . '" />' .  (($TimesheetRow['wo']=='0') ? _('Non-chargable') : $TimesheetRow['wo']) . '</td>
							<td>' .  $TimesheetRow['workcentrename'] . '</td>
							<td><input type="text" required="required" class="number" name="Day1_' . $EditableRowNo . '" value="' . locale_number_format($TimesheetRow['day1'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
							<td><input type="text" required="required" class="number" name="Day2_' . $EditableRowNo . '" value="' . locale_number_format($TimesheetRow['day2'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
							<td><input type="text" required="required" class="number" name="Day3_' . $EditableRowNo . '" value="' . locale_number_format($TimesheetRow['day3'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
							<td><input type="text" required="required" class="number" name="Day4_' . $EditableRowNo . '" value="' . locale_number_format($TimesheetRow['day4'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
							<td><input type="text" required="required" class="number" name="Day5_' . $EditableRowNo . '" value="' . locale_number_format($TimesheetRow['day5'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
							<td><input type="text" required="required" class="number" name="Day6_' . $EditableRowNo . '" value="' . locale_number_format($TimesheetRow['day6'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
							<td><input type="text" required="required" class="number" name="Day7_' . $EditableRowNo . '" value="' . locale_number_format($TimesheetRow['day7'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
							<td class="number">' . locale_number_format($TimesheetRow['day1']+$TimesheetRow['day2']+$TimesheetRow['day3']+$TimesheetRow['day4']+$TimesheetRow['day5']+$TimesheetRow['day6']+$TimesheetRow['day7'],$EmployeeRow['decimalplaces']) . '</td>
							<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'] . '?Delete=' . $TimesheetRow['id'] . '&SelectedEmployee=' . $SelectedEmployee . '&WeekEnding=' . $_POST['WeekEnding'], ENT_QUOTES,'UTF-8') . '" onclick="return confirm(\'' . _('Are you sure you wish to delete this timesheet entry') . '\');">' . _('Delete')  . '</a></td>'
							 . (($TimesheetRow['status']=='1') ? '<td>' . _('submitted') . '</td>' : '') . 
						'</tr>';
						$EditableRowNo++; //increment the row number
				}
				 

				
				$Day1 += $TimesheetRow['day1'];
				$Day2 += $TimesheetRow['day2'];
				$Day3 += $TimesheetRow['day3'];
				$Day4 += $TimesheetRow['day4'];
				$Day5 += $TimesheetRow['day5'];
				$Day6 += $TimesheetRow['day6'];
				$Day7 += $TimesheetRow['day7'];
			} //end of the loop through the previous entries
		} //end if there are previous entries
	
		//Set up a form variable to tell us how many existing rows without going back to the DB
		echo '<input type="hidden" name="Rows" value="' . $EditableRowNo . '" />';
		if ($EditableRowNo+$PostedRowNo > 1) { // it is worth displaying the totals - only if there are several lines
			echo '<tr>
					<td colspan="10"><hr /></td>
				</tr>
				<tr>
					<td colspan="2">' . _('TOTALS') . '</td>
					<td class="number">' . locale_number_format($Day1,$EmployeeRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($Day2,$EmployeeRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($Day3,$EmployeeRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($Day4,$EmployeeRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($Day5,$EmployeeRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($Day6,$EmployeeRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($Day7,$EmployeeRow['decimalplaces']) . '</td>
					<td class="number">' . locale_number_format($Day1+$Day2+$Day3+$Day4+$Day5+$Day6+$Day7,$EmployeeRow['decimalplaces']) . '</td>
				</tr>';
		} // end of totals - only if multiple lines
		echo '<tr>
				<td><select name="WO">';
					
		if (!isset($_POST['WO']) OR $_POST['WO']=='0'){
			echo '<option selected="selected" value="0">' . _('Non-chargable') . '</option>';
		} else {
			echo '<option  value="0">' . _('Non-chargable') . '</option>';
		}		
		$OpenWOResult = DB_query("SELECT woitems.wo,
										stockmaster.description
								FROM workorders INNER JOIN woitems
									ON workorders.wo=woitems.wo
									INNER JOIN stockmaster
									ON stockmaster.stockid=woitems.stockid
								WHERE workorders.closed=0");
		while ($OpenWORow = DB_fetch_array($OpenWOResult)) {
			if ($OpenWORow['wo']==$_POST['WO']) {
				echo '<option selected="selected" value="' . $OpenWORow['wo'] . '">' . $OpenWORow['wo'] . ' - ' . $OpenWORow['description'] . '</option>';
			} else {
				echo '<option value="' . $OpenWORow['wo'] . '">' . $OpenWORow['wo'] . ' - ' . $OpenWORow['description'] . '</option>';
			}
		}
		echo '</select></td>
				<td><select name="WorkCentre">';
		if (!isset($_POST['WorkCentre']) OR $_POST['WorkCentre']=='0'){
			echo '<option selected="selected" value="0">' . _('N/A') . '</option>';
		} else {
			echo '<option value="0">' . _('N/A') . '</option>';
		}
		$WorkCentreSQL = "SELECT code,
								description
						FROM workcentres";
		if ($EmployeeLocation!='') {
			$WorkCentreSQL .= " WHERE location='" . $EmployeeLocation . "'";
		}
		$WorkCentresResult = DB_query($WorkCentreSQL);
		while ($WorkCentreRow = DB_fetch_array($WorkCentresResult)) {
			if ($_POST['WorkCentre']==$WorkCentreRow['code']){
				echo '<option selected="selected" value="' . $WorkCentreRow['code'] . '">' . $WorkCentreRow['description'] . '</option>';
			} else {
				echo '<option value="' . $WorkCentreRow['code'] . '">' . $WorkCentreRow['description'] . '</option>';
			}
		} //end loop through valid work centres
	
		if (!isset($_POST['Day1'])) { //then none of the days' hours will have been set
			$_POST['Day1'] = 0;
			$_POST['Day2'] = 0;
			$_POST['Day3'] = 0;
			$_POST['Day4'] = 0;
			$_POST['Day5'] = 0;
			$_POST['Day6'] = 0;
			$_POST['Day7'] = 0;
		}
	
	
		
		echo '</select></td>
			<td><input type="text" required="required" class="number" name="Day1" value="' . locale_number_format($_POST['Day1'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
			<td><input type="text" required="required" class="number" name="Day2" value="' . locale_number_format($_POST['Day2'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
			<td><input type="text" required="required" class="number" name="Day3" value="' . locale_number_format($_POST['Day3'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
			<td><input type="text" required="required" class="number" name="Day4" value="' . locale_number_format($_POST['Day4'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
			<td><input type="text" required="required" class="number" name="Day5" value="' . locale_number_format($_POST['Day5'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
			<td><input type="text" required="required" class="number" name="Day6" value="' . locale_number_format($_POST['Day6'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
			<td><input type="text" required="required" class="number" name="Day7" value="' . locale_number_format($_POST['Day7'],$EmployeeRow['decimalplaces']) . '" minlength="1" maxlength="4" size="4" /></td>
			</tr>';
	} //end of if isset($_POST['WeekEnding'])
	
	echo '</table>
		<br />
		<div class="centre">
			<input type="submit" name="Enter" value="' , _('Enter') , '" />';
	
	if (isset($SelectedEmployee) AND $EditableRowNo>0){
		echo '<input type="submit" name="SubmitForApproval" value="' , _('Submit for Approval') , '" />';
	}
	
	if (in_array(20, $_SESSION['AllowedPageSecurityTokens']) AND isset($SelectedEmployee) AND $EditableRowNo>0) { //a timesheet administrator
		echo '<br />
			<hr />
			<input type="submit" name="ApproveTimesheet" value="' , _('Approve') , '" />';
	}
} //end if there is an employee selected - entering a timesheet

echo '</div>
	</div>
	</form>';

include('includes/footer.php');
?>
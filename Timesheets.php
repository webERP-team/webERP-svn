<?php

/* Timesheet Entry */

include('includes/session.php');
$Title = _('Timesheet Entry');// Screen identification.
$ViewTopic = 'Labour';// Filename's id in ManualContents.php's TOC.
$BookMark = 'Timesheets';// Anchor's id in the manual's html document.

include('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/clock.png" title="',// Icon image.
	_('Timesheets'), '" /> ',// Icon title.
	_('Timesheet Entry'), '</p>';// Page title.


if(isset($_GET['SelectedEmployee']) AND in_array(20, $_SESSION['AllowedPageSecurityTokens'])) { //only timesheet administrators can see timesheets with a $_GET from this script
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

if(isset($_POST['submit'])) {
	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	
	//end of checking the input
}


if(!isset($SelectedEmployee) AND in_array(20, $_SESSION['AllowedPageSecurityTokens'])) {

/* It could still be the second time the page has been run and a record has been selected for modification - SelectedEmployee will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters then none of the above are true and the list of employees will be displayed with links to select one. These will call the same page again and allow input of the timesheet or deletion of the records*/

/*Only allow access to modify prices if securiy token 20 - Timesheet administrator is allowed */
	
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
			<tr>
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
} elseif(isset($_GET['delete'])) {

	/* DO the delete of a timesheet */

}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
	<div>
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<input type="hidden" name="SelectedEmployee" value="' . $SelectedEmployee . '" />';

//Entry of Timesheets - populate the employee's details

$sql = "SELECT id,
				surname,
				firstname,
				stockid,
				manager,
				normalhours,
				userid,
				email
		FROM employees
		WHERE employees.id='" . $SelectedEmployee . "'";

$EmployeeResult = DB_query($sql);
$EmployeeRow = DB_fetch_array($EmployeeResult);


echo '<h2>' . _('For') . ' ' . $EmployeeRow['firstname'] . ' ' . $EmployeeRow['surname'] . ' ' . _('For the week ending') . ': <select name="WeekEnding">';

$LatestWeekEndingDate = Date($_SESSION['DefaultDateFormat'],mktime(0,0,0,date('n'),date('j')-(date('w')+$_SESSION['LastDayOfWeek'])+7,date('Y')));

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

echo '</select></h2>
<br /><hr />';

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
	</tr>
	<tr>';

$Day1 =0;
$Day2 =0;
$Day3 =0;
$Day4 =0;
$Day5 =0;
$Day6 =0;
$Day7 =0;

$RowNo = 0;

if (isset($_POST['WeekEnding'])){
	/* Populate with any pre-existing entries */
	$TimesheetResult = DB_query("SELECT wo,
										workcentre,
										workcentres.description as workcentrename,
										day1,
										day2,
										day3,
										day4,
										day5,
										day6,
										day7
								FROM timesheets INNER JOIN workcentres
								ON timesheets.workcentre=workcentres.code
								WHERE employeeid ='" . $SelectedEmployee . "'
								AND weekending ='" . FormatDateForSQL($_POST['WeekEnding']) ."'");
	if (DB_num_rows($TimesheetResult) > 0) {
		while ($TimesheetRow = DB_fetch_array($TimesheetResult)) {
			echo '<tr>
					<td><input type="hidden" name="WO_' . $RowNo . '" value="' . $TimesheetRow['wo'] . '" />' .  $TimesheetRow['wo'] . '</td>
					<td><input type="hidden" name="WorkCentre_' . $RowNo . '" value="' . $TimesheetRow['workcentre'] . '" />' .  $TimesheetRow['workcentrename'] . '</td>
					<td><input type="text" class="number" name="Day1_' . $RowNo . '" value="' . $TimesheetRow['day1'] . '" /></td>
					<td><input type="text" class="number" name="Day2_' . $RowNo . '" value="' . $TimesheetRow['day2'] . '" /></td>
					<td><input type="text" class="number" name="Day3_' . $RowNo . '" value="' . $TimesheetRow['day3'] . '" /></td>
					<td><input type="text" class="number" name="Day4_' . $RowNo . '" value="' . $TimesheetRow['day4'] . '" /></td>
					<td><input type="text" class="number" name="Day5_' . $RowNo . '" value="' . $TimesheetRow['day5'] . '" /></td>
					<td><input type="text" class="number" name="Day6_' . $RowNo . '" value="' . $TimesheetRow['day6'] . '" /></td>
					<td><input type="text" class="number" name="Day7_' . $RowNo . '" value="' . $TimesheetRow['day7'] . '" /></td>
					<td class="number">' . ($TimesheetRow['day1']+$TimesheetRow['day2']+$TimesheetRow['day3']+$TimesheetRow['day4']+$TimesheetRow['day5']+$TimesheetRow['day6']+$TimesheetRow['day7']) . '" /></td>
				</tr>';
			
			$Day1 += $TimesheetRow['day1'];
			$Day2 += $TimesheetRow['day2'];
			$Day3 += $TimesheetRow['day3'];
			$Day4 += $TimesheetRow['day4'];
			$Day5 += $TimesheetRow['day5'];
			$Day6 += $TimesheetRow['day6'];
			$Day7 += $TimesheetRow['day7'];
		} //end of the loop through the previous entries
	} //end if there are previous entries
	echo '<tr>
			<td colspan="10"><hr /></td>
		</tr>
		<tr>
			<td colspan="2">' . _('TOTALS') . '</td>
			<td class="number">' . $Day1 . '</td>
			<td class="number">' . $Day2 . '</td>
			<td class="number">' . $Day3 . '</td>
			<td class="number">' . $Day4 . '</td>
			<td class="number">' . $Day5 . '</td>
			<td class="number">' . $Day6 . '</td>
			<td class="number">' . $Day7 . '</td>
			<td class="number">' . $Day1+$Day2+$Day3+$Day4+$Day5+$Day6+$Day7 . '</td>
		</tr>
		<tr>
			<td><select name="WO">
				<option selected="selected" value="0">' . _('None chargable time') . '</option>';
			
	$OpenWOResult = DB_query("SELECT woitems.wo,
									stockmaster.description
							FROM workorders INNER JOIN woitems
								ON workorders.wo=woitems.wo
								INNER JOIN stockmaster
								ON stockmaster.stockid=woitems.stockid
							WHERE workorders.closed=0");
	while ($OpenWORow = DB_fetch_array($OpenWOResult)) {
		echo '<option value="' . $OpenWORow['wo'] . '">' . $OpenWORow['wo'] . ' - ' . $OpenWORow['description'] . '</option>';
	}
	
	
	echo '</select></td>
		</tr>';
} //end of if isset($_POST['WeekEnding'])
















echo '</table>
	<br />
	<div class="centre">
		<input type="submit" name="submit" value="' , _('Enter Information') , '" />
	</div>
	</div>
	</form>';



include('includes/footer.php');
?>
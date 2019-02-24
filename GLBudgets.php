<?php

include('includes/session.php');
include('includes/SQL_CommonFunctions.inc');

$Title = _('Create GL Budgets');

$ViewTopic = 'GeneralLedger';
$BookMark = 'GLBudgets';

include('includes/header.php');

if (isset($_POST['SelectedAccount'])) {
	$SelectedAccount = $_POST['SelectedAccount'];
} elseif (isset($_GET['SelectedAccount'])) {
	$SelectedAccount = $_GET['SelectedAccount'];
}

if (isset($_POST['Previous'])) {
	$SelectedAccount = $_POST['PrevAccount'];
} elseif (isset($_POST['Next'])) {
	$SelectedAccount = $_POST['NextAccount'];
}

if (isset($_POST['update'])) {
	prnMsg(_('Budget updated successfully'), 'success');
}

//If an account has not been selected then select one here.
echo '<p class="page_title_text">
		<img src="'.$RootPath, '/css/', $Theme, '/images/maintenance.png" title="' . _('Search') . '" alt="" />' . ' ' . $Title.'
	</p>';
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post" id="selectaccount">
	<div>
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<br />
		<table>
		<tr>
			<td>' .   _('Select GL Account').  ':</td>
			<td><select name="SelectedAccount" required="required" onchange="ReloadForm(selectaccount.Select)">';

$SQL = "SELECT accountcode,
				accountname
			FROM chartmaster
			ORDER BY accountcode";

$result=DB_query($SQL);
if (DB_num_rows($result)==0) {
	echo '</select></td>
		</tr>';
	prnMsg(_('No General ledger accounts have been set up yet') . ' - ' . _('budgets cannot be allocated until the GL accounts are set up'),'warn');
} else {
	while ($myrow=DB_fetch_array($result)) {
		$Account = $myrow['accountcode'] . ' - ' . htmlspecialchars($myrow['accountname'],ENT_QUOTES,'UTF-8',false);
		if (isset($SelectedAccount) AND isset($LastCode) AND $SelectedAccount==$myrow['accountcode']) {
			echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $Account . '</option>';
			$PrevCode=$LastCode;
		} else {
			echo '<option value="' . $myrow['accountcode'] . '">' . $Account . '</option>';
			if (isset($SelectedAccount) AND isset($LastCode) AND $SelectedAccount == $LastCode) {
				$NextCode=$myrow['accountcode'];
			}
		}
		$LastCode=$myrow['accountcode'];
	}
	echo '</select></td>
		</tr>';
}

if (!isset($PrevCode)) {$PrevCode='';}
if (!isset($NextCode)) {$NextCode='';}

echo '</table>';
echo '<input type="hidden" name="PrevAccount" value="'.$PrevCode.'" />';
echo '<input type="hidden" name="NextAccount" value="'.$NextCode.'" />';

echo '<br />
		<table>
		<tr>
			<td><input type="submit" name="Previous" value="' . _('Prev Account') . '" /></td>
			<td><input type="submit" name="Select" value="' . _('Select Account') . '" /></td>
			<td><input type="submit" name="Next" value="' . _('Next Account') . '" /></td>
		</tr>
		</table>
		<br />
    </div>
	</form>';

// End of account selection

if (isset($SelectedAccount) and $SelectedAccount != '') {

	$CurrentYearEndPeriod = GetPeriod(Date($_SESSION['DefaultDateFormat'],YearEndDate($_SESSION['YearEnd'],0)));

// If the update button has been hit, then update chartdetails with the budget figures
// for this year and next.
	if (isset($_POST['update'])) {
		$ErrMsg = _('Cannot update GL budgets');
		$DbgMsg = _('The SQL that failed to update the GL budgets was');
		for ($i=1; $i<=12; $i++) {
			$SQL="UPDATE chartdetails SET budget='" . round(filter_number_format($_POST[$i.'last']),$_SESSION['CompanyRecord']['decimalplaces']). "'
					WHERE period='" . ($CurrentYearEndPeriod-(24-$i)) ."'
					AND  accountcode = '" . $SelectedAccount."'";
			$result=DB_query($SQL,$ErrMsg,$DbgMsg);
			$SQL="UPDATE chartdetails SET budget='" . round(filter_number_format($_POST[$i.'this']),$_SESSION['CompanyRecord']['decimalplaces']). "'
					WHERE period='" . ($CurrentYearEndPeriod-(12-$i)) ."'
					AND  accountcode = '" . $SelectedAccount."'";
			$result=DB_query($SQL,$ErrMsg,$DbgMsg);
			$SQL="UPDATE chartdetails SET budget='". round(filter_number_format($_POST[$i.'next']),$_SESSION['CompanyRecord']['decimalplaces'])."'
					WHERE period='" .  ($CurrentYearEndPeriod+$i) ."'
					AND  accountcode = '" . $SelectedAccount."'";
			$result=DB_query($SQL,$ErrMsg,$DbgMsg);
		}
	}
// End of update

	$YearEndYear=Date('Y', YearEndDate($_SESSION['YearEnd'],0));

/* If the periods dont exist then create them */
	for ($i=1; $i <=36; $i++) {
		$MonthEnd=mktime(0,0,0,$_SESSION['YearEnd']+1+$i,0,$YearEndYear-2);
		$period=GetPeriod(Date($_SESSION['DefaultDateFormat'],$MonthEnd), false);
		$PeriodEnd[$period]=Date('M Y',$MonthEnd);
	}
	include('includes/GLPostings.inc'); //creates chartdetails with correct values
// End of create periods

	$SQL="SELECT period,
					budget,
					actual
				FROM chartdetails
				WHERE accountcode='" . $SelectedAccount . "'";

	$result=DB_query($SQL);
	while ($myrow=DB_fetch_array($result)) {
		$Budget[$myrow['period']]=$myrow['budget'];
		$Actual[$myrow['period']]=$myrow['actual'];
	}


	if (isset($_POST['Apportion'])) {
		for ($i=1; $i<=12; $i++) {
			if (filter_number_format($_POST['AnnualAmountLY']) != '0' AND is_numeric(filter_number_format($_POST['AnnualAmountLY']))) {
				$Budget[$CurrentYearEndPeriod+$i-24]	=round(filter_number_format( $_POST['AnnualAmountLY'])/12,0);
			}
			if (filter_number_format($_POST['AnnualAmountTY']) != '0' AND is_numeric(filter_number_format($_POST['AnnualAmountTY']))) {
				$Budget[$CurrentYearEndPeriod+$i-12]	= round(filter_number_format($_POST['AnnualAmountTY'])/12,0);
			}
			if (filter_number_format($_POST['AnnualAmount']) != '0' AND is_numeric(filter_number_format($_POST['AnnualAmount']))) {
				$Budget[$CurrentYearEndPeriod+$i]	= round(filter_number_format($_POST['AnnualAmount'])/12,0);
			}
		}
	}

	$LastYearActual=0;
	$LastYearBudget=0;
	$ThisYearActual=0;
	$ThisYearBudget=0;
	$NextYearActual=0;
	$NextYearBudget=0;

// Table Headers

	echo '<form id="form" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<br />
			<table class="selection">
			<tr>
				<th colspan="3">' .  _('Last Financial Year')  . '</th>
				<th colspan="3">' .  _('This Financial Year')  . '</th>
				<th colspan="3">' .  _('Next Financial Year')  . '</th>
			</tr>
			<tr>
				<th colspan="3">' .  _('Year ended').' - ' . Date($_SESSION['DefaultDateFormat'],YearEndDate($_SESSION['YearEnd'],-1))  . '</th>
				<th colspan="3">' .  _('Year ended').' - ' . Date($_SESSION['DefaultDateFormat'],YearEndDate($_SESSION['YearEnd'],0))  . '</th>
				<th colspan="3">' .  _('Year ended').' - ' . Date($_SESSION['DefaultDateFormat'],YearEndDate($_SESSION['YearEnd'],1))  . '</th>
			</tr>
			<tr>';
	for ($i=0; $i<3; $i++) {
		echo '<th>' .  _('Period'). '</th>
				<th>' .  _('Actual') . '</th>
				<th>' .  _('Budget') . '</th>';
	}
	echo '</tr>';

// Main Table

	for ($i=1; $i<=12; $i++) {
		echo '<tr>';
		echo '<th>' .  $PeriodEnd[$CurrentYearEndPeriod-(24-$i)]  . '</th>';
		echo '<td style="background-color:d2e5e8" class="number">' . locale_number_format($Actual[$CurrentYearEndPeriod-(24-$i)],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>';
		echo '<td><input type="text" class="number" size="14" name="'.$i.'last" value="'.locale_number_format($Budget[$CurrentYearEndPeriod-(24-$i)],$_SESSION['CompanyRecord']['decimalplaces']) .'" /></td>';
		echo '<th>' .  $PeriodEnd[$CurrentYearEndPeriod-(12-$i)]  . '</th>';
		echo '<td style="background-color:d2e5e8" class="number">' . locale_number_format($Actual[$CurrentYearEndPeriod-(12-$i)],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>';
		echo '<td><input type="text" class="number" size="14" name="'.$i.'this" value="'. locale_number_format($Budget[$CurrentYearEndPeriod-(12-$i)],$_SESSION['CompanyRecord']['decimalplaces']) .'" /></td>';
		echo '<th>' .  $PeriodEnd[$CurrentYearEndPeriod+($i)]  . '</th>';
		echo '<td style="background-color:d2e5e8" class="number">' . locale_number_format($Actual[$CurrentYearEndPeriod+$i],$_SESSION['CompanyRecord']['decimalplaces']) . '</td>';
		echo '<td><input type="text" class="number" size="14" name="'.$i.'next" value="'. locale_number_format($Budget[$CurrentYearEndPeriod+$i],$_SESSION['CompanyRecord']['decimalplaces']) .'" /></td>';
		echo '</tr>';
		$LastYearActual=$LastYearActual+$Actual[$CurrentYearEndPeriod-(24-$i)];
		$LastYearBudget=$LastYearBudget+$Budget[$CurrentYearEndPeriod-(24-$i)];
		$ThisYearActual=$ThisYearActual+$Actual[$CurrentYearEndPeriod-(12-$i)];
		$ThisYearBudget=$ThisYearBudget+$Budget[$CurrentYearEndPeriod-(12-$i)];
		$NextYearActual=$NextYearActual+$Actual[$CurrentYearEndPeriod+($i)];
		$NextYearBudget=$NextYearBudget+$Budget[$CurrentYearEndPeriod+($i)];
	}

// Total Line

	echo '<tr>
			<th>' .  _('Total')  . '</th>
			<th class="number">' . locale_number_format($LastYearActual,$_SESSION['CompanyRecord']['decimalplaces']). '</th>
			<th class="number">' . locale_number_format($LastYearBudget,$_SESSION['CompanyRecord']['decimalplaces']). '</th>
			<th class="number"></th>
			<th class="number">' . locale_number_format($ThisYearActual,$_SESSION['CompanyRecord']['decimalplaces']). '</th>
			<th class="number">' . locale_number_format($ThisYearBudget,$_SESSION['CompanyRecord']['decimalplaces']). '</th>
			<th class="number"></th>
			<th class="number">' . locale_number_format($NextYearActual,$_SESSION['CompanyRecord']['decimalplaces']). '</th>
			<th class="number">' . locale_number_format($NextYearBudget,$_SESSION['CompanyRecord']['decimalplaces']). '</th>
		</tr>
		<tr>
			<td colspan="2">' . _('Annual Budget') . '</td>
			<td><input class="number" type="text" size="14" name="AnnualAmountLY" value="0.00" /></td>
			<td></td>
			<td></td>
			<td><input class="number" type="text" size="14" name="AnnualAmountTY" value="0.00" /></td>
			<td></td>
			<td><input onchange="numberFormat(this,' . $_SESSION['CompanyRecord']['decimalplaces'] . ')" class="number" type="text" size="14" name="AnnualAmount" value="0.00" /></td>
			<td><input type="submit" name="Apportion" value="' . _('Apportion Budget') . '" /></td>
		</tr>
		</table>';

	echo '<input type="hidden" name="SelectedAccount" value="'.$SelectedAccount.'" />';

	echo '<script  type="text/javascript">defaultControl(document.form.1next);</script>';
	echo '<br />
		<div class="centre">
			<input type="submit" name="update" value="' . _('Update') . '" />
		</div>
		</div>
		</form>';

	$SQL="SELECT MIN(periodno) FROM periods";
	$result=DB_query($SQL);
	$MyRow=DB_fetch_array($result);
	$FirstPeriod=$MyRow[0];

	$SQL="SELECT MAX(periodno) FROM periods";
	$result=DB_query($SQL);
	$MyRow=DB_fetch_array($result);
	$LastPeriod=$MyRow[0];

	for ($i=$FirstPeriod;$i<=$LastPeriod;$i++) {
		$sql="SELECT accountcode,
					period,
					budget,
					actual,
					bfwd,
					bfwdbudget
				FROM chartdetails
				WHERE period ='". $i . "'
				AND  accountcode = '" . $SelectedAccount . "'";

		$ErrMsg = _('Could not retrieve the ChartDetail records because');
		$result = DB_query($sql,$ErrMsg);

		while ($myrow=DB_fetch_array($result)) {

			$CFwdBudget = $myrow['bfwdbudget'] + $myrow['budget'];
			$sql = "UPDATE chartdetails
					SET bfwdbudget='" . $CFwdBudget . "'
					WHERE period='" . ($myrow['period'] +1) . "'
					AND  accountcode = '" . $SelectedAccount . "'";

			$ErrMsg =_('Could not update the chartdetails record because');
			$updresult = DB_query($sql,$ErrMsg);
		}
	} /* end of for loop */
}

include('includes/footer.php');

?>
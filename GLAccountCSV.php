<?php
// GLAccountCSV.php


include ('includes/session.php');
$Title = _('General Ledger Account Report');

$ViewTopic= 'GeneralLedger';
$BookMark = 'GLAccountCSV';

include('includes/header.php');
include('includes/GLPostings.inc');

if (isset($_POST['Period'])) {
	$SelectedPeriod = $_POST['Period'];
} elseif (isset($_GET['Period'])) {
	$SelectedPeriod = $_GET['Period'];
}

echo '<p class="page_title_text"><img src="'.$RootPath, '/css/', $Theme, '/images/transactions.png" title="' . _('General Ledger Account Inquiry') . '" alt="" />' . ' ' . _('General Ledger Account Report') . '</p>';

echo '<div class="page_help_text">' . _('Use the keyboard Shift key to select multiple accounts and periods') . '</div><br />';

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/*Dates in SQL format for the last day of last month*/
$DefaultPeriodDate = Date ('Y-m-d', Mktime(0,0,0,Date('m'),0,Date('Y')));

/*Show a form to allow input of criteria for the report */
echo '<table>
	        <tr>
	         <td>' . _('Selected Accounts') . ':</td>
	         <td><select name="Account[]" size="12" multiple="multiple">';
$sql = "SELECT chartmaster.accountcode,
			   chartmaster.accountname
		FROM chartmaster
		INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canview=1
		ORDER BY chartmaster.accountcode";
$AccountsResult = DB_query($sql);
$i=0;
while ($myrow=DB_fetch_array($AccountsResult)) {
	if(isset($_POST['Account'][$i]) AND $myrow['accountcode'] == $_POST['Account'][$i]) {
		echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' ' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
		$i++;
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' ' . htmlspecialchars($myrow['accountname'], ENT_QUOTES, 'UTF-8', false) . '</option>';
	}
}
echo '</select></td>';

echo '<td>' . _('For Period range').':</td>
		<td><select name="Period[]" size="12" multiple="multiple">';
$sql = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno DESC";
$Periods = DB_query($sql);
$id=0;

while ($myrow=DB_fetch_array($Periods)) {
	if (isset($SelectedPeriod[$id]) and $myrow['periodno'] == $SelectedPeriod[$id]) {
		echo '<option selected="selected" value="' . $myrow['periodno'] . '">' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period'])) . '</option>';
		$id++;
	} else {
		echo '<option value="' . $myrow['periodno'] . '">' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period'])) . '</option>';
	}
}
echo '</select></td></tr>';

//Select the tag
echo '<tr><td>' . _('Select Tag') . ':</td><td><select name="tag">';

$SQL = "SELECT tagref,
	       tagdescription
		FROM tags
		ORDER BY tagref";

$result=DB_query($SQL);
echo '<option value="0">0 - ' . _('All tags') . '</option>';
while ($myrow=DB_fetch_array($result)) {
	if (isset($_POST['tag']) and $_POST['tag']==$myrow['tagref']) {
	   echo '<option selected="selected" value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	} else {
	   echo '<option value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
	}
}
echo '</select></td></tr>';
// End select tag

echo '</table><br />
		<div class="centre"><input type="submit" name="MakeCSV" value="'._('Make CSV File').'" /></div>
    </div>
	</form>';

/* End of the Form  rest of script is what happens if the show button is hit*/

if (isset($_POST['MakeCSV'])) {

	if (!isset($SelectedPeriod)) {
		prnMsg(_('A period or range of periods must be selected from the list box'),'info');
		include('includes/footer.php');
		exit;
	}
	if (!isset($_POST['Account'])) {
		prnMsg(_('An account or range of accounts must be selected from the list box'),'info');
		include('includes/footer.php');
		exit;
	}

	if (!file_exists($_SESSION['reports_dir'])) {
		$Result = mkdir('./' . $_SESSION['reports_dir']);
	}

	$FileName = $_SESSION['reports_dir'] . '/Accounts_Listing_' . Date('Y-m-d') .'.csv';

	$fp = fopen($FileName,'w');

	if ($fp==FALSE) {
		prnMsg(_('Could not open or create the file under') . ' ' . $FileName,'error');
		include('includes/footer.php');
		exit;
	}

	foreach ($_POST['Account'] as $SelectedAccount) {
		/*Is the account a balance sheet or a profit and loss account */
		$SQL = "SELECT chartmaster.accountname,
								accountgroups.pandl
							    FROM accountgroups
							    INNER JOIN chartmaster ON accountgroups.groupname=chartmaster.group_
							    WHERE chartmaster.accountcode='" . $SelectedAccount . "'";
		$result = DB_query($SQL);
		$AccountDetailRow = DB_fetch_row($result);
		$AccountName = $AccountDetailRow[1];
		if ($AccountDetailRow[1]==1) {
			$PandLAccount = True;
		}else{
			$PandLAccount = False; /*its a balance sheet account */
		}

		$FirstPeriodSelected = min($SelectedPeriod);
		$LastPeriodSelected = max($SelectedPeriod);

		if ($_POST['tag']==0) {
	 		$sql= "SELECT type,
				      typename,
				      gltrans.typeno,
				      gltrans.trandate,
				      gltrans.narrative,
          			      gltrans.amount,
				      gltrans.periodno,
				      gltrans.tag
				FROM gltrans, systypes
				WHERE gltrans.account = '" . $SelectedAccount . "'
				AND systypes.typeid=gltrans.type
				AND posted=1
				AND periodno>='" . $FirstPeriodSelected . "'
				AND periodno<='" . $LastPeriodSelected . "'
				ORDER BY periodno, gltrans.trandate, counterindex";

		} else {
	 		$sql= "SELECT gltrans.type,
						gltrans.typename,
						gltrans.typeno,
						gltrans.trandate,
						gltrans.narrative,
						gltrans.amount,
						gltrans.periodno,
						gltrans.tag
					FROM gltrans, systypes
					WHERE gltrans.account = '" . $SelectedAccount . "'
					AND systypes.typeid=gltrans.type
					AND posted=1
					AND periodno>='" . $FirstPeriodSelected . "'
					AND periodno<='" . $LastPeriodSelected . "'
					AND tag='".$_POST['tag']."'
					ORDER BY periodno, gltrans.trandate, counterindex";
		}

		$ErrMsg = _('The transactions for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved because') ;
		$TransResult = DB_query($sql,$ErrMsg);

		fwrite($fp, $SelectedAccount . ' - ' . $AccountName . ' ' . _('for period'). ' ' . $FirstPeriodSelected . ' ' . _('to') . ' ' . $LastPeriodSelected . "\n");
		if ($PandLAccount==True) {
			$RunningTotal = 0;
		} else {
			$sql = "SELECT bfwd,
					actual,
					period
				FROM chartdetails
				WHERE chartdetails.accountcode= '" . $SelectedAccount . "'
				AND chartdetails.period='" . $FirstPeriodSelected . "'";

			$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
			$ChartDetailsResult = DB_query($sql,$ErrMsg);
			$ChartDetailRow = DB_fetch_array($ChartDetailsResult);

			$RunningTotal =$ChartDetailRow['bfwd'];
			if ($RunningTotal < 0 ) {
				fwrite($fp,$SelectedAccount . ', '  .$FirstPeriodSelected . ', ' . _('Brought Forward Balance') . ',,,,' . -$RunningTotal . "\n");
			} else {
				fwrite($fp,$SelectedAccount . ', '  .$FirstPeriodSelected . ', ' . _('Brought Forward Balance') . ',,,' . $RunningTotal . "\n");
			}
		}
		$PeriodTotal = 0;
		$PeriodNo = -9999;

		while ($myrow=DB_fetch_array($TransResult)) {

			if ($myrow['periodno']!=$PeriodNo) {
				if ($PeriodNo!=-9999) { //ie its not the first time around
					/*Get the ChartDetails balance b/fwd and the actual movement in the account for the period as recorded in the chart details - need to ensure integrity of transactions to the chart detail movements. Also, for a balance sheet account it is the balance carried forward that is important, not just the transactions*/
					$sql = "SELECT bfwd,
									actual,
									period
							FROM chartdetails
							WHERE chartdetails.accountcode= '" . $SelectedAccount . "'
							AND chartdetails.period='" . $PeriodNo . "'";

					$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
					$ChartDetailsResult = DB_query($sql,$ErrMsg);
					$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
					if ($PeriodTotal < 0) {
						fwrite($fp, $SelectedAccount . ', ' . $PeriodNo . ', ' . _('Period Total') . ',,,,' . -$PeriodTotal. "\n");
					} else {
						fwrite($fp, $SelectedAccount . ', ' . $PeriodNo . ', ' . _('Period Total') . ',,,' . $PeriodTotal. "\n");
					}
				}
				$PeriodNo = $myrow['periodno'];
				$PeriodTotal = 0;
			}

			$RunningTotal += $myrow['amount'];
			$PeriodTotal += $myrow['amount'];

			$FormatedTranDate = ConvertSQLDate($myrow['trandate']);

			$tagsql="SELECT tagdescription FROM tags WHERE tagref='".$myrow['tag'] . "'";
			$tagresult=DB_query($tagsql);
			$tagrow = DB_fetch_array($tagresult);
			if ($myrow['amount']<0) {
				fwrite($fp, $SelectedAccount . ',' . $myrow['periodno'] . ', ' . $myrow['typename'] . ',' . $myrow['typeno'] . ',' . $FormatedTranDate . ',,' . -$myrow['amount'] . ',' . $myrow['narrative'] . ',' . $tagrow['tagdescription']. "\n");
			} else {
				fwrite($fp, $SelectedAccount . ',' . $myrow['periodno'] . ', ' . $myrow['typename'] . ',' . $myrow['typeno'] . ',' . $FormatedTranDate . ',' . $myrow['amount'] . ',,' . $myrow['narrative'] . ',' . $tagrow['tagdescription']. "\n");
			}
		} //end loop around GLtrans
		if ($PeriodTotal <>0) {
			if ($PeriodTotal < 0) {
				fwrite($fp, $SelectedAccount . ', ' . $PeriodNo . ', ' . _('Period Total') . ',,,,' . -$PeriodTotal. "\n");
			} else {
				fwrite($fp, $SelectedAccount . ', ' . $PeriodNo . ', ' . _('Period Total') . ',,,' . $PeriodTotal. "\n");
			}
		}
		if ($PandLAccount==True) {
			if ($RunningTotal < 0) {
				fwrite($fp, $SelectedAccount . ',' . $LastPeriodSelected . ', ' . _('Total Period Movement') . ',,,,' . -$RunningTotal . "\n");
			} else {
				fwrite($fp, $SelectedAccount . ',' . $LastPeriodSelected . ', ' . _('Total Period Movement') . ',,,' . $RunningTotal . "\n");
			}
		} else { /*its a balance sheet account*/
			if ($RunningTotal < 0) {
				fwrite($fp, $SelectedAccount . ',' . $LastPeriodSelected . ', ' . _('Balance C/Fwd') . ',,,,' . -$RunningTotal . "\n");
			} else {
				fwrite($fp, $SelectedAccount . ',' . $LastPeriodSelected . ', ' . _('Balance C/Fwd') . ',,,' . $RunningTotal . "\n");
			}
		}

	} /*end for each SelectedAccount */
	fclose($fp);
	echo '<p><a href="' .  $FileName . '">' . _('click here') . '</a> ' . _('to view the file') . '<br />';
} /* end of if CreateCSV button hit */

include('includes/footer.php');
?>
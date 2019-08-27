<?php
// GLJournalInquiry.php
include ('includes/session.php');
$Title = _('General Ledger Journal Inquiry');
$ViewTopic = 'GeneralLedger';
$BookMark = 'GLJournalInquiry';

include ('includes/header.php');

echo '<p class="page_title_text"><img src="' . $RootPath, '/css/', $Theme, '/images/money_add.png" title="' . _('Search') . '" alt="" />' . ' ' . $Title . '</p>';

if (!isset($_POST['Show'])) {
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<table class="selection">';
	echo '<tr><th colspan="3">' . _('Selection Criteria') . '</th></tr>';

	$SQL = "SELECT typeid,systypes.typeno,typename FROM
		systypes INNER JOIN gltrans ON systypes.typeid=gltrans.type
		GROUP BY typeid";
	$Result = DB_query($SQL);
	if (DB_num_rows($Result) > 0) {
		echo '<tr>

			<td>' . _('Transaction Type') . ' </td>
			<td> <select name="TransType">';
		while ($MyRow = DB_fetch_array($Result)) {
			if (!isset($MaxJournalNumberUsed)) {
				$MaxJournalNumberUsed = $MyRow['typeno'];
			} else {
				$MaxJournalNumberUsed = ($MyRow['typeno'] > $MaxJournalNumberUsed) ? $MyRow['typeno'] : $MaxJournalNumberUsed;
			}
			echo '<option value="' . $MyRow['typeid'] . '">' . _($MyRow['typename']) . '</option>';
		}
		echo '</select></td>
			</tr>';

	}

	echo '<tr>
			<td>' . _('Journal Number Range') . ' (' . _('Between') . ' 1 ' . _('and') . ' ' . $MaxJournalNumberUsed . ')</td>
			<td>' . _('From') . ':' . '<input type="text" class="number" name="NumberFrom" size="10" maxlength="11" value="1" />' . '</td>
			<td>' . _('To') . ':' . '<input type="text" class="number" name="NumberTo" size="10" maxlength="11" value="' . $MaxJournalNumberUsed . '" />' . '</td>
		</tr>';

	$SQL = "SELECT MIN(trandate) AS fromdate,
					MAX(trandate) AS todate FROM gltrans WHERE type=0";
	$Result = DB_query($SQL);
	$MyRow = DB_fetch_array($Result);
	if (isset($MyRow['fromdate']) and $MyRow['fromdate'] != '') {
		$FromDate = $MyRow['fromdate'];
		$ToDate = $MyRow['todate'];
	} else {
		$FromDate = date('Y-m-d');
		$ToDate = date('Y-m-d');
	}

	echo '<tr><td>' . _('Journals Dated Between') . ':</td>
		<td>' . _('From') . ':' . '<input type="text" name="FromTransDate" class="date" maxlength="10" size="11" value="' . ConvertSQLDate($FromDate) . '" /></td>
		<td>' . _('To') . ':' . '<input type="text" name="ToTransDate" class="date" maxlength="10" size="11" value="' . ConvertSQLDate($ToDate) . '" /></td>
		</tr>';

	echo '</table>';
	echo '<br /><div class="centre"><input type="submit" name="Show" value="' . _('Show transactions') . '" /></div>';
	echo '</form>';
} else {

	$SQL = "SELECT gltrans.typeno,
				gltrans.trandate,
				gltrans.account,
				chartmaster.accountname,
				gltrans.narrative,
				gltrans.amount,
				gltrans.tag,
				tags.tagdescription,
				gltrans.jobref
			FROM gltrans
			INNER JOIN chartmaster
				ON gltrans.account=chartmaster.accountcode
			LEFT JOIN tags
				ON gltrans.tag=tags.tagref
			WHERE gltrans.type='" . $_POST['TransType'] . "'
				AND gltrans.trandate>='" . FormatDateForSQL($_POST['FromTransDate']) . "'
				AND gltrans.trandate<='" . FormatDateForSQL($_POST['ToTransDate']) . "'
				AND gltrans.typeno>='" . $_POST['NumberFrom'] . "'
				AND gltrans.typeno<='" . $_POST['NumberTo'] . "'
			ORDER BY gltrans.typeno";

	$Result = DB_query($SQL);
	if (DB_num_rows($Result) == 0) {
		prnMsg(_('There are no transactions for this account in the date range selected'), 'info');
	} else {
		echo '<table class="selection">';
		echo '<tr>
				<th>' . _('Date') . '</th>
				<th>' . _('Journal Number') . '</th>
				<th>' . _('Account Code') . '</th>
				<th>' . _('Account Description') . '</th>
				<th>' . _('Narrative') . '</th>
				<th>' . _('Amount') . ' ' . $_SESSION['CompanyRecord']['currencydefault'] . '</th>
				<th>' . _('Tag') . '</th>
			</tr>';

		$LastJournal = 0;

		while ($MyRow = DB_fetch_array($Result)) {

			if ($MyRow['tag'] == 0) {
				$MyRow['tagdescription'] = 'None';
			}

			if ($MyRow['typeno'] != $LastJournal) {

				echo '<tr>
						<td colspan="8"></td>
					</tr>
					<tr>
					<td>' . ConvertSQLDate($MyRow['trandate']) . '</td>
					<td class="number">' . $MyRow['typeno'] . '</td>';

			} else {
				echo '<tr>
						<td colspan="2"></td>';
			}

			// if user is allowed to see the account we show it, other wise we show "OTHERS ACCOUNTS"
			$CheckSql = "SELECT count(*)
						 FROM glaccountusers
						 WHERE accountcode= '" . $MyRow['account'] . "'
							 AND userid = '" . $_SESSION['UserID'] . "'
							 AND canview = '1'";
			$CheckResult = DB_query($CheckSql);
			$CheckRow = DB_fetch_row($CheckResult);

			if ($CheckRow[0] > 0) {
				echo '<td>' . $MyRow['account'] . '</td>
						<td>' . $MyRow['accountname'] . '</td>';
			} else {
				echo '<td>' . _('Others') . '</td>
						<td>' . _('Other GL Accounts') . '</td>';
			}

			echo '<td>' . $MyRow['narrative'] . '</td>
					<td class="number">' . locale_number_format($MyRow['amount'], $_SESSION['CompanyRecord']['decimalplaces']) . '</td>
					<td class="number">' . $MyRow['tag'] . ' - ' . $MyRow['tagdescription'] . '</td>';

			if ($MyRow['typeno'] != $LastJournal and $CheckRow[0] > 0) {
				if ($_SESSION['Language'] == 'zh_CN.utf8' or $_SESSION['Language'] == 'zh_hk.utf8') {
					echo '<td class="number"><a href="PDFGLJournalCN.php?JournalNo=' . $MyRow['typeno'] . '&Type=' . $_POST['TransType'] . '">' . _('Print') . '</a></td></tr>';
				} else {
					echo '<td class="number"><a href="PDFGLJournal.php?JournalNo=' . $MyRow['typeno'] . '">' . _('Print') . '</a></td></tr>';
				}

				$LastJournal = $MyRow['typeno'];
			} else {
				echo '<td colspan="1"></td></tr>';
			}

		}
		echo '</table>';
	} //end if no bank trans in the range to show
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<br /><div class="centre"><input type="submit" name="Return" value="' . _('Select Another Date') . '" /></div>';
	echo '</form>';
}
include ('includes/footer.php');
?>
<?php
/* Display outstanding debtors, creditors, etc */

include ('includes/session.php');
$Title = _('Dashboard');
$ViewTopic = 'GeneralLedger'; // RChacon: You should be in this topic ?
$BookMark = 'Dashboard';
include ('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/gl.png" title="', // Icon image.
$Title, '" /> ', // Icon title.
$Title, '</p>'; // Page title.
$Sql = "SELECT pagesecurity
		FROM scripts
		WHERE scripts.script = 'AgedDebtors.php'";
$ErrMsg = _('The security for Aging Debtors cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security1Result = DB_query($Sql, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security1Result);
$DebtorSecurity = $MyUserRow['pagesecurity'];

$Sql = "SELECT pagesecurity
		FROM scripts
		WHERE scripts.script = 'SuppPaymentRun.php'";
$ErrMsg = _('The security for upcoming payments cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security2Result = DB_query($Sql, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security2Result);
$PayeeSecurity = $MyUserRow['pagesecurity'];

$Sql = "SELECT pagesecurity
		FROM scripts
		WHERE scripts.script = 'GLAccountInquiry.php'";
$ErrMsg = _('The security for G/L Accounts cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security2Result = DB_query($Sql, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security2Result);
$CashSecurity = $MyUserRow['pagesecurity'];

$Sql = "SELECT pagesecurity
		FROM scripts
		WHERE scripts.script = 'SelectSalesOrder.php'";
$ErrMsg = _('The security for Aging Debtors cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security1Result = DB_query($Sql, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security1Result);
$OrderSecurity = $MyUserRow['pagesecurity'];

if (in_array($DebtorSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($DebtorSecurity)) {
	echo '<br />
		<h2>', _('Overdue Customer Balances'), '</h2>
		<table class="selection">
		<thead>
			<tr>
				<th>', _('Customer'), '</th>
				<th>', _('Reference'), '</th>
				<th>', _('Trans Date'), '</th>
				<th>', _('Due Date'), '</th>
				<th>', _('Balance'), '</th>
				<th>', _('Current'), '</th>
				<th>', _('Due Now'), '</th>
				<th>', '> ', $_SESSION['PastDueDays1'], ' ', _('Days Over'), '</th>
				<th>', '> ', $_SESSION['PastDueDays2'], ' ', _('Days Over'), '</th>
			</tr>
		</thead><tbody>';
	$j = 1;
	$TotBal = 0;
	$TotCurr = 0;
	$TotDue = 0;
	$TotOD1 = 0;
	$TotOD2 = 0;
	$CurrDecimalPlaces = 2; //By default.
	if (!isset($_POST['Salesman'])) {
		$_POST['Salesman'] = '';
	}
	if ($_SESSION['SalesmanLogin'] != '') {
		$_POST['Salesman'] = $_SESSION['SalesmanLogin'];
	}
	if (trim($_POST['Salesman']) != '') {
		$SalesLimit = " AND custbranch.salesman = '" . $_POST['Salesman'] . "' ";
	} else {
		$SalesLimit = '';
	}
	$Sql = "SELECT debtorsmaster.debtorno,
				debtorsmaster.name,
				currencies.currency,
				currencies.decimalplaces,
				paymentterms.terms,
				debtorsmaster.creditlimit,
				holdreasons.dissallowinvoices,
				holdreasons.reasondescription,
				SUM(
					debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
				) AS balance,
				SUM(
					CASE WHEN (paymentterms.daysbeforedue > 0)
					THEN
						CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) >= paymentterms.daysbeforedue
						THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
						ELSE 0 END
					ELSE
						CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= 0
						THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
					END
				) AS due,
				SUM(
					CASE WHEN (paymentterms.daysbeforedue > 0)
					THEN
						CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays1'] . ")
						THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
					ELSE
						CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays1'] . "
						THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
						ELSE 0 END
					END
				) AS overdue1,
				SUM(
					CASE WHEN (paymentterms.daysbeforedue > 0)
					THEN
						CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays2'] . ")
						THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
					ELSE
						CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays2'] . "
						THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
						ELSE 0 END
					END
				) AS overdue2
				FROM debtorsmaster,
					paymentterms,
					holdreasons,
					currencies,
					debtortrans,
					custbranch
				WHERE debtorsmaster.paymentterms = paymentterms.termsindicator
					AND debtorsmaster.currcode = currencies.currabrev
					AND debtorsmaster.holdreason = holdreasons.reasoncode
					AND debtorsmaster.debtorno = debtortrans.debtorno
					AND custbranch.debtorno=debtorsmaster.debtorno
					AND custbranch.branchcode=debtortrans.branchcode
					" . $SalesLimit . "
				GROUP BY debtorsmaster.debtorno,
					debtorsmaster.name,
					currencies.currency,
					paymentterms.terms,
					paymentterms.daysbeforedue,
					paymentterms.dayinfollowingmonth,
					debtorsmaster.creditlimit,
					holdreasons.dissallowinvoices,
					holdreasons.reasondescription
				HAVING
					ROUND(ABS(SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc)),currencies.decimalplaces) > 0";
	$CustomerResult = DB_query($Sql, '', '', False, False); /*dont trap errors handled below*/

	if (DB_error_no() != 0) {
		prnMsg(_('The customer details could not be retrieved by the SQL because') . ' ' . DB_error_msg(), 'error');
		echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a>';
		if ($debug == 1) {
			echo '<br />', $Sql;
		}
		include ('includes/footer.php');
		exit;
	}

	while ($AgedAnalysis = DB_fetch_array($CustomerResult)) {
		$CurrDecimalPlaces = $AgedAnalysis['decimalplaces'];
		$DisplayDue = locale_number_format($AgedAnalysis['due'] - $AgedAnalysis['overdue1'], $CurrDecimalPlaces);
		$DisplayCurrent = locale_number_format($AgedAnalysis['balance'] - $AgedAnalysis['due'], $CurrDecimalPlaces);
		$DisplayBalance = locale_number_format($AgedAnalysis['balance'], $CurrDecimalPlaces);
		$DisplayOverdue1 = locale_number_format($AgedAnalysis['overdue1'] - $AgedAnalysis['overdue2'], $CurrDecimalPlaces);
		$DisplayOverdue2 = locale_number_format($AgedAnalysis['overdue2'], $CurrDecimalPlaces);
		if ($DisplayDue <> 0 or $DisplayOverdue1 <> 0 or $DisplayOverdue2 <> 0) {
			$TotBal+= $AgedAnalysis['balance'];
			$TotCurr+= ($AgedAnalysis['balance'] - $AgedAnalysis['due']);
			$TotDue+= ($AgedAnalysis['due'] - $AgedAnalysis['overdue1']);
			$TotOD1+= ($AgedAnalysis['overdue1'] - $AgedAnalysis['overdue2']);
			$TotOD2+= $AgedAnalysis['overdue2'];
			echo '<tr class="striped_row">
					<td class="text" colspan="4"><a href="CustomerInquiry.php?CustomerID=', $AgedAnalysis['debtorno'], '"><b>', $AgedAnalysis['debtorno'], ' - ', $AgedAnalysis['name'], '</b></a></td>
					<td class="number"><b>', $DisplayBalance, '</b></td>
					<td class="number"><b>', $DisplayCurrent, '</b></td>
					<td class="number" style="color:orange;"><b>', $DisplayDue, '</b></td>
					<td class="number" style="color:red;"><b>', $DisplayOverdue1, '</b></td>
					<td class="number" style="color:red;"><b>', $DisplayOverdue2, '</b></td>
				</tr>';

			if ($_SESSION['SalesmanLogin'] != '') {
				$SalesLimit = " AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "'";
			} else {
				$SalesLimit = '';
			}

			$Sql = "SELECT systypes.typename,
						debtortrans.transno,
						debtortrans.trandate,
						daysbeforedue,
						dayinfollowingmonth,
						(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) as balance,
						(CASE WHEN (paymentterms.daysbeforedue > 0)
							THEN
								(CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) >= paymentterms.daysbeforedue
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
							ELSE
								(CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(ADDDATE(last_day(debtortrans.trandate), 1), paymentterms.dayinfollowingmonth)) >= 0
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
						END) AS due,
						(CASE WHEN (paymentterms.daysbeforedue > 0)
							THEN
								(CASE WHEN TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays1'] . ") THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END)
							ELSE
								(CASE WHEN (TO_DAYS(Now()) - TO_DAYS(ADDDATE(ADDDATE(last_day(debtortrans.trandate), 1), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays1'] . ")
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
						END) AS overdue1,
						(CASE WHEN (paymentterms.daysbeforedue > 0)
							THEN
								(CASE WHEN TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays2'] . ")
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
							ELSE
								(CASE WHEN (TO_DAYS(Now()) - TO_DAYS(ADDDATE(ADDDATE(last_day(debtortrans.trandate), 1), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays2'] . ")
								THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
								ELSE 0 END)
						END) AS overdue2
				   FROM debtorsmaster,
						paymentterms,
						debtortrans,
						systypes,
						custbranch
				   WHERE systypes.typeid = debtortrans.type
						AND debtorsmaster.paymentterms = paymentterms.termsindicator
						AND debtorsmaster.debtorno = debtortrans.debtorno
						AND debtortrans.debtorno = '" . $AgedAnalysis['debtorno'] . "'
						AND ABS(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc)>0.004
						AND custbranch.debtorno=debtorsmaster.debtorno
						AND custbranch.branchcode=debtortrans.branchcode
						" . $SalesLimit . "
					ORDER BY debtortrans.trandate";

			$DetailResult = DB_query($Sql, '', '', False, False);
			if (DB_error_no() != 0) {
				prnMsg(_('The details of outstanding transactions for customer') . ' - ' . $AgedAnalysis['debtorno'] . ' ' . _('could not be retrieved because') . ' - ' . DB_error_msg(), 'error');
				echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a>';
				if ($debug == 1) {
					echo '<br />' . _('The SQL that failed was') . '<br />' . $Sql;
				}
				include ('includes/footer.php');
				exit;
			}

			while ($DetailTrans = DB_fetch_array($DetailResult)) {
				$DisplayTranDate = ConvertSQLDate($DetailTrans['trandate']);
				$DisplayBalance = locale_number_format($DetailTrans['balance'], $CurrDecimalPlaces);
				$DisplayCurrent = locale_number_format($DetailTrans['balance'] - $DetailTrans['due'], $CurrDecimalPlaces);
				$DisplayDue = locale_number_format($DetailTrans['due'] - $DetailTrans['overdue1'], $CurrDecimalPlaces);
				$DisplayOverdue1 = locale_number_format($DetailTrans['overdue1'] - $DetailTrans['overdue2'], $CurrDecimalPlaces);
				$DisplayOverdue2 = locale_number_format($DetailTrans['overdue2'], $CurrDecimalPlaces);

				$DisplayDueDate = CalcDueDate($DisplayTranDate, $DetailTrans['dayinfollowingmonth'], $DetailTrans['daysbeforedue']);

				echo '<tr class="striped_row">
						<td class="centre">', _($DetailTrans['typename']), '</td>', // Should it be left (text field) ?
				'<td class="number">', $DetailTrans['transno'], '</td>
						<td class="centre">', $DisplayTranDate, '</td>
						<td class="centre">', $DisplayDueDate, '</td>
						<td class="number">', $DisplayBalance, '</td>
						<td class="number">', $DisplayCurrent, '</td>
						<td class="number" style="color:orange;">', $DisplayDue, '</td>
						<td class="number" style="color:red;">', $DisplayOverdue1, '</td>
						<td class="number" style="color:red;">', $DisplayOverdue2, '</td>
					</tr>';
			} //end while there are detail transactions to show
			
		} //has Due now or overdue
		
	} //end customer aged analysis while loop
	// Print totals of 'Overdue Customer Balances':
	echo '<tr class="striped_row">
			<td class="text"><b>', _('Totals'), '</b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="number"><b>', locale_number_format($TotBal, $CurrDecimalPlaces), '</b></td>
			<td class="number"><b>', locale_number_format($TotCurr, $CurrDecimalPlaces), '</b></td>
			<td class="number"><b>', locale_number_format($TotDue, $CurrDecimalPlaces), '</b></td>
			<td class="number" style="color:red;"><b>', locale_number_format($TotOD1, $CurrDecimalPlaces), '</b></td>
			<td class="number" style="color:red;"><b>', locale_number_format($TotOD2, $CurrDecimalPlaces), '</b></td>
		</tr>
		</tbody></table>';
} //DebtorSecurity
if (in_array($PayeeSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($PayeeSecurity)) {
	echo '<br />
		<h2>', _('Supplier Invoices Due within 1 Month'), '</h2>
		<table class="selection">
		<thead>
			<tr>
				<th>', _('Supplier'), '</th>
				<th>', _('Invoice Date'), '</th>
				<th>', _('Invoice'), '</th>
				<th>', _('Amount Due'), '</th>
				<th>', _('Due Date'), '</th>
			</tr>
		</thead><tbody>';
	$SupplierID = '';
	$TotalPayments = 0;
	$TotalAccumDiffOnExch = 0;
	$AccumBalance = 0;

	$Sql = "SELECT suppliers.supplierid,
					currencies.decimalplaces AS currdecimalplaces,
					SUM(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) AS balance
			FROM suppliers INNER JOIN paymentterms
			ON suppliers.paymentterms = paymentterms.termsindicator
			INNER JOIN supptrans
			ON suppliers.supplierid = supptrans.supplierno
			INNER JOIN systypes
			ON systypes.typeid = supptrans.type
			INNER JOIN currencies
			ON suppliers.currcode=currencies.currabrev
			WHERE supptrans.ovamount + supptrans.ovgst - supptrans.alloc !=0
			AND supptrans.hold=0
			GROUP BY suppliers.supplierid,
					currencies.decimalplaces
			HAVING SUM(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) <> 0
			ORDER BY suppliers.supplierid";
	$SuppliersResult = DB_query($Sql);

	while ($SuppliersToPay = DB_fetch_array($SuppliersResult)) {

		$CurrDecimalPlaces = $SuppliersToPay['currdecimalplaces'];

		$Sql = "SELECT suppliers.supplierid,
						suppliers.suppname,
						systypes.typename,
						paymentterms.terms,
						supptrans.suppreference,
						supptrans.trandate,
						supptrans.rate,
						supptrans.transno,
						supptrans.type,
						supptrans.duedate,
						(supptrans.ovamount + supptrans.ovgst - supptrans.alloc) AS balance,
						(supptrans.ovamount + supptrans.ovgst ) AS trantotal,
						supptrans.diffonexch,
						supptrans.id
				FROM suppliers
				INNER JOIN paymentterms ON suppliers.paymentterms = paymentterms.termsindicator
				INNER JOIN supptrans ON suppliers.supplierid = supptrans.supplierno
				INNER JOIN systypes ON systypes.typeid = supptrans.type
				WHERE supptrans.supplierno = '" . $SuppliersToPay['supplierid'] . "'
					AND supptrans.ovamount + supptrans.ovgst - supptrans.alloc !=0
					AND supptrans.duedate <='" . Date('Y-m-d', mktime(0, 0, 0, Date('n'), Date('j') + 30, date('Y'))) . "'
					AND supptrans.hold = 0
				ORDER BY supptrans.supplierno,
					supptrans.type,
					supptrans.transno";

		$TransResult = DB_query($Sql, '', '', false, false);
		if (DB_error_no() != 0) {
			prnMsg(_('The details of supplier invoices due could not be retrieved because') . ' - ' . DB_error_msg(), 'error');
			echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a>';
			if ($debug == 1) {
				echo '<br />' . _('The SQL that failed was') . ' ' . $Sql;
			}
			include ('includes/footer.php');
			exit;
		}

		unset($Allocs);
		$Allocs = array();
		$AllocCounter = 0;

		while ($DetailTrans = DB_fetch_array($TransResult)) {
			if ($DetailTrans['supplierid'] != $SupplierID) { /*Need to head up for a new suppliers details */
				$SupplierID = $DetailTrans['supplierid'];
				$SupplierName = $DetailTrans['suppname'];
				//$AccumBalance = 0;
				$AccumDiffOnExch = 0;
				echo '<tr class="striped_row">
						<td class="text" colspan="5"><b>', $DetailTrans['supplierid'], ' - ', $DetailTrans['suppname'], ' - ', $DetailTrans['terms'], '</b></td>
					</tr>';
			}

			$DisplayFormat = '';
			if ((time() - (60 * 60 * 24)) > strtotime($DetailTrans['duedate'])) {
				$DisplayFormat = ' style="color:red;"';
			}
			$DislayTranDate = ConvertSQLDate($DetailTrans['trandate']);
			$AccumBalance+= $DetailTrans['balance'];
			if ($DetailTrans['type'] == 20) { // If Purchase Invoice:
				echo '<tr class="striped_row">
						<td class="centre">', _($DetailTrans['typename']), '</td>
						<td class="centre">', $DislayTranDate, '</td>
						<td class="text"><a href="', $RootPath, '/Payments.php?&SupplierID=', $SupplierID, '&amp;Amount=', $DetailTrans['balance'], '&amp;BankTransRef=', $DetailTrans['suppreference'], '">', $DetailTrans['suppreference'], '</a></td>
						<td class="number"', $DisplayFormat, '>', locale_number_format($DetailTrans['balance'], $CurrDecimalPlaces), '</td>
						<td class="centre"', $DisplayFormat, '>', ConvertSQLDate($DetailTrans['duedate']), '</td>
					</tr>';
			} else { // If NOT Purchase Invoice (Creditors Payment):
				echo '<tr class="striped_row">
						<td class="centre">', _($DetailTrans['typename']), '</td>
						<td class="centre">', $DislayTranDate, '</td>
						<td class="text"><a href="', $RootPath, '/SupplierAllocations.php?AllocTrans=', $DetailTrans['id'], '">', $DetailTrans['suppreference'], '</a></td>
						<td class="number"', $DisplayFormat, '>', locale_number_format($DetailTrans['balance'], $CurrDecimalPlaces), '</td>
						<td class="centre"', $DisplayFormat, '>', ConvertSQLDate($DetailTrans['duedate']), '</td>
					</tr>';
			}
		} /*end while there are detail transactions to show */
	} /* end while there are suppliers to retrieve transactions for */

	echo '<tr class="striped_row">
			<td class="number">', _('Grand Total Payments Due'), '</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="number"><b>', locale_number_format($AccumBalance, $CurrDecimalPlaces), '</b></td>
			<td>&nbsp;</td>
		</tr>
		</tbody>
		</table>';
} //PayeeSecurity
if (in_array($CashSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($CashSecurity)) {
	include ('includes/GLPostings.inc');
	echo '<br />
		<h2>', _('Bank and Credit Card Balances'), '</h2>
		<table class="selection">
		<thead>
			<tr>
				<th class="ascending">', _('GL Account'), '</th>
				<th class="ascending">', _('Account Name'), '</th>
				<th class="ascending">', _('Balance'), '</th>
			</tr>
		</thead>
		<tbody>';

	$FirstPeriodSelected = GetPeriod(date($_SESSION['DefaultDateFormat']));
	$LastPeriodSelected = GetPeriod(date($_SESSION['DefaultDateFormat']));
	$SelectedPeriod = $LastPeriodSelected;

	$Sql = "SELECT bankaccounts.accountcode,
                    bankaccounts.bankaccountcode,
                    chartmaster.accountname,
                    bankaccountname
            FROM bankaccounts
            INNER JOIN chartmaster
            ON bankaccounts.accountcode = chartmaster.accountcode
            INNER JOIN bankaccountusers
            ON bankaccounts.accountcode=bankaccountusers.accountcode
            AND userid='" . $_SESSION['UserID'] . "'";

	$ErrMsg = _('The bank accounts set up could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the bank account details was') . '<br />' . $Sql;
	$Result1 = DB_query($Sql, $ErrMsg, $DbgMsg);

	while ($MyRow = DB_fetch_array($Result1)) {
		/*Is the account a balance sheet or a profit and loss account */
		$Result = DB_query("SELECT pandl
						FROM accountgroups
						INNER JOIN chartmaster ON accountgroups.groupname=chartmaster.group_
						WHERE chartmaster.accountcode='" . $MyRow['accountcode'] . "'");
		$PandLRow = DB_fetch_row($Result);
		if ($PandLRow[0] == 1) {
			$PandLAccount = True;
		} else {
			$PandLAccount = False; /*its a balance sheet account */
		}

		$Sql = "SELECT counterindex,
						type,
						typename,
						gltrans.typeno,
						trandate,
						narrative,
						amount,
						periodno,
						gltrans.tag,
						tagdescription
					FROM gltrans INNER JOIN systypes
					ON systypes.typeid=gltrans.type
					LEFT JOIN tags
					ON gltrans.tag = tags.tagref
					WHERE gltrans.account = '" . $MyRow['accountcode'] . "'
					AND posted=1
					AND periodno>='" . $FirstPeriodSelected . "'
					AND periodno<='" . $LastPeriodSelected . "'
					ORDER BY periodno, gltrans.trandate, counterindex";
		$TransResult = DB_query($Sql, $ErrMsg);
		if ($PandLAccount == True) {
			$RunningTotal = 0;
		} else { // added to fix bug with Brought Forward Balance always being zero
			$Sql = "SELECT bfwd,
						actual,
						period
					FROM chartdetails
					WHERE chartdetails.accountcode='" . $MyRow['accountcode'] . "'
					AND chartdetails.period='" . $FirstPeriodSelected . "'";

			$ErrMsg = _('The chart details for account') . ' ' . $MyRow['accountcode'] . ' ' . _('could not be retrieved');
			$ChartDetailsResult = DB_query($Sql, $ErrMsg);
			$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
			$RunningTotal = $ChartDetailRow['bfwd'];
		}
		$PeriodTotal = 0;
		$PeriodNo = - 9999;
		while ($MyRow2 = DB_fetch_array($TransResult)) {
			if ($MyRow2['periodno'] != $PeriodNo) {
				if ($PeriodNo != - 9999) { //ie its not the first time around
					/*Get the ChartDetails balance b/fwd and the actual movement in the account for the period as recorded in the chart details - need to ensure integrity of transactions to the chart detail movements. Also, for a balance sheet account it is the balance carried forward that is important, not just the transactions*/

					$Sql = "SELECT bfwd,
							actual,
							period
						FROM chartdetails
						WHERE chartdetails.accountcode='" . $MyRow['accountcode'] . "'
						AND chartdetails.period='" . $PeriodNo . "'";
					$ErrMsg = _('The chart details for account') . ' ' . $MyRow['accountcode'] . ' ' . _('could not be retrieved');
					$ChartDetailsResult = DB_query($Sql, $ErrMsg);
					$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
					if ($PeriodTotal < 0) { //its a credit balance b/fwd
						if ($PandLAccount == True) {
							$RunningTotal = 0;
						}
					} else { //its a debit balance b/fwd
						if ($PandLAccount == True) {
							$RunningTotal = 0;
						}
					}
				}
				$PeriodNo = $MyRow2['periodno'];
				$PeriodTotal = 0;
			}
			$RunningTotal+= $MyRow2['amount'];
			$PeriodTotal+= $MyRow2['amount'];
		}
		$DisplayBalance = locale_number_format(($RunningTotal), $_SESSION['CompanyRecord']['decimalplaces']);
		echo '<tr class="striped_row">
				<td class="text">', $MyRow['accountcode'], ' - ', $MyRow['accountname'], '</td>
				<td class="text">', $MyRow['bankaccountname'], '</td>
				<td class="number">', $DisplayBalance, '</td>
			</tr>';
	} //each bank account
	echo '</tbody>
		</table>';
} //CashSecurity
if (in_array($OrderSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($OrderSecurity)) {
	echo '<br />
		<h2>', _('Outstanding Orders'), '</h2>
		<table class="selection">
		<thead>', '<tr>
				<th>', _('View Order'), '</th>
				<th>', _('Customer'), '</th>
				<th>', _('Branch'), '</th>
				<th>', _('Cust Order'), ' #</th>
				<th>', _('Order Date'), '</th>
				<th>', _('Req Del Date'), '</th>
				<th>', _('Delivery To'), '</th>
				<th>', _('Order Total'), ' ', _('in'), ' ', $_SESSION['CompanyRecord']['currencydefault'], '</th>
			</tr>
		</thead><tbody>';

	$Sql = "SELECT salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverto,
						salesorders.deliverydate,
						salesorders.printedpackingslip,
						salesorders.poplaced,
						SUM(salesorderdetails.unitprice*salesorderdetails.quantity*(1-salesorderdetails.discountpercent)/currencies.rate) AS ordervalue
					FROM salesorders INNER JOIN salesorderdetails
						ON salesorders.orderno = salesorderdetails.orderno
						INNER JOIN debtorsmaster
						ON salesorders.debtorno = debtorsmaster.debtorno
						INNER JOIN custbranch
						ON debtorsmaster.debtorno = custbranch.debtorno
						AND salesorders.branchcode = custbranch.branchcode
						INNER JOIN currencies
						ON debtorsmaster.currcode = currencies.currabrev
					WHERE salesorderdetails.completed=0
					AND salesorders.quotation =0
					GROUP BY salesorders.orderno,
						debtorsmaster.name,
						custbranch.brname,
						salesorders.customerref,
						salesorders.orddate,
						salesorders.deliverto,
						salesorders.deliverydate,
						salesorders.printedpackingslip
					ORDER BY salesorders.orddate DESC, salesorders.orderno";
	$ErrMsg = _('No orders or quotations were returned by the SQL because');
	$SalesOrdersResult = DB_query($Sql, $ErrMsg);

	/*show a table of the orders returned by the SQL */
	if (DB_num_rows($SalesOrdersResult) > 0) {
		$OrdersTotal = 0;
		$FontColor = '';

		while ($MyRow = DB_fetch_array($SalesOrdersResult)) {
			$OrderDate = ConvertSQLDate($MyRow['orddate']);
			$FormatedDelDate = ConvertSQLDate($MyRow['deliverydate']);
			$FormatedOrderValue = locale_number_format($MyRow['ordervalue'], $_SESSION['CompanyRecord']['decimalplaces']);
			if (DateDiff(Date($_SESSION['DefaultDateFormat']), $OrderDate, 'd') > 5) {
				$FontColor = ' style="color:green; font-weight:bold"';
			}

			echo '<tr class="striped_row">
					<td class="number"><a href="', $RootPath, '/OrderDetails.php?OrderNumber=', $MyRow['orderno'], '" target="_blank">', $MyRow['orderno'], '</a></td>
					<td class="text"', $FontColor, '>', $MyRow['name'], '</td>
					<td class="text"', $FontColor, '>', $MyRow['brname'], '</td>
					<td class="number"', $FontColor, '>', $MyRow['customerref'], '</td>
					<td class="centre"', $FontColor, '>', $OrderDate, '</td>
					<td class="centre"', $FontColor, '>', $FormatedDelDate, '</td>
					<td class="text"', $FontColor, '>', html_entity_decode($MyRow['deliverto'], ENT_QUOTES, 'UTF-8'), '</td>
					<td class="number"', $FontColor, '>', $FormatedOrderValue, '</td>
				</tr>';
			$OrdersTotal+= $MyRow['ordervalue'];
		} // END while($MyRow=DB_fetch_array($SalesOrdersResult))
		echo '<tr>
					<td class="number" colspan="7"><b>', _('Total Order(s) Value in'), ' ', $_SESSION['CompanyRecord']['currencydefault'], ' :</b></td>
					<td class="number"><b>', locale_number_format($OrdersTotal, $_SESSION['CompanyRecord']['decimalplaces']), '</b></td>
				</tr>';
	} //rows > 0
	echo '</tbody></table>';
} //OrderSecurity
include ('includes/footer.php');
?>

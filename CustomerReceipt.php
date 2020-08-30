<?php
/* Entry of both customer receipts against accounts receivable and also general ledger or nominal receipts */

include('includes/DefineReceiptClass.php');
include('includes/session.php');


$Title = _('Receipt Entry');

if ($_GET['Type']=='GL') {
	$ViewTopic= 'GeneralLedger';
	$BookMark = 'GLReceipts';
} else {
	$ViewTopic= 'ARTransactions';
	$BookMark = 'CustomerReceipts';
}

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
if (empty($_GET['identifier'])) {
	$identifier = date('U');
} else {
	$identifier = $_GET['identifier'];
}


$msg='';

if (isset($_GET['NewReceipt'])){
	unset($_SESSION['ReceiptBatch' . $identifier]->Items);
	unset($_SESSION['ReceiptBatch' . $identifier]);
	unset($_SESSION['CustomerRecord' . $identifier]);
}

if (isset($_POST['Cancel'])) {
	$Cancel=1;
}

if (isset($_GET['Type']) AND $_GET['Type']=='GL') {
	$_POST['GLEntry']=1;
}

if ((isset($_POST['BatchInput'])
	AND $_POST['BankAccount']=='')
	OR (isset($_POST['Process'])
	AND $_POST['BankAccount']=='')) {

	echo '<br />';
	prnMsg(_('A bank account must be selected for this receipt'), 'warn');
	$BankAccountEmpty=true;
} else if(isset($_GET['NewReceipt'])) {
	$BankAccountEmpty=true;
} else {
	$BankAccountEmpty=false;
}

$Errors = array();

if (!isset($_GET['Delete']) AND isset($_SESSION['ReceiptBatch' . $identifier])){
	//always process a header update unless deleting an item

	include('includes/GetPaymentMethods.php');

	$_SESSION['ReceiptBatch' . $identifier]->Account = $_POST['BankAccount'];
	/*Get the bank account currency and set that too */

	$SQL = "SELECT bankaccountname,
					currcode,
					decimalplaces
			FROM bankaccounts
			INNER JOIN currencies
			ON bankaccounts.currcode=currencies.currabrev
			WHERE accountcode='" . $_POST['BankAccount']."'";

	$ErrMsg =_('The bank account name cannot be retrieved because');
	$result= DB_query($SQL,$ErrMsg);

	if (DB_num_rows($result)==1){
		$myrow = DB_fetch_array($result);
		$_SESSION['ReceiptBatch' . $identifier]->BankAccountName = $myrow['bankaccountname'];
		$_SESSION['ReceiptBatch' . $identifier]->AccountCurrency=$myrow['currcode'];
		$_SESSION['ReceiptBatch' . $identifier]->CurrDecimalPlaces=$myrow['decimalplaces'];
		unset($result);
	} elseif (DB_num_rows($result)==0 AND !$BankAccountEmpty){
		prnMsg( _('The bank account number') . ' ' . $_POST['BankAccount'] . ' ' . _('is not set up as a bank account'),'error');
		include ('includes/footer.php');
		exit;
	}

	if (!Is_Date($_POST['DateBanked'])){
		$_POST['DateBanked'] = Date($_SESSION['DefaultDateFormat']);
	}
	$_SESSION['ReceiptBatch' . $identifier]->DateBanked = $_POST['DateBanked'];
	if (isset($_POST['ExRate']) AND $_POST['ExRate']!=''){
		if (is_numeric(filter_number_format($_POST['ExRate']))){
			$_SESSION['ReceiptBatch' . $identifier]->ExRate = filter_number_format($_POST['ExRate']);
		} else {
			prnMsg(_('The exchange rate entered should be numeric'),'warn');
		}
	}
	if (isset($_POST['FunctionalExRate']) AND $_POST['FunctionalExRate']!=''){
		if (is_numeric(filter_number_format($_POST['FunctionalExRate']))){
			$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate=filter_number_format($_POST['FunctionalExRate']); //ex rate between receipt currency and account currency
		} else {
			prnMsg(_('The functional exchange rate entered should be numeric'),'warn');
		}
	}
	if (!isset($_POST['ReceiptType'])) {
		$_POST['ReceiptType'] = '';
	}
	$_SESSION['ReceiptBatch' . $identifier]->ReceiptType = $_POST['ReceiptType'];

	if (!isset($_POST['Currency'])){
		$_POST['Currency']=$_SESSION['CompanyRecord']['currencydefault'];
	}

	if ($_SESSION['ReceiptBatch' . $identifier]->Currency!=$_POST['Currency']){

		$_SESSION['ReceiptBatch' . $identifier]->Currency=$_POST['Currency']; //receipt currency
		/*Now customer receipts entered using the previous currency need to be ditched
		and a warning message displayed if there were some customer receipted entered */
		if (count($_SESSION['ReceiptBatch' . $identifier]->Items)>0){
			unset($_SESSION['ReceiptBatch' . $identifier]->Items);
			prnMsg(_('Changing the currency of the receipt means that existing entries need to be re-done - only customers trading in the selected currency can be selected'),'warn');
		}

	}

	if ($_SESSION['ReceiptBatch' . $identifier]->AccountCurrency==$_SESSION['CompanyRecord']['currencydefault']){
		$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate = 1;
		$SuggestedFunctionalExRate =1;
	} elseif (!$BankAccountEmpty) {
		/*To illustrate the rates required
			Take an example functional currency NZD receipt in USD from an AUD bank account
			1 NZD = 0.80 USD
			1 NZD = 0.90 AUD
			The FunctionalExRate = 0.90 - the rate between the functional currency and the bank account currency
			The receipt ex rate is the rate at which one can sell the received currency and purchase the bank account currency
			or 0.8/0.9 = 0.88889
		*/

		/*Get suggested FunctionalExRate between the bank account currency and the home (functional) currency */
		$result = DB_query("SELECT rate, decimalplaces FROM currencies WHERE currabrev='" . $_SESSION['ReceiptBatch' . $identifier]->AccountCurrency . "'");
		$myrow = DB_fetch_array($result);
		$SuggestedFunctionalExRate = $myrow['rate'];
		$_SESSION['ReceiptBatch' . $identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];

	} //end else account currency != functional currency

	if ($_POST['Currency']==$_SESSION['ReceiptBatch' . $identifier]->AccountCurrency){
		$_SESSION['ReceiptBatch' . $identifier]->ExRate = 1; //ex rate between receipt currency and account currency
		$SuggestedExRate=1;
	} elseif(isset($_POST['Currency'])) {
		/*Get the exchange rate between the functional currency and the receipt currency*/
		$result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['ReceiptBatch' . $identifier]->Currency . "'");
		$myrow = DB_fetch_array($result);
		$TableExRate = $myrow['rate']; //this is the rate of exchange between the functional currency and the receipt currency
		/*Calculate cross rate to suggest appropriate exchange rate between receipt currency and account currency */
		$SuggestedExRate = $TableExRate/$SuggestedFunctionalExRate;
	}

	$_SESSION['ReceiptBatch' . $identifier]->BankTransRef = $_POST['BankTransRef'];
	$_SESSION['ReceiptBatch' . $identifier]->Narrative = $_POST['BatchNarrative'];

} elseif (isset($_GET['Delete'])) {
	/* User hit delete the receipt entry from the batch */
	$_SESSION['ReceiptBatch' . $identifier]->remove_receipt_item($_GET['Delete']);
} else { //it must be a new receipt batch
	$_SESSION['ReceiptBatch' . $identifier] = new Receipt_Batch;
}


if (isset($_POST['Process'])){ //user hit submit a new entry to the receipt batch

	if (!isset($_POST['GLCode'])) {
		$_POST['GLCode']='';
	}
	if (!isset($_POST['tag'])) {
		$_POST['tag']='';
	}
	if (!isset($_POST['CustomerID'])) {
		$_POST['CustomerID']='';
	}
	if (!isset($_POST['CustomerName'])) {
		$_POST['CustomerName']='';
	}
	if ($_POST['Discount']==0 AND $ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['percentdiscount']>0){
		if (isset($_GET['Type']) AND $_GET['Type'] == 'Customer') {
			$_POST['Discount'] = $_POST['Amount']*$ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['percentdiscount'];
		}
	}

	if ($_POST['GLCode'] == '' AND $_GET['Type']=='GL') {
		prnMsg( _('No General Ledger code has been chosen') . ' - ' . _('so this GL analysis item could not be added'),'warn');

	} else {
		$AllowThisPosting = true;
 		if ($_SESSION['ProhibitJournalsToControlAccounts'] == 1) {
 			if ($_SESSION['CompanyRecord']['gllink_debtors'] == '1' AND $_POST['GLCode'] == $_SESSION['CompanyRecord']['debtorsact']) {
 				prnMsg(_('Payments involving the debtors control account cannot be entered. The general ledger debtors ledger (AR) integration is enabled so control accounts are automatically maintained. This setting can be disabled in System Configuration'), 'warn');
 				$AllowThisPosting = false;
 			}
 			if ($_SESSION['CompanyRecord']['gllink_creditors'] == '1' AND
				($_POST['GLCode'] == $_SESSION['CompanyRecord']['creditorsact'] OR $_POST['GLCode'] == $_SESSION['CompanyRecord']['grnact'])) {
 				prnMsg(_('Payments involving the creditors control account or the GRN suspense account cannot be entered. The general ledger creditors ledger (AP) integration is enabled so control accounts are automatically maintained. This setting can be disabled in System Configuration'), 'warn');
 				$AllowThisPosting = false;
 			}
 			if ($_POST['GLCode'] == $_SESSION['CompanyRecord']['retainedearnings']) {
 				prnMsg(_('Payments involving the retained earnings control account cannot be entered. This account is automtically maintained.'), 'warn');
 				$AllowThisPosting = false;
 			}
 		}
 		if ($AllowThisPosting) {
 			$_SESSION['ReceiptBatch' . $identifier]->add_to_batch(filter_number_format($_POST['Amount']),
													$_POST['CustomerID'],
													filter_number_format($_POST['Discount']),
													$_POST['Narrative'],
													$_POST['GLCode'],
													$_POST['PayeeBankDetail'],
													$_POST['CustomerName'],
													$_POST['tag']);
			/*Make sure the same receipt is not double processed by a page refresh */
			$Cancel = 1;
		}
	}
}

if (isset($Cancel)){
	unset($_SESSION['CustomerRecord' . $identifier]);
	unset($_POST['CustomerID']);
	unset($_POST['CustomerName']);
	unset($_POST['Amount']);
	unset($_POST['Discount']);
	unset($_POST['Narrative']);
	unset($_POST['PayeeBankDetail']);
}


if (isset($_POST['CommitBatch'])){

 /* once all receipts items entered, process all the data in the
  session cookie into the DB creating a single banktrans for the whole amount
  of all receipts in the batch and DebtorTrans records for each receipt item
  all DebtorTrans will refer to a single banktrans. A GL entry is created for
  each GL receipt entry and one for the debtors entry and one for the bank
  account debit

  NB allocations against debtor receipts are a separate exercice

  first off run through the array of receipt items $_SESSION['ReceiptBatch']->Items and
  if GL integrated then create GL Entries for the GL Receipt items
  and add up the non-GL ones for posting to debtors later,
  also add the total discount total receipts*/

	$PeriodNo = GetPeriod($_SESSION['ReceiptBatch' . $identifier]->DateBanked);

	if ($_SESSION['CompanyRecord']==0){
		prnMsg(_('The company has not yet been set up properly') . ' - ' . _('this information is needed to process the batch') . '. ' . _('Processing has been cancelled'),'error');
		include('includes/footer.php');
		exit;
	}

	/*Make an array of the defined bank accounts */
	$SQL = "SELECT accountcode FROM bankaccounts";
	$result = DB_query($SQL);
	$BankAccounts = array();
	$i=0;
	while ($Act = DB_fetch_row($result)){
		$BankAccounts[$i]= $Act[0];
		$i++;
	}

	/*Start a transaction to do the whole lot inside */
	$result = DB_Txn_Begin();
	$_SESSION['ReceiptBatch' . $identifier]->BatchNo = GetNextTransNo(12);


	$BatchReceiptsTotal = 0; //in functional currency
	$BatchDiscount = 0; //in functional currency
	$BatchDebtorTotal = 0; //in functional currency
	$CustomerReceiptCounter=1; //Count lines of customer receipts in this batch

	echo '<br />
		<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
			'/images/money_add.png" title="',// Icon image.
			_('Summary of Receipt Batch'), '" /> ',// Icon title.
			_('Summary of Receipt Batch'), '</p>',// Page title.
		'<br />
		<table class="selection">
		<thead>
			<tr>
				<th>', _('Batch Number'), '</th>
				<th>', _('Date Banked'), '</th>
				<th>', _('Customer Name'), '</th>
				<th class="text">', _('GL Code'), '</th>
				<th class="number">', _('Amount of Receipt'), '</th>';
	if(isset($ReceiptItem) AND $ReceiptItem->GLCode =='') {
		echo '<th class="noprint">&nbsp;</th>';
	}
	echo '</tr>
		</thead><tbody>';

	foreach ($_SESSION['ReceiptBatch' . $identifier]->Items as $ReceiptItem) {

		$SQL = "SELECT accountname FROM chartmaster WHERE accountcode='" . $ReceiptItem->GLCode . "'";
		$Result=DB_query($SQL);
		$myrow=DB_fetch_array($Result);

		echo '<tr class="striped_row">
			<td>' . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . '</td>
			<td>' . $_SESSION['ReceiptBatch' . $identifier]->DateBanked . '</td>
			<td>' . $ReceiptItem->CustomerName . '</td>
			<td class="text">' . $ReceiptItem->GLCode . ' - ' . $myrow['accountname'] . '</td>
			<td class="number">' . locale_number_format($ReceiptItem->Amount/$_SESSION['ReceiptBatch' . $identifier]->ExRate/$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate,$_SESSION['ReceiptBatch' . $identifier]->CurrDecimalPlaces)  . '</td>';

		if ($ReceiptItem->GLCode ==''){
			echo '<td class="noprint"><a target="_blank" href="', $RootPath, '/PDFReceipt.php?BatchNumber=', $_SESSION['ReceiptBatch' . $identifier]->BatchNo, '&ReceiptNumber=', $CustomerReceiptCounter, '">', _('Print a Customer Receipt'), '</a></td></tr>';
			$CustomerReceiptCounter += 1;
		}

		if ($ReceiptItem->GLCode !=''){ //so its a GL receipt
			if ($_SESSION['CompanyRecord']['gllink_debtors']==1){ /* then enter a GLTrans record */
				 $SQL = "INSERT INTO gltrans (type,
								 			typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount,
											tag)
					VALUES (
						12,
						'" . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . "',
						'" . FormatDateForSQL($_SESSION['ReceiptBatch' . $identifier]->DateBanked) . "',
						'" . $PeriodNo . "',
						'" . $ReceiptItem->GLCode . "',
						'" . $ReceiptItem->Narrative . "',
						'" . -($ReceiptItem->Amount/$_SESSION['ReceiptBatch' . $identifier]->ExRate/$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate) . "',
						'" . $ReceiptItem->tag . "'" . "
					)";
				$ErrMsg = _('Cannot insert a GL entry for the receipt because');
				$DbgMsg = _('The SQL that failed to insert the receipt GL entry was');
				$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
			}

			/*check to see if this is a GL posting to another bank account (or the same one)
			if it is then a matching payment needs to be created for this account too */

			if (in_array($ReceiptItem->GLCode, $BankAccounts)) {

			/*Need to deal with the case where the payment from one bank account could be to a bank account in another currency */

				/*Get the currency and rate of the bank account transferring to*/
				$SQL = "SELECT currcode, rate
							FROM bankaccounts INNER JOIN currencies
							ON bankaccounts.currcode = currencies.currabrev
							WHERE accountcode='" . $ReceiptItem->GLCode."'";
				$TrfFromAccountResult = DB_query($SQL);
				$TrfFromBankRow = DB_fetch_array($TrfFromAccountResult) ;
				$TrfFromBankCurrCode = $TrfFromBankRow['currcode'];
				$TrfFromBankExRate = $TrfFromBankRow['rate'];

				if ($_SESSION['ReceiptBatch' . $identifier]->AccountCurrency == $TrfFromBankCurrCode){
					/*Make sure to use the same rate if the transfer is between two bank accounts in the same currency */
					$TrfFromBankExRate = $_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate;
				}

				/*Consider an example - had to be currencies I am familar with sorry so I could figure it out!!
					 functional currency NZD
					 bank account in AUD - 1 NZD = 0.90 AUD (FunctionalExRate)
					 receiving USD - 1 AUD = 0.85 USD  (ExRate)
					 from a bank account in EUR - 1 NZD = 0.52 EUR

					 oh yeah - now we are getting tricky!
					 Lets say we received USD 100 to the AUD bank account from the EUR bank account

					 To get the ExRate for the bank account we are transferring money from
					 we need to use the cross rate between the NZD-AUD/NZD-EUR
					 and apply this to the

					 the receipt record will read
					 exrate = 0.85 (1 AUD = USD 0.85)
					 amount = 100 (USD)
					 functionalexrate = 0.90 (1 NZD = AUD 0.90)

					 the payment record will read

					 amount 100 (USD)
					 exrate    (1 EUR =  (0.85 x 0.90)/0.52 USD  ~ 1.47
					  					(ExRate x FunctionalExRate) / USD Functional ExRate
					 Check this is 1 EUR = 1.47 USD
					 functionalexrate =  (1NZD = EUR 0.52)

				*/

				$PaymentTransNo = GetNextTransNo( 1 );
				$SQL="INSERT INTO banktrans (transno,
											type,
											bankact,
											ref,
											exrate,
											functionalexrate,
											transdate,
											banktranstype,
											amount,
											currcode)
						VALUES (
							'" . $PaymentTransNo . "',
							1,
							'" . $ReceiptItem->GLCode . "',
							'" . _('Act Transfer') ." - " . $ReceiptItem->Narrative . "',
							'" . (($_SESSION['ReceiptBatch' . $identifier]->ExRate * $_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate)/$TrfFromBankExRate). "',
							'" . $TrfFromBankExRate . "',
							'" . FormatDateForSQL($_SESSION['ReceiptBatch' . $identifier]->DateBanked) . "',
							'" . $ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['paymentname'] . "',
							'" . -$ReceiptItem->Amount . "',
							'" . $_SESSION['ReceiptBatch' . $identifier]->Currency . "'
						)";

				$DbgMsg = _('The SQL that failed to insert the bank transaction was');
				$ErrMsg = _('Cannot insert a bank transaction using the SQL');
				$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
			} //end if an item is a transfer between bank accounts

		} else { //its not a GL item - its a customer receipt then
			/*Accumulate the total debtors credit including discount */
			$BatchDebtorTotal += (($ReceiptItem->Discount + $ReceiptItem->Amount)/$_SESSION['ReceiptBatch' . $identifier]->ExRate/$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate);
			/*Create a DebtorTrans entry for each customer deposit */

			/*The rate of exchange required here is the rate between the functional (home) currency and the customer receipt currency
			 * We have the exchange rate between the bank account and the functional home currency  $_SESSION['ReceiptBatch']->ExRate
			 * and the exchange rate betwen the currency being paid and the bank account */

			$SQL = "INSERT INTO debtortrans (transno,
											type,
											debtorno,
											branchcode,
											order_,
											trandate,
											inputdate,
											prd,
											reference,
											tpe,
											rate,
											ovamount,
											ovdiscount,
											invtext,
											salesperson)
					VALUES (
						'" . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . "',
						12,
						'" . $ReceiptItem->Customer . "',
						'',
						'" . $ReceiptItem->ID . "',
						'" . FormatDateForSQL($_SESSION['ReceiptBatch' . $identifier]->DateBanked) . "',
						'" . date('Y-m-d H-i-s') . "',
						'" . $PeriodNo . "',
						'" . $ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['paymentname']  . ' ' . $ReceiptItem->PayeeBankDetail . "',
						'',
						'" . ($_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate*$_SESSION['ReceiptBatch' . $identifier]->ExRate) . "',
						'" . -$ReceiptItem->Amount . "',
						'" . -$ReceiptItem->Discount . "',
						'" . $ReceiptItem->Narrative. "',
						'" . $_SESSION['SalesmanLogin']. "'
					)";
			$DbgMsg = _('The SQL that failed to insert the customer receipt transaction was');
			$ErrMsg = _('Cannot insert a receipt transaction against the customer because') ;
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			$SQL = "UPDATE debtorsmaster
						SET lastpaiddate = '" . FormatDateForSQL($_SESSION['ReceiptBatch' . $identifier]->DateBanked) . "',
						lastpaid='" . $ReceiptItem->Amount ."'
					WHERE debtorsmaster.debtorno='" . $ReceiptItem->Customer . "'";

			$DbgMsg = _('The SQL that failed to update the date of the last payment received was');
			$ErrMsg = _('Cannot update the customer record for the date of the last payment received because');
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		} //end of if its a customer receipt
		$BatchDiscount += ($ReceiptItem->Discount/$_SESSION['ReceiptBatch' . $identifier]->ExRate/$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate);
		$BatchReceiptsTotal += ($ReceiptItem->Amount/$_SESSION['ReceiptBatch' . $identifier]->ExRate/$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate);

	} /*end foreach $ReceiptItem */
	echo '</tbody></table>';

	/*now enter the BankTrans entry */

	$SQL="INSERT INTO banktrans (type,
								transno,
								bankact,
								ref,
								exrate,
								functionalexrate,
								transdate,
								banktranstype,
								amount,
								currcode)
		VALUES (
			12,
			'" . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . "',
			'" . $_SESSION['ReceiptBatch' . $identifier]->Account . "',
			'" . $_SESSION['ReceiptBatch' . $identifier]->BankTransRef . "',
			'" . $_SESSION['ReceiptBatch' . $identifier]->ExRate . "',
			'" . $_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate . "',
			'" . FormatDateForSQL($_SESSION['ReceiptBatch' . $identifier]->DateBanked) . "',
			'" . $ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['paymentname'] . "',
			'" . ($BatchReceiptsTotal * $_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate * $_SESSION['ReceiptBatch' . $identifier]->ExRate) . "',
			'" . $_SESSION['ReceiptBatch' . $identifier]->Currency . "'
		)";
	$DbgMsg = _('The SQL that failed to insert the bank account transaction was');
	$ErrMsg = _('Cannot insert a bank transaction');
	$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);


	if ($_SESSION['CompanyRecord']['gllink_debtors']==1){ /* then enter GLTrans records for discount, bank and debtors */

		if ($BatchReceiptsTotal!=0){
			/* Bank account entry first */
			$SQL="INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
				VALUES (
					12,
					'" . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . "',
					'" . FormatDateForSQL($_SESSION['ReceiptBatch' . $identifier]->DateBanked) . "',
					'" . $PeriodNo . "',
					'" . $_SESSION['ReceiptBatch' . $identifier]->Account . "',
					'" . $_SESSION['ReceiptBatch' . $identifier]->Narrative . "',
					'" . $BatchReceiptsTotal . "'
				)";
			$DbgMsg = _('The SQL that failed to insert the GL transaction fro the bank account debit was');
			$ErrMsg = _('Cannot insert a GL transaction for the bank account debit');
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);


		}
		if ($BatchDebtorTotal!=0){
			/* Now Credit Debtors account with receipts + discounts */
			$SQL="INSERT INTO gltrans ( type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
						VALUES (
							12,
							'" . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . "',
							'" . FormatDateForSQL($_SESSION['ReceiptBatch' . $identifier]->DateBanked) . "',
							'" . $PeriodNo . "',
							'". $_SESSION['CompanyRecord']['debtorsact'] . "',
							'" . $_SESSION['ReceiptBatch' . $identifier]->Narrative . "',
							'" . -$BatchDebtorTotal . "'
							)";
			$DbgMsg = _('The SQL that failed to insert the GL transaction for the debtors account credit was');
			$ErrMsg = _('Cannot insert a GL transaction for the debtors account credit');
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		} //end if there are some customer deposits in this batch

		if ($BatchDiscount!=0){
			/* Now Debit Discount account with discounts allowed*/
			$SQL="INSERT INTO gltrans ( type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
						VALUES (
								12,
								'" . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . "',
								'" . FormatDateForSQL($_SESSION['ReceiptBatch' . $identifier]->DateBanked) . "',
								'" . $PeriodNo . "',
								'" . $_SESSION['CompanyRecord']['pytdiscountact'] . "',
								'" . $_SESSION['ReceiptBatch' . $identifier]->Narrative . "',
								'" . $BatchDiscount . "'
							)";
			$DbgMsg = _('The SQL that failed to insert the GL transaction for the payment discount debit was');
			$ErrMsg = _('Cannot insert a GL transaction for the payment discount debit');
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		} //end if there is some discount

	} //end if there is GL work to be done - ie config is to link to GL
	EnsureGLEntriesBalance(12,$_SESSION['ReceiptBatch' . $identifier]->BatchNo);

	$ErrMsg = _('Cannot commit the changes');
	$DbgMsg = _('The SQL that failed was');
	$result = DB_Txn_Commit();
	echo '<br />';
	prnMsg( _('Receipt batch') . ' ' . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . ' ' . _('has been successfully entered into the database'),'success');

	echo '<div class="centre noprint">',
		'<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/printer.png" title="' . _('Print') . '" alt="" />' . ' ' . '<a href="' . $RootPath . '/PDFBankingSummary.php?BatchNo=' . $_SESSION['ReceiptBatch' . $identifier]->BatchNo . '">' . _('Print PDF Batch Summary') . '</a></p>';
	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/allocation.png" title="' . _('Allocate') . '" alt="" />' . ' ' . '<a href="' . $RootPath . '/CustomerAllocations.php">' . _('Allocate Receipts') . '</a></p>';
	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/transactions.png" title="', _('Enter Receipts'), '" /> ', '<a href="', $RootPath, '/CustomerReceipt.php?NewReceipt=Yes&Type=', urlencode($_GET['Type']), '">', _('Enter Receipts'), '</a></p>',
		'</div>';

	unset($_SESSION['ReceiptBatch' . $identifier]);
	include('includes/footer.php');
	exit;

} /* End of commit batch */

if (isset($_POST['Search'])){
/*Will only be true if clicked to search for a customer code */

	if ($_POST['Keywords'] AND $_POST['CustCode']) {
		$msg=_('Customer name keywords have been used in preference to the customer code extract entered');
	}
	if ($_POST['Keywords']==''
		AND $_POST['CustCode']==''
		AND $_POST['CustInvNo']=='') {
			$SQL = "SELECT debtorsmaster.debtorno,
						debtorsmaster.name
					FROM debtorsmaster
					WHERE debtorsmaster.currcode= '" . $_SESSION['ReceiptBatch' . $identifier]->Currency . "'";
	} else {
		if (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			$SQL = "SELECT debtorsmaster.debtorno,
						debtorsmaster.name
					FROM debtorsmaster
					WHERE debtorsmaster.name " . LIKE . " '". $SearchString . "'
					AND debtorsmaster.currcode= '" . $_SESSION['ReceiptBatch' . $identifier]->Currency . "'";

		} elseif (mb_strlen($_POST['CustCode'])>0){
			$SQL = "SELECT debtorsmaster.debtorno,
						debtorsmaster.name
					FROM debtorsmaster
					WHERE debtorsmaster.debtorno " . LIKE . " '%" . $_POST['CustCode'] . "%'
					AND debtorsmaster.currcode= '" . $_SESSION['ReceiptBatch' . $identifier]->Currency . "'";
		} elseif (mb_strlen($_POST['CustInvNo'])>0){
			$SQL = "SELECT debtortrans.debtorno,
						debtorsmaster.name
					FROM debtorsmaster LEFT JOIN debtortrans
					ON debtorsmaster.debtorno=debtortrans.debtorno
					WHERE debtortrans.transno " . LIKE . " '%" . $_POST['CustInvNo'] . "%'
					AND debtorsmaster.currcode= '" . $_SESSION['ReceiptBatch' . $identifier]->Currency . "'";
		}
	}
		if ($_SESSION['SalesmanLogin'] != '') {
			$SQL .= " AND EXISTS (
						SELECT *
						FROM 	custbranch
						WHERE 	custbranch.debtorno = debtorsmaster.debtorno
							AND custbranch.salesman='" . $_SESSION['SalesmanLogin'] . "')";
		}

		$CustomerSearchResult = DB_query($SQL,'','',false,false);
		if (DB_error_no() !=0) {
			prnMsg(_('The searched customer records requested cannot be retrieved because') . ' - ' . DB_error_msg(),'error');
			if ($debug==1){
				prnMsg(_('SQL used to retrieve the customer details was') . '<br />' . $sql,'error');
			}
		} elseif (DB_num_rows($CustomerSearchResult)==1){
			$myrow=DB_fetch_array($CustomerSearchResult);
			$Select = $myrow['debtorno'];
			unset($CustomerSearchResult);
		} elseif (DB_num_rows($CustomerSearchResult)==0){
			prnMsg( _('No customer records contain the selected text') . ' - ' . _('please alter your search criteria and try again'),'info');
		}

	 //one of keywords or custcode was more than a zero length string
} //end of if search

if (isset($_POST['Select'])){
	$Select = $_POST['Select'];
}

if (isset($Select)) {
/*will only be true if a customer has just been selected by clicking on the customer or only one
customer record returned by the search - this record is then auto selected */

	$_POST['CustomerID']=$Select;
	/*need to get currency sales type - payment discount percent and GL code
	as well as payment terms and credit status and hold the lot as session variables
	the receipt held entirely as session variables until the button clicked to process*/


	if (isset($_SESSION['CustomerRecord' . $identifier])){
	   unset($_SESSION['CustomerRecord' . $identifier]);
	}

	$SQL = "SELECT debtorsmaster.name,
				debtorsmaster.pymtdiscount,
				debtorsmaster.currcode,
				currencies.currency,
				currencies.rate,
				currencies.decimalplaces AS currdecimalplaces,
				paymentterms.terms,
				debtorsmaster.creditlimit,
				holdreasons.dissallowinvoices,
				holdreasons.reasondescription,
				SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc) AS balance,
				SUM(CASE WHEN paymentterms.daysbeforedue > 0  THEN
					CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) >= paymentterms.daysbeforedue  THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
				ELSE
					CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= 0 THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
				END) AS due,
				SUM(CASE WHEN paymentterms.daysbeforedue > 0 THEN
					CASE WHEN TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) > paymentterms.daysbeforedue	AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays1'] . ") THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight - debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
				ELSE
					CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays1'] . " THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
				END) AS overdue1,
				SUM(CASE WHEN paymentterms.daysbeforedue > 0 THEN
					CASE WHEN TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays2'] . ") THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
				ELSE
					CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays2'] . " THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
				END) AS overdue2
			FROM debtorsmaster INNER JOIN paymentterms
			ON debtorsmaster.paymentterms = paymentterms.termsindicator
			INNER JOIN holdreasons
			ON debtorsmaster.holdreason = holdreasons.reasoncode
			INNER JOIN currencies
			ON debtorsmaster.currcode = currencies.currabrev
			INNER JOIN debtortrans
			ON debtorsmaster.debtorno = debtortrans.debtorno
			WHERE debtorsmaster.debtorno = '" . $_POST['CustomerID'] . "'";
	if ($_SESSION['SalesmanLogin'] != '') {
		$SQL .= " AND debtortrans.salesperson='" . $_SESSION['SalesmanLogin'] . "'";
	}
	$SQL .= " GROUP BY debtorsmaster.name,
				debtorsmaster.pymtdiscount,
				debtorsmaster.currcode,
				currencies.currency,
				currencies.rate,
				currencies.decimalplaces,
				paymentterms.terms,
				debtorsmaster.creditlimit,
				paymentterms.daysbeforedue,
				paymentterms.dayinfollowingmonth,
				debtorsmaster.creditlimit,
				holdreasons.dissallowinvoices,
				holdreasons.reasondescription";


	$ErrMsg = _('The customer details could not be retrieved because');
	$DbgMsg = _('The SQL that failed was');
	$CustomerResult = DB_query($SQL,$ErrMsg, $DbgMsg);

	if (DB_num_rows($CustomerResult)==0){

		/*Because there is no balance - so just retrieve the header information about the customer - the choice is do one query to get the balance and transactions for those customers who have a balance and two queries for those who don't have a balance OR always do two queries - I opted for the former */

		$NIL_BALANCE = True;

		$SQL = "SELECT debtorsmaster.name,
						debtorsmaster.pymtdiscount,
						currencies.currency,
						currencies.rate,
						currencies.decimalplaces AS currdecimalplaces,
						paymentterms.terms,
						debtorsmaster.creditlimit,
						debtorsmaster.currcode,
						holdreasons.dissallowinvoices,
						holdreasons.reasondescription
					FROM debtorsmaster INNER JOIN paymentterms
					ON debtorsmaster.paymentterms = paymentterms.termsindicator
					INNER JOIN holdreasons
					ON debtorsmaster.holdreason = holdreasons.reasoncode
					INNER JOIN currencies
					ON debtorsmaster.currcode = currencies.currabrev
					WHERE debtorsmaster.debtorno = '" . $_POST['CustomerID'] . "'";

		$ErrMsg = _('The customer details could not be retrieved because');
		$DbgMsg = _('The SQL that failed was');
		$CustomerResult = DB_query($SQL,$ErrMsg, $DbgMsg);

	} else {
		$NIL_BALANCE = False;
	}

	$_SESSION['CustomerRecord' . $identifier] = DB_fetch_array($CustomerResult);

	if ($NIL_BALANCE==True){
		$_SESSION['CustomerRecord' . $identifier]['balance']=0;
		$_SESSION['CustomerRecord' . $identifier]['due']=0;
		$_SESSION['CustomerRecord' . $identifier]['overdue1']=0;
		$_SESSION['CustomerRecord' . $identifier]['overdue2']=0;
	}
} /*end of if customer has just been selected  all info required read into $_SESSION['CustomerRecord']*/

/*set up the form whatever */


echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Type=' . urlencode($_GET['Type']) . '&amp;identifier=' . urlencode($identifier) . '" method="post" id="form1">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/*show the batch header details and the entries in the batch so far */

$SQL = "SELECT bankaccountname,
				bankaccounts.accountcode,
				bankaccounts.currcode
		FROM bankaccounts
		INNER JOIN chartmaster
			ON bankaccounts.accountcode=chartmaster.accountcode
		INNER JOIN bankaccountusers
			ON bankaccounts.accountcode=bankaccountusers.accountcode
		WHERE bankaccountusers.userid = '" . $_SESSION['UserID'] ."'
		ORDER BY bankaccountname";

$ErrMsg = _('The bank accounts could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve the bank accounts was');
$AccountsResults = DB_query($SQL,$ErrMsg,$DbgMsg);

if (isset($_POST['GLEntry'])) {
	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/transactions.png" title="' . _('Bank Account Receipts Entry') . '" alt="" />' . ' ' . _('Bank Account Receipts Entry') . '</p>';
} else {
	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/transactions.png" title="' . _('Enter Receipt') . '" alt="" />' . ' ' . _('Enter Customer Receipt') . '</p>';
	echo '<div class="page_help_text">' . _('To enter a payment TO a customer (ie. to pay out a credit note), enter a negative payment amount.') . '</div>';
}
echo '<br />
	<table class="selection">
	<tr>
		<td>' . _('Bank Account') . ':</td>
		 <td><select tabindex="1" autofocus="autofocus" name="BankAccount" onchange="ReloadForm(form1.BatchInput)">';

if (DB_num_rows($AccountsResults)==0){
	echo '</select></td>
		</tr>
		</table>
		<p />';
	prnMsg(_('Bank Accounts have not yet been defined') . '. ' . _('You must first') . ' ' . '<a href="' . $RootPath . '/BankAccounts.php">' . _('define the bank accounts') . '</a>' . _('and general ledger accounts to be affected'),'info');
	include('includes/footer.php');
	 exit;
} else {
	echo '<option value=""></option>';
	while ($myrow=DB_fetch_array($AccountsResults)){
		/*list the bank account names */
		if ($_SESSION['ReceiptBatch' . $identifier]->Account==$myrow['accountcode']){
			echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . ' - ' . $myrow['currcode'] . '</option>';
		} else {
			echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname']. ' - ' . $myrow['currcode'] . '</option>';
		}
	}
	echo '</select></td>
		</tr>';
}

if (!Is_Date($_SESSION['ReceiptBatch' . $identifier]->DateBanked)){
	$_SESSION['ReceiptBatch' . $identifier]->DateBanked = Date($_SESSION['DefaultDateFormat']);
}

echo '<tr>
		<td>' . _('Date Banked') . ':</td>
		<td><input tabindex="2" type="text" required="required" class="date" name="DateBanked" maxlength="10" size="11" onchange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' . $_SESSION['ReceiptBatch' . $identifier]->DateBanked . '" /></td>
	</tr>
	<tr>
		<td>' . _('Currency') . ':</td>
		<td><select tabindex="3" name="Currency" onchange="ReloadForm(form1.BatchInput)">';

if (!isset($_SESSION['ReceiptBatch' . $identifier]->Currency)){
  $_SESSION['ReceiptBatch' . $identifier]->Currency=$_SESSION['CompanyRecord']['currencydefault'];
}

$SQL = "SELECT currency, currabrev, rate FROM currencies";
$result=DB_query($SQL);
if (DB_num_rows($result)==0){
	echo '</select></td></tr>';
	prnMsg(_('No currencies are defined yet') . '. ' . _('Receipts cannot be entered until a currency is defined'),'warn');

} else {
	include('includes/CurrenciesArray.php'); // To get the currency name from the currency code.
	while ($myrow=DB_fetch_array($result)){
		if ($_SESSION['ReceiptBatch' . $identifier]->Currency==$myrow['currabrev']){
			echo '<option selected="selected" value="' . $myrow['currabrev'] . '">' . $CurrencyName[$myrow['currabrev']] . '</option>';
		} else {
			echo '<option value="' . $myrow['currabrev'] . '">' . $CurrencyName[$myrow['currabrev']] . '</option>';
		}
	}
	echo '</select></td>
		</tr>';
}


if (!isset($_SESSION['ReceiptBatch' . $identifier]->ExRate)){
	$_SESSION['ReceiptBatch' . $identifier]->ExRate=1;
}

if (!isset($_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate)){
	$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate=1;
}
if ($_SESSION['ReceiptBatch' . $identifier]->AccountCurrency != $_SESSION['ReceiptBatch' . $identifier]->Currency AND isset($_SESSION['ReceiptBatch' . $identifier]->AccountCurrency)) {
	if($_SESSION['ReceiptBatch' . $identifier]->ExRate==1 AND isset($SuggestedExRate)) {
		$_SESSION['ReceiptBatch' . $identifier]->ExRate = $SuggestedExRate;
	} elseif($_POST['Currency'] != $_POST['PreviousCurrency'] AND isset($SuggestedExRate)) {//the user has changed the currency, then we should revise suggested rate
		$_SESSION['ReceiptBatch' . $identifier]->ExRate = $SuggestedExRate;
	}

	if(isset($SuggestedExRate)) {
		$SuggestedExRateText = '<b>' . _('Suggested rate:') . ' 1 ' . $_SESSION['ReceiptBatch' . $identifier]->AccountCurrency . ' = ' . locale_number_format($SuggestedExRate,8) . ' ' . $_SESSION['ReceiptBatch']->Currency . '</b>';
	} else {
		$SuggestedExRateText = '<b>1 ' . $_SESSION['ReceiptBatch' . $identifier]->AccountCurrency . ' = ? ' . $_SESSION['ReceiptBatch' . $identifier]->Currency . '</b>';
	}
	echo '<tr>
			<td>', _('Receipt Exchange Rate'), ':</td>
			<td><input class="number" maxlength="12" name="ExRate" required="required" size="14" tabindex="4" type="text" value="', locale_number_format($_SESSION['ReceiptBatch' . $identifier]->ExRate,8), '" /> ', $SuggestedExRateText, ' <i>', _('The exchange rate between the currency of the bank account currency and the currency of the receipt'), '.</i></td>
		</tr>';
}

if($_SESSION['ReceiptBatch' . $identifier]->AccountCurrency != $_SESSION['CompanyRecord']['currencydefault'] AND isset($_SESSION['ReceiptBatch' . $identifier]->AccountCurrency)) {

	if($_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate==1 AND isset($SuggestedFunctionalExRate)) {
		$_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate = $SuggestedFunctionalExRate;
	}
	if(isset($SuggestedFunctionalExRate)) {
		$SuggestedFunctionalExRateText = '<b>' . _('Suggested rate:') . ' 1 ' . $_SESSION['CompanyRecord']['currencydefault'] . ' = ' . locale_number_format($SuggestedFunctionalExRate,8) . ' ' . $_SESSION['ReceiptBatch' . $identifier]->AccountCurrency . '</b>';
	} else {
		$SuggestedFunctionalExRateText = '<b>1 ' . $_SESSION['CompanyRecord']['currencydefault'] . ' = ? ' . $_SESSION['ReceiptBatch']->AccountCurrency . '</b>';
	}
	echo '<tr>
			<td>', _('Functional Exchange Rate'), ':</td>
			<td><input class="number" maxlength="12" name="FunctionalExRate" pattern="[0-9\.,]*" required="required" size="14" tabindex="5" type="text" value="', $_SESSION['ReceiptBatch' . $identifier]->FunctionalExRate, '" /> ', $SuggestedFunctionalExRateText, ' <i>', _('The exchange rate between the currency of the business (the functional currency) and the currency of the bank account'),  '.</i></td>
		</tr>';
}

echo '<tr>
		<td>' . _('Receipt Type') . ':</td>
		<td><select name="ReceiptType" tabindex="6" onchange="ReloadForm(form1.BatchInput)">';

/* The array ReceiptTypes is defined from the setup tab of the main menu under
payment methods - the array is populated from the include file GetPaymentMethods.php */

foreach ($ReceiptTypes as $RcptType) {
	if (isset($_POST['ReceiptType']) AND $_POST['ReceiptType']==$RcptType['paymentid']){
		echo '<option selected="selected" value="' . $RcptType['paymentid'] . '">' . $RcptType['paymentname']  . '</option>';
	} else {
		echo '<option value="' . $RcptType['paymentid'] . '">' . $RcptType['paymentname']  . '</option>';
	}
}
echo '</select></td>
	</tr>';

/* Receipt (Bank Account) info to be inserted on banktrans.ref, varchar(50). */
if (!isset($_SESSION['ReceiptBatch' . $identifier]->BankTransRef)) {
	$_SESSION['ReceiptBatch' . $identifier]->BankTransRef='';
}
echo '<tr>
		<td>', _('Reference'), ':</td>
		<td><input maxlength="50" name="BankTransRef" size="52" tabindex="7" type="text" value="', $_SESSION['ReceiptBatch' . $identifier]->BankTransRef,'" /> <i>', _('Reference on Bank Transactions Inquiry'), '.</i></td>
	</tr>';

/* Receipt (Bank Account) info to be inserted on gltrans.narrative, varchar(200). */
if (!isset($_SESSION['ReceiptBatch' . $identifier]->Narrative)) {
	$_SESSION['ReceiptBatch' . $identifier]->Narrative='';
}
if (!isset($_POST['Currency'])){
	$_POST['Currency'] = $_SESSION['CompanyRecord']['currencydefault'];
}
echo '<tr>
		<td>', _('Narrative'), ':</td>
		<td><input maxlength="200" name="BatchNarrative" size="52" tabindex="8" type="text" value="', $_SESSION['ReceiptBatch' . $identifier]->Narrative, '" /> <i>', _('Narrative on General Ledger Account Inquiry'), '.</i></td>
	</tr>
	<input name="PreviousCurrency" type="hidden" value="', $_POST['Currency'], '" />
	<tr>
		<td colspan="3">
		<div class="centre">
			<input name="BatchInput" tabindex="9" type="submit" value="', _('Accept'), '" />
		</div>
		</td>
	</tr>
	</table>
	<br />';

if (isset($_SESSION['ReceiptBatch' . $identifier])){
	/* Now show the entries made so far */
	if (!$BankAccountEmpty) {
		if (!isset($ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['paymentname'])) {
			$PaymentTypeString = '';
		} else {
			$PaymentTypeString = $ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['paymentname'];

		}
		echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/transactions.png" title="' . _('Banked') . '" alt="" />
             ' . ' ' . $PaymentTypeString . ' - ' . _('Banked into the') . " " .
				$_SESSION['ReceiptBatch' . $identifier]->BankAccountName . ' ' . _('on') . ' ' . $_SESSION['ReceiptBatch' . $identifier]->DateBanked . '</p>';
	}

	echo '<table width="90%" class="selection">
		<tr>
			<th>' . _('Amount') . ' ' . _('Received') . '</th>
			<th>' . _('Discount') . '</th>
			<th>' . _('Customer') . '</th>
			<th>' . _('GL Code') . '</th>
			<th>' . _('Narrative') . '</th>
			<th>' . _('Tag') . '</th>
		</tr>';

	$BatchTotal = 0;

	foreach ($_SESSION['ReceiptBatch' . $identifier]->Items as $ReceiptItem) {

		$SQL = "SELECT accountname FROM chartmaster WHERE accountcode='" . $ReceiptItem->GLCode . "'";
		$Result=DB_query($SQL);
		$myrow=DB_fetch_array($Result);

		echo '<tr>
				<td class="number">' . locale_number_format($ReceiptItem->Amount,$_SESSION['ReceiptBatch' . $identifier]->CurrDecimalPlaces) . '</td>
				<td class="number">' . locale_number_format($ReceiptItem->Discount,$_SESSION['ReceiptBatch' . $identifier]->CurrDecimalPlaces) . '</td>
				<td>' . stripslashes($ReceiptItem->CustomerName) . '</td>
				<td>' . $ReceiptItem->GLCode.' - '.$myrow['accountname'] . '</td>
				<td>' .  stripslashes($ReceiptItem->Narrative) . '</td>
				<td>' .  $ReceiptItem->TagName . '</td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete='     . urlencode($ReceiptItem->ID)
                                                                                           . '&Type='       . urlencode($_GET['Type'])
                                                                                           . '&identifier=' . urlencode($identifier) . '">'
                                                                                           . _('Delete') . '</a></td>
			</tr>';
		$BatchTotal= $BatchTotal + $ReceiptItem->Amount;
	}

	echo '<tr>
			<td class="number"><b>' . locale_number_format($BatchTotal,$_SESSION['ReceiptBatch' . $identifier]->CurrDecimalPlaces) . '</b></td>
		</tr>
		</table>';
}

/*this next block of ifs deals with what information to display for input into the form
the info depends on where the user is up to ie the first stage is to select a bank
account, currency being banked and a batch number - or start a new batch by leaving the batch no blank
and a date for the banking. The second stage is to select a customer or GL account.
Finally enter the amount */


/*if a customer has been selected (and a receipt batch is underway)
then set out the customers account summary */


if (isset($_SESSION['CustomerRecord' . $identifier])
		AND $_SESSION['CustomerRecord' . $identifier]['currcode'] != $_SESSION['ReceiptBatch' . $identifier]->Currency){
	prnMsg(_('The selected customer does not trade in the currency of the receipt being entered - either the currency of the receipt needs to be changed or a different customer selected'),'warn');
	unset($_SESSION['CustomerRecord' . $identifier]);
}


if (isset($_SESSION['CustomerRecord' . $identifier])
		AND isset($_POST['CustomerID'])
		AND $_POST['CustomerID']!=''
		AND isset($_SESSION['ReceiptBatch' . $identifier])){
/*a customer is selected  */

	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/customer.png" title="' . _('Customer') . '" alt="" />' . ' ' . $_SESSION['CustomerRecord' . $identifier]['name'] . ' - (' . _('All amounts stated in') . ' ' . $_SESSION['CustomerRecord' . $identifier]['currency'] . ')' . _('Terms') . ': ' . $_SESSION['CustomerRecord' . $identifier]['terms'] . '<br/>' . _('Credit Limit') . ': ' . locale_number_format($_SESSION['CustomerRecord'.$identifier]['creditlimit'],0) . '  ' . _('Credit Status') . ': ' . $_SESSION['CustomerRecord'.$identifier]['reasondescription'];

	if ($_SESSION['CustomerRecord' . $identifier]['dissallowinvoices']!=0){
	   echo '<br />
			<font color="red" size="4"><b>' . _('ACCOUNT ON HOLD') . '</font></b>
			<br/>';
	}

	echo '<table width="90%" class="selection">
			<tr>
				<th width="20%">' . _('Total Balance') . '</th>
				<th width="20%">' . _('Current') . '</th>
				<th width="20%">' . _('Now Due') . '</th>
				<th width="20%">' . $_SESSION['PastDueDays1'] . '-' . $_SESSION['PastDueDays2'] . ' ' . _('Days Overdue') . '</th>
				<th width="20%">' . _('Over') . ' ' . $_SESSION['PastDueDays2'] . ' ' . _('Days Overdue') . '</th>
				<th width="20%">' . _('Customer Transaction Inquiry') . '</th>
			</tr>';

	echo '<tr>
		<td class="number">' . locale_number_format($_SESSION['CustomerRecord' . $identifier]['balance'],$_SESSION['CustomerRecord' . $identifier]['currdecimalplaces']) . '</td>
		<td class="number">' . locale_number_format(($_SESSION['CustomerRecord' . $identifier]['balance'] - $_SESSION['CustomerRecord' . $identifier]['due']),$_SESSION['CustomerRecord' . $identifier]['currdecimalplaces']) . '</td>
		<td class="number">' . locale_number_format(($_SESSION['CustomerRecord' . $identifier]['due']-$_SESSION['CustomerRecord' . $identifier]['overdue1']),$_SESSION['CustomerRecord' . $identifier]['currdecimalplaces']) . '</td>
		<td class="number">' . locale_number_format(($_SESSION['CustomerRecord' . $identifier]['overdue1']-$_SESSION['CustomerRecord' . $identifier]['overdue2']) ,$_SESSION['CustomerRecord' . $identifier]['currdecimalplaces']) . '</td>
		<td class="number">' . locale_number_format($_SESSION['CustomerRecord' . $identifier]['overdue2'],$_SESSION['CustomerRecord' . $identifier]['currdecimalplaces']) . '</td>
		<td><a href="CustomerInquiry.php?CustomerID=' . $_POST['CustomerID'] . '&Status=0" target="_blank">' . _('Inquiry') . '</td>
		</tr>
		</table>
		<br />';

	echo '<table class="selection">';

	if ($_SESSION['CustomerRecord' . $identifier]['pymtdiscount'] > $ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['percentdiscount']) {
		$DisplayDiscountPercent = locale_number_format($_SESSION['CustomerRecord' . $identifier]['pymtdiscount']*100,2) . '%';
	} else {
		$DisplayDiscountPercent = locale_number_format($ReceiptTypes[$_SESSION['ReceiptBatch' . $identifier]->ReceiptType]['percentdiscount']*100,2) . '%';
	}

	echo '<input type="hidden" name="CustomerID" value="' . $_POST['CustomerID'] . '" />';
	echo '<input type="hidden" name="CustomerName" value="' . $_SESSION['CustomerRecord' . $identifier]['name'] . '" />';

}

if (isset($_POST['GLEntry']) AND isset($_SESSION['ReceiptBatch' . $identifier])){
	/* Set up a heading for the transaction entry for a GL Receipt */
	echo '<br />
		<table class="selection">
			<tr>
				<th colspan="2">' . _('General Ledger Receipt Entry') . '</th>
			</tr>';

	//Select the tag
	echo '<tr>
			<td>' . _('Select Tag') . ':</td>
			<td><select name="tag">';

	$SQL = "SELECT tagref,
					tagdescription
					FROM tags
					ORDER BY tagref";

	$result=DB_query($SQL);
	echo '<option value="0"></option>';
	while ($myrow=DB_fetch_array($result)){
		if (isset($_POST['tag']) AND $_POST['tag']==$myrow['tagref']){
			echo '<option selected="selected" value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
		} else {
			echo '<option value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
		}
	}
	echo '</select></td>
		</tr>';
// End select tag

	/*now set up a GLCode field to select from avaialble GL accounts */
	echo '<tr>
			<td>' . _('GL Account') . ':</td>
			<td><select tabindex="8" name="GLCode">';

	$SQL = "SELECT chartmaster.accountcode,
					chartmaster.accountname
			FROM chartmaster
				INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" .  $_SESSION['UserID'] . "' AND glaccountusers.canupd=1
			ORDER BY chartmaster.accountcode";
	$result=DB_query($SQL);
	if (DB_num_rows($result)==0){
		echo '</select>' . _('No General ledger accounts have been set up yet') . ' - ' . _('receipts cannot be entered against GL accounts until the GL accounts are set up') . '</td>
			</tr>';
	} else {
		echo '<option value=""></option>';
		while ($myrow=DB_fetch_array($result)){
			if ($_POST['GLCode']==$myrow['accountcode']){
				echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . $myrow['accountname'] . '</option>';
			} else {
			echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . $myrow['accountname'] . '</option>';
			}
		}
		echo '</select></td>
			</tr>';
	}
}





/*if either a customer is selected or its a GL Entry then set out
the fields for entry of receipt amt, disc, payee details, narrative */

if (((isset($_SESSION['CustomerRecord' . $identifier])
		AND isset($_POST['CustomerID'])
		AND $_POST['CustomerID']!='')
			OR isset($_POST['GLEntry']))
		AND isset($_SESSION['ReceiptBatch' . $identifier])){

	if (!isset($_POST['Amount'])) {
		$_POST['Amount']=0;
	}
	if (!isset($_POST['Discount'])) {
		$_POST['Discount']=0;
	}
	if (!isset($_POST['PayeeBankDetail'])) {
		$_POST['PayeeBankDetail']='';
	}
	if (!isset($_POST['Narrative'])) {
		$_POST['Narrative']='';
	}
	echo '<tr>
			<td>' . _('Amount of Receipt') . ':</td>
			<td><input tabindex="9" type="text" name="Amount" required="required" maxlength="12" size="13" class="number" value="' . $_POST['Amount'] . '" /></td>
		</tr>';

	if (!isset($_POST['GLEntry'])){
		echo '<tr>
				<td>' . _('Amount of Discount') . ':</td>
				<td><input tabindex="10" type="text" name="Discount" maxlength="12" size="13" class="number" value="' . $_POST['Discount'] . '" /> ' . _('agreed prompt payment discount is') . ' ' . $DisplayDiscountPercent . '</td>
			</tr>';
	} else {
		echo '<input tabindex="11" type="hidden" name="Discount" value="0" />';
	}

	echo '<tr>
			<td>' . _('Payee Bank Details') . ':</td>
			<td><input tabindex="12" type="text" name="PayeeBankDetail" maxlength="22" size="20" value="' . $_POST['PayeeBankDetail'] . '" /></td>
		</tr>
		<tr>
			<td>' . _('Narrative') . ':</td>
			<td><textarea name="Narrative"  cols="40" rows="1"></textarea></td>
		</tr>
		</table>
		<br />
		<div class="centre">
			<input tabindex="14" type="submit" name="Process" value="' . _('Accept') . '" />
			<input tabindex="15" type="submit" name="Cancel" value="' . _('Cancel') . '" />
		</div>';

} elseif (isset($_SESSION['ReceiptBatch' . $identifier])
			AND !isset($_POST['GLEntry'])){

	/*Show the form to select a customer */
	echo '<br />';

	echo '<p class="page_title_text">
			<img src="'.$RootPath.'/css/'.$Theme.'/images/customer.png" title="' . _('Customer') . '" alt="" />' . ' ' . _('Select a Customer') . '</p>
		<table class="selection">
		<tr>
			<td>' . _('Text in the Customer') . ' ' . '<b>' . _('name') . '</b>:</td>
			<td><input tabindex="9" type="text" name="Keywords" size="15" maxlength="25" /></td>
			<td><b>' . _('OR') . '</b></td>
			<td>' . _('Text extract in the Customer') . ' ' . '<b>' . _('code') . '</b>:</td>
			<td><input tabindex="10" type="text" name="CustCode" data-type="no-illegal-chars" title="' . _('Enter an extract of the customer code to search for. Customer codes can contain any alpha-numeric character or underscore') . '" size="10" maxlength="18" /></td>
			<td><b>' . _('OR') . '</b></td>
			<td>' . _('Customer invoice number') . ':</td>
			<td><input tabindex="11" type="text" name="CustInvNo" class="integer" size="8" maxlength="8" /></td>
		</tr>
		</table>
		<div class="centre">
			<br />
			<input tabindex="11" type="submit" name="Search" value="' . _('Search Now') . '" />
			<br/>
			<br/>
			<input tabindex="12" type="submit" name="GLEntry" value="' . _('Enter A GL Receipt') . '" />
			<br />
		</div>';

	if (isset($CustomerSearchResult)) {

		echo '<table class="selection">';
		$TableHeader = '<tr>
							<th>' . _('Code') . '</th>
							<th>' . _('Customer Name') . '</th>
						</tr>';
		echo $TableHeader;
		$j = 1;

		while ($myrow=DB_fetch_array($CustomerSearchResult)) {

			printf('<tr class="striped_row">
					<td><input tabindex="'. strval(12+$j).'" type="submit" name="Select" value="%s" /></td>
					<td>%s</td>
					</tr>',
					$myrow['debtorno'],
					$myrow['name']);

			$j++;
			If ($j == 11){
				$j=1;
				echo $TableHeader;

			}
	//end of page full new headings if
		}
	//end of while loop

		echo '</table>';

	}	//end if results to show

}
if (isset($_SESSION['ReceiptBatch' . $identifier]->Items) AND count($_SESSION['ReceiptBatch' . $identifier]->Items) > 0){
	echo '<div class="centre">
			<br/>
			<input tabindex="13" type="submit" name="CommitBatch" value="' . _('Accept and Process Batch') . '" />
		</div>';
}
echo '</div>';
echo '</form>';
include('includes/footer.php');
?>

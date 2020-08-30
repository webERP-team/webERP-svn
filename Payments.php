<?php
/* Entry of bank account payments either against an AP account or a general ledger payment - if the AP-GL link in company preferences is set */

include('includes/DefinePaymentClass.php');

include('includes/session.php');
$Title = _('Payment Entry');
if(isset($_GET['SupplierID'])) {// Links to Manual before header.php
	$ViewTopic = 'AccountsPayable';
	$BookMark = 'SupplierPayments';
	$PageTitleText = _('Enter a Payment to, or Receipt from the Supplier');
} else {
	$ViewTopic= 'GeneralLedger';
	$BookMark = 'BankAccountPayments';
	$PageTitleText = _('Bank Account Payments Entry');
}
include('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/transactions.png" title="', // Icon image.
	$PageTitleText, '" /> ', // Icon title.
	$PageTitleText, '</p>';// Page title.

include('includes/SQL_CommonFunctions.inc');

if(isset($_POST['PaymentCancelled'])) {
	prnMsg(_('Payment Cancelled since cheque was not printed'), 'warning');
	include('includes/footer.php');
	exit();
}

if(empty($_GET['identifier'])) {
	$identifier = date('U');// Unique session identifier to ensure that there is no conflict with other order entry session on the same machine.
} else {
	$identifier = $_GET['identifier'];
}

if(isset($_GET['NewPayment']) AND $_GET['NewPayment']=='Yes') {
	unset($_SESSION['PaymentDetail'.$identifier]->GLItems);
	unset($_SESSION['PaymentDetail'.$identifier]);
}

if(!isset($_SESSION['PaymentDetail'.$identifier])) {
	$_SESSION['PaymentDetail'.$identifier] = new Payment;
	$_SESSION['PaymentDetail'.$identifier]->GLItemCounter = 1;
}

if((isset($_POST['UpdateHeader']) AND $_POST['BankAccount']=='')
	OR (isset($_POST['Process']) AND $_POST['BankAccount']=='')) {

	prnMsg(_('A bank account must be selected to make this payment from'), 'warn');
	$BankAccountEmpty = true;
} else {
	$BankAccountEmpty = false;
}

echo '<div class="page_help_text">', _('Use this screen to enter payments FROM your bank account.<br />Note: To enter a payment FROM a supplier, first select the Supplier, click Enter a Payment to, or Receipt from the Supplier, and use a negative Payment amount on this form.'), '</div>
	<br />';

$SQL = "SELECT pagesecurity
		  FROM scripts
		 WHERE scripts.script = 'BankAccountBalances.php'";

$ErrMsg = _('The security for G/L Accounts cannot be retrieved because');
$DbgMsg = _('The SQL that was used and failed was');
$Security2Result = DB_query($SQL, $ErrMsg, $DbgMsg);
$MyUserRow = DB_fetch_array($Security2Result);
$CashSecurity = $MyUserRow['pagesecurity'];

if(isset($_GET['SupplierID'])) {
	/*The page was called with a supplierID check it is valid and default the inputs for Supplier Name and currency of payment */

	unset($_SESSION['PaymentDetail'.$identifier]->GLItems);
	unset($_SESSION['PaymentDetail'.$identifier]);
	$_SESSION['PaymentDetail'.$identifier] = new Payment;
	$_SESSION['PaymentDetail'.$identifier]->GLItemCounter = 1;

	$SQL= "SELECT suppname,
				address1,
				address2,
				address3,
				address4,
				address5,
				address6,
				currcode,
				factorcompanyid
			FROM suppliers
			WHERE supplierid='" . $_GET['SupplierID'] . "'";

	$Result = DB_query($SQL);

	if(DB_num_rows($Result)==0) {

		prnMsg( _('The supplier code that this payment page was called with is not a currently defined supplier code') . '. ' . _('If this page is called from the selectSupplier page then this assures that a valid supplier is selected'),'warn');
		include('includes/footer.php');
		exit;

	} else {

		$myrow = DB_fetch_array($Result);
		if($myrow['factorcompanyid'] == 0) {
			$_SESSION['PaymentDetail'.$identifier]->SuppName = $myrow['suppname'];
			$_SESSION['PaymentDetail'.$identifier]->Address1 = $myrow['address1'];
			$_SESSION['PaymentDetail'.$identifier]->Address2 = $myrow['address2'];
			$_SESSION['PaymentDetail'.$identifier]->Address3 = $myrow['address3'];
			$_SESSION['PaymentDetail'.$identifier]->Address4 = $myrow['address4'];
			$_SESSION['PaymentDetail'.$identifier]->Address5 = $myrow['address5'];
			$_SESSION['PaymentDetail'.$identifier]->Address6 = $myrow['address6'];
			$_SESSION['PaymentDetail'.$identifier]->SupplierID = $_GET['SupplierID'];
			$_SESSION['PaymentDetail'.$identifier]->Currency = $myrow['currcode'];
			$_POST['Currency'] = $_SESSION['PaymentDetail'.$identifier]->Currency;

		} else {

			$FactorSQL = "SELECT coyname,
			 					address1,
			 					address2,
			 					address3,
			 					address4,
			 					address5,
			 					address6
							FROM factorcompanies
							WHERE id='" . $myrow['factorcompanyid'] . "'";

			$FactorResult = DB_query($FactorSQL);
			$myfactorrow = DB_fetch_array($FactorResult);
			$_SESSION['PaymentDetail'.$identifier]->SuppName = $myrow['suppname'] . ' ' . _('care of') . ' ' . $myfactorrow['coyname'];
			$_SESSION['PaymentDetail'.$identifier]->Address1 = $myfactorrow['address1'];
			$_SESSION['PaymentDetail'.$identifier]->Address2 = $myfactorrow['address2'];
			$_SESSION['PaymentDetail'.$identifier]->Address3 = $myfactorrow['address3'];
			$_SESSION['PaymentDetail'.$identifier]->Address4 = $myfactorrow['address4'];
			$_SESSION['PaymentDetail'.$identifier]->Address5 = $myfactorrow['address5'];
			$_SESSION['PaymentDetail'.$identifier]->Address6 = $myfactorrow['address6'];
			$_SESSION['PaymentDetail'.$identifier]->SupplierID = $_GET['SupplierID'];
			$_SESSION['PaymentDetail'.$identifier]->Currency = $myrow['currcode'];
			$_POST['Currency'] = $_SESSION['PaymentDetail'.$identifier]->Currency;
		}

		if(isset($_GET['Amount']) AND is_numeric($_GET['Amount'])) {
			$_SESSION['PaymentDetail'.$identifier]->Amount = filter_number_format($_GET['Amount']);
		}
	}
}

if(isset($_POST['BankAccount']) AND $_POST['BankAccount']!='') {

	$_SESSION['PaymentDetail'.$identifier]->Account = $_POST['BankAccount'];
	// Get the bank account currency and set that too
	$ErrMsg = _('Could not get the currency of the bank account');

	$result = DB_query("SELECT currcode,
								decimalplaces
						FROM bankaccounts
						INNER JOIN currencies
						ON bankaccounts.currcode = currencies.currabrev
						WHERE accountcode ='" . $_POST['BankAccount'] . "'",
						$ErrMsg);

	$myrow = DB_fetch_array($result);
	if($_SESSION['PaymentDetail'.$identifier]->AccountCurrency != $myrow['currcode']) {
		//then we'd better update the functional exchange rate
		$DefaultFunctionalRate = true;
		$_SESSION['PaymentDetail'.$identifier]->AccountCurrency = $myrow['currcode'];
		$_SESSION['PaymentDetail'.$identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];
	} else {
		$DefaultFunctionalRate = false;
	}

} else {

	$_SESSION['PaymentDetail'.$identifier]->AccountCurrency = $_SESSION['CompanyRecord']['currencydefault'];
	$_SESSION['PaymentDetail'.$identifier]->CurrDecimalPlaces = $_SESSION['CompanyRecord']['decimalplaces'];

}
if(isset($_POST['DatePaid']) AND $_POST['DatePaid']!='' AND Is_Date($_POST['DatePaid'])) {
	$_SESSION['PaymentDetail'.$identifier]->DatePaid = $_POST['DatePaid'];
}
if(isset($_POST['ExRate']) AND $_POST['ExRate']!='') {
	$_SESSION['PaymentDetail'.$identifier]->ExRate=filter_number_format($_POST['ExRate']); //ex rate between payment currency and account currency
}
if(isset($_POST['FunctionalExRate']) AND $_POST['FunctionalExRate']!='') {
	$_SESSION['PaymentDetail'.$identifier]->FunctionalExRate = filter_number_format($_POST['FunctionalExRate']); //ex rate between bank account currency and functional (business home) currency
}
if(isset($_POST['Paymenttype']) AND $_POST['Paymenttype']!='') {
	$_SESSION['PaymentDetail'.$identifier]->Paymenttype = $_POST['Paymenttype'];
	//lets validate the paymenttype here
	$SQL = "SELECT usepreprintedstationery
			FROM paymentmethods
			WHERE paymentname='" . $_SESSION['PaymentDetail'.$identifier]->Paymenttype . "'";
	$result = DB_query($SQL);
	$myrow = DB_fetch_row($result);
	if($myrow[0] == 1) {
		if(empty($_POST['ChequeNum'])) {
			prnMsg(_('The cheque number should not be empty'),'warn');
			$Errors[] = 'ChequeNum';
		} else {
			$cheqsql = "SELECT count(chequeno) FROM supptrans WHERE chequeno='" . $_POST['ChequeNum'] . "'";
			$ErrMsg = _('Failed to retrieve cheque number data');
			$cheqresult = DB_query($cheqsql,$ErrMsg);
			$cheqrow = DB_fetch_row($cheqresult);
			if($cheqrow[0]>0) {
				prnMsg(_('The cheque has already been used'),'warn');
				$Errors[] = 'ChequeNum';
			}
		}
	}
}

if(isset($_POST['Currency']) AND $_POST['Currency']!='') {
	/* Payment currency is the currency that is being paid */
	$_SESSION['PaymentDetail'.$identifier]->Currency=$_POST['Currency']; // Payment currency

	if($_SESSION['PaymentDetail'.$identifier]->AccountCurrency==$_SESSION['CompanyRecord']['currencydefault']) {
		$_POST['FunctionalExRate']=1;
		$_SESSION['PaymentDetail'.$identifier]->FunctionalExRate=1;
		$SuggestedFunctionalExRate =1;

	} else {
		/*To illustrate the rates required
			Take an example functional currency NZD payment in USD from an AUD bank account
			1 NZD = 0.80 USD
			1 NZD = 0.90 AUD
			The FunctionalExRate = 0.90 - the rate between the functional currency and the bank account currency
			The payment ex rate is the rate at which one can purchase the payment currency in the bank account currency
			or 0.8/0.9 = 0.88889
		*/

		/*Get suggested FunctionalExRate - between bank account and home functional currency */
		$result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['PaymentDetail'.$identifier]->AccountCurrency . "'");
		$myrow = DB_fetch_row($result);
		$SuggestedFunctionalExRate = $myrow[0];
		if($DefaultFunctionalRate) {
			$_SESSION['PaymentDetail'.$identifier]->FunctionalExRate = $SuggestedFunctionalExRate;
		}
	}

	if($_POST['Currency']==$_SESSION['PaymentDetail'.$identifier]->AccountCurrency) {
		/* if the currency being paid is the same as the bank account currency then default ex rate to 1 */
		$_POST['ExRate']=1;
		$_SESSION['PaymentDetail'.$identifier]->ExRate = 1; //ex rate between payment currency and account currency is 1 if they are the same!!
		$SuggestedExRate=1;
	} elseif(isset($_POST['Currency'])) {
		/*Get the exchange rate between the bank account currency and the payment currency*/
		$result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['PaymentDetail'.$identifier]->Currency . "'");
		$myrow = DB_fetch_row($result);
		$TableExRate = $myrow[0]; //this is the rate of exchange between the functional currency and the payment currency
		/*Calculate cross rate to suggest appropriate exchange rate between payment currency and account currency */
		$SuggestedExRate = $TableExRate/$SuggestedFunctionalExRate;
	}
}

// Reference in banking transactions:
if(isset($_POST['BankTransRef']) AND $_POST['BankTransRef']!='') {
	$_SESSION['PaymentDetail'.$identifier]->BankTransRef = $_POST['BankTransRef'];
}
// Narrative in general ledger transactions:
if(isset($_POST['Narrative']) AND $_POST['Narrative']!='') {
	$_SESSION['PaymentDetail'.$identifier]->Narrative = $_POST['Narrative'];
}
// Supplier narrative in general ledger transactions:
if(isset($_POST['gltrans_narrative'])) {
	if($_POST['gltrans_narrative']=='') {
		$_SESSION['PaymentDetail'.$identifier]->gltrans_narrative = $_POST['Narrative'];// If blank, it uses the bank narrative.
	} else {
		$_SESSION['PaymentDetail'.$identifier]->gltrans_narrative = $_POST['gltrans_narrative'];
	}
}
// Supplier reference in supplier transactions:
if(isset($_POST['supptrans_suppreference'])) {
	if($_POST['supptrans_suppreference']=='') {
		$_SESSION['PaymentDetail'.$identifier]->supptrans_suppreference = $_POST['Paymenttype'];// If blank, it uses the payment type.
	} else {
		$_SESSION['PaymentDetail'.$identifier]->supptrans_suppreference = $_POST['supptrans_suppreference'];
	}
}
// Transaction text in supplier transactions:
if(isset($_POST['supptrans_transtext'])) {
	if($_POST['supptrans_transtext']=='') {
		$_SESSION['PaymentDetail'.$identifier]->supptrans_transtext = $_POST['Narrative'];// If blank, it uses the narrative.
	} else {
		$_SESSION['PaymentDetail'.$identifier]->supptrans_transtext = $_POST['supptrans_transtext'];
	}
}

if(isset($_POST['Amount']) AND $_POST['Amount']!='') {
	$_SESSION['PaymentDetail'.$identifier]->Amount = filter_number_format($_POST['Amount']);
} else {
	if(!isset($_SESSION['PaymentDetail'.$identifier]->Amount)) {
		$_SESSION['PaymentDetail'.$identifier]->Amount = 0;
	}
}

if(isset($_POST['Discount']) AND $_POST['Discount']!='') {
	$_SESSION['PaymentDetail'.$identifier]->Discount = filter_number_format($_POST['Discount']);
} else {
	if(!isset($_SESSION['PaymentDetail'.$identifier]->Discount)) {
	 $_SESSION['PaymentDetail'.$identifier]->Discount = 0;
 }
}


if(isset($_POST['CommitBatch']) AND empty($Errors)) {

	/* once the GL analysis of the payment is entered (if the Creditors_GLLink is active),
	process all the data in the session cookie into the DB creating a banktrans record for
	the payment in the batch and SuppTrans record for the supplier payment if a supplier was selected
	A GL entry is created for each GL entry (only one for a supplier entry) and one for the bank
	account credit.

	NB allocations against supplier payments are a separate exercise

	if GL integrated then
	first off run through the array of payment items $_SESSION['Payment']->GLItems and
	create GL Entries for the GL payment items
	*/

	/*First off check we have an amount entered as paid ?? */
	$TotalAmount = 0;
	foreach($_SESSION['PaymentDetail'.$identifier]->GLItems AS $PaymentItem) {
		$TotalAmount += $PaymentItem->Amount;
	}

	if($TotalAmount==0 AND
		($_SESSION['PaymentDetail'.$identifier]->Discount + $_SESSION['PaymentDetail'.$identifier]->Amount)/$_SESSION['PaymentDetail'.$identifier]->ExRate ==0) {
		prnMsg( _('This payment has no amounts entered and will not be processed'),'warn');
		include('includes/footer.php');
		exit;
	}

	if($_POST['BankAccount']=='') {
		prnMsg( _('No bank account has been selected so this payment cannot be processed'),'warn');
		include('includes/footer.php');
		exit;
	}

	/*Make an array of the defined bank accounts */
	$SQL = "SELECT bankaccounts.accountcode
			FROM bankaccounts,
				chartmaster
			WHERE bankaccounts.accountcode=chartmaster.accountcode";
	$result = DB_query($SQL);
	$BankAccounts = array();
	$i=0;

	while($Act = DB_fetch_row($result)) {
		$BankAccounts[$i]= $Act[0];
		$i++;
	}

	$PeriodNo = GetPeriod($_SESSION['PaymentDetail'.$identifier]->DatePaid);

	$SQL = "SELECT usepreprintedstationery
			FROM paymentmethods
			WHERE paymentname='" . $_SESSION['PaymentDetail'.$identifier]->Paymenttype ."'";
	$result=DB_query($SQL);
	$myrow=DB_fetch_row($result);

	// first time through commit if supplier cheque then print it first
	if((!isset($_POST['ChequePrinted']))
		AND (!isset($_POST['PaymentCancelled']))
		AND ($myrow[0] == 1)) {
	// it is a supplier payment by cheque and haven't printed yet so print cheque
		//check the cheque number
		if(empty($_POST['ChequeNum'])) {
			prnMsg(_('There are no Check Number input'),'error');
			include('includes/footer.inc');
			exit;
		} elseif(!is_numeric($_POST['ChequeNum'])) {//check if this cheque no has been used
			prnMsg(_('The cheque no should be numeric'),'error');
			include('includes/footer.inc');
			exit;
		} else {
			$cheqsql = "SELECT count(chequeno) FROM  supptrans WHERE chequeno='" . $_POST['ChequeNum'] . "'";
			$ErrMsg = _('Failed to retrieve cheque number data');
			$cheqresult = DB_query($cheqsql,$ErrMsg);
			$cheqrow = DB_fetch_row($cheqresult);
			if($cheqrow[0]>0) {
				prnMsg(_('The cheque has already been used'),'error');
				include('includes/footer.inc');
				exit;
			}
		}
		//store the paid array here;
		$PaidArray = array();
		foreach ($_POST as $name=>$Value) {
			if(substr($name, 0, 4) == 'paid' AND $Value>0) {
				$PaidArray[substr($name, 4)] = $Value;
			}
		}
		if(!empty($PaidArray)) {
			$PaidJ = base64_encode(serialize($PaidArray));
			$PaidInput = '<input type="hidden" name="PaidArray" value="' . $PaidJ . '" />';
		} else {
			$PaidInput = '';
		}



		echo '<br />
			<a href="' . $RootPath . '/PrintCheque.php?ChequeNum=' . $_POST['ChequeNum'] . '&amp;identifier=' . $identifier . '">' . _('Print Cheque using pre-printed stationery') . '</a>
			<br />
			<br />
			<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?identifier=', urlencode($identifier), '">
			<div>
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />' ,
			_('Has the cheque been printed') , '?
			<br />
			<br />'
			, $PaidInput , '
			<input type="hidden" name="BankTransRef" value="' , $_POST['BankTransRef'] , '" />
			<input type="hidden" name="ChequeNum" value="' , $_POST['ChequeNum'] , '" />
			<input type="hidden" name="CommitBatch" value="' , $_POST['CommitBatch'] , '" />
			<input type="hidden" name="BankAccount" value="' , $_POST['BankAccount'] , '" />
			<input type="submit" name="ChequePrinted" value="' , _('Yes / Continue') , '" />&nbsp;&nbsp;
			<input type="submit" name="PaymentCancelled" value="' , _('No / Cancel Payment') , '" />
			<br />' , _('Payment amount') , ' = ' , $_SESSION['PaymentDetail'.$identifier]->Amount ,
			'</div>
			</form>';

	} else {

		//Start a transaction to do the whole lot inside

		$result = DB_Txn_Begin();

		if($_SESSION['PaymentDetail'.$identifier]->SupplierID=='') {

		//its a nominal bank transaction type 1

			$TransNo = GetNextTransNo( 1 );
			$TransType = 1;

			if($_SESSION['CompanyRecord']['gllink_creditors']==1) { /* then enter GLTrans */
				$TotalAmount=0;
				foreach($_SESSION['PaymentDetail'.$identifier]->GLItems as $PaymentItem) {

					 /*The functional currency amount will be the
					 payment currenct amount / the bank account currency exchange rate - to get to the bank account currency
					 then / the functional currency exchange rate to get to the functional currency */
					if($PaymentItem->Cheque=='') {
						$PaymentItem->Cheque=0;
					}
					$SQL = "INSERT INTO gltrans (
								type,
								typeno,
								trandate,
								periodno,
								account,
								narrative,
								amount,
								chequeno,
								tag
							) VALUES (
								1,'" .
								$TransNo . "','" .
								FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "','" .
								$PeriodNo . "','" .
								$PaymentItem->GLCode . "','" .
								$PaymentItem->Narrative . "','" .
								($PaymentItem->Amount/$_SESSION['PaymentDetail'.$identifier]->ExRate/$_SESSION['PaymentDetail'.$identifier]->FunctionalExRate) . "','".
								$PaymentItem->Cheque ."','" .
								$PaymentItem->Tag .
							"')";
					$ErrMsg = _('Cannot insert a GL entry for the payment using the SQL');
					$result = DB_query($SQL,$ErrMsg,_('The SQL that failed was'),true);

					$TotalAmount += $PaymentItem->Amount;
				}
				$_SESSION['PaymentDetail'.$identifier]->Amount = $TotalAmount;
				$_SESSION['PaymentDetail'.$identifier]->Discount=0;
			}

			//Run through the GL postings to check to see if there is a posting to another bank account (or the same one) if there is then a receipt needs to be created for this account too

			foreach($_SESSION['PaymentDetail'.$identifier]->GLItems as $PaymentItem) {

				if(in_array($PaymentItem->GLCode, $BankAccounts)) {

					/*Need to deal with the case where the payment from one bank account could be to a bank account in another currency */

					/*Get the currency and rate of the bank account transferring to*/
					$SQL = "SELECT currcode, rate
							FROM bankaccounts INNER JOIN currencies
							ON bankaccounts.currcode = currencies.currabrev
							WHERE accountcode='" . $PaymentItem->GLCode . "'";
					$TrfToAccountResult = DB_query($SQL);
					$TrfToBankRow = DB_fetch_array($TrfToAccountResult) ;
					$TrfToBankCurrCode = $TrfToBankRow['currcode'];
					$TrfToBankExRate = $TrfToBankRow['rate'];

					if($_SESSION['PaymentDetail'.$identifier]->AccountCurrency == $TrfToBankCurrCode) {
					/*Make sure to use the same rate if the transfer is between two bank accounts in the same currency */
						$TrfToBankExRate = $_SESSION['PaymentDetail'.$identifier]->FunctionalExRate;
					}
					if($_SESSION['PaymentDetail'.$identifier]->Currency == $TrfToBankCurrCode) {
						$ExRate = 1;
						$TrfToBankExRate = $_SESSION['PaymentDetail'.$identifier]->ExRate;
					} else {
						$ExRate = ($_SESSION['PaymentDetail'.$identifier]->ExRate * $_SESSION['PaymentDetail'.$identifier]->FunctionalExRate)/$TrfFromBankExRate;
					}

					/*Consider an example
					 functional currency NZD
					 bank account in AUD - 1 NZD = 0.90 AUD (FunctionalExRate)
					 paying USD - 1 AUD = 0.85 USD (ExRate)
					 to a bank account in EUR - 1 NZD = 0.52 EUR

					 oh yeah - now we are getting tricky!
					 Lets say we pay USD 100 from the AUD bank account to the EUR bank account

					 To get the ExRate for the bank account we are transferring money to
					 we need to use the cross rate between the NZD-AUD/NZD-EUR
					 and apply this to the

					 the payment record will read
					 exrate = 0.85 (1 AUD = USD 0.85)
					 amount = 100 (USD)
					 functionalexrate = 0.90 (1 NZD = AUD 0.90)

					 the receipt record will read

					 amount 100 (USD)
					 exrate (1 EUR = (0.85 x 0.90)/0.52 USD)
					 					(ExRate x FunctionalExRate) / USD Functional ExRate
					 functionalexrate = (1NZD = EUR 0.52)

				*/

					$ReceiptTransNo = GetNextTransNo( 2 );
					$SQL = "INSERT INTO banktrans (
								transno,
								type,
								bankact,
								ref,
								exrate,
								functionalexrate,
								transdate,
								banktranstype,
								amount,
								currcode
							) VALUES ('" .
								$ReceiptTransNo . "',
								2,'" .
								$PaymentItem->GLCode . "','" .
								'@' . $TransNo . ' ' . _('Act Transfer From') .' '. $_SESSION['PaymentDetail'.$identifier]->Account . ' - ' . $PaymentItem->Narrative . "','" .
								$ExRate . "','" .
								$TrfToBankExRate . "','" .
								FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "','" .
								$_SESSION['PaymentDetail'.$identifier]->Paymenttype . "','" .
								$PaymentItem->Amount . "','" .
								$_SESSION['PaymentDetail'.$identifier]->Currency .
							"')";
					$ErrMsg = _('Cannot insert a bank transaction because');
					$DbgMsg = _('Cannot insert a bank transaction with the SQL');
					$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
				}
			}
	} else {

			/* Get an array of supptans id fields that were paid */
			if(!isset($_POST['PaidArray'])) {
				$PaidArray = array();
				foreach ($_POST as $name=>$Value) {
					if(substr($name, 0, 4) == 'paid' AND $Value>0) {
						$PaidArray[substr($name, 4)] = $Value;
					}
				}
			} else {
				$PaidArray = unserialize(base64_decode($_POST['PaidArray']));
			}

			/*Its a supplier payment type 22 */
			$CreditorTotal = (($_SESSION['PaymentDetail'.$identifier]->Discount + $_SESSION['PaymentDetail'.$identifier]->Amount)/$_SESSION['PaymentDetail'.$identifier]->ExRate)/$_SESSION['PaymentDetail'.$identifier]->FunctionalExRate;

			$TransNo = GetNextTransNo(22);
			$TransType = 22;

			/* Create a SuppTrans entry for the supplier payment */
			$SQL = "INSERT INTO supptrans (
							transno,
							type,
							supplierno,
							trandate,
							inputdate,
							suppreference,
							rate,
							ovamount,
							transtext,
							chequeno
						) VALUES ('" .
							$TransNo . "',
							22,'" .
							$_SESSION['PaymentDetail'.$identifier]->SupplierID . "','" .
							FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "','" .
							date('Y-m-d H-i-s') . "','" .
							$_SESSION['PaymentDetail'.$identifier]->supptrans_suppreference . "','" .
							($_SESSION['PaymentDetail'.$identifier]->FunctionalExRate * $_SESSION['PaymentDetail'.$identifier]->ExRate) . "','" .
							(-$_SESSION['PaymentDetail'.$identifier]->Amount-$_SESSION['PaymentDetail'.$identifier]->Discount) . "','" .
							$_SESSION['PaymentDetail'.$identifier]->supptrans_transtext .
							"',
							'" . $_POST['ChequeNum'] . "')";
			$ErrMsg = _('Cannot insert a payment transaction against the supplier because');
			$DbgMsg = _('Cannot insert a payment transaction against the supplier using the SQL');
			$result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			$SQL = "SELECT id FROM supptrans WHERE transno='" . $TransNo . "' AND type=22";
			$Result = DB_query($SQL,'','',true);
			$MyRow = DB_fetch_array($Result);
			$PaymentID = $MyRow['id'];
			if(sizeof($PaidArray) > 0) {
				foreach ($PaidArray as $PaidID=>$PaidAmount) {
					/* Firstly subtract from the payment the amount of the invoice  */
					$SQL = "UPDATE supptrans SET alloc=alloc-" . $PaidAmount . " WHERE id='" . $PaymentID . "'";
					$ErrMsg = _('Cannot update an allocation against the supplier because');
					$DbgMsg = _('Cannot update an allocation against the supplier using the SQL');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					/* Then add theamount of the invoice to the invoice allocation */
					$SQL = "UPDATE supptrans SET alloc=alloc+" . $PaidAmount . " WHERE id='" . $PaidID . "'";
					$ErrMsg = _('Cannot update an allocation against the supplier because');
					$DbgMsg = _('Cannot update an allocation against the supplier using the SQL');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					/* Finally update the supplier allocations table */
					$SQL = "INSERT INTO suppallocs (amt,
													datealloc,
													transid_allocfrom,
													transid_allocto
												) VALUES (
													'" . $PaidAmount . "',
													'" . FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "',
													'" . $PaymentID . "',
													'" . $PaidID . "'
												)";
					$ErrMsg = _('Cannot update an allocation against the supplier because');
					$DbgMsg = _('Cannot update an allocation against the supplier using the SQL');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				}
			}





			/*Update the supplier master with the date and amount of the last payment made */
			$SQL = "UPDATE suppliers
					SET	lastpaiddate = '" . FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "',
						lastpaid='" . $_SESSION['PaymentDetail'.$identifier]->Amount ."'
					WHERE suppliers.supplierid='" . $_SESSION['PaymentDetail'.$identifier]->SupplierID . "'";
			$ErrMsg = _('Cannot update the supplier record for the date of the last payment made because');
			$DbgMsg = _('Cannot update the supplier record for the date of the last payment made using the SQL');
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			$_SESSION['PaymentDetail'.$identifier]->gltrans_narrative = $_SESSION['PaymentDetail'.$identifier]->SupplierID . ' - ' . $_SESSION['PaymentDetail'.$identifier]->gltrans_narrative;

			if($_SESSION['CompanyRecord']['gllink_creditors']==1) { /* then do the supplier control GLTrans */
			/* Now debit creditors account with payment + discount */

				$SQL = "INSERT INTO gltrans (
							type,
							typeno,
							trandate,
							periodno,
							account,
							narrative,
							amount
						) VALUES (
							22,'" .
							$TransNo . "','" .
							FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "','" .
							$PeriodNo . "','" .
							$_SESSION['CompanyRecord']['creditorsact'] . "','" .
							$_SESSION['PaymentDetail'.$identifier]->gltrans_narrative . "','" .
							$CreditorTotal .
						"')";
				$ErrMsg = _('Cannot insert a GL transaction for the creditors account debit because');
				$DbgMsg = _('Cannot insert a GL transaction for the creditors account debit using the SQL');
				$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				if($_SESSION['PaymentDetail'.$identifier]->Discount != 0) {
					/* Now credit Discount received account with discounts */
					$SQL = "INSERT INTO gltrans (
								type,
								typeno,
								trandate,
								periodno,
								account,
								narrative,
								amount
							) VALUES (
								22,'" .
								$TransNo . "','" .
								FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "','" .
								$PeriodNo . "','" .
								$_SESSION['CompanyRecord']['pytdiscountact'] . "','" .
								$_SESSION['PaymentDetail'.$identifier]->gltrans_narrative . "','" .
								(-$_SESSION['PaymentDetail'.$identifier]->Discount/$_SESSION['PaymentDetail'.$identifier]->ExRate/$_SESSION['PaymentDetail'.$identifier]->FunctionalExRate) .
							"')";
					$ErrMsg = _('Cannot insert a GL transaction for the payment discount credit because');
					$DbgMsg = _('Cannot insert a GL transaction for the payment discount credit using the SQL');
					$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
				} // end if discount

			} // end if gl creditors
		} // end if supplier

		if($_SESSION['CompanyRecord']['gllink_creditors'] == 1) { /* then do the common GLTrans */

			if($_SESSION['PaymentDetail'.$identifier]->Amount != 0) {
				/* Bank account entry first */
				$SQL = "INSERT INTO gltrans (
							type,
							typeno,
							trandate,
							periodno,
							account,
							narrative,
							amount
						) VALUES ('" .
							$TransType . "','" .
							$TransNo . "','" .
							FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "','" .
							$PeriodNo . "','" .
							$_SESSION['PaymentDetail'.$identifier]->Account . "','" .
							$_SESSION['PaymentDetail'.$identifier]->Narrative . "','" .
							(-$_SESSION['PaymentDetail'.$identifier]->Amount/$_SESSION['PaymentDetail'.$identifier]->ExRate/$_SESSION['PaymentDetail'.$identifier]->FunctionalExRate) .
						"')";
				$ErrMsg = _('Cannot insert a GL transaction for the bank account credit because');
				$DbgMsg = _('Cannot insert a GL transaction for the bank account credit using the SQL');
				$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
				EnsureGLEntriesBalance($TransType,$TransNo);
			}
		}

		/*now enter the BankTrans entry */
		$SQL = "INSERT INTO banktrans (
					transno,
					type,
					bankact,
					ref,
					exrate,
					functionalexrate,
					transdate,
					banktranstype,
					amount,
					currcode,
					chequeno
				) VALUES ('" .
					$TransNo . "','" .
					$TransType . "','" .
					$_SESSION['PaymentDetail'.$identifier]->Account . "','" .
					$_SESSION['PaymentDetail'.$identifier]->BankTransRef . "','" .
					$_SESSION['PaymentDetail'.$identifier]->ExRate . "','" .
					$_SESSION['PaymentDetail'.$identifier]->FunctionalExRate . "','" .
					FormatDateForSQL($_SESSION['PaymentDetail'.$identifier]->DatePaid) . "','" .
					$_SESSION['PaymentDetail'.$identifier]->Paymenttype . "','" .
					-$_SESSION['PaymentDetail'.$identifier]->Amount . "','" .
					$_SESSION['PaymentDetail'.$identifier]->Currency . "','" .
					$_POST['ChequeNum'] .
				"')";
		$ErrMsg = _('Cannot insert a bank transaction because');
		$DbgMsg = _('Cannot insert a bank transaction using the SQL');
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		DB_Txn_Commit();
		prnMsg(_('Payment') . ' ' . $TransNo . ' ' . _('has been successfully entered'),'success');

		$LastSupplier = ($_SESSION['PaymentDetail'.$identifier]->SupplierID);

		unset($_POST['BankAccount']);
		unset($_POST['DatePaid']);
		unset($_POST['ExRate']);
		unset($_POST['Paymenttype']);
		unset($_POST['Currency']);
		unset($_POST['Narrative']);
		unset($_POST['gltrans_narrative']);
		unset($_POST['supptrans_suppreference']);
		unset($_POST['supptrans_transtext']);
		unset($_POST['Amount']);
		unset($_POST['Discount']);
		unset($_POST['FunctionalExRate']);
		unset($_SESSION['PaymentDetail'.$identifier]->GLItems);
		unset($_SESSION['PaymentDetail'.$identifier]->SupplierID);
		unset($_SESSION['PaymentDetail'.$identifier]);

		/*Set up a newy in case user wishes to enter another */
		if(isset($LastSupplier) and $LastSupplier!='') {
			$SupplierSQL="SELECT suppname FROM suppliers
					WHERE supplierid='".$LastSupplier."'";
			$SupplierResult = DB_query($SupplierSQL);
			$SupplierRow = DB_fetch_array($SupplierResult);
			$TransSQL = "SELECT id FROM supptrans WHERE type=22 AND transno='" . $TransNo . "'";
			$TransResult = DB_query($TransSQL);
			$TransRow = DB_fetch_array($TransResult);
			echo '<br /><a href="' . $RootPath . '/SupplierAllocations.php?AllocTrans=' . $TransRow['id'] . '">' . _('Allocate this payment') . '</a>';
			echo '<br /><a href="' . $RootPath . '/Payments.php?SupplierID=' . $LastSupplier . '">' . _('Enter another Payment for') . ' ' . $SupplierRow['suppname'] . '</a>';
		} else {
			echo '<br /><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('Enter another General Ledger Payment') . '</a><br />';
		}
	}

	include('includes/footer.php');
	exit;

} elseif(isset($_GET['Delete'])) {
 /* User hit delete the receipt entry from the batch */
	$_SESSION['PaymentDetail'.$identifier]->Remove_GLItem($_GET['Delete']);
	//recover the bank account relative setting
	$_POST['BankAccount'] = $_SESSION['PaymentDetail'.$identifier]->Account;
	$_POST['DatePaid'] = $_SESSION['PaymentDetail'.$identifier]->DatePaid;
	$_POST['Currency'] = $_SESSION['PaymentDetail'.$identifier]->Currency;
	$_POST['ExRate'] = $_SESSION['PaymentDetail'.$identifier]->ExRate;
	$_POST['FunctionalExRate'] = $_SESSION['PaymentDetail'.$identifier]->FunctionalExRate;
	$_POST['PaymentType'] = $_SESSION['PaymentDetail'.$identifier]->Paymenttype;
	$_POST['BankTransRef'] = $_SESSION['PaymentDetail'.$identifier]->BankTransRef;
	$_POST['Narrative'] = $_SESSION['PaymentDetail'.$identifier]->Narrative;

} elseif(isset($_POST['Process']) AND !$BankAccountEmpty) { //user hit submit a new GL Analysis line into the payment
	if(!empty($_POST['Cheque'])) {
		$ChequeNoSQL="SELECT transno FROM supptrans WHERE chequeno='" . $_POST['Cheque'] ."'";
		$ChequeNoResult=DB_query($ChequeNoSQL);
	}

	if(is_numeric($_POST['GLManualCode'])) {

		$SQL = "SELECT accountname
				FROM chartmaster
				WHERE accountcode='" . $_POST['GLManualCode'] . "'";

		$Result=DB_query($SQL);

		if(DB_num_rows($Result)==0) {
			prnMsg( _('The manual GL code entered does not exist in the database') . ' - ' . _('so this GL analysis item could not be added'),'warn');
			unset($_POST['GLManualCode']);
		} elseif(isset($ChequeNoResult) AND DB_num_rows($ChequeNoResult)!=0 AND $_POST['Cheque']!='') {
			prnMsg( _('The Cheque/Voucher number has already been used') . ' - ' . _('This GL analysis item could not be added'),'error');
		} else {
			$myrow = DB_fetch_array($Result);
			$AllowThisPosting = true;
			if($_SESSION['ProhibitJournalsToControlAccounts'] == 1) {
				if($_SESSION['CompanyRecord']['gllink_debtors'] == '1' AND $_POST['GLManualCode'] == $_SESSION['CompanyRecord']['debtorsact']) {
					prnMsg(_('Payments involving the debtors control account cannot be entered. The general ledger debtors ledger (AR) integration is enabled so control accounts are automatically maintained. This setting can be disabled in System Configuration'), 'warn');
					$AllowThisPosting = false;
				}
	 			if($_SESSION['CompanyRecord']['gllink_creditors'] == '1' AND
					($_POST['GLManualCode'] == $_SESSION['CompanyRecord']['creditorsact'] OR $_POST['GLManualCode'] == $_SESSION['CompanyRecord']['grnact'])) {
	 				prnMsg(_('Payments involving the creditors control account or the GRN suspense account cannot be entered. The general ledger creditors ledger (AP) integration is enabled so control accounts are automatically maintained. This setting can be disabled in System Configuration'), 'warn');
	 				$AllowThisPosting = false;
	 			}
	 			if($_POST['GLManualCode'] == $_SESSION['CompanyRecord']['retainedearnings']) {
	 				prnMsg(_('Payments involving the retained earnings control account cannot be entered. This account is automtically maintained.'), 'warn');
	 				$AllowThisPosting = false;
	 			}
	 		}
	 		if($AllowThisPosting) {
				$_SESSION['PaymentDetail'.$identifier]->add_to_glanalysis(filter_number_format($_POST['GLAmount']),
																								$_POST['GLNarrative'],
																								$_POST['GLManualCode'],
																								$myrow['accountname'],
																								$_POST['Tag'],
																								$_POST['Cheque']);
				unset($_POST['GLManualCode']);
			}
		}
	} elseif(DB_num_rows($ChequeNoResult)!=0 AND $_POST['Cheque']!='') {
		prnMsg( _('The cheque number has already been used') . ' - ' . _('This GL analysis item could not be added'),'error');
	} elseif($_POST['GLCode'] == '') {
			prnMsg( _('No General Ledger code has been chosen') . ' - ' . _('so this GL analysis item could not be added'),'warn');
	} else {
		$SQL = "SELECT accountname FROM chartmaster WHERE accountcode='" . $_POST['GLCode'] . "'";
		$Result=DB_query($SQL);
		$myrow=DB_fetch_array($Result);
		$_SESSION['PaymentDetail'.$identifier]->add_to_glanalysis(filter_number_format($_POST['GLAmount']),
														$_POST['GLNarrative'],
														$_POST['GLCode'],
														$myrow['accountname'],
														$_POST['Tag'],
														$_POST['Cheque']);
	}

	/*Make sure the same receipt is not double processed by a page refresh */
	$_POST['Cancel'] = 1;
}

if(isset($_POST['Cancel'])) {
	unset($_POST['GLAmount']);
	unset($_POST['GLNarrative']);
	unset($_POST['GLCode']);
	unset($_POST['AccountName']);
}

/*set up the form whatever */
if(!isset($_POST['DatePaid'])) {
	$_POST['DatePaid'] = '';
}

if(isset($_POST['DatePaid'])
	AND ($_POST['DatePaid']==''
		OR !Is_Date($_SESSION['PaymentDetail'.$identifier]->DatePaid))) {

	$_POST['DatePaid']= Date($_SESSION['DefaultDateFormat']);
	$_SESSION['PaymentDetail'.$identifier]->DatePaid = $_POST['DatePaid'];
}

if($_SESSION['PaymentDetail'.$identifier]->Currency=='' AND $_SESSION['PaymentDetail'.$identifier]->SupplierID=='') {
	$_SESSION['PaymentDetail'.$identifier]->Currency=$_SESSION['CompanyRecord']['currencydefault'];
}


if(isset($_POST['BankAccount']) AND $_POST['BankAccount']!='') {
	$SQL = "SELECT bankaccountname
			FROM bankaccounts,
				chartmaster
			WHERE bankaccounts.accountcode= chartmaster.accountcode
			AND chartmaster.accountcode='" . $_POST['BankAccount'] . "'";

	$ErrMsg = _('The bank account name cannot be retrieved because');
	$DbgMsg = _('SQL used to retrieve the bank account name was');

	$result= DB_query($SQL,$ErrMsg,$DbgMsg);

	if(DB_num_rows($result)==1) {
		$myrow = DB_fetch_row($result);
		$_SESSION['PaymentDetail'.$identifier]->BankAccountName = $myrow[0];
		unset($result);
	} elseif(DB_num_rows($result)==0) {
		prnMsg( _('The bank account number') . ' ' . $_POST['BankAccount'] . ' ' . _('is not set up as a bank account with a valid general ledger account'),'error');
	}
}

echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?identifier=', urlencode($identifier), '" method="post">
	<div>
	<input type="hidden" name="FormID" value="' , $_SESSION['FormID'] , '" />
	<br />
	<table class="selection">
	<tr>
		<th colspan="4"><h3>' , _('Payment');

if($_SESSION['PaymentDetail'.$identifier]->SupplierID!='') {
	echo ' ' . _('to') . ' ' . $_SESSION['PaymentDetail'.$identifier]->SuppName;
}

if($_SESSION['PaymentDetail'.$identifier]->BankAccountName!='') {
	echo ' ' . _('from the') . ' ' . $_SESSION['PaymentDetail'.$identifier]->BankAccountName;

	if (in_array($CashSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($CashSecurity)) {
		$CurrBalanceSQL = "SELECT SUM(amount) AS balance FROM banktrans WHERE bankact='" . $_SESSION['PaymentDetail'.$identifier]->Account . "'";
		$CurrBalanceResult = DB_query($CurrBalanceSQL);
		$CurrBalanceRow = DB_fetch_array($CurrBalanceResult);

		$DecimalPlacesSQL = "SELECT decimalplaces FROM currencies WHERE currabrev='" . $_SESSION['PaymentDetail'.$identifier]->Account . "'";
		$DecimalPlacesResult = DB_query($DecimalPlacesSQL);
		$DecimalPlacesRow = DB_fetch_array($DecimalPlacesResult);
	}
}

echo ' ' . _('on') . ' ' . $_SESSION['PaymentDetail'.$identifier]->DatePaid . '</h3></th></tr>';

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

echo '<tr>
		<td>', _('Bank Account'), ':</td>
		<td><select autofocus="autofocus" name="BankAccount" onchange="ReloadForm(UpdateHeader)" required="required" title="', _('Select the bank account that the payment has been made from'), '">';

if(DB_num_rows($AccountsResults)==0) {
	echo '</select></td>
		</tr>
		</table>
		<p />';
	prnMsg( _('Bank Accounts have not yet been defined. You must first') . ' <a href="' . $RootPath . '/BankAccounts.php">' . _('define the bank accounts') . '</a> ' . _('and general ledger accounts to be affected'),'warn');
	include('includes/footer.php');
	exit;
} else {
	echo '<option value=""></option>';
	while($myrow=DB_fetch_array($AccountsResults)) {
	/*list the bank account names */
		echo '<option ';
		if(isset($_POST['BankAccount']) AND $_POST['BankAccount']==$myrow['accountcode']) {
			echo 'selected="selected" ';
		}
		echo 'value="', $myrow['accountcode'], '">', $myrow['bankaccountname'], ' - ', $myrow['currcode'], '</option>';
	}
	echo '</select>';

	if (in_array($CashSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($CashSecurity)) {
		if (isset($_SESSION['PaymentDetail'.$identifier]->Account)){
			echo ' (' . locale_number_format($CurrBalanceRow['balance'], $_SESSION['CompanyRecord']['decimalplaces']) . ' ' . _('Balance in account currency') . ')';
		}
	}

	echo '</td></tr>';
}

echo '<tr>
		<td>', _('Date'), ':</td>
		<td><input class="date" maxlength="10" name="DatePaid" onchange="isDate(this, this.value, ', "'", $_SESSION['DefaultDateFormat'], "'", ')" required="required" size="11" type="text" value="', $_SESSION['PaymentDetail'.$identifier]->DatePaid, '" /></td>
	</tr>';

// Currency of payment:
echo '<tr>
		<td>', _('Currency'), ':</td>
		<td>';
$result = DB_query("SELECT currabrev FROM currencies");
if(DB_num_rows($result) == 0) {
	prnMsg( _('No currencies are defined yet. Payments cannot be entered until a currency is defined'),'error');
	echo '</td>
		</tr>';
} else {
	include('includes/CurrenciesArray.php');// To get the currency name from the currency code.
	if($_SESSION['PaymentDetail'.$identifier]->SupplierID == '') {
		echo '<select name="Currency" onchange="ReloadForm(UpdateHeader)" required="required">';
		while($myrow=DB_fetch_array($result)) {
			echo '<option ';
			if($_SESSION['PaymentDetail'.$identifier]->Currency == $myrow['currabrev']) {
				echo 'selected="selected" ';
			}
			echo 'value="', $myrow['currabrev'], '">', $CurrencyName[$myrow['currabrev']], '</option>';
		}
		echo '</select>', _('The transaction currency does not need to be the same as the bank account currency'), '</td>
			</tr>';
	} else { /*It is a supplier payment so it must be in the supplier's currency */
		echo '<input name="Currency" type="hidden" value="', $_SESSION['PaymentDetail'.$identifier]->Currency, '" />';
		echo '<select disabled="disabled">';
		while($myrow=DB_fetch_array($result)) {
			echo '<option ';
			if($_SESSION['PaymentDetail'.$identifier]->Currency == $myrow['currabrev']) {
				echo 'selected="selected" ';
			}
			echo 'value="', $myrow['currabrev'], '">', $CurrencyName[$myrow['currabrev']], '</option>';
		}
		echo '</select>';
		echo ' ', _('It is a supplier payment so it should be in the supplier\'s currency'), '</td>
		</tr>';// Sometimes some people pay in a currency different from supplier's document currency (e.g. invoice in euros paid in dollars).

		/*get the default rate from the currency table if it has not been set */
		if(!isset($_POST['ExRate']) OR $_POST['ExRate'] == '') {
			$SQL = "SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['PaymentDetail'.$identifier]->Currency . "'";
			$Result = DB_query($SQL);
			$myrow = DB_fetch_row($Result);
			$_POST['ExRate'] = locale_number_format($myrow[0], 'Variable');
		}// RChacon: Should be for both cases (Bank_payment and supplier_payment) ?
	}
	// RChacon: Set ExRate from the currency table if it has not been set ?
	// RChacon: Set SuggestedExRate from the currency table if it has not been set ?
}

if(!isset($_POST['ExRate'])) {
	$_POST['ExRate']=1;
}

if(!isset($_POST['FunctionalExRate'])) {
	$_POST['FunctionalExRate']=1;
}
if($_SESSION['PaymentDetail'.$identifier]->AccountCurrency != $_SESSION['PaymentDetail'.$identifier]->Currency AND isset($_SESSION['PaymentDetail'.$identifier]->AccountCurrency)) {
	if(isset($SuggestedExRate) AND ($_POST['ExRate'] == 1 OR $_POST['Currency'] != $_POST['PreviousCurrency'] OR $_POST['PreviousBankAccount'] != $_SESSION['PaymentDetail'.$identifier]->Account)) {
		$_POST['ExRate'] = locale_number_format($SuggestedExRate, 'Variable');
	}

	if(isset($SuggestedExRate)) {
		$SuggestedExRateText = '<b>' . _('Suggested rate:') . ' 1 ' . $_SESSION['PaymentDetail'.$identifier]->AccountCurrency . ' = '	. locale_number_format($SuggestedExRate, 'Variable') . ' ' . $_SESSION['PaymentDetail'.$identifier]->Currency . '</b>';
	} else {
		$SuggestedExRateText = '1 ' . $_SESSION['PaymentDetail'.$identifier]->AccountCurrency . ' = ? ' . $_SESSION['PaymentDetail'.$identifier]->Currency;
	}
	echo '<tr>
			<td>', _('Payment Exchange Rate'), ':</td>
			<td><input class="number" maxlength="12" name="ExRate" size="14" title="', _('The exchange rate between the currency of the bank account currency and the currency of the payment'), '" type="text" value="', $_POST['ExRate'], '" /> ', $SuggestedExRateText, ' ', _('The exchange rate between the currency of the bank account currency and the currency of the payment'), '</td>
		</tr>';
}

if($_SESSION['PaymentDetail'.$identifier]->AccountCurrency != $_SESSION['CompanyRecord']['currencydefault'] AND isset($_SESSION['PaymentDetail'.$identifier]->AccountCurrency)) {
	if(isset($SuggestedFunctionalExRate) AND ($_POST['FunctionalExRate']==1 OR $_POST['Currency'] != $_POST['PreviousCurrency'] OR $_POST['PreviousBankAccount'] != $_SESSION['PaymentDetail'.$identifier]->Account)) {
		$_POST['FunctionalExRate'] = locale_number_format($SuggestedFunctionalExRate, 'Variable');
	}

	if(isset($SuggestedFunctionalExRate)) {
		$SuggestedFunctionalExRateText = '<b>' . _('Suggested rate:') . ' 1 ' . $_SESSION['CompanyRecord']['currencydefault'] . ' = ' . locale_number_format($SuggestedFunctionalExRate, 'Variable') . ' ' . $_SESSION['PaymentDetail'.$identifier]->AccountCurrency . '</b>';
	} else {
		$SuggestedFunctionalExRateText = '1 ' . $_SESSION['CompanyRecord']['currencydefault'] . ' = ? ' . $_SESSION['PaymentDetail'.$identifier]->AccountCurrency;
	}
	echo '<tr>
			<td>', _('Functional Exchange Rate'), ':</td>
			<td><input class="number" maxlength="16" name="FunctionalExRate" pattern="[0-9\.,]*" required="required" size="18" type="text" value="', $_POST['FunctionalExRate'], '" /> ', $SuggestedFunctionalExRateText, ' ', _('The exchange rate between the currency of the business (the functional currency) and the currency of the bank account'), '</td>
		</tr>';
}
echo '<tr>
		<td>' . _('Payment type') . ':</td>
		<td><select name="Paymenttype">';

include('includes/GetPaymentMethods.php');
/* The array Payttypes is set up in includes/GetPaymentMethods.php
payment methods can be modified from the setup tab of the main menu under payment methods*/

foreach($PaytTypes as $PaytType) {

	if(isset($_POST['Paymenttype']) AND $_POST['Paymenttype']==$PaytType) {
		echo '<option selected="selected" value="' . $PaytType . '">' . $PaytType . '</option>';
	} else {
		echo '<option value="' . $PaytType . '">' . $PaytType . '</option>';
	}
} //end foreach
echo '</select></td>
	</tr>';

if(!isset($_POST['ChequeNum'])) {
	$_POST['ChequeNum']='';
}
if(!empty($Errors)) {
	$ErrClass = 'class="error"';
} else {
	$ErrClass = '';
}

echo '<tr>
		<td>' . _('Cheque Number') . ':</td>
		<td><input maxlength="8" name="ChequeNum" size="10" type="text" value="' . $_POST['ChequeNum'] . '" ' . $ErrClass . ' /> ' . _('(if using pre-printed stationery)') . '</td>
	</tr>';

// Info to be inserted on `banktrans`.`ref` varchar(50):
if(!isset($_POST['BankTransRef'])) {
	$_POST['BankTransRef'] = '';
}
echo '<tr>
		<td>', _('Reference'), ':</td>
		<td><input maxlength="50" name="BankTransRef" size="52" type="text" value="', stripslashes($_POST['BankTransRef']), '" /> <i>', _('Reference on Bank Transactions Inquiry'), '.</i></td>
	</tr>';

// Info to be inserted on `gltrans`.`narrative` varchar(200):
if(!isset($_POST['Narrative'])) {
	$_POST['Narrative'] = '';
}
if(!isset($_POST['Currency'])) {
	$_POST['Currency'] = '';
}
echo '<tr>
		<td>', _('Narrative'), ':</td>
		<td><input maxlength="200" name="Narrative" size="52" type="text" value="', stripslashes($_POST['Narrative']), '" /> <i>', _('Narrative on General Ledger Account Inquiry'), '.</i></td>
	</tr>';

echo '<tr>
		<td colspan="2"><div class="centre">
			<input name="PreviousCurrency" type="hidden" value="', $_POST['Currency'], '" />
			<input type="hidden" name="PreviousBankAccount" value="' . $_SESSION['PaymentDetail'.$identifier]->Account . '" />
			<input name="UpdateHeader" type="submit" value="', _('Update'), '" />
		</div></td>
	</tr>
	</table>
	<br />';

if($_SESSION['CompanyRecord']['gllink_creditors']==1 AND $_SESSION['PaymentDetail'.$identifier]->SupplierID=='') {
/* Set upthe form for the transaction entry for a GL Payment Analysis item */

	echo '<br /><table class="selection">';
	echo '<tr>
			<th colspan="2"><h3>' . _('General Ledger Payment Analysis Entry') . '</h3></th>
		</tr>
		<tr>
			<td>' . _('Select Tag') . ':</td>
			<td><select name="Tag">';

	$SQL = "SELECT tagref,
				tagdescription
			FROM tags
			ORDER BY tagref";

	$result=DB_query($SQL);
	echo '<option value="0"></option>';
	while($myrow=DB_fetch_array($result)) {
		echo '<option ';
		if(/*isset($_POST['Tag']) AND */$_POST['Tag']==$myrow['tagref']) {
			echo 'selected="selected" ';
		}
		echo 'value="', $myrow['tagref'], '">', $myrow['tagref'], ' - ', $myrow['tagdescription'], '</option>';
	}
	echo '</select></td>
		</tr>';
	// End select Tag

	/*now set up a GLCode field to select from avaialble GL accounts */
	if(isset($_POST['GLManualCode'])) {
		echo '<tr>
				<td>' . _('Enter GL Account Manually') . ':</td>
				<td><input type="text" name="GLManualCode" maxlength="12" size="12" onchange="return inArray(this, GLCode.options,\'' . _('The account code') . ' \' + this.value + \' ' . _('doesnt exist') . '\')" value="'. $_POST['GLManualCode'] .'" /></td>
			</tr>';
	} else {
		echo '<tr>
				<td>' . _('Enter GL Account Manually') . ':</td>
				<td><input type="text" name="GLManualCode" maxlength="12" size="12" onchange="return inArray(this, GLCode.options,\'' . _('The account code') . ' \' + this.value + \' ' . _('doesnt exist') . '\')" /></td>
			</tr>';
	}

	echo '<tr>
			<td>' . _('Select GL Group') . ':</td>
			<td><select name="GLGroup" onchange="return ReloadForm(UpdateCodes)">';

	$SQL = "SELECT groupname
			FROM accountgroups
			ORDER BY sequenceintb";

	$result=DB_query($SQL);
	if(DB_num_rows($result)==0) {
		echo '</select></td>
			</tr>';
		prnMsg(_('No General ledger account groups have been set up yet') . ' - ' . _('payments cannot be analysed against GL accounts until the GL accounts are set up'),'error');
	} else {
		echo '<option value=""></option>';
		while($myrow=DB_fetch_array($result)) {
			if(isset($_POST['GLGroup']) AND ($_POST['GLGroup']==$myrow['groupname'])) {
				echo '<option selected="selected" value="' . $myrow['groupname'] . '">' . $myrow['groupname'] . '</option>';
			} else {
				echo '<option value="' . $myrow['groupname'] . '">' . $myrow['groupname'] . '</option>';
			}
		}
		echo '</select>
				<input type="submit" name="UpdateCodes" value="Select" /></td>
				</tr>';
	}

	if(isset($_POST['GLGroup']) AND $_POST['GLGroup']!='') {
		$SQL = "SELECT chartmaster.accountcode,
						chartmaster.accountname
				FROM chartmaster
					INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" . $_SESSION['UserID'] . "' AND glaccountusers.canupd=1
				WHERE chartmaster.group_='".$_POST['GLGroup']."'
				ORDER BY chartmaster.accountcode";
	} else {
		$SQL = "SELECT chartmaster.accountcode,
						chartmaster.accountname
				FROM chartmaster
					INNER JOIN glaccountusers ON glaccountusers.accountcode=chartmaster.accountcode AND glaccountusers.userid='" . $_SESSION['UserID'] . "' AND glaccountusers.canupd=1
				ORDER BY chartmaster.accountcode";
	}


	echo '<tr>
			<td>' . _('Select GL Account') . ':</td>
			<td><select name="GLCode" onchange="return assignComboToInput(this,'.'GLManualCode'.')">';

	$result=DB_query($SQL);
	if(DB_num_rows($result)==0) {
		echo '</select></td></tr>';
		prnMsg(_('No General ledger accounts have been set up yet') . ' - ' . _('payments cannot be analysed against GL accounts until the GL accounts are set up'),'error');
	} else {
		echo '<option value=""></option>';
		while($myrow=DB_fetch_array($result)) {
			if(isset($_POST['GLCode']) AND $_POST['GLCode']==$myrow['accountcode']) {
				echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . htmlspecialchars($myrow['accountname'],ENT_QUOTES,'UTF-8',false) . '</option>';
			} else {
				echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . htmlspecialchars($myrow['accountname'],ENT_QUOTES,'UTF-8',false) . '</option>';
			}
		}
		echo '</select></td></tr>';
	}

	echo '<tr>
			<td>' . _('Cheque/Voucher Number') . '</td>
			<td><input type="text" name="Cheque" maxlength="12" size="12" /></td>
		</tr>';

	if(isset($_POST['GLNarrative'])) {// General Ledger Payment (Different than Bank Account) info to be inserted on gltrans.narrative, varchar(200).
		echo '<tr>
				<td>' . _('GL Narrative') . ':</td>
				<td><input maxlength="200" name="GLNarrative" size="52" type="text" value="' . stripslashes($_POST['GLNarrative']) . '" /></td>
			</tr>';
	} else {
		echo '<tr>
				<td>' . _('GL Narrative') . ':</td>
				<td><input maxlength="200" name="GLNarrative" size="52" type="text" /></td>
			</tr>';
	}

	if(isset($_POST['GLAmount'])) {
		echo '<tr>
				<td>' . _('Amount') . ' (' . $_SESSION['PaymentDetail'.$identifier]->Currency . '):</td>
				<td><input type="text" required="required" name="GLAmount" maxlength="12" size="12" class="number" value="' . $_POST['GLAmount'] . '" /></td>
			</tr>';
	} else {
		echo '<tr>
				<td>' . _('Amount') . ' (' . $_SESSION['PaymentDetail'.$identifier]->Currency . '):</td>
				<td><input type="text" required="required" name="GLAmount" maxlength="12" size="12" class="number" value="0" /></td>
			</tr>';
	}

	echo '</table><br />';
	echo '<div class="centre">
			<input type="submit" name="Process" value="' . _('Accept') . '" />
			<input type="submit" name="Cancel" value="' . _('Cancel') . '" />
		</div>';

	if(sizeOf($_SESSION['PaymentDetail'.$identifier]->GLItems)>0) {
		echo '<br />
			<table class="selection">
			<tr>
				<th>' . _('Cheque No') . '</th>
				<th>' . _('Amount') . ' (' . $_SESSION['PaymentDetail'.$identifier]->Currency . ')</th>
				<th>' . _('GL Account') . '</th>
				<th>' . _('Narrative') . '</th>
				<th>' . _('Tag') . '</th>
			</tr>';

		$PaymentTotal = 0;
		foreach($_SESSION['PaymentDetail'.$identifier]->GLItems as $PaymentItem) {
			$Tagsql="SELECT tagdescription from tags where tagref='" . $PaymentItem->Tag . "'";
			$TagResult=DB_query($Tagsql);
			$TagMyrow=DB_fetch_row($TagResult);
			if($PaymentItem->Tag==0) {
				$TagName='None';
			} else {
				$TagName=$TagMyrow[0];
			}

			echo '<tr>
				<td>' . $PaymentItem->Cheque . '</td>
				<td class="number">' . locale_number_format($PaymentItem->Amount, $_SESSION['PaymentDetail'.$identifier]->CurrDecimalPlaces) . '</td>
				<td>' . $PaymentItem->GLCode . ' - ' . $PaymentItem->GLActName . '</td>
				<td>' . stripslashes($PaymentItem->Narrative) . '</td>
				<td>' . $PaymentItem->Tag . ' - ' . $TagName . '</td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'] . '?identifier=' . $identifier) . '&amp;Delete=' . $PaymentItem->ID . '" onclick="return confirm(\'' . _('Are you sure you wish to delete this payment analysis item?') . '\');">' . _('Delete') . '</a></td>
				</tr>';
			$PaymentTotal += $PaymentItem->Amount;
		}
		echo '<tr>
				<td></td>
				<td class="number"><b>' . locale_number_format($PaymentTotal, $_SESSION['PaymentDetail'.$identifier]->CurrDecimalPlaces) . '</b></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			</table>
			<br />';
		echo '<div class="centre"><input type="submit" name="CommitBatch" value="' . _('Accept and Process Payment') . '" /></div>';
	}

} else {
/*a supplier is selected or the GL link is not active then set out
the fields for entry of receipt amt and disc */

	echo '<table class="selection">
			<tr>
				<th colspan="2"><h3>', _('Supplier Transactions Payment Entry'), '</h3></th>
			</tr>
			</table>';

	$SQL = "SELECT systypes.typename,
						supptrans.id,
						supptrans.transno,
						supptrans.suppreference,
						supptrans.trandate,
						supptrans.ovamount+supptrans.ovgst+supptrans.diffonexch-supptrans.alloc AS amount
					FROM supptrans
					INNER JOIN systypes
						ON systypes.typeid=supptrans.type
					WHERE settled=0 AND (systypes.typeid=20 OR systypes.typeid=21 OR (systypes.typeid=22 AND (supptrans.ovamount+supptrans.ovgst+supptrans.diffonexch-supptrans.alloc)>0))
						AND supplierno='" . $_SESSION['PaymentDetail'.$identifier]->SupplierID . "'
						AND (supptrans.ovamount+supptrans.ovgst+supptrans.diffonexch-supptrans.alloc)<>0";
		$Result = DB_query($SQL);

		echo '<table class="selection">
				<tr>
					<th class="Ascending">' . _('Date') . '</th>
					<th class="Ascending">' . _('Transaction Type') . '</th>
					<th class="Ascending">' . _('Transaction Number') . '</th>
					<th class="Ascending">' . _('Reference') . '</th>
					<th class="Ascending">' . _('Amount') . '</th>
					<th class="Ascending">' . _('This time to pay') . '</th>
				</tr>';
		$ids = '';
		while ($myrow = DB_fetch_array($Result)) {
			$ids .= $i>0?';' . $myrow['id']:$myrow['id'];
			if(!isset($_POST['paid'.$myrow['id']])) {
				$_POST['paid'.$myrow['id']] = 0;
			}
			echo '<tr>
					<td>' . ConvertSQLDate($myrow['trandate']) . '</td>
					<td>' . $myrow['typename'] . '</td>
					<td>' . $myrow['transno'] . '</td>
					<td>' . $myrow['suppreference'] . '</td>
					<td class="number">' . locale_number_format($myrow['amount'], $_SESSION['PaymentDetail'.$identifier]->CurrDecimalPlaces) . '</td>
					<td><input onclick="AddAmount(this,'.$myrow['id'].');" type="checkbox" name="check'.$myrow['id'] . '" value="' . $myrow['amount'] . '" />' . _('Pay') . '</td>
					<td><input type="text" class="number" id="'.$myrow['id'].'" name="paid'.$myrow['id'].'" value="'.$_POST['paid'.$myrow['id']].'" />
					<input type="hidden" name="remainamt'.$myrow['id'] .'" value="'.$myrow['amount'].'" />
					</td>
					</tr>';
			$i++;
		}//end of while loop;
		echo '</table>
				<p>' . _('Total Input Amount').':<input type="text" id="ttl" value="0" readonly>' .' <input type="button" data-ids="' . $ids . '" value="' . _('Update') . '" onclick="update1(\''.$ids.'\')" id="update" /></p>
				<table class="selection">';

	// If the script was called with a SupplierID, it allows to input a customised gltrans.narrative, supptrans.suppreference and supptrans.transtext:
	// Info to be inserted on `gltrans`.`narrative` varchar(200):
	if(!isset($_POST['gltrans_narrative'])) {
		$_POST['gltrans_narrative'] = '';
	}
	echo '<tr>
			<td>', _('Supplier Narrative'), ':</td>
			<td><input class="text" maxlength="200" name="gltrans_narrative" size="52" type="text" value="', stripslashes($_POST['gltrans_narrative']), '" /> ', _('Supplier narrative in general ledger transactions. If blank, it uses the bank narrative.'), '</td>
		</tr>';
	// Info to be inserted on `supptrans`.`suppreference` varchar(20):
	if(!isset($_POST['supptrans_suppreference'])) {
		$_POST['supptrans_suppreference'] = '';
	}
	echo '<tr>
			<td>', _('Supplier Reference'), ':</td>
			<td><input class="text" maxlength="20" name="supptrans_suppreference" size="22" type="text" value="', stripslashes($_POST['supptrans_suppreference']), '" /> ', _('Supplier reference in supplier transactions. If blank, it uses the payment type.'), '</td>
		</tr>';
	// Info to be inserted on `supptrans`.`transtext` text:
	if(!isset($_POST['supptrans_transtext'])) {
		$_POST['supptrans_transtext'] = '';
	}
	echo '<tr>
			<td>', _('Transaction Text'), ':</td>
			<td><input class="text" maxlength="200" name="supptrans_transtext" size="52" type="text" value="', stripslashes($_POST['supptrans_transtext']), '" /> ', _('Transaction text in supplier transactions. If blank, it uses the bank narrative.'), '</td>
		</tr>
		<tr>
			<td>',
				_('Amount of Payment'), ' ', $_SESSION['PaymentDetail'.$identifier]->Currency, ':</td>
			<td><input class="number" maxlength="12" id="Amount" name="Amount" size="13" type="text" value="', $_SESSION['PaymentDetail'.$identifier]->Amount, '" /></td>
		</tr>
		<tr>
			<td><input name="SuppName" type="hidden" value="', $_SESSION['PaymentDetail'.$identifier]->SuppName, '" />',
				_('Amount of Discount'), ' ', $_SESSION['PaymentDetail'.$identifier]->Currency, ':</td>
			<td><input class="number" maxlength="12" name="Discount" size="13" type="text" value="', $_SESSION['PaymentDetail'.$identifier]->Discount, '" /></td>
		</tr>
	</table>
	<br />
	<div class="centre"><input type="submit" name="CommitBatch" onClick=payVerify("Amount","ttl") value="' . _('Accept and Process Payment') . '" /></div>';
}
echo '</div>
	</form>';

include('includes/footer.php');
?>
<?php


/*The ImportBankTransClass contains the structure ofinformation about the transactions
An array of class BankTrans objects - containing details of the bank transactions has an array of
GLEntries objects to hold the GL analysis for each transaction */

include('includes/DefineImportBankTransClass.php');

/* Session started in header.php for password checking and authorisation level check */
include('includes/session.php');

$Title = _('Imported Bank Transaction General Ledger Analysis');

include('includes/header.php');

if (!isset($_SESSION['Trans'])){
	prnMsg(_('This page can only be called from the importation of bank transactions page which sets up the data to receive the analysed general ledger entries'),'info');
	echo '<br /><a href="' . $RootPath . '/ImportBankTrans.php">' . _('Import Bank Transactions') . '</a>';
	include('includes/footer.php');
	exit;
	/*It all stops here if there aint no bank transactions being imported i.e. $_SESSION['Trans'] has not been initiated
	 * */
}

if (isset($_GET['TransID'])){
	$TransID = $_GET['TransID'];
} else {
	$TransID = $_POST['TransID'];
}
if (!isset($TransID)){
	prnMsg(_('This page can only be called from the importation of bank transactions page which sets up the data to receive the analysed general ledger entries'),'info');
	echo '<br /><a href="' . $RootPath . '/ImportBankTrans.php">' . _('Import Bank Transactions') . '</a>';
	include('includes/footer.php');
	exit;
}

if ($_SESSION['Trans'][$TransID]->BankTransID != 0) {
	prnMsg(_('This transaction appears to be already entered against this bank account. By entering values in this analysis form the transaction will be entered again. Only proceed to analyse this transaction if you are sure it has not already been processed'),'warn');
	echo '<br /><div class="centre"><a href="' . $RootPath . '/ImportBankTrans.php">' . _('Back to Main Import Screen - Recommended') . '</a></div>';

}

if (isset($_POST['DebtorNo'])){
	$_SESSION['Trans'][$TransID]->DebtorNo = $_POST['DebtorNo'];
}
if (isset($_POST['SupplierID'])){
	$_SESSION['Trans'][$TransID]->SupplierID = $_POST['SupplierID'];
}
/*If the user hit the Add to transaction button then process this first before showing  all GL codes on the transaction otherwise it wouldnt show the latest addition*/

if (isset($_POST['AddGLCodeToTrans']) AND $_POST['AddGLCodeToTrans'] == _('Enter GL Line')){

	$InputError = False;
	if ($_POST['GLCode'] == ''){
		$_POST['GLCode'] = $_POST['AcctSelection'];
	}

	if ($_POST['GLCode'] == ''){
		prnMsg( _('You must select a general ledger code from the list below') ,'warn');
		$InputError = True;
	}

	$sql = "SELECT accountcode,
					accountname
				FROM chartmaster
				WHERE accountcode='" . $_POST['GLCode'] . "'";
	$result = DB_query($sql);
	if (DB_num_rows($result) == 0 AND $_POST['GLCode'] != ''){
		prnMsg(_('The account code entered is not a valid code') . '. ' . _('This line cannot be added to the transaction') . '.<br />' . _('You can use the selection box to select the account you want'),'error');
		$InputError = True;
	} else if ($_POST['GLCode'] != '') {
		$myrow = DB_fetch_row($result);
		$GLActName = $myrow[1];
		if (!is_numeric($_POST['Amount'])){
			prnMsg( _('The amount entered is not numeric') . '. ' . _('This line cannot be added to the transaction'),'error');
			$InputError = True;
		}
	}

	if ($InputError == False){

		$_SESSION['Trans'][$TransID]->Add_To_GLAnalysis($_POST['Amount'],
														$_POST['Narrative'],
														$_POST['GLCode'],
														$GLActName,
														$_POST['GLTag'] );
		unset($_POST['GLCode']);
		unset($_POST['Amount']);
		unset($_POST['Narrative']);
		unset($_POST['AcctSelection']);
		unset($_POST['GLTag']);
	}
}

if (isset($_GET['Delete'])){
	$_SESSION['Trans'][$TransID]->Remove_GLEntry($_GET['Delete']);
}

if (isset($_GET['Edit'])){
	$_POST['GLCode'] = $_SESSION['Trans'][$TransID]->GLEntries[$_GET['Edit']]->GLCode;
	$_POST['AcctSelection']= $_SESSION['Trans'][$TransID]->GLEntries[$_GET['Edit']]->GLCode;
	$_POST['Amount'] = $_SESSION['Trans'][$TransID]->GLEntries[$_GET['Edit']]->Amount;
	$_POST['GLTag'] = $_SESSION['Trans'][$TransID]->GLEntries[$_GET['Edit']]->Tag;
	$_POST['Narrative'] = $_SESSION['Trans'][$TransID]->GLEntries[$_GET['Edit']]->Narrative;
	$_SESSION['Trans'][$TransID]->Remove_GLEntry($_GET['Edit']);
}

/*Show all the selected GLEntries so far from the $_SESSION['Trans'][$TransID]->GLEntries array */
if ($_SESSION['Trans'][$TransID]->Amount >= 0){ //its a receipt
	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/transactions.png" title="' . _('Bank Account Transaction Analysis') . '" alt="" />' . ' '
	. _('Imported Bank Receipt of') . ' ' . $_SESSION['Trans'][$TransID]->Amount . ' ' .  $_SESSION['Statement']->CurrCode . ' ' . _('dated') . ': ' . $_SESSION['Trans'][$TransID]->ValueDate . '<br /> ' . $_SESSION['Trans'][$TransID]->Description;
} else {
	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/transactions.png" title="' . _('Bank Account Transaction Analysis') . '" alt="" />' . ' '
	. _('Imported Bank Payment of') . ' ' . $_SESSION['Trans'][$TransID]->Amount . ' ' . $_SESSION['Statement']->CurrCode . ' ' ._('dated') . ': ' . $_SESSION['Trans'][$TransID]->ValueDate . '<br /> ' . $_SESSION['Trans'][$TransID]->Description;
}

/*Set up a form to allow input of new GL entries */
echo '</p><form name="form1" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<input type="hidden" name="TransID" value=' . $TransID . ' />';

echo '<div class="centre"><a href="' . $RootPath . '/ImportBankTrans.php" onclick="return confirm(\'' . _('If you have entered a GL analysis check that the sum of GL Entries agrees to the total bank transaction. If it does not then the bank transaction import will not be processed.') . '\');">' . _('Back to Main Import Screen') . '</a></div>';

echo '<br /><table cellpadding="2" class="selection">';

$AllowGLAnalysis = true;

if ($_SESSION['Trans'][$TransID]->Amount<0){ //its a payment
	echo '<tr>
			<td>' . _('Payment to Supplier Account') . ':</td>
			<td><select name="SupplierID" onChange="ReloadForm(form1.Update)">';

	$result = DB_query("SELECT supplierid,
								suppname
						FROM suppliers
						WHERE currcode='" . $_SESSION['Statement']->CurrCode . "'
						ORDER BY suppname");
	if ($_SESSION['Trans'][$TransID]->SupplierID ==''){
		echo '<option selected value="">' . _('GL Payment') . '</option>';
	} else {
		echo '<option value="">' . _('GL Payment') . '</option>';
	}
	while ($myrow = DB_fetch_array($result)){
		if ($myrow['supplierid']==$_SESSION['Trans'][$TransID]->SupplierID){
			echo '<option selected value="' . $myrow['supplierid'] . '">' . $myrow['supplierid'] . ' - ' . $myrow['suppname'] . '</option>';
		} else {
			echo '<option value="' . $myrow['supplierid'] . '">' . $myrow['supplierid'] .' - ' .  $myrow['suppname'] . '</option>';
		}
	}
	echo '</select></td>
			<td><input type="submit" name="Update" value="' . _('Update') . '" /></td>
		</tr>';
	if ($_SESSION['Trans'][$TransID]->SupplierID==''){
		$AllowGLAnalysis = true;
	} else {
		$AllowGLAnalysis = false;
	}
	echo '</table>';
} else { //its a receipt
	echo '<tr>
			<td>' . _('Receipt to Customer Account') . ':</td>
			<td><select name="DebtorNo" onChange="ReloadForm(form1.Update)">';

	$result = DB_query("SELECT debtorno,
								name
						FROM debtorsmaster
						WHERE currcode='" . $_SESSION['Statement']->CurrCode . "'
						ORDER BY name");
	if ($_SESSION['Trans'][$TransID]->DebtorNo ==''){
		echo '<option selected value="">' . _('GL Receipt') . '</option>';
	} else {
		echo '<option value="">' . _('GL Receipt') . '</option>';
	}
	while ($myrow = DB_fetch_array($result)){
		if ($myrow['debtorno']==$_SESSION['Trans'][$TransID]->DebtorNo){
			echo '<option selected value="' . $myrow['debtorno'] . '">' . $myrow['debtorno'] . ' - ' . $myrow['name'] . '</option>';
		} else {
			echo '<option value="' . $myrow['debtorno'] . '">' . $myrow['debtorno'] . ' - ' . $myrow['name'] . '</option>';
		}
	}
	echo '</select></td>
			<td><input type="submit" name="Update" value="' . _('Update') . '" /></td>
			</tr>';
	if ($_SESSION['Trans'][$TransID]->DebtorNo==''){
		$AllowGLAnalysis = true;
	} else {
		$AllowGLAnalysis = false;
	}
	echo '</table>';
}

if ($AllowGLAnalysis==false){
	/*clear any existing GLEntries */
	foreach ($_SESSION['Trans'][$TransID]->GLEntries AS $GLAnalysisLine) {
		$_SESSION['Trans'][$TransID]->Remove_GLEntry($GLAnalysisLine->ID);
	}
} else { /*Allow GL Analysis == true */
	echo '</p><table cellpadding="2" class="selection">
			<thead>
				<tr>
					<th colspan="5">' . _('General ledger Analysis') . '</th>
				</tr>
				<tr>
					<th class="ascending">' . _('Account') . '</th>
					<th class="ascending">' . _('Name') . '</th>
					<th class="ascending">' . _('Amount') . '<br />' . _('in') . ' ' . $_SESSION['Statement']->CurrCode . '</th>
					<th>' . _('Narrative') . '</th>
					<th class="ascending">' . _('Tag') . '</th>
				</tr>
			</thead>
			<tbody>';

	$TotalGLValue=0;

	foreach ( $_SESSION['Trans'][$TransID]->GLEntries AS $EnteredGLCode){

		echo '<tr>
			<td>' . $EnteredGLCode->GLCode . '</td>
			<td>' . $EnteredGLCode->GLAccountName . '</td>
			<td class=number>' . locale_number_format($EnteredGLCode->Amount,$_SESSION['Statement']->CurrDecimalPlaces) . '</td>
			<td>' . $EnteredGLCode->Narrative . '</td>
			<td>' . $EnteredGLCode->Tag . '</td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Edit=' . $EnteredGLCode->ID . '&amp;TransID=' . $TransID . '">' . _('Edit') . '</a></td>
			<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=' . $EnteredGLCode->ID . '&amp;TransID=' . $TransID . '">' . _('Delete') . '</a></td>
			</tr>';

		$TotalGLValue += $EnteredGLCode->Amount;
	}

	echo '</tbody>
		<tfoot>
		<tr>
			<td colspan="2" class="number">' . _('Total of GL Entries') . ':</td>
			<td class="number">' . locale_number_format($TotalGLValue,$_SESSION['Statement']->CurrDecimalPlaces) . '</td>
		</tr>
		<tr>
			<td colspan="2" class="number">' . _('Total Bank Transaction') . ':</td>
			<td class="number">' . locale_number_format($_SESSION['Trans'][$TransID]->Amount,$_SESSION['Statement']->CurrDecimalPlaces) . '</td>
		</tr>
		<tr>';

	if (($_SESSION['Trans'][$TransID]->Amount - $TotalGLValue)!=0) {
		echo '<td colspan="2" class="number">' . _('Yet To Enter') . ':</font></td>
		<td class="number"><font size="4" color="red">' . locale_number_format($_SESSION['Trans'][$TransID]->Amount-$TotalGLValue,$_SESSION['Statement']->CurrDecimalPlaces) . '</td>';
	} else {
		echo '<th colspan="5"><font size="4" color="green">' . _('Reconciled') . '</th>';
	}
	echo '</tr>
		</tfoot>
		</table>';


	echo '<br />
		<table class="selection">';
	if (!isset($_POST['GLCode'])) {
		$_POST['GLCode']='';
	}
	echo '<tr>
			<td>' . _('Account Code') . ':</td>
			<td><input type="text" name="GLCode" size="12" maxlength="11" value="' .  $_POST['GLCode'] . '"></td>
		</tr>';
	echo '<tr>
			<td>' . _('Account Selection') . ':<br />(' . _('If you know the code enter it above') . '<br />' . _('otherwise select the account from the list') . ')</td>
			<td><select name="AcctSelection">';

	$result = DB_query("SELECT accountcode, accountname FROM chartmaster ORDER BY accountcode");
	echo '<option value=""></option>';
	while ($myrow = DB_fetch_array($result)) {
		if ($myrow['accountcode'] == $_POST['AcctSelection']) {
			echo '<option selected value="';
		} else {
			echo '<option value="';
		}
		echo $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . $myrow['accountname'] . '</option>';
	}

	echo '</select>
		</td>
		</tr>';
	if (!isset($_POST['Amount'])) {
		$_POST['Amount']=0;
	}
	echo '<tr>
			<td>' . _('Amount') . ':</td>
			<td><input type="text" class="number" name="Amount" required="required" size="12" maxlength="11" value="' .  locale_number_format($_POST['Amount'],$_SESSION['Statement']->CurrDecimalPlaces) . '"></td>
		</tr>';

	if (!isset($_POST['Narrative'])) {
		$_POST['Narrative']='';
	}
	echo '<tr>
		<td>' . _('Narrative') . ':</td>
		<td><textarea name="Narrative" cols=40 rows=2>' .  $_POST['Narrative'] . '</textarea></td>
		</tr>';

	//Select the tag
	echo '<tr><td>' . _('Tag') . '</td>
			<td><select name="GLTag">';

	$SQL = "SELECT tagref,
					tagdescription
			FROM tags
			ORDER BY tagref";

	$result=DB_query($SQL);
	echo '<option value="0">0 - ' . _('None') . '</option>';
	while ($myrow=DB_fetch_array($result)){
		if (isset($_POST['tag']) and $_POST['tag']==$myrow['tagref']){
			echo '<option selected value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
		} else {
			echo '<option value="' . $myrow['tagref'] . '">' . $myrow['tagref'].' - ' .$myrow['tagdescription'] . '</option>';
		}
	}
	echo '</select></td>
		</tr>
		</table><br />';

	echo '<div class="centre"><input type="submit" name="AddGLCodeToTrans" value="' . _('Enter GL Line') . '"></div>';
}
echo '</form>';
include('includes/footer.php');
?>

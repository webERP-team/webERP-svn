<?php


/*The credit selection screen uses the Cart class used for the making up orders
some of the variable names refer to order - please think credit when you read order */

include('includes/DefineCartClass.php');
include('includes/DefineSerialItems.php');
/* Session started in session.php for password checking and authorisation level check */
include('includes/session.php');

$Title = _('Create Credit Note');
$ViewTopic= 'ARTransactions';
$BookMark = 'CreateCreditNote';

include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/GetSalesTransGLCodes.inc');
include('includes/GetPrice.inc');


if (empty($_GET['identifier'])) {
	/*unique session identifier to ensure that there is no conflict with other order entry sessions on the same machine  */
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}

if (isset($_POST['ProcessCredit']) AND !isset($_SESSION['CreditItems'.$identifier])){
	prnMsg(_('This credit note has already been processed. Refreshing the page will not enter the credit note again') . '<br />' . _('Please use the navigation links provided rather than using the browser back button and then having to refresh'),'info');
	echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a>';
	include('includes/footer.php');
  exit;
}

if (isset($_GET['NewCredit'])){
/*New credit note entry - clear any existing credit note details from the Items object and initiate a newy*/
	if (isset($_SESSION['CreditItems'.$identifier])){
		unset ($_SESSION['CreditItems'.$identifier]->LineItems);
		unset ($_SESSION['CreditItems'.$identifier]);
	}
}


if (!isset($_SESSION['CreditItems'.$identifier])){
	 /* It must be a new credit note being created $_SESSION['CreditItems'.$identifier] would be set up from a previous call*/

	$_SESSION['CreditItems'.$identifier] = new cart;

	$_SESSION['RequireCustomerSelection'] = 1;
}

if (isset($_POST['ChangeCustomer'])){
	$_SESSION['RequireCustomerSelection']=1;
}

if (isset($_POST['Quick'])){
	unset($_POST['PartSearch']);
}

if (isset($_POST['CancelCredit'])) {
	unset($_SESSION['CreditItems'.$identifier]->LineItems);
	unset($_SESSION['CreditItems'.$identifier]);
	$_SESSION['CreditItems'.$identifier] = new cart;
	$_SESSION['RequireCustomerSelection'] = 1;
}


if (isset($_POST['SearchCust']) AND $_SESSION['RequireCustomerSelection']==1){

	if ($_POST['Keywords'] AND $_POST['CustCode']) {
		  prnMsg( _('Customer name keywords have been used in preference to the customer code extract entered'), 'info' );
	}
	if ($_POST['Keywords']=='' AND $_POST['CustCode']=='') {
		  prnMsg( _('At least one Customer Name keyword OR an extract of a Customer Code must be entered for the search'), 'info' );
	} else {
		if (mb_strlen($_POST['Keywords'])>0) {
		  //insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			   $SQL = "SELECT	debtorsmaster.name,
								custbranch.debtorno,
								custbranch.brname,
								custbranch.contactname,
								custbranch.phoneno,
								custbranch.faxno,
								custbranch.branchcode
							FROM custbranch
							INNER JOIN debtorsmaster
							ON custbranch.debtorno=debtorsmaster.debtorno
							WHERE custbranch.brname " . LIKE  . " '" . $SearchString . "'
							AND custbranch.disabletrans='0'";

		  } elseif (mb_strlen($_POST['CustCode'])>0){

			   $SQL = "SELECT 	debtorsmaster.name,
								custbranch.debtorno,
								custbranch.brname,
								custbranch.contactname,
								custbranch.phoneno,
								custbranch.faxno,
								custbranch.branchcode
							FROM custbranch
							INNER JOIN debtorsmaster
							ON custbranch.debtorno=debtorsmaster.debtorno
							WHERE custbranch.debtorno " . LIKE  . "'%" . $_POST['CustCode'] . "%'
							AND custbranch.disabletrans='0'";
		  }

		  $ErrMsg = _('Customer branch records requested cannot be retrieved because');
		  $DbgMsg = _('SQL used to retrieve the customer details was');
		  $result_CustSelect = DB_query($SQL,$ErrMsg,$DbgMsg);


		  if (DB_num_rows($result_CustSelect)==1){
			    $myrow=DB_fetch_array($result_CustSelect);
			    $SelectedCustomer = trim($myrow['debtorno']);
			    $SelectedBranch = trim($myrow['branchcode']);
			    $_POST['JustSelectedACustomer'] = true;
		  } elseif (DB_num_rows($result_CustSelect)==0){
			    prnMsg(_('Sorry') . ' ... ' . _('there are no customer branch records contain the selected text') . ' - ' . _('please alter your search criteria and try again'),'info');
		  }

	 } /*one of keywords or custcode was more than a zero length string */
} /*end of if search button for customers was hit*/


if (isset($_POST['JustSelectedACustomer']) AND !isset($SelectedCustomer)){
	/*Need to figure out the number of the form variable that the user clicked on */
	for ($i=1; $i < count($_POST); $i++){ //loop through the returned customers
		if(isset($_POST['SubmitCustomerSelection'.$i])){
			break;
		}
	}
	if ($i==count($_POST)){
		prnMsg(_('Unable to identify the selected customer'),'error');
	} else {
		$SelectedCustomer = trim($_POST['SelectedCustomer'.$i]);
		$SelectedBranch = trim($_POST['SelectedBranch'.$i]);
	}
}


if (isset($SelectedCustomer) AND isset($_POST['JustSelectedACustomer'])) {

/*will only be true if page called from customer selection form
  Now retrieve customer information - name, salestype, currency, terms etc
*/

	$_SESSION['CreditItems'.$identifier]->DebtorNo = $SelectedCustomer;
	$_SESSION['CreditItems'.$identifier]->Branch = $SelectedBranch;
	$_SESSION['RequireCustomerSelection'] = 0;

/*  default the branch information from the customer branches table CustBranch -particularly where the stock
will be booked back into. */

	 $sql = "SELECT debtorsmaster.name,
					debtorsmaster.salestype,
					debtorsmaster.currcode,
					currencies.rate,
					currencies.decimalplaces,
					custbranch.brname,
					custbranch.braddress1,
					custbranch.braddress2,
					custbranch.braddress3,
					custbranch.braddress4,
					custbranch.braddress5,
					custbranch.braddress6,
					custbranch.phoneno,
					custbranch.email,
					custbranch.salesman,
					custbranch.defaultlocation,
					custbranch.taxgroupid,
					locations.taxprovinceid
				FROM custbranch
				INNER JOIN locations ON locations.loccode=custbranch.defaultlocation
				INNER JOIN debtorsmaster ON custbranch.debtorno=debtorsmaster.debtorno
				INNER JOIN currencies ON debtorsmaster.currcode=currencies.currabrev
				WHERE custbranch.branchcode='" . $_SESSION['CreditItems'.$identifier]->Branch . "'
				AND custbranch.debtorno = '" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "'";

	$ErrMsg = _('The customer branch record of the customer selected') . ': ' . $SelectedCustomer . ' ' . _('cannot be retrieved because');
	$DbgMsg =  _('SQL used to retrieve the branch details was');
	$result =DB_query($sql,$ErrMsg,$DbgMsg);

	$myrow = DB_fetch_array($result);

/* the sales type determines the price list to be used by default the customer of the user is
defaulted from the entry of the userid and password.  */
	$_SESSION['CreditItems'.$identifier]->CustomerName = $myrow['name'];
	$_SESSION['CreditItems'.$identifier]->DefaultSalesType = $myrow['salestype'];
	$_SESSION['CreditItems'.$identifier]->DefaultCurrency = $myrow['currcode'];
	$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces = $myrow['decimalplaces'];
	$_SESSION['CurrencyRate'] = $myrow['rate'];
	$_SESSION['CreditItems'.$identifier]->DeliverTo = $myrow['brname'];
	$_SESSION['CreditItems'.$identifier]->BrAdd1 = $myrow['braddress1'];
	$_SESSION['CreditItems'.$identifier]->BrAdd2 = $myrow['braddress2'];
	$_SESSION['CreditItems'.$identifier]->BrAdd3 = $myrow['braddress3'];
	$_SESSION['CreditItems'.$identifier]->BrAdd4 = $myrow['braddress4'];
	$_SESSION['CreditItems'.$identifier]->BrAdd5 = $myrow['braddress5'];
	$_SESSION['CreditItems'.$identifier]->BrAdd6 = $myrow['braddress6'];
	$_SESSION['CreditItems'.$identifier]->PhoneNo = $myrow['phoneno'];
	$_SESSION['CreditItems'.$identifier]->Email = $myrow['email'];
	$_SESSION['CreditItems'.$identifier]->SalesPerson = $myrow['salesman'];
	$_SESSION['CreditItems'.$identifier]->Location = $myrow['defaultlocation'];
	$_SESSION['CreditItems'.$identifier]->TaxGroup = $myrow['taxgroupid'];
	$_SESSION['CreditItems'.$identifier]->DispatchTaxProvince = $myrow['taxprovinceid'];
	$_SESSION['CreditItems'.$identifier]->GetFreightTaxes();
}

/* if the change customer button hit or the customer has not already been selected */
if ($_SESSION['RequireCustomerSelection'] ==1
	OR !isset($_SESSION['CreditItems'.$identifier]->DebtorNo)
	OR $_SESSION['CreditItems'.$identifier]->DebtorNo=='' ) {

	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier .  '" method="post">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/magnifier.png" title="' .
		_('Search') . '" alt="" />' . ' ' . _('Select Customer For Credit Note') . '</p>';

	echo '<table cellpadding="3" class="selection">';
	echo '<tr><th colspan="5"><h3> ' . _('Customer Selection')  . '</h3></th></tr>';
	echo '<tr>
			<td>' . _('Enter text in the customer name') . ':</td>
			<td><input type="text" name="Keywords" size="20" maxlength="25" /></td>
			<td><b>' . _('OR') . '</b></td>
			<td>' . _('Enter text extract in the customer code') . ':</td>
			<td><input type="text" name="CustCode" size="15" maxlength="18" /></td>
		</tr>';
	echo '</table>
		<br />
		<div class="centre">
			<input type="submit" name="SearchCust" value="' . _('Search Now') . '" />
		</div>';

	if (isset($result_CustSelect)) {

		echo '<br /><table cellpadding="2">';

		$TableHeader = '<tr>
						<th>' . _('Customer') . '</th>
						<th>' . _('Branch') . '</th>
						<th>' . _('Contact') . '</th>
						<th>' . _('Phone') . '</th>
						<th>' . _('Fax') . '</th>
					</tr>';
		echo $TableHeader;

		$j = 1;
		$LastCustomer='';
		while ($myrow=DB_fetch_array($result_CustSelect)) {

			if ($LastCustomer != $myrow['name']) {
				echo '<td>' . $myrow['name'] . '</td>';
			} else {
				echo '<td></td>';
			}
			echo '<tr class="striped_row">
				<td><input tabindex="'.($j+5).'" type="submit" name="SubmitCustomerSelection' . $j .'" value="' . htmlspecialchars($myrow['brname'], ENT_QUOTES,'UTF-8'). '" />
				<input type="hidden" name="SelectedCustomer' . $j .'" value="'.$myrow['debtorno'].'" />
				<input type="hidden" name="SelectedBranch' . $j .'" value="'. $myrow['branchcode'].'" /></td>
				<td>' . $myrow['contactname'] . '</td>
				<td>' . $myrow['phoneno'] . '</td>
				<td>' . $myrow['faxno'] . '</td>
				</tr>';
			$LastCustomer=$myrow['name'];
			$j++;
		//end of page full new headings if
		} //end of while loop
		echo '</table><input type="hidden" name="JustSelectedACustomer" value="Yes" />';
	}//end if results to show
    echo '</div>
          </form>';


//end if RequireCustomerSelection
} else {
/* everything below here only do if a customer is selected
   first add a header to show who we are making a credit note for */

	echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/magnifier.png" title="' .
		_('Search') . '" alt="" />' . ' ' . $_SESSION['CreditItems'.$identifier]->CustomerName  . ' - ' . $_SESSION['CreditItems'.$identifier]->DeliverTo . '</p>';

	if (isset($_POST['SalesPerson'])){
		$_SESSION['CreditItems' . $identifier]->SalesPerson = $_POST['SalesPerson'];
	}

 /* do the search for parts that might be being looked up to add to the credit note */
	 if (isset($_POST['Search'])){

		  if ($_POST['Keywords']!='' AND $_POST['StockCode']!='') {
			   prnMsg( _('Stock description keywords have been used in preference to the Stock code extract entered') . '.', 'info' );
		  }

		if ($_POST['Keywords']!='') {
			//insert wildcard characters in spaces
			$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

			if ($_POST['StockCat']=='All'){
				$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					ORDER BY stockmaster.stockid";
			} else {
				$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					ORDER BY stockmaster.stockid";
			}

		} elseif ($_POST['StockCode']!=''){
			$SearchString = '%' . $_POST['StockCode'] . '%';
			if ($_POST['StockCat']=='All'){
				$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND  stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					ORDER BY stockmaster.stockid";
			} else {
				$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
						FROM stockmaster INNER JOIN stockcategory
						ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
						AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
						AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
						GROUP BY stockmaster.stockid,
							stockmaster.description,
							stockmaster.units
						ORDER BY stockmaster.stockid";
			}
		} else {
			if ($_POST['StockCat']=='All'){
				$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					ORDER BY stockmaster.stockid";
			} else {
				$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					GROUP BY stockmaster.stockid,
						stockmaster.description,
						stockmaster.units
					ORDER BY stockmaster.stockid";
			  }
		}

		$ErrMsg = _('There is a problem selecting the part records to display because');
		$SearchResult = DB_query($SQL,$ErrMsg);

		if (DB_num_rows($SearchResult)==0){
			   prnMsg(_('There are no products available that match the criteria specified'),'info');
			   if ($debug==1){
				    prnMsg(_('The SQL statement used was') . ':<br />' . $SQL,'info');
			   }
		}
		if (DB_num_rows($SearchResult)==1){
			   $myrow=DB_fetch_array($SearchResult);
			   $_POST['NewItem'] = $myrow['stockid'];
			   DB_data_seek($SearchResult,0);
		}

	 } //end of if search for parts to add to the credit note

/*Always do the stuff below if not looking for a customerid
  Set up the form for the credit note display and  entry*/

	 echo '<form id="MainForm" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier . '" method="post">
		<div>
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


/*Process Quick Entry */

	 if (isset($_POST['QuickEntry'])){
/* get the item details from the database and hold them in the cart object make the quantity 1 by default then add it to the cart */
	    $i=1;
	     do {
		   do {
			  $QuickEntryCode = 'part_' . $i;
			  $QuickEntryQty = 'qty_' . $i;
			  $i++;
		   } while (!is_numeric(filter_number_format($_POST[$QuickEntryQty]))
					AND filter_number_format($_POST[$QuickEntryQty]) <=0
					AND mb_strlen($_POST[$QuickEntryCode])!=0
					AND $i<=$QuickEntires);

		   $_POST['NewItem'] = trim($_POST[$QuickEntryCode]);
		   $NewItemQty = filter_number_format($_POST[$QuickEntryQty]);

		   if (mb_strlen($_POST['NewItem'])==0){
			     break;	 /* break out of the loop if nothing in the quick entry fields*/
		   }

		   $AlreadyOnThisCredit =0;

		   foreach ($_SESSION['CreditItems'.$identifier]->LineItems AS $OrderItem) {

		   /* do a loop round the items on the credit note to see that the item
		   is not already on this credit note */

			    if ($_SESSION['SO_AllowSameItemMultipleTimes']==0 AND strcasecmp($OrderItem->StockID, $_POST['NewItem']) == 0) {
				     $AlreadyOnThisCredit = 1;
				     prnMsg($_POST['NewItem'] . ' ' . _('is already on this credit - the system will not allow the same item on the credit note more than once. However you can change the quantity credited of the existing line if necessary'),'warn');
			    }
		   } /* end of the foreach loop to look for preexisting items of the same code */

		   if ($AlreadyOnThisCredit!=1){

			    $sql = "SELECT stockmaster.description,
								stockmaster.longdescription,
					    		stockmaster.stockid,
								stockmaster.units,
								stockmaster.volume,
								stockmaster.grossweight,
								(materialcost+labourcost+overheadcost) AS standardcost,
								stockmaster.mbflag,
								stockmaster.decimalplaces,
								stockmaster.controlled,
								stockmaster.serialised,
								stockmaster.discountcategory,
								stockmaster.taxcatid
							FROM stockmaster
							WHERE  stockmaster.stockid = '". $_POST['NewItem'] . "'";

				$ErrMsg =  _('There is a problem selecting the part because');
				$result1 = DB_query($sql,$ErrMsg);

		   		if ($myrow = DB_fetch_array($result1)){

					$LineNumber = $_SESSION['CreditItems'.$identifier]->LineCounter;

					if ($_SESSION['CreditItems'.$identifier]->add_to_cart ($myrow['stockid'],
																			$NewItemQty,
																			$myrow['description'],
																			$myrow['longdescription'],
																			GetPrice ($_POST['NewItem'],
																			$_SESSION['CreditItems'.$identifier]->DebtorNo,
																			$_SESSION['CreditItems'.$identifier]->Branch),
																			0,
																			$myrow['units'],
																			$myrow['volume'],
																			$myrow['grossweight'],
																			0,
																			$myrow['mbflag'],
																			Date($_SESSION['DefaultDateFormat']),
																			0,
																			$myrow['discountcategory'],
																			$myrow['controlled'],
																			$myrow['serialised'],
																			$myrow['decimalplaces'],
																			'',
																			'No',
																			-1,
																			$myrow['taxcatid'],
																			'',
																			'',
																			'',
																			$myrow['standardcost']) ==1){

						$_SESSION['CreditItems'.$identifier]->GetTaxes($LineNumber);

						if ($myrow['controlled']==1){
							/*Qty must be built up from serial item entries */
				   			$_SESSION['CreditItems'.$identifier]->LineItems[$LineNumber]->Quantity = 0;
						}

					}
			   	} else {
					prnMsg( $_POST['NewItem'] . ' ' . _('does not exist in the database and cannot therefore be added to the credit note'),'warn');
			   	}
		   	} /* end of if not already on the credit note */
		} while ($i<=$_SESSION['QuickEntries']); /*loop to the next quick entry record */
		unset($_POST['NewItem']);
	} /* end of if quick entry */


/* setup system defaults for looking up prices and the number of ordered items
   if an item has been selected for adding to the basket add it to the session arrays */

	 if ($_SESSION['CreditItems'.$identifier]->ItemsOrdered > 0 OR isset($_POST['NewItem'])){

		if (isset($_GET['Delete'])){
			$_SESSION['CreditItems'.$identifier]->remove_from_cart($_GET['Delete']);
		}

		if (isset($_POST['ChargeFreightCost'])){
			$_SESSION['CreditItems'.$identifier]->FreightCost = filter_number_format($_POST['ChargeFreightCost']);
		}

		if (isset($_POST['Location'])
			AND $_POST['Location'] != $_SESSION['CreditItems'.$identifier]->Location){

			$_SESSION['CreditItems'.$identifier]->Location = $_POST['Location'];

			$NewDispatchTaxProvResult = DB_query("SELECT taxprovinceid FROM locations WHERE loccode='" . $_POST['Location'] . "'");
			$myrow = DB_fetch_array($NewDispatchTaxProvResult);

			$_SESSION['CreditItems'.$identifier]->DispatchTaxProvince = $myrow['taxprovinceid'];

			foreach ($_SESSION['CreditItems'.$identifier]->LineItems as $LineItem) {
				$_SESSION['CreditItems'.$identifier]->GetTaxes($LineItem->LineNumber);
			}
		}

		foreach ($_SESSION['CreditItems'.$identifier]->LineItems as $LineItem) {

			if (isset($_POST['Quantity_' . $LineItem->LineNumber])){

				$Quantity = filter_number_format($_POST['Quantity_' . $LineItem->LineNumber]);
				$Narrative = $_POST['Narrative_' . $LineItem->LineNumber];

				if (isset($_POST['Price_' . $LineItem->LineNumber])){
					if (isset($_POST['Gross']) AND $_POST['Gross']==true){
						$TaxTotalPercent =0;
						foreach ($LineItem->Taxes AS $Tax) {
							if ($Tax->TaxOnTax ==1){
								$TaxTotalPercent += (1 + $TaxTotalPercent) * $Tax->TaxRate;
							} else {
								$TaxTotalPercent += $Tax->TaxRate;
							}
						}
						$Price = round(filter_number_format($_POST['Price_' . $LineItem->LineNumber])/($TaxTotalPercent + 1),$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces);
					} else {
						$Price = filter_number_format($_POST['Price_' . $LineItem->LineNumber]);
					}

     				$DiscountPercentage = filter_number_format($_POST['Discount_' . $LineItem->LineNumber]);

					foreach ($LineItem->Taxes as $TaxKey=>$TaxLine) {
						if (is_numeric(filter_number_format($_POST[$LineItem->LineNumber  . $TaxLine->TaxCalculationOrder . '_TaxRate']))){
							$_SESSION['CreditItems'.$identifier]->LineItems[$LineItem->LineNumber]->Taxes[$TaxKey]->TaxRate = filter_number_format($_POST[$LineItem->LineNumber  . $TaxKey . '_TaxRate'])/100;
						}
					}
				}
				if ($Quantity<0 OR $Price <0 OR $DiscountPercentage >100 OR $DiscountPercentage <0){
					prnMsg(_('The item could not be updated because you are attempting to set the quantity credited to less than 0 or the price less than 0 or the discount more than 100% or less than 0%'),'warn');
				} elseif (isset($_POST['Quantity_' . $LineItem->LineNumber])) {
					$_SESSION['CreditItems'.$identifier]->update_cart_item($LineItem->LineNumber,
																			$Quantity,
																			$Price,
																			$DiscountPercentage/100,
																			$Narrative,
																			'No',
																			$LineItem->ItemDue,
																			$LineItem->POLine,
																			0,
																			$identifier);
				}
			}

		}

		foreach ($_SESSION['CreditItems'.$identifier]->FreightTaxes as $FreightTaxKey=>$FreightTaxLine) {
			if (is_numeric(filter_number_format($_POST['FreightTaxRate'  . $FreightTaxLine->TaxCalculationOrder]))){
				$_SESSION['CreditItems'.$identifier]->FreightTaxes[$FreightTaxKey]->TaxRate = filter_number_format($_POST['FreightTaxRate'  . $FreightTaxKey])/100;
			}
		}

		if (isset($_POST['NewItem'])){
/* get the item details from the database and hold them in the cart object make the quantity 1 by default then add it to the cart */

			   $AlreadyOnThisCredit =0;

			   foreach ($_SESSION['CreditItems'.$identifier]->LineItems AS $OrderItem) {

			   /* do a loop round the items on the credit note to see that the item
			   is not already on this credit note */

					if ($_SESSION['SO_AllowSameItemMultipleTimes']==0 AND strcasecmp($OrderItem->StockID, $_POST['NewItem']) == 0) {
					     $AlreadyOnThisCredit = 1;
					     prnMsg(_('The item selected is already on this credit the system will not allow the same item on the credit note more than once. However you can change the quantity credited of the existing line if necessary.'),'warn');
				    }
			   } /* end of the foreach loop to look for preexisting items of the same code */

			   if ($AlreadyOnThisCredit!=1){

				$sql = "SELECT stockmaster.description,
								stockmaster.longdescription,
								stockmaster.stockid,
								stockmaster.units,
								stockmaster.volume,
								stockmaster.grossweight,
								stockmaster.mbflag,
								stockmaster.discountcategory,
								stockmaster.controlled,
								stockmaster.decimalplaces,
								stockmaster.serialised,
								(materialcost+labourcost+overheadcost) AS standardcost,
								stockmaster.taxcatid
							FROM stockmaster
							WHERE stockmaster.stockid = '". $_POST['NewItem'] . "'";

				$ErrMsg = _('The item details could not be retrieved because');
				$DbgMsg = _('The SQL used to retrieve the item details but failed was');
				$result1 = DB_query($sql,$ErrMsg,$DbgMsg);
				$myrow = DB_fetch_array($result1);

				$LineNumber = $_SESSION['CreditItems'.$identifier]->LineCounter;
/*validate the data returned before adding to the items to credit */
				if ($_SESSION['CreditItems'.$identifier]->add_to_cart ($myrow['stockid'],
														1,
														$myrow['description'],
														$myrow['longdescription'],
														GetPrice($_POST['NewItem'],
														$_SESSION['CreditItems'.$identifier]->DebtorNo,
														$_SESSION['CreditItems'.$identifier]->Branch),
														0,
														$myrow['units'],
														$myrow['volume'],
														$myrow['grossweight'],
														0,
														$myrow['mbflag'],
														Date($_SESSION['DefaultDateFormat']),
														0,
														$myrow['discountcategory'],
														$myrow['controlled'],
														$myrow['serialised'],
														$myrow['decimalplaces'],
														'',
														'No',
														-1,
														$myrow['taxcatid'],
														'',
														'',
														'',
														$myrow['standardcost']) ==1){

					$_SESSION['CreditItems'.$identifier]->GetTaxes($LineNumber);

					if ($myrow['controlled']==1){
						/*Qty must be built up from serial item entries */
						$_SESSION['CreditItems'.$identifier]->LineItems[$LineNumber]->Quantity = 0;
					}
				}
			   } /* end of if not already on the credit note */
		  } /* end of if its a new item */

/* This is where the credit note as selected should be displayed  reflecting any deletions or insertions*/

		  echo '<table cellpadding="2" class="selection">
				<tr>
					<th>' . _('Item Code') . '</th>
					<th>' . _('Item Description') . '</th>
					<th>' . _('Quantity') . '</th>
					<th>' . _('Unit') . '</th>
					<th>' . _('Price') . '</th>
					<th>' . _('Gross') . '</th>
					<th>' . _('Discount') . '</th>
					<th>' . _('Total') . '<br />' . _('Excl Tax') . '</th>
					<th>' . _('Tax Authority') . '</th>
					<th>' . _('Tax') . '<br />' . _('Rate') . '</th>
					<th>' . _('Tax') . '<br />' . _('Amount') . '</th>
					<th>' . _('Total') . '<br />' . _('Incl Tax') . '</th>
				</tr>';

		  $_SESSION['CreditItems'.$identifier]->total = 0;
		  $_SESSION['CreditItems'.$identifier]->totalVolume = 0;
		  $_SESSION['CreditItems'.$identifier]->totalWeight = 0;

		  $TaxTotal = 0;
		  $TaxTotals = array();
		  $TaxGLCodes = array();

		  foreach ($_SESSION['CreditItems'.$identifier]->LineItems as $LineItem) {

			   $LineTotal =  round($LineItem->Quantity * $LineItem->Price * (1 - $LineItem->DiscountPercent),$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces);
			   $DisplayLineTotal = locale_number_format($LineTotal,$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces);

			   echo '<tr class="striped_row">
						<td>' . $LineItem->StockID . '</td>
									<td title="'. $LineItem->LongDescription . '">' . $LineItem->ItemDescription . '</td>';

			   if ($LineItem->Controlled==0){
			   	echo '<td><input type="text" class="number" name="Quantity_' . $LineItem->LineNumber . '" maxlength="8" size="6" value="' . locale_number_format(round($LineItem->Quantity,$LineItem->DecimalPlaces),$LineItem->DecimalPlaces) . '" /></td>';
			   } else {
				echo '<td class="number"><a href="' . $RootPath . '/CreditItemsControlled.php?LineNo=' . $LineItem->LineNumber . '&identifier=' . $identifier . '">' . locale_number_format($LineItem->Quantity,$LineItem->DecimalPlaces) . '</a>
                      <input type="hidden" name="Quantity_' . $LineItem->LineNumber . '" value="' . locale_number_format(round($LineItem->Quantity,$LineItem->DecimalPlaces),$LineItem->DecimalPlaces) . '" /></td>';
			   }

			echo '<td>' . $LineItem->Units . '</td>
			<td><input type="text" class="number" name="Price_' . $LineItem->LineNumber . '" size="10" maxlength="12" value="' . locale_number_format($LineItem->Price,$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces) . '" /></td>
			<td><input type="CheckBox" name="Gross" value="false" /></td>
			<td><input type="text" class="number" name="Discount_' . $LineItem->LineNumber . '" size="3" maxlength="3" value="' . locale_number_format(($LineItem->DiscountPercent * 100),'Variable') . '" />%</td>
			<td class="number">' . $DisplayLineTotal . '</td>';


			/*Need to list the taxes applicable to this line */
			echo '<td>';
			foreach ($_SESSION['CreditItems'.$identifier]->LineItems[$LineItem->LineNumber]->Taxes AS $Tax) {
				echo '<br />';
				echo $Tax->TaxAuthDescription;
			}
			echo '</td>';
			echo '<td>';

			$i=0; // initialise the number of taxes iterated through
			$TaxLineTotal =0; //initialise tax total for the line

			foreach ($LineItem->Taxes AS $TaxKey=>$Tax) {

				if ($i>0){
					echo '<br />';
				}
				echo '<input type="text" class="number" name="' . $LineItem->LineNumber . $TaxKey . '_TaxRate" maxlength="4" size="4" value="' . locale_number_format($Tax->TaxRate*100,'Variable') . '" />';
				$i++;
				if ($Tax->TaxOnTax ==1){
					$TaxTotals[$Tax->TaxAuthID] += ($Tax->TaxRate * ($LineTotal + $TaxLineTotal));
					$TaxLineTotal += ($Tax->TaxRate * ($LineTotal + $TaxLineTotal));
				} else {
					$TaxTotals[$Tax->TaxAuthID] += ($Tax->TaxRate * $LineTotal);
					$TaxLineTotal += ($Tax->TaxRate * $LineTotal);
				}
				$TaxGLCodes[$Tax->TaxAuthID] = $Tax->TaxGLCode;
			}
			echo '</td>';

			$TaxTotal += $TaxLineTotal;

			$DisplayTaxAmount = locale_number_format($TaxLineTotal ,$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces);
			$DisplayGrossLineTotal = locale_number_format($LineTotal + $TaxLineTotal, $_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces);

			echo '<td class="number">' . $DisplayTaxAmount . '</td>
				<td class="number">' . $DisplayGrossLineTotal . '</td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier=' . $identifier . '&Delete=' . $LineItem->LineNumber . '" onclick="return confirm(\'' . _('Are you sure you wish to delete this line item from the credit note?') . '\');">' . _('Delete') . '</a></td>
				</tr>';

			echo '<tr class="striped_row">
					<td colspan="11"><textarea  name="Narrative_' . $LineItem->LineNumber . '" cols="100%" rows="1">' . $LineItem->Narrative . '</textarea><br /></td>
				</tr>';


			$_SESSION['CreditItems'.$identifier]->total += $LineTotal;
			$_SESSION['CreditItems'.$identifier]->totalVolume += ($LineItem->Quantity * $LineItem->Volume);
			$_SESSION['CreditItems'.$identifier]->totalWeight += ($LineItem->Quantity * $LineItem->Weight);
		}

		if (!isset($_POST['ChargeFreightCost'])
			AND !isset($_SESSION['CreditItems'.$identifier]->FreightCost)){
			$_POST['ChargeFreightCost']=0;
		}
		echo '<tr>
				<td colspan="5"></td>';

		echo '<td colspan="2" class="number">' .  _('Credit Freight') . '</td>
			<td><input type="text" class="number" size="6" maxlength="6" name="ChargeFreightCost" value="' . locale_number_format($_SESSION['CreditItems'.$identifier]->FreightCost,$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces) . '" /></td>';

		$FreightTaxTotal =0; //initialise tax total

		echo '<td>';

		$i=0; // initialise the number of taxes iterated through
		foreach ($_SESSION['CreditItems'.$identifier]->FreightTaxes as $FreightTaxLine) {
			if ($i>0){
				echo '<br />';
			}
			echo  $FreightTaxLine->TaxAuthDescription;
			$i++;
		}

		echo '</td><td>';

		$i=0;
		foreach ($_SESSION['CreditItems'.$identifier]->FreightTaxes as $FreightTaxLine) {
			if ($i>0){
				echo '<br />';
			}

			echo  '<input type="text" class="number" name=FreightTaxRate' . $FreightTaxLine->TaxCalculationOrder . ' maxlength="4" size="4" value="' . locale_number_format(($FreightTaxLine->TaxRate * 100),'Variable') . '" />';

			if ($FreightTaxLine->TaxOnTax ==1){
				$TaxTotals[$FreightTaxLine->TaxAuthID] += ($FreightTaxLine->TaxRate * ($_SESSION['CreditItems'.$identifier]->FreightCost + $FreightTaxTotal));
				$FreightTaxTotal += ($FreightTaxLine->TaxRate * ($_SESSION['CreditItems'.$identifier]->FreightCost + $FreightTaxTotal));
			} else {
				$TaxTotals[$FreightTaxLine->TaxAuthID] += ($FreightTaxLine->TaxRate * $_SESSION['CreditItems'.$identifier]->FreightCost);
				$FreightTaxTotal += ($FreightTaxLine->TaxRate * $_SESSION['CreditItems'.$identifier]->FreightCost);
			}
			$i++;
			$TaxGLCodes[$FreightTaxLine->TaxAuthID] = $FreightTaxLine->TaxGLCode;
		}
		echo '</td>';

		echo '<td class="number">' . locale_number_format($FreightTaxTotal,$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces) . '</td>
			<td class="number">' . locale_number_format($FreightTaxTotal+ $_SESSION['CreditItems'.$identifier]->FreightCost,$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces) . '</td>
			</tr>';

		$TaxTotal += $FreightTaxTotal;
		$DisplayTotal = locale_number_format($_SESSION['CreditItems'.$identifier]->total + $_SESSION['CreditItems'.$identifier]->FreightCost,$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces);

		echo '<tr>
				<td colspan="7" class="number">' . _('Credit Totals') . '</td>
				<td class="number"><b>' . $DisplayTotal . '</b></td>
				<td colspan="2"></td>
				<td class="number"><b>' . locale_number_format($TaxTotal,$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces) . '</td>
				<td class="number"><b>' . locale_number_format($TaxTotal+($_SESSION['CreditItems'.$identifier]->total + $_SESSION['CreditItems'.$identifier]->FreightCost),$_SESSION['CreditItems'.$identifier]->CurrDecimalPlaces) . '</b></td>
			</tr>
			</table>';

/*Now show options for the credit note */

		echo '<br />
				<table class="selection">
				<tr>
					<td>' . _('Credit Note Type') . ' :</td>
					<td><select name="CreditType" onchange="ReloadForm(MainForm.Update)" >';

		if (!isset($_POST['CreditType']) OR $_POST['CreditType']=='Return'){
			   echo '<option selected="selected" value="Return">' . _('Goods returned to store') . '</option>
					<option value="WriteOff">' . _('Goods written off') . '</option>
					<option value="ReverseOverCharge">' . _('Reverse an Overcharge') . '</option>';
		} elseif ($_POST['CreditType']=='WriteOff') {
			   echo '<option selected="selected" value="WriteOff">' . _('Goods written off') . '</option>
					<option value="Return">' . _('Goods returned to store') . '</option>
					<option value="ReverseOverCharge">' . _('Reverse an Overcharge') . '</option>';
		} elseif($_POST['CreditType']=='ReverseOverCharge'){
		  	echo '<option selected="selected" value="ReverseOverCharge">' . _('Reverse Overcharge Only') . '</option>
				<option value="Return">' . _('Goods Returned To Store') . '</option>
				<option value="WriteOff">' . _('Good written off') . '</option>';
		}

		echo '</select></td></tr>';


		if (!isset($_POST['CreditType']) OR $_POST['CreditType']=='Return'){

/*if the credit note is a return of goods then need to know which location to receive them into */

			echo '<tr>
					<td>' . _('Goods Returned to Location') . ' :</td>
					<td><select name="Location">';

			$SQL="SELECT locations.loccode, locationname FROM locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1";
			$Result = DB_query($SQL);

			if (!isset($_POST['Location'])){
				$_POST['Location'] = $_SESSION['CreditItems'.$identifier]->Location;
			}
			while ($myrow = DB_fetch_array($Result)) {

				if ($_POST['Location']==$myrow['loccode']){
					echo '<option selected="selected" value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
				} else {
					echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
				}
			}
			echo '</select></td></tr>';

		} elseif ($_POST['CreditType']=='WriteOff') { /* the goods are to be written off to somewhere */

			echo '<tr><td>' . _('Write off the cost of the goods to') . '</td>
					<td><select name=WriteOffGLCode>';

			$SQL="SELECT accountcode,
						accountname
					FROM chartmaster INNER JOIN accountgroups
					ON chartmaster.group_=accountgroups.groupname
					WHERE accountgroups.pandl=1
					ORDER BY accountcode";
			$Result = DB_query($SQL);

			while ($myrow = DB_fetch_array($Result)) {

				if ($_POST['WriteOffGLCode']==$myrow['accountcode']){
					echo '<option selected="selected" value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . $myrow['accountname'] . '</option>';
				} else {
					echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' - ' . $myrow['accountname'] . '</option>';
				}
			}
			   echo '</select></td></tr>';
		  }
		echo '<tr>
				<td>' . _('Sales person'). ':</td>
				<td><select name="SalesPerson">';
		$SalesPeopleResult = DB_query("SELECT salesmancode, salesmanname FROM salesman WHERE current=1");
		if (!isset($_POST['SalesPerson']) AND $_SESSION['SalesmanLogin']!=NULL ){
			$_SESSION['CreditItems'.$identifier]->SalesPerson = $_SESSION['SalesmanLogin'];
		}

		while ($SalesPersonRow = DB_fetch_array($SalesPeopleResult)){
			if ($SalesPersonRow['salesmancode']==$_SESSION['CreditItems'.$identifier]->SalesPerson){
				echo '<option selected="selected" value="' . $SalesPersonRow['salesmancode'] . '">' . $SalesPersonRow['salesmanname'] . '</option>';
			} else {
				echo '<option value="' . $SalesPersonRow['salesmancode'] . '">' . $SalesPersonRow['salesmanname'] . '</option>';
			}
		}

		echo '</select></td>
			</tr>';
		  if (!isset($_POST['CreditText'])) {
			  $_POST['CreditText']='';
		  }
		  echo '<tr><td>' . _('Credit Note Text') . ' :</td>
		  		<td><textarea name="CreditText" COLS="31" rows="5">' . $_POST['CreditText'] . '</textarea></td>
			</tr>
			</table><br />';

		  $OKToProcess = true;
		/*Check for the worst */
		  if (isset($_POST['CreditType']) and $_POST['CreditType']=='WriteOff' AND !isset($_POST['WriteOffGLCode'])){
			prnMsg (_('The GL code to write off the credit value to must be specified. Please select the appropriate GL code for the selection box'),'info');
			$OKToProcess = false;
		  }
		  echo '<div class="centre">
				<input type="submit" name="Update" value="' . _('Update') . '" />
				<input type="submit" name="CancelCredit" value="' . _('Cancel') . '" onclick="return confirm(\'' . _('Are you sure you wish to cancel the whole of this credit note?') . '\');" />';
		  if (!isset($_POST['ProcessCredit']) AND $OKToProcess == true){
			echo '<input type="submit" name="ProcessCredit" value="' . _('Process Credit Note') . '" />
					<br />';
		  }
		  echo '</div>';
	 } # end of if lines


/* Now show the stock item selection search stuff below */

	 if (isset($_POST['PartSearch']) AND $_POST['PartSearch']!='' AND !isset($_POST['ProcessCredit'])){

		 echo '<input type="hidden" name="PartSearch" value="' . _('Yes Please') . '" />';

		 $SQL="SELECT categoryid,
					categorydescription
				FROM stockcategory
				WHERE stocktype='F'
				ORDER BY categorydescription";

		 $result1 = DB_query($SQL);

		 echo '<br />
				<table class="selection">
				<tr>
					<td>' . _('Select a stock category') . ':&nbsp;<select name="StockCat">';

		 echo '<option selected="selected" value="All">' . _('All') . '</option>';
		 while ($myrow1 = DB_fetch_array($result1)) {
			  if (isset($_POST['StockCat']) and $_POST['StockCat']==$myrow1['categoryid']){
				   echo '<option selected="selected" value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
			  } else {
				   echo '<option value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
			  }
		 }

		 echo '</select></td>';
		 if (!isset($_POST['Keywords'])) {
		 	$_POST['Keywords'] = '';
		 }
		 if (!isset($_POST['StockCode'])) {
		 	$_POST['StockCode'] = '';
		 }
		 echo '<td>' . _('Enter text extracts in the description') . ':&nbsp;</td>';
		 echo '<td><input type="text" name="Keywords" size="20" maxlength="25" value="' . $_POST['Keywords'] . '" /></td></tr>';
		 echo '<tr><td></td>';
		 echo '<td><b>' ._('OR') . '</b>&nbsp;&nbsp;' . _('Enter extract of the Stock Code') . ':&nbsp;</td>';
		 echo '<td><input type="text" name="StockCode" size="15" maxlength="18" value="' . $_POST['StockCode'] . '" /></td>';
		 echo '</tr>';
		 echo '</table>
				<br />
				<div class="centre">';

		 echo '<input type="submit" name="Search" value="' . _('Search Now') .'" />
				<input type="submit" name="ChangeCustomer" value="' . _('Change Customer') . '" />
				<input type="submit" name="Quick" value="' . _('Quick Entry') . '" />
				</div>';

		 if (isset($SearchResult)) {

			  echo '<table cellpadding="2" class="selection">';
			  $TableHeader = '<tr>
								<th>' . _('Code') . '</th>
					  			<th>' . _('Description') . '</th>
								<th>' . _('Units')  . '</th>
							</tr>';
			  echo $TableHeader;

			  $j = 1;

			  while ($myrow=DB_fetch_array($SearchResult)) {

				$SupportedImgExt = array('png','jpg','jpeg');
				$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
				if (extension_loaded('gd') && function_exists('gd_info') && file_exists ($imagefile) ) {
						$ImageSource = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
							'&amp;StockID='.urlencode($myrow['stockid']).
							'&amp;text='.
							'&amp;width=64'.
							'&amp;height=64'.
							'" alt="" />';
					printf('<tr class="striped_row">
							<td><input type="submit" name="NewItem" value="%s" /></td>
							<td>%s</td>
							<td>%s</td>
							<td>' . $ImageSource . '</td>
							</tr>',
							$myrow['stockid'],
							$myrow['description'],
							$myrow['units'],
							$myrow['stockid']);
				} else { //don't try to show the image
					printf('<tr class="striped_row">
						<td><input type="submit" name="NewItem" value="%s" /></td>
						<td>%s</td>
						<td>%s</td>
						<td>' . _('No Image') . '</td>
						</tr>',
						$myrow['stockid'],
						$myrow['description'],
						$myrow['units']);
				}
	#end of page full new headings if
			  }
	#end of while loop
			  echo '</table>';
		 }#end if SearchResults to show
	} /*end if part searching required */ elseif(!isset($_POST['ProcessCredit'])) { /*quick entry form */

/*FORM VARIABLES TO POST TO THE CREDIT NOTE 10 AT A TIME WITH PART CODE AND QUANTITY */
	     echo '<table class="selection">';
	     echo '<tr><th colspan="2"><h3>' . _('Quick Entry') . '</h3></th></tr>';
	     echo '<tr>
	           	<th>' . _('Part Code') . '</th>
	           	<th>' . _('Quantity') . '</th>
	           </tr>';

	      for ($i=1;$i<=$_SESSION['QuickEntries'];$i++){

	     	echo '<tr class="striped_row">
					<td><input type="text" name="part_' . $i . '" size="21" maxlength="20" /></td>
					<td><input type="text" class="number" name="qty_' . $i . '" size="6" maxlength="6" /></td>
				</tr>';
	     }

	     echo '</table>
				<br />
				<div class="centre">
				<input type="submit" name="QuickEntry" value="' . _('Process Entries') . '" />
				<input type="submit" name="PartSearch" value="' . _('Search Parts') . '" />
				</div>';

	}

    echo '</div>
          </form>';
} //end of else not selecting a customer

if (isset($_POST['ProcessCredit']) AND $OKToProcess==true){

	/* SQL to process the postings for sales credit notes...
	First Get the area where the credit note is to from the branches table */

	 $SQL = "SELECT area
		 	FROM custbranch
			WHERE custbranch.debtorno ='". $_SESSION['CreditItems'.$identifier]->DebtorNo . "'
			AND custbranch.branchcode = '" . $_SESSION['CreditItems'.$identifier]->Branch . "'";
	$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The area cannot be determined for this customer');
	$DbgMsg = _('The following SQL to insert the customer credit note was used');
	$Result = DB_query($SQL,$ErrMsg,$DbgMsg);

	 if ($myrow = DB_fetch_row($Result)){
	     $Area = $myrow[0];
	 }

	 DB_free_result($Result);

	 if ($_SESSION['CompanyRecord']['gllink_stock']==1
	 	AND $_POST['CreditType']=='WriteOff'
		AND (!isset($_POST['WriteOffGLCode'])
		OR $_POST['WriteOffGLCode']=='')){

		  prnMsg(_('For credit notes created to write off the stock a general ledger account is required to be selected. Please select an account to write the cost of the stock off to then click on Process again'),'error');
		  include('includes/footer.php');
		  exit;
	 }


/*Now Get the next credit note number - function in SQL_CommonFunctions*/

	 $CreditNo = GetNextTransNo(11);
	 $SQLCreditDate = Date('Y-m-d');
	 $PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']));

/*Start an SQL transaction */
	 DB_Txn_Begin();


/*Now insert the Credit Note into the DebtorTrans table allocations will have to be done seperately*/

	 $SQL = "INSERT INTO debtortrans (transno,
							 		type,
									debtorno,
									branchcode,
									trandate,
									inputdate,
									prd,
									tpe,
									ovamount,
									ovgst,
									ovfreight,
									rate,
									invtext,
									salesperson)
								  VALUES ('". $CreditNo . "',
								  	'11',
									'" . $_SESSION['CreditItems' . $identifier]->DebtorNo . "',
									'" . $_SESSION['CreditItems' . $identifier]->Branch . "',
									'" . $SQLCreditDate . "',
									'" . date('Y-m-d H-i-s') . "',
									'" . $PeriodNo . "',
									'" . $_SESSION['CreditItems' . $identifier]->DefaultSalesType . "',
									'" . -($_SESSION['CreditItems'.$identifier]->total) . "',
									'" . -$TaxTotal . "',
								  	'" . -$_SESSION['CreditItems' . $identifier]->FreightCost . "',
									'" . $_SESSION['CurrencyRate'] . "',
									'" . $_POST['CreditText'] . "',
									'" . $_SESSION['CreditItems' . $identifier]->SalesPerson . "' )";

	$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The customer credit note transaction could not be added to the database because');
	$DbgMsg = _('The following SQL to insert the customer credit note was used');
	$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);


	$CreditTransID = DB_Last_Insert_ID('debtortrans','id');

	/* Insert the tax totals for each tax authority where tax was charged on the invoice */
	foreach ($TaxTotals AS $TaxAuthID => $TaxAmount) {

		$SQL = "INSERT INTO debtortranstaxes (debtortransid,
							taxauthid,
							taxamount)
				VALUES ('" . $CreditTransID . "',
						'" . $TaxAuthID . "',
						'" . -$TaxAmount/$_SESSION['CurrencyRate'] . "')";

		$ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction taxes records could not be inserted because');
		$DbgMsg = _('The following SQL to insert the debtor transaction taxes record was used');
 		$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
	}

/* Insert stock movements for stock coming back in if the Credit is a return of goods */

	 foreach ($_SESSION['CreditItems'.$identifier]->LineItems as $CreditLine) {

		if ($CreditLine->Quantity > 0){

			$LocalCurrencyPrice = ($CreditLine->Price / $_SESSION['CurrencyRate']);

		    if ($CreditLine->MBflag=='M' oR $CreditLine->MBflag=='B'){
		   /*Need to get the current location quantity will need it later for the stock movement */
	 	    	$SQL="SELECT locstock.quantity
						FROM locstock
						WHERE locstock.stockid='" . $CreditLine->StockID . "'
						AND loccode= '" . $_SESSION['CreditItems'.$identifier]->Location . "'";

		    	$Result = DB_query($SQL);
		    	if (DB_num_rows($Result)==1){
					$LocQtyRow = DB_fetch_row($Result);
					$QtyOnHandPrior = $LocQtyRow[0];
		    	} else {
				/*There must actually be some error this should never happen */
					$QtyOnHandPrior = 0;
		    	}
		    } else {
		    	$QtyOnHandPrior =0; //because its a dummy/assembly/kitset part
		    }

		    if ($_POST['CreditType']=='ReverseOverCharge') {
		   /*Insert a stock movement coming back in to show the credit note  - flag the stockmovement not to show on stock movement enquiries - its is not a real stock movement only for invoice line - also no mods to location stock records*/
				$SQL = "INSERT INTO stockmoves (stockid,
												type,
												transno,
												loccode,
												trandate,
												userid,
												debtorno,
												branchcode,
												price,
												prd,
												reference,
												qty,
												discountpercent,
												standardcost,
												newqoh,
												hidemovt,
												narrative)
										VALUES ('" . $CreditLine->StockID . "',
												11,
												'" . $CreditNo . "',
												'" . $_SESSION['CreditItems'.$identifier]->Location . "',
												'" . $SQLCreditDate . "',
												'" . $_SESSION['UserID'] . "',
												'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
												'" . $_SESSION['CreditItems'.$identifier]->Branch . "',
												'" . $LocalCurrencyPrice . "',
												'" . $PeriodNo . "',
												'" . $_POST['CreditText'] . "',
												'" . $CreditLine->Quantity . "',
												'" . $CreditLine->DiscountPercent . "',
												'" . $CreditLine->StandardCost . "',
												'" . $QtyOnHandPrior  . "',
												1,
												'" . $CreditLine->Narrative . "')";

				$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records could not be inserted because');
				$DbgMsg = _('The following SQL to insert the stock movement records for the purpose of display on the credit note was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			} else { //its a return or a write off need to record goods coming in first

		    	if ($CreditLine->MBflag=='M' OR $CreditLine->MBflag=='B'){
		    		$SQL = "INSERT INTO stockmoves (stockid,
												type,
												transno,
												loccode,
												trandate,
												userid,
												debtorno,
												branchcode,
												price,
												prd,
												qty,
												discountpercent,
												standardcost,
												reference,
												newqoh,
												narrative)
											VALUES (
												'" . $CreditLine->StockID . "',
												11,
												" . $CreditNo . ",
												'" . $_SESSION['CreditItems'.$identifier]->Location . "',
												'" . $SQLCreditDate . "',
												'" . $_SESSION['UserID'] . "',
												'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
												'" . $_SESSION['CreditItems'.$identifier]->Branch . "',
												'" . $LocalCurrencyPrice . "',
												'" . $PeriodNo . "',
												'" . $CreditLine->Quantity . "',
												'" . $CreditLine->DiscountPercent . "',
												'" . $CreditLine->StandardCost . "',
												'" . $_POST['CreditText'] . "',
												'" . ($QtyOnHandPrior + $CreditLine->Quantity) . "',
												'" . $CreditLine->Narrative . "'
											)";

		    	} else { /*its an assembly/kitset or dummy so don't attempt to figure out new qoh */
					$SQL = "INSERT INTO stockmoves (stockid,
													type,
													transno,
													loccode,
													trandate,
													userid,
													debtorno,
													branchcode,
													price,
													prd,
													qty,
													discountpercent,
													standardcost,
													reference,
													narrative)
											VALUES ('" . $CreditLine->StockID . "',
													11,
													'" . $CreditNo . "',
													'" . $_SESSION['CreditItems'.$identifier]->Location . "',
													'" . $SQLCreditDate . "',
													'" . $_SESSION['UserID'] . "',
													'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
													'" . $_SESSION['CreditItems'.$identifier]->Branch . "',
													'" . $LocalCurrencyPrice . "',
													'" . $PeriodNo . "',
													'" . $CreditLine->Quantity . "',
													'" . $CreditLine->DiscountPercent . "',
													'" . $CreditLine->StandardCost . "',
													'" . $_POST['CreditText'] . "',
													'" . $CreditLine->Narrative . "' )";
		    	}

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records could not be inserted because');
				$DbgMsg = _('The following SQL to insert the stock movement records was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				/*Get the stockmoveno from above - need to ref StockMoveTaxes and possibly SerialStockMoves */
				$StkMoveNo = DB_Last_Insert_ID('stockmoves','stkmoveno');

				/*Insert the taxes that applied to this line */
				foreach ($CreditLine->Taxes as $Tax) {

					$SQL = "INSERT INTO stockmovestaxes (stkmoveno,
										taxauthid,
										taxrate,
										taxcalculationorder,
										taxontax)
							VALUES ('" . $StkMoveNo . "',
								'" . $Tax->TaxAuthID . "',
								'" . $Tax->TaxRate . "',
								'" . $Tax->TaxCalculationOrder . "',
								'" . $Tax->TaxOnTax . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Taxes and rates applicable to this credit note line item could not be inserted because');
					$DbgMsg = _('The following SQL to insert the stock movement tax detail records was used');
					$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
				}


				if (($CreditLine->MBflag=='M' OR $CreditLine->MBflag=='B') AND $CreditLine->Controlled==1){
					/*Need to do the serial stuff in here now */

					foreach($CreditLine->SerialItems as $Item){

						/*1st off check if StockSerialItems already exists */
						$SQL = "SELECT COUNT(*)
								FROM stockserialitems
								WHERE stockid='" . $CreditLine->StockID . "'
								AND loccode='" . $_SESSION['CreditItems'.$identifier]->Location . "'
								AND serialno='" . $Item->BundleRef . "'";
						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The existence of the serial stock item record could not be determined because');
						$DbgMsg = _('The following SQL to find out if the serial stock item record existed already was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
						$myrow = DB_fetch_row($Result);

						if ($myrow[0]==0) {
						/*The StockSerialItem record didnt exist
						so insert a new record */
							$SQL = "INSERT INTO stockserialitems ( stockid,
																loccode,
																serialno,
																quantity)
																VALUES (
																'" . $CreditLine->StockID . "',
																'" . $_SESSION['CreditItems'.$identifier]->Location . "',
																'" . $Item->BundleRef . "',
																'" . $Item->BundleQty . "'
																)";

							$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The new serial stock item record could not be inserted because');
							$DbgMsg = _('The following SQL to insert the new serial stock item record was used') ;
							$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
						} else { /*Update the existing StockSerialItems record */
							$SQL = "UPDATE stockserialitems SET quantity= quantity + " . $Item->BundleQty . "
									WHERE stockid='" . $CreditLine->StockID . "'
									AND loccode='" . $_SESSION['CreditItems'.$identifier]->Location . "'
									AND serialno='" . $Item->BundleRef . "'";

							$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated because');
							$DbgMsg = _('The following SQL to update the serial stock item record was used');
							$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
						}
						/* now insert the serial stock movement */

						$SQL = "INSERT INTO stockserialmoves ( stockmoveno,
															stockid,
															serialno,
															moveqty)
														VALUES (
															'" . $StkMoveNo . "',
															'" . $CreditLine->StockID . "',
															'" . $Item->BundleRef . "',
															'" . $Item->BundleQty . "')";
						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
						$DbgMsg = _('The following SQL to insert the serial stock movement record was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					}/* foreach serial item in the serialitems array */

				} /*end if the credit line is a controlled item */

			    }/*End of its a return or a write off */

			    if ($_POST['CreditType']=='Return'){

				/* Update location stock records if not a dummy stock item */

				if ($CreditLine->MBflag=='B' OR $CreditLine->MBflag=='M') {

					$SQL = "UPDATE locstock
							SET locstock.quantity = locstock.quantity + " . $CreditLine->Quantity . "
							WHERE locstock.stockid = '" . $CreditLine->StockID . "'
							AND locstock.loccode = '" . $_SESSION['CreditItems'.$identifier]->Location . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Location stock record could not be updated because');
					$DbgMsg = _('The following SQL to update the location stock record was used');
					$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				} else if ($CreditLine->MBflag=='A'){ /* its an assembly */
					/*Need to get the BOM for this part and make stock moves
					for the componentsand of course update the Location stock
					balances for all the components*/

					$StandardCost =0; /*To start with then accumulate the cost of the comoponents
								for use in journals later on */

					$SQL = "SELECT bom.component,
									bom.quantity,
									stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standard
							FROM bom INNER JOIN stockmaster
							ON bom.component=stockmaster.stockid
							WHERE bom.parent='" . $CreditLine->StockID . "'
                            AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                            AND bom.effectiveto > '" . date('Y-m-d') . "'";

					$ErrMsg =  _('Could not retrieve assembly components from the database for') . ' ' . $CreditLine->StockID . ' ' . _('because');
				 	$DbgMsg = _('The SQL that failed was');
					$AssResult = DB_query($SQL,$ErrMsg,$DbgMsg,true);

					while ($AssParts = DB_fetch_array($AssResult)){

						$StandardCost += $AssParts['standard'] * $AssParts['quantity'];

/*Need to get the current location quantity will need it later for the stock movement */
					   	$SQL="SELECT locstock.quantity
						   		FROM locstock
								WHERE locstock.stockid='" . $AssParts['component'] . "'
								AND locstock.loccode= '" . $_SESSION['CreditItems'.$identifier]->Location . "'";

        					$Result = DB_query($SQL);
						if (DB_num_rows($Result)==1){
							$LocQtyRow = DB_fetch_row($Result);
							$QtyOnHandPrior = $LocQtyRow[0];
						} else {
						/*There must actually be some error this should never happen */
							$QtyOnHandPrior = 0;
						}

						/*Add stock movements for the assembly component items */
						$SQL = "INSERT INTO stockmoves (stockid,
														type,
														transno,
														loccode,
														trandate,
														userid,
														debtorno,
														branchcode,
														prd,
														reference,
														qty,
														standardcost,
														show_on_inv_crds,
														newqoh)
												VALUES (
													'" . $AssParts['component'] . "',
													11,
													'" . $CreditNo . "',
													'" . $_SESSION['CreditItems'.$identifier]->Location . "',
													'" . $SQLCreditDate . "',
													'" . $_SESSION['UserID'] . "',
													'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
													'" . $_SESSION['CreditItems'.$identifier]->Branch . "',
													'" . $PeriodNo . "',
													'" . _('Assembly') .': ' . $CreditLine->StockID . "',
													'" . $AssParts['quantity'] * $CreditLine->Quantity . "',
													'" . $AssParts['standard'] . "',
													0,
													'" . ($QtyOnHandPrior + ($AssParts['quantity'] * $CreditLine->Quantity)) . "'
													)";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records for the assembly components of') . ' ' . $CreditLine->StockID . ' ' . _('could not be inserted because');
					$DbgMsg = _('The following SQL to insert the assembly components stock movement records was used');
				        $Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

					  /*Update the stock quantities for the assembly components */
					 $SQL = "UPDATE locstock
					   		SET locstock.quantity = locstock.quantity + " . $AssParts['quantity'] * $CreditLine->Quantity . "
							WHERE locstock.stockid = '" . $AssParts['component'] . "'
							AND locstock.loccode = '" . $_SESSION['CreditItems'.$identifier]->Location . "'";

					$ErrMsg =  _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Location stock record could not be updated for an assembly component because');
  					$DbgMsg =  _('The following SQL to update the component location stock record was used');
					$Result = DB_query($SQL,$ErrMsg, $DbgMsg,true);
				    } /* end of assembly explosion and updates */


				    /*Update the cart with the recalculated standard cost
				    from the explosion of the assembly's components*/
				    $_SESSION['CreditItems'.$identifier]->LineItems[$CreditLine->LineNumber]->StandardCost = $StandardCost;
				    $CreditLine->StandardCost = $StandardCost;
				}
				    /*end of its a return of stock */
			   } elseif ($_POST['CreditType']=='WriteOff'){ /*its a stock write off */

			   	    if ($CreditLine->MBflag=='B' OR $CreditLine->MBflag=='M'){
			   		/* Insert stock movements for the
					item being written off - with unit cost */
				    	$SQL = "INSERT INTO stockmoves ( stockid,
													type,
													transno,
													loccode,
													trandate,
													userid,
													debtorno,
													branchcode,
													price,
													prd,
													qty,
													discountpercent,
													standardcost,
													reference,
													show_on_inv_crds,
													newqoh,
													narrative)
												VALUES (
													'" . $CreditLine->StockID . "',
													11,
													'" . $CreditNo . "',
													'" . $_SESSION['CreditItems'.$identifier]->Location . "',
													'" . $SQLCreditDate . "',
													'" . $_SESSION['UserID'] . "',
													'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
													'" . $_SESSION['CreditItems'.$identifier]->Branch . "',
													'" . $LocalCurrencyPrice . "',
													'" . $PeriodNo . "',
													'" . -$CreditLine->Quantity . "',
													'" . $CreditLine->DiscountPercent . "',
													'" . $CreditLine->StandardCost . "',
													'" . $_POST['CreditText'] . "',
													0,
													'" . $QtyOnHandPrior . "',
													'" . $CreditLine->Narrative . "'
													)";

				    } else { /* its an assembly, so dont figure out the new qoh */

					$SQL = "INSERT INTO stockmoves (stockid,
													type,
													transno,
													loccode,
													trandate,
													userid,
													debtorno,
													branchcode,
													price,
													prd,
													qty,
													discountpercent,
													standardcost,
													reference,
													show_on_inv_crds)
												VALUES (
													'" . $CreditLine->StockID . "',
													11,
													'" . $CreditNo . "',
													'" . $_SESSION['CreditItems'.$identifier]->Location . "',
													'" . $SQLCreditDate . "',
													'" . $_SESSION['UserID'] . "',
													'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
													'" . $_SESSION['CreditItems'.$identifier]->Branch . "',
													'" . $LocalCurrencyPrice . "',
													'" . $PeriodNo . "',
													'" . -$CreditLine->Quantity . "',
													'" . $CreditLine->DiscountPercent . "',
													'" . $CreditLine->StandardCost . "',
													'" . $_POST['CreditText'] . "',
													0)";

				}

     			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement record to write the stock off could not be inserted because');
				$DbgMsg = _('The following SQL to insert the stock movement to write off the stock was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				if (($CreditLine->MBflag=='M' OR $CreditLine->MBflag=='B') AND $CreditLine->Controlled==1){
					/*Its a write off too still so need to process the serial items
					written off */

					$StkMoveNo = DB_Last_Insert_ID('stockmoves','stkmoveno');

					foreach($CreditLine->SerialItems as $Item){
					/*no need to check StockSerialItems record exists
					it would have been added by the return stock movement above */
						$SQL = "UPDATE stockserialitems SET quantity= quantity - " . $Item->BundleQty . "
								WHERE stockid='" . $CreditLine->StockID . "'
								AND loccode='" . $_SESSION['CreditItems'.$identifier]->Location . "'
								AND serialno='" . $Item->BundleRef . "'";

						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be updated for the write off because');
						$DbgMsg = _('The following SQL to update the serial stock item record was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

						/* now insert the serial stock movement */

						$SQL = "INSERT INTO stockserialmoves ( stockmoveno,
															stockid,
															serialno,
															moveqty)
														VALUES (
															'" . $StkMoveNo . "',
															'" . $CreditLine->StockID . "',
															'" . $Item->BundleRef . "',
															'" . -$Item->BundleQty . "'
															)";
						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record for the write off could not be inserted because');
						$DbgMsg = _('The following SQL to insert the serial stock movement write off record was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					}/* foreach serial item in the serialitems array */

				} /*end if the credit line is a controlled item */

   			} /*end if its a stock write off */

/*Insert Sales Analysis records use links to the customer master and branch tables to ensure that if
the salesman or area has changed a new record is inserted for the customer and salesman of the new
set up. Considered just getting the area and salesman from the branch table but these can alter and the
sales analysis needs to reflect the sales made before and after the changes*/

			$SalesValue = 0;
			if ($_SESSION['CurrencyRate']>0){
				$SalesValue = $CreditLine->Price * $CreditLine->Quantity / $_SESSION['CurrencyRate'];
			}

			   $SQL="SELECT	COUNT(*),
							salesanalysis.stkcategory,
							salesanalysis.area
						FROM salesanalysis,
							custbranch,
							stockmaster
						WHERE salesanalysis.stkcategory=stockmaster.categoryid
						AND salesanalysis.stockid=stockmaster.stockid
						AND salesanalysis.cust=custbranch.debtorno
						AND salesanalysis.custbranch=custbranch.branchcode
						AND salesanalysis.area=custbranch.area
						AND salesanalysis.salesperson='" . $_SESSION['CreditItems'.$identifier]->SalesPerson . "'
						AND salesanalysis.typeabbrev ='" . $_SESSION['CreditItems'.$identifier]->DefaultSalesType . "'
						AND salesanalysis.periodno='" . $PeriodNo . "'
						AND salesanalysis.cust = '" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "'
						AND salesanalysis.custbranch = '" . $_SESSION['CreditItems'.$identifier]->Branch . "'
						AND salesanalysis.stockid = '" . $CreditLine->StockID . "'
						AND salesanalysis.budgetoractual=1
						GROUP BY salesanalysis.stkcategory,
							salesanalysis.area,
							salesanalysis.salesperson";

			$ErrMsg = _('The count to check for existing Sales analysis records could not run because');
			$DbgMsg = _('SQL to count the no of sales analysis records');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			$myrow = DB_fetch_array($Result);

			if ($myrow[0]>0){  /*Update the existing record that already exists */

				if ($_POST['CreditType']=='ReverseOverCharge'){

					/*No updates to qty or cost data */

					$SQL = "UPDATE salesanalysis SET amt=amt-" . $SalesValue . ",
													disc=disc-" . $CreditLine->DiscountPercent * $SalesValue . "
							WHERE salesanalysis.area='" . $myrow['area'] . "'
							AND salesanalysis.salesperson='" . $_SESSION['CreditItems'.$identifier]->SalesPerson . "'
							AND salesanalysis.typeabbrev ='" . $_SESSION['CreditItems'.$identifier]->DefaultSalesType . "'
							AND salesanalysis.periodno = '" . $PeriodNo . "'
							AND salesanalysis.cust = '" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "'
							AND salesanalysis.custbranch = '" . $_SESSION['CreditItems'.$identifier]->Branch . "'
							AND salesanalysis.stockid = '" . $CreditLine->StockID . "'
							AND salesanalysis.stkcategory ='" . $myrow['stkcategory'] . "'
							AND salesanalysis.budgetoractual=1";

				} else {

					$SQL = "UPDATE salesanalysis SET Amt=Amt-" . $SalesValue . ",
													Cost=Cost-" . $CreditLine->StandardCost * $CreditLine->Quantity . ",
													Qty=Qty-" . $CreditLine->Quantity . ",
													Disc=Disc-" . $CreditLine->DiscountPercent * $SalesValue . "
							WHERE salesanalysis.area='" . $myrow['area'] . "'
							AND salesanalysis.salesperson='" . $_SESSION['CreditItems'.$identifier]->SalesPerson . "'
							AND salesanalysis.typeabbrev ='" . $_SESSION['CreditItems'.$identifier]->DefaultSalesType . "'
							AND salesanalysis.periodno = '" . $PeriodNo . "'
							AND salesanalysis.cust = '" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "'
							AND salesanalysis.custbranch = '" . $_SESSION['CreditItems'.$identifier]->Branch . "'
							AND salesanalysis.stockid = '" . $CreditLine->StockID . "'
							AND salesanalysis.stkcategory ='" . $myrow['stkcategory'] . "'
							AND salesanalysis.budgetoractual=1";
				}

			   } else { /* insert a new sales analysis record */

		   		if ($_POST['CreditType']=='ReverseOverCharge'){

					$SQL = "INSERT salesanalysis (typeabbrev,
												periodno,
												amt,
												cust,
												custbranch,
												qty,
												disc,
												stockid,
												area,
												budgetoractual,
												salesperson,
												stkcategory)
										 SELECT '" . $_SESSION['CreditItems'.$identifier]->DefaultSalesType . "',
												'" . $PeriodNo . "',
												'" . -$SalesValue . "',
												'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
												'" . $_SESSION['CreditItems'.$identifier]->Branch . "',
												0,
												'" . -$CreditLine->DiscountPercent * $SalesValue . "',
												'" . $CreditLine->StockID . "',
												custbranch.area,
												1,
												'" . $_SESSION['CreditItems'.$identifier]->SalesPerson . "',
												stockmaster.categoryid
										FROM stockmaster, custbranch
										WHERE stockmaster.stockid = '" . $CreditLine->StockID . "'
										AND custbranch.debtorno = '" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "'
										AND custbranch.branchcode='" . $_SESSION['CreditItems'.$identifier]->Branch . "'";

				} else {

				    $SQL = "INSERT salesanalysis ( typeabbrev,
												periodno,
												amt,
												cost,
												cust,
												custbranch,
												qty,
												disc,
												stockid,
												area,
												budgetoractual,
												salesperson,
												stkcategory)
										SELECT '" . $_SESSION['CreditItems'.$identifier]->DefaultSalesType . "',
												'" . $PeriodNo . "',
												'" . -$SalesValue . "',
												'" . -$CreditLine->StandardCost * $CreditLine->Quantity . "',
												'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
												'" . $_SESSION['CreditItems'.$identifier]->Branch . "',
												'" . -$CreditLine->Quantity . "',
												'" . -$CreditLine->DiscountPercent * $SalesValue . "',
												'" . $CreditLine->StockID . "',
												custbranch.area,
												1,
												'" . $_SESSION['CreditItems'.$identifier]->SalesPerson . "',
												stockmaster.categoryid
										FROM stockmaster,
												custbranch
										WHERE stockmaster.stockid = '" . $CreditLine->StockID . "'
										AND custbranch.debtorno = '" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "'
										AND custbranch.branchcode='" . $_SESSION['CreditItems'.$identifier]->Branch . "'";
				}
			}

			$ErrMsg = _('The sales analysis record for this credit note could not be added because');
			$DbgMsg = _('The following SQL to insert the sales analysis record was used');
			$Result = DB_query($SQL,$ErrMsg, $DbgMsg, true);


/* If GLLink_Stock then insert GLTrans to either debit stock or an expense
depending on the valuve of $_POST['CreditType'] and then credit the cost of sales
at standard cost*/

			   if ($_SESSION['CompanyRecord']['gllink_stock']==1
			   	AND $CreditLine->StandardCost !=0
				AND $_POST['CreditType']!='ReverseOverCharge'){

/*first reverse credit the cost of sales entry*/
				  $COGSAccount = GetCOGSGLAccount($Area,
				  					$CreditLine->StockID,
									$_SESSION['CreditItems'.$identifier]->DefaultSalesType);

				  $SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
										VALUES (
											11,
											'" . $CreditNo . "',
											'" . $SQLCreditDate . "',
											'" . $PeriodNo . "',
											'" . $COGSAccount . "',
											'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . " - " . $CreditLine->StockID . " x " . $CreditLine->Quantity . " @ " . $CreditLine->StandardCost . "',
											'" . ($CreditLine->StandardCost * -$CreditLine->Quantity) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The cost of the stock credited GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);


				if ($_POST['CreditType']=='WriteOff'){

/* The double entry required is to reverse the cost of sales entry as above
then debit the expense account the stock is to written off to */

					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
								VALUES (11,
										'" . $CreditNo . "',
										'" . $SQLCreditDate . "',
										'" . $PeriodNo . "',
										'" . $_POST['WriteOffGLCode'] . "',
										'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . " - " . $CreditLine->StockID . " x " . $CreditLine->Quantity . " @ " . $CreditLine->StandardCost . "',
										'" . ($CreditLine->StandardCost * $CreditLine->Quantity) . "'
										)";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The cost of the stock credited GL posting could not be inserted because');
					$DbgMsg = _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				    } else {

/*the goods are coming back into stock so debit the stock account*/
					$StockGLCode = GetStockGLCode($CreditLine->StockID);
					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
										VALUES (11,
											'" . $CreditNo . "',
											'" . $SQLCreditDate . "',
											'" . $PeriodNo . "',
											'" . $StockGLCode['stockact'] . "',
											'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . " - " . $CreditLine->StockID . " x " . $CreditLine->Quantity . " @ " . $CreditLine->StandardCost . "',
											'" . ($CreditLine->StandardCost * $CreditLine->Quantity) . "'
											)";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock side (or write off) of the cost of sales GL posting could not be inserted because');
					$DbgMsg = _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				    }

				} /* end of if GL and stock integrated and standard cost !=0 */

				if ($_SESSION['CompanyRecord']['gllink_debtors']==1 AND $CreditLine->Price !=0){

//Post sales transaction to GL credit sales
				    $SalesGLAccounts = GetSalesGLAccount($Area,
				    						$CreditLine->StockID,
										$_SESSION['CreditItems'.$identifier]->DefaultSalesType);

					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
										VALUES (11,
											'" . $CreditNo . "',
											'" . $SQLCreditDate . "',
											'" . $PeriodNo . "',
											'" . $SalesGLAccounts['salesglcode'] . "',
											'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . " - " . $CreditLine->StockID . " x " . $CreditLine->Quantity . " @ " . $CreditLine->Price . "',
											'" . (($CreditLine->Price * $CreditLine->Quantity)/$_SESSION['CurrencyRate']) . "'
											)";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The credit note GL posting could not be inserted because');
					$DbgMsg = _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					if ($CreditLine->DiscountPercent !=0){

						$SQL = "INSERT INTO gltrans (type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount)
									VALUES (11,
										'" . $CreditNo . "',
										'" . $SQLCreditDate . "',
										'" . $PeriodNo . "',
										'" . $SalesGLAccounts['discountglcode'] . "',
										'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . " - " . $CreditLine->StockID . " @ " . ($CreditLine->DiscountPercent * 100) . "%',
										'" . -(($CreditLine->Price * $CreditLine->Quantity * $CreditLine->DiscountPercent)/$_SESSION['CurrencyRate']) . "'
										)";


						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The credit note discount GL posting could not be inserted because');
						$DbgMsg = _('The following SQL to insert the GLTrans record was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					}/* end of if discount not equal to 0 */
				} /*end of if sales integrated with debtors */
		  } /*Quantity credited is more than 0 */
	} /*end of CreditLine loop */


	if ($_SESSION['CompanyRecord']['gllink_debtors']==1){

/*Post credit note transaction to GL credit debtors, debit freight re-charged and debit sales */
		if (($_SESSION['CreditItems'.$identifier]->total + $_SESSION['CreditItems'.$identifier]->FreightCost + $TaxTotal) !=0) {
			$SQL = "INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
							VALUES (11,
								'" . $CreditNo . "',
								'" . $SQLCreditDate . "',
								'" . $PeriodNo . "',
								'" . $_SESSION['CompanyRecord']['debtorsact'] . "',
								'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
								'" . -(($_SESSION['CreditItems'.$identifier]->total + $_SESSION['CreditItems'.$identifier]->FreightCost + $TaxTotal)/$_SESSION['CurrencyRate']) . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The total debtor GL posting for the credit note could not be inserted because');
			$DbgMsg = _('The following SQL to insert the GLTrans record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
		}
		if ($_SESSION['CreditItems'.$identifier]->FreightCost !=0) {
			$SQL = "INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
							VALUES (11,
								'" . $CreditNo . "',
								'" . $SQLCreditDate . "',
								'" . $PeriodNo . "',
								'" . $_SESSION['CompanyRecord']['freightact'] . "',
								'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
								'" . ($_SESSION['CreditItems'.$identifier]->FreightCost/$_SESSION['CurrencyRate']) . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The freight GL posting for this credit note could not be inserted because');
			$DbgMsg = _('The following SQL to insert the GLTrans record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
		}
		foreach ( $TaxTotals as $TaxAuthID => $TaxAmount){
			if ($TaxAmount !=0 ){
				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount )
										VALUES (11,
											'" . $CreditNo . "',
											'" . $SQLCreditDate . "',
											'" . $PeriodNo . "',
											'" . $TaxGLCodes[$TaxAuthID] . "',
											'" . $_SESSION['CreditItems'.$identifier]->DebtorNo . "',
											'" . ($TaxAmount/$_SESSION['CurrencyRate']) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The tax GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
			}
		}

		EnsureGLEntriesBalance(11,$CreditNo);

	} /*end of if Sales and GL integrated */

	DB_Txn_Commit();

	 unset($_SESSION['CreditItems'.$identifier]->LineItems);
	 unset($_SESSION['CreditItems'.$identifier]);

	 echo _('Credit Note number') . ' ' . $CreditNo . ' ' . _('processed') . '<br />';
	 echo '<a target="_blank" href="' . $RootPath . '/PrintCustTrans.php?FromTransNo=' . $CreditNo . '&InvOrCredit=Credit">' . _('Show this Credit Note on screen') . '</a><br />';
	if ($_SESSION['InvoicePortraitFormat']==0){
	 	echo '<a href="' . $RootPath . '/PrintCustTrans.php?FromTransNo=' . $CreditNo . '&InvOrCredit=Credit&PrintPDF=True">' . _('Print this Credit Note') . '</a>';
	} else {
		echo '<a href="' . $RootPath . '/PrintCustTransPortrait.php?FromTransNo=' . $CreditNo . '&InvOrCredit=Credit&PrintPDF=True">' . _('Print this Credit Note') . '</a>';
	}
	 echo '<br /><a href="' . $RootPath . '/SelectCreditItems.php">' . _('Enter Another Credit Note') . '</a>';

} /*end of process credit note */

include('includes/footer.php');
?>

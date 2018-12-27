<?php
// CounterReturns.php
// This script allows credits and refunds from the default Counter Sale account for an inventory location.

include('includes/DefineCartClass.php');

/* Session started in session.php for password checking and authorisation level check
config.php is in turn included in session.php $PageSecurity now comes from session.php (and gets read in by GetConfig.php*/

include('includes/session.php');
$Title = _('Counter Returns');
$ViewTopic = 'SalesOrders';
$BookMark = 'CounterReturns';
include('includes/header.php');

include('includes/GetPrice.inc');
include('includes/SQL_CommonFunctions.inc');
include('includes/GetSalesTransGLCodes.inc');

if (empty($_GET['identifier'])) {
	$identifier=date('U');
} else {
	$identifier=$_GET['identifier'];
}
if (isset($_SESSION['Items' . $identifier]) AND isset($_POST['CustRef'])) {
	//update the Items object variable with the data posted from the form
	$_SESSION['Items' . $identifier]->CustRef = $_POST['CustRef'];
	$_SESSION['Items' . $identifier]->Comments = $_POST['Comments'];
	$_SESSION['Items' . $identifier]->DeliverTo = $_POST['DeliverTo'];
	$_SESSION['Items' . $identifier]->PhoneNo = $_POST['PhoneNo'];
	$_SESSION['Items' . $identifier]->Email = $_POST['Email'];
	$_SESSION['Items' . $identifier]->SalesPerson = $_POST['SalesPerson'];
}

if (isset($_POST['QuickEntry'])) {
	unset($_POST['PartSearch']);
}

if (isset($_POST['SelectingReturnItems'])) {
	foreach ($_POST as $FormVariable => $Quantity) {
		if (mb_strpos($FormVariable,'ReturnQty')!==false) {
			$NewItemArray[$_POST['StockID' . mb_substr($FormVariable,9)]] = filter_number_format($Quantity);
		}
	}
}

if (isset($_GET['NewItem'])) {
	$NewItem = trim($_GET['NewItem']);
}

if (isset($_GET['NewReturn'])) {
	/*New return entry - clear any existing return details from the ReturnItems object and initiate a newy*/
	 if (isset($_SESSION['Items' . $identifier])) {
		unset ($_SESSION['Items' . $identifier]->LineItems);
		$_SESSION['Items' . $identifier]->ItemsOrdered=0;
		unset ($_SESSION['Items' . $identifier]);
	}
}

$AlreadyWarnedAboutCredit = true; //no point testing credit limits for a return!!

if (!isset($_SESSION['Items' . $identifier])) {
	/* It must be a new return being created $_SESSION['Items' . $identifier] would be set up from the
	modification code above if a modification to an existing retur. Also $ExistingOrder would be
	set to 1. */

	$_SESSION['ExistingOrder'. $identifier] = 0;
	$_SESSION['Items' . $identifier] = new cart;

	/*Get the default customer-branch combo from the user's default location record */
	$SQL = "SELECT cashsalecustomer,
				cashsalebranch,
				locationname,
				taxprovinceid
			FROM locations
			WHERE loccode='" . $_SESSION['UserStockLocation'] ."'";
	$result = DB_query($SQL);
	if (DB_num_rows($result)==0) {
		prnMsg(_('Your user account does not have a valid default inventory location set up. Please see the system administrator to modify your user account.'),'error');
		include('includes/footer.php');
		exit;
	} else {
		$MyRow = DB_fetch_array($result); //get the only row returned

		if ($MyRow['cashsalecustomer']=='' OR $MyRow['cashsalebranch']=='') {
			prnMsg(_('To use this script it is first necessary to define a cash sales customer for the location that is your default location. The default cash sale customer is defined under set up ->Inventory Locations Maintenance. The customer should be entered using the customer code and a valid branch code of the customer entered.'),'error');
			include('includes/footer.php');
			exit;
		}
		if (isset($_GET['DebtorNo'])) {
			$_SESSION['Items' . $identifier]->DebtorNo = $_GET['DebtorNo'];
			$_SESSION['Items' . $identifier]->Branch = $_GET['BranchNo'];
		} else {
			$_SESSION['Items' . $identifier]->DebtorNo = $MyRow['cashsalecustomer'];
			$_SESSION['Items' . $identifier]->Branch = $MyRow['cashsalebranch'];
		}

		$_SESSION['Items' . $identifier]->LocationName = $MyRow['locationname'];
		$_SESSION['Items' . $identifier]->Location = $_SESSION['UserStockLocation'];
		$_SESSION['Items' . $identifier]->DispatchTaxProvince = $MyRow['taxprovinceid'];

		// Now check to ensure this account exists and set defaults */
		$SQL = "SELECT debtorsmaster.name,
					holdreasons.dissallowinvoices,
					debtorsmaster.salestype,
					salestypes.sales_type,
					debtorsmaster.currcode,
					debtorsmaster.customerpoline,
					paymentterms.terms,
					currencies.decimalplaces
				FROM debtorsmaster INNER JOIN holdreasons
				ON debtorsmaster.holdreason=holdreasons.reasoncode
				INNER JOIN salestypes
				ON debtorsmaster.salestype=salestypes.typeabbrev
				INNER JOIN paymentterms
				ON debtorsmaster.paymentterms=paymentterms.termsindicator
				INNER JOIN currencies
				ON debtorsmaster.currcode=currencies.currabrev
				WHERE debtorsmaster.debtorno = '" . $_SESSION['Items' . $identifier]->DebtorNo . "'";

		$ErrMsg = _('The details of the customer selected') . ': ' .  $_SESSION['Items' . $identifier]->DebtorNo . ' ' . _('cannot be retrieved because');
		$DbgMsg = _('The SQL used to retrieve the customer details and failed was') . ':';
		$result =DB_query($SQL,$ErrMsg,$DbgMsg);

		$MyRow = DB_fetch_array($result);
		$_SESSION['RequireCustomerSelection']=0;
		$_SESSION['Items' . $identifier]->CustomerName = $MyRow['name'];
		// the sales type is the price list to be used for this sale
		$_SESSION['Items' . $identifier]->DefaultSalesType = $MyRow['salestype'];
		$_SESSION['Items' . $identifier]->SalesTypeName = $MyRow['sales_type'];
		$_SESSION['Items' . $identifier]->DefaultCurrency = $MyRow['currcode'];
		$_SESSION['Items' . $identifier]->DefaultPOLine = $MyRow['customerpoline'];
		$_SESSION['Items' . $identifier]->PaymentTerms = $MyRow['terms'];
		$_SESSION['Items' . $identifier]->CurrDecimalPlaces = $MyRow['decimalplaces'];
			/* now get the branch defaults from the customer branches table CustBranch. */

		$SQL = "SELECT custbranch.brname,
				       custbranch.braddress1,
				       custbranch.defaultshipvia,
				       custbranch.deliverblind,
				       custbranch.specialinstructions,
				       custbranch.estdeliverydays,
				       custbranch.salesman,
				       custbranch.taxgroupid,
				       custbranch.defaultshipvia
				FROM custbranch
				WHERE custbranch.branchcode='" . $_SESSION['Items' . $identifier]->Branch . "'
				AND custbranch.debtorno = '" . $_SESSION['Items' . $identifier]->DebtorNo . "'";
        $ErrMsg = _('The customer branch record of the customer selected') . ': ' . $_SESSION['Items' . $identifier]->Branch . ' ' . _('cannot be retrieved because');
		$DbgMsg = _('SQL used to retrieve the branch details was') . ':';
		$result =DB_query($SQL,$ErrMsg,$DbgMsg);

		if (DB_num_rows($result)==0) {

			prnMsg(_('The branch details for branch code') . ': ' . $_SESSION['Items' . $identifier]->Branch . ' ' . _('against customer code') . ': ' . $_SESSION['Items' . $identifier]->DebtorNo . ' ' . _('could not be retrieved') . '. ' . _('Check the set up of the customer and branch'),'error');

			if ($debug==1) {
				echo '<br />' . _('The SQL that failed to get the branch details was') . ':<br />' . $SQL;
			}
			include('includes/footer.php');
			exit;
		}
		// add echo
		echo '<br />';
		$MyRow = DB_fetch_array($result);

		$_SESSION['Items' . $identifier]->DeliverTo = '';
		$_SESSION['Items' . $identifier]->DelAdd1 = $MyRow['braddress1'];
		$_SESSION['Items' . $identifier]->ShipVia = $MyRow['defaultshipvia'];
		$_SESSION['Items' . $identifier]->DeliverBlind = $MyRow['deliverblind'];
		$_SESSION['Items' . $identifier]->SpecialInstructions = $MyRow['specialinstructions'];
		$_SESSION['Items' . $identifier]->DeliveryDays = $MyRow['estdeliverydays'];
		$_SESSION['Items' . $identifier]->TaxGroup = $MyRow['taxgroupid'];
		$_SESSION['Items' . $identifier]->TaxGroup = $MyRow['taxgroupid'];
		$_SESSION['Items' . $identifier]->SalesPerson = $MyRow['salesman'];
		if ($_SESSION['Items' . $identifier]->SpecialInstructions) {
			prnMsg($_SESSION['Items' . $identifier]->SpecialInstructions,'warn');
		}
	} // user does not have valid inventory location
} // end if its a new return to be set up

if (isset($_POST['CancelReturn'])) {

	unset($_SESSION['Items' . $identifier]->LineItems);
	$_SESSION['Items' . $identifier]->ItemsOrdered = 0;
	unset($_SESSION['Items' . $identifier]);
	$_SESSION['Items' . $identifier] = new cart;

	echo '<br /><br />';
	prnMsg(_('This return has been cancelled as requested'),'success');
	echo '<br /><br /><a href="' .htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('Start a new Counter Return') . '</a>';
	include('includes/footer.php');
	exit;

} else { /*Not cancelling the return */

	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Counter Return') . '" alt="" />' . ' ';
	echo '<font color="red" size="5">' . $_SESSION['Items' . $identifier]->CustomerName . '<br /> ' . _('Counter Return') . ' ' . _('to') . ' ' . $_SESSION['Items' . $identifier]->LocationName . ' ' . _('inventory') . ' (' . _('all amounts in') . ' ' . $_SESSION['Items' . $identifier]->DefaultCurrency . ')';
	echo '</font></p>';
}

if (isset($_POST['Search']) OR isset($_POST['Next']) OR isset($_POST['Prev'])) {

	if ($_POST['Keywords']!='' AND $_POST['StockCode']=='') {
		$msg='<div class="page_help_text">' . _('Item description has been used in search') . '.</div>';
	} else if ($_POST['StockCode']!='' AND $_POST['Keywords']=='') {
		$msg='<div class="page_help_text">' . _('Item Code has been used in search') . '.</div>';
	} else if ($_POST['Keywords']=='' AND $_POST['StockCode']=='') {
		$msg='<div class="page_help_text">' . _('Stock Category has been used in search') . '.</div>';
	}
	if (isset($_POST['Keywords']) AND mb_strlen($_POST['Keywords'])>0) {
		//insert wildcard characters in spaces
		$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		if ($_POST['StockCat']=='All') {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.decmimalplaces
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.mbflag <>'G'
					AND stockmaster.controlled <> 1
					AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					AND stockmaster.discontinued=0
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.decimalplaces
					FROM stockmaster INNER JOIN stockcategory
					ON  stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.mbflag <>'G'
					AND stockmaster.controlled <> 1
					AND stockmaster.discontinued=0
					AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}

	} else if (mb_strlen($_POST['StockCode'])>0) {

		$_POST['StockCode'] = mb_strtoupper($_POST['StockCode']);
		$SearchString = '%' . $_POST['StockCode'] . '%';

		if ($_POST['StockCat']=='All') {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.decimalplaces
					FROM stockmaster INNER JOIN stockcategory
					  ON stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					AND stockmaster.mbflag <>'G'
					AND stockmaster.controlled <> 1
					AND stockmaster.discontinued=0
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.decimalplaces
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					AND (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					AND stockmaster.mbflag <>'G'
					AND stockmaster.controlled <> 1
					AND stockmaster.discontinued=0
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		}

	} else {
		if ($_POST['StockCat']=='All') {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.decimalplaces
					FROM stockmaster INNER JOIN stockcategory
					ON  stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.mbflag <>'G'
					AND stockmaster.controlled <> 1
					AND stockmaster.discontinued=0
					ORDER BY stockmaster.stockid";
        	} else {
			$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.longdescription,
						stockmaster.units,
						stockmaster.decimalplaces
					FROM stockmaster INNER JOIN stockcategory
					ON stockmaster.categoryid=stockcategory.categoryid
					WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='D')
					AND stockmaster.mbflag <>'G'
					AND stockmaster.controlled <> 1
					AND stockmaster.discontinued=0
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					ORDER BY stockmaster.stockid";
		  }
	}

	if (isset($_POST['Next'])) {
		$Offset = $_POST['NextList'];
	}
	if (isset($_POST['Prev'])) {
		$Offset = $_POST['previous'];
	}
	if (!isset($Offset) or $Offset<0) {
		$Offset=0;
	}
	$SQL = $SQL . ' LIMIT ' . $_SESSION['DefaultDisplayRecordsMax'].' OFFSET '.strval($_SESSION['DefaultDisplayRecordsMax']*$Offset);

	$ErrMsg = _('There is a problem selecting the part records to display because');
	$DbgMsg = _('The SQL used to get the part selection was');
	$SearchResult = DB_query($SQL,$ErrMsg, $DbgMsg);

	if (DB_num_rows($SearchResult)==0 ) {
		prnMsg (_('There are no products available meeting the criteria specified'),'info');
	}
	if (DB_num_rows($SearchResult)==1) {
		$MyRow=DB_fetch_array($SearchResult);
		$NewItem = $MyRow['stockid'];
		DB_data_seek($SearchResult,0);
	}
	if (DB_num_rows($SearchResult)< $_SESSION['DisplayRecordsMax']) {
		$Offset=0;
	}

} //end of if search


/* Always do the stuff below */

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier . '" name="SelectParts" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

//Get The exchange rate used for GPPercent calculations on adding or amending items
if ($_SESSION['Items' . $identifier]->DefaultCurrency != $_SESSION['CompanyRecord']['currencydefault']) {
	$ExRateResult = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['Items' . $identifier]->DefaultCurrency . "'");
	if (DB_num_rows($ExRateResult)>0) {
		$ExRateRow = DB_fetch_row($ExRateResult);
		$ExRate = $ExRateRow[0];
	} else {
		$ExRate =1;
	}
} else {
	$ExRate = 1;
}

/*Process Quick Entry */
/* If enter is pressed on the quick entry screen, the default button may be Recalculate */
 if (isset($_POST['SelectingReturnItems'])
		OR isset($_POST['QuickEntry'])
		OR isset($_POST['Recalculate'])) {

	/* get the item details from the database and hold them in the cart object */

	/*Discount can only be set later on  -- after quick entry -- so default discount to 0 in the first place */
	$Discount = 0;
	$i=1;
	while ($i<=$_SESSION['QuickEntries']
			AND isset($_POST['part_' . $i])
			AND $_POST['part_' . $i]!='') {

		$QuickEntryCode = 'part_' . $i;
		$QuickEntryQty = 'qty_' . $i;

		$i++;

		if (isset($_POST[$QuickEntryCode])) {
			$NewItem = mb_strtoupper($_POST[$QuickEntryCode]);
		}
		if (isset($_POST[$QuickEntryQty])) {
			$NewItemQty = filter_number_format($_POST[$QuickEntryQty]);
		}
		$NewItemDue = Date($_SESSION['DefaultDateFormat']);
		$NewPOLine = 0;


		if (!isset($NewItem)) {
			unset($NewItem);
			break;	/* break out of the loop if nothing in the quick entry fields*/
		}


		/*Now figure out if the item is a kit set - the field MBFlag='K'*/
		$SQL = "SELECT stockmaster.mbflag,
						stockmaster.controlled
				FROM stockmaster
				WHERE stockmaster.stockid='". $NewItem ."'";

		$ErrMsg = _('Could not determine if the part being ordered was a kitset or not because');
		$DbgMsg = _('The sql that was used to determine if the part being ordered was a kitset or not was ');
		$KitResult = DB_query($SQL,$ErrMsg,$DbgMsg);


		if (DB_num_rows($KitResult)==0) {
			prnMsg( _('The item code') . ' ' . $NewItem . ' ' . _('could not be retrieved from the database and has not been added to the return'),'warn');
		} elseif ($MyRow=DB_fetch_array($KitResult)) {
			if ($MyRow['mbflag']=='K') {	/*It is a kit set item */
				$SQL = "SELECT bom.component,
							bom.quantity
						FROM bom
						WHERE bom.parent='" . $NewItem . "'
                        AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                        AND bom.effectiveto > '" . date('Y-m-d') . "'";

				$ErrMsg =  _('Could not retrieve kitset components from the database because') . ' ';
				$KitResult = DB_query($SQL,$ErrMsg,$DbgMsg);

				$ParentQty = $NewItemQty;
				while ($KitParts = DB_fetch_array($KitResult)) {
					$NewItem = $KitParts['component'];
					$NewItemQty = $KitParts['quantity'] * $ParentQty;
					$NewPOLine = 0;
					include('includes/SelectOrderItems_IntoCart.inc');
					$_SESSION['Items' . $identifier]->GetTaxes(($_SESSION['Items' . $identifier]->LineCounter - 1));
				}

			} else if ($MyRow['mbflag']=='G') {
				prnMsg(_('Phantom assemblies cannot be returned, these items exist only as bills of materials used in other manufactured items. The following item has not been added to the return:') . ' ' . $NewItem, 'warn');
			} else if ($MyRow['controlled']==1) {
				prnMsg(_('The system does not currently cater for counter returns of lot controlled or serialised items'),'warn');
			} else if ($NewItemQty<=0) {
				prnMsg(_('Only items entered with a positive quantity can be added to the return'),'warn');
			} else { /*Its not a kit set item*/
				include('includes/SelectOrderItems_IntoCart.inc');
				$_SESSION['Items' . $identifier]->GetTaxes(($_SESSION['Items' . $identifier]->LineCounter - 1));
			}
		}
	 }
	 unset($NewItem);
 } /* end of if quick entry */

 /*Now do non-quick entry delete/edits/adds */

if ((isset($_SESSION['Items' . $identifier])) OR isset($NewItem)) {

	if (isset($_GET['Delete'])) {
		$_SESSION['Items' . $identifier]->remove_from_cart($_GET['Delete']);  /*Don't do any DB updates*/
	}

	foreach ($_SESSION['Items' . $identifier]->LineItems as $ReturnItemLine) {

		if (isset($_POST['Quantity_' . $ReturnItemLine->LineNumber])) {

			$Quantity = round(filter_number_format($_POST['Quantity_' . $ReturnItemLine->LineNumber]),$ReturnItemLine->DecimalPlaces);

				if (ABS($ReturnItemLine->Price - filter_number_format($_POST['Price_' . $ReturnItemLine->LineNumber]))>0.01) {
					/*There is a new price being input for the line item */

					$Price = filter_number_format($_POST['Price_' . $ReturnItemLine->LineNumber]);
					$_POST['GPPercent_' . $ReturnItemLine->LineNumber] = (($Price*(1-(filter_number_format($_POST['Discount_' . $ReturnItemLine->LineNumber])/100))) - $ReturnItemLine->StandardCost*$ExRate)/($Price *(1-filter_number_format($_POST['Discount_' . $ReturnItemLine->LineNumber]))/100);

				} elseif (ABS($ReturnItemLine->GPPercent - filter_number_format($_POST['GPPercent_' . $ReturnItemLine->LineNumber]))>=0.01) {
					/* A GP % has been input so need to do a recalculation of the price at this new GP Percentage */


					prnMsg(_('Recalculated the price from the GP % entered - the GP % was') . ' ' . $ReturnItemLine->GPPercent . '  the new GP % is ' . filter_number_format($_POST['GPPercent_' . $ReturnItemLine->LineNumber]),'info');


					$Price = ($ReturnItemLine->StandardCost*$ExRate)/(1 -((filter_number_format($_POST['GPPercent_' . $ReturnItemLine->LineNumber]) + filter_number_format($_POST['Discount_' . $ReturnItemLine->LineNumber]))/100));
				} else {
					$Price = filter_number_format($_POST['Price_' . $ReturnItemLine->LineNumber]);
				}
				$DiscountPercentage = filter_number_format($_POST['Discount_' . $ReturnItemLine->LineNumber]);
				if ($_SESSION['AllowOrderLineItemNarrative'] == 1) {
					$Narrative = $_POST['Narrative_' . $ReturnItemLine->LineNumber];
				} else {
					$Narrative = '';
				}

				if (!isset($ReturnItemLine->DiscountPercent)) {
					$ReturnItemLine->DiscountPercent = 0;
				}

			if ($Quantity<0 or $Price <0 or $DiscountPercentage >100 or $DiscountPercentage <0) {
				prnMsg(_('The item could not be updated because you are attempting to set the quantity returned to less than 0 or the price less than 0 or the discount more than 100% or less than 0%'),'warn');
			} else if ($ReturnItemLine->Quantity !=$Quantity
						OR $ReturnItemLine->Price != $Price
						OR abs($ReturnItemLine->DiscountPercent -$DiscountPercentage/100) >0.001
						OR $ReturnItemLine->Narrative != $Narrative
						OR $ReturnItemLine->ItemDue != $_POST['ItemDue_' . $ReturnItemLine->LineNumber]
						OR $ReturnItemLine->POLine != $_POST['POLine_' . $ReturnItemLine->LineNumber]) {

				$_SESSION['Items' . $identifier]->update_cart_item($ReturnItemLine->LineNumber,
																$Quantity,
																$Price,
																$DiscountPercentage/100,
																$Narrative,
																'Yes', /*Update DB */
																$_POST['ItemDue_' . $ReturnItemLine->LineNumber],
																$_POST['POLine_' . $ReturnItemLine->LineNumber],
																filter_number_format($_POST['GPPercent_' . $ReturnItemLine->LineNumber]),
																$identifier);
			}
		} //page not called from itself - POST variables not set
	}
}

if (isset($_POST['Recalculate'])) {
	foreach ($_SESSION['Items' . $identifier]->LineItems as $ReturnItemLine) {
		$NewItem=$ReturnItemLine->StockID;
		$SQL = "SELECT stockmaster.mbflag,
						stockmaster.controlled
				FROM stockmaster
				WHERE stockmaster.stockid='". $ReturnItemLine->StockID."'";

		$ErrMsg = _('Could not determine if the part being ordered was a kitset or not because');
		$DbgMsg = _('The sql that was used to determine if the part being ordered was a kitset or not was ');
		$KitResult = DB_query($SQL,$ErrMsg,$DbgMsg);
		if ($MyRow=DB_fetch_array($KitResult)) {
			if ($MyRow['mbflag']=='K') {	/*It is a kit set item */
				$SQL = "SELECT bom.component,
								bom.quantity
							FROM bom
							WHERE bom.parent='" . $ReturnItemLine->StockID. "'
                            AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                            AND bom.effectiveto > '" . date('Y-m-d') . "'";

				$ErrMsg = _('Could not retrieve kitset components from the database because');
				$KitResult = DB_query($SQL,$ErrMsg);

				$ParentQty = $NewItemQty;
				while ($KitParts = DB_fetch_array($KitResult)) {
					$NewItem = $KitParts['component'];
					$NewItemQty = $KitParts['quantity'] * $ParentQty;
					$NewPOLine = 0;
					$NewItemDue = date($_SESSION['DefaultDateFormat']);
					$_SESSION['Items' . $identifier]->GetTaxes($ReturnItemLine->LineNumber);
				}

			} else { /*Its not a kit set item*/
				$NewItemDue = date($_SESSION['DefaultDateFormat']);
				$NewPOLine = 0;
				$_SESSION['Items' . $identifier]->GetTaxes($ReturnItemLine->LineNumber);
			}
		}
		unset($NewItem);
	} /* end of if its a new item */
}

if (isset($NewItem)) {
/* get the item details from the database and hold them in the cart object make the quantity 1 by default then add it to the cart
Now figure out if the item is a kit set - the field MBFlag='K'
* controlled items and ghost/phantom items cannot be selected because the SQL to show items to select doesn't show 'em
* */

	$SQL = "SELECT stockmaster.mbflag,
				stockmaster.taxcatid
			FROM stockmaster
			WHERE stockmaster.stockid='". $NewItem ."'";

	$ErrMsg =  _('Could not determine if the part being ordered was a kitset or not because');

	$KitResult = DB_query($SQL,$ErrMsg);

	$NewItemQty = 1; /*By Default */
	$Discount = 0; /*By default - can change later or discount category override */

	if ($MyRow=DB_fetch_array($KitResult)) {
	   	if ($MyRow['mbflag']=='K') {	/*It is a kit set item */
			$SQL = "SELECT bom.component,
						bom.quantity
					FROM bom
					WHERE bom.parent='" . $NewItem . "'
                    AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                    AND bom.effectiveto > '" . date('Y-m-d') . "'";

			$ErrMsg = _('Could not retrieve kitset components from the database because');
			$KitResult = DB_query($SQL,$ErrMsg);

			$ParentQty = $NewItemQty;
			while ($KitParts = DB_fetch_array($KitResult)) {
				$NewItem = $KitParts['component'];
				$NewItemQty = $KitParts['quantity'] * $ParentQty;
				$NewPOLine = 0;
				$NewItemDue = date($_SESSION['DefaultDateFormat']);
				include('includes/SelectOrderItems_IntoCart.inc');
				$_SESSION['Items' . $identifier]->GetTaxes(($_SESSION['Items' . $identifier]->LineCounter - 1));
			}

		} else { /*Its not a kit set item*/
			$NewItemDue = date($_SESSION['DefaultDateFormat']);
			$NewPOLine = 0;

			include('includes/SelectOrderItems_IntoCart.inc');
			$_SESSION['Items' . $identifier]->GetTaxes(($_SESSION['Items' . $identifier]->LineCounter - 1));
		}

	} /* end of if its a new item */

} /*end of if its a new item */

if (isset($NewItemArray) AND isset($_POST['SelectingReturnItems'])) {
/* get the item details from the database and hold them in the cart object make the quantity 1 by default then add it to the cart */
/*Now figure out if the item is a kit set - the field MBFlag='K'*/

	foreach($NewItemArray as $NewItem => $NewItemQty) {
		if($NewItemQty > 0)	{
			$SQL = "SELECT stockmaster.mbflag
					FROM stockmaster
					WHERE stockmaster.stockid='". $NewItem ."'";

			$ErrMsg =  _('Could not determine if the part being returned was a kitset or not because');

			$KitResult = DB_query($SQL,$ErrMsg);

			//$NewItemQty = 1; /*By Default */
			$Discount = 0; /*By default - can change later or discount category override */

			if ($MyRow=DB_fetch_array($KitResult)) {
				if ($MyRow['mbflag']=='K') {	/*It is a kit set item */
					$SQL = "SELECT bom.component,
	        					bom.quantity
		          			FROM bom
							WHERE bom.parent='" . $NewItem . "'
                            AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                            AND bom.effectiveto > '" . date('Y-m-d') . "'";

					$ErrMsg = _('Could not retrieve kitset components from the database because');
					$KitResult = DB_query($SQL,$ErrMsg);

					$ParentQty = $NewItemQty;
					while ($KitParts = DB_fetch_array($KitResult)) {
						$NewItem = $KitParts['component'];
						$NewItemQty = $KitParts['quantity'] * $ParentQty;
						$NewItemDue = date($_SESSION['DefaultDateFormat']);
						$NewPOLine = 0;
						include('includes/SelectOrderItems_IntoCart.inc');
						$_SESSION['Items' . $identifier]->GetTaxes(($_SESSION['Items' . $identifier]->LineCounter - 1));
					}

				} else { /*Its not a kit set item*/
					$NewItemDue = date($_SESSION['DefaultDateFormat']);
					$NewPOLine = 0;
					include('includes/SelectOrderItems_IntoCart.inc');
					$_SESSION['Items' . $identifier]->GetTaxes(($_SESSION['Items' . $identifier]->LineCounter - 1));
				}
			} /* end of if its a new item */
		} /*end of if its a new item */
	}
}


if (count($_SESSION['Items' . $identifier]->LineItems)>0) { /*only show return lines if there are any */
/*
// *************************************************************************
//   T H I S   W H E R E   T H E   R E T U R N  I S   D I S P L A Y E D
// *************************************************************************
*/

	echo '<br />
		<table width="90%" cellpadding="2" colspan="7">
		<tr bgcolor="#800000">';
	echo '<th>' . _('Item Code') . '</th>
   	      <th>' . _('Item Description') . '</th>
	      <th>' . _('Quantity') . '</th>
	      <th>' . _('Unit') . '</th>
	      <th>' . _('Price') . '</th>
		  <th>' . _('Discount') . '</th>
		  <th>' . _('GP %') . '</th>
		  <th>' . _('Net') . '</th>
	      <th>' . _('Tax') . '</th>
	      <th>' . _('Total') . '<br />' . _('Incl Tax') . '</th>
	      </tr>';

	$_SESSION['Items' . $identifier]->total = 0;
	$_SESSION['Items' . $identifier]->totalVolume = 0;
	$_SESSION['Items' . $identifier]->totalWeight = 0;
	$TaxTotals = array();
	$TaxGLCodes = array();
	$TaxTotal =0;

	foreach ($_SESSION['Items' . $identifier]->LineItems as $ReturnItemLine) {

		$SubTotal = $ReturnItemLine->Quantity * $ReturnItemLine->Price * (1 - $ReturnItemLine->DiscountPercent);
		$DisplayDiscount = locale_number_format(($ReturnItemLine->DiscountPercent * 100),2);
		$QtyReturned = $ReturnItemLine->Quantity;

		echo '<input type="hidden" name="POLine_' .	 $ReturnItemLine->LineNumber . '" value="" />';
		echo '<input type="hidden" name="ItemDue_' .	 $ReturnItemLine->LineNumber . '" value="'.$ReturnItemLine->ItemDue.'" />';

		echo '<tr class="striped_row">
				<td><a target="_blank" href="' . $RootPath . '/StockStatus.php?identifier='.$identifier . '&StockID=' . $ReturnItemLine->StockID . '&DebtorNo=' . $_SESSION['Items' . $identifier]->DebtorNo . '">' . $ReturnItemLine->StockID . '</a></td>
			<td title="' . $ReturnItemLine->LongDescription . '">' . $ReturnItemLine->ItemDescription . '</td>';

		echo '<td><input class="number" tabindex="2" type="text" name="Quantity_' . $ReturnItemLine->LineNumber . '" required="required" size="6" maxlength="6" value="' . locale_number_format($ReturnItemLine->Quantity,$ReturnItemLine->DecimalPlaces) . '" />';

		echo '</td>
				<td>' . $ReturnItemLine->Units . '</td>
				<td><input class="number" type="text" name="Price_' . $ReturnItemLine->LineNumber . '" required="required" size="16" maxlength="16" value="' . locale_number_format($ReturnItemLine->Price,$_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '" /></td>
				<td><input class="number" type="text" name="Discount_' . $ReturnItemLine->LineNumber . '" required="required" size="5" maxlength="4" value="' . locale_number_format(($ReturnItemLine->DiscountPercent * 100),2) . '" /></td>
				<td><input class="number" type="text" name="GPPercent_' . $ReturnItemLine->LineNumber . '" required="required" size="3" maxlength="40" value="' . locale_number_format($ReturnItemLine->GPPercent,2) . '" /></td>
				<td class="number">' . locale_number_format($SubTotal,$_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>';
		$LineDueDate = $ReturnItemLine->ItemDue;
		$_SESSION['Items' . $identifier]->LineItems[$ReturnItemLine->LineNumber]->ItemDue= $LineDueDate;

		$i=0; // initialise the number of taxes iterated through
		$TaxLineTotal =0; //initialise tax total for the line

		foreach ($ReturnItemLine->Taxes AS $Tax) {
			if (empty($TaxTotals[$Tax->TaxAuthID])) {
				$TaxTotals[$Tax->TaxAuthID]=0;
			}
			if ($Tax->TaxOnTax ==1) {
				$TaxTotals[$Tax->TaxAuthID] += ($Tax->TaxRate * ($SubTotal + $TaxLineTotal));
				$TaxLineTotal += ($Tax->TaxRate * ($SubTotal + $TaxLineTotal));
			} else {
				$TaxTotals[$Tax->TaxAuthID] += ($Tax->TaxRate * $SubTotal);
				$TaxLineTotal += ($Tax->TaxRate * $SubTotal);
			}
			$TaxGLCodes[$Tax->TaxAuthID] = $Tax->TaxGLCode;
		}

		$TaxTotal += $TaxLineTotal;
		$_SESSION['Items' . $identifier]->TaxTotals=$TaxTotals;
		$_SESSION['Items' . $identifier]->TaxGLCodes=$TaxGLCodes;
		echo '<td class="number">' . locale_number_format($TaxLineTotal ,$_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>';
		echo '<td class="number">' . locale_number_format($SubTotal + $TaxLineTotal ,$_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>';
		echo '<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier . '&Delete=' . $ReturnItemLine->LineNumber . '" onclick="return confirm(\'' . _('Are You Sure?') . '\');">' . _('Delete') . '</a></td></tr>';

		if ($_SESSION['AllowOrderLineItemNarrative'] == 1) {
			echo '<tr class="striped_row">
					<td valign="top" colspan="11">' . _('Narrative') . ':<textarea name="Narrative_' . $ReturnItemLine->LineNumber . '" cols="100" rows="1">' . stripslashes(AddCarriageReturns($ReturnItemLine->Narrative)) . '</textarea><br /></td></tr>';
		} else {
			echo '<input type="hidden" name="Narrative" value="" />';
		}

		$_SESSION['Items' . $identifier]->total += $SubTotal;
		$_SESSION['Items' . $identifier]->totalVolume += $ReturnItemLine->Quantity * $ReturnItemLine->Volume;
		$_SESSION['Items' . $identifier]->totalWeight += $ReturnItemLine->Quantity * $ReturnItemLine->Weight;

	} /* end of loop around items */

	echo '<tr class="striped_row">';
	echo '<td colspan="7" class="number"><b>' . _('Total') . '</b></td>';
	echo '<td class="number">' . locale_number_format(($_SESSION['Items' . $identifier]->total),$_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>
			<td class="number">' . locale_number_format($TaxTotal,$_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>
			<td class="number">' . locale_number_format(($_SESSION['Items' . $identifier]->total+$TaxTotal),$_SESSION['Items' . $identifier]->CurrDecimalPlaces) . '</td>
		</tr>
		</table>';
	echo '<input type="hidden" name="TaxTotal" value="'.$TaxTotal.'" />';
	echo '<table><tr><td>';
	//nested table
	echo '<table><tr>
		<td style="color:red">' .  _('Returned By') .':</td>
		<td><input type="text" size="25" maxlength="25" name="DeliverTo" value="' . stripslashes($_SESSION['Items' . $identifier]->DeliverTo) . '" /></td>
	</tr>';
	echo '<tr>
		<td style="color:red">' .  _('Contact Phone Number') .':</td>
		<td><input type="tel" size="25" maxlength="25" name="PhoneNo"  required="required" title="' . _('A phone number is required for counter returns') . '" value="' . stripslashes($_SESSION['Items' . $identifier]->PhoneNo) . '" /></td>
	</tr>';

	echo '<tr>
			<td style="color:red">' . _('Contact Email') . ':</td>
			<td><input type="email" size="25" maxlength="30" name="Email" value="' . stripslashes($_SESSION['Items' . $identifier]->Email) . '" /></td></tr>';

	echo '<tr>
			<td style="color:red">' .  _('Customer Reference') .':</td>
			<td><input type="text" size="25" maxlength="25" name="CustRef" value="' . stripcslashes($_SESSION['Items' . $identifier]->CustRef) . '" /></td>
		</tr>';
	echo '<tr>
			<td style="color:red">' . _('Sales person'). ':</td>
			<td><select name="SalesPerson">';
	$SalesPeopleResult = DB_query("SELECT salesmancode, salesmanname FROM salesman WHERE current=1");
	if (!isset($_POST['SalesPerson']) AND $_SESSION['SalesmanLogin']!=NULL ) {
		$_SESSION['Items' . $identifier]->SalesPerson = $_SESSION['SalesmanLogin'];
	}
	while ($SalesPersonRow = DB_fetch_array($SalesPeopleResult)) {
		if ($SalesPersonRow['salesmancode']==$_SESSION['Items' . $identifier]->SalesPerson) {
			echo '<option selected="selected" value="' . $SalesPersonRow['salesmancode'] . '">' . $SalesPersonRow['salesmanname'] . '</option>';
		} else {
			echo '<option value="' . $SalesPersonRow['salesmancode'] . '">' . $SalesPersonRow['salesmanname'] . '</option>';
		}
	}

	echo '</select></td>
		</tr>';
	echo '<tr>
		<td style="color:red">' .  _('Reason for Return') .':</td>
		<td><textarea name="Comments" cols="23" rows="5">' . stripcslashes($_SESSION['Items' . $identifier]->Comments)  . '</textarea></td>
	</tr>';
	echo '</table>'; //end the sub table in the first column of master table
	echo '</td><th valign="bottom">'; //for the master table
	echo '<table class="selection">'; // a new nested table in the second column of master table

	//now the payment stuff in this column
	$PaymentMethodsResult = DB_query("SELECT paymentid, paymentname FROM paymentmethods");

	echo '<tr>
			<td style="color:red">' . _('Payment Type') . ':</td>
			<td><select name="PaymentMethod">';
	while ($PaymentMethodRow = DB_fetch_array($PaymentMethodsResult)) {
		if (isset($_POST['PaymentMethod']) AND $_POST['PaymentMethod'] == $PaymentMethodRow['paymentid']) {
			echo '<option selected="selected" value="' . $PaymentMethodRow['paymentid'] . '">' . $PaymentMethodRow['paymentname'] . '</option>';
		} else {
			echo '<option value="' . $PaymentMethodRow['paymentid'] . '">' . $PaymentMethodRow['paymentname'] . '</option>';
		}
	}
	echo '</select></td></tr>';

	$BankAccountsResult = DB_query("SELECT bankaccountname, accountcode, currcode FROM bankaccounts ORDER BY bankaccountname");

	echo '<tr>
			<td style="color:red">' . _('Bank Account') . ':</td>
			<td><select name="BankAccount">';
	while ($MyRow = DB_fetch_array($BankAccountsResult)) {
		// Lists bank accounts order by bankaccountname
		echo '<option',
			((isset($_POST['BankAccount']) and $_POST['BankAccount'] == $MyRow['accountcode']) ? ' selected="selected"' : '' ),
			' value="', $MyRow['accountcode'], '">', $MyRow['bankaccountname'], ' - ', $MyRow['currcode'], '</option>';
	}
	echo '</select></td>
		</tr>';
	if (!isset($_POST['AmountPaid'])) {
		$_POST['AmountPaid'] =0;
	}
	echo '<tr>
			<td style="color:red">' . _('Paid to Customer') . ':</td>
			<td><input type="text" class="number" name="AmountPaid"  required="required" maxlength="12" size="12" value="' . $_POST['AmountPaid'] . '" /></td>
		</tr>';

	echo '</table>'; //end the sub table in the second column of master table
	echo '</th></tr></table>';	//end of column/row/master table
	if (!isset($_POST['ProcessReturn'])) {
		echo '<br />
				<div class="centre">
					<input type="submit" name="Recalculate" value="' . _('Re-Calculate') . '" />
					<input type="submit" name="ProcessReturn" value="' . _('Process The Return') . '" />
				</div>';
	}
	echo '<hr />';
} # end of if lines

/* **********************************
 * Credit Note Processing Here
 * **********************************
 * */
if (isset($_POST['ProcessReturn']) AND $_POST['ProcessReturn'] != '') {

	$InputError = false; //always assume the best
	//but check for the worst
	if ($_SESSION['Items' . $identifier]->LineCounter == 0) {
		prnMsg(_('There are no lines on this return. Please enter lines to return first'),'error');
		$InputError = true;
	}
	if (abs(filter_number_format($_POST['AmountPaid']) -round($_SESSION['Items' . $identifier]->total+filter_number_format($_POST['TaxTotal']),$_SESSION['Items' . $identifier]->CurrDecimalPlaces))>=0.01) {
		prnMsg(_('The amount entered as payment to the customer does not equal the amount of the return. Please correct amount and re-enter'),'error');
		$InputError = true;
	}

	if ($InputError == false) { //all good so let's get on with the processing

	/* Now Get the area where the sale is to from the branches table */

		$SQL = "SELECT 	area,
						defaultshipvia
				FROM custbranch
				WHERE custbranch.debtorno ='". $_SESSION['Items' . $identifier]->DebtorNo . "'
				AND custbranch.branchcode = '" . $_SESSION['Items' . $identifier]->Branch . "'";

		$ErrMsg = _('We were unable to load the area where the sale is to from the custbranch table');
		$Result = DB_query($SQL, $ErrMsg);
		$MyRow = DB_fetch_row($Result);
		$Area = $MyRow[0];
		$DefaultShipVia = $MyRow[1];
		DB_free_result($Result);

	/*company record read in on login with info on GL Links and debtors GL account*/

		if ($_SESSION['CompanyRecord']==0) {
			/*The company data and preferences could not be retrieved for some reason */
			prnMsg( _('The company information and preferences could not be retrieved. See your system administrator'), 'error');
			include('includes/footer.php');
			exit;
		}

	// *************************************************************************
	//   S T A R T   O F   C R E D I T  N O T E   S Q L   P R O C E S S I N G
	// *************************************************************************
		$result = DB_Txn_Begin();

	/*Now Get the next invoice number - GetNextTransNo() function in SQL_CommonFunctions
	 * GetPeriod() in includes/DateFunctions.inc */

		$CreditNoteNo = GetNextTransNo(11);
		$PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat']));

		$ReturnDate = Date('Y-m-d');

	/*Now insert the DebtorTrans */

		$SQL = "INSERT INTO debtortrans (transno,
										type,
										debtorno,
										branchcode,
										trandate,
										inputdate,
										prd,
										reference,
										tpe,
										ovamount,
										ovgst,
										rate,
										invtext,
										shipvia,
										alloc,
										salesperson )
			VALUES ('". $CreditNoteNo . "',
					11,
					'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
					'" . $_SESSION['Items' . $identifier]->Branch . "',
					'" . $ReturnDate . "',
					'" . date('Y-m-d H-i-s') . "',
					'" . $PeriodNo . "',
					'" . $_SESSION['Items' . $identifier]->CustRef  . "',
					'" . $_SESSION['Items' . $identifier]->DefaultSalesType . "',
					'" . -$_SESSION['Items' . $identifier]->total . "',
					'" . filter_number_format(-$_POST['TaxTotal']) . "',
					'" . $ExRate . "',
					'" . $_SESSION['Items' . $identifier]->Comments . "',
					'" . $_SESSION['Items' . $identifier]->ShipVia . "',
					'" . (-$_SESSION['Items' . $identifier]->total - filter_number_format($_POST['TaxTotal'])) . "',
					'" . $_SESSION['Items' . $identifier]->SalesPerson . "' )";

		$ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction record could not be inserted because');
		$DbgMsg = _('The following SQL to insert the debtor transaction record was used');
	 	$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		$DebtorTransID = DB_Last_Insert_ID('debtortrans','id');

	/* Insert the tax totals for each tax authority where tax was charged on the invoice */
		foreach ($_SESSION['Items' . $identifier]->TaxTotals AS $TaxAuthID => $TaxAmount) {

			$SQL = "INSERT INTO debtortranstaxes (debtortransid,
													taxauthid,
													taxamount)
										VALUES ('" . $DebtorTransID . "',
											'" . $TaxAuthID . "',
											'" . -$TaxAmount/$ExRate . "')";

			$ErrMsg =_('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The debtor transaction taxes records could not be inserted because');
			$DbgMsg = _('The following SQL to insert the debtor transaction taxes record was used');
	 		$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		}

		//Loop around each item on the sale and process each in turn
		foreach ($_SESSION['Items' . $identifier]->LineItems as $ReturnItemLine) {
			 /* Update location stock records if not a dummy stock item
			 need the MBFlag later too so save it to $MBFlag */
			$Result = DB_query("SELECT mbflag FROM stockmaster WHERE stockid = '" . $ReturnItemLine->StockID . "'");
			$MyRow = DB_fetch_row($Result);
			$MBFlag = $MyRow[0];
			if ($MBFlag=='B' OR $MBFlag=='M') {
				$Assembly = False;

				/* Need to get the current location quantity
				will need it later for the stock movement */
				$SQL="SELECT locstock.quantity
								FROM locstock
								WHERE locstock.stockid='" . $ReturnItemLine->StockID . "'
								AND loccode= '" . $_SESSION['Items' . $identifier]->Location . "'";
				$ErrMsg = _('WARNING') . ': ' . _('Could not retrieve current location stock');
				$Result = DB_query($SQL, $ErrMsg);

				if (DB_num_rows($Result)==1) {
					$LocQtyRow = DB_fetch_row($Result);
					$QtyOnHandPrior = $LocQtyRow[0];
				} else {
					/* There must be some error this should never happen */
					$QtyOnHandPrior = 0;
				}

				$SQL = "UPDATE locstock
							SET quantity = locstock.quantity + " . $ReturnItemLine->Quantity . "
						WHERE locstock.stockid = '" . $ReturnItemLine->StockID . "'
						AND loccode = '" . $_SESSION['Items' . $identifier]->Location . "'";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Location stock record could not be updated because');
				$DbgMsg = _('The following SQL to update the location stock record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			} else if ($MBFlag=='A') { /* its an assembly */
				/*Need to get the BOM for this part and make
				stock moves for the components then update the Location stock balances */
				$Assembly=True;
				$StandardCost =0; /*To start with - accumulate the cost of the comoponents for use in journals later on */
				$SQL = "SELECT bom.component,
						bom.quantity,
						stockmaster.materialcost+stockmaster.labourcost+stockmaster.overheadcost AS standard
						FROM bom,
							stockmaster
						WHERE bom.component=stockmaster.stockid
						AND bom.parent='" . $ReturnItemLine->StockID . "'
                        AND bom.effectiveafter <= '" . date('Y-m-d') . "'
                        AND bom.effectiveto > '" . date('Y-m-d') . "'";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not retrieve assembly components from the database for'). ' '. $ReturnItemLine->StockID . _('because').' ';
				$DbgMsg = _('The SQL that failed was');
				$AssResult = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				while ($AssParts = DB_fetch_array($AssResult)) {

					$StandardCost += ($AssParts['standard'] * $AssParts['quantity']) ;
					/* Need to get the current location quantity
					will need it later for the stock movement */
					$SQL="SELECT locstock.quantity
									FROM locstock
									WHERE locstock.stockid='" . $AssParts['component'] . "'
									AND loccode= '" . $_SESSION['Items' . $identifier]->Location . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Can not retrieve assembly components location stock quantities because ');
					$DbgMsg = _('The SQL that failed was');
					$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
					if (DB_num_rows($Result)==1) {
						$LocQtyRow = DB_fetch_row($Result);
						$QtyOnHandPrior = $LocQtyRow[0];
					} else {
						/*There must be some error this should never happen */
						$QtyOnHandPrior = 0;
					}
					if (empty($AssParts['standard'])) {
						$AssParts['standard']=0;
					}
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
													newqoh
						) VALUES (
													'" . $AssParts['component'] . "',
													 11,
													'" . $CreditNoteNo . "',
													'" . $_SESSION['Items' . $identifier]->Location . "',
													'" . $ReturnDate . "',
													'" . $_SESSION['UserID'] . "',
													'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
													'" . $_SESSION['Items' . $identifier]->Branch . "',
													'" . $PeriodNo . "',
													'" . _('Assembly') . ': ' . $ReturnItemLine->StockID . "',
													'" . $AssParts['quantity'] * $ReturnItemLine->Quantity . "',
													'" . $AssParts['standard'] . "',
													0,
													newqoh + " . ($AssParts['quantity'] * $ReturnItemLine->Quantity) . " )";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records for the assembly components of'). ' '. $ReturnItemLine->StockID . ' ' . _('could not be inserted because');
					$DbgMsg = _('The following SQL to insert the assembly components stock movement records was used');
					$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);


					$SQL = "UPDATE locstock
							SET quantity = locstock.quantity + " . ($AssParts['quantity'] * $ReturnItemLine->Quantity) . "
							WHERE locstock.stockid = '" . $AssParts['component'] . "'
							AND loccode = '" . $_SESSION['Items' . $identifier]->Location . "'";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Location stock record could not be updated for an assembly component because');
					$DbgMsg = _('The following SQL to update the locations stock record for the component was used');
					$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
				} /* end of assembly explosion and updates */

				/*Update the cart with the recalculated standard cost from the explosion of the assembly's components*/
				$_SESSION['Items' . $identifier]->LineItems[$ReturnItemLine->LineNumber]->StandardCost = $StandardCost;
				$ReturnItemLine->StandardCost = $StandardCost;
			} /* end of its an assembly */

			// Insert stock movements - with unit cost
			$LocalCurrencyPrice = ($ReturnItemLine->Price / $ExRate);

			if (empty($ReturnItemLine->StandardCost)) {
				$ReturnItemLine->StandardCost=0;
			}
			if ($MBFlag=='B' OR $MBFlag=='M') {
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
												narrative )
						VALUES ('" . $ReturnItemLine->StockID . "',
								11,
								'" . $CreditNoteNo . "',
								'" . $_SESSION['Items' . $identifier]->Location . "',
								'" . $ReturnDate . "',
								'" . $_SESSION['UserID'] . "',
								'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
								'" . $_SESSION['Items' . $identifier]->Branch . "',
								'" . $LocalCurrencyPrice . "',
								'" . $PeriodNo . "',
								'" . $OrderNo . "',
								'" . $ReturnItemLine->Quantity . "',
								'" . $ReturnItemLine->DiscountPercent . "',
								'" . $ReturnItemLine->StandardCost . "',
								'" . ($QtyOnHandPrior + $ReturnItemLine->Quantity) . "',
								'" . $ReturnItemLine->Narrative . "' )";
			} else {
			// its an assembly or dummy and assemblies/dummies always have nil stock (by definition they are made up at the time of dispatch  so new qty on hand will be nil
				if (empty($ReturnItemLine->StandardCost)) {
					$ReturnItemLine->StandardCost = 0;
				}
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
												narrative )
						VALUES ('" . $ReturnItemLine->StockID . "',
								'11',
								'" . $CreditNoteNo . "',
								'" . $_SESSION['Items' . $identifier]->Location . "',
								'" . $ReturnDate . "',
								'" . $_SESSION['UserID'] . "',
								'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
								'" . $_SESSION['Items' . $identifier]->Branch . "',
								'" . $LocalCurrencyPrice . "',
								'" . $PeriodNo . "',
								'" . $ReturnItemLine->Quantity . "',
								'" . $ReturnItemLine->DiscountPercent . "',
								'" . $ReturnItemLine->StandardCost . "',
								'" . $ReturnItemLine->Narrative . "')";
			}

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Stock movement records could not be inserted because');
			$DbgMsg = _('The following SQL to insert the stock movement records was used');
			$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		/*Get the ID of the StockMove... */
			$StkMoveNo = DB_Last_Insert_ID('stockmoves','stkmoveno');

		/*Insert the taxes that applied to this line */
			foreach ($ReturnItemLine->Taxes as $Tax) {

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

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Taxes and rates applicable to this invoice line item could not be inserted because');
				$DbgMsg = _('The following SQL to insert the stock movement tax detail records was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
			} //end for each tax for the line


		/*Insert Sales Analysis records */
			$SalesValue = 0;
			if ($ExRate>0) {
				$SalesValue = $ReturnItemLine->Price * $ReturnItemLine->Quantity / $ExRate;
			}

			$SQL="SELECT COUNT(*),
						salesanalysis.stockid,
						salesanalysis.stkcategory,
						salesanalysis.cust,
						salesanalysis.custbranch,
						salesanalysis.area,
						salesanalysis.periodno,
						salesanalysis.typeabbrev,
						salesanalysis.salesperson
					FROM salesanalysis,
						custbranch,
						stockmaster
					WHERE salesanalysis.stkcategory=stockmaster.categoryid
					AND salesanalysis.stockid=stockmaster.stockid
					AND salesanalysis.cust=custbranch.debtorno
					AND salesanalysis.custbranch=custbranch.branchcode
					AND salesanalysis.area=custbranch.area
					AND salesanalysis.salesperson='" . $_SESSION['Items' . $identifier]->SalesPerson . "'
					AND salesanalysis.typeabbrev ='" . $_SESSION['Items' . $identifier]->DefaultSalesType . "'
					AND salesanalysis.periodno='" . $PeriodNo . "'
					AND salesanalysis.cust " . LIKE . " '" . $_SESSION['Items' . $identifier]->DebtorNo . "'
					AND salesanalysis.custbranch " . LIKE . " '" . $_SESSION['Items' . $identifier]->Branch . "'
					AND salesanalysis.stockid " . LIKE . " '" . $ReturnItemLine->StockID . "'
					AND salesanalysis.budgetoractual=1
					GROUP BY salesanalysis.stockid,
						salesanalysis.stkcategory,
						salesanalysis.cust,
						salesanalysis.custbranch,
						salesanalysis.area,
						salesanalysis.periodno,
						salesanalysis.typeabbrev,
						salesanalysis.salesperson";

			$ErrMsg = _('The count of existing Sales analysis records could not run because');
			$DbgMsg = _('SQL to count the no of sales analysis records');
			$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			$MyRow = DB_fetch_row($Result);

			if ($MyRow[0]>0) {  /*Update the existing record that already exists */

				$SQL = "UPDATE salesanalysis
							SET amt=amt-" . ($SalesValue) . ",
								cost=cost-" . ($ReturnItemLine->StandardCost * $ReturnItemLine->Quantity) . ",
								qty=qty -" . $ReturnItemLine->Quantity . ",
								disc=disc-" . ($ReturnItemLine->DiscountPercent * $SalesValue) . "
							WHERE salesanalysis.area='" . $MyRow[5] . "'
								AND salesanalysis.salesperson='" . $_SESSION['Items' . $identifier]->SalesPerson . "'
								AND typeabbrev ='" . $_SESSION['Items' . $identifier]->DefaultSalesType . "'
								AND periodno = '" . $PeriodNo . "'
								AND cust " . LIKE . " '" . $_SESSION['Items' . $identifier]->DebtorNo . "'
								AND custbranch " . LIKE . " '" . $_SESSION['Items' . $identifier]->Branch . "'
								AND stockid " . LIKE . " '" . $ReturnItemLine->StockID . "'
								AND salesanalysis.stkcategory ='" . $MyRow[2] . "'
								AND budgetoractual=1";

			} else { /* insert a new sales analysis record */

				$SQL = "INSERT INTO salesanalysis (	typeabbrev,
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
													stkcategory	)
					SELECT '" . $_SESSION['Items' . $identifier]->DefaultSalesType . "',
						'" . $PeriodNo . "',
						'" . -($SalesValue) . "',
						'" . -($ReturnItemLine->StandardCost * $ReturnItemLine->Quantity) . "',
						'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
						'" . $_SESSION['Items' . $identifier]->Branch . "',
						'" . -$ReturnItemLine->Quantity . "',
						'" . -($ReturnItemLine->DiscountPercent * $SalesValue) . "',
						'" . $ReturnItemLine->StockID . "',
						custbranch.area,
						1,
						'" . $_SESSION['Items' . $identifier]->SalesPerson . "',
						stockmaster.categoryid
					FROM stockmaster,
						custbranch
					WHERE stockmaster.stockid = '" . $ReturnItemLine->StockID . "'
					AND custbranch.debtorno = '" . $_SESSION['Items' . $identifier]->DebtorNo . "'
					AND custbranch.branchcode='" . $_SESSION['Items' . $identifier]->Branch . "'";
			}

			$ErrMsg = _('Sales analysis record could not be added or updated because');
			$DbgMsg = _('The following SQL to insert the sales analysis record was used');
			$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		/* If GLLink_Stock then insert GLTrans to credit stock and debit cost of sales at standard cost*/

			if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $ReturnItemLine->StandardCost !=0) {

		/*first the cost of sales entry*/

				$SQL = "INSERT INTO gltrans (	type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount)
										VALUES ( 11,
												'" . $CreditNoteNo . "',
												'" . $ReturnDate . "',
												'" . $PeriodNo . "',
												'" . GetCOGSGLAccount($Area, $ReturnItemLine->StockID, $_SESSION['Items' . $identifier]->DefaultSalesType) . "',
												'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $ReturnItemLine->StockID . " x " . -$ReturnItemLine->Quantity . " @ " . $ReturnItemLine->StandardCost . "',
												'" . $ReturnItemLine->StandardCost * -$ReturnItemLine->Quantity . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The cost of sales GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		/*now the stock entry*/
				$StockGLCode = GetStockGLCode($ReturnItemLine->StockID);

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount )
										VALUES ( 11,
											'" . $CreditNoteNo . "',
											'" . $ReturnDate . "',
											'" . $PeriodNo . "',
											'" . $StockGLCode['stockact'] . "',
											'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $ReturnItemLine->StockID . " x " . -$ReturnItemLine->Quantity . " @ " . $ReturnItemLine->StandardCost . "',
											'" . ($ReturnItemLine->StandardCost * $ReturnItemLine->Quantity) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock side of the cost of sales GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
			} /* end of if GL and stock integrated and standard cost !=0 */

			if ($_SESSION['CompanyRecord']['gllink_debtors']==1 AND $ReturnItemLine->Price !=0) {

		//Post sales transaction to GL credit sales
				$SalesGLAccounts = GetSalesGLAccount($Area, $ReturnItemLine->StockID, $_SESSION['Items' . $identifier]->DefaultSalesType);

				$SQL = "INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount )
										VALUES ( 11,
											'" . $CreditNoteNo . "',
											'" . $ReturnDate . "',
											'" . $PeriodNo . "',
											'" . $SalesGLAccounts['salesglcode'] . "',
											'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $ReturnItemLine->StockID . " x " . -$ReturnItemLine->Quantity . " @ " . $ReturnItemLine->Price . "',
											'" . ($ReturnItemLine->Price * $ReturnItemLine->Quantity/$ExRate) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales GL posting could not be inserted because');
				$DbgMsg = '<br />' ._('The following SQL to insert the GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				if ($ReturnItemLine->DiscountPercent !=0) {

					$SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount )
												VALUES ( 11,
													'" . $CreditNoteNo . "',
													'" . $ReturnDate . "',
													'" . $PeriodNo . "',
													'" . $SalesGLAccounts['discountglcode'] . "',
													'" . $_SESSION['Items' . $identifier]->DebtorNo . " - " . $ReturnItemLine->StockID . " @ " . ($ReturnItemLine->DiscountPercent * 100) . "%',
													'" . -($ReturnItemLine->Price * $ReturnItemLine->Quantity * $ReturnItemLine->DiscountPercent/$ExRate) . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The sales discount GL posting could not be inserted because');
					$DbgMsg = _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
				} /*end of if discount !=0 */
			} /*end of if sales integrated with debtors */
		} /*end of OrderLine loop */

		if ($_SESSION['CompanyRecord']['gllink_debtors']==1) {

	/*Post debtors transaction to GL debit debtors, credit freight re-charged and credit sales */
			if (($_SESSION['Items' . $identifier]->total + filter_number_format($_POST['TaxTotal'])) !=0) {
				$SQL = "INSERT INTO gltrans (	type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount	)
											VALUES ( 11,
												'" . $CreditNoteNo . "',
												'" . $ReturnDate . "',
												'" . $PeriodNo . "',
												'" . $_SESSION['CompanyRecord']['debtorsact'] . "',
												'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
												'" . -(($_SESSION['Items' . $identifier]->total + filter_number_format($_POST['TaxTotal']))/$ExRate) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The total debtor GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the total debtors control GLTrans record was used');
				$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
			}


			foreach ( $_SESSION['Items' . $identifier]->TaxTotals as $TaxAuthID => $TaxAmount) {
				if ($TaxAmount !=0 ) {
					$SQL = "INSERT INTO gltrans (	type,
													typeno,
													trandate,
													periodno,
													account,
													narrative,
													amount	)
												VALUES ( 11,
													'" . $CreditNoteNo . "',
													'" . $ReturnDate . "',
													'" . $PeriodNo . "',
													'" . $_SESSION['Items' . $identifier]->TaxGLCodes[$TaxAuthID] . "',
													'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
													'" . ($TaxAmount/$ExRate) . "')";

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The tax GL posting could not be inserted because');
					$DbgMsg = _('The following SQL to insert the GLTrans record was used');
					$Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
				}
			}

			EnsureGLEntriesBalance(11,$CreditNoteNo);

			/*Also if GL is linked to debtors need to process the debit to bank and credit to debtors for the payment */
			/*Need to figure out the cross rate between customer currency and bank account currency */

			if ($_POST['AmountPaid']!=0) {
				$PaymentNumber = GetNextTransNo(12);
				$SQL="INSERT INTO gltrans (type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
						VALUES (12,
							'" . $PaymentNumber . "',
							'" . $ReturnDate . "',
							'" . $PeriodNo . "',
							'" . $_POST['BankAccount'] . "',
							'" . $_SESSION['Items' . $identifier]->LocationName . ' ' . _('Counter Return') . ' ' . $CreditNoteNo . "',
							'" . -(filter_number_format($_POST['AmountPaid'])/$ExRate) . "')";
				$DbgMsg = _('The SQL that failed to insert the GL transaction for the bank account debit was');
				$ErrMsg = _('Cannot insert a GL transaction for the bank account debit');
				$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

				/* Now Debit Debtors account with negative receipt/payment to customer */
				$SQL="INSERT INTO gltrans ( type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount)
						VALUES (12,
							'" . $PaymentNumber . "',
							'" . $ReturnDate . "',
							'" . $PeriodNo . "',
							'" . $_SESSION['CompanyRecord']['debtorsact'] . "',
							'" . $_SESSION['Items' . $identifier]->LocationName . ' ' . _('Counter Return') . ' ' . $CreditNoteNo . "',
							'" . (filter_number_format($_POST['AmountPaid'])/$ExRate) . "')";
				$DbgMsg = _('The SQL that failed to insert the GL transaction for the debtors account credit was');
				$ErrMsg = _('Cannot insert a GL transaction for the debtors account credit');
				$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
			}//amount paid was not zero

			EnsureGLEntriesBalance(12,$PaymentNumber);

		} /*end of if Sales and GL integrated */

		if ($_POST['AmountPaid']!=0) {
			if (!isset($PaymentNumber)) {
				$PaymentNumber = GetNextTransNo(12);
			}
			//Now need to add the receipt banktrans record
			//First get the account currency that it has been banked into
			$result = DB_query("SELECT rate FROM currencies
								INNER JOIN bankaccounts
								ON currencies.currabrev=bankaccounts.currcode
								WHERE bankaccounts.accountcode='" . $_POST['BankAccount'] . "'");
			$MyRow = DB_fetch_row($result);
			$BankAccountExRate = $MyRow[0];

			/*
			 * Some interesting exchange rate conversion going on here
			 * Say :
			 * The business's functional currency is NZD
			 * Customer location counter sales are in AUD - 1 NZD = 0.80 AUD
			 * Banking money into a USD account - 1 NZD = 0.68 USD
			 *
			 * Customer sale is for $100 AUD
			 * GL entries  conver the AUD 100 to NZD  - 100 AUD / 0.80 = $125 NZD
			 * Banktrans entries convert the AUD 100 to USD using 100/0.8 * 0.68
			*/

			//insert the banktrans record in the currency of the bank account

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
					VALUES (12,
						'" . $PaymentNumber . "',
						'" . $_POST['BankAccount'] . "',
						'" . $_SESSION['Items' . $identifier]->LocationName . ' ' . _('Counter Sale') . ' ' . $CreditNoteNo . "',
						'" . $ExRate . "',
						'" . $BankAccountExRate . "',
						'" . $ReturnDate . "',
						'" . $_POST['PaymentMethod'] . "',
						'" . -filter_number_format($_POST['AmountPaid']) * $BankAccountExRate . "',
						'" . $_SESSION['Items' . $identifier]->DefaultCurrency . "')";

			$DbgMsg = _('The SQL that failed to insert the bank account transaction was');
			$ErrMsg = _('Cannot insert a bank transaction');
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			//insert a new debtortrans for the receipt

			$SQL = "INSERT INTO debtortrans (transno,
											type,
											debtorno,
											trandate,
											inputdate,
											prd,
											reference,
											rate,
											ovamount,
											alloc,
											invtext)
					VALUES ('" . $PaymentNumber . "',
						12,
						'" . $_SESSION['Items' . $identifier]->DebtorNo . "',
						'" . $ReturnDate . "',
						'" . date('Y-m-d H-i-s') . "',
						'" . $PeriodNo . "',
						'" . $CreditNoteNo . "',
						'" . $ExRate . "',
						'" . filter_number_format($_POST['AmountPaid']) . "',
						'" . filter_number_format($_POST['AmountPaid']) . "',
						'" . $_SESSION['Items' . $identifier]->LocationName . ' ' . _('Counter Sale') ."')";

			$DbgMsg = _('The SQL that failed to insert the customer receipt transaction was');
			$ErrMsg = _('Cannot insert a receipt transaction against the customer because') ;
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

			$ReceiptDebtorTransID = DB_Last_Insert_ID('debtortrans','id');


			//and finally add the allocation record between receipt and invoice

			$SQL = "INSERT INTO custallocns (	amt,
												datealloc,
												transid_allocfrom,
												transid_allocto )
									VALUES  ('" . filter_number_format($_POST['AmountPaid']) . "',
											'" . $ReturnDate . "',
											 '" . $DebtorTransID . "',
											 '" . $ReceiptDebtorTransID . "')";
			$DbgMsg = _('The SQL that failed to insert the allocation of the receipt to the credit note was');
			$ErrMsg = _('Cannot insert the customer allocation of the receipt to the invoice because');
			$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		} //end if $_POST['AmountPaid']!= 0

		DB_Txn_Commit();
	// *************************************************************************
	//   E N D   O F   C R E D I T  N O T E   S Q L   P R O C E S S I N G
	// *************************************************************************

		unset($_SESSION['Items' . $identifier]->LineItems);
		unset($_SESSION['Items' . $identifier]);

		echo prnMsg( _('Credit Note number'). ' '. $CreditNoteNo .' '. _('processed'), 'success');

		echo '<br /><div class="centre">';

		if ($_SESSION['InvoicePortraitFormat']==0) {
			echo '<img src="'.$RootPath.'/css/'.$Theme.'/images/printer.png" title="' . _('Print') . '" alt="" />' . ' ' . '<a target="_blank" href="'.$RootPath.'/PrintCustTrans.php?FromTransNo='.$CreditNoteNo.'&InvOrCredit=Credit&PrintPDF=True">' .  _('Print this credit note'). ' (' . _('Landscape') . ')</a><br /><br />';
		} else {
			echo '<img src="'.$RootPath.'/css/'.$Theme.'/images/printer.png" title="' . _('Print') . '" alt="" />' . ' ' . '<a target="_blank" href="'.$RootPath.'/PrintCustTransPortrait.php?FromTransNo='.$CreditNoteNo.'&InvOrCredit=Credit&PrintPDF=True" onClick="return window.location=\'index.php\'">' .  _('Print this credit note'). ' (' . _('Portrait') . ')</a><br /><br />';
		}
		echo '<br /><br /><a href="' .htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">' . _('Start a new Counter Return') . '</a></div>';

	}
	// There were input errors so don't process nuffin
} else {
	//pretend the user never tried to commit the sale
	unset($_POST['ProcessReturn']);
}
/*******************************
 * end of Credit Note Processing
 * *****************************
*/

/* Now show the stock item selection search stuff below */
if (!isset($_POST['ProcessReturn'])) {
	 if (isset($_POST['PartSearch']) and $_POST['PartSearch']!='') {

		echo '<input type="hidden" name="PartSearch" value="' .  _('Yes Please') . '" />';
		echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/magnifier.png" title="' . _('Search') . '" alt="" />' . ' ';
		echo _('Search for Items') . '</p>';
		echo '<div class="page_help_text">' . _('Search for Items') . _(', Searches the database for items, you can narrow the results by selecting a stock category, or just enter a partial item description or partial item code') . '.</div><br />';
		echo '<table class="selection">
				<tr>
					<td><b>' . _('Select a Stock Category') . ': </b><select tabindex="1" name="StockCat">';

		if (!isset($_POST['StockCat'])) {
			echo '<option selected="selected" value="All">' . _('All') . '</option>';
			$_POST['StockCat'] ='All';
		} else {
			echo '<option value="All">' . _('All') . '</option>';
		}
		$SQL="SELECT categoryid,
					categorydescription
				FROM stockcategory
				WHERE stocktype='F' OR stocktype='D'
				ORDER BY categorydescription";
		$result1 = DB_query($SQL);
		while ($MyRow1 = DB_fetch_array($result1)) {
			if ($_POST['StockCat']==$MyRow1['categoryid']) {
				echo '<option selected="selected" value="' . $MyRow1['categoryid'] . '">' . $MyRow1['categorydescription'] . '</option>';
			} else {
				echo '<option value="'. $MyRow1['categoryid'] . '">' . $MyRow1['categorydescription'] . '</option>';
			}
		}

		?>

		</select></td>
		<td><b><?php echo _('Enter partial Description'); ?>:</b>
		<input tabindex="2" type="text" name="Keywords" <?php isset($SearchResult)? '': 'autofocus="autofocus"' ?> size="20" maxlength="25" value="<?php if (isset($_POST['Keywords'])) echo $_POST['Keywords']; ?>" /></td>

		<td class="number"><b><?php echo _('OR'); ?> </b><b><?php echo _('Enter extract of the Stock Code'); ?>:</b>
		<input tabindex="3" type="text" name="StockCode" size="15" maxlength="18" value="<?php if (isset($_POST['StockCode'])) echo $_POST['StockCode']; ?>" /></td>

		</tr><tr>
		<td class="centre" colspan="1"><input tabindex="4" type="submit" name="Search" value="<?php echo _('Search Now'); ?>" /></td>
		<td class="centre" colspan="1"><input tabindex="5" type="submit" name="QuickEntry" value="<?php echo _('Use Quick Entry'); ?>" /></td>

		<?php

		echo '</tr></table><br />';
	// Add some useful help as the order progresses
		if (isset($SearchResult)) {
			echo '<br />';
			echo '<div class="page_help_text">' . _('Select an item by entering the quantity required.  Click Return when ready.') . '</div>';
			echo '<br />';
		}


		if (isset($SearchResult)) {
			$j = 1;
			echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?identifier='.$identifier . '" method="post" name="ReturnForm">';
			echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
			echo '<table class="table1">';
			echo '<tr>
					<td><input type="hidden" name="previous" value="'.strval($Offset-1).'" /><input tabindex="'.strval($j+7).'" type="submit" name="Prev" value="'._('Prev').'" /></td>
					<td class="centre" colspan="6"><input type="hidden" name="SelectingReturnItems" value="1" /><input tabindex="'.strval($j+8).'" type="submit" value="'._('Return Item(s)').'" /></td>
					<td><input type="hidden" name="NextList" value="'.strval($Offset+1).'" /><input tabindex="'.strval($j+9).'" type="submit" name="Next" value="'._('Next').'" /></td>
				</tr>';
			$TableHeader = '<tr>
								<th>' . _('Code') . '</th>
					   			<th>' . _('Description') . '</th>
					   			<th>' . _('Units') . '</th>
					   			<th>' . _('On Hand') . '</th>
					   			<th>' . _('On Demand') . '</th>
					   			<th>' . _('On Order') . '</th>
					   			<th>' . _('Available') . '</th>
					   			<th>' . _('Quantity') . '</th></tr>';
			echo $TableHeader;
			$i=0;

			while ($MyRow=DB_fetch_array($SearchResult)) {

				// Find the quantity in stock at location
				$QOHSql = "SELECT sum(quantity) AS qoh
 					   FROM locstock
					   WHERE locstock.stockid='" .$MyRow['stockid'] . "'
					   AND loccode = '" . $_SESSION['Items' . $identifier]->Location . "'";
				$QOHResult =  DB_query($QOHSql);
				$QOHRow = DB_fetch_array($QOHResult);
				$QOH = $QOHRow['qoh'];

				// Find the quantity on outstanding sales orders
				$SQL = "SELECT SUM(salesorderdetails.quantity-salesorderdetails.qtyinvoiced) AS dem
						 FROM salesorderdetails INNER JOIN salesorders
						 ON salesorders.orderno = salesorderdetails.orderno
						 WHERE salesorders.fromstkloc='" . $_SESSION['Items' . $identifier]->Location . "'
						 AND salesorderdetails.completed=0
						 AND salesorders.quotation=0
						 AND salesorderdetails.stkcode='" . $MyRow['stockid'] . "'";

				$ErrMsg = _('The demand for this product from') . ' ' . $_SESSION['Items' . $identifier]->Location . ' ' . _('cannot be retrieved because');
				$DemandResult = DB_query($SQL,$ErrMsg);

				$DemandRow = DB_fetch_row($DemandResult);
				if ($DemandRow[0] != null) {
				  $DemandQty =  $DemandRow[0];
				} else {
				  $DemandQty = 0;
				}

				// Get the QOO due to Purchase orders for all locations. Function defined in SQL_CommonFunctions.inc
				$QOO= GetQuantityOnOrderDueToPurchaseOrders($MyRow['stockid'], '');
				// Get the QOO dues to Work Orders for all locations. Function defined in SQL_CommonFunctions.inc
				$QOO += GetQuantityOnOrderDueToWorkOrders($MyRow['stockid'], '');

				$Available = $QOH - $DemandQty + $QOO;

				printf('<tr class="striped_row">
						<td>%s</td>
						<td title="%s">%s</td>
						<td>%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td class="number">%s</td>
						<td><input class="number"  tabindex="'.strval($j+7).'" type="text"  required="required" ' . ($i==0?'autofocus="autofocus"':'') . ' size="6" name="ReturnQty%s" value="0" /><input type="hidden" name="StockID%s" value="%s" /></td>
						</tr>',
						$MyRow['stockid'],
						$MyRow['longdescription'],
						$MyRow['description'],
						$MyRow['units'],
						locale_number_format($QOH, $MyRow['decimalplaces']),
						locale_number_format($DemandQty, $MyRow['decimalplaces']),
						locale_number_format($QOO, $MyRow['decimalplaces']),
						locale_number_format($Available, $MyRow['decimalplaces']),
						$i,
						$i,
						$MyRow['stockid']);
				$j++;
				$i++;
	#end of page full new headings if
			}
	#end of while loop
			echo '<input type="hidden" name="CustRef" value="'.$_SESSION['Items' . $identifier]->CustRef.'" />
				<input type="hidden" name="Comments" value="'.$_SESSION['Items' . $identifier]->Comments.'" />
				<input type="hidden" name="DeliverTo" value="'.$_SESSION['Items' . $identifier]->DeliverTo.'" />
				<input type="hidden" name="PhoneNo" value="'.$_SESSION['Items' . $identifier]->PhoneNo.'" />
				<input type="hidden" name="Email" value="'.$_SESSION['Items' . $identifier]->Email.'" />
				<tr>
					<td><input type="hidden" name="previous" value="'.strval($Offset-1).'" />
						<input tabindex="'.strval($j+7).'" type="submit" name="Prev" value="'._('Prev').'" /></td>
					<td class="centre" colspan="6"><input type="hidden" name="SelectingReturnItems" value="1" />
						<input tabindex="'.strval($j+8).'" type="submit" value="'._('Add to Sale').'" /></td>
					<td><input type="hidden" name="NextList" value="'.strval($Offset+1).'" />
						<input tabindex="'.strval($j+9).'" type="submit" name="Next" value="'._('Next').'" /></td>
				</tr>
				</table>
				</form>';
		}#end if SearchResults to show
	} /*end of PartSearch options to be displayed */
		else { /* show the quick entry form variable */

		echo '<div class="page_help_text"><b>' . _('Use this form to add return items quickly if the item codes are already known') . '</b></div><br />
		 		<table border="1">
				<tr>';
			/*do not display colum unless customer requires po line number by sales order line*/
		echo '<th>' . _('Item Code') . '</th>
				<th>' . _('Quantity') . '</th>
				</tr>';
		$ReturnDate = Date($_SESSION['DefaultDateFormat']);
		if (count($_SESSION['Items' . $identifier]->LineItems)==0) {
			echo '<input type="hidden" name="CustRef" value="'.$_SESSION['Items' . $identifier]->CustRef.'" />
			<input type="hidden" name="Comments" value="'.$_SESSION['Items' . $identifier]->Comments.'" />
			<input type="hidden" name="DeliverTo" value="'.$_SESSION['Items' . $identifier]->DeliverTo.'" />
			<input type="hidden" name="PhoneNo" value="'.$_SESSION['Items' . $identifier]->PhoneNo.'" />
			<input type="hidden" name="Email" value="'.$_SESSION['Items' . $identifier]->Email.'" />';
		}
		for ($i=1;$i<=$_SESSION['QuickEntries'];$i++) {

	 		echo '<tr class="striped_row">';
	 		/* Do not display colum unless customer requires po line number by sales order line*/
	 		echo '<td><input type="text" name="part_' . $i . '" ' . ($i==1 ? 'autofocus="autofocus" ': '') . 'size="21" data-type="no-illegal-chars" title="' . _('Enter a part code to be returned. Part codes can contain any alpha-numeric characters underscore or hyphen.') . '" maxlength="20" /></td>
					<td><input type="text" class="number" name="qty_' . $i . '" size="6" maxlength="6" />
						<input type="hidden" class="date" name="ItemDue_' . $i . '" value="' . $ReturnDate . '" /></td>
				</tr>';
   		}
	 	echo '</table>
				<br />
				<div class="centre">
					<input type="submit" name="QuickEntry" value="' . _('Quick Entry') . '" />
					<input type="submit" name="PartSearch" value="' . _('Search Parts') . '" />
				</div>';

  	}
	if ($_SESSION['Items' . $identifier]->ItemsOrdered >=1) {
  		echo '<br />
			<div class="centre">
				<input type="submit" name="CancelReturn" value="' . _('Cancel Return') . '" onclick="return confirm(\'' . _('Are you sure you wish to cancel this return?') . '\');" />
			</div>';
	}
}
echo '</form>';
include('includes/footer.php');
?>

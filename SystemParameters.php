<?php
/* This script is for maintenance of the system parameters. */

include('includes/session.php');
$Title = _('System Parameters');
$ViewTopic = 'CreatingNewSystem';
$BookMark = 'SystemParameters';
include('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/maintenance.png" title="', // Icon image.
	$Title, '" /> ', // Icon title.
	$Title, '</p>';// Page title.

include('includes/CountriesArray.php');

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	/*
		Note: the X_ in the POST variables, the reason for this is to overcome globals=on replacing
		the actial system/overidden variables.
	*/
	if (mb_strlen($_POST['X_PastDueDays1']) > 3 OR !is_numeric($_POST['X_PastDueDays1']) ) {
		$InputError = 1;
		prnMsg(_('First overdue deadline days must be a number'),'error');
	} elseif (mb_strlen($_POST['X_PastDueDays2'])  > 3 OR !is_numeric($_POST['X_PastDueDays2']) ) {
		$InputError = 1;
		prnMsg(_('Second overdue deadline days must be a number'),'error');
	} elseif (mb_strlen($_POST['X_DefaultCreditLimit']) > 12 OR !is_numeric($_POST['X_DefaultCreditLimit']) ) {
		$InputError = 1;
		prnMsg(_('Default Credit Limit must be a number'),'error');
	} elseif (mb_strstr($_POST['X_RomalpaClause'], "'") OR mb_strlen($_POST['X_RomalpaClause']) > 5000) {
		$InputError = 1;
		prnMsg(_('The Romalpa Clause may not contain single quotes and may not be longer than 5000 chars'),'error');
	} elseif (mb_strlen($_POST['X_QuickEntries']) > 2 OR !is_numeric($_POST['X_QuickEntries']) OR
		$_POST['X_QuickEntries'] < 1 OR $_POST['X_QuickEntries'] > 99 ) {
		$InputError = 1;
		prnMsg(_('No less than 1 and more than 99 Quick entries allowed'),'error');
	} elseif (!is_numeric($_POST['X_MaxSerialItemsIssued']) or $_POST['X_MaxSerialItemsIssued'] < 1) {
		$InputError = 1;
		prnMsg(_('The maximum number of serial numbers issued must be numeric and greater than zero'), 'error');
	} elseif (mb_strlen($_POST['X_FreightChargeAppliesIfLessThan']) > 12 OR !is_numeric($_POST['X_FreightChargeAppliesIfLessThan']) ) {
		$InputError = 1;
		prnMsg(_('Freight Charge Applies If Less Than must be a number'),'error');
	} elseif ( !is_numeric($_POST['X_StandardCostDecimalPlaces']) OR
		$_POST['X_StandardCostDecimalPlaces'] < 0 OR $_POST['X_StandardCostDecimalPlaces'] > 4 ) {
		$InputError = 1;
		prnMsg(_('Standard Cost Decimal Places must be a number between 0 and 4'),'error');
	} elseif (mb_strlen($_POST['X_NumberOfPeriodsOfStockUsage']) > 2 OR !is_numeric($_POST['X_NumberOfPeriodsOfStockUsage']) OR
		$_POST['X_NumberOfPeriodsOfStockUsage'] < 1 OR $_POST['X_NumberOfPeriodsOfStockUsage'] > 12 ) {
		$InputError = 1;
		prnMsg(_('Financial period per year must be a number between 1 and 12'),'error');
	} elseif (mb_strlen($_POST['X_TaxAuthorityReferenceName']) >25) {
		$InputError = 1;
		prnMsg(_('The Tax Authority Reference Name must be 25 characters or less long'),'error');
	} elseif (mb_strlen($_POST['X_OverChargeProportion']) > 3 OR !is_numeric($_POST['X_OverChargeProportion']) OR
		$_POST['X_OverChargeProportion'] < 0 OR $_POST['X_OverChargeProportion'] > 100 ) {
		$InputError = 1;
		prnMsg(_('Over Charge Proportion must be a percentage'),'error');
	} elseif (mb_strlen($_POST['X_OverReceiveProportion']) > 3 OR !is_numeric($_POST['X_OverReceiveProportion']) OR
		$_POST['X_OverReceiveProportion'] < 0 OR $_POST['X_OverReceiveProportion'] > 100 ) {
		$InputError = 1;
		prnMsg(_('Over Receive Proportion must be a percentage'),'error');
	} elseif (mb_strlen($_POST['X_PageLength']) > 3 OR !is_numeric($_POST['X_PageLength']) OR
		$_POST['X_PageLength'] < 1 ) {
		$InputError = 1;
		prnMsg(_('Lines per page must be greater than 1'),'error');
	} elseif (mb_strlen($_POST['X_MonthsAuditTrail']) > 2 OR !is_numeric($_POST['X_MonthsAuditTrail']) OR
		$_POST['X_MonthsAuditTrail'] < 0 ) {
		$InputError = 1;
		prnMsg(_('The number of months of audit trail to keep must be zero or a positive number less than 100 months'),'error');
	}elseif (mb_strlen($_POST['X_DefaultTaxCategory']) > 1 OR !is_numeric($_POST['X_DefaultTaxCategory']) OR
		$_POST['X_DefaultTaxCategory'] < 1 ) {
		$InputError = 1;
		prnMsg(_('DefaultTaxCategory must be between 1 and 9'),'error');
	} elseif (mb_strlen($_POST['X_DefaultDisplayRecordsMax']) > 3 OR !is_numeric($_POST['X_DefaultDisplayRecordsMax']) OR
		$_POST['X_DefaultDisplayRecordsMax'] < 1 ) {
		$InputError = 1;
		prnMsg(_('Default maximum number of records to display must be between 1 and 500'),'error');
	}elseif (mb_strlen($_POST['X_MaxImageSize']) > 3 OR !is_numeric($_POST['X_MaxImageSize']) OR
		$_POST['X_MaxImageSize'] < 1 ) {
		$InputError = 1;
		prnMsg(_('The maximum size of item image files must be between 50 and 500 (NB this figure refers to KB)'),'error');
	}elseif (!IsEmailAddress($_POST['X_FactoryManagerEmail'])){
		$InputError = 1;
		prnMsg(_('The Factory Manager Email address does not appear to be valid'),'error');
	}elseif (!IsEmailAddress($_POST['X_PurchasingManagerEmail'])){
		$InputError = 1;
		prnMsg(_('The Purchasing Manager Email address does not appear to be valid'),'error');
	}elseif (!IsEmailAddress($_POST['X_InventoryManagerEmail']) AND $_POST['X_InventoryManagerEmail']!=''){
		$InputError = 1;
		prnMsg(_('The Inventory Manager Email address does not appear to be valid'),'error');
	}elseif (mb_strlen($_POST['X_FrequentlyOrderedItems']) > 2 OR !is_numeric($_POST['X_FrequentlyOrderedItems'])) {
		$InputError = 1;
		prnMsg(_('The number of frequently ordered items to display must be numeric'),'error');
	}elseif (strlen($_POST['X_SmtpSetting']) != 1 OR !is_numeric($_POST['X_SmtpSetting'])){
		$InputError = 1;
		prnMsg(_('The SMTP setting should be selected as Yes or No'),'error');
	}elseif (strlen($_POST['X_QualityLogSamples']) != 1 OR !is_numeric($_POST['X_QualityLogSamples'])){
		$InputError = 1;
		prnMsg(_('The Quality Log Samples setting should be selected as Yes or No'),'error');
	} elseif (mb_strstr($_POST['X_QualityProdSpecText'], "'") OR mb_strlen($_POST['X_QualityProdSpecText']) > 5000) {
		$InputError = 1;
		prnMsg(_('The Quality ProdSpec Text may not contain single quotes and may not be longer than 5000 chars'),'error');
	} elseif (mb_strstr($_POST['X_QualityCOAText'], "'") OR mb_strlen($_POST['X_QualityCOAText']) > 5000) {
		$InputError = 1;
		prnMsg(_('The Quality COA Text may not contain single quotes and may not be longer than 5000 chars'),'error');
	}

	if ($InputError !=1){

		$sql = array();

		if ($_SESSION['DefaultDateFormat'] != $_POST['X_DefaultDateFormat'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_DefaultDateFormat']."' WHERE confname = 'DefaultDateFormat'";
		}
		if($DefaultTheme != $_POST['X_DefaultTheme']) {// If not equal, update the default theme.
			// BEGIN: Update the config.php file:
			$fhandle = fopen('config.php', 'r');
			if($fhandle) {
				$content = fread($fhandle, filesize('config.php'));
				$content = str_replace(' ;\n', ';\n', $content);// Clean space before the end-of-php-line.
				$content = str_replace('\''.$DefaultTheme .'\';', '\''.$_POST['X_DefaultTheme'].'\';', $content);
				$fhandle = fopen('config.php','w');
				if(!fwrite($fhandle,$content)) {
					prnMsg(_('Cannot write to the configuration file.'), 'error');
				} else {
					prnMsg(_('The configuration file was updated.'), 'info');
				}
				fclose($fhandle);
			} else {
				prnMsg(_('Cannot open the configuration file.'), 'error');
			}
			// END: Update the config.php file.
		}
		if ($_SESSION['PastDueDays1'] != $_POST['X_PastDueDays1'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_PastDueDays1']."' WHERE confname = 'PastDueDays1'";
		}
		if ($_SESSION['PastDueDays2'] != $_POST['X_PastDueDays2'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_PastDueDays2']."' WHERE confname = 'PastDueDays2'";
		}
		if ($_SESSION['DefaultCreditLimit'] != $_POST['X_DefaultCreditLimit'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_DefaultCreditLimit']."' WHERE confname = 'DefaultCreditLimit'";
		}
		if ($_SESSION['Show_Settled_LastMonth'] != $_POST['X_Show_Settled_LastMonth'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_Show_Settled_LastMonth']."' WHERE confname = 'Show_Settled_LastMonth'";
		}
		if ($_SESSION['RomalpaClause'] != $_POST['X_RomalpaClause'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_RomalpaClause'] . "' WHERE confname = 'RomalpaClause'";
		}
		if ($_SESSION['QuickEntries'] != $_POST['X_QuickEntries'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_QuickEntries']."' WHERE confname = 'QuickEntries'";
		}
		if ($_SESSION['MaxSerialItemsIssued'] != $_POST['X_MaxSerialItemsIssued']) {
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_MaxSerialItemsIssued'] . "' WHERE confname = 'MaxSerialItemsIssued'";
		}
		if ($_SESSION['WorkingDaysWeek'] != $_POST['X_WorkingDaysWeek'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_WorkingDaysWeek']."' WHERE confname = 'WorkingDaysWeek'";
		}

		if ($_SESSION['DispatchCutOffTime'] != $_POST['X_DispatchCutOffTime'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_DispatchCutOffTime']."' WHERE confname = 'DispatchCutOffTime'";
		}
		if ($_SESSION['AllowSalesOfZeroCostItems'] != $_POST['X_AllowSalesOfZeroCostItems'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_AllowSalesOfZeroCostItems']."' WHERE confname = 'AllowSalesOfZeroCostItems'";
		}
		if ($_SESSION['CreditingControlledItems_MustExist'] != $_POST['X_CreditingControlledItems_MustExist'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_CreditingControlledItems_MustExist']."' WHERE confname = 'CreditingControlledItems_MustExist'";
		}
		if ($_SESSION['DefaultPriceList'] != $_POST['X_DefaultPriceList'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_DefaultPriceList']."' WHERE confname = 'DefaultPriceList'";
		}
		if ($_SESSION['Default_Shipper'] != $_POST['X_Default_Shipper'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_Default_Shipper']."' WHERE confname = 'Default_Shipper'";
		}
		if ($_SESSION['DoFreightCalc'] != $_POST['X_DoFreightCalc'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_DoFreightCalc']."' WHERE confname = 'DoFreightCalc'";
		}
		if ($_SESSION['FreightChargeAppliesIfLessThan'] != $_POST['X_FreightChargeAppliesIfLessThan'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_FreightChargeAppliesIfLessThan']."' WHERE confname = 'FreightChargeAppliesIfLessThan'";
		}
		if ($_SESSION['DefaultTaxCategory'] != $_POST['X_DefaultTaxCategory'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_DefaultTaxCategory']."' WHERE confname = 'DefaultTaxCategory'";
		}
		if ($_SESSION['TaxAuthorityReferenceName'] != $_POST['X_TaxAuthorityReferenceName'] ) {
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_TaxAuthorityReferenceName'] . "' WHERE confname = 'TaxAuthorityReferenceName'";
		}
		if ($_SESSION['CountryOfOperation'] != $_POST['X_CountryOfOperation'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_CountryOfOperation'] ."' WHERE confname = 'CountryOfOperation'";
		}
		if ($_SESSION['StandardCostDecimalPlaces'] != $_POST['X_StandardCostDecimalPlaces'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_StandardCostDecimalPlaces']."' WHERE confname = 'StandardCostDecimalPlaces'";
		}
		if ($_SESSION['NumberOfPeriodsOfStockUsage'] != $_POST['X_NumberOfPeriodsOfStockUsage'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_NumberOfPeriodsOfStockUsage']."' WHERE confname = 'NumberOfPeriodsOfStockUsage'";
		}
		if ($_SESSION['Check_Qty_Charged_vs_Del_Qty'] != $_POST['X_Check_Qty_Charged_vs_Del_Qty'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_Check_Qty_Charged_vs_Del_Qty']."' WHERE confname = 'Check_Qty_Charged_vs_Del_Qty'";
		}
		if ($_SESSION['Check_Price_Charged_vs_Order_Price'] != $_POST['X_Check_Price_Charged_vs_Order_Price'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_Check_Price_Charged_vs_Order_Price']."' WHERE confname = 'Check_Price_Charged_vs_Order_Price'";
		}
		if ($_SESSION['OverChargeProportion'] != $_POST['X_OverChargeProportion'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_OverChargeProportion']."' WHERE confname = 'OverChargeProportion'";
		}
		if ($_SESSION['OverReceiveProportion'] != $_POST['X_OverReceiveProportion'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_OverReceiveProportion']."' WHERE confname = 'OverReceiveProportion'";
		}
		if ($_SESSION['PO_AllowSameItemMultipleTimes'] != $_POST['X_PO_AllowSameItemMultipleTimes'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_PO_AllowSameItemMultipleTimes']."' WHERE confname = 'PO_AllowSameItemMultipleTimes'";
		}
		if ($_SESSION['SO_AllowSameItemMultipleTimes'] != $_POST['X_SO_AllowSameItemMultipleTimes'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_SO_AllowSameItemMultipleTimes']."' WHERE confname = 'SO_AllowSameItemMultipleTimes'";
		}
		if ($_SESSION['YearEnd'] != $_POST['X_YearEnd'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_YearEnd']."' WHERE confname = 'YearEnd'";
		}
		if ($_SESSION['PageLength'] != $_POST['X_PageLength'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_PageLength']."' WHERE confname = 'PageLength'";
		}
		if ($_SESSION['DefaultDisplayRecordsMax'] != $_POST['X_DefaultDisplayRecordsMax'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_DefaultDisplayRecordsMax']."' WHERE confname = 'DefaultDisplayRecordsMax'";
		}
		if ($_SESSION['MaxImageSize'] != $_POST['X_MaxImageSize'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_MaxImageSize']."' WHERE confname = 'MaxImageSize'";
		}
		if ($_SESSION['ShowStockidOnImages'] != $_POST['X_ShowStockidOnImages'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_ShowStockidOnImages']."' WHERE confname = 'ShowStockidOnImages'";
		}
//new number must be shown
		if ($_SESSION['NumberOfMonthMustBeShown'] != $_POST['X_NumberOfMonthMustBeShown'] ) {
			$sql[] = "UPDATE config SET confvalue = '".$_POST['X_NumberOfMonthMustBeShown']."' WHERE confname = 'NumberOfMonthMustBeShown'";
		}
		if ($_SESSION['part_pics_dir'] != $_POST['X_part_pics_dir'] ) {
			$sql[] = "UPDATE config SET confvalue = 'companies/" . $_SESSION['DatabaseName'] . '/' . $_POST['X_part_pics_dir']."' WHERE confname = 'part_pics_dir'";
		}
		if ($_SESSION['reports_dir'] != $_POST['X_reports_dir'] ) {
			$sql[] = "UPDATE config SET confvalue = 'companies/" . $_SESSION['DatabaseName'] . '/' . $_POST['X_reports_dir']."' WHERE confname = 'reports_dir'";
		}
		if ($_SESSION['AutoDebtorNo'] != $_POST['X_AutoDebtorNo'] ) {
			$sql[] = "UPDATE config SET confvalue = '". ($_POST['X_AutoDebtorNo'])."' WHERE confname = 'AutoDebtorNo'";
		}
		if ($_SESSION['AutoSupplierNo'] != $_POST['X_AutoSupplierNo'] ) {
			$sql[] = "UPDATE config SET confvalue = '". ($_POST['X_AutoSupplierNo'])."' WHERE confname = 'AutoSupplierNo'";
		}
		if ($_SESSION['HTTPS_Only'] != $_POST['X_HTTPS_Only'] ) {
			$sql[] = "UPDATE config SET confvalue = '". ($_POST['X_HTTPS_Only'])."' WHERE confname = 'HTTPS_Only'";
		}
		if ($_SESSION['DB_Maintenance'] != $_POST['X_DB_Maintenance'] ) {
			$sql[] = "UPDATE config SET confvalue = '". ($_POST['X_DB_Maintenance'])."' WHERE confname = 'DB_Maintenance'";
		}
		if ($_SESSION['DefaultBlindPackNote'] != $_POST['X_DefaultBlindPackNote'] ) {
			$sql[] = "UPDATE config SET confvalue = '". ($_POST['X_DefaultBlindPackNote'])."' WHERE confname = 'DefaultBlindPackNote'";
		}
		if ($_SESSION['ShowValueOnGRN'] != $_POST['X_ShowValueOnGRN'] ) {
			$sql[] = "UPDATE config SET confvalue = '". ($_POST['X_ShowValueOnGRN'])."' WHERE confname = 'ShowValueOnGRN'";
		}
		if ($_SESSION['PackNoteFormat'] != $_POST['X_PackNoteFormat'] ) {
			$sql[] = "UPDATE config SET confvalue = '". ($_POST['X_PackNoteFormat'])."' WHERE confname = 'PackNoteFormat'";
		}
		if ($_SESSION['CheckCreditLimits'] != $_POST['X_CheckCreditLimits'] ) {
			$sql[] = "UPDATE config SET confvalue = '". ($_POST['X_CheckCreditLimits'])."' WHERE confname = 'CheckCreditLimits'";
		}
		if ($_SESSION['WikiApp'] !== $_POST['X_WikiApp'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_WikiApp']."' WHERE confname = 'WikiApp'";
		}
		if ($_SESSION['WikiPath'] != $_POST['X_WikiPath'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_WikiPath']."' WHERE confname = 'WikiPath'";
		}
		if ($_SESSION['ProhibitJournalsToControlAccounts'] != $_POST['X_ProhibitJournalsToControlAccounts'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_ProhibitJournalsToControlAccounts']."' WHERE confname = 'ProhibitJournalsToControlAccounts'";
		}
		if ($_SESSION['InvoiceQuantityDefault'] != $_POST['X_InvoiceQuantityDefault'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_InvoiceQuantityDefault']."' WHERE confname = 'InvoiceQuantityDefault'";
		}
		if ($_SESSION['InvoicePortraitFormat'] != $_POST['X_InvoicePortraitFormat'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_InvoicePortraitFormat']."' WHERE confname = 'InvoicePortraitFormat'";
		}
		if ($_SESSION['AllowOrderLineItemNarrative'] != $_POST['X_AllowOrderLineItemNarrative'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_AllowOrderLineItemNarrative']."' WHERE confname = 'AllowOrderLineItemNarrative'";
		}
		if ($_SESSION['GoogleTranslatorAPIKey'] != $_POST['X_GoogleTranslatorAPIKey'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_GoogleTranslatorAPIKey']."' WHERE confname = 'GoogleTranslatorAPIKey'";
		}
		if ($_SESSION['RequirePickingNote'] != $_POST['X_RequirePickingNote'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_RequirePickingNote']."' WHERE confname = 'RequirePickingNote'";
		}
		if ($_SESSION['geocode_integration'] != $_POST['X_geocode_integration'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_geocode_integration']."' WHERE confname = 'geocode_integration'";
		}
		if ($_SESSION['Extended_SupplierInfo'] != $_POST['X_Extended_SupplierInfo'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_Extended_SupplierInfo']."' WHERE confname = 'Extended_SupplierInfo'";
		}
		if ($_SESSION['Extended_CustomerInfo'] != $_POST['X_Extended_CustomerInfo'] ) {
			$sql[] = "UPDATE config SET confvalue = '". $_POST['X_Extended_CustomerInfo']."' WHERE confname = 'Extended_CustomerInfo'";
		}
		if ($_SESSION['ProhibitPostingsBefore'] != $_POST['X_ProhibitPostingsBefore'] ) {
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_ProhibitPostingsBefore']."' WHERE confname = 'ProhibitPostingsBefore'";
		}
		if ($_SESSION['WeightedAverageCosting'] != $_POST['X_WeightedAverageCosting'] ) {
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_WeightedAverageCosting']."' WHERE confname = 'WeightedAverageCosting'";
		}
		if ($_SESSION['AutoIssue'] != $_POST['X_AutoIssue']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_AutoIssue'] . "' WHERE confname='AutoIssue'";
		}
		if ($_SESSION['ProhibitNegativeStock'] != $_POST['X_ProhibitNegativeStock']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_ProhibitNegativeStock'] . "' WHERE confname='ProhibitNegativeStock'";
		}
		if ($_SESSION['MonthsAuditTrail'] != $_POST['X_MonthsAuditTrail']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_MonthsAuditTrail'] . "' WHERE confname='MonthsAuditTrail'";
		}
		if ($_SESSION['LogSeverity'] != $_POST['X_LogSeverity']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_LogSeverity'] . "' WHERE confname='LogSeverity'";
		}
		if ($_SESSION['LogPath'] != $_POST['X_LogPath']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_LogPath'] . "' WHERE confname='LogPath'";
		}
		if ($_SESSION['UpdateCurrencyRatesDaily'] != $_POST['X_UpdateCurrencyRatesDaily']){
			if ($_POST['X_UpdateCurrencyRatesDaily']==1) {
				$sql[] = "UPDATE config SET confvalue='".Date('Y-m-d')."' WHERE confname='UpdateCurrencyRatesDaily'";
			} else {
				$sql[] = "UPDATE config SET confvalue='0' WHERE confname='UpdateCurrencyRatesDaily'";
			}
		}
		if ($_SESSION['ExchangeRateFeed'] != $_POST['X_ExchangeRateFeed']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_ExchangeRateFeed'] . "' WHERE confname='ExchangeRateFeed'";
		}
		if ($_SESSION['FactoryManagerEmail'] != $_POST['X_FactoryManagerEmail']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_FactoryManagerEmail'] . "' WHERE confname='FactoryManagerEmail'";
		}
		if ($_SESSION['PurchasingManagerEmail'] != $_POST['X_PurchasingManagerEmail']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_PurchasingManagerEmail'] . "' WHERE confname='PurchasingManagerEmail'";
		}
		if ($_SESSION['InventoryManagerEmail'] != $_POST['X_InventoryManagerEmail']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_InventoryManagerEmail'] . "' WHERE confname='InventoryManagerEmail'";
		}
		if ($_SESSION['AutoCreateWOs'] != $_POST['X_AutoCreateWOs']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_AutoCreateWOs'] . "' WHERE confname='AutoCreateWOs'";
		}
		if ($_SESSION['DefaultFactoryLocation'] != $_POST['X_DefaultFactoryLocation']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_DefaultFactoryLocation'] . "' WHERE confname='DefaultFactoryLocation'";
		}
		if ($_SESSION['DefineControlledOnWOEntry'] != $_POST['X_DefineControlledOnWOEntry']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_DefineControlledOnWOEntry'] . "' WHERE confname='DefineControlledOnWOEntry'";
		}
		if ($_SESSION['FrequentlyOrderedItems'] != $_POST['X_FrequentlyOrderedItems']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_FrequentlyOrderedItems'] . "' WHERE confname='FrequentlyOrderedItems'";
		}
		if ($_SESSION['AutoAuthorisePO'] != $_POST['X_AutoAuthorisePO']){
			$sql[] = "UPDATE config SET confvalue='" . $_POST['X_AutoAuthorisePO'] . "' WHERE confname='AutoAuthorisePO'";
		}
		if (isset($_POST['X_ItemDescriptionLanguages'])) {
			$ItemDescriptionLanguages = '';
			foreach ($_POST['X_ItemDescriptionLanguages'] as $ItemLanguage){
				$ItemDescriptionLanguages .= $ItemLanguage .',';
			}

			if ($_SESSION['ItemDescriptionLanguages'] != $ItemDescriptionLanguages){
				$sql[] = "UPDATE config SET confvalue='" . $ItemDescriptionLanguages . "' WHERE confname='ItemDescriptionLanguages'";
			}
		}
		if ($_SESSION['SmtpSetting'] != $_POST['X_SmtpSetting']){
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_SmtpSetting'] . "' WHERE confname='SmtpSetting'";

		}
		if ($_SESSION['QualityLogSamples'] != $_POST['X_QualityLogSamples']){
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_QualityLogSamples'] . "' WHERE confname='QualityLogSamples'";

		}
		if ($_SESSION['QualityProdSpecText'] != $_POST['X_QualityProdSpecText']){
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_QualityProdSpecText'] . "' WHERE confname='QualityProdSpecText'";

		}
		if ($_SESSION['QualityCOAText'] != $_POST['X_QualityCOAText']){
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_QualityCOAText'] . "' WHERE confname='QualityCOAText'";

		}
		if ($_SESSION['ShortcutMenu'] != $_POST['X_ShortcutMenu']){
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_ShortcutMenu'] . "' WHERE confname='ShortcutMenu'";

		}
		if ($_SESSION['LastDayOfWeek'] != $_POST['X_LastDayOfWeek']){
			$sql[] = "UPDATE config SET confvalue = '" . $_POST['X_LastDayOfWeek'] . "' WHERE confname='LastDayOfWeek'";

		}

		$ErrMsg =  _('The system configuration could not be updated because');
		if (sizeof($sql) > 1 ) {
			$result = DB_Txn_Begin();
			foreach ($sql as $line) {
				$result = DB_query($line,$ErrMsg);
			}
			$result = DB_Txn_Commit();
		} elseif(sizeof($sql)==1) {
			$result = DB_query($sql,$ErrMsg);
		}

		prnMsg( _('System configuration updated'),'success');

		$ForceConfigReload = True; // Required to force a load even if stored in the session vars
		include('includes/GetConfig.php');
		$ForceConfigReload = False;
	} else {
		prnMsg( _('Validation failed') . ', ' . _('no updates or deletes took place'),'warn');
	}

} /* end of if submit */

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">
	<div>
	<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
	<table cellpadding="2" class="selection" width="98%">';

$TableHeader = '<tr>
					<th>' . _('System Variable Name') . '</th>
					<th>' . _('Value') . '</th>
					<th>' . _('Notes') . '</th>
                </tr>';

echo '<tr><th colspan="3">' . _('General Settings') . '</th></tr>';
echo $TableHeader;

// DefaultDateFormat
echo '<tr style="outline: 1px solid"><td>' . _('Default Date Format') . ':</td>
	<td><select name="X_DefaultDateFormat">
	<option '.(($_SESSION['DefaultDateFormat']=='d/m/Y')?'selected="selected" ':'').'value="d/m/Y">' . _('d/m/Y') . '</option>
	<option '.(($_SESSION['DefaultDateFormat']=='d.m.Y')?'selected="selected" ':'').'value="d.m.Y">' . _('d.m.Y') . '</option>
	<option '.(($_SESSION['DefaultDateFormat']=='m/d/Y')?'selected="selected" ':'').'value="m/d/Y">' . _('m/d/Y') . '</option>
	<option '.(($_SESSION['DefaultDateFormat']=='Y/m/d')?'selected="selected" ':'').'value="Y/m/d">' . _('Y/m/d') . '</option>
	<option '.(($_SESSION['DefaultDateFormat']=='Y-m-d')?'selected="selected" ':'').'value="Y-m-d">' . _('Y-m-d') . '</option>
	</select></td>
	<td>' . _('The default date format for entry of dates and display.') . '</td></tr>';

// DefaultTheme:
if (is_writable('config.php')) {
	echo '<tr style="outline: 1px solid"><td>' . _('Default Theme') . ':</td>
			<td><select name="X_DefaultTheme">';
	$ThemeDirectories = scandir('css');// List directories inside ~/css. Each diretory is a theme.
	foreach($ThemeDirectories as $ThemeName) {
		if(is_dir('css/'.$ThemeName) AND $ThemeName!='.' AND $ThemeName!='..' AND $ThemeName!='.svn') {
			echo '<option';
			if ($DefaultTheme == $ThemeName) {
				echo ' selected="selected"';
			}
			echo ' value="'. $ThemeName.'">' . $ThemeName . '</option>';
		}
	}
	echo '</select></td>
			<td>' . _("The default theme to use for the login screen and the setup of new users. The users' theme selection will override it.") . '</td>
		</tr>';
} else {
	echo '<input type="hidden" name="X_DefaultTheme" value="' . $DefaultTheme . '" />';
}
// ---------- New section:
echo '<tr><th colspan="3">' . _('Accounts Receivable/Payable Settings') . '</th></tr>';

// PastDueDays1
echo '<tr style="outline: 1px solid"><td>' . _('First Overdue Deadline in (days)') . ':</td>
	<td><input type="text" class="integer" required="required"  pattern="(?!^0\d+$)[\d]+" title="'._('The input must be integer').'" name="X_PastDueDays1" value="' . $_SESSION['PastDueDays1'] . '" size="3" maxlength="3" /></td>
	<td>' . _('Customer and supplier balances are displayed as overdue by this many days. This parameter is used on customer and supplier enquiry screens and aged listings') . '</td></tr>';

// PastDueDays2
echo '<tr style="outline: 1px solid"><td>' . _('Second Overdue Deadline in (days)') . ':</td>
	<td><input type="text" class="integer" required="required"  pattern="(?!^0\d+$)[\d]+" title="'._('The input must be integer').'" name="X_PastDueDays2" value="' . $_SESSION['PastDueDays2'] . '" size="3" maxlength="3" /></td>
	<td>' . _('As above but the next level of overdue') . '</td></tr>';


// DefaultCreditLimit
echo '<tr style="outline: 1px solid"><td>' . _('Default Credit Limit') . ':</td>
	<td><input type="text" class="number" required="required" title="'._('The input must be numeric').'" name="X_DefaultCreditLimit" value="' . $_SESSION['DefaultCreditLimit'] . '" size="12" maxlength="12" /></td>
	<td>' . _('The default used in new customer set up') . '</td></tr>';

// Check Credit Limits
echo '<tr style="outline: 1px solid"><td>' . _('Check Credit Limits') . ':</td>
	<td><select name="X_CheckCreditLimits">
	<option '.($_SESSION['CheckCreditLimits']==0?'selected="selected" ':'').'value="0">' . _('Do not check') . '</option>
	<option '.($_SESSION['CheckCreditLimits']==1?'selected="selected" ':'').'value="1">' . _('Warn on breach') . '</option>
	<option '.($_SESSION['CheckCreditLimits']==2?'selected="selected" ':'').'value="2">' . _('Prohibit Sales') . '</option>
	</select></td>
	<td>' . _('Credit limits can be checked at order entry to warn only or to stop the order from being entered where it would take a customer account balance over their limit') . '</td></tr>';

// Show_Settled_LastMonth
echo '<tr style="outline: 1px solid"><td>' . _('Show Settled Last Month') . ':</td>
	<td><select name="X_Show_Settled_LastMonth">
	<option '.($_SESSION['Show_Settled_LastMonth']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['Show_Settled_LastMonth']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('This setting refers to the format of customer statements. If the invoices and credit notes that have been paid and settled during the course of the current month should be shown then select Yes. Selecting No will only show currently outstanding invoices, credits and payments that have not been allocated') . '</td></tr>';

//RomalpaClause
echo '<tr style="outline: 1px solid"><td>' . _('Romalpa Clause') . ':</td>
	<td><textarea name="X_RomalpaClause" rows="3" cols="40">' . $_SESSION['RomalpaClause'] . '</textarea></td>
	<td>' . _('This text appears on invoices and credit notes in small print. Normally a reservation of title clause that gives the company rights to collect goods which have not been paid for - to give some protection for bad debts.') . '</td></tr>';

// QuickEntries
echo '<tr style="outline: 1px solid"><td>' . _('Quick Entries') . ':</td>
	<td><input type="text" class="integer" required="required" pattern="[1-9][\d]{0,1}" name="X_QuickEntries" value="' . $_SESSION['QuickEntries'] . '" size="3" maxlength="2" /></td>
	<td>' . _('This parameter defines the layout of the sales order entry screen. The number of fields available for quick entries. Any number from 1 to 99 can be entered.') . '</td></tr>';

// MaxSerialItemsIssued
echo '<tr style="outline: 1px solid"><td>' . _('Maximum number of serial numbered items that can be issued') . ':</td>
	<td><input type="text" class="integer" name="X_MaxSerialItemsIssued" value="' . $_SESSION['MaxSerialItemsIssued'] . '" size="3" required="required" maxlength="10" /></td>
	<td>' . _('This parameter defines the Maximum number of serial numbered items that can be issued. It should be an integer greater than zero.') . '</td></tr>';

// Frequently Ordered Items
echo '<tr style="outline: 1px solid"><td>' . _('Frequently Ordered Items') . ':</td>
	<td><input type="text" class="integer" pattern="(?!^0[1-9]+$)[\d]{1,2}" name="X_FrequentlyOrderedItems" value="' . $_SESSION['FrequentlyOrderedItems'] . '" size="3" maxlength="2" /></td>
	<td>' . _('To show the most frequently ordered items enter the number of frequently ordered items you wish to display from 1 to 99. If you do not wish to display the frequently ordered item list enter 0.') . '</td></tr>';

// SO_AllowSameItemMultipleTimes
echo '<tr style="outline: 1px solid"><td>' . _('Sales Order Allows Same Item Multiple Times') . ':</td>
	<td><select name="X_SO_AllowSameItemMultipleTimes">
	<option '.($_SESSION['SO_AllowSameItemMultipleTimes']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['SO_AllowSameItemMultipleTimes']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td><td>&nbsp;</td></tr>';

//'AllowOrderLineItemNarrative'
echo '<tr style="outline: 1px solid">
		<td>' . _('Order Entry allows Line Item Narrative') . ':</td>
		<td><select name="X_AllowOrderLineItemNarrative">
		<option ' . ($_SESSION['AllowOrderLineItemNarrative']=='1'?'selected="selected" ':'').'value="1">' . _('Allow Narrative Entry') . '</option>
		<option ' . ($_SESSION['AllowOrderLineItemNarrative']=='0'?'selected="selected" ':'').'value="0">' . _('No Narrative Line') . '</option>
		</select></td>
		<td>' . _('Select whether or not to allow entry of narrative on order line items. This narrative will appear on invoices and packing slips. Useful mainly for service businesses.') . '</td>
	</tr>';
//ItemDescriptionLanguages
if (!isset($_POST['X_ItemDescriptionLanguages'])){
	$_POST['X_ItemDescriptionLanguages'] = explode(',',$_SESSION['ItemDescriptionLanguages']);
}
echo '<tr style="outline: 1px solid">
		<td>' . _('Languages to Maintain Translations for Item Descriptions') . ':</td>
		<td><select name="X_ItemDescriptionLanguages[]" size="5" multiple="multiple" >';

		echo '<option value=""' . (count($LanguagesArray)==0 ? '':'selected="selected"') . '>' . _('None')  . '</option>';
foreach ($LanguagesArray as $LanguageEntry => $LanguageName){
	if (isset($_POST['X_ItemDescriptionLanguages']) AND in_array($LanguageEntry,$_POST['X_ItemDescriptionLanguages'])){
		echo '<option selected="selected" value="' . $LanguageEntry . '">' . $LanguageName['LanguageName']  . '</option>';
	} elseif ($LanguageEntry != $DefaultLanguage) {
		echo '<option value="' . $LanguageEntry . '">' . $LanguageName['LanguageName']  . '</option>';
	}
}
echo '</select></td>
		<td>' . _('Select the languages in which translations of the item description will be maintained. The default language is excluded.') . '</td>
	</tr>';

// Google Translator API Key
echo '<tr style="outline: 1px solid"><td>' . _('Google Translator API Key') . ':</td>
	<td><input type="text" name="X_GoogleTranslatorAPIKey" size="25" maxlength="50" value="' . $_SESSION['GoogleTranslatorAPIKey'] . '" /></td>
	<td>' . _('Google Translator API Key to allow automatic translations. More info at https://cloud.google.com/translate/')  . '</td></tr>';

//'RequirePickingNote'
echo '<tr style="outline: 1px solid"><td>' . _('A picking note must be produced before an order can be delivered') . ':</td>
	<td><select name="X_RequirePickingNote">
	<option '.($_SESSION['RequirePickingNote']=='1'?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.($_SESSION['RequirePickingNote']=='0'?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('Select whether or not a picking note must be produced before an order can be delivered to a customer.') . '</td>
	</tr>';

//UpdateCurrencyRatesDaily
echo '<tr style="outline: 1px solid"><td>' . _('Auto Update Exchange Rates Daily') . ':</td>
	<td><select name="X_UpdateCurrencyRatesDaily">
	<option '.($_SESSION['UpdateCurrencyRatesDaily']!='1'?'selected="selected" ':'').'value="1">' . _('Automatic') . '</option>
	<option '.($_SESSION['UpdateCurrencyRatesDaily']=='0'?'selected="selected" ':'').'value="0">' . _('Manually') . '</option>
	</select></td>
	<td>' . _('Automatic updates to exchange rates will retrieve the latest daily rates from either the European Central Bank or Google once per day - when the first user logs in for the day. Manual will never update the rates automatically - exchange rates will need to be maintained manually') . '</td>
	</tr>';
echo '<tr style="outline: 1px solid"><td>' . _('Source Exchange Rates From') . ':</td>
	<td><select name="X_ExchangeRateFeed">
	<option '.($_SESSION['ExchangeRateFeed']!='ECB'?'selected="selected" ':'').'value="ECB">' . _('European Central Bank') . '</option>
	<option '.($_SESSION['ExchangeRateFeed']=='Google'?'selected="selected" ':'').'value="Google">' . _('Google') . '</option>
	</select></td>
	<td>' . _('Specify the source to use for exchange rates') . '</td>
	</tr>';

//Default Packing Note Format
echo '<tr style="outline: 1px solid"><td>' . _('Format of Packing Slips') . ':</td>
	<td><select name="X_PackNoteFormat">
	<option '.($_SESSION['PackNoteFormat']=='1'?'selected="selected" ':'').'value="1">' . _('Laser Printed') . '</option>
	<option '.($_SESSION['PackNoteFormat']=='2'?'selected="selected" ':'').'value="2">' . _('Special Stationery') . '</option>
	</select></td>
	<td>' . _('Choose the format that packing notes should be printed by default') . '</td>
	</tr>';

//Default Invoice Format
echo '<tr style="outline: 1px solid"><td>' . _('Invoice Orientation') . ':</td>
	<td><select name="X_InvoicePortraitFormat">
	<option '.($_SESSION['InvoicePortraitFormat']=='0'?'selected="selected" ':'').'value="0">' . _('Landscape') . '</option>
	<option '.($_SESSION['InvoicePortraitFormat']=='1'?'selected="selected" ':'').'value="1">' . _('Portrait') . '</option>
	</select></td>
	<td>' . _('Select the invoice layout') . '</td>
	</tr>';

//Default Invoice Quantity
echo '<tr style="outline: 1px solid"><td>' . _('Invoice Quantity Default') . ':</td>
	<td><select name="X_InvoiceQuantityDefault">
	<option '.($_SESSION['InvoiceQuantityDefault']=='0'?'selected="selected" ':'').'value="0">0</option>
	<option '.($_SESSION['InvoiceQuantityDefault']=='1'?'selected="selected" ':'').'value="1">' . _('Outstanding') . '</option>
	</select></td>
	<td>' . _('This setting controls the default behaviour of invoicing. Setting to 0 defaults the invocie quantity to zero to force entry. Set to outstanding to default the invoice quantity to the balance outstanding, after previous deliveries, on the sales order') . '</td>
	</tr>';

//Blind packing note
echo '<tr style="outline: 1px solid"><td>' . _('Show company details on packing slips') . ':</td>
	<td><select name="X_DefaultBlindPackNote">
	<option '.($_SESSION['DefaultBlindPackNote']=='1'?'selected="selected" ':'').'value="1">' . _('Show Company Details') . '</option>
	<option '.($_SESSION['DefaultBlindPackNote']=='2'?'selected="selected" ':'').'value="2">' . _('Hide Company Details') . '</option>
	</select></td>
	<td>' . _('Customer branches can be set by default not to print packing slips with the company logo and address. This is useful for companies that ship to customers customers and to show the source of the shipment would be inappropriate. There is an option on the setup of customer branches to ship blind, this setting is the default applied to all new customer branches') . '</td>
	</tr>';

// Working days on a week
echo '<tr style="outline: 1px solid">
		<td>' . _('Working Days on a Week') . ':</td>
		<td><select name="X_WorkingDaysWeek">
			<option '.($_SESSION['WorkingDaysWeek']=='7'?'selected="selected" ':'').'value="7">7 '._('working days') . '</option>
			<option '.($_SESSION['WorkingDaysWeek']=='6'?'selected="selected" ':'').'value="6">6 '._('working days') . '</option>
			<option '.($_SESSION['WorkingDaysWeek']=='5'?'selected="selected" ':'').'value="5">5 '._('working days') . '</option>
			</select></td>
		<td>' . _('Number of working days on a week') . '</td>
	</tr>';

// DispatchCutOffTime
echo '<tr style="outline: 1px solid">
		<td>' . _('Dispatch Cut-Off Time') . ':</td>
		<td><select name="X_DispatchCutOffTime">';
for ($i=0; $i < 24; $i++ )
	echo '<option '.($_SESSION['DispatchCutOffTime'] == $i?'selected="selected" ':'').'value="'.$i.'">' . $i . '</option>';
echo '</select></td>
	<td>' . _('Orders entered after this time will default to be dispatched the following day, this can be over-ridden at the time of sales order entry') . '</td></tr>';

// AllowSalesOfZeroCostItems
echo '<tr style="outline: 1px solid"><td>' . _('Allow Sales Of Zero Cost Items') . ':</td>
	<td><select name="X_AllowSalesOfZeroCostItems">
	<option '.($_SESSION['AllowSalesOfZeroCostItems']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['AllowSalesOfZeroCostItems']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('If an item selected at order entry does not have a cost set up then if this parameter is set to No then the order line will not be able to be entered') . '</td></tr>';

// CreditingControlledItems_MustExist
echo '<tr style="outline: 1px solid"><td>' . _('Controlled Items Must Exist For Crediting') . ':</td>
	<td><select name="X_CreditingControlledItems_MustExist">
	<option '.($_SESSION['CreditingControlledItems_MustExist']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['CreditingControlledItems_MustExist']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('This parameter relates to the behaviour of the controlled items code. If a serial numbered item has not previously existed then a credit note for it will not be allowed if this is set to Yes') . '</td></tr>';

// DefaultPriceList
$sql = "SELECT typeabbrev, sales_type FROM salestypes ORDER BY sales_type";
$ErrMsg = _('Could not load price lists');
$result = DB_query($sql,$ErrMsg);
echo '<tr style="outline: 1px solid"><td>' . _('Default Price List') . ':</td>';
echo '<td><select name="X_DefaultPriceList">';
if( DB_num_rows($result) == 0 ) {
	echo '<option selected="selected" value="">' . _('Unavailable');
} else {
	while( $row = DB_fetch_array($result) ) {
		echo '<option '.($_SESSION['DefaultPriceList'] == $row['typeabbrev']?'selected="selected" ':'').'value="'.$row['typeabbrev'].'">' . $row['sales_type'] . '</option>';
	}
}
echo '</select></td>
	<td>' . _('This price list is used as a last resort where there is no price set up for an item in the price list that the customer is set up for') . '</td></tr>';

// Default_Shipper
$sql = "SELECT shipper_id, shippername FROM shippers ORDER BY shippername";
$ErrMsg = _('Could not load shippers');
$result = DB_query($sql,$ErrMsg);
echo '<tr style="outline: 1px solid"><td>' . _('Default Shipper') . ':</td>';
echo '<td><select name="X_Default_Shipper">';
if( DB_num_rows($result) == 0 ) {
	echo '<option selected="selected" value="">' . _('Unavailable') . '</option>';
} else {
	while( $row = DB_fetch_array($result) ) {
		echo '<option '.($_SESSION['Default_Shipper'] == $row['shipper_id']?'selected="selected" ':'').'value="'.$row['shipper_id'].'">' . $row['shippername'] . '</option>';
	}
}
echo '</select></td>
	<td>' . _('This shipper is used where the best shipper for a customer branch has not been defined previously') . '</td></tr>';

// DoFreightCalc
echo '<tr style="outline: 1px solid"><td>' . _('Do Freight Calculation') . ':</td>
	<td><select name="X_DoFreightCalc">
	<option '.($_SESSION['DoFreightCalc']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['DoFreightCalc']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('If this is set to Yes then the system will attempt to calculate the freight cost of a dispatch based on the weight and cubic and the data defined for each shipper and their rates for shipping to various locations. The results of this calculation will only be meaningful if the data is entered for the item weight and volume in the stock item setup for all items and the freight costs for each shipper properly maintained.') . '</td></tr>';

//FreightChargeAppliesIfLessThan
echo '<tr style="outline: 1px solid"><td>' . _('Apply freight charges if an order is less than') . ':</td>
	<td><input type="text" class="number" required="required" title="'._('The input must be numeric').'" name="X_FreightChargeAppliesIfLessThan" size="12" maxlength="12" value="' . $_SESSION['FreightChargeAppliesIfLessThan'] . '" /></td>
	<td>' . _('This parameter is only effective if Do Freight Calculation is set to Yes. If it is set to 0 then freight is always charged. The total order value is compared to this value in deciding whether or not to charge freight')  . '</td></tr>';


// AutoDebtorNo
echo '<tr style="outline: 1px solid"><td>' . _('Create Debtor Codes Automatically') . ':</td>
	<td><select name="X_AutoDebtorNo">';

if ($_SESSION['AutoDebtorNo']==0) {
	echo '<option selected="selected" value="0">' . _('Manual Entry') . '</option>';
	echo '<option value="1">' . _('Automatic') . '</option>';
} else {
	echo '<option selected="selected" value="1">' . _('Automatic') . '</option>';
	echo '<option value="0">' . _('Manual Entry') . '</option>';
}
echo '</select></td>
	<td>' . _('Set to Automatic - customer codes are automatically created - as a sequential number')  . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Create Supplier Codes Automatically') . ':</td>
	<td><select name="X_AutoSupplierNo">';

if ($_SESSION['AutoSupplierNo']==0) {
	echo '<option selected="selected" value="0">' . _('Manual Entry') . '</option>';
	echo '<option value="1">' . _('Automatic') . '</option>';
} else {
	echo '<option selected="selected" value="1">' . _('Automatic') . '</option>';
	echo '<option value="0">' . _('Manual Entry') . '</option>';
}
echo '</select></td>
	<td>' . _('Set to Automatic - Supplier codes are automatically created - as a sequential number')  . '</td></tr>';



//==HJ== drop down list for tax category
$sql = "SELECT taxcatid, taxcatname FROM taxcategories ORDER BY taxcatname";
$ErrMsg = _('Could not load tax categories table');
$result = DB_query($sql,$ErrMsg);
echo '<tr style="outline: 1px solid"><td>' . _('Default Tax Category') . ':</td>';
echo '<td><select name="X_DefaultTaxCategory">';
if( DB_num_rows($result) == 0 ) {
	echo '<option selected="selected" value="">' . _('Unavailable') . '</option>';
} else {
	while( $row = DB_fetch_array($result) ) {
		echo '<option '.($_SESSION['DefaultTaxCategory'] == $row['taxcatid']?'selected="selected" ':'').'value="'.$row['taxcatid'].'">' . $row['taxcatname'] . '</option>';
	}
}
echo '</select></td>
	<td>' . _('This is the tax category used for entry of supplier invoices and the category at which freight attracts tax')  . '</td></tr>';


//TaxAuthorityReferenceName
echo '<tr style="outline: 1px solid"><td>' . _('Tax Authority Reference Name') . ':</td>
	<td><input type="text" name="X_TaxAuthorityReferenceName" size="16" maxlength="25" value="' . $_SESSION['TaxAuthorityReferenceName'] . '" /></td>
	<td>' . _('This parameter is what is displayed on tax invoices and credits for the tax authority of the company eg. in Australian this would by A.B.N.: - in NZ it would be GST No: in the UK it would be VAT Regn. No')  . '</td></tr>';

// CountryOfOperation

echo '<tr style="outline: 1px solid"><td>' . _('Country Of Operation') . ':</td>';
echo '<td><select name="X_CountryOfOperation">';
echo '<option selected="selected" value="">' . _('Unavailable') . '</option>';
foreach ($CountriesArray as $CountryEntry => $CountryName){
    echo '<option ' . ($_SESSION['CountryOfOperation'] == $CountryEntry?'selected="selected" ':'') . ' value="' . $CountryEntry . '">' . $CountryName  . '</option>';
}

echo '</select></td>
	<td>' . _('This parameter is only effective if Do Freight Calculation is set to Yes.')  . '</td></tr>';

// StandardCostDecimalPlaces
echo '<tr style="outline: 1px solid"><td>' . _('Standard Cost Decimal Places') . ':</td>
	<td><select name="X_StandardCostDecimalPlaces">';
for ($i=0; $i <= 4; $i++ )
	echo '<option '.($_SESSION['StandardCostDecimalPlaces'] == $i?'selected="selected" ':'').'value="'.$i.'">' . $i  . '</option>';
echo '</select></td><td>' . _('Decimal Places to be used in Standard Cost')  . '</td></tr>';

// NumberOfPeriodsOfStockUsage
echo '<tr style="outline: 1px solid"><td>' . _('Number Of Periods Of StockUsage') . ':</td>
	<td><select name="X_NumberOfPeriodsOfStockUsage">';
for ($i=1; $i <= 12; $i++ )
	echo '<option '.($_SESSION['NumberOfPeriodsOfStockUsage'] == $i?'selected="selected" ':'').'value="'.$i.'">' . $i . '</option>';
echo '</select></td><td>' . _('In stock usage inquiries this determines how many periods of stock usage to show. An average is calculated over this many periods')  . '</td></tr>';

//Show values on GRN
echo '<tr style="outline: 1px solid"><td>' . _('Show order values on GRN') . ':</td>
	<td><select name="X_ShowValueOnGRN">
	<option '.($_SESSION['ShowValueOnGRN']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['ShowValueOnGRN']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('Should the value of the purchased stock be shown on the GRN screen') . '</td>
	</tr>';

// Check_Qty_Charged_vs_Del_Qty
echo '<tr style="outline: 1px solid"><td>' . _('Check Quantity Charged vs Deliver Qty') . ':</td>
	<td><select name="X_Check_Qty_Charged_vs_Del_Qty">
	<option '.($_SESSION['Check_Qty_Charged_vs_Del_Qty']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['Check_Qty_Charged_vs_Del_Qty']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('In entry of AP invoices this determines whether or not to check the quantities received into stock tie up with the quantities invoiced')  . '</td></tr>';

// Check_Price_Charged_vs_Order_Price
echo '<tr style="outline: 1px solid"><td>' . _('Check Price Charged vs Order Price') . ':</td>
	<td><select name="X_Check_Price_Charged_vs_Order_Price">
	<option '.($_SESSION['Check_Price_Charged_vs_Order_Price']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['Check_Price_Charged_vs_Order_Price']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('In entry of AP invoices this parameter determines whether or not to check invoice prices tie up to ordered prices')  . '</td></tr>';

// OverChargeProportion
echo '<tr style="outline: 1px solid"><td>' . _('Allowed Over Charge Proportion') . ':</td>
	<td><input type="text" class="integer" pattern="(?!^0\d+$)[\d]{1,2}|(100)" required="required" title="'._('The input must between 0 and 100').'" name="X_OverChargeProportion" size="4" maxlength="3" value="' . $_SESSION['OverChargeProportion'] . '" placeholder="'._('integer between 0 and 100').'" /></td>
	<td>' . _('If check price charges vs Order price is set to yes then this proportion determines the percentage by which invoices can be overcharged with respect to price')  . '</td></tr>';

// OverReceiveProportion
echo '<tr style="outline: 1px solid"><td>' . _('Allowed Over Receive Proportion') . ':</td>
	<td><input type="text" class="integer" pattern="(?!^0\d+$)[\d]{1,2}|(100)" required="required" title="'._('The input must between 0 and 100').'" name="X_OverReceiveProportion" size="4" maxlength="3" value="' . $_SESSION['OverReceiveProportion'] . '" /></td>
	<td>' . _('If check quantity charged vs delivery quantity is set to yes then this proportion determines the percentage by which invoices can be overcharged with respect to delivery')  . '</td></tr>';

// PO_AllowSameItemMultipleTimes
echo '<tr style="outline: 1px solid"><td>' . _('Purchase Order Allows Same Item Multiple Times') . ':</td>
	<td><select name="X_PO_AllowSameItemMultipleTimes">
	<option '.($_SESSION['PO_AllowSameItemMultipleTimes']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['PO_AllowSameItemMultipleTimes']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td><td>' . _('If a purchase order can have the same item on the order several times this parameter should be set to yes') . '</td></tr>';

// AutoAuthorisePO
echo '<tr style="outline: 1px solid"><td>' . _('Automatically authorise purchase orders if user has authority') . ':</td>
	<td><select name="X_AutoAuthorisePO">
	<option '.($_SESSION['AutoAuthorisePO'] ?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['AutoAuthorisePO'] ?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td><td>' . _('If the user changing an existing purchase order or adding a new puchase order is set up to authorise purchase orders and the order is within their limit, then the purchase order status is automatically set to authorised') . '</td></tr>';


echo '<tr><th colspan="3">' . _('General Settings') . '</th></tr>';
echo $TableHeader;

// YearEnd
$MonthNames = array( 1=>_('January'),
			2=>_('February'),
			3=>_('March'),
			4=>_('April'),
			5=>_('May'),
			6=>_('June'),
			7=>_('July'),
			8=>_('August'),
			9=>_('September'),
			10=>_('October'),
			11=>_('November'),
			12=>_('December') );
echo '<tr style="outline: 1px solid"><td>' . _('Financial Year Ends On') . ':</td>
	<td><select name="X_YearEnd">';
for ($i=1; $i <= sizeof($MonthNames); $i++ )
	echo '<option '.($_SESSION['YearEnd'] == $i ? 'selected="selected" ' : '').'value="'.$i.'">' . $MonthNames[$i] . '</option>';
echo '</select></td>
	<td>' . _('Defining the month in which the financial year ends enables the system to provide useful defaults for general ledger reports')  . '</td></tr>';

//PageLength
echo '<tr style="outline: 1px solid"><td>' . _('Report Page Length') . ':</td>
	<td><input type="text" class="integer" pattern="(?!^0\d*$)[\d]{1,3}" title="'._('The input should be between 1 and 999').'" placeholder="'._('1 to 999').'" name="X_PageLength" size="4" maxlength="6" value="' . $_SESSION['PageLength'] . '" /></td><td>&nbsp;</td>
</tr>';

//DefaultDisplayRecordsMax
echo '<tr style="outline: 1px solid">
		<td>' . _('Default Maximum Number of Records to Show') . ':</td>
		<td><input type="text" class="integer" pattern="(?!^0\d*$)[\d]{1,3}" required="required" title="'._('The records should be between 1 and 999').'" name="X_DefaultDisplayRecordsMax" size="4" maxlength="3" value="' . $_SESSION['DefaultDisplayRecordsMax'] . '" /></td>
		<td>' . _('When pages have code to limit the number of returned records - such as select customer, select supplier and select item, then this will be the default number of records to show for a user who has not changed this for themselves in user settings.') . '</td>
	</tr>';

// ShowStockidOnImage
echo '<tr style="outline: 1px solid">
		<td>' . _('Show Stockid on images') . ':</td>
		<td><select name="X_ShowStockidOnImages">
			<option '.($_SESSION['ShowStockidOnImages'] ?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
			<option '.(!$_SESSION['ShowStockidOnImages'] ?'selected="selected" ':'').'value="0">' . _('No') . '</option>
			</select></td>
		<td>' . _('Show the code inside the thumbnail image of the items') . '</td>
	</tr>';


//MaxImageSize
echo '<tr style="outline: 1px solid">
		<td>' . _('Maximum Size in KB of uploaded images') . ':</td>
		<td><input type="text" class="integer" pattern="(?!^0\d*$)[\d]{1,3}" required="required" title="'._('The input should be between 1 and 999').'" placeholder="'._('1 to 999').'" name="X_MaxImageSize" size="4" maxlength="3" value="' . $_SESSION['MaxImageSize'] . '" /></td>
		<td>' . _('Picture files of items can be uploaded to the server. The system will check that files uploaded are less than this size (in KB) before they will be allowed to be uploaded. Large pictures will make the system slow and will be difficult to view in the stock maintenance screen.')  . '</td>
	</tr>';
//NumberOfMonthMustBeShown
$sql = "SELECT confvalue
		FROM `config`
		WHERE confname ='numberOfMonthMustBeShown'";

$ErrMsg = _('Could not load the Number Of Month Must be Shown');
$result = DB_query($sql,$ErrMsg);
$row = DB_fetch_array($result);
$_SESSION['NumberOfMonthMustBeShown'] = $row['confvalue'];

echo '<tr style="outline: 1px solid"><td>' . _('Number Of Month Must Be Shown') . ':</td>
		  <td><input type="text" class="integer" pattern="(?!^0\d*$)[\d]+" required="required" title="'._('input must be positive integer').'" placeholder="'._('positive integer').'" name="X_NumberOfMonthMustBeShown" size="4" maxlength="3" value="' . $_SESSION['NumberOfMonthMustBeShown'] . '" /></td>
		  <td>' . _('Number of month must be shown on report can be changed with this parameters ex: in CustomerInquiry.php ')  . '</td>
      </tr>';

//$part_pics_dir
echo '<tr style="outline: 1px solid"><td>' . _('The directory where images are stored') . ':</td>
	 <td><select name="X_part_pics_dir">';


$CompanyDirectory = 'companies/' . $_SESSION['DatabaseName'] . '/';
$DirHandle = dir($CompanyDirectory);

while ($DirEntry = $DirHandle->read() ){

	if (is_dir($CompanyDirectory . $DirEntry)
		AND $DirEntry != '..'
		AND $DirEntry!='.'
		AND $DirEntry!='.svn'
		AND $DirEntry != 'CVS'
		AND $DirEntry != 'reports'
		AND $DirEntry != 'locale'
		AND $DirEntry != 'fonts'   ){

		if ($_SESSION['part_pics_dir'] == $CompanyDirectory . $DirEntry){
			echo '<option selected="selected" value="' . $DirEntry . '">' . $DirEntry . '</option>';
		} else {
			echo '<option value="' . $DirEntry . '">' . $DirEntry  . '</option>';
		}
	}
}
echo '</select></td>
	<td>' . _('The directory under which all image files should be stored. Image files take the format of ItemCode.jpg - they must all be .jpg files and the part code will be the name of the image file. This is named automatically on upload. The system will check to ensure that the image is a .jpg file') . '</td>
	</tr>';


//$reports_dir
echo '<tr style="outline: 1px solid"><td>' . _('The directory where reports are stored') . ':</td>
	<td><select name="X_reports_dir">';

$DirHandle = dir($CompanyDirectory);

while (false != ($DirEntry = $DirHandle->read())){

	if (is_dir($CompanyDirectory . $DirEntry)
		AND $DirEntry != '..'
		AND $DirEntry != 'includes'
		AND $DirEntry!='.'
		AND $DirEntry!='.svn'
		AND $DirEntry != 'doc'
		AND $DirEntry != 'css'
		AND $DirEntry != 'CVS'
		AND $DirEntry != 'sql'
		AND $DirEntry != 'part_pics'
		AND $DirEntry != 'locale'
		AND $DirEntry != 'fonts'      ){

		if ($_SESSION['reports_dir'] == $CompanyDirectory . $DirEntry){
			echo '<option selected="selected" value="' . $DirEntry . '">' . $DirEntry . '</option>';
		} else {
			echo '<option value="' . $DirEntry . '">' . $DirEntry  . '</option>';
		}
	}
}

echo '</select></td>
	<td>' . _('The directory under which all report pdf files should be created in. A separate directory is recommended') . '</td>
	</tr>';


// HTTPS_Only
echo '<tr style="outline: 1px solid"><td>' . _('Only allow secure socket connections') . ':</td>
	<td><select name="X_HTTPS_Only">
	<option '.($_SESSION['HTTPS_Only']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
	<option '.(!$_SESSION['HTTPS_Only']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
	</select></td>
	<td>' . _('Force connections to be only over secure sockets - ie encrypted data only') . '</td>
	</tr>';

/*Perform Database maintenance DB_Maintenance*/
echo '<tr style="outline: 1px solid"><td>' . _('Perform Database Maintenance At Logon') . ':</td>
	<td><select name="X_DB_Maintenance">';
	if ($_SESSION['DB_Maintenance']=='1'){
		echo '<option selected="selected" value="1">' . _('Daily') . '</option>';
	} else {
		echo '<option value="1">' . _('Daily') . '</option>';
	}
	if ($_SESSION['DB_Maintenance']=='7'){
		echo '<option selected="selected" value="7">' . _('Weekly') . '</option>';
	} else {
		echo '<option value="7">' . _('Weekly') . '</option>';
	}
	if ($_SESSION['DB_Maintenance']=='30'){
		echo '<option selected="selected" value="30">' . _('Monthly') . '</option>';
	} else {
		echo '<option value="30">' . _('Monthly') . '</option>';
	}
	if ($_SESSION['DB_Maintenance']=='0'){
		echo '<option selected="selected" value="0">' . _('Never') . '</option>';
	} else {
		echo '<option value="0">' . _('Never') . '</option>';
	}
	if ($_SESSION['DB_Maintenance']=='-1'){
		echo '<option selected="selected" value="-1">' . _('Allow SysAdmin Access Only') . '</option>';
	} else {
		echo '<option value="-1">' . _('Allow SysAdmin Access Only') . '</option>';
	}

	echo '</select></td>
	<td>' . _('Uses the function DB_Maintenance defined in ConnectDB_XXXX.inc to perform database maintenance tasks, to run at regular intervals - checked at each and every user login') . '</td>
	</tr>';

$WikiApplications = array( _('Disabled'),
 					_('WackoWiki'),
 					_('MediaWiki'),
					_('DokuWiki') );

echo '<tr style="outline: 1px solid"><td>' . _('Wiki application') . ':</td>
	<td><select name="X_WikiApp">';
for ($i=0; $i < sizeof($WikiApplications); $i++ ) {
	echo '<option '.($_SESSION['WikiApp'] == $WikiApplications[$i] ? 'selected="selected" ' : '').'value="'.$WikiApplications[$i].'">' . $WikiApplications[$i]  . '</option>';
}
echo '</select></td>
	<td>' . _('This feature makes webERP show links to a free form company knowledge base using a wiki. This allows sharing of important company information - about customers, suppliers and products and the set up of work flow menus and/or company procedures documentation')  . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Wiki Path') . ':</td>
	<td><input type="text" name="X_WikiPath" size="40" maxlength="40" value="' . $_SESSION['WikiPath'] . '" /></td>
	<td>' . _('The path to the wiki installation to form the basis of wiki URLs - or the full URL of the wiki.')  . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Geocode Customers and Suppliers') . ':</td>
        <td><select name="X_geocode_integration">';
if ($_SESSION['geocode_integration']==1){
        echo  '<option selected="selected" value="1">' . _('Geocode Integration Enabled') . '</option>';
        echo  '<option value="0">' . _('Geocode Integration Disabled') . '</option>';
} else {
        echo  '<option selected="selected" value="0">' . _('Geocode Integration Disabled') . '</option>';
        echo  '<option value="1">' . _('Geocode Integration Enabled') . '</option>';
}
echo '</select></td>
        <td>' . _('This feature will give Latitude and Longitude coordinates to customers and suppliers. Requires access to a mapping provider. You must setup this facility under Main Menu - Setup - Geocode Setup. This feature is experimental.')  . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Extended Customer Information') . ':</td>
        <td><select name="X_Extended_CustomerInfo">';
if ($_SESSION['Extended_CustomerInfo']==1){
        echo  '<option selected="selected" value="1">' . _('Extended Customer Info Enabled') . '</option>';
        echo  '<option value="0">' . _('Extended Customer Info Disabled') . '</option>';
} else {
        echo  '<option selected="selected" value="0">' . _('Extended Customer Info Disabled') . '</option>';
        echo  '<option value="1">' . _('Extended Customer Info Enabled') . '</option>';
}
echo '</select></td>
        <td>' . _('This feature will give extended information in the Select Customer screen.')  . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Extended Supplier Information') . ':</td>
        <td><select name="X_Extended_SupplierInfo">';
if ($_SESSION['Extended_SupplierInfo']==1){
        echo  '<option selected="selected" value="1">' . _('Extended Supplier Info Enabled') . '</option>';
        echo  '<option value="0">' . _('Extended Supplier Info Disabled') . '</option>';
} else {
        echo  '<option selected="selected" value="0">' . _('Extended Supplier Info Disabled') . '</option>';
        echo  '<option value="1">' . _('Extended Supplier Info Enabled') . '</option>';
}
echo '</select></td>
        <td>' . _('This feature will give extended information in the Select Supplier screen.')  . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Prohibit GL Journals to Control Accounts') . ':</td>
	<td><select name="X_ProhibitJournalsToControlAccounts">';
if ($_SESSION['ProhibitJournalsToControlAccounts']=='1'){
		echo  '<option selected="selected" value="1">' . _('Prohibited') . '</option>';
		echo  '<option value="0">' . _('Allowed') . '</option>';
} else {
		echo  '<option value="1">' . _('Prohibited') . '</option>';
		echo  '<option selected="selected" value="0">' . _('Allowed') . '</option>';
}
echo '</select></td><td>' . _('Setting this to prohibited prevents accidentally entering a journal to the automatically posted and reconciled control accounts for creditors (AP) and debtors (AR)') . '</td></tr>';


echo '<tr style="outline: 1px solid">
		<td>' . _('Prohibit GL Journals to Periods Prior To') . ':</td>
		<td><select name="X_ProhibitPostingsBefore">';

$sql = "SELECT lastdate_in_period FROM periods ORDER BY periodno DESC";
$ErrMsg = _('Could not load periods table');
$result = DB_query($sql,$ErrMsg);
if ($_SESSION['ProhibitPostingsBefore']=='' OR $_SESSION['ProhibitPostingsBefore']=='1900-01-01' OR !isset($_SESSION['ProhibitPostingsBefore'])){
	echo '<option selected="selected" value="1900-01-01">' . ConvertSQLDate('1900-01-01') . '</option>';
}
while ($PeriodRow = DB_fetch_row($result)){
	if ($_SESSION['ProhibitPostingsBefore']==$PeriodRow[0]){
		echo  '<option selected="selected" value="' . $PeriodRow[0] . '">' . ConvertSQLDate($PeriodRow[0]) . '</option>';
	} else {
		echo  '<option value="' . $PeriodRow[0] . '">' . ConvertSQLDate($PeriodRow[0]) . '</option>';
	}
}
echo '</select></td>
	<td>' . _('This allows all periods before the selected date to be locked from postings. All postings for transactions dated prior to this date will be posted in the period following this date.') . '</td>
	</tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Inventory Costing Method') . ':</td>
	<td><select name="X_WeightedAverageCosting">';

if ($_SESSION['WeightedAverageCosting']==1){
	echo  '<option selected="selected" value="1">' . _('Weighted Average Costing') . '</option>';
	echo  '<option value="0">' . _('Standard Costing') . '</option>';
} else {
	echo  '<option selected="selected" value="0">' . _('Standard Costing') . '</option>';
	echo  '<option value="1">' . _('Weighted Average Costing') . '</option>';
}

echo '</select></td><td>' . _('webERP allows inventory to be costed based on the weighted average of items in stock or full standard costing with price variances reported. The selection here determines the method used and the general ledger postings resulting from purchase invoices and shipment closing') . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Auto Issue Components') . ':</td>
		<td>
		<select name="X_AutoIssue">';
if ($_SESSION['AutoIssue']==0) {
	echo '<option selected="selected" value="0">' . _('No') . '</option>';
	echo '<option value="1">' . _('Yes') . '</option>';
} else {
	echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
	echo '<option value="0">' . _('No') . '</option>';
	}
echo '</select></td><td>' . _('When items are manufactured it is possible for the components of the item to be automatically decremented from stock in accordance with the Bill of Material setting') . '</td></tr>' ;

echo '<tr style="outline: 1px solid"><td>' . _('Prohibit Negative Stock') . ':</td>
		<td>
		<select name="X_ProhibitNegativeStock">';
if ($_SESSION['ProhibitNegativeStock']==0) {
	echo '<option selected="selected" value="0">' . _('No') . '</option>';
	echo '<option value="1">' . _('Yes') . '</option>';
} else {
	echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
	echo '<option value="0">' . _('No') . '</option>';
}
echo '</select></td><td>' . _('Setting this parameter to Yes prevents invoicing and the issue of stock if this would result in negative stock. The stock problem must be corrected before the invoice or issue is allowed to be processed.') . '</td></tr>' ;

//Months of Audit Trail to Keep
echo '<tr style="outline: 1px solid"><td>' . _('Months of Audit Trail to Retain') . ':</td>
	<td><input type="text" class="integer" pattern="(?!^0\d+$)[\d]{1,2}" required="required" name="X_MonthsAuditTrail" size="3" maxlength="2" value="' . $_SESSION['MonthsAuditTrail'] . '" /></td><td>' . _('If this parameter is set to 0 (zero) then no audit trail is retained. An audit trail is a log of which users performed which additions updates and deletes of database records. The full SQL is retained') . '</td>
</tr>';

//Which messages to log
echo '<tr style="outline: 1px solid"><td>' . _('Log Severity Level') . ':</td><td><select name="X_LogSeverity" >';
if ($_SESSION['LogSeverity']==0) {
	echo '<option selected="selected" value="0">' ._('None'). '</option>';
	echo '<option value="1">' ._('Errors Only'). '</option>';
	echo '<option value="2">' ._('Errors and Warnings'). '</option>';
	echo '<option value="3">' ._('Errors, Warnings and Info'). '</option>';
	echo '<option value="4">' ._('All'). '</option>';
} else if ($_SESSION['LogSeverity']==1) {
	echo '<option value="0">' ._('None'). '</option>';
	echo '<option selected="selected" value="1">' ._('Errors Only'). '</option>';
	echo '<option value="2">' ._('Errors and Warnings'). '</option>';
	echo '<option value="3">' ._('Errors, Warnings and Info'). '</option>';
	echo '<option value="4">' ._('All'). '</option>';
} else if ($_SESSION['LogSeverity']==2) {
	echo '<option value="0">' ._('None'). '</option>';
	echo '<option value="1">' ._('Errors Only'). '</option>';
	echo '<option selected="selected" value="2">' ._('Errors and Warnings'). '</option>';
	echo '<option value="3">' ._('Errors, Warnings and Info'). '</option>';
	echo '<option value="4">' ._('All'). '</option>';
} else if ($_SESSION['LogSeverity']==3) {
	echo '<option value="0">' ._('None'). '</option>';
	echo '<option value="1">' ._('Errors Only'). '</option>';
	echo '<option value="2">' ._('Errors and Warnings'). '</option>';
	echo '<option selected="selected" value="3">' ._('Errors, Warnings and Info'). '</option>';
	echo '<option value="4">' ._('All'). '</option>';
} else if ($_SESSION['LogSeverity']==4) {
	echo '<option value="0">' ._('None'). '</option>';
	echo '<option value="1">' ._('Errors Only'). '</option>';
	echo '<option value="2">' ._('Errors andWarnings'). '</option>';
	echo '<option value="3">' ._('Errors, Warnings and Info'). '</option>';
	echo '<option selected="selected" value="4">' ._('All'). '</option>';
}
echo '</select></td>';
echo '<td>' . _('Choose which Status messages to keep in your log file.') . '</td></tr>';

//Path to keep log files in
echo '<tr style="outline: 1px solid"><td>' . _('Path to log files') . ':</td>
	<td><input type="text" name="X_LogPath" size="40" maxlength="79" value="' . $_SESSION['LogPath'] . '" /></td><td>' . _('The path to the directory where the log files will be stored. Note the apache user must have write permissions on this directory.') . '</td>
</tr>';

//DefineControlledOnWOEntry
echo '<tr style="outline: 1px solid"><td>' . _('Controlled Items Defined At Work Order Entry') . ':</td>
	<td><select name="X_DefineControlledOnWOEntry">
		<option '.($_SESSION['DefineControlledOnWOEntry']?'selected="selected" ':'').'value="1">' . _('Yes') . '</option>
		<option '.(!$_SESSION['DefineControlledOnWOEntry']?'selected="selected" ':'').'value="0">' . _('No') . '</option>
		</select></td>
	<td>' . _('When set to yes, controlled items are defined at the time of the work order creation. Otherwise controlled items (serial numbers and batch/roll/lot references) are entered at the time the finished items are received against the work order') . '</td></tr>';

//AutoCreateWOs
echo '<tr style="outline: 1px solid"><td>' . _('Auto Create Work Orders') . ':</td>
		<td>
		<select name="X_AutoCreateWOs">';

if ($_SESSION['AutoCreateWOs']==0) {
	echo '<option selected="selected" value="0">' . _('No') . '</option>';
	echo '<option value="1">' . _('Yes') . '</option>';
} else {
	echo '<option selected="selected" value="1">' . _('Yes') . '</option>';
	echo '<option value="0">' . _('No') . '</option>';
}
echo '</select></td><td>' . _('Setting this parameter to Yes will ensure that when a sales order is placed if there is insufficient stock then a new work order is created at the default factory location') . '</td></tr>' ;

echo '<tr style="outline: 1px solid"><td>' . _('Default Factory Location') . ':</td>
	<td><select name="X_DefaultFactoryLocation">';

$sql = "SELECT loccode,locationname FROM locations";
$ErrMsg = _('Could not load locations table');
$result = DB_query($sql,$ErrMsg);
while ($LocationRow = DB_fetch_array($result)){
	if ($_SESSION['DefaultFactoryLocation']==$LocationRow['loccode']){
		echo  '<option selected="selected" value="' . $LocationRow['loccode'] . '">' . $LocationRow['locationname'] . '</option>';
	} else {
		echo  '<option value="' .  $LocationRow['loccode'] . '">' . $LocationRow['locationname'] . '</option>';
	}
}
echo '</select></td><td>' . _('This location is the location where work orders will be created from when the auto create work orders option is activated') . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Factory Manager Email Address') . ':</td>
	<td><input type="email" name="X_FactoryManagerEmail" size="50" maxlength="50" value="' . $_SESSION['FactoryManagerEmail'] . '" /></td>
	<td>' . _('Work orders automatically created when sales orders are entered will be emailed to this address')  . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Purchasing Manager Email Address') . ':</td>
	<td><input type="email" name="X_PurchasingManagerEmail" size="50" maxlength="50" value="' . $_SESSION['PurchasingManagerEmail'] . '" /></td>
	<td>' . _('The email address for the purchasing manager, used to receive notifications by the tendering system')  . '</td></tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Inventory Manager Email Address') . ':</td>
	<td><input type="email" name="X_InventoryManagerEmail" size="50" maxlength="50" value="' . $_SESSION['InventoryManagerEmail'] . '" /></td>
	<td>' . _('The email address for the inventory manager, where notifications of all manual stock adjustments created are sent by the system. Leave blank if no emails should be sent to the factory manager for manual stock adjustments')  . '</td></tr>';

echo '<tr style="outline: 1px solid">
	<td>' . _('Using Smtp Mail'). '</td>
	<td>
		<select type="text" name="X_SmtpSetting" >';
		if ($_SESSION['SmtpSetting'] == 0){
			echo '<option select="selected" value="0">' . _('No') . '</option>';
			echo '<option value="1">' . _('Yes') . '</option>';
		} elseif ($_SESSION['SmtpSetting'] == 1){
			echo '<option select="selected" value="1">' . _('Yes') . '</option>';
			echo '<option value="0">' . _('No') . '</option>';
		}

echo '		</select>
         </td>
	 <td>' .  _('The default setting is using mail in default php.ini, if you choose Yes for this selection, you can use the SMTP set in the setup section.').'
	 </td>
     </tr>';

echo '<tr style="outline: 1px solid"><td>' . _('Text for Quality Product Specification') . ':</td>
	<td><textarea name="X_QualityProdSpecText" rows="3" cols="40">' . $_SESSION['QualityProdSpecText'] . '</textarea></td>
	<td>' . _('This text appears on product specifications') . '</td></tr>';
echo '<tr style="outline: 1px solid"><td>' . _('Text for Quality Product Certifications') . ':</td>
	<td><textarea name="X_QualityCOAText" rows="3" cols="40">' . $_SESSION['QualityCOAText'] . '</textarea></td>
	<td>' . _('This text appears on product certifications') . '</td></tr>';
echo '<tr style="outline: 1px solid">
	<td>' . _('Auto Log Quality Samples'). '</td>
	<td>
		<select type="text" name="X_QualityLogSamples" >';
		if ($_SESSION['QualityLogSamples'] == 0){
			echo '<option select="selected" value="0">' . _('No') . '</option>';
			echo '<option value="1">' . _('Yes') . '</option>';
		} elseif ($_SESSION['QualityLogSamples'] == 1){
			echo '<option select="selected" value="1">' . _('Yes') . '</option>';
			echo '<option value="0">' . _('No') . '</option>';
		}

echo '</select>
         </td>
	 <td>' .  _('The flag determines if the system creates quality samples automatically for each lot during P/O Receipt and W/O Receipt transactions.').'
	 </td>
	</tr>
	<tr style="outline: 1px solid">
	<td>' . _('Allow use of short-cut menus'). '</td>
	<td>
		<select type="text" name="X_ShortcutMenu" >';
		if ($_SESSION['ShortcutMenu'] == 0){
			echo '<option select="selected" value="0">' . _('No') . '</option>';
			echo '<option value="1">' . _('Yes') . '</option>';
		} elseif ($_SESSION['ShortcutMenu'] == 1){
			echo '<option select="selected" value="1">' . _('Yes') . '</option>';
			echo '<option value="0">' . _('No') . '</option>';
		}

echo '</select>
         </td>
	 <td>' .  _('The flag determines if the system allows users to create the javascript short cut menu - this can cause confusion to some users with some themes.').'
	 </td>
	</tr>

	<tr style="outline: 1px solid">
	<td>' . _('Last day of the week'). '</td>
	<td>
		<select type="text" name="X_LastDayOfWeek" >
			<option ' . ($_SESSION['LastDayOfWeek'] == 0 ?'selected="selected"':'') . ' value="0">' . _('Sunday') . '</option>
			<option ' . ($_SESSION['LastDayOfWeek'] == 1 ?'selected="selected"':'') . ' value="1">' . _('Monday') . '</option>
			<option ' . ($_SESSION['LastDayOfWeek'] == 2 ?'selected="selected"':'') . ' value="2">' . _('Tuesday') . '</option>
			<option ' . ($_SESSION['LastDayOfWeek'] == 3 ?'selected="selected"':'') . ' value="3">' . _('Wednesday') . '</option>
			<option ' . ($_SESSION['LastDayOfWeek'] == 4 ?'selected="selected"':'') . ' value="4">' . _('Thursday') . '</option>
			<option ' . ($_SESSION['LastDayOfWeek'] == 5 ?'selected="selected"':'') . ' value="5">' . _('Friday') . '</option>
			<option ' . ($_SESSION['LastDayOfWeek'] == 6 ?'selected="selected"':'') . ' value="6">' . _('Saturday') . '</option>
	</select>
         </td>
	 <td>' .  _('Timesheet entry default to weeks ending on this day').'
	 </td>
	</tr>


	</table>
		<br /><div class="centre"><input type="submit" name="submit" value="' . _('Update') . '" /></div>
    </div>
	</form>';

include('includes/footer.php');
?>

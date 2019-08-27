<?php
/* webERP menus with Captions and URLs. */

$ModuleLink = array('Sales', 'AR', 'PO', 'AP', 'stock', 'manuf', 'GL', 'FA', 'PC', 'system', 'Utilities');
$ReportList = array('Sales' => 'ord', 'AR' => 'ar', 'PO' => 'prch', 'AP' => 'ap', 'stock' => 'inv', 'manuf' => 'man', 'GL' => 'gl', 'FA' => 'fa', 'PC' => 'pc', 'system' => 'sys', 'Utilities' => 'utils');

/*The headings showing on the tabs across the main index used also in WWW_Users for defining what should be visible to the user */
$ModuleList = array(_('Sales'), _('Receivables'), _('Purchases'), _('Payables'), _('Inventory'), _('Manufacturing'), _('General Ledger'), _('Asset Manager'), _('Petty Cash'), _('Setup'), _('Utilities'));

$MenuItems['Sales']['Transactions']['Caption'] = array(_('New Sales Order or Quotation'), _('Enter Counter Sales'), _('Enter Counter Returns'), _('Generate/Print Picking Lists'), _('Outstanding Sales Orders/Quotations'), _('Special Order'), _('Recurring Order Template'), _('Process Recurring Orders'), _('Maintain Picking Lists'));
$MenuItems['Sales']['Transactions']['URL'] = array('/SelectOrderItems.php?NewOrder=Yes', '/CounterSales.php', '/CounterReturns.php', '/GeneratePickingList.php', '/SelectSalesOrder.php', '/SpecialOrder.php', '/SelectRecurringSalesOrder.php', '/RecurringSalesOrdersProcess.php', '/SelectPickingLists.php');

$MenuItems['Sales']['Reports']['Caption'] = array(_('Sales Order Inquiry'), _('Print Price Lists'), _('Order Status Report'), _('Orders Invoiced Reports'), _('Daily Sales Inquiry'), _('Sales By Sales Type Inquiry'), _('Sales By Category Inquiry'), _('Sales By Category By Item Inquiry'), _('Sales Analysis Reports'), _('Sales Graphs'), _('Top Sellers Inquiry'), _('Order Delivery Differences Report'), _('Delivery In Full On Time (DIFOT) Report'), _('Sales Order Detail Or Summary Inquiries'), _('Top Sales Items Inquiry'), _('Top Customers Inquiry'), _('Worst Sales Items Report'), _('Sales With Low Gross Profit Report'), _('Sell Through Support Claims Report'), _('Sales to Customers'));
$MenuItems['Sales']['Reports']['URL'] = array('/SelectCompletedOrder.php', '/PDFPriceList.php', '/PDFOrderStatus.php', '/PDFOrdersInvoiced.php', '/DailySalesInquiry.php', '/SalesByTypePeriodInquiry.php', '/SalesCategoryPeriodInquiry.php', '/StockCategorySalesInquiry.php', '/SalesAnalRepts.php', '/SalesGraph.php', '/SalesTopItemsInquiry.php', '/PDFDeliveryDifferences.php', '/PDFDIFOT.php', '/SalesInquiry.php', '/TopItems.php', '/SalesTopCustomersInquiry.php', '/NoSalesItems.php', '/PDFLowGP.php', '/PDFSellThroughSupportClaim.php', '/SalesReport.php');

$MenuItems['Sales']['Maintenance']['Caption'] = array(_('Create Contract'), _('Select Contract'), _('Sell Through Support Deals'));
$MenuItems['Sales']['Maintenance']['URL'] = array('/Contracts.php', '/SelectContract.php', '/SellThroughSupport.php');

$MenuItems['AR']['Transactions']['Caption'] = array(_('Select Order to Invoice'), _('Create A Credit Note'), _('Enter Receipts'), _('Allocate Receipts or Credit Notes'));
$MenuItems['AR']['Transactions']['URL'] = array('/SelectSalesOrder.php', '/SelectCreditItems.php?NewCredit=Yes', '/CustomerReceipt.php?NewReceipt=Yes&amp;Type=Customer', '/CustomerAllocations.php');

$MenuItems['AR']['Reports']['Caption'] = array(_('Where Allocated Inquiry'), _('Print Invoices or Credit Notes'), _('Print Statements'), _('Aged Customer Balances/Overdues Report'), _('Re-Print A Deposit Listing'), _('Debtor Balances At A Prior Month End'), _('Customer Listing By Area/Salesperson'), _('List Daily Transactions'), _('Customer Transaction Inquiries'), _('Customer Activity and Balances'));

if ($_SESSION['InvoicePortraitFormat'] == 0) {
	$PrintInvoicesOrCreditNotesScript = '/PrintCustTrans.php';
} else {
	$PrintInvoicesOrCreditNotesScript = '/PrintCustTransPortrait.php';
}

$MenuItems['AR']['Reports']['URL'] = array('/CustWhereAlloc.php', $PrintInvoicesOrCreditNotesScript, '/PrintCustStatements.php', '/AgedDebtors.php', '/PDFBankingSummary.php', '/DebtorsAtPeriodEnd.php', '/PDFCustomerList.php', '/PDFCustTransListing.php', '/CustomerTransInquiry.php', '/CustomerBalancesMovement.php');

$MenuItems['AR']['Maintenance']['Caption'] = array(_('Add Customer'), _('Select Customer'));
$MenuItems['AR']['Maintenance']['URL'] = array('/Customers.php', '/SelectCustomer.php');

$MenuItems['AP']['Transactions']['Caption'] = array(_('Select Supplier'), _('Supplier Allocations'));
$MenuItems['AP']['Transactions']['URL'] = array('/SelectSupplier.php', '/SupplierAllocations.php');

$MenuItems['AP']['Reports']['Caption'] = array(_('Where Allocated Inquiry'), _('Aged Supplier Report'), _('Payment Run Report'), _('Remittance Advices'), _('Outstanding GRNs Report'), _('Supplier Balances At A Prior Month End'), _('List Daily Transactions'), _('Supplier Transaction Inquiries'));
$MenuItems['AP']['Reports']['URL'] = array('/SuppWhereAlloc.php', '/AgedSuppliers.php', '/SuppPaymentRun.php', '/PDFRemittanceAdvice.php', '/OutstandingGRNs.php', '/SupplierBalsAtPeriodEnd.php', '/PDFSuppTransListing.php', '/SupplierTransInquiry.php');

$MenuItems['AP']['Maintenance']['Caption'] = array(_('Add Supplier'), _('Select Supplier'), _('Maintain Factor Companies'));
$MenuItems['AP']['Maintenance']['URL'] = array('/Suppliers.php', '/SelectSupplier.php', '/Factors.php');

$MenuItems['PO']['Transactions']['Caption'] = array(_('New Purchase Order'), _('Purchase Orders'), _('Purchase Order Grid Entry'), _('Create a New Tender'), _('Edit Existing Tenders'), _('Process Tenders and Offers'), _('Orders to Authorise'), _('Shipment Entry'), _('Select A Shipment'));
$MenuItems['PO']['Transactions']['URL'] = array('/PO_Header.php?NewOrder=Yes', '/PO_SelectOSPurchOrder.php', '/PurchaseByPrefSupplier.php', '/SupplierTenderCreate.php?New=Yes', '/SupplierTenderCreate.php?Edit=Yes', '/OffersReceived.php', '/PO_AuthoriseMyOrders.php', '/SelectSupplier.php', '/Shipt_Select.php');

$MenuItems['PO']['Reports']['Caption'] = array(_('Purchase Order Inquiry'), _('Purchase Order Detail Or Summary Inquiries'), _('Supplier Price List'), _('Purchases from Suppliers'));
$MenuItems['PO']['Reports']['URL'] = array('/PO_SelectPurchOrder.php', '/POReport.php', '/SuppPriceList.php', '/PurchasesReport.php');

$MenuItems['PO']['Maintenance']['Caption'] = array(_('Maintain Supplier Price Lists'));
$MenuItems['PO']['Maintenance']['URL'] = array('/SupplierPriceList.php');

$MenuItems['stock']['Transactions']['Caption'] = array(_('Receive Purchase Orders'), _('Inventory Location Transfers'), //"Inventory Transfer - Item Dispatch"
_('Bulk Inventory Transfer') . ' - ' . _('Dispatch'), //"Inventory Transfer - Bulk Dispatch"
_('Bulk Inventory Transfer') . ' - ' . _('Receive'), //"Inventory Transfer - Receive"
_('Inventory Adjustments'), _('Reverse Goods Received'), _('Enter Stock Counts'), _('Create a New Internal Stock Request'), _('Authorise Internal Stock Requests'), _('Fulfill Internal Stock Requests'));
$MenuItems['stock']['Transactions']['URL'] = array('/PO_SelectOSPurchOrder.php', '/StockTransfers.php?New=Yes', '/StockLocTransfer.php', '/StockLocTransferReceive.php', '/StockAdjustments.php?NewAdjustment=Yes', '/ReverseGRN.php', '/StockCounts.php', '/InternalStockRequest.php?New=Yes', '/InternalStockRequestAuthorisation.php', '/InternalStockRequestFulfill.php');

$MenuItems['stock']['Reports']['Caption'] = array(_('Serial Item Research Tool'), _('Print Price Labels'), _('Reprint GRN'), _('Inventory Item Movements'), _('Inventory Item Status'), _('Inventory Item Usage'), _('Inventory Quantities'), _('Reorder Level'), _('Stock Dispatch'), _('Inventory Valuation Report'), _('Mail Inventory Valuation Report'), _('Inventory Planning Report'), _('Inventory Planning Based On Preferred Supplier Data'), _('Inventory Stock Check Sheets'), _('Make Inventory Quantities CSV'), _('Compare Counts Vs Stock Check Data'), _('All Inventory Movements By Location/Date'), _('List Inventory Status By Location/Category'), _('Historical Stock Quantity By Location/Category'), _('List Negative Stocks'), _('Period Stock Transaction Listing'), _('Stock Transfer Note'), _('Aged Controlled Stock Report'), _('Internal stock request inquiry'));
$MenuItems['stock']['Reports']['URL'] = array('/StockSerialItemResearch.php', '/PDFPrintLabel.php', '/ReprintGRN.php', '/StockMovements.php', '/StockStatus.php', '/StockUsage.php', '/InventoryQuantities.php', '/ReorderLevel.php', '/StockDispatch.php', '/InventoryValuation.php', '/MailInventoryValuation.php', '/InventoryPlanning.php', '/InventoryPlanningPrefSupplier.php', '/StockCheck.php', '/StockQties_csv.php', '/PDFStockCheckComparison.php', '/StockLocMovements.php', '/StockLocStatus.php', '/StockQuantityByDate.php', '/PDFStockNegatives.php', '/PDFPeriodStockTransListing.php', '/PDFStockTransfer.php', '/AgedControlledInventory.php', '/InternalStockRequestInquiry.php');

$MenuItems['stock']['Maintenance']['Caption'] = array(_('Add A New Item'), _('Select An Item'), _('Review Translated Descriptions'), _('Sales Category Maintenance'), _('Brands Maintenance'), _('Add or Update Prices Based On Costs'), _('View or Update Prices Based On Costs'), _('Reorder Level By Category/Location'));
$MenuItems['stock']['Maintenance']['URL'] = array('/Stocks.php', '/SelectProduct.php', '/RevisionTranslations.php', '/SalesCategories.php', '/Manufacturers.php', '/PricesBasedOnMarkUp.php', '/PricesByCost.php', '/ReorderLevelLocation.php');

$MenuItems['manuf']['Transactions']['Caption'] = array(_('Work Order Entry'), _('Select A Work Order'), _('QA Samples and Test Results'), _('Timesheet Entry'));
$MenuItems['manuf']['Transactions']['URL'] = array('/WorkOrderEntry.php', '/SelectWorkOrder.php', '/SelectQASamples.php', '/Timesheets.php');

$MenuItems['manuf']['Reports']['Caption'] = array(_('Select A Work Order'), _('Costed Bill Of Material Inquiry'), _('Where Used Inquiry'), _('Bill Of Material Listing'), _('Indented Bill Of Material Listing'), _('List Components Required'), _('List Materials Not Used Anywhere'), _('Indented Where Used Listing'), _('WO Items ready to produce'), _('MRP'), _('MRP Shortages'), _('MRP Suggested Purchase Orders'), _('MRP Suggested Work Orders'), _('MRP Reschedules Required'), _('Print Product Specification'), _('Print Certificate of Analysis'), _('Historical QA Test Results'), _('Multiple Work Orders Total Cost Inquiry'));
$MenuItems['manuf']['Reports']['URL'] = array('/SelectWorkOrder.php', '/BOMInquiry.php', '/WhereUsedInquiry.php', '/BOMListing.php', '/BOMIndented.php', '/BOMExtendedQty.php', '/MaterialsNotUsed.php', '/BOMIndentedReverse.php', '/WOCanBeProducedNow.php', '/MRPReport.php', '/MRPShortages.php', '/MRPPlannedPurchaseOrders.php', '/MRPPlannedWorkOrders.php', '/MRPReschedules.php', '/PDFProdSpec.php', '/PDFCOA.php', '/HistoricalTestResults.php', '/CollectiveWorkOrderCost.php');

$MenuItems['manuf']['Maintenance']['Caption'] = array(_('Work Centre'), _('Bills Of Material'), _('Copy a Bill Of Materials Between Items'), _('Master Schedule'), _('Auto Create Master Schedule'), _('MRP Calculation'), _('Quality Tests Maintenance'), _('Product Specifications'), _('Employees'));
$MenuItems['manuf']['Maintenance']['URL'] = array('/WorkCentres.php', '/BOMs.php', '/CopyBOM.php', '/MRPDemands.php', '/MRPCreateDemands.php', '/MRP.php', '/QATests.php', '/ProductSpecs.php', '/Employees.php');

$MenuItems['GL']['Transactions']['Caption'] = array(_('Bank Account Payments Entry'), _('Bank Account Receipts Entry'), _('Import Bank Transactions'), _('Bank Account Payments Matching'), _('Bank Account Receipts Matching'), _('Journal Entry'));
$MenuItems['GL']['Transactions']['URL'] = array('/Payments.php?NewPayment=Yes', '/CustomerReceipt.php?NewReceipt=Yes&amp;Type=GL', '/ImportBankTrans.php', '/BankMatching.php?Type=Payments', '/BankMatching.php?Type=Receipts', '/GLJournal.php?NewJournal=Yes');

$MenuItems['GL']['Reports']['Caption'] = array(_('Bank Account Balances'), _('Bank Account Reconciliation Statement'), _('Cheque Payments Listing'), _('Daily Bank Transactions'), _('Account Inquiry'), _('Graph of Account Transactions'), _('Account Listing'), _('Account Listing to CSV File'), _('General Ledger Journal Inquiry'), _('Trial Balance'), _('Balance Sheet'), _('Profit and Loss Statement'), _('Statement of Cash Flows'), _('Financial Statements'), _('Horizontal Analysis of Statement of Financial Position'), _('Horizontal Analysis of Statement of Comprehensive Income'), _('Tag Reports'), _('Tax Reports'));
$MenuItems['GL']['Reports']['URL'] = array('/BankAccountBalances.php', '/BankReconciliation.php', '/PDFChequeListing.php', '/DailyBankTransactions.php', '/SelectGLAccount.php', '/GLAccountGraph.php', '/GLAccountReport.php', '/GLAccountCSV.php', '/GLJournalInquiry.php', '/GLTrialBalance.php', '/GLBalanceSheet.php', '/GLProfit_Loss.php', '/GLCashFlowsIndirect.php', '/GLStatements.php', '/AnalysisHorizontalPosition.php', '/AnalysisHorizontalIncome.php', '/GLTagProfit_Loss.php', '/Tax.php');

$MenuItems['GL']['Maintenance']['Caption'] = array(_('Account Sections'), _('Account Groups'), _('GL Accounts'), _('GL Account Authorised Users'), _('User Authorised GL Accounts'), _('GL Budgets'), _('GL Tags'), _('Bank Accounts'), _('Bank Account Authorised Users'), _('User Authorised Bank Accounts'), _('Maintain Journal Templates'));
$MenuItems['GL']['Maintenance']['URL'] = array('/AccountSections.php', '/AccountGroups.php', '/GLAccounts.php', '/GLAccountUsers.php', '/UserGLAccounts.php', '/GLBudgets.php', '/GLTags.php', '/BankAccounts.php', '/BankAccountUsers.php', '/UserBankAccounts.php', '/GLJournalTemplates.php');

$MenuItems['FA']['Transactions']['Caption'] = array(_('Add a new Asset'), _('Select an Asset'), _('Change Asset Location'), _('Depreciation Journal'));
$MenuItems['FA']['Transactions']['URL'] = array('/FixedAssetItems.php', '/SelectAsset.php', '/FixedAssetTransfer.php', '/FixedAssetDepreciation.php');

$MenuItems['FA']['Reports']['Caption'] = array(_('Asset Register'), _('My Maintenance Schedule'), _('Maintenance Reminder Emails'));
$MenuItems['FA']['Reports']['URL'] = array('/FixedAssetRegister.php', '/MaintenanceUserSchedule.php', '/MaintenanceReminders.php');

$MenuItems['FA']['Maintenance']['Caption'] = array(_('Fixed Asset Category Maintenance'), _('Add or Maintain Asset Locations'), _('Fixed Asset Maintenance Tasks'));
$MenuItems['FA']['Maintenance']['URL'] = array('/FixedAssetCategories.php', '/FixedAssetLocations.php', '/MaintenanceTasks.php');

$MenuItems['PC']['Transactions']['Caption'] = array(_('Assign Cash to PC Tab'), _('Transfer Assigned Cash Between PC Tabs'), _('Claim Expenses From PC Tab'), _('Authorise Expenses'), _('Authorise Assigned Cash'));
$MenuItems['PC']['Transactions']['URL'] = array('/PcAssignCashToTab.php', '/PcAssignCashTabToTab.php', '/PcClaimExpensesFromTab.php', '/PcAuthorizeExpenses.php', '/PcAuthorizeCash.php');

$MenuItems['PC']['Reports']['Caption'] = array(_('PC Tab General Report'), _('PC Expense General Report'), _('PC Tab Expenses List'), _('PC Expenses Analysis'));
$MenuItems['PC']['Reports']['URL'] = array('/PcReportTab.php', '/PcReportExpense.php', '/PcTabExpensesList.php', '/PcAnalysis.php');

$MenuItems['PC']['Maintenance']['Caption'] = array(_('Types of PC Tabs'), _('PC Tabs'), _('PC Expenses'), _('Expenses for Type of PC Tab'));
$MenuItems['PC']['Maintenance']['URL'] = array('/PcTypeTabs.php', '/PcTabs.php', '/PcExpenses.php', '/PcExpensesTypeTab.php');

$MenuItems['system']['Transactions']['Caption'] = array(_('Company Preferences'), _('System Parameters'), _('Users Maintenance'), _('Maintain Security Tokens'), _('Access Permissions Maintenance'), _('Page Security Settings'), _('Currencies Maintenance'), _('Tax Authorities and Rates Maintenance'), _('Tax Group Maintenance'), _('Dispatch Tax Province Maintenance'), _('Tax Category Maintenance'), _('List Periods Defined'), _('Report Builder Tool'), _('View Audit Trail'), _('Geocode Maintenance'), _('Form Designer'), _('Web-Store Configuration'), _('SMTP Server Details'), _('Mailing Group Maintenance'));
$MenuItems['system']['Transactions']['URL'] = array('/CompanyPreferences.php', '/SystemParameters.php', '/WWW_Users.php', '/SecurityTokens.php', '/WWW_Access.php', '/PageSecurity.php', '/Currencies.php', '/TaxAuthorities.php', '/TaxGroups.php', '/TaxProvinces.php', '/TaxCategories.php', '/PeriodsInquiry.php', '/reportwriter/admin/ReportCreator.php', '/AuditTrail.php', '/GeocodeSetup.php', '/FormDesigner.php', '/ShopParameters.php', '/SMTPServer.php', '/MailingGroupMaintenance.php');

$MenuItems['system']['Reports']['Caption'] = array(_('Sales Types'), _('Customer Types'), _('Supplier Types'), _('Credit Status'), _('Payment Terms'), _('Set Purchase Order Authorisation levels'), _('Payment Methods'), _('Sales People'), _('Sales Areas'), _('Shippers'), _('Sales GL Interface Postings'), _('COGS GL Interface Postings'), _('Freight Costs Maintenance'), _('Discount Matrix'));
$MenuItems['system']['Reports']['URL'] = array('/SalesTypes.php', '/CustomerTypes.php', '/SupplierTypes.php', '/CreditStatus.php', '/PaymentTerms.php', '/PO_AuthorisationLevels.php', '/PaymentMethods.php', '/SalesPeople.php', '/Areas.php', '/Shippers.php', '/SalesGLPostings.php', '/COGSGLPostings.php', '/FreightCosts.php', '/DiscountMatrix.php');

$MenuItems['system']['Maintenance']['Caption'] = array(_('Inventory Categories Maintenance'), _('Inventory Locations Maintenance'), _('Inventory Location Authorised Users Maintenance'), _('User Authorised Inventory Locations Maintenance'), _('Discount Category Maintenance'), _('Units of Measure'), _('MRP Available Production Days'), _('MRP Demand Types'), _('Maintain Internal Departments'), _('Maintain Internal Stock Categories to User Roles'), _('Label Templates Maintenance'));
$MenuItems['system']['Maintenance']['URL'] = array('/StockCategories.php', '/Locations.php', '/LocationUsers.php', '/UserLocations.php', '/DiscountCategories.php', '/UnitsOfMeasure.php', '/MRPCalendar.php', '/MRPDemandTypes.php', '/Departments.php', '/InternalStockCategoriesByRole.php', '/Labels.php');

$MenuItems['Utilities']['Transactions']['Caption'] = array(_('Change A Customer Code'), _('Change A Customer Branch Code'), _('Change A GL Account Code'), _('Change An Inventory Item Code'), _('Change A Location Code'), _('Change A Salesman Code'), _('Change A Stock Category Code'), _('Change A Supplier Code'), _('Translate Item Descriptions'), _('Update costs for all BOM items, from the bottom up'), _('Re-apply costs to Sales Analysis'), _('Delete sales transactions'), _('Reverse all supplier payments on a specified date'), _('Update sales analysis with latest customer data'), _('Copy Authority of GL Accounts from one user to another'));
$MenuItems['Utilities']['Transactions']['URL'] = array('/Z_ChangeCustomerCode.php', '/Z_ChangeBranchCode.php', '/Z_ChangeGLAccountCode.php', '/Z_ChangeStockCode.php', '/Z_ChangeLocationCode.php', '/Z_ChangeSalesmanCode.php', '/Z_ChangeStockCategory.php', '/Z_ChangeSupplierCode.php', '/AutomaticTranslationDescriptions.php', '/Z_BottomUpCosts.php', '/Z_ReApplyCostToSA.php', '/Z_DeleteSalesTransActions.php', '/Z_ReverseSuppPaymentRun.php', '/Z_UpdateSalesAnalysisWithLatestCustomerData.php', '/Z_GLAccountUsersCopyAuthority.php');

$MenuItems['Utilities']['Reports']['Caption'] = array(_('Debtors Balances By Currency Totals'), _('Suppliers Balances By Currency Totals'), _('Show General Transactions That Do Not Balance'), _('List of items without picture'));
$MenuItems['Utilities']['Reports']['URL'] = array('/Z_CurrencyDebtorsBalances.php', '/Z_CurrencySuppliersBalances.php', '/Z_CheckGLTransBalance.php', '/Z_ItemsWithoutPicture.php');

$MenuItems['Utilities']['Maintenance']['Caption'] = array(_('Maintain Language Files'), _('Make New Company'), _('Data Export Options'), _('Import Customers from .csv file'), _('Import Stock Items from .csv file'), _('Import Price List from .csv file'), _('Import Fixed Assets from .csv file'), _('Import GL Payments Receipts Or Journals From .csv file'), _('Create new company template SQL file and submit to webERP'), _('Re-calculate brought forward amounts in GL'), _('Re-Post all GL transactions from a specified period'), _('Purge all old prices'), _('Remove all purchase back orders'));
$MenuItems['Utilities']['Maintenance']['URL'] = array('/Z_poAdmin.php', '/Z_MakeNewCompany.php', '/Z_DataExport.php', '/Z_ImportDebtors.php', '/Z_ImportStocks.php', '/Z_ImportPriceList.php', '/Z_ImportFixedAssets.php', '/Z_ImportGLTransactions.php', '/Z_CreateCompanyTemplateFile.php', '/Z_UpdateChartDetailsBFwd.php', '/Z_RePostGLFromPeriod.php', '/Z_DeleteOldPrices.php', '/Z_RemovePurchaseBackOrders.php');
?>

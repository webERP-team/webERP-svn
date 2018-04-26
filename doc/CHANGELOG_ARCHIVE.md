# Changelog - Archive
All notable changes to the webERP project will be documented in this file.  
The format is based on [Keep a Changelog], and this project adheres to [Semantic Versioning].  
For the most recent changelogs, please refer to [CHANGELOG.md].

## [v4.14] - 2017-06-20

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Add menu.css to make the main menu workable in header.php. |  | Exson | 2017-06-19 |  |  |
| Fixed the variable not existed error (Reported by Tim). Add a feature to add favorite script under main menu which idea originated from Tim's Kwamoja project. |  | Exson | 2017-06-19 |  |  |
| Add a empty submit blocked feature to SupplierInvoice.php to avoid a empty invoice. |  | Exson | 2017-06-19 |  |  |
| Make these classes defined php 7 compliant and keep php4 backwards compatibility. |  | Exson | 2017-06-19 |  |  |
| Rename from css/custom/ to from css/WEBootstrap/. |  | RChacon | 2017-06-18 |  |  |
| Standardise icon size to 36x36 px in css/custom/ (WEBootstrap). |  | RChacon | 2017-06-18 |  |  |
| Delete unused images in css/xenos/. |  | RChacon | 2017-06-18 |  |  |
| Improve jump to enter a GL receipt if 'Type'=='GL' in CustomerReceipt.php. |  | Tim | 2017-06-18 |  |  |
| Add missing images and delete unused images in css/custom/. |  | RChacon | 2017-06-18 |  |  |
| Fixed a typo in GLPostings.inc. |  | Exson | 2017-06-18 |  |  |
| Created a custom theme for webERP, Exson port part of it to webERP. |  | Giankocr | 2017-06-18 |  |  |
| In CustomerReceipt.php, it jumps to enter a GL receipt if 'Type'=='GL'. |  | RChacon | 2017-06-17 |  |  |
| Fixed quotation and orders are mixed in searching result of SelectSalesOrder.php. |  | Exson | 2017-06-08 |  |  |
| Fixed empty list error in GLAccounts.php, GLCashFlowsIndirect.php, GLCashFlowsSetup.php |  | VortecCPI | 2017-06-01 |  |  |
| Remove changes in Andrew's PO_Items.php script that increased dp for purchase price to 5 - for some low value currencies this would be inappropriate, better to use the currency decimal places + 2 as per Ricard's idea |  | Phil | 2017-05-18 |  |  |
| SQL correction in PO_Items.php. Line 1094. |  | Andrew | 2017-05-16 |  |  |
| Fixes to MRP scripts SQL to get table names |  | Andy Couling/JanB | 2017-05-07 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=2448) |
| Rename includes/footer.inc, includes/header.inc and includes/session.inc to includes/footer.php, includes/header.php and includes/session.php. |  | RChacon | 2017-04-13 |  |  |
| In UserSettings.php, add options to turn off/on page help and field help. In WWW_Users.php, improve code and documentation. |  | RChacon | 2017-04-09 |  |  |
| In ManualContents.php, fix checkbox showing and do some improvements. |  | RChacon | 2017-03-30 |  |  |
| In webERP manual, allow to use default pages if locale pages do not exist. |  | RChacon | 2017-03-29 |  |  |
| Make degine $ReportList as an array to comply with PHP 7. |  | Abel World | 2017-03-26 |  |  |
| Rename AccountSectionsDef.inc to AccountSectionsDef.php. |  | RChacon | 2017-03-19 |  |  |
| Allow sales order item lines to be imported from a csv consisting of lines of item code, quantity |  | Tim | 2017-03-11 |  |  |
| BankAccounts.php: Add quotes to variable in query. |  | TurboPT/Tim | 2017-03-07 |  |  |
| SpecialOrder.php add identifier to delete link; DefineSpecialOrderClass.php remove the "&" with parameter to function remove_from_order(). |  | TurboPT | 2017-02-27 |  |  |
| CopyBOM.php fixed insert SQL had duplicate digitals field |  | TurboPT | 2017-02-26 |  |  |
| CustomerReceipt now calculates payment discount based on percentages entered in payment methods |  | Phil | 2017-01-14 |  |  |
| Removes the "&" before the variable $ResultIndex in ConnectDB_XXX.inc. |  | RChacon | 2017-01-12 |  |  |
| Added a discount percentage field to the payment methods - to allow calculation of discount when receipts entered... still yet to code this bit |  | Phil | 2017-01-12 |  |  |
| Add Turn off/on the page help and the field help. |  | RChacon | 2017-01-06 |  |  |
| In GLCashFlowsIndirect.php, fix named key in Associative array with config value. Thanks Tim. |  | RChacon | 2017-01-05 |  |  |
| For strict Standards, removes the "&" before the variable in DB_fetch_row() and in DB_fetch_array() in ConnectDB_XXX.inc. Thanks Tim. |  | RChacon | 2017-01-05 |  |  |
| In PurchasesReport.php, fix date comparison and title. Thanks Tim. |  | RChacon | 2016-12-21 |  |  |
| Standardise to "Print" button. |  | RChacon | 2016-12-20 |  |  |
| Add a report of purchases from suppliers for the range of selected dates. |  | RChacon | 2016-12-20 |  |  |
| Dashboard.php: Correct table closure. When there are no outstanding orders, causes footer artifact. |  | PaulT | 2016-12-11 |  |  |
| Fixed the variable error in stock take pdf header in includes/PDFStockCheckPageHeader.inc. And fixed undefined noise in Payments.php. Reported by shane. |  | Exson | 2016-12-08 |  |  |
| Fixed noise of undefined variable and string required for function_exists in Dashboard.php. Reported by Shane. |  | Exson | 2016-12-08 |  |  |
| SelectProduct.php: Add footer before the script exits when stock categories are not defined. |  | PaulT | 2016-12-06 |  |  |
| WriteReport.inc: Fix condition needed to support PHP7, reported by Tim. |  | PaulT | 2016-12-02 |  |  |
| Fix and improve code for existent parameters in GLCashFlowsSetup.php. |  | RChacon | 2016-12-02 |  |  |
| Add location code and reference to Work Orders search result in SelectWorkOrder.php. |  | Exson | 2016-12-02 |  |  |
| Fixed the no users data displayed bug and copy BOM fields error bug in WWW_Users.php and CopyBOM.php. Thanks for shane's report. |  | Exson | 2016-12-02 |  |  |
| Fixed the bug that write off option not work without freight cost input in Credit_Invoice.php. |  | Exson | 2016-11-30 |  |  |

## [v4.13.1] - 2016-11-27

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Add a constraint to Pc assignment transfer in PcAssignCashTabToTab.php |  | Exson | 2016-11-26 |  |  |
| Fixed the bugs in InternalStockRequestInquiry.php. Thanks for Tim's report. |  | Exson | 2016-11-26 |  |  |
| On AccountGroups.php, add navigation aids (return button). |  | RChacon | 2016-11-23 |  |  |
| On WWW_Users.php, fix hardcoded label (difficult to translate for languages with a different structure to English), do sortable the users list, replace printf() with plain list echo, and add format class. |  | RChacon | 2016-11-08 |  |  |
| Add scripts to show a statement of cash flows for the period using the indirect method. |  | RChacon | 2016-11-08 |  |  |
| On SelectSupplier.php, fix table html code. |  | RChacon | 2016-11-08 |  |  |
| On SupplierPriceList.php, fix to show price in user's locale format, add supplier's id and name to title, replace printf() with plain list echo, and regroup and document code. |  | RChacon | 2016-11-06 |  |  |
| On Dashboard.php, fix creditors payment to point to allocation instead to payment, replace printf() with plain list echo, regroup code, and complete column styles. |  | RChacon | 2016-11-03 |  |  |
| On SupplierInquiry.php, replace the printf() statements with echo statements to fix bug caused by a supplier invoice with a comment that includes a % character (reported by R2-G, solution by Tim). |  | RChacon | 2016-11-01 |  |  |
| In SecurityTokens.php: Fix Description's input maxlength, fix table head in data table, move form-tables after data table, add cancel button in edit form table, add return button, add style to print data table, add title in form tables, regroup code, change from if/elseif to switch/case to improve code readability, and add code documentation. |  | RChacon | 2016-10-30 |  |  |
| On SupplierPriceList.php, add ViewTopic and BookMark, and complete html table. Add info to manual. |  | RChacon | 2016-10-30 |  |  |
| Fix SQL in ReverseGRN.php as reported by Ricard/Tim. |  | Phil | 2016-10-17 |  |  |
| Fix function convertDate(dS,dF). |  | RChacon | 2016-10-16 |  |  |
| Format the ManualAPITutorial.html for easier reading. |  | Eatong | 2016-10-05 |  |  |
| Add CSS rule for `<pre>` for easier reading. |  | Eatong | 2016-10-05 |  |  |
| Align field length of salesanalysis.salesperson to salesman.salesmancode. |  | Eatong | 2016-10-05 |  |  |
| Add cross.svg, next.svg, previous.svg, and tick.svg images in Scalable Vector Graphics (SVG) format for general use (any size). |  | RChacon | 2016-10-05 |  |  |
| In class.pdf.php, fix the function addJpegFromFile() use of the functionality Image() of TCPDF class. |  | RChacon | 2016-10-04 |  |  |
| In class.pdf.php, functions Rectangle() and RoundRectangle() use the functionalities Rect() and RoundedRectXY() of TCPDF class. |  | RChacon | 2016-10-04 |  |  |
| Add return.svg image for Return button in Scalable Vector Graphics (SVG) format. |  | RChacon | 2016-10-04 |  |  |
| Add webERP logo in Scalable Vector Graphics format. |  | RChacon | 2016-09-27 |  |  |
| Make customer reference GET method workable in SelectCompletedOrder.php. Fixed decimalplaces missing bug in SelectOrderItems.php. Add due date, order date and customer reference option in SelectSalesOrder.php. |  | Exson | 2016-09-25 |  |  |
| Make the details show immediately when the search result is one in SelectCompletedOrder.php.And add return links in OrderDetails.php. |  | Exson | 2016-09-25 |  |  |
| Fixed placing POs for sales orders using array form variable |  | Simon | 2016-10-24 |  |  |
| Fixed missing date in Sales Price history |  | Wayne McDougall | 2016-09-24 |  |  |
| Make Justify feature workable in addTextWrap in class.pdf.php. |  | Exson | 2016-09-24 |  |  |
| Fixed the AddTextWrap missing characters errors when there is space and make it more reliable. |  | Exson | 2016-09-24 |  |  |
| In SuppWhereAlloc.php, accepts the payment multiple creditors. In CustWhereAlloc.php, accepts the receipt of multiple debtors. |  | RChacon | 2016-09-21 |  |  |
| Add style to describe how button image should be displayed. Clean up Xenos css. |  | RChacon | 2016-09-18 |  |  |
| Add multiple items issue for non-controlled items feature to Work Orders in WorkOrderIssue.php. |  | Exson | 2016-09-18 |  |  |
| Add narrative, transaction date data to PDFOrdersInvoiced.php. |  | Exson | 2016-09-14 |  |  |
| Add order line narrative and invoices link to sales order inquiry in OrderDetails.php. |  | Exson | 2016-09-14 |  |  |
| Add a filter to avoid tons of zero valued gl transaction records generated in SQL_CommonFunctions.inc. |  | Exson | 2016-09-12 |  |  |
| Add WO items delete constraint in WorkOrderEntry.php. Thanks for Phil's reminder. |  | Exson | 2016-09-04 |  |  |
| Add delete Work orders Items feature in WorkOrderEntry.php. |  | Exson | 2016-09-04 |  |  |
| Fixed the undefined noise in WorkOrderStatus.php. |  | Exson | 2016-09-04 |  |  |
| Fixed the bug that work order location will be wrong when user select location which is not user's default location. |  | Exson | 2016-09-04 |  |  |
| Fixed accumulated No of orders bug in SalesByTypePeriodInquiry.php. |  | Dave | 2016-09-04 |  |  |
| Add new feature assign cash from one tab to another. |  | Exson | 2016-08-31 |  |  |
| Fixed the latin1 charset mixed bug in supplierdiscounts table; |  | Exson | 2016-08-24 |  |  |
| Fixed the bug that days of payment terms in the following month over 31 days can not be handled correctly in DateFunctions.inc. |  | Exson | 2016-09-24 |  |  |
| In ConfirmDispatch_Invoice.php, fix table html code. |  | RChacon | 2016-08-20 |  |  |
| In PDFStatementPageHeader.inc, replace addJpegFromFile() and RoundRectangle() functions from class.pdf.php with Image() and RoundedRect() functions from tcpdf.php. |  | RChacon | 2016-08-18 |  |  |
| Add a Cancel button on SupplierAllocations.php to make user can return to previous page easily. |  | Exson | 2016-08-18 |  |  |
| Add date format validation in PcClaimExpensesFromTab.php. |  | Exson | 2016-08-18 |  |  |
| In CustWhereAlloc.php and SuppWhereAlloc.php, use the ConvertSQLDate() function for the dates. |  | Tim | 2016-08-17 |  |  |
| In SuppWhereAlloc.php, show transaction date in report. Improvements in HTML code and code documentation. |  | Rchacon | 2016-08-14 |  |  |
| In CustWhereAlloc.php, show transaction date in report. Standardise trandate in debtortrans. Improvements in HTML code and code documentation. |  | Rchacon | 2016-08-13 |  |  |
| Committed falkoners fix for the upgrade script - was not adding the new field in customercontact required for the customer statements email address |  | Phil | 2016-08-11 |  |  |
| Fix SQL for location users in SelectSalesOrder.php |  | Simon Kelly | 2016-08-11 |  |  |
| In GLAccountInquiry.php, add noprint class to clean up printer output and improve code documentation. |  | RChacon | 2016-08-05 |  |  |
| Fix html code in SuppInvGRNs.php. |  | RChacon | 2016-08-05 |  |  |
| Make account inquiry shown directly when GL Code selected or inquiry result is 1 in SelectGLAccount.php. |  | Exson | 2016-08-02 |  |  |
| Add cost update date for material cost in WorkOrderCosting.php and WorkOrderIssue.php. |  | Exson | 2016-07-27 |  |  |
| Fixed the typo in Credit_Invoice.php introduced in previous update. |  | Exson | 2016-07-27 |  |  |
| Fixed the divided by zero error when discount is 100% in SelectOrderItems.php. |  | Exson | 2016-07-27 |  |  |
| Add error proof to avoid a blank credit note issued without any items credited or freight charge input in Credit_Invoice.php. |  | Exson | 2016-07-27 |  |  |
| Add InternalStockRequestInquiry.php script. |  | Exson | 2016-07-25 |  |  |
| Make items search limited to the sales orders and if search result is 1 show the result immediately in SelectSalesOrder.php |  | Exson | 2016-07-22 |  |  |
| Add empty check for internal request to avoid empty request creating in InternalStockRequest.php. |  | Exson | 2016-07-22 |  |  |
| Fixed the utf8 character print incorrect of pdf file in class.pdf.php. |  | Exson | 2016-07-09 |  |  |
| Fixed the transaction atomicity bug by change table lock to row lock in SQL_CommonFunctions.inc. |  | Exson | 2016-07-08 |  |  |
| Fixed the bug that when bank account or currency changes the functional rate or exrate unchanged with suggested rate in Payments.php. |  | Exson | 2016-07-08 |  |  |
| Fixed the bug of wrong original amount of payments to another bank accounts in GLAccountInquiry.php and wrong transaction link in DailyBankTransactions.php and add payment transaction no in bank transaction ref to make it traceable. |  | Exson | 2016-07-07 |  |  |
| Add identifier to avoid SESSION overwritten in CustomerReceipt.php. |  | Exson | 2016-06-29 |  |  |
| Fixed the wrong balance of amount in bank account currency in DailyBankTransactions.php. |  | Exson | 2016-06-29 |  |  |
| Fixed bom clone failure due to fields missing in CopyBom.php. Reported by shane. |  | Exson | 2016-06-20 |  |  |
| Fixed the bug that # is not allowed as part of stockid in SelectProduct.php. |  | Exson | 2016-06-20 |  |  |
| Make monthly payment term can be more than 30 days. |  | Exson | 2016-06-16 |  |  |
| Fix syntax error in StockClone.php |  | TurboPT | 2016-06-12 |  |  |
| Add data label for SalesGraph.php. |  | Exson | 2016-06-08 |  |  |

## [v4.13] - 2016-05-22

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Default new salesperson to current |  | Phil | 2016-05-30 |  |  |
| Remove hidden GP_Percent form field when customer login to prevent un-authorised viewing of this data when looking at the page source |  | Andew Galuski | 2016-05-30 |  |  |
| Updated Portuguese Brazilian translation |  | Gilberto Dos Santos Alves | 2016-05-28 |  |  |
| PrintCustStatement.php now has an option to email the statement and uses the customer contacts email addresses defined as wanting a statement and sends individual statements to each of the customer contacts for their customer statement and lists the customers and the recipients they have been sent to. The existing emailcustomerstatement script had too much potential to send statement for other customers to another customer by mistake - so I've removed it! |  | Phil | 2016-05-28 |  |  |
| PrintCustStatement.php now shows all the date. |  | Phil | 2016-05-23 |  |  |
| Credit_Invoice.php now adds correct tax authorities - correcting bug that resulted in foreign key errors when adding taxauthorities as per fix to SelectCreditItems.php on 13/1/16 |  | Phil | 2016-05-23 |  |  |
| POS upload file now includes assembly items |  | Phil | 2016-05-20 |  |  |
| Insert missing script as an utility. |  | RChacon | 2016-05-20 |  |  |
| In Login.php, add a return link. |  | RChacon | 2016-05-20 |  |  |
| CustomerAllocations.php consistent sort order adding by date and transno sorting for transactions on the same date |  | Paul Harness | 2016-05-20 |  |  |
| In SelectCustomer.php, use a default map host if $map_host is empty. |  | RChacon | 2016-05-15 |  |  |
| In AccountGroups.php, hide no printing elements. |  | RChacon | 2016-05-15 |  |  |
| Add sequence digitals to make BOM sequences can be adjusted flexible and avoid any uncertainty of the number stored in SQL. Thanks Tim's suggestion. |  | Exson | 2016-05-15 |  |  |
| In SelectCustomer.php, fix use of Google Maps JavaScript API V3, unpaired html tags and other bugs. |  | RChacon | 2016-05-14 |  |  |
| In AddCustomerContacts.php, add classes to print and table heads. Improve code. |  | RChacon | 2016-05-13 |  |  |
| Fix blank line caused by reverse character RTL. Clean up code. |  | RChacon | 2016-05-12 |  |  |
| Include translation to hebrew, thanks to Hagay Mandel. |  | RChacon | 2016-05-11 |  |  |
| Tidy Code Up to remove redundant code according to Tim's guide. |  | Exson | 2016-05-09 |  |  |
| Modify Z_RePostGLFromPeriod.php to make this feature still reliable with prev |  | Exson | 2016-06-05 |  |  |
| Remove the $db which is not needed now. Reported by Tim. ious version of GLPostings.inc. Rework the new GLPostings.inc. |  | Exson | 2016-06-05 |  |  |
| Fixed typo of IndentText, thanks for Tim's report. Change sequence from int to double to make item is easily inserted into BOMs and Add pictures to BOMs and make BOM printable. |  | Exson | 2016-06-05 |  |  |
| In Z_poAddLanguage.php, fix directory name and default language file name. |  | RChacon | 2016-05-01 |  |  |
| In includes/DateFunctions.inc, add year in long date and time in locale format. |  | RChacon | 2016-04-29 |  |  |
| Apparently only change required for PHP7 |  | Tim | 2016-04-26 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=2733&pid=7132#pid7132) |
| In Payments.php, allow to input a customised gltrans.narrative, supptrans.suppreference and supptrans.transtext. |  | RChacon | 2016-04-25 |  |  |
| google maps api improvement to SubmitCustomerSelection changed script src to https du to mixed content error Updated to Google Maps API v.3 Integrated code lines from ceocode.php to update Customers (custbranch table) without lat (0) but width brpostaddr1 Show Branch Contact beneath Customer Contacts when branch is selected Table-width cleanup - diff submitted in March sorry Phil's bad :-( |  | Jan Bakke | 2016-04-25 |  |  |
| Improvements to allow .png and .gif images |  | Jan Bakke | 2016-04-25 |  |  |
| Add missing } causing error. |  | PaulT | 2016-04-15 |  |  |
| Add cost security token to make price security and cost security separated to cope with different situation. |  | Exson | 2016-04-12 |  |  |
| In WorkCentres.php, add ViewTopic and BookMark and completes html table. In doc/Manual/ManualManufacturing.html, add help for WorkCentres.php. |  | RChacon | 2016-04-10 |  |  |
| In SupplierInvoice.php, add ' - ' to standardise gltran.narrative to "SupplierID - ". In SuppTransGLAnalysis.php, add ViewTopic and Bookmark, completes html tables, add text class, and add currency_code to input table. In css/*/default.css, regroup horizontal align classes for readability. In doc/Manual/ManualAccountsPayable.html, add anchor id="SuppTransGLAnalysis". |  | RChacon | 2016-04-10 |  |  |
| In includes/class.pdf.php, add script documentation and completes switch($Align) to translate from Pdf-Creator to TCPDF. |  | RChacon | 2016-04-07 |  |  |
| Add remark column to CopyBOM.php. |  | Exson | 2016-04-07 |  |  |
| Make Petty cash assigner and authorizer multiple selectable in PcExpenses.php,PcTabs.php, PcAssignCashToTab.php and PcAuthorizeExpenses. |  | Exson | 2016-04-01 |  |  |
| In CustomerReceipt.php, allow more precision on the functional_exchange_rate. In Payments.php, add pattern and required attributes to the functional_exchange_rate input. |  | RChacon | 2016-03-24 |  |  |
| Make the MRP report more place for material description in MRPReport.php. |  | Exson | 2016-03-24 |  |  |
| Correct the currency code for transaction between bank account in GLAccountInquiry.php. |  | Exson | 2016-03-18 |  |  |
| Fixed the bug that transaction between bank shows wrong original currency and amount in GLAccountInquiry.php. |  | Exson | 2016-03-18 |  |  |
| Fixed the credit note tax authority not set up bug in SelectCreditItems.php. Reported by Bob. |  | Tim | 2016-03-10 |  |  |
| Fixed the bug of wrong location selected when add items to Work Orders in WorkOrderEntry.php. |  | Exson | 2016-09-03 |  |  |
| Fix FormDesigner requires casting XML elements as strings |  | Andrew Galuski | 2016-03-09 |  |  |
| Add PO details option to show balance of each outstanding PO in PO_SelectOSPurchOrder.php. |  | Exson | 2016-09-03 |  |  |
| Fixed the typo which make sql query failed in GLPostings.inc. reported by Richard. |  | Exson | 2016-09-03 |  |  |
| Fix the GLPosting initiating error in GLPostings.inc. |  | Richard/Exson | 2016-02-24 |  |  |
| Fixed typo in upgrade4.12.3-4.13.sql |  | JanB/Tim | 2016-02-20 |  |  |
| Fixed page number error of AgedDebtors.php. |  | Dave Parrish | 2016-02-19 |  |  |
| Fixed the bug of chartdetails bfwd amount wrong in GLPostings.inc. |  | Exson | 2016-02-02 |  |  |
| Make GL Posting really transaction in GLPostings.inc. |  | Exson | 2015-02-01 |  |  |
| Fix the bug to print invoice instead of credit note when a credit note requested in CustomerInquiry.php reported by daveparrish. |  | Exson | 2016-01-30 |  |  |
| Add Supplier transaction allocation inquiry in SuppWhereAlloc.php and add a link to in SupplierInquiry.php. |  | Exson | 2016-01-14 |  |  |
| Add credit note allocation option for CustWhereAlloc.php and add a link to it in CustomerInquiry.php and make the allocation printable by print.css. |  | Exson | 2016-01-14 |  |  |
| Add remark to BOM items and make the BOM printable via the new print.css created by Rafael. |  | Exson | 2016-01-13 |  |  |
| Fixed bug in creating customer credit notes manually - blank taxes were being added that caused the SQL to commit the transaction to fail with foreign key constraint to tax authorities |  | Phil | 2016-01-13 |  |  |
| Fixed the variables non-refresh bugs in GLAccountInquiry.php. Reported by Richard. |  | Exson | 2016-01-13 |  |  |
| Fixed the bug of bank account original amount data error. Reported by Tim, Richard and make this data only available for bank account. |  | Exson | 2016-01-11 |  |  |
| Fixed the lot control items negative not allowed problem and fix the data storage caused precision error which make material issuing is impossible under some situation in WorkOrderIssue.php. |  | Exson | 2016-07-01 |  |  |
| In GLAccountUsers.php: Fix script name; add $ViewTopic and $BookMark; improve $SelectedGLAccount validation; improve page_title_text; improve select GL account; regroup modify access permission code (improve logic); add classes to table elements; translate database "0" and "1" to human "No" and "Yes"; simplify and tide code; modify prnMsg from multiple part sentence to one part sentence (better to translate when language use a different grammar structure from English); add "Print This", "Select A Different GL account" and "Return" buttons. Add info to ManualGeneralLedger.html. |  | RChacon | 2015-12-29 |  |  |
| Fixed the bug that discount not modified for items whose discount is null in discount matrix in SelectOrderItems.php. |  | Exson | 2015-12-28 |  |  |
| In UserGLAccounts.php: Fix script name; add $ViewTopic and $BookMark; improve $SelectedUser validation; improve page_title_text; improve select user; regroup modify access permission code (improve logic); add classes to table elements; translate database "0" and "1" to human "No" and "Yes"; simplify and tide code; modify prnMsg from multiple part sentence to one part sentence (better to translate when language use a different grammar structure from English); add "Print This", "Select A Different User" and "Return" buttons. Add info to ManualGeneralLedger.html. |  | RChacon | 2015-12-27 |  |  |
| Add items not received information on outstanding po inquiry screen in PO_SelectOSPurchOrder.php. |  | Exson | 2015-12-26 |  |  |
| Add supplier no as a option for supplier transaction inquiry in SupplierTransInquiry.php. |  | Exson | 2015-12-24 |  |  |
| Add width of printed text to make day to appear in PDFOstdgGRNsPageHeader.inc. |  | Exson | 2015-12-24 |  |  |
| Add Completed option for PO printed to allowed the order details can be completed in PO_Header.php. |  | Exson | 2015-12-24 |  |  |
| GoodsReceived.php now shows the supplier's item code as well |  | Phil | 2015-12-20 |  |  |
| SelectProduct.php now allows items to be searched based on the supplier's item code |  | Phil | 2015-12-20 |  |  |
| Remove retrieving allocated data in Z_AutoCustomerAllocations.php. |  | Exson | 2015-12-14 |  |  |
| Fixed the default Transaction Disable bug to enable for CustomerBranches.php. |  | Exson | 2015-12-11 |  |  |
| Add invoice no while reprint GRN in ReprintGRN.php. |  | Exson | 2015-12-10 |  |  |
| Add multiple work orders total cost inquiry script. |  | Exson | 2015-12-10 |  |  |
| Fixed the telephone regular expression bug in SelectCustomer.php. Reported by Terry. |  | Exson | 2005-12-09 |  |  |
| Remove the wrong foreign key in suppinvstogrn. Reported by rafael. |  | Exson | 2015-12-01 |  |  |
| Fixed the rounding error caused extra lines on WO pdf file and 2 number display without locale format in PDFWOPrint.php. |  | Exson | 2015-11-26 |  |  |
| Updated Portuguese Brazilian translation |  | Gilberto Dos Santos Alves | 2015-11-21 |  |  |
| Remove the duplicate foreign key in stockrequest and stockrequestitem; |  | AlexFigueiro | 2015-11-18 |  |  |
| Tidy css/*/default.css, reagrouping style for clases centre, number, page_title_text and text. |  | RChacon | 2015-11-15 |  |  |
| Add new arabic locale for Syria. |  | Hazem Wehbi | 2015-11-15 |  |  |
| In css/default/default.css, add sections to use with a cascading style sheet for a small device width, reagroup style for centre, number and text in tables. |  | RChacon | 2015-11-14 |  |  |
| New feature GL accounts - users authority. |  | Ricard | 2015-11-11 |  |  |
| In CustomerReceipt.php, minor changes (completes table columns, adds classes, etc.). |  | RChacon | 2015-11-09 |  |  |
| Tidy code up following Tim's suggestion. |  | Exson | 2015-11-05 |  |  |
| In GLAccountInquiry.php, add ViewTopic and BookMark, fix some colspan, and add thead and column classes. |  | RChacon | 2015-11-03 |  |  |
| Tidy code up in StockClone.php. |  | Exson | 2015-11-03 |  |  |
| Fixed typo in PriceMatrix.php. |  | Exson | 2015-11-03 |  |  |
| Allow user input supplier's delivery note during goods receiving and make the inquiry script for it. So users can search corresponding GRN, PO and invoice with it. |  | Exson | 2015-10-30 |  |  |
| Add gl narrative and account balance information to DailyBankTransactions.php. |  | Exson | 2015-10-29 |  |  |
| Add bank default currency, original amount and check no data to GL account inquiry in GLAccountInquiry.php. |  | Exson | 2015-10-28 |  |  |
| Contribute email customer statements feature scripts. |  | UK-Steven | 2015-10-06 |  |  |
| Fixed the wrong unable to identify the selected customer warning at SelectOrderItems.php. |  | Exson | 2015-10-06 |  |  |
| ADD invoice to grns mapping data in SupplierInvoice.php. |  | Tim | 2015-09-23 |  |  |
| In StockLocStatus.php, add current date and time, and format to use print.css. |  | RChacon | 2015-09-21 |  |  |
| Fix the wrong material cost updated in SupplierCredit.php. Reported by Akits. |  | Exson | 2015-09-21 |  |  |
| Korean translation via Google translate |  | Dongbak Cha | 2015-09-19 |  |  |
| Rebuild languages files *.pot, *.po and *.mo to includes new texts. |  | RChacon | 2015-09-14 |  |  |
| In AnalysisHorizontalIncome.php, delete duplicated tag. Replaces text "Absolute/Relative variation" with "Absolute/Relative difference" to avoid confusions. |  | RChacon | 2015-09-14 |  |  |
| In AccountSections.php, add modifications for direct printing. |  | RChacon | 2015-09-13 |  |  |
| Fix to Z_ImportStocks.php added quotes to descriptions - also fixes error message when the csv does not match the template |  | Wes Wolfenbarger | 2015-09-04 |  |  |
| Fixed the allocation status of involved invoice and credit notes in Credit_Invoice.php. |  | Exson | 2015-08-27 |  |  |
| In AnalysisHorizontalIncome.php, delete variable $period because it is not used anywhere (thanks Tim). Extract header.inc from if. |  | RChacon | 2015-08-19 |  |  |
| In doc/ManualGeneralLedger.html, add help for the horizontal analysis. |  | RChacon | 2015-08-13 |  |  |
| Add new script AnalysisHorizontalIncome.php to generate an horizontal analysis of the statement of comprehensive income. In AnalysisHorizontalPosition.php, adjust signs and add report footnote. |  | RChacon | 2015-08-13 |  |  |
| In AnalysisHorizontalPosition.php, modify DB_fetch_array() function because it requires only one parameter (thanks Tim). Other improvements. |  | RChacon | 2015-08-05 |  |  |
| Add new script AnalysisHorizontalPosition.php to generate an horizontal analysis of the statement of financial position. |  | RChacon | 2015-08-04 |  |  |
| In CustomerReceipt.php, move currency tags near currency ratesand other minor changes. |  | RChacon | 2015-08-02 |  |  |
| In Payments.php, move currency tags near currency rates. |  | RChacon | 2015-07-27 |  |  |
| In header.inc, add meta tag to keep relationship between CSS pixels and device pixels. |  | RChacon | 2015-06-20 |  |  |
| Fixed the menu cannot show completely on mobile phone in xenos/default.css. |  | Kif | 2015-06-19 |  |  |
| Currencies.php: Add closing select, td and tr tags to complete last table row. |  | TurboPT | 2015-06-04 |  |  |
| Fix error_reporting() bug change && to & in install/index.php. |  | Thumb | 2015-05-27 |  |  |
| SupplierInquiry.php moved SQL to get the users authorisation to put supplier invoices on hold outside the loop to avoid unecessary round trips to the SQL server |  | Tom Barry | 2015-05-25 |  |  |
| Adjust CustomerAccount.php for direct printing. |  | RChacon | 2015-05-21 |  |  |
| Add documentation and help for users in Locations.php and ManualInventory.html. |  | RChacon | 2015-05-19 |  |  |

## [v4.12.3] - 2015-05-17

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Made currencies delete check first for bank accounts set up using the currency - as reported by Ricard |  | Phil | 2015-05-17 |  |  |
| New features: (1) GL account code for an inventory location, so that you can have general ledger transactions of inventory transfers to or from this location; useful for pledged inventory and goods on consignment. (2) Allow Invoicing parameter to allow or deny the availability of a location to be delivered; useful when a location stores compromised good that can not be sold. |  | RChacon | 2015-05-10 |  |  |
| 09/05/15 Exson: Fixed no input filtering bug which cause input failure and location quantity become mess in WorkOrderReceive.php. But the transaction failure maybe still there. |  | Exson | 2015-05-09 |  |  |
| In DeliveryDetails.php, add $ViewTopic and $BookMark to ManualSalesOrders.html. |  | RChacon | 2015-05-08 |  |  |
| In CustomerInquiry.php, hide submit button and 'More Info' columns when printing. Add thead tag to enable the table header to be printed at the top of each page. |  | RChacon | 2015-05-07 |  |  |
| In SupplierInquiry.php, hide submit button and 'More Info' columns when printing. Add thead tag to enable the table header to be printed at the top of each page. |  | RChacon | 2015-05-07 |  |  |
| Make PO number searching also in compliance with location authority rules and make default search result as all if users have full location authority. |  | Exson | 2015-05-04 |  |  |
| Make all option available for users who have authority for all locations and all option available for all stock categories in PO_SelectOSPurchOrder.php. |  | Exson | 2015-05-04 |  |  |
| Added bankaccountusers table in Z_ChangeGLAccountCode.php |  | TeHonu | 2015-05-02 |  |  |
| Add Delivery Date in GoodsReceived.php output. Forum feature request by giusba71. |  | TurboPT | 2015-04-30 |  |  |
| Fixed the notice noise in SupplierCredit.php and SupplierInvoice.php. |  | Exson | 2015-04-30 |  |  |
| Fixed the properties initial bug and tidy code up by fix typo. |  | Exson | 2015-04-30 |  |  |
| Add adjustment reason to the mail text and fixed the notice noise in StockAdjustments.php. |  | Exson | 2015-04-26 |  |  |
| Comment out the mrpparameters table update in sql file upgrade4.11.3-4.11.4.sql which made a misleading during upgrade and absolutely non-necessary. |  | Exson | 2015-04-26 |  |  |
| Fixed the account code cannot be key in directly bug in GLJournal.php. Reported by Akits. |  | Exson | 2015-04-26 |  |  |
| Fixed the raw material sellable bug introduced by myself in SelectOrderItems.php. |  | Exson | 2015-04-26 |  |  |
| Remove the class noprint from the image in page_title_text. |  | RChacon | 2015-04-19 |  |  |
| Add ".page_title_text img" as a display none class. This makes unnecessary to add the class noprint to the image in page_title_text. |  | RChacon | 2015-04-19 |  |  |
| SelectSupplier - total supplier spend was incorrect only looked at supplier transactions excluding invoices? Now takes invoice net of debit notes reported by Andrew Galuski |  | Phil | 2015-04-19 |  |  |
| Fixed the Wiki link broken bug in SystemParameters.php. |  | Exson | 2015-04-07 |  |  |
| Fixed the bug that Credit and Debit submit amount was duplicated processed by local_number_format function. |  | Exson | 2015-04-04 |  |  |
| Remove comma from item description and suppliername to make csv file correctly in POReport.php. |  | Exson | 2015-04-01 |  |  |
| Fixed comma bug due to locale issue of POReport.php csv file. And add ordered/received quantity field in the report. |  | Exson | 2015-04-01 |  |  |
| Fixed the bug that when delete one gl item from the payment details, the bank account related setting will disappeared in Payments.php. Reported by Steven. |  | Exson | 2015-04-01 |  |  |
| Add a Z_ImportCustbranch.php script to import customer branches. |  | Thumb | 2015-04-01 |  |  |
| Get all taxes for the tax group, even if calculationorder is left at default value 0. |  | Vitaly | 2015-03-30 |  |  |
| Allow dummy/service items to be propogated to CounterLogic POS installs through the api |  | Phil | 2015-03-29 |  |  |
| Remove redundant code from PrintCustTrans.php. |  | Exson | 2015-03-27 |  |  |
| Make credit not for freight only is printable in PrintCustTrans.php. |  | Exson | 2015-03-27 |  |  |
| Fixed disabletrans status bug in CustomerBranches.php and fix index undefined noise. |  | Exson | 2015-03-26 |  |  |
| Add planned accumulation in MRP report. |  | Exson | 2015-03-25 |  |  |
| Fixed undefined index noise in Credit_Invoice.php. |  | Exson | 2015-03-14 |  |  |
| Fixed the undefined index noise in WorkOrderReceive.php. |  | Exson | 2015-03-13 |  |  |
| Fixed the undefined index noise in SelectWorkOrder.php. |  | Exson | 2015-03-13 |  |  |
| Make Labor type allowed by category select in WorkOrderIssue.php. |  | Exson | 2015-03-13 |  |  |
| Make Order No sortable in SelectSalesOrder.php. |  | Exson | 2015-03-12 |  |  |
| Fixed undefined parameters noise in PO_SelectPurchOrder.php. |  | Exson | 2015-03-12 |  |  |
| Fixed wrong handling for dummy parts receiving from PO in GoodsReceived.php. |  | Exson | 2015-03-11 |  |  |
| Page refresh when credit type changes in Credit_Invoce.php |  | Vitaly | 2015-03-09 |  |  |
| In DailyBankTransactions.php: Improves page_title_text. Orders by banktrans.transdate ascending and banktrans.banktransid ascending. Adds division to identify the report block. Groups table-header cells inside thead tags. Groups table-data cells inside tbody tags. Adds th.text class to left align. Adds "Print This" and "Return" buttons with icon. |  | RChacon | 2015-03-09 |  |  |
| Fixed undefined index noise of $_POST['SupplierContact'] in PO_Header.php. |  | Exson | 2015-03-09 |  |  |
| Fit HTML view of invoices to one screen |  | Vitaly | 2015-03-08 |  |  |
| Fixed undefined index noise in PO_Items.php. |  | Exson | 2015-03-08 |  |  |
| Deletes class="invoice" (it does not exist in css). Creates division id="Report" to identify the report block. Moves full width style to print.css (thanks Tim Schofield). |  | RChacon | 2015-03-07 |  |  |
| Fixed undefined index noise in ShipmentCosting.php. |  | Exson | 2015-03-07 |  |  |
| Fixed property ShiptCounter non defined bug in DefineSuppTransClass.php. |  | Exson | 2015-03-07 |  |  |
| Fixed the undefine index OpenOrClose noise in Shipt_Select.php. |  | Exson | 2015-03-07 |  |  |
| Fixed undefine index InputError noise in SupplierInvoice.php. |  | Exson | 2015-03-07 |  |  |
| Fixed typo in AuditTrail.php. |  | Exson | 2015-03-07 |  |  |
| Remove properties $_SESSION['Shipment']->GLLink which never been defined in Shipments.php. |  | Exson | 2015-03-07 |  |  |
| Change login date of users without login record to 'No login record' instead of today. It's very confusion. |  | Exson | 2015-03-07 |  |  |
| Fixed order value error that should be value undelivered instead of uncompleted line value and some notice noise in SelectSalesOrder.php. |  | Exson | 2015-03-07 |  |  |
| Display Phantom assembly type correctly in SelectProduct.php |  | Vitaly | 2015-03-04 |  |  |
| Added missing comma to SQL statement in Credit_Invoice.php |  | Vitaly | 2015-03-04 |  |  |
| Adds cross.png to all css images for use in Reset or Cancel buttons as needed. |  | RChacon | 2015-03-03 |  |  |
| Completes table-row colums, regroups price, cost and gross profit in one table-row, uses company decimal places for gross profit in SelectProduct.php. |  | RChacon | 2015-03-02 |  |  |
| Fix Z_ImportSupplier bug reported in forum by: Bill Schlaerth. |  | TurboPT | 2015-02-25 |  |  |
| Fix supplier delivery address bug reported in form by: Giusba |  | TurboPT | 2015-02-22 |  |  |
| Adjust page_title and add "Print This" and "Return" buttons with icon to Statement of Comprehensive Income and Trial Balance scripts. Add code documentation and removes redundant $ViewTopic and $BookMark in GLTrialBalance.php. |  | RChacon | 2015-02-22 |  |  |
| Add missing preview.png and new previous.png icons. Add "Print This" and "Return" buttons with icon in GLBalanceSheet.php. |  | RChacon | 2015-02-22 |  |  |
| Added global $db; statements to functions in ConnectDB_mysql.inc that had been missed for the transaction functions |  | Simon Rhodes | 2015-02-22 |  |  |
| Add headings, page-title and centre-align styles to print.css. Improve page title to use with print.css and add code documentation in GLBalanceSheet.php. |  | RChacon | 2015-02-21 |  |  |
| Fix heading 2 html-tags inside paragraph html-tags. Add code documentation. |  | RChacon | 2015-02-20 |  |  |
| Fix AddTextWrap split behaviour (thanks Andrew Galuski). Add code documentation. |  | RChacon | 2015-02-19 |  |  |
| Align numbers to the right in print.css |  | Tim | 2015-02-19 |  |  |
| Added print.css to allow printing pages off the screen |  | Vitaly | 2015-02-17 |  |  |
| Fixed date format error for request date and start date in WorkOrderEntry.php. |  | Exson | 2015-02-15 |  |  |
| Fix on PrintCustTransPortrait.php: Do not need to escape special characters in a string for use in an SQL statement. |  | RChacon | 2015-02-12 |  |  |
| BOMIndented.php fix bug that duplicated components - error with SQL to restrict to only those users with permission to view a locations |  | Vitaly | 2015-02-11 |  |  |
| New script StockCategorySalesInquiry.php - shows category sales by item for a selected custom date range |  | Phil | 2015-02-10 |  |  |
| Reinstate Andrew Galuski's lost functionality that shows only the items that are defined for a customer (in the custitems table) when searching for items for a sales order/quote. |  | Phil | 2015-02-10 |  |  |
| Standardise to currency.png. Delete currency.gif. |  | RChacon | 2015-02-09 |  |  |
| Changes from email.gif to email.png. Delete email.gif. |  | RChacon | 2015-02-08 |  |  |
| InventoryPlanning.php now has an option to export the last 24 months usage to CSV |  | Phil | 2015-02-08 |  |  |
| Add credit.png, email.png, folders.png and currency.png. Delete bank.gif. |  | RChacon | 2015-02-08 |  |  |
| New script CustomerAccount.php - on screen statement similar to CustomerInquiry.php |  | Phil | 2015-02-07 |  |  |

## [v4.12.2] - 2015-02-06

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Added SQL to add the script LocationUsers.php and the necessary table locationusers |  | Vitaly | 2015-02-06 |  |  |

## [v4.12.1] - 2015-02-06

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Rewrite of Z_ImportChartOfAccounts.php old script used the api and hard coded based on the database in api_php.php |  | Phil | 2015-02-05 |  |  |
| Fixed date compatible problem for strict sql mode in CopyBOM.php. |  | Exson | 2015-02-03 |  |  |
| Added more of Andrew Galuski's QA pdf manual to the webERP html manual. |  | Phil | 2015-02-03 |  |  |
| Fix bug in Credit_Invoice.php that prevented the credit note where the origianal invoice was over delivered compared to the underlying order. |  | Andrew Galuski | 2015-02-03 |  |  |
| Remove unecessary suppliercontact sql statement which makes installation failed. |  | Exson | 2015-02-02 |  |  |
| Add and modify help text. Spanish translation improvements. Standardise "Account:" to "Account". |  | RChacon | 2015-01-31 |  |  |
| Updated Traditional Chinese translation under zh_TW.utf8 |  | Jiro | 2015-01-30 |  |  |
| Spanish translation improvements. Note: The "Delete" key (keyboard) is translated to "Suprimir"; for usability, we standardise "borrar", "eliminar", etc. to "suprimir". |  | RChacon | 2015-01-27 |  |  |

## [v4.12] - 2015-01-27

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Add $ViewTopic and $BookMark, comments and page_title_text to ImportBankTrans.php. Add info to Manual. |  | RChacon | 2015-01-25 |  |  |
| Add info to Manual about Indian Numbering System. Add comments to code. Improvements to Cascading Style Sheet (CSS) for Manual. |  | RChacon | 2015-01-24 |  |  |
| Generalise indian number format for country=India (languages: English, Hindi, Marathi, etc.). |  | RChacon | 2015-01-23 |  |  |
| Add translation for transaction type, script ID, manual references, class="number" to columns. |  | RChacon | 2015-01-20 |  |  |
| Remove die() function from Suppliers.php which will make adding or updating suppliers' data failed when google map is activated. Reported by Terry. |  | Exson | 2015-01-20 |  |  |
| Fix Czech language code, add info for Croatian language, add the script name and revision number and sort by language code. |  | RChacon | 2015-01-17 |  |  |
| Standardise RevisionTranslations.php's titles to Stocks.php's titles. |  | RChacon | 2015-01-16 |  |  |
| Inserts HTML line breaks before all newlines in the default language's long description. |  | RChacon | 2015-01-16 |  |  |
| Remove redundant code from Prices.php. |  | Exson | 2015-01-12 |  |  |
| Fixed bug in Z_AutoCustomerAllocations.php to make it workable. |  | Exson | 2015-01-12 |  |  |
| Removed reference to AliasNbPages() now no longer part of TCPDF |  | Bob Thomas | 2015-01-09 |  |  |
| Added missing SQL for suppliercontacts and supplierdiscounts and adding the script SalesTopCustomersInquiry to the scripts table |  | Phil | 2015-01-07 |  |  |
| Allow turn on/off the dashboard display after login for an specific user. Add comments to code. |  | RChacon | 2015-01-05 |  |  |
| In RevisionTranslations.php, add $Id, $ViewTopic, $BookMark and page_title_text. Add info to manual. Add comments to code. |  | RChacon | 2015-01-04 |  |  |
| In SystemParameters.php, remove root path to config.php and add comments. |  | RChacon | 2015-01-04 |  |  |
| Move default theme from session.inc to config.php. Add the ability to change the default theme in SystemParameters.php. |  | RChacon | 2015-01-01 |  |  |
| Make StockCategories.php strict sql mode compatible. |  | Exson | 2015-01-01 |  |  |
| Standardise labels and texts in Prices_Customer.php SpecialOrder.php and WWW_Access.php. Add $ViewTopic and $BookMark to WWW_Access.php |  | RChacon | 2014-12-28 |  |  |
| Add comments, variables to link to the manual and titles in Z_CurrencyDebtorsBalances.php and Z_CurrencySuppliersBalances.php. |  | RChacon | 2014-12-28 |  |  |
| Add comments, variables to link to the manual and titles in Z_Change*.php. |  | RChacon | 2014-12-28 |  |  |
| Update comment for the SVN repository automatically updates the revision number, standardise title. |  | RChacon | 2014-12-28 |  |  |
| Add info to manual and context $BookMark for Sales People Maintenance. |  | RChacon | 2014-12-27 |  |  |
| Allow translation of typename in CustomerTransInquiry.php. |  | RChacon | 2014-12-27 |  |  |
| Allow translation of typename in GLAccountInquiry.php. |  | RChacon | 2014-12-27 |  |  |
| In AutomaticTranslationDescriptions.php, add comment for the SVN repository automatically updates the revision number, add $ViewTopic and $BookMark, and else if no translation. Add info to manual. |  | RChacon | 2014-12-27 |  |  |
| Set boolean field needrevision to tiny integer with a maximum display width of 1, signed and not null. Add comments to sql file. |  | RChacon | 2014-12-27 |  |  |
| Make COGSGLPostings.php, SalesGLPostings.php SQL strict mode compatible. |  | Exson | 2014-12-20 |  |  |
| Remove item from weberpchina.sql |  | Exson | 2014-12-20 |  |  |
| Modifications of stockdescriptiontranslations table for longdescription translation and translated versions control. |  | RChacon | 2014-12-18 |  |  |
| Fix to only add a description translation record where there is a non-empty translation string |  | Phil | 2014-12-18 |  |  |
| Google translate script to add descriptions in a selected language |  | Ricard | 2014-12-18 |  |  |
| Add new Quality Module to WebERP. |  | Agaluski | 2014-12-16 |  |  |
| Expand and uniform the accuracy of the exchange rate. |  | RChacon | 2014-12-16 |  |  |
| Fixed the branch field bug in CustomerReceipt.php. Reported by wertthey. |  | Exson | 2014-12-05 |  |  |
| Add Pan Size explanation (thanks to Exson Qu Tim Schofield and Pak Ricard) and other item maintenance explanations. |  | RChacon | 2014-11-29 |  |  |
| In PrintCustStatements.php, make translatable the currency name and the transaction type. |  | RChacon | 2014-11-24 |  |  |
| Spanish translation improvements. Merging "View GL Entries" and "View the GL Entries". |  | RChacon | 2014-11-24 |  |  |
| Fix the qualitytext does not have default value for strict sql mode in Credit_Invoice.php. |  | Exson | 2014-11-22 |  |  |
| Z_DeleteOldPrices.php now removes all old prices where there is a later start for a price with no end date |  | Phil | 2014-11-22 |  |  |
| Added new script CustomerBalancesMovement.php to show customer activity debits and credits and the movement of their balances over a specified date range |  | Phil | 2014-11-22 |  |  |
| Added new script Z_UpdateItemCosts.php that allows a csv import of items and costs and updates the standard cost based on imported data |  | Phil | 2014-11-22 |  |  |
| Add GRN numbers to select grns screen in SuppInvGRNs.php. |  | Exson | 2014-11-20 |  |  |
| Fixed the pop up error when input account no in Payments.php by align the js function with applying it. The fixes are mismatched before. Hope it's the last time. |  | Exson | 2014-11-20 |  |  |
| Move EnsureGLBalance() to the right place in ConfirmDispatch_Invoice.php to ensure that the whole transaction are checked instead of only one type of it checked. |  | Thumb | 2014-11-17 |  |  |
| Fixed InvoiceQuantityDefault parameters failed to save in SystemParameters.php. Reported by Richard. |  | Exson | 2014-11-17 |  |  |
| Remove qtyrecd in work orders requirements calculation from group by in MRP.php. |  | Exson | 2014-11-15 |  |  |
| Fixed the NULL bug for no issued materials for WO and make multiple times material issues correctly in MRP.php. |  | Exson | 2014-11-15 |  |  |
| Fixed the foreign key constrained failure bug in Z_DeleteCreditNote.php. |  | Exson | 2014-11-14 |  |  |
| Fixed the bug in MRP.php that wo requirement not counting demand for work orders without issuing items. Thanks for Tim's reminder. |  | Exson | 2014-11-14 |  |  |
| The systypes should be 28 instead of 38 for work order issued in MRP.php. |  | Tim | 2014-11-14 |  |  |
| Fixed bugs that issued materials not be calculated in demand for work orders and over received are ignored in level netting and negative inventory are not considered in REORDER level management in MRP.php which leads to wrong MRP results. |  | Exson | 2014-11-13 |  |  |
| Fix to javascript function to sort numbers including formatted numbers with commas. |  | Tim | 2014-11-09 |  |  |
| Fixed the bug that the wrong invoiced quantity result in Z_DeleteInvoice.php. |  | Alessandro Saporetti | 2014-11-08 |  |  |
| Remove date range from sql when users input the PO number in PO_SelectOSPurchOrder.php. |  | Exson | 2014-11-07 |  |  |
| Add code change for table custitem and pricematrix in Z_ChangeStockCode.php. |  | Exson | 2014-11-06 |  |  |
| Make Dashboard.php workable in php which version is lower than 5.3. Report by Craig Craven. |  | Exson | 2014-11-03 |  |  |
| Add Chinese Yuan to default.sql to prevent wrong Chinese Yuan input which leads lots of problem. Advised by Thumb. |  | Exson | 2014-10-28 |  |  |
| Fixed the variable undefined bug in simplify Chinese manual in Manual/ManualContents.php. Reported by webERP Chinese community QQ group chengdu-belief. |  | Exson | 2014-10-28 |  |  |
| Removed the $db parameter from all DB_Txn_Begin($db) DB_Txn_Commit($db) DB_Txn_Rollback($db)calls - $db is now global |  | Phil | 2014-10-27 |  |  |
| Removed the $db parameter from all DB_Maintenance($db) calls - $db is now global |  | Phil | 2014-10-27 |  |  |
| Removed the $db parameter from all DB_error_msg($db) calls - $db is now global |  | Phil | 2014-10-27 |  |  |
| Removed the $db parameter from all DB_error_no($db) calls - $db is now global |  | Phil | 2014-10-27 |  |  |
| Removed all $db in DB_query() calls as now a global |  | Phil | 2014-10-27 |  |  |
| Add sorting feature for tables to meet different needs. |  | Exson | 2014-10-25 |  |  |
| Fixed the extra blank in pattern in TaxGroups.php which leads to input failure. |  | Exson | 2014-10-24 |  |  |
| Correct Chinese currency code in weberpchina.sql |  | Exson | 2014-10-24 |  |  |
| Add Order by in SalesTypes.php. |  | Exson | 2014-10-22 |  |  |
| Remove debug info in GetPrice.inc. Reported by Jiro Akits. |  | Exson | 2014-10-22 |  |  |
| Remove translation of tax category 'Freight' inside database. |  | RChacon | 2014-10-17 |  |  |
| Add po line and due date result in OrderDetails.php. |  | Exson | 2014-10-17 |  |  |
| Fixed no receipt data displayed retrieved bugs for sales login in CustomerInquiry.php. |  | Exson | 2014-10-15 |  |  |
| Update Chinese traditional translation. |  | Jiro Akits | 2014-10-14 |  |  |

## v4.11.5 - 2014-10-13

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fixed the bugs in pattern properties with extra blank which leads to the patterns checking failed for all fields. Report from webERP Chinese community QQ group DongDong. |  | Exson | 2014-10-13 |  |  |

## v4.11.4 - 2014-10-12

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Add transaccion type name translation, transaccion type number and link to GL transaction inquiry in DailyBankTransactions.php. |  | RChacon | 2014-10-09 |  |  |
| Fixed the prices or line total variance in invoice for foreign currencies. |  | Exson | 2014-10-06 |  |  |
| Defaulted factory location from WO record |  | Andrew Galuski | 2014-10-04 |  |  |
| Fixed the purchase order details status to be right when the PO is recovered from Cancelled status. |  | Exson | 2014-09-30 |  |  |
| Change charset from latin1 to utf8 for supplierdiscounts. |  | Exson | 2014-09-29 |  |  |
| Add ascending class to have sortable columns, complete table-heads and add documentation. |  | RChacon | 2014-09-27 |  |  |
| Tidy up Dashboard to remove redundant code etc |  | Phil | 2014-09-21 |  |  |
| Move the DB Maintenance/GetConfig/Exchange rates update/audit trail purge inside UserLogin.php to ensure they do no add unecessary overhead to every page |  | Phil | 2014-09-20 |  |  |
| StockStatus incorrectly displaying quantity on order extended by conversion units |  | Bob Thomas | 2014-09-20 |  |  |
| Add ascending class to PaymentMethods.php to have sortable columns. |  | RChacon | 2014-09-19 |  |  |
| Moved the audit log purge to occur if DB_Maintenance is set to run |  | Phil | 2014-09-17 |  |  |
| Added BNZ GIFTS format for bank account transaction imports |  | Phil | 2014-09-16 |  |  |
| Fixed the date format errors in WorkOrderEntry.php. |  | Exson | 2014-09-15 |  |  |
| Added new MT940 - SCB - Siam Comercial Bank Thailand style - a bit different to ING's style. Modifications to BankAccounts.php to allow the transaction file format for imports. ImportBankTrans.php must now select the bank account to determine the file import format to use. It now uses an include for the file parsing so other formats can easily be accomodated with a new include. |  | Phil | 2014-09-13 |  |  |
| Adds gettext() to line 402 of CustomerAllocations.php. Reported by Harald. |  | RChacon | 2014-09-11 |  |  |
| Code tidy up to make it meet coding guidance in CustomerInquiry.php. |  | Exson | 2014-09-11 |  |  |
| Fixed the prnMsg bug in CustomerReceipt.php and add an inquiry link in it and make a status selectable in Customer inquiry and removed those rowstring definition which caused printf parameters missing. |  | Exson | 2014-09-11 |  |  |
| In general: Replaces code to determine background colour with TableRows(). Adds comments to ManualNewScripts.html. Fixes manual.css format. In GLTransInquiry.php: Adds $ViewTopic and $BookMark and sorts columns, and minor improvements. Minor translation improvements. |  | RChacon | 2014-08-31 |  |  |
| Standardizes page_title_text to main-menu-option, standardizes bottom tax-menu, adds $ViewTopic and $BookMark, completes table header columns, formats data columns in Tax* scripts. |  | RChacon | 2014-08-31 |  |  |
| In TaxAuthorities.php, adds $ViewTopic and $BookMark, completes table headings, formats number columns to fix sort order, and minor improvements. |  | RChacon | 2014-08-30 |  |  |
| WorkOrderEntry.php didn't handle no item returned from search correctly - fixed. |  | Phil | 2014-08-30 |  |  |
| In TaxAuthorityRates.php and TaxCategories.php: adds $ViewTopic and $BookMark; Uses gettext() to translate 'Exempt', 'Freight' and 'Handling'. |  | RChacon | 2014-08-29 |  |  |
| Fixed the StockLocStatus Serial Number link column shift problem. |  | Exson | 2014-08-29 |  |  |
| In Tax.php: Fixes SQL select order-by from transaction-date to record-number (primary key). Minor improvements. |  | RChacon | 2014-08-28 |  |  |
| Fixed the csv file aligned abnormal when there are commas in fields strings. |  | Exson | 2014-08-28 |  |  |
| In Tax.php: Adds code comments, clean-up unneeded variables, simplies and reorder SQL select, reduces if() calls, replaces addTextWarp-left-align with addText, replaces line-drawing a box with Rectangle(), fixes start-date for 'Y/m/d' format, adds start-date for 'Y-m-d' format, adds a summary section for the report, adds ViewTopic and BookMark, adds more NoOfPeriods. |  | RChacon | 2014-08-28 |  |  |
| In PDFPriceList.php: Adds code comments, adds PageHeader() print for currabrev and categoryid, replaces addTextWarp-left-align with addText, replaces line-drawing a box with Rectangle(), adds 'Prices excluding tax' warning. |  | RChacon | 2014-08-26 |  |  |
| Fixes double line feed in PDFQuotationPortrait.php reported by Arwan Galaya. Uniforms code between PDFQuotation.php and PDFQuotationPortrait.php. |  | RChacon | 2014-08-15 |  |  |
| In CustomerInquiry.php and SupplierInquiry.php makes translatable the systypes.typename. |  | RChacon | 2014-08-14 |  |  |
| In SupplierInquiry.php: Reorganizes columns Date, Type, Number, Reference and Comments, and regroups table-datacel format-strings as in CustomerInquiry.php code. |  | RChacon | 2014-08-14 |  |  |
| SupplierBalsAtPeriodEnd.php fix calculation to take into account fx differences on after date transactions |  | kelo | 2014-08-13 |  |  |
| includes/DatabaseTranslations.php used to store the fields of tables that are used from the database so that they can be translated in particular systypes for the types of transactions invoice credit note payment receipt etc. Can be extended for scripts and other tables where the data from the table is static and used to display |  | RChacon | 2014-08-13 |  |  |
| In CustomerInquiry.php: Adds class ascending and reorganizes columns Date, Type, Number and Reference; Regroups table-datacel format-strings, completes the datacel quantity by table-row; makes translatable the systypes.typename. |  | RChacon | 2014-08-13 |  |  |
| In SupplierInquiry.php: makes the field systypes.typename be translatable. |  | RChacon | 2014-08-12 |  |  |
| In Z_poRebuildDefault.php: extends title, improves comments, sets file to pot, updates $FilesToInclude, renames old pot file to bak extension. Minor improvements. |  | RChacon | 2014-08-12 |  |  |
| Add SQL and maintenance screens for Location based security. added new report aged controlled stock. additional scripts to follow as time allows |  | Andrew Galuski | 2014-08-08 |  |  |
| Change columns around on SelectWorkOrder.php |  | Tim | 2014-01-02 |  |  |
| Corrects the bottom line of the rectangle. Adds comments (info for developers). |  | RChacon | 2014-07-31 |  |  |
| In PDFPriceList.php: Adds comments (info for developers), ViewTopic and BookMark, and currency name in locale language; deletes unused lines; reformats for legibility; adjusts column sizes to field sizes; improves printing of stockmaster.longdescription; improves code to reduce execution time (calculation out of loops); links right column positions to right margin; corrects IF for CustomerSpecials (deletes translation). In ManualSalesTypes.html: Adds help info about Print a price list by inventory category. |  | RChacon | 2014-07-29 |  |  |
| Fixed the PO header lost initiator bugs when locations changed. Report by Akits from www.minghao.hk/bbs/. |  | Exson | 2014-07-27 |  |  |
| Allow dummy - labour stock type items to be added to purchase orders. |  | Phil | 2014-07-25 |  |  |
| Adds ViewTopic and BookMark, adds bullet for class ascending columns, repositions columns Type and Trans, add class number to Trans, completes printf. |  | RChacon | 2014-07-17 |  |  |
| Add standard cost to stock movement record for stock adjustments |  | Andrew Galuski | 2014-07-16 |  |  |
| Make status comments available in PO_OrderDetails.php to ensure that some important data such as grn reversing can be viewed. And add a return previous page link. |  | Exson | 2014-07-03 |  |  |
| Add code-comments, $ViewTopic, $BookMark, page_title_text and code to update NewStockID if OldStockID and SelectedStockItem are the same in Z_ChangeStockCode.php. Add id to the "Change An Inventory Item Code" topic anchor in ManualSpecialUtilities.html. |  | RChacon | 2014-06-27 |  |  |
| Make tel length in PO_Header.php is as same as field definition in sql. |  | Exson | 2014-06-27 |  |  |
| Fixed the bug that Select Customers search result inconsistence with Customer receipt search result in SelectCustomer.php. |  | Exson | 2014-06-26 |  |  |
| Add $ViewTopic, $BookMark to Prices.php and minor improvements. |  | RChacon | 2014-06-24 |  |  |
| Fixed the GL account validation pop up error in MiscFunctions.js. |  | Tim | 2014-06-23 |  |  |
| Fixed the bug that the sales order line's quantity will be update to zero but it show no change in appearance in SelectOrderItems.php. |  | Exson | 2014-06-23 |  |  |
| Replace now() with CURRENT_TIMESTAMP in MRP.php to get time stamp to meet ANSI standard. |  | Tim | 2014-06-22 |  |  |
| Fixed the mrp parameters runtime to datetime instead of date format since MRP running records need more precision. |  | Exson | 2014-06-22 |  |  |
| Solution for MRP LevelNetting unusual exit bug. The details can be found here: http://weberp-accounting.1478800.n4.nabble.com/MRP-Error-LevelNetting-td4657425.html |  | Benjamin (bpiltz2302) | 2014-06-22 |  |  |
| Add location check in StockAdjustments.php to prevent from users selecting controlled items based on one location but changed the location before submit it to server which make serial not exist check absolutely failed. |  | Exson | 2014-06-21 |  |  |
| Add Z_ImportDebtors.php into Utilities menu and scripts table. |  | Exson | 2014-06-17 |  |  |
| Fixed the bugs that www_Users.php allowed modules does not matched the one displayed in index.php. |  | Akits | 2014-06-15 |  |  |
| Fixed the exported csv files with wrong aligned fields due to comma as part of fields content. |  | Exson | 2014-06-11 |  |  |
| Fixed the Delivery Date lossing bugs when change Warehouse. Reported by akits from minghao.hk(weberp) bbs. |  | Exson | 2014-06-09 |  |  |
| Added bom effectivity dates into work order creation cost calculations - as spotted by Andrew Galuski |  | Phil | 2014-06-02 |  |  |
| Fixed bug in POItems.php that resulted in an SQL error when the number of items from the search was zero after previous searches had returned records |  | Phil | 2014-06-01 |  |  |
| Add page title text and icon to import scripts. Page title text = menu option. |  | RChacon | 2014-05-27 |  |  |
| Regroups the import scripts in the utility menu. |  | RChacon | 2014-05-27 |  |  |
| Add Sales man login control for PDFOrderStatus.php |  | Exson | 2014-05-27 |  |  |
| Fixed discount modifier missing bug in SelectOrderItems.php. |  | Thumb | 2014-05-26 |  |  |
| Fixes other problems related to updating translations of the items description.. |  | rchacon | 2014-05-26 |  |  |
| Fixed bugs in MRP.php that quantity which stated in PO with lines not completed but PO status marked Completed are calculated as supplies. Exson add PO order lines checked to avoid same problem. |  | newuser990/Exson | 2014-05-22 |  |  |
| Currencies.php now allows FunctionalCurrency to be modified |  | Tim | 2014-05-22 |  |  |
| Prevent use of enter key - experimental - in number fields - prevents users from losing data in big forms where they are entering large amounts of data e.g. purchase ordering - maybe we should consider for integer fields too? |  | Phil | 2014-05-22 |  |  |
| Tidy up SQL in StockClone.php |  | Ricard/Phil | 2014-05-22 |  |  |
| Tidy up code of SelectOrderItems.php. |  | Exson | 2014-05-19 |  |  |
| Fixed the bug in ConfirmDispatch_Invoice.php for Balance quantity canceled policy by line no instead of by stockid since webERP allow same stock appeared in one order multiple times. |  | Thumb | 2014-05-18 |  |  |
| Tidy up SelectSalesOrder.php SQL code and add Thumb's salesman login control and fixed bugs caused by no group by statements for customer and items selected. |  | Exson | 2014-05-18 |  |  |
| Sales invoice and credit scripts all check for 0 exchange rate before updating sales anlaysis now |  | Phil | 2014-05-17 |  |  |
| Fixed Z_ChangeStockCode.php which was orphaning stockdescriptiontranslations without changing them to the new code |  | Phil | 2014-05-17 |  |  |
| Fixed typo in MRPReport.php which make some supplies not to be shown on the report and use itemdue instead of deliverydate in MRP to ensure that requirements are calculated correctly for items due on different date. |  | Exson | 2014-05-14 |  |  |
| New Xenos theme |  | Khwunchai J | 2014-05-03 |  |  |
| StockCostUpdate.php now allows updates to manufactured items. |  | Phil | 2014-05-03 |  |  |
| Make price matrix workable including GetPrice.inc, MainMenuLinksArray.php,SelectOrderItems_IntoCart.inc,PriceMatrix.php, SelectProduct.php,StockDispatch.php. |  | Exson | 2014-04-29 |  |  |
| Fixed undefined variable bugs in PDFWOPageHeader.inc and PDFWOPrint.php and remove some redundant codes in PDFWOPrint.php |  | Exson | 2014-04-20 |  |  |
| Update zh_CN.utf8 translation. |  | Exson | 2014-04-17 |  |  |
| Fixed the table sort failure bugs in SelectSalesOrder.php reported by Andrew Agaluski. |  | Tim | 2014-04-09 |  |  |
| Add InOutModifier to input Quantity when there whole batches are removed. Otherwise, it will create a wrong quantity left in Add_SerialItems.php. |  | Exson | 2014-04-07 |  |  |
| Fixed the bug in Prices_Customer.php. MySQL strict mode not allowed a date is a white space. |  | Exson | 2014-04-07 |  |  |
| Fixed the quote date and order confirmed date has not been retrieved for orders to modify in SelectOrderItems.php. |  | Exson | 2014-04-06 |  |  |
| Extensions for printing WO documentation new labels and links to print |  | Andrew Galuski | 2014-04-06 |  |  |
| Fixes the bug that emptied ItemDescriptionLanguages. |  | rchacon | 2014-04-05 |  |  |
| Add decimal places check for controlled items quantity input in Add_SerialItems.php and InputSerialItems.php.The wrong decimal places will make this sections quite buggy and problem prone. |  | Exson | 2014-04-05 |  |  |
| Add a decimal places check in StockAdjustments.php to prevent from wrong decimal places input. |  | Exson | 2014-04-05 |  |  |
| Fixed the bug that controlled items can be dispatched more than order balance in ConfirmDispatch_Invoice.php. |  | Exson | 2014-04-04 |  |  |
| Get Credit available when modifiying an existing order |  | Andrew Galuski | 2014-04-02 |  |  |
| Fixed the Expiry Date not handled right during stocks adjustments by manual key in or bar code scan in Add_SerialItems.php, DefineSerialItems.php,InputSerialItems.php and InputSerialItemsKeyed.php. |  | Exson | 2014-03-28 |  |  |
| Fixed findLogoFile function in includes/session.inc |  | Serakfalcon | 2014-03-26 |  |  |
| Removed DefaultTheme configuration parameter - unecessary as noted by Serafalcon |  | Phil | 2014-03-24 |  |  |
| Fixed the no defined variable bugs in WorkOrderIssue.php. Reported by Tim. |  | Exson | 2014-03-23 |  |  |
| Fixed the bug that the search results shows only limited to DisplayRecordsMax which does not make sense due to pagination in WorkOrderIssue.php. |  | Exson | 2014-03-23 |  |  |
| Fixed that currency name not available in Prices.php. |  | Exson | 2014-03-23 |  |  |
| Fixed the stock searching function failure when items setup in system less than DisplayedRecordsMax in WorkOrderEntry.php. |  | Exson | 2014-03-22 |  |  |
| Fixed that bugs in WorkOrderIssue.php that the issued non BOM materials not shown. And fixed bugs in WorkOrderStatus.php failed to retrieve item description. |  | Exson | 2014-03-21 |  |  |
| Fixed the bugs in StockCounts that Location set does not work and link typo. |  | Exson | 2014-03-20 |  |  |
| Fixed the bug Items Other than those in BOM are not listed in Status of Work Order even if issued. Exson made a little revision to keep SQL query only once for those additional issued materials. Reported by newuesr990 from weberp forum. |  | Tim | 2014-03-19 |  |  |
| Fixed the bugs in GoodsReceived.php which will leads to duplicated goods receiving. |  | Exson | 2014-03-18 |  |  |
| Allow translations of tax category Freight |  | rchacon | 2014-03-17 |  |  |
| Z_ChangeStockCategory.php was not updating sales analysis records correctly - fixed |  | Phil | 2014-03-15 |  |  |
| Auto Supplier number functionality |  | Andrew Galuski | 2014-03-15 |  |  |
| Updated zh_TW.utf8 translation |  | Jiro Akits | 2014-03-14 |  |  |
| Removed htmchars in DB_escape_string() functions as suggested by Tim |  | Phil | 2014-03-14 |  |  |
| Ensure there are no unescaped characters in existing data when updating purchorders after reversing GRN. This fixes a symptom of a more widespread bug and therefore temporary until more general fix is applied. |  | icedlava | 2014-03-05 |  |  |
| Remove input fields (Country and Language) and display this data instead on customer view page |  | icedlava | 2014-03-05 |  |  |
| Remove redundant code in CustomerAllocations.php |  | Exson | 2014-03-03 |  |  |
| WhereUsedInquiry.php will no longer accept StockID with dash (-) as input allowed has changed. Still need to check for consistency for StockId input elsewhere in code. |  | icedlava | 2014-03-03 |  |  |
| Div swap in footer to simplify CSS for Gel and Silverwolf themes. Some other themes will see a basic position swap of the date and version info where these vertically appeared together at the far left end. |  | PaulT | 2014-03-02 |  |  |
| Correct variable spelling error. [reported in forums by serakfalcon] |  | PaulT | 2014-03-01 |  |  |
| Make negative integer allowable in MiscFunctions.js and make negative integer inputable for PastDueDays in SystemParameters.php. |  | Exson | 2014-02-24 |  |  |
| Fixed the wrong error messages displayed while input date data manually in MiscFunctions.js. |  | Exson | 2014-02-22 |  |  |
| GLTrialBalance_csv.php -Remove set AllowAnyone variable and prevent TB display - temp solution. |  | icedlava | 2014-02-19 |  |  |
| Remove redundant code in StockLocStatus.php and InventoryPlanning.php. |  | Exson | 2014-02-18 |  |  |
| Tidy up variable overwrite to if else structure in WorkOrderReceive.php scripts according Tim's comments. |  | Exson | 2014-02-17 |  |  |
| CopyBOM.php - SQL fix for insert to bom and locstock tables - ensure column counts match values even when zero. |  | icedlava | 2014-02-17 |  |  |
| StockCounts.php - Enter by Category only counts and enters 10 items maximum - fix to allow any number that are input. |  | icedlava | 2014-02-16 |  |  |
| Make perishable control available in WorkOrderReceive.php. |  | Exson | 2014-02-15 |  |  |
| Stock check comparison report now shows the bin location after the item code |  | Phil | 2014-02-15 |  |  |
| Customer statement now shows bank account number for payments based on the defined default bank account |  | Phil | 2014-02-15 |  |  |
| Reverse HTML5 input type="date" as this will not use the webERP javascript date picker used everywhere else - better be consistent as html5 date picker functionality varies between browsers - AddCustomerNotes.php and AddCustomerTypeNotes.php |  | Andrew Galuski | 2014-02-15 |  |  |
| Fix PO_SelectOSPurchOrder.php date selection functionality |  | Vitaly | 2014-02-15 |  |  |
| Fixed POSDataCreation script to send all current prices in the POS currency including debtorno info so the POS can now deal with customer specific prices correctly. |  | Phil | 2014-02-15 |  |  |
| WorkOrderStatus.php now shows requirements for multiple parent item works orders. |  | Andrew Galuski | 2014-02-15 |  |  |
| WOSerialNos.php - added filter number format on quantity |  | Andrew Galuski | 2014-02-12 |  |  |
| Fixed the Earliest date calculation when is over Friday cutoff Time in DateFunctions.inc. |  | Exson | 2014-02-12 |  |  |

## [v4.11.3] - 2014-02-08

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Prevented GL posting to control accounts from Payments.php and CustomerReceipts.php where the configuration option to stop postings to these accounts is set. |  | Phil | 2014-02-08 |  |  |
| Used Tim's code as the basis to allow deletion of internal stock request items. |  | Phil/Tim | 2014-02-08 |  |  |
| Geocode integration geocode with google API v3. You need to get a API Key from google. The parameters to geocode must be: geocode key: your API key geocode lat: your lat geocode long: your long geocode height: 100 geocode width: 100 host geocode: maps.googleapis.com |  | Eduardo Marzolla | 2014-02-06 |  |  |
| Payments.php - correct javascript to prevent error popup on every manual GL code entry, correct spacing. |  | icedlava | 2014-02-06 |  |  |
| BankMatching.php - correct column heading order. |  | icedlava | 2014-02-06 |  |  |
| SalesTopCustomersInquiry.php - shows top customers over specified date range showing invoices/returns and net sales |  | Tim | 2014-02-04 |  |  |
| Fixed installer now allows characters in the database name |  | Exson | 2014-02-03 |  |  |
| Fix bug in Payments.php that was duplicating payments for mutliple GL analysis entries. Now just a single bank trans is created for the payment which can be analysed to any number of GL accounts |  | Phil | 2014-02-03 |  |  |
| Profit and Loss format fixed for detailed - don't show zero balances accounts |  | Andrew Galuski | 2014-01-31 |  |  |
| Added sequence field to BOMs.php |  | Muthu | 2014-01-31 |  |  |
| Updated pt_BR and also applied to pt_PT translation since pt_PT translation was quite old |  | Gilberto dos santos alves | 2014-01-30 |  |  |
| Update the hold reason table default data dissallowinvoice to make customers' credit on watch is workable. |  | Exson | 2014-01-28 |  |  |
| Display serial items on GRN printouts |  | Andrew Galuski | 2014-01-24 |  |  |
| Fixed incompatible error traps on hyphens between new customers and new branches. Now both allow hyphens. Also prevented deletion of the last customer branch. Customer branches are now deleted when a customer is attempted to be deleted provided there are no dependent records. |  | Phil | 2014-01-23 |  |  |
| Add sales man login control and modify the PDF to download to harmony with other files and solve backward failure problem in PrintCustStatements.php. |  | Exson | 2014-01-19 |  |  |
| Change property name value to uppercase to match use in the script. [causing input loss] |  | Paul | 2014-01-18 |  |  |
| Fixed sql strict mode failure problem in StockTransfers.php by adding a '' to qualitytext fields. |  | Exson | 2014-01-16 |  |  |
| PO_SelectOSPurchOrder.php now allows selection of purchase orders based on order dates |  | Tim | 2014-01-23 |  |  |
| System would go to get currency rates even though they were set to manual - bug fixed. |  | Tim | 2014-01-14 |  |  |
| Fixed bug that was not recording the standard cost against goods received - this would put all accounting out for both standard and weighted average journals. |  | Phil | 2014-01-14 |  |  |
| Fixed SuppInvGRNs.php price variance was not calculated correctly because cost not brought accross correctly as reported by Don Grimes |  | Phil | 2014-01-13 |  |  |
| Fixed that no bank accounts recorded in gl in CounterReturns.php |  | CQZ | 2014-01-11 |  |  |
| Apply Tim's bug report regarding conversion of database name to lower case in ConnectDB.inc Although uppercase characters should not be included in database names, removing this trap allows backward compatibility with users who did install with upper case database name |  | Phil/Tim | 2014-01-06 |  |  |
| Add option to create CSV from inventory valuation rather than create PDF |  | Phil | 2014-01-04 |  |  |
| Fixed php-mbstring extension detection failure in install/index.php during installation. |  | Exson | 2014-01-02 |  |  |
| Fixed the stock location will loss problem when move to StockAdjustmentsControlled.php interface in StockAdjustments.php. |  | Thumb | 2013-12-27 |  |  |
| Fixed the controlled items cannot be removed due to the negative operator is modified and balance of a serial no is wrong due negative operator is missing during credit controlled items by KEYED method. Other input method has not be inspected. |  | Exson | 2013-12-23 |  |  |
| Add a fool-proof to Credit_Invoice.php to prevent an invoice was credit again and again. |  | Exson | 2013-12-22 |  |  |
| Modify the stock select element to a combox box which autocomplete the limited stock ID options to 300 in PriceMatrix.php. Otherwise, users have to input an stock ID themselves. To avoid a too long stock list as pointed by Tim. |  | Exson | 2013-12-22 |  |  |
| Add price matrix features. Modified MainMenuLinksArray.php, GetPrice.inc and add pricematrix table and PriceMatrix.php |  | Exson | 2013-12-21 |  |  |
| Salesman can only review his own customer's data |  | Thumb | 2013-12-20 |  |  |
| Extended smtp user name to varchar(50) as sometimes a full email address is required - tidied the script a bit too. |  | Phil / Gilberto dos santos alves | 2012-12-14 |  |  |
| Fixed bug that calculated the wrong StandardCost of assembly parts in Credit_Invoice.php and SelectCreditItems.php. Bug confirmed by Phil. |  | Thumb | 2013-12-11 |  |  |
| Fixed bug that using limit without offsetting in PO_Items.php,WorkOrderEntry.php and make users' DisplayRecordsMax effective in SelectOrderItems.php.And fixed typo in SelectCreditItems.php. |  | Thumb | 2013-12-11 |  |  |
| Fixed the typo of sql in DiscountCategories.php. Report by tangjun |  | Exson | 2013-12-09 |  |  |
| Allow entry of stock counts by stock category |  | Phil | 2013-12-07 |  |  |
| Fixed htmlMimeEmail.inc following Tim's submission - removed & value by reference errors |  | Phil | 2013-12-07 |  |  |
| Alter table stockmoves reference to varchar(100) to make it can meet mysql strict mode requirements when data is more than original 40. |  | Exson | 2013-12-04 |  |  |
| Fixed the typo in WWW_Users.php. Reported by Thumb. |  | Exson | 2013-12-02 |  |  |
| Fixed country Chinese sql for installation failure. |  | Exson | 2013-12-01 |  |  |
| Correct Chinese translation error dispatch cut_off time. |  | CQZ | 2013-12-01 |  |  |

## v4.11.2 - 2013-12-01

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Added user permissions by bank account so only certain users can see and create payments/receipts on bank accounts selected. |  | Ricard | 2013-11-30 |  |  |
| Includes supplier-code, currency-code, and currency-name-from-CurrenciesArray.php in SelectSupplier.php. |  | rchacon | 2013-11-30 |  |  |
| Add salesman constraint to show salesperson's own sales orders invoice, customer etc in DailySalesInquiry.php,PDFDeliveryDifferences.php,PDFOrdersInvoiced.php,PDFOrderStatus.php,PDFPickingList.php,SalesByTypePeriodInquiry.php,SalesInquiry.php,SelectCompletedOrder.php. |  | Thumb | 2013-11-30 |  |  |
| Apply Tim's idea for stripping slashes from incorrectly displayed items PO_Items.php and DeliveryDetails.php |  | Phil | 2013-11-28 |  |  |
| Add salesman constraints to ConfirmDispatch_Invoice.php to ensure that sales can only print his own sales orders' invoice. |  | Thumb | 2013-11-28 |  |  |
| Add constraints to salesman that he can only print his own sales orders in PrintCustOrder_generic.php |  | Thumb | 2013-11-28 |  |  |
| Add constraints to salesman that he can only print his own sales orders in PrintCustOrder.php |  | Thumb | 2013-11-28 |  |  |
| Add salesman login constraint to only their own customers available in SelectOrderItems.php and fixed SQL error of customers login. |  | Thumb | 2013-11-28 |  |  |
| Add create new scripts to import Customers and Debtors. |  | Thumb | 2013-11-27 |  |  |
| Supplier invoice entry now allows modification of invoice quantities and prices for multiple goods received lines in line rather than having to go into each line to modify individually. |  | Phil | 2013-11-26 |  |  |
| Translate the name of each language to the name in their respective language. |  | rchacon | 2013-11-20 |  |  |
| Payments.php FunctionalExchangeRate was not defaulted appropriately when entering a supplier payment in FX from a bank account of the same currency selected and the transaction was posted immediately without update first. Fixed |  | Phil | 2013-11-20 |  |  |
| Add webERP Chinese country sql including Chinese COA, currency, role,tax, transaction type etc which should be localized. |  | Thumb/CQZ | 2013-11-19 |  |  |
| Add '-' to telephone no pattern in CustomerBranches.php and WWW_Users.php. |  | Thumb | 2013-11-19 |  |  |
| Correct translation of customer text and customer code in CustomerReceipt.php of locale file. |  | Thumb | 2013-11-19 |  |  |
| Text 'settled transaction' position adjusted to proper position in PrintCustStatements.php. |  | Thumb | 2013-11-19 |  |  |
| Make inventory it as default to show inventory serial no in ConfirmDispatchControlled_Invoice.php. |  | Exson | 2013-11-18 |  |  |
| Make company name client side requirements consistent with server side in CompanyPreferences.php. |  | Exson | 2013-11-18 |  |  |
| Improves translation and format in PaymentMethods.php. |  | rchacon | 2013-11-16 |  |  |
| MacPhotoBiker reported shipment charges html5 type=number removed to use the class=number javascript' |  | Phil | 2013-11-16 |  |  |
| Allow translation of the subkey name in FormDesigner.php. |  | rchacon | 2013-11-12 |  |  |
| Allow translation of the key name in FormDesigner.php. |  | rchacon | 2013-11-07 |  |  |
| Add check box to allow user to decide weather raw material is sellable or not. |  | Exson | 2013-11-07 |  |  |
| Revise the bin definition to NOT NULL DEFAULT '' as suggest by Tim to make it more ISO compatible. |  | Exson | 2013-11-07 |  |  |
| Allow multiline printing of salesorderdetails.narrative in quotations. |  | rchacon | 2013-11-06 |  |  |
| Fixed the warning error in GLAccountInquiry.php add change variable type to array to make min() and max() reasonable. Reported by Jo |  | Phil | 2013-11-05 |  |  |
| Change insert new clone stock event to transaction as in Stocks.php for new item. |  | icedlava | 2013-11-04 |  |  |
| Allow translate the name of the currency on CompanyPreferences.php. |  | rchacon | 2013-11-03 |  |  |
| Fixed the bug that discount id for category cannot be set and add an error message when there is no stockid set for the respective category. |  | Exson | 2013-11-03 |  |  |
| Fixed bug by removing pattern and add no-illegal-chars to stockid in StockReorderLevel.php.xed bug in MiscFunctions.js allow '0' input as number. |  | Exson | 2013-11-03 |  |  |
| Allow translate the name of the currency on Currencies.php. |  | rchacon | 2013-11-01 |  |  |
| Allow translate the name of the currency on CustomerReceipt.php and Payments.php. |  | rchacon | 2013-10-31 |  |  |
| Allow insert different data on banktrans.ref and gltrans.narrative for the bank account on CustomerReceipt.php. Match the page_title_text with the MainMenuLinksArray option for Bank Account Payments Entry and Bank Account Receipts Entry. Regroup the General Ledger Transactions menu. |  | rchacon | 2013-10-30 |  |  |
| Add required attribute for Z_MakeNewCompany.php to avoid file void error and make it more user friendly. |  | Exson | 2013-10-30 |  |  |
| Modify the locstock table change the bin to NULL to avoid stick sql standard constraint failed for those items without bin. |  | Exson | 2013-10-30 |  |  |
| Modify the the insert new stocks event to transaction. |  | Exson | 2013-10-30 |  |  |
| MailingGroupMaintenance.php, minor tag and other formatting corrections. |  | TurboPT | 2013-10-24 |  |  |
| Add StockClone.php script to create a new item with the same properties, image, cost, purchasing and pricing data as the selected item, and allow modification of image and general item details before cloning. |  | icedlava | 2013-10-20 |  |  |
| ManualSecuritySchema.html, add missing tr tags, reduced doubled-closing td tags to one, and changed & to &amp; for HTML. |  | Paul | 2013-10-18 |  |  |
| ManualInventory.html, add bracket to complete closing h3 tag. |  | Paul | 2013-10-18 |  |  |
| GoodsReceived.php, change variable name from OrderNumber to OrderNo. |  | Paul | 2013-10-15 |  |  |
| Links for manual internal transfers and supplier payment link to allocations |  | Tim | 2013-10-11 |  |  |
| Commit the fixed "Unable to Locate Purchase Order Number" error when the PO is created by SO interface. Fixed provided by Tim and reported by Merci from webERP forum. |  | Exson/Tim | 2013-10-09 |  |  |
| New script to show a grid of items by preferred supplier for placing purchase orders to the users's default inventory location - orders will be authorised if the user has authority and the auto-authorise config option is enabled. |  | Phil | 2013-10-06 |  |  |
| PO_Items.php with non-stock items still require GL Code in case of modified order at invoice time else SQL error is generated due to invalid GL Code. |  | icedlava | 2013-10-03 |  |  |
| Added new field url to suppliers modified SelectSupplier.php and Suppliers.php |  | David Lynn | 2013-10-02 |  |  |
| Help with regular expression to trap quotes and backslashes for data-type="no-illegal-chars" |  | wh_hsn | 2013-09-28 |  |  |
| Followed Exson's example to set pattern to prevent dodgy characters in other scripts that were using a pattern that only allowed [a-zA-Z0-9] thus making it impossible to enter non latin characters. |  | Phil | 2013-09-28 |  |  |
| SelectCompletedOrder.php Fix SQL typo. |  | icedlava | 2013-09-11 |  |  |
| Using javascript to set the pattern attribute based on a new attribute data-type and first script Stocks.php |  | Exson | 2013-09-07 |  |  |
| Fix PrintStatements.php to allow selection of alphanumeric customer codes in length to match database definition. |  | icedlava | 2013-09-07 |  |  |
| StockStatus.php Allow dash in stock code again. |  | icedlava | 2013-09-07 |  |  |

## v4.11.1 - 2013-09-06

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Typo in ConfirmDispatch_Invoice preventing invoicing - changed patterns to remove {1,20} statements which make the pattern fail to validate. SelectProduct removed pattern from keyword search on description to allow entry of any characte as reported by Brian May |  | Phil | 2013-09-06 |  |  |
| Stocks.php Set error message for upload image failure when no upload tmp directory set in php. |  | icedlava | 2013-09-05 |  |  |
| Stocks.php Clear item image for new item creation. |  | icedlava | 2013-09-05 |  |  |
| Suppliers.php regex pattern for email, also not all suppliers have email. |  | icedlava | 2013-09-05 |  |  |
| Fixed the undefined StockID error and make it html5 compatible and table sorting in StockReorderLevel.php |  | Exson | 2013-09-04 |  |  |
| SelectOrderItems.php fix frequently ordered items to accept entry as was not working. |  | icedlava | 2013-09-02 |  |  |
| inc tax corrected to ex tax on ConfirmDespatch.php and DeliveryDetails.php |  | icedlava | 2013-09-02 |  |  |

## [v4.11.0] - 2013-09-01

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fixed the pattern typo error in StockCategories.php. |  | Exson | 2013-08-29 |  |  |
| Fixed html tag mark error and make it html5 compatible and table sortable in StockStatus.php |  | Exson | 2013-08-28 |  |  |
| Fixed MiscFunctions.js bug which make onclick event failed for tag whose class is date, number or integer. Solution from Tim. And update the sql file in coa directory. |  | Exson | 2013-08-28 |  |  |
| Remove the option of weberp-demo.sql to avoid installation error. Reported by icedlava. |  | Exson | 2013-08-27 |  |  |
| Allow display of BOM component entry screen after deletion of component in BOM.php |  | icedlava | 2013-08-27 |  |  |
| Fix rounding error in supplier unit price when less than 1 |  | icedlava | 2013-08-27 |  |  |
| Add A default company name to make the installation as dummy as possible. |  | Exson | 2013-08-26 |  |  |
| Fix COGS and Sales GL interface to include AN area and specific Sales Type and Stock Category. |  | icedlava | 2013-08-26 |  |  |
| Fixed the object no definition error in StockTransfers.php. |  | Thumb | 2013-08-21 |  |  |
| Fixed the notice error of undefined index Status and and space for some string. |  | Exson | 2013-08-21 |  |  |
| Fixed the sortable not workable bug in Prices.php. |  | Exson | 2013-08-21 |  |  |
| Change sortable block from table to tbody. Reported by Tim. |  | Exson | 2013-08-21 |  |  |
| Make StockUsage.php html5 compatible and table sortable. |  | Exson | 2013-08-20 |  |  |
| html5 compatible and table sortable for SuppContractChgs.php,SuppCreditGRNs.php,SuppFixedAssetChgs.php,SuppInvGRNs.php, SupplierAllocations.php |  | Exson | 2013-08-20 |  |  |
| Make SupplierInquiry.php table sortable |  | Exson | 2013-08-20 |  |  |
| SupplierContacts.php html5 compatible and table sortable. |  | Exson | 2013-08-20 |  |  |
| Make SupplierCredit.php html5 compatible. |  | Exson | 2013-08-20 |  |  |
| Use manually instead of manual to make distinguish translation possible and make it html5 compatible in SupplierInvoice.php. |  | Exson | 2013-08-20 |  |  |
| Fixed the typo that should be class integer instead of type integer. Reported by Tim. |  | Exson | 2013-09-19 |  |  |
| Fixed the account code pattern to make it compatible with the definition in GLAccounts.php |  | Exson | 2013-08-19 |  |  |
| Fixed the type error to class is integer. Reported by Tim. |  | Exson | 2013-08-19 |  |  |
| Fixed blank validation error in Suppliers.php and make it html5 compatible. |  | Exson | 2013-08-19 |  |  |
| Add warning messages for no suppliers returned in SupplierTenderCreate.php and make it html5 compatible and table sortable. |  | Exson | 2013-08-19 |  |  |
| Make SupplierTenders.php html5 compatible and sortable. |  | Exson | 2013-08-19 |  |  |
| Fixed the validation error which allow blank supplier type and make it html5 compatible and sortable. |  | Exson | 2013-08-19 |  |  |
| Make SuppLoginSetup.php html5 compatible. |  | Exson | 2013-08-19 |  |  |
| Remove redundant Ref no and make SuppPaymentRun.php html5 compatible. |  | Exson | 2013-08-16 |  |  |
| Make the SuppShiptChgs.php html5 compatible. |  | Exson | 2013-08-16 |  |  |
| Fixed the account code max length in SuppTransGLAnalysis.php and make it html5 compatible. |  | Exson | 2013-08-16 |  |  |
| Modify 'manual' to 'manually' in SystemParameters.php to remove the translation frustration and make it html5 compatible. |  | Exson | 2013-08-15 |  |  |
| Make TaxAuthorities.php html5 compatible and table columns sortable. |  | Exson | 2013-08-15 |  |  |
| Make TaxAuthorityRates.php html5 compatible. |  | Exson | 2013-08-15 |  |  |
| Make the tax category 'Freight' undeleted in TaxCategories.php and make it html5 compatible and table sortable. |  | Exson | 2013-08-15 |  |  |
| Make TaxGroups.php html5 compatible. |  | Exson | 2013-08-15 |  |  |
| Make TaxProvinces.php html5 compatible. |  | Exson | 2013-08-15 |  |  |
| Fixed the no-number warning in TopItems.php and make it html5 compatible. |  | Exson | 2013-08-15 |  |  |
| Make UnitsOfMeasure.php html5 compatible. |  | Exson | 2012-08-14 |  |  |
| html5 compatible for UserSettings.php |  | Exson | 2013-08-14 |  |  |
| Make table in WorkCentres.php sortable. |  | Exson | 2013-08-14 |  |  |
| Make WhereUsedInquiry.php Html5 compatible. |  | Exson | 2013-08-14 |  |  |
| Make WorkCentres.php html5 compatible. |  | Exson | 2013-08-14 |  |  |
| HTML5 compatible for WorkOrderEntry.php. |  | Exson | 2013-08-14 |  |  |
| Fixed the in_array() warning in WorkOrderIssue.php and make it html5 compatible. |  | Exson | 2013-08-14 |  |  |
| html5 compatible for WorkOrderReceive.php |  | Exson | 2013-08-14 |  |  |
| Html5 compatible |  | Exson | 2013-08-14 |  |  |
| Fixed INSERT sql errors and href link error and make it html5 compatible in WOSerialNos.php. |  | Exson | 2013-08-14 |  |  |
| Update Manual - Using the webERP Installer - rework of some text due to new installer, and updated screen shots |  | icedlava | 2013-08-03 |  |  |
| Obfuscate database name in login, do not show company in login if directory/company not wanted. |  | icedlava | 2013-08-03 |  |  |
| Profit and loss now has option to show all accounts and by default just shows those with a balance |  | Serge GÃ©linas | 2013-07-29 |  |  |
| Hard code the page title in Installer html, previously missing - no language strings available at this time - used English |  | icedlava | 2013-07-29 |  |  |
| Installer - strip inline styles, add html5/css to pretty up, fix small typo/bugs and spelling/grammar. No logic changes. |  | icedlava | 2013-07-28 |  |  |
| Allow ampersands in company name eg Matthew & Sons Ltd |  | icedlava | 2013-07-28 |  |  |
| Move Supplier Contact Detail review link to top of page away from Delete button |  | icedlava | 2013-07-26 |  |  |
| Fix Form Designer xml for GL Journal |  | icedlava | 2013-07-26 |  |  |
| Fix PO PDF Preview when $result returns Bool in some environments. |  | icedlava | 2013-07-26 |  |  |
| Balance sheet now has option to show all accounts and by default just shows those with a balance |  | Serge GÃ©linas | 2013-07-25 |  |  |
| Fixed that empty password should be allowed. Reported by Tim. |  | Exson | 2013-07-24 |  |  |
| Add the html5 mark for some input fields in WWW_Users.php. |  | Exson | 2013-07-24 |  |  |
| Fixed the document.form not defined error in GLJournal.php. |  | Exson | 2013-07-22 |  |  |
| Fixed the onchange overwritten problem for number class in MiscFunctions.js. Reported by Tim for GLJournal.php lost Credit and Debit mutually exclusive feature. |  | Thumb | 2013-07-22 |  |  |
| Allowed the input of numeric format like .5 in MiscFunctions.js rLocalNumber() function. Reported by Tim. |  | Exson | 2013-07-21 |  |  |
| Fixed the keyChar control to avoid backspace mistake. |  | Exson | 2013-07-21 |  |  |
| Fixed the number class function in MiscFunctions.js prevent character 0 from input for integer. Reported by Icedlava |  | Exson | 2013-07-21 |  |  |
| Added Tim's javascript as modified a bit to use different styles to allow sorting of tables - images for styles to all themes and added th.ascending th.descending to theme/default.css scripts |  | Phil | 2013-07-20 |  |  |
| Swag of scripts updated for html5 `<input type="tel" "email" pattern required="required" autofocus="autofocus">`, also added many title="" tooltips |  | Phil | 2013-07-19 |  | [Wiki](http://www.weberp.org/wiki/TransitionToHtml5) |
| Add required to the input field of WWW_Access.php |  | Exson | 2013-07-19 |  |  |
| Make the WWW_Access.php html5 compatible |  | Exson | 2013-07-19 |  |  |
| Move the hidden input after the html document type definition. Reported by Tim. |  | Exson | 2013-07-18 |  |  |
| Fixed the LanguageSetup.php extra language mark introduced by handle locale number. Reported by Tim. |  | Exson | 2013-07-18 |  |  |
| Fixed the missing negative mark problem reported by Tim. |  | Exson | 2013-07-18 |  |  |
| Add a locale style check to pass the style to MisFunctions.js to do locale number format validation in LanguageSetup.php. |  | Exson | 2013-07-18 |  |  |
| Modify the MisFunctions.js to improve the locale number validation feature. |  | Exson | 2013-07-18 |  |  |
| Stripslashes on Description for consistency with LongDescription but why- only until we fix source of this problem in the code |  | icedlava | 2013-07-17 |  |  |
| Add checkbox option in UI to clear item image |  | icedlava | 2013-07-17 |  |  |
| Add space bar as a accepted charcode for number input in MiscFunctions.js |  | Exson | 2013-07-17 |  |  |
| Add date check on purchase price effective date. |  | icedlava | 2013-07-16 |  |  |
| AllowCompanySelectionBox comparison fix to allow boolean value to evaluate correctly |  | icedlava | 2013-07-16 |  |  |
| Add new directory for new installer and add those default installation sql file. |  | Exson | 2013-07-14 |  |  |
| Rewrite the installer by removing the save.php file and revise the index.php file. |  | Exson | 2013-07-14 |  |  |
| webSHOP ShopParameters.php script and new configuration variables to allow integrated shop |  | Phil | 2013-06-24 |  |  |
| ContractCosting.php fix references to contract issues object which should just have been an array. |  | Phil | 2013-06-21 |  |  |
| Reworked the display of stock category properties on the SelectProduct.php inquiry |  | Phil | 2013-06-18 |  |  |
| Z_ImportGLTransactions.php new script for importing GL payments, receipts or journals from a CSV file |  | Tim | 2013-06-18 |  |  |
| Fix sql query of goods received when a serial number is already present. GoodsReceived.php |  | Paul Harness | 2013-06-17 |  |  |
| SelectSupplier - improve handling if single supplier selected |  | tomglare | 2013-06-12 |  |  |
| CountriesArray.php now uses the index as the ISO 2 character code for the country. |  | Phil | 2013-05-25 |  |  |
| Currencies.php now takes advantage of Rafael's new CurrenciesArray - to ensure correct ISO 3 letter abbreviation is selected - also allows for translation of currency names |  | Phil | 2013-05-25 |  |  |
| New include/CurrenciesArray.php listing all ISO currencies and their code |  | Rafael ChacÃ³n | 2013-05-25 |  |  |
| include/PO_PDFOrderPageHeader.inc now allows different length labels for date and intiator without running into the field data - needed for translations of different lengths |  | Rafael ChacÃ³n | 2013-05-22 |  |  |
| Stop session.inc execution when there is no config.php found. |  | Exson | 2013-05-18 |  |  |
| ConfirmDispatch_Invoice.php -include qty already invoiced in order when cancelling any balance on subsequent deliveries. |  | Phil | 2013-05-17 |  |  |
| Add patch for the email groups are set situation in PDFChequeListing.php, MailSalesReport.php, MailSalesReport_csv.php, OffersReceived.php. |  | Tim | 2013-05-12 |  |  |
| Add patch for GetMailList to show error when there is no email settings available. |  | Tim | 2013-05-12 |  |  |
| Make smtp mail available for StockLocTransferReceive.php. |  | Exson | 2013-05-12 |  |  |
| Make the smtp mail available for StockAdjustments.php. |  | Exson | 2013-05-12 |  |  |
| Fixed the bug of not use strpos correctly in PO_PDFPurchOrder.php. |  | Exson | 2013-05-12 |  |  |
| Make smtp mail available for InternalStockRequestFulfill.php. |  | Exson | 2013-05-12 |  |  |
| Fixed the stockrequestitems duplicated primary key bug by modify the primary definition. |  | Exson | 2013-05-12 |  |  |
| Make the smtp mail available in InternalStockRequest.php. |  | Exson | 2013-05-12 |  |  |
| Make the SMTP mail available for UserLogin.php. |  | Exson | 2013-05-12 |  |  |
| Make SMTP mail available for EmailConfirmation.php. The scripts seem not ready. |  | Exson | 2013-05-12 |  |  |
| Add smtp mail to DeliveryDetails.php. |  | Exson | 2013-05-12 |  |  |
| Make the SMTP mail available for CounterSales.php to mail new WO. |  | Exson | 2013-05-12 |  |  |
| Make the sales report can be mailed via SMTP in files MailSalesReport.php and MailSalesReport_csv.php |  | Exson | 2013-05-12 |  |  |
| Make EDI modules can send mail via SMTP in files EDIProcessOrders.php, EDISendInvoices.php, EDISendInvoices_Reece.php |  | Exson | 2013-05-11 |  |  |
| Make the inventory valuation report can be mailed. |  | Exson | 2013-05-11 |  |  |
| Add Mail Validation Report to inventory module in MainMenuLinksArray.php. |  | Exson | 2013-05-11 |  |  |
| Revise the CURDATE() to CURRENT_DATE to make it a more general SQL compatible in OffersReceived.php. Recommend by Tim. |  | Exson | 2013-05-11 |  |  |
| Spanish translation update |  | Rafael ChacÃ³n | 2013-05-12 |  |  |
| Add OffersRecievedResultRecipients group to mailgroups in ugrade4.10-4.11.sql/mysql/upgrade4.10-4.11.sql |  | Exson | 2013-05-11 |  |  |
| Add OffersReceivedRecipients Group, added feature to use mail instead of smtp mail only, fixed the problem that item with single quotation mark cannot be stored and the same date comparison problem. |  | Exson | 2013-05-11 |  |  |
| Add MailSalesReport_csv to scripts and add SalesAnalysisReportRecipients for mailing list in sql/mysql/upgrade4.10-4.11.sql |  | Exson | 2013-05-11 |  |  |
| Make the report can be sent via smtp mail in MailSalesReport_csv.php. |  | Exson | 2013-05-11 |  |  |
| Add Cc and Reply-To feature for mail sent by smtp in DefineTenderClass.php. |  | Exson | 2013-05-11 |  |  |
| Make the offer can be mailed via smtp in DefineOfferClass.php. |  | Exson | 2013-05-11 |  |  |
| Make the tender can be sent by SMTP mail in DefineTenderClass.php. |  | Exson | 2013-05-11 |  |  |
| Fixed the Z_CreateCompanyTemplateFile.php to make it workable in windows OS and make it workable via smtp mail. |  | Exson | 2013-05-10 |  |  |
| Fixed the report_runner.php to make it can be sent via smtp. |  | Exson | 2013-05-10 |  |  |
| Modify RecurringSalesOrdersProcess.php to make it can send the order by smtp. |  | Exson | 2013-05-10 |  |  |
| Fixed the mail function for PDFChequeListing.php |  | Exson | 2013-05-10 |  |  |
| Add GetMailList function in MiscFunctions.php. |  | Exson | 2013-05-10 |  |  |
| Fixed the unclosed a href tag in MailingGroupMaintenance.php. |  | Exson | 2013-05-10 |  |  |
| Add new feature to main mail list group for mail sending purpose. |  | Exson | 2013-05-10 |  |  |
| Fixed the date hard coded problem in PcAssignCashToTab.php which lead to malfunction for some date format. Report by thumb. |  | Exson | 2013-05-09 |  |  |
| New script to change GL account codes Z_ChangeGLAccountCode.php and SQL to upgrade to varchar account codes |  | Ricard | 2013-05-03 |  |  |
| Fixed strpos error and make the smtp server name more generic when user not use a email address in function of SendmailBySmtp in MiscFunctions.php. |  | Exson | 2013-05-02 |  |  |
| Credit_Invoice.php was not setting the selected location to credit into as reported by Ricard |  | Phil | 2013-05-02 |  |  |
| Revise the account code to 20 reported by Tim in GLAccounts.php. |  | Exson | 2013-05-02 |  |  |
| Make correction for removing those functions not related with webERP committed last time in MiscFunctions.php. Thanks for Tim's review. |  | Exson | 2013-05-02 |  |  |
| Make smtp available for PDFDeliveryDifferences.php. |  | Exson | 2013-05-02 |  |  |
| Make smtp mail available for PDFDIFOT.php and fixed the bug that the result should be data within acceptable days instead of out of the range. |  | Exson | 2013-05-02 |  |  |
| Make the smtp mail available for PrintCustTransPortrait.php |  | Exson | 2013-05-02 |  |  |
| Add SendmailBySmtp function to MiscFunctions.php and make smtp mail workable for PrintCustTrans.php. |  | Exson | 2013-05-02 |  |  |
| GLAccounts.php change error trap that only allows numeric GL accounts as now the chart of accounts can contain text accounts |  | Ricard | 2013-05-01 |  |  |
| Fix purchase order lead time calculation as reported by MacPhotoBiker on forum |  | Phil | 2013-05-01 |  |  |
| Add link to print purchase order after placement as suggested by MacPhotoBiker on forum |  | Phil | 2013-05-01 |  |  |
| Update the translation file for Chinese simplify locale |  | Exson | 2013-04-30 |  |  |
| Fixed the bug in SystemParameters.php which missing a `</select>` tag and cannot display the config in $_SESSION correctly. Reported by Tim. |  | Exson | 2013-04-30 |  |  |
| Create a new sql file 4.10-4.11.sql with the smtp setting statement. |  | Exson | 2013-04-30 |  |  |
| Modify the PO_PDFPurchOrder.php to make it suitable for smtp setting. |  | Exson | 2013-04-29 |  |  |
| Fixed the smtp does not work for langaguage is change in utf8 code in file of smtp.php and HTMLMimeMail.php. |  | Exson | 2013-04-29 |  |  |
| Fixed the db_free_result() error messages due to the query returned a boolean value in SMTPServer.php |  | Exson | 2013-04-29 |  |  |
| Fixed a bug in PO_Items.php when a line of a purchase order is deleted (other than the last line) and then subsequently a new line is added - the last line of the order is over-written. |  | Samudaya | 2013-04-27 |  |  |
| PO_Items.php When purchasing a non-stock item (asset), AssetID goes to wrong column in purchorderdetails table (Column name - suppliers_partno). Fixed the bug and now save the AssetID in the correct assetid column. |  | Samudaya | 2013-04-27 |  |  |
| Fixed the Page Navigation (Go, Previous, Next) problem. This problem occurs there are many assets and display as several pages. |  | Samudaya | 2013-04-27 |  |  |
| Fix the image broken problem for Manual/ManualGettingStarted.html Chinese version |  | Exson | 2013-04-26 |  |  |
| StockLocStatus.php was not showing purchase orders with status of printed |  | Phil | 2013-07-19 |  |  |
| Selecting customer in Contracts form was not working - fixed |  | Tim | 2013-04-25 |  |  |
| WorkOrderIssue.php was not showing the serialised items with a quantity that could be issued was showing them all in error |  | Bob Thomas | 2013-04-25 |  |  |
| BOMs.php fixed error that allowed auto issue to be flagged on serialised items |  | Phil | 2013-04-25 |  |  |
| Rework includes/GLPostings.inc to avoid incorrect b/fwd balances on posting back to a period which did not previously exist. |  | Phil | 2013-04-25 |  |  |
| Reported by Bob Thomas - BOMExtendedQty.php was missing purchase orders with status='Authorised' or Printed |  | Phil | 2013-04-19 |  |  |
| Credit_Invoice.php missing $identifier in link causing details of credit note to be lost |  | Tim | 2013-04-18 |  |  |
| Audit trail was not being purged if DB Maintenance was turned off and it should be pruned daily. |  | Ricard | 2013-04-16 |  |  |
| Fixed the bug of Y-m-d date format error in MiscFunctions.js (this date type is missing) which will display wrong date in Work Order. |  | Thumb | 2013-04-16 |  |  |
| PDFPriceList.php split long description to maximum of 132 characters long |  | Rafael | 2013-04-06 |  |  |
| Correct includes/LanguagesArray.php to use correct decimal point and thousands separator |  | Kalmer Piiskop | 2013-04-01 |  |  |
| Updates to editing tenders and button to close a tender |  | Fahad Hatib | 2013-03-27 |  |  |
| Updated Estonian translation |  | Kalmer Piiskop | 2013-03-22 |  |  |
| CustomerReceipt.php Added GL tag name for GL analysis of receipts |  | Arwan | 2013-03-21 |  |  |
| Karel Van Der Esch discovered issues in PO_Items.php and Prices.php in relation to thousands and decimal separators now resolved. |  | Phil | 2013-03-23 |  |  |
| Added salesperson to debtortrans and now credit notes can have salesman allocated - previously the salesperson was only used for sales analysis reporting - but for those that need sales person against all transactions and want to drill into the detail it is necessary to have the salesperson recorded on each debtortrans - changed CounterSales.php CounterReturns.php ConfirmDispatch_Invoice.php SelectCreditItems.php Credit_Invoice.php defaulted for api functions |  | Phil | 2013-03-23 |  |  |
| CounterSales.php cancel order during item search failed - now fixed |  | Phil | 2013-03-21 |  |  |
| MT940 Bank transactions importing - allows importation of bank statements from the bank (for those banks that offer MT940 format statment exports) - other bank transaction export formats are easily accomodated |  | Phil | 2013-03-21 |  |  |
| Fixed Asset Maintenance tasks, user schedule of tasks due and email reminders to the maintainer and their supervisor when the task is overdue |  | Phil | 2013-03-21 |  |  |
| Remove the need to be logged in to see the manual |  | Fahad Hatib | 2013-03-21 |  |  |
| Only display those offers that have not gone past their expiry dates |  | Tim | 2013-03-06 |  |  |

## v4.10.1 - 2013-02-25

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| SalesGraph.php Fix syntax error, missing ; at end of line |  | Tim | 2013-02-24 |  |  |
| CustWhereAlloc.php Fix syntax error, bad indenting, and an extra } entered because of it |  | Tim | 2013-02-24 |  |  |
| Tidy up PDFSellThroughSupportClaim.php report |  | Phil | 2013-02-23 |  |  |
| Committed by Phil - added BookMarks and ViewTopic links in the General Ledger scripts and modifications to the general ledger manual to add these bookmarks. |  | Fahad Hatib | 2013-02-18 |  |  |
| Z_ImportPriceList.php to import a price list from a csv file |  | Tim | 2013-02-18 |  |  |
| Updated Brazilian Portuguese (pt_BR.utf8) translation |  | Gilberto Dos Santos Alves | 2013-02-15 |  |  |
| Corrected CRC flag |  | Rafael ChacÃ³n | 2013-02-15 |  |  |
| Fix bugs in labelling of fields in view customer details in Customers.php |  | Phil | 2013-02-13 |  |  |
| 12/2/13Fix of serialized transfer quantities/includes, StockTransferControlled.php |  | Paul Harness | 2013-02-12 |  |  |
| Committed by Phil - added Manual links for AR, Fixed Assets and AP manual sections |  | Fahad Hatib | 2013-02-11 |  |  |
| Added bin to locstock so the standard bin location for stock can be specified, can update from LocReorderlevel.php script of StockStatus.php - this prints on packing slips so dispatch people can see where the stock should be to pick it. Could also add to picking report and GRN receipt report. |  | Phil | 2013-03-10 |  |  |
| Refix wiki links |  | Phil | 2013-02-02 |  |  |
| Fix PDFTransPageHeader.inc landscape invoice printing |  | Phil | 2013-02-02 |  |  |
| Fixed up syntax error in InternalStockCategoriesByRole.php |  | Tim | 2013-02-02 |  |  |
| New Manual page for Supplier Tendering brought in from KwaMoja |  | Fahad Hatib | 2013-02-01 |  |  |
| Fix PDFTransPageHeader.inc landscape invoice printing |  | Phil | 2013-01-02 |  |  |
| includes/MiscFunctions.php fix wiki links |  | Tim | 2013-01-31 |  |  |
| Fix sql injection security hole in session.inc. |  | Tim | 2013-01-29 |  |  |

## v4.10.0 - 2013-01-27

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Sell Through Support scripts - allows setting up rebate for a customer/supplier/item combinations for a date range with a discount to apply. A claim report allows a listing of invoices/credits where these items have been sold accumulating the claim from the supplier. Claim report needs work. |  | Phil | 2013-01-27 |  |  |
| Supplier discounts/promotions can now be configured against supplier purchasing data with an effective date range. Purchase orders automatically accumulate and apply the discounts on placing orders. |  | Phil | 2013-01-27 |  |  |
| Various minor improvements from their fork Kwamoja |  | Tim/Fahad Hatib | 2013-01-27 |  |  |
| Phil brought in changes to date functions to allow for Y-m-d date formats and finished |  | Tim | 2013-01-27 |  |  |
| Provide facility for editing account group name in AccountGroups.php |  | Tim | 2013-01-27 |  |  |
| Automatically adjust config.php for variable name changes |  | Tim | 2013-01-27 |  |  |
| Manual updates for internal inventory transfer system |  | Fahad Hatib | 2013-01-27 |  |  |
| Use scandir to get an array of files/directories for neater code |  | Martha Njeri | 2013-01-10 |  |  |
| Prevent selection of assembly items to create purchase orders for... these are just selling devices like kits |  | Phil | 2013-01-06 |  |  |
| Fix wiki link for media wiki had extra / |  | Bob Thomas | 2013-01-04 |  |  |
| Corrections to various strings where English was suspect!! |  | Thomas Lie | 2013-01-02 |  |  |
| On log out destroy session and show the login form - avoiding nasty errors |  | Phil | 2012-12-29 |  |  |
| Proper casing of $Title $RootPath $Theme $DBType $DBUser $DBPassword $AllowDemoMode variables - may make upgrade tricky!! |  | Phil | 2012-12-29 |  |  |
| OutstandingGRNs.php now has show on screen option |  | Ricard | 2012-12-24 |  |  |
| Customer login selection of branch option removed unecessary fields from customer order placement |  | Phil | 2012-12-15 |  |  |
| Modified default.css for the default theme to use pt based default font and other font sizes based on this |  | Bob Thomas | 2012-12-15 |  |  |
| SelectOrderitems.php would not recognise Customer only logins correctly as there are two tokens in a Customer login role. and was testing to see if just one token! |  | Phil/RockStar | 2012-12-12 |  |  |
| Made CustomerLogin and SupplierLogin more intuitive - still rely on hard coded Security Tokens though. |  | Phil | 2012-12-11 |  |  |
| Reworked GLTransInquiry.php produced incorrect journals |  | Phil | 2012-12-11 |  |  |
| Remove hard coding of security tokens in favour of new OrderEntryDiscountPricing dummy script |  | Phil | 2012-12-10 |  |  |
| Check user has authority to receive goods before allowing auto receiving |  | Phil | 2012-12-08 |  |  |
| SupplierInvoice.php add link to pay invoice after invoice input |  | Phil | 2012-12-07 |  |  |
| Allow auto receiving of purchase orders and populating of purchase invoice when purchase order is authorised. |  | Phil | 2012-12-05 |  |  |
| PO_PDFPurchOrder.php fix printing of initiator to show the full name also style changes |  | Tim | 2012-12-05 |  |  |
| PO_Header.php style mods also initiator reinstated as the userid not the user's realname - realname displayed but not stored in db. |  | Tim | 2012-12-04 |  |  |
| Remove input fields in Customers.php email, phone fax which are not used - these fields do not exist in debtorsmaster to update these fields are only held by branch. |  | Phil | 2012-12-01 |  |  |
| SupplierInvoice.php and link from PO_Items.php to auto receive all items on an authorised purchase order and auto populate the invoice with the received items |  | Phil | 2012-12-01 |  |  |
| Start of a conversion to remove dependencies between company name, database table name, and the company file system. Particularly on shared virtual server environments, these can be unpredictable and out of the user's control. This should reduce the difficulties often reported by users with installation. |  | Jeff Trickett | 2012-11-29 |  |  |
| Updated German translation to SVN revision 5743 |  | Harald Ringehan | 2012-11-21 |  |  |
| SelectCreditItems.php allow selection of sales person and record credit against this salesperson in sales analysis |  | Phil | 2012-11-17 |  |  |
| Allow user to specify sales person to credit so that sales analysis reflects sales by sales person without recourse back to the default sales person for the customer branch |  | Phil | 2012-11-17 |  |  |
| Fixed Credit_Invoice.php to now use identifier to uniquely identify a credit note session |  | Phil | 2012-11-11 |  |  |
| Fixed links to send session identifier and whether from CreditInvoice or SelectCreditItems.php InputSerialItemsKeyed.php and CreditItemsControlled. Creating credit notes manually for serial numbered items was failing as reported by Bob Thomas |  | Phil | 2012-11-11 |  |  |
| Updated Japanese translation |  | Craig Craven | 2012-11-08 |  |  |
| Fixed BAD bug wtth CounterSales.php it was possible to make sales where the invoice didn't agree with the sum of the lines sold!! |  | Phil | 2012-11-03 |  |  |
| Added CounterReturns.php script to handle cash returns |  | Phil | 2012-11-03 |  |  |
| Added tag selection to purchase invoice gl analysis |  | Jesus Aguirre | 2012-11-03 |  |  |
| Improve GLPostings.inc just update all gltrans posted once the postings are done rather than a whole load of updates for each gltrans posted |  | David Short | 2012-11-03 |  |  |
| Added telephone and email to supplier search - SelectSupplier.php |  | Phil | 2012-10-25 |  |  |
| Rounding error prevented CounterSales.php from posting a sale where currency was rounding to 0 decimal places - reported by Arwan |  | Phil | 2012-10-25 |  |  |
| Fixed the problem of Authoriser drop down selection when try to edit a Internal Departments. |  | Samudaya Nanayakkara | 2012-10-20 |  |  |
| Fixed exchange rate trend using google based https://encrypted.google.com/finance/chart? |  | Phil | 2012-10-19 |  |  |
| Added google exchange rates option - many more rates than ECB published daily |  | Phil | 2012-10-18 |  |  |
| CustomerReceipt.php and Payments.php stripslashes |  | Icedlava | 2012-10-16 |  |  |
| SelectOrderItems.php and CounterSales.php $_POST['Prev'] modified to $_POST['Previous'] |  | Phil | 2012-10-16 |  |  |

## v4.09.1 - 2012-10-13

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Typo fox in FixedAssetLocations.php and FixedAssetTransfer.php added location to search for existing assets to transfer |  | Samudaya Nanayakkara | 2012-10-13 |  |  |
| Fix pagination/selection of order items in SelectOrderItems.php and CounterSales.php |  | Phil | 2012-10-12 |  |  |
| SelectAsset.php now goes to FixedAssetItems.php when an item is selected to display the asset for editing |  | Phil | 2012-10-12 |  |  |
| Updated CRC - Costa Rica flag image |  | Rafael ChacÃ³n | 2012-10-12 |  |  |
| Updated German translation |  | Harald Ringehan | 2012-10-10 |  |  |
| Fixed typo in CounterSales.php reported by thumb. |  | Exson | 2012-10-09 |  |  |
| Installation would not allow demo to be installed - install/index.php |  | Exson | 2012-10-09 |  |  |
| Fix WorkOrderIssue.php required by date |  | Phil | 2012-10-07 |  |  |

## v4.09 - 2012-10-06

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| WorkOrderIssue.php with multiple work order items was not calculating requirements correctly nor showing all the outputs of the work order with correct quantities received and ordered. Reworked the script to show a table of the output items and the sql to get the correct component requirements. |  | Samudaya Nanayakkara | 2012-09-27 |  |  |
| InternalStockRequestFulfill.php fixed quantity not filtered for number formatting. Phil tidy up coding conventions |  | Samudaya Nanayakkara | 2012-09-27 |  |  |
| Fixed SupplierPriceList.php units of measure were being looked up based on unit id but required matching against the unitname |  | Samudaya Nanayakkara | 2012-09-27 |  |  |
| Added primary keys, keys and foreign keys for stockinternalrequest and associated work as reported by Oscar M. Borja |  | Phil | 2012-09-26 |  |  |
| As per Tim's posting to forum in includes/PDFStockTransferHeader.inc use $_SESSION['LogoFile'] which is obtained in includes/session.inc - also in includes/PDFDIFOTPageHeader.inc |  | Phil | 2012-09-25 |  |  |
| ConfirmDispatch_Invoice.php now compares the total of orderlines that have an uninvoiced quantity to the number of lines in the session to ensure no one has invoiced out the order already. It used to compare the total number of lines on the order - but if there are zero lines then this doesn't work and the user is presented with the error message report. |  | Phil | 2012-09-25 |  |  |
| WorkOrderIssue.php - now shows all output items on the work order and sums the required component quantities as reported by Samudaya |  | Phil | 2012-09-25 |  |  |
| Some small corrections in the Manual, and update of bookmark links for some setup, petty cash and order functions. |  | Icedlava | 2012-09-12 |  |  |
| Removed all use of $_REQUEST across all scripts replace with GET/POST to cirumvent SQL injection attacks as reported by Daniel Compton |  | Phil | 2012-09-08 |  |  |
| Updated Indonesian translation |  | Thomas Timothy Lie / baliboss.com | 2012-09-06 |  |  |
| AccountGroups.php now has option to move to alternative accountgroups |  | Carlos Rubio | 2012-09-01 |  |  |
| Fixed sql problem reported bymodpr0be |  | Exson | 2012-08-30 |  |  |
| Added new fr_CA.utf8 French Quebec translation |  | Serge GÃ©linas | 2012-08-24 |  |  |
| Stock.php could not change an item to an assembly if there were any old completed or deleted/rejected purchase orders for the item. |  | Phil | 2012-08-21 |  |  |
| StockCounts.php now allows entry Bar Codes or stock codes |  | Phil | 2012-08-20 |  |  |
| Now allow labour type category items to be selected searched for in CounterSales.php and SelectOrderItems.php |  | Phil | 2012-08-20 |  |  |
| Fixed that the shipper will change sometime when modify the PO by modify file PO_ReadInOrder.inc. Reported by Craig. |  | Exson | 2012-07-30 |  |  |
| install/save.php now uses date_default_timezone_set function to set the time-zone to avoid all the nasty warnings |  | Phil | 2012-07-29 |  |  |
| Updates to Professional and Professional-rtl themes - tidy up images not used |  | Hindra Joshua | 2012-07-29 |  |  |
| Fix for Gel theme and default theme to work with new styles |  | Hindra Joshua | 2012-07-28 |  |  |
| New wood theme - mods to index.php / header.inc and footer.inc and modify existing themes to work with new html tag classes used - using `<div>` tags instead of tables for layout of the main menu |  | Hindra Joshua | 2012-07-26 |  |  |
| Fix the typo to make the $TotalQuantityOnHand is correct in SupplierInvoice.php |  | Exson | 2012-07-25 |  |  |
| SupplierInvoice.php incorrect calculation of weighted average cost just using the stock quantity at a single location, now corrected |  | Exson/Phil | 2012-07-24 |  |  |
| includes/Z_POSDataCreation.php - added system default date format config to POS data upload file |  | Phil | 2012-07-24 |  |  |
| Fix all scripts where demand was calculated including salesorder quotations (to exclude quotations). CounterSales.php DeliveryDetails.php SelectOrderItems.php StockCheck.php StockLocStatus.php |  | Phil/Bob Thomas | 2012-07-22 |  |  |
| Fixed on order quantities to exclude purchase orders with status completed and cancelled CounterSales.php SelectOrderItems.php DeliveryDetails.php StockLocStatus.php |  | Phil | 2012-07-22 |  |  |
| Fix api function for POS prices includes/Z_POSDataCreation.php |  | Phil | 2012-07-21 |  |  |
| Sorting products by discontinued then stockid to ensure obsolete items at the end of the list |  | Ricard | 2012-07-21 |  |  |
| Added scripts to inquire on and to print General Ledger Journals |  | Tim | 2012-07-20 |  |  |
| Fixed that delete or editing new serial items will lead to hyper-link changed as select credit items instead of back to credit invoice in scripts InputSerialItemsKeyed.php. Report by UK-Steven from webERP Chinese Community QQ group |  | Exson | 2012-07-20 |  |  |
| Updated pt_BR.utf8 translation |  | Gilberto Dos Santos Alves | 2012-07-15 |  |  |
| Fix up the sql where a field was selected twice |  | Tim | 2012-07-13 |  |  |
| Made purchase order lines look at the purchasing data lead time and set the delivery date of the line to today + the lead time if this is beyond the delivery date specified in the PO header. |  | Phil | 2012-07-10 |  |  |
| Add Z_ChangeSupplierCode.php |  | Tim/Ricard | 2012-07-09 |  |  |
| Add stable versions to UpgradeDatabase.php script |  | Phil | 2012-07-06 |  |  |
| Added new Z_DeleteOldPrices.php script to purge prices which are past their end date |  | Phil | 2012-07-06 |  |  |
| Attempt at quicker price retrieval |  | Phil | 2012-06-30 |  |  |
| Allow creation of work orders for Raw materials - well intermediary components manufacture - per Bob Thomas email |  | Phil | 2012-06-30 |  |  |
| StockAdjustments fix link to controlled stock adjustments entry |  | Bob Thomas | 2012-06-26 |  |  |
| Added tooltip/title showing long description on many scripts where short description is currently shown SelectOrderItems.php SelectCreditItems.php CreditInvoice.php DeliveryDetails.php CounterSales.php RecurringSalesOrders.php |  | Phil | 2012-06-24 |  |  |
| Add ViewTopic and BookMark vars to some functions to take advantage of new Manual contextual help. Must be added before header.inc include. |  | Icedlava | 2012-06-24 |  |  |
| Adjust header.inc, ManualContents.php, ManualHeader.html to take Viewtopic and Bookmark parameters in displaying manual. Add ManualOutline.php file containing manual TOC, add CSS and rework all manual docs. Still to add $Viewtopic and $Bookmark to functions (eg see Stocks.php) |  | Icedlava | 2012-06-24 |  |  |

## [v4.08.1] - 2012-06-24

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fixed bug preventing insert of new account section |  | Icedlava | 2012-06-22 |  |  |
| Added tooltip of long description to SelectProduct.php |  | Phil | 2012-06-21 |  |  |
| Added total QOH to NoSalesItems.php. |  | Ricard | 2012-06-20 |  |  |
| Added opposite currency pair graph to ExchangeRateTrend.php. |  | Ricard | 2012-06-20 |  |  |
| Added new script Z_ChangeLocationCode. |  | Ricard | 2012-06-19 |  |  |
| Added a 2nd strategy for StockDispatch (items with overstock at FROM, RL=0 no matter if needed at TO). |  | Ricard | 2012-06-17 |  |  |

## [v4.08] - 2012-06-15

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fixed the order cancel function failure in SelectOrderItems.php. Reported by Pak Richard. |  | Exson | 2012-06-02 |  |  |
| Settle counter sales and receipts should be set - now fixed. |  | Phil | 2012-06-02 |  |  |
| Configure xmlrpc api to work correctly with utf-8 character encoding. |  | Phil | 2012-05-28 |  |  |
| Fix barcode printing on PDFPrintLabel.php - barcode functionality is unreliable so used (http://www.barcodepack.com) by TomÃ¡Å¡ HorÃ¡Ä�ek very simple and clean |  | Phil | 2012-05-26 |  |  |
| PO_PDFPurchOrders.php print purchase orders with supplier's code where it is available in the purchasing data |  | Klaus | 2012-05-23 |  |  |
| Revise GLAccounts.php to make more than 10 digits account code is allowed. |  | Exson | 2012-05-23 |  |  |
| Modify accountcode to varchar(20) to meet some countries accounting regulation for more digits account code. |  | Exson | 2012-05-22 |  |  |
| Removed issue location (must always be the same as the manufacture location) and the issued date from WorkOrderStatus.php form - as materials could be issued on many different dates and incorrect to say they are always issued on the current date |  | Phil | 2012-05-22 |  |  |
| Move leadtime calculation into levelnetting function and resolve bug which does not use leadtime for highest level items of a BOM |  | Klaus Beucher (Opto) | 2012-05-20 |  | [](http://www.weberp.org/forum/showthread.php?tid=203) |
| Fixed bugs that the offer cannot store more than one item and remove function does not work in SupplierTenders.php and DefineOfferClass.php |  | Ahmed | 2012-05-18 |  |  |
| Fixed Branch code validation rule to rule out '-' in CustomerBranches.php. |  | Ahmed.Fawzy | 2012-05-18 |  |  |
| Fixed bug that when users input a Exchang Rate manually, when ones changed minds and select another currency, it'll not show the suggested rate correctly in Payments.php. |  | Exson | 2012-05-14 |  |  |
| Fixed when user change the currency, the suggested rate can not changed accordingly in CustomerReceipt.php. Reported by Ahmed.Fawzy. |  | Exson | 2012-05-14 |  |  |
| Fixed bugs that ExchangRateText be applied with locale_number_format() function in CustomerReceipt.php |  | Ahmed.Fawzy | 2012-05-14 |  |  |
| Fixed bug that Date selection does not work in ReverseGRN.php. Add GRN batch column. |  | Exson | 2012-05-10 |  |  |
| Fixed bugs in Stocks.php that the properties for new items cannot be saved. Checked status is not kept and numeric values between max and min are not validated. |  | Ahmed.Fawzy | 2012-05-09 |  |  |
| Fixed typo in SalesCategories.php which leads to the jpg pictures do not appear. |  | Exson | 2012-05-08 |  |  |
| Fix typo and add 'L' type for selection in SelectOrderItems.php. |  | Exson | 2012-05-08 |  |  |
| Fixed the bug of Credit_Invoice.php the total will be doubled when use Update button. The bug is introduced for a fix of directly proceed the Credit. Reported by PakRichard. |  | Exson | 2012-05-08 |  |  |
| PaymentMethods.php added open cash drawer field for my POS. |  | Phil | 2012-05-06 |  |  |
| Labels.php PDFPrintLabel.php fine tuning of new labels code |  | Phil | 2012-05-04 |  |  |
| Fixed customer type name displayed when it is successfully deleted as reported by James Dupin |  | Phil | 2012-05-04 |  |  |
| Attempt to create simple work around for the limitations of strftime function and resulting character encoding issues - added new functions to get multi-lingual months GetMonthText() and week day names GetWeekDayText(). Not sure if there is any international consensus on the best format here - but can use the DefaultDateFormat or $_SESSION['Language'] if others want it a different way |  | Phil | 2012-05-04 |  |  |
| Allow SelectOrderItems.php to select labour type items |  | Phil | 2012-05-04 |  |  |
| Updated tcpdf to 5.9.160 |  | Phil | 2012-05-04 |  |  |
| Default lastcostupdate to 0000-00-00 to avoid issues inserting new items. |  | Phil | 2012-05-04 |  |  |
| Measurement unit in FormDesigner.php should be points instead of millimeters. |  | MTPubRadio | 2012-05-01 |  |  |
| Fixed Purch Order PDF file text of Order Total- Excl tax does not align with amount horizontally. |  | MTPubRadio | 2012-05-01 |  |  |
| SelectWorkOrder.php added start date for the work order to the work orders displayed for selection |  | Opto/Klaus | 2012-04-29 |  |  |
| Added EDISendInvoices_Reece.php to send Reece format EDI invoices - approved by Reece (Australian Plumbing retailer) |  | David Short | 2012-04-29 |  |  |
| Fixed bugs in MRPCalendar.php which caused working days cannot be calculated correctly. |  | Exson | 2012-04-28 |  |  |
| Fixed PO header that does not display user's default warehous when iusse a new PO in PO_Header.php. |  | Exson | 2012-04-06 |  |  |
| Complete rewrite of PDFPrintLabels.php and Labels.php in webERP style - half the code and maintainable with templates stored with all the other data in the database. The new labels also allow fields to be printed as barcodes too. |  | Phil | 2012-04-25 |  |  |
| Fix CopyBOM.php that was insering a blank stockid into stockmaster as reported by Ricard |  | Phil | 2012-04-24 |  |  |
| Editable item description in PO. |  | Vitaly | 2012-04-21 |  |  |
| Added option to display only items that are currently on purchase order in StockLocStatus.php |  | Vitaly | 2012-04-16 |  |  |
| Fixed Days textbox where it did not remember entered value. |  | Vitaly | 2012-04-16 |  |  |
| ReorderLevelLocation.php only showing items not discontinued (current). |  | Ricard | 2012-04-16 |  |  |
| Adding stock category as filter for selection in TopItems.php |  | Ricard | 2012-04-14 |  |  |
| Replaced table row bgcolor [or style=background-color] with the appropriate css class. |  | TurboPT | 2012-04-12 |  |  |
| Code simplified on TopItems.php |  | Ricard | 2012-04-11 |  |  |
| Remove invalid attribute colspan found within table tag elements. |  | TurboPT | 2012-04-07 |  |  |
| Fixed order delivery comments overlapped with Shipper in Packing slip of PDFOrderPageHeader_generic.inc Reported by CQZ from webERP Chinese Community QQ group |  | Exson | 2012-04-05 |  |  |
| Cleaned up Petty Cash module. Fixed several errors in SQL INSERT statements. |  | Vitaly | 2012-04-03 |  |  |
| Fixed typo in PurchData.php. |  | Exson | 2012-04-03 |  |  |
| Fixed Supplier Code and Supplier part code inconsistent with database field length definition in PurchData.php. Reported by rfthomas. |  | Tim | 2012-04-03 |  |  |
| BOMs delete function requires Location and WorkCentre to be set |  | Vitaly | 2012-04-02 |  |  |
| Add Chinese Traditional TW locale |  | Billy Chang | 2012-04-02 |  |  |
| Fixed CopyBOM.php sql errors and header already sent error. |  | Exson | 2012-04-02 |  |  |
| Fixed for PcAuthorizeExpenses.php cannot authorized expenses due to no applying gettext function to 'Update' |  | Exson | 2012-03-31 |  |  |
| Fixed problem that on PCAssignCashToTab.php it should be used the decimal places of the currency of the tab, not the decimal places of the functional currency.The same in PcClaimExpensesFromTab.php |  | PakRichard | 2012-03-31 |  |  |
| Fixed bug in PDFStockCheckComparision.php when selecting Report and Close the Inventory Comparison, there will be seriously sql errors. Reported and fixed by Kunshan-Ouhai from webERP Chinese community QQ group. |  | Kunshan-Ouhai?G | 2012-03-31 |  |  |
| Revise scripts Add_SerialItemsOut.php and OutputSerialItems.php fixes a problem that occurs when receiving a bulk inventory transfer. There was a hard-coded loop that prevented adding bundles that were greater than 10. |  | Dafydd Crosby | 2012-03-31 |  |  |
| Modified security role to order by role name instead of by roleid to make it more user friendly in WWW_Access.php and WWW_Users.php |  | Richard Andreu | 2012-03-30 |  |  |
| Fixed href typo error which lead to Select another location link does not work in InternalStockRequestFulfill.php |  | Exson | 2012-03-29 |  |  |
| Fixed typo which caused scripts does not work. Cannot add or modify existed items. |  | Exson | 2012-03-29 |  |  |
| Fixed typo in footer.inc |  | Exson | 2012-03-27 |  |  |
| Add page footer with total pages for users verification purpose in PrintCustOrder.php Reported by Russell |  | Exson | 2012-03-25 |  |  |
| Add return back link for SupplierInvoice.php while users make input errors. Reported by Ke from webERP Chinese Community QQ group |  | Exson | 2012-03-25 |  |  |
| Fixed footer time displayed garbage in Win OS of simplified Chinese Language in footer.inc. Reported and fixed by Ke from webERP Chinese community QQ group. |  | Ke | 2012-02-23 |  |  |
| Fixed accounting period displayed abnormal in simplified Chinese language in Win OS in DateFunctions.inc. Reported by CQZ from webERP Chinese community QQ group |  | Exson | 2012-03-23 |  |  |
| Fixed accounting period displayed abnormal in simplified Chinese language in Win OS. Reported by CQZ from webERP Chinese community QQ group. |  | Exson | 2012-03-23 |  |  |
| Fixed no control of accumulated quantity of the same item during transferring which lead to negative quantity of stock in StockLocTransfer.php reported by Rong. |  | Exson | 2012-03-22 |  |  |
| Fixed time displayed incorrectly in footer in Win OS since the strftime() encoding is not UTF-8. Reported by CQZ and KE in webERP Chinese Community QQ group. |  | Exson | 2012-03-22 |  |  |
| Fixed period displayed incorrectly in GL inquiry in Win OS since the strftime() encoding is not UTF-8. Reported by CQZ and KE in webERP Chinese Community QQ group. |  | Exson | 2012-03-22 |  |  |
| Fixed the csv file cannot display UTF-8 characters correctly in Excel. |  | CQZ,KE | 2012-03-21 |  |  |
| Fixed Multi currency payment exchange rate errors in Payments.php. Reported by PakRichard |  | Exson | 2012-03-20 |  |  |
| Fixed bugs that users push Process Credit button in Credit_Invoice.php directly without update will lead to unbalance Journal entry. Reported by Russ |  | Exson | 2012-03-20 |  |  |
| Fixed item properties are not deleted together with item deletion in Stocks.php. Reported by Zhoule from webERP chinese forum QQ group. |  | Exson | 2012-03-20 |  |  |
| Remove illegal copyright notices to prevent any legal problems. |  | Tim | 2012-03-19 |  |  |
| Fixed that same parent and component but added in different Work Centers and Locations will display twice in BOMs.php. And fixed that to delete one line of same parents and component will delete all lines with same parents and components. Add ArrayUnique function to get unique array for multi-dimensional array. |  | Exson | 2012-03-13 |  |  |
| Correct mailing sending messages display error in PrintCustTransPortrait.php Reported by Thomas_lie |  | Exson | 2012-03-13 |  |  |
| Fixed freight cost over 1000 cannot be calculated correctly in ConfirmDispatch_Invoice.php reported by Craig Craven. |  | Exson | 2012-03-12 |  |  |
| Added missing file encodings_maps.php to TCPDF |  | Vitaly | 2012-03-11 |  |  |
| ar_EG.utf8 arabic translation started |  | Dr. Magdy Salib | 2012-03-11 |  |  |
| French language translation update |  | James Dupin | 2012-03-11 |  |  |
| Added parameter for show stock image on select product screen - SelectProduct.php and SystemParameters.php |  | Ricard | 2012-03-08 |  |  |
| Fixed that serialised items cannot be processed in StockAdjustmentsControlled.php. Reported by Soujiro |  | Exson | 2012-03-07 |  |  |
| Fixed that serialised items cannot be processed in StockAdjustments.php. Reported by Soujiro |  | Exson | 2012-03-07 |  |  |
| Rule out 'Pending' status PO from On Order Quantity in ReorderLevel.php Suggested by Brian May |  | Exson | 2012-03-06 |  |  |
| Remove carriage return and feed line from Quotation PDF file in PDFQuotation.php Reported by Thomas_lie |  | Exson | 2012-03-06 |  |  |
| Add condition to prevent blank stock category description in StockCategories.php |  | Exson | 2012-06-03 |  |  |
| BankMatching.php sql error due to extra single quotation. Reported by PakRichard |  | Exson | 2012-03-06 |  |  |
| PrintCustTransPortrait.php html elements failed to display. Reported by rfthomas |  | Exson/Tim | 2012-03-05 |  |  |
| SelectProduct.php checked for existence of part pic before displaying a link that could potnetially fail |  | Phil | 2012-03-03 |  |  |
| Changed all htmlentities calls to htmlspecialchars calls |  | Phil | 2012-02-29 |  |  |
| Added functionality that allows for the set up of internal departments, and for the request of stock to be issued - a new issue account is defined in stockcategories. Issue requests must be authorised by a department manager and then issues fulfilled creates a stock adjustment with posting in the gl to the item category issue account. |  | Tim | 2012-02-28 |  |  |
| Added DB_escape_string() to narrative in SQL INSERT statements. Fixes a problem posting to database when the string contains quotes. |  | Vitaly | 2012-02-28 |  |  |
| Fixed bug that when set frequently sold items, there are sql errors which claimed that no group set for sum() function. |  | Exson | 2012-02-26 |  |  |
| PO_Items.php Add $_SESSION and $_POST variables to fixed extra lines added while page refreshing or push F5. Reported by CQZ and Ke from webERP Chinese forum QQ group. |  | Exson | 2012-02-26 |  |  |
| SQL in PDFSuppTransListing.php would not fetch any data and debug message was not showing. Missing FROM in upgrade4.07-4.08.sql. |  | Vitaly | 2012-02-25 |  |  |
| Remove extra ) after Create GL entries for stock transactions in CompanyPreferences.php |  | James Dupin | 2012-02-25 |  |  |
| Moved Utility scripts from Z_index.php to their own module named Utilities |  | Ricard | 2012-02-23 |  |  |
| Make daily sales inquiry work correctly with assembly items where costs are recalculated in the case of negatives stock when supplier invoices entered |  | Phil | 2012-02-22 |  |  |
| Update to French translation |  | James Dupin | 2012-02-22 |  |  |
| Add ENT_QUOTES, 'UTF-8' to all htmlspecialchars calls |  | Phil | 2012-02-22 |  |  |
| SuppCreditGRNs.php SuppInvGRNs.php DefineSuppTransClass.php SupplierInvoice.php stock movement was not being updated correctly with cost on purchase invoice entry as was using GRNNo not GRNBatchNo - which is used as the GRN transaction number in stock movements. |  | Phil | 2012-02-17 |  |  |
| Modified index.php to use arrays of links rather than hard code all menus |  | Tim | 2012-02-16 |  |  |
| Fixed StockAdjustments.php was not producing gltrans entries when stock code entered directly |  | Exson | 2012-02-16 |  |  |

## v4.07 - 2012-02-11

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fix Prices.php for end dates when a new price with no end date is inserted with a start date in the future. |  | Phil | 2012-02-11 |  |  |
| Repairs to Prices_Customer.php option to have prices for a single customer and all branches had been compromised by having the branch selection box with no "All Branches" option. Also selected branch was not showing as it should have when editing a specific branch price |  | Ahmed.Fawzy | 2012-02-11 |  |  |
| Made new system parameter for StandardCostDecimalPlaces - reworked SelectProduct.php and StockCostUpdate.php to use the new parameter. Modified SystemParameters.php to allow the parameter to be a number between 0 and 4 inclusive. |  | Ricard | 2012-02-07 |  |  |
| Reworked ReorderLevelLocation.php |  | Phil | 2012-02-07 |  |  |
| StockTransfers.php if SESSION['Transfer'][0] not set then now initiate a new transfer |  | Phil | 2012-02-07 |  |  |
| Sent NewTransfer with call from SelectProduct.php to ensure new transfer initiated |  | Exson | 2012-02-07 |  |  |
| Added new API function InsertDebtorReceipt in api_debtortransactions.php |  | Phil | 2012-02-06 |  |  |
| Fixed addTextWrap() in class.pdf.php. The length of the string was not calculated properly, causing long strings to print beyond the cell boundaries. |  | Vitaly | 2012-02-04 |  |  |
| Added new API function CreateCreditNote in api_debtortransactions.php |  | Phil | 2012-02-04 |  |  |
| Fixed bug that was not allowing PO lines to be deleted in OrderValue method of PO class was testing using asignment operator not comparison operator |  | VitalyFixed | 2012-02-03 |  |  |
| Added DB_escape_string before ItemDescription and SupplierName in GoodsReceived.php to prevent problems with '. |  | Vitaly | 2012-01-31 |  |  |
| Stocks.php error on changing a stock category the journal between the stock GL accounts was not working because $NewStockAccount should have been $NewStockAct |  | Phil | 2012-01-31 |  |  |
| PO_Items.php removed $Maximum_Number_Of_Parts_To_Show should exist when we are already limiting the output of the query based on the configuraiton option $_SESSION['DefaultDisplayRecordsMax']; |  | Phil | 2012-01-31 |  |  |
| Removed extra 'AND' in SQL statement in ReverseGRN.php |  | Vitaly | 2012-01-30 |  |  |
| Alterations to API to fix SQL and to add InvoiceSalesOrder method |  | Phil | 2012-01-29 |  |  |
| Z_ChangeStockCode.php now alters SalesCategories of items being changed |  | Phil | 2012-01-29 |  |  |
| StockCategories.php fixes for numericvalue not displaying and errored with "minimum value is not numeric" |  | Ahmed.Fawzy | 2012-01-28 |  |  |
| ConfirmDispatch_Invoice.php corrected link to ConfirmDispatchControlled_Invoice.php to send $identifier to get the correct session variable containing the order to invoice |  | Phil | 2012-01-28 |  |  |
| SpecialOrder.php added $identifier to session class variable to avoid overlapping sessions in multiple tabs. |  | Tim | 2012-01-28 |  |  |
| PO_AuthoriseMyOrders.php fixed html in hidden $_POST['StatusComments'] by using htmlspecialchars($_POST['StatusComments']) |  | Phil | 2012-01-27 |  |  |
| Added quotes and missing closing tags in multiple files |  | Vitaly | 2012-01-25 |  |  |
| Added quotes to attributes in multiple files and changed option selected to selected="selected". |  | Vitaly | 2012-01-24 |  |  |
| Added quotes to attributes in multiple files. |  | Vitaly | 2012-01-23 |  |  |
| Added quotes and missing closing tags in multiple files |  | Vitaly | 2012-01-22 |  |  |
| Added quotes to attributes in multiple files. |  | Vitaly | 2012-01-21 |  |  |
| xhtml fixes in multiple files. |  | Vitaly | 2012-01-21 |  |  |
| Fixed GetStockPrice API function for effectivty dates |  | Phil | 2012-01-20 |  |  |
| Changed $DB_error_no($db) to DB_error_no($db) in Z_ChangeStockCode.php |  | Vitaly | 2012-01-20 |  |  |
| xhtml fixes in CustomerBranches.php StockAdjustments.php WorkOrderEntry.php |  | Vitaly | 2012-01-20 |  |  |
| Merge tendering system from Tim's branch |  | Tim | 2012-01-20 |  |  |
| Merge xhtml fixes from Tim's branch: Labels.php TaxGroups.php GLCodesInquiry.php CustomerInquiry.php SalesByTypePeriodInquiry.php ContractBOM.php Shippers.php MRPPlannedWorkOrders.php |  | Tim | 2012-01-20 |  |  |
| Added previously received quantity to stock transfer notes PDFStockLocTransfer.php |  | Tim | 2012-01-20 |  |  |
| Added missing ')' at the end of some INSERT statements Stocks.php. |  | Vitaly | 2012-01-19 |  |  |
| Check on deletion of a location to see if any purchase orders exist prior to deletion |  | Phil | 2012-01-19 |  |  |
| StockCheck.php fixed error in SQL two ANDs in calculating quantity demand reported by Ricard |  | Phil | 2012-01-19 |  |  |
| SelectOrderItems.php $i++ - in code for frequently ordered items. |  | Paul Harness | 2012-01-19 |  |  |
| Removed unused .table2 declaration from default.css. Fixed border settings in .table1. |  | Vitaly | 2012-01-17 |  |  |
| Made StockReorderLevel.php just update changed fields rather than update all locations even though they may not have changed./ |  | Phil | 2012-01-16 |  |  |
| Added new api functions to get tax group taxes, list tax authorities, get tax authority details and get tax authority tax rates, also to list and get payment methods |  | Phil | 2012-01-08 |  |  |
| PcAuthorizeExpenses.php Compare date against SQL raw date format, then convert for display when deciding to display authorize checkbox. |  | Paul Harness | 2012-01-08 |  |  |
| PcClaimExpensesFromTab.php Use DefaultDateFormat for date in expense entry. |  | Paul Harness | 2012-01-08 |  |  |

## v4.06.6 - Date ?

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Purchase invoice tax on tax was not being processed correctly although it was appearing on the supplier invoice during entry of the invoice the processing was not calculating the tax on tax. Fixed SupplierInvoice.php |  | Phil | 2012-01-07 |  |  |
| Unable to add completed (fully received) lines off a purchase order against a shipment. Removed trap to allow completed (fully received) lines to be added. |  | Phil | 2012-01-07 |  |  |
| Issues with entering shipments discovered parameter DecimalPlaces missed off adding line to shipment also quotation error in entering shipment ETA date. |  | Phil | 2012-01-05 |  |  |

## v4.06.5 - 2012-01-03

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| install/index.php now checks for the existence of config.php before attempting to run the installation script |  | Phil | 2011-12-30 |  |  |
| includes/SelectOrderItems_intoCart.inc and includes/DefineCartClass.php fix line numbers for order lines |  | Phil | 2011-12-29 |  |  |

## v4.06.4 - 2011-12-28

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| SelectCreditItems.php SelectOrderItems.php CounterSales.php includes/DefineCartClass.php fixes for ExistingOrder . $identifier |  | Phil | 2011-12-28 |  |  |
| PDFStockLocTransfer.php now has include('includes/session.inc') above the title. Fixes to allow manual entry. |  | Phil | 2011-12-27 |  |  |

## v4.06.3 - 2011-12-22

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fix to install/save.php to use mysqli_connect_error to report the connection error |  | Phil | 2011-12-22 |  |  |
| Shipments.php was not retaining vessel and voyage |  | Phil | 2011-12-22 |  |  |
| StockLocTransfer.php can now import a csv of items to transfer and each line can be checked to remove if required, |  | Phil | 2011-12-20 |  |  |
| Fix StockTransfers.php missing brackets around calculation in parameter passed to SQL |  | Phil | 2011-12-20 |  |  |
| Fix to UpgradeDatabase.php the button to perform the upgrade was missing for older versions where the version was not stored in the DB. |  | Phil | 2011-12-15 |  |  |
| SalesGraph.php was trying to set background colour to 'selection' changed to white |  | Phil | 2011-12-15 |  |  |
| Shipments.php fixed missing quote in html |  | Vitaly Shevkunov | 2011-12-15 |  |  |
| check_syntax.sh Script for checking syntax of all php scripts in webERP |  | Tim | 2011-12-14 |  |  |
| Z_ChangeStockCode.php fixed call to DB_error_no had missing ($db) - also turned off FOREIGN KEY CHECKS before updating the BOM. |  | Vitaly Shevkunov | 2011-12-13 |  |  |
| Fix SelectOrderItems.php and DeliveryDetails.php for where an order is modified while another order is being created in a different tab of the browser - $_SESSION['ExistingOrder'] now modified to $_SESSION['ExistingOrder' .$identifier] as suggested by Tim |  | Exson | 2011-12-13 |  |  |
| Fixed other scripts affected and send $identifier to add_to_cart and remove_from_cart functions |  | Phil | 2011-12-13 |  |  |
| SupplierInvoice.php attempts to post back any cost variances where there is no stock left to apportion the variances to - posting back to stockmoves (so the DailySales.php and other sales inquiry scripts reflect the appropriate GP) and the salesanalysis tables |  | Phil | 2011-12-11 |  |  |
| DailySalesInquiry.php removed incorrect call to establish new cart object?? |  | Phil | 2011-12-11 |  |  |
| ConfirmDispatch_Invoice.php corrected SQL that was not calculating the difference to go to the orderdeliverydifferences log in parenthesis first before casting/concatenating to string for the SQL. |  | Brian May | 2011-12-10 |  |  |

## v4.06.2 - 2011-12-03

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Added indian_number_format for specific unusal number formatting 00,00,000.00 for India and apparently South Asian countries. Kicks in for en_IN.utf8 and hi_IN.utf8 |  | Phil | 2011-12-02 |  |  |
| Removed a load of DB_escape_string() calls as no longer required now the entire $_POST and $_GET array are DB_escape_string()'ed |  | Phil | 2011-11-27 |  |  |
| PrintCustTransPortrait.php added bank account code |  | Tim | 2011-11-27 |  |  |
| GLTagProfit_Loss Gross Profit calculation error was = COGS - fixed |  | Tim | 2011-11-27 |  |  |
| SupplierInvoice.php attempt to update salesanalysis cost if goods sold prior to purchase invoice being entered |  | Phil | 2011-11-26 |  |  |
| Added missing images to aguapop theme - for customer inquiry |  | Phil | 2011-11-26 |  |  |
| ConfirmDispatchControlledInvoice.php now uses $identifier in session variable name to ensure uniqueness |  | Phil | 2011-11-26 |  |  |
| PcAssignCashToTab.php removed unecssary DB_escape_string() calls |  | Phil | 2011-11-26 |  |  |
| StockCostUpdate.php fix link to StockStatus.php |  | Phil | 2011-11-26 |  |  |
| UserSettings.php - now only checks password if != '' |  | Phil | 2011-11-23 |  |  |
| StockCategories number_formatting of minumum and maximum values of stock category properties |  | Phil | 2011-11-22 |  |  |
| StockSerialItems.php closing quote typo. |  | Felix Lim | 2011-11-22 |  |  |
| UserSettings.php now checks for at least 5 character passwords WWW_Users.php CustLoginSetup.php and SupplierLoginSetup.php now also check for 4 character userids |  | Phil | 2011-11-21 |  |  |
| WWW_users.php malfomed `<input type="hidden" - with no closing >` |  | Felix Lim | 2011-11-21 |  |  |
| Shipments.php now only allows purchase order items to be added to a shipment where the shipment is authorised. Completed, rejected, cancelled orders no longer show. |  | Phil | 2011-11-20 |  |  |
| Shipments.php now shows quantities to the number of decimal places defined in the stock master and amounts in the currency of the purchase order. |  | Phil | 2011-11-20 |  |  |
| PO_Header.php purchase orders cannot be cancelled now if they are on a shipment - need to remove from the shipment first |  | Phil | 2011-11-20 |  |  |

## v4.06RC3 to 4.06.1 - 2011-11-19

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| SupplierAllocations.php fixes for number_formatting |  | Phil | 2011-11-19 |  |  |
| CounterSales.php fixes as made to SelectOrderItems.php for discount/price - also AmountPaid not run through filter_number_format on display only when the value is used. |  | Phil | 2011-11-19 |  |  |
| Fixes to SelectOrderItems.php and associated scripts to prevent issues with discount entry and price modifications |  | Phil | 2011-11-19 |  |  |
| ConfirmDispatch_Invoice.php and ConfirmDispatchControlled_Invoice.php now use $idenfier in the $SESSION name to avoid $_SESSION conflicts. |  | Phil | 2011-11-19 |  |  |
| MiscFunctions.php filter_number_format now checks for several . in numbers and removes all exlcuding the last to ensure SQL compliant numbers returned. |  | Phil | 2011-11-19 |  |  |
| Prices_Customer.php sql error had neglected to include currencies table to get the number of decimalplaces to show |  | Phil | 2011-11-16 |  |  |
| reportwriter/WriteReport.inc modified to use OutputD function in class.pdf.inc |  | Phil | 2011-11-16 |  |  |
| includes/LanguageSetup.php was not including LanguagesArray.php in reportwriter as PathPrefix was not prepended |  | Phil | 2011-11-15 |  |  |
| AgedDebtors.php now ignores balances with an absolute value of < 0.005 |  | Don Grames | 2011-11-14 |  |  |
| Allow serialised perishible items to have expiration dates. |  | Dafydd Crosby | 2011-11-14 |  |  |

## v4.06RC2 - 2011-11-13

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Purchase order Order_Value in includes/DefinePOClass.php now only looks at the value of lines left on the order after deleted lines |  | Phil | 2011-11-12 |  |  |
| Removed old Numbers to words class code - this code was not in keeping with the rest of the system and not PHP 5 compatible anyway. Replaced with a much simpler function. This code is only used for pre-printed cheques - so it is in that script only. The new function may not effective for other languages but will be easily adapted as needed. The PrintCheque.php script needs to be modified specifically for the business stationery. |  | Phil | 2011-11-12 |  |  |
| Added a function in includes/SQL_CommonFunctions.inc EnsureGLTransBalance that looks at the gltrans entries for a given transaction type and type number and if the total is not zero and less than 0.05 it fudges the largest gltrans record to make the gltrans balance in total - added to ConfirmDispatch_Invoice.php Credit_Invoice.php SelectCreditItems.php CounterSales.php Payments.php CustomerReceipt.php |  | Phil | 2011-11-10 |  |  |
| includes/DateFunctions.inc fixed GetPeriod function to create new period when just one month ahead of last period - worried about this can't see how it could have been like this so long without a bug report? |  | Phil | 2011-11-10 |  |  |
| includes/LanguageSetup.php now sets LC_NUMERIC locale category after LC_ALL to ensure SQL compliant calculations |  | Phil | 2011-11-10 |  |  |
| includes/GetConfig.php no longer casts the VersionNumber to a double - issue highlighted by Tim |  | Phil | 2011-11-08 |  |  |
| includes/InputSerialItems.php and InputSerialItemsKeyed.php now sends $identifier |  | Felix Lim | 2011-11-08 |  |  |
| CustomerReceipt.php now shows entries made with decimal places of the currency being entered |  | Phil | 2011-11-08 |  |  |
| Fixed bugs reported by Ron Wong and Don SelectSupplier.php sql error missed "s" off suppliers and CustomerReceipt.php - had not populated new class variable (property) of CurrDecimalPlaces |  | Phil | 2011-11-08 |  |  |

## v4.06RC1 - 2011-11-06

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Modification to Stocks.php to avoid losing PanSize and ShrinkFactor |  | Exson | 2011-10-30 |  |  |
| Made it so a saleman cannot be deleted if a user is setup referring only to this salesperson |  | Phil | 2011-10-30 |  |  |
| sanitising $_SERVER['PHP_SELF'] and unquoted sql in reportwriter/FormMaker.php and reportwriter/ReportMaker.php |  | High-Tech Bridge SA Security Research Lab | 2011-10-29 |  |  |
| Tried to get correct characters for each language's thousands separator and decimal point in includes/LanguagesArray.php |  | Phil | 2011-10-15 |  |  |
| Updated PHPplot to 5.5.0 |  | Phil | 2011-10-15 |  |  |
| aguapop - theme |  | Fred Schuettler | 2011-10-15 |  |  |
| PcExpenses.php PcAuthorizeExpense.php now uses GL tags |  | Ricard | 2011-10-08 |  |  |
| SelectSalesOrder.php now allows for creation of purchase orders for the components of assembly items on sales orders. |  | Phil | 2011-10-08 |  |  |
| Added new query critera to AuditTrail.php to look for text in query strings to enable searching for updates/inserts/deletes relating to a specified customer or item code etc |  | Ricard | 2011-09-29 |  |  |
| Fixed the bug in GLAccountReport.php for typename from the right table (systypes) in SQL and change locale_number_format for multinational. |  | Exson | 2011-09-22 |  |  |
| Added WindowsLocale element to LanguagesArray.php and modified UserSettings.php and WWW_Users.php to use the new array definition. Also modified includes/LanguageSetup.php to use the windows locale string in the LanguagesArray as required for proper setting of the locale under windows. |  | Phil | 2011-09-17 |  |  |
| Added serialised field into PrintCustTransPortrait.php to reinstate possiblity for printing serialised items on invoice - not possible in landscape version (PrintCustTrans.php) |  | Felix Lim | 2011-09-17 |  |  |
| Change all scripts to allow display and input of numbers in the format of the selected users locale |  | Phil/Exson/Tom | 2011-10-31 |  |  |
| Fixed supplier payment exchange rate ... was being calculated incorrectly from functional exchange rate and the exchange rate between the currency of the bank account and currency of payment. |  | Phil | 2011-09-05 |  |  |
| GLTransInquiry posted was not retrieved correctly - now fixed |  | Phil | 2011-09-05 |  |  |
| Removed the stock code from form entities in SelectOrderItems.php and PO_Items.php to prevent issues with some characters in stock codes as suggested by Tim |  | Phil | 2011-09-04 |  |  |
| SelectOrderItems.php and CounterSales.php removed incorrect !== comparisons sh/been != comparisons |  | Marcos Skambraks | 2011-09-04 |  |  |
| Modified tcpdf.php to just send the pdf header - was causing issues with apache fastcgi module |  | Marcos Skambraks | 2011-09-04 |  |  |
| Changed SelectProduct.php to show just current prices |  | Ricard | 2011-09-03 |  |  |
| Changed SelectCustomer to use %% around each parameter to use just a single SQL statement rather than several which ignored other inputs - as suggested by Marcos Skambraks |  | Phil | 2011-09-03 |  |  |
| Made locale_number_format() as per Tim's instruction that displays numbers in the format of the locale in includes/LanguageSetup.php and replaced all occurrences of locale_number_format() with locale_number_format() |  | Phil | 2011-09-02 |  |  |
| Scaling of image in GetStockImage.php |  | Warren Olds | 2011-09-02 |  |  |
| StockAdjustments.php now sends an email to the inventory manager on the creation of manual stock adjustments. The inventory manager email is defined in SystemParameters.php - leaving the email address blank will stop any emails from being created. |  | Ricard | 2011-08-31 |  |  |
| Updated lastcostupdate in StockCostUpdate.php |  | Ricard | 2011-08-29 |  |  |
| Reinstated stockmaster.lastcostupdate field - added update to lastcostupdate in WorkOrderReceive.php |  | Phil | 2011-08-29 |  |  |
| WorkOrderIssue.php now allows issue of 0 cost items to the work order - in the event that customer supplied product needs to be included in a work order and the quantities maintained for accountability to the customer |  | Phil | 2011-08-27 |  |  |
| Fixed bugs in ConnectDB_mysql.inc in DB_Txn_Commit and DB_Txn_Begin was using msql functions not mysql functions!! |  | Pablo Martin | 2011-08-26 |  |  |
| Added BuyerName to Delivery Details and the cart class |  | Phil | 2011-08-23 |  |  |
| PDFPrintLabel Does Not display discontinued items and now allows printing of future price labels |  | Ricard | 2011-08-23 |  |  |
| Fixed bug that duplicated purchase order items when more than one item was added to an existing purchase order |  | Phil | 2011-08-21 |  |  |
| BackupDatabase.php Delete link now deletes any backup files in the company directory in case any were left there before - as this is a serious security issue if files are left on the web-server |  | Phil | 2011-08-21 |  |  |
| Fixed bug in Stocks.php should have used Date($_SESSION['DefaultDateFormat']) instead of Date('Y-m-d') inside GetPeriod function as pointed out by Ricard |  | Phil | 2011-08-19 |  |  |
| SelectCustomer.php fixed selection of customer where the first one was selected needed /to kick off count at 0. |  | Tim | 2011-08-16 |  |  |
| Remove redundant field stockmaster.lastcurcostdate |  | Phil | 2011-08-19 |  |  |
| Fine tuning formatting PDFStockNegatives.php |  | Ricard | 2011-08-19 |  |  |
| security.png image was missing now added |  | Carlos Urbieta Cabrera | 2011-08-19 |  |  |
| SystemParameters.php default ProhibitPostingsBefore to 1900-01-01 when no default is currently set - can cause errors at the moment as defaults to the latest period if not previously set. |  | Phil | 2011-08-14 |  |  |
| Stocks.php now does a journal for any work in progress on a change of category where the new category has a different GL account for WIP - and bug fixes as per Ricard |  | Phil | 2011-08-14 |  |  |
| Backed out changes on 7/8 that prevented SelectProduct.php transaction links - now only purchase order links blocked if an item is obsolete |  | Phil | 2011-08-12 |  |  |
| CounterSales.php apply discountmatrix fixes |  | Phil | 2011-08-12 |  |  |
| GoodsReceived.php now has checkbox to flag the order line as complete - even though the quantity delivered might be short of the order quantity. |  | Phil | 2011-08-11 |  |  |
| PO_Items.php link to complete uncompleted lines - status of order is changed to complete is all lines are completed |  | Phil | 2011-08-11 |  |  |
| POReport.php added link to detail purchase order inquiry |  | Phil | 2011-08-10 |  |  |
| PO_SelectPurchOrder.php and outstanding purchase order searches now show the delivery date (from the purchase order header) line items may have different delivery dates. |  | Phil | 2011-08-10 |  |  |
| Stocks.php changing the stock category to one with a different stock account now creates a journal (if stock is linked to GL) to move the cost of the stock from the old GL account to the new GL account |  | Phil | 2011-08-10 |  |  |
| SelectProduct.php now disables transactions on items flagged as obsolete (discontinued). /Also obsolete items are shown as such in the selection list - suggested by Klaus (opto) |  | Phil | 2011-08-07 |  |  |
| Corrected INNER JOIN ON clause in sql used in InventoryQuantities.php script |  | Ricard | 2011-08-07 |  |  |
| Added docuwiki links to WikiLinks function in MiscFunctions.php and allow Docuwiki option in SystemParameters.php |  | Klaus | 2011-08-07 |  |  |
| SalesInquiry.php fix wording of labels to be more consistent with the rest of webERP |  | Ricard | 2011-08-06 |  |  |
| PO_Items.php now has checkbox to select items that have purchasing data entered for the supplier ordering from - as per Klaus's (opto) suggestion |  | Phil | 2011-08-06 |  |  |
| Added leadtime to the link from SelectProduct.php so that delivery dates used when creating purchase orders make sense |  | Exson | 2011-08-06 |  |  |
| Fix GP Percent reported when discounts updated by discount matrix |  | Phil | 2011-08-04 |  |  |
| Make PO_SelectOSPurchOrder.php behave similarly to SelectPurchOrder.php (for inquiries) where the order number is the link that takes you to the order |  | Phil | 2011-08-04 |  |  |
| SalesInquiry.php now shows net sales after discounts |  | Ricard | 2011-08-04 |  |  |
| PO_Header.php - was not updating when update hit as reported by Klaus(opto) |  | Phil | 2011-07-31 |  |  |
| BackupDatabase.php script |  | Phil | 2011-07-31 |  |  |
| SelectCreditItems.php made it so if several sessions creating a credit note they no longer over-write each other. |  | Phil | 2011-07-30 |  |  |
| POItems.php now checks for return of more than 1 purchasing data from the supplier and takes the newest record |  | Ricard | 2011-07-30 |  |  |

## v4.05 - 2011-07-27

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| includes/LanguageSetup.php now checks for existence of LC_MESSAGES (it doesn't exist on windows servers) and uses LC_ALL as the fall back only |  | ? | 2011-07-27 |  |  |
| Fixed quoting in PDFSuppTransListing.php, StockReorderLevel.php WhereUsedInquiry.php Z_ReverseSuppPaymentRun.php |  | ? | 2011-07-27 |  |  |
| SalesCategories.php fixed display of active categories - this script is not used by webERP - only by Mo Kelly's joomla cart application |  | ? | 2011-07-26 |  |  |

## v4.04.5 - 2011-07-24

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| CustomerBranches.php check for existance of Shippers and TaxGroups |  | Phil | 2011-07-24 |  |  |
| CustLoginSetup.php and SuppLoginSetup can no longer edit user accounts - only add |  | Phil | 2011-07-24 |  |  |
| Updated Hungarian/German/Portuguese/Japanese translations from launchpad. |  | Phil | 2011-07-24 |  |  |
| Make link from SelectCustomer.php to CounterSales.php so that sales together with payment can be entered directly against a selected customer. |  | Phil | 2011-07-22 |  |  |
| Change SelectCreditItems.php and SelectCustomer.php to select customer and branch using form variables in the same way as SelectOrderItems.php to avoid difficulties with spaces and hyphens - and to be consistent. |  | Phil | 2011-07-22 |  |  |
| Error in taxes GL posting SelectCreditItems.php |  | Phil | 2011-07-17 |  |  |
| Comments not wrapping correctly on quotations - fixed landscape quotation |  | Phil/Beth Lesko | 2011-07-09 |  |  |
| Remove duplication of checking for illegal characters - use the same function in MiscFunctions.php ContainsIllegalCharacters in Currencies and the utility scripts Z_ChangeBranchCode.php and Z_ImportStockCodes.php |  | Phil | 2011-07-08 |  |  |
| Change all strstr occurrences to use multi-byte function mb_strstr |  | Phil | 2011-07-08 |  |  |
| Trap codes with decimal point "." in them in the IllegalCharacters function |  | Phil | 2011-07-08 |  |  |
| Fix discount matrix calculations on order entry and amendment |  | Phil | 2011-07-08 |  |  |
| Amend menu to use PDFStockLocTransfer.php to reprint transfer list - as reported by Ron Wong |  | Phil | 2011-07-05 |  |  |
| Changed all strpos to mb_strpos |  | Phil | 2011-07-03 |  |  |
| Changed all strtoupper to mb_strtoupper |  | Phil | 2011-07-03 |  |  |
| Changed all substr to mb_substr |  | Phil | 2011-07-03 |  |  |
| Changed all `<br>` to `<br />` |  | Phil | 2011-07-03 |  |  |
| Changed all strlen to mb_strlen to resolve the issue with multi-byte strlen issues as UTF-8 is a multi-byte character encoding system |  | Phil | 2011-07-03 |  |  |
| WorkOrderCosting.php extra comma in SQL after decimalplaces field - now removed |  | Pablo Martin | 2011-07-03 |  |  |
| Bank account to allow default for invoice in currency and also a fall back default to show on invoice where no default for the currency. Modified BankAccounts.php and PrintCustTrans.php (Landscape only) to show the pertinent bank account given the currency of the invoice |  | Phil | 2011-06-29 |  |  |
| Make assembly items explode into components on packing slips PrintCustOrder_generic.php |  | Phil | 2011-06-28 |  |  |
| MRPDemands.php links missing ? now fixed - script fixed for quoting variable name CamelCasing |  | Exson | 2011-06-28 |  |  |
| MRP.php fixed modulus arithmetic that prevented suggesting production quantities where the quantity was less than 1 - the calculation using PanSize should round up to the nearest whole unit to manufacture |  | Exson | 2011-06-27 |  |  |
| Fixed reference to $_POST['StockID'] in WorkOrderCosting.php when updating the new weighted average cost |  | Phil | 2011-06-26 |  |  |
| Went back to no number_formatting on PurchData.php |  | Phil | 2011-06-26 |  |  |
| Went back to no number_formatting on PO_Items.php price and quantity - Brian May still reporting issues |  | Phil | 2011-06-26 |  |  |
| Made PO_PDFPurchOrder.php have the description of the order item run over several lines where it exceeds the width of the space allowed for it |  | Phil | 2011-06-25 |  |  |
| Added message to AccountGroups.php to show that child account groups cannot have changes to their account section, their profit and loss or balance sheet and sequence in TB as these properties belong only to parent account groups and are inherited by the kids |  | Phil | 2011-06-22 |  |  |
| SelectOrderItems.php includes/DefineCartClass.php repaired credit checks |  | Phil | 2011-06-23 |  |  |
| Credit_Invoice.php typos in quoting changes preventing posting credits on write offs also sql on gl account selection for write off |  | Phil | 2011-06-19 |  |  |
| Made up a includes/LanguagesArray.php to contain a list of languages and the translated name of the language - updated WWW_Users.php and UserSettings.php and CustLoginSetup.php to use this array - so the selection of a language uses the language name rather than the locale codes |  | Phil | 2011-06-18 |  |  |
| Added email to custcontacts and updated AddCustomerContacts.php SelectCustomer.php and Customers.php |  | Phil | 2011-06-14 |  |  |
| Birthdays (xhtml/indenting/quoting/casing/decimalplaces) for Areas.php, BOMInquiry.php, BOMS.php, BankAccounts.php, BankMatching.php, COGSGLPosting.php, CompanyPreferences.php, ConfirmDispatch_Invoice.php StockLocTransfer.php |  | Phil | 2011-06-13 |  |  |

## v4.04.4 - 2011-06-12

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Customers.php included telephone and email from the first branch found - removed as could be confusing. Customer contact information is stored in CustomerContacts and custbranch table |  | Phil | 2011-06-12 |  |  |
| SupplierCredit.php variables containing value case consistency |  | Phil | 2011-06-12 |  |  |
| Prices.php now updates the end date of a price when a new default (no end date) price is added |  | Phil | 2011-06-09 |  |  |
| PcExpensesTypeTab.php deletion question message fixed as does not delete any expenses set up with this expense type |  | Ricard | 2011-06-09 |  |  |
| includes/LanguageSetup.php changed to set $Locale = setlocale (LC_MESSAGES, $_SESSION['Language']); as using LC_ALL over-rides numeric and we need decimal points as . - commas stuff things up |  | Daniel Richert | 2011-06-09 |  |  |
| Added new field current to salesman table to flag if the salesman is currently still on the team or not - modified WWW_Users.php and CustomerBranches to only allow selection of current salesfolk |  | Ricard | 2011-06-09 |  |  |
| EmailCustTrans.php missing closing quote off input hidden InvOrCredit value |  | Phil | 2011-06-07 |  |  |
| Currencies table included 2 x in SQL for getting invoice details in Credit_Invoice.php |  | Phil | 2011-06-06 |  |  |
| AddSerialItems.php from Stock Adjustment was not picking up single entries because of error in for loop condition fixed |  | Phil | 2011-06-06 |  |  |
| BankMatching typo pprintf fixed |  | Phil | 2011-06-05 |  |  |

## v4.04.3 - 2011-06-03

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| ConfirmDispatchControlled_Invoice.php type include/InputSerialItems.php |  | Phil | 2011-06-02 |  |  |
| Modified build script to include audittrail table in weberp-new.sql and weberpdemo.sql - excluded by mistake |  | Phil | 2011-06-02 |  |  |
| UpgradeDatabase.php manual upgrades from 3.11.x 4.01 and 4.02 now fixed! Brian May's report |  | Phil | 2011-06-02 |  |  |
| GetConfig.php amended so that an earlier version without the emailsettings table does not choke before getting to the UpgradeDatabase.php option - reported by Brian May |  | Phil | 2011-05-31 |  |  |

## v4.04.2 - 2011-05-30

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Invoicing of serial items was not happening using includes/OutputSerialItems.php script not includes/InputSerialItems.php script |  | Phil | 2011-05-30 |  |  |
| StockSerialItems.php closing td> tag was missing / |  | Phil | 2011-05-30 |  |  |
| InputSerialItemsFile.php was looking for a hard coded reports dir now used $_SESSION['reports_dir'] |  | Phil | 2011-05-30 |  |  |
| GetConfig.php checks for existence of the decimalplaces field in currencies and inserts it before attemtpting to run the company SQL... |  | Phil | 2011-05-30 |  |  |
| PcExpensesTypeTab.php could not delete Expenses lost TabType value fixed |  | Phil | 2011-05-30 |  |  |
| PriceByCost.php now only makes a new price if the price is actually changed!! |  | Phil | 2011-05-30 |  |  |

## v4.04.1 - 2011-05-29

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| PricesByCost.php made it so the existing prices had end dates set as yesterday and new prices created from today |  | Phil | 2011-05-29 |  |  |
| TopItems.php fixed sequence and birthday to script and PDFTopItems.php script |  | Phil | 2011-05-29 |  |  |
| Could not set controlled item batches/serial numbers on ConfirmDispatch_Invoice.php |  | Phil | 2011-05-28 |  |  |
| PO_SelectOSPurchOrder.php was showing select location with gaps between locations - no slash before  `<option>` fixed |  | Ricard | 2011-05-28 |  |  |
| Added new field assigner to petty cash module and changes to PcTabs.php and PcAssignCashToTab.php |  | Ricard | 2011-05-28 |  |  |
| PcAssignCashToTab.php quoted the $Days integer parameter to the INTERVAL function incorrectly now fixed |  | Ricard | 2011-05-28 |  |  |
| Reported bug on deletion of PcExpensesTypeTab - incorrectly formed URL and parameters added ? |  | Ricard | 2011-05-28 |  |  |
| Reported mismatch of fields scripts pagesecurity changed to int(11) from tinyint as other tables all refer to as int(11) |  | Ricard | 2011-05-28 |  |  |
| Reported alignment issue with link and button on SuppTransGLAnalysis now fixed |  | R2-G | 2011-05-28 |  |  |
| MRPShortages now has an option to report excesses too |  | Ricard | 2011-05-28 |  |  |
| Fix sql to take quotes out of literals in upgrade script. PDFPrintLabels fix sql to get current price. |  | Ricard | 2011-05-28 |  |  |
| Reported by Daniel Brewer Fix SelectSalesOrder.php creation of PO with excluding redundant fields in purchorderdetails that were taken out. |  | Phil | 2011-05-28 |  |  |
| UpdateCurrencyRateDaily was set to 1 when the option to enable it was clicked - should have been set to today's date in SystemParameters - fixed. Now no error reported bu ConvertSQLDate function when user enables update currencies daily. |  | Exson | 2011-05-28 |  |  |

## v4.04 - 2011-05-26

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Require securitytoken 9 to allow user to see purchasing data in SelectProduct.php |  | Ricard | 2011-05-26 |  |  |
| SelectProduct.php fix item image display in centre under item transactions |  | Phil | 2011-05-26 |  |  |
| New Sales Inquiry scripts by sales type/price list by category and top sellers |  | Phil | 2011-05-25 |  |  |
| Updated from launchpad translations those that had changed from 18/4/11 including Viatnamese, Albanian, Russian, Spanish |  | Phil | 2011-05-24 |  |  |
| Remove SystemCheck.php and code standards changes(launchpad 4711-4718) |  | Tim | 2011-05-23 |  |  |
| Make link from SelectProduct.php to place purchase orders factor in the lead time into the delivery date in the purchase order (launchpad 4710) |  | Tim | 2011-05-23 |  |  |
| Fix PaymentMethods could not add new - comma missing in INSERT SQL |  | Exson | 2011-05-23 |  |  |
| SuppAllocs.php DefineSuppAllocClass.php made display appropriate decimal places to currency of supplier |  | Phil | 2011-05-20 |  |  |
| SuppInvGRNs.php made display appropriate decimal places to currency of supplier invoice |  | Phil | 2011-05-19 |  |  |
| SuppTransGLAnalysis.php made display appropriate decimal places to currency of supplier invoice also added an edit option and fixed narrative that was not coming through into GLtrans - also modified DefineSuppTransClass.php to hold the CurrDecimalPlaces variable |  | Phil | 2011-05-19 |  |  |
| Bug in quantity on purchase order in SelectOrderItems.php added correct join syntax |  | Phil | 2011-05-16 |  |  |
| Added upgrade from 4.03.7 to UpgradeDatabase.php |  | Phil | 2011-05-16 |  |  |
| Z_ChangeStockCode.php modified to test if MRP tables exist before doing the updates - turned off error trapping on these queries as the error trapping is done in the script |  | Phil | 2011-05-15 |  |  |
| FixedAssetCategories prior to deletion of a category check for existing assets in the category failed due to typo in SQL - fixed |  | Tim | 2011-05-15 |  |  |
| StockTransfer now checks for negative stock before allowing transfer - launchpad changes to 4691 |  | Tim | 2011-05-14 |  |  |
| CustomerInquiry.php now shows the currency decimal places |  | Phil | 2011-05-14 |  |  |
| Locations.php fix table hidden POST variable quotes mismatch - also mismatch between number of parameters in printf output |  | Phil | 2011-05-03 |  |  |
| Make order entry show the currency decimal places for amounts and totals also in invoicing SelectOrderItems.php and ConfirmDispatch_Invoice.php |  | Phil | 2011-05-02 |  |  |
| Fix PO_PDFPurchOrder.php to allow emailing but email option not to appear on printed/emailed orders |  | Phil | 2011-05-02 |  |  |
| PageSecurity.php fix bug that prevented updates to Security Token for a particular script. |  | Tim | 2011-05-01 |  |  |
| Many scripts quoting changes single quotes for strings double quotes for xhtml variables |  | Phil | 2011-05-01 |  |  |
| SecurityTokens allow deletion of tokens if no scripts using it |  | Phil | 2011-05-01 |  |  |
| Add facility to select an account group to limit GL acccounts returned as options to post payments to |  | Tim | 2011-04-28 |  |  |
| New SecurityTokens script |  | Tim | 2011-04-28 |  |  |
| Fix Secunia reported vulnerability by checking for dodgy characters in CompanyNameField - then matched to a real directory on the web-server |  | Phil | 2011-04-28 |  |  |

## v4.03.8 - 2011-04-18

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Update zh_HK.utf8, pt_BR.utf8, fa_IR.utf8 from launchpad translations |  | Phil | 2011-04-18 |  |  |
| Changed PurchData.php back to now have free form text entry of unit of measure - as suggested by Brian May - think it works better this way |  | Phil | 2011-04-18 |  |  |
| Removed redundant fields that are not used anywhere from DefinePOClass and the various function to add lines to purchase orders and to update purhcase order lines - netweight, cuft, kgs, itemno, total_quantity etc. all this data can be retrieved without duplication in purchorderdetails |  | Phil | 2011-04-18 |  |  |
| Reworked PrintCustTrans.php landscape invoice removing reliance on xml. No longer have the ability to use the form designer to manipulate the position of fields on the invoice. The form designer invoice could not print multiple page invoices |  | Phil | 2011-04-17 |  |  |
| Tim's changes in launchpad fork to 4663 - xhtml syntax fixes |  | Phil | 2011-04-16 |  |  |
| Copy Exson's traditional Chinese back to zh_HK.utf8 |  | Phil | 2011-04-15 |  |  |
| New pcAuthorizeExpenses.php that shows the current balance of the tab to the authorizer. Before the authorizer did not see this information. |  | Ricard | 2011-04-11 |  |  |
| StockLocTransfer.php added $_POST['LinesCounter'] -= 10; |  | Tim | 2011-04-11 |  |  |
| Use PHP 5 specific scandir to sort languages into alphabetic order for UserSettings and WWW_Users language selection |  | Tim/Phil | 2011-04-11 |  |  |
| AddCustomerContacts.php use single field rather than * in SQL> |  | Tim | 2011-04-10 |  |  |
| GLAccountInquiry.php show None if no tag selected |  | Tim | 2011-04-10 |  |  |
| PDFPrintLabel.php javascript fix |  | Tim | 2011-04-10 |  |  |
| Add perishable to StockTransfer.php and PDFStockTransfer |  | Tim | 2011-04-10 |  |  |
| PDFPeriodStockTransListing - new report to print off stock transactions of a specified type for a selected period>/p> |  | Tim | 2011-04-10 |  |  |
| PDFStockTransListing.php option to print off transactions by inventory location |  | Tim | 2011-04-10 |  |  |
| Stocks.php - more logical use of $New and $_POST['New'] |  | Tim | 2011-04-10 |  |  |
| Payments.php PaymentMethods.php Add new field userpreprintedstationery to payment methods to determine whether to print cheques |  | Tim | 2011-04-10 |  |  |
| includes/LanguageSetup.php - discovered solution to Turkish character set problem!! |  | Tim | 2011-04-05 |  |  |
| Couple of is_date functions left over from experiment to see if changing fixed Turkish - now removed from SupplierInvoice.php and PDFOrdersInvoiced.php |  | Phil | 2011-04-05 |  |  |
| SuppCreditGRNs was not showing old GRNs and no way to input an older date |  | Phil | 2011-04-05 |  |  |
| Fix link to create purchase order from purchasing data link on SelectProduct.php - thanks Brian May for pointing out the bug |  | Phil | 2011-03-31 |  |  |
| Updated all tranlations from the launchpad site |  | Phil | 2011-03-31 |  |  |
| Fix to make languages display immediately on change - session.inc moved includes/LanguageSetup.php down |  | Tim | 2011-03-31 |  |  |
| New ReprintGRN.php script takes a purchase order and allows any line received to have GRN(s) reprinted |  | Tim | 2011-03-30 |  |  |
| Checking for unquoted SQL and for SQL where literals quoted with double quotes rather than single - double quotes are not ANSI compatible - so making the SQL more ANSI compatible by doing this as suggested by Tim. Many many scripts involved will take a week or so |  | Phil | 2011-03-29 |  |  |

## v4.03.5 - 2011-03-27

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fixed SQL upgrade script to add the Z_ChangeSupplierCode.php script is added to the scripts table |  | Phil | 2011-03-27 |  |  |
| Fixed some SQL for ansi compatibility I had changed in error - would affect users running strict mode ansi |  | Phil | 2011-03-27 |  |  |
| Added conversion factor to item look up in PO_Items.php - also ensured quantity entry no longer trapped for commas and commas removed from numbers before committing. Also trapped for committing purchase orders with no lines |  | Phil | 2011-03-27 |  |  |

## v4.03 - 2011-03-26

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Added some error trapping to codes of pcTabTypes |  | Phil | 2011-03-26 |  |  |
| Rework PDFStockTransfer.php remove a few round trips to DB and added facility to be able to select a transfer number to reprint - added to main menu |  | Phil | 2011-03-24 |  |  |
| Added small image in middle of SelectProduct.php |  | James Wertthey | 2011-03-24 |  |  |
| Added country to packing slips |  | Phil | 2011-03-19 |  |  |
| Added Z_ChangeSupplierCode.php |  | Marcos Garcia Trejo | 2011-03-18 |  |  |
| Add orderby transdate to DailyBankTransactions.php |  | Ricard | 2011-03-18 |  |  |
| Check for Customers.php CustomerBranches.php and Stocks.php now traps codes containing spaces - as well as other illegal characters |  | Phil | 2011-03-18 |  |  |
| PricesByCost.php - made it update prices where there is already a price starting on today's date |  | Phil | 2011-03-15 |  |  |
| SelectOrderItems.php customer selection now done using a hidden $_POST rather than parsing debtorno hyphen branchcode. |  | Phil | 2011-03-15 |  |  |
| Locations.php new field for CounterSales branch code - instead of parsing it from a single field with a hyphen in it. CounterSales.php now uses the new field for customer branch |  | Phil | 2011-03-15 |  |  |
| SelectSalesOrder.php now lists with sales order value denominated in functional currency with total of listed outstadning sales orders (or quotations) shown at the bottom of the listing |  | Phil | 2011-03-14 |  |  |
| Now allow space in codes |  | Tim | 2011-03-12 |  |  |
| SelectSalesOrder.php now allows any number of sales orders to be selected and purchase orders placed for the aggregate of items on the selected sales orders |  | ? | 2011-03-12 |  |  |
| SuppPriceList.php removed a round trip to DB to get currency - fixed function to get pdf to new TCPDF Output |  | ? | 2011-03-12 |  |  |
| Fix all htmlentities to use ENTQUOTES, 'UTF-8' option so other character sets work with it |  | Exson | 2011-03-12 |  |  |
| Fix pagination of PrintCustOrder_generic.php - second copy was not restarting page numbers |  | Phil | 2011-03-10 |  |  |
| Launchpad fixes brought in MRP.php fix for table charset utf8 so joins work correctly; typeo in PO_Header preventing purchasing data being retrieved ($result not $Result); correct sql on searching for customer in SelectCreditItems.php; StockStatus.php pricing history bug resolved (4450); StockQuantityByDate.php now allowed to show for all categories - enclosed 'All' in gettext |  | Tims | 2011-03-10 |  |  |
| PO_Items.php in committing an order detail the assetid of 'Not an Asset' was being inserted to an integer field. Modified $_POST['AssetID'] to = 0 if it was 'Not an Asset' as advised by Tim |  | Tim/Phil | 2011-03-08 |  |  |
| Fix SalesAnalysis reports for TCPDF as reported by Joe Zhou |  | Phil | 2011-03-08 |  |  |
| Fix to Stocks.php to use ANSI GROUP BY for aggregate functions SQL |  | KovÃ¡cs Attila | 2011-03-05 |  |  |
| Fix to LanguageSetup.php to use utf-8 not ISO-8859-1. Phil hardcoded UTF-8 now as no dynamic changing of character set required all translations are utf-8 |  | KovÃ¡cs Attila | 2011-03-05 |  |  |
| Fix to customer login to ensure that other customers orders are not displayed when searching by customer ref or order no |  | Exson/Baran/Phil | 2011-03-03 |  |  |
| Launchpad mods to revision 4441 including change to allow supplier currency to be changed if there are no transactions already against the supplier. Ensure credit note session variable is unset before attempting to create a new credit note from the supplier form. Tim's work to add perisable expiry dates to the serial items logic - affects quite a few scripts. Headings to stock check script even if no quantity is shown. Portrait quotations. Not included change to default delivery date to the date the customer requested - left to be the current day's date. Not included Tim's unit pricing work .. yet launchpad revisions 4442-4447 inclusive |  | Tim | 2011-03-03 |  |  |
| Launchpad added category option for MRPShortages.php links with matching quotes in WorkOrderEntry |  | Phil/Tim/Peter | 2011-02-27 |  |  |
| StockUsage.php now totals usage each month even in months where there was none - average now includes months with no usage |  | Phil | 2011-02-27 |  |  |
| Fix units deletion issue reported by Exson in UnitsOfMeasure.php script - checked for none existant units field in contracts table - removed the check |  | Phil | 2011-02-27 |  |  |
| Fix PDFTopItems.php for changed variable names - now using CamelCase tried to rework to conform |  | Phil | 2011-02-22 |  |  |
| Remove options for PDFLanguage that are not unique in WWW_Users.php - make it default to the users settings in the SESSION for UserSettings.php |  | Phil | 2011-02-22 |  |  |
| Change PageSecurity array variable name to PageSecurityArray - caused problems with conflict with PageSecurity variable where register_globals = on; |  | Phil/Don | 2011-02-18 |  |  |
| PO_Items.php make locale_number_format variables turn back into numbers for > 1000 |  | Phil | 2011-02-18 |  |  |
| FixedAssetItems.php now checks for location before allowing additions |  | Phil | 2011-02-16 |  |  |
| Company preferences - changed wording of stock integration at cost removed the word standard as suggested by Exson |  | Phil | 2011-02-16 |  |  |
| SelectCreditItems.php formating/conventions lower case html and CreditInvoice.php |  | Phil | 2011-02-16 |  |  |
| Make invoicing warn the user when no taxes are defined for a tax group - i.e. there is a configuration error with taxes |  | Phil | 2011-02-15 |  |  |

## v4.03RC2 - 2011-02-15

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| UpgradeDatabase.php fix to upgrade from 4.01RC1 !! |  | Phil | 2011-02-15 |  |  |
| PrintCustTrans.php and PrintCustTransPortrait.php htmlspecialchars_decode($narrative) - conversion at the time of committing to DB needs to be unconverted. Also in ConfirmDispatch_Invoice.php |  | Phil | 2011-02-15 |  |  |
| SelectCustomer.php removed showing blank message when $msg was empty |  | Phil | 2011-02-15 |  |  |

## v4.03RC1 - 2011-02-13

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| GoodsReceivedControlled InputSerialItems and friends - now uses session identifier to ensure uniqueness is several people entering stock receipts |  | Phil | 2011-02-13 |  |  |
| Contracts.php SelectOrderItems.php customer selection mechanism reworked - was causing issues in Contracts as noted by DK Shukla |  | Phil | 2011-02-13 |  |  |
| Rework CounterSales.php so that it is now possible to sell items that have sales/purchasing tax |  | Tim | 2011-02-08 |  |  |
| Fix InventoryPlanning and InventoryPlanningPrefSupplier to only show sales order demand - excluding quotations. Also fixed for conversionfactor as now all purchase order quantities are in our normal stock units |  | Phil | 2011-02-08 |  |  |
| Fix incorrect layout of narrative on multiple lines of PDFQuotation.php as reported by Ricard Andreu |  | Phil | 2011-02-08 |  |  |
| StockLocTransfer.php can now transfer the same amount as on hand in the location - previously checked to see that the transfer was less than the quantity on hand (when checking for negative stock) |  | Tim/Ricard | 2011-02-08 |  |  |
| Payments.php and javascripts/MiscFunctions.js corrections to javascript |  | Tim | 2011-02-08 |  |  |
| PDFGrn.php turns out preview is used from the form modification script doh! Over simplificaton reversed to reinstate preview mode |  | Phil | 2011-02-08 |  |  |
| Removed debug prnMsg in FixedAssetDepreciation |  | Otandeka | 2011-02-06 |  |  |
| SystemParameters.php new option to AutoAuthorisePO when the user has authority to do so |  | Phil | 2011-02-05 |  |  |
| PO_Items.php fixed non-existant variables on Enter nominal line. Added option to automatically authorise when user is an authoriser as well as a creator |  | Phil | 2011-02-05 |  |  |
| PDFGrn.php rewrote - preview never used - simplified |  | Phil | 2011-02-05 |  |  |
| CostUpdate.php allow cost update with appropriate permissions token 10 hard coded |  | Tim | 2011-02-04 |  |  |
| SelectCustomer.php rejig selection options in more logical way. Used non-specific SQL to search by any part of the address |  | Phil | 2011-02-04 |  |  |
| PDFTopItems.php and TopItems.php removed mysql specific SQL |  | Phil | 2011-02-02 |  |  |
| GoodsReceived.php rework for supplier units and conversion factor etc now in PO class |  | Phil | 2011-01-30 |  |  |
| PDFGrn.php rework for supplier units and conversion factor etc now in PO class |  | Phil | 2011-01-30 |  |  |
| Changed PDFLowGP report remove % from gettext string |  | Exson | 2011-01-30 |  |  |
| Fix reportwriter with tcpdf using parent::__construct rather than $this->Cpdf( |  | Tim | 2011-01-29 |  |  |
| Depreciation fixes - SelectAsset by description fix |  | Phil | 2011-01-24 |  |  |
| PDFTopItems.php SQL quoting fixes |  | Peter Otandeka | 2011-01-23 |  |  |
| Fix Depreciation posting and dates of end of periods |  | Phil | 2011-01-23 |  |  |
| Changed back references throughout several MRP scripts from is_date to Is_Date - as Is_Date is used throughout the code and much bigger job to change all references to is_date |  | Phil | 2011-01-23 |  |  |
| Changed back addinfo calls to addInfo on PDF reports - Zhigio originally thought to be an issue with Turkish utf-8 pdfs but turned out to be a red herring. class.pdf call changed back to addInfo too - most of calls had not been changed to lower case info |  | Tim | 2011-01-20 |  |  |
| Changed PricesBasedOnMarkUp to have end date day before new prices effective from |  | Tim | 2011-01-20 |  |  |
| FixedAssetRegister.php FixedAssetDepreciation.php fixes |  | Phil | 2011-01-20 |  |  |
| Modified GLBudgets.php to allow entry of last years budget too |  | Phil | 2011-01-19 |  |  |
| More purchasing work removing redundant queries rationalising code - new layout with supplier units |  | Phil | 2011-01-17 |  |  |
| Removed MX record check from IsEmail function in includes/MiscFunctions.php |  | Tim | 2011-01-15 |  |  |
| SQL quoting a whole bunch of scripts - changing single quotes to doubles - didn't apply ones where no variables required quoting |  | Tim/Peter | 2011-01-15 |  |  |
| Install scripts modified to copy over FormDesigns under the new company directory created |  | Phil | 2011-01-14 |  |  |
| Updates to manual for security and supplier invoice entry |  | Phil | 2011-01-14 |  |  |
| PO_Items.php remove redundant code, setup entry of lines in supplier units |  | Phil | 2011-01-13 |  |  |
| Z_ChangeCustomerCode.php now has foreign key checks defeated when deleting the old customer record and custbranch record |  | Tim | 2011-01-13 |  |  |
| SupplierInvoice.php and SupplierCredit.php now check to ensure the total of contracts fixed asset charges goods received charges, shipment charges and GL charges are at least equal to the amount of the invoice or credit. It was possible when GL interface turned off to get strange results |  | Phil | 2011-01-13 |  |  |
| DailyBankTransactions.php made it so a range of dates can be selected but defaults to just today |  | Phil | 2011-01-12 |  |  |
| Fix choice of portrait or landscape invoices - fix landscape default form layout. Fix portrait invoice logo position |  | Phil | 2011-01-11 |  |  |
| Fix customer transaction inquiries to show correct links where user is not authorised for credit notes or GL inquiries |  | Phil | 2011-01-11 |  |  |
| SupplierCredit.php and SupplierInvoice.php recalculate price variance to post differences on fixed asset additions correctly |  | Phil | 2011-01-11 |  |  |
| PO_PDFPurchOrder.php - fixed for coding conventions removed uneccessary sql calls |  | Phil | 2011-01-11 |  |  |
| Emailing invoices was writing the pdf file twice - once with fwrite and once with the TCPDF output function with the option 'F' |  | Murray Collingwood | 2011-01-11 |  |  |
| Z_ChangeCustomerCode.php added typeid field that made change customer code fail. Also corrected typo for foreign key checking side stepping for Z_ChangeBranchCode.php |  | Ricard Andreu | 2011-01-08 |  |  |
| Bug fixes AssetLocationTransfer and Supplier Contacts |  | Phil | 2011-01-05 |  |  |
| Bug# 3151192 - insert underscore for superglobal. |  | Paul | 2011-01-04 |  |  |
| Bug fixes AssetLocationTransfer and Supplier Contacts |  | Phil | 2011-01-05 |  |  |
| Start rework of purchase order scripts ... again. |  | Phil | 2011-01-04 |  |  |
| Select Purchase orders now defaults to just pending and authorised/printed - other statii are options |  | Phil | 2011-01-04 |  |  |
| Upgrade script make capable of upgrades from any earlier version - and email a backup to the user. Deleted DBUpgradeNumber config variable now use the VersionNumber already there |  | Phil | 2011-01-01 |  |  |
| Tidy up of CounterSales.php - CamelCasing, quoting SQL, closing slashes in xhtml tags |  | Tim | 2010-12-31 |  |  |
| Reverted to single SQL upgrade file per release - but retaining Tim's upgrade mechanism if the DBUpgradeNumber is out of date. Removed pseudo SQL language required for upgrade script and the 52 update files - just applies plain vanilla SQL from the scripts required |  | Phil | 2010-12-29 |  |  |
| DB upgrade mechanism with separate pseudo SQL for each database change in a separate file + 52 files of updates since 3.11.4 |  | Tim | 2010-12-29 |  |  |
| Reworked PDFPrintLabels.php to conform to standards and corrected SQL for prices |  | Phil | 2010-12-21 |  |  |
| PDFPrintLabels.php was not checking for end date of prices - fixed |  | Ricard Andreu | 2010-12-21 |  |  |
| SelectOrderItems.php ConfirmDispatch_Invoice.php add code to handle asset disposals. |  | Phil | 2010-12-19 |  |  |
| Modify purchasing scripts for coding conventions/readability |  | Phil | 2010-12-14 |  |  |
| Have populated the new field stockcheckdate in stockcheckfreeze and modified PDFStockCheckComparison to use this field when posting the GL - stockmoves need to be on the current day otherwise historical balances will all need to be updated. But narrative shows the date of the stock check for which the adjustment is being made |  | Phil | 2010-12-11 |  |  |
| Highlighted a bug in SupplierInvoice.php (and also in SupplierCredit.php) where the due date of the invoice/credit was not calculated correctly based on the terms - it was picking up the current date rather than the invoice/credit date. Now fixed |  | James Murray | 2010-12-09 |  |  |
| Fixed bug in SuppPaymentRun.php - was not showing anything as the test to see if there was anything to see was using a non-existant result set! |  | James Murray | 2010-12-08 |  |  |
| Estonian translation |  | Matt Elbrecht | 2010-12-03 |  |  |
| Changed table structure of new fixedassettrans and modified upgrade script - those who already ran that bit will need to change the table again. Modified fixed asset scripts again. New fixed assets manual |  | Phil | 2010-11-30 |  |  |
| CreditStatus.php - Fix bug in sql statement |  | Exson | 2010-11-28 |  |  |
| OutstandingGRNs.php - Only show when the invoiced qty is less than the GRN qty |  | Tim | 2010-11-28 |  |  |
| All fixed asset scripts SupplierInvoice.php SupplierCredit.php SuppFixedAssetChgs.php - adding fixed assets directly from invoice charges - and reversing additions with credit notes. Also FixedAssetRegister.php report to print PDF or export CSV of fixed assets now includes date range depreciation and b/fwd cost b/fwd accum depn and c/fwd cost and accum depn - and NBV |  | Phil | 2010-11-28 |  |  |
| Unset session in ConnectDB_mysqli.inc and ConnectDB_mysql.inc when the login fails |  | James Murray | 2010-11-28 |  |  |
| install/index.php check for critical requirements before allowing install to proceed |  | Phil | 2010-11-27 |  |  |
| includes/DefinePOClass.php change variable to camel case also PO_Header.php PO_Items.php PO_ReadInOrder.inc GoodsReceived.php - scope for error needs lots of testing please |  | Phil | 2010-11-27 |  |  |
| PurchData - fix MinOrderQty error trapping with appropriate text and default to 1 |  | Phil | 2010-11-27 |  |  |
| SupplierInvoice.php removed check on weighted average costing to reverse GRN suspense posting and set cost to zero - this was defeating the price variance calculation and the proper valuation of stock. Also took out of the calculations the conversionfactor - will need to alter goods received code to ensure only our units recorded in quantity received. |  | Phil | 2010-11-27 |  |  |
| Upgrade3.11.1-4.00.sql - Add default date for stockcheckdate field in stockcheckfreeze table |  | Tim | 2010-11-26 |  |  |
| CounterSales.php - Fix bug in counter sales script. |  | Otandeka | 2010-11-26 |  |  |
| GoodsReceived.php - modified to insert fixedassettrans and to post nominal POs to fixed asset cost account from fixedassetcategories.costact |  | Phil | 2010-11-24 |  |  |
| MRP.php and MRPShortages.php fixed temporary tables to use utf-8 - code failed without probably depends on mysql server settings |  | Pak Ricard | 2010-11-23 |  |  |
| Rewritten FixedAssetJournal.php - renamed FixedAssetDepreciation.php |  | Phil | 2010-11-21 |  |  |
| Rewritten FixedAssetItems.php FixedAssetCategories.php and modified FixedAssetLocations.php to use the new structure |  | Phil | 2010-11-20 |  |  |
| upgrade3.11.1-4.00.sql - Fix sql syntax errors brought in on recent changes. |  | Tim | 2010-11-18 |  |  |
| SelectAsset.php script reworked SelectAssetType.php script now deleted |  | Phil | 2010-11-14 |  |  |
| Z_ImportStocks.php - Bug fixes. |  | Exson | 2010-11-08 |  |  |
| ShiptChgs.php - made a check to ensure a shipment reference entered manully actually exists before it is added - otherwise a nasty error occurs on commital of the invoice |  | Phil | 2010-11-06 |  |  |
| InputSerialItemsSequential.php - Bug# 3080130 - Add new FormID to form. (and minor cleanup) |  | Paul | 2010-11-06 |  |  |
| ReorderLevelLocation.php - Remove fixed assets from selections |  | Tim | 2010-11-06 |  |  |
| ReorderLevel.php - Remove fixed assets from selections |  | Tim | 2010-11-06 |  |  |
| InventoryQuantities.php - Remove fixed assets from selections |  | Tim | 2010-11-06 |  |  |
| Selectproduct.php - Remove fixed assets from selections |  | Tim | 2010-11-06 |  |  |
| InventoryValuation.php - Ensure fixed assets dont get shown in valuation report |  | Tim | 2010-11-06 |  |  |
| FixedAssetItems.php - Fixed typo preventing Item code being shown |  | Tim | 2010-11-06 |  |  |
| StockCategory.php FixedAssetCategory.php attempt to add validation to depreciation rates by extending the stock category property logic with new fields for numericvalue, minimumvalue and maximumvalue. Then adding the depreciation rate percentage property to expect numeric values with a minimum of 0 and maximum of 100. |  | Phil | 2010-11-06 |  |  |
| PurchData.php. Converted field name to lower case for consistency |  | Tim | 2010-11-05 |  |  |
| SelectProduct.php. Change the standard order quantities to agree with Minimum order qty |  | Tim | 2010-11-05 |  |  |
| PurchData.php. Make the Minimum order quantity field numeric only |  | Tim | 2010-11-05 |  |  |
| SelectProduct.php,PurchData.php. Add in Minimum order quantity to Purchasing data |  | Poul Bjerre-Jensen | 2010-11-05 |  |  |
| SelectCustomer.php. Correction to work with debtors that have a - in the code |  | Tim | 2010-11-02 |  |  |
| SuppTransGLAnalysis.php. Minor bug fixes |  | Tim | 2010-10-31 |  |  |
| SuppTransGLAnalysis.php. Force the user to select a GL account code instaed of defaulting to first on the list |  | Tim | 2010-10-31 |  |  |
| CustomerReceipt.php. Force the user to select a GL account code and a Bank Account instaed of defaulting to first on the list |  | Tim | 2010-10-31 |  |  |
| index.php. If CustomerReceipt selected from the GL menu then go to GL receipts else customer receipts |  | Tim | 2010-10-29 |  |  |
| Payments.php. Force the user to select a GL account code and a Bank Account instaed of defaulting to first on the list |  | Tim | 2010-10-29 |  |  |
| GLJournal.php. Force the user to select a GL account code instaed of defaulting to first on the list |  | Tim | 2010-10-29 |  |  |
| Fix bug # 3017709. When bulk transfers are received for controlled items serial numbers are required |  | Tim | 2010-10-28 |  |  |
| PcExpensesTypeTab.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-10-28 |  |  |
| PcTabs.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-10-28 |  |  |
| PcTypeTabs.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-10-28 |  |  |
| PcReportTab.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-10-27 |  |  |
| PcClaimExpensesFromTab.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-10-27 |  |  |
| class.pdf.php - Fix bug in html_entity_decode() function call |  | Tim | 2010-10-27 |  |  |
| ManualPurchaseOrdering.html - Improvements to purchase ordering manual |  | d.k shukla | 2010-10-27 |  |  |
| WorkOrderEntry.php - When the quantities are changed, then the correct quantities are updated, and the date picker chooses the correct date. |  | Tim | 2010-10-27 |  |  |
| header.inc - Correct for non ascii characters |  | Tim | 2010-10-27 |  |  |
| Corrections to display multi line invoice narratives correctly |  | Tim | 2010-10-27 |  |  |
| Discountmatrix.php - Fix discount category bug |  | Tim | 2010-10-26 |  |  |
| Discountmatrix.php - Increase the number of decimal places that can be entered |  | Tim | 2010-10-26 |  |  |
| StockLocTransfer.php - Check there is sufficient stock for the transfer |  | Tim | 2010-10-26 |  |  |
| New labelprinting functionality |  | Marcos Garcia Trejo | 2010-10-25 |  |  |
| class.pdf.php - correctly display some html encoded special characters, to make them human readeable in pdf file. |  | ChenJohn | 2010-10-25 |  |  |
| PurchData.php - search by name failed concatenation of SQL stuffed now repaired. |  | Phil | 2010-10-23 |  |  |

## v4.0RC1 - 2010-10-22

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| MiscFunctions.js - Bug #3060329. Correct condition check. [allows the calendar to start with the textbox's date] |  | Paul | 2010-10-20 |  |  |
| WorkOrderReceive.php - Bug #3023776. Applied anonymous contribution. |  | Paul | 2010-10-18 |  |  |
| SupplierInvocie.php fixed for mix up with commits - now shipment charges added correctly and contract charges also |  | Phil | 2010-10-16 |  |  |
| MiscFunctions.php - Fix bug preventing download of ECB rates |  | Tim | 2010-10-14 |  |  |
| WWW_Users.php - Show the last visit date correctly. Fixes bug 3085860 |  | Tim | 2010-10-13 |  |  |
| Added xmlrpc_GetStockCategoryList api method |  | Phil | 2010-10-09 |  |  |
| upgrade3.11.1-3.12.sql - Update tables to utf8 |  | Matt Taylor | 2010-10-04 |  |  |
| ManualPurchaseOrdering.php - Manual for purchase ordering system |  | Gabriel Olowo | 2010-10-03 |  |  |
| AuditTrail.php - Bug fixes and layout changes |  | Tim | 2010-10-02 |  |  |
| DailySalesInquiry.php - Bug fixes and layout changes |  | Tim | 2010-10-02 |  |  |
| session.inc - Corrections to sql quoting. Resolves bug 3023782 |  | Tim | 2010-10-02 |  |  |
| ppdf_tpl.php - Make php 5.3 compatible |  | Tim | 2010-10-02 |  |  |
| GLJournal.php - Fix to create a reversing journal even when non english language is used. |  | Tim | 2010-10-02 |  |  |
| PDFGrnHeader.php - Show correct column headings. Fixes bug 3072507 |  | Tim | 2010-10-02 |  |  |
| Add form verification to prevent form spoofing |  | Tim | 2010-09-30 |  |  |
| WWW_Users.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-30 |  |  |
| WWW_Access.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-30 |  |  |
| WOSerialNos.php - SQL quoting corrections |  | Tim | 2010-09-29 |  |  |
| WorkOrderStatus.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-29 |  |  |
| WorkOrderReceive.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-29 |  |  |
| WorkOrderIssue.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-29 |  |  |
| WorkOrderEntry.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-29 |  |  |
| WorkOrderCosting.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| WorkCentres.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| WhereUsedInquiry.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| UserSettings.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| UnitsOfMeasure.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| TopItems.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| TaxProvinces.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| TaxGroups.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| TaxCategories.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| TaxAuthorityRates.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| TaxAuthorities.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| Tax.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| SystemParameters.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| SuppTransGLAnalysis.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| SuppShiptCharges.php - Layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| SuppPriceList.php - Layout changes and improvements |  | Tim | 2010-09-28 |  |  |
| Various default.css files -- Minor CSS corrections. |  | Paul Thursby | 2010-09-27 |  |  |
| SuppPaymentRun.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SuppLoginSetup.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SupplierTypes.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SupplierTransInquiry.php - New script to show detail supplier transactions |  | Tim | 2010-09-27 |  |  |
| SupplierTenders.php - SQL quoting corrections |  | Tim | 2010-09-27 |  |  |
| Suppliers.php - SQL quoting corrections |  | Tim | 2010-09-27 |  |  |
| SupplierInvoice.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SupplierInquiry.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SupplierCredit.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SupplierContacts.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SupplierBalsAtPeriodEnd.php - Layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SupplierAllocations.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SuppInvGRNs.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| SuppCreditGRNs.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| StockUsage.php - Layout changes and improvements |  | Tim | 2010-09-27 |  |  |
| StockTransfers.php - Layout changes and improvements |  | Tim | 2010-09-26 |  |  |
| StockSerialItemResearch.php - Layout changes and improvements |  | Tim | 2010-09-26 |  |  |
| StockReorderLevel.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-26 |  |  |
| StockQuantityByDate.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-26 |  |  |
| StockQties_csv.php - Layout changes and improvements |  | Tim | 2010-09-26 |  |  |
| StockMovements.php - Layout changes and improvements |  | Tim | 2010-09-26 |  |  |
| StockLocStatus.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-26 |  |  |
| StockDispatch.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-26 |  |  |
| StockCounts.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-26 |  |  |
| StockCostUpdate.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-26 |  |  |
| StockCategories.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-26 |  |  |
| SpecialOrder.php - SQL quoting corrections |  | Tim | 2010-09-26 |  |  |
| SMTPServer.php - SQL quoting corrections |  | Tim | 2010-09-25 |  |  |
| ShiptsList.php - Add script to show list of open shipments for selected supplier |  | Tim | 2010-09-25 |  |  |
| Shipt_Select.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-25 |  |  |
| Shippers.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-25 |  |  |
| Shipments.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-25 |  |  |
| ShipmentCosting.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-25 |  |  |
| SelectWorkOrder.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-25 |  |  |
| SelectSalesOrder.php - Layout changes and improvements and bug fixes |  | Tim | 2010-09-24 |  |  |
| SelectRecurringSalesOrder.php - Layout changes and improvements and bug fixes |  | Tim | 2010-09-24 |  |  |
| SelectProduct.php - SQL quoting corrections |  | Tim | 2010-09-24 |  |  |
| SelectOrderItems.php - SQL quoting corrections |  | Tim | 2010-09-24 |  |  |
| SelectGLAccount.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-24 |  |  |
| SelectCustomer.php - Layout changes and improvements and bug fixes |  | Tim | 2010-09-24 |  |  |
| SelectCreditItems.php - SQL quoting corrections and layout changes and improvements and bug fixes |  | Tim | 2010-09-24 |  |  |
| SelectAssetType.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| SalesTypes.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| SalesPeople.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| SalesGraph.php - Fix deprecated use of assigning by reference |  | Tim | 2010-09-23 |  |  |
| SalesGLPostings.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| SalesCategories.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| SalesAnalRepts.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| SalesAnalReptCols.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| ReverseGRN.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| ReorderLevelLocation.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| ReorderLevel.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| RecurringSalesOrdersProcess.php - SQL quoting corrections |  | Tim | 2010-09-23 |  |  |
| RecurringSalesOrders.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-23 |  |  |
| DateFunctions.inc - mktime() function without paramaeters is now deprecated, replaced with time() |  | Tim | 2010-09-23 |  |  |
| PricesByCost.php - Restrict price changes to those stock items not discontinued |  | Pak Ricard | 2010-09-20 |  |  |
| PurchData.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-14 |  |  |
| PrintSalesOrder_generic.php - SQL quoting corrections |  | Tim | 2010-09-14 |  |  |
| PrintCustTransPortrait.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-14 |  |  |
| PrintCustTrans.php - SQL quoting corrections and layout changes and improvements |  | Tim | 2010-09-13 |  |  |
| PrintStatements.php - Small bug fixes |  | Tim | 2010-09-13 |  |  |
| PrintCustOrder_Generic.php - Correct the sql quoting |  | Tim | 2010-09-13 |  |  |
| PrintCustOrder.php - Correct the sql quoting |  | Tim | 2010-09-13 |  |  |
| PricesBasedOnMarkup.php - Correct the sql quoting, and various layout improvements and bug fixes |  | Tim | 2010-09-13 |  |  |
| Prices_Customer.php - Correct the sql quoting, and various layout improvements and bug fixes |  | Tim | 2010-09-13 |  |  |
| Prices.php - Correct the sql quoting, and various layout improvements and bug fixes |  | Tim | 2010-09-13 |  |  |
| POReport.php - Format correctly for screen and add in option to export as a csv file |  | Tim | 2010-09-13 |  |  |
| PO_SelectPurchOrder.php - Correct the sql quoting, and various layout improvements |  | Tim | 2010-09-11 |  |  |
| PO_SelectOSPurchOrder.php - Correct the sql quoting |  | Tim | 2010-09-11 |  |  |
| PO_PDFPurchOrder.php - Correct the sql quoting |  | Tim | 2010-09-11 |  |  |
| PDFPriceList.php - Corrected undefined offset error |  | Otandeka | 2010-09-11 |  |  |
| Manual changes - for utf-8 - language and PDFlanguage selection, also the installer and the new CounterSales.php script |  | Phil | 2010-09-11 |  |  |
| PO_OrderDetails.php - Layout improvements, links to SelectSuppliers.php and sql quoting corrections |  | Tim | 2010-09-10 |  |  |
| PO_Items.php - Layout improvements, and sql quoting corrections |  | Tim | 2010-09-10 |  |  |
| PO_Header.php - Layout improvements, and sql quoting corrections |  | Tim | 2010-09-10 |  |  |
| PO_AuthoriseMyOrders.php - Layout improvements, and sql quoting corrections |  | Tim | 2010-09-10 |  |  |
| PO_AuthorisationLevels.php - Layout improvements, and sql quoting corrections |  | Tim | 2010-09-10 |  |  |
| PeriodsInquiry.php - Layout improvements |  | Tim | 2010-09-10 |  |  |
| ConnectDB_mysql.inc ConnectDB_mysqli.inc fix utf-8 data being encoded/stored incorrectly in db server |  | ChenJohn | 2010-09-10 |  |  |
| PDFTopItems.php - Correct the sql quoting |  | Tim | 2010-09-08 |  |  |
| PDFSuppTransListing.php - Screen layout improvements. Correct the sql quoting |  | Tim | 2010-09-08 |  |  |
| PDFStockTransfer.php - Correct the sql quoting |  | Tim | 2010-09-08 |  |  |
| PDFStockNegatives.php - Fix missing sql error message and correct the default date format in the heading |  | Tim | 2010-09-08 |  |  |
| PDFStockCheckComparison.php - Screen layout improvements. Correct the sql quoting |  | Tim | 2010-09-08 |  |  |
| PDFRemittanceAdvice.php - Correct the sql quoting |  | Tim | 2010-09-07 |  |  |
| PDFReceipt.php - Correct the sql quoting |  | Tim | 2010-09-07 |  |  |
| PDFQuotation.php - Correct the sql quoting |  | Tim | 2010-09-07 |  |  |
| PDFPickingList.php - Correct the sql quoting |  | Tim | 2010-09-07 |  |  |
| PDFOrderStatus.php - Improve report layout for readability |  | Tim | 2010-09-07 |  |  |
| PDFOrderInvoiced.php - Improve report layout for readability |  | Tim | 2010-09-07 |  |  |
| PO_Header.php - Move dummy status array from DefinePOClass.php |  | Tim | 2010-09-06 |  |  |
| upgrade3.11.1-3.12.sql - Change syntax to work in both windows and linux |  | Tim | 2010-09-06 |  |  |
| SupplierInvoice.php - Correct the roundings so that the double entry balances |  | Tim | 2010-09-06 |  |  |
| PO_AuthoriseMyOrders.php - Update correct status when language not English |  | Tim | 2010-09-05 |  |  |
| PcAssignCashToTab.php - Show authorised, notes, and receipt fields correctly. Changed to use DB_fetch_array() rather than DB_fetch_row(). |  | Tim | 2010-09-05 |  |  |
| PDFStockTransListing.php - List stock transactions by transaction type. |  | Tim | 2010-09-05 |  |  |
| upgrade3.11.1-3.12.sql - Database changes required for changes to report writer. |  | Ricard | 2010-09-05 |  |  |
| StockLocTransfer.php - Change quantity input field to have more than 5 digits |  | Tim | 2010-08-31 |  |  |
| Fix ConfirmDispatchInvoice.php assembly components new qty on hand |  | Marcos Garcia Trejo | 2010-08-28 |  |  |
| Add in requisition number into purchase order prints. |  | Tim | 2010-08-27 |  |  |
| Fix bugs introduced by Paul's patch prnMsg= should be prnMsg() BOMs.php also did in Contracts.php |  | Phil | 2010-08-21 |  |  |
| Contract closing changes all the contracts scripts |  | Phil | 2010-08-21 |  |  |
| More $msg cleanup. Unused eliminated; Others replaced with prnMsg(). |  | Paul Thursby | 2010-08-20 |  |  |
| Decided to have contracts as part of orders module since not really enough links to warrant a new module changes to index.php WWW_Users.php and sql upgrade. |  | Phil | 2010-08-15 |  |  |
| New script for ContractCosting.php comparison of contract costs budgeted vs incurred. Lot of work on contracts |  | Phil | 2010-08-15 |  |  |
| DefineSuppTransClass.php SupplierInvoice.php and SupplierCredit.php now allow entry of contract charges. New script for SuppContractChgs.php |  | Phil | 2010-08-13 |  |  |
| Fixes to show creditors transactions correctly in GLTransInquiry.php signs mixed up and period not shown previously |  | Phil | 2010-08-13 |  |  |
| Fix SelectOrderItems.php width of narrative box was making screen unusable!! |  | Russell (Regal Prods) | 2010-08-10 |  |  |
| Revised report writer with the ability for more fields |  | Pak Ricard | 2010-08-18 |  |  |
| PO_Items.php - Eliminate query; Moves the ONE field into a query above this point. |  | Paul Thursby | 2010-08-07 |  |  |
| SelectProduct.php - Variable $msg will never print. Elimnate to use function prnMsg() instead. |  | Paul Thursby | 2010-08-07 |  |  |
| Contracts.php SelectContracts.php DeliveryDetails.php - more work to convert Contracts to quotations and on conversion of contract quotations to orders to create contract work order to issue materials to |  | Phil | 2010-08-08 |  |  |
| PDFLowGP.php - Sql quoting correction, layout changes, and assorted minor bug corrections |  | Tim | 2010-08-07 |  |  |
| PO_Items.php - Sql quoting correction, layout changes, and assorted minor bug corrections |  | Tim | 2010-08-07 |  |  |
| SelectCustomer.php - Unset $result variable once used as it was causing search errors later in the script |  | Tim | 2010-08-07 |  |  |
| Purchase Ordering - Set the allow print flag when the order is re-authorised |  | Tim | 2010-08-06 |  |  |
| DailyBankTransactions.php - Show currencies correctly when transaction is in different currency to bank currency |  | Tim | 2010-08-02 |  |  |
| Payments.php - Syntax error in sql statement |  | Tim | 2010-07-30 |  |  |
| Change locatransfers table so that recd quantity on bulk stock transfers is a double field not integer |  | Simon Peter Otandeka | 2010-07-28 |  |  |
| class.pdf.php - Ensure ellipse() method has compatible declaration with ancestor class |  | Tim | 2010-07-28 |  |  |
| class.pdf.php - Ensure line() method has compatible declaration with ancestor class |  | Tim | 2010-07-28 |  |  |
| PDFGrn.php - Sql quoting corrections, bug fixes |  | Tim | 2010-07-26 |  |  |
| PDFDIFOT.php - Sql quoting corrections, bug fixes and layout changes |  | Tim | 2010-07-26 |  |  |
| PDFDeliveryDifferences.php - Sql quoting corrections, bug fixes and layout changes |  | Tim | 2010-07-26 |  |  |
| PDFCustTransListing.php - Sql quoting corrections and layout changes |  | Tim | 2010-07-26 |  |  |
| PDFCustomerList.php - Sql quoting corrections and layout changes |  | Tim | 2010-07-26 |  |  |
| PDFChequeListing.php - Sql quoting corrections, add javascript date picker, and layout changes |  | Tim | 2010-07-26 |  |  |
| PDFBankingSummary.php - Sql quoting corrections |  | Tim | 2010-07-26 |  |  |
| DailyBankTransactions.php - New script to show daily bank transactions in bank account currency, and local currency |  | Tim | 2010-07-26 |  |  |
| PrintCustTrans.php - Correctly show decimal places |  | Tim | 2010-07-25 |  |  |
| SelectContract.php new script to select a contract |  | Phil | 2010-07-25 |  |  |
| ContractOtherReqts.php new script to enter other requirements for a contract |  | Phil | 2010-07-25 |  |  |
| Contracts.php and includes/DefineContractClass.php debugging |  | Phil | 2010-07-25 |  |  |
| GLAccountInquiry.php - Add in running balance. Cumulative for BS account, and period for PL accounts. |  | Tim | 2010-07-22 |  |  |
| FixedAssetList.php - New script that provides a simple list of assets and their properties. |  | Simon Peter Otandeka | 2010-07-22 |  |  |
| FixedAssetJournal.php - Corrections to depreciation calculation layout changes, and sql quoting |  | Tim | 2010-07-22 |  |  |
| SalesGraph.php - Update to latest phplot, add in choice of graph types layout changes, and sql quoting |  | Tim | 2010-07-22 |  |  |
| PcAuthoriseExpenses.php - Layout changes, and sql quoting |  | Tim | 2010-07-20 |  |  |
| FixedAssetRegister.php - Layout changes, correctly export to csv, and sql quoting |  | Tim | 2010-07-20 |  |  |
| StockLocStatus.php - Add a filter to only show stock with available balances |  | Tim | 2010-07-20 |  |  |
| StockLocMovements.php - Add in the new quantity on hand field |  | Tim | 2010-07-20 |  |  |
| TopItems.php - Show value in functional currency, and show decimal places correctly |  | Tim | 2010-07-20 |  |  |
| Daily Transactions listings - Show reports correctly even in mysql strict mode |  | Tim | 2010-07-20 |  |  |
| FixedassetTransfer.php - Layout changes and sql quoting, use javascript to change location |  | Tim | 2010-07-19 |  |  |
| FixedassetLocations.php - Layout changes and sql quoting, proper checks for deletion of location |  | Tim | 2010-07-19 |  |  |
| PO_SelectOSPurchOrder.php - Remove order values when security token 12 is not set |  | Tim | 2010-07-19 |  |  |
| SelectProduct.php - Change prices security to token 12 |  | Tim | 2010-07-19 |  |  |
| FixedAssetCategories.php - Layout changes and sql quoting |  | Tim | 2010-07-19 |  |  |
| FixedAssetItems.php - Layout changes and include editing of item type |  | Tim | 2010-07-19 |  |  |
| SelectAssetType.php - New script to choose an asset type for editing |  | Tim | 2010-07-19 |  |  |
| PCAssignCashToTab.php - Layout changes, sql quoting |  | Tim | 2010-07-18 |  |  |
| StockAdjustments.php - Provide option for tagging stock adjustments, change layout, allow for searching for a part code, and sql quoting |  | Tim | 2010-07-18 |  |  |
| CounterSales.php - was not retrieving ReceiptNumber if the debtor integration was disabled - also the correct period was not retrieved because the date format was not in the correct DateFormat because of a typo in SESSION['DefaultDateFormat'] |  | Phil | 2010-07-18 |  |  |
| New script ContractBOM.php to allow defining the bill of materials for a contract |  | Phil | 2010-07-18 |  |  |
| Further work on Contracts.php |  | Phil | 2010-07-18 |  |  |
| PaymentTerms.php - Bug fixes, SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| Payments.php - Bug fixes, SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| PaymentMethods.php - SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| PaymentAllocations.php - SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| OutstandingGRNs.php - SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| SelectCompletedOrder.php - SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| OrderDetails.php - SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| OffersReceived.php - SQL quoting and provide confirmation message at the end |  | Tim | 2010-07-17 |  |  |
| MRPShortages.php - SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| MRPReschedules.php - SQL quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| MRPReport.php - Allow stock item search and selection, bug fixes, sql quoting and layout changes |  | Tim | 2010-07-17 |  |  |
| Stocks.php - Correctly show item category properties, layout changes, quoting of sql, and deal with images correctly |  | Tim | 2010-07-17 |  |  |
| GLTagProfit_Loss.php - Change report to show tag name in the heading |  | Tim | 2010-07-15 |  |  |
| StockCheck.php - Bug fixes to show decimal places correctly and other fixes, fix sql quoting errors and layout changes |  | Tim | 2010-07-15 |  |  |
| MRPPlannedWorkOrders.php - Fix sql quoting errors and layout changes |  | Tim | 2010-07-14 |  |  |
| MRPPlannedPurchaseOrders.php - Bug fixes, correctly do the workflow, fix sql quoting errors and layout changes |  | Tim | 2010-07-13 |  |  |
| Add logo.png file and select png before jpg if present |  | Tim | 2010-07-13 |  |  |
| Move version number to config table in database |  | Tim | 2010-07-13 |  |  |
| MiscFunctions.php - Correctly show sourceforge logo only when an internet connection exists |  | Gjergj Sheldija | 2010-07-13 |  |  |
| MRPDemandTypes.php - Bug fixes, correctly do the workflow, fix sql quoting errors and layout changes |  | Tim | 2010-07-13 |  |  |
| MRPDemands.php - Bug fixes, correctly do the workflow, fix sql quoting errors and layout changes |  | Tim | 2010-07-13 |  |  |
| MiscFunctions.js - Revert formatting changes till I can ascertain why the numbers only function stopped working |  | Tim | 2010-07-13 |  |  |
| MRPCreateDemand.php - Layout changes, sql quoting corrections |  | Tim | 2010-07-12 |  |  |
| MRPCalender.php - Layout changes, sql quoting corrections |  | Tim | 2010-07-12 |  |  |
| MRP.php - Layout changes, sql quoting corrections |  | Tim | 2010-07-12 |  |  |
| Locations.php - Bug Fixes, Layout changes, sql quoting corrections |  | Tim | 2010-07-12 |  |  |
| PDFGrn.php - Correct conversion factor bug |  | Tim | 2010-07-12 |  |  |
| InventoryValuation.php - Layout changes |  | Tim | 2010-07-11 |  |  |
| InventoryPlanningPrefSupplier.php - Bug fixes, layout changes, sql quoting fixed |  | Tim | 2010-07-11 |  |  |
| InventoryPlanningPrefSupplier.php - Layout changes, sql quoting fixed |  | Tim | 2010-07-11 |  |  |
| InventoryPlanning.php - Layout changes, sql quoting fixed |  | Tim | 2010-07-11 |  |  |
| Goods Receiving - Fix conversion factor bug, layout changes, sql quoting fixed |  | Tim | 2010-07-11 |  |  |
| DefineCartClass.php - Fix typo in variable declaration |  | Tim | 2010-07-11 |  |  |
| StockTransfers - Bug fixes, correctly round the decimal places, fix sql quoting errors and layout changes |  | Tim | 2010-07-10 |  |  |
| Bulk Transfers - Bug fixes, correctly round the decimal places, fix sql quoting errors and layout changes |  | Tim | 2010-07-10 |  |  |
| GLTrialBalance.php - Bug fixes, layout improvements changed period number to date and year and sql fixes |  | Tim | 2010-07-09 |  |  |
| GLTransInquiry.php - Layout improvements changed period number to date and year and sql fixes |  | Tim | 2010-07-09 |  |  |
| GLTags - Layout improvements and sql fixes |  | Tim | 2010-07-09 |  |  |
| includes/DateFunctions.inc added assumption for 2 digit years to d/m/Y m/d/Y and Y/m/d formats |  | Phil | 2010-07-09 |  |  |
| Prices.php fixed error trapping to allow end dates of 0000-00-00 - the default no end date used by the logic |  | Phil | 2010-07-09 |  |  |
| GLTagProfit_Loss - Layout improvements and sql fixes |  | Tim | 2010-07-08 |  |  |
| GLProfit_Loss - Layout improvements and sql fixes |  | Tim | 2010-07-08 |  |  |
| GLJournal - Layout improvements and sql fixes |  | Tim | 2010-07-08 |  |  |
| GLBudgets - Bug fixes and layout improvements and sql fixes |  | Tim | 2010-07-08 |  |  |
| index.php - Correct typo |  | Tim | 2010-07-08 |  |  |
| ManualPettyCash.html - Correct typo |  | Tim | 2010-07-08 |  |  |
| GLBalanceSheet.php - Layout improvements and properly quoting sql |  | Tim | 2010-07-07 |  |  |
| GLAccounts.php - Layout improvements and properly quoting sql |  | Tim | 2010-07-07 |  |  |
| GLAccountInquiry.php - Layout improvements and properly quoting sql |  | Tim | 2010-07-07 |  |  |
| GeocodeSetup.php - Properly quoting sql |  | Tim | 2010-07-07 |  |  |
| FreightCosts.php - Layout improvements and properly quoting sql |  | Tim | 2010-07-07 |  |  |
| Factors.php - Redo much of script to conform more with webERP standards |  | Tim | 2010-07-07 |  |  |
| ExchangeRateTrend.php - Add Javascript, and layout improvements |  | Tim | 2010-07-07 |  |  |
| EDIMessageFormat.php - Bug fixes with updating the format |  | Tim | 2010-07-07 |  |  |
| DiscountMatrix.php - Layout improvements |  | Tim | 2010-07-05 |  |  |
| DiscountCategories.php - Layout improvements |  | Tim | 2010-07-05 |  |  |
| DemandWorkOrders.php - Remove redundant file |  | Tim | 2010-07-05 |  |  |
| DeliveryDetails.php - sql corrections |  | Tim | 2010-07-05 |  |  |
| DebtorsAtPeriodEnd.php - Report design changes, screen layout changes, and sql corrections |  | Tim | 2010-07-05 |  |  |
| CustWhereAlloc.php - Layout changes and correction to sql statement |  | Tim | 2010-07-05 |  |  |
| CustomerTypes.php - Misc minor bug fixes, layout changes and correction to sql statement |  | Tim | 2010-07-05 |  |  |
| Customers.php - Layout changes and correction to sql statement |  | Tim | 2010-07-05 |  |  |
| Customers.php - Layout changes |  | Tim | 2010-07-05 |  |  |
| CustomerReceipt.php - Corrections to sql statements |  | Tim | 2010-07-05 |  |  |
| CustomerInquiries.php - Layout changes |  | Tim | 2010-07-05 |  |  |
| CustomerAllocations.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-05 |  |  |
| CustomerAllocations.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-05 |  |  |
| CustLoginSetup.php - Minor bug fixes, corrections to sql statements |  | Tim | 2010-07-05 |  |  |
| upgrade3.11.1-3.12.sql - Error in table name |  | Pak Ricard | 2010-07-05 |  |  |
| PricesByCost.php - Margin must be decimal to allow margins like 2,5 or 3,3 time standard cost. Also ot has to be large enough to accept "currency margins". If you have a standard costs in EUR and want to check prices in IDR or viceversa, you'll need to compute large margins. |  | Pak Ricard | 2010-07-05 |  |  |
| CustEDISetup.php - Corrections to sql statements |  | Tim | 2010-07-04 |  |  |
| Currencies.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-04 |  |  |
| CreditStatus.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-04 |  |  |
| Credit_Invoice.php - Minor bug fixes layout changes and corrections to sql statements |  | Tim | 2010-07-04 |  |  |
| ConfirmDispatchControlled_Invoice.php - Force the LineNo variable to be read as integer |  | Tim | 2010-07-04 |  |  |
| ConfirmDispatch_Invoice.php - Various fixes, corrections to sql statements and layout changes |  | Tim | 2010-07-04 |  |  |
| CompanyPreferences.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-04 |  |  |
| COGSGLPostings.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-04 |  |  |
| BOMs.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-04 |  |  |
| BOMListing.php - Minor layout changes |  | Tim | 2010-07-03 |  |  |
| BOMInquiry.php - Minor bug fixes and layout changes |  | Tim | 2010-07-03 |  |  |
| BOMIndentedReverse.php - Layout changes |  | Tim | 2010-07-03 |  |  |
| BOMIndented.php - Layout changes |  | Tim | 2010-07-03 |  |  |
| BOMExtendedQty.php - Layout changes |  | Tim | 2010-07-03 |  |  |
| BankReconciliation.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-03 |  |  |
| BankMatching.php - Corrections to sql statements and layout changes |  | Tim | 2010-07-03 |  |  |
| BankAccounts.php - Layout changes |  | Tim | 2010-07-03 |  |  |
| AddCustomerTypeNotes.php - Layout changes and verify Type ID before query |  | Tim | 2010-07-03 |  |  |
| AddCustomerNotes.php - Verify Contact ID before query |  | Tim | 2010-07-03 |  |  |
| AddCustomerContacts.php - Verify Contact ID before query |  | Tim | 2010-07-03 |  |  |
| AccountSections.php - Incorrect boolean statement causing the existing groups to still be shown |  | Tim | 2010-07-03 |  |  |
| AccountGroups.php - Incorrect boolean statement causing the existing groups to still be shown |  | Tim | 2010-07-03 |  |  |
| PO_OrderDetails.php - Force $_GET['OrderNo'] to be an integer |  | Tim | 2010-07-03 |  |  |
| Bulk transfers need to be able to have decimal places in the quantity. |  | Tim | 2010-07-02 |  |  |
| PrintCustTransPortrait.php - Error in sql, nor picking up stkmoveno. |  | Tim | 2010-07-01 |  |  |
| Numerous problems with stock adjustments for batch controlled items. |  | Tim | 2010-07-01 |  |  |
| DateFunctions.inc - Correction to GetPeriod() for case when future period didn't exist. |  | Tim | 2010-07-01 |  |  |
| Resolution of GL posting problem - removed INSERTs of new chartdetails from DateFunctions.inc CreatePeriod function and GLAccounts.php when adding a new GL account. Now all chartdetails are created from includes/GLPostings.inc and correct bfwd balances updated as they should be - I hope this solves this long outstanding previously unsquashable bug!! |  | Phil | 2010-06-30 |  |  |
| PDFPriceList.php - Layout improvements. |  | Tim | 2010-06-25 |  |  |
| SelectCompletedOrders.php - Layout improvements. |  | Tim | 2010-06-25 |  |  |
| FormDesigner.php - Add A5 to the paper sizes. |  | Tim | 2010-06-25 |  |  |
| Add the ability to have supplier types. |  | Tim | 2010-06-25 |  |  |
| PDFStarter.php - Add in A5 parameters. |  | Tim | 2010-06-25 |  |  |
| SupplierTender.php - Highlight any expired offers. |  | Tim | 2010-06-25 |  |  |
| SupplierTender.php - Add in expiry date for offers. |  | Tim | 2010-06-25 |  |  |
| SupplierTender.php - Add facility of the supplier to view, amend, or remove offers. |  | Tim | 2010-06-25 |  |  |
| OffersReceived.php - Deal correctly with case where the cancreate flag is turned off for user/currency combination |  | Tim | 2010-06-24 |  |  |
| SelectWorkOrder.php - Layout changes |  | Tim | 2010-06-24 |  |  |
| WorkOrderEntry.php - sql string must be unset before it can become an array |  | Tim | 2010-06-24 |  |  |
| upgrade3.11.1-3.12.sql - Add fields in salesorderfdetails table to enable new salesman commission program |  | Tim | 2010-06-24 |  |  |
| WOCosting.php fix to variances calculation were based on total quantity of the work order items required not the actual quantity of the work order items received as finished!!! Was correct when first released but has been wrong for several years |  | Phil | 2010-06-24 |  |  |
| Update SMTP server details from the UI rather than hard code into the scripts. |  | Tim | 2010-06-24 |  |  |
| View, accept, or reject any offers made. |  | Tim | 2010-06-23 |  |  |
| Z_ChangeStockCode.php - updated Z_ChangeStockCode.php as the last one broke due to the recent changes in contractBOM table (just changed the field name). |  | Pak Ricard | 2010-06-21 |  |  |
| AddCustomerNotes.php - Layout improvements |  | Tim | 2010-06-18 |  |  |
| AddCustomerContacts.php - Layout improvements |  | Tim | 2010-06-18 |  |  |
| AccountSections.php - Layout improvements |  | Tim | 2010-06-18 |  |  |
| AccountGroups.php - Layout improvements |  | Tim | 2010-06-18 |  |  |
| Areas.php - Layout improvements, and link to SearchCustomer.php |  | Tim | 2010-06-18 |  |  |
| SelectCustomers.php - Add facility to search by sales area |  | Tim | 2010-06-18 |  |  |
| WWW_Users.php - Add a field in for the supplier code |  | Tim | 2010-06-18 |  |  |
| CounterSales.php - Add the first line of address to the sql query |  | Tim | 2010-06-18 |  |  |
| default.css - Use percentages for font sizes |  | Tim | 2010-06-18 |  |  |
| WWW_Users.php - Extend for Contracts module |  | Tim | 2010-06-18 |  |  |
| Updates to Chinese translation |  | Zhiguo & Alec_H | 2010-06-17 |  |  |
| Enable a supplier to login and make an offer to the company |  | Tim | 2010-06-17 |  |  |
| SelectProduct.php - Layout improvements |  | Tim | 2010-06-16 |  |  |
| Customers.php - Correctly show fax and phone numbers |  | Tim | 2010-06-16 |  |  |
| CustloginSetup.php - Improvements to layout, and correctly get customer name |  | Tim | 2010-06-16 |  |  |
| GoodsReceived.php - Correct sql syntax to work for mysql versions before 5.1 |  | Simon Peter Otandeka | 2010-06-13 |  |  |
| Supplier login scripts needed for tendering system |  | Tim | 2010-06-13 |  |  |
| upgrade3.11.1-3.12.sql - Extend modules al.lowed field to allow for new modules |  | Tim | 2010-06-13 |  |  |
| Contracts.php new work changes to index.php to allow for new contract costing module |  | Phil | 2010-06-13 |  |  |
| GoodsReceived.php - session.inc should come before any calls to gettext _() function. |  | Tim | 2010-06-10 |  |  |
| Contracts.php - Fix typo in error message. Phil - This script is not even close to working yet anyway!! |  | Harald | 2010-06-09 |  |  |
| UserSettings.php - Fix sql error for case when password is being updated. |  | Tim | 2010-06-09 |  |  |
| Add option to exclude value information from GRN screen |  | Tim | 2010-06-08 |  |  |
| Various changes to correctly deal with suppliers uom plus layout changes to puirchase orders and goods received notes |  | Tim | 2010-06-07 |  |  |
| WorkOrderReceive.php - Change to make rollup costs change when more labour or overheadcosts change |  | Zhiguo Yuan | 2010-06-06 |  |  |
| Added a bit of error trapping to ensure customer/branch set up when going into CounterSales.php |  | Phil | 2010-06-03 |  |  |
| Locations.php used explode function rather than mb_substr function to split the cashsalecustomer to get Branch and Debtorno codes - also changed format to just a hypen between debtorno and branchcode |  | Otandeka | 2010-06-03 |  |  |
| CounterSales php changed to use explode to get branch and debtorno from cashsalecustomer - and changed format to debtorno-branchcode and rather than debtorno - branchcode (spaces removed) |  | Otandeka | 2010-06-03 |  |  |
| BankMatching script took out double quotes reformated indenting - a hard one to read - my bad! |  | Phil | 2010-05-31 |  |  |
| New script CounterSales.php to allow entry of sale and payment over the counter as a half way step to POS - the customer account is defaulted based on the users default stock location. The locations table now has a default cash sales account - see below |  | Phil | 2010-05-31 |  |  |
| SalesTypes.php - upgrade3.11.1-3.12.sql - to allow sales type description up to 40 char length |  | ChenJohn | 2010-05-29 |  |  |
| New field in locations table to allow a default cash sales account to be setup by location -modifications to Locations.php to allow it to be entered and error-trapping for debtor - branch format required for specification. This will be used in a new CounterSales.php script I am working on |  | Phil | 2010-05-28 |  |  |
| PurchData.php - Show the uom name, not the number |  | Tim | 2010-05-27 |  |  |
| CustomerAllocations.php - Show the right balance, and the allocate link where needed |  | Tim | 2010-05-27 |  |  |
| GoodsReceived.php - Correctly show the suppliers Units of measure |  | Tim | 2010-05-27 |  |  |
| PO_ReadInOrder.inc - Only show one line when item has more than one price set up |  | Tim | 2010-05-27 |  |  |
| GLProfit_Loss.php - Correction to show profit and loss reports for other languages |  | Tim | 2010-05-27 |  |  |
| Corrections to Suppliers.php and new fields for suppliers table |  | Tim | 2010-05-26 |  |  |
| Corrections to SelectCustomer.php and Customers.php |  | Tim | 2010-05-26 |  |  |
| Added telephone, email and fax fields to Suppliers and Customers Pages. Ability to add/edit the records. |  | Simon Peter Otandeka | 2010-05-24 |  |  |
| javascripts/MiscFunctions.js - not my field ... but could not resist adding some indentation - I struggle at the best of times with javascript but this was a shocker to read!! Hope I didn't mess up anything |  | Phil | 2010-05-16 |  |  |
| reworked PDFRemittanceAdvices.php - somehow missed from tcpdf work to print utf-8 pdfs |  | Phil | 2010-05-16 |  |  |
| reworked PDFPriceList to use the new effective dates fields and print out effective prices as at a specified date - showing effective dates on the report - also ditched includes/PDFPriceListPageHeader.php in favour of a PageHeader() function inside PDFPriceList.php. Also made the script work with tcpdf - not sure how it was missed before? |  | Phil | 2010-05-16 |  |  |
| reworked PricesBasedOnMarkUp.php to insert new prices with effectivity dates and update the prices where effectivity dates specified. |  | Phil | 2010-05-16 |  |  |
| reworked PricesByCost.php this was bit of a dodgy script - well I found it hard to follow - in the words of Frank Sinatra - I did it my way! Also built in effectivity dates to display and ensure correct prices updated now the primary key of prices is changed. |  | Phil | 2010-05-16 |  |  |
| Wrote up the manual so that the logic of pricing with effective dates is explained |  | Phil | 2010-05-16 |  |  |
| Used Lindsay/Ngaraj's nice email address checking function to replace the existing function in MiscFunctions.php and includes MiscFunctions.php in install/save.php to avoid duplication of the function |  | Phil | 2010-05-15 |  |  |
| $debug variable in UserLogin.php was only set on first login - not subsequent page calls (its not a session variable) - moved it back into session.inc so that full info about bugs is available to sysadmins |  | Phil | 2010-05-15 |  |  |
| GetPrices.inc Prices.php and Prices_Customer.php - modified to allow default prices - with no end dates - reducing requirement to administer - also updated Prices section of the manual |  | Phil | 2010-05-15 |  |  |
| Exit MRP scripts gracefully if no MRP calculation has been done. |  | Tim | 2010-05-11 |  |  |
| API was broken after adding a test for global variable $DatabaseName in ConnectDB_mysql* for this variable being set and using it as DB name if so. The variable of that name in the api_php.php has been changed to $api_DatabaseName. |  | Lindsay | 2010-05-08 |  |  |
| GetPrices.inc now uses the new price startdate and enddate to return the price which falls within the date range base on the current date. Changes to Prices.php and Prices_Customer.php to allow entry of effective from and effective to dates and updating/deleting of prices with appropriate error trapping and rescheduling of enddates where start and end dates would otherwise overlap. |  | Phil | 2010-05-08 |  |  |
| Found a bug in Date1GreaterThanDate2 function (in includes/DateFunction.inc) with SESSION['DefaultDateFormat'] = 'd/m/Y' this function had been broken |  | Phil | 2010-05-08 |  |  |
| Correct SQL error on Currency updater |  | Keith | 2010-05-06 |  |  |
| dates mangled via QuicK Entry format |  | Lindsay | 2010-05-06 |  |  |
| Added default startdate to prices table. |  | Matt Taylor | 2010-05-06 |  |  |
| Added startdate and enddate columns to prices table. |  | Matt Taylor | 2010-05-06 |  |  |
| Added startdate and enddate columns to prices table. |  | Matt Taylor | 2010-05-06 |  |  |
| Added feature to convert planned work orders. |  | Matt Taylor | 2010-05-06 |  |  |
| Added feature to convert planned work orders. |  | Matt Taylor | 2010-05-06 |  |  |
| Fix javascript errors in date picker |  | Tim | 2010-05-05 |  |  |
| Update to allow RecurringSalesOrdersProcess.php to run via cron |  | SiteMe | 2010-05-05 |  |  |
| Correct statements layout problems caused by the newer version of tcpdf |  | SiteMe | 2010-05-05 |  |  |
| Correct Invoice layout problems caused by the newer version of tcpdf |  | SiteMe | 2010-05-05 |  |  |
| GLProfit_Loss.php - Showing net profit percent (similar coding to gross profit percent |  | Ricard | 2010-05-05 |  |  |
| Fix javascript errors in order entry process |  | Tim | 2010-05-05 |  |  |
| PO_ReadInOrder.inc - Correct sql so that only one line per order line appears in the goods received screen |  | Zhiguo | 2010-05-05 |  |  |
| DateFunctions.inc - Fixed DateAdd function to correctly calculate Y/m/d dates. |  | SiteMe | 2010-05-04 |  |  |
| SelectProduct.php - Correctly display the product when selected from more than one page. |  | Tim | 2010-05-04 |  |  |
| More installer tweaks: better error handling |  | Lindsay | 2010-05-03 |  |  |
| PurchData.php - Do not show thousands seperator in price field |  | Tim | 2010-05-03 |  |  |
| Add picking list printing |  | Tim | 2010-05-02 |  |  |
| Updates for case where no internet connection is present |  | Tim | 2010-05-02 |  |  |
| SelectProduct.php - Correctly show the next page of products in a search. |  | Tim | 2010-05-02 |  |  |
| Install now includes an option to install a logo.jpg file |  | Lindsay | 2010-05-01 |  |  |
| Make installation operation functional again |  | Lindsay | 2010-04-30 |  |  |
| SelectOrderItems.php : Ensure PO line number is carried through from quck entry screen. |  | Tim | 2010-04-29 |  |  |
| SelectOrderItems.php : LastCustomer was not being initialised. |  | Tim | 2010-04-29 |  |  |
| MiscFunctions.php : Fix currency download for when there is no internet connection. |  | Tim | 2010-04-28 |  |  |
| Z_ImportStocks.php: Prevent importing empty string for pdfappend. |  | Matt Taylor | 2010-04-28 |  |  |
| MiscFunctions.js : Fix IsDate() function for Y/m/d format. |  | Matt Taylor | 2010-04-23 |  |  |
| DateFunctions.inc : Fix Date1GreaterThanDate2() function for Y/m/d format. |  | Matt Taylor | 2010-04-23 |  |  |
| Z_BottomUpCost.php : Add script for batch updating BOM costs. |  | Matt Taylor | 2010-04-23 |  |  |
| SelectProduct.php - Add a link to StockStatus.php direct from the selection page. |  | Zhiguo | 2010-04-22 |  |  |
| add missing ; to second last line of upgrade3.11.1-3.12.sql |  | Lindsay | 2010-04-22 |  |  |
| index.php - Change SID to sid, so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| PrintCustStatements.php - Change addInfo() to addinfo() and also change INTERVAL() to interval(), so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| PDFOrdersInvoiced.php - Change addInfo() to addinfo() and Is_Date() to is_date() also change SID to sid, so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| PDFDIFOT.php - Change addInfo() to addinfo() and also change SID to sid, so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| PDFDeliveryDifferences.php - Change addInfo() to addinfo() and also change SID to sid, so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| ConnectDB_mysql.inc - Change INTERVAL() to interval(), so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| ConnectDB_mysqli.inc - Change INTERVAL() to interval(), so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| DateFunctions.inc - Change Is_Date() to is_date(), so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| class.pdf.php - Change addInfo() to addinfo(), so as to solve the locale issue of Turkish I(Ä±) & Ä°(i) |  | Zhiguo | 2010-04-22 |  |  |
| Z_ImportStocks.php : Fix bug in checking of perishable field. |  | Matt Taylor | 2010-04-21 |  |  |
| InventoryPlanning.php - Remove discontinued items from inventory planning reports. |  | Ricard | 2010-04-18 |  |  |
| MiscFunctions.js - Update IsDAte() function for all date formats. |  | Kalmer Piiskop | 2010-04-15 |  |  |
| WWW_Users.php - Correctly show the last visited date. |  | Ricard | 2010-04-15 |  |  |
| GLJournal.php - Fix to show the footer on completion of the journal. |  | Tim | 2010-04-15 |  |  |
| SystemParameters.php - Add in picking note parameter |  | Tim | 2010-04-15 |  |  |
| SelectOrdderitems.php and DeliveryDetails.php - Improvements to layout and design of sales order entry |  | Tim | 2010-04-14 |  |  |
| SuppTransGLAnalysis.php - Correct typo in sql statement. |  | Tim | 2010-04-08 |  |  |
| SelectOrdderitems.php and DeliveryDetails.php - Improvements to layout. |  | Tim | 2010-04-07 |  |  |
| Improvements to the silverwolf theme. |  | Tim | 2010-04-07 |  |  |
| SelectOrderItems.php - Improve layout and correct for paging of items. |  | Tim | 2010-04-07 |  |  |
| PO_Items.php - Remove the Back To Purchase Order Header link if the order is already posted. |  | Tim | 2010-04-07 |  |  |
| SystemParameters.php and Currencies.php - Correct so that currency updates are only done when Automatic is chosen. |  | Tim | 2010-04-07 |  |  |
| Chinese translation update. |  | Exson | 2010-04-05 |  |  |
| Z_ImportStocks.php : Correct DB_txn functions and add validation check. |  | Matt Taylor | 2010-04-05 |  |  |
| Japanese translation updates |  | Zhiguo | 2010-04-05 |  |  |
| upgrade3.11.1-3.12.sql - Enlarge fieldname column in reportfields to 60 characters |  | Tim | 2010-04-04 |  |  |
| PDFSuppTransListing.php - Typing error in script title |  | Harald | 2010-04-01 |  |  |
| PrintCustStatements.php - Use new layout when all statements printed |  | Tim | 2010-03-30 |  |  |
| SelectCustomer.php - Remove extra '<' character |  | Tim | 2010-03-22 |  |  |
| SelectCustomer.php - Remove extra '>' character |  | Tim | 2010-03-22 |  |  |
| Change customer statement layout to not have overlapping text |  | Tim | 2010-03-22 |  |  |
| PDFStatementPageHeader.inc - Correction for right margin variable misnamed |  | Simon Peter Otandeka | 2010-03-21 |  |  |
| MRPShortages.php - Correctuion to mrp shortages report where total supply = 0 |  | Pak Ricard | 2010-03-21 |  |  |
| Add report of customer transactions entered on a given date |  | Tim | 2010-03-18 |  |  |
| Add report of supplier transactions entered on a given date |  | Tim | 2010-03-18 |  |  |
| Various layout improvements to top items report |  | Tim | 2010-03-17 |  |  |
| PO_SelecOSPurchOrder.php - Various layout changes for consistency and to stick to coding guidelines |  | Tim | 2010-03-16 |  |  |
| DateFunctions.inc - When getting the period number compare the same date formats |  | Anand | 2010-03-16 |  |  |
| PO_SelecOSPurchOrder.php - Correctly position link to create a new order |  | Tim | 2010-03-16 |  |  |
| PO_SelecOSPurchOrder.php - Change option from Printed to Print in case where order is not yet printed |  | Tim | 2010-03-16 |  |  |
| Layout changes to make SelectCustomer.php SelectProduct.php and SelectSupplier.php have a similar look and feel to them |  | Tim | 2010-03-15 |  |  |
| Various layout improvements |  | Tim | 2010-03-14 |  |  |
| report_runner.php - Remove deprecated split() function |  | Tim | 2010-03-13 |  |  |
| geocode.php - Remove deprecated split() function |  | Tim | 2010-03-13 |  |  |
| CustomerBranches.php - Remove deprecated split() function |  | Tim | 2010-03-13 |  |  |
| Suppliers.php - Remove deprecated split() function |  | Tim | 2010-03-13 |  |  |
| SuppPriceList.php - Remove deprecated split() function |  | Tim | 2010-03-13 |  |  |
| SupplierContacts.php - Correctly do the while loop to avoid a blank line, and only try to do the database updates when there are no errors |  | Tim | 2010-03-13 |  |  |
| GLAccountReport.php - creates a pdf for GL account listings for any selected range of accounts and periods |  | Phil | 2010-03-13 |  |  |
| GLAccountCSV.php - creates a csv file for GL account report for any selected range of accounts and periods |  | Phil | 2010-03-13 |  |  |
| Updated Swedish translation and some changes to Language oriented files |  | Peter Pettersson | 2010-03-10 |  |  |
| PDFStockLocTransfer.php - Add a reprint option |  | Tim | 2010-03-09 |  |  |
| Updates to Petty Cash Management Report and include new header file |  | Tim | 2010-03-09 |  |  |
| Small changes to Petty Cash module |  | Tim | 2010-03-08 |  |  |
| FixedAssetRegister.php Include totals and sub-totals |  | Simon Peter Otandeka | 2010-03-08 |  |  |
| PrintCustTransPortrait.php Correctly show the prices |  | Tim | 2010-03-08 |  |  |
| Corrections to DIFOT report to correctly show the logo |  | Tim | 2010-03-08 |  |  |
| PrintCustTrans.php generates HTML for Print, PDF for Print PDF |  | Lindsay | 2010-03-05 |  |  |
| Z_DataExport.php - Remove unused fields |  | Tim | 2010-03-04 |  |  |
| PDFQuotation.php - Remove call to SelectFont function |  | Tim | 2010-03-04 |  |  |
| WorkCentres.php - Default value for overhead per hour |  | Tim | 2010-03-01 |  |  |
| PcReportTab.php - New PcReportTab with limited tab view (supervised or own) tabs. |  | Pak Ricard | 2010-03-01 |  |  |
| PrintCustTrans.php - Correctly show html and pdf formats |  | Tim | 2010-03-01 |  |  |
| PDFPriceList.php - Updated price list report |  | Pak Ricard | 2010-03-01 |  |  |
| PcExpensesTypeTabv.php - Correct message on deletion of tab |  | Harald | 2010-03-01 |  |  |
| PcExpensesTypeTabv.php - Missing space |  | Harald | 2010-03-01 |  |  |
| PcExpensesTypeTabv.php - Missing space, and bad end tag |  | Harald | 2010-03-01 |  |  |
| PcExpensesTypeTabv.php - Missing Gettext function |  | Harald | 2010-03-01 |  |  |
| PcAuthorizeExpenses.php - Remove blank line at the start of file, and correct for instance where $SelectedTabs is not set |  | Tim | 2010-03-01 |  |  |
| Bug fix and better description for API functions for SalesInvoiceEntry/Modify |  | Lindsay | 2010-02-20 |  |  |
| Bug fixing to make SalesOrder entry work for old and new API. |  | Lindsay | 2010-02-19 |  |  |
| date formatting in api_salesorders.php; added FormatDateWithTimeForSQL function to includes/DateFunctions.inc |  | Lindsay | 2010-02-18 |  |  |
| New Petty cash module |  | Pak Ricard | 2010-02-16 |  |  |
| Added GetCustomerBranchCodes() to API, and aoi_DB_query function to record DB error message and SQL causing it. |  | Lindsay | 2010-02-16 |  |  |
| Return no error indication from SearchCustomer() API |  | Lindsay | 2010-02-15 |  |  |
| Completion of Indonesian translation |  | Thomas Timothy Lie | 2010-02-12 |  |  |
| PO_Items.php - Incorrect number of decimal places shown for pricdes less than 1 |  | Paul | 2010-02-12 |  |  |
| Update phplot to the laterst version and remove reference to deprecated function eregi() |  | Tim | 2010-02-12 |  |  |
| Update php-gettext to the laterst version and remove reference to deprecated function eregi() |  | Tim | 2010-02-12 |  |  |
| Save.php - Replace deprecated function eregi |  | Tim | 2010-02-12 |  |  |
| Change the default font family to Arial |  | Tim | 2010-02-08 |  |  |
| PO_AuthorisationLevels.php - If authoriastion limit is blank, then use a zero value |  | Tim | 2010-02-05 |  |  |
| Payments.php - Correctly show escaped characters in narrative while item hasn't been posted |  | Tim | 2010-02-03 |  |  |
| BankReconciliation.php - Correction for multi currency bank accounts |  | Pak Ricard | 2010-02-02 |  |  |
| BOMInquiry.php - Show the labour and verhead costs for the parent item |  | Chris Franks | 2010-02-02 |  |  |
| api_xml-rpc.php.php - Corrections to descriptions. |  | Harald | 2010-02-02 |  |  |
| PricesByCost.php - New script to view or update prices by cost |  | Pak Ricard | 2010-01-29 |  |  |
| SupplierCredit.php - Bring credit note scrpt in line with the invoice script |  | Zhiguo | 2010-01-29 |  |  |
| Partial Romanian translation |  | Victor Onofrei | 2010-01-29 |  |  |
| Updated Indonesian translation files |  | Sajatmiko Akbar Wibowo | 2010-01-27 |  |  |
| api_xml-rpc.php - Update the documentation |  | Tim | 2010-01-26 |  |  |
| Expand description for API Login and Logout functions |  | Lindsay | 2010-01-26 |  |  |
| Simplify and shorten the code for wildcard selection criteria |  | Paul Thursby | 2010-01-25 |  |  |
| api_xml-rpc.php - API now in 2 styles: name/password not required after login |  | Lindsay | 2010-01-25 |  |  |
| api_xml-rpc.php - Typo correction in string |  | Harald | 2010-01-24 |  |  |
| api_xml-rpc.php - Typo correction in string |  | Harald | 2010-01-24 |  |  |
| Croatian translation files |  | Miroslav Mazurek | 2010-01-24 |  |  |
| api_xml-rpc.php - Typo correction in string |  | Harald | 2010-01-24 |  |  |
| DateFunctions.inc - Correction to DateAdd() function for the d.m.Y date format |  | Tim | 2010-01-23 |  |  |
| Correct path in ReportCreator.php |  | Harald | 2010-01-21 |  |  |
| Remove redundant javascript calls |  | Tim | 2010-01-20 |  |  |
| MRP.php - Missing gettext function for string |  | Tim | 2010-01-19 |  |  |
| MRPPlannedWorkOrders.php - Add default date and use the javascript date picker |  | Tim | 2010-01-18 |  |  |
| MRPPlannedPurchaseOrders.php - Add default date and use the javascript date picker |  | Tim | 2010-01-18 |  |  |
| index.php - Add entry for planned work orders |  | Tim | 2010-01-17 |  |  |
| MRPCalendar.php - gettext function for string was missing |  | Harald | 2010-01-17 |  |  |
| Correctly deal with different paper sizes in form designer |  | Tim | 2010-01-16 |  |  |
| Add the Goods Received Note to the Form Designer |  | Tim | 2010-01-16 |  |  |
| UserLogin.php: The userid session variable was not being correctly set. |  | Tim | 2010-01-15 |  |  |
| api_xml-rpc.php: Use output buffering to hide html error messages so that the api can handle errors in a graceful way |  | Tim | 2010-01-13 |  |  |
| PO_Header.php: Show the mailto links correctly by decoding the hml entities |  | Tim | 2010-01-13 |  |  |
| api_login.php, api_xml-rpc.php: Added logout method to API. |  | Lindsay | 2010-01-13 |  |  |
| API changes: added method to turn error number to string; clean up api_xml-rpc.php and corrected some method descriptions. |  | Lindsay | 2010-01-13 |  |  |
| Z_ChangeStockCategory.php - New script to change a stock category code |  | Pak Ricard | 2010-01-12 |  |  |
| PDFGrn.php - Show the date in the correct format |  | Chris Franks | 2010-01-12 |  |  |
| Z_ChangeStockCode.php - Check thast mrpplannedorders table exists before trying to alter it |  | Tim | 2010-01-12 |  |  |
| StockTransfers.php - Correctly show stockid in links to ther functions. |  | Tim | 2010-01-10 |  |  |
| BOMs.php - syntax error includes() was used not include() |  | Bryan Nielsen | 2010-01-10 |  |  |
| Fix price for same item on an order priced at different prices - unfortunately, this means that there may be rounding errors on very large currencies as I reverted to the old logic using the stockmoves which use local currency and then converting back to the currency of the invoice - fixed both portrait and landscape |  | Phil | 2010-01-09 |  |  |
| Changes to api session handling. |  | Lindsay | 2010-01-09 |  |  |
| PrintCustTrans.php - Correct errors preventing invoice from printing |  | Tim | 2010-01-08 |  |  |
| Put a supplier invoice on hold when outside the bounds set up in the config |  | Tim | 2010-01-08 |  |  |
| Payments.php - Enforce numbers only in input boxes |  | Tim | 2010-01-07 |  |  |
| GoodsReceived.php - Declare $identifier if not already declared before including PO_ReadInOrder.inc |  | Tim | 2010-01-07 |  |  |
| silverwolf/default.css - Changes for new menu layout |  | Tim | 2010-01-07 |  |  |
| PrintCustTransPortrait.php - Correct errors preventing invoice from printing |  | Tim | 2010-01-07 |  |  |
| Change to only allow authorised staff to release invoices from hold |  | Tim | 2010-01-07 |  |  |
| index.php - Ensure the modules positioning remains consistent. |  | Tim | 2010-01-06 |  |  |
| api_stock.php - Error in sql statement. |  | Tim | 2010-01-06 |  |  |
| index.php - Move the modules selection to the left of the screen |  | Tim | 2010-01-06 |  |  |
| Form layout designer - Enable each company to have their own layouts for orders/invoices etc |  | Tim | 2010-01-06 |  |  |
| api_stock.php - Correct typo in sql statement. |  | Tim | 2010-01-04 |  |  |
| Fix SalesAnalysis_UserDefined.php - one too many brackets and indentation |  | Phil | 2010-01-03 |  |  |
| Allow pdf output of the asset register. |  | Tim | 2010-01-02 |  |  |
| Move the purchase ordering printing parameters to a separate file for the form designer. |  | Tim | 2009-12-24 |  |  |
| Rename rtl theme to remove the space. |  | Tim | 2009-12-23 |  |  |
| TopItems.php - Improvements to the top sales items report. |  | Pak Ricard | 2009-12-19 |  |  |
| UserSettings.php - added new pdflanguage field to allow user pdf language support to be specified |  | Tim | 2009-12-18 |  |  |
| class.pdf.php - Corrections to addTextWrap() function |  | Tim | 2009-12-17 |  |  |
| PrintSalesOrder_generic.php - Correctly set $Copy for when not customer copy. |  | Tim | 2009-12-17 |  |  |
| upgrade3.11-3.12.sql - Include pdflanguage field in www_users |  | Tim | 2009-12-14 |  |  |
| FixedAssetRegister.php and FixedAssetJournal.php - Bux fixes to asset manager. |  | Simon Peter Otandeka | 2009-12-12 |  |  |
| PO_PDFPurchOrder.php - Print the correct suppliers unit of measure. |  | Tim | 2009-12-11 |  |  |
| SelectProducts.php - Ensure that preferred supplier is shown first, and provide a direct link to purchase ordering. |  | Tim | 2009-12-11 |  |  |
| Selectproduct.php - Add ORDER BY to show the supplier info in effective date order. |  | Pak Ricard | 2009-12-11 |  |  |
| Correctly align numbers in tables. |  | Tim | 2009-12-09 |  |  |
| upgrade3.11-3.12.sql - Correct inconsistency with area code field length in cogsglpostings table |  | Pak Ricard | 2009-12-09 |  |  |
| SpecialOrder.php - Correction to produce sales order correctly |  | Tim | 2009-12-04 |  | [](Pak Rica+F2252rd) |
| WriteForm.inc - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-12-04 |  |  |
| WriteReport.inc - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-12-04 |  |  |
| ConfirmDispatch_Invoice.php - Items are posted directly to the GL so posted should be immediately set to 1. |  | Tim | 2009-12-04 |  |  |
| PO_Items.php - Show the suppliers uom correctly, and have button correctly shown on screen. |  | Tim | 2009-12-04 |  |  |
| StockTransferontrolled.php - Correct for batch controlled Items. |  | Tim | 2009-12-01 |  |  |
| New Remittance Advice script |  | Phil | 2009-11-29 |  |  |
| WWW_Users.php - Allow for case where Last visited date isn'nt set, and set it. |  | Tim | 2009-11-24 |  |  |
| SystemParameters.php - Remove .svn from the theme list, and minor layout changes for currencies where large numbers are common. |  | Tim | 2009-11-24 |  |  |
| PrintCustTrans.php and PrintCustTransPortrait.php - Correctly print the invoice lines, and add the invoice number to the file name |  | Tim | 2009-11-23 |  |  |
| MiscFunctions.php - Correction for case of LogPath not being set |  | Tim | 2009-11-22 |  |  |
| PurchData.php - Improve the working of adding and editing purchasing data |  | Tim | 2009-11-22 |  |  |
| PO_PDFOrderPageHeader.php - Correct variable name to show order lines correctly |  | Tim | 2009-11-22 |  |  |
| PO_AuthorisationLevels.php - Correct edit functionality - Fixes Ticket #15 |  | Tim | 2009-11-21 |  |  |
| Logout.php - Take out reference to $demo_text and the incorrect call to session_start(). |  | Tim | 2009-11-21 |  |  |
| CustomerInquiry.php - Correction of errors in retrieving the number of months inquiries to show. |  | Tim | 2009-11-20 |  |  |
| Create a log file of all status messages. Default is no log. |  | Tim | 2009-11-20 |  |  |
| Improvements to factor company implementaton. |  | Tim | 2009-11-19 |  |  |
| default.css - Correction to jelly theme |  | Tim | 2009-11-18 |  |  |
| PO_Header.php - Add direct link to raise a Purchase Order |  | Tim | 2009-11-18 |  |  |
| PO_PDFOrderPageHeader.inc - Include payment terms on the order. |  | Tim | 2009-11-18 |  |  |
| Customers.php - Provide for better error handling when price lists and customer types are not setup. |  | Tim | 2009-11-18 |  |  |
| Customers.php - Provide option to just view a customers details |  | Tim | 2009-11-18 |  |  |
| PDFReceipt.php - Provide option to print a customer receipt |  | Tim | 2009-11-18 |  |  |
| Factors.php - Correction first factor does not show in amend list |  | Bryan Nielsen | 2009-11-17 |  |  |
| WWW_Users.php - Alter directory listing for svn directory |  | Tim | 2009-11-17 |  |  |
| UserSettings.php - Alter directory listing for svn directory |  | Tim | 2009-11-17 |  |  |
| TopItems.php - Correct typo preventing the correct loading of type of customers. |  | Pak Ricard | 2009-11-17 |  |  |
| button_bg.png - quick menu silverwolf button background to follow style of theme |  | Javier | 2009-11-15 |  |  |
| default.css - removed duplicated and restored overwritten classes after the synchronization |  | Javier | 2009-11-15 |  |  |
| Change.log.html - This file, partially corrected xhtml code and convert tag being text to entities |  | Javier | 2009-11-15 |  |  |
| default.css - Added in menu_group_item &lt;p&gt; css style to restore and even enhance previous &lt;li&gt; appearance |  | Javier | 2009-11-15 |  |  |
| index.php - Complete correction of PhP and xhtml code to follow Zend and W3C standard rules |  | Javier | 2009-11-15 |  |  |
| DateFunctions.inc - Correction for North American style dates |  | Beth Lesko | 2009-11-15 |  |  |
| api_salesorders.php - Correct date checking functions |  | CSRBusiness | 2009-11-15 |  |  |
| StockLocStatus.php - Add in link to stock items |  | Bryan Nielsen | 2009-11-15 |  |  |
| default.css - Synchronise css files so that all themes include all classes |  | Tim | 2009-11-15 |  |  |
| GLTrialBalance_csv.php Creates a csv of the trial balance with parameters for PeriodFrom and PeriodTo - also with webERP username and password set as part of the URL. This script allows a csv to be retrieved into an open-office spreadsheet directly with the loadComponentFromURL statement. Also modified session.inc - hope I've not broken anything else!! |  | Phil | 2009-11-15 |  |  |
| StockLocStatus.php - Correct missing &lt;/a&gt; tag. |  | Tim | 2009-11-14 |  |  |
| Improve direct ordering code and make the EOQ the default quantity when ordered direct |  | Tim | 2009-11-12 |  |  |
| PO_PDFPurchOrder.php - Correct syntax error |  | Tim | 2009-11-12 |  |  |
| PO_Items.php - Correct error in sql reventing a new order being raised |  | Tim | 2009-11-12 |  |  |
| api_branches.php - Remove echo statement from modifybranch() function |  | Tim | 2009-11-12 |  |  |
| api_php.php - include sqlcommonfunctions. |  | Tim | 2009-11-12 |  |  |
| api_salesorders.php - Fix call to GtNextTransaction() |  | Tim | 2009-11-12 |  |  |
| api_salesorders.php - Correct the number of parameters in call to VerifyDeliveryDate() |  | CSRBusiness | 2009-11-12 |  |  |
| BankAccounts.php - Correctly assign values to Yes and No options. |  | Bryan Nielsen | 2009-11-12 |  |  |
| TaxProvinces.php - Correctly check if any locations use this province before deletion. |  | Tim | 2009-11-12 |  |  |
| ManualContents.php - Fix error in showing API manual |  | Tim | 2009-11-12 |  |  |
| DateFunctions.inc - Corection to DateAdd() function |  | John Straka | 2009-11-11 |  |  |
| Improvements to purchase ordering system |  | Tim | 2009-11-11 |  |  |
| PO_ReadInOrder - Update for additinal purchase order line properties |  | Tim | 2009-11-09 |  |  |
| PO_Header.php - Correctly assign warehouse variables from sql |  | Tim | 2009-11-09 |  |  |
| messages.pot - gettext en_GB template file for translations. |  | Javier | 2009-11-09 |  |  |
| class.pdf.php - (miss v) Removed calls to html_entity_decode and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| PO_Header.php - v1.35 Removed call to html_entity_decode. |  | Javier | 2009-11-09 |  |  |
| DeliveryDetails.php - v1.76 Removed call to html_entity_decode and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| PDFTransPageHeaderPortrait.inc - v1.9 Removed call to htmlspecialchars_decode. |  | Javier | 2009-11-09 |  |  |
| PDFTransPageHeader.inc - v1.19 Removed call to htmlspecialchars_decode. |  | Javier | 2009-11-09 |  |  |
| PDFTransPageHeaderPortrait.inc - v1.8 Added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| PDFTransPageHeader.inc - v1.18 Added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| Z_poEditLangHeader.php - v1.8 Removed call to htmlspecialchars and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| SalesCategories.php - v1.11 Removed calls to htmlentities and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| Z_poEditLangRemaining.php - v1.4 Removed call to htmlentities and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| Z_poEditLangModule.php - v1.11 Removed call to htmlentities and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| Stocks.php - v1.75 Removed call to htmlentities and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| FixedAssetItems.php - v1.3 Removed call to htmlentities and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| AuditTrail.php - (miss v) Removed call to htmlentities and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| Suppliers.php - v1.44 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| SupplierInvoice.php - v1.45 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| SupplierCredit.php - v1.23 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| StockQuantityByDate - v1.10 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| SpecialOrder.php - v1.20 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| SalesInquiry.php - v1.5 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| POReport.php - v1.4 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| PO_Items.php - v1.43 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| MRPCalendar.php - v1.6 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| Customers.php - v1.44 Is_Date to match case for Turkish and added SVN Keyword. |  | Javier | 2009-11-09 |  |  |
| PO_Header.php - v1.34 Replaced colon and space with period at Line 543. |  | Javier | 2009-11-09 |  |  |
| ConnectDB_mysql.inc v1.32 ConnectDB_mysqli.inc v1.21 and ConnectDB_postgres.inc v1.15 - Added Id and Removed Charset from gettext |  | Javier | 2009-11-09 |  |  |
| PO_Header.php - v1.33 Added gettext function at Line 544 and Id SVN Property. |  | Javier | 2009-11-09 |  |  |
| messages.pot - update of gettext en_GB template file for translations. |  | Javier | 2009-11-09 |  |  |
| CustomerReceipt.php - v1.46 Add in Id SVN Property. |  | Javier | 2009-11-09 |  |  |
| CustomerReceipt.php - v1.45 Removed br at Line 783 from html table first column title. |  | Javier | 2009-11-09 |  |  |
| upgrade3.11-3.12.sql - corrected syntax for consistency. |  | Javier | 2009-11-09 |  |  |
| SelectCustomer.php - Add in facility to search by customer address |  | Gilles Deacur | 2009-11-08 |  |  |
| New DailySalesInquiry.php - show sales for a selected month - day by day with average sales per billing day and GP% - work sponsored by Manny Neri |  | Phil | 2009-11-08 |  |  |
| update3.10-3.11.sql - Remove FOI config value as feature not in 3.11 |  | Tim | 2009-11-07 |  |  |
| PrintCstTransPortrait.php - Correct $pdf->Output() call to show invoice without saving first |  | Tim | 2009-11-07 |  |  |
| upgrade3.11-3.12.sql - Remove duplicated line and corrected syntax for consistency |  | Tim | 2009-11-07 |  |  |
| Login.php - A correction for the svn folder, and minor corrections to HTML. |  | Bogdan Stanciu | 2009-11-06 |  |  |
| Corrections to Silverwolf login.css |  | Javier | 2009-11-06 |  |  |
| Corrections to Silverwolf default.css |  | Javier | 2009-11-06 |  |  |
| Converted 10 Silverwolf mono images png color files into png mono files. |  | Javier | 2009-11-06 |  |  |
| ManualHeader.html - Corrected syntax of the Charset Declaration. |  | Javier | 2009-11-05 |  |  |
| Added missed icon images money_add.png and money_delete.png to all themes. |  | Javier | 2009-11-05 |  |  |
| PDFStarter.php - v1.5 Changed addinfo calls into addInfo to avoid the Turkish issue. |  | Javier | 2009-11-05 |  |  |
| SystemParameters.php - v1.59 Removed htmlentities at Line 362. |  | Javier | 2009-11-05 |  |  |
| PrintCustTransPortrait.php - Corrections to sql |  | Bogdan Stanciu | 2009-11-04 |  |  |
| EDIProcessOrders.php - Fix string concatenation errors |  | Bogdan Stanciu | 2009-11-03 |  |  |
| SuppPriceList.php - Allow price list to be printed from the SelectSupplier screen |  | Tim | 2009-11-02 |  |  |
| SelectSupplier.php - Add option to print a supplier price list |  | Tim | 2009-11-02 |  |  |
| index.php - Add in new facility to maintain Reorder levels |  | Tim | 2009-11-02 |  |  |
| ReorderLevelLocation.php - A new script ReorderLevelLocation, allowing to setup reorder levels selecting a location and a category. |  | Pak Ricard | 2009-11-02 |  |  |
| SuppPriceList.php - A script that produces a PDF with a supplier price list. User can select supplier, category (all is available), and current prices or all prices (to compare old prices with new ones). |  | Pak Ricard | 2009-11-02 |  |  |
| StockLocTransferReceive.php - Fix to allow the balance of a transfer to be cancelled. |  | Pak Ricard | 2009-11-01 |  |  |
| api_php.php - Corrections to database authentication |  | Lindsay | 2009-10-30 |  |  |
| GoodsReceived.php - Correction to SQL |  | Tim | 2009-10-30 |  |  |
| FixedAssetRegister.php - Changes in layout to the register |  | Tim | 2009-10-30 |  |  |
| FixedAssetJournal.php - Correctly calculate depreciation |  | Tim | 2009-10-30 |  |  |
| FixedAssetItems.php - Various bug fixes |  | Tim | 2009-10-30 |  |  |
| FixedAssetLocations.php - Allow for parent locations |  | Tim | 2009-10-30 |  |  |
| Z_ImportStocks.php - Change file type to text/csv |  | Tim | 2009-10-30 |  |  |
| MRPCreateDemands.php - Minor formatting changes |  | Tim | 2009-10-30 |  |  |
| SelectCompletedOrder.php - Use javascript date picker if javascript is enabled |  | Tim | 2009-10-30 |  |  |
| StockMovements.php - Layout improvements. |  | Tim | 2009-10-30 |  |  |
| WorkOrderReceive.php - Layout improvements. |  | Tim | 2009-10-30 |  |  |
| upgrade3.11-3.11.1 - Remove change to www_users as not a bug in 3.11 |  | Tim | 2009-10-30 |  |  |
| Update images to ensure that all themes have the same images |  | Tim | 2009-10-30 |  |  |
| StockDispatch - New version for StockDispatch with some printing improvements and also with an option to choose a â€œsimpleâ€� or â€œstandardâ€� form, depending on the company needs. |  | Tim | 2009-10-30 |  |  |
| StockDispatch - New version for StockDispatch with some printing improvements and also with an option to choose a â€œsimpleâ€� or â€œstandardâ€� form, depending on the company needs. |  | Pak Ricard | 2009-10-30 |  |  |
| PrintCustTransPortrait.php - Allows PrintCustTransPortrait to use prices from salesordersdetails, instead of stockmoves, to solve the multicurrency issue with large exchage rates. |  | Pak Ricard | 2009-10-30 |  |  |
| PrintCustTrans.php - Allows PrintCustTrans to use prices from salesordersdetails, instead of stockmoves, to solve the multicurrency issue with large exchage rates. |  | Pak Ricard | 2009-10-30 |  |  |
| upgrade3.11-3.12.sql - Remove duplicated indexes |  | Tim | 2009-10-30 |  |  |
| PDFBalanceSheetPageHeader.inc - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| PDFOrderStatusPageHeader.inc - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| index.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| PO_SelectPurchOrder.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| PO_SelectOSPurchOrder.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| index.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| SpecialOrder.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| PO_PDFPurchOrder.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| PO_Items.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| BOMExtendedQty.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-30 |  |  |
| SuppInvGRNs.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-29 |  |  |
| AccountGroups.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-29 |  |  |
| DeliveryDetails.php - Fix to make similar phrases read the same to ease translation. Notified by Javier |  | Tim | 2009-10-29 |  |  |
| MRPPlannedWorkOrders.php - Fix typos |  | Tim | 2009-10-29 |  |  |
| PDFStockCheckComparison.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| api_xmlrpc.php - Fix typos |  | Tim | 2009-10-29 |  |  |
| api_errorcodes.php - Fix typos |  | Tim | 2009-10-29 |  |  |
| PDFSalesAnalysis.inc - Fix typo |  | Tim | 2009-10-29 |  |  |
| ConstructSQLForUserDefinedSalesReport.inc - Fix typo |  | Tim | 2009-10-29 |  |  |
| Z_MakeNewCompany.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| ShipmentCosting.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| SalesInquiry.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| PO_PDFPurchOrder.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| PO_Items.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| MRPReport.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| MRP.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| MRPDemands.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| InventoryPlanningPrefSupplier.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| ConfirmDispatch_Invoice.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| BOMIndentedReverse.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| BOMIndented.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| BOMExtendedQty.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| Aged Debtors.php - Fix typo |  | Tim | 2009-10-29 |  |  |
| ConfirmDispatch_Invoice.php - Fix to ensure correct qoh is calculated even with simultaneous invoices |  | Tim | 2009-10-29 |  |  |
| PO_PDFPurchOrder.php - Improvements in Purchase order print to include supplier part code |  | Pak Ricard | 2009-10-29 |  |  |
| InventoryPlanning.php - Improvements to Inventory Planning reports |  | Pak Ricard | 2009-10-29 |  |  |
| PDFInventoryPlanPageHeader.inc - Improvements to Inventory Planning reports |  | Pak Ricard | 2009-10-29 |  |  |
| upgrade3.11-3.12.sql - Add in parameter for new config value, and additional asset manager fields |  | Pak Ricard | 2009-10-29 |  |  |
| System Parameters.php - Add config parameter for default number of months to show in Cstomer Inquiry |  | Pak Ricard | 2009-10-29 |  |  |
| CustomerInquiry.php - Add config parameter for default number of months to show |  | Pak Ricard | 2009-10-29 |  |  |
| PrintCustTransPortrait.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PDFStockLocTransfer.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| MRPPlannedPurchaseOrders.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| MRPShortages.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PDFSockTransfer.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| InventoryQuantities.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PrintSalesOrder_generic.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PDFCustomerList.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| GLProfit_Loss.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| MRPReschedules.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PrintCustOrder_generic.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| SalesAnalysis_UserDefined.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| BOMIndentedReverse.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PDFLowGP.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PDFGrn.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PrintCustOrder.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PrintCustTrans.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| InventoryPlanningPrefSupplier.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| BOMExtendedQty.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| GLTagProfit_Loss.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PrintCustStatements.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| PDFQuotation.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| InventoryPlanning.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| InventoryValuation.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-29 |  |  |
| OutstandingGRNs.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| PDFTopItems.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| StockCheck.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| GLBalanceSheet.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| PDFStockCheckComparison.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| StockDispatch.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| ReorderLevel.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| MRPPlannedWorkOrders.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| BOMIndented.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| GLTrialBalance.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| PrintCheque.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| PDFStockNegatives.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| MRPReports.php - Change to make the file name have a default extension of pdf as per suggestion from Javier |  | Tim | 2009-10-28 |  |  |
| FixedAssetCategories.php - Correction to strings. |  | Harald Ringehahn | 2009-10-28 |  |  |
| SelectOrderItems.php - Changes to Gettext functions removing concatenated strings |  | Uldis Nelsons | 2009-10-28 |  |  |
| SystemParameters.php - Correct Typo. |  | Gilles Deacur | 2009-10-28 |  |  |
| PDFTopItems.php Print out Top Sales Item to pdf. |  | Pak Ricard | 2009-10-28 |  |  |
| PDFTopItemsHeader.php Print out Top Sales Item to pdf. |  | Pak Ricard | 2009-10-28 |  |  |
| TopItems.php Improvements to conform with webERP coding standards |  | Pak Ricard | 2009-10-28 |  |  |
| CustomerAllocations.php - the date of the allocation was converted to SQL format using the FormatDateForSQL function but the date was hard coded as d/m/Y - so the function failed - hard coded for SQL inserts Y-m-d |  | Phil | 2009-10-24 |  |  |
| fix bug reported by Manny Neri - customer statements where the option to show settled transaction in the last month was activiated were actually showing all settled transactions - which was not ideal!! |  | Phil | 2009-10-23 |  |  |
| SuppInvGRNs.php - Properly align numbers in table |  | Tim | 2009-10-10 |  |  |
| SupplierInquiry.php - Properly align numbers in table |  | Tim | 2009-10-10 |  |  |
| PO_SelectPurchOrder.php - Properly align numbers in table |  | Tim | 2009-10-10 |  |  |
| PO_Header.php - Javascript function to repost form when the status is changed |  | Tim | 2009-10-10 |  |  |
| PDFBankingSummary.php - Select batch from drop down list |  | Tim | 2009-10-10 |  |  |
| MRPDemands.php - Enable option to use Date picker to choose dates. |  | Tim | 2009-10-10 |  |  |
| MRPCreateDemands.php - Enable option to use Date picker to choose dates. |  | Tim | 2009-10-10 |  |  |
| GLAccountInquiry.php - Allow option to show all transactions |  | Tim | 2009-10-10 |  |  |
| api_debtortransactions.php - Fix errors in checking for valid transaction date |  | Tim | 2009-10-10 |  |  |
| RCFunctions.inc - Fix problem with database criteria statement |  | Tim | 2009-10-08 |  |  |
| WorkOrderIssue.php - Allow for the selection of a date for the manual issue to work orders |  | Tim | 2009-10-08 |  |  |
| Top sales items report |  | Pak Ricard | 2009-10-08 |  |  |
| Fix PO_Items.php to enable changes to prices and weights |  | Phil | 2009-10-07 |  |  |
| upgrade3.11-3.11.1 - Enlarge language field in www_users for utf8 codeset |  | Tim | 2009-10-03 |  |  |
| upgrade3.11-3.12.sql - Enlarge language field in www_users for utf8 codeset |  | Tim | 2009-10-03 |  |  |
| weberp-new.sql - Enlarge language field in www_users for utf8 codeset |  | Tim | 2009-10-03 |  |  |
| weberp-demo.sql - Enlarge language field in www_users for utf8 codeset |  | Tim | 2009-10-03 |  |  |
| Fix to www_users.php branchcode and debtorno could only be 8 characters long should allow 10 |  | Pak Ricard | 2009-09-26 |  |  |
| Fix to Z_ChangeStockCode.php to change the stock code in more recent tables including MRP tables and locationtransfers tables |  | Pak Ricard | 2009-09-25 |  |  |
| Fix to Z_ImportStocks.php to error trap mbflag |  | asv | 2009-09-25 |  |  |
| PrintCustTransPotrait is fixed to print default bank account details on invoice, only if config parameter is set. Tested to work printing single or multilple invoices, and credit notes confirmed as printing OK (no bank account details required). No DB changes required. |  | Murray | 2009-09-21 |  |  |
| StockDispatch.php - Changed to create loctransfers records |  | Mark | 2009-09-20 |  |  |
| Fix SelectOrderItems.php - option to show frequently ordered items, fixed SQL to be generic using existing date functions in /includes/DateFunctions.inc and also the new parameter to allow up to 99 frequently ordered items to display, GP Percentage rounding problem resolved. Corrected indentation and brackets in line with style guide |  | Phil | 2009-09-20 |  |  |
| SystemParameters.php - option to show up to 99 frequently ordered items |  | Phil | 2009-09-20 |  |  |
| upgrade3.11-3.12.sql new config parameter for FrequentlyOrderedItems |  | Phil | 2009-09-20 |  |  |
| weberp-new.sql - Reinstate missing foreign keys |  | Tim | 2009-09-18 |  |  |
| weberp-demo.sql - Reinstate missing foreign keys |  | Tim | 2009-09-18 |  |  |
| Stocks.php - Updates for Asset manager module |  | Tim | 2009-09-16 |  |  |
| WWW_Users.php - Allow the showing of the asset manager module in user setup |  | Tim | 2009-09-16 |  |  |
| index.php - Show new functionality on menu pages |  | Tim | 2009-09-16 |  |  |
| PO_AuthoriseMyOrders.php - New script to show those orders a user can authorise |  | Tim | 2009-09-16 |  |  |
| upgrade3.11-3.12.sql - Changes to www_users to show asset manager module |  | Tim | 2009-09-16 |  |  |
| DefineJournalClass.php - Changes required for depreciation journals |  | Tim | 2009-09-16 |  |  |
| GoodsReceived.php - Alterations for purchase of new assets, and some bug fixes |  | Tim | 2009-09-16 |  |  |
| FixedAssetRegister.php - New script to list a fixed asset register |  | Tim | 2009-09-16 |  |  |
| FixedAssetTransfer.php - New script to transfer assets between locations |  | Tim | 2009-09-16 |  |  |
| FixedAssetLocations.php - New script to define fixed asset locations |  | Tim | 2009-09-16 |  |  |
| FixedAssetJournal.php - New script to produce a depreciation journal |  | Tim | 2009-09-16 |  |  |
| FixedAssetItems.php - New script to define individual Fixed Asset items |  | Tim | 2009-09-16 |  |  |
| FixedAssetCategories.php - New script to define categories of Fixed Assets |  | Tim | 2009-09-16 |  |  |
| Added new pdflanguage to www_users table - modified session.inc to read it in and WWW_Users.php to modify the variable for users - not modified UserSettings.php ...yet |  | Phil | 2009-11-24 |  |  |
| PDFBankingSummary.php - Select batch from drop down list |  | Tim | 2009-10-10 |  |  |
| MRPDemands.php - Enable option to use Date picker to choose dates. |  | Tim | 2009-10-10 |  |  |
| MRPCreateDemands.php - Enable option to use Date picker to choose dates. |  | Tim | 2009-10-10 |  |  |
| GLAccountInquiry.php - Allow option to show all transactions |  | Tim | 2009-10-10 |  |  |
| api_debtortransactions.php - Fix errors in checking for valid transaction date |  | Tim | 2009-10-10 |  |  |
| RCFunctions.inc - Fix problem with database criteria statement |  | Tim | 2009-10-08 |  |  |
| WorkOrderIssue.php - Allow for the selection of a date for the manual issue to work orders |  | Tim | 2009-10-08 |  |  |
| Top sales items report |  | Pak Ricard | 2009-10-08 |  |  |
| Fix PO_Items.php to enable changes to prices and weights |  | Phil | 2009-10-07 |  |  |
| upgrade3.11-3.11.1 - Enlarge language field in www_users for utf8 codeset |  | Tim | 2009-10-03 |  |  |
| upgrade3.11-3.12.sql - Enlarge language field in www_users for utf8 codeset |  | Tim | 2009-10-03 |  |  |
| weberp-new.sql - Enlarge language field in www_users for utf8 codeset |  | Tim | 2009-10-03 |  |  |
| weberp-demo.sql - Enlarge language field in www_users for utf8 codeset |  | Tim | 2009-10-03 |  |  |
| Fix to www_users.php branchcode and debtorno could only be 8 characters long should allow 10 |  | Pak Ricard | 2009-09-26 |  |  |
| Fix to Z_ChangeStockCode.php to change the stock code in more recent tables including MRP tables and locationtransfers tables |  | Pak Ricard | 2009-09-25 |  |  |
| Fix to Z_ImportStocks.php to error trap mbflag |  | asv | 2009-09-25 |  |  |
| PrintCustTransPotrait is fixed to print default bank account details on invoice, only if config parameter is set. Tested to work printing single or multilple invoices, and credit notes confirmed as printing OK (no bank account details required). No DB changes required. |  | Murray | 2009-09-21 |  |  |
| StockDispatch.php - Changed to create loctransfers records |  | Mark | 2009-09-20 |  |  |
| Fix SelectOrderItems.php - option to show frequently ordered items, fixed SQL to be generic using existing date functions in /includes/DateFunctions.inc and also the new parameter to allow up to 99 frequently ordered items to display, GP Percentage rounding problem resolved. Corrected indentation and brackets in line with style guide |  | Phil | 2009-09-20 |  |  |
| SystemParameters.php - option to show up to 99 frequently ordered items |  | Phil | 2009-09-20 |  |  |
| upgrade3.11-3.12.sql new config parameter for FrequentlyOrderedItems |  | Phil | 2009-09-20 |  |  |
| weberp-new.sql - Reinstate missing foreign keys |  | Tim | 2009-09-18 |  |  |
| weberp-demo.sql - Reinstate missing foreign keys |  | Tim | 2009-09-18 |  |  |
| Stocks.php - Updates for Asset manager module |  | Tim | 2009-09-16 |  |  |
| WWW_Users.php - Allow the showing of the asset manager module in user setup |  | Tim | 2009-09-16 |  |  |
| index.php - Show new functionality on menu pages |  | Tim | 2009-09-16 |  |  |
| PO_AuthoriseMyOrders.php - New script to show those orders a user can authorise |  | Tim | 2009-09-16 |  |  |
| upgrade3.11-3.12.sql - Changes to www_users to show asset manager module |  | Tim | 2009-09-16 |  |  |
| DefineJournalClass.php - Changes required for depreciation journals |  | Tim | 2009-09-16 |  |  |
| GoodsReceived.php - Alterations for purchase of new assets, and some bug fixes |  | Tim | 2009-09-16 |  |  |
| FixedAssetRegister.php - New script to list a fixed asset register |  | Tim | 2009-09-16 |  |  |
| FixedAssetTransfer.php - New script to transfer assets between locations |  | Tim | 2009-09-16 |  |  |
| FixedAssetLocations.php - New script to define fixed asset locations |  | Tim | 2009-09-16 |  |  |
| FixedAssetJournal.php - New script to produce a depreciation journal |  | Tim | 2009-09-16 |  |  |
| FixedAssetItems.php - New script to define individual Fixed Asset items |  | Tim | 2009-09-16 |  |  |
| FixedAssetCategories.php - New script to define categories of Fixed Assets |  | Tim | 2009-09-16 |  |  |
| PrintCustTransPortrait.php - Correct sql to show invoices |  | Tim | 2009-09-15 |  |  |
| StockReOrderLevel.php - Show the correct number of decimal places for stock levels |  | Tim | 2009-09-15 |  |  |
| SelectSalesOrder.php - Bug fixes and layout changes |  | Tim | 2009-09-15 |  |  |
| PurchData.php - Show supplier unit of measure as a drop down list and various other layout changes |  | Tim | 2009-09-15 |  |  |
| PricesBasedOnMarkup.php - Show text at top of page using help text style |  | Tim | 2009-09-15 |  |  |
| PO_OrderDetails.php - Bug fixes and layout changes |  | Tim | 2009-09-15 |  |  |
| upgrade3.11-3.12.sql - Add tables for asset manager and create extra field for currency decimal places to show |  | Tim | 2009-09-15 |  |  |
| MiscFunctions.inc - Add functions to retrieve decimal places for stock items and cuurency codes |  | Tim | 2009-09-15 |  |  |
| DateFunctions.inc - Various bug fixes and change to GetPeriod() function to allow periods to be created before the Prohibit date |  | Tim | 2009-09-15 |  |  |
| GLBudgets.php - Create periods even when they are before `Prohibit Postings Before Date` |  | Tim | 2009-09-15 |  |  |
| Customers.php - Use javascript functions, and and correctly display numbers |  | Tim | 2009-09-15 |  |  |
| CustomerAllocations.php - Fixes bug that so that $curDebtor is a string not integer so comparison occurs correctly |  | Tim | 2009-09-15 |  |  |
| Added help text to Balance Sheet and P&L, to assist operators, improved layout of HTML view. |  | Murray | 2009-09-07 |  |  |
| Added Frequently Ordered Items to Sales Orders, allows quick selection of top-5 most ordered items which saves operator keystrokes/time. Changed selection of items text from "Order", to "Add to Sales Order" as this is a more accurate label for this button (reduces operator errors). |  | Murray | 2009-09-04 |  |  |
| Adding PDF printing of Sales Orders / Proforma Invoices, these are pre-invoice documents required by some companies |  | Murray | 2009-09-03 |  |  |

## [v3.11] - 2009-08-31

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| upgrade3.10-3.11.sql - Alter column shipvia in table debtortrans to match definition of shipvia in shippers |  | Tim | 2009-08-28 |  |  |
| RecurringSalesOrders.php - Correct for case when recurring order is created directly from the sales order |  | Tim | 2009-08-28 |  |  |
| api_customers.php - Remove echo statement from modify customer function |  | Tim | 2009-08-28 |  |  |
| BankAccounts.php - Correct sql syntax for insert statement |  | David | 2009-08-28 |  |  |
| upgrade3.10-3.11.sql - Add comments for use with the upgrade script |  | Tim | 2009-08-28 |  |  |
| Z_Upgrade_3.10-3.11.php - Change upgrade script to handle the case where some upgrades have already been done. |  | Tim | 2009-08-28 |  |  |
| Fix Shoops CustLoginSetup.php by populating the $ModuleList |  | Phil | 2009-08-26 |  |  |
| SelectCustomer.php ORDER BY clause doubled up (now fixed) following salesman login changes |  | Phil | 2009-08-26 |  |  |
| StockCategories.php - Fixed missing closing > in html |  | Paul | 2009-08-26 |  |  |
| Saleman login - modifications to session.inc SelectOrderItems.php SelectCustomer.php WWW_Users.php - added a new field to www_users for salesman login - when entering orders users with this restricted login will only be able to enter orders against the selected salesman (in WWW_Users.php). The user will also only be able to select customers for the selected salesman too. Note that if this user has access to salesanalysis then they will be analyse the sales of all customers |  | Phil | 2009-08-24 |  |  |
| PDFQuotation.php - Add missing gettext function |  | Tim | 2009-08-21 |  |  |
| PO_Items.php - Correctly use suppliers Unit of Measure and conversion factor |  | Tim | 2009-08-19 |  |  |
| GoodsReceived.php - Correctly use suppliers Unit of Measure and conversion factor |  | Tim | 2009-08-19 |  |  |
| Enable customer login creation from the customer screen |  | Jurgen | 2009-08-19 |  |  |
| ConnectDB_mysqli.inc - Add mysql port configuration |  | Tim | 2009-08-16 |  |  |
| ConnectDB_mysql.inc - Add mysql port configuration |  | Tim | 2009-08-16 |  |  |
| config.distrib.php - Add mysql port configuration |  | Tim | 2009-08-16 |  |  |
| api_xml-rpc.php - Updates to stock adjustment function |  | Tim | 2009-08-15 |  |  |
| api_stock.php - Updates to stock adjustment function |  | Tim | 2009-08-15 |  |  |
| Tax.php - Fix problem with conversion of PeriodEndDate |  | Tim | 2009-08-15 |  |  |
| Add GetStockCategory() function to api |  | Tim | 2009-08-13 |  |  |
| DateFunctions.inc - Update for all supported date formats |  | Harald Ringehahn | 2009-08-13 |  |  |
| MiscFunctions.js - Update for all supported date formats |  | Harald Ringehahn | 2009-08-13 |  |  |
| Correction to database sql scripts |  | Jurgen | 2009-08-13 |  |  |
| Z_poRebuildDefault.php - Add additional paths for gettext |  | Harald Ringehahn | 2009-08-12 |  |  |
| SelectCompletedOrder.php - Correct for missing _ in gettext function call |  | Tim | 2009-08-11 |  |  |
| ConfirmDispatch_Invoice.php - Correct for missing _ in gettext function call |  | Tim | 2009-08-11 |  |  |
| MRPCreateDemands.php - Update for all supported date formats |  | Tim | 2009-08-10 |  |  |
| DateFunctions.inc -Fixed error in GetPeriod() where the period was being incorrectly found |  | Tim | 2009-08-10 |  |  |
| SelectOrderItems.php - Correct for case when all quick entry lines are filled |  | Tim | 2009-08-08 |  |  |
| MiscFunctions.js - Correction to date picker function to use the correct base |  | exleys | 2009-08-05 |  |  |
| WorkOrderIssue.php - Alter for qualitytext field |  | Tim | 2009-08-05 |  |  |
| WorkOrderCosting.php - Fix incorrect calculations in WorkOrderCosting.php |  | DK Shukla | 2009-08-05 |  |  |
| api_stock.php - Update for all supported date formats |  | Tim | 2009-08-05 |  |  |
| api_salesorders.php - Update for all supported date formats |  | Tim | 2009-08-05 |  |  |
| api_suppliers.php - Update for all supported date formats |  | Tim | 2009-08-05 |  |  |
| api_debtortransactions.php - Update for all supported date formats |  | Tim | 2009-08-05 |  |  |
| api_workorders.php - Update for all supported date formats |  | Tim | 2009-08-05 |  |  |
| DeliveryDetails.php - Remove probably unnecessary code |  | Tim | 2009-08-05 |  |  |
| Tax.php - Update for all supported date formats |  | Tim | 2009-08-05 |  |  |
| PO_ReadInOrder.inc - Correct for controlled only items |  | Tim | 2009-07-31 |  |  |
| PO_Header.php - Correction for case when Exch rate is 1 |  | Tim | 2009-07-30 |  |  |
| SuppShiptChgs.php - Correct missing " in html |  | exleys | 2009-07-30 |  |  |
| WOSerialNos.php - Alter for qualitytext field |  | Tim | 2009-07-30 |  |  |
| PrintCustTransPortrait.php - Correct bank invoice field in sql |  | Tim | 2009-07-30 |  |  |
| PrintCustTransPortrait.php - Correct header row for gettext function. |  | Harald Ringehahn | 2009-07-30 |  |  |
| PO_AuthorisationLevels.php - Correct for gettext function |  | Harald Ringehahn | 2009-07-30 |  |  |
| BankAccounts.php - Update table headings for consistancy and to help translations |  | Harald Ringehahn | 2009-07-30 |  |  |
| Missing _ in gettext functions |  | Tim | 2009-07-28 |  |  |
| PO_Header.php - Add link to set up authorisation limits |  | Tim | 2009-07-28 |  |  |
| Add in invoice field to bankaccounts table |  | Tim | 2009-07-28 |  |  |
| CustomerReceipt.php - Add javascript functions |  | Tim | 2009-07-28 |  |  |
| api_debtortrans.php - Verify for the correct length of the tpe field in VerifyTpe() functio |  | Tim | 2009-07-27 |  |  |
| api_branches.php - Check for the coorect values in VerifyDeliverBlind() function |  | Tim | 2009-07-27 |  |  |
| Add in bankaccountcode field |  | Tim | 2009-07-26 |  |  |
| SelectOrderItems.php - Various user interface improvements |  | Tim | 2009-07-24 |  |  |
| SystemParameters.php - Update for German date format |  | Juergen Ruemmler | 2009-07-24 |  |  |
| DateFunctions.inc - Update for German date format |  | Juergen Ruemmler | 2009-07-24 |  |  |
| api_debtortrans.php - Fix sql bugs in insert invoive function |  | Tim | 2009-07-23 |  |  |
| api_customers.php - Correct various bugs |  | Tim | 2009-07-23 |  |  |
| api_branches.php - Correct bug in VerifyFwdDate and make sure default values are set |  | Tim | 2009-07-23 |  |  |
| PO_Header.php - Correctly handle exchange rates |  | Tim | 2009-07-23 |  |  |
| StockAdjustments.php - Correct sql to show qualitytext field in stockserialmoves |  | Tim | 2009-07-23 |  |  |
| PDFPriceList.php - Change output file name to include company name and date |  | Tim | 2009-07-21 |  |  |
| Z_MakeNewCompany.php - Correct the sql for non ansi mode |  | Tim | 2009-07-21 |  |  |
| Correct various spelling mistakes and typos |  | Tim | 2009-07-19 |  |  |
| Logout.php - New logout screen |  | Juergen Ruemmler | 2009-07-19 |  |  |
| Work over installer to try to get it to work and conform to webERP consistency guidelines |  | Phil | 2009-07-18 |  |  |
| DeliveryDetails.php - Correctly show items when returning to order detail |  | Tim | 2009-07-18 |  |  |
| CustomerReceipt.php - Add in javascript functionality |  | Tim | 2009-07-18 |  |  |
| ConfirmDispatch_invoice.php - Some layout changes, javascript addition and minor bug fixes |  | Tim | 2009-07-18 |  |  |
| ConfirmDispatch_invoice.php - Ensure that a standard cost is set |  | Tim | 2009-07-17 |  |  |
| Correctly set mysql autocommit variable |  | Tim | 2009-07-17 |  |  |
| CustomerTypes.php - Prevent empty or duplicate types being created, and remove cancel button |  | Tim | 2009-07-17 |  |  |
| Z_PriceChanges.php - Correct gettext strings to remove % symbol |  | Tim | 2009-07-17 |  |  |
| InventoryValuation.php - Changes to allow longer part descriptions |  | Ricard Andreu | 2009-07-17 |  |  |
| New installer from Moxx consulting |  | Nagaraj Potti | 2009-07-16 |  |  |
| SelectOrderItems.php - Button labels not formatted for gettext |  | Tim | 2009-07-16 |  |  |
| ManualHeader.html - Change manual to work with utf-8 as per Javier de Lorenzo-CÃ¡ceres |  | Tim | 2009-07-16 |  |  |
| ManualContributors.html - Javier de Lorenzo-CÃ¡ceres |  | Tim | 2009-07-16 |  |  |
| ShipmentCosting.php - Correct typo |  | Tim | 2009-07-16 |  |  |
| WorkOrderReceive.php - Correct typo |  | Tim | 2009-07-16 |  |  |
| Payments.php - Correct typo |  | Tim | 2009-07-16 |  |  |
| Z_ReApplyCostToSA.php - Correct typo |  | Tim | 2009-07-16 |  |  |
| TaxAuthorities.php - Correct typo |  | Tim | 2009-07-16 |  |  |
| Suppliers.php - Correct typo |  | Tim | 2009-07-16 |  |  |
| Removed includes/Wiki.php and added wikiLink function to includes/MiscFunctions.php - modified all calls to wikiLink |  | Phil | 2009-07-15 |  |  |
| WorkOrderEntry.php added a link to the wiki - if integrated wiki enabled. |  | Phil | 2009-07-15 |  |  |
| GoodsReceived.php - Fixed sql for missing qualitytext field in INSERT |  | Tim | 2009-07-14 |  |  |
| weberp-demo.sql - Add new data |  | Tim | 2009-07-14 |  |  |
| CustomerReceipt, added link to customer allocations after receipt entry |  | Murray | 2009-07-13 |  |  |
| fixed some div centering for SelectCustomer.php |  | Murray | 2009-07-13 |  |  |
| PO_SelectOSPurchOrder.php - Correct sql for mysql strict mode |  | Tim | 2009-07-10 |  |  |
| PO_Header.php - Correction to goods received link |  | Tim | 2009-07-10 |  |  |
| weberp-new.sql - Remove unecessary data |  | Tim | 2009-07-10 |  |  |
| weberp-demo.sql - Remove unecessary data (audittrail), and add in 3.11 database changes |  | Tim | 2009-07-10 |  |  |
| sql/mysql/upgrade3.10-3.11.sql - Make alteration to type for notes field in custnotes table |  | Tim | 2009-07-10 |  |  |
| weberp-new.sql - Add in new database changes |  | Tim | 2009-07-10 |  |  |
| SelectOrderItems.php - Allow more than one sales order to be open at any one time |  | Tim | 2009-07-10 |  |  |
| DefineCartClass.php - Allow more than one sales order to be open at any one time |  | Tim | 2009-07-10 |  |  |
| SelectOrderItems_IntoCart.inc - Allow more than one sales order to be open at any one time |  | Tim | 2009-07-10 |  |  |
| DeliveryDetails.php - Allow more than one sales order to be open at any one time |  | Tim | 2009-07-10 |  |  |
| PO_Header.php - Add javascript to automatically update reprint status |  | Tim | 2009-07-09 |  |  |
| PO_Header.php - Correct bug when changing stock location for delivery |  | Tim | 2009-07-09 |  |  |
| PO_PDFPurchOrder.php - Corrections in order reprint functionality |  | Tim | 2009-07-09 |  |  |
| PO_Header.php - Corrections in order reprint functionality |  | Tim | 2009-07-09 |  |  |
| POReport.php - Correct sql statement to work in strict mode, changed break tags |  | Mark | 2009-07-07 |  |  |
| SalesInquiry.php - Correct sql statement to work in strict mode, changed break tags |  | Mark | 2009-07-07 |  |  |
| Update images to ensure all themes have the same named images |  | Tim | 2009-07-07 |  |  |
| GLBalanceSheet.php - Correct sql statement to work in strict mode |  | Tim | 2009-07-07 |  |  |
| Payments.php - Correct typing errors |  | Tim | 2009-07-07 |  |  |
| RCFunctions.inc - Correction to still design report if table is empty |  | Tim | 2009-07-07 |  |  |
| weberp-new.sql - Remove references to weberpdemo |  | Tim | 2009-07-07 |  |  |
| SuppInvGRNs.php - Add a select all and deselect all option |  | Tim | 2009-07-05 |  |  |
| PO_ReadInOrder.inc - Updates to allow multiple orders to be opened at the same time |  | Tim | 2009-07-05 |  |  |
| PO_Header.php - Updates to allow multiple orders to be opened at the same time |  | Tim | 2009-07-05 |  |  |
| PO_Items.php - Updates to allow multiple orders to be opened at the same time |  | Tim | 2009-07-05 |  |  |
| class.pdf.php - Correct constructor to conform with php 5.3 |  | Tim | 2009-07-05 |  |  |
| fpdf.php - Function set_magic_quotes_runtime() is deprecated |  | Terry Porter | 2009-07-05 |  |  |
| PDFStarter.php - Assigning the return value of new by reference is deprecated. |  | Terry Porter | 2009-07-05 |  |  |
| BOMExtendedQty.php - Correct sql statement to work in strict mode |  | Tim | 2009-07-04 |  |  |
| SelectProduct.php - Correct sql statement to work in strict mode |  | Tim | 2009-07-04 |  |  |
| doc/INSTALL.txt - Change url to show path to wiki |  | Tim | 2009-07-03 |  |  |
| includes/DefineCartClass.php - Correct sql statement to work in strict mode |  | Tim | 2009-07-03 |  |  |
| PO_Items.php - Change status to pending when an order is modified |  | Tim | 2009-06-30 |  |  |
| sql/mysql/upgrade3.10-3.11.sql - Include missing field |  | Tim | 2009-06-30 |  |  |
| includes/PO_ReadInOrder.inc - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| includes/DefinePOClass.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| GoodsReceived.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| PO_Chk_ShiptRef_JobRef.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| PO_PDFPurchOrder.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| PO_SelectPurchOrder.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| PO_SelectOSPurchOrder.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| PO_OrderDetails.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| PO_Items.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| PO_Header.php - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| sql/mysql/upgrade3.10-3.11.sql - New purchase order functionality |  | Tim | 2009-06-30 |  |  |
| sql/mysql/upgrade3.10-3.11.sql - Add in extra fields for purchase orders |  | Tim | 2009-06-27 |  |  |
| PO_AuthorisationLevels.php - New script to assign authorisation levels for purchase orders |  | Tim | 2009-06-27 |  |  |
| PDFDeliveryDifferences.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| PDFCustomerList.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| PDFChequeListing.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| PDFBankingSummary.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| PaymentTerms.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| PaymentMethods.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| PaymentAllocations.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| OutstandingGRNs.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| OrderDetails.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPShortages.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPReschedules.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPReport.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPPlannedWorkOrders.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPPlannedPurchaseOrders.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPDemandTypes.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPDemands.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPCreateDemands.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRPCalendar.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MRP.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| MailInventoryValuation.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| Logout.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| Locations.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| InventoryValuation.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| InventoryQuantities.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| InventoryPlanningPrefSupplier.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| InventoryPlanning.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| index.php - New menu items |  | Tim | 2009-06-27 |  |  |
| GoodsReceivedControlled.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GoodsReceived.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLTrialBalance.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLTransInquiry.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLTags.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLTagProfit_Loss.php - Script for creating Profit and Loss accounts by Tag |  | Tim | 2009-06-27 |  |  |
| GLProfit_Loss.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLJournal.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLCodesInquiry.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLBudgets.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLBalanceSheet.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLAccounts.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GLAccountInquiry.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GetStockImage.php - Change to lower case html and remove |  | Tim | 2009-06-27 |  |  |
| GeocodeSetup.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| geocode.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| geo_displaymap_suppliers.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| geo_displaymap_customers.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| FTP_RadioBeacon.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| FreightCosts.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| Factors.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| ExchangeRateTrend.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| EmailCustTrans.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| EmailConfirmation.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| EDISendInvoices.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| EDIProcessOrders.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| EDIMessageFormat.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| DiscountMatrix.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| DiscountCategories.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| DemandWorkOrders.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| DeliveryDetails.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| DebtorsAtPeriodEnd.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CustWhereAlloc.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CustomerTypes.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CustomerTransInquiry.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| Customers.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CustomerReceipt.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CustomerInquiry.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CustomerBranches.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CustomerAllocations.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CustEDISetup.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| Currencies.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CreditStatus.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CreditItemsControlled.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| Credit_Invoice.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| ConfirmDispatchControlled_Invoice.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| CompanyPreferences.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| COGSGLPostings.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BOMs.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BOMListing.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BOMInquiry.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BOMIndentedReverse.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BOMIndented.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BOMExtendedQty.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BankReconciliation.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BankMatching.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| BankAccounts.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| AuditTrail.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| Areas.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| AgedSuppliers.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| AgedDebtors.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| AddCustomerTypeNotes.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| AddCustomerNotes.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| AddCustomerContacts.php - Change to lower case html and remove |  | Tim | 2009-06-26 |  |  |
| SelectProduct.php - Correct field line up in inventory item display |  | Turbopt | 2009-06-24 |  |  |
| PeriodsInquiry.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFStockTransfer.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFStockNegatives.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFStockLocTransfer.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFStockCheckComparison.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFQuotation.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFPriceList.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFOrderStatus.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFOrdersInvoiced.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFLowGP.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFGrn.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| PDFDIFOT.php - Change to lower case html and remove |  | Tim | 2009-06-24 |  |  |
| ConfirmDispatch_Invoice.php - Remove backslashes from narrative |  | Tim | 2009-06-24 |  |  |
| SelectOrderItems.php - Remove backslashes from narrative |  | Tim | 2009-06-24 |  |  |
| PrintCustTransPortrait.php - Remove backslashes from narrative |  | Tim | 2009-06-24 |  |  |
| PrintCustTrans.php - Remove backslashes from narrative |  | Tim | 2009-06-24 |  |  |
| ReverseGRN.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| ReorderLevel.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| RecurringSalesOrdersProcess.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| RecurringSalesOrders.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| PurchData.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| PrintCustTransPortrait.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| PrintCustStatements.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| PrintCustOrder.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| PricesBasedOnMarkUp.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| Prices.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| Prices_Customer.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| POReport.php - Change to lower case html and remove |  | Tim | 2009-06-21 |  |  |
| PrintCustOrder_generic.php - Prevent blank line on second page |  | Lindsay | 2009-06-21 |  |  |
| PrintCustTrans.php - Prevent blank page being printed every second page |  | Lindsay | 2009-06-21 |  |  |
| Z_ChangeStockCode.php - Add in check for woitems table |  | Lindsay | 2009-06-21 |  |  |
| some improvements the html and css styles |  | Murray | 2009-06-19 |  |  |
| StockTransferControlled.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockStatus.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockSerialItems.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockSerialItemResearch.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| Stocks.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockReorderLevel.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockQuantityByDate.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockQties_csv.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockMovements.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockLocTransferReceive.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockLocTransfer.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockLocStatus.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockLocMovements.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockCounts.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockCostUpdate.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockCheck.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockCategories.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockAdjustmentsControlled.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| StockAdjustments.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SpecialOrder.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| Shipt_Select.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| Shippers.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| Shipments.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| ShipmentCosting.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectWorkOrder.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectSupplier.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectSalesOrder.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectRecurringSalesOrder.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectProduct.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectOrderItems.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectGLAccount.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectCustomer.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SelectCreditItems.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesTypes.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesPeople.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesInquiry.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesGraph.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesGLPostings.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesCategories.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesAnalysis_UserDefined.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesAnalRepts.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| SalesAnalReptCols.php - Change to lower case html and remove |  | Tim | 2009-06-19 |  |  |
| Added SalesInquiry.php - detailed or summarized inquiries on Sales Orders |  | Mark | 2009-06-12 |  |  |
| Z_UploadForm.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_Upgrade_3.09-3.10.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_Upgrade_3.08-3.09.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_Upgrade_3.07-3.08.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_Upgrade_3.05-3.06.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_Upgrade_3.04-3.05.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_UpdateChartDetailsBFwd.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_SalesIntegrityCheck.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ReverseSuppPaymentRun.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_RePostGLFromPeriod.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ReApplyCostToSA.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_PriceChanges.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_poRebuildDefault.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_poEditLangRemaining.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_poEditLangModule.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_poEditLangHeader.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_poAdmin.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_poAddLanguage.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_MakeStockLocns.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_MakeNewCompany.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ImportStocks.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ImportPartCodes.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ImportGLAccountSections.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ImportGLAccountGroups.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ImportChartOfAccounts.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_DescribeTable.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_DeleteSalesTransActions.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_DeleteInvoice.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_DeleteCreditNote.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_DataExport.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_CurrencySuppliersBalances.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_CurrencyDebtorsBalances.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_CreateCompany.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_CheckGLTransBalance.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_CheckDebtorsControl.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_CheckAllocs.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_CheckAllocationsFrom.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ChangeStockCode.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ChangeCustomerCode.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| Z_ChangeBranchCode.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| WWW_Users.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| WWW_Access.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| WOSerialNos.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| WorkOrderStatus.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| WorkOrderReceive.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| WorkOrderIssue.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| WorkOrderEntry.php - Change to lower case html and remove |  | Tim | 2009-06-17 |  |  |
| ConfirmDispatch_Invoice.php - Correctly show multiple lines of narrative |  | Tim | 2009-06-16 |  |  |
| PrintCustTransPortrait.php - Correctly show multiple lines of narrative |  | Tim | 2009-06-16 |  |  |
| PrintCustTrans.php - Correctly show multiple lines of narrative |  | Tim | 2009-06-16 |  |  |
| Payments.php - Change to lower case html and remove |  | Tim | 2009-06-16 |  |  |
| SelectOrderItems.php - Change to lower case html and remove and include javascript |  | Tim | 2009-06-16 |  |  |
| SelectSalesOrder.php - Change to lower case html and remove |  | Tim | 2009-06-16 |  |  |
| ShiptsList.php - Change to lower case html and remove |  | Tim | 2009-06-16 |  |  |
| StockAdjustments.php - Change to lower case html and remove |  | Tim | 2009-06-16 |  |  |
| SuppInvGRNs.php - Change to lower case html and and change to show GRN batch number and Purchase Order number |  | Tim | 2009-06-16 |  |  |
| WWW_Users.php - Change to lower case html and various changes for Opera compatability |  | Tim | 2009-06-16 |  |  |
| css/jelly/default.css - Correct typo in css |  | Tim | 2009-06-16 |  |  |
| Z_ChangeStockCode.php - Change image name if one exists |  | Tim | 2009-06-15 |  |  |
| DeliveryDetails.php - Correct sql for mysql strict mode compatability |  | Tim | 2009-06-14 |  |  |
| ConfirmDispatch_Invoice.php - Correctly post tax transactions for multiple tax authorities on multiple lines |  | Tim | 2009-06-14 |  |  |
| Added POReport.php - detailed or summarized inquiries on Purchase Orders |  | Mark | 2009-06-12 |  |  |
| BankMatching, added datepickers, and changed date entry format to STARTDATE to ENDDATE |  | Murray | 2009-06-11 |  |  |
| api/api_stock.php - Correct syntax errors in insert stock item function |  | Tim | 2009-05-25 |  |  |
| Fixed bogus Bank Account details line in PrintCustTrans.php and PrintCustTrans_Portrait - also fixed string parameter sent to get_html_translation_table function used in a substitute htmlspecialchars_decode function |  | Phil | 2009-05-22 |  |  |
| Added Reorderlevel.php - parts with quantity below reorderlevel |  | Mark | 2009-05-18 |  |  |
| Added InventoryQuantities.php - Added InventoryQuantities.php - shows locstock records with quantity on hand |  | Mark | 2009-05-18 |  |  |
| Changed header to show page title on a second line and remove labels/icons for company and user name |  | Phil | 2009-05-18 |  |  |
| Rewrite of the function to get the logo file in session.inc to avoid use/dependency of scandir function only became available in php 5. |  | Phil | 2009-05-18 |  |  |
| WorkOrderReceive.php did not play well with numeric nextlotSN - also changes to accomodate receiving of predefined lots/serial numbers where this option is set in the config parameters. Closing work orders now also deletes outstanding predefined lots/serial numbers where they have not already been received. |  | Phil | 2009-05-18 |  |  |
| mod to header menu to save screen real-estate |  | Murray | 2009-05-18 |  |  |
| modification for PO_PDF_PurchOrder.php as current printed long codes incorrectly. |  | ricard | 2009-05-15 |  |  |
| New config parameter SystemParameters.php to change behaviour of work order entry depending on whether controlled items should be defined on work order entry and the quantity derived from the serial numbers or batch quantities entered. Also a config parameter to create works orders automatically where sales order exceeds available stock after outstanding work orders/purchase orders completed/received. |  | Phil | 2009-05-10 |  |  |
| WOSerialNos.php allows serial numbers or batches to be defined up front on creation of a work order and quality info to be entered against the serial numbers etc. |  | Phil | 2009-05-10 |  |  |
| WorkOrderEntry.php now allows entry of serial numbers up front and expects lots and serial numbers to be defined at the time the work order is created. The stockmaster now has a nextserialno field that is used to create serial numbers automatically where this number is set to any positive number (if required) Changes to stocks.php also to accomodate entry of this parameter |  | Phil | 2009-05-09 |  |  |
| includes/header.inc - Corrections for Japanese locale |  | shunichi | 2009-05-09 |  |  |
| GLJournal.php - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| includes/footer.inc - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| css/silverwolf/default.css - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| css/professional- rtl/default.css - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| css/professional/default.css - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| css/jelly/default.css - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| css/gel/default.css - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| css/fresh/default.css - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| css/default/default.css - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| javascripts/MiscFunctions.js - Include date picker and correct browser compatability issues |  | Tim | 2009-05-09 |  |  |
| MRPPlannedWorkOrders.php |  | Mark | 2009-05-08 |  |  |
| Z_Upgrade3.10.php - Add new salesorders fields |  | Tim | 2009-05-07 |  |  |
| sql/mysql/weberp-new.sql - Correct weberp sql data |  | Tim | 2009-05-07 |  |  |
| sql/mysql/weberp-demo.sql - Correct demo data |  | Tim | 2009-05-07 |  |  |
| DeliveryDetails.php now has code to create work orders for manufactured items where the system parameter is set to do so - it can also allocate serial numbers automatically for work orders created where the item is serialised and has a nextserialno value greater than 0 |  | Phil | 2009-05-03 |  |  |
| PrintCustTransPortrait.php - Show line description even when on more than one line |  | Tim | 2009-05-03 |  |  |
| Mess with the API to add $_SESSION['AllowedSecurityTokens'] array to provide role based authentication infrastructure to the api_php.php script - now just to add the checking $PageSecurity variable in_array for exposed functions - Tim to check!! |  | Phil | 2009-05-03 |  |  |
| Added new configuration parameters to SystemParameters for DefaultFactoryLocation, AutoCreateWOs and FactoryManagerEmail as preparation for the automatic creation of works orders at the default factory advising the factory manager of the work orders created. |  | Phil | 2009-05-02 |  |  |
| DeliveryDetails.php and RecurringSalesOrdersProcess.php made it so sales order headers use the order number from GetNextTransNo (30) as well as the sales order details - and avoiding the inconsistency issues where the auto-increment was out of sync with the systypes typeid=30 typeno |  | Phil | 2009-04-30 |  |  |
| StockCategories.php - Correct form refresh to show changes in stock types when a category is being edited |  | Tim | 2009-05-02 |  |  |
| Add field to Stocks.php for NextSerialNo - where > 0 and Serialised then automatically allocates serial numbers for quantities on new work orders - and work orders will not allow quantities to change once created |  | Phil | 2009-04-30 |  |  |
| StockCategories.php - Change default behaviour to show Stock GL Code |  | Tim | 2009-04-29 |  |  |
| InventoryPlanning.php - InventoryPlanning.php now allowing 1, 1.5, 2, 3 and 4 months of 'Maximum number of Months Holding' instead of 3 or 4 as used to be. Also the â€œNilâ€� on Suggested order column gets right justified as all the other fields. |  | Ricard Andreu | 2009-04-29 |  |  |
| fix some HTML typos in DeliveryDetails and remove 6 digit price restriction from Credit_Invoice |  | Murray | 2009-04-28 |  |  |
| api/api_xml-rpc.php - Corrections to enable API documentation to be shown |  | Tim | 2009-04-27 |  |  |
| doc/Manual/ManualAPIFunctions.php - Corrections to enable API documentation to be shown |  | Tim | 2009-04-27 |  |  |
| doc/Manual/ManualContents.php - Corrections to enable API documentation to be shown |  | Tim | 2009-04-27 |  |  |
| CustomerBranches.php - Reinstate changes to correctly update latitude and longitude |  | Tim | 2009-04-26 |  |  |
| add some extra help to SelectOrderItems.php |  | Murray | 2009-04-21 |  |  |
| add some sanity checking to geocode section on suppliers and customer branches |  | Murray | 2009-04-20 |  |  |
| CustomerReceipt.php - Add facility to search by invoice number |  | Tim | 2009-04-19 |  |  |
| reportwriter/admin/RCFunctions.inc - Check that the imported file is of type Text |  | Tim | 2009-04-19 |  |  |
| SpecialOrder.php - Update systypes when the order is placed |  | Tim | 2009-04-19 |  |  |
| DeliveryDetails.php - Check that BestShipper is initialised and trap error if not. |  | David Lamotte | 2009-04-19 |  |  |
| SelectOrderItems.php DefineCartClass.php SelectOrderItems_IntoCart.inc ConfirmDispatch_Invoice.php RecurringSalesOrders.php - modified the cart class function add_to_cart to add the standardcost of a line item as it is added - also changed all calling scripts to use this as necessary rather than have specific code to update the cost retrospectively |  | Phil | 2009-04-18 |  |  |
| PrintCustTransPortrait.php vertical lines of grid on a full page in the wrong place - now fixed |  | Phil | 2009-04-18 |  |  |
| CustomerBranches.php - Correct sql for strict mode compatability |  | Tim | 2009-04-16 |  |  |
| SelectOrderItems.php GP % entry now works for FX orders! |  | Phil | 2009-04-15 |  |  |
| SelectOrderItems.php now has option to enter a GP % on a line that calculates the price |  | Phil | 2009-04-14 |  |  |
| Update PurchData.php now allows multiple prices from a supplier with different effective dates |  | Ricard | 2009-04-14 |  |  |
| Some UI work for sales order entry, sales orders can now take quote date and confirmed date |  | Murray | 2009-04-15 |  |  |
| SelectCompletedOrder.php - Add in option to just view completed orders and clean up html for xhtml compatability |  | Tim | 2009-04-11 |  |  |
| SelectProduct.php - Correct justification and show prices info when security is high enough |  | Tim | 2009-04-11 |  |  |
| SelectCustomer.php - Correctly change the customer when returning from OrderDetails.php |  | Tim | 2009-04-10 |  |  |
| includes/session.inc - Ensure last visit field is always updated |  | Tim | 2009-04-10 |  |  |
| Z_Upgrade3.10.php - Remove deprecated |  | Tim | 2009-04-10 |  |  |
| reportwriter/admin/forms/TplFrmTtl.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmText.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmTBlk.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmTbl.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmRect.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmPgNum.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmLine.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmImg.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmData.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmCDta.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/TplFrmCBlk.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/ReportsRename.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/ReportsPageSetup.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/ReportsImport.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/ReportsID.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/ReportsHome.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/ReportsFieldSetup.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/ReportsDBSetup.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| reportwriter/admin/forms/ReportsCritSetup.html - Remove duplication of &lt;html&gt; and &lt;body&gt; tags |  | Tim | 2009-04-09 |  |  |
| SupplierInvoice.php - Corrections to Tax calculation order |  | Tim | 2009-04-09 |  |  |
| SelectProduct.php - Add link to maintain discount category, clean up html to fit guidelines, and xhtm compatability |  | Tim | 2009-04-09 |  |  |
| GLBudgets.php - Allow aportionment for both years, add javascript, and xhtml compatability |  | Tim | 2009-04-08 |  |  |
| css/silverwolf/default.css - Include specific entities for numbers |  | Tim | 2009-04-08 |  |  |
| css/professional- rtl/default.css - Include specific entities for numbers |  | Tim | 2009-04-08 |  |  |
| css/professional/default.css - Include specific entities for numbers |  | Tim | 2009-04-08 |  |  |
| css/jelly/default.css - Include specific entities for numbers |  | Tim | 2009-04-08 |  |  |
| css/gel/default.css - Include specific entities for numbers |  | Tim | 2009-04-08 |  |  |
| css/fresh/default.css - Include specific entities for numbers |  | Tim | 2009-04-08 |  |  |
| css/default/default.css - Include specific entities for numbers |  | Tim | 2009-04-08 |  |  |
| DiscountMatrix.php - Improve methods of input, include javascript, and remove for xhtml compatability |  | Tim | 2009-04-08 |  |  |
| DiscountCategories.php - Improve methods of input, include javascript, and remove for xhtml compatability |  | Tim | 2009-04-08 |  |  |
| index.php - Incorrect link to BOMExtendedQty.php |  | Tim | 2009-04-06 |  |  |
| Stocks.php - Move reload form function to javascript library |  | Tim | 2009-04-06 |  |  |
| javascripts/MiscFunctions.js - Move reload form function to javascript library |  | Tim | 2009-04-06 |  |  |
| SelectCompletedOrder.php - Amend sql to show completed orders only |  | Tim | 2009-04-03 |  |  |
| SelectCompletedOrder.php - Amend sql to show parts even when no purchase orders |  | Tim | 2009-04-02 |  |  |
| SelectProduct.php - Correctly show category product properties |  | Tim | 2009-04-01 |  |  |
| PurchData.php - Show most recent price first |  | Tim | 2009-04-01 |  |  |
| sql/mysql/weberp-new.sql - Correction to purchdata table to allow for multiple data |  | Tim | 2009-04-01 |  |  |
| sql/mysql/weberp-demo.sql - Correction to purchdata table to allow for multiple data |  | Tim | 2009-04-01 |  |  |
| sql/mysql/upgrade3.10-3.11.sql - Correction to purchdata table to allow for multiple data |  | Tim | 2009-04-01 |  |  |
| SelectCustomer.php - This will give a link to setup customer types if they are not already |  | CW | 2009-03-26 |  |  |
| Customers.php - This will give a link to setup customer types if they are not already |  | CW | 2009-03-26 |  |  |
| Stocks.php - Errors in Camelcasing with pansize variable |  | Tim | 2009-03-26 |  |  |
| OrderDetails.php - Correctly show the debtor number in inquiry screen |  | Tim | 2009-03-25 |  |  |
| sql/mysql/weberp-new.sql - Add Primary key to geocode_param table |  | Tim | 2009-03-24 |  |  |
| sql/mysql/upgrade3.10-3.11.sql - Add Primary key to geocode_param table |  | Tim | 2009-03-24 |  |  |
| sql/mysql/weberp-new.sql - correct geocode_param table setup |  | Tim | 2009-03-24 |  |  |
| sql/mysql/upgrade3.10-3.11.sql - Insert default value into factor companies table |  | Tim | 2009-03-24 |  |  |
| config.distrib.php - Update version number |  | Tim | 2009-03-24 |  |  |
| CustomerReceipt.php - Revert patch that split up receipt batch |  | Tim | 2009-03-24 |  |  |
| sql/mysql/upgrade3.10-3.11.sql - Tidy up sql |  | Tim | 2009-03-23 |  |  |
| includes/SelectOrderItems_IntoCart.inc - Use the SESSION variable for WarnOnce correctly |  | Tim | 2009-03-23 |  |  |
| includes/PO_PDFOrderPageHeader.inc - Correct typo in variable name |  | Tim | 2009-03-23 |  |  |
| Suppliers.php - Correct indentation, and initialise the latitude and longitude variables |  | Tim | 2009-03-23 |  |  |
| SupplierInvoice.php - Find out the supplier name in all cases and ensure that ['OverRideTax'] is set before using it in test |  | Tim | 2009-03-23 |  |  |
| SupplierCredit.php - Ensure that ['OverRideTax'] is set before using it in test |  | Tim | 2009-03-23 |  |  |
| SuppInvGRNs.php - Add in closing &lt;font&gt; tag and show the suppliers name correctly |  | Tim | 2009-03-23 |  |  |
| StockCategories.php - Correctly check for uninitialised POST variables |  | Tim | 2009-03-23 |  |  |
| SelectOrderItems.php - Correct html for xhtml compatability |  | Tim | 2009-03-23 |  |  |
| SalesGLPostings.php - Initial assignment of |  | Tim | 2009-03-23 |  |  |
| PO_PDFPurchOrder.php - Show purchase order number correctly |  | Tim | 2009-03-23 |  |  |
| PO_Items.php - Only show Purchase order text once |  | Tim | 2009-03-23 |  |  |
| PO_Header.php - Show location address when the lookup address button is pushed |  | Tim | 2009-03-23 |  |  |
| Payments.php - Show individual bank payments as bank transactions rather than the batch total, to help in bank reconciliation |  | Tim | 2009-03-23 |  |  |
| Locations.php - Ensure that ['Managed'] is set before using it in test |  | Tim | 2009-03-23 |  |  |
| GLJournal.php - Correct typo in select tag |  | Tim | 2009-03-23 |  |  |
| DeliveryDetails.php - Correct variable assignments and other mnor bugs |  | Tim | 2009-03-23 |  |  |
| CustomerTypes.php - Correct variable assignment for default customer type |  | Tim | 2009-03-23 |  |  |
| CustomerReceipt.php - Show individual bank receipts as bank transactions rather than the batch total, to help in bank reconciliation |  | Tim | 2009-03-23 |  |  |
| CreditStatus.php - Ensure that the Disallow invoice check box is ticked when updating a credit status, if it is set. |  | Tim | 2009-03-23 |  |  |
| BOMs.php - Ensure that ['WorkCentreAdded'] is set before using it in test |  | Tim | 2009-03-23 |  |  |
| Areas.php - Correct Update sql statement to correctly updatre a sales areas details |  | Tim | 2009-03-23 |  |  |
| api/api_salesorders.php - Add calls for getting details of the Sales Order Header & Order Lines from a Sales order Number |  | Abhijit | 2009-03-23 |  |  |
| api/api_locations.php - Add calls for Inserting and modifying locations |  | Abhijit | 2009-03-23 |  |  |
| bug fixes to geocodesetup.php, allows editing, deleting of geocode data, prevents bad data |  | Murray | 2009-03-23 |  |  |
| SelectProduct.php - Allow purchasing data for manufactured items |  | Tim | 2009-03-21 |  |  |
| BOM indented listing (BOMIndented.php), indented reverse BOM (where used) listing (BOMIndentedReverse.php), Extended BOM requirements listing (BOMExtendedQty.php) |  | Mark Yeager | 2009-03-16 |  |  |
| MRP - new scripts to create Demand, Demand Types, amend demand (master schedule), reports for reschedules, proposed orders, MRP listings - very comprehensive contribution indeed!! |  | Mark Yeager | 2009-03-14 |  |  |
| Stocks.php - Create pdf_append directory if it doesnt already exist |  | Tim | 2009-03-13 |  |  |
| RecurringSalesOrdersProcess.php - Fix numerous bugs in automatic invoicing of recurring orders |  | Tim | 2009-03-10 |  |  |
| RecurringSalesOrdersProcess.php - Bug in automatic invoicing of recurring orders |  | Tim | 2009-03-09 |  |  |
| Numbers/Words.php - Ensure $locale has a sane value |  | Tim | 2009-03-08 |  |  |
| ConfirmDispatch_Invoice.php - Correct freight behaviour for re-deliveries |  | Tim | 2009-03-08 |  |  |
| TaxGroups.php - Update to show group being edited, and include javascript |  | Tim | 2009-03-07 |  |  |
| TaxGroups.php - re arrange screen to show update order at the top, and clean up the code |  | Tim | 2009-03-06 |  |  |
| WWW_Users.php - Allow 3 character user names |  | Tim | 2009-03-05 |  |  |
| includes/PDFTransPageHeaderPortrait.inc - Correctly show logo width and position |  | Tim | 2009-03-02 |  |  |
| includes/class.pdf.php - Correctly initialise tmp variable |  | Tim | 2009-03-02 |  |  |
| PrintCustTransPortrait.php - Correctly initialise variable, and comment out bank details |  | Tim | 2009-03-02 |  |  |
| SelectCustomer.php - Correct bug in paging buttons |  | Ashish Shukla | 2009-03-02 |  |  |
| Some CSS fixes for purchase orders |  | Murray | 2009-02-27 |  |  |
| PrintCustTransPortrait.php - This fixes problems with pdf_append |  | Murray | 2009-02-27 |  |  |
| PrintCustTrans.php - Correct bug in invoice printing |  | Tim | 2009-02-26 |  |  |
| sql/mysql/weberp-demo.sql - Latest weberp-demo.sql file |  | Tim | 2009-02-25 |  |  |
| locale/en_GB/LC_MESSAGES/messages.po - Latest messages.po files |  | Tim | 2009-02-25 |  |  |
| install/maintenance_db.inc - Updates to installer |  | Tim | 2009-02-25 |  |  |
| install/index.php - Updates to installer |  | Tim | 2009-02-25 |  |  |
| install/timezone.php - Updates to installer |  | Tim | 2009-02-25 |  |  |
| install/save.php - Updates to installer |  | Tim | 2009-02-25 |  |  |
| includes/PDFOrderPageHeader.inc - Strip slashes to correctly show in printed order |  | Tim | 2009-02-25 |  |  |
| includes/PDFOrderPageHeader_generic.inc - Strip slashes to correctly show in printed order |  | Tim | 2009-02-25 |  |  |
| SelectOrderItems.php - Unescape comments to correctly show in order |  | Tim | 2009-02-25 |  |  |
| Areas.php - Correct for XHTML compatability |  | Tim | 2009-02-24 |  |  |
| AgedSuppliers.php - Correct for XHTML compatability |  | Tim | 2009-02-24 |  |  |
| AgedDebtors.php - Correct for XHTML compatability |  | Tim | 2009-02-24 |  |  |
| AddCustomerNotes.php - Correct for XHTML compatability |  | Tim | 2009-02-24 |  |  |
| AddCustomerContacts.php - Correct for XHTML compatability |  | Tim | 2009-02-24 |  |  |
| AccountSections.php - Correct for XHTML compatability |  | Tim | 2009-02-24 |  |  |
| AccountGroups.php - Correct for XHTML compatability |  | Tim | 2009-02-24 |  |  |
| CustomerBranches.php - Correct problem preventing branches being deleted |  | Tim | 2009-02-24 |  |  |
| includes/PDFAgedDebtorsPageHeader.inc - Correctly ensure that $HeadingLine3 has been initialised |  | Tim | 2009-02-24 |  |  |
| css/silverwolf/default.css - Update theme for xhtml compatability work |  | Tim | 2009-02-24 |  |  |
| css/professional- rtl/default.css - Update theme for xhtml compatability work |  | Tim | 2009-02-24 |  |  |
| css/professional/default.css - Update theme for xhtml compatability work |  | Tim | 2009-02-24 |  |  |
| css/jelly/default.css - Update theme for xhtml compatability work |  | Tim | 2009-02-24 |  |  |
| css/gel/default.css - Update theme for xhtml compatability work |  | Tim | 2009-02-24 |  |  |
| css/fresh/default.css - Update theme for xhtml compatability work |  | Tim | 2009-02-24 |  |  |
| css/default/default.css - Update theme for xhtml compatability work |  | Tim | 2009-02-24 |  |  |
| Z_Upgrade3.10.php - Script for upgrading stable versions |  | Tim | 2009-02-24 |  |  |
| fixes to PrintCustStatements to output PDF doc name rather than script name. |  | Murray | 2009-02-24 |  |  |
| fixes to PrintCustStatements to allow being called from SelectCustomer.php, so customer statements can be printed from Selected Customer. |  | Murray | 2009-02-24 |  |  |
| Nailed the creation of loads of periods on setting up a new system - the GetPeriod function was being called before the GLPostings function - the GetPeriod function now creates the first couple of periods if none exist. |  | Phil | 2009-02-20 |  |  |
| PrintCustTrans.php and PrintCustTransPortrait.php - Update to correctly email invoice to customer |  | Tim | 2009-02-18 |  |  |
| sql/mysql/upgrade3.09-3.10.sql - Changed debtortrans.trandate to date from datetime |  | Phil | 2009-02-17 |  |  |
| PrintCustTransPortrait.php - Update to correctly email invoice to customer |  | Tim | 2009-02-17 |  |  |
| WorkOrderCosting.php - Correctly calculate cost variances |  | Tim | 2009-02-17 |  |  |
| PrintCustTrans. - Update to correctly email invoice to customer |  | Tim | 2009-02-17 |  |  |
| BOMInquiry.php BOMs.php Stocks.php WorkOrderReceive.php WorOrderEntry.php includes/SQL_CommonFunctions.inc Z_Im/ortStocks.php - modifications to allow Phantom Items - a new class of item type G - ghost (since P for Phantom could mean purchased). These allow sub-components to be included in the top level of a bill of material as a single line making BOMs simpler to follow and create - the components of ghosts/phantoms are exploded automatically on work order entry so the full requirements show against the work order. |  | Matt Taylor | 2009-02-12 |  |  |
| first commit of web based install UI |  | Moxx Consulting | 2009-02-11 |  |  |
| weberp-new.sql - Update data for factor companies and debtortype. |  | Tim | 2009-02-10 |  |  |
| WorkOrderCosting.php - Correct sql to correctly calculate costings. |  | Tim | 2009-02-10 |  |  |
| CustomerAllocations.php - Remove commented code that was preventing gettext from working |  | Tim | 2009-02-09 |  |  |
| Correct messages.po file for v3.10 |  | Tim | 2009-02-09 |  |  |
| GeocodeSetup.php - Correct the lack of gettext function. |  | Harald Ringehahn | 2009-02-09 |  |  |
| sessions.inc - Correction for logo search |  | Tim | 2009-02-08 |  |  |

## [v3.10] - 2009-02-06

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| session.inc - xhtml compatability work. |  | Bogdan Stanciu | 2009-02-06 |  |  |
| PDFStockLocTransferHeader.inc - Correct layout problems |  | Tim | 2009-02-06 |  |  |
| ConnectDB_mysql.inc and ConnectDB_mysqli.inc - xhtml compatability work. |  | Bogdan Stanciu | 2009-02-06 |  |  |
| AccountGroups.php - xhtml compatability work. |  | Bogdan Stanciu | 2009-02-06 |  |  |
| PDFStockLocTransfer.php - Correct layout problems and uninitialised variable assignments |  | Tim | 2009-02-06 |  |  |
| StockLocTransfer.php - Add javascript and other UI improvements, and default control, and correctly initialise variables |  | Tim | 2009-02-06 |  |  |
| StockLocTransferReceive.php - Add javascript and other UI improvements, and default control, and correctly initialise variables |  | Tim | 2009-02-06 |  |  |
| WWW_Users.php - Correctly show default theme in selection |  | Tim | 2009-02-06 |  |  |
| Update default.css for image border |  | Tim | 2009-02-05 |  |  |
| PDFGrnHeader.php - Correctly Assign Logo variable. |  | Tim | 2009-02-05 |  |  |
| header.inc - Correct html for xhtml compatability |  | Bogdan Stanciu | 2009-02-05 |  |  |
| footer.inc - Correct html for xhtml compatability |  | Bogdan Stanciu | 2009-02-05 |  |  |
| MiscFunctions.js - Add externalLinks() function for xhtml compatability |  | Bogdan Stanciu | 2009-02-05 |  |  |
| AccountGroups.php - xhtml compatability work. |  | Bogdan Stanciu | 2009-02-05 |  |  |
| WhereUsedInquiry.php - Add javascript and other UI improvements, and default control |  | Tim | 2009-02-04 |  |  |
| GoodsReceived.php - Add javascript and other UI improvements, and default control |  | Tim | 2009-02-04 |  |  |
| DefinePOClass.php - Remove unnecessary variable declarations |  | Tim | 2009-02-04 |  |  |
| PO_SelectOSPurchOrder.php - Add javascript and other UI improvements, and default control |  | Tim | 2009-02-04 |  |  |
| BOMs.php - Add javascript and other UI improvements, and default control |  | Tim | 2009-02-04 |  |  |
| WorkCentres.php - Add javascript and other UI improvements, and default control |  | Tim | 2009-02-04 |  |  |
| BOMListing.php - Add javascript and other UI improvements, and default control |  | Tim | 2009-02-04 |  |  |
| small changes for better theme support |  | Murray | 2009-02-04 |  |  |
| CustomerReceipt.php - Correct bugs in variable default assignments |  | Tim | 2009-02-04 |  |  |
| BOMInquiry.php - Add javascript and other UI improvements, and default control |  | Tim | 2009-02-03 |  |  |
| SelectWorkOrder.php - Add javascript to check input form, and default control |  | Tim | 2009-02-03 |  |  |
| WorkOrderEntry.php - Add javascript to check input form, and default control |  | Tim | 2009-02-03 |  |  |
| GLTags.php - Add default control |  | Tim | 2009-02-03 |  |  |
| GLBudgets.php - Add javascript to check input form, and default control |  | Tim | 2009-02-03 |  |  |
| MiscFunctions.js - Add function to select the default control for the form |  | Tim | 2009-02-03 |  |  |
| GLJournal.php - Add default control selection |  | Tim | 2009-02-03 |  |  |
| GLAccounts.php - Add javascript to check input form, and default control |  | Tim | 2009-02-02 |  |  |
| AccountSections.php - Add javascript to check input form, and default control |  | Tim | 2009-02-02 |  |  |
| AccountGroups.php - Add javascript to check input form, and default control |  | Tim | 2009-02-02 |  |  |
| Tax.php - Correct typos in variable names |  | Tim | 2009-02-02 |  |  |
| GLBalanceSheet.php - Default balance date to current period and ensure that all variables are initialised |  | Tim | 2009-02-02 |  |  |
| GLProfit_Loss.php - Correctly show From and to dates and ensure that all variables are initialised |  | Tim | 2009-02-02 |  |  |
| PDFChequeListing.php - Add javascript to check valid dates |  | Tim | 2009-02-02 |  |  |
| SelectGLAccount.php - Improve user interface, and add in javascript code |  | Tim | 2009-02-02 |  |  |
| GLTrialBalance.php - Correctly show From and to dates |  | Tim | 2009-02-02 |  |  |
| Z_Upgrade_3.09-3.10.php - Add upgrade script |  | Tim | 2009-02-01 |  |  |
| SelectSupplier.php - Correct uninitialised variables |  | Tim | 2009-02-01 |  |  |
| GLJournal.php - Correct uninitialised variables |  | Tim | 2009-02-01 |  |  |
| Payments.php - Add javascript functionality |  | Tim | 2009-02-01 |  |  |
| CustomerReceipt.php - Add javascript functionality |  | Tim | 2009-02-01 |  |  |
| BankMatching.php - Add javascript functionality |  | Tim | 2009-02-01 |  |  |
| MiscFunctions.js - Allow for negative signs in numeric input fields |  | Tim | 2009-02-01 |  |  |
| INSTALL.txt - Add instructions for renaming config file |  | Tim | 2009-02-01 |  |  |
| api_workorders.php - Correct date verification calls |  | Tim | 2009-02-01 |  |  |
| api_stockcategories.php - Add StockCatPropertyList() function and correct return values in other functions |  | Tim | 2009-02-01 |  |  |
| api_salestypes.php - Add InsertSalesType() function and correct return values in other functions |  | Tim | 2009-02-01 |  |  |
| PDFDIFOT.php - Remove superfluous line break. |  | Tim | 2009-01-30 |  |  |
| Added htmlspecialchars_decode support for <5.1 PHP versions. Fixed up geocode support. |  | Murray | 2009-01-30 |  |  |
| Added CSS classes to other themes to support better page titles and help fields. |  | Murray | 2009-01-28 |  |  |
| InputSerialItemsKeyed.php - Jump to first serial number field, to enable bar code scanner to work. |  | Max | 2009-01-27 |  |  |
| CustomerTypes.php - Correct sql statement |  | Bogdan Stanciu | 2009-01-27 |  |  |
| More jelly theme support, adds some CSS compatibilty support |  | Murray | 2009-01-27 |  |  |
| DebtorsAtPeriodEnd.php - Correct header string |  | Bogdan Stanciu | 2009-01-26 |  |  |
| Add_SerialItems.php - Correct typo in variable name. |  | Bogdan Stanciu | 2009-01-26 |  |  |
| SystemCheck.php - Add mysql character set inquiry |  | Tim | 2009-01-26 |  |  |
| Stocks.php - Correctly initialise variables |  | Tim | 2009-01-25 |  |  |
| PDFStockTransferHeader.php - Initialise $XPos variable to zero |  | Tim | 2009-01-25 |  |  |
| StockTransfers.php - Correct missing values for $ErrMsg and $DbgMsg |  | Tim | 2009-01-25 |  |  |
| PO_SelectPurchOrder.php - Correctly initialise $_POST['StockLocation'] for all possibilities |  | Tim | 2009-01-25 |  |  |
| SelectProduct.php - Initialise variable $Its_A_Labour_Item |  | Tim | 2009-01-25 |  |  |
| SelectProduct.php - Correct typo in variable name $DbgMsg |  | Tim | 2009-01-25 |  |  |
| CustomerBranches.php - Ensure all variables are initialised |  | Tim | 2009-01-25 |  |  |
| AddCustomerContacts.php - Correctly initialise $_GET['delete'] |  | Tim | 2009-01-25 |  |  |
| Customers.php - Correctly initialise $ID, $Edit and $_POST['New'] |  | Tim | 2009-01-25 |  |  |
| GLAccountInquiry.php - Correctly initialise $ShowIntegrityReport and $_POST['tag'] |  | Tim | 2009-01-25 |  |  |
| GLTransInquiry.php - Correctly initialise counter variable $j |  | Tim | 2009-01-25 |  |  |
| PrintCustTrans.php - Correct typo in variable name |  | Tim | 2009-01-25 |  |  |
| SelectCustomer.php - Ensure that $_SESSION['CustomerID'] is initialised |  | Tim | 2009-01-25 |  |  |
| SelectOrderItems.php - Ensure that $_POST['part_' . $i] is initialised |  | Tim | 2009-01-25 |  |  |
| SelectOrderItems.php - Correctly find location name |  | Tim | 2009-01-25 |  |  |
| footer.inc - Correctly initialise $_SERVER['HTTPS'] |  | Tim | 2009-01-25 |  |  |
| Changes to WorkOrderCosting.php |  | Ashish Shukla | 2009-01-24 |  |  |
| Corrections to api functions |  | Tim | 2009-01-23 |  |  |
| PrintCustTransPortrait.php - Check that payment.jpg exists before trying to print it |  | Tim | 2009-01-23 |  |  |
| PDFTransPageHeader.inc - correctly decode html special characters |  | Tim | 2009-01-21 |  |  |
| Add jelly theme which adds more icon support and some minor UI improvements. |  | Murray | 2009-01-21 |  |  |
| Store the logo in a session variable, and search for the logo selecting a default if none present. |  | Klaus Wulff | 2009-01-17 |  |  |
| Correct the default date format in GetPeriod call. |  | Tim | 2009-01-16 |  |  |
| Show terms on SelectOrderItems.php - make Location on SelectOrderItems.php use Cart class $_SESSION variable LocationName - rather than ugly hack that had crept in somehow. |  | Phil | 2009-01-14 |  |  |
| Update to gel theme from CW |  | CW | 2009-01-13 |  |  |
| Script to copy a bom - Z_CopyBOM.php |  | Ashish Shukla | 2009-01-13 |  |  |
| Correctly show sourceforge logo in footer |  | Tim | 2009-01-13 |  |  |
| geocode, add map width and height variable to selectcustomer and selectsupplier. |  | Pablo Martin | 2009-01-13 |  |  |
| Fix typing error in WorkOrderCosting.php. |  | Pablo Martin | 2009-01-12 |  |  |
| Payments.php - Fix strings not in correct gettext format, as per Klaus Wulff |  | Klaus Wulff | 2009-01-12 |  |  |
| Changes to PaymentTerms.php to fix errors in variable checks. |  | Klaus Wulff | 2009-01-12 |  |  |
| Changes to footer.inc to show the day of the week in the correct language, and to search for logo. |  | Klaus Wulff | 2009-01-12 |  |  |
| Fix CompanyPreferences for when GLAccounts dont exist |  | Klaus Wulff | 2009-01-12 |  |  |
| Add api documentation page to manual. |  | Tim | 2009-01-10 |  |  |
| Change area column in salesanalysis table to be 3 characters long |  | Tim | 2009-01-10 |  |  |
| Update work orders api. |  | Tim | 2009-01-10 |  |  |
| Correct bug in SelectOrderItems.php preventing all order lines from being modified |  | Tim | 2009-01-06 |  |  |
| Add purchase order number to GRN |  | Tim | 2009-01-06 |  |  |
| MiscFunctions.js - Remove spaces at end of file |  | Tim | 2009-01-06 |  |  |
| api_salesareas.php - Add VerifyAreaCodeDoesntExist() function |  | Tim | 2009-01-06 |  |  |
| api_debtortransactions.php - Correction to GetSalesGLPosting function |  | Tim | 2009-01-06 |  |  |
| Addition to mysql update script to correct salesglpostings definition |  | Tim | 2009-01-06 |  |  |
| PO_Items.php - Correct lookup price bug when editing an item |  | Tim | 2008-12-29 |  |  |
| StockLocStatus.php - Show status even when qty is zero |  | Tim | 2008-12-26 |  |  |
| Correct session timeout problem |  | Tim | 2008-12-26 |  |  |
| Index.php - New menu item for adding purchase order |  | Tim | 2008-12-24 |  |  |
| Corrections for updating GL tags |  | Tim | 2008-12-24 |  |  |
| GLJournal.php - Improvements to journal entry |  | Tim | 2008-12-24 |  |  |
| GLBudget.php - Improvements to budget update |  | Tim | 2008-12-24 |  |  |
| Update api for salesman and sales area functions |  | Tim | 2008-12-24 |  |  |
| UI updates to SelectProduct to match Customer/Supplier select screens. |  | Murray | 2008-12-18 |  |  |
| Customer Inquiry and Supplier Inquiry UI updates to match each others screen layout |  | Murray | 2008-12-18 |  |  |
| PDF Append, fix Stocks to append correct '0' state, allow Invoice printing to accept either '0' or 'none' state |  | Murray | 2008-12-17 |  |  |
| Sales Orders, fix searching for Order Items, display of correct selected method |  | Murray | 2008-12-17 |  |  |
| Sales Orders, clean up display of order entry, rename "Customer No." to "Customer Code" |  | Murray | 2008-12-17 |  |  |
| SelectSupplier, modified to match UI of SelectCustomer |  | Murray | 2008-12-17 |  |  |
| CRM related: Configurations settings allows choice of extended Supplier Data or Customer Data |  | Murray | 2008-12-16 |  |  |
| SelectCustomer, code changes to match code standards, cleanup |  | Murray | 2008-12-16 |  |  |
| CRM related: SelectCustomer extended customer info available |  | Murray | 2008-12-14 |  |  |
| CRM related: SelectCustomer now can add notes, add group notes, add contacts |  | Murray | 2008-12-14 |  |  |
| Login.php Select default company in login list |  | Tim | 2008-12-04 |  |  |
| Improve and reduce javascript functions |  | Tim | 2008-12-04 |  |  |
| New api functions to retrieve webERP settings |  | Tim | 2008-12-04 |  |  |
| New theme called gel |  | CW | 2008-12-04 |  |  |
| CRM related: SelectCustomer.php now has export of customers via CSV format |  | Murray | 2008-11-28 |  |  |
| CRM related: SelectCustomer.php now has search by Customer Type |  | Murray | 2008-11-24 |  |  |
| css/default/default.css links now have white background was possible to lose text on blue background with on hover |  | CW | 2008-11-24 |  |  |
| includes/ConnectDB_mysql.inc - fix transaction abstraction calls to mysql_query - $Conn after SQL |  | CW | 2008-11-24 |  |  |
| Correct bug in WorkOrderIssue.php stopping the updating of stockserialitems for batched items. |  | Tim | 2008-11-15 |  |  |
| Add GetBatches() function to api |  | Tim | 2008-11-14 |  |  |
| Add batch control to WorkOrderIssue() api function |  | Tim | 2008-11-14 |  |  |
| Improvements to JavaScript functions and UI improvements in GLJournal.php |  | Tim | 2008-11-13 |  |  |
| Correct the discount percentage check in api_salesorders.php |  | Abhijit | 2008-11-12 |  |  |
| Added Z_ImportStocks.php; Database transactions in ConnectDB_mysql.inc and ConnectDB_mysqli.inc; |  | Matt Taylor | 2008-11-11 |  |  |
| Change UI of GLJournal.php, and add javascript checking |  | Tim/Moxx Consulting | 2008-11-11 |  |  |
| Correct LineItem Due Date is incorrect in SelectOrderItems.php |  | Ashish Shukla | 2008-11-10 |  |  |
| DeliveryDetails.php - Fix to Correctly bring forward Deliverto name |  | Tim | 2008-11-10 |  |  |
| Create WorkOrderReceive() function in api |  | Tim | 2008-11-10 |  |  |
| Improve error checking in WorkOrderIssue() function |  | Tim | 2008-11-07 |  |  |
| typo mods to UI to make it easier to use, adjustments to PDFGrn.php |  | Murray | 2008-11-05 |  |  |
| PO_Items.php - Change to prevent no stock form being repeated |  | Tim | 2008-11-04 |  |  |
| Alter api function to return work order number when work order first inserted |  | Tim | 2008-11-04 |  |  |
| GetConfig.php - Fix to ensure Default price list is read as a string |  | Tim | 2008-11-04 |  |  |
| Create WorkOrderIssue() function in the api. |  | Tim | 2008-11-04 |  |  |
| PDFStockCheckComparison.php - correct parsing error bug. Remove extra close bracket. |  | Mike Appolonia | 2008-11-04 |  |  |
| api function to create a works order |  | Tim | 2008-11-04 |  |  |
| Create api for purchasing data |  | Tim | 2008-11-03 |  |  |
| api_stock.php - Use sql transaction for inserting stock item |  | Tim | 2008-11-03 |  |  |
| Add api function to do stock adjustments |  | Tim | 2008-11-02 |  |  |
| api_stock.php and api_xml-rpc - Alter GetStockBalance() and GetStockReorderLevel() to return an array by location |  | Tim | 2008-11-02 |  |  |
| config.php - Correct timezone settings |  | Tim | 2008-11-02 |  |  |
| modifications to PurchData.php to allow entry of a date when the price was entered as sponsored by Ricard Andreu |  | Phil | 2008-11-02 |  |  |
| CustomerAllocations.php - Correct bug giving incorrect figure in left to allocate |  | Tim | 2008-11-01 |  |  |
| Correct bug in ModifySupplier() function |  | Tim | 2008-11-01 |  |  |
| Update index.php to include link to GL Tags |  | Tim | 2008-11-01 |  |  |
| Add new script to update or insert prices based on standard/WA costs or preferred supplier costs for stock category/currency/sales type of the users selection - work sponsored by Ricard Andreu |  | Phil | 2008-11-01 |  |  |
| Add reorder level functions to the api |  | Tim | 2008-10-31 |  |  |
| added back the reports directory under companies/weberp - required for StockQties_cvs.php. Fixed StockQties_cvs.php for companies directory structure and trapped non-existent directory -error opening file. |  | Phil | 2008-10-30 |  |  |
| Update api for SearchSuppliers() function. |  | Tim | 2008-10-29 |  |  |
| Update api for GetSupplier() function. |  | Tim | 2008-10-29 |  |  |
| Update api for inserting and modifying suppliers |  | Tim | 2008-10-29 |  |  |
| Manual page for api function documentation |  | Tim | 2008-10-29 |  |  |
| Add api function to get tax rate used for stock item |  | Tim | 2008-10-28 |  |  |
| Add categorydescription to GetStockCategoryList() api function |  | Tim | 2008-10-25 |  |  |
| SuppCreditGRNs.php - Correct bug in Add_GRN_To_Trans() function call |  | Tim | 2008-10-25 |  |  |
| api_glaccounts.php - Add account name to account list function |  | Tim | 2008-10-25 |  |  |
| CustomerAllocations.php - Correct bug where incorrect transaction number was shown |  | Tim | 2008-10-25 |  |  |
| SelectCustomer.php - Add link to customer allocations |  | Tim | 2008-10-25 |  |  |
| CustomerAllocations.php - Correct link back to Select Customers screen. |  | Tim | 2008-10-25 |  |  |
| Correct strings in api_errorcodes.php to facilitate localisation. |  | Tim | 2008-10-25 |  |  |
| Add api functions for lookup of general ledger accounts |  | Tim | 2008-10-24 |  |  |
| Add api functions for stock categories |  | Tim | 2008-10-24 |  |  |
| api_salesorders.php - Add additional checks to modify order lines function |  | Tim | 2008-10-24 |  |  |
| PO_Items.php - Change sql to conform to mysql strict mode. |  | Tim | 2008-10-24 |  |  |
| Correction to StockLocStatus.php to take work orders in progress into consideration |  | Ashish Shukla | 2008-10-23 |  |  |
| Update api for GetCustomerBranch() function |  | Tim | 2008-10-23 |  |  |
| DeliveryDetails.php - Change sql to conform to mysql strict mode, and updates to correctly bring forward delivery details. |  | Tim | 2008-10-23 |  |  |
| api_stock.php - Add function to get stock outstanding on purchase orders |  | Tim | 2008-10-22 |  |  |
| api_stock.php - Add function to get allocated stock |  | Tim | 2008-10-22 |  |  |
| api_customers.php - Correct bug in sql checking for AutoDebtorNo |  | Tim | 2008-10-21 |  |  |
| PO_Items.php - Correctly show $rootpath in href links |  | Tim | 2008-10-21 |  |  |
| PO_Items.php - Correct bugs when updating an order and clean up code to align with coding standards |  | Tim | 2008-10-21 |  |  |
| Add function to api to retrieve a sales price |  | Tim | 2008-10-20 |  |  |
| Correct bugs in api function SetStockPrice(). |  | Tim | 2008-10-20 |  |  |
| Correct WorkOrderCosting.php |  | Ashish Shukla | 2008-10-20 |  |  |
| Ensure chartdetails is correctly updated on creation of a new GL account |  | Tim | 2008-10-19 |  |  |
| Add api function to set a sales price for a stock item |  | Tim | 2008-10-18 |  |  |
| Add api functions to query customertypes table |  | Tim | 2008-10-18 |  |  |
| Add api functions to query taxgroups table |  | Tim | 2008-10-18 |  |  |
| Add api functions to query salesman table |  | Tim | 2008-10-17 |  |  |
| Add api functions to query sales area table |  | Tim | 2008-10-17 |  |  |
| Updated manual for the current units of measure setup |  | Joe | 2008-10-16 |  |  |
| Update api_customers.php for new database field |  | Tim | 2008-10-16 |  |  |
| Update api_branches.php for new database fields |  | Tim | 2008-10-16 |  |  |
| Update database scripts for customer type. |  | Tim | 2008-10-16 |  |  |
| Remove duplicated error code in api_errorcodes.php |  | Tim | 2008-10-16 |  |  |
| CRM related: Add Customer Type to debtors, with maintenance UI |  | Murray | 2008-10-15 |  |  |
| Add api functions to query shippers table |  | Tim | 2008-10-15 |  |  |
| Add api functions to query stock location table |  | Tim | 2008-10-15 |  |  |
| Correct bug in DeliveryDetails.php for when branch name contained an apostraphe and clean up code to conform with coding requirements |  | Tim | 2008-10-15 |  |  |
| Correct sql in ModifySalesOrderLine to send orderline number |  | Tim | 2008-10-14 |  |  |
| Multiply discount percent by 100 and check that it is below 100 before insertion |  | Tim | 2008-10-14 |  |  |
| Correct ModifySalesOrderLine function to call VerifyUnitPrice() instead of VerifyQuotation() |  | Tim | 2008-10-14 |  |  |
| InsertSalesOrderLine: Multiply discount percent by 100 and check that it is below 100 before insertion |  | Tim | 2008-10-14 |  |  |
| Correct function InsertSalesOrderLine, change VerifyDiscountPercent() to VerifyNarrative(). |  | Tim | 2008-10-14 |  |  |
| Correct InsertSalesOrderLine function to call VerifyUnitPrice() instead of VerifyQuotation() |  | Tim | 2008-10-14 |  |  |
| Correct sql in select query in InsertOrderHeader function |  | Tim | 2008-10-14 |  |  |
| SalesTypes.php - Force value of default session price list as soon as first entry is made |  | Tim | 2008-10-14 |  |  |
| Fixed bug in GLAccountInquiry.php |  | Tim | 2008-10-13 |  |  |
| mods to allow append PDF function to Portrait invoices. |  | Murray | 2008-10-13 |  |  |
| Z_ImportChartOfAccounts.php - Bug fixes |  | Tim | 2008-10-12 |  |  |
| Z_ImportGLAccountGroups.php - Script for importing Account Groups |  | Tim | 2008-10-12 |  |  |
| Z_ImportGLAccountSections.php - Script for importing Account Sections |  | Tim | 2008-10-12 |  |  |
| Bug fixes in api |  | Tim | 2008-10-12 |  |  |
| New api call to create a GL account group |  | Tim | 2008-10-12 |  |  |
| New api call to create a GL account section |  | Tim | 2008-10-11 |  |  |
| Z_ImportChartOfAccounts.php - script that takes a csv file and uploads the general ledger accounts. |  | Tim | 2008-10-11 |  |  |
| New api call to create a GL account code |  | Tim | 2008-10-11 |  |  |
| Stock transfer note. |  | Tim | 2008-10-09 |  |  |
| Geocode, updated to respect map_host parameter |  | Murray | 2008-10-09 |  |  |
| Add the ability to print goods received notes to GoodsReceived.php |  | Tim | 2008-10-08 |  |  |
| SQL additions for geocode updates |  | Tim | 2008-10-08 |  |  |
| GLAccountInquiry.php - Allow selection by GL tag as well as account code |  | Tim | 2008-10-08 |  |  |
| api_customers.php - Update api to deal with automatic numbering of debtors |  | Tim | 2008-10-08 |  |  |
| Update GeocodeSetup.php to activate hyperlinks and format text for gettext |  | Tim | 2008-10-08 |  |  |
| Fix bug in CustomerBranches.php |  | Tim | 2008-10-08 |  |  |
| Correct bug in CustomerBranches.php. Missing }. |  | Tim | 2008-10-07 |  |  |
| Add geocoding and mapping (experimental) to suppliers and customers, default is disabled. Options under Setup - Configuration Settings/Geocode Setup. |  | Murray | 2008-10-07 |  |  |
| Remove vtiger integration from CustomerBranches.php |  | Tim | 2008-10-06 |  |  |
| Remove vtiger integration from SelectOrderItems.php |  | Tim | 2008-10-06 |  |  |
| Remove vtiger integration database fields |  | Tim | 2008-10-06 |  |  |
| SystemParameters - Remove vtiger integration config option. |  | Tim | 2008-10-06 |  |  |
| Change ISO to iso for xhtml compatability |  | Tim | 2008-10-06 |  |  |
| Update api to enable modifying of sales order headers and order lines. |  | Tim | 2008-10-05 |  |  |
| Update api to enable sales order lines to be inserted |  | Tim | 2008-10-05 |  |  |
| Update api to enable sales order headers to be inserted |  | Tim | 2008-10-05 |  |  |
| Correct sql scripts to show AUD to AUD exchange rate correctly |  | Tim | 2008-10-05 |  |  |
| Correct bug in call to Update_Cart_Item() function |  | Tim | 2008-10-02 |  |  |
| Change api to correct bugs in credit note transaction |  | Tim | 2008-09-30 |  |  |
| Allow ProhibitPostingsBefore variable to be outside of currently created periods |  | Tim | 2008-09-29 |  |  |
| Correct bug in GetPeriod() |  | Tim | 2008-09-29 |  |  |
| Re-write of GetPeriod() in DateFunctions.php function to eliminate chartdetails errors |  | Tim | 2008-09-29 |  |  |
| Updated index and Supplier Balance reports to provide consistant UI text labels. |  | Murray | 2008-09-29 |  |  |
| Remove redundant error from GLJournal.php, CustomerReceipt.php and Payments.php |  | Tim | 2008-09-28 |  |  |
| Correct sql bug in Suppliers.php |  | Tim | 2008-09-26 |  |  |
| Update DefineJournalClass.php, DefinePaymentClass.php and DefineReceiptClass.php to allow selection of a GL Tag. |  | Tim | 2008-09-26 |  |  |
| Update Payments.php to allow selection of a GL Tag. |  | Tim | 2008-09-26 |  |  |
| Update CustomerReceipt.php to allow selection of a GL Tag. |  | Tim | 2008-09-26 |  |  |
| Update GLJournal.php to allow selection of a GL Tag. |  | Tim | 2008-09-26 |  |  |
| Fixed bug to correctly show tax ref in Suppiers.php |  | Ashish Shukla | 2008-09-25 |  |  |
| Added links to work order status and costing from issue and receipt scripts also links to issue and receive from status and costing scripts |  | Nicholas Lee | 2008-09-25 |  |  |
| New script to create and edit GL tags. |  | Tim | 2008-09-24 |  |  |
| Add database tables to allow general ledger transaction tagging. |  | Tim | 2008-09-24 |  |  |
| Change the initial user from demo to admin, and revisethe install instrcutions. |  | Tim | 2008-09-23 |  |  |
| Check to see if user name has entries in the audittrail table, and refuse delete with a user friendly message. |  | Tim | 2008-09-23 |  |  |
| Update html header to enforce strict XHTML syntax |  | Tim | 2008-09-22 |  |  |
| Remove redundant check on prohibit journals before date. |  | Tim | 2008-09-22 |  |  |
| Correct typo in INSTALL.txt |  | Anthon Pang | 2008-09-21 |  |  |
| Z_ImportPartCodes.php to allow mass import of part codes from a csv file |  | Tim | 2008-09-18 |  |  |
| Array of error descriptions and bug fix to api |  | Tim | 2008-09-18 |  |  |
| Modified BOM.php, SelectProduct.php Stocks.php StockCategories.php WorkOrderIssues.php WorkOrderReceipts.php These changes allow entry of labour type stock categories - the stock categories script now allows selection of a profit and loss account for the recovery account (was the stock account). Items created (in Stocks.php) must be service/labour items - these can then be added to BOMs in the BOM.php script. Issues of such components will credit the recovery account and debit the WIP account. These items can also be set to auto-issue on receipt of finished items from work orders. |  | Phil | 2008-09-13 |  |  |
| SelectProduct.php now displays just the default price in the local currency. Displaying all prices could result in overload on the screen - without showing specific debtors/branches/currencies the other prices are meaningless anyway. |  | Phil | 2008-09-13 |  |  |
| update SelectOrderItems UI to provide more helpful text labels. Update index.php to provide more consistant UI text labels for Bank Account options. |  | Murray | 2008-09-12 |  |  |
| Correct bug in PDFAgedDebtorsPageHeader.inc when salesman code is selected |  | Andres Amaya | 2008-09-11 |  |  |
| Add option in StockTransfers.php to search on part code or description |  | Tim | 2008-09-11 |  |  |
| Add field for suppliers tax reference |  | Tim | 2008-09-10 |  |  |
| Insert link back to SelectProduct.php on successful insert of new strock item |  | KStan | 2008-09-10 |  |  |
| Fixed colour scheme problem |  | Tim | 2008-09-09 |  |  |
| Fixed bug in PDFStockLocTransferHeader.inc for translation error, and improved layout. |  | BjÃ¶rn Paulsen | 2008-09-09 |  |  |
| Fixed bug in AuditTrauil.php where the field value contains a comma, as per Ashish Shukla |  | Ashish Shukla | 2008-09-09 |  |  |
| Correct typo in Payments.php |  | Thomas Pulina | 2008-09-09 |  |  |
| Correct bug in MiscFunctions.php for when default currency is not in the ECB list |  | Tim | 2008-09-09 |  |  |
| Change to show company logo, and to ensure the sourceforge logo is correctly shown when using https and IE |  | Nichlas Lee | 2008-09-08 |  |  |
| Corrected Add_SerialItems.php to show correct path to StockSerialItemResearch.php |  | Tim | 2008-09-08 |  |  |
| Updated weberp-new.sql and weberp-demo.sql to create initial periods. |  | Tim | 2008-09-08 |  |  |
| Correct bug in FormMaker.php that prevented images being shown in forms. |  | Tim | 2008-09-07 |  |  |
| Correct stock location problem in PO_Header.php. |  | Ashish Shukla | 2008-09-07 |  |  |
| Change Z_ChangeStockCode.php to alter the stockitemproperties table. |  | Tim | 2008-09-07 |  |  |
| Modified SelectProducts and PO Header to allow quick link to PO from Inventory Item |  | Thomas Pulina | 2008-08-27 |  |  |
| Correctly show controls for Category properties |  | Andres Amaya | 2008-08-15 |  |  |
| Modifications and improvements to Quotation PDF form |  | Arnold Ligtvoet | 2008-08-12 |  |  |
| Fixed non-gettext strings in customers.php and customerbranches.php |  | Harald Ringehahn | 2008-07-21 |  |  |
| Changed RecurringSalesOrders.php and RecurringSalesOrdersProcess.php to use GetNextTransNo(). |  | Stuart Sheldon | 2008-07-19 |  |  |
| Update api to enable posting of simple invoices and credits |  | Tim | 2008-07-09 |  |  |
| update Tax PDF report, sort by date, add missing headings to columns. |  | Murray | 2008-07-09 |  |  |
| Correct date checking bug in api_stock.php |  | Tim | 2008-07-08 |  |  |
| Correct MiscFunctions.php for divide by zero errors in Currency function. |  | Tim | 2008-07-07 |  |  |
| Fixed bug in PaymentTerms.php that prevented addition of new terms because "day number must exist" |  | Phil | 2008-07-04 |  |  |
| Z_SalesIntegrityCheck.php string missed gettextification and single quote space fixed. This script extensively edited to conform to webERP coding conventions - single quotes, { at end of conditional statements not next line, SQL broken up on keywords, gettext strings not including trailing spaces. |  | Harald Ringehahn | 2008-06-29 |  |  |
| Provide option to create a new supplier from the select supplier screen |  | Tim | 2008-07-01 |  |  |
| Add api functionality for stock items enabling stock balances to be retrieved by a remote application |  | Tim | 2008-06-30 |  |  |
| Z_SalesIntegrityCheck.php string missed gettextification and single quote space fixed. This script extensively edited to conform to webERP coding conventions - single quotes, { at end of conditional statements not next line, SQL broken up on keywords, gettext strings not including trailing spaces. |  | Harald Ringehahn | 2008-06-29 |  |  |
| Add api functionality for stock items, allowing stock items to be created, modified, retrieved and searched from a remote application |  | Tim | 2008-06-28 |  |  |
| As reworked to latest version multiple customer contacts faciltiy on customer.php |  | Mox Consulting | 2008-06-28 |  |  |

## [v3.09] - 2008-06-27

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Now checks qoh of autoissue components on receiving items against a work order when CheckNegativeStock is enabled |  | Phil | 2008-06-27 |  |  |
| Typo changes to Payments.php to simplify supplier receipts. |  | Murray | 2008-06-24 |  |  |
| Add systype 40 for work orders. |  | Tim | 2008-06-21 |  |  |
| SelectOrderItems. Changed too show carriage returns correctly in line narrative |  | Tim | 2008-06-18 |  |  |
| Correct default options on Stocks.php |  | Tim | 2008-06-17 |  |  |
| Added first version SystemCheck.php |  | Murray | 2008-06-17 |  |  |
| Correct CustomerBranches.php to enable branch deletion |  | Tim | 2008-06-13 |  |  |
| Correct corrupt messages.mo |  | Ap Muthu | 2008-06-13 |  |  |
| Correct missing table in weberp-demo.sql |  | Ap Muthu | 2008-06-13 |  |  |
| Correct typos in weberp-new.sql,weberp-demo.sql and GetConfig.php |  | Ap Muthu | 2008-06-13 |  |  |
| minor changes to text labels of UI. |  | Murray | 2008-06-12 |  |  |
| DeliveryDetails.php - Correct delivery address details and bug not allowing branch info to be returned. |  | Tim | 2008-06-08 |  |  |
| PaymentMethods.php - Improve error handling.. |  | Tim | 2008-05-26 |  |  |
| PaymentTerms.php - Improve error handling.. |  | Tim | 2008-05-26 |  |  |
| SalesPeople.php - Improve error handling.. |  | Tim | 2008-05-26 |  |  |
| SalesTypes.php - Improve error handling.. |  | Tim | 2008-05-26 |  |  |
| Shippers.php - Improve error handling.. |  | Tim | 2008-05-26 |  |  |
| api_branches and api_errorcodes. Improvements to api. |  | Tim | 2008-05-26 |  |  |
| Update to only change the used language when the users own profile is being changed. |  | Tim | 2008-05-22 |  |  |
| Fix bug preventing page goto from working |  | Tim | 2008-05-08 |  |  |
| Correctly show line breaks in Long Description |  | Tim | 2008-05-08 |  |  |
| Correct the display of the company name in CustomerReceipt.php when special characters have been used. |  | Tim | 2008-05-05 |  |  |
| Correct bug in CustomerReceipt.php where batch narrative text was lost, and add in a cancel button for when customer is wrongly selected. |  | Tim | 2008-05-05 |  |  |
| Fixed bug in CustomerAllocations that corrupted table when reallocated amount equalled originally allocated amount |  | Tim | 2008-05-03 |  |  |
| Fixed bug in DeliveryDetails.php, to correctly show names and addresses with apostropges. |  | Tim | 2008-04-30 |  |  |
| Fixed bug in CustomerAllocations, debug info left in from previous fix. |  | Tim | 2008-04-29 |  |  |
| Fixed bug that showed date of GL entries twice on GLTransInquiry.php and munted format of table - attempted to put formating of the script back to webERP conventions |  | Phil | 2008-04-28 |  |  |
| Add labels to CustomerInquiry.php links Allocation /View GL Entries etc |  | Muz | 2008-04-28 |  |  |
| Add api for customer branches. |  | Tim | 2008-04-27 |  |  |
| Put all api error codes in one file. |  | Tim | 2008-04-27 |  |  |
| Correct path to preview icon in Customerinquiry.php |  | Muz | 2008-04-26 |  |  |
| Update Credit_invoice.php to correctly mark the credit note as settled |  | Tim | 2008-04-26 |  |  |
| Improvements to api. |  | Tim | 2008-04-20 |  |  |
| Improvements to api. |  | Tim | 2008-04-18 |  |  |
| Correcting problem in displaying old allocations. |  | Tim | 2008-04-16 |  |  |
| Updated Customers.php and CustomerBranches.php to correctly show branch details when escaped characters are used. |  | Tim | 2008-04-16 |  |  |
| Change to PrintCustTrans.php to take into account pdf attachments. |  | Tim | 2008-04-16 |  |  |
| Add currency searches and other improvements to api |  | Tim | 2008-04-14 |  |  |
| Added xml-rpc and php interfaces to the api. |  | Tim | 2008-04-13 |  |  |
| Correction for CustomerAllocations.php to correctly do part allocations. |  | Tim | 2008-04-12 |  |  |
| Update fpdf_tpl.php to the latest version. |  | Tim | 2008-04-12 |  |  |
| Correct bug in SupplierInvoice.php preventing another supplier invoice being entered directly. |  | Tim | 2008-04-12 |  |  |
| Add file fpdi/fpdf_tpl.php required for attaching pdf files to invoices |  | Tim | 2008-04-10 |  |  |
| ConnectDB_mysql.inc and other db abrastraction scripts used htmlspecialchars instead of htmlentities to prevent XSS attacks inside DB_escape_string function - this leaves accented characters alone and doesn't disturb translations. Bug spotted as a result of Umlauts on command buttons failing to work. |  | Harald Ringehahn | 2008-04-10 |  |  |
| Correct DB_last_insert_id bug in ConnectDB_mysql.inc and ConnectDB_mysqli.inc |  | Tim | 2008-04-08 |  |  |
| Changes to make options clearer |  | Muz | 2008-04-06 |  |  |
| Test whether StockID is set before seeing if part already exists. |  | Tim | 2008-04-06 |  |  |
| Update currency exchange rates when default currency is changed. |  | Tim | 2008-04-06 |  |  |
| Bring in the xmlrpc libraries. |  | Tim | 2008-03-30 |  |  |
| Improve error messaging in ConnectDB.inc and ConnectDB_mysql.inc and ConnectDB_mysqli.inc |  | Mo | 2008-03-29 |  |  |
| Improvements to Z_CheckDebtorsControl.php |  | Renier | 2008-03-29 |  |  |
| Added Z_SalesIntegrityCheck.php |  | Renier | 2008-03-29 |  |  |
| Improved layout and added drill down to GLTansInquiry.php |  | Renier | 2008-03-29 |  |  |
| Correct behaviour of quick entry items. |  | Tim | 2008-03-29 |  |  |
| CustomerBranches.php Correct default delivery days field |  | Tim | 2008-03-25 |  |  |
| SupplierInvoice.php Move unsetting of session variables, to show correct message per emdeex |  | Tim | 2008-03-25 |  |  |
| Altered comments on api_customers.php |  | Tim | 2008-03-25 |  |  |
| Added first draft of api_customers.php |  | Tim | 2008-03-24 |  |  |
| Removed the default charset from the database definitions. |  | Tim | 2008-03-22 |  |  |
| Altered the Connect_DB scripts to correctly show quotes in DB_escape_string. |  | Tim | 2008-03-22 |  |  |
| Completed sweep of code to remove unnecessay DB_escape_strings, remove unneceassry Class="tableheader" statements, and put the row colouring into the css file. |  | Tim | 2008-03-22 |  |  |
| to side step SQL injection and cross scripting attacks modified session.inc to DB_escape_string for all elements of $_POST and $_GET - the DB_escape_string functions are now redundant inside scripts so started to remove these in a sweep of all code. |  | Tim | 2008-03-12 |  |  |
| Currencies.php now has link to show a graph of recent exchange rate fluctations and spiced up with a flag of the appropriate country of the currency. |  | Renier DuPlessis/Phil | 2008-03-11 |  |  |
| complete sweep of the code to replace all occurrences of &lt;td class='tableheader'&gt; with &lt;tr&gt; all hard coded odd and even line colours now use a `<td class="OddRows">` and a `<td class="EvenRows">` from the theme's css - various scripts now have fields highlighted showing the field where and input error was detected - stocks.php suppliers.php, customers.php etc. |  | Tim | 2008-03-10 |  |  |
| Reworked CustomerAllocations.php - typos and tidy up |  | Renier DuPlessis | 2008-03-10 |  |  |
| New config variable SystemParameters.php session.inc modifications to allow exchange rates to be updated daily from the European Central Bank on the first user login of the day. The manual option is still available for those who cover exchange rate risk. Updates to the manual added a section on currency maintenance. |  | Phil | 2008-03-01 |  |  |
| Currencies.php MiscFunctions.php now has a function to get exchange rates from ecb (European Central Bank) published daily as XML file that is parsed to retrieve the current day's spot rates are shown against the current currencies table rates. |  | Phil | 2008-02-29 |  |  |
| Modified the link to the manual in header.inc such that for locales other than en_?? the manual is sought under locale/xx_XX/LC_MESSAGES/Manual/ManualContents.php - Harald's is the first translation we have of the manual into German and it can now be added to the German translation archive. |  | Phil/Harald Ringehahn | 2008-02-25 |  |  |
| BankAccounts.php Payments.php and CustomerReceipt.php DefinePaymentClass DefinedReceiptClass BankReconciliation.php all modified to allow for multi-currency bank accounts ) Manual updates to Bank Accounts and Bank Payments. |  | Phil | 2008-02-16 |  |  |
| Fix problem in SelectOrderItems.php re keywords appearing wrongly |  | Tim | 2008-02-01 |  |  |
| Fix problem with call to javascript function in Stocks.php |  | Tim | 2008-01-23 |  |  |
| Improve Selectproduct.php to show more information about product state. |  | Tim | 2008-01-23 |  |  |
| Change Customers.php and Suppliers.php to accept apostrophes and accented characters in addresses. |  | Tim | 2008-01-15 |  |  |
| BOM Cost rollup updates to force cost rollup on insertions and deletions. |  | Tim | 2008-01-15 |  |  |
| Change last insert functions to work with audit trail. |  | Tim | 2008-01-15 |  |  |
| Sales Analysis Manual examples and some rework for Creating a New System |  | Phil | 2008-01-13 |  |  |
| Correct bug in Date functions. |  | Tim | 2008-01-12 |  |  |
| Sales orders now obtain next transaction number from GetNextTransNo function changed in DeliveryDetails.php |  | Tim | 2008-11-12 |  |  |
| New field in stockmaster for perishable available if the item is controlled - changes to Stocks.php to accomodate |  | Tim | 2008-11-12 |  |  |
| Lock tables to avoid concurrency problems when getting next transaction numbers as reported on the list |  | Tim | 2008-01-10 |  |  |
| New Factor company maintenance and ability to set up factor comapnies against suppliers |  | Tim | 2008-01-01 |  |  |
| Improvements to Inventory valuation report bringing in the units identifier |  | Damian Lee | 2007-11-17 |  |  |
| AuditTrail.php script added with changes in DB_query to add records to the audit trail for all inserts, updates and deletes. The Audit Trail script allows the user to inquire on this data for a range of dates and tables being modified - with field names parsed into nice screen output. |  | Tim | 2007-11-13 |  |  |

## v3.08 - 2007-11-07

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| mysqli support new/(i)mporved mysql db functions for mysql >4.1 - can now have $dbtype='mysqli'; in config.php provided appropriate mysqli library available to php |  | Peter Moulding | 2007-11-03 |  |  |
| GLBudgets.php added to allow development/review and entry of GL budgets. |  | Tim | 2007-11-03 |  |  |
| Witih certain versions of PHP or PEAR in debug mode I have experienced the following error notices: "Only variable references should be returned by reference" I recommend the atached FIX for htmlMimeMail.php |  | Renier du Plessis | 2007-11-03 |  |  |
| SelectOrderItems.php now allows entry of line items by entry of a quantity in the item browse mode |  | Chris | 2007-10-24 |  |  |
| DateFunctions.inc GetPeriod function now checks for date format of ProhibitPostingsBefore and sets up a default of one year ago if a null date |  | ? | 2007-10-24 |  |  |
| DefineCartClass - now defaults poline='' and itemdue = '' for credit notes |  | ? | 2007-10-09 |  |  |
| Bug fixes in Freight cost calculation. |  | Tim | 2007-10-07 |  |  |
| Amend table headers in OrderDetails.php |  | Tim | 2007-10-05 |  |  |
| Bug fix in PDFOrdersInvoiced.php |  | Bjorn Paulsen | 2007-10-05 |  |  |
| Remove CartClass references |  | Tim | 2007-10-05 |  |  |
| include errors in WorkOrderReceive.php |  | Tim | 2007-10-01 |  |  |
| Correct calculation of costs for GL entries |  | Tim | 2007-09-29 |  |  |
| Show additional item properties in Select Item mode |  | Anthony C | 2007-09-25 |  |  |
| Update javascript for product category changes |  | Jan Hendrik Rust | 2007-09-25 |  |  |
| SystemParameters.php now has new parameter to ProhibitNegativeStocks - new config added to DB. |  | ? | 2007-09-25 |  |  |
| ConfirmDispatch_Invoice.php now checks stock balances after the order invoiced before processing it and reports back nasty message if stock will go negative. |  | ? | 2007-09-25 |  |  |
| GoodsReceived.php now checks stock balances after the receipt of stock is processed for negative receipts of stock and it reports back a nasty message if stock will go negative. |  | ? | 2007-09-25 |  |  |
| StockAdjustment.php now checks the stock balance after the adjustment to see if it would go negative, if so it reports back a nasty message and prevents the adjustment. |  | ? | 2007-09-25 |  |  |
| WorkOrderIssue.php now checks the stock balance after the issue to see if it would go negative, if so it reports back a nasty message and prevents the issue. |  | ? | 2007-09-25 |  |  |
| Correct date conversion for statements with only settled items on. |  | Tim | 2007-09-21 |  |  |
| Changes to SystemParameters.php for Y-M-D date format |  | Tim | 2007-09-20 |  |  |
| DateFunctions.inc now allows Date Format option Y-m-d as required by Scandanavian countries |  | Tim | 2007-09-20 |  |  |
| Alter sql for serial item issues, and sql bug fixes |  | Tim | 2007-09-14 |  |  |
| Correct bugs in the issuing of serialised components |  | Tim | 2007-09-13 |  |  |
| report_runner.php MailSalesReport.php and MailSalesCSV.php now added DatabaseName variable |  | ? | 2007-09-12 |  |  |
| Various bug fixes in SelectOrderItems.php |  | Tim | 2007-09-11 |  |  |
| Bug fixes in SelectOrderItems.php |  | Tim | 2007-09-10 |  |  |
| Update Stocks.php for javascript update of stock category changes |  | Tim | 2007-09-08 |  |  |
| Update Z_poAdmin.php for new options |  | Tim | 2007-09-07 |  |  |
| Improvements to translation scripts |  | Tim | 2007-09-07 |  |  |
| Remove non ascii characters in OrderDetails.php |  | Tim | 2007-09-06 |  |  |
| Corrections to language scripts |  | Tim | 2007-08-30 |  |  |
| Fix HTML error in PO_PDFPurchOrder.php |  | sunshine33777 | 2007-08-30 |  |  |
| Correct javascript in login.php |  | Tim | 2007-08-27 |  |  |
| Replace corrupted .mo file |  | Tim | 2007-08-25 |  |  |
| Correct sql in SupplierInvoice.php to agree with ansi standard |  | Tim | 2007-08-25 |  |  |
| Bug fix in SalesTypes.php |  | Tim | 2007-08-23 |  |  |
| If default price list doesnt exist, then update config for it |  | Tim | 2007-08-23 |  |  |
| Modify Customer.php to add parameter to determine if customer PO lines are collected during quick entry of sales oreders |  | Mo Kelly | 2007-08-23 |  |  |
| Modify DefineCartClass.php to incule PO line data and line item due dates. |  | Mo Kelly | 2007-08-23 |  |  |
| Modify SelectOrderItems.php to allow the entry of due dates and corresponding customer PO Line by sales order line if parameter is set in the debtormaster to collect this field. Also to caluculate and use default delivery date by line item. |  | Mo Kelly | 2007-08-23 |  |  |
| Modify SelectOrderItems_IntoCart.inc to include PO line data and line item due dates |  | Mo Kelly | 2007-08-23 |  |  |
| Modify PDFDIFOT.php to calculate on time deliveries using the line item due date when available |  | Mo Kelly | 2007-08-23 |  |  |

## v3.07.1 - 2007-08-13

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| StockCostUpdate.php added input type=hidden MaterialCost to allow MaterialCost for Manufacturing items to be correctly defaulted on update of labour and overhead costs |  | ? | 2007-08-13 |  |  |
| StockCategories.php moved the submit enter button outside of the code for if a category is selected. Button was not shown and prevented adding a new stock category. A show stopper bug! |  | ? | 2007-08-13 |  |  |

## v3.07 - 2007-08-02

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| StockCostUpdate.php changed function call to match function in SQL_CommonFunctions.inc for GL update and the cost roll up parent changed to Parent |  | ? | 2007-08-08 |  |  |
| Added new tables stockcatproperties and stockitemproperties and modified Stocks.php and StockCategories.php to allow setup of category properties. These properties create input options against the stock item in Stocks.php for recording specific information relating to the category of items. |  | ? | 2007-08-05 |  |  |

## v3.06 - 2007-08-02

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|


## v3.06RC3 - 2007-07-29

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Amend SelectWorkOrder.php to allow work order status inquiry. |  | Tim | 2007-07-29 |  |  |
| Create WorkOrderStatus.php. |  | Tim | 2007-07-29 |  |  |
| Alter GLProfit_Loss.php to show headings correctly. |  | Tim | 2007-07-27 |  |  |
| Correction to sql/SQL_CommonFunctions.inc to move stock rollup functions into inc file, and show gl transactions when bom structure alters material cost |  | Tim | 2007-07-26 |  |  |
| Correct syntax of sql in WorkOrderReceive.php. |  | Tim | 2007-07-25 |  |  |
| Correct BOMs.php and StockCostUpdate.php to correctly show gl transactions, on Standard Cost roll up. |  | Tim | 2007-07-25 |  |  |
| Change WorkOrderCosting.php so that requirements are shown correctly. |  | Tim | 2007-07-25 |  |  |
| Change WorkOrderReceive.php to enable a works order to be viewed when previous works order with sames product has been closed. |  | Tim | 2007-07-25 |  |  |
| GetStockImage.php changed $filepath = $_SESSION['part_pics_dir'] . $pathsep; |  | Lindsay | 2007-07-23 |  |  |
| salesmancode selections removed from wwww_users.php |  | ? | 2007-07-23 |  |  |
| Alter GLTrialBalance.php to show headings correctly. |  | Tim | 2007-07-22 |  |  |
| Addition to BOMs.php to roll up standard material costs into the parent items when a BOM structure is altered. |  | Tim | 2007-07-19 |  |  |
| Addition to StockCostUpdate.php to roll up standard material costs into the parent items when a material cost is altered. |  | Tim | 2007-07-19 |  |  |
| No StockID being selected in SelectProduct.php |  | Tim | 2007-07-19 |  |  |

## v3.06RC2 - 2007-07-18

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Correct error in SelectProduct.php, and sql/upgrade3.05-3.06.sql preventing sales price from being displayed. |  | Tim | 2007-07-18 |  |  |
| Correct includes error in OutstandinfGRGs.php, StockCheck.php, SupplierBalsAtPeriodEnd.php, and Tax.php |  | Tim | 2007-07-15 |  |  |
| Correct bug in SystemParameters.php preventing config updates in. |  | Tim | 2007-07-14 |  |  |
| PriceLists and several other pdf reports failed to include session.inc in the script |  | ? | 2007-06-23 |  |  |
| $$ error in PO_DetailItems.php |  | Lindsay Harris | 2007-06-23 |  |  |
| PurchData added 3 dp for the suppliers price - we don't currently have settings for dp by currency which would be good. |  | ? | 2007-06-23 |  |  |
| Added column in WOIssues.php to show the quantity already issued against requried quantities |  | ? | 2007-06-23 |  |  |

## v3.06RC1 - 2007-06-20

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| WorkOrderCosting.php added - shows variances by item with option to close the work order and take variances to GL (if integration to Stock enabled) |  | ? | 2007-06-18 |  |  |
| SelectProduct.php and StockStatus.php now shows demand including works order component requirements and also the quantity on open work orders yet to be received |  | ? | 2007-06-17 |  |  |
| WorkOrderIssues.php added to CVS - sponsored manufacturing work by Tariq Farooqi and Lindsay Harris |  | ? | 2007-06-13 |  |  |
| BOMs script re-worked matrice and arbore variables to create multi-dimensional array directly with english variable names. |  | ? | 2007-06-12 |  |  |
| Locations script now checks for bom records referring to the location before allowing deletion |  | ? | 2007-06-12 |  |  |
| Stocks.php script now deletes prices for the item before deleting the item itself. |  | ? | 2007-06-12 |  |  |
| Added WorkOrderEntry.php with new work order tables to add work orders with multiple outputs. Can change quantity required and required by dates. Also added SelectWorkOrder.php to allow selection and modification of a work order - from the work order number or by item, location and status open or closed. Sponsored by Lindsay Harris and Tariq |  | ? | 2007-06-02 |  |  |
| GLTrialBalance and GLProfit_Loss now use the parentgroup heirarchical account groups with nested sub-groups of accounts |  | ? | 2007-05-27 |  |  |
| SilverWolf theme added |  | Rob Wolf | 2007-05-20 |  |  |
| SelectProduct.php now shows summary data about the item selected total qty on hand, ordered, purchasing data, unit of measure etc. |  | ? | 2007-05-20 |  |  |
| New salesman login option that allows selection of a salesman - these logins are restricted to selecting only customers who have branches allocated to the selected salesman. |  | ? | 2007-05-20 |  |  |
| BOM now shows quantity on hand of components - sponsored by Manny Neri Dutch Precision |  | ? | 2007-05-20 |  |  |
| includes/Login.php checks for magic_quotes_gpc and issues a warning if they are enabled. |  | Wayne McDougall | 2007-05-07 |  |  |
| Updated installation instructions to recommend magic quotes off. Added .htaccess file for the main directory to default magic quotes for those web-servers that support .htaccess files. |  | ? | 2007-05-03 |  |  |
| footer.inc / InventoryPlanning.php use strftime to get locale specific dates rather than gettext translation |  | Wayne McDougall | 2007-05-06 |  |  |
| footer.inc nows uses phpgettext for date/time translation, and the webserver locale date/time if no translation is available |  | Wayne McDougall | 2007-05-01 |  |  |
| Use DefaultDateFormat in all PDF report headings |  | Wayne McDougall | 2007-05-01 |  |  |
| InventoryPlanning.php now supports translation of abbreviated month names in the page headers |  | Wayne McDougall | 2007-05-01 |  |  |
| StockStatus.php now displays a summarised sales pricing history for the selected customer (if any). When called from SelectOrderItems.php, shows the sales pricing history for the customer whose order is being entered. |  | Wayne McDougall | 2007-04-30 |  |  |
| StockLocTransferReceive.php now has an option to delete the balance of a transfer if the quantity received is less than the amount recorded as dispatched. |  | ? | 2007-04-30 |  |  |
| Portrait invoice now shows serial numbers/lot numbers and qty for controlled items sold. Work sponsored by Tip Top Tips Sarl |  | ? | 2007-04-30 |  |  |
| CustomerReceipt fixed bug where a GL receipt and a customer negative receipt (or vice versa) resulted in a zero batch that did not have the correct GL entries created for it - result TB out of balance and debtors control out of balance with list of balances. |  | ? | 2007-04-26 |  |  |
| Using the new Special Instructions field held against the branch record - CustomerBranches now allows entry and editing of the field. DeliveryDetails shows the messasge as does SelectOrderItems. Credit message only appears once now instead of for each line where the credit limit is exceeded. |  | Wayne McDougall | 2007-04-26 |  |  |
| CustomerBranches.php - removed ? syntax for comparison and assignment of GET values |  | ? | 2007-02-20 |  |  |
| Took out the default credit limit when a 0 credit limit is entered. If the user enters 0 then a zero credit limit is set. |  | ? | 2007-02-20 |  |  |
| ShipmentCosting bug with multiple lines of the same item on the shipment (albethey different POs) caused incorrect costings. |  | ? | 2007-02-20 |  |  |
| Optimised GLPostings.inc for postgres - thinks mysql performance will be even better too - sorting postings by account and doing one post for each period/account combination |  | Danie | 2007-02-15 |  |  |
| SelectSalesOrder.php fails with postgres printpackingslip needs to be in the group by now fixed |  | ? | 2007-02-12 |  |  |
| Tax Categories now uses a cross join to be more ansi sql friendly - failed with postgres otherwise |  | ? | 2007-02-12 |  |  |
| OrderDetails.php is not honouring the Landscape/Portrait config switch for invoice printing - now fixed. |  | Wayne McDougall | 2007-02-12 |  |  |
| Locations.php was checking for existence of worksorders in a non-existant table before allowing deletion! |  | Errol Livingston | 2007-02-12 |  |  |
| Bug fix on width of terms in PrintCustTransPortrait.php |  | Wayne McDougall | 2007-02-09 |  |  |
| PaymentAllocations.php and modifications to SupplierInquiry to allow viewing of where a payment made was allocated. |  | emdeex | 2007-02-06 |  |  |
| Taken out references to session_register in several files - historical code no longer necessary implicit in $_SESSION |  | Ditesh | 2007-02-06 |  |  |
| Bug fix in Z_CreateNewCompany.php the checkbox to create the new DB was incorrectly coded and would not have worked. Also SystemParameters.php the WikiPath variable was defined twice - this should not have caused issues in practise. |  | Wayne McDougall | 2007-02-03 |  |  |
| AccountGroups.php now allows hierarchical account groups GLTrialBalance.php now shows account groups within the new hierachy. |  | ? | 2007-01-30 |  |  |
| SpecialOrder.php now checks for customer hold reasons to ensure the customer is not on hold prior to placing the order so there is a warning if there is some credit issues |  | ? | 2007-01-18 |  |  |
| InventoryValuation.php now shows the quantitiy total for each product group which may or may not be meaningful |  | ? | 2007-01-18 |  |  |
| Added page offset buttons at the bottom of SelectSupplier SelectCustomer SelectProduct and added an account code to the GLTransInquiry script |  | Gilles Deacur | 2007-01-16 |  |  |

## [v3.05] - 2007-01-02

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Got webERP logo showing on footer.inc in report writer scripts. Updated copyright to change year on title. |  | ? | 2007-01-02 |  |  |
| Fixed bad bug that didn't post freight on Credit_Invoice.php script!!! This resulted in control account out of balance issues. |  | ? | 2007-01-02 |  |  |
| Added upgrade script that applies DB changes and does the data conversions for the user - now just have to run the script. |  | ? | 2007-01-02 |  |  |
| DB_connect_mysql and DB_connect_psql added a DB_show_tables function to abstract this one in the report writer. |  | ? | 2006-11-30 |  |  |
| BOMs.php removed dependency on mysql num rows and mysl_fetch_array functions and used abstraction functions as per the rest of the application. Modifications to consistency of braces use of prnMsg. Bug fixes as per Till |  | ? | 2006-11-29 |  |  |
| Upgrade script for 3.04-3.05 for postgres. |  | ? | 2006-11-27 |  |  |
| Now check on login that the company entered at login has a companies/directory defined to avoid logins to incorrect companies - bug noted by Alan Jones |  | ? | 2006-11-18 |  |  |
| added the directory Numbers to contain the PEAR module Numbers_to_Words that is a multi-language class. There is one modification required to avoid the dependency on PEAR's raiseerror function - this now refers to the webERP prnMsg function - otherwise updates to this module can be imported as necessary. Needs some testing with other Locales to check translation. It is only used in the PrintChecks.php script. |  | ? | 2006-10-24 |  |  |
| includes/LanguageSetup.php now uses the path prefix for the bindtextdomain statement\ - multi-lang would not have worked with report writer scripts |  | ? | 2006-09-27 |  |  |
| Shipt_Select.php now modified SQL per bug report `<>` doesn't work on integers with postgres. |  | ? | 2006-09-27 |  |  |
| PO_Header.php now have a button to update the delivery address based on the selection of the stock location |  | ? | 2006-09-17 |  |  |
| ShipmentCosting.php - added code to deal with weighted average costing - similar to SupplierInvoice.php below. Also now checks when closing to make sure that all the purchase order line stock receipts are invoiced before allowing to be closed. Also completes the purchorderdetails and updates the purchase order line quantity to be the quantity received to avoid the possibility of receiving additional stock against this line |  | ? | 2006-09-17 |  |  |
| SupplierInvoice.php GoodsReceived.php added a field to grns table to allow the standard cost at the time of receiving to be recorded in the GRN record rather than the purchorderdetails table - this allows for multiple receipts against a purchase order to be at different costs - required for average costing. SupplierInvoice.php now has code to deal with average costing GL entries and cost updates. Also deals with where the invoice is for a quantity more than is left in stock now - writes off the variance on the shortfall. GoodsReceived now tracks the weighted average of all receipts of a purchase order line so that the quantity received multiplied by the stdcostunit = sum of all cost received against the line. |  | ? | 2006-09-16 |  |  |
| New script Z_Upgrade_3.04-3.05.php required to bring cost data over to the grn records to fit with the new schema of allowing different drops of stock received to be booked in at different costs. Previously |  | ? | 2006-09-16 |  |  |
| Polish-up of SalesTypes.php |  | Renier du Plessis | 2006-09-13 |  |  |
| DateFunctions.inc modified GetPeriod function to check for dates prior to the new ProhibitPostingsBefore parameter - postings get made in the period following this date now. Also SystemParameters.php script modified to allow selection of a period end to set ProhibitPostingsBefore |  | ? | 2006-09-11 |  |  |
| SupplierInvoice.php was not updating purchaseorderdetails quantities correctly due to errors in the supplier transaction class - the podetailitem was populated with the orderno!! Modified the SuppInvGRNs script to work with purchase orders and added the orderno to the supptrans class script |  | ? | 2006-09-07 |  |  |
| Shipments.php script modified to ensure that the shipment is created before purchorderdetails added to it |  | ? | 2006-09-07 |  |  |
| Fixed bug in SelectCreditItems.php and Credit_Invoice.php for "Write off Stock" type credits A warning is displayed: and no process button is available. |  | ? | 2006-09-06 |  |  |
| Fixed to GetStockImage.php and scripts that call it to display stock images correctly |  | ? | 2006-08-30 |  |  |
| Fixed and improved reportwriter scripts drop down list for field selection and over-ride with entry of calculated field SQL |  | Dave | 2006-08-22 |  |  |
| New BOMs.php script that shows the full context of parents and children for multi-level BOMs. |  | Dumitru Popa | 2006-08-22 |  |  |
| PO_OrderDetails.php link refered to sales orders sh/be purchase orders |  | Saras chithra | 2006-07-19 |  |  |
| ConfirmDispatch_Invoice.php updates to salesorderdetails did not work correctly for orders with multiple lines of the same item. |  | ? | 2006-07-19 |  |  |
| Z_RePostGLFromPeriod.php did not work correctly from periods into the business since bfwd balances were zeroed rather than using the previously bfwd balance - now fixed. |  | ? | 2006-07-12 |  |  |
| SupplierTransInquiry.php allows supplier transactions to be listed with GL transactions created for each. |  | ? | 2006-07-11 |  |  |
| report_runner.php now allows command line arguments to run reports using the php interpreter directly. |  | Alan J | 2006-07-11 |  |  |
| ConnectDB.inc - now allows for the DatabaseName to be set in hard code for scripts that do not require a login - in particular automated reports. |  | Alan J | 2006-07-10 |  |  |
| SpecialOrder.php was not creating multiple line orders correctly - needed to have orderlineno in SQL to add salesorderdetails records |  | ? | 2006-07-05 |  |  |
| SupplierInvoice.php made manual entry of taxes work correctly now takes values at time of processing the invoice previously they were not updated before posting |  | ? | 2006-07-04 |  |  |
| Credit_Invoice.php removed taxrate from stockmoves sql insert statement on stock write off option |  | ? | 2006-07-04 |  |  |
| Various changes |  | Jesse | 2006-06-27 |  |  |
| PDFSalesAnalysis lowercasing missed a few field names now corrected Cols ColNo etc. Various scripts added rounding |  | Steve | 2006-06-20 |  |  |
| Z_ChangeBranchCode.php fixed sql for taxauthority to taxgroupid and some typos |  | ? | 2006-06-08 |  |  |
| GLAccounts.php fixed to allow deletions of a GL account where there are no postings to it. chartdetail records also deleted. |  | ? | 2006-06-08 |  |  |
| SpecialOrder.php - Location code not inserted correctly double up of contact ... lower casing errors fixed. |  | Steve K | 2006-06-08 |  |  |
| index.php link to PDFDIFOT.php delivery in full on time report |  | ? | 2006-05-31 |  |  |
| added Steve Ks PrintCustTransPortrait.php option added new config variable and changed the SystemParameters.php script to allow the variable to be switched. Changed all scripts that call PrintCustTrans.php to look at the new session variable before printing |  | Steve K | 2006-05-31 |  |  |
| lots of changes |  | Steve K | 2006-05-30 |  |  |
| includes/ConstructSQLForUserDefinedSalesReport.inc not using 'Not Used" rather than _('Not Used') |  | Harald Ringehahn | 2006-05-16 |  |  |
| includes/PDFSalesAnalysis.inc ColNo and colNo should be colno fixed lower casing. |  | Harald Ringehahn | 2006-05-16 |  |  |
| EDIProcessOrders.php quotation marks and semi colon. PDFLowGP.php missing bracket on if. Z_DeleteInvoice.php missing = and } sh/be ]. reportwriter/WriteForm.inc was testing for numerical month numbers fixed to text for month strings. |  | Matthew Sibson | 2006-05-02 |  |  |
| includes/DBConnect_mysql.inc now uses mysql_real_escape_string rather than mysql_escape_string the former takes into account the character set in use. |  | Ditesh Kumar | 2006-04-25 |  |  |
| includes PDFTransPageHeader.inc - was printing address line 3 twice changed to print line 6 correctly |  | Suren Nadu | 2006-04-25 |  |  |
| ConfirmDispatch_Invoice.php wording of error message had superfluous "the" |  | Steve K | 2006-04-25 |  |  |
| Z_MakeNewCompany has the restriction to 10 character company codes removed and option to create DB is optional with a checkbox now. |  | Steve K | 2006-04-25 |  |  |
| SelectSupplier, SelectOrderItems SelectCustomer now have selections ordered by supplier/customer code |  | ? | 2006-04-25 |  |  |
| SalesAnalRepts.php fixed Not Used translations and comparison test to always use the non-translated Not Used and display the Not Used translation |  | Steve K | 2006-04-25 |  |  |
| SelectItemsInto Cart.in fixed case of $myrow['DiscountCategory'] |  | Steve K | 2006-04-25 |  |  |
| SelectCreditItems.php includes/DefineCartClass.inc CreditInvoice.php SelectOrderItems.php fixes for garbled line numbers |  | Steve K | 2006-04-25 |  |  |
| CustomerAllocations.php missing $_SESSION['DefaultDateFormat'] for FormatDateForSQL statement. |  | Greg Morehead | 2006-04-03 |  |  |
| SalesAnalRepts.php no VALUE in Select OPTION statement |  | Steve | 2006-04-02 |  |  |
| indexed serialno in stockserialmoves and stockserialitems for performance reasons in larger dbs |  | Jesse Peterson | 2006-03-31 |  |  |
| includes/GetSalesTransGLCodes.inc error in SQL extra comma removed |  | Harald Ringehahn | 2006-03-30 |  |  |
| StockAdjustment.php unset the Adjustment session variable on entry of an incorrect item code to avoid problems as reported by Kyle. |  | Jesse Peterson/Kyle Sasser | 2006-03-29 |  |  |
| CompanyPreferences.php now reloads session variables after an update. |  | Neil Williams | 2006-03-29 |  |  |
| GLAccounts.php fixed incorrect call to _() function inside prnMsg function call and added TABINDEX to sequence focus on tabs |  | Harald Ringehahn | 2006-03-22 |  |  |
| Reversed order of period selection to show latest periods first. |  | Ditesh Kumar | 2006-03-16 |  |  |
| footer.inc now displays time in the locale of the language selected. |  | Marcos Garcia | 2006-03-16 |  |  |
| ReportMaker.php - Fixed small warning log about $DefaultReports not existing when there are no reports |  | Danie | 2006-02-23 |  |  |
| GetSalesTransGLCodes.inc - Patched to check for area='AN', stkcat='ANY', salestype=$salestype |  | Ditesh | 2006-02-23 |  |  |
| StockCostUpdate.php - Fixed dodgy update which might corrupt with delayed posting, now check/get the cost on post |  | Danie | 2006-02-23 |  |  |
| ConfirmDispatch_Invoice.php - Fixed broken url |  | Steve | 2006-02-23 |  |  |
| Z_DataExport.php - Fixed CSV Export, to adhere closer to CSV protocol, Fixed TaxLevel to TaxCatId |  | Danie | 2006-02-22 |  |  |
| Stocks.php - Fix Part descriptions so it displays when description contains html entities |  | Danie | 2006-02-22 |  |  |
| SelectProduct.php - Time and Uppercase the StockCode and StockID fields, makes finding the product easier. |  | Danie | 2006-02-22 |  |  |
| SelectOrderItems.php - added number formating to price display |  | ? | 2006-02-22 |  |  |
| PurchData.php Trim Supplier Description as spacing sometimes mess up the reports when it ends with newlines. |  | Danie | 2006-02-22 |  |  |
| GLAccountInquiry.php Modified to show order based on Transaction Date. |  | Ditesh | 2006-02-22 |  |  |
| Credit_Invoice.php : Wrong Name Descriptor -&gt;LineNo used Chaned to ->LineNumber. |  | Steve | 2006-02-22 |  |  |
| GLAccounts.php missing opening bracket in prnMsg call - removed extra quotes in StockCategories.php Areas.php was checking for 50 character area description but field only 25 in db |  | Steve | 2006-02-03 |  |  |
| Z_Upgrade3.01-3.02.php now checks to see if its been run already and exits nicely if it has |  | ? | 2006-02-01 |  |  |
| GLJournal.php SystemParameters.php new config parameter to prohibit entering GL journals directly to control accounts. |  | ? | 2006-02-01 |  |  |
| Credit_Invoice.php - nasty bug on return of items used invoice number rather than the order number to update the salesorderdetails |  | Jesse | 2006-02-01 |  |  |
| ConfirmDispatch_Invoice.php and ConfirmDispatch_InvoiceControlled.php corrections for serial items |  | Jesse | 2006-02-01 |  |  |
| Made the wiki links more generic and possible to have other wikis link to webERP. |  | Greg Morehead | 2006-01-31 |  |  |
| Fixed messages in GLAccounts.php also added DB_escape_string to sql queries |  | Joerg Aldinger | 2006-01-28 |  |  |
| PDFOrdersInvoices.php - did not deal with multiple lines of the same item correctly - now adds these order lines together |  | Greg Morehead | 2006-01-28 |  |  |
| ConfirmDispatch_Invoice.php made quantity input field wider to accomodate larger decimal places and quantities. |  | Muz | 2006-01-28 |  |  |
| upgrade_3.01-3.02.sql didn't have a line to insert the new SO_AllowSameItemMultipleTimes variable into the config table |  | Muz | 2006-01-28 |  |  |
| session.inc now DB_escape_string() posted characters into the login text boxes |  | ? | 2006-01-25 |  |  |
| CreditItemsControlled.php moved session.inc above call to _() |  | Clay Mokus | 2006-01-22 |  |  |
| login.php required reference to the character set in the Content Type tag. |  | Finjon Kian | 2006-01-22 |  |  |
| htmlentities calls in SalesCategories.php now have reference to the character set |  | Finjon Kian | 2006-01-22 |  |  |
| phpgettext required PathPrefix correction in LagnuageSetup.php |  | Finjon Kian | 2006-01-22 |  |  |
| Bug in SalesGLPostings.php could not add or delete records freely - removed checks and added warnings. |  | ? | 2005-12-29 |  |  |
| Added links to wacko wiki site based on new config options for WackoWiki - the links don't show if integration is disabled and WikiPath from SelectProduct SelectCustomer and SelectSupplier. Requires an install of wacko wiki on the same web-server http://wackowiki.com/WackoDownload/InEnglish?v=xlv |  | ? | 2005-12-20 |  |  |
| New form creator options for use with his report builder - most files in the reportwriter subdirectory changed - new sql to allow 5 tables in the query |  | Dave | 2005-12-12 |  |  |
| New delivery performance report PDFDIFOT.php (Delivery In Full On Time - DIFOT) compares order requested delivery date against the date the invoice is prepared - assumed to be the same as the date of dispatch - the number of days that it is acceptable to dispatch product is entered at run time. |  | ? | 2005-12-12 |  |  |
| Obscure bug in GetPrice.inc - if special prices set up for a salestype/customer combo but not default price set up for the salestype then the special price is picked up for customers belonging to the sales type even though it is specific to a different customer! - now fixed |  | ? | 2005-12-09 |  |  |
| StockLocTransfers.php -now checks for positive stock in the location before allowing a transfer. |  | Danie | 2005-12-09 |  |  |
| Updated the webERP php-gettext to the latest version 1.05 |  | ? | 2005-12-06 |  |  |
| WWW_Users.php now options to set theme and language before user logs in. Previously had to be set from the user settings when the user logged in themselves now the sysadmin can change them. Changed locale setting to LC_ALL then changed the LC_NUMERIC back to en_GB. |  | Joï¿½ | 2005-12-03 |  |  |
| Report writer tool - allows adding reports on any web erp data and formating thereof provided some knowledge of the tables etc. Export to PDF, CSV - import and export of reports too. New directory for the files reportwriter as well as changes to session.inc, header.inc, config.php, footer.inc |  | Dave Premo | 2005-11-21 |  |  |
| Credit status - tidied up consistency of wording "Disallow invoices". Also used white and black in .css files instead of #FFFFFF and #000000 respectively |  | Tech Nossomy | 2005-11-16 |  |  |

## v3.04 - 2005-11-15

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fixed bug in adding new chart details in the function includes/DateFunctions.inc - GetPeriod for all chartdetails not already setup - this was not happening for periods created before the current period only for subsequent periods. |  | ? | 2005-11-15 |  |  |
| PaymentTerms.php now checks for payment terms less than 360 days following invoice 99 days was considered too restrictive. |  | Ed | 2005-11-13 |  |  |
| PrintCustTrans.php during translation couldnt translate "Tax" not in _('') function. |  | ? | 2005-11-11 |  |  |
| Increased the length of the bank account to 30 varchar was text already in pg |  | ? | 2005-11-09 |  |  |
| SelectCustomer.php SelectSupplier now have buttons to show all - with the paging the user is protected from massive downloads over dialup. |  | Briain | 2005-11-08 |  |  |
| TaxGroups.php changed to prohibit the same tax group name, automatically enter calculation orders to prevent problems with the same ordered tax authorities. Changed messages when updated and inserted tax groups to show the name of the tax group updated/inserted. Fine tuning all suggested by Steve K. |  | ? | 2005-11-07 |  |  |
| CustomerBranches.php now displays the name of the taxgroup rather than the id! |  | ? | 2005-11-07 |  |  |
| DateFunctions.inc MonthAndYearFromSQLDate fixed for locale using strftime |  | Briain | 2005-11-06 |  |  |
| Fixed CustWhereAlloc - string 'Type' not in _('') function |  | Briain | 2005-11-06 |  |  |
| Fixed SalesGraph sh/been SalesFolkResult not StockCatsResult and default $_POST['SalesmanCode'] |  | emdeex | 2005-11-06 |  |  |
| Fixed bug in SuppInvGRNs.php - nominal purchase order items could not be invoiced since the GL code was lost (it wasn't retrieved in the selectable list of outstanding GRNs) |  | ? | 2005-11-06 |  |  |
| in includes/login.php and logout.php |  | ? | 2005-11-05 |  |  |
| Reported by Ed@unixmania SupplierContacts.php extra ? sh/be an & in the URL string |  | ? | 2005-11-03 |  |  |
| Translation.txt transferred to a new section in the manual |  | ? | 2005-10-31 |  |  |
| SalesGraph.php script allows printing various sales graphs by stock category, rep, sales area, item and customer linked from index.php |  | ? | 2005-10-31 |  |  |
| Translating document depreciated and a chapter added to the manual to cover translations and the use of the online translation facilities. |  | Steve/Phil | 2005-10-30 |  |  |
| DB_escape_string() was not used in places it should be in Suppliers.php - now fixed - also checking for apostrophes was not working corrected. |  | ? | 2005-10-29 |  |  |
| Updates to ManualContents.php, DevelopmentStrucutre, Introduction and Requirements |  | Steve | 2005-10-29 |  |  |
| PO_Items.php removed the references to contracts as per Clay Mokus advice - and fixed error trapping code not to check contracts on 30/10/05 |  | ? | 2005-10-27 |  |  |
| header.inc now sends a header with the charset if headers not already sent - this will work for 90% of scripts that need to know the charset will fail for any output from ConnectDB_XXX scripts. |  | ? | 2005-10-24 |  |  |
| PDFStarter_ros.inc changed to PDFStarter.php accross the board |  | ? | 2005-10-23 |  |  |
| included the Olivier Plathey CDI font files for Chinese, Japanese and Korean - with the appropriate fonts installed with the local version of Acrobat Reader these allow pdfs to be created in the Big5 (Chinese), SJIS (Japanese) and UHC (Korean) fonts. Modifications necessary to class.pdf.php to include the appropriate files depending on the locale - eg if the locale is zh_CN - then the Chinese fonts are included and selected. |  | ? | 2005-10-23 |  |  |
| includes/DefineReceiptClass.php:38: - ($this-&gt;Items[$RcptID]-&gt;$amount + $this->Items[$RcptID]-&gt;$discount) fixed |  | Ed | 2005-10-22 |  |  |
| missing ' (quote) at the beginning of line 206 of the file StockLocTransferReceive.php |  | Jason Jaques | 2005-10-19 |  |  |
| bug in translations lost on SelectOrderItems.php and SelectCreditItems.php - actually turned out to be in includes/DefineCartClass.inc _() function defined displaced gettext class scripts. |  | Tony G | 2005-10-17 |  |  |
| fix to ConfirmDispatch_Invoice.php $_SESSION['Items']->$Orig_OrderDate = $myrow['orddate']; |  | Greg Morehead | 2005-10-11 |  |  |
| removed references to BrAddX which was not defined in the cart class and changed to DelAddX - not sure how this had worked |  | Greg Morehead | 2005-10-11 |  |  |
| addJpegFromFile - changes on 1/1/05 fixed the height and should have focused on the width - the logos are now scaled based on the widths by setting the height argument to 0. |  | Hindra Joshua | 2005-10-09 |  |  |
| Exchange rate on entering receipts was not checking the correct variable for the currencydefault - now corrected |  | ? | 2005-10-09 |  |  |

## v3.03 - 2005-10-01

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Shipments.php script class was not initiated before the session started so lost the session data each time! |  | ? | 2005-10-01 |  |  |
| freight cost in order entry not carried through to ConfirmDispatch_Invoice.php |  | ? | 2005-10-01 |  |  |
| Fixed all logo to PDFs by defaulting the last argument to resize images in proportion |  | ? | 2005-10-01 |  |  |
| Check to ensure that ex rate other than 1 entered for receipts in FX. |  | ? | 2005-10-01 |  |  |
| Re-wrote tax report to take account of new tax structure |  | ? | 2005-10-01 |  |  |
| SupplierInvoice.php and SupplierCredit.php - was not posting tax correctly some GL journals created were out of balance. |  | ? | 2005-10-01 |  |  |
| Added phplot and an example graph for the usage of stock - new script StockUsageGraph.php |  | Afan Ottenheimer/Miguel de Benito Delgado | 2005-09-29 |  |  |
| 2 additional addresses to the address fields on the databases from the current braddress1-4 structure to a braddress1-6 structure. This will allow more uniform database entry and expanded fields needed for future upgrades in shipping and reporting. |  | Dave Premo | 2005-09-25 |  |  |
| onclick confirm javascript function added to provide a double check on critical deletes and updates accross the system - stocks.php customers.php, custbranches.php, price updates, salestrans delete, selectorderitems.php cancel order - delete a line, creditiems.php delete line cancel the credit note etc |  | ? | 2005-09-17 |  |  |

## [v3.02] - 2005-09-04

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fixed bugs in ReverseGRN.php quotation marks around narrative in a few spots |  | ? | 2005-09-04 |  |  |
| Fixed bugs in GoodsReceived.php was not allowing update of quantity in a batch/lot where more of the same lot received. Thanks for pointing this out Lior |  | ? | 2005-09-04 |  |  |
| PDF Profit and Loss Account option |  | Suren Naidu | 2005-09-03 |  |  |
| Fixed to COGSGLPostings.php and SalesGLPostings.php |  | ? | 2005-09-03 |  |  |
| made Customers.php META REFESH into Branches.php after adding a new billing address to ensure that a branch is added |  | ? | 2005-09-03 |  |  |
| fixed bugs on Z_DeleteCreditNote.php and GLAccountInquiry.php |  | Glen Rice | 2005-09-03 |  |  |
| default chart of accounts changed to suit Canada/US |  | Steve | 2005-09-01 |  |  |
| TaxProvinces.php script added - to maintain the dispatch tax provinces |  | ? | 2005-09-01 |  |  |
| TaxCategories.php script added - to maintain the item levels at which taxes are charged |  | ? | 2005-08-30 |  |  |
| PDF Trial balance modifications to GLTrialBalance.php and new script includes/PDFGLTrialBalancePageHeader.inc |  | Suren Naidu | 2005-08-22 |  |  |
| SelectOrderItems.php bug in the stock code search showing the % in the stock code field after a search fixed. |  | Clay Mokus | 2005-08-22 |  |  |
| Colin bug fixes - Stocks.php - double check for existence before adding a new one, includes/Add_SerialItems.php bogus echo $sql GoodsReceivedControlled.php SuppTransGLAnal.php removed link to Contracts - not coded yet! |  | Colin | 2005-08-17 |  |  |
| option to print balance sheet as a pdf |  | Suren Naidu | 2005-08-14 |  |  |
| tidy up company login - remove session variable for incorrect company login |  | Ricardo Pedroso | 2005-08-14 |  |  |
| SystemParameters.php - now able to set part pics directory and reports directories correctly - bug in rc1 |  | ? | 2005-08-09 |  |  |
| ConfirmDispatch_Invoice.php - assembly items with multiples of a single component were not extended by the quantity in calcualting the standard cost of the assembly - fixed |  | Suren Naidu | 2005-08-08 |  |  |
| Tax changes to allow multiple taxes per line and compounding taxes on previous taxes - this now allows Canadian and some European taxes to be accomodated. Implemented for invoices, credit notes in AR and AP. New tax groups - allocated to suppliers and customer branches for the basis for the taxes that will apply to invoices in conjunction with the location where the goods/services are provided from - the dispatch tax province - on the stock location record. Tax calculations show on the transaction entry screen but only the totals show on invoices. All the transaction entry work is done - reporting still to be done. Largely as per specs worked on by Danie and Steve |  | ? | 2005-08-06 |  |  |
| Payments.php sql quotation marks error in narrative fixed |  | Suren Naidu | 2005-08-06 |  |  |
| PO_Header.php was printing existing purchase orders as at todays date rather than the date at which it was initiated - fixed |  | Tom Fox | 2005-07-23 |  |  |
| Z_PriceChanges.php lowercased to work with new db |  | ? | 2005-07-15 |  |  |
| SupplierBalsAtPeriodEnd.php was not taking into account FX diffs that had been realised after the year end. Same for DebtorBalsAtPeriodEnd.php |  | ? | 2005-07-13 |  |  |
| RecurringOrders templates set to recurr quarterly were only recurring 3 times per annum - now fixed |  | ? | 2005-07-13 |  |  |
| depending on which $dbType is set in config.php. The idea for this was prompted by the work done by EDO and ITWorx on behalf of EDO - that was contributed by EDO. |  | ? | 2005-07-10 |  |  |
| bug in includes/PDFSalesAnalysis.inc - $columnno should be $ColumnNo |  | Scott Rosa | 2005-07-07 |  |  |
| modified StockStatus.php to show bin locations of items for managed locations. Also added BinStockStatus.php |  | Chris Bice | 2005-07-03 |  |  |
| Locations.php now has flag for warehouse management |  | Chris Bice/Jake Stride | 2005-06-30 |  |  |
| postgres upgrade script for 3.01 - 3.02 and Z_Upgrade_3.01-3.02.php to renumber salesorderdetail lines as required |  | Jake Stride | 2005-06-30 |  |  |
| SupplierInvoice.php SupplierCredit.php now allow for entry of multiple taxes |  | ? | 2005-06-30 |  |  |
| GLAccountInquiry.php checks for entry of period range now |  | ? | 2005-06-30 |  |  |
| PO_PDFOrderPageHeader.inc changed "THIS IN NOT AN ORDER" to "THIS IS NOT AN ORDER" |  | Murray | 2005-06-30 |  |  |
| SupplierAllocations.php Exch Diff was not in quotes in sql statement - fixed. |  | ? | 2005-06-30 |  |  |

## [v3.01] - 2005-04-23

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| PO_Header.php Line 218: gettext function missing for 'Reset' Line 363: gettext function missing for 'Deliver to' |  | Gunnar Rikardsson | 2005-06-05 |  |  |
| PO_Items.php Line 777: gettext function missing for 'Leave blank if NOT a stock order' Line 809: gettext function for 'Enter Line' Line 359: If(isset($_POST['UpdateLine']) AND $_POST['UpdateLine']=='Update Line'){ changed to If(isset($_POST['UpdateLine'])){ Line 329: If($_POST['Delete']=='Delete'){ replaced with If($_POST['Delete']){ |  | Gunnar Rikardsson | 2005-06-05 |  |  |
| Prices_Customer.php Line 192: gettext function missing for 'All Branches' |  | Gunnar Rikardsson | 2005-06-05 |  |  |
| SalesGLPostings.php Line 167: gettext function missing for 'Discount Account' |  | Gunnar Rikardsson | 2005-06-05 |  |  |
| Stocks.php Line 293: Changed success message to standard formatting with prnMsg function for consistent "look and feel" |  | Gunnar Rikardsson | 2005-06-05 |  |  |
| PurchData.php Line 206: gettext function missing for 'Yes' Line 209: gettext function missing for 'No' |  | Gunnar Rikardsson | 2005-06-05 |  |  |
| StockCheck.php Line 333: gettext function missing for 'Show system quantity on sheets' Line 342: gettext function missing for 'Only print items with non zero quantities' |  | Gunnar Rikardsson | 2005-06-05 |  |  |
| includes/PO_PDFOrderPageHeader.inc Line 114: misspelling: Initator -> Initiator |  | Gunnar Rikardsson | 2005-06-05 |  |  |
| WorkCentres.php error in sql accountGroup sb/been accountgroup |  | ? | 2005-06-05 |  |  |
| ConfirmDispatch_Invoice.php includes/DefineCartClass.php - mods to allow mulitple taxes |  | ? | 2005-06-04 |  |  |
| Shipments.php DefineShipmentClass.php moved below session.inc - fixed bug could not initiate new shipments |  | ? | 2005-05-08 |  |  |
| SystemParameters.php - Bug Year end December was not working correctly fixed |  | ? | 2005-05-08 |  |  |
| gettextification bug fixes for Stocks.php StockTransfers.php BankMatching.php StockCounts.php SalesPeople.php PaymentMethods.php GLAccounts.php GLProfit_Loss.php |  | Gunnar Rikardsson | 2005-05-08 |  |  |
| php-gettext included to enable gettext translations using only php no reliance on gettext and locales for translations |  | Braian Gomez | 2005-05-07 |  |  |
| bug in includes/ContructSQLForUserDefinedSalesAnalysis.inc - custbranch join should be on both debtorno and branch code |  | Scott Rosa | 2005-05-03 |  |  |
| bug fixes www_users.php passwords using the crypt function and defaults the language and DisplayRecordsMax. DB error messages now attempt to put a page header - DB_connect_mysql.inc and DB_connect_psql.inc |  | Braian Gomez | 2005-05-01 |  |  |
| gettextification SystemParameters.php |  | Gunnar Rikardsson | 2005-05-01 |  |  |
| WWW_Users.php DisplayMaxRecords was not using the config variable DefaultDisplayRecordsMax to default the number of records on a display .. the db was set to default to 0 - search functions failing. Now fixed. |  | ? | 2005-05-01 |  |  |

## v3.0 - 2005-04-30

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| db had language field in www_users char(2) sh/be char(5) |  | ? | 2005-04-29 |  |  |
| fixed pdf printing bug error in addTextWrap - leading spaces not handled |  | David Hawke | 2005-04-13 |  |  |
| Fix credit check code of 7/4/05 - buggy and fixed again 14/4/05 Tan Hock Chye noted bug where no transactions |  | ? | 2005-04-11 |  |  |
| Areas.php - Fix handling of empty fields and make opperation compatible with rest of system. |  | Danie | 2005-04-09 |  |  |
| Shippers.php - Fix handling of empty fields and make opperation compatible with rest of system. |  | Danie | 2005-04-09 |  |  |
| StockCategories.php - Fix handling of empty fields and make opperation compatible with rest of system. |  | Danie | 2005-04-09 |  |  |
| DiscountCategories - Fix automatic use of 0 value should be empty string. |  | Danie | 2005-04-09 |  |  |
| FreightCosts.php - Fix default values, change "do while" to "while" loop so an empty record is not displayed, also conformity. |  | Danie | 2005-04-09 |  |  |
| Locations.php - Fix conformity and Error generated after error report. |  | Danie | 2005-04-09 |  |  |
| SalesTypes.php - Fix conformity and Error generated after error report. |  | Danie | 2005-04-09 |  |  |
| BankAccounts.php - Fix conformity and Error generated after error report. |  | Danie | 2005-04-09 |  |  |
| CompanyPreferences - Add account code to display of account names |  | Danie | 2005-04-09 |  |  |
| Added a check to SelectOrderItems_IntoCart.inc and to SelectOrderItems.php to check credit limits - new parameter in SystemParameters and the config table to set the level of credit limit checking to not checked, warn only or prohibit sales. Calculation of credit available in SQL_CommonFunctions.inc |  | ? | 2005-04-07 |  |  |
| Changed fpdf.php output method/function to send a string by default - this preserves the way the ros code was written - and was causing issues with the headers/filenames |  | ? | 2005-04-07 |  |  |
| Blind packing notes - system parameter to default all customer branches to blind - ie not to show the company and logo of the webERP company dispatching the product - useful for where the webERP system is dispatching to customers' of customers. Changes to SelectOrderItems.php DefineCartClass.php and DeliveryDetails.php to allow the default to be overridden and CustomerBranches.php to allow entry of the setting. |  | Scott Rosa | 2005-03-31 |  |  |
| Defaulted new users to see all modules on screen - WWW_Users.php |  | ? | 2005-03-31 |  |  |
| Made new system parameter to set the PackNoteFormat - this ensures that the link to print the packing slip uses the correct script - the pre-printed stationery template script or the laser packing slip script. |  | ? | 2005-03-31 |  |  |
| Added a new configuration variable DB_Maintenance and DB_Maintenance_LastRun. This variable is checked in session.inc to see if it is set to maintain the DB using a new function in the DB specific include DB_Connect_XXXXX.inc (where XXXX is the $dbtype) - there is a new function in these files called DB_Maintenance($Conn) that runs the routines necessary to keep the DB on top form. pg needs regular vacuums to run optimally. Changes also in SystemParameters.php to allow the variable to be set. |  | ? | 2005-03-27 |  |  |
| Bug fix Lyndsay Roger - StockAdjustments.php could not enter quantity and part code for a stock adjustment without being asked to enter the quantity again ... fixed |  | ? | 2005-03-26 |  |  |
| Added a new config HTTPS_Only variable that requires a https connection in session.inc before allowing the page as suggested by Jesse Peterson |  | Lyndsay Roger | 2005-03-25 |  |  |
| Z_DeleteSalesTrans.php fixed lower caseing of sql and trucate statements not liked by pg |  | ? | 2005-03-25 |  |  |
| Recurring sales orders new scripts for SelectRecurringSalesOrder.php RecurringSalesOrder.php and RecurringSalesOrdersProcess.php for selection, modification and processing of recurring sales orders. |  | ? | 2005-03-25 |  |  |
| Auto DebtorNo - Added a variable to config table and modified Customers.php to control the assignment of the DebtorNo to be either manually assigned by the user (the current weberp default) or automatically assigned by the system. The only automatic numbering scheme available right now is sequential, numeric with the value being pulled from the systypes table type 500 |  | Scott Rosa | 2005-03-11 |  |  |
| Changed SystemParameters.php to enable the new AutoDebtorNo to be set in the DB - changed validation to allow debtorno to be set automatically. |  | ? | 2005-03-11 |  |  |
| changes to Stock.php to use the image manipulation features and changed Z_GetStockImage.php to GetStockImage.php |  | Danie | 2005-03-05 |  |  |
| Added SalesCategories.php also table salescat and salescatprod for POS/Shopping integration |  | Danie | 2005-03-05 |  |  |
| changed ALT references to TITLE - TITLE needs to be used for the pop up text to appear as per html spec. ALT just for when images turned off. |  | Jesse | 2005-02-26 |  |  |
| for R&OS pdf class to use FPDF class by Olivier PLATHEY in place of R&OS pdf.php class which is not good at alternative fonts/character sets. |  | Janusz Dobrowlski/ David La | 2005-02-26 |  |  |
| Links to Danie's new scripts in Z_index.php |  | Danie | 2005-02-23 |  |  |
| Z_DataExport.php allows export of various data as csv files |  | Danie | 2005-02-23 |  |  |
| session.inc, config.php, WWW_Users.php, UserSettings.php passwords now have a choice of md5, sha1 or plain text depending on setting in config.php - default is sha1 |  | Danie | 2005-02-23 |  |  |
| Z_GetStockImage.php image manipluation script |  | Danie | 2005-02-23 |  |  |
| New script to print quotations PDFQuotation.php includes/PDFQuotationPageHeader.inc |  | ? | 2005-02-21 |  |  |
| Modification to DeliveryDetails.php to allow flagging of sales orders as quotations. Also modification to select outstanding orders to allow viewing of quotations |  | ? | 2005-02-20 |  |  |
| Bug fixes - on BankMatching.php, GLAccountInquiry.php integrity checking against wrong chartdetail record, lower casing errors in some Z scripts - Dick Stins, StockCostUpdate.php - OverheadCost added twice |  | ? | 2005-02-18 |  |  |
| maximum execution time now as a config.php parameter defaults to 2 mins - 120 seconds |  | ? | 2005-02-17 |  |  |
| Customer login SelectCompletedOrder.php now shows just the orders for the customer without resort to a new script |  | ? | 2005-02-09 |  |  |
| Discount Category logic was not applied on quick entry - bug corrected |  | Scott Rosa | 2005-02-09 |  |  |
| DB_escape_string function added to ConnectDB_xxx.inc database specific scripts and also modifications to Stocks.php Customers.php Currencies.php DeliveryDetails.php CustomerBranches.php to use this function on insert and updates as necessary. |  | ? | 2005-02-04 |  |  |
| db now stores sha1 hash of the password in the database rather than the password itself |  | ? | 2005-02-04 |  |  |
| Negative stocks report |  | ? | 2005-01-29 |  |  |
| new config SystemParamters.php form for entry of config paramters - 99% of all config now in this form. Full description of the purpose of each parameter shown on the form |  | Danie | 2005-01-25 |  |  |
| fixed CompanyPreferences.php had exchange diff account field twice! |  | Janusz Dobrowolski | 2005-01-24 |  |  |
| Upload of part pictures code in Stocks.php |  | Ori Solomon | 2005-01-24 |  |  |
| units of measure now a table and array created from table new script for maintenance |  | Danie | 2005-01-23 |  |  |
| payment and receipt types arrays moved to a table from config.php new include for payments and receipts |  | Danie | 2005-01-23 |  |  |
| Update manual to reflect new security definitions interface changes no longer use config.php |  | ? | 2005-01-22 |  |  |
| StockCheck sheets now have option not to show system counts for counting blind ie so bogus count data can not be made up by counters!! |  | ? | 2005-01-20 |  |  |
| modifications to Z_poAdmin.php and other language translation utility scripts of Kitch's to use the user's language that they currently have set |  | Steve | 2005-01-20 |  |  |
| Moved Security setup to the db from config.php new tables for securityroles securitygroups securitytokens in db. New script WWW_Access to define roles and permissions. |  | Danie | 2005-01-20 |  |  |
| check of sql changes quite a number of bug fixes resulting from change to lower case. |  | Steve | 2005-01-11 |  |  |
| changing all SQL that used IF to CASE to enable more universal database compatibility |  | ? | 2005-01-04 |  |  |
| changing all LastInsertID to add the necessary parameters to enable PG compatibility |  | ? | 2005-01-03 |  |  |
| changing all INTERVAL to use the DB specific function and new DB_Connect_postgres (Danie) and DB_Connect_mysql |  | ? | 2005-01-02 |  |  |
| Moved Account Sections to the db from config.php new table in db |  | Danie | 2005-01-19 |  |  |
| lower case all sql and array elements of returns from DB_fetch_array to enable compatibility with postgres |  | Khattak/Phil | 2004-12-30 |  |  |

## v2.9b - 2004-12-23

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| new LanguageSetup.php using 5 character language_country codes wrinkles in multi-language fixed - testing with Polish from Janusz Dobrowolski the first language to be translated into. Lots of bug fixes |  | Steve | 2004-12-23 |  |  |
| Large changes throughout to ensure consistent use of prnMsg function of Jesse and to use the DB_query function with ErrMsg and DbgMsg parameters and to ensure all strings are enclosed in the gettext function |  | ? | 2004-12-20 |  |  |
| AgedDebtors.php uses CASE instead of IF to ensure ANSI SQL used. |  | ? | 2004-12-18 |  |  |
| modified Z_poEditLangModule.php to check for new strings each time and to write a fresh .mo file after completing and edit |  | ? | 2004-12-18 |  |  |
| new scripts to enable administration of language files and directories |  | Kitch | 2004-11-15 |  |  |
| GoodsReceived.php bug on receiving nominal items - value of CurrentStandardCost not set for nominal items - now fixed |  | ? | 2004-11-22 |  |  |
| Large amounts of gettext work too much to record here - most scripts re-worked some |  | ? | 2004-11-22 |  |  |
| Entering purchase orders now allows selection of all GRNs on a Purchase order in one hit or selected lines using checkboxes - much neater selection of all lines to be invoiced in one go. Gettextified this script - SuppInvGRNs.php |  | Vitaliy | 2004-11-08 |  |  |
| SelectCustomer.php gettextified |  | Steve | 2004-11-08 |  |  |
| SelectOrderItems.php bug in customer login order entry |  | ? | 2004-11-06 |  |  |
| PO_PDFPurchOrder.php now prints the suppliers description for the item on the PO. |  | ? | 2004-11-04 |  |  |
| bug fixes Bob CustomerReceipt.php price_customer.php |  | ? | 2004-11-04 |  |  |
| SelectCreditItems.php Steve gettextified - index.php html fixes Steve |  | Steve | 2004-10-31 |  |  |
| UserSettings.php fixed title =_(...) after include session.inc |  | ? | 2004-10-31 |  |  |
| footer.inc Jesse - new web site reference and link, also fix copy; Steve |  | Steve | 2004-10-31 |  |  |
| PurchData.php fix hyphens in strings not minus signs - Bob |  | ? | 2004-10-31 |  |  |
| PrintCustOrder.php PrintCustOrder_generic.php PrintCustStatements.php PO_OrderDetails.php PO_PDFPurchOrder.php and associated includes/XXXXPageHeader.inc files gettextified Jesse |  | ? | 2004-10-31 |  |  |

## v2.9a - Date ?

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| PageSize errors caused with register globals off. Used new variable PaperSize |  | Vitaliy | 2004-10-30 |  |  |
| fixed AgedDebtors.php and CustTransInquiry.php |  | ? | 2004-10-30 |  |  |
| added correct icons to css directories |  | Steve/Chris | 2004-10-30 |  |  |
| Focus on SelectCustomer, SelectOrderItems, SelectProduct, SelectSupplier all default to the code entry. Previosuly required a click in the field now already in the field |  | ? | 2004-10-24 |  |  |
| gettextified SalesAnalysis_UserDefined.php, SelectSalesOrder.php, SelectSupplier.php, TaxAuthorityRates.php, WWW_Users.php, WhereUsedInquiry.php, Z_ChangeBranchCode.php, Z_UploadForm.php, Z_UploadResult.php |  | ? | 2004-10-24 |  |  |
| session.inc / login.php - Chris bad password now goes straight to another try. Also, bad permission for a script code improved |  | Chris | 2004-10-22 |  |  |
| bg.gif - Steve's much reduced graphic - great performance increase on login. Biggest graphic in system was the first login page ?! |  | Steve | 2004-10-22 |  |  |
| Gettextified PDFBankingSummary.php, PDFChequeListing.php, SalesGLPostings.php, SalesPeople.php, SalesTypes.php |  | ? | 2004-10-17 |  |  |
| Gettextified PurchData.php, ReverseGRN.php |  | Steve | 2004-10-17 |  |  |
| Gettextified Prices.php,Prices_Customer.php |  | Steve | 2004-10-14 |  |  |
| Gettextified PDFCustomerList.php PDFPriceList.php, PDFStockCheckComparison.php, PDFStockLocTransfer.php Jesse |  | Jesse | 2004-10-14 |  |  |
| Bug fixes GLTransInquiry.php Steve - $rootpath in single quotes, $title above include session.inc |  | Steve | 2004-10-14 |  |  |
| Bug fixes DateFunctions.inc, SQL_CommonFunctions.inc Jesse - proper use of DB_query, prnMsg |  | Jesse | 2004-10-14 |  |  |
| StockTransfer.php serialised now works, gettextified: GLTrialBalance - Jake, GLAccountInquiry - Jake, Payments - Steve, PaymentTerms Steve PO_SelectOSPurchOrder.php Steve PeriodsInquiry.php Steve PO_SelectPurchOrder Steve |  | Jake/Steve | 2004-10-12 |  |  |
| StockCostUpdate.php gettextified - fixed bug that would not allow update with zero stock on hand |  | ? | 2004-09-29 |  |  |
| New reports OrderStatus.php and OrderStatusPageHeader.inc allow reporting on orders, back orders by location and stock category for a specified date range. |  | ? | 2004-09-29 |  |  |
| SelectOrderItems_IntoCart.php now traps for obsolete items and a check to see if prohibit sales of items where the cost is zero if the new config.php variable $AllowSalesOfZeroCostItems is set to false. |  | ? | 2004-09-29 |  |  |
| gettextification - FTP_RadioBeacon.php, EmailCustTrans.php, FreightCosts.php |  | ? | 2004-09-23 |  |  |
| gettextification - EDIMessageFormat.php EDIProcessOrders.php EDISendInvoices.php BOMInquiry.php BOMListing.php |  | ? | 2004-09-22 |  |  |
| gettextification - InventoryValuation.php Locations.php Logout.php MailInventoryValuation.php MailSalesReport.php |  | Steve | 2004-09-22 |  |  |
| gettextification - GLJournal.php GLProfit_Loss.php GLBalanceSheet.php GLCodesInquiry.php GLTransInquiry.php |  | Jake | 2004-09-21 |  |  |
| ConfirmDispatch_Invoice.php now checks to ensure there is a quantity to be invoiced on at least one line |  | ? | 2004-09-19 |  |  |
| Stocks.php gettextified - DB_query changes also check no stock on hand before alteration to controlled and not dummy/assembly/kitset |  | ? | 2004-09-19 |  |  |
| ConfirmDispatch_Invoice.php ConfirmDispatchControlled_Invoice.php CreditItemsControlled.php GoodReceivedControlled.php ConnectDB.inc DefineSerialItems.php InputSerialItemsFile.php - more extensive modifications too InputSerialItemsKeyed.php, InputSerialItems.php StockAdjustmentsControlled.php StockAdjustments.php StockTransfers.php StockTransfersControlled.php all above gettextified and rationalisation of code in InputSerialItems.php includes |  | Jesse | 2004-09-19 |  |  |
| TaxAuthorities.php gettextification - TaxID in taxauthorities table made auto increment |  | ? | 2004-09-19 |  |  |
| SuppliersAtPeriodEnd.php gettextified |  | ? | 2004-09-12 |  |  |
| DebtorsAtPeriodEnd.php gettextified |  | Hani | 2004-09-12 |  |  |
| Credit_Invoice.php gettextified - fixed bug where TaxRate was not recorded on StockMoves correctly |  | ? | 2004-09-12 |  |  |
| Tax.php and PDFTaxPageHeader.inc reporting GST/VAT |  | ? | 2004-09-19 |  |  |
| GLPostings.inc was not adding new chart details when an account had been added - fixed |  | ? | 2004-09-12 |  |  |
| BOM.php, BankMatching.php, BankReconciliation.php gettextified |  | Steve | 2004-09-10 |  |  |
| Areas.php gettextified |  | ? | 2004-09-08 |  |  |
| CustomerReceipt.php CustomerTransInquiry.php gettextified |  | Victor | 2004-09-06 |  |  |
| GoodsReceived.php GoodsReceivedControlled.php InputSerialItems.php InputSerialItemsKeyed.php InputSerialItemsFile.php gettextified and some polish |  | Jesse | 2004-09-05 |  |  |
| AgedDebtors.php/PDFAgedDebtorsPageHeader.inc AgedSuppliers.php PDFAgedSuppliersPageHeader.inc all gettextified |  | ? | 2004-09-05 |  |  |
| AccountGroups.php getextified, Customers.php gettextified |  | Victor | 2004-09-01 |  |  |
| footer.inc now has date/time displayed before copyright notice. |  | ? | 2004-09-01 |  |  |
| PrintCustTrans.php now prints the Payment Terms on invoices and credit notes as contributed |  | Steve Ball | 2004-09-01 |  |  |
| New script StockQuantityByDate.php that shows the quantity on hand at any historical date selected for all items in a selected stock category |  | Chris Bice | 2004-09-01 |  |  |
| Fixed SalesPeople.php, CreditStatus.php Salestypes.php display issues on editing. |  | ? | 2004-08-30 |  |  |
| PO_Header.php fixed sequence of order deletion details then order header to allow for foreign key constraints. Thanks Victor! Also setup strings for multi-language and using DB_query error reporting. |  | ? | 2004-08-30 |  |  |
| PDFDeliveryDifferences.php had a few bugs in the sql fixed |  | ? | 2004-08-28 |  |  |
| PDFChequeListing.php PrintCustTrans.php PDFStockCheckComparison.php SuppPaymentRun.php all had display errors fixed. |  | Steve | 2004-08-28 |  |  |
| PO_PDFPurchOrder.php purchase order printing corrected. |  | ? | 2004-08-28 |  |  |
| new script UserSettings.php linked from the user name on header.inc to allow setting of the theme, language and max number of records to display. Changes to session.inc to accomodate changing theme and language. |  | Steve | 2004-08-28 |  |  |
| Theme, Language and DisplayRecordsMax are now fields in WWW_Users |  | ? | 2004-08-28 |  |  |
| Minor changes to theme default.css files |  | Steve | 2004-08-28 |  |  |
| SelectCustomer.php SelectProduct.php SelectSupplier.php all modified to allow paging where a large result set is returned |  | Steve | 2004-08-28 |  |  |
| SelectCreditItems.php now allows text line entries with each line of the credit note |  | ? | 2004-08-25 |  |  |
| SelectOrderItems.php SelectCreditItems.php strrpos works differently in PHP 5 changed to use mb_strpos |  | ? | 2004-08-23 |  |  |
| Changed ConfirmDispatch_Invoice.php to allow entry of consignment reference also changed PrintCustTrans.php and PDFPrintCustTransPageHeader.inc to print out the narrative on sales order lines and the consignment number |  | ? | 2004-08-14 |  |  |
| Changed SelectOrderItems.php to allow entry of narrative and update narrative entries also show warning when quick entries not processed. |  | ? | 2004-08-14 |  |  |
| Changed DefineCartClass.inc to have a Narrative variable in LineItems and a Consignment variable in the header |  | ? | 2004-08-14 |  |  |
| Added Narrative field to SalesOrderDetails and StockMoves for adding a narrative about each line on an invoice |  | ? | 2004-08-14 |  |  |
| Added Consignment field to DebtorTrans for adding a Consigment Note (UPS or other freight company) reference against each delivery/invoice |  | ? | 2004-08-14 |  |  |
| Help system now has disabled editing for HelpType A or S where the user does not have system set up access |  | ? | 2004-08-14 |  |  |
| Bug in ConfirmOrderInvoice.php used $SESSION['CustomerID'] should be using $_SESSION['Items']->DebtorNo to avoid situations where another instance of the browser looks up a customer and changes $_SESSION['CustomerID'] half way through invoicing. |  | ? | 2004-08-13 |  |  |
| Fixed bugs in PDFCustomerList.php, Help.php (couldn't edit a help record!), index.php left a html comment without an opening bracket. |  | ? | 2004-08-10 |  |  |
| now with company name, user name and database and shortcut keys to quick menu options. |  | ? | 2004-08-09 |  |  |
| New Customer listing report PDFCustomerList.php with options to print for selected areas and sales folks, also customers below or above a certain level of activity since a specified date. |  | ? | 2004-08-08 |  |  |
| New scripts for reconciliation of GL for debtor and creditor balances as at the end of a prior period. DebtorsAtPeriodEnd.php includes/PDFDebtorBalsPageHeader.inc SupplierBalsAtPeriodEnd.php includes/PDFSupplierBalsPageHeader.inc |  | ? | 2004-08-01 |  |  |

## v2.9 - 2004-07-17

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Polishing Credit_Invoice.php SelectCreditItems.php using new DB_query function for error messages and checking all working with controlled items. |  | ? | 2004-07-15 |  |  |
| Serial items input from file added - fixed inconsistencies with other scripts. |  | Jesse | 2004-07-11 |  |  |
| header.inc now has a link to a Help.php page Help.php added and new tables Help and Scripts. Help contains the context sensitive help referenced to the script - which is where the scripts table comes in. It contains all the scripts in the system with an overview of what the script does. The new Help script retrieves records from the Help table relevant to the PageID, the PageID is determined from the filename looked up against the Scripts table. Users can add new help and edit or delete help. A little shy on data for the help table!! |  | ? | 2004-07-07 |  |  |
| GLBalanceSheet.php displays a html balance sheet for any period end. |  | ? | 2004-06-19 |  |  |
| AccountGroups now uses an array for Section in accounts defined in config.php $Sections array. This array also used in GLProfit_Loss.php |  | ? | 2004-06-17 |  |  |
| GLProfit_Loss.php added - index.php modified to add a link to this script |  | ? | 2004-06-17 |  |  |
| DefineCartClass.inc updates to DB new parameters for delete, remove, add to cart class - changes to SelectIntoCartClass and other scripts to pass appropriate variables rather than rely on SESSION['ExistingOrder']. Also DecimalPlaces field added to order data in add_to_cart. |  | ? | 2004-06-08 |  |  |
| Updates to SelectOrderItems.php now uses new DB_query error message/debug message parameters |  | ? | 2004-06-08 |  |  |
| SelectCreditItems.php now uses Quantity not QtyDispatched - bug corrected. |  | ? | 2004-06-08 |  |  |
| New script StockSerialItems.php shows the stock status of serial or controlled items - the total shown should tie up with the StockStatus total quantities on hand. |  | ? | 2004-06-05 |  |  |
| New script StockTransfersControlled.php allows input of serial items for stock transfers. |  | ? | 2004-05-29 |  |  |
| New script StockAdjustmentsControlled.php allows input of serial items for stock adjustments. |  | ? | 2004-05-29 |  |  |
| StockStatus.php, StockMovements.php StockLocMovements.php, StockUsage.php now all use the new decimalplaces field of the stock master to display quantities. |  | ? | 2004-05-29 |  |  |
| BOMs.php modified to use Jesse DB_query function with error messages and Debug messages |  | ? | 2004-05-25 |  |  |
| Stocks.php now checks and stops the mbflag change to assembly or kitset when the item is a component in a bill. |  | ? | 2004-05-25 |  |  |
| ConfirmDispatch_Invoice.php updates and inserts for serialised/controlled items. |  | ? | 2004-05-25 |  |  |
| ConfirmDispatch_Invoice.php ConfirmDispatchControlled_Invoice.php DefineSerialItems.php mods for controlled batch numbers and serial number input. |  | ? | 2004-05-24 |  |  |
| GoodsReceived.php and GoodsReceivedControlled.php now allows for receiving controlled and controlled/serialised items. |  | ? | 2004-05-21 |  |  |
| StockMoves remove field BundleID |  | ? | 2004-05-17 |  |  |
| StockCheck.php now only prints stock sheets for items committed to the stockcheckfreeze table. Behaviour of this script was counter intuitive. Deletion of existing entries deleted all locations stock of the same range of categories - now corrected. |  | ? | 2004-05-17 |  |  |
| Added DecimalPlaces tinyint default 0 to StockMaster - this determines the number of decimal places to show for the stock item in all places where the item is displayed. Yet to update displaing throughout the system. |  | ? | 2004-05-17 |  |  |
| DeliveryDetails.php SelectOrderItems.php DefineCartClass.php - deleting lines off an existing order was not working correctly. It was possible to delete completed order lines off an order in changing an outstanding order line. Bad bug corrected. |  | ? | 2004-05-12 |  |  |
| Added new tables for Serialised stuff StockSerialItems StockSerialMoves. Alterations to StockMaster for Serialised and DecimalPlaces - for displaying items with different decimal places |  | ? | 2004-05-05 |  |  |
| Added includes/MiscFunctions.php for Jesse. Stocks.php script modified for Serialised and DecimalPlaces |  | ? | 2004-05-05 |  |  |
| StockLocTransferReceive.php to receive quantities set up to transfer from StockLocTransfer.php. Deletes the temporary table data holding the transfered items on creation of the stock movements necessary. |  | ? | 2004-04-29 |  |  |
| RePostGL scripts added updating of ChartDetails.BFwdBudget field. |  | ? | 2004-04-28 |  |  |
| SelectProduct.php added link to the part picture as suggested by Alan Beard. |  | ? | 2004-04-28 |  |  |
| StockLocTransfer.php - contributed by Chris Bice, creates a transfer from one location to another without creating the transaction but records the transfer in a pending transfers file. Also PDFStockTransfer.php and includes/PDFStockLocTransferPageHeader.inc creates the transfer paperwork. new table LocTransfers also required - in upgrade script. |  | ? | 2004-04-25 |  |  |
| balances only shown where the account is a balance sheet account. GL account is now selectable from a list rather than selecting as per customer or supplier - unlikely to be the same volume of GL accounts hence overhead of downloading all GL accounts over dial up (for the list) is deemed an acceptable compromise. |  | ? | 2004-04-25 |  |  |
| PO_SelectOSPurchOrder.php made the location default properly to the users default location. |  | ? | 2004-04-22 |  |  |
| Links for CurrencySuppliersBalances.php and CurrencyDebtorsBalances.php re-instated on Z_index.php - these scripts are for reconciling the creditors and debtors control accounts. |  | ? | 2004-04-22 |  |  |
| bug fix in GoodsReceived.php - cost differences where std cost changed was not working correctly all cost was going to price variance rather than stock!! Serious bug.... now rectified. |  | ? | 2004-04-22 |  |  |
| index.php now uses extra column where many options under one heading |  | ? | 2004-04-18 |  |  |
| New scripts StockLocStatus.php - inquiry script that shows all stock items status in the selected category. StockLocMovements.php - inquiry script that shows all movements in the selected location and date range - default only one month - this script can return a lot of data and is a mistake to run over dial up on a business with many stock movemnts. Thanks to Chris Bice for these scripts. Also added links from index.php - |  | ? | 2004-04-18 |  |  |
| config.php now over-rides php.ini to ensure tht NOTICES turned off. |  | ? | 2004-04-02 |  |  |
| Z_ChangeStockCode, Z_ChangeDebtorCode, Z_ChangeBranchCode all required mods to work correctly with Foreign key checks in place. |  | ? | 2004-03-30 |  |  |
| Stocks.php now prohibits stock codes containing a point . |  | ? | 2004-03-30 |  |  |
| SelectOrderItems search products now defaults to stock category 'All' |  | ? | 2004-03-30 |  |  |
| GoodsReceived.php now takes account of changed standard cost at between bookings in of stock - unbalanced GL journals corrected. (this was wrong - fixed 22/4/04) |  | ? | 2004-03-27 |  |  |
| Various scripts changed variables that are not initiated first to avoid NOTICES where NOTICES are enabled in php.ini - still a long way to go here |  | ? | 2004-03-16 |  |  |
| All scripts now have a comment for CVS version number - Dick Stins suggestion |  | ? | 2004-03-16 |  |  |
| Fixed bug on new system where sales and COGS GL posting tables not set up - auto create a default GL account - foreign key could give problem if account groups modified so now auto creates a sales account group first - thanks Dick Stins |  | ? | 2004-03-16 |  |  |
| Added new tables for EDI_ORDERS_Segs and EDI_ORDERS_SegGroups that hold the defined segments for the EANCOM EDI ORDERS message and the segment groups info. |  | ? | 2004-03-14 |  |  |
| AccountGroups.php modified to disallow & or ' - also fixed class='tableheader' as per css. |  | ? | 2004-03-14 |  |  |
| GLAccounts.php modified to check for related records in BankAccounts and TaxAuthorities to avoid data integrity issues. Foriegn Keys added as necessary - in the upgrade2.8-2.9.sql script |  | ? | 2004-03-14 |  |  |

## v2.8 - 2004-02-26

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| TaxAuthorityRates.php script for modifying tax rates for defined tax authorities/tax levels |  | ? | 2004-02-26 |  |  |
| fixes to sales analysis reports with only one level of grouping - also error in ReportColumns storeage of datatype Select corrected. |  | ? | 2004-02-24 |  |  |
| EDIMessageFormat script to set up invoice format only so far |  | ? | 2004-02-22 |  |  |
| Foreign key defintions accross the board for all relations in all tables as necessary added to upgrade script 2.7-2.8 |  | ? | 2004-02-21 |  |  |
| Referential integrity checks in Locations.php for changes in TaxAuthority to ensure existance of necessary records in TaxAuthLevels |  | ? | 2004-02-16 |  |  |
| Referential integrity checks in Stocks.php for changes in TaxLevel to ensure exist in TaxAuthLevels |  | ? | 2004-02-16 |  |  |
| Referential integrity checks in TaxAuthorities for deletions or additions of tax authorities to ensure that records exist (or are deleted) when Tax Authorities are added or deleted. |  | ? | 2004-02-16 |  |  |
| Customers now default the currency of new customer. |  | ? | 2004-02-16 |  |  |
| Suppliers now default the currency and clears input from last insertion on each addition. |  | ? | 2004-02-16 |  |  |
| Location maintenance form modified to allow specification of the dispatch TaxAuthority. |  | ? | 2004-02-15 |  |  |
| Tax Authorities maintenance form modified no tax rate any more. |  | ? | 2004-02-15 |  |  |
| CustomerInquiry icons reduced in size. |  | ? | 2004-02-15 |  |  |
| Upgrade sql file create 2.7 - 2.8 to add new tables and modify existing ones as necessary. |  | ? | 2004-02-12 |  |  |
| SupplierInvoice.php and SupplierCredit.php modified to use the new tax configuration - gets tax rate from DefaultTaxLevel in config.php and TaxAuthority in Suppliers table. |  | ? | 2004-02-12 |  |  |
| EmailCustTrans.php that runs PrintCustTrans.php with appropriate get paramters to send the transation off to the customer branch email address - or the user can over-ride with another email address. |  | ? | 2004-02-12 |  |  |
| Email invoices or credits link set up from CustomerInquiry.php |  | ? | 2004-02-11 |  |  |
| Credit_Invoice.php get tax rate per the new tax structure record tax rate against each line of the credit note |  | ? | 2004-02-11 |  |  |
| New variable in config.php DefaultTaxLevel used in AP and for freight tax calcs by default |  | ? | 2004-02-11 |  |  |
| modified Z_changeStockCode.php to not use a transaction - some of the tables were not transactional tables anyway. Also Z_ChangeCustomerCode.php same treatment. |  | ? | 2004-02-10 |  |  |
| new script Z_ChangeBranchCode.php changes the branch code for a selected customer and customer branch code - all occurrences in SalesAnalysis pricing, debtorTrans, CustBranch, SalesOrders etc changed |  | ? | 2004-02-10 |  |  |
| Modifications to SelectCreditItems.php to get tax rate per the new tax structure record tax rate against each line of the credit note |  | ? | 2004-02-09 |  |  |
| New function in SQL_CommonFunctions.inc to get the tax rate from the Level and TaxAuthority |  | ? | 2004-02-08 |  |  |
| Modification to ConfirmDispatch_Invoice.php to get tax rate and be user modifiable by line of the invoice |  | ? | 2004-02-08 |  |  |
| TaxLevel field added to Stocks.php master maintenance form. |  | ? | 2004-02-07 |  |  |
| EDISendInvoices.php script added to create EDI INVOIC messages for all customers with EDIInvoice set to 1 and DEbtorTrans EDISent field=0. Creates and sends EDI message in the method set up against the customer EDI set up. Linked in Z_index.php only - script should be run from Cron really - but debugging output for testing if run in browser. |  | ? | 2004-02-07 |  |  |
| EDI configuration variables applicable company wide in config.php |  | ? | 2004-02-06 |  |  |
| DateFunctions.inc new function SQLDateToEDI - takes an SQL date and converts it to EANCOM 102 format date |  | ? | 2004-02-06 |  |  |
| SelectCustomer.php new link to CustEDISetup.php |  | ? | 2004-02-06 |  |  |
| New field in StockMaster TaxLevel (TinyInt) this field indicates the level of tax applicable to the item. The rate of tax is determined by reference to the TaxAuthLevels table which is a matrix of TaxAuthorities and Levels of tax applicable. |  | ? | 2004-02-06 |  |  |
| new table TaxAuthLevels this is to enable tax rate depending on product |  | ? | 2004-02-06 |  |  |
| new field in StockMoves TaxRate float this records the rate used in the transaction |  | ? | 2004-02-06 |  |  |
| CustEDISetup.php new script to maintain the EDI parameters of the customer - the new fields in DebtorsMaster table. |  | ? | 2004-02-06 |  |  |
| PrintCustTrans.php now has option to print or not EDI Invoices - defaults to no print |  | ? | 2004-02-06 |  |  |
| new field in DebtorTrans for EDISent TinyInt - indexed. Required to figure out if the transmission of EDI invoices or credits are duplicates or originals. |  | ? | 2004-02-06 |  |  |
| new table for EDIMessageFormat for outgoing message definition by partner outgoing message is either INVOIC DebtorNo is expected or ORDERS - SupplierID is expected |  | ? | 2004-02-01 |  |  |
| new table for EDIItemMapping for the cross reference table of part codes of suppliers or customers for EDI translations for orders or invoices. |  | ? | 2004-02-01 |  |  |
| new field in CustBranch for CustBranchCode - the customers internal code for the branch - required for edi mapping purposes. Modified CustomerBranches.php to allow the field to be maintained. |  | ? | 2004-02-01 |  |  |
| New field in www_users for blocked tinyint this field is set to 1 when there have been too many login attempts. Modifications to session.inc to fail login if the account is blocked and to update the database when login attempt are exceeded. - Dick Stins suggestion. |  | ? | 2004-02-01 |  |  |
| DiscountCode in DebtorsMaster left off by mistake in last release - fixed. |  | ? | 2004-01-28 |  |  |
| Subject and from of email purchase order was incorrect. |  | ? | 2004-01-28 |  |  |
| Could not modify currencies - fixed |  | ? | 2004-01-28 |  |  |
| using zip format archive (compression is not as good) for 2.7 and on to increase compatability. |  | ? | 2004-01-28 |  |  |

## v2.7 - 2004-01-14

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| New scripts for PDFDeliveryDifferences.php and includes/DeliveryDiffercesPageHeader.inc to report order lines which werent delivered with the first drop of an order for whatever reason - a performance management measure. |  | ? | 2004-01-12 |  |  |
| DiscountCategories script to allow updates to stock master and see other items in the discount category. |  | ? | 2004-01-11 |  |  |
| Shipments written up in the manual. Discount Matrix written up in the manual. |  | ? | 2004-01-11 |  |  |
| SelectOrderItems.php modified for discount matrix lookups - DefineCartClass.inc now holds DiscountCategory for each line item on the order |  | ? | 2004-01-11 |  |  |
| DB changes modifications to DiscountMatrix table - new field StockMaster DiscountCategory increase length of barcode to 50 characters. |  | ? | 2004-01-11 |  |  |
| SelectSupplier options now tablularised as per SelectProduct.php |  | ? | 2004-01-11 |  |  |
| Shipt_Select.php new script to select shipment called alone or from SelectSupplier.php links to Shipments.php - to modify shipment, ShipmentCosting.php to view the costing and close to close the shipment record variances and do the GL journals. |  | ? | 2004-01-11 |  |  |
| SelectCredititems.php correction to sql on goods written off assembly item stock movement going out had an extra comman by mistake. |  | ? | 2004-01-09 |  |  |
| PrintCustStatement.php bug fix for page numbering. |  | ? | 2004-01-09 |  |  |
| New ShipmentCosting.php script when passed a SelectedShipment shows the current costing for the items on the shipment - also shows old closed costings. |  | ? | 2004-01-08 |  |  |
| ShipmentCharges table now has a field for StockID to store charges specific to a particular line item of a shipment. General shipment charges leave this field blank. |  | ? | 2004-01-08 |  |  |
| ShiptCharges.php now allows entry against shipments that are not from the same supplier eg frieght charges from a freight company can be entered against a differnt supplier's shipment as it should be! |  | ? | 2004-01-06 |  |  |
| Shipment sql changed in SupplierInvoice.php to Value not amount for ShipmentCharges - no updates to Accum value field in Shipments since can accumulate from ShiptCharges. AccumValue field dropped from Shipments table. |  | ? | 2004-01-06 |  |  |
| New Shipments.php script to allow definition of a shipment from purchase order items to the same supplier and into the same location |  | ? | 2004-01-06 |  |  |
| Added a 'professional' theme css directory to show how a different look can be acheived by editing the css script - login css is unchanged |  | ? | 2003-12-24 |  |  |
| class='tableheader' references updated throughout all scripts - I think? |  | ? | 2003-12-24 |  |  |
| Credit_Invoice.php and SelectCreditItems.php now use in line editing of lines rather than clicking the link to make it available to modify a line. This is a usability enhancement allowing several items to be modified on screen without sucessive round trips to the server for page updates. Deleting a line from a credit is now only a one click operation rather than a two click previously. Overhead saving, usability improved. |  | ? | 2003-12-24 |  |  |
| InventoryPlanning.php and header changed to use pdf.php class by R&OS - this is the last conversion PDFLIB is NO LONGER REQUIRED! PDFStarter.inc removed |  | ? | 2003-12-21 |  |  |
| PDFPaymentRun.php, header and footer changed to use pdf.php class by R&OS |  | ? | 2003-12-21 |  |  |
| SelectCreditItems.php bug in salesanalysis insert new record on Overcharge type credit note QtyDispatched should be Quantity. |  | ? | 2003-12-16 |  |  |
| can be modified as displayed rather than hitting select to bring up the line for editing. |  | ? | 2003-12-16 |  |  |
| DeliveryDetails.php made to have button to modify line items and save any entries made on the form first using META refresh - taken off the select link on the order summary. |  | ? | 2003-12-16 |  |  |

## v2.6 - 2003-12-15

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Significant updates to the manual |  | ? | 2003-12-14 |  |  |
| SelectCreditItems.php now has a credit type that allows an overcharge to be reversed without crediting the entire invoice then re-invoicing it as per Credit_Invoice.php page. |  | ? | 2003-12-14 |  |  |
| $QuickEntries variable in config.php now used by SelectCreditItems and SelectOrderItems to determine how many quick entry fields to show for credit notes and order entry. |  | ? | 2003-12-14 |  |  |
| fixed double process of last item on quick entry fields on order entry SelectOrderItems.php |  | ? | 2003-12-14 |  |  |
| StockCheck.php now creates pdf using R & OS pdf.php class. |  | ? | 2003-12-08 |  |  |
| StockStatus.php now includes demand for components of assemblies ie if the item is a component of an assembly the demand for the parent as extended by the Bill of Material quantity is included in the reported demand. The same modification in InventoryPlanning.php report. |  | ? | 2003-12-05 |  |  |
| CustomerAllocations.php and includes/DefineCustAllocsClass.php now include the credits and receipts as a line - without any opportunity for allocating but for informational purposes. - Client request. |  | ? | 2003-12-05 |  |  |
| PO_Items.php didn't retain the quantity when a supplier price was looked up - fixed. |  | ? | 2003-12-05 |  |  |
| Stock check system implemented with a modification to print stock check sheets to also create a stock check data - update stock check data or print stock sheets only. Also, new scripts for StockCounts.php for entry of stock counts and PDFStockCheckComparison.php to report the difference and/or create adjustments for all items requiring an adjustment based on the counts and the stock check data quantities. New tables for StockCounts and StockCheckFreeze created. |  | ? | 2003-12-05 |  |  |
| Z_PriceChanges.php has several utilities to modify pricing set up by sales type/customer specials. |  | ? | 2003-12-05 |  |  |
| PrintCustStatements.php now has a config.php option to print settled transactions where they were settled in the last month. Previously this was the default. Could save printing and sending many statements which have zero balances if this option ( $Show_Settled_LastMonth ) is set to 0. |  | ? | 2003-12-05 |  |  |
| PDFPriceList.php allows various options for making a pdf of the price lists or part of a price list. |  | ? | 2003-12-05 |  |  |
| InventoryPlanning.php and StockUsage.php updated to avoid counting in hidden stock movements arising from pricing credits - no real physical stock movement only there for the credit line. |  | ? | 2003-11-30 |  |  |
| Z_ChangeCustomerCode.php allows a customer code to be changed and modifies the code in all tables as necessary. |  | ? | 2003-11-27 |  |  |
| Credit_Invoice.php now has a credit type that allows an overcharge to be reversed without crediting the entire invoice then re-invoicing it. |  | ? | 2003-11-27 |  |  |
| StockMovements.php now only shows movements that are not hidden |  | ? | 2003-11-27 |  |  |
| SpecialOrder.php created to allow one off indented items to be entered by selecting a supplier and a customer then entering the cost and selling price of the items with a description and stock category for GL coding. |  | ? | 2003-11-25 |  |  |
| Changed fields defaulting to '' to blank - these fields actually stored '' not an empty string as expected. SalesAnalysis table fields and Prices DebtorNo and BranchCode fields. Also, changed datetime fields to date where time is not appropriate to store. |  | ? | 2003-11-25 |  |  |
| SalesAnalysis_UserDefined.php broken up and ConstructSQLForUserDefinedSalesReport.inc for the SQL section and PDFSalesAnalysis.inc for the report construction section. The SalesAnalysis_UserDefined.php now uses these as includes as do the MailSalesReport.php and MailSalesReport_csv.php |  | ? | 2003-11-13 |  |  |
| SalesAnalysis_UserDefined.php now prints totals for calculated columns in correct value format as specified |  | ? | 2003-11-13 |  |  |
| SalesAnalysis_UserDefined.php now creates Sql for numerator col x or divided by a constant. Also new field in ReportColumns for Constant. SalesAnalReptCols.php has new field input for the constant. |  | ? | 2003-11-13 |  |  |
| PrintCustStatements.php now settles transactions that should have allocated == the amount of the trans. |  | ? | 2003-11-13 |  |  |
| GLPostings.inc now checks for and creates ChartDetails records where none existed even though they should have existed. |  | ? | 2003-11-11 |  |  |
| PDFChequeListing.php now fixed include("includes/htmlMimeMail.php") so that reports do indeed get mailed! Also moved receipients of this report variable to config.php |  | ? | 2003-11-30 |  |  |

## v2.5 - 2003-10-27

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| BOMListing.php and includes/PDFBOMListingPageHeader.inc modified to use R & OS pdf.php class |  | Phil | 2003-10-27 |  |  |
| AgedSuppliers.php and includes/PDFAgedSuppliersPageHeader.inc modified to use R & OS pdf.php class also supressed settled transactions and transactions with less than a cent unsettled from showing on a detailed report. |  | Phil | 2003-10-27 |  |  |
| AgedDebtors.php and includes/PDFAgedDebtorsPageHeader.inc modified to use R & OS pdf.php class. |  | Phil | 2003-10-27 |  |  |
| Credit_invoice.php cost now taken off sales analysis was adding cost when crediting in error. |  | Phil | 2003-10-27 |  |  |
| ReverseGRNs.php was not creating GL postings fixed was testing for journal amount using StdUnit |  | Phil | 2003-10-26 |  |  |
| Goods received were not recording the GRNBatch to cross reference to the stock movement transaction (in GoodsReceived.php) New field added to GRNs - GRNBatch. Also, was not refering to GRN batch in SuppInvGRN.php to cross reference the batch returned on entry of the goods received batch - normally written on the packing slip by the person booking the goods in. |  | Phil | 2003-10-20 |  |  |
| PrintCustStatements.php some fine tuning to layout - pagination when end of Settled Transactions and space before new page for footer. |  | Phil | 2003-10-17 |  |  |
| Changed AgedSuppliers.php and includes/PDFAgedSuppliersPageHeader.inc to use the R & OS pdf.php class instead of pdflib. |  | Phil | 2003-10-16 |  |  |
| New script PO_OrderDetails.php heavily modified from Open Accounting to show purchase order details instead of re-print of order - also shows qty received and actual price charged as modified from SupplierInvoice.php entry. Linked from PO_SelectCompletedOrder.php. Changed name to PO_SelectPurchOrder.php (can be any PO now not just completed). index.php and SelectSupplier.php links also modified for script name changes |  | Phil | 2003-10-15 |  |  |
| PDFChequeListing.php first payment in the range was missing - now fixed. |  | Phil | 2003-10-13 |  |  |
| SupplierInvoice.php/SupplierCredit.php fixed date retention - rounding of tax and GRN entries - table headers use class='tableheader' |  | Phil | 2003-10-13 |  |  |
| ConfirmDispatch_Invoice.php fixed assembly items stock movements not being created after NewQOH modification |  | Phil | 2003-10-13 |  |  |
| PO_Header.php and PO_Items.php now show the order number as the page header if its an order being modified |  | Phil | 2003-10-13 |  |  |
| SelectSupplier.php now has menu item for Outstanding Purchase Orders and Completed Purchase Orders |  | Phil | 2003-10-13 |  |  |
| PO_SelectCompletedPurchOrder.php now accepts order number entry and queries correctly |  | Phil | 2003-10-13 |  |  |
| SupplierContacts.php fixed now updates where spaces in contact name |  | Phil | 2003-10-13 |  |  |
| PO_SelectPurchOrder.php fixed table headers using class=tableheader |  | Phil | 2003-10-12 |  |  |
| PO_SelectCompletedPurchOrder.php fixed table headers using class=tableheader |  | Phil | 2003-10-12 |  |  |
| PO_PDFPurchOrder.php and includes/PO_PDFOrderPageHeader.inc now print in A4_Landscape with delivery dates and order total in currency of supplier |  | Phil | 2003-10-12 |  |  |
| PO_Items.php now checks for StockID existence where a code is entered. |  | Phil | 2003-10-12 |  |  |
| GoodsReceived.php narrative now adds StockID |  | Phil | 2003-10-12 |  |  |
| SupplierInvoice.php rounding fixed. |  | Phil | 2003-10-12 |  |  |
| ReverseGRN.php finsihed added to SelectSupplier.php menu |  | Phil | 2003-10-12 |  |  |
| New script to create a csv file of stock quantities added StockQties_csv.php added to index.php |  | Phil | 2003-10-08 |  |  |
| BOMInquiry.php now links the component codes back to SelectProduct.php |  | Phil | 2003-03-07 |  |  |
| CreditInvoice.php was not updating NewQOH - now corrected. |  | Phil | 2003-03-07 |  |  |
| Allow other pages to call SelectProduct.php with StockID to show the options available for the item code sent $_POST['Select'] = $_GET['StockID'] |  | Phil | 2003-03-07 |  |  |
| SelectProduct.php tidy display when only one item returned from search |  | Phil | 2003-03-07 |  |  |
| OutstandingGRNs.php from and to criteria were not used by the SQL and no defaults - totals out of alignment with detail lines. Now fixed. |  | Phil | 2003-10-05 |  |  |
| GoodsReceived.php was not creating GL entries correctly at the standard cost due to setting the standard cost inside the for each loop have to use full vble name not the for each vble. Now fixed. |  | Phil | 2003-10-05 |  |  |

## v0.2.4 - 2003-10-01

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| PDFChequeListing.php fixed includes paths and link from index.php |  | Phil | 2003-10-01 |  |  |
| WhereUsedInquiry.php ex Open Accounting modified and linked to from index.php and SelectProduct.php |  | Sherif | 2003-10-01 |  |  |
| SupplierInvoice.php and SupplierCredit.php rounding of amounts posted to minimise allocation and GL cents issues also no GL postings for tax when tax is 0 |  | Phil | 2003-09-27 |  |  |
| PO_Header.php same problem as below when hit enter line items. |  | Phil | 2003-09-27 |  |  |
| Stocks.php when submit hit refreshes to new search - used to fall over when https because hard coded http |  | Phil | 2003-09-27 |  |  |
| SupplierAllocations.php fixed footer.inc now down the bottom! |  | Phil | 2003-09-27 |  |  |
| PO_Header.php fixed cancel order to delete purchorderdetails not purchaseorderdetails as previously. |  | Phil | 2003-09-27 |  |  |
| PO_SelectPurchOrder.php Entering order number to search was ineffective - now fixed. |  | Phil | 2003-09-27 |  |  |
| A not unrelated fix script that re-applies current costs against sales analysis records. Z_ReApplyCostToSA.php for a specified period. |  | Phil | 2003-09-22 |  |  |
| Fixed Credit_Invoice.php was adding cost to sales analysis records sh/been taking off cost!! |  | Phil | 2003-09-22 |  |  |
| Fixed includes paths for Credit_invoice.php/InventoryValuation thanks Al |  | Phil | 2003-09-19 |  |  |
| SelectOrderItems removed extra AND in stock search SQL - thanks Al. |  | Phil | 2003-09-19 |  |  |
| New config.php variable $PO_AllowSameItemMultipleTimes and effected in PO_Items.php |  | Phil | 2003-09-14 |  |  |
| InventoryValuation.php SQL_CommonFunctions.inc now in includes corrected path |  | Phil | 2003-09-14 |  |  |

## v0.2.3 - 2003-09-13

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Increased the length of all email fields in the database and scripts affected WWW_Users.php CustBranches.php SupplierContacts.php |  | Phil | 2003-09-13 |  |  |
| Increased the length of TaxRates in TaxAuthorities.php and the database |  | Phil | 2003-09-13 |  |  |
| Fixed bug that prevented GL payments from being entered |  | Phil | 2003-09-13 |  |  |
| Fixed bugs caused from re-structure of files all class definitions in includes forgot to modify some of the supplier transactions scripts - quite a few !! |  | Phil | 2003-09-13 |  |  |
| to have save_session_path($SaveSessionPath) for load balancing web servers. Re-instated variable $SaveSessionPath in Config.php |  | Phil | 2003-09-12 |  |  |
| modified session.inc to include ConnectDB.inc from includes |  | Phil | 2003-09-12 |  |  |

## v0.2.2 - 2003-09-10

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| BOMs.php fixed search bug that only showed items that had a quantity on sales orders |  | Phil | 2003-09-10 |  |  |
| Tidy up BOMInquiry.php |  | Phil | 2003-09-10 |  |  |
| Reorganisation of remaining included files into includes all Define class files and class.pdf.php and htmlMimeMail.php files modifications to all files included from. |  | Phil | 2003-09-08 |  |  |
| index.php customer login was not initiating a NewOrder with call to SelectOrderItems.php fixed |  | Phil | 2003-09-08 |  |  |
| SelectOrderItems.php new cart was defined after using Items->DebtorNo so was lost on extranet login customer order. |  | Phil | 2003-09-08 |  |  |
| AgedDebtors.php now detail report now only shows transactions not fully allocated - fixed bug. |  | Phil | 2003-09-08 |  |  |
| CustTransInquiry.php corrected links to css/theme/images/ |  | Phil | 2003-09-08 |  |  |
| Used by sherif on tables to remove need for `<bgcolor=#>` and `<font color=white>` html throughout all table headings |  | Phil | 2003-09-08 |  |  |
| Modified CustAllocations.php ended the table and center of html so the footer comes out at the end!! Also used $TableHeader variable for repeated headings - modified to use .tableheader css |  | Phil | 2003-09-08 |  |  |
| Modified SalesAnalysis_UserDefined to find the page header.inc under includes directory |  | Phil | 2003-09-08 |  |  |
| Modified SelectProduct.php to always show the selection criteria options - was a wasted click/round trip to server previously. |  | Phil | 2003-09-08 |  |  |
| Modified SelectOrderItems.php ConfirmDispatch_Invoice.php SelectCreditItems.php DeliveryDetails.php to move references to DefineCartClass.php to includes |  | Phil | 2003-09-02 |  |  |
| SupplierInvoice.php SupplierCredit.php references to DefineSuppTransClass.php moved to includes |  | Phil | 2003-09-02 |  |  |
| Payments.php now dissallows 0 payments DefinePaymentsClass.php moved to includes directory |  | Phil | 2003-09-02 |  |  |
| StockCostUpdate.php reverted back to the latest version with Shane's mods. |  | Phil | 2003-09-02 |  |  |
| CustomerAllocations.php reverted back to the latest version with Shane's mods. Also, moved DefinedCustAllocsClass.php to includes directory |  | Phil | 2003-09-02 |  |  |
| PrintCustTrans.php references to images/logo.jpg back to logo.jpg |  | Phil | 2003-09-02 |  |  |
| GoodsReceived.php default delivery quantity to 2 dp as per rest of system - would be good to set this with the stock item master how many decimal places to display and take this number as the number of dp to display. |  | Phil | 2003-09-02 |  |  |
| PO_PDFPurchOrder.php PO_PDFPageHeader.inc put back to later version that allows emailing and R & OS php-pdf class. |  | Phil | 2003-09-02 |  |  |
| removed images directory didn't think necessary for one image. |  | Phil | 2003-09-02 |  |  |
| Bug fixes following re-structure, PO_SelectPurchOrder.php extra bracket |  | Phil | 2003-09-02 |  |  |
| Bug fixes following re-structure, re-added link to stock check sheets in index.php. Updated PDFBankingSummary.php to new R & OS version. |  | Phil | 2003-09-01 |  |  |
| Allowed dates other than the calculated earliest dispatch date to be entered for the invoice date in Confirm_DispatchInvoice.php and also more flexible dating of orders in DeliveryDetails.php - to allow retrospective entry of an order already delivered. |  | Phil | 2003-09-01 |  |  |
| MailSalesReport_csv.php automated mailing of csv sales analysis report bugs ironed out and new structure for includes modified. |  | Phil | 2003-09-01 |  |  |
| Renamed header.inc to session.inc to reflect pages function, which is to provide a common page for global includes and authenticate users. All references throughout the site modified to from HeaderTitle.inc to includes/header.inc and header.inc to includes/session.inc |  | Shane Barnaby | 2003-08-31 |  |  |
| Renamed HeaderTitle.inc to header.inc. The purpose of header.inc is to create the common HTML header used throughout the site. |  | Shane Barnaby | 2003-08-31 |  |  |
| has been replaced with the footer.inc |  | Shane Barnaby | 2003-08-31 |  |  |
| Moved all includes to includes directory. All references to these include files have been updated. |  | Shane Barnaby | 2003-08-31 |  |  |
| Created css directory and moved all images to the default directory within css. This allows for the support of themes. |  | Shane Barnaby | 2003-08-31 |  |  |
| Removed all references to style from the config.php and added the $theme variable. |  | Shane Barnaby | 2003-08-31 |  |  |
| Removed web-erp.css all style now under the css directory/theme |  | Shane Barnaby | 2003-08-31 |  |  |
| Extensive mods to index.php to reflect the theme defined under css - currently only default theme set up but other directories under css can be set up and referred to from the variable in config.php |  | Shane Barnaby | 2003-08-31 |  |  |
| PO_Items.php where a part code is entered it now checks that a part entered exists in the database before allowing it to be added to the order - bug fix. |  | Phil | 2003-08-30 |  |  |
| SelectOrderItems.php checks for existence of the picture file before attempting to display it if no picture then displays "NO PICTURE" |  | Chris | 2003-08-30 |  |  |
| META refresh statments referring to http could equally be (and more likely) https so made these more generic in SupplierCredit.php and SupplierInvoice.php. Link to OutstandingGRNs.php in index.php under AP reports. |  | Phil | 2003-08-28 |  |  |
| OutstandingGRNs.php and PDFOstdgGRNsPageHeader.inc R &OS code pdf-php reports added to show the value of goods received not yet entered against purchase invoices for the purposes of reconciling the goods received suspense account |  | Phil | 2003-08-28 |  |  |
| Modified InventoryValuation.php and PDFInventoryValnPageHeader.inc to use R &OS php-pdf class |  | Phil | 2003-08-28 |  |  |
| SelectOrderItems.php now shows sales type of the customer. |  | Phil | 2003-08-20 |  |  |
| GetPrice.inc now shows a warning if a zero price is returned. |  | Phil | 2003-08-20 |  |  |
| SalesAnalysis_UserDefined.php now used R & OS and accepts 4 levels of grouping allowing more complex reports. |  | Phil | 2003-08-20 |  |  |
| GetPrice.inc bug fixed now gets SalesType, DebtorNo, BranchCode price, then SalesType,DebtorNo, then SalesType, the default price list price. |  | Phil | 2003-08-20 |  |  |
| mods to ConfirmDispatch_Invoice.php, StockAdjustments.php, StockTransfers.php, SelectCreditItems.php, GoodsReceived.php to insert the new qty on hand in the stock movement records created by these scripts and StockMovements.php to show the quantity on hand after each "movement" (?). |  | Phil | 2003-08-19 |  |  |
| introduced a new field to StockMoves table NewQOH this records the new qty on hand after a stock movement |  | Phil | 2003-08-19 |  |  |
| CustWhereAlloc.php shows how an invoice was paid ie the allocations that were made against it. |  | Phil | 2003-08-18 |  |  |
| Z_CheckAllocs.php checks integrity of allocations |  | Phil | 2003-08-18 |  |  |
| CustAllocations.php now includes previous allocations that settled a transaction (fixed bug) |  | Phil | 2003-08-18 |  |  |
| PrintCustOrder_generic.php and PDFOrderPageHeader_generic.php now uses php-pdf by R & OS - for A4 Landscape packing slips - prints two copies one headed office copy the other customer copy. PrintCustOrder.php also allows re-prints on laser stationery with a link to PrintCustOrder_generic/.php. Also prints logo and company info. |  | Phil | 2003-08-11 |  |  |
| CustomerBranches.php now also shows branch code./ |  | Phil | 2003-08-10 |  |  |
| SelectCustomer.php looks at customers codes/names not branches - fixed bug that didn't allow a customer to be selected for adding branches when none defined initially. |  | Phil | 2003-08-10 |  |  |
| Prices_Customer.php now allows many prices to be defined for specific branches. Prices defined not refering to a branch will be the customer default for all branches without a specific price defined. |  | Phil | 2003-08-10 |  |  |
| GetPrice.inc function changed to expect BranchCode. SelectOrderItems_IntoCart.inc also modified to send branch code when this function called from both SelectOrderItems.php and SelectCreditItems.php |  | Phil | 2003-08-10 |  |  |
| Added new field to Prices table BranchCode Varchar(10) so that pricing can be set specific to a branch of a customer. |  | Phil | 2003-08-10 |  |  |
| SelectOrderItems.php. DeliveryDetails.php DefineCartClass.php, SelectCreditItems.php changed references to $_SESSION['CustomerID'] to a new class variable Cart->DebtorNo so that another page open in the browser could not interfere with the order being entered in the same session. |  | Phil | 2003-08-10 |  |  |
| SupplierInvoice.php now checks for pre-existing supplier invoice of the same reference before allowing entry |  | Phil | 2003-08-07 |  |  |
| Install instructions modified to warn users of php.ini setting for session.auto_start sh/be =0 |  | Phil | 2003-08-07 |  |  |
| Payments.php now deals with supplier discount received correctly. Also adds supplier code to narrative of GL postings and bank transactions. |  | Phil | 2003-08-07 |  |  |
| SupplierAllocations.php added check box to allocate whole trans and total allocated recalculation before submission of processing. |  | Phil | 2003-08-06 |  |  |
| DefineSuppAllocsClass.php was not allowing existing allocations to show - fixed. |  | Phil | 2003-08-06 |  |  |
| PO_PDFOrderPageHeader.inc changed $_GET['OrderNo'] to $OrderNo - order number not printing on purchase orders. |  | Phil | 2003-08-06 |  |  |
| CustomerAllocations.php fixed it so that settled transactions now show on the allocation screen where there was an allocation against the transaction from the receipt or credit note being allocated! |  | Phil | 2003-08-04 |  |  |
| CustomerInquiry.php fixed so negative receipts don't have allocation link and real ones do. |  | Phil | 2003-08-04 |  |  |
| StockCostUpdate.php no longer creates a zero GLTrans where there is no quantity of stock on hand. |  | Phil | ? |  |  |
| CustomerAllocations.php no longer shows unsettled negative receipts to allocate against. Also, settles transactions where the allocated amount = the amount of the transaction - there was a bug. New checkbox to tick off allocated transactions to get total exactly right and minimise keying .Also, a button to recalculate the allocation total rather than report an error when attempting to process the allocation. |  | Phil | ? |  |  |
| CustomerInquiry.php modified not to allow allocations of a negative receipt. Allocations must be from credit notes and positive receipts only. Negative receipts show as available to allocate to. |  | Phil | ? |  |  |
| SelectCreditItems.php modified to ensure that a credit note could not be entered twice by hitting refresh. Whilst the CreditItems session variable was unset hitting refresh was entering a credit note for the tax value of the field posted for tax. This is now trapped with a check at the start of the page for an unset and POST["ProcessCredit"]. |  | Phil | ? |  |  |
| PO_PDFPurchOrder.php modified to go straight through to print the order if ViewingOnly==1 |  | Phil | ? |  |  |
| Z_CheckDebtorsControl.php shows the debtors control account for the period selected to compare against the aged balance listing in local currency to prove data integrity - needs editing to modify the period to check. |  | Phil | ? |  |  |
| PDFBankingSummary.php modified to use PHP-PDF class by R & OS and now in a prettier tabular format |  | Phil | ? |  |  |
| PO_PDFPurchOrder.php and PO_PDFPageHeader.inc modified to use R & OS PHP-PDF class. Also now allows selection of a supplier contact to email the order to rather than print it then fax it as was the traditional method. |  | Phil | ? |  |  |
| New scripts for StockCheck.php and PDFStockCheckPageHeader.inc pdflib based stock sheets print outs. |  | Phil | ? |  |  |

## v0.2.0 - 2003-07-26

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| PDFOrderPageHeader.inc and PrintCustOrder.php now refer to the pre-printed stationery versions. These will have to be modified to suit the client. A packing slip format that is headed up is included in the distribution called PDFOrderPageHeader_generic.inc and PrintCustOrder_generic.php - may be better to have another link to this from the order. |  | Phil | ? |  |  |
| CostUpdate.php now allows definition of two page security variables one that allows costs to be viewed and another that allows modifications to costs. |  | Phil | ? |  |  |
| Prices.php now allows definition of two page security variables one that allows prices to be viewed and another that allows modifications to prices. |  | Phil | ? |  |  |
| PO_OrderItems.php now has a button to lookup the purchase price of an item from the purchasing data this was automatic from the part search but where a code was entered directly, there was no lookup. |  | Phil | ? |  |  |
| New field in CustBranch DefaultShipVia that gets updated at the time of invoicing  if it was modified in an order. |  | Phil | ? |  |  |
| PrintCustTrans.php and PDFTransPageHeader.inc modified to print the branch postal address instead of the head office address where the flag InvAddrBranch is set to 1 |  | Phil | ? |  |  |
| Customers.php modified to show AddrInvBranch option |  | Phil | ? |  |  |
| Payments.php now retains the bank account from which the amount is being paid. |  | Phil | ? |  |  |
| Fixed bug in SelectCompletedOrders.php was not showing the delivery dates and qty delivered due to earlier changes to the DefineCartClass.inc calls to additem now given correct params. |  | Phil | ? |  |  |
| Made select customer select a branch names and codes rather than a charge account DebtorNo code. |  | Phil | ? |  |  |
| Stopped order lines from being changed to 0 quantity |  | Phil | ? |  |  |
| SelectOrderItems.php excludes branches with DiableTrans=1 |  | Phil | ? |  |  |
| SelectCreditItems.php excludes branches with DiableTrans=1 |  | Phil | ? |  |  |
| CustomerBranches.php modified to show the new fields for DisableTrans, PostAddr1 - 4. |  | Phil | ? |  |  |
| GetSalesTransGLCodes.php fixed bugs was not returning codes correctly always using 1 |  | Phil | ? |  |  |
| CustomerAllocations.php and SupplierAllocations.php made a rounding tolerance of 0.005 for allocations to be accepted. |  | Phil | ? |  |  |
| Fixed bug in SuppPaymentRun.php to only print/process suppliers with an amount owing - it was picking up suppliers with debit balances ;( |  | Phil | ? |  |  |
| Changed SupplierInquiry.php to retain date from when invoice is held or released. |  | Phil | ? |  |  |
| Changed SelectOrderItems.php  to avoid showing the last quick entry item as selected. unset at the end of the quick entry code. |  | Phil | ? |  |  |
| Fixed bugs in SupplierInvoice.php and SupplierCredit.php that crashed the entry if the tax method was changed without a manual entry being applied new button to change tax calculation method. |  | Phil | ? |  |  |
| ConfirmDispatch_invoice.php now has link to new order entry |  | Phil | ? |  |  |
| CustomerAllocations.php fixed a bug that was locale_number_format ing an already previously locale_number_format ed field to display 1 where locale_number_format had inserted a comma - ie where the figure for yet to allocate was > 999 the system displayed 1 |  | Phil | ? |  |  |
| SupplierInquiry.php made the invoices already paid incapable of changing status to held when already settled |  | Phil | ? |  |  |
| SuppTransGLAnalysis.php made general ledger account select box show account code and in account code order |  | Phil | ? |  |  |
| Made SelectOrderItems.php search only branches - a branch is required to invoice/credit it makes sense to select the branch rather than the customer and then the branch. The customer is implicit in the branch selection. Also use flag DisableTrans to ensure that only those current branches are allowed invoicing. |  | Phil | ? |  |  |
| Added DisableTrans on CustBranch table to disable a branch from being invoiced or credited to |  | Phil | ? |  |  |
| Added a new field AddrInvBranch to DebtorsMaster to enable invoices to be addressed to the branch postal address. To cover the situation where branch invoices must be sent to the branch not the charged customer. |  | Phil | ? |  |  |
| Fixed OrderItems.php to show menu for navigation - sorry Sherif! Also, now centres order comments below. Invoice numbers used to invoice the order are appended to the comments from ConfirmDispatch_Invoice.php. |  | Phil | ? |  |  |
| Fixed bugs in SelectSalesOrder.php was not using $_POST variables for SearchParts also was only searching parts with a sales order - need to show all parts then return orders for that part - no orders returned if no orders for the part selected. |  | Phil | ? |  |  |
| SelectOrderItems.php now uses LIMIT as per SelectProduct.php to limit the number of parts returned on as per the config directive $Maximum_Number_Of_Parts_To_Show |  | Phil | ? |  |  |
| Fixed bug in PDFBankingSummary.php now shows bank account name and account number. |  | Phil | ? |  |  |
| Modified - SelectProduct.php to have an 'All' stock categories search to find an item where the user may not know what category its in or user doesn't need to enter the category cos she knows the code. |  | Phil | ? |  |  |
| Modified - the PrintCustOrder.php and PDFOrderPageHeader.inc to use php-pdf class and default for pre-printed stationery. Pre-printed 2 part stationery is necessary to record notes of storemen. This will require modification for the client's own stationery. Copied the labelled up scripts to PrintCustOrder_generic.php and PDFOrderPageHeader.inc these still require PDFlib installed on the server. |  | Phil | ? |  |  |
| Modified ConfirmDispatch_Invoice.php back to remove selection of freight company. |  | Phil | ? |  |  |
| Modified delivery details to allow selection of a freight company and to allow entry of a freight cost to charge - which can be either calculated by the system or entered manually. |  | Phil | ? |  |  |
| Made new directive in config.php $DefaultPriceList this is used if the price cannot be determined first by reference to the customers salestype and currency. |  | Phil | ? |  |  |
| Modifications to SelectOrderItems.php GetPrice function to use the $DefaultPriceList if fails using the customer salestype/price list. This minimises input in other sales type prices to only those prices which are special to the customer. |  | Phil | ? |  |  |
| Fixed login page in header.inc to test for AccessLevel=="" and check for existence of SecurityGroups being an array as it should be. |  | Phil | ? |  |  |
| Deleted redundant fonts from the distribution only using helvetica |  | Phil | ? |  |  |
| PrintCustTrans.php corrections to format of InvText printing and select box for Credit Notes or Invoices to allow correct parameter to be sent for printing a credit note - was not showing charge branch. |  | Phil | ? |  |  |
| PrintCustStatements.php and PDFStatementPageHeader.inc now uses R & OS class.pdf.php class for creation of pdf statements. |  | Phil | ? |  |  |

## v0.1.10 - 2003-07-06

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| modified PrintCustTrans.php and PDFTransPageHeader.inc to use the R&OS class.pdf.php requires new file PDFStarter_ros.inc |  | Phil | ? |  |  |
| config.php deleted DefaultFont - now in Sherif's style-sheet. |  | Phil | ? |  |  |
| moved $ModuleList array of module descriptions to config.php now used in WWW_Users for defining access as well as index.php |  | Phil | ? |  |  |
| WWW_Users.php modified to allow modules shown on a users menu to be defined and modified. |  | Phil | ? |  |  |
| WWW_Users table addded ModulesAllowed charto hold a comma seperated list of modules allowed to access defined in the same order as $ModuleList array |  | Phil | ? |  |  |
| config.php now has a $version variable also referred to in header.inc |  | Phil | ? |  |  |
| Added scripts class.pdf.php for the pdf-php class by R & OS http://www.ros.co.nz/pdf - this will allow pdf creation without pdflib module compiled or enabled in php. PDF report scripts are yet to be modified. The /fonts directory contains the fonts distributed with the class. |  | Phil | ? |  |  |
| New AutoStocks.php - allows automated adding of a range inventory items. under development. |  | Sherif | ? |  |  |
| New lang folder to contain localizable language string files. for future development |  | Sherif | ? |  |  |
| ConfirmDispatch_Invoice.php - freight calculations only on $DoFreightCalc flag. Also added field to select freight company manually. |  | Sherif | ? |  |  |
| CustomerInquiry.php - display InvText, HTML cleanup of table header |  | Sherif | ? |  |  |
| CustomerTransInquiry.php - display InvText, HTML cleanup of table header |  | Sherif | ? |  |  |
| DefineCartClass.php - add storing of $FreightCost (see SelectOrderItems.php) |  | Sherif | ? |  |  |
| DeliveryDetails.php - add missing fields that weren't being saved/loaded, freight cals only on $DoFreightCalc flag |  | Sherif | ? |  |  |
| HeaderTitle.inc - add option to hide menu (useful when previwing orders/invoices/recepits,etc) |  | Sherif | ? |  |  |
| index.php - Initial module enabling/disabling - not yet used another field in the user set up should determine the modules available. Also added link to AutoStocks.php disabled as an incomplete feature currently. |  | Sherif | ? |  |  |
| OrderDetails.php - hide menu on display of this page |  | Sherif | ? |  |  |
| PDFOrderPageHeader.inc - misc arrangement |  | Sherif | ? |  |  |
| PDFStatementPageHeader.inc - misc arrangement |  | Sherif | ? |  |  |
| PDFTransPageHeader.inc - misc arrangement, rename "A.B.N Number" to "Tax Authority" |  | Sherif | ? |  |  |
| PDFTransPageHeader_A4_Portrait.inc - rename "A.B.N Number" to "Tax Authority" |  | Sherif | ? |  |  |
| PO_Header.php - HTML cleanup, use tableheader style |  | Sherif | ? |  |  |
| PO_Items.php - disable shipment calculation and display on incomplete features flag |  | Sherif | ? |  |  |
| PO_PDFOrderPageHeader.inc - add viewing-only mark - was not merged last time i think. |  | Sherif | ? |  |  |
| PrintCustTrans.php - get and display DelAdd4, rename "A.B.N Number" to "Tax Authority", "GST" to "Tax" |  | Sherif | ? |  |  |
| PrintCustTrans_A4_Portrait.php - rename "A.B.N Number" to "Tax Authority" |  | Sherif | ? |  |  |
| SelectCompletedOrder.php - HTML cleanup, use tableheader style |  | Sherif | ? |  |  |
| SelectCreditItems.php - open preview in a new window |  | Sherif | ? |  |  |
| SelectOrderItems.php - store $FreightCost in session with other vars, HTML cleanup, |  | Sherif | ? |  |  |
| SelectProduct.php - hiding of commands for dummy items, use tableheader style in table |  | Sherif | ? |  |  |
| SelectSupplier.php - get Address4 for Suppliers, use tableheader style in table |  | Sherif | ? |  |  |
| StockCostUpdate.php - add saving of LastCost in StockMaster |  | Sherif | ? |  |  |
| StockMovements.php - HTML cleanup, use tableheader style, open preview/print in new window |  | Sherif | ? |  |  |
| StockStatus.php - HTML cleanup, use tableheader style |  | Sherif | ? |  |  |
| StockUsage.php - HTML cleanup, use tableheader style |  | Sherif | ? |  |  |
| SupplierInvoice.php - hide 'Enter Against Shipment' button for incomplete features flag |  | Sherif | ? |  |  |
| web-erp.css - add tableheader class to standardize table headers |  | Sherif | ? |  |  |

## v0.1.9 - 2003-06-30

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| PrintCustTrans.php and PDFTransPageHeader.inc credit note printing printed branch in middle of company details. Wasn't printing at all on re-print - now fixed. |  | Phil | ? |  |  |
| ConfirmDispatch_Invoice.php HTML cleanup |  | Sherif | ? |  |  |
| Prices.php HTML cleanup |  | Sherif | ? |  |  |
| Credit_Invoice.php HTML cleanup |  | Sherif | ? |  |  |
| DeliveryDetails.php HTML cleanup |  | Sherif | ? |  |  |
| SelectCreditItems.php HTML cleanup, edit percentage as 0-100 not 0.0-1.0 fixed bug caused by additional parameters required for add_items which uses the same Cart class as SelectOrderItems.php following changes to the class required to highlight lines on order with quantity greater than stock |  | Sherif | ? |  |  |
| SelectCustomer.php HTML cleanup |  | Sherif | ? |  |  |
| Added new script PO_SelectCompletedPurchOrder.php to show completed purchase order details |  | Sherif | ? |  |  |
| Added new script Currencies.php to enter or amend currencies and rates |  | Sherif | ? |  |  |
| Added web-erp.css |  | Sherif | ? |  |  |
| BOMs.php HTML cleanup, moved table display to function |  | Sherif | ? |  |  |
| CustomerInquiry.php remove hard-coded date string |  | Sherif | ? |  |  |
| Credit_Invoice.php remove hard-coded date string and display the tax rate |  | Sherif | ? |  |  |
| SelectOrderItems.php removed ability to select a customer without a branch, entry of discounts now 0-100 not 0-1 as before. |  | Sherif | ? |  |  |
| SelectSalesOrder.php HTML cleanup |  | Sherif | ? |  |  |
| ConfirmDispatch_Invoice.php print invoice opens a new window. HTML cleanup of unused FONT tags and |  | Sherif | ? |  |  |
| fix recalculation of tax on update. Fix GL trans created in functional currency not customer currency as before |  | Phil | ? |  |  |
| BOMs.php changed text from "effective from" to "effective after" |  | Sherif | ? |  |  |
| PDFBOMListingPageHeader.inc effective from -> effective after |  | Sherif | ? |  |  |
| DeliveryDetails.php print sales order in a separate window |  | Sherif | ? |  |  |
| PO_Items.php HTML cleanup of table headers, fix for deleting items |  | Sherif | ? |  |  |
| SupplierInvoice.php HTML cleanup - make table headers as strings, display tax rate and authority |  | Sherif | ? |  |  |
| SuppInvGRNs.php HTML cleanup, put table headers in strings |  | Sherif | ? |  |  |
| header.inc $allow_demo_mode to display demo login or not depending on setting in config.php. Also, use a template and make a little prettier :) |  | ? | ? |  |  |
| config.php added $allow_demo_mode to display demo login or not, also added $OverReceiveProportion used in receiving purchase orders. |  | Sherif | ? |  |  |
| GoodsReceived.php added check $OverReceiveProportion to control receipt of quantities larger than on a purchase order |  | Sherif | ? |  |  |
| StockUsage.php no stock usage for dummy, assembly or kitset items |  | Sherif | ? |  |  |
| SelectProduct.php modified to disable the link to allow stock usage or stock status inquiries for dummy, kitset or assemblies HTML cleanup added link to PO_SelectCompletedPurchOrder.php |  | Phil | ? |  |  |
| SelectSalesOrder.php print sales order opens a new window |  | Sherif | ? |  |  |
| PO_Header.php UI fix |  | Sherif | ? |  |  |
| PO_Items.php cleaner way of deleting items to fix bugs in editing after creating order. |  | Sherif | ? |  |  |
| CustomerBranches.php Check if there are any users that refer to this branch code before delete |  | Sherif | ? |  |  |
| Customers.php change discounts to be entered as real percent 0-100 instead of 0.0-1.0 |  | Sherif | ? |  |  |
| DefinePOClass.php on delete of items, mark as deleted don't remove so we can compare later for changes |  | Sherif | ? |  |  |
| DefineSuppTransClass.php fixed Shipment constructor name |  | Sherif | ? |  |  |
| Payments.php for supplier payments, link back to same supplier to avoid re-selecting supplier code show GL account code in select box, check valid GL code for Bank accounts available for selection. |  | Sherif | ? |  |  |
| PO_PDFOrderPageHeader.inc add viewing-only mode misc re-arrangements for obstructing objects |  | Sherif | ? |  |  |
| PO_PDFPurchOrder.php add mode to "view only". for completed purchase orders. |  | Sherif | ? |  |  |
| StockCostUpdate.php check whether the selected stock id actually exists before action |  | Sherif | ? |  |  |
| StockMovements.php HTML cleanup |  | Sherif | ? |  |  |
| Stocks.php $hide_incomplete_features for Controlled field |  | Sherif | ? |  |  |
| SupplierCredit.php HTML cleanup, use $DefaultDateFormat for date display, syntax fixes for Shipments change to buttons for links to gl analysis, shipment entry and entry vs GRNs to avoid additional update step to store entries made in this screen to session. |  | Sherif | ? |  |  |
| SupplierInquiry.php added display of transaction description |  | Sherif | ? |  |  |
| SuppShiptChgs.php syntax fix |  | Sherif | ? |  |  |
| SuppCreditGRNs.php HTML cleanup - Currency on new line |  | Sherif | ? |  |  |
| ShiptsList.php fix table string display - was shifted one column changed to set $PageSecurity before header.inc included. |  | Sherif | ? |  |  |
| CompanyPreferences.php syntax fix in UPDATE sql clause missed closing quote. |  | Sherif | ? |  |  |
| index.php add link to the new currencies page, HTML cleanup of bad FONT codes, changed to black category text for better contrast, added link to query completed purchase orders. |  | Sherif | ? |  |  |
| Logout.php add company name, logo and link back to login |  | Sherif | ? |  |  |
| PO_SelectPurchOrder.php print opens in a separate window |  | ? | ? |  |  |
| Prices.php HTML cleanup - changed heading to Currency prices for item code. |  | ? | ? |  |  |
| SelectCustomer.php HTML cleanup - bigger font for customer name and code, changed text of link to modify sales orders - implicit that only outstanding orders can be modified! |  | Sherif | ? |  |  |
| SelectSupplier.php Display the supplier name as well as the code |  | Sherif | ? |  |  |
| StockAdjustments.php check that the stock item exists before action and link to stock movements on new line |  | Sherif | ? |  |  |
| StockReorderLevel.php HTML cleanup - put item code and description on a new line |  | Sherif | ? |  |  |
| StockStatus.php HTML cleanup - removed unecessary &lt;font&gt; tags |  | Sherif | ? |  |  |
| StockTransfers.php Check that the stock item is valid before action |  | Sherif | ? |  |  |
| TaxAuthorities.php check if there are suppliers using this tax authority before delete |  | Sherif | ? |  |  |
| SelectOrderItems.php now shows order lines where there is insufficient stock on hand at the location in red. Also modified price logic into a seperate function that gets the price specific to the customer if set up otherwise returns the defualt price for the sales type and currency of the customer otherwise 0. |  | Phil | ? |  |  |
| GLTrialBalance.php now shows a link to the account inquiry page for the last period in the range selected |  | Phil | ? |  |  |
| WWW_Users.php now checks that the customer code and branch code are valid before accepting the user details input |  | Phil | ? |  |  |
| GLTrialBalance.php now shows subtotals by account group. |  | Phil | ? |  |  |
| CustomerReceipt.php now checks for a valid GL account on bank account as Payments.php - also now shows account number in select box when entering a gl receipt. |  | Phil | ? |  |  |
| Payments.php now checks to see that the bank account has a valid GL account defined for it. It was possible to enter payments to a non-existant GL account! Also, account code shows in select box for gl code. |  | Phil | ? |  |  |
| Payments.php ensure that the company default currency is selected by default |  | Phil | ? |  |  |
| CustomerReceipt.php check to see that there are entries in the receipt batch before processing |  | Phil | ? |  |  |
| header.inc checked for AccessLevel==0 no longer appropriate sends user back to login screen - access level inquiries only |  | Phil | ? |  |  |

## v0.1.8 - 2003-06-22

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| MailInventoryReport.php script similar to MailSalesReport.php to enable automated email of inventory report |  | ? | ? |  |  |
| MailSalesReport.php script added - largely a copy of SaleAnalysis_UserDefined.php. This new script runs a user defined sales analysis report and mails it to an email account. This script has no PageSecurity nor any output - it creates a file then emails it as an attachement to the address(es) specified in the script. It is meant to be run from cron using wget to email a sales report periodically to staff concerned. |  | ? | ? |  |  |
| GetSalesTransGLCodes.inc now looks for SalesType, Area match for finding a GL code match before using the default. |  | ? | ? |  |  |
| SelectProduct.php - added a transactions column to the actions applicable on selection of an item with links to perform stock adjustments or stock location transfers. |  | ? | ? |  |  |
| PO_Header.php - changed the link to the line items to be a button that diverts to the PO_Items.php only after updating the session variables for changes in the form data. Needs META refresh in the browser - most have this |  | ? | ? |  |  |
| SupplierInvoice.php - changed links to enter invoice amounts against goods received, against shipments and against gl to buttons and made the page redirect after updating the session variables with the posted data in the form. Changes could be lost without hitting update. This method avoids the need for the update button - which has now gone. |  | ? | ? |  |  |
| SupplierCredit.php was not using the default date format for transaction dates. |  | ? | ? |  |  |
| Stocks.php check for stock on hand when changing to assembly or kitset or dummy. Also check for sales orders or purchase orders when changing to assembly kitset or dummy. Also check for BOM when changing to buy or dummy. |  | ? | ? |  |  |
| SQL_CommonFunctions.php GetCompanyRecord function excluding the fields now removed from this table. |  | ? | ? |  |  |
| CompanyPreference.php reflecting the changes in the companies table below |  | ? | ? |  |  |
| Companies table modified - removed default bank account and bank act number field from companies table. Multiple bank accounts can now be defined seperately. |  | ? | ? |  |  |
| Companies table modified removed credit check flag - always checked now |  | ? | ? |  |  |
| Companies table modified removed check stock flag |  | ? | ? |  |  |
| Modified all scripts to have the variable $PageSecurity also config.php to allow definition of security by user group |  | ? | ? |  |  |
| this mechanism allows for much finer control of who has access to what. |  | ? | ? |  |  |
| Modified GLTrialBalance.php to retain periods selected as a starting point for when entering a new period range |  | ? | ? |  |  |
| Modified Stocks.php to always convert stock codes to upper case. |  | ? | ? |  |  |
| SelectOrderItems.php - orders for kitset items that explode the BOM into its components to order were doubling in quantity for each component in the BOM - now fixed. |  | ? | ? |  |  |
| SelectOrderItems.php - selection of parts to order would not show assembly parts since no stock location records for these parts - no stock is maintained for them. No longer show stock on hand in the location nearest the customer in the part search. |  | ? | ? |  |  |
| Shippers.php fixed to allow editing of shippers - link was malformed. |  | ? | ? |  |  |
| GLJournal.php modified to retain processing date after an entry is deleted. |  | ? | ? |  |  |
| New script Z_RePostGLFromPeriod.php reposts all GLTrans from a selected period. |  | ? | ? |  |  |
| New format invoice A4 landscape PDF. |  | ? | ? |  |  |
| New script GLAccountInquiry.php called from SelectGLAccount.php allows listing of the transactions for a specified |  | ? | ? |  |  |
| account. The transaction type and number shows with a link to the detail of the individual transaction ie "drill down" |  | ? | ? |  |  |
| to the lowest level of the transaction. |  | ? | ? |  |  |
| New script InventoryPlanning.php allows listing of the current stock status, QOH, Purchase orders outstanding and sales orders outstanding together with the recent history of stock movements and suggests an order quantity based on a number of periods stock to hold. |  | ? | ? |  |  |
| Logout link shown on header destroys any session data - a user requested this function - can't see why it is necessary but hey! |  | ? | ? |  |  |
| New script BOMListing.php allows listing of bills of material defined for a specified range of items. |  | ? | ? |  |  |
| New script InventoryValuation.php allows listing stock items with a quantity !=0 by location and stock category. |  | ? | ? |  |  |
| Stocks.php after updating an item the user is returned to a new select item screen (SelectProduct.php?NewSearch=True) |  | ? | ? |  |  |
| SalesPeople.php - checked so that cannot have zero length salesperson code. |  | ? | ? |  |  |
| Dropped redundant tables Hedges, TempMRP, StandingJournals (standing journals can be created live at any time since user can post forward and backwards at will). |  | ? | ? |  |  |

## v0.1.7 - 2003-05-16

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Costed BOM inquiry script - thanks Al Adcock. |  | ? | ? |  |  |
| Modified Customers.php to always convert customer codes to upper case. |  | ? | ? |  |  |
| Modified CustomerBranches.php to always convert branch codes to upper case. |  | ? | ? |  |  |
| Modified StockCategories.php to always convert stock category codes to upper case. |  | ? | ? |  |  |
| Modified Areas.php to always convert area codes to upper case. |  | ? | ? |  |  |
| Modified PrintStatments.php to always convert customer from code and customer to codes to upper case. |  | ? | ? |  |  |
| Modified SelectCustomer.php to always convert entries in either keywords or customer code extract to upper case. |  | ? | ? |  |  |
| Modified SelectOrderItems.php to always convert search entries in either keywords or customer code extracts to upper case. |  | ? | ? |  |  |
| MOdified SelectSupplier.php to always convert entries in either keywords or supplier code extract to upper case. |  | ? | ? |  |  |
| Modified Suppliers.php to always convert the supplier code to upper case. |  | ? | ? |  |  |
| Modified SalesTypes.php to always convert the sales type code to upper case. |  | ? | ? |  |  |
| Modified SalesPeople.php to always convert the salesperson code to upper case. |  | ? | ? |  |  |
| Modified SelectProduct.php to always convert the keywords or product code extract to upper case. |  | ? | ? |  |  |
| Modified Locations.php to clear form variables for adding new locations and after updates to existing locations being modified. Made location codes always upper case. |  | ? | ? |  |  |
| Some prawn changed the access rights of demonstration user so that one could only get into the order entry and account status screens. Made an admin user that has the rights to add or delete new users - prohibiting such destructive folks from malicious tricks in the sourceforge demo system. |  | ? | ? |  |  |
| Modified SelectOrderItems.php to place the cancel whole order button away from the main entry - field testing showed that it was too easy to delete the order by hitting enter when the focus was on this button. |  | ? | ? |  |  |
| New script to print bank reconciliation based on current gl balance of the bank account, showing bank payments not yet presented and deposits not yet cleared to arrive at a calculated bank statement balance. Links back to matching receipts and matching payments script. Also new link on index.php under GL inquiries. |  | ? | ? |  |  |
| Added a link in index.php to allow re-prints of deposit listings produced from entry of receipts. |  | ? | ? |  |  |
| New script for matching deposits and payments against bank statements, for the purposes of reconciling to bank statements. The script does double duty for matching deposits and payments, but must be called with either Receipts or Payments. Added appropriate links to this script in index.php one for Deposits matching and one for Payment Matching. |  | ? | ? |  |  |
| Bug fix to GLTrialBalance.php the DefaultPeriodTo was not picking up the last defined period less one as was intended. |  | ? | ? |  |  |
| Dropped the tables ClearedPayments and ClearedReceipts - amount cleared against banktrans now recorded inside the one banktrans table |  | ? | ? |  |  |
| General Ledger Journal entry form created. New journal class defintion to hold the transaction before committing to the DB. |  | ? | ? |  |  |
| Reversing journals entered on same form by changing journal type to reversing, default is normal. |  | ? | ? |  |  |
| Prohibited journal entries directly to bank accounts since no banktrans record would be created for matching off statements. |  | ? | ? |  |  |
| Bank gl transactions must be entered as either a payment or a receipt. |  | ? | ? |  |  |
| Removed facility to be able to modify the cost data for the part in the inventory item maintenance form since this did not create the necessary GL journal if integrated stock journals are active. Cost changes must be effected through the dedicated form for this purpose. |  | ? | ? |  |  |
| Entry of a payment to a general ledger act already defined as a bank account (be it a different bank account or the same bank account as the payment is from) now creates the necessary banktrans record for the receipt into the other (or same) bank account. |  | ? | ? |  |  |
| Entry of a receipt to a general ledger act defined as a bank account now creates the necessary banktrans record for the payment from the other bank account as above. |  | ? | ? |  |  |

## v0.1.6 - 2003-03-22

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Fixed bug in SelectProduct.php script that overstated stock on hand.Sadly I couldn't figure a way to show the sales order demand and resolve this problem so the selection of parts no longer shows current sales order demand. |  | ? | ? |  |  |
| Additional error trapping to check that a stock location is not a default stock location for an existing customer branch before allowing deletion.Also, an additional error trap to check that the default_shipper in config.php cannot be deleted from the shipper maintenance form |  | ? | ? |  |  |
| Further error trap to check that shipping companies that have debtortrans records (ie invoices created using them) cannot be deleted. |  | ? | ? |  |  |

## v0.1.5 - Date ?

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| User created with Access level less than or equal to 10 and with a customer code and branch code selected will be able to view only the account details for the customer code selected with invoice reprints - only for the customers transactions. Such users can also enter orders for the customer code and branch defined against them as well. This allows customers to be given access to the system to view only their own details. |  | ? | ? |  |  |
| Deleted PrintInvoice.php superseeded by PrintCustTrans.php which deals with credits and invoices |  | ? | ? |  |  |
| logicworks.ini file changed name and all references to in other files to config.php to avoid displaying sensitive info in the config file over the net ... duh! Thanks Ryan Fox! |  | ? | ? |  |  |
| Sales Types - field to say if the sales type updates stock or not has been removed. This was a carry over from some old logic that was superseeded. |  | ? | ? |  |  |
| It was possible to add a sales type with a blank sales type code - now corrected |  | ? | ? |  |  |
| Editing of a sales type was not possible - fixed. |  | ? | ? |  |  |


## v0.1.4 - 2003-02-23

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| New page GLTrialBalance.php to show a trial balance with movements for a month and a period |  | ? | ? |  |  |
| GLPostings.inc to effect updates to ChartDetails for GLTrans created |  | ? | ? |  |  |
| New variable in config.php to define the company's year end |  | ? | ? |  |  |
| ConfirmDispatch_Invoice.php - changed date format of date of dispatch field to refer to the $DefaultDateFormat invoices commited with dates in US format became 00/00/0000. Now corrected. |  | ? | ? |  |  |
| ChartDetails table - BFwd and BFwdBudget fields created. |  | ? | ? |  |  |
| GLTrans table - removed reversal flag - reversing journals now create the reversal in the following period at the time of entry no flag is necessary since the open period system with no rollovers requires creation of reversals at the time. |  | ? | ? |  |  |
| Also, dropped field ShiptRef - transactions now created in seperate tablef |  | ? | ? |  |  |
| Payment.php - was not unsetting the fields when a new GL analysis line was added to the payment. |  | ? | ? |  |  |
| Periods table - LastDateInPeriod field changed from DateTime to Date. |  | ? | ? |  |  |

## v0.1.3 - 2003-02-01

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Payments.php insert of BankTrans record now records Currency and TransNo and Type correctly. |  | ? | ? |  |  |
| Allocation of supplier payments and credits - creating GL entries and exchange diffs. |  | ? | ? |  |  |
| Made SuppAllocs table mirror CustAllocs refering to the TransID rather than the Type and TransNo as previously. |  | ? | ? |  |  |
| SupplierAllocations.php and links from SupplierInquiry.php |  | ? | ? |  |  |
| Changed DateFunctions.inc to use the global DefaultDateFormat variable in config.php to determine format of dates required. Changed demo default to US format 'm/d/Y' |  | ? | ? |  |  |
| SelectOrderItems.php can now select items with no prices set up for them - including kits |  | ? | ? |  |  |
| Customer special prices now comes up as a seperate item in the search - the system will show the normal price part as well as the special price one. |  | ? | ? |  |  |
| SelectProduct.php removed the link to special customer prices in the case of a kit set parts. |  | ? | ? |  |  |
| BOM.php - BOM Maintnenance - Effective dates were modified by the code to be in SQL format on submit and appeared corrupt on the next viewing. Fixed. |  | ? | ? |  |  |
| Effective dates were not displayed in accordance with DefaultDateFormat - fixed. |  | ? | ? |  |  |
| Now check to ensure components cannot be on the BOM more than once. |  | ? | ? |  |  |
| Pass db handle by reference to check recursion function to eliminate errors. |  | ? | ? |  |  |
| BOM table modified EffectiveTo and EffectiveAfter to be Date fields not DateTime as was. |  | ? | ? |  |  |
| Shippers.php - Shipping company maintenance - deletion of shipping companies failed - fixed. |  | ? | ? |  |  |
| Prices.php - should not be able to enter prices against a kit since a kit is exploded into its components. |  | ? | ? |  |  |
| SelectProduct.php - dont show prices link for kitset products. |  | ? | ? |  |  |
| Modified PDF_TranPageHeader.inc to refer to YPos rather than hard code vertical coordinates for credit notes so that alternate paper sizes can be accomodated. Fixed invoices 20 Jan forgot credit notes. |  | ? | ? |  |  |
| Created SuppCreditGRNs.php - this page allows selection of goods received records for entering supplier credits against. Similar to the SuppInvGRNs.php but shows all goods received records after a specified date. The invoice entry page only shows goods received records that are yet to be fully invoiced. The credit note needs to show all goods received records. |  | ? | ? |  |  |
| Now have SupplierCredit.php working creating GL entries as appropriate and shipment charge records etc. |  | ? | ? |  |  |

## v0.1.2 - 2003-01-20

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Modified PDF_TranPageHeader.inc to refer to YPos rather than hard code vertical coordinates so that alternate paper sizes can be accomodated. (only fixed invoices - credit notes fixed 28th Jan 03) |  | ? | ? |  |  |
| Noted that pdf reports for Purchase orders did not print in Konqueror but worked fine in Netscape and Opera 6.1 |  | ? | ? |  |  |
| No modifications - bug in Konqueror |  | ? | ? |  |  |
| SupplierInvoice.php - stopped the script trying to update shipment 0 even though no shipment defined. |  | ? | ? |  |  |
| WWW_Users table now has page size field varchar(20) was char(2) to allow proper description of page size. |  | ? | ? |  |  |
| There was a mismatch between PDFStarter.inc switch statements and the stored values in WWW_Users table. |  | ? | ? |  |  |
| Also modified WWW_Users.php to allow selection of other page sizes. |  | ? | ? |  |  |
| SelectProduct.php now checks for the existence of any stock categories before proceding and insists on some beng set up showing a link to the StockCategories.php script. |  | ? | ? |  |  |
| CustomerBranches.php: Check for existence of Sales Types, Sales Areas and Stock Locations before allowing input of branches with a null items in these fields. |  | ? | ? |  |  |
| SalesAnalReptCols.php: Bug in sales analysis column definitions |  | ? | ? |  |  |
| BudgetOrActual field was used the opposite way in creating reports script SalesAnalysis_UserDefined.php. |  | ? | ? |  |  |
| Modified the select box BudgetOrActual to be 1 for Budget and 0 for Actual (NOT budget). |  | ? | ? |  |  |
| Also modified so that the returned 1 or 0 interpreted correctly on display of the column details from the printf statement. |  | ? | ? |  |  |

## v0.1.1 - 2003-01-11

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Initial file set |  |  |  |  |  |


[v4.14]: https://github.com/webERP-team/webERP/compare/v4.13.1...v4.14
[v4.13.1]: https://github.com/webERP-team/webERP/compare/v4.13...v4.13.1
[v4.13]: https://github.com/webERP-team/webERP/compare/v4.12.3...v4.13
[v4.12.3]: https://github.com/webERP-team/webERP/compare/v4.12.2...v4.12.3
[v4.12.2]: https://github.com/webERP-team/webERP/compare/v4.12.1...v4.12.2
[v4.12.1]: https://github.com/webERP-team/webERP/compare/v4.12...v4.12.1
[v4.12]: https://github.com/webERP-team/webERP/compare/v4.11.5...v4.12
[v4.11.3]: https://github.com/webERP-team/webERP/compare/v4.11.2...v4.11.3
[v4.11.0]: https://github.com/webERP-team/webERP/compare/v4.10.1...v4.11.0
[v4.08.1]: https://github.com/webERP-team/webERP/compare/v4.08...v4.08.1
[v4.08]: https://github.com/webERP-team/webERP/compare/v4.07...v4.08
[v3.11]: https://github.com/webERP-team/webERP/compare/v3.10...v3.11
[v3.10]: https://github.com/webERP-team/webERP/compare/v3.09...v3.10
[v3.09]: https://github.com/webERP-team/webERP/compare/v3.08...v3.09
[v3.05]: https://github.com/webERP-team/webERP/compare/v3.04...v3.05
[v3.02]: https://github.com/webERP-team/webERP/compare/v3.01...v3.02
[v3.01]: https://github.com/webERP-team/webERP/compare/v3.0...v3.01

[Semantic Versioning]: http://semver.org/spec/v2.0.0.html
[Keep a Changelog]: http://keepachangelog.com/en/1.0.0/
[CHANGELOG.md]: CHANGELOG.md
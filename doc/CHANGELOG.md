# Changelog
All notable changes to the webERP project will be documented in this file.  
The format is based on [Keep a Changelog], and this project adheres to [Semantic Versioning].  
For changelogs earlier than v4.14.1, please refer to [CHANGELOG_ARCHIVE.md].

## Guidelines
- Keep descriptions as short and concise as possible.  
- If including html tags in your description, please surround them with back ticks.  
- The commit Type, will typically be one of the following: `Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`  
- Enter dates in format yyyy-mm-dd.  
- Add links to the associated GitHub commit in Details column.  
- Add links to supporting info in Ref column, such as Forum posts, Mailing List archives or GitHub issues.  

## [Unreleased]

| Description | Type | Author | Date | Details | Ref |
|:-----------:|:----:|:------:|:----:|:-------:|:---:|
| Fix rounding of shrink factor in Stocks.php | Fixed | Paul Becker | 2018-11-10 | [View](https://github.com/webERP-team/webERP/commit/) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8240) |
| PDFWOPrint.php: Fix notices, reported in the forums by William Hunter | Fixed | PaulT | 2018-11-06 | [View](https://github.com/webERP-team/webERP/commit/9786f3a5c05b3be1c26827b35ca92f62485d77ee) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8233) |
| New script to enter timeshees with the new table timesheets| Added | Phil | 2018-10-28 |  |  |
| Updates to the manual to describe labour functionality| Added | Phil | 2018-10-20 | [View](https://github.com/webERP-team/webERP/commit/b466bb45d20d1510947bd64048fcaec3c55e6f78) |  |
| Fix error in prnMsg display - DB errors were not reported - had to define $Messages as global inside DB_query function| Fixed | Phil | 2018-10-20 |  |  |
| New script to add employees Employees.php with new table employees for the purposes of time-sheet entry| Added | Phil | 2018-10-20 | [View](https://github.com/webERP-team/webERP/commit/c0bea42348118135c9f19230d8048c1d46d4e2e9) |  |
| Add new config option to allow shortcut menus to be disabled as they can confuse some folks| Changed | Phil | 2018-10-20 | [View](https://github.com/webERP-team/webERP/commit/c0bea42348118135c9f19230d8048c1d46d4e2e9) |  |
| Add manual links for MRP scripts | Changed | Phil | 2018-10-20 | [View](https://github.com/webERP-team/webERP/commit/c0bea42348118135c9f19230d8048c1d46d4e2e9) |  |
| Eliminate pdf_append/ and stockmaster appendfile column | Removed | Paul Becker / Tim | 2018-09-09 | [View](https://github.com/webERP-team/webERP/commit/e0daaed8a9859f41f50821a3339de627b277d954) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8206) |
| Fix filename output in PDFQALabel | Fixed | Paul Becker | 2018-09-02 | [View](https://github.com/webERP-team/webERP/commit/d54dae8c60530b80591081778810e19709e47dbe) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8198) |
| Fix calculation descrepancy in Z_CheckDebtorsControl | Fixed | Paul Becker | 2018-09-02 | [View](https://github.com/webERP-team/webERP/commit/bc14d0d28e4356139d4a05a56d97761c47df1eaf) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8188) |
| Add order number into file name | Change | Paul Becker | 2018-08-28 | [View](https://github.com/webERP-team/webERP/commit/e5fd39ed26b0768ccfbe7dba70e0d633de8635c9) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8194) |
| Log output not working (reported by Michelle) | Fixed | Tim Schofield | 2018-08-28 | [View](https://github.com/webERP-team/webERP/commit/18b96699565897a169e47f588577fda5c50edd47) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8196) |
| Update DB version info (missing with last release) | Changed | Rafael | 2018-08-25 | [View](https://github.com/webERP-team/webERP/commit/fd0cc7a9633ced890a0b15d7184a94feaa5fc42f) |  |
| Add a input check for the sortby input | Fixed | Exson | 2018-07-29 | [View](https://github.com/webERP-team/webERP/commit/593252c64c0b38cc03d661ca6847a1e5096a5222) |  |
| Add script to set period of GL transactions where the period has not been set correctly | Added | Phil | 2018-07-01 | [View](https://github.com/webERP-team/webERP/commit/6136b5a96b564b1add42d2dd3bbe22936f00e476) |  |
| Relocate tcpdf barcodes directory | Fixed | PaulT | 2018-06-28 | [View](https://github.com/webERP-team/webERP/commit/23fd7ff52) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8173&pid=14904#pid14904) |
| Fix $Prefix use in footer.php | Fixed | alanmi3383 | 2018-06-28 | [View](https://github.com/webERP-team/webERP/commit/c1093765f) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8173&pid=14897#pid14897) |
| Add Tim's default shipper for suppliers feature | Added | Tim Schofield | 2018-06-23 | [View](https://github.com/timschofield/webERP-svn/commit/8f8ea3d9c) | [Forum](http://www.weberp.org/forum/showthread.php?tid=2696) |
| Add Tim's defaultgl column feature | Added | Tim Schofield | 2018-06-18 | [View](https://github.com/webERP-team/webERP/pull/67/files) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8169&pid=14931#pid14931) |
| DailyBankTransactions.php (updated script) | Changed | Tim Schofield | 2018-06-18 | [View](https://github.com/webERP-team/webERP/commit/ed1e5e0cb) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8179) |
| MRP.php fix reorder qty insert calculation | Fixed | BrianTMG | 2018-06-17 | [View](https://github.com/webERP-team/webERP/commit/ab6308dcf) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8175) |
| DailyBankTransactions.php calculation fix | Fixed | Paul Becker | 2018-06-16 | [View](https://github.com/webERP-team/webERP/commit/7d39e0bfd) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8178) |

## [v4.15] - 2018-05-20

| Description | Type | Author | Date | Details | Ref |
|:-----------:|:----:|:------:|:----:|:-------:|:---:|
| Upgrade weberpchina.sql file and modify htmlspecialcharts parameter in footer.php |  | Exson Qu | 2018-05-15 | [View](http://github.com/webERP-team/webERP/commit/62a4571fb) |  |
| Change module alias from "orders" to "Sales" to standardise in MainMenuLinksArray.php |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/30115ebda) |  |
| Groups the INSERT INTO sentences in SQL upgrade script |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/7ad7340cc) |  |
| Various improvements to manual |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/cfce65413) |  |
| Add script description and ViewTopic to SelectPickingLists.php |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/62c7a5fb0) |  |
| Add ViewTopic and BookMark to GeneratePickingList.php |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/88a43575f) |  |
| Add "id" for $BookMark = 'CounterReturns' in ManualSalesOrders.html |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/a12be5b00) |  |
| Add script description, ViewTopic and BookMark in CounterReturns.php |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/80243e2d1) |  |
| Add info to General Ledger manual |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/a1c8fac82) |  |
| Add "id" for $BookMark = 'SelectContract' in SelectContract.php |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/fa43031fb) |  |
| Add "id" for $BookMark = 'SelectContract' in ManualContracts.html |  | Rafael Chacón | 2018-05-08 | [View](http://github.com/webERP-team/webERP/commit/4d75b86c5) |  |
| Add 'Bank Account Balances' and 'Graph of Account Transactions' info to manual. Reorganise 'Inquiries and Reports' and 'Maintenance' of 'General Ledger" chapter of manual. |  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/84aa988b3) |  |
| Add script description and BookMark in BankAccountBalances.php |  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/21ea3ac27) |  |
| Add section for the "Graph of Account Transactions" script in ManualGeneralLedger.html |  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/078c13a60) |  |
| Groups "INSERT INTO `scripts`" sentences and completes empty `description` fields in SQL upgrade script |  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/c649a57fb) |  |
| Add script description, also fix $ViewTopic in GLAccountGraph.php|  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/fdfda2148) |  |
| Update Spanish translation |  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/41380a6c3) |  |
| Add script description in PDFShipLabel.php |  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/8e6513bb8) |  |
| Add script description in PDFAck.php |  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/4cd593557) |  |
| Add script description in GeneratePickingList.php |  | Rafael Chacón | 2018-05-07 | [View](http://github.com/webERP-team/webERP/commit/4857c4a7e) |  |
| Add script description, ViewTopic and BookMark in PickingLists.php|  | Rafael Chacón | 2018-05-06 | [View](http://github.com/webERP-team/webERP/commit/aff58aa7d) |  |
| Rebuild languages files |  | Rafael Chacón | 2018-05-06 | [View](http://github.com/webERP-team/webERP/commit/ca7ee2360) |  |
| Add script description, ViewTopic and BookMark in SelectPickingLists.php |  | Rafael Chacón | 2018-05-06 | [View](http://github.com/webERP-team/webERP/commit/1c184e09c) |  |
| Update Spanish translation |  | Rafael Chacón | 2018-05-04 | [View](http://github.com/webERP-team/webERP/commit/511eb2e29) |  |
| Fixed bank account and related data visible to an unauthorised user in Dashboard.php | Fixed | Paul Becker | 2018-05-02 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8161) |
| Fix the bug that accountgroup validation used the wrong table and field reported by Laura | Fixed | Exson Qu | 2018-04-30 | [View](https://github.com/webERP-team/webERP/commit/56b66d466e3fdd4d5d55c8e1827a3ab74d69a4b8) |  |
| Added a utility script to fix 1c allocations in AR - a GL journal will be required for the total of debtor balances changed as a result for the control account to remain in balance Z_Fix1cAllocations.php | Added | Phil Daintree | 2018-04-28 | [View](https://github.com/webERP-team/webERP/pull/45/commits/756546887bac32f3ce5d2c357b7e79f7366c0391) |  |
| Added the latest phpxmlrpc code version 4.3.1 - using the compatibility layer though - see https://github.com/gggeek/phpxmlrpc | Changed | Phil Daintree | 2018-04-28 | [View](https://github.com/webERP-team/webERP/pull/45/commits/756546887bac32f3ce5d2c357b7e79f7366c0391) |  |
| Fixed failure to issue invoice for customer reference is more than 20 characters | Fixed | Xiaobotian | 2018-04-27 | [View](https://github.com/webERP-team/webERP/commit/9a7f83ac16e858ac50362cb1e90a74adbdb9f419) |  |
| Added latest SQL update script to UpgradeDatabase.php | Changed | Deibei | 2018-04-27 | [View](https://github.com/webERP-team/webERP/commit/d02430f20e6c044131828f5e27a82471e79f1723) |  |
| Change log updated and formatted in tabular markdown | Changed | Andrew Couling | 2018-04-27 | [View](https://github.com/webERP-team/webERP/commit/bcb543774885bad573081c7798140193665b9bd1) |  |
| Fix the bug of wrong affected scope of bom changing in BOMs.php | Fixed | Exson Qu | 2018-04-26 | [View](http://github.com/webERP-team/webERP/commit/9e8585e91) |  |
| Rebuild languages files | Changed | Rafael Chacon | 2018-04-24 | [View](http://github.com/webERP-team/webERP/commit/1cdd72b5f) |  |
| Minor changes to GeneratePickingList.php | Changed | Rafael Chacon | 2018-04-24 | [View](http://github.com/webERP-team/webERP/commit/ccaf2c404) |  |
| Rebuild languages files Part 3 | Changed | Rafael Chacon | 2018-04-24 | [View](http://github.com/webERP-team/webERP/commit/417971114) |  |
| Rebuild languages files Part 2 | Changed | Rafael Chacon | 2018-04-24 | [View](http://github.com/webERP-team/webERP/commit/bee02030a) |  |
| Rebuild languages files Part 1 | Changed | Rafael Chacon | 2018-04-24 | [View](http://github.com/webERP-team/webERP/commit/04ab5753c) |  |
| Rebuild languages files | Changed | Rafael Chacon | 2018-04-24 | [View](http://github.com/webERP-team/webERP/commit/9b2b54858) |  |
| Correct menu link caption | Fixed | PaulT | 2018-04-20 | [View](http://github.com/webERP-team/webERP/commit/c9fb416a7) |  |
| Fixed the cost calculation bug in Work Order Costing for different standcost of the same stock and use Total Cost Variance to calculate cost variance instead of Total Cost variance for WAC' | Fixed | Exson Qu | 2018-04-18 | [View](http://github.com/webERP-team/webERP/commit/74ac6b4a5) |  |
| New script to graph GL account | Added | Paul Becker | 2018-04-17 | [View](http://github.com/webERP-team/webERP/commit/a930ba0b4) |  |
| Redo changes lost in the last commit | Added | Tim Schofield | 2018-04-11 | [View](http://github.com/webERP-team/webERP/commit/3548fa1b1) |  |
| Add in checkbox for showing zero stocks | Added | Tim Schofield | 2018-04-10 | [View](http://github.com/webERP-team/webERP/commit/74592f0e1) |  |
| Show only non-zero balances, and whether controlled item. | Added | Paul Becker | 2018-04-10 | [View](http://github.com/webERP-team/webERP/commit/a14d3d7b7) |  |
| Fixes incorrect counter numbering of form elements | Fixed | Tim Schofield | 2018-04-10 | [View](http://github.com/webERP-team/webERP/commit/3fcaa6b92) |  |
| Remove tabindex attributes | Changed | Tim Schofield/Jeff Harr | 2018-04-06 | [View](http://github.com/webERP-team/webERP/commit/7d6cec6a2) |  |
| Add XML files to new company copy | Added | PaulT | 2018-04-03 | [View](http://github.com/webERP-team/webERP/commit/5072343f7) |  |
| Fixes to the database files | Fixed | Ap Muthu | 2018-04-03 | [View](http://github.com/webERP-team/webERP/commit/f4d6ff0ac) |  |
| Fix messages not being shown | Fixed | Ap Muthu | 2018-04-03 | [View](http://github.com/webERP-team/webERP/commit/0fcf1c414) |  |
| Synch sqls with upgrade sqls #28 | Fixed | Ap Muthu | 2018-04-03 | [View](http://github.com/webERP-team/webERP/commit/83d8e9ba4) |  |
| Wrong $MysqlExt value in installer | Fixed | Ap Muthu | 2018-04-03 | [View](http://github.com/webERP-team/webERP/commit/eda1245c3) |  |
| Add session name to avoid conflicts | Fixed | PaulT | 2018-04-02 | [View](http://github.com/webERP-team/webERP/commit/2bf01bf9e) |  |
| Remove more unused $db parameters | Deprecated | PaulT | 2018-04-01 | [View](http://github.com/webERP-team/webERP/commit/517f3ed2f) |  |
| Use new period selection functions | Added | Tim Schofield/Paul Becker | 2018-03-31 | [View](http://github.com/webERP-team/webERP/commit/4d3e7d35c) |  |
| If config.php is already in place the error message will never be displayed, so print direct to screen | Fixed | Ap Muthu | 2018-03-31 | [View](http://github.com/webERP-team/webERP/commit/e6e56ac8c) |  |
| Correct invalid function name and clean up code Files Changed: api/api_debtortransactions.ph api/api_stock.php api/api_workorders.php Correct function call from GetNextTransactionNo() to GetNextTransNo() Change variable names to conform to coding standards Change code layout to conform to coding standards Fixes issue no #24 | Fixed | Tim Schofield | 2018-03-29 | [View](http://github.com/webERP-team/webERP/commit/cec3387b2) | [Issue](https://github.com/timschofield/webERP-svn/issues/24) |
| Meet coding standards and more commentary. | Changed | PaulT | 2018-03-27 | [View](http://github.com/webERP-team/webERP/commit/e04d4580b) |  |
| Files to ignore | Changed | Tim Schofield | 2018-03-22 | [View](http://github.com/webERP-team/webERP/commit/22045a5b2) |  |
| Improvements to the period selection functions: 1 Changed variable names to conform to coding standards 2 Added  options to show financial years as well as calendar years 3 Added extra parameter to allow only some options to appear in drop down list 4 Added default case to prevent dropping through without anything being selected | Added | Tim Schofield | 2018-03-20 | [View](http://github.com/webERP-team/webERP/commit/1fd7a0d8e) |  |
| Misspelling of Aged Suppliers Report | Fixed | PaulT/Paul Becker | 2018-03-16 | [View](http://github.com/webERP-team/webERP/commit/c8dc092fc) |  |
| DeliveryDetails.php: Sales Order required date not reflected in Work Order. | Fixed | PaulT/Jeff Harr | 2018-03-16 | [View](http://github.com/webERP-team/webERP/commit/d58fb9632) |  |
| GLAccountInquiry: Fix that the script does not automatically show data | Fixed | PaulT/Paul Becker | 2018-03-16 | [View](http://github.com/webERP-team/webERP/commit/02b212294) |  |
| Add from and to period in links to GLAccountInquiry.php | Added | Tim Schofield | 2018-03-16 | [View](http://github.com/webERP-team/webERP/commit/f492056dc) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8138&pid=14667#pid14667) |
| Consider discount with SalesGraph | Added | PaulT/Paul Becker | 2018-03-16 | [View](http://github.com/webERP-team/webERP/commit/acfacde4b) |  |
| Remove unused $db parameter | Deprecated | PaulT | 2018-03-16 | [View](http://github.com/webERP-team/webERP/commit/ff9c4106e) |  |
| Dismissible notification functionality added. New icons and styling added to all themes. Messages now stored in an array before being printed. Assistance from @timschofield and @TurboPT. | Added | Andrew Couling | 2018-03-15 | [View](http://github.com/webERP-team/webERP/commit/488a32560) |  |
| Remove .gitignore from branch | Changed | PaulT | 2018-03-13 | [View](http://github.com/webERP-team/webERP/commit/8294000b6) |  |
| Move hidden input | Changed | PaulT | 2018-03-13 | [View](http://github.com/webERP-team/webERP/commit/2c915bd82) |  |
| "Periods in GL Reports" mod | Added | PaulT/Paul Becker | 2018-03-12 | [View](http://github.com/webERP-team/webERP/commit/1d5e2e28d) |  |
| code review GLPosting.php and Payments.php | Changed | Phil Daintree | 2018-03-12 | [View](http://github.com/webERP-team/webERP/commit/e4d5e9cb7) |  |
| Removed unused variable | Changed | PaulT | 2018-03-09 | [View](http://github.com/webERP-team/webERP/commit/1411c48fb) |  |
| Link updates to GLAccountInquiry | Changed | PaulT/Paul Becker | 2018-03-09 | [View](http://github.com/webERP-team/webERP/commit/ee91eb111) |  |
| Add duedate to ORDERY BY clause | Fixed | PaulT/Paul Becker | 2018-03-09 | [View](http://github.com/webERP-team/webERP/commit/60d85e070) |  |
| Reportwriter mods, part 3 | Changed | PaulT/Paul Becker | 2018-03-09 | [View](http://github.com/webERP-team/webERP/commit/d94e197ab) |  |
| Fixes error that allowed a transaction to be authorised and posted multiple times by hitting page refresh | Fixed | Tim Schofield | 2018-03-09 | [View](http://github.com/webERP-team/webERP/commit/683a630e1) |  |
| Logout cookie handling | Changed | PaulT | 2018-03-09 | [View](http://github.com/webERP-team/webERP/commit/b163d75c1) |  |
| Reportwrite mods, part 2 | Changed | PaulT/Paul Becker | 2018-03-08 | [View](http://github.com/webERP-team/webERP/commit/f3aeea5dc) |  |
| Refreshed README file in markdown format | Added | Andrew Couling | 2018-03-09 | [View](http://github.com/webERP-team/webERP/commit/c075e5765) |  |
| Reportwriter mods. | Changed | PaulT/Paul Becker | 2018-03-06 | [View](http://github.com/webERP-team/webERP/commit/21756696c) |  |
| Show stock adjustments and internal stock requests | Changed | Tim Schofield | 2018-03-06 | [View](http://github.com/webERP-team/webERP/commit/d07055458) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8111) |
| Remove the 'alt' attribute from date inputs | Changed | PaulT | 2018-03-04 | [View](http://github.com/webERP-team/webERP/commit/f7bac6fe8) |  |
| Update MiscFunctions.js | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/63f205820) |  |
| Update DatabaseTranslations.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/a2947cc93) |  |
| Update UPGRADING.txt | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/bafed3526) |  |
| Update manual.css | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/98b5678b9) |  |
| Update INSTALL.txt | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/2bbda6c36) |  |
| Update print.css | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/40b3162f9) |  |
| Update login.css for each theme | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/26df5c58c) |  |
| Update default.css for each theme | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/5f6e798f6) |  |
| Update PDFQuotationPortraitPageHeader.inc | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/36d7f47c7) |  |
| Update PDFQuotationPageHeader.inc | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/086beaaee) |  |
| Update FailedLogin.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/46b9612ed) |  |
| Update CurrenciesArray.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/da99d31b5) |  |
| Update Z_poRebuildDefault.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/01284f7c4) |  |
| Update Z_ChangeStockCode.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/113ab5fcc) |  |
| Update SupplierAllocations.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/5f6c046fc) |  |
| Update PDFQuotationPortrait.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/5d6df7dd3) |  |
| Update PDFQuotation.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/c71a8fc6d) |  |
| Update CustomerAllocations.php | Changed | PaulT | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/a6c962c7c) |  |
| Remove ID svn lines | Changed | Phil Daintree | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/b1f012819) |  |
| Move dashboard link | Changed | PaulT | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/529be6951) |  |
| Remove sourceforge image link - broken | Deprecated | Phil Daintree | 2018-03-03 | [View](http://github.com/webERP-team/webERP/commit/020c9edb0) |  |
| Replace a $Bundle reference with $SItem. | Changed | PaulT | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/d798a999f) |  |
| Petty Cash - Receipt upload filenames now hashed for improved security & new expenses claim field 'Business Purpose' | Added | Andrew Couling | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/d63ecaeca) |  |
| Updates to README.txt | Changed | Phil Daintree | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/24e1e8e40) |  |
| Moved README.txt to root directory | Changed | Phil Daintree | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/60ce8504f) |  |
| Delete README.md | Changed | PhilDaintree | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/bb500beb8) |  |
| Rename README.txt to README.md | Changed | Phil Daintree | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/7fa8c263d) |  |
| Add readme | Added | Phil Daintree | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/33ee42363) |  |
| Update PrintCustTrans.php | Changed | PaulT | 2018-03-02 | [View](http://github.com/webERP-team/webERP/commit/0235627f6) |  |
| Update StockLocMovements.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/b2080a61a) |  |
| Revert "Update StockLocMovements.php" | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/8f71a30ce) |  |
| Revert "Revert "Update StockLocMovements.php"" | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/18fb22add) |  |
| Revert "Update StockLocMovements.php" | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/d8e040941) |  |
| Revert "Update StockLocMovements.php" | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/c38a2877c) |  |
| Update ConnectDB_postgres.inc | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/76704de09) |  |
| Update ConnectDB_mysqli.inc | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/60a112ca6) |  |
| Update ConnectDB_mysql.inc | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/92f72c7b1) |  |
| Update header.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/909af543c) |  |
| Update GetConfig.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/ee32394d8) |  |
| Update GetConfig.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/d04c256c0) |  |
| Update TestPlanResults.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/adeddb122) |  |
| Update StockMovements.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/cac9b6a5d) |  |
| Make the email address a mailto: link as in SelectSupplier.php. | Changed | Tim Schofield | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/f15783e07) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8109) |
| Update StockLocMovements.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/f8c1bb5a1) |  |
| Revert "Update StockLocMovements.php" | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/d716178a5) |  |
| Revert "Update StockLocMovements.php" | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/034b5a2e4) |  |
| Revert "Revert "Update StockLocMovements.php"" | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/671837690) |  |
| Revert "Update StockLocMovements.php" | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/bb38776d6) |  |
| Update StockLocMovements.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/011887797) |  |
| Update StockLocMovements.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/d496ef8ef) |  |
| Update SelectPickingLists.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/2f99a1ee1) |  |
| Update PickingLists.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/02bfcd469) |  |
| Update MRPPlannedPurchaseOrders.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/c8282670a) |  |
| Update BOMs_SingleLevel.php | Changed | PaulT | 2018-03-01 | [View](http://github.com/webERP-team/webERP/commit/c296fa1e5) |  |
| Update WorkOrderCosting.php | Changed | PaulT | 2018-02-28 | [View](http://github.com/webERP-team/webERP/commit/191833317) |  |
| Update EDIMessageFormat.php | Changed | PaulT | 2018-02-28 | [View](http://github.com/webERP-team/webERP/commit/a370b1119) |  |
| Update PO_SelectPurchOrder.php | Changed | PaulT | 2018-02-28 | [View](http://github.com/webERP-team/webERP/commit/352151ecd) |  |
| Changes to tables to work with the improved table sorting routine | Changed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/8df9ea96b) |  |
| Add Tim's improved SortSelect() js function replacement. Change also requires `<thead>`, `<tbody>`, and (if needed) `<tfoot>` tags applied to tables that have sorting. Also, change removes the 'alt' attribute from date inputs (handling replaced by commit 7974) within these modified files. | Added | Tim Schofield/PaulT | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/cda963727) | [Forum](http://www.weberp.org/forum/showthread.php?tid=7918) |
| InventoryPlanning.php, InventoryValuation.php, StockCheck.php: Fix view page source message "No space between attributes" reported in Firefox | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/07452b09c) |  |
| Sanitize scripts name in footer.inc and forbidden the use of InputSerialItemsSequential.php without login. | Changed | Exson Qu | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/bc05bb017) |  |
| Stocks.php: Fix navigation bar handling to avoid stockid loss and also disable navigation submit when at the first (or last) item. Change also adds a closing table tag, removes an extra double quote from two attributes, and a minor message layout improvement. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/e012d6364) |  |
| PO_SelectOSPurchOrder.php: Derived from Tim's code: add default current dates. (there may not yet be any purchorders records) / PaulT: do not show the order list table when there are no records to show. (avoids a table heading output without any associated row data) | Changed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/7b3107223) |  |
| MiscFunctions.js: Set the calendar click and change handlers to reference the localStorage DateFormat instead of the element's "alt" attribute value. (Know that this update requires the localStorage change applied with commit 7973) | Changed | Tim Schofield | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/5a136ea9c) |  |
| header.php: Set the DOCTYPE to html5 declaration format, update the meta tag with Content-Type info, and add localStorage with DateFormat and Theme for upcoming changes to table column sorting and calendar handling improvements. | Changed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/1f2dd1dbf) |  |
| CustomerAllocations.php: Minor code shuffle to fix view page source message "Start tag 'div' seen in 'table'" reported in Firefox | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/2216b0d0b) |  |
| Customers.php, ShopParameter.php: Fix view page source message "No space between attributes" reported in Firefox. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/8f51f42b0) |  |
| Labels.php: Remove extra closing `</td>` `</tr>` tag pair. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/5daf2c79f) |  |
| FixedAssetLocations.php: Move closing condition brace to cover entire table output to avoid a stray closing table tag output if the condition is not met. Also, replace some style attributes with equivalent CSS. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/4167599b1) |  |
| MaintenanceUserSchedule.php: Fix closing tag mismatch. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/5fc33c882) |  |
| GLJournalInquiry.php: Add missing = to value attribute. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/63d0e7ee4) |  |
| Fixed the DB_escape_string bug for Array in session.inc and destroy cookie while users log out in Logout.php | Fixed | Exson Qu | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/631d6aebc) |  |
| header.php: Add link to the Dashboard in the AppInfoUserDiv. | Added | Paul Becker | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/ee8e7eb3e) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8100) |
| Remove unused $db parameter from many functions within the /api area. | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/634c3f93b) |  |
| upgrade4.14.1-4.14.2.sql: Add SQL update to support commit 7961. | Changed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/5935b1e07) |  |
| AgedControlledInventory.php: Add UOM to output. | Added | Paul Becker | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/905e650ba) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8091&pid=14286#pid14286) |
| Z_ChangeSalesmanCode.php: New script to change a salesman code. | Added | Paul Becker | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/e840b5301) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8094) |
| Minor change to remove number from input field committed with 7946, and add attributes on two input fields. | Changed | Paul Becker | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/3e220cf14) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8089&pid=14266#pid14266) |
| Remove $db parameter from PeriodExists(), CreatePeriod(), CalcFreightCost(), CheckForRecursiveBOM(), DisplayBOMItems() and a few other functions. | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/69f9dc4a5) |  |
| InternalStockRequest.php: Address a few issues reported by Paul B: Fix Previous/Next handling, table sorting, wrong on-order quantities, and apply the user's display records max. Change also removes unused code and other minor improvements. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/cfc8cfe8b) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8089) |
| StockMovements.php, StockLocMovements.php: Correct stock movements that have more than one serial number as part of it, then the item will appear multiple times in the movements script with the total quantity in each line. For example, if I enter a quantity adjustment for a controlled item, and assign 3 serial numbers to this movement and then run the inquiries, there will be 3 separate lines with a quantity of 3 against each one. | Fixed | Tim Schofield | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/45f169be1) |  |
| SellThroughSupport.php: Remove (another) redundant hidden FormID input. (there were two, overlooked the 2nd one earlier) | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/bd8ae37df) |  |
| SellThroughSupport.php: Remove redundant hidden FormID input. | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/bd3e0ec77) |  |
| Contracts.php: Move closing form tag outside of condition. Fixes view page source message: "Saw a form start tag, but there was already an active form element. Nested forms are not allowed. Ignoring the tag." reported in Firefox. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/d2a5d9d7e) |  |
| Remove $db parameter from WoRealRequirements(), EnsureGLEntriesBalance(), and CreateQASample() functions. | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/e6aa43214) |  |
| Remove $db parameter from BomMaterialCost(), GetTaxRate(), GetTaxes(), GetCreditAvailable(), ItemCostUpdateGL(), and UpdateCost() functions. | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/2ebcfda45) |  |
| Remove $db parameter from all GetStockGLCode() functions. | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/7a44c37ef) |  |
| Remove a few lingering $k and $j variables left behind from 7944 commit. | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/4bca85d25) |  |
| MRPReschedules.php, MRPShortages.php: Use DB_table_exists() from commit 7943 to replace table check query. | Fixed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/c036edadf) |  |
| Remove the last of the remaining URL 'SID' references. | Deprecated | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/79cd5b540) |  |
| StockLocMovements.php, StockMovements.php: Add serial number column to output. | Added | Paul Becker | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/9d5aaa1d1) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8088) |
| InternalStockRequestFulfill.php: Add controlled stock handling within this script. | Added | Paul Becker | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/a88d57e9e) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8086) |
| MRPPlannedPurchaseOrders.php, MRPPlannedWorkOrders.php: Fix conversion factor matter noted by Tim, use DB_table_exists() from commit 7943 to replace table check query, and minor rework to 'missing cell' handling from commit 7939. | Fixed | Tim/Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/21b6010d5) |  |
| Replace old method of table row alternating color handing with improved CSS. Also, this change removes some empty/unused properties from a few css file and removes old URL 'SID' references in files already modified for this commit. Due to SVN issues with TestPlanResults.php, this one file will be committed later. | Changed | Paul Thursby | 2018-02-27 | [View](http://github.com/webERP-team/webERP/commit/a35e49759) |  |
| ConnectDB_xxxx.inc files: Add function DB_table_exists() function to all DB support files, by Tim suggestion. Note that this function will be used in other files in a future commit. Signed-off-by: Paul Thursby | Added | Tim Schofield | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/d502bb706) |  |
| Z_SalesIntegrityCheck.php: Fix that the does not take into account discountpercent so it shows an issue where non exists. | Fixed | Paul Becker | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/b44969048) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8084) |
| UserSettings.php: Fix the 'Maximum Number of Records to Display' from populating with the session default at page load instead of the user's setting. Applied Tim's improved handling. | Fixed | Tim Schofield | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/223abbb93) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8081) |
| geo_displaymap_customers.php, geo_displaymap_suppliers.php: Fix a few PHP short-tags, and move some javascript from PHP output to fix 'missing tag' validation complaints. | Fixed | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/2713e4123) |  |
| MRPPlannedPurchasekOrders.php, MRPPlannedWorkOrders.php: PaulT: Add missing table cell to work orders to match recent change to planned purchase orders and replace 'where clause joins' with table join in both files. Apply consistent code formatting between both files. | Fixed | Paul Thursby/Paul Becker | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/889896d2b) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8061) |
| SalesGraph.php: Rework previous 7908 implementation that caused graphing to break. | Fixed | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/83ee834cd) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8071) |
| InternalStockRequestInquiry.php: Restore ONE space to previous 7936 commit. | Changed | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/49c76ebe2) |  |
| Remove unused $db and $conn parameters from DB_Last_Insert_ID() and (where present) from DB_show_tables(), and DB_show_fields(). Also, remove any unused 'global $db' references across the code base. | Deprecated | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/7f3464c40) |  |
| MRPPlannedPurchaseOrders.php: Add capability to review planned purchase orders and add a new link to convert to a new PO. | Added | Paul Becker | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/ffa090fa8) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8061) |
| PrintCustOrder.php, PrintCustOrder_generic.php, PDFOrderPageHeader_generic.inc: Add units, volume, and weight info, date/signature lines, sales order details narrative, plus other minor PDF formatting. | Added | Paul Becker | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/c7e99651b) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8048) |
| Remove unused $db parameter from DB_fetch_array() and DB_Query() functions. Also, rename several DB_Query names to match function definition name: DB_query. | Deprecated | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/5252106df) |  |
| Dashboard.php: Replace due date handling with existing function. | Changed | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/5e94cef20) |  |
| PrintCustTrans.php, PDFTransPageHeader.inc, PrintCustTransPortrait.php, PDFTransPageHeaderPortrait.inc: Add missing stock lot/serial info to landscape output to be consistent with portrait output (reported by HDeriauFF), add Due Date info to invoices (reported by Paul Becker), and (PaulT) add security checks to portrait file, layout improvements, change PDF initialization handling, and more. | Fixed | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/079b5e908) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8065&pid=14115#pid14115)) |
| Add a 'warning' case to getMsg(), as there is mixed use of 'warn' and 'warning' usage with prnMsg() calls. The 'warning' (before this change) defaults to an 'info' style message. | Changed | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/e5147580f) |  |
| Remove unused $db parameter from DB_query(), DB_error_no() and DB_error_msg() other DB-related function calls. | Deprecated | Paul Thursby | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/e506e545b) |  |
| Remove stray ; appearing after if, else, and foreach blocks. | Fixed | Paul Becker | 2018-02-26 | [View](http://github.com/webERP-team/webERP/commit/439e34699) | [Forum](http://www.weberp.org/forum/showthread.php?tid=8064) |
| MiscFunctions.php, Z_ChangeStockCode.php, Z_ChangeGLAccountCode.php: Remove unused $db parameter from function ChangeFieldInTable(). | Deprecated | PaulT | 2018-01-27 |  |  |
| New picking list feature for regular and controlled/serialized stock. This feature improves (and replaces) the current pick list handling. | Added | Andrew Galuski/Tim | 2018-01-26 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7988) |
| Add single quotation escape and charset in htmlspecialchars() in session.inc | Changed | Exson | 2018-01-26 |  |  |
| Use htmlspecialchars() to encode html special characters to html entity and set the cookie available only httponly in session.inc | Changed | Exson | 2018-01-26 |  |  |
| ReorderLevel.php: Exclude completed orders from on order counts. | Changed | Briantmg | 2018-01-25 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8060) |
| SelectOrderItems.php: Paul B. Fix stock table columns NOT sorting on this page / PaulT. Use existing CSS to replace two style attributes. | Fixed | PaulT/Paul Becker | 2018-01-24 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8057) |
| SupplierInvoice.php, CounterSales.php: Replace two other hard-coded styles with existing CSS class. | Changed | Andy Couling | 2018-01-24 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8057) |
| SelectOrderItems.php: Paul B. Remove stray value output / PaulT. Replace hard-coded style with existing CSS class. | Fixed | PaulT/Paul Becker | 2018-01-24 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8057) |
| CustomerPurchases.php: Adds Units and Discount columns and other minor changes so that it more closely matches output from OrderDetails.php. | Added | Paul Becker | 2018-01-15 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8040) |
| CustomerPurchases.php: Fix script to show actual Price and actual Amount of Sale based upon discount. | Fixed | Paul Becker | 2018-01-12 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8040) |
| Payments.php: Remove my debug/test echo line from the previous commit. | Fixed | PaulT | 2018-01-09 |  |  |
| Payments.php: Show bank balance at payments. Know that balance display/output is protected by a similar security check manner as protected information at the dashboard. | Added | Paul Becker | 2018-01-09 |  | [Forum](http://weberp.org/forum/showthread.php?tid=8017) |
| Z_MakeNewCompany.php, default.sql, demo.sql: Remove doubled underscore in EDI_Sent reference. | Fixed | Paul Becker | 2018-01-09 |  | [Forum](http://weberp.org/forum/showthread.php?tid=7920) |
| PDFTransPageHeader.inc, PDFTransPageHeaderPortrait.inc: Add additional address fields and/or adds an extra space between some address fields. | Added | Paul Becker | 2018-01-08 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7942) |
| PO_Items.php: Fix/improve Supplier checkbox handling, and fix a PHP7 compatibility issue. | Fixed | Tim | 2018-01-08 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7958) |
| SalesGraph.php: Replace period numbers in graph title with month and year. | Changed | Paul Becker/Tim | 2018-01-08 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7946) |
| WriteReport.inc: Fix broken page number handling. | Fixed | Paul Becker | 2018-01-07 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7955) |
| Change.log: Update remaining past commit entries (during the past few weeks) to give credit to the right person(s) involved with the change, and when applicable, add the related forum URL for historical reference. | Changed | PaulT | 2018-01-07 |  |  |
| Update phpxmlrpc to latest from https://github.com/gggeek/phpxmlrpc | Changed | Phil | 2018-01-07 |  |  |
| Change.log: Update some past commit entries to give credit to the right person(s) involved with the change, and when applicable, add the related forum URL for historical reference. | Changed | PaulT | 2018-01-06 |  |  |
| SelectSalesOrder.php: Fix handling to correct table heading value. | Fixed | Paul Becker/Tim | 2018-01-06 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8000) |
| Attempt to avoid XSS attacks by logged in users by parsing out "script>" from all $_POST and $_GET variables - subsequentely changed to strip_tags from all $_POST and $_GETs per Tim's recommendation | Security | Phil | 2018-01-06 |  |  |
| SelectSalesOrder.php: Fix search to retain quote option and set StockLocation to the UserStockLocation to auto-load current Sales Orders. | Fixed | Paul Becker | 2018-01-03 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8000) |
| SelectSalesOrder.php: Move handling for URL Quotations parameter to top of file to avoid potential page error(s). Handling move reduces code within some conditional checks. This change also includes minor whitespace improvements and removes an unused global reference. | Changed | Paul Becker/Tim | 2018-01-02 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=8000) |
| css/default/default.css: Add text alignment in a couple of styles to match the same use in other CSS to avoid formatting issues when the default theme is used. Also, set several property names to lowercase. | Changed | PaulT | 2018-01-02 |  |  |
| FormMaker.php, ReportMaker.php, WriteForm.inc: A few more PHP 7.1 array compatibility changes. | Changed | PaulT | 2017-12-20 |  |  |
| RCFunctions.inc, FormMaker.php: PHP 7.1 array compatibility change. | Changed | PaulT | 2017-12-20 |  |  |
| PDFOrderStatus.php: Remove redundant ConnectDB.inc include reference. (already included by session.php at the top of the file) | Deprecated | PaulT | 2017-12-19 |  |  |
| Change.log: Correct my Day/Month entry references over the last few days. | Changed | PaulT | 2017-12-19 |  |  |
| Contracts.php: Move work center handling causing a partial form to appear after the footer when no work centers exist. | Fixed | PaulT | 2017-12-19 |  |  |
| Contract_Readin.php: Add customerref field to query to appear in the form when a contract is modified. | Added | Paul Becker | 2017-12-19 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7998) |
| ReportCreator.php: PHP 7.1 array compatibility change. | Changed | rjonesbsink | 2017-12-18 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7969) |
| BOMIndented.php, BOMIndentedReverse.php: Adjust PDF position values, and add UoM, remove stray 0-9 string output. | Added | Paul Becker | 2017-12-18 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7994) |
| PDFBOMListingPageHeader.inc, BOMListing.php: Adjust PDF position values, and add UoM. | Added | Paul Becker | 2017-12-18 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7993) |
| MRPPlannedPurchaseOrders.php, MRPPlannedWorkOrders.php: Fix PDF highlighting, PDF position value adjustments, and other minor tweaks. | Fixed | Paul Becker | 2017-12-15 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7991) |
| CustomerReceipt.php: Wrap delete link parameter values with urlencode(). | Security | Tim | 2017-12-14 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7980) |
| PDFCOA.php: Add column prodspeckey to queries which is used as a description alternative. | Added | Paul Becker | 2017-12-13 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7989) |
| PDFCOA.php, PDFProdSpec: Minor value adjust to correct inconsistent footer wrap. | Fixed | Paul Becker | 2017-12-13 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7987) |
| HistoricalTestResults.php, SelectQASamples.php, TestPlanResults.php: Fix date inputs to work with the date picker. | Fixed | PaulT | 2017-12-13 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7984) |
| PDFQALabel.php: Overlapping in the PDF when printing non-controlled items. | Fixed | Paul Becker | 2017-12-13 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7976) |
| CustomerReceipt.php: Add identifier to URL for delete link. | Fixed | Paul Becker | 2017-12-13 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7980) |
| QATests.php: Correct wrong attribute name in two option tags. | Fixed | Paul Becker | 2017-12-13 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7983) |
| PHP 7 constructor compatibility change to phplot.php. | Changed | rjonesbsink | 2017-12-11 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7977) |
| SelectSalesOrder.php: Consistent delivery address and correct a unit conversion issue. | Changed | Paul Becker | 2017-12-11 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7967) |
| PHP 7 constructor compatibility change to htmlMimeMail.php and mimePart.php. | Changed | rjonesbsink | 2017-12-11 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7971) |
| Order by transaction date and add link to debtors in Dashboard.php script. | Changed | RChacon | 2017-12-06 |  |  |
| Phil commited Tim's BankAccountBalances.php script | Added | Tim | 2017-12-03 |  |  |
| Fixed the outstanding quantity is not right in PO_SelectOSPurchOrder.php. | Fixed | Exson | 2017-12-02 |  |  |
| Fix for javascript date picker for US date formats | Fixed | Tim | 2017-12-02 |  |  |
| Purchases report - also deleted id non-exsitent in css committed changes suggested by VortecCPI | Changed | Phil/Paul Becker | 2017-12-02 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=7943) |
| Added Petty Cash receipt file upload to directory functionality. | Added | Andy Couling | 2017-11-23 |  |  |
| Remove cost updating for WAC method in BOMs.php. | Changed | Exson | 2017-11-07 |  |  |
| Fixed the salesman authority problem in PrintCustTrans.php. | Fixed | Exson | 2017-10-25 |  |  |
| Prevent sales man from viewing other sales' sales orders in PrintCustTrans.php. | Fixed | Exson | 2017-10-23 |  |  |
| Prevent customer from modifying or viewing other customer's order in SelectOrderItems.php and PrintCustTrans.php. | Fixed | Exson | 2017-10-23 |  |  |
| Change header to meta data to avoid header do not work in some server environment in SelectCompletedOrder.php and SelectGLAccount.php | Changed | Exson | 2018-10-21 |  |  |
| Removed reference to css class 'toplink' in CustomerInquiry.php and CustomerAccount.php. | Fixed | Andy Couling | 2017-10-17 |  |  |
| Fix InventoryPlanning.php and includes/PDFInventoryPlanPageHeader.inc to display categories selected and fix month headings displayed | Fixed | Phil | 2017-10-17 |  |  |
| New Expenses/Update Expense table header in PcClaimExpensesFromTab.php | Added | Andy Couling | 2017-10-15 |  |  |
| Fixed the edit/delete cash assignment functionality in PcAssignCashToTab.php | Fixed | Andy Couling | 2017-10-15 |  |  |
| Table header labels corrected in PcClaimExpensesFromTab.php and PcAuthorizeExpenses.php. | Fixed | Andy Couling | 2017-10-15 |  |  |
| Fixed expense deletion dialogue box in PcClaimExpensesFromTab.php. | Fixed | Andy Couling | 2017-10-15 |  |  |
| Missing $Id comments added to Petty Cash scripts. | Changed | Andy Couling | 2017-10-15 |  |  |
| Fixed the bug that Narrative information will loss when add or remove controlled items lot no in StockAdjustments.php. | Fixed | Exson | 2017-10-10 |  |  |
| If it is set the $_SESSION['ShowPageHelp'] parameter AND it is FALSE, hides the page help text (simplifies code using css). | Changed | RChacon | 2017-10-10 |  |  |
| Set decimals variable for exchange rate in Currencies.php. | Changed | RChacon | 2017-10-10 |  |  |
| Improve currency showing and set decimals variable for exchange rate in Payments.php. | Changed | RChacon | 2017-10-10 |  |  |
| Fix the indian_number_format bug in MiscFunctions.php. | Fixed | Exson | 2017-10-10 |  |  |
| Fixed the non-balance bug in CustomerReceipt.php. And fixed the non rollback problem when there is a non-balance existed. Fixed error noises. | Fixed | Exson | 2017-10-09 |  |  |
| Add html view to SuppPriceList.php. | Added | Exson | 2017-10-03 |  |  |
| Standardise and add icons for usability. | Added | RChacon | 2017-09-20 |  |  |
| Increases accuracy in coordinates. | Changed | RChacon | 2017-09-20 |  |  |
| Fixed the wrong price retrieved bug in PO_Header.php. | Fixed | Exson | 2017-09-20 |  |  |
| Fixed the vendor price bug to ensure only the effective price showed by suppliers in SelectProduct.php. | Fixed | Exson | 2017-09-20 |  |  |
| Fixed the bug to make GRN reverse workable. | Fixed | Exson | 2017-09-20 |  |  |
| Customer information missing in CustomerReceipt.php. Reported by Steven Fu. | Fixed | Exson | 2017-09-19 |  |  |
| Geocode bug fixes | Fixed | Tim | 2017-09-18 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=4380) |
| SelectProduct.php made image dispay code match that used in WorkOrderEntry.php | Fixed | Paul Becker | 2017-09-17 |  |  |
| Fixed the onclick delete confirmation box call in ContractBOM.php, was 'MakeConfirm'. | Fixed | Andy Couling | 2017-09-11 |  |  |
| Code consistency in PO_Items.php. | Changed | Andy Couling | 2017-09-11 |  | [Forum](http://www.weberp.org/forum/showthread.php?tid=4355) |
| Z_ChangeLocationCode.php: Add missing locationusers table update, reported by Paul Becker in forums. | Fixed | PaulT | 2017-09-08 |  |  |
| Fix portrait invoice email now has narrative of correct invoice number! | Fixed | Phil | 2017-09-08 |  |  |
| Petty cash improvements to tax taken from Tim's work | Added | Andy Couling | 2017-09-08 |  |  |
| Fix currency translation in PO_AuthorisationLevels.php. | Fixed | RChacon | 2017-09-06 |  |  |
| Fixed the bug that invoice cannot be issued by same transaction multiple times in SuppTrans.php. | Fixed | Exson | 2017-09-06 |  |  |
| Fixed the bug that can not display correctly while the same debtors has more than one transaction and make GL account which is not AR account or bank account transaction showing on too. | Fixed | Exson | 2017-08-30 |  |  |
| Fixed the default shipper does not work in CustomerBranches.php reported by Steven. | Fixed | Exson | 2017-08-30 |  |  |
| CounterSales.php and StockAdjustments.php: Apply fixes posted by Tim in weberp forums. | Fixed | PaulT | 2017-08-10 |  |  |
| Fixed the search failure problem due to stock id code in SelectWorkOrder.php. | Fixed | Exson | 2017-07-27 |  |  |
| Add QR code for item issue and fg collection for WO in PDFWOPrint.php | Fixed | Exson | 2017-07-18 |  |  |
| Fix call to image tick.svg. | Fixed | RChacon | 2017-07-17 |  |  |
| Utility script to remove all purchase back orders | Added | Phil | 2017-07-15 |  |  |
| Fixed the wrong price bug and GP not updated correctly in SelectOrderItems.php. Report by Robert from MHHK forum. | Fixed | Exson | 2017-07-10 |  |  |
| reportwriter/admin/forms area, fix tag name in four files: `<image>` to `<img>` | Fixed | PaulT | 2017-07-08 |  |  |
| DefineImportBankTransClass.php - Remove extra ( | Fixed | PaulT | 2017-07-04 |  |  |
| Fixed the argument count error in SupplierInvoice.php. | Fixed | Exson | 2017-06-30 |  |  |

## [v4.14.1] - 2017-06-26

| Description | Type | Author | Date | Details | Ref |
|:------------|:----:|:------:|:----:|:-------:|:---:|
| Merge css/WEBootstrap/css/custom.css into css/WEBootstrap/default.css to preserve bootstrap as original. |  | RChacon | 2022-06-25 |  |  |
| Add style sections for device rendering width ranges for no responsive themes. |  | RChacon | 2022-06-24 |  |  |
| Fix class for TransactionsDiv, InquiriesDiv and MaintenanceDiv. Fix bootstrap copy. |  | RChacon | 2022-06-23 |  |  |
| Fixed the Over Receive Portion bug in WorkOrderReceive.php. |  | Exson | 2022-06-22 |  |  |
| Add meta viewport for initial-scale=1 for working css in small devices. |  | RChacon | 2017-06-21 |  |  |


[Unreleased]: https://github.com/webERP-team/webERP/compare/v4.15...HEAD
[v4.15]: https://github.com/webERP-team/webERP/compare/v4.14.1...v4.15
[v4.14.1]: https://github.com/webERP-team/webERP/compare/v4.14...v4.14.1
[Semantic Versioning]: http://semver.org/spec/v2.0.0.html
[Keep a Changelog]: http://keepachangelog.com/en/1.0.0/
[CHANGELOG_ARCHIVE.md]: CHANGELOG_ARCHIVE.md

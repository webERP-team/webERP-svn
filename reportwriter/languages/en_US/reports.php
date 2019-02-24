<?php

/**************************************************************************************
Locale language file for reports. This file can be broken out for apps that use
DEFINE statements. The report scripts and forms obtain their text definitions here.
This first part of this file may be broken out to a language file for apps that don't
use getttext for translation.
***************************************************************************************/

// Message definitions
define('RPT_SAVEDEF',_('The name you entered is a default report. Please enter a new My Report name.'));
define('RPT_SAVEDUP',_('This report name already exists! Press Replace to overwrite or enter a new name and press continue.'));
define('RPT_DUPDB',_('There is an error in your database selection. Check your database names and link equations.'));
define('RPT_BADFLD',_('There is an error in your database field or description. Please check and try again.'));
define('RPT_BADDATA',_('There is an error in one of your data fields. Please check and try again.'));
define('RPT_EMPTYFIELD',_('A data field has been left empty located at sequence number: '));
define('RPT_DEFDEL',_('If you replace this report/form, the original report/form will be deleted!'));
define('RPT_NODATA',_('There was not any data in this report based on the criteria provided!'));
define('RPT_NOROWS',_('You do not have any rows to show!'));
define('RPT_WASSAVED',_(' was saved and copied to report: '));
define('RPT_UPDATED',_('The report name has been updated!'));
define('FRM_NORPT',_('No form name was selected to perform this operation.'));
define('RPT_NORPT',_('No report or form was selected to perform this operation.'));
define('RPT_NORPTTYPE',_('Either Report or Form type needs to be selected!'));
define('RPT_REPDUP',_('The name you entered is already in use. Please enter a new report name!'));
define('RPT_REPDEL',_('Press OK to delete this report.'));
define('RPT_REPOVER',_('Press OK to overwrite this report.'));
define('RPT_NOSHOW',_('There are no reports to show!'));
define('RPT_NOFIELD',_('There are no fields to show!'));
define('FRM_RPTENTER',_('Enter a name for this form.'));
define('RPT_RPTENTER',_('Enter a name for this report.'));
define('RPT_RPTNOENTER',_('(Leave blank to use default report name from import file)'));
define('RPT_MAX30',_('(maximum 30 characters)'));
define('FRM_RPTGRP',_('Enter the group this form is a part of:'));
define('RPT_RPTGRP',_('Enter the group this report is a part of:'));
define('RPT_DEFIMP',_('Select a default report to import.'));
define('RPT_RPTBROWSE',_('Or browse for a report to upload.'));

// Error messages for importing reports
define('RPT_IMP_ERMSG1',_('The filesize exceeds the upload_max_filesize directive in your php.ini settings.'));
define('RPT_IMP_ERMSG2',_('The filesize exceeds the MAX_FILE_SIZE directive in the webERP form.'));
define('RPT_IMP_ERMSG3',_('The file was not completely uploaded. Please retry.'));
define('RPT_IMP_ERMSG4',_('No file was selected to upload.'));
define('RPT_IMP_ERMSG5',_('Unknown php upload error, php returned error # '));
define('RPT_IMP_ERMSG6',_('This file is not reported by the server as a text file.'));
define('RPT_IMP_ERMSG7',_('The uploaded file does not contain any data!'));
define('RPT_IMP_ERMSG8',_('webERP could not find a valid report to import in the uploaded file!'));
define('RPT_IMP_ERMSG9',_(' was successfully imported!'));
define('RPT_IMP_ERMSG10',_('There was an unexpected error uploading the file!'));

// General definitions
define('RPT_ABSCISSA',_('Abscissa'));
define('RPT_ACTIVE',_('Active'));
define('RPT_ALL',_('All'));
define('RPT_ALIGN',_('Align'));
define('RPT_ASSEMBLY',_('Assembly'));
define('RPT_BOTTOM',_('Bottom'));
define('RPT_BREAK',_('Break'));
define('RPT_CENTER',_('Center'));
define('RPT_COLUMN',_('Column'));
define('RPT_COLOR',_('Color'));
define('RPT_CUSTCOLOR',_('Custom Color (Range 0-255)'));
define('RPT_CUSTOM',_('Custom'));
define('RPT_DATE',_('Date'));
define('RPT_DEFAULT',_('Default'));
define('RPT_FALSE',_('False'));
define('RPT_FIELDS',_('Fields'));
define('RPT_FIFTH',_('Fifth'));
define('RPT_FILTER',_('Filter'));
define('RPT_FORM',_('Form'));
define('RPT_FORMS',_('Forms'));
define('RPT_FLDNAME',_('Field Name'));
define('RPT_FROM',_('From'));
define('RPT_FONT',_('Font'));
define('RPT_FOURTH',_('Fourth'));
define('RPT_GROUP',_('Group'));
define('RPT_HORIZONTAL',_('Horizontal'));
define('RPT_INACTIVE',_('Inactive'));
define('RPT_LEFT',_('Left'));
define('RPT_LENGTH',_('Length'));
define('RPT_MOVE',_('Move'));
define('RPT_NO',_('No'));
define('RPT_NONE',_('None'));
define('RPT_ORDER',_('Order'));
define('RPT_ODINATE',_('Ordinate'));
define('RPT_PAGE',_('Page'));
define('RPT_PRIMARY',_('Primary'));
define('RPT_PRINTED',_('Printed'));
define('RPT_RANGE',_('Range'));
define('RPT_REPORT',_('Report'));
define('RPT_REPORTS',_('Reports'));
define('RPT_RIGHT',_('Right'));
define('RPT_SHOW',_('Show'));
define('RPT_SECOND',_('Second'));
define('RPT_SEPARATOR',_('Separator'));
define('RPT_SELECT',_('Select a table...'));
define('RPT_SLCTFIELD',_('Select a field...'));
define('RPT_SEQ',_('Sequence'));
define('RPT_SIXTH',_('Sixth'));
define('RPT_SIZE',_('Size'));
define('RPT_SORT',_('Sort'));
define('RPT_STDCOLOR',_('Standard Color'));
define('RPT_STOCK',_('Stock'));
define('RPT_THIRD',_('Third'));
define('RPT_TO',_('To'));
define('RPT_TOP',_('Top'));
define('RPT_TOTAL',_('Total'));
define('RPT_TRUE',_('True'));
define('RPT_TRUNCATE',_('Truncate'));
define('RPT_TRUNC',_('Truncate Long Descriptions'));
define('RPT_TYPE',_('Type'));
define('RPT_UNPRINTED',_('Unprinted'));
define('RPT_VERTICAL',_('Vertical'));
define('RPT_YES',_('Yes'));

// Report and Form page title definitions
define('RPT_RPTFRM',_('Report/Form: '));
define('RPT_RPRBLDR',_('Report and Form Builder - '));
define('RPT_STEP1',_('Menu'));
define('RPT_STEP2',_('Step 2'));
define('RPT_STEP3',_('Step 3'));
define('RPT_STEP4',_('Step 4'));
define('RPT_STEP5',_('Step 5'));
define('RPT_STEP6',_('Step 6'));
define('RPT_MENU',_('Reports Menu'));
define('RPT_CRITERIA',_('Criteria Setup'));
define('RPT_PAGESAVE',_('Save Report'));
define('RPT_PAGESETUP',_('Report Page Setup'));

// Button definitions - General
define('RPT_BTN_ADD',_('Add'));
define('RPT_BTN_ADDNEW',_('Add New'));
define('RPT_BTN_BACK',_('Go Back'));
define('RPT_BTN_CANCEL',_('Cancel'));
define('RPT_BTN_CHANGE',_('Change'));
define('RPT_BTN_CONT',_('Continue'));
define('RPT_BTN_COPY',_('Copy'));
define('RPT_BTN_CPYRPT',_('Copy To My Reports'));
define('RPT_BTN_CRIT',_('Criteria Setup'));
define('RPT_BTN_DB',_('Database Setup'));
define('RPT_BTN_DEL',_('Delete'));
define('RPT_BTN_DELRPT',_('Delete Report'));
define('RPT_BTN_EDIT',_('Edit'));
define('RPT_BTN_EXPCSV',_('Export CSV'));
define('RPT_BTN_EXPORT',_('Export'));
define('RPT_BTN_EXPPDF',_('Export PDF'));
define('RPT_BTN_FINISH',_('Finish'));
define('RPT_BTN_FLDSETUP',_('Field Setup'));
define('RPT_BTN_IMPORT',_('Import'));
define('RPT_BTN_PGSETUP',_('Page Setup'));
define('RPT_BTN_PROP',_('Properties'));
define('RPT_BTN_REPLACE',_('Replace'));
define('RPT_BTN_REMOVE',_('Remove'));
define('RPT_BTN_RENAME',_('Rename'));
define('RPT_BTN_SAVE',_('Save'));
define('RPT_BTN_UPDATE',_('Update'));

// Report  Specific
define('RPT_RPTBASIS',_('Accrual Basis'));
define('RPT_RPTFILTER',_('Report Filters: '));
define('RPT_GROUPBY',_('Grouped by:'));
define('RPT_SORTBY',_('Sorted by:'));
define('RPT_DATERANGE',_('Date Range:'));
define('RPT_CRITBY',_('Filters:'));
define('RPT_ADMIN',_('Administrator Page'));
define('RPT_BRDRLINE',_('Border Line'));
define('RPT_BOXDIM',_('Box  Dimensions (mm)'));
define('RPT_COL1W',_('Column 1'));
define('RPT_COL2W',_('Column 2'));
define('RPT_COL3W',_('Column 3'));
define('RPT_COL4W',_('Column 4'));
define('RPT_COL5W',_('Column 5'));
define('RPT_COL6W',_('Column 6'));
define('RPT_COL7W',_('Column 7'));
define('RPT_COL8W',_('Column 8'));
define('RPT_COL9W',_('Column 9'));
define('RPT_COL10W',_('Column 10'));
define('RPT_COL11W',_('Column 11'));
define('RPT_COL12W',_('Column 12'));
define('RPT_COL13W',_('Column 13'));
define('RPT_COL14W',_('Column 14'));
define('RPT_COL15W',_('Column 15'));
define('RPT_COL16W',_('Column 16'));
define('RPT_COL17W',_('Column 17'));
define('RPT_COL18W',_('Column 18'));
define('RPT_COL19W',_('Column 19'));
define('RPT_COL20W',_('Column 20'));
define('RPT_CRITTYPE',_('Type of Criteria'));
define('RPT_TYPECREATE',_('Select report or form type to create:'));
define('RPT_CWDEF',_('Column widths - mm (0 for same as prior column)'));
define('RPT_CUSTRPT',_('Custom Reports'));
define('RPT_DATEDEF',_('Default Date Selected'));
define('RPT_DATEFNAME',_('Date Fieldname (table.fieldname)'));
define('RPT_DATEINFO',_('Report Date Information'));
define('RPT_DATEINST',_('For date-independent reports:<br />Uncheck all period range boxes<br />Leave Date Fieldname empty'));
define('RPT_DATELIST',_('Date Field List<br />(check all that apply)'));
define('RPT_DEFRPT',_('Default Reports'));
define('RPT_ENDPOS',_('End Position (mm)'));
define('RPT_ENTRFLD',_('Enter a New Field'));
define('RPT_FLDLIST',_('Field List'));
define('RPT_FILL',_('Fill'));
define('RPT_FORMOUTPUT',_('Select a Form to Output'));
define('RPT_FORMSELECT',_('Form Selection'));
define('RPT_GRPLIST',_('Grouping List'));
define('RPT_HEIGHT',_('Height'));
define('RPT_IMAGECUR',_('Current Image'));
define('RPT_IMAGESEL',_('Image Selection'));
define('RPT_IMAGESTORED',_('Select Stored Image'));
define('RPT_IMAGEDIM',_('Image  Dimensions (mm)'));
define('RPT_LINEATTRIB',_('Line Attributes'));
define('RPT_LINEWIDTH',_('Line Width (pts)'));
define('RPT_LINKEQ',_('Link Equation (SQL Syntax)<br />example: tablename1.fieldname1=tablename2.fieldname2'));
define('RPT_MYRPT',_('My Reports'));
define('RPT_NOBRDR',_('No Border'));
define('RPT_NOFILL',_('No Fill'));
define('RPT_DISPNAME',_('Name to Display'));
define('RPT_PGCOYNM',_('Company Name'));
define('RPT_PGFILDESC',_('Report Filter Description'));
define('RPT_PGHEADER',_('Header Information / Formatting'));
define('RPT_PGLAYOUT',_('Page Layout'));
define('RPT_PGMARGIN',_('Page Margins'));
define('RPT_RNRPT',_('Rename Report'));
define('RPT_PGTITL1',_('Report Title 1'));
define('RPT_PGTITL2',_('Report Title 2'));
define('RPT_RPTDATA',_('Report Data'));
define('RPT_RPTID',_('Report/Form Identification'));
define('RPT_RPTIMPORT',_('Report Import'));
define('RPT_SORTLIST',_('Sorting Information'));
define('RPT_STARTPOS',_('Start Position (Upper Left Corner in mm)'));
define('RPT_TBLNAME',_('Table Name'));
define('RPT_TEXTATTRIB',_('Text Attributes'));
define('RPT_TEXTDISP',_('Text to Display'));
define('RPT_TEXTPROC',_('Text Processing'));
define('RPT_TBLFNAME',_('Fieldname (table.fieldname)'));
define('RPT_TOTALS',_('Report Totals'));
define('RPT_FLDTOTAL',_('Enter fields to total (Table.Fieldname)'));
define('RPT_WIDTH',_('Width'));

// Report Group Definitions
define('RPT_ORDERS',_('Orders'));
define('RPT_PAYABLES',_('Payables'));
define('RPT_PURCHASES',_('Purchases'));
define('RPT_RECEIVABLES',_('Receivables'));
define('RPT_INVENTORY',_('Inventory'));
define('RPT_MANUFAC',_('Manufacturing'));
define('RPT_GL',_('General Ledger'));
define('RPT_AM',_('Asset Manager'));
define('RPT_PC',_('Petty Cash'));
define('RPT_FINANCIAL',_('Financial Reports'));
define('RPT_MISC',_('Miscellaneous'));

// Form Group Definitions
define('RPT_BANKCHK',_('Bank Checks'));
define('RPT_BANKDEPSLIP',_('Bank Deposit Slips'));
define('RPT_COLLECTLTR',_('Collection Letters'));
define('RPT_CUSTLBL',_('Labels - Customer'));
define('RPT_CUSTQUOTE',_('Customer Quotes'));
define('RPT_CUSTSTATE',_('Customer Statements'));
define('RPT_INVPKGSLIP',_('Invoices and Packing Slips'));
define('RPT_PURCHORD',_('Purchase Orders'));
define('RPT_SALESORD',_('Sales Orders'));
define('RPT_SALESREC',_('Sales Receipts'));
define('RPT_VENDLBL',_('Labels - Vendor'));

// Form Processing Definitions
define('FRM_DATALINE',_('Data Line'));
define('FRM_DATABLOCK',_('Data Block'));
define('FRM_DATATABLE',_('Data Table'));
define('FRM_DATATOTAL',_('Data Total'));
define('FRM_FIXEDTXT',_('Fixed Text Field'));
define('FRM_NOIMAGE',_('No Image'));
define('FRM_IMAGE',_('Image - JPG or PNG'));
define('FRM_RECTANGLE',_('Rectangle'));
define('FRM_LINE',_('Line'));
define('FRM_COYDATA',_('Company Data Line'));
define('FRM_COYBLOCK',_('Company Data Block'));
define('FRM_PAGENUM',_('Page Number'));
define('FRM_UPPERCASE',_('Uppercase'));
define('FRM_LOWERCASE',_('Lowercase'));
define('FRM_NEGATE',_('Negate'));
define('FRM_RNDR2',_('Round (2 decimal)'));
define('FRM_CNVTDLR',_('Convert Dollars'));
define('FRM_CNVTEURO',_('Convert Euros'));
define('FRM_SPACE1',_('Single Space'));
define('FRM_SPACE2',_('Double Space'));
define('FRM_COMMA',_('Comma'));
define('FRM_COMMASP',_('Comma-Space'));
define('FRM_NEWLINE',_('Line Break'));
define('FRM_SEMISP',_('Semicolon-space'));

// Paper Size Definitions
define('RPT_PAPER',_('Paper Size:'));
define('RPT_ORIEN',_('Orientation:'));
define('RPT_MM',_('mm'));
define('RPT_A3',_('A3'));
define('RPT_A4',_('A4'));
define('RPT_A5',_('A5'));
define('RPT_LEGAL',_('Legal'));
define('RPT_LETTER',_('Letter'));
define('RPT_PORTRAIT',_('Portrait'));
define('RPT_LANDSCAPE',_('Landscape'));

// Font Names
define('RPT_COURIER',_('Courier'));
define('RPT_COURIERB',_('Courier Bold'));
define('RPT_COURIERBI',_('Courier Bold Italic'));
define('RPT_COURIERI',_('Courier Italic'));
define('RPT_HELVETICA',_('Helvetica'));
define('RPT_HELVETICAB',_('Helvetica Bold'));
define('RPT_HELVETICABI',_('Helvetica Bold Italic'));
define('RPT_HELVETICAI',_('Helvetica Italic'));
define('RPT_TIMES',_('Times'));
define('RPT_TIMESB',_('Times Bold'));
define('RPT_TIMESBI',_('Times Bold Italic'));
define('RPT_TIMESI',_('Times Italic'));

// General Number Definitions
define('RPT_1','1');
define('RPT_2','2');
define('RPT_3','3');
define('RPT_4','4');
define('RPT_5','5');
define('RPT_6','6');
define('RPT_7','7');
define('RPT_8','8');
define('RPT_9','9');
define('RPT_10','10');
define('RPT_12','12');
define('RPT_14','14');
define('RPT_16','16');
define('RPT_18','18');
define('RPT_20','20');
define('RPT_24','24');
define('RPT_28','28');
define('RPT_32','32');
define('RPT_36','36');
define('RPT_40','40');
define('RPT_50','50');

// Color definitions
define('RPT_BLACK',_('Black'));
define('RPT_BLUE',_('Blue'));
define('RPT_RED',_('Red'));
define('RPT_ORANGE',_('Orange'));
define('RPT_YELLOW',_('Yellow'));
define('RPT_GREEN',_('Green'));
define('RPT_WHITE',_('White'));

// Definitions for date selection dropdown list
define('RPT_TODAY',_('Today'));
define('RPT_WEEK',_('This Week'));
define('RPT_WTD',_('This Week To Date'));
define('RPT_MONTH',_('This Month'));
define('RPT_MTD',_('This Month To Date'));
define('RPT_QUARTER',_('This Quarter'));
define('RPT_QTD',_('This Quarter To Date'));
define('RPT_YEAR',_('This Year'));
define('RPT_YTD',_('This Year To Date'));
define('RPT_YESTERDAY',_('Yesterday'));
define('RPT_LASTWEEK',_('Last Week'));
define('RPT_LASTWTD',_('Last Week To Date'));
define('RPT_LASTMONTH',_('Last Month'));
define('RPT_LASTMTD',_('Last Month To Date'));
define('RPT_LASTQUARTER',_('Last Quarter'));
define('RPT_LASTQTD',_('Last Quarter To Date'));
define('RPT_LASTYEAR',_('Last Year'));
define('RPT_LASTYTD',_('Last Year To Date'));
define('RPT_NEXTWEEK',_('Next Week'));
define('RPT_NEXTMONTH',_('Next Month'));
define('RPT_NEXTQUARTER',_('Next Quarter'));
define('RPT_NEXTYEAR',_('Next Year'));
?>
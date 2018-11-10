<?php

/* Creates the csv files necessary for Phocas sales analysis and sends to an ftp server*/

/*Config */

$FTP_Server = 'someftpserver.com';
$FTP_User = 'someuser';
$FTP_Password = '';

$AllowAnyone=true;
$PageSecurity=15;
$_POST['CompanyNameField']= 'yourdatabase';

include ('includes/session.php');
$Title = _('Create and send sales analysis files');
include ('includes/header.php');

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Export Sales Analysis Files') .'" alt="" /><b>' . $Title. '</b></p>';

function stripcomma($str) { //because we're using comma as a delimiter
	return str_replace(',', '', $str);
}

echo '<div class="centre">' . _('Making a comma separated values file of the stock items') . '</div>';

$ErrMsg = _('The SQL to get the stock items failed with the message');

$sql = "SELECT stockid, categoryid, description FROM stockmaster";
$result = DB_query($sql, $ErrMsg);

if (!file_exists($_SESSION['reports_dir'])){
	$Result = mkdir('./' . $_SESSION['reports_dir']);
}

$ItemsFileName = $_SESSION['reports_dir'] . '/StockItems.csv';

unlink($ItemsFileName);

$fp = fopen($ItemsFileName,'w');

if ($fp==FALSE){

	prnMsg(_('Could not open or create the file under') . ' ' . $ItemsFileName,'error');
	include('includes/footer.php');
	exit;
}
fputs($fp, '"Item Code", "Category ID", "Item Description"'  . "\n");
While ($myrow = DB_fetch_row($result)){
	$line = stripcomma($myrow[0]) . ', ' . stripcomma($myrow[1]) . ', ' . stripcomma($myrow[2]);
	//fputs($fp,"\xEF\xBB\xBF" . $line . "\n");
	fputs($fp, $line . "\n");
}

fclose($fp);
//Now the customers

echo '<div class="centre">' . _('Making a comma separated values file of the customers') . '</div>';

$ErrMsg = _('The SQL to get the customers failed with the message');

$sql = "SELECT debtorsmaster.debtorno, debtorsmaster.name, custbranch.branchcode, brname, salestype, area, salesman FROM debtorsmaster INNER JOIN custbranch ON debtorsmaster.debtorno=custbranch.debtorno";
$result = DB_query($sql, $ErrMsg);

$CustomersFileName = $_SESSION['reports_dir'] . '/Customers.csv';

unlink($CustomersFileName);

$fp = fopen($CustomersFileName,'w');

if ($fp==FALSE){

	prnMsg(_('Could not open or create the file under') . ' ' . $CustomersFileName,'error');
	include('includes/footer.php');
	exit;
}
fputs($fp, '"Customer Code", "Customer Name", "Branch Code", "Branch Name", "Price List", "Sales Area", "Salesman"'  . "\n");
While ($myrow = DB_fetch_row($result)){
	$line = stripcomma($myrow[0]) . ', ' . stripcomma($myrow[1]). ', ' . stripcomma($myrow[2]) . ', ' . stripcomma($myrow[3]) . ', ' . stripcomma($myrow[4])  . ', ' . stripcomma($myrow[5]) . ', ' . stripcomma($myrow[6]);
	//fputs($fp,"\xEF\xBB\xBF" . $line . "\n");
	fputs($fp, $line . "\n");
}

fclose($fp);

//Now the sales analysis invoice & credit note lines

echo '<div class="centre">' . _('Making a comma separated values file of the sales lines') . '</div>';

$ErrMsg = _('The SQL to get the sales data failed with the message');

$sql = "SELECT 	stockmoves.debtorno,
				stockmoves.branchcode,
				stockid,
				trandate,
				-qty,
				-price*(1-discountpercent)*qty,
				-standardcost*qty,
				transno
			FROM stockmoves
			INNER JOIN custbranch
			ON stockmoves.debtorno=custbranch.debtorno
				AND stockmoves.branchcode=custbranch.branchcode
			WHERE (stockmoves.type=10 OR stockmoves.type=11)
			AND show_on_inv_crds=1";

$result = DB_query($sql, $ErrMsg);

$SalesFileName = $_SESSION['reports_dir'] . '/SalesAnalysis.csv';

unlink($SalesFileName);

$fp = fopen($SalesFileName,'w');

if ($fp==FALSE){

	prnMsg(_('Could not open or create the file under') . ' ' . $SalesFileName,'error');
	include('includes/footer.php');
	exit;
}
fputs($fp,'"Customer Code", "Branch Code", "Item Code", "Date", "Quantity", "Line Value", "Line Cost", "Inv/Credit Number"'  . "\n");
While ($myrow = DB_fetch_row($result)){
	$line = stripcomma($myrow[0]) . ', ' . stripcomma($myrow[1]) . ', ' . stripcomma($myrow[2]) . ', ' . stripcomma($myrow[3]) . ', ' . stripcomma($myrow[4]) . ', ' . stripcomma($myrow[5]) . ', ' . stripcomma($myrow[6]) . ', ' . stripcomma($myrow[7]);
	//fputs($fp,"\xEF\xBB\xBF" . $line . "\n");
	fputs($fp, $line . "\n");
}

fclose($fp);

// set up basic ftp connection
$conn_id = ftp_connect($FTP_Server);

// login with username and password
$login_result = ftp_login($conn_id, $FTP_User, $FTP_Password);

// check connection
if ((!$conn_id) || (!$login_result)) {
    echo "FTP connection has failed!";
    echo "Attempted to connect to $FTP_Server  with user $FTP_User";
    exit;
} else {
    echo "Connected to ftp_server";
}

// upload the files
$upload = ftp_put($conn_id, '/' . Date('Y-m-d') .'_Sales.csv', $SalesFileName, FTP_BINARY);
$upload = ftp_put($conn_id, '/' . Date('Y-m-d') .'_Items.csv', $ItemsFileName, FTP_BINARY);
$upload = ftp_put($conn_id, '/' . Date('Y-m-d') .'_Customers.csv', $CustomersFileName, FTP_BINARY);

// check upload status
if (!$upload) {
    echo "FTP upload has failed!";
} else {
    echo "Uploaded $source_file to $ftp_server as $destination_file";
}

// close the FTP stream
ftp_close($conn_id);

include('includes/footer.php');

?>
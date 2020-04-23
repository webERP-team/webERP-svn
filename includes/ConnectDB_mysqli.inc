<?php
/* Database abstraction for mysqli */

define ('LIKE', 'LIKE');

if(!isset($mysqlport)) {
	$mysqlport = 3306;
}
global $db;	// Make sure it IS global, regardless of our context

$db = mysqli_connect($host , $DBUser, $DBPassword,$_SESSION['DatabaseName'], $mysqlport);


//this statement sets the charset to be used for sending data to and from the db server
//if not set, both mysqli server and mysqli client/library may assume otherwise
mysqli_set_charset($db, 'utf8');

/* check connection */
if(mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	session_unset();
	session_destroy();
	echo '<p>' . _('Click') . ' ' . '<a href="index.php">' . _('here') . '</a>' . ' ' ._('to try logging in again') . '</p>';
	exit();
}

if(!$db) {
	echo '<br />' . _('The configuration in the file config.php for the database user name and password do not provide the information required to connect to the database server');
	exit;
}

/* Update to allow RecurringSalesOrdersProcess.php to run via cron */
if(isset($DatabaseName)) {
	if(!mysqli_select_db($db,$DatabaseName)) {
		echo '<br />' . _('The company name entered does not correspond to a database on the database server specified in the config.php configuration file. Try logging in with a different company name');
		echo '<br /><a href="index.php">' . _('Back to login page') . '</a>';
		unset ($DatabaseName);
		exit;
	} 
} else {
	if(!mysqli_select_db($db,$_SESSION['DatabaseName'])) {
		echo '<br />' . _('The company name entered does not correspond to a database on the database server specified in the config.php configuration file. Try logging in with a different company name');
		echo '<br /><a href="index.php">' . _('Back to login page') . '</a>';
		unset ($_SESSION['DatabaseName']);
		exit;
	}
}

//DB wrapper functions to change only once for whole application

function DB_query($SQL, $ErrorMessage='', $DebugMessage= '', $Transaction=false, $TrapErrors=true) {

	global $debug;
	global $PathPrefix;
	global $db;
	global $Messages;

	$result = mysqli_query($db, $SQL);
	$_SESSION['LastInsertId'] = mysqli_insert_id($db);

	if($DebugMessage == '') {
		$DebugMessage = _('The SQL that failed was');
	}
		
	if(DB_error_no() != 0 AND $TrapErrors==true) {
		require_once($PathPrefix . 'includes/header.php');
		prnMsg($ErrorMessage . '<br />' . DB_error_msg(), 'error', _('Database Error'). ' ' . DB_error_no());
		if($debug==1) {
			prnMsg($DebugMessage. '<br />' . $SQL . '<br />','error',_('Database SQL Failure'));
		}
		if($Transaction) {
			$SQL = 'rollback';
			$Result = DB_query($SQL);
			if(DB_error_no() != 0) {
				prnMsg(_('Error Rolling Back Transaction'), 'error', _('Database Rollback Error'). ' ' .DB_error_no() );
			}else{
				prnMsg(_('Rolling Back Transaction OK'), 'error', _('Database Rollback Due to Error Above'));
			}
		}
		include($PathPrefix . 'includes/footer.php');
		exit;
	} elseif(isset($_SESSION['MonthsAuditTrail']) and (DB_error_no()==0 AND $_SESSION['MonthsAuditTrail']>0) AND (DB_affected_rows($result)>0)) {

		$SQLArray = explode(' ', $SQL);

		if(($SQLArray[0] == 'INSERT')
			OR ($SQLArray[0] == 'UPDATE')
			OR ($SQLArray[0] == 'DELETE')) {

			if($SQLArray[2] != 'audittrail') { // to ensure the auto delete of audit trail history is not logged
				$AuditSQL = "INSERT INTO audittrail (transactiondate,
									userid,
									querystring)
						VALUES('" . Date('Y-m-d H:i:s') . "',
							'" . trim($_SESSION['UserID']) . "',
							'" . DB_escape_string($SQL) . "')";

				$AuditResult = mysqli_query($db, $AuditSQL);
			}
		}
	}

	return $result;
}

function DB_fetch_row($ResultIndex) {
	$RowPointer=mysqli_fetch_row($ResultIndex);
	return $RowPointer;
}

function DB_fetch_assoc($ResultIndex) {
	$RowPointer=mysqli_fetch_assoc($ResultIndex);
	return $RowPointer;
}

function DB_fetch_array($ResultIndex) {
	$RowPointer = mysqli_fetch_array($ResultIndex);
	return $RowPointer;
}

function DB_data_seek(&$ResultIndex,$Record) {
	mysqli_data_seek($ResultIndex,$Record);
}

function DB_free_result($ResultIndex) {
	if(is_resource($ResultIndex)) {
		mysqli_free_result($ResultIndex);
	}
}

function DB_num_rows($ResultIndex) {
	return mysqli_num_rows($ResultIndex);
}

function DB_affected_rows($ResultIndex) {
	global $db;
	return mysqli_affected_rows($db);
}

function DB_error_no() {
	global $db;
	return mysqli_errno($db);
}

function DB_error_msg() {
	global $db;
	return mysqli_error($db);
}

function DB_Last_Insert_ID($Table, $FieldName) {
//	return mysqli_insert_id($Conn);
	if(isset($_SESSION['LastInsertId'])) {
		$Last_Insert_ID = $_SESSION['LastInsertId'];
	} else {
		$Last_Insert_ID = 0;
	}
//	unset($_SESSION['LastInsertId']);
	return $Last_Insert_ID;
}

function DB_escape_string($String) {
	global $db;
	return mysqli_real_escape_string($db, $String);
}

function DB_show_tables() {
	$Result = DB_query('SHOW TABLES');
	return $Result;
}

function DB_show_fields($TableName) {
	$Result = DB_query("DESCRIBE $TableName");
	return $Result;
}

function interval( $val, $Inter ) {
		return "\n".'interval ' . $val . ' ' . $Inter . "\n";
}

function DB_Maintenance() {
	prnMsg(_('The system has just run the regular database administration and optimisation routine.'),'info');

	$TablesResult = DB_show_tables();
	while ($myrow = DB_fetch_row($TablesResult)) {
		$Result = DB_query('OPTIMIZE TABLE ' . $myrow[0]);
	}

	$Result = DB_query("UPDATE config
				SET confvalue = CURRENT_DATE
				WHERE confname = 'DB_Maintenance_LastRun'");
}

function DB_Txn_Begin() {
	global $db;
	mysqli_query($db,'SET autocommit=0');
	mysqli_query($db,'START TRANSACTION');
}

function DB_Txn_Commit() {
	global $db;
	mysqli_query($db,'COMMIT');
	mysqli_query($db,'SET autocommit=1');
}

function DB_Txn_Rollback() {
	global $db;
	mysqli_query($db,'ROLLBACK');
}

function DB_IgnoreForeignKeys() {
	global $db;
	mysqli_query($db,'SET FOREIGN_KEY_CHECKS=0');
}

function DB_ReinstateForeignKeys() {
	global $db;
	mysqli_query($db, 'SET FOREIGN_KEY_CHECKS=1');
}

function DB_table_exists($TableName) {
	global $db;

	$SQL = "SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA = '" . $_SESSION['DatabaseName'] . "' AND TABLE_NAME = '" . $TableName . "'";
	$Result = DB_query($SQL);

	if (DB_num_rows($Result) > 0) {
		return True;
	} else {
		return False;
	}
}
?>

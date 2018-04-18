<?php

if (!isset($PathPrefix)) {
	$PathPrefix='';
}

if (!file_exists($PathPrefix . 'config.php')){
	$RootPath = dirname(htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'));
	if ($RootPath == '/' OR $RootPath == "\\") {
		$RootPath = '';
	}
	header('Location:' . $RootPath . '/install/index.php');
	exit;
}
include($PathPrefix . 'config.php');

if (isset($dbuser)) { //this gets past an upgrade issue where old versions used lower case variable names
	$DBUser=$dbuser;
	$DBPassword=$dbpassword;
	$DBType=$dbType;
}

if (isset($SessionSavePath)){
	session_save_path($SessionSavePath);
}

if (!isset($SysAdminEmail)) {
	$SysAdminEmail='';
}

ini_set('session.gc_maxlifetime',$SessionLifeTime);

if( !ini_get('safe_mode') ){
	set_time_limit($MaximumExecutionTime);
	ini_set('max_execution_time',$MaximumExecutionTime);
}

session_write_close(); //in case a previous session is not closed
ini_set('session.cookie_httponly',1);

// Set a specific session_name to avoid potential default session_name conflicts
// with other apps using the same host.
// For an example situation to support this need, see:
// http://www.weberp.org/forum/showthread.php?tid=8133
session_name('PHPSESSIDwebERPteam');
session_start();

include($PathPrefix . 'includes/ConnectDB.inc');
include($PathPrefix . 'includes/DateFunctions.inc');

if (!isset($_SESSION['AttemptsCounter']) OR $AllowDemoMode==true){
	$_SESSION['AttemptsCounter'] = 0;
}

/* iterate through all elements of the $_POST array and DB_escape_string them
to limit possibility for SQL injection attacks and cross scripting attacks
*/

if (isset($_SESSION['DatabaseName'])){
	foreach ($_POST as $PostVariableName => $PostVariableValue) {
		if (gettype($PostVariableValue) != 'array') {
			if(get_magic_quotes_gpc()) {
				$_POST['name'] = stripslashes($_POST['name']);
			}

			$_POST[$PostVariableName] = DB_escape_string(htmlspecialchars($PostVariableValue,ENT_QUOTES,'UTF-8'));
		} else {
			foreach ($PostVariableValue as $PostArrayKey => $PostArrayValue) {
				if(get_magic_quotes_gpc()) {
					$PostVariableValue[$PostArrayKey] = stripslashes($value[$PostArrayKey]);
				}
			 	$_POST[$PostVariableName][$PostArrayKey] = DB_escape_string(htmlspecialchars($PostArrayValue,ENT_QUOTES,'UTF-8'));

			}
		}
	}

	/* iterate through all elements of the $_GET array and DB_escape_string them
	to limit possibility for SQL injection attacks and cross scripting attacks
	*/
	foreach ($_GET as $GetKey => $GetValue) {
		if (gettype($GetValue) != 'array') {
			$_GET[$GetKey] = DB_escape_string(htmlspecialchars($GetValue,ENT_QUOTES,'UTF-8'));
		}
	}
} else { //set SESSION['FormID'] before the a user has even logged in
	$_SESSION['FormID'] = sha1(uniqid(mt_rand(), true));
}

include($PathPrefix . 'includes/LanguageSetup.php');
$FirstLogin = False;

if(basename($_SERVER['SCRIPT_NAME'])=='Logout.php'){
	if (isset($_SESSION['Favourites'])) {
		//retrieve the sql data;
		$sql = "SELECT href, caption FROM favourites WHERE userid='" . $_SESSION['UserID'] . "'";
		$ErrMsg = _('Failed to retrieve favorites');
		$result = DB_query($sql,$ErrMsg);
		if (DB_num_rows($result)>0) {
			$sql = array();
			while ($myrow = DB_fetch_array($result)) {
				if (!isset($_SESSION['Favourites'][$myrow['href']])) {//The script is removed;
					$sql[] = "DELETE FROM favourites WHERE href='" . $myrow['href'] . "' AND userid='" . $_SESSION['UserID'] . "'";

				} else {
					unset($_SESSION['Favourites'][$myrow['href']]);
				}
			}
			if (count($_SESSION['Favourites']) >0) {
				$sqli = "INSERT INTO favourites(href,caption,userid) VALUES ";
				$k = 0;
				foreach ($_SESSION['Favourites'] as $url=>$ttl) {
					if ($k) {
						$sqli .=",";
					}
					$sqli .= "('" . $url . "', '" . $ttl . "', '" . $_SESSION['UserID'] . "')";
					$k++;
				}
			}
			foreach ($sql as $sq) {
				$result = DB_query($sq);
			}
			if (isset($sqli)) {
				$result = DB_query($sqli);
			}
		} else {

			$sqli = "INSERT INTO favourites(href,caption,userid) VALUES ";
			$k = 0;
			foreach ($_SESSION['Favourites'] as $url=>$ttl) {
				if ($k) {
					$sqli .=",";
				}
				$sqli .= "('" . $url . "', '" . $ttl . "','" . $_SESSION['UserID'] . "')";
				$k++;
			}
			if ($k) {
				$result = DB_query($sqli);
			}
		}
	}

	header('Location: index.php');
} elseif (isset($AllowAnyone)){ /* only do security checks if AllowAnyone is not true */
	if (!isset($_SESSION['DatabaseName'])){
		$_SESSION['AllowedPageSecurityTokens'] = array();
		$_SESSION['DatabaseName'] = $DefaultDatabase;
		$_SESSION['CompanyName'] = $DefaultDatabase;
	}
	include_once ($PathPrefix . 'includes/ConnectDB_' . $DBType . '.inc');
	include($PathPrefix . 'includes/GetConfig.php');
} else {
	include $PathPrefix . 'includes/UserLogin.php';	/* Login checking and setup */

	if (isset($_POST['UserNameEntryField']) AND isset($_POST['Password'])) {
		$rc = userLogin($_POST['UserNameEntryField'], $_POST['Password'], $SysAdminEmail);
		$FirstLogin = true;
	} elseif (empty($_SESSION['DatabaseName'])) {
		$rc = UL_SHOWLOGIN;
	} else {
		$rc = UL_OK;
	}

	/*  Need to set the theme to make login screen nice */
	$Theme = (isset($_SESSION['Theme'])) ? $_SESSION['Theme'] : $DefaultTheme;
	switch ($rc) {
	case  UL_OK; //user logged in successfully
		include($PathPrefix . 'includes/LanguageSetup.php'); //set up the language
		break;

	case UL_SHOWLOGIN:
		include($PathPrefix . 'includes/Login.php');
		exit;

	case UL_BLOCKED:
		die(include($PathPrefix . 'includes/FailedLogin.php'));

	case  UL_CONFIGERR:
		$Title = _('Account Error Report');
		include($PathPrefix . 'includes/header.php');
		echo '<br /><br /><br />';
		prnMsg(_('Your user role does not have any access defined for webERP. There is an error in the security setup for this user account'),'error');
		include($PathPrefix . 'includes/footer.php');
			exit;

	case  UL_NOTVALID:
		$demo_text = '<font size="3" color="red"><b>' .  _('incorrect password') . '</b></font><br /><b>' . _('The user/password combination') . '<br />' . _('is not a valid user of the system') . '</b>';
		die(include($PathPrefix . 'includes/Login.php'));

	case  UL_MAINTENANCE:
		$demo_text = '<font size="3" color="red"><b>' .  _('system maintenance') . '</b></font><br /><b>' . _('webERP is not available right now') . '<br />' . _('during maintenance of the system') . '</b>';
		die(include($PathPrefix . 'includes/Login.php'));

	}
}

/*If the Code $Version - held in ConnectDB.inc is > than the Database VersionNumber held in config table then do upgrades */
if (strcmp($Version,$_SESSION['VersionNumber'])>0 AND (basename($_SERVER['SCRIPT_NAME'])!='UpgradeDatabase.php')) {
	header('Location: UpgradeDatabase.php');
}


If (isset($_POST['Theme']) AND ($_SESSION['UsersRealName'] == $_POST['RealName'])) {
	$_SESSION['Theme'] = $_POST['Theme'];
	$Theme = $_POST['Theme'];
} elseif (isset($_SESSION['Theme'])) {
	$Theme = $_SESSION['Theme'];
} else {
	$Theme = $DefaultTheme;
	$_SESSION['Theme'] = '$DefaultTheme';
}


if ($_SESSION['HTTPS_Only']==1){
	if ($_SERVER['HTTPS']!='on'){
		prnMsg(_('webERP is configured to allow only secure socket connections. Pages must be called with https://') . ' .....','error');
		exit;
	}
}



// Now check that the user as logged in has access to the page being called. $SecurityGroups is an array of
// arrays defining access for each group of users. These definitions can be modified by a system admin under setup


if (!is_array($_SESSION['AllowedPageSecurityTokens']) AND !isset($AllowAnyone)) {
	$Title = _('Account Error Report');
	include($PathPrefix . 'includes/header.php');
	echo '<br /><br /><br />';
	prnMsg(_('Security settings have not been defined for your user account. Please advise your system administrator. It could also be that there is a session problem with your PHP web server'),'error');
	include($PathPrefix . 'includes/footer.php');
	exit;
}

/*The page security variable is now retrieved from the database in GetConfig.php and stored in the $SESSION['PageSecurityArray'] array
 * the key for the array is the script name - the script name is retrieved from the basename ($_SERVER['SCRIPT_NAME'])
 */
if (!isset($PageSecurity)){
//only hardcoded in the UpgradeDatabase script - so old versions that don't have the scripts.pagesecurity field do not choke
	$PageSecurity = $_SESSION['PageSecurityArray'][basename($_SERVER['SCRIPT_NAME'])];
}


if (!isset($AllowAnyone)){
	if ((!in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) OR !isset($PageSecurity))) {
		$Title = _('Security Permissions Problem');
		include($PathPrefix . 'includes/header.php');
		echo '<tr>
				<td class="menu_group_items">
					<table width="100%" class="table_index">
						<tr>
							<td class="menu_group_item">
								<b><font style="size:+1; text-align:center;">' . _('The security settings on your account do not permit you to access this function') . '</font></b>
							</td>
						</tr>
					</table>
				</td>
			</tr>';

		include($PathPrefix . 'includes/footer.php');
		exit;
	}
}

//$PageSecurity = 9 hard coded for supplier access Supplier access must have just 9 and 0 tokens
if (in_array(9,$_SESSION['AllowedPageSecurityTokens']) AND count($_SESSION['AllowedPageSecurityTokens'])==2){
	$SupplierLogin = 1;
} else {
	$SupplierLogin = 0; //false
}
if (in_array(1,$_SESSION['AllowedPageSecurityTokens']) AND count($_SESSION['AllowedPageSecurityTokens'])==2){
	$CustomerLogin = 1;
} else {
	$CustomerLogin = 0;
}
if (in_array($_SESSION['PageSecurityArray']['WWW_Users.php'], $_SESSION['AllowedPageSecurityTokens'])) { /*System administrator login */
	$debug = 1; //allow debug messages
} else {
	$debug = 0; //don't allow debug messages
}

if ($FirstLogin AND !$SupplierLogin AND !$CustomerLogin AND $_SESSION['ShowDashboard']==1) {
	header('Location: ' . $PathPrefix .'Dashboard.php');
}


function CryptPass( $Password ) {
    if (PHP_VERSION_ID < 50500) {
        $Salt = base64_encode(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
        $Salt = str_replace('+', '.', $Salt);
        $Hash = crypt($Password, '$2y$10$' . $Salt . '$');
    } else {
        $Hash = password_hash($Password,PASSWORD_DEFAULT);
    }
    return $Hash;
 }

 function VerifyPass($Password, $Hash) {
     if(PHP_VERSION_ID < 50500) {
         return (crypt($Password,$Hash)==$Hash);
     } else {
         return password_verify($Password,$Hash);
     }
 }


if (sizeof($_POST) > 0 AND !isset($AllowAnyone)) {
	/*Security check to ensure that the form submitted is originally sourced from webERP with the FormID = $_SESSION['FormID'] - which is set before the first login*/
	if (!isset($_POST['FormID']) OR ($_POST['FormID'] != $_SESSION['FormID'])) {
		$Title = _('Error in form verification');
		include('includes/header.php');
		prnMsg(_('This form was not submitted with a correct ID') , 'error');
		include('includes/footer.php');
		exit;
	}
}
?>

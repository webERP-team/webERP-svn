<?php
/* $Version is compared against the value for config.confvalue WHERE confname=VersionNumber in the database
 * this value is saved in the $_SESSION['Versionumber'] when includes/GetConfig.php is run
 * if  VersionNumber is < $Version  then the DB update script is run */

$Version='4.15.1'; //must update manually every time there is a DB change

require_once ($PathPrefix .'includes/MiscFunctions.php');
if (!isset($_SESSION['DatabaseName'])){
	if (isset($_POST['CompanyNameField'])){
		if (isset($CompanyList) AND is_array($CompanyList)) {
			foreach ($CompanyList as $CompanyEntryKey => $CompanyEntry){
				if (is_dir('./companies/'. $CompanyEntry['database']) AND ($CompanyEntryKey == $_POST['CompanyNameField']) ) {
					$_SESSION['DatabaseName'] = $CompanyEntry['database'];
					$_SESSION['CompanyName'] = $CompanyEntry['company'];
					break;
				}
			}
			if (!isset($_SESSION['DatabaseName'])) {
				prnMsg(_('You do not seem to have a database configured correctly to use with webERP. Check you database exists and there is a corresponding directory in the \'companies\' directory of the same name. Contact your system administrator for assistance.'),'error');
				exit;
			}
            if (ContainsIllegalCharacters( $_SESSION['DatabaseName'])){
				prnMsg(_('The company database being logged into cannot contain any of the illegal characters'),'error');
				echo '<br /><a href="index.php">' . _('Back to login page') . '</a>';
				exit;
            }
        }
        //provision for old setup - delete when we have a reliable upgrade for config.php
            else {
           if (ContainsIllegalCharacters($_POST['CompanyNameField'])){
				prnMsg(_('The company database being logged into cannot contain any of the illegal characters'),'error');
				echo '<br /><a href="index.php">' . _('Back to login page') . '</a>';
				exit;
            }
            if (is_dir('companies/' . $_POST['CompanyNameField']) AND $_POST['CompanyNameField'] != '..'){
				$_SESSION['DatabaseName'] = $_POST['CompanyNameField'];
				$_SESSION['CompanyName'] =  $_POST['CompanyNameField'];
            } else {
				prnMsg(_('The company name entered' . ' (' . $_POST['CompanyNameField'] . ') ' . 'is not configured for use with this installation of webERP. Check that a directory named ' . $_POST['CompanyNameField'] . ' is set up under the companies sub-directory.'),'error');
				prnMsg(_('Check the company name entered' . ' (' . $_POST['CompanyNameField'] . ') ' . 'is the same as the database name.'),'error');
				prnMsg(_('The company name abbreviation entered at login must also have a company directory defined. See your system administrator'),'error');
				echo '<br /><a href="index.php">' . _('Back to login page') . '</a>';
			exit;
            }
        } // end provision for old setup
		include_once ($PathPrefix . 'includes/ConnectDB_' . $DBType . '.inc');
	} elseif (isset($DatabaseName)) { /* Scripts that do not require a login must have the $DatabaseName variable set in hard code */
		$_SESSION['DatabaseName'] = $DatabaseName;
		$_SESSION['CompanyName']= $DatabaseName;
		include_once ($PathPrefix . 'includes/ConnectDB_' . $DBType . '.inc');
	}
} else {
	include_once ($PathPrefix . 'includes/ConnectDB_' . $DBType . '.inc');
}
?>

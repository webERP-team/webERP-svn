<?php


include ('includes/session.php');
include ('includes/SQL_CommonFunctions.inc');

/* Was the Cancel button pressed the last time through ? */

if (isset($_POST['EnterCompanyDetails'])) {

	header ('Location:' . $RootPath . '/CompanyPreferences.php');
	exit;
}
$Title = _('Make New Company Database Utility');

include('includes/header.php');

/* Your webserver user MUST have read/write access to here,
	otherwise you'll be wasting your time */
if (! is_writeable('./companies/')){
		prnMsg(_('The web-server does not appear to be able to write to the companies directory to create the required directories for the new company and to upload the logo to. The system administrator will need to modify the permissions on your installation before a new company can be created'),'error');
		include('includes/footer.php');
		exit;
}

if (isset($_POST['submit']) AND isset($_POST['NewDatabase'])) {

	if(mb_strlen($_POST['NewDatabase'])>32
		OR ContainsIllegalCharacters($_POST['NewDatabase'])){
		prnMsg(_('Company database must not contain spaces, \& or " or \''),'error');
	} else {
		$_POST['NewDatabase'] = strtolower($_POST['NewDatabase']);
		echo '<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '">
			<div class="centre">
			<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />';

		/* check for directory existence */
		if (!file_exists('./companies/' . $_POST['NewDatabase'])
				AND (isset($_FILES['LogoFile']) AND $_FILES['LogoFile']['name'] !='')) {

			$result    = $_FILES['LogoFile']['error'];
			$UploadTheLogo = 'Yes'; //Assume all is well to start off with
			$filename = './companies/' . $_POST['NewDatabase'] . '/logo.jpg';

			//But check for the worst
			if (mb_strtoupper(mb_substr(trim($_FILES['LogoFile']['name']),mb_strlen($_FILES['LogoFile']['name'])-3))!='JPG'){
				prnMsg(_('Only jpg files are supported - a file extension of .jpg is expected'),'warn');
				$UploadTheLogo ='No';
			} elseif ( $_FILES['LogoFile']['size'] > ($_SESSION['MaxImageSize']*1024)) { //File Size Check
				prnMsg(_('The file size is over the maximum allowed. The maximum size allowed in KB is') . ' ' . $_SESSION['MaxImageSize'],'warn');
				$UploadTheLogo ='No';
			} elseif ( $_FILES['LogoFile']['type'] == "text/plain" ) {  //File Type Check
				prnMsg( _('Only graphics files can be uploaded'),'warn');
				$UploadTheLogo ='No';
			} elseif (file_exists($filename)){
				prnMsg(_('Attempting to overwrite an existing item image'),'warn');
				$result = unlink($filename);
				if (!$result){
					prnMsg(_('The existing image could not be removed'),'error');
					$UploadTheLogo ='No';
				}
			}

			if ($_POST['CreateDB']==TRUE){
				/* Need to read in the sql script and process the queries to initate a new DB */

				$result = DB_query('CREATE DATABASE ' . $_POST['NewDatabase']);

				if ($DBType=='postgres'){

					$PgConnStr = 'dbname=' . $_POST['NewDatabase'];
					if ( isset($host) && ($host != "")) {
						$PgConnStr = 'host=' . $host . ' ' . $PgConnStr;
					}

					if (isset( $DBUser ) && ($DBUser != "")) {
						// if we have a user we need to use password if supplied
						$PgConnStr .= " user=".$DBUser;
						if ( isset( $DBPassword ) && ($DBPassword != "") ) {
							$PgConnStr .= " password=".$DBPassword;
						}
					}
					$db = pg_connect( $PgConnStr );
					$SQLScriptFile = file('./sql/pg/country_sql/default.psql');

				} elseif ($DBType =='mysql') { //its a mysql db < 4.1
					mysql_select_db($_POST['NewDatabase'],$db);
					$SQLScriptFile = file('./sql/mysql/country_sql/default.sql');
				} elseif ($DBType =='mysqli') { //its a mysql db using the >4.1 library functions
					mysqli_select_db($db,$_POST['NewDatabase']);
					$SQLScriptFile = file('./sql/mysql/country_sql/default.sql');
				}

				$ScriptFileEntries = sizeof($SQLScriptFile);
				$ErrMsg = _('The script to create the new company database failed because');
				$SQL ='';
				$InAFunction = false;

				for ($i=0; $i<=$ScriptFileEntries; $i++) {

					$SQLScriptFile[$i] = trim($SQLScriptFile[$i]);

					if (mb_substr($SQLScriptFile[$i], 0, 2) != '--'
						AND mb_substr($SQLScriptFile[$i], 0, 3) != 'USE'
						AND mb_strstr($SQLScriptFile[$i],'/*')==FALSE
						AND mb_strlen($SQLScriptFile[$i])>1){

						$SQL .= ' ' . $SQLScriptFile[$i];

						//check if this line kicks off a function definition - pg chokes otherwise
						if (mb_substr($SQLScriptFile[$i],0,15) == 'CREATE FUNCTION'){
							$InAFunction = true;
						}
						//check if this line completes a function definition - pg chokes otherwise
						if (mb_substr($SQLScriptFile[$i],0,8) == 'LANGUAGE'){
							$InAFunction = false;
						}
						if (mb_strpos($SQLScriptFile[$i],';')>0 AND ! $InAFunction){
							$SQL = mb_substr($SQL,0,mb_strlen($SQL)-1);
							$result = DB_query($SQL, $ErrMsg);
							$SQL='';
						}

					} //end if its a valid sql line not a comment
				} //end of for loop around the lines of the sql script
			} //end if CreateDB was checked

			prnMsg (_('Attempting to create the new company directories') . '.....<br />', 'info');
			$Result = mkdir('./companies/' . $_POST['NewDatabase']);

			// Sub-directories listed alphabetically to ease referencing.
			$Result = mkdir('./companies/' . $_POST['NewDatabase'] . '/EDI_Incoming_Orders');
			$Result = mkdir('./companies/' . $_POST['NewDatabase'] . '/EDI_Pending');
			$Result = mkdir('./companies/' . $_POST['NewDatabase'] . '/EDI_Sent');
			$Result = mkdir('./companies/' . $_POST['NewDatabase'] . '/FormDesigns');
			$Result = mkdir('./companies/' . $_POST['NewDatabase'] . '/part_pics');
			$Result = mkdir('./companies/' . $_POST['NewDatabase'] . '/reports');
			$Result = mkdir('./companies/' . $_POST['NewDatabase'] . '/reportwriter');

			// XML files listed alphabetically to ease referencing.
			copy ('./companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/FGLabel.xml',       './companies/' . $_POST['NewDatabase'] . '/FormDesigns/FGLabel.xml');
			copy ('./companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/GoodsReceived.xml', './companies/' . $_POST['NewDatabase'] . '/FormDesigns/GoodsReceived.xml');
			copy ('./companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/Journal.xml',       './companies/' . $_POST['NewDatabase'] . '/FormDesigns/Journal.xml');
			copy ('./companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/PickingList.xml',   './companies/' . $_POST['NewDatabase'] . '/FormDesigns/PickingList.xml');
			copy ('./companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/PurchaseOrder.xml', './companies/' . $_POST['NewDatabase'] . '/FormDesigns/PurchaseOrder.xml');
			copy ('./companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/QALabel.xml',       './companies/' . $_POST['NewDatabase'] . '/FormDesigns/QALabel.xml');
			copy ('./companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/ShippingLabel.xml', './companies/' . $_POST['NewDatabase'] . '/FormDesigns/ShippingLabel.xml');
			copy ('./companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/WOPaperwork.xml',   './companies/' . $_POST['NewDatabase'] . '/FormDesigns/WOPaperwork.xml');

			/*OK Now upload the logo */
			if ($UploadTheLogo=='Yes'){
				$result  =  move_uploaded_file($_FILES['LogoFile']['tmp_name'], $filename);
				$message = ($result) ? _('File url') . '<a href="' . $filename . '">' .  $filename . '</a>' : _('Something is wrong with uploading a file');
			}

		} else {
			prnMsg(_('This company cannot be added because either it already exists or no logo is being uploaded!'),'error');

			if (isset($_FILES['LogoFile'])){
				prnMsg('_Files[LogoFile] ' . _('is set ok'), 'info');
			} else  {
				prnMsg('_FILES[LogoFile] ' . _('is not set'), 'info');
			}

			if($_FILES['LogoFile']['name'] !=''){
				prnMsg('_FILES[LogoFile][name] ' . _('is not blank'), 'info');
			} else  {
				prnMsg('_FILES[LogoFile][name] ' . _('is blank'), 'info');
			}

			echo '</div>
				</form>';
			include('includes/footer.php');
			exit;
		}


         //now update the config.php file if using the obfuscated database login else we don't want it there
        if (isset($CompanyList) && is_array($CompanyList)) {
            $ConfigFile = './config.php';
            $config_php = join('', file($ConfigFile));
            //fix the Post var - it is being preprocessed with slashes and entity encoded which we do not want here
            $_POST['NewCompany'] =  html_entity_decode($_POST['NewCompany'],ENT_QUOTES,'UTF-8');
            $config_php = preg_replace('/\/\/End Installed companies-do not change this line/', "\$CompanyList[] = array('database'=>'".$_POST['NewDatabase']."' ,'company'=>'".$_POST['NewCompany']."');\n//End Installed companies-do not change this line", $config_php);
            if (!$fp = fopen($ConfigFile, 'wb')) {
                prnMsg(_('Cannot open the configuration file' . ': ').$ConfigFile.". Please add the following line to the end of the file:\n\$CompanyList[] = array('database'=>'".$_POST['NewDatabase']."' ,'company'=>'".htmlspecialchars($_POST['NewCompany'],ENT_QUOTES,'UTF-8').");",'error');
            } else {
                fwrite ($fp, $config_php);
                fclose ($fp);
            }
        }

		$_SESSION['DatabaseName'] = $_POST['NewDatabase'];

		unset ($_SESSION['CustomerID']);
		unset ($_SESSION['SupplierID']);
		unset ($_SESSION['StockID']);
		unset ($_SESSION['Items']);
		unset ($_SESSION['CreditItems']);

		$SQL ="UPDATE config SET confvalue='companies/" . $_POST['NewDatabase'] . "/EDI_Incoming_Orders' WHERE confname='EDI_Incoming_Orders'";
		$result = DB_query($SQL);
		$SQL ="UPDATE config SET confvalue='companies/" . $_POST['NewDatabase'] . "/EDI_Pending' WHERE confname='EDI_MsgPending'";
		$result = DB_query($SQL);
		$SQL ="UPDATE config SET confvalue='companies/" . $_POST['NewDatabase'] . "/EDI_Sent' WHERE confname='EDI_MsgSent'";
		$result = DB_query($SQL);
		$SQL ="UPDATE config SET confvalue='companies/" . $_POST['NewDatabase'] . "/part_pics' WHERE confname='part_pics_dir'";
		$result = DB_query($SQL);
		$SQL ="UPDATE config SET confvalue='companies/" . $_POST['NewDatabase'] . "/reports' WHERE confname='reports_dir'";
		$result = DB_query($SQL);

		//add new company
		$SQL = "UPDATE companies SET coyname='" . $_POST['NewCompany'] . "' WHERE coycode = 1";
		$result = DB_query($SQL);

		$ForceConfigReload=true;
		include('includes/GetConfig.php');


		prnMsg (_('The new company database has been created for' . ' ' . htmlspecialchars($_POST['NewCompany'], ENT_QUOTES, 'UTF-8') . '. ' . _('The company details and parameters should now be set up for the new company. NB: Only a single user "admin" is defined with the password "weberp" in the new company database. A new system administrator user should be defined for the new company and this account deleted immediately.')), 'info');

		echo '<p><a href="', $RootPath, '/CompanyPreferences.php">', _('Set Up New Company Details'), '</a></p>
			<p><a href="', $RootPath, '/SystemParameters.php">', _('Set Up Configuration Details'), '</a></p>
			<p><a href="', $RootPath, '/WWW_Users.php">', _('Set Up User Accounts'), '</a></p>
			</div>
		</form>';
		include('includes/footer.php');
		exit;
	}

}


prnMsg (_('This utility will create a new company') . '. ' .
		_('If the company name already exists then you cannot recreate it') . '.', 'info', _('PLEASE NOTE'));

echo '<br /><br />
	<form method="post" action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" enctype="multipart/form-data">
	<div class="centre">
		<input type="hidden" name="FormID" value="', $_SESSION['FormID'], '" />
		<table>
			<tr>
				<td>' . _('Enter the name of the database used for the company up to 32 characters in lower case') . ':</td>
				<td><input type="text" size="33" maxlength="32" name="NewDatabase" /></td>
			</tr>
			<tr>
				<td>' . _('Enter a unique name for the company of up to 50 characters') . ':</td>
				<td><input type="text" size="33" maxlength="32" name="NewCompany" /></td>
			</tr>
			<tr>
				<td>' .  _('Logo Image File (.jpg)') . ':</td>
				<td><input type="file" required="true" id="LogoFile" name="LogoFile" /></td>
			</tr>
			<tr>
				<td>' . _('Create Database?') . '</td>
				<td><input type="checkbox" name="CreateDB" /></td>
			</tr>
		</table>
		<br />
		<input type="submit" name="submit" value="', _('Proceed'), '" />
	</div>
	</form>';

include('includes/footer.php');
?>
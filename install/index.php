<?php
	ini_set('max_execution_time', "600");
	session_name('weberp_installation');
	session_start();
if(!extension_loaded('mbstring')){
	echo 'The php-mbstring extension has not been installed or loaded, please correct your php configuration first';
	exit;
}

/*
 * Web ERP Installer
 * Step 1: Licence acknowledgement and Choose Language
 * Step 2: Check requirements
 * Step 3: Database connection
 * Step 4: Company details
 * Step 5: Administrator account details
 * Step 6: Finalise
**/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>WebERP Installation Wizard</title>
    <link rel="stylesheet" type="text/css" href="installer.css" />
</head>
<body>
<div id="CanvasDiv">
	<?php
	error_reporting(1);

	//get the php-gettext function
	//When users have not select the language, we guess user's language via the http header information.
	//once the user has selected their language, use the language user selected
	if(!isset($_POST['Language'])){
		if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){//get users preferred language
			$ClientLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
			switch($ClientLang){
				case 'ar':
					$Language = 'ar_EG.utf8';
					break;
				case 'cs':
					$Language = 'cs_CZ.utf8';
					break;
				case 'de':
					$Language = 'de_DE.utf8';
					break;
				case 'el':
					$Language = 'el_GR.utf8';
					break;
				case 'en':
					$Language = 'en_GB.utf8';
					break;
				case 'es':
					$Language = 'es_ES.utf8';
					break;
				case 'et':
					$Language = 'et_EE.utf8';
					break;
				case 'fa':
					$Language = 'fa_IR.utf8';
					break;
				case 'fr':
					$Langauge = 'fr_CA.utf8';
					break;
				case 'hi':
					$Language = 'hi_IN.utf8';
					break;
				case 'hr':
					$Language = 'hr_HR.utf8';
					break;
				case 'hu':
					$Language = 'hu_HU.utf8';
					break;
				case 'id':
					$Language = 'id_ID.utf8';
					break;
				case 'it':
					$Language = 'it_IT.utf8';
					break;
				case 'ja':
					$Language = 'ja_JP.utf8';
					break;
				case 'lv':
					$Language = 'lv_LV.utf8';
					break;
				case 'nl':
					$Language = 'nl_NL.utf8';
					break;
				case 'pl':
					$Language = 'pl_PL.utf8';
					break;
				case 'pt':
					$Language = 'pt-PT.utf8';
					break;
				case 'ro':
					$Language = 'ro_RO.utf8';
					break;
				case 'ru':
					$Language = 'ru_RU.utf8';
					break;
				case 'sq':
					$Language = 'sq_AL.utf8';
					break;
				case 'sv':
					$Language = 'sv_SE.utf8';
					break;
				case 'sw':
					$Language = 'sw_KE.utf8';
					break;
				case 'tr':
					$Language = 'tr_TR.utf8';
					break;
				case 'vi':
					$Language = 'vi_VN.utf8';
					break;
				case 'zh':
					$Language = 'zh_CN.utf8';
					break;
				default:
					$Language = 'en_GB.utf8';

			}
			$DefaultLanguage = $Language;
			if(isset($_SESSION['Language'])){
				unset($_SESSION['Language']);
			}

		}else{
			$Language = 'en_US.utf8';
			$DefaultLanguage = 'en_US.utf8';
		}
		//This is the first step - let us initialise some variables (esp. important if installer is rerun)
		$DatabaseName = '';
		$DefaultDatabase = '';

	}else{

		$Language = $_POST['Language'];
		if(substr($Language,0,2)=='zh'){//To help set the default time zone
			date_default_timezone_set('Asia/Shanghai');
		}
		$DefaultLanguage = $_POST['Language'];

		}

	$PathPrefix = '../';//To make the LanguageSetup.php script run properly
	include('../includes/LanguageSetup.php');
	include('../includes/MiscFunctions.php');
	$DefaultTheme = 'xenos';

	// Prevent the installation file from running again:
	if(file_exists('../config.php') or file_exists('../Config.php')){
		echo _('It seems that the system has been already installed. If you want to install again, please remove the config.php file first');
		exit;
	}
	if(isset($_POST['Install'])){//confirm the final install data, the last validation step before we submit the data
		//first do necessary validation
		//Since user may have changed the DatabaseName so we need check it again
		$InputError = 0;
		if(!empty($_POST['CompanyName'])){
			//validate the Database name setting
			//The mysql database name cannot contains illegal characters such as "/","\","." etc
			//and it should not contains illegal characters as file name such as "?""%"<"">"" " etc

			if(!preg_match(',^[^/\\\?%:\|<>\"]+$,',$_POST['CompanyName'])){
				$InputError = 1;
				echo _('The Company names cannot contain illegal characters such as /\?%:|<>"');

			}
			$CompanyName= $_POST['CompanyName'];
		}else{
				$InputError = 1;
				echo _('The Company Name name should not be empty');
		}
		//provision for differing database post inputs - need to review and make these consistent
		if ( (isset($_POST['DatabaseName'])  && !empty($_POST['DatabaseName'])) && (!isset($_POST['Database']) || empty($_POST['Database']))) $_POST['Database'] = $_POST['DatabaseName'];
		if(!empty($_POST['Database'])){
			//validate the Database name setting
			//The mysql database name cannot contains illegal characters such as "/","\","." etc
			//and it should not contains illegal characters as file name such as "?""%"<"">"" " etc

			if(!preg_match(',[a-zA-Z0-9_\&\-\ ]*,',$_POST['Database'])){
				$InputError = 1;
				echo _('The database name should not contains illegal characters such as "/\?%:|<>" etc');

			}
			$DatabaseName = strtolower($_POST['Database']);
		}else{
			$InputError = 1;
			echo _('The database name should not be empty');
		}
		if(!empty($_POST['TimeZone'])){
			if(preg_match(',(Etc|Pacific|India|Europe|Australia|Atlantic|Asia|America|Africa)/[A-Z]{1}[a-zA-Z\-_/]+,',$_POST['TimeZone'])){
				$TimeZone = $_POST['TimeZone'];
			}else{
				$InputError = 1;
				echo _('The timezone must be legal');
			}
		}
		$OnlyDemo = 0;
		$DualCompany = 0;
		$NewCompany = 0;
		if(!empty($_POST['Demo']) and $_POST['Demo'] == 'on'){
			if(strtolower($DatabaseName) === 'weberpdemo'){//user select to install the weberpdemo
				$OnlyDemo = 1;

			}else{
				$DualCompany = 1; //user choose to install the demo company and production environment
			}
		}else{//user only choose to install the new weberp company
			$NewCompany = 1;
		}
		if(!empty($_POST['Email']) and IsEmailAddress($_POST['Email'])){
			$Email = trim($_POST['Email']);

		}else{
			$InputError = 1;
			echo _('You must enter a valid email address for the Administrator.');
		}
		if(!empty($_POST['webERPPassword']) and !empty($_POST['PasswordConfirm']) and $_POST['webERPPassword'] == $_POST['PasswordConfirm']){
			$AdminPassword = $_POST['webERPPassword'];
		}else{
			$InputError = 1;
			echo _('Please correct the password. The password is either blank, or the password check does not match.');

		}
		if(!empty($_POST['HostName'])){
			// As HTTP_HOST is user input, ensure it only contains characters allowed
 			// in hostnames. See RFC 952 (and RFC 2181).
    			// $_SERVER['HTTP_HOST'] is lowercased here per specifications.
			$_POST['HostName'] = strtolower($_POST['HostName']);
			$HostValid = preg_match('/^\[?(?:[a-zA-Z0-9-:\]_]+\.?)+$/', $_POST['HostName']);
			if($HostValid){
				$HostName = $_POST['HostName'];
			}else{
				echo _('The Host Name is not a valid name.');
				exit;
			}

		}else{
			$InputError = 1;
			echo _('The Host Name must not be empty.');
		}
		if(!empty($_POST['UserName']) and strlen($_POST['UserName'])<=16){//mysql database user
			$UserName = $_POST['UserName'];
		}else{
			$InputError = 1;
			echo _('The user name cannot be empty and length must not be over 16 characters.');
		}
		if(isset($_POST['Password'])){//mysql database password
			$Password = $_POST['Password'];
		}
		if(!empty($_POST['MysqlExt'])){//get the mysql connect extension
			$DBConnectType = 'mysql';
		}else{
			$DBConnectType = 'mysqli';
		}
		if(!empty($_POST['UserLanguage'])){
			if(preg_match(',^[a-z]{2}_[A-Z]{2}.utf8$,',$_POST['UserLanguage'])){
				$UserLanguage = $_POST['UserLanguage'];
			}else{
				$InputError = 1;
				echo _('The user language defintion is not in the correct format');
			}
		}
		If(!empty($_FILES['LogoFile'])){//We check the file upload situation
			if($_FILES['LogoFile']['error'] == UPLOAD_ERR_INI_SIZE || $_FILES['LogoFile']['error'] == UPLOAD_ERR_FORM_SIZE){//the file is over the php.ini limit or over the from limit
				$InputError = 1;
				if(upload_max_filesize < 0.01){
					echo _('The company logo file failed to upload due to it\'s size. The file was over the upload_max_filesize set in your php.ini configuration.');

				}else{
					echo _('The logo file failed to upload as it was over 10KB size limit.');
				}

			}elseif($_FILES['LogoFile']['error'] == UPLOAD_ERR_OK){//The file has been successfully uploaded
				$File_Temp_Name = $_FILES['LogoFile']['tmp_name'];
			}elseif($_FILES['LogoFile']['error'] == UPLOAD_ERR_NO_FILE){//There are no file uploaded by users
				$File_To_Copy = 1;
			}

		}
		if(!empty($_POST['CountrySQL'])){
			if(preg_match('/[a-zA-Z_-]+(\.sql)/',$_POST['CountrySQL'])){
				$CountrySQL = $_POST['CountrySQL'];
			}else{
				$InputError = 1;
				echo _('The country SQL file name must only contain letters,"-","_"');
			}
		}else{
				$InputError = 1;
				echo _('There is no country SQL file selected. Please select a file.');

		}
		if($InputError == 1){//return to the company configuration stage
			if($DBConnectType=='mysqli'){
				CompanySetup($UserLanguage,$HostName,$UserName,$Password,$DatabaseName,$MysqlExt = FALSE);
			}else{
				CompanySetup($UserLanguage,$HostName,$UserName,$Password,$DatabaseName,1);
			}

		}else{
		    //start to installation
		    $CompanyList = array();
			$Path_To_Root = '..';
			$Config_File = $Path_To_Root . '/config.php';
			if((isset($DualCompany) and $DualCompany == 1) or (isset($NewCompany) and $NewCompany == 1)){
				$CompanyDir = $Path_To_Root . '/companies/' . $DatabaseName;
			    $Result = mkdir($CompanyDir);
				$Result = mkdir($CompanyDir . '/part_pics');
				$Result = mkdir($CompanyDir . '/EDI_Incoming_Orders');
				$Result = mkdir($CompanyDir . '/reports');
				$Result = mkdir($CompanyDir . '/EDI_Sent');
				$Result = mkdir($CompanyDir . '/EDI_Pending');
				$Result = mkdir($CompanyDir . '/reportwriter');
				$Result = mkdir($CompanyDir . '/pdf_append');
				$Result = mkdir($CompanyDir . '/FormDesigns');
				copy ($Path_To_Root . '/companies/weberpdemo/FormDesigns/GoodsReceived.xml', $CompanyDir . '/FormDesigns/GoodsReceived.xml');
				copy ($Path_To_Root . '/companies/weberpdemo/FormDesigns/PickingList.xml', $CompanyDir . '/FormDesigns/PickingList.xml');
				copy ($Path_To_Root . '/companies/weberpdemo/FormDesigns/PurchaseOrder.xml', $CompanyDir . '/FormDesigns/PurchaseOrder.xml');
				copy ($Path_To_Root . '/companies/weberpdemo/FormDesigns/Journal.xml', $CompanyDir . '/FormDesigns/Journal.xml');
				if(isset($File_Temp_Name)){
					$Result = move_uploaded_file($File_Temp_Name, $CompanyDir . '/logo.jpg');

				}elseif(isset($File_To_Copy)){
					$Result = copy ($Path_To_Root . '/logo_server.jpg',$CompanyDir.'/logo.jpg');
				}
			}
			if ( isset($NewCompany) and ($NewCompany == 1)) {
			    $CompanyList[] = array('database' => $DatabaseName, 'company' => $CompanyName);
			} elseif (isset($DualCompany) and $DualCompany == 1) {
			    $CompanyList[] = array('database' => $DatabaseName, 'company' => $CompanyName);
			    $CompanyList[] = array('database' => 'weberpdemo', 'company' => _('WebERP Demo Company'));
			} else {
			    //make sure we have at least the demo
			    $CompanyList[] = array('database' => 'weberpdemo', 'company' => _('WebERP Demo Company'));
			}

			//$msg holds the text of the new config.php file
			$msg = "<?php\n\n";
			$msg .= "// User configurable variables\n";
			$msg .= "//---------------------------------------------------\n\n";
			$msg .= "// Default language to use for the login screen and the setup of new users.\n";
			$msg .= "\$DefaultLanguage = '" . $UserLanguage . "';\n\n";
			$msg .= "// Default theme to use for the login screen and the setup of new users.\n";
			$msg .= "\$DefaultTheme = '" . $DefaultTheme . "';\n\n";
			$msg .= "// Whether to display the demo login and password or not on the login screen\n";
			$msg .= "\$AllowDemoMode = FALSE;\n\n";
			$msg .= "// Connection information for the database\n";
			$msg .= "// \$host is the computer ip address or name where the database is located\n";
			$msg .= "// assuming that the webserver is also the sql server\n";
			$msg .= "\$host = '" . $HostName . "';\n\n";
			$msg .= "// assuming that the web server is also the sql server\n";
			$msg .= "\$DBType = '".$DBConnectType."';\n";
		        $msg .= "//assuming that the web server is also the sql server\n";
			$msg .= "\$DBUser = '".$UserName."';\n";
			$msg .= "\$DBPassword = '".$Password."';\n";
			$msg .= "// The timezone of the business - this allows the possibility of having;\n";
			$msg .= "date_default_timezone_set('".$TimeZone."');\n";
			$msg .= "putenv('TZ=" . $TimeZone ."');\n";
			$msg .= "\$AllowCompanySelectionBox = 'ShowSelectionBox';\n";
			$msg .= "//The system administrator name use the user input mail;\n";
			if(strtolower($AdminEmail) != 'admin@weberp.org'){
			$msg .= "\$SysAdminEmail = '".$AdminEmail."';\n";
			}
			if(isset($NewCompany)){
				$msg .= "\$DefaultDatabase = '".$DatabaseName."';\n";
			}else{
				$msg .= "\$DefaultDatabase = 'weberpdemo';\n";
			}
			$msg .= "\$SessionLifeTime = 3600;\n";
			$msg .= "\$MaximumExecutionTime = 120;\n";
			$msg .= "\$DefaultClock = 12;\n";
			$msg .= "\$RootPath = dirname(htmlspecialchars(\$_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'));\n";
			$msg .= "if (isset(\$DirectoryLevelsDeep)){\n";
			$msg .= "   for (\$i=0;\$i<\$DirectoryLevelsDeep;\$i++){\n";
			$msg .= "		\$RootPath = mb_substr(\$RootPath,0, strrpos(\$RootPath,'/'));\n";
			$msg .= "	}\n";
			$msg .= "}\n";

			$msg .= "if (\$RootPath == '/' OR \$RootPath == '\\\') {\n";
			$msg .= "	\$RootPath = '';\n";
			$msg .= "}\n";
			$msg .= "error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);\n";
			$msg .=  "//Installed companies \n";
			foreach ($CompanyList as $k=>$compinfo)
			{
		        $msg .= "\$CompanyList[".$k."] = array('database'=>'".$compinfo['database']."' ,'company'=>'".addslashes($compinfo['company'])."' );\n"; //simpler to manipulate this way
            }
            $msg .=  "//End Installed companies-do not change this line\n";
            $msg .= "/* Make sure there is nothing - not even spaces after this last ?> */\n";
			$msg .= "?>";

			//write the config.php file since we have test the writability of the root path and companies,
			//there is little possibility that it will fail here. So just an warn if it is failed.
			if(!$zp = fopen($Path_To_Root . '/config.php','w')){
				echo _("Cannot open the configuration file").$Config_File;
			} else {
				if (!fwrite($zp, $msg)){
					fclose($zp);
					echo _("Cannot write to the configuration file").$Config_File;
				}
				//close file
				fclose($zp);
			}
			//Now it is the time to create the mysql data
			//Just get the data from $CountrySQL and read data from this file
			//At the mean time, we should check the user need demo database or not
			if($DBConnectType == 'mysqli'){
				$Db = mysqli_connect($HostName,$UserName,$Password);
				if(!$Db){
					echo _('Failed to connect the database, the error is ').mysqli_connect_error();
				}
			}elseif($DBConnectType == 'mysql'){
				$Db = mysql_connect($HostName,$UserName,$Password);
                if(!$Db){
                    echo _('Failed to connect the database, the error is ').mysql_connect_error();
                }
			}
			$NewSQLFile = $Path_To_Root.'/sql/mysql/country_sql/'.$CountrySQL;
			$DemoSQLFile = $Path_To_Root.'/sql/mysql/country_sql/demo.sql';
			if(!empty($DualCompany) and $DualCompany == 1){
				//we should install the production data and demo data
				$sql = 'CREATE DATABASE IF NOT EXISTS `'.$DatabaseName.'`';
				$result = ($DBConnectType == 'mysqli') ? mysqli_query($Db,$sql) : mysql_query($sql,$Db);
				if(!$result){
					if($DBConnectType == 'mysqli'){
						echo _('Failed to create database '.$DatabaseName.' and the error is '.' '.mysqli_error($Db));
					}else{
						echo _('Failed to create database '.$DatabaseName.' and the error is '.' '.mysql_error($Db));

					}
				}
				$sql = 'CREATE DATABASE IF NOT EXISTS `weberpdemo`';
				$result = ($DBConnectType == 'mysqli') ? mysqli_query($Db,$sql) : mysql_query($sql,$Db);
				if(!$result){
					if($DBConnectType == 'mysqli'){
						echo _('Failed to create database weberpdemo and the error is '.' '.mysqli_error($Db));
					}else{
						echo _('Failed to create database weberpdemo and the error is '.' '.mysql_error($Db));

					}


				}
				PopulateSQLData($NewSQLFile,false,$Db,$DBConnectType,$DatabaseName);
				DBUpdate($Db,$DatabaseName,$DBConnectType,$AdminPassword,$Email,$UserLanguage,$CompanyName);
				PopulateSQLData(false,$DemoSQLFile,$Db,$DBConnectType,'weberpdemo');
				DBUpdate($Db,'weberpdemo',$DBConnectType,$AdminPassword,$Email,$UserLanguage,'weberpdemo');

			}elseif(!empty($NewCompany) and $NewCompany == 1){//only install the production data

				$sql = 'CREATE DATABASE IF NOT EXISTS `'.$DatabaseName.'`';
				$result = ($DBConnectType == 'mysqli')? mysqli_query($Db,$sql) : mysql_query($sql,$Db);
				if(!$result){
					if($DBConnectType == 'mysqli'){
						echo _('Failed to create database '.$DatabaseName.'  and the error is '.' '.mysqli_error($Db));
					}else{
						echo _('Failed to create database '.$DatabaseName.'  and the error is '.' '.mysql_error($Db));

					}
				}
				PopulateSQLData($NewSQLFile,false,$Db,$DBConnectType,$DatabaseName);
				DBUpdate($Db,$DatabaseName,$DBConnectType,$AdminPassword,$Email,$UserLanguage,$CompanyName);

			}else { //if(!empty($OnlyDemo) and $OnlyDemo == 1){//only install the demo data
				$sql = 'CREATE DATABASE IF NOT EXISTS `weberpdemo`';
				$result = ($DBConnectType == 'mysqli') ? mysqli_query($Db,$sql) : mysql_query($sql,$Db);
				if(!$result){
					if($DBConnectType == 'mysqli'){
						echo _('Failed to create database weberpdemo and the error is '.' '.mysqli_error($Db));
					}else{
						echo _('Failed to create database weberpdemo and the error is '.' '.mysql_error($Db));

					}

				}
				PopulateSQLData(false,$DemoSQLFile,$Db,$DBConnectType,'weberpdemo');
				DBUpdate($Db,'weberpdemo',$DBConnectType,$AdminPassword,$Email,$UserLanguage,'weberpdemo');

			}
			session_unset();
			session_destroy();

			header('Location: ' . $Path_To_Root . '/index.php?newDb=1');
			ini_set('max_execution_time', '120');
			echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=' . $Path_To_Root . '/index.php">';



		}//end of the installation

		exit;
	}
	//Handle the database configuration data. We'd like to check if the database information has been input correctly
	//First try mysqli configuration

	if(isset($_POST['DbConfig'])){

		//validate those data first
		$InputError = 0; //Assume the best first
		if(!empty($_POST['HostName'])){
			// As HTTP_HOST is user input, ensure it only contains characters allowed
 			// in hostnames. See RFC 952 (and RFC 2181).
    			// $_SERVER['HTTP_HOST'] is lowercased here per specifications.
			$_POST['HostName'] = strtolower($_POST['HostName']);
			$HostValid = preg_match('/^\[?(?:[a-zA-Z0-9-:\]_]+\.?)+$/', $_POST['HostName']);
			if($HostValid){
				$HostName = $_POST['HostName'];
			}else{
				echo _('The Host Name is illegal');
				exit;
			}


		}else{
			$InputError = 1;
			echo _('The Host Name should not be empty');
		}
		if(!empty($_POST['Database'])){
			//validate the Database name setting
			//The mysql database name cannot contains illegal characters such as "/","\","." etc
			//and it should not contains illegal characters as file name such as "?""%"<"">"" " etc
			//if prefix is set it should be added to database name
			if(!empty($_POST['Prefix'])){
				$_POST['Database'] = $_POST['Prefix'].$_POST['Database'];
			}
			if(preg_match(',[/\\\?%:\|<>\."]+,',$_POST['Database'])){
				$InputError = 1;
				echo _('The database name should be lower case and not contains illegal characters such as "/\?%:|<>"');

			}
			$DatabaseName = $_POST['Database'];
		}else{
			$InputError = 1;
			echo _('The database name should not be empty');
		}

		if(!empty($_POST['Password'])){
			$Password = $_POST['Password'];
		}else{
			$Password = '';
		}
		if(!empty($_POST['UserLanguage'])){
			$UserLanguage = $_POST['UserLanguage'];
		}
		if(!empty($_POST['UserName']) and mb_strlen($_POST['UserName'])<=16){
			$UserName = trim($_POST['UserName']);
		}
		if($InputError == 0){
			if(!empty($_POST['MysqlExt']) and $_POST['MysqlExt']==1){
				DbCheck($UserLanguage,$HostName,$UserName,$Password,$DatabaseName,$_POST['MysqlExt']);
			}else{
				DbCheck($UserLanguage,$HostName,$UserName,$Password,$DatabaseName);
			}
			exit;
		}else{
			echo _('Please correct the displayed error first');
			if(!empty($_POST['MysqlExt'])){
				DbConfig($_POST['UserLanguage'],$_POST['MysqlExt']);
			}else{
				DbConfig($_POST['UserLanguage']);
			}
			exit;
		}
		//	$db = mysqli_connect
		//if everything is OK, then we try to connect the DB, the database should be connect by two types of method, if there is no mysqli
	}//end of users has submit the database configuration data

	?>

    <h1><?php echo _('webERP Installation Wizard'); ?></h1>
	<?php
    	if(!isset($_POST['LanguageSet'])){
		 Installation($DefaultLanguage);
	    } else {//The locale has been set, it's time to check the settings item.
		    $ErrMsg = '';
		    $InputError = 0;
		    $WarnMsg = '';
		    $InputWarn = 0;
		    //set the default time zone
		    if(!empty($_POST['DefaultTimeZone'])){
			    	date_default_timezone_set($_POST['DefaultTimeZone']);

		    }
		    //Check if the browser has been set properly
		    if(!isset($_SESSION['CookieAllowed']) or !($_SESSION['CookieAllowed'] == 1)){
			    $InputError = 1;
			    $ErrMsg .= '<p>' . _('Please set Cookies allowed in your web brower, otherwise webERP cannot run properly') . '</p>';

		    }
		    //Check the situation of php safe mode
		    if(!empty($_POST['SafeModeWarning'])){
			    if(!ContainsIllegalCharacters($_POST['SafeModeWarning'])){
				    $InputWarn = 1;
				    $WarnMsg .= '<p>' . _($_POST['SafeModeWarning']) . '</p>';
			    }else{//Something must be wrong since this messages have been defined.
				    echo _('Illegal characters or data has been identified, please see your admistrator for help');
				    exit;

			    }
		    }
		    //check the php version
		    if(empty($_POST['PHPVersion'])){
				$InputError = 1;
				$ErrMsg .= '<p>' . _('Although webERP should work with PHP version 5.1 onwards, a PHP version greater than 5.2 is strongly recommended') . '</p>';
		    }
		    //check the directory access authority of rootpath and companies
		    if(empty($_POST['ConfigFile'])){
			    $InputError = 1;
			    //get the directory where weberp live
			    $WebERPHome = dirname(dirname(__FILE__));
			    $ErrMsg .= '<p>' . _('The directory').' '.$WebERPHome.' '._('must be writable by web server') . '</p>';
		    }
		    if(empty($_POST['CompaniesCreate'])){
			    $InputError = 1;
			    $WebERPHome = dirname(dirname(__FILE__));
			    $ErrMsg .= '<p>' . _('The directory').' '.$WebERPHome.'/companies/'.' '.('must be writable by web server') . '</p>';
		    }
		    //check the necessary php extensions
		    if(empty($_POST['MbstringExt']) or $_POST['MbstringExt'] != 1){
			    $InputError = 1;
			    $ErrMsg .= '<p>' . _('The mbstring extension is not availble in your PHP') . '</p>';
		    }
		    //check if the libxml is exist
		    if(empty($_POST['LibxmlExt']) or $_POST['LibxmlExt'] != 1){
			    $InputError = 1;
			    $ErrMsg .='<p>' . _('The libxml extension is not available in your PHP') . '</p>';

		    }
		    //check if the mysqli or mysql is exist
		    if(!empty($_POST['NosqlExt']) and $_POST['NosqlExt'] == 1){
			    $InputError = 1;
			    $ErrMsg .= '<p>' . _('There is no MySQL or MySQL extension available') . '</p>';
		    }
		    if(!empty($_POST['MysqlExt']) and $_POST['MysqlExt'] == 1 and empty($_POST['PHP55'])){

			    $InputWarn = 1;
			    $MysqlExt = 1;
			    $WarnMsg .= _('The PHP MySQLI extension is recommend as MySQL extension has been deprecated since PHP 5.5') . '<br/>';

		    }elseif(!empty($_POST['MysqlExt']) and $_POST['MysqlExt'] ==1 and !empty($_POST['PHP55'])){
			    $InputError = 1;
			    $ErrMsg .='<p>' . _('The MySQL extension has been deprecated since 5.5. You should install the MySQLI extension or downgrade you PHP version to  one prior to 5.5') . '</p>';
		    }
		    //Check if the GD extension is available
		    if(empty($_POST['GdExt']) or $_POST['GdExt'] != 1){
			    $InputWarn = 1;
			    $WarnMsg .='<p>' .  _('The GD extension should be installed in your PHP configuration') . '</p>';

		    }

		    if($InputError != 0){
			    echo $ErrMsg;
			    Recheck();
			    exit;
		    }
		    if($InputWarn != 0){

			    echo $WarnMsg;
			    Recheck();
		    }
		    //If all of them are OK, then users can input the data of database etc
		    //Show the database
		    if(!empty($MysqlExt)){
			    DbConfig($Language,$MysqlExt);
		    }else{
			    DbConfig($Language);
		    }


	    }

	?>


<?php
//This function used to display the first screen for users to select they preferred langauage
//And at the mean time to check if the php configuration has meet requirements.
function Installation($DefaultLanguage)
{
    //Check if the cookie is allowed

    $_SESSION['CookieAllowed'] = 1;

    //Check if it's in safe model, safe mode has been deprecated at 5.3.0 and removed at 5.4
    //Please refer to here for more details http://hk2.php.net/manual/en/features.safe-mode.php
    if(ini_get('safe_mode')){
        $SafeModeWarning = 'You php is running in safe mode, it will leads to the execution time within 30 seconds, sometime in windows system, this will lead to installation cannot be completed in time, You would better to turn this function off';
    }

    //It's time to check the php version. The version should be run greater than 5.1
    if(version_compare(PHP_VERSION,'5.1.0')>=0){
        $PHPVersion = 1;
    }
    if(version_compare(PHP_VERSION,'5.5.0')>=0){
        $PHP55 = 1;
    }
    //Check the writability of the root path and companies path
    $RootPath = '..';
    $Companies = $RootPath.'/companies';
    if(is_writable($RootPath)){
        $ConfigFile = 1;
    }else{
        clearstatcache();
    }
    if(is_writable($Companies)){
        $CompaniesCreate = 1;
    }else{
        clearstatcache();
    }
    //check the necessary extensions
    $Extensions = get_loaded_extensions();

    //First check the gd module
    if(in_array('gd',$Extensions)){
        $GDExt = 1;
    }
    //Check the gettext module, it's a selectable
    if(in_array('gettext',$Extensions)){
        $GettextExt = 1;
    }
    //Check the mbstring module, it must be exist
    if(in_array('mbstring',$Extensions)){
        $MbstringExt = 1;
    }
    //Check the libxml module
    if(in_array('libxml',$Extensions)){
        $LibxmlExt = 1;
    }
    //Check if mysqli is exist
    //usually when it's not exist, there is some warning and cannot contiue in before version
    //We should adjust show a warning to the users if the users still use the mysql, then we should modify the config.php
    //to make use can still continue the installation. It's just performance lost
    if(in_array('mysqli',$Extensions)){
        $MysqliExt = '1';
    }elseif(in_array('mysql',$Extensions)){//if only mysql has been installed
        $MysqlExt = '1';
    }else{
        $NosqlExt = '1';//There is no sql available
    }

    ?>

    <form id="installation" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'); ?>" method="post">
    <fieldset>
        <legend><?php echo _('Welcome to the webERP Installation Wizard'); ?></legend>
        <div class="page_help_text">
            <?php echo '
            <ul>
                 <li>' . _('During installation you may see different status messages.') . '</li>
                <li>' . _('When there is an error message you must correct the error to continue.') . '</li>
                <li>' . _('If you see a warning message you should take notice before you proceed.') . '</li>
                <li>' . _('If you are unsure of an option value, you may keep the default setting.') . '</li>
            </ul>';
            ?>

        </div>
    </fieldset>
    <fieldset>
            <legend><?php echo _('Select your language'); ?></legend>

            <div class="page_help_text" >
                <p><?php echo _('The installer will try and guess your language from your browser, but may get it wrong. Please select you preferred language below.'); ?></p>
            </div>
            <ul>
            <?php include('../includes/LanguagesArray.php'); ?>
                <li><label for="Language"><?php echo _('Language:'); ?>&#160;</label>
                <select id="Language" name="Language">
            <?php
                if(substr($DefaultLanguage,0,2) !='en'){//ensure that the bilingual only display when the language is not english
                    foreach($LanguagesArray as $Key => $Language1){//since we only use the first 2 characters to separate the language, there are some
                                            //chance that different locale but use same first 2 letters.
                        if(!isset($SelectedKey) and substr($DefaultLanguage,0,2) == substr($Key,0,2)){
                            $SelectedKey = $Key;
                            echo '<option value="'.$Key.'" selected="selected">' . $Language1['LanguageName'].' - '.$Language1['WindowsLocale'] . '</option>';
                        }
                        if(!isset($SelectedKey) or (isset($SelectedKey) and $Key != $SelectedKey)){
                            echo '<option value="'.$Key.'" >' . $Language1['LanguageName'].' - '.$Language1['WindowsLocale'] . '</option>';
                        }
                    }
                }else{
                    foreach($LanguagesArray as $Key => $Language1){
                        if(!isset($SelectedKey) and substr($Key,0,2) == 'en'){
                            $SelectedKey = $Key;
                            echo '<option value="'.$Key.'" selected="selected">' . $Language1['LanguageName'] . '</option>';
                        }
                        if(!isset($SelectedKey) or (isset($SelectedKey) and $SelectedKey != $Key)){

                            echo '<option value="'.$Key.'" >' . $Language1['LanguageName'] . '</option>';
                        }
                    }
                }

                ?>
                    </select>
                </li>
            </ul>
            <script>
                function tz(){
                document.getElementById('DefaultTimeZone').value = jstz.determine().name();
                }
            </script>
                <input type="hidden" name="DefaultTimeZone" id="DefaultTimeZone" />
        <?php
        if(!empty($SafeModeWarning)){
        ?>
        <input type="hidden" name="SafeModeWarning" value="<?php echo $SafeModeWarning; ?>" />
        <?php
        }
        if(!empty($PHPVersion)){//
        ?>
        <input type="hidden" name="PHPVersion" value="1" />
        <?php
        }
        if(!empty($ConfigFile)){
        ?>
        <input type="hidden" name="ConfigFile" value="1" />
        <?php
        }
        if(!empty($CompaniesCreate)){
        ?>
        <input type="hidden" name="CompaniesCreate" value="1" />
        <?php
        }
        if(!empty($GDExt)){
        ?>
        <input type="hidden" name="GdExt" value="1" />
        <?php
        }
        if(!empty($GettextExt)){
        ?>
        <input type="hidden" name="GettextExt" value="1" />

        <?php
        }
        if(!empty($MbstringExt)){
        ?>
        <input type="hidden" name="MbstringExt" value="1" />
        <?php
        }
        if(!empty($LibxmlExt)){
        ?>
        <input type="hidden" name="LibxmlExt" value="1" />
        <?php
        }
        if(!empty($MysqliExt)){
        ?>
        <input type="hidden" name="MysqliExt" value="1" />
        <?php
        }
        if(!empty($MysqlExt)){
        ?>
        <input type="hidden" name="MysqlExt" value="1" />
        <?php
        }

        if(!empty($NosqlExt)){
        ?>
        <input type="hidden" name="NosqlExt" value="1" />
        <?php
        }
        if(!empty($PHP55)){
        ?>
        <input type="hidden" name="PHP55" value="1" />
        <?php
        }
        ?>

        </fieldset>
        <fieldset>
            <input type="hidden" name="LanguageSet" value="1" />
            <button type="submit" ><?php echo _('Next Step'); ?></button>
        </fieldset>


        <?php echo '
        <div class="page_help_text">
            <p>' .  _('webERP is an open source application licenced under GPL V2 and absolutely free to download.<br /> By installing webERP you acknowledge you have read <a href="http://www.gnu.org/licenses/gpl-2.0.html#SEC1" target="_blank">the licence</a>. <br />Please visit the official webERP website for more information.').'
            </p>
            <p><img src="../css/webERPsm.gif" title="webERP" alt="webERP" />&#160; <a href="http://www.weberp.org">http://www.weberp.org</a></p>
        </div>';
        ?>

    </form>
</div>

<?php
}

//@para Language used to determine user's preferred language
//@para MysqlExt use to mark if mysql extension has been used by users
//The function used to provide a screen for users to input mysql server parameters data
function DbConfig($Language,$MysqlExt = FALSE){//The screen for users to input mysql database information
	?>
	<form id="DatabaseConfig" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'); ?>" method="post">
        <fieldset>
            <legend><?php echo _('Database settings'); ?></legend>
            <div class="page_help_text">
                <p>
                    <?php echo _('Please enter your MySQL Database information below.'); ?><br />
                    <span><?php echo _('* Denotes required field'); ?></span>
                </p>
            </div>
            <ul>
                <li>
                    <label for="HostName"><?php echo _('Host Name'); ?>: </label>
                    <input type="text" name="HostName" id="HostName" required="true" value="localhost" placeholder="<?php echo _('Enter database host name'); ?>" />
                    <span><?php echo _('Commonly: localhost or 127.0.0.1'); ?></span>
                </li>
                <li>
                    <label for="Database"><?php echo _('Database Name'); ?>: </label>
                    <input type="text" name="Database" id="Database" required="true" pattern="^[a-zA-Z0-9_\&\-\ ]+$" value="weberp" maxlength="16" placeholder="<?php echo _('The database name'); ?>" />
                    <span><?php echo _('The database must have a valid name'); ?></span>
                </li>
                <li>
                    <label for="Prefix"><?php echo _('Database Prefix'); ?>: </label>
                    <input type="text" name="Prefix" size="25" placeholder="<?php echo _('Useful with shared hosting'); ?>" pattern="^[A-Za-z0-9$]+_$" />&#160;
                    <span><?php echo _('Optional: in the form of prefix_'); ?></span>
                </li>
                <li>
                    <label for="UserName"><?php echo _('Database User Name'); ?>: </label>
                    <input type="text" name="UserName" id="UserName" value="root" placeholder="<?php echo _('A valid database user name'); ?>" maxlength="16" required="true" />&#160;
                    <span><?php echo _('Must be a user that has permission to create a database'); ?></span>
                </li>
                <li>
                    <label for="Password"><?php echo _('Password'); ?>: </label>
                    <input type="password" name="Password" placeholder="<?php echo _('mySQL user password'); ?>"  />
                    <span><?php echo _('Enter the user password if one exists'); ?></span>
                </li>
            </ul>
        </fieldset>
        <input type="hidden" name="UserLanguage" value="<?php echo $Language; ?>" />
        <input type="hidden" name="Language" value="<?php echo $Language; ?>" />
        <?php
        if($MysqlExt){
        ?>
            <input type="hidden" name="MysqlExt" value="1" />
        <?php
        }else{
        ?>
            <input type="hidden" name="MysqliExt" value="0" />
        <?php
        }
        ?>

        <fieldset>
            <button type="submit" name="DbConfig"><?php echo _('Next Step'); ?></button>
        </fieldset>
    </form>
</div>

	<?php
}

//The function is used by users to return to start page
function Recheck(){
	?>
	<form id="refresh" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8');?>" method="post">
	    <fieldset>
    		<button type="submit"><?php echo _('Check Again'); ?></button>
	    </fieldset>
	<?php
}

//@para $UserLanguage is the language select by users and will be used as a default language
//@para $HostName is the Host of mysql server
//@para $UserName is the name of the mysql user
//@para $Password is the user's password which is stored in plain text in config.php
//@DatabaseName is the database used by weberp
//@$MysqlExt to check if it's use mysql extension in php instead of mysqli
//The function used to check if mysql parameters have been set correctly and can connect correctly

function DbCheck($UserLanguage,$HostName,$UserName,$Password,$DatabaseName,$MysqlExt = FALSE){//Check if the users have input the correct password
		if($MysqlExt){//use the mysqli
			$Con = mysql_connect($HostName,$UserName,$Password);

		}else{
			$Con = mysqli_connect($HostName,$UserName,$Password);
		}
		if(!$Con){
			echo '<h1>' . _('webERP Installation Wizard') . '</h1>';
			echo _('Failed to connect to the database. Please correct the following error:') . '<br/>' . mysqli_connect_error() . '<br/> '.('This error is usually caused by entry of an incorrect database password or user name.');
			if($MysqlExt){
				DbConfig($UserLanguage,$MysqlExt);
			}else{
				DbConfig($UserLanguage);
				}

		}else{
			if($MysqlExt === FALSE){
				CompanySetup($UserLanguage,$HostName,$UserName,$Password,$DatabaseName);
			}else{
				CompanySetup($UserLanguage,$HostName,$UserName,$Password,$DatabaseName,$MysqlExt);
			}
		}

}
//@para $UsersLanguage the language select by the user it will be used as the default langauge in config.php
//@para $HostName is the host for mysql server
//@para $UserName is the name of mysql user
//@para $Password is the password for mysql server
//@para $DatabaseName is the name of the database of webERP and also the same name of company
//@para $MysqlEx is refer to the php mysql extention if it's false, it means the php configuration only support mysql instead of mysqli
//The purpose of this function is to display the final screen for users to input company, admin user accounts etc informatioin
function CompanySetup($UserLanguage,$HostName,$UserName,$Password,$DatabaseName,$MysqlExt = FALSE){//display the company setup for users
	$CompanyName = $DatabaseName;
?>
    <h1><?php echo _('webERP Installation Wizard'); ?></h1>
    <!--<p style="text-align:center;"><?php echo _("Please enter the company name and please pay attention the company will be as same as the database name"); ?></p>-->
    <form id="companyset" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend><?php echo _('Company Settings'); ?></legend>
             <div class="page_help_text">
                <p><span><?php echo _('* Denotes required field'); ?></span></p>
            </div>
            <ul>
                <li>
                    <label for="CompanyName"><?php echo _("Company Name"); ?>: </label>
                    <input type="text" name="CompanyName" required="true" pattern='[^|/\\\?%:\<>"]+' value="<?php echo $CompanyName; ?>" maxlength="50" />
                    <span><?php echo _('The name of your company should not contain characters such as |\?%:<>"'); ?></span>
                </li>
                <li>
                    <label for="CountrySQL"><?php echo _("Chart of Accounts"); ?>: </label>
                    <select name="CountrySQL">
                    <?php
                        $CountrySQLs = scandir('../sql/mysql/country_sql');
                        $CountrySQLs = array_diff($CountrySQLs,array('.','..'));
                        if(!empty($CountrySQLs)){
                            foreach($CountrySQLs as $Value){
                                if($Value == 'default.sql'){
                                    echo '<option value="'.$Value.'" selected="true">' . $Value . '</option>';
                                }elseif($Value != 'demo.sql'){// the demo sql selection is not necessary so not allowed
                                    echo '<option value="'.$Value.'">' . $Value . '</option>';
                                }
                            }
                        }else{
                            echo '<option value="1">' . _('Default') . '</option>';
                        }
                    ?>
                    </select>
                    <span><?php echo _('A starter Chart of Accounts (use default.sql if having empty db problems)'); ?> </span>
                </li>
                <li>
                    <label for="TimeZone"><?php echo _("Time Zone"); ?>: </label>
                    <select name="TimeZone"><?php include('timezone.php'); ?></select>
                </li>
                <li>
                    <label for="Logo"><?php echo _('Company logo file'); ?>: </label>
                    <input type="file" accept="image/jpg" name="LogoFile" title="<?php echo _('A jpg file up to 10k, and not greater than 170px x 80px'); ?>" />
                    <span><?php echo _("jpg file to 10k, not greater than 170px x 80px"); ?></span>
                </li>
            </ul>
        </fieldset>
        <fieldset>
            <legend><?php echo _('Installation option'); ?></legend>
            <ul>
                <li>
                    <label for="InstallDemo"><?php echo _('Install the demo data?'); ?> </label><input type="checkbox" name="Demo" checked="checked"  />
                    <span><?php echo _("WebERPDemo site and data will be installed"); ?></span>
                </li>
            </ul>
        </fieldset>
        <fieldset>
            <legend><?php echo _('Administrator account settings'); ?></legend>
                <div class="page_help_text">
                    <ul>
                        <li>
                            <?php echo _('The default user name is \'admin\' and it cannot be changed.'); ?>
                        </li>
                        <li>
                            <?php echo _('The default password is \'weberp\' which you can change below.'); ?>
                        </li>
                    </ul>
                </div>
                <ul>
                    <li>
                        <label for="adminaccount"><?php echo _('webERP Admin Account'); ?>: </label>
                        <input type="text" name="adminaccount" value="admin" disabled="disabled" />
                    </li>
                    <li>
                        <label for="Email"><?php echo _('Email address'); ?>: </label>
                        <input type="text" name="Email" required="true" placeholder="admin@yoursite.com" value="admin@weberp.org" pattern="[a-z0-9!#$%&'*+/=?^_`{|}~.-]+@[a-z0-9-]+(\.[a-z0-9-]+)*" />
                        <span> <?php echo _('For example: admin@yourcompany.com'); ?></span>
                    </li>
                    <li>
                        <label for="webERPPassword"><?php echo _('webERP Password'); ?>: </label>
                        <input type="password" name="webERPPassword" value="weberp" required="true" />
                    </li>
                    <li>
                        <label for="PasswordConfirm"><?php echo _('Re-enter Password'); ?>: </label>
                        <input type="password" required="true" value="weberp" name="PasswordConfirm" />
                    </li>
                </ul>

            </fieldset>
            <input type="hidden" name="HostName" value="<?php echo $HostName; ?>" />
            <input type="hidden" name="UserName" value="<?php echo $UserName; ?>" />
            <input type="hidden" name="DatabaseName" value="<?php echo $DatabaseName; ?>" />
            <input type="hidden" name="Password" value="<?php echo $Password; ?>" />
            <input type="hidden" name="MysqlExt" value="<?php echo $MysqlExt; ?>" />
            <input type="hidden" name="UserLanguage" value="<?php echo $UserLanguage; ?>" />
            <input type="hidden" name="MAX_FILE_SIZE" value="10240" />

            <fieldset>
              <button type="submit" name="Install"><?php echo _('Install'); ?></button>
            </fieldset>
    </form>
</div>

<?php

}
//@para $NewSQL is the weberp new sql file which contains the CountrySQL file
//@para $Demo is the weberp demo sql file
//@para $db refer to the database connection reference
//@para $DBType refer to the database connection type mysqli or mysql
//@para $NewDB is the new database name
//The purpose of this function is populate database with data from the sql file by mysqli
function PopulateSQLData($NewSQL=false,$Demo=false,$db,$DBType,$NewDB = false){
	if($DBType == 'mysqli'){
		mysqli_select_db($db,$NewDB);
	}else{
		mysql_select_db($NewDB,$db);
	}

	if(file_exists($NewSQL)){
		PopulateSQLDataBySQL($NewSQL,$db,$DBType,$NewDB, false);
/*
				if($DBType == 'mysqli'){//if the mysql db type is mysqli
						mysqli_select_db($db,$NewDB);
						//currently there is no 'USE' statements in sql file, no bother to remove them
						$sql = 'SET names UTF8;';
						$sql .= file_get_contents($NewSQL);
						if(!$sql){
							die(_('Failed to open the new sql file'));
						}

						$result = mysqli_multi_query($db,$sql);
						if(!$result){
							echo 'Failed to populate the database'.' '.$NewDB.' and the error is'.' '.mysqli_error($db);
						}
						//now clear the result otherwise the next operation will failed with commands out of sync
						//Since the mysqli_multi_query() return boolean value, we must retrieve the query result set
						//via mysqli_store_result or mysqli_use_result
						//mysqli_store_result return an buffered object or false if failed or no such object such as result of INSERT
						//so if it's false no bother to free them
						do {
							if($result = mysqli_store_result($db)){
								mysqli_free_result($result);
							}
						} while (mysqli_more_results($db)?mysqli_next_result($db):false);
						//} while (mysqli_next_result($db));
				}else{
						PopulateSQLDataBySQL($NewSQL,$db,$DBType,$NewDB);
				}
*/

	}

	if($Demo){
		PopulateSQLDataBySQL($Demo,$db,$DBType,false,$NewDB);
/*
						//we can let users wait instead of changing the my.cnf file
						//It is a non affordable challenge for them since wamp set the max_allowed_packet 1M
						//and weberpdemo.sql is 1.4M so at least it cannot install in wamp
						//so we not use the multi query here


						$SQLFile = fopen($Demo);

						$sql = file_get_contents($Demo);
						if(!$sql){
							die(_('Failed to open the demo sql file'));
						}

						$result = mysqli_multi_query($db,$sql);

						if(!$result){
							echo _('Failed to populate the database'.' '.$NewDB.' and the error is').' '.mysqli_error($db);
						}
						//clear the bufferred result
						do {
							if($result = mysqli_store_result($db)){
								mysqli_free_result($result);
							}
						} while (mysqli_more_results($db)?mysqli_next_result($db):false);


				}else{
						mysqli_select_db($db,$NewDB);
						PopulateSQLDataBySQL($Demo,$db,$DBType,false,$NewDB);
			}
*/
	}
}

//@para $File is the sql file name
//@para $db is the DB connect reference
//@para $DBType refer to mysqli or mysql connection
//@para $NewDB is the new database name
//@para $DemoDB is the demo database name
//The purpose of this function is populate the database with mysql extention
function PopulateSQLDataBySQL($File,$db,$DBType,$NewDB=false,$DemoDB='weberpdemo'){
	$dbName = ($NewDB) ? $NewDB : $DemoDB;
	($DBType=='mysqli')?mysqli_select_db($db,$dbName):mysql_select_db($dbName,$db);
	$SQLScriptFile = file($File);
	$ScriptFileEntries = sizeof($SQLScriptFile);
	$SQL =' SET names UTF8;';
	$InAFunction = false;
	for ($i=0; $i<$ScriptFileEntries; $i++) {

		$SQLScriptFile[$i] = trim($SQLScriptFile[$i]);
		//ignore lines that start with -- or USE or /*
		if (mb_substr($SQLScriptFile[$i], 0, 2) != '--'
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
				// Database created above with correct name.
				if (strncasecmp($SQL, ' CREATE DATABASE ', 17)
					AND strncasecmp($SQL, ' USE ', 5)){
					$SQL = mb_substr($SQL,0,mb_strlen($SQL)-1);

					$result = ($DBType=='mysqli')?mysqli_query($db,$SQL):mysql_query($SQL,$db);
				}
				$SQL = '';
			}

		} //end if its a valid sql line not a comment
	} //end of for loop around the lines of the sql script
}

function CryptPass( $Password ) {
    if (PHP_VERSION_ID < 50500) {
        $salt = base64_encode(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
        $salt = str_replace('+', '.', $salt);
        $hash = crypt($Password, '$2y$10$'.$salt.'$');
    } else {
        $hash = password_hash($Password,PASSWORD_DEFAULT);
    }
    return $hash;
 }

//@para $db the database connection
//@para $DatabaseName the database to update
//@para $DBConnectType if it is mysql extention or not
//@para $AdminPasswd the weberp administrator's password
//@para $AdminEmail the weberp administrators' email
//@para $AdminLangauge the administrator's language for login
//@para $CompanyName the company
//The purpose of this function is to update the admin accounts and company name information

function DBUpdate($db,$DatabaseName,$DBConnectType,$AdminPasswd,$AdminEmail,$AdminLanguage,$CompanyName){
	$MysqlExt = ($DBConnectType == 'mysql')?true:false;
	//select the database to connect
	$Result = (!$MysqlExt) ? mysqli_select_db($db,$DatabaseName):mysql_select_db($DatabaseName,$db);

	$sql = "UPDATE www_users
				SET password = '".CryptPass($AdminPasswd)."',
					email = '".$AdminEmail."',
				        language = '".$AdminLanguage."'
				WHERE userid = 'admin'";
	$Result = (!$MysqlExt) ? mysqli_query($db,$sql):mysql_query($sql,$db);
	if(!$Result){

		echo _('Failed to update the email address and password of the administrator and the error is').((!$MysqlExt)?mysqli_error($db):mysql_error($db));
	}

	$sql = "UPDATE companies
			SET coyname = '". ((!$MysqlExt)?mysqli_real_escape_string($db, $CompanyName):mysql_real_escape_string($CompanyName,$db)) . "'
			WHERE coycode = 1";
	$Result = (!$MysqlExt)?mysqli_query($db,$sql):mysql_query($sql,$db);
	if(!$Result){
		echo _('Failed to update the company name and the erroris').((!$MysqlExt)?mysqli_error($db):mysql_error($db));
	}


}

	?>
<script>
/**
 * This script gives you the zone info key representing your device's time zone setting.
 *
 * @name jsTimezoneDetect
 * @version 1.0.5
 * @author Jon Nylander
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 *
 * For usage and examples, visit:
 * http://pellepim.bitbucket.org/jstz/
 *
 * Copyright (c) Jon Nylander
 */

/*jslint undef: true */
/*global console, exports*/

(function(root) {
  /**
   * Namespace to hold all the code for timezone detection.
   */
  var jstz = (function () {
      'use strict';
      var HEMISPHERE_SOUTH = 's',

          /**
           * Gets the offset in minutes from UTC for a certain date.
           * @param {Date} date
           * @returns {Number}
           */
          get_date_offset = function (date) {
              var offset = -date.getTimezoneOffset();
              return (offset !== null ? offset : 0);
          },

          get_date = function (year, month, date) {
              var d = new Date();
              if (year !== undefined) {
                d.setFullYear(year);
              }
              d.setMonth(month);
              d.setDate(date);
              return d;
          },

          get_january_offset = function (year) {
              return get_date_offset(get_date(year, 0 ,2));
          },

          get_june_offset = function (year) {
              return get_date_offset(get_date(year, 5, 2));
          },

          /**
           * Private method.
           * Checks whether a given date is in daylight saving time.
           * If the date supplied is after august, we assume that we're checking
           * for southern hemisphere DST.
           * @param {Date} date
           * @returns {Boolean}
           */
          date_is_dst = function (date) {
              var is_southern = date.getMonth() > 7,
                  base_offset = is_southern ? get_june_offset(date.getFullYear()) :
                                              get_january_offset(date.getFullYear()),
                  date_offset = get_date_offset(date),
                  is_west = base_offset < 0,
                  dst_offset = base_offset - date_offset;

              if (!is_west && !is_southern) {
                  return dst_offset < 0;
              }

              return dst_offset !== 0;
          },

          /**
           * This function does some basic calculations to create information about
           * the user's timezone. It uses REFERENCE_YEAR as a solid year for which
           * the script has been tested rather than depend on the year set by the
           * client device.
           *
           * Returns a key that can be used to do lookups in jstz.olson.timezones.
           * eg: "720,1,2".
           *
           * @returns {String}
           */

          lookup_key = function () {
              var january_offset = get_january_offset(),
                  june_offset = get_june_offset(),
                  diff = january_offset - june_offset;

              if (diff < 0) {
                  return january_offset + ",1";
              } else if (diff > 0) {
                  return june_offset + ",1," + HEMISPHERE_SOUTH;
              }

              return january_offset + ",0";
          },

          /**
           * Uses get_timezone_info() to formulate a key to use in the olson.timezones dictionary.
           *
           * Returns a primitive object on the format:
           * {'timezone': TimeZone, 'key' : 'the key used to find the TimeZone object'}
           *
           * @returns Object
           */
          determine = function () {
              var key = lookup_key();
              return new jstz.TimeZone(jstz.olson.timezones[key]);
          },

          /**
           * This object contains information on when daylight savings starts for
           * different timezones.
           *
           * The list is short for a reason. Often we do not have to be very specific
           * to single out the correct timezone. But when we do, this list comes in
           * handy.
           *
           * Each value is a date denoting when daylight savings starts for that timezone.
           */
          dst_start_for = function (tz_name) {

            var ru_pre_dst_change = new Date(2010, 6, 15, 1, 0, 0, 0), // In 2010 Russia had DST, this allows us to detect Russia :)
                dst_starts = {
                    'America/Denver': new Date(2011, 2, 13, 3, 0, 0, 0),
                    'America/Mazatlan': new Date(2011, 3, 3, 3, 0, 0, 0),
                    'America/Chicago': new Date(2011, 2, 13, 3, 0, 0, 0),
                    'America/Mexico_City': new Date(2011, 3, 3, 3, 0, 0, 0),
                    'America/Asuncion': new Date(2012, 9, 7, 3, 0, 0, 0),
                    'America/Santiago': new Date(2012, 9, 3, 3, 0, 0, 0),
                    'America/Campo_Grande': new Date(2012, 9, 21, 5, 0, 0, 0),
                    'America/Montevideo': new Date(2011, 9, 2, 3, 0, 0, 0),
                    'America/Sao_Paulo': new Date(2011, 9, 16, 5, 0, 0, 0),
                    'America/Los_Angeles': new Date(2011, 2, 13, 8, 0, 0, 0),
                    'America/Santa_Isabel': new Date(2011, 3, 5, 8, 0, 0, 0),
                    'America/Havana': new Date(2012, 2, 10, 2, 0, 0, 0),
                    'America/New_York': new Date(2012, 2, 10, 7, 0, 0, 0),
                    'Europe/Helsinki': new Date(2013, 2, 31, 5, 0, 0, 0),
                    'Pacific/Auckland': new Date(2011, 8, 26, 7, 0, 0, 0),
                    'America/Halifax': new Date(2011, 2, 13, 6, 0, 0, 0),
                    'America/Goose_Bay': new Date(2011, 2, 13, 2, 1, 0, 0),
                    'America/Miquelon': new Date(2011, 2, 13, 5, 0, 0, 0),
                    'America/Godthab': new Date(2011, 2, 27, 1, 0, 0, 0),
                    'Europe/Moscow': ru_pre_dst_change,
                    'Asia/Amman': new Date(2013, 2, 29, 1, 0, 0, 0),
                    'Asia/Beirut': new Date(2013, 2, 31, 2, 0, 0, 0),
                    'Asia/Damascus': new Date(2013, 3, 6, 2, 0, 0, 0),
                    'Asia/Jerusalem': new Date(2013, 2, 29, 5, 0, 0, 0),
                    'Asia/Yekaterinburg': ru_pre_dst_change,
                    'Asia/Omsk': ru_pre_dst_change,
                    'Asia/Krasnoyarsk': ru_pre_dst_change,
                    'Asia/Irkutsk': ru_pre_dst_change,
                    'Asia/Yakutsk': ru_pre_dst_change,
                    'Asia/Vladivostok': ru_pre_dst_change,
                    'Asia/Baku': new Date(2013, 2, 31, 4, 0, 0),
                    'Asia/Yerevan': new Date(2013, 2, 31, 3, 0, 0),
                    'Asia/Kamchatka': ru_pre_dst_change,
                    'Asia/Gaza': new Date(2010, 2, 27, 4, 0, 0),
                    'Africa/Cairo': new Date(2010, 4, 1, 3, 0, 0),
                    'Europe/Minsk': ru_pre_dst_change,
                    'Pacific/Apia': new Date(2010, 10, 1, 1, 0, 0, 0),
                    'Pacific/Fiji': new Date(2010, 11, 1, 0, 0, 0),
                    'Australia/Perth': new Date(2008, 10, 1, 1, 0, 0, 0)
                };

              return dst_starts[tz_name];
          };

      return {
          determine: determine,
          date_is_dst: date_is_dst,
          dst_start_for: dst_start_for
      };
  }());

  /**
   * Simple object to perform ambiguity check and to return name of time zone.
   */
  jstz.TimeZone = function (tz_name) {
      'use strict';
        /**
         * The keys in this object are timezones that we know may be ambiguous after
         * a preliminary scan through the olson_tz object.
         *
         * The array of timezones to compare must be in the order that daylight savings
         * starts for the regions.
         */
      var AMBIGUITIES = {
              'America/Denver':       ['America/Denver', 'America/Mazatlan'],
              'America/Chicago':      ['America/Chicago', 'America/Mexico_City'],
              'America/Santiago':     ['America/Santiago', 'America/Asuncion', 'America/Campo_Grande'],
              'America/Montevideo':   ['America/Montevideo', 'America/Sao_Paulo'],
              'Asia/Beirut':          ['Asia/Amman', 'Asia/Jerusalem', 'Asia/Beirut', 'Europe/Helsinki','Asia/Damascus'],
              'Pacific/Auckland':     ['Pacific/Auckland', 'Pacific/Fiji'],
              'America/Los_Angeles':  ['America/Los_Angeles', 'America/Santa_Isabel'],
              'America/New_York':     ['America/Havana', 'America/New_York'],
              'America/Halifax':      ['America/Goose_Bay', 'America/Halifax'],
              'America/Godthab':      ['America/Miquelon', 'America/Godthab'],
              'Asia/Dubai':           ['Europe/Moscow'],
              'Asia/Dhaka':           ['Asia/Yekaterinburg'],
              'Asia/Jakarta':         ['Asia/Omsk'],
              'Asia/Shanghai':        ['Asia/Krasnoyarsk', 'Australia/Perth'],
              'Asia/Tokyo':           ['Asia/Irkutsk'],
              'Australia/Brisbane':   ['Asia/Yakutsk'],
              'Pacific/Noumea':       ['Asia/Vladivostok'],
              'Pacific/Tarawa':       ['Asia/Kamchatka', 'Pacific/Fiji'],
              'Pacific/Tongatapu':    ['Pacific/Apia'],
              'Asia/Baghdad':         ['Europe/Minsk'],
              'Asia/Baku':            ['Asia/Yerevan','Asia/Baku'],
              'Africa/Johannesburg':  ['Asia/Gaza', 'Africa/Cairo']
          },

          timezone_name = tz_name,

          /**
           * Checks if a timezone has possible ambiguities. I.e timezones that are similar.
           *
           * For example, if the preliminary scan determines that we're in America/Denver.
           * We double check here that we're really there and not in America/Mazatlan.
           *
           * This is done by checking known dates for when daylight savings start for different
           * timezones during 2010 and 2011.
           */
          ambiguity_check = function () {
              var ambiguity_list = AMBIGUITIES[timezone_name],
                  length = ambiguity_list.length,
                  i = 0,
                  tz = ambiguity_list[0];

              for (; i < length; i += 1) {
                  tz = ambiguity_list[i];

                  if (jstz.date_is_dst(jstz.dst_start_for(tz))) {
                      timezone_name = tz;
                      return;
                  }
              }
          },

          /**
           * Checks if it is possible that the timezone is ambiguous.
           */
          is_ambiguous = function () {
              return typeof (AMBIGUITIES[timezone_name]) !== 'undefined';
          };

      if (is_ambiguous()) {
          ambiguity_check();
      }

      return {
          name: function () {
              return timezone_name;
          }
      };
  };

  jstz.olson = {};

  /*
   * The keys in this dictionary are comma separated as such:
   *
   * First the offset compared to UTC time in minutes.
   *
   * Then a flag which is 0 if the timezone does not take daylight savings into account and 1 if it
   * does.
   *
   * Thirdly an optional 's' signifies that the timezone is in the southern hemisphere,
   * only interesting for timezones with DST.
   *
   * The mapped arrays is used for constructing the jstz.TimeZone object from within
   * jstz.determine_timezone();
   */
  jstz.olson.timezones = {
      '-720,0'   : 'Pacific/Majuro',
      '-660,0'   : 'Pacific/Pago_Pago',
      '-600,1'   : 'America/Adak',
      '-600,0'   : 'Pacific/Honolulu',
      '-570,0'   : 'Pacific/Marquesas',
      '-540,0'   : 'Pacific/Gambier',
      '-540,1'   : 'America/Anchorage',
      '-480,1'   : 'America/Los_Angeles',
      '-480,0'   : 'Pacific/Pitcairn',
      '-420,0'   : 'America/Phoenix',
      '-420,1'   : 'America/Denver',
      '-360,0'   : 'America/Guatemala',
      '-360,1'   : 'America/Chicago',
      '-360,1,s' : 'Pacific/Easter',
      '-300,0'   : 'America/Bogota',
      '-300,1'   : 'America/New_York',
      '-270,0'   : 'America/Caracas',
      '-240,1'   : 'America/Halifax',
      '-240,0'   : 'America/Santo_Domingo',
      '-240,1,s' : 'America/Santiago',
      '-210,1'   : 'America/St_Johns',
      '-180,1'   : 'America/Godthab',
      '-180,0'   : 'America/Argentina/Buenos_Aires',
      '-180,1,s' : 'America/Montevideo',
      '-120,0'   : 'America/Noronha',
      '-120,1'   : 'America/Noronha',
      '-60,1'    : 'Atlantic/Azores',
      '-60,0'    : 'Atlantic/Cape_Verde',
      '0,0'      : 'UTC',
      '0,1'      : 'Europe/London',
      '60,1'     : 'Europe/Berlin',
      '60,0'     : 'Africa/Lagos',
      '60,1,s'   : 'Africa/Windhoek',
      '120,1'    : 'Asia/Beirut',
      '120,0'    : 'Africa/Johannesburg',
      '180,0'    : 'Asia/Baghdad',
      '180,1'    : 'Europe/Moscow',
      '210,1'    : 'Asia/Tehran',
      '240,0'    : 'Asia/Dubai',
      '240,1'    : 'Asia/Baku',
      '270,0'    : 'Asia/Kabul',
      '300,1'    : 'Asia/Yekaterinburg',
      '300,0'    : 'Asia/Karachi',
      '330,0'    : 'Asia/Kolkata',
      '345,0'    : 'Asia/Kathmandu',
      '360,0'    : 'Asia/Dhaka',
      '360,1'    : 'Asia/Omsk',
      '390,0'    : 'Asia/Rangoon',
      '420,1'    : 'Asia/Krasnoyarsk',
      '420,0'    : 'Asia/Jakarta',
      '480,0'    : 'Asia/Shanghai',
      '480,1'    : 'Asia/Irkutsk',
      '525,0'    : 'Australia/Eucla',
      '525,1,s'  : 'Australia/Eucla',
      '540,1'    : 'Asia/Yakutsk',
      '540,0'    : 'Asia/Tokyo',
      '570,0'    : 'Australia/Darwin',
      '570,1,s'  : 'Australia/Adelaide',
      '600,0'    : 'Australia/Brisbane',
      '600,1'    : 'Asia/Vladivostok',
      '600,1,s'  : 'Australia/Sydney',
      '630,1,s'  : 'Australia/Lord_Howe',
      '660,1'    : 'Asia/Kamchatka',
      '660,0'    : 'Pacific/Noumea',
      '690,0'    : 'Pacific/Norfolk',
      '720,1,s'  : 'Pacific/Auckland',
      '720,0'    : 'Pacific/Tarawa',
      '765,1,s'  : 'Pacific/Chatham',
      '780,0'    : 'Pacific/Tongatapu',
      '780,1,s'  : 'Pacific/Apia',
      '840,0'    : 'Pacific/Kiritimati'
  };

  if (typeof exports !== 'undefined') {
    exports.jstz = jstz;
  } else {
    root.jstz = jstz;
  }
})(this);
if(typeof tz !== 'undefined'){
	window.onload=tz;
}
</script>

</body>
</html>

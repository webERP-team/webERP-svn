<?php

$PageSecurity = 15; //hard coded in case database is old and PageSecurity stuff cannot be retrieved

include('includes/session.php');
$Title = _('Upgrade webERP Database');
include('includes/header.php');

if (!isset($_POST['DoUpgrade'])){

	echo '<br /><form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (!isset($_SESSION['VersionNumber'])){
		prnMsg(_('The webERP code is version')  . ' ' . $Version . ' ' . _('and the database version is not actually recorded at this version'),'info');
		echo '<table class="selection">
			<tr>
				<td>' . _('Select the version you are upgrading from:') . '</td>
				<td><select name="OldVersion" >
					<option selected="selected" value="Manual">' . _('Apply database changes manually') . '</option>
					<option value="3.00">' . _('Version 3.00') . '</option>
					<option value="3.01">' . _('Version 3.01') . '</option>
					<option value="3.02">' . _('Version 3.02') . '</option>
					<option value="3.03">' . _('Version 3.03') . '</option>
					<option value="3.04">' . _('Version 3.04') . '</option>
					<option value="3.05">' . _('Version 3.05') . '</option>
					<option value="3.06">' . _('Version 3.06') . '</option>
					<option value="3.07">' . _('Version 3.07') . '</option>
					<option value="3.08">' . _('Version 3.08') . '</option>
					<option value="3.09">' . _('Version 3.09') . '</option>
					<option value="3.10">' . _('Version 3.10') . '</option>
					<option value="3.11.x">' . _('Version 3.11 or 4.01 - 4.02') . '</option>
				</select></td>
			</tr>
		</table>';
		prnMsg(_('This script will perform any modifications to the database required to allow the additional functionality in later scripts.') . '<br /><a target="_blank" href="' . $RootPath . '/BackupDatabase.php">' ._('Click to do a database backup now before proceeding!') . '</a>','info');
		echo '<div class="centre">
					<input type="submit" name="DoUpgrade" value="' . _('Perform Database Upgrade') . '" />
				</div>';
	} else {
		if ($_SESSION['VersionNumber']=='4.00RC1'){
			$_SESSION['VersionNumber']='3.12';
		}
		if (strcmp($Version,$_SESSION['VersionNumber'])==0){
			prnMsg(_('The database is up to date, there are no upgrades to perform'),'info');
		} else {
			prnMsg(_('This script will perform any modifications to the database required to allow the additional functionality in later scripts.') . '<br />' . _('The webERP code is version')  . ' ' . $Version . ' ' . _('and the database version is') . ' ' . $_SESSION['VersionNumber'] . '<br /><a target="_blank" href="' . $RootPath . '/BackupDatabase.php">' ._('Click to do a database backup now before proceeding!') . '</a>','info');

			echo '<input type="hidden" name="OldVersion" value="' . $_SESSION['VersionNumber'] . '" />';
			echo '<div class="centre">
					<input type="submit" name="DoUpgrade" value="' . _('Perform Database Upgrade') . '" />
				</div>';
		}
	}

	echo '</div>
          </form>';
}

if (isset($_POST['DoUpgrade'])){

	if ($DBType=='mysql' OR $DBType =='mysqli'){

		$SQLScripts = array();

		if ($_POST['OldVersion']=='Manual') {
			prnMsg(_('No datbase updates have been done as you selected to apply these manually - upgrade SQL scripts are under sql/mysql/ directory in the distribution'),'info');
		} else { //we are into automatically applying database upgrades

			prnMsg(_('If there are any failures then please check with your system administrator. Please read all notes carefully to ensure they are expected'),'info');
			switch ($_POST['OldVersion']) {
				//since there are no "break" statements subsequent upgrade scripts will be added to the array
				case '3.00':
					$SQLScripts[] = './sql/mysql/upgrade3.00-3.01.sql';
				case '3.01':
					$SQLScripts[] = './sql/mysql/upgrade3.01-3.02.sql';
				case '3.02':
					$SQLScripts[] = './sql/mysql/upgrade3.02-3.03.sql';
				case '3.03':
					$SQLScripts[] = './sql/mysql/upgrade3.03-3.04.sql';
				case '3.04':
					$SQLScripts[] = './sql/mysql/upgrade3.04-3.05.sql';
				case '3.05':
					$SQLScripts[] = './sql/mysql/upgrade3.05-3.06.sql';
				case '3.06':
					$SQLScripts[] = './sql/mysql/upgrade3.06-3.07.sql';
				case '3.07':
					$SQLScripts[] = './sql/mysql/upgrade3.07-3.08.sql';
				case '3.08':
				case '3.09':
					$SQLScripts[] = './sql/mysql/upgrade3.09-3.10.sql';
				case '3.10':
					$SQLScripts[] = './sql/mysql/upgrade3.10-3.11.sql';
				case '3.11.x':
				case '3.11.1':
				case '3.11.2':
				case '3.11.3':
				case '3.12.32':
				case '4.0RC1':
				case '4.01':
				case '4.02':
				case '4.03RC1':
				case '4.03RC2':
				case '4.03':
				case '4.03.2':
				case '4.03.3':
				case '4.03.5':
				case '4.03.6':
				case '4.03.7':
					$SQLScripts[] = './sql/mysql/upgrade3.11.1-4.00.sql';
				case '4.03.8':
					$SQLScripts[] = './sql/mysql/upgrade4.03-4.04.sql';
				case '4.04':
					$SQLScripts[] = './sql/mysql/upgrade4.04-4.04.1.sql';
				case '4.04.1':
				case '4.04.2':
				case '4.04.3':
					$SQLScripts[] = './sql/mysql/upgrade4.04.1-4.04.4.sql';
				case '4.04.4':
					$SQLScripts[] = './sql/mysql/upgrade4.04.4-4.04.5.sql';
				case '4.04.5':
					$SQLScripts[] = './sql/mysql/upgrade4.04.5-4.05.sql';
				case '4.05':
				case '4.05.1':
				case '4.05.2':
				case '4.05.3':
				case '4.06':
				case '4.06.1':
				case '4.06.2':
				case '4.06.3':
				case '4.06.4':
				case '4.06.5':
					$SQLScripts[] = './sql/mysql/upgrade4.05-4.06.sql';
				case '4.06.6':
					$SQLScripts[] = './sql/mysql/upgrade4.06-4.07.sql';
				case '4.07':
				case '4.07.0':
				case '4.07.1':
				case '4.07.2':
				case '4.07.3':
				case '4.07.4':
				case '4.07.5':
				case '4.07.6':
				case '4.07.7':
				case '4.07.8':
				case '4.07.9':
				case '4.07.10':
					$SQLScripts[] = './sql/mysql/upgrade4.07-4.08.sql';
				case '4.08':
				case '4.08.1':
				case '4.08.2':
				case '4.08.3':
				case '4.08.4':
				case '4.08.5':
				case '4.08.6':
					$SQLScripts[] = './sql/mysql/upgrade4.08-4.09.sql';
				case '4.09':
				case '4.09.1':
				case '4.10.0':
					if (!is_writable('config.php')) {
						prnMsg( _('To perform this upgrade the web server must have write access to the config.php file. Currently the web-server is reporting that it does not have appropriate permission. Please ensure config.php is writable and run the upgrade again'), 'warning');
						include('includes/footer.php');
						exit;
					} else {
						$ConfigFileContents = file_get_contents('config.php');
						$ConfigFileContents = str_replace('dbuser','DBUser', $ConfigFileContents);
						$ConfigFileContents = str_replace('dbpassword','DBPassword', $ConfigFileContents);
						$ConfigFileContents = str_replace('dbType','DBType', $ConfigFileContents);
						$ConfigFileContents = str_replace('allow_demo_mode','AllowDemoMode',$ConfigFileContents);
						$ConfigFileContents = str_replace('rootpath','RootPath',$ConfigFileContents);
						file_put_contents('config.php',$ConfigFileContents);
						prnMsg( _('You should now make the config.php read only for the web server.'), 'warning');
					}
					$SQLScripts[] = './sql/mysql/upgrade4.09-4.10.sql';
				case '4.10.1':
					$SQLScripts[] = './sql/mysql/upgrade4.10-4.11.sql';
				case '4.11.0':
				case '4.11.1':
					$SQLScripts[] = './sql/mysql/upgrade4.11.1-4.11.2.sql';
				case '4.11.2':
					$SQLScripts[] = './sql/mysql/upgrade4.11.2-4.11.3.sql';
				case '4.11.3':
				case '4.11.4':
					$SQLScripts[] = './sql/mysql/upgrade4.11.3-4.11.4.sql';
				case '4.11.5':
					$SQLScripts[] = './sql/mysql/upgrade4.11.4-4.12.sql';
				case '4.12':
					$SQLScripts[] = './sql/mysql/upgrade4.12-4.12.1.sql';
				case '4.12.1':
					$SQLScripts[] = './sql/mysql/upgrade4.12.1-4.12.2.sql';
				case '4.12.2':
					$SQLScripts[] = './sql/mysql/upgrade4.12.2-4.12.3.sql';
				case '4.12.3':
					$SQLScripts[] = './sql/mysql/upgrade4.12.3-4.13.sql';
				case '4.13':
					$SQLScripts[] = './sql/mysql/upgrade4.13-4.13.1.sql';
				case '4.13.1':
					$SQLScripts[] = './sql/mysql/upgrade4.13.1-4.14.sql';
				case '4.14':
					$SQLScripts[] = './sql/mysql/upgrade4.14-4.14.1.sql';
				case '4.14.1':
					$SQLScripts[] = './sql/mysql/upgrade4.14.1-4.15.sql';
				case '4.15':
					$SQLScripts[] = './sql/mysql/upgrade4.15-4.15.1.sql';
					break;
			} //end switch
		}
	} else { //$DBType is not mysql or mysqli
		prnMsg(_('Only mysql upgrades are performed seamlessly at this time. Your database will need to be manually updated'),'info');
	}

	$result = DB_IgnoreForeignKeys();

	foreach ($SQLScripts AS $SQLScriptFile) {

		$SQLEntries = file($SQLScriptFile);
		$ScriptFileEntries = sizeof($SQLEntries);
		$sql ='';
		$InAFunction = false;
		echo '<br />
			<table>
			<tr>
				<th colspan="2">' . _('Applying') . ' ' . $SQLScriptFile . '</th>
			</tr>';

		for ($i=0; $i<=$ScriptFileEntries; $i++) {

			$SQLEntries[$i] = trim($SQLEntries[$i]);

			if (mb_substr($SQLEntries[$i], 0, 2) != '--'
				AND mb_substr($SQLEntries[$i], 0, 3) != 'USE'
				AND mb_strstr($SQLEntries[$i],'/*')==FALSE
				AND mb_strlen($SQLEntries[$i])>1){

				$sql .= ' ' . $SQLEntries[$i];

				//check if this line kicks off a function definition - pg chokes otherwise
				if (mb_substr($SQLEntries[$i],0,15) == 'CREATE FUNCTION'){
					$InAFunction = true;
				}
				//check if this line completes a function definition - pg chokes otherwise
				if (mb_substr($SQLEntries[$i],0,8) == 'LANGUAGE'){
					$InAFunction = false;
				}
				if (mb_strpos($SQLEntries[$i],';')>0 AND ! $InAFunction){
					$sql = mb_substr($sql,0,mb_strlen($sql)-1);
					$result = DB_query($sql, '','', false, false);
					echo '<tr><td>' . $sql . '</td>';
					switch (DB_error_no()) {
						case 0:
							echo '<td style="background-color:green">' . _('Success') . '</td></tr>';
							break;
						case 1025:
							echo '<td style="background-color:yellow">' . _('Note').' - '. _('Foreign Key already removed') . '</td></tr>';
							break;
						case 1050:
							echo '<td style="background-color:yellow">' . _('Note').' - '. _('Table has already been created') . '</td></tr>';
							break;
						case 1054:
							echo '<td style="background-color:yellow">' . _('Note').' - '. _('Column has already been changed') . '</td></tr>';
							break;
						case 1060:
							echo '<td style="background-color:yellow">' . _('Note').' - '. _('Column has already been created') . '</td></tr>';
							break;
						case 1061:
							echo '<td style="background-color:yellow">' . _('Note').' - '. _('Index already exists') . '</td></tr>';
							break;
						case 1062:
							echo '<td style="background-color:yellow">' . _('Note').' - '. _('Entry has already been done') . '</td></tr>';
							break;
						case 1064:
							echo '<td style="background-color:red">' . _('Note').' - '.  _('SQL syntax error. The SQL error message is'). ' ' . DB_error_msg() . '</td></tr>';
							break;
						case 1068:
							echo '<td style="background-color:yellow">' . _('Note').' - '. _('Primary key already exists') . '</td></tr>';
							break;
						case 1091:
							echo '<td style="background-color:yellow">' . _('Note').' - '. _('Index already dropped previously') . '</td></tr>';
							break;
						default:
							echo '<td style="background-color:red">' . _('Failure').' - '. 	_('Error number').' - '.DB_error_no() .' ' . DB_error_msg() . '</td></tr>';
							break;
					}
					$sql='';
				}
			} //end if its a valid sql line not a comment
		} //end of for loop around the lines of the sql script
	echo '</table>';
	} //end of loop around SQLScripts  apply
	$result =DB_ReinstateForeignKeys();
	/*Now get the modified VersionNumber and script pagesecurities */
	$ForceConfigReload=true;
	include('includes/GetConfig.php');
} /*Dont do upgrade */

include('includes/footer.php');
?>

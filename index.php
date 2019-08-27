<?php
$PageSecurity = 0;

include ('includes/session.php');
$Title = _('Main Menu');
include ('includes/header.php');

/*The module link codes are hard coded in a switch statement below to determine the options to show for each tab */
include ('includes/MainMenuLinksArray.php');

if (isset($SupplierLogin) and $SupplierLogin == 1) {
	echo '<table class="table_index">
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SupplierTenders.php?TenderType=1">' . _('View or Amend outstanding offers') . '</a></p>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SupplierTenders.php?TenderType=2">' . _('Create a new offer') . '</a></p>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SupplierTenders.php?TenderType=3">' . _('View any open tenders without an offer') . '</a></p>
			</td>
			</tr>
		</table>';
	include ('includes/footer.php');
	exit;
} elseif (isset($CustomerLogin) and $CustomerLogin == 1) {
	echo '<table class="table_index">
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/CustomerInquiry.php?CustomerID=' . $_SESSION['CustomerID'] . '">' . _('Account Status') . '</a></p>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SelectOrderItems.php?NewOrder=Yes">' . _('Place An Order') . '</a></p>
			</td>
			</tr>
			<tr>
			<td class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . '/SelectCompletedOrder.php?SelectedCustomer=' . $_SESSION['CustomerID'] . '">' . _('Order Status') . '</a></p>
			</td>
			</tr>
		</table>';

	include ('includes/footer.php');
	exit;
}

if (isset($_GET['Application'])) { /*This is sent by this page (to itself) when the user clicks on a tab */
	$_SESSION['Module'] = $_GET['Application'];
}

// BEGIN MainMenuDiv ===========================================================
// Option 1:
echo '<div class="row well sidebar-nav ">';
echo '<div class="col-md-3">';
echo '<div id="MainMenuDiv" class= "collapse navbar-collapse navbar-bootsnipp-collapse sidebar-navbar-collapse ">';
echo '<ul class="nav">'; //===HJ===
/*
// Option 2:
echo '<div id="MainMenuDiv" class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><ul class="nav">';
*/

$i = 0;
while ($i < count($ModuleLink)) {
	// This determines if the user has display access to the module see config.php and header.php
	// for the authorisation and security code
	if ($_SESSION['ModulesEnabled'][$i] == 1) {
		// If this is the first time the application is loaded then it is possible that
		// SESSION['Module'] is not set if so set it to the first module that is enabled for the user
		if (!isset($_SESSION['Module']) or $_SESSION['Module'] == '') {
			$_SESSION['Module'] = $ModuleLink[$i];
		}
		if ($ModuleLink[$i] == $_SESSION['Module']) {
			echo '<li class="main_menu_selected">';
		} else {
			echo '<li class="main_menu_unselected">';

		}
		echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?Application=' . $ModuleLink[$i] . '">' . $ModuleList[$i] . '</a></li>';
	}
	$i++;
}
echo '</ul></div></div>'; // MainMenuDiv ===HJ===


//=== SubMenuDiv (wrapper) ==============================================================================
echo '<div id="SubMenuDiv">'; //===HJ===
// BEGIN TransactionsDiv =======================================================
echo '<div id="TransactionsDiv" class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><ul class="list-group-item">';

echo '<li class="menu_group_headers">'; //=== SubMenuHeader ===
if ($_SESSION['Module'] == 'system') {
	$Header = '<img src="' . $RootPath . '/css/' . $Theme . '/images/company.png" title="' . _('General Setup Options') . '" alt="' . _('General Setup Options') . '" /><b>' . _('General Setup Options') . '</b>';
} else {
	$Header = '<img alt="" src="' . $RootPath . '/css/' . $Theme . '/images/transactions.png" title="' . _('Transactions') . '" /><b>' . _('Transactions') . '</b>';
}
echo $Header;
echo '</li>'; // SubMenuHeader
//=== SubMenu Items ===
$i = 0;
foreach ($MenuItems[$_SESSION['Module']]['Transactions']['Caption'] as $Caption) {
	/* Transactions Menu Item */
	$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Transactions']['URL'][$i], 1));
	$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
	if ((in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($PageSecurity))) {
		echo '<li class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . $MenuItems[$_SESSION['Module']]['Transactions']['URL'][$i] . '">' . $Caption . '</a></p>
			  </li>';
	}
	$i++;
}
echo '</ul></div>';
// END TransactionsDiv =========================================================


echo '<div id="InquiriesDiv" class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><ul class="list-group-item">'; //=== InquiriesDiv ===
echo '<li class="menu_group_headers">';
if ($_SESSION['Module'] == 'system') {
	$Header = '<img src="' . $RootPath . '/css/' . $Theme . '/images/ar.png" title="' . _('Receivables/Payables Setup') . '" alt="' . _('Receivables/Payables Setup') . '" /><b>' . _('Receivables/Payables Setup') . '</b>';
} else {
	$Header = '<img alt="" src="' . $RootPath . '/css/' . $Theme . '/images/reports.png" title="' . _('Inquiries and Reports') . '" /><b>' . _('Inquiries and Reports') . '</b>';
}
echo $Header;
echo '</li>';

$i = 0;
foreach ($MenuItems[$_SESSION['Module']]['Reports']['Caption'] as $Caption) {
	/* Transactions Menu Item */
	$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Reports']['URL'][$i], 1));
	$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
	if ((in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($PageSecurity))) {
		echo '<li class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . $MenuItems[$_SESSION['Module']]['Reports']['URL'][$i] . '">' . $Caption . '</a></p>
			  </li>';
	}
	$i++;
}
echo GetRptLinks($_SESSION['Module']); //=== GetRptLinks() must be modified!!! ===
echo '</ul></div>'; //=== InquiriesDiv ===


echo '<div id="MaintenanceDiv" class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><ul class="list-group-item">'; //=== MaintenanceDive ===
echo '<li class="menu_group_headers">';
if ($_SESSION['Module'] == 'system') {
	$Header = '<img src="' . $RootPath . '/css/' . $Theme . '/images/inventory.png" title="' . _('Inventory Setup') . '" alt="' . _('Inventory Setup') . '" /><b>' . _('Inventory Setup') . '</b>';
} else {
	$Header = '<img alt="" src="' . $RootPath . '/css/' . $Theme . '/images/maintenance.png" title="' . _('Maintenance') . '" /><b>' . _('Maintenance') . '</b>';

}
echo $Header;
echo '</li>';

$i = 0;
foreach ($MenuItems[$_SESSION['Module']]['Maintenance']['Caption'] as $Caption) {
	/* Transactions Menu Item */
	$ScriptNameArray = explode('?', substr($MenuItems[$_SESSION['Module']]['Maintenance']['URL'][$i], 1));
	$PageSecurity = $_SESSION['PageSecurityArray'][$ScriptNameArray[0]];
	if ((in_array($PageSecurity, $_SESSION['AllowedPageSecurityTokens']) or !isset($PageSecurity))) {
		echo '<li class="menu_group_item">
				<p>&bull; <a href="' . $RootPath . $MenuItems[$_SESSION['Module']]['Maintenance']['URL'][$i] . '">' . $Caption . '</a></p>
			  </li>';
	}
	$i++;
}
echo '</ul></div>'; // MaintenanceDive ===HJ===
echo '</div>';
echo '</div>'; // SubMenuDiv ===HJ===
include ('includes/footer.php');

function GetRptLinks($GroupID) {
	/*
	This function retrieves the reports given a certain group id as defined in /reports/admin/defaults.php
	in the acssociative array $ReportGroups[]. It will fetch the reports belonging solely to the group
	specified to create a list of links for insertion into a table to choose a report. Two table sections will
	be generated, one for standard reports and the other for custom reports.
	*/
	global $RootPath, $ReportList;
	require_once ('reportwriter/languages/en_US/reports.php');
	require_once ('reportwriter/admin/defaults.php');
	$GroupID = $ReportList[$GroupID];
	$Title = array(_('Custom Reports'), _('Standard Reports and Forms'));

	if (!isset($_SESSION['ReportList'])) {
		$SQL = "SELECT id,
						reporttype,
						defaultreport,
						groupname,
						reportname
					FROM reports
					ORDER BY groupname,
							reportname";
		$Result = DB_query($SQL, '', '', false, true);
		$_SESSION['ReportList'] = array();
		while ($Temp = DB_fetch_array($Result)) {
			$_SESSION['ReportList'][] = $Temp;
		}
	}
	$RptLinks = '';
	for ($Def = 1;$Def >= 0;$Def--) {
		$RptLinks.= '<li class="menu_group_headers">';
		$RptLinks.= '<b>' . $Title[$Def] . '</b>';
		$RptLinks.= '</li>';
		$NoEntries = true;
		if (isset($_SESSION['ReportList']['groupname']) and count($_SESSION['ReportList']['groupname']) > 0) { // then there are reports to show, show by grouping
			foreach ($_SESSION['ReportList'] as $Report) {
				if (isset($Report['groupname']) and $Report['groupname'] == $GroupID and $Report['defaultreport'] == $Def) {
					$RptLinks.= '<li class="menu_group_item">';
					$RptLinks.= '<p><a href="' . $RootPath . '/reportwriter/ReportMaker.php?action=go&amp;reportid=' . urlencode($Report['id']) . '">&bull; ' . _($Report['reportname']) . '</a></p>';
					$RptLinks.= '</li>';
					$NoEntries = false;
				}
			}
			// now fetch the form groups that are a part of this group (List after reports)
			$NoForms = true;
			foreach ($_SESSION['ReportList'] as $Report) {
				$Group = explode(':', $Report['groupname']); // break into main group and form group array
				if ($NoForms and $Group[0] == $GroupID and $Report['reporttype'] == 'frm' and $Report['defaultreport'] == $Def) {
					$RptLinks.= '<li class="menu_group_item">';
					$RptLinks.= '<img src="' . $RootPath . '/css/' . $_SESSION['Theme'] . '/images/folders.gif" width="16" height="13" alt="" />&nbsp;';
					$RptLinks.= '<a href="' . $RootPath . '/reportwriter/FormMaker.php?id=' . urlencode($Report['groupname']) . '">&bull; ';
					$RptLinks.= $_SESSION['FormGroups'][$Report['groupname']] . '</a>';
					$RptLinks.= '</li>';
					$NoForms = false;
					$NoEntries = false;
				}
			}
		}
		if ($NoEntries) $RptLinks.= '<li class="menu_group_item">' . _('There are no reports to show!') . '</li>';
	}
	return $RptLinks;
}

?>

<?php
/*	header.php */
/*	Titles and screen header */

//	Needs the file config.php loaded where the variables are defined for $RootPath.
//	$Title - should be defined in the page before this file is included.
//	The line '<meta name="viewport" content="width=device-width, initial-scale=1">' is to tell the small device that the website is a responsive site (keep relationship between CSS pixels and device pixels).
//	All the echo() functions are formatted thinking on the html page than in the php script style.

if (!isset($RootPath)) {
	$RootPath = dirname(htmlspecialchars($_SERVER['PHP_SELF']));
	if ($RootPath == '/' OR $RootPath == "\\") {
		$RootPath = '';
	}
}

$ViewTopic = isset($ViewTopic) ? '?ViewTopic=' . $ViewTopic : '';
$BookMark = isset($BookMark) ? '#' . $BookMark : '';

if(isset($Title) && $Title == _('Copy a BOM to New Item Code')){//solve the cannot modify header information in CopyBOM.php scripts
	ob_start();
}

// The "link" tag requires a "rel" attribute. In the "meta" tag, the "content" attribute gives the value associated with the "http-equiv" or "name" attributes.
echo '<!DOCTYPE html>
<head>
	<title>', $Title, '</title>
	<link rel="icon" href="', $RootPath, '/favicon.ico" />
	<link rel="stylesheet" href="', $RootPath, '/css/', $_SESSION['Theme'], '/default.css" media="screen" type="text/css" />
	<link rel="stylesheet" href="', $RootPath, '/css/menu.css" type="text/css" />
	<link rel="stylesheet" href="', $RootPath, '/css/print.css" media="print" type="text/css" />
	<meta http-equiv="Content-Type" content="application/html; charset=utf-8; cache-control:no-cache, no-store, must-revalidate; Pragma:no-cache" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script defer="defer" src="', $RootPath, '/javascripts/MiscFunctions.js"></script>
	<script>
		localStorage.setItem("DateFormat", "', $_SESSION['DefaultDateFormat'], '");
		localStorage.setItem("Theme", "', $_SESSION['Theme'], '");
	</script>';

// If it is set the $_SESSION['ShowPageHelp'] parameter AND it is FALSE, hides the page help text:
if (isset($_SESSION['ShowPageHelp']) AND !$_SESSION['ShowPageHelp']) {
	echo '
	<style>
		.page_help_text {display:none;}
	</style>';
}

echo '
</head>
<body>
	<div id="CanvasDiv">
		<input type="hidden" name="Lang" id="Lang" value="', $Lang, '" />
		<div id="HeaderDiv">
			<div id="HeaderWrapDiv">';

if (isset($Title)) {
	echo		'<div id="AppInfoDiv">',
					'<div id="AppInfoCompanyDiv">',
						'<img alt="', _('Company'), '" src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/company.png" title="', _('Company'), '" />&nbsp;', stripslashes($_SESSION['CompanyRecord']['coyname']),
					'</div>',
					'<div id="AppInfoUserDiv">',
						'<a href="', $RootPath, '/UserSettings.php">&nbsp;<img alt="', _('User'), '" src="', $RootPath, '/css/', $_SESSION['Theme'], '/images/user.png" title="', _('User'), '" />&nbsp;', stripslashes($_SESSION['UsersRealName']), '</a>',
					'</div>',
					'<div id="AppInfoModuleDiv">',
						$Title, // Make the title text a class, can be set to display:none in some themes
					'</div>',
				'</div>',
				'<div id="QuickMenuDiv">
					<ul id="menu">
						<li><a href="', $RootPath, '/index.php">', _('Main Menu'), '</a>';
	if ($_SESSION['ShortcutMenu']==1) {
		if (isset($_POST['AddToMenu'])) {
			if (!isset($_SESSION['Favourites'][$_POST['ScriptName']])) {
				$_SESSION['Favourites'][$_POST['ScriptName']] = $_POST['Title'];
			}
		}

		if (isset($_POST['DelFromMenu'])) {
			unset($_SESSION['Favourites'][$_POST['ScriptName']]);
		}

		if (isset($_SESSION['Favourites']) AND count($_SESSION['Favourites'])>0) {
			echo '<ul>';
			foreach ($_SESSION['Favourites'] as $url=>$ttl) {
				echo '<li><a href="', $url, '">', _($ttl), '<a></li>';
			}
			echo '</ul>';
		}
	}
}

	echo '</li>'; //take off inline formatting, use CSS instead ===HJ===

	if (count($_SESSION['AllowedPageSecurityTokens'])>1){
		echo '<li><a href="', $RootPath, '/Dashboard.php">', _('Dashboard'), '</a></li>',
			'<li><a href="', $RootPath, '/SelectCustomer.php">', _('Customers'), '</a></li>',
			'<li><a href="', $RootPath, '/SelectProduct.php">', _('Items'), '</a></li>',
			'<li><a href="', $RootPath, '/SelectSupplier.php">', _('Suppliers'), '</a></li>',
			'<li><a href="', $RootPath, '/ManualContents.php', $ViewTopic, $BookMark, '" rel="external" accesskey="8">', _('Manual'), '</a></li>';
	}

	echo				'<li><a href="', $RootPath, '/Logout.php" onclick="return confirm(\'', _('Are you sure you wish to logout?'), '\');">', _('Logout'), '</a></li>',
					'</ul>',
				'</div>';// END div id="QuickMenuDiv" ;

echo		'</div>',// END div id="HeaderWrapDiv"
		'</div>',// END div id="Headerdiv"
		'<div id="BodyDiv">',
			'<div id="BodyWrapDiv">',
				'<div id="MessageContainerHead"></div>';
?>
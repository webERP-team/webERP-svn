<?php
// ManualContents.php
/* Shows the local manual content if available, else shows the manual content in en-GB. */
/* This program is under the GNU General Public License, last version. */
/* This creative work is under the CC BY-NC-SA, later version. */

/*
This table of contents allows the choice to display one section or select multiple sections to format for print.
Selecting multiple sections is for printing.
The outline of the Table of Contents is contained in the 'ManualOutline.php' file that can be easily translated.
The individual topics in the manual are in straight html files that are called along with the header and foot from here.
Each function in webERP can initialise a $ViewTopic and $Bookmark variable, prior to including the header.php file.
This will display the specified topic and bookmark if it exists when the user clicks on the Manual link in the webERP main menu.
In this way the help can be easily broken into sections for online context-sensitive help.
Comments beginning with Help Begin and Help End denote the beginning and end of a section that goes into the online help.
What section is named after Help Begin: and there can be multiple sections separated with a comma.
*/

// BEGIN: Procedure division ---------------------------------------------------
$PageSecurity = 0;
$Title = _('webERP Manual');
// Set the language to show the manual:
/*
session_start();
$Language = $_SESSION['Language'];
if(isset($_GET['Language'])) {// Set an other language for manual.
	$Language = $_GET['Language'];
}
*/
include('includes/session.php');

// Set the Cascading Style Sheet for the manual:
$ManualStyle = 'locale/' . $Language . '/Manual/style/manual.css';
if(!file_exists($ManualStyle)) {// If locale ccs not exist, use doc/Manual/style/manual.css. Each language can have its own css.
	$ManualStyle = 'doc/Manual/style/manual.css';
}
// Set the the outline of the webERP manual:
$ManualOutline = 'locale/' . $Language . '/Manual/ManualOutline.php';
if(!file_exists($ManualOutline)) {// If locale outline not exist, use doc/Manual/ManualOutline.php. Each language can have its own outline.
	$ManualOutline = 'doc/Manual/ManualOutline.php';
}

ob_start();

// Output the header part:
$ManualHeader = 'locale/' . $Language . '/Manual/ManualHeader.html';
if(file_exists($ManualHeader)) {// Use locale ManualHeader.html if exists. Each language can have its own page header.
	include($ManualHeader);
} else {// Default page header:
	echo '<!DOCTYPE html>
	<html>
	<head>
	  <title>', $Title, '</title>
	  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	  <link rel="stylesheet" type="text/css" href="', $ManualStyle, '" />
	</head>
	<body lang="', str_replace('_', '-', substr($Language, 0, 5)), '">
		<div id="pagetitle">', $Title, '</div>
		<div class="right">
			<a id="top">&#160;</a><a class="minitext" href="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '">☜ ', _('Table of Contents'), '</a><br />
			<a class="minitext" href="#bottom">⬇ ', _('Go to Bottom'), '</a>
		</div>';
}

include($ManualOutline);
$_GET['Bookmark'] = isset($_GET['Bookmark']) ? $_GET['Bookmark'] : '';
$_GET['ViewTopic'] = isset($_GET['ViewTopic']) ? $_GET['ViewTopic'] : '';

//all sections of manual listed here
if(((!isset($_POST['Submit'])) and (empty($_GET['ViewTopic']))) || ((isset($_POST['Submit'])) and (isset($_POST['SelectTableOfContents'])))) {
	echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" method="post">';
	// if not submittws then coming into manual to look at TOC
	// if SelectTableOfContents set then user wants it displayed
	if(!isset($_POST['Submit'])) {
		echo '<p>', _('Click on a link to view a page, or check boxes and click on "Display Checked" to view selected in one page'), '</p>';
		echo '<p><input type="submit" name="Submit" value="', _('Display Checked'), '" /></p>';
	}
	echo '<h1>';
	if(!isset($_POST['Submit'])) {
		echo '<input name="SelectTableOfContents" type="checkbox">';
	}
	echo _('Table of Contents'), '</h1>';
	$j = 0;
	foreach($TOC_Array['TableOfContents'] as $Title => $SubLinks) {
		$Name = 'Select' . $Title;
		echo '<ul>
			<li class="toc"';
		// List topic title:
		if(!isset($_POST['Submit'])) {
			echo ' style="list-style-type:none;"><input id="roundedOne', $j, '" name="', $Name, '" type="checkbox" value="None" /';
		}
		echo '>
			<a href="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '?ViewTopic=', $Title, '">', $SubLinks[0], '</a></li>';
		// List topic content:
		if(count($SubLinks) > 1) {
			echo '<ul>';
			foreach($SubLinks as $k => $SubName) {
				if($k == 0) {// Skip first array element $SubLinks[0].
					continue;
				}
				echo '<li>', $SubName, '</li>';
			}
			echo '</ul>';
		}

		echo '</ul>';
		++$j;
	}
	echo '</ul>',
		'<p><input type="submit" name="Submit" value="', _('Display Checked'), '" /></p>',
		'</form>';
}

if(!isset($_GET['ViewTopic'])) {
	$_GET['ViewTopic'] = '';
}

foreach($TOC_Array['TableOfContents'] as $Name => $FullName) {
	$PostName = 'Select' . $Name;
	if(($_GET['ViewTopic'] == $Name) or (isset($_POST[$PostName]))) {
		if($Name == 'APIFunctions') {
			$Name .= '.php';
		} else {
			$Name .= '.html';
		}
		$ManualPage = 'locale/' . $Language . '/Manual/Manual' . $Name;
		if(!file_exists($ManualPage)) {// If locale topic page not exist, use topic page in doc/Manual.
			$ManualPage = 'doc/Manual/Manual' . $Name;
		}
		echo '<div id="manualpage">';
		include($ManualPage);
		echo '</div>';
	}
}

// Output the footer part:
$ManualFooter = 'locale/' . $Language . '/Manual/ManualFooter.html';
if(file_exists($ManualFooter)) {// Use locale ManualHeader.html if exists. Each language can have its own page footer.
	include($ManualFooter);
} else {// Default page footer:
	echo '<div class="right">
			<a id="bottom">&#160;</a><a class="minitext" href="#top">⬆ ', _('Go to Top'), '</a><br />
			<a id="top">&#160;</a><a class="minitext" href="', htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8'), '">☜ ', _('Table of Contents'), '</a>
		</div>
	</body>
	</html>';
}

ob_end_flush();
// END: Procedure division -----------------------------------------------------
?>
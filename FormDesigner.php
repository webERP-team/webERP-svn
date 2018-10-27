<?php
/*	FormDesigner.php */
/*	Allows to customize the form layout without requiring the use of scripting or technical development. */

/*	Form Designer notes:
	- All measurements are in PostScript points (72 points = 25,4 mm).
	- All coordinates are measured from the lower left corner of the sheet to the top left corner of the field. */
/*	General attributes for elements:
	- x (X coordinate),
	- y (Y coordinate),
	- Width,
	- Height,
	- FontSize,
	- Alignment,
	- Show (to show or not an element). */

/* RCHACON: To Do: standardize the name of the parameters x, y, width, height, font-size, alignment and radius inside the xml files. Non-standard attribute "Length" should be replace with "width". */
/* RCHACON: Question: The use or not of <label for="KeyId">KeyCaption</label> <input id="KeyId" name="KeyName" type="..." value="KeyValue"> for usability ? */

include ('includes/session.php');
$Title = _('Form Designer');
$ViewTopic = 'Setup';
$BookMark = 'FormDesigner';
include ('includes/header.php');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme, '/images/reports.png" title="', // Icon image.
_('Form Designer'), '" /> ', // Icon title.
_('Form Designer'), '</p>'; // Page title.


// BEGIN: Functions division ---------------------------------------------------
function InputX($keyName, $keyValue) {
	// Function to input the X coordinate from the left side of the sheet to the left side of the field in points (72 points = 25,4 mm).
	echo '<td class="number"><label for="', $keyName, 'x">', _('x'), ' = </label></td>', '<td><input class="number" id="', $keyName, 'x" maxlength="4" name="', $keyName, 'x" size="4" title="', _('Distance from the left side of the sheet to the left side of the element in points'), '" type="number" value="', $keyValue, '" /></td>';
}
function InputY($keyName, $keyValue) {
	// Function to input the Y coordinate from the lower side of the sheet to the top side of the field in points (72 points = 25,4 mm).
	echo '<td class="number"><label for="', $keyName, 'y">', _('y'), ' = </label></td>', '<td><input class="number" id="', $keyName, 'y" maxlength="4" name="', $keyName, 'y" size="4" title="', _('Distance from the lower side of the sheet to the top side of the element in points'), '" type="number" value="', $keyValue, '" /></td>';
}
function InputLength($keyName, $keyValue) {
	// Function to input the the Length of the field in points (72 points = 25,4 mm).
	echo '<td class="number"><label for="', $keyName, 'Length">', _('Width'), ' = </label></td>', '<td><input class="number" id="', $keyName, 'Length" maxlength="4" name="', $keyName, 'Length" size="4" title="', _('Width of the element in points'), '" type="number" value="', $keyValue, '" /></td>';
	// Requires to standardize xml files from "Length" to "Width" before changing the xml name.**********
	
}
function InputWidth($keyName, $keyValue) {
	// Function to input the the width of the field in points (72 points = 25,4 mm).
	echo '<td class="number"><label for="', $keyName, 'width">', _('Width'), ' = </label></td>', '<td><input class="number" id="', $keyName, 'width" maxlength="4" name="', $keyName, 'width" size="4" title="', _('Width of the element in points'), '" type="number" value="', $keyValue, '" /></td>';
	// Requires to standardize xml files from "width" to "Width" before changing the xml name.**********
	
}
function InputHeight($keyName, $keyValue) {
	// Function to input the the height of the field in points (72 points = 25,4 mm).
	echo '<td class="number"><label for="', $keyName, 'height">', _('Height'), ' = </label></td>', '<td><input class="number" id="', $keyName, 'height" maxlength="4" name="', $keyName, 'height" size="4" title="', _('Height of the element in points'), '" type="number" value="', $keyValue, '" /></td>';
	// Requires to standardize xml files from "height" to "Height" before changing the xml name.**********
	
}
function SelectAlignment($keyName, $keyValue) {
	// Function to select a text alignment.
	$Alignments = array(); // Possible alignments
	$Alignments['left']['Caption'] = _('Left');
	$Alignments['left']['Title'] = _('Text lines are rendered flush left');
	$Alignments['centre']['Caption'] = _('Centre');
	$Alignments['centre']['Title'] = _('Text lines are centred');
	$Alignments['right']['Caption'] = _('Right');
	$Alignments['right']['Title'] = _('Text lines are rendered flush right');
	$Alignments['full']['Caption'] = _('Justify');
	$Alignments['full']['Title'] = _('Text lines are justified to both margins');
	echo '<td>' . _('Alignment') . ' = </td><td><select name="', $keyName, 'Alignment">';
	foreach ($Alignments as $AlignmentValue => $AlignmentOption) {
		echo '<option';
		if ($AlignmentValue == $keyValue) {
			echo ' selected="selected"';
		}
		echo ' title="' . $AlignmentOption['Title'] . '" value="' . $AlignmentValue . '">' . $AlignmentOption['Caption'] . '</option>';
	}
	echo '</select></td>';
}
function InputFontSize($keyName, $keyValue) {
	// Function to select a text font size.
	echo '<td class="number"><label for="', $keyName, 'FontSize">', _('Font Size'), ' = </label></td>', '<td><input class="number" id="', $keyName, 'FontSize" maxlength="4" name="', $keyName, 'FontSize" size="4" title="', _('Font size in points'), '" type="number" value="', $keyValue, '" /></td>';
}
function SelectShowElement($keyName, $keyValue) {
	// Function to select to show or not an element.
	$Shows = array(); // Possible alignments
	$Shows['No']['Caption'] = _('No');
	$Shows['No']['Title'] = _('Does not display this element');
	$Shows['Yes']['Caption'] = _('Yes');
	$Shows['Yes']['Title'] = _('Displays this element');
	echo '<td><label for="', $keyName, 'Show">', _('Show'), ' = </label></td>', '<td><select id="', $keyName, 'Show" name="', $keyName, 'Show">';
	foreach ($Shows as $ShowValue => $ShowOption) {
		echo '<option';
		if ($ShowValue == $keyValue) {
			echo ' selected="selected"';
		}
		echo ' title="' . $ShowOption['Title'] . '" value="' . $ShowValue . '">' . $ShowOption['Caption'] . '</option>';
	}
	echo '</select></td>';
}
// END: Functions division -----------------------------------------------------


// BEGIN: Data division --------------------------------------------------------
$PaperSizes = array('A3_Portrait', 'A3_Landscape', 'A4_Portrait', 'A4_Landscape', 'A5_Portrait', 'A5_Landscape', 'A6_Portrait', 'A6_Landscape', 'Legal_Portrait', 'Legal_Landscape', 'Letter_Portrait', 'Letter_Landscape'); // Possible paper sizes and orientations.
// END: Data division ----------------------------------------------------------


// BEGIN: Procedure division ---------------------------------------------------
/* If the user has chosen to either preview the form, or
 * save it then we first have to get the POST values into a
 * simplexml object and then save the file as either a
 * temporary file, or into the main code
*/
if (isset($_POST['preview']) or isset($_POST['save'])) {
	/*First create a simple xml object from the main file */
	$FormDesign = simplexml_load_file($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/' . $_POST['FormName']);
	$FormDesign['name'] = $_POST['formname'];
	if (mb_substr($_POST['PaperSize'], -8) == 'Portrait') {
		$_POST['PaperSize'] = mb_substr($_POST['PaperSize'], 0, mb_strlen($_POST['PaperSize']) - 9);
	}
	$FormDesign->PaperSize = $_POST['PaperSize'];
	$FormDesign->LineHeight = $_POST['LineHeight'];
	/*Iterate through the object filling in the values from the POST variables */
	foreach ($FormDesign as $key) {
		foreach ($key as $subkey => $value) {
			if ($key['type'] == 'ElementArray') {
				foreach ($value as $subsubkey => $subvalue) {
					$value->$subsubkey = $_POST[$value['id'] . $subsubkey];
				}
			} else {
				$key->$subkey = $_POST[$key['id'] . $subkey];
			}
		}
	}
	/* If we are just previewing the form then
	 * save it to the temporary directory and call the
	 * PDF creating script */
	if (isset($_POST['preview'])) {
		$FormDesign->asXML(sys_get_temp_dir() . '/' . $_POST['FormName']);
		switch ($_POST['FormName']) {
			case 'PurchaseOrder.xml':
				echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/PO_PDFPurchOrder.php?OrderNo=Preview">';
			break;
			case 'GoodsReceived.xml':
				echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/PDFGrn.php?GRNNo=Preview&PONo=1">';
			break;
			case 'PickingList.xml':
				echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/PDFPickingList.php?TransNo=Preview">';
			break;
			case 'QALabel.xml':
				echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/PDFQALabel.php?GRNNo=Preview&PONo=1">';
			break;
			case 'WOPaperwork.xml':
				echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/PDFWOPrint.php?WO=Preview">';
			break;
			case 'FGLabel.xml':
				echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/PDFFGLabel.php?WO=Preview">';
			break;
			case 'ShippingLabel.xml':
				echo '<meta http-equiv="Refresh" content="0; url=' . $RootPath . '/PDFShipLabel.php?ORD=Preview">';
			break;
		}
	} else {
		/* otherwise check that the web server has write premissions on the companies
		 * directory and save the xml file to the correct directory */
		if (is_writable($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/' . $_POST['FormName'])) {
			$FormDesign->asXML($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/' . $_POST['FormName']);
		} else {
			prnMsg(_('The web server does not have write permissions on the file ') . '<br />' . $PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/' . $_POST['FormName'] . '<br />' . _('Your changes cannot be saved') . '<br />' . _('See your system administrator to correct this problem'), 'error');
		}
	}
}
/* If no form has been selected to edit, then offer a drop down list of possible forms */
if (empty($_POST['FormName'])) {
	echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" id="ChooseForm" method="post">
		<input name="FormID" type="hidden" value="', $_SESSION['FormID'], '" />
		<table class="selection">
			<tr>
				<th colspan="2">', _('Edit Form Layout'), '</th>
			</tr>
			<tr>
				<td>', _('Select the form to edit'), '</td>
				<td>
					<select name="FormName">';
	// Iterate throght the appropriate companies FormDesigns/ directory and extract the form name from each of the xml files found:
	if ($handle = opendir($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/')) {
		while (false !== ($file = readdir($handle))) {
			if ($file[0] != '.') {
				$FormDesign = simplexml_load_file($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/' . $file);
				//echo "name is". $FormDesign['name'];
				echo '<option value="', $file, '">' . /*_(*/
				$FormDesign['name'] /*)*/ . '</option>';
			}
		}
		closedir($handle);
	}
	echo '</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">', '<div class="centre">', '<button name="Submit" type="submit" value="Submit">', '<img alt="" src="', $RootPath, '/css/', $Theme, '/images/tick.svg" /> ', _('Submit'), '</button>', // "Submit" button.
	'<button onclick="window.location=\'index.php?Application=system\'" type="button">', '<img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
	'</div>
				</td>
			</tr>
		</table>
		</form>';
	include ('includes/footer.php');
	exit;
} // End of if (empty($_POST['FormName']))
/* If we are not previewing the form then load up the simplexml object from the main xml file */
if (empty($_POST['preview'])) {
	$FormDesign = simplexml_load_file($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/' . $_POST['FormName']);
}
echo '<div class="page_help_text">' . _('Enter the changes that you want in the form layout below.') . '<br /> ' . _('All measurements are in PostScript points (72 points = 25,4 mm).') . '<br /> ' . _('All coordinates are measured from the lower left corner of the sheet to the top left corner of the element.') . '</div><br />';

echo '<form action="', htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'), '" id="Form" method="post" >';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<input name="FormName" type="hidden" value="' . $_POST['FormName'] . '" />';

echo '<table class="selection">
			<tr>
				<th colspan="2">', _((string)$FormDesign['name']), '</th>
			</tr>
			<tr>
				<td><label for="formname">', _('Form Name'), '</label></td>
				<td><input id="formname" name="formname" type="text" value="', $FormDesign['name'], '" /></td>
			</tr>
			<tr>', // Select the paper size/orientation:
'<td><label for="PaperSize">', _('Paper Size'), '</label></td>
				<td>
					<select id="PaperSize" name="PaperSize">';
foreach ($PaperSizes as $Paper) {
	if (mb_substr($Paper, -8) == 'Portrait') {
		$PaperValue = mb_substr($Paper, 0, mb_strlen($Paper) - 9);
	} else {
		$PaperValue = $Paper;
	}
	if ($PaperValue == $FormDesign->PaperSize) {
		echo '<option selected="selected" value="', $PaperValue, '">', $Paper, '</option>';
	} else {
		echo '<option value="', $PaperValue, '">', $Paper, '</option>';
	}
}
echo '</select>
				</td>
			</tr>
			<tr>', // Sets the standard line height for the form:
'<td><label for="LineHeight">', _('Line Height'), '</label></td>
				<td><input class="number" id="LineHeight" maxlength="4" name="LineHeight" size="4" title="', _('Standard line height for the form'), '" type="number" value="', $FormDesign->LineHeight, '" /></td>
			</tr>
		</table>
		<hr />
		<div>';
foreach ($FormDesign as $key) {
	echo '<div class="gallery">
		<table width="100%" border="0">';
	switch ($key['type']) {
		case 'image':
			echo '<tr><th colspan="2">', _((string)$key['name']), '</th></tr>
				<tr>', InputX($key['id'], $key->x), '</tr>
				<tr>', InputY($key['id'], $key->y), '</tr>
				<tr>', InputWidth($key['id'], $key->width), '</tr>
				<tr>', InputHeight($key['id'], $key->height), '</tr>';
		break;
		case 'SimpleText':
			echo '<tr><th colspan="2">', _((string)$key['name']), '</th></tr>
				<tr>', InputX($key['id'], $key->x), '</tr>
				<tr>', InputY($key['id'], $key->y), '</tr>
				<tr>', InputFontSize($key['id'], $key->FontSize), '</tr>';
		break;
		case 'MultiLineText':
			echo '<tr><th colspan="2">', _((string)$key['name']), '</th></tr>
				<tr>', InputX($key['id'], $key->x), '</tr>
				<tr>', InputY($key['id'], $key->y), '</tr>
				<tr>', InputLength($key['id'], $key->Length), '</tr>', // Non-standard attribute "Length" instead of "width".
			'<tr>', InputFontSize($key['id'], $key->FontSize), '</tr>';
		break;
		case 'ElementArray':
			echo '<tr><th colspan="7">' . _((string)$key['name']) . '</th></tr>';
			foreach ($key as $subkey) {
				echo '<tr>';
				if ($subkey['type'] == 'SimpleText') {
					echo '<td>' . _((string)$subkey['name']) . '</td>';
					InputX($subkey['id'], $subkey->x);
					InputY($subkey['id'], $subkey->y);
					InputFontSize($subkey['id'], $subkey->FontSize);
				} elseif ($subkey['type'] == 'MultiLineText') { // This element (9 td) overflows the table size (7 td).
					echo '<td>' . _((string)$subkey['name']) . '</td>';
					InputX($subkey['id'], $subkey->x);
					InputY($subkey['id'], $subkey->y);
					InputLength($subkey['id'], $subkey->Length);
					InputFontSize($subkey['id'], $subkey->FontSize);
				} elseif ($subkey['type'] == 'DataText') {
					echo '<td>' . _((string)$subkey['name']) . '</td>';
					InputX($subkey['id'], $subkey->x);
					InputLength($subkey['id'], $subkey->Length);
					InputFontSize($subkey['id'], $subkey->FontSize);
				} elseif ($subkey['type'] == 'StartLine') {
					echo '<td colspan="3">' . _((string)$subkey['name']) . ' = ' . '</td>';
					echo '<td><input type="text" class="number" name="StartLine" size="4" maxlength="4" value="' . $key->y . '" /></td>';
				}
				echo '</tr>';
			}
		break;
		case 'CurvedRectangle':
			echo '<tr><th colspan="2">', _((string)$key['name']), '</th></tr>
				<tr>', InputX($key['id'], $key->x), '</tr>
				<tr>', InputY($key['id'], $key->y), '</tr>
				<tr>', InputWidth($key['id'], $key->width), '</tr>
				<tr>', InputHeight($key['id'], $key->height), '</tr>
				<tr>
					<td class="number">', _('Radius'), ' = ', '</td>
					<td><input class="number" maxlength="4" name="', $key['id'], 'radius" size="4" title="', _('Radius of the rounded corners'), '" type="number" value="', $key->radius, '" /></td>
				</tr>'; // Requires to standardize xml files from "radius" to "Radius" before changing the html name.
			/* RCHACON: Attributes to add:
			Show: To turn on/off the use of this rectangle.
			Corners: Numbers of the corners to be rounded: 1, 2, 3, 4 or any combination (1=top left, 2=top right, 3=bottom right, 4=bottom left).
			Style: (draw/fill): D (default), F, FD or DF.
			Fill: Filling color.*/

		break;
		case 'Rectangle': // This case can be included in CurvedRectangle.
			echo '<tr><th colspan="2">', _((string)$key['name']), '</th></tr>
				<tr>', InputX($key['id'], $key->x), '</tr>
				<tr>', InputY($key['id'], $key->y), '</tr>
				<tr>', InputWidth($key['id'], $key->width), '</tr>
				<tr>', InputHeight($key['id'], $key->height), '</tr>';
		break;
		case 'Line':
			echo '<tr><th colspan="6">' . _((string)$key['name']) . '</th></tr>';
			echo '<tr>';
			echo '<td class="number">', _('Start x co-ordinate') . ' = ' . '</td><td><input type="text" class="number" name="' . $key['id'] . 'startx" size="4" maxlength="4" value="' . $key->startx . '" /></td>';
			echo '<td class="number">', _('Start y co-ordinate') . ' = ' . '</td><td><input type="text" class="number" name="' . $key['id'] . 'starty" size="4" maxlength="4" value="' . $key->starty . '" /></td></tr><tr>';
			echo '<td class="number">', _('End x co-ordinate') . ' = ' . '</td><td><input type="text" class="number" name="' . $key['id'] . 'endx" size="4" maxlength="4" value="' . $key->endx . '" /></td>';
			echo '<td class="number">', _('End y co-ordinate') . ' = ' . '</td><td><input type="text" class="number" name="' . $key['id'] . 'endy" size="4" maxlength="4" value="' . $key->endy . '" /></td>';
			echo '</tr>';
		break;
		default:
		break;
	} // END switch ($key['type']).
	echo '</table>
		</div>';
} // END foreach ($FormDesign as $key).
echo '</div>
	<br />
		<div class="centre" style="float:none;clear: left;">
			<input type="submit" name="preview" value="' . _('Preview the Form Layout') . '" />
			<input type="submit" name="save" value="' . _('Save the Form Layout') . '" />', '<button onclick="window.location=\'index.php?Application=system\'" type="button">', '<img alt="" src="', $RootPath, '/css/', $Theme, '/images/return.svg" /> ', _('Return'), '</button>', // "Return" button.
'</div>';

echo '</div>', '</form>';
// END: Procedure division ----------------------------------------------------


include ('includes/footer.php');
?>

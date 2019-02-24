<?php


include('includes/session.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/DefinePOClass.php');

if (!isset($_GET['OrderNo']) AND !isset($_POST['OrderNo'])) {
	$Title = _('Select a purchase order');
	include('includes/header.php');
	echo '<div class="centre"><br /><br /><br />';
	prnMsg(_('Select a Purchase Order Number to Print before calling this page'), 'error');
	echo '<br />
				<br />
				<br />
				<table class="table_index">
					<tr><td class="menu_group_item">
						<li><a href="' . $RootPath . '/PO_SelectOSPurchOrder.php">' . _('Outstanding Purchase Orders') . '</a></li>
						<li><a href="' . $RootPath . '/PO_SelectPurchOrder.php">' . _('Purchase Order Inquiry') . '</a></li>
						</td>
					</tr></table>
				</div>
				<br />
				<br />
				<br />';
	include('includes/footer.php');
	exit();

	echo '<div class="centre"><br /><br /><br />' . _('This page must be called with a purchase order number to print');
	echo '<br /><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a></div>';
	exit;
}
if (isset($_GET['OrderNo'])) {
	$OrderNo = $_GET['OrderNo'];
}
elseif (isset($_POST['OrderNo'])) {
	$OrderNo = $_POST['OrderNo'];
}
$Title = _('Print Purchase Order Number') . ' ' . $OrderNo;

if (isset($_POST['PrintOrEmail']) AND isset($_POST['EmailTo'])) {
	if ($_POST['PrintOrEmail'] == 'Email' AND !IsEmailAddress($_POST['EmailTo'])) {
		include('includes/header.php');
		prnMsg(_('The email address entered does not appear to be valid. No emails have been sent.'), 'warn');
		include('includes/footer.php');
		exit;
	}
}
$ViewingOnly = 0;

if (isset($_GET['ViewingOnly']) AND $_GET['ViewingOnly'] != '') {
	$ViewingOnly = $_GET['ViewingOnly'];
}
elseif (isset($_POST['ViewingOnly']) AND $_POST['ViewingOnly'] != '') {
	$ViewingOnly = $_POST['ViewingOnly'];
}

/* If we are previewing the order then we dont want to email it */
if ($OrderNo == 'Preview') { //OrderNo is set to 'Preview' when just looking at the format of the printed order
	$_POST['PrintOrEmail'] = 'Print';
	/*These are required to kid the system - I hate this */
	$_POST['ShowAmounts'] = 'Yes';
	$OrderStatus = _('Printed');
	$MakePDFThenDisplayIt = True;
} //$OrderNo == 'Preview'

if (isset($_POST['DoIt']) AND ($_POST['PrintOrEmail'] == 'Print' OR $ViewingOnly == 1)) {
	$MakePDFThenDisplayIt = True;
	$MakePDFThenEmailIt = False;
} elseif (isset($_POST['DoIt']) AND $_POST['PrintOrEmail'] == 'Email' AND isset($_POST['EmailTo'])) {
	$MakePDFThenEmailIt = True;
	$MakePDFThenDisplayIt = False;
}

if (isset($OrderNo) AND $OrderNo != '' AND $OrderNo > 0 AND $OrderNo != 'Preview') {
	/*retrieve the order details from the database to print */
	$ErrMsg = _('There was a problem retrieving the purchase order header details for Order Number') . ' ' . $OrderNo . ' ' . _('from the database');
	$sql = "SELECT	purchorders.supplierno,
					suppliers.suppname,
					suppliers.address1,
					suppliers.address2,
					suppliers.address3,
					suppliers.address4,
					suppliers.address5,
					suppliers.address6,
					purchorders.comments,
					purchorders.orddate,
					purchorders.rate,
					purchorders.dateprinted,
					purchorders.deladd1,
					purchorders.deladd2,
					purchorders.deladd3,
					purchorders.deladd4,
					purchorders.deladd5,
					purchorders.deladd6,
					purchorders.allowprint,
					purchorders.requisitionno,
					www_users.realname as initiator,
					purchorders.paymentterms,
					suppliers.currcode,
					purchorders.status,
					purchorders.stat_comment,
					currencies.decimalplaces AS currdecimalplaces
				FROM purchorders INNER JOIN suppliers
					ON purchorders.supplierno = suppliers.supplierid
				INNER JOIN currencies
					ON suppliers.currcode=currencies.currabrev
				INNER JOIN www_users
					ON purchorders.initiator=www_users.userid
				INNER JOIN locationusers ON locationusers.loccode=purchorders.intostocklocation AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
				WHERE purchorders.orderno='" . $OrderNo . "'";
	$result = DB_query($sql, $ErrMsg);
	if (DB_num_rows($result) == 0) {
		/*There is no order header returned */
		$Title = _('Print Purchase Order Error');
		include('includes/header.php');
		echo '<div class="centre"><br /><br /><br />';
		prnMsg(_('Unable to Locate Purchase Order Number') . ' : ' . $OrderNo . ' ', 'error');
		echo '<br />
			<br />
			<br />
			<table class="table_index">
				<tr><td class="menu_group_item">
				<li><a href="' . $RootPath . '/PO_SelectOSPurchOrder.php">' . _('Outstanding Purchase Orders') . '</a></li>
				<li><a href="' . $RootPath . '/PO_SelectPurchOrder.php">' . _('Purchase Order Inquiry') . '</a></li>
				</td>
				</tr>
			</table>
			</div><br /><br /><br />';
		include('includes/footer.php');
		exit();
	} elseif (DB_num_rows($result) == 1) {
		/*There is only one order header returned  (as it should be!)*/

		$POHeader = DB_fetch_array($result);

		if ($POHeader['status'] != 'Authorised' AND $POHeader['status'] != 'Printed') {
			include('includes/header.php');
			prnMsg(_('Purchase orders can only be printed once they have been authorised') . '. ' . _('This order is currently at a status of') . ' ' . _($POHeader['status']), 'warn');
			include('includes/footer.php');
			exit;
		}

		if ($ViewingOnly == 0) {
			if ($POHeader['allowprint'] == 0) {
				$Title = _('Purchase Order Already Printed');
				include('includes/header.php');
				echo '<p>';
				prnMsg(_('Purchase Order Number') . ' ' . $OrderNo . ' ' . _('has previously been printed') . '. ' . _('It was printed on') . ' ' . ConvertSQLDate($POHeader['dateprinted']) . '<br />' . _('To re-print the order it must be modified to allow a reprint') . '<br />' . _('This check is there to ensure that duplicate purchase orders are not sent to the supplier resulting in several deliveries of the same supplies'), 'warn');

				echo '<div class="centre">
 					<li><a href="' . $RootPath . '/PO_PDFPurchOrder.php?OrderNo=' . $OrderNo . '&ViewingOnly=1">' . _('Print This Order as a Copy') . '</a>
 					<li><a href="' . $RootPath . '/PO_Header.php?ModifyOrderNumber=' . $OrderNo . '">' . _('Modify the order to allow a real reprint') . '</a>
					<li><a href="' . $RootPath . '/PO_SelectPurchOrder.php">' . _('Select another order') . '</a>
					<li><a href="' . $RootPath . '/index.php">' . _('Back to the menu') . '</a></div>';

				include('includes/footer.php');
				exit;
			} //AllowedToPrint
		} //not ViewingOnly
	} // 1 valid record
} //if there is a valid order number
else if ($OrderNo == 'Preview') { // We are previewing the order

	/* Fill the order header details with dummy data */
	$POHeader['supplierno'] = str_pad('', 10, 'x');
	$POHeader['suppname'] = str_pad('', 40, 'x');
	$POHeader['address1'] = str_pad('', 40, 'x');
	$POHeader['address2'] = str_pad('', 40, 'x');
	$POHeader['address3'] = str_pad('', 40, 'x');
	$POHeader['address4'] = str_pad('', 40, 'x');
	$POHeader['address5'] = str_pad('', 20, 'x');
	$POHeader['address6'] = str_pad('', 15, 'x');
	$POHeader['comments'] = str_pad('', 50, 'x');
	$POHeader['orddate'] = '1900-01-01';
	$POHeader['rate'] = '0.0000';
	$POHeader['dateprinted'] = '1900-01-01';
	$POHeader['deladd1'] = str_pad('', 40, 'x');
	$POHeader['deladd2'] = str_pad('', 40, 'x');
	$POHeader['deladd3'] = str_pad('', 40, 'x');
	$POHeader['deladd4'] = str_pad('', 40, 'x');
	$POHeader['deladd5'] = str_pad('', 20, 'x');
	$POHeader['deladd6'] = str_pad('', 15, 'x');
	$POHeader['allowprint'] = 1;
	$POHeader['requisitionno'] = str_pad('', 15, 'x');
	$POHeader['initiator'] = str_pad('', 50, 'x');
	$POHeader['paymentterms'] = str_pad('', 15, 'x');
	$POHeader['currcode'] = 'XXX';
} // end of If we are previewing the order

/* Load the relevant xml file */
if (isset($MakePDFThenDisplayIt) or isset($MakePDFThenEmailIt)) {
	if ($OrderNo == 'Preview') {
		$FormDesign = simplexml_load_file(sys_get_temp_dir() . '/PurchaseOrder.xml');
	} else {
		$FormDesign = simplexml_load_file($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/PurchaseOrder.xml');
	}
	// Set the paper size/orintation
	$PaperSize = $FormDesign->PaperSize;
	include('includes/PDFStarter.php');
	$pdf->addInfo('Title', _('Purchase Order'));
	$pdf->addInfo('Subject', _('Purchase Order Number') . ' ' . $OrderNo);
	$line_height = $FormDesign->LineHeight;
	$PageNumber = 1;
	/* Then there's an order to print and its not been printed already (or its been flagged for reprinting)
	Now ... Has it got any line items */
	if ($OrderNo != 'Preview') { // It is a real order
		$ErrMsg = _('There was a problem retrieving the line details for order number') . ' ' . $OrderNo . ' ' . _('from the database');
		$sql = "SELECT itemcode,
						deliverydate,
						itemdescription,
						unitprice,
						suppliersunit,
						quantityord,
						decimalplaces,
						conversionfactor,
						suppliers_partno
				FROM purchorderdetails LEFT JOIN stockmaster
					ON purchorderdetails.itemcode=stockmaster.stockid
				WHERE orderno ='" . $OrderNo . "'
				ORDER BY itemcode";	/*- ADDED: Sort by our item code -*/
		$result = DB_query($sql);
	}
	if ($OrderNo == 'Preview' or DB_num_rows($result) > 0) {
		/*Yes there are line items to start the ball rolling with a page header */
		include('includes/PO_PDFOrderPageHeader.inc');
		$YPos = $Page_Height - $FormDesign->Data->y;
		$OrderTotal = 0;
		while ((isset($OrderNo) AND $OrderNo == 'Preview') OR (isset($result) AND !is_bool($result) AND $POLine = DB_fetch_array($result))) {
			/* If we are previewing the order then fill the
			 * order line with dummy data */
			if ($OrderNo == 'Preview') {
				$POLine['itemcode'] = str_pad('', 10, 'x');
				$POLine['deliverydate'] = '1900-01-01';
				$POLine['itemdescription'] = str_pad('', 50, 'x');
				$POLine['unitprice'] = 9999.99;
				$POLine['units'] = str_pad('', 4, 'x');
				$POLine['suppliersunit'] = str_pad('', 4, 'x');
				$POLine['quantityord'] = 9999.99;
				$POLine['conversionfactor'] = 1;
				$POLine['decimalplaces'] = 2;
			}
			if ($POLine['decimalplaces'] != NULL) {
				$DecimalPlaces = $POLine['decimalplaces'];
			}
			else {
				$DecimalPlaces = 2;
			}
			$DisplayQty = locale_number_format($POLine['quantityord'] / $POLine['conversionfactor'], $DecimalPlaces);
			if ($_POST['ShowAmounts'] == 'Yes') {
				$DisplayPrice = locale_number_format($POLine['unitprice'] * $POLine['conversionfactor'], $POHeader['currdecimalplaces']);
			} else {
				$DisplayPrice = '----';
			}
			$DisplayDelDate = ConvertSQLDate($POLine['deliverydate']);
			if ($_POST['ShowAmounts'] == 'Yes') {
				$DisplayLineTotal = locale_number_format($POLine['unitprice'] * $POLine['quantityord'], $POHeader['currdecimalplaces']);
			} else {
				$DisplayLineTotal = '----';
			}
			/* If the supplier item code is set then use this to display on the PO rather than the businesses item code */
			if (mb_strlen($POLine['suppliers_partno'])>0){
				$ItemCode = $POLine['suppliers_partno'];
			} else {
				$ItemCode = $POLine['itemcode'];
			}
			$OrderTotal += ($POLine['unitprice'] * $POLine['quantityord']);

			$LeftOvers = $pdf->addTextWrap($FormDesign->Data->Column1->x, $YPos, $FormDesign->Data->Column1->Length, $FormDesign->Data->Column1->FontSize, $ItemCode, 'left');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Data->Column2->x, $YPos, $FormDesign->Data->Column2->Length, $FormDesign->Data->Column2->FontSize, $POLine['itemdescription'], 'left');
			while (mb_strlen($LeftOvers) > 1) {
				$YPos -= $line_height;
				if ($YPos - $line_height <= $Bottom_Margin) {
					/* We reached the end of the page so finsih off the page and start a newy */
					$PageNumber++;
					$YPos = $Page_Height - $FormDesign->Data->y;
					include('includes/PO_PDFOrderPageHeader.inc');
				} //end if we reached the end of page
				$LeftOvers = $pdf->addTextWrap($FormDesign->Data->Column2->x, $YPos, $FormDesign->Data->Column2->Length, $FormDesign->Data->Column2->FontSize, $LeftOvers, 'left');
			} //end if need a new page headed up
			$LeftOvers = $pdf->addTextWrap($FormDesign->Data->Column3->x, $YPos, $FormDesign->Data->Column3->Length, $FormDesign->Data->Column3->FontSize, $DisplayQty, 'left');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Data->Column4->x, $YPos, $FormDesign->Data->Column4->Length, $FormDesign->Data->Column4->FontSize, $POLine['suppliersunit'], 'left');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Data->Column5->x, $YPos, $FormDesign->Data->Column5->Length, $FormDesign->Data->Column5->FontSize, $DisplayDelDate, 'left');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Data->Column6->x, $YPos, $FormDesign->Data->Column6->Length, $FormDesign->Data->Column6->FontSize, $DisplayPrice, 'right');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Data->Column7->x, $YPos, $FormDesign->Data->Column7->Length, $FormDesign->Data->Column7->FontSize, $DisplayLineTotal, 'right');
			if (mb_strlen($LeftOvers) > 1) {
				$LeftOvers = $pdf->addTextWrap($Left_Margin + 1 + 94, $YPos - $line_height, 270, $FontSize, $LeftOvers, 'left');
				$YPos -= $line_height;
			}
			if ($YPos - $line_height <= $Bottom_Margin) {
				/* We reached the end of the page so finsih off the page and start a newy */
				$PageNumber++;
				$YPos = $Page_Height - $FormDesign->Data->y;
				include('includes/PO_PDFOrderPageHeader.inc');
			} //end if need a new page headed up

			/*increment a line down for the next line item */
			$YPos -= $line_height;
			/* If we are previewing we want to stop showing order
			 * lines after the first one */
			if ($OrderNo == 'Preview') {
				$OrderNo = 'Preview_PurchaseOrder';
			} //$OrderNo == 'Preview'
		} //end while there are line items to print out
		if ($YPos - $line_height <= $Bottom_Margin) { // need to ensure space for totals
			$PageNumber++;
			include('includes/PO_PDFOrderPageHeader.inc');
		} //end if need a new page headed up
		if ($_POST['ShowAmounts'] == 'Yes') {
			$DisplayOrderTotal = locale_number_format($OrderTotal, $POHeader['currdecimalplaces']);
		} else {
			$DisplayOrderTotal = '----';
		}
		$pdf->addText($FormDesign->OrderTotalCaption->x, $Page_Height - $FormDesign->OrderTotalCaption->y, $FormDesign->OrderTotalCaption->FontSize, _('Order Total - excl tax') . ' ' . $POHeader['currcode']);
		$LeftOvers = $pdf->addTextWrap($FormDesign->OrderTotal->x, $Page_Height - $FormDesign->OrderTotal->y, $FormDesign->OrderTotal->Length, $FormDesign->OrderTotal->FontSize, $DisplayOrderTotal, 'right');
	} /*end if there are order details to show on the order - or its a preview*/

	$Success = 1; //assume the best and email goes - has to be set to 1 to allow update status
	if ($MakePDFThenDisplayIt) {
		$pdf->OutputD($_SESSION['DatabaseName'] . '_PurchaseOrder_' . $OrderNo . '_' . date('Y-m-d') . '.pdf');
		$pdf->__destruct();
	} else {
		/* must be MakingPDF to email it */

		$PdfFileName = $_SESSION['DatabaseName'] . '_PurchaseOrder_' . $OrderNo . '_' . date('Y-m-d') . '.pdf';
		$pdf->Output($_SESSION['reports_dir'] . '/' . $PdfFileName, 'F');
		$pdf->__destruct();
		include('includes/htmlMimeMail.php');
		$mail = new htmlMimeMail();
		$attachment = $mail->getFile($_SESSION['reports_dir'] . '/' . $PdfFileName);
		$mail->setText(_('Please find herewith our purchase order number') . ' ' . $OrderNo);
		$mail->setSubject(_('Purchase Order Number') . ' ' . $OrderNo);
		$mail->addAttachment($attachment, $PdfFileName, 'application/pdf');
		//since sometime the mail server required to verify the users, so must set this information.
		if($_SESSION['SmtpSetting'] == 0){//use the mail service provice by the server.
			$mail->setFrom($_SESSION['CompanyRecord']['coyname'] . '<' . $_SESSION['CompanyRecord']['email'] . '>');
			$Success = $mail->send(array($_POST['EmailTo']));
		}else if($_SESSION['SmtpSetting'] == 1) {
			$Success = SendmailBySmtp($mail,array($_POST['EmailTo']));

		}else{
			prnMsg(_('The SMTP settings are wrong, please ask administrator for help'),'error');
			exit;
			include('includes/footer.php');
		}

		if ($Success == 1) {
			$Title = _('Email a Purchase Order');
			include('includes/header.php');
			echo '<div class="centre"><br /><br /><br />';
			prnMsg(_('Purchase Order') . ' ' . $OrderNo . ' ' . _('has been emailed to') . ' ' . $_POST['EmailTo'] . ' ' . _('as directed'), 'success');

		} else { //email failed
			$Title = _('Email a Purchase Order');
			include('includes/header.php');
			echo '<div class="centre"><br /><br /><br />';
			prnMsg(_('Emailing Purchase order') . ' ' . $OrderNo . ' ' . _('to') . ' ' . $_POST['EmailTo'] . ' ' . _('failed'), 'error');
		}
	}
	if ($ViewingOnly == 0 AND $Success == 1) {
		$StatusComment = date($_SESSION['DefaultDateFormat']) . ' - ' . _('Printed by') . ' <a href="mailto:' . $_SESSION['UserEmail'] . '">' . $_SESSION['UsersRealName'] . '</a><br />' . html_entity_decode($POHeader['stat_comment'], ENT_QUOTES, 'UTF-8');

		$sql = "UPDATE purchorders	SET	allowprint =  0,
										dateprinted  = '" . Date('Y-m-d') . "',
										status = 'Printed',
										stat_comment = '" . htmlspecialchars($StatusComment, ENT_QUOTES, 'UTF-8') . "'
				WHERE purchorders.orderno = '" . $OrderNo . "'";
		$result = DB_query($sql);
	}
	include('includes/footer.php');
} //isset($MakePDFThenDisplayIt) OR isset($MakePDFThenEmailIt)

/* There was enough info to either print or email the purchase order */
else {
	/*the user has just gone into the page need to ask the question whether to print the order or email it to the supplier */
	include('includes/header.php');
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
	echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	if ($ViewingOnly == 1) {
		echo '<input type="hidden" name="ViewingOnly" value="1" />';
	} //$ViewingOnly == 1
	echo '<br /><br />';
	echo '<input type="hidden" name="OrderNo" value="' . $OrderNo . '" />';
	echo '<table>
         <tr>
             <td>' . _('Print or Email the Order') . '</td>
             <td><select name="PrintOrEmail">';

	if (!isset($_POST['PrintOrEmail'])) {
		$_POST['PrintOrEmail'] = 'Print';
	}
	if ($ViewingOnly != 0) {
		echo '<option selected="selected" value="Print">' . _('Print') . '</option>';
	}
	else {
		if ($_POST['PrintOrEmail'] == 'Print') {
			echo '<option selected="selected" value="Print">' . _('Print') . '</option>';
			echo '<option value="Email">' . _('Email') . '</option>';
		} else {
			echo '<option value="Print">' . _('Print') . '</option>';
			echo '<option selected="selected" value="Email">' . _('Email') . '</option>';
		}
	}
	echo '</select></td></tr>';
	echo '<tr><td>' . _('Show Amounts on the Order') . '</td><td>
		<select name="ShowAmounts">';
	if (!isset($_POST['ShowAmounts'])) {
		$_POST['ShowAmounts'] = 'Yes';
	}
	if ($_POST['ShowAmounts'] == 'Yes') {
		echo '<option selected="selected" value="Yes">' . _('Yes') . '</option>';
		echo '<option value="No">' . _('No') . '</option>';
	} else {
		echo '<option value="Yes">' . _('Yes') . '</option>';
		echo '<option selected="selected" value="No">' . _('No') . '</option>';
	}
	echo '</select></td></tr>';
	if ($_POST['PrintOrEmail'] == 'Email') {
		$ErrMsg = _('There was a problem retrieving the contact details for the supplier');
		$SQL = "SELECT suppliercontacts.contact,
						suppliercontacts.email
				FROM suppliercontacts INNER JOIN purchorders
				ON suppliercontacts.supplierid=purchorders.supplierno
				WHERE purchorders.orderno='" . $OrderNo . "'";
		$ContactsResult = DB_query($SQL, $ErrMsg);
		if (DB_num_rows($ContactsResult) > 0) {
			echo '<tr><td>' . _('Email to') . ':</td><td><select name="EmailTo">';
			while ($ContactDetails = DB_fetch_array($ContactsResult)) {
				if (mb_strlen($ContactDetails['email']) > 2 AND mb_strpos($ContactDetails['email'], '@') > 0) {
					if ($_POST['EmailTo'] == $ContactDetails['email']) {
						echo '<option selected="selected" value="' . $ContactDetails['email'] . '">' . $ContactDetails['Contact'] . ' - ' . $ContactDetails['email'] . '</option>';
					} else {
						echo '<option value="' . $ContactDetails['email'] . '">' . $ContactDetails['contact'] . ' - ' . $ContactDetails['email'] . '</option>';
					}
				}
			}
			echo '</select></td></tr></table>';
		} else {
			echo '</table><br />';
			prnMsg(_('There are no contacts defined for the supplier of this order') . '. ' . _('You must first set up supplier contacts before emailing an order'), 'error');
			echo '<br />';
		}
	} else {
		echo '</table>';
	}
	echo '<br />
         <div class="centre">
              <input type="submit" name="DoIt" value="' . _('OK') . '" />
         </div>
         </div>
         </form>';

	include('includes/footer.php');
}
?>

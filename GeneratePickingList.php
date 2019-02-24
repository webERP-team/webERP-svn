<?php
// GeneratePickingList.php
// Generate a picking list.

include('includes/session.php');
/* $Title is set in several parts of this script. */
$ViewTopic = 'Sales';
$BookMark = 'GeneratePickingList';
include('includes/SQL_CommonFunctions.inc');

/* Check that the config variable is set for picking notes and get out if not. */
if ($_SESSION['RequirePickingNote'] == 0) {
	$Title = _('Picking Lists Not Enabled');
	include('includes/header.php');
	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
		'/images/error.png" title="', // Icon image.
		$Title, '" /> ', // Icon title.
		$Title, '</p>';// Page title.
	echo '<br />';
	prnMsg(_('The system is not configured for picking lists. A configuration parameter is required where picking slips are required. Please consult your system administrator.'), 'info');
	/*prnMsg(_('The system is configured to NOT use picking lists. In order for a picking note to occur before an order can be delivered, a configuration parameter must be activated. Please, consult your system administrator.'), 'info');*/
	include('includes/footer.php');
	exit;
}

/* Show selection screen if we have no orders to work with */
if ((!isset($_GET['TransNo']) or $_GET['TransNo'] == '') and !isset($_POST['TransDate'])) {
	$Title = _('Select Picking Lists');
	include('includes/header.php');
	echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
		'/images/sales.png" title="', // Icon image.
		_('Search'), '" /> ', // Icon title.
		$Title, '</p>';// Page title.
	$SQL = "SELECT locations.loccode,
			locationname
		FROM locations
		INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" . $_SESSION['UserID'] . "' AND locationusers.canupd=1";
	$Result = DB_query($SQL);
	echo '<br /><form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" name="form">
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<table class="selection">
		<tr>
			<td>' . _('Create picking lists for all deliveries to be made on') . ' : ' . '</td>
			<td><input type="text" required="required" autofocus="autofocus" class="date" name="TransDate" maxlength="10" size="11" value="' . date($_SESSION['DefaultDateFormat'], mktime(date('m'), date('Y'), date('d') + 1)) . '" /></td>
		</tr>
		<tr>
			<td>' . _('From Warehouse') . ' : ' . '</td>
			<td><select required="required" name="loccode">';
	while ($MyRow = DB_fetch_array($Result)) {
		echo '<option value="' . $MyRow['loccode'] . '">' . $MyRow['locationname'] . '</option>';
	}
	echo '</select></td>
		</tr>
		</table>';
	echo '<div class="centre">
			<input type="submit" name="Process" value="' . _('Print Picking Lists') . '" />
		</div>
		</form>';
	include('includes/footer.php');
	exit();
}

/*retrieve the order details from the database to print */
$ErrMsg = _('There was a problem retrieving the order header details from the database');

if (!isset($_POST['TransDate']) and $_GET['TransNo'] != 'Preview') {
	/* If there is no transaction date set, then it must be for a single order */
	$SQL = "SELECT salesorders.debtorno,
					salesorders.orderno,
					salesorders.customerref,
					salesorders.comments,
					salesorders.orddate,
					salesorders.deliverto,
					salesorders.deladd1,
					salesorders.deladd2,
					salesorders.deladd3,
					salesorders.deladd4,
					salesorders.deladd5,
					salesorders.deladd6,
					salesorders.deliverblind,
					salesorders.deliverydate,
					debtorsmaster.name,
					debtorsmaster.address1,
					debtorsmaster.address2,
					debtorsmaster.address3,
					debtorsmaster.address4,
					debtorsmaster.address5,
					debtorsmaster.address6,
					shippers.shippername,
					salesorders.printedpackingslip,
					salesorders.datepackingslipprinted,
					locations.loccode,
					locations.locationname
				FROM salesorders INNER JOIN salesorderdetails on salesorderdetails.orderno=salesorders.orderno,
					debtorsmaster,
					shippers,
					locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" . $_SESSION['UserID'] . "' AND locationusers.canupd=1
				WHERE salesorders.debtorno=debtorsmaster.debtorno
					AND salesorders.shipvia=shippers.shipper_id
					AND salesorders.fromstkloc=locations.loccode
					AND salesorders.orderno='" . $_GET['TransNo'] . "'
					AND salesorderdetails.completed=0
				GROUP BY salesorders.orderno";
} else if (isset($_POST['TransDate']) or (isset($_GET['TransNo']) and $_GET['TransNo'] != 'Preview')) {
	/* We are printing picking lists for all orders on a day */
	$SQL = "SELECT salesorders.debtorno,
					salesorders.orderno,
					salesorders.customerref,
					salesorders.comments,
					salesorders.orddate,
					salesorders.deliverto,
					salesorders.deladd1,
					salesorders.deladd2,
					salesorders.deladd3,
					salesorders.deladd4,
					salesorders.deladd5,
					salesorders.deladd6,
					salesorders.deliverblind,
					salesorders.deliverydate,
					debtorsmaster.name,
					debtorsmaster.address1,
					debtorsmaster.address2,
					debtorsmaster.address3,
					debtorsmaster.address4,
					debtorsmaster.address5,
					debtorsmaster.address6,
					shippers.shippername,
					salesorders.printedpackingslip,
					salesorders.datepackingslipprinted,
					locations.loccode,
					locations.locationname
				FROM salesorders INNER JOIN salesorderdetails on salesorderdetails.orderno=salesorders.orderno,
					debtorsmaster,
					shippers,
					locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" . $_SESSION['UserID'] . "' AND locationusers.canupd=1
				WHERE salesorders.debtorno=debtorsmaster.debtorno
					AND salesorders.shipvia=shippers.shipper_id
					AND salesorders.fromstkloc=locations.loccode
					AND salesorders.fromstkloc='" . $_POST['loccode'] . "'
					AND salesorders.deliverydate<='" . FormatDateForSQL($_POST['TransDate']) . "'
					AND salesorderdetails.completed=0
				GROUP BY salesorders.orderno
				ORDER BY salesorders.deliverydate, salesorders.orderno";
}

if ($_SESSION['SalesmanLogin'] != '') {
	$SQL .= " AND salesorders.salesperson='" . $_SESSION['SalesmanLogin'] . "'";
}

if (isset($_POST['TransDate']) or (isset($_GET['TransNo']) and $_GET['TransNo'] != 'Preview')) {
	$Result = DB_query($SQL, $ErrMsg);

	/*if there are no rows, there's a problem. */
	if (DB_num_rows($Result) == 0) {
		$Title = _('Print Picking List Error');
		include('includes/header.php');
		echo '<br />';
		prnMsg(_('Unable to Locate any orders for this criteria '), 'info');
		echo '<br />
			<table class="selection">
			<tr>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '">' . _('Enter Another Date') . '</a></td>
			</tr>
			</table>
			<br />';
		include('includes/footer.php');
		exit();
	}

	/*retrieve the order details from the database and place them in an array */
	while ($MyRow = DB_fetch_array($Result)) {
		$OrdersToPick[] = $MyRow;
	}
}
else {
	$OrdersToPick[0]['debtorno'] = str_pad('', 10, 'x');
	$OrdersToPick[0]['orderno'] = 'Preview';
	$OrdersToPick[0]['customerref'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['comments'] = str_pad('', 100, 'x');
	$OrdersToPick[0]['orddate'] = '1900-00-01';
	$OrdersToPick[0]['deliverto'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['deladd1'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['deladd2'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['deladd3'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['deladd4'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['deladd5'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['deladd6'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['deliverblind'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['deliverydate'] = '1900-00-01';
	$OrdersToPick[0]['name'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['address1'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['address2'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['address3'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['address4'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['address5'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['address6'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['shippername'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['printedpackingslip'] = str_pad('', 20, 'x');
	$OrdersToPick[0]['datepackingslipprinted'] = '1900-00-01';
	$OrdersToPick[0]['locationname'] = str_pad('', 15, 'x');
}
/* Then there's an order to print and its not been printed already (or its been flagged for reprinting/ge_Width=807;)
LETS GO */

if ($OrdersToPick[0]['orderno'] == 'Preview') {
	$FormDesign = simplexml_load_file(sys_get_temp_dir() . '/PickingList.xml');
} else {
	$FormDesign = simplexml_load_file($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/PickingList.xml');
}

$PaperSize = $FormDesign->PaperSize;
include('includes/PDFStarter.php');
$pdf->addInfo('Title', _('Picking List'));
$pdf->addInfo('Subject', _('Laser Picking List'));
$FontSize = 12;
$ListCount = 0;
$Copy = '';

$line_height = $FormDesign->LineHeight;
$TotalOrderCount = sizeof($OrdersToPick);

for ( $i = 0; $i < $TotalOrderCount; $i++ ){
	/*Cycle through each of the orders to pick */
	if ($i > 0) {
		$pdf->newPage();
	}

	/* Now ... Has the order got any line items still outstanding to be picked */

	$PageNumber = 1;

	if (isset($_POST['TransDate']) or (isset($_GET['TransNo']) and $_GET['TransNo'] != 'Preview')) {
		$ErrMsg = _('There was a problem retrieving the order line details for Order Number') . ' ' . $OrdersToPick[$i]['orderno'] . ' ' . _('from the database');

		/* Are there any picking lists for this order already */
		$SQL = "SELECT COUNT(orderno),
						prid,
						comments
				FROM pickreq
				WHERE orderno='" . $OrdersToPick[$i]['orderno'] . "'
					AND closed='0'";

		$CountResult = DB_query($SQL);
		$Count = DB_fetch_row($CountResult);

		if (!isset($Count[2]) or $Count[2] == '') { /* No comment was found in the query */
			$Count[2]='Please pick order. Generate packing slip. Apply shipment labels and ship in system.';
		}

		if ($Count[0] == 0) {
			$SQL = "SELECT salesorderdetails.stkcode,
							stockmaster.description,
							stockmaster.controlled,
							stockmaster.serialised,
							salesorderdetails.orderlineno,
							(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) as qtyexpected,
							salesorderdetails.quantity,
							salesorderdetails.qtyinvoiced,
							salesorderdetails.narrative,
							stockmaster.decimalplaces,
							custitem.cust_part,
							custitem.cust_description,
							locstock.quantity qtyavail,
							bin
						FROM salesorderdetails
						INNER JOIN locstock
							ON locstock.loccode='" . $OrdersToPick[$i]['loccode'] . "'
							AND locstock.stockid=salesorderdetails.stkcode
						INNER JOIN stockmaster
							ON salesorderdetails.stkcode=stockmaster.stockid
						LEFT OUTER JOIN custitem
							ON custitem.debtorno='" . $OrdersToPick[$i]['debtorno'] . "'
							AND custitem.stockid=stockmaster.stockid
						WHERE salesorderdetails.orderno='" . $OrdersToPick[$i]['orderno'] . "'
						AND salesorderdetails.completed=0";
		} else {
			$SQL = "SELECT salesorderdetails.stkcode,
							stockmaster.description,
							stockmaster.controlled,
							stockmaster.serialised,
							salesorderdetails.orderlineno,
							(salesorderdetails.quantity - salesorderdetails.qtyinvoiced) as qtyexpected,
							salesorderdetails.quantity,
							salesorderdetails.qtyinvoiced,
							pickreqdetails.qtypicked,
							pickreqdetails.shipqty,
							salesorderdetails.narrative,
							stockmaster.decimalplaces,
							custitem.cust_part,
							custitem.cust_description,
							locstock.quantity qtyavail,
							bin
						FROM salesorderdetails
						INNER JOIN locstock
							ON locstock.loccode='" . $OrdersToPick[$i]['loccode'] . "'
							AND locstock.stockid=salesorderdetails.stkcode
						INNER JOIN stockmaster
							ON salesorderdetails.stkcode=stockmaster.stockid
						LEFT OUTER JOIN pickreq
							ON pickreq.orderno=salesorderdetails.orderno
							AND pickreq.closed=0
						LEFT OUTER JOIN pickreqdetails
							ON pickreqdetails.stockid=salesorderdetails.stkcode
							AND pickreqdetails.orderlineno=salesorderdetails.orderlineno
							AND pickreqdetails.prid=pickreq.prid
						LEFT OUTER JOIN custitem
							ON custitem.debtorno='" . $OrdersToPick[$i]['debtorno'] . "'
							AND custitem.stockid=stockmaster.stockid
						WHERE salesorderdetails.orderno='" . $OrdersToPick[$i]['orderno'] . "'
						AND salesorderdetails.completed=0";
		}
		$LineResult = DB_query($SQL, $ErrMsg);
	}
	if ((isset($_GET['TransNo']) and $_GET['TransNo'] == 'Preview') or (isset($LineResult) and DB_num_rows($LineResult) > 0)) {
		/*Yes there are line items to start the ball rolling with a page header */
		DB_Txn_Begin();

		if (isset($_POST['TransDate']) or (isset($_GET['TransNo']) and $_GET['TransNo'] != 'Preview')) {
			$LinesToShow = DB_num_rows($LineResult);
			if ($Count[0] == 0) {
				/*create picklist we have open lines and no pickreq yet*/

				$SQL = "INSERT INTO pickreq
							(prid,
							initiator,
							initdate,
							requestdate,
							status,
							comments,
							loccode,
							orderno)
						VALUES (
							'NULL',
							'" . $_SESSION['UserID'] . "',
							'" . date('Y-m-d') . "',
							'" . $OrdersToPick[$i]['deliverydate'] . "',
							'New',
							'Please pick order. Generate packing slip. Apply shipment labels and ship in system.  Return all Paperwork to MemberSupport@resmart.com',
							'" . $OrdersToPick[$i]['loccode'] . "',
							'" . $OrdersToPick[$i]['orderno'] . "');";
				$HeaderResult = DB_query($SQL);
				$PickReqID = DB_Last_Insert_ID('pickreq', 'prid');
				$Count[1]=$PickReqID;
			} //create pickreq
		}
		else {
			$LinesToShow = 1;
		}

		include('includes/GenPickingListHeader.inc');
		$YPos = $FormDesign->Data->y;
		$Lines = 0;

		while ($Lines < $LinesToShow) {
			if (isset($_GET['TransNo']) and $_GET['TransNo'] == 'Preview') {
				$MyRow2['stkcode'] = str_pad('', 10, 'x');
				$MyRow2['decimalplaces'] = 2;
				$DisplayQty = 'XXXX.XX';
				$DisplayPrevDel = 'XXXX.XX';
				$DisplayQtySupplied = 'XXXX.XX';
				$MyRow2['description'] = str_pad('', 18, 'x');
				$MyRow2['narrative'] = str_pad('', 18, 'x');
			}
			else {
				$MyRow2 = DB_fetch_array($LineResult);

				if ($Count[0] == 0) {
					$SQL = "INSERT INTO pickreqdetails
								(detailno,
								prid,
								orderlineno,
								stockid,
								qtyexpected)
							VALUES (
								'NULL',
								'" . $PickReqID . "',
								'" . $MyRow2['orderlineno'] . "',
								'" . $MyRow2['stkcode'] . "',
								'" . $MyRow2['qtyexpected'] . "');";

					$InsLineResult = DB_query($SQL);
					$MyRow2['qtyexpected'] = 0;
					$MyRow2['qtypicked'] = 0;
				} //create pickreqdetail

				$DisplayQty = locale_number_format($MyRow2['quantity'], $MyRow2['decimalplaces']);
				$DisplayQtySupplied = locale_number_format($MyRow2['quantity'] - $MyRow2['qtyinvoiced'], $MyRow2['decimalplaces']);
				$DisplayPrevDel = locale_number_format($MyRow2['qtyinvoiced'], $MyRow2['decimalplaces']);
				$DisplayQtyAvail = locale_number_format($MyRow2['qtyavail'], $MyRow2['decimalplaces']);

				if ($MyRow2['qtypicked'] > 0) {
					$DisplayPicked = locale_number_format($MyRow2['qtypicked'], $MyRow2['decimalplaces']);
				} else {
					$DisplayPicked = '____________';
				}
			}
			++$ListCount;

			$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column1->x, $Page_Height - $YPos, $FormDesign->Headings->Column1->Length, $FormDesign->Headings->Column1->FontSize, $MyRow2['stkcode'], 'left');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column2->x, $Page_Height - $YPos, $FormDesign->Headings->Column2->Length, $FormDesign->Headings->Column2->FontSize, $MyRow2['description']);
			$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column3->x, $Page_Height - $YPos, $FormDesign->Headings->Column3->Length, $FormDesign->Headings->Column3->FontSize, $MyRow2['bin'], 'right');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column4->x, $Page_Height - $YPos, $FormDesign->Headings->Column4->Length, $FormDesign->Headings->Column4->FontSize, $DisplayQtySupplied, 'right');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column5->x, $Page_Height - $YPos, $FormDesign->Headings->Column5->Length, $FormDesign->Headings->Column5->FontSize, $DisplayQtyAvail, 'right');
			$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column6->x, $Page_Height - $YPos, $FormDesign->Headings->Column6->Length, $FormDesign->Headings->Column6->FontSize, $DisplayPicked, 'right');

			if ($Page_Height - $YPos - $line_height <= 60) {
				/* We reached the end of the page so finish off the page and start a new */
				$PageNumber++;
				include ('includes/GenPickingListHeader.inc');
			} //end if need a new page headed up
			else {
				/*increment a line down for the next line item */
				$YPos += ($line_height);
			}

			if ($MyRow2['cust_part'] > '') {
				$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column2->x, $Page_Height - $YPos, $FormDesign->Headings->Column2->Length, $FormDesign->Headings->Column2->FontSize, $MyRow2['cust_part'] . ' ' . $MyRow2['cust_description']);

				if ($Page_Height - $YPos - $line_height <= 60) {
					/* We reached the end of the page so finish off the page and start a new */
					$PageNumber++;
					include ('includes/GenPickingListHeader.inc');
				} //end if need a new page headed up
				else {
					/*increment a line down for the next line item */
					$YPos += ($line_height);
				}
			}

			if ($MyRow2['narrative'] > '') {
				$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column2->x, $Page_Height - $YPos, $FormDesign->Headings->Column2->Length, $FormDesign->Headings->Column2->FontSize, $MyRow2['narrative']);
				if ($Page_Height - $YPos - $line_height <= 60) {
					/* We reached the end of the page so finish off the page and start a new */
					$PageNumber++;
					include ('includes/GenPickingListHeader.inc');
				} //end if need a new page headed up
				else {
					/*increment a line down for the next line item */
					$YPos += ($line_height);
				}
			}

			if ($MyRow2['controlled'] == 1) {
				if ($MyRow2['serialised'] == 1) {
/*					$BundleLabel = _('Serial#:');*/
					$BundleLabel = _('Serial number') . ':';
				}
				else {
/*					$BundleLabel = _('Lot#:');*/
					$BundleLabel = _('Lot Number') . ':';
/*					$BundleLabel = _('Lot number') . ':';*/
				}
				$SQL = "SELECT serialno,
								quantity,
								(SELECT SUM(moveqty)
									FROM pickserialdetails
									INNER JOIN pickreqdetails on pickreqdetails.detailno=pickserialdetails.detailno
									INNER JOIN pickreq on pickreq.prid=pickreqdetails.prid
									AND pickreq.closed=0
									WHERE pickserialdetails.serialno=stockserialitems.serialno
									AND pickserialdetails.stockid=stockserialitems.stockid) as qtypickedtotal,
								(SELECT SUM(moveqty)
									FROM pickserialdetails
									INNER JOIN pickreqdetails on pickreqdetails.detailno=pickserialdetails.detailno
									INNER JOIN pickreq on pickreq.prid=pickreqdetails.prid
									AND pickreq.orderno='" . $OrdersToPick[$i]['orderno'] . "'
									AND pickreq.closed=0
									WHERE pickserialdetails.serialno=stockserialitems.serialno
									AND pickserialdetails.stockid=stockserialitems.stockid) as qtypickedthisorder
						FROM stockserialitems
						WHERE stockid='" . $MyRow2['stkcode'] . "'
						AND stockserialitems.loccode ='" . $OrdersToPick[$i]['loccode'] . "'
						AND quantity > 0
						ORDER BY createdate, quantity";

				$ErrMsg = '<br />' . _('Could not retrieve the items for') . ' ' . $MyRow2['stkcode'];
				$Bundles = DB_query($SQL, $ErrMsg);
				$YPos += ($line_height);

				while ($mybundles = DB_fetch_array($Bundles)) {
					if ($mybundles['qtypickedthisorder'] == 0 or is_null($mybundles['qtypickedthisorder'])) {
						$mybundles['qtypickedthisorder'] = '____________';
					}

					$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column3->x, $Page_Height - $YPos, $FormDesign->Headings->Column3->Length, $FormDesign->Headings->Column3->FontSize, $BundleLabel, 'right');
					$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column4->x, $Page_Height - $YPos, $FormDesign->Headings->Column4->Length, $FormDesign->Headings->Column4->FontSize, $mybundles['serialno'], 'left');
					$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column5->x, $Page_Height - $YPos, $FormDesign->Headings->Column5->Length, $FormDesign->Headings->Column5->FontSize, $mybundles['quantity'] - $mybundles['qtypickedtotal'], 'right');
					$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column6->x, $Page_Height - $YPos, $FormDesign->Headings->Column6->Length, $FormDesign->Headings->Column6->FontSize, $mybundles['qtypickedthisorder'], 'right');

					if ($Page_Height - $YPos - $line_height <= 60) {
						/* We reached the end of the page so finish off the page and start a new */
						$PageNumber++;
						include ('includes/GenPickingListHeader.inc');
					} //end if need a new page headed up
					else {
						/*increment a line down for the next line item */
						$YPos += ($line_height);
					}
				} //while
			} //controlled

			++$Lines;
			$YPos += ($line_height);
		} //end while there are line items to print out

		$YPos = $Page_Height - 45;
		$pdf->setFont('', 'B');
		$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column2->x, $Page_Height - $YPos, $FormDesign->Headings->Column2->Length, $FormDesign->Headings->Column2->FontSize, _('Signed for') . ': ______________________________');
		$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column3->x, $Page_Height - $YPos, $FormDesign->Headings->Column3->Length, $FormDesign->Headings->Column3->FontSize, _('Date') . ' : __________');
		$pdf->setFont('', '');
	} /*end if there are order details to show on the order*/
} /*end for loop to print the whole lot twice */

if ($ListCount == 0) {
	$Title = _('Print Picking List Error');
	include('includes/header.php');
	prnMsg( _('There are no picking lists to print'), 'error');
	include('includes/footer.php');
	exit;
} else {
	$pdf->OutputD($_SESSION['DatabaseName'] . '_PickingLists_' . date('Y-m-d') . '.pdf');
	$pdf->__destruct();
	DB_Txn_Commit();
}
?>

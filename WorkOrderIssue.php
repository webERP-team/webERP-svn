<?php
include ('includes/session.php');
$Title = _('Issue Materials To Work Order');
include ('includes/header.php');
include ('includes/SQL_CommonFunctions.inc');

if (isset($_GET['WO'])) {
	$_POST['WO'] = $_GET['WO'];
}
if (isset($_GET['StockID'])) {
	$_POST['StockID'] = $_GET['StockID'];
}

echo '<a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Back to Work Orders') . '</a>
	<br />';
echo '<a href="' . $RootPath . '/WorkOrderCosting.php?WO=' . $_POST['WO'] . '">' . _('Back to Costing') . '</a>
	<br />';

echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/group_add.png" title="' . _('Search') . '" alt="" />' . ' ' . $Title . '</p>';

echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($_POST['WO']) or !isset($_POST['StockID'])) {
	/* This page can only be called with a work order number for issuing stock to*/
	echo '<div class="centre"><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select a work order to issue materials to') . '</a></div>';
	prnMsg(_('This page can only be opened if a work order has been selected. Please select a work order to issue materials to first'), 'info');
	include ('includes/footer.php');
	exit;
} else {
	echo '<input type="hidden" name="WO" value="' . $_POST['WO'] . '" />';
	echo '<input type="hidden" name="StockID" value="' . $_POST['StockID'] . '" />';
}
if (isset($_GET['IssueItem'])) {
	$_POST['IssueItem'] = $_GET['IssueItem'];
}
if (isset($_GET['FromLocation'])) {
	$_POST['FromLocation'] = $_GET['FromLocation'];
}

if (isset($_POST['Process'])) { //user hit the process the work order issues entered.
	$InputError = false; //ie assume no problems for a start - ever the optomist
	$ErrMsg = _('Could not retrieve the details of the selected work order item');
	$WOResult = DB_query("SELECT workorders.loccode,
								 locations.locationname,
								 workorders.closed,
								 stockcategory.wipact,
								 stockcategory.stockact
							FROM workorders INNER JOIN locations
							ON workorders.loccode=locations.loccode
							INNER JOIN woitems
							ON workorders.wo=woitems.wo
							INNER JOIN stockmaster
							ON woitems.stockid=stockmaster.stockid
							INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
							WHERE woitems.stockid='" . $_POST['StockID'] . "'
							AND woitems.wo='" . $_POST['WO'] . "'", $ErrMsg);

	if (DB_num_rows($WOResult) == 0) {
		prnMsg(_('The selected work order item cannot be retrieved from the database'), 'info');
		include ('includes/footer.php');
		exit;
	}
	$WORow = DB_fetch_array($WOResult);

	if ($WORow['closed'] == 1) {
		prnMsg(_('The work order is closed - no more materials or components can be issued to it.'), 'error');
		$InputError = true;
	}
	//Need to get the current standard cost for the item being issued
	$SQL = "SELECT materialcost+labourcost+overheadcost AS cost,
					controlled,
					serialised,
					decimalplaces,
					mbflag
			FROM stockmaster
			WHERE stockid='" . $_POST['IssueItem'] . "'";
	$Result = DB_query($SQL);
	$IssueItemRow = DB_fetch_array($Result);
	//now lets get the decimalplaces needed
	if ($IssueItemRow['decimalplaces'] <= 3) {
		$VarianceAllowed = 0.0001;
	} else {
		$VarianceAllowed = pow(10, -(1 + $IssueItemRow['decimalplaces']));
	}

	$QuantityIssued = 0;
	if (isset($_POST['SerialNos']) and is_array($_POST['SerialNos'])) { //then we are issuing a serialised item
		$QuantityIssued = count($_POST['SerialNos']); // the total quantity issued as 1 per serial no
		
	} elseif (isset($_POST['Qty'])) { //then its a plain non-controlled item
		$QuantityIssued = filter_number_format($_POST['Qty']);
	} else { //it must be a batch/lot controlled item
		if (!isset($_POST['LotCounter']) or !is_numeric($_POST['LotCounter'])) {
			$InputError = true;
			prnMsg(_('The line counter is not set up or not numeric, please ask administrator for help'), 'error');
			include ('include/footer.php');
			exit;
		}
		for ($i = 0;$i < $_POST['LotCounter'];$i++) {
			if (mb_strlen($_POST['Qty' . $i]) > 0 and $_POST['Qty' . $i] != 0) {
				if (!is_numeric(filter_number_format($_POST['Qty' . $i]))) {
					$InputError = 1;
				} else {
					$QuantityIssued+= filter_number_format($_POST['Qty' . $i]);

					if ($_SESSION['ProhibitNegativeStock'] == 1 and $_POST['BatchRef' . $i] > "" and $_POST['Qty' . $i] > 0) {
						$SQL = "SELECT quantity from stockserialitems WHERE (stockid= '" . $_POST['IssueItem'] . "')
										AND (loccode = '" . $_POST['FromLocation'] . "')
										AND (serialno = '" . $_POST['BatchRef' . $i] . "')";
						$Result = DB_query($SQL);
						//$CheckLot = DB_fetch_array($Result);
						if (DB_num_rows($Result) == 0) {
							$InputError = true;
							prnMsg(_('This issue cannot be processed because the system parameter is set to prohibit negative stock and this batch does not exist'), 'error');
						} else {
							$CheckLotRow = DB_fetch_row($Result);
							if (($_POST['Qty' . $i] - $CheckLotRow[0]) > $VarianceAllowed) {
								$InputError = true;
								prnMsg(_('This issue cannot be processed because the system parameter is set to prohibit negative stock and this issue would result in this batch going into negative. Please correct the stock first before attempting another issue'), 'error');
							}
						}
					}

				} //end if the qty field is numeric
				
			} // end if the qty field is entered
			
		} //end for the 15 fields available for batch/lot entry
		
	} //end batch/lot controlled item
	

	if ($IssueItemRow['cost'] == 0) {
		prnMsg(_('The item being issued has a zero cost. The issue will still be processed '), 'warn');
	}

	if ($_SESSION['ProhibitNegativeStock'] == 1 and ($IssueItemRow['mbflag'] == 'M' or $IssueItemRow['mbflag'] == 'B')) {
		//don't need to check labour or dummy items
		$SQL = "SELECT quantity FROM locstock
				WHERE stockid ='" . $_POST['IssueItem'] . "'
				AND loccode ='" . $_POST['FromLocation'] . "'";
		$CheckNegResult = DB_query($SQL);
		$CheckNegRow = DB_fetch_row($CheckNegResult);
		if (($QuantityIssued - $CheckNegRow[0]) > $VarianceAllowed) {
			$InputError = true;
			prnMsg(_('This issue cannot be processed because the system parameter is set to prohibit negative stock and this issue would result in stock going into negative. Please correct the stock first before attempting another issue'), 'error');
		}
	}

	if ($InputError == false) {

		/************************ BEGIN SQL TRANSACTIONS ************************/

		$Result = DB_Txn_Begin();
		/*Now Get the next WO Issue transaction type 28 - function in SQL_CommonFunctions*/
		$WOIssueNo = GetNextTransNo(28);

		$PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat'])); //backdate
		$SQLIssuedDate = FormatDateForSQL($_POST['IssuedDate']);
		$StockGLCode = GetStockGLCode($_POST['IssueItem']);

		if ($IssueItemRow['mbflag'] == 'M' or $IssueItemRow['mbflag'] == 'B') {
			/* Need to get the current location quantity will need it later for the stock movement */
			$SQL = "SELECT locstock.quantity
				FROM locstock
				WHERE locstock.stockid='" . $_POST['IssueItem'] . "'
				AND loccode= '" . $_POST['FromLocation'] . "'";

			$Result = DB_query($SQL);
			if (DB_num_rows($Result) == 1) {
				$LocQtyRow = DB_fetch_row($Result);
				$NewQtyOnHand = ($LocQtyRow[0] - $QuantityIssued);
				if ($NewQtyOnHand < $VarianceAllowed) {
					$NewQtyOnHand = 0;
				}
			} else {
				/*There must actually be some error this should never happen */
				$NewQtyOnHand = 0;
			}

			$SQL = "UPDATE locstock
					SET quantity = locstock.quantity - " . $QuantityIssued . "
					WHERE locstock.stockid = '" . $_POST['IssueItem'] . "'
					AND loccode = '" . $_POST['FromLocation'] . "'";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
			$DbgMsg = _('The following SQL to update the location stock record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
		} else {
			$NewQtyOnHand = 0; //since we can't have stock of labour type items!!
			
		}
		/*Insert stock movements - with unit cost */

		$SQL = "INSERT INTO stockmoves (stockid,
						type,
						transno,
						loccode,
						trandate,
						userid,
						price,
						prd,
						reference,
						qty,
						standardcost,
						newqoh)
					VALUES ('" . $_POST['IssueItem'] . "',
							28,
							'" . $WOIssueNo . "',
							'" . $_POST['FromLocation'] . "',
							'" . FormatDateForSQL($_POST['IssuedDate']) . "',
							'" . $_SESSION['UserID'] . "',
							'" . $IssueItemRow['cost'] . "',
							'" . $PeriodNo . "',
							'" . $_POST['WO'] . "',
							'" . -$QuantityIssued . "',
							'" . $IssueItemRow['cost'] . "',
							'" . $NewQtyOnHand . "')";

		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted when processing the work order issue because');
		$DbgMsg = _('The following SQL to insert the stock movement records was used');
		$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

		/*Get the ID of the StockMove... */
		$StkMoveNo = DB_Last_Insert_ID('stockmoves', 'stkmoveno');
		/* Do the Controlled Item INSERTS HERE */

		if ($IssueItemRow['controlled'] == 1) {
			//the form is different for serialised items and just batch/lot controlled items
			if ($IssueItemRow['serialised'] == 1) {
				//serialised items form has multi select box of serial numbers that contains all the available serial numbers at the location selected
				foreach ($_POST['SerialNos'] as $SerialNo) {
					/*  We need to add the StockSerialItem record and
					 The StockSerialMoves as well */
					//need to test if the serialised item exists first already
					if (trim($SerialNo) != "") {

						$SQL = "UPDATE stockserialitems set quantity=0
										WHERE (stockid= '" . $_POST['IssueItem'] . "')
										AND (loccode = '" . $_POST['FromLocation'] . "')
										AND (serialno = '" . $SerialNo . "')";
						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock item record could not be inserted because');
						$DbgMsg = _('The following SQL to insert the serial stock item records was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

						/** end of handle stockserialitems records */

						/* now insert the serial stock movement */
						$SQL = "INSERT INTO stockserialmoves (stockmoveno,
																stockid,
																serialno,
																moveqty)
									VALUES ('" . $StkMoveNo . "',
											'" . $_POST['IssueItem'] . "',
											'" . $SerialNo . "',
											-1)";
						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
						$DbgMsg = _('The following SQL to insert the serial stock movement records was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
					} //non blank SerialNo
					
				} //end for all of the potential serialised entries in the multi select box
				
			} else { //the item is just batch/lot controlled not serialised
				/*the form for entry of batch controlled items is only 15 possible fields */
				for ($i = 0;$i < $_POST['LotCounter'];$i++) {
					/*  We need to add the StockSerialItem record and
					 The StockSerialMoves as well */
					//need to test if the batch/lot exists first already
					if (trim($_POST['BatchRef' . $i]) != "") {

						$SQL = "SELECT COUNT(*) FROM stockserialitems
								WHERE stockid='" . $_POST['IssueItem'] . "'
								AND loccode = '" . $_POST['FromLocation'] . "'
								AND serialno = '" . $_POST['BatchRef' . $i] . "'";
						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not check if a batch/lot reference for the item already exists because');
						$DbgMsg = _('The following SQL to test for an already existing controlled item was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
						$AlreadyExistsRow = DB_fetch_row($Result);

						if ($AlreadyExistsRow[0] > 0 and $_POST['Qty' . $i] != 0) {
							$SQL = "UPDATE stockserialitems SET quantity = CASE
												WHEN abs(quantity -" . $_POST['Qty' . $i] . ")<" . $VarianceAllowed . "
												THEN 0
												ELSE  quantity - " . $_POST['Qty' . $i] . "
												END
										WHERE stockid='" . $_POST['IssueItem'] . "'
										AND loccode = '" . $_POST['FromLocation'] . "'
										AND serialno = '" . $_POST['BatchRef' . $i] . "'";
						} elseif ($_POST['Qty' . $i] != 0) {
							$SQL = "INSERT INTO stockserialitems (stockid,
												loccode,
												serialno,
												qualitytext,
												quantity)
												VALUES ('" . $_POST['IssueItem'] . "',
												'" . $_POST['FromLocation'] . "',
												'" . $_POST['BatchRef' . $i] . "',
												'',
												'" . -(filter_number_format($_POST['Qty' . $i])) . "')";
						}

						$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The batch/lot item record could not be inserted because');
						$DbgMsg = _('The following SQL to insert the batch/lot item records was used');
						$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

						/** end of handle stockserialitems records */

						/** now insert the serial stock movement **/
						if ($_POST['Qty' . $i] != 0) {
							$SQL = "INSERT INTO stockserialmoves (stockmoveno,
											stockid,
											serialno,
											moveqty)
									VALUES ('" . $StkMoveNo . "',
											'" . $_POST['IssueItem'] . "',
											'" . $_POST['BatchRef' . $i] . "',
											'" . filter_number_format($_POST['Qty' . $i]) * -1 . "')";
							$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The serial stock movement record could not be inserted because');
							$DbgMsg = _('The following SQL to insert the serial stock movement records was used');
							$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
						}
					} //non blank BundleRef
					
				} //end for all 15 of the potential batch/lot fields received
				
			} //end of the batch controlled stuff
			
		} //end if the woitem received here is a controlled item
		

		if ($_SESSION['CompanyRecord']['gllink_stock'] == 1) {
			/*GL integration with stock is activated so need the GL journals to make it so */

			/*first the debit the WIP of the item being manufactured from the WO
			 the appropriate account was already retrieved into the $StockGLCode variable as the Processing code is kicked off
			 it is retrieved from the stock category record of the item by a function in SQL_CommonFunctions.inc*/

			$SQL = "INSERT INTO gltrans (type,
							typeno,
							trandate,
							periodno,
							account,
							narrative,
							amount)
					VALUES (28,
						'" . $WOIssueNo . "',
						'" . FormatDateForSQL($_POST['IssuedDate']) . "',
						'" . $PeriodNo . "',
						'" . $WORow['wipact'] . "',
						'" . $_POST['WO'] . " " . $_POST['IssueItem'] . ' x ' . $QuantityIssued . " @ " . locale_number_format($IssueItemRow['cost'], $_SESSION['CompanyRecord']['decimalplaces']) . "',
						'" . ($IssueItemRow['cost'] * $QuantityIssued) . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The issue of the item to the work order GL posting could not be inserted because');
			$DbgMsg = _('The following SQL to insert the work order issue GLTrans record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			/*now the credit Stock entry*/
			$SQL = "INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
					VALUES (28,
						'" . $WOIssueNo . "',
						'" . FormatDateForSQL($_POST['IssuedDate']) . "',
						'" . $PeriodNo . "',
						'" . $StockGLCode['stockact'] . "',
						'" . $_POST['WO'] . " " . $_POST['IssueItem'] . ' x ' . $QuantityIssued . " @ " . locale_number_format($IssueItemRow['cost'], $_SESSION['CompanyRecord']['decimalplaces']) . "',
						'" . -($IssueItemRow['cost'] * $QuantityIssued) . "')";

			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock account credit on the issue of items to a work order GL posting could not be inserted because');
			$DbgMsg = _('The following SQL to insert the stock GLTrans record was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

		} /* end of if GL and stock integrated and standard cost !=0 */

		//update the wo with the new qtyrecd
		$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not update the work order cost issued to the work order because');
		$DbgMsg = _('The following SQL was used to update the work order');
		$UpdateWOResult = DB_query("UPDATE workorders
									SET costissued=costissued+" . ($QuantityIssued * $IssueItemRow['cost']) . "
									WHERE wo='" . $_POST['WO'] . "'", $ErrMsg, $DbgMsg, true);

		$Result = DB_Txn_Commit();

		prnMsg(_('The issue of') . ' ' . $QuantityIssued . ' ' . _('of') . ' ' . $_POST['IssueItem'] . ' ' . _('against work order') . ' ' . $_POST['WO'] . ' ' . _('has been processed'), 'info');
		echo '<p><ul><li><a href="' . $RootPath . '/WorkOrderIssue.php?WO=' . $_POST['WO'] . '&amp;StockID=' . $_POST['StockID'] . '">' . _('Issue more components to this work order') . '</a></li>';
		echo '<li><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select a different work order for issuing materials and components against') . '</a></li></ul>';
		unset($_POST['WO']);
		unset($_POST['StockID']);
		unset($_POST['IssueItem']);
		unset($_POST['FromLocation']);
		unset($_POST['Process']);
		unset($_POST['SerialNos']);
		for ($i = 0;$i < $_POST['LotCounter'];$i++) {
			unset($_POST['BatchRef' . $i]);
			unset($_POST['Qty' . $i]);
		}
		unset($_POST['Qty']);
		/*end of process work order issues entry */
		include ('includes/footer.php');
		exit;
	} //end if there were not input errors reported - so the processing was allowed to continue
	
} //end of if the user hit the process button
elseif (isset($_POST['ProcessMultiple'])) {
	$IssueItems = array();
	foreach ($_POST as $key => $value) {
		if (strpos($key, 'IssueQty') !== false and abs(filter_number_format($value)) > 0) {
			$No = substr($key, 8);
			$InputError = false; //ie assume no problems for a start - ever the optomist
			$ErrMsg = _('Could not retrieve the details of the selected work order item');
			$WOResult = DB_query("SELECT workorders.loccode,
								 locations.locationname,
								 workorders.closed,
								 stockcategory.wipact,
								 stockcategory.stockact
							FROM workorders INNER JOIN locations
							ON workorders.loccode=locations.loccode
							INNER JOIN woitems
							ON workorders.wo=woitems.wo
							INNER JOIN stockmaster
							ON woitems.stockid=stockmaster.stockid
							INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
							WHERE woitems.stockid='" . $_POST['StockID'] . "'
							AND woitems.wo='" . $_POST['WO'] . "'", $ErrMsg);

			if (DB_num_rows($WOResult) == 0) {
				prnMsg(_('The selected work order item cannot be retrieved from the database'), 'info');
				include ('includes/footer.php');
				exit;
			}
			$WORow = DB_fetch_array($WOResult);
			if ($WORow['closed'] == 1) {
				prnMsg(_('The work order is closed - no more materials or components can be issued to it.'), 'error');
				$InputError = true;
			}
			$QuantityIssued = filter_number_format($value);;
			//Need to get the current standard cost for the item being issued
			$SQL = "SELECT materialcost+labourcost+overheadcost AS cost,
									controlled,
									serialised,
									mbflag
								FROM stockmaster
						WHERE stockid='" . $_POST['Item' . $No] . "'";
			$Result = DB_query($SQL);
			$IssueItemRow = DB_fetch_array($Result);

			if ($IssueItemRow['cost'] == 0) {
				prnMsg(_('The item being issued has a zero cost. The issue will still be processed '), 'warn');
			}

			if ($_SESSION['ProhibitNegativeStock'] == 1 and ($IssueItemRow['mbflag'] == 'M' or $IssueItemRow['mbflag'] == 'B')) {
				$SQL = "SELECT quantity FROM locstock
						WHERE stockid ='" . $_POST['IssueItem'] . "'
						AND loccode ='" . $_POST['FromLocation'] . "'";
				$CheckNegResult = DB_query($SQL);
				$CheckNegRow = DB_fetch_row($CheckNegResult);
				if ($CheckNegRow[0] < $QuantityIssued) {
					$InputError = true;
					prnMsg(_('This issue cannot be processed because the system parameter is set to prohibit negative stock and this issue would result in stock going into negative. Please correct the stock first before attempting another issue'), 'error');
				}
			} //end of negative inventory check
			$IssueItems[] = array('item' => $_POST['Item' . $No], 'qty' => $QuantityIssued, 'mbflag' => $IssueItemRow['mbflag'], 'cost' => $IssueItemRow['cost']);
		} //end of validation
		
	}
	if (isset($InputError) and $InputError == false) {
		/************************ BEGIN SQL TRANSACTIONS ************************/
		$Result = DB_Txn_Begin();
		/*Now Get the next WO Issue transaction type 28 - function in SQL_CommonFunctions*/
		$WOIssueNo = GetNextTransNo(28);
		$PeriodNo = GetPeriod(Date($_SESSION['DefaultDateFormat'])); //backdate
		$SQLIssuedDate = FormatDateForSQL($_POST['IssuedDate']);
		foreach ($IssueItems as $key => $itm) {
			$_POST['IssueItem'] = $itm['item'];
			$QuantityIssued = $itm['qty'];
			$IssueItemRow['mbflag'] = $itm['mbflag'];
			$StockGLCode = GetStockGLCode($_POST['IssueItem']);
			$IssueItemRow['cost'] = $itm['cost'];
			if ($IssueItemRow['mbflag'] == 'M' or $IssueItemRow['mbflag'] == 'B') {
				/* Need to get the current location quantity will need it later for the stock movement */
				$SQL = "SELECT locstock.quantity
						FROM locstock
						WHERE locstock.stockid='" . $_POST['IssueItem'] . "'
						AND loccode= '" . $_POST['FromLocation'] . "'";
				$Result = DB_query($SQL);
				if (DB_num_rows($Result) == 1) {
					$LocQtyRow = DB_fetch_row($Result);
					$NewQtyOnHand = ($LocQtyRow[0] - $QuantityIssued);
				} else {
					/*There must actually be some error this should never happen */
					$NewQtyOnHand = 0;
				}

				$SQL = "UPDATE locstock
							SET quantity = locstock.quantity - " . $QuantityIssued . "
							WHERE locstock.stockid = '" . $_POST['IssueItem'] . "'
							AND loccode = '" . $_POST['FromLocation'] . "'";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
				$DbgMsg = _('The following SQL to update the location stock record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			} else {
				$NewQtyOnHand = 0; //since we can't have stock of labour type items!!
				
			}
			/*Insert stock movements - with unit cost */
			$SQL = "INSERT INTO stockmoves (stockid,
								type,
								transno,
								loccode,
								trandate,
								userid,
								price,
								prd,
								reference,
								qty,
								standardcost,
								newqoh)
							VALUES ('" . $_POST['IssueItem'] . "',
									28,
									'" . $WOIssueNo . "',
									'" . $_POST['FromLocation'] . "',
									'" . FormatDateForSQL($_POST['IssuedDate']) . "',
									'" . $_SESSION['UserID'] . "',
									'" . $IssueItemRow['cost'] . "',
									'" . $PeriodNo . "',
									'" . $_POST['WO'] . "',
									'" . -$QuantityIssued . "',
									'" . $IssueItemRow['cost'] . "',
									'" . $NewQtyOnHand . "')";
			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('stock movement records could not be inserted when processing the work order issue because');
			$DbgMsg = _('The following SQL to insert the stock movement records was used');
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
			if ($_SESSION['CompanyRecord']['gllink_stock'] == 1) {
				/*GL integration with stock is activated so need the GL journals to make it so */
				/*first the debit the WIP of the item being manufactured from the WO
								the appropriate account was already retrieved into the $StockGLCode variable as the Processing code is kicked off
								it is retrieved from the stock category record of the item by a function in SQL_CommonFunctions.inc*/
				$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									narrative,
									amount)
							VALUES (28,
								'" . $WOIssueNo . "',
								'" . FormatDateForSQL($_POST['IssuedDate']) . "',
								'" . $PeriodNo . "',
								'" . $WORow['wipact'] . "',
								'" . $_POST['WO'] . " " . $_POST['IssueItem'] . ' x ' . $QuantityIssued . " @ " . locale_number_format($IssueItemRow['cost'], $_SESSION['CompanyRecord']['decimalplaces']) . "',
								'" . ($IssueItemRow['cost'] * $QuantityIssued) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The issue of the item to the work order GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the work order issue GLTrans record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);
				/*now the credit Stock entry*/
				$SQL = "INSERT INTO gltrans (type,
										typeno,
										trandate,
										periodno,
										account,
										narrative,
										amount)
							VALUES (28,
								'" . $WOIssueNo . "',
								'" . FormatDateForSQL($_POST['IssuedDate']) . "',
								'" . $PeriodNo . "',
								'" . $StockGLCode['stockact'] . "',
								'" . $_POST['WO'] . " " . $_POST['IssueItem'] . ' x ' . $QuantityIssued . " @ " . locale_number_format($IssueItemRow['cost'], $_SESSION['CompanyRecord']['decimalplaces']) . "',
								'" . -($IssueItemRow['cost'] * $QuantityIssued) . "')";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock account credit on the issue of items to a work order GL posting could not be inserted because');
				$DbgMsg = _('The following SQL to insert the stock GLTrans record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

			} /* end of if GL and stock integrated and standard cost !=0 */

			//update the wo with the new qtyrecd
			$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Could not update the work order cost issued to the work order because');
			$DbgMsg = _('The following SQL was used to update the work order');
			$UpdateWOResult = DB_query("UPDATE workorders
									SET costissued=costissued+" . ($QuantityIssued * $IssueItemRow['cost']) . "
									WHERE wo='" . $_POST['WO'] . "'", $ErrMsg, $DbgMsg, true);

			prnMsg(_('The issue of') . ' ' . $QuantityIssued . ' ' . _('of') . ' ' . $_POST['IssueItem'] . ' ' . _('against work order') . ' ' . $_POST['WO'] . ' ' . _('has been processed'), 'info');
		} //end of foreach loop;
		$Result = DB_Txn_Commit();

		echo '<p><ul><li><a href="' . $RootPath . '/WorkOrderIssue.php?WO=' . $_POST['WO'] . '&amp;StockID=' . $_POST['StockID'] . '">' . _('Issue more components to this work order') . '</a></li>';
		echo '<li><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select a different work order for issuing materials and components against') . '</a></li></ul>';
		unset($_POST['WO']);
		unset($_POST['StockID']);
		unset($_POST['FromLocation']);
		unset($_POST['Process']);
		unset($_POST['SerialNos']);
		/*end of process work order issues entry */
		include ('includes/footer.php');
		exit;
	} //end if there were not input errors reported - so the processing was allowed to continue
	
} //end of multiple items input


/*User hit the search button looking for an item to issue to the WO */
if (isset($_POST['Search'])) {

	if ($_POST['Keywords'] and $_POST['StockCode']) {
		prnMsg(_('Stock description keywords have been used in preference to the Stock code extract entered'), 'warn');
	}
	if (mb_strlen($_POST['Keywords']) > 0) {
		//insert wildcard characters in spaces
		$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
					FROM stockmaster,
					stockcategory
					WHERE stockmaster.categoryid=stockcategory.categoryid
					AND (stockcategory.stocktype='F' OR stockcategory.stocktype='L' OR stockcategory.stocktype='M')
					AND stockmaster.description " . LIKE . " '$SearchString'
					AND stockmaster.discontinued=0
					AND (mbflag='B' OR mbflag='M' OR mbflag='D')
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
					FROM stockmaster, stockcategory
					WHERE  stockmaster.categoryid=stockcategory.categoryid
					AND (stockcategory.stocktype='F' OR stockcategory.stocktype='L' OR stockcategory.stocktype='M')
					AND stockmaster.discontinued=0
					AND stockmaster.description " . LIKE . " '" . $SearchString . "'
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					AND (mbflag='B' OR mbflag='M' OR mbflag='D')
					ORDER BY stockmaster.stockid";
		}

	} elseif (mb_strlen($_POST['StockCode']) > 0) {

		$_POST['StockCode'] = mb_strtoupper($_POST['StockCode']);
		$SearchString = '%' . $_POST['StockCode'] . '%';

		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
					FROM stockmaster, stockcategory
					WHERE stockmaster.categoryid=stockcategory.categoryid
					AND (stockcategory.stocktype='F' OR stockcategory.stocktype='L' OR stockcategory.stocktype='M')
					AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					AND stockmaster.discontinued=0
					AND (mbflag='B' OR mbflag='M' OR mbflag='D')
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
					FROM stockmaster, stockcategory
					WHERE stockmaster.categoryid=stockcategory.categoryid
					AND (stockcategory.stocktype='F' OR stockcategory.stocktype='L' OR stockcategory.stocktype='M')
					AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
					AND stockmaster.discontinued=0
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					AND (mbflag='B' OR mbflag='M' OR mbflag='D')
					ORDER BY stockmaster.stockid";
		}
	} else {
		if ($_POST['StockCat'] == 'All') {
			$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
					FROM stockmaster, stockcategory
					WHERE  stockmaster.categoryid=stockcategory.categoryid
					AND (stockcategory.stocktype='F' OR stockcategory.stocktype='L' OR stockcategory.stocktype='M')
					AND stockmaster.discontinued=0
					AND (mbflag='B' OR mbflag='M' OR mbflag='D')
					ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT stockmaster.stockid,
					stockmaster.description,
					stockmaster.units
					FROM stockmaster, stockcategory
					WHERE stockmaster.categoryid=stockcategory.categoryid
					AND (stockcategory.stocktype='F' OR stockcategory.stocktype='L' OR stockcategory.stocktype='M')
					AND stockmaster.discontinued=0
					AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
					AND (mbflag='B' OR mbflag='M' OR mbflag='D')
					ORDER BY stockmaster.stockid";
		}
	}

	$ErrMsg = _('There is a problem selecting the part records to display because');
	$DbgMsg = _('The SQL used to get the part selection was');
	$SearchResult = DB_query($SQL, $ErrMsg, $DbgMsg);

	if (DB_num_rows($SearchResult) == 0) {
		prnMsg(_('There are no products available meeting the criteria specified'), 'info');

		if ($debug == 1) {
			prnMsg(_('The SQL statement used was') . ':<br />' . $SQL, 'info');
		}
	}
	if (DB_num_rows($SearchResult) == 1) {
		$MyRow = DB_fetch_array($SearchResult);
		$_POST['IssueItem'] = $MyRow['stockid'];
		DB_data_seek($SearchResult, 0);
	}

} //end of if search


/* Always display quantities received and recalc balance for all items on the order */

$ErrMsg = _('Could not retrieve the details of the selected work order item');
$WOResult = DB_query("SELECT workorders.loccode,
						 locations.locationname,
						 workorders.requiredby,
						 workorders.startdate,
						 workorders.closed,
						 stockmaster.stockid,
						 stockmaster.description,
						 stockmaster.decimalplaces,
						 stockmaster.units,
						 woitems.qtyreqd,
						 woitems.qtyrecd
						FROM workorders INNER JOIN locations
						ON workorders.loccode=locations.loccode
						INNER JOIN woitems
						ON workorders.wo=woitems.wo
						INNER JOIN stockmaster
						ON woitems.stockid=stockmaster.stockid
						WHERE woitems.wo ='" . $_POST['WO'] . "'", $ErrMsg);

if (DB_num_rows($WOResult) == 0) {
	prnMsg(_('The selected work order item cannot be retrieved from the database'), 'info');
	include ('includes/footer.php');
	exit;
}

if (!isset($_POST['IssuedDate'])) {
	$_POST['IssuedDate'] = Date($_SESSION['DefaultDateFormat']);
}
$WORow = DB_fetch_array($WOResult);

echo '<table class="selection">
		<tr>
			<td class="label">' . _('Issue to work order') . ':</td>
			<td>' . $_POST['WO'] . '</td>
		</tr>
		<tr>
			<td class="label">' . _('Manufactured at') . ':</td>
			<td>' . $WORow['locationname'] . '</td>
			<td class="label">' . _('Required By') . ':</td>
			<td>' . ConvertSQLDate($WORow['requiredby']) . '</td>
		</tr>
		<tr>
			<td class="label">' . _('Item') . '</td>
			<td class="label">' . _('Quantity Ordered') . ':</td>
			<td class="label">' . _('Already Received') . ':</td>
			<td class="label">' . _('Unit') . ':</td>
		</tr>';

if ($WORow['closed'] == 1) {
	prnMsg(_('The selected work order has been closed and variances calculated and posted. No more issues of materials and components can be made against this work order.'), 'info');
	include ('includes/footer.php');
	exit;
}
DB_data_seek($WOResult, 0);

if (!isset($_POST['FromLocation'])) {
	$_POST['FromLocation'] = $WORow['loccode'];
}

while ($WORow = DB_fetch_array($WOResult)) {

	echo '<tr>
				<td>' . $WORow['stockid'] . ' - ' . $WORow['description'] . '</td>
				<td class="number">' . locale_number_format($WORow['qtyreqd'], $WORow['decimalplaces']) . '</td>
				<td class="number">' . locale_number_format($WORow['qtyrecd'], $WORow['decimalplaces']) . '</td>
				<td>' . $WORow['units'] . '</td>
			</tr>';
}

echo '<tr>
		<td class="label">' . _('Date Material Issued') . ':</td>
		<td><input type="text" name="IssuedDate" value="' . Date($_SESSION['DefaultDateFormat']) . '" class="date" maxlength="10" size="11" /></td>
		<td class="label">' . _('Issued From') . ':</td>
		<td>';

if (!isset($_POST['IssueItem'])) {
	$LocResult = DB_query("SELECT locations.loccode,locationname
							FROM locations
							INNER JOIN locationusers
								ON locationusers.loccode=locations.loccode AND locationusers.userid='" . $_SESSION['UserID'] . "'
								AND locationusers.canupd=1
							WHERE locations.usedforwo = 1");

	echo '<select name="FromLocation">';

	while ($LocRow = DB_fetch_array($LocResult)) {
		if ($_POST['FromLocation'] == $LocRow['loccode']) {
			echo '<option selected="selected" value="' . $LocRow['loccode'] . '">' . $LocRow['locationname'] . '</option>';
		} else {
			echo '<option value="' . $LocRow['loccode'] . '">' . $LocRow['locationname'] . '</option>';
		}
	}
	echo '</select>';
} else {
	$LocResult = DB_query("SELECT loccode, locationname
						FROM locations
						WHERE loccode='" . $_POST['FromLocation'] . "'");
	$LocRow = DB_fetch_array($LocResult);
	echo '<input type="hidden" name="FromLocation" value="' . $_POST['FromLocation'] . '" />';
	echo $LocRow['locationname'];
}
echo '</td>
	</tr>
	</table>
	<br />
	<table class="selection">';

if (!isset($_POST['IssueItem'])) { //no item selected to issue yet
	//set up options for selection of the item to be issued to the WO
	echo '<tr>
			<th colspan="7">' . _('Material Requirements For this Work Order') . '</th>
		</tr>';
	echo '<tr>
			<th>' . _('Parent Item') . '</th>
			<th colspan="2">' . _('Item') . '</th>
			<th>' . _('Qty Required') . '</th>
			<th>' . _('Qty Issued') . '</th>
			<th>' . _('Qty Issue') . '</th>
		</tr>';

	$RequirmentsResult = DB_query("SELECT worequirements.stockid,
										worequirements.parentstockid,
										stockmaster.description,
										stockmaster.decimalplaces,
										stockmaster.controlled,
										autoissue,
										qtypu*qtyreqd AS quantityrequired
									FROM worequirements INNER JOIN stockmaster
									ON worequirements.stockid=stockmaster.stockid
									INNER JOIN woitems
									ON worequirements.wo=woitems.wo
									AND worequirements.parentstockid=woitems.stockid
									WHERE worequirements.wo='" . $_POST['WO'] . "'");
	$IssuedAlreadyResult = DB_query("SELECT stockid, SUM(-qty) as total FROM stockmoves
										WHERE stockmoves.type=28
										AND reference='" . $_POST['WO'] . "' GROUP BY stockid");
	while ($MyRow = DB_fetch_array($IssuedAlreadyResult)) {
		$IssuedMaterials[$MyRow['stockid']] = $MyRow['total'];

	}
	$i = 0;
	while ($RequirementsRow = DB_fetch_array($RequirmentsResult)) {
		if ($RequirementsRow['autoissue'] == 0) {
			echo '<tr>
					<td>' . $RequirementsRow['parentstockid'] . '</td>
					<td><input type="submit" name="IssueItem" value="' . $RequirementsRow['stockid'] . '" /></td>
					<td>' . $RequirementsRow['stockid'] . ' - ' . $RequirementsRow['description'] . '</td>';
		} else {
			echo '<tr>
					<td>' . $RequirementsRow['parentstockid'] . '</td>
					<td class="notavailable">' . _('Auto Issue') . '</td>
					<td class="notavailable">' . $RequirementsRow['stockid'] . ' - ' . $RequirementsRow['description'] . '</td>';
		}
		if (isset($IssuedMaterials[$RequirementsRow['stockid']])) {
			$IssuedAlreadyRow = $IssuedMaterials[$RequirementsRow['stockid']];
			unset($IssuedMaterials[$RequirementsRow['stockid']]);
		} else {
			$IssuedAlreadyRow = 0;
		}

		echo '<td class="number">' . locale_number_format($RequirementsRow['quantityrequired'], $RequirementsRow['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format($IssuedAlreadyRow, $RequirementsRow['decimalplaces']) . '</td>';
		if ($RequirementsRow['controlled'] == 0) {
			echo '<td><input type="text" name="IssueQty' . $i . '" id="IssueQty' . $i . '" /></td>
				<td><input type="checkbox" name="CheckQty' . $i . '" value="' . locale_number_format($RequirementsRow['quantityrequired'], $RequirementsRow['decimalplaces']) . '" onclick="AddAmount(this,\'IssueQty' . $i . '\')" /></td>
				<input type="hidden" name="Item' . $i . '" value="' . $RequirementsRow['stockid'] . '" />';
		}

		echo '</tr>';
		$i++;
	}
	/* now to deal with those addtional issues of items not in BOM */
	if (isset($IssuedMaterials) and count($IssuedMaterials) > 0) {
		$IssuedStocks = implode("','", array_keys($IssuedMaterials));
		$SQL = "SELECT  stockid,
				description,
				decimalplaces,
				controlled
			FROM stockmaster
			WHERE stockid in ('" . $IssuedStocks . "')";
		$ErrMsg = _('Failed to retrieve the item data');
		$Result = DB_query($SQL, $ErrMsg);
		while ($MyRow = DB_fetch_array($Result)) {
			echo '<tr>
					<td><input type="submit" name="IssueItem' . $i . '" value="' . $MyRow['stockid'] . '" /></td>
					<td>' . $MyRow['stockid'] . ' - ' . $MyRow['description'] . '</td>
					<td class="number">0</td>
					<td class="number">' . locale_number_format($IssuedMaterials[$MyRow['stockid']], $MyRow['decimalplaces']) . '</td>';
			if ($RequirementsRow['controlled'] == 0) {
				echo '<td><input type="text" name="IssueQty' . $i . '"  /></td>
				<input type="hidden" name="Item' . $i . '" value="' . $MyRow['stockid'] . '" />';
			}

			echo '</tr>';
			$i++;

		}

	}

	echo '</table>
		<br />
		<div class="centre">
			<input type="submit" name="ProcessMultiple" value="' . _('Process Items Issued') . '" />
		</div><br/>';

	$SQL = "SELECT categoryid,
			categorydescription
			FROM stockcategory
			WHERE stocktype='F' OR stocktype='D' OR stocktype='L'
			ORDER BY categorydescription";
	$Result1 = DB_query($SQL);

	echo '<table class="selection">
			<tr><td>' . _('Select a stock category') . ':<select name="StockCat">';

	if (!isset($_POST['StockCat'])) {
		echo '<option selected="selected" value="All">' . _('All') . '</option>';
		$_POST['StockCat'] = 'All';
	} else {
		echo '<option value="All">' . _('All') . '</option>';
	}

	while ($MyRow1 = DB_fetch_array($Result1)) {

		if ($_POST['StockCat'] == $MyRow1['categoryid']) {
			echo '<option selected="selected" value="' . $MyRow1['categoryid'] . '">' . $MyRow1['categorydescription'] . '</option>';
		} else {
			echo '<option value="' . $MyRow1['categoryid'] . '">' . $MyRow1['categorydescription'] . '</option>';
		}
	}

	echo '</select></td>
	    <td>' . _('Enter text extracts in the') . ' <b>' . _('description') . '</b>:</td>
	    <td><input type="text" name="Keywords" size="20" maxlength="25" value="';
	if (isset($_POST['Keywords'])) echo $_POST['Keywords'];
	echo '" /></td></tr>
	    <tr><td></td>
		<td><b>' . _('OR') . ' </b>' . _('Enter extract of the') . ' <b>' . _('Stock Code') . '</b>:</td>
		<td><input type="text" name="StockCode" size="15" maxlength="18" value="';
	if (isset($_POST['StockCode'])) echo $_POST['StockCode'];
	echo '" /></td>
			</tr>
			</table>
			<br />
			<div class="centre"><input type="submit" name="Search" value="' . _('Search Now') . '" />';

	echo '<script type="text/javascript">
		document.forms[0].StockCode.select();
		document.forms[0].StockCode.focus();
	</script>';

	echo '</div>';

	if (isset($SearchResult)) {

		if (DB_num_rows($SearchResult) > 1) {

			echo '<br />
				<table cellpadding="2" class="selection">';
			$TableHeader = '<tr>
								<th>' . _('Code') . '</th>
								<th>' . _('Description') . '</th>
								<th>' . _('Units') . '</th>
							</tr>';
			echo $TableHeader;
			$j = 1;
			$ItemCodes = array();

			while ($MyRow = DB_fetch_array($SearchResult)) {

				$SupportedImgExt = array('png', 'jpg', 'jpeg');
				if (!in_array($MyRow['stockid'], $ItemCodes)) {
					$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $MyRow['stockid'] . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
					if (extension_loaded('gd') && function_exists('gd_info') && file_exists($imagefile)) {
						$ImageSource = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC' . '&amp;StockID=' . urlencode($MyRow['stockid']) . '&amp;text=' . '&amp;width=64' . '&amp;height=64' . '" alt="" />';
					} else if (file_exists($imagefile)) {
						$ImageSource = '<img src="' . $imagefile . '" height="64" width="64" />';
					} else {
						$ImageSource = _('No Image');
					}

					$IssueLink = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?WO=' . $_POST['WO'] . '&amp;StockID=' . urlencode($_POST['StockID']) . '&amp;IssueItem=' . urlencode($MyRow['stockid']) . '&amp;FromLocation=' . $_POST['FromLocation'];
					printf('<tr class="striped_row">
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							<td>%s</td>
							<td><a href="%s">' . _('Add to Work Order') . '</a></td>
							</tr>', $MyRow['stockid'], $MyRow['description'], $MyRow['units'], $ImageSource, $IssueLink);

					$j++;
					if ($j == 25) {
						$j = 1;
						echo $TableHeader;
					} //end of page full new headings if
					
				} //end if not already on work order
				
			} //end of while loop
			
		} //end if more than 1 row to show
		echo '</table>';
	} #end if SearchResults to show
	
} else { //There is an item selected to issue
	//need to get some details about the item to issue
	$SQL = "SELECT description,
				decimalplaces,
				units,
				controlled,
				serialised
			FROM stockmaster
			WHERE stockid='" . $_POST['IssueItem'] . "'";
	$ErrMsg = _('Could not get the detail of the item being issued because');
	$IssueItemResult = DB_query($SQL, $ErrMsg);
	$IssueItemRow = DB_fetch_array($IssueItemResult);
	if ($IssueItemRow['decimalplaces'] <= 3) {
		$VarianceAllowed = 0.0001;
	} else {
		$VarianceAllowed = pow(10, -(1 + $IssueItemRow['decimalplaces']));
	}

	echo '<table class="selection">
		<tr>
			<td class="label">' . _('Issuing') . ':</td>
			<td>' . $_POST['IssueItem'] . ' - ' . $IssueItemRow['description'] . '</td>
			<td class="label">' . _('Units') . ':</td>
			<td>' . $IssueItemRow['units'] . '</td>
		</tr>
		</table>';

	echo '<br />
		<table class="selection">';

	//Now Setup the form for entering quantities of the item to be issued to the WO
	if ($IssueItemRow['controlled'] == 1) { //controlled
		if ($IssueItemRow['serialised'] == 1) { //serialised
			echo '<tr>
					<th>' . _('Serial Numbers Issued') . '</th>
				</tr>';

			$SerialNoResult = DB_query("SELECT serialno
										FROM stockserialitems
										WHERE stockid='" . $_POST['IssueItem'] . "'
										AND loccode='" . $_POST['FromLocation'] . "'
										AND quantity > " . $VarianceAllowed, _('Could not retrieve the serial numbers available at the location specified because'));
			if (DB_num_rows($SerialNoResult) == 0) {
				echo '<tr>
						<td>' . _('There are no serial numbers at this location to issue') . '</td>
					</tr>';
				echo '<tr>
						<td colspan="2"><div class="centre"><input type="submit" name="Retry" value="' . _('Reselect Location or Issued Item') . '" /></td>
					</tr>';
			} else {
				echo '<tr>
						<td><select name="SerialNos[]" multiple="multiple">';
				while ($SerialNoRow = DB_fetch_array($SerialNoResult)) {
					if (isset($_POST['SerialNos']) and in_array($SerialNoRow['serialno'], $_POST['SerialNos'])) {
						echo '<option selected="selected" value="' . $SerialNoRow['serialno'] . '">' . $SerialNoRow['serialno'] . '</option>';
					} else {
						echo '<option value="' . $SerialNoRow['serialno'] . '">' . $SerialNoRow['serialno'] . '</option>';
					}
				}
				echo '</select></td></tr>';
				echo '<input type="hidden" name="IssueItem" value="' . $_POST['IssueItem'] . '" />';
				echo '<tr>
						<td colspan="2"><div class="centre"><input type="submit" name="Process" value="' . _('Process Items Issued') . '" /></div></td>
					</tr>';
			}
		} else { //controlled but not serialised - just lot/batch control
			echo '<tr>
					<th colspan="2">' . _('Batch/Lots Issued') . '</th>
				</tr>';
			$LotNoSQL = "SELECT serialno,quantity
										FROM stockserialitems
										WHERE stockid='" . $_POST['IssueItem'] . "'
										AND loccode='" . $_POST['FromLocation'] . "'
										AND quantity > " . $VarianceAllowed;
			$ErrMsg = _('Failed to retrieve lot No');
			$LotResult = DB_query($LotNoSQL, $ErrMsg);
			if (DB_num_rows($LotResult) > 0) {
				$i = 0;
				while ($LotRow = DB_fetch_array($LotResult)) {
					echo '<tr>
						<td><input type="text" name="BatchRef' . $i . '" title="' . _('Enter a batch/roll reference being used with this work order') . '" value="' . $LotRow['serialno'] . '"  /></td>
						<td><input class="number" title="' . _('Enter the quantity of this batch/roll to issue to the work order') . '" name="Qty' . $i . '"  placeholder="' . $LotRow['quantity'] . '" /></td>
						</tr>';
					$i++;
				}
				echo '<input type="hidden" name="LotCounter" value="' . $i . '" />';
			} else {
				echo '<tr>
						<td>' . _('There are no serial numbers at this location to issue') . '</td>
					</tr>';
				echo '<tr>
						<td colspan="2"><div class="centre"><input type="submit" name="Retry" value="' . _('Reselect Location or Issued Item') . '" /></td>
					</tr>';
				$i = 0;
				echo '<tr>
						<td colspan="4">' . _('You may need to receive (input negative quantity) some items whose serial no has never existed by following') . '</td>
					</tr>';
				echo '<tr>
						<td colspan="2">' . _('Lot No') . ': <input type="text" name="BatchRef' . $i . '" title="' . _('Enter a batch/roll reference being used with this work order') . '" value=""  />
						<td colspan="2">' . _('Quantity') . ': <input class="number" title="' . _('Enter the quantity of this batch/roll to issue to the work order') . '" name="Qty' . $i . '" /></td>
						</tr>';
				$i++;
				echo '<input type="hidden" name="LotCounter" value="' . $i . '" />';
			}
			echo '<input type="hidden" name="IssueItem" value="' . $_POST['IssueItem'] . '" />';
			echo '<tr>
					<td colspan="2"><div class="centre"><input type="submit" name="Process" value="' . _('Process Items Issued') . '" /></div></td>
				</tr>';
		} //end of lot/batch control
		
	} else { //not controlled - an easy one!
		echo '<input type="hidden" name="IssueItem" value="' . $_POST['IssueItem'] . '" />';
		echo '<tr><td>' . _('Quantity Issued') . ':</td>
			  <td><input class="number" type="text" size="10" maxlength="10" title="' . _('Enter the quantity of this item to issue to the work order') . '" name="Qty" required="required" value="0"/></tr>';
		echo '<tr>
				<td colspan="2"><input type="submit" name="Process" value="' . _('Process Items Issued') . '" /></div></td>
			</tr>';
	}
	echo '</table>';
} //end if selecting new item to issue or entering the issued item quantities
echo '</div>
      </form>';

include ('includes/footer.php');
?>

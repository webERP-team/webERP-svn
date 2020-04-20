<?php
/* Creates a pdf comparing the quantites entered as counted at a given range of locations against the quantity stored as on hand as at the time a stock check was initiated. */

include('includes/session.php');

If (isset($_POST['PrintPDF']) AND isset($_POST['ReportOrClose'])){

	include('includes/PDFStarter.php');
	$pdf->addInfo('Title', _('Check Comparison Report') );
	$pdf->addInfo('Subject', _('Inventory Check Comparison'). ' ' . Date($_SESSION['DefaultDateFormat']));
	$PageNumber=1;
	$line_height=15;


	include('includes/SQL_CommonFunctions.inc');


/*First off do the Inventory Comparison file stuff */
	if ($_POST['ReportOrClose']=='ReportAndClose'){

		$sql = "SELECT stockcheckfreeze.stockid,
						stockcheckfreeze.loccode,
						qoh,
						materialcost+labourcost+overheadcost AS standardcost
				FROM stockmaster INNER JOIN stockcheckfreeze
				ON stockcheckfreeze.stockid=stockmaster.stockid
				ORDER BY stockcheckfreeze.loccode,
						stockcheckfreeze.stockid";

		$StockChecks = DB_query($sql,'','',false,false);
		if (DB_error_no() !=0) {
			$Title = _('Stock Freeze') . ' - ' . _('Problem Report') . '....';
			include('includes/header.php');
			echo '<br />';
			prnMsg( _('The inventory check file could not be retrieved because'). ' - ' . DB_error_msg(),'error');
			echo '<br /><a href="' .$RootPath .'/index.php">' .  _('Back to the menu') . '</a>';
			if ($debug==1){
	      			echo '<br />' . $sql;
			}
			include('includes/footer.php');
			exit;
		}

		$PeriodNo = GetPeriod (Date($_SESSION['DefaultDateFormat']));
		$SQLAdjustmentDate = FormatDateForSQL(Date($_SESSION['DefaultDateFormat']));
		$AdjustmentNumber = GetNextTransNo(17);

		while ($myrow = DB_fetch_array($StockChecks)){

			$sql = "SELECT SUM(stockcounts.qtycounted) AS totcounted,
					COUNT(stockcounts.stockid) AS noofcounts
					FROM stockcounts
					WHERE stockcounts.stockid='" . $myrow['stockid'] . "'
					AND stockcounts.loccode='" . $myrow['loccode'] . "'";

			$StockCounts = DB_query($sql);
			if (DB_error_no() !=0) {
				$Title = _('Stock Count Comparison') . ' - ' . _('Problem Report') . '....';
				include('includes/header.php');
				echo '<br />';
				prnMsg( _('The inventory counts file could not be retrieved because'). ' - ' . DB_error_msg(). 'error');
				echo '<br /><a href="' .$RootPath .'/index.php">' .  _('Back to the menu') . '</a>';
				if ($debug==1){
					echo '<br />' .  $sql;
				}
				include('includes/footer.php');
				exit;
			}

			$StkCountResult = DB_query($sql);
			$StkCountRow = DB_fetch_array($StkCountResult);

			$StockQtyDifference = $StkCountRow['totcounted'] - $myrow['qoh'];

			if ($_POST['ZeroCounts']=='Leave' AND $StkCountRow['noofcounts']==0){
				$StockQtyDifference =0;
			}

			if ($StockQtyDifference !=0){ // only adjust stock if there is an adjustment to make!!

				DB_Txn_Begin();

				// Need to get the current location quantity will need it later for the stock movement
				$SQL="SELECT locstock.quantity
						FROM locstock
					WHERE locstock.stockid='" . $myrow['stockid'] . "'
					AND loccode= '" . $myrow['loccode'] . "'";

				$Result = DB_query($SQL);
				if (DB_num_rows($Result)==1){
					$LocQtyRow = DB_fetch_row($Result);
					$QtyOnHandPrior = $LocQtyRow[0];
				} else {
					// There must actually be some error this should never happen
					$QtyOnHandPrior = 0;
				}

				$SQL = "INSERT INTO stockmoves (stockid,
								type,
								transno,
								loccode,
								trandate,
								userid,
								prd,
								reference,
								qty,
								newqoh,
								standardcost)
						VALUES ('" . $myrow['stockid'] . "',
							17,
							'" . $AdjustmentNumber . "',
							'" . $myrow['loccode'] . "',
							'" . $SQLAdjustmentDate . "',
							'" . $_SESSION['UserID'] . "',
							'" . $PeriodNo . "',
							'" . _('Inventory Check') . "',
							'" . $StockQtyDifference . "',
							'" . ($QtyOnHandPrior + $StockQtyDifference) . "',
							'" . $myrow['standardcost'] . "'
						)";

				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The stock movement record cannot be inserted because');
				$DbgMsg = _('The following SQL to insert the stock movement record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				$SQL = "UPDATE locstock
						SET quantity = quantity + '" . $StockQtyDifference . "'
						WHERE stockid='" . $myrow['stockid'] . "'
						AND loccode='" . $myrow['loccode'] . "'";
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The location stock record could not be updated because');
				$DbgMsg = _('The following SQL to update the stock record was used');
				$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				if ($_SESSION['CompanyRecord']['gllink_stock']==1 AND $myrow['standardcost'] > 0){

					$StockGLCodes = GetStockGLCode($myrow['stockid']);
					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The general ledger transaction entries could not be added because');
					$DbgMsg = _('The following SQL to insert the GL entries was used');

					$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									amount,
									narrative)
							VALUES (17,
								'" .$AdjustmentNumber . "',
								'" . $SQLAdjustmentDate . "',
								'" . $PeriodNo . "',
								'" .  $StockGLCodes['adjglact'] . "',
								'" . ($myrow['standardcost'] * -($StockQtyDifference)) . "',
								'" . $myrow['stockid'] . " x " . $StockQtyDifference . " @ " . $myrow['standardcost'] . " - " . _('Inventory Check') . "')";
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

					$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('The general ledger transaction entries could not be added because');
					$DbgMsg = _('The following SQL to insert the GL entries was used');

					$SQL = "INSERT INTO gltrans (type,
									typeno,
									trandate,
									periodno,
									account,
									amount,
									narrative)
							VALUES (17,
								'" .$AdjustmentNumber . "',
								'" . $SQLAdjustmentDate . "',
								'" . $PeriodNo . "',
								'" .  $StockGLCodes['stockact'] . "',
								'" . $myrow['standardcost'] * $StockQtyDifference . "',
                                '" . $myrow['stockid'] . " x " . $StockQtyDifference . " @ " . $myrow['standardcost'] . " - " . _('Inventory Check') . "')";
					$Result = DB_query($SQL, $ErrMsg, $DbgMsg, true);

				} //END INSERT GL TRANS
				$ErrMsg = _('CRITICAL ERROR') . '! ' . _('NOTE DOWN THIS ERROR AND SEEK ASSISTANCE') . ': ' . _('Unable to COMMIT transaction while adjusting stock in StockCheckAdjustmet report');
				DB_Txn_Commit();

			} // end if $StockQtyDifference !=0

		} // end loop round all the checked parts
	} // end user wanted to close the inventory check file and do the adjustments

	// now do the report
	$ErrMsg = _('The Inventory Comparison data could not be retrieved because');
	$DbgMsg = _('The following SQL to retrieve the Inventory Comparison data was used');
	$sql = "SELECT stockcheckfreeze.stockid,
					description,
					stockmaster.categoryid,
					stockcategory.categorydescription,
					stockcheckfreeze.loccode,
					locations.locationname,
					stockcheckfreeze.qoh,
					stockmaster.decimalplaces,
					bin
			FROM stockcheckfreeze INNER JOIN stockmaster
				ON stockcheckfreeze.stockid=stockmaster.stockid
			INNER JOIN stockcategory
				ON stockmaster.categoryid=stockcategory.categoryid
			INNER JOIN locations
				ON stockcheckfreeze.loccode=locations.loccode
			INNER JOIN locstock
				ON stockcheckfreeze.loccode=locstock.loccode
				AND stockcheckfreeze.stockid=locstock.stockid
			ORDER BY stockcheckfreeze.loccode,
				stockmaster.categoryid,
				stockcheckfreeze.stockid";

	$CheckedItems = DB_query($sql, $ErrMsg, $DbgMsg);

	if (DB_num_rows($CheckedItems)==0){
		$Title= _('Inventory Comparison Comparison Report');
		include('includes/header.php');
		echo '<p>';
		prnMsg(_('There is no inventory check data to report on'), 'warn');
		echo '<p>' .  _('To start an inventory check first run the'). ' <a href="' . $RootPath . '/StockCheck.php">' .  _('inventory check sheets') . '</a> - '. _('and select the option to create new Inventory Comparison data file');
		include('includes/footer.php');
		exit;
	}
	$FirstRow = DB_fetch_array($CheckedItems);
	$LocationName = $FirstRow['locationname'];
	DB_data_seek($CheckedItems,0);

	include ('includes/PDFStockComparisonPageHeader.inc');

	$Location = '';
	$Category = '';

	While ($CheckItemRow = DB_fetch_array($CheckedItems)){

		if ($Location!=$CheckItemRow['loccode']){
			$FontSize=14;
			if ($Location!=''){ /*Then it is NOT the first time round */
				/*draw a line under the Location*/
				$pdf->line($Left_Margin, $YPos-2,$Page_Width-$Right_Margin, $YPos-2);
				$YPos -=$line_height;
			}

			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,260-$Left_Margin,$FontSize,$CheckItemRow['loccode'] . ' - ' . $CheckItemRow['locationname'], 'left');
			$Location = $CheckItemRow['loccode'];
			$YPos -=$line_height;
		}


		if ($Category!=$CheckItemRow['categoryid']){
			$FontSize=12;
			if ($Category!=''){ /*Then it is NOT the first time round */
				/*draw a line under the CATEGORY TOTAL*/
				$pdf->line($Left_Margin, $YPos-2,$Page_Width-$Right_Margin, $YPos-2);
				$YPos -=$line_height;
			}

			$LeftOvers = $pdf->addTextWrap($Left_Margin+15,$YPos,260-$Left_Margin,$FontSize,$CheckItemRow['categoryid'] . ' - ' . $CheckItemRow['categorydescription'], 'left');
			$Category = $CheckItemRow['categoryid'];
			$YPos -=$line_height;
		}


		$SQL = "SELECT qtycounted,
						reference
				FROM stockcounts
				WHERE loccode ='" . $Location . "'
				AND stockid = '" . $CheckItemRow['stockid'] . "'";

		$Counts = DB_query($SQL,'','',false,false);

		if (DB_error_no() !=0) {
	 		$Title = _('Inventory Comparison') . ' - ' . _('Problem Report') . '.... ';
	  		include('includes/header.php');
	   		echo '<br />';
			prnMsg( _('The inventory counts could not be retrieved by the SQL because').' - ' . DB_error_msg(), 'error');
	   		echo '<br /><a href="' .$RootPath .'/index.php">' .  _('Back to the menu'). '</a>';
	   		if ($debug==1){
	      			echo '<br />' .  $SQL;
	   		}
	   		include('includes/footer.php');
	   		exit;
		}
		if ($CheckItemRow['qoh']!=0 OR DB_num_rows($Counts)>0) {
			$YPos -=$line_height;
			$FontSize=8;
			if (mb_strlen($CheckItemRow['bin'])>0){
				$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,120,$FontSize,$CheckItemRow['stockid'] . ' - ' . _('Bin') . ':' . $CheckItemRow['bin'], 'left');
			} else {
				$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,120,$FontSize,$CheckItemRow['stockid'], 'left');
			}
			$LeftOvers = $pdf->addTextWrap(135,$YPos,180,$FontSize,$CheckItemRow['description'], 'left');
			$LeftOvers = $pdf->addTextWrap(315,$YPos,60,$FontSize,locale_number_format($CheckItemRow['qoh'],$CheckItemRow['decimalplaces']), 'right');
		}

		if (DB_num_rows($Counts)==0 AND $CheckItemRow['qoh']!=0){
			$LeftOvers = $pdf->addTextWrap(380, $YPos,160, $FontSize, _('No counts entered'), 'left');
			if ($_POST['ZeroCounts']=='Adjust'){
				$LeftOvers = $pdf->addTextWrap(485, $YPos, 60, $FontSize, locale_number_format(-($CheckItemRow['qoh']),$CheckItemRow['decimalplaces']), 'right');
			}
		} elseif (DB_num_rows($Counts)>0) {
			$TotalCount =0;
			while ($CountRow=DB_fetch_array($Counts)){
				$LeftOvers = $pdf->addTextWrap(375, $YPos, 60, $FontSize, locale_number_format(($CountRow['qtycounted']),$CheckItemRow['decimalplaces']), 'right');
				$LeftOvers = $pdf->addTextWrap(440, $YPos, 100, $FontSize, $CountRow['reference'], 'left');
				$TotalCount += $CountRow['qtycounted'];
				$YPos -= $line_height;

				if ($YPos < $Bottom_Margin + $line_height){
		 			$PageNumber++;
		   			include('includes/PDFStockComparisonPageHeader.inc');
				}
			} // end of loop printing count information
			$LeftOvers = $pdf->addTextWrap($Left_Margin, $YPos, 375-$Left_Margin, $FontSize, _('Total for') . ': ' . $CheckItemRow['stockid'], 'right');
			$LeftOvers = $pdf->addTextWrap(375, $YPos, 60, $FontSize, locale_number_format($TotalCount,$CheckItemRow['decimalplaces']), 'right');
			$LeftOvers = $pdf->addTextWrap(485, $YPos, 60, $FontSize, locale_number_format($TotalCount-$CheckItemRow['qoh'],$CheckItemRow['decimalplaces']), 'right');
		} //end of if there are counts to print

		$pdf->line($Left_Margin, $YPos-2,$Page_Width-$Right_Margin, $YPos-2);

		if ($YPos < $Bottom_Margin + $line_height){
		   $PageNumber++;
		   include('includes/PDFStockComparisonPageHeader.inc');
		}

	} /*end STOCK comparison while loop */

	$YPos -= (2*$line_height);

    $pdf->OutputD($_SESSION['DatabaseName'] . '_StockComparison_' . date('Y-m-d') . '.pdf');
    $pdf->__destruct();

	if ($_POST['ReportOrClose']=='ReportAndClose'){
		//need to print the report first before this but don't risk re-adjusting all the stock!!
		$sql = "TRUNCATE TABLE stockcheckfreeze";
		$result = DB_query($sql);

		$sql = "TRUNCATE TABLE stockcounts";
		$result = DB_query($sql);
	}

} else { /*The option to print PDF was not hit */

	$Title= _('Inventory Comparison Report');
	include('includes/header.php');

	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/transactions.png" title="' . $Title . '" alt="" />' . ' '
		. $Title . '</p>';

	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

    echo '<table class="selection">';
	echo '<tr><td>' . _('Choose Option'). ':</td>
              <td><select name="ReportOrClose">';

	if ($_POST['ReportOrClose']=='ReportAndClose'){
		echo '<option selected="selected" value="ReportAndClose">' .  _('Report and Close the Inventory Comparison Processing Adjustments As Necessary') . '</option>';
		echo '<option value="ReportOnly">' .  _('Report The Inventory Comparison Differences Only - No Adjustments') . '</option>';
	} else {
		echo '<option selected="selected" value="ReportOnly">' . _('Report The Inventory Comparison Differences Only - No Adjustments') . '</option>';
		echo '<option value="ReportAndClose">' . _('Report and Close the Inventory Comparison Processing Adjustments As Necessary') . '</option>';
	}

	echo '</select></td></tr>';
	echo '<tr><td>' .  _('Action for Zero Counts') . ':</td>
              <td><select name="ZeroCounts">';

	if ($_POST['ZeroCounts'] =='Adjust'){
		echo '<option selected="selected" value="Adjust">' .  _('Adjust System stock to Nil') . '</option>';
		echo '<option value="Leave">' . _('Do not Adjust System stock to Nil') . '</option>';
	} else {
		echo '<option value="Adjust">' .  _('Adjust System stock to Nil') . '</option>';
		echo '<option selected="selected" value="Leave">' . _('Do not Adjust System stock to Nil') . '</option>';
	}

    echo '</select></td></tr>';
	echo '</table><br /><div class="centre"><input type="submit" name="PrintPDF" value="' . _('Print PDF'). '" /></div>';
    echo '</div>
          </form>';

	include('includes/footer.php');

} /*end of else not PrintPDF */

?>
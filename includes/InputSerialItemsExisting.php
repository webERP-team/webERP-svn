<?php


/**
If the User has selected Keyed Entry, show them this special select list...
it is just in the way if they are doing file imports
it also would not be applicable in a PO and possible other situations...
**/
if ($_POST['EntryType'] == 'KEYED'){
	/*Also a multi select box for adding bundles to the dispatch without keying */

	$sql = "SELECT serialno,
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
				AND pickreq.orderno='" . $OrderstoPick . "'
				AND pickreq.closed=0
				WHERE pickserialdetails.serialno=stockserialitems.serialno
				AND pickserialdetails.stockid=stockserialitems.stockid) as qtypickedthisorder
			FROM stockserialitems
			INNER JOIN locationusers
				ON locationusers.loccode=stockserialitems.loccode
				AND locationusers.userid='" .  $_SESSION['UserID'] . "'
				AND locationusers.canupd=1
			WHERE stockid='" . $StockID . "'
				AND stockserialitems.loccode ='" . $LocationOut."'
				AND quantity > 0
			ORDER BY createdate, quantity";
	$ErrMsg = '<br />' .  _('Could not retrieve the items for'). ' ' . $StockID;
	$Bundles = DB_query($sql, $ErrMsg );
	echo '<table class="selection"><tr>';
	if (DB_num_rows($Bundles)>0){
		$AllSerials=array();

		foreach ($LineItem->SerialItems as $Itm){
			$AllSerials[$Itm->BundleRef] = $Itm->BundleQty;
		}

		echo '<td valign="top"><b>' .  _('Select Existing Items'). '</b><br />';

		echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?identifier=' . urlencode($identifier) . '" method="post">';
		echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
		echo '<input type="hidden" name="LineNo" value="' . $LineNo . '">
			<input type="hidden" name="StockID" value="' . $StockID . '">
			<input type="hidden" name="EntryType" value="KEYED">
			<input type="hidden" name="identifier" value="' . $identifier . '">
			<input type="hidden" name="EditControlled" value="true">
			<select name=Bundles[] multiple="multiple">';

		$id=0;
		$ItemsAvailable=0;
		while ($myrow=DB_fetch_array($Bundles)){
			if (is_null($MyRow['qtypickedtotal'])) {
				$MyRow['qtypickedtotal'] = 0;
			}
			if (is_null($MyRow['qtypickedthisorder'])) {
				$MyRow['qtypickedthisorder'] = 0;
			}
			if ($LineItem->Serialised==1){
				if ( !array_key_exists($myrow['serialno'], $AllSerials) ){
					echo '<option value="' . $myrow['serialno'] . '">' . $myrow['serialno'] . '</option>';
					$ItemsAvailable++;
				}
			} else {

				if ( !array_key_exists($myrow['serialno'], $AllSerials)  OR
					($myrow['quantity'] - $AllSerials[$myrow['serialno']] >= 0) ) {

					//Use the $InOutModifier to ajust the negative or postive direction of the quantity. Otherwise the calculated quantity is wrong.
					if (isset($AllSerials[$MyRow['serialno']])) {
						$RecvQty = $myrow['quantity'] - $InOutModifier * $AllSerials[$myrow['serialno']];
					} else {
						$RecvQty = $myrow['quantity'];
					}
					echo '<option value="' . $myrow['serialno'] . '/|/'. $RecvQty .'">' . $myrow['serialno'].' - ' . _('Qty left'). ': ' . $RecvQty . '</option>';
					$ItemsAvailable += $RecvQty;
				}
			}
		}
		echo '</select>
			<br />';
		echo '<br /><div class="centre"><input type="submit" name="AddBatches" value="'. _('Enter'). '"></div>
			<br />';
		echo '</form>';
		echo $ItemsAvailable . ' ' . _('items available');
		echo '</td>';
	} else {
		echo '<td>' .  prnMsg( _('There does not appear to be any of') . ' ' . $StockID . ' ' . _('left in'). ' '. $LocationOut , 'warn') . '</td>';
	}
	echo '</tr></table>';
}

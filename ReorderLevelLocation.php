<?php


// ReorderLevelLocation.php - Report of reorder level by category

include('includes/session.php');

$Title=_('Reorder Level Location Reporting');
include('includes/header.php');

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Inventory') . '" alt="" />' . ' ' . _('Inventory Reorder Level Location Report') . '</p>';


//update database if update pressed
if (isset($_POST['submit'])){
	for ($i=1;$i<count($_POST);$i++){ //loop through the returned customers
		if (isset($_POST['StockID' . $i]) AND is_numeric(filter_number_format($_POST['ReorderLevel'.$i]))){
			$SQLUpdate="UPDATE locstock SET reorderlevel = '" . filter_number_format($_POST['ReorderLevel'.$i]) . "',
											bin = '" . strtoupper($_POST['BinLocation'.$i]) . "'
						WHERE loccode = '" . $_POST['StockLocation'] . "'
						AND stockid = '" . $_POST['StockID' . $i] . "'";
			$Result = DB_query($SQLUpdate);
		}
	}
}

if (isset($_POST['submit']) OR isset($_POST['Update'])) {

	if ($_POST['NumberOfDays']==''){
		header('Location: ReorderLevelLocation.php');
	}

	if($_POST['Sequence']==1){
		$Sequence="qtyinvoice DESC, locstock.stockid";
	}else{
		$Sequence="locstock.stockid";
	}

	$sql="SELECT locstock.stockid,
				description,
				reorderlevel,
				bin,
				quantity,
				decimalplaces,
				canupd
			FROM locstock INNER JOIN stockmaster
			ON locstock.stockid = stockmaster.stockid
			INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			WHERE stockmaster.categoryid = '" . $_POST['StockCat'] . "'
			AND locstock.loccode = '" . $_POST['StockLocation'] . "'
			AND stockmaster.discontinued = 0
			ORDER BY '" . $Sequence . "' ASC";

	$result = DB_query($sql);

	$SqlLoc="SELECT locationname
		   FROM locations
		   WHERE loccode='".$_POST['StockLocation']."'";

	$ResultLocation = DB_query($SqlLoc);
	$Location=DB_fetch_array($ResultLocation);

	echo'<p class="page_title_text"><strong>' . _('Location : ') . '' . $Location['locationname'] . ' </strong></p>';
	echo'<p class="page_title_text"><strong>' . _('Number Of Days Sales : ') . '' . locale_number_format($_POST['NumberOfDays'],0) . '' . _(' Days ') . ' </strong></p>';

	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" id="Update">';
    echo '<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    echo '<table>';
    echo '<tr>
            <th>' . _('Code') . '</th>
            <th>' . _('Description') . '</th>
            <th>' . _('Total Invoiced') . '<br />' . _('At Location') . '</th>
            <th>' . _('On Hand')  . '<br />' . _('At All Locations') . '</th>
            <th>' . _('On Hand')  . '<br />' ._('At Location') . '</th>
            <th>' . _('Reorder Level') . '</th>
            <th>' . _('Bin Location') . '</th>
        </tr>';

	$i=1;
	while ($myrow=DB_fetch_array($result))	{

		//variable for update data

		echo'<input type="hidden" value="' . $_POST['Sequence'] . '" name="Sequence" />
			<input type="hidden" value="' . $_POST['StockLocation'] . '" name="StockLocation" />
			<input type="hidden" value="' . $_POST['StockCat'] . '" name="StockCat" />
			<input type="hidden" value="' . locale_number_format($_POST['NumberOfDays'],0) . '" name="NumberOfDays" />';


		$SqlInv="SELECT SUM(-qty) AS qtyinvoiced
				FROM stockmoves
				WHERE stockid='".$myrow['stockid']."'
				AND (type=10 OR type=11)
				AND loccode='" . $_POST['StockLocation'] ."'
				AND trandate >= '" . FormatDateForSQL(DateAdd(Date($_SESSION['DefaultDateFormat']),'d',-filter_number_format($_POST['NumberOfDays']))) . "'";

		$ResultInvQty = DB_query($SqlInv);
		$SalesRow=DB_fetch_array($ResultInvQty);


		//get On Hand all
		//find the quantity onhand item
		$SqlOH="SELECT SUM(quantity) AS qty
				FROM locstock
				INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
				WHERE stockid='" . $myrow['stockid'] . "'";
		$TotQtyResult = DB_query($SqlOH);
		$TotQtyRow = DB_fetch_array($TotQtyResult);

		echo '<tr class="striped_row">
			<td>' . $myrow['stockid'] . '</td>
			<td>' . $myrow['description'] . '</td>
			<td class="number">' . locale_number_format($SalesRow['qtyinvoiced'],$myrow['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format($TotQtyRow['qty'],$myrow['decimalplaces']) . '</td>
			<td class="number">' . locale_number_format($myrow['quantity'],$myrow['decimalplaces']) . '</td>
			<td class="number">';
		if ($myrow['canupd']==1) {
			echo '<input type="text" class="number" name="ReorderLevel' . $i .'" maxlength="10" size="10" value="'. locale_number_format($myrow['reorderlevel'],0) .'" />
				<input type="hidden" name="StockID' . $i . '" value="' . $myrow['stockid'] . '" /></td>
			<td><input type="text" name="BinLocation' . $i .'" maxlength="10" size="10" value="'. $myrow['bin'] .'" />';
		} else {
			echo locale_number_format($myrow['reorderlevel'],0) . '</td><td>' . $myrow['bin'] . '</td>';
		}

		echo '</td>
			</tr> ';
		$i++;
	} //end of looping
	echo'<tr>
			<td class="centre" colspan="7">
				<input type="submit" name="submit" value="' . _('Update') . '" />
			</td>
		</tr>
        </table>
        </div>
		</form>';


} else { /*The option to submit was not hit so display form */


	echo '<div class="page_help_text">' . _('Use this report to display the reorder levels for Inventory items in different categories.') . '</div><br />';

	echo '<br />
		<br />
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
		<div>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	$sql = "SELECT locations.loccode,
				   locationname
		    FROM locations INNER JOIN locationusers ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1";
	$resultStkLocs = DB_query($sql);
	echo '<table class="selection">
			<tr>
				<td>' . _('Location') . ':</td>
				<td><select name="StockLocation"> ';

	while ($myrow=DB_fetch_array($resultStkLocs)){
		echo '<option value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
	}
	echo '</select></td></tr>';

	$SQL="SELECT categoryid,
				categorydescription
			FROM stockcategory
			ORDER BY categorydescription";

	$result1 = DB_query($SQL);

	echo '<tr><td>' . _('Category') . ':</td>
				<td><select name="StockCat">';

	while ($myrow1 = DB_fetch_array($result1)) {
		echo '<option value="' . $myrow1['categoryid'] . '">' . $myrow1['categorydescription'] . '</option>';
	}

	echo '</select></td></tr>';
	echo '<tr>
			<td>' . _('Number Of Days Sales') . ':</td>
			<td><input type="text" class="number" name="NumberOfDays" maxlength="3" size="4" value="0" /></td>
          </tr>';
	echo '<tr>
			<td>' . _('Order By') . ':</td>
			<td><select name="Sequence">
				<option value="1">' .  _('Total Invoiced') . '</option>
				<option value="2">' .  _('Item Code') . '</option>
				</select></td>
		</tr>';
	echo '</table>
			<br />
			<div class="centre">
				<input type="submit" name="submit" value="' . _('Submit') . '" />
			</div>';
    echo '</div>
          </form>';

} /*end of else not submit */
include('includes/footer.php');
?>
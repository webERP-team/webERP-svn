<?php
/*Input Serial Items - used for inputing serial numbers or batch/roll/bundle references
for controlled items - used in:
- ConfirmDispatchControlledInvoice.php
- GoodsReceivedControlled.php
- StockAdjustments.php
- StockTransfers.php
- CreditItemsControlled.php

*/

//we start with a batch or serial no header and need to display something for verification...
global $TableHeader;

if (isset($_GET['LineNo'])){
	$LineNo = $_GET['LineNo'];
} elseif (isset($_POST['LineNo'])){
	$LineNo = $_POST['LineNo'];
}

/*Display the batches already entered with quantities if not serialised */

echo '<table class="selection">
		<tr><td valign="top">
			<table class="selection">';
echo $TableHeader;

$TotalQuantity = 0; /*Variable to accumulate total quantity received */
$RowCounter =0;

foreach ($LineItem->SerialItems as $Bundle){

	if ($RowCounter == 10){
		echo $TableHeader;
		$RowCounter =0;
	} else {
		$RowCounter++;
	}

	echo '<tr class="striped_row">
			<td>' . $Bundle->BundleRef . '</td>';

	if ($LineItem->Serialised==0){
		echo '<td class="number">' . locale_number_format($Bundle->BundleQty, $LineItem->DecimalPlaces) . '</td>';
	}
	if ($Perishable==1){
		echo '<td class="number">' . $Bundle->ExpiryDate . '</td>';
	}


	echo '<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=' . $Bundle->BundleRef . '&amp;StockID=' . $LineItem->StockID . '&amp;LineNo=' . $LineNo .'&amp;identifier=' . $identifier . $CreditInvoice . '">' .  _('Delete'). '</a></td>
		</tr>';

	$TotalQuantity += $Bundle->BundleQty;
}


/*Display the totals and rule off before allowing new entries */
if ($LineItem->Serialised==1){
	echo '<tr><td class="number"><b>' .  _('Total Quantity'). ': ' . locale_number_format($TotalQuantity,$LineItem->DecimalPlaces) . '</b></td></tr>';
} else {
	echo '<tr>
			<td class="number"><b>' .  _('Total Quantity'). ':</b></td>
			<td class="number"><b>' . locale_number_format($TotalQuantity,$LineItem->DecimalPlaces) . '</b></td>
		</tr>';
}

/*Close off old table */
echo '</table></td><td valign="top">';


echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?identifier=' . urlencode($identifier) . '" id="Ga6uF5Wa" method="post">
		<div>
		<input type="hidden" name="LineNo" value="' . $LineNo . '" />
		<input type="hidden" name="StockID" value="' . $StockID . '" />
		<input type="hidden" name="EntryType" value="KEYED" />
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (isset($_GET['CreditInvoice']) OR isset($_POST['CreditInvoice'])){
	echo '<input type="hidden" name="CreditInvoice" value="Yes" />';
}
/*Start a new table for the Serial/Batch ref input  in one column (as a sub table
then the multi select box for selection of existing bundle/serial nos for dispatch if applicable*/

/*in the first column add a table for the input of newies */
echo '<table class="selection">';
echo $TableHeader;

if ( isset($_GET['EditControlled']) ) {
	$EditControlled = isset($_GET['EditControlled'])?$_GET['EditControlled']:false;
} elseif ( isset($_POST['EditControlled']) ){
	$EditControlled = isset($_POST['EditControlled'])?$_POST['EditControlled']:false;
} else {
	$EditControlled=false;
}

$StartAddingAt = 0;
if ($EditControlled){
	foreach ($LineItem->SerialItems as $Bundle){

		echo '<tr>
				<td valign="top"><input type="text" name="SerialNo'. $StartAddingAt .'" value="' .$Bundle->BundleRef.'" size="21"  maxlength="20" /></td>';

		/*if the item is controlled not serialised - batch quantity required so just enter bundle refs
		into the form for entry of quantities manually */

		if ($LineItem->Serialised==1){
			echo '<input type="hidden" name="Qty' . $StartAddingAt .'" value="1" /></TR>';
		} else if ($LineItem->Serialised==0 and $Perishable==1) {
			echo '<td><input type="text" class="number" name="Qty' . $StartAddingAt .'" size="11" value="'. locale_number_format($Bundle->BundleQty, $LineItem->DecimalPlaces). '" maxlength="10" /></td><td><input type="text" name="ExpiryDate' . $StartAddingAt . '" size="11" value="' . $Bundle->ExpiryDate . '" class="date" maxlength="10" /></td></tr>';
		} else {
			echo '<td><input type="text" class="number" name="Qty' . $StartAddingAt .'" size="11" value="'. locale_number_format($Bundle->BundleQty, $LineItem->DecimalPlaces). '" maxlength="10" /></tr>';
		}

		$StartAddingAt++;
	}
}

for ($i=0;$i < 10;$i++){

	echo '<tr>
			<td valign="top"><input type="text" name="SerialNo'. ($StartAddingAt+$i) .'" size="21"  maxlength="20" /></td>';

	/*if the item is controlled not serialised - batch quantity required so just enter bundle refs
	into the form for entry of quantities manually */

	if ($LineItem->Serialised==1){
		if ($Perishable==0) {
			echo '<input type="hidden" name="Qty' . ($StartAddingAt+$i) .'" value="1" />
				</tr>';
		} else {
			echo '<td><input type="hidden" name="Qty' . ($StartAddingAt+$i) .'" value="1" />
					<input type="text" class="date" name="ExpiryDate' . ($StartAddingAt+$i) .'" size="11" value="" maxlength="10" /></td>
				</tr>';
		}
	} else if ($LineItem->Serialised==0 and $Perishable==1) {
		echo '<td><input type="text" class="number" name="Qty' . ($StartAddingAt+$i) .'" size="11"  maxlength="10" /></td>
				<td><input type="text" class="date" name="ExpiryDate' . ($StartAddingAt+$i) .'" size="11" value="" maxlength="10" /></td>
			</tr>';
	} else {
		echo '<td><input type="text" class="number" name="Qty' . ($StartAddingAt+$i) .'" size="11"  maxlength="10" /></td></tr>';
	}
}

echo '</table>
		<br />
		<div class="centre">
			<input type="submit" name="AddBatches" value="'. _('Enter'). '" />
		</div>
		</div>
		</form>
		</td>
		<td valign="top">';

if ($ShowExisting){
	include('includes/InputSerialItemsExisting.php');
}
echo '</td>
	</tr>
	</table>
	<script type="text/javascript">
//<![CDATA[
document.Ga6uF5Wa.SerialNo0.focus();
//]]>
</script>'; /*end of nested table */
?>
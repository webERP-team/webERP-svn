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
if (!isset($_SESSION['DatabaseName'])){
	die;
}

global $tableheader;

if (isset($_GET['LineNo'])){
	$LineNo = $_GET['LineNo'];
} elseif (isset($_POST['LineNo'])){
	$LineNo = $_POST['LineNo'];
}

echo '<td valign="top">';

/*Start a new table for the Serial/Batch ref input  in one column (as a sub table
then the multi select box for selection of existing bundle/serial nos for dispatch if applicable*/
//echo '<table><tr><td valign="top">';

/*in the first column add a table for the input of newies */
echo '<table>';
echo $TableHeader;


echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?identifier=' . urlencode($identifier) . '" method="post">
      <input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
      <input type="hidden" name="LineNo" value="' . $LineNo . '">
      <input type="hidden" name="StockID" value="' . $StockID . '">
      <input type="hidden" name="EntryType" value="SEQUENCE">';
if ( isset($_GET['EditControlled']) ) {
	$EditControlled = isset($_GET['EditControlled'])?$_GET['EditControlled']:false;
} elseif ( isset($_POST['EditControlled']) ){
	$EditControlled = isset($_POST['EditControlled'])?$_POST['EditControlled']:false;
}
echo '<tr>
		<td valign="top">' .  _('Begin:') . '</td>
		<td> <input type="text" name="BeginNo" size="21"  maxlength="20" value="'. $_POST['BeginNo']. '" /></td>
	</tr>';
echo '<tr>
		<td valign="top">' .  _('End:') . '</td>
		<td> <input type="text" name="EndNo" size="21"  maxlength="20"  value="'. $_POST['EndNo']. '" /></td>
	</tr>';

echo '</table>';
echo '<br /><center><input type="submit" name="AddSequence" value="'. _('Enter'). '"></center><br />';
echo '</form></td><td valign="top">';
//echo '</td></tr></table>'; /*end of nested table */
?>
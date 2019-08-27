<?php
// Z_ClearPOBackOrders.php
//

$PageSecurity =15;
include ('includes/session.php');
$Title = _('UTILITY PAGE To Clear purchase orders with quantity on back order');
include('includes/header.php');

if (isset($_POST['ClearSupplierBackOrders'])) {
	$SQL = "UPDATE purchorderdetails INNER JOIN purchorders ON purchorderdetails.orderno=purchorders.orderno SET purchorderdetails.quantityord=purchorderdetails.quantityrecd, purchorderdetails.completed=1 WHERE quantityrecd >0 AND supplierno>= '" . $_POST['FromSupplierNo'] . "' AND supplierno <= '" . $_POST['ToSupplierNo'] . "'";
	echo $SQL;
	$result = DB_query($SQL);

}
echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '
	<div class="centre">
	<table>
	<tr><td>' . _('From Supplier Code') . ':</td>
		<td><input type="text" name="FromSupplierNo" size="20" maxlength="20" /></td>
	</tr>
		<tr><td> ' . _('To Supplier Code') . ':</td>
	<td><input type="text" name="ToSupplierNo" size="20" maxlength="20" /></td>
	</tr>
	</table>
	<button type="submit" name="ClearSupplierBackOrders">' . _('Clear Supplier Back Orders') . '</button>
	<div>
	</form>';

include('includes/footer.php');
?>
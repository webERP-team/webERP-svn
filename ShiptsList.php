<?php
// ShiptsList.php
// Shows a list of all the open shipments for a selected supplier. Linked from POItems.php

//$PageSecurity = 2;
include ('includes/session.php');
$Title = _('Shipments Open Inquiry');
include('includes/header.php');

echo '<p class="page_title_text"><img src="' . $RootPath . '/css/' . $Theme . '/images/supplier.png" title="' .
				_('Supplier') . '" alt="" />' . ' ' . _('Open Shipments for').' ' . $_GET['SupplierName']. '.</p>';

if (!isset($_GET['SupplierID']) or !isset($_GET['SupplierName'])){
	echo '<br />';
	prnMsg( _('This page must be given the supplier code to look for shipments for'), 'error');
	include('includes/footer.php');
	exit;
}

$SQL = "SELECT shiptref,
		vessel,
		eta
	FROM shipments
	WHERE supplierid='" . $_GET['SupplierID'] . "'";
$ErrMsg = _('No shipments were returned from the database because'). ' - '. DB_error_msg();
$ShiptsResult = DB_query($SQL, $ErrMsg);

if (DB_num_rows($ShiptsResult)==0){
       prnMsg(_('There are no open shipments currently set up for').' ' . $_GET['SupplierName'],'warn');
	include('includes/footer.php');
       exit;
}
/*show a table of the shipments returned by the SQL */

echo '<table cellpadding="2" class="selection">';
echo '<tr>
		<th>' .  _('Reference'). '</th>
		<th>' .  _('Vessel'). '</th>
		<th>' .  _('ETA'). '</th></tr>';

$j = 1;

while ($myrow=DB_fetch_array($ShiptsResult)) {

       echo '<tr class="striped_row">
			<td><a href="'.$RootPath.'/Shipments.php?SelectedShipment='.$myrow['shiptref'].'">' . $myrow['shiptref'] . '</a></td>
       		<td>' . $myrow['vessel'] . '</td>
		<td>' . ConvertSQLDate($myrow['eta']) . '</td>
		</tr>';

}
//end of while loop

echo '</table>';

include('includes/footer.php');

?>
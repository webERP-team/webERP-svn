<?php


include ('includes/session.php');
$Title = _('Recalculation of GL Balances in Chart Details Table');
include('includes/header.php');

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

if (!isset($_POST['FromPeriod'])){

/*Show a form to allow input of criteria for TB to show */
	echo '<table>
                             <tr>
                                 <td>' . _('Select Period From') . ':</td>
                                 <td><select name="FromPeriod">';

	$sql = "SELECT periodno,
                       lastdate_in_period
                FROM periods ORDER BY periodno";
	$Periods = DB_query($sql);

	while ($myrow=DB_fetch_array($Periods)){
		echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
	}

	echo '</select></td>
             </tr>
             </table>';

	echo '<div class="centre"><input type="submit" name="recalc" value="' . _('Do the Recalculation') . '" onclick="return confirm(\'' . _('Are you sure you wish to re-post all general ledger transactions since the selected period this can take some time?') . '\');" /></div>
    </div>
    </form>';

} else {  /*OK do the updates */

	/* Make the posted flag on all GL entries including and after the period selected = 0 */
	$sql = "UPDATE gltrans SET posted=0 WHERE periodno >='" . $_POST['FromPeriod'] . "'";
	$UpdGLTransPostedFlag = DB_query($sql);

	/* Now make all the actuals 0 for all periods including and after the period from */
	$sql = "UPDATE chartdetails SET actual =0 WHERE period >= '" . $_POST['FromPeriod'] . "'";
	$UpdActualChartDetails = DB_query($sql);

	$ChartDetailBFwdResult = DB_query("SELECT accountcode, bfwd FROM chartdetails WHERE period='" . $_POST['FromPeriod'] . "'");
	while ($ChartRow=DB_fetch_array($ChartDetailBFwdResult)){
		$sql = "UPDATE chartdetails SET bfwd ='" . $ChartRow['bfwd'] . "' WHERE period > '" . $_POST['FromPeriod'] . "' AND accountcode='" . $ChartRow['accountcode'] . "'";
		$UpdActualChartDetails = DB_query($sql);
	}

	/*Now repost the lot */

	include('includes/GLPostingsZero.inc');

	prnMsg(_('All general ledger postings have been reposted from period') . ' ' . $_POST['FromPeriod'],'success');
}
include('includes/footer.php');
?>

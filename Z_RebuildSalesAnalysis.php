<?php
// Z_RebuildSalesAnalysis.php
// Script to rebuild sales analysis records from stock movements
$PageSecurity = 15;
include ('includes/session.php');
$Title = _('Rebuild sales analysis Records');
include('includes/header.php');

echo '<br /><br />' . _('This script rebuilds sales analysis records. NB: all sales budget figures will be lost!');

$result = DB_query("TRUNCATE TABLE salesanalysis");

$sql = "INSERT INTO salesanalysis (typeabbrev,
									periodno,
									amt,
									cost,
									cust,
									custbranch,
									qty,
									disc,
									stockid,
									area,
									budgetoractual,
									salesperson,
									stkcategory)
		SELECT salestype,
		(SELECT periodno FROM periods WHERE MONTH(lastdate_in_period)=MONTH(trandate) AND YEAR(lastdate_in_period)=YEAR(trandate)) as prd,
				SUM(price*-qty) as salesvalue,
				SUM(standardcost*-qty) as cost,
				stockmoves.debtorno,
				stockmoves.branchcode,
				SUM(-qty),
				SUM(-qty*price*discountpercent) AS discountvalue,
				stockmoves.stockid,
				custbranch.area,
				1,
				custbranch.salesman,
				stockmaster.categoryid
		FROM stockmoves
		INNER JOIN debtorsmaster
		ON stockmoves.debtorno=debtorsmaster.debtorno
		INNER JOIN custbranch
		ON stockmoves.debtorno=custbranch.debtorno
		AND stockmoves.branchcode=custbranch.branchcode
		INNER JOIN stockmaster
		ON stockmoves.stockid=stockmaster.stockid
        WHERE show_on_inv_crds=1
		GROUP BY salestype,
				debtorno,
				prd,
				branchcode,
				stockid,
				area,
				salesman,
				categoryid
		ORDER BY prd";

$ErrMsg = _('The sales analysis data could not be recreated because');
$Result = DB_query($sql,$ErrMsg);

echo '<p />';
prnMsg(_('The sales analsysis data has been recreated based on current stock master and customer master information'),'info');

include('includes/footer.php');
?>
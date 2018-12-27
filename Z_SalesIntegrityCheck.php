<?php


// Script to do some Sales Integrity checks
// No SQL updates or Inserts - so safe to run


include ('includes/session.php');
$Title = _('Sales Integrity');
include('includes/header.php');


echo '<div class="centre"><h3>' . _('Sales Integrity Check') . '</h3></div>';

echo '<br /><br />' . _('Check every Invoice has a Sales Order') . '<br />';
echo '<br /><br />' . _('Check every Invoice has a Tax Entry') . '<br />';
echo '<br /><br />' . _('Check every Invoice has a GL Entry') . '<br />';
$SQL = 'SELECT id, transno, order_, trandate FROM debtortrans WHERE type = 10';
$Result = DB_query($SQL);

while ($myrow = DB_fetch_array($Result)) {
	$SQL2 = "SELECT orderno, orddate FROM salesorders WHERE orderno = '" . $myrow['order_'] . "'";
	$Result2 = DB_query($SQL2);

	if ( DB_num_rows($Result2) == 0) {
		echo '<br />' . _('Invoice '). ' '. $myrow['transno'] . ' : ';
		echo '<div style="color:red">' . _('No Sales Order') . '</div>';
	}

	$SQL3 = "SELECT debtortransid FROM debtortranstaxes WHERE debtortransid = '" . $myrow['id'] . "'";
	$Result3 = DB_query($SQL3);

	if ( DB_num_rows($Result3) == 0) {
		echo '<br />' .  _('Invoice '). ' ' . $myrow['transno'] . ' : ';
		echo '<div style="color:red">' . _('Has no Tax Entry') . '</div>';
	}

	$SQL4 = "SELECT typeno
				FROM gltrans
				WHERE type = 10
				AND typeno = '" . $myrow['transno'] . "'";
	$Result4 = DB_query($SQL4);

	if ( DB_num_rows($Result4) == 0) {
		echo '<br />' . _('Invoice') . ' ' . $myrow['transno'] . ' : ';
		echo '<div style="color:red">' . _('has no GL Entry') . '</div>';
	}
}


echo '<br /><br />' . _('Check for orphan GL Entries') . '<br />';
$SQL = "SELECT DISTINCT typeno, counterindex FROM gltrans WHERE type = 10";
$Result = DB_query($SQL);

while ($myrow = DB_fetch_array($Result)) {
	$SQL2 = "SELECT id,
					transno,
					trandate
				FROM debtortrans
				WHERE type = 10
				AND transno = '" . $myrow['typeno'] . "'";
	$Result2 = DB_query($SQL2);

	if ( DB_num_rows($Result2) == 0) {
			echo "<br />"._('GL Entry ') . $myrow['counterindex'] . " : ";
			echo ', <div style="color:red">' . _('Invoice ') . $myrow['typeno'] . _(' could not be found') . '</div>';
	}
}

echo '<br /><br />' . _('Check Receipt totals') . '<br />';
$SQL = "SELECT typeno,
				amount
		FROM gltrans
		WHERE type = 12
		AND account = '" . $_SESSION['CompanyRecord']['debtorsact'] . "'";

$Result = DB_query($SQL);

while ($myrow = DB_fetch_array($Result)) {
	$SQL2 = "SELECT SUM((ovamount+ovgst)/rate)
			FROM debtortrans
			WHERE type = 12
			AND transno = '" . $myrow['typeno'] . "'";

	$Result2 = DB_query($SQL2);
	$myrow2 = DB_fetch_row($Result2);

	if ( $myrow2[0] + $myrow['amount'] == 0 ) {
			echo '<br />' . _('Receipt') . ' ' . $myrow['typeno'] . " : ";
			echo '<div style="color:red">' . $myrow['amount']. ' ' . _('in GL but found'). ' ' . $myrow2[0] . ' ' . _('in debtorstrans') . '</div>';
	}
}

echo '<br /><br />' . _('Check for orphan Receipts') . '<br />';
$SQL = "SELECT transno FROM debtortrans WHERE type = 12";
$Result = DB_query($SQL);

while ($myrow = DB_fetch_array($Result)) {
	$SQL2 = "SELECT amount FROM gltrans WHERE type = 12 AND typeno = '" . $myrow['transno'] . "'";
	$Result2 = DB_query($SQL2);
	$myrow2 = DB_fetch_row($Result2);

	if ( !$myrow2[0] ) {
		echo '<br />' . _('Receipt') . ' ' . $myrow['transno'] . " : ";
		echo '<div style="color:red">' . $myrow['transno'] . ' ' ._('not found in GL')."</div>";
	}
}


echo '<br /><br />' . _('Check for orphan Sales Orders') . '<br />';
$SQL = "SELECT orderno, orddate FROM salesorders";
$Result = DB_query($SQL);

while ($myrow = DB_fetch_array($Result)) {
	$SQL2 = "SELECT transno,
					order_,
					trandate
				FROM debtortrans
				WHERE type = 10
				AND order_ = '" . $myrow['orderno'] . "'";

	$Result2 = DB_query($SQL2);

	if ( DB_num_rows($Result2) == 0) {
		echo '<br />' . _('Sales Order') . ' ' . $myrow['orderno'] . ' : ';
		echo '<div style="color:red">' . _('Has no Invoice') . '</div>';
	}
}

echo '<br /><br />' . _('Check for orphan Order Items') . '<br />';
echo '<br /><br />' . _('Check Order Item Amounts') . '<br />';
$SQL = "SELECT orderno FROM salesorderdetails";
$Result = DB_query($SQL);

while ($myrow = DB_fetch_array($Result)) {
	$SQL2 = "SELECT orderno, orddate FROM salesorders WHERE orderno = '" . $myrow['orderno'] . "'";
	$Result2 = DB_query($SQL2);

	if ( DB_num_rows($Result2) == 0) {
			echo '<br />' . _('Order Item') . ' ' . $myrow['orderno'] . ' : ';
			echo ', <div style="color:red">' . _('Has no Sales Order') . '</div>';
	}

	$sumsql = "SELECT ROUND(SUM(qtyinvoiced * unitprice * (1 - discountpercent)), 3) AS InvoiceTotal
				FROM salesorderdetails
				WHERE orderno = '" . $myrow['orderno'] . "'";
	$sumresult = DB_query($sumsql);

	if ($sumrow = DB_fetch_array($sumresult)) {
		$invSQL = "SELECT transno,
							type,
							trandate,
							settled,
							rate,
							SUM(ovamount) AS ovamount,
							ovgst
				 	FROM debtortrans WHERE order_ = '" . $myrow['orderno'] . "'";
		$invResult = DB_query($invSQL);

		while( $invrow = DB_fetch_array($invResult) ) {
			// Ignore credit notes
			if ( $invrow['type'] != 11 ) {
					// Do an integrity check on sales order items
					if ( $sumrow['InvoiceTotal'] != $invrow['ovamount'] ) {
						echo '<br /><div style="color:red">' . _('Debtors trans') . ' ' . $invrow['ovamount'] . ' ' . _('differ from salesorderdetails') . ' ' . $sumrow['InvoiceTotal'] . '</div>';
					}
			}
		}
	}
}


echo '<br /><br />' . _('Check for orphan Stock Moves') . '<br />';
$SQL = "SELECT stkmoveno, transno FROM stockmoves";
$Result = DB_query($SQL);

while ($myrow = DB_fetch_array($Result)) {
	$SQL2 = "SELECT transno,
					order_,
					trandate
				FROM debtortrans
				WHERE type BETWEEN 10 AND 11
				AND transno = '" . $myrow['transno'] . "'";

	$Result2 = DB_query($SQL2);

	if ( DB_num_rows($Result2) == 0) {
			echo '<br />' . _('Stock Move') . ' ' . $myrow['stkmoveno'] . ' : ';
			echo ', <div style="color:red">' . _('Has no Invoice') . '</div>';
	}
}


echo '<br /><br />' . _('Check for orphan Tax Entries') . '<br />';
$SQL = "SELECT debtortransid FROM debtortranstaxes";
$Result = DB_query($SQL);

while ($myrow = DB_fetch_array($Result)) {
	$SQL2 = "SELECT id, transno, trandate FROM debtortrans WHERE type BETWEEN 10 AND 11 AND id = '" . $myrow['debtortransid'] . "'";
	$Result2 = DB_query($SQL2);

	if ( DB_num_rows($Result2) == 0) {
			echo '<br />' . _('Tax Entry') . ' ' . $myrow['debtortransid'] . ' : ';
			echo ', <div style="color:red">' . _('Has no Invoice') . '</div>';
	}
}

echo '<br /><br />' . _('Sales Integrity Check completed.') . '<br /><br />';

prnMsg(_('Sales Integrity Check completed.'),'info');

include('includes/footer.php');
?>
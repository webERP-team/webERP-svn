<?php

function Create_POS_Data_Full ($POSDebtorNo, $POSBranchCode, $PathPrefix) {

	set_time_limit(1800);
	ini_set('max_execution_time',1800);

	$result = DB_query("SELECT confvalue FROM config WHERE confname='reports_dir'");
	$ReportDirRow = DB_fetch_row($result);
	$ReportDir = $ReportDirRow[0];

	$result = DB_query("SELECT confvalue FROM config WHERE confname='DefaultPriceList'");
	$DefaultPriceListRow = DB_fetch_row($result);
	$DefaultPriceList= $DefaultPriceListRow[0];

	$result = DB_query("SELECT confvalue FROM config WHERE confname='DefaultDateFormat'");
	$DefaultDateFormatRow = DB_fetch_row($result);
	$DefaultDateFormat= $DefaultDateFormatRow[0];


	$result = DB_query("SELECT currcode, salestype FROM debtorsmaster WHERE debtorno='" . $POSDebtorNo . "'");
	$CustomerRow = DB_fetch_array($result);
	if (DB_num_rows($result)==0){
		return 0;
	}
	$CurrCode = $CustomerRow['currcode'];
	$SalesType = $CustomerRow['salestype'];


	$FileHandle = fopen($PathPrefix . $ReportDir . '/POS.sql','w');

	if ($FileHandle == false){
		return 'Cannot open file ' . $PathPrefix . $ReportDir . '/POS.sql';
	}

	fwrite($FileHandle,"UPDATE config SET configvalue='" . $DefaultDateFormat . "' WHERE configname='DefaultDateFormat';\n");

	fwrite($FileHandle,"DELETE FROM currencies;\n");
	$result = DB_query('SELECT currency, currabrev, country, hundredsname,decimalplaces, rate FROM currencies');
	while ($CurrRow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO currencies VALUES ('" . $CurrRow['currency'] . "', '" . $CurrRow['currabrev'] . "', '" . SQLite_Escape ($CurrRow['country']) . "', '" . SQLite_Escape ($CurrRow['hundredsname']) . "', '" .$CurrRow['decimalplaces'] . "', '" .$CurrRow['rate'] . "');\n");

	}

	fwrite($FileHandle,"DELETE FROM salestypes;\n");

	$result = DB_query("SELECT typeabbrev, sales_type FROM salestypes");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO salestypes VALUES ('" . $myrow['typeabbrev'] . "', '" . SQLite_Escape ($myrow['sales_type']) . "');\n");

	}
	fwrite($FileHandle,"DELETE FROM holdreasons;\n");

	$result = DB_query("SELECT reasoncode, reasondescription, dissallowinvoices FROM holdreasons");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO holdreasons VALUES ('" . $myrow['reasoncode'] . "', '" . SQLite_Escape ($myrow['reasondescription']) . "', '" . $myrow['dissallowinvoices'] . "');\n");

	}
	fwrite($FileHandle,"DELETE FROM paymentterms;\n");

	$result = DB_query("SELECT termsindicator, terms FROM paymentterms");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO paymentterms VALUES ('" . $myrow['termsindicator'] . "', '" . SQLite_Escape ($myrow['terms']) . "');\n");

	}

	fwrite($FileHandle,"DELETE FROM paymentmethods;\n");
	$result = DB_query("SELECT paymentid, paymentname,opencashdrawer FROM paymentmethods");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO paymentmethods VALUES ('" . $myrow['paymentid'] . "', '" . SQLite_Escape ($myrow['paymentname']) . "', '" . $myrow['opencashdrawer'] . "');\n");

	}

	fwrite($FileHandle,"DELETE FROM locations;\n");
	$result = DB_query("SELECT loccode, locationname,taxprovinceid FROM locations");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO locations VALUES ('" . $myrow['loccode'] . "', '" . SQLite_Escape ($myrow['locationname']) . "', '" . $myrow['taxprovinceid'] . "');\n");

	}

	fwrite($FileHandle,"DELETE FROM stockcategory;\n");
	$result = DB_query("SELECT categoryid, categorydescription FROM stockcategory");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO stockcategory VALUES ('" . $myrow['categoryid'] . "', '" . SQLite_Escape ($myrow['categorydescription']) . "');\n");

	}
	fwrite($FileHandle,"DELETE FROM taxgroups;\n");
	$result = DB_query("SELECT taxgroupid, taxgroupdescription FROM taxgroups");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO taxgroups VALUES ('" . $myrow['taxgroupid'] . "', '" . SQLite_Escape ($myrow['taxgroupdescription']) . "');\n");

	}

	fwrite($FileHandle,"DELETE FROM taxgrouptaxes;\n");
	$result = DB_query("SELECT taxgroupid, taxauthid, calculationorder, taxontax FROM taxgrouptaxes");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO taxgrouptaxes VALUES ('" . $myrow['taxgroupid'] . "', '" . $myrow['taxauthid'] . "', '" . $myrow['calculationorder'] . "', '" . $myrow['taxontax'] . "');\n");

	}
	fwrite($FileHandle,"DELETE FROM taxauthorities;\n");
	$result = DB_query("SELECT taxid, description FROM taxauthorities");
	while ($myrow = DB_fetch_array($result)) {

		  fwrite($FileHandle,"INSERT INTO taxauthorities VALUES ('" . $myrow['taxid'] . "', '" . SQLite_Escape ($myrow['description']) . "');\n");

	}
	fwrite($FileHandle,"DELETE FROM taxauthrates;\n");
	$result = DB_query("SELECT taxauthority, dispatchtaxprovince, taxcatid, taxrate FROM taxauthrates");
	while ($myrow = DB_fetch_array($result)) {
		  fwrite($FileHandle,"INSERT INTO taxauthrates VALUES ('" . $myrow['taxauthority'] . "', '" . $myrow['dispatchtaxprovince'] . "', '" . $myrow['taxcatid'] . "', '" . $myrow['taxrate'] . "');\n");

	}
	fwrite($FileHandle,"DELETE FROM stockmaster;\n");


	$result = DB_query("SELECT stockid, categoryid, description, longdescription, units, barcode, taxcatid,  decimalplaces, discountcategory FROM stockmaster WHERE (mbflag='B' OR mbflag='M' OR mbflag='D' OR mbflag='A') AND discontinued=0 AND controlled=0");

	while ($myrow = DB_fetch_array($result)) {

		fwrite($FileHandle,"INSERT INTO stockmaster VALUES ('" . SQLite_Escape($myrow['stockid']) . "', '" . SQLite_Escape($myrow['categoryid']) . "', '" . SQLite_Escape ($myrow['description']) . "', '" . SQLite_Escape(str_replace("\n", '', $myrow['longdescription'])) . "', '" . SQLite_Escape($myrow['units']) . "', '" . SQLite_Escape ($myrow['barcode']) . "', '" . $myrow['taxcatid'] . "', '" . $myrow['decimalplaces'] . "', '" . SQLite_Escape($myrow['discountcategory']) . "' );\n");
	}
	fwrite($FileHandle,"DELETE FROM prices;\n");

	$result = DB_query("SELECT prices.stockid,
								prices.typeabbrev,
								prices.currabrev,
								prices.debtorno,
								MIN(prices.price) AS lowestprice
							FROM prices INNER JOIN stockmaster
								ON prices.stockid=stockmaster.stockid
							WHERE (mbflag='B' OR mbflag='M')
							AND discontinued=0
							AND controlled=0
							AND prices.branchcode=''
							AND prices.currabrev='" . $CurrCode . "'
							AND prices.startdate <='" . Date('Y-m-d:23.59') . "'
							AND (prices.enddate >='" . Date('Y-m-d:23.59') . "' OR prices.enddate='0000-00-00')
							GROUP BY prices.stockid,
									prices.typeabbrev,
									prices.currabrev,
									prices.debtorno");
	while ($myrow = DB_fetch_array($result)) {
		fwrite($FileHandle,"INSERT INTO prices VALUES ('" . SQLite_Escape($myrow['stockid']) . "', '" . SQLite_Escape($myrow['typeabbrev']) . "', '" . SQLite_Escape($myrow['currabrev']) . "', '" . SQLite_Escape($myrow['debtorno']) . "', '" . $myrow['lowestprice'] . "', '');\n");
	}

	fwrite($FileHandle,"DELETE FROM discountmatrix;\n");
	$result = DB_query("SELECT salestype, discountcategory, quantitybreak, discountrate FROM discountmatrix");
	while ($myrow = DB_fetch_array($result)) {
		  fwrite($FileHandle,"INSERT INTO discountmatrix VALUES ('" . SQLite_Escape($myrow['salestype']) . "', '" . SQLite_Escape($myrow['discountcategory']) . "', '" . $myrow['quantitybreak'] . "', '" . $myrow['discountrate'] . "');\n");
	}

	fwrite($FileHandle,"DELETE FROM debtorsmaster;\n");
	$result = DB_query("SELECT debtorno, name, currcode, salestype, holdreason, paymentterms, discount, creditlimit, discountcode FROM debtorsmaster WHERE currcode='". $CurrCode . "'");
	while ($myrow = DB_fetch_array($result)) {
		  fwrite($FileHandle,"INSERT INTO debtorsmaster VALUES ('" . $myrow['debtorno'] . "', '" . SQLite_Escape ($myrow['name']) . "', '" . $myrow['currcode'] . "', '" . $myrow['salestype'] . "', '" . $myrow['holdreason'] . "', '" . SQLite_Escape ($myrow['paymentterms']) . "', '" . $myrow['discount'] . "', '" . $myrow['creditlimit'] . "', '" . $myrow['discountcode'] . "');\n");
	}
	fwrite($FileHandle,"DELETE FROM custbranch;\n");
	$result = DB_query("SELECT branchcode, debtorsmaster.debtorno, brname, contactname, specialinstructions,taxgroupid FROM custbranch INNER JOIN debtorsmaster ON custbranch.debtorno=debtorsmaster.debtorno WHERE debtorsmaster.currcode='". $CurrCode . "'");
	while ($myrow = DB_fetch_array($result)) {
		  fwrite($FileHandle,"INSERT INTO custbranch VALUES ('" . $myrow['branchcode'] . "', '" . $myrow['debtorno'] . "', '" . SQLite_Escape ($myrow['brname']) . "', '" . SQLite_Escape ($myrow['contactname']) . "', '" . SQLite_Escape ($myrow['specialinstructions']) . "', '" . $myrow['taxgroupid'] . "');\n");
	}
	fclose($FileHandle);
	/*Now compress to a zip archive */
	if (file_exists($PathPrefix . $ReportDir . '/POS.sql.zip')){
		unlink($PathPrefix . $ReportDir . '/POS.sql.zip');
	}
	$ZipFile = new ZipArchive();
	if ($ZipFile->open($PathPrefix . $ReportDir . '/POS.sql.zip', ZIPARCHIVE::CREATE)!==TRUE) {
		return 'couldnt open zip file ' . $PathPrefix . $ReportDir . '/POS.sql.zip';
	}
	$ZipFile->addFile($PathPrefix . $ReportDir . '/POS.sql','POS.sql');
	$ZipFile->close();
	//delete the original big sql file as we now have the zip for transferring
	unlink($PathPrefix . $ReportDir . '/POS.sql');
	set_time_limit($MaximumExecutionTime);
	ini_set('max_execution_time',$MaximumExecutionTime);
	return 1;
}

function SQLite_Escape($String) {
  $SearchCharacters  = array('&', '"', "'",'<', '>',"\n","\r");
  $ReplaceWith = array('&amp;', '""', "''", '&lt;', '&gt;', '', '&#13;');

  $String = str_replace($SearchCharacters, $ReplaceWith, $String);
  return $String;
}

function Delete_POS_Data($PathPrefix){

	$result = DB_query("SELECT confvalue FROM config WHERE confname='reports_dir'");
	$ReportDirRow = DB_fetch_row($result);
	$ReportDir = $ReportDirRow[0];


	$Success = true;
	if (file_exists($PathPrefix . $ReportDir . '/POS.sql.zip')){
		$Success = unlink($PathPrefix . $ReportDir . '/POS.sql.zip');
	}
	if (file_exists($PathPrefix . $ReportDir . '/POS.sql')){
		$Success = unlink($PathPrefix . $ReportDir . '/POS.sql');
	}
	if ($Success){
		return 1;
	} else {
		return 0;
	}
}

?>

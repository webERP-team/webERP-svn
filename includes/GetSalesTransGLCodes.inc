<?php


/*Functions to get the GL codes for customer transactions based on
$Area, $StockID to determine the stock category and the SalesType (Price List)

Function returns the relavent GL Code to post COGS entries to*/

Function GetCOGSGLAccount ($Area, $StockID, $SalesType) {

	$ErrMsg = _('Can not retrieve the cost of sales GL code because');
	$DbgMsg =_('SQL to get the cost of sales GL Code');

	/*Get the StockCategory for this item */

	$SQL = "SELECT categoryid FROM stockmaster WHERE stockid='" . $StockID . "'";
	$Result=DB_query($SQL);
	$myrow = DB_fetch_row($Result);
	$StockCategory = $myrow[0];

	/*Gets the GL Code for the COGS for a specific area and stock category. */

	$SQL = "SELECT glcode FROM cogsglpostings
			WHERE area = '" . $Area . "'
			AND stkcat = '" . $StockCategory . "'
			AND salestype='" . $SalesType . "'";
	/*Need to determine if COGS GL codes set up for the stk cat, area and sales type of the item/customer branch and 	use the most appropriate GL Code.
	If no match for all fields area, sales type, stock category then the rules for choosing the nearest match
	are

	- goes for gold a match for salestype stock category and area then -
	- matching Area, stock category and AN Sales type
	- see if matching Area, stock category - AN sales type
	- see if matching Area, saletype and ANY StockCategory
	- see if matching saletype , StockCategory	and AN Area
	- see if mathcing Area, ANY stock category and AN salestype
	- see if matching stockcategory, AN area and AN salestype
	- if still no record is found then the GL Code for the default area, sales type and default stock category is used

	*/


	$Result = DB_query($SQL,$ErrMsg,$DbgMsg);

	if (DB_num_rows($Result)==0){

		DB_free_result($Result);
		$SQL = "SELECT glcode FROM cogsglpostings
			WHERE area = '" . $Area . "'
			AND stkcat = '" . $StockCategory . "'
			AND salestype = 'AN'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);

	}

	if (DB_num_rows($Result)==0){

		DB_free_result($Result);
		$SQL = "SELECT glcode FROM cogsglpostings
			WHERE area = '" . $Area . "'
			AND stkcat = 'ANY' AND salestype = '" . $SalesType . "'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}
	if (DB_num_rows($Result)==0){

		DB_free_result($Result);
		$SQL = "SELECT glcode FROM cogsglpostings
			WHERE area = 'AN'
			AND stkcat = '" . $StockCategory . "'
			AND salestype = '" . $SalesType . "'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}

	if (DB_num_rows($Result)==0){

		DB_free_result($Result);
		$SQL = "SELECT glcode
			FROM cogsglpostings
			WHERE area = 'AN'
			AND salestype='AN'
			AND stkcat = '" . $StockCategory . "'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}

	if (DB_num_rows($Result)==0){

		DB_free_result($Result);
		$SQL = "SELECT glcode
			FROM cogsglpostings
			WHERE area = '" . $Area . "'
			AND stkcat = 'ANY'
			AND salestype='AN'";
		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}

	if (DB_num_rows($Result)==0) {
		DB_free_result($Result);
		$SQL = "SELECT glcode
				FROM cogsglpostings
                		WHERE area = 'AN'
                		AND stkcat = 'ANY'
                		AND salestype = '" . $SalesType . "'";
            $Result = DB_query($SQL,$ErrMsg,$DbgMsg);
      }

	if (DB_num_rows($Result)==0){

            DB_free_result($Result);
            $SQL = "SELECT glcode
                  FROM cogsglpostings
                  WHERE area = 'AN'
                  AND stkcat = 'ANY'
                  AND salestype='AN'";
                  $Result = DB_query($SQL,$ErrMsg,$DbgMsg);
      }

	if (DB_num_rows($Result)==0){ /*STILL!*/
		/*The default if all else fails */
		/*Check GL account 1 exists */
		prnMsg(_('Could not determine the correct general ledger account to use for posting the cost of this sale. Go to the setup menu and define appropriate COGS (Cost Of Goods Sold) accounts. To enable this invoice to be posted it has been posted to default sales and COGS - account number 1'),'warn');
		$SQL = "SELECT accountcode FROM chartmaster WHERE accountcode=1";
		$Result = DB_query($SQL);
		if (DB_num_rows($Result)==0){ /*It doesn't exist so  create it */
			$Result = DB_query("SELECT groupname FROM accountgroups WHERE groupname='Sales'");
			if (DB_num_rows($Result)==0) {
				$Result = DB_query("INSERT INTO accountgroups (groupname,
									sectioninaccounts,
									pandl,
									sequenceintb)
							VALUES ('Sales',
								1,
								1,
								5)");
			}
			$SQL = "INSERT INTO chartmaster VALUES (1, 'Default Sales and COGS', 'Sales')";
			$Result = DB_query($SQL);
		}
		return 1;
	}

	$myrow = DB_fetch_row($Result);
	Return $myrow[0];
}

Function GetSalesGLAccount ($Area, $StockID, $SalesType) {

/*Gets the  Sales GL Code for a specific area, sales type and stock category */

	$ErrMsg = _('There was a problem retrieving the sales general ledger code because');
	$DbgMsg =  _('SQL to get the sales GL Codes for sales and discounts');


		/*Get the StockCategory for this item */
	$SQL = "SELECT categoryid FROM stockmaster WHERE stockid='" . $StockID . "'";
	$Result=DB_query($SQL);
	$myrow = DB_fetch_row($Result);
	$StockCategory = $myrow[0];


	/*Need to determine if Sales GL codes set up for the stk cat, area and sales type of the item/customer branch and 	use the most appropriate GL Code.
	If no match for all fields area, sales type, stock category then the rules for choosing the nearest match
	are

	- goes for gold a match for salestype stock category and area then -
	- matching Area, stock category and AN Sales type
	- see if matching Area, stock category - AN sales type
	- see if matching Area, saletype and ANY StockCategory
	- see if mathcing Area, ANY stock category and AN salestype
	- see if matching stockcategory, AN area and AN salestype
	- if still no record is found then the GL Code for the default area, sales type and default stock category is used

	*/

	$SQL = "SELECT salesglcode,
					discountglcode
			FROM salesglpostings
			WHERE area = '" . $Area . "'
			AND stkcat = '" . $StockCategory . "'
			AND salestype = '". $SalesType . "'";

	$Result = DB_query($SQL,$ErrMsg,$DbgMsg);

	if (DB_num_rows($Result)==0){
		DB_free_result($Result);
		$SQL = "SELECT salesglcode,
						discountglcode
				FROM salesglpostings
				WHERE area = '" . $Area . "'
				AND stkcat = '" . $StockCategory . "'
				AND salestype = 'AN'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}

	if (DB_num_rows($Result)==0){
		DB_free_result($Result);
		$SQL = "SELECT salesglcode,
						discountglcode
				FROM salesglpostings
				WHERE area = '" . $Area . "'
				AND stkcat = 'ANY'
				AND salestype = '" . $SalesType . "'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}

	if (DB_num_rows($Result)==0){
		DB_free_result($Result);
		$SQL = "SELECT salesglcode,
						discountglcode
				FROM salesglpostings
				WHERE area = 'AN'
				AND salestype='" . $SalesType . "'
				AND stkcat = '" . $StockCategory . "'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}

	if (DB_num_rows($Result)==0){
		DB_free_result($Result);
		$SQL = "SELECT salesglcode,
						discountglcode
				FROM salesglpostings
				WHERE area = 'AN'
				AND salestype='AN'
				AND stkcat = '" . $StockCategory . "'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}

	if (DB_num_rows($Result)==0){
		DB_free_result($Result);
		$SQL = "SELECT salesglcode,
						discountglcode
				FROM salesglpostings
				WHERE area = '" . $Area . "'
				AND stkcat = 'ANY'
				AND salestype='AN'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}

        if (DB_num_rows($Result)==0) {
    		DB_free_result($Result);
        	$SQL = "SELECT salesglcode,
							discountglcode
                	FROM salesglpostings
                	WHERE area = 'AN'
                	AND stkcat = 'ANY'
                	AND salestype = '" . $SalesType . "'";
        	$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
        }

	if (DB_num_rows($Result)==0){

		DB_free_result($Result);
		$SQL = "SELECT salesglcode,
						discountglcode
				FROM salesglpostings
				WHERE area = 'AN'
				AND stkcat = 'ANY'
				AND salestype='AN'";

		$Result = DB_query($SQL,$ErrMsg,$DbgMsg);
	}
	if (DB_num_rows($Result)==0){ /*STILL!*/
		/*The default if all else fails */
		prnMsg(_('Could not determine the correct general ledger account to use for posting this sale to. Go to the setup menu and define appropriate Sale GL Posting accounts. To enable this invoice to be posted it has been posted to default sales and COGS - account number 1'),'warn');
		/*Check GL account 1 exists */
		$SQL = "SELECT accountcode FROM chartmaster WHERE accountcode=1";
		$Result = DB_query($SQL);
		if (DB_num_rows($Result)==0){ /*It doesn't exist so  create it */
			/*First check the account group sales exists */
			$Result = DB_query("SELECT groupname FROM accountgroups WHERE groupname='Sales'");
			if (DB_num_rows($Result)==0) {
				$Result = DB_query("INSERT INTO accountgroups (groupname,
																sectioninaccounts,
																pandl,
																sequenceintb)
														VALUES ('Sales',
																1,
																1,
																5)");
			}
			$SQL = "INSERT INTO chartmaster VALUES (1, 'Default Sales and COGS', 'Sales')";
			$Result = DB_query($SQL);
		}
		return array('salesglcode'=>1,
					'discountglcode'=>1);
	}
	$myrow = DB_fetch_array($Result);
	return $myrow;
}

?>

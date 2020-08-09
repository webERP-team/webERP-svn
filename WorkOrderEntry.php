<?php
/* Entry of new work orders */

include('includes/session.php');
$Title = _('Work Order Entry');
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');

echo '<p class="page_title_text"><img alt="" src="', $RootPath, '/css/', $Theme,
	'/images/transactions.png" title="', // Icon image.
	$Title, '" /> ', // Icon title.
	$Title, '</p>';// Page title.

if(isset($_GET['WO'])) {
	$SelectedWO = $_GET['WO'];
} elseif(isset($_POST['WO'])) {
	$SelectedWO = $_POST['WO'];
} else {
	unset($SelectedWO);
}

if(isset($_GET['ReqDate'])) {
	$ReqDate = $_GET['ReqDate'];
} else {
	$ReqDate=Date('Y-m-d');
}

if(isset($_GET['StartDate'])) {
	$StartDate = $_GET['StartDate'];
} else {
	$StartDate=Date('Y-m-d');
}

if(isset($_GET['loccode'])) {
	$LocCode = $_GET['loccode'];
} else {
	$LocCode=$_SESSION['UserStockLocation'];
}

$LocResult = DB_query("SELECT locations.loccode FROM locations
						INNER JOIN locationusers ON locationusers.loccode=locations.loccode
						AND locationusers.userid='" .  $_SESSION['UserID'] . "'
						AND locationusers.canupd=1
						WHERE locations.loccode='" . $LocCode . "'");
$LocRow = DB_fetch_array($LocResult);

if(is_null($LocRow['loccode']) OR $LocRow['loccode']=='') {
	prnMsg(_('Your security settings do not allow you to create or update new Work Order at this location') . ' ' . $LocCode,'error');
	echo '<br /><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select an existing work order') . '</a>';
	include('includes/footer.php');
	exit;
}

foreach ($_POST as $key=>$value) {
	if(substr($key, 0, 9)=='OutputQty' OR substr($key, 0, 7)=='RecdQty') {
		$_POST[$key] = filter_number_format($value);
	}
}

// check for new or modify condition
if(isset($SelectedWO) AND$SelectedWO!='') {
	// modify
	$_POST['WO'] = (int)$SelectedWO;
	$EditingExisting = true;
} else {
	if(!isset($_POST['Ref'])) {
		$Ref = '';
	}
	if(!isset($_POST['Remark'])) {
		$Remark = '';
	}

	// new
	$_POST['WO'] = GetNextTransNo(40);
	$SQL = "INSERT INTO workorders (wo,
									loccode,
									requiredby,
									startdate,
									reference,
									remark)
								VALUES (
									'" . $_POST['WO'] . "',
									'" . $LocCode . "',
									'" . $ReqDate . "',
									'" . $StartDate. "',
									'" . $Ref . "',
									'" . $Remark . "')";
	$InsWOResult = DB_query($SQL);
}


if(isset($_GET['NewItem'])) {
	$NewItem = $_GET['NewItem'];
}
if(isset($_GET['ReqQty'])) {
	$ReqQty = $_GET['ReqQty'];
}
if(!isset($_POST['StockLocation'])) {
	if(isset($LocCode)) {
		$_POST['StockLocation']=$LocCode;
	} elseif(isset($_SESSION['UserStockLocation'])) {
		$_POST['StockLocation']=$_SESSION['UserStockLocation'];
	}
}



if(isset($_POST['Search']) OR isset($_POST['Prev']) OR isset($_POST['Next'])) {

	If ($_POST['Keywords'] AND $_POST['StockCode']) {
		prnMsg(_('Stock description keywords have been used in preference to the Stock code extract entered'),'warn');
	}
	if(mb_strlen($_POST['SO'])>0) {
		$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units,
						stockmaster.controlled,
						salesorderdetails.quantity
						FROM salesorderdetails
						INNER JOIN stockmaster
							ON salesorderdetails.stkcode=stockmaster.stockid
						WHERE salesorderdetails.orderno='" . $_POST['SO'] . "'
						ORDER BY stockmaster.stockid
							";
	} elseif(mb_strlen($_POST['CustomerRef'])>0) {
		$SQL = "SELECT stockmaster.stockid,
						stockmaster.description,
						stockmaster.units,
						stockmaster.controlled,
						salesorderdetails.quantity
						FROM salesorderdetails
						INNER JOIN salesorders
							ON salesorderdetails.orderno=salesorders.orderno
						INNER JOIN stockmaster
							ON salesorderdetails.stkcode=stockmaster.stockid
						WHERE salesorders.customerref='" . $_POST['CustomerRef'] . "'
						ORDER BY stockmaster.stockid";
	} elseIf (mb_strlen($_POST['Keywords'])>0) {
			//insert wildcard characters in spaces
		$_POST['Keywords'] = mb_strtoupper($_POST['Keywords']);
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		if($_POST['StockCat']=='All') {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units,
							stockmaster.controlled
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.description " . LIKE . " '" . $SearchString . "'
							AND stockmaster.discontinued=0
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units,
							stockmaster.controlled
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.discontinued=0
							AND stockmaster.description " . LIKE . " '" . $SearchString . "'
							AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		}

	} elseif(mb_strlen($_POST['StockCode'])>0) {

		$_POST['StockCode'] = mb_strtoupper($_POST['StockCode']);
		$SearchString = '%' . $_POST['StockCode'] . '%';

		/* Only items of stock type F finished goods or M - raw materials can have work orders created - raw materials can include the manufacture of components (as noted by Bob Thomas! */
		if($_POST['StockCat']=='All') {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units,
							stockmaster.controlled
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
							AND stockmaster.discontinued=0
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units,
							stockmaster.controlled
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.stockid " . LIKE . " '" . $SearchString . "'
							AND stockmaster.discontinued=0
							AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		}
	} else {
		if($_POST['StockCat']=='All') {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units,
							stockmaster.controlled
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.discontinued=0
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		} else {
			$SQL = "SELECT  stockmaster.stockid,
							stockmaster.description,
							stockmaster.units,
							stockmaster.controlled
						FROM stockmaster
						INNER JOIN stockcategory
							ON stockmaster.categoryid=stockcategory.categoryid
						WHERE (stockcategory.stocktype='F' OR stockcategory.stocktype='M')
							AND stockmaster.discontinued=0
							AND stockmaster.categoryid='" . $_POST['StockCat'] . "'
							AND mbflag='M'
						ORDER BY stockmaster.stockid";
		  }
	}

	$SQLCount = substr($SQL,strpos($SQL,   "FROM"));
	$SQLCount = substr($SQLCount,0, strpos($SQLCount,   "ORDER"));
	$SQLCount = 'SELECT COUNT(*) '.$SQLCount;
	$ErrMsg = _('Failed to retrieve item number');
	$SearchResult = DB_query($SQLCount,$ErrMsg);

	$myrow=DB_fetch_array($SearchResult);
	DB_free_result($SearchResult);
	unset($SearchResult);
	$ListCount = $myrow[0];
	if($ListCount>0) {
		$ListPageMax = ceil($ListCount / $_SESSION['DisplayRecordsMax'])-1;
	} else {
		$ListPageMax =1;
	}


	if(isset($_POST['Next'])) {
		$Offset = $_POST['CurrPage']+1;
	}
	if(isset($_POST['Prev'])) {
		$Offset = $_POST['CurrPage']-1;
	}
	if(!isset($Offset)) {
		$Offset=0;
	}
	if($Offset<0) {
		$Offset=0;
	}
	if($Offset>$ListPageMax) {
		$Offset=$ListPageMax;
	}
	$SQL = $SQL . ' LIMIT ' . $_SESSION['DisplayRecordsMax'].' OFFSET ' . strval($_SESSION['DisplayRecordsMax']*$Offset);


	$ErrMsg = _('There is a problem selecting the part records to display because');
	$DbgMsg = _('The SQL used to get the part selection was');
	$SearchResult = DB_query($SQL,$ErrMsg, $DbgMsg);

	if(DB_num_rows($SearchResult)==0 ) {
		prnMsg (_('There are no products available meeting the criteria specified'),'info');

		if($debug==1) {
			prnMsg(_('The SQL statement used was') . ':<br />' . $SQL,'info');
		}
	}
	if(DB_num_rows($SearchResult)==1) {
		$myrow=DB_fetch_array($SearchResult);
		$NewItem = $myrow['stockid'];
		if(isset($myrow['quantity'])) {
			$ReqQty = $myrow['quantity'];
		}
		DB_data_seek($SearchResult,0);
	}

} //end of if search
if(isset($_POST['Add'])) {
	foreach($_POST as $key=>$value) {
		if(strpos($key,'Check_')!==false) {
			$NewItem[] = array('item'=>$value,'qty'=>0);
		}
		if(strpos($key,'Qty_') !== false AND $value>0) {
			$No = substr($key,4);
			$NewItem[] = array('item'=>$_POST['Item_'.$No],'qty'=>$value);
		}
	}
}


if(isset($NewItem) AND isset($_POST['WO'])) {
	if(!is_array($NewItem)) {
		$InputError=false;
		$CheckItemResult = DB_query("SELECT mbflag,
										eoq,
										controlled
									FROM stockmaster
									WHERE stockid='" . $NewItem . "'");
		if(DB_num_rows($CheckItemResult)==1) {
			$CheckItemRow = DB_fetch_array($CheckItemResult);
			if($CheckItemRow['controlled']==1 AND $_SESSION['DefineControlledOnWOEntry']==1) { //need to add serial nos or batches to determine quantity
				$EOQ = 0;
			} else {
				if(!isset($ReqQty)) {
					$ReqQty=$CheckItemRow['eoq'];
				}
				$EOQ = $ReqQty;
			}
			if($CheckItemRow['mbflag']!='M') {
				prnMsg(_('The item selected cannot be added to a work order because it is not a manufactured item'),'warn');
				$InputError=true;
			}
		} else {
			prnMsg(_('The item selected cannot be found in the database'),'error');
			$InputError = true;
		}
		$CheckItemResult = DB_query("SELECT stockid
									FROM woitems
									WHERE stockid='" . $NewItem . "'
										AND wo='" .$_POST['WO'] . "'");
		if(DB_num_rows($CheckItemResult)==1) {
			prnMsg(_('This item is already on the work order and cannot be added again'),'warn');
			$InputError=true;
		}


		if($InputError==false) {
			$CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost,
									bom.loccode
									FROM stockmaster
									INNER JOIN bom
										ON stockmaster.stockid=bom.component
									WHERE bom.parent='" . $NewItem . "'
										AND bom.loccode=(SELECT loccode FROM workorders WHERE wo='" . $_POST['WO'] . "')
										AND bom.effectiveafter<='" . Date('Y-m-d') . "'
										AND bom.effectiveto>='" . Date('Y-m-d') . "'");
			$CostRow = DB_fetch_array($CostResult);
			if(is_null($CostRow['cost'])) {
					$Cost =0;
					prnMsg(_('The cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
			} elseif($CostRow['cost'] == 0) {
					$Cost = 0;
					prnMsg(_('The cost of this item as accumulated from the sum of the component costs is zero'),'warn');
			} else {
					$Cost = $CostRow['cost'];
			}
			if(!isset($EOQ)) {
				$EOQ=1;
			}

			$Result = DB_Txn_Begin();

			// insert parent item info
			$SQL = "INSERT INTO woitems (wo,
									 stockid,
									 qtyreqd,
									 stdcost)
								VALUES (
									 '" . $_POST['WO'] . "',
									 '" . $NewItem . "',
									 '" . $EOQ . "',
									 '" . $Cost . "'
								)";
			$ErrMsg = _('The work order item could not be added');
			$result = DB_query($SQL,$ErrMsg);

			//Recursively insert real component requirements - see includes/SQL_CommonFunctions.in for function WoRealRequirements
			WoRealRequirements($_POST['WO'], $CostRow['loccode'], $NewItem);

			$result = DB_Txn_Commit();

			unset($NewItem);
		}
	} else {
		DB_txn_begin();
		foreach ($NewItem as $ItemDetail) {
				$Itm = $ItemDetail['item'];
				if($ItemDetail['qty']>0) {
					$ReqQty = $ItemDetail['qty'];
				}
				$InputError=false;
				$CheckItemResult = DB_query("SELECT mbflag,
										eoq,
										controlled
									FROM stockmaster
									WHERE stockid='" . $Itm . "'");

		if(DB_num_rows($CheckItemResult)==1) {
			$CheckItemRow = DB_fetch_array($CheckItemResult);
			if($CheckItemRow['controlled']==1 AND $_SESSION['DefineControlledOnWOEntry']==1) { //need to add serial nos or batches to determine quantity
				$EOQ = 0;
			} else {
				if(!isset($ReqQty)) {
					$ReqQty=$CheckItemRow['eoq'];
				}
				$EOQ = $ReqQty;
			}
			if($CheckItemRow['mbflag']!='M') {
				prnMsg(_('The item selected cannot be added to a work order because it is not a manufactured item'),'warn');
				$InputError=true;
			}
		} else {
			prnMsg(_('The item selected cannot be found in the database'),'error');
			$InputError = true;
		}
		$CheckItemResult = DB_query("SELECT stockid
									FROM woitems
									WHERE stockid='" . $Itm . "'
										AND wo='" .$_POST['WO'] . "'"
									);
		if(DB_num_rows($CheckItemResult)==1) {
		prnMsg(_('This item is already on the work order and cannot be added again'),'warn');
		$InputError=true;
	}


	if($InputError==false) {
			$CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost,
									bom.loccode
									FROM stockmaster
									INNER JOIN bom
										ON stockmaster.stockid=bom.component
									WHERE bom.parent='" . $Itm . "'
										AND bom.loccode=(SELECT loccode FROM workorders WHERE wo='" . $_POST['WO'] . "')
										AND bom.effectiveafter<='" . Date('Y-m-d') . "'
										AND bom.effectiveto>='" . Date('Y-m-d') . "'");


			$CostRow = DB_fetch_array($CostResult);
			if(is_null($CostRow['cost'])) {
					$Cost =0;
					prnMsg(_('The cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
			} elseif($CostRow['cost'] == 0) {
					$Cost = 0;
					prnMsg(_('The The cost of this item as accumulated from the sum of the component costs is zero'),'warn');
	        	} else {
					$Cost = $CostRow['cost'];
			}
			if(!isset($EOQ)) {
				$EOQ=1;
			}


			// insert parent item info
			$SQL = "INSERT INTO woitems (wo,
									 stockid,
									 qtyreqd,
									 stdcost)
								VALUES (
									 '" . $_POST['WO'] . "',
									 '" . $Itm . "',
									 '" . $EOQ . "',
									 '" . $Cost . "'
								)";
			$ErrMsg = _('The work order item could not be added');
			$result = DB_query($SQL,$ErrMsg);
			//Recursively insert real component requirements - see includes/SQL_CommonFunctions.in for function WoRealRequirements
			WoRealRequirements($_POST['WO'], $CostRow['loccode'], $Itm);
		} //end if there were no input errors
		else {
			DB_txn_rollback();
			}
	}//end of foreach loop;

		DB_txn_commit();
		unset($NewItem);
	}
} //adding a new item to the work order


if(isset($_POST['submit']) OR isset($_POST['Search'])) { //The update button has been clicked

	echo '<div class="centre"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') .'">' . _('Enter a new work order') . '</a>';
	echo '<br /><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select an existing work order') . '</a>';
	echo '<br /><a href="'. $RootPath . '/WorkOrderCosting.php?WO=' .  $SelectedWO . '">' . _('Go to Costing'). '</a></div>';

	$Input_Error = false; //hope for the best
	 for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++) {
	   	if(!is_numeric($_POST['OutputQty'.$i])) {
		   	prnMsg(_('The quantity entered must be numeric'),'error');
			$Input_Error = true;
		} elseif($_POST['OutputQty'.$i]<=0) {
			prnMsg(_('The quantity entered must be a positive number greater than zero'),'error');
			$Input_Error = true;
		}
	 }
	 if(!Is_Date($_POST['RequiredBy'])) {
		prnMsg(_('The required by date entered is in an invalid format'),'error');
		$Input_Error = true;
	 }

	if($Input_Error == false) {

		$SQL_ReqDate = FormatDateForSQL($_POST['RequiredBy']);
		$QtyRecd=0;

		for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++) {
				$QtyRecd+=$_POST['RecdQty'.$i];
		}
		unset($SQL);

		if($QtyRecd==0) { //can only change factory location if Qty Recd is 0
				$SQL[] = "UPDATE workorders SET requiredby='" . $SQL_ReqDate . "',
												startdate='" . FormatDateForSQL($_POST['StartDate']) . "',
												loccode='" . $_POST['StockLocation'] . "',
												reference='" . $_POST['Ref'] . "',
												remark='" . $_POST['Remark'] . "'
											WHERE wo='" . $_POST['WO'] . "'";
		} else {
				prnMsg(_('The factory where this work order is made can only be updated if the quantity received on all output items is 0'),'warn');
				$SQL[] = "UPDATE workorders SET requiredby='" . $SQL_ReqDate . "',
												startdate='" . FormatDateForSQL($_POST['StartDate']) . "',
												reference='" . $_POST['Ref'] . "',
												remark='" . $_POST['Remark'] . "'
											WHERE wo='" . $_POST['WO'] . "'";
		}

		for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++) {
			if(!isset($_POST['NextLotSNRef'.$i])) {
				$_POST['NextLotSNRef'.$i]='';
			}
			if(!isset($_POST['WOComments'.$i])) {
				$_POST['WOComments'.$i]='';
			}
			$SQL[] = "UPDATE woitems SET comments = '". $_POST['WOComments'.$i] ."'
										WHERE wo='" . $_POST['WO'] . "'
										AND stockid='" . $_POST['OutputItem'.$i] . "'";
			if(isset($_POST['QtyRecd'.$i]) AND $_POST['QtyRecd'.$i]>$_POST['OutputQty'.$i]) {
				$_POST['OutputQty'.$i]=$_POST['QtyRecd'.$i]; //OutputQty must be >= Qty already reced
			}
			if($_POST['RecdQty'.$i]==0 AND (!isset($_POST['HasWOSerialNos'.$i]) OR $_POST['HasWOSerialNos'.$i]==false)) {
				/* can only change location cost if QtyRecd=0 */
				$CostResult = DB_query("SELECT SUM((materialcost+labourcost+overheadcost)*bom.quantity) AS cost,bom.loccode
												FROM stockmaster
												INNER JOIN bom ON stockmaster.stockid=bom.component
												WHERE bom.parent='" . $_POST['OutputItem'.$i] . "'
												AND bom.loccode=(SELECT loccode FROM workorders WHERE wo='" . $_POST['WO'] . "')
												AND bom.effectiveafter<='" . Date('Y-m-d') . "'
												AND bom.effectiveto>='" . Date('Y-m-d') . "'");
				$CostRow = DB_fetch_array($CostResult);
				if(is_null($CostRow['cost'])) {
					$Cost =0;
					prnMsg(_('The cost of this item as accumulated from the sum of the component costs is nil. This could be because there is no bill of material set up ... you may wish to double check this'),'warn');
				} else {
					$Cost = $CostRow['cost'];
				}
				$SQL[] = "UPDATE woitems SET qtyreqd =  '". $_POST['OutputQty' . $i] . "',
											 nextlotsnref = '". $_POST['NextLotSNRef'.$i] ."',
											 stdcost ='" . $Cost . "'
										WHERE wo='" . $_POST['WO'] . "'
										AND stockid='" . $_POST['OutputItem'.$i] . "'";
  			} elseif(isset($_POST['HasWOSerialNos'.$i]) AND $_POST['HasWOSerialNos'.$i]==false) {
				$SQL[] = "UPDATE woitems SET qtyreqd =  '". $_POST['OutputQty' . $i] . "',
											 nextlotsnref = '". $_POST['NextLotSNRef'.$i] ."'
										WHERE wo='" . $_POST['WO'] . "'
										AND stockid='" . $_POST['OutputItem'.$i] . "'";
			}
		}

		//run the SQL from either of the above possibilites
		$ErrMsg = _('The work order could not be added/updated');
		foreach ($SQL as $SQL_stmt) {
		//	echo '<br />' . $SQL_stmt;
			$result = DB_query($SQL_stmt,$ErrMsg);

		}
		if(!isset($_POST['Search'])) {
			prnMsg(_('The work order has been updated'),'success');
		}

		for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++) {
		  		 unset($_POST['OutputItem'.$i]);
				 unset($_POST['OutputQty'.$i]);
				 unset($_POST['QtyRecd'.$i]);
				 unset($_POST['NetLotSNRef'.$i]);
				 unset($_POST['HasWOSerialNos'.$i]);
				 unset($_POST['WOComments'.$i]);
		}
	}
} elseif(isset($_POST['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

	$CancelDelete=false; //always assume the best

	// can't delete it there are open work issues
	$HasTransResult = DB_query("SELECT transno
									FROM stockmoves
								WHERE (stockmoves.type= 26 OR stockmoves.type=28)
								AND reference " . LIKE  . " '%" . $_POST['WO'] . "%'");
	if(DB_num_rows($HasTransResult)>0) {
		prnMsg(_('This work order cannot be deleted because it has issues or receipts related to it'),'error');
		$CancelDelete=true;
	}

	if($CancelDelete==false) { //ie all tests proved ok to delete
		DB_Txn_Begin();
		$ErrMsg = _('The work order could not be deleted');
		$DbgMsg = _('The SQL used to delete the work order was');
		//delete the worequirements
		$SQL = "DELETE FROM worequirements WHERE wo='" . $_POST['WO'] . "'";
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		//delete the items on the work order
		$SQL = "DELETE FROM woitems WHERE wo='" . $_POST['WO'] . "'";
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		//delete the controlled items defined in wip
		$SQL="DELETE FROM woserialnos WHERE wo='" . $_POST['WO'] . "'";
		$ErrMsg=_('The work order serial numbers could not be deleted');
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		// delete the actual work order
		$SQL="DELETE FROM workorders WHERE wo='" . $_POST['WO'] . "'";
		$ErrMsg=_('The work order could not be deleted');
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

		DB_Txn_Commit();
		prnMsg(_('The work order has been cancelled'),'success');


		echo '<p><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select an existing outstanding work order') . '</a></p>';
		unset($_POST['WO']);
		for ($i=1;$i<=$_POST['NumberOfOutputs'];$i++) {
			unset($_POST['OutputItem'.$i]);
			unset($_POST['OutputQty'.$i]);
			unset($_POST['QtyRecd'.$i]);
			unset($_POST['NetLotSNRef'.$i]);
			unset($_POST['HasWOSerialNos'.$i]);
			unset($_POST['WOComments'.$i]);
		}
		include('includes/footer.php');
		exit;
	}
}
if(isset($_GET['Delete'])) {
	$CancelDelete=false; //always assume the best

	// can't delete it there are open work issues
	$HasTransResult = DB_query("SELECT transno
									FROM stockmoves
								WHERE (stockmoves.type= 26 OR stockmoves.type=28)
								AND reference " . LIKE  . " '%" . $_POST['WO'] . "%'");
	if(DB_num_rows($HasTransResult)>0) {
		prnMsg(_('This work order cannot be deleted because it has issues or receipts related to it'),'error');
		$CancelDelete=true;
	}
	//delete items
	if($CancelDelete===false) {
	//delete items
		DB_Txn_Begin();
		$ErrMsg = _('The work order could not be deleted');
		$DbgMsg = _('The SQL used to delete the work order was');
		//delete the worequirements
		$SQL = "DELETE FROM worequirements WHERE wo='" . $_GET['WO'] . "' AND parentstockid='" . $_GET['StockID'] . "'";
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		//delete the item on the work order
		$SQL = "DELETE FROM woitems WHERE wo='" . $_GET['WO'] . "' AND stockid='" . $_GET['StockID'] . "' ";
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		//delete the controlled items defined in wip
		$SQL="DELETE FROM woserialnos WHERE wo='" . $_GET['WO'] . "' AND stockid='" . $_GET['StockID'] . "' ";
		$ErrMsg=_('The work order serial numbers could not be deleted');
		$result = DB_query($SQL,$ErrMsg,$DbgMsg,true);
		DB_Txn_Commit();
		prnMsg(_('The item in this work order has been cancelled'),'success');
		header('Location: '. htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?WO=' . $_GET['WO']);
	}
}


echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" name="form1">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<br /><table class="selection">';

$SQL="SELECT workorders.loccode,
			 requiredby,
			 startdate,
			 costissued,
			 closed,
			 reference,
			 remark
		FROM workorders	INNER JOIN locations
		ON workorders.loccode=locations.loccode
		INNER JOIN locationusers ON locationusers.loccode=workorders.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canupd=1
		WHERE workorders.wo='" . $_POST['WO'] . "'";

$WOResult = DB_query($SQL);
if(DB_num_rows($WOResult)==1) {

	$myrow = DB_fetch_array($WOResult);
	$_POST['StartDate'] = ConvertSQLDate($myrow['startdate']);
	$_POST['CostIssued'] = $myrow['costissued'];
	$_POST['Closed'] = $myrow['closed'];
	$_POST['RequiredBy'] = ConvertSQLDate($myrow['requiredby']);
	$_POST['StockLocation'] = $myrow['loccode'];
	$_POST['Ref'] = $myrow['reference'];
	$_POST['Remark'] = $myrow['remark'];
	$ErrMsg =_('Could not get the work order items');
	$WOItemsResult = DB_query("SELECT   woitems.stockid,
										stockmaster.description,
										qtyreqd,
										qtyrecd,
										stdcost,
										nextlotsnref,
										controlled,
										serialised,
										stockmaster.decimalplaces,
										nextserialno,
										woitems.comments
								FROM woitems INNER JOIN stockmaster
								ON woitems.stockid=stockmaster.stockid
								WHERE wo='" .$_POST['WO'] . "'",
								$ErrMsg);

	$NumberOfOutputs=DB_num_rows($WOItemsResult);
	$i=1;
	while ($WOItem=DB_fetch_array($WOItemsResult)) {
				$_POST['OutputItem' . $i]=$WOItem['stockid'];
				$_POST['OutputItemDesc'.$i]=$WOItem['description'];
				$_POST['OutputQty' . $i]= $WOItem['qtyreqd'];
		  		$_POST['RecdQty' .$i] =$WOItem['qtyrecd'];
				$_POST['WOComments' .$i] =$WOItem['comments'];
		  		$_POST['DecimalPlaces' . $i] = $WOItem['decimalplaces'];
		  		if($WOItem['serialised']==1 AND $WOItem['nextserialno']>0) {
		  		   $_POST['NextLotSNRef' .$i]=$WOItem['nextserialno'];
		  		} else {
				   $_POST['NextLotSNRef' .$i]=$WOItem['nextlotsnref'];
				}
		  		$_POST['Controlled'.$i] =$WOItem['controlled'];
		  		$_POST['Serialised'.$i] =$WOItem['serialised'];
		  		$HasWOSerialNosResult = DB_query("SELECT wo FROM woserialnos WHERE wo='" . $_POST['WO'] . "'");
		  		if(DB_num_rows($HasWOSerialNosResult)>0) {
		  		   $_POST['HasWOSerialNos']=true;
		  		} else {
				   $_POST['HasWOSerialNos']=false;
				}
		  		$i++;
	}
} else {
	if($EditingExisting==true) {
		prnMsg(_('Your location security settings do not allow you to Update this Work Order'),'error');
		echo '<br /><a href="' . $RootPath . '/SelectWorkOrder.php">' . _('Select an existing work order') . '</a>';
		include('includes/footer.php');
		exit;
	}

}

echo '<input type="hidden" name="WO" value="' .$_POST['WO'] . '" />';
echo '<tr><td class="label">' . _('Work Order Reference') . ':</td><td>' . $_POST['WO'] . '</td></tr>';
echo '<tr><td class="label">' . _('Factory Location') .':</td>
	<td><select name="StockLocation" onChange="ReloadForm(form1.submit)">';
$LocResult = DB_query("SELECT locations.loccode,locationname
						FROM locations
						INNER JOIN locationusers
							ON locationusers.loccode=locations.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "'
							AND locationusers.canupd=1
						WHERE locations.usedforwo = 1");
while ($LocRow = DB_fetch_array($LocResult)) {
	if($_POST['StockLocation']==$LocRow['loccode']) {
		echo '<option selected="True" value="' . $LocRow['loccode'] .'">' . $LocRow['locationname'] . '</option>';
	} else {
		echo '<option value="' . $LocRow['loccode'] .'">' . $LocRow['locationname'] . '</option>';
	}
}
echo '</select></td></tr>';
if(!isset($_POST['StartDate'])) {
	$_POST['StartDate'] = Date($_SESSION['DefaultDateFormat']);
}

echo '<tr>
		<td class="label">' . _('Start Date') . ':</td>
		<td><input type="text" name="StartDate" size="12" maxlength="12" value="' . $_POST['StartDate'] .'" class="date" /></td>
	</tr>';

if(!isset($_POST['RequiredBy'])) {
	$_POST['RequiredBy'] = Date($_SESSION['DefaultDateFormat']);
}

echo '<tr>
		<td class="label">' . _('Required By') . ':</td>
		<td><input type="text" name="RequiredBy" size="12" maxlength="12" value="' . $_POST['RequiredBy'] .'" class="date" /></td>
	</tr>';
if(!isset($_POST['Ref'])) {
	$_POST['Ref'] = '';
}
echo '<tr>
		<td class="label">' . _('Reference') . ':</td>
		<td><input type="text" name="Ref"  value="' . $_POST['Ref'] . '" size="12" maxlength="40" /><td>
	</tr>';
if(!isset($_POST['Remark'])) {
	$_POST['Remark'] = '';
}
echo '<tr>
		<td class="label">' . _('Remark') . ':</td>
		<td><textarea name="Remark" >' . $_POST['Remark'] . '</textarea></td>
		</tr>';


if(isset($WOResult)) {
	echo '<tr><td class="label">' . _('Accumulated Costs') . ':</td>
			  <td class="number">' . locale_number_format($myrow['costissued'],$_SESSION['CompanyRecord']['decimalplaces']) . '</td></tr>';
}
echo '</table>
		<br /><table class="selection">';
echo '<tr><th>' . _('Output Item') . '</th>
		  <th>' . _('Comments') . '</th>
		  <th>' . _('Qty Required') . '</th>
		  <th>' . _('Qty Received') . '</th>
		  <th>' . _('Balance Remaining') . '</th>
		  <th>' . _('Next Lot/SN Ref') . '</th>
		  </tr>';

if(isset($NumberOfOutputs)) {
	for ($i=1;$i<=$NumberOfOutputs;$i++) {
		echo '<tr class="striped_row">
			<td><input type="hidden" name="OutputItem' . $i . '" value="' . $_POST['OutputItem' .$i] . '" />' .
			$_POST['OutputItem' . $i] . ' - ' . $_POST['OutputItemDesc' .$i] . '
			</td>';
		echo'<td><textarea style="width:100%" rows="5" cols="20" name="WOComments' . $i . '" >' . $_POST['WOComments' . $i] . '</textarea>
			</td>';
		if($_POST['Controlled'.$i]==1 AND $_SESSION['DefineControlledOnWOEntry']==1) {
			echo '<td class="number">' . locale_number_format($_POST['OutputQty' . $i], $_POST['DecimalPlaces' . $i]) . '
				</td>';
			echo '<input type="hidden" name="OutputQty' . $i .'" value="' . locale_number_format($_POST['OutputQty' . $i]-$_POST['RecdQty' .$i], $_POST['DecimalPlaces' . $i]) . '" />';
		} else {
			echo'<td><input type="text" required="required" class="number" name="OutputQty' . $i . '" value="' . locale_number_format($_POST['OutputQty' . $i]-$_POST['RecdQty' .$i], $_POST['DecimalPlaces' . $i]) . '" size="10" maxlength="10" title="'._('The input format must be positive numeric').'" />
				</td>';
		}
		echo '<td class="number"><input type="hidden" name="RecdQty' . $i . '" value="' . locale_number_format($_POST['RecdQty' .$i], $_POST['DecimalPlaces' . $i]) . '" />' . locale_number_format($_POST['RecdQty' .$i], $_POST['DecimalPlaces' . $i])  . '
			</td>
			<td class="number">' . locale_number_format(($_POST['OutputQty' . $i] - $_POST['RecdQty' .$i]), $_POST['DecimalPlaces' . $i]) . '
			</td>';
		if($_POST['Controlled'.$i]==1) {
			echo '<td><input type="text" name="NextLotSNRef' .$i . '" value="' . $_POST['NextLotSNRef'.$i] . '" /></td>';
			if($_SESSION['DefineControlledOnWOEntry']==1) {
				if($_POST['Serialised' . $i]==1) {
					$LotOrSN = _('S/Ns');
				} else {
					$LotOrSN = _('Batches');
				}
				echo '<td><a href="' . $RootPath . '/WOSerialNos.php?WO=' . $_POST['WO'] . '&StockID=' . urlencode($_POST['OutputItem' .$i]) . '&Description=' . $_POST['OutputItemDesc' .$i] . '&Serialised=' . $_POST['Serialised' .$i] . '&NextSerialNo=' . $_POST['NextLotSNRef' .$i] . '">' . $LotOrSN . '</a></td>';
			}
		} else {
			echo '<td></td>';
		}
		echo '<td>
			<a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Delete=Yes&StockID=' . urlencode($_POST['OutputItem' . $i]) . '&WO='.$_POST['WO'].'" onclick="return confirm(\''._('Are you sure').'?\');">' . _('Delete') . '</a></td>';

		if($_SESSION['WikiApp']!=0) {
			echo '<td>';
			wikiLink('WorkOrder', $_POST['WO'] . $_POST['OutputItem' .$i]);
			echo '</td>';
		}
		echo '</tr>';
		if(isset($_POST['Controlled' . $i])) {
			echo '<input type="hidden" name="Controlled' . $i .'" value="' . $_POST['Controlled' . $i] . '" />';
		}
		if(isset( $_POST['Serialised' . $i])) {
			echo '<input type="hidden" name="Serialised' . $i .'" value="' . $_POST['Serialised' . $i] . '" />';
		}
		if(isset($_POST['HasWOSerialNos' . $i])) {
			echo '<input type="hidden" name="HasWOSerialNos' . $i .'" value="' . $_POST['HasWOSerialNos' . $i] . '" />';
		}
	}
	echo '<input type="hidden" name="NumberOfOutputs" value="' . ($i -1).'" />';
}
echo '</table>';

echo '<br /><div class="centre"><button type="submit" name="submit">' . _('Update') . '</button></div>';

echo '<br /><div class="centre"><button type="submit" name="delete" onclick="return confirm(\'' . _('Are You Sure?') . '\');">' . _('Cancel This Work Order') . '</button>';

echo '</div><br />';

$SQL="SELECT categoryid,
			categorydescription
		FROM stockcategory
		WHERE stocktype='F' OR stocktype='M'
		ORDER BY categorydescription";
	$result1 = DB_query($SQL);

echo '<table class="selection"><tr><td>' . _('Select a stock category') . ':<select name="StockCat">';

if(!isset($_POST['StockCat'])) {
	echo '<option selected="True" value="All">' . _('All') . '</option>';
	$_POST['StockCat'] ='All';
} else {
	echo '<option value="All">' . _('All') . '</option>';
}

while ($myrow1 = DB_fetch_array($result1)) {

	if($_POST['StockCat']==$myrow1['categoryid']) {
		echo '<option selected="True" value=' . $myrow1['categoryid'] . '>' . $myrow1['categorydescription'] . '</option>';
	} else {
		echo '<option value='. $myrow1['categoryid'] . '>' . $myrow1['categorydescription'] . '</option>';
	}
}

if(!isset($_POST['Keywords'])) {
    $_POST['Keywords']='';
}

if(!isset($_POST['StockCode'])) {
    $_POST['StockCode']='';
}
if(!isset($_POST['SO'])) {
	$_POST['SO'] = '';
}
if(!isset($_POST['CustomerRef'])) {
	$_POST['CustomerRef'] = '';
}

echo '</select>
		<td>' . _('Enter text extracts in the') . ' <b>' . _('description') . '</b>:</td>
		<td><input type="text" name="Keywords" size="20" maxlength="25" value="' . $_POST['Keywords'] . '" /></td>
	</tr>
    <tr>
		<td>&nbsp;</td>
		<td><font size="3"><b>' . _('OR') . ' </b></font>' . _('Enter extract of the') . ' <b>' . _('Stock Code') . '</b>:</td>
		<td><input type="text" name="StockCode" autofocus="autofocus" size="15" maxlength="18" value="' . $_POST['StockCode'] . '" /></td>
	</tr>
	<tr>
		<td><font size="3"><b>' . _('OR') . ' </b></font>' . _('Enter Sales Order') . '
		<input type="text" name="SO" value="' . $_POST['SO'] . '" size="15" /></td>
		<td><font size="3"><b>' . _('OR') . ' </b></font>' . _('Enter Customer Order Ref') . '</td>
		<td><input type="text" name="CustomerRef" size="20"  value="' . $_POST['CustomerRef'] . '" /></td>
	</tr>
	</table>
	<br />
	<div class="centre">
		<button type="submit" name="Search">' . _('Search Now') . '</button>
	</div>';

if(isset($SearchResult)) {

	if(DB_num_rows($SearchResult)>1) {

		$PageBar = '<tr><td><input type="hidden" name="CurrPage" value="'.$Offset.'">';
		if($Offset>0)
			$PageBar .= '<input type="submit" name="Prev" value="'._('Prev').'" />';
		else
			$PageBar .= '<input type="submit" name="Prev" value="'._('Prev').'" disabled="disabled"/>';
		$PageBar .= '</td><td>';
		if($Offset<$ListPageMax)
			$PageBar .= '<input type="submit" name="Next" value="'._('Next').'" />';
		else
			$PageBar .= '<input type="submit" name="Next" value="'._('Next').'" disabled="disabled"/>';
		$PageBar .= '</td></tr>';

		echo '<br /><table cellpadding="2" class="selection">
			<thead>';
		echo $PageBar;
		echo '<tr>
				<th class="ascending">' . _('Code') . '</th>
	   			<th class="ascending">' . _('Description') . '</th>
	   			<th>' . _('Units') . '</th>
			</tr>
			</thead>
			<tbody>';

		$j = 1;
		$ItemCodes = array();
		for ($i=1;$i<=$NumberOfOutputs;$i++) {
			$ItemCodes[] =$_POST['OutputItem'.$i];
		}

		while ($myrow=DB_fetch_array($SearchResult)) {

			if(!in_array($myrow['stockid'],$ItemCodes)) {

				$SupportedImgExt = array('png','jpg','jpeg');
				$imagefile = reset((glob($_SESSION['part_pics_dir'] . '/' . $myrow['stockid'] . '.{' . implode(",", $SupportedImgExt) . '}', GLOB_BRACE)));
				if(extension_loaded('gd') && function_exists('gd_info') && file_exists ($imagefile) ) {
					$ImageSource = '<img src="GetStockImage.php?automake=1&amp;textcolor=FFFFFF&amp;bgcolor=CCCCCC'.
						'&amp;StockID='.urlencode($myrow['stockid']).
						'&amp;text='.
						'&amp;width=64'.
						'&amp;height=64'.
						'" alt="" />';
				} else if(file_exists ($imagefile)) {
					$ImageSource = '<img src="' . $imagefile . '" height="64" width="64" />';
				} else {
					$ImageSource = _('No Image');
				}

				if($myrow['controlled']==1 AND $_SESSION['DefineControlledOnWOEntry']==1) { //need to add serial nos or batches to determine quantity

				printf('<tr class="striped_row">
						<td><font size="1">%s</font></td>
						<td><font size="1">%s</font></td>
						<td><font size="1">%s</font></td>
						<td>%s</td>
						<td><font size="1"><a href="%s">'
						. _('Add to Work Order') . '</a></font></td>
						<td><input type="checkbox" value="%s" name="Check_%s" /></td>
						</tr>',
						$myrow['stockid'],
						$myrow['description'],
						$myrow['units'],
						$ImageSource,
						htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?WO=' . $_POST['WO'] . '&NewItem=' . urlencode($myrow['stockid']).'&Line='.$i,
						$myrow['stockid'],
						$j);
				} else {
						if(!isset($myrow['quantity'])) {
							$myrow['quantity'] = 0;
						}
						printf('<tr class="striped_row">
						<td><font size="1">%s</font></td>
						<td><font size="1">%s</font></td>
						<td><font size="1">%s</font></td>
						<td>%s</td>
						<td><font size="1"><a href="%s">'
						. _('Add to Work Order') . '</a></font></td>
						<td><input type="text" name="Qty_%s" value="%s" size="10" /><input type="hidden" value="%s" name="Item_%s" /></td>
						</tr>',
						$myrow['stockid'],
						$myrow['description'],
						$myrow['units'],
						$ImageSource,
						htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '?WO=' . $_POST['WO'] . '&NewItem=' . urlencode($myrow['stockid']).'&Line='.$i,
						$j,
						$myrow['quantity'],
						$myrow['stockid'],
						$j);
				}


				$j++;
			} //end if not already on work order
		}//end of while loop
	} //end if more than 1 row to show
	echo '</tbody></table>';
	echo '<div class="centre">
			<input type="submit" name="Add" value="' . _('Add To Work Order') . '" />
			<input type="hidden" name="WO" value="' . $_POST['WO'] . '" />
			</div>';

}#end if SearchResults to show

echo '</form>';
include('includes/footer.php');
?>

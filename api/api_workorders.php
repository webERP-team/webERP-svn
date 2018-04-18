<?php

/* Check that the stock code exists*/
	function VerifyWorkOrderExists($WorkOrder, $i, $Errors) {
		$Searchsql = "SELECT count(wo)
				FROM workorders
				WHERE wo='".$WorkOrder."'";
		$SearchResult=DB_query($Searchsql);
		$answer = DB_fetch_array($SearchResult);
		if ($answer[0]==0) {
			$Errors[$i] = WorkOrderDoesntExist;
		}
		return $Errors;
	}

/* Check that the stock location is set up in the weberp database */
	function VerifyStockLocation($location, $i, $Errors) {
		$Searchsql = "SELECT COUNT(loccode)
					 FROM locations
					  WHERE loccode='" . $location . "'";
		$SearchResult=DB_query($Searchsql);
		$answer = DB_fetch_row($SearchResult);
		if ($answer[0] == 0) {
			$Errors[$i] = LocationCodeNotSetup;
		}
		return $Errors;
	}

/* Verify that the quantity figure is numeric */
	function VerifyIssuedQuantity($quantity, $i, $Errors) {
		if (!is_numeric($quantity)) {
			$Errors[$i] = InvalidIssuedQuantity;
		}
		return $Errors;
	}

/* Verify that the quantity figure is numeric */
	function VerifyReceivedQuantity($quantity, $i, $Errors) {
		if (!is_numeric($quantity)) {
			$Errors[$i] = InvalidReceivedQuantity;
		}
		return $Errors;
	}

	function VerifyRequiredByDate($RequiredByDate, $i, $Errors) {
		$sql="SELECT confvalue FROM config WHERE confname='DefaultDateFormat'";
		$result=DB_query($sql);
		$myrow=DB_fetch_array($result);
		$DateFormat=$myrow[0];
		if (mb_strstr('/',$PeriodEnd)) {
			$Date_Array = explode('/',$PeriodEnd);
		} elseif (mb_strstr('.',$PeriodEnd)) {
			$Date_Array = explode('.',$PeriodEnd);
		}
		if ($DateFormat=='d/m/Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='m/d/Y') {
			$Day=$DateArray[1];
			$Month=$DateArray[0];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='Y/m/d') {
			$Day=$DateArray[2];
			$Month=$DateArray[1];
			$Year=$DateArray[0];
		} elseif ($DateFormat=='d.m.Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		}
		if (!checkdate(intval($Month), intval($Day), intval($Year))) {
			$Errors[$i] = InvalidRequiredByDate;
		}
		return $Errors;
	}

	function VerifyStartDate($StartDate, $i, $Errors) {
		$sql="SELECT confvalue FROM config WHERE confname='DefaultDateFormat'";
		$result=DB_query($sql);
		$myrow=DB_fetch_array($result);
		$DateFormat=$myrow[0];
		if (mb_strstr('/',$PeriodEnd)) {
			$Date_Array = explode('/',$PeriodEnd);
		} elseif (mb_strstr('.',$PeriodEnd)) {
			$Date_Array = explode('.',$PeriodEnd);
		}
		if ($DateFormat=='d/m/Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='m/d/Y') {
			$Day=$DateArray[1];
			$Month=$DateArray[0];
			$Year=$DateArray[2];
		} elseif ($DateFormat=='Y/m/d') {
			$Day=$DateArray[2];
			$Month=$DateArray[1];
			$Year=$DateArray[0];
		} elseif ($DateFormat=='d.m.Y') {
			$Day=$DateArray[0];
			$Month=$DateArray[1];
			$Year=$DateArray[2];
		}
		if (!checkdate(intval($Month), intval($Day), intval($Year))) {
			$Errors[$i] = InvalidStartDate;
		}
		return $Errors;
	}

	function VerifyCostIssued($CostIssued, $i, $Errors) {
		if (!is_numeric($CostIssued)) {
			$Errors[$i] = InvalidCostIssued;
		}
		return $Errors;
	}

	function VerifyQtyReqd($QtyReqd, $i, $Errors) {
		if (!is_numeric($QtyReqd)) {
			$Errors[$i] = InvalidQuantityRequired;
		}
		return $Errors;
	}

	function VerifyQtyRecd($QtyRecd, $i, $Errors) {
		if (!is_numeric($QtyRecd)) {
			$Errors[$i] = InvalidQuantityReceived;
		}
		return $Errors;
	}

	function VerifyStdCost($StdCost, $i, $Errors) {
		if (!is_numeric($StdCost)) {
			$Errors[$i] = InvalidStandardCost;
		}
		return $Errors;
	}

	function VerifyLotSerialNumber($nextlotsnref, $i, $Errors) {
		if (mb_strlen($nextlotsnref)>20) {
			$Errors[$i] = IncorrectSerialNumber;
		}
		return $Errors;
	}

	function VerifyBatch($batch, $stockid, $location, $i, $Errors) {
		$sql="SELECT controlled, serialised FROM stockmaster WHERE stockid='".$stockid."'";
		$result=DB_query($sql);
		$myrow=DB_fetch_row($result);
		if ($myrow[0]!=1) {
			$Errors[$i] = ItemNotControlled;
			return $Errors;
		} else if ($myrow[1]==1) {
			$Errors[$i] = ItemSerialised;
			return $Errors;
		}
		$sql="SELECT quantity FROM stockserialitems
              WHERE stockid='".$stockid. "'
              AND loccode='".$location."'
              AND serialno='".$batch."'";
		$result=DB_query($sql);
		if (DB_num_rows($result)==0) {
			$Errors[$i] = BatchNumberDoesntExist;
			return $Errors;
		}
		$myrow=DB_fetch_row($result);
		if ($myrow<=0) {
			$Errors[$i]=BatchIsEmpty;
			return $Errors;
		}
		return $Errors;
	}

	function InsertWorkOrder($WorkOrderDetails, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		foreach ($WorkOrderDetails as $key => $value) {
			$WorkOrderDetails[$key] = DB_escape_string($value);
		}
		$WorkOrder['wo']=GetNextTransNo(40);
		$WorkOrderItem['wo']=$WorkOrder['wo'];
		if (isset($WorkOrderDetails['loccode'])){
			$Errors=VerifyFromStockLocation($WorkOrderDetails['loccode'], sizeof($Errors), $Errors);
			$WorkOrder['loccode']=$WorkOrderDetails['loccode'];
		}
		if (isset($WorkOrderDetails['requiredby'])){
//			$Errors=VerifyRequiredByDate($WorkOrderDetails['requiredby'], sizeof($Errors), $Errors);
			$WorkOrder['requiredby']=$WorkOrderDetails['requiredby'];
		}
		if (isset($WorkOrderDetails['startdate'])){
//			$Errors=VerifyStartDate($WorkOrderDetails['startdate'], sizeof($Errors), $Errors);
			$WorkOrder['startdate']=$WorkOrderDetails['startdate'];
		}
		if (isset($WorkOrderDetails['costissued'])){
			$Errors=VerifyCostIssued($WorkOrderDetails['costissued'], sizeof($Errors), $Errors);
			$WorkOrder['costissued']=$WorkOrderDetails['costissued'];
		}
		if (isset($WorkOrderDetails['closed'])){
			$Errors=VerifyCompleted($WorkOrderDetails['closed'], sizeof($Errors), $Errors);
			$WorkOrder['closed']=$WorkOrderDetails['closed'];
		}
		if (isset($WorkOrderDetails['stockid'])){
			$Errors=VerifyStockCodeExists($WorkOrderDetails['stockid'], sizeof($Errors), $Errors);
			$WorkOrderItem['stockid']=$WorkOrderDetails['stockid'];
		}
		if (isset($WorkOrderDetails['qtyreqd'])){
			$Errors=VerifyQtyReqd($WorkOrderDetails['qtyreqd'], sizeof($Errors), $Errors);
			$WorkOrderItem['qtyreqd']=$WorkOrderDetails['qtyreqd'];
		}
		if (isset($WorkOrderDetails['qtyrecd'])){
			$Errors=VerifyQtyRecd($WorkOrderDetails['qtyrecd'], sizeof($Errors), $Errors);
			$WorkOrderItem['qtyrecd']=$WorkOrderDetails['qtyrecd'];
		}
		if (isset($WorkOrderDetails['stdcost'])){
			$Errors=VerifyStdCost($WorkOrderDetails['stdcost'], sizeof($Errors), $Errors);
			$WorkOrderItem['stdcost']=$WorkOrderDetails['stdcost'];
		}
		if (isset($WorkOrderDetails['nextlotsnref'])){
			$Errors=VerifyLotSerialNumber($WorkOrderDetails['nextlotsnref'], sizeof($Errors), $Errors);
			$WorkOrderItem['nextlotsnref']=$WorkOrderDetails['nextlotsnref'];
		}

		$WOFieldNames='';
		$WOFieldValues='';
		foreach ($WorkOrder as $key => $value) {
			$WOFieldNames.=$key.', ';
			$WOFieldValues.='"'.$value.'", ';
		}
		$ItemFieldNames='';
		$ItemFieldValues='';
		foreach ($WorkOrderItem as $key => $value) {
			$ItemFieldNames.=$key.', ';
			$ItemFieldValues.='"'.$value.'", ';
		}
		if (sizeof($Errors)==0) {
			$wosql = 'INSERT INTO workorders ('.mb_substr($WOFieldNames,0,-2).') '.
				'VALUES ('.mb_substr($WOFieldValues,0,-2).') ';
			$itemsql = 'INSERT INTO woitems ('.mb_substr($ItemFieldNames,0,-2).') '.
				'VALUES ('.mb_substr($ItemFieldValues,0,-2).') ';
			$systypessql = 'UPDATE systypes set typeno='.GetNextTransNo(40).' where typeid=40';
			DB_Txn_Begin();
			$woresult = DB_query($wosql);
			$itemresult = DB_query($itemsql);
			$systyperesult = DB_query($systypessql);
			DB_Txn_Commit();
			if (DB_error_no() != 0) {
				$Errors[0] = DatabaseUpdateFailed;
			} else {
				$Errors[0]=0;
				$Errors[1]=$WorkOrder['wo'];
			}
		}
		return $Errors;
	}

	function WorkOrderIssue($WONumber, $StockID, $Location, $Quantity, $TranDate, $Batch, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$Errors = VerifyStockCodeExists($StockID, sizeof($Errors), $Errors);
		$Errors = VerifyWorkOrderExists($WONumber, sizeof($Errors), $Errors);
		$Errors = VerifyStockLocation($Location, sizeof($Errors), $Errors);
		$Errors = VerifyIssuedQuantity($Quantity, sizeof($Errors), $Errors);
//		$Errors = VerifyTransactionDate($TranDate, sizeof($Errors), $Errors);
		if ($Batch!='') {
			VerifyBatch($Batch, $StockID, $Location, sizeof($Errors), $Errors);
		}
		if (sizeof($Errors)!=0) {
			return $Errors;
		} else {
			$balances=GetStockBalance($StockID, $user, $password);
			$balance=0;
			for ($i=0; $i<sizeof($balances); $i++) {
				$balance=$balance+$balances[$i]['quantity'];
			}
			$newqoh = $Quantity + $balance;
			$itemdetails = GetStockItem($StockID, $user, $password);
			$wipglact=GetCategoryGLCode($itemdetails[1]['categoryid'], 'wipact');
			$stockact=GetCategoryGLCode($itemdetails[1]['categoryid'], 'stockact');
			$cost=$itemdetails[1]['materialcost']+$itemdetails[1]['labourcost']+$itemdetails[1]['overheadcost'];
			$TransactionNo=GetNextTransNo(28);

			$stockmovesql="INSERT INTO stockmoves (stockid,
                                                   type,
                                                   transno,
                                                   loccode,
                                                   trandate,
                                                   prd,
                                                   reference,
                                                   qty,
                                                   newqoh,
				                                   price,
                                                   standardcost)
                                   		VALUES ('".$StockID."',
                                                28,
                                                '" .$TransactionNo. "',
                                                '".$Location."',
                                                '" .$TranDate."',
                                                '".GetPeriodFromTransactionDate($TranDate, sizeof($Errors), $Errors). "',
                                                '".$WONumber."',
                                                '".$Quantity."',
                                                '".$newqoh."',
                                                '".$cost."',
                                                '".$cost."')";
			$locstocksql="UPDATE locstock SET quantity = quantity + " . $Quantity ."
			                           WHERE loccode='". $Location."'
			                           AND stockid='".$StockID."'";
			$glupdatesql1="INSERT INTO gltrans (type,
						                                               typeno,
						                                               trandate,
						                                               periodno,
						                                               account,
						                                               amount,
						                                               narrative)
									      VALUES (28,
						                                              '".$TransactionNo. "',
						                                              '".$TranDate."',
						                                              '".GetPeriodFromTransactionDate($TranDate, sizeof($Errors), $Errors)."',
						                                              '".$wipglact."',
						                                              '".$cost*-$Quantity."',
						                                              '".$StockID.' x '.$Quantity.' @ '.$cost."')";
			$glupdatesql2="INSERT INTO gltrans (type,
						                                                typeno,
						                                                trandate,
						                                                periodno,
						                                                account,
						                                                amount,
						                                                narrative)
						                          VALUES (28,
						                                        '".$TransactionNo."',
						                                        '".$TranDate."',
						                                        '".GetPeriodFromTransactionDate($TranDate, sizeof($Errors), $Errors)."',
						                                        '".$stockact."',
						                                        '".$cost*$Quantity."',
						                                        '".$StockID.' x '.$Quantity.' @ '.$cost."')";
			$systypessql = "UPDATE systypes set typeno='".$TransactionNo."' where typeid=28";
			$batchsql="UPDATE stockserialitems SET quantity=quantity-" . $Quantity.
				              " WHERE stockid='".$StockID."'
                              AND loccode='".$Location."' AND serialno='".$Batch."'";
			$costsql = "UPDATE workorders SET costissued=costissued+".$cost." WHERE wo='".$WONumber . "'";

			DB_Txn_Begin();
			DB_query($stockmovesql);
			DB_query($locstocksql);
			DB_query($glupdatesql1);
			DB_query($glupdatesql2);
			DB_query($systypessql);
			DB_query($costsql);
			if ($Batch!='') {
				DB_query($batchsql);
			}
			DB_Txn_Commit();
			if (DB_error_no() != 0) {
				$Errors[0] = DatabaseUpdateFailed;
				return $Errors;
			} else {
				return 0;
			}
		}
	}

	function WorkOrderReceive($WONumber, $StockID, $Location, $Quantity, $TranDate, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$Errors = VerifyStockCodeExists($StockID, sizeof($Errors), $Errors);
		$Errors = VerifyWorkOrderExists($WONumber, sizeof($Errors), $Errors);
		$Errors = VerifyStockLocation($Location, sizeof($Errors), $Errors);
		$Errors = VerifyReceivedQuantity($Quantity, sizeof($Errors), $Errors);
//		$Errors = VerifyTransactionDate($TranDate, sizeof($Errors), $Errors);
		if (sizeof($Errors)!=0) {
			return $Errors;
		}
			$itemdetails = GetStockItem($StockID, $user, $password);
			$balances=GetStockBalance($StockID, $user, $password);
			$balance=0;
			for ($i=0; $i<sizeof($balances); $i++) {
				$balance=$balance+$balances[$i]['quantity'];
			}
			$newqoh = $Quantity + $balance;
			$wipglact=GetCategoryGLCode($itemdetails['categoryid'], 'wipact');
			$stockact=GetCategoryGLCode($itemdetails['categoryid'], 'stockact');
			$costsql="SELECT costissued FROM workorders WHERE wo='".$WONumber . "'";
			$costresult=DB_query($costsql);
			$myrow=DB_fetch_row($costresult);
			$cost=$myrow[0];
			$TransactionNo=GetNextTransNo(26);
			$stockmovesql="INSERT INTO stockmoves (stockid,
                                                   type,
                                                   transno,
                                                   loccode,
                                                   trandate,
                                                   prd,
                                                   reference,
                                                   qty,
                                                   newqoh,
                                                   price,
                                                   standardcost)
                                      	VALUES ('".$StockID."',
                                                 '26',
                                                '".$TransactionNo."',
                                                '".$Location."',
                                                '".$TranDate."',
                                                '".GetPeriodFromTransactionDate($TranDate, sizeof($Errors), $Errors)."',
                                                '".$WONumber."',
                                                '".$Quantity."',
                                                '".$newqoh."',
                                                '".$cost."',
                                                '".$cost."')";
			$locstocksql="UPDATE locstock SET quantity = quantity + ".$Quantity."
                                 WHERE loccode='". $Location."'
                                 AND stockid='".$StockID."'";
			$glupdatesql1="INSERT INTO gltrans (type,
                                               typeno,
                                               trandate,
                                               periodno,
                                               account,
                                               amount,
                                               narrative)
                                		VALUES (26,
                                               '".$TransactionNo."',
                                               '".$TranDate. "',
                                               '" .GetPeriodFromTransactionDate($TranDate, sizeof($Errors), $Errors)."',
                                               '".$wipglact."',
                                               '".$cost*$Quantity. "',
                                               '".$StockID.' x '.$Quantity.' @ '.$cost."')";
			$glupdatesql2="INSERT INTO gltrans (type,
                                                typeno,
                                                trandate,
                                                periodno,
                                                account,
                                                amount,
                                                narrative)
                                    	VALUES (26,
                                               '".$TransactionNo."',
                                               '".$TranDate."',
                                               '".GetPeriodFromTransactionDate($TranDate, sizeof($Errors), $Errors)."',
                                               '".$stockact.','.$cost*-$Quantity. "',
                                               '".$StockID.' x '.$Quantity.' @ '.$cost."')";
			$systypessql = "UPDATE systypes set typeno='".$TransactionNo."' where typeid=26";
			DB_Txn_Begin();
			DB_query($stockmovesql);
			DB_query($locstocksql);
			DB_query($glupdatesql1);
			DB_query($glupdatesql2);
			DB_query($systypessql);
			DB_Txn_Commit();
			if (DB_error_no() != 0) {
				$Errors[0] = DatabaseUpdateFailed;
			} else {
				$Errors[0] = 0;
			}
			return $Errors;

	}

	function SearchWorkOrders($Field, $Criteria, $user, $password) {
		$Errors = array();
		$db = db($user, $password);
		if (gettype($db)=='integer') {
			$Errors[0]=NoAuthorisation;
			return $Errors;
		}
		$sql="SELECT wo
			  FROM woitems
			  WHERE " . $Field ." " . LIKE  . " '%".$Criteria."%'";
		$result = DB_query($sql);
		$i=0;
		$WOList = array();
		while ($myrow=DB_fetch_array($result)) {
			$WOList[$i]=$myrow[0];
			$i++;
		}
		return $WOList;
	}
?>

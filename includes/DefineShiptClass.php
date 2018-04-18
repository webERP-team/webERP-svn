<?php
/* Definition of the Shipment class to hold all the information for a shipment*/

Class Shipment {

	var $ShiptRef; /*unqique identifier for the shipment */
	var $LineItems; /*array of objects of class LineDetails using the product id as the pointer */
	var $SupplierID;
	var $SupplierName;
	var $CurrCode;
	var $VoyageRef;
	var $Vessel;
	var $ETA;
	var $StockLocation;
	var $Closed;
	var $CurrDecimalPlaces;

	function __construct(){
	/*Constructor function initialises a new Shipment object */
		$this->LineItems = array();
		$this->AccumValue =0;
		$this->Closed =0;
	}

	function Shipment() {
		self::__construct();
	}

	function Add_To_Shipment($PODetailItem,
							$OrderNo,
							$StockID,
							$ItemDescr,
							$QtyInvoiced,
							$UnitPrice,
							$UOM,
							$DelDate,
							$QuantityOrd,
							$QuantityRecd,
							$StdCostUnit,
							$DecimalPlaces){

		$this->LineItems[$PODetailItem]= new LineDetails($PODetailItem,
														$OrderNo,
														$StockID,
														$ItemDescr,
														$QtyInvoiced,
														$UnitPrice,
														$UOM,
														$DelDate,
														$QuantityOrd,
														$QuantityRecd,
														$StdCostUnit,
														$DecimalPlaces);

		$sql = "UPDATE purchorderdetails SET shiptref = '" . $this->ShiptRef . "'
			WHERE podetailitem = '" . $PODetailItem . "'";
		$ErrMsg = _('There was an error updating the purchase order detail record to make it part of shipment') . ' ' . $this->ShiptRef . ' ' . _('the error reported was');
		$result = DB_query($sql, $ErrMsg);

		Return 1;
	}


	function Remove_From_Shipment($PODetailItem){

		if ($this->LineItems[$PODetailItem]->QtyInvoiced==0){

			unset($this->LineItems[$PODetailItem]);
			$sql = "UPDATE purchorderdetails SET shiptref = 0 WHERE podetailitem='" . $PODetailItem . "'";
			$Result = DB_query($sql);
		} else {
			prnMsg(_('This shipment line has a quantity invoiced and already charged to the shipment - it cannot now be removed'),'warn');
		}
	}

} /* end of class defintion */

Class LineDetails {

	var $PODetailItem;
	var $OrderNo;
	var $StockID;
	var $ItemDescription;
	var $QtyInvoiced;
	var $UnitPrice;
	var $UOM;
	var $DelDate;
	var $QuantityOrd;
	var $QuantityRecd;
	var $StdCostUnit;
	var $DecimalPlaces;


	function __construct ($PODetailItem,
							$OrderNo,
							$StockID,
							$ItemDescr,
							$QtyInvoiced,
							$UnitPrice,
							$UOM,
							$DelDate,
							$QuantityOrd,
							$QuantityRecd,
							$StdCostUnit,
							$DecimalPlaces=2){

	/* Constructor function to add a new LineDetail object with passed params */
		$this->PODetailItem = $PODetailItem;
		$this->OrderNo = $OrderNo;
		$this->StockID =$StockID;
		$this->ItemDescription = $ItemDescr;
		$this->QtyInvoiced = $QtyInvoiced;
		$this->DelDate = $DelDate;
		$this->UnitPrice = $UnitPrice;
		$this->UOM = $UOM;
		$this->QuantityRecd = $QuantityRecd;
		$this->QuantityOrd = $QuantityOrd;
		$this->StdCostUnit = $StdCostUnit;
		$this->DecimalPlaces = $DecimalPlaces;
	}

	function LineDetails($PODetailItem,
							$OrderNo,
							$StockID,
							$ItemDescr,
							$QtyInvoiced,
							$UnitPrice,
							$UOM,
							$DelDate,
							$QuantityOrd,
							$QuantityRecd,
							$StdCostUnit,
							$DecimalPlaces=2) {
			self::__construct($PODetailItem,
							$OrderNo,
							$StockID,
							$ItemDescr,
							$QtyInvoiced,
							$UnitPrice,
							$UOM,
							$DelDate,
							$QuantityOrd,
							$QuantityRecd,
							$StdCostUnit,
							$DecimalPlaces=2);
	}
}

?>

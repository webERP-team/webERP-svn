<?php

if ($PageNumber>1){
	$pdf->newPage();
}
$pdf->addJpegFromFile($_SESSION['LogoFile'],$Left_Margin+$FormDesign->logo->x,$Page_Height- $FormDesign->logo->y,$FormDesign->logo->width,$FormDesign->logo->height);

$pdf->setFont('','B');
$pdf->addText($FormDesign->OrderNumber->x,$Page_Height- $FormDesign->OrderNumber->y,$FormDesign->OrderNumber->FontSize, _('Work Order Number'). ': ' . $WOHeader['wo']);
$pdf->setFont('','');
$pdf->addText($FormDesign->PageNumber->x,$Page_Height - $FormDesign->PageNumber->y, $FormDesign->PageNumber->FontSize, _('Page') . ': ' .$PageNumber);
$pdf->addText($FormDesign->CompanyName->x,$Page_Height - $FormDesign->CompanyName->y, $FormDesign->CompanyName->FontSize, $_SESSION['CompanyRecord']['coyname']);
$pdf->addText($FormDesign->CompanyAddress->Line1->x,$Page_Height - $FormDesign->CompanyAddress->Line1->y, $FormDesign->CompanyAddress->Line1->FontSize,  $_SESSION['CompanyRecord']['regoffice1']);
$pdf->addText($FormDesign->CompanyAddress->Line2->x,$Page_Height - $FormDesign->CompanyAddress->Line2->y, $FormDesign->CompanyAddress->Line2->FontSize,  $_SESSION['CompanyRecord']['regoffice2']);
$pdf->addText($FormDesign->CompanyAddress->Line3->x,$Page_Height - $FormDesign->CompanyAddress->Line3->y, $FormDesign->CompanyAddress->Line3->FontSize,  $_SESSION['CompanyRecord']['regoffice3']);
$pdf->addText($FormDesign->CompanyPhone->x,$Page_Height - $FormDesign->CompanyPhone->y, $FormDesign->CompanyPhone->FontSize, _('Tel'). ': ' . $_SESSION['CompanyRecord']['telephone']);
$pdf->addText($FormDesign->CompanyFax->x,$Page_Height - $FormDesign->CompanyFax->y, $FormDesign->CompanyFax->FontSize, _('Fax').': ' . $_SESSION['CompanyRecord']['fax']);
$pdf->addText($FormDesign->CompanyEmail->x,$Page_Height - $FormDesign->CompanyEmail->y, $FormDesign->CompanyEmail->FontSize, _('Email'). ': ' .$_SESSION['CompanyRecord']['email']);

$AddressLineOffset=0;
if ($WOHeader['deladd2'] == '') {
	$AddressLineOffset=$FormDesign->FactoryAddress->Line2->FontSize;
}
if ($WOHeader['deladd3'] == '') {
	$AddressLineOffset+=$FormDesign->FactoryAddress->Line2->FontSize;
}

$pdf->addText($FormDesign->FactoryAddress->Caption->x,$Page_Height - $FormDesign->FactoryAddress->Caption->y, $FormDesign->FactoryAddress->Caption->FontSize, _('Produced At') . ': ' );
$pdf->addText($FormDesign->FactoryAddress->Name->x,$Page_Height - $FormDesign->FactoryAddress->Name->y, $FormDesign->FactoryAddress->Name->FontSize, $WOHeader['locationname']);
$pdf->addText($FormDesign->FactoryAddress->Line1->x,$Page_Height - $FormDesign->FactoryAddress->Line1->y, $FormDesign->FactoryAddress->Line1->FontSize, $WOHeader['deladd1']);
$pdf->addText($FormDesign->FactoryAddress->Line2->x,$Page_Height - $FormDesign->FactoryAddress->Line2->y, $FormDesign->FactoryAddress->Line2->FontSize, $WOHeader['deladd2']);
$pdf->addText($FormDesign->FactoryAddress->Line3->x,$Page_Height - $FormDesign->FactoryAddress->Line3->y+$AddressLineOffset, $FormDesign->FactoryAddress->Line3->FontSize, $WOHeader['deladd3']);
$pdf->addText($FormDesign->FactoryAddress->Line4->x,$Page_Height - $FormDesign->FactoryAddress->Line4->y+$AddressLineOffset, $FormDesign->FactoryAddress->Line4->FontSize, $WOHeader['deladd4']);
$pdf->addText($FormDesign->FactoryAddress->Line5->x,$Page_Height - $FormDesign->FactoryAddress->Line5->y+$AddressLineOffset, $FormDesign->FactoryAddress->Line5->FontSize, $WOHeader['deladd5'] . $WOHeader['deladd6']);// Includes delivery postal code and country.
//$pdf->addText($FormDesign->FactoryAddress->WorkCenter->x,$Page_Height - $FormDesign->FactoryAddress->WorkCenter->y+$AddressLineOffset, $FormDesign->FactoryAddress->WorkCenter->FontSize, $WOHeader['workcenter']);

$pdf->RoundRectangle($FormDesign->WOAddressBox->x, $Page_Height - $FormDesign->WOAddressBox->y,$FormDesign->WOAddressBox->width, $FormDesign->WOAddressBox->height, $FormDesign->WOAddressBox->radius, $FormDesign->WOAddressBox->radius);// Function RoundRectangle from includes/class.pdf.php
//$pdf->RoundRectangle($FormDesign->WOHeaderBox->x, $Page_Height - $FormDesign->WOHeaderBox->y,$FormDesign->WOHeaderBox->width, $FormDesign->WOHeaderBox->height, $FormDesign->WOHeaderBox->radius, $FormDesign->WOHeaderBox->radius);// Function RoundRectangle from includes/class.pdf.php

$pdf->setFont('','B');
$pdf->addText($FormDesign->ItemNumberLab->x,$Page_Height - $FormDesign->ItemNumberLab->y, $FormDesign->ItemNumberLab->FontSize,  _('Item Number') . ':' );
$pdf->addText($FormDesign->ItemDescLab->x,$Page_Height - $FormDesign->ItemDescLab->y, $FormDesign->ItemDescLab->FontSize, _('Item Description'). ':' );
$pdf->addText($FormDesign->RequiredDateLab->x,$Page_Height - $FormDesign->RequiredDateLab->y, $FormDesign->RequiredDateLab->FontSize, _('Required Date') . ':');
$pdf->addText($FormDesign->LotLab->x,$Page_Height - $FormDesign->LotLab->y, $FormDesign->LotLab->FontSize, _('Lot'). ':' );
$pdf->addText($FormDesign->RequiredQtyLab->x,$Page_Height - $FormDesign->RequiredQtyLab->y, $FormDesign->RequiredQtyLab->FontSize, _('Required Qty'). ':');
$pdf->addText($FormDesign->ReceivedQtyLab->x,$Page_Height - $FormDesign->ReceivedQtyLab->y, $FormDesign->ReceivedQtyLab->FontSize, _('Received Qty'). ':');
$pdf->addText($FormDesign->PackageQtyLab->x,$Page_Height - $FormDesign->PackageQtyLab->y, $FormDesign->PackageQtyLab->FontSize, _('Packing Qty'). ':');

//$pdf->Line($FormDesign->HeaderLine->startx, $Page_Height - $FormDesign->HeaderLine->starty, $FormDesign->HeaderLine->endx,$Page_Height - $FormDesign->HeaderLine->endy);

$pdf->addText($FormDesign->ItemNumber->x,$Page_Height - $FormDesign->ItemNumber->y, $FormDesign->ItemNumber->FontSize, $WOHeader['stockid']);
$pdf->addText($FormDesign->ItemDesc->x,$Page_Height - $FormDesign->ItemDesc->y, $FormDesign->ItemDesc->FontSize, $WOHeader['description']);
$pdf->addText($FormDesign->RequiredDate->x,$Page_Height - $FormDesign->RequiredDate->y, $FormDesign->RequiredDate->FontSize, ConvertSQLDate($WOHeader['requiredby']) );
$pdf->addText($FormDesign->Lot->x,$Page_Height - $FormDesign->Lot->y, $FormDesign->Lot->FontSize, $SerialNo );
$pdf->addTextWrap($FormDesign->RequiredQty->x,$Page_Height - $FormDesign->RequiredQty->y, $FormDesign->RequiredQty->Length, $FormDesign->RequiredQty->FontSize, $WOHeader['qtyreqd'],'right' );
$pdf->addTextWrap($FormDesign->ReceivedQty->x,$Page_Height - $FormDesign->ReceivedQty->y, $FormDesign->ReceivedQty->Length, $FormDesign->ReceivedQty->FontSize, $WOHeader['qtyrecd'],'right' );
$pdf->addTextWrap($FormDesign->PackageQty->x,$Page_Height - $FormDesign->PackageQty->y, $FormDesign->PackageQty->Length, $FormDesign->PackageQty->FontSize, $PackQty,'right' );
$pdf->setFont('','');

if (!empty($PrintingComments)) {
   return;
}
$pdf->addText($FormDesign->MatReqTitle->x,$Page_Height - $FormDesign->MatReqTitle->y,$FormDesign->MatReqTitle->FontSize, _('Material Requirements for this Work Order'));
/*draw a square grid for entering line headings */
$pdf->Rectangle($FormDesign->HeaderRectangle->x, $Page_Height - $FormDesign->HeaderRectangle->y, $FormDesign->HeaderRectangle->width,$FormDesign->HeaderRectangle->height);
/*Set up headings */
$pdf->addText($FormDesign->Headings->Column1->x,$Page_Height - $FormDesign->Headings->Column1->y, $FormDesign->Headings->Column1->FontSize, _('Action'));
$pdf->addText($FormDesign->Headings->Column2->x,$Page_Height - $FormDesign->Headings->Column2->y, $FormDesign->Headings->Column2->FontSize, _('Item') );
$pdf->addText($FormDesign->Headings->Column3->x,$Page_Height - $FormDesign->Headings->Column3->y, $FormDesign->Headings->Column3->FontSize, _('Item Description') );
$pdf->addText($FormDesign->Headings->Column4->x,$Page_Height - $FormDesign->Headings->Column4->y, $FormDesign->Headings->Column4->FontSize, _('Qty Reqd') );
$pdf->addText($FormDesign->Headings->Column5->x,$Page_Height - $FormDesign->Headings->Column5->y, $FormDesign->Headings->Column5->FontSize, _('Qty Issued') );
$pdf->addText($FormDesign->Headings->Column6->x,$Page_Height - $FormDesign->Headings->Column6->y, $FormDesign->Headings->Column6->FontSize, _('Units') );
/*draw a rectangle to hold the data lines */
$pdf->Rectangle($FormDesign->DataRectangle->x, $Page_Height - $FormDesign->DataRectangle->y, $FormDesign->DataRectangle->width,$FormDesign->DataRectangle->height);
?>
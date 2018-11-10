<?php
/*PDF page header for inventory valuation report */
if ($PageNumber>1){
	$pdf->newPage();
}

$FontSize=10;
$YPos= $Page_Height-$Top_Margin;

$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,300,$FontSize,$_SESSION['CompanyRecord']['coyname']);

$YPos -=$line_height;

$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,300,$FontSize,_('Inventory Valuation for Categories between') . ' ' . $_POST['FromCriteria'] . ' ' . _('and') . ' ' . $_POST['ToCriteria'] . ' ' . _('at') . ' ' . $_POST['Location'] . ' ' . _('location'));
$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-120,$YPos,120,$FontSize,_('Printed') . ': ' . Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber);

$YPos -=(2*$line_height);

/*Draw a rectangle to put the headings in     */

$pdf->line($Left_Margin, $YPos+$line_height,$Page_Width-$Right_Margin, $YPos+$line_height);
$pdf->line($Left_Margin, $YPos+$line_height,$Left_Margin, $YPos- $line_height);
$pdf->line($Left_Margin, $YPos- $line_height,$Page_Width-$Right_Margin, $YPos- $line_height);
$pdf->line($Page_Width-$Right_Margin, $YPos+$line_height,$Page_Width-$Right_Margin, $YPos- $line_height);

/*set up the headings */
$Xpos = $Left_Margin+1;

if ($_POST['DetailedReport']=='Yes'){

	$LeftOvers = $pdf->addTextWrap($Xpos,$YPos,300-$Left_Margin,$FontSize,_('Category') . '/' . _('Item'), 'left');
	$LeftOvers = $pdf->addTextWrap(360,$YPos,60,$FontSize,_('Quantity'), 'center');
	$LeftOvers = $pdf->addTextWrap(422,$YPos,15,$FontSize,_('Units'), 'center');
	$LeftOvers = $pdf->addTextWrap(437,$YPos,60,$FontSize,_('Cost'), 'right');
	$LeftOvers = $pdf->addTextWrap(500,$YPos,60,$FontSize,_('Extended Cost'), 'right');
} else {
	$LeftOvers = $pdf->addTextWrap($Xpos,$YPos,320-$Left_Margin,$FontSize,_('Category'), 'center');
	$LeftOvers = $pdf->addTextWrap(360,$YPos,60,$FontSize,_('Quantity'), 'right');
	$LeftOvers = $pdf->addTextWrap(490,$YPos,60,$FontSize,_('Cost'), 'right');
}

$FontSize=8;
$YPos =$YPos - (2*$line_height);

$PageNumber++;

?>
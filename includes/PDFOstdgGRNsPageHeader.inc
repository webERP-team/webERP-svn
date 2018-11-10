<?php
/*PDF page header for outstanding GRNs report */
if ($PageNumber>1){
	$pdf->newPage();
}

$FontSize=10;
$YPos= $Page_Height-$Top_Margin;

$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,260,$FontSize,$_SESSION['CompanyRecord']['coyname']);

$YPos -=$line_height;

$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,300,$FontSize,_('Outstanding GRNs Valuation for Suppliers between') . ' ' . $_POST['FromCriteria'] . ' ' . _('and') . ' ' . $_POST['ToCriteria']);
$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-120,$YPos,220,$FontSize,_('Printed') . ': ' . Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber);

$YPos -=(2*$line_height);

/*Draw a rectangle to put the headings in     */

$pdf->line($Left_Margin, $YPos-$line_height,$Page_Width-$Right_Margin, $YPos-$line_height);
$pdf->line($Left_Margin, $YPos-$line_height,$Left_Margin, $YPos+10);
$pdf->line($Left_Margin, $YPos+10,$Page_Width-$Right_Margin, $YPos+10);
$pdf->line($Page_Width-$Right_Margin, $YPos-$line_height,$Page_Width-$Right_Margin, $YPos+10);

/*Draw a rectangle to put the details in     */

$pdf->line($Left_Margin, $Bottom_Margin, $Page_Width-$Right_Margin, $Bottom_Margin);
$pdf->line($Left_Margin, $Bottom_Margin, $Left_Margin, $YPos+10);
$pdf->line($Page_Width-$Right_Margin, $Bottom_Margin, $Page_Width-$Right_Margin, $YPos+10);

/*set up the headings */
$Xpos = $Left_Margin+1;

$LeftOvers = $pdf->addTextWrap(32,$YPos,40,$FontSize,_('GRN'), 'centre');
$LeftOvers = $pdf->addTextWrap(70,$YPos,40,$FontSize,_('Order') . ' #', 'centre');
$LeftOvers = $pdf->addTextWrap(110,$YPos,200,$FontSize,_('Item') . ' / ' . _('Description'), 'centre');
$LeftOvers = $pdf->addTextWrap(310,$YPos,50,$FontSize,_('Qty Recd'), 'centre');
$LeftOvers = $pdf->addTextWrap(360,$YPos,50,$FontSize,_('Qty Inv'), 'centre');
$LeftOvers = $pdf->addTextWrap(410,$YPos,50,$FontSize,_('Balance'), 'centre');
$LeftOvers = $pdf->addTextWrap(460,$YPos,50,$FontSize,_('Std Cost'), 'centre');
$LeftOvers = $pdf->addTextWrap(510,$YPos,50,$FontSize,_('Value'), 'centre');

$YPos =$YPos - (2*$line_height);

$PageNumber++;
$FontSize=8;
?>

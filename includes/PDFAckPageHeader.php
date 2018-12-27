<?php

if ($PageNumber > 1) {
	$pdf->newPage();
}

$XPos = $Page_Width / 2 - 140;
$pdf->addJpegFromFile($_SESSION['LogoFile'], $XPos + 127, 720, 0, 35);

$XPos = $XPos + 130;

$FontSize = 18;
$pdf->addText($XPos, 780, $FontSize, _('Acknowledgement'));
$FontSize = 12;
$YPos = 720;
$pdf->addText($XPos, $YPos, $FontSize, $_SESSION['CompanyRecord']['coyname']);
$FontSize = 10;
$pdf->addText($XPos, $YPos - 12, $FontSize, $_SESSION['CompanyRecord']['regoffice1']);
$pdf->addText($XPos, $YPos - 21, $FontSize, $_SESSION['CompanyRecord']['regoffice2']);
$pdf->addText($XPos, $YPos - 30, $FontSize, $_SESSION['CompanyRecord']['regoffice3'] . ', ' . $_SESSION['CompanyRecord']['regoffice4'] . ' ' . $_SESSION['CompanyRecord']['regoffice5']);
$pdf->addText($XPos, $YPos - 48, $FontSize, _('Ph') . ': ' . $_SESSION['CompanyRecord']['telephone']);
$pdf->addText($XPos, $YPos - 57, $FontSize, _('Fax') . ': ' . $_SESSION['CompanyRecord']['fax']);
$pdf->addText($XPos, $YPos - 66, $FontSize, $_SESSION['CompanyRecord']['email']);


$XPos = 46;
$YPos = 760;

$FontSize = 12;
$MyRow = array_map(html_entity_decode, $MyRow);
$pdf->addText($XPos, $YPos + 10, $FontSize, _('Delivery To') . ':');
$pdf->addText($XPos, $YPos - 3, $FontSize, $MyRow['deliverto']);
$pdf->addText($XPos, $YPos - 15, $FontSize, $MyRow['deladd1']);
$pdf->addText($XPos, $YPos - 30, $FontSize, $MyRow['deladd2']);
$pdf->addText($XPos, $YPos - 45, $FontSize, $MyRow['deladd3'] . ', ' . $MyRow['deladd4'] . ' ' . $MyRow['deladd5']);

$YPos -= 80;

$pdf->addText($XPos, $YPos, $FontSize, _('Bill To') . ':');
$pdf->addText($XPos, $YPos - 15, $FontSize, $MyRow['name']);
$pdf->addText($XPos, $YPos - 30, $FontSize, $MyRow['address1']);
$pdf->addText($XPos, $YPos - 45, $FontSize, $MyRow['address2']);
$pdf->addText($XPos, $YPos - 60, $FontSize, $MyRow['address3'] . ', ' . $MyRow['address4'] . ' ' . $MyRow['address5']);


$XPos = 50;
$YPos += 25;
/*draw a nice curved corner box around the delivery details */
/*from the top right */
$pdf->partEllipse($XPos + 200, $YPos + 60, 0, 90, 10, 10);
/*line to the top left */
$pdf->line($XPos + 200, $YPos + 70, $XPos, $YPos + 70);
/*Dow top left corner */
$pdf->partEllipse($XPos, $YPos + 60, 90, 180, 10, 10);
/*Do a line to the bottom left corner */
$pdf->line($XPos - 10, $YPos + 60, $XPos - 10, $YPos);
/*Now do the bottom left corner 180 - 270 coming back west*/
$pdf->partEllipse($XPos, $YPos, 180, 270, 10, 10);
/*Now a line to the bottom right */
$pdf->line($XPos, $YPos - 10, $XPos + 200, $YPos - 10);
/*Now do the bottom right corner */
$pdf->partEllipse($XPos + 200, $YPos, 270, 360, 10, 10);
/*Finally join up to the top right corner where started */
$pdf->line($XPos + 210, $YPos, $XPos + 210, $YPos + 60);


$YPos -= 90;
/*draw a nice curved corner box around the billing details */
/*from the top right */
$pdf->partEllipse($XPos + 200, $YPos + 60, 0, 90, 10, 10);
/*line to the top left */
$pdf->line($XPos + 200, $YPos + 70, $XPos, $YPos + 70);
/*Dow top left corner */
$pdf->partEllipse($XPos, $YPos + 60, 90, 180, 10, 10);
/*Do a line to the bottom left corner */
$pdf->line($XPos - 10, $YPos + 60, $XPos - 10, $YPos);
/*Now do the bottom left corner 180 - 270 coming back west*/
$pdf->partEllipse($XPos, $YPos, 180, 270, 10, 10);
/*Now a line to the bottom right */
$pdf->line($XPos, $YPos - 10, $XPos + 200, $YPos - 10);
/*Now do the bottom right corner */
$pdf->partEllipse($XPos + 200, $YPos, 270, 360, 10, 10);
/*Finally join up to the top right corner where started */
$pdf->line($XPos + 210, $YPos, $XPos + 210, $YPos + 60);

$pdf->addTextWrap($Page_Width - $Right_Margin - 200, $Page_Height - $Top_Margin - $FontSize * 1, 200, $FontSize, _('Order Number') . ': ' . $_GET['AcknowledgementNo'], 'right');
$pdf->addTextWrap($Page_Width - $Right_Margin - 200, $Page_Height - $Top_Margin - $FontSize * 2, 200, $FontSize, _('Customer P/O') . ': ' . $MyRow['customerref'], 'right');
$pdf->addTextWrap($Page_Width - $Right_Margin - 200, $Page_Height - $Top_Margin - $FontSize * 3, 200, $FontSize, _('Date') . ': ' . ConvertSQLDate($MyRow['orddate']), 'right');

$pdf->addText($Page_Width / 2 - 10, $YPos + 15, $FontSize, _('All amounts stated in') . ' - ' . $MyRow['currcode']);

$YPos -= 45;
$XPos = 40;

$FontSize = 10;
$LeftOvers = $pdf->addTextWrap($XPos + 2, $YPos, 100, $FontSize, _('Item Code'), 'left');
$LeftOvers = $pdf->addTextWrap(120, $YPos, 235, $FontSize, _('Item Description'), 'left');
$LeftOvers = $pdf->addTextWrap(270, $YPos, 85, $FontSize, _('Ship Date'), 'right');
$LeftOvers = $pdf->addTextWrap(340, $YPos, 85, $FontSize, _('Quantity'), 'right');
$LeftOvers = $pdf->addTextWrap(420, $YPos, 85, $FontSize, _('Units'), 'left');
$LeftOvers = $pdf->addTextWrap(420, $YPos, 85, $FontSize, _('Price'), 'right');
$LeftOvers = $pdf->addTextWrap($Page_Width - $Right_Margin - 90, $YPos, 90, $FontSize, _('Total'), 'right');


/*draw a box with nice round corner for entering line items */
/*90 degree arc at top right of box 0 degrees starts a bottom */
$pdf->partEllipse($Page_Width - $Right_Margin - 0, $Bottom_Margin + 540, 0, 90, 10, 10);
/*line to the top left */
$pdf->line($Page_Width - $Right_Margin - 0, $Bottom_Margin + 550, $Left_Margin + 10, $Bottom_Margin + 550);

/*line under headings to top left */
$pdf->line($Page_Width - $Right_Margin + 10, $Bottom_Margin + 525, $Left_Margin, $Bottom_Margin + 525);


/*Dow top left corner */
$pdf->partEllipse($Left_Margin + 10, $Bottom_Margin + 540, 90, 180, 10, 10);
/*Do a line to the bottom left corner */
$pdf->line($Left_Margin, $Bottom_Margin + 540, $Left_Margin, $Bottom_Margin + 10);
/*Now do the bottom left corner 180 - 270 coming back west*/
$pdf->partEllipse($Left_Margin + 10, $Bottom_Margin + 10, 180, 270, 10, 10);
/*Now a line to the bottom right */
$pdf->line($Left_Margin + 10, $Bottom_Margin, $Page_Width - $Right_Margin - 0, $Bottom_Margin);
/*Now do the bottom right corner */
$pdf->partEllipse($Page_Width - $Right_Margin - 0, $Bottom_Margin + 10, 270, 360, 10, 10);
/*Finally join up to the top right corner where started */
$pdf->line($Page_Width - $Right_Margin + 10, $Bottom_Margin + 10, $Page_Width - $Right_Margin + 10, $Bottom_Margin + 540);

$YPos -= $line_height * 2;

$FontSize = 12;

?>
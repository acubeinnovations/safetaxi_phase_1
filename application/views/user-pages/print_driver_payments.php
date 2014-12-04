<?php 
tcpdf();
$pdf =  new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SafeTaxi');
$pdf->SetTitle('Driver Payments');


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 8);

ob_start();
$content='<table border="1" style="line-height: 10px">

<tr>

<td>Sl.No</td>
<td>Date</td>
<td>Vehicle Number</td>
<td>Type</td>
<td>Amount</td>

</tr> 

<tr>

<td>1</td>
<td>'.$values[0]['Payment_date'].'</td>
<td>'.$values[0]['Driver_vehicle_registration'].'</td>
<td>'.$values[0]['Voucher_type'].'</td>
<td>'.$values[0]['Driver_debit'].'</td>

</tr> 
<hr>


<table>

<table border="1" style="line-height: 10px"><tr><td>1</td><td>1</td></tr></table>


';



ob_end_clean();

//$pdf->writeHTML($content, true, false, false, false, '');
//$pdf->writeHTML($content1, true, false, false, false, '');

//echo $content; exit;
$pdf->writeHTML($content, true, false, false, false, '');
$file_name='Payment-'.$values[0]['Driver_name'].'.pdf';
$pdf->Output($file_name, 'I');



?>

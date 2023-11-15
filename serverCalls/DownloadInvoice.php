<?php
/* 
Change the directory of the image as per thes erver
*/
include '../Connect.php';

// require_once dirname(__DIR__) . '../vendor/autoload.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

$InvoiceID = $_REQUEST['ID'];


$sql = "SELECT * FROM invoices WHERE ID = '$InvoiceID'";
$result = mysqli_query($con, $sql);
$invoice = mysqli_fetch_assoc($result);

$FileNumber = $invoice["FileNumber"];
$sql = "SELECT * FROM wip WHERE FileNumber = '$FileNumber' ";
$result = mysqli_query($con, $sql);
$FileInfo = mysqli_fetch_assoc($result);

$ClientContactID = $FileInfo["Client1Contact_ID"];
$sql = "SELECT * FROM client_contact_details WHERE ID = '$ClientContactID'";
$result = mysqli_query($con, $sql);
$ClientInfo = mysqli_fetch_assoc($result);

$options = new Options();
$options->set('isRemoteEnabled', true);
$document = new Dompdf($options);

$timestamp = strtotime($invoice['Date']);

$Date = date("d-m-Y", $timestamp);
$textToPrint =  '
<html>
<head>
<style>
@page { size: a4 potrait;
    margin-bottom: 2.3cm;
 }
      
      
    </style>
</head>

<body>

<header class="header"><div style="float: right;"><img src="/images/FinalLogo.png" alt="ANP Logo" width="200" height="75"></div></header>



<main>

<table style="width:100%;">

    <thead>
        <tr>
            <th style="width:90%;"></th>
            <th></th>
            
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><b>Our ref: </b>' . $FileNumber . '</td>
            <td></td>
            
        </tr>
        <tr>
            <td><b>Invoice No: </b>' . $invoice['InvoiceNumber'] . '</td>
            <td></td>
            
        </tr>
        <tr>
            <td><b>Date: </b>' . $Date . '</td>
            <td></td>
            
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            
        </tr>
        <tr>
            <td><b>Private &amp; Confidential </b></td>
            <td></td>
            
        </tr>


        <tr>
        <td>' . $ClientInfo['ClientName'] . '</td>
        <td></td>
       
    </tr>
           
        </tr>
        <tr>
            <td>' . $ClientInfo['AddressLine1'] . '</td>
            <td></td>
            
        </tr>
        <tr>
            <td>' . $ClientInfo['AddressLine2'] . '</td>
            <td></td>
            
        </tr>
        <tr>
            <td>' . $ClientInfo['County'] . '</td>
            <td></td>
            
        </tr>
        <tr>
            <td>' . $ClientInfo['Postcode'] . '</td>
            <td></td>
            
        </tr>
        ';
if ($invoice['PayableBy'] != 'Client') {
    $textToPrint = $textToPrint . '
    <tr>
            <td><b>Payable by ' . $invoice['PayableBy'] . '</b></td>
            <td></td>
            
        </tr>
        ';
}
if ($invoice['ByEmail'] == 1 && $invoice['ByPost'] == 1) {
    $textToPrint = $textToPrint . '
        
        <tr>
            <td style="text-align: right;" colspan="2"><b>By post and email to: </b>' . $ClientInfo['Email'] . '</td> 
            
        </tr>';
} elseif ($invoice['ByEmail'] == 1 && $invoice['ByPost'] == 0) {
    $textToPrint = $textToPrint . '
        
        <tr>
            <td style="text-align: right;" colspan="2"><b>By email to: </b>' . $ClientInfo['Email'] . '</td> 
            
        </tr>';
} elseif ($invoice['ByEmail'] == 0 && $invoice['ByPost'] == 1) {
    $textToPrint = $textToPrint . '
            <tr>
                <td style="text-align: right;" colspan="2"><b>By Post Only</td> 
                
            </tr>';
}

$textToPrint = $textToPrint . '
        
        <tr>
            <td><b>Re: ' . $FileInfo['MatterDescription'] . '</b></td>
            <td></td>
            
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
           
        </tr>
        <tr>
            <td align="justify" colspan="2">' . $invoice['Description'] . '</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td style="text-align: center;"></td>
            
        </tr>';
$ourCosts = json_decode($invoice['OurCosts']);
$ourCostsDesc = json_decode($invoice['OurCostsDesc']);
for ($i = 0; $i < count($ourCosts); $i++) {
    $textToPrint = $textToPrint . '<tr>
            <td><b>' . $ourCostsDesc[$i] . '</b></td>
            <td style="text-align: center;';
    if ($i == 0) {
        $textToPrint = $textToPrint . ' border-top: solid; border-top-width: thin;';
    }
    $textToPrint = $textToPrint . '">£' . number_format(round($ourCosts[$i], 2), 2) . '</td>
           
            </tr>';
}
$totalCosts = array_sum($ourCosts);
$VAT = round($totalCosts, 2) * 0.2;
$textToPrint = $textToPrint . '
        
        <tr>
            <td>Add VAT @20%</td>
            <td style="text-align: center; border-top: solid; border-top-width: thin;" >£' . number_format($VAT, 2) . '</td>
            
        </tr>';
$totalCostsAndVAT = round($totalCosts + $VAT, 2);

$textToPrint = $textToPrint . '
        <tr>
            <td><b>Total Costs and VAT - A</b></td>
            <td style="text-align: center; border-bottom: solid; border-bottom-width: thin; border-top: solid; border-top-width: thin;"><b>£' . number_format($totalCostsAndVAT, 2) . '</b></td>
            
        </tr>
        ';

$PinkSlipIDs = json_decode($invoice['DisbsIDs']);
$GreenSlipIDs = json_decode($invoice['GreenSlip_IDs']);
$GreenSlipFrom = false;
$GreenSlipTo = false;
if ($GreenSlipIDs != null) {
    foreach ($GreenSlipIDs as $ID) {
        $sql = "SELECT FileNumberFrom, FileNumberTo FROM ledger_accounts_transfers WHERE ID ='$ID '";
        $result = mysqli_query($con, $sql);
        $data = $result->fetch_assoc();

        if ($data['FileNumberFrom'] == $FileNumber) {
            $GreenSlipFrom = true;
        } else {
            $GreenSlipTo = true;
        }

        if ($GreenSlipFrom && $GreenSlipTo) {
            break;
        }
    }
}


$TotalPinkSlips = 0;
if ($PinkSlipIDs != null || $GreenSlipFrom) {
    $textToPrint = $textToPrint . '
            <tr>
            <td>&nbsp;</td>
            <td></td>
            
             </tr>
            <tr>
            <td><b>Add Disbursements</b></td>
            <td></td>
            
        </tr>';
    if ($PinkSlipIDs != null) {
        foreach ($PinkSlipIDs as $ID) {
            $sql = "SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";
            $result = mysqli_query($con, $sql);
            $slip = mysqli_fetch_assoc($result);


            $timestamp = strtotime($slip['Date']);
            $Date = date("d-m-Y", $timestamp);

            $textToPrint = $textToPrint . '<tr>
             <td>' . $slip['Description'] . ' - ' . $Date . ' </td>
             <td style="text-align: center; ';

            if ($TotalPinkSlips == 0) {
                $textToPrint = $textToPrint . 'border-top: solid; border-top-width: thin';
            }
            $textToPrint = $textToPrint . '">£' . number_format(round($slip['Amount'], 2), 2) . '</td>
             </tr>';
            $TotalPinkSlips = $TotalPinkSlips + $slip['Amount'];
        }
    }

    if ($GreenSlipIDs != null) {
        foreach ($GreenSlipIDs as $GSID) {
            $sql = "SELECT * FROM ledger_accounts_transfers WHERE ID = '$GSID' LIMIT 1";
            $result = mysqli_query($con, $sql);
            $slip = mysqli_fetch_assoc($result);

            $timestamp = strtotime($slip['Date']);
            $Date = date("d-m-Y", $timestamp);

            if ($slip['FileNumberFrom'] == $FileNumber) {


                $AmtInvoicedFrom = $slip['Amount'];

                $textToPrint = $textToPrint . '<tr><td>Transfer to ' . $slip['FileNumberTo'] . ' - ' . $Date . ' </td>';
                $textToPrint = $textToPrint . '<td style="text-align: center;">£' . number_format(round($AmtInvoicedFrom, 2), 2) . '</td> </tr>';
                $TotalPinkSlips = $TotalPinkSlips + $AmtInvoicedFrom;
            }
        }
    }

    $textToPrint = $textToPrint . '
            
    <tr>
    <td><b>Total Disbursements - B</b></td>
    <td style="text-align: center; border-bottom: solid; border-top: solid; border-top-width: thin; border-bottom-width: thin;"><b>£' . number_format(round($TotalPinkSlips, 2), 2) . '</b></td>

</tr>';
}



$BlueSlipIDs = json_decode($invoice['MOA_IDs']);
$TotalBlueSlips = 0;
if ($BlueSlipIDs != null || $GreenSlipTo) {
    $textToPrint = $textToPrint . '
        
            <tr>
                <td>&nbsp;</td>
                <td></td>
                
            </tr>
            <tr>
            <td><b>Less Monies Received</b></td>
            <td></td>
            
        </tr>';
    if ($BlueSlipIDs != null) {
        foreach ($BlueSlipIDs as $ID) {
            $sql = "SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";
            $result = mysqli_query($con, $sql);
            $slip = mysqli_fetch_assoc($result);


            $timestamp = strtotime($slip['Date']);
            $Date = date("d-m-Y", $timestamp);


            $textToPrint = $textToPrint . '<tr>
                     <td>Monies received - ' . $Date . ' </td>
                     <td style="text-align: center;';

            if ($TotalBlueSlips == 0) {
                $textToPrint = $textToPrint . 'border-top: solid; border-top-width: thin';
            }
            $obj = json_decode($slip['AmountInvoiced']);



            $InvoiceData = json_decode($obj->{"$InvoiceID"}, true);
            $AmountInvoiced  = $InvoiceData['Amount'];

            $textToPrint = $textToPrint . '">£' . number_format(round($AmountInvoiced, 2), 2) . '</td>
                     </tr>';

            $TotalBlueSlips = $TotalBlueSlips + $AmountInvoiced;
        }
    }
    if ($GreenSlipIDs) {
        foreach ($GreenSlipIDs as $GSID) {
            $sql = "SELECT * FROM ledger_accounts_transfers WHERE ID = '$GSID' LIMIT 1";
            $result = mysqli_query($con, $sql);
            $slip = mysqli_fetch_assoc($result);

            $timestamp = strtotime($slip['Date']);
            $Date = date("d-m-Y", $timestamp);


            if ($slip['FileNumberTo'] == $FileNumber) {
                $obj = json_decode($slip['AmountInvoicedTo']);

                $AmtInvoicedTo = $obj->{"$InvoiceID"};

                $textToPrint = $textToPrint . '<tr><td>Transfer from ' . $slip['FileNumberFrom'] . ' - ' . $Date . ' </td>';
                $textToPrint = $textToPrint . '<td style="text-align: center;">£' . number_format(round($AmtInvoicedTo, 2), 2) . '</td> </tr>';
                $TotalBlueSlips = $TotalBlueSlips + $AmtInvoicedTo;
            }
        }
    }


    $textToPrint = $textToPrint . '
                 <tr>
                 <td><b>Total Monies Received - ';
    if ($TotalPinkSlips == 0) {
        $textToPrint = $textToPrint . 'B</b></td>';
    } else {
        $textToPrint = $textToPrint . 'C</b></td>';
    }
    $textToPrint = $textToPrint . '
                 <td style="text-align: center; border-bottom: solid; border-top: solid; border-top-width: thin; border-bottom-width: thin;"><b>£' . number_format(round($TotalBlueSlips, 2), 2) . '</b></td>
               
                </tr>';
}

$textToPrint = $textToPrint . '
        <tr>
            <td>&nbsp;</td>
            <td></td>
            
        </tr>';
$finalFigure = (round($totalCostsAndVAT, 2) + round($TotalPinkSlips, 2)) - round($TotalBlueSlips, 2);

if ($finalFigure >= 0) {
    $textToPrint = $textToPrint . '
            <tr>
            <td><b>Total Due: ';
    if ($TotalPinkSlips == 0 && $TotalBlueSlips == 0) {
        $textToPrint = $textToPrint . 'A</b></td>';
    } elseif ($TotalPinkSlips == 0 && $TotalBlueSlips > 0) {
        $textToPrint = $textToPrint . 'A - B</b></td>';
    } elseif ($TotalPinkSlips > 0 && $TotalBlueSlips == 0) {
        $textToPrint = $textToPrint . 'A+B</b></td>';
    } else {
        $textToPrint = $textToPrint . '(A+B) - C</b></td>';
    }

    $textToPrint = $textToPrint . '<td style="text-align: center; border-top: solid; border-top-width: thin; border-bottom: solid;  border-bottom-style:double"><b>£' . number_format($finalFigure, 2) . '</b></td>
            
        </tr>';
} else {
    $textToPrint = $textToPrint . '
            <tr>
                <td><b>Monies Remaining On Account: ';

    if ($TotalPinkSlips == 0 && $TotalBlueSlips > 0) {
        $textToPrint = $textToPrint . 'B - A</b></td>';
    } else {
        $textToPrint = $textToPrint . 'C - (A+B)</b></td>';
    }

    $textToPrint = $textToPrint . '</b></td>
                <td style="text-align: center; border-top: solid; border-top-width: thin; border-bottom: solid;  border-bottom-style:double"><b>£' . number_format($finalFigure * (-1), 2) . '</b></td>
            
            </tr>';
}
$textToPrint = $textToPrint . '
        
        
        <tr>
            <td>&nbsp;</td>
            <td></td>
            
        </tr>
        <tr>
            <td><b style="font-style: italic;">With Compliments</b></td>
            <td></td>
           
        </tr>
        <tr>
            <td><b>ANP Solicitors</b></td>
            <td></td>
         
        </tr>';

if ($finalFigure > 0) {

    $textToPrint = $textToPrint . '
 <tr>
        <td>&nbsp;</td>
        <td></td>
            
        </tr>
	<tr>
	<td style=" font-size: 10"><b>Account Name:</b> ANP Solicitors Limited; <b>Sort Code:</b> 20-70-93; <b>Account No:</b> 13065049;  <b>Ref:</b> ' . $FileNumber . ' <td>
	</tr> ';
}
// Bank: Barclays; Sort Code:20-70-93; Account No: 13065049; Account Name: ANP Solicitors Limited; Reference: ' . $FileNumber . '



$textToPrint = $textToPrint . '
       
    </tbody>
</table>
</main>
</body>
</html>
';


$document->loadHtml($textToPrint);
$document->render();
$canvas = $document->getCanvas();
if ($invoice['State'] == 'Draft') {
    $fontMetrics = new FontMetrics($canvas, $options);
    $w = $canvas->get_width();
    $h = $canvas->get_height();

    // Get font family file 
    $font = $fontMetrics->getFont('Times New Roman');

    // Specify watermark text 
    $text = "DRAFT";

    $textHeight = $fontMetrics->getFontHeight($font, 75);
    $textWidth = $fontMetrics->getTextWidth($text, $font, 75);

    // Set text opacity 
    $canvas->set_opacity(0.5);


    $x = (120);
    $y = (500);


    $canvas->page_text($x, $y, $text, $font, 150, array(1, 0, 0), 0, 30, 315);
    $canvas->set_opacity(1);
}





makeCanvas("ANP Solicitors is a trading name of ANP Solicitors Limited", 199, 65, $canvas);
makeCanvas("Registered in England and Wales – Company No: 6948759 | Registered office at 290 Kiln Road, Benfleet, Essex SS7 1QT", 87.5, 55, $canvas);
makeCanvas("T: 01702 556688 | F: 01702 556696 | E: info@anpsolicitors.com | www.anpsolicitors.com", 145, 45, $canvas);
makeCanvas("This firm is authorised and regulated by the Solicitors Regulatory Authority", 170, 35, $canvas);
makeCanvas("A list of directors is open to inspection at the office", 215, 25, $canvas);
makeCanvas("VAT No. 977 542 767 | SRA No. 515388", 233, 15, $canvas);

function makeCanvas($fooText, $fooWidth, $fooheight, $fooCanvas)
{

    $GLOBALS['fooWidth'] = $fooWidth;
    $GLOBALS['fooHeight'] = $fooheight;
    $GLOBALS['fooText'] = $fooText;
    $fooCanvas->page_script(function ($pageNumber, $pageCount, $fooCanvas, $fontMetrics) {
        $text = $GLOBALS['fooText'];
        $font = $fontMetrics->getFont('Times New Roman');
        $pageWidth = $fooCanvas->get_width();
        $pageHeight = $fooCanvas->get_height();
        $size = 9;
        $color = array(0.66, 0.66, 0.66);
        $width = $fontMetrics->getTextWidth($text, $font, $size);
        $fooCanvas->text($pageWidth - $width - $GLOBALS['fooWidth'], $pageHeight - $GLOBALS['fooHeight'], $text, $font, $size, $color);
    });
}


$document->stream("Invoice " . $invoice['InvoiceNumber'] . " - " . $ClientInfo['ClientName'] . '(' . $FileInfo['MatterDescription'] . ')');
// , array('Attachment' => 0)
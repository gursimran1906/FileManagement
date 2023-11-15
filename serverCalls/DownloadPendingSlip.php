<?php

include '../Connect.php';

require_once '../vendor/autoload.php';

?>

<?php

use Dompdf\Dompdf;

$DateToFetchData = $_REQUEST['dateOfLastSlipsDone'];

$d =  $DateToFetchData . " 00:00:00";


// $todayStartTS = date("Y-m-d 00:00:00");
$todayStartTS = '2023-11-01 00:00:00';



$PendingSlipPdf = new Dompdf();
$sql = "SELECT FileNumber,
LedgerAccount as 'Account',
ModeOfPMT as 'Mode Of Payment',
Amount,
PMTToOrFrom as 'Type Of Slip',
PMTPerson  as 'Payment To/From',
Description, 
DATE_FORMAT(Date,'%d/%m/%Y') as Date FROM pmts_slip WHERE Timestamp > '$d' AND PMTToOrFrom < 2 AND Timestamp < '$todayStartTS' ORDER BY LedgerAccount,PMTToOrFrom,Date";

$resultPBISlip = mysqli_query($con, $sql);

$sql = "SELECT FileNumberFrom as 'File Number From',
FileNumberTo as 'File Number To',
FromLedgerAccount as 'From Account',
ToLedgerAccount as 'To Account',
Amount, Description, 
DATE_FORMAT(Date,'%d/%m/%Y') as Date FROM ledger_accounts_transfers WHERE Timestamp > '$d' AND Timestamp < '$todayStartTS'";

$resultGreenSlip = mysqli_query($con, $sql);

$Sql = "SELECT
FileNumber as 'File Number',
InvoiceNumber as 'Invoice No.',
Date,
OurCosts, MOA_IDs as 'VAT', DisbsIDs as 'Dibsursements' 
FROM invoices WHERE State = '' AND Timestamp > '$d' AND Timestamp < '$todayStartTS'";



$data = $con->query($Sql);

$resultInvoices = array();

//checks whether there is a row in the database
if ($data->num_rows > 0) {
    // output data of each row
    while ($row = $data->fetch_assoc()) {
        $resultInvoices[] = $row; // adds the row from the database into the array
    }
}

for ($i = 0; $i < count($resultInvoices); $i++) {
    $OurCosts = array_sum(json_decode($resultInvoices[$i]['OurCosts']));

    $resultInvoices[$i]['OurCosts'] = "£" . number_format(round($OurCosts, 2), 2);
    $resultInvoices[$i]['VAT'] = "£" . number_format(round(0.2 * $OurCosts, 2), 2);

    $timestamp = strtotime($resultInvoices[$i]['Date']);
    $Date = date("d-m-Y", $timestamp);
    $resultInvoices[$i]['Date'] = $Date;

    $Disbs = json_decode($resultInvoices[$i]['Dibsursements']);

    if ($Disbs != null) {
        $DisbsText = "";
        foreach ($Disbs as $ID) {
            $sql = "SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";
            $result = mysqli_query($con, $sql);
            $slip = mysqli_fetch_assoc($result);

            $timestamp = strtotime($slip['Date']);
            $Date = date("d-m-Y", $timestamp);

            $DisbsText = $DisbsText . "(" . $Date . ", £" . number_format(round($slip['Amount'], 2), 2) . ")  ";
        }
        $resultInvoices[$i]['Dibsursements'] = $DisbsText;
    }
}



function display_PBI_slip($data)
{

    $output = '<table class="table" >';
    foreach ($data as $key => $var) {
        if ($key === 0) {
            $output .= '<thead><tr>';
        }
        foreach ($var as $k => $v) {
            if ($key === 0) {
                $output .= '<th>' . $k . '</th>';
            }
        }
        break;
    }
    $output .= '</thead> <tbody>';
    foreach ($data as $key => $var) {

        // if ($key != 0){
        $output .= '<tr style="border: 1px solid black;">';
        foreach ($var as $k => $v) {

            if ($v == 'O') {
                $output .= '<td style="border: 1px solid black;">Office</td>';
            } elseif ($v == 'C') {
                $output .= '<td style="border: 1px solid black;">' . 'Client</td>';
            } elseif ($v == '1') {
                $output .= '<td style="border: 1px solid black;">' . 'Pink Slip' . '</td>';
            } elseif ($v == '0') {
                $output .= '<td style="border: 1px solid black;">' . 'Blue Slip' . '</td>';
            } elseif ($v === '2') {
                $output .= '<td style="border: 1px solid black;">' . 'Invoice' . '</td>';
            } else {
                $output .= '<td style="border: 1px solid black;">' . $v . '</td>';
            }
        }
        $output .= '</tr>';
    }
    // }
    $output .= '</tbody></table>';
    return $output;
}

function displayInvoices($data)
{

    $output = '<table class="table" >';
    foreach ($data as $key => $var) {
        if ($key === 0) {
            $output .= '<thead><tr>';
        }
        foreach ($var as $k => $v) {
            if ($key === 0) {
                $output .= '<th>' . $k . '</th>';
            }
        }
        break;
    }
    $output .= '</thead> <tbody>';
    foreach ($data as $key => $var) {

        // if ($key != 0){
        $output .= '<tr style="border: 1px solid black;">';
        foreach ($var as $k => $v) {


            $output .= '<td style="border: 1px solid black;">' . $v . '</td>';
        }
        $output .= '</tr>';
    }
    // }
    $output .= '</tbody></table>';
    return $output;
}

function display_Green_slip($data)
{

    $output = '<table class="table" >';
    foreach ($data as $key => $var) {
        if ($key === 0) {
            $output .= '<thead><tr>';
        }
        foreach ($var as $k => $v) {
            if ($key === 0) {
                $output .= '<th>' . $k . '</th>';
            }
        }
        break;
    }
    $output .= '</thead> <tbody>';
    foreach ($data as $key => $var) {

        // if ($key != 0){
        $output .= '<tr style="border: 1px solid black;">';
        foreach ($var as $k => $v) {

            if ($v == 'O') {
                $output .= '<td style="border: 1px solid black;">Office</td>';
            } elseif ($v == 'C') {
                $output .= '<td style="border: 1px solid black;">' . 'Client</td>';
            } else {
                $output .= '<td style="border: 1px solid black;">' . $v . '</td>';
            }
        }
        $output .= '</tr>';
    }
    // }
    $output .= '</tbody></table>';
    return $output;
}

if ($resultInvoices != null) {
    $HtmlDataInvoices = displayInvoices($resultInvoices);
} else {
    $HtmlDataInvoices = "<h3>No Invoices available from the date ($d).</h3>";
}
if (mysqli_num_rows($resultPBISlip) != 0) {
    $htmlDataPBISlips = display_PBI_slip($resultPBISlip);
} else {
    $htmlDataPBISlips = "<h3>No Pink or Blue slips available from the date ($d).</h3>";
}
if (mysqli_num_rows($resultGreenSlip) != 0) {
    $htmlDataGreenSlips = display_Green_slip($resultGreenSlip);
} else {
    $htmlDataGreenSlips = "<h3>No Green Slips available from the date ($d).</h3>";
}





$combinedHtml = "<h1> Invoices </h1> <br> " . $HtmlDataInvoices . " <h1> Pink and Blue Slips </h1> <br>" . $htmlDataPBISlips . "<br> <br><h1> Green Slips</h1><br>" . $htmlDataGreenSlips;
$combinedHtml = $combinedHtml . "<br><br><b>Signed: </b>.............................................";
$combinedHtml = $combinedHtml . "<br><b>Dated: </b>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;/";



$PendingSlipPdf->set_paper('letter', 'landscape');
$PendingSlipPdf->loadHtml($combinedHtml);
$PendingSlipPdf->render();
$PendingSlipPdf->stream("Slips (" . date("d.m.Y") . ")", array("Attachment" => false));


?>
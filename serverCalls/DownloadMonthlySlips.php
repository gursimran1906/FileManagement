<?php

include '../Connect.php';

require_once '../vendor/autoload.php';

?>

<?php

use Dompdf\Dompdf;

$FromDate = $_REQUEST['fromDate'] . " 00:00:00";

$ToDate = $_REQUEST['toDate'] . " 00:00:00";

// PMTToOrFrom, 1 = pink slip, 0 = blue slip


// Office Pink Slips
$sql = "SELECT FileNumber,
        LedgerAccount as 'Account',
        ModeOfPMT as 'Mode Of Payment',
        Amount,
        PMTToOrFrom as 'Type Of Slip',
        PMTPerson  as 'Payment To/From',
        Description, 
        DATE_FORMAT(Date,'%d/%m/%Y') as Date 
        FROM pmts_slip 
        WHERE Date >= '$FromDate' AND PMTToOrFrom = 1 AND LedgerAccount = 'O'  AND Date <= '$ToDate' 
        ORDER BY Date ASC";

$resultOfficePink = $con->query($sql);



// Office Blue slips
$sql = "SELECT FileNumber,
        LedgerAccount as 'Account',
        ModeOfPMT as 'Mode Of Payment',
        Amount,
        PMTToOrFrom as 'Type Of Slip',
        PMTPerson  as 'Payment To/From',
        Description, 
        DATE_FORMAT(Date,'%d/%m/%Y') as Date 
        FROM pmts_slip 
        WHERE Date >= '$FromDate' AND PMTToOrFrom = 0 AND LedgerAccount = 'O' AND Date <= '$ToDate' 
        ORDER BY Date ASC";

$resultOfficeBlue = $con->query($sql);


//Client Pink Slips
$sql = "SELECT FileNumber,
        LedgerAccount as 'Account',
        ModeOfPMT as 'Mode Of Payment',
        Amount,
        PMTToOrFrom as 'Type Of Slip',
        PMTPerson  as 'Payment To/From',
        Description, 
        DATE_FORMAT(Date,'%d/%m/%Y') as Date 
        FROM pmts_slip 
        WHERE Date >= '$FromDate' AND PMTToOrFrom = 1 AND LedgerAccount = 'C' AND Date <= '$ToDate' 
        ORDER BY Date ASC";

$resultClientPink = $con->query($sql);


// Client Blue Slips
$sql = "SELECT FileNumber,
        LedgerAccount as 'Account',
        ModeOfPMT as 'Mode Of Payment',
        Amount,
        PMTToOrFrom as 'Type Of Slip',
        PMTPerson  as 'Payment To/From',
        Description, 
        DATE_FORMAT(Date,'%d/%m/%Y') as Date 
        FROM pmts_slip 
        WHERE Date >= '$FromDate' AND PMTToOrFrom = 0 AND LedgerAccount = 'C' AND Date <= '$ToDate' 
        ORDER BY Date ASC";

$resultClientBlue = $con->query($sql);





$sql = "SELECT FileNumberFrom as 'File Number From',
FileNumberTo as 'File Number To',
FromLedgerAccount as 'From Account',
ToLedgerAccount as 'To Account',
Amount, Description, 
DATE_FORMAT(Date,'%d/%m/%Y') as Date 
FROM ledger_accounts_transfers 
WHERE Date >= '$FromDate' AND Date <= '$ToDate' 
ORDER BY Date ASC";


$resultGreenSlip = mysqli_query($con, $sql);



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
       
        $output .= '<th>Checked Millenium </th> <th>Checked Bank A/c</th> </tr>';
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
            
            } else {
                $output .= '<td style="border: 1px solid black;">' . $v . '</td>';
            }
        }
        $output .= '<td style="border: 1px solid black;"> </td><td style="border: 1px solid black;"></td>';
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
        
        $output .= '<th>Checked Millenium</th> <th>Checked Bank A/c</th>';
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
        $output .= '<td style="border: 1px solid black;"> </td><td style="border: 1px solid black;"></td>';
       
        $output .= '</tr>';
    }
    // }

    $output .= '</tbody></table>';
    return $output;
}


if (mysqli_num_rows($resultClientPink) != 0) {
    $htmlDataClientPink = display_PBI_slip($resultClientPink);
} else {
    $htmlDataClientPink = "<h3>No Client Pink slips available from ($FromDate) to ($ToDate).</h3>";
}
if (mysqli_num_rows($resultClientBlue) != 0) {
    $htmlDataClientBlue = display_PBI_slip($resultClientBlue);
} else {
    $htmlDataClientBlue = "<h3>No Client Blue slips available from ($FromDate) to ($ToDate).</h3>";
}
if (mysqli_num_rows($resultOfficeBlue) != 0) {
    $htmlDataOfficeBlue = display_PBI_slip($resultOfficeBlue);
} else {
    $htmlDataOfficeBlue = "<h3>No Office Blue slips available from ($FromDate) to ($ToDate).</h3>";
}
if (mysqli_num_rows($resultOfficePink) != 0) {
    $htmlDataOfficePink = display_PBI_slip($resultOfficePink);
} else {
    $htmlDataOfficePink = "<h3>No Office Pink slips available from( $FromDate) to ($ToDate).</h3>";
}
if (mysqli_num_rows($resultGreenSlip) != 0) {
    $htmlDataGreenSlips = display_Green_slip($resultGreenSlip);
} else {
    $htmlDataGreenSlips = "<h3>No Green Slips available from the date ($FromDate).</h3>";
}





$combinedHtml = "<h1>Client Pink Slips</h1> <br>" . $htmlDataClientPink . "<br> <h1>Client Blue Slips</h1> <br>" . $htmlDataClientBlue . "<br> <h1>Office Pink Slips</h1> <br>" . $htmlDataOfficePink . "<br> <h1>Office Blue Slips</h1> <br>" . $htmlDataOfficeBlue . "<br><h1> Green Slips</h1><br>" . $htmlDataGreenSlips;
$combinedHtml = $combinedHtml . "<br><br><b>Signed: </b>.............................................";
$combinedHtml = $combinedHtml . "<br><b>Dated: </b>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;/";


$monthlySlipPdf = new Dompdf();
$monthlySlipPdf->set_paper('letter', 'landscape');
$monthlySlipPdf->loadHtml($combinedHtml);
$monthlySlipPdf->render();
$monthlySlipPdf->stream("Monthly Slips (" . date("d.m.Y") . ")", array("Attachment" => false));


?>
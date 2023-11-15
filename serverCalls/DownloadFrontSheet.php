<?php
include '../Connect.php';
require_once  '../vendor/autoload.php';

?>

<?php


use Dompdf\Dompdf;

$document = new Dompdf();




$FileNumber = $_REQUEST['FileNumber'];


$sql = "SELECT * FROM wip WHERE FileNumber='$FileNumber'";
$data = $con->query($sql);
$matter = $data->fetch_assoc();

$client1ContactID = $matter['Client1Contact_ID'];
$client2ContactID = $matter['Client2Contact_ID'];

$DateOfToESent = $matter['DateOfToESent'];
$DateOfToERcvd = $matter['DateOfToERcvd'];

$DateOfNCBASent = $matter['DateOfNCBASent'];
$DateOfNCBARcvd = $matter['DateOfNCBARcvd'];

$DateOfClientCareSent = $matter['DateOfClientCareSent'];

$DateOfAMLCheck = $matter['DateOfAMLReport'];

$Funding = $matter['Funding'];

if ($Funding == 'CFA') {
    $Funding = 'Conditional Fee Agreement';
} else {
    $Funding = 'Private Funding';
}


// CLIENT 1 INFO 
$sql = "SELECT * FROM client_contact_details WHERE ID =$client1ContactID";

$data = $con->query($sql);
$client1Info = $data->fetch_assoc();

if ($client1Info != null) {
    $Client1Name = $client1Info['ClientName'];
    $timestamp = strtotime($client1Info['DOB']);
    $Client1DOB = date("d-m-Y", $timestamp);
    $Client1Address = $client1Info['AddressLine1'] . ", " . $client1Info['AddressLine2'] . ", <br>" . $client1Info['County'] . ", " . $client1Info['Postcode'];
    $Client1Mobile = $client1Info['ContactNumber'];
    $Client1Email = $client1Info['Email'];
}

// CLIENT 2 INFOR
$sql = "SELECT * FROM client_contact_details WHERE ID =$client2ContactID";

$data = $con->query($sql);
$client2Info = $data->fetch_assoc();

if ($client2Info != null) {
    $Client2Name = $client2Info['ClientName'];
    $timestamp = strtotime($client2Info['DOB']);
    $Client2DOB = date("d-m-Y", $timestamp);
    $Client2Address = $client2Info['AddressLine1'] . ", " . $client2Info['AddressLine2'] . ", <br>" . $client2Info['County'] . ", " . $client2Info['Postcode'];
    $Client2Mobile = $client2Info['ContactNumber'];
    $Client2Email = $client2Info['Email'];
}


$AP1ContactID = $matter['AuthorisedParty1_ID'];
$AP2ContactID = $matter['AuthorisedParty2_ID'];

// AP 1 INFO 
$sql = "SELECT * FROM authorised_party_contact_details WHERE ID =$AP1ContactID";

$data = $con->query($sql);
$AP1Info = $data->fetch_assoc();
if ($AP1Info != null) {
    $AP1Name = $AP1Info['Name'];
    $AP1Address = $AP1Info['AddressLine1'] . ", " . $AP1Info['AddressLine2'] . ", <br>" . $AP1Info['County'] . ", " . $AP1Info['Postcode'];
    $AP1Mobile = $AP1Info['ContactNumber'];

    $timestamp = strtotime($AP1Info['DateOfIDCheck']);
    $AP1IDCheck = date("d-m-Y", $timestamp);
    $AP1Email = $AP1Info['Email'];
    $AP1Relationship = $AP1Info['RelationshipToClient'];
}

// AP 2 INFOR
$sql = "SELECT * FROM authorised_party_contact_details WHERE ID =$AP2ContactID";

$data = $con->query($sql);
$AP2Info = $data->fetch_assoc();
if ($AP2Info != null) {
    $AP2Name = $AP2Info['Name'];
    $AP2Address = $AP2Info['AddressLine1'] . ", " . $AP2Info['AddressLine2'] . ", <br>" . $AP2Info['County'] . ", " . $AP2Info['Postcode'];
    $AP2Mobile = $AP2Info['ContactNumber'];
    $timestamp = strtotime($AP2Info['DateOfIDCheck']);
    $AP2IDCheck = date("d-m-Y", $timestamp);
    $AP2Relationship = $AP2Info['RelationshipToClient'];
    $AP2Email = $AP2Info['Email'];
}


// OS info

$OS_ID = $matter['OtherSideDetails'];
$sql = "SELECT * FROM  OS_Details WHERE ID =$OS_ID";

$data = $con->query($sql);
$OSInfo = $data->fetch_assoc();

if ($OSInfo != null) {
    $OSName = $OSInfo['Name'];
    $OSAddress = $OSInfo['AddressLine1'] . ", " . $OSInfo['AddressLine2'] . ", <br>" . $OSInfo['County'] . ", " . $OSInfo['Ptcode'];
    $OSEmail = $OSInfo['Email'];
    $OSContactNumber = $OSInfo['ContactNumber'];
    $OSSolicitors = $OSInfo['Solicitors'];
    $OSSolEmail = $OSInfo['SolicitorsEmail'];
}

$Undertakings = json_decode($matter['Undertakings']);


$DataToDownload = '
<html>
<head>
<style>
@page { margin-top: 2pt; margin-bottom:0; font-size:10; }
</style>
</head>
<body>
<table border="1" style="margin-top:0; margin-bottom:0; margin-left:50pt;  border-color: black;" cellpadding="2" cellspacing="0" >
    <tr >
        <td style="font-weight: bold; font-size:16px;" >FILE NUMBER</td>
        <td colspan="6" style=" text-align:center; font-size:16px; font-weight: bold;" >' . $FileNumber . '</td>
    </tr>
    <tr >
        
    <td style="background-color:grey;" colspan="7">&nbsp;</td>
</tr>
    <tr >
        <td  style="font-weight: bold;" colspan="7" >CLIENTS DETAILS</td>
    </tr>
    <tr >
        <td ></td>
        <td  style="font-weight: bold; text-align:center;" colspan="3">CLIENT 1</td>
        <td style="font-weight: bold; text-align:center;"  colspan="3" >CLIENT 2</td>
    </tr>
    <tr >
        <td >NAME</td>
        <td colspan="3" style=" text-align:center;" >' . $Client1Name . '</td>
        <td colspan="3" style=" text-align:center;" >' . $Client2Name . '</td>
    </tr>
    <tr >
        <td >ADDRESS</td>
        <td colspan="3" style=" text-align:center;"  >' . $Client1Address . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $Client2Address . '</td>
    </tr>
    <tr >
        <td >CONTACT NO.</td>
        <td colspan="3" style=" text-align:center;"  >' . $Client1Mobile . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $Client2Mobile . '</td>
    </tr>
    <tr >
        <td >E MAIL</td>
        <td colspan="3" style=" text-align:center;"  >' . $Client1Email . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $Client2Email . '</td>
    </tr>
    <tr >
        <td >DATE OF BIRTH</td>
        <td colspan="3" style=" text-align:center;"  >' . $Client1DOB . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $Client2DOB . '</td>
    </tr>
    <tr >
        
        <td style="background-color:grey;" colspan="7">&nbsp;</td>
    </tr>
    <tr >
        <td ></td>
        <td style="font-weight: bold; text-align:center;"  colspan="3" >SENT</td>
        <td style="font-weight: bold; text-align:center;"  colspan="3" >RECEIVED</td>
    </tr>
    <tr >
        <td >TERMS OF ENGAGEMENT</td>
        <td colspan="3" style=" text-align:center;"  >' . $DateOfToESent . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $DateOfToERcvd . '</td>
    </tr>
    <tr >
        <td >NCBA</td>
        <td colspan="3" style=" text-align:center;"  >' . $DateOfNCBASent . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $DateOfNCBARcvd . '</td>
    </tr>
    <tr >
        <td >CLIENT CARE LETTER SENT</td>
        <td colspan="6" style=" text-align:center;"  >' . $DateOfClientCareSent . '</td>
    </tr>
    <tr >
        <td >DATE OF AML CHECK</td>
        <td colspan="6" style=" text-align:center;" >' . $DateOfAMLCheck . '</td>
    </tr>
    
    <tr >
        <td >FUNDING</td>
        <td colspan="6" style=" text-align:center;"  >' . $Funding . '</td>
    </tr>
    <tr >
        
        <td style="background-color:grey;" colspan="7">&nbsp;</td>
    </tr>
    <tr >
    <td style="font-weight: bold;"  colspan="7" >AUTHORISED PARTIES</td>
</tr>
    
    <tr >
        <td >&nbsp;</td>
        <td style="font-weight: bold; text-align:center;"  colspan="3" >AUTHORISED PARTY 1</td>
        <td style="font-weight: bold; text-align:center;"  colspan="3" >AUTHORISED PARTY 2</td>

    </tr>
    <tr >
        <td >NAME</td>
        <td colspan="3" style=" text-align:center;"  >' . $AP1Name . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $AP2Name . '</td>

    </tr>
    <tr >
    <td >ADDRESS</td>
    <td colspan="3" style=" text-align:center;"  >' . $AP1Address . '</td>
    <td colspan="3" style=" text-align:center;"  >' . $AP2Address . '</td>

</tr>
<tr >
        <td >E MAIL</td>
        <td colspan="3" style=" text-align:center;"  >' . $AP1Email . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $AP2Email . '</td>
    </tr>
    <tr >
        <td >CONTACT NO.</td>
        <td colspan="3" style=" text-align:center;"  >' . $AP1Mobile . '</td>
        <td colspan="3" style=" text-align:center;"  >' . $AP2Mobile . '</td>
    </tr>
    <tr >
        <td >DATE OF ID CHECK</td>
        <td colspan="3" style=" text-align:center;"  >' . $AP1IDCheck . '</td>
    <td colspan="3" style=" text-align:center;"  >' . $AP2IDCheck . '</td>
    </tr>
    <tr >
        <td >RELATIONSHIP</td>
        <td colspan="3" style=" text-align:center;"  >' . $AP1Relationship . '</td>
    <td colspan="3" style=" text-align:center;"  >' . $AP2Relationship . '</td>
    </tr>
    <tr >
        
        <td style="background-color:grey;" colspan="7">&nbsp;</td>
    </tr>
    <tr >
        <td style="font-weight: bold;"  colspan="7" >OTHER SIDE’S DETAILS</td>
    </tr>
    <tr >
        <td >NAME</td>
        <td colspan="6" style=" text-align:center;"> ' . $OSName . '</td>
        
    </tr>
    <tr >
        <td >ADDRESS</td>
        <td colspan="6" style=" text-align:center;"  >' . $OSAddress . '</td>
        
    </tr>
    <tr >
        <td >MOBILE</td>
        <td colspan="6" style=" text-align:center;"  >' . $OSContactNumber . '</td>
        
    </tr>
    <tr >
        <td >EMAIL</td>
        <td colspan="6" style=" text-align:center;"  >' . $OSEmail . '</td>
        
    </tr>
    
    <tr >
        <td >SOLICITORS</td>
        <td colspan="6" style=" text-align:center;"  >' . $OSSolicitors . '</td>
    </tr>
    <tr >
        <td >SOLICITORS - E MAIL</td>
        <td colspan="6" style=" text-align:center;"  >' . $OSSolEmail . '</td>
    </tr>
    
    
    
    <tr >
        
        <td style="background-color:grey;" colspan="7">&nbsp;</td>
    </tr>
    <tr >
        <td style="font-weight: bold;"  colspan="7" >UNDERTAKINGS</td>
    </tr>
    

    <tr >
        
        <td style="height:75pt;" colspan="7"><ul>';

foreach ($Undertakings as $u) {

    $DataToDownload = $DataToDownload . "<li>" . $u . "</li>";
}

$DataToDownload = $DataToDownload . ' </ul></td>
    </tr>
    <tr >
        <td style="font-weight: bold; "  colspan="7" >KEY INFORMATION</td>
    </tr>
    <tr >
        <td style="height:80pt;" colspan="7"  >' . $matter['KeyInformation'] . '</td>
    </tr>
    



</table> 
</body>
</html>';

$document->loadHtml($DataToDownload);
$document->render();
$document->stream("$FileNumber (Frontsheet)");


   
   <?php

include '../Connect.php';

require_once '../vendor/autoload.php';

?>

<?php 


use Dompdf\Dompdf;

$document = new Dompdf();




$ID = $_REQUEST['ID'];
$sql ="SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";

 $result = mysqli_query($con,$sql);
 

 $row = mysqli_fetch_assoc($result);

 $FileNumber = $row['FileNumber'];
 $sql1 = "SELECT * FROM wip WHERE FileNumber = '$FileNumber' LIMIT 1";
 $result1 = mysqli_query($con,$sql1);
 $row1 = mysqli_fetch_assoc($result1);

 $timestamp = strtotime($row['Date']);
 $Date = date("d-m-Y", $timestamp); 

 $client1ContactID = $row1['Client1Contact_ID'];
   $client2ContactID = $row1['Client2Contact_ID'];

   // CLIENT 1 INFO 
   $sql = "SELECT * FROM client_contact_details WHERE ID =$client1ContactID"; 

   $data = $con->query($sql);
   $client1Info = $data->fetch_assoc();


   // CLIENT 2 INFOR
   $sql = "SELECT * FROM client_contact_details WHERE ID =$client2ContactID"; 

   $data = $con->query($sql);
   $client2Info = $data->fetch_assoc();


                
$DataToDownload = "<div><h1 style='text-align:center;'>";
    if($row['PMTToOrFrom'] == 1) {
        $DataToDownload = $DataToDownload."Pink Slip </h1>";
    }
    else {
        $DataToDownload = $DataToDownload."Blue Slip </h1>";
    }

$DataToDownload = $DataToDownload."<br><b>File Number: </b>".$row['FileNumber'];
 
$DataToDownload = $DataToDownload."<br><br><b>Client Name:  </b> ".$client1Info['ClientName'];
if($client2Info != Null)
{
   $DataToDownload = $DataToDownload." & ".$client2Info['ClientName'];
}
 
//  $DataToDownload = $DataToDownload."<br><br><b>Ledger Account: </b>";
//  if($row['LedgerAccount'] == 'O')
//  {
//     $DataToDownload = $DataToDownload."Office Acount Ledger";
//  }
//  else{
//     $DataToDownload = $DataToDownload."Client Acount Ledger";
//  }
 $DataToDownload = $DataToDownload."<br><br><table><thead><tr style='background: #f0f0f0;'><th  margin: 1px;'><b>Ledger Account</th></b><th '><b>Amount</th></b><th><b>Date</th></b></tr></thead>"; // Table head
 $DataToDownload = $DataToDownload."<tbody><tr style='background: #f0f0f0;'><td  width: 180px; text-align: center;'>";
 if($row['LedgerAccount'] == 'O')
 {
    $DataToDownload = $DataToDownload."Office Acount Ledger</td>";
 }
 else{
    $DataToDownload = $DataToDownload."Client Acount Ledger</td>";
 }
 $DataToDownload = $DataToDownload."<td style=  'width: 120px; text-align: center;'>£".$row['Amount']."</td>";
 $DataToDownload = $DataToDownload."<td style= ' width: 120px; text-align: center;'>".$Date."</td></tr></tbody></table>";
if($row['PMTToOrFrom'] == 1) 
{
    $DataToDownload = $DataToDownload."<br><b>Payment to</b>".$row['PMTPerson'];
}
else {
    $DataToDownload = $DataToDownload."<br><b>Payment from: </b>".$row['PMTPerson'];
}
$DataToDownload = $DataToDownload."<br><br><b>Mode of Payment: </b>".$row['ModeOfPMT'];

$DataToDownload = $DataToDownload."<br><br><b>Description: </b>".$row['Description'];

 
 
 $document->loadHtml($DataToDownload);
 $document->render();
 $document->stream("Slip".$Date." - ".$FileNumber, array("Attachment" => false));

?>




   
   <?php

include '../Connect.php';

require_once '../vendor/autoload.php';

?>

<?php 

use Dompdf\Dompdf;
$document1 = new Dompdf();



$ID = $_REQUEST['ID'];
$sql = "SELECT * FROM ledger_accounts_transfers WHERE ID = '$ID' LIMIT 1";

  

 $result = mysqli_query($con,$sql);
 $row = mysqli_fetch_assoc($result);


 $timestamp = strtotime($row['Date']);
 $Date = date("d-m-Y", $timestamp); 


                
$DataToDownload = "<body><h1 style='text-align:center;'>Green Slip</h1>";

$DataToDownload = $DataToDownload."<br><b>File Number From: </b>".$row['FileNumberFrom'];
$DataToDownload = $DataToDownload."&nbsp; &nbsp;<b>File Number To: </b>".$row['FileNumberTo'];
 

 $DataToDownload = $DataToDownload."<br><br><table><thead><tr style='background: #f0f0f0;'><th  margin: 1px;><b>Ledger Account From</b></th><th  margin: 1px;><b>Ledger Account To</b></th><th><b>Amount</th></b><th><b>Date</th></b></tr></thead>"; // Table head
 $DataToDownload = $DataToDownload."<tbody><tr style='background: #f0f0f0;'><td  width: 180px; text-align: center;>";
 if($row['FromLedgerAccount'] == 'O')
 {
    $DataToDownload = $DataToDownload."Office Acount Ledger</td>";
 }
 else{
    $DataToDownload = $DataToDownload."Client Acount Ledger</td>";
 }
 if($row['ToLedgerAccount'] == 'O')
 {
    $DataToDownload = $DataToDownload."<td  width: 180px; text-align: center;'>Office Acount Ledger</td>";
 }
 else{
    $DataToDownload = $DataToDownload."<td  width: 180px; text-align: center;'>Client Acount Ledger</td>";
 }
 $DataToDownload = $DataToDownload."<td style=  'width: 120px; text-align: center;'>£".round($row['Amount'],2)."</td>";
 $DataToDownload = $DataToDownload."<td style= ' width: 120px; text-align: center;'>".$Date."</td></tr></tbody></table>";


$DataToDownload = $DataToDownload."<br><br><b>Description: </b>".$row['Description']."</body>";

 
 


    

    $document1->loadHtml($DataToDownload);
    $document1->render();
    $document1->stream($row['FileNumberFrom']."- Green Slip -".$Date, array("Attachment" => false));
 
?>



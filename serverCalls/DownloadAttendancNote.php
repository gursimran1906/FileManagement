
   
   <?php

   include '../Connect.php';

   require_once dirname(__DIR__) . '../vendor/autoload.php';

   ?>

<?php


use Dompdf\Dompdf;

$document = new Dompdf();




$ID = $_REQUEST['ID'];
$sql = $sql = "SELECT * FROM matter_attendancenotes WHERE ID = '$ID' LIMIT 1";



$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);



$FileNumber = $row['FileNumber'];
$sql1 = "SELECT * FROM wip WHERE FileNumber = '$FileNumber' LIMIT 1";
$result1 = mysqli_query($con, $sql1);
$row1 = mysqli_fetch_assoc($result1);

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

$timestamp = strtotime($row['Date']);
$Date = date("d-m-Y", $timestamp);

$timestamp = strtotime($row['StartTime']);
$StartTime = date("h:ia", $timestamp);

$timestamp = strtotime($row['FinishTime']);
$FinishTime = date("h:ia", $timestamp);


$DataToDownload = "<div><h1 style='text-align:center;'> ATTENDANCE NOTE </h1> ";
$DataToDownload = $DataToDownload . "<br><b>Client Name:  </b> " . $client1Info['ClientName'];
if ($client2Info != null) {
   $DataToDownload = $DataToDownload . " & " . $client2Info['ClientName'];
}
$DataToDownload = $DataToDownload . "<br><br><b>File Number: </b>" . $row['FileNumber'];
$DataToDownload = $DataToDownload . "<br><br><b>Date: </b>" . $Date;
$DataToDownload = $DataToDownload . "<br><br><b>Start Time: </b>" . $StartTime . " <b>   Finish Time: </b>" . $FinishTime;
$DataToDownload = $DataToDownload . "<hr></div>";
$DataToDownload = "<br><br><div style='white-space: pre-wrap; font-family: 'Times New Roman', Times, serif;'>" . $DataToDownload . $row['Content'] . "<br>";
if ($row['isCharged'] == 0) {
   $DataToDownload = $DataToDownload . "<br> <b>N/C</b>";
}
$DataToDownload = $DataToDownload . "<br><b> Unit(s): </b> " . $row['Unit'] . " - <b>" . substr($row['PersonAttended'],0,2) . "</b> </div>";


$document->loadHtml($DataToDownload);
$document->render();
$document->stream("$FileNumber - $Date (AN)");



?>



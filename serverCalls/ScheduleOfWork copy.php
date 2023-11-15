<?php
include '../Connect.php';
$fileNumber = $_GET['FileNumber'];

$sql1 = "SELECT * FROM wip WHERE FileNumber = '$fileNumber' LIMIT 1";
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

$sql = "SELECT * FROM scheduleofwork WHERE FileNumber = '$fileNumber' ";

$data = $con->query($sql);
$workDone = array();

//checks whether there is a row in the database
if ($data->num_rows > 0) {
  // output data of each row
  while ($row = $data->fetch_assoc()) {
    $workDone[] = $row; // adds the row from the database into the array
  }
}


$sql = "CREATE TABLE ScheduleOFWorkAndCostFile (
                    Date date NOT NULL,
                    Description VARCHAR(500) NOT NULL,
                    Unit int(3),
                    Amount float
                    )";

$con->query($sql);

foreach ($workDone as $q) {
  $timestamp = strtotime($q['StartTime']);
  $StartTime = date("h:ia", $timestamp);

  $timestamp = strtotime($q['FinishTime']);
  $FinishTime = date("h:ia", $timestamp);

  $Date = $q['Date'];
  $Units = $q['Units'];
  $Amount = $q['Amount'];
  $ToOrFrom = $q['ToOrFrom'];
  $Description = mysqli_real_escape_string($con, $q['Description']);

  if ($q['TypeOfWork'] == 'E') {
    if ($q['Sent'] == 1) {
      $Descrip = "Email to " . $ToOrFrom . " @" . $StartTime . " - " . $Description;
    } else {
      $Descrip = "Perusal of email from " . $ToOrFrom . " @" . $StartTime . " - " . $Description;
    }
  } else if ($q['TypeOfWork'] == 'L') {
    if ($q['Sent'] == 1) {
      $Descrip = "Letter to " . $ToOrFrom . " - " . $Description;
    } else {
      $Descrip = "Perusal of letter from " . $ToOrFrom . " - " . $Description;
    }
  } else {
    $Descrip = "Attendance Note - " . $Description . " from " . $StartTime . " to " . $FinishTime;
  }

  $sql1 =  "INSERT INTO ScheduleOFWorkAndCostFile (Date, Description, Unit, Amount) VALUES ('$Date', '$Descrip', $Units, $Amount)";
  $con->query($sql1);
}

$sql = "SELECT * FROM ScheduleOFWorkAndCostFile ORDER BY Date ASC";

$data = $con->query($sql);
$work = array();

//checks whether there is a row in the database
if ($data->num_rows > 0) {
  // output data of each row
  while ($row = $data->fetch_assoc()) {
    $work[] = $row; // adds the row from the database into the array
  }
}


$sql = "SELECT * FROM wip WHERE FileNumber='$fileNumber'";


$data = $con->query($sql);
$matter = $data->fetch_assoc();

$sql = "SELECT * FROM ScheduleOFWorkAndCostFile ORDER BY Date ASC LIMIT 1";
$data = $con->query($sql);
$startDate = $data->fetch_assoc();

$timestamp = strtotime($startDate['Date']);
$StartDate = date("d-m-Y", $timestamp);


$sql = "SELECT * FROM ScheduleOFWorkAndCostFile ORDER BY Date DESC LIMIT 1";
$data = $con->query($sql);
$endDate = $data->fetch_assoc();

$timestamp = strtotime($endDate['Date']);
$EndDate = date("d-m-Y", $timestamp);

echo "\tClient Name: " . $client1Info['ClientName'];


if ($client2Info != null) {

  if ($client2Info['ClientName'] != '') {
    echo " & " . $client2Info['ClientName'];
  }
}

echo "Matter: " . $matter['MatterDescription'] . " [" . $fileNumber . "] \n";
echo "\tSchedule of Work and Costs from " . $StartDate . " to " . $EndDate . " \n";
echo "\t";
if ($matter['Supervisor'] == 'SD') {
  echo "S Dhillon rate £295.00 + VAT per hour, 6 minutes = 1 unit \n";
} elseif ($matter['Supervisor'] == 'ND') {
  echo "N Dhillon rate £295.00 + VAT per hour, 6 minutes = 1 unit \n";
} elseif ($matter['Supervisor'] == 'CP') {
  echo "C Pinnion rate £275.00 + VAT per hour, 6 minutes = 1 unit \n";
} elseif ($matter['Supervisor'] == 'TR') {
  echo "T Rowley rate £275.00 + VAT per hour, 6 minutes = 1 unit \n";
} else {
  echo "[FEE EARNER] rate £250.00 + VAT per hour, 6 minutes = 1 unit \n";
}



$sql = "SELECT * FROM ScheduleOFWorkAndCostFile";
if ($result = mysqli_query($con, $sql)) {
  $rowcount = mysqli_num_rows($result);
}


// $sql = "DROP TABLE ScheduleOFWorkAndCostFile";
// $con->query($sql);


//Define the filename with current date
$fileName = "Schedule of Work and Costs from $StartDate to $EndDate - " . date('d-m-Y') . ".xls";

//Set header information to export data in excel format
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=' . $fileName);

//Set variable to false for heading
$heading = false;

//Add the MySQL table data to excel file
if (!empty($work)) {
  foreach ($work as $item) {
    if (!$heading) {
      echo implode("\t", array_keys($item)) . "\n";
      $heading = true;
    }
    echo implode("\t", array_values($item)) . "\n";
  }
}
$finalCellToSum = (8 + $rowcount) - 1;
echo "\n\t Total Costs \t\t=sum(d8:d$finalCellToSum) \n";
$TotalCostsCell = $finalCellToSum + 2;
echo " \t Add VAT @20% \t\t=.2*d$TotalCostsCell \n";
$VATCell = $TotalCostsCell + 1;
echo "\t Total Costs and VAT \t\t=sum(d$TotalCostsCell : d$VATCell)";

exit();

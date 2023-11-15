<?php
include '../Connect.php';
$fileNumber = $_GET['FileNumber'];


$scheduleOfWC = array();

$sql = "SELECT *  FROM matter_attendancenotes WHERE FileNumber = '$fileNumber' ORDER BY Date Asc";
$data = $con->query($sql);
$attendanceNotes = array();

//checks whether there is a row in the database
if ($data->num_rows > 0) {
  // output data of each row
  while ($row = $data->fetch_assoc()) {
    $attendanceNotes[] = $row; // adds the row from the database into the array
  }
}

foreach ($attendanceNotes as $attendanceNote) {
  $timestamp = strtotime($attendanceNote['Date']);
  $date = date("d-m-Y", $timestamp);

  $timestamp = strtotime($attendanceNote['StartTime']);
  $StartTime = date("h:ia", $timestamp);

  $timestamp = strtotime($attendanceNote['FinishTime']);
  $FinishTime = date("h:ia", $timestamp);

  $unit = calculateUnits($attendanceNote['StartTime'], $attendanceNote['FinishTime']);
  $desc = "Attendance Note - " . $attendanceNote['SubjectLine'] . " from " . $StartTime . " to " . $FinishTime;

  $PersonAttended = $attendanceNote['PersonAttended'];
  $Amount = 0;
  if ($attendanceNote['isCharged'] == 1) {
    $Amount = calculateAmount($PersonAttended, $unit);
  }
  $scheduleOfWC[] = ['Date' => $date, 'Time' => $attendanceNote['StartTime'], 'Fee Earner' => substr($PersonAttended, 0, 2), 'Description' => $desc, 'Unit(s)' => $unit, 'Amount' => $Amount];
}

$sql = "SELECT *  FROM matter_emails WHERE FileNumber = '$fileNumber' ORDER BY Date Asc";
$data = $con->query($sql);
$emails = array();

//checks whether there is a row in the database
if ($data->num_rows > 0) {
  // output data of each row
  while ($row = $data->fetch_assoc()) {
    $emails[] = $row; // adds the row from the database into the array
  }
}

foreach ($emails as $email) {
  $timestamp = strtotime($email['Date']);
  $date = date("d-m-Y", $timestamp);

  $timestamp = strtotime($email['Time']);
  $Time = date("h:ia", $timestamp);

  $ToOrFrom = $email['ToOrFrom'];

  if ($email['Sent'] == 1) {
    $Desc = "Email to " . $ToOrFrom . " @" . $Time . " - " . $email['SubjectLine'];
  } else {
    $Desc = "Perusal of email from " . $ToOrFrom . " @" . $Time . " - " . $email['SubjectLine'];
  }
  $unit = 1;
  $Amount = 0;
  $PersonAttended = $email['PersonAttended'];

  if ($email['isCharged'] == 1) {
    $Amount = calculateAmount($PersonAttended, $unit);
  }

  $scheduleOfWC[] = ['Date' => $date, 'Time' => $email['Time'], 'Fee Earner' => substr($PersonAttended, 0, 2), 'Description' => $Desc, 'Unit(s)' => $unit, 'Amount' => $Amount];
}
print_r($scheduleOfWC);
$sql = "SELECT *  FROM matter_letters WHERE FileNumber = '$fileNumber' ORDER BY Date Asc";
$data = $con->query($sql);
$letters = array();

//checks whether there is a row in the database
if ($data->num_rows > 0) {
  // output data of each row
  while ($row = $data->fetch_assoc()) {
    $letters[] = $row; // adds the row from the database into the array
  }
}

foreach ($letters as $letter) {
  $timestamp = strtotime($letter['Date']);
  $date = date("d-m-Y", $timestamp);

  $ToOrFrom = $letter['ToOrFrom'];

  if ($letter['Sent'] == 1) {
    $Desc = "Letter to " . $ToOrFrom .  " - " . $letter['SubjectLine'];
  } else {
    $Desc = "Perusal of letter from " . $ToOrFrom .  " - " . $letter['SubjectLine'];
  }
  $unit = 1;
  $Amount = 0;
  $PersonAttended = $letter['PersonAttended'];

  if ($letter['IsCharged'] == 1) {
    $Amount = calculateAmount($PersonAttended, $unit);
  }
  $scheduleOfWC[] = ['Date' => $date, 'Time' => 0, 'Fee Earner' => substr($PersonAttended, 0, 2), 'Description' => $Desc, 'Unit(s)' => $unit, 'Amount' => $Amount];
}


function calculateAmount($PersonAttended, $unit)
{

  if ($PersonAttended == 'SD') {
    $Amount = $unit *  29.5;
  } elseif ($PersonAttended == 'ND') {
    $Amount = $unit *  29.5;
  } elseif ($PersonAttended == 'JP') {
    $Amount = $unit *  22.5;
  } elseif ($PersonAttended == 'TR') {
    $Amount = $unit *  27.5;
  } elseif ($PersonAttended == 'TR NEW RATE') {
    $Amount = $unit *  29.5;
  } elseif ($PersonAttended == 'TRN') {
    $Amount = $unit *  29.5;
  } elseif ($PersonAttended == 'JPN') {
    $Amount = $unit *  25.0;
  } elseif ($PersonAttended == 'GM') {
    $Amount = $unit *  25.0;
  } elseif ($PersonAttended == 'LH') {
    $Amount = $unit *  19.5;
  } elseif ($PersonAttended == 'CP') {
    $Amount = $unit *  27.5;
  } elseif ($PersonAttended == 'SM') {
    $Amount = $unit *  19.5;
  } elseif ($PersonAttended == 'KS') {
    $Amount = $unit *  19.5;
  } elseif ($PersonAttended == 'GB') {
    $Amount = $unit *  19.5;
  } else {
    $Amount = $unit *  19.5;
  }

  return $Amount;
}

function calculateUnits($startTime, $finishTime)
{
  $Unit = (strtotime($finishTime) - strtotime($startTime)) / 6;
  $Unit = $Unit / 60;
  $Unit = ceil($Unit);
  return $Unit;
}

usort($scheduleOfWC, function ($a, $b) {
  $dateA = new DateTime($a['Date']);
  $dateB = new DateTime($b['Date']);

  $dateComparison = $dateA <=> $dateB;
  if ($dateComparison === 0) {
    // If the dates are equal, compare based on time
    $timeA = strtotime($a['Time']);
    $timeB = strtotime($b['Time']);
    return $timeA <=> $timeB;
  }

  return $dateComparison;
});

foreach ($scheduleOfWC as &$item) {
  unset($item['Time']);
}


$feeEarners = array_unique(array_column(array_filter($scheduleOfWC, function ($entry) {
  return $entry['Fee Earner'];
}), 'Fee Earner'));


$sql = "SELECT Client1Contact_ID, Client2Contact_ID, MatterDescription FROM wip WHERE FileNumber = '$fileNumber'";
$data = $con->query($sql);
$matter = $data->fetch_assoc();
$Client1ID = $matter['Client1Contact_ID'];
$Client2ID = $matter['Client2Contact_ID'];

$sql = "SELECT ClientName FROM client_contact_details WHERE ID = '$Client1ID'";
$data = $con->query($sql);
$Client1Name = $data->fetch_assoc();

$sql = "SELECT ClientName FROM client_contact_details WHERE ID = '$Client2ID'";
$data = $con->query($sql);
$Client2Name = $data->fetch_assoc();



echo "\tClient Name: " . $Client1Name['ClientName'];


if ($Client2Name != null) {

  if ($Client2Name['ClientName'] != '') {
    echo " & " . $Client2Name['ClientName'];
  }
}

$EndRow = end($scheduleOfWC);
$EndDate = $EndRow['Date'];

$startRow = $scheduleOfWC[0];
$StartDate = $startRow['Date'];

echo " Matter: " . $matter['MatterDescription'] . " [" . $fileNumber . "] \n";
echo "\t\tSchedule of Work and Costs from " . $StartDate . " to " . $EndDate . " \n";

foreach ($feeEarners as $feeEarner) {
  if ($feeEarner == 'TR NEW RATE' || $feeEarner == 'TRN') {
    echo "\t\tT Rowley (TR) rate 295.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'SD') {
    echo "\t\tS Dhillon (SD) rate £295.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'ND') {
    echo "\t\tN Dhillon (ND) rate £295.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'CP') {
    echo "\t\tC Pinnion (CP) rate £275.00 + VAT per hour, 6 minutes = 1 unit \n";
  } elseif ($feeEarner == 'TR') {
    echo "\t\tT Rowley (TR) rate £275.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'JP') {
    echo "\\ttJ Phillips (JP) rate £275.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'KS') {
    echo "\t\tK Spears (KS) rate £195.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'LH') {
    echo "\t\tL Hubbard (LH) rate £195.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'SM') {
    echo "\t\tS Manning (SM) rate £195.00 + VAT per hour, 6 minutes = 1 unit \n";
  } elseif ($feeEarner == 'GB') {
    echo "\t\tG Bassi (GB) rate £195.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'GM') {
    echo "\t\tG Marshall (GM) rate £250.00 + VAT per hour, 6 minutes = 1 unit \n";
  } else if ($feeEarner == 'JPN') {
    echo "\t\tJ Phillips (JP) rate 250.00 + VAT per hour, 6 minutes = 1 unit \n";
  }
}


//Define the filename with current date
$fileName = "Schedule of Work and Costs from " . $StartDate . " to " . $EndDate . " - (" . date('d-m-Y') . ").xls";

//Set header information to export data in excel format
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=' . $fileName);

//Set variable to false for heading
$heading = false;
$work = $scheduleOfWC;
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
// echo "Fee Earners Count" . count($feeEarners);
// echo "\n Rows Count " . count($scheduleOfWC);
$finalCellToSum = (4 + count($feeEarners) + count($scheduleOfWC));
echo "\n\t\tTotal Costs \t\t=sum(e8:e$finalCellToSum) \n";
$TotalCostsCell = $finalCellToSum + 2;
echo " \t\tAdd VAT @20% \t\t=.2*e$TotalCostsCell \n";
$VATCell = $TotalCostsCell + 1;
echo "\t\tTotal Costs and VAT \t\t=sum(e$TotalCostsCell : e$VATCell)";

exit();

<?php
include '../Connect.php';
$FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);


$Date = mysqli_real_escape_string($con, $_REQUEST['Date']);


$Amount = mysqli_real_escape_string($con, $_REQUEST['Amount']);
$Description = mysqli_real_escape_string($con, $_REQUEST['Description']);





$sql =  "INSERT INTO temp_slips (FileNumber, Amount, Date, Description) 
    
        VALUES ('$FileNumber','$Amount','$Date','$Description')";
mysqli_query($con, $sql);


header("Location: ../Finances.php?FileNumber=$FileNumber");
exit();

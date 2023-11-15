<?php 
      
        include '../Connect.php';
        $ID = mysqli_real_escape_string($con, $_REQUEST['ID']);
        $FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);
        $ClientAcc = isset($_REQUEST['ClientAcc']) ? 1 : 0;
        $OfficeAcc = isset($_REQUEST['OfficeAcc']) ? 1 : 0;
        $Date = mysqli_real_escape_string($con, $_REQUEST['Date']);
        $PMTPerson = mysqli_real_escape_string($con, $_REQUEST['PMTPerson']);
        
        $Amount = mysqli_real_escape_string($con, $_REQUEST['Amount']);
        $Description = mysqli_real_escape_string($con, $_REQUEST['Description']);
        $TypeOfSlip = mysqli_real_escape_string($con, $_REQUEST['TypeOfSlip']);
        
        if($ClientAcc == 1)
        {
            $LedgerAcc = "C";
        }
        else 
        {
            $LedgerAcc = "O";
        }
        if($TypeOfSlip != 2){
            $ModeOfPmt = mysqli_real_escape_string($con, $_REQUEST['ModeOfPmt']);
        
        }
        else{
            $ModeOfPmt ="";
        }
        
        $sql = "UPDATE pmts_slip SET 
        FileNumber='$FileNumber',
        LedgerAccount='$LedgerAcc',
        ModeOfPmt='$ModeOfPmt',
        Amount='$Amount',
        PMTPerson='$PMTPerson',
        Description='$Description',
        Date='$Date' 
        WHERE ID='$ID'
        ";
        mysqli_query($con, $sql);


       
        header("Location: ../Finances.php?FileNumber=$FileNumber");
        exit();
?>                     
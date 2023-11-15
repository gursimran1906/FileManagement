<?php 
        // 1 is payment out and 0 is payment in 
        
        include '../Connect.php';
        
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
     
        $ModeOfPmt = mysqli_real_escape_string($con, $_REQUEST['ModeOfPmt']);
        
        
     
    
         
        $sql =  "INSERT INTO pmts_slip(FileNumber, LedgerAccount, ModeOfPMT, Amount, PMTToOrFrom, PMTPerson, Description, Date, BalanceLeft) 
    
        VALUES ('$FileNumber','$LedgerAcc','$ModeOfPmt','$Amount','$TypeOfSlip','$PMTPerson','$Description', '$Date', '$Amount')";               
        mysqli_query($con, $sql);

       
        header("Location: ../Finances.php?FileNumber=$FileNumber");
        exit();

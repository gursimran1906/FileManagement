<?php 
        include '../Connect.php';
        $ID = mysqli_real_escape_string($con, $_REQUEST['ID']);
        $FileNumberFrom = mysqli_real_escape_string($con, $_REQUEST['FileNumberFrom']);
        $FileNumberTo = mysqli_real_escape_string($con, $_REQUEST['FileNumberTo']);
        $FromClientAcc = isset($_REQUEST['FromClientAcc']) ? 1 : 0;
        $FromOfficeAcc = isset($_REQUEST['FromOfficeAcc']) ? 1 : 0;
        $ToClientAcc = isset($_REQUEST['ToClientAcc']) ? 1 : 0;
        $ToOfficeAcc = isset($_REQUEST['ToOfficeAcc']) ? 1 : 0;
        $Date = mysqli_real_escape_string($con, $_REQUEST['Date']);
        
        
        $Amount = mysqli_real_escape_string($con, $_REQUEST['Amount']);
        $Description = mysqli_real_escape_string($con, $_REQUEST['Description']);
        

        
        if($FromClientAcc == 1)
        {
            $FromLedgerAcc = "C";
        }
        else 
        {
            $FromLedgerAcc = "O";
        }

        if($ToClientAcc == 1)
        {
            $ToLedgerAcc = "C";
        }
        else 
        {
            $ToLedgerAcc = "O";
        }

        
        $sql = "UPDATE ledger_accounts_transfers SET
        FileNumberFrom ='$FileNumberFrom',
        FileNumberTo='$FileNumberTo',
        FromLedgerAccount='$FromLedgerAcc',
        ToLedgerAccount='$ToLedgerAcc',
        Amount ='$Amount',
        Date='$Date',
        Description='$Description'
        WHERE 
        ID='$ID';
        ";
        
        mysqli_query($con, $sql);

       
        header("Location: ../Finances.php?FileNumber=$FileNumberFrom");
        exit();
?>                     
<?php 
        include '../Connect.php';
        ?>
        <?php
        $FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);
    
        $Time = mysqli_real_escape_string($con, $_REQUEST['Time']);
        $AttendingPerson = mysqli_real_escape_string($con, $_REQUEST['PersonAttended']);
        $isCharged = isset($_REQUEST['isCharged']) ? 1 : 0;
        $Date = mysqli_real_escape_string($con, $_REQUEST['Date']);
        $SubjectLine = mysqli_real_escape_string($con, $_REQUEST['SubjectLine']);
        $ToOrFrom = mysqli_real_escape_string($con, $_REQUEST['ToOrFrom']);
        
        $Unit = 1;
        $Sent = isset($_REQUEST['To']) ? 1 : 0;
        $Received = isset($_REQUEST['From']) ? 1 : 0;

        $ID = mysqli_real_escape_string($con, $_REQUEST['ID']);

        if($AttendingPerson == 'SD')
        {
            $Rate = 29.5;
        }
        elseif ($AttendingPerson == 'ND') {
            $Rate = 29.5;
        }
        elseif ($AttendingPerson == 'JP') {
            $Rate = 250;
        }
        elseif ($AttendingPerson == 'TR') {
            $Rate = 27.5;
        }
        elseif ($AttendingPerson == 'GM') {
            $Rate = 25.0;
        }
        elseif ($AttendingPerson == 'LH') {
            $Rate = 11.8;
        }
        elseif ($AttendingPerson == 'CP') {
            $Rate = 27.5;
        }
        else {
            $Rate = 20.00;
        }


        if($isCharged == '0'){
            $Amount = 0;
        }
        else {
            $Amount = $Unit * $Rate;
        }

       
       
        $sql1 = "UPDATE matter_emails SET FileNumber='$FileNumber', Time='$Time', SubjectLine='$SubjectLine', isCharged='$isCharged', PersonAttended='$AttendingPerson', Date='$Date', ToOrFrom='$ToOrFrom', Sent='$Sent', Received ='$Received' WHERE ID='$ID'";
        mysqli_query($con, $sql1); 

        $sql ="UPDATE scheduleofwork SET FileNumber='$FileNumber', Date='$Date', ToOrFrom='$ToOrFrom', Sent='$Sent',Description='$SubjectLine', Units='$Unit', Amount='$Amount', StartTime='$Time' WHERE ID='$ID' AND TypeOfWork='E'";
        mysqli_query($con, $sql);
       
        header("Location: ../Correspondence.php?FileNumber=$FileNumber");
        exit();
?>      
<?php 
        include '../Connect.php';
        ?>
        <?php
        $FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);
    
       
        $AttendingPerson = mysqli_real_escape_string($con, $_REQUEST['PersonAttended']);
        $isCharged = isset($_REQUEST['isCharged']) ? 1 : 0;
        $Date = mysqli_real_escape_string($con, $_REQUEST['Date']);
        $SubjectLine = mysqli_real_escape_string($con, $_REQUEST['SubjectLine']);
        $ToOrFrom = mysqli_real_escape_string($con, $_REQUEST['ToOrFrom']);
        
        $Unit = 1;
        $Sent = isset($_REQUEST['To']) ? 1 : 0;
        $Received = isset($_REQUEST['From']) ? 1 : 0;

        

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

         
        $sql1 =  "INSERT INTO 
        matter_letters
        (FileNumber,
        Date,
        ToOrFrom,
        Sent,
        Received,
        SubjectLine,
        PersonAttended,
        IsCharged)
         VALUES 
         ('$FileNumber',
         '$Date',
         '$ToOrFrom',
         $Sent,
         $Received,
         '$SubjectLine',
         '$AttendingPerson',
         $isCharged)";               
        mysqli_query($con, $sql1);

        
        $ID = mysqli_insert_id($con);

        echo $ID;
        
        $sql = "INSERT INTO scheduleofwork 
        (ID,
        FileNumber,
        TypeOfWork,
        ToOrFrom,
        Sent,
        Date,
        Description,
        Units,
        Amount) 
        VALUES 
        ('$ID',
        '$FileNumber',
        'L',
        '$ToOrFrom',
         '$Sent',
         '$Date', 
         '$SubjectLine',
          '$Unit', 
          '$Amount')"; 
        mysqli_query($con, $sql);

       
        header("Location: ../Correspondence.php?FileNumber=$FileNumber");
        exit();
?>      
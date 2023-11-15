<?php 
        include '../Connect.php';
        $FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);
        $StartTime = mysqli_real_escape_string($con, $_REQUEST['StartTime']);
        $FinishTime = mysqli_real_escape_string($con, $_REQUEST['FinishTime']);
        $AttendingPerson = mysqli_real_escape_string($con, $_REQUEST['PersonAttended']);
        $isCharged = isset($_REQUEST['isCharged']) ? 1 : 0;
        $Date = mysqli_real_escape_string($con, $_REQUEST['Date']);
        $SubjectLine = mysqli_real_escape_string($con, $_REQUEST['SubjectLine']);
        $Content = mysqli_real_escape_string($con, $_REQUEST['Content']);
        $Unit = (strtotime($FinishTime) - strtotime($StartTime)) / 6;
        $Unit = $Unit / 60;
        $Unit = ceil($Unit);
        
        if($AttendingPerson == 'SD')
        {
            $Rate = 27.5;
        }
        elseif ($AttendingPerson == 'ND') {
            $Rate = 27.5;
        }
        elseif ($AttendingPerson == 'JP') {
            $Rate = 22.5;
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
         
        $sql1 =  "INSERT INTO matter_attendancenotes(FileNumber, StartTime, FinishTime, SubjectLine, Content, isCharged, PersonAttended, Rate, Date, Unit) 
    
        VALUES ('$FileNumber','$StartTime','$FinishTime','$SubjectLine','$Content',$isCharged,'$AttendingPerson', $Rate,'$Date',$Unit)";               
        
        mysqli_query($con, $sql1);
        $ID = mysqli_insert_id($con);

        
        $sql = "INSERT INTO scheduleofwork (ID,FileNumber, TypeOfWork, Date, Description , Units, Amount, StartTime, FinishTime) VALUES ( '$ID', '$FileNumber', 'A', '$Date', '$SubjectLine', '$Unit', '$Amount', '$StartTime', '$FinishTime')"; 
        mysqli_query($con, $sql);

       
        header("Location: ../AttendanceNote.php?FileNumber=$FileNumber");
        exit();

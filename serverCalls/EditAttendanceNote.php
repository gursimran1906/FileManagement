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
        $ID = mysqli_real_escape_string($con, $_REQUEST['ID']);

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
         
        

        $sql1 = "UPDATE matter_attendancenotes SET FileNumber='$FileNumber', StartTime='$StartTime', FinishTime='$FinishTime', SubjectLine='$SubjectLine', Content='$Content', isCharged='$isCharged', PersonAttended='$AttendingPerson', Rate='$Rate', Date='$Date', Unit='$Unit' WHERE ID='$ID'";
        mysqli_query($con, $sql1); 

        // $sql = "INSERT INTO scheduleofwork (FileNumber, TypeOfWork, Date, Description , Units, Amount, StartTime, FinishTime) VALUES ( '$FileNumber', 'A', '$Date', '$SubjectLine', '$Unit', '$Amount', '$StartTime', '$FinishTime')"; 
        $sql ="UPDATE scheduleofwork SET FileNumber='$FileNumber', Date='$Date', Description='$SubjectLine', Units='$Unit', Amount='$Amount', StartTime='$StartTime', FinishTime='$FinishTime' WHERE ID='$ID' AND TypeOfWork='A'";
        mysqli_query($con, $sql);

      
        header("Location: ../AttendanceNote.php?FileNumber=$FileNumber");
        exit();
      
        
?>                     

                       
                    
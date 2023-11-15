<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();

$ID = $_REQUEST['ID'];
$sql = $sql = "SELECT * FROM matter_attendancenotes WHERE ID = '$ID' LIMIT 1";



$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $row['FileNumber'] . " - "; ?>Edit Attendance Notes</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <meta name="author" content="Gursimran Singh">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php writeNavBar(); ?>
    <?php sessionCheck(); ?>
    <div class="container">
        <form class="row g-3" action=serverCalls/EditAttendanceNote.php method="post" id="AddAttendanceNoteForm">
            <div class="col-md-8">
                <label class="form-label">File Number</label>
                <input required type="text" class="form-control" name="FileNumber" value="<?php echo $row['FileNumber']; ?>">
            </div>
            <div class="col-md-8">
                <label class="form-label">Date</label>
                <input required type="date" class="form-control" name="Date" value=<?php
                                                                                    $timestamp = strtotime($row['Date']);
                                                                                    $Date = date("Y-m-d", $timestamp);
                                                                                    echo $Date; ?>>
            </div>
            <div class="col-md-6">
                <label class="form-label">Start Time</label>
                <input required type="time" class="form-control" name="StartTime" value=<?php $timestamp = strtotime($row['StartTime']);
                                                                                        $StartTime = date("H:i", $timestamp);
                                                                                        echo $StartTime;  ?>>
            </div>
            <div class="col-md-6">
                <label class="form-label">Finish Time</label>
                <input required type="time" class="form-control" name="FinishTime" value="<?php $timestamp = strtotime($row['FinishTime']);
                                                                                            $FinishTime = date("H:i", $timestamp);
                                                                                            echo $FinishTime;  ?>">
            </div>
            <div class="col-md-8">
                <label class="form-label">Subject Line</label>
                <input required type="text" class="form-control" name="SubjectLine" value="<?php echo $row['SubjectLine']; ?>">
            </div>
            <div class="col-md-12">
                <label class="form-label">Content</label>
                <textarea type="text" class="form-control" name="Content" style="height:150px"><?php echo $row['Content']; ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Attending Person</label>
                <select name="PersonAttended" class="form-select">
                    <?php initialsList($con, $row['PersonAttended'], true); ?>

                </select>
            </div>

            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" name="isCharged" type="checkbox" <?php
                                                                                        if ($row['isCharged'] == 1) {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?>>
                    <label class="form-check-label">
                        Billable
                    </label>
                </div>
            </div>



            <div class="col-12">
                <button type="submit" class="btn btn-primary" name="ID" value=<?php echo $ID; ?>>Update</button>
            </div>
        </form>
    </div>
    <?php
    writeFooter();
    ?>
</body>

</html>
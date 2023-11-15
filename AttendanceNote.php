<?php
include 'Connect.php';
include 'commonFunctions.php';

$FileNumber = $_REQUEST['FileNumber'];
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $FileNumber . " - " ?> AttendanceNotes</title>
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
    <div>
      <h1> <?php echo $FileNumber ?></h1>
    </div>
    <div class="row">
      <div class="col-6">
        <?php
        $sql = "SELECT * FROM matter_attendancenotes WHERE FileNumber = '$FileNumber' ORDER BY Date DESC";

        $data = $con->query($sql);

        $attendanceNotes = array();

        //checks whether there is a row in the database
        if ($data->num_rows > 0) {
          // output data of each row
          while ($row = $data->fetch_assoc()) {
            $attendanceNotes[] = $row; // adds the row from the database into the array
          }
        } else {
          echo 'No Attendance notes for this file number';
        }
        ?>

        <div class="accordion ">
          <?php
          $heading = 'heading';
          $collapse = 'collapse';
          $numberOFNotes = 0;
          foreach ($attendanceNotes as $q) { ?>
            <div class="accordion-item">
              <h2 class="accordion-header" <?php echo 'id= "' . $heading . $numberOFNotes . '"'; ?>>
                <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="false" <?php echo 'data-bs-target= "#' . $collapse . $numberOFNotes . '"  aria-controls= "#' . $collapse . $numberOFNotes . '" '; ?>>
                  <?php
                  $timestamp = strtotime($q['Date']);
                  $Date = date("d-m-Y", $timestamp);
                  echo "<b>" . $q['SubjectLine'] . "</b> - " . $Date; ?>
                </button>
              </h2>
              <div <?php echo ' id = "' . $collapse . $numberOFNotes . '"  class = "accordion-collapse collapse"  aria-labelledby= "' . $heading . $numberOFNotes . '"'; ?> data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php


                  echo "<b>Date:</b> " . $Date . "<br>";

                  $timestamp = strtotime($q['StartTime']);
                  $StartTime = date("h:ia", $timestamp);

                  echo "<b>Start Time:</b> " . $StartTime;
                  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; \t";

                  $timestamp = strtotime($q['FinishTime']);
                  $FinishTime = date("h:ia", $timestamp);

                  echo "<b>Finish Time:</b> " . $FinishTime;
                  echo "<br><br>";
                  ?>

                  <?php echo  "<div style='white-space: pre-wrap;'>" . $q['Content'] . "</div>";
                  echo "<br><br>";
                  echo "<b>Unit(s): </b>" . $q['Unit'] . " - <b>" . $q['PersonAttended'] . " </b> &emsp;";

                  ?>
                  <a href="serverCalls/DownloadAttendancNote.php?ID=<?php echo $q['ID']; ?>" rel="noopener noreferrer">Download Attendance Note</a>
                  &nbsp; &nbsp;
                  <a href="EditAttendanceNotePage.php?ID=<?php echo $q['ID']; ?>" rel="noopener noreferrer">Edit Attendance Note</a>
                </div>
              </div>
            </div>

          <?php
            $numberOFNotes++;
          } ?>

        </div>
      </div>
      <div class="col-6">
        <form class="row g-3" action="serverCalls/AddAttendanceNote.php" method="post" id="AddAttendanceNoteForm">
          <div class="col-md-8">
            <label class="form-label">File Number</label>
            <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumber" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Date</label>
            <input required type="date" class="form-control" name="Date">
          </div>
          <div class="col-md-6">
            <label class="form-label">Start Time</label>
            <input required type="time" class="form-control" name="StartTime">
          </div>
          <div class="col-md-6">
            <label class="form-label">Finish Time</label>
            <input required type="time" class="form-control" name="FinishTime">
          </div>
          <div class="col-md-8">
            <label class="form-label">Subject Line</label>
            <input required type="text" class="form-control" name="SubjectLine">
          </div>
          <div class="col-md-12">
            <label class="form-label">Content</label>
            <textarea type="text" class="form-control" name="Content" style="height:150px"></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Attending Person</label>
            <select name="PersonAttended" class="form-select">
              <?php initialsList($con, "", true); ?>
            </select>
          </div>

          <div class="col-md-4">
            <div class="form-check">
              <input class="form-check-input" name="isCharged" type="checkbox">
              <label class="form-check-label">
                Billable
              </label>
            </div>
          </div>


          <div class="col-12">
            <button type="submit" classs="btn btn-primary">Add Attendance Note</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="p-3 mb-2 bg-light text-dark">
    <footer class="mt-auto footer ">
      <div class="footer-copyright">
        <div class="container">
          <div class="row">
            <div class="col-md-12 text-center">
              <p>Copyright ANP Solicitors Limited © 2022. All rights reserved.</p>
            </div>
          </div>
        </div>
      </div>

    </footer>
  </div>

</body>

</html>
<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();

$FileNumber = $_REQUEST['FileNumber'];
$sql = "SELECT * FROM wip WHERE FileNumber='$FileNumber'";
$data = $con->query($sql);
$matter = $data->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $FileNumber . " - "; ?>Correspondence</title>
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
      <div class="col">
        <?php
        $sql = "SELECT * FROM matter_emails WHERE FileNumber = '$FileNumber' ORDER BY DATE DESC";

        $data = $con->query($sql);

        $emails = array();

        //checks whether there is a row in the database
        if ($data->num_rows > 0) {
          // output data of each row
          while ($row = $data->fetch_assoc()) {
            $emails[] = $row; // adds the row from the database into the array
          }
        } else {
          echo '<h5>No Emails for this file number</h5>';
        }

        $sql = "SELECT * FROM matter_letters WHERE FileNumber = '$FileNumber' ORDER BY DATE DESC";

        $data = $con->query($sql);

        $letters = array();

        //checks whether there is a row in the database
        if ($data->num_rows > 0) {
          // output data of each row
          while ($row = $data->fetch_assoc()) {
            $letters[] = $row; // adds the row from the database into the array
          }
        } else {
          echo '<h5>No Letters for this file number</h5>';
        }
        ?>

        <div class="accordion">
          <?php
          $heading = 'heading';
          $collapse = 'collapse';
          $numberOFEmails = 0;
          foreach ($emails as $q) { ?>
            <div class="accordion-item">
              <h2 class="accordion-header" <?php echo 'id= "' . $heading . $numberOFEmails . '"'; ?>>
                <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="true" <?php echo 'data-bs-target= "#' . $collapse . $numberOFEmails . '"  aria-controls= "#' . $collapse . $numberOFEmails . '" '; ?>>
                  <?php
                  $timestamp = strtotime($q['Time']);
                  $Time = date("h:ia", $timestamp);
                  $timestamp = strtotime($q['Date']);
                  $Date = date("d-m-Y", $timestamp);
                  if ($q['Sent'] == 1) {
                    echo "Email to " . $q['ToOrFrom'] . " @" . $Time . " - " . $Date;
                  }
                  if ($q['Received'] == 1) {
                    echo "Email from " . $q['ToOrFrom'] . " @" . $Time . " - " . $Date;
                  }
                  ?>
                </button>
              </h2>
              <div <?php echo ' id = "' . $collapse . $numberOFEmails . '"  class = "accordion-collapse collapse"  aria-labelledby= "' . $heading . $numberOFEmails . '"'; ?> data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php
                  echo $q['SubjectLine'] . " <br> <b>" . $q['PersonAttended'] . "</b>";
                  ?>
                  &nbsp; &nbsp; &nbsp; <a href="EditEmailPage.php?ID=<?php echo $q['ID']; ?>" rel="noopener noreferrer">Edit Email</a>
                </div>
              </div>
            </div>

          <?php
            $numberOFEmails++;
          } ?>
        </div>
        <div class="accordion mt-2">
          <?php
          $heading = 'heading';
          $collapse = 'collapse_letter';
          $numberOfLetters = 0;
          foreach ($letters as $q) { ?>
            <div class="accordion-item">
              <h2 class="accordion-header" <?php echo 'id= "' . $heading . $numberOfLetters . '"'; ?>>
                <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="true" <?php echo 'data-bs-target= "#' . $collapse . $numberOfLetters . '"  aria-controls= "#' . $collapse . $numberOfLetters . '" '; ?>>
                  <?php

                  $timestamp = strtotime($q['Date']);
                  $Date = date("d-m-Y", $timestamp);
                  if ($q['Sent'] == 1) {
                    echo "Letter to " . $q['ToOrFrom'] . " - " . $Date;
                  } else {
                    echo "Letter from " . $q['ToOrFrom'] . " - " . $Date;
                  }
                  ?>
                </button>
              </h2>
              <div <?php echo ' id = "' . $collapse . $numberOfLetters . '"  class = "accordion-collapse collapse"  aria-labelledby= "' . $heading . $numberOfLetters . '"'; ?> data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php
                  echo $q['SubjectLine'] . " <br> <b>" . $q['PersonAttended'] . "</b>";
                  ?>
                  &nbsp; &nbsp; &nbsp; <a href="EditLetterPage.php?ID=<?php echo $q['ID']; ?>" rel="noopener noreferrer">Edit Letter</a>
                </div>
              </div>
            </div>

          <?php
            $numberOfLetters++;
          } ?>
        </div>
      </div>
      <?php
      if ($matter['FileStatus'] != 'Archived') : ?>
        <div1 class="col">
          <button class="btn btn-primary m-1" type="button" data-bs-toggle="collapse" data-bs-target="#emailForm" aria-expanded="true" aria-controls="FileSummary">
            Add Email
          </button>
          <button class="btn btn-primary m-1" type="button" data-bs-toggle="collapse" data-bs-target="#letterForm" aria-expanded="true" aria-controls="FileSummary">
            Add Letter
          </button>
          <div class="collapse collapse-vertical" id="emailForm" aria-expanded="true">
            <div class="card card-body shadow-sm p-3 mt-3 mb-2 bg-body rounded" style="width:100%;">

              <form class="row g-3" action="serverCalls/AddEmails.php" method="post" id="AddEmailForm">
                <legend>Add Email</legend>
                <div class="col-md-8">
                  <label class="form-label">File Number</label>
                  <input required type="text" class="form-control" name="FileNumber" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
                </div>
                <div class="col-md-8">
                  <label class="form-label">Date</label>
                  <input required type="date" class="form-control" name="Date">
                </div>
                <div class="col-md-5">
                  <div class="form-check">
                    <input class="form-check-input" name="To" type="checkbox">
                    <label class="form-check-label">
                      To
                    </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-check">
                    <input class="form-check-input" name="From" type="checkbox">
                    <label class="form-check-label">
                      From
                    </label>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">To/From</label>
                  <input required type="text" class="form-control" name="ToOrFrom">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Time Sent/Received</label>
                  <input required type="time" class="form-control" name="Time">
                </div>
                <div class="col-md-8">
                  <label class="form-label">Subject Line</label>
                  <input required type="text" class="form-control" name="SubjectLine">
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
                      Billable (1 unit)
                    </label>
                  </div>
                </div>



                <div class="col-12">
                  <button type="submit" class="btn btn-primary">Add Email</button>
                </div>
              </form>
            </div>
          </div>
          <div class="collapse collapse-vertical" id="letterForm" aria-expanded="true">
            <div class="card card-body shadow-sm p-3 mt-3 mb-2 bg-body rounded" style="width:100%;">

              <form class="row g-3" action="serverCalls/AddLetter.php" method="post" id="AddLetterForm">
                <legend>Add Letter</legend>
                <div class="col-md-8">
                  <label class="form-label">File Number</label>
                  <input required type="text" class="form-control" name="FileNumber" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
                </div>
                <div class="col-md-8">
                  <label class="form-label">Date</label>
                  <input required type="date" class="form-control" name="Date">
                </div>
                <div class="col-md-5">
                  <div class="form-check">
                    <input class="form-check-input" name="To" type="checkbox">
                    <label class="form-check-label">
                      To
                    </label>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-check">
                    <input class="form-check-input" name="From" type="checkbox">
                    <label class="form-check-label">
                      From
                    </label>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">To/From</label>
                  <input required type="text" class="form-control" name="ToOrFrom">
                </div>

                <div class="col-md-8">
                  <label class="form-label">Subject Line</label>
                  <input required type="text" class="form-control" name="SubjectLine">
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
                      Billable (1 unit)
                    </label>
                  </div>
                </div>



                <div class="col-12">
                  <button type="submit" class="btn btn-primary">Add Letter</button>
                </div>
              </form>
            </div>
          </div>
        </div1>
      <?php
      endif; ?>
    </div>
  </div>
  <?php
  writeFooter();
  ?>
</body>

</html>
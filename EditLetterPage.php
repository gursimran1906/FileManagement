<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();

$ID = $_REQUEST['ID'];
$sql = $sql = "SELECT * FROM matter_letters WHERE ID = '$ID' LIMIT 1";



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
    <form class="row g-3" action="serverCalls/updateletter.php" method="post">
      <div class="col-md-8">
        <label class="form-label">File Number</label>
        <input required type="text" class="form-control" name="FileNumber" value="<?php echo $row['FileNumber']; ?>">
      </div>
      <div class="col-md-8">
        <label class="form-label">Date</label>
        <input required type="date" class="form-control" name="Date" value="<?php
                                                                            $timestamp = strtotime($row['Date']);
                                                                            $Date = date("Y-m-d", $timestamp);
                                                                            echo $Date; ?>">
      </div>
      <div class="col-md-5">
        <div class="form-check">
          <input class="form-check-input" name="To" type="checkbox" <?php
                                                                    if ($row['Sent'] == 1) {
                                                                      echo "checked";
                                                                    }
                                                                    ?>>
          <label class="form-check-label">
            To
          </label>
        </div>
      </div>
      <div class="col-md-5">
        <div class="form-check">
          <input class="form-check-input" name="From" type="checkbox" <?php
                                                                      if ($row['Received'] == 1) {
                                                                        echo "checked";
                                                                      }
                                                                      ?>>
          <label class="form-check-label">
            From
          </label>
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label">To/From</label>
        <input required type="text" class="form-control" name="ToOrFrom" value="<?php echo $row['ToOrFrom']; ?>">
      </div>

      <div class="col-md-8">
        <label class="form-label">Subject Line</label>
        <input required type="text" class="form-control" name="SubjectLine" value="<?php echo $row['SubjectLine']; ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Attending Person</label>
        <select name="PersonAttended" class="form-select">
          <?php initialsList($con, $row['PersonAttended'], true); ?>

      </div>

      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input" name="isCharged" type="checkbox" <?php
                                                                            if ($row['IsCharged'] == 1) {
                                                                              echo "checked";
                                                                            }
                                                                            ?>>
          <label class="form-check-label">
            Billable (1 unit)
          </label>
        </div>
      </div>



      <div class="col-12">
        <button type="submit" class="btn btn-primary" name="ID" value="<?php echo $ID; ?>">Update</button>
      </div>
    </form>
  </div>
  <?php
  writeFooter();
  ?>
</body>

</html>
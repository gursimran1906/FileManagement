<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();

$ID = $_REQUEST['ID'];
$sql = $sql = "SELECT * FROM ledger_accounts_transfers WHERE ID = '$ID' LIMIT 1";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $row['FileNumberFrom'] . " - "; ?>Edit Blue Slip</title>
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

  <div class="container mt-3">
    <form class="row g-3" action="serverCalls/UpdateGreenSlip.php" method="post" id="GreenSlipForm">
      <div class="col-md-6">
        <label class="form-label">File Number From</label>
        <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumberFrom" value="<?php echo $row['FileNumberFrom']; ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">File Number To</label>
        <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumberTo" value="<?php echo $row['FileNumberTo']; ?>">
      </div>
      <div class="col-md-6">
        <fieldset>
          <h5>From Account</h5>
          <div class="form-check">
            <input class="form-check-input" name="FromClientAcc" type="checkbox" <?php
                                                                                  if ($row['FromLedgerAccount'] == 'C') {
                                                                                    echo "checked";
                                                                                  }
                                                                                  ?>>
            <label class="form-check-label">
              Client Account
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" name="FromOfficeAcc" type="checkbox" <?php
                                                                                  if ($row['FromLedgerAccount'] == 'O') {
                                                                                    echo "checked";
                                                                                  }
                                                                                  ?>>
            <label class="form-check-label">
              Office Account
            </label>
          </div>
        </fieldset>
      </div>
      <div class="col-md-6">
        <fieldset>
          <h5>To Account</h5>
          <div class="form-check">
            <input class="form-check-input" name="ToClientAcc" type="checkbox" <?php
                                                                                if ($row['ToLedgerAccount'] == 'C') {
                                                                                  echo "checked";
                                                                                }
                                                                                ?>>
            <label class="form-check-label">
              Client Account
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" name="ToOfficeAcc" type="checkbox" <?php
                                                                                if ($row['ToLedgerAccount'] == 'O') {
                                                                                  echo "checked";
                                                                                }
                                                                                ?>>
            <label class="form-check-label">
              Office Account
            </label>
          </div>
        </fieldset>
      </div>

      <div class="col-md-4">
        <label class="form-label">Date</label>
        <input required type="Date" class="form-control" name="Date" value="<?php
                                                                            $timestamp = strtotime($row['Date']);
                                                                            $Date = date("Y-m-d", $timestamp);
                                                                            echo $Date; ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Amount (incl. VAT)</label>
        <input required type="number" step="any" class="form-control" name="Amount" placeholder="£0.00" value="<?php echo $row['Amount']; ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Description</label>
        <input required type="text" class="form-control" name="Description" placeholder="Details" value="<?php echo $row['Description']; ?>">
      </div>

      <div class="col-12">
        <button type="submit" class="mb-2 btn btn-primary" name="ID" value="<?php echo $ID; ?>">Update</button>
      </div>
    </form>

  </div>
  <?php
  writeFooter();
  ?>
</body>

</html>
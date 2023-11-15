<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();

$ID = $_REQUEST['ID'];
$sql = $sql = "SELECT * FROM invoices WHERE ID = '$ID' LIMIT 1";



$result = mysqli_query($con, $sql);
$Invoice = mysqli_fetch_assoc($result);

$FileNumber = $Invoice['FileNumber'];
$sql = "SELECT * FROM pmts_slip WHERE FileNumber = '$FileNumber' ORDER BY Date DESC";

$data = $con->query($sql);

$pmts = array();



//checks whether there is a row in the database
if ($data->num_rows > 0) {
  // output data of each row
  while ($row = $data->fetch_assoc()) {
    $pmts[] = $row; // adds the row from the database into the array
  }
}

$pmts = array_reverse($pmts);

$sql = "SELECT * FROM ledger_accounts_transfers WHERE FileNumberFrom = '$FileNumber' OR FileNumberTo ='$FileNumber' ORDER BY Date DESC";

$data = $con->query($sql);

$tfrs = array();

if ($data->num_rows > 0) {
  // output data of each row
  while ($row = $data->fetch_assoc()) {
    $tfrs[] = $row; // adds the row from the database into the array
  }
}
$tfrs = array_reverse($tfrs);



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <meta name="author" content="Gursimran Singh">
  <title>Edit Invoice - <?php echo $Invoice['FileNumber']; ?></title>

  <script src="./js/commonFucntions.js" defer></script>

  <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
  <?php writeNavBar(); ?>
  <?php sessionCheck(); ?>

  <div class="container mt-3">
    <h1>Edit Invoice</h1>
    <form class="row g-3" action="serverCalls/UpdateInvoice.php" method="get" id="InvoiceForm">



      <div class="col-md-10">
        <label class="form-label">File Number</label>
        <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumber" value="<?php echo $Invoice['FileNumber']; ?>" placeholder="<?php echo $FileNumber; ?>">
      </div>
      <?php
      if ($Invoice['State'] != 'Final') {
        echo '<div class="col-md-2">
        <label class="form-label">Draft/Final</label>
        <select name="State" class="form-select">
          <option selected>Draft</option>
          <option>Final</option>
        </select>
      </div>';
      }
      ?>



      <div class="col-md-3">
        <label class="form-label">Payable by</label>
        <input required type="text" class="form-control" name="PayableBy" placeholder="eg. Client" value="<?php echo $Invoice['PayableBy']; ?>">
      </div>

      <div class="col-md-3">
        <label class="form-label">Date</label>
        <input type="Date" class="form-control" name="Date" value=<?php
                                                                  $timestamp = strtotime($Invoice['Date']);
                                                                  $Date = date("Y-m-d", $timestamp);
                                                                  echo "'" . $Date . "'"; ?>>

      </div>
      <div class='col-md-3'>
        <label class=" mt-4 form-label">By email</label>
        <input class="mt-4 form-check-input" type="checkbox" name="ByEmail" <?php
                                                                            if ($Invoice['ByEmail'] == 1) {
                                                                              echo 'checked';
                                                                            }
                                                                            ?>>
      </div>
      <div class='col-md-3'>
        <label class="mt-4 form-label">By post</label>
        <input class="mt-4 form-check-input" type="checkbox" name="ByPost" <?php
                                                                            if ($Invoice['ByPost'] == 1) {
                                                                              echo 'checked';
                                                                            }
                                                                            ?>>
      </div>
      <div class="col-md-12">
        <label class="form-label">Description</label>
        <textarea required type="text" class="form-control" name="Description"><?php echo $Invoice['Description']; ?></textarea>
      </div>

      <div class="col-md-12" id="our_costs_row">
        <div class="row">
          <div class="col-md-5">
            <label class="form-label">
              <h5>Our Costs</h5>
            </label>
          </div>
          <div class="col-md-5">
            <label class="form-label">
              <h5>Amount excl VAT</h5>
            </label>
          </div>
        </div>



        <?php
        $OurCostDesc = json_decode($Invoice['OurCostsDesc']);
        $OurCost = json_decode($Invoice['OurCosts']);
        for ($i = 0; $i < count($OurCost); $i++) {
          echo '<div class="row">
            <div class="col-md-5"><input type="text" class="mb-2 form-control" id="our_costs_description" placeholder="Costs Description" name="our_costs_desc[]" value="' . $OurCostDesc[$i] . '"></div>
            <div class="col-md-5"><input required type="Currency" class="mb-2 form-control" name="our_costs[]" id="our_costs" placeholder="£0.00" value="' . $OurCost[$i] . '"></div>
            <div class="col-md-2"><span type="button" class="mb-2 btn btn-danger" onclick="removeField(this);">-</span><br></div>
            </div>';
        }

        ?>

        <div class="row">
          <div class="col-md-5">
            <input type="text" class="mb-2 form-control" id="our_costs_description" placeholder="Costs Description" name="our_costs_desc[]">
          </div>
          <div class="col-md-5">
            <input type="Currency" class="mb-2 form-control" name="our_costs[]" id="our_costs" placeholder="£0.00">
          </div>
          <div class="col-md-2">
            <span type='button' class='mb-2 btn btn-primary' onclick="addFields();" id="add_more_fields">+</span>
          </div>
        </div>
      </div>



      <?php
      $DisbIDs = json_decode($Invoice['DisbsIDs']);
      $BlueSlipIDs = json_decode($Invoice['MOA_IDs']);
      $GreenSlipsIDS = json_decode($Invoice['GreenSlip_IDs']);
      if ($pmts != null) {
        echo '<div class="col-md-12"> 
    <legend>Pink slips to be added</legend>';
      }

      foreach ($pmts as $q) { ?>
        <?php
        if ($q['PMTToOrFrom'] == 1) {
          $LedgerAcc = "";
          if ($q['LedgerAccount'] == 'O') {
            $LedgerAcc = "Office Account Ledger";
          } else {
            $LedgerAcc = "Client Account Ledger";
          }
          $timestamp = strtotime($q['Date']);
          $Date = date("d-m-Y", $timestamp);
          $BalanceLeft = $q['BalanceLeft'];
          $checked = false;
          $available = false;
          if ($DisbIDs != null && in_array($q['ID'], $DisbIDs)) {
            $checked = true;
            $available = true;
          } else if ($BalanceLeft > 0) {
            $available = true;
          }


          if ($available) {

            echo '<div class="form-check">
            <input ';
            if ($checked) {
              echo 'checked ';
            }
            echo 'class="form-check-input" name="PinkSlipsToAdd[]" type="checkbox" value="' . $q['ID'] . '""></input>
                      <label class="form-check-label">Payment to&nbsp;<b>' . $q['PMTPerson'] . '</b>&nbsp;of £' . number_format(round($q['Amount'], 2), 2) . ' from ' . $LedgerAcc . ' on ' . $Date . '</label>
                  </div>
                ';
          }
        }
        ?>

      <?php } ?>
      <?php
      if ($pmts != null) {
        echo '</div>';
      }

      ?>
      <?php
      if ($pmts != null) {
        echo '<div class="col-md-12"> 
    <legend>Blue slips to be added</legend>';
      }

      foreach ($pmts as $q) { ?>
        <?php
        if ($q['PMTToOrFrom'] == 0) {
          $LedgerAcc = "";
          if ($q['LedgerAccount'] == 'O') {
            $LedgerAcc = "Office Account Ledger";
          } else {
            $LedgerAcc = "Client Account Ledger";
          }
          $timestamp = strtotime($q['Date']);
          $Date = date("d-m-Y", $timestamp);
          $AmountInvoiced = 0.00;


          if ($BlueSlipIDs != null && in_array($q['ID'], $BlueSlipIDs)) {

            if ($q['AmountInvoiced'] != null) {
              $obj = json_decode($q['AmountInvoiced']);



              $InvoiceData = json_decode($obj->{"$ID"});
              $AmountInvoiced = $InvoiceData->{'Amount'};


              echo '<div class="mt-1 form-check">
                        <input class="mt-2 form-check-input" checked name="BlueSlipsToAdd[]" type="checkbox" value="' . $q['ID'] . '"">
                        <label class="form-check-label">Payment of <b>£' . number_format(round($AmountInvoiced, 2), 2) . '</b> from&nbsp;<b>' . $q['PMTPerson'] . '</b>&nbsp;to ' . $LedgerAcc . ' on ' . $Date . '</label>
                    </div>
                  ';
            }
          } else {
            $BalanceLeft = $q['BalanceLeft'];
            $Amount = $q['Amount'];
            if ($BalanceLeft > 0) {


              echo '<div class="mt-1 form-check">
                        <input class="mt-2 form-check-input" name="BlueSlipsToAdd[]" type="checkbox" value="' . $q['ID'] . '"">
                        <label class="form-check-label">Payment of <b>£' . number_format(round($BalanceLeft, 2), 2) . '</b> from&nbsp;<b>' . $q['PMTPerson'] . '</b>&nbsp;of £' .  number_format(round($Amount, 2), 2) . ' to ' . $LedgerAcc . ' on ' . $Date . '</label>
                    </div>
                  ';
            }
          }
        }

        ?>

      <?php } ?>
      <?php
      if ($pmts != null) {
        echo '</div>';
      }
      ?>
      <?php
      if ($tfrs != null) {
        echo '<div class="col-md-12"> 
                      <legend>Green slips to be attached</legend>';
      }

      foreach ($tfrs as $q) { ?>
        <?php
        $timestamp = strtotime($q['Date']);
        $Date2 = date("d-m-Y", $timestamp);
        if ($q['FromLedgerAccount'] == 'O') {
          $FromLedgerAcc = 'Office Account Ledger';
        } else {
          $FromLedgerAcc = 'Client Account Ledger';
        }
        if ($q['ToLedgerAccount'] == 'O') {
          $ToLedgerAccFrom = 'Office Account Ledger';
        } else {
          $ToLedgerAcc = 'Client Account Ledger';
        }
        echo '<div class="form-check"> 
                  <input ';
        if ($GreenSlipsIDS != null && in_array($q['ID'], $GreenSlipsIDS)) {
          echo 'checked';
        }
        echo ' class="form-check-input" name="GreenSlipsToAdd[]" type="checkbox" value="' . $q['ID'] . '"">
                  <label class="form-check-label">From ' . $q['FileNumberFrom'] . ' to ' . $q['FileNumberTo'] . ' <b>£' . number_format(round($q['Amount'], 2), 2) . '</b> on ' . $Date2 . '</label>
                  
                  </div>';
        ?>

      <?php } ?>
      <div class="col-12">
        <button type="submit" class="mb-2 btn btn-primary" name='ID' value="<?php echo $ID; ?>">Edit Invoice</button>
      </div>

    </form>

  </div>
  </div>
  <div class="footer">

    <?php
    writeFooter();
    ?>
  </div>

</body>

</html>
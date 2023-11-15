<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();

$FileNumber = $_REQUEST['FileNumber'];
$UserInitials = $_SESSION['userInitials'];
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $FileNumber . " - " ?> Finances</title>
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <meta name="author" content="Gursimran Singh">

  <script src="./js/commonFucntions.js" defer></script>
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
      <div class="mb-2 col-6">

        <div>
          <?php
          $sql = "SELECT * FROM invoices WHERE FileNumber = '$FileNumber' ORDER BY Date DESC";

          $data = $con->query($sql);

          $invoices = array();

          //checks whether there is a row in the database
          if ($data->num_rows > 0) {
            // output data of each row
            while ($row = $data->fetch_assoc()) {
              $invoices[] = $row; // adds the row from the database into the array
            }
          } else {
            echo '<h4>No invoices on the WIP for this matter</h4>';
          }
          $sql = "SELECT * FROM pmts_slip WHERE FileNumber = '$FileNumber' ORDER BY Date DESC";

          $data = $con->query($sql);

          $pmts = array();

          //checks whether there is a row in the database
          if ($data->num_rows > 0) {
            // output data of each row
            while ($row = $data->fetch_assoc()) {
              $pmts[] = $row; // adds the row from the database into the array
            }
          } else {
            echo '<h4>No Pink or Blue slips for this matter</h4>';
          }

          $DraftInvCol = "#F9EBDF";
          $InvCol = "#FFFCC9";
          $GreenCol = "#90EE90";
          $TempCol = "#CCD1D1";


          $sqlSumMoniesIn = "SELECT sum(Amount) AS TotalMoniesIn FROM ledger_accounts_transfers WHERE FileNumberTo = '$FileNumber'";
          $result = mysqli_query($con, $sqlSumMoniesIn);
          $row = mysqli_fetch_assoc($result);
          $TotalMoniesInGreenSlip = $row['TotalMoniesIn'];
          $TotalMoniesInGreenSlip = round($TotalMoniesInGreenSlip, 2);

          $sqlSumMoniesIn = "SELECT sum(Amount) AS TotalMoniesIn FROM pmts_slip WHERE FileNumber = '$FileNumber' AND PMTToOrFrom = 0";
          $result = mysqli_query($con, $sqlSumMoniesIn);
          $row = mysqli_fetch_assoc($result);
          $TotalMoniesIn = $row['TotalMoniesIn'];
          $TotalMoniesIn = round($TotalMoniesIn + $TotalMoniesInGreenSlip, 2);

          $sqlSumMoniesOut = "SELECT sum(Amount) AS TotalMoniesOut FROM pmts_slip WHERE FileNumber = '$FileNumber' AND PMTToOrFrom = 1";
          $result = mysqli_query($con, $sqlSumMoniesOut);
          $row = mysqli_fetch_assoc($result);
          $TotalMoniesOutSlips = $row['TotalMoniesOut'];

          $sqlSumMoniesOut = "SELECT sum(Amount) AS TotalMoniesOut FROM pmts_slip WHERE FileNumber = '$FileNumber' AND PMTToOrFrom = 2";
          $result = mysqli_query($con, $sqlSumMoniesOut);
          $row = mysqli_fetch_assoc($result);
          $TotalMoniesOut = $row['TotalMoniesOut'];
          $TotalMoniesOutPI = round($TotalMoniesOut + $TotalMoniesOutSlips, 2);

          $sqlSumMoniesOut = "SELECT sum(Amount) AS TotalMoniesOut FROM ledger_accounts_transfers WHERE FileNumberFrom = '$FileNumber'";
          $result = mysqli_query($con, $sqlSumMoniesOut);
          $row = mysqli_fetch_assoc($result);
          $TotalMoniesOutGreenSlip = $row['TotalMoniesOut'];
          $TotalMoniesOut = round($TotalMoniesOutPI + $TotalMoniesOutGreenSlip, 2);

          $sqlSumTempMoniesIn = "SELECT sum(Amount) as TotalMoniesTemp From temp_slips WHERE FileNumber = '$FileNumber' AND Amount >0";
          $result = mysqli_query($con, $sqlSumTempMoniesIn);
          $row = mysqli_fetch_assoc($result);
          $TotalTempMoniesIn = $row['TotalMoniesTemp'];
          $TotalMoniesIn = round($TotalMoniesIn + $TotalTempMoniesIn, 2);

          $sqlSumTempMoniesOut = "SELECT sum(Amount) as TotalMoniesTemp From temp_slips WHERE FileNumber = '$FileNumber' AND Amount <0";
          $result = mysqli_query($con, $sqlSumTempMoniesOut);
          $row = mysqli_fetch_assoc($result);
          $TotalTempMoniesOut = $row['TotalMoniesTemp'];
          $TotalMoniesOut = round($TotalMoniesOut + (-$TotalTempMoniesOut), 2);
          ?>
        </div>

        <div class="pt-2 accordion accordion-flush">
          <?php
          $heading = 'heading';
          $collapse = 'collapse';
          $numOfInv = 0;
          $TotalAmountOfAllInvoices = 0;

          foreach ($invoices as $q) { ?>
            <div class="accordion-item" <?php
                                        if ($q['State'] == 'Draft') {
                                          echo 'style = "background-color:' . $DraftInvCol . ' !important;" ';
                                        } else {
                                          echo 'style = "background-color:' . $InvCol . ' !important;" ';
                                        }

                                        ?>>
              <h2 class="accordion-header" <?php echo 'id= "' . $heading . $numOfInv . '"'; ?>>
                <button class="btn accordion-button collapsed" <?php
                                                                if ($q['State'] == 'Draft') {
                                                                  echo 'style = "background-color:' . $DraftInvCol . ' !important;" ';
                                                                } else {
                                                                  echo 'style = "background-color:' . $InvCol . ' !important;" ';
                                                                }

                                                                ?> type="button" data-bs-toggle="collapse" aria-expanded="false" <?php echo 'data-bs-target= "#' . $collapse . $numOfInv . 'INV"  aria-controls= "#' . $collapse . $numOfInv . 'INV" '; ?>>
                  <?php
                  $timestamp = strtotime($q['Date']);
                  $Date = date("d-m-Y", $timestamp);
                  $TotalCosts = 0;
                  $OurCosts = json_decode($q['OurCosts']);
                  $OurCostsDesc = json_decode($q['OurCostsDesc']);
                  $PinkSlipIDs = json_decode($q['DisbsIDs']);
                  $BlueSlipsIDs = json_decode($q['MOA_IDs']);
                  $GreenSlipIDs = json_decode($q['GreenSlip_IDs']);
                  $AllocatedIDs = json_decode($q['CashAllocatedSlips']);

                  $TotalGreenSlips = 0;
                  $TotalAllocated = 0;

                  $TotalCosts = array_sum($OurCosts);
                  $TotalAmountOfAllInvoices = $TotalAmountOfAllInvoices + $TotalCosts;
                  if ($q['State'] == 'Draft') {
                    echo "<b>DRAFT&nbsp;</b>";
                  }
                  echo " Inovice no.&nbsp;" . $q['InvoiceNumber'] . "&nbsp;of amount £" . number_format(round($TotalCosts, 2), 2) . "+VAT on " . $Date;

                  ?>
                </button>
              </h2>
              <div <?php echo ' id = "' . $collapse . $numOfInv . 'INV"  class = "accordion-collapse collapse"  aria-labelledby= "' . $heading . $numOfInv . '"'; ?> data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php

                  echo "<b>Date:</b> " . $Date . "<br>";
                  echo "<b>Invoice No:</b> " . $q['InvoiceNumber'] . "<br>";

                  echo  "<br>" . $q['Description'] . " <br>";


                  for ($i = 0; $i < count($OurCosts); $i++) {
                    echo '<br><b>' . $OurCostsDesc[$i] . ':</b> £' . number_format(round($OurCosts[$i], 2), 2);
                  }
                  $VAT = round($TotalCosts, 2) * 0.2;
                  echo '<br>VAT @20%: £' . number_format($VAT, 2);
                  $TotalCostAndVAT = $VAT + $TotalCosts;
                  echo '<br><b>Total Costs and VAT:</b> £' . number_format(round($TotalCostAndVAT, 2), 2);
                  ?>
                  <?php
                  $TotalPinkSlips = 0;
                  if ($PinkSlipIDs != null) {
                    echo '<br><br><h5>Pink Slips Attached</h5>';

                    foreach ($PinkSlipIDs as $ID) {
                      $sql = "SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";
                      $result = mysqli_query($con, $sql);
                      $slip = mysqli_fetch_assoc($result);

                      $TotalPinkSlips = $TotalPinkSlips + $slip['Amount'];
                      $timestamp = strtotime($slip['Date']);
                      $Date = date("d-m-Y", $timestamp);
                      if ($slip['LedgerAccount'] == 'O') {
                        $LedgerAcc = 'Office Account Ledger';
                      } else {
                        $LedgerAcc = 'Client Account Ledger';
                      }

                      echo "Payment to&nbsp;<b>" . $slip['PMTPerson'] . "</b>&nbsp;of £" . number_format(round($slip['Amount'], 2), 2) . " from " . $LedgerAcc . " on <b>" . $Date . "</b><br>";
                    }
                    echo '<b>Total Disbursements: </b>£' . number_format(round($TotalPinkSlips, 2), 2) . '<br>';
                  } else {
                    echo '<br><br><h5>No Pink Slips Attached</h5>';
                  }

                  $TotalBlueSlips = 0;
                  if ($BlueSlipsIDs != null) {
                    echo '<br><h5>Blue Slips Attached</h5>';

                    foreach ($BlueSlipsIDs as $ID) {
                      $sql = "SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";
                      $result = mysqli_query($con, $sql);
                      $slip = mysqli_fetch_assoc($result);

                      $timestamp = strtotime($slip['Date']);
                      $Date = date("d-m-Y", $timestamp);
                      if ($slip['LedgerAccount'] == 'O') {
                        $LedgerAcc = 'Office Account Ledger';
                      } else {
                        $LedgerAcc = 'Client Account Ledger';
                      }


                      if ($slip['AmountInvoiced'] != null) {

                        $obj = json_decode($slip['AmountInvoiced']);

                        $InvoiceID = $q['ID'];

                        $InvoiceData = json_decode($obj->{"$InvoiceID"});


                        $AmountInvoiced =  $InvoiceData->{"Amount"};
                      }
                      $TotalBlueSlips = $TotalBlueSlips + $AmountInvoiced;


                      echo "Payment from&nbsp;<b>" . $slip['PMTPerson'] . "</b>&nbsp;of £" . number_format(round($AmountInvoiced, 2), 2) . " to " . $LedgerAcc . " on <b>" . $Date . "</b><br>";
                    }
                    echo '<b>Total Monies On Account: </b>£' . number_format(round($TotalBlueSlips, 2), 2) . '<br>';
                  } else {
                    echo '<br><h5>No Blue Slips Attached</h5>';
                  }

                  if ($GreenSlipIDs != null) {
                    echo '<br><h5>Green Slips Attached</h5>';
                    foreach ($GreenSlipIDs as $GSID) {
                      $sql = "SELECT * FROM ledger_accounts_transfers WHERE ID = '$GSID' LIMIT 1";
                      $result = mysqli_query($con, $sql);
                      $slip = mysqli_fetch_assoc($result);

                      $timestamp = strtotime($slip['Date']);
                      $Date = date("d-m-Y", $timestamp);
                      $obj = $slip['AmountInvoicedFrom'];


                      $InvoiceID = $q['ID'];

                      $AmtInvoicedFrom = $slip['AmountInvoicedFrom'];

                      $obj = json_decode($slip['AmountInvoicedTo']);

                      $AmtInvoicedTo = $obj->{"$InvoiceID"};
                      if ($slip['FileNumberFrom'] == $FileNumber) {

                        echo 'Transfer to <b>' . $slip['FileNumberTo'] . '</b>';
                        echo ' of amount £' . number_format(round($AmtInvoicedFrom, 2), 2) . ' - <b>' . $Date . '</b><br>';
                        $TotalGreenSlips = $TotalGreenSlips - $slip['Amount'];
                      }
                      if ($slip['FileNumberTo'] == $FileNumber) {

                        echo 'Transfer from <b>' . $slip['FileNumberFrom'] . '</b>';
                        echo ' of amount £' . number_format(round($AmtInvoicedTo, 2), 2) . ' - <b>' . $Date . '</b><br>';
                        $TotalGreenSlips = $TotalGreenSlips + $AmtInvoicedTo;
                      }
                    }
                    echo '<b>Total Balance Green Slips: </b>£' . number_format(round($TotalGreenSlips, 2), 2) . '<br>';
                  } else {
                    echo '<br><h5>No Green Slips Attached</h5>';
                  }

                  // $Balance = (($TotalCostAndVAT + $TotalPinkSlips) - ($TotalBlueSlips + $TotalGreenSlips));
                  // if ($Balance >= 0) {
                  //   echo '<br><b>Total Due:</b> £' . number_format(round($Balance, 2), 2) . '<br>';
                  // } else {
                  //   echo '<br><b>Balance On Account:</b> £' . number_format(round($Balance, 2) * -1, 2) . '<br>';
                  // }

                  if ($AllocatedIDs != null) {
                    echo '<br><h5>Blus Slips Allocated after invoice issued</h5>';
                    foreach ($AllocatedIDs as $AID) {
                      $sql = "SELECT * FROM pmts_slip WHERE ID = '$AID' LIMIT 1";
                      $result = mysqli_query($con, $sql);
                      $slip = mysqli_fetch_assoc($result);

                      $timestamp = strtotime($slip['Date']);
                      $Date = date("d-m-Y", $timestamp);
                      $obj = json_decode($slip['AmountAllocated'], true);


                      $InvoiceID = $q['ID'];

                      $AmtInvoiced = json_decode($obj[$InvoiceID], true);
                      $Amount = $AmtInvoiced['Amount'];

                      echo 'Balance of <b>' . $Amount . '</b>';
                      echo ' from amount £' . number_format(round($slip['Amount'], 2), 2) . ' - <b>' . $Date . '</b><br>';
                      $TotalAllocated = $TotalAllocated + $Amount;
                    }
                    echo '<b>Total Blue Slips Allocated: </b>£' . number_format(round($TotalAllocated, 2), 2) . '<br>';
                  } else {
                    echo '<br><h5>No Blue slips Allocated after invoice issued</h5>';
                  }

                  $Balance = (($TotalCostAndVAT + $TotalPinkSlips) - ($TotalBlueSlips + $TotalAllocated + $TotalGreenSlips));
                  if ($Balance >= 0) {
                    echo '<br><b>Total Due:</b> £' . number_format(round($Balance, 2), 2) . '<br>';
                  } else {
                    echo '<br><b>Balance On Account:</b> £' . number_format(round($Balance, 2) * -1, 2) . '<br>';
                  }


                  echo "<br><a style='color: black !important; text-decoration: none !important;' href='serverCalls/DownloadInvoice.php?ID=" . $q['ID'] . "'target='_blank' rel='noopener noreferrer'>Download Invoice</a>&nbsp; &nbsp;";

                  echo "<a style='color: black !important; text-decoration: none !important;' href='EditInvoicePage.php?ID=" . $q['ID'] . "' target='_blank' rel='noopener noreferrer'>Edit Invoice</a>";
                  ?>

                </div>
              </div>

            </div>

          <?php
            $numOfInv++;
          } ?>

        </div>


        <div class="pt-2 accordion accordion-flush">
          <?php
          $heading = 'heading';
          $collapse = 'collapse';
          $numOfPmts = 0;
          foreach ($pmts as $q) { ?>
            <div class="accordion-item" <?php if ($q['PMTToOrFrom'] == 0) {
                                          echo " style='background-color: lightblue !important;' ";
                                        } else if ($q['PMTToOrFrom'] == 1) {
                                          echo  " style='background-color: pink !important;' ";
                                        } else {
                                          echo " style='background-color:" . $InvCol . " !important;' ";
                                        } ?>>
              <h2 class="accordion-header" <?php echo 'id= "' . $heading . $numOfPmts . '"'; ?>>
                <button class="btn accordion-button collapsed" <?php if ($q['PMTToOrFrom'] == 0) {
                                                                  echo " style='background-color: lightblue !important;' ";
                                                                } else if ($q['PMTToOrFrom'] == 1) {
                                                                  echo  " style='background-color: pink !important;' ";
                                                                } else {
                                                                  echo " style='background-color: " . $InvCol . " !important;' ";
                                                                } ?> type="button" data-bs-toggle="collapse" aria-expanded="false" <?php echo 'data-bs-target= "#' . $collapse . $numOfPmts . '"  aria-controls= "#' . $collapse . $numOfPmts . '" '; ?>>
                  <?php
                  $timestamp = strtotime($q['Date']);
                  $Date = date("d-m-Y", $timestamp);
                  if ($q['LedgerAccount'] == 'O') {
                    $LedgerAcc = 'Office Account Ledger';
                  } else {
                    $LedgerAcc = 'Client Account Ledger';
                  }
                  if ($q['PMTToOrFrom'] == 1) {
                    echo "Payment to&nbsp;<b>" . $q['PMTPerson'] . "</b>&nbsp;of £" . number_format(round($q['Amount'], 2), 2) . " from " . $LedgerAcc . " on " . $Date;
                  } else if ($q['PMTToOrFrom'] == 2) {
                    echo "Inovice no.&nbsp;" . $q['Description'] . "&nbsp;of amount £" . number_format(round($q['Amount'], 2), 2) . " on " . $Date;
                  } else {
                    echo "Payment from&nbsp; <b>" . $q['PMTPerson'] . "</b>&nbsp;of £" . number_format(round($q['Amount'], 2), 2) . " to " . $LedgerAcc . " on " . $Date;
                  }
                  ?>
                </button>
              </h2>
              <div <?php echo ' id = "' . $collapse . $numOfPmts . '"  class = "accordion-collapse collapse"  aria-labelledby= "' . $heading . $numOfPmts . '"'; ?> data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php


                  echo "<b>Date:</b> " . $Date . "&nbsp;&nbsp; ";
                  if ($q['PMTToOrFrom'] != 2) {
                    echo "<b>Via: </b>" . $q['ModeOfPMT'];
                  }

                  echo  "<br>" . $q['Description'] . " <br>";
                  ?>

                  <div class="text">

                    <?php if ($q['PMTToOrFrom'] != 2) {
                      echo "<a style='color: black !important; text-decoration: none !important;' href='serverCalls/DowloadPBIslips.php?ID=" . $q['ID'] . "target='_blank' rel='noopener noreferrer'>Download Slip</a>&nbsp; &nbsp;";
                    }
                    ?>

                    <a style="color: black !important; text-decoration: none !important;" href=<?php if ($q['PMTToOrFrom'] == 0) {
                                                                                                  echo "'EditBlueSlipPage.php?ID=" . $q['ID'] . "' target='_blank' rel='noopener noreferrer'>Edit Blue Slip";
                                                                                                } else if ($q['PMTToOrFrom'] == 1) {
                                                                                                  echo "'EditPinkSlipPage.php?ID=" . $q['ID'] . "' target='_blank' rel='noopener noreferrer'>Edit Pink Slip";
                                                                                                } else {
                                                                                                  echo "'EditInvoicePage.php?ID=" . $q['ID'] . "' target='_blank' rel='noopener noreferrer'>Edit Invoice";
                                                                                                } ?> </a>
                  </div>
                </div>
              </div>

            </div>

          <?php
            $numOfPmts++;
          } ?>

        </div>


        <div id="GreenSlip" class="accordion" aria-labelledby="headingGreenslip">
          <?php
          $sql = "SELECT * FROM ledger_accounts_transfers WHERE FileNumberFrom = '$FileNumber' OR FileNumberTo ='$FileNumber' ORDER BY Date DESC";

          $data = $con->query($sql);

          $tfrs = array();

          //checks whether there is a row in the database
          if ($data->num_rows > 0) {
            // output data of each row
            while ($row = $data->fetch_assoc()) {
              $tfrs[] = $row; // adds the row from the database into the array
            }
          } else {
            echo "<h4>No Greenslips for this matter</h4>";
          }
          ?>
          <?php
          $heading = 'heading';
          $collapse = 'collapse';
          $transfers = 'transfers';
          $numOfTfrs = 0;
          foreach ($tfrs as $q) { ?>
            <div class="accordion-item" style="background-color: <?php echo $GreenCol; ?> !important;">
              <h2 class="accordion-header" <?php echo 'id= "' . $heading . $transfers . $numOfTfrs . '"'; ?>>
                <button class="accordion-button collapsed " style="background-color: <?php echo $GreenCol; ?> !important;" type="button" data-bs-toggle="collapse" aria-expanded="false" <?php echo 'data-bs-target= "#' . $collapse . $transfers . $numOfTfrs . '"  aria-controls= "#' . $collapse . $transfers . $numOfTfrs . '" '; ?>>
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

                  echo "From " . $q['FileNumberFrom'] . " to " . $q['FileNumberTo'] . " £" . number_format(round($q['Amount'], 2), 2) . " on " . $Date2;

                  ?>
                </button>
              </h2>
              <div <?php echo ' id = "' . $collapse . $transfers . $numOfTfrs . '"  class="accordion-collapse collapse"  aria-labelledby= "' . $heading . $transfers . $numOfTfrs . '"'; ?> data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php
                  echo "<b>Date:</b> " . $Date2 . "&nbsp;&nbsp; <br>";
                  echo  $q['Description'] . "<br>";

                  ?>
                  <a style='color: black !important; text-decoration: none !important;' href="serverCalls/DownloadGreenSlip.php?ID=<?php echo $q['ID']; ?>" target="_blank" rel="noopener noreferrer">Download Slip</a>
                  &nbsp; &nbsp;
                  <a style='color: black !important; text-decoration: none !important;' href="EditGreenSlip.php?ID=<?php echo $q['ID']; ?>" target="_blank" rel="noopener noreferrer">Edit Slip</a>

                </div>
              </div>
            </div>

          <?php
            $numOfTfrs++;
          } ?>

        </div>

        <div id="TempSlip" class="accordion" aria-labelledby="headingTempslip">
          <?php
          $sql = "SELECT * FROM temp_slips WHERE FileNumber= '$FileNumber'  ORDER BY Date DESC";

          $data = $con->query($sql);

          $tempSlips = array();

          //checks whether there is a row in the database
          if ($data->num_rows > 0) {
            // output data of each row
            while ($row = $data->fetch_assoc()) {
              $tempSlips[] = $row; // adds the row from the database into the array
            }
          } else {
            echo "<h4>No Temprorary slips for this matter</h4>";
          }
          ?>
          <?php
          $heading = 'heading';
          $collapse = 'collapse';
          $temp = 'temp';
          $numOfTfrs = 0;
          foreach ($tempSlips as $q) { ?>
            <div class="accordion-item" style="background-color: <?php echo $TempCol; ?> !important;">
              <h2 class="accordion-header" <?php echo 'id= "' . $heading . $temp . $numOfTfrs . '"'; ?>>
                <button class="accordion-button collapsed " style="background-color: <?php echo $TempCol; ?> !important;" type="button" data-bs-toggle="collapse" aria-expanded="false" <?php echo 'data-bs-target= "#' . $collapse . $temp . $numOfTfrs . '"  aria-controls= "#' . $collapse . $temp . $numOfTfrs . '" '; ?>>
                  <?php
                  $timestamp = strtotime($q['Date']);
                  $Date2 = date("d-m-Y", $timestamp);
                  echo  $q['Description'] . '&nbsp;&nbsp;<b>(' . number_format(round($q['Amount'], 2), 2) . ')&nbsp;&nbsp;</b>  ' . $Date2;

                  ?>
                </button>
              </h2>
              <div <?php echo ' id = "' . $collapse . $temp . $numOfTfrs . '"  class="accordion-collapse collapse"  aria-labelledby= "' . $heading . $temp . $numOfTfrs . '"'; ?> data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <?php
                  echo "<b>Date:</b> " . $Date2 . "&nbsp;&nbsp; <br>";
                  echo  $q['Description'] . "<br>";

                  ?>

                </div>
              </div>
            </div>

          <?php
            $numOfTfrs++;
          } ?>

        </div>
      </div>
      <div class="col-6">
        <div>
          <table class="table">
            <thead>
              <tr>
                <th>
                  Monies In
                </th>
                <th>
                  Monies Out
                </th>
                <th>
                  Net Balance
                </th>
              </tr>
            </thead>
            <tbody>
              <tr class="table-light">
                <td>
                  <?php echo "£" . number_format(round($TotalMoniesIn, 2), 2); ?>
                </td>
                <td>
                  <?php echo "£" . number_format(round($TotalMoniesOut + ($TotalAmountOfAllInvoices + ($TotalAmountOfAllInvoices * 0.2)), 2), 2); ?>
                </td>

                <?php $Balance = number_format(round($TotalMoniesIn - ($TotalMoniesOut + ($TotalAmountOfAllInvoices + ($TotalAmountOfAllInvoices * 0.2))), 2), 2);
                if ($Balance < 0) {
                  echo "<td class='text-danger'>£" . $Balance;
                } else {
                  echo "<td class='text-success'>£" . $Balance;
                }
                ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div>
          <legend>Cash Allocation</legend>
          <table class="table">
            <thead>
              <tr>
                <th>Invoice No.</th>
                <th>Amount to allocate</th>
                <th>Click to Allocate</th>
              </tr>
            </thead>
            <tbody>

              <?php
              $invoiceNumbersOptions = "";
              foreach ($invoices as $q) {
                if ($q['State'] == 'Final' && $q['Settled'] == 0) {
                  $InvNum = $q['InvoiceNumber'];
                  $invoiceNumbersOptions .= $invoiceNumbersOptions . "<option>" . $InvNum . "</option>";
                }
              }

              foreach ($pmts as $b) {

                if ($b['PMTToOrFrom'] == '0' && $b['BalanceLeft'] > 0) {
                  $timestamp = strtotime($b['Date']);
                  $Date = date("d-m-Y", $timestamp);
                  if ($b['LedgerAccount'] == 'O') {
                    $LedgerAcc = 'Office Account Ledger';
                  } else {
                    $LedgerAcc = 'Client Account Ledger';
                  }


              ?>
                  <tr class="table-light">
                    <form action="serverCalls/AllocateCash.php" method="get">
                      <td><select class="form-control w-100" name='InvNum'>
                          <?php echo $invoiceNumbersOptions; ?>
                        </select></td>
                      <td><?php
                          echo "Balance of £<input name='AmtToAllocate' class='d-inline w-25 form-control ' value='" . number_format(round($b['BalanceLeft'], 2), 2) . "'></input> from Payment from&nbsp; <b>" . $b['PMTPerson'] . "</b>&nbsp;of £" . number_format(round($b['Amount'], 2), 2) . " to " . $LedgerAcc . " on " . $Date;

                          ?></td>
                      <td><button class="btn btn-primary" name="slipId" value="<?php echo $b['ID']; ?>" type="submit">Allocate</button></td>
                    </form>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
        <div class="add-buttons-finance-page">

          <div class="row">
            <button class="col btn m-1" style="background-color: <?php echo $InvCol; ?> !important;" type="button" data-bs-toggle="collapse" data-bs-target="#invoiceForm" aria-expanded="true" aria-controls="FileSummary">
              Add Invoice
            </button>
            <button class="col btn m-1" style="background-color: pink !important;" type="button" data-bs-toggle="collapse" data-bs-target="#pinkSlipForm" aria-expanded="true" aria-controls="FileSummary">
              Add Pink Slip
            </button>
            <button class="col btn m-1" style="background-color: lightblue !important;" type="button" data-bs-toggle="collapse" data-bs-target="#blueSlipForm" aria-expanded="true" aria-controls="FileSummary">
              Add Blue Slip
            </button>
            <button class="col btn m-1" style="background-color: <?php echo $GreenCol; ?> !important;" type="button" data-bs-toggle="collapse" data-bs-target="#GreenSlipForm" aria-expanded="true" aria-controls="FileSummary">
              Add Green Slip
            </button>
            <button class="col btn m-1" style="background-color: <?php echo $TempCol; ?> !important;" type="button" data-bs-toggle="collapse" data-bs-target="#TempSlipForm" aria-expanded="true" aria-controls="FileSummary">
              Add Temp Slip
            </button>
          </div>

        </div>

        <div>
          <div class="collapse collapse-vertical" id="invoiceForm" aria-expanded="true">
            <div class="card card-body shadow-sm p-3 mt-3 mb-2 bg-body rounded" style="background-color: <?php echo $InvCol; ?> !important; width:100%;">
              <h4 id="FileSummary">Add Invoice</h4>
              <form class="row g-3" action="serverCalls/AddInvoice.php" method="get" id="InvoiceForm">



                <div class="col-md-9">
                  <label class="form-label">File Number</label>
                  <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumber" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
                </div>

                <div class="col-md-3">
                  <label class="form-label" for="">Draft/Final</label>
                  <select name="State" class="form-select">
                    <option selected>Draft</option>
                    <option>Final</option>
                  </select>
                </div>


                <div class="col-md-3">
                  <label class="form-label">Payable by</label>
                  <input required type="text" class="form-control" name="PayableBy" placeholder="eg. Client" value="Client">
                </div>

                <div class="col-md-3">
                  <label class="form-label">Date</label>
                  <input required type="Date" class="form-control" name="Date">
                </div>
                <div class='col-md-3'>
                  <label class="mt-4 form-label">By email</label>
                  <input class="mt-4 form-check-input" type="checkbox" name="ByEmail">
                </div>
                <div class='col-md-3'>
                  <label class="mt-4 form-label">By post</label>
                  <input class="mt-4 form-check-input" type="checkbox" name="ByPost">
                </div>
                <div class="col-md-12">
                  <label class="form-label">Description</label>
                  <textarea required type="text" class="form-control" name="Description">Our professional charges in relation to work undertaken on your behalf as per attached schedule of work and costs.</textarea>
                </div>

                <div class="col-md-12" id="our_costs_row">
                  <div class="row">
                    <div class="col-md-5">
                      <label class="form-label">Our Costs </label>
                    </div>
                    <div class="col-md-5">
                      <label class="form-label">Amount excl. VAT</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-5">
                      <input type="text" class="form-control" id="our_costs_description" placeholder="Costs Description" name="our_costs_desc[]" value="Our Costs">
                    </div>
                    <div class="col-md-5">
                      <input required type="Currency" class="form-control" name="our_costs[]" id="our_costs" placeholder="£0.00">
                    </div>
                    <div class="col-md-2">
                      <span type='button' class='btn btn-primary' onclick="addFields();" id="add_more_fields">+</span>
                    </div>
                  </div>
                </div>



                <?php
                $pmts = array_reverse($pmts);

                if ($pmts != null) {
                  echo '<div class="col-md-12"> 
                        <legend>Pink slips to be attached</legend>';
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

                    if ($q['AmountInvoiced']  == '{}') {
                      $PendingAmount = $q['Amount'];
                    } else {
                      $PendingAmount = 0;
                    }


                    if ($PendingAmount > 0) {
                      echo '<div class="form-check">
                                <input class="form-check-input" name="PinkSlipsToAdd[]" type="checkbox" value="' . $q['ID'] . '"">
                                <label class="form-check-label">Payment to&nbsp;<b>' . $q['PMTPerson'] . '</b>&nbsp;of £' . number_format(round($PendingAmount, 2), 2) . ' from ' . $LedgerAcc . ' on ' . $Date . '</label>
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
                  <legend>Blue slips to be attached</legend>';
                }

                foreach ($pmts as $q) { ?>
                  <?php
                  if ($q['PMTToOrFrom'] == 0) {

                    $BalanceLeft = $q['BalanceLeft'];

                    if ($BalanceLeft > 0) {
                      $LedgerAcc = "";
                      if ($q['LedgerAccount'] == 'O') {
                        $LedgerAcc = "Office Account Ledger";
                      } else {
                        $LedgerAcc = "Client Account Ledger";
                      }
                      $timestamp = strtotime($q['Date']);
                      $Date = date("d-m-Y", $timestamp);

                      $Amount = $q['Amount'];
                      echo '<div class="mt-1 form-check">
                                <input class="mt-2 form-check-input" name="BlueSlipsToAdd[]" type="checkbox" value="' . $q['ID'] . '"">
                                <label class="form-check-label">Balance of <b>£' . number_format(round($BalanceLeft, 2), 2) . '</b> from payment by&nbsp;<b>' . $q['PMTPerson'] . '</b>&nbsp;of £' .  number_format(round($Amount, 2), 2) . ' to ' . $LedgerAcc . ' on ' . $Date . '</label>
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
                  $PendingAmount = $q['Amount'];
                  if ($q['FileNumberFrom'] == $FileNumber) {

                    if ($q['AmountInvoicedFrom']  == '{}') {
                      $PendingAmount = $q['Amount'];
                    } else {
                      $PendingAmount = $q['Amount'] - $q['AmountInvoicedFrom'];
                    }
                  } else {
                    $PendingAmount = $q['BalanceLeftTo'];
                  }

                  if ($PendingAmount > 0) {
                    echo '<div class="form-check">
                    <input class="form-check-input" name="GreenSlipsToAdd[]" type="checkbox" value="' . $q['ID'] . '"">
                    <label class="form-check-label">From ' . $q['FileNumberFrom'] . ' to ' . $q['FileNumberTo'] . ' <b>£' . number_format(round($PendingAmount, 2), 2) . '</b> on ' . $Date2 . '</label>
                    
                    </div>';
                  }


                  ?>

                <?php } ?>

                <div class="col-12">
                  <button type="submit" class="mb-2 btn btn-primary">Add Invoice</button>
                </div>

              </form>
            </div>
          </div>
        </div>

        <div>
          <div class="collapse collapse-vertical" id="pinkSlipForm" aria-expanded="true">
            <div class="card card-body shadow-sm p-3 mt-3 mb-2 bg-body rounded" style="background-color: pink !important; width:100%;">
              <h4 id="FileSummary">Add Pink Slip</h4>
              <form class="row g-3" action="serverCalls/AddPBISlips.php" method="get" id="PinkSlipForm">
                <div class="col-md-8">
                  <label class="form-label">File Number</label>
                  <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumber" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
                </div>
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" name="ClientAcc" type="checkbox">
                    <label class="form-check-label">
                      Client Account
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" name="OfficeAcc" type="checkbox">
                    <label class="form-check-label">
                      Office Account
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Payable to</label>
                  <input required type="text" class="form-control" name="PMTPerson" placeholder="eg. HMLR">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Mode Of Payment</label>
                  <select name="ModeOfPmt" class="form-select">
                    <option selected>Bank Transfer</option>
                    <option>Cash</option>
                    <option>Cheque</option>
                    <option>Bank Charges</option>
                    <option>Banker's Draft</option>
                    <option>Money Order</option>
                    <option>Dr/Cr Card</option>
                    <option>Tel. Transfer</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Date</label>
                  <input required type="Date" class="form-control" name="Date">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Amount (incl. VAT)</label>
                  <input required step="any" class="form-control" name="Amount" placeholder="£0.00">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Description</label>
                  <input required type="text" class="form-control" name="Description" placeholder="Details">
                </div>

                <div class="col-12">
                  <button type="submit" class="mb-2 btn btn-primary" name="TypeOfSlip" value="1">Add Pink Slip</button>
                </div>
              </form>
            </div>
          </div>

        </div>
        <div>
          <div class="collapse collapse-vertical" id="blueSlipForm" aria-expanded="true">
            <div class="card card-body shadow-sm p-3 mt-3 mb-2 bg-body rounded" style="background-color: lightblue !important; width:100%;">
              <h4 id="FileSummary">Add Blue Slip</h4>
              <form class="row g-3" action="serverCalls/AddPBISlips.php" method="get" id="BlueSlipForm">
                <div class="col-md-8">
                  <label class="form-label">File Number</label>
                  <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumber" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
                </div>
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" name="ClientAcc" type="checkbox">
                    <label class="form-check-label">
                      Client Account
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" name="OfficeAcc" type="checkbox">
                    <label class="form-check-label">
                      Office Account
                    </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Received From</label>
                  <input required type="text" class="form-control" name="PMTPerson" placeholder="eg. Client">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Mode Of Payment</label>
                  <select name="ModeOfPmt" class="form-select">
                    <option selected>Bank Transfer</option>
                    <option>Cash</option>
                    <option>Cheque</option>
                    <option>Bank Charges</option>
                    <option>Banker's Draft</option>
                    <option>Money Order</option>
                    <option>Dr/Cr Card</option>
                    <option>Tel. Transfer</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Date</label>
                  <input required type="Date" class="form-control" name="Date">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Amount (incl. VAT)</label>
                  <input required type="number" step="any" class="form-control" name="Amount" placeholder="£0.00">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Description</label>
                  <input required type="text" class="form-control" name="Description" placeholder="eg. Monies On Account">
                </div>

                <div class="col-12">
                  <button type="submit" class="mb-2 btn btn-primary" name="TypeOfSlip" value="0">Add Blue Slip</button>
                </div>
              </form>
            </div>
          </div>

          <div class="collapse collapse-vertical" id="GreenSlipForm" aria-expanded="true">
            <div class="card card-body shadow-sm p-3 mt-3 mb-2 bg-body rounded" style="background-color: <?php echo $GreenCol; ?> !important; width:100%;">
              <h4 id="FileSummary">Add Green Slip</h4>
              <form class="row g-3" action="serverCalls/AddGreenSlip.php" method="post" id="GreenSlipForm">
                <div class="col-md-6">
                  <label class="form-label">File Number From</label>
                  <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumberFrom" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label">File Number To</label>
                  <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumberTo" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
                </div>
                <div class="col-md-6">
                  <fieldset>
                    <h5>From Account</h5>
                    <div class="form-check">
                      <input class="form-check-input" name="FromClientAcc" type="checkbox">
                      <label class="form-check-label">
                        Client Account
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="FromOfficeAcc" type="checkbox">
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
                      <input class="form-check-input" name="ToClientAcc" type="checkbox">
                      <label class="form-check-label">
                        Client Account
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="ToOfficeAcc" type="checkbox">
                      <label class="form-check-label">
                        Office Account
                      </label>
                    </div>
                  </fieldset>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Date</label>
                  <input required type="Date" class="form-control" name="Date">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Amount (incl. VAT)</label>
                  <input required type="number" step="any" class="form-control" name="Amount" placeholder="£0.00">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Description</label>
                  <input required type="text" class="form-control" name="Description" placeholder="Details">
                </div>

                <div class="col-12">
                  <button type="submit" class="mb-2 btn btn-primary">Add Green Slip</button>
                </div>
              </form>
            </div>
          </div>


          <div class="collapse collapse-vertical" id="TempSlipForm" aria-expanded="true">
            <div class="card card-body shadow-sm p-3 mt-3 mb-2 bg-body rounded" style="background-color: <?php echo $TempCol; ?> !important; width:100%;">
              <h4 id="FileSummary">Add Temporary Slip</h4>
              <form class="row g-3" action="serverCalls/AddTempSlip.php" method="post" id="TempSlipForm">
                <div class="col-md-6">
                  <label class="form-label">File Number</label>
                  <input required type="text" class="form-control" pattern="[A-Z]{3}[0-9]{7}" name="FileNumber" value="<?php echo $FileNumber; ?>" placeholder="<?php echo $FileNumber; ?>">
                </div>


                <div class="col-md-4">
                  <label class="form-label">Date</label>
                  <input required type="Date" class="form-control" name="Date">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Amount</label>
                  <input required type="number" step="any" class="form-control" name="Amount" placeholder="£0.00">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Description</label>
                  <textarea required type="text" class="form-control" name="Description" placeholder="Detail(s) of slip(s) included in this entry"></textarea>
                </div>

                <div class="col-12">
                  <button type="submit" class="mb-2 btn btn-primary">Add Temporary Slip</button>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>

  <?php
  writeFooter();
  ?>
</body>

</html>
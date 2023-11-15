<?php
include '../Connect.php';
$SlipId = mysqli_real_escape_string($con, $_REQUEST['slipId']);
$AmtToAllocate = filter_input(INPUT_GET, 'AmtToAllocate', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$InvNum = mysqli_real_escape_string($con, $_REQUEST['InvNum']);

$sql = "SELECT * FROM invoices WHERE InvoiceNumber = '$InvNum'";
$data = mysqli_query($con, $sql);
$q = $data->fetch_assoc();





$timestamp = strtotime($q['Date']);
$Date = date("d-m-Y", $timestamp);
$TotalCosts = 0;
$OurCosts = json_decode($q['OurCosts']);
$OurCostsDesc = json_decode($q['OurCostsDesc']);
$PinkSlipIDs = json_decode($q['DisbsIDs']);
$BlueSlipsIDs = json_decode($q['MOA_IDs']);
$GreenSlipIDs = json_decode($q['GreenSlip_IDs']);
$AllocatedIDs = json_decode($q['CashAllocatedSlips']);

$TotalCosts = array_sum($OurCosts);

$VAT = round($TotalCosts, 2) * 0.2;

$TotalCostAndVAT = $VAT + $TotalCosts;

$TotalPinkSlips = 0;
if ($PinkSlipIDs != null) {


    foreach ($PinkSlipIDs as $ID) {
        $sql = "SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";
        $result = mysqli_query($con, $sql);
        $slip = mysqli_fetch_assoc($result);

        $TotalPinkSlips = $TotalPinkSlips + $slip['Amount'];
    }
}
$TotalBlueSlips = 0;
if ($BlueSlipsIDs != null) {;

    foreach ($BlueSlipsIDs as $ID) {
        $sql = "SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";
        $result = mysqli_query($con, $sql);
        $slip = mysqli_fetch_assoc($result);



        if ($slip['AmountInvoiced'] != null) {

            $obj = json_decode($slip['AmountInvoiced']);

            $InvoiceID = $q['ID'];

            $InvoiceData = json_decode($obj->{"$InvoiceID"});


            $AmountInvoiced =  $InvoiceData->{"Amount"};
        }
        $TotalBlueSlips = $TotalBlueSlips + $AmountInvoiced;
    }
}

$TotalAllocated = 0;
if ($AllocatedIDs != null) {;

    foreach ($AllocatedIDs as $ID) {
        $sql = "SELECT * FROM pmts_slip WHERE ID = '$ID' LIMIT 1";
        $result = mysqli_query($con, $sql);
        $slip = mysqli_fetch_assoc($result);

        if ($slip['AmountAllocated'] != null) {

            $obj = json_decode($slip['AmountAllocated']);

            $InvoiceID = $q['ID'];

            $InvoiceData = json_decode($obj->{"$InvoiceID"});


            $AmountInvoiced =  $InvoiceData->{"Amount"};
        }
        $TotalAllocated += $AmountInvoiced;
    }
}
$TotalGreenSlips = 0;
if ($GreenSlipIDs != null) {

    foreach ($GreenSlipIDs as $GSID) {
        $sql = "SELECT * FROM ledger_accounts_transfers WHERE ID = '$GSID' LIMIT 1";
        $result = mysqli_query($con, $sql);
        $slip = mysqli_fetch_assoc($result);


        $InvoiceID = $q['ID'];

        $AmtInvoicedFrom = $slip['AmountInvoicedFrom'];

        $obj = json_decode($slip['AmountInvoicedTo']);

        $AmtInvoicedTo = $obj->{"$InvoiceID"};
        if ($slip['FileNumberFrom'] == $FileNumber) {
            $TotalGreenSlips = $TotalGreenSlips - $slip['Amount'];
        }
        if ($slip['FileNumberTo'] == $FileNumber) {
            $TotalGreenSlips = $TotalGreenSlips + $AmtInvoicedTo;
        }
    }
}

$Balance = (($TotalCostAndVAT + $TotalPinkSlips) - ($TotalBlueSlips + $TotalAllocated + $TotalGreenSlips));

$BalanceLater = $Balance - $AmtToAllocate;
$BalanceCleared = false;
if ($BalanceLater <= 0) {
    $BalanceLeft = $AmtToAllocate - $Balance;
    $BalanceCleared = true;
} else {
    $BalanceLeft = 0;
}

$arr = array('Amount' => $AmtToAllocate);
$amtAllocated = json_encode($arr);
$InvoiceID = $q['ID'];
$sql = "UPDATE pmts_slip SET AmountAllocated=JSON_SET(AmountAllocated,'$.$InvoiceID','$amtAllocated'), BalanceLeft=$BalanceLeft WHERE ID='$SlipId'";
mysqli_query($con, $sql);


if ($AllocatedIDs == null) {
    $AllocatedIDs = [$SlipId];
} else {
    array_push($AllocatedIDs, $SlipId);
}

$jsonAllocatedIDs = json_encode($AllocatedIDs);



if ($BalanceCleared) {
    $sql = "UPDATE invoices SET Settled = '1', CashAllocatedSlips='$jsonAllocatedIDs' WHERE ID='$InvoiceID'";
} else {
    $sql = "UPDATE invoices SET CashAllocatedSlips='$jsonAllocatedIDs' WHERE ID='$InvoiceID'";
}
mysqli_query($con, $sql);


$FileNumber = $q['FileNumber'];

header("Location: ../Finances.php?FileNumber=$FileNumber");
exit();

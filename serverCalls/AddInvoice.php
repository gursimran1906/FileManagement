<?php



include '../Connect.php';

$FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);

$Date = mysqli_real_escape_string($con, $_REQUEST['Date']);
$PayableBy = mysqli_real_escape_string($con, $_REQUEST['PayableBy']);
$ByEmail = isset($_REQUEST['ByEmail']) ? 1 : 0;
$ByPost = isset($_REQUEST['ByPost']) ? 1 : 0;

$OurCostsDesc = json_encode(array_filter($_REQUEST['our_costs_desc']));
$OurCosts = json_encode(array_filter($_REQUEST['our_costs']));
$Description = mysqli_real_escape_string($con, $_REQUEST['Description']);

$State = mysqli_real_escape_string($con, $_REQUEST['State']);




// just need to check how many blue slips (include date of balance last used)
// for pink slips will just leave the slips out by a negative number


if (isset($_REQUEST['PinkSlipsToAdd'])) {
    $PinkSlips = json_encode($_REQUEST['PinkSlipsToAdd']);
} else {
    $PinkSlips = json_encode('');
}


if (isset($_REQUEST['BlueSlipsToAdd'])) {
    $BlueSlips = json_encode($_REQUEST['BlueSlipsToAdd']);
} else {
    $BlueSlips = json_encode('');
}

if (isset($_REQUEST['GreenSlipsToAdd'])) {
    $GreenSlips = json_encode($_REQUEST['GreenSlipsToAdd']);
} else {
    $GreenSlips = json_encode('');
}

$sql = "INSERT INTO invoices(State, FileNumber, Date, PayableBy,ByEmail, ByPost, Description, OurCostsDesc, OurCosts, DisbsIDs, MOA_IDs, GreenSlip_IDs) 
    VALUES ('$State','$FileNumber','$Date','$PayableBy','$ByEmail', '$ByPost','$Description','$OurCostsDesc','$OurCosts','$PinkSlips','$BlueSlips','$GreenSlips')";
mysqli_query($con, $sql);

$InvoiceID = mysqli_insert_id($con);

if ($State == 'Final') {
    $sql = "SELECT InvoiceNumber FROM invoices ORDER BY InvoiceNumber DESC LIMIT 1";
    $result = mysqli_query($con, $sql);
    $LastInvoiceNumber = mysqli_fetch_assoc($result);
    $LastInvoiceNumber = $LastInvoiceNumber['InvoiceNumber'];
    print($LastInvoiceNumber);

    if ($LastInvoiceNumber != null) {
        $InvNumber = $LastInvoiceNumber + 1;
        $sql = "UPDATE invoices SET InvoiceNumber = '$InvNumber' WHERE ID='$InvoiceID'";
        mysqli_query($con, $sql);
    }
}



$OurCostsSum = array_sum($_REQUEST['our_costs']);
$OurCostsSum = $OurCostsSum + ($OurCostsSum * 0.2);
$DisbSum = 0;

if (isset($_REQUEST['PinkSlipsToAdd'])) {
    $PinkSlips = $_REQUEST['PinkSlipsToAdd'];
    foreach ($PinkSlips as $ID) {
        $sql = "UPDATE pmts_slip SET AmountInvoiced =  Amount, BalanceLeft=0 WHERE ID=$ID";
        mysqli_query($con, $sql);

        $sql = "SELECT Amount FROM pmts_slip WHERE ID=$ID";
        $result = mysqli_query($con, $sql);
        $Data = $result->fetch_assoc();
        $DisbSum = $DisbSum + $Data['Amount'];
    }
}

$TotalCostsDisbs = $OurCostsSum + $DisbSum;

if (isset($_REQUEST['GreenSlipsToAdd'])) {
    $GreenSlips = $_REQUEST['GreenSlipsToAdd'];

    for ($i = 0; $i < count($GreenSlips); $i++) {
        $ID = $GreenSlips[$i];
        $sql = "SELECT * FROM ledger_accounts_transfers WHERE ID=$ID";
        $result = mysqli_query($con, $sql);
        $data = $result->fetch_assoc();
        $FileNumberTo = $data['FileNumberTo'];

        if ($FileNumberTo == $FileNumber && $TotalCostsDisbs > 0) {

            $sql = "SELECT BalanceLeftFrom FROM ledger_accounts_transfers WHERE ID=$ID";
            $result = mysqli_query($con, $sql);
            $data = $result->fetch_assoc();
            $Amount = $data['BalanceLeftFrom'];

            $BeforeCost = $TotalCostsDisbs;

            $TotalCostsDisbs = $TotalCostsDisbs - $Amount;

            $BalanceLeft = 0;

            if ($TotalCostsDisbs <= 0) {
                $BalanceLeft = $Amount - $BeforeCost;
            }

            $arr = array('Amount' => $Amount, 'BalanceLeft' => $BalanceLeft);
            $InvoiceData = json_encode($arr);


            $sql = "UPDATE ledger_account_transfers 
                SET AmountInvoicedTo = JSON_SET(AmountInvoicedTo, '$.$InvoiceID', '$InvoiceData'), 
                    BalanceLeftTo = $BalanceLeft
                WHERE ID = $ID";
            mysqli_query($con, $sql);
        } else if ($FileNumber == $FileNumberTo) {

            $sql = "SELECT BalanceLeftFrom FROM ledger_accounts_transfers WHERE ID=$ID";
            $result = mysqli_query($con, $sql);
            $data = $result->fetch_assoc();
            $Amount = $data['BalanceLeftFrom'];

            $arr = array('Amount' => $Amount, 'BalanceLeft' => $Amount);
            $InvoiceData = json_encode($arr);

            $sql = "UPDATE ledger_account_transfers 
                SET AmountInvoicedTo = JSON_SET(AmountInvoicedTo, '$.$InvoiceID', '$InvoiceData')
                WHERE ID = $ID";
            mysqli_query($con, $sql);
        } else {
            $sql = "UPDATE ledger_account_transfer SET  AmountInvoicedFrom = Amount, BalanceLeftFrom=0 WHERE ID=$ID";
            mysqli_query($con, $sql);
            $TotalCostsDisbs += $data['Amount'];
        }
    }
}

if (isset($_REQUEST['BlueSlipsToAdd'])) {
    $BlueSlips = $_REQUEST['BlueSlipsToAdd'];

    for ($i = 0; $i < count($BlueSlips); $i++) {
        if ($TotalCostsDisbs > 0) {


            $ID = $BlueSlips[$i];
            $sql = "SELECT BalanceLeft FROM pmts_slip WHERE ID=$ID";
            $result = mysqli_query($con, $sql);
            $data = $result->fetch_assoc();
            $Amount = $data['BalanceLeft'];

            $BeforeCost = $TotalCostsDisbs;

            $TotalCostsDisbs = $TotalCostsDisbs - $Amount;

            $BalanceLeft = 0;

            if ($TotalCostsDisbs <= 0) {
                $BalanceLeft = $Amount - $BeforeCost;
            }

            $arr = array('Amount' => $Amount, 'BalanceLeft' => $BalanceLeft);
            $InvoiceData = json_encode($arr);


            $sql = "UPDATE pmts_slip 
            SET AmountInvoiced = JSON_SET(AmountInvoiced, '$.$InvoiceID', '$InvoiceData'), 
                BalanceLeft = $BalanceLeft
            WHERE ID = $ID";
            mysqli_query($con, $sql);
        }
        if ($TotalCostsDisbs <= 0) {
            for ($j = $i + 1; $j < count($BlueSlips); $j++) {
                $ID = $BlueSlips[$j];
                $sql = "SELECT BalanceLeft FROM pmts_slip WHERE ID=$ID";
                $result = mysqli_query($con, $sql);
                $data = $result->fetch_assoc();
                $Amount = $data['BalanceLeft'];


                $arr = array('Amount' => $Amount, 'BalanceLeft' => $Amount);
                $InvoiceData = json_encode($arr);


                $sql = "UPDATE pmts_slip 
                 SET AmountInvoiced = JSON_SET(AmountInvoiced, '$.$InvoiceID', '$InvoiceData')
                 WHERE ID = $ID";
                mysqli_query($con, $sql);
            }
            $i = count($BlueSlips);
        }
    }
}

if ($TotalCostsDisbs <= 0) {
    $sql = "UPDATE invoices SET Settled = '1' WHERE ID='$InvoiceID'";

    mysqli_query($con, $sql);
}



header("Location: ../Finances.php?FileNumber=$FileNumber");

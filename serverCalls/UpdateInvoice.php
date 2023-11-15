<?php

include '../Connect.php';

$Invoice_ID = mysqli_real_escape_string($con, $_REQUEST['ID']);

$FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);

$Date = mysqli_real_escape_string($con, $_REQUEST['Date']);
$PayableBy = mysqli_real_escape_string($con, $_REQUEST['PayableBy']);
$ByEmail = isset($_REQUEST['ByEmail']) ? 1 : 0;
$ByPost = isset($_REQUEST['ByPost']) ? 1 : 0;

$OurCostsDesc = json_encode(array_filter($_REQUEST['our_costs_desc']));
$OurCosts = json_encode(array_filter($_REQUEST['our_costs']));
$Description = mysqli_real_escape_string($con, $_REQUEST['Description']);

$State = mysqli_real_escape_string($con, $_REQUEST['State']);


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

$sql = "UPDATE invoices SET State = '$State',
 FileNumber = '$FileNumber',
 Date = '$Date',
 PayableBy = '$PayableBy',
ByEmail = '$ByEmail',
 ByPost = '$ByPost',
 Description = '$Description',
 OurCostsDesc='$OurCostsDesc',
 OurCosts  = '$OurCosts',
 DisbsIDs = '$PinkSlips',
 MOA_IDs ='$BlueSlips',
 GreenSlip_IDs= '$GreenSlips'
 WHERE ID=$Invoice_ID";

mysqli_query($con, $sql);

$sql = "SELECT * FROM invoices WHERE ID= '$Invoice_ID' ";
$Invoice = $con->query($sql)->fetch_assoc();

$sql = "UPDATE pmts_slip 
        SET BalanceLeft = IFNULL(JSON_UNQUOTE(JSON_EXTRACT(AmountInvoiced, '$.$Invoice_ID')), BalanceLeft),
            AmountInvoiced = JSON_REMOVE(AmountInvoiced, '$.$Invoice_ID') 
        WHERE FileNumber = '$FileNumber' AND PMTToOrFrom = '1'
        ";
mysqli_query($con, $sql);


if ($State == 'Final' &&  $Invoice['InvoiceNumber'] == 0) {
    $sql = "SELECT InvoiceNumber FROM invoices ORDER BY InvoiceNumber DESC LIMIT 1";
    $result = mysqli_query($con, $sql);
    $LastInvoiceNumber = mysqli_fetch_assoc($result);
    $LastInvoiceNumber = $LastInvoiceNumber['InvoiceNumber'];
    print($LastInvoiceNumber);

    if ($LastInvoiceNumber != null) {
        $InvNumber = $LastInvoiceNumber + 1;
        $sql = "UPDATE invoices SET InvoiceNumber = '$InvNumber' WHERE ID='$Invoice_ID'";
        mysqli_query($con, $sql);
    }
}
$OurCostsSum = array_sum($_REQUEST['our_costs']);
$OurCostsSum = $OurCostsSum + ($OurCostsSum * 0.2);
$DisbSum = 0;

if (isset($_REQUEST['PinkSlipsToAdd'])) {
    $PinkSlips = $_REQUEST['PinkSlipsToAdd'];
    foreach ($PinkSlips as $ID) {
        $sql = "UPDATE pmts_slip SET AmountInvoiced = Amount, BalanceLeft='0' WHERE ID=$ID";
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

        if ($FileNumberTo = $FileNumber && $TotalCostsDisbs > 0) {

            $InvoiceData = json_decode(json_decode($data['AmountInvoicedTo'])->{"$Invoice_ID"}, true);

            $InvAmt = $InvoiceDate['Amount'];
            $InvBalanceLeft = $InvoiceData['BalanceLeft'];

            $Amount = $data['BalanceLeftTo'] + ($InvAmt - $InvBalanceLeft);

            $BeforeCost = $TotalCostsDisbs;

            $TotalCostsDisbs = $TotalCostsDisbs - $Amount;

            $BalanceLeft = 0;

            if ($TotalCostsDisbs <= 0) {
                $BalanceLeft = $Amount - $BeforeCost;
            }

            $arr = array('Amount' => $Amount, 'BalanceLeft' => $BalanceLeft);
            $InvoiceData = json_encode($arr);


            $sql = "UPDATE ledger_account_transfers 
                SET AmountInvoicedTo = JSON_SET(AmountInvoicedTo, '$.$Invoice_ID', '$InvoiceData'), 
                    BalanceLeftTo = $BalanceLeft
                WHERE ID = $ID";
            mysqli_query($con, $sql);
        } else if ($FileNumberTo == $FileNumber) {


            $Amount = $data['BalanceLeftTo'];

            $arr = array('Amount' => $Amount, 'BalanceLeft' => $Amount);
            $InvoiceData = json_encode($arr);

            $sql = "UPDATE ledger_accounts_transfers 
            SET 
            AmountInvoicedTo = JSON_SET(AmountInvoicedTo, '$.$Invoice_ID', '$InvoiceData')
            WHERE ID=$ID";
            mysqli_query($con, $sql);
        } else {
            $TotalCostsDisbs += $data['Amount'];
        }
        // } else {


        //     $sql = "UPDATE ledger_accounts_transfers SET  AmountInvoicedFrom = JSON_SET(Amount, '$.$Invoice_ID', BalanceLeftFrom), BalanceLeftFrom = 0 WHERE ID=$ID";
        //     mysqli_query($con, $sql);
        //     $TotalCostsDisbs += $data['Amount'];
        // }
    }
}
if (isset($_REQUEST['BlueSlipsToAdd'])) {
    $BlueSlips = $_REQUEST['BlueSlipsToAdd'];


    for ($i = 0; $i < count($BlueSlips); $i++) {
        $ID = $BlueSlips[$i];

        $sql = "SELECT AmountInvoiced, BalanceLeft FROM pmts_slip WHERE ID = $ID";
        $result = mysqli_query($con, $sql);
        $data = $result->fetch_assoc();

        $colData = json_decode($data['AmountInvoiced'], true);
        $InvoiceData = json_decode($colData[$Invoice_ID], true);

        $InvAmountInvoiced = $InvoiceData['Amount'];
        $InvBalanceLeft = $InvoiceData['BalanceLeft'];

        $AmountLeft = $data['BalanceLeft'] + ($InvAmountInvoiced - $InvBalanceLeft);

        $BeforeCost = $TotalCostsDisbs;

        $TotalCostsDisbs = $TotalCostsDisbs - $AmountLeft;

        $BalanceLeft = 0;

        if ($TotalCostsDisbs <= 0) {
            $BalanceLeft = $AmountLeft - $BeforeCost;
        }

        $arr = array('Amount' => $AmountLeft, 'BalanceLeft' => $BalanceLeft);
        $InvoiceData = json_encode($arr);


        $sql = "UPDATE pmts_slip 
            SET AmountInvoiced = JSON_SET(AmountInvoiced, '$.$Invoice_ID', '$InvoiceData'), 
                BalanceLeft = $BalanceLeft
            WHERE ID = $ID";
        mysqli_query($con, $sql);
        if ($TotalCostsDisbs <= 0) {
            for ($j = $i + 1; $j < count($BlueSlips); $j++) {
                $ID = $BlueSlips[$j];

                $sql = "SELECT BalanceLeft FROM pmts_slip WHERE ID=$ID";
                $result = mysqli_query($con, $sql);
                $data = $result->fetch_assoc();
                $Amount = $data['BalanceLeft'];


                $arr = array('Amount' => $Amount, 'BalanceLeft' => $Amount);
                $InvoiceData = json_encode($arr);
                print_r($arr);

                echo $TotalCostsDisbs;

                $sql = "UPDATE pmts_slip 
                 SET AmountInvoiced = JSON_SET(AmountInvoiced, '$.$Invoice_ID', '$InvoiceData')
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



 // if ($AmountLeft > 0 && $TotalCostsDisbs > 0) {
        //     $TotalCostsDisbs -= $AmountLeft;
        // }


        // if ($TotalCostsDisbs < 0 && $prevBalanceUsed == 0) {
        //     $BalanceLeft = $AmountLeft - $BeforeCost;
        // } else if ($TotalCostsDisbs < 0) {
        //     $BalanceLeft = ($prevBalanceUsed  + $AmountLeft) - $BeforeCost;
        // } else {
        //     $BalanceLeft = 0;
        // }
        // $AmountInvoiced = $prevBalanceUsed  + $AmountLeft;
        // $sql = "UPDATE pmts_slip 
        // SET
        //  AmountInvoiced=JSON_SET(AmountInvoiced, '$.$Invoice_ID', '$AmountInvoiced'), BalanceLeft = $BalanceLeft 
        //  WHERE
        //   ID=$ID";
        // mysqli_query($con, $sql);
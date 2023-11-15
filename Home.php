<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();

$fileNumber = $_REQUEST['filenumber'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title> <?php echo $fileNumber . " - Home" ?></title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <meta name="author" content="Gursimran Singh">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php writeNavBar(); ?>


    <div class="container">
        <?php



        $sql = "SELECT * FROM wip WHERE FileNumber='$fileNumber'";
        $data = $con->query($sql);
        $matter = $data->fetch_assoc();



        if (isset($matter)) :


            $client1ContactID = $matter['Client1Contact_ID'];
            $client2ContactID = $matter['Client2Contact_ID'];

            // CLIENT 1 INFO 
            $sql = "SELECT * FROM client_contact_details WHERE ID =$client1ContactID";

            $data = $con->query($sql);
            $client1Info = $data->fetch_assoc();


            // CLIENT 2 INFOR
            $sql = "SELECT * FROM client_contact_details WHERE ID =$client2ContactID";

            $data = $con->query($sql);
            $client2Info = $data->fetch_assoc();

            $AP1ContactID = $matter['AuthorisedParty1_ID'];
            $AP2ContactID = $matter['AuthorisedParty2_ID'];

            // AP 1 INFO 
            $sql = "SELECT * FROM authorised_party_contact_details WHERE ID =$AP1ContactID";

            $data = $con->query($sql);
            $AP1Info = $data->fetch_assoc();


            // AP 2 INFOR
            $sql = "SELECT * FROM authorised_party_contact_details WHERE ID =$AP2ContactID";

            $data = $con->query($sql);
            $AP2Info = $data->fetch_assoc();

            // OS Info
            $OS_ID = $matter['OtherSideDetails'];
            $sql = "SELECT * FROM OS_Details WHERE ID =$OS_ID";

            $data = $con->query($sql);
            $OSInfo = $data->fetch_assoc();

        ?>
            <div class="row">

                <div class="col-md-6">
                    <div>
                        <h1>
                            <?php echo $fileNumber; ?>

                            <button id="editButton" class="btn btn-primary" type="submit" onClick="window.open('EditPage.php?FileNumber=<?php echo $fileNumber . "'"; ?>);">Edit Matter</button>
                            <a class="btn btn-primary m-1" href='serverCalls/DownloadFrontsheet.php?FileNumber=<?php echo $fileNumber; ?>'>Frontsheet</a>

                        </h1>
                    </div>

                    <button class="btn btn-primary m-1" type="button" data-bs-toggle="collapse" data-bs-target="#FileSummary" aria-expanded="true" aria-controls="FileSummary">
                        File Summary
                    </button>
                    <button class="btn btn-primary m-1" type="button" data-bs-toggle="collapse" data-bs-target="#Compliance" aria-expanded="false" aria-controls="Compliance">
                        Compliance
                    </button>
                    <button class="btn btn-primary m-1" type="button" data-bs-toggle="collapse" data-bs-target="#LastWorkDone" aria-expanded="false" aria-controls="LastWorkDone">
                        Last Work Done
                    </button>
                    <button class="btn btn-primary m-1" type="button" data-bs-toggle="collapse" data-bs-target="#NextWork" aria-expanded="false" aria-controls="NextWork">
                        Next Work
                    </button>
                    <div class="alert alert-danger shadow-sm p-3 mb-5  rounded" role="alert" id="CommentSection">
                        <p>
                            <?php
                            $timestamp = strtotime($matter['NextKeyDate']);
                            $NextKeyDate = date("d-m-Y", $timestamp);
                            echo "<b>Next Key Date: </b>" . $NextKeyDate . "<br>";
                            echo "<b>Comments: </b>" . $matter['Comments']; ?></p><br>

                        <p>


                            <?php echo "<b>Person For Next Task: </b>" . $matter['PersonForNextTask']; ?> <br>
                            <?php echo "<b>Tasks Required: </b>" . $matter['TasksRequired']; ?><br>
                            <?php
                            $timestamp = strtotime($matter['DateOfNextWork']);
                            $DateOfNxtWork = date("d-m-Y", $timestamp);
                            echo "<b>Date Of Next Work: </b>" . $DateOfNxtWork; ?><br>
                            <?php
                            if ($matter['ActionRequired'] == 1) {
                                $ActionReq = 'Yes';
                            } else {
                                $ActionReq = 'No';
                            }
                            echo "<b>Action Required:</b> " . $ActionReq; ?><br>
                            <br>
                            <b>Key Information: <?php echo $matter['KeyInformation']; ?></b>
                            <br>
                            <br>
                            <b>Undertakings:</b>
                        <ul>
                            <?php
                            $Undertakings = json_decode($matter['Undertakings']);

                            foreach ($Undertakings as $undertak) {
                                if ($undertak != ' ')
                                    echo '<li>' . $undertak . '</li>';
                            }
                            ?>
                        </ul>




                        </p>

                    </div>

                    <button class="btn btn-primary m-1" type="submit" onClick=" window.open('AttendanceNote.php?FileNumber=<?php echo $fileNumber . "'"; ?>  );">Add/View Attendance Notes</button>
                    <button class="btn btn-primary m-1" type="submit" onClick=" window.open('Correspondence.php?FileNumber=<?php echo $fileNumber . "'"; ?>  );">Correspondence</button>
                    <button id="sowdcButton" class="btn btn-primary m-1" type="submit" onClick=" window.open('serverCalls/ScheduleOfWork.php?FileNumber=<?php echo $fileNumber . "'"; ?>  );">Schedule of Work And Costs</button>
                    <?php
                    if ($matter['FileStatus'] != 'Archived') : ?>
                        <button class="m-1 btn btn-primary" type="submit" onClick=" window.open('Finances.php?FileNumber=<?php echo $fileNumber . "'"; ?>  );">Finances</button>
                    <?php endif ?>
                </div>
                <div class="col-md-6">
                    <div>
                        <div class="collapse collapse-horizontal" id="FileSummary" aria-expanded="true">
                            <div class="card card-body shadow-sm p-3 mb-5 bg-body rounded" style="width: 650px;">
                                <h4 id="FileSummary">File Summary</h4>
                                <p>
                                <ul class="list-group">
                                    <li class="list-group-item"><?php echo "<b>File Number: </b> " . $matter['FileNumber']; ?></li>
                                    <li class="list-group-item"><?php echo "<b>FeeEarner: </b>" . $matter['FeeEarner']; ?></li>
                                    <li class="list-group-item"><?php echo "<b>Matter Description: </b>" . $matter['MatterDescription']; ?></li>
                                    <li class="list-group-item"><b>Funding: </b><?php if ($matter['Funding'] == 'PF') {
                                                                                    echo 'Private Funding';
                                                                                } else if ($matter['Funding'] == 'CFA') {
                                                                                    echo 'Conditional Fee Agreement';
                                                                                } ?></li>
                                    <?php
                                    if ($client1Info != null) {
                                        if ($client1Info['ClientName'] != null) {
                                            echo "<li class='list-group-item'> <h5>Client 1</h5><b>Name: </b>" . $client1Info['ClientName'];
                                        }
                                        if ($client1Info['DOB'] != null) {
                                            $timestamp = strtotime($client1Info['DOB']);
                                            $DOB = date("d-m-Y", $timestamp);
                                            echo "<br><b>DOB: </b>" . $DOB;
                                        }
                                        if ($client1Info['AddressLine1'] != null  or $client1Info['AddressLine2'] != null or $client1Info['County'] != null or $client1Info['Postcode'] != null) {
                                            echo "<br><b>Address: </b>" . $client1Info['AddressLine1'] . ", " . $client1Info['AddressLine2'] . ", " . $client1Info['County'] . "," . $client1Info['Postcode'];
                                        }
                                        if ($client1Info['Email'] != null) {
                                            echo "<br><b>Email: </b>" . $client1Info['Email'];
                                        }
                                        if ($client1Info['ContactNumber'] != null) {
                                            echo "<br><b>Contact Number: </b>" . $client1Info['ContactNumber'];
                                        }
                                        if ($client1Info['DateOfLastAML'] != null) {
                                            $timestamp = strtotime($client1Info['DateOfLastAML']);
                                            $DateOfLastAML = date("d-m-Y", $timestamp);
                                            echo "<br><b>Date of Last AML Check: </b>" . $DateOfLastAML;
                                        }
                                        if ($client1Info['IdVerified'] != null) {
                                            $idVerified = "";
                                            if ($client1Info['IdVerified'] == 1) {
                                                $idVerified = 'Yes';
                                            } else {
                                                $idVerified = 'No';
                                            }
                                            echo "<br><b>ID Verified: </b>" . $idVerified . "</li>";
                                        }
                                    }
                                    if ($client2Info != null) {
                                        if ($client2Info['ClientName'] != null) {
                                            echo "<li class='list-group-item'> <h5>Client 2</h5><b>Name: </b>" . $client2Info['ClientName'];
                                        }
                                        if ($client2Info['DOB'] != null) {
                                            $timestamp = strtotime($client2Info['DOB']);
                                            $DOB = date("d-m-Y", $timestamp);
                                            echo "<br><b>DOB: </b>" . $DOB;
                                        }
                                        if ($client2Info['AddressLine1'] != null or $client2Info['AddressLine2'] != null or $client2Info['County'] != null or $client2Info['Postcode'] != null) {
                                            echo "<br><b>Address: </b>" . $client2Info['AddressLine1'] . ", " . $client2Info['AddressLine2'] . ", " . $client2Info['County'] . ", " . $client2Info['Postcode'];
                                        }
                                        if ($client2Info['Email'] != null) {
                                            echo "<br><b>Email: </b>" . $client2Info['Email'];
                                        }
                                        if ($client2Info['ContactNumber'] != null) {
                                            echo "<br><b>Contact Number: </b>" . $client2Info['ContactNumber'];
                                        }
                                        if ($client2Info['DateOfLastAML'] != null) {
                                            $timestamp = strtotime($client2Info['DateOfLastAML']);
                                            $DateOfLastAML = date("d-m-Y", $timestamp);
                                            echo "<br><b>Date of Last AML Check: </b>" . $DateOfLastAML;
                                        }
                                        if ($client2Info['IdVerified'] != null) {
                                            $idVerified = "";
                                            if ($client2Info['IdVerified'] == 1) {
                                                $idVerified = 'Yes';
                                            } else {
                                                $idVerified = 'No';
                                            }
                                            echo "<br><b>ID Verified: </b>" . $idVerified . "</li>";
                                        }
                                    }

                                    if ($AP1Info != null) {
                                        if ($AP1Info['Name'] != null) {
                                            echo "<li class='list-group-item'> <h5>Authorised Party 1</h5><b>Name: </b>" . $AP1Info['Name'];
                                        }
                                        if ($AP1Info['RelationshipToClient'] != null) {
                                            echo "<br><b>Relationship with Client: </b>" . $AP1Info['RelationshipToClient'];
                                        }
                                        if ($AP1Info['AddressLine1'] != null  or $AP1Info['AddressLine2'] != null or $AP1Info['County'] != null or $AP1Info['Postcode'] != null) {
                                            echo "<br><b>Address: </b>" . $AP1Info['AddressLine1'] . ", " . $AP1Info['AddressLine2'] . ", " . $AP1Info['County'] . "," . $AP1Info['Postcode'];
                                        }
                                        if ($AP1Info['Email'] != null) {
                                            echo "<br><b>Email: </b>" . $AP1Info['Email'];
                                        }

                                        if ($AP1Info['ContactNumber'] != null) {
                                            echo "<br><b>Contact Number: </b>" . $AP1Info['ContactNumber'];
                                        }
                                        if ($AP1Info['IDCheck'] != null) {
                                            echo "<br><b>ID Check: </b>";

                                            if ($AP1Info['IDCheck'] == 1) {
                                                echo 'Yes';
                                            } else {
                                                echo 'No';
                                            }
                                        }
                                        if ($AP1Info['DateOfIDCheck'] != null) {
                                            $timestamp = strtotime($AP1Info['DateOfIDCheck']);
                                            $DateOfIDCheck = date("d-m-Y", $timestamp);
                                            echo "<br><b>Date of ID Check: </b>" . $DateOfIDCheck . "</li>";
                                        }
                                    }
                                    if ($AP2Info != null) {
                                        if ($AP2Info['Name'] != null) {
                                            echo "<li class='list-group-item'> <h5>Authorised Party 2</h5><b>Name: </b>" . $AP2Info['Name'];
                                        }
                                        if ($AP2Info['RelationshipToClient'] != null) {
                                            echo "<br><b>Relationship with Client: </b>" . $AP2Info['RelationshipToClient'];
                                        }
                                        if ($AP2Info['AddressLine1'] != null or $AP2Info['AddressLine2'] != null or $AP2Info['County'] != null or $AP2Info['Postcode'] != null) {
                                            echo "<br><b>Address: </b>" . $AP2Info['AddressLine1'] . ", " . $AP2Info['AddressLine2'] . ", " . $AP2Info['County'] . ", " . $AP2Info['Postcode'];
                                        }
                                        if ($AP2Info['Email'] != null) {
                                            echo "<br><b>Email: </b>" . $AP2Info['Email'];
                                        }
                                        if ($AP2Info['ContactNumber'] != null) {
                                            echo "<br><b>Contact Number: </b>" . $AP2Info['ContactNumber'];
                                        }
                                        if ($AP2Info['IDCheck'] != null) {
                                            echo "<br><b>ID Check: </b>";

                                            if ($AP2Info['IDCheck'] == 1) {
                                                echo 'Yes';
                                            } else {
                                                echo 'No';
                                            }
                                        }
                                        if ($AP2Info['DateOfIDCheck'] != null) {
                                            $timestamp = strtotime($AP2Info['DateOfIDCheck']);
                                            $DateOfIDCheck = date("d-m-Y", $timestamp);
                                            echo "<br><b>Date of ID Check: </b>" . $DateOfIDCheck . "</li>";
                                        }
                                    }

                                    if ($OSInfo != null) {
                                        if ($OSInfo['Name'] != null) {
                                            echo "<li class='list-group-item'> <h5>Other Side</h5><b>Name: </b>" . $OSInfo['Name'];
                                        }

                                        if ($OSInfo['AddressLine1'] != null or $OSInfo['AddressLine2'] != null or $OSInfo['County'] != null or $OSInfo['Postcode'] != null) {
                                            echo "<br><b>Address: </b>" . $OSInfo['AddressLine1'] . ", " . $OSInfo['AddressLine2'] . ", " . $OSInfo['County'] . ", " . $OSInfo['Postcode'];
                                        }
                                        if ($AP2Info['Email'] != null) {
                                            echo "<br><b>Email: </b>" . $OSInfo['Email'];
                                        }
                                        if ($AP2Info['ContactNumber'] != null) {
                                            echo "<br><b>Contact Number: </b>" . $OSInfo['ContactNumber'];
                                        }
                                        if ($AP2Info['Solicitors'] != null) {
                                            echo "<br><b>Solicitors: </b>" . $OSInfo['Solicitors'];
                                        }
                                        if ($AP2Info['SolicitorsEmail'] != null) {
                                            echo "<br><b>Solicitors Email: </b>" . $OSInfo['SolicitorsEmail'];
                                        }
                                    }



                                    ?>
                                    <li class="list-group-item"><?php echo "<b>Matter Type: </b>" . $matter['MatterType']; ?></li>
                                    <li class="list-group-item"><?php echo "<b>File Status: </b>" . $matter['FileStatus']; ?></li>
                                    <li class="list-group-item"><?php echo "<b>File Location: </b>" . $matter['FileLocation']; ?></li>
                                </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="collapse collapse-horizontal" id="Compliance">
                            <div class="card card-body shadow-sm p-3 mb-5 bg-body rounded" style="width: 650px;">
                                <h4 id="Compliance">Compliance</h4>
                                <p>
                                <ul class="list-group">
                                    <li class="list-group-item"><?php
                                                                if ($matter['ClientCareLetter'] == 1) {
                                                                    $termOfEngCli1 = 'Yes';
                                                                } else {
                                                                    $termOfEngCli1 = 'No';
                                                                }
                                                                $timestamp = strtotime($matter['DateOfClientCareSent']);
                                                                $DateOfCCSent = date("d-m-Y", $timestamp);
                                                                echo "<b>Client Care Letter: </b>" . $termOfEngCli1 . ", <b>Sent on: </b>" . $DateOfCCSent; ?></li>
                                    <li class="list-group-item"><?php $timestamp = strtotime($matter['DateOfToESent']);
                                                                $DateOfToESent = date("d-m-Y", $timestamp);
                                                                echo "<b>Date Terms Of Engagement Sent: </b>" . $DateOfToESent; ?></li>
                                    <li class="list-group-item"><?php $timestamp = strtotime($matter['DateOfToERcvd']);
                                                                $DateOfToERcvd = date("d-m-Y", $timestamp);
                                                                echo "<b>Date Terms Of Engagement Received: </b>" . $DateOfToERcvd; ?></li>
                                    <li class="list-group-item"><?php
                                                                if ($matter['TermsOfEngagementClient1'] == 1) {
                                                                    $termOfEngCli1 = 'Yes';
                                                                } else {
                                                                    $termOfEngCli1 = 'No';
                                                                }
                                                                echo "<b>Terms of Engagement Client 1: </b>" . $termOfEngCli1; ?></li>
                                    <li class="list-group-item"><?php
                                                                if ($matter['TermsOfEngagementClient2'] == 1) {
                                                                    $termOfEngCli2 = 'Yes';
                                                                } else {
                                                                    $termOfEngCli2 = 'No';
                                                                }
                                                                echo "<b>Terms of Engagement Client 2: </b>" . $termOfEngCli2; ?></li>
                                    <li class="list-group-item"><?php $timestamp = strtotime($matter['DateOfNCBASent']);
                                                                $DateOfNCBASent = date("d-m-Y", $timestamp);
                                                                echo "<b>Date NCBA Sent: </b>" . $DateOfNCBASent; ?></li>
                                    <li class="list-group-item"><?php $timestamp = strtotime($matter['DateOfNCBARcvd']);
                                                                $DateOfNCBAERcvd = date("d-m-Y", $timestamp);
                                                                echo "<b>Date NCBA Received: </b>" . $DateOfNCBARcvd; ?></li>
                                    <li class="list-group-item"><?php
                                                                if ($matter['NCBAClient1'] == 1) {
                                                                    $NCBACli1 = 'Yes';
                                                                } else {
                                                                    $NCBACli1 = 'No';
                                                                }
                                                                echo "<b>NCBA Client 1: </b>" . $NCBACli1; ?></li>
                                    <li class="list-group-item"><?php
                                                                if ($matter['NCBAClient2'] == 1) {
                                                                    $NCBACli2 = 'Yes';
                                                                } else {
                                                                    $NCBACli2 = 'No';
                                                                }
                                                                echo "<b>NCBA Client 2: </b>" . $NCBACli2; ?></li>
                                    <!-- <li class="list-group-item"><?php
                                                                        // if ($matter['IDVerifiedClient1'] == 1) {
                                                                        //     $IDVerifiedClient1 = 'Yes';
                                                                        // } else {
                                                                        //     $IDVerifiedClient1 = 'No';
                                                                        // }
                                                                        // echo "<b>ID Verified Client 1: </b>" . $IDVerifiedClient1; 
                                                                        ?></li>
                                    <li class="list-group-item"><?php
                                                                // if ($matter['IDVerifiedClient2'] == 1) {
                                                                //     $IDVerifiedClient2 = 'Yes';
                                                                // } else {
                                                                //     $IDVerifiedClient2 = 'No';
                                                                // }
                                                                // echo "<b>ID Verified Client 2: </b>" . $IDVerifiedClient2; 
                                                                ?></li>
                                    <li class="list-group-item"><?php
                                                                // $timestamp = strtotime($matter['DateOfAMLReport']);
                                                                // $DateOfLastAML = date("d-m-Y", $timestamp);
                                                                // echo "<b>Date Of Last AML Report: </b>" . $DateOfLastAML; 
                                                                ?></li> -->
                                </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="collapse collapse-horizontal" id="LastWorkDone">
                            <div class="card card-body shadow-sm p-3 mb-5 bg-body rounded" style="width: 650px;">
                                <h4 id="LastWorkDone">Last Work</h4>
                                <p>
                                <ul class="list-group">
                                    <li class="list-group-item"><?php
                                                                $timestamp = strtotime($matter['DateOfLastWork']);
                                                                $DateOfLastWork = date("d-m-Y", $timestamp);
                                                                echo "<b>Date Of Last Work: </b>" . $DateOfLastWork; ?></li>

                                    <li class="list-group-item"><?php echo "<b>Person Last Worked: </b>" . $matter['PersonLastWorked']; ?></li>
                                    <li class="list-group-item"><?php echo "<b>Description of Last Work: </b>" . $matter['DescOfLastWork']; ?></li>
                                </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="collapse collapse-horizontal" id="NextWork">
                            <div class="card card-body shadow-sm p-3 mb-5 bg-body rounded" style="width: 650px;">
                                <h4 id="NextWork">Next Work</h4>
                                <p>
                                <ul class="list-group">
                                    <li class="list-group-item"><?php echo "<b>Person For Next Task: </b>" . $matter['PersonForNextTask']; ?></li>
                                    <li class="list-group-item"><?php echo "<b>Tasks Required: </b>" . $matter['TasksRequired']; ?></li>
                                    <li class="list-group-item"><?php
                                                                $timestamp = strtotime($matter['DateOfNextWork']);
                                                                $DateOfNxtWork = date("d-m-Y", $timestamp);
                                                                echo "<b>Date Of Next Work: </b>" . $DateOfNxtWork; ?></li>
                                    <li class="list-group-item"><?php
                                                                if ($matter['ActionRequired'] == 1) {
                                                                    $ActionReq = 'Yes';
                                                                } else {
                                                                    $ActionReq = 'No';
                                                                }
                                                                echo "<b>Action Required:</b> " . $ActionReq; ?></li>



                                </ul>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        <?php
        else : header("Location: index.php?FileNotFound=1");
            exit();
        endif;

        ?>


    </div>


    </div>

    <?php
    writeFooter();
    ?>


</body>

</html>
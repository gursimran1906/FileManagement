<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();

$fileNumber = $_GET['FileNumber'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title><?php echo $fileNumber . " - "; ?>Update Matter</title>
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
  <?php writeNavBar(); ?>
  <?php sessionCheck(); ?>
  <div class="col">
    <?php

    // echo $_GET['filenumber']; work here


    $sql = "SELECT * FROM wip WHERE FileNumber='$fileNumber'";


    $data = $con->query($sql);

    $matter = $data->fetch_assoc();

    $client1ContactID = $matter['Client1Contact_ID'];
    $client2ContactID = $matter['Client2Contact_ID'];

    // CLIENT 1 INFO 
    $sql = "SELECT * FROM client_contact_details WHERE ID =$client1ContactID";

    $data = $con->query($sql);
    $client1Info = $data->fetch_assoc();


    // CLIENT 2 INFO
    $sql = "SELECT * FROM client_contact_details WHERE ID =$client2ContactID";

    $data = $con->query($sql);
    $client2Info = $data->fetch_assoc();

    ?>
    <form class="row g-3" action="serverCalls/Update.php" method="post" id="OpenFileForm">
      <div class="col-md-6">
        <label class="form-label">File Number</label>
        <input type="text" class="form-control" name="FileNumber" value="<?php echo $matter['FileNumber']; ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Fee Earner</label>
        <select class="form-select" name="FeeEarner">
          <option>Select...</option>
          <option <?php
                  if ($matter['FeeEarner'] == 'SD') {
                    echo "selected";
                  } ?>>SD</option>
          <option <?php
                  if ($matter['FeeEarner'] == 'ND') {
                    echo "selected";
                  } ?>>ND</option>
          <option <?php
                  if ($matter['FeeEarner'] == 'CP') {
                    echo "selected";
                  } ?>>CP</option>
          <option <?php
                  if ($matter['FeeEarner'] == 'TR') {
                    echo "selected";
                  } ?>>TR</option>
          <option <?php
                  if ($matter['FeeEarner'] == 'GM') {
                    echo "selected";
                  } ?>>GM</option>
          <option <?php
                  if ($matter['FeeEarner'] == 'JP') {
                    echo "selected";
                  } ?>>JP</option>

          <option <?php
                  if ($matter['FeeEarner'] == 'DC') {
                    echo "selected";
                  } ?>>DC</option>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label">Matter Descriptions</label>
        <input type="text" class="form-control" name="MatterDesc" value="<?php echo $matter['MatterDescription']; ?>">
      </div>
      <div class="row">
        <div class="col">
          <legend>Client 1</legend>
          <div class="col-md-8">
            <label class="form-label">Client 1 Name</label>
            <input type="text" class="form-control" name="ClientName1" value="<?php if ($client1ContactID != 0 and $client1Info['ClientName'] != null) {
                                                                                echo $client1Info['ClientName'];
                                                                              } ?>">
          </div>

          <div class="col-md-8">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" name="Client1AddressLine1" value="<?php if ($client1ContactID != 0 and $client1Info['AddressLine1'] != null) {
                                                                                        echo $client1Info['AddressLine1'];
                                                                                      } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" name="Client1AddressLine2" value="<?php if ($client1ContactID != 0 and $client1Info['AddressLine2'] != null) {
                                                                                        echo $client1Info['AddressLine2'];
                                                                                      } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">County</label>
            <input type="text" class="form-control" name="Client1County" value="<?php if ($client1ContactID != 0 and $client1Info['County'] != null) {
                                                                                  echo $client1Info['County'];
                                                                                } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" name="Client1Postcode" value="<?php if ($client1ContactID != 0 and $client1Info['Postcode'] != null) {
                                                                                    echo $client1Info['Postcode'];
                                                                                  } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="Client1Email" value="<?php if ($client1ContactID != 0 and $client1Info['Email'] != null) {
                                                                                  echo $client1Info['Email'];
                                                                                } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Contact Number</label>
            <input type="text" class="form-control" name="Client1ContactNumber" value="<?php if ($client1ContactID != 0 and $client1Info['ContactNumber'] != null) {
                                                                                          echo $client1Info['ContactNumber'];
                                                                                        } ?>">
          </div>
        </div>




        <div class="col">
          <legend>Client 2</legend>
          <div class="col-md-8">
            <label class="form-label">Client 2 Name</label>
            <input type="text" class="form-control" name="ClientName2" value="<?php if ($client2ContactID != 0 and $client2Info['ClientName'] != null) {
                                                                                echo $client2Info['ClientName'];
                                                                              } ?>">
          </div>

          <div class="col-md-8">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" name="Client2AddressLine1" value="<?php if ($client2ContactID != 0 and $client2Info['AddressLine1'] != null) {
                                                                                        echo $client2Info['AddressLine1'];
                                                                                      } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 2</label>
            <input type="text" class="form-control" name="Client2AddressLine2" value="<?php if ($client2ContactID != 0 and $client2Info['AddressLine2'] != null) {
                                                                                        echo $client2Info['AddressLine2'];
                                                                                      } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">County</label>
            <input type="text" class="form-control" name="Client2County" value="<?php if ($client2ContactID != 0 and $client2Info['County'] != null) {
                                                                                  echo $client2Info['County'];
                                                                                } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" name="Client2Postcode" value="<?php if ($client2ContactID != 0 and $client2Info['Postcode'] != null) {
                                                                                    echo $client2Info['Postcode'];
                                                                                  } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="Client2Email" value="<?php if ($client2ContactID != 0 and $client2Info['Email'] != null) {
                                                                                  echo $client2Info['Email'];
                                                                                } ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Contact Number</label>
            <input type="text" class="form-control" name="Client2ContactNumber" value="<?php if ($client2ContactID != 0 and $client2Info['ContactNumber'] != null) {
                                                                                          echo $client2Info['ContactNumber'];
                                                                                        } ?>">
          </div>
        </div>

      </div>



      <div class="col-md-4">
        <label class="form-label">Matter Type</label>
        <select name="MatterType" class="form-select">
          <option>Select...</option>
          <option <?php
                  if ($matter['MatterType'] == 'Family') {
                    echo "selected";
                  } ?>>Family</option>
          <option <?php
                  if ($matter['MatterType'] == 'Litigation') {
                    echo "selected";
                  } ?>>Litigation</option>
          <option <?php
                  if ($matter['MatterType'] == 'Conveyancing') {
                    echo "selected";
                  } ?>>Conveyancing</option>

          <option <?php
                  if ($matter['MatterType'] == 'Clinical Negligence') {
                    echo "selected";
                  } ?>>Clinical Negligence</option>
          <option <?php
                  if ($matter['MatterType'] == 'Corporate/ Commercial') {
                    echo "selected";
                  } ?>>Corporate/ Commercial</option>
          <option <?php
                  if ($matter['MatterType'] == 'Corporate/ Commercial') {
                    echo "selected";
                  } ?>>Debt Recovery</option>
          <option <?php
                  if ($matter['MatterType'] == 'Employment') {
                    echo "selected";
                  } ?>>Employment</option>

          <option <?php
                  if ($matter['MatterType'] == 'General Advice') {
                    echo "selected";
                  } ?>>General Advice</option>
          <option <?php
                  if ($matter['MatterType'] == 'Housing') {
                    echo "selected";
                  } ?>>Housing</option>
          <option <?php
                  if ($matter['MatterType'] == 'Immigration') {
                    echo "selected";
                  } ?>>Immigration</option>
          <option <?php
                  if ($matter['MatterType'] == 'Intellectual Property') {
                    echo "selected";
                  } ?>>Intellectual Property</option>
          <option <?php
                  if ($matter['MatterType'] == 'Licensing') {
                    echo "selected";
                  } ?>>Licensing</option>

          <option <?php
                  if ($matter['MatterType'] == 'Miscellaneous') {
                    echo "selected";
                  } ?>>Miscellaneous</option>
          <option <?php
                  if ($matter['MatterType'] == 'Personal Injury') {
                    echo "selected";
                  } ?>>Personal Injury</option>
          <option <?php
                  if ($matter['MatterType'] == 'Power of Attorney') {
                    echo "selected";
                  } ?>>Power of Attorney</option>
          <option <?php
                  if ($matter['MatterType'] == 'Probate') {
                    echo "selected";
                  } ?>>Probate</option>
          <option <?php
                  if ($matter['MatterType'] == 'Trust') {
                    echo "selected";
                  } ?>>Trust</option>
          <option <?php
                  if ($matter['MatterType'] == 'Wills') {
                    echo "selected";
                  } ?>>Wills</option>

        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">File Status</label>
        <select name="FileStatus" class="form-select">
          <option>Select...</option>

          <option <?php
                  if ($matter['FileStatus'] == 'Open') {
                    echo "selected";
                  } ?>>Open</option>
          <option <?php
                  if ($matter['FileStatus'] == 'Archived') {
                    echo "selected";
                  } ?>>Archived</option>
          <option <?php
                  if ($matter['FileStatus'] == 'To Be Closed') {
                    echo "selected";
                  } ?>>To Be Closed</option>
        </select>
      </div>
      <div class="col-md-2">
        <div class="form-check">
          <label class="form-check-label">
            Client Care Letter
          </label>
          <input class="form-check-input" name="ClientCareLetter" type="checkbox" <?php
                                                                                  if ($matter['ClientCareLetter'] == 1) {
                                                                                    echo "checked";
                                                                                  }
                                                                                  ?>>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">File Location</label>
        <select name="FileLocation" class="form-select">
          <option>Select...</option>
          <option <?php
                  if ($matter['FileLocation'] == 'Attic - See comments for box No.') {
                    echo "selected";
                  } ?>>Attic - See comments for box No.</option>
          <option <?php
                  if ($matter['FileLocation'] == 'Back Office - Family') {
                    echo "selected";
                  } ?>>Back Office - Family</option>
          <option <?php
                  if ($matter['FileLocation'] == 'Back Office - Litigation') {
                    echo "selected";
                  } ?>>Back Office - Litigation</option>
          <option <?php
                  if ($matter['FileLocation'] == 'Back Office - Conveyancing') {
                    echo "selected";
                  } ?>>Back Office - Conveyancing</option>
          <option <?php
                  if ($matter['FileLocation'] == 'Back Office - Miscellaneous') {
                    echo "selected";
                  } ?>>Back Office - Miscellaneous</option>
          <option <?php
                  if ($matter['FileLocation'] == 'Back Office - Private') {
                    echo "selected";
                  } ?>>Back Office - Private</option>
          <option <?php
                  if ($matter['FileLocation'] == 'With CP') {
                    echo "selected";
                  } ?>>With CP</option>
          <option <?php
                  if ($matter['FileLocation'] == 'With TR') {
                    echo "selected";
                  } ?>>With TR</option>
        </select>
      </div>
      <div class="col-md-2">
        <div class="form-check">
          <input class="form-check-input" name="TermsOfEng1" type="checkbox" <?php
                                                                              if ($matter['TermsOfEngagementClient1'] == 1) {
                                                                                echo "checked";
                                                                              }
                                                                              ?>>
          <label class="form-check-label">
            Signed Terms Of Engagement Client 1
          </label>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="TermsOfEng2" <?php
                                                                              if ($matter['TermsOfEngagementClient2'] == 1) {
                                                                                echo "checked";
                                                                              }
                                                                              ?>>
          <label class="form-check-label">
            Signed Terms Of Engagement Client 2
          </label>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="NCBA1" <?php
                                                                        if ($matter['NCBAClient1'] == 1) {
                                                                          echo "checked";
                                                                        }
                                                                        ?>>
          <label class="form-check-label">
            Signed NCBA Client 1
          </label>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="NCBA2" <?php
                                                                        if ($matter['NCBAClient2'] == 1) {
                                                                          echo "checked";
                                                                        }
                                                                        ?>>
          <label class="form-check-label">
            Signed NCBA Client 2
          </label>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="IDVer1" <?php
                                                                        if ($matter['IDVerifiedClient1'] == 1) {
                                                                          echo "checked";
                                                                        }
                                                                        ?>>
          <label class="form-check-label">
            ID Verified Client 1
          </label>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="IDVer2" <?php
                                                                        if ($matter['IDVerifiedClient2'] == 1) {
                                                                          echo "checked";
                                                                        }
                                                                        ?>>
          <label class="form-check-label">
            ID Verified Client 2
          </label>
        </div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Date Of Last AML Check</label>
        <input type="date" class="form-control" name="DateOfLastAML" value=<?php
                                                                            $timestamp = strtotime($matter['DateOfAMLReport']);
                                                                            $DateOfLastAML = date("Y-m-d", $timestamp);
                                                                            echo $DateOfLastAML; ?>>
      </div>
      <div class="col-md-4">
        <label class="form-label">Person Last Worked</label>
        <select name="PersonLastWorked" class="form-select">
          <option>Select...</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'SD') {
                    echo "selected";
                  } ?>>SD</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'ND') {
                    echo "selected";
                  } ?>>ND</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'CP') {
                    echo "selected";
                  } ?>>CP</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'TR') {
                    echo "selected";
                  } ?>>TR</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'GM') {
                    echo "selected";
                  } ?>>GM</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'JP') {
                    echo "selected";
                  } ?>>JP</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'GB') {
                    echo "selected";
                  } ?>>GB</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'LH') {
                    echo "selected";
                  } ?>>LH</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'SM') {
                    echo "selected";
                  } ?>>SM</option>

        </select>
      </div>
      <div class="col-md-8">
        <label class="form-label">Description of Last Work</label>
        <textarea type="text" class="form-control" name="DescLastWork" value="<?php echo $matter['DescOfLastWork'];  ?>"><?php echo $matter['DescOfLastWork'];  ?></textarea>
      </div>
      <div class="col-md-4">
        <label class="form-label">Date Of Last Work</label>
        <input type="date" class="form-control" name="DateOfLastWork" value=<?php
                                                                            $timestamp = strtotime($matter['DateOfLastWork']);
                                                                            $DateOfLastWork = date("Y-m-d", $timestamp);
                                                                            echo $DateOfLastWork; ?>>
      </div>
      <div class="col-md-4">
        <label class="form-label">Person for Next Task</label>
        <select name="PersonNextTask" class="form-select">
          <option>Select...</option>
          <option <?php
                  if ($matter['PersonForNextTask'] == 'SD') {
                    echo "selected";
                  } ?>>SD</option>
          <option <?php
                  if ($matter['PersonForNextTask'] == 'ND') {
                    echo "selected";
                  } ?>>ND</option>
          <option <?php
                  if ($matter['PersonForNextTask'] == 'CP') {
                    echo "selected";
                  } ?>>CP</option>
          <option <?php
                  if ($matter['PersonForNextTask'] == 'TR') {
                    echo "selected";
                  } ?>>TR</option>
          <option <?php
                  if ($matter['PersonForNextTask'] == 'GM') {
                    echo "selected";
                  } ?>>GM</option>
          <option <?php
                  if ($matter['PersonForNextTask'] == 'JP') {
                    echo "selected";
                  } ?>>JP</option>
          <option <?php
                  if ($matter['PersonForNextTask'] == 'GB') {
                    echo "selected";
                  } ?>>GB</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'LH') {
                    echo "selected";
                  } ?>>LH</option>
          <option <?php
                  if ($matter['PersonLastWorked'] == 'SM') {
                    echo "selected";
                  } ?>>SM</option>

        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Date Of Next Work</label>
        <input type="date" class="form-control" name="DateOfNextWork" value=<?php
                                                                            $timestamp = strtotime($matter['DateOfNextWork']);
                                                                            $DateOfNextWork = date("Y-m-d", $timestamp);
                                                                            echo $DateOfNextWork; ?>>
      </div>
      <div class="col-md-10">
        <label class="form-label">Task Required</label>
        <textarea type="text" class="form-control" name="TaskRequired" value=" <?php echo $matter['TasksRequired']; ?>"><?php echo $matter['TasksRequired']; ?></textarea>
      </div>
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="ActionRequired" <?php
                                                                                if ($matter['ActionRequired'] == 1) {
                                                                                  echo "checked";
                                                                                }
                                                                                ?>>
          <label class="form-check-label">
            Action Required
          </label>
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label">Next Key Date</label>
        <input type="date" class="form-control" name="NextKeyDate" value=<?php
                                                                          $timestamp = strtotime($matter['NextKeyDate']);
                                                                          $NextKeyDate = date("Y-m-d", $timestamp);
                                                                          echo $NextKeyDate; ?>>
      </div>
      <div class="col-12">
        <label class="form-label">Comments</label>
        <textarea type="text" class="form-control" name="Comments"><?php echo $matter['Comments']; ?></textarea>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>

  </div>
  <?php
  writeFooter();
  ?>
</body>

</html>
<?php
include 'commonFunctions.php';
include 'Connect.php';



checkLogin();
$fileNumber = $_GET['FileNumber'];


$sql = "SELECT * FROM wip WHERE FileNumber='$fileNumber'";


$data = $con->query($sql);

$matter = $data->fetch_assoc();



$sql = "SELECT * FROM client_contact_details ORDER BY ClientName ASC";
$data = $con->query($sql);

$clientNames = array();


if ($data->num_rows > 0) {

  while ($row = $data->fetch_assoc()) {
    $clientNames[] = $row;
  }
}


$sql = "SELECT * FROM authorised_party_contact_details ORDER BY Name ASC";
$data = $con->query($sql);

$APNames = array();


if ($data->num_rows > 0) {

  while ($row = $data->fetch_assoc()) {
    $APNames[] = $row;
  }
}


$sql = "SELECT * FROM OS_Details ORDER BY Name ASC";
$data = $con->query($sql);

$OSNames = array();


if ($data->num_rows > 0) {

  while ($row = $data->fetch_assoc()) {
    $OSNames[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>Edit - <?php echo $fileNumber; ?></title>
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <script src="./js/commonFucntions.js" defer></script>
  <link rel="stylesheet" href="css/style.css">

  <script type="text/javascript">
    function toggleForm(divName) {
      const element = document.getElementById("inputNew" + divName);
      checkValue = document.getElementById(divName + "List");
      const inputs = element.getElementsByTagName('input');
      if (checkValue.value == '-1') {
        element.style.display = 'block';
        for (let i = 0; i < inputs.length; i++) {
          inputs[i].value = '';
          inputs[i].disabled = false;
        }
      } else {
        element.style.display = 'none';
        for (let i = 0; i < inputs.length; i++) {
          inputs[i].disabled = true;
        }
      }
    }
  </script>
  <script>
    function addFieldsUndertaking(id) {

      var container = document.getElementById(id);


      var input = document.createElement("textarea");
      input.type = "text";
      input.className = "form-control";
      input.name = id + '[]';

      var inputDiv = document.createElement("div");
      inputDiv.className = 'col-sm-10';

      inputDiv.appendChild(input);



      var fieldDiv = document.createElement("div");
      fieldDiv.className = "row";


      fieldDiv.appendChild(inputDiv);


      var deleteButton = document.createElement("span");
      deleteButton.type = "button";
      deleteButton.className = "btn btn-danger";
      deleteButton.innerHTML = "-";
      deleteButton.onclick = function() {

        container.removeChild(fieldDiv);
      };

      var deleteBtnDiv = document.createElement("div");
      deleteBtnDiv.className = 'col-sm-2';
      deleteBtnDiv.appendChild(deleteButton);


      fieldDiv.appendChild(deleteBtnDiv);


      container.appendChild(fieldDiv);
    }

    function removeParentDiv(div) {
      var parentNodeRow = div.parentNode.parentNode;
      var undertakinDiv = document.getElementById('UndertakingDiv');

      undertakinDiv.removeChild(parentNodeRow);


    }
  </script>
</head>

<body class="d-flex flex-column min-vh-100">

  <?php writeNavBar(); ?>
  <?php sessionCheck(); ?>
  <div class="col">

    <form class="row g-3" action="serverCalls/Update.php" method="post" id="OpenFileForm">

      <div class="col-md-5">
        <label class="form-label">File Number</label>
        <input type="text" class="form-control" name="FileNumber" pattern="[A-Z]{3}[0-9]{7}" title="Please, try to match ANP0010001" value="<?php echo $fileNumber; ?>">
      </div>

      <div class="col-md-4">
        <label class="form-label">Fee Earner</label>
        <select class="form-select" name="FeeEarner">

          <?php initialsList($con, $matter['FeeEarner'], false); ?>
          <option value='--' <?php if ($matter['FeeEarner'] == 'DC') {
                                echo "selected";
                              } ?>>DC</option>
        </select>
      </div>
      <div class="col-md-2">

        <label class="form-label">File Status</label>
        <select name="FileStatus" class="form-select">


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
      <div class="col-md-4">
        <label class="form-label">Matter Descriptions</label>
        <input type="text" class="form-control" name="MatterDesc" value="<?php echo $matter['MatterDescription']; ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">File Location</label>
        <select name="FileLocation" class="form-select">

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
        <label class="form-label">Funding</label>
        <select name="Funding" class="form-select">

          <option value="PF" <?php
                              if ($matter['Funding'] == 'PF') {
                                echo "selected";
                              } ?>>Private Funding</option>
          <option value="CFA" <?php
                              if ($matter['Funding'] == 'CFA') {
                                echo "selected";
                              } ?>>Conditional Fee Agreement</option>
        </select>
      </div>


      <div class="col-md-2">
        <label class="form-label">Matter Type</label>
        <select name="MatterType" class="form-select">


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
      <div class="row">
        <div class="col-md-6">
          <legend>Client 1</legend>
          <select id="Client1List" onchange="toggleForm('Client1');" class="form-select" name="client1List">
            <option value='0'><button class="dropdown-item" type="button">Select Client 1 (If new client choose New Client)</button></option>
            <option value='-1'>New Client</option>
            <?php
            foreach ($clientNames as $key => $client) { ?>
              <option value="<?php echo $client['ID']; ?>" <?php if ($client['ID'] == $matter['Client1Contact_ID']) {
                                                              echo 'selected';
                                                            } ?>><?php echo $client['ClientName']; ?></option>
            <?php }  ?>
          </select>
        </div>

        <div class="col-md-6">
          <legend>Client 2</legend>
          <select id="Client2List" onchange="toggleForm('Client2');" class="form-select" name="client2List">
            <option value='0'><button class="dropdown-item" type="button">Select Client 2 (If new client choose New Client)</button></option>
            <option value='-1'>New Client</option>

            <?php
            foreach ($clientNames as $key => $client) { ?>
              <option value="<?php echo $client['ID']; ?>" <?php if ($client['ID'] == $matter['Client2Contact_ID']) {
                                                              echo 'selected';
                                                            } ?>><?php echo $client['ClientName']; ?></option>
            <?php }  ?>
          </select>
        </div>
      </div>

      <?php
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
      <div class="row">
        <div id="inputNewClient1" class="col">

          <div class="col-md-8">
            <label class="form-label">Client 1 Name</label>
            <input type="text" class="form-control" id="ClientName1" name="ClientName1" value="<?php echo $client1Info['ClientName']; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="Client1DOB" name="Client1DOB" value="<?php
                                                                                              $timestamp = strtotime($client1Info['DOB']);
                                                                                              $DOB = date("Y-m-d", $timestamp);
                                                                                              echo $DOB; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" id="Client1AddressLine1" name="Client1AddressLine1" value="<?php echo $client1Info['AddressLine1']; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 2</label>
            <input type="text" class="form-control" id="Client1AddressLine2" name="Client1AddressLine2" value="<?php echo $client1Info['AddressLine2']; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">County</label>
            <input type="text" class="form-control" id="Client1County" name="Client1County" value="<?php echo $client1Info['County']; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" id="Client1Postcode" name="Client1Postcode" value="<?php echo $client1Info['Postcode']; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="Client1Email" name="Client1Email" value="<?php echo $client1Info['Email']; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control" id="Client1ContactNumber" name="Client1ContactNumber" value="<?php echo $client1Info['ContactNumber']; ?>">
          </div>
          <div class="col-md-8">
            <label class="form-label">Date of Last AML Check</label>
            <input type="date" class="form-control" id="AMLCheck" name="Client1AMLCheckDate" value="<?php
                                                                                                    $timestamp = strtotime($client1Info['DateOfLastAML']);
                                                                                                    $DtAML = date("Y-m-d", $timestamp);
                                                                                                    echo $DtAML; ?>">
          </div>
          <div class="col-md-8 mt-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="IDVer1" <?php
                                                                            if ($client1Info['IdVerified'] == 1) {
                                                                              echo "checked";
                                                                            }
                                                                            ?>>
              <label class="form-check-label">
                ID Verified Client 1
              </label>
            </div>
          </div>
        </div>

        <div class="col">
          <div id="inputNewClient2" <?php if ($client2ContactID == 0) {
                                      echo 'style="display:none ;"';
                                    } ?>class="col">

            <div class="col-md-8">
              <label class="form-label">Client 2 Name</label>
              <input type="text" class="form-control" id="ClientName2" name="ClientName2" value="<?php if ($client2ContactID != 0 and $client2Info['ClientName'] != null) {
                                                                                                    echo $client2Info['ClientName'];
                                                                                                  } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Date of Birth</label>
              <input type="date" class="form-control" id="Client2DOB" name="Client2DOB" value="<?php if ($client2ContactID != 0) {

                                                                                                  $timestamp = strtotime($client2Info['DOB']);
                                                                                                  $DOB = date("Y-m-d", $timestamp);
                                                                                                  echo $DOB;
                                                                                                } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Address Line 1</label>
              <input type="text" class="form-control" id="Client2AddressLine2" name="Client2AddressLine1" value="<?php if ($client2ContactID != 0) {
                                                                                                                    echo $client2Info['AddressLine2'];
                                                                                                                  } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Address Line 2</label>
              <input type="text" class="form-control" id="Client2AddressLine2" name="Client2AddressLine2" value="<?php if ($client2ContactID != 0) {
                                                                                                                    echo $client2Info['AddressLine2'];
                                                                                                                  } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">County</label>
              <input type="text" class="form-control" id="Client2County" name="Client2County" value="<?php if ($client2ContactID != 0) {
                                                                                                        echo $client2Info['County'];
                                                                                                      } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Postcode</label>
              <input type="text" class="form-control" id="Client2Postcode" name="Client2Postcode" value="<?php if ($client2ContactID != 0) {
                                                                                                            echo $client2Info['Postcode'];
                                                                                                          } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="Client2Email" name="Client2Email" value="<?php if ($client2ContactID != 0) {
                                                                                                      echo $client2Info['Email'];
                                                                                                    } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Contact Number</label>
              <input type="tel" class="form-control" id="Client2ContactNumber" name="Client2ContactNumber" value="<?php if ($client2ContactID != 0) {
                                                                                                                    echo $client2Info['ContactNumber'];
                                                                                                                  } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Date of Last AML Check</label>
              <input type="date" class="form-control" id="AMLCheck" name="Client2AMLCheckDate" value="<?php if ($client2ContactID != 0) {
                                                                                                        $timestamp = strtotime($client2Info['DateOfLastAML']);
                                                                                                        $DtAML = date("Y-m-d", $timestamp);
                                                                                                        echo $DtAML;
                                                                                                      } ?>">
            </div>
            <div class="col-md-8 mt-2">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="IDVer2" <?php if ($client2ContactID != 0) {
                                                                                if ($client2Info['IdVerified'] == 1) {
                                                                                  echo "checked";
                                                                                }
                                                                              }
                                                                              ?>>
                <label class="form-check-label">
                  ID Verified Client 2
                </label>
              </div>
            </div>
          </div>



        </div>

        <div class="row">
          <div class="col-md-6">
            <legend>Authorised Party 1</legend>
            <select id="AP1List" onchange="toggleForm('AP1');" class="form-select" name="AP1List">
              <option value='0'><button class="dropdown-item" type="button">Select Authorised Party 1 (If same as client, choose nothing and if not on list choose New)</button></option>
              <option value='-1'>New</option>
              <?php
              foreach ($APNames as $key => $AP) { ?>
                <option value="<?php echo $AP['ID']; ?>" <?php if ($AP['ID'] == $matter['AuthorisedParty1_ID']) {
                                                            echo 'selected';
                                                          } ?>><?php echo '<b>Name:</b> ' . $AP['Name'] . ', <b>Relationship to Client:</b> ' . $AP['RelationshipToClient']; ?></option>
              <?php }  ?>
            </select>
          </div>

          <div class="col-md-6">
            <legend>Authorised Party 2</legend>
            <select id="AP2List" onchange="toggleForm('AP2');" class="form-select" name="AP2List">
              <option value='0'><button class="dropdown-item" type="button">Select Authorised Party 2 (If same as client, choose nothing and if not on list choose New)</button></option>
              <option value='-1'>New</option>

              <?php
              foreach ($APNames as $key => $AP) { ?>
                <option value="<?php echo $AP['ID']; ?>" <?php if ($AP['ID'] == $matter['AuthorisedParty2_ID']) {
                                                            echo 'selected';
                                                          } ?>><?php echo '<b>Name:</b> ' . $AP['Name'] . ', <b>Relationship to Client:</b> ' . $AP['RelationshipToClient']; ?></option>
              <?php }  ?>
            </select>
          </div>
        </div>
        <?php
        $AP1_ID = $matter['AuthorisedParty1_ID'];
        $AP2_ID = $matter['AuthorisedParty2_ID'];

        // AP 1 INFO 
        $sql = "SELECT * FROM authorised_party_contact_details WHERE ID =$AP1_ID";

        $data = $con->query($sql);
        $AP1Info = $data->fetch_assoc();


        // AP 2 INFO
        $sql = "SELECT * FROM authorised_party_contact_details WHERE ID =$AP2_ID";

        $data = $con->query($sql);
        $AP2Info = $data->fetch_assoc();
        ?>
        <div class="row">
          <div id="inputNewAP1" <?php if ($AP2_ID == 0) {
                                  echo 'style="display:none ;"';
                                }  ?>class="col">

            <div class="col-md-8">
              <label class="form-label">Authorised Party 1 Name</label>
              <input type="text" class="form-control" id="APName1" name="APName1" value="<?php if ($AP1_ID != 0) {
                                                                                            echo $AP1Info['Name'];
                                                                                          } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Relationship to Client</label>
              <input type="text" class="form-control" name="AP1RelationshipToC" value="<?php if ($AP1_ID != 0) {
                                                                                          echo $AP1Info['RelationshipToClient'];
                                                                                        } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Address Line 1</label>
              <input type="text" class="form-control" id="AP1AddressLine1" name="AP1AddressLine1" value="<?php if ($AP1_ID != 0) {
                                                                                                            echo $AP1Info['AdressLine1'];
                                                                                                          } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Address Line 2</label>
              <input type="text" class="form-control" id="AP1AddressLine2" name="AP1AddressLine2" value="<?php if ($AP1_ID != 0) {
                                                                                                            echo $AP1Info['AddressLine2'];
                                                                                                          } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">County</label>
              <input type="text" class="form-control" id="AP1County" name="AP1County" value="<?php if ($AP1_ID != 0) {
                                                                                                echo $AP1Info['County'];
                                                                                              } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Postcode</label>
              <input type="text" class="form-control" id="AP1Postcode" name="AP1Postcode" value="<?php if ($AP1_ID != 0) {
                                                                                                    echo $AP1Info['Postcode'];
                                                                                                  } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="AP1Email" name="AP1Email" value="<?php if ($AP1_ID != 0) {
                                                                                              echo $AP1Info['Email'];
                                                                                            } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Contact Number</label>
              <input type="tel" class="form-control" id="AP1ContactNumber" name="AP1ContactNumber" value="<?php if ($AP1_ID != 0) {
                                                                                                            echo $AP1Info['ContactNumber'];
                                                                                                          } ?>">
            </div>
            <div class="mt-2 col-md-8">
              <div class="form-check">
                <label class="form-check-label">
                  ID check
                </label>
                <input class="form-check-input" name="AP1IDCheck" type="checkbox" <?php if ($AP1_ID != 0) {
                                                                                    if ($AP1Info['IDCheck'] == 1) {
                                                                                      echo 'checked';
                                                                                    }
                                                                                  } ?>>
              </div>
            </div>
            <div id="inputNewAP1" class="col-md-8">
              <label class="form-label">Date of ID check</label>
              <input type="date" class="form-control" name="AP1IDCheckDate" value="<?php if ($AP1_ID != 0) {
                                                                                      $timestamp = strtotime($AP1Info['DateOfIDCheck']);
                                                                                      $DtID = date("Y-m-d", $timestamp);
                                                                                      echo $DtID;
                                                                                    } ?>">
            </div>
          </div>

          <div id="inputNewAP2" <?php if ($AP2_ID == 0) {
                                  echo 'style="display:none ;"';
                                }  ?>class="col">

            <div class="col-md-8">
              <label class="form-label">Authorised Party 2 Name</label>
              <input type="text" class="form-control" id="APName2" name="APName2" value="<?php if ($AP2_ID != 0) {
                                                                                            echo $AP2Info['Name'];
                                                                                          } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Relationship to Client</label>
              <input type="text" class="form-control" name="AP2RelationshipToC" value="<?php if ($AP2_ID != 0) {
                                                                                          echo $AP2Info['RelationshipToClient'];
                                                                                        } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Address Line 1</label>
              <input type="text" class="form-control" id="AP2AddressLine1" name="AP2AddressLine1" value="<?php if ($AP2_ID != 0) {
                                                                                                            echo $AP2Info['AdressLine1'];
                                                                                                          } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Address Line 2</label>
              <input type="text" class="form-control" id="AP2AddressLine2" name="AP2AddressLine2" value="<?php if ($AP2_ID != 0) {
                                                                                                            echo $AP2Info['AddressLine2'];
                                                                                                          } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">County</label>
              <input type="text" class="form-control" id="AP2County" name="AP2County" value="<?php if ($AP2_ID != 0) {
                                                                                                echo $AP2Info['County'];
                                                                                              } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Postcode</label>
              <input type="text" class="form-control" id="AP2Postcode" name="AP2Postcode" value="<?php if ($AP2_ID != 0) {
                                                                                                    echo $AP2Info['Postcode'];
                                                                                                  } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="AP2Email" name="AP2Email" value="<?php if ($AP2_ID != 0) {
                                                                                              echo $AP2Info['Email'];
                                                                                            } ?>">
            </div>
            <div class="col-md-8">
              <label class="form-label">Contact Number</label>
              <input type="tel" class="form-control" id="AP2ContactNumber" name="AP2ContactNumber" value="<?php if ($AP2_ID != 0) {
                                                                                                            echo $AP2Info['ContactNumber'];
                                                                                                          } ?>">
            </div>
            <div class="mt-2 col-md-8">
              <div class="form-check">
                <label class="form-check-label">
                  ID check
                </label>
                <input class="form-check-input" name="AP2IDCheck" type="checkbox" <?php if ($AP2_ID != 0) {
                                                                                    if ($AP2Info['IDCheck'] == 1) {
                                                                                      echo 'checked';
                                                                                    }
                                                                                  } ?>>
              </div>
            </div>
            <div id="inputNewAP2" class="col-md-8">
              <label class="form-label">Date of ID check</label>
              <input type="date" class="form-control" name="AP2IDCheckDate" value="<?php if ($AP2_ID != 0) {
                                                                                      $timestamp = strtotime($AP2Info['DateOfIDCheck']);
                                                                                      $DtID = date("Y-m-d", $timestamp);
                                                                                      echo $DtID;
                                                                                    } ?>">
            </div>
          </div>


        </div>
        <?php
        $OS_ID = $matter['OtherSideDetails'];
        $sql = "SELECT * FROM OS_Details WHERE ID =$OS_ID";

        $data = $con->query($sql);
        $OSInfo = $data->fetch_assoc();

        ?>
        <legend>Other Side's Details</legend>
        <div class="col-md-6">

          <select id="OSList" onchange="toggleForm('OS');" class="form-select" name="OS_ID">
            <option value='0'><button class="dropdown-item" type="button">Select Other side(If not on the list choose New)</button></option>
            <option value='-1'>New</option>
            <?php
            foreach ($OSNames as $key => $OS) { ?>
              <option value="<?php echo $OS['ID']; ?>" <?php if ($OS['ID'] == $OS_ID) {
                                                          echo 'selected';
                                                        } ?>><?php echo '<b>' . $OS['Name'] . '</b> Of <b>' . $OS['AddressLine1'] . ', ' . $OS['AddressLine2'] . ', ' . $OS['Ptcode'] . '</b> ' ?></option>
            <?php }  ?>
          </select>
        </div>
        <div class="row" id="inputNewOS" <?php if ($OS_ID == 0) {
                                            echo 'style="display:none;"';
                                          } ?>>
          <div class="col">
            <div class="col-md-4">
              <label class="form-label">Name</label>
              <input type="text" class="form-control" name="OSName" value="<?php echo $OSInfo['Name']; ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Address Line 1</label>
              <input type="text" class="form-control" name="OSAddressLine1" value="<?php echo $OSInfo['AddressLine1']; ?>">
            </div>
            <div class=" col-md-4">
              <label class="form-label">Address Line 2</label>
              <input type="text" class="form-control" name="OSAddressLine2" value="<?php echo $OSInfo['AddressLine2']; ?>">
            </div>
            <div class=" col-md-4">
              <label class="form-label">County</label>
              <input type="text" class="form-control" name="OSCounty" value="<?php echo $OSInfo['County']; ?>">
            </div>
            <div class="col-md-2">
              <label class="form-label">Postcode</label>
              <input type="text" class="form-control" name="OSPostcode" value="<?php echo $OSInfo['Ptcode']; ?>">
            </div>


            <div class="col-md-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="OSEmail" value="<?php echo $OSInfo['Email']; ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Contact Number</label>
              <input type="tel" class="form-control" name="OSContactNumber" value="<?php echo $OSInfo['ContactNumber']; ?>">
            </div>

            <div class="col-md-4">
              <label class="form-label">Solicitors</label>
              <input type="text" class="form-control" name="OSSolicitors" value="<?php echo $OSInfo['Solicitors']; ?>">
            </div>

            <div class="col-md-4">
              <label class="form-label">Solicitors Email</label>
              <input type="email" class="form-control" name="OSSolicitorsEmail" value="<?php echo $OSInfo['SolicitorsEmail']; ?>">
            </div>





          </div>
        </div>





        <legend>Compliance</legend>

        <div class="col-md-2">
          <label class="form-label">Date Client Care Sent</label>
          <input type="date" class="form-control" name="DateOfClientCareSent" value="<?php
                                                                                      $timestamp = strtotime($matter['DateOfClientCareSent']);
                                                                                      $DtCCsent = date("Y-m-d", $timestamp);
                                                                                      echo $DtCCsent;
                                                                                      ?>">
        </div>

        <div class="col-md-3">
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
        <div class="col-md-3">
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
          <label class="form-label">Date Terms Of Engagement sent</label>
          <input type="date" class="form-control" name="DateOfToESent" value="<?php
                                                                              $timestamp = strtotime($matter['DateOfToESent']);
                                                                              $DtToEsent = date("Y-m-d", $timestamp);
                                                                              echo $DtToEsent;
                                                                              ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Date Terms Of Engagement received</label>
          <input type="date" class="form-control" name="DateOfToERcvd" value="<?php
                                                                              $timestamp = strtotime($matter['DateOfToERcvd']);
                                                                              $DtToERcvd = date("Y-m-d", $timestamp);
                                                                              echo $DtToERcvd;
                                                                              ?>">
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
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
          <label class="form-label">Date NCBA sent</label>
          <input type="date" class="form-control" name="DateOfNCBASent" value="<?php
                                                                                $timestamp = strtotime($matter['DateOfNCBASent']);
                                                                                $DtNCBAsent = date("Y-m-d", $timestamp);
                                                                                echo $DtNCBAsent;
                                                                                ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Date NCBA received</label>
          <input type="date" class="form-control" name="DateOfNCBARcvd" value="<?php
                                                                                $timestamp = strtotime($matter['DateOfNCBARcvd']);
                                                                                $DtNCBARcvd = date("Y-m-d", $timestamp);
                                                                                echo $DtNCBARcvd;
                                                                                ?>">
        </div>


        <legend>Last Work</legend>
        <div class="col-md-4">
          <label class="form-label">Description of Last Work</label>
          <textarea type="text" class="form-control" name="DescLastWork" value="<?php echo $matter['DescOfLastWork'];  ?>"><?php echo $matter['DescOfLastWork'];  ?></textarea>
        </div>
        <div class="col-md-2">
          <label class="form-label">Person Last Worked</label>
          <select name="PersonLastWorked" class="form-select">
            <?php initialsList($con, $matter['PersonLastWorked'], false); ?>
          </select>

        </div>
        <div class="col-md-2">
          <label class="form-label">Date Of Last Work</label>
          <input type="date" class="form-control" name="DateOfLastWork" value=<?php
                                                                              $timestamp = strtotime($matter['DateOfLastWork']);
                                                                              $DateOfLastWork = date("Y-m-d", $timestamp);
                                                                              echo $DateOfLastWork; ?>>
        </div>

        <legend>Next Work</legend>
        <div class="col-md-4">
          <label class="form-label">Person for Next Task</label>
          <select name="PersonNextTask" class="form-select">
            <?php initialsList($con, $matter['PersonForNextWork'], false); ?>
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

        <div class="col-md-6" id='KeyInfoDiv'>
          <legend>Key Information</legend>

          <div class="row">
            <div class="col-sm">
              <textarea type="text" class="form-control" name="KeyInfo"><?php echo $matter['KeyInformation']; ?></textarea>
            </div>

          </div>
        </div>
        <?php
        $Undertakings = json_decode($matter['Undertakings']);

        ?>
        <div class="col-md-6" id='UndertakingDiv'>
          <legend>Undertakings</legend>

          <div class="row">
            <div class="col-sm-10">
              <textarea type="text" class="form-control" name="UndertakingDiv[]"></textarea>
            </div>

            <div class="col-sm-2">
              <span type='button' class='btn btn-primary' onclick="addFieldsUndertaking('UndertakingDiv');" id="add_more_fields">+</span>
            </div>
          </div>
          <?php
          foreach ($Undertakings as $q) { ?>

            <?php
            if ($q != '') { ?>
              <div class="row">
                <div class="col-sm-10"><textarea class="form-control" name="UndertakingDiv[]"><?php echo $q; ?></textarea></div>
                <div class="col-sm-2"><span type='button' onclick="removeParentDiv(this);" class="btn btn-danger">-</span></div>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
        <div class="col-12">
          <label class="form-label">Comments</label>
          <textarea type="text" class="form-control" name="Comments"><?php echo $matter['Comments']; ?></textarea>
        </div>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
  </div>
  </div>
  </form>
  </div>
  <?php
  writeFooter();
  ?>
</body>

</html>
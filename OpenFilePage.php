<?php
include 'commonFunctions.php';
include 'Connect.php';



checkLogin();

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
  <title>Open New File</title>
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
  </script>
</head>

<body class="d-flex flex-column min-vh-100">

  <?php writeNavBar(); ?>
  <?php sessionCheck(); ?>
  <div class="col">

    <form class="row g-3" action="serverCalls/OpenFile.php" method="post" id="OpenFileForm">

      <div class="col-md-5">
        <label class="form-label">File Number</label>
        <input type="text" class="form-control" name="FileNumber" pattern="[A-Z]{3}[0-9]{7}" title="Please, try to match ANP0010001">
      </div>
      <div class="col-md-4">
        <label class="form-label">Fee Earner</label>
        <select class="form-select" name="FeeEarner">
          <?php initialsList($con, "", false); ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">File Status</label>
        <select name="FileStatus" class="form-select">
          <option>Select...</option>
          <option selected>Open</option>
          <option>Archived</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Matter Descriptions</label>
        <input type="text" class="form-control" name="MatterDesc">
      </div>
      <div class="col-md-4">
        <label class="form-label">File Location</label>
        <select name="FileLocation" class="form-select">
          <option selected>Select...</option>
          <option>Attic - See comments for box No.</option>
          <option>Back Office - Family</option>
          <option>Back Office - Litigation</option>
          <option>Back Office - Conveyancing</option>
          <option>Back Office - Miscellaneous</option>
          <option>Back Office - Private</option>
          <option>With CP</option>
          <option>With TR</option>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Funding</label>
        <select name="Funding" class="form-select">

          <option selected value="PF">Private Funding</option>
          <option value="CFA">Conditional Fee Agreement</option>
        </select>
      </div>


      <div class="col-md-2">
        <label class="form-label">Matter Type</label>
        <select name="MatterType" class="form-select">
          <option selected>Select...</option>

          <option>Conveyancing</option>
          <option>Clinical Negligence</option>
          <option>Corporate/ Commercial</option>
          <option>Debt Recovery</option>
          <option>Employment</option>
          <option>Family</option>
          <option>General Advice</option>
          <option>Housing</option>
          <option>Immigration</option>
          <option>Intellectual Property</option>
          <option>Licensing</option>
          <option>Litigation</option>
          <option>Miscellaneous</option>
          <option>Personal Injury</option>
          <option>Power of Attorney</option>
          <option>Probate</option>
          <option>Trust</option>
          <option>Wills</option>
        </select>
      </div>
      <div class="row">
        <div class="col-md-6">
          <legend>Client 1</legend>
          <select id="Client1List" onchange="toggleForm('Client1');" class="form-select" name="client1List">
            <option selected value='0'><button class="dropdown-item" type="button">Select Client 1 (If new client choose New Client)</button></option>
            <option value='-1'>New Client</option>
            <?php
            foreach ($clientNames as $key => $client) { ?>
              <option value="<?php echo $client['ID']; ?>"><?php echo $client['ClientName']; ?></option>
            <?php }  ?>
          </select>
        </div>

        <div class="col-md-6">
          <legend>Client 2</legend>
          <select id="Client2List" onchange="toggleForm('Client2');" class="form-select" name="client2List">
            <option selected value='0'><button class="dropdown-item" type="button">Select Client 2 (If new client choose New Client)</button></option>
            <option value='-1'>New Client</option>

            <?php
            foreach ($clientNames as $key => $client) { ?>
              <option value="<?php echo $client['ID']; ?>"><?php echo $client['ClientName']; ?></option>
            <?php }  ?>
          </select>
        </div>
      </div>
      <div class="row">
        <div id="inputNewClient1" style="display:none ;" class="col">

          <div class="col-md-8">
            <label class="form-label">Client 1 Name</label>
            <input type="text" class="form-control" id="ClientName1" name="ClientName1">
          </div>
          <div class="col-md-8">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="Client1DOB" name="Client1DOB">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" id="Client1AddressLine1" name="Client1AddressLine1">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 2</label>
            <input type="text" class="form-control" id="Client1AddressLine2" name="Client1AddressLine2">
          </div>
          <div class="col-md-8">
            <label class="form-label">County</label>
            <input type="text" class="form-control" id="Client1County" name="Client1County">
          </div>
          <div class="col-md-8">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" id="Client1Postcode" name="Client1Postcode">
          </div>
          <div class="col-md-8">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="Client1Email" name="Client1Email">
          </div>
          <div class="col-md-8">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control" id="Client1ContactNumber" name="Client1ContactNumber">
          </div>
          <div class="col-md-8">
            <label class="form-label">Date of Last AML Check</label>
            <input type="date" class="form-control" id="AMLCheck" name="Client1AMLCheckDate">
          </div>
          <div class="col-md-8 mt-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="IDVer1">
              <label class="form-check-label">
                ID Verified Client 1
              </label>
            </div>
          </div>
        </div>

        <div id="inputNewClient2" style="display:none ;" class="col">

          <div class="col-md-8">
            <label class="form-label">Client 2 Name</label>
            <input type="text" class="form-control" id="ClientName2" name="ClientName2">
          </div>
          <div class="col-md-8">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="Client2DOB" name="Client2DOB">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" id="Client2AddressLine1" name="Client2AddressLine1">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 2</label>
            <input type="text" class="form-control" id="Client2AddressLine2" name="Client2AddressLine2">
          </div>
          <div class="col-md-8">
            <label class="form-label">County</label>
            <input type="text" class="form-control" id="Client2County" name="Client2County">
          </div>
          <div class="col-md-8">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" id="Client2Postcode" name="Client2Postcode">
          </div>
          <div class="col-md-8">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="Client2Email" name="Client2Email">
          </div>
          <div class="col-md-8">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control" id="Client2ContactNumber" name="Client2ContactNumber">
          </div>
          <div class="col-md-8">
            <label class="form-label">Date of Last AML Check</label>
            <input type="date" class="form-control" id="AMLCheck" name="Client2AMLCheckDate">
          </div>
          <div class="col-md-8 mt-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="IDVer2">
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
            <option selected value='0'><button class="dropdown-item" type="button">Select Authorised Party 1 (If same as client, choose nothing and if not on list choose New)</button></option>
            <option value='-1'>New</option>
            <?php
            foreach ($APNames as $key => $AP) { ?>
              <option value="<?php echo $AP['ID']; ?>"><?php echo '<b>Name:</b> ' . $AP['Name'] . ', <b>Relationship to Client:</b> ' . $AP['RelationshipToClient']; ?></option>
            <?php }  ?>
          </select>
        </div>

        <div class="col-md-6">
          <legend>Authorised Party 2</legend>
          <select id="AP2List" onchange="toggleForm('AP2');" class="form-select" name="AP2List">
            <option selected value='0'><button class="dropdown-item" type="button">Select Authorised Party 2 (If same as client, choose nothing and if not on list choose New)</button></option>
            <option value='-1'>New</option>

            <?php
            foreach ($APNames as $key => $AP) { ?>
              <option value="<?php echo $AP['ID']; ?>"><?php echo '<b>Name:</b> ' . $AP['Name'] . ', <b>Relationship to Client:</b> ' . $AP['RelationshipToClient']; ?></option>
            <?php }  ?>
          </select>
        </div>
      </div>
      <div class="row">
        <div id="inputNewAP1" style="display:none ;" class="col">

          <div class="col-md-8">
            <label class="form-label">Authorised Party 1 Name</label>
            <input type="text" class="form-control" id="APName1" name="APName1">
          </div>
          <div class="col-md-8">
            <label class="form-label">Relationship to Client</label>
            <input type="text" class="form-control" name="AP1RelationshipToC">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" id="AP1AddressLine1" name="AP1AddressLine1">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 2</label>
            <input type="text" class="form-control" id="AP1AddressLine2" name="AP1AddressLine2">
          </div>
          <div class="col-md-8">
            <label class="form-label">County</label>
            <input type="text" class="form-control" id="AP1County" name="AP1County">
          </div>
          <div class="col-md-8">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" id="AP1Postcode" name="AP1Postcode">
          </div>
          <div class="col-md-8">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="AP1Email" name="AP1Email">
          </div>
          <div class="col-md-8">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control" id="AP1ContactNumber" name="AP1ContactNumber">
          </div>
          <div class="mt-2 col-md-8">
            <div class="form-check">
              <label class="form-check-label">
                ID check
              </label>
              <input class="form-check-input" name="AP1IDCheck" type="checkbox">
            </div>
          </div>
          <div id="inputNewAP1" class="col-md-8">
            <label class="form-label">Date of ID check</label>
            <input type="date" class="form-control" name="AP1IDCheckDate">
          </div>
        </div>

        <div id="inputNewAP2" style="display:none ;" class="col">
          <div class="col-md-8">
            <label class="form-label">Authorised Party 2 Name</label>
            <input type="text" class="form-control" id="APName2" name="APName2">
          </div>
          <div class="col-md-8">
            <label class="form-label">Relationship to Client</label>
            <input type="text" class="form-control" name="AP2RelationshipToC">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" id="AP2AddressLine1" name="AP2AddressLine1">
          </div>
          <div class="col-md-8">
            <label class="form-label">Address Line 2</label>
            <input type="text" class="form-control" id="AP2AddressLine2" name="AP2AddressLine2">
          </div>
          <div class="col-md-8">
            <label class="form-label">County</label>
            <input type="text" class="form-control" id="AP2County" name="AP2County">
          </div>
          <div class="col-md-8">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" id="AP2Postcode" name="AP2Postcode">
          </div>
          <div class="col-md-8">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="AP2Email" name="AP2Email">
          </div>
          <div class="col-md-8">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control" id="AP2ContactNumber" name="AP2ContactNumber">
          </div>

          <div class="mt-2 col-md-8">
            <div class="form-check">
              <label class="form-check-label">
                ID check
              </label>
              <input class="form-check-input" name="AP2IDCheck" type="checkbox">
            </div>
          </div>
          <div id="inputNewAP2" class="col-md-8">
            <label class="form-label">Date of ID check</label>
            <input type="date" class="form-control" name="AP2IDCheckDate">
          </div>
        </div>
      </div>

      <legend>Other Side's Details</legend>
      <div class="col-md-6">

        <select id="OSList" onchange="toggleForm('OS');" class="form-select" name="OS_ID">
          <option selected value='0'><button class="dropdown-item" type="button">Select Other side(If not on the list choose New)</button></option>
          <option value='-1'>New</option>
          <?php
          foreach ($OSNames as $key => $OS) { ?>
            <option value="<?php echo $OS['ID']; ?>"><?php echo '<b>' . $OS['Name'] . '</b> Of <b>' . $OS['AddressLine1'] . ', ' . $OS['AddressLine2'] . ', ' . $OS['Ptcode'] . '</b> ' ?></option>
          <?php }  ?>
        </select>
      </div>
      <div class="row" id="inputNewOS" style="display:none;">
        <div class="col">
          <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="OSName">
          </div>
          <div class="col-md-4">
            <label class="form-label">Address Line 1</label>
            <input type="text" class="form-control" name="OSAddressLine1">
          </div>
          <div class="col-md-4">
            <label class="form-label">Address Line 2</label>
            <input type="text" class="form-control" name="OSAddressLine2">
          </div>
          <div class="col-md-4">
            <label class="form-label">County</label>
            <input type="text" class="form-control" name="OSCounty">
          </div>
          <div class="col-md-2">
            <label class="form-label">Postcode</label>
            <input type="text" class="form-control" name="OSPostcode">
          </div>
        </div>
        <div class="col">
          <div class="col-md-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="OSEmail">
          </div>
          <div class="col-md-3">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control" name="OSContactNumber">
          </div>

          <div class="col-md-4">
            <label class="form-label">Solicitors</label>
            <input type="text" class="form-control" name="OSSolicitors">
          </div>

          <div class="col-md-4">
            <label class="form-label">Solicitors Email</label>
            <input type="email" class="form-control" name="OSSolicitorsEmail">
          </div>
        </div>




      </div>





      <legend>Compliance</legend>

      <div class="col-md-2">
        <label class="form-label">Date Client Care Sent</label>
        <input type="date" class="form-control" name="DateOfClientCareSent">
      </div>

      <div class="col-md-3">
        <div class="form-check">
          <input class="form-check-input" name="TermsOfEng1" type="checkbox">
          <label class="form-check-label">
            Signed Terms Of Engagement Client 1
          </label>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="TermsOfEng2">
          <label class="form-check-label">
            Signed Terms Of Engagement Client 2
          </label>
        </div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Date Terms Of Engagement sent</label>
        <input type="date" class="form-control" name="DateOfToESent">
      </div>
      <div class="col-md-2">
        <label class="form-label">Date Terms Of Engagement received</label>
        <input type="date" class="form-control" name="DateOfToERcvd">
      </div>
      <div class="col-md-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="NCBA1">
          <label class="form-check-label">
            Signed NCBA Client 1
          </label>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="NCBA2">
          <label class="form-check-label">
            Signed NCBA Client 2
          </label>
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label">Date NCBA sent</label>
        <input type="date" class="form-control" name="DateOfNCBASent">
      </div>
      <div class="col-md-3">
        <label class="form-label">Date NCBA received</label>
        <input type="date" class="form-control" name="DateOfNCBARcvd">
      </div>


      <legend>Last Work</legend>
      <div class="col-md-4">
        <label class="form-label">Description of Last Work</label>
        <textarea type="text" class="form-control" name="DescLastWork"></textarea>
      </div>
      <div class="col-md-2">
        <label class="form-label">Person Last Worked</label>
        <select name="PersonLastWorked" class="form-select">
          <?php initialsList($con, "", false); ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Date Of Last Work</label>
        <input type="date" class="form-control" name="DateOfLastWork">
      </div>

      <legend>Next Work</legend>
      <div class="col-md-4">
        <label class="form-label">Person for Next Task</label>
        <select name="PersonNextTask" class="form-select">
          <?php initialsList($con, "", false); ?>

        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Date Of Next Work</label>
        <input type="date" class="form-control" name="DateOfNextWork">
      </div>
      <div class="col-md-10">
        <label class="form-label">Task Required</label>
        <textarea type="text" class="form-control" name="TaskRequired"></textarea>
      </div>
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="ActionRequired">
          <label class="form-check-label">
            Action Required
          </label>
        </div>
      </div>

      <div class="col-md-6">
        <label class="form-label">Next Key Date</label>
        <input type="date" class="form-control" name="NextKeyDate">
      </div>

      <div class="col-md-6" id='KeyInfoDiv'>
        <legend>Key Information</legend>

        <div class="row">
          <div class="col-sm">
            <textarea type="text" class="form-control" name="KeyInfo"></textarea>
          </div>

        </div>
      </div>
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
      </div>
      <div class="col-12">
        <label class="form-label">Comments</label>
        <textarea type="text" class="form-control" name="Comments"></textarea>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary">Make New Record</button>
      </div>
    </form>
  </div>
  <?php
  writeFooter();
  ?>
</body>

</html>
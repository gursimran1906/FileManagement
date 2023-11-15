<?php
include '../Connect.php';
print_r($_REQUEST);


$TermsOfEng1 = isset($_REQUEST['TermsOfEng1']) ? 1 : 0;
$TermsOfEng2 = isset($_REQUEST['TermsOfEng2']) ? 1 : 0;
$NCBA1 = isset($_REQUEST['NCBA1']) ? 1 : 0;
$NCBA2 = isset($_REQUEST['NCBA2']) ? 1 : 0;

$ActionRequired = isset($_REQUEST['ActionRequired']) ? 1 : 0;

$FeeEarner = mysqli_real_escape_string($con, $_REQUEST['FeeEarner']);
$Funding = mysqli_real_escape_string($con, $_REQUEST['Funding']);

$MatterDesc = mysqli_real_escape_string($con, $_REQUEST['MatterDesc']);
$MatterType = mysqli_real_escape_string($con, $_REQUEST['MatterType']);
$FileStatus = mysqli_real_escape_string($con, $_REQUEST['FileStatus']);
$FileLocation = mysqli_real_escape_string($con, $_REQUEST['FileLocation']);
$FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);

$DateOfClientCareSent = ($_REQUEST['DateOfClientCareSent'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['DateOfClientCareSent']) : '0000-00-00';
$DateOfToESent = ($_REQUEST['DateOfToESent'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['DateOfToESent']) : '0000-00-00';
$DateOfToERcvd = ($_REQUEST['DateOfToERcvd'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['DateOfToERcvd']) : '0000-00-00';
$DateOfNCBASent = ($_REQUEST['DateOfNCBASent'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['DateOfNCBASent']) : '0000-00-00';
$DateOfNCBARcvd = ($_REQUEST['DateOfNCBARcvd'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['DateOfNCBARcvd']) : '0000-00-00';


//$DateOfLastAML = ($_REQUEST['DateOfLastAML'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['DateOfLastAML']) : '0000-00-00';

$PersonLastWorked = mysqli_real_escape_string($con, $_REQUEST['PersonLastWorked']);
$DescOfLastWork = mysqli_real_escape_string($con, $_REQUEST['DescLastWork']);

$DateOfLastWork = ($_REQUEST['DateOfLastWork'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['DateOfLastWork']) : '0000-00-00';

$PersonForNext = mysqli_real_escape_string($con, $_REQUEST['PersonNextTask']);

$DateOfNextWork = ($_REQUEST['DateOfNextWork'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['DateOfNextWork']) : '0000-00-00';

$TaskRequired = mysqli_real_escape_string($con, $_REQUEST['TaskRequired']);

$NextKeyDate = ($_REQUEST['NextKeyDate'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['NextKeyDate']) : '0000-00-00';


$KeyInfo = mysqli_real_escape_string($con, $_REQUEST['KeyInfo']);
$Undertakings = json_encode(array_filter($_REQUEST['UndertakingDiv']));
$Comments = mysqli_real_escape_string($con, $_REQUEST['Comments']);


$Client1ID = mysqli_real_escape_string($con, $_REQUEST['client1List']);
$Client2ID = mysqli_real_escape_string($con, $_REQUEST['client2List']);

$AP1ID = mysqli_real_escape_string($con, $_REQUEST['AP1List']);
$AP2ID = mysqli_real_escape_string($con, $_REQUEST['AP2List']);

$OS_ID = mysqli_real_escape_string($con, $_REQUEST['OS_ID']);

if ($OS_ID == '-1') {
  $OSName = mysqli_real_escape_string($con, $_REQUEST['OSName']);
  $OSAddressLine1 = mysqli_real_escape_string($con, $_REQUEST['OSAddressLine1']);
  $OSAddressLine2 = mysqli_real_escape_string($con, $_REQUEST['OSAddressLine2']);
  $OSCounty = mysqli_real_escape_string($con, $_REQUEST['OSCounty']);
  $OSPostcode = mysqli_real_escape_string($con, $_REQUEST['OSPostcode']);
  $OSEmail = mysqli_real_escape_string($con, $_REQUEST['OSEmail']);
  $OSContactNumber = mysqli_real_escape_string($con, $_REQUEST['OSContactNumber']);
  $OSSolicitors = mysqli_real_escape_string($con, $_REQUEST['OSSolicitors']);
  $OSSolicitorsEmail = mysqli_real_escape_string($con, $_REQUEST['OSSolicitorsEmail']);



  $sql = "INSERT INTO OS_details (Name, AddressLine1, AddressLine2, County, Ptcode, Email, ContactNumber, Solicitors, SolicitorsEmail)
  VALUES ('$OSName', '$OSAddressLine1', '$OSAddressLine2', '$OSCounty', '$OSPostcode', '$OSEmail', '$OSContactNumber', '$OSSolicitors', '$OSSolicitorsEmail')";

  mysqli_query($con, $sql);
  $OSDetails_ID = mysqli_insert_id($con);
} else {
  $OSDetails_ID = $OS_ID;
}



if ($Client1ID == '-1') {
  // Client 1 Contact Details
  $Client1Name = mysqli_real_escape_string($con, $_REQUEST['ClientName1']);
  $Client1DOB = mysqli_real_escape_string($con, $_REQUEST['Client1DOB']);
  $Client1AddressLine1 = mysqli_real_escape_string($con, $_REQUEST['Client1AddressLine1']);
  $Client1AddressLine2 = mysqli_real_escape_string($con, $_REQUEST['Client1AddressLine2']);
  $Client1County = mysqli_real_escape_string($con, $_REQUEST['Client1County']);
  $Client1Postcode = mysqli_real_escape_string($con, $_REQUEST['Client1Postcode']);
  $Client1Email = mysqli_real_escape_string($con, $_REQUEST['Client1Email']);
  $Client1ContactNumber = mysqli_real_escape_string($con, $_REQUEST['Client1ContactNumber']);
  $Client1AMLCheckDate = ($_REQUEST['Client1AMLCheckDate'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['Client1AMLCheckDate']) : '0000-00-00';

  $IDVer1 = isset($_REQUEST['IDVer1']) ? 1 : 0;


  $sql = "INSERT INTO client_contact_details 
    (ClientName, 
    DOB,
    AddressLine1,
    AddressLine2,
    County,
    Postcode,
    Email,
    ContactNumber,
    DateOfLastAML, 
    IdVerified) VALUES
    ('$Client1Name',
    '$Client1DOB',
    '$Client1AddressLine1',
    '$Client1AddressLine2',
    '$Client1County',
    '$Client1Postcode',
    '$Client1Email',
    '$Client1ContactNumber',
    '$Client1AMLCheckDate',
    '$IDVer1')";
  mysqli_query($con, $sql);
  $Client1Contact_ID = mysqli_insert_id($con);
} else {
  $Client1Contact_ID = $Client1ID;
}
echo $Client2ID;

// Client 2 Contact Details
if ($Client2ID == '-1') {


  $Client2Name = mysqli_real_escape_string($con, $_REQUEST['ClientName2']);
  if ($Client2Name != null) {
    $Client2DOB = mysqli_real_escape_string($con, $_REQUEST['Client1DOB']);
    $Client2AddressLine1 = mysqli_real_escape_string($con, $_REQUEST['Client2AddressLine2']);
    $Client2AddressLine2 = mysqli_real_escape_string($con, $_REQUEST['Client2AddressLine2']);
    $Client2County = mysqli_real_escape_string($con, $_REQUEST['Client2County']);
    $Client2Postcode = mysqli_real_escape_string($con, $_REQUEST['Client2Postcode']);
    $Client2Email = mysqli_real_escape_string($con, $_REQUEST['Client2Email']);
    $Client2ContactNumber = mysqli_real_escape_string($con, $_REQUEST['Client2ContactNumber']);
    $Client2AMLCheckDate = ($_REQUEST['Client2AMLCheckDate'] !== '') ? mysqli_real_escape_string($con, $_REQUEST['Client2AMLCheckDate']) : '0000-00-00';

    $IDVer2 = isset($_REQUEST['IDVer2']) ? 1 : 0;



    $sql = "INSERT INTO client_contact_details 
       (ClientName, 
       DOB,
       AddressLine1,
       AddressLine2,
       County,
       Postcode,
       Email,
       ContactNumber,
       DateOfLastAML, 
      IdVerified) VALUES
       ('$Client2Name',
       '$Client2DOB',
       '$Client2AddressLine1',
       '$Client2AddressLine2',
       '$Client2County',
       '$Client2Postcode',
       '$Client2Email',
       '$Client2ContactNumber',
       '$Client2AMLCheckDate',
        '$IDVer2')";
    mysqli_query($con, $sql);
    $Client2Contact_ID = mysqli_insert_id($con);
  }
} else {
  $Client2Contact_ID = $Client2ID;
}

if ($AP1ID == '-1') {
  // AP 1 Contact Details
  $AP1Name = mysqli_real_escape_string($con, $_REQUEST['APName1']);
  $AP1RelationshipToC = mysqli_real_escape_string($con, $_REQUEST['AP1RelationshipToC']);
  $AP1AddressLine1 = mysqli_real_escape_string($con, $_REQUEST['AP1AddressLine1']);
  $AP1AddressLine2 = mysqli_real_escape_string($con, $_REQUEST['AP1AddressLine2']);
  $AP1County = mysqli_real_escape_string($con, $_REQUEST['AP1County']);
  $AP1Postcode = mysqli_real_escape_string($con, $_REQUEST['AP1Postcode']);
  $AP1Email = mysqli_real_escape_string($con, $_REQUEST['AP1Email']);
  $AP1ContactNumber = mysqli_real_escape_string($con, $_REQUEST['AP1ContactNumber']);
  $AP1IDCheck = isset($_REQUEST['AP1IDCheck']) ? 1 : 0;
  $AP1DateofIDCheck = mysqli_real_escape_string($con, $_REQUEST['AP1IDCheckDate']);


  $sql = "INSERT INTO authorised_party_contact_details 
    (Name, 
    RelationshipToClient,
    AddressLine1,
    AddressLine2,
    County,
    Postcode,
    Email,
    ContactNumber,
    IDCheck,
    DateOfIDCheck) VALUES
    ('$AP1Name',
    '$AP1RelationshipToC',
    '$AP1AddressLine1',
    '$AP1AddressLine2',
    '$AP1County',
    '$AP1Postcode',
    '$AP1Email',
    '$AP1ContactNumber',
    '$AP1IDCheck',
    '$AP1DateofIDCheck'
    )";
  mysqli_query($con, $sql);
  $AP1Contact_ID = mysqli_insert_id($con);
} else {
  $AP1Contact_ID = $AP1ID;
}

if ($AP2ID == '-1') {
  // AP 2 Contact Details
  $AP2Name = mysqli_real_escape_string($con, $_REQUEST['APName2']);
  $AP2RelationshipToC = mysqli_real_escape_string($con, $_REQUEST['AP2RelationshipToC']);
  $AP2AddressLine2 = mysqli_real_escape_string($con, $_REQUEST['AP2AddressLine2']);
  $AP2AddressLine2 = mysqli_real_escape_string($con, $_REQUEST['AP2AddressLine2']);
  $AP2County = mysqli_real_escape_string($con, $_REQUEST['AP2County']);
  $AP2Postcode = mysqli_real_escape_string($con, $_REQUEST['AP2Postcode']);
  $AP2Email = mysqli_real_escape_string($con, $_REQUEST['AP2Email']);
  $AP2ContactNumber = mysqli_real_escape_string($con, $_REQUEST['AP2ContactNumber']);
  $AP2IDCheck = isset($_REQUEST['AP2IDCheck']) ? 1 : 0;
  $AP2DateofIDCheck = mysqli_real_escape_string($con, $_REQUEST['AP2IDCheckDate']);

  $sql = "INSERT INTO authorised_party_contact_details 
    (Name, 
    RelationshipToClient,
    AddressLine1,
    AddressLine2,
    County,
    Postcode,
    Email,
    ContactNumber,
    IDCheck,
    DateOfIDCheck) VALUES
    ('$AP2Name',
    '$AP2RelationshipToC',
    '$AP2AddressLine1',
    '$AP2AddressLine2',
    '$AP2County',
    '$AP2Postcode',
    '$AP2Email',
    '$AP2ContactNumber',
    '$AP2IDCheck',
    '$AP2DateofIDCheck'
    )";
  mysqli_query($con, $sql);
  $AP2Contact_ID = mysqli_insert_id($con);
} else {
  $AP2Contact_ID = $AP2ID;
}


echo $DateOfToESent;

$sql = "INSERT INTO wip (FileNumber,
    FeeEarner,
    MatterDescription,
    Client1Contact_ID,
    Client2Contact_ID,
    MatterType,
    FileStatus,
    FileLocation,
    OtherSideDetails,

    DateOfClientCareSent,
    TermsOfEngagementClient1,
    TermsOfEngagementClient2,
    DateOfToESent,
    DateOfToERcvd,
    NCBAClient1,
    NCBAClient2,
    DateOfNCBASent,
    DateOfNCBARcvd,
    
   
    Funding,
    AuthorisedParty1_ID,
    AuthorisedParty2_ID,
    PersonLastWorked,
    DescOfLastWork,
    DateOfLastWork,
    PersonForNextTask,
    TasksRequired,
    DateOfNextWork,
    ActionRequired,
    NextKeyDate,
    KeyInformation,
    Undertakings,
    Comments) 
    VALUES 
    ('$FileNumber',
    '$FeeEarner',
    '$MatterDesc',
    $Client1Contact_ID,
    $Client2Contact_ID,
    '$MatterType',
    '$FileStatus',
    '$FileLocation',
    $OSDetails_ID,
   
    '$DateOfClientCareSent',
    '$TermsOfEng1',
    '$TermsOfEng2',
    '$DateOfToESent',
    '$DateOfToERcvd',
    '$NCBA1',
    '$NCBA2',
    '$DateOfNCBASent',
    '$DateOfNCBARcvd',
    
    
    '$Funding',
    $AP1Contact_ID,
    $AP2Contact_ID,
    '$PersonLastWorked',
    '$DescOfLastWork',
    '$DateOfLastWork',
    '$PersonForNext',
    '$TaskRequired',
    '$DateOfNextWork',
    '$ActionRequired',
    '$NextKeyDate',
    '$KeyInfo',
    '$Undertakings',
    '$Comments')";

try {
  mysqli_query($con, $sql);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}




//header("Location: ../Home.php?filenumber=$FileNumber");
//exit();

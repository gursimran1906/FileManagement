<?php
include '../Connect.php';


    $ClientCareLetter = isset($_REQUEST['ClientCareLetter']) ? 1 : 0;
    $TermsOfEng1 = isset($_REQUEST['TermsOfEng1']) ? 1 : 0;
    $TermsOfEng2 = isset($_REQUEST['TermsOfEng2']) ? 1 : 0;
    $NCBA1 = isset($_REQUEST['NCBA1']) ? 1 : 0;
    $NCBA2 = isset($_REQUEST['NCBA2']) ? 1 : 0;
    $IDVer1 = isset($_REQUEST['IDVer1']) ? 1 : 0;
    $IDVer2 = isset($_REQUEST['IDVer2']) ? 1 : 0;
    $ActionRequired = isset($_REQUEST['ActionRequired']) ? 1 : 0;

    $Supervisor = mysqli_real_escape_string($con, $_REQUEST['Supervisor']);
    $MatterDesc = mysqli_real_escape_string($con, $_REQUEST['MatterDesc']); 
     
    $MatterType = mysqli_real_escape_string($con, $_REQUEST['MatterType']);
    $FileStatus = mysqli_real_escape_string($con, $_REQUEST['FileStatus']); 
    $FileLocation = mysqli_real_escape_string($con, $_REQUEST['FileLocation']); 
    $FileNumber = mysqli_real_escape_string($con, $_REQUEST['FileNumber']);
    $DateOfLastAML = mysqli_real_escape_string($con, $_REQUEST['DateOfLastAML']); 
    $PersonLastWorked = mysqli_real_escape_string($con, $_REQUEST['PersonLastWorked']); 
    $DescOfLastWork = mysqli_real_escape_string($con, $_REQUEST['DescLastWork']);
    $DateOfLastWork = mysqli_real_escape_string($con, $_REQUEST['DateOfLastWork']);
    $PersonForNext = mysqli_real_escape_string($con, $_REQUEST['PersonNextTask']); 
    $DateOfNextWork =mysqli_real_escape_string($con, $_REQUEST['DateOfNextWork']); 
    $TaskRequired = mysqli_real_escape_string($con, $_REQUEST['TaskRequired']);
    $NextKeyDate = mysqli_real_escape_string($con, $_REQUEST['NextKeyDate']); 
    $Comments = mysqli_real_escape_string($con, $_REQUEST['Comments']);    

$sql = "SELECT Client1Contact_ID, Client2Contact_ID FROM wip WHERE FileNumber= '$FileNumber' LIMIT 1";
$result = mysqli_query($con, $sql);

$IDs = $result->fetch_assoc();
$client1ContactID = $IDs['Client1Contact_ID'];
$client2ContactID = $IDs['Client2Contact_ID'];
// $client1ContactID = $IDs['Client1Contact_ID'];
// $client2ContactID = $IDs['Client2Contact_ID'];

// Client 1 Updating

$Client1Name = mysqli_real_escape_string($con, $_REQUEST['ClientName1']);
$Client1AddressLine1 = mysqli_real_escape_string($con, $_REQUEST ['Client1AddressLine1']);
$Client1AddressLine2 = mysqli_real_escape_string($con, $_REQUEST ['Client1AddressLine2']);
$Client1County= mysqli_real_escape_string($con, $_REQUEST ['Client1County']);
$Client1Postcode = mysqli_real_escape_string($con, $_REQUEST ['Client1Postcode']);
$Client1Email = mysqli_real_escape_string($con, $_REQUEST ['Client1Email']);
$Client1ContactNumber = mysqli_real_escape_string($con, $_REQUEST ['Client1ContactNumber']);

if($client1ContactID == 0 AND $Client1Name != null){
   $sql = "INSERT INTO client_contact_details 
   (ClientName, 
   AddressLine1,
   AddressLine2,
   County,
   Postcode,
   Email,
   ContactNumber) VALUES
   ('$Client1Name',
   '$Client1AddressLine1',
   '$Client1AddressLine2',
   '$Client1County',
   '$Client1Postcode',
   '$Client1Email',
   '$Client1ContactNumber')";
   
   mysqli_query($con,$sql);
     
  $client1ContactID = mysqli_insert_id($con);
}
else{
   $sql = "UPDATE client_contact_details SET 
ClientName = '$Client1Name',
AddressLine1='$Client1AddressLine1',
AddressLine2='$Client1AddressLine2',
County='$Client1County',
Postcode='$Client1Postcode',
Email='$Client1Email',
ContactNumber='$Client1ContactNumber'
WHERE ID='$client1ContactID'";
mysqli_query($con,$sql);
  

}
// Client 2 Updating

$Client2Name = mysqli_real_escape_string($con, $_REQUEST['ClientName2']);
$Client2AddressLine1 = mysqli_real_escape_string($con, $_REQUEST ['Client2AddressLine2']);
$Client2AddressLine2 = mysqli_real_escape_string($con, $_REQUEST ['Client2AddressLine2']);
$Client2County= mysqli_real_escape_string($con, $_REQUEST ['Client2County']);
$Client2Postcode = mysqli_real_escape_string($con, $_REQUEST ['Client2Postcode']);
$Client2Email = mysqli_real_escape_string($con, $_REQUEST ['Client2Email']);
$Client2ContactNumber = mysqli_real_escape_string($con, $_REQUEST ['Client2ContactNumber']);

echo $Client2Name;
if($client2ContactID == 0 AND $Client2Name != null){
       
       $sql = "INSERT INTO client_contact_details 
       (ClientName, 
       AddressLine1,
       AddressLine2,
       County,
       Postcode,
       Email,
       ContactNumber) VALUES
       ('$Client2Name',
       '$Client2AddressLine1',
       '$Client2AddressLine2',
       '$Client2County',
       '$Client2Postcode',
       '$Client2Email',
       '$Client2ContactNumber')";
       
       mysqli_query($con,$sql);
         
      $client2ContactID = mysqli_insert_id($con);
       
}
else{
   $sql = "UPDATE client_contact_details SET 
   ClientName = '$Client2Name',
   AddressLine1='$Client2AddressLine1',
   AddressLine2='$Client2AddressLine2',
   County='$Client2County',
   Postcode='$Client2Postcode',
   Email='$Client2Email',
   ContactNumber='$Client2ContactNumber'
   WHERE ID='$client2ContactID'";
   mysqli_query($con,$sql);
   
}


 $sql = "UPDATE wip SET FileNumber='$FileNumber', Supervisor='$Supervisor', MatterDescription='$MatterDesc', Client1Contact_ID='$client1ContactID', Client2Contact_ID='$client2ContactID', MatterType='$MatterType', FileStatus='$FileStatus', FileLocation='$FileLocation', ClientCareLetter='$ClientCareLetter', TermsOfEngagementClient1='$TermsOfEng1', TermsOfEngagementClient2='$TermsOfEng2', NCBAClient1='$NCBA1', NCBAClient2='$NCBA2', IDVerifiedClient1='$IDVer1', IDVerifiedClient2='$IDVer2', DateOfAMLReport='$DateOfLastAML', PersonLastWorked='$PersonLastWorked', DescOfLastWork='$DescOfLastWork', DateOfLastWork='$DateOfLastWork', PersonForNextTask='$PersonForNext', TasksRequired='$TaskRequired', DateOfNextWork='$DateOfNextWork', ActionRequired='$ActionRequired', NextKeyDate='$NextKeyDate', Comments='$Comments' WHERE FileNumber= '$FileNumber'";


 if(mysqli_query($con,$sql)){
    header("Location: ../Home.php?filenumber=$FileNumber");
    exit();   
 }
 else{
   header("Location: ../custom_404.php");
 }

    
 
 
 

 

 


?>
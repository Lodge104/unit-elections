<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include '../unitelections-info.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['unitId'])) { $unitId = $_POST['unitId']; } else { die("No unit id."); }
if (isset($_POST['accessKey'])) { $accessKey = $_POST['accessKey']; } else { die("No unit key."); }
if (isset($_POST['unitNumber'])) {  $unitNumber = $_POST['unitNumber']; } else { $unitNumber = ""; }
if (isset($_POST['unitCommunity'])) {  $unitCommunity = $_POST['unitCommunity']; } else { $unitCommunity = ""; }
if (isset($_POST['numRegisteredYouth'])) {  $numRegisteredYouth = $_POST['numRegisteredYouth']; } else { $numRegisteredYouth = ""; }
if (isset($_POST['dateOfElection'])) {  $dateOfElection = $_POST['dateOfElection']; } else { $dateOfElection = ""; }
if (isset($_POST['chapter'])) {  $chapter = $_POST['chapter']; } else { $chapter = ""; }

if (isset($_POST['open'])) {  $open = $_POST['open']; } else { $open = ""; }
if (isset($_POST['sm_name'])) {  $sm_name = $_POST['sm_name']; } else { $sm_name = ""; }
if (isset($_POST['sm_address_line1'])) {  $sm_address_line1 = $_POST['sm_address_line1']; } else { $sm_address_line1 = ""; }
if (isset($_POST['sm_address_line2'])) {  $sm_address_line2 = $_POST['sm_address_line2']; } else { $sm_address_line2 = ""; }
if (isset($_POST['sm_city'])) {  $sm_city = $_POST['sm_city']; } else { $sm_city = ""; }
if (isset($_POST['sm_state'])) {  $sm_state = $_POST['sm_state']; } else { $sm_state = ""; }
if (isset($_POST['sm_zip'])) {  $sm_zip = $_POST['sm_zip']; } else { $sm_zip = ""; }
if (isset($_POST['sm_email'])) {  $sm_email = $_POST['sm_email']; } else { $sm_email = ""; }
if (isset($_POST['sm_phone'])) {  $sm_phone = $_POST['sm_phone']; } else { $sm_phone = ""; }


$updateElection = $conn->prepare("UPDATE unitElections SET open=?,sm_name=?,sm_address_line1=?,sm_address_line2=?,sm_city=?,sm_state=?,sm_zip=?,sm_email=?,sm_phone=?,numRegisteredYouth=? WHERE id = ?");
$updateElection->bind_param("sssssssssss", $open, $sm_name, $sm_address_line1, $sm_address_line2, $sm_city, $sm_state, $sm_zip, $sm_email, $sm_phone, $numRegisteredYouth, $unitId);
$updateElection->execute();
$updateElection->close();

$updateElection1 = $conn->prepare("UPDATE unitElections SET unitNumber=?, unitCommunity=?, dateOfElection=?, chapter=? WHERE id = ?");
$updateElection1->bind_param("sssss", $unitNumber, $unitCommunity, $dateOfElection, ucfirst($chapter), $unitId);
$updateElection1->execute();
$updateElection1->close();


header("Location: index.php?status=1");

?>

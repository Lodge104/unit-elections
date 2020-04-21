<?php
include '../unitelections-info.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['unitId'])) {  $unitId = $_POST['unitId']; } else { die("No unit id."); }
if (isset($_POST['eligibleScoutId'])) {  $eligibleScoutId = $_POST['eligibleScoutId']; } else { $eligibleScoutId = "new"; }
if (isset($_POST['firstName'])) {  $firstName = $_POST['firstName']; }
if (isset($_POST['lastName'])) {  $lastName = $_POST['lastName']; }
if (isset($_POST['dob'])) {  $dob = $_POST['dob']; }
if (isset($_POST['rank'])) {  $rank = $_POST['rank']; }
if (isset($_POST['address_line1'])) {  $address_line1 = $_POST['address_line1']; }
if (isset($_POST['address_line2'])) {  $address_line2 = $_POST['address_line2']; }
if (isset($_POST['city'])) {  $city = $_POST['city']; }
if (isset($_POST['state'])) {  $state = $_POST['state']; }
if (isset($_POST['zip'])) {  $zip = $_POST['zip']; }
if (isset($_POST['email'])) {  $email = $_POST['email']; }
if (isset($_POST['phone'])) {  $phone = $_POST['phone']; }

for ($i = 0; $i < count($eligibleScoutId); $i++) {
  if ($eligibleScoutId[$i] == "new") {
    if ($firstName[$i]=="" && $lastName[$i]=="" && $dob[$i]=="" && $rank[$i]==""
        && $address_line1[$i]=="" && $address_line2[$i]=="" && $city[$i]==""
        && $state[$i]=="" && $zip[$i]=="" && $email[$i]=="" && $phone[$i]=="") {} else {
          $insertScoutQuery = $conn->prepare("INSERT INTO eligibleScouts(unitId,lastName,firstName,rank,dob,address_line1,address_line2,city,state,zip,phone,email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
          $insertScoutQuery->bind_param("ssssssssssss", $unitId,$lastName[$i],$firstName[$i],$rank[$i],$dob[$i],$address_line1[$i],$address_line2[$i],$city[$i],$state[$i],$zip[$i],$phone[$i],$email[$i]);
          $insertScoutQuery->execute();
          $insertScoutQuery->close();
    }
  } else {
    if ($eligibleScoutId[$i] >= 0 && $eligibleScoutId[$i] !== "") {
      if ($firstName[$i]=="" && $lastName[$i]=="" && $dob[$i]=="" && $rank[$i]==""
          && $address_line1[$i]=="" && $address_line2[$i]=="" && $city[$i]==""
          && $state[$i]=="" && $zip[$i]=="" && $email[$i]=="" && $phone[$i]=="") {
            $deleteScoutQuery = $conn->prepare("DELETE from eligibleScouts WHERE id = ?");
            $deleteScoutQuery->bind_param("s", $eligibleScoutId[$i]);
            $deleteScoutQuery->execute();
            $deleteScoutQuery->close();
      } else {
        $updateScoutQuery = $conn->prepare("UPDATE eligibleScouts SET lastName=?,firstName=?,rank=?,dob=?,address_line1=?,address_line2=?,city=?,state=?, zip=?,phone=?, email=? WHERE id = ?");
        $updateScoutQuery->bind_param("ssssssssssss",$lastName[$i],$firstName[$i],$rank[$i],$dob[$i],$address_line1[$i],$address_line2[$i],$city[$i],$state[$i],$zip[$i],$phone[$i],$email[$i],$eligibleScoutId[$i]);
        $updateScoutQuery->execute();
        $updateScoutQuery->close();
      }
    }
  }
}
//var_dump(get_defined_vars());
if (isset($_POST['accessKey'])) {
  $accessKey = $_POST['accessKey'];
  header("Location: index.php?accessKey=" . $accessKey . "&status=1");
} else {
  header("Location: index.php");
}

?>

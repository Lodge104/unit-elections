<?php
include '../unitelections-info.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['unitId'])) { $unitId = $_POST['unitId']; } else { die("No unit id."); }
if (isset($_POST['accessKey'])) { $accessKey = $_POST['accessKey']; } else { die("No unit key."); }
if (isset($_POST['advisor_signature'])) {  $advisor_signature = $_POST['advisor_signature']; } else { $advisor_signature = ""; }



$createAdult = $conn->prepare("UPDATE adultNominations SET advisor_signature=? WHERE id = ?");
$createAdult->bind_param("ss", $advisor_signature, $unitId);
$createAdult->execute();
$createAdult->close();


header("Location: index.php?status=1");

?>
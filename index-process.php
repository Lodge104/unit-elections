<?php

if (isset($_POST['submit'])) {

    include 'unitelections-info.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    if (isset($_POST['unitId'])) {  $unitId = $conn->real_escape_string($_POST['unitId']); } else { die("No unit id."); }

    $createSubmission = $conn->prepare("INSERT into submissions(unitId) VALUES (?)");
    $createSubmission->bind_param("s", $unitId);
    $createSubmission->execute();
    $submissionId = $createSubmission->insert_id;
    $createSubmission->close();


    if (isset($_POST['eligibleScouts'])) {
      $eligibleScoutsArray = explode(',', $_POST['eligibleScouts']);
    }

    foreach($eligibleScoutsArray as $scoutId) {
      if (isset($_POST['eligibleScout-' . $scoutId])) {
        if ($_POST['eligibleScout-' . $scoutId] == "1") {
          //vote
          $voteQuery = $conn->prepare("INSERT into votes(unitId, submissionId, scoutId) VALUES (?,?,?)");
          $voteQuery->bind_param("sss", $unitId, $submissionId, $scoutId);
          $voteQuery->execute();
          $voteQuery->close();
        } else {
          //no vote
        }
      }
    }
    if (isset($_POST['accessKey'])) {
      $accessKey = $_POST['accessKey'];
      setcookie($accessKey, "voted-" . time(), time() + (86400 * 2)); //prevent voting for 2 days
    }
    header("Location: thanks.php");
} else {
    header("Location: index.php");
}

?>

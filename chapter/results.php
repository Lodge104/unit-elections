<?php
$title = "Election Results | Chapter Election Portal | Occoneechee Lodge - Order of the Arrow, BSA";
$userrole = "Standard User"; // Allow only logged in users
include "../login/misc/pagehead.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include '../unitelections-info.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tz = 'America/New_York';
$timestamp = time();
$dt = new DateTime("now", new DateTimeZone($tz));
$dt->setTimestamp($timestamp);

$date = $dt->format("Y-m-d");
$hour = $dt->format("H");

$unitQuery = $conn->prepare("SELECT * from unitElections");
$unitQuery->execute();
$unitQ = $unitQuery->get_result();
$neededForElection = 0;
if ($unitQ->num_rows > 0) {
  while ($unit = $unitQ->fetch_assoc()) {
    $unitArr[$unit['accessKey']] = array('id' => $unit['id'], 'unitNumber' => $unit['unitNumber'], 'unitCommunity' => $unit['unitCommunity']);
    if ((strtotime($unit['dateOfElection']) < strtotime($date)) || (strtotime($unit['dateOfElection']) == strtotime($date) && $hour >= 21)) {
      //unit election is over
      $submissionsQuery = $conn->prepare("SELECT COUNT(*) AS unitTotal FROM submissions WHERE unitId=?");
      $submissionsQuery->bind_param("s", $unit['id']);
      $submissionsQuery->execute();
      $submissionsQ = $submissionsQuery->get_result();
      if ($submissionsQ->num_rows > 0) {
        $submissions = $submissionsQ->fetch_assoc();
        if ($submissions['unitTotal'] > 0) {
          $neededForElection = intval($submissions['unitTotal']/2)+1;
          $eligibleScoutsQuery = $conn->prepare("SELECT * from eligibleScouts where unitId = ?");
          $eligibleScoutsQuery->bind_param("s", $unit['id']);
          $eligibleScoutsQuery->execute();
          $eligibleScoutsQ = $eligibleScoutsQuery->get_result();
          if ($eligibleScoutsQ->num_rows > 0) {
            while ($eligibleScout = $eligibleScoutsQ->fetch_assoc()) {
              $getVotesQuery = $conn->prepare("SELECT COUNT(*) AS voteTotal FROM votes WHERE scoutId = ?");
              $getVotesQuery->bind_param("s", $eligibleScout['id']);
              $getVotesQuery->execute();
              $getVotesQ = $getVotesQuery->get_result();
              if ($getVotesQ->num_rows > 0) {
                $getVotes = $getVotesQ->fetch_assoc();
                if ($getVotes['voteTotal'] >= $neededForElection) {
                  //set isElected to true
                  $updateScoutQuery = $conn->prepare("UPDATE eligibleScouts SET isElected = 1 WHERE id = ?");
                  $updateScoutQuery->bind_param("s", $eligibleScout['id']);
                } else {
                  //set isElected to false
                  $updateScoutQuery = $conn->prepare("UPDATE eligibleScouts SET isElected = 0 WHERE id = ?");
                  $updateScoutQuery->bind_param("s", $eligibleScout['id']);
                }
                $updateScoutQuery->execute();
                $updateScoutQuery->close();
              }
            }
          }
        }
      }
    } else {
      //unit election is still happening or hasn't happened yet.
    }
  }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=X-UA-Compatible content="IE=Edge,chrome=1" />
    <meta name=viewport content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />

    <title>Results | Unit Elections Administration | Occoneechee Lodge - Order of the Arrow, BSA</title>

    <link rel="stylesheet" href="../libraries/fontawesome-free-5.12.0/css/all.min.css">

</head>

<body id="dashboard">
	<?php require '../login/misc/pullnav.php'; ?>
  <div class="wrapper">

    <main class="container-fluid">
      <div class="card mb-3">
        <div class="card-body">
          <?php
          if (isset($_GET['accessKey'])) {
            if (preg_match("/^([a-z\d]){8}-([a-z\d]){4}-([a-z\d]){4}-([a-z\d]){4}-([a-z\d]){12}$/", $_GET['accessKey']) || $_GET['accessKey'] == "all") {
              $accessKey = $_POST['accessKey'] = $_GET['accessKey'];
            } else {
              $accessKey = "all";
            }
          } else { $accessKey = "all"; }

          if ($accessKey == "all") {
            ?>
		<div class="col-sm-4"></div>
        <div class="col-sm-4">
                <h3 class="card-title">Access Key </h3>
                <form action='' method="get">
                  <div class="form-group">
                    <label for="accessKey" class="required">Access Key</label>
                    <input type="text" id="accessKey" name="accessKey" class="form-control" required>
                  </div>
                  <input type="submit" class="btn btn-primary" value="Submit">
                </form>
			<div class="col-sm-4"></div>
              </div>
            </div>
		  </div><?php
            $getElectedScoutsQuery = $conn->prepare();
          } else {
            if (array_key_exists($accessKey, $unitArr)) {
              ?><h3 class="card-title">Elected Scouts from <?php echo $unitArr[$accessKey]['unitCommunity'] . " ". $unitArr[$accessKey]['unitNumber']; ?></h3><?php

              //get all elected scouts from unit
              $getElectedScoutsQuery = $conn->prepare("SELECT * from eligibleScouts LEFT JOIN unitElections on eligibleScouts.unitId = unitElections.id WHERE isElected = 1 and unitElections.accessKey = ?");
              $getElectedScoutsQuery->bind_param("s", $accessKey);
            } else {
              //no election exists
            }
          }
          $getElectedScoutsQuery->execute();
          $getElectedScouts = $getElectedScoutsQuery->get_result();
          if ($getElectedScouts->num_rows > 0) {
            ?><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr><?php
                    if ($accessKey == "all") {
                      ?><th scope="col">Last Name</th>
                      <th scope="col">First Name</th>
                      <th scope="col">BSA ID</th>
                      <th scope="col">Unit Type</th>
                      <th scope="col">Unit Number</th>
                      <th scope="col">Chapter</th>
                      <th scope="col">Date of Election</th><?php
                    } else {
                      ?><th scope="col">Last Name</th>
                      <th scope="col">First Name</th>
                      <th scope="col">BSA ID</th>
                      <th scope="col">Date of Election</th><?php
                    }
                  ?></tr>
                  </thead>
                  <tbody>
                    <?php while ($electedScout = $getElectedScouts->fetch_assoc()) {
                      $data[] = array('lastName' => $electedScout['lastName'],
                        'firstName' => $electedScout['firstName'],
                        'bsa_id' => $electedScout['bsa_id'],
                        'dob' => $electedScout['dob'],
                        'address_line1' => $electedScout['address_line1'],
                        'address_line2' => $electedScout['address_line2'],
                        'city' => $electedScout['city'],
                        'state' => $electedScout['state'],
                        'zip' => $electedScout['zip'],
                        'phone' => $electedScout['phone'],
                        'email' => $electedScout['email'],
                        'unitNumber' => $electedScout['unitNumber'],
                        'unitCommunity' => $electedScout['unitCommunity'],
                        'chapter' => $electedScout['chapter'],
                        'dateOfElection' => date("m-d-Y", strtotime($electedScout['dateOfElection']))
                      );
                      ?><tr>
                          <td><?php echo $electedScout['lastName']; ?></td>
                          <td><?php echo $electedScout['firstName']; ?></td>
                          <td><?php echo $electedScout['bsa_id']; ?></td>
                          <?php if ($accessKey == "all") {
                            ?>
                            <td><?php echo $electedScout['unitCommunityr']; ?></td>
                            <td><?php echo $electedScout['unitNumber']; ?></td>
                            <td><?php echo $electedScout['chapter']; ?></td>
                            <?php
                          }
                          ?>
                          <td><?php echo date("m-d-Y", strtotime($electedScout['dateOfElection'])); ?></td>
                      </tr><?php
                    } ?>
                  </tbody>
                </table>
              </div><?php
            } else {
              ?>
              <div class="mt-4 alert alert-danger" role="alert">
                There are no elected Scouts.
              </div>
              <?php
            }
          $conn->close();
          ?>
        </div>
      </div>
    </main>
  </div>
    <?php include "../footer.php"; ?>

    <script src="../libraries/jquery-3.4.1.min.js"></script>
    <script src="../libraries/popper-1.16.0.min.js"></script>
    <script src="../libraries/bootstrap-4.4.1/js/bootstrap.min.js"></script>

</body>

</html>

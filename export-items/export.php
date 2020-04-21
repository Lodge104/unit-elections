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

  if (isset($_GET['accessKey'])) {
    if (preg_match("/^([a-z\d]){8}-([a-z\d]){4}-([a-z\d]){4}-([a-z\d]){4}-([a-z\d]){12}$/", $_GET['accessKey']) || $_GET['accessKey'] == "all") {
      $accessKey = $_POST['accessKey'] = $_GET['accessKey'];
    } else {
      $accessKey = "all";
    }
  } else { $accessKey = "all"; }

  $unitFileName = "";

  if ($accessKey == "all") {
    $getElectedScoutsQuery = $conn->prepare("SELECT * from eligibleScouts LEFT JOIN unitElections on eligibleScouts.unitId = unitElections.id WHERE isElected = 1");
    $unitFileName = "all";
  } else {
    if (array_key_exists($accessKey, $unitArr)) {
      //get all elected scouts from unit
      $getElectedScoutsQuery = $conn->prepare("SELECT * from eligibleScouts LEFT JOIN unitElections on eligibleScouts.unitId = unitElections.id WHERE isElected = 1 and unitElections.accessKey = ?");
      $getElectedScoutsQuery->bind_param("s", $accessKey);
      $unitFileName = $unitArr[$accessKey]['unitNumber'] . $unitArr[$accessKey]['unitCommunity'];
    } else {
      //no election exists
    }
  }
  $getElectedScoutsQuery->execute();
  $getElectedScouts = $getElectedScoutsQuery->get_result();
  if ($getElectedScouts->num_rows > 0) {
    while ($electedScout = $getElectedScouts->fetch_assoc()) {
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
                'unitCommunity' => $electedScout['unitCommunity'],
                'unitNumber' => $electedScout['unitNumber'],
                'chapter' => $electedScout['chapter'],
                'dateOfElection' => date("m-d-Y", strtotime($electedScout['dateOfElection']))
              );
            }
    } else {

    }
  $conn->close();

  function outputCSV($data,$file_name) {
       # output headers so that the file is downloaded rather than displayed
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=$file_name");
        # Disable caching - HTTP 1.1
        header("Cache-Control: no-cache, no-store, must-revalidate");
        # Disable caching - HTTP 1.0
        header("Pragma: no-cache");
        # Disable caching - Proxies
        header("Expires: 0");

        # Start the ouput
        $output = fopen("php://output", "w");
        fputcsv($output, array_keys($data[0]));
         # Then loop through the rows
        foreach ($data as $row) {
            # Add the rows to the body
            fputcsv($output, $row); // here you can change delimiter/enclosure
        }
        # Close the stream off
        fclose($output);
    }

  $filename = "unit_election_results_" . $unitFileName . "_" . date('Ymd') . ".csv";
  outputCSV($data, $filename);

  exit;

?>

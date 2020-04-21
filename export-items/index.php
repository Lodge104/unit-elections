<?php
$title = "Leadership Election Portal | Occoneechee Lodge - Order of the Arrow, BSA";
$userrole = "Admin"; // Allow only admins to access this page
include "../login/misc/pagehead.php";

include '../unitelections-info.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>


<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=X-UA-Compatible content="IE=Edge,chrome=1" />
    <meta name=viewport content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />

    <title>Dashboard | Unit Elections Administration | Occoneechee Lodge - Order of the Arrow, BSA</title>

    <link rel="stylesheet" href="../libraries/fontawesome-free-5.12.0/css/all.min.css">


</head>

<body id="dashboard">
	<?php require '../login/misc/pullnav.php'; ?>
  <div class="wrapper">


    <main class="container-fluid col-xl-11">
      <?php
      if ($_GET['status'] == 1) { ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <div class="alert alert-success" role="alert">
            <strong>Saved!</strong> Your data has been saved! Thanks!
            <button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
        </div>
    <?php } ?>
        <section class="row">
            <div class="col-12">
                <h2>Unit Elections Dashboard</h2>
            </div>
        </section>

        <?php
          $getChaptersQuery = $conn->prepare("SELECT DISTINCT chapter FROM unitElections ORDER BY chapter ASC");
          $getChaptersQuery->execute();
          $getChaptersQ = $getChaptersQuery->get_result();
          if ($getChaptersQ->num_rows > 0) {
            while ($getChapters = $getChaptersQ->fetch_assoc()) {
              $getUnitElectionsQuery = $conn->prepare("SELECT * from unitElections where chapter = ? ORDER BY dateOfElection ASC");
              $getUnitElectionsQuery->bind_param("s", $getChapters['chapter']);
              $getUnitElectionsQuery->execute();
              $getUnitElectionsQ = $getUnitElectionsQuery->get_result();
              if ($getUnitElectionsQ->num_rows > 0) {
                //print election info
                ?>
                <div class="card mb-3">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $getChapters['chapter']; ?></h5>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Unit</th>
                            <th scope="col">Date of Election</th>
							<th scope="col"># of Votes</th>
                            <th scope="col">View Results</th>
							<th scope="col">Exported</th>
							<th scope="col">Update Election</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php while ($getUnitElections = $getUnitElectionsQ->fetch_assoc()){
                            ?><tr>
                              <td><?php echo $getUnitElections['unitCommunity'] . " " . $getUnitElections['unitNumber']; ?></td>
                              <td><?php echo date("m-d-Y", strtotime($getUnitElections['dateOfElection'])); ?></td>
							  <?php
                              $submissionsQuery = $conn->prepare("SELECT COUNT(*) AS unitTotal FROM submissions WHERE unitId=?");
                              $submissionsQuery->bind_param("s", $getUnitElections['id']);
                              $submissionsQuery->execute();
                              $submissionsQ = $submissionsQuery->get_result();
                              if ($submissionsQ->num_rows > 0) {
                                $submissions = $submissionsQ->fetch_assoc();
                                ?><td><?php echo $submissions['unitTotal']; ?> out of <?php echo $getUnitElections['numRegisteredYouth']; ?> Scouts</td>
                              <?php }
                              $submissionsQuery->close();
                              ?>
                              <td>
                                <?php

                                $tz = 'America/New_York';
                                $timestamp = time();
                                $dt = new DateTime("now", new DateTimeZone($tz));
                                $dt->setTimestamp($timestamp);

                                $date = $dt->format("Y-m-d");
                                $hour = $dt->format("H");
                                if ((strtotime($getUnitElections['dateOfElection']) < strtotime($date)) || ($getUnitElections['dateOfElection'] == $date && $hour >= 12)) { ?>
                                  <a href="results.php?accessKey=<?php echo $getUnitElections['accessKey']; ?>">view</a>
                                <?php } else { ?>
                                  <span class="text-muted">not completed</span>
                                <?php } ?>
                              </td>
							  <td>
								  <?php
								  if (($getUnitElections['exported'] == 'Yes')) { ?>
                                  <span class="badge badge-success">Yes - Imported to LodgeMaster</span>
                                <?php } else { ?>
                                  <span class="badge badge-danger">No - Not in LodgeMaster</span>
                                <?php } ?>
							  </td>
                              <td><a href="edit-unit-election.php?accessKey=<?php echo $getUnitElections['accessKey']; ?>">edit</a></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <?php
              } else {
                ?>
                <div class="alert alert-danger" role="alert">
                  There are no elections in the database.
                </div>
                <?php
              }
            }
          } else {
            ?>
            <div class="alert alert-danger" role="alert">
              There are no elections in the database.
            </div>
            <?php
          }
        ?>

    </main>
  </div>
    <?php include "../footer.php"; ?>

    <script src="../libraries/jquery-3.4.1.min.js"></script>
    <script src="../libraries/popper-1.16.0.min.js"></script>
    <script src="../libraries/bootstrap-4.4.1/js/bootstrap.min.js"></script>

</body>

</html>

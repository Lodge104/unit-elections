<?php
$title = "Chapter Election Portal | Occoneechee Lodge - Order of the Arrow, BSA";
$userrole = "Standard User"; // Allow only logged in users
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
	  <?php
      if ($_GET['status'] == 2) { ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <div class="alert alert-success" role="alert">
            <strong>Submitted!</strong> Your election results have been submitted! Thanks!
            <button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
        </div>
    <?php } ?>
		<?php
      if ($_GET['status'] == 3) { ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <div class="alert alert-danger" role="alert">
            <strong>Error!</strong> Something went wrong and your submission did not finish successfully!
            <button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
        </div>
    <?php } ?>
		<div class="row">
            <div class="col-auto mr-auto">
                <h2>Chapter's Unit Elections Dashboard</h2>
            </div>
			<div class="col-auto">
				<a class="btn btn-primary" data-toggle="collapse" href="#collapseInstructions" role="button" aria-expanded="false" aria-controls="collapseInstructions">Show Instructions</a>
				<!--<a class="btn btn-primary" data-toggle="collapse" href="#online" role="button" aria-expanded="false" aria-controls="online">Show Online Voting</a>-->
				<a class="btn btn-primary" data-toggle="collapse" href="#inperson" role="button" aria-expanded="false" aria-controls="inperson">Show In-Person Voting</a>
			</div>
			</div>
		<div class="collapse" id="collapseInstructions">
		<div class="card mb-3">
            <div class="card-body">
				<h3 class="card-title d-inline-flex">Instructions</h3>
					<p>This is the chapter dashboard for unit elections. All unit elections in the lodge are located here and are sorted by Chapter. Please only edit elections for your chapter.<br><br>After a unit leader has contacted the chapter requesting a unit election, begin by creating a new unit election in this portal. Then share the <span class="badge badge-danger">Access Key</span> and a link to <span class="badge badge-danger">https://elections.lodge104.net/</span>. You may also share the voting link but it will not be active until voting is turned on.<br><br>The status of the unit election will remain <span class="badge badge-danger">Voting Not Open</span> until voting is turned on. Only turn on voting after the election team has spoken to the unit and voting is ready to begin. To turn on voting, click the button "Edit" under the "Edit" column. Then change "Voting Open?" to "Yes" and click save. Remember to change "Voting Open?" back to "No" after the election is complete. This will let the lodge leadership know the election is finished.<br><br>The status will change to <span class="badge badge-success">Completed</span>, once the election has been imported to LodgeMaster and the candidates can register for their Ordeal. This will happen within a week of the election.<br><br>If you run into any problems using the system, please use the live chat feature in the bottom right hand corner.</p>
			 </div>
		 </div>
		</div>
		
        <?php
          $getChaptersQuery = $conn->prepare("SELECT DISTINCT chapter FROM unitElections ORDER BY chapter ASC");
          $getChaptersQuery->execute();
          $getChaptersQ = $getChaptersQuery->get_result();
          if ($getChaptersQ->num_rows > 0) {
            while ($getChapters = $getChaptersQ->fetch_assoc()) {
              $getUnitElectionsQuery = $conn->prepare("SELECT * from unitElections where chapter = ? AND onlinevote = 'Yes' AND ((unitCommunity = 'Test Unit' AND date(dateOfElection) BETWEEN date(date_add(now(), INTERVAL -30 day)) AND date(now()) or date(dateOfElection) BETWEEN date(now()) AND date(date_add(now(), INTERVAL 120 day))) OR (NOT unitCommunity = 'Test Unit' AND date(dateOfElection) BETWEEN date(date_add(now(), INTERVAL -183 day)) AND date(now()) or date(dateOfElection) BETWEEN date(now()) AND date(date_add(now(), INTERVAL 120 day)))) ORDER BY dateOfElection ASC");
              $getUnitElectionsQuery->bind_param("s", $getChapters['chapter']);
              $getUnitElectionsQuery->execute();
              $getUnitElectionsQ = $getUnitElectionsQuery->get_result();
              if ($getUnitElectionsQ->num_rows > 0) {
                //print election info
                ?>
				<!--<div class="collapse" id="online">-->
                <div class="card mb-3">
                  <div class="card-body">
                    <h3 class="card-title"><?php echo $getChapters['chapter']; ?> - Online Voting</h3>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Unit</th>
                            <th scope="col">Date of Election</th>
                            <th scope="col">Access Key for Unit Leader</th>
							<th scope="col">Eligible Scouts</th>
                            <th scope="col">Voting Link</th>
                            <th scope="col">Edit</th>
							<th scope="col"># of Votes</th>
							<th scope="col">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php while ($getUnitElections = $getUnitElectionsQ->fetch_assoc()){
                            ?><tr>
                              <td><?php echo $getUnitElections['unitCommunity'] . " " . $getUnitElections['unitNumber']; ?></td>
                              <td><?php echo date("m-d-Y", strtotime($getUnitElections['dateOfElection'])); ?></td>
							  <td><input id="key" type="text" value="<?php echo $getUnitElections['accessKey']; ?>" disabled><button class="btn btn-primary" id="btn" data-clipboard-text="<?php echo $getUnitElections['accessKey']; ?>">Copy</button>
						   	  </td>
                              <td><a href="eligible-scouts.php?accessKey=<?php echo $getUnitElections['accessKey']; ?>" class="btn btn-primary" role="button">Edit</a></td>
                              <td><input id="foo" type="text" value="https://elections.lodge104.net/submit.php?accessKey=<?php echo $getUnitElections['accessKey']; ?>" disabled><button class="btn btn-primary" id="btn" data-clipboard-text="https://elections.lodge104.net/submit.php?accessKey=<?php echo $getUnitElections['accessKey']; ?>">Copy</button>
                              </td>
                              <td><a href="edit-unit-election.php?accessKey=<?php echo $getUnitElections['accessKey']; ?>" class="btn btn-primary" role="button">Edit</a></td>
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
								  if (($getUnitElections['exported'] == 'Yes')) { ?>
                                  <span class="badge badge-success">Completed</span>
                                <?php } elseif (($getUnitElections['open'] == 'Yes')) { ?>
                                  <span class="badge badge-warning">Voting Open</span>
                                <?php } else { ?>
                                  <span class="badge badge-danger">Voting Not Open</span>
                                <?php } ?>
							  </td>	  
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
		<!--</div>-->
                <?php
              } else {
                ?>
				<div></div>
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
		
		<?php
          $getInpersonQuery = $conn->prepare("SELECT DISTINCT chapter FROM unitElections ORDER BY chapter ASC");
          $getInpersonQuery->execute();
          $getInpersonQ = $getInpersonQuery->get_result();
          if ($getInpersonQ->num_rows > 0) {
            while ($getInperson = $getInpersonQ->fetch_assoc()) {
              $getUnitElectionsInpersonQuery = $conn->prepare("SELECT * from unitElections where chapter = ? AND onlinevote = 'No' AND ((unitCommunity = 'Test Unit' AND date(dateOfElection) BETWEEN date(date_add(now(), INTERVAL -30 day)) AND date(now()) or date(dateOfElection) BETWEEN date(now()) AND date(date_add(now(), INTERVAL 120 day))) OR (NOT unitCommunity = 'Test Unit' AND date(dateOfElection) BETWEEN date(date_add(now(), INTERVAL -183 day)) AND date(now()) or date(dateOfElection) BETWEEN date(now()) AND date(date_add(now(), INTERVAL 120 day)))) ORDER BY dateOfElection ASC");
              $getUnitElectionsInpersonQuery->bind_param("s", $getInperson['chapter']);
              $getUnitElectionsInpersonQuery->execute();
              $getUnitElectionsInpersonQ = $getUnitElectionsInpersonQuery->get_result();
              if ($getUnitElectionsInpersonQ->num_rows > 0) {
                //print election info
                ?>
                <div class="collapse" id="inperson">
				<div class="card mb-3">
                  <div class="card-body">
                    <h3 class="card-title"><?php echo $getInperson['chapter']; ?> - In-Person Voting</h3>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Unit</th>
                            <th scope="col">Date of Election</th>
                            <th scope="col">Access Key for Unit Leader</th>
							<th scope="col">Eligible Scouts</th>
                            <th scope="col">Submit Results</th>
                            <th scope="col">Edit</th>
							<th scope="col">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php while ($getUnitElectionsInperson = $getUnitElectionsInpersonQ->fetch_assoc()){
                            ?><tr>
                              <td><?php echo $getUnitElectionsInperson['unitCommunity'] . " " . $getUnitElectionsInperson['unitNumber']; ?></td>
                              <td><?php echo date("m-d-Y", strtotime($getUnitElectionsInperson['dateOfElection'])); ?></td>
							  <td><input id="key" type="text" value="<?php echo $getUnitElectionsInperson['accessKey']; ?>" disabled><button class="btn btn-primary" id="btn" data-clipboard-text="<?php echo $getUnitElectionsInperson['accessKey']; ?>">Copy</button>
						   	  </td>
                              <td><a href="eligible-scouts.php?accessKey=<?php echo $getUnitElectionsInperson['accessKey']; ?>" class="btn btn-primary" role="button">Edit</a></td>
                             <td>
                                <?php
								$submissionsInpersonQuery = $conn->prepare("SELECT COUNT(*) AS unitTotal FROM submissions WHERE unitId=?");
                                $submissionsInpersonQuery->bind_param("s", $getUnitElectionsInperson['id']);
                                $submissionsInpersonQuery->execute();
                                $submissionsIQ = $submissionsInpersonQuery->get_result();
								$submissionsI = $submissionsIQ->fetch_assoc();
                                if (($submissionsI['unitTotal'] == 0)) { ?>
                                  <a href="/chapter/submit.php?accessKey=<?php echo $getUnitElectionsInperson['accessKey']; ?>&watchedVideo=true&ignoreTime=true&ignorePreviousSubmission=true" class="btn btn-primary" role="button">Submit</a>
                                <?php } else { ?>
                                  <span class="text-muted">Results Already Submitted</span>
                                <?php } ?>
                              </td>
                              <td><a href="edit-unit-election.php?accessKey=<?php echo $getUnitElectionsInperson['accessKey']; ?>" class="btn btn-primary" role="button">Edit</a></td>
							  <td>
								  <?php
								  if (($getUnitElectionsInperson['exported'] == 'Yes')) { ?>
                                  <span class="badge badge-success">Completed</span>
                                <?php } elseif (($getUnitElectionsInperson['open'] == 'Yes')) { ?>
                                  <span class="badge badge-warning">Voting Open</span>
                                <?php } else { ?>
                                  <span class="badge badge-danger">Voting Not Open</span>
                                <?php } ?>
							  </td>	  
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
			  </div>
                <?php
              } else {
                ?>
				<div></div>
                <?php
              }
            }
          } else {
            ?>
            <div class="alert alert-danger" role="alert">
              There are no in-person unit elections in the database.
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
	<script src="../dist/clipboard.min.js"></script>

    								<script>
    									var clipboard = new ClipboardJS('.btn');

    									clipboard.on('success', function(e) {
												console.log(e);
										});

										clipboard.on('error', function(e) {
											console.log(e);
										});
								    </script>

</body>

</html>

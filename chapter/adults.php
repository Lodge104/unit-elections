<?php
$title = "Leadership Election Portal | Occoneechee Lodge - Order of the Arrow, BSA";
$userrole = "Standard User"; // Allow only admins to access this page
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

    <title>Review Adult Nominatons | Unit Elections Administration | Occoneechee Lodge - Order of the Arrow, BSA</title>

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
                <h2>Review Adult Nominations</h2>
            </div>
        </section>
			<div class="alert alert-danger" role="alert">
				<h3>Warning</h3>
				 	<p>These nominations are read-only. Nominations are sent directly from the unit to the Lodge Selection Committee.</p>
			 </div>

		  <?php
          $adultNominationQuery = $conn->prepare("SELECT * from adultNominations");
          $adultNominationQuery->execute();
          $adultNominationQ = $adultNominationQuery->get_result();
          if ($adultNominationQ->num_rows > 0) {
                //print election info
                ?>
				<!--<div class="collapse" id="online">-->
                <div class="card mb-3">
                  <div class="card-body">
                    <h3 class="card-title">Adult Nominations</h3>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>	  
							<th scope="col">Unit</th>
                            <th scope="col">Name</th>
                            <th scope="col">BSA ID</th>
                            <th scope="col">Position</th>
							<th scope="col">Review</th>
							<th scope="col">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php while ($getAdult = $adultNominationQ->fetch_assoc()){
								
                            ?><tr>
							  <?php
                              $submissionsQuery = $conn->prepare("SELECT * from unitElections WHERE id=?");
                              $submissionsQuery->bind_param("s", $getAdult['unitId']);
                              $submissionsQuery->execute();
                              $submissionsQ = $submissionsQuery->get_result();
                              if ($submissionsQ->num_rows > 0) {
                                $submissions = $submissionsQ->fetch_assoc();
                                ?>
							  <td><?php echo $submissions['unitCommunity']; ?> <?php echo $submissions['unitNumber']; ?></td>
							  <?php }
                              $submissionsQuery->close();
                              ?>
                              <td><?php echo $getAdult['firstName'] . " " . $getAdult['lastName']; ?></td>
							  <td><?php echo $getAdult['bsa_id']; ?></td>
                              <td><?php echo $getAdult['position']; ?></td>
							  <td><a href="../chapter/review-nomination.php?accessKey=<?php echo $getAdult['accessKey']; ?>" class="btn btn-primary" role="button">Review Nomination</a></td>	  
							  <td>
								  <?php
								  if (($getAdult['leader_signature'] == '1' && (($getAdult['chair_signature'] == '1') && ($getAdult['advisor_signature'] == '2')))) { ?>
                                  <span class="badge badge-warning">Not Approved</span>
								<?php } elseif (($getAdult['leader_signature'] == '1' && (($getAdult['chair_signature'] == '1') && ($getAdult['advisor_signature'] == '1')))) { ?>
								  <span class="badge badge-success">Approved</span>
                                <?php } elseif (($getAdult['leader_signature'] == '1' && $getAdult['chair_signature'] == '1')) { ?>
                                  <span class="badge badge-danger">Waiting for Selection Committee</span>
                                <?php } elseif (($getAdult['leader_signature'] == '1')) { ?>
                                  <span class="badge badge-danger">Waiting for Unit Chair Approval</span>
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
              }
			else {
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

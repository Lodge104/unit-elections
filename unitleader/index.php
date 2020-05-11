<?php
$title = "Unit Leader Election Portal | Occoneechee Lodge - Order of the Arrow, BSA";
include "../login/misc/pagehead.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=X-UA-Compatible content="IE=Edge,chrome=1" />
    <meta name=viewport content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />

    <title>Unit Election Administration | Occoneechee Lodge - Order of the Arrow, BSA</title>

    <link rel="stylesheet" href="../libraries/fontawesome-free-5.12.0/css/all.min.css">

</head>

<body id="dashboard">
	
	<?php require '../login/misc/pullnav.php'; ?>
	
  <div class="wrapper">

    <main class="container-fluid">
      <?php
      if ($_GET['status'] == 1) { ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <div class="alert alert-success" role="alert">
            <strong>Saved!</strong> Your data has been saved! Thanks!
            <button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
        </div>
    <?php } ?>
        <?php
          include '../unitelections-info.php';
          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);
          // Check connection
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          if (isset($_GET['accessKey'])) {
            if (preg_match("/^([a-z\d]){8}-([a-z\d]){4}-([a-z\d]){4}-([a-z\d]){4}-([a-z\d]){12}$/", $_GET['accessKey'])) {
              $accessKey = $_POST['accessKey'] = $_GET['accessKey'];
              ?>
              <section class="row">
                  <div class="col-12">
                      <h2>Unit Leader Election Dashboard</h2>
                  </div>
              </section>
			  <div class="card mb-3">
                  <div class="card-body">
				  	<h3 class="card-title d-inline-flex">Instructions</h3>
					  <p>This is the dashboard for your unit election. Please start by making sure all the information in the first box is accurate and up to date. If not, please click the edit button to correct it.<br><br> 
					  Secondly, please add all eligible scouts who will be on the ballot in the appropriate box. Please reference the criteria information to determine which scouts are eligible. Any scout not included by the start of the election will have to wait until the next year. After the election, you can view the results with a link that will appear on your dashboard.<br><br>It is up to you to determine when you would like to announce the results. You may announce them directly after the election or wait until another time. Please speak with the election team as many chapters offer district wide call-out ceremonies. Additionally, the election team has information to give to newly elected candidates regardless of when the results are announced, so please retrieve the information from them.<br><br>The status of the unit election will remain <span class="badge badge-danger">In Progress</span> until the results are imported into our membership database. Once the status says <span class="badge badge-success">Completed</span>, your scouts will be able to register for their ordeal.</p>
				  </div>
		 	  </div>
              <?php
              $getUnitElectionsQuery = $conn->prepare("SELECT * from unitElections where accessKey = ?");
              $getUnitElectionsQuery->bind_param("s", $accessKey);
              $getUnitElectionsQuery->execute();
              $getUnitElectionsQ = $getUnitElectionsQuery->get_result();
              if ($getUnitElectionsQ->num_rows > 0) {
                //print election info
                ?>
                <div class="card mb-3">
                  <div class="card-body">
                    <a href="edit-unit-election.php?accessKey=<?php echo $accessKey; ?>" class="btn btn-sm btn-secondary mb-2 d-inline-flex float-right">edit</a>
                    <h3 class="card-title d-inline-flex">Scheduled Unit Election Information</h3>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Unit Type</th>
                            <th scope="col">Unit Number</th>
                            <th scope="col"># of Registered Youth</th>
                            <th scope="col">Chapter</th>
                            <th scope="col">Date of Election</th>
                            <th scope="col">Results</th>
							<th scope="col">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $getUnitElections = $getUnitElectionsQ->fetch_assoc(); ?>
                          <tr>
                            <td><?php echo $getUnitElections['unitCommunity']; ?></td>
                            <td><?php echo $getUnitElections['unitNumber']; ?></td>
                            <td><?php echo $getUnitElections['numRegisteredYouth']; ?></td>
                            <td><?php echo $getUnitElections['chapter']; ?></td>
                            <td><?php echo date("m-d-Y", strtotime($getUnitElections['dateOfElection'])); ?></td>
                            <td>
                                <?php

                                $tz = 'America/New_York';
                                $timestamp = time();
                                $dt = new DateTime("now", new DateTimeZone($tz));
                                $dt->setTimestamp($timestamp);

                                $date = $dt->format("Y-m-d");
                                $hour = $dt->format("H");
                                if ((strtotime($getUnitElections['dateOfElection']) < strtotime($date)) || ($getUnitElections['dateOfElection'] == $date && $hour >= 14)) { ?>
                                  <a href="results.php?accessKey=<?php echo $getUnitElections['accessKey']; ?>">view</a>
                                <?php } else { ?>
                                  <span class="text-muted">Not Available</span>
                                <?php } ?>
                              </td>
							<td>
								  <?php
								  if (($getUnitElections['exported'] == 'Yes')) { ?>
                                  <span class="badge badge-success">Completed</span>
                                <?php } else { ?>
                                  <span class="badge badge-danger">In Progress</span>
                                <?php } ?>
							</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <h5 class="card-title">Unit Leader Information</h5>
                    <div class="row">
                      <div class="col-md-3">
                        <?php echo $getUnitElections['sm_name']; ?><br>
                      </div>
                      <div class="col-md-3">
                        <?php echo $getUnitElections['sm_address_line1']; ?><br>
                        <?php echo ($getUnitElections['sm_address_line2'] == "" ? '' : $getUnitElections['sm_address_line2'] . "<br>"); ?>
                        <?php echo $getUnitElections['sm_city']; ?>, <?php echo $getUnitElections['sm_state']; ?> <?php echo $getUnitElections['sm_zip']; ?><br>
                      </div>
                      <div class="col-md-3">
                        <?php echo $getUnitElections['sm_email']; ?><br>
                        <?php echo $getUnitElections['sm_phone']; ?><br>
                      </div>
                    </div>
                  </div>
                </div>
				
				<div class="card mb-3">
                      <div class="card-body">
                        <h3 class="card-title">Eligible Scout Criteria</h3>
                        <div>Youth	membership	qualifications:
                        <ol class="mb-3">
                          <li>Registered	member	of	the	Boy	Scouts	of America</li>
                          <li>Hold	the	rank	of	First	Class,	hold	the	Scouts	BSA	First	Class	rank,	the	Venturing	Discovery	Award,	or	the	Sea	Scout	Ordinary	rank	or	higher</li>
                          <li>In	the	past	two	years,	have	completed	fifteen	(15)	days	and	nights	of	camping	under	the	auspices	of	the	Boy	Scouts	of	America.		The	fifteen	days	and	nights	of	camping	must	include	one	long-term	camp	of	six	days	and	five	nights,	and	the	balance	of	the	camping	must	be	short-term	(1,	2,	or	3	night)	camping	trips.</li>
                          <li>Scoutmaster	approval</li>
						  <li><span class="badge badge-danger">COVID-19</span> Virtual camping can now count towards qualifications. Please see the policy exception <a href='https://lodge104.net/coronavirus/#April29'>here</a></li>
                        </ol></div>
                      </div>
                    </div>
		
                <div class="card mb-3">
                  <div class="card-body">
                    <h3 class="card-title">Eligible Scouts</h3>
                    <form action="add-scouts.php" method="post">
                      <input type="hidden" id="unitId" name="unitId" value="<?php echo $getUnitElections['id']; ?>">
                      <input type="hidden" id="accessKey" name="accessKey" value="<?php echo $accessKey; ?>">
                      <div id="eligible-scouts">
                        <?php $counterEligibleScouts = 0;
                        $eligibleScoutsQuery = $conn->prepare("SELECT * from eligibleScouts where unitId = ?");
                        $eligibleScoutsQuery->bind_param("s", $getUnitElections['id']);
                        $eligibleScoutsQuery->execute();
                        $eligibleScoutsQ = $eligibleScoutsQuery->get_result();
                        if ($eligibleScoutsQ->num_rows > 0) {
                          while ($eligibleScout = $eligibleScoutsQ->fetch_assoc()) {
                            if ($counterEligibleScouts > 0) { ?>
                              <hr></hr>
                            <?php } ?>
                            <input type="hidden" name="eligibleScoutId[<?php echo $counterEligibleScouts; ?>]" value="<?php echo $eligibleScout['id']; ?>">
                            <div class="form-row">
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="firstName[<?php echo $counterEligibleScouts; ?>]" class="required">First Name</label>
                                  <input type="text" id="firstName[<?php echo $counterEligibleScouts; ?>]" name="firstName[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['firstName']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="lastName[<?php echo $counterEligibleScouts; ?>]" class="required">Last Name</label>
                                  <input type="text" id="lastName[<?php echo $counterEligibleScouts; ?>]" name="lastName[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['lastName']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="dob[<?php echo $counterEligibleScouts; ?>]" class="required">Birthday</label>
                                  <input type="date" id="dob[<?php echo $counterEligibleScouts; ?>]" name="dob[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['dob']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="bsa_id[<?php echo $counterEligibleScouts; ?>]" class="required">BSA ID</label>
                                  <input type="text" id="bsa_id[<?php echo $counterEligibleScouts; ?>]" name="bsa_id[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['bsa_id']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Address</label>
                                  <input id="address_line1[<?php echo $counterEligibleScouts; ?>]" name="address_line1[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Address" value="<?php echo $eligibleScout['address_line1']; ?>" >
                                </div>
                                <div class="form-group">
                                  <input id="address_line2[<?php echo $counterEligibleScouts; ?>]" name="address_line2[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Address Line 2 (optional)" value="<?php echo $eligibleScout['address_line2']; ?>">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>City, State, Zip</label>
                                  <input id="city[<?php echo $counterEligibleScouts; ?>]" name="city[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="City" value="<?php echo $eligibleScout['city']; ?>" >
                                </div>
                                <div class="form-row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <input id="state[<?php echo $counterEligibleScouts; ?>]" name="state[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="State" value="<?php echo $eligibleScout['state']; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-8">
                                    <div class="form-group">
                                      <input id="zip[<?php echo $counterEligibleScouts; ?>]" name="zip[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Zip" value="<?php echo $eligibleScout['zip']; ?>" >
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label class="required">Contact Information</label>
                                  <input id="email[<?php echo $counterEligibleScouts; ?>]" name="email[<?php echo $counterEligibleScouts; ?>]" type="email" class="form-control" placeholder="Email" value="<?php echo $eligibleScout['email']; ?>" required>
                                </div>
                                <div class="form-group">
                                  <input id="phone[<?php echo $counterEligibleScouts; ?>]" name="phone[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Phone" value="<?php echo $eligibleScout['phone']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <?php
                            $counterEligibleScouts++;
                          }
                        } else {
                          while ($counterEligibleScouts < 2) {
                            if ($counterEligibleScouts > 0) { ?>
                              <hr></hr>
                            <?php } ?>
                            <input type="hidden" name="eligibleScoutId[<?php echo $counterEligibleScouts; ?>]" value="new">
                            <div class="form-row">
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="firstName[<?php echo $counterEligibleScouts; ?>]" class="required">First Name</label>
                                  <input type="text" id="firstName[<?php echo $counterEligibleScouts; ?>]" name="firstName[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['firstName']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="lastName[<?php echo $counterEligibleScouts; ?>]" class="required">Last Name</label>
                                  <input type="text" id="lastName[<?php echo $counterEligibleScouts; ?>]" name="lastName[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['lastName']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="dob[<?php echo $counterEligibleScouts; ?>]" class="required">Birthday</label>
                                  <input type="date" id="dob[<?php echo $counterEligibleScouts; ?>]" name="dob[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['dob']; ?>" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                  <label for="bsa_id[<?php echo $counterEligibleScouts; ?>]" class="required">BSA ID</label>
                                  <input type="text" id="bsa_id[<?php echo $counterEligibleScouts; ?>]" name="bsa_id[<?php echo $counterEligibleScouts; ?>]" class="form-control" value="<?php echo $eligibleScout['bsa_id']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>Address</label>
                                  <input id="address_line1[<?php echo $counterEligibleScouts; ?>]" name="address_line1[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Address" value="<?php echo $eligibleScout['address_line1']; ?>" >
                                </div>
                                <div class="form-group">
                                  <input id="address_line2[<?php echo $counterEligibleScouts; ?>]" name="address_line2[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Address Line 2 (optional)" value="<?php echo $eligibleScout['address_line2']; ?>">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label>City, State, Zip</label>
                                  <input id="city[<?php echo $counterEligibleScouts; ?>]" name="city[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="City" value="<?php echo $eligibleScout['city']; ?>" >
                                </div>
                                <div class="form-row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <input id="state[<?php echo $counterEligibleScouts; ?>]" name="state[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="State" value="<?php echo $eligibleScout['state']; ?>" >
                                    </div>
                                  </div>
                                  <div class="col-md-8">
                                    <div class="form-group">
                                      <input id="zip[<?php echo $counterEligibleScouts; ?>]" name="zip[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Zip" value="<?php echo $eligibleScout['zip']; ?>" >
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label class="required">Contact Information</label>
                                  <input id="email[<?php echo $counterEligibleScouts; ?>]" name="email[<?php echo $counterEligibleScouts; ?>]" type="email" class="form-control" placeholder="Email" value="<?php echo $eligibleScout['email']; ?>" required>
                                </div>
                                <div class="form-group">
                                  <input id="phone[<?php echo $counterEligibleScouts; ?>]" name="phone[<?php echo $counterEligibleScouts; ?>]" type="text" class="form-control" placeholder="Phone" value="<?php echo $eligibleScout['phone']; ?>" required>
                                </div>
                              </div>
                            </div>
                            <?php
                            $counterEligibleScouts++;
                          }
                        } ?>
                      </div>
                      <div>
                        <button type="button" class="btn btn-secondary mb-2" onclick="addScout('eligible-scouts')">Add another</button>
                      </div>
					<br>
                      <div>
                        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        <input type="submit" class="btn btn-primary" value="Save">
                      </div>
                      <script>
                          var counter = <?php echo $counterEligibleScouts; ?>;

                          function addScout(divName) {
                              var hr = document.createElement('hr');
                              var formRow = document.createElement('div');
                              formRow.innerHTML = "<input type='hidden' name='eligibleScoutId["+ counter +"]' value='new'><div class='form-row'>  <div class='col-md-3'><div class='form-group'><label for='firstName["+ counter +"]' class='required'>First Name</label><input type='text' id='firstName["+ counter +"]' name='firstName["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'><label for='lastName["+ counter +"]' class='required'>Last Name</label><input type='text' id='lastName["+ counter +"]' name='lastName["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'>  <label for='dob["+ counter +"]' class='required'>Birthday</label>  <input type='date' id='dob["+ counter +"]' name='dob["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'><label for='bsa_id["+ counter +"]' class='required'>BSA ID</label><input type='text' id='bsa_id["+ counter +"]' name='bsa_id["+ counter +"]' class ='form-control' required></div>  </div></div><div class='form-row'>  <div class='col-md-4'><div class='form-group'>  <label>Address</label>  <input id='address_line1["+ counter +"]' name='address_line1["+ counter +"]' type='text' class='form-control' placeholder='Address' ></div><div class='form-group'>  <input id='address_line2["+ counter +"]' name='address_line2["+ counter +"]' type='text' class='form-control' placeholder='Address Line 2 (optional)'></div>  </div>  <div class='col-md-4'><div class='form-group'>  <label>City, State, Zip</label>  <input id='city["+ counter +"]' name='city["+ counter +"]' type='text' class='form-control' placeholder='City' ></div><div class='form-row'>  <div class='col-md-4'><div class='form-group'>  <input id='state["+ counter +"]' name='state["+ counter +"]' type='text' class='form-control' placeholder='State' ></div>  </div>  <div class='col-md-8'><div class='form-group'>  <input id='zip["+ counter +"]' name='zip["+ counter +"]' type='text' class='form-control' placeholder='Zip' ></div>  </div></div>  </div>  <div class='col-md-4'><div class='form-group'>  <label class='required'>Contact Information</label>  <input id='email["+ counter +"]' name='email["+ counter +"]' type='email' class='form-control' placeholder='Email' required></div><div class='form-group'>  <input id='phone["+ counter +"]' name='phone["+ counter +"]' type='text' class='form-control' placeholder='Phone' required></div></div></div>";
                              document.getElementById(divName).appendChild(hr);
                              document.getElementById(divName).appendChild(formRow);
                              counter++;
                          }

                      </script>
                    </form>
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
            } else {
              //accesskey bad
              ?>
              <div class="alert alert-danger" role="alert">
                <h5 class="alert-heading">Invalid Access Key</h5>
                You have an invalid access key. Please use the personalized link provided in your email, or enter your access key below.
              </div>
              <div class="card col-md-6 mx-lg-5">
                <div class="card-body">
                  <h3 class="card-title">Access Key </h3>
                  <form action='' method="get">
                    <div class="form-group">
                      <label for="accessKey" class="required">Access Key</label>
                      <input type="text" id="accessKey" name="accessKey" class="form-control" required>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                  </form>
                </div>
              </div>
              <?php
            }
          } else {
            //no accessKey
            ?>
				<div class="card col-sm">		
              <div class="card-body">
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

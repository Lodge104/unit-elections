<?php
$title = "Edit Eligible Scouts | Chapter Election Portal | Occoneechee Lodge - Order of the Arrow, BSA";
$userrole = "Standard User"; // Allow only logged in users
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

    <title>Eligible Scouts | Unit Elections Administration | Occoneechee Lodge - Order of the Arrow, BSA</title>

    <link rel="stylesheet" href="../libraries/fontawesome-free-5.12.0/css/all.min.css">

</head>

<body id="dashboard">
	<?php require '../login/misc/pullnav.php'; ?>
  <div class="wrapper">

    <main class="container-fluid">
      <?php
      if ($_GET['status'] == 1) { ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
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
              <?php
              $getUnitElectionsQuery = $conn->prepare("SELECT * from unitElections where accessKey = ?");
              $getUnitElectionsQuery->bind_param("s", $accessKey);
              $getUnitElectionsQuery->execute();
              $getUnitElectionsQ = $getUnitElectionsQuery->get_result();
              if ($getUnitElectionsQ->num_rows > 0) {
                //print election info
                $getUnitElections = $getUnitElectionsQ->fetch_assoc();
                ?>
                <section class="row">
                    <div class="col-12">
                        <h2>Unit Election Administration | <?php echo $getUnitElections['unitCommunity'] . " " . $getUnitElections['unitNumber']; ?></h2>
                    </div>
                </section>
                <div class="card mb-3">
                  <div class="card-body">
                    <h3 class="card-title">Eligible Scouts</h3>
                    <form action="add-scouts-process.php" method="post">
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
                                  <input id="phone[<?php echo $counterEligibleScouts; ?>]" name="phone[<?php echo $counterEligibleScouts; ?>]" type="tel" class="form-control" placeholder="Phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" title="555-555-5555" value="<?php echo $eligibleScout['phone']; ?>" required>
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
                                  <input id="phone[<?php echo $counterEligibleScouts; ?>]" name="phone[<?php echo $counterEligibleScouts; ?>]" type="tel" class="form-control" placeholder="Phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" title="555-555-5555" value="<?php echo $eligibleScout['phone']; ?>" required>
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
						<div class="my-2"><small class="text-muted">We suggest saving the page before adding an additional scout.</small></div>
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
                              formRow.innerHTML = "<input type='hidden' name='eligibleScoutId["+ counter +"]' value='new'><div class='form-row'>  <div class='col-md-3'><div class='form-group'><label for='firstName["+ counter +"]' class='required'>First Name</label><input type='text' id='firstName["+ counter +"]' name='firstName["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'><label for='lastName["+ counter +"]' class='required'>Last Name</label><input type='text' id='lastName["+ counter +"]' name='lastName["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'>  <label for='dob["+ counter +"]' class='required'>Birthday</label>  <input type='date' id='dob["+ counter +"]' name='dob["+ counter +"]' class='form-control' required></div>  </div>  <div class='col-md-3'><div class='form-group'><label for='bsa_id["+ counter +"]' class='required'>BSA ID</label><input type='text' id='bsa_id["+ counter +"]' name='bsa_id["+ counter +"]' class ='form-control' required></div>  </div></div><div class='form-row'>  <div class='col-md-4'><div class='form-group'>  <label>Address</label>  <input id='address_line1["+ counter +"]' name='address_line1["+ counter +"]' type='text' class='form-control' placeholder='Address' ></div><div class='form-group'>  <input id='address_line2["+ counter +"]' name='address_line2["+ counter +"]' type='text' class='form-control' placeholder='Address Line 2 (optional)'></div>  </div>  <div class='col-md-4'><div class='form-group'>  <label>City, State, Zip</label>  <input id='city["+ counter +"]' name='city["+ counter +"]' type='text' class='form-control' placeholder='City' ></div><div class='form-row'>  <div class='col-md-4'><div class='form-group'>  <input id='state["+ counter +"]' name='state["+ counter +"]' type='text' class='form-control' placeholder='State' ></div>  </div>  <div class='col-md-8'><div class='form-group'>  <input id='zip["+ counter +"]' name='zip["+ counter +"]' type='text' class='form-control' placeholder='Zip' ></div>  </div></div>  </div>  <div class='col-md-4'><div class='form-group'>  <label class='required'>Contact Information</label>  <input id='email["+ counter +"]' name='email["+ counter +"]' type='email' class='form-control' placeholder='Email' required></div><div class='form-group'>  <input id='phone["+ counter +"]' name='phone["+ counter +"]' type='tel' class='form-control' placeholder='Phone' pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}' title='555-555-5555' required></div></div></div>";
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
              <div class="card col-md-6 mx-auto">
                <div class="card-body">
                  <h5 class="card-title">Access Key </h5>
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
            <div class="card col-md-6 mx-auto">
              <div class="card-body">
                <h5 class="card-title">Access Key </h5>
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
        ?>

    </main>
  </div>
    <?php include "../footer.php"; ?>

    <script src="../libraries/jquery-3.4.1.min.js"></script>
    <script src="../libraries/popper-1.16.0.min.js"></script>
    <script src="../libraries/bootstrap-4.4.1/js/bootstrap.min.js"></script>

</body>

</html>

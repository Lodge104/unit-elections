<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=X-UA-Compatible content="IE=Edge,chrome=1" />
    <meta name=viewport content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />

    <title>Voting | Unit Elections Portal | Occoneechee Lodge - Order of the Arrow, BSA</title>

    <link rel="stylesheet" href="libraries/bootstrap-4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="libraries/fontawesome-free-5.12.0/css/all.min.css">
    <link rel="stylesheet" href="https://use.typekit.net/awb5aoh.css" media="all">
    <link rel="stylesheet" href="style.css">


</head>

<body class="d-flex flex-column h-100" id="section-conclave-report-form" data-spy="scroll" data-target="#scroll" data-offset="0">
  <div class="wrapper">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="https://lodge104.net">
            <img src="/assets/lodge-logo.png" alt="Occoneechee Lodge" class="d-inline-block align-top">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse c-navbar-content" id="navbar-main">
            <div class="navbar-nav ml-auto">
                <a class="nav-item nav-link" href="https://lodge104.net" target="_blank">Occoneechee Lodge Home</a>
            </div>
        </div>
    </nav>

    <main class="container-fluid flex-shrink-0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <div class="row">
            <div class="col-12">
                <section>
                    <?php
                    if ($_GET['error'] == 1) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Something went wrong. Please try again. If this continues, please <a href="#" data-toggle="modal" data-target="#contact">contact the Lodge leadership team</a>.
                        <button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
                    </div>
                    <?php } ?>
                </section>
            </div>
        </div>
        <?php

        include 'unitelections-info.php';

        if (isset($_GET['accessKey'])) {
          if (preg_match("/^([a-z\d]){8}-([a-z\d]){4}-([a-z\d]){4}-([a-z\d]){4}-([a-z\d]){12}$/", $_GET['accessKey'])) {
            $accessKey = $_POST['accessKey'] = $_GET['accessKey'];
            if(!isset($_COOKIE[$accessKey]) || $_GET['ignorePreviousSubmission'] == "true") {
              //let them vote
              $conn = new mysqli($servername, $username, $password, $dbname);
              // Check connection
              if ($conn->connect_error) {
              	die("Connection failed: " . $conn->connect_error);
              }

              $unitInfoQuery = $conn->prepare("SELECT * from unitElections where accessKey = ?");
              $unitInfoQuery->bind_param("s", $accessKey);
              $unitInfoQuery->execute();
              $unitInfoQ = $unitInfoQuery->get_result();
              if ($unitInfoQ->num_rows > 0) {
                $unitInfo = $unitInfoQ->fetch_assoc();

                ?><h2>Unit Election: <?php echo $unitInfo['unitCommunity'] . " " . $unitInfo['unitNumber']; ?></h2>
                <?php

                $tz = 'America/New_York';
                $timestamp = time();
                $dt = new DateTime("now", new DateTimeZone($tz));
                $dt->setTimestamp($timestamp);

                $date = $dt->format("Y-m-d");
                $hour = $dt->format("H");
                if (($unitInfo['open'] == 'Yes')) {
                  //if the today is the date of the election and its between 5pm and 8:59pm ET then allow voting
                  $eligibleScoutsQuery = $conn->prepare("SELECT * from eligibleScouts where unitId = ?");
                  $eligibleScoutsQuery->bind_param("s", $unitInfo['id']);
                  $eligibleScoutsQuery->execute();
                  $eligibleScoutsQ = $eligibleScoutsQuery->get_result();
                  if ($eligibleScoutsQ->num_rows > 0) {
                    // there are eligibleScouts

                    //insert instructions and video embed here
                    if (!isset($_GET['watchedVideo'])) {
                    ?>
                    <div class="card mb-3">
                      <div class="card-body">
                        <h5 class="card-title">Election Information</h5>
                        <div>The	Order	of	the	Arrow	is	Scouting’s	National	Honor	Society.	The	fourfold purpose of	the	OA	is:
                          <ol class="mb-3">
                            <li>To	recognize	those	campers	– Scouts	and	Scouters	– who	best	exemplify	the Scout	Oath	and	Law	in	their	daily	lives,	and	by	such	recognition	cause	other campers	to	conduct	themselves	in	such	manner	as	to	warrant	recognition.</li>
                            <li>To	develop	and	maintain	camping	traditions	and	spirit.</li>
                            <li>To	promote	Scout	camping,	which	reaches	its	greatest	effectiveness	as	a	part of	the	unit’s	camping	program,	both	year-round	and	in	the	summer	camp,	as directed	by	the	camping	committee	of	the	council.</li>
                            <li>To	crystallize	the	Scout	habit	of	helpfulness	into	a	life	purpose	of	leadership in	cheerful	service	to	others.</li>
                          </ol>

                        Youth	membership	qualifications:
                        <ol class="mb-3">
                          <li>Registered	member	of	the	Boy	Scouts	of America</li>
                          <li>Hold	the	rank	of	First	Class,	hold	the	Scouts	BSA	First	Class	rank,	the	Venturing	Discovery	Award,	or	the	Sea	Scout	Ordinary	rank	or	higher</li>
                          <li>In	the	past	two	years,	have	completed	fifteen	(15)	days	and	nights	of	camping	under	the	auspices	of	the	Boy	Scouts	of	America.		The	fifteen	days	and	nights	of	camping	must	include	one	long-term	camp	of	six	days	and	five	nights,	and	the	balance	of	the	camping	must	be	short-term	(1,	2,	or	3	night)	camping	trips.</li>
                          <li>Scoutmaster	approval</li>
                        </ol></div>
                      </div>
                    </div>
  					<div class="row">
					<div class="col-auto">
						<a href="thanks.php?error=1" class="btn btn-outline-secondary">Cancel</a></div>
					<div class="col-auto">
						<a href="/submit.php?accessKey=<?php echo $accessKey; ?>&watchedVideo=true<?php echo ($_GET['ignoreTime'] == 'true' ? '&ignoreTime=true' : ''); ?><?php echo ($_GET['ignorePreviousSubmission'] == 'true' ? '&ignorePreviousSubmission=true' : ''); ?>" class="btn btn-primary mb-3">I'm ready to vote!</a></div>
					</div>
                    <?php
                  } else {
                    //insert form elements here
                    ?>
                    <a href="/submit.php?accessKey=<?php echo $accessKey; ?><?php echo ($_GET['ignoreTime'] == 'true' ? '&ignoreTime=true' : ''); ?><?php echo ($_GET['ignorePreviousSubmission'] == 'true' ? '&ignorePreviousSubmission=true' : ''); ?>" class="btn btn-secondary mb-3">Show qualifications again</a>
                    <div class="card mb-3">
                      <div class="card-body">
                        <p>Remember: The election is by secret ballot, so no one will know for who you are voting for or against. The OA is not a popularity contest! Don't vote for a Scout just because they're your friend, or older than the rest. What really counts is their loyalty to the Scout Oath and Law.</p>
                        <p>Who is a friend to all? Who is pleasant and easy to get along with? Who is cheerful, even when they have many tiresome jobs to do? Who has served your unit all year round, faithfully attending meetings and helping with service projects? Do you think they will continue his service in the future? Vote for only those you believe will continue in unselfish service to your troop.</p>
                      </div>
                    </div>
                    <form method="POST" action="index-process.php" enctype="multipart/form-data" id="unitElectionForm">
                      <input type="hidden" name="unitId" id="unitId" value="<?php echo $unitInfo['id']; ?>">
                      <div class="card mb-3">
                        <div class="card-body">
                          <p>Please check the box next to each Scout you would like to vote for. You may vote for all, some, one, or none. If you do not check any boxes and click submit, it will count as a vote for none. If you do not wish to vote at all, please click cancel.</p>
                          <?php $count = 1;
                          while($eligibleScout = $eligibleScoutsQ->fetch_assoc()) {
                            $eligibleScoutsArray[] = $eligibleScout['id'];
                            if ($count > 1) { ?>
                              <hr></hr>
                            <?php } ?>
                            <div class="form-group my-2">
                                <div class="form-check mb-1">
                                    <input type="hidden" name="eligibleScout-<?php echo $eligibleScout['id']; ?>" value="0">
                                    <input name="eligibleScout-<?php echo $eligibleScout['id']; ?>" class="form-check-input" type="checkbox" value="1" id="eligibleScout-<?php echo $eligibleScout['id']; ?>">
                                    <label class="form-check-label" for="eligibleScout-<?php echo $eligibleScout['id']; ?>">
                                        <?php echo $eligibleScout['firstName'] . " " . $eligibleScout['lastName']; ?>
                                    </label>
                                </div>
                            </div>
                            <?php $count++;
                          } ?>
                        </div>
                      </div>
                      <input type="hidden" id="eligibleScouts" name="eligibleScouts" value="<?php print_r(implode(',', $eligibleScoutsArray)); ?>">
                      <input type="hidden" id="accessKey" name="accessKey" value="<?php echo $accessKey; ?>">
					  <a href="thanks.php?error=1" class="btn btn-outline-secondary">Cancel</a>
                      <button type="submit" name="submit" value="submit" class="btn btn-primary"><i class="fas fa-paper-plane pr-1"></i> Submit</button>
                      <div class="my-2"><small class="text-muted">Note: You will only be able to submit once, so make sure your ballot is correct!</small></div>
                    </form><?php
                    }
                  } else {
                    //there are not any eligibleScouts
                    ?>
                    <div class="card col-10 mx-auto">
                      <div class="card-body">
                        There are no eligible Scouts in your unit. Thanks for visiting!
                        <br>
                        <br>
                        Are you seeing this by mistake? Use the need help chat in the corner.
                      </div>
                    </div>
                    <?php
                  }
                } else {
                  //voting is not open at this time
                  ?>
                  <div class="alert alert-danger" role="alert">
                    Your unit election is not open at this time. Your unit's election is <?php echo date("l, F j, Y", strtotime($unitInfo['dateOfElection'])); ?> and must be opened by your election team.
                  </div>
                  <?php
                }
                $unitInfoQuery->close();
              } else {
                //bad accessKey
                include 'badAccess.php';
              }

              $conn->close();
            } else {
              //they already voted
              ?>
              <div class="card col-10 mx-auto">
                <div class="card-body">
                  You've already voted in this election! Thanks!
					<br>
                        <br>
                        Are you seeing this by mistake? Use the need help chat in the corner.
                </div>
              </div>
              <?php
            }
          } else {
            include 'badAccess.php';
          }
        } else {
          //accessKey bad
          include 'badAccess.php';
        }


        ?>
    </main>
  </div>
    <?php include "footer.php"; ?>

    <script src="libraries/jquery-3.4.1.min.js"></script>
    <script src="libraries/popper-1.16.0.min.js"></script>
    <script src="libraries/bootstrap-4.4.1/js/bootstrap.min.js"></script>

</body>

</html>
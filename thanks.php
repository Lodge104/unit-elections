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

    <title>Unit Elections Portal | Occoneechee Lodge - Order of the Arrow, BSA</title>

    <link rel="stylesheet" href="libraries/bootstrap-4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="libraries/fontawesome-free-5.12.0/css/all.min.css">
    <link rel="stylesheet" href="libraries/fontawesome-free-5.12.1-web/css/all.min.css">
    <link rel="stylesheet" href="https://use.typekit.net/awb5aoh.css" media="all">
    <link rel="stylesheet" href="style.css">
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-37461006-19"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-37461006-19');
</script>



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
                        <strong>Error!</strong> Your vote did not save. If this continues, please use the red Need Help button in the bottom right hand corner to contact us.
                        <button type="button" class="close" data-dismiss="alert"><i class="fas fa-times"></i></button>
                    </div>
                    <?php } ?>
                </section>
            </div>
        </div>
		<?php
        if ($_GET['success'] == 1) { ?>
        <div class="alert alert-success" role="alert">
            <strong>Thanks!</strong> Your votes have been submitted!
        </div>
		<?php } ?>
        <div class="row">
          <div class="col-12">
            <div class="card mb-3">
              <div class="card-body">
                <h3 class="card-title">Are you already a Lodge member?</h3>
                <div class="row">
                  <div class="col-md-8 mb-md-auto mb-3">
                    Don't forget to keep your Lodge Dues current! Paying your dues is a simple and great way to stay engaged with the lodge and support its mission and programs!
                  </div>
                  <div class="col-md-4">
                    <a class="btn btn-primary btn-block" href="https://lodge104.net/dues" target="_blank">Pay Dues!</a>
                  </div>
                </div>
                <hr></hr>
				<div class="row">
                  <div class="col-md-8 mb-md-auto mb-3">
                    <strong>Seal Your Membership in the Order of the Arrow: Join us for the Brotherhood</strong>
                    <br>
                    Has it been more than 6 months since you successfully completed your Ordeal experience and became a member of the Order of the Arrow? Join us for the Brotherhood. This worthwhile experience, which is completely different than your Ordeal, is not to be missed.
				  </div>
				  <div class="col-md-4">
				    <a class="btn btn-primary btn-block" href="https://lodge104.net/brotherhood" target="_blank">Learn More!</a>
			  	  </div>
				</div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card mb-3">
              <div class="card-body text-center">
                <h3 class="card-title">Stay Connected</h3>
                <p class="footer-socialmedia">
                  <a href="https://www.facebook.com/lodge104" target="_blank"><i class="fab fa-facebook-square fa-2x"></i></a>
                  <a href="https://twitter.com/lodge104" target="_blank"><i class="fab fa-twitter-square fa-2x"></i></a>
                  <a href="https://www.instagram.com/lodge104/" target="_blank"><i class="fab fa-instagram-square fa-2x"></i></a>
				  <a href="https://www.snapchat.com/add/lodge104/" target="_blank"><i class="fab fa-snapchat-square fa-2x"></i></a>
				  <a href="https://youtube.com/oalodge104" target="_blank"><i class="fab fa-youtube-square fa-2x"></i></a>
				  <a href="https://www.flickr.com/photos/144663840@N05/albums" target="_blank"><i class="fab fa-flickr fa-2x"></i></a>
                </p>
              </div>
            </div>
            <div class="list-group mb-3">
              <a href="https://lodge104.net/calendar/conclave/" class="list-group-item list-group-item-action" target="_blank">Register for Conclave</a>
              <a href="https://lodge104.net/sharon-a-mcdonald-campership-fund/" class="list-group-item list-group-item-action" target="_blank">OA High Adventure</a>
              <a href="https://camping.lodge104.net/" class="list-group-item list-group-item-action" target="_blank">Where to go Camping Guide</a>
			  <a href="https://store.lodge104.net/" class="list-group-item list-group-item-action" target="_blank">Online Trading Post</a>
            </div>

            <a href="https://registration.lodge104.net" target="_blank" class="btn btn-primary btn-block mb-3">Event Registration Portal</a>
          </div>

          <div class="col-md-9">
            <div class="card mb-3">
              <div class="card-body text-center">
                <h3 class="card-title">What is the Order of the Arrow?</h3>
                <div class="mt-3 col-10 mx-auto embed-responsive embed-responsive-16by9">
                  <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/AyjHIxfWUuc?controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
            </div>
          </div>

        </div>
      </main>
    </div>
      <?php include "footer.php"; ?>

      <script src="libraries/jquery-3.4.1.min.js"></script>
      <script src="libraries/popper-1.16.0.min.js"></script>
      <script src="libraries/bootstrap-4.4.1/js/bootstrap.min.js"></script>
  </body>


  </html>

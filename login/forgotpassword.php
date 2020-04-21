<?php
$userrole = 'loginpage';
$title = 'Forgot Password';
require 'misc/pagehead.php';
?>

<script src="js/forgotpassword.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/additional-methods.min.js"></script>

<head>
    <meta http-equiv=X-UA-Compatible content="IE=Edge,chrome=1" />
    <meta name=viewport content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />

    <title>Unit Election Administration | Occoneechee Lodge - Order of the Arrow, BSA</title>

    <link rel="stylesheet" href="../libraries/fontawesome-free-5.12.0/css/all.min.css">

</head>
<body id="dashboard">

  <?php require 'misc/pullnav.php'; ?>

    <div class="container logindiv wrapper">
		<div class="card col-sm">		
        <div class="card-body">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form class="text-center" id="forgotpassword" name="forgotpassword" method="post">
                <h3 class="form-signup-heading"><?php echo $title;?></h3>
                <br>
                <div class="form-group">
                    <input name="email" id="email" type="text" class="form-control input-lg" placeholder="Email Address" autofocus> </div>
                <div class="form-group">
                    <button name="Submit" id="submitbtn" class="btn btn-lg btn-primary btn-block" type="submit">Send Reset Email</button>
                </div>
            </form>
            <div id="message"></div>
            <p id="orlogin" class="text-center"><a href="index.php">Return to Login</a></p>
        </div>
        <div class="col-sm-4"></div>
    </div>
	</div>
	</div>
    <script>
        $("#forgotpassword").validate({
            rules: {
                email: {
                    email: true
                    , required: true
                }
            }
        });
    </script>
<?php include "../footer.php"; ?>
</body>
</html>

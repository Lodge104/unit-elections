<?php
if ((isset($_SESSION)) && array_key_exists('username', $_SESSION)) {
    session_destroy();
}
$userrole = 'loginpage';
$title = 'Create an Account';
require 'misc/pagehead.php';
?>

<script src="js/signup.js"></script>
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
            <form class="text-center" id="usersignup" name="usersignup" method="post" action="ajax/createuser.php">
                <h3 class="form-signup-heading"><?php echo $title;?></h3>
                <br>
				 <div class="alert alert-danger" role="alert">
                <h5 class="alert-heading">Lodge Members Only</h5>
                Only lodge members who are part of Chapter or Lodge Leadership are allowed to register for an account. All accounts are manually approved before access is given. Please use the email associated with your LodgeMaster profile.
              </div>
				<br>
                <input name="newuser" id="newuser" type="text" class="form-control input-lg" placeholder="Email" autofocus>
                <div class="form-group">
                    <input name="email" id="email" type="text" class="form-control input-lg" placeholder="Repeat Email"> </div>
                <div class="form-group">
                    <input name="password1" id="password1" type="password" class="form-control input-lg" placeholder="Password">
                    <input name="password2" id="password2" type="password" class="form-control input-lg" placeholder="Repeat Password"> </div>
                <div class="form-group">
                    <button name="Submit" id="submitbtn" class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
                </div>
            </form>
            <div id="message"></div>
            <p id="orlogin" class="text-center">or <a href="index.php">Login</a></p>
        </div>
        <div class="col-sm-4"></div>
		<div class="col-sm-4"></div>
    </div>
	</div>
	</div>
    <?php $conf = new PHPLogin\AppConfig; ?>
        <script>
            $("#usersignup").validate({
                rules: {
                    email: {
                        email: true
                        , required: true
                    }
					, newuser: {
						equalTo: "#email"
					}
                    , password1: {
                        required: true
                        <?php if ($conf->password_policy_enforce == true) {
    echo ", minlength: ". $conf->password_min_length;
}; ?>
                    }
                    , password2: {
                        equalTo: "#password1"
                    }
                }
            });
        </script>
<?php include "../footer.php"; ?>	
</body>
</html>

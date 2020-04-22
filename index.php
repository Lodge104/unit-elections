<?php
$title = 'Unit Elections Portal | Occoneechee Lodge - Order of the Arrow, BSA';

include "login/misc/pagehead.php";
?>
<head>
    <meta http-equiv=X-UA-Compatible content="IE=Edge,chrome=1" />
    <meta name=viewport content="width=device-width,initial-scale=1.0,maximum-scale=1.0" />

    <title>Unit Elections Portal | Occoneechee Lodge - Order of the Arrow, BSA</title>


	<link rel="stylesheet" href="libraries/fontawesome-free-5.12.0/css/all.min.css">

</head>
<body>

<?php require 'login/misc/pullnav.php'; ?>

  <div class="wrapper">

    <main class="container-fluid col-xl-11">

<?php
if ($auth->isLoggedIn()) {
    echo '<div class="card mb-3"><div class="card-body"><div class="jumbotron text-center"><h1>Occoneechee Lodge<br>Unit Elections Portal</h1>
    <p>Click on your username in the top right corner to expose more menu options</p></div>
    <div class="col-lg-2"></div><div class="col-lg-8">
    <h2>Menu Items:</h2>

    <p><b><em><i class="fas fa-tachometer-alt pr-1"></i> Chapter Dashboard</em></b> - View all active unit elections in the lodge sorted by Chapter. Please only edit elections from your chapter</p>
	<p><b><em><i class="fas fa-plus pr-1"></i> Create Unit Election</em></b> - After a unit emails to request a unit election, use this to create a unit election record. Then send the Unit Leader Access Key and this link: <span class="badge badge-danger">https://elections.lodge104.net/</span> to the Unit Leader and ask them to add all eligible scouts on their dashboard</p>
	<p><b><em><i class="fas fa-poll-h pr-1"></i> Election Results</em></b> - Once an election is complete, use the Access Key to view the results of the election. Unit Leaders have an identical results page on their dashboard.</p>
	<h4>In Dropdown:</h4>
	<p><b><em>Edit Profile</em></b> - Edit your own user profile information including your name, contact info, avatar, etc</p>
    <p><b><em>Account Settings</em></b> - Change your email address and/or password</p>';

    if ($auth->isAdmin()) {
        echo '<h4>Admin Only In Dropdown:</h4>';
		echo '<p><b><em>Export Results</em></b> - View elections results for each unit and export them to a CSV file</p>';
		echo '<p><b><em>Manage Active Users</em></b> - Admin manage active users and/or ban trolls</p>';
        echo '<p><b><em>Verify/Delete Users</em></b> - Admin mass verify or delete new user requests</p>';
        echo '<p><b><em>Mail Log</em></b> - Admin mail status logging</p>';
    }

    if ($auth->isSuperAdmin()) {
        echo '<p><b><em>Edit Site Config</em></b> - Superadmin edit site configuration in one page</p></div></div>';
    }
} else {
    echo '<div class="row">
		<div class="card col-sm">
              <div class="card-body">
			   <form action="/unitleader/" method="get">
                <h3 class="form-signin-heading text-center">Unit Leader Login</h3>
                  <div class="form-group">
                    <label for="accessKey" class="required">Access Key</label>
                    <input type="text" id="accessKey" name="accessKey" class="form-control" required>
                  </div>
                  <input type="submit" class="btn btn-lg btn-primary btn-block" value="Submit">
                </form>
              </div>
				</div>
		<div class="card col-sm">		
              <div class="card-body">
                <h3 class="form-signin-heading text-center">Chapter Login</h3><br>
				    <p class="text-center"><a href="../login/forgotpassword.php">Forgot your account?</a></p>
					<p class="text-center">or <a href="../login/signup.php">Create an Account</a></p>
                    <a href="/login"class="btn btn-lg btn-primary btn-block" role="button" aria-disabled="true">Log In</a>
        </div>
        <div class="col-sm-4"></div>
    </div>
              </div>
            </div>
            </div>';
}

?>
		</div><div class="col-lg-2"></div>
	  </div>
	  </main>
    </div>
    <?php include "footer.php"; ?>

    <script src="../libraries/jquery-3.4.1.min.js"></script>
    <script src="../libraries/popper-1.16.0.min.js"></script>
    <script src="../libraries/bootstrap-4.4.1/js/bootstrap.min.js"></script>
	<script src="https://elections.lodge104.net/login/js/login.js"></script>
</body>
</html>

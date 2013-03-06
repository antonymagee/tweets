<?php
/* 
   Created By Antony Magee @ antony.magee@gmail.com 
   -----------------------January 2013 ----------------------- 
*/
	// Initializing Session to carry over user ID
	session_start();

	
// Checks if a user is logged in, if they are, continue, if not, drop out to login page and give approiate not logged in error
if (isset($_SESSION['id'])) {

	// Check if e-mail has been sent from form, aka new user has attempted ot be added	
	if ($_POST['email']) {
	  //Connect to the database through our include 
	  include_once "connect.php";

	  // This block stips the email of slashes, HTML tags and escapes it for use in the database
	  $email = stripslashes($_POST['email']);
	  $email = strip_tags($email);
	  $email = mysql_real_escape_string($email);

	  // filter everything but numbers and letters
	  $password = $_POST['pass']; 

	  //$password = md5($password);
	 
	  // gets the value form the admin radial button
	  $adminradio = $_POST['admincheck'];
	
	  // gets all the data from the members table where the email = the email entered in the new user form
	  $test = mysql_query("SELECT * FROM members HAVING email = '$email'") or die(mysql_error());
	  
	  if (mysql_num_rows($test) > 0){
	  	//$sql = mysql_query("INSERT INTO members VALUES('', '$email', '$password', '', '1', '$unqPage')") or die(mysql_error());
	  	//echo $unqPage;

	  	// If there is already an e-mail or password in the database this will pass an alert to the page to give
	  	// 'record already exists' error
	  	header('location: admin.php?l=2');	  	
	  } else {
	  
	  // if the admin button has been clicked the first section of this IF fires and adds a users with the admin column set to '1'
	  // if a non admin user us added the second part of this statement is fired and adds the user with an admin value of '0'
	  if ($adminradio == '1')
	  	{
	  	$sql = mysql_query("INSERT INTO members VALUES('', '$email', '$password', '', '1')") or die(mysql_error());
	  	} else 
	  		{
	  		$sql = mysql_query("INSERT INTO members VALUES('', '$email', '$password', '', '')") or die(mysql_error());
	  		}
	
	  
	 
	}// close if post
	}// end of first if that checks for duplicate data
	} else {
	    header("location: signin.php?l=4");
	    exit(); 
	}

	?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="shortcut icon" href="img/favicon.ico" />
		<meta charset="utf-8" />
		<title>ILoveqc Social</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<!-- Le styles -->
		<link href="css/bootstrap.css" rel="stylesheet" />
		<style type="text/css">
			body {
			padding-top: 40px;
			padding-bottom: 40px;
			background-color: #f5f5f5;
			}
			.form-signin {
			max-width: 300px;
			padding: 19px 29px 29px;
			margin: 0 auto 20px;
			background-color: #fff;
			border: 1px solid #e5e5e5;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			box-shadow: 0 1px 2px rgba(0,0,0,.05);
			}
			.form-signin .form-signin-heading,
			.form-signin .checkbox {
			margin-bottom: 10px;
			}
			.form-signin input[type="text"],
			.form-signin input[type="password"] {
			font-size: 16px;
			height: auto;
			margin-bottom: 15px;
			padding: 7px 9px;
			}
		</style>
		<link href="css/bootstrap-responsive.css" rel="stylesheet" />
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
		<![endif]-->
		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-57-precomposed.png" />
		<link rel="shortcut icon" href="favicon.png" />
	</head>
	<script src="js/jquery-1.9.1.min.js"></script>
	<script scr="js/bootstrap-alert.js"></script>  
	<script type="text/javascript">
		<!-- Form Validation -->
		function validate_form ( ) { 
			valid = true; 
				if ( document.logform.email.value == "" ) { 
				alert ( "Please enter your Email" ); 
				valid = false;
			}
				if ( document.logform.pass.value == "" ) { 
				alert ( "Please enter your password" ); 
				valid = false;
			}
			return valid;
		}
		<!-- Form Validation -->
	</script>
	<body>
		<div class="container">
			<form class="form-signin" action="admin.php" method="post" enctype="multipart/form-data" name="logform" id="logform" onsubmit="return validate_form ( );">
				<fieldset>
					<h2 class="form-signin-heading">Add User</h2>
					<input type="text" name="email" id="email" class="input-block-level" placeholder="Email address" />
					<input type="text" name="pass" id="pass" class="input-block-level" placeholder="Password" />
					<span class="help-block">Only [^A-Za-z0-9] to be used for passwords.</span>
					<p>
						<button class="btn btn btn-primary" type="submit">Add User</button>
						<a class="btn btn-warning" href="logout.php" type="button">logout</a>
					<p>      
						<label class="checkbox">
						<input type="checkbox" name="admincheck" id="admincheck" value="1" /> Make Admin
						</label>
						<?php
							// handles the display of the alerts that get passed to the page,
							// http://twitter.github.com/bootstrap/javascript.html#alerts
							$out = $_GET['l'];
							if ($out == 2)  {
							    	   echo "<div class='alert alert-block alert-error'>
							  <a class='close' data-dismiss='alert'>×</a>
							  <strong>Warning!</strong>
							 That user already exists!!
							</div>";
							  		}   
							
							
							  		?>
				</fieldset>
			</form>
		</div>
		<!-- /container -->
		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
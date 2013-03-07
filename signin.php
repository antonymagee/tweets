<?php
   /* 
   Created By Antony Magee @ antony.magee@gmail.com 
   -----------------------January 2013 ----------------------- 
   */
   if ($_POST['email']) {
     //Connect to the database through our include 
     include_once "connect.php";

     $email = stripslashes($_POST['email']);
     $password = $_POST['password']; // filter everything but numbers and letters
     
     $sql = '
      SELECT
        `password`
      FROM `members`
        WHERE
          `email` = "' . mysql_real_escape_string($email) . '"
      LIMIT 1
      ;';

    $r = mysql_fetch_assoc(mysql_query($sql));

    // The first 64 characters of the hash is the salt
    $salt = substr($r['password'], 0, 64);

    $hash = $salt . $password;

    // Hash the password just like before
    for ( $i = 0; $i < 100000; $i ++ ) {
      $hash = hash('sha256', $hash);
    }

    $hash = $salt . $hash;

    //echo $hash;
    //echo $r['password'];

    if ( $hash == $r['password'] ) {

      $addDetails = mysql_query("SELECT * FROM members WHERE email='$email' "); 

      while($row = mysql_fetch_array($addDetails))
             { 
               // Get member ID into a session variable
               $id = $row["id"];   
               session_start();
               $_SESSION['id'] = $id;
               // Update last_log_date field for this member now
               mysql_query("UPDATE members SET time_logged=now() WHERE id='$id'"); 
               // Print success message here if all went well then exit the script
               $adchk = $row['admin'];   

               // this deals with if the user is an admin or not, if they are they are redirected to the add user page
               // if the user is not an admin they are simple redirected to the 
               if ($adchk === '1'){
                 header("location: admin.php");
               } else {
                 header("location: index.php"); 
                 exit();
               }
               
             } // close while

      // Ok!
    } else {
      // Print login failure message to the user and link them back to your login page
      header("location: signin.php?l=2");
    }


   }// close if post
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <link rel="shortcut icon" href="img/favicon.ico" />
      <meta charset="utf-8" />
      <title>ILoveqc Social</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="IloveQC Social Login Page" content="" />
      <meta name="antony.magee@gmail.com" content="" />
      <!-- Le styles -->
      <link href=" css/bootstrap.css" rel="stylesheet" />
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
      <link href="ss/bootstrap-responsive.css" rel="stylesheet" />
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
   <script type="text/javascript">
      $(".alert").alert()
      
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
         <form class="form-signin" action="signin.php" method="post" enctype="multipart/form-data" name="logform" id="logform" onsubmit="return validate_form ( );">
            <h2 class="form-signin-heading">Please sign in</h2>
            <input name="email" type="text" id="email" class="input-block-level" placeholder="Email address" />
            <input name="password" type="password" id="password" class="input-block-level" placeholder="Password" />
            <button class="btn btn-primary" type="submit">Sign in</button>
            </br>
            <p>
            <p>
               <?php 
               // Pulls the variable passed back from the URL, a 3 is passed from the logour page on die() to display a successful logout
               // a 4 is from any page that has been tried to be accessed via url when a user is not logged in, these are displayed below the 
               // login in form.
                  $out = $_GET['l'];
                  if ($out == 3) {
                    echo "<div class='alert alert-block alert-success'>
                              <a class='close' data-dismiss='alert'>×</a>
                              <strong>Success</strong>
                             You have logged out!!!
                          </div>";
                  }
                  if ($out == 4)  {
                    echo "<div class='alert alert-block alert-error'>
                              <a class='close' data-dismiss='alert'>×</a>
                              <strong>Error!</strong>
                             You must login before accessing that page!
                          </div>";
                  } 
                  if ($out == 2)  {
                    echo "<div class='alert alert-block alert-error'>
                              <a class='close' data-dismiss='alert'>×</a>
                              <strong>Error!</strong>
                              The information you entered is incorrect!
                          </div>";
                  } ?>
         </form>
      </div> <!-- /container -->
      <script src="js/jquery-1.9.1.min.js"></script>
      <script src="js/bootstrap.js"></script>
   </body>
</html>
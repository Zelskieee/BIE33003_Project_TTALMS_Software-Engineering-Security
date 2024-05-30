<?php
session_start();

error_reporting(0);
include('includes/config.php');
if ($_SESSION['alogin'] != '') {
  $_SESSION['alogin'] = '';
}
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $cipher = "aes-256-cbc";
  //Generate a 256-bit encryption key 
  $encryption_key = hash('sha256', $cipher);
  $password = openssl_encrypt($_POST['password'], $cipher, $encryption_key, 0, 256);
  $sql = "SELECT UserName,Password FROM admin WHERE UserName=:username";
  //$sql ="SELECT UserName,Password FROM admin WHERE UserName=:username and Password=:password";
  $query = $dbh->prepare($sql);
  $query->bindParam(':username', $username, PDO::PARAM_STR);
  //$query-> bindParam(':password', $password, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);
  //echo $results[0];
  //echo $results[0]->Password;
  //	if(password_verify($password, $results[0]->Password)) {
  //      echo 'Password is correct, logged in!';
  //  } else {
  //      echo 'Password is wrong, try again';
  //  }
  //print_r($results);
  //	exit();

  if ($password == $results[0]->Password) {
    $_SESSION['alogin'] = $_POST['username'];
    header("Location: sendotp.php");
    // echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
  } else {
    echo "<script>alert('Invalid Details');</script>";
  }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>TTALMS - Librarian Login</title>
  <link rel="icon" type="image/x-icon" href="assets\img\icon_ttalms.ico">
  <!-- BOOTSTRAP CORE STYLE  -->
  <link href="assets/css/bootstrap.css" rel="stylesheet" />
  <!-- FONT AWESOME STYLE  -->
  <link href="assets/css/font-awesome.css" rel="stylesheet" />
  <!-- CUSTOM STYLE  -->
  <link href="assets/css/style.css" rel="stylesheet" />
  <!-- GOOGLE FONT -->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
  <!-- ICON -->
  <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script> 

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  


  <style>
    .error {
      color: #FF0000;
    }

    .capt {
      background-color: #fff;
      width: 290px;
      height: 40px;

    }

    .fade {
      animation-name: fade;
      animation-duration: 1.5s;
    }

    @keyframes fade {
      from {
        opacity: .4
      }

      to {
        opacity: 1
      }
    }

    #mainCaptcha {
      font-family: Arial, sans-serif;
      font-size: 24px;
      color: #333;
      /* Text color */
      background-color: #f5f5f5;
      /* Background color */
      padding: 10px;
      /* Padding around the text */
      border-radius: 8px;
      /* Rounded corners */
      text-align: center;
      /* Center the text */
      padding-bottom: 10px;
      border: 2px solid #333;
      /* Border style */
      -webkit-user-select: none;
      /* Chrome, Safari, Opera */
      -moz-user-select: none;
      /* Firefox */
      -ms-user-select: none;
      /* IE 10+ */
      user-select: none;
      /* General syntax */
    }

    #refresh {
      position: relative;
      left: 310px;
      width: 30px;
      height: 30px;
      bottom: 45px;
      background-image: url(rpt.jpg);
    }

    input[type="submit"] {
      padding: 17px 40px;
      border-radius: 50px;
      cursor: pointer;
      border: 0;
      background-color: white;
      color: black;
      box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
      font-weight: bold;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      font-size: 15px;
      transition: all 0.5s ease;
    }

    /* Define the styles for hover effect */
    input[type="submit"]:hover {
      letter-spacing: 3px;
      background-color: #9B00EA;
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }

    /* Define the styles for active effect */
    input[type="submit"]:active {
      letter-spacing: 3px;
      background-color: #9B00EA;
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 0px 0px 0px;
      transform: translateY(10px);
      transition: 100ms;
    }
  </style>

  <script>
    function Captcha() {
      var alpha = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
      var i;
      var code = '';
      for (i = 0; i < 6; i++) {
        var randomChar = alpha[Math.floor(Math.random() * alpha.length)];
        code += randomChar + ' ';
      }
      document.getElementById("mainCaptcha").textContent = code.trim();
    }

    function ValidCaptcha() {
      var string1 = removeSpaces(document.getElementById('mainCaptcha').textContent); // Get the text content of the CAPTCHA
      var string2 = removeSpaces(document.getElementById('txtInput').value); // Get the user input
      if (string1 !== string2) { // Compare the strings
        alert('Invalid CAPTCHA. Please try again.'); // Show an alert if they don't match
        return false;
      }
      return true; // Return true if the CAPTCHA is valid
    }

    function removeSpaces(string) {
      return string.split(' ').join('');
    }

    function placeOrder(form) {
      form.submit();
    }
  </script>

  <script type="text/javascript" src="script.js"></script>

</head>

<body onload="Captcha();">

  <!------MENU SECTION START-->
  <?php include('includes/header.php'); ?>

  <!-- MENU SECTION END-->
  <div class="content-wrapper">
    <div class="container">
      <div class="row pad-botm">
        <div class="col-md-12">
          <h4 class="header-line" style="text-align: center; position: relative; font-weight: unbold;"><i class="fa-solid fa-book-open-reader fa-beat"></i> LIBRARIAN LOGIN FORM</h4>
        </div>
      </div>

      <!--LOGIN PANEL START-->
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
          <div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
            <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
              <i class="fas fa-sign-in-alt" style="margin-right: 5px;"></i> LIBRARIAN LOGIN FORM
            </div>
            <div class="panel-body">
              <form role="form" method="post" id="my_form" action="adminlogin.php" onsubmit="return ValidCaptcha()">

                <div class="form-group">
                  <label><i class="fa fa-user" style="margin-right: 5px;"></i> Librarian Username</label>
                  <input class="form-control" type="text" name="username" placeholder="Librarian Username..." autocomplete="off" required />
                </div>

                <div class="form-group">
                  <label><i class="fa fa-lock" style="margin-right: 5px;"></i> Password</label>
                  <input class="form-control" type="password" name="password" placeholder="Password..." autocomplete="off" required />
                </div>

                <div class="capt">
                  <h2 type="text" id="mainCaptcha"></h2>
                  <p><button id="refresh" onclick="Captcha()" style="border-radius: 8px;"><i class="fa fa-refresh" style="margin-right: 5px;"></i></button></p>
                </div>

                <div class="form-group">
                  <input type="text" id="txtInput" class="form-control" autocomplete="off" style="margin-top: 25px;">
                  <span class="error"><?php echo $validCaptchaErr; ?></span>
                </div>

                <input type="hidden" name="login" value="login">
                <input type="submit" name="login" class="btn btn-primary" onclick="return ValidCaptcha();" value="Login">
              </form>

            </div>
          </div>
        </div>
      </div>

      <!-- CONTENT-WRAPPER SECTION END-->
      <?php include('includes/footer.php'); ?>
      <!-- FOOTER SECTION END-->
      <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS  -->
      <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
      <script src="assets/js/custom.js"></script>
      </script>

</body>

</html>
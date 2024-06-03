<?php
include('includes/config.php');
error_reporting(0);
session_start();
$validCaptchaErr = "";
$loginErr = "";
$studid = "";
$password = "";

if (isset($_POST['login'])) {
  // username and password sent from form 
  $studid = $_POST['studid'];
  $password = $_POST['password'];
  $myusername = mysqli_real_escape_string($db, $_POST['studid']);
  $cipher = "aes-256-cbc";
  //Generate a 256-bit encryption key 
  $encryption_key = hash('sha256', $cipher);
  $mypassword = openssl_encrypt(mysqli_real_escape_string($db, $_POST['password']), $cipher, $encryption_key, 0, 256);

  $sql = "SELECT * FROM students WHERE matricNo = '$myusername' and Password = '$mypassword' ";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $count = mysqli_num_rows($result);
  // If result matched $myusername and $mypassword, table row must be 1 row

  if ($count == 1 && $row["Status"] == '1') {
    $_SESSION['login'] = $_POST['studid'];
    $_SESSION["stdid"] = $_POST['studid'];
    header('location:resend-otp.php');
    // echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
    $loginErr = "";
  } else if ($count == 1 && $row["Status"] == '0') {
    echo "<script>alert('Your account has been deactive, Please pay your fines to active your account.');</script>";
    echo "<script type='text/javascript'> document.location ='index.php#ulogin'; </script>";
  } else {
    echo "<script>alert('Please enter valid Student ID and Password');</script>";
    echo "<script type='text/javascript'> document.location ='index.php#ulogin'; </script>";
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
  <title>TTALMS - Student Login</title>
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

    .container {
      align-items: center;
    }

    .image {
      cursor: pointer;
      display: none;
    }

    .project {
      display: none;
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
          <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-house fa-beat"></i> HOME</h4>
        </div>
      </div>
      <!--Slider---->
      <div class="row">
        <div class="col-md-10 col-sm-8 col-xs-12 col-md-offset-1">
          <div id="carousel-example" class="carousel slide slide-bdr" data-ride="carousel" style="border-radius: 8px; border: 4px solid #9B00EA; overflow: hidden;">
            <div class="carousel-inner">
              <div class="item active">
                <img src="assets/img/9.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/16.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/2.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/7.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/1.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/3.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/4.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/5.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/6.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/8.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/10.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/11.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/12.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/13.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/14.png" alt="" />
              </div>
              <div class="item">
                <img src="assets/img/15.png" alt="" />
              </div>
            </div>
            <!--INDICATORS-->
            <ol class="carousel-indicators">
              <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
              <li data-target="#carousel-example" data-slide-to="1"></li>
              <li data-target="#carousel-example" data-slide-to="2"></li>
              <li data-target="#carousel-example" data-slide-to="3"></li>
              <li data-target="#carousel-example" data-slide-to="4"></li>
              <li data-target="#carousel-example" data-slide-to="5"></li>
              <li data-target="#carousel-example" data-slide-to="6"></li>
              <li data-target="#carousel-example" data-slide-to="7"></li>
              <li data-target="#carousel-example" data-slide-to="8"></li>
              <li data-target="#carousel-example" data-slide-to="9"></li>
              <li data-target="#carousel-example" data-slide-to="10"></li>
              <li data-target="#carousel-example" data-slide-to="11"></li>
              <li data-target="#carousel-example" data-slide-to="12"></li>
              <li data-target="#carousel-example" data-slide-to="13"></li>
              <li data-target="#carousel-example" data-slide-to="14"></li>
              <li data-target="#carousel-example" data-slide-to="15"></li>
              <li data-target="#carousel-example" data-slide-to="16"></li>
            </ol>
            <!--PREVIUS-NEXT BUTTONS-->
            <a class="left carousel-control" href="#carousel-example" data-slide="prev">
            </a>
            <a class="right carousel-control" href="#carousel-example" data-slide="next">
            </a>
          </div>
        </div>
      </div>
      <hr />

      <div class="row pad-botm">
        <div class="col-md-12">
          <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-graduation-cap fa-beat"></i> STUDENT LOGIN FORM</h4>
        </div>
      </div>
      <a name="ulogin"></a>

      <!--LOGIN PANEL START-->
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
              <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
              <i class="fa-solid fa-right-to-bracket"></i> LOGIN FORM </div>
              <div class="panel-body" style="padding: 20px;">
                <form role="form" method="post" id="my_form" action="index.php" onsubmit="return ValidCaptcha()">
                  <div class="form-group">
                    <label>
                      <i class="fa-solid fa-graduation-cap" style="margin-right: 5px;"></i> Student ID
                    </label>
                    <input class="form-control" type="text" name="studid" placeholder="Student ID..." autocomplete="off" value="<?php echo $studid; ?>" required>
                  </div>
                  <div class="form-group">
                    <label>
                      <i class="fa fa-lock" style="margin-right: 5px;"></i> Password
                    </label>
                    <input class="form-control" type="password" name="password" placeholder="Password..." autocomplete="off" value="<?php echo $password; ?>" required>
                    <p class="help-block"><a href="user-forgot-password.php" style="color: black; font-weight: bold; text-decoration: none;" onmouseover="this.style.color='grey'; this.style.fontWeight='normal';" onmouseout="this.style.color='black'; this.style.fontWeight='bold';">
                        Forgot Password
                      </a>
                    </p>
                  </div>
                  <div class="capt">
                    <h2 type="text" id="mainCaptcha"></h2>
                    <p><button id="refresh" onclick="Captcha()" style="border-radius: 8px;"><i class="fa-solid fa-arrows-rotate fa-spin" style="margin-right: 5px;"></i></button></p>
                  </div>
                  <div class="form-group">
                    <input type="text" id="txtInput" class="form-control" autocomplete="off" style="margin-top: 25px;">
                    <span class="error"><?php echo $validCaptchaErr; ?></span>
                  </div>
                  <input type="submit" name="login" class="btn btn-primary" onclick="return ValidCaptcha();" value="Login">
                  Don't have an account? <a href="signup.php" style="color: #9B00EA; text-decoration: none; font-weight: bold;" onmouseover="this.style.color='#808080'; this.style.fontWeight='normal';" onmouseout="this.style.color='#9B00EA'; this.style.fontWeight='bold';">Register Here</a>
                  <br>
                  <span class="error"><?php echo $loginErr; ?></span>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!---LOGIN PABNEL END-->


    </div>
  </div>
  <center>
    <div class="description mt-5 p-10">
      <h1>Project Report Repository</h1>
      <br>
      <h3>Scan QR Code</h3><br>
      <div class="container">
        <div>
          <img class="image" src="assets\img\dotnet.png" alt="Image 1" width="200" height="200" style="margin-bottom: 10px;">
          <p class="project" style="margin-bottom: 30px;">DotNet Programming</p>
        </div>
        <div>
          <img class="image" src="assets\img\cn.png" alt="Image 2" width="200" height="200" style="margin-bottom: 10px;">
          <p class="project" style="margin-bottom: 30px;">Computer Networking</p>
        </div>
        <div>
          <img class="image" src="assets\img\ds.png" alt="Image 3" width="200" height="200" style="margin-bottom: 10px;">
          <p class="project" style="margin-bottom: 30px;">Discrete Structure</p>
        </div>
        <div>
          <img class="image" src="assets\img\german.png" alt="Image 4" width="200" height="200">
          <p class="project" style="margin-bottom: 30px;">German Communication</p>
        </div>
        <div>
          <img class="image" src="assets\img\cyber.png" alt="Image 5" width="200" height="200" style="margin-bottom: 10px;">
          <p class="project" style="margin-bottom: 30px;">Cyberpreneurship</p>
        </div>
        <div>
          <img class="image" src="assets\img\cni.png" alt="Image 6" width="200" height="200">
          <p class="project" style="margin-bottom: 30px;">Creative and Innovation</p>
        </div>
        <div>
          <img class="image" src="assets\img\tcs.png" alt="Image 7" width="200" height="200" style="margin-bottom: 10px;">
          <p class="project" style="margin-bottom: 30px;">USEM Test Case Specification</p>
        </div>
        <div>
          <img class="image" src="assets\img\tds.png" alt="Image 8" width="200" height="200">
          <p class="project" style="margin-bottom: 30px;">USEM Test Design Specification</p>
        </div>
        <div>
          <img class="image" src="assets\img\vp.png" alt="Image 9" width="200" height="200" style="margin-bottom: 10px;">
          <p class="project" style="margin-bottom: 30px;">Visual Programming</p>
        </div>
        <div>
          <img class="image" src="assets\img\oop.png" alt="Image 10" width="200" height="200">
          <p class="project" style="margin-bottom: 30px;">Object-Oriented Programming</p>
        </div>
      </div>
    </div>
  </center>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const images = document.querySelectorAll(".image");
      const texts = document.querySelectorAll(".project");
      let currentIndex = 0;

      function changeImageAndText() {
        // Hide all images and text
        images.forEach(image => {
          image.style.display = "none";
        });
        texts.forEach(text => {
          text.style.display = "none";
        });

        // Show next image and text
        images[currentIndex].style.display = "block";
        texts[currentIndex].style.display = "block";

        // Update currentIndex for the next image and text
        currentIndex = (currentIndex + 1) % images.length;
      }

      setInterval(changeImageAndText, 5000);
    });
  </script>

  <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php'); ?>
  <!-- FOOTER SECTION END-->
  <script src="assets/js/jquery-1.10.2.js"></script>
  <!-- BOOTSTRAP SCRIPTS  -->
  <script src="assets/js/bootstrap.js"></script>
  <!-- CUSTOM SCRIPTS  -->
  <script src="assets/js/custom.js"></script>

</body>

</html>
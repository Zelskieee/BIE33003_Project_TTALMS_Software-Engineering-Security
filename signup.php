<?php
session_start();
include('includes/config.php');
error_reporting(0);
if (isset($_POST['signup'])) {
  //Code for student ID

  $fname = $_POST['fullanme'];
  $mobileno = $_POST['mobileno'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirmpassword = $_POST['confirmpassword'];
  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number    = preg_match('@[0-9]@', $password);
  $specialChars = preg_match('@[_^\w]@', $password);
  $ic = $_POST["ic"];
  $matricNo = $_POST["matricNo"];
  $regex = '/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/';

  if ($confirmpassword == $password) {
    if (preg_match($regex, $ic)) {

      if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 9) {
        echo "<script>
        alert('Password should be at least 10 characters in length and should include at least one upper case letter, one number, and one special character.');
        </script>";
      } else {
        $sql = "SELECT * FROM students WHERE icNo='$ic' OR matricNo='$matricNo'";
        //$sql ="SELECT EmailId,Password,StudentId,Status FROM tblstudents WHERE StudentId=:ID and Password=:password";
        $query = $dbh->prepare($sql);
        // $query-> bindParam(':ID', $ID, PDO::PARAM_STR);
        //$query-> bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        // echo var_dump($results);
        // echo count($results);
        // $query="SELECT * FROM tblstudents WHERE icNo='$ic'";
        // $condition= mysqli_query($dbh,$query) or die(mysqli_error($dbh));
        // echo mysqli_num_rows($condition);
        //exit();
        if (count($results) > 0) {
          echo "<script>
        alert('Duplicated Matric Number Or Identity Card Number.');
        </script>";
        } else {
          $cipher = "aes-256-cbc";

          //Generate a 256-bit encryption key 
          $encryption_key = hash('sha256', $cipher);
          // Generate an initialization vector 
          //Data to encrypt 
          $password = openssl_encrypt($_POST['password'], $cipher, $encryption_key, 0, 256);
          $status = 1;
          $sql = "INSERT INTO  students(FullName,MobileNumber,EmailId,Password,Status,icNo,matricNo) VALUES(:fname,:mobileno,:email,:password,:status,:ic,:matricNo)";
          $query = $dbh->prepare($sql);
          $query->bindParam(':fname', $fname, PDO::PARAM_STR);
          $query->bindParam(':ic', $ic, PDO::PARAM_STR);
          $query->bindParam(':matricNo', $matricNo, PDO::PARAM_STR);
          $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
          $query->bindParam(':email', $email, PDO::PARAM_STR);
          $query->bindParam(':password', $password, PDO::PARAM_STR);
          $query->bindParam(':status', $status, PDO::PARAM_STR);
          $query->execute();


          if ($query) {
            echo '<script>alert("Your Registration successfull")</script>';
            $_SESSION['login'] = $matricNo;
            header('location:resend-otp.php');
            // echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
          } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
          }
        }
      }
    } else {
      echo "<script>alert('Something went wrong. Please make sure your Identity Card Number with format XXXX-XX-XXXX.');</script>";
    }
  } else {
    echo "<script>
        alert('Password Not Match.');
        </script>";
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
  <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
  <title>TTALMS - Student Register</title>
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

  <script type="text/javascript">
    function valid() {
      alert('hallo');
      // if(document.signup.password.value!= document.signup.confirmpassword.value)
      // {
      // alert("Password and Confirm Password Field do not match  !!");
      // document.signup.confirmpassword.focus();
      // return false;
      // }
      // return true;
    }
  </script>
  <script>
    function checkAvailability() {
      $("#loaderIcon").show();
      jQuery.ajax({
        url: "check_availability.php",
        data: 'emailid=' + $("#emailid").val(),
        type: "POST",
        success: function(data) {
          $("#user-availability-status").html(data);
          $("#loaderIcon").hide();
        },
        error: function() {}
      });
    }
  </script>

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
</head>

<body onload="Captcha();">

  <!------MENU SECTION START-->
  <?php include('includes/header.php'); ?>
  <!-- MENU SECTION END-->
  <div class="content-wrapper">
    <div class="container">
      <div class="row pad-botm">
        <div class="col-md-12">
          <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-person fa-beat"></i> STUDENT REGISTER FORM</h4>

        </div>

      </div>
      <div class="row">

        <div class="col-md-9 col-md-offset-1">
          <div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
            <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
              <i class="fas fa-sign-in-alt" style="margin-right: 5px;"></i> REGISTER FORM
            </div>
            <div class="panel-body" style="padding: 20px;">
              <form name="signup" id="my_form" action="signup.php" method="post" onsubmit=" return ValidCaptcha()">
                <div class="form-group">
                  <label><i class="fa fa-user" style="margin-right: 5px;"></i> Full Name</label>
                  <input class="form-control" type="text" name="fullanme" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" placeholder="Full Name..." value="<?php echo $fname; ?>" required />
                </div>

                <div class="form-group">
                  <label class="form-label"><i class="fa fa-user" style="margin-right: 5px;"></i> Student ID</label>
                  <input type="text" class="form-control" name="matricNo" placeholder="AA000..." maxlength="8" id="matricNo" onkeypress="return isalphanumber(event);" ondrop="return false;" onpaste="return false;" onkeyup="this.value = this.value.toUpperCase();" required="Students ID required" value="<?php echo $matricNo; ?>" />
                </div>

                <div class="form-group">
                  <label><i class="fa fa-credit-card" style="margin-right: 5px;"></i> Identity Card Number</label>
                  <input class="form-control" type="ic" name="ic" id="ic" maxlength="14" autocomplete="off" placeholder="Identity Card Number with format XXXX-XX-XXXX..." required value="<?php echo $ic; ?>" />
                </div>

                <div class="form-group">
                  <label><i class="fa fa-phone" style="margin-right: 5px;"></i> Mobile Number</label>
                  <input class="form-control" type="text" name="mobileno" maxlength="11" autocomplete="off" placeholder="Mobile Number..." onkeypress="return (event.charCode == 8 || event.charCode == 0) ? null : event.charCode >= 48 && event.charCode <= 57" required value="<?php echo $mobileno; ?>" />
                </div>


                <div class="form-group">
                  <label><i class="fa fa-envelope" style="margin-right: 5px;"></i> Email</label>
                  <input class="form-control" type="email" name="email" id="emailid" onBlur="checkAvailability()" autocomplete="off" placeholder="Student Email..." required value="<?php echo $email; ?>" />
                  <span id="user-availability-status" style="font-size:12px;"></span>
                </div>

                <div class="form-group">
                  <label><i class="fa fa-lock" style="margin-right: 5px;"></i> Password</label>
                  <input class="form-control" type="password" name="password" autocomplete="off" placeholder="Password..." required value="<?php echo $password; ?>" />
                </div>

                <div class="form-group">
                  <label><i class="fa fa-lock" style="margin-right: 5px;"></i> Confirm Password</label>
                  <input class="form-control" type="password" name="confirmpassword" autocomplete="off" placeholder="Confirm Password..." required value="<?php echo $confirmpassword; ?>" />
                </div>
                <div class="capt">
                  <h2 type="text" id="mainCaptcha"></h2>
                  <p><button id="refresh" onclick="Captcha()" style="border-radius: 8px;"><i class="fa-solid fa-arrows-rotate fa-spin" style="margin-right: 5px;"></i></button></p>
                </div>

                <div class="form-group">
                  <input type="text" id="txtInput" class="form-control" autocomplete="off" style="margin-top: 25px;">
                  <span class="error"><?php echo $validCaptchaErr; ?></span>
                </div>

                <input type="hidden" name="signup" value="signup">
                <input type="submit" name="signup" class="btn btn-primary" onclick="return ValidCaptcha();" value="Register">
                Already have an account? <a href="index.php#ulogin" style="color: #9B00EA; text-decoration: none; font-weight: bold;" onmouseover="this.style.color='#808080'; this.style.fontWeight='normal';" onmouseout="this.style.color='#9B00EA'; this.style.fontWeight='bold';">Login Here</a>

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php'); ?>
  <script src="assets/js/jquery-1.10.2.js">

  </script>
  <!-- CUSTOM SCRIPTS  -->
  <script src="assets/js/custom.js"></script>
</body>

</html>
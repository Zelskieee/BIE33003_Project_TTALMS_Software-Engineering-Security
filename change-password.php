<?php
session_start();
include('includes/config.php');
error_reporting(0);
if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else {
  if (isset($_POST['change'])) {
    $newpassword = $_POST['newpassword'];
    $uppercase = preg_match('@[A-Z]@', $newpassword);
    $lowercase = preg_match('@[a-z]@', $newpassword);
    $number    = preg_match('@[0-9]@', $newpassword);
    $specialChars = preg_match('@[_^\w]@', $newpassword);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newpassword) < 9) {
      echo "<script>
				alert('Password should be at least 10 characters in length and should include at least one upper case letter, one number, and one special character.');
				window.location.href='change-password.php';
				</script>";
      exit();
    }

    //$password=password_hash($_POST['password'], PASSWORD_DEFAULT);
    $cipher = "aes-256-cbc";
    //Generate a 256-bit encryption key 
    $encryption_key = hash('sha256', $cipher);
    $newpassword = openssl_encrypt($_POST['newpassword'], $cipher, $encryption_key, 0, 256);
    $password = openssl_encrypt($_POST['password'], $cipher, $encryption_key, 0, 256);
    $email = $_SESSION['emailid'];
    // $sql ="SELECT Password FROM tblstudents WHERE EmailId=:email and Password=:password";
    $sql = "SELECT Password FROM students WHERE matricNo=:stuID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':stuID', $_SESSION['login'], PDO::PARAM_STR);
    //$query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($password == $results[0]->Password) {
      $con = "update students set Password=:newpassword where matricNo=:stuID";
      $chngpwd1 = $dbh->prepare($con);
      $chngpwd1->bindParam(':stuID', $_SESSION['login'], PDO::PARAM_STR);
      $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
      $chngpwd1->execute();
      $msg = "Your Password succesfully changed";
    } else {
      $error = "Your current password is wrong";
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
    <title>TTALMS - Change Password</title>
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
      .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
      }

      .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
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
  <script type="text/javascript">
    function valid() {
      if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
        alert("New Password and Confirm Password Field do not match  !!");
        document.chngpwd.confirmpassword.focus();
        return false;
      }
      return true;
    }
  </script>

  <body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
      <div class="container">
        <?php include('includes/check_verification.php'); ?>
        <div class="row pad-botm">
          <div class="col-md-12">
            <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-key fa-beat"></i> CHANGE PASSWORD</h4>
          </div>
        </div>
        <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
        <!--LOGIN PANEL START-->
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
              <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                <i class="fa fa-lock" style="margin-right: 5px;"></i> CHANGE PASSWORD
              </div>
              <div class="panel-body">
                <form role="form" method="post" onSubmit="return valid();" name="chngpwd">

                  <div class="form-group">
                    <i class="fa fa-lock" style="margin-right: 5px;"></i><label>Current Password</label>
                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                  </div>

                  <div class="form-group">
                    <i class="fa fa-lock" style="margin-right: 5px;"></i><label>Enter Password</label>
                    <input class="form-control" type="password" name="newpassword" autocomplete="off" required />
                  </div>

                  <div class="form-group">
                    <i class="fa fa-lock" style="margin-right: 5px;"></i><label>Confirm Password </label>
                    <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
                  </div>

                  <input type="submit" name="login" class="btn btn-primary" value="Change Password">
                </form>
              </div>
            </div>
          </div>
        </div>
        <!---LOGIN PABNEL END-->


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
  </body>

  </html>
<?php } ?>
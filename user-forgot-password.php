<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(isset($_POST['change']))
{
$email=$_POST['email'];
$mobile=$_POST['mobile'];
	$newpassword=$_POST['newpassword'];
	$uppercase = preg_match('@[A-Z]@', $newpassword);
		$lowercase = preg_match('@[a-z]@', $newpassword);
		$number    = preg_match('@[0-9]@', $newpassword);
		$specialChars = preg_match('@[_^\w]@', $newpassword);
	
	if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($newpassword) < 9) {
 			echo "<script>
				alert('Password should be at least 10 characters in length and should include at least one upper case letter, one number, and one special character.');
				</script>";

	}else{
		 $cipher = "aes-256-cbc"; 
	
	  //Generate a 256-bit encryption key 
	  $encryption_key = hash('sha256', $cipher);
	  $newpassword = openssl_encrypt($_POST['newpassword'], $cipher, $encryption_key, 0, 256);

	  	$sql ="SELECT EmailId FROM students WHERE EmailId=:email and MobileNumber=:mobile";
		$query= $dbh -> prepare($sql);
		$query-> bindParam(':email', $email, PDO::PARAM_STR);
		$query-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
		$query-> execute();
		$results = $query -> fetchAll(PDO::FETCH_OBJ);
		if($query -> rowCount() > 0)
		{
			$con="update students set Password=:newpassword where EmailId=:email and MobileNumber=:mobile";
			$chngpwd1 = $dbh->prepare($con);
			$chngpwd1-> bindParam(':email', $email, PDO::PARAM_STR);
			$chngpwd1-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
			$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
			$chngpwd1->execute();
			echo "<script>alert('Your Password succesfully changed');</script>";
		}
		else {
			echo "<script>alert('Email id or Mobile no is invalid');</script>"; 
		}
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
    <title>TTALMS - Forgot Password</title>
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
function valid()
{
if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
{
alert("New Password and Confirm Password Field do not match  !!");
document.chngpwd.confirmpassword.focus();
return false;
}
return true;
}
</script>

<style>
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

<body>
    <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="content-wrapper">
<div class="container">
<div class="row pad-botm">
<div class="col-md-12">
<h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-key fa-beat"></i> STUDENT PASSWORD RECOVERY</h4>
</div>
</div>
             
<!--LOGIN PANEL START-->           
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
<div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
<div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
PASSWORD RECOVERY FORM </div>
<div class="panel-body">
<form role="form" name="chngpwd" method="post" onSubmit="return valid();">

<div class="form-group">
<i class="fa fa-envelope" style="margin-right: 5px;"></i> <label>Registered Email</label>
<input class="form-control" type="email" name="email" required autocomplete="off" required value="<?php echo $email;?>"/>
</div>

<div class="form-group">
<i class="fa fa-phone" style="margin-right: 5px;"></i> <label>Registered Phone</label>
<input class="form-control" type="text" name="mobile" required autocomplete="off"required value="<?php echo $mobile;?>" />
</div>

<div class="form-group">
<i class="fa fa-lock" style="margin-right: 5px;"></i> <label>Password</label>
<input class="form-control" type="password" name="newpassword" required autocomplete="off"  />
</div>

<div class="form-group">
<i class="fa fa-lock" style="margin-right: 5px;"></i> <label>Confirm Password</label>
<input class="form-control" type="password" name="confirmpassword" required autocomplete="off"  />
</div>

<input type="submit" name="change" class="btn btn-primary" value="Change Password">    Click here to <a href="index.php#ulogin" style="color: #9B00EA; font-weight: bold; text-decoration: none;"
   onmouseover="this.style.color='grey'; this.style.fontWeight='normal';"
   onmouseout="this.style.color='#9B00EA'; this.style.fontWeight='bold';">
    Login
</a>

</form>
 </div>
</div>
</div>
</div>  
<!---LOGIN PABNEL END-->            
             
 
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
 <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>

</body>
</html>

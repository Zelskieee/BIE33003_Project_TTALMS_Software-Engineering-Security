<?php
require_once("config.php");
$sid = $_SESSION['login'];
$sql = "SELECT is_verified, verified_at, otp from  students  where matricNo=:sid ";
$query = $dbh->prepare($sql);
$query->bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;
if ($query->rowCount() > 0) {
    foreach ($results as $result) {
        if ($result->is_verified == 0) {
            echo "<div class='alert alert-danger' role='alert'>Your account is not verified. Please verify your account by entering the OTP sent to your email. Click <a href='resend-otp.php'>here</a> to resend OTP.</div>";
        } else {
            // echo "<div class='alert alert-success' role='alert'>Your account is verified.</div>";
        }
    }
}

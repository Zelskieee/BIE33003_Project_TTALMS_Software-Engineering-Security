<?php
//create the resend otp page
error_reporting(0);
session_start();
require 'includes/config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (isset($_POST['submit'])) {
    verifyOTP($dbh);
}

function verifyOTP($dbh)
{
    $sid = $_SESSION['alogin'];
    $otp = $_POST['otp'];
    $sql = "SELECT * from admin where UserName=:sid and otp=:otp";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->bindParam(':otp', $otp, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $date = date('Y-m-d H:i:s');
        $otp = null;
        $verified = 1;
        $sql = "UPDATE admin SET otp=:otp, verify_at=:date WHERE UserName=:sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':otp', $otp, PDO::PARAM_NULL);
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->execute();
        
        notifyLoginViaEmail($dbh); // Notify user via email after successful OTP verification

        echo "<script>alert('OTP verified successfully.');</script>";
        header('location:admin/dashboard.php');
    } else {
        echo "<script>alert('Invalid OTP.');</script>";
    }
}

function notifyLoginViaEmail($dbh)
{
    $sid = $_SESSION['alogin'];
    $sql = "SELECT * from admin where UserName=:sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $email = $result->EmailId;
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->Port = SMTP_PORT;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->SMTPAuth = true;
            $mail->SMTPDebug = false;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->setFrom(FROM_EMAIL, 'TTALMS');
            $mail->addAddress($email);
            $mail->Subject = 'Login Notification';
            $mail->isHTML(true);
            $mailContent = "<h1>TTALMS</h1><p>Your account has been logged in successfully.</p> <br> <p> Thank you for using our service.<br> Best regards, <span style='font-weight: bold;'>TTALMS</span></p>";
            $mail->Body = $mailContent;
            try {
                if (!$mail->send()) {
                    echo "<script>alert('Email could not be sent.');</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Email could not be sent.');</script>";
            }
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
    <title>TTALMS - Resend OTP</title>
    <link rel="icon" type="image/x-icon" href="assets/img/icon_ttalms.ico">
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
        .otp-input {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }

        .otp-input input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .otp-input input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
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

        input[type="submit"]:hover {
            letter-spacing: 3px;
            background-color: #9B00EA;
            color: hsl(0, 0%, 100%);
            box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
        }

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
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <?php include('includes/check_verification.php'); ?>
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-hashtag fa-beat"></i> 2-Step Verification</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                            Librarian Verify OTP
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label><i class="fa-solid fa-mobile"></i> OTP has been sent to your email. Please check your email.</label>
                                    <div class="otp-input">
                                        <input type="text" autocomplete="one-time-code" inputmode="numeric" name="otp1" maxlength="1" required>
                                        <input type="text" autocomplete="one-time-code" inputmode="numeric" name="otp2" maxlength="1" required>
                                        <input type="text" autocomplete="one-time-code" inputmode="numeric" name="otp3" maxlength="1" required>
                                        <input type="text" autocomplete="one-time-code" inputmode="numeric" name="otp4" maxlength="1" required>
                                        <input type="text" autocomplete="one-time-code" inputmode="numeric" name="otp5" maxlength="1" required>
                                        <input type="text" autocomplete="one-time-code" inputmode="numeric" name="otp6" maxlength="1" required>
                                    </div>
                                    <input type="hidden" name="otp" id="otp">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Verify OTP">
                                </div>
                                <i class="fa-solid fa-rotate-right fa-spin"></i><a href="resend-otp.php" class="btn btn-secondary" style="color: black; font-weight: bold; text-decoration: none;" onmouseover="this.style.color='grey'; this.style.fontWeight='normal';" onmouseout="this.style.color='black'; this.style.fontWeight='bold';">Resend OTP</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll(".otp-input input");
            const otpHiddenInput = document.getElementById("otp");
            const otpForm = document.querySelector("form");

            inputs.forEach((input, index) => {
                input.addEventListener("input", (e) => {
                    if (/[^0-9]/.test(e.target.value)) {
                        e.target.value = "";
                    } else if (input.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener("keydown", (e) => {
                    if (e.key === "Backspace" && input.value === "" && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });

            otpForm.addEventListener("submit", (e) => {
                let otp = "";
                inputs.forEach(input => {
                    otp += input.value;
                });
                otpHiddenInput.value = otp;
            });
        });
    </script>
</body>
</html>

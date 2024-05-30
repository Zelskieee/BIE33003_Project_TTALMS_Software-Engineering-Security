<?php
//create the resend otp page
error_reporting(1);
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once("includes/config.php");
//get the user details and send the otp if the user is login
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    $save = saveOTP($dbh);
    if ($save) {
        sendOTP($dbh);
    }
}

function createOtp()
{
    $otp = rand(100000, 999999);
    return $otp;
}

function saveOTP($dbh)
{
    $sid = $_SESSION['login'];
    $otp = createOtp();
    $sql = "UPDATE students SET otp=:otp WHERE matricNo=:sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':otp', $otp, PDO::PARAM_STR);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->execute();
    return $query;
}


function sendOTP($dbh)
{
    $sid = $_SESSION['login'];
    $sql = "SELECT EmailId, otp from  students  where matricNo=:sid ";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $email = $result->EmailId;
            $otp = $result->otp;
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
            $mail->addReplyTo(FROM_EMAIL, 'TTALMS');
            $mail->isHTML(true);
            $mail->Subject = 'OTP for account verification';
            $mail->Body = "Your OTP for account verification is: $otp";
            try {
                if (!$mail->send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    header('location:verify-otp.php');
                }
            } catch (Exception $e) {

                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}

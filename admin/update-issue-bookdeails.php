<?php
session_start();
error_reporting(E_ALL); // Enable detailed error reporting
include('includes/config.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (strlen($_SESSION['alogin']) == 0) {
  header('location:index.php');
} else {

  if (isset($_POST['return'])) {
    $rid = intval($_GET['rid']);
    $fine = $_POST['fine'];
    $bookid = $_POST['bookid'];
    $studentid = $_POST['studentid']; // Assuming studentid is passed from form

    error_log("Received POST request for book return with RID: $rid, Fine: $fine, BookID: $bookid, StudentID: $studentid");

    if (is_numeric($fine)) {
      $rstatus = 1;

      // Debugging: Before SQL execution
      error_log("Executing SQL updates for return process...");

      // Update issuedbookdetails table
      $sql1 = "UPDATE issuedbookdetails SET fine=:fine, RetrunStatus=:rstatus WHERE id=:rid";
      $query1 = $dbh->prepare($sql1);
      $query1->bindParam(':rid', $rid, PDO::PARAM_INT);
      $query1->bindParam(':fine', $fine, PDO::PARAM_INT);
      $query1->bindParam(':rstatus', $rstatus, PDO::PARAM_INT);

      if ($query1->execute()) {
        // Debugging: After first SQL execution
        error_log("issuedbookdetails table updated successfully.");
      } else {
        // Debugging: If first SQL execution fails
        error_log("Error updating issuedbookdetails table.");
      }

      // Update books table
      $sql2 = "UPDATE books SET isIssued=0 WHERE id=:bookid";
      $query2 = $dbh->prepare($sql2);
      $query2->bindParam(':bookid', $bookid, PDO::PARAM_INT);

      if ($query2->execute()) {
        // Debugging: After second SQL execution
        error_log("books table updated successfully.");
      } else {
        // Debugging: If second SQL execution fails
        error_log("Error updating books table.");
      }

      // Debugging: Before setting session message and email notification
      error_log("Setting session message and sending email notification...");

      $_SESSION['msg'] = "Book Returned successfully";
      notifyLoginViaEmail($dbh, $studentid, $fine);

      // Debugging: After email notification function call
      error_log("Email notification function called.");

      header('location:manage-issued-books.php');
      exit();
    } else {
      echo "<script>alert('Fines can only be numerical.');</script>";
    }
  }
}

function notifyLoginViaEmail($dbh, $studentid, $fine)
{
    $sql = "SELECT EmailId FROM students WHERE matricNo = :sid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $studentid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Close cursor to free the connection
    $query->closeCursor();

    if ($result) {
        $email = $result->EmailId;
        error_log("Student email found: $email");
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->Port = SMTP_PORT;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->setFrom(FROM_EMAIL, 'TTALMS');
            $mail->addAddress($email);
            $mail->Subject = 'Return Book Notification';
            $mail->isHTML(true);
            $mailContent = "<h1>TTALMS</h1><p>The book borrowed was returned successfully. The fine is <strong>RM $fine.00</strong> </p><br><p>Thank you for using our service.<br>Best regards,<br><strong>TTALMS</strong></p>";
            $mail->Body = $mailContent;
            $mail->send();
            error_log("Email sent successfully to $email");
        } catch (Exception $e) {
            // Log the error message
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
        }
    } else {
        error_log("Student email not found for student ID: {$studentid}");
        echo "<script>alert('Student email not found.');</script>";
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
    <title>TTALMS - Edit Borrowed Book</title>
    <link rel="icon" type="image/x-icon" href="..\assets\img\icon_ttalms.ico">
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

    <style type="text/css">
      .others {
        color: red;
      }
    </style>

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
        <div class="row pad-botm">
          <div class="col-md-12">
            <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-book-bookmark fa-beat"></i> BORROWED BOOK DETAILS</h4>

          </div>

        </div>
        <div class="row">
          <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
            <div class="panel panel-info" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
              <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                <i class="fa-solid fa-book-bookmark" style="margin-right: 5px;"></i> BORROWED BOOK DETAILS
              </div>
              <div class="panel-body">
                <form role="form" method="post">
                  <?php
                  $rid = intval($_GET['rid']);
                  $sql = "SELECT students.matricNo ,students.FullName,students.EmailId,students.MobileNumber,books.BookName,books.ISBNNumber,issuedbookdetails.IssuesDate,issuedbookdetails.ReturnDate,issuedbookdetails.id as rid,issuedbookdetails.fine,issuedbookdetails.RetrunStatus,books.id as bid,books.bookImage from  issuedbookdetails join students on students.matricNo=issuedbookdetails.StudentId join books on books.id=issuedbookdetails.BookId where issuedbookdetails.id=:rid";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':rid', $rid, PDO::PARAM_STR);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  $cnt = 1;
                  if ($query->rowCount() > 0) {
                    foreach ($results as $result) { 
                  ?>
                      <input type="hidden" name="bookid" value="<?php echo htmlentities($result->bid); ?>">
                      <input type="hidden" name="studentid" value="<?php echo htmlentities($result->matricNo); ?>"> <!-- Added student ID here -->
                      <h4 style="text-align: center; font-weight: bold;"><i class="fa-solid fa-graduation-cap"></i> Student Details</h4>
                      <hr />
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Student ID :</label>
                          <?php echo htmlentities($result->matricNo); ?>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Student Name :</label>
                          <?php echo htmlentities($result->FullName); ?>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Student Email :</label>
                          <?php echo htmlentities($result->EmailId); ?>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Student Phone Number :</label>
                          <?php echo htmlentities($result->MobileNumber); ?>
                        </div>
                      </div>

                      <h4 style="text-align: center; font-weight: bold;"><i class="fa-solid fa-book-open"></i> Book Details</h4>
                      <hr />

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Book Image :</label>
                          <img src="bookimage/<?php echo htmlentities($result->bookImage); ?>" width="120">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Book Name :</label>
                          <?php echo htmlentities($result->BookName); ?>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>ISBN :</label>
                          <?php echo htmlentities($result->ISBNNumber); ?>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Book Borrowed Date :</label>
                          <?php echo htmlentities($result->IssuesDate); ?>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Book Returned Date :</label>
                          <?php if ($result->ReturnDate == "") {
                            echo '<span style="color: red; font-weight: bold;">Not Returned Yet</span>';
                          } else {
                            echo htmlentities($result->ReturnDate);
                          }
                          ?>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Fine (RM) :</label>
                          <?php
                          if ($result->ReturnDate == "") { ?>
                            <input class="form-control" type="text" name="fine" id="fine" required autocomplete="off" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" />
                          <?php } else {
                            if ($result->fine == 0) {
                              echo '<span style="color: green; font-weight: bold;">No fine</span>';
                            } else {
                              echo '<span style="color: red; font-weight: bold;">' . number_format($result->fine, 2) . '</span>';
                            }
                          }
                          ?>
                        </div>
                      </div>
                      <?php if ($result->RetrunStatus == 0) { ?>
                        <input type="submit" name="return" class="btn btn-primary" value="Return Book">
                      <?php } ?>
                  <?php 
                    }
                  } 
                  ?>
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

</body>

</html>

<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
  header('location:index.php');
} else {

  if (isset($_POST['return'])) {
    $rid = intval($_GET['rid']);
    $fine = $_POST['fine'];
    if (is_numeric($fine) == 1) {
      $rstatus = 1;
      $bookid = $_POST['bookid'];
      $sql = "update issuedbookdetails set fine=:fine,RetrunStatus=:rstatus where id=:rid;
	update books set isIssued=0 where id=:bookid";
      $query = $dbh->prepare($sql);
      $query->bindParam(':rid', $rid, PDO::PARAM_STR);
      $query->bindParam(':fine', $fine, PDO::PARAM_STR);
      $query->bindParam(':rstatus', $rstatus, PDO::PARAM_STR);
      $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
      $query->execute();

      $_SESSION['msg'] = "Book Returned successfully";
      header('location:manage-issued-books.php');
    } else {
      echo "<script>alert('Fines can only be numerical.');</script>";
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

    <script>
      // function for get student name
      function getstudent() {
        $("#loaderIcon").show();
        jQuery.ajax({
          url: "get_student.php",
          data: 'studentid=' + $("#studentid").val(),
          type: "POST",
          success: function(data) {
            $("#get_student_name").html(data);
            $("#loaderIcon").hide();
          },
          error: function() {}
        });
      }

      //function for book details
      function getbook() {
        $("#loaderIcon").show();
        jQuery.ajax({
          url: "get_book.php",
          data: 'bookid=' + $("#bookid").val(),
          type: "POST",
          success: function(data) {
            $("#get_book_name").html(data);
            $("#loaderIcon").hide();
          },
          error: function() {}
        });
      }
    </script>
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
                    foreach ($results as $result) {               ?>



                      <input type="hidden" name="bookid" value="<?php echo htmlentities($result->bid); ?>">
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
                            echo htmlentities("Not Return Yet");
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
                          if ($result->fine == "") { ?>
                            <input class="form-control" type="text" name="fine" id="fine" required />

                          <?php } else {
                            echo htmlentities($result->fine);
                          }
                          ?>
                        </div>
                      </div>
                      <?php if ($result->RetrunStatus == 0) { ?>

                        <input type="submit" name="return" class="btn btn-primary" value="Return Book">

              </div>

        <?php }
                    }
                  } ?>
        </form>
            </div>
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
<?php } ?>
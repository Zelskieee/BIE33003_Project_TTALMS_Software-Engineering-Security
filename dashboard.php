<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else { ?>
  <!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TTALMS - Student Dashboard</title>
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
      .alert:hover {
        transform: scale(1.1);
        /* Increase size by 10% */
        transition: transform 0.3s ease;
        /* Add smooth transition effect */
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
            <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-table-columns fa-beat"></i> STUDENT DASHBOARD</h4>
          </div>

        </div>

        <div class="row">


          <a href="listed-books.php">
            <div class="col-md-4 col-sm-4 col-xs-6">
              <div class="alert alert-success back-widget-set text-center" style="border-color: #9B00EA;">
              <i class="fa-solid fa-book fa-bounce fa" style="color: #9B00EA; font-size: 70px; padding-top: 50px;"></i>
                <?php
                $sql = "SELECT id FROM books";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $listdbooks = $query->rowCount();
                ?>
                <h3><?php echo htmlentities($listdbooks); ?></h3>
                List of Books
              </div>
            </div>
          </a>


          <div class="col-md-4 col-sm-4 col-xs-6">
            <div class="alert alert-warning back-widget-set text-center" style="border-color: red;">
              <i class="fa-solid fa-arrow-rotate-right fa-bounce" style="color: red; font-size: 70px; padding-top: 50px;"></i>
              <?php
              $rsts = 0;
              $sid = $_SESSION['stdid'];

              $sql2 = "SELECT id from issuedbookdetails where StudentID=:sid and (RetrunStatus=:rsts || RetrunStatus is null || RetrunStatus='')";
              $query2 = $dbh->prepare($sql2);
              $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
              $query2->bindParam(':rsts', $rsts, PDO::PARAM_STR);
              $query2->execute();
              $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
              $returnedbooks = $query2->rowCount();
              ?>

              <h3><?php echo htmlentities($returnedbooks); ?></h3>
              Book Not Return Yet
            </div>
          </div>

          <a href="issued-books.php">
            <div class="col-md-4 col-sm-4 col-xs-6">
              <div class="alert alert-success back-widget-set text-center" style="border-color: #9B00EA;">
                <i class="fa-solid fa-book-bookmark fa-bounce" style="color: #9B00EA; font-size: 70px; padding-top: 50px;"></i>

                <?php
                session_start();
                $sid = $_SESSION['stdid'];

                try {
                  $sql2 = "SELECT COUNT(BookId) AS borrowed_books_count FROM issuedbookdetails WHERE StudentID = :sid";
                  $query2 = $dbh->prepare($sql2);
                  $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
                  $query2->execute();
                  $borrowed_books_count = $query2->fetchColumn();
                } catch (PDOException $e) {
                  // Handle database errors
                  echo "Error: " . $e->getMessage();
                }
                ?>
                <h3><?php echo htmlentities($borrowed_books_count); ?></h3>

                Borrowed Books
              </div>
            </div>
          </a>

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
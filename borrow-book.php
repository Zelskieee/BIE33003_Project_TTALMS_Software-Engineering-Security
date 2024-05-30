<?php
session_start();
require 'includes/config.php';

if (isset($_GET['bookid']) && isset($_SESSION['login'])) {
    if (isset($_POST['issue'])) {
        $bookid = $_GET['bookid'];
        $studentid = $_SESSION['login'];
        $isissued = 1;
        $sql = "INSERT INTO  issuedbookdetails(StudentID,BookId) VALUES(:studentid,:bookid);
    update books set isIssued=:isissued where id=:bookid;";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->bindParam(':isissued', $isissued, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $_SESSION['msg'] = "Book issued successfully";
            header('location:issued-books.php');
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:manage-issued-books.php');
        }
    }
} else {
    $_SESSION['error'] = "Please login to borrow a book";
    header('location:index.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
                    <h4 class="header-line">Borrow Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            Borrow Book
                        </div>
                        <div class="panel-body bg-warning">
                            <form role="form" method="post">
                                <div class="form-group text-center">
                                    <label>Are you sure you want to borrow this book?</label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="issue" class="btn btn-danger">Yes</button> <a href="listed-books.php" class="btn btn-primary">No</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- book details -->
                <?php
                $bookid = $_GET['bookid'];
                $sql = "SELECT distinct books.BookName,books.id,authors.AuthorName,books.bookImage,books.isIssued, books.ISBNNumber, books.BookPrice, c.CategoryName FROM books
                        join authors on authors.id=books.AuthorId
                        join category c on c.id=books.CatId
                        where books.id=:bookid;";
                $query = $dbh->prepare($sql);
                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                ?>
                        <div class="col-md-8 col-sm-3">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <?php echo htmlentities($result->BookName); ?>
                                </div>
                                <div class="panel-body text-center">
                                    <img src="admin/bookimage/<?php echo htmlentities($result->bookImage); ?>" style="width: 150px; height: 200px;" />
                                    <h4><b>Author:</b> <?php echo htmlentities($result->AuthorName); ?></h4>
                                    <h4><b>Category:</b> <?php echo htmlentities($result->CategoryName); ?></h4>
                                    <h4><b>ISBN Number:</b> <?php echo htmlentities($result->ISBNNumber); ?></h4>
                                    <h4><b>Price:</b> RM<?php echo htmlentities($result->BookPrice); ?></h4>
                                </div>
                        <?php }
                } ?>


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
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
    <title>TTALMS - Student Borrow Book</title>
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

        .btn-primary:hover {
            background-color: white !important;
            color: black !important;
            transition: background-color 0.5s ease, color 0.5s ease;
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
                    <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-book-bookmark fa-beat"></i> Borrow Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-offset-1">
                    <div class="panel panel-danger" style="border-radius: 10px; border-color: #9B00EA;">
                        <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                            Borrow Book
                        </div>
                        <div class="panel-body bg-warning" style="background-color: white; ">
                            <form role="form" method="post">
                                <div class="form-group text-center">
                                    <label>Are you sure you want to borrow this book?</label>
                                </div>
                                <div class="text-center">
                                    <input type="submit" name="issue" value="Yes"> 
                                </div>
                                <div class="text-center">
                                <a href="listed-books.php" class="btn btn-primary" style="background-color: black; border-radius: 15px; margin-top: 20px; border-color: black; color: white;"><i class="fa-solid fa-arrow-left fa-beat-fade"></i> NO</a>
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
                                <div class="panel-heading" style="text-align: center; font-weight: bold;">
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
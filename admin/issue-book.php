<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_POST['issue'])) {
        $studentid = strtoupper($_POST['studentid']);
        $bookid = $_POST['bookid'];

        // Function to validate input and check for disallowed content
        function isValidInput($input) {
            // Check for presence of HTML tags
            if (preg_match('/<[^>]*>/', $input)) {
                return false;
            }
            return true;
        }

        // Validate inputs
        if (!isValidInput($studentid) || !isValidInput($bookid)) {
            $_SESSION['error'] = "Invalid input. HTML tags are not allowed.";
            header('location:manage-issued-books.php');
            exit();
        }

        // Sanitize inputs to prevent XSS
        $studentid = htmlspecialchars($studentid, ENT_QUOTES, 'UTF-8');
        $bookid = htmlspecialchars($bookid, ENT_QUOTES, 'UTF-8');

        if ($bookid == null || $bookid == "") {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:manage-issued-books.php');
        } else {
            $isissued = 1;
            $sql = "INSERT INTO issuedbookdetails(StudentID,BookId) VALUES(:studentid,:bookid);
                    UPDATE books SET isIssued=:isissued WHERE id=:bookid;";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
            $query->bindParam(':isissued', $isissued, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                $_SESSION['msg'] = "Book issued successfully";
                header('location:manage-issued-books.php');
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again";
                header('location:manage-issued-books.php');
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
    <title>TTALMS - Borrow New Book</title>
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
                    <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-book-bookmark fa-beat"></i> BORROW NEW BOOK</h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
                    <div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
                        <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                            <i class="fa-solid fa-book-bookmark" style="margin-right: 5px;"></i> BORROW NEW BOOK
                        </div>
                        <div class="panel-body" style="padding: 20px;">
                            <form role="form" method="post">

                                <div class="form-group">
                                    <i class="fa-solid fa-graduation-cap" style="margin-right: 5px;"></i><label>Student ID<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="studentid" id="studentid" onBlur="getstudent()" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <span id="get_student_name" style="font-size:16px;"></span>
                                </div>

                                <div class="form-group">
                                    <i class="fa-solid fa-barcode" style="margin-right: 5px;"></i><label>Book Code or Book Title<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="bookid" id="bookid" onBlur="getbook()" required="required" />
                                </div>

                                <div class="form-group" id="get_book_name">

                                </div>
                                <input type="submit" name="issue" class="btn btn-primary" value="Borrow">

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
<?php } ?>

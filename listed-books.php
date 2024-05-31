<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {



?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>TTALMS - List of Book</title>
        <link rel="icon" type="image/x-icon" href="assets\img\icon_ttalms.ico">
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- DATATABLE STYLE  -->
        <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>

        <style>
            .book-container {
                border: 1px solid #ccc;
                /* Add border to each book */
                padding: 10px;
                /* Add padding to give some space inside the border */
            }

            .book-container:hover {
                transform: scale(1.1);
                /* Increase size on hover */
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                /* Add shadow on hover */
                background-color: #f0f0f0;
                /* Change background color on hover */
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
                        <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-book fa-beat"></i> LIST OF BOOK</h4>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <!-- Advanced Tables -->
                            <div class="panel panel-default" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
                                <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                                    List of Books
                                </div>
                                <div class="panel-body">


                                    <?php $sql = "SELECT books.BookName,category.CategoryName,authors.AuthorName,books.ISBNNumber,books.BookPrice,books.id as bookid,books.bookImage,books.isIssued from  books join category on category.id=books.CatId join authors on authors.id=books.AuthorId";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>
                                            <div class="col-md-4 book-container" style="float: left; height: 300px; transition: all 0.3s; text-align: center; border-radius: 8px; border-color: #9B00EA;">
                                                <img src="admin/bookimage/<?php echo htmlentities($result->bookImage); ?>" style="max-width: 100px; max-height: 100px; display: inline-block; vertical-align: middle;" />
                                                <br>
                                                <b><?php echo htmlentities($result->BookName); ?></b>
                                                <br>
                                                <?php echo htmlentities($result->CategoryName); ?>
                                                <br>
                                                <?php echo htmlentities($result->AuthorName); ?>
                                                <br>
                                                <?php echo htmlentities($result->ISBNNumber); ?>
                                                <br>
                                                <?php if ($result->isIssued == '1') : ?>
                                                    <p style="color:red; font-weight: bold;">BOOK BORROWED</p>
                                                <?php else : ?>
                                                    <a href="borrow-book.php?bookid=<?php echo htmlentities($result->bookid); ?>" 
                                                    class="btn btn-primary" 
                                                    style="background-color: #9B00EA; color: white; border-color: #9B00EA; transition: background-color 0.5s ease, color 0.5s ease;" 
                                                    onmouseover="this.style.backgroundColor='white'; this.style.color='#9B00EA';" 
                                                    onmouseout="this.style.backgroundColor='#9B00EA'; this.style.color='white';">
                                                    <i class="fa-solid fa-book-bookmark fa-bounce"></i> Borrow Book
                                                    </a>
                                                <?php endif; ?>
                                            </div>



                                    <?php $cnt = $cnt + 1;
                                        }
                                    } ?>


                                </div>
                            </div>
                            <!--End Advanced Tables -->
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
        <!-- DATATABLE SCRIPTS  -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>

    </body>

    </html>
<?php } ?>
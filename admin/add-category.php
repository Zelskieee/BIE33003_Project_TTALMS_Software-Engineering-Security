<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin']) == 0)
{   
    header('location:index.php');
}
else
{ 
    if(isset($_POST['create']))
    {
        $category = $_POST['category'];
        $status = $_POST['status'];

        // Function to validate input and check for disallowed content for preventing Cross-Site Scripting (XSS)
        function isValidInput($input) {
            // Check for presence of HTML tags
            if (preg_match('/<[^>]*>/', $input)) {
                return false;
            }
            return true;
        }

        // Validate category input
        if(!isValidInput($category)) {
            $_SESSION['error'] = "Invalid input. HTML tags are not allowed.";
            header('location:manage-categories.php');
            exit();
        }

        // Sanitize inputs to prevent XSS
        $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');

        $sql = "INSERT INTO category(CategoryName,Status) VALUES(:category,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId)
        {
            $_SESSION['msg'] = "Category Listed successfully";
            header('location:manage-categories.php');
        }
        else 
        {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:manage-categories.php');
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
    <title>TTALMS - Add Category</title>
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
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line" style="text-align: center; position: relative;">
                        <i class="fa-solid fa-table-list fa-beat"></i> ADD CATEGORY
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
                        <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                            <i class="fas fa-sign-in-alt" style="margin-right: 5px;"></i> CATEGORY INFO 
                        </div>
                        <div class="panel-body" style="padding: 20px;">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <i class="fa-solid fa-list-ol" style="margin-right: 5px;"></i><label>Category Name</label>
                                    <input class="form-control" type="text" name="category" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" required />
                                </div>
                                <div class="form-group">
                                    <i class="fa-solid fa-question" style="margin-right: 5px;"></i><label>Status</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status" id="status" value="1" checked="checked">Active
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status" id="status" value="0">Inactive
                                        </label>
                                    </div>
                                </div>
                                <input type="submit" name="create" class="btn btn-primary" value="Add">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
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

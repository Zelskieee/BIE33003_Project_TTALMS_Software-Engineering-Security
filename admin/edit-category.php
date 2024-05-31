<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
} else { 

    if(isset($_POST['update'])) {
        $category = $_POST['category'];
        $status = $_POST['status'];
        $catid = intval($_GET['catid']);

        // Function to validate input and check for disallowed content
        function isValidInput($input) {
            // Check for presence of HTML tags
            if (preg_match('/<[^>]*>/', $input)) {
                return false;
            }
            return true;
        }

        // Validate and sanitize input
        if (!isValidInput($category)) {
            $_SESSION['error'] = "Invalid input. HTML tags are not allowed.";
            header('location:manage-categories.php');
            exit();
        }

        // Sanitize input to prevent XSS
        $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
        $status = htmlspecialchars($status, ENT_QUOTES, 'UTF-8');

        $sql = "UPDATE category SET CategoryName=:category, Status=:status WHERE id=:catid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':catid', $catid, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg'] = "Category updated successfully";
        header('location:manage-categories.php');
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TTALMS - Edit Category</title>
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
                    <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-table-list fa-beat"></i> EDIT CATEGORY</h4>
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
                                <?php 
                                $catid = intval($_GET['catid']);
                                $sql = "SELECT * FROM category WHERE id=:catid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':catid', $catid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) {
                                ?> 
                                <div class="form-group">
                                    <i class="fa-solid fa-list-ol" style="margin-right: 5px;"></i><label>Category Name</label>
                                    <input class="form-control" type="text" name="category" value="<?php echo htmlentities($result->CategoryName); ?>" onkeyup="this.value = this.value.toUpperCase();" required />
                                </div>
                                <div class="form-group">
                                    <i class="fa-solid fa-question" style="margin-right: 5px;"></i><label>Status</label>
                                    <?php if ($result->Status == 1) { ?>
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
                                    <?php } else { ?>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status" id="status" value="0" checked="checked">Inactive
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="status" id="status" value="1">Active
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php }} ?>
                                <input type="submit" name="update" class="btn btn-primary" value="Update">
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

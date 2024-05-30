<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
} else {
    if (isset($_POST['update'])) {
        $sid = $_SESSION['login'];
        $fname = $_POST['fullanme'];
        $mobileno = $_POST['mobileno'];
        $image_name = '';

        // Check if an image is selected for upload
        if (!empty($_FILES["image"]["tmp_name"])) {
            $image_file = $_FILES["image"];
            $image_extension = pathinfo($image_file["name"], PATHINFO_EXTENSION);
            $image_name = bin2hex(random_bytes(16)) . '.' . $image_extension;
            $target_path = "assets/img/student_profile_img/" . $image_name;

            // Move the temp image file to the destination directory
            if (!move_uploaded_file($image_file["tmp_name"], $target_path)) {
                die('Failed to move uploaded file to destination.');
            }
        }

        try {
            // Update the database
            $sql = "UPDATE students SET FullName=:fname, MobileNumber=:mobileno";
            if (!empty($image_name)) {
                $sql .= ", profile_picture=:image";
            }
            $sql .= " WHERE matricNo=:sid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':sid', $sid, PDO::PARAM_STR);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
            if (!empty($image_name)) {
                $query->bindParam(':image', $image_name, PDO::PARAM_STR);
            }
            $query->execute();

            echo '<script>alert("Your profile has been updated")</script>';
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <!-- Head content -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>TTALMS - Student Profile</title>
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
                    <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-regular fa-address-card fa-beat"></i> MY PROFILE</h4>
                </div>
            </div>
            <div class="container">
                <?php include('includes/check_verification.php'); ?>
                <div class="row">
                    <div class="col-md-9 col-md-offset-1">
                        <div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
                            <div class="panel-heading" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                                <i class="fa fa-user" style="margin-right: 5px;"></i> MY PROFILE
                            </div>
                            <div class="panel-body">
                                <form name="signup" method="post" enctype="multipart/form-data">
                                    <?php
                                    $sid = $_SESSION['login'];
                                    $sql = "SELECT matricNo,FullName,EmailId,MobileNumber,RegDate,UpdationDate,Status from  students  where matricNo=:sid ";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>

                                            <div class="form-group">
                                                <i class="fa fa-user" style="margin-right: 5px;"></i><label> Student ID : </label>
                                                <?php echo htmlentities($result->matricNo); ?>
                                            </div>

                                            <div class="form-group">
                                                <i class="fa-solid fa-calendar-days" style="margin-right: 5px;"></i><label> Registration Date : </label>
                                                <?php 
                                                    $originalRegDate = $result->RegDate;
                                                    $regDateTime = new DateTime($originalRegDate);
                                                    echo $regDateTime->format('d/m/Y h:i A');
                                                ?>
                                            </div>

                                            <?php if ($result->UpdationDate != "") { ?>
                                                <div class="form-group">
                                                    <i class="fa-solid fa-calendar-days" style="margin-right: 5px;"></i><label> Last Updation Date : </label>
                                                    <?php 
                                                        $originalDate = $result->UpdationDate;
                                                        $dateTime = new DateTime($originalDate);
                                                        echo $dateTime->format('d/m/Y h:i A');
                                                    ?>
                                                </div>
                                            <?php } ?>


                                            <div class="form-group">
                                                <i class="fa-brands fa-creative-commons-by" style="margin-right: 5px;"></i><label> Profile Status : </label>
                                                <?php if ($result->Status == 1) { ?>
                                                    <span style="color: green; font-weight: bold;">Active</span>
                                                <?php } else { ?>
                                                    <span style="color: red; font-weight: bold;">Blocked</span>
                                                <?php } ?>
                                            </div>

                                            <div class="form-group">
                                                <i class="fa-solid fa-signature" style="margin-right: 5px;"></i><label> Full Name</label>
                                                <input class="form-control" type="text" name="fullanme" value="<?php echo htmlentities($result->FullName); ?>" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" required />
                                            </div>


                                            <div class="form-group">
                                                <i class="fa fa-phone" style="margin-right: 5px;"></i><label> Mobile Number</label>
                                                <input class="form-control" type="text" name="mobileno" maxlength="10" value="<?php echo htmlentities($result->MobileNumber); ?>" autocomplete="off" required />
                                            </div>

                                            <div class="form-group">
                                                <i class="fa-regular fa-envelope" style="margin-right: 5px;"></i><label> Email</label>
                                                <input class="form-control" type="email" name="email" id="emailid" value="<?php echo htmlentities($result->EmailId); ?>" autocomplete="off" required readonly />
                                            </div>
                                    <?php }
                                    } ?>

                                    <input type="submit" name="update" class="btn btn-primary" value="Update">
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
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
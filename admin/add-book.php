<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
  header('location:index.php');
} else {

  if (isset($_POST['add'])) {
    $bookname = $_POST['bookname'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $price = $_POST['price'];
    $bookimage = $_FILES["bookpic"]["name"];
    // get the image extension
    $extension = substr($bookimage, strlen($bookimage) - 4, strlen($bookimage));
    // allowed extensions
    $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
    // Validation for allowed extensions .in_array() function searches an array for a specific value.
    //rename the image file
    $imgnewname = md5($bookimage . time()) . $extension;
    // Code for move image into directory

    if (!in_array($extension, $allowed_extensions)) {
      echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
    } else {
      move_uploaded_file($_FILES["bookpic"]["tmp_name"], "bookimage/" . $imgnewname);
      $sql = "INSERT INTO  books(BookName,CatId,AuthorId,ISBNNumber,BookPrice,bookImage) VALUES(:bookname,:category,:author,:isbn,:price,:imgnewname)";
      $query = $dbh->prepare($sql);
      $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
      $query->bindParam(':category', $category, PDO::PARAM_STR);
      $query->bindParam(':author', $author, PDO::PARAM_STR);
      $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
      $query->bindParam(':price', $price, PDO::PARAM_STR);
      $query->bindParam(':imgnewname', $imgnewname, PDO::PARAM_STR);
      $query->execute();
      $lastInsertId = $dbh->lastInsertId();
      if ($lastInsertId) {
        echo "<script>alert('Book Listed successfully');</script>";
        echo "<script>window.location.href='manage-books.php'</script>";
      } else {
        echo "<script>alert('Something went wrong. Please try again');</script>";
        echo "<script>window.location.href='manage-books.php'</script>";
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
    <title>TTALMS - Add Book</title>
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

    <script type="text/javascript">
      function checkisbnAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
          url: "check_availability.php",
          data: 'isbn=' + $("#isbn").val(),
          type: "POST",
          success: function(data) {
            $("#isbn-availability-status").html(data);
            $("#loaderIcon").hide();
          },
          error: function() {}
        });
      }
    </script>

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
            <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-book fa-beat"></i> ADD BOOK</h4>

          </div>

        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-primary" style="margin-top: 50px; border-radius: 10px; border-color: #9B00EA;">
              <div class="panel-heading custom_class" style="background-color: #9B00EA; color: #fff; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center; font-weight: bold; border-color: #9B00EA;">
                BOOK INFO
              </div>
              <div class="panel-body">
                <form role="form" method="post" enctype="multipart/form-data">

                  <div class="col-md-6">
                    <div class="form-group">
                      <i class="fa-solid fa-book" style="margin-right: 5px;"></i><label>Book Name<span style="color:red;">*</span></label>
                      <input class="form-control" type="text" name="bookname" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" required />
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <i class="fa-solid fa-table-list" style="margin-right: 5px;"></i><label> Category<span style="color:red;">*</span></label>
                      <select class="form-control" name="category" required="required">
                        <option value=""> - - Select Category - - </option>
                        <?php
                        $status = 1;
                        $sql = "SELECT * from  category where Status=:status";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':status', $status, PDO::PARAM_STR);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                          foreach ($results as $result) {               ?>
                            <option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->CategoryName); ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label> Author<span style="color:red;">*</span></label>
                      <select class="form-control" name="author" required="required">
                        <option value=""> - - Select Author - - </option>
                        <?php

                        $sql = "SELECT * from  authors ";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                          foreach ($results as $result) {               ?>
                            <option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->AuthorName); ?></option>
                        <?php }
                        } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                    <i class="fa-solid fa-barcode" style="margin-right: 5px;"></i><label>Book Code<span style="color:red;">*</span></label>
                      <input class="form-control" type="text" name="isbn" id="isbn" required="required" autocomplete="off" onBlur="checkisbnAvailability()" />
                      <span id="isbn-availability-status" style="font-size:12px;"></span>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <i class="fa-solid fa-money-bill-1-wave" style="margin-right: 5px;"></i><label>Price in RM<span style="color:red;">*</span></label>
                      <input class="form-control" type="text" name="price" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" required="required" />
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <i class="fa-regular fa-image" style="margin-right: 5px;"></i><label>Book Picture<span style="color:red;">*</span></label>
                      <input class="form-control" type="file" name="bookpic" autocomplete="off" required="required" />
                    </div>
                  </div>
                  <input type="submit" name="add" class="btn btn-primary" value="Add">
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
    <!-- <script src="assets/js/custom.js"></script> -->
  </body>

  </html>
<?php } ?>
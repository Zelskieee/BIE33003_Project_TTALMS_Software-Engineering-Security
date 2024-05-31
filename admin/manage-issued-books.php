<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 



    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TTALMS - Manage Borrowed Book</title>
    <link rel="icon" type="image/x-icon" href="..\assets\img\icon_ttalms.ico">
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
    <!-- ICON -->
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script> 

</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
            <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-book-bookmark fa-beat"></i> MANAGE BORROWED BOOK</h4>
    </div>
     <div class="row">
    <?php if($_SESSION['error']!="")
    {?>
<div class="col-md-6">
<div class="alert alert-danger" >
 <strong>Error :</strong> 
 <?php echo htmlentities($_SESSION['error']);?>
<?php echo htmlentities($_SESSION['error']="");?>
</div>
</div>
<?php } ?>
<?php if($_SESSION['msg']!="")
{?>
<div class="col-md-6">
<div class="alert alert-success" >
 <strong>Success :</strong> 
 <?php echo htmlentities($_SESSION['msg']);?>
<?php echo htmlentities($_SESSION['msg']="");?>
</div>
</div>
<?php } ?>



   <?php if($_SESSION['delmsg']!="")
    {?>
<div class="col-md-6">
<div class="alert alert-success" >
 <strong>Success :</strong> 
 <?php echo htmlentities($_SESSION['delmsg']);?>
<?php echo htmlentities($_SESSION['delmsg']="");?>
</div>
</div>
<?php } ?>

</div>


        </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary" style="border-color: #9B00EA;">
                        <div class="panel-heading" style="text-align: center; background-color: #9B00EA;">
                        <i class="fa-solid fa-book-bookmark"></i> List of Borrowed Books 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">#</th>
                                            <th style="text-align: center;">Student Name</th>
                                            <th style="text-align: center;">Book Name</th>
                                            <th style="text-align: center;">Book Code</th>
                                            <th style="text-align: center;">Borrow Date</th>
                                            <th style="text-align: center;">Return Date</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php $sql = "SELECT students.FullName,books.BookName,books.ISBNNumber,issuedbookdetails.IssuesDate,issuedbookdetails.ReturnDate,issuedbookdetails.id as rid from  issuedbookdetails join students on students.matricNo=issuedbookdetails.StudentId join books on books.id=issuedbookdetails.BookId order by issuedbookdetails.id desc";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>                                      
                                        <tr class="odd gradeX">
                                            <td class="center" style="text-align: center;"><?php echo htmlentities($cnt);?></td>
                                            <td class="center"><?php echo htmlentities($result->FullName);?></td>
                                            <td class="center"><?php echo htmlentities($result->BookName);?></td>
                                            <td class="center" style="text-align: center;"><?php echo htmlentities($result->ISBNNumber);?></td>
                                            <td class="center" style="text-align: center;">
                                                <?php 
                                                if (!empty($result->IssuesDate)) {
                                                    echo date('d/m/Y h:i A', strtotime($result->IssuesDate));
                                                } else {
                                                    echo "&nbsp;"; // Display a non-breaking space if the date is empty
                                                }
                                                ?>
                                            </td>
                                            <td class="center" style="text-align: center;">
                                                <?php 
                                                if (!empty($result->ReturnDate)) {
                                                    echo date('d/m/Y h:i A', strtotime($result->ReturnDate));
                                                } else {
                                                    echo '<span style="color: red; font-weight: bold;">Not Return Yet</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="center" style="text-align: center;">
                                                <?php if (!empty($result->ReturnDate)) { ?>
                                                    <a href="update-issue-bookdeails.php?rid=<?php echo htmlentities($result->rid);?>"><button class="btn btn-primary" style="background-color: white; border-color: black; color: black;"><i class="fa-solid fa-eye fa-bounce"></i> View</button></a>
                                                <?php } else { ?>
                                                    <a href="update-issue-bookdeails.php?rid=<?php echo htmlentities($result->rid);?>"><button class="btn btn-primary" style="background-color: black;"><i class="fa-solid fa-arrow-rotate-left fa-bounce"></i> Return</button></a>
                                                <?php } ?>
                                            </td>

                                        </tr>
                                        <?php $cnt=$cnt+1;}} ?>                                      
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                    <!--End Advanced Tables -->
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
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>

<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

// code for block student    
if(isset($_GET['inid']))
{
$id=$_GET['inid'];
$status=0;
$sql = "update students set Status=:status  WHERE matric=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query -> execute();
header('location:reg-students.php');
}



//code for active students
if(isset($_GET['id']))
{
$id=$_GET['id'];
$status=1;
$sql = "update students set Status=:status  WHERE matricNo=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query -> execute();
header('location:reg-students.php');
}


    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TTALMS - Student History</title>
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
                <?php $sid=$_GET['stdid']; ?>
                <h4 class="header-line" style="text-align: center; position: relative;"><i class="fa-solid fa-circle-info fa-beat"></i> <?php echo $sid;?> HISTORY</h4>
    </div>


        </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-primary" style="border-color: #9B00EA;">
                        <div class="panel-heading" style="text-align: center; background-color: #9B00EA;">

<?php echo $sid;?> History Details
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">#</th>
                                            <th style="text-align: center;">Student ID</th>
                                            <th style="text-align: center;">Student Name</th>
                                            <th style="text-align: center;">Borrowed Book  </th>
                                            <th style="text-align: center;">Borrowed Date</th>
                                            <th style="text-align: center;">Returned Date</th>
                                            <th style="text-align: center;">Fine in RM (if any)</th>
          
                                        </tr>
                                    </thead>
                                    <tbody>
<?php 

$sql = "SELECT students.matricNo ,students.FullName,students.EmailId,students.MobileNumber,books.BookName,books.ISBNNumber,issuedbookdetails.IssuesDate,issuedbookdetails.ReturnDate,issuedbookdetails.id as rid,issuedbookdetails.fine,issuedbookdetails.RetrunStatus,books.id as bid,books.bookImage from  issuedbookdetails join students on students.matricNo=issuedbookdetails.StudentId join books on books.id=issuedbookdetails.BookId where students.matricNo='$sid' ";
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
                                            <td class="center" style="text-align: center;"><?php echo htmlentities($result->matricNo);?></td>
                                            <td class="center"><?php echo htmlentities($result->FullName);?></td>
                                            <td class="center"><?php echo htmlentities($result->BookName);?></td>
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
                                                if (empty($result->ReturnDate)) {
                                                    echo '<span style="color: red; font-weight: bold;">Not returned yet</span>';
                                                } else {
                                                    echo date('d/m/Y h:i A', strtotime($result->ReturnDate));
                                                }
                                                ?>
                                            </td>
                                            <td class="center" style="text-align: center;">
                                                <?php 
                                                if (!empty($result->ReturnDate)) {
                                                    if ($result->fine == 0) {
                                                        echo '<span style="color: green; font-weight: bold;">No fine</span>';
                                                    } else {
                                                        echo '<span style="color: red; font-weight: bold;">' . number_format($result->fine, 2) . '</span>';
                                                    }
                                                } else {
                                                    echo '&nbsp;'; // Leave blank if the book is not returned yet
                                                }
                                                ?>
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

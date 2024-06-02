<?php 
require_once("includes/config.php");

if (!empty($_POST["bookid"])) {
    $bookid = $_POST["bookid"];
    
    // Sanitize and validate input
    function isValidInput($input) {
        // Check for presence of HTML tags
        if (preg_match('/<[^>]*>/', $input)) {
            return false;
        }
        return true;
    }

    if (!isValidInput($bookid)) {
        echo "<span style='color: red;'>Invalid input</span>";
        exit();
    }
  

    // Sanitize input to prevent XSS
    $bookid = htmlspecialchars($bookid, ENT_QUOTES, 'UTF-8');

    $sql = "SELECT DISTINCT books.BookName, books.id, authors.AuthorName, books.bookImage, books.isIssued 
            FROM books 
            JOIN authors ON authors.id = books.AuthorId 
            WHERE ISBNNumber = :bookid OR BookName LIKE :bookname";
    $query = $dbh->prepare($sql);
    $bookname_param = "%$bookid%";
    $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
    $query->bindParam(':bookname', $bookname_param, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        echo '<table border="1">';
        echo '<tr>';
        foreach ($results as $result) {
            echo '<th style="padding-left:5%; width: 10%;">';
            echo '<img src="bookimage/' . htmlentities($result->bookImage) . '" width="120"><br />';
            echo htmlentities($result->BookName) . '<br />';
            echo htmlentities($result->AuthorName) . '<br />';
            if ($result->isIssued == '1') {
                echo '<p style="color:red;">Book has already been borrowed for now.</p>';
            } else {
                echo '<input type="radio" name="bookid" value="' . htmlentities($result->id) . '" required>';
            }
            echo '</th>';
            echo "<script>$('#submit').prop('disabled', false);</script>";
        }
        echo '</tr>';
        echo '</table>';
    } else {
        echo '<p>Record not found. Please try again.</p>';
        echo "<script>$('#submit').prop('disabled', true);</script>";
    }
}
?>

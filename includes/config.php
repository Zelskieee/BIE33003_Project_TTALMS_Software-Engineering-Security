<?php
// DB credentials.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ttalms');
// Establish database connection.

$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
try {
	$dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
	exit("Error: " . $e->getMessage());
}


// email config
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'mukhtarsani20@gmail.com');
define('SMTP_PASSWORD', 'mlauyqvhjnprxzfp');
define('SMTP_SECURE', 'tls');
define('SMTP_PORT', 587);
define('FROM_EMAIL', 'noreply@uthmlibrary.com');

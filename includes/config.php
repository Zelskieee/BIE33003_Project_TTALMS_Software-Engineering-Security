<?php
// DB credentials.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ttalms_system');

// Establish database connection using MySQLi
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Establish database connection using PDO with TLS
$options = [
    PDO::MYSQL_ATTR_SSL_CA => 'C:\Users\Zelggx Arif\Desktop\openssl-3.3.0\ca-cert.pem',
    PDO::MYSQL_ATTR_SSL_CERT => 'C:\Users\Zelggx Arif\Desktop\openssl-3.3.0\server-cert.pem',
    PDO::MYSQL_ATTR_SSL_KEY => 'C:\Users\Zelggx Arif\Desktop\openssl-3.3.0\server-key.pem',
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
];

try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, $options);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}

// email config
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'epoojaproject@gmail.com');
define('SMTP_PASSWORD', 'mtgpycdwkypjagva');
define('SMTP_SECURE', 'tls');
define('SMTP_PORT', 587);
define('FROM_EMAIL', 'noreply@uthmlibrary.com');
?>

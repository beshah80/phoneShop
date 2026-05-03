<?php
// Session hardening
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 86400); // 24 hours

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_init();
    mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5); 
    if (!mysqli_real_connect($conn, 'localhost', 'phone_admin', 'phone123', 'shop_db', 3306)) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, 'utf8mb4');
} catch (Exception $e) {
    die("Error: Unable to connect to the database. Please try again later.");
}
?>
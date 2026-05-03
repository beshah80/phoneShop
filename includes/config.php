<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Log for debugging infinite reloads
if (!isset($_SESSION['config_loaded'])) {
    error_log("config.php loaded at " . date('Y-m-d H:i:s'));
    $_SESSION['config_loaded'] = true;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_init();
    mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5); // 5-second timeout
    if (!mysqli_real_connect($conn, 'localhost', 'root', '', 'shop_db', 3306)) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, 'utf8mb4');
    error_log("Database connected successfully at " . date('Y-m-d H:i:s'));
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage() . " at " . date('Y-m-d H:i:s'));
    die("Error: Unable to connect to the database. Please try again later.");
}
?>
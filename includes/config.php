<?php
// ==========================================
// 🚀 MODERN DEPLOYMENT CONFIG (Environment Variables)
// ==========================================

// Get variables from Railway environment
$db_host = getenv('DB_HOST');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');
$db_name = getenv('DB_NAME');
$db_port = getenv('DB_PORT') ?: 3306;

// Site URL
define('BASE_URL', getenv('BASE_URL') ?: '/');

// ==========================================
// 🛡️ SYSTEM CORE
// ==========================================

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 86400);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if variables are missing
if (!$db_host || !$db_user || !$db_name) {
    die("<h1>❌ Deployment Setup Incomplete</h1>
         <p>Your app is online, but it can't see the Database Variables.</p>
         <p><b>Please ensure you added DB_HOST, DB_USER, DB_PASS, and DB_NAME in the Railway Variables tab.</b></p>");
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_init();
    mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5); 
    if (!mysqli_real_connect($conn, $db_host, $db_user, $db_pass, $db_name, (int)$db_port)) {
        throw new Exception("Connection failed");
    }
    mysqli_set_charset($conn, 'utf8mb4');
} catch (Exception $e) {
    die("<h1>❌ Database Connection Failed</h1>
         <p>The variables are there, but the password or host is incorrect.</p>
         <p>Error: " . $e->getMessage() . "</p>");
}
?>
<?php
// ==========================================
// 🚀 MODERN DEPLOYMENT CONFIG (Environment Variables)
// ==========================================

// This section reads from the "Environment Variables" you set in Railway/Render/Vercel
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'phone_admin';
$db_pass = getenv('DB_PASS') ?: 'phone123';
$db_name = getenv('DB_NAME') ?: 'shop_db';
$db_port = getenv('DB_PORT') ?: 3306;

// Site URL (Set this to your live domain in environment variables)
define('BASE_URL', getenv('BASE_URL') ?: '/phoneShop/');

// ==========================================
// 🛡️ SYSTEM CORE
// ==========================================

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 86400);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    // Show detailed error only in local dev
    if (getenv('DB_HOST')) {
        die("Deployment Error: Database connection failed. Check your environment variables.");
    } else {
        die("Local Error: " . $e->getMessage());
    }
}
?>
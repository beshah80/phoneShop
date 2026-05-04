<?php
// ==========================================
// 🚀 SMART ZERO-CONFIG DEPLOYMENT
// ==========================================

function get_env_var($key) {
    return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?? null;
}

// 🔍 Smart Detection: Checks for Railway defaults OR custom names
$db_host = get_env_var('MYSQLHOST') ?: get_env_var('DB_HOST') ?: 'localhost';
$db_user = get_env_var('MYSQLUSER') ?: get_env_var('DB_USER') ?: 'phone_admin';
$db_pass = get_env_var('MYSQLPASSWORD') ?: get_env_var('DB_PASS') ?: 'phone123';
$db_name = get_env_var('MYSQLDATABASE') ?: get_env_var('DB_NAME') ?: 'shop_db';
$db_port = get_env_var('MYSQLPORT') ?: get_env_var('DB_PORT') ?: 3306;

define('BASE_URL', get_env_var('BASE_URL') ?: '/');

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
    echo "<h1>❌ Database Connection Failed</h1>";
    echo "<p>Your app found the variables, but could not connect.</p>";
    echo "<p><b>Host:</b> $db_host</p>";
    echo "<p><b>User:</b> $db_user</p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    die();
}
?>
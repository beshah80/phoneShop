<?php
// ==========================================
// 🚀 ROBUST DEPLOYMENT CONFIG
// ==========================================

// Helper function to get variables from ENV, SERVER, or getenv()
function get_env_var($key) {
    return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?? null;
}

$db_host = get_env_var('DB_HOST');
$db_user = get_env_var('DB_USER');
$db_pass = get_env_var('DB_PASS');
$db_name = get_env_var('DB_NAME');
$db_port = get_env_var('DB_PORT') ?: 3306;

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

// 🧪 DEBUG SECTION (This will help us see what is happening)
if (!$db_host || !$db_user || !$db_name) {
    echo "<h1>❌ Deployment Debugger</h1>";
    echo "<p>Checking variables...</p>";
    echo "<ul>";
    echo "<li>DB_HOST: " . ($db_host ? "✅ Found" : "❌ MISSING") . "</li>";
    echo "<li>DB_USER: " . ($db_user ? "✅ Found" : "❌ MISSING") . "</li>";
    echo "<li>DB_NAME: " . ($db_name ? "✅ Found" : "❌ MISSING") . "</li>";
    echo "</ul>";
    echo "<p><b>Action:</b> If you see 'MISSING', go to Railway and make sure you clicked the <b>'Deploy'</b> button after adding the variables.</p>";
    die();
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
         <p>Error: " . $e->getMessage() . "</p>");
}
?>
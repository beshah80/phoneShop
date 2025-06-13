<?php
// Handle message display (string or array)
if (isset($message)) {
    // Convert string to array if needed
    $messages = is_array($message) ? $message : [$message];
    foreach ($messages as $msg) {
        // Skip empty messages
        if (!empty(trim($msg))) {
            echo '
            <div class="message">
                <span>' . htmlspecialchars($msg) . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
}
?>

<header class="header">
    <div class="flex">
        <a href="admin_page.php" class="logo">PhoneSell<span>Admin</span></a>

        <nav class="navbar">
            <a href="admin_page.php">dashboard</a>
            <a href="admin_products.php">phones</a>
            <a href="admin_orders.php">orders</a>
            <a href="admin_users.php">users</a>
            <a href="admin_contacts.php">messages</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="account-box">
            <p>username: <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'N/A'); ?></span></p>
            <p>email: <span><?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'N/A'); ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
            <div>new <a href="login.php">login</a> | <a href="register.php">register</a></div>
        </div>
    </div>
</header>
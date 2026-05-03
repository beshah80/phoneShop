<?php
include_once __DIR__ . '/config.php';

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $stmt = mysqli_prepare($conn, "SELECT status FROM `seller_applications` WHERE user_id = ? AND status = 'approved'");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        header('Location: ../home.php');
        exit();
    }
    mysqli_stmt_close($stmt);
}

if (isset($messages)) {
    $message_list = is_array($messages) ? $messages : [$messages];
    foreach ($message_list as $msg) {
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
    <link rel="stylesheet" href="../assets/css/seller_style.css">
    <div class="flex">
        <a href="dashboard.php" class="logo">PhoneSell</a>
        <nav class="navbar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php">My Products</a></li>
                <li><a href="add_product.php">Add Product</a></li>
            </ul>
        </nav>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="../search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
            $wishlist_count = 0;
            $cart_count = 0;
            if ($user_id) {
                $stmt_wishlist = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `wishlist` WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt_wishlist, "i", $user_id);
                mysqli_stmt_execute($stmt_wishlist);
                $wishlist_result = mysqli_stmt_get_result($stmt_wishlist);
                $wishlist_count = mysqli_fetch_assoc($wishlist_result)['count'];
                mysqli_stmt_close($stmt_wishlist);

                $stmt_cart = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `cart` WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt_cart, "i", $user_id);
                mysqli_stmt_execute($stmt_cart);
                $cart_result = mysqli_stmt_get_result($stmt_cart);
                $cart_count = mysqli_fetch_assoc($cart_result)['count'];
                mysqli_stmt_close($stmt_cart);
            }
            ?>
            <a href="../wishlist.php"><i class="fas fa-heart"></i><span>(<?php echo $wishlist_count; ?>)</span></a>
            <a href="../cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cart_count; ?>)</span></a>
        </div>
        <div class="account-box">
            <p>username: <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></span></p>
            <p>email: <span><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'N/A'); ?></span></p>
            <a href="../logout.php" class="delete-btn">logout</a>
            <a href="../home.php" class="option-btn">back to home</a>
        </div>
    </div>
</header>
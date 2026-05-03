<?php
include '../includes/seller_header.php';
include '../includes/config.php';

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$messages = [];

if (!$user_id) {
    header('Location: ../login.php');
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT status FROM `seller_applications` WHERE user_id = ? AND status = 'approved'");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) == 0) {
    header('Location: home.php');
    exit();
}
mysqli_stmt_close($stmt);

$stmt_products = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `products` WHERE seller_id = ?");
mysqli_stmt_bind_param($stmt_products, "i", $user_id);
mysqli_stmt_execute($stmt_products);
$products_result = mysqli_stmt_get_result($stmt_products);
$products = mysqli_fetch_assoc($products_result) ?: ['count' => 0];
mysqli_stmt_close($stmt_products);

$stmt_orders = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `orders` WHERE user_id = ?");
mysqli_stmt_bind_param($stmt_orders, "i", $user_id);
mysqli_stmt_execute($stmt_orders);
$orders_result = mysqli_stmt_get_result($stmt_orders);
$orders = mysqli_fetch_assoc($orders_result) ?: ['count' => 0];
mysqli_stmt_close($stmt_orders);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/seller_style.css">
</head>
<body>

<section class="dashboard">
    <h1 class="title">Seller Dashboard</h1>
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <div class="message">
                <span><?php echo htmlspecialchars($msg); ?></span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="box-container">
        <div class="box">
            <h3><?php echo $products['count']; ?></h3>
            <p>Products Listed</p>
        </div>
        <div class="box">
            <h3><?php echo $orders['count']; ?></h3>
            <p>Orders Received</p>
        </div>
        <div class="box">
            <h3>Welcome!</h3>
            <p><a href="add_product.php" class="btn">Add Your First Product</a></p>
        </div>
    </div>
</section>

<script src="../assets/js/seller_script.js"></script>
</body>
</html>
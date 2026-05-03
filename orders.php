<?php
include 'includes/config.php';
// session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>Your Orders</h3>
    <p><a href="home.php">Home</a> / Orders</p>
</section>

<section class="placed-orders">
    <h1 class="title">Phone Orders</h1>
    <div class="box-container">
        <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
        ?>
        <div class="order-card">
            <div class="order-card-header">
                <span class="order-date"><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($fetch_orders['placed_on']); ?></span>
                <span class="order-status <?php echo $fetch_orders['payment_status'] == 'pending' ? 'pending' : 'paid'; ?>">
                    <i class="fas fa-<?php echo $fetch_orders['payment_status'] == 'pending' ? 'hourglass-half' : 'check-circle'; ?>"></i>
                    <?php echo htmlspecialchars(ucfirst($fetch_orders['payment_status'])); ?>
                </span>
            </div>
            <div class="order-card-body">
                <div class="order-info">
                    <div><i class="fas fa-user"></i> <?php echo htmlspecialchars($fetch_orders['name']); ?></div>
                    <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($fetch_orders['number']); ?></div>
                    <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($fetch_orders['email']); ?></div>
                    <div><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($fetch_orders['address']); ?></div>
                </div>
                <div class="order-details">
                    <div><i class="fas fa-list"></i> <strong>Items:</strong> <?php echo htmlspecialchars($fetch_orders['total_products']); ?></div>
                    <div><i class="fas fa-credit-card"></i> <strong>Method:</strong> <?php echo htmlspecialchars($fetch_orders['method']); ?></div>
                    <div class="order-total"></i> <strong>Total:</strong> <?php echo number_format($fetch_orders['total_price'], 2); ?> ETB</div>
                </div>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No phone orders placed yet!</p>';
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/script.js"></script>
</body>
</html>
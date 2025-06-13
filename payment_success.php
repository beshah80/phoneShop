<?php
include 'config.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('Location: login.php');
    exit();
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    header('Location: home.php');
    exit();
}

// Fetch order details with payment information
$order_query = "SELECT o.*, pm.name as payment_name, pt.transaction_id, pt.status as payment_status
                FROM orders o 
                JOIN payment_methods pm ON o.payment_method_id = pm.id 
                JOIN payment_transactions pt ON o.id = pt.order_id
                WHERE o.id = ? AND o.user_id = ?";
$stmt = mysqli_prepare($conn, $order_query);
mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($stmt);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$order) {
    header('Location: home.php');
    exit();
}

// Fetch order items
$items_query = "SELECT p.name, p.image, oi.quantity, oi.price
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?";
$stmt = mysqli_prepare($conn, $items_query);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$items = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="heading">
    <h3>Payment Successful</h3>
    <p><a href="home.php">Home</a> / Payment Success</p>
</section>

<section class="success-section">
    <div class="container">
        <div class="success-details">
            <div class="success-header">
                <i class="fas fa-check-circle"></i>
                <h2>Payment Successful!</h2>
                <p>Thank you for your purchase. Your order has been confirmed.</p>
            </div>

            <div class="order-summary">
                <h3>Order Summary</h3>
                <div class="order-info">
                    <p>Order ID: <span><?php echo $order_id; ?></span></p>
                    <p>Transaction ID: <span><?php echo htmlspecialchars($order['transaction_id']); ?></span></p>
                    <p>Payment Method: <span><?php echo htmlspecialchars($order['payment_name']); ?></span></p>
                    <p>Payment Status: <span class="status-<?php echo $order['payment_status']; ?>"><?php echo ucfirst($order['payment_status']); ?></span></p>
                    <p>Total Amount: <span>ETB <?php echo number_format($order['total_price'], 2); ?></span></p>
                    <p>Order Date: <span><?php echo date('F j, Y', strtotime($order['placed_on'])); ?></span></p>
                </div>

                <div class="order-items">
                    <h4>Order Items</h4>
                    <div class="items-list">
                        <?php while ($item = mysqli_fetch_assoc($items)): ?>
                        <div class="item">
                            <img src="uploaded_img/<?php echo htmlspecialchars($item['image']); ?>" alt="">
                            <div class="item-details">
                                <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p>Quantity: <?php echo $item['quantity']; ?></p>
                                <p>Price: ETB <?php echo number_format($item['price'], 2); ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="delivery-info">
                    <h4>Delivery Information</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['number']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                </div>

                <div class="next-steps">
                    <h4>Next Steps</h4>
                    <ul>
                        <li><i class="fas fa-envelope"></i> You will receive an order confirmation email shortly.</li>
                        <li><i class="fas fa-truck"></i> We will process your order and update you on the delivery status.</li>
                        <li><i class="fas fa-phone"></i> Our delivery team will contact you to confirm the delivery time.</li>
                    </ul>
                </div>

                <div class="action-buttons">
                    <a href="orders.php" class="btn">View All Orders</a>
                    <a href="shop.php" class="btn">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.success-section {
    padding: 4rem 0;
}

.success-details {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background: var(--ivory-cream);
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.success-header {
    text-align: center;
    margin-bottom: 3rem;
}

.success-header i {
    font-size: 6rem;
    color: #28a745;
    margin-bottom: 1rem;
}

.success-header h2 {
    color: var(--dusk-navy);
    font-size: 2.4rem;
    margin-bottom: 1rem;
}

.success-header p {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
}

.order-summary {
    background: var(--snow-glow);
    padding: 2rem;
    border-radius: 0.8rem;
}

.order-summary h3 {
    color: var(--dusk-navy);
    font-size: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

.order-info {
    background: var(--ivory-cream);
    padding: 2rem;
    border-radius: 0.8rem;
    margin-bottom: 2rem;
}

.order-info p {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
}

.order-info p span {
    font-weight: bold;
    color: var(--mystic-blue);
}

.status-completed {
    color: #28a745 !important;
}

.status-pending {
    color: #ffc107 !important;
}

.order-items {
    margin-bottom: 2rem;
}

.order-items h4 {
    color: var(--dusk-navy);
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
}

.items-list {
    background: var(--ivory-cream);
    padding: 1.5rem;
    border-radius: 0.8rem;
}

.item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--mist-silver);
}

.item:last-child {
    border-bottom: none;
}

.item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.5rem;
    margin-right: 1.5rem;
}

.item-details h5 {
    font-size: 1.6rem;
    color: var(--dusk-navy);
    margin-bottom: 0.5rem;
}

.item-details p {
    font-size: 1.4rem;
    color: var(--deep-obsidian);
    margin-bottom: 0.3rem;
}

.delivery-info {
    background: var(--ivory-cream);
    padding: 2rem;
    border-radius: 0.8rem;
    margin-bottom: 2rem;
}

.delivery-info h4 {
    color: var(--dusk-navy);
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
}

.delivery-info p {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    margin-bottom: 1rem;
}

.next-steps {
    background: var(--ivory-cream);
    padding: 2rem;
    border-radius: 0.8rem;
    margin-bottom: 2rem;
}

.next-steps h4 {
    color: var(--dusk-navy);
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
}

.next-steps ul {
    list-style: none;
}

.next-steps li {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.next-steps li i {
    color: var(--mystic-blue);
    margin-right: 1rem;
    font-size: 1.8rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.action-buttons .btn {
    flex: 1;
    max-width: 200px;
    text-align: center;
}

@media (max-width: 768px) {
    .success-details {
        padding: 1.5rem;
    }

    .success-header i {
        font-size: 4rem;
    }

    .success-header h2 {
        font-size: 2rem;
    }

    .order-summary,
    .order-info,
    .items-list,
    .delivery-info,
    .next-steps {
        padding: 1.5rem;
    }

    .item {
        flex-direction: column;
        text-align: center;
    }

    .item img {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .action-buttons {
        flex-direction: column;
    }

    .action-buttons .btn {
        max-width: 100%;
    }
}
</style>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html> 
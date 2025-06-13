<?php
include 'config.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('Location: login.php');
    exit();
}

// Create payment_methods table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS `payment_methods` (
    `id` int(100) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(50) NOT NULL,
    `icon` varchar(100) NOT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

mysqli_query($conn, $create_table_sql);

// Check if payment methods exist, if not insert them
$check_methods = mysqli_query($conn, "SELECT COUNT(*) as count FROM payment_methods");
$method_count = mysqli_fetch_assoc($check_methods)['count'];

if ($method_count == 0) {
    $insert_methods_sql = "INSERT INTO `payment_methods` (`name`, `code`, `icon`, `is_active`) VALUES
        ('Telebirr', 'telebirr', 'fab fa-telegram', 1),
        ('Amole', 'amole', 'fas fa-wallet', 1),
        ('CBE Birr', 'cbe_birr', 'fas fa-university', 1),
        ('Dashen Bank', 'dashen', 'fas fa-university', 1),
        ('Cash on Delivery', 'cod', 'fas fa-money-bill-wave', 1),
        ('Credit Card', 'credit_card', 'far fa-credit-card', 1)";
    mysqli_query($conn, $insert_methods_sql);
}

$messages = [];

// Fetch cart items
$cart_query = "SELECT c.*, p.name, p.price, p.image 
               FROM cart c 
               JOIN products p ON c.pid = p.id 
               WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $cart_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$cart_items = mysqli_stmt_get_result($stmt);

$total_price = 0;
$total_products = 0;

// Calculate totals
while ($item = mysqli_fetch_assoc($cart_items)) {
    $total_price += $item['price'] * $item['quantity'];
    $total_products += $item['quantity'];
}

// Fetch active payment methods
$payment_query = "SELECT * FROM payment_methods WHERE is_active = 1";
$payment_methods = mysqli_query($conn, $payment_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method_id = (int)$_POST['payment_method'];

    if (empty($name) || empty($number) || empty($email) || empty($address) || empty($payment_method_id)) {
        $messages[] = 'Please fill in all fields';
    } else {
        mysqli_begin_transaction($conn);
        try {
            // Insert order
            $order_query = "INSERT INTO orders (user_id, name, number, email, address, total_products, total_price, payment_method_id, currency) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ETB')";
            $stmt = mysqli_prepare($conn, $order_query);
            mysqli_stmt_bind_param($stmt, "issssidi", $user_id, $name, $number, $email, $address, $total_products, $total_price, $payment_method_id);
            mysqli_stmt_execute($stmt);
            $order_id = mysqli_insert_id($conn);

            // Insert order items
            $cart_items = mysqli_query($conn, "SELECT c.*, p.price FROM cart c JOIN products p ON c.pid = p.id WHERE c.user_id = $user_id");
            while ($item = mysqli_fetch_assoc($cart_items)) {
                $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $item_query);
                mysqli_stmt_bind_param($stmt, "iiid", $order_id, $item['pid'], $item['quantity'], $item['price']);
                mysqli_stmt_execute($stmt);
            }

            // Create payment transaction
            $transaction_query = "INSERT INTO payment_transactions (order_id, payment_method_id, amount, currency, status) 
                                VALUES (?, ?, ?, 'ETB', 'pending')";
            $stmt = mysqli_prepare($conn, $transaction_query);
            mysqli_stmt_bind_param($stmt, "iid", $order_id, $payment_method_id, $total_price);
            mysqli_stmt_execute($stmt);

            // Clear cart
            mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

            mysqli_commit($conn);

            // Redirect to payment page
            $payment_method = mysqli_fetch_assoc(mysqli_query($conn, "SELECT code FROM payment_methods WHERE id = $payment_method_id"));
            header("Location: payment_process.php?order_id=$order_id&method=" . $payment_method['code']);
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $messages[] = 'An error occurred. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="heading">
    <h3>Checkout</h3>
    <p><a href="home.php">Home</a> / Checkout</p>
</section>

<section class="checkout-section">
    <div class="container">
        <?php if (!empty($messages)): ?>
        <div class="messages">
            <?php foreach ($messages as $message): ?>
            <div class="message"><?php echo $message; ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="checkout-container">
            <div class="order-summary">
                <h3>Order Summary</h3>
                <div class="cart-items">
    <?php
                    $cart_items = mysqli_query($conn, "SELECT c.*, p.name, p.price, p.image 
                                                      FROM cart c 
                                                      JOIN products p ON c.pid = p.id 
                                                      WHERE c.user_id = $user_id");
                    while ($item = mysqli_fetch_assoc($cart_items)):
                    ?>
                    <div class="item">
                        <img src="uploaded_img/<?php echo htmlspecialchars($item['image']); ?>" alt="">
                        <div class="item-details">
                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                            <div class="price"><?php echo number_format($item['price'], 2); ?> ETB</div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <div class="total">
                    <p>Total Items: <span><?php echo $total_products; ?></span></p>
                    <p>Total Amount: <span><?php echo number_format($total_price, 2); ?> ETB</span></p>
                </div>
            </div>

            <div class="checkout-form">
                <h3>Delivery Information</h3>
    <form action="" method="POST">
            <div class="inputBox">
                        <span>Name:</span>
                <input type="text" name="name" placeholder="Enter your name" required>
            </div>
            <div class="inputBox">
                        <span>Phone Number:</span>
                        <input type="tel" name="number" placeholder="Enter your phone number" required>
            </div>
            <div class="inputBox">
                        <span>Email:</span>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="inputBox">
                        <span>Address:</span>
                        <textarea name="address" placeholder="Enter your delivery address" required></textarea>
            </div>

                    <div class="payment-methods">
                        <h3>Select Payment Method</h3>
                        <div class="methods-grid">
                            <?php while ($method = mysqli_fetch_assoc($payment_methods)): ?>
                            <div class="method-item">
                                <input type="radio" name="payment_method" id="method_<?php echo $method['id']; ?>" 
                                       value="<?php echo $method['id']; ?>" required>
                                <label for="method_<?php echo $method['id']; ?>">
                                    <i class="<?php echo $method['icon']; ?>"></i>
                                    <span><?php echo htmlspecialchars($method['name']); ?></span>
                                </label>
            </div>
                            <?php endwhile; ?>
            </div>
            </div>

                    <input type="submit" value="Place Order" class="btn">
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.checkout-section {
    padding: 4rem 0;
}

.checkout-container {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
}

.order-summary {
    background: var(--ivory-cream);
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.order-summary h3 {
    color: var(--dusk-navy);
    font-size: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

.cart-items {
    margin-bottom: 2rem;
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

.item-details h4 {
    font-size: 1.6rem;
    color: var(--dusk-navy);
    margin-bottom: 0.5rem;
}

.item-details p {
    font-size: 1.4rem;
    color: var(--deep-obsidian);
    margin-bottom: 0.3rem;
}

.total {
    background: var(--snow-glow);
    padding: 1.5rem;
    border-radius: 0.8rem;
}

.total p {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
}

.total p span {
    font-weight: bold;
    color: var(--mystic-blue);
}

.checkout-form {
    background: var(--ivory-cream);
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.checkout-form h3 {
    color: var(--dusk-navy);
    font-size: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

.inputBox {
    margin-bottom: 2rem;
}

.inputBox span {
    display: block;
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    margin-bottom: 0.8rem;
}

.inputBox input,
.inputBox textarea {
    width: 100%;
    padding: 1rem;
    font-size: 1.4rem;
    border: 1px solid var(--mist-silver);
    border-radius: 0.5rem;
    background: var(--snow-glow);
}

.inputBox textarea {
    height: 100px;
    resize: vertical;
}

.payment-methods {
    margin-top: 3rem;
}

.payment-methods h3 {
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
}

.methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.method-item {
    position: relative;
}

.method-item input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.method-item label {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: var(--snow-glow);
    border: 2px solid var(--mist-silver);
    border-radius: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.method-item input[type="radio"]:checked + label {
    border-color: var(--mystic-blue);
    background: var(--ivory-cream);
}

.method-item label i {
    font-size: 2rem;
    color: var(--mystic-blue);
    margin-right: 1rem;
}

.method-item label span {
    font-size: 1.4rem;
    color: var(--deep-obsidian);
}

.btn {
    width: 100%;
    padding: 1.2rem;
    font-size: 1.6rem;
    background: var(--mystic-blue);
    color: var(--snow-glow);
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-top: 2rem;
}

.btn:hover {
    background: var(--sunlit-gold);
}

@media (max-width: 768px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }

    .order-summary,
    .checkout-form {
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

    .methods-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>

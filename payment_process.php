<?php
include 'config.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('Location: login.php');
    exit();
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$method = isset($_GET['method']) ? $_GET['method'] : '';

if (!$order_id || !$method) {
    header('Location: home.php');
    exit();
}

// Fetch order details
$order_query = "SELECT o.*, pm.code as payment_code, pm.name as payment_name 
                FROM orders o 
                JOIN payment_methods pm ON o.payment_method_id = pm.id 
                WHERE o.id = ? AND o.user_id = ?";
$stmt = mysqli_prepare($conn, $order_query);
mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($stmt);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$order) {
    header('Location: home.php');
    exit();
}

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($method) {
        case 'telebirr':
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $pin = mysqli_real_escape_string($conn, $_POST['pin']);
            
            // Here you would integrate with Telebirr API
            // For now, we'll simulate a successful payment
            $transaction_id = 'TELEBIRR' . time() . rand(1000, 9999);
            updatePaymentStatus($conn, $order_id, $transaction_id, 'completed');
            header('Location: payment_success.php?order_id=' . $order_id);
            exit();
            break;

        case 'amole':
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $pin = mysqli_real_escape_string($conn, $_POST['pin']);
            
            // Here you would integrate with Amole API
            $transaction_id = 'AMOLE' . time() . rand(1000, 9999);
            updatePaymentStatus($conn, $order_id, $transaction_id, 'completed');
            header('Location: payment_success.php?order_id=' . $order_id);
            exit();
            break;

        case 'cbe_birr':
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $pin = mysqli_real_escape_string($conn, $_POST['pin']);
            
            // Here you would integrate with CBE Birr API
            $transaction_id = 'CBEBIRR' . time() . rand(1000, 9999);
            updatePaymentStatus($conn, $order_id, $transaction_id, 'completed');
            header('Location: payment_success.php?order_id=' . $order_id);
            exit();
            break;

        case 'dashen':
            $account = mysqli_real_escape_string($conn, $_POST['account']);
            $pin = mysqli_real_escape_string($conn, $_POST['pin']);
            
            // Here you would integrate with Dashen Bank API
            $transaction_id = 'DASHEN' . time() . rand(1000, 9999);
            updatePaymentStatus($conn, $order_id, $transaction_id, 'completed');
            header('Location: payment_success.php?order_id=' . $order_id);
            exit();
            break;

        case 'cod':
            // For Cash on Delivery, we'll just mark it as pending
            $transaction_id = 'COD' . time() . rand(1000, 9999);
            updatePaymentStatus($conn, $order_id, $transaction_id, 'pending');
            header('Location: payment_success.php?order_id=' . $order_id);
            exit();
            break;

        case 'credit_card':
            $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
            $expiry = mysqli_real_escape_string($conn, $_POST['expiry']);
            $cvv = mysqli_real_escape_string($conn, $_POST['cvv']);
            
            // Here you would integrate with a payment gateway
            $transaction_id = 'CARD' . time() . rand(1000, 9999);
            updatePaymentStatus($conn, $order_id, $transaction_id, 'completed');
            header('Location: payment_success.php?order_id=' . $order_id);
            exit();
            break;
    }
}

function updatePaymentStatus($conn, $order_id, $transaction_id, $status) {
    mysqli_begin_transaction($conn);
    try {
        // Update payment transaction
        $update_transaction = "UPDATE payment_transactions 
                             SET transaction_id = ?, status = ? 
                             WHERE order_id = ?";
        $stmt = mysqli_prepare($conn, $update_transaction);
        mysqli_stmt_bind_param($stmt, "ssi", $transaction_id, $status, $order_id);
        mysqli_stmt_execute($stmt);

        // Update order status
        $update_order = "UPDATE orders 
                        SET payment_status = ? 
                        WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_order);
        mysqli_stmt_bind_param($stmt, "si", $status, $order_id);
        mysqli_stmt_execute($stmt);

        mysqli_commit($conn);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        throw $e;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - <?php echo htmlspecialchars($order['payment_name']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="heading">
    <h3>Payment</h3>
    <p><a href="home.php">Home</a> / Payment</p>
</section>

<section class="payment-section">
    <div class="container">
        <div class="payment-details">
            <h2>Order Summary</h2>
            <div class="order-info">
                <p>Order ID: <span><?php echo $order_id; ?></span></p>
                <p>Total Amount: <span>ETB <?php echo number_format($order['total_price'], 2); ?></span></p>
                <p>Payment Method: <span><?php echo htmlspecialchars($order['payment_name']); ?></span></p>
            </div>

            <div class="payment-form">
                <h3>Complete Your Payment</h3>
                <form action="" method="POST">
                    <?php switch ($method):
                        case 'telebirr':
                    ?>
                        <div class="inputBox">
                            <span>Telebirr Phone Number:</span>
                            <input type="tel" name="phone" placeholder="e.g., 0912345678" pattern="[0-9]{10}" required>
                        </div>
                        <div class="inputBox">
                            <span>PIN:</span>
                            <input type="password" name="pin" placeholder="Enter your Telebirr PIN" required>
                        </div>
                    <?php break;
                        case 'amole':
                    ?>
                        <div class="inputBox">
                            <span>Amole Phone Number:</span>
                            <input type="tel" name="phone" placeholder="e.g., 0912345678" pattern="[0-9]{10}" required>
                        </div>
                        <div class="inputBox">
                            <span>PIN:</span>
                            <input type="password" name="pin" placeholder="Enter your Amole PIN" required>
                        </div>
                    <?php break;
                        case 'cbe_birr':
                    ?>
                        <div class="inputBox">
                            <span>CBE Birr Phone Number:</span>
                            <input type="tel" name="phone" placeholder="e.g., 0912345678" pattern="[0-9]{10}" required>
                        </div>
                        <div class="inputBox">
                            <span>PIN:</span>
                            <input type="password" name="pin" placeholder="Enter your CBE Birr PIN" required>
                        </div>
                    <?php break;
                        case 'dashen':
                    ?>
                        <div class="inputBox">
                            <span>Account Number:</span>
                            <input type="text" name="account" placeholder="Enter your Dashen account number" required>
                        </div>
                        <div class="inputBox">
                            <span>PIN:</span>
                            <input type="password" name="pin" placeholder="Enter your Dashen PIN" required>
                        </div>
                    <?php break;
                        case 'credit_card':
                    ?>
                        <div class="inputBox">
                            <span>Card Number:</span>
                            <input type="text" name="card_number" placeholder="1234 5678 9012 3456" pattern="[0-9\s]{16,19}" required>
                        </div>
                        <div class="inputBox">
                            <span>Expiry Date:</span>
                            <input type="text" name="expiry" placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/([0-9]{2})" required>
                        </div>
                        <div class="inputBox">
                            <span>CVV:</span>
                            <input type="password" name="cvv" placeholder="123" pattern="[0-9]{3,4}" required>
                        </div>
                    <?php break;
                        case 'cod':
                    ?>
                        <div class="cod-info">
                            <p>You have chosen Cash on Delivery. Our delivery agent will collect the payment when delivering your order.</p>
                            <p>Total amount to be paid: <strong>ETB <?php echo number_format($order['total_price'], 2); ?></strong></p>
                        </div>
                    <?php break;
                    endswitch; ?>

                    <input type="submit" value="Complete Payment" class="btn">
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.payment-section {
    padding: 4rem 0;
}

.payment-details {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background: var(--ivory-cream);
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.payment-details h2 {
    color: var(--dusk-navy);
    font-size: 2.4rem;
    margin-bottom: 2rem;
    text-align: center;
}

.order-info {
    background: var(--snow-glow);
    padding: 2rem;
    border-radius: 0.8rem;
    margin-bottom: 3rem;
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

.payment-form {
    background: var(--snow-glow);
    padding: 2rem;
    border-radius: 0.8rem;
}

.payment-form h3 {
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

.inputBox input {
    width: 100%;
    padding: 1rem;
    font-size: 1.4rem;
    border: 1px solid var(--mist-silver);
    border-radius: 0.5rem;
    background: var(--ivory-cream);
}

.cod-info {
    text-align: center;
    padding: 2rem;
    background: var(--ivory-cream);
    border-radius: 0.8rem;
    margin-bottom: 2rem;
}

.cod-info p {
    font-size: 1.6rem;
    color: var(--deep-obsidian);
    margin-bottom: 1rem;
}

.cod-info strong {
    color: var(--mystic-blue);
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
}

.btn:hover {
    background: var(--sunlit-gold);
}

@media (max-width: 768px) {
    .payment-details {
        padding: 1.5rem;
    }

    .order-info,
    .payment-form {
        padding: 1.5rem;
    }

    .payment-details h2 {
        font-size: 2rem;
    }

    .payment-form h3 {
        font-size: 1.8rem;
    }

    .order-info p,
    .inputBox span {
        font-size: 1.4rem;
    }
}
</style>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html> 
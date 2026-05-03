<?php
include 'includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($user_id)) {
    header('Location: login.php');
    exit();
}

$payment_query = "SELECT * FROM payment_methods WHERE is_active = 1";
$payment_methods = mysqli_query($conn, $payment_query);

$cart_query = "SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.pid = p.id WHERE c.user_id = '$user_id'";
$cart_result = mysqli_query($conn, $cart_query);

$total_price = 0;
$total_products = 0;
while($row = mysqli_fetch_assoc($cart_result)) {
    $total_price += $row['price'] * $row['quantity'];
    $total_products += $row['quantity'];
}
mysqli_data_seek($cart_result, 0); // Reset result pointer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $method_id = (int)$_POST['payment_method'];

    if ($total_price > 0) {
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, address, total_products, total_price, payment_method_id, currency) VALUES('$user_id', '$name', '$number', '$email', '$address', '$total_products', '$total_price', '$method_id', 'ETB')") or die('query failed');
        
        $order_id = mysqli_insert_id($conn);
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        
        header("Location: orders.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: var(--jiji-bg);">

<?php include 'includes/header.php'; ?>

<section class="jiji-hero" style="padding: 2rem 7%;">
    <div class="container">
        <h2 style="font-size: 2.5rem;">Secure Checkout</h2>
    </div>
</section>

<div class="container" style="padding: 3rem 7%;">
    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 3rem;">
        
        <!-- Delivery Info -->
        <div style="background: #fff; padding: 3rem; border-radius: 0.8rem; box-shadow: var(--box-shadow);">
            <h3 style="font-size: 2rem; margin-bottom: 2rem; color: var(--text-black); border-bottom: 2px solid #eee; padding-bottom: 1rem;">Delivery Details</h3>
            <form action="" method="POST">
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 1.4rem; margin-bottom: 0.8rem;">Full Name</label>
                    <input type="text" name="name" required style="width: 100%; padding: 1.2rem; border: 1px solid #ddd; border-radius: 0.4rem; font-size: 1.5rem;">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; font-size: 1.4rem; margin-bottom: 0.8rem;">Phone Number</label>
                        <input type="tel" name="number" required style="width: 100%; padding: 1.2rem; border: 1px solid #ddd; border-radius: 0.4rem; font-size: 1.5rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 1.4rem; margin-bottom: 0.8rem;">Email Address</label>
                        <input type="email" name="email" required style="width: 100%; padding: 1.2rem; border: 1px solid #ddd; border-radius: 0.4rem; font-size: 1.5rem;">
                    </div>
                </div>
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 1.4rem; margin-bottom: 0.8rem;">Full Address</label>
                    <textarea name="address" required style="width: 100%; padding: 1.2rem; border: 1px solid #ddd; border-radius: 0.4rem; font-size: 1.5rem; height: 100px;"></textarea>
                </div>

                <h3 style="font-size: 2rem; margin-bottom: 2rem; margin-top: 3rem; color: var(--text-black); border-bottom: 2px solid #eee; padding-bottom: 1rem;">Payment Method</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <?php while($method = mysqli_fetch_assoc($payment_methods)): ?>
                    <label style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; border: 1px solid #eee; border-radius: 0.8rem; cursor: pointer;">
                        <input type="radio" name="payment_method" value="<?php echo $method['id']; ?>" required>
                        <i class="<?php echo $method['icon']; ?>" style="font-size: 2rem; color: var(--jiji-green);"></i>
                        <span style="font-size: 1.4rem; font-weight: 600;"><?php echo $method['name']; ?></span>
                    </label>
                    <?php endwhile; ?>
                </div>

                <button type="submit" class="btn" style="width: 100%; padding: 1.5rem; margin-top: 4rem; font-size: 1.8rem; background: var(--jiji-orange); border-radius: 0.8rem; font-weight: 800;">CONFIRM ORDER</button>
            </form>
        </div>

        <!-- Order Summary -->
        <div style="background: #fff; padding: 2.5rem; border-radius: 0.8rem; box-shadow: var(--box-shadow); align-self: start;">
            <h3 style="font-size: 1.8rem; margin-bottom: 2rem;">Order Summary</h3>
            <div style="max-height: 400px; overflow-y: auto; margin-bottom: 2rem;">
                <?php while($item = mysqli_fetch_assoc($cart_result)): ?>
                <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f5f5f5; padding-bottom: 1rem;">
                    <img src="assets/uploads/<?php echo $item['image']; ?>" width="50" style="border-radius: 0.4rem;">
                    <div>
                        <p style="font-size: 1.3rem; font-weight: 600;"><?php echo $item['name']; ?></p>
                        <p style="font-size: 1.2rem; color: var(--text-gray);"><?php echo $item['quantity']; ?> x <?php echo number_format($item['price']); ?> ETB</p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div style="border-top: 2px solid #eee; padding-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; font-size: 1.6rem; margin-bottom: 1rem;">
                    <span>Subtotal</span>
                    <span><?php echo number_format($total_price); ?> ETB</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 1.6rem; margin-bottom: 1rem;">
                    <span>Delivery</span>
                    <span style="color: var(--jiji-green);">FREE</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 2rem; font-weight: 800; color: var(--text-black); margin-top: 1rem;">
                    <span>Total</span>
                    <span style="color: var(--jiji-green);"><?php echo number_format($total_price); ?> ETB</span>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>

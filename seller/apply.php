<?php
include '../includes/config.php';

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$messages = [];

if (!$user_id) {
    header('location:../login.php');
    exit();
}

$user_query = mysqli_query($conn, "SELECT user_type FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_query);

if ($user['user_type'] === 'seller' || $user['user_type'] === 'admin') {
    $messages[] = 'You are already a seller or admin!';
    header('location:home.php');
    exit();
}

$order_query = mysqli_query($conn, "SELECT COUNT(*) as order_count FROM orders WHERE user_id = $user_id AND payment_status = 'completed'");
$orders = mysqli_fetch_assoc($order_query);
if ($orders['order_count'] < 1) {
    $messages[] = 'You need at least one completed order!';
}

// Check if user has already applied
$application_query = mysqli_query($conn, "SELECT COUNT(*) as app_count FROM seller_applications WHERE user_id = $user_id");
$application = mysqli_fetch_assoc($application_query);
if ($application['app_count'] > 0) {
    $messages[] = 'You have already applied to be a seller!';
}

if (isset($_POST['apply']) && $application['app_count'] == 0) {
    $tax_id = mysqli_real_escape_string($conn, $_POST['tax_id'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if ($orders['order_count'] >= 1) {
        $query = "INSERT INTO seller_applications (user_id, tax_id, phone, address, status, applied_on) 
                  VALUES ($user_id, '$tax_id', '$phone', '$address', 'pending', CURDATE())";
        if (mysqli_query($conn, $query)) {
            $messages[] = 'Application submitted! Await admin approval.';
        } else {
            $messages[] = 'Failed to submit application: ' . mysqli_error($conn);
        }
    } else {
        $messages[] = 'You do not meet the seller criteria!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply to be a Seller</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/seller_style.css">
  
</head>
<body>
<?php include '../includes/header.php'; ?>

<section class="add-products">
    <h1 class="title">Become a Seller</h1>
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <div class="message">
                <span><?php echo $msg; ?></span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($application['app_count'] == 0 && $user['user_type'] !== 'seller'): ?>
        <form action="" method="POST" class="application-form">
            <h3>Seller Application</h3>
            <div class="input-group">
                <label for="tax_id">Tax ID (Optional)</label>
                <input type="text" id="tax_id" name="tax_id" class="box" placeholder="Enter Tax ID" maxlength="100">
            </div>
            <div class="input-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="box" placeholder="Enter Phone Number" required maxlength="20" pattern="[0-9]{10,20}">
            </div>
            <div class="input-group">
                <label for="address">Business Address</label>
                <textarea id="address" name="address" class="box" placeholder="Enter Business Address" required maxlength="255"></textarea>
            </div>
            <input type="submit" value="Submit Application" name="apply" class="btn">
        </form>
    <?php else: ?>
        <p class="empty">You have already applied or are a seller!</p>
    <?php endif; ?>
</section>

<script src="../assets/js/seller_script.js"></script>
</body>
</html>
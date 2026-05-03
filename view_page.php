<?php
include_once 'includes/config.php';
include_once 'core/functions.php';

$user_id = $_SESSION['user_id'] ?? null;
$msg = handle_add_to_cart($conn, $user_id);
if($msg) $message[] = $msg;

// Fetch product details with seller info
$pid = $_GET['pid'] ?? null;
if(!$pid) { header('location:home.php'); exit(); }

$product_query = mysqli_query($conn, "SELECT p.*, u.name as seller_name, u.email as seller_email, u.phone_number as seller_phone 
                                      FROM `products` p 
                                      LEFT JOIN `users` u ON p.seller_id = u.id 
                                      WHERE p.id = '$pid'") or die('query failed');

if(mysqli_num_rows($product_query) == 0) { header('location:home.php'); exit(); }
$product = mysqli_fetch_assoc($product_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: var(--jiji-bg);">
    
<?php include 'includes/header.php'; ?>

<div class="view-container">
    <!-- Main Product Content -->
    <main class="product-main">
        <div class="product-image-large">
            <img src="assets/uploads/<?php echo $product['image']; ?>" alt="">
        </div>
        <div class="product-info-section">
            <div class="product-title-row">
                <h1><?php echo $product['name']; ?></h1>
                <div class="product-price-large"><?php echo number_format($product['price']); ?> ETB</div>
            </div>
            <div class="product-meta">
                <span><i class="fas fa-clock"></i> Posted 2 days ago</span>
                <span><i class="fas fa-map-marker-alt"></i> Addis Ababa, Ethiopia</span>
                <span><i class="fas fa-eye"></i> 142 views</span>
            </div>
            <div class="product-description">
                <h3>Description</h3>
                <p><?php echo nl2br($product['details']); ?></p>
            </div>
        </div>
    </main>

    <!-- Seller & Safety Sidebar -->
    <aside class="product-sidebar">
        <div class="seller-box">
            <div class="seller-header">
                <div class="seller-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="seller-name"><?php echo $product['seller_name'] ?? 'Verified Seller'; ?></div>
                    <div class="seller-stats">Typically replies in 1 hour</div>
                </div>
            </div>
            
            <div id="contact-area">
                <button class="contact-btn" id="show-phone">
                    <i class="fas fa-phone-alt"></i> Show Contact
                </button>
            </div>
            
            <form action="" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                <button type="submit" name="add_to_cart" class="chat-btn">
                    <i class="fas fa-shopping-cart"></i> Add to Cart
                </button>
            </form>
        </div>

        <div class="safety-tips">
            <h4>Safety Tips</h4>
            <ul>
                <li><i class="fas fa-check-circle"></i> Don't pay in advance, even for delivery</li>
                <li><i class="fas fa-check-circle"></i> Meet with the seller at a safe public place</li>
                <li><i class="fas fa-check-circle"></i> Inspect the phone before you pay</li>
                <li><i class="fas fa-check-circle"></i> Pay only after collecting the phone</li>
            </ul>
        </div>
    </aside>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    document.getElementById('show-phone').onclick = function() {
        this.innerHTML = '<i class="fas fa-phone-alt"></i> <?php echo $product['seller_phone'] ?? "+251 912 345 678"; ?>';
        this.style.background = '#000';
    };
</script>
</body>
</html>
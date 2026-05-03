<?php

include_once 'includes/config.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if(isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;

    if(!isset($user_id)){
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
        $_SESSION['cart'][] = array(
            'pid' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_quantity,
            'image' => $product_image
        );
        $message[] = 'phone added to cart!';
    } else {
        $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_cart_numbers) > 0){
            $message[] = 'already added to cart!';
        }else{
            mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
            $message[] = 'phone added to cart!';
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
    <title>Shop - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: var(--jiji-bg);">
    
<?php include 'includes/header.php'; ?>

<section class="jiji-hero" style="padding: 2rem 7%;">
    <div class="container">
        <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">The Best Marketplace for Phones</h2>
        <form action="search_page.php" method="GET" class="hero-search-box">
            <div class="region-select">
                <i class="fas fa-map-marker-alt"></i>
                <span>Ethiopia</span>
            </div>
            <input type="text" name="search" placeholder="What phone are you looking for?" required>
            <button type="submit" class="hero-search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</section>

<div class="container" style="padding: 3rem 7%;">
    <div class="jiji-grid">
        <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY id DESC") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
                while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        <div class="jiji-card">
            <div class="jiji-card-img">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>">
                    <img src="assets/uploads/<?php echo $fetch_products['image']; ?>" alt="">
                </a>
                <div class="jiji-price"><?php echo number_format($fetch_products['price']); ?> ETB</div>
            </div>
            <div class="jiji-card-content">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="jiji-card-title">
                    <?php echo $fetch_products['name']; ?>
                </a>
                <div class="jiji-card-footer">
                    <span><i class="fas fa-map-marker-alt"></i> Addis Ababa</span>
                    <span>New</span>
                </div>
                <form action="" method="POST" style="margin-top: 1rem;">
                    <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                    <input type="submit" value="Add to Cart" name="add_to_cart" class="btn" style="width: 100%; margin: 0; background: var(--jiji-green); border-radius: 0.4rem; padding: 0.8rem; font-size: 1.4rem;">
                </form>
            </div>
        </div>
        <?php
            }
        }else{
            echo '<p class="empty" style="grid-column: 1/-1;">No phones available yet!</p>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


</body>
</html>
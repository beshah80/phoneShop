<?php
include 'config.php';
// session_start();

$user_id = $_SESSION['user_id'] ?? null;
$messages = [];

if (isset($_POST['add_to_wishlist'])) {
    // Remove the entire add_to_wishlist PHP block
}

if (isset($_POST['add_to_cart'])) {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $product_quantity = (int)$_POST['product_quantity'];

    if(!isset($user_id)){
        // Store cart items in session for non-logged-in users
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
        // Check if item already exists in cart
        $exists = false;
        foreach($_SESSION['cart'] as $item){
            if($item['name'] === $product_name){
                $exists = true;
                break;
            }
        }
        if(!$exists){
            $_SESSION['cart'][] = array(
                'pid' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'quantity' => $product_quantity,
                'image' => $product_image
            );
            $messages[] = 'Phone added to cart!';
        } else {
            $messages[] = 'Already added to cart!';
        }
    } else {
        $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

        if (mysqli_num_rows($check_cart) > 0) {
            $messages[] = 'Already added to cart!';
        } else {
            $check_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($check_wishlist) > 0) {
                mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
            }
            $product_image = strpos($product_image, '../upload_image/') === 0 ? $product_image : '../upload_image/' . $product_image;
            mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
            $messages[] = 'Phone added to cart!';
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
    <title>Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="heading">
    <h3>Phone Shop</h3>
    <p><a href="home.php">Home</a> / Shop</p>
</section>

<section class="products">
    <h1 class="title">All Phones</h1>
    <div class="box-container">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                $image_path = strpos($fetch_products['image'], 'uploaded_img/') === 0 ? $fetch_products['image'] : 'uploaded_img/' . $fetch_products['image'];
        ?>
        <form action="" method="POST" class="box">
            <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
            <div class="price"><?php echo number_format($fetch_products['price'], 2); ?> ETB</div>
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($fetch_products['name']); ?>" class="image">
            <div class="name"><?php echo htmlspecialchars($fetch_products['name']); ?></div>
            <input type="number" name="product_quantity" value="1" min="1" class="qty">
            <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $image_path; ?>">
            <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
        </form>
        <?php
            }
        } else {
            echo '<p class="empty">No phones added yet!</p>';
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
<?php

@include 'config.php';

// session_start();

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
            $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

            if(mysqli_num_rows($check_wishlist_numbers) > 0){
                mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
            }

            mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
            $message[] = 'phone added to cart!';
        }
    }
}

if(isset($_GET['delete'])){
    if(isset($user_id)) {
        $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
        mysqli_query($conn, "DELETE FROM `wishlist` WHERE id = '$delete_id' AND user_id = '$user_id'") or die('query failed');
    } else {
        $delete_index = (int)$_GET['delete'];
        if(isset($_SESSION['wishlist'][$delete_index])) {
            unset($_SESSION['wishlist'][$delete_index]);
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']); // Reindex array
        }
    }
    header('location:wishlist.php');
    exit();
}

if(isset($_GET['delete_all'])){
    if(isset($user_id)) {
        mysqli_query($conn, "DELETE FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
    } else {
        $_SESSION['wishlist'] = array();
    }
    header('location:wishlist.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wishlist</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>your wishlist</h3>
    <p> <a href="home.php">home</a> / wishlist </p>
</section>

<section class="wishlist">

    <h1 class="title">phones added</h1>

    <div class="box-container">

    <?php
        $grand_total = 0;
        
        if(isset($user_id)) {
            $select_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
            if(mysqli_num_rows($select_wishlist) > 0){
                while($fetch_wishlist = mysqli_fetch_assoc($select_wishlist)){
    ?>
    <form action="" method="POST" class="box">
        <a href="wishlist.php?delete=<?php echo $fetch_wishlist['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from wishlist?');"></a>
        <a href="view_page.php?pid=<?php echo $fetch_wishlist['pid']; ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?php echo $fetch_wishlist['image']; ?>" alt="" class="image">
        <div class="name"><?php echo htmlspecialchars($fetch_wishlist['name']); ?></div>
        <div class="price"><?php echo number_format($fetch_wishlist['price'], 2); ?> ETB</div>
        <input type="hidden" name="product_id" value="<?php echo $fetch_wishlist['pid']; ?>">
        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_wishlist['name']); ?>">
        <input type="hidden" name="product_price" value="<?php echo $fetch_wishlist['price']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $fetch_wishlist['image']; ?>">
        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
    </form>
    <?php
                    $grand_total += $fetch_wishlist['price'];
                }
            }
        } else {
            if(isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist'])) {
                foreach($_SESSION['wishlist'] as $index => $item) {
    ?>
    <form action="" method="POST" class="box">
        <a href="wishlist.php?delete=<?php echo $index; ?>" class="fas fa-times" onclick="return confirm('delete this from wishlist?');"></a>
        <a href="view_page.php?pid=<?php echo $item['pid']; ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?php echo $item['image']; ?>" alt="" class="image">
        <div class="name"><?php echo htmlspecialchars($item['name']); ?></div>
        <div class="price"><?php echo number_format($item['price'], 2); ?> ETB</div>
        <input type="hidden" name="product_id" value="<?php echo $item['pid']; ?>">
        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
        <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $item['image']; ?>">
        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
    </form>
    <?php
                    $grand_total += $item['price'];
                }
            }
        }
        
        if($grand_total == 0) {
            echo '<p class="empty">your wishlist is empty!</p>';
        }
    ?>
    </div>

    <div class="wishlist-total">
        <p>grand total : <span>$<?php echo number_format($grand_total, 2); ?>/-</span></p>
        <a href="shop.php" class="option-btn">continue shopping</a>
        <a href="wishlist.php?delete_all" class="delete-btn <?php echo ($grand_total > 0)?'':'disabled' ?>" onclick="return confirm('delete all from wishlist?');">delete all</a>
    </div>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
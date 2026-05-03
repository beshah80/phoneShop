<?php
// Core logic for PhoneSell E-commerce

function handle_add_to_cart($conn, $user_id) {
    if(isset($_POST['add_to_cart'])){
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
        $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
        $product_quantity = 1;

        if(!isset($user_id)){
            // Guest User (Session-based cart)
            if(!isset($_SESSION['cart'])){
                $_SESSION['cart'] = array();
            }
            
            // Check if already in session cart
            $found = false;
            foreach($_SESSION['cart'] as $item) {
                if($item['pid'] == $product_id) { $found = true; break; }
            }

            if($found){
                return "already added to cart!";
            } else {
                $_SESSION['cart'][] = array(
                    'pid' => $product_id,
                    'name' => $product_name,
                    'price' => $product_price,
                    'quantity' => $product_quantity,
                    'image' => $product_image
                );
                return "phone added to cart!";
            }
        } else {
            // Logged-in User (Database-based cart)
            $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

            if(mysqli_num_rows($check_cart_numbers) > 0){
                return "already added to cart!";
            }else{
                mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
                return "phone added to cart!";
            }
        }
    }
    return null;
}

function get_cart_count($conn, $user_id) {
    if ($user_id) {
        $stmt_cart = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM `cart` WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt_cart, "i", $user_id);
        mysqli_stmt_execute($stmt_cart);
        $cart_result = mysqli_stmt_get_result($stmt_cart);
        return mysqli_fetch_assoc($cart_result)['count'];
    } else {
        return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    }
}
?>

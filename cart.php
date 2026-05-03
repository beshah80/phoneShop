<?php
include 'includes/config.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$messages = [];

if (isset($_GET['delete'])) {
    if(isset($user_id)) {
        $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
        mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id' AND user_id = '$user_id'") or die('query failed');
    } else {
        $delete_index = (int)$_GET['delete'];
        if(isset($_SESSION['cart'][$delete_index])) {
            unset($_SESSION['cart'][$delete_index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); 
        }
    }
    header('Location: cart.php');
    exit();
}

if (isset($_GET['delete_all'])) {
    if(isset($user_id)) {
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    } else {
        $_SESSION['cart'] = array();
    }
    header('Location: cart.php');
    exit();
}

if (isset($_POST['update_quantity'])) {
    $cart_id = mysqli_real_escape_string($conn, $_POST['cart_id']);
    $cart_quantity = (int)$_POST['cart_quantity'];
    if ($cart_quantity > 0) {
        if(isset($user_id)) {
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id' AND user_id = '$user_id'") or die('query failed');
        } else {
            $cart_index = (int)$_POST['cart_id'];
            if(isset($_SESSION['cart'][$cart_index])) {
                $_SESSION['cart'][$cart_index]['quantity'] = $cart_quantity;
            }
        }
    }
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background: var(--jiji-bg);">

<?php include 'includes/header.php'; ?>

<section class="jiji-hero" style="padding: 2rem 7%;">
    <div class="container">
        <h2 style="font-size: 2.5rem;">Your Shopping Cart</h2>
    </div>
</section>

<div class="container" style="padding: 3rem 7%;">
    <div style="background: #fff; padding: 2rem; border-radius: 0.8rem; box-shadow: var(--box-shadow);">
        <div class="cart-table-wrapper" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee; text-align: left;">
                        <th style="padding: 1.5rem; font-size: 1.6rem;">Item</th>
                        <th style="padding: 1.5rem; font-size: 1.6rem;">Price</th>
                        <th style="padding: 1.5rem; font-size: 1.6rem;">Quantity</th>
                        <th style="padding: 1.5rem; font-size: 1.6rem;">Subtotal</th>
                        <th style="padding: 1.5rem; font-size: 1.6rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    if(isset($user_id)) {
                        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                        if (mysqli_num_rows($select_cart) > 0) {
                            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                                $grand_total += $sub_total;
                                $image_path = 'assets/uploads/' . $fetch_cart['image'];
                    ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1.5rem; display: flex; align-items: center; gap: 1.5rem;">
                            <img src="<?php echo htmlspecialchars($image_path); ?>" width="80" style="border-radius: 0.5rem;">
                            <span style="font-size: 1.5rem; font-weight: 600;"><?php echo htmlspecialchars($fetch_cart['name']); ?></span>
                        </td>
                        <td style="padding: 1.5rem; font-size: 1.5rem;"><?php echo number_format($fetch_cart['price']); ?> ETB</td>
                        <td style="padding: 1.5rem;">
                            <form action="" method="POST" style="display: flex; gap: 0.5rem;">
                                <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                <input type="number" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" min="1" style="width: 60px; padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.4rem;">
                                <button type="submit" name="update_quantity" class="btn" style="padding: 0.5rem 1rem; margin: 0; font-size: 1.2rem; background: var(--jiji-green);">Update</button>
                            </form>
                        </td>
                        <td style="padding: 1.5rem; font-size: 1.6rem; font-weight: 800; color: var(--jiji-green);"><?php echo number_format($sub_total); ?> ETB</td>
                        <td style="padding: 1.5rem;">
                            <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" style="color: var(--ruby-flame); font-size: 1.4rem;" onclick="return confirm('Remove this item?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                            }
                        }
                    }
                    if($grand_total == 0) echo '<tr><td colspan="5" style="text-align:center; padding: 3rem; font-size: 1.8rem; color: var(--text-gray);">Your cart is empty</td></tr>';
                    ?>
                </tbody>
            </table>
        </div>

        <?php if($grand_total > 0): ?>
        <div style="margin-top: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
            <a href="cart.php?delete_all" style="color: var(--ruby-flame); font-size: 1.4rem; font-weight: 600;" onclick="return confirm('Clear your cart?');">Clear All Items</a>
            <div style="text-align: right;">
                <h3 style="font-size: 2rem; margin-bottom: 1.5rem;">Grand Total: <span style="color: var(--jiji-green); font-size: 2.5rem;"><?php echo number_format($grand_total); ?> ETB</span></h3>
                <a href="checkout.php" class="btn" style="background: var(--jiji-orange); padding: 1.5rem 4rem; font-size: 1.8rem; border-radius: 0.8rem;">Proceed to Checkout</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <div style="margin-top: 2rem; text-align: center;">
        <a href="shop.php" style="font-size: 1.5rem; color: var(--jiji-green); font-weight: 600;"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/script.js"></script>
</body>
</html>
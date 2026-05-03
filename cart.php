<?php
include 'includes/config.php';
// session_start();

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
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
        }
    }
    $messages[] = 'Item deleted from cart!';
    header('Location: cart.php');
    exit();
}

if (isset($_GET['delete_all'])) {
    if(isset($user_id)) {
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    } else {
        $_SESSION['cart'] = array();
    }
    $messages[] = 'All items deleted from cart!';
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
        $messages[] = 'Cart quantity updated successfully!';
    } else {
        $messages[] = 'Invalid quantity!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="heading">
    <h3>Your Cart</h3>
    <p><a href="home.php">Home</a> / Cart</p>
</section>

<section class="cart">
    <h1 class="title">Phones Added</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
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
                            $image_path = strpos($fetch_cart['image'], 'assets/uploads/') === 0 ? $fetch_cart['image'] : 'assets/uploads/' . $fetch_cart['image'];
                ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($fetch_cart['name']); ?>" class="cart-image"></td>
                    <td><?php echo htmlspecialchars($fetch_cart['name']); ?></td>
                    <td><?php echo number_format($fetch_cart['price'], 2); ?> ETB</td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                            <input type="number" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" min="1" class="qty">
                            <button type="submit" name="update_quantity" class="option-btn">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($sub_total, 2); ?></td>
                    <td class="actions">
                        <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="btn"><i class="fas fa-eye"></i> View</a>
                        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('Delete this phone from cart?');" class="delete-btn"><i class="fas fa-times"></i> Delete</a>
                    </td>
                </tr>
                <?php
                        }
                    }
                } else {
                    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $index => $item) {
                            $sub_total = $item['price'] * $item['quantity'];
                            $grand_total += $sub_total;
                            $image_path = strpos($item['image'], 'assets/uploads/') === 0 ? $item['image'] : 'assets/uploads/' . $item['image'];
                ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-image"></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo number_format($item['price'], 2); ?> ETB</td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo $index; ?>">
                            <input type="number" name="cart_quantity" value="<?php echo $item['quantity']; ?>" min="1" class="qty">
                            <button type="submit" name="update_quantity" class="option-btn">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($sub_total, 2); ?></td>
                    <td class="actions">
                        <a href="view_page.php?pid=<?php echo $item['pid']; ?>" class="btn"><i class="fas fa-eye"></i> View</a>
                        <a href="cart.php?delete=<?php echo $index; ?>" onclick="return confirm('Delete this phone from cart?');" class="delete-btn"><i class="fas fa-times"></i> Delete</a>
                    </td>
                </tr>
                <?php
                        }
                    }
                }
                
                if($grand_total == 0) {
                    echo '<tr><td colspan="6" class="empty">Your cart is empty!</td></tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Grand Total</td>
                    <td colspan="2"><?php echo number_format($grand_total, 2); ?> ETB</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="cart-actions">
        <a href="shop.php" class="option-btn">Continue Shopping</a>
        <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all phones from cart?');">Delete All</a>
        <?php if($grand_total > 0): ?>
            <a href="<?php echo isset($user_id) ? 'checkout.php' : 'login.php'; ?>" class="btn">Proceed to Checkout</a>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="assets/js/script.js"></script>
</body>
</html>
<?php
include 'config.php';
include 'seller_header.php';
session_start();

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$messages = [];

if (!$user_id || $_SESSION['user_type'] !== 'seller') {
    header('location:login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $select_product = mysqli_query($conn, "SELECT image FROM products WHERE id = $delete_id AND seller_id = $user_id");
    $fetch_product = mysqli_fetch_assoc($select_product);

    if ($fetch_product) {
        $image_path = 'C:/xampp/htdocs/PhoneSell/uploaded_img/' . $fetch_product['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
        mysqli_query($conn, "DELETE FROM products WHERE id = $delete_id AND seller_id = $user_id");
        mysqli_query($conn, "DELETE FROM wishlist WHERE pid = $delete_id AND user_id = $user_id");
        mysqli_query($conn, "DELETE FROM cart WHERE pid = $delete_id AND user_id = $user_id");
        $messages[] = 'Product deleted!';
    }
}

$select_products = mysqli_query($conn, "SELECT * FROM products WHERE seller_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/seller_style.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="show-products">
    <h1 class="title">My Products</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Details</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($select_products) > 0): ?>
                    <?php while ($fetch_products = mysqli_fetch_assoc($select_products)): ?>
                        <tr>
                            <td><img src="uploaded_img/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="" class="cart-image"></td>
                            <td><?php echo htmlspecialchars($fetch_products['name']); ?></td>
                            <td>$<?php echo number_format($fetch_products['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($fetch_products['details']); ?></td>
                            <td><?php echo htmlspecialchars($fetch_products['category']); ?></td>
                            <td class="actions">
                                <a href="seller_update_product.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn"><i class="fas fa-edit"></i> Update</a>
                                <a href="?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="empty">No products added!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
        <a href="seller_add_product.php" class="btn">Add New Product</a>
    </div>
</section>

<script src="js/seller_script.js"></script>
</body>
</html>
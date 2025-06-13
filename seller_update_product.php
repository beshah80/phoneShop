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

$fetch_product = null;
if (isset($_GET['update'])) {
    $update_id = (int)$_GET['update'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $update_id AND seller_id = $user_id");
    $fetch_product = mysqli_fetch_assoc($result);

    if (!$fetch_product) {
        header('location:seller_products.php');
        exit();
    }
}

if (isset($_POST['update_product'])) {
    $csrf_token = $_POST['csrf_token'];
    if ($csrf_token !== $_SESSION['csrf_token']) {
        $messages[] = 'Invalid CSRF token!';
    } else {
        $update_id = (int)$_POST['update_id'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = (float)$_POST['price'];
        $details = mysqli_real_escape_string($conn, $_POST['details']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);

        $query = "UPDATE products SET name = '$name', price = $price, details = '$details', category = '$category' 
                  WHERE id = $update_id AND seller_id = $user_id";
        mysqli_query($conn, $query);

        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image']['name'];
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = 'C:/xampp/htdocs/PhoneSell/uploaded_img/';
            $old_image = $_POST['update_p_image'];

            if (!is_dir($image_folder)) {
                mkdir($image_folder, 0755, true);
            }
            if (!is_writable($image_folder)) {
                $messages[] = 'Upload directory is not writable!';
            } else {
                if ($image_size > 2000000) {
                    $messages[] = 'Image size is too large (max 2MB)!';
                } else {
                    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
                    $image_name = uniqid() . '.' . $image_ext;
                    $image_path = $image_folder . $image_name;
                    if (move_uploaded_file($image_tmp_name, $image_path)) {
                        $old_image_path = 'C:/xampp/htdocs/PhoneSell/uploaded_img/' . $old_image;
                        if (file_exists($old_image_path)) {
                            unlink($old_image_path);
                        }
                        mysqli_query($conn, "UPDATE products SET image = '$image_name' WHERE id = $update_id AND seller_id = $user_id");
                        $messages[] = 'Image updated successfully!';
                    } else {
                        $messages[] = 'Failed to upload image!';
                    }
                }
            }
        }
        $messages[] = 'Product updated successfully!';
    }
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/seller_style.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="update-product">
    <h1 class="title">Update Product</h1>
    <?php if ($fetch_product): ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <h3>Update Product</h3>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="update_id" value="<?php echo $fetch_product['id']; ?>">
            <input type="hidden" name="update_p_image" value="<?php echo $fetch_product['image']; ?>">
            <img src="uploaded_img/<?php echo htmlspecialchars($fetch_product['image']); ?>" alt="" class="cart-image">
            <input type="text" name="name" class="box" placeholder="Phone model" required maxlength="100" value="<?php echo htmlspecialchars($fetch_product['name']); ?>">
            <input type="number" name="price" step="0.01" min="0" class="box" placeholder="Phone price" required value="<?php echo htmlspecialchars($fetch_product['price']); ?>">
            <textarea name="details" class="box" placeholder="Phone specifications" required maxlength="500"><?php echo htmlspecialchars($fetch_product['details']); ?></textarea>
            <input type="text" name="category" class="box" placeholder="Phone category" required maxlength="50" value="<?php echo htmlspecialchars($fetch_product['category']); ?>">
            <input type="file" name="image" accept="image/jpg,image/jpeg,image/png" class="box">
            <input type="submit" value="Update Product" name="update_product" class="btn">
            <a href="seller_products.php" class="option-btn">Go Back</a>
        </form>
    <?php else: ?>
        <p class="empty">No product selected!</p>
    <?php endif; ?>
</section>

<script src="js/seller_script.js"></script>
</body>
</html>
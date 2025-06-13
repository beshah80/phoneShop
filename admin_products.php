<?php
include 'config.php';
// session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('Location: login.php');
    exit();
}

$messages = [];

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    
    // Check details length
    if (strlen($details) > 200) {
        $messages[] = ['type' => 'error', 'text' => 'Product details must not exceed 200 characters!'];
    } else {
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../upload_image/' . basename($image);

        // Check if upload_image directory exists and is writable
        $upload_dir = '../upload_image/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        if (!is_writable($upload_dir)) {
            $messages[] = ['type' => 'error', 'text' => 'Upload directory is not writable!'];
        } else {
            $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die(mysqli_error($conn));

            if (mysqli_num_rows($select_product_name) > 0) {
                $messages[] = ['type' => 'error', 'text' => 'Phone model already exists!'];
            } else {
                $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, details, price, image) VALUES('$name', '$details', '$price', '$image')") or die(mysqli_error($conn));

                if ($insert_product) {
                    if ($image_size > 2000000) {
                        $messages[] = ['type' => 'error', 'text' => 'Image size is too large (max 2MB)!'];
                        // Remove product if image fails
                        mysqli_query($conn, "DELETE FROM `products` WHERE name = '$name'") or die(mysqli_error($conn));
                    } else {
                        if (move_uploaded_file($image_tmp_name, $image_folder)) {
                            $messages[] = ['type' => 'success', 'text' => 'Phone added successfully!'];
                        } else {
                            $messages[] = ['type' => 'error', 'text' => 'Failed to upload image!'];
                            // Remove product if image fails
                            mysqli_query($conn, "DELETE FROM `products` WHERE name = '$name'") or die(mysqli_error($conn));
                        }
                    }
                } else {
                    $messages[] = ['type' => 'error', 'text' => 'Failed to add phone!'];
                }
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die(mysqli_error($conn));
    if (mysqli_num_rows($select_delete_image) > 0) {
        $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
        $image_path = '../upload_image/' . $fetch_delete_image['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die(mysqli_error($conn));
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die(mysqli_error($conn));
    mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die(mysqli_error($conn));
    $messages[] = ['type' => 'success', 'text' => 'Phone deleted successfully!'];
    header('Location: admin_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Phones - PhoneSell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            animation: slideIn 0.5s ease-out;
        }
        .notification.success {
            background-color: #4CAF50;
        }
        .notification.error {
            background-color: #f44336;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<?php if (!empty($messages)): ?>
    <?php foreach ($messages as $message): ?>
        <div class="notification <?php echo $message['type']; ?>">
            <?php echo $message['text']; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<section class="add-products">
    <form action="" method="POST" enctype="multipart/form-data" class="modern-admin-form">
        <h3>Add New Phone</h3>
        <input type="text" class="box" required placeholder="Enter phone model" name="name">
        <input type="number" min="0" step="0.01" class="box" required placeholder="Enter phone price" name="price">
        <textarea name="details" class="box" required placeholder="Enter phone specifications (max 200 characters)" cols="30" rows="10" maxlength="200"></textarea>
        <div class="char-count">Characters remaining: <span id="charCount">200</span></div>
        <input type="file" accept="image/jpg,image/jpeg,image/png" required class="box" name="image">
        <input type="submit" value="Add Phone" name="add_product" class="btn">
    </form>
</section>

<section class="show-products">
    <h1 class="title">Phone List</h1>
    <div class="table-container">
        <table class="modern-admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die(mysqli_error($conn));
                if (mysqli_num_rows($select_products) > 0) {
                    while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                ?>
                <tr>
                    <td><img src="../upload_image/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="<?php echo htmlspecialchars($fetch_products['name']); ?>" class="product-thumb"></td>
                    <td><?php echo htmlspecialchars($fetch_products['name']); ?></td>
                    <td><span class="product-price-badge"><?php echo number_format($fetch_products['price'], 2); ?> ETB</span></td>
                    <td><?php echo htmlspecialchars($fetch_products['details']); ?></td>
                    <td class="actions">
                        <a href="admin_update_product.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn"><i class="fas fa-edit"></i> Update</a>
                        <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" onclick="return confirm('Delete this phone?');" class="delete-btn"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="empty">No phones added yet!</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script src="js/admin_script.js"></script>
<script>
    // Auto-hide notifications after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        });

        // Character counter for product details
        const textarea = document.querySelector('textarea[name="details"]');
        const charCount = document.getElementById('charCount');
        
        textarea.addEventListener('input', function() {
            const remaining = 200 - this.value.length;
            charCount.textContent = remaining;
            
            if (remaining < 50) {
                charCount.style.color = 'red';
            } else {
                charCount.style.color = 'inherit';
            }
        });
    });
</script>
</body>
</html>
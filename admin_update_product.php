<?php
include 'config.php';
// session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('Location: login.php');
    exit();
}

$messages = [];

if (isset($_POST['update_product'])) {
    $update_p_id = mysqli_real_escape_string($conn, $_POST['update_p_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);

    // Check details length
    if (strlen($details) > 200) {
        $messages[] = ['type' => 'error', 'text' => 'Product details must not exceed 200 characters!'];
    } else {
        mysqli_query($conn, "UPDATE `products` SET name = '$name', details = '$details', price = '$price' WHERE id = '$update_p_id'") or die(mysqli_error($conn));

        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../upload_image/' . basename($image);
        $old_image = $_POST['update_p_image'];

        if (!empty($image)) {
            $upload_dir = '../upload_image/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            if (!is_writable($upload_dir)) {
                $messages[] = ['type' => 'error', 'text' => 'Upload directory is not writable!'];
            } else {
                if ($image_size > 2000000) {
                    $messages[] = ['type' => 'error', 'text' => 'Image size is too large (max 2MB)!'];
                } else {
                    if (move_uploaded_file($image_tmp_name, $image_folder)) {
                        mysqli_query($conn, "UPDATE `products` SET image = '$image' WHERE id = '$update_p_id'") or die(mysqli_error($conn));
                        $old_image_path = '../upload_image/' . $old_image;
                        if (file_exists($old_image_path)) {
                            unlink($old_image_path);
                        }
                        $messages[] = ['type' => 'success', 'text' => 'Image updated successfully!'];
                    } else {
                        $messages[] = ['type' => 'error', 'text' => 'Failed to upload image!'];
                    }
                }
            }
        }

        $messages[] = ['type' => 'success', 'text' => 'Phone updated successfully!'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Phone - PhoneSell</title>
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

<section class="update-product">
    <?php
    $update_id = mysqli_real_escape_string($conn, $_GET['update']);
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die(mysqli_error($conn));
    if (mysqli_num_rows($select_products) > 0) {
        $fetch_products = mysqli_fetch_assoc($select_products);
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <img src="../upload_image/<?php echo htmlspecialchars($fetch_products['image']); ?>" alt="<?php echo htmlspecialchars($fetch_products['name']); ?>" class="cart-image">
        <input type="hidden" value="<?php echo $fetch_products['id']; ?>" name="update_p_id">
        <input type="hidden" value="<?php echo $fetch_products['image']; ?>" name="update_p_image">
        <input type="text" class="box" value="<?php echo htmlspecialchars($fetch_products['name']); ?>" required placeholder="Update phone model" name="name">
        <input type="number" min="0" step="0.01" class="box" value="<?php echo $fetch_products['price']; ?>" required placeholder="Update phone price" name="price">
        <textarea name="details" class="box" required placeholder="Update phone specifications (max 200 characters)" cols="30" rows="10" maxlength="200"><?php echo htmlspecialchars($fetch_products['details']); ?></textarea>
        <div class="char-count">Characters remaining: <span id="charCount"><?php echo 200 - strlen($fetch_products['details']); ?></span></div>
        <input type="file" accept="image/jpg,image/jpeg,image/png" class="box" name="image">
        <input type="submit" value="Update Phone" name="update_product" class="btn">
        <a href="admin_products.php" class="option-btn">Go Back</a>
    </form>
    <?php
    } else {
        echo '<p class="empty">No phone selected for update!</p>';
    }
    ?>
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
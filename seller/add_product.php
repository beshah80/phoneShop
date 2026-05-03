<?php
include '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if(!isset($user_id)) { header('location:../login.php'); exit(); }

$message = [];

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../assets/uploads/';

    if ($image) {
        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $image_ext;
        if (move_uploaded_file($image_tmp_name, $image_folder . $image_name)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO `products` (name, details, price, image, category, seller_id, status) VALUES (?, ?, ?, ?, ?, ?, 'available')");
            mysqli_stmt_bind_param($stmt, "ssdssi", $name, $details, $price, $image_name, $category, $user_id);
            mysqli_stmt_execute($stmt);
            $message[] = 'Ad posted successfully! Redirecting...';
            echo "<script>setTimeout(() => { window.location.href = 'dashboard.php'; }, 2000);</script>";
        } else {
            $message[] = 'Failed to upload image!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Ad - PhoneSell Ethiopia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #f4f4f4; }
        .post-container { max-width: 800px; margin: 4rem auto; padding: 0 2rem; }
        .post-form-card { background: #fff; padding: 4rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .post-form-card h2 { font-size: 2.5rem; margin-bottom: 3rem; color: #333; font-weight: 800; border-bottom: 2px solid #f4f4f4; padding-bottom: 1rem; }
        .form-group { margin-bottom: 2.5rem; }
        .form-group label { display: block; margin-bottom: 1rem; font-weight: 700; color: #555; font-size: 1.5rem; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 1.5rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1.6rem; background: #fafafa; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #3db83a; background: #fff; box-shadow: 0 0 10px rgba(61, 184, 58, 0.1); }
        .submit-btn { width: 100%; background: #3db83a; color: #fff; padding: 1.8rem; border-radius: 8px; font-size: 1.8rem; font-weight: 800; cursor: pointer; transition: 0.3s; border: none; }
        .submit-btn:hover { background: #34a832; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(61, 184, 58, 0.3); }
        .image-preview-box { border: 2px dashed #ddd; padding: 3rem; text-align: center; border-radius: 8px; cursor: pointer; transition: 0.3s; }
        .image-preview-box:hover { border-color: #3db83a; background: #f0fdf4; }
        .image-preview-box i { font-size: 3rem; color: #aaa; margin-bottom: 1rem; }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="post-container">
    <div class="post-form-card">
        <h2>Post New Phone Ad</h2>
        
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="message" style="margin-bottom: 2rem; background: #f0fdf4; color: #3db83a; padding: 1.5rem; border-radius: 8px; font-weight: 600;">'.$msg.'</div>';
            }
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Phone Model Name *</label>
                <input type="text" name="name" placeholder="e.g. iPhone 14 Pro Max" required>
            </div>

            <div class="form-group">
                <label>Category *</label>
                <select name="category" required>
                    <option value="">Select Brand</option>
                    <option value="apple">Apple iPhone</option>
                    <option value="samsung">Samsung Galaxy</option>
                    <option value="google">Google Pixel</option>
                    <option value="accessories">Accessories</option>
                </select>
            </div>

            <div class="form-group">
                <label>Price (ETB) *</label>
                <input type="number" name="price" placeholder="Enter your asking price" required>
            </div>

            <div class="form-group">
                <label>Description & Features *</label>
                <textarea name="details" rows="5" placeholder="Condition, Battery Health, Storage, RAM..." required></textarea>
            </div>

            <div class="form-group">
                <label>Phone Photo *</label>
                <div class="image-preview-box" onclick="document.getElementById('imgInput').click()">
                    <i class="fas fa-camera"></i>
                    <p style="font-size: 1.4rem; color: #777;">Click here to upload photo</p>
                    <input type="file" name="image" id="imgInput" accept="image/*" required style="display:none;">
                </div>
            </div>

            <button type="submit" name="add_product" class="submit-btn">POST AD NOW</button>
        </form>
    </div>
</div>

</body>
</html>
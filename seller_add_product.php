<?php
include 'seller_header.php';
include 'config.php';

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$messages = [];

if (!$user_id) {
    header('location:login.php');
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT status FROM `seller_applications` WHERE user_id = ? AND status = 'approved'");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) == 0) {
    header('location:home.php');
    exit();
}
mysqli_stmt_close($stmt);

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'C:/xampp/htdocs/PhoneSell/uploaded_img/';

    if (!is_dir($image_folder)) {
        mkdir($image_folder, 0755, true);
    }
    if (!is_writable($image_folder)) {
        $messages[] = 'Upload directory is not writable!';
    } else {
        $stmt_check = mysqli_prepare($conn, "SELECT name FROM `products` WHERE name = ? AND seller_id = ?");
        mysqli_stmt_bind_param($stmt_check, "si", $name, $user_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        if (mysqli_num_rows($result_check) > 0) {
            $messages[] = 'Product name already exists!';
        } else {
            if ($image_size > 2000000) {
                $messages[] = 'Image size is too large (max 2MB)!';
            } else {
                $image_ext = pathinfo($image, PATHINFO_EXTENSION);
                $image_name = uniqid() . '.' . $image_ext;
                $image_path = $image_folder . $image_name;
                if (move_uploaded_file($image_tmp_name, $image_path)) {
                    $stmt_insert = mysqli_prepare($conn, "INSERT INTO `products` (name, details, price, image, category, seller_id) VALUES (?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt_insert, "ssdssi", $name, $details, $price, $image_name, $category, $user_id);
                    mysqli_stmt_execute($stmt_insert);
                    mysqli_stmt_close($stmt_insert);
                    $messages[] = 'Product added successfully!';
                } else {
                    $messages[] = 'Failed to upload image!';
                }
            }
        }
        mysqli_stmt_close($stmt_check);
    }
}
?>
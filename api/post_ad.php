<?php
include '../includes/config.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Please login first!']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float)$_POST['price'];
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../assets/uploads/';
        
        $image_ext = pathinfo($image, PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $image_ext;
        
        if (move_uploaded_file($image_tmp_name, $image_folder . $image_name)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO `products` (name, details, price, image, category, seller_id, status) VALUES (?, ?, ?, ?, ?, ?, 'available')");
            mysqli_stmt_bind_param($stmt, "ssdssi", $name, $details, $price, $image_name, $category, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'Ad posted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error!']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image is required!']);
    }
}
?>

<?php
// API endpoint for posting new ads via modal
header('Content-Type: application/json');
include_once '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if(!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Please login to post ads']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $details = mysqli_real_escape_string($conn, $_POST['details'] ?? '');
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    
    // Validate required fields
    if(empty($name) || $price <= 0 || empty($details) || empty($category)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
        exit;
    }
    
    // Handle image upload
    if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Please upload a phone image']);
        exit;
    }
    
    $image = $_FILES['image'];
    $image_size = $image['size'];
    $image_tmp_name = $image['tmp_name'];
    $image_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    
    // Validate image
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    if(!in_array($image_ext, $allowed_ext)) {
        echo json_encode(['success' => false, 'message' => 'Invalid image format. Use JPG, PNG, or GIF']);
        exit;
    }
    
    if($image_size > 2000000) { // 2MB limit
        echo json_encode(['success' => false, 'message' => 'Image size too large. Max 2MB']);
        exit;
    }
    
    // Create unique filename
    $image_name = uniqid('phone_') . '.' . $image_ext;
    $image_folder = '../assets/uploads/';
    
    // Ensure upload directory exists
    if(!is_dir($image_folder)) {
        mkdir($image_folder, 0755, true);
    }
    
    // Upload image
    $image_path = $image_folder . $image_name;
    if(!move_uploaded_file($image_tmp_name, $image_path)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
        exit;
    }
    
    // Insert into database
    $stmt = mysqli_prepare($conn, "INSERT INTO `products` (name, details, price, image, category, seller_id, status) VALUES (?, ?, ?, ?, ?, ?, 'available')");
    mysqli_stmt_bind_param($stmt, "ssdssi", $name, $details, $price, $image_name, $category, $user_id);
    
    if(mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Phone ad posted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
    
    mysqli_stmt_close($stmt);
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>

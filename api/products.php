<?php
// Professional REST API for Products
header('Content-Type: application/json');
include_once '../includes/config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        $pid = $_GET['id'] ?? null;
        if($pid) {
            $query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$pid'");
            $data = mysqli_fetch_assoc($query);
        } else {
            $query = mysqli_query($conn, "SELECT * FROM `products` ORDER BY id DESC");
            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
        }
        echo json_encode($data);
        break;

    case 'POST':
        // Handle adding products via API (for Seller Dashboard)
        // This will be used by the Jiji-style 'Post Ad' form
        break;
}
?>

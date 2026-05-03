<?php
// Professional REST API for Cart
header('Content-Type: application/json');
include_once '../includes/config.php';
include_once '../core/functions.php';

$user_id = $_SESSION['user_id'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add to cart via API
    $response = handle_add_to_cart($conn, $user_id);
    echo json_encode(['message' => $response, 'cart_count' => get_cart_count($conn, $user_id)]);
} else {
    echo json_encode(['cart_count' => get_cart_count($conn, $user_id)]);
}
?>

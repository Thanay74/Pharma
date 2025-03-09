<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'redirect' => 'login.html',
        'message' => 'Please login first'
    ]);
    exit();
}

require_once 'db_connection.php';

try {
    // Get JSON data from request
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!isset($data['cart']) || empty($data['cart'])) {
        throw new Exception('Cart is empty');
    }

    $user_id = $_SESSION['user_id'];
    
    // Start transaction
    $conn->begin_transaction();

    // Store cart data in session for checkout
    $_SESSION['checkout_cart'] = $data['cart'];
    $_SESSION['cart_total'] = array_reduce($data['cart'], function($total, $item) {
        return $total + ($item['price'] * $item['quantity']);
    }, 0);

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Cart processed successfully'
    ]);

} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

if (isset($conn)) {
    $conn->close();
}
?> 
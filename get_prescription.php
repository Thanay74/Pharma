<?php
session_start();
header('Content-Type: application/json');

// Verify admin authentication here

if (!isset($_GET['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Order ID required']);
    exit;
}

require_once 'db_connect.php';

try {
    $query = "SELECT prescription FROM orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $_GET['order_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'type' => 'image/jpeg', // Adjust based on your storage format
            'prescription' => base64_encode($row['prescription'])
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Prescription not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 
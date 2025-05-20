<?php
// Config file with database connection and utility functions
require_once '../config/config.php';

// Set response headers for JSON
header('Content-Type: application/json');

// Check if method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get UID from POST data
$uid = isset($_POST['uid']) ? trim($_POST['uid']) : '';

// Validate UID
if (empty($uid)) {
    echo json_encode([
        'success' => false,
        'message' => 'UID is required'
    ]);
    exit;
}

try {
    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM products WHERE uid = :uid LIMIT 1");
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();
    
    // Fetch the product
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if product exists
    if ($product) {
        // Product found
        echo json_encode([
            'success' => true,
            'product' => [
                'product_name' => $product['product_name'],
                'uid' => $product['uid'],
                'description' => $product['description']
            ]
        ]);
    } else {
        // Product not found
        echo json_encode([
            'success' => false,
            'message' => 'Invalid or Fake Product'
        ]);
    }
} catch (PDOException $e) {
    // Log error (in a production environment)
    error_log('Database error: ' . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while verifying the product'
    ]);
}
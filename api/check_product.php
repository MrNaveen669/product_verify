<?php
session_start();

require_once '../config/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

$uid = isset($_POST['uid']) ? trim($_POST['uid']) : '';

if (empty($uid)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a UID'
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM products WHERE uid = :uid LIMIT 1");
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $alreadyVerifiedInSession = isset($_SESSION['verified_uids'][$uid]) && $_SESSION['verified_uids'][$uid] === true;
        $alreadyUsedInDB = $product['is_used'] == 1;

        if ($alreadyUsedInDB) {
            // UID already marked as used
            $pdo->commit();
            echo json_encode([
                'success' => true,
                'isUsed' => true,
                'message' => 'UID is already used.',
                'product' => [
                    'product_name' => $product['product_name'],
                    'uid' => $product['uid'],
                    'description' => $product['description'],
                    'verification_status' => 'Already Used'
                ]
            ]);
        } elseif (!$alreadyVerifiedInSession) {
            // First-time verification
            $updateStmt = $pdo->prepare("UPDATE products SET is_used = 1 WHERE uid = :uid");
            $updateStmt->bindParam(':uid', $uid);
            $updateStmt->execute();

            $_SESSION['verified_uids'][$uid] = true;

            $pdo->commit();
            echo json_encode([
                'success' => true,
                'isUsed' => false,
                'message' => 'Product verified successfully!',
                'product' => [
                    'product_name' => $product['product_name'],
                    'uid' => $product['uid'],
                    'description' => $product['description'],
                    'verification_status' => 'First-time Verification'
                ]
            ]);
        } else {
            // Already verified in session (but not marked as used in DB, rare case)
            $pdo->commit();
            echo json_encode([
                'success' => true,
                'isUsed' => false,
                'message' => 'Product already verified in this session.',
                'product' => [
                    'product_name' => $product['product_name'],
                    'uid' => $product['uid'],
                    'description' => $product['description'],
                    'verification_status' => 'Session Verified'
                ]
            ]);
        }
    } else {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Invalid UID.'
        ]);
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Database error: ' . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while verifying the product. Please try again.'
    ]);
}

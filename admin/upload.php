<?php
session_start();
require_once '../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["jsonFile"])) {
    $file = $_FILES["jsonFile"];
    
    // Check if there was an upload error
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $error = "Upload failed with error code " . $file["error"];
    } 
    // Verify file type
    else if (pathinfo($file["name"], PATHINFO_EXTENSION) !== "json") {
        $error = "Only .json files are allowed";
    }
    else {
        // Read and decode JSON file
        $jsonContent = file_get_contents($file["tmp_name"]);
        $products = json_decode($jsonContent, true);
        
        if ($products === null) {
            $error = "Invalid JSON format";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (uid, product_name, description) 
                                     VALUES (:uid, :product_name, :description)
                                     ON DUPLICATE KEY UPDATE 
                                     product_name = :product_name,
                                     description = :description");
                
                $successCount = 0;
                $skipCount = 0;
                
                foreach ($products as $product) {
                    if (!isset($product['uid']) || !isset($product['product_name']) || !isset($product['description'])) {
                        $error = "Invalid product data format. Each product must have uid, product_name, and description.";
                        break;
                    }
                    
                    try {
                        $stmt->execute([
                            ':uid' => $product['uid'],
                            ':product_name' => $product['product_name'],
                            ':description' => $product['description']
                        ]);
                        if ($stmt->rowCount() > 0) {
                            $successCount++;
                        } else {
                            $skipCount++;
                        }
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry
                            $skipCount++;
                        } else {
                            throw $e;
                        }
                    }
                }
                
                if (!$error) {
                    $message = "Upload successful! Added $successCount new products. Skipped $skipCount existing products.";
                }
                
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Products - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Admin Header -->
        <?php include 'includes/header.php'; ?>
        
        <div class="admin-content">
            <!-- Admin Sidebar -->
            <?php include 'includes/sidebar.php'; ?>
            
            <!-- Main Content -->
            <main class="main-content">
                <h2>Upload Products JSON File</h2>

                <?php if ($message): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="upload-section">
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="jsonFile">Select JSON file:</label>
                            <input type="file" name="jsonFile" id="jsonFile" accept=".json" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                    </form>
                </div>

                <div class="instructions">
                    <h3>JSON Format Example:</h3>
                    <pre>
[
    {
        
        "product_name": "Premium Lens",
        "uid": "UID0012364",
        "description": "Anti-glare contact lens"
    },
    {
        
        "product_name": "Blue Lens",
        "uid": "UID0021574",
        "description": "Blue tone soft lens"
    }
]
                    </pre>
                </div>
            </main>
        </div>
    </div>
    
    <script src="js/admin.js"></script>
</body>
</html>

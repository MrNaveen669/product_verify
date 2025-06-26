<?php
session_start();
require_once '../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo APP_NAME; ?></title>
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
                <div class="dashboard-welcome">
                    <h2>Welcome to the Admin Dashboard</h2>
                    <p>Use the sidebar to navigate to different sections.</p>
                </div>
                
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <?php
                        // Count total products
                        $stmt = $pdo->query("SELECT COUNT(*) FROM products");
                        $total_products = $stmt->fetchColumn();
                        ?>
                        <h3>Total Products</h3>
                        <p class="stat-number"><?php echo $total_products; ?></p>
                    </div>
                </div>
                
                <div class="dashboard-actions">
                    <a href="upload.php" class="btn btn-primary">Upload Products</a>
                    <a href="products.php" class="btn btn-secondary">View Products</a>
                </div>
            </main>
        </div>
    </div>
    
    <script src="js/admin.js"></script>
</body>
</html>
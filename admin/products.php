<?php
session_start();
require_once '../config/config.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Get filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Base query
$query = "SELECT * FROM products WHERE 1=1";
$params = [];

// Search filter
if (!empty($search)) {
    $query .= " AND (uid LIKE :search OR product_name LIKE :search)";
    $params[':search'] = "%$search%";
}

// Status filter with proper binding
if ($status === 'used') {
    $query .= " AND is_used = :status";
    $params[':status'] = 1;
} elseif ($status === 'unused') {
    $query .= " AND is_used = :status";
    $params[':status'] = 0;
}

// Order by latest
$query .= " ORDER BY id DESC";

// Execute query
try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - View Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/header.php'; ?>

        <div class="admin-content">
            <?php include 'includes/sidebar.php'; ?>

            <main class="main-content">
                <div class="content-header">
                    <h2>Product List</h2>
                    <div class="actions">
                        <a href="upload.php" class="btn btn-primary">Upload New Products</a>
                    </div>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="filter-section">
                    <form action="products.php" method="get" class="filter-form">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by UID or Product Name"
                                value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="form-group">
                            <select name="status" class="form-control">
                                <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>All Products</option>
                                <option value="unused" <?php echo $status === 'unused' ? 'selected' : ''; ?>>Unused Products</option>
                                <option value="used" <?php echo $status === 'used' ? 'selected' : ''; ?>>Used Products</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary">Filter</button>
                        <a href="products.php" class="btn btn-outline">Clear</a>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>UID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr><td colspan="4" class="text-center">No products found</td></tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['uid']); ?></td>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $product['is_used'] ? 'used' : 'unused'; ?>">
                                                <?php echo $product['is_used'] ? 'Used' : 'Unused'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="js/admin.js"></script>
</body>
</html>

<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['jsonFile'])) {
    $file = $_FILES['jsonFile']['tmp_name'];
    $jsonData = file_get_contents($file);
    $products = json_decode($jsonData, true);

    if ($products && is_array($products)) {
        foreach ($products as $product) {
            $stmt = $conn->prepare("INSERT INTO products (product_id, amount, stock_sold, stock_warehouse, stock_instore, status, customer_id, timestamp, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "siiiissss",
                $product['product_id'],
                $product['amount'],
                $product['stock_sold'],
                $product['stock_warehouse'],
                $product['stock_instore'],
                $product['status'],
                $product['customer_id'],
                $product['timestamp'],
                $product['description']
            );

            $stmt->execute();
        }

        echo "<p>✅ Products uploaded successfully!</p>";
    } else {
        echo "<p>❌ Invalid JSON file format.</p>";
    }
}
?>

<h2>Upload Product JSON</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="jsonFile" accept=".json" required />
    <button type="submit">Upload</button>
</form>

<h2>Uploaded Products</h2>
<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th><th>Amount</th><th>Sold</th><th>Warehouse</th><th>In-Store</th><th>Status</th><th>Customer</th><th>Timestamp</th><th>Description</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM products");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['product_id']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['stock_sold']}</td>
                    <td>{$row['stock_warehouse']}</td>
                    <td>{$row['stock_instore']}</td>
                    <td>{$row['status']}</td>
                    <td>{$row['customer_id']}</td>
                    <td>{$row['timestamp']}</td>
                    <td>{$row['description']}</td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

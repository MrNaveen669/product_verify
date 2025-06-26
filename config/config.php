<?php
// Admin login credentials
define('ADMIN_USERNAME', 'admin');
// Password hash for "admin"
define('ADMIN_PASSWORD', '$2a$12$DiENmuVD6lE1Zp2xPjKwWeUYQPSIoKaQQXJ4dLPfQkgwmEdexASWi');

// Application constants
define('APP_NAME', 'Herbaras');

// Database connection
$host = "localhost";
$user = "root";
$password = "";         // Update with your DB password
$dbname = "product_auth";  // DB name

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

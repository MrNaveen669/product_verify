<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Change in production
define('DB_PASS', ''); // Change in production
define('DB_NAME', 'prod_auth2');

// App configuration
define('APP_NAME', 'Product Authentication System');
define('BASE_URL', 'http://localhost/product-auth'); // Change in production

// Error reporting - turn off in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect to database
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER, 
        DB_PASS, 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

/**
 * Redirect to specified URL
 * 
 * @param string $url URL to redirect to
 * @return void
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Check if user is logged in
 * 
 * @return bool Whether user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Require login to access page
 * 
 * @return void
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'You must be logged in to access that page';
        redirect(BASE_URL . '/admin/login.php');
    }
}

/**
 * Sanitize input data
 * 
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
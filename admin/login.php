<?php
session_start();
require_once '../config/config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$username = $password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate username
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username)) {
        $errors[] = 'Please enter a username.';
    }

    if (empty($password)) {
        $errors[] = 'Please enter your password.';
    }    // Check if there are no errors
    if (empty($errors)) {
        // Debug line - remove in production
        error_log("Login attempt - Username: $username");
        
        if ($username === ADMIN_USERNAME) {
            if (password_verify($password, ADMIN_PASSWORD)) {
                // Start new session
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['username'] = $username;
                
                // Debug line - remove in production
                error_log("Login successful - redirecting to dashboard");
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = 'Invalid password.';
            }
        } else {
            $errors[] = 'Invalid username.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo defined('APP_NAME') ? APP_NAME : 'Dashboard'; ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="container">
        <div class="admin-login-box">
            <h1>Admin Login</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control"
                           value="<?php echo htmlspecialchars($username); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
            </form>

            <div class="login-footer">
                <a href="../index.html" class="back-link">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>

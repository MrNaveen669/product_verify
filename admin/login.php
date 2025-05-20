<?php
// Include config file
require_once '../config/config.php';

// Initialize variables
$username = $password = '';
$errors = [];

// Process login form when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate username
    if (empty(trim($_POST['username']))) {
        $errors[] = 'Please enter a username.';
    } else {
        $username = trim($_POST['username']);
    }
    
    // Validate password
    if (empty(trim($_POST['password']))) {
        $errors[] = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }
    
    // Check if there are no errors
    if (empty($errors)) {
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM admin_users WHERE username = :username";
        
        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Check if username exists
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row['id'];
                        $username = $row['username'];
                        $hashed_password = $row['password'];
                        
                        // Verify password
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION['user_id'] = $id;
                            $_SESSION['username'] = $username;
                            
                            // Redirect to dashboard
                            redirect('dashboard.php');
                        } else {
                            $errors[] = 'The password you entered is incorrect.';
                        }
                    }
                } else {
                    $errors[] = 'No account found with that username.';
                }
            } else {
                $errors[] = 'Oops! Something went wrong. Please try again later.';
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
    <title>Admin Login - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="container">
        <div class="admin-login-container">
            <div class="admin-login-box">
                <h1>Admin Login</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo $username; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                
                <div class="login-footer">
                    <a href="../index.html" class="back-link">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
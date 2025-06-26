<div class="admin-header">
    <div class="header-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1><?php echo APP_NAME; ?> - Admin Panel</h1>
    </div>
    <div class="admin-user">
        <span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

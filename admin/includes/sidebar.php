<div class="admin-sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="upload.php" <?php echo basename($_SERVER['PHP_SELF']) == 'upload.php' ? 'class="active"' : ''; ?>>
                <i class="fas fa-upload"></i>
                <span>Upload Products</span>
            </a>
        </li>
        <li>
            <a href="products.php" <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'class="active"' : ''; ?>>
                <i class="fas fa-box"></i>
                <span>View Products</span>
            </a>
        </li>
    </ul>
</div>
<div class="overlay"></div>

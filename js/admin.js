document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    const mainContent = document.querySelector('.main-content');
    const overlay = document.querySelector('.overlay');
    let isMobile = window.innerWidth <= 768;

    // Function to update mobile state
    function updateMobileState() {
        isMobile = window.innerWidth <= 768;
        if (!isMobile) {
            overlay.classList.remove('active');
            sidebar.classList.remove('active');
        }
    }

    // Toggle sidebar
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            if (isMobile) {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                sidebarToggle.classList.toggle('active');
            } else {
                sidebar.classList.toggle('minimized');
                mainContent.classList.toggle('expanded');
                sidebarToggle.classList.toggle('active');
            }
        });

        // Close sidebar when clicking overlay
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            sidebarToggle.classList.remove('active');
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            updateMobileState();
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (isMobile && 
                sidebar.classList.contains('active') && 
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                sidebarToggle.classList.remove('active');
            }
        });
    }

    // Add icons to sidebar menu if missing
    const menuLinks = document.querySelectorAll('.sidebar-menu a');
    menuLinks.forEach(link => {
        if (!link.querySelector('i')) {
            const icon = document.createElement('i');
            icon.className = 'fas fa-circle';
            link.insertBefore(icon, link.firstChild);
        }
    });

    // Table responsiveness
    const tables = document.querySelectorAll('.admin-table');
    tables.forEach(table => {
        const headerCells = table.querySelectorAll('th');
        const dataCells = table.querySelectorAll('td');
        
        // Add data labels for mobile view
        dataCells.forEach((cell, index) => {
            cell.setAttribute('data-label', headerCells[index % headerCells.length].textContent);
        });
    });
});

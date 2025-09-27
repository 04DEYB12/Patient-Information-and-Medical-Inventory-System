<aside class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo">
                <img src="../Images/GranbyLogo.png" alt="Logo" width="50" height="50">
            </div>
            <div class="brand">
                <h1>GCST</h1>
                <span>Patient Information &<br>Medical Inventory System</span>
            </div>
        </div>
    </div>  
    
    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-group">
            <h2 class="nav-group-title">Navigation</h2>
            <ul class="nav-items">
                <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'Dashboard.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/Dashboard.php"><i class='bx bx-pulse'></i><span>Dashboard</span></a>
                </li>
                <li style="display: <?php echo $role == 'Administrator' ? 'block' : 'none'; ?>;" class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'AccountManagement.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/AccountManagement.php"><i class='bx bx-user'></i><span>User Management</span></a>
                </li>
                <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'Patients.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/Patients.php"><i class='bx bx-user-plus'></i><span>Patients / Students</span></a>
                </li>
                <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'Inventory.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/Inventory.php"><i class='bx bx-package'></i><span>Inventory</span></a>
                </li>
                <li style="display: <?php echo $role == 'Administrator' ? 'block' : 'none'; ?>;" class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'AuditLogs.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/AuditLogs.php"><i class='bx bx-history'></i><span>Audit Logs</span></a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Sidebar User Profile -->
    <div class="sidebar-footer">
        <div class="profile-menu" id="profileMenu">
            <div class="profile-info">
                <div class="avatar">
                    <span><?php echo $firstname[0] . $lastname[0]; ?></span>
                </div>
                <div class="user-info">
                    <h3><?php echo $firstname . " " . $lastname; ?></h3>
                    <span class="role"><?php echo $role; ?></span>
                </div>
                <i class='bx bx-chevron-up dropdown-icon'></i>
            </div>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="avatar">
                        <span><?php echo $firstname[0] . $lastname[0]; ?></span>
                    </div>
                    <div class="user-info">
                        <h3><?php echo $firstname . " " . $lastname; ?></h3>
                        <span class="role"><?php echo $role; ?></span>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <ul class="dropdown-menu">
                    <li><a href="../components/myProfile.php"><i class='bx bx-user'></i> Profile</a></li>
                    <li><a href="#settings"><i class='bx bx-cog'></i> Settings</a></li>
                    <div class="dropdown-divider"></div>
                    <li><a href="../Landing Repository/logout.php" class="logout"><i class='bx bx-log-out'></i> Log out</a></li>
                </ul>
            </div>
        </div>
    </div>
</aside>

<script>
    // Profile dropdown toggle
    profileMenu.addEventListener('click', function(e) {
        e.stopPropagation();
        profileDropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!profileMenu.contains(e.target)) {
            profileDropdown.classList.remove('show');
        }
    });
</script>
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
                <!-- quick scan -->
                <div class="relative mb-3">
                    <i class='bx bx-qr-scan absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                    <input id="scannerInput" type="text" placeholder="Quick Scan!" autofocus onkeyup="quickScan(event)"
                    class="pl-10 pr-10 py-2 border rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'Dashboard.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/Dashboard.php"><i class='bx bx-pulse'></i><span>Dashboard</span></a>
                </li>
                <li style="display: <?php echo $role == 'Administrator' || $role == 'Super Administrator' ? 'block' : 'none'; ?>;" class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'AccountManagement.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/AccountManagement.php"><i class='bx bx-user'></i><span>User Management</span></a>
                </li>
                <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'Patients.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/Patients.php"><i class='bx bx-user-plus'></i><span>Patients / Students</span></a>
                </li>
                <li class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'Inventory.php') !== false ? 'active' : ''; ?>">
                    <a href="../PIAIMS Repository/Inventory.php"><i class='bx bx-package'></i><span>Inventory</span></a>
                </li>
                <li style="display: <?php echo $role == 'Administrator' || $role == 'Super Administrator' ? 'block' : 'none'; ?>;" class="nav-item <?php echo strpos($_SERVER['PHP_SELF'], 'AuditLogs.php') !== false ? 'active' : ''; ?>">
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
                    <li style="display: <?php echo $role == 'Super Administrator' ? 'none' : 'block'; ?>;"><a href="../components/myProfile.php"><i class='bx bx-user' style='color:#d57a25'></i> Profile</a></li>
                    <!-- <li><a href="#settings"><i class='bx bx-cog' style='color:#d57a25'></i> Settings</a></li> -->
                    <li><a href="../components/UserGuide.php"><i class='bx  bx-book-open' style='color:#d57a25'></i> User Guide</a></li>
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
    
    // Open modal
    function openModal(modal) {
        document.getElementById(modal).style.display = 'block';
    }
    
    // Close modal
    function closeModal(modal) {
        const modalElement = document.getElementById(modal);
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    }
    
    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modalIds = [
            "checkInModal"
        ];

        modalIds.forEach(id => {
            const modal = document.getElementById(id);
            if (modal && event.target === modal) {
                modal.style.display = "none";
            }
        });
    };
    
    // 🔎 Quick Scan
    let scanTimer;
    let lastScanned = '';
    
    function quickScan(event) {
        // Clear any existing timer
        clearTimeout(scanTimer);
        
        // Set a new timer (300ms after last input)
        scanTimer = setTimeout(() => {
            const scannedValue = event.target.value.trim();
            
            // Only process if we have a value and it's different from the last scan
            if (scannedValue && scannedValue !== lastScanned) {
                lastScanned = scannedValue;
                openCheckInModal(scannedValue);
                
                // Clear the input after processing
                event.target.value = '';
            }
        }, 300);
    }
    
    function openCheckInModal(studentId) {
        // Open the modal
        openModal('checkInModal');
        
        // Show loading state
        console.log('Loading student data for ID:', studentId);
        document.getElementById('CheckInStudentName').textContent = 'Loading...';
        document.getElementById('CheckInStudentID').textContent = 'ID: ' + studentId;
        
        fetch(`../Functions/patientFunctions.php?action=getStudent&id=${studentId}`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.success) {
                    const student = data.student;
                    
                    // Update header
                    const checkInFirstInitial = student.FirstName ? student.FirstName[0] : '';
                    const checkInLastInitial = student.LastName ? student.LastName[0] : '';
                    document.getElementById('CheckInAvatar').textContent = checkInFirstInitial + checkInLastInitial;
                    document.getElementById('CheckInStudentName').textContent = `${student.FirstName || ''} ${student.LastName || ''}`.trim();
                    // Update the hidden input value
                    document.getElementById('CheckInStudentIdHidden').value = studentId;
                    document.getElementById('CheckInStaffIdHidden').value = "<?php echo $_SESSION['User_ID']; ?>";
                } else {
                    alert('Failed to load student data: ' + (data.message || 'Unknown error'));
                    closeModal('checkInModal');
                }
            })
            .catch(error => {
                console.error('Error loading student data:', error);
                alert('Failed to load student data. Please try again.');
                closeModal('checkInModal');
            });
    }
</script>
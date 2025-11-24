<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>window.location.href = '../components/Error401.php';</script>";
    exit();
}

$user_id = $_SESSION['User_ID'];
require_once '../Functions/Queries.php';

if ($_SESSION['role'] != 'Administrator') {
    echo "<script>window.location.href = '../components/Error404.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIAIMS | User Management</title>
    <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="../Functions/scripts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="../Stylesheet/Design.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #4338ca;
            --secondary-dark: #139419ff;
            --secondary-light: #88ff8eff;
            --success: #10b981;
            --danger: #ef4444;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-card: #ffffff;
            --bg-hover: #f9fafb;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .user-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }
        
        .user-card {
            background: var(--bg-card);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            will-change: transform, box-shadow;
        }
        
        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
        }
        
        .user-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .user-card-header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
            color: white;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }
        .user-card-header.staff {
            background: linear-gradient(135deg, var(--secondary-dark), var(--secondary-light));
            color: white;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .user-card-header.staff::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            pointer-events: none;
        }
        
        .user-card-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            pointer-events: none;
        }
        
        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .user-info {
            flex: 1;
            min-width: 0;
        }
        
        .user-name {
            font-weight: 600;
            margin: 0;
            font-size: 1.125rem;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-role {
            font-size: 0.8125rem;
            opacity: 0.9;
            margin: 0.25rem 0 0;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        
        .user-card-body {
            padding: 1.5rem;
            background: var(--bg-card);
        }
        
        .user-detail {
            display: flex;
            align-items: center;
            margin-bottom: 0.875rem;
            font-size: 0.9375rem;
            color: var(--text-primary);
            line-height: 1.5;
        }
        
        .user-detail:last-child {
            margin-bottom: 0;
        }
        
        .user-detail i {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            color: var(--primary-color);
            background: rgba(79, 70, 229, 0.1);
            border-radius: 6px;
            flex-shrink: 0;
            font-size: 1rem;
        }
        
        .user-actions {
            display: flex;
            border-top: 1px solid var(--border-color);
            padding: 0.75rem 1.25rem;
            gap: 0.75rem;
            background: var(--bg-hover);
        }
        
        .btn-action {
            flex: 1;
            padding: 0.625rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .btn-action::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%, -50%);
            transform-origin: 50% 50%;
        }
        
        .btn-action:active::after {
            animation: ripple 0.6s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }
        
        .btn-edit {
            background: #eef2ff;
            color: var(--primary-color);
        }
        
        .btn-edit:hover {
            background: #e0e7ff;
        }
        
        .btn-delete {
            background: #fef2f2;
            color: var(--danger);
        }
        
        .btn-delete:hover {
            background: #fee2e2;
        }
        
        .btn-active {
            background-color: #4caf50;
            color: white;
        }
        .btn-active:hover {
            background-color: #388e3c;
        }
        .btn-inactive {
            background-color: #f57c00;
            color: white;
        }
        .btn-inactive:hover {
            background-color: #e65100;
        }
        
        /* Status indicators */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .status-active {
            background: #ecfdf5;
            color: #047857;
        }
        
        .status-inactive {
            background: #fef2f2;
            color: #b91c1c;
        }
        
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .user-cards-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
            
            .user-card-header {
                padding: 1rem;
            }
            
            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body style="font-family: 'Poppins', sans-serif;">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include '../components/sidebar.php'; ?>
        
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class='bx bx-menu'></i>
                </button>
                <h1 id="pageTitle" style="color: #002e2d;">USER MANAGEMENT</h1>
            </header>
            
            <!-- Contents -->

            <div class="content-container">
                <section class="content-section active" id="userManagementSection">
                    <div class="infotype bg-white shadow-md rounded-2xl p-6 flex flex-col gap-3 hover:shadow-lg transition w-full mb-2">
                    <!-- Icon + Title -->
                    <div class="flex items-center gap-2 text-green-600">
                        <i class="bx bx-info-circle text-3xl"></i>
                        <h2 class="text-lg font-semibold">Guide</h2>
                    </div>

                    <!-- One-line Instruction (Step by Step) -->
                    <div class=" text-sm text-gray-700 font-medium leading-relaxed flex">
                        <!-- Short Description -->
                        <p class="text-gray-600 text-sm">
                            Manage account credentials and access settings properly. 
                        
                        <br>Click <span class="font-semibold">Manage this Account</span> → Toggle <span class="font-semibold"> Active or Inactive</span> → 
                        </span> Update <span class="font-semibold ">User Email, Status and Role</span> → </span> Reset <span class="font-semibold">User Password</span> → </span> Log out for security
                        </p>
                    </div>
                </div>



                    <div class="card bg-white">
                        <div class="card-header bg-white">
                            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <h2 class="flex items-center gap-2">Accounts 
                                    <span class="font-normal text-sm flex gap-2 items-center">
                                        (
                                        <p class="admin">Admin</p>
                                        <p class="dot1 p-[5px] rounded-full bg-indigo-800"></p>
                                    </span>
                                    <span class="font-normal text-sm flex gap-2 items-center">
                                        <p class="staff">Staff</p>
                                        <p class="dot2 p-[5px] rounded-full bg-green-600" ></p>
                                        )
                                    </span>
                                
                                </h2>
                                <div class="flex items-center gap-4"> 
                                    <!-- Search and Filter Container -->
                                    <div class="flex items-center gap-4">
                                        <!-- Search Bar -->
                                        <div class="search-container relative">
                                            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500'></i>
                                            <input 
                                                type="text" 
                                                id="searchInput2" 
                                                placeholder="Search by ID or Name..." 
                                                onkeyup="filterUserCards()"  autofocus
                                                class="pl-8 pr-8 py-2 border border-gray-300 rounded-md w-[400px] focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                            >
                                            <i 
                                                id="clearSearch2" 
                                                class='bx bx-x absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 cursor-pointer hidden' 
                                                onclick="clearSearch()"
                                            ></i>
                                        </div>
                                        
                                        <!-- Role Filter Dropdown -->
                                        <div class="filter-container relative">
                                            <select 
                                                id="roleFilter" 
                                                onchange="filterUserCards()" 
                                                class="pl-3 pr-8 py-2 border border-gray-300 rounded-md bg-white text-gray-600 cursor-pointer appearance-none min-w-[150px] focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                            >
                                                <option value="">All Roles</option>
                                                <option value="Administrator">Administrator</option>
                                                <option value="Staff">Staff</option>
                                            </select>
                                            <i class='bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Add New User Button -->
                                    <button 
                                        id="addUserBtn" 
                                        class="btn btn-primary flex items-center gap-2 px-4 py-[10px] rounded-md bg-blue-600 text-white hover:bg-blue-700 transition shadow-md hover:shadow-lg" 
                                        onclick="openModal('addUserModal'); return false;"
                                    >
                                        <i class='bx bx-plus'></i>
                                        <span>Add New User</span>
                                    </button>
                                </div>

                            </div>
                        </div>
                        
                        <div class="card-content bg-white">
                                <div id="no-results-message" style="display: none; text-align: center; padding: 2rem; color: #6b7280;">
                                    No matching User found
                                </div>
                                <div id="user-cards-container" class="user-cards-grid">
                                    <!-- User cards will be dynamically inserted here -->
                                </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>

<script>

    // Open modal
    function openModal(modal) {
        document.getElementById(modal).style.display = 'block';
    }
    
    // Close modal
    function closeModal(modal) {
        document.getElementById(modal).style.display = 'none';
    }
    
    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modalIds = [
            "ViewUserModal"
        ];

        modalIds.forEach(id => {
            const modal = document.getElementById(id);
            if (modal && event.target === modal) {
                modal.style.display = "none";
            }
        });
    };

    // Fetch users from the clinicpersonnel table
    function loadUserCards() {
        const container = document.getElementById('user-cards-container');
        container.innerHTML = '<div class="loading">Loading users...</div>';

        const loadingIndicator = container.querySelector('.loading');
    
        fetch('../Functions/UserFunctions.php?action=get_users', {
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(async response => {
            const responseText = await response.text();
            let data;
            
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON response:', e);
                console.log('Raw response:', responseText);
                throw new Error('Invalid JSON response from server');
            }
            
            if (!response.ok) {
                throw new Error(data.error || `HTTP error! status: ${response.status}`);
            }
            
            if (!Array.isArray(data)) {
                console.error('Expected array but got:', typeof data, data);
                throw new Error('Invalid data format: expected array');
            }
            
            container.innerHTML = '';
            
            if (data.length > 0) {
                container.innerHTML = data.map(user => createUserCard(user)).join('');
            } else {
                container.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                        No users found
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error in loadUserCards:', error);
            container.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #ef4444;">
                    <h3>Error Loading Users</h3>
                    <p>${error.message || 'An unknown error occurred'}</p>
                    <button onclick="loadUserCards()" class="retry-btn">
                        <i class="fas fa-sync-alt"></i> Retry
                    </button>
                </div>`;
            
            // Add retry button styles
            const style = document.createElement('style');
            style.textContent = `
                .retry-btn {
                    margin-top: 1rem;
                    padding: 0.5rem 1rem;
                    background-color: #4f46e5;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                }
                .retry-btn:hover {
                    background-color: #4338ca;
                }
                .loading {
                    grid-column: 1 / -1;
                    text-align: center;
                    padding: 2rem;
                    color: #6b7280;
                }`;
            document.head.appendChild(style);
        });
    }
    
    // Displaying user cards
    function createUserCard(user) {
        // Get initials from the name
        const nameParts = user.name.trim().split(/\s+/);
        const firstName = nameParts[0] || '';
        const lastName = nameParts.length > 1 ? nameParts[nameParts.length - 1] : '';
        const initials = (firstName[0] + (lastName[0] || '')).toUpperCase();
        
        // Format the name properly (remove extra spaces)
        const formattedName = user.name.replace(/\s+/g, ' ').trim();
        
        // Determine status class and icon
        const isActive = user.status === 'Active';
        const statusClass = isActive ? 'status-active' : 'status-inactive';
        const statusIcon = isActive ? 'bx-check-circle' : 'bx-x-circle';
        
        return `
            <div class="user-card">
                <div class="user-card-header ${user.role?.toLowerCase() === 'staff' ? 'staff' : ''}">
                    <div class="user-avatar" title="${formattedName}">${initials}</div>
                    <div class="user-info">
                        <h3 class="user-name" title="${formattedName}">${formattedName}</h3>
                        <p class="user-role">${user.role}</p>
                    </div>
                </div>

                <div class="user-card-body bg-white p-4 flex flex-col gap-4 border border-gray-200 hover:shadow-lg transition">
                
                <!-- ID -->
                <div class="user-detail flex items-center justify-between text-gray-700">
                    <div class="group-detail flex items-center gap-2">
                        <i class='bx bx-id-card text-indigo-600 text-xl'></i>
                        <span class="font-medium">Identification Number:</span>
                    </div>
                    <strong class="text-gray-900">${user.id || 'N/A'}</strong>
                </div>

                <!-- Phone -->
                <div class="user-detail flex items-center justify-between text-gray-700">
                    <div class="group-detail flex items-center gap-2">
                        <i class='bx bx-phone text-green-600 text-xl'></i>
                        <span class="font-medium">Phone Number:</span>
                    </div>
                    <strong class="text-gray-900">${user.phone || 'No contact number'}</strong>
                </div>

                <!-- Status -->
                <div class="user-detail flex items-center justify-between text-gray-700">
                    <div class="group-detail flex items-center gap-2">
                        <i class='bx ${statusIcon} text-gray-500 text-xl'></i>
                        <span class="font-medium">Status:</span>
                    </div>
                    <span class="status-badge ${statusClass} px-3 py-1 rounded-full text-xs font-semibold">
                        ${user.status || 'Inactive'}
                    </span>
                </div>

            </div>

                <div class="user-actions">
                    <button class="btn-action btn-edit" onclick="ManageUserProfile(${JSON.stringify(user).replace(/"/g, '&quot;')})" title="View user profile">
                        <i class='bx bx-user'></i> Manage this account
                    </button>
                </div>
            </div>
        `;
    }
    
    function ManageUserProfile(user) {
        try {
            const userData = typeof user === 'string' ? JSON.parse(user.replace(/&quot;/g, '"')) : user;
            
            // Format the join date
            const joinDate = userData.hiredate ? new Date(userData.hiredate).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }) : 'N/A';
            
            // Get initials from the name
            const nameParts = userData.name.trim().split(/\s+/);
            const firstName = nameParts[0] || '';
            const lastName = nameParts.length > 1 ? nameParts[nameParts.length - 1] : '';
            const initials = (firstName[0] + (lastName[0] || '')).toUpperCase();
            
            // Update modal content
            document.getElementById('userAvatar').textContent = initials;
            document.getElementById('userName').textContent = userData.name || 'N/A';
            document.getElementById('userStatus').textContent = userData.status || 'Inactive';
            document.getElementById('userStatus').className = `status-badge ${userData.status === 'Active' ? 'status-active' : 'status-inactive'}`;
            document.getElementById('userId').textContent = userData.id || 'N/A';
            document.getElementById('userEmail').textContent = userData.email || 'N/A';
            document.getElementById('userContact').textContent = userData.phone || 'N/A';
            document.getElementById('userJoinDate').textContent = joinDate;
            document.getElementById('userRole').textContent = userData.role || 'Staff';
            // // Set role and initialize toggle button
            // const userRole = userData.role || 'Staff';
            
            // // Initialize role toggle button
            // const roleToggleBtn = document.getElementById('roleToggleBtn');
            // const roleText = document.getElementById('roleText');
            // if (roleToggleBtn && roleText) {
            //     roleToggleBtn.setAttribute('data-role', userRole);
            //     roleText.textContent = userRole;
                
            //     // Set appropriate icon based on role
            //     const roleIcon = roleToggleBtn.querySelector('i');
            //     if (userRole === 'Admin') {
            //         roleIcon.className = 'bx bx-shield';
            //         roleToggleBtn.title = 'Change to Staff';
            //     } else {
            //         roleIcon.className = 'bx bx-user';
            //         roleToggleBtn.title = 'Change to Admin';
            //     }
            // }
            
            // Set the initial state of the Role toggle button 
            const roleToggleBtn = document.getElementById('roleToggleBtn');
            const currentRole = userData.role || 'Staff';
            roleToggleBtn.setAttribute('data-role', currentRole);
            
            // Update button text
            const roleText = roleToggleBtn.querySelector('#roleText');
            
            if(currentRole === 'Administrator') {
                roleText.textContent = 'Administrator';
            } else {
                roleText.textContent = 'Staff';
            }
            
            
            
            // Set the initial state of the status toggle button
            const statusToggleBtn = document.getElementById('userStatustry');
            const currentStatus = userData.status || 'Inactive';
            statusToggleBtn.setAttribute('data-status', currentStatus);
            
            // Update button text and icon based on status
            const statusText = statusToggleBtn.querySelector('#statusText');
            const statusIcon = statusToggleBtn.querySelector('i');
            
            if (currentStatus === 'Active') {
                statusText.textContent = 'Active';
                statusIcon.className = 'bx bx-check-circle';
            } else {
                statusText.textContent = 'Inactive';
                statusIcon.className = 'bx bx-power-off';
            }
            
            document.getElementById('hidden_userId').value = userData.id;
            
            // Show the modal
            openModal('ViewUserModal');
        } catch (error) {
            console.error('Error displaying user profile:', error);
            alert('Error loading user profile. Please try again.');
        }
    }
    
    // Call this function when the page loads
    document.addEventListener('DOMContentLoaded', loadUserCards);
    
    // Filter function for the search input and role filter
    function filterUserCards() {
        const searchTerm = document.getElementById('searchInput2').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value.toLowerCase();
        const cards = document.querySelectorAll('.user-card');
        const noResultsMsg = document.getElementById('no-results-message');
        let hasVisibleCards = false;
        
        cards.forEach(card => {
            const name = card.querySelector('.user-name').textContent.toLowerCase();
            const id = card.querySelector('.user-detail span').textContent.toLowerCase();
            // Get the role from the user-role paragraph text content
            const role = card.querySelector('.user-role').textContent.trim().toLowerCase();
            
            // Check search term match (if any)
            const searchMatch = !searchTerm || name.includes(searchTerm) || id.includes(searchTerm);
            
            // Check role filter match (if any)
            const roleMatch = !roleFilter || role === roleFilter;
            
            // Show card only if both search and role match
            const isVisible = searchMatch && roleMatch;
            
            card.style.display = isVisible ? 'block' : 'none';
            if (isVisible) hasVisibleCards = true;
        });
        
        // Show/hide no results message
        if (noResultsMsg) {
            noResultsMsg.style.display = hasVisibleCards ? 'none' : 'block';
        }
    }
    
    // Clear Search
    function clearSearch() {
        const searchInput = document.getElementById('searchInput2');
        searchInput.value = '';
        searchInput.focus();
        filterUserCards();
        document.getElementById('clearSearch2').style.display = 'none';
    }
    
    // Toggle clear button visibility based on search input
    document.getElementById('searchInput2').addEventListener('input', function() {
        document.getElementById('clearSearch2').style.display = 
            this.value ? 'block' : 'none';
    });
</script>

<!-- Add User Modal -->
<?php include '../Modals/AddUser_modal.php'; ?>

<!-- View User Profile Modal -->
<?php include '../Modals/viewUserProfile_modal.php'; ?>

<!-- Check-in Modal -->
<?php include '../Modals/Checkin_modal.php'; ?>

</html>

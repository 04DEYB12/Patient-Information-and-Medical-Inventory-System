<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>window.location.href = '../components/Error401.php';</script>";
    exit();
}

$user_id = $_SESSION['User_ID'];
$role = $_SESSION['role'];

require_once '../Functions/Queries.php';

if ($role != 'Administrator' && $role != 'Super Administrator') {
    echo "<script>window.location.href = '../components/Error403.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIAIMS | Audit Logs</title>
    <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="../Functions/scripts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="../Stylesheet/Design.css">
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
                <h1 id="pageTitle" style="color: #002e2d;">AUDIT LOGS HISTORY</h1>
            </header>
            
            <!-- Contents -->
            <div class="content-container">
                <section class="content-section active" id="userManagementSection">
                    <div class="infotype bg-white shadow-md rounded-2xl p-6 flex flex-col gap-3 hover:shadow-lg transition w-full mb-2">
                    <!-- Icon + Title -->
                    <div class="flex items-center gap-2 text-blue-600">
                        <i class="bx bx-history text-3xl"></i>
                        <h2 class="text-lg font-semibold">Audit Logs Overview</h2>
                    </div>

                    <!-- Audit Logs Description -->
                    <div class="text-sm text-gray-700 font-medium leading-relaxed text-gray-600">
                        <p>The Audit Logs provide a comprehensive record of all user activities and system events within PIAMIS. This tool helps administrators track and monitor:</p>
                        <ul class="list-disc pl-5 mt-2 space-y-1">
                            <li>Account creation and modifications</li>
                        </ul>
                        <p class="mt-2">
                            Use the search and filter options to quickly find specific log entries.
                        </p>
                    </div>
                </div>
                    <div class="card bg-white">
                        <div class="card-header bg-white">
                            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <h2 class="flex items-center gap-2">Audit Trail</h2>
                                <div class="flex items-center gap-4"> 
                                    <!-- Search and Filter Container -->
                                    <div class="flex items-center gap-4">
                                        <!-- Search Bar -->
                                        <div class="search-container relative">
                                            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500'></i>
                                            <input 
                                                type="text" 
                                                id="searchInput" 
                                                placeholder="Search by ID or Name..."  autofocus
                                                class="pl-8 pr-8 py-2 border border-gray-300 rounded-md w-[400px] focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                            >
                                            <i 
                                                id="clearSearch" 
                                                class='bx bx-x absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 cursor-pointer hidden' 
                                                onclick="clearSearch()"
                                            ></i>
                                        </div>
                                        
                                        <!-- Role Filter Dropdown -->
                                        <div class="filter-container relative">
                                            <select 
                                                id="roleFilter"
                                                class="pl-3 pr-8 py-2 border border-gray-300 rounded-md bg-white text-gray-600 cursor-pointer appearance-none min-w-[150px] focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                            >
                                                <option value="">All Roles</option>
                                                <option value="Super Administrator">Super Administrator</option>
                                                <option value="Administrator">Administrator</option>
                                                <option value="Staff">Staff</option>
                                            </select>
                                            <i class='bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                                        </div>
                                        
                                        <!-- Module/Table Filter Dropdown -->
                                        <div class="filter-container relative">
                                            <select 
                                                id="moduleFilter"
                                                class="pl-3 pr-8 py-2 border border-gray-300 rounded-md bg-white text-gray-600 cursor-pointer appearance-none min-w-[150px] focus:ring-2 focus:ring-yellow-400 focus:outline-none"
                                            >
                                                <option value="">All Modules</option>
                                                <option value="clinicpersonnel">Clinic Personnel</option>
                                                <option value="student">Student/Patient</option>
                                                <option value="medicine">Medicine</option>
                                                <option value="studentcheckins">Student Check-ins</option>
                                            </select>
                                            <i class='bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none'></i>
                                        </div>
                                        
                                        <!-- Export to Excel -->
                                        <button onclick="Export_toExcel()" class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                            <i class='bx bx-export text-lg'></i>
                                            <span>Export to Excel</span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div class="card-content bg-white">
                                <div id="no-results-message" style="display: none; text-align: center; padding: 2rem; color: #6b7280;">
                                    No matching User found
                                </div>
                                <div class="overflow-x-auto rounded-lg border border-gray-200 mt-4">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Timestamp
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    User (Performed by)
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Action Type
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Module/Table
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Record ID
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Details
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="auditLogsBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Audit log rows will be dynamically inserted here -->
                                            <tr class="hover:bg-gray-50">
                                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Loading audit logs...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

<script src="https://cdn.sheetjs.com/xlsx-0.18.12/package/dist/xlsx.full.min.js"></script>
    
<script>
// Function to format timestamp
function formatTimestamp(timestamp) {
    if (!timestamp) return 'N/A';
    const date = new Date(timestamp);
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    });
}

// Function to determine badge color based on action type
function getActionBadgeClass(actionType) {
    const actionClasses = {
        'create': 'bg-green-100 text-green-800',
        'update': 'bg-blue-100 text-blue-800',
        'delete': 'bg-red-100 text-red-800',
        'login': 'bg-purple-100 text-purple-800',
        'logout': 'bg-gray-100 text-gray-800',
        'default': 'bg-yellow-100 text-yellow-800'
    };
    
    const action = actionType.toLowerCase();
    return actionClasses[action] || actionClasses['default'];
}

// Function to render audit logs in the table
function renderAuditLogs(logs) {
    const tbody = document.getElementById('auditLogsBody');
    
    if (!logs || logs.length === 0) {
        tbody.innerHTML = `
            <tr class="hover:bg-gray-50">
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                    No audit logs found
                </td>
            </tr>`;
        return;
    }
    
    tbody.innerHTML = logs.map(log => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${formatTimestamp(log.timestamp)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-indigo-600 font-medium">${log.user_initials || 'U'}</span>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${log.user_name || 'System'}</div>
                        <div class="text-sm text-gray-500">${log.user_role || 'System'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getActionBadgeClass(log.action_type)}">
                    ${log.action_type || 'Unknown'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${log.module || 'N/A'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${log.record_id || 'N/A'}
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
                <div class="max-w-xs truncate" title="${log.details || ''}">
                    ${log.details || 'No details available'}
                </div>
            </td>
        </tr>
    `).join('');
}

// Function to fetch audit logs
async function fetchAuditLogs() {
    const tbody = document.getElementById('auditLogsBody');
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const moduleFilter = document.getElementById('moduleFilter');
    
    // Build query parameters
    const params = new URLSearchParams();
    params.append('action', 'get_logs');
    
    if (searchInput?.value) {
        params.append('search', searchInput.value);
    }
    if (roleFilter?.value) {
        params.append('role', roleFilter.value);
    }
    if (moduleFilter?.value) {
        params.append('module', moduleFilter.value);
    }
    
    try {
        // Show loading state
        tbody.innerHTML = `
            <tr class="hover:bg-gray-50">
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                    <div class="flex justify-center items-center space-x-2">
                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div>
                        <span>Loading audit logs...</span>
                    </div>
                </td>
            </tr>`;
        
        // Include filters in the request
        const response = await fetch(`../Functions/AuditLogFunctions.php?${params.toString()}`);
        
        // Rest of the function remains the same...
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.logs) {
            renderAuditLogs(data.logs);
        } else {
            throw new Error(data.message || 'Failed to load audit logs');
        }
    } catch (error) {
        console.error('Error fetching audit logs:', error);
        tbody.innerHTML = `
            <tr class="hover:bg-gray-50">
                <td colspan="6" class="px-6 py-4 text-center text-sm text-red-600">
                    Error loading audit logs. ${error.message}
                    <button onclick="fetchAuditLogs()" class="ml-2 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-md text-sm hover:bg-indigo-200">
                        Retry
                    </button>
                </td>
            </tr>`;
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Load audit logs when the page loads
    fetchAuditLogs();
    
    // Set up filters
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const moduleFilter = document.getElementById('moduleFilter');
    
    // Add event listeners for filters
    if (searchInput) {
        searchInput.addEventListener('input', debounce(fetchAuditLogs, 300));
    }
    
    if (roleFilter) {
        roleFilter.addEventListener('change', fetchAuditLogs);
    }
    
    if (moduleFilter) {
        moduleFilter.addEventListener('change', fetchAuditLogs);
    }
});

// Utility function for debouncing input
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export to Excel
// Export to Excel
async function Export_toExcel() {
    try {
        // Get current filter values
        const searchQuery = document.getElementById('searchInput').value;
        const roleFilter = document.getElementById('roleFilter').value;
        const moduleFilter = document.getElementById('moduleFilter').value;
        
        // Show loading state
        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Exporting...';
        button.disabled = true;

        // Fetch data with current filters
        const response = await fetch('../Functions/AuditLogFunctions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `export_excel=1&search=${encodeURIComponent(searchQuery)}&role=${encodeURIComponent(roleFilter)}&module=${encodeURIComponent(moduleFilter)}&action=export_excel`
        });

        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Failed to export data');
        }

        // Create workbook and worksheet
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.json_to_sheet(data.data);
        
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Audit Logs');
        
        // Generate Excel file and trigger download
        const date = new Date().toISOString().split('T')[0];
        XLSX.writeFile(wb, `Audit_Logs_${date}.xlsx`);
        
        if (button) {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    } catch (error) {
        console.error('Export failed:', error);
        showAlert(`Export failed: ${error.message}`, 'error');
    }
}

</script>

    <!-- Check-in Modal -->
    <?php include '../Modals/Checkin_modal.php'; ?>

</body>

</html>

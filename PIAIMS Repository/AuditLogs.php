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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <link rel="stylesheet" href="../Stylesheet/Design.css">
    <style>
        .filter-panel { transition: all 0.3s ease-in-out; overflow: hidden; }
        .filter-panel.hidden { max-height: 0; opacity: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; }
        .filter-panel.show { max-height: 1000px; opacity: 1; margin-bottom: 1rem; padding: 1.5rem; }
    </style>
</head>
<body style="font-family: 'Poppins', sans-serif;">
    <div class="dashboard-container">
        <?php include '../components/sidebar.php'; ?>
        
        <main class="main-content">
            <header class="main-header">
                <button class="sidebar-toggle" id="sidebarToggle"><i class='bx bx-menu'></i></button>
                <h1 id="pageTitle" style="color: #002e2d;">AUDIT LOGS HISTORY</h1>
            </header>
            
            <div class="content-container">
                <section class="content-section active">
                        <div class="infotype bg-white shadow-md rounded-2xl p-6 flex flex-col gap-3 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="infotype">
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
                            
                        </div>
                    </div>

                    <div id="filterPanel" class="filter-panel hidden bg-white shadow-md rounded-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Search</label>
                                <input type="text" id="searchInput" placeholder="User or ID..." class="p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-400 outline-none">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Role</label>
                                <select id="roleFilter" class="p-2 border border-gray-300 rounded-md outline-none">
                                    <option value="">All Roles</option>
                                    <option value="Super Administrator">Super Administrator</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Staff">Staff</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Action Type</label>
                                <select id="actionTypeFilter" class="p-2 border border-gray-300 rounded-md outline-none">
                                    <option value="">All Actions</option>
                                    <option value="create">Create</option>
                                    <option value="update">Update</option>
                                    <option value="delete">Delete</option>
                                    <option value="login">Login</option>
                                    <option value="logout">Logout</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Module</label>
                                <select id="moduleFilter" class="p-2 border border-gray-300 rounded-md outline-none">
                                    <option value="">All Modules</option>
                                    <option value="clinicpersonnel">Clinic Personnel</option>
                                    <option value="student">Student/Patient</option>
                                    <option value="medicine">Medicine</option>
                                    <option value="studentcheckins">Student Check-ins</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Start Date</label>
                                <input type="date" id="startDate" class="p-2 border border-gray-300 rounded-md outline-none">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">End Date</label>
                                <input type="date" id="endDate" class="p-2 border border-gray-300 rounded-md outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="card bg-white shadow-lg rounded-xl overflow-hidden">
                        <div class="card-header p-4 border-b flex flex-wrap justify-between items-center gap-4 bg-white">
                            <div class="flex items-center gap-4">
                                <h2 class="text-xl font-bold">Audit Trail</h2>
                                <select id="pageSize" class="p-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                    <option value="10">Show 10</option>
                                    <option value="25">Show 25</option>
                                    <option value="50">Show 50</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <button id="toggleFilters" class="flex items-center gap-2 px-4 py-2 text-white bg-blue-600 hover:bg-blue-7  00 text-blue-700 rounded-lg transition font-medium">
                                    <i class='bx bx-filter-alt'></i> <span>Filter Options</span>
                                </button>
                                <button onclick="Export_toExcel()" class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition">
                                    <i class='bx bx-export'></i> <span>Excel</span>
                                </button>
                                <button onclick="Export_toPDF()" class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                                    <i class='bx bxs-file-pdf'></i> <span>PDF</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-content">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Timestamp</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Performed By</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Record ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="auditLogsBody" class="bg-white divide-y divide-gray-200">
                                        </tbody>
                                </table>
                            </div>
                            
                            <div class="p-4 flex items-center justify-between border-t bg-gray-50">
                                <div class="text-sm text-gray-600" id="paginationInfo">Showing 0 to 0 of 0 entries</div>
                                <div class="flex gap-1" id="paginationButtons"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

<script src="https://cdn.sheetjs.com/xlsx-0.18.12/package/dist/xlsx.full.min.js"></script>
<script>
let allLogs = [];
let currentPage = 1;
let rowsPerPage = 10;
let totalRecords = 0;
let totalPages = 1;

function formatTimestamp(ts) {
    if (!ts) return 'N/A';
    const date = new Date(ts);
    return date.toLocaleString('en-US', { year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit', hour12:true });
}

function getBadge(action) {
    const act = (action || '').toLowerCase();
    const colors = { 
        create:'bg-green-100 text-green-800', 
        update:'bg-blue-100 text-blue-800', 
        delete:'bg-red-100 text-red-800', 
        login:'bg-purple-100 text-purple-800', 
        logout:'bg-gray-100 text-gray-800' 
    };
    return colors[act] || 'bg-yellow-100 text-yellow-800';
}

function renderTable() {
    const tbody = document.getElementById('auditLogsBody');
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const displayItems = allLogs.slice(start, end);

    if (displayItems.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No matching records found.</td></tr>`;
        updateControls(0);
        return;
    }

    tbody.innerHTML = displayItems.map(log => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">${formatTimestamp(log.timestamp)}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${log.user_name || 'System'}</div>
                <div class="text-xs text-gray-500">${log.user_role || ''}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-bold rounded-full ${getBadge(log.action_type)}">${log.action_type}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.module}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${log.record_id || 'N/A'}</td>
            <td class="px-6 py-4 text-sm text-gray-500"><div class="max-w-xs truncate" title="${log.details}">${log.details}</div></td>
        </tr>
    `).join('');
    updateControls(allLogs.length);
}

function updateControls(total) {
    totalRecords = total;
    totalPages = Math.ceil(total / rowsPerPage);
    
    // Update pagination info text
    const start = total === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
    const end = Math.min(currentPage * rowsPerPage, total);
    document.getElementById('paginationInfo').innerText = `Showing ${start} to ${end} of ${total} entries`;
    
    // Build pagination buttons
    let btns = '';
    
    // Previous button
    btns += `<button onclick="changePage(${currentPage-1})" ${currentPage===1?'disabled':''} class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Prev</button>`;
    
    // Always show first page
    btns += `<button onclick="changePage(1)" class="px-3 py-1 border rounded ${currentPage===1?'bg-indigo-600 text-white':'hover:bg-gray-100'}">1</button>`;
    
    // Show ellipsis if needed
    if (currentPage > 3) {
        btns += `<span class="px-2">...</span>`;
    }
    
    // Show pages around current page
    for(let i = Math.max(2, currentPage - 1); i <= Math.min(totalPages - 1, currentPage + 1); i++) {
        if (i > 1 && i < totalPages) {
            btns += `<button onclick="changePage(${i})" class="px-3 py-1 border rounded ${currentPage===i?'bg-indigo-600 text-white':'hover:bg-gray-100'}">${i}</button>`;
        }
    }
    
    // Show ellipsis if needed
    if (currentPage < totalPages - 2) {
        btns += `<span class="px-2">...</span>`;
    }
    
    // Always show last page if there's more than 1 page
    if (totalPages > 1) {
        btns += `<button onclick="changePage(${totalPages})" class="px-3 py-1 border rounded ${currentPage===totalPages?'bg-indigo-600 text-white':'hover:bg-gray-100'}">${totalPages}</button>`;
    }
    
    // Next button
    btns += `<button onclick="changePage(${currentPage+1})" ${currentPage===totalPages || totalPages===0?'disabled':''} class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Next</button>`;
    
    document.getElementById('paginationButtons').innerHTML = btns;
}

function changePage(p) { 
    if (p < 1 || p > totalPages) return;
    currentPage = p; 
    renderTable(); 
}

async function fetchAuditLogs() {
    // Show loading state
    const tbody = document.getElementById('auditLogsBody');
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>`;
    
    const params = new URLSearchParams({
        action: 'get_logs',
        search: document.getElementById('searchInput').value,
        role: document.getElementById('roleFilter').value,
        module: document.getElementById('moduleFilter').value,
        action_type: document.getElementById('actionTypeFilter').value,
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value,
        page: currentPage
    });
    
    try {
        const res = await fetch(`../Functions/AuditLogFunctions.php?${params.toString()}`);
        const data = await res.json();
        
        if (data.success) {
            allLogs = data.logs || [];
            currentPage = data.pagination?.page || 1;
            rowsPerPage = data.pagination?.per_page || 10;
            totalRecords = data.pagination?.total || 0;
            
            renderTable();
        } else {
            console.error('Failed to fetch logs:', data.message);
            tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error: ${data.message}</td></tr>`;
        }
    } catch (e) { 
        console.error('Fetch error:', e);
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Network error. Please try again.</td></tr>`;
    }
}

function Export_toPDF() {
    if (allLogs.length === 0) {
        alert('No data to export.');
        return;
    }
    
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4');
    
    // Add title
    doc.setFontSize(16);
    doc.text("Audit Logs History Report", 14, 15);
    
    // Add filters info
    doc.setFontSize(10);
    let filterInfo = "Filters: ";
    if (document.getElementById('startDate').value) {
        filterInfo += `From ${document.getElementById('startDate').value} `;
    }
    if (document.getElementById('endDate').value) {
        filterInfo += `To ${document.getElementById('endDate').value} `;
    }
    if (document.getElementById('actionTypeFilter').value) {
        filterInfo += `| Action: ${document.getElementById('actionTypeFilter').value} `;
    }
    doc.text(filterInfo, 14, 25);
    
    // Prepare table data
    const rows = allLogs.map(l => [
        formatTimestamp(l.timestamp), 
        l.user_name, 
        l.action_type, 
        l.module, 
        l.record_id || 'N/A', 
        l.details
    ]);
    
    // Generate table
    doc.autoTable({ 
        head: [['Timestamp', 'User', 'Action', 'Module', 'ID', 'Details']], 
        body: rows, 
        startY: 30, 
        theme: 'grid', 
        styles: {fontSize: 8},
        headStyles: { fillColor: [41, 128, 185] }
    });
    
    // Save PDF
    doc.save('AuditLogs_Report.pdf');
}

function Export_toExcel() {
    if (allLogs.length === 0) {
        alert('No data to export.');
        return;
    }
    
    // Prepare data for Excel
    const excelData = allLogs.map(log => ({
        'Timestamp': formatTimestamp(log.timestamp),
        'User Name': log.user_name || 'System',
        'User Role': log.user_role || 'System',
        'Action Type': log.action_type,
        'Module': log.module,
        'Record ID': log.record_id || 'N/A',
        'Details': log.details
    }));
    
    const ws = XLSX.utils.json_to_sheet(excelData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Audit Logs");
    
    // Auto-size columns
    const wscols = [
        {wch: 20}, // Timestamp
        {wch: 20}, // User Name
        {wch: 15}, // User Role
        {wch: 10}, // Action Type
        {wch: 15}, // Module
        {wch: 15}, // Record ID
        {wch: 40}, // Details
    ];
    ws['!cols'] = wscols;
    
    XLSX.writeFile(wb, "AuditLogs_Report.xlsx");
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('moduleFilter').value = '';
    document.getElementById('actionTypeFilter').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    currentPage = 1;
    fetchAuditLogs();
}

document.addEventListener('DOMContentLoaded', () => {
    fetchAuditLogs();
    
    // Toggle filter panel
    document.getElementById('toggleFilters').addEventListener('click', () => {
        const p = document.getElementById('filterPanel');
        p.classList.toggle('hidden'); 
        p.classList.toggle('show');
        
        // Update button text
        const btn = document.getElementById('toggleFilters');
        const span = btn.querySelector('span');
        if (p.classList.contains('show')) {
            span.textContent = 'Hide Filters';
        } else {
            span.textContent = 'Show Filters';
        }
    });
    
    // Add event listeners for filters
    ['roleFilter', 'moduleFilter', 'actionTypeFilter', 'startDate', 'endDate'].forEach(id => {
        document.getElementById(id).addEventListener('change', () => {
            currentPage = 1;
            fetchAuditLogs();
        });
    });
    
    // Search input with debounce
    document.getElementById('searchInput').addEventListener('input', debounce(() => {
        currentPage = 1;
        fetchAuditLogs();
    }, 400));
    
    // Page size change
    document.getElementById('pageSize').addEventListener('change', (e) => {
        rowsPerPage = parseInt(e.target.value);
        currentPage = 1;
        renderTable();
    });
    
    // Add clear filters button
    const filterPanel = document.getElementById('filterPanel');
    const clearBtn = document.createElement('button');
    clearBtn.className = 'flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-600 hover:text-white border-2 border-red-600 rounded-lg transition font-medium ml-auto';
    clearBtn.innerHTML = '<i class="bx bx-x"></i> <span>Clear Filters</span>';
    clearBtn.onclick = clearFilters;
    
    const filterGrid = filterPanel.querySelector('.grid');
    const clearContainer = document.createElement('div');
    clearContainer.className = 'flex items-end';
    clearContainer.appendChild(clearBtn);
    filterGrid.appendChild(clearContainer);
});

function debounce(f, w) { 
    let t; 
    return (...a) => { 
        clearTimeout(t); 
        t = setTimeout(() => f.apply(this, a), w); 
    }; 
}
</script>

<?php include '../Modals/Checkin_modal.php'; ?>
</body>
</html>
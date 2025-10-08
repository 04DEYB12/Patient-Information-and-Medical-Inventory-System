<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('Please login first!'); window.location.href = '../Landing Repository/LandingPage.php';</script>";
    exit();
}

$user_id = $_SESSION['User_ID'];
require_once '../Functions/Queries.php';


require_once '../Functions/MedicineCard.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIAIMS | Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../Stylesheet/Design.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include '../components/sidebar.php'; ?>
        
        <main class="main-content w-full h-screen p-2 overflow-auto bg-gray-50">
            <!-- Header -->
            <header class="main-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class='bx bx-menu'></i>
                </button>
                <h1 id="pageTitle" style="color: #002e2d;">ADMIN DASHBOARD</h1>
            </header>
            
            <!-- Contents -->
            <div class="content-container">
            <section class="content-section active" id="dashboardSection">
                <div class="top-container flex items-center justify-center gap-4">
                    <!-- Status Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full mb-10">

                        <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border-t-4 border-green-500">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-bold text-lg text-gray-700 tracking-wide">Total Medicines</h3>
                                <svg class="h-10 w-10 text-green-500 bg-green-100 p-2 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M12 20.5v-2.5m-5.464-9.673l-1.414-1.414M17.464 10.827l1.414-1.414M16.5 12h-2.5m-7.5 0h-2.5m-2 7.5l-1.414-1.414M20 19l-1.414-1.414"></path>
                                </svg>
                            </div>
                            <div class="mt-2">
                                <div class="text-6xl font-extrabold text-green-700 leading-tight"><?= $total_meds_count ?></div>
                                <p class="text-base text-gray-500 mt-1">Total items in inventory</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border-t-4 border-yellow-500">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-bold text-lg text-gray-700">Low Stock Items</h3>
                                <svg class="h-10 w-10 text-yellow-500 bg-yellow-100 p-2 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="mt-2">
                                <div class="text-6xl font-extrabold text-yellow-600 leading-tight"><?= $low_stock_count ?></div>
                                <p class="text-base text-gray-500 mt-1">Requires immediate restocking</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border-t-4 border-orange-500">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-bold text-lg text-gray-700">Near Expiry</h3>
                                <svg class="h-10 w-10 text-orange-500 bg-orange-100 p-2 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="mt-2">
                                <div class="text-6xl font-extrabold text-orange-600 leading-tight"><?= $near_expiry_count ?></div>
                                <p class="text-base text-gray-500 mt-1">Items approaching expiration</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border-t-4 border-red-500">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-bold text-lg text-gray-700">Expired Stock</h3>
                                <svg class="h-10 w-10 text-red-500 bg-red-100 p-2 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                            </div>
                            <div class="mt-2">
                                <div class="text-6xl font-extrabold text-red-600 leading-tight"><?= $expired_count ?></div>
                                <p class="text-base text-gray-500 mt-1">Requires disposal/removal</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border-t-4 border-blue-500">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-bold text-lg text-gray-700">Users Overview</h3>
                                <svg class="h-10 w-10 text-blue-500 bg-blue-100 p-2 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m0 0v-2a3 3 0 014.54-2.329m7.58 1.42L13.75 6.13a2.5 2.5 0 00-3.5 0L6.75 16.09m10.5-5.91h-2.5m-3 0h-2.5"></path>
                                </svg>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-6 text-center">
                                <div class="p-3">
                                    <div class="text-3xl font-extrabold text-blue-600"><?= $student_count; ?></div>
                                    <p class="mt-1 text-sm text-gray-500 font-medium">Students</p>
                                </div>
                                <div class="p-3 border-l border-r border-gray-100">
                                    <div class="text-3xl font-extrabold text-purple-600"><?= $clinicPersonnel_count; ?></div>
                                    <p class="mt-1 text-sm text-gray-500 font-medium">Admins</p>
                                </div>
                                <div class="p-3">
                                    <div class="text-3xl font-extrabold text-pink-600">15</div>
                                    <p class="mt-1 text-sm text-gray-500 font-medium">Staff</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-2 p-8 border-t-4 border-indigo-500">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-bold text-lg text-gray-700">Daily Check-Ins</h3>
                                <svg class="h-10 w-10 text-indigo-500 bg-indigo-100 p-2 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zm7-9h.01M12 15h.01"></path>
                                </svg>
                            </div>
                            <div class="mt-2">
                                <div class="text-6xl font-extrabold text-indigo-600 leading-tight"><?= $clinicPersonnel_count; ?></div> 
                                <p class="text-base text-gray-500 mt-1">Students checked in today</p>
                            </div>
                        </div>

                    </div>

                    <div class="card chart-card min-w-[400px] max-w-[900px] h-[450px] bg-white p-2 rounded-2xl shadow-lg overflow-hidden">
                        <!-- Top: Quick Scan Header -->
                        <div class="rounded-2xl bg-gradient-to-r from-green-700 to-green-900 p-4 shadow-md cursor-pointer mb-2">
                            <div class="flex items-center gap-4">
                                <!-- Icon -->
                                <div class="bg-white rounded-full p-3 shadow-inner flex items-center justify-center">
                                    <i class="bx bx-barcode text-green-800 text-2xl"></i>
                                </div>
                                <!-- Text -->
                                <div>
                                    <h1 class="text-white text-xl font-bold tracking-wide uppercase">Quick Student Scan</h1>
                                    <p class="text-green-200 text-sm mt-1">Scan student IDs quickly and efficiently</p>
                                </div>
                            </div>
                        </div>

                        <!-- Middle: Card Header -->
                        <div class="px-4 py-2 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-gray-700 font-semibold text-lg">Common Reasons For Visit</h2>
                        </div>

                        <!-- Bottom: Chart -->
                        <div class="card-content p-4 h-[calc(100%-128px)] bg-green-50 flex items-center justify-center">
                            <div class="chart-container w-full h-full rounded-xl bg-green-100 p-2 shadow-inner">
                                <canvas id="visitReasonsChart" class="w-full h-full"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
                

                

                
                <div class=" flex gap-4">
                    <div class="card w-full border-2 border-blue-600">
                        <div class="card-header flex flex-col gap-2 w-full ">
                            <div class="w-full flex items-center justify-between p-6">
                                <h3 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                                    <svg class="h-6 w-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Clinick check-in visits
                                </h3>
                                <div class="card-count"></div>
                            </div>
                            <div class="mt-4 flex flex-wrap items-center gap-4">

                                <div class="flex items-center gap-4 justify-between w-full">
                                    <!-- Search Bar -->
                                    <div class="relative w-[500px]">
                                        <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                        <input 
                                            type="text" 
                                            id="studentSearch" 
                                            placeholder="Search students..." 
                                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                                        >
                                    </div>

                                    <!-- Entries Filter -->
                                    <div class="flex items-center gap-2 rounded-md px-8 bg-gray-50">
                                        <span class="text-gray-600 text-sm">Show</span>
                                        <select 
                                            id="entriesFilter" 
                                            class="px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                                        >
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        <span class="text-gray-600 text-sm">entries</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 justify-between w-full">
                                    <!-- Date Filter -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-600 text-sm">Date:</span>
                                        <select 
                                            id="dateFilter" 
                                            class="px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                                        >
                                            <option value="all">All</option>
                                            <option value="today">Today</option>
                                            <option value="week">This Week</option>
                                            <option value="month">This Month</option>
                                        </select>
                                    </div>

                                    <!-- Managed By Filter -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-600 text-sm">Managed By:</span>
                                        <select 
                                            id="staffFilter" 
                                            class="px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                                        >
                                            <option value="all">All</option>
                                            <?php
                                            // Fetch all staff for the dropdown
                                            $staffSql = "SELECT PersonnelID, FirstName, MiddleName, LastName FROM clinicpersonnel ORDER BY LastName";
                                            $staffResult = $con->query($staffSql);
                                            if($staffResult->num_rows > 0){
                                                while($staff = $staffResult->fetch_assoc()){
                                                    $staffName = $staff['FirstName'] . ' ' . ($staff['MiddleName'] ? $staff['MiddleName'] . ' ' : '') . $staff['LastName'];
                                                    echo "<option value='" . $staff['PersonnelID'] . "'>" . htmlspecialchars($staffName) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-content">
                            <div class="table-container">
                                <table class="data-table" id="clinicTable">
                                    <thead>
                                        <tr class="text-nowrap">
                                            <th>Managed By</th>
                                            <th>Time</th>    
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Reason for Visit</th>
                                            <th>Status/Outcome</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-nowrap">
                                        <?php
                                        // Fetch all recent visits
                                        $sql = "SELECT 
                                                    sc.DateTime as DT,
                                                    sc.StaffID as StaffID,
                                                    s.School_ID as SID, 
                                                    s.FirstName as FN, 
                                                    s.LastName as LN, 
                                                    sc.Reason as R, 
                                                    sc.Status as S,
                                                    cp.FirstName as StaffFN,
                                                    cp.MiddleName as StaffMN,
                                                    cp.LastName as StaffLN
                                                FROM studentcheckins sc
                                                JOIN student s ON sc.StudentID = s.School_ID
                                                LEFT JOIN clinicpersonnel cp ON sc.StaffID = cp.PersonnelID
                                                ORDER BY sc.ID DESC";
                                        $result = $con->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $managedBy = $row['StaffFN'] . ' ' . ($row['StaffMN'] ? $row['StaffMN'] . ' ' : '') . $row['StaffLN'];
                                                echo "<tr data-staff='" . $row['StaffID'] . "' data-date='" . $row['DT'] . "'>";
                                                echo "<td class='text-nowrap'>" . htmlspecialchars($managedBy) . "</td>";
                                                echo "<td>" . date("F j, Y g:i A", strtotime($row["DT"])) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["SID"]) . "</td>";
                                                echo "<td class='text-nowrap'>" . htmlspecialchars($row["FN"] . " " . $row["LN"]) . "</td>";
                                                echo "<td class='text-nowrap italic font-500 text-green-600'>" . htmlspecialchars($row['R']) . "</td>";
                                                echo "<td class='text-nowrap text-green-600'>" . htmlspecialchars($row['S']) . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No recent clinic visits</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php
                        include '../Landing Repository/Connection.php';

                       // Fetch from audit_trail (only those related to medicine) and get medicine name
                        $audit_query = "
                        SELECT a.*, m.name AS med_name
                        FROM audit_trail a
                        JOIN medicine m ON a.record_id = m.med_id
                        WHERE a.table_name = 'medicine'
                        ORDER BY a.created_at DESC
                        ";
                        $audit_result = $con->query($audit_query);

                        // Fetch from medicine_usage (also with medicine name)
                        $usage_query = "
                        SELECT u.*, m.name AS med_name
                        FROM medicine_usage u
                        JOIN medicine m ON u.med_id = m.med_id
                        ORDER BY u.usage_date DESC
                        ";
                        $usage_result = $con->query($usage_query);


                    ?>

                    <div class="inventory-log bg-white rounded-3xl shadow-xl p-6 border-2 border-purple-600 overflow-hidden w-full">
                        <h3 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                            <svg class="h-6 w-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Unified Inventory Actions and Events Log
                        </h3>

                        <div class="overflow-x-auto max-h-[600px] lg:max-h-[700px]">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr class="text-nowrap"> 
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Med NAME</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details / Description</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User/Source</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm text-nowrap">

                                    <!-- Medicine Usage Logs -->
                                    <?php while ($row = $usage_result->fetch_assoc()): 
                                        $timestamp = date("F j, Y h:i A", strtotime($row["usage_date"]));
                                        $actionType = "USED";
                                        ?>
                                        <tr class="hover:bg-blue-50">
                                            <td class="px-4 py-3 text-gray-500 text-nowrap"><?= $timestamp ?></td>
                                            <td class="px-4 py-3 text-blue-600"><?= $actionType ?></td>
                                            <td class="px-4 py-3 text-blue-700"><?= strtoupper($row['med_name']) ?></td>
                                            <td class="px-4 py-3 text-gray-600">
                                                Quantity of <strong><?= htmlspecialchars($row['quantity_used']) ?></strong> used from Medicine ID <?= htmlspecialchars($row['med_id']) ?>.
                                            </td>
                                            <td class="px-4 py-3 text-gray-500">System/Usage</td>
                                        </tr>
                                    <?php endwhile; ?>

                                    <!-- Audit Trail Logs -->
                                    <?php while ($row = $audit_result->fetch_assoc()):
                                        $timestamp = date("F j, Y h:i A", strtotime($row["created_at"]));

                                        // Original action type (from DB)
                                        $type = strtoupper($row["action_type"]);

                                        // Map for display name changes
                                        $displayType = match($type) {
                                            'CREATE' => 'ADDED',
                                            'UPDATE' => 'RESTOCK',
                                            default => $type
                                        };

                                        // Color scheme
                                        $color = match($displayType) {
                                            'ADDED' => 'green',
                                            'RESTOCK' => 'green',
                                            'DEDUCT' => 'red',
                                            'DELETE' => 'red',
                                            default => 'gray'
                                        };
                                    ?>
                                        <tr class="hover:bg-<?=
                                            $color === 'green' ? 'green-50' :
                                            ($color === 'yellow' ? 'yellow-50' :
                                            ($color === 'red' ? 'red-50' : 'gray-50'));
                                        ?>">
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-500"><?= $timestamp ?></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-<?= $color ?>-600"><?= $displayType ?></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-blue-700"><?= strtoupper($row['med_name']) ?></td>
                                            <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($row['action_details']) ?></td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['user_id']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>
            </div>
        </main>
    </div>
</body>

<!-- Add Chart.js library with plugins -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.querySelector('.main-content');
        
        // Toggle sidebar
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        
            // For mobile
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
            }
        });
    
    console.log('Doctor Management System initialized successfully!');
    });
    
    const searchInput = document.getElementById('studentSearch');
    const entriesFilter = document.getElementById('entriesFilter');
    const dateFilter = document.getElementById('dateFilter');
    const staffFilter = document.getElementById('staffFilter');
    const table = document.getElementById('clinicTable').getElementsByTagName('tbody')[0];

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const selectedDate = dateFilter.value;
        const selectedStaff = staffFilter.value;

        const rows = table.getElementsByTagName('tr');
        let visibleCount = 0;
        const maxEntries = parseInt(entriesFilter.value);

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const studentName = row.cells[3].textContent.toLowerCase();
            const studentID = row.cells[2].textContent.toLowerCase();
            const rowStaff = row.dataset.staff;
            const rowDate = new Date(row.dataset.date);

            // Search filter
            let matchSearch = studentName.includes(searchText) || studentID.includes(searchText);

            // Staff filter
            let matchStaff = (selectedStaff === 'all' || rowStaff === selectedStaff);

            // Date filter
            let matchDate = false;
            const today = new Date();
            const oneWeekAgo = new Date(); oneWeekAgo.setDate(today.getDate() - 7);
            const oneMonthAgo = new Date(); oneMonthAgo.setMonth(today.getMonth() - 1);

            if(selectedDate === 'all') matchDate = true;
            else if(selectedDate === 'today') {
                matchDate = rowDate.toDateString() === today.toDateString();
            } else if(selectedDate === 'week') {
                matchDate = rowDate >= oneWeekAgo;
            } else if(selectedDate === 'month') {
                matchDate = rowDate >= oneMonthAgo;
            }

            if(matchSearch && matchStaff && matchDate && visibleCount < maxEntries){
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
    }

    // Event listeners
    searchInput.addEventListener('input', filterTable);
    entriesFilter.addEventListener('change', filterTable);
    dateFilter.addEventListener('change', filterTable);
    staffFilter.addEventListener('change', filterTable);

    // Initial filter
    filterTable();

    // Register the datalabels plugin
    Chart.register(ChartDataLabels);

    // Wait for the document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading state
        const chartContainer = document.querySelector('.card-content');
        chartContainer.innerHTML = `
            <div class="chart-loading" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                <div class="spinner" style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            </div>
            <canvas id="visitReasonsChart" style="display: none;"></canvas>
        `;
        
        // Fetch visit reasons data from the server
        fetch('../Functions/patientFunctions.php?action=getVisitReasons')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.reasons.length > 0) {
                    const reasons = data.reasons;
                    
                    // Prepare data for the chart
                    const labels = reasons.map(item => item.reason);
                    const counts = reasons.map(item => item.count);
                    const totalVisits = counts.reduce((a, b) => a + b, 0);
                    
                    // Generate gradient colors
                    const { backgroundColors, borderColors } = generateGradientColors(labels.length);
                    
                    // Get the canvas element and show it
                    const chartElement = document.querySelector('#visitReasonsChart');
                    chartElement.style.display = 'block';
                    document.querySelector('.chart-loading').style.display = 'none';
                    
                    const ctx = chartElement.getContext('2d');
                    
                    // Create gradient for bars
                    function createGradient(ctx, chartArea, colors, index) {
                        const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                        gradient.addColorStop(0, colors[index].start);
                        gradient.addColorStop(1, colors[index].end);
                        return gradient;
                    }
                    
                    // Create the pie chart
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: counts,
                                backgroundColor: backgroundColors.map(bg => bg.start),
                                borderColor: '#ffffff',
                                borderWidth: 2,
                                hoverOffset: 15,
                                hoverBorderColor: '#ffffff',
                                hoverBorderWidth: 2,
                                weight: 0.5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            radius: '90%',
                            layout: {
                                padding: 20
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true,
                                duration: 1000,
                                easing: 'easeOutQuart'
                            },
                            plugins: {
                                legend: {
                                    position: 'right',
                                    align: 'center',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            family: 'Inter, sans-serif',
                                            size: 12
                                        },
                                        generateLabels: function(chart) {
                                            const data = chart.data;
                                            if (data.labels.length && data.datasets.length) {
                                                return data.labels.map((label, i) => {
                                                    const meta = chart.getDatasetMeta(0);
                                                    const ds = data.datasets[0];
                                                    const arc = meta.data[i];
                                                    const custom = arc && arc.custom || {};
                                                    const value = chart.data.labels && i < chart.data.labels.length ? 
                                                        `${label}: ${ds.data[i]} (${((ds.data[i] / totalVisits) * 100).toFixed(1)}%)` : 
                                                        '';
                                                    
                                                    return {
                                                        text: value,
                                                        fillStyle: ds.backgroundColor[i],
                                                        strokeStyle: ds.borderColor,
                                                        lineWidth: 1,
                                                        hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
                                                        index: i
                                                    };
                                                });
                                            }
                                            return [];
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(33, 37, 41, 0.95)',
                                    titleFont: {
                                        family: 'Inter, sans-serif',
                                        size: 13,
                                        weight: '600'
                                    },
                                    bodyFont: {
                                        family: 'Inter, sans-serif',
                                        size: 13
                                    },
                                    padding: 12,
                                    cornerRadius: 8,
                                    displayColors: false,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const percentage = ((value / totalVisits) * 100).toFixed(1);
                                            return [
                                                `${label}: ${value} visit${value !== 1 ? 's' : ''}`,
                                                `(${percentage}% of total)`
                                            ];
                                        }
                                    }
                                },
                                datalabels: {
                                    formatter: (value, ctx) => {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value * 100) / total).toFixed(1) + '%';
                                        return value > total * 0.05 ? percentage : '';
                                    },
                                    color: '#fff',
                                    font: {
                                        family: 'Inter, sans-serif',
                                        size: 11,
                                        weight: '600'
                                    },
                                    textAlign: 'center',
                                    textShadowColor: 'rgba(0,0,0,0.3)',
                                    textShadowBlur: 5
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            onHover: (event, chartElement) => {
                                const target = event.native.target;
                                target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                            },
                            onClick: (event, elements) => {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const label = this.data.labels[index];
                                    console.log('Clicked on:', label);
                                    // You can add custom click behavior here
                                }
                            }
                        }
                    });
                    
                    // Add total visits badge
                    const cardHeader = document.querySelector('.card-count');
                    const badge = document.createElement('div');
                    badge.className = 'total-visits-badge';
                    badge.innerHTML = `
                        <span class="total-count">${totalVisits}</span>
                        <span class="total-label">Total Visits</span>
                    `;
                    cardHeader.appendChild(badge);
                    
                    // Add styles
                    const style = document.createElement('style');
                    style.textContent = `
                        .card {
                            position: relative;
                            background: #ffffff;
                            border-radius: 20px;
                            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                            overflow: hidden;
                            transition: transform 0.3s ease, box-shadow 0.3s ease;
                            border-top: 4px solidrgb(36, 103, 190);
                        }
                        .card:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                        }
                        .card-header {
                            padding: 1.25rem 1.5rem;
                            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            position: relative;
                        }
                        .card-header h2 {
                            margin: 0;
                            font-size: 1.25rem;
                            font-weight: 600;
                            color: #2d3436;
                            font-family: 'Inter', sans-serif;
                        }
                        .card-content {
                            padding: 1.5rem;
                            position: relative;
                        }
                        .total-visits-badge {
                            background: #f8f9fa;
                            border-radius: 20px;
                            padding: 0.35rem 0.75rem;
                            display: flex;
                            align-items: center;
                            gap: 0.5rem;
                            font-family: 'Inter', sans-serif;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                        }
                        .total-count {
                            font-weight: 700;
                            color: #3498db;
                            font-size: 1rem;
                        }
                        .total-label {
                            font-size: 0.75rem;
                            color: #6c757d;
                            font-weight: 500;
                        }
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        /* Custom scrollbar for chart tooltip */
                        .chartjs-tooltip {
                            max-height: 300px;
                            overflow-y: auto;
                        }
                        .chartjs-tooltip::-webkit-scrollbar {
                            width: 6px;
                        }
                        .chartjs-tooltip::-webkit-scrollbar-track {
                            background: rgba(0, 0, 0, 0.05);
                            border-radius: 10px;
                        }
                        .chartjs-tooltip::-webkit-scrollbar-thumb {
                            background: rgba(0, 0, 0, 0.2);
                            border-radius: 10px;
                        }
                    `;
                    document.head.appendChild(style);
                    
                } else {
                    // Show no data message
                    const message = data.success ? 'No visit data available' : 'Failed to load data';
                    chartContainer.innerHTML = `
                        <div style="height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #6c757d; font-family: 'Inter', sans-serif;">
                            <i class='bx bx-pie-chart-alt' style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p>${message}</p>
                        </div>
                    `;
                    console.error('Failed to load visit reasons:', data.message || 'No data available');
                }
            })
            .catch(error => {
                console.error('Error fetching visit reasons:', error);
                chartContainer.innerHTML = `
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #dc3545; font-family: 'Inter', sans-serif;">
                        <i class='bx bx-error-circle' style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <p>Error loading data. Please try again later.</p>
                    </div>
                `;
            });
        
        // Function to generate gradient colors for the chart
        function generateGradientColors(count) {
            const backgroundColors = [];
            const borderColors = [];
            
            const colorPalette = [
                { start: 'rgba(52, 152, 219, 0.8)', end: 'rgba(41, 128, 185, 0.8)' }, // Blue
                { start: 'rgba(46, 204, 113, 0.8)', end: 'rgba(39, 174, 96, 0.8)' }, // Green
                { start: 'rgba(155, 89, 182, 0.8)', end: 'rgba(142, 68, 173, 0.8)' }, // Purple
                { start: 'rgba(241, 196, 15, 0.8)', end: 'rgba(243, 156, 18, 0.8)' }, // Yellow
                { start: 'rgba(230, 126, 34, 0.8)', end: 'rgba(211, 84, 0, 0.8)' }, // Orange
                { start: 'rgba(231, 76, 60, 0.8)', end: 'rgba(192, 57, 43, 0.8)' }, // Red
                { start: 'rgba(26, 188, 156, 0.8)', end: 'rgba(22, 160, 133, 0.8)' }, // Turquoise
                { start: 'rgba(52, 73, 94, 0.8)', end: 'rgba(44, 62, 80, 0.8)' }, // Dark
                { start: 'rgba(149, 165, 166, 0.8)', end: 'rgba(127, 140, 141, 0.8)' }, // Gray
                { start: 'rgba(22, 160, 133, 0.8)', end: 'rgba(26, 188, 156, 0.8)' }  // Teal
            ];
            
            for (let i = 0; i < count; i++) {
                const colorIndex = i % colorPalette.length;
                backgroundColors.push(colorPalette[colorIndex]);
                borderColors.push({
                    start: colorPalette[colorIndex].start.replace('0.8', '1'),
                    end: colorPalette[colorIndex].end.replace('0.8', '1')
                });
            }
            
            return { backgroundColors, borderColors };
        }
    });
</script>
</html>
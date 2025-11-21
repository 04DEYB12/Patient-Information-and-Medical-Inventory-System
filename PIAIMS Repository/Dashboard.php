<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('Please login first!'); window.location.href = '../Landing Repository/LandingPage.php';</script>";
    exit();
}

$user_id = $_SESSION['User_ID'];
// Keeping require_once for compatibility, but fetching critical data here for certainty.
require_once '../Functions/Queries.php'; 
require_once '../Functions/MedicineCard.php';

// --- DATA FETCHING ENHANCEMENT ---

// 1. Daily Check-ins: Fetches count of students checked in today.
$daily_checkin_sql = "SELECT COUNT(*) as count FROM studentcheckins WHERE DATE(DateTime) = CURDATE()";
$daily_checkin_result = $con->query($daily_checkin_sql);
$daily_checkin_count = $daily_checkin_result ? $daily_checkin_result->fetch_assoc()['count'] : 0;

// 2. Users Overview: Fetches student, admin, and staff counts separately.
$student_count_sql = "SELECT COUNT(*) as count FROM student";
$student_count_result = $con->query($student_count_sql);
$student_count = $student_count_result ? $student_count_result->fetch_assoc()['count'] : 0;

// ASSUMPTION: 'clinicpersonnel' table has a 'Role' column ('Admin', 'Staff').
$admin_count_sql = "SELECT COUNT(*) as count FROM clinicpersonnel WHERE RoleID = '1'";
$admin_count_result = $con->query($admin_count_sql);
$admin_count = $admin_count_result ? $admin_count_result->fetch_assoc()['count'] : 0;

$staff_count_sql = "SELECT COUNT(*) as count FROM clinicpersonnel WHERE RoleID = '2'";
$staff_count_result = $con->query($staff_count_sql);
$staff_count = $staff_count_result ? $staff_count_result->fetch_assoc()['count'] : 0;

// Placeholder variables for Inventory Summary (If not defined in Queries.php)
$total_meds_count = $total_meds_count ?? 0;
$low_stock_count = $low_stock_count ?? 0;
$near_expiry_count = $near_expiry_count ?? 0;
$expired_count = $expired_count ?? 0;

// --- INVENTORY LOG FETCHING FIX ---

// A. Medicine Usage Logs: Join 'medicine_usage' with 'medicine' to get the name
$usage_sql = "SELECT 
                mu.usage_date,
                mu.med_id,
                mu.quantity_used,
                m.name AS med_name
              FROM medicine_usage mu
              JOIN medicine m ON mu.med_id = m.med_id
              ORDER BY mu.usage_date DESC";
$usage_result = $con->query($usage_sql);

// B. Audit Trail Logs (Inventory Actions): Join 'audit_trail' with 'medicine' to get the medicine name
// Filter for actions that reference the 'medicine' table (inventory actions).
$audit_sql = "SELECT 
                at.created_at,
                at.action_type,
                at.action_details,
                at.user_id,
                m.name AS med_name
              FROM audit_trail at
              LEFT JOIN medicine m ON at.record_id = m.med_id AND at.table_name = 'medicine'
              WHERE at.table_name = 'medicine' OR at.table_name IS NULL
              ORDER BY at.created_at DESC";
$audit_result = $con->query($audit_sql);

// --- END INVENTORY LOG FETCHING FIX ---
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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <script src="../Functions/scripts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../Stylesheet/Design.css">
</head>
<style>
    /* Luminous Time Styling */
    .luminous-time {
        text-shadow:
            0 0 6px rgba(34, 197, 94, 0.6),
            0 0 12px rgba(34, 197, 94, 0.6),
            0 0 24px rgba(34, 197, 94, 0.4),
            0 0 36px rgba(34, 197, 94, 0.3);
        letter-spacing: 0.15em;
        transition: all 0.3s ease-in-out;
        font-family: "Orbitron", sans-serif;
    }

    /* Optional glowing pulse animation */
    @keyframes pulseGlow {
        0%, 100% {
            text-shadow:
                0 0 6px rgba(34, 197, 94, 0.7),
                0 0 14px rgba(34, 197, 94, 0.5),
                0 0 28px rgba(34, 197, 94, 0.4);
        }
        50% {
            text-shadow:
                0 0 12px rgba(34, 197, 94, 1),
                0 0 24px rgba(34, 197, 94, 0.8),
                0 0 36px rgba(34, 197, 94, 0.6);
        }
    }

    .luminous-time:hover {
        animation: pulseGlow 2s infinite alternate;
    }
    
    /* Button Active State Styling */
    .active-btn {
        background: linear-gradient(90deg, #16a34a, #22c55e);
        box-shadow: 0 0 12px rgba(34, 197, 94, 0.5), 0 0 0 3px rgba(34, 197, 94, 0.3); /* Enhanced ring */
    }
    
    /* Page Section Transition */
    .page-section {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

    /* Table Styling Enhancement */
    .data-table th {
        padding: 12px 10px;
        text-align: left;
        font-weight: 700;
        color: #374151; /* Darker text */
        border-bottom: 2px solid #e5e7eb;
    }
    .data-table td {
        padding: 10px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .data-table tbody tr:hover {
        background-color: #f9fafb;
    }
    
    /* Custom Card Design for Users Overview */
    .user-card {
        transition: all 0.3s ease;
        border: 2px solid;
    }
    .user-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

</style>
<body>
    <div class="dashboard-container">
        <?php include '../components/sidebar.php'; ?>
        
        <main class="main-content w-full h-screen overflow-auto bg-gray-50">
            <header class="main-header p-4">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class='bx bx-menu'></i>
                </button>
                <h1 id="pageTitle" style="color: #002e2d;">ADMIN DASHBOARD</h1>
            </header>
            
            <div class="content-container">
                <section class="content-section active" id="dashboardSection">
                    <div class="flex flex-col w-full gap-4">
                        
                        <!-- Inventory Summary -->
                        <div class="top-container w-full flex flex-col lg:flex-row gap-4">
                            <div class="flex-1 bg-green-50 max-w-[700px] rounded-2xl shadow-lg p-6 border border-green-400">
                                <div class="flex justify-between items-center mb-8">
                                    <h3 class="font-semibold text-gray-700">Inventory Summary</h3>
                                    <div class="h-10 w-10 text-green-500 bg-green-200 p-2 rounded-full flex items-center justify-center">
                                        <i class='bx bx-package text-xl'></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-4 gap-2">
                                    <div class="bg-green-50 border border-green-400 p-3 rounded-xl text-center">
                                        <span class="text-green-700 font-bold text-lg"><?= $total_meds_count ?></span>
                                        <p class="text-gray-600 text-sm font-medium">All</p>
                                    </div>
                                    <div class="bg-yellow-50 border border-yellow-400 p-3 rounded-xl text-center">
                                        <span class="text-yellow-600 font-bold text-lg"><?= $low_stock_count ?></span>
                                        <p class="text-gray-600 text-sm font-medium">Low</p>
                                    </div>
                                    <div class="bg-orange-50 border border-orange-400 p-3 rounded-xl text-center">
                                        <span class="text-orange-600 font-bold text-lg"><?= $near_expiry_count ?></span>
                                        <p class="text-gray-600 text-sm font-medium">Near</p>
                                    </div>
                                    <div class="bg-red-50 border border-red-400 p-3 rounded-xl text-center">
                                        <span class="text-red-600 font-bold text-lg"><?= $expired_count ?></span>
                                        <p class="text-gray-600 text-sm font-medium">Expired</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Users Overview -->
                            <div class="flex-1 bg-blue-50 max-w-[400px] rounded-2xl shadow-lg p-6 border border-blue-300">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="font-semibold text-gray-700">Users Overview</h3>
                                    <div class="h-10 w-10 text-blue-500 bg-blue-100 p-2 rounded-full flex items-center justify-center">
                                        <i class='bx bx-group text-xl'></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-3 text-center">
                                    <div class="user-card bg-blue-50 border border-blue-400 p-4 rounded-xl">
                                        <div class="text-2xl font-extrabold text-blue-600"><?= $student_count ?></div>
                                        <p class="text-sm text-gray-500">Students</p>
                                    </div>
                                    <div class="user-card bg-purple-50 border border-purple-400 p-4 rounded-xl">
                                        <div class="text-2xl font-extrabold text-purple-600"><?= $admin_count ?></div>
                                        <p class="text-sm text-gray-500">Admins</p>
                                    </div>
                                    <div class="user-card bg-pink-50 border border-pink-400 p-4 rounded-xl">
                                        <div class="text-2xl font-extrabold text-pink-600"><?= $staff_count ?></div>
                                        <p class="text-sm text-gray-500">Staff</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Students Checked-in Today -->
                            <div class="flex-1 max-w-[200px] bg-white rounded-2xl shadow-lg p-6 border-2 border-indigo-600 flex flex-col justify-between">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="font-semibold text-gray-700 text-sm leading-tight">STUDENTS CHECKED-IN TODAY</h3>
                                    <div class="h-10 w-10 text-white bg-indigo-600 p-2 rounded-full flex items-center justify-center shadow-lg">
                                        <i class='bx bx-calendar-check text-xl'></i>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-6xl font-extrabold text-indigo-600 tracking-wider transition-transform hover:scale-105">
                                        <?= $daily_checkin_count ?>
                                    </div>
                                    <p class="text-gray-500 text-xs mt-1 font-medium">Daily Visits</p>
                                </div>
                            </div>

                            <!-- Time & Date -->
                            <div class="flex-1 max-w-[200px] rounded-2xl shadow-lg hover:shadow-2xl p-2 text-white flex flex-col items-center justify-start"
                                style="background-image: url('../Images/timebg.jpg'); background-size: cover;">
                                <h1 class="text-2xl font-semibold tracking-wide mt-2">Time & Date</h1>
                                <div id="currentDate" class="text-xl opacity-90 mb-1 mt-8"></div>
                                <div id="currentTime" class="luminous-time text-3xl font-semibold tracking-widest mt-2 text-center text-green-300"></div>                            
                            </div>

                        </div>

                        <!-- Bottom Container -->
                        <div class="bottom-container w-full flex gap-4">
                            <!-- Top Used Medicines -->
                            <div class="w-2/3 bg-white rounded-md shadow-lg hover:border-blue-600 transition-all transform p-6 border border-blue-300">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="font-bold text-lg text-gray-700">Top Used Medicines</h3>

                                    <div class="flex items-center gap-3">
                                        <select id="chartDateFilter" class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            <option value="all">All Time</option>
                                            <option value="today">Today</option>
                                            <option value="day">Select Day</option>
                                            <option value="week">This Week</option>
                                            <option value="month">This Month</option>
                                            <option value="year">This Year</option>
                                        </select>
                                        <input type="date" id="specificDate" class="hidden border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                </div>

                                <canvas id="medicineBarChart" class="w-full" style="max-height: 260px;"></canvas>
                            </div>
                            <!-- Common Reasons for Visit -->
                            <div class="w-full bg-white rounded-md shadow-lg hover:border-green-600 transition-all transform p-6 border border-green-300">
                                <div class="card-count flex justify-between items-center mb-2">
                                    <h3 class="text-gray-700 font-semibold text-lg">Common Reasons for Visit</h3>
                                    <div class="h-10 w-10 text-green-500 bg-green-100 p-2 rounded-full flex items-center justify-center">
                                        <i class='bx bx-pulse text-xl'></i>
                                    </div>
                                </div>

                                <div class="p-4 bg-green-50 rounded-xl flex items-center justify-center">
                                    <div class="chart-container w-full h-[260px] rounded-xl bg-green-100 p-2 shadow-inner">
                                        <canvas id="visitReasonsChart" class="w-full h-full"></canvas>
                                        </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Page Buttons -->
                    <div class="flex w-full items-center justify-start gap-2 mb-4 mt-8 pt-4 shadow-t-lg">
                        <button 
                            class="page-btn active-btn bg-white hover:shadow-lg text-gray-700 font-semibold px-6 py-2 border border-blue-600 transition-all"
                            data-target="clinic-page">
                            Clinic Check-in Visits
                        </button>

                        <button 
                            class="page-btn bg-white hover:shadow-lg text-gray-700 font-semibold px-6 py-2 border border-green-600 transition-all"
                            data-target="inventory-page">
                            Inventory Log
                        </button>
                    </div>

                    <!-- Pages Container -->
                    <div class="pages-container w-full">

                        <!-- Clinic Page -->
                        <div id="clinic-page" class="page-section">
                            <div class="card w-full border-2 border-blue-600 rounded-2xl bg-white shadow-xl p-4">
                                <div class="card-header w-full flex flex-col gap-2">
                                    <div class="w-full flex items-center justify-between">
                                        <h3 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                                            <i class='bx bxs-time-five text-purple-600 mr-2 text-2xl'></i>
                                            Clinic Check-in Visits
                                        </h3>
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-600 text-sm text-nowrap">Show:</span>
                                            <select id="entriesFilter" class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-400">
                                                <option value="5">5 Entries</option>
                                                <option value="10">10 Entries</option>
                                                <option value="25">25 Entries</option>
                                                <option value="50">50 Entries</option>
                                                <option value="100">100 Entries</option>
                                                <option value="999999">All</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-4 w-full items-center justify-between">
                                        <div class="relative w-full md:w-1/3">
                                            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                            <input 
                                                type="text" 
                                                id="studentSearch" 
                                                placeholder="Search students..." 
                                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                                            >
                                        </div>

                                        <div class="flex gap-4 items-center">
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-600 text-sm">Date:</span>
                                                <select id="clinicDateFilter" class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-400">
                                                    <option value="all">All</option>
                                                    <option value="today">Today</option>
                                                    <option value="week">This Week</option>
                                                    <option value="month">This Month</option>
                                                </select>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-600 text-sm text-nowrap">Managed By:</span>
                                                <select id="staffFilter" class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-400">
                                                    <option value="all">All</option>
                                                    <?php
                                                    $staffSql = "SELECT PersonnelID, FirstName, MiddleName, LastName FROM clinicpersonnel ORDER BY LastName";
                                                    $staffResult = $con->query($staffSql);
                                                    if($staffResult && $staffResult->num_rows > 0){
                                                        while($staff = $staffResult->fetch_assoc()){
                                                            $staffName = $staff['FirstName'] . ' ' . ($staff['MiddleName'] ? $staff['MiddleName'] . ' ' : '') . $staff['LastName'];
                                                            echo "<option value='" . htmlspecialchars($staff['PersonnelID']) . "'>" . htmlspecialchars($staffName) . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-content p-6">
                                    <div class="overflow-x-auto">
                                        <table id="clinicTable" class="data-table min-w-full divide-y divide-gray-200 text-sm">
                                            <thead class="bg-gray-50">
                                                <tr class="text-nowrap">
                                                    <th>Managed By</th>
                                                    <th>Time</th>
                                                    <th>Student ID</th>
                                                    <th>Student Name</th>
                                                    <th>Reason for Visit</th>
                                                    <th>Status/Outcome</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
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
                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $managedBy = $row['StaffFN'] . ' ' . ($row['StaffMN'] ? $row['StaffMN'] . ' ' : '') . $row['StaffLN'];
                                                        $status = htmlspecialchars($row["S"]);

                                                        // Determine text color based on status
                                                        $statusClass = match ($status) {
                                                            'In Progress' => 'text-yellow-700 font-semibold',
                                                            'Completed'   => 'text-blue-600 font-semibold',
                                                            default       => 'text-green-600 font-semibold',
                                                        };

                                                        echo "<tr data-staff='" . htmlspecialchars($row['StaffID']) . "' data-date='" . date("Y-m-d", strtotime($row["DT"])) . "'>
                                                                <td>" . htmlspecialchars($managedBy) . "</td>
                                                                <td>" . date("F j, Y g:i A", strtotime($row["DT"])) . "</td>
                                                                <td>" . htmlspecialchars($row["SID"]) . "</td>
                                                                <td>" . htmlspecialchars($row["FN"] . ' ' . $row["LN"]) . "</td>
                                                                <td class='italic text-green-600'>" . htmlspecialchars($row["R"]) . "</td>
                                                                <td class='" . $statusClass . "'>" . $status . "</td>
                                                            </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='6' class='text-center text-gray-500 py-4'>No recent clinic visits</td></tr>";
                                                }
                                                ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Page -->
                        <div id="inventory-page" class="page-section hidden">
                            <div class="inventory-log bg-white rounded-3xl shadow-xl p-6 border-2 border-purple-600">
                                <h3 class="text-xl font-bold text-gray-700 mb-4 flex items-center">
                                    <i class='bx bx-notepad text-purple-600 mr-2 text-2xl'></i>
                                    Unified Inventory Actions and Events Log
                                </h3>

                                <div class="overflow-x-auto max-h-[600px] lg:max-h-[700px]">
                                    <table class="data-table min-w-full divide-y divide-gray-200">
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

                                            <?php 
                                            // Check if $usage_result is set and has rows before iterating
                                            if ($usage_result && $usage_result->num_rows > 0) :
                                                // Reset pointer for safety if needed, though this is the first iteration
                                                $usage_result->data_seek(0);
                                                while ($row = $usage_result->fetch_assoc()): 
                                                    $timestamp = date("F j, Y h:i A", strtotime($row["usage_date"]));
                                                    $actionType = "USED";
                                                    ?>
                                                    <tr class="hover:bg-blue-50">
                                                        <td class="px-4 py-3 text-gray-500 text-nowrap"><?= $timestamp ?></td>
                                                        <td class="px-4 py-3 text-blue-600"><?= $actionType ?></td>
                                                        <td class="px-4 py-3 text-blue-700"><?= strtoupper(htmlspecialchars($row['med_name'])) ?></td>
                                                        <td class="px-4 py-3 text-gray-600">
                                                            Quantity of <strong><?= htmlspecialchars($row['quantity_used']) ?></strong> used from Medicine ID <?= htmlspecialchars($row['med_id']) ?>.
                                                        </td>
                                                        <td class="px-4 py-3 text-gray-500">System/Usage</td>
                                                    </tr>
                                                <?php endwhile; 
                                            endif;
                                            ?>

                                            <?php 
                                            // Check if $audit_result is set and has rows before iterating
                                            if ($audit_result && $audit_result->num_rows > 0) :
                                                // Reset pointer for safety if needed, though this is the first iteration
                                                $audit_result->data_seek(0);
                                                while ($row = $audit_result->fetch_assoc()):
                                                    $timestamp = date("F j, Y h:i A", strtotime($row["created_at"]));

                                                    // Original action type (from DB)
                                                    $type = strtoupper($row["action_type"]);

                                                    // Map for display name changes
                                                    $displayType = match($type) {
                                                        'CREATE' => 'ADDED',
                                                        'UPDATE' => 'RESTOCK',
                                                        'DEDUCT' => 'DEDUCT',
                                                        'DELETE' => 'DELETE',
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
                                                        <td class="px-4 py-3 whitespace-nowrap text-blue-700"><?= strtoupper(htmlspecialchars($row['med_name'])) ?></td>
                                                        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($row['action_details']) ?></td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['user_id']) ?></td>
                                                    </tr>
                                                <?php endwhile;
                                            endif; 
                                            ?>

                                            <?php 
                                            // Display a message if neither result set has rows
                                            if ((!$usage_result || $usage_result->num_rows === 0) && (!$audit_result || $audit_result->num_rows === 0)) {
                                                echo '<tr><td colspan="5" class="text-center text-gray-500 py-8">No inventory actions or usage recorded.</td></tr>';
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </main>
    </div>
</body>

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
    
    console.log('PIAIMS Dashboard initialized successfully!');
    });

    function updateDateTime() {
        const now = new Date();

        // Format date and time
        const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };

        document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', timeOptions);
    }

    // Initial call and auto-update every second
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Page Navigation Logic
    const buttons = document.querySelectorAll('.page-btn');
    const sections = document.querySelectorAll('.page-section');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
        // Reset all
        buttons.forEach(b => b.classList.remove('active-btn'));
        sections.forEach(s => s.classList.add('hidden'));

        // Activate selected
        btn.classList.add('active-btn');
        document.getElementById(btn.dataset.target).classList.remove('hidden');
        });
    });
    
    // Clinic Visits Table Filtering Logic
    const searchInput = document.getElementById('studentSearch');
    const entriesFilter = document.getElementById('entriesFilter');
    const clinicDateFilter = document.getElementById('clinicDateFilter'); // Corrected ID
    const staffFilter = document.getElementById('staffFilter');
    const clinicTable = document.getElementById('clinicTable');
    const tableBody = clinicTable ? clinicTable.getElementsByTagName('tbody')[0] : null;

    if (tableBody) {
        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            const selectedDate = clinicDateFilter.value; 
            const selectedStaff = staffFilter.value;

            const rows = tableBody.getElementsByTagName('tr');
            let visibleCount = 0;
            const maxEntries = parseInt(entriesFilter.value);

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                // Check if the row contains a data-date attribute (to skip "No recent visits" row)
                if (!row.dataset.date) {
                     row.style.display = 'table-row'; // Always show non-data rows
                     continue;
                }

                // Get data from data-attributes (set in PHP) and cell content
                const studentName = row.cells[3].textContent.toLowerCase();
                const studentID = row.cells[2].textContent.toLowerCase();
                const rowStaff = row.dataset.staff; 
                const rowDate = new Date(row.dataset.date); 

                // Search filter: Checks student name and ID
                let matchSearch = studentName.includes(searchText) || studentID.includes(searchText);

                // Staff filter
                let matchStaff = (selectedStaff === 'all' || rowStaff === selectedStaff);

                // Date filter
                let matchDate = false;
                const today = new Date();
                today.setHours(0, 0, 0, 0); // Normalize to start of day

                const oneWeekAgo = new Date(today); oneWeekAgo.setDate(today.getDate() - 7);
                const oneMonthAgo = new Date(today); oneMonthAgo.setMonth(today.getMonth() - 1);

                if(selectedDate === 'all') matchDate = true;
                else if(selectedDate === 'today') {
                    // Check if the row date matches today's normalized date
                    matchDate = rowDate.toDateString() === today.toDateString();
                } else if(selectedDate === 'week') {
                    // Check if the row date is within the last 7 days
                    matchDate = rowDate >= oneWeekAgo;
                } else if(selectedDate === 'month') {
                    // Check if the row date is within the last 30 days
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
        clinicDateFilter.addEventListener('change', filterTable); 
        staffFilter.addEventListener('change', filterTable);

        // Initial filter
        filterTable();
    }


    // --- Chart.js Logic ---

    // Register the datalabels plugin
    Chart.register(ChartDataLabels);

    // Wait for the document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- VISIT REASONS PIE CHART LOGIC ---
        const visitReasonsChartContainer = document.querySelector('.chart-container');
        
        // Initial setup for loading state
        visitReasonsChartContainer.innerHTML = `
            <div class="chart-loading" style="height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                <div class="spinner" style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 10px;"></div>
                <p class="text-gray-500 text-sm">Loading visit data...</p>
            </div>
            <canvas id="visitReasonsChart" style="display: none;"></canvas>
        `;
        
        // Fetch visit reasons data from the server
        fetch('../Functions/patientFunctions.php?action=getVisitReasons')
            .then(response => response.json())
            .then(data => {
                const chartElement = document.querySelector('#visitReasonsChart');
                const loadingElement = document.querySelector('.chart-loading');

                if (data.success && data.reasons.length > 0) {
                    const reasons = data.reasons;
                    
                    // Prepare data for the chart
                    const labels = reasons.map(item => item.reason);
                    const counts = reasons.map(item => item.count);
                    const totalVisits = counts.reduce((a, b) => a + b, 0);
                    
                    // Generate colors
                    const { backgroundColors } = generateGradientColors(labels.length);
                    
                    // Show canvas and hide loading
                    chartElement.style.display = 'block';
                    loadingElement.style.display = 'none';
                    
                    const ctx = chartElement.getContext('2d');
                    
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
                                padding: 10
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
                                            family: 'Poppins, sans-serif',
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(33, 37, 41, 0.95)',
                                    titleFont: { size: 13, weight: '600' },
                                    bodyFont: { size: 13 },
                                    padding: 12,
                                    cornerRadius: 8,
                                    displayColors: true,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const percentage = ((value / totalVisits) * 100).toFixed(1);
                                            return `${label}: ${value} (${percentage}%)`;
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
                                        family: 'Poppins, sans-serif',
                                        size: 11,
                                        weight: '600'
                                    },
                                    textAlign: 'center',
                                    textShadowColor: 'rgba(0,0,0,0.4)',
                                    textShadowBlur: 4
                                }
                            }
                        }
                    });
                    
                    // Add total visits badge
                    const cardHeader = document.querySelector('.card-count');
                    const existingBadge = document.querySelector('.total-visits-badge');
                    if(existingBadge) existingBadge.remove(); // Prevent duplication
                    
                    const badge = document.createElement('div');
                    badge.className = 'total-visits-badge bg-green-200 text-green-800 font-semibold px-3 py-1 rounded-full text-sm shadow-inner';
                    badge.innerHTML = `
                        <span class="total-count text-lg">${totalVisits}</span>
                        <span class="total-label text-xs">Total Visits</span>
                    `;
                    cardHeader.appendChild(badge);
                    
                } else {
                    // Show no data message
                    const message = data.success ? 'No visit data available for plotting.' : 'Failed to load visit data.';
                    loadingElement.innerHTML = `
                        <i class='bx bx-error-circle text-4xl text-gray-400 mb-2'></i>
                        <p class="text-gray-500">${message}</p>
                    `;
                    loadingElement.style.display = 'flex';
                }
            })
            .catch(error => {
                console.error('Error fetching visit reasons:', error);
                document.querySelector('.chart-loading').innerHTML = `
                    <i class='bx bx-server-Downtime text-4xl text-red-500 mb-2'></i>
                    <p class="text-red-500">Error loading data. Check console.</p>
                `;
                document.querySelector('.chart-loading').style.display = 'flex';
            });
        
        // Function to generate distinct colors for the chart
        function generateGradientColors(count) {
            const colorPalette = [
                { start: 'rgb(52, 152, 219)', end: 'rgb(41, 128, 185)' }, // Blue
                { start: 'rgb(46, 204, 113)', end: 'rgb(39, 174, 96)' }, // Green
                { start: 'rgb(155, 89, 182)', end: 'rgb(142, 68, 173)' }, // Purple
                { start: 'rgb(241, 196, 15)', end: 'rgb(243, 156, 18)' }, // Yellow
                { start: 'rgb(230, 126, 34)', end: 'rgb(211, 84, 0)' }, // Orange
                { start: 'rgb(231, 76, 60)', end: 'rgb(192, 57, 43)' }, // Red
                { start: 'rgb(26, 188, 156)', end: 'rgb(22, 160, 133)' }, // Turquoise
                { start: 'rgb(52, 73, 94)', end: 'rgb(44, 62, 80)' }, // Dark
            ];
            
            const backgroundColors = [];
            for (let i = 0; i < count; i++) {
                backgroundColors.push(colorPalette[i % colorPalette.length]);
            }
            return { backgroundColors };
        }

        // --- TOP USED MEDICINES BAR CHART LOGIC ---
        
        const ctx = document.getElementById("medicineBarChart").getContext("2d");
        
        // Initial Chart instance setup
        let medicineChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: [],
                datasets: [{
                    label: "Quantity Used",
                    data: [],
                    backgroundColor: [],
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { 
                        grid: { display: false }, 
                        ticks: { color: "#6b7280", font: { size: 12 } } 
                    },
                    y: { 
                        beginAtZero: true, 
                        grid: { color: "#e5e7eb" }, 
                        ticks: { color: "#6b7280", stepSize: 10 } 
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { 
                        backgroundColor: "#1f2937", 
                        titleColor: "#fff", 
                        bodyColor: "#d1d5db", 
                        padding: 10, 
                        cornerRadius: 8,
                        callbacks: {
                             label: function(context) {
                                return ` Quantity Used: ${context.parsed.y}`;
                            }
                        }
                    }
                }
            }
        });

        const colorMap = ["#22c55e", "#0ea5e9", "#f97316", "#a855f7", "#ec4899", "#84cc16", "#ef4444", "#eab308"];

        function fetchChartData(filterType = "all", date = "") {
            // Updated URL to point to the correct file (assuming it's named chart_data.php from the previous context)
            const url = `../api/chart_data.php?filter=${filterType}&date=${encodeURIComponent(date)}`;

            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    // Check for PHP/SQL error
                    if (data.error) {
                        console.error('Backend Error:', data.error, data.sql_error, data.query);
                        medicineChart.data.labels = ['Error Loading Data'];
                        medicineChart.data.datasets[0].data = [1];
                        medicineChart.data.datasets[0].backgroundColor = ['#ef4444'];
                    } else {
                        medicineChart.data.labels = data.labels || [];
                        medicineChart.data.datasets[0].data = data.values || [];
                        medicineChart.data.datasets[0].backgroundColor = (data.values || []).map((v, i) => colorMap[i % colorMap.length]);
                    }
                    
                    medicineChart.update();
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                    medicineChart.data.labels = ['Network Error'];
                    medicineChart.data.datasets[0].data = [1];
                    medicineChart.data.datasets[0].backgroundColor = ['#9ca3af'];
                    medicineChart.update();
                });
        }

        // FIX: Initial load uses 'all' filter
        fetchChartData("all");

        // FIX: Use the correct, unique ID for the chart filter
        const chartDateFilter = document.getElementById("chartDateFilter"); 
        const specificDate = document.getElementById("specificDate");
        
        chartDateFilter.addEventListener("change", () => {
            const selectedFilter = chartDateFilter.value;
            specificDate.classList.add("hidden");

            if (selectedFilter === "day") {
                specificDate.classList.remove("hidden");
                // Fetch with default value if empty
                fetchChartData("day", specificDate.value || new Date().toISOString().slice(0, 10));
            } else {
                fetchChartData(selectedFilter);
            }
        });

        specificDate.addEventListener("change", () => {
            fetchChartData("day", specificDate.value);
        });

    });
</script>

</html>
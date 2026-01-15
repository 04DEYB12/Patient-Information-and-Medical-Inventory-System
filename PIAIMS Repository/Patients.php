<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>window.location.href = '../components/Error401.php';</script>";
    exit();
}

$user_id = $_SESSION['User_ID'];
require_once '../Functions/Queries.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIAIMS | Patients / Students</title>
    <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../Stylesheet/Design.css">
        <script src="../Functions/scripts.js"></script>
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: modalFadeIn 0.3s;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
        }

        /* Form Styling */
        .form-section {
            margin-bottom: 1.5rem;
            background: #ffffff;
            padding: 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #4b5563;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #4a6cf7;
            box-shadow: 0 0 0 2px rgba(74, 108, 247, 0.2);
        }

        textarea.form-input {
            min-height: 80px;
            resize: vertical;
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1em;
            padding-right: 2.5rem;
        }

        .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            border: none;
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn-primary {
            background-color: #4a6cf7;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3b5bdb;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animation */
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Custom Scrollbar */
        #profileContent::-webkit-scrollbar {
            width: 8px;
        }
        #profileContent::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        #profileContent::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        #profileContent::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Profile Card Styles */
        .profile-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .profile-card h3 {
            color: #4a6cf7;
            font-size: 1.1rem;
            margin-top: 0;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f1f5ff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .profile-card h3 i {
            font-size: 1.2em;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.25rem;
        }
        
        .info-item {
            margin-bottom: 0.75rem;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
            display: block;
            font-weight: 500;
        }
        
        .info-value {
            font-size: 1rem;
            color: #2d3748;
            font-weight: 500;
            word-break: break-word;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-content {
                margin: 1rem auto;
                padding: 1.5rem;
                width: 95%;
            }
            
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            gap: 0.5rem;
        }
        
        .btn-check-in {
            background-color: #10B981;
            color: white;
        }
        
        .btn-check-in:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }
        
        .btn-view-record {
            background-color: #3B82F6;
            color: white;
        }
        
        .btn-view-record:hover {
            background-color: #2563EB;
            transform: translateY(-1px);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .patient-header {
            display: flex;
            width: 100%;
            flex-direction: column;
            align-items: start;
            justify-content: start;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .patient-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .patient-basic-info {
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
        
        .tab-btn {
            padding: 0.6rem 0.5rem;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            font-weight: 500;
            color: #6b7280; /* gray-500 */
            transition: all 0.25s ease;
            font-size: 15px;
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 100%;
            justify-content: space-around;
            gap: 0.5rem;
            letter-spacing: 0.2px;
        }

        /* Hover effect */
        .tab-btn:hover {
            color: #2563eb; /* blue-600 */
        }

        /* Active tab */
        .tab-btn.active {
            color: #1d4ed8; /* blue-700 */
            border-bottom-color: #1d4ed8;
            font-weight: 600;
        }

        /* Focus ring */
        .tab-btn:focus {
            outline: none;
        }

        @keyframes heartbeat {
        0%, 100% {
            transform: scale(1);
        }
        14% {
            transform: scale(1.3);
        }
        28% {
            transform: scale(1);
        }
        42% {
            transform: scale(1.3);
        }
        70% {
            transform: scale(1);
        }
        }

        .animate-heartbeat {
        animation: heartbeat 1.5s ease-in-out infinite;
        }

        .info-label {
            margin: 0 0 0.25rem 0;
            font-size: 0.875rem;
            color: #6b7280;
        }
        .info-value {
            margin: 0;
            font-weight: 500;
            color: #1f2937;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .editable-field {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            width: 100%;
            box-sizing: border-box;
        }
        .editable-field:focus {
            outline: none;
            border-color: #4a6cf7;
            box-shadow: 0 0 0 2px rgba(74, 108, 247, 0.2);
        }
        .view-mode { display: block; }
        .edit-mode { display: none; }
        
        .patient-header {
            background-image: url("../Images/studentcardbg.jpg");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include '../components/sidebar.php'; ?>
        
        <main class="main-content">
            <!-- Header -->
            <header class="main-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class='bx bx-menu'></i>
                </button>
                <h1 id="pageTitle" style="color: #002e2d;">PATIENT / STUDENT INFORMATIONS</h1>
            </header>
            
            <!-- Contents -->
            <div class="content-container">
                <section class="content-section active" id="patientsSection">
                    <div class="bg-white shadow-md rounded-xl overflow-hidden">
                        <!-- Header -->
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 px-6 py-4 border-b bg-gray-50">
                            <h2 class="text-xl font-semibold text-gray-800">🎓 <?php echo $student_count; ?> Student Records</h2>

                            <div class="flex flex-wrap gap-4 items-center">
                            <!-- Search -->
                            <div class="relative">
                                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                <input id="searchInput1" type="text" placeholder="Search by ID or Name..."
                                onkeyup="filterStudentCards()"
                                class="pl-10 pr-10 py-2 border rounded-lg w-72 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <i id="clearSearch1" class='bx bx-brush-alt absolute right-3 top-1/2 -translate-y-1/2 text-gray-700 cursor-pointer hidden'
                                onclick="clearSearch1()"></i>
                            </div>
                            
                            <!-- filter button -->
                            <div class="relative">
                                <button id="filterBtn" onclick="filterbutton()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                                    <i class='bx bx-filter text-lg'></i> <p class="hidden sm:flex">Filter</p>
                                </button>
                            </div>

                            <!-- Add New Student -->
                            <button id="addStudentBtn"
                                onclick="openModal('addStudentModal')"
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                                <i class='bx bx-plus text-lg'></i> <p class="hidden sm:flex">Add New Student</p>
                            </button>

                            <!-- Entries Filter -->
                            <div class="flex items-center gap-2 text-sm text-gray-900 p-2 bg-gray-200 rounded-lg px-4">
                                <span>Show</span>
                                <select id="entriesFilter"
                                    class="px-2 py-1 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="5">5</option>
                                    <option value="10" selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span>entries</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter -->
                    <div id="filter" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 p-4 bg-white rounded-lg shadow-sm mb-4 hidden">
                        <!-- Department Filter -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-500">
                                <i class='bx bx-buildings text-xl'></i>
                            </div>
                            <select id="departmentFilter" onchange="updateGradeLevelOptions(this.value, 'gradeFilter')" class="pl-10 pr-3 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-colors">
                                <option value="All">All Departments</option>
                                <option value="Elementary">Elementary</option>
                                <option value="Junior Highschool">Junior Highschool</option>
                                <option value="Senior Highschool">Senior Highschool</option>
                                <option value="College">College</option>
                            </select>
                        </div>
                        
                        <!-- Grade Filter -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-500">
                                <i class='bx bx-layer text-xl'></i>
                            </div>
                            <select id="gradeFilter" onchange="filterbyGradeLevel(this.value)" class="pl-10 pr-3 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-colors">
                                <option value="All">All Grades</option>
                            </select>
                        </div>
                        
                        <!-- Section Filter -->
                        <!-- <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-500">
                                <i class='bx bx-group text-xl'></i>
                            </div>
                            <select id="Section" class="pl-10 pr-3 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-colors">
                                <option value="">All Sections</option>
                            </select>
                        </div> -->
                        
                        <!-- Account Status Filter -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-500">
                                <i class='bx bx-user-check text-xl'></i>
                            </div>
                            <select id="AccountStatus" onchange="filterbyAccountStatus(this.value)" class="pl-10 pr-3 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-colors">
                                <option value="All">All Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <!-- Visit Status Filter -->
                        <!-- <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-500">
                                <i class='bx bx-calendar-check text-xl'></i>
                            </div>
                            <select id="VisitStatus" class="pl-10 pr-3 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-colors">
                                <option value="">Visit Status</option>
                                <option value="Inprogress">Inprogress</option>
                                <option value="Completed">Completed</option>
                                <option value="Follow-up">Follow up</option>
                                <option value="Lapsed">Lapsed</option>
                            </select>
                        </div> -->
                    </div>

                    <!-- Content -->
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 bg-white gap-2"> 
                        <div id="no-results-message1" class="hidden text-center text-gray-500 py-6">
                        No matching students found
                        </div>

                            <?php
                            $student_list_stmt = mysqli_prepare($con, "SELECT * FROM student");
                            if ($student_list_stmt) {
                                mysqli_stmt_execute($student_list_stmt);
                                $result = mysqli_stmt_get_result($student_list_stmt);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($student_row = mysqli_fetch_assoc($result)) {
                                        $first_initial = !empty($student_row['FirstName'][0]) ? $student_row['FirstName'][0] : '';
                                        $last_initial = !empty($student_row['LastName'][0]) ? $student_row['LastName'][0] : '';
                                        $student_name = $student_row['FirstName'] . ' ' . $student_row['LastName'];
                                        $student_id = $student_row['School_ID'];
                                        $student_section = $student_row['Section'];
                                        $student_email = $student_row['StudentEmailAddress'];
                                        $student_year = $student_row['Department'] === 'College' ? preg_replace('/[^0-9]/', '', $student_row['GradeLevel']) : $student_row['GradeLevel']; 
                                        $student_status = $student_row['Status'];
                                        $color = $student_status === 'Active' ? 'green' : 'slate';
                                        $text_color = $student_status === 'Active' ? 'green' : 'red';
                                        
                                        $with_Caution = !empty($student_row['KnownAllergies']) || 
                                                        !empty($student_row['ChronicConditions']) || 
                                                        !empty($student_row['CurrentMedications']);

                                        echo "
                                        <div class='flex mt-2 flex-col w-full max-w-[360px] h-[280px] flex-row items-center justify-between rounded-lg shadow-sm hover:shadow-md transition bg-white border-[1px] border-gray-300 shadow-lg patient-card rounded-xl'
                                        data-patient-name='{$student_name}' data-patient-id='{$student_id}' data-patient-department='{$student_row['Department']}' data-patient-grade='{$student_year}' data-patient-section='{$student_section}' data-patient-status='{$student_status}'>
                                        <div class='patient-header w-full p-2 px-4 flex justify-start' style='border-radius: 10px 10px 0px 0px;'>
                                            <div class='flex items-start gap-4 w-full'>
                                                <div class='flex items-center justify-center bg-[#ffffffd7] text-blue-400 rounded-lg shasow-lg font-bold text-2xl' style='height: 50px; width: 50px;'>
                                                    {$first_initial}
                                                </div>
                                                <div>
                                                    <h3 class='font-semibold text-white'>{$student_name}</h3>
                                                    <div style='display: flex; align-items: center; gap: 8px; margin-top: 4px;'>
                                                        <p class='text-sm text-white'>Student</p>
                                                        <p class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-".$text_color."-100 text-".$text_color."-800 '>{$student_status}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='flex flex-row gap-2'>
                                                <button onclick=\"openCheckInModal('{$student_id}')\" title='Check Up' ".($student_status === 'Active' ? '' : 'style="cursor: not-allowed;" disabled')."
                                                    class='px-3 py-2 bg-".$color."-600 text-white rounded-lg hover:bg-".$color."-700 text-sm flex items-center gap-1'>
                                                    <i class=\"bx bx-user-check\"></i> Check Up
                                                </button>
                                                <button onclick=\"openViewModal('{$student_id}')\" title='View Profile' 
                                                    class='px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm flex items-center gap-1'>
                                                    <div class=\"relative\">
                                                        <i class=\"bx bx-user\"></i>Profile
                                                        <span id='allergyDot_atUsercards' class='absolute -top-4 -right-2 bg-red-600 h-4 w-4 flex items-center justify-center text-[10px] text-white font-bold rounded-full animate-heartbeat " . ($with_Caution ? '' : 'hidden') . "'>!</span>
                                                    </div>
                                                </button>
                                                    <button onclick=\"viewStudentRecord('{$student_id}')\" title='View Record' 
                                                    class='px-3 py-2 bg-teal-700 text-white rounded-lg hover:bg-teal-800 text-sm flex items-center gap-1'>
                                                    <i class=\"bx bxs-report\"></i>Record
                                                </button>
                                            </div>
                                        </div>
                                        <div class='w-full p-4 flex flex-col items-start justify-center'>
                                            <span class='text-md font-semibold mb-2'>Basic Info:</span>
                                            <p class='text-sm text-gray-700'>ID: {$student_id}</p>
                                            <p class='text-sm text-gray-700'>Age: {$student_row['Age']}</p>
                                            <p class='text-sm text-gray-700'>Department: {$student_row['Department']}</p>
                                            <p class='text-sm text-gray-700'>" . ($student_row['Department'] === 'College' ? 'Year' : 'Grade') . " and Section: {$student_year} - {$student_section}</p>                                            <p class='text-sm text-gray-700'>Email: {$student_email}</p>
                                        </div>
                                    </div>";
                                    }
                                }
                            }
                            ?>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-between items-center px-6 py-4 border-t bg-gray-50 text-sm text-gray-600">
                                <div>
                                Showing <span id="startCount">1</span> to <span id="endCount">10</span> of <span id="totalCount">0</span> entries
                                </div>
                            <div class="flex gap-2 items-center">
                            <button class="px-2 py-1 rounded border bg-white text-gray-600 hover:bg-gray-100 disabled:opacity-50" data-page="first" disabled>
                                <i class='bx bx-chevrons-left'></i>
                            </button>
                            <button class="px-2 py-1 rounded border bg-white text-gray-600 hover:bg-gray-100 disabled:opacity-50" data-page="prev" disabled>
                                <i class='bx bx-chevron-left'></i>
                            </button>
                            <div class="page-numbers flex gap-1">
                                <!-- Page numbers will be inserted by JS -->
                            </div>
                            <button class="px-2 py-1 rounded border bg-white text-gray-600 hover:bg-gray-100" data-page="next">
                                <i class='bx bx-chevron-right'></i>
                            </button>
                            <button class="px-2 py-1 rounded border bg-white text-gray-600 hover:bg-gray-100" data-page="last">
                                <i class='bx bx-chevrons-right'></i>
                            </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

    
    
<script>
    // Clear Search1
    function clearSearch1() {
        const searchInput = document.getElementById('searchInput1');
        searchInput.value = '';
        searchInput.focus();
        filterStudentCards();
        document.getElementById('clearSearch1').style.display = 'none';
    }
    
    // Clear Search Button
    document.getElementById('searchInput1').addEventListener('input', function() {
        const clearBtn = document.getElementById('clearSearch1');
        clearBtn.style.display = this.value ? 'block' : 'none';
        
        
        
        
        // let AlreadyScanned = this.value ? true : false;
        // if(AlreadyScanned){document.getElementById('searchInput1').value = '';}
        
        // function extractLastGC(searchInput){
        //     const matches = searchInput.match(/GC-\d+/g);
            
        //     if(matches && matches.length > 0){
        //         return matches[matches.length -1];
        //     }
            
        //     return '';
        // }
        // GC-220708
        
        // const searchInputValue = document.getElementById('searchInput1').value;
        // const newValue = extractLastGC(searchInputValue);
        // document.getElementById('searchInput1').value = newValue;
        
        
        // // if(searchInput.length >= 9){
        // //     let newValue;
        // //     for(let i=searchInput.length-8; i<= searchInput.length; i++ ){
        // //         newValue +=  searchInput.value[i];
        // //     }
        // //     searchInput.value = newValue;
        // // }
    });

    // Validate School ID
    function validateSchoolId(input) {
        const pattern = /^(GE|GJ|GS|GC)-[0-9A-Za-z]{1,6}$/;
        const errorElement = document.getElementById('schoolIdError');
        
        if (input.value.length > 0) {  // Only validate if there's input
            const isValid = pattern.test(input.value);
            if (!isValid) {
                errorElement.textContent = 'School ID must start with GE-, GJ-, GS-, or GC- followed by 1-6 alphanumeric characters';
                errorElement.style.display = 'block';
            } else {
                errorElement.style.display = 'none';
            }
            input.setCustomValidity(isValid ? '' : 'Invalid format');
            return isValid;
        } else {
            errorElement.style.display = 'none';
            input.setCustomValidity(''); // Clear any previous validation message
            return false;
        }
    }
    
    // Validate Contact Number
    function validateContactNumber(input, errorElementId) {
        const errorElement = document.getElementById(errorElementId);
        input.value = input.value.replace(/[^0-9]/g, '').slice(0, 11);
        
        if (input.value.length > 0) {
            const isValid = /^09[0-9]{9}$/.test(input.value);
            if (!isValid) {
                errorElement.textContent = 'Please enter a valid 11-digit number starting with 09';
                errorElement.style.display = 'block';
            } else {
                errorElement.style.display = 'none';
            }
            input.setCustomValidity(isValid ? '' : 'Invalid format');
            return isValid;
        } else {
            errorElement.style.display = 'none';
            input.setCustomValidity('');
            return false;
        }
    }

    // Initialize pagination
    document.addEventListener('DOMContentLoaded', function() {
    
        let itemsPerPage = 10;
        let currentPage = 1;
        let patientCards = Array.from(document.querySelectorAll('.patient-card'));
        let totalItems = patientCards.length;
        let totalPages = Math.ceil(totalItems / itemsPerPage);

        // Init
        updatePagination();

        // Entries per page change
        document.getElementById('entriesFilter').addEventListener('change', function () {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            updatePagination();
        });

        // Navigation buttons
        document.querySelectorAll('[data-page]').forEach(btn => {
            btn.classList.add('pagination-btn');
            btn.addEventListener('click', function() {
                const action = this.dataset.page;
                switch(action) {
                    case 'first': currentPage = 1; break;
                    case 'prev': if(currentPage > 1) currentPage--; break;
                    case 'next': if(currentPage < totalPages) currentPage++; break;
                    case 'last': currentPage = totalPages; break;
                }
                showPage(currentPage);
                updatePaginationInfo();
                updatePaginationButtons();
            });
        });

        function updatePaginationInfo() {
            const start = ((currentPage - 1) * itemsPerPage) + 1;
            const end = Math.min(currentPage * itemsPerPage, totalItems);
            document.getElementById('startCount').textContent = totalItems ? start : 0;
            document.getElementById('endCount').textContent = totalItems ? end : 0;
            document.getElementById('totalCount').textContent = totalItems;
        }

        function updatePaginationButtons() {
            document.querySelector('[data-page="first"]').disabled = currentPage === 1;
            document.querySelector('[data-page="prev"]').disabled = currentPage === 1;
            document.querySelector('[data-page="next"]').disabled = currentPage === totalPages;
            document.querySelector('[data-page="last"]').disabled = currentPage === totalPages;

            const pageNumbers = document.querySelector('.page-numbers');
            pageNumbers.innerHTML = '';

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            if (startPage > 1) {
                pageNumbers.appendChild(createPageButton(1));
                if (startPage > 2) pageNumbers.appendChild(createEllipsis());
            }

            for (let i = startPage; i <= endPage; i++) {
                const btn = createPageButton(i);
                if (i === currentPage) {
                    btn.classList.add('bg-blue-600', 'text-white');
                }
                pageNumbers.appendChild(btn);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) pageNumbers.appendChild(createEllipsis());
                pageNumbers.appendChild(createPageButton(totalPages));
            }
        }

        function createPageButton(pageNum) {
            const btn = document.createElement('button');
            btn.textContent = pageNum;
            btn.className = 'px-2 py-1 border rounded';
            btn.addEventListener('click', () => {
                currentPage = pageNum;
                showPage(currentPage);
                updatePaginationInfo();
                updatePaginationButtons();
            });
            return btn;
        }

        function createEllipsis() {
            const span = document.createElement('span');
            span.textContent = '...';
            span.className = 'px-2';
            return span;
        }

        function showPage(pageNum) {
            const startIndex = (pageNum - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            patientCards.forEach((card, index) => {
                card.style.display = (index >= startIndex && index < endIndex) ? 'flex' : 'none';
            });
        }

        function updatePagination() {
            patientCards = Array.from(document.querySelectorAll('.patient-card'))
                .filter(card => card.style.display !== 'none'); // only visible cards
            totalItems = patientCards.length;
            totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
            currentPage = Math.min(currentPage, totalPages);
            updatePaginationInfo();
            updatePaginationButtons();
            showPage(currentPage);
        }

        // 🔎 Search filter
        window.filterStudentCards = function() {
            const input = document.getElementById('searchInput1').value.toLowerCase();
            const cards = document.querySelectorAll('.patient-card');
            const noResultsMsg = document.getElementById('no-results-message1');
            let hasVisibleCards = false;

            cards.forEach(card => {
                const name = (card.getAttribute('data-patient-name') || '').toLowerCase();
                const id = (card.getAttribute('data-patient-id') || '').toLowerCase();
                const isMatch = name.includes(input) || id.includes(input);
                card.style.display = isMatch ? 'flex' : 'none';
                if (isMatch) hasVisibleCards = true;
            });

            noResultsMsg.style.display = hasVisibleCards ? 'none' : 'block';
            updatePagination();
        };
    });
    
    // Open modal
    function openModal(modal) {
        document.getElementById(modal).style.display = 'block';
    }
    
    // Close modal
    function closeModal(modal) {
        const modalElement = document.getElementById(modal);
        if (modalElement) {
            // If in edit mode, switch back to view mode
            const editBtn = document.getElementById('editStudentBtn');
            if (editBtn && editBtn.style.display === 'none') {
                toggleEditMode(false);
            }
            // Close the modal
            modalElement.style.display = 'none';
        }
    }
    
    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modalIds = [
            "addStudentModal",
            "viewStudentModal",
            "checkInModal",
            "RecordModal",
            "EditRecordModal",
            "ViewRecordModal"
        ];

        modalIds.forEach(id => {
            const modal = document.getElementById(id);
            if (modal && event.target === modal) {
                // Check if we're in edit mode before closing
                const isEditMode = document.getElementById('editStudentBtn').style.display === 'none';
                if (isEditMode) {
                    document.getElementById('editDepartment').selectedIndex = 0;
                    document.getElementById('editGradeLevel').disabled = true;
                    document.getElementById('editGradeLevel').innerHTML = '<option value="">Select Department first</option>';
                    toggleEditMode(false);  // Switch back to view mode
                    modal.style.display = "none";
                } else {
                    modal.style.display = "none";
                }
            }
        });
    };
    
    // Function to update grade level options based on selected department
    function updateGradeLevelOptions(department, gradeLevelId) {
        
        const gradeLevelSelect = document.getElementById(gradeLevelId);
        
        // Clear existing options
        gradeLevelSelect.innerHTML = '';
        
        // Define grade levels based on department
        let gradeLevels = [];
        
        switch(department) {
            case 'Elementary':
                gradeLevels = Array.from({length: 6}, (_, i) => ({
                    value: `Grade ${i + 1}`,
                    text: `Grade ${i + 1}`
                }));
                break;
            case 'Junior Highschool':
                gradeLevels = Array.from({length: 4}, (_, i) => ({
                    value: `Grade ${i + 7}`,
                    text: `Grade ${i + 7}`
                }));
                break;
            case 'Senior Highschool':
                gradeLevels = Array.from({length: 2}, (_, i) => ({
                    value: `Grade ${i + 11}`,
                    text: `Grade ${i + 11}`
                }));
                break;
            case 'College':
                gradeLevels = [
                    { value: 'Irregular', text: 'Irregular' },
                    ...Array.from({length: 4}, (_, i) => ({
                        value: `Year ${i + 1}`,
                        text: `Year ${i + 1}`
                    }))
                ];
                break;
            default:
                gradeLevelSelect.disabled = true;
                break;
        }
        
        // Add options to select
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Grade Level';
        gradeLevelSelect.appendChild(defaultOption);
        
        gradeLevels.forEach(level => {
            const option = document.createElement('option');
            option.value = level.value;
            option.textContent = level.text;
            gradeLevelSelect.appendChild(option);
        });
        
        gradeLevelSelect.disabled = false;
        
        const cards = document.querySelectorAll('.patient-card');
        const noResultsMsg = document.getElementById('no-results-message1');
        let hasVisibleCards = false;

        cards.forEach(card => {
            const dept = card.getAttribute('data-patient-department');
            const isMatch = department == "All" || dept.includes(department);
            card.style.display = isMatch ? 'flex' : 'none';
            if (isMatch) hasVisibleCards = true;
        });
        
        noResultsMsg.style.display = hasVisibleCards ? 'none' : 'block';
    }
    
    function filterbyGradeLevel(gradeLevel) {
        // If "All" is selected, show all cards
        if (gradeLevel === "All") {
            document.querySelectorAll('.patient-card').forEach(card => {
                card.style.display = 'flex';
            });
            document.getElementById('no-results-message1').style.display = 'none';
            return;
        }

        // For "Year X" format, extract just the number
        const gradeToMatch = gradeLevel.startsWith('Year ') ? gradeLevel.replace('Year ', '') : gradeLevel;
        
        const cards = document.querySelectorAll('.patient-card');
        const noResultsMsg = document.getElementById('no-results-message1');
        let hasVisibleCards = false;

        cards.forEach(card => {
            const grade = card.getAttribute('data-patient-grade');
            // Handle both "Year X" format and direct number matching
            const normalizedGrade = grade.startsWith('Year ') ? grade.replace('Year ', '') : grade;
            const isMatch = normalizedGrade === gradeToMatch;
            card.style.display = isMatch ? 'flex' : 'none';
            if (isMatch) hasVisibleCards = true;
        });
        
        noResultsMsg.style.display = hasVisibleCards ? 'none' : 'block';
        
        
    }
    
    function filterbyAccountStatus(accountStatus) {
        // If "All" is selected, show all cards
        if (accountStatus === "All") {
            document.querySelectorAll('.patient-card').forEach(card => {
                card.style.display = 'flex';
            });
            document.getElementById('no-results-message1').style.display = 'none';
            return;
        }
        
        const cards = document.querySelectorAll('.patient-card');
        const noResultsMsg = document.getElementById('no-results-message1');
        let hasVisibleCards = false;

        cards.forEach(card => {
            const status = card.getAttribute('data-patient-status');
            const isMatch = accountStatus == "All" || status.includes(accountStatus);
            card.style.display = isMatch ? 'flex' : 'none';
            if (isMatch) hasVisibleCards = true;
        });
        
        noResultsMsg.style.display = hasVisibleCards ? 'none' : 'block';
    }
    
    // Department to Grade Level mapping
    const departmentGradeLevels = {
        'Elementary': Array.from({length: 6}, (_, i) => ({
            value: i + 1,
            text: `Grade ${i + 1}`
        })),
        'Junior Highschool': Array.from({length: 4}, (_, i) => ({
            value: i + 7,
            text: `Grade ${i + 7}`
        })),
        'Senior Highschool': Array.from({length: 2}, (_, i) => ({
            value: i + 11,
            text: `Grade ${i + 11}`
        })),
        'College': [
            { value: 'irregular', text: 'Irregular' },
            ...Array.from({length: 4}, (_, i) => ({
                value: `year${i + 1}`,
                text: `Year ${i + 1}`
            }))
        ]
    };

    // Initialize grade level selection
    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('department');
        const gradeLevelSelect = document.getElementById('gradeLevel');
        
        if (departmentSelect && gradeLevelSelect) {
            departmentSelect.addEventListener('change', function() {
                const selectedDept = this.value;
                const gradeLevels = departmentGradeLevels[selectedDept] || [];
                
                gradeLevelSelect.innerHTML = '';
                
                if (gradeLevels.length > 0) {
                    gradeLevels.forEach(level => {
                        const option = document.createElement('option');
                        option.value = level.value;
                        option.textContent = level.text;
                        gradeLevelSelect.appendChild(option);
                    });
                    gradeLevelSelect.disabled = false;
                } else {
                    gradeLevelSelect.disabled = true;
                }
            });
        }
    });
    
    // Calculate age from birth date
    function calculateAge() {
        const birthDate = document.getElementById('birthDate');
        const ageField = document.getElementById('age');
        
        if (birthDate.value) {
            const birthDateObj = new Date(birthDate.value);
            const today = new Date();
            let age = today.getFullYear() - birthDateObj.getFullYear();
            const monthDiff = today.getMonth() - birthDateObj.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
                age--;
            }
            
            ageField.value = age;
        } else {
            ageField.value = '';
        }
    }
    
    // Initialize tabs
    function initializeTabs() {
        console.log('Initializing tabs...');
        const tabs = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // Debug: Log all tabs and their corresponding content
        console.log('Found tabs:', tabs.length);
        console.log('Found tab contents:', tabContents.length);
        
        // Set click handlers for each tab
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab') + 'Tab';
                console.log('Tab clicked:', tabId);
                
                // Remove active class from all buttons and hide all contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked button and show corresponding content
                this.classList.add('active');
                const content = document.getElementById(tabId);
                if (content) {
                    content.classList.add('active');
                    console.log('Showing tab content:', tabId);
                } else {
                    console.error('Tab content not found:', tabId);
                }
            });
        });
        
        // Activate first tab by default if no tab is active
        const activeTabs = document.querySelectorAll('.tab-btn.active');
        if (tabs.length > 0 && activeTabs.length === 0) {
            console.log('Activating first tab by default');
            tabs[0].classList.add('active');
            const firstTabId = tabs[0].getAttribute('data-tab') + 'Tab';
            const firstContent = document.getElementById(firstTabId);
            if (firstContent) {
                firstContent.classList.add('active');
            }
        }
    }
    
    // Function to load student data
    function loadStudentData(studentId) {
        // Show loading state
        console.log('Loading student data for ID:', studentId);
        document.getElementById('studentName').textContent = 'Loading...';
        document.getElementById('studentBasicInfo').textContent = 'ID: ' + studentId;
        
        // Fetch student data via AJAX
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
                    // Update the hidden input value
                    document.getElementById('schoolIDInput').value = student.School_ID;
                    document.getElementById('student_SchoolID').value = student.School_ID;
                    document.getElementById('currentStatus').value = student.Status;
                    
                    document.getElementById('recipientEmail').value = student.GuardianEmailAddress;
                    
                    const statusBtn = document.getElementById('statusStudentBtn');
                    let spanval = "";
                    if (student.Status === 'Active') {
                        statusBtn.classList.remove('bg-red-100', 'text-red-800');
                        statusBtn.classList.add('bg-green-100', 'text-green-800');
                        spanval = "<span class='w-2 h-2 rounded-full bg-green-500 mr-1.5'></span>";
                        document.getElementById('profileLocked').style.display = 'none';
                        document.getElementById('profileButtons').style.display = 'flex';
                    } else {
                        statusBtn.classList.remove('bg-green-100', 'text-green-800');
                        statusBtn.classList.add('bg-red-100', 'text-red-800');
                        spanval = "<span class='w-2 h-2 rounded-full bg-red-500 mr-1.5'></span>";
                        document.getElementById('profileLocked').style.display = 'block';
                        document.getElementById('profileButtons').style.display = 'none';
                        }
                    statusBtn.innerHTML = `${spanval} ${student.Status}`;                   
                    // Update header
                    const firstInitial = student.FirstName ? student.FirstName[0] : '';
                    const lastInitial = student.LastName ? student.LastName[0] : '';
                    document.getElementById('studentAvatar').textContent = firstInitial + lastInitial;
                    document.getElementById('studentName').textContent = `${student.FirstName || ''} ${student.LastName || ''}`.trim();
                    document.getElementById('studentBasicInfo').textContent = `ID: ${student.School_ID || studentId}`;
                    
                    // Update Student Info
                    document.getElementById('schoolID').textContent = student.School_ID || '-';
                    document.getElementById('Department').textContent = student.Department || '-';
                    document.getElementById('GradeLevel').textContent = student.GradeLevel || '-';
                    document.getElementById('Section').textContent = student.Section || '-';
                    
                    // Update personal info
                    document.getElementById('studentFirstName').textContent = student.FirstName || '-';
                    document.getElementById('studentMiddleName').textContent = student.MiddleName || '-';
                    document.getElementById('studentLastName').textContent = student.LastName || '-';
                    document.getElementById('Age').textContent = student.Age || '-';
                    document.getElementById('Gender').textContent = student.Gender || '-';
                    document.getElementById('Birthdate').textContent = student.DateOfBirth || '-';
                    document.getElementById('Address').textContent = student.Address || '-';
                    document.getElementById('ContactNumber').textContent = student.StudentContactNumber || '-';
                    document.getElementById('StudentEmailAddress').textContent = student.StudentEmailAddress || '-';
                    
                    // Update guardian info
                    document.getElementById('GuardianFirstName').textContent = student.GuardianFirstName || '-';
                    document.getElementById('GuardianLastName').textContent = student.GuardianLastName || '-';
                    document.getElementById('GuardianContactNumber').textContent = student.GuardianContactNumber || '-';
                    document.getElementById('GuardianEmailAddress').textContent = student.GuardianEmailAddress || '-';
                    
                    // Update Emergency Contact
                    document.getElementById('guardianName').textContent = student.EmergencyContactName || '-';
                    document.getElementById('guardianRelationship').textContent = student.EmergencyContactRelation || '-';
                    document.getElementById('emergencyContactNumber').textContent = student.EmergencyContactNumber || '-';
                    
                    // Update medical info
                    document.getElementById('BloodType').textContent = student.BloodType || '-';
                    
                    // Known Allergies + heartbeat dot
                    const knownAllergiesEl = document.getElementById('KnownAllergies');
                    const allergyDot = document.getElementById('allergyDot');

                    knownAllergiesEl.textContent = student.KnownAllergies || 'None';
                    if (student.KnownAllergies && student.KnownAllergies !== 'None') {
                        allergyDot.classList.remove('hidden');
                        allergyDot.classList.add('animate-heartbeat');
                    } else {
                        allergyDot.classList.add('hidden');
                        allergyDot.classList.remove('animate-heartbeat');
                    }
                    
                    document.getElementById('ChronicConditions').textContent = student.ChronicConditions || 'None';
                    document.getElementById('CurrentMedication').textContent = student.CurrentMedications || 'None';
                    
                } else {
                    alert('Failed to load student data: ' + (data.message || 'Unknown error'));
                    closeModal('viewStudentModal');
                }
            })
            .catch(error => {
                console.error('Error loading student data:', error);
                alert('Failed to load student data. Please try again.');
                closeModal('viewStudentModal');
            });
    }
    
    // Function to open view modal with student ID
    function openViewModal(studentId) {
        // Open the modal
        openModal('viewStudentModal');
        
        // Reset all tabs and contents
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        // Initialize tabs
        setTimeout(() => {
            initializeTabs();
            // Load student data after a small delay to ensure DOM is ready
            loadStudentData(studentId);
            // initEditMode(studentId);
        }, 10);
    }

    let currentStudentId = null;

    // Toggle between view and edit modes
    function toggleEditMode(enableEdit) {
        const viewModeElements = document.querySelectorAll('.view-mode');
        const editModeElements = document.querySelectorAll('.edit-mode');
        const informGuardianBtn = document.getElementById('informGuardianBtn');
        const editBtn = document.getElementById('editStudentBtn');
        const saveBtn = document.getElementById('saveStudentBtn');
        const cancelBtn = document.getElementById('cancelEditBtn');

        if (enableEdit) {
            // Switch to edit mode
            viewModeElements.forEach(el => el.style.display = 'none');
            editModeElements.forEach(el => el.style.display = 'block');
            informGuardianBtn.style.display = 'none';
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
            cancelBtn.style.display = 'inline-block';
        } else {
            // Switch to view mode
            viewModeElements.forEach(el => el.style.display = 'block');
            editModeElements.forEach(el => el.style.display = 'none');
            informGuardianBtn.style.display = 'inline-block';
            editBtn.style.display = 'inline-block';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
        }
    }

    // Initialize edit mode for a student
    function initEditMode(studentId) {
        currentStudentId = studentId;

        // Set up event listeners
        const editBtn = document.getElementById('editStudentBtn');
        const cancelBtn = document.getElementById('cancelEditBtn');
        
        if (editBtn) {
            editBtn.onclick = () => {
                toggleEditMode(true);
            };
        }
        
        if (cancelBtn) {
            cancelBtn.onclick = () => {
                toggleEditMode(false);
            };
        }
    }

    // Update the loadStudentData function to initialize edit mode
    const originalLoadStudentData = window.loadStudentData;
    window.loadStudentData = function(studentId) {
        originalLoadStudentData(studentId);
        initEditMode(studentId);
    };
    
     // Handle View Record button click
    function viewStudentRecord(studentId) {
    
        openModal('RecordModal');
        
        // Show loading state
        console.log('Loading student data for ID:', studentId);
        document.getElementById('RecordStudentName').textContent = 'Loading...';
        document.getElementById('RecordStudentID').textContent = 'ID: ' + studentId;
        
        
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
                    document.getElementById('RecordStudentName').textContent = `${student.FirstName || ''} ${student.LastName || ''}`.trim();
                    // Update the hidden input value
                    // document.getElementById('RecordStudentIdHidden').value = studentId;
                    // document.getElementById('RecordStaffIdHidden').value = <?php //echo $_SESSION['User_ID']; ?>;
                    
                    
                    // Show loading state in table
                    const tbody = document.getElementById('recordTableBody');
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" style="padding: 1.5rem; text-align: center; color: #6b7280;">
                                Loading records...
                            </td>
                        </tr>`;
                    
                    // Fetch check-in records for this student
                    fetch(`../Functions/patientFunctions.php?action=getCheckInRecords&studentId=${studentId}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Check-in records:', data);
                            
                            if (data.success && data.records && data.records.length > 0) {
                                // Clear loading message
                                tbody.innerHTML = '';
                                
                                // Add each record to the table
                                data.records.forEach(record => {
                                
                                    // Set status to 'Lapsed' if FollowUpDate is in the past
                                    if (record.FollowUpDate) {
                                        const followUpDate = new Date(record.FollowUpDate);
                                        const currentDate = new Date();
                                        if (followUpDate < currentDate) {
                                            record.Status = 'Lapsed';
                                        } else {
                                            record.Status = record.Status;
                                        }
                                    } else {
                                        record.Status = record.Status;
                                    }
                                    
                                    const row = document.createElement('tr');
                                    const date = new Date(record.DateTime);
                                    const formattedDate = date.toLocaleDateString();
                                    const formattedTime = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                    const statusClass = record.Status === 'In-Progress' ? 'status-in-progress' : 
                                                        record.Status === 'Follow-up' ? 'status-follow-up' :
                                                        record.Status === 'Lapsed' ? 'status-lapsed' :
                                                        record.Status === 'Completed' ? 'status-completed' : 'status-in-progress';
                                    
                                    // Add clickable styles and behavior
                                    row.style.cursor = 'pointer';
                                    row.style.transition = 'background-color 0.2s';
                                    row.onmouseover = function() { this.style.backgroundColor = '#f9fafb'; };
                                    row.onmouseout = function() { this.style.backgroundColor = ''; };
                                    row.onclick = function() {
                                        console.log('Record clicked:', record);
                                        viewRecordDetails(record.id, record.StudentID, record.Status);
                                    };
                                    
                                    row.innerHTML = `
                                        <td style="padding: 0.75rem 1rem;">
                                            <div style="font-weight: 500;">${formattedDate}</div>
                                            <div style="font-size: 0.75rem; color: #6b7280;">${formattedTime}</div>
                                        </td>
                                        <td style="padding: 0.75rem 1rem;">${record.Reason || 'N/A'}</td>
                                        <td style="padding: 0.75rem 1rem;">
                                            <span class="${statusClass}">${record.Status || 'N/A'}</span>
                                        </td>
                                        <td style="padding: 0.75rem 1rem;">${record.Outcome || 'N/A'}</td>
                                        <td style="padding: 0.75rem 1rem;">${record.staff_name || 'N/A'}</td>
                                    `;
                                    tbody.appendChild(row);
                                });
                            } else {
                                tbody.innerHTML = `
                                    <tr>
                                        <td colspan="5" style="padding: 1.5rem; text-align: center; color: #6b7280;">
                                            No check-in records found for this student.
                                        </td>
                                    </tr>`;
                            }
                        })
                        .catch(error => {
                            console.error('Error loading check-in records:', error);
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="5" style="padding: 1.5rem; text-align: center; color: #ef4444;">
                                        Error loading records. Please try again.
                                    </td>
                                </tr>`;
                        });
                    
                } else {
                    alert('Failed to load student data: ' + (data.message || 'Unknown error'));
                    closeModal('RecordModal');
                }
            })
            .catch(error => {
                console.error('Error loading student data:', error);
                alert('Failed to load student data. Please try again.');
                closeModal('RecordModal');
            });
    }
    
    // View Record Details
    function viewRecordDetails(recordId, studentId, recordStatus) {
        closeModal('RecordModal');
        
        if(recordStatus == "In Progress" || recordStatus == "Follow-up"){
            openModal('EditRecordModal');
            loadMedicines();
            loadPrescriptions(recordId,studentId);
            isInProgress_or_FollowUp(recordId, studentId);
        }else if(recordStatus == "Lapsed" || recordStatus == "Completed"){
            openModal('ViewRecordModal');
            isLapsed_or_Completed(recordId, studentId);
        }
        
        // Show loading state
        console.log('Loading student data for ID:', studentId);
        document.getElementById('EditStudentName').textContent = 'Loading...';
        document.getElementById('EditStudentID').textContent = 'ID: ' + studentId;
        
        
    }
    
    
    function isInProgress_or_FollowUp(recordId, studentId){
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
                document.getElementById('EditStudentName').textContent = `${student.FirstName || ''} ${student.LastName || ''}`.trim();
                document.getElementById('EditAvatar').textContent = student.FirstName[0] + student.LastName[0];
                
                // Fetch check-in records for this student
                fetch(`../Functions/patientFunctions.php?action=getRecords&studentId=${studentId}&recordId=${recordId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Check-in records:', data);
                        
                        if (data.success && data.records && data.records.length > 0) {
                            
                            // Add each record to the table
                            data.records.forEach(record => {
                                const date = new Date(record.DateTime);
                                const formattedDate = date.toLocaleDateString();
                                const formattedTime = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                const statusClass = record.Status === 'In Progress' ? 'status-in-progress' : 
                                                    record.Status === 'Follow-up' ? 'status-follow-up' :
                                                    record.Status === 'Lapsed' ? 'status-lapsed' :
                                                    record.Status === 'Completed' ? 'status-completed' : 'status-in-progress';
                                
                                document.getElementById('CheckInDateTime_record').textContent = `${formattedDate} ${formattedTime}`;
                                document.getElementById('Status_record').textContent = record.Status;
                                document.getElementById('DateTime_record').textContent = record.UpdatedAt;
                                document.getElementById('FollowUpDate_record').value = record.FollowUpDate || 'N/A';
                                document.getElementById('checkup_reason_record').value = record.Reason;
                                document.getElementById('Notes_record').value = record.Notes;
                                document.getElementById('recordID').value = record.id;
                                
                                document.getElementById('EditRecordModalTitle').textContent = "Record Details";
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading check-in records:', error);
                    });
            } else {
                alert('Failed to load student data: ' + (data.message || 'Unknown error'));
                closeModal('EditRecordModal');
            }
        })
        .catch(error => {
            console.error('Error loading student data:', error);
            alert('Failed to load student data. Please try again.');
            closeModal('EditRecordModal');
        });
    }
    
    function isLapsed_or_Completed(recordId, studentId){
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
                
                let MiddleName = (!student || !student.MiddleName) ? "" : student.MiddleName;
                
                // Update header
                document.getElementById('ViewRecord_StudentName').textContent = student.FirstName + ' ' + MiddleName + ' ' + student.LastName;
                document.getElementById('ViewRecord_StudentID').textContent = student.School_ID;
                document.getElementById('ViewRecord_StudentAddress').textContent = student.Address;
                document.getElementById('ViewRecord_StudentGender').textContent = student.Gender;
                document.getElementById('ViewRecord_StudentAge').textContent = student.Age;
                document.getElementById('ViewRecord_StudentDepartment').textContent = student.Department;
                document.getElementById('ViewRecord_StudentGradeLevel').textContent = student.GradeLevel;
                document.getElementById('ViewRecord_StudentSection').textContent = student.Section;
                document.getElementById('ViewRecord_StudentContactNumber').textContent = student.StudentContactNumber;
                document.getElementById('ViewRecord_StudentEmail').textContent = student.StudentEmailAddress;
                
                // Fetch check-in records for this student
                fetch(`../Functions/patientFunctions.php?action=getRecords&studentId=${studentId}&recordId=${recordId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Check-in records:', data);
                        
                        if (data.success && data.records && data.records.length > 0) {
                            
                            // Add each record to the table
                            data.records.forEach(record => {
                                
                                // Set status to 'Lapsed' if FollowUpDate is in the past
                                if (record.FollowUpDate) {
                                    const followUpDate = new Date(record.FollowUpDate);
                                    const currentDate = new Date();
                                    if (followUpDate < currentDate) {
                                        record.Status = 'Lapsed';
                                    } else {
                                        record.Status = record.Status;
                                    }
                                } else {
                                    record.Status = record.Status;
                                }
                            
                                const date = new Date(record.DateTime);
                                const formattedDate = date.toLocaleDateString();
                                const formattedTime = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                const statusClass = record.Status === 'In Progress' ? 'status-in-progress' : 
                                                    record.Status === 'Follow-up' ? 'status-follow-up' :
                                                    record.Status === 'Lapsed' ? 'status-lapsed' :
                                                    record.Status === 'Completed' ? 'status-completed' : 'status-in-progress';
                                
                                document.getElementById('ViewRecord_DateTime').textContent = `${formattedDate} ${formattedTime}`;
                                document.getElementById('ViewRecord_Status').textContent = record.Status;
                                document.getElementById('ViewRecord_FollowUpDate').textContent = record.FollowUpDate || 'N/A';
                                document.getElementById('ViewRecord_Reason').textContent = record.Reason;
                                document.getElementById('ViewRecord_Notes').textContent = record.Notes;
                                document.getElementById('ViewRecord_Outcome').textContent = record.Outcome;
                                document.getElementById('ViewRecord_AssistBy').textContent = record.AssistBy;
                                
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading check-in records:', error);
                    });
            } else {
                alert('Failed to load student data: ' + (data.message || 'Unknown error'));
                closeModal('EditRecordModal');
            }
        })
        .catch(error => {
            console.error('Error loading student data:', error);
            alert('Failed to load student data. Please try again.');
            closeModal('EditRecordModal');
        });
    }
    
    
    // Update account status
    function updateStatus(SchoolID, currentStatus) {
        const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
        
        if (confirm(`Are you sure you want to ${currentStatus === 'Active' ? 'deactivate' : 'activate'} this student?`)) {
            const formData = new FormData();
            formData.append('action', 'updateStudentStatus');
            formData.append('schoolID', SchoolID);
            formData.append('newStatus', newStatus);
            
            fetch('../Functions/patientFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the UI to reflect the new status
                    const statusBtn = document.getElementById('statusStudentBtn');
                    const statusSpan = statusBtn.querySelector('span');
                    
                    // Toggle classes based on new status
                    if (newStatus === 'Active') {
                        statusBtn.classList.remove('bg-red-100', 'text-red-800');
                        statusBtn.classList.add('bg-green-100', 'text-green-800');
                        statusSpan.className = 'w-2 h-2 rounded-full bg-green-500 mr-1.5';
                    } else {
                        statusBtn.classList.remove('bg-green-100', 'text-green-800');
                        statusBtn.classList.add('bg-red-100', 'text-red-800');
                        statusSpan.className = 'w-2 h-2 rounded-full bg-red-500 mr-1.5';
                    }
                    
                    // Update the button text and hidden field
                    statusBtn.innerHTML = `${statusSpan.outerHTML} ${newStatus}`;
                    document.getElementById('currentStatus').value = newStatus;
                    
                    alert(data.message);
                    window.location.reload();
                } else {
                    throw new Error(data.error || 'Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating status: ' + error.message);
            });
        }
    }
    
    // Filter Button
    function filterbutton() {
        const filter = document.getElementById('filter');
        filter.classList.toggle('hidden');
    }
</script>
    
    
    <!-- Add New Student Modal -->
    <?php include '../Modals/NewStudent_modal.php'; ?>
    
    <!-- Alert Guardian Modal -->
    <?php include '../Modals/AlertGuardian_modal.php'; ?>
    
    <!-- View Student Modal -->
    <?php include '../Modals/ViewProfile_modal.php'; ?>
    
    <!-- Check-in Modal -->
    <?php include '../Modals/Checkin_modal.php'; ?>
    
    <!-- Medical Records Modal -->
    <?php include '../Modals/MedRecords_modal.php'; ?>
    
</section>
            </div>
        </main>
    </div>
</body>

</html>
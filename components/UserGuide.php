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
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIAIMS | User Guide</title>
    <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../Stylesheet/Design.css">
    <script src="../Functions/scripts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#F7FFF7',
                            100: '#E8F5E8',
                            200: '#D1E7DD',
                            300: '#95D5B2',
                            400: '#74C69D',
                            500: '#52B788',
                            600: '#40916C',
                            700: '#2D5A3D',
                            800: '#1B4332',
                            900: '#081c15',
                        },
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                    },
                },
            },
        }
    </script>
    <style>
        .container {
            max-width: 1200px;
        }
        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }
        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .p-6 {
            padding: 1.5rem;
        }
        .mb-8 {
            margin-bottom: 2rem;
        }
        .flex {
            display: flex;
        }
        .flex-col {
            flex-direction: column;
        }
        .md\:flex-row {
            flex-direction: row;
        }
        .gap-8 {
            gap: 2rem;
        }
        .items-center {
            align-items: center;
        }
        .md\:w-1\/2 {
            width: 50%;
        }
        .list-disc {
            list-style-type: disc;
        }
        .pl-5 {
            padding-left: 1.25rem;
        }
        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }
        .text-gray-600 {
            color: #4B5563;
        }
        .text-gray-700 {
            color: #374151;
        }
        .text-gray-800 {
            color: #1F2937;
        }
        .font-bold {
            font-weight: 700;
        }
        .font-semibold {
            font-weight: 600;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .text-orange-500 {
            color: #F59E0B;
        }
        .text-blue-500 {
            color: #3B82F6;
        }
        .text-purple-500 {
            color: #8B5CF6;
        }
        .text-green-500 {
            color: #10B981;
        }
        .text-yellow-500 {
            color: #F59E0B;
        }
        .text-red-500 {
            color: #EF4444;
        }
        kbd {
            background-color: #F3F4F6;
            border-radius: 0.25rem;
            border: 1px solid #D1D5DB;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            color: #4B5563;
            display: inline-block;
            font-size: 0.75rem;
            line-height: 1.25rem;
            padding: 0.125rem 0.5rem;
            white-space: nowrap;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <!-- Back button -->
    <a href="javascript:history.back()" class="fixed top-4 left-4 bg-white text-gray-700 hover:bg-gray-100 p-3 rounded-full shadow-lg transition-all duration-300 z-50 flex items-center">
        <i class='bx bx-arrow-back text-xl mr-1'></i>
        <span class="hidden sm:inline">Back</span>
    </a>
    
    <!-- Back to top button -->
    <button id="backToTop" class="fixed bottom-8 right-8 bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 transition-all duration-300 opacity-0 invisible z-50">
        <i class='bx bx-chevron-up text-xl'></i>
    </button>

<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in">User Guide</h1>
            <div class="text-xl text-primary-100 max-w-3xl mx-auto">
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <span>Welcome,</span>
                    <div class="inline-flex items-baseline bg-white/10 backdrop-blur-sm px-3 py-1.5 rounded-full border border-white/20">
                        <span class="font-medium text-white"><?php echo htmlspecialchars($fullname) ?></span>
                        <span class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-xs text-white font-medium"><?php echo htmlspecialchars($role) ?></span>
                    </div>
                </div>
                <p class="mt-2">Everything you need to know about using the Patient Information & Medical Inventory System</p>
            </div>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="#quick-scan" class="bg-white text-primary-700 hover:bg-primary-50 px-6 py-3 rounded-lg font-medium transition-all duration-300 transform hover:-translate-y-1 flex items-center">
                    <i class='bx bx-qr-scan mr-2'></i> Quick Start
                </a>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=davemalaran2004@gmail.com&su=<?php echo urlencode('PIAIMS User Question - ' . $fullname . ' (' . $role . ')') ?>" target="_blank" rel="noopener noreferrer" class="bg-primary-500 hover:bg-primary-400 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 transform hover:-translate-y-1 flex items-center">
                    <i class='bx bx-envelope mr-2'></i> Need Help? Email Us
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <!-- Table of Contents -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-12 sticky top-4 z-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                <i class='bx bx-list-ul text-primary-600 mr-2'></i>
                Table of Contents
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="#quick-scan" class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-primary-100 p-2 rounded-lg mr-3">
                        <i class='bx bx-qr-scan text-primary-600 text-xl'></i>
                    </div>
                    <span>Quick Scan</span>
                </a>
                <a href="#dashboard" class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <i class='bx bx-pulse text-blue-600 text-xl'></i>
                    </div>
                    <span>Dashboard</span>
                </a>
                <a href="#user-management" class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <i class='bx bx-user text-purple-600 text-xl'></i>
                    </div>
                    <span>User Management</span>
                </a>
                <a href="#patients" class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class='bx bx-user-plus text-green-600 text-xl'></i>
                    </div>
                    <span>Patients / Students</span>
                </a>
                <a href="#inventory" class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                        <i class='bx bx-package text-yellow-600 text-xl'></i>
                    </div>
                    <span>Inventory Management</span>
                </a>
                <a href="#audit-logs" class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                        <i class='bx bx-history text-red-600 text-xl'></i>
                    </div>
                    <span>Audit Logs</span>
                </a>
            </div>
        </div>
    
    <!-- Quick Scan Section -->
    <div id="quick-scan" class="bg-white rounded-2xl shadow-lg p-8 mb-12 transform transition-all duration-300 hover:shadow-xl animate-slide-up">
        <div class="flex items-center mb-6">
            <div class="bg-primary-100 p-3 rounded-xl mr-4">
                <i class='bx bx-qr-scan text-primary-600 text-3xl'></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Quick Scan</h2>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-1/2">
                <img src="../Images/System Screenshots/Dashboard-demo.png" class="w-full h-auto rounded-xl shadow-md border border-gray-200" alt="Quick Scan Demo" class="rounded-lg shadow-md mb-4">
            </div>
            <div class="w-full lg:w-1/2">
                <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                    The Quick Scan feature allows you to quickly look up patients or inventory items by scanning their QR codes or entering their IDs manually.
                </p>
                <ul class="list-disc pl-5 text-gray-700 space-y-3">
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Click on the scan input field or press <kbd class="bg-gray-100 border border-gray-200 px-2 py-1 rounded-md text-sm font-mono">Ctrl + Q</kbd> to focus</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Scan a QR code or type the ID manually</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Press <kbd class="bg-gray-100 border border-gray-200 px-2 py-1 rounded-md text-sm font-mono">Enter</kbd> to search</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Matching results will be displayed automatically</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Dashboard Section -->
    <div id="dashboard" class="bg-white rounded-2xl shadow-lg p-8 mb-12 transform transition-all duration-300 hover:shadow-xl animate-slide-up">
        <div class="flex items-center mb-6">
            <div class="bg-blue-100 p-3 rounded-xl mr-4">
                <i class='bx bx-pulse text-blue-600 text-3xl'></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard</h2>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-1/2 relative group">
                <div class="carousel relative overflow-hidden rounded-xl shadow-md border border-gray-200">
                    <!-- Slides -->
                    <div class="carousel-inner flex transition-transform duration-300 ease-in-out">
                        <div class="carousel-item w-full flex-shrink-0">
                            <img src="../Images/System Screenshots/Dashboard-demo.png" class="w-full h-auto" alt="Main Dashboard">
                            <p class="text-center text-sm text-gray-500 p-2 bg-white">Main Dashboard Overview</p>
                        </div>
                        <div class="carousel-item w-full flex-shrink-0">
                            <img src="../Images/System Screenshots/Dashboard-Checkins-demo.png" class="w-full h-auto" alt="Check-ins Dashboard">
                            <p class="text-center text-sm text-gray-500 p-2 bg-white">Patient Check-ins</p>
                        </div>
                        <div class="carousel-item w-full flex-shrink-0">
                            <img src="../Images/System Screenshots/Dashboard-InvLogs-demo.png" class="w-full h-auto" alt="Inventory Logs">
                            <p class="text-center text-sm text-gray-500 p-2 bg-white">Inventory Logs</p>
                        </div>
                    </div>
                    
                    <!-- Navigation buttons -->
                    <button class="carousel-prev absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <i class='bx bx-chevron-left text-xl'></i>
                    </button>
                    <button class="carousel-next absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <i class='bx bx-chevron-right text-xl'></i>
                    </button>
                    
                    <!-- Indicators -->
                    <div class="absolute bottom-2 left-0 right-0 flex justify-center space-x-2">
                        <button class="carousel-indicator w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-colors"></button>
                        <button class="carousel-indicator w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-colors"></button>
                        <button class="carousel-indicator w-2 h-2 rounded-full bg-white/50 hover:bg-white transition-colors"></button>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/2">
                <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                    The Dashboard provides an overview of system statistics and recent activities.
                </p>
                <ul class="list-disc pl-5 text-gray-700 space-y-3">
                    <li class="flex items-start">
                        <i class='bx bx-package text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span><strong>Inventory Summary</strong> - View current stock levels and low inventory alerts</span>
                    </li>
                    <li class="flex items-start">
                        <i class='bx bx-group text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span><strong>Users Overview</strong> - See active users and their roles at a glance</span>
                    </li>
                    <li class="flex items-start">
                        <i class='bx bx-calendar-check text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span><strong>Today's Check-ups</strong> - Monitor scheduled and completed student visits</span>
                    </li>
                    <li class="flex items-start">
                        <i class='bx bx-bar-chart-alt-2 text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span><strong>Medicine Usage</strong> - Visual charts of most frequently used medications</span>
                    </li>
                    <li class="flex items-start">
                        <i class='bx bx-clipboard text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span><strong>Common Ailments</strong> - Track frequent health concerns and symptoms</span>
                    </li>
                    <li class="flex items-start">
                        <i class='bx bx-clinic text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span><strong>Visit Logs</strong> - Detailed records of all clinic visits and consultations</span>
                    </li>
                    <li class="flex items-start">
                        <i class='bx bx-history text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span><strong>Inventory History</strong> - Complete transaction logs for all stock movements</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- User Management Section -->
    <div id="user-management" class="bg-white rounded-2xl shadow-lg p-8 mb-12 transform transition-all duration-300 hover:shadow-xl animate-slide-up">
        <div class="flex items-center mb-6">
            <div class="bg-purple-100 p-3 rounded-xl mr-4">
                <i class='bx bx-user text-purple-600 text-3xl'></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">User Management</h2>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-1/2">
                <img src="../Images/System Screenshots/Dashboard-demo.png" class="w-full h-auto rounded-xl shadow-md border border-gray-200" alt="User Management Demo" class="rounded-lg shadow-md mb-4">
            </div>
            <div class="w-full lg:w-1/2">
                <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                    Manage system users, roles, and permissions in one place.
                </p>
                <ul class="list-disc pl-5 text-gray-700 space-y-3">
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Add, edit, or deactivate user accounts</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Assign roles and permissions</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Reset passwords and manage access</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>View user activity logs</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Patients/Students Section -->
    <div id="patients" class="bg-white rounded-2xl shadow-lg p-8 mb-12 transform transition-all duration-300 hover:shadow-xl animate-slide-up">
        <div class="flex items-center mb-6">
            <div class="bg-green-100 p-3 rounded-xl mr-4">
                <i class='bx bx-user-plus text-green-600 text-3xl'></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Patients / Students</h2>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-1/2">
                <img src="../Images/System Screenshots/Dashboard-demo.png" class="w-full h-auto rounded-xl shadow-md border border-gray-200" alt="Patients Demo" class="rounded-lg shadow-md mb-4">
            </div>
            <div class="w-full lg:w-1/2">
                <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                    Manage patient and student records efficiently.
                </p>
                <ul class="list-disc pl-5 text-gray-700 space-y-3">
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Add new patient/student records</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Search and filter existing records</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>View medical history and visit logs</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Generate reports and export data</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Inventory Section -->
    <div id="inventory" class="bg-white rounded-2xl shadow-lg p-8 mb-12 transform transition-all duration-300 hover:shadow-xl animate-slide-up">
        <div class="flex items-center mb-6">
            <div class="bg-yellow-100 p-3 rounded-xl mr-4">
                <i class='bx bx-package text-yellow-600 text-3xl'></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Inventory Management</h2>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-1/2">
                <img src="../Images/System Screenshots/Dashboard-demo.png" class="w-full h-auto rounded-xl shadow-md border border-gray-200" alt="Inventory Demo" class="rounded-lg shadow-md mb-4">
            </div>
            <div class="w-full lg:w-1/2">
                <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                    Track and manage medical supplies and equipment.
                </p>
                <ul class="list-disc pl-5 text-gray-700 space-y-3">
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>View current stock levels</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Add new inventory items</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Update stock quantities</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Set up low stock alerts</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Generate inventory reports</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Audit Logs Section -->
    <div id="audit-logs" class="bg-white rounded-2xl shadow-lg p-8 mb-12 transform transition-all duration-300 hover:shadow-xl animate-slide-up">
        <div class="flex items-center mb-6">
            <div class="bg-red-100 p-3 rounded-xl mr-4">
                <i class='bx bx-history text-red-600 text-3xl'></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Audit Logs</h2>
        </div>
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-1/2">
                <img src="../Images/System Screenshots/Dashboard-demo.png" class="w-full h-auto rounded-xl shadow-md border border-gray-200" alt="Audit Logs Demo" class="rounded-lg shadow-md mb-4">
            </div>
            <div class="w-full lg:w-1/2">
                <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                    Track all system activities and changes.
                </p>
                <ul class="list-disc pl-5 text-gray-700 space-y-3">
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>View detailed activity logs</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Filter logs by date, user, or action type</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Export logs for record-keeping</span></li>
                    <li class="flex items-start">
                        <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                        <span>Monitor system security and compliance</span></li>
                </ul>
            </div>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">About PIAIMS</h3>
                    <p class="text-gray-300">Patient Information & Medical Inventory Management System for efficient healthcare management.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Support</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <p class="text-gray-300">Email: support@piaims.com</p>
                    <p class="text-gray-300">Phone: (123) 456-7890</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 PIAIMS. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>

<!-- Back to top button script -->
<script>
    // Show/hide back to top button
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
            backToTopButton.classList.add('opacity-100', 'visible');
        } else {
            backToTopButton.classList.remove('opacity-100', 'visible');
            backToTopButton.classList.add('opacity-0', 'invisible');
        }
    });
    
    // Smooth scroll to top
    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>

<script>
// Carousel functionality
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.carousel');
    if (!carousel) return;
    
    const inner = carousel.querySelector('.carousel-inner');
    const items = carousel.querySelectorAll('.carousel-item');
    const prevBtn = carousel.querySelector('.carousel-prev');
    const nextBtn = carousel.querySelector('.carousel-next');
    const indicators = carousel.querySelectorAll('.carousel-indicator');
    
    let currentIndex = 0;
    const totalItems = items.length;
    let autoSlide;
    
    function updateCarousel() {
        // Update slide position
        inner.style.transform = `translateX(-${currentIndex * 100}%`;
        
        // Update active indicator
        indicators.forEach((indicator, index) => {
            if (index === currentIndex) {
                indicator.classList.add('bg-white');
                indicator.classList.remove('bg-white/50');
            } else {
                indicator.classList.remove('bg-white');
                indicator.classList.add('bg-white/50');
            }
        });
    }
    
    // Initialize carousel
    function initCarousel() {
        // Set initial active state
        updateCarousel();
        
        // Previous button click
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            updateCarousel();
            resetAutoSlide();
        });
        
        // Next button click
        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
            resetAutoSlide();
        });
        
        // Indicator clicks
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
                resetAutoSlide();
            });
        });
        
        // Start auto-slide
        startAutoSlide();
        
        // Pause auto-slide on hover
        carousel.addEventListener('mouseenter', () => {
            clearInterval(autoSlide);
        });
        
        // Resume auto-slide when mouse leaves
        carousel.addEventListener('mouseleave', () => {
            startAutoSlide();
        });
    }
    
    function startAutoSlide() {
        clearInterval(autoSlide);
        autoSlide = setInterval(() => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        }, 5000);
    }
    
    function resetAutoSlide() {
        clearInterval(autoSlide);
        startAutoSlide();
    }
    
    // Initialize the carousel
    initCarousel();
});
</script>

</body>

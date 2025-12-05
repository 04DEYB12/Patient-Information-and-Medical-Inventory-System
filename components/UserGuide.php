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
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white py-20 px-4 sm:px-6 lg:py-24">
        <!-- Decorative elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSgzMCkiPjxyZWN0IHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhGVpZ2h0PSIxMDAlIi8+PC9zdmc+')]"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-8 md:p-10 shadow-2xl border border-white/10">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center mb-6">
                        <span class="relative flex h-3 w-3 mr-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white/80"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                        </span>
                        <span class="text-sm font-medium bg-white/10 px-3 py-1 rounded-full">User Guide Portal</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-primary-100 mb-6">
                        Welcome to PIAIMS
                    </h1>
                    
                    <div class="max-w-3xl mx-auto">
                        <div class="flex flex-wrap items-center justify-center gap-2 mb-6">
                            <span class="text-lg text-primary-100">Hello,</span>
                            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/20 shadow-lg hover:bg-white/20 transition-colors duration-300">
                                <span class="font-medium text-white"><?php echo htmlspecialchars($fullname) ?></span>
                                <span class="ml-2 px-2.5 py-0.5 bg-white/20 rounded-full text-xs font-medium text-white/90"><?php echo htmlspecialchars($role) ?></span>
                            </div>
                        </div>
                        
                        <p class="text-xl text-primary-100/90 leading-relaxed mb-8">
                            Your guide to mastering the Patient Information & Medical Inventory System.
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-8">
                        <a href="#quick-scan" 
                           class="group relative inline-flex items-center justify-center px-8 py-3.5 overflow-hidden font-medium text-primary-700 bg-white rounded-xl hover:shadow-xl hover:shadow-primary-500/20 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-600">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-primary-100 to-primary-200 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <span class="relative flex items-center">
                                <i class='bx bx-qr-scan text-xl mr-2 group-hover:animate-pulse'></i>
                                <span class="font-semibold">Quick Start Guide</span>
                            </span>
                        </a>
                        
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=davemalaran2004@gmail.com&su=<?php echo urlencode('PIAIMS User Question - ' . $fullname . ' (' . $role . ')') ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="group relative inline-flex items-center justify-center px-8 py-3.5 overflow-hidden font-medium text-white bg-primary-500/90 hover:bg-primary-400 rounded-xl hover:shadow-xl hover:shadow-primary-500/30 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-600">
                            <span class="absolute inset-0 bg-gradient-to-r from-primary-400 to-primary-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <span class="relative flex items-center">
                                <i class='bx bx-envelope text-xl mr-2 group-hover:animate-bounce'></i>
                                <span class="font-semibold">Need Help? Email Us</span>
                            </span>
                        </a>
                    </div>
                </div>
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
                <a href="#user-management" id="user-management-nav" class="hidden flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
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
                <div class="w-full lg:w-1/2 group">
                    <div class="carousel relative overflow-hidden rounded-xl shadow-md border border-gray-200">
                        <!-- Slides -->
                        <div class="carousel-inner flex transition-transform duration-300 ease-in-out">
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/QuickScan-demo.png" class="w-full h-auto" alt="Quick Scan">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Quick Scan</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/QuickScan-Check-up-demo.png" class="w-full h-auto" alt="Check-up modal">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Check-up modal</p>
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
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                        The Quick Scan feature allows you to quickly check up patients by scanning their QR codes.
                    </p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-3">
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Scan QR Code from the student ID card</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Anywhere in Navigation can Trigger Quick Scan</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Check-up modal will show</span>
                        </li>
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
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                        The Dashboard provides an overview of system statistics and recent activities.
                    </p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-3">
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>View current stock levels and low inventory alerts</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>See active users and their roles at a glance</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Monitor how many students check up today.</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Visual charts of most frequently used medications</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Track frequent health concerns and symptoms</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Detailed records of all clinic visits and consultations</span>
                        </li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Complete transaction logs for all stock movements</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- User Management Section -->
        <div id="user-management" class="hidden bg-white rounded-2xl shadow-lg p-8 mb-12 transform transition-all duration-300 hover:shadow-xl animate-slide-up">
            <div class="flex items-center mb-6">
                <div class="bg-purple-100 p-3 rounded-xl mr-4">
                    <i class='bx bx-user text-purple-600 text-3xl'></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">User Management</h2>
            </div>
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <div class="w-full lg:w-1/2 group">
                    <div class="carousel relative overflow-hidden rounded-xl shadow-md border border-gray-200">
                        <!-- Slides -->
                        <div class="carousel-inner flex transition-transform duration-300 ease-in-out">
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/UserManagement-demo.png" class="w-full h-auto" alt="User Management">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">User Management</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/UserManagement-NewUser-demo.png" class="w-full h-auto" alt="New User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">New User Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/UserManagement-ManageAccount-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">User Profile Modal</p>
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
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                        Manage system users, roles, and permissions in one place.
                    </p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-3">
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Add new user accounts</span></li>
                        <li id="user-management-assign" class="hidden flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Assign roles and permissions</span></li>
                        <li id="user-management-status" class="hidden flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Manage account status</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Reset passwords to default and send via email</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Change user email upon request</span></li>
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
                <div class="w-full lg:w-1/2 group">
                    <div class="carousel relative overflow-hidden rounded-xl shadow-md border border-gray-200">
                        <!-- Slides -->
                        <div class="carousel-inner flex transition-transform duration-300 ease-in-out">
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Patient-demo.png" class="w-full h-auto" alt="User Management">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Patient / Student</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Patient-AddStudent-demo.png" class="w-full h-auto" alt="New User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">New Student Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Patient-Checkup-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Checkup Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Patient-Profile-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Student Profile Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Patient-InformGuardian-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Inform Guardian Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Patient-RecordHistory-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Record History Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Patient-RecordDetails-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Record Details Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Patient-MedicalRecord-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Medical Record Modal</p>
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
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                        </div>
                    </div>
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
                            <span>Check up on students' reasons for visits</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Manage student profile information</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Inform guardians via email</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Manage medical records</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Schedule student follow-up assessments</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Generate PDF reports</span></li>
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
                <div class="w-full lg:w-1/2 group">
                    <div class="carousel relative overflow-hidden rounded-xl shadow-md border border-gray-200">
                        <!-- Slides -->
                        <div class="carousel-inner flex transition-transform duration-300 ease-in-out">
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Inventory-demo.png" class="w-full h-auto" alt="User Management">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Medicine Inventory</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Inventory-NewMedicine-demo.png" class="w-full h-auto" alt="New User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Add New Medicine Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Inventory-Restock-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Restock Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Inventory-Deduct-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Deduct Modal</p>
                            </div>
                            <div class="carousel-item w-full flex-shrink-0">
                                <img src="../Images/System Screenshots/Inventory-Dispose-demo.png" class="w-full h-auto" alt="Edit User">
                                <p class="text-center text-sm text-gray-500 p-2 bg-white">Dispose Modal</p>
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
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                            <button class="carousel-indicator w-2 h-2 rounded-full bg-blue-400/70 hover:bg-blue-500 transition-colors"></button>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                        Track and manage medical supplies and equipment.
                    </p>
                    <ul class="list-disc pl-5 text-gray-700 space-y-3">
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>View current stock levels and expiration dates</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Add new medicine to inventory</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Restock existing medicine</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Deduct medicine from inventory</span></li>
                        <li class="flex items-start">
                            <i class='bx bx-chevron-right text-primary-500 mt-1 mr-2 text-lg'></i>
                            <span>Dispose of expired or damaged medicine</span></li>
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
                    <img src="../Images/System Screenshots/AuditLogs-demo.png" class="w-full h-auto rounded-xl shadow-md border border-gray-200" alt="Audit Logs Demo" class="rounded-lg shadow-md mb-4">
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
                            <span>Filter logs by roles and modules</span></li>
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
                    <h3 class="text-lg font-semibold mb-4 text-primary-500">About PIAIMS</h3>
                    <p class="text-gray-300">Patient Information & Medical Inventory Management System for efficient healthcare management.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-primary-500">Contact</h3>
                    <div class="relative inline-block">
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=davemalaran2004@gmail.com&su=<?php echo urlencode('PIAIMS - ' . $fullname . ' (' . $role . ')') ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="relative text-gray-300 hover:text-white transition-colors group">
                            <i class='bx bx-envelope mr-2'></i>davemalaran2004@gmail.com
                            <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-primary-400 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </div>
                    <p class="text-gray-300 mt-3"><i class='bx bx-phone mr-2'></i> 0951 457 2814</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-primary-500">Our Teams</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="flex items-start">
                            <span class="text-primary-400 mr-2">•</span>
                            <a href="https://www.facebook.com/04.deyb.12" target="_blank" class="relative text-gray-300 hover:text-white transition-colors group">
                                Dave Malaran
                                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-primary-400 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </div>
                        <div class="flex items-start">
                            <span class="text-primary-400 mr-2">•</span>
                            <a href="https://www.facebook.com/aa.aranthou" target="_blank" class="relative text-gray-300 hover:text-white transition-colors group">
                                Anthony Arisgado
                                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-primary-400 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </div>
                        <div class="flex items-start">
                            <span class="text-primary-400 mr-2">•</span>
                            <a href="https://www.facebook.com/elmarr.delacruz" target="_blank" class="relative text-gray-300 hover:text-white transition-colors group">
                                Elmar Reymond Dela Cruz
                                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-primary-400 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </div>
                        <div class="flex items-start">
                            <span class="text-primary-400 mr-2">•</span>
                            <a href="https://www.facebook.com/Jobert.Tumbado" target="_blank" class="relative text-gray-300 hover:text-white transition-colors group">
                                Jobert Tumbado
                                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-primary-400 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </div>
                        <div class="flex items-start">
                            <span class="text-primary-400 mr-2">•</span>
                            <a href="https://www.facebook.com/2m4j3" target="_blank" class="relative text-gray-300 hover:text-white transition-colors group">
                                Mark Jhone Sumaylo
                                <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-primary-400 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; 2025 PIAIMS. All rights reserved. Powered by Granby Colleges of Science and Technology.</p>
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

    const userManagement = document.getElementById('user-management');
    const userManagementNav = document.getElementById('user-management-nav');
    const userManagementAssign = document.getElementById('user-management-assign');
    const userManagementStatus = document.getElementById('user-management-status');
    
    if (userManagement) {
        const userRole = '<?php echo addslashes(htmlspecialchars($_SESSION['role'] ?? '', ENT_QUOTES, 'UTF-8')); ?>';
        const allowedRoles = ['Super Administrator', 'Administrator'];
        
        if (allowedRoles.includes(userRole)) {
            userManagement.classList.remove('hidden');
            userManagementNav.classList.remove('hidden');
        }
        
        if(userRole == 'Super Administrator'){
            userManagementAssign.classList.remove('hidden');
            userManagementStatus.classList.remove('hidden');
        }
    }

    // Get all carousels
    const carousels = document.querySelectorAll('.carousel');
    
    carousels.forEach(carousel => {
        const inner = carousel.querySelector('.carousel-inner');
        const items = carousel.querySelectorAll('.carousel-item');
        const prevBtn = carousel.querySelector('.carousel-prev');
        const nextBtn = carousel.querySelector('.carousel-next');
               const indicators = carousel.querySelectorAll('.carousel-indicator');
        
        let currentIndex = 0;
        const totalItems = items.length;
        let autoSlide;
        
        function updateCarousel() {
            inner.style.transform = `translateX(-${currentIndex * 100}%)`;
            
            // Update indicators
            indicators.forEach((indicator, index) => {
                if (index === currentIndex) {
                    indicator.classList.remove('bg-blue-400');
                    indicator.classList.remove('bg-white/50');
                    indicator.classList.add('bg-green-600');
                } else {
                    indicator.classList.remove('bg-green-600');
                    indicator.classList.add('bg-blue-400');
                }
            });
        }
        
        // Initialize carousel
        function initCarousel() {
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
            }, 5000); // Change slide every 5 seconds
        }
        
        function resetAutoSlide() {
            startAutoSlide();
        }
        
        initCarousel();
    });
});
</script>

</body>

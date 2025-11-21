<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('Please login first!'); window.location.href = '../Landing Repository/LandingPage.php';</script>";
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
    <title>PIAIMS | My Profile</title>
    <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script src="../Functions/scripts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../Stylesheet/Design.css">
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
                <h1 id="pageTitle" style="color: #002e2d;">MY PROFILE</h1>
            </header>
            
            <!-- Contents -->
            <div class="content-container">
            <?php include '../components/Sections.php'; ?>
            
            <section class="content-section active" id="MyProfileSection">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Personal Info</h1>
                    <p class="text-gray-600">Info about you and your preferences across PIAMIS</p>
                </div>
                
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-8">
                        <div class="md:w-2/3">
                            <h1 class="font-semibold text-gray-800 mb-4 text-2xl">Your Profile Information in PIAMIS</h1>
                            <p class="text-gray-600 mb-6 text-xm">Personal info and options to manage it. You can make some of this info, like your contact details, visible to others so they can reach you easily. You can also see a summary of your profiles.</p>
                        </div>
                        <div class="md:w-1/3 flex flex-col items-center">
                            <div class="overflow-hidden">
                                <img src="../Images/infoCard.png" alt="Info Card" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="basicinfo rounded-lg border-[1px] border-gray-300 p-6 mb-6">
                        <div class="header flex flex-col items-start justify-center mb-4">
                            <h2 class="text-xl font-normal text-gray-800">Basic info</h2>
                            <p>Manage your basic informations.</p>
                        </div>
                        <div class="infodata mt-8 p-4 px-6 border-b-[1px] border-gray-200 flex items-center justify-between">
                            <label>Profile Picture</label>
                            <span class="text-gray-500">A personalized image for user profile</span>
                            <div class="user-avatar py-6 px-8 flex items-center justify-center text-2xl font-bold rounded-full bg-blue-100 relative shadow-md overflow-hidden">
                            <?php echo $firstname[0]; ?>
                            
                            <!-- <button class="absolute bottom-1 right-0 bg-gray-100 p-1.5 w-full shadow-md hover:bg-gray-100 transition">
                                <i class='bx bx-camera text-gray-600 text-lg'></i>
                            </button>  -->
                            </div>

                        </div>
                        <div class="infodata p-4 px-6 border-b-[1px] border-gray-200 flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <label  class="w-full max-w-[200px]">Personnel ID</label>
                                <span class="font-normal text-gray-800 ml-[295px]"><?php echo $PersonnelID; ?></span>
                            </div>
                        </div>
                        <div class="infodata p-4 px-6 border-b-[1px] border-gray-200 flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <label  class="w-full max-w-[200px]">Name</label>
                                <span class="font-normal text-gray-800 ml-[295px]"><?php echo $fullname; ?></span>
                            </div>
                            <button id="NameSectionbtn" onclick="showSection('NameSection')" type="button" class="px-10 text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                <i class="bx bx-chevron-right font-bold text-2xl"></i>
                            </button>
                            </div>
                        <div class="infodata p-4 px-6 border-b-[1px] border-gray-200 flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <label  class="w-full max-w-[200px]">Role</label>
                                <span class="font-normal text-gray-800 ml-[295px]"><?php echo $role; ?></span>
                            </div>
                        </div>
                        <div class="infodata p-4 px-6 border-b-[1px] border-gray-200 flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <label class="w-full max-w-[200px]">Account status</label>
                                <span class="font-normal text-gray-800 ml-[295px]"><?php echo $Status; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="basicinfo rounded-lg border-[1px] border-gray-200 p-6 mb-6">
                        <div class="header flex flex-col items-start justify-center mb-4">
                            <h2 class="text-xl font-normal text-gray-800">Contact info</h2>
                            <p>Track your personal contact for reaching you.</p>
                        </div>
                        <div class="infodata p-4 px-6 mt-8 border-b-[1px] border-gray-200 flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <label class="w-full max-w-[200px]">Email</label>
                                <span class="font-normal text-gray-800 ml-[295px]"><?php echo $Email; ?></span>
                            </div>
                            <button id="EmailSectionbtn" onclick="showSection('EmailSection')" type="button" class="px-10 text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                <i class="bx bx-chevron-right font-bold text-2xl"></i>
                            </button>
                        </div>
                        <div class="infodata p-4 px-6 flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <label class="w-full max-w-[200px]">Phone Number</label>
                                <span class="font-normal text-gray-800 ml-[295px]"><?php echo $ContactNumber; ?></span>
                            </div>
                            <button id="PhoneSectionbtn" onclick="showSection('PhoneSection')" type="button" class="px-10 text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                <i class="bx bx-chevron-right font-bold text-2xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="basicinfo rounded-lg border-[1px] border-gray-200 p-6 mb-6">
                        <div class="header flex flex-col items-start justify-center mb-4">
                            <h2 class="text-xl font-normal text-gray-800">Address</h2>
                            <p>Manage address for a better reach.</p>
                        </div>
                        <div class="infodata p-4 px-6 mt-8 border-b-[1px] border-gray-200 flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <label class="w-full max-w-[200px]">Home</label>
                                <span class="font-normal text-gray-800 ml-[295px]"><?php echo $Address; ?></span>
                            </div>
                            <button id="AddressSectionbtn" onclick="showSection('AddressSection')" type="button" class="px-10 text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                <i class="bx bx-chevron-right font-bold text-2xl"></i>
                            </button>
                        </div>
                        <div class="infodata p-4 px-6 flex items-center justify-between">
                            <div class="flex items-center w-full">
                                <label class="w-full max-w-[200px]">Office</label>
                                <span class="font-normal text-gray-800 ml-[295px]"><?php echo $Office; ?></span>
                            </div>
                            <button id="OfficeSectionbtn" onclick="showSection('OfficeSection')" type="button" class="px-10 text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                <i class="bx bx-chevron-right font-bold text-2xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="md:w-2/3">
                                <h1 class="font-semibold text-gray-800 mb-4 text-2xl">Other info and preferences for<br>PIAMIS Services</h1>
                                <p class="text-gray-600 mb-6 text-xm">Ways to verify your authenticity and verifications.</p>
                            </div>
                            <div class="md:w-1/3 flex flex-col items-center">
                                <div class="overflow-hidden">
                                    <img src="../Images/infoCard.png" alt="Info Card" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex itemms-center justify-center gap-4 p-2">
                        <div class="portion w-full border-[1px] border-gray-200 rounded-lg p-4">
                            <div class="md:w-2/3 p-2 px-4">
                                <h1 class="font-normal text-gray-800 mb-4 text-2xl">Password</h1>
                                <p class="text-gray-600 mb-6 text-xm">Prioritize your security to protect your safety</p>
                            </div>
                            <div class="infodata p-4 mt-8 px-6 flex flex-col items-start justify-center">
                                <div class="flex items-center w-full">
                                    <label  class="w-full font-bold text-2xl">........</label>
                                    <button id="PasswordSectionbtn" onclick="showSection('PasswordSection')"type="button" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                        <i class="bx bx-chevron-right font-bold text-2xl"></i>
                                    </button>
                                </div>
                                <span class="font-normal text-gray-800">Last changed <?php echo $PasswordChangeDT; ?></span>
                            </div>
                        </div>
                        <div class="portion w-full border-[1px] border-gray-200 rounded-lg p-4">
                            <div class="md:w-2/3 p-2 px-4">
                                <h1 class="font-normal text-gray-800 mb-4 text-2xl">Other Preference</h1>
                                <p class="text-gray-600 mb-6 text-xm">Ways to verify your authenticity and verifications.</p>
                            </div>
                            <div class="infodata p-4 mt-8 px-6 border-b-[1px] border-gray-200 flex items-start justify-between">
                                <div class="flex items-center w-full gap-2 flex">
                                    <i class='bx bx-globe text-4xl' ></i>
                                    <div class="flex flex-col">
                                        <label  class="w-full text-xl">Language</label>
                                        <span class="font-normal text-gray-800 text-sm">English (United States)</span>
                                    </div>
                                </div>
                                <i class="bx bx-chevron-right font-bold text-2xl"></i>
                            </div>
                            <div class="infodata p-4 px-6 border-b-[1px] border-gray-200 flex items-start justify-between">
                                <div class="flex items-center w-full gap-2 flex">
                                    <i class='bx bxs-keyboard text-4xl' ></i>
                                    <div class="flex flex-col">
                                        <label  class="w-full text-xl">Input Tools</label>
                                        <span class="font-normal text-gray-800 text-sm">Type more easily in your language</span>
                                    </div>
                                </div>
                                <i class="bx bx-chevron-right font-bold text-2xl"></i>
                            </div>
                            <div class="infodata p-4 px-6 flex items-start justify-between">
                                <div class="flex items-center w-full gap-2 flex">
                                    <i class='bx bx-universal-access text-4xl' ></i>
                                    <div class="flex flex-col">
                                        <label  class="w-full text-xl">Accessibility</label>
                                        <span class="font-normal text-gray-800 text-sm">High-Contrast color</span>
                                    </div>
                                </div>
                                <i class="bx bx-chevron-right font-bold text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </section>
            
            
            </div>
        </main>
    </div>
</body>

<script>
    
    function showSection(sectionId) {
        document.getElementById(sectionId).classList.add('active');
        document.getElementById('MyProfileSection').classList.remove('active');
    }

    // document.getElementById('NameSectionbtn').addEventListener('click', function() {
    //     document.getElementById('NameSection').classList.add('active');
    //     document.getElementById('MyProfileSection').classList.remove('active');
    // });
    
    // document.getElementById('EmailSectionbtn').addEventListener('click', function() {
    //     document.getElementById('EmailSection').classList.add('active');
    //     document.getElementById('MyProfileSection').classList.remove('active');
    // });
    
    // document.getElementById('PhoneSectionbtn').addEventListener('click', function() {
    //     document.getElementById('PhoneSection').classList.add('active');
    //     document.getElementById('MyProfileSection').classList.remove('active');
    // });
    
    // document.getElementById('PasswordSectionbtn').addEventListener('click', function() {
    //     document.getElementById('PasswordSection').classList.add('active');
    //     document.getElementById('MyProfileSection').classList.remove('active');
    // });
</script>
</html>
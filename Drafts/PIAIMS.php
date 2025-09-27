<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('Please login first!'); window.location.href = 'Loginpage.php';</script>";
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
  <title>PIAIMS | Admin</title>
  <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../Stylesheet/Design.css">
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    

    <!-- Main Content -->
    <main class="main-content">
      <header class="main-header">
        <button class="sidebar-toggle" id="sidebarToggle">
          <i class='bx bx-menu'></i>
        </button>
        <h1 id="pageTitle" style="color: #002e2d;">ADMIN DASHBOARD</h1>
      </header>

      <!-- Dashboard Content -->
      <div class="content-container">
        <!-- Dashboard Section -->
        <?php include 'DashboardSection.php'; ?>
        
        <!-- User Management Section -->
        <?php include 'UserManagementSection.php'; ?>

        <!-- Patients Section -->
        <?php include 'PatientSection.php'; ?>

        <!-- Restock Section -->
        <?php include 'Inventory.php'; ?>
      </div>
    </main>
  </div>

  <script>
    // Global variables
    let currentPatientId = null;
    let restockItems = [];
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    document.addEventListener('DOMContentLoaded', function() {
      // DOM Elements
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const mainContent = document.querySelector('.main-content');
      const navItems = document.querySelectorAll('.nav-item');
      const contentSections = document.querySelectorAll('.content-section');
      const pageTitle = document.getElementById('pageTitle');
      const profileMenu = document.getElementById('profileMenu');
      const profileDropdown = document.getElementById('profileDropdown');
      
      // Toggle sidebar
      sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        
        // For mobile
        if (window.innerWidth < 768) {
          sidebar.classList.toggle('show');
        }
      });
      
      // Handle navigation
      navItems.forEach(item => {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          
          const section = this.getAttribute('data-section');
          switchSection(section);
        });
      });
      
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
      
      console.log('Doctor Management System initialized successfully!');
    });

    // Navigation functions
    function switchSection(section) {
      // Update active nav item
      document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
      document.querySelector(`[data-section="${section}"]`).classList.add('active');
      
      // Show corresponding section
      document.querySelectorAll('.content-section').forEach(content => {
        content.classList.remove('active');
        if (content.id === section + 'Section') {
          content.classList.add('active');
        }
      });
      
      // Update page title
      updatePageTitle(section);
      
      // Close sidebar on mobile
      if (window.innerWidth < 768) {
        document.getElementById('sidebar').classList.remove('show');
      }
    }
    
    function updatePageTitle(section) {
      const pageTitle = document.getElementById('pageTitle');
      switch(section) {
        case 'dashboard':
          pageTitle.textContent = 'Dashboard';
          break;
        case 'patients':
          pageTitle.textContent = 'Patient / Student Information';
          break;
        case 'appointments':
          pageTitle.textContent = 'Appointment Management';
          break;
        case 'userManagement':
          pageTitle.textContent = 'User Management';
          break;
        case 'restock':
          pageTitle.textContent = 'Medicine Restock';
          break;
        default:
          pageTitle.textContent = 'Doctor Dashboard';
      }
    }
</script>
</body>
</html>
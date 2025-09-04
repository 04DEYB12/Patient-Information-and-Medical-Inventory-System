<?php
include '../Landing Repository/Connection.php';
session_start();
if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('Please login first!'); window.location.href = 'Loginpage.php';</script>";
    exit();
}

$user_id = $_SESSION['User_ID'];

$stmt = mysqli_prepare($con, "SELECT * FROM clinicpersonnel as cp JOIN userrole as ur ON cp.RoleID = ur.RoleID WHERE cp.PersonnelID = ?");
if ($stmt) {
  mysqli_stmt_bind_param($stmt, "s", $user_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if ($instructor_row = mysqli_fetch_assoc($result)) {
    $firstname = htmlspecialchars($instructor_row['FirstName']);
    $lastname = htmlspecialchars($instructor_row['LastName']);
  } else {
    $firstname = $lastname = "Unknown";
  }
  $role = htmlspecialchars($instructor_row['RoleName']);
} else {
    die("Database query failed.");
}

$student_count_stmt = mysqli_prepare($con, "SELECT COUNT(*) as total_students FROM student");
if ($student_count_stmt) {
  mysqli_stmt_execute($student_count_stmt);
  $result = mysqli_stmt_get_result($student_count_stmt);
  if ($student_row = mysqli_fetch_assoc($result)) {
    $student_count = $student_row['total_students'];
  } else {
    die("Database query failed.");
  }
}

$clinicPersonnel_count_stmt = mysqli_prepare($con, "SELECT COUNT(*) as total_clinicPersonnel FROM clinicpersonnel");
if ($clinicPersonnel_count_stmt) {
  mysqli_stmt_execute($clinicPersonnel_count_stmt);
  $result = mysqli_stmt_get_result($clinicPersonnel_count_stmt);
  if ($clinicPersonnel_row = mysqli_fetch_assoc($result)) {
    $clinicPersonnel_count = $clinicPersonnel_row['total_clinicPersonnel'];
  } else {
    die("Database query failed.");
  }
}

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
  <style>
    /* Base Styles */
    :root {
      --color-nature-green-50: #F7FFF7;
      --color-nature-green-100: #E8F5E8;
      --color-nature-green-200: #D1E7DD;
      --color-nature-green-300: #95D5B2;
      --color-nature-green-400: #74C69D;
      --color-nature-green-500: #52B788;
      --color-nature-green-600: #40916C;
      --color-nature-green-700: #2D5A3D;
      --color-nature-green-800: #1B4332;
      --color-nature-green-900: #081C15;
      
      --color-golden-yellow: #FFD60A;
      --color-golden-yellow-light: #FFF3B0;
      --color-golden-yellow-dark: #E6C00A;
      
      --color-white: #FFFFFF;
      --color-red-500: #EF4444;
      --color-red-600: #DC2626;
      --color-red-100: #FEE2E2;
      
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      
      --radius-sm: 0.25rem;
      --radius: 0.5rem;
      --radius-md: 0.75rem;
      --radius-lg: 1rem;
      --radius-xl: 1.5rem;
      
      --transition: all 0.3s ease;
      --sidebar-width: 280px;
      --sidebar-collapsed-width: 80px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
      background: linear-gradient(to bottom right, var(--color-nature-green-50), var(--color-nature-green-100));
      color: var(--color-nature-green-800);
      line-height: 1.5;
      min-height: 100vh;
    }

    /* Layout */
    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: var(--sidebar-width);
      background: linear-gradient(to bottom, var(--color-nature-green-800), var(--color-nature-green-900));
      color: var(--color-nature-green-50);
      display: flex;
      flex-direction: column;
      transition: width 0.3s ease;
      position: fixed;
      height: 100vh;
      z-index: 100;
      overflow: hidden;
    }

    .sidebar.collapsed {
      width: var(--sidebar-collapsed-width);
    }

    .sidebar-header {
      padding: 1.5rem 1rem;
      border-bottom: 1px solid var(--color-nature-green-700);
      background: linear-gradient(to right, var(--color-nature-green-800), var(--color-nature-green-700));
      min-height: 88px;
      display: flex;
      align-items: center;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      width: 100%;
      position: relative;
    }

    .logo {
      width: 60px;
      height: 60px;
      background-color: #E8F5E8;
      border-radius: var(--radius-lg);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-md);
      flex-shrink: 0;
    }

    .logo i {
      font-size: 20px;
      color: var(--color-nature-green-800);
    }

    .brand {
      display: flex;
      flex-direction: column;
      opacity: 1;
      transition: opacity 0.3s ease;
      white-space: nowrap;
      overflow: hidden;
    }

    .sidebar.collapsed .brand {
      opacity: 0;
      width: 0;
    }

    .brand h1 {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--color-golden-yellow);
      margin: 0;
    }

    .brand span {
      font-size: 0.75rem;
      color: var(--color-nature-green-100);
    }

    .sidebar-nav {
      flex: 1;
      padding: 1rem 0;
      overflow-y: auto;
      overflow-x: hidden;
    }

    .nav-group-title {
      padding: 0.5rem 1.5rem;
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--color-golden-yellow);
      text-transform: uppercase;
      opacity: 1;
      transition: opacity 0.3s ease;
      white-space: nowrap;
    }

    .sidebar.collapsed .nav-group-title {
      opacity: 0;
      height: 0;
      padding: 0;
      margin: 0;
    }

    .nav-items {
      list-style: none;
      padding: 0.5rem 0.75rem;
    }

    .nav-item {
      margin-bottom: 0.25rem;
      border-radius: var(--radius);
      transition: var(--transition);
      position: relative;
    }

    .nav-item a {
      display: flex;
      align-items: center;
      padding: 0.75rem 1rem;
      color: var(--color-nature-green-50);
      text-decoration: none;
      font-weight: 500;
      border-radius: var(--radius);
      transition: var(--transition);
      gap: 0.75rem;
      position: relative;
      overflow: hidden;
    }

    .nav-item a i {
      font-size: 20px;
      flex-shrink: 0;
    }

    .nav-item a span {
      opacity: 1;
      transition: opacity 0.3s ease;
      white-space: nowrap;
    }

    .sidebar.collapsed .nav-item a {
      justify-content: center;
      padding: 0.75rem;
    }

    .sidebar.collapsed .nav-item a span {
      opacity: 0;
      width: 0;
      overflow: hidden;
    }

    .nav-item:hover {
      background-color: var(--color-nature-green-700);
    }

    .nav-item.active {
      background-color: var(--color-golden-yellow);
    }

    .nav-item.active a {
      color: var(--color-nature-green-800);
      font-weight: 600;
    }

    .sidebar-footer {
      padding: 1rem;
      border-top: 1px solid var(--color-nature-green-700);
      background-color: var(--color-nature-green-900);
    }

    .profile-menu {
      position: relative;
    }

    .profile-info {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.5rem;
      border-radius: var(--radius);
      cursor: pointer;
      transition: var(--transition);
      overflow: hidden;
    }

    .profile-info:hover {
      background-color: var(--color-nature-green-700);
    }

    .avatar {
      width: 40px;
      height: 40px;
      border-radius: var(--radius-lg);
      overflow: hidden;
      border: 2px solid var(--color-golden-yellow);
      flex-shrink: 0;
    }

    .avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .avatar span {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      height: 100%;
      background-color: var(--color-golden-yellow);
      color: var(--color-nature-green-800);
      font-weight: 600;
    }

    .user-info {
      flex: 1;
      min-width: 0;
      opacity: 1;
      transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .user-info {
      opacity: 0;
      width: 0;
      overflow: hidden;
    }

    .user-info h3 {
      font-size: 0.875rem;
      font-weight: 600;
      margin: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      color: var(--color-nature-green-50);
    }

    .user-info span {
      font-size: 0.75rem;
      color: var(--color-nature-green-200);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display: block;
    }

    .user-info .role {
      color: var(--color-golden-yellow);
      font-weight: 500;
    }

    .dropdown-icon {
      font-size: 16px;
      color: var(--color-nature-green-200);
      transition: var(--transition);
      flex-shrink: 0;
      opacity: 1;
    }

    .sidebar.collapsed .dropdown-icon {
      opacity: 0;
      width: 0;
    }

    .profile-dropdown {
      position: absolute;
      bottom: calc(100% + 0.5rem);
      left: 0;
      right: 0;
      background-color: var(--color-nature-green-50);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--color-nature-green-300);
      overflow: hidden;
      z-index: 10;
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
      transition: var(--transition);
      min-width: 200px;
    }

    .sidebar.collapsed .profile-dropdown {
      left: calc(100% + 10px);
      bottom: 0;
    }

    .profile-dropdown.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .dropdown-header {
      padding: 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .dropdown-header .user-info h3 {
      color: var(--color-nature-green-800);
    }

    .dropdown-divider {
      height: 1px;
      background-color: var(--color-nature-green-200);
      margin: 0.25rem 0;
    }

    .dropdown-menu {
      list-style: none;
      padding: 0.5rem 0;
    }

    .dropdown-menu li a {
      display: flex;
      align-items: center;
      padding: 0.5rem 0.75rem;
      color: var(--color-nature-green-800);
      text-decoration: none;
      font-size: 0.875rem;
      transition: var(--transition);
    }

    .dropdown-menu li a i {
      font-size: 16px;
      margin-right: 0.5rem;
      color: var(--color-golden-yellow);
    }

    .dropdown-menu li a:hover {
      background-color: var(--color-nature-green-100);
    }

    .dropdown-menu li a.logout {
      color: var(--color-red-600);
    }

    .dropdown-menu li a.logout i {
      color: var(--color-red-600);
    }

    .dropdown-menu li a.logout:hover {
      background-color: var(--color-red-100);
    }

    /* Main Content */
    .main-content {
      flex: 1;
      margin-left: var(--sidebar-width);
      transition: margin-left 0.3s ease;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .main-content.expanded {
      margin-left: var(--sidebar-collapsed-width);
    }

    .main-header {
      height: 64px;
      display: flex;
      align-items: center;
      padding: 0 1.5rem;
      background-color: var(--color-nature-green-50);
      border-bottom: 1px solid var(--color-nature-green-200);
      position: sticky;
      top: 0;
      z-index: 10;
      backdrop-filter: blur(8px);
    }

    .sidebar-toggle {
      width: 40px;
      height: 40px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      background: transparent;
      border: none;
      cursor: pointer;
      margin-right: 1rem;
      transition: var(--transition);
    }

    .sidebar-toggle:hover {
      background-color: rgba(255, 214, 10, 0.2);
    }

    .sidebar-toggle i {
      font-size: 20px;
      color: var(--color-nature-green-800);
    }

    .main-header h1 {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--color-golden-yellow);
    }

    .content-container {
      flex: 1;
      padding: 1.5rem;
    }

    .content-section {
      display: none;
      animation: fadeIn 0.3s ease-in-out;
    }

    .content-section.active {
      display: block;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .stat-card {
      background: linear-gradient(to bottom right, var(--color-nature-green-50), var(--color-nature-green-100));
      border: 1px solid var(--color-nature-green-200);
      border-radius: var(--radius-lg);
      padding: 1.25rem;
      box-shadow: var(--shadow-md);
      transition: var(--transition);
    }

    .stat-card:hover {
      box-shadow: var(--shadow-lg);
      transform: translateY(-2px);
    }

    .stat-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }

    .stat-header h3 {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--color-nature-green-700);
    }

    .stat-header i {
      font-size: 20px;
      color: var(--color-golden-yellow);
    }

    .stat-content .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--color-golden-yellow);
      margin-bottom: 0.25rem;
    }

    .stat-change {
      font-size: 0.75rem;
      color: var(--color-nature-green-600);
    }

    .stat-change .positive {
      color: var(--color-nature-green-500);
      font-weight: 600;
    }

    .card {
      background: linear-gradient(to bottom right, var(--color-nature-green-50), var(--color-nature-green-100));
      border: 1px solid var(--color-nature-green-200);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-md);
      overflow: hidden;
      transition: var(--transition);
      margin-bottom: 1.5rem;
    }

    .card:hover {
      box-shadow: var(--shadow-lg);
    }

    .card-header {
      padding: 1.25rem;
      border-bottom: 1px solid var(--color-nature-green-200);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-header h2 {
      font-size: 1.25rem;
      font-weight: 700;
      color:rgb(7, 86, 59);
    }

    .card-actions {
      display: flex;
      gap: 0.75rem;
      align-items: center;
    }

    .card-content {
      padding: 1.25rem;
      display: block;
    }

    .processing-form {
      display: none;
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid var(--color-nature-green-200);
    }

    .processing-form.active {
      display: block;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-group.full-width {
      grid-column: span 2;
    }

    .form-group label {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--color-nature-green-700);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      padding: 0.5rem 0.75rem;
      border-radius: var(--radius);
      border: 1px solid var(--color-nature-green-300);
      background-color: var(--color-white);
      font-size: 0.875rem;
      transition: var(--transition);
    }

    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--color-golden-yellow);
      box-shadow: 0 0 0 3px rgba(255, 214, 10, 0.2);
    }

    .medicine-list {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .medicine-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem;
      background: var(--color-nature-green-50);
      border-radius: var(--radius);
    }

    .medicine-item input {
      flex: 1;
      margin: 0;
    }

    .medicine-item button {
      background: var(--color-red-500);
      color: white;
      border: none;
      border-radius: var(--radius);
      padding: 0.25rem 0.5rem;
      cursor: pointer;
      font-size: 0.75rem;
    }

    /* Buttons */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.5rem 1rem;
      border-radius: var(--radius);
      font-weight: 500;
      font-size: 0.875rem;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      gap: 0.5rem;
      text-decoration: none;
    }

    .btn i {
      font-size: 16px;
    }

    .btn-primary {
      background-color: var(--color-golden-yellow);
      color: var(--color-nature-green-800);
      box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
      background-color: var(--color-golden-yellow-dark);
      box-shadow: var(--shadow);
    }

    .btn-secondary {
      background-color: var(--color-nature-green-600);
      color: var(--color-white);
      box-shadow: var(--shadow-sm);
    }

    .btn-secondary:hover {
      background-color: var(--color-nature-green-700);
      box-shadow: var(--shadow);
    }

    .btn-outline {
      background-color: transparent;
      border: 1px solid var(--color-nature-green-300);
      color: var(--color-nature-green-700);
    }

    .btn-outline:hover {
      background-color: var(--color-nature-green-100);
    }

    .btn-danger {
      background-color: var(--color-red-500);
      color: white;
    }

    .btn-danger:hover {
      background-color: var(--color-red-600);
    }

    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }

    /* Status Badges */
    .status-badge {
      padding: 0.25rem 0.5rem;
      border-radius: var(--radius);
      font-size: 0.75rem;
      font-weight: 500;
      white-space: nowrap;
    }

    .status-badge.waiting {
      background-color: var(--color-golden-yellow-light);
      color: var(--color-nature-green-800);
    }

    .status-badge.processing {
      background-color: var(--color-nature-green-600);
      color: var(--color-nature-green-50);
    }

    .status-badge.completed {
      background-color: var(--color-nature-green-200);
      color: var(--color-nature-green-800);
    }

    .status-badge.urgent {
      background-color: var(--color-red-500);
      color: white;
    }

    .status-badge.scheduled {
      background-color: var(--color-nature-green-600);
      color: var(--color-nature-green-50);
    }

    /* Calendar */
    .calendar-container {
      background: var(--color-white);
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-md);
    }

    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      background: var(--color-nature-green-600);
      color: white;
    }

    .calendar-nav {
      display: flex;
      gap: 0.5rem;
    }

    .calendar-nav button {
      background: transparent;
      border: none;
      color: white;
      padding: 0.5rem;
      border-radius: var(--radius);
      cursor: pointer;
      transition: var(--transition);
    }

    .calendar-nav button:hover {
      background: rgba(255, 255, 255, 0.1);
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
    }

    .calendar-day-header {
      padding: 0.75rem;
      text-align: center;
      font-weight: 600;
      background: var(--color-nature-green-100);
      color: var(--color-nature-green-800);
      font-size: 0.875rem;
    }

    .calendar-day {
      min-height: 100px;
      padding: 0.5rem;
      border: 1px solid var(--color-nature-green-200);
      cursor: pointer;
      transition: var(--transition);
      position: relative;
    }

    .calendar-day:hover {
      background: var(--color-nature-green-50);
    }

    .calendar-day.available {
      background: var(--color-nature-green-50);
    }

    .calendar-day.unavailable {
      background: var(--color-red-100);
      color: var(--color-red-600);
    }

    .calendar-day.selected {
      background: var(--color-golden-yellow-light);
      border-color: var(--color-golden-yellow);
    }

    .day-number {
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .day-appointments {
      font-size: 0.75rem;
      color: var(--color-nature-green-600);
    }

    /* Tables */
    .table-container {
      overflow-x: auto;
      border-radius: var(--radius);
      border: 1px solid var(--color-nature-green-200);
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
    }

    .data-table thead {
      background-color: var(--color-nature-green-100);
    }

    .data-table th {
      padding: 0.75rem 1rem;
      text-align: left;
      font-weight: 600;
      color: var(--color-nature-green-800);
      border-bottom: 1px solid var(--color-nature-green-200);
    }

    .data-table td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid var(--color-nature-green-200);
      color: var(--color-nature-green-700);
    }

    .data-table tr:hover {
      background-color: var(--color-nature-green-50);
    }

    .data-table tr:last-child td {
      border-bottom: none;
    }

    /* Restock */
    .medicine-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1rem;
    }

    .medicine-card {
      background: var(--color-white);
      border: 1px solid var(--color-nature-green-200);
      border-radius: var(--radius-lg);
      padding: 1rem;
      transition: var(--transition);
    }

    .medicine-card:hover {
      box-shadow: var(--shadow-md);
    }

    .medicine-card.low-stock {
      border-color: var(--color-red-500);
      background: var(--color-red-100);
    }

    .medicine-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.5rem;
    }

    .medicine-name {
      font-weight: 600;
      color: var(--color-nature-green-800);
    }

    .stock-level {
      font-size: 0.875rem;
      color: var(--color-nature-green-600);
    }

    .stock-level.low {
      color: var(--color-red-600);
      font-weight: 600;
    }

    .restock-controls {
      display: flex;
      gap: 0.5rem;
      align-items: center;
      margin-top: 0.5rem;
    }

    .quantity-input {
      width: 80px;
      padding: 0.25rem 0.5rem;
      border: 1px solid var(--color-nature-green-300);
      border-radius: var(--radius);
      text-align: center;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal.show {
      display: flex;
    }

    .modal-content {
      background: var(--color-white);
      border-radius: var(--radius-lg);
      padding: 2rem;
      max-width: 600px;
      width: 90%;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .modal-header h3 {
      color: var(--color-golden-yellow);
      font-size: 1.25rem;
      font-weight: 600;
    }

    .modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--color-nature-green-600);
    }

    /* Print Styles */
    @media print {
      .sidebar,
      .main-header,
      .card-actions,
      .btn {
        display: none !important;
      }
      
      .main-content {
        margin-left: 0 !important;
      }
      
      .card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #000;
      }
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .medicine-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 0;
        overflow: hidden;
      }
      
      .sidebar.show {
        width: var(--sidebar-width);
      }
      
      .main-content {
        margin-left: 0;
      }
      
      .main-content.expanded {
        margin-left: 0;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
      }
      
      .form-grid {
        grid-template-columns: 1fr;
      }
      
      .form-group.full-width {
        grid-column: span 1;
      }
      
      .medicine-grid {
        grid-template-columns: 1fr;
      }
      
      .calendar-day {
        min-height: 80px;
      }
    }

    @media (max-width: 480px) {
      .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }
      
      .card-actions {
        width: 100%;
        flex-wrap: wrap;
      }
      
      .patient-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }
      
      .patient-actions {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="logo-container">
          <div class="logo">
            <img src="../Images/GranbyLogo.png" alt="Logo" width="50" height="50">
          </div>
          <div class="brand">
            <h1>GCST</h1>
            <span>Patient Information &<br>Inventory Management System</span>
          </div>
        </div>
      </div>  
      <nav class="sidebar-nav">
        <div class="nav-group">
          <h2 class="nav-group-title">Navigation</h2>
          <ul class="nav-items">
            <li class="nav-item active" data-section="dashboard">
              <a href="#dashboard">
                <i class='bx bx-pulse'></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="nav-item" data-section="users">
              <a href="#users">
                <i class='bx bx-user'></i>
                <span>User Accounts</span>
              </a>
            </li>
            <li class="nav-item" data-section="patients">
              <a href="#patients">
                <i class='bx bx-user-plus'></i>
                <span>Patients</span>
              </a>
            </li>
            <li class="nav-item" data-section="restock">
              <a href="#restock">
                <i class='bx bx-package'></i>
                <span>Inventory</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="profile-menu" id="profileMenu">
          <div class="profile-info">
            <div class="avatar">
              <span><?php echo $firstname[0] . $lastname[0]; ?></span>
            </div>
            <div class="user-info">
              <h3><?php echo $firstname . " " . $lastname; ?></h3>
              <span class="role"><?php echo $role; ?></span>
            </div>
            <i class='bx bx-chevron-up dropdown-icon'></i>
          </div>
          <div class="profile-dropdown" id="profileDropdown">
            <div class="dropdown-header">
              <div class="avatar">
                <span><?php echo $firstname[0] . $lastname[0]; ?></span>
              </div>
              <div class="user-info">
                <h3><?php echo $firstname . " " . $lastname; ?></h3>
                <span class="role"><?php echo $role; ?></span>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <ul class="dropdown-menu">
              <li><a href="#profile"><i class='bx bx-user'></i> Profile</a></li>
              <li><a href="#settings"><i class='bx bx-cog'></i> Settings</a></li>
              <li><a href="#notifications"><i class='bx bx-bell'></i> Notifications</a></li>
              <div class="dropdown-divider"></div>
              <li><a href="../Landing Repository/logout.php" class="logout"><i class='bx bx-log-out'></i> Log out</a></li>
            </ul>
          </div>
        </div>
      </div>
    </aside>

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
        case 'schedule':
          pageTitle.textContent = 'Schedule Management';
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HealthTrack - Admin Management Dashboard</title>
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
      --color-orange-500: #F97316;
      --color-orange-100: #FED7AA;
      
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

    /* Offline Icons */
    .icon {
      display: inline-block;
      width: 20px;
      height: 20px;
      fill: currentColor;
      vertical-align: middle;
    }

    .icon-sm {
      width: 16px;
      height: 16px;
    }

    .icon-lg {
      width: 24px;
      height: 24px;
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
      width: 40px;
      height: 40px;
      background-color: var(--color-golden-yellow);
      border-radius: var(--radius-lg);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-md);
      flex-shrink: 0;
      color: darkgreen;
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
      opacity: .9;
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

    .nav-item a .icon {
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
    
      color: white;
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
      display: flex;
      align-items: center;
      justify-content: center;
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
      color: var(--color-golden-yellow);
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display: block;
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
      flex-wrap: wrap;
      gap: 1rem;
    }

    .card-header h2 {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--color-golden-yellow);
    }

    .card-actions {
      display: flex;
      gap: 0.75rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .card-content {
      padding: 1.25rem;
    }

    /* Dashboard Specific Styles */
    .dashboard-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .activity-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem;
      border-radius: var(--radius);
      transition: var(--transition);
    }

    .activity-item:hover {
      background-color: var(--color-nature-green-100);
    }

    .activity-icon {
      width: 32px;
      height: 32px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .activity-icon.success {
      background-color: var(--color-nature-green-600);
      color: white;
    }

    .activity-icon.warning {
      background-color: var(--color-orange-500);
      color: white;
    }

    .activity-icon.info {
      background-color: var(--color-golden-yellow);
      color: var(--color-nature-green-800);
    }

    .activity-content {
      flex: 1;
      min-width: 0;
    }

    .activity-title {
      font-weight: 600;
      color: var(--color-nature-green-800);
      font-size: 0.875rem;
    }

    .activity-description {
      font-size: 0.75rem;
      color: var(--color-nature-green-600);
    }

    .activity-time {
      font-size: 0.75rem;
      color: var(--color-nature-green-500);
      white-space: nowrap;
    }

    .quick-actions {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.75rem;
    }

    .quick-action {
      padding: 1rem;
      border: 1px solid var(--color-nature-green-200);
      border-radius: var(--radius);
      text-align: center;
      cursor: pointer;
      transition: var(--transition);
      background: var(--color-white);
    }

    .quick-action:hover {
      background-color: var(--color-nature-green-100);
      border-color: var(--color-golden-yellow);
    }

    .quick-action .icon {
      margin-bottom: 0.5rem;
      color: var(--color-golden-yellow);
    }

    .quick-action span {
      display: block;
      font-size: 0.75rem;
      font-weight: 500;
      color: var(--color-nature-green-700);
    }

    /* Sub Navigation for Inventory */
    .sub-nav {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .sub-nav-btn {
      padding: 0.5rem 1rem;
      border: 1px solid var(--color-nature-green-300);
      background: var(--color-white);
      color: var(--color-nature-green-700);
      border-radius: var(--radius);
      cursor: pointer;
      transition: var(--transition);
      font-size: 0.875rem;
      font-weight: 500;
    }

    .sub-nav-btn:hover {
      background: var(--color-nature-green-100);
    }

    .sub-nav-btn.active {
      background: var(--color-golden-yellow);
      color: var(--color-nature-green-800);
      border-color: var(--color-golden-yellow);
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
      cursor: pointer;
      position: relative;
    }

    .data-table th:hover {
      background-color: var(--color-nature-green-200);
    }

    .data-table th.sortable::after {
      content: '↕';
      position: absolute;
      right: 0.5rem;
      opacity: 0.5;
    }

    .data-table th.sort-asc::after {
      content: '↑';
      opacity: 1;
    }

    .data-table th.sort-desc::after {
      content: '↓';
      opacity: 1;
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

    /* Status Badges */
    .status-badge {
      padding: 0.25rem 0.5rem;
      border-radius: var(--radius);
      font-size: 0.75rem;
      font-weight: 500;
      white-space: nowrap;
    }

    .status-badge.active {
      background-color: var(--color-nature-green-600);
      color: var(--color-nature-green-50);
    }

    .status-badge.inactive {
      background-color: var(--color-red-500);
      color: white;
    }

    .status-badge.resigned {
      background-color: var(--color-orange-500);
      color: white;
    }

    .status-badge.left {
      background-color: var(--color-red-600);
      color: white;
    }

    .status-badge.in-stock {
      background-color: var(--color-nature-green-600);
      color: var(--color-nature-green-50);
    }

    .status-badge.low-stock {
      background-color: var(--color-orange-500);
      color: white;
    }

    .status-badge.out-of-stock {
      background-color: var(--color-red-500);
      color: white;
    }

    .status-badge.expired {
      background-color: var(--color-red-600);
      color: white;
    }

    .status-badge.near-expiry {
      background-color: var(--color-orange-500);
      color: white;
    }

    .status-badge.completed {
      background-color: var(--color-nature-green-600);
      color: white;
    }

    .status-badge.pending {
      background-color: var(--color-orange-500);
      color: white;
    }

    .status-badge.cancelled {
      background-color: var(--color-red-500);
      color: white;
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

    .btn-outline {
      background-color: transparent;
      border: 1px solid var(--color-nature-green-300);
      color: var(--color-nature-green-700);
    }

    .btn-outline:hover {
      background-color: var(--color-nature-green-100);
    }

    .btn-success {
      background-color: var(--color-nature-green-600);
      color: white;
    }

    .btn-success:hover {
      background-color: var(--color-nature-green-700);
    }

    .btn-danger {
      background-color: var(--color-red-500);
      color: white;
    }

    .btn-danger:hover {
      background-color: var(--color-red-600);
    }

    .btn-icon {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: var(--radius);
      background-color: transparent;
      border: none;
      cursor: pointer;
      transition: var(--transition);
    }

    .btn-icon:hover {
      background-color: rgba(255, 214, 10, 0.2);
    }

    .action-buttons {
      display: flex;
      gap: 0.25rem;
    }

    /* Forms */
    .search-container {
      position: relative;
    }

    .search-container input {
      padding: 0.5rem 0.75rem 0.5rem 2.25rem;
      border-radius: var(--radius);
      border: 1px solid var(--color-nature-green-300);
      background-color: var(--color-white);
      font-size: 0.875rem;
      width: 250px;
      transition: var(--transition);
    }

    .search-container input:focus {
      outline: none;
      border-color: var(--color-golden-yellow);
      box-shadow: 0 0 0 3px rgba(255, 214, 10, 0.2);
    }

    .select-container select {
      appearance: none;
      padding: 0.5rem 2rem 0.5rem 0.75rem;
      border-radius: var(--radius);
      border: 1px solid var(--color-nature-green-300);
      background-color: var(--color-white);
      font-size: 0.875rem;
      transition: var(--transition);
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2340916C' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.5rem center;
      background-size: 16px;
    }

    .select-container select:focus {
      outline: none;
      border-color: var(--color-golden-yellow);
      box-shadow: 0 0 0 3px rgba(255, 214, 10, 0.2);
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--color-nature-green-700);
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 0.5rem 0.75rem;
      border: 1px solid var(--color-nature-green-300);
      border-radius: var(--radius);
      font-size: 0.875rem;
      transition: var(--transition);
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: var(--color-golden-yellow);
      box-shadow: 0 0 0 3px rgba(255, 214, 10, 0.2);
    }

    .form-row {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
    }

    .modal.show {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: var(--color-white);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-lg);
      max-width: 800px;
      width: 90%;
      max-height: 80vh;
      overflow-y: auto;
    }

    .modal-content.large {
      max-width: 1200px;
    }

    .modal-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--color-nature-green-200);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-header h3 {
      color: var(--color-golden-yellow);
      font-size: 1.25rem;
      font-weight: 700;
    }

    .modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--color-nature-green-600);
    }

    .modal-body {
      padding: 1.5rem;
    }

    .modal-footer {
      padding: 1rem 1.5rem;
      border-top: 1px solid var(--color-nature-green-200);
      display: flex;
      justify-content: flex-end;
      gap: 0.75rem;
    }

    /* Toggle Switch */
    .toggle-switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
    }

    .toggle-switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .toggle-slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: var(--color-red-500);
      transition: 0.4s;
      border-radius: 24px;
    }

    .toggle-slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: 0.4s;
      border-radius: 50%;
    }

    input:checked + .toggle-slider {
      background-color: var(--color-nature-green-600);
    }

    input:checked + .toggle-slider:before {
      transform: translateX(26px);
    }

    /* Record Details */
    .record-details {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .record-field {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }

    .record-field label {
      font-weight: 600;
      color: var(--color-nature-green-700);
      font-size: 0.875rem;
    }

    .record-field span {
      color: var(--color-nature-green-800);
    }

    .record-field.full-width {
      grid-column: span 2;
    }

    .vitals-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.5rem;
      margin-top: 1rem;
    }

    /* Responsive */
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

      .dashboard-grid {
        grid-template-columns: 1fr;
      }
      
      .card-header {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .card-actions {
        width: 100%;
        flex-wrap: wrap;
      }
      
      .search-container input {
        width: 100%;
      }

      .record-details {
        grid-template-columns: 1fr;
      }

      .record-field.full-width {
        grid-column: span 1;
      }

      .vitals-grid {
        grid-template-columns: 1fr;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .quick-actions {
        grid-template-columns: 1fr;
      }
    }
    .sidebar i{
      font-size: 20px;
    }
    .content-container  i{
      font-size: 25px;
      color: darkgreen;
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
            <i class='bx bx-leaf'></i>
          </div>
          <div class="brand">
             <h1>Healthtrack</h1>
            <span>Patient Information &<br>Inventory Management System</span>
          </div>
        </div>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-group">
          <h2 class="nav-group-title">Management</h2>
          <ul class="nav-items">
            <li class="nav-item active" data-section="dashboard">
              <a href="#dashboard">
                <i class='bx bx-pulse'></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="nav-item" data-section="staff">
              <a href="#staff">
                <i class='bx bx-user-check' ></i>
                <span>Staff Management</span>
              </a>
            </li>
            <li class="nav-item" data-section="inventory">
              <a href="#inventory">
                <i class='bx bx-capsule' ></i>
                <span>Medical Inventory</span>
              </a>
            </li>
            <li class="nav-item" data-section="appointments">
              <a href="#appointments">
                <i class='bx bx-calendar-check' ></i>
                <span>Appointments</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="profile-info">
          <div class="avatar">AM</div>
          <div class="user-info">
            <h3>Mr. Anthony Arisgado</h3>
            <span>System Administrator</span>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header class="main-header">
        <button class="sidebar-toggle" id="sidebarToggle">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <h1 id="pageTitle">Admin Dashboard</h1>
      </header>

      <div class="content-container">
        <!-- Dashboard Section -->
        <section class="content-section active" id="dashboardSection">
          <!-- Stats Grid -->
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-header">
                <h3>Total Staff</h3>
                <i class='bx bx-user-check' ></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">45</div>
                <p class="stat-change">38 Active, 7 Inactive</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Medicine Categories</h3>
                <i class='bx bx-category' ></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">12</div>
                <p class="stat-change">156 Total Medicines</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Low Stock Items</h3>
                <i class='bx bx-trending-down'></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">8</div>
                <p class="stat-change">Requires Restocking</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Appointments Today</h3>
                <i class='bx bx-list-check' ></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">24</div>
                <p class="stat-change">18 Completed, 6 Pending</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Monthly Revenue</h3>
                <i class='bx bx-dollar' ></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">$45,230</div>
                <p class="stat-change">+12% from last month</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>System Alerts</h3>
                <i class='bx bxs-bell-ring' ></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">3</div>
                <p class="stat-change">2 Critical, 1 Warning</p>
              </div>
            </div>
          </div>

          <!-- Dashboard Grid -->
          <div class="dashboard-grid">
            <!-- Recent Activities -->
            <div class="card">
              <div class="card-header">
                <h2>Recent Activities</h2>
                <button class="btn btn-outline" onclick="openTransactionsReport()">
                  <i class='bx bxs-report' ></i>
                  View Reports
                </button>
              </div>
              <div class="card-content">
                <div class="activity-item">
                  <div class="activity-icon success">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M20 6 9 17l-5-5"/>
                    </svg>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">Medicine Restocked</div>
                    <div class="activity-description">Paracetamol 500mg - 200 units added</div>
                  </div>
                  <div class="activity-time">2 min ago</div>
                </div>
                <div class="activity-item">
                  <div class="activity-icon info">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                      <circle cx="9" cy="7" r="4"/>
                    </svg>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">New Staff Login</div>
                    <div class="activity-description">Dr. Sarah Wilson logged in</div>
                  </div>
                  <div class="activity-time">5 min ago</div>
                </div>
                <div class="activity-item">
                  <div class="activity-icon warning">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                      <path d="M12 9v4"/>
                      <path d="m12 17 .01 0"/>
                    </svg>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">Low Stock Alert</div>
                    <div class="activity-description">Insulin Glargine below minimum threshold</div>
                  </div>
                  <div class="activity-time">15 min ago</div>
                </div>
                <div class="activity-item">
                  <div class="activity-icon success">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                      <line x1="16" y1="2" x2="16" y2="6"/>
                      <line x1="8" y1="2" x2="8" y2="6"/>
                      <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">Appointment Completed</div>
                    <div class="activity-description">Patient Sarah Johnson - Follow-up</div>
                  </div>
                  <div class="activity-time">1 hour ago</div>
                </div>
                <div class="activity-item">
                  <div class="activity-icon info">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14,2 14,8 20,8"/>
                    </svg>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">Report Generated</div>
                    <div class="activity-description">Monthly inventory report created</div>
                  </div>
                  <div class="activity-time">2 hours ago</div>
                </div>
              </div>
            </div>

            

          <!-- System Status -->
          <div class="card">
            <div class="card-header">
              <h2>System Status</h2>
            </div>
            <div class="card-content">
              <div class="stats-grid">
                <div class="stat-card">
                  <div class="stat-header">
                    <h3>Database Status</h3>
                    <div class="status-badge active">Online</div>
                  </div>
                  <div class="stat-content">
                    <div class="stat-value">99.9%</div>
                    <p class="stat-change">Uptime this month</p>
                  </div>
                </div>
                <div class="stat-card">
                  <div class="stat-header">
                    <h3>Backup Status</h3>
                    <div class="status-badge active">Completed</div>
                  </div>
                  <div class="stat-content">
                    <div class="stat-value">2 hrs</div>
                    <p class="stat-change">Last backup ago</p>
                  </div>
                </div>
                <div class="stat-card">
                  <div class="stat-header">
                    <h3>Active Users</h3>
                    <div class="status-badge active">Online</div>
                  </div>
                  <div class="stat-content">
                    <div class="stat-value">23</div>
                    <p class="stat-change">Currently logged in</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Staff Management Section -->
        <section class="content-section" id="staffSection">
          <div class="card">
            <div class="card-header">
              <h2>Staff Management</h2>
              <div class="card-actions">
                <div class="search-container">
                  <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                  </svg>
                  <input type="text" placeholder="Search staff..." id="staffSearch">
                </div>
                <div class="select-container">
                  <select id="staffStatusFilter">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="resigned">Resigned</option>
                    <option value="left">Left</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="card-content">
              <div class="table-container">
                <table class="data-table" id="staffTable">
                  <thead>
                    <tr>
                      <th>Staff ID</th>
                      <th>Name</th>
                      <th>Position</th>
                      <th>Department</th>
                      <th>Hire Date</th>
                      <th>Last Login</th>
                      <th>Status</th>
                      <th>Account</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>ST001</td>
                      <td>Dr. Sarah Wilson</td>
                      <td>Senior Doctor</td>
                      <td>Cardiology</td>
                      <td>2020-01-15</td>
                      <td>2024-01-18 09:30</td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <label class="toggle-switch">
                          <input type="checkbox" checked>
                          <span class="toggle-slider"></span>
                        </label>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewStaffLogs('ST001')" title="View Logs">
                            <i class='bx bx-book-content' ></i>
                          </button>
                          
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>ST002</td>
                      <td>Nurse Mary Johnson</td>
                      <td>Head Nurse</td>
                      <td>Emergency</td>
                      <td>2019-03-20</td>
                      <td>2024-01-18 08:15</td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <label class="toggle-switch">
                          <input type="checkbox" checked>
                          <span class="toggle-slider"></span>
                        </label>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewStaffLogs('ST002')" title="View Logs">
                            <i class='bx bx-book-content' ></i>
                          </button>
                          
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>ST003</td>
                      <td>Dr. Michael Chen</td>
                      <td>Pediatrician</td>
                      <td>Pediatrics</td>
                      <td>2021-06-10</td>
                      <td>2024-01-17 16:45</td>
                      <td><span class="status-badge resigned">Resigned</span></td>
                      <td>
                        <label class="toggle-switch">
                          <input type="checkbox">
                          <span class="toggle-slider"></span>
                        </label>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewStaffLogs('ST003')" title="View Logs">
                           <i class='bx bx-book-content' ></i>
                          </button>
                          
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>ST004</td>
                      <td>Tech. Robert Davis</td>
                      <td>Lab Technician</td>
                      <td>Laboratory</td>
                      <td>2022-02-14</td>
                      <td>2024-01-18 07:30</td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <label class="toggle-switch">
                          <input type="checkbox" checked>
                          <span class="toggle-slider"></span>
                        </label>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewStaffLogs('ST004')" title="View Logs">
                           <i class='bx bx-book-content' ></i>
                          </button>
                           
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>ST005</td>
                      <td>Admin Lisa Brown</td>
                      <td>Administrator</td>
                      <td>Administration</td>
                      <td>2018-11-05</td>
                      <td>2024-01-15 14:20</td>
                      <td><span class="status-badge left">Left</span></td>
                      <td>
                        <label class="toggle-switch">
                          <input type="checkbox">
                          <span class="toggle-slider"></span>
                        </label>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewStaffLogs('ST005')" title="View Logs">
                            <i class='bx bx-book-content' ></i>
                          </button>
                           
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- Medical Inventory Section -->
        <section class="content-section" id="inventorySection">
          <div class="sub-nav">
            <button class="sub-nav-btn active" data-inventory="medicines">Medicines</button>
            <button class="sub-nav-btn" data-inventory="restock">Restock</button>
            <button class="sub-nav-btn" data-inventory="expired">Expired</button>
            <button class="sub-nav-btn" data-inventory="alerts">Alerts</button>
            <button class="sub-nav-btn" data-inventory="reports">Reports</button>
          </div>

          <!-- Medicines Tab -->
          <div class="inventory-tab active" id="medicinesTab">
            <div class="card">
              <div class="card-header">
                <h2>Medicine Inventory</h2>
                <div class="card-actions">
                  <div class="search-container">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);">
                      <circle cx="11" cy="11" r="8"/>
                      <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" placeholder="Search medicines..." id="medicineSearch">
                  </div>
                  <div class="select-container">
                    <select id="categoryFilter">
                      <option value="all">All Categories</option>
                      <option value="antibiotics">Antibiotics</option>
                      <option value="painkillers">Painkillers</option>
                      <option value="vitamins">Vitamins</option>
                      <option value="cardiovascular">Cardiovascular</option>
                      <option value="respiratory">Respiratory</option>
                      <option value="diabetes">Diabetes</option>
                      <option value="gastrointestinal">Gastrointestinal</option>
                      <option value="neurological">Neurological</option>
                      <option value="dermatological">Dermatological</option>
                      <option value="ophthalmology">Ophthalmology</option>
                      <option value="emergency">Emergency</option>
                      <option value="pediatric">Pediatric</option>
                    </select>
                  </div>
                  <div class="select-container">
                    <select id="stockFilter">
                      <option value="all">All Stock</option>
                      <option value="in-stock">In Stock</option>
                      <option value="low-stock">Low Stock</option>
                      <option value="out-of-stock">Out of Stock</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="card-content">
                <div class="table-container">
                  <table class="data-table" id="medicineTable">
                    <thead>
                      <tr>
                        <th class="sortable" data-sort="id">Medicine ID</th>
                        <th class="sortable" data-sort="name">Name</th>
                        <th class="sortable" data-sort="category">Category</th>
                        <th class="sortable" data-sort="stock">Stock</th>
                        <th class="sortable" data-sort="unit">Unit</th>
                        <th class="sortable" data-sort="stock-date">Stock Date</th>
                        <th class="sortable" data-sort="expiry">Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="medicineTableBody">
                      <!-- Medicine data will be populated by JavaScript -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Restock Tab -->
          <div class="inventory-tab" id="restockTab">
            <div class="card">
              <div class="card-header">
                <h2>Restock Requirements</h2>
                <div class="card-actions">
                  <button class="btn btn-primary" onclick="generateRestockOrder()">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                      <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                      <line x1="12" y1="11" x2="12" y2="17"/>
                      <line x1="9" y1="14" x2="15" y2="14"/>
                    </svg>
                    Generate Order
                  </button>
                </div>
              </div>
              <div class="card-content">
                <div class="table-container">
                  <table class="data-table" id="restockTable">
                    <thead>
                      <tr>
                        <th>Medicine</th>
                        <th>Current Stock</th>
                        <th>Minimum Required</th>
                        <th>Shortage</th>
                        <th>Estimated Cost</th>
                        <th>Priority</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Insulin Glargine</td>
                        <td>5</td>
                        <td>30</td>
                        <td>25</td>
                        <td>$1,250</td>
                        <td><span class="status-badge expired">Critical</span></td>
                        <td>
                          <button class="btn btn-primary">Order Now</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Epinephrine Auto-Injector</td>
                        <td>10</td>
                        <td>25</td>
                        <td>15</td>
                        <td>$750</td>
                        <td><span class="status-badge near-expiry">High</span></td>
                        <td>
                          <button class="btn btn-primary">Order Now</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Ciprofloxacin 500mg</td>
                        <td>25</td>
                        <td>50</td>
                        <td>25</td>
                        <td>$125</td>
                        <td><span class="status-badge low-stock">Medium</span></td>
                        <td>
                          <button class="btn btn-primary">Order Now</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Trimethoprim 200mg</td>
                        <td>0</td>
                        <td>40</td>
                        <td>40</td>
                        <td>$200</td>
                        <td><span class="status-badge expired">Critical</span></td>
                        <td>
                          <button class="btn btn-primary">Order Now</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Expired Tab -->
          <div class="inventory-tab" id="expiredTab">
            <div class="card">
              <div class="card-header">
                <h2>Expired Medicines</h2>
                <div class="card-actions">
                  <button class="btn btn-danger" onclick="disposeExpiredMedicines()">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <polyline points="3,6 5,6 21,6"/>
                      <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"/>
                      <line x1="10" y1="11" x2="10" y2="17"/>
                      <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                    Dispose All
                  </button>
                </div>
              </div>
              <div class="card-content">
                <div class="table-container">
                  <table class="data-table" id="expiredTable">
                    <thead>
                      <tr>
                        <th>Medicine</th>
                        <th>Batch No</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                        <th>Days Expired</th>
                        <th>Value Lost</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Aspirin 100mg</td>
                        <td>ASP001</td>
                        <td>20</td>
                        <td>2024-01-10</td>
                        <td>8</td>
                        <td>$40</td>
                        <td>
                          <button class="btn btn-danger">Dispose</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Codeine Cough Syrup</td>
                        <td>CS002</td>
                        <td>5</td>
                        <td>2024-01-05</td>
                        <td>13</td>
                        <td>$75</td>
                        <td>
                          <button class="btn btn-danger">Dispose</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Vitamin C 500mg</td>
                        <td>VC003</td>
                        <td>50</td>
                        <td>2023-12-28</td>
                        <td>21</td>
                        <td>$25</td>
                        <td>
                          <button class="btn btn-danger">Dispose</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Alerts Tab -->
          <div class="inventory-tab" id="alertsTab">
            <div class="card">
              <div class="card-header">
                <h2>Inventory Alerts</h2>
                <div class="card-actions">
                  <button class="btn btn-outline" onclick="markAllAlertsRead()">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    Mark All Read
                  </button>
                </div>
              </div>
              <div class="card-content">
                <div class="table-container">
                  <table class="data-table" id="alertsTable">
                    <thead>
                      <tr>
                        <th>Alert Type</th>
                        <th>Medicine</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Low Stock</td>
                        <td>Insulin Glargine</td>
                        <td>Stock below critical threshold (5 units remaining)</td>
                        <td>2024-01-18</td>
                        <td><span class="status-badge expired">Critical</span></td>
                        <td><span class="status-badge pending">Unread</span></td>
                        <td>
                          <button class="btn btn-outline">Acknowledge</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Near Expiry</td>
                        <td>Prednisolone Eye Drops</td>
                        <td>Expires in 7 days (2025-01-25)</td>
                        <td>2024-01-18</td>
                        <td><span class="status-badge near-expiry">High</span></td>
                        <td><span class="status-badge pending">Unread</span></td>
                        <td>
                          <button class="btn btn-outline">Acknowledge</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Out of Stock</td>
                        <td>Trimethoprim 200mg</td>
                        <td>Medicine completely out of stock</td>
                        <td>2024-01-17</td>
                        <td><span class="status-badge expired">Critical</span></td>
                        <td><span class="status-badge completed">Read</span></td>
                        <td>
                          <button class="btn btn-outline">View Details</button>
                        </td>
                      </tr>
                      <tr>
                        <td>Batch Recall</td>
                        <td>Ranitidine 150mg</td>
                        <td>Manufacturer recall notice for batch RAN456</td>
                        <td>2024-01-16</td>
                        <td><span class="status-badge near-expiry">High</span></td>
                        <td><span class="status-badge pending">Unread</span></td>
                        <td>
                          <button class="btn btn-outline">Acknowledge</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Reports Tab -->
          <div class="inventory-tab" id="reportsTab">
            <div class="card">
              <div class="card-header">
                <h2>Inventory Reports</h2>
                <div class="card-actions">
                  <button class="btn btn-primary" onclick="openTransactionsReport()">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14,2 14,8 20,8"/>
                      <line x1="16" y1="13" x2="8" y2="13"/>
                      <line x1="16" y1="17" x2="8" y2="17"/>
                      <polyline points="10,9 9,9 8,9"/>
                    </svg>
                    Transactions Report
                  </button>
                </div>
              </div>
              <div class="card-content">
                <div class="stats-grid">
                  <div class="stat-card">
                    <div class="stat-header">
                      <h3>Total Inventory Value</h3>
                      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                      </svg>
                    </div>
                    <div class="stat-content">
                      <div class="stat-value">$45,230</div>
                      <p class="stat-change">Current inventory value</p>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-header">
                      <h3>Monthly Consumption</h3>
                      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3v18h18"/>
                        <path d="m19 9-5 5-4-4-3 3"/>
                      </svg>
                    </div>
                    <div class="stat-content">
                      <div class="stat-value">$8,450</div>
                      <p class="stat-change">This month usage</p>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-header">
                      <h3>Expired Value</h3>
                      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <path d="M12 9v4"/>
                        <path d="m12 17 .01 0"/>
                      </svg>
                    </div>
                    <div class="stat-content">
                      <div class="stat-value">$140</div>
                      <p class="stat-change">Lost to expiry this month</p>
                    </div>
                  </div>
                  <div class="stat-card">
                    <div class="stat-header">
                      <h3>Restock Orders</h3>
                      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                        <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                        <path d="M12 3v6"/>
                      </svg>
                    </div>
                    <div class="stat-content">
                      <div class="stat-value">12</div>
                      <p class="stat-change">Pending orders this month</p>
                    </div>
                  </div>
                </div>
                <div style="margin-top: 1.5rem;">
                  <button class="btn btn-outline" onclick="generateInventoryReport()">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                      <polyline points="7,10 12,15 17,10"/>
                      <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Download Full Report
                  </button>
                  <button class="btn btn-outline" onclick="generateStockReport()">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14,2 14,8 20,8"/>
                    </svg>
                    Stock Level Report
                  </button>
                  <button class="btn btn-outline" onclick="generateExpiryReport()">
                    <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <circle cx="12" cy="12" r="10"/>
                      <polyline points="12,6 12,12 16,14"/>
                    </svg>
                    Expiry Report
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Appointments Section -->
        <section class="content-section" id="appointmentsSection">
          <div class="card">
            <div class="card-header">
              <h2>Appointment Records</h2>
              <div class="card-actions">
                <div class="search-container">
                  <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                  </svg>
                  <input type="text" placeholder="Search appointments..." id="appointmentSearch">
                </div>
                <div class="select-container">
                  <select id="appointmentDateFilter">
                    <option value="all">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="card-content">
              <div class="table-container">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Appointment ID</th>
                      <th>Patient</th>
                      <th>Doctor</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>A001</td>
                      <td>Sarah Johnson</td>
                      <td>Dr. Smith</td>
                      <td>2024-01-18</td>
                      <td>09:00 AM</td>
                      <td>Follow-up</td>
                      <td><span class="status-badge completed">Completed</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewAppointmentRecord('A001')" title="View Record">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                              <circle cx="12" cy="12" r="3"/>
                            </svg>
                          </button>
                          <button class="btn-icon" onclick="downloadPDF('A001')" title="Download PDF">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                              <polyline points="7,10 12,15 17,10"/>
                              <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>A002</td>
                      <td>Michael Chen</td>
                      <td>Dr. Johnson</td>
                      <td>2024-01-18</td>
                      <td>10:30 AM</td>
                      <td>Consultation</td>
                      <td><span class="status-badge completed">Completed</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewAppointmentRecord('A002')" title="View Record">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                              <circle cx="12" cy="12" r="3"/>
                            </svg>
                          </button>
                          <button class="btn-icon" onclick="downloadPDF('A002')" title="Download PDF">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                              <polyline points="7,10 12,15 17,10"/>
                              <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>A003</td>
                      <td>Emily Davis</td>
                      <td>Dr. Williams</td>
                      <td>2024-01-18</td>
                      <td>02:00 PM</td>
                      <td>Check-up</td>
                      <td><span class="status-badge pending">Pending</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewAppointmentRecord('A003')" title="View Record">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                              <circle cx="12" cy="12" r="3"/>
                            </svg>
                          </button>
                          <button class="btn-icon" onclick="downloadPDF('A003')" title="Download PDF">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                              <polyline points="7,10 12,15 17,10"/>
                              <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>A004</td>
                      <td>Robert Wilson</td>
                      <td>Dr. Brown</td>
                      <td>2024-01-19</td>
                      <td>11:00 AM</td>
                      <td>Emergency</td>
                      <td><span class="status-badge expired">Urgent</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewAppointmentRecord('A004')" title="View Record">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                              <circle cx="12" cy="12" r="3"/>
                            </svg>
                          </button>
                          <button class="btn-icon" onclick="downloadPDF('A004')" title="Download PDF">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                              <polyline points="7,10 12,15 17,10"/>
                              <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>A005</td>
                      <td>Lisa Anderson</td>
                      <td>Dr. Davis</td>
                      <td>2024-01-19</td>
                      <td>03:30 PM</td>
                      <td>Therapy</td>
                      <td><span class="status-badge pending">Scheduled</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon" onclick="viewAppointmentRecord('A005')" title="View Record">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                              <circle cx="12" cy="12" r="3"/>
                            </svg>
                          </button>
                          <button class="btn-icon" onclick="downloadPDF('A005')" title="Download PDF">
                            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                              <polyline points="7,10 12,15 17,10"/>
                              <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                          </button>
                        </div>
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

  <!-- Modals -->
  <!-- Staff Logs Modal -->
  <div class="modal" id="staffLogsModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Staff Activity Logs</h3>
        <button class="modal-close" onclick="closeModal('staffLogsModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div id="staffLogsContent">
          <div class="table-container">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Action</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody id="staffLogsTableBody">
                <!-- Logs will be populated by JavaScript -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" onclick="closeModal('staffLogsModal')">Close</button>
        <button class="btn btn-primary" onclick="exportStaffLogs()">Export Logs</button>
      </div>
    </div>
  </div>

  <!-- Appointment Record Modal -->
  <div class="modal" id="appointmentRecordModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Appointment Record Details</h3>
        <button class="modal-close" onclick="closeModal('appointmentRecordModal')">&times;</button>
      </div>
      <div class="modal-body">
        <div id="appointmentRecordContent">
          <!-- Record details will be populated by JavaScript -->
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" onclick="closeModal('appointmentRecordModal')">Close</button>
        <button class="btn btn-primary" onclick="downloadCurrentRecord()">Download PDF</button>
      </div>
    </div>
  </div>

  <!-- Transactions Report Modal -->
  <div class="modal" id="transactionsReportModal">
    <div class="modal-content large">
      <div class="modal-header">
        <h3>Transactions Report</h3>
        <button class="modal-close" onclick="closeModal('transactionsReportModal')">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Report Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
          <div class="card-header">
            <h2>Report Filters</h2>
          </div>
          <div class="card-content">
            <div class="form-row">
              <div class="form-group">
                <label for="reportDateFrom">From Date</label>
                <input type="date" id="reportDateFrom" value="2024-01-01">
              </div>
              <div class="form-group">
                <label for="reportDateTo">To Date</label>
                <input type="date" id="reportDateTo" value="2024-01-31">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="reportType">Transaction Type</label>
                <select id="reportType">
                  <option value="all">All Transactions</option>
                  <option value="purchase">Purchases</option>
                  <option value="sale">Sales</option>
                  <option value="disposal">Disposals</option>
                  <option value="adjustment">Adjustments</option>
                </select>
              </div>
              <div class="form-group">
                <label for="reportCategory">Category</label>
                <select id="reportCategory">
                  <option value="all">All Categories</option>
                  <option value="antibiotics">Antibiotics</option>
                  <option value="painkillers">Painkillers</option>
                  <option value="vitamins">Vitamins</option>
                  <option value="cardiovascular">Cardiovascular</option>
                  <option value="respiratory">Respiratory</option>
                  <option value="diabetes">Diabetes</option>
                  <option value="emergency">Emergency</option>
                </select>
              </div>
            </div>
            <div style="margin-top: 1rem;">
              <button class="btn btn-primary" onclick="filterTransactions()">
                <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                </svg>
                Apply Filters
              </button>
              <button class="btn btn-outline" onclick="resetTransactionFilters()">
                <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                  <path d="M3 3v5h5"/>
                </svg>
                Reset
              </button>
            </div>
          </div>
        </div>

        <!-- Transaction Summary -->
        <div class="stats-grid" style="margin-bottom: 1.5rem;">
          <div class="stat-card">
            <div class="stat-header">
              <h3>Total Transactions</h3>
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14,2 14,8 20,8"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-value" id="totalTransactions">156</div>
              <p class="stat-change">This period</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-header">
              <h3>Total Value</h3>
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"/>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-value" id="totalValue">$23,450</div>
              <p class="stat-change">Transaction value</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-header">
              <h3>Purchases</h3>
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                <line x1="12" y1="11" x2="12" y2="17"/>
                <line x1="9" y1="14" x2="15" y2="14"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-value" id="totalPurchases">$18,200</div>
              <p class="stat-change">Inventory purchases</p>
            </div>
          </div>
          <div class="stat-card">
            <div class="stat-header">
              <h3>Disposals</h3>
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3,6 5,6 21,6"/>
                <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-value" id="totalDisposals">$340</div>
              <p class="stat-change">Expired/damaged</p>
            </div>
          </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-container">
          <table class="data-table" id="transactionsTable">
            <thead>
              <tr>
                <th>Transaction ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Medicine</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Value</th>
                <th>Staff</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody id="transactionsTableBody">
              <!-- Transactions will be populated by JavaScript -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline" onclick="closeModal('transactionsReportModal')">Close</button>
        <button class="btn btn-success" onclick="exportTransactionsCSV()">
          <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7,10 12,15 17,10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
          Export CSV
        </button>
        <button class="btn btn-primary" onclick="downloadTransactionsPDF()">
          <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/>
          </svg>
          Download PDF
        </button>
      </div>
    </div>
  </div>

  <script>
   // Medicine data with 10+ categories and 10+ medicines per category
const medicineData = [
  // Antibiotics
  {
    id: "MED001",
    name: "Amoxicillin 500mg",
    category: "antibiotics",
    stock: 150,
    unit: "Tablets",
    stockDate: "2024-01-10",
    expiry: "2025-06-15",
    status: "in-stock",
  },
  {
    id: "MED002",
    name: "Azithromycin 250mg",
    category: "antibiotics",
    stock: 75,
    unit: "Tablets",
    stockDate: "2024-01-12",
    expiry: "2025-08-20",
    status: "in-stock",
  },
  {
    id: "MED003",
    name: "Ciprofloxacin 500mg",
    category: "antibiotics",
    stock: 25,
    unit: "Tablets",
    stockDate: "2024-01-05",
    expiry: "2025-03-10",
    status: "low-stock",
  },
  {
    id: "MED004",
    name: "Doxycycline 100mg",
    category: "antibiotics",
    stock: 90,
    unit: "Capsules",
    stockDate: "2024-01-08",
    expiry: "2025-07-25",
    status: "in-stock",
  },
  {
    id: "MED005",
    name: "Erythromycin 250mg",
    category: "antibiotics",
    stock: 60,
    unit: "Tablets",
    stockDate: "2024-01-15",
    expiry: "2025-09-30",
    status: "in-stock",
  },
  {
    id: "MED006",
    name: "Flucloxacillin 500mg",
    category: "antibiotics",
    stock: 40,
    unit: "Capsules",
    stockDate: "2024-01-03",
    expiry: "2025-04-18",
    status: "in-stock",
  },
  {
    id: "MED007",
    name: "Gentamicin 80mg",
    category: "antibiotics",
    stock: 15,
    unit: "Vials",
    stockDate: "2024-01-20",
    expiry: "2025-12-05",
    status: "low-stock",
  },
  {
    id: "MED008",
    name: "Metronidazole 400mg",
    category: "antibiotics",
    stock: 80,
    unit: "Tablets",
    stockDate: "2024-01-14",
    expiry: "2025-10-12",
    status: "in-stock",
  },
  {
    id: "MED009",
    name: "Penicillin V 250mg",
    category: "antibiotics",
    stock: 120,
    unit: "Tablets",
    stockDate: "2024-01-11",
    expiry: "2025-05-28",
    status: "in-stock",
  },
  {
    id: "MED010",
    name: "Trimethoprim 200mg",
    category: "antibiotics",
    stock: 0,
    unit: "Tablets",
    stockDate: "2024-01-01",
    expiry: "2025-02-14",
    status: "out-of-stock",
  },

  // Painkillers
  {
    id: "MED011",
    name: "Paracetamol 500mg",
    category: "painkillers",
    stock: 200,
    unit: "Tablets",
    stockDate: "2024-01-16",
    expiry: "2026-01-30",
    status: "in-stock",
  },
  {
    id: "MED012",
    name: "Ibuprofen 400mg",
    category: "painkillers",
    stock: 180,
    unit: "Tablets",
    stockDate: "2024-01-18",
    expiry: "2025-11-22",
    status: "in-stock",
  },
  {
    id: "MED013",
    name: "Aspirin 100mg",
    category: "painkillers",
    stock: 95,
    unit: "Tablets",
    stockDate: "2024-01-09",
    expiry: "2025-08-15",
    status: "in-stock",
  },
  {
    id: "MED014",
    name: "Codeine 30mg",
    category: "painkillers",
    stock: 30,
    unit: "Tablets",
    stockDate: "2024-01-07",
    expiry: "2025-04-20",
    status: "low-stock",
  },
  {
    id: "MED015",
    name: "Diclofenac 50mg",
    category: "painkillers",
    stock: 70,
    unit: "Tablets",
    stockDate: "2024-01-13",
    expiry: "2025-09-08",
    status: "in-stock",
  },
  {
    id: "MED016",
    name: "Morphine 10mg",
    category: "painkillers",
    stock: 20,
    unit: "Vials",
    stockDate: "2024-01-19",
    expiry: "2025-12-31",
    status: "low-stock",
  },
  {
    id: "MED017",
    name: "Naproxen 250mg",
    category: "painkillers",
    stock: 85,
    unit: "Tablets",
    stockDate: "2024-01-06",
    expiry: "2025-07-10",
    status: "in-stock",
  },
  {
    id: "MED018",
    name: "Tramadol 50mg",
    category: "painkillers",
    stock: 45,
    unit: "Capsules",
    stockDate: "2024-01-17",
    expiry: "2025-10-25",
    status: "in-stock",
  },
  {
    id: "MED019",
    name: "Celecoxib 200mg",
    category: "painkillers",
    stock: 55,
    unit: "Capsules",
    stockDate: "2024-01-04",
    expiry: "2025-06-03",
    status: "in-stock",
  },
  {
    id: "MED020",
    name: "Fentanyl 25mcg",
    category: "painkillers",
    stock: 10,
    unit: "Patches",
    stockDate: "2024-01-21",
    expiry: "2026-03-15",
    status: "low-stock",
  },

  // Vitamins
  {
    id: "MED021",
    name: "Vitamin D3 1000IU",
    category: "vitamins",
    stock: 300,
    unit: "Tablets",
    stockDate: "2024-01-22",
    expiry: "2026-12-31",
    status: "in-stock",
  },
  {
    id: "MED022",
    name: "Vitamin B12 1000mcg",
    category: "vitamins",
    stock: 250,
    unit: "Tablets",
    stockDate: "2024-01-20",
    expiry: "2026-11-15",
    status: "in-stock",
  },
  {
    id: "MED023",
    name: "Vitamin C 500mg",
    category: "vitamins",
    stock: 400,
    unit: "Tablets",
    stockDate: "2024-01-25",
    expiry: "2027-01-20",
    status: "in-stock",
  },
  {
    id: "MED024",
    name: "Folic Acid 5mg",
    category: "vitamins",
    stock: 150,
    unit: "Tablets",
    stockDate: "2024-01-15",
    expiry: "2026-08-30",
    status: "in-stock",
  },
  {
    id: "MED025",
    name: "Iron 65mg",
    category: "vitamins",
    stock: 120,
    unit: "Tablets",
    stockDate: "2024-01-18",
    expiry: "2026-05-25",
    status: "in-stock",
  },
  {
    id: "MED026",
    name: "Calcium 600mg",
    category: "vitamins",
    stock: 200,
    unit: "Tablets",
    stockDate: "2024-01-12",
    expiry: "2026-09-10",
    status: "in-stock",
  },
  {
    id: "MED027",
    name: "Magnesium 400mg",
    category: "vitamins",
    stock: 80,
    unit: "Tablets",
    stockDate: "2024-01-08",
    expiry: "2026-04-18",
    status: "in-stock",
  },
  {
    id: "MED028",
    name: "Zinc 15mg",
    category: "vitamins",
    stock: 90,
    unit: "Tablets",
    stockDate: "2024-01-14",
    expiry: "2026-07-22",
    status: "in-stock",
  },
  {
    id: "MED029",
    name: "Multivitamin",
    category: "vitamins",
    stock: 180,
    unit: "Tablets",
    stockDate: "2024-01-10",
    expiry: "2026-06-15",
    status: "in-stock",
  },
  {
    id: "MED030",
    name: "Omega-3 1000mg",
    category: "vitamins",
    stock: 100,
    unit: "Capsules",
    stockDate: "2024-01-16",
    expiry: "2026-10-05",
    status: "in-stock",
  },

  // Cardiovascular
  {
    id: "MED031",
    name: "Amlodipine 5mg",
    category: "cardiovascular",
    stock: 120,
    unit: "Tablets",
    stockDate: "2024-01-11",
    expiry: "2025-08-20",
    status: "in-stock",
  },
  {
    id: "MED032",
    name: "Atenolol 50mg",
    category: "cardiovascular",
    stock: 85,
    unit: "Tablets",
    stockDate: "2024-01-13",
    expiry: "2025-09-15",
    status: "in-stock",
  },
  {
    id: "MED033",
    name: "Lisinopril 10mg",
    category: "cardiovascular",
    stock: 95,
    unit: "Tablets",
    stockDate: "2024-01-09",
    expiry: "2025-07-30",
    status: "in-stock",
  },
  {
    id: "MED034",
    name: "Metoprolol 25mg",
    category: "cardiovascular",
    stock: 70,
    unit: "Tablets",
    stockDate: "2024-01-17",
    expiry: "2025-11-12",
    status: "in-stock",
  },
  {
    id: "MED035",
    name: "Simvastatin 20mg",
    category: "cardiovascular",
    stock: 110,
    unit: "Tablets",
    stockDate: "2024-01-05",
    expiry: "2025-06-25",
    status: "in-stock",
  },
  {
    id: "MED036",
    name: "Warfarin 5mg",
    category: "cardiovascular",
    stock: 40,
    unit: "Tablets",
    stockDate: "2024-01-19",
    expiry: "2025-12-08",
    status: "in-stock",
  },
  {
    id: "MED037",
    name: "Digoxin 0.25mg",
    category: "cardiovascular",
    stock: 25,
    unit: "Tablets",
    stockDate: "2024-01-07",
    expiry: "2025-05-18",
    status: "low-stock",
  },
  {
    id: "MED038",
    name: "Furosemide 40mg",
    category: "cardiovascular",
    stock: 80,
    unit: "Tablets",
    stockDate: "2024-01-21",
    expiry: "2026-01-10",
    status: "in-stock",
  },
  {
    id: "MED039",
    name: "Clopidogrel 75mg",
    category: "cardiovascular",
    stock: 60,
    unit: "Tablets",
    stockDate: "2024-01-15",
    expiry: "2025-10-22",
    status: "in-stock",
  },
  {
    id: "MED040",
    name: "Losartan 50mg",
    category: "cardiovascular",
    stock: 90,
    unit: "Tablets",
    stockDate: "2024-01-23",
    expiry: "2026-02-28",
    status: "in-stock",
  },

  // Respiratory
  {
    id: "MED041",
    name: "Salbutamol Inhaler",
    category: "respiratory",
    stock: 45,
    unit: "Inhalers",
    stockDate: "2024-01-12",
    expiry: "2025-08-15",
    status: "in-stock",
  },
  {
    id: "MED042",
    name: "Prednisolone 5mg",
    category: "respiratory",
    stock: 75,
    unit: "Tablets",
    stockDate: "2024-01-16",
    expiry: "2025-11-30",
    status: "in-stock",
  },
  {
    id: "MED043",
    name: "Montelukast 10mg",
    category: "respiratory",
    stock: 55,
    unit: "Tablets",
    stockDate: "2024-01-08",
    expiry: "2025-07-20",
    status: "in-stock",
  },
  {
    id: "MED044",
    name: "Budesonide Inhaler",
    category: "respiratory",
    stock: 30,
    unit: "Inhalers",
    stockDate: "2024-01-20",
    expiry: "2025-12-25",
    status: "low-stock",
  },
  {
    id: "MED045",
    name: "Theophylline 200mg",
    category: "respiratory",
    stock: 40,
    unit: "Tablets",
    stockDate: "2024-01-14",
    expiry: "2025-09-10",
    status: "in-stock",
  },
  {
    id: "MED046",
    name: "Ipratropium Inhaler",
    category: "respiratory",
    stock: 25,
    unit: "Inhalers",
    stockDate: "2024-01-18",
    expiry: "2026-01-05",
    status: "low-stock",
  },
  {
    id: "MED047",
    name: "Dextromethorphan 15mg",
    category: "respiratory",
    stock: 80,
    unit: "Tablets",
    stockDate: "2024-01-06",
    expiry: "2025-06-12",
    status: "in-stock",
  },
  {
    id: "MED048",
    name: "Guaifenesin 200mg",
    category: "respiratory",
    stock: 65,
    unit: "Tablets",
    stockDate: "2024-01-22",
    expiry: "2026-03-18",
    status: "in-stock",
  },
  {
    id: "MED049",
    name: "Fluticasone Inhaler",
    category: "respiratory",
    stock: 35,
    unit: "Inhalers",
    stockDate: "2024-01-10",
    expiry: "2025-08-28",
    status: "in-stock",
  },
  {
    id: "MED050",
    name: "Codeine Cough Syrup",
    category: "respiratory",
    stock: 20,
    unit: "Bottles",
    stockDate: "2024-01-24",
    expiry: "2025-04-15",
    status: "low-stock",
  },

  // Diabetes
  {
    id: "MED051",
    name: "Metformin 500mg",
    category: "diabetes",
    stock: 200,
    unit: "Tablets",
    stockDate: "2024-01-15",
    expiry: "2025-10-30",
    status: "in-stock",
  },
  {
    id: "MED052",
    name: "Insulin Glargine",
    category: "diabetes",
    stock: 15,
    unit: "Vials",
    stockDate: "2024-01-19",
    expiry: "2025-12-20",
    status: "low-stock",
  },
  {
    id: "MED053",
    name: "Gliclazide 80mg",
    category: "diabetes",
    stock: 90,
    unit: "Tablets",
    stockDate: "2024-01-11",
    expiry: "2025-08-25",
    status: "in-stock",
  },
  {
    id: "MED054",
    name: "Sitagliptin 100mg",
    category: "diabetes",
    stock: 70,
    unit: "Tablets",
    stockDate: "2024-01-17",
    expiry: "2025-11-15",
    status: "in-stock",
  },
  {
    id: "MED055",
    name: "Insulin Aspart",
    category: "diabetes",
    stock: 12,
    unit: "Vials",
    stockDate: "2024-01-21",
    expiry: "2026-01-10",
    status: "low-stock",
  },
  {
    id: "MED056",
    name: "Glimepiride 2mg",
    category: "diabetes",
    stock: 85,
    unit: "Tablets",
    stockDate: "2024-01-09",
    expiry: "2025-07-18",
    status: "in-stock",
  },
  {
    id: "MED057",
    name: "Pioglitazone 15mg",
    category: "diabetes",
    stock: 60,
    unit: "Tablets",
    stockDate: "2024-01-13",
    expiry: "2025-09-22",
    status: "in-stock",
  },
  {
    id: "MED058",
    name: "Empagliflozin 10mg",
    category: "diabetes",
    stock: 45,
    unit: "Tablets",
    stockDate: "2024-01-25",
    expiry: "2026-04-08",
    status: "in-stock",
  },
  {
    id: "MED059",
    name: "Liraglutide Pen",
    category: "diabetes",
    stock: 8,
    unit: "Pens",
    stockDate: "2024-01-23",
    expiry: "2025-05-30",
    status: "low-stock",
  },
  {
    id: "MED060",
    name: "Acarbose 50mg",
    category: "diabetes",
    stock: 75,
    unit: "Tablets",
    stockDate: "2024-01-07",
    expiry: "2025-06-14",
    status: "in-stock",
  },

  // Gastrointestinal
  {
    id: "MED061",
    name: "Omeprazole 20mg",
    category: "gastrointestinal",
    stock: 150,
    unit: "Capsules",
    stockDate: "2024-01-14",
    expiry: "2025-09-20",
    status: "in-stock",
  },
  {
    id: "MED062",
    name: "Ranitidine 150mg",
    category: "gastrointestinal",
    stock: 100,
    unit: "Tablets",
    stockDate: "2024-01-18",
    expiry: "2025-11-25",
    status: "in-stock",
  },
  {
    id: "MED063",
    name: "Loperamide 2mg",
    category: "gastrointestinal",
    stock: 80,
    unit: "Capsules",
    stockDate: "2024-01-10",
    expiry: "2025-08-12",
    status: "in-stock",
  },
  {
    id: "MED064",
    name: "Simethicone 40mg",
    category: "gastrointestinal",
    stock: 120,
    unit: "Tablets",
    stockDate: "2024-01-16",
    expiry: "2025-10-18",
    status: "in-stock",
  },
  {
    id: "MED065",
    name: "Lansoprazole 30mg",
    category: "gastrointestinal",
    stock: 70,
    unit: "Capsules",
    stockDate: "2024-01-12",
    expiry: "2025-09-05",
    status: "in-stock",
  },
  {
    id: "MED066",
    name: "Domperidone 10mg",
    category: "gastrointestinal",
    stock: 90,
    unit: "Tablets",
    stockDate: "2024-01-20",
    expiry: "2025-12-15",
    status: "in-stock",
  },
  {
    id: "MED067",
    name: "Mesalazine 400mg",
    category: "gastrointestinal",
    stock: 50,
    unit: "Tablets",
    stockDate: "2024-01-08",
    expiry: "2025-07-28",
    status: "in-stock",
  },
  {
    id: "MED068",
    name: "Lactulose Syrup",
    category: "gastrointestinal",
    stock: 35,
    unit: "Bottles",
    stockDate: "2024-01-22",
    expiry: "2026-02-10",
    status: "in-stock",
  },
  {
    id: "MED069",
    name: "Ondansetron 4mg",
    category: "gastrointestinal",
    stock: 60,
    unit: "Tablets",
    stockDate: "2024-01-06",
    expiry: "2025-06-20",
    status: "in-stock",
  },
  {
    id: "MED070",
    name: "Prochlorperazine 5mg",
    category: "gastrointestinal",
    stock: 40,
    unit: "Tablets",
    stockDate: "2024-01-24",
    expiry: "2025-04-25",
    status: "in-stock",
  },

  // Neurological
  {
    id: "MED071",
    name: "Levodopa 100mg",
    category: "neurological",
    stock: 60,
    unit: "Tablets",
    stockDate: "2024-01-13",
    expiry: "2025-09-15",
    status: "in-stock",
  },
  {
    id: "MED072",
    name: "Phenytoin 100mg",
    category: "neurological",
    stock: 45,
    unit: "Capsules",
    stockDate: "2024-01-17",
    expiry: "2025-11-08",
    status: "in-stock",
  },
  {
    id: "MED073",
    name: "Carbamazepine 200mg",
    category: "neurological",
    stock: 70,
    unit: "Tablets",
    stockDate: "2024-01-09",
    expiry: "2025-08-22",
    status: "in-stock",
  },
  {
    id: "MED074",
    name: "Gabapentin 300mg",
    category: "neurological",
    stock: 85,
    unit: "Capsules",
    stockDate: "2024-01-21",
    expiry: "2026-01-18",
    status: "in-stock",
  },
  {
    id: "MED075",
    name: "Amitriptyline 25mg",
    category: "neurological",
    stock: 55,
    unit: "Tablets",
    stockDate: "2024-01-15",
    expiry: "2025-10-12",
    status: "in-stock",
  },
  {
    id: "MED076",
    name: "Diazepam 5mg",
    category: "neurological",
    stock: 30,
    unit: "Tablets",
    stockDate: "2024-01-19",
    expiry: "2025-12-30",
    status: "low-stock",
  },
  {
    id: "MED077",
    name: "Sertraline 50mg",
    category: "neurological",
    stock: 90,
    unit: "Tablets",
    stockDate: "2024-01-11",
    expiry: "2025-08-28",
    status: "in-stock",
  },
  {
    id: "MED078",
    name: "Lorazepam 1mg",
    category: "neurological",
    stock: 25,
    unit: "Tablets",
    stockDate: "2024-01-23",
    expiry: "2026-03-05",
    status: "low-stock",
  },
  {
    id: "MED079",
    name: "Fluoxetine 20mg",
    category: "neurological",
    stock: 75,
    unit: "Capsules",
    stockDate: "2024-01-07",
    expiry: "2025-07-15",
    status: "in-stock",
  },
  {
    id: "MED080",
    name: "Risperidone 2mg",
    category: "neurological",
    stock: 40,
    unit: "Tablets",
    stockDate: "2024-01-25",
    expiry: "2025-05-20",
    status: "in-stock",
  },

  // Dermatological
  {
    id: "MED081",
    name: "Hydrocortisone Cream 1%",
    category: "dermatological",
    stock: 80,
    unit: "Tubes",
    stockDate: "2024-01-12",
    expiry: "2025-09-18",
    status: "in-stock",
  },
  {
    id: "MED082",
    name: "Clotrimazole Cream 1%",
    category: "dermatological",
    stock: 65,
    unit: "Tubes",
    stockDate: "2024-01-16",
    expiry: "2025-11-22",
    status: "in-stock",
  },
  {
    id: "MED083",
    name: "Betamethasone Cream",
    category: "dermatological",
    stock: 45,
    unit: "Tubes",
    stockDate: "2024-01-10",
    expiry: "2025-08-30",
    status: "in-stock",
  },
  {
    id: "MED084",
    name: "Aciclovir Cream 5%",
    category: "dermatological",
    stock: 55,
    unit: "Tubes",
    stockDate: "2024-01-18",
    expiry: "2025-12-10",
    status: "in-stock",
  },
  {
    id: "MED085",
    name: "Calamine Lotion",
    category: "dermatological",
    stock: 90,
    unit: "Bottles",
    stockDate: "2024-01-14",
    expiry: "2026-01-25",
    status: "in-stock",
  },
  {
    id: "MED086",
    name: "Fusidic Acid Cream",
    category: "dermatological",
    stock: 35,
    unit: "Tubes",
    stockDate: "2024-01-20",
    expiry: "2025-10-15",
    status: "in-stock",
  },
  {
    id: "MED087",
    name: "Tretinoin Gel 0.025%",
    category: "dermatological",
    stock: 25,
    unit: "Tubes",
    stockDate: "2024-01-08",
    expiry: "2025-07-12",
    status: "low-stock",
  },
  {
    id: "MED088",
    name: "Ketoconazole Shampoo",
    category: "dermatological",
    stock: 40,
    unit: "Bottles",
    stockDate: "2024-01-22",
    expiry: "2026-02-28",
    status: "in-stock",
  },
  {
    id: "MED089",
    name: "Mupirocin Ointment",
    category: "dermatological",
    stock: 50,
    unit: "Tubes",
    stockDate: "2024-01-06",
    expiry: "2025-06-18",
    status: "in-stock",
  },
  {
    id: "MED090",
    name: "Permethrin Cream 5%",
    category: "dermatological",
    stock: 30,
    unit: "Tubes",
    stockDate: "2024-01-24",
    expiry: "2025-04-30",
    status: "low-stock",
  },

  // Ophthalmology
  {
    id: "MED091",
    name: "Chloramphenicol Eye Drops",
    category: "ophthalmology",
    stock: 40,
    unit: "Bottles",
    stockDate: "2024-01-15",
    expiry: "2025-10-20",
    status: "in-stock",
  },
  {
    id: "MED092",
    name: "Timolol Eye Drops 0.5%",
    category: "ophthalmology",
    stock: 30,
    unit: "Bottles",
    stockDate: "2024-01-19",
    expiry: "2025-12-15",
    status: "low-stock",
  },
  {
    id: "MED093",
    name: "Prednisolone Eye Drops",
    category: "ophthalmology",
    stock: 35,
    unit: "Bottles",
    stockDate: "2024-01-11",
    expiry: "2025-08-25",
    status: "in-stock",
  },
  {
    id: "MED094",
    name: "Artificial Tears",
    category: "ophthalmology",
    stock: 80,
    unit: "Bottles",
    stockDate: "2024-01-17",
    expiry: "2026-01-30",
    status: "in-stock",
  },
  {
    id: "MED095",
    name: "Cyclopentolate Eye Drops",
    category: "ophthalmology",
    stock: 20,
    unit: "Bottles",
    stockDate: "2024-01-13",
    expiry: "2025-09-12",
    status: "low-stock",
  },
  {
    id: "MED096",
    name: "Tropicamide Eye Drops",
    category: "ophthalmology",
    stock: 25,
    unit: "Bottles",
    stockDate: "2024-01-21",
    expiry: "2026-02-08",
    status: "low-stock",
  },
  {
    id: "MED097",
    name: "Latanoprost Eye Drops",
    category: "ophthalmology",
    stock: 15,
    unit: "Bottles",
    stockDate: "2024-01-09",
    expiry: "2025-07-28",
    status: "low-stock",
  },
  {
    id: "MED098",
    name: "Ofloxacin Eye Drops",
    category: "ophthalmology",
    stock: 45,
    unit: "Bottles",
    stockDate: "2024-01-23",
    expiry: "2025-11-18",
    status: "in-stock",
  },
  {
    id: "MED099",
    name: "Dexamethasone Eye Drops",
    category: "ophthalmology",
    stock: 30,
    unit: "Bottles",
    stockDate: "2024-01-07",
    expiry: "2025-06-22",
    status: "low-stock",
  },
  {
    id: "MED100",
    name: "Brimonidine Eye Drops",
    category: "ophthalmology",
    stock: 25,
    unit: "Bottles",
    stockDate: "2024-01-25",
    expiry: "2025-05-15",
    status: "low-stock",
  },

  // Emergency
  {
    id: "MED101",
    name: "Epinephrine Auto-Injector",
    category: "emergency",
    stock: 10,
    unit: "Injectors",
    stockDate: "2024-01-20",
    expiry: "2025-12-31",
    status: "low-stock",
  },
  {
    id: "MED102",
    name: "Atropine 1mg/ml",
    category: "emergency",
    stock: 15,
    unit: "Vials",
    stockDate: "2024-01-18",
    expiry: "2026-01-15",
    status: "low-stock",
  },
  {
    id: "MED103",
    name: "Naloxone 0.4mg/ml",
    category: "emergency",
    stock: 12,
    unit: "Vials",
    stockDate: "2024-01-22",
    expiry: "2025-11-30",
    status: "low-stock",
  },
  {
    id: "MED104",
    name: "Adenosine 6mg/2ml",
    category: "emergency",
    stock: 8,
    unit: "Vials",
    stockDate: "2024-01-16",
    expiry: "2025-10-25",
    status: "low-stock",
  },
  {
    id: "MED105",
    name: "Amiodarone 150mg/3ml",
    category: "emergency",
    stock: 20,
    unit: "Vials",
    stockDate: "2024-01-14",
    expiry: "2025-09-20",
    status: "low-stock",
  },
  {
    id: "MED106",
    name: "Dopamine 200mg/5ml",
    category: "emergency",
    stock: 18,
    unit: "Vials",
    stockDate: "2024-01-12",
    expiry: "2025-08-18",
    status: "low-stock",
  },
  {
    id: "MED107",
    name: "Lidocaine 2% 20ml",
    category: "emergency",
    stock: 25,
    unit: "Vials",
    stockDate: "2024-01-24",
    expiry: "2026-03-10",
    status: "low-stock",
  },
  {
    id: "MED108",
    name: "Dextrose 50% 50ml",
    category: "emergency",
    stock: 30,
    unit: "Vials",
    stockDate: "2024-01-10",
    expiry: "2025-07-30",
    status: "low-stock",
  },
  {
    id: "MED109",
    name: "Calcium Gluconate 10%",
    category: "emergency",
    stock: 22,
    unit: "Vials",
    stockDate: "2024-01-08",
    expiry: "2025-06-25",
    status: "low-stock",
  },
  {
    id: "MED110",
    name: "Magnesium Sulfate 50%",
    category: "emergency",
    stock: 16,
    unit: "Vials",
    stockDate: "2024-01-26",
    expiry: "2025-04-20",
    status: "low-stock",
  },

  // Pediatric
  {
    id: "MED111",
    name: "Paracetamol Syrup 120mg/5ml",
    category: "pediatric",
    stock: 60,
    unit: "Bottles",
    stockDate: "2024-01-17",
    expiry: "2025-11-12",
    status: "in-stock",
  },
  {
    id: "MED112",
    name: "Ibuprofen Syrup 100mg/5ml",
    category: "pediatric",
    stock: 45,
    unit: "Bottles",
    stockDate: "2024-01-21",
    expiry: "2026-01-08",
    status: "in-stock",
  },
  {
    id: "MED113",
    name: "Amoxicillin Syrup 125mg/5ml",
    category: "pediatric",
    stock: 50,
    unit: "Bottles",
    stockDate: "2024-01-13",
    expiry: "2025-09-28",
    status: "in-stock",
  },
  {
    id: "MED114",
    name: "Oral Rehydration Salts",
    category: "pediatric",
    stock: 100,
    unit: "Sachets",
    stockDate: "2024-01-19",
    expiry: "2026-12-31",
    status: "in-stock",
  },
  {
    id: "MED115",
    name: "Zinc Syrup 10mg/5ml",
    category: "pediatric",
    stock: 35,
    unit: "Bottles",
    stockDate: "2024-01-15",
    expiry: "2025-10-18",
    status: "in-stock",
  },
  {
    id: "MED116",
    name: "Iron Drops 25mg/ml",
    category: "pediatric",
    stock: 40,
    unit: "Bottles",
    stockDate: "2024-01-11",
    expiry: "2025-08-15",
    status: "in-stock",
  },
  {
    id: "MED117",
    name: "Vitamin D Drops 400IU/ml",
    category: "pediatric",
    stock: 55,
    unit: "Bottles",
    stockDate: "2024-01-23",
    expiry: "2026-02-20",
    status: "in-stock",
  },
  {
    id: "MED118",
    name: "Cetirizine Syrup 5mg/5ml",
    category: "pediatric",
    stock: 30,
    unit: "Bottles",
    stockDate: "2024-01-09",
    expiry: "2025-07-25",
    status: "low-stock",
  },
  {
    id: "MED119",
    name: "Salbutamol Syrup 2mg/5ml",
    category: "pediatric",
    stock: 25,
    unit: "Bottles",
    stockDate: "2024-01-25",
    expiry: "2025-05-30",
    status: "low-stock",
  },
  {
    id: "MED120",
    name: "Domperidone Syrup 5mg/5ml",
    category: "pediatric",
    stock: 40,
    unit: "Bottles",
    stockDate: "2024-01-07",
    expiry: "2025-06-12",
    status: "in-stock",
  },
]

// Staff logs data
const staffLogsData = {
  ST001: [
    { date: "2024-01-18", time: "09:30", action: "Login", details: "Successful login from workstation 1" },
    { date: "2024-01-18", time: "09:35", action: "Patient Access", details: "Viewed patient record P001" },
    { date: "2024-01-18", time: "10:15", action: "Prescription", details: "Created prescription for patient P001" },
    { date: "2024-01-18", time: "11:30", action: "Patient Access", details: "Updated patient record P005" },
    { date: "2024-01-18", time: "14:20", action: "Report", details: "Generated monthly patient report" },
    { date: "2024-01-18", time: "17:45", action: "Logout", details: "Session ended normally" },
  ],
  ST002: [
    { date: "2024-01-18", time: "08:15", action: "Login", details: "Successful login from workstation 2" },
    { date: "2024-01-18", time: "08:20", action: "Inventory", details: "Checked medicine inventory" },
    { date: "2024-01-18", time: "09:45", action: "Patient Care", details: "Administered medication to patient P003" },
    { date: "2024-01-18", time: "12:30", action: "Emergency", details: "Responded to emergency call" },
    { date: "2024-01-18", time: "16:00", action: "Documentation", details: "Updated patient care notes" },
    { date: "2024-01-18", time: "16:30", action: "Logout", details: "End of shift logout" },
  ],
  ST003: [
    { date: "2024-01-17", time: "16:45", action: "Login", details: "Final login before resignation" },
    { date: "2024-01-17", time: "16:50", action: "Data Transfer", details: "Transferred patient files to Dr. Smith" },
    { date: "2024-01-17", time: "17:15", action: "System Access", details: "Account deactivated" },
    { date: "2024-01-17", time: "17:20", action: "Logout", details: "Final logout - resignation effective" },
  ],
  ST004: [
    { date: "2024-01-18", time: "07:30", action: "Login", details: "Early shift login" },
    { date: "2024-01-18", time: "07:35", action: "Lab Setup", details: "Prepared lab equipment for daily tests" },
    {
      date: "2024-01-18",
      time: "09:00",
      action: "Test Processing",
      details: "Processed blood samples for patients P001, P002",
    },
    { date: "2024-01-18", time: "11:30", action: "Results", details: "Uploaded test results to patient records" },
    { date: "2024-01-18", time: "14:45", action: "Quality Check", details: "Performed equipment calibration" },
    { date: "2024-01-18", time: "15:30", action: "Logout", details: "End of shift" },
  ],
  ST005: [
    { date: "2024-01-15", time: "14:20", action: "Login", details: "Last login before departure" },
    { date: "2024-01-15", time: "14:25", action: "File Transfer", details: "Transferred administrative files" },
    { date: "2024-01-15", time: "14:45", action: "Access Revoked", details: "Administrative privileges removed" },
    { date: "2024-01-15", time: "15:00", action: "Logout", details: "Final logout - left organization" },
  ],
}

// Appointment records data
const appointmentRecords = {
  A001: {
    id: "A001",
    patient: "Sarah Johnson",
    patientId: "P001",
    doctor: "Dr. Smith",
    date: "2024-01-18",
    time: "09:00 AM",
    type: "Follow-up",
    status: "Completed",
    diagnosis: "Hypertension - stable",
    treatment: "Continue current medication, lifestyle modifications",
    prescription: "Amlodipine 5mg daily, Lisinopril 10mg daily",
    notes: "Blood pressure well controlled. Patient reports no side effects. Next follow-up in 3 months.",
    vitals: {
      bloodPressure: "128/82 mmHg",
      heartRate: "72 bpm",
      temperature: "98.6°F",
      weight: "68 kg",
    },
  },
  A002: {
    id: "A002",
    patient: "Michael Chen",
    patientId: "P002",
    doctor: "Dr. Johnson",
    date: "2024-01-18",
    time: "10:30 AM",
    type: "Consultation",
    status: "Completed",
    diagnosis: "Type 2 Diabetes Mellitus",
    treatment: "Medication adjustment, dietary counseling",
    prescription: "Metformin 500mg twice daily, Gliclazide 80mg daily",
    notes: "HbA1c improved to 7.2%. Continue current regimen with minor adjustments.",
    vitals: {
      bloodPressure: "135/85 mmHg",
      heartRate: "78 bpm",
      temperature: "98.4°F",
      weight: "75 kg",
      bloodSugar: "145 mg/dL",
    },
  },
  A003: {
    id: "A003",
    patient: "Emily Davis",
    patientId: "P003",
    doctor: "Dr. Williams",
    date: "2024-01-18",
    time: "02:00 PM",
    type: "Check-up",
    status: "Pending",
    diagnosis: "Pending examination",
    treatment: "To be determined",
    prescription: "To be prescribed after examination",
    notes: "Routine annual check-up scheduled.",
    vitals: {},
  },
  A004: {
    id: "A004",
    patient: "Robert Wilson",
    patientId: "P004",
    doctor: "Dr. Brown",
    date: "2024-01-19",
    time: "11:00 AM",
    type: "Emergency",
    status: "Urgent",
    diagnosis: "Acute chest pain - under investigation",
    treatment: "Emergency protocol initiated",
    prescription: "Aspirin 300mg stat, Nitroglycerin SL PRN",
    notes:
      "Patient presented with acute chest pain. ECG and cardiac enzymes ordered. Monitoring in emergency department.",
    vitals: {
      bloodPressure: "160/95 mmHg",
      heartRate: "95 bpm",
      temperature: "99.1°F",
      oxygenSaturation: "96%",
    },
  },
  A005: {
    id: "A005",
    patient: "Lisa Anderson",
    patientId: "P005",
    doctor: "Dr. Davis",
    date: "2024-01-19",
    time: "03:30 PM",
    type: "Therapy",
    status: "Scheduled",
    diagnosis: "Rheumatoid Arthritis",
    treatment: "Physical therapy session",
    prescription: "Continue Methotrexate 15mg weekly",
    notes: "Weekly physical therapy for joint mobility and pain management.",
    vitals: {},
  },
}

// Transactions data
const transactionsData = [
  {
    id: "TXN001",
    date: "2024-01-18",
    type: "Purchase",
    medicine: "Paracetamol 500mg",
    category: "painkillers",
    quantity: 200,
    unitPrice: 0.5,
    totalValue: 100,
    staff: "Admin Manager",
    notes: "Monthly restock",
  },
  {
    id: "TXN002",
    date: "2024-01-18",
    type: "Sale",
    medicine: "Amoxicillin 500mg",
    category: "antibiotics",
    quantity: 30,
    unitPrice: 2.0,
    totalValue: 60,
    staff: "Dr. Sarah Wilson",
    notes: "Patient prescription",
  },
  {
    id: "TXN003",
    date: "2024-01-17",
    type: "Purchase",
    medicine: "Insulin Glargine",
    category: "diabetes",
    quantity: 50,
    unitPrice: 50.0,
    totalValue: 2500,
    staff: "Admin Manager",
    notes: "Emergency restock",
  },
  {
    id: "TXN004",
    date: "2024-01-17",
    type: "Disposal",
    medicine: "Aspirin 100mg",
    category: "painkillers",
    quantity: 20,
    unitPrice: 2.0,
    totalValue: 40,
    staff: "Nurse Mary Johnson",
    notes: "Expired medication",
  },
  {
    id: "TXN005",
    date: "2024-01-16",
    type: "Sale",
    medicine: "Vitamin D3 1000IU",
    category: "vitamins",
    quantity: 60,
    unitPrice: 0.75,
    totalValue: 45,
    staff: "Dr. Michael Chen",
    notes: "Patient prescription",
  },
  {
    id: "TXN006",
    date: "2024-01-16",
    type: "Purchase",
    medicine: "Epinephrine Auto-Injector",
    category: "emergency",
    quantity: 25,
    unitPrice: 50.0,
    totalValue: 1250,
    staff: "Admin Manager",
    notes: "Emergency stock replenishment",
  },
  {
    id: "TXN007",
    date: "2024-01-15",
    type: "Sale",
    medicine: "Omeprazole 20mg",
    category: "gastrointestinal",
    quantity: 28,
    unitPrice: 1.5,
    totalValue: 42,
    staff: "Dr. Sarah Wilson",
    notes: "Patient prescription",
  },
  {
    id: "TXN008",
    date: "2024-01-15",
    type: "Adjustment",
    medicine: "Metformin 500mg",
    category: "diabetes",
    quantity: -5,
    unitPrice: 1.0,
    totalValue: -5,
    staff: "Tech. Robert Davis",
    notes: "Inventory correction",
  },
  {
    id: "TXN009",
    date: "2024-01-14",
    type: "Purchase",
    medicine: "Salbutamol Inhaler",
    category: "respiratory",
    quantity: 40,
    unitPrice: 15.0,
    totalValue: 600,
    staff: "Admin Manager",
    notes: "Quarterly restock",
  },
  {
    id: "TXN010",
    date: "2024-01-14",
    type: "Sale",
    medicine: "Hydrocortisone Cream 1%",
    category: "dermatological",
    quantity: 12,
    unitPrice: 3.0,
    totalValue: 36,
    staff: "Dr. Michael Chen",
    notes: "Patient prescription",
  },
  {
    id: "TXN011",
    date: "2024-01-13",
    type: "Disposal",
    medicine: "Codeine Cough Syrup",
    category: "respiratory",
    quantity: 5,
    unitPrice: 15.0,
    totalValue: 75,
    staff: "Nurse Mary Johnson",
    notes: "Expired medication",
  },
  {
    id: "TXN012",
    date: "2024-01-13",
    type: "Purchase",
    medicine: "Artificial Tears",
    category: "ophthalmology",
    quantity: 100,
    unitPrice: 2.5,
    totalValue: 250,
    staff: "Admin Manager",
    notes: "Regular restock",
  },
  {
    id: "TXN013",
    date: "2024-01-12",
    type: "Sale",
    medicine: "Ibuprofen 400mg",
    category: "painkillers",
    quantity: 40,
    unitPrice: 0.8,
    totalValue: 32,
    staff: "Dr. Sarah Wilson",
    notes: "Patient prescription",
  },
  {
    id: "TXN014",
    date: "2024-01-12",
    type: "Purchase",
    medicine: "Oral Rehydration Salts",
    category: "pediatric",
    quantity: 200,
    unitPrice: 0.25,
    totalValue: 50,
    staff: "Admin Manager",
    notes: "Pediatric stock",
  },
  {
    id: "TXN015",
    date: "2024-01-11",
    type: "Sale",
    medicine: "Amlodipine 5mg",
    category: "cardiovascular",
    quantity: 30,
    unitPrice: 1.2,
    totalValue: 36,
    staff: "Dr. Michael Chen",
    notes: "Patient prescription",
  },
  {
    id: "TXN016",
    date: "2024-01-11",
    type: "Adjustment",
    medicine: "Trimethoprim 200mg",
    category: "antibiotics",
    quantity: -40,
    unitPrice: 5.0,
    totalValue: -200,
    staff: "Tech. Robert Davis",
    notes: "Stock write-off - out of stock",
  },
  {
    id: "TXN017",
    date: "2024-01-10",
    type: "Purchase",
    medicine: "Gabapentin 300mg",
    category: "neurological",
    quantity: 120,
    unitPrice: 2.0,
    totalValue: 240,
    staff: "Admin Manager",
    notes: "Monthly restock",
  },
  {
    id: "TXN018",
    date: "2024-01-10",
    type: "Sale",
    medicine: "Chloramphenicol Eye Drops",
    category: "ophthalmology",
    quantity: 8,
    unitPrice: 4.0,
    totalValue: 32,
    staff: "Dr. Sarah Wilson",
    notes: "Patient prescription",
  },
  {
    id: "TXN019",
    date: "2024-01-09",
    type: "Purchase",
    medicine: "Domperidone Syrup 5mg/5ml",
    category: "pediatric",
    quantity: 60,
    unitPrice: 3.5,
    totalValue: 210,
    staff: "Admin Manager",
    notes: "Pediatric restock",
  },
  {
    id: "TXN020",
    date: "2024-01-09",
    type: "Sale",
    medicine: "Simvastatin 20mg",
    category: "cardiovascular",
    quantity: 28,
    unitPrice: 1.8,
    totalValue: 50.4,
    staff: "Dr. Michael Chen",
    notes: "Patient prescription",
  },
]

// Global variables
let currentSortColumn = ""
let currentSortDirection = "asc"
let currentAppointmentId = ""
let filteredMedicineData = [...medicineData]
let filteredTransactionsData = [...transactionsData]

// Initialize the dashboard when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  initializeDashboard()
})

// Main initialization function
function initializeDashboard() {
  // DOM Elements
  const sidebar = document.getElementById("sidebar")
  const sidebarToggle = document.getElementById("sidebarToggle")
  const mainContent = document.querySelector(".main-content")
  const navItems = document.querySelectorAll(".nav-item")
  const contentSections = document.querySelectorAll(".content-section")
  const pageTitle = document.getElementById("pageTitle")

  // Search and filter elements
  const staffSearch = document.getElementById("staffSearch")
  const staffStatusFilter = document.getElementById("staffStatusFilter")
  const medicineSearch = document.getElementById("medicineSearch")
  const categoryFilter = document.getElementById("categoryFilter")
  const stockFilter = document.getElementById("stockFilter")
  const appointmentSearch = document.getElementById("appointmentSearch")
  const appointmentDateFilter = document.getElementById("appointmentDateFilter")

  // Inventory sub-navigation
  const subNavBtns = document.querySelectorAll(".sub-nav-btn")
  const inventoryTabs = document.querySelectorAll(".inventory-tab")

  // Sortable table headers
  const sortableHeaders = document.querySelectorAll("th.sortable")

  // Initialize medicine table
  populateMedicineTable()
  populateTransactionsTable()

  // Toggle sidebar
  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed")
      mainContent.classList.toggle("expanded")

      if (window.innerWidth < 768) {
        sidebar.classList.toggle("show")
      }
    })
  }

  // Handle navigation
  navItems.forEach((item) => {
    item.addEventListener("click", function (e) {
      e.preventDefault()

      const section = this.getAttribute("data-section")

      navItems.forEach((nav) => nav.classList.remove("active"))
      this.classList.add("active")

      contentSections.forEach((content) => {
        content.classList.remove("active")
        if (content.id === section + "Section") {
          content.classList.add("active")
        }
      })

      updatePageTitle(section)

      if (window.innerWidth < 768) {
        sidebar.classList.remove("show")
      }
    })
  })

  // Search and filter event listeners
  if (staffSearch) {
    staffSearch.addEventListener("input", filterStaff)
  }
  if (staffStatusFilter) {
    staffStatusFilter.addEventListener("change", filterStaff)
  }
  if (medicineSearch) {
    medicineSearch.addEventListener("input", filterMedicines)
  }
  if (categoryFilter) {
    categoryFilter.addEventListener("change", filterMedicines)
  }
  if (stockFilter) {
    stockFilter.addEventListener("change", filterMedicines)
  }
  if (appointmentSearch) {
    appointmentSearch.addEventListener("input", filterAppointments)
  }
  if (appointmentDateFilter) {
    appointmentDateFilter.addEventListener("change", filterAppointments)
  }

  // Inventory sub-navigation
  subNavBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const inventoryType = this.getAttribute("data-inventory")

      subNavBtns.forEach((b) => b.classList.remove("active"))
      this.classList.add("active")

      inventoryTabs.forEach((tab) => {
        tab.classList.remove("active")
        if (tab.id === inventoryType + "Tab") {
          tab.classList.add("active")
        }
      })
    })
  })

  // Sortable table headers
  sortableHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      const sortColumn = this.getAttribute("data-sort")
      sortMedicineTable(sortColumn)
    })
  })

  // Close modals when clicking outside
  window.addEventListener("click", (event) => {
    const modals = document.querySelectorAll(".modal")
    modals.forEach((modal) => {
      if (event.target === modal) {
        modal.classList.remove("show")
      }
    })
  })
}

// Update page title based on section
function updatePageTitle(section) {
  const pageTitle = document.getElementById("pageTitle")
  if (!pageTitle) return

  switch (section) {
    case "dashboard":
      pageTitle.textContent = "Admin Dashboard"
      break
    case "staff":
      pageTitle.textContent = "Staff Management"
      break
    case "inventory":
      pageTitle.textContent = "Medical Inventory"
      break
    case "appointments":
      pageTitle.textContent = "Appointments"
      break
    default:
      pageTitle.textContent = "Admin Dashboard"
  }
}

// Populate medicine table
function populateMedicineTable() {
  const tableBody = document.getElementById("medicineTableBody")
  if (!tableBody) return

  tableBody.innerHTML = ""

  filteredMedicineData.forEach((medicine) => {
    const row = document.createElement("tr")
    row.innerHTML = `
      <td>${medicine.id}</td>
      <td>${medicine.name}</td>
      <td>${capitalizeFirst(medicine.category)}</td>
      <td>${medicine.stock}</td>
      <td>${medicine.unit}</td>
      <td>${medicine.stockDate}</td>
      <td>${medicine.expiry}</td>
      <td><span class="status-badge ${medicine.status}">${getStatusText(medicine.status)}</span></td>
      <td>
        <div class="action-buttons">
          <button class="btn-icon" onclick="editMedicine('${medicine.id}')" title="Edit">
            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
              <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
          </button>
          <button class="btn-icon" onclick="scanBarcode('${medicine.id}')" title="Scan Barcode">
            <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2Z"/>
              <path d="M8 7v10"/>
              <path d="M12 7v10"/>
              <path d="M16 7v10"/>
            </svg>
          </button>
        </div>
      </td>
    `
    tableBody.appendChild(row)
  })
}

// Populate transactions table
function populateTransactionsTable() {
  const tableBody = document.getElementById("transactionsTableBody")
  if (!tableBody) return

  tableBody.innerHTML = ""

  filteredTransactionsData.forEach((transaction) => {
    const row = document.createElement("tr")
    row.innerHTML = `
      <td>${transaction.id}</td>
      <td>${transaction.date}</td>
      <td><span class="status-badge ${getTransactionTypeClass(transaction.type)}">${transaction.type}</span></td>
      <td>${transaction.medicine}</td>
      <td>${capitalizeFirst(transaction.category)}</td>
      <td>${transaction.quantity}</td>
      <td>$${transaction.unitPrice.toFixed(2)}</td>
      <td>$${transaction.totalValue.toFixed(2)}</td>
      <td>${transaction.staff}</td>
      <td>${transaction.notes}</td>
    `
    tableBody.appendChild(row)
  })
}

// Helper functions
function capitalizeFirst(str) {
  return str.charAt(0).toUpperCase() + str.slice(1)
}

function getStatusText(status) {
  switch (status) {
    case "in-stock":
      return "In Stock"
    case "low-stock":
      return "Low Stock"
    case "out-of-stock":
      return "Out of Stock"
    case "expired":
      return "Expired"
    case "near-expiry":
      return "Near Expiry"
    default:
      return status
  }
}

function getTransactionTypeClass(type) {
  switch (type) {
    case "Purchase":
      return "in-stock"
    case "Sale":
      return "completed"
    case "Disposal":
      return "expired"
    case "Adjustment":
      return "pending"
    default:
      return "pending"
  }
}

// Filter functions
function filterStaff() {
  const searchTerm = document.getElementById("staffSearch")?.value.toLowerCase() || ""
  const statusFilter = document.getElementById("staffStatusFilter")?.value || "all"
  const table = document.getElementById("staffTable")

  if (!table) return

  const rows = table.querySelectorAll("tbody tr")

  rows.forEach((row) => {
    const name = row.cells[1].textContent.toLowerCase()
    const position = row.cells[2].textContent.toLowerCase()
    const department = row.cells[3].textContent.toLowerCase()
    const status = row.cells[6].textContent.toLowerCase()

    const matchesSearch = name.includes(searchTerm) || position.includes(searchTerm) || department.includes(searchTerm)
    const matchesStatus = statusFilter === "all" || status.includes(statusFilter)

    row.style.display = matchesSearch && matchesStatus ? "" : "none"
  })
}

function filterMedicines() {
  const searchTerm = document.getElementById("medicineSearch")?.value.toLowerCase() || ""
  const categoryFilter = document.getElementById("categoryFilter")?.value || "all"
  const stockFilter = document.getElementById("stockFilter")?.value || "all"

  filteredMedicineData = medicineData.filter((medicine) => {
    const matchesSearch =
      medicine.name.toLowerCase().includes(searchTerm) || medicine.id.toLowerCase().includes(searchTerm)
    const matchesCategory = categoryFilter === "all" || medicine.category === categoryFilter
    const matchesStock = stockFilter === "all" || medicine.status === stockFilter

    return matchesSearch && matchesCategory && matchesStock
  })

  populateMedicineTable()
}

function filterAppointments() {
  const searchTerm = document.getElementById("appointmentSearch")?.value.toLowerCase() || ""
  const dateFilter = document.getElementById("appointmentDateFilter")?.value || "all"

  // Implementation for appointment filtering would go here
  console.log("Filtering appointments:", searchTerm, dateFilter)
}

function filterTransactions() {
  const dateFrom = document.getElementById("reportDateFrom")?.value || ""
  const dateTo = document.getElementById("reportDateTo")?.value || ""
  const typeFilter = document.getElementById("reportType")?.value || "all"
  const categoryFilter = document.getElementById("reportCategory")?.value || "all"

  filteredTransactionsData = transactionsData.filter((transaction) => {
    const matchesDateRange = (!dateFrom || transaction.date >= dateFrom) && (!dateTo || transaction.date <= dateTo)
    const matchesType = typeFilter === "all" || transaction.type === typeFilter
    const matchesCategory = categoryFilter === "all" || transaction.category === categoryFilter

    return matchesDateRange && matchesType && matchesCategory
  })

  populateTransactionsTable()
  updateTransactionSummary()
}

function resetTransactionFilters() {
  document.getElementById("reportDateFrom").value = "2024-01-01"
  document.getElementById("reportDateTo").value = "2024-01-31"
  document.getElementById("reportType").value = "all"
  document.getElementById("reportCategory").value = "all"

  filteredTransactionsData = [...transactionsData]
  populateTransactionsTable()
  updateTransactionSummary()
}

function updateTransactionSummary() {
  const totalTransactions = filteredTransactionsData.length
  const totalValue = filteredTransactionsData.reduce((sum, t) => sum + Math.abs(t.totalValue), 0)
  const totalPurchases = filteredTransactionsData
    .filter((t) => t.type === "Purchase")
    .reduce((sum, t) => sum + t.totalValue, 0)
  const totalDisposals = filteredTransactionsData
    .filter((t) => t.type === "Disposal")
    .reduce((sum, t) => sum + t.totalValue, 0)

  document.getElementById("totalTransactions").textContent = totalTransactions
  document.getElementById("totalValue").textContent = `$${totalValue.toFixed(2)}`
  document.getElementById("totalPurchases").textContent = `$${totalPurchases.toFixed(2)}`
  document.getElementById("totalDisposals").textContent = `$${totalDisposals.toFixed(2)}`
}

// Sort medicine table
function sortMedicineTable(column) {
  if (currentSortColumn === column) {
    currentSortDirection = currentSortDirection === "asc" ? "desc" : "asc"
  } else {
    currentSortColumn = column
    currentSortDirection = "asc"
  }

  // Update header classes
  document.querySelectorAll("th.sortable").forEach((th) => {
    th.classList.remove("sort-asc", "sort-desc")
    if (th.getAttribute("data-sort") === column) {
      th.classList.add(currentSortDirection === "asc" ? "sort-asc" : "sort-desc")
    }
  })

  // Sort the data
  filteredMedicineData.sort((a, b) => {
    let aValue = a[column]
    let bValue = b[column]

    // Handle different data types
    if (column === "stock") {
      aValue = Number.parseInt(aValue)
      bValue = Number.parseInt(bValue)
    } else if (column === "stock-date" || column === "expiry") {
      aValue = new Date(aValue)
      bValue = new Date(bValue)
    } else {
      aValue = aValue.toString().toLowerCase()
      bValue = bValue.toString().toLowerCase()
    }

    if (currentSortDirection === "asc") {
      return aValue < bValue ? -1 : aValue > bValue ? 1 : 0
    } else {
      return aValue > bValue ? -1 : aValue < bValue ? 1 : 0
    }
  })

  populateMedicineTable()
}

// Modal functions
function openModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.classList.add("show")
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.classList.remove("show")
  }
}

// Staff logs functions
function viewStaffLogs(staffId) {
  const logs = staffLogsData[staffId] || []
  const tableBody = document.getElementById("staffLogsTableBody")

  if (!tableBody) return

  tableBody.innerHTML = ""

  logs.forEach((log) => {
    const row = document.createElement("tr")
    row.innerHTML = `
      <td>${log.date}</td>
      <td>${log.time}</td>
      <td>${log.action}</td>
      <td>${log.details}</td>
    `
    tableBody.appendChild(row)
  })

  openModal("staffLogsModal")
}

function exportStaffLogs() {
  // Simulate staff logs export
  alert("Staff logs exported successfully! (This is a simulation)")
  console.log("Exporting staff logs...")
}

// Appointment functions
function viewAppointmentRecord(appointmentId) {
  const record = appointmentRecords[appointmentId]
  if (!record) return

  currentAppointmentId = appointmentId

  const content = document.getElementById("appointmentRecordContent")
  if (!content) return

  content.innerHTML = `
    <div class="record-details">
      <div class="record-field">
        <label>Appointment ID:</label>
        <span>${record.id}</span>
      </div>
      <div class="record-field">
        <label>Patient:</label>
        <span>${record.patient}</span>
      </div>
      <div class="record-field">
        <label>Patient ID:</label>
        <span>${record.patientId}</span>
      </div>
      <div class="record-field">
        <label>Doctor:</label>
        <span>${record.doctor}</span>
      </div>
      <div class="record-field">
        <label>Date:</label>
        <span>${record.date}</span>
      </div>
      <div class="record-field">
        <label>Time:</label>
        <span>${record.time}</span>
      </div>
      <div class="record-field">
        <label>Type:</label>
        <span>${record.type}</span>
      </div>
      <div class="record-field">
        <label>Status:</label>
        <span class="status-badge ${getAppointmentStatusClass(record.status)}">${record.status}</span>
      </div>
      <div class="record-field full-width">
        <label>Diagnosis:</label>
        <span>${record.diagnosis}</span>
      </div>
      <div class="record-field full-width">
        <label>Treatment:</label>
        <span>${record.treatment}</span>
      </div>
      <div class="record-field full-width">
        <label>Prescription:</label>
        <span>${record.prescription}</span>
      </div>
      <div class="record-field full-width">
        <label>Notes:</label>
        <span>${record.notes}</span>
      </div>
    </div>
    ${
      Object.keys(record.vitals).length > 0
        ? `
      <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; color: var(--color-golden-yellow);">Vital Signs</h4>
      <div class="vitals-grid">
        ${Object.entries(record.vitals)
          .map(
            ([key, value]) => `
          <div class="record-field">
            <label>${formatVitalLabel(key)}:</label>
            <span>${value}</span>
          </div>
        `,
          )
          .join("")}
      </div>
    `
        : ""
    }
  `

  openModal("appointmentRecordModal")
}

function getAppointmentStatusClass(status) {
  switch (status.toLowerCase()) {
    case "completed":
      return "completed"
    case "pending":
      return "pending"
    case "scheduled":
      return "pending"
    case "urgent":
      return "expired"
    case "cancelled":
      return "cancelled"
    default:
      return "pending"
  }
}

function formatVitalLabel(key) {
  const labels = {
    bloodPressure: "Blood Pressure",
    heartRate: "Heart Rate",
    temperature: "Temperature",
    weight: "Weight",
    bloodSugar: "Blood Sugar",
    oxygenSaturation: "Oxygen Saturation",
  }
  return labels[key] || key
}

function downloadPDF(appointmentId) {
  // Simulate PDF download
  alert(`Downloading PDF for appointment ${appointmentId}... (This is a simulation)`)
  console.log(`Downloading PDF for appointment: ${appointmentId}`)
}

function downloadCurrentRecord() {
  if (currentAppointmentId) {
    downloadPDF(currentAppointmentId)
  }
}

// Transactions report functions
function openTransactionsReport() {
  populateTransactionsTable()
  updateTransactionSummary()
  openModal("transactionsReportModal")
}

function exportTransactionsCSV() {
  // Simulate CSV export
  alert("Transactions exported to CSV successfully! (This is a simulation)")
  console.log("Exporting transactions to CSV...")
}

function downloadTransactionsPDF() {
  // Simulate PDF download
  alert("Transactions report PDF downloaded successfully! (This is a simulation)")
  console.log("Downloading transactions PDF...")
}

// Quick action functions
function addNewStaff() {
  alert("Add New Staff functionality would open here. (This is a simulation)")
  console.log("Opening Add New Staff form...")
}

function addMedicine() {
  alert("Add Medicine functionality would open here. (This is a simulation)")
  console.log("Opening Add Medicine form...")
}

function scheduleAppointment() {
  alert("Schedule Appointment functionality would open here. (This is a simulation)")
  console.log("Opening Schedule Appointment form...")
}

function generateReport() {
  alert("Generate Report functionality would open here. (This is a simulation)")
  console.log("Opening Generate Report dialog...")
}

// Medicine functions
function editMedicine(medicineId) {
  alert(`Edit medicine ${medicineId} functionality would open here. (This is a simulation)`)
  console.log(`Editing medicine: ${medicineId}`)
}

function scanBarcode(medicineId) {
  alert(`Barcode scanner for medicine ${medicineId} would open here. (This is a simulation)`)
  console.log(`Scanning barcode for medicine: ${medicineId}`)
}

// Inventory functions
function generateRestockOrder() {
  alert("Generating restock order... (This is a simulation)")
  console.log("Generating restock order...")
}

function disposeExpiredMedicines() {
  alert("Disposing expired medicines... (This is a simulation)")
  console.log("Disposing expired medicines...")
}

function markAllAlertsRead() {
  alert("All alerts marked as read. (This is a simulation)")
  console.log("Marking all alerts as read...")
}

function generateInventoryReport() {
  alert("Generating inventory report... (This is a simulation)")
  console.log("Generating inventory report...")
}

function generateStockReport() {
  alert("Generating stock level report... (This is a simulation)")
  console.log("Generating stock level report...")
}

function generateExpiryReport() {
  alert("Generating expiry report... (This is a simulation)")
  console.log("Generating expiry report...")
}

// Responsive handling
window.addEventListener("resize", () => {
  const sidebar = document.getElementById("sidebar")
  const mainContent = document.querySelector(".main-content")

  if (window.innerWidth > 768) {
    sidebar.classList.remove("show")
  }
})

// Initialize tooltips and other UI enhancements
function initializeUIEnhancements() {
  // Add hover effects and tooltips
  const buttons = document.querySelectorAll(".btn-icon")
  buttons.forEach((button) => {
    button.addEventListener("mouseenter", function () {
      const title = this.getAttribute("title")
      if (title) {
        // Could implement custom tooltip here
      }
    })
  })
}

// Call UI enhancements after DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(initializeUIEnhancements, 100)
})

// Export functions for global access
window.viewStaffLogs = viewStaffLogs
window.exportStaffLogs = exportStaffLogs
window.viewAppointmentRecord = viewAppointmentRecord
window.downloadPDF = downloadPDF
window.downloadCurrentRecord = downloadCurrentRecord
window.openTransactionsReport = openTransactionsReport
window.filterTransactions = filterTransactions
window.resetTransactionFilters = resetTransactionFilters
window.exportTransactionsCSV = exportTransactionsCSV
window.downloadTransactionsPDF = downloadTransactionsPDF
window.closeModal = closeModal
window.addNewStaff = addNewStaff
window.addMedicine = addMedicine
window.scheduleAppointment = scheduleAppointment
window.generateReport = generateReport
window.editMedicine = editMedicine
window.scanBarcode = scanBarcode
window.generateRestockOrder = generateRestockOrder
window.disposeExpiredMedicines = disposeExpiredMedicines
window.markAllAlertsRead = markAllAlertsRead
window.generateInventoryReport = generateInventoryReport
window.generateStockReport = generateStockReport
window.generateExpiryReport = generateExpiryReport

</script>
</body>
</html>
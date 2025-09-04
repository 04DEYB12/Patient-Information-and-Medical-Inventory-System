<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NatureCare - Holistic Healthcare Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
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
      width: 40px;
      height: 40px;
      background-color: var(--color-golden-yellow);
      border-radius: var(--radius-lg);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: var(--shadow-md);
      flex-shrink: 0;
    }

    .logo svg {
      width: 20px;
      height: 20px;
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

    /* Tooltip for collapsed sidebar */
    .sidebar.collapsed .logo-container::after {
      content: "NatureCare";
      position: absolute;
      left: calc(100% + 15px);
      top: 50%;
      transform: translateY(-50%);
      background-color: var(--color-nature-green-800);
      color: var(--color-nature-green-50);
      padding: 0.5rem 0.75rem;
      border-radius: var(--radius);
      font-size: 0.875rem;
      font-weight: 500;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--color-nature-green-600);
    }

    .sidebar.collapsed .logo-container:hover::after {
      opacity: 1;
      visibility: visible;
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

    .nav-item a svg {
      width: 20px;
      height: 20px;
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

    /* Tooltip for nav items when collapsed */
    .sidebar.collapsed .nav-item::after {
      content: attr(data-tooltip);
      position: absolute;
      left: calc(100% + 15px);
      top: 50%;
      transform: translateY(-50%);
      background-color: var(--color-nature-green-800);
      color: var(--color-nature-green-50);
      padding: 0.5rem 0.75rem;
      border-radius: var(--radius);
      font-size: 0.875rem;
      font-weight: 500;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--color-nature-green-600);
    }

    .sidebar.collapsed .nav-item:hover::after {
      opacity: 1;
      visibility: visible;
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
      width: 16px;
      height: 16px;
      color: var(--color-nature-green-200);
      transition: var(--transition);
      flex-shrink: 0;
      opacity: 1;
    }

    .sidebar.collapsed .dropdown-icon {
      opacity: 0;
      width: 0;
    }

    /* Profile tooltip when collapsed */
    .sidebar.collapsed .profile-info::after {
      content: "Dr. Sarah Wilson";
      position: absolute;
      left: calc(100% + 15px);
      top: 50%;
      transform: translateY(-50%);
      background-color: var(--color-nature-green-800);
      color: var(--color-nature-green-50);
      padding: 0.5rem 0.75rem;
      border-radius: var(--radius);
      font-size: 0.875rem;
      font-weight: 500;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--color-nature-green-600);
    }

    .sidebar.collapsed .profile-info:hover::after {
      opacity: 1;
      visibility: visible;
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

    .dropdown-menu li a svg {
      width: 16px;
      height: 16px;
      margin-right: 0.5rem;
      color: var(--color-golden-yellow);
    }

    .dropdown-menu li a:hover {
      background-color: var(--color-nature-green-100);
    }

    .dropdown-menu li a.logout {
      color: var(--color-red-600);
    }

    .dropdown-menu li a.logout svg {
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

    .sidebar-toggle svg {
      width: 20px;
      height: 20px;
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

    .stat-header svg {
      width: 20px;
      height: 20px;
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

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 1.5rem;
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
      color: var(--color-golden-yellow);
    }

    .card-actions {
      display: flex;
      gap: 0.75rem;
      align-items: center;
    }

    .card-content {
      padding: 1.25rem;
    }

    /* Patient List */
    .patient-list, .appointment-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .patient-item, .appointment-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 0.75rem;
      background-color: var(--color-nature-green-50);
      border-radius: var(--radius);
      transition: var(--transition);
    }

    .patient-item:hover, .appointment-item:hover {
      background-color: var(--color-nature-green-100);
    }

    .patient-info, .appointment-info {
      flex: 1;
      min-width: 0;
    }

    .patient-info h4, .appointment-info h4 {
      font-size: 0.875rem;
      font-weight: 600;
      margin: 0;
      color: var(--color-nature-green-800);
    }

    .patient-info p, .appointment-info p {
      font-size: 0.75rem;
      color: var(--color-nature-green-600);
      margin: 0;
    }

    .appointment-icon {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background-color: rgba(255, 214, 10, 0.2);
      border: 2px solid var(--color-golden-yellow);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .appointment-icon svg {
      width: 20px;
      height: 20px;
      color: var(--color-golden-yellow);
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

    .status-badge.critical {
      background-color: var(--color-red-500);
      color: white;
    }

    .status-badge.discharged {
      background-color: var(--color-nature-green-200);
      color: var(--color-nature-green-800);
    }

    .status-badge.scheduled {
      background-color: var(--color-nature-green-600);
      color: var(--color-nature-green-50);
    }

    .status-badge.confirmed {
      background-color: var(--color-nature-green-200);
      color: var(--color-nature-green-800);
    }

    .status-badge.completed {
      background-color: var(--color-nature-green-600);
      color: var(--color-nature-green-50);
    }

    .status-badge.urgent {
      background-color: var(--color-red-500);
      color: white;
    }

    .status-badge.in-progress {
      background-color: var(--color-nature-green-200);
      color: var(--color-nature-green-800);
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

    .id-cell {
      font-weight: 500;
      color: var(--color-golden-yellow) !important;
    }

    .contact-info {
      font-size: 0.875rem;
    }

    .contact-info .email {
      color: var(--color-nature-green-500);
      font-size: 0.75rem;
    }

    .patient-info .name {
      font-weight: 500;
      color: var(--color-nature-green-800);
    }

    .patient-info .id {
      font-size: 0.75rem;
      color: var(--color-nature-green-500);
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

    .btn svg {
      width: 16px;
      height: 16px;
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

    .btn-icon svg {
      width: 16px;
      height: 16px;
      color: var(--color-nature-green-700);
    }

    .btn-icon:hover {
      background-color: rgba(255, 214, 10, 0.2);
    }

    .btn-icon:hover svg {
      color: var(--color-golden-yellow);
    }

    .btn-icon.delete:hover {
      background-color: var(--color-red-100);
    }

    .btn-icon.delete:hover svg {
      color: var(--color-red-600);
    }

    .action-buttons {
      display: flex;
      gap: 0.25rem;
    }

    /* Forms */
    .search-container {
      position: relative;
    }

    .search-container svg {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      width: 16px;
      height: 16px;
      color: var(--color-nature-green-500);
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

    .select-container {
      position: relative;
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

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
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

    .form-actions {
      grid-column: span 2;
      display: flex;
      justify-content: flex-start;
      margin-top: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .stats-grid {
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
      
      .card-grid {
        grid-template-columns: 1fr;
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
      
      .search-container input {
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
            <i data-lucide="leaf"></i>
          </div>
          <div class="brand">
            <h1>Healthtrack</h1>
            <span>Patient Information &<br>Inventory Management System</span>
          </div>
        </div>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-group">
          <h2 class="nav-group-title">Navigation</h2>
          <ul class="nav-items">
            <li class="nav-item active" data-section="dashboard" data-tooltip="Dashboard">
              <a href="#dashboard">
                <i data-lucide="activity"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="nav-item" data-section="patients" data-tooltip="Patients">
              <a href="#patients">
                <i data-lucide="users"></i>
                <span>Patients</span>
              </a>
            </li>
            <li class="nav-item" data-section="appointments" data-tooltip="Appointments">
              <a href="#appointments">
                <i data-lucide="calendar"></i>
                <span>Appointments</span>
              </a>
            </li>
            <li class="nav-item" data-section="reports" data-tooltip="Reports">
              <a href="#reports">
                <i data-lucide="file-text"></i>
                <span>Reports</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="profile-menu" id="profileMenu">
          <div class="profile-info">
            <div class="avatar">
              <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=FFD60A&color=1B4332&bold=true" alt="Dr. Sarah Wilson">
            </div>
            <div class="user-info">
              <h3>Sf.Dave O. Malaran</h3>
              <span>malarandave001@gmail.com</span>
            </div>
            <i data-lucide="chevron-up" class="dropdown-icon"></i>
          </div>
          <div class="profile-dropdown" id="profileDropdown">
            <div class="dropdown-header">
              <div class="avatar">
                <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=FFD60A&color=1B4332&bold=true" alt="Dr. Sarah Wilson">
              </div>
              <div class="user-info">
                <h3>Sf. Dave O. Malaran</h3>
                <span class="role">Patient Management Staff</span>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <ul class="dropdown-menu">
              <li><a href="#profile"><i data-lucide="user"></i> Profile</a></li>
              <li><a href="#settings"><i data-lucide="settings"></i> Settings</a></li>
              <li><a href="#notifications"><i data-lucide="bell"></i> Notifications</a></li>
              <div class="dropdown-divider"></div>
              <li><a href="PIAIMS.php" class="logout"><i data-lucide="log-out"></i> Log out</a></li>
            </ul>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header class="main-header">
        <button class="sidebar-toggle" id="sidebarToggle">
          <i data-lucide="menu"></i>
        </button>
        <h1 id="pageTitle">Healthcare Dashboard</h1>
      </header>

      <!-- Dashboard Content -->
      <div class="content-container">
        <!-- Dashboard Section -->
        <section class="content-section active" id="dashboardSection">
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-header">
                <h3>Total Patients</h3>
                <i data-lucide="users"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">1,247</div>
                <p class="stat-change"><span class="positive">+12%</span> from last month</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Active Cases</h3>
                <i data-lucide="heart"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">89</div>
                <p class="stat-change"><span class="positive">+3</span> new today</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Today's Appointments</h3>
                <i data-lucide="clock"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">24</div>
                <p class="stat-change"><span class="positive">6 completed</span></p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Revenue This Month</h3>
                <i data-lucide="trending-up"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">$45,231</div>
                <p class="stat-change"><span class="positive">+8%</span> from last month</p>
              </div>
            </div>
          </div>

          <div class="card-grid">
            <div class="card">
              <div class="card-header">
                <h2>Recent Patients</h2>
              </div>
              <div class="card-content">
                <div class="patient-list">
                  <div class="patient-item">
                    <div class="avatar">
                      <span>SJ</span>
                    </div>
                    <div class="patient-info">
                      <h4>Sarah Johnson</h4>
                      <p>Hypertension</p>
                    </div>
                    <span class="status-badge active">Active</span>
                  </div>
                  <div class="patient-item">
                    <div class="avatar">
                      <span>MC</span>
                    </div>
                    <div class="patient-info">
                      <h4>Michael Chen</h4>
                      <p>Diabetes Type 2</p>
                    </div>
                    <span class="status-badge active">Active</span>
                  </div>
                  <div class="patient-item">
                    <div class="avatar">
                      <span>ED</span>
                    </div>
                    <div class="patient-info">
                      <h4>Emily Davis</h4>
                      <p>Asthma</p>
                    </div>
                    <span class="status-badge discharged">Discharged</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h2>Upcoming Appointments</h2>
              </div>
              <div class="card-content">
                <div class="appointment-list">
                  <div class="appointment-item">
                    <div class="appointment-icon">
                      <i data-lucide="calendar-days"></i>
                    </div>
                    <div class="appointment-info">
                      <h4>Sarah Johnson</h4>
                      <p>09:00 AM - Dr. Smith</p>
                    </div>
                    <span class="status-badge scheduled">Scheduled</span>
                  </div>
                  <div class="appointment-item">
                    <div class="appointment-icon">
                      <i data-lucide="calendar-days"></i>
                    </div>
                    <div class="appointment-info">
                      <h4>Michael Chen</h4>
                      <p>10:30 AM - Dr. Johnson</p>
                    </div>
                    <span class="status-badge confirmed">Confirmed</span>
                  </div>
                  <div class="appointment-item">
                    <div class="appointment-icon">
                      <i data-lucide="calendar-days"></i>
                    </div>
                    <div class="appointment-info">
                      <h4>Robert Wilson</h4>
                      <p>11:00 AM - Dr. Brown</p>
                    </div>
                    <span class="status-badge urgent">Urgent</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Patients Section -->
        <section class="content-section" id="patientsSection">
          <div class="card">
            <div class="card-header">
              <h2>Patient Records</h2>
              <div class="card-actions">
                <div class="search-container">
                  <i data-lucide="search"></i>
                  <input type="text" placeholder="Search patients..." id="patientSearch">
                </div>
                <div class="select-container">
                  <select id="statusFilter">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="critical">Critical</option>
                    <option value="discharged">Discharged</option>
                  </select>
                </div>
                <button class="btn btn-primary">
                  <i data-lucide="plus"></i>
                  Add Patient
                </button>
              </div>
            </div>
            <div class="card-content">
              <div class="table-container">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Patient ID</th>
                      <th>Name</th>
                      <th>Age</th>
                      <th>Gender</th>
                      <th>Condition</th>
                      <th>Last Visit</th>
                      <th>Doctor</th>
                      <th>Contact</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="id-cell">P001</td>
                      <td>Sarah Johnson</td>
                      <td>34</td>
                      <td>Female</td>
                      <td>Hypertension</td>
                      <td>2024-01-15</td>
                      <td>Dr. Smith</td>
                      <td>
                        <div class="contact-info">
                          <div>(555) 123-4567</div>
                          <div class="email">sarahj@gmail.com</div>
                        </div>
                      </td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">P002</td>
                      <td>Michael Chen</td>
                      <td>45</td>
                      <td>Male</td>
                      <td>Diabetes Type 2</td>
                      <td>2024-01-14</td>
                      <td>Dr. Johnson</td>
                      <td>
                        <div class="contact-info">
                          <div>(555) 234-5678</div>
                          <div class="email">m.chen@email.com</div>
                        </div>
                      </td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">P003</td>
                      <td>Emily Davis</td>
                      <td>28</td>
                      <td>Female</td>
                      <td>Asthma</td>
                      <td>2024-01-12</td>
                      <td>Dr. Williams</td>
                      <td>
                        <div class="contact-info">
                          <div>(555) 345-6789</div>
                          <div class="email">emily@gmail.com</div>
                        </div>
                      </td>
                      <td><span class="status-badge discharged">Discharged</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">P004</td>
                      <td>Robert Wilson</td>
                      <td>67</td>
                      <td>Male</td>
                      <td>Heart Disease</td>
                      <td>2024-01-16</td>
                      <td>Dr. Brown</td>
                      <td>
                        <div class="contact-info">
                          <div>(555) 456-7890</div>
                          <div class="email">wilson@gmail.com</div>
                        </div>
                      </td>
                      <td><span class="status-badge critical">Critical</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">P005</td>
                      <td>Lisa Anderson</td>
                      <td>52</td>
                      <td>Female</td>
                      <td>Arthritis</td>
                      <td>2024-01-13</td>
                      <td>Dr. Davis</td>
                      <td>
                        <div class="contact-info">
                          <div>(555) 567-8901</div>
                          <div class="email">lisa.a@email.com</div>
                        </div>
                      </td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- Appointments Section -->
        <section class="content-section" id="appointmentsSection">
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-header">
                <h3>Today's Appointments</h3>
                <i data-lucide="calendar"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">24</div>
                <p class="stat-change">6 completed, 18 pending</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>This Week</h3>
                <i data-lucide="calendar-days"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">156</div>
                <p class="stat-change">+12% from last week</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Urgent Cases</h3>
                <i data-lucide="alert-triangle"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">3</div>
                <p class="stat-change">Requires immediate attention</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Completion Rate</h3>
                <i data-lucide="user-check"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">94%</div>
                <p class="stat-change">+2% from last month</p>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2>Appointment Schedule</h2>
              <div class="card-actions">
                <div class="select-container">
                  <select id="dateFilter">
                    <option value="all">All Dates</option>
                    <option value="today">Today</option>
                    <option value="tomorrow">Tomorrow</option>
                    <option value="week">This Week</option>
                  </select>
                </div>
                <button class="btn btn-primary">
                  <i data-lucide="plus"></i>
                  New Appointment
                </button>
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
                      <th>Duration</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="id-cell">A001</td>
                      <td>
                        <div class="patient-info">
                          <div class="name">Sarah Johnson</div>
                          <div class="id">P001</div>
                        </div>
                      </td>
                      <td>Dr. Smith</td>
                      <td>2024-01-18</td>
                      <td>09:00 AM</td>
                      <td>Follow-up</td>
                      <td>30 min</td>
                      <td><span class="status-badge scheduled">Scheduled</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">A002</td>
                      <td>
                        <div class="patient-info">
                          <div class="name">Michael Chen</div>
                          <div class="id">P002</div>
                        </div>
                      </td>
                      <td>Dr. Johnson</td>
                      <td>2024-01-18</td>
                      <td>10:30 AM</td>
                      <td>Consultation</td>
                      <td>45 min</td>
                      <td><span class="status-badge confirmed">Confirmed</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">A003</td>
                      <td>
                        <div class="patient-info">
                          <div class="name">Emily Davis</div>
                          <div class="id">P003</div>
                        </div>
                      </td>
                      <td>Dr. Williams</td>
                      <td>2024-01-18</td>
                      <td>02:00 PM</td>
                      <td>Check-up</td>
                      <td>30 min</td>
                      <td><span class="status-badge completed">Completed</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">A004</td>
                      <td>
                        <div class="patient-info">
                          <div class="name">Robert Wilson</div>
                          <div class="id">P004</div>
                        </div>
                      </td>
                      <td>Dr. Brown</td>
                      <td>2024-01-19</td>
                      <td>11:00 AM</td>
                      <td>Emergency</td>
                      <td>60 min</td>
                      <td><span class="status-badge urgent">Urgent</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">A005</td>
                      <td>
                        <div class="patient-info">
                          <div class="name">Lisa Anderson</div>
                          <div class="id">P005</div>
                        </div>
                      </td>
                      <td>Dr. Davis</td>
                      <td>2024-01-19</td>
                      <td>03:30 PM</td>
                      <td>Therapy</td>
                      <td>45 min</td>
                      <td><span class="status-badge scheduled">Scheduled</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>

        <!-- Reports Section -->
        <section class="content-section" id="reportsSection">
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-header">
                <h3>Total Reports</h3>
                <i data-lucide="file-text"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">47</div>
                <p class="stat-change">This month</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Completed</h3>
                <i data-lucide="bar-chart-3"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">42</div>
                <p class="stat-change">89% completion rate</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>In Progress</h3>
                <i data-lucide="clock"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">5</div>
                <p class="stat-change">Expected completion: 3 days</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-header">
                <h3>Downloads</h3>
                <i data-lucide="download"></i>
              </div>
              <div class="stat-content">
                <div class="stat-value">234</div>
                <p class="stat-change">This month</p>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2>Generate New Report</h2>
            </div>
            <div class="card-content">
              <form class="report-form">
                <div class="form-grid">
                  <div class="form-group">
                    <label for="report-type">Report Type</label>
                    <select id="report-type">
                      <option value="" selected disabled>Select report type</option>
                      <option value="patient">Patient Report</option>
                      <option value="financial">Financial Report</option>
                      <option value="inventory">Inventory Report</option>
                      <option value="staff">Staff Performance</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="date-range">Date Range</label>
                    <select id="date-range">
                      <option value="" selected disabled>Select date range</option>
                      <option value="week">Last Week</option>
                      <option value="month">Last Month</option>
                      <option value="quarter">Last Quarter</option>
                      <option value="year">Last Year</option>
                    </select>
                  </div>
                  <div class="form-group full-width">
                    <label for="description">Description (Optional)</label>
                    <textarea id="description" placeholder="Enter report description..."></textarea>
                  </div>
                  <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                      <i data-lucide="plus"></i>
                      Generate Report
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h2>Recent Reports</h2>
              <div class="card-actions">
                <button class="btn btn-outline">
                  <i data-lucide="filter"></i>
                  Filter
                </button>
                <button class="btn btn-outline">
                  <i data-lucide="download"></i>
                  Export All
                </button>
              </div>
            </div>
            <div class="card-content">
              <div class="table-container">
                <table class="data-table">
                  <thead>
                    <tr>
                      <th>Report ID</th>
                      <th>Title</th>
                      <th>Type</th>
                      <th>Generated By</th>
                      <th>Date</th>
                      <th>Details</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="id-cell">R001</td>
                      <td>Monthly Patient Summary</td>
                      <td>Patient Report</td>
                      <td>Dr. Smith</td>
                      <td>2024-01-15</td>
                      <td>247 patients</td>
                      <td><span class="status-badge completed">Completed</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="download"></i></button>
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">R002</td>
                      <td>Inventory Status Report</td>
                      <td>Inventory Report</td>
                      <td>Admin</td>
                      <td>2024-01-14</td>
                      <td>156 items</td>
                      <td><span class="status-badge completed">Completed</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="download"></i></button>
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">R003</td>
                      <td>Financial Summary Q1</td>
                      <td>Financial Report</td>
                      <td>Finance Team</td>
                      <td>2024-01-10</td>
                      <td>$125,000</td>
                      <td><span class="status-badge in-progress">In Progress</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="download"></i></button>
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="id-cell">R004</td>
                      <td>Staff Performance Review</td>
                      <td>HR Report</td>
                      <td>HR Manager</td>
                      <td>2024-01-08</td>
                      <td>45 staff members</td>
                      <td><span class="status-badge completed">Completed</span></td>
                      <td>
                        <div class="action-buttons">
                          <button class="btn-icon"><i data-lucide="download"></i></button>
                          <button class="btn-icon"><i data-lucide="edit"></i></button>
                          <button class="btn-icon delete"><i data-lucide="trash-2"></i></button>
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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Lucide icons
      lucide.createIcons();
      
      // DOM Elements
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const mainContent = document.querySelector('.main-content');
      const navItems = document.querySelectorAll('.nav-item');
      const contentSections = document.querySelectorAll('.content-section');
      const pageTitle = document.getElementById('pageTitle');
      const profileMenu = document.getElementById('profileMenu');
      const profileDropdown = document.getElementById('profileDropdown');
      const patientSearch = document.getElementById('patientSearch');
      const statusFilter = document.getElementById('statusFilter');
      
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
          
          // Update active nav item
          navItems.forEach(nav => nav.classList.remove('active'));
          this.classList.add('active');
          
          // Show corresponding section
          contentSections.forEach(content => {
            content.classList.remove('active');
            if (content.id === section + 'Section') {
              content.classList.add('active');
            }
          });
          
          // Update page title
          updatePageTitle(section);
          
          // Close sidebar on mobile
          if (window.innerWidth < 768) {
            sidebar.classList.remove('show');
          }
        });
      });
      
      // Update page title based on section
      function updatePageTitle(section) {
        switch(section) {
          case 'dashboard':
            pageTitle.textContent = 'Healthcare Dashboard';
            break;
          case 'patients':
            pageTitle.textContent = 'Patient Management';
            break;
          case 'appointments':
            pageTitle.textContent = 'Appointment Management';
            break;
          case 'reports':
            pageTitle.textContent = 'Reports & Analytics';
            break;
          default:
            pageTitle.textContent = 'Healthcare Dashboard';
        }
      }
      
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
      
      // Patient search functionality
      if (patientSearch) {
        patientSearch.addEventListener('input', function() {
          const searchTerm = this.value.toLowerCase();
          const tableRows = document.querySelectorAll('#patientsSection .data-table tbody tr');
          
          tableRows.forEach(row => {
            const patientName = row.cells[1].textContent.toLowerCase();
            const patientId = row.cells[0].textContent.toLowerCase();
            
            if (patientName.includes(searchTerm) || patientId.includes(searchTerm)) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });
      }
      
      // Status filter functionality
      if (statusFilter) {
        statusFilter.addEventListener('change', function() {
          const filterValue = this.value.toLowerCase();
          const tableRows = document.querySelectorAll('#patientsSection .data-table tbody tr');
          
          tableRows.forEach(row => {
            const statusBadge = row.querySelector('.status-badge');
            const status = statusBadge.textContent.toLowerCase();
            
            if (filterValue === 'all' || status === filterValue) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });
      }
      
      // Form submission handlers
      const reportForm = document.querySelector('.report-form');
      if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const reportType = document.getElementById('report-type').value;
          const dateRange = document.getElementById('date-range').value;
          const description = document.getElementById('description').value;
          
          if (!reportType || !dateRange) {
            alert('Please select both report type and date range.');
            return;
          }
          
          // Simulate report generation
          alert(`Generating ${reportType} for ${dateRange}...`);
          
          // Reset form
          this.reset();
        });
      }
      
      // Button click handlers
      document.addEventListener('click', function(e) {
        // Handle edit buttons
        if (e.target.closest('.btn-icon') && e.target.closest('.btn-icon').querySelector('[data-lucide="edit"]')) {
          e.preventDefault();
          alert('Edit functionality would be implemented here.');
        }
        
        // Handle delete buttons
        if (e.target.closest('.btn-icon.delete') || (e.target.closest('.btn-icon') && e.target.closest('.btn-icon').querySelector('[data-lucide="trash-2"]'))) {
          e.preventDefault();
          if (confirm('Are you sure you want to delete this item?')) {
            alert('Delete functionality would be implemented here.');
          }
        }
        
        // Handle download buttons
        if (e.target.closest('.btn-icon') && e.target.closest('.btn-icon').querySelector('[data-lucide="download"]')) {
          e.preventDefault();
          alert('Download functionality would be implemented here.');
        }
        
        // Handle add buttons
        if (e.target.closest('.btn-primary') && (e.target.textContent.includes('Add') || e.target.textContent.includes('New'))) {
          e.preventDefault();
          alert('Add/Create functionality would be implemented here.');
        }
        
        // Handle profile dropdown links
        if (e.target.closest('.dropdown-menu a')) {
          e.preventDefault();
          const action = e.target.textContent.trim();
          
          if (action === 'Log out') {
            if (confirm('Are you sure you want to log out?')) {
              alert('Logout functionality would be implemented here.');
            }
          } else {
            alert(`${action} functionality would be implemented here.`);
          }
        }
      });
      
      // Responsive sidebar handling
      function handleResize() {
        if (window.innerWidth < 768) {
          sidebar.classList.remove('collapsed');
          mainContent.classList.remove('expanded');
        }
      }
      
      window.addEventListener('resize', handleResize);
      
      // Initialize responsive state
      handleResize();
      
      // Smooth scrolling for better UX
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
      
      // Add loading states for buttons
      document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function() {
          if (!this.classList.contains('loading')) {
            const originalText = this.innerHTML;
            this.classList.add('loading');
            this.style.opacity = '0.7';
            this.style.pointerEvents = 'none';
            
            setTimeout(() => {
              this.innerHTML = originalText;
              this.classList.remove('loading');
              this.style.opacity = '';
              this.style.pointerEvents = '';
            }, 1000);
          }
        });
      });
      
      // Initialize tooltips for icons
      document.querySelectorAll('.btn-icon').forEach(button => {
        const icon = button.querySelector('svg');
        if (icon) {
          const iconName = icon.getAttribute('data-lucide');
          let tooltip = '';
          
          switch(iconName) {
            case 'edit':
              tooltip = 'Edit';
              break;
            case 'trash-2':
              tooltip = 'Delete';
              break;
            case 'download':
              tooltip = 'Download';
              break;
            default:
              tooltip = iconName.charAt(0).toUpperCase() + iconName.slice(1);
          }
          
          button.setAttribute('title', tooltip);
        }
      });
      
      // Add keyboard navigation support
      document.addEventListener('keydown', function(e) {
        // ESC key to close dropdown
        if (e.key === 'Escape') {
          profileDropdown.classList.remove('show');
        }
        
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
          e.preventDefault();
          if (patientSearch && patientSearch.offsetParent !== null) {
            patientSearch.focus();
          }
        }
      });
      
      // Auto-save form data to localStorage
      const formInputs = document.querySelectorAll('input, select, textarea');
      formInputs.forEach(input => {
        // Load saved data
        const savedValue = localStorage.getItem(`naturecare_${input.id}`);
        if (savedValue && input.type !== 'password') {
          input.value = savedValue;
        }
        
        // Save data on change
        input.addEventListener('change', function() {
          if (this.type !== 'password') {
            localStorage.setItem(`naturecare_${this.id}`, this.value);
          }
        });
      });
      
      console.log('Healthtrack Dashboard initialized successfully!');
    });
  </script>
</body>
</html>
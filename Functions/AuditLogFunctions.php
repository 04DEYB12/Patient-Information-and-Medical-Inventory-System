<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors to users
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Ensure no output is sent before headers
if (headers_sent($filename, $linenum)) {
    error_log("Headers already sent in $filename on line $linenum");
    exit('Headers already sent');
}

header('Content-Type: application/json');

// Include database connection
require_once __DIR__ . '/../Landing Repository/Connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an administrator
if (!isset($_SESSION['User_ID']) || ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Super Administrator')) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Access denied. Administrator privileges required.'
    ]);
    exit();
}

// Create response array
$response = [
    'success' => false,
    'message' => 'Invalid request',
    'logs' => []
];

// Handle GET requests (API-like endpoints)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    try {
        switch ($_GET['action']) {
            case 'get_logs':
                // Get filter parameters with proper sanitization
                $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                $role = isset($_GET['role']) ? trim($_GET['role']) : '';
                $module = isset($_GET['module']) ? trim($_GET['module']) : '';
                $action_type = isset($_GET['action_type']) ? trim($_GET['action_type']) : '';
                $start_date = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
                $end_date = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
                
                // Build the query
                $query = "SELECT 
                            atl.*,
                            CONCAT_WS(' ', cp.FirstName, cp.MiddleName, cp.LastName) as user_name,
                            ur.RoleName as user_role
                          FROM audit_trail atl
                          LEFT JOIN clinicpersonnel cp ON atl.user_id = cp.PersonnelID
                          LEFT JOIN userrole ur ON cp.RoleID = ur.RoleID
                          WHERE 1=1";
                
                $params = [];
                $types = '';
                
                // Add search filter
                if (!empty($search)) {
                    $query .= " AND (
                        CONCAT(cp.FirstName, ' ', cp.LastName) LIKE ?
                        OR cp.FirstName LIKE ?
                        OR cp.LastName LIKE ?
                        OR atl.action_type LIKE ?
                        OR atl.table_name LIKE ?
                        OR atl.record_id LIKE ?
                        OR atl.action_details LIKE ?
                        OR atl.user_id LIKE ?
                    )";
                    $searchTerm = "%$search%";
                    $params = array_merge($params, array_fill(0, 8, $searchTerm));
                    $types .= str_repeat('s', 8);
                }
                
                // Add role filter
                if (!empty($role)) {
                    $query .= " AND ur.RoleName = ?";
                    $params[] = $role;
                    $types .= 's';
                }
                
                // Add module filter
                if (!empty($module)) {
                    $query .= " AND atl.table_name = ?";
                    $params[] = $module;
                    $types .= 's';
                }
                
                // Add action type filter - FIXED
                if (!empty($action_type)) {
                    $query .= " AND atl.action_type = ?";
                    $params[] = $action_type;
                    $types .= 's';
                }
                
                // Add date filters - FIXED
                if (!empty($start_date)) {
                    $query .= " AND DATE(atl.created_at) >= ?";
                    $params[] = $start_date;
                    $types .= 's';
                }
                
                if (!empty($end_date)) {
                    $query .= " AND DATE(atl.created_at) <= ?";
                    $params[] = $end_date;
                    $types .= 's';
                }
                
                // Add sorting
                $query .= " ORDER BY atl.created_at DESC";
                
                // Get total count for pagination (without LIMIT)
                $countQuery = str_replace(
                    "SELECT atl.*, CONCAT_WS(' ', cp.FirstName, cp.MiddleName, cp.LastName) as user_name, ur.RoleName as user_role",
                    "SELECT COUNT(*) as total",
                    $query
                );
                
                // Remove ORDER BY from count query
                $countQuery = preg_replace("/ORDER BY.*$/i", "", $countQuery);
                
                // Execute count query
                if (!empty($params)) {
                    $countStmt = $con->prepare($countQuery);
                    $countStmt->bind_param($types, ...$params);
                    $countStmt->execute();
                    $countResult = $countStmt->get_result();
                    $totalRow = $countResult->fetch_assoc();
                    $total = $totalRow['total'];
                } else {
                    $total = $con->query("SELECT COUNT(*) as total FROM audit_trail")->fetch_assoc()['total'];
                }
                
                // Add pagination to main query
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $perPage = 50; // Default to match frontend
                $offset = ($page - 1) * $perPage;
                $query .= " LIMIT ? OFFSET ?";
                
                // Add pagination parameters
                $params[] = $perPage;
                $params[] = $offset;
                $types .= 'ii';
                
                // Prepare and execute the main query
                $stmt = $con->prepare($query);
                
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                
                $stmt->execute();
                $result = $stmt->get_result();
                
                // Format the logs
                $logs = [];
                while ($row = $result->fetch_assoc()) {
                    // Generate user initials
                    $initials = '';
                    if (!empty($row['user_name'])) {
                        $names = explode(' ', $row['user_name']);
                        $initials = strtoupper(substr($names[0], 0, 1));
                        if (count($names) > 1) {
                            $initials .= strtoupper(substr(end($names), 0, 1));
                        }
                    }
                    
                    $logs[] = [
                        'timestamp' => $row['created_at'],
                        'user_name' => $row['user_name'] ?? 'System',
                        'user_initials' => $initials,
                        'user_role' => $row['user_role'] ?? 'System',
                        'action_type' => ucfirst($row['action_type']),
                        'module' => $row['table_name'],
                        'record_id' => $row['record_id'],
                        'details' => $row['action_details']
                    ];
                }
                
                $response = [
                    'success' => true,
                    'logs' => $logs,
                    'pagination' => [
                        'total' => (int)$total,
                        'page' => $page,
                        'per_page' => $perPage,
                        'total_pages' => ceil($total / $perPage)
                    ]
                ];
                break;
                
            default:
                $response['message'] = 'Invalid action';
                http_response_code(400);
                break;
        }
        
    } catch (Exception $e) {
        error_log('Audit Log Error: ' . $e->getMessage());
        error_log('Stack Trace: ' . $e->getTraceAsString());
        
        http_response_code(500);
        $response = [
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage(),
            'error' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]
        ];
    }
}

// Handle POST requests (form submissions)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'export_excel':
                // Get filter values
                $search = $_POST['search'] ?? '';
                $role = $_POST['role'] ?? '';
                $module = $_POST['module'] ?? '';
                $action_type = $_POST['action_type'] ?? '';
                $start_date = $_POST['start_date'] ?? '';
                $end_date = $_POST['end_date'] ?? '';

                // Build the query
                $query = "SELECT 
                            atl.created_at as Timestamp,
                            CONCAT_WS(' ', cp.FirstName, cp.MiddleName, cp.LastName) as FullName,
                            ur.RoleName,
                            atl.table_name as Module,
                            atl.action_type as Action,
                            atl.action_details as Details
                          FROM audit_trail atl
                          LEFT JOIN clinicpersonnel cp ON atl.user_id = cp.PersonnelID
                          LEFT JOIN userrole ur ON cp.RoleID = ur.RoleID
                          WHERE 1=1";
                
                $params = [];
                $types = '';
                
                // Add search filter
                if (!empty($search)) {
                    $query .= " AND (
                        CONCAT_WS(' ', cp.FirstName, cp.MiddleName, cp.LastName) LIKE ?
                        OR atl.action_type LIKE ?
                        OR atl.table_name LIKE ?
                        OR atl.action_details LIKE ?
                    )";
                    $searchTerm = "%$search%";
                    $params = array_merge($params, array_fill(0, 4, $searchTerm));
                    $types .= str_repeat('s', 4);
                }
                
                // Add role filter
                if (!empty($role)) {
                    $query .= " AND ur.RoleName = ?";
                    $params[] = $role;
                    $types .= 's';
                }
                
                // Add module filter
                if (!empty($module)) {
                    $query .= " AND atl.table_name = ?";
                    $params[] = $module;
                    $types .= 's';
                }
                
                // Add action type filter
                if (!empty($action_type)) {
                    $query .= " AND atl.action_type = ?";
                    $params[] = $action_type;
                    $types .= 's';
                }
                
                // Add date filters
                if (!empty($start_date)) {
                    $query .= " AND DATE(atl.created_at) >= ?";
                    $params[] = $start_date;
                    $types .= 's';
                }
                
                if (!empty($end_date)) {
                    $query .= " AND DATE(atl.created_at) <= ?";
                    $params[] = $end_date;
                    $types .= 's';
                }
                
                // Add sorting
                $query .= " ORDER BY atl.created_at DESC";
                
                // Prepare and execute the query
                $stmt = $con->prepare($query);
                
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                
                $stmt->execute();
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);

                // Format data for Excel
                $formattedData = array_map(function($row) {
                    return [
                        'Date & Time' => $row['Timestamp'],
                        'Name' => $row['FullName'] ?: 'System',
                        'Role' => $row['RoleName'] ?: 'System',
                        'Module' => ucwords(str_replace('_', ' ', $row['Module'])),
                        'Action' => ucfirst($row['Action']),
                        'Details' => $row['Details']
                    ];
                }, $data);

                // Return JSON response
                $response = [
                    'success' => true,
                    'data' => $formattedData
                ];
                break;

            default:
                http_response_code(400);
                $response = [
                    'success' => false,
                    'message' => 'Invalid action specified'
                ];
                break;
        }
    } catch (Exception $e) {
        error_log('Export Excel Error: ' . $e->getMessage());
        http_response_code(500);
        $response = [
            'success' => false,
            'message' => 'Failed to process request: ' . $e->getMessage()
        ];
    }
}

// Ensure no output before this
if (ob_get_level() > 0) {
    ob_clean();
}

// Set JSON header and output
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
exit;
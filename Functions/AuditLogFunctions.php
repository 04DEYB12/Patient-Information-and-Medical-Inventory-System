<?php
header('Content-Type: application/json');

// Include database connection
require_once __DIR__ . '/../Landing Repository/Connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an administrator
if (!isset($_SESSION['User_ID']) || $_SESSION['role'] != 'Administrator') {
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

try {
    // Get the action from the request
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'get_logs':
            // Get filter parameters
            $search = $_GET['search'] ?? '';
            $role = $_GET['role'] ?? '';
            $module = $_GET['module'] ?? '';
            
            // Build the query
            $query = "SELECT 
                        atl.*,
                        CONCAT(cp.FirstName, ' ', cp.MiddleName, ' ', cp.LastName) as user_name,
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
                    OR atl.action_type LIKE ?
                    OR atl.table_name LIKE ?
                    OR atl.record_id LIKE ?
                    OR atl.action_details LIKE ?
                )";
                $searchTerm = "%$search%";
                $params = array_merge($params, array_fill(0, 5, $searchTerm));
                $types .= str_repeat('s', 5);
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
            
            // Add sorting
            $query .= " ORDER BY atl.created_at DESC";
            
            // Add pagination
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 20;
            $offset = ($page - 1) * $perPage;
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;
            $types .= 'ii';
            
            // Prepare and execute the query
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
            
            // Get total count for pagination
            $countQuery = "SELECT COUNT(*) as total FROM audit_trail atl";
            if (!empty($search) || !empty($role) || !empty($module)) {
                $countQuery = str_replace("SELECT * FROM audit_trail", 
                                       "SELECT COUNT(*) as total FROM audit_trail atl", 
                                       $query);
                $countQuery = preg_replace("/ORDER BY.*$|LIMIT.*$/i", "", $countQuery);
                
                $countStmt = $con->prepare($countQuery);
                if (!empty($params)) {
                    // Remove the limit and offset params for the count query
                    $countParams = array_slice($params, 0, -2);
                    $countTypes = substr($types, 0, -2);
                    if (!empty($countParams)) {
                        $countStmt->bind_param($countTypes, ...$countParams);
                    }
                }
                $countStmt->execute();
                $total = $countStmt->get_result()->fetch_assoc()['total'];
            } else {
                $total = $con->query("SELECT COUNT(*) as total FROM audit_trail")->fetch_assoc()['total'];
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
    http_response_code(500);
    $response = [
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ];
}

// Return the response as JSON
echo json_encode($response);
?>

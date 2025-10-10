<?php
include '../Landing Repository/Connection.php';

// Set the correct timezone (Asia/Manila is correct for the location)
date_default_timezone_set('Asia/Manila');

// 1. FIX: Set default filter to 'all' to match JavaScript initial call.
// This ensures that when the page first loads, it tries to fetch all records.
$filter = $_GET['filter'] ?? 'all'; 
$date = $_GET['date'] ?? '';

$query = "
    SELECT m.name, SUM(u.quantity_used) AS total_used
    FROM medicine_usage u
    JOIN medicine m ON m.med_id = u.med_id
";

$conditions = [];

switch ($filter) {
    case 'today':
        $conditions[] = "DATE(u.usage_date) = CURDATE()";
        break;

    case 'day':
        // Use the provided date or default to the current date
        $target_date = !empty($date) ? $con->real_escape_string($date) : date('Y-m-d');
        $conditions[] = "DATE(u.usage_date) = '$target_date'";
        break;

    case 'week':
        $week_mode = 1;
        $target_date = !empty($date) ? $con->real_escape_string($date) : date('Y-m-d');
        $conditions[] = "YEARWEEK(u.usage_date, $week_mode) = YEARWEEK('$target_date', $week_mode)";
        break;

    case 'month':
        $target_date = !empty($date) ? $con->real_escape_string($date) : date('Y-m-d');
        $conditions[] = "YEAR(u.usage_date) = YEAR('$target_date') AND MONTH(u.usage_date) = MONTH('$target_date')";
        break;

    case 'year':
        // Calculate the target year based on the provided date or current year (2025)
        $target_year = !empty($date) ? date('Y', strtotime($con->real_escape_string($date))) : date('Y');
        $conditions[] = "YEAR(u.usage_date) = $target_year";
        break;

    case 'all':
        // No condition, fetch all records
        break;
}

// Add WHERE only if conditions exist
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " GROUP BY m.name ORDER BY total_used DESC LIMIT 10";

// --- CRITICAL DEBUGGING STEP ---
// Check the connection first
if ($con->connect_error) {
    header('Content-Type: application/json');
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => "Database Connection Failed: " . $con->connect_error]);
    exit;
}

$result = $con->query($query);

if (!$result) {
    // 2. CRITICAL FIX: If the query fails, return an error message to the browser console.
    header('Content-Type: application/json');
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => "SQL Query Failed", 'query' => $query, 'sql_error' => $con->error]);
    exit;
}

$labels = [];
$values = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['name'];
        $values[] = (int)$row['total_used'];
    }
}

header('Content-Type: application/json');
echo json_encode(['labels' => $labels, 'values' => $values]);
exit;
?>
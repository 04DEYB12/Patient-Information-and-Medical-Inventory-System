<?php
session_start();
// The connection script should define $con (mysqli object)
include '../Landing Repository/Connection.php';

// --- Global Setup and Authentication ---

// Check if $con is available and a valid mysqli object
if (!isset($con) || $con->connect_error) {
    // Refactored to use a simple error message
    die("Database connection failed: " . ($con->connect_error ?? 'Connection object not set.'));
}

// Redirect if user not logged in
if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('Please login first!'); window.location.href = 'Loginpage.php';</script>";
    exit();
}

// Get required session data
$user_id = $_SESSION['User_ID'];
$admin_name = 'Admin'; 
$disposed_by_user = htmlspecialchars($_SESSION['username'] ?? $admin_name); 

// Include external queries, assuming they don't contain handler functions
require_once '../Functions/Queries.php'; 


// --- Audit Trail Logging Function ---

function log_audit_trail($con, $user_id, $action_type, $table_name, $record_id, $action_details) {
    $stmt = $con->prepare("INSERT INTO audit_trail (user_id, action_type, table_name, record_id, action_details) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        error_log("Audit trail prepare failed: " . $con->error);
        return false;
    }
    
    $record_id_str = strval($record_id); 
    
    $stmt->bind_param("sssss", $user_id, $action_type, $table_name, $record_id_str, $action_details);
    
    if (!$stmt->execute()) {
        error_log("Audit trail execute failed: " . $stmt->error);
        return false;
    }
    $stmt->close();
    return true;
}

// --- Enhanced response handler with toast notification data
function send_response($is_ajax, $status, $message, $redirect_page = 'Inventory.php') {
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $status, 
            'message' => $message,
            'toast' => true // For frontend toast notification
        ]);
    } else {
        echo "<script>alert('" . addslashes($message) . "'); window.location.href = '{$redirect_page}';</script>";
    }
    exit;
}

// Determine if the request is AJAX
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';


// --- New Dispose Functionality: Set Quantity to 0 and Expiry to NULL ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dispose_full'])) {
    $med_id = filter_var($_POST['med_id'], FILTER_VALIDATE_INT);
    $reason_status = trim($_POST['dispose_reason'] ?? 'expired');
    $reason_details = trim($_POST['dispose_details'] ?? '');

    if ($med_id === false) {
        send_response($is_ajax, 'error', 'Invalid medicine ID.');
    }

    $con->begin_transaction();

    try {
        $stmt_select = $con->prepare("SELECT quantity, name, description FROM medicine WHERE med_id = ? FOR UPDATE");
        if (!$stmt_select) {
            throw new Exception("Prepare SELECT failed: " . $con->error);
        }
        $stmt_select->bind_param("i", $med_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $medicine = $result->fetch_assoc();
        $stmt_select->close();

        if (!$medicine) {
            throw new Exception("Medicine with ID $med_id not found.");
        }

        $update_stmt = $con->prepare("UPDATE medicine SET quantity = 0, expiry_date = NULL WHERE med_id = ?");
        if (!$update_stmt) {
            throw new Exception("Prepare UPDATE failed: " . $con->error);
        }
        $update_stmt->bind_param("i", $med_id);
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update medicine: " . $update_stmt->error);
        }
        $update_stmt->close();

        // Record in disposal log
        $disposal_stmt = $con->prepare("
            INSERT INTO disposed_medicines 
            (med_id, name, status, quantity, description, reason, disposed_by, disposed_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        if (!$disposal_stmt) {
            throw new Exception("Prepare INSERT failed: " . $con->error);
        }
        $disposal_stmt->bind_param(
            "ississs",
            $med_id,
            $medicine['name'],
            $reason_status,
            $medicine['quantity'],
            $medicine['description'],
            $reason_details,
            $disposed_by_user
        );
        if (!$disposal_stmt->execute()) {
            throw new Exception("Failed to record disposal: " . $disposal_stmt->error);
        }
        $disposal_stmt->close();

        $details = "Disposed ALL of {$medicine['name']} (Qty: {$medicine['quantity']}) | Reason: {$reason_status}";
        log_audit_trail($con, $user_id, 'DISPOSE', 'medicine', $med_id, $details);

        if (!$con->commit()) {
            throw new Exception("Transaction Commit Failed: " . $con->error);
        }
        send_response($is_ajax, 'success', 'Medicine disposed successfully!');

    } catch (Exception $e) {
        $con->rollback();
        send_response($is_ajax, 'error', 'Disposal failed! Reason: ' . $e->getMessage());
    }
}


// --- Handle Add Medicine Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_medicine_submit'])) {
    $med_name = trim($_POST['med_name']);
    $med_purposes = isset($_POST['med_purposes']) && is_array($_POST['med_purposes']) ? implode('; ', $_POST['med_purposes']) : '';
    $med_severity = trim($_POST['med_severity'] ?? 'general');
    $med_quantity = filter_var($_POST['med_quantity'], FILTER_VALIDATE_INT);
    $med_type = trim($_POST['med_type']);
    $med_expiry = trim($_POST['med_expiry']);
    $added_by = $admin_name;

    if ($med_quantity === false || $med_quantity <= 0) {
        send_response($is_ajax, 'error', 'Quantity must be a valid number greater than zero.');
    }

    $med_description = $med_purposes . " [Severity: " . $med_severity . "]";

    $stmt = $con->prepare("INSERT INTO medicine (name, description, quantity, type, expiry_date, added_by) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        send_response($is_ajax, 'error', 'Prepare statement failed: ' . $con->error);
    }
    $stmt->bind_param("ssisss", $med_name, $med_description, $med_quantity, $med_type, $med_expiry, $added_by);

    try {
        if ($stmt->execute()) {
            $new_med_id = $con->insert_id;
            
            $details = "Added new medicine: " . $med_name . " (Qty: " . $med_quantity . ", Exp: " . $med_expiry . ", Purposes: " . $med_purposes . ")";
            log_audit_trail($con, $user_id, 'CREATE', 'medicine', $new_med_id, $details);
            
            send_response($is_ajax, 'success', 'Medicine added successfully!');
        } else {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        send_response($is_ajax, 'error', 'Error: ' . $e->getMessage());
    } finally {
        $stmt->close();
    }
}

// --- Handle Deduct Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deduct_items'])) {
    
    if (empty($_POST['deduct_items'])) {
        send_response($is_ajax, 'error', 'Please select at least one medicine to deduct.');
    }

    $con->begin_transaction();

    try {
        foreach ($_POST['deduct_items'] as $med_id => $quantity_to_deduct) {
            $med_id = filter_var($med_id, FILTER_VALIDATE_INT);
            $quantity_to_deduct = filter_var($quantity_to_deduct, FILTER_VALIDATE_INT);

            if ($med_id === false || $quantity_to_deduct === false || $quantity_to_deduct <= 0) {
                continue;
            }

            $stmt_select = $con->prepare("SELECT quantity, name, expiry_date FROM medicine WHERE med_id = ? FOR UPDATE");
            $stmt_select->bind_param("i", $med_id);
            $stmt_select->execute();
            $result = $stmt_select->get_result();
            $medicine = $result->fetch_assoc();
            $stmt_select->close();

            if (!$medicine) {
                throw new Exception("Medicine with ID $med_id not found.");
            }

            $expiry_date = new DateTime($medicine['expiry_date']);
            $today = new DateTime();
            if ($expiry_date < $today) {
                throw new Exception("CANNOT DEDUCT: " . htmlspecialchars($medicine['name']) . " is EXPIRED. Please dispose it instead. Use the Dispose button.");
            }

            $current_stock = (int)$medicine['quantity'];
            if ($current_stock < $quantity_to_deduct) {
                throw new Exception("Not enough stock for " . htmlspecialchars($medicine['name']) . ". Available: " . $current_stock);
            }

            $new_stock = $current_stock - $quantity_to_deduct;

            $update_stmt = $con->prepare("UPDATE medicine SET quantity = ? WHERE med_id = ?");
            $update_stmt->bind_param("ii", $new_stock, $med_id);
            $update_stmt->execute();
            $update_stmt->close();
            
            $usage_stmt = $con->prepare("INSERT INTO medicine_usage (med_id, quantity_used) VALUES (?, ?)");
            $usage_stmt->bind_param("ii", $med_id, $quantity_to_deduct);
            $usage_stmt->execute();
            $usage_stmt->close();
            
            $details = "Deducted " . $quantity_to_deduct . " of " . $medicine['name'] . ". New stock: " . $new_stock;
            log_audit_trail($con, $user_id, 'DEDUCT', 'medicine', $med_id, $details);
        }
        
        $con->commit();
        send_response($is_ajax, 'success', 'Deduction successful!');

    } catch (Exception $e) {
        $con->rollback();
        send_response($is_ajax, 'error', 'Deduction error: ' . $e->getMessage());
    }
}


// --- Enhanced Restock: Save as new ID if expired or 0 quantity, otherwise update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restock_items'])) {

    if (empty($_POST['restock_items'])) {
        send_response($is_ajax, 'error', 'Please select at least one medicine to restock.');
    }

    $con->begin_transaction();

    try {
        foreach ($_POST['restock_items'] as $med_id => $details) {
            $med_id = filter_var($med_id, FILTER_VALIDATE_INT);
            $quantity_to_add = filter_var($details['quantity'], FILTER_VALIDATE_INT);
            $new_expiry_date = trim($details['expiry_date']);

            if ($med_id === false || $quantity_to_add === false || $quantity_to_add <= 0) {
                continue;
            }

            // Validate new expiry date
            $newExp = new DateTime($new_expiry_date);
            $today = new DateTime('today');

            if ($newExp < $today) {
                throw new Exception("Cannot restock with expired date ($new_expiry_date). Please enter a valid future date.");
            }

            // Fetch existing medicine (old batch)
            $stmt = $con->prepare("SELECT name, description, type, added_by, quantity, expiry_date FROM medicine WHERE med_id = ?");
            $stmt->bind_param("i", $med_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $medicine = $result->fetch_assoc();
            $stmt->close();

            if (!$medicine) {
                throw new Exception("Medicine with ID $med_id not found.");
            }

            $old_expiry = new DateTime($medicine['expiry_date']);
            $old_quantity = (int)$medicine['quantity'];
            $is_expired = ($old_expiry < $today);
            $is_zero_quantity = ($old_quantity == 0);

            if ($is_expired || $is_zero_quantity) {
                // ALWAYS create a new batch if new expiry date is different OR if old batch is expired or zero quantity
                $insert_stmt = $con->prepare("
                    INSERT INTO medicine (name, description, quantity, type, expiry_date, added_by)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                $insert_stmt->bind_param(
                    "ssisss",
                    $medicine['name'],
                    $medicine['description'],
                    $quantity_to_add,
                    $medicine['type'],
                    $new_expiry_date,
                    $medicine['added_by']
                );

                $insert_stmt->execute();
                $new_med_id = $con->insert_id;
                $insert_stmt->close();

                $action = ($is_expired ? "expired" : "zero quantity");
                $details_log = "Restocked NEW batch of {$medicine['name']} ({$action} old batch) (Qty: {$quantity_to_add}, Exp: {$new_expiry_date})";
                log_audit_trail($con, $user_id, 'CREATE', 'medicine', $new_med_id, $details_log);
            } else {
                // Update existing batch
                $update_stmt = $con->prepare("UPDATE medicine SET quantity = quantity + ?, expiry_date = ? WHERE med_id = ?");
                $update_stmt->bind_param("isi", $quantity_to_add, $new_expiry_date, $med_id);
                $update_stmt->execute();
                $update_stmt->close();

                $new_qty = $old_quantity + $quantity_to_add;
                $details_log = "Updated existing batch of {$medicine['name']} (Qty: {$quantity_to_add}, New Total: {$new_qty}, Exp: {$new_expiry_date})";
                log_audit_trail($con, $user_id, 'UPDATE', 'medicine', $med_id, $details_log);
            }
        }

        $con->commit();
        send_response($is_ajax, 'success', 'Restock successful!');

    } catch (Exception $e) {
        $con->rollback();
        send_response($is_ajax, 'error', 'Restock error: ' . $e->getMessage());
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['single_dispose'])) {
    $med_id = filter_var($_POST['med_id'], FILTER_VALIDATE_INT);
    $quantity_to_dispose = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
    $reason_status = trim($_POST['type']);
    $reason_details = trim($_POST['reason'] ?? '');
    $disposed_by = $_POST['disposed_by'] ?? 'System';

    if ($med_id === false || $quantity_to_dispose === false || $quantity_to_dispose <= 0) {
        send_response($is_ajax, 'error', 'Invalid medicine disposal request.');
    }

    $con->begin_transaction();

    try {
        $stmt_select = $con->prepare("SELECT quantity, name, description, expiry_date FROM medicine WHERE med_id = ? FOR UPDATE");
        if (!$stmt_select) {
            throw new Exception("Prepare SELECT failed: " . $con->error);
        }
        $stmt_select->bind_param("i", $med_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $medicine = $result->fetch_assoc();
        $stmt_select->close();

        if (!$medicine) {
            throw new Exception("Medicine with ID $med_id not found.");
        }

        $current_stock = (int)$medicine['quantity'];
        if ($current_stock < $quantity_to_dispose) {
            throw new Exception("Not enough stock for disposal. Available: " . $current_stock);
        }

        $new_stock = $current_stock - $quantity_to_dispose;

        $update_stmt = $con->prepare("UPDATE medicine SET quantity = ? WHERE med_id = ?");
        if (!$update_stmt) {
            throw new Exception("Prepare UPDATE failed: " . $con->error);
        }
        $update_stmt->bind_param("ii", $new_stock, $med_id);
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update medicine stock: " . $update_stmt->error);
        }
        $update_stmt->close();

        $disposal_stmt = $con->prepare("
            INSERT INTO disposed_medicines 
            (med_id, name, status, quantity, description, reason, disposed_by, disposed_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        if (!$disposal_stmt) {
            throw new Exception("Prepare INSERT failed: " . $con->error);
        }
        $disposal_stmt->bind_param(
            "ississs",
            $med_id,
            $medicine['name'],
            $reason_status,
            $quantity_to_dispose,
            $medicine['description'],
            $reason_details,
            $disposed_by
        );
        if (!$disposal_stmt->execute()) {
            throw new Exception("Failed to record disposal: " . $disposal_stmt->error);
        }
        $disposal_stmt->close();

        $details = "Disposed $quantity_to_dispose of {$medicine['name']} | Reason: {$reason_status}";
        log_audit_trail($con, $user_id, 'DISPOSE', 'medicine', $med_id, $details);

        if (!$con->commit()) {
            throw new Exception("Transaction Commit Failed: " . $con->error);
        }
        send_response($is_ajax, 'success', 'Medicine disposed successfully!');

    } catch (Exception $e) {
        $con->rollback();
        send_response($is_ajax, 'error', 'Disposal failed! Reason: ' . $e->getMessage());
    }
}

// --- Multiple Dispose Functionality ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['multiple_dispose'])) {

    if (empty($_POST['dispose_items'])) {
        send_response($is_ajax, 'error', 'Please select at least one medicine to dispose.');
        exit;
    }

    $con->begin_transaction();

    try {
        $disposedCount = 0;

        foreach ($_POST['dispose_items'] as $med_id => $dispose_data) {

            $med_id = filter_var($med_id, FILTER_VALIDATE_INT);
            $quantity_to_dispose = filter_var($dispose_data['quantity'], FILTER_VALIDATE_INT);

            // ❌ Skip invalid entries
            if ($med_id === false || $quantity_to_dispose === false || $quantity_to_dispose <= 0) {
                continue;
            }

            // 🔒 Lock row
            $stmt_select = $con->prepare(
                "SELECT quantity, name, description FROM medicine WHERE med_id = ? FOR UPDATE"
            );
            $stmt_select->bind_param("i", $med_id);
            $stmt_select->execute();
            $result = $stmt_select->get_result();
            $medicine = $result->fetch_assoc();
            $stmt_select->close();

            if (!$medicine) {
                throw new Exception("Medicine with ID {$med_id} not found.");
            }

            $current_stock = (int)$medicine['quantity'];

            if ($current_stock < $quantity_to_dispose) {
                throw new Exception("Not enough stock for {$medicine['name']}.");
            }

            // ➖ Update stock
            $new_stock = $current_stock - $quantity_to_dispose;

            $update_stmt = $con->prepare(
                "UPDATE medicine SET quantity = ? WHERE med_id = ?"
            );
            $update_stmt->bind_param("ii", $new_stock, $med_id);

            if (!$update_stmt->execute()) {
                throw new Exception($update_stmt->error);
            }
            $update_stmt->close();

            // ➕ Insert disposal record
            $status = 'expired';
            $reason = 'Bulk disposal';

            $disposal_stmt = $con->prepare("
                INSERT INTO disposed_medicines
                (med_id, name, status, quantity, description, reason, disposed_by, disposed_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");

            $disposal_stmt->bind_param(
                "ississs",
                $med_id,
                $medicine['name'],
                $status,
                $quantity_to_dispose,
                $medicine['description'],
                $reason,
                $disposed_by_user
            );

            if (!$disposal_stmt->execute()) {
                throw new Exception($disposal_stmt->error);
            }
            $disposal_stmt->close();

            // 📝 Audit log
            $details = "Disposed {$quantity_to_dispose} of {$medicine['name']}";
            log_audit_trail($con, $user_id, 'DISPOSE', 'medicine', $med_id, $details);

            $disposedCount++;
        }

        // ❌ Nothing disposed
        if ($disposedCount === 0) {
            throw new Exception('No valid medicines were disposed.');
        }

        $con->commit();
        send_response($is_ajax, 'success', 'Bulk disposal successful!');

    } catch (Exception $e) {
        $con->rollback();
        send_response($is_ajax, 'error', 'Disposal error: ' . $e->getMessage());
    }

    exit;
}

// --- Edit Medicine: Name, Type, Description (with purposes/severity) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_medicine'])) {
    $med_id = filter_var($_POST['med_id'], FILTER_VALIDATE_INT);
    $med_name = trim($_POST['med_name']);
    $med_type = trim($_POST['med_type']);
    $med_purposes = isset($_POST['med_purposes']) && is_array($_POST['med_purposes']) ? implode('; ', $_POST['med_purposes']) : '';
    $med_severity = trim($_POST['med_severity'] ?? 'general');

    if ($med_id === false) {
        send_response($is_ajax, 'error', 'Invalid medicine ID.');
    }

    $med_description = $med_purposes . " [Severity: " . $med_severity . "]";

    $stmt = $con->prepare("UPDATE medicine SET name = ?, type = ?, description = ? WHERE med_id = ?");
    if (!$stmt) {
        send_response($is_ajax, 'error', 'Prepare statement failed: ' . $con->error);
    }
    $stmt->bind_param("sssi", $med_name, $med_type, $med_description, $med_id);

    try {
        if ($stmt->execute()) {
            $details = "Updated medicine: $med_name (Type: $med_type)";
            log_audit_trail($con, $user_id, 'UPDATE', 'medicine', $med_id, $details);
            send_response($is_ajax, 'success', 'Medicine updated successfully!');
        } else {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        send_response($is_ajax, 'error', 'Error: ' . $e->getMessage());
    } finally {
        $stmt->close();
    }
}

// --- Get usage data for chart ---
if (isset($_GET['action']) && $_GET['action'] == 'get_usage_data') {
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-t');
    
    $query = "SELECT DATE(usage_date) as date, SUM(quantity_used) as total_used 
              FROM medicine_usage 
              WHERE DATE(usage_date) BETWEEN ? AND ? 
              GROUP BY DATE(usage_date) 
              ORDER BY date";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [
        'labels' => [],
        'datasets' => [
            [
                'label' => 'Medicine Usage',
                'data' => [],
                'borderColor' => '#40916C',
                'backgroundColor' => 'rgba(64, 145, 108, 0.1)',
                'fill' => true
            ]
        ]
    ];
    
    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['date'];
        $data['datasets'][0]['data'][] = $row['total_used'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}


// --- Dashboard Data Queries ---
$total_meds_query = $con->query("SELECT SUM(quantity) AS total_count FROM medicine WHERE quantity > 0");
$total_meds_count = $total_meds_query->fetch_assoc()['total_count'] ?? 0;

$low_stock_query = $con->query("SELECT COUNT(*) AS low_stock_count FROM medicine WHERE quantity BETWEEN 1 AND 20 AND quantity > 0");
$low_stock_count = $low_stock_query->fetch_assoc()['low_stock_count'] ?? 0;

$near_expiry_query = $con->query("SELECT COUNT(*) AS near_expiry_count FROM medicine WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND quantity > 0");
$near_expiry_count = $near_expiry_query->fetch_assoc()['near_expiry_count'] ?? 0;

$expired_query = $con->query("SELECT COUNT(*) AS expired_count FROM medicine WHERE expiry_date < CURDATE() AND quantity > 0");
$expired_count = $expired_query->fetch_assoc()['expired_count'] ?? 0;

$monthly_usage_query = $con->query("SELECT SUM(quantity_used) AS total_used FROM medicine_usage WHERE MONTH(usage_date) = MONTH(CURDATE()) AND YEAR(usage_date) = YEAR(CURDATE())");
$monthly_usage_count = $monthly_usage_query->fetch_assoc()['total_used'] ?? 0;

$items_per_page = isset($_GET['items_per_page']) ? max(1, min((int)$_GET['items_per_page'], 100)) : 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Calculate total pages for all medicines with current filter
$search_query = "";
$params = [];
$param_types = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $search_query = "WHERE (name LIKE ? OR description LIKE ?)";
    $params[] = $search;
    $params[] = $search;
    $param_types .= "ss";
}

if (isset($_GET['stock_filter']) && !empty($_GET['stock_filter'])) {
    switch ($_GET['stock_filter']) {
        case 'low':
            $search_query .= (empty($search_query) ? "WHERE" : " AND") . " quantity BETWEEN 1 AND 20";
            break;
        case 'high':
            $search_query .= (empty($search_query) ? "WHERE" : " AND") . " quantity > 20";
            break;
    }
}

if (isset($_GET['expiry_filter']) && !empty($_GET['expiry_filter'])) {
    switch ($_GET['expiry_filter']) {
        case 'expired':
            $search_query .= (empty($search_query) ? "WHERE" : " AND") . " expiry_date < CURDATE()";
            break;
        case 'near_expiry':
            $search_query .= (empty($search_query) ? "WHERE" : " AND") . " expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
            break;
        case 'expiry_1m':
            $search_query .= (empty($search_query) ? "WHERE" : " AND") . " expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";
            break;
        case 'expiry_2m':
            $search_query .= (empty($search_query) ? "WHERE" : " AND") . " expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 MONTH)";
            break;
        case 'expiry_3m':
            $search_query .= (empty($search_query) ? "WHERE" : " AND") . " expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)";
            break;
    }
}

if (isset($_GET['type_filter']) && !empty($_GET['type_filter'])) {
    $search_query .= (empty($search_query) ? "WHERE" : " AND") . " type = ?";
    $params[] = $_GET['type_filter'];
    $param_types .= "s";
}

$quantity_filter = (empty($search_query) ? "WHERE" : " AND") . " quantity > 0";

// Get total count for pagination
$count_query = "SELECT COUNT(DISTINCT name) AS total FROM medicine $search_query $quantity_filter";
$count_stmt = $con->prepare($count_query);
if (!empty($params)) {
    $bind_params = array_merge([$param_types], $params);
    $ref = [];
    foreach($bind_params as $key => $value) {
        $ref[$key] = &$bind_params[$key];
    }
    call_user_func_array([$count_stmt, 'bind_param'], $ref);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_medicines = $count_result->fetch_assoc()['total'] ?? 0;
$total_pages = ceil($total_medicines / $items_per_page);

// --- Handle AJAX requests for medicine list updates ---
if (isset($_GET['action']) && $_GET['action'] == 'fetch_medicines') {
    header('Content-Type: text/html');
    
    $sort_query = "ORDER BY added_at DESC";
    
    if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
            case 'oldest_first': $sort_query = "ORDER BY expiry_date ASC"; break;
            case 'newest_first': $sort_query = "ORDER BY expiry_date DESC"; break;
        }
    }
    
    $medicines_query = "SELECT * FROM medicine $search_query $quantity_filter $sort_query LIMIT $items_per_page OFFSET $offset";
    $stmt = $con->prepare($medicines_query);
    
    if (!empty($params)) {
        $bind_params = array_merge([$param_types], $params);
        $ref = [];
        foreach($bind_params as $key => $value) {
            $ref[$key] = &$bind_params[$key];
        }
        call_user_func_array([$stmt, 'bind_param'], $ref);
    }

    $stmt->execute();
    $medicines_result = $stmt->get_result();

    
    if ($medicines_result->num_rows > 0) {
        $medicines_grouped = [];
        while ($row = $medicines_result->fetch_assoc()) {
            $name = $row['name'];
            if (!isset($medicines_grouped[$name])) {
                $medicines_grouped[$name] = [];
            }
            $medicines_grouped[$name][] = $row;
        }
        
        foreach ($medicines_grouped as $med_name => $med_items) {
            // Sort batches: non-zero first by expiry date, then zero quantity at the end
            usort($med_items, function($a, $b) {
                // If one has 0 quantity and the other doesn't, the non-zero comes first
                if ((int)$a['quantity'] == 0 && (int)$b['quantity'] != 0) return 1;
                if ((int)$a['quantity'] != 0 && (int)$b['quantity'] == 0) return -1;
                
                // Both non-zero or both zero: sort by expiry date
                return strtotime($a['expiry_date']) - strtotime($b['expiry_date']);
            });

            $total_stock = 0;
            $has_expired = false;
            $has_near_expiry = false;
            $status_text = 'Healthy Stock';
            $card_highlight_class = 'default-highlight';
            
            foreach ($med_items as $item) {
                $total_stock += $item['quantity'];
                $expiry_date = new DateTime($item['expiry_date']);
                $today = new DateTime();
                $days_until_expiry = $today->diff($expiry_date)->days;
                
                if ($expiry_date < $today) {
                    $has_expired = true;
                } elseif ($days_until_expiry <= 30) {
                    $has_near_expiry = true;
                }
            }
            
            $is_low_stock = $total_stock < 15;
            
            if ($has_expired) {
                $status_text = 'Expired';
                $card_highlight_class = 'expired-highlight';
            } elseif ($has_near_expiry) {
                $status_text = 'Near Expiry';
                $card_highlight_class = 'near-expiry-highlight';
            } elseif ($is_low_stock) {
                $status_text = 'Low Stock';
                $card_highlight_class = 'low-stock-highlight';
            } else {
                $status_text = 'Healthy Stock';
                $card_highlight_class = 'default-highlight';
            }
            
            $stock_class = $total_stock <= 10 ? 'text-red-600' : ($total_stock < 15 ? 'text-yellow-600' : 'text-green-600');
            ?>
            <div class="medicine-card-group <?= $card_highlight_class ?> relative transition-transform hover:scale-101 mt-4 border-2 border-gray-600 rounded-lg min-h-[70px]">
             
                <!-- Enhanced Grouped Header -->
                <div class="medicine-header-grouped flex justify-between items-center p-4 cursor-pointer hover:bg-white/50 transition rounded-t-lg" onclick="toggleMedicineGroup(event)">
                    <div class="flex items-center gap-3 flex-1">
                        <!-- Enhanced status badge with icon -->
                        <div class="text-xs font-normal px-3 py-1.5 rounded-full flex items-center gap-1
                            <?php
                                if ($status_text === 'Expired') echo 'bg-red-600 text-white';
                                elseif ($status_text === 'Near Expiry') echo 'bg-orange-600 text-white';
                                elseif ($status_text === 'Low Stock') echo 'bg-yellow-600 text-white';
                                else echo 'bg-green-600 text-white';
                            ?>">
                            <?php
                                if ($status_text === 'Expired') echo '<i class="bx bx-x-circle"></i>';
                                elseif ($status_text === 'Near Expiry') echo '<i class="bx bx-time-five"></i>';
                                elseif ($status_text === 'Low Stock') echo '<i class="bx bx-down-arrow-alt"></i>';
                                else echo '<i class="bx bx-check-circle"></i>';
                            ?>
                            <?= $status_text ?>
                        </div>
                        <i class='bx bx-chevron-right transition-transform'></i>
                        <div class="medicine-name font-semibold text-lg text-gray-800 flex items-center gap-2">
                            <i class='bx bx-capsule text-blue-500'></i>
                            <?= htmlspecialchars($med_name) ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="stock-level font-semibold text-sm <?= $stock_class ?> flex items-center gap-1">
                            <i class='bx bx-package'></i>
                            Total Stock: <?= htmlspecialchars($total_stock) ?>
                        </div>
                        <div class="batch-count text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                            <?= count($med_items) ?> batch<?= count($med_items) > 1 ? 'es' : '' ?>
                        </div>
                    </div>
                </div>

                <!-- Expanded Details (Hidden by Default) -->
                <div class="medicine-details hidden bg-white/30 p-4 border-t border-gray-200 w-full space-y-3">
                    <!-- Individual batch details -->
                    <?php foreach ($med_items as $index => $item):
                        $exp_date = new DateTime($item['expiry_date']);
                        $today = new DateTime();
                        $is_item_expired = $exp_date < $today;
                        $item_status = $is_item_expired ? 'EXPIRED' : 'ACTIVE';
                        $item_status_color = $is_item_expired ? 'text-red-600 font-bold' : 'text-green-600';
                        $formatted_expiry = $exp_date->format('F d, Y');
                        $has_restock_button = $item['quantity'] < 20;
                        $days_diff = $today->diff($exp_date)->days;
                        $expiry_class = $is_item_expired ? 'bg-red-50 border-red-200' : ($days_diff <= 30 ? 'bg-orange-50 border-orange-200' : 'bg-green-50 border-green-200');
                    ?>
                        <div class="batch-item border-2 p-4 rounded-lg <?= $expiry_class ?> <?= $index > 0 ? 'pt-3 border-gray-200' : '' ?>">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700 flex items-center gap-1">
                                    <i class='bx bx-layer'></i>
                                    Medicine Batch <?= $index + 1 ?> (ID: <?= $item['med_id'] ?>)
                                </span>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $item_status_color ?>">
                                    <?= $item_status ?>
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <p class="flex justify-between text-sm"><strong class="flex items-center gap-1"><i class='bx bx-package'></i> Quantity:</strong> <?= htmlspecialchars($item['quantity']) ?></p>
                                <p class="flex justify-between text-sm"><strong class="flex items-center gap-1"><i class='bx bx-cube'></i> Type:</strong> <?= htmlspecialchars(ucfirst($item['type'])) ?></p>
                                <p class="flex justify-between text-sm"><strong class="flex items-center gap-1"><i class='bx bx-calendar'></i> Expiry:</strong> <?= $formatted_expiry ?></p>
                                <p class="flex justify-between text-sm"><strong class="flex items-center gap-1"><i class='bx bx-time'></i> Listed At:</strong> <?= date("F j, Y – g:i A", strtotime($item['added_at'])) ?></p>
                            </div>
                            <p class="flex justify-between text-sm text-gray-600"><strong class="flex items-center gap-1"><i class='bx bx-user'></i> Added By:</strong> <?= htmlspecialchars($item['added_by']) ?></p><br>
                            <div class="w-full h-[2px] bg-gray-300 mb-3"></div>
                            <p class="flex justify-between text-sm mb-2"><strong class="flex items-center gap-1"><i class='bx bx-info-circle'></i> Description:</strong></p>
                            <p class="text-sm mb-4 bg-gray-50 p-2 rounded border"><?= htmlspecialchars(ucfirst($item['description'])) ?></p><br>
                            <div class="w-full h-[1px] bg-gray-300 mt-2 mb-3"></div>
                            <p class="flex justify-between text-sm mb-3"><strong class="flex items-center gap-1"><i class='bx bx-cog'></i> ACTIONS</strong></p>
                            <div class="flex gap-2 flex-wrap">
                                <button
                                    class="btn btn-edit btn-sm bg-yellow-500 text-white rounded-lg py-2 px-4 text-md flex items-center gap-1 hover:bg-yellow-600 transition mt-2 shadow-sm"
                                    onclick="showEditModal(<?= (int)$item['med_id'] ?>, '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($item['type'], ENT_QUOTES) ?>', '<?= htmlspecialchars($item['description'], ENT_QUOTES) ?>')"
                                >
                                    <i class='bx bx-edit'></i> Edit
                                </button>
                                <?php if ($has_restock_button): ?>
                                <button
                                    class="btn btn-restock btn-sm bg-green-500 text-white rounded-lg py-2 px-4 text-md flex items-center gap-1 hover:bg-green-600 transition mt-2 shadow-sm"
                                    onclick="showRestockModalForBatch(<?= (int)$item['med_id'] ?>, '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>', <?= (int)$item['quantity'] ?>, '<?= htmlspecialchars($item['expiry_date'], ENT_QUOTES) ?>')"
                                >
                                    <i class='bx bx-refresh'></i> Restock
                                </button>
                                <?php endif; ?>
                                <button 
                                    class="btn btn-dispose btn-sm bg-red-500 text-white rounded-lg py-2 px-4 text-md flex items-center gap-1 hover:bg-red-600 transition mt-2 shadow-sm" 
                                    onclick="showDisposeModal(
                                        <?= (int)$item['med_id'] ?>, 
                                        '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>', 
                                        '<?= (int)$item['quantity'] ?>'
                                    )">
                                    <i class='bx bx-trash'></i> Dispose All
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p class='text-gray-500 text-center py-4'>No medicines found.</p>";
    }
    $stmt->close();
    exit; 
}


// --- Initial Page Load Queries ---
$medicines_query = "SELECT * FROM medicine ORDER BY added_at DESC LIMIT $items_per_page OFFSET $offset";
$medicines_result = $con->query($medicines_query);

$all_medicines_query = "SELECT med_id, name, quantity, expiry_date, description, type FROM medicine ORDER BY name ASC";
$all_medicines_result = $con->query($all_medicines_query);

// Group medicines by name for modals
$medicines_by_name = [];
$medicines_grouped_for_modals = [];
$medicines_for_modals = $con->query("SELECT * FROM medicine ORDER BY name ASC, expiry_date ASC");
while ($row = $medicines_for_modals->fetch_assoc()) {
    $name = $row['name'];
    if (!isset($medicines_grouped_for_modals[$name])) {
        $medicines_grouped_for_modals[$name] = [];
    }
    $medicines_grouped_for_modals[$name][] = $row;
}

$categories = [];
// Modified category extraction to be more robust
$categories_query = "SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(description, '[', 1), ';', 1) as category FROM medicine WHERE description LIKE '%for%' OR description LIKE '%for %'";
$categories_result = $con->query($categories_query);
if ($categories_result) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = trim($row['category']);
    }
}
$categories = array_filter(array_unique($categories));
sort($categories);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIAIMS | Inventory</title>
    <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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
            font-family: 'Poppins', sans-serif;
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
            font-weight: 700;
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
            font-weight: normal;
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
            font-weight: 700;
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

        .stat-card {
            transition: transform 0.5s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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

        .infoguide {
            width: 100%;
            padding: 15px;
        }

        .infocontent {
            background: #fafdffff;
            padding: 18px 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .infocontent:hover {
            transform: translateY(-3px);
        }

        .infocontent i {
            font-size: 32px;
            color: #1e88e5;
            animation: pulse 2s infinite;
        }

        .infocontent p {
            font-size: 1rem;
            color: #333;
            margin: 0;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .medicine-grid{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(800px, 1fr));
            gap: 1rem; /* space between cards */
            padding: 15px;
            border-radius: 4px;
        }

        /* Enhanced Medicine Card */
        .medicine-card-group {
            background: linear-gradient(to bottom right, #ffffff, #f8fafc);
            border-radius: 14px;
            padding: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .medicine-card-group:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            border-color: #cbd5e1;
        }

        /* Status Highlight Variations */
        .expired-highlight {
            border-left: 6px solid #dc2626;
            background: linear-gradient(to right, #fef2f2, #ffffff);
        }
        .near-expiry-highlight {
            border-left: 6px solid #ea580c;
            background: linear-gradient(to right, #fff7ed, #ffffff);
        }
        .low-stock-highlight {
            border-left: 6px solid #d97706;
            background: linear-gradient(to right, #fefce8, #ffffff);
        }
        .default-highlight {
            border-left: 6px solid #2f8f4e;
            background: linear-gradient(to right, #f0fdf4, #ffffff);
        }

        /* Header Section */
        .medicine-header-grouped {
            padding: 16px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .medicine-header-grouped:hover {
            background: rgba(248, 250, 252, 0.7);
        }

        .medicine-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.3px;
        }

        .batch-count {
            font-size: 0.75rem;
            background: #3b82f6;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        /* Details Section */
        .medicine-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-top: 1px solid #e2e8f0;
        }
        .batch-item {
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }
        /* Action Buttons */
        .btn-edit, .btn-restock, .btn-dispose {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
        }

        .btn-edit:hover, .btn-restock:hover, .btn-dispose:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .pagination-controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .pagination-controls button {
            padding: 8px 16px;
            border: 1px solid #d4e5d4;
            border-radius: 8px;
            background: white;
            color: #2D5A3D;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination-controls button:hover:not(:disabled) {
            background: #40916C;
            color: white;
            border-color: #40916C;
        }

        .pagination-controls button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .items-per-page-select {
            padding: 8px 12px;
            border: 1px solid #d4e5d4;
            border-radius: 8px;
            background: white;
            color: #2D5A3D;
            font-weight: 500;
            cursor: pointer;
        }

        /* General Modal Styling */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0,0,0,0.5); 
            overflow: auto;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 800px;
            width: 90%;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .modal-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            margin: 0;
            color: #002e2d;
        }
        .close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s;
        }
        .close-btn:hover, .close-btn:focus {
            color: #333;
            text-decoration: none;
        }
        .modal form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .modal label {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }
        .modal input[type="text"], 
        .modal input[type="number"], 
        .modal input[type="date"], 
        .modal select, 
        .modal textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }
        .modal textarea {
            resize: vertical;
        }
        .modal-footer {
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: 20px;
            text-align: right;
        }

        /* Enhanced Modal Layout */
        .modal-two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            height: 60vh;
        }

        .modal-column {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .modal-search-section {
            padding: 10px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-search-section input,
        .modal-search-section select {
            width: 100%;
            padding: 8px 12px;
            margin-bottom: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        .medicine-selection-container {
            flex: 1;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }

        .medicine-group-container {
            margin-bottom: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }

        .medicine-group-header {
            padding: 12px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .medicine-group-header:hover {
            background: #f1f5f9;
        }

        .batch-list {
            padding: 8px;
            background: #f8fafc;
        }

        .batch-item-checkbox {
            display: flex;
            align-items: center;
            padding: 8px;
            margin: 4px 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            cursor: pointer;
        }

        .batch-item-checkbox:hover {
            background: #f1f5f9;
        }

        .batch-item-checkbox input {
            margin-right: 8px;
        }

        .selected-items-container {
            flex: 1;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            background: #f0fdf4;
        }

        .selected-medicine-card {
            background: white;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
        }

        .selected-medicine-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        /* Floating Action Button */
        .fab-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .fab-main {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #40916C, #2D5A3D);
            color: white;
            border: none;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(64, 145, 108, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fab-main:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(64, 145, 108, 0.4);
        }

        .fab-main.active {
            transform: rotate(45deg);
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .fab-actions {
            position: absolute;
            bottom: 70px;
            right: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .fab-actions.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: all;
        }

        .fab-action {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: white;
            border-radius: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: none;
            cursor: pointer;
            transform: translateX(20px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }

        .fab-actions.show .fab-action {
            transform: translateX(0);
            opacity: 1;
        }

        .fab-actions.show .fab-action:nth-child(1) { transition-delay: 0.1s; }
        .fab-actions.show .fab-action:nth-child(2) { transition-delay: 0.2s; }
        .fab-actions.show .fab-action:nth-child(3) { transition-delay: 0.3s; }
        .fab-actions.show .fab-action:nth-child(4) { transition-delay: 0.4s; }

        .fab-action:hover {
            transform: translateX(-5px) !important;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .fab-action.add {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .fab-action.restock {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .fab-action.deduct {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .fab-action.dispose {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 16px;
            margin-bottom: 10px;
            min-width: 300px;
            max-width: 350px;
            transform: translateX(400px);
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 4px solid;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.success {
            border-left-color: #10B981;
        }

        .toast.error {
            border-left-color: #EF4444;
        }

        .toast.warning {
            border-left-color: #F59E0B;
        }

        .toast.info {
            border-left-color: #3B82F6;
        }

        .toast-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .toast-title {
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #6B7280;
        }

        .toast-body {
            font-size: 13px;
            color: #4B5563;
            margin-bottom: 12px;
        }

        .toast-progress {
            height: 3px;
            background: #E5E7EB;
            border-radius: 2px;
            overflow: hidden;
        }

        .toast-progress-bar {
            height: 100%;
            width: 100%;
            transition: width linear;
        }

        .toast.success .toast-progress-bar {
            background: #10B981;
        }

        .toast.error .toast-progress-bar {
            background: #EF4444;
        }

        .toast.warning .toast-progress-bar {
            background: #F59E0B;
        }

        .toast.info .toast-progress-bar {
            background: #3B82F6;
        }

        /* Filter Controls */
        .filter-controls {
            display: none;
            animation: slideDown 0.3s ease;
        }

        .filter-controls.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-expired {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-active {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-low-stock {
            background: #fef3c7;
            color: #d97706;
        }

        .status-high-stock {
            background: #dbeafe;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../components/sidebar.php'; ?>
        
        <main class="main-content" id="mainContent">
            <header class="main-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class='bx bx-menu'></i>
                </button>
                <h1 class="text-2xl font-bold">Inventory Management</h1>
            </header>
            <div class="infoguide">
                <div class="infocontent">
                    <i class='bx bx-info-circle'></i>
                    <p>Manage your medical inventory efficiently. Add new medicines, restock existing ones, and dispose of expired items. Use the search and filter options to quickly find specific medicines. Expired medicines cannot be deducted and must be disposed.</p>
                </div>
            </div>
            <div class="content-container pl-6 pr-6 pt-2">
                <section class="content-section active" id="restockSection">
                    <div class="status-grid grid grid-cols-2 sm:grid-cols-1  lg:grid-cols-3 xl:grid-cols-5 gap-4 w-full mb-4">
                        
                        <div class="stat-card bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition" onclick="setFilter('total')">
                            <div class="stat-header flex justify-between items-center">
                            <h3 class="font-semibold text-lg">Total Medicines</h3>
                            <i class='bx bx-package text-3xl'></i>
                            </div>
                            <div class="stat-content mt-4">
                            <div class="stat-value text-3xl font-bold"><?= $total_meds_count ?></div>
                            <p class="text-sm opacity-90">In inventory</p>
                            </div>
                        </div>

                        <div class="stat-card bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition" onclick="setFilter('low_stock')">
                            <div class="stat-header flex justify-between items-center">
                            <h3 class="font-semibold text-lg">Low Stock Items</h3>
                            <i class='bx bx-error text-3xl'></i>
                            </div>
                            <div class="stat-content mt-4">
                            <div class="stat-value text-3xl font-bold"><?= $low_stock_count ?></div>
                            <p class="text-sm">Requires restocking</p>
                            </div>
                        </div>

                        <div class="stat-card bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition" onclick="setFilter('near_expiry')">
                            <div class="stat-header flex justify-between items-center">
                            <h3 class="font-semibold text-lg">Near Expiry</h3>
                            <i class='bx bx-calendar-exclamation text-3xl'></i>
                            </div>
                            <div class="stat-content mt-4">
                            <div class="stat-value text-3xl font-bold"><?= $near_expiry_count ?></div>
                            <p class="text-sm opacity-90">Approaching expiry</p>
                            </div>
                        </div>

                        <div class="stat-card bg-gradient-to-r from-red-500 to-red-600 text-white rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition" onclick="setFilter('expired')">
                            <div class="stat-header flex justify-between items-center">
                            <h3 class="font-semibold text-lg">Expired</h3>
                            <i class='bx bx-trash text-3xl'></i>
                            </div>
                            <div class="stat-content mt-4">
                            <div class="stat-value text-3xl font-bold"><?= $expired_count ?></div>
                            <p class="text-sm opacity-90">Should be disposed</p>
                            </div>
                        </div>

                        <div class="stat-card bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition">
                            <div class="stat-header flex justify-between items-center">
                            <h3 class="font-semibold text-lg">This Month Usage</h3>
                            <i class='bx bx-trending-up text-3xl'></i>
                            </div>
                            <div class="stat-content mt-4">
                            <div class="stat-value text-3xl font-bold"><?= $monthly_usage_count ?></div>
                            <p class="text-sm opacity-90">Items dispensed</p>
                            </div>
                        </div>

                    </div>

                    <div class="card p-4 gap-2 bg-white border-2 border-gray-300 rounded-lg pb-10">
                        <div class="card-header p-2 gap-10 mb-6">
                            <h2 class=" font-semibold" style="font-size: 20px;">Medicine Inventory</h2>
                            <p class="mb-10 font-500" style="font-size: 15px;">Manage your medicines capacity, quantity, types and status. Expanded cards show detailed information.</p>
                            <div class="card-actions w-full flex flex-col sm:flex-row items-center justify-between">
                                <div class="flex gap-2 w-full">
                                    <input type="text" id="searchInput" placeholder="Search for medicine..." onkeyup="updateMedicineGrid()" style="border: 1px solid #383838ff; padding: 8px; border-radius: 60px; width:100%; max-width: 350px; padding-left: 20px;">
                                    
                                </div>
                                <div class="flex gap-4 w-2xl text-nowrap">
                                    <button class="btn btn-primary p-2 px-4 gap-4 rounded-lg bg-blue-600 text-white hover:bg-gray-800" onclick="toggleFilterControls()">
                                        <i class='bx bx-filter-alt'></i> Filters
                                    </button>
                                    <button class="btn btn-primary hidden p-2 rounded-lg bg-green-700 text-white hover:bg-green-800" onclick="showAddMedicineModal()">
                                        <i class='bx bx-plus'></i> Medicine
                                    </button>
                                    <button class="btn btn-primary hidden p-2 rounded-lg bg-blue-500 text-white hover:bg-blue-800" onclick="showMultiRestockModal()">
                                        <i class='bx bx-refresh'></i> Restock
                                    </button>
                                    <button class="btn btn-warning hidden p-2 rounded-lg bg-red-600 text-white hover:bg-red-800" onclick="showMultiDeductModal()">
                                        <i class='bx bx-minus'></i> Deduct
                                    </button>
                                    <button class="btn btn-warning hidden p-2 rounded-lg bg-purple-600 text-white hover:bg-purple-800 text-nowrap" onclick="showMultipleDisposeModal()">
                                        <i class='bx bx-trash'></i> Dispose Multiple
                                    </button>
                                    <div style="margin-left: 10px; width: 100%;">
                                        <label for="itemsPerPage" style="margin-right: 10px; font-weight: 500;">Show:</label>
                                        <select id="itemsPerPage" class="items-per-page-select" onchange="changeItemsPerPage()">
                                            <option value="2" <?= $items_per_page == 2 ? 'selected' : '' ?>>2 items</option>
                                            <option value="5" <?= $items_per_page == 5 ? 'selected' : '' ?>>5 items</option>
                                            <option value="10" <?= $items_per_page == 10 ? 'selected' : '' ?>>10 items</option>
                                            <option value="25" <?= $items_per_page == 25 ? 'selected' : '' ?>>25 items</option>
                                            <option value="50" <?= $items_per_page == 50 ? 'selected' : '' ?>>50 items</option>
                                            <option value="100" <?= $items_per_page == 100 ? 'selected' : '' ?>>100 items</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Filter Controls (Hidden by default) -->
                            <div id="filterControls" class="filter-controls mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Filter</label>
                                        <select id="stockFilter" onchange="updateMedicineGrid()" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                            <option value="">All Stock</option>
                                            <option value="low">Low Stock (1-20)</option>
                                            <option value="high">High Stock (20+)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Expiry Filter</label>
                                        <select id="expiryFilter" onchange="updateMedicineGrid()" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                            <option value="">All Expiry</option>
                                            <option value="expired">Expired</option>
                                            <option value="near_expiry">Near Expiry (30 days)</option>
                                            <option value="expiry_1m">Expires in 1 Month</option>
                                            <option value="expiry_2m">Expires in 2 Months</option>
                                            <option value="expiry_3m">Expires in 3 Months</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type Filter</label>
                                        <select id="typeFilter" onchange="updateMedicineGrid()" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                            <option value="">All Types</option>
                                            <option value="tablet">Tablet</option>
                                            <option value="capsule">Capsule</option>
                                            <option value="syrup">Syrup</option>
                                            <option value="injection">Injection</option>
                                            <option value="ointment">Ointment</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort by Expiry</label>
                                    <select id="expirySortDropdown" onchange="updateMedicineGrid()" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                        <option value="">Default</option>
                                        <option value="oldest_first">Oldest First</option>
                                        <option value="newest_first">Newest First</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="medicine-grid" id="medicineGrid">
                                <?php if ($medicines_result->num_rows > 0): ?>
                                    <?php 
                                    $medicines_grouped = [];
                                    while ($row = $medicines_result->fetch_assoc()) {
                                        $name = $row['name'];
                                        if (!isset($medicines_grouped[$name])) {
                                            $medicines_grouped[$name] = [];
                                        }
                                        $medicines_grouped[$name][] = $row;
                                    }
                                    
                                    foreach ($medicines_grouped as $med_name => $med_items):
                                        // Sort batches: non-zero first by expiry date, then zero quantity at the end
                                        usort($med_items, function($a, $b) {
                                            if ((int)$a['quantity'] == 0 && (int)$b['quantity'] != 0) return 1;
                                            if ((int)$a['quantity'] != 0 && (int)$b['quantity'] == 0) return -1;
                                            return strtotime($a['expiry_date']) - strtotime($b['expiry_date']);
                                        });

                                        $total_stock = 0;
                                        $has_expired = false;
                                        $has_near_expiry = false;
                                        $status_text = 'Healthy Stock';
                                        $card_highlight_class = 'default-highlight';
                                        
                                        foreach ($med_items as $item) {
                                            $total_stock += $item['quantity'];
                                            $expiry_date = new DateTime($item['expiry_date']);
                                            $today = new DateTime();
                                            $days_until_expiry = $today->diff($expiry_date)->days;
                                            
                                            if ($expiry_date < $today) {
                                                $has_expired = true;
                                            } elseif ($days_until_expiry <= 30) {
                                                $has_near_expiry = true;
                                            }
                                        }
                                        
                                        $is_low_stock = $total_stock < 15;
                                        
                                        if ($has_expired) {
                                            $status_text = 'Expired';
                                            $card_highlight_class = 'expired-highlight';
                                        } elseif ($has_near_expiry) {
                                            $status_text = 'Near Expiry';
                                            $card_highlight_class = 'near-expiry-highlight';
                                        } elseif ($is_low_stock) {
                                            $status_text = 'Low Stock';
                                            $card_highlight_class = 'low-stock-highlight';
                                        } else {
                                            $status_text = 'Healthy Stock';
                                            $card_highlight_class = 'default-highlight';
                                        }
                                        
                                        $stock_class = $total_stock <= 10 ? 'text-red-600' : ($total_stock < 15 ? 'text-yellow-600' : 'text-green-600');
                                    ?>
                                    
                                    <div class="medicine-card-group <?= $card_highlight_class ?> relative transition-transform hover:scale-101 mt-4 border-2 border-gray-600 rounded-lg min-h-[70px]">
                                 
                                        <!-- Enhanced Grouped Header -->
                                        <div class="medicine-header-grouped flex justify-between items-center p-4 cursor-pointer hover:bg-white/50 transition rounded-t-lg" onclick="toggleMedicineGroup(event)">
                                            <div class="flex items-center gap-3 flex-1">
                                                <!-- Enhanced Status Badge -->
                                                <div class="text-xs font-normal px-3 py-1.5 rounded-full flex items-center gap-1
                                                    <?php
                                                        if ($status_text === 'Expired') echo 'bg-red-600 text-white';
                                                        elseif ($status_text === 'Near Expiry') echo 'bg-orange-600 text-white';
                                                        elseif ($status_text === 'Low Stock') echo 'bg-yellow-600 text-white';
                                                        else echo 'bg-green-600 text-white';
                                                    ?>" style="z-index: 2000px;">
                                                    
                                                    <?php
                                                        if ($status_text === 'Expired') echo '<i class="bx bx-x-circle"></i>';
                                                        elseif ($status_text === 'Near Expiry') echo '<i class="bx bx-time-five"></i>';
                                                        elseif ($status_text === 'Low Stock') echo '<i class="bx bx-down-arrow-alt"></i>';
                                                        else echo '<i class="bx bx-check-circle"></i>';
                                                    ?>
                                                    <?= $status_text ?>
                                                </div>
                                                <i class='bx bx-chevron-right transition-transform'></i>
                                                <div class="medicine-name font-semibold text-lg text-gray-800 flex items-center gap-2">
                                                    <i class='bx bx-capsule text-blue-500'></i>
                                                    <?= htmlspecialchars($med_name) ?>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <div class="stock-level font-semibold text-sm <?= $stock_class ?> flex items-center gap-1">
                                                    <i class='bx bx-package'></i>
                                                    Total Stock: <?= htmlspecialchars($total_stock) ?>
                                                </div>
                                                <div class="batch-count text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                    <?= count($med_items) ?> batch<?= count($med_items) > 1 ? 'es' : '' ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Enhanced Expanded Details -->
                                        <div class="medicine-details hidden bg-white/30 p-4 border-t border-gray-200 w-full space-y-3">
                                            <!-- Individual batch details -->
                                            <?php foreach ($med_items as $index => $item):
                                                $exp_date = new DateTime($item['expiry_date']);
                                                $today = new DateTime();
                                                $is_item_expired = $exp_date < $today;
                                                $item_status = $is_item_expired ? 'EXPIRED' : 'ACTIVE';
                                                $item_status_color = $is_item_expired ? 'text-red-600 font-bold' : 'text-green-600';
                                                $formatted_expiry = $exp_date->format('F d, Y');
                                                $has_restock_button = $item['quantity'] < 20;
                                                $days_diff = $today->diff($exp_date)->days;
                                                $expiry_class = $is_item_expired ? 'bg-red-50 border-red-200' : ($days_diff <= 30 ? 'bg-orange-50 border-orange-200' : 'bg-green-50 border-green-200');
                                            ?>
                                                <div class="batch-item border-2 p-4 rounded-lg <?= $expiry_class ?> <?= $index > 0 ? 'pt-3 border-gray-200' : '' ?>">
                                                    <div class="flex justify-between items-center mb-2">
                                                        <span class="text-sm font-semibold text-gray-700 flex items-center gap-1">
                                                            <i class='bx bx-layer'></i>
                                                            Medicine Batch <?= $index + 1 ?>
                                                        </span>
                                                        <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $item_status_color ?>">
                                                            <?= $item_status ?>
                                                        </span>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                                        <p class="flex justify-between text-sm"><strong class="flex items-center gap-1"><i class='bx bx-package'></i> Quantity:</strong> <?= htmlspecialchars($item['quantity']) ?></p>
                                                        <p class="flex justify-between text-sm"><strong class="flex items-center gap-1"><i class='bx bx-cube'></i> Type:</strong> <?= htmlspecialchars(ucfirst($item['type'])) ?></p>
                                                        <p class="flex justify-between text-sm"><strong class="flex items-center gap-1"><i class='bx bx-calendar'></i> Expiry:</strong> <?= $formatted_expiry ?></p>
                                                        <p class="flex justify-between text-sm"><strong class="flex items-center gap-1"><i class='bx bx-time'></i> Listed At:</strong> <?= date("F j, Y – g:i A", strtotime($item['added_at'])) ?></p>
                                                    </div>

                                                    <p class="flex justify-between text-sm text-gray-600"><strong class="flex items-center gap-1"><i class='bx bx-user'></i> Added By:</strong> <?= htmlspecialchars($item['added_by']) ?></p><br>
                                                    <div class="w-full h-[2px] bg-gray-300 mb-3"></div>
                                                    <p class="flex justify-between text-sm mb-2"><strong class="flex items-center gap-1"><i class='bx bx-info-circle'></i> Description:</strong></p>
                                                    <p class="text-sm mb-4 bg-gray-50 p-2 rounded border"><?= htmlspecialchars(ucfirst($item['description'])) ?></p><br>
                                                    <div class="w-full h-[1px] bg-gray-300 mt-2 mb-3"></div>
                                                    <p class="flex justify-between text-sm mb-3"><strong class="flex items-center gap-1"><i class='bx bx-cog'></i> ACTIONS</strong></p>
                                                    <div class="flex gap-2 flex-wrap">
                                                        <button
                                                            class="btn btn-edit btn-sm bg-yellow-500 text-white rounded-lg py-2 px-4 text-md flex items-center gap-1 hover:bg-yellow-600 transition mt-2 shadow-sm"
                                                            onclick="showEditModal(<?= (int)$item['med_id'] ?>, '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($item['type'], ENT_QUOTES) ?>', '<?= htmlspecialchars($item['description'], ENT_QUOTES) ?>')"
                                                        >
                                                            <i class='bx bx-edit'></i> Edit
                                                        </button>
                                                        <?php if ($has_restock_button): ?>
                                                        <button
                                                            class="btn btn-restock btn-sm bg-green-500 text-white rounded-lg py-2 px-4 text-md flex items-center gap-1 hover:bg-green-600 transition mt-2 shadow-sm"
                                                            onclick="showRestockModalForBatch(<?= (int)$item['med_id'] ?>, '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>', <?= (int)$item['quantity'] ?>, '<?= htmlspecialchars($item['expiry_date'], ENT_QUOTES) ?>')"
                                                        >
                                                            <i class='bx bx-refresh'></i> Restock
                                                        </button>
                                                        <?php endif; ?>
                                                        <button 
                                                            class="btn btn-dispose btn-sm bg-red-500 text-white rounded-lg py-2 px-4 text-md flex items-center gap-1 hover:bg-red-600 transition mt-2 shadow-sm" 
                                                            onclick="showDisposeModal(
                                                                <?= (int)$item['med_id'] ?>, 
                                                                '<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>', 
                                                                '<?= (int)$item['quantity'] ?>'
                                                            )">
                                                            <i class='bx bx-trash'></i> Dispose All
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <?php endforeach; ?>
                                
                                <?php else: ?>
                                    <p class="text-gray-500 text-center py-4">No medicines found.</p>
                                <?php endif; ?>
                            </div>

                            <!-- Added pagination controls -->
                            <div class="pagination-container">
                                <div class="pagination-controls">
                                    <button id="prevBtn" onclick="changePage(-1)" <?= $page <= 1 ? 'disabled' : '' ?>>
                                        <i class='bx bx-chevron-left'></i> Previous
                                    </button>
                                    <span class="text-gray-700 font-medium">
                                        Page <span id="currentPage"><?= $page ?></span> of <span id="totalPages"><?= $total_pages ?></span>
                                    </span>
                                    <button id="nextBtn" onclick="changePage(1)" <?= $page >= $total_pages ? 'disabled' : '' ?>>
                                        Next <i class='bx bx-chevron-right'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Floating Action Button -->
    <div class="fab-container">
        <button class="fab-main" id="fabMain">
            <i class='bx bx-plus'></i>
        </button>
        <div class="fab-actions" id="fabActions">
            <button class="fab-action add" onclick="showAddMedicineModal()">
                <i class='bx bx-plus-medical'></i>
                <span>Medicine</span>
            </button>
            <button class="fab-action restock" onclick="showMultiRestockModal()">
                <i class='bx bx-refresh'></i>
                <span>Restock</span>
            </button>
            <button class="fab-action deduct" onclick="showMultiDeductModal()">
                <i class='bx bx-minus'></i>
                <span>Deduct</span>
            </button>
            <button class="fab-action dispose" onclick="showMultipleDisposeModal()">
                <i class='bx bx-trash'></i>
                <span>Dispose</span>
            </button>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

   <!-- Enhanced Add Medicine Modal with 4 severity levels -->
    <div id="addMedicineModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm" style="z-index: 1000px;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl animate-fadeIn">
            <!-- Enhanced Header with Icon -->
            <div class="flex justify-between items-center border-b-2 border-green-200 px-6 py-5 bg-gradient-to-r from-green-50 via-emerald-50 to-green-100">
                <div class="flex items-center gap-2">
                    <i class='bx bx-plus-circle text-green-600 text-2xl'></i>
                    <h2 class="text-xl font-semibold text-gray-800">Add New Medicine</h2>
                </div>
                <button onclick="closeAddMedicineModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold transition">&times;</button>
            </div>

            <form id="addMedicineForm" action="Inventory.php" method="POST" class="px-6 py-5 space-y-4">
                <input type="hidden" name="add_medicine_submit" value="1">
                
                <div class="flex justify-center w-full gap-2 align-center">
                    <!-- Name and Quantity Row -->
                    <div class="flex gap-4 w-full">
                        <div class="w-full">
                            <label for="med_name" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-capsule text-blue-500'></i> Medicine Name
                            </label>
                            <input type="text" id="med_name" name="med_name" required
                                class="w-full rounded-lg border-2 border-gray-300 py-2.5 px-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 shadow-sm transition"/>
                        </div>

                        <div class="w-full">
                            <label for="med_quantity" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-package text-blue-500'></i> Quantity
                            </label>
                            <input type="number" id="med_quantity" name="med_quantity" min="1" required
                                class="w-full rounded-lg border-2 border-gray-300 py-2.5 px-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 shadow-sm transition"/>
                        </div>
                    </div>

                    <!-- Type and Expiry Row -->
                    <div class="flex gap-4 w-full">
                        <div class="w-full">
                            <label for="med_type" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-shapes text-blue-500'></i> Type
                            </label>
                            <select id="med_type" name="med_type" required
                                    class="w-full rounded-lg border-2 border-gray-300 py-2.5 px-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 shadow-sm transition">
                                <option value="">Select Type</option>
                                <option value="tablet">💊 Tablet</option>
                                <option value="capsule">⊙ Capsule</option>
                                <option value="syrup">🧪 Syrup</option>
                                <option value="injection">💉 Injection</option>
                                <option value="ointment">🧴 Ointment</option>
                                <option value="other">📦 Other</option>
                            </select>
                        </div>

                        <div class="w-full">
                            <label for="med_expiry" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-calendar text-blue-500'></i> Expiry Date
                            </label>
                            <input type="date" id="med_expiry" name="med_expiry" required
                                class="w-full rounded-lg border-2 border-gray-300 py-2.5 px-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 shadow-sm transition">
                        </div>
                    </div>
                </div>

                <!-- Severity Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1">
                        <i class='bx bx-shield text-blue-500'></i> Select Severity Level
                    </label>
                    <div class="grid grid-cols-4 gap-3">
                        <label class="flex items-center p-3 border-2 border-blue-100 rounded-lg bg-blue-50 cursor-pointer hover:bg-blue-100 transition">
                            <input type="radio" name="med_severity" value="mild" class="mr-2" checked>
                            <div>
                                <div class="font-medium text-blue-700">Mild</div>
                                <div class="text-xs text-blue-600">General symptoms</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-green-100 rounded-lg bg-green-50 cursor-pointer hover:bg-green-100 transition">
                            <input type="radio" name="med_severity" value="moderate" class="mr-2">
                            <div>
                                <div class="font-medium text-green-700">Moderate</div>
                                <div class="text-xs text-green-600">Chronic conditions</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-orange-100 rounded-lg bg-orange-50 cursor-pointer hover:bg-orange-100 transition">
                            <input type="radio" name="med_severity" value="severe" class="mr-2">
                            <div>
                                <div class="font-medium text-orange-700">Severe</div>
                                <div class="text-xs text-orange-600">Acute conditions</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-red-100 rounded-lg bg-red-50 cursor-pointer hover:bg-red-100 transition">
                            <input type="radio" name="med_severity" value="critical" class="mr-2">
                            <div>
                                <div class="font-medium text-red-700">Critical</div>
                                <div class="text-xs text-red-600">Life-threatening</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Enhanced Medical Purposes Section with 8 checkboxes (2 per severity) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1">
                        <i class='bx bx-list-check text-blue-500'></i> Medical Purposes / Description
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Mild Severity Purposes -->
                        <div class="p-3 border-2 border-blue-100 rounded-lg bg-blue-50">
                            <p class="text-xs font-bold text-blue-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-smile'></i> Mild Severity Purposes
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="purpose_headache" name="med_purposes[]" value="Headache Relief" class="rounded">
                                    <label for="purpose_headache" class="ml-2 text-sm text-gray-700">Headache Relief</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="purpose_fever" name="med_purposes[]" value="Fever Reduction" class="rounded">
                                    <label for="purpose_fever" class="ml-2 text-sm text-gray-700">Fever Reduction</label>
                                </div>
                            </div>
                        </div>

                        <!-- Moderate Severity Purposes -->
                        <div class="p-3 border-2 border-green-100 rounded-lg bg-green-50">
                            <p class="text-xs font-bold text-green-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-time-five'></i> Moderate Severity Purposes
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="purpose_allergy" name="med_purposes[]" value="Allergy Treatment" class="rounded">
                                    <label for="purpose_allergy" class="ml-2 text-sm text-gray-700">Allergy Treatment</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="purpose_inflammation" name="med_purposes[]" value="Inflammation" class="rounded">
                                    <label for="purpose_inflammation" class="ml-2 text-sm text-gray-700">Inflammation</label>
                                </div>
                            </div>
                        </div>

                        <!-- Severe Severity Purposes -->
                        <div class="p-3 border-2 border-orange-100 rounded-lg bg-orange-50">
                            <p class="text-xs font-bold text-orange-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-shield-alt-2'></i> Severe Severity Purposes
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="purpose_infection" name="med_purposes[]" value="Infection" class="rounded">
                                    <label for="purpose_infection" class="ml-2 text-sm text-gray-700">Infection</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="purpose_bp" name="med_purposes[]" value="Blood Pressure" class="rounded">
                                    <label for="purpose_bp" class="ml-2 text-sm text-gray-700">Blood Pressure</label>
                                </div>
                            </div>
                        </div>

                        <!-- Critical Severity Purposes -->
                        <div class="p-3 border-2 border-red-100 rounded-lg bg-red-50">
                            <p class="text-xs font-bold text-red-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-first-aid'></i> Critical Severity Purposes
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="purpose_heart" name="med_purposes[]" value="Heart Condition" class="rounded">
                                    <label for="purpose_heart" class="ml-2 text-sm text-gray-700">Heart Condition</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="purpose_diabetes" name="med_purposes[]" value="Diabetes" class="rounded">
                                    <label for="purpose_diabetes" class="ml-2 text-sm text-gray-700">Diabetes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>

            <!-- Footer -->
            <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-2xl">
                <button onclick="closeAddMedicineModal()" type="button" class="px-5 py-2.5 rounded-lg bg-gray-300 text-gray-800 hover:bg-gray-400 transition font-medium flex items-center gap-1">
                    <i class='bx bx-x'></i> Cancel
                </button>
                <button type="submit" form="addMedicineForm" class="px-5 py-2.5 rounded-lg bg-green-600 text-white hover:bg-green-700 transition font-medium flex items-center gap-1">
                    <i class='bx bx-check'></i> Add Medicine
                </button>
            </div>
        </div>
    </div>
    
    <!-- Edit Medicine Modal with 4 severity levels -->
    <div id="editMedicineModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm" style="z-index: 1000px;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl animate-fadeIn">
            <!-- Enhanced Header with Icon -->
            <div class="flex justify-between items-center border-b-2 border-yellow-200 px-6 py-5 bg-gradient-to-r from-yellow-50 via-amber-50 to-yellow-100">
                <div class="flex items-center gap-2">
                    <i class='bx bx-edit text-yellow-600 text-2xl'></i>
                    <h2 class="text-xl font-semibold text-gray-800">Edit Medicine</h2>
                </div>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold transition">&times;</button>
            </div>

            <form id="editMedicineForm" action="Inventory.php" method="POST" class="px-6 py-5 space-y-4">
                <input type="hidden" name="edit_medicine" value="1">
                <input type="hidden" id="edit_med_id" name="med_id">
                
                <div class="flex justify-center w-full gap-2 align-center">
                    <!-- Name and Type Row -->
                    <div class="flex gap-4 w-full">
                        <div class="w-full">
                            <label for="edit_med_name" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-capsule text-blue-500'></i> Medicine Name
                            </label>
                            <input type="text" id="edit_med_name" name="med_name" required
                                class="w-full rounded-lg border-2 border-gray-300 py-2.5 px-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 shadow-sm transition"/>
                        </div>

                        <div class="w-full">
                            <label for="edit_med_type" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-shapes text-blue-500'></i> Type
                            </label>
                            <select id="edit_med_type" name="med_type" required
                                    class="w-full rounded-lg border-2 border-gray-300 py-2.5 px-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 shadow-sm transition">
                                <option value="">Select Type</option>
                                <option value="tablet">💊 Tablet</option>
                                <option value="capsule">⊙ Capsule</option>
                                <option value="syrup">🧪 Syrup</option>
                                <option value="injection">💉 Injection</option>
                                <option value="ointment">🧴 Ointment</option>
                                <option value="other">📦 Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Severity Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1">
                        <i class='bx bx-shield text-blue-500'></i> Select Severity Level
                    </label>
                    <div class="grid grid-cols-4 gap-3">
                        <label class="flex items-center p-3 border-2 border-blue-100 rounded-lg bg-blue-50 cursor-pointer hover:bg-blue-100 transition">
                            <input type="radio" name="med_severity" value="mild" class="mr-2" id="edit_severity_mild">
                            <div>
                                <div class="font-medium text-blue-700">Mild</div>
                                <div class="text-xs text-blue-600">General symptoms</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-green-100 rounded-lg bg-green-50 cursor-pointer hover:bg-green-100 transition">
                            <input type="radio" name="med_severity" value="moderate" class="mr-2" id="edit_severity_moderate">
                            <div>
                                <div class="font-medium text-green-700">Moderate</div>
                                <div class="text-xs text-green-600">Chronic conditions</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-orange-100 rounded-lg bg-orange-50 cursor-pointer hover:bg-orange-100 transition">
                            <input type="radio" name="med_severity" value="severe" class="mr-2" id="edit_severity_severe">
                            <div>
                                <div class="font-medium text-orange-700">Severe</div>
                                <div class="text-xs text-orange-600">Acute conditions</div>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-red-100 rounded-lg bg-red-50 cursor-pointer hover:bg-red-100 transition">
                            <input type="radio" name="med_severity" value="critical" class="mr-2" id="edit_severity_critical">
                            <div>
                                <div class="font-medium text-red-700">Critical</div>
                                <div class="text-xs text-red-600">Life-threatening</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Enhanced Medical Purposes Section with 8 checkboxes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1">
                        <i class='bx bx-list-check text-blue-500'></i> Medical Purposes / Description
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Mild Severity Purposes -->
                        <div class="p-3 border-2 border-blue-100 rounded-lg bg-blue-50">
                            <p class="text-xs font-bold text-blue-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-smile'></i> Mild Severity Purposes
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit_purpose_headache" name="med_purposes[]" value="Headache Relief" class="rounded">
                                    <label for="edit_purpose_headache" class="ml-2 text-sm text-gray-700">Headache Relief</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit_purpose_fever" name="med_purposes[]" value="Fever Reduction" class="rounded">
                                    <label for="edit_purpose_fever" class="ml-2 text-sm text-gray-700">Fever Reduction</label>
                                </div>
                            </div>
                        </div>

                        <!-- Moderate Severity Purposes -->
                        <div class="p-3 border-2 border-green-100 rounded-lg bg-green-50">
                            <p class="text-xs font-bold text-green-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-time-five'></i> Moderate Severity Purposes
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit_purpose_allergy" name="med_purposes[]" value="Allergy Treatment" class="rounded">
                                    <label for="edit_purpose_allergy" class="ml-2 text-sm text-gray-700">Allergy Treatment</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit_purpose_inflammation" name="med_purposes[]" value="Inflammation" class="rounded">
                                    <label for="edit_purpose_inflammation" class="ml-2 text-sm text-gray-700">Inflammation</label>
                                </div>
                            </div>
                        </div>

                        <!-- Severe Severity Purposes -->
                        <div class="p-3 border-2 border-orange-100 rounded-lg bg-orange-50">
                            <p class="text-xs font-bold text-orange-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-shield-alt-2'></i> Severe Severity Purposes
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit_purpose_infection" name="med_purposes[]" value="Infection" class="rounded">
                                    <label for="edit_purpose_infection" class="ml-2 text-sm text-gray-700">Infection</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit_purpose_bp" name="med_purposes[]" value="Blood Pressure" class="rounded">
                                    <label for="edit_purpose_bp" class="ml-2 text-sm text-gray-700">Blood Pressure</label>
                                </div>
                            </div>
                        </div>

                        <!-- Critical Severity Purposes -->
                        <div class="p-3 border-2 border-red-100 rounded-lg bg-red-50">
                            <p class="text-xs font-bold text-red-700 mb-2 flex items-center gap-1">
                                <i class='bx bx-first-aid'></i> Critical Severity Purposes
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit_purpose_heart" name="med_purposes[]" value="Heart Condition" class="rounded">
                                    <label for="edit_purpose_heart" class="ml-2 text-sm text-gray-700">Heart Condition</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit_purpose_diabetes" name="med_purposes[]" value="Diabetes" class="rounded">
                                    <label for="edit_purpose_diabetes" class="ml-2 text-sm text-gray-700">Diabetes</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>

            <!-- Footer -->
            <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-2xl">
                <button onclick="closeEditModal()" type="button" class="px-5 py-2.5 rounded-lg bg-gray-300 text-gray-800 hover:bg-gray-400 transition font-medium flex items-center gap-1">
                    <i class='bx bx-x'></i> Cancel
                </button>
                <button type="submit" form="editMedicineForm" class="px-5 py-2.5 rounded-lg bg-yellow-600 text-white hover:bg-yellow-700 transition font-medium flex items-center gap-1">
                    <i class='bx bx-check'></i> Update Medicine
                </button>
            </div>
        </div>
    </div>
    
    <!-- Enhanced Restock Modal with search, filters and grouped medicines -->
    <div id="restockModal" class="modal hidden fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 backdrop-blur-sm" style="z-index: 1000px;">
        <div class="modal-content bg-white rounded-2xl p-6 shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-y-auto">
            <!-- Header -->
            <div class="modal-header flex justify-between items-center border-b-2 border-blue-200 pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class='bx bx-plus-medical text-blue-600 text-2xl'></i>
                    <h2 class="text-lg font-bold text-gray-800">Restock Medicines</h2>
                </div>
                <span class="close-btn cursor-pointer text-xl font-bold text-gray-400 hover:text-gray-600 transition" onclick="closeRestockModal()">&times;</span>
            </div>

            <form id="restockForm" action="Inventory.php" method="POST">
                <div class="modal-two-column">
                    <!-- Left Column: Medicine Selection with Search & Filters -->
                    <div class="modal-column">
                        <div class="modal-search-section">
                            <input type="text" id="restockSearch" placeholder="Search medicine..." class="w-full mb-2">
                            <div class="grid grid-cols-2 gap-2">
                                <select id="restockTypeFilter" class="w-full">
                                    <option value="">All Types</option>
                                    <option value="tablet">Tablet</option>
                                    <option value="capsule">Capsule</option>
                                    <option value="syrup">Syrup</option>
                                    <option value="injection">Injection</option>
                                    <option value="ointment">Ointment</option>
                                    <option value="other">Other</option>
                                </select>
                                <select id="restockStatusFilter" class="w-full">
                                    <option value="">All Status</option>
                                    <option value="expired">Expired</option>
                                    <option value="active">Active</option>
                                    <option value="low-stock">Low Stock</option>
                                    <option value="high-stock">High Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="medicine-selection-container" id="restockSelectionList">
                            <?php foreach ($medicines_grouped_for_modals as $med_name => $batches): 
                                $first_batch = $batches[0];
                                $today = new DateTime();
                                $status = 'active';
                                $has_expired = false;
                                $has_low_stock = false;
                                
                                foreach ($batches as $batch) {
                                    $exp_date = new DateTime($batch['expiry_date']);
                                    if ($exp_date < $today) {
                                        $has_expired = true;
                                    }
                                    if ($batch['quantity'] <= 20) {
                                        $has_low_stock = true;
                                    }
                                }
                                
                                if ($has_expired) {
                                    $status = 'expired';
                                } elseif ($has_low_stock) {
                                    $status = 'low-stock';
                                } elseif ($batches[0]['quantity'] > 20) {
                                    $status = 'high-stock';
                                }
                            ?>
                            <div class="medicine-group-container" data-name="<?= htmlspecialchars($med_name) ?>" data-type="<?= $first_batch['type'] ?>" data-status="<?= $status ?>">
                                <div class="medicine-group-header" onclick="toggleBatchList(this)">
                                    <input type="checkbox" class="group-checkbox" data-med-name="<?= htmlspecialchars($med_name) ?>">
                                    <div class="flex-1">
                                        <div class="font-medium"><?= htmlspecialchars($med_name) ?></div>
                                        <div class="text-xs text-gray-500"><?= count($batches) ?> batches</div>
                                    </div>
                                    <span class="status-badge status-<?= $status ?>"><?= $status ?></span>
                                </div>
                                <div class="batch-list hidden">
                                    <?php foreach ($batches as $batch): 
                                        $exp_date = new DateTime($batch['expiry_date']);
                                        $is_expired = $exp_date < $today;
                                        $batch_status = $is_expired ? 'expired' : ($batch['quantity'] <= 20 ? 'low-stock' : 'high-stock');
                                    ?>
                                    <div class="batch-item-checkbox" data-type="<?= $batch['type'] ?>" data-status="<?= $batch_status ?>">
                                        <input type="checkbox" class="batch-checkbox" 
                                            id="restock-batch-<?= $batch['med_id'] ?>"
                                            data-med-id="<?= $batch['med_id'] ?>"
                                            data-med-name="<?= htmlspecialchars($med_name) ?>"
                                            data-stock="<?= $batch['quantity'] ?>"
                                            data-expiry="<?= htmlspecialchars($batch['expiry_date']) ?>"
                                            onchange="toggleRestockCard(this)">
                                        <label for="restock-batch-<?= $batch['med_id'] ?>" class="flex-1">
                                            <div>Stock: <?= $batch['quantity'] ?> | Exp: <?= date('m/d/Y', strtotime($batch['expiry_date'])) ?></div>
                                            <div class="text-xs text-gray-500">Batch ID: <?= $batch['med_id'] ?></div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Column: Selected Items -->
                    <div class="modal-column">
                        <h4 class="font-semibold text-gray-700 mb-3">Selected Items</h4>
                        <div class="selected-items-container" id="restockSelectedList">
                            <p id="no-restock-selection-msg" class="text-center text-gray-500 text-sm py-4">No medicines selected for restocking.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer flex justify-end gap-3 border-t border-gray-200 pt-4 mt-6">
                    <button type="button" onclick="closeRestockModal()" class="px-5 py-2.5 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium flex items-center gap-1">
                        <i class='bx bx-x'></i> Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-5 py-2.5 transition font-medium flex items-center gap-1">
                        <i class='bx bx-plus-medical'></i> Restock Selected
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Enhanced Deduct Modal with search, filters and grouped medicines -->
    <div id="deductModal" class="modal hidden fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 backdrop-blur-sm" style="z-index: 1000px;">
        <div class="modal-content bg-white rounded-2xl p-6 shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-y-auto">
            <!-- Header -->
            <div class="modal-header flex justify-between items-center border-b-2 border-red-200 pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class='bx bx-minus-circle text-red-600 text-2xl'></i>
                    <h2 class="text-lg font-bold text-gray-800">Deduct Medicines</h2>
                </div>
                <span class="close-btn cursor-pointer text-xl font-bold text-gray-400 hover:text-gray-600 transition" onclick="closeDeductModal()">&times;</span>
            </div>

            <form id="deductForm" action="Inventory.php" method="POST">
                <div class="modal-two-column">
                    <!-- Left Column: Medicine Selection with Search & Filters -->
                    <div class="modal-column">
                        <div class="modal-search-section">
                            <input type="text" id="deductSearch" placeholder="Search medicine..." class="w-full mb-2">
                            <div class="grid grid-cols-2 gap-2">
                                <select id="deductTypeFilter" class="w-full">
                                    <option value="">All Types</option>
                                    <option value="tablet">Tablet</option>
                                    <option value="capsule">Capsule</option>
                                    <option value="syrup">Syrup</option>
                                    <option value="injection">Injection</option>
                                    <option value="ointment">Ointment</option>
                                    <option value="other">Other</option>
                                </select>
                                <select id="deductStatusFilter" class="w-full">
                                    <option value="">All Status</option>
                                    <option value="active">Active Only</option>
                                    <option value="low-stock">Low Stock</option>
                                    <option value="high-stock">High Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="medicine-selection-container" id="deductSelectionList">
                            <?php foreach ($medicines_grouped_for_modals as $med_name => $batches): 
                                $first_batch = $batches[0];
                                $today = new DateTime();
                                $status = 'active';
                                $has_expired = false;
                                $has_low_stock = false;
                                
                                foreach ($batches as $batch) {
                                    $exp_date = new DateTime($batch['expiry_date']);
                                    if ($exp_date < $today) {
                                        $has_expired = true;
                                    }
                                    if ($batch['quantity'] <= 20) {
                                        $has_low_stock = true;
                                    }
                                }
                                
                                if ($has_expired) {
                                    $status = 'expired';
                                } elseif ($has_low_stock) {
                                    $status = 'low-stock';
                                } elseif ($batches[0]['quantity'] > 20) {
                                    $status = 'high-stock';
                                }
                            ?>
                            <div class="medicine-group-container" data-name="<?= htmlspecialchars($med_name) ?>" data-type="<?= $first_batch['type'] ?>" data-status="<?= $status ?>">
                                <div class="medicine-group-header" onclick="toggleBatchList(this)">
                                    <input type="checkbox" class="group-checkbox" data-med-name="<?= htmlspecialchars($med_name) ?>">
                                    <div class="flex-1">
                                        <div class="font-medium"><?= htmlspecialchars($med_name) ?></div>
                                        <div class="text-xs text-gray-500"><?= count($batches) ?> batches</div>
                                    </div>
                                    <span class="status-badge status-<?= $status ?>"><?= $status ?></span>
                                </div>
                                <div class="batch-list hidden">
                                    <?php foreach ($batches as $batch): 
                                        $exp_date = new DateTime($batch['expiry_date']);
                                        $is_expired = $exp_date < $today;
                                        $batch_status = $is_expired ? 'expired' : ($batch['quantity'] <= 20 ? 'low-stock' : 'high-stock');
                                    ?>
                                    <div class="batch-item-checkbox" data-type="<?= $batch['type'] ?>" data-status="<?= $batch_status ?>">
                                        <input type="checkbox" class="batch-checkbox" 
                                            id="deduct-batch-<?= $batch['med_id'] ?>"
                                            data-med-id="<?= $batch['med_id'] ?>"
                                            data-med-name="<?= htmlspecialchars($med_name) ?>"
                                            data-stock="<?= $batch['quantity'] ?>"
                                            data-expiry="<?= htmlspecialchars($batch['expiry_date']) ?>"
                                            onchange="toggleDeductCard(this)"
                                            <?= $is_expired ? 'disabled' : '' ?>>
                                        <label for="deduct-batch-<?= $batch['med_id'] ?>" class="flex-1 <?= $is_expired ? 'opacity-50' : '' ?>">
                                            <div>Stock: <?= $batch['quantity'] ?> | Exp: <?= date('m/d/Y', strtotime($batch['expiry_date'])) ?></div>
                                            <div class="text-xs text-gray-500">Batch ID: <?= $batch['med_id'] ?></div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Column: Selected Items -->
                    <div class="modal-column">
                        <h4 class="font-semibold text-gray-700 mb-3">Selected Items</h4>
                        <div class="selected-items-container" id="deductSelectedList">
                            <p id="no-deduct-selection-msg" class="text-center text-gray-500 text-sm py-4">No medicines selected for deduction.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer flex justify-end gap-3 border-t border-gray-200 pt-4 mt-6">
                    <button type="button" onclick="closeDeductModal()" class="px-5 py-2.5 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium flex items-center gap-1">
                        <i class='bx bx-x'></i> Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-5 py-2.5 transition font-medium flex items-center gap-1">
                        <i class='bx bx-minus-circle'></i> Deduct Selected
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Enhanced Multiple Dispose Modal with search, filters and grouped medicines -->
    <div id="multipleDisposeModal" class="modal hidden fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 backdrop-blur-sm" style="z-index: 1000px;">
        <div class="modal-content bg-white rounded-2xl p-6 shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-y-auto">
            <!-- Header -->
            <div class="modal-header flex justify-between items-center border-b-2 border-red-200 pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class='bx bx-trash text-red-600 text-2xl'></i>
                    <h2 class="text-lg font-bold text-gray-800">Dispose Multiple Medicines</h2>
                </div>
                <span class="close-btn cursor-pointer text-xl font-bold text-gray-400 hover:text-gray-600 transition" onclick="closeMultipleDisposeModal()">&times;</span>
            </div>

            <form id="multipleDisposeForm" action="Inventory.php" method="POST">
                <input type="hidden" name="multiple_dispose" value="1">
                
                <div class="modal-two-column">
                    <!-- Left Column: Medicine Selection with Search & Filters -->
                    <div class="modal-column">
                        <div class="modal-search-section">
                            <input type="text" id="disposeSearch" placeholder="Search medicine..." class="w-full mb-2">
                            <div class="grid grid-cols-2 gap-2">
                                <select id="disposeTypeFilter" class="w-full">
                                    <option value="">All Types</option>
                                    <option value="tablet">Tablet</option>
                                    <option value="capsule">Capsule</option>
                                    <option value="syrup">Syrup</option>
                                    <option value="injection">Injection</option>
                                    <option value="ointment">Ointment</option>
                                    <option value="other">Other</option>
                                </select>
                                <select id="disposeStatusFilter" class="w-full">
                                    <option value="">All Status</option>
                                    <option value="expired">Expired</option>
                                    <option value="active">Active</option>
                                    <option value="low-stock">Low Stock</option>
                                    <option value="high-stock">High Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="medicine-selection-container" id="disposeSelectionList">
                            <?php foreach ($medicines_grouped_for_modals as $med_name => $batches): 
                                $first_batch = $batches[0];
                                $today = new DateTime();
                                $status = 'active';
                                $has_expired = false;
                                $has_low_stock = false;
                                
                                foreach ($batches as $batch) {
                                    $exp_date = new DateTime($batch['expiry_date']);
                                    if ($exp_date < $today) {
                                        $has_expired = true;
                                    }
                                    if ($batch['quantity'] <= 20) {
                                        $has_low_stock = true;
                                    }
                                }
                                
                                if ($has_expired) {
                                    $status = 'expired';
                                } elseif ($has_low_stock) {
                                    $status = 'low-stock';
                                } elseif ($batches[0]['quantity'] > 20) {
                                    $status = 'high-stock';
                                }
                            ?>
                            <div class="medicine-group-container" data-name="<?= htmlspecialchars($med_name) ?>" data-type="<?= $first_batch['type'] ?>" data-status="<?= $status ?>">
                                <div class="medicine-group-header" onclick="toggleBatchList(this)">
                                    <input type="checkbox" class="group-checkbox" data-med-name="<?= htmlspecialchars($med_name) ?>">
                                    <div class="flex-1">
                                        <div class="font-medium"><?= htmlspecialchars($med_name) ?></div>
                                        <div class="text-xs text-gray-500"><?= count($batches) ?> batches</div>
                                    </div>
                                    <span class="status-badge status-<?= $status ?>"><?= $status ?></span>
                                </div>
                                <div class="batch-list hidden">
                                    <?php foreach ($batches as $batch): 
                                        $exp_date = new DateTime($batch['expiry_date']);
                                        $is_expired = $exp_date < $today;
                                        $batch_status = $is_expired ? 'expired' : ($batch['quantity'] <= 20 ? 'low-stock' : 'high-stock');
                                    ?>
                                    <div class="batch-item-checkbox" data-type="<?= $batch['type'] ?>" data-status="<?= $batch_status ?>">
                                        <input type="checkbox" class="batch-checkbox" 
                                            id="dispose-batch-<?= $batch['med_id'] ?>"
                                            data-med-id="<?= $batch['med_id'] ?>"
                                            data-med-name="<?= htmlspecialchars($med_name) ?>"
                                            data-stock="<?= $batch['quantity'] ?>"
                                            data-expiry="<?= htmlspecialchars($batch['expiry_date']) ?>"
                                            onchange="toggleDisposeCard(this)">
                                        <label for="dispose-batch-<?= $batch['med_id'] ?>" class="flex-1">
                                            <div>Stock: <?= $batch['quantity'] ?> | Exp: <?= date('m/d/Y', strtotime($batch['expiry_date'])) ?></div>
                                            <div class="text-xs text-gray-500">Batch ID: <?= $batch['med_id'] ?></div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Column: Selected Items -->
                    <div class="modal-column">
                        <h4 class="font-semibold text-gray-700 mb-3">Selected Items</h4>
                        <div class="selected-items-container" id="disposeSelectedList">
                            <p id="no-dispose-selection-msg" class="text-center text-gray-500 text-sm py-4">No medicines selected for disposal.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer flex justify-end gap-3 border-t border-gray-200 pt-4 mt-6">
                    <button type="button" onclick="closeMultipleDisposeModal()" class="px-5 py-2.5 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium flex items-center gap-1">
                        <i class='bx bx-x'></i> Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-5 py-2.5 transition font-medium flex items-center gap-1">
                        <i class='bx bx-trash'></i> Dispose Selected
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <!-- Enhanced Single Dispose Modal with full stock disposal -->
    <div id="disposeModal" class="modal hidden fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 backdrop-blur-sm" style="z-index: 1000px;">
        <div class="modal-content bg-white rounded-2xl p-6 shadow-2xl w-full max-w-lg">
            <!-- Enhanced Header -->
            <div class="modal-header flex justify-between items-center border-b-2 border-red-200 pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class='bx bx-trash text-red-600 text-2xl'></i>
                    <h2 class="text-lg font-bold text-gray-800">Dispose Medicine</h2>
                </div>
                <span class="close-btn cursor-pointer text-xl font-bold text-gray-400 hover:text-gray-600 transition" onclick="closeDisposeModal()">&times;</span>
            </div>

            <form id="disposeForm" action="Inventory.php" method="POST">
                <input type="hidden" id="dispose-med-id" name="med_id">
                <input type="hidden" name="single_dispose" value="1">
                <input type="hidden" id="dispose_quantity" name="quantity">

                <!-- Medicine Info Card -->
                <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg border-2 border-red-200">
                    <div class="flex justify-between text-xs text-gray-600 mb-2 font-semibold">
                        <span>Medicine Name</span>
                        <span>Current Stock</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-capsule text-red-600'></i>
                            <span id="dispose-med-name-text"></span>
                        </div>
                        <div id="dispose-med-stock" class="text-2xl font-extrabold text-red-600"></div>
                    </div>
                    <div class="mt-3 p-3 bg-red-100 rounded-lg">
                        <div class="text-sm font-semibold text-red-800 flex items-center gap-2">
                            <i class='bx bx-info-circle'></i>
                            <span>Full Stock Disposal</span>
                        </div>
                        <p class="text-xs text-red-600 mt-1">All <span id="dispose-full-quantity"></span> units will be disposed. Quantity will go to 0.</p>
                    </div>
                </div>

                <!-- Form Inputs -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-folder text-blue-500'></i> Reason:
                    </label>
                    <select id="disposal_type" name="type" required
                        class="w-full rounded-lg border-2 border-gray-300 py-2.5 px-3 focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm transition">
                        <option value="">Select Reason</option>
                        <option value="expired">❌ Expired</option>
                        <option value="damaged">⚠️ Damaged</option>
                        <option value="recall">🚫 Product Recall</option>
                        <option value="contamination">🧫 Contamination</option>
                        <option value="other">📝 Other</option>
                    </select>
                </div>

                <!-- Detailed Reason -->
                <div class="mb-6">
                    <label for="dispose_reason" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-notepad text-blue-500'></i> Detailed Reason (Optional):
                    </label>
                    <textarea id="dispose_reason" name="reason" rows="3"
                        class="w-full rounded-lg border-2 border-gray-300 py-2.5 px-3 focus:border-red-500 focus:ring-2 focus:ring-red-200 shadow-sm transition"
                        placeholder="Enter additional details about disposal..."></textarea>
                </div>

                <input type="hidden" name="disposed_by" value="<?php echo htmlspecialchars($_SESSION['username'] ?? 'System'); ?>">

                <!-- Footer -->
                <div class="modal-footer flex justify-end gap-3 border-t border-gray-200 pt-4">
                    <button type="button" onclick="closeDisposeModal()" class="px-5 py-2.5 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium flex items-center gap-1">
                        <i class='bx bx-x'></i> Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-5 py-2.5 transition font-medium flex items-center gap-1">
                        <i class='bx bx-trash'></i> Confirm Full Disposal
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Usage Report Modal -->
    <div id="usageModal" class="modal hidden fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 backdrop-blur-sm" style="z-index: 1000px;">
        <div class="modal-content bg-white rounded-2xl p-6 shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="modal-header flex justify-between items-center border-b-2 border-blue-200 pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class='bx bx-trending-up text-blue-600 text-2xl'></i>
                    <h2 class="text-lg font-bold text-gray-800">Medicine Usage Report</h2>
                </div>
                <span class="close-btn cursor-pointer text-xl font-bold text-gray-400 hover:text-gray-600 transition" onclick="closeUsageModal()">&times;</span>
            </div>

            <!-- Date Filters -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-gray-700 mb-3">Filter by Date Range</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                        <input type="date" id="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                        <input type="date" id="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="flex items-end">
                        <button onclick="loadUsageChart()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class='bx bx-filter'></i> Apply Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="mb-6">
                <canvas id="usageChart"></canvas>
            </div>

            <!-- Data Table -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-700 mb-3">Usage Data</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg" id="usageTable">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b text-left">Date</th>
                                <th class="py-2 px-4 border-b text-left">Medicine Name</th>
                                <th class="py-2 px-4 border-b text-left">Quantity Used</th>
                            </tr>
                        </thead>
                        <tbody id="usageTableBody">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-4 border-t border-gray-200">
                <div>
                    <button onclick="printUsageReport()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i class='bx bx-printer'></i> Print Report
                    </button>
                </div>
                <div class="flex gap-2">
                    <button onclick="closeUsageModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                        <i class='bx bx-x'></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toast Notification System
        class Toast {
            constructor(message, type = 'info', duration = 5000) {
                this.message = message;
                this.type = type;
                this.duration = duration;
                this.toastElement = null;
                this.progressBar = null;
                this.timeout = null;
            }

            show() {
                const toastContainer = document.getElementById('toastContainer');
                
                // Create toast element
                this.toastElement = document.createElement('div');
                this.toastElement.className = `toast ${this.type}`;
                
                // Determine icon based on type
                let icon = 'bx-info-circle';
                let title = 'Information';
                switch(this.type) {
                    case 'success':
                        icon = 'bx-check-circle';
                        title = 'Success';
                        break;
                    case 'error':
                        icon = 'bx-error-circle';
                        title = 'Error';
                        break;
                    case 'warning':
                        icon = 'bx-error';
                        title = 'Warning';
                        break;
                }

                this.toastElement.innerHTML = `
                    <div class="toast-header">
                        <div class="toast-title">
                            <i class='bx ${icon}'></i>
                            ${title}
                        </div>
                        <button class="toast-close" onclick="this.closest('.toast').remove()">
                            <i class='bx bx-x'></i>
                        </button>
                    </div>
                    <div class="toast-body">${this.message}</div>
                    <div class="toast-progress">
                        <div class="toast-progress-bar"></div>
                    </div>
                `;

                // Add to container
                toastContainer.appendChild(this.toastElement);

                // Trigger animation
                setTimeout(() => {
                    this.toastElement.classList.add('show');
                    this.startProgressBar();
                }, 10);

                // Auto remove after duration
                this.timeout = setTimeout(() => {
                    this.remove();
                }, this.duration);

                // Add click to dismiss
                this.toastElement.addEventListener('click', (e) => {
                    if (!e.target.closest('.toast-close')) {
                        this.remove();
                    }
                });
            }

            startProgressBar() {
                this.progressBar = this.toastElement.querySelector('.toast-progress-bar');
                this.progressBar.style.transition = `width ${this.duration}ms linear`;
                this.progressBar.style.width = '0%';
                
                setTimeout(() => {
                    this.progressBar.style.width = '100%';
                }, 10);
            }

            remove() {
                if (this.toastElement) {
                    this.toastElement.classList.remove('show');
                    setTimeout(() => {
                        if (this.toastElement.parentNode) {
                            this.toastElement.parentNode.removeChild(this.toastElement);
                        }
                    }, 300);
                }
                if (this.timeout) {
                    clearTimeout(this.timeout);
                }
            }
        }

        function showToast(message, type = 'info', duration = 5000) {
            new Toast(message, type, duration).show();
        }

        // Floating Action Button
        const fabMain = document.getElementById('fabMain');
        const fabActions = document.getElementById('fabActions');

        if (fabMain && fabActions) {
            fabMain.addEventListener('click', () => {
                fabMain.classList.toggle('active');
                fabActions.classList.toggle('show');
            });

            // Close FAB when clicking outside
            document.addEventListener('click', (e) => {
                if (!fabMain.contains(e.target) && !fabActions.contains(e.target)) {
                    fabMain.classList.remove('active');
                    fabActions.classList.remove('show');
                }
            });
        }

        // Toggle batch list in modals
        function toggleBatchList(header) {
            const batchList = header.nextElementSibling;
            batchList.classList.toggle('hidden');
        }

        // Filter medicines in modal selection
        function filterMedicines(modalId, searchId, typeId, statusId) {
            const searchTerm = document.getElementById(searchId).value.toLowerCase();
            const typeFilter = document.getElementById(typeId).value;
            const statusFilter = document.getElementById(statusId).value;
            
            const groups = document.querySelectorAll(`#${modalId} .medicine-group-container`);
            
            groups.forEach(group => {
                const name = group.dataset.name.toLowerCase();
                const type = group.dataset.type;
                const status = group.dataset.status;
                
                const matchesSearch = name.includes(searchTerm);
                const matchesType = !typeFilter || type === typeFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                
                if (matchesSearch && matchesType && matchesStatus) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            });
        }

        // Setup search and filter for restock modal
        document.getElementById('restockSearch')?.addEventListener('input', () => {
            filterMedicines('restockSelectionList', 'restockSearch', 'restockTypeFilter', 'restockStatusFilter');
        });
        
        document.getElementById('restockTypeFilter')?.addEventListener('change', () => {
            filterMedicines('restockSelectionList', 'restockSearch', 'restockTypeFilter', 'restockStatusFilter');
        });
        
        document.getElementById('restockStatusFilter')?.addEventListener('change', () => {
            filterMedicines('restockSelectionList', 'restockSearch', 'restockTypeFilter', 'restockStatusFilter');
        });

        // Setup search and filter for deduct modal
        document.getElementById('deductSearch')?.addEventListener('input', () => {
            filterMedicines('deductSelectionList', 'deductSearch', 'deductTypeFilter', 'deductStatusFilter');
        });
        
        document.getElementById('deductTypeFilter')?.addEventListener('change', () => {
            filterMedicines('deductSelectionList', 'deductSearch', 'deductTypeFilter', 'deductStatusFilter');
        });
        
        document.getElementById('deductStatusFilter')?.addEventListener('change', () => {
            filterMedicines('deductSelectionList', 'deductSearch', 'deductTypeFilter', 'deductStatusFilter');
        });

        // Setup search and filter for dispose modal
        document.getElementById('disposeSearch')?.addEventListener('input', () => {
            filterMedicines('disposeSelectionList', 'disposeSearch', 'disposeTypeFilter', 'disposeStatusFilter');
        });
        
        document.getElementById('disposeTypeFilter')?.addEventListener('change', () => {
            filterMedicines('disposeSelectionList', 'disposeSearch', 'disposeTypeFilter', 'disposeStatusFilter');
        });
        
        document.getElementById('disposeStatusFilter')?.addEventListener('change', () => {
            filterMedicines('disposeSelectionList', 'disposeSearch', 'disposeTypeFilter', 'disposeStatusFilter');
        });

        // Group checkbox functionality
        document.querySelectorAll('.group-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const medName = this.dataset.medName;
                const batchCheckboxes = document.querySelectorAll(`.batch-checkbox[data-med-name="${medName}"]`);
                batchCheckboxes.forEach(batchCheckbox => {
                    if (!batchCheckbox.disabled) {
                        batchCheckbox.checked = this.checked;
                        batchCheckbox.dispatchEvent(new Event('change'));
                    }
                });
            });
        });

        function toggleMedicineGroup(event) {
            const header = event.currentTarget;
            const card = header.closest('.medicine-card-group');
            const details = card.querySelector('.medicine-details');
            const icon = header.querySelector('i');
            
            details.classList.toggle('hidden');
            icon.classList.toggle('rotate-90');
        }

        function changePage(direction) {
            const currentPage = parseInt(document.getElementById('currentPage').textContent);
            const totalPages = parseInt(document.getElementById('totalPages').textContent);
            const itemsPerPage = document.getElementById('itemsPerPage').value;
            
            let newPage = currentPage + direction;
            
            if (newPage < 1) newPage = 1;
            if (newPage > totalPages) newPage = totalPages;
            
            // Get current filter values
            const searchTerm = document.getElementById('searchInput').value;
            const stockFilter = document.getElementById('stockFilter').value;
            const expiryFilter = document.getElementById('expiryFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const expirySort = document.getElementById('expirySortDropdown').value;
            
            // Build URL with filters
            let url = `?page=${newPage}&items_per_page=${itemsPerPage}`;
            if (searchTerm) url += `&search=${encodeURIComponent(searchTerm)}`;
            if (stockFilter) url += `&stock_filter=${stockFilter}`;
            if (expiryFilter) url += `&expiry_filter=${expiryFilter}`;
            if (typeFilter) url += `&type_filter=${typeFilter}`;
            if (expirySort) url += `&sort=${expirySort}`;
            
            window.location.href = url;
        }

        function changeItemsPerPage() {
            const itemsPerPage = document.getElementById('itemsPerPage').value;
            
            // Get current filter values
            const searchTerm = document.getElementById('searchInput').value;
            const stockFilter = document.getElementById('stockFilter').value;
            const expiryFilter = document.getElementById('expiryFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const expirySort = document.getElementById('expirySortDropdown').value;
            
            // Build URL with filters
            let url = `?page=1&items_per_page=${itemsPerPage}`;
            if (searchTerm) url += `&search=${encodeURIComponent(searchTerm)}`;
            if (stockFilter) url += `&stock_filter=${stockFilter}`;
            if (expiryFilter) url += `&expiry_filter=${expiryFilter}`;
            if (typeFilter) url += `&type_filter=${typeFilter}`;
            if (expirySort) url += `&sort=${expirySort}`;
            
            window.location.href = url;
        }

        // Parse description to extract purposes and severity
        function parseDescription(description) {
            let purposes = [];
            let severity = 'mild';
            
            if (description.includes('[Severity: ')) {
                const parts = description.split('[Severity: ');
                const purposeText = parts[0].trim();
                purposes = purposeText.split('; ').filter(p => p.trim() !== '');
                severity = parts[1].replace(']', '').trim();
            } else if (description) {
                purposes = description.split('; ').filter(p => p.trim() !== '');
            }
            
            return { purposes, severity };
        }

        document.getElementById('addMedicineForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const result = await response.json();

                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    closeAddMedicineModal();
                    updateMedicineGrid();
                    form.reset();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            }
        });
        
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.querySelector('.main-content');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
            }
        });

        function showAddMedicineModal() {
            document.getElementById('addMedicineModal').style.display = 'flex';
            fabMain.classList.remove('active');
            fabActions.classList.remove('show');
        }

        function closeAddMedicineModal() {
            document.getElementById('addMedicineModal').style.display = 'none';
            document.getElementById('addMedicineForm').reset();
        }

        function showEditModal(med_id, name, type, description) {
            document.getElementById('edit_med_id').value = med_id;
            document.getElementById('edit_med_name').value = name;
            document.getElementById('edit_med_type').value = type;
            
            // Parse description to pre-fill checkboxes and severity
            const { purposes, severity } = parseDescription(description);
            
            // Set severity radio button
            document.getElementById(`edit_severity_${severity}`).checked = true;
            
            // Uncheck all checkboxes first
            document.querySelectorAll('#editMedicineForm input[name="med_purposes[]"]').forEach(cb => {
                cb.checked = false;
            });
            
            // Check the appropriate purposes
            purposes.forEach(purpose => {
                const checkbox = document.querySelector(`#editMedicineForm input[value="${purpose}"]`);
                if (checkbox) checkbox.checked = true;
            });
            
            document.getElementById('editMedicineModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editMedicineModal').style.display = 'none';
            document.getElementById('editMedicineForm').reset();
        }

        function showMultiRestockModal() {
            document.getElementById('restockModal').style.display = 'flex';
            fabMain.classList.remove('active');
            fabActions.classList.remove('show');
        }

        function closeRestockModal() {
            document.getElementById('restockModal').style.display = 'none';
            const checkboxes = document.querySelectorAll('#restockModal input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('restockSelectedList').innerHTML = '<p id="no-restock-selection-msg" class="text-center text-gray-500 text-sm py-4">No medicines selected for restocking.</p>';
        }
        
        function showMultiDeductModal() {
            document.getElementById('deductModal').style.display = 'flex';
            fabMain.classList.remove('active');
            fabActions.classList.remove('show');
        }

        function closeDeductModal() {
            document.getElementById('deductModal').style.display = 'none';
            const checkboxes = document.querySelectorAll('#deductModal input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('deductSelectedList').innerHTML = '<p id="no-deduct-selection-msg" class="text-center text-gray-500 text-sm py-4">No medicines selected for deduction.</p>';
        }

        function showMultipleDisposeModal() {
            document.getElementById('multipleDisposeModal').style.display = 'flex';
            fabMain.classList.remove('active');
            fabActions.classList.remove('show');
        }

        function closeMultipleDisposeModal() {
            document.getElementById('multipleDisposeModal').style.display = 'none';
            const checkboxes = document.querySelectorAll('#multipleDisposeModal input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('disposeSelectedList').innerHTML = '<p id="no-dispose-selection-msg" class="text-center text-gray-500 text-sm py-4">No medicines selected for disposal.</p>';
        }

        function showDisposeModal(id, name, quantity) {
            document.getElementById('dispose-med-id').value = id;
            document.getElementById('dispose-med-name-text').textContent = name;
            document.getElementById('dispose-med-stock').textContent = quantity;
            document.getElementById('dispose_quantity').value = quantity;
            document.getElementById('dispose-full-quantity').textContent = quantity;
            document.getElementById('dispose_reason').value = '';
            document.getElementById('disposal_type').value = 'expired';

            document.getElementById('disposeModal').style.display = 'flex';
        }

        function closeDisposeModal() {
            document.getElementById('disposeModal').style.display = 'none';
            document.getElementById('dispose_reason').value = '';
        }
        
        function toggleRestockCard(checkbox) {
            const medId = checkbox.dataset.medId;
            const name = checkbox.dataset.medName;
            const stock = checkbox.dataset.stock;
            const expiry = checkbox.dataset.expiry;
            const selectedList = document.getElementById('restockSelectedList');
            const noSelectionMsg = document.getElementById('no-restock-selection-msg');
            
            if (checkbox.checked) {
                const card = document.createElement('div');
                card.className = 'selected-medicine-card';
                card.id = `restock-card-${medId}`;
                
                const exp_date = new Date(expiry);
                const today = new Date();
                const is_expired = exp_date < today;
                const status_badge = is_expired ? 'Expired' : 'Active';
                const status_color = is_expired ? 'bg-red-600 text-white' : 'bg-green-600 text-white';
                
                card.innerHTML = `
                    <div class="selected-medicine-header">
                        <span class="font-semibold text-gray-800">${name}</span>
                        <span class="text-xs ${status_color} px-2 py-1 rounded">${status_badge}</span>
                    </div>
                    <div class="text-sm text-gray-600 mb-2">Current Stock: ${stock}</div>
                    <div class="input-group space-y-2">
                        <div>
                            <label for="restock-qty-${medId}" class="text-sm font-medium text-gray-700">Quantity to Add:</label>
                            <input type="number" id="restock-qty-${medId}" name="restock_items[${medId}][quantity]" min="1" required class="w-full mt-1 px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label for="restock-exp-${medId}" class="text-sm font-medium text-gray-700">New Expiry Date:</label>
                            <input type="date" id="restock-exp-${medId}" name="restock_items[${medId}][expiry_date]" required class="w-full mt-1 px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" value="${expiry}">
                        </div>
                    </div>
                `;
                selectedList.appendChild(card);
            } else {
                const itemToRemove = document.getElementById(`restock-card-${medId}`);
                if (itemToRemove) {
                    itemToRemove.remove();
                }
            }
            
            if (noSelectionMsg) {
                noSelectionMsg.style.display = selectedList.children.length > 1 ? 'none' : 'block';
            }
        }

        function showRestockModalForBatch(med_id, name, stock, expiry) {
            showMultiRestockModal();
            
            setTimeout(() => {
                const checkbox = document.querySelector(`#restockModal input[data-med-id="${med_id}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                    toggleRestockCard(checkbox);
                    
                    const qtyInput = document.querySelector(`#restockSelectedList input[name="restock_items[${med_id}][quantity]"]`);
                    const expInput = document.querySelector(`#restockSelectedList input[name="restock_items[${med_id}][expiry_date]"]`);
                    
                    if (qtyInput) qtyInput.value = 1;
                    if (expInput) expInput.value = expiry;
                }
            }, 100);
        }

        function toggleDeductCard(checkbox) {
            const medId = checkbox.dataset.medId;
            const name = checkbox.dataset.medName;
            const stock = checkbox.dataset.stock;
            const selectedList = document.getElementById('deductSelectedList');
            const noSelectionMsg = document.getElementById('no-deduct-selection-msg');
            
            if (checkbox.checked) {
                const card = document.createElement('div');
                card.className = 'selected-medicine-card';
                card.id = `deduct-card-${medId}`;
                card.innerHTML = `
                    <div class="selected-medicine-header">
                        <span class="font-semibold text-gray-800">${name}</span>
                        <span class="text-xs bg-green-600 text-white px-2 py-1 rounded">Active</span>
                    </div>
                    <div class="text-sm text-gray-600 mb-2">Current Stock: ${stock}</div>
                    <div class="input-group">
                        <label for="deduct-qty-${medId}" class="text-sm font-medium text-gray-700">Quantity to Deduct:</label>
                        <input type="number" id="deduct-qty-${medId}" name="deduct_items[${medId}]" min="1" max="${stock}" required class="w-full mt-1 px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                    </div>
                `;
                selectedList.appendChild(card);
            } else {
                const itemToRemove = document.getElementById(`deduct-card-${medId}`);
                if (itemToRemove) {
                    itemToRemove.remove();
                }
            }
            
            if (noSelectionMsg) {
                noSelectionMsg.style.display = selectedList.children.length > 1 ? 'none' : 'block';
            }
        }

        function toggleDisposeCard(checkbox) {
            const medId = checkbox.dataset.medId;
            const name = checkbox.dataset.medName;
            const stock = checkbox.dataset.stock;
            const selectedList = document.getElementById('disposeSelectedList');
            const noSelectionMsg = document.getElementById('no-dispose-selection-msg');
            
            if (checkbox.checked) {
                const card = document.createElement('div');
                card.className = 'selected-medicine-card';
                card.id = `dispose-card-${medId}`;
                card.innerHTML = `
                    <div class="selected-medicine-header">
                        <span class="font-semibold text-gray-800">${name}</span>
                        <span class="text-xs bg-green-600 text-white px-2 py-1 rounded">Active</span>
                    </div>
                    <div class="text-sm text-gray-600 mb-2">Current Stock: ${stock}</div>
                    <div class="input-group space-y-2">
                        <div>
                            <label for="dispose-qty-${medId}" class="text-sm font-medium text-gray-700">Quantity to Dispose (Full Stock):</label>
                            <input type="hidden" id="dispose-qty-${medId}" name="dispose_items[${medId}][quantity]" value="${stock}">
                            <div class="mt-1 px-2 py-1 bg-red-100 text-red-800 rounded-lg font-semibold">${stock} units (Full Stock)</div>
                        </div>
                        <div>
                            <label for="dispose-reason-${medId}" class="text-sm font-medium text-gray-700">Reason:</label>
                            <select id="dispose-reason-${medId}" name="dispose_items[${medId}][reason]" class="w-full mt-1 px-2 py-1 border border-gray-300 rounded-lg">
                                <option value="expired">Expired</option>
                                <option value="damaged">Damaged</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                `;
                selectedList.appendChild(card);
            } else {
                const itemToRemove = document.getElementById(`dispose-card-${medId}`);
                if (itemToRemove) {
                    itemToRemove.remove();
                }
            }
            
            if (noSelectionMsg) {
                noSelectionMsg.style.display = selectedList.children.length > 1 ? 'none' : 'block';
            }
        }

        document.getElementById('restockForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const quantityInputs = document.querySelectorAll('#restockSelectedList input[type="number"]');
            if (quantityInputs.length === 0) {
                showToast('Please select at least one medicine to restock.', 'warning');
                return;
            }

            for (const input of quantityInputs) {
                if (input.value.trim() === '' || parseInt(input.value) <= 0) {
                    showToast('Please enter a valid quantity for all selected medicines.', 'warning');
                    return;
                }
            }
            
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const result = await response.json();

                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    closeRestockModal();
                    updateMedicineGrid();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred during restocking. Please try again.', 'error');
            }
        });

        document.getElementById('deductForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const quantityInputs = document.querySelectorAll('#deductSelectedList input[type="number"]');
            if (quantityInputs.length === 0) {
                showToast('Please select at least one medicine to deduct.', 'warning');
                return;
            }
            
            for (const input of quantityInputs) {
                if (input.value.trim() === '' || parseInt(input.value) <= 0 || parseInt(input.value) > parseInt(input.max)) {
                    showToast('Please enter a valid quantity for all selected medicines. Quantity cannot exceed current stock.', 'warning');
                    return;
                }
            }

            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const result = await response.json();

                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    closeDeductModal();
                    updateMedicineGrid();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred during deduction. Please try again.', 'error');
            }
        });

        document.getElementById('disposeForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const result = await response.json();

                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    closeDisposeModal();
                    updateMedicineGrid();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred during disposal. Please try again.', 'error');
            }
        });

        document.getElementById('multipleDisposeForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const hiddenInputs = document.querySelectorAll('#disposeSelectedList input[type="hidden"]');
            if (hiddenInputs.length === 0) {
                showToast('Please select at least one medicine to dispose.', 'warning');
                return;
            }

            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const result = await response.json();

                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    closeMultipleDisposeModal();
                    updateMedicineGrid();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred during disposal. Please try again.', 'error');
            }
        });

        document.getElementById('editMedicineForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const result = await response.json();

                if (result.status === 'success') {
                    showToast(result.message, 'success');
                    closeEditModal();
                    updateMedicineGrid();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            }
        });
        
        async function updateMedicineGrid(page = 1) {
            const searchTerm = document.getElementById('searchInput').value;
            const stockFilter = document.getElementById('stockFilter').value;
            const expiryFilter = document.getElementById('expiryFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const expirySortValue = document.getElementById('expirySortDropdown').value;
            const medicineGrid = document.getElementById('medicineGrid');
            const itemsPerPage = document.getElementById('itemsPerPage').value;

            const params = new URLSearchParams({
                action: 'fetch_medicines',
                search: searchTerm,
                stock_filter: stockFilter,
                expiry_filter: expiryFilter,
                type_filter: typeFilter,
                sort: expirySortValue,
                items_per_page: itemsPerPage,
                page: page
            });

            const url = `?${params.toString()}`;
            
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const newHtml = await response.text();
                medicineGrid.innerHTML = newHtml;
                
                // Update pagination buttons
                updatePaginationButtons();
            } catch (error) {
                console.error('Error:', error);
                medicineGrid.innerHTML = '<p class="text-red-600">Error loading medicines. Please try again.</p>';
            }
        }

        function setFilter(filterType) {
            // Reset all filters
            document.getElementById('stockFilter').value = '';
            document.getElementById('expiryFilter').value = '';
            document.getElementById('typeFilter').value = '';
            document.getElementById('expirySortDropdown').value = '';

            switch(filterType) {
                case 'total':
                    // No filters, show all
                    break;
                case 'low_stock':
                    document.getElementById('stockFilter').value = 'low';
                    break;
                case 'near_expiry':
                    document.getElementById('expiryFilter').value = 'near_expiry';
                    break;
                case 'expired':
                    document.getElementById('expiryFilter').value = 'expired';
                    break;
            }

            updateMedicineGrid(1);
        }

        function toggleFilterControls() {
            const filterControls = document.getElementById('filterControls');
            filterControls.classList.toggle('show');
        }

        function updatePaginationButtons() {
            const currentPage = <?= $page ?>;
            const totalPages = <?= $total_pages ?>;
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            if (prevBtn) prevBtn.disabled = (currentPage <= 1);
            if (nextBtn) nextBtn.disabled = (currentPage >= totalPages);
        }

        // Usage Modal Functions
        let usageChart = null;

        function showUsageModal() {
            document.getElementById('usageModal').style.display = 'flex';
            
            // Set default dates (last 30 days)
            const endDate = new Date();
            const startDate = new Date();
            startDate.setDate(startDate.getDate() - 30);
            
            document.getElementById('startDate').value = startDate.toISOString().split('T')[0];
            document.getElementById('endDate').value = endDate.toISOString().split('T')[0];
            
            loadUsageChart();
        }

        function closeUsageModal() {
            document.getElementById('usageModal').style.display = 'none';
            if (usageChart) {
                usageChart.destroy();
                usageChart = null;
            }
        }

        async function loadUsageChart() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            try {
                const response = await fetch(`?action=get_usage_data&start_date=${startDate}&end_date=${endDate}`);
                if (!response.ok) throw new Error('Network error');
                
                const data = await response.json();
                
                // Update chart
                const ctx = document.getElementById('usageChart').getContext('2d');
                
                if (usageChart) {
                    usageChart.destroy();
                }
                
                usageChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Medicine Usage Over Time'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Quantity Used'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            }
                        }
                    }
                });
                
                // Update table (simplified version - in real app you'd fetch detailed data)
                const tableBody = document.getElementById('usageTableBody');
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-4">Loading detailed data...</td></tr>';
                
            } catch (error) {
                console.error('Error loading usage chart:', error);
                showToast('Error loading usage data', 'error');
            }
        }

        function printUsageReport() {
            window.print();
        }

        // Load initial medicines on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePaginationButtons();
            
            // Event listeners for search and filter dropdowns
            document.getElementById('searchInput').addEventListener('keyup', () => updateMedicineGrid(1));
            document.getElementById('stockFilter').addEventListener('change', () => updateMedicineGrid(1));
            document.getElementById('expiryFilter').addEventListener('change', () => updateMedicineGrid(1));
            document.getElementById('typeFilter').addEventListener('change', () => updateMedicineGrid(1));
            document.getElementById('expirySortDropdown').addEventListener('change', () => updateMedicineGrid(1));
            document.getElementById('itemsPerPage').addEventListener('change', changeItemsPerPage);
        });
    </script>
    
    <!-- Check-in Modal -->
    <?php include '../Modals/Checkin_modal.php'; ?>
</body>
</html>
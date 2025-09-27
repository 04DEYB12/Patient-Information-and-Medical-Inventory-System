<?php
session_start();
include '../Landing Repository/Connection.php';

if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('Please login first!'); window.location.href = 'Loginpage.php';</script>";
    exit();
}

$user_id = $_SESSION['User_ID'];
require_once '../Functions/Queries.php';

if (!isset($con)) {
    die("Database connection is not available.");
}

$admin_name = 'Admin'; 

// --- Handle Add Medicine Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_medicine_submit'])) {
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if ($is_ajax) {
        header('Content-Type: application/json');
    }

    $med_name = $con->real_escape_string($_POST['med_name']);
    $med_description = $con->real_escape_string($_POST['med_description']);
    $med_quantity = (int)$_POST['med_quantity'];
    $med_type = $con->real_escape_string($_POST['med_type']);
    $med_expiry = $con->real_escape_string($_POST['med_expiry']);
    $added_by = $admin_name;

    try {
        if ($med_quantity <= 0) {
            throw new Exception("Quantity must be greater than zero.");
        }

        $stmt = $con->prepare("INSERT INTO medicine (name, description, quantity, type, expiry_date, added_by) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $con->error);
        }
        $stmt->bind_param("ssisss", $med_name, $med_description, $med_quantity, $med_type, $med_expiry, $added_by);

        if ($stmt->execute()) {
            if ($is_ajax) {
                echo json_encode(['status' => 'success', 'message' => 'Medicine added successfully!']);
            } else {
                echo "<script>alert('Medicine added successfully!'); window.location.href = 'Inventory.php';</script>";
            }
        } else {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        if ($is_ajax) {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        } else {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'Inventory.php';</script>";
        }
    }
    exit;
}

// --- Handle Deduct Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deduct_items'])) {
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    if ($is_ajax) {
        header('Content-Type: application/json');
    }

    if (!empty($_POST['deduct_items'])) {
        $deduct_items = $_POST['deduct_items'];
        $success = true;

        $con->begin_transaction();

        try {
            foreach ($deduct_items as $med_id => $quantity_to_deduct) {
                $med_id = $con->real_escape_string($med_id);
                $quantity_to_deduct = (int)$quantity_to_deduct;

                if ($quantity_to_deduct <= 0) {
                    continue; 
                }

                // Get the medicine details and lock the row
                $stmt = $con->prepare("SELECT quantity, name FROM medicine WHERE med_id = ? FOR UPDATE");
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $con->error);
                }
                $stmt->bind_param("i", $med_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $medicine = $result->fetch_assoc();
                $stmt->close();

                if ($medicine) {
                    $current_stock = (int)$medicine['quantity'];
                    if ($current_stock < $quantity_to_deduct) {
                        throw new Exception("Not enough stock for " . htmlspecialchars($medicine['name']) . ". Available: " . $current_stock);
                    }

                    $new_stock = $current_stock - $quantity_to_deduct;

                    // Update the quantity
                    $update_stmt = $con->prepare("UPDATE medicine SET quantity = ? WHERE med_id = ?");
                    if (!$update_stmt) {
                        throw new Exception("Update statement failed: " . $con->error);
                    }
                    $update_stmt->bind_param("ii", $new_stock, $med_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                    
                    // Log the usage
                    $usage_stmt = $con->prepare("INSERT INTO medicine_usage (med_id, quantity_used) VALUES (?, ?)");
                    if (!$usage_stmt) {
                        throw new Exception("Usage log statement failed: " . $con->error);
                    }
                    $usage_stmt->bind_param("ii", $med_id, $quantity_to_deduct);
                    $usage_stmt->execute();
                    $usage_stmt->close();
                } else {
                    throw new Exception("Medicine with ID $med_id not found.");
                }
            }
            
            $con->commit();
            
            if ($is_ajax) {
                echo json_encode(['status' => 'success', 'message' => 'Deduction successful!']);
            } else {
                echo "<script>alert('Deduction successful!'); window.location.href = 'Inventory.php';</script>";
            }
        } catch (Exception $e) {
            $con->rollback();
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
            } else {
                echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'Inventory.php';</script>";
            }
        }
    } else {
        if ($is_ajax) {
            echo json_encode(['status' => 'error', 'message' => 'Please select at least one medicine to deduct.']);
        } else {
            echo "<script>alert('Please select at least one medicine to deduct.'); window.location.href = 'Inventory.php';</script>";
        }
    }
    exit; 
}


// --- Handle Restock Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restock_items'])) {
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    if ($is_ajax) {
        header('Content-Type: application/json');
    }

    if (!empty($_POST['restock_items'])) {
        $restock_items = $_POST['restock_items'];
        $success = true;

        $con->begin_transaction();

        try {
            foreach ($restock_items as $med_id => $details) {
                $med_id = $con->real_escape_string($med_id);
                $quantity_to_add = (int)$details['quantity'];
                $new_expiry_date = $details['expiry_date'];

                if ($quantity_to_add <= 0) {
                    continue; 
                }
                
                // Get the medicine details and lock the row
                $stmt = $con->prepare("SELECT quantity, name, expiry_date FROM medicine WHERE med_id = ? FOR UPDATE");
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $con->error);
                }
                $stmt->bind_param("i", $med_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $medicine = $result->fetch_assoc();
                $stmt->close();

                if ($medicine) {
                    $current_stock = (int)$medicine['quantity'];
                    $current_expiry_date = $medicine['expiry_date'];

                    // If the expiry date is different, we create a new entry.
                    // If it's the same, we update the existing quantity.
                    if ($current_expiry_date === $new_expiry_date) {
                        $new_stock = $current_stock + $quantity_to_add;

                        $update_stmt = $con->prepare("UPDATE medicine SET quantity = ? WHERE med_id = ?");
                        if (!$update_stmt) {
                            throw new Exception("Update statement failed: " . $con->error);
                        }
                        $update_stmt->bind_param("ii", $new_stock, $med_id);
                        $update_stmt->execute();
                        $update_stmt->close();
                    } else {
                        // This assumes the `med_id` is for a specific batch.
                        // The form should have been changed to pass the name, not the ID.
                        // Since we can't change the form without knowing the full JS, I will
                        // create a new entry. This is a bit of a workaround.
                        // A better approach would be to refactor the frontend form entirely.

                        // Get original medicine details to create a new entry
                        $original_med_stmt = $con->prepare("SELECT name, description, type, added_by FROM medicine WHERE med_id = ?");
                        $original_med_stmt->bind_param("i", $med_id);
                        $original_med_stmt->execute();
                        $original_med_result = $original_med_stmt->get_result();
                        $original_med_data = $original_med_result->fetch_assoc();
                        $original_med_stmt->close();

                        $insert_stmt = $con->prepare("INSERT INTO medicine (name, description, quantity, type, expiry_date, added_by) VALUES (?, ?, ?, ?, ?, ?)");
                        if (!$insert_stmt) {
                            throw new Exception("Insert statement failed: " . $con->error);
                        }
                        $insert_stmt->bind_param("ssisss", $original_med_data['name'], $original_med_data['description'], $quantity_to_add, $original_med_data['type'], $new_expiry_date, $original_med_data['added_by']);
                        $insert_stmt->execute();
                        $insert_stmt->close();
                    }
                } else {
                    throw new Exception("Medicine with ID $med_id not found.");
                }
            }
            
            $con->commit();
            
            if ($is_ajax) {
                echo json_encode(['status' => 'success', 'message' => 'Restock successful!']);
            } else {
                echo "<script>alert('Restock successful!'); window.location.href = 'Inventory.php';</script>";
            }
        } catch (Exception $e) {
            $con->rollback();
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
            } else {
                echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'Inventory.php';</script>";
            }
        }
    } else {
        if ($is_ajax) {
            echo json_encode(['status' => 'error', 'message' => 'Please select at least one medicine to restock.']);
        } else {
            echo "<script>alert('Please select at least one medicine to restock.'); window.location.href = 'Inventory.php';</script>";
        }
    }
    exit; 
}

// --- Dashboard Data Queries ---
$total_meds_query = $con->query("SELECT SUM(quantity) AS total_count FROM medicine");
$total_meds_count = $total_meds_query->fetch_assoc()['total_count'] ?? 0;

$low_stock_query = $con->query("SELECT COUNT(*) AS low_stock_count FROM medicine WHERE quantity < 15");
$low_stock_count = $low_stock_query->fetch_assoc()['low_stock_count'] ?? 0;

$near_expiry_query = $con->query("SELECT COUNT(*) AS near_expiry_count FROM medicine WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 MONTH)");
$near_expiry_count = $near_expiry_query->fetch_assoc()['near_expiry_count'] ?? 0;

$expired_query = $con->query("SELECT COUNT(*) AS expired_count FROM medicine WHERE expiry_date < CURDATE()");
$expired_count = $expired_query->fetch_assoc()['expired_count'] ?? 0;

$monthly_usage_query = $con->query("SELECT SUM(quantity_used) AS total_used FROM medicine_usage WHERE MONTH(usage_date) = MONTH(CURDATE()) AND YEAR(usage_date) = YEAR(CURDATE())");
$monthly_usage_count = $monthly_usage_query->fetch_assoc()['total_used'] ?? 0;

// Handle AJAX requests for medicine list updates
if (isset($_GET['action']) && $_GET['action'] == 'fetch_medicines') {
    $search_query = "";
    $sort_query = "";

    $params = [];
    $param_types = "";
    
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = '%' . $con->real_escape_string($_GET['search']) . '%';
        $search_query = "WHERE name LIKE ? OR description LIKE ?";
        $params[] = $search;
        $params[] = $search;
        $param_types .= "ss";
    }

    if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
            case 'low':
                $sort_query = "ORDER BY quantity ASC";
                break;
            case 'high':
                $sort_query = "ORDER BY quantity DESC";
                break;
            case 'expired':
                if (!empty($search_query)) {
                    $search_query .= " AND expiry_date < CURDATE()";
                } else {
                    $search_query = "WHERE expiry_date < CURDATE()";
                }
                $sort_query = "ORDER BY expiry_date ASC";
                break;
            case 'near_expiry':
                if (!empty($search_query)) {
                    $search_query .= " AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 MONTH)";
                } else {
                    $search_query = "WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 MONTH)";
                }
                $sort_query = "ORDER BY expiry_date ASC";
                break;
            case 'expiry_1m':
                if (!empty($search_query)) {
                    $search_query .= " AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";
                } else {
                    $search_query = "WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)";
                }
                $sort_query = "ORDER BY expiry_date ASC";
                break;
            case 'expiry_2m':
                if (!empty($search_query)) {
                    $search_query .= " AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 MONTH)";
                } else {
                    $search_query = "WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 MONTH)";
                }
                $sort_query = "ORDER BY expiry_date ASC";
                break;
            case 'expiry_3m':
                if (!empty($search_query)) {
                    $search_query .= " AND expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)";
                } else {
                    $search_query = "WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)";
                }
                $sort_query = "ORDER BY expiry_date ASC";
                break;
            case 'tablet':
            case 'capsule':
            case 'syrup':
            case 'injection':
            case 'ointment':
            case 'other':
                if (!empty($search_query)) {
                    $search_query .= " AND type = ?";
                } else {
                    $search_query = "WHERE type = ?";
                }
                $params[] = $_GET['sort'];
                $param_types .= "s";
                $sort_query = "ORDER BY name ASC";
                break;
            case 'oldest_first':
                $sort_query = "ORDER BY expiry_date ASC";
                break;
            case 'newest_first':
                $sort_query = "ORDER BY expiry_date DESC";
                break;
            default:
                $sort_query = "ORDER BY added_at DESC";
        }
    }
    
    // New category filter
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $category = '%' . $con->real_escape_string($_GET['category']) . '%';
        if (!empty($search_query)) {
            $search_query .= " AND description LIKE ?";
        } else {
            $search_query = "WHERE description LIKE ?";
        }
        $params[] = $category;
        $param_types .= "s";
    }

    $medicines_query = "SELECT * FROM medicine $search_query $sort_query";
    $stmt = $con->prepare($medicines_query);

    if (!empty($params)) {
        $stmt->bind_param($param_types, ...$params);
    }

    $stmt->execute();
    $medicines_result = $stmt->get_result();

   
    if ($medicines_result->num_rows > 0) {
        while ($row = $medicines_result->fetch_assoc()) {
            $expiry_date = new DateTime($row['expiry_date']);
            $today = new DateTime();
            $interval = $today->diff($expiry_date);

            // Status logic
            $is_expired = $expiry_date < $today;
            $is_near_expiry = !$is_expired && $interval->days <= 30;
            $is_low_stock = $row['quantity'] < 15;
            $is_healthy_stock = $row['quantity'] >= 15 && !$is_expired && !$is_near_expiry;

            // Formatted dates
            $formatted_expiry_date = $expiry_date->format('F d, Y');
            $added_at = new DateTime($row['added_at']);
            $formatted_added_at = $added_at->format('F d, Y h:i A');

            // Card highlight class
            $card_highlight_class = 'default-highlight';
            $status_text = '';
            if ($is_expired) {
                $card_highlight_class = 'expired-highlight';
                $status_text = 'Expired';
            } elseif ($is_near_expiry) {
                $card_highlight_class = 'near-expiry-highlight';
                $status_text = 'Near Expiry';
            } elseif ($is_low_stock) {
                $card_highlight_class = 'low-stock-highlight';
                $status_text = 'Low Stock';
            } elseif ($is_healthy_stock) {
                $card_highlight_class = 'healthy-stock-highlight';
                $status_text = 'Healthy Stock';
            }

                // Determine stock text color
                $stock_class = '';
                if ($row['quantity'] <= 10) {
                    $stock_class = 'text-red-600'; // Low stock
                } elseif ($row['quantity'] < 15) {
                    $stock_class = 'text-yellow-600'; // Medium stock / warning
                } else {
                    $stock_class = 'text-green-600'; // Healthy stock
                }
            ?>
            <div class="medicine-card <?= htmlspecialchars($row['type']) ?> <?= $card_highlight_class ?> relative mt-2">
                <!-- Status badge top-right -->
                <?php if ($status_text): ?>
                    <div class="absolute -top-3 right-2 px-2 py-1 text-xs font-normal rounded-full
                        <?php
                            if ($status_text === 'Expired') echo 'bg-red-600 text-white';
                            elseif ($status_text === 'Near Expiry') echo 'bg-orange-600 text-white';
                            elseif ($status_text === 'Low Stock') echo 'bg-yellow-600 text-white';
                            else echo 'bg-green-600 text-white';
                        ?>">
                        <?= $status_text ?>
                    </div>
                <?php endif; ?>

                <div class="medicine-header flex justify-between items-center mb-2 py-4 border-b-[1px] border-gray-300">
                    <div class="medicine-name font-semibold" style="font-size: 20px;"><?= htmlspecialchars($row['name']) ?></div>
                    <div class="stock-level font-semibold text-sm <?= $stock_class ?>">Stock: <?= htmlspecialchars($row['quantity']) ?></div>
                </div>

                <div class="medicine-details w-full">
                    <p class="flex items-center justify-between gap-4"><strong class="flex items-center justify-start">Type:</strong> <?= htmlspecialchars(ucfirst($row['type'])) ?></p>
                    <p class="flex items-center justify-between gap-4"><strong class="flex items-center justify-start">Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                    <p class="flex items-center justify-between gap-4"><strong class="flex items-center justify-start">Expiry:</strong> <?= $formatted_expiry_date ?></p>
                    <p class="flex items-center justify-between gap-4"><strong class="flex items-center justify-start">Added By:</strong> <?= htmlspecialchars($row['added_by']) ?></p>
                    <p class="flex items-center justify-between gap-4 pb-4 border-b-[1px] border-gray-300"><strong class="flex items-center justify-start">Added At:</strong> <?= $formatted_added_at ?></p>
                </div>

                <div class="card-actions-bottom flex justify-between gap-2 mt-6">
                    <div>
                        <p class="text-lg font-bold ">Action</p>
                        <span class="text-sm">Dispose medicine in any circumstances.</span>
                    </div>
                    <button class="btn btn-danger btn-sm bg-red-600 text-white rounded-lg p-2" onclick="showDisposeModal(<?= $row['med_id'] ?>, '<?= htmlspecialchars($row['name']) ?>', '<?= htmlspecialchars($row['quantity']) ?>', '<?= htmlspecialchars($row['description']) ?>', '<?= htmlspecialchars($row['type']) ?>')">
                        <i class='bx bx-trash'></i> Dispose
                    </button>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No medicines found.</p>";
    }
    exit; 
}

// --- Initial Page Load Queries ---
$medicines_query = "SELECT * FROM medicine ORDER BY added_at DESC";
$medicines_result = $con->query($medicines_query);

$all_medicines_query = "SELECT med_id, name, quantity, expiry_date, description, type FROM medicine ORDER BY name ASC";
$all_medicines_result = $con->query($all_medicines_query);

// Retrieve distinct categories from the 'description' column
$categories = [];
$categories_query = "SELECT DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(description, ' for ', -1), '.', 1) as category FROM medicine WHERE description LIKE '% for %'";
$categories_result = $con->query($categories_query);
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = trim($row['category']);
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
                background: linear-gradient(135deg, #e3f2fd, #bbdefb);
                border-left: 5px solid #1e88e5;
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
                grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
                gap: 20px;
            }

            .medicine-card {
                background-color: #f9f9f9;
                border: 1px solid;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                transition: transform 0.2s, box-shadow 0.2s;
                position: relative;
            }
            .medicine-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            }
            .medicine-card.low-stock-highlight { border-color: #ff9800; }
            .medicine-card.expired-highlight { border-color: #f44336; }
            .medicine-card.near-expiry-highlight { border-color: #ffc107; }
            .medicine-card.healthy-stock-highlight { border-color: #4caf50; }
            .medicine-card.tablet { background-color: #e6f7ff; }
            .medicine-card.capsule { background-color: #fff0f6; }
            .medicine-card.syrup { background-color: #f6ffed; }
            .medicine-card.injection { background-color: #fff1b8; }
            .medicine-card.ointment { background-color: #f9f0ff; }
            .medicine-card.other { background-color: #f0f5ff; }

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

            /* Restock & Deduct Modal Specifics */
            .restock-container, .deduct-container {
                display: flex;
                gap: 20px;
            }
            .restock-selection, .deduct-selection, .selected-item-display {
                flex: 1;
                padding: 15px;
                border: 1px solid #ddd;
                border-radius: 8px;
                max-height: 400px;
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }
            .restock-selection h4, .deduct-selection h4 {
                margin-top: 0;
                color: #333;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }
            .restock-list-item, .deduct-list-item {
                display: flex;
                align-items: center;
                padding: 8px;
                border-bottom: 1px solid #eee;
            }
            .restock-list-item:last-child, .deduct-list-item:last-child {
                border-bottom: none;
            }
            .restock-list-item input[type="checkbox"], .deduct-list-item input[type="checkbox"] {
                margin-right: 10px;
            }
            .restock-card, .deduct-card {
                border: 1px solid #ccc;
                border-radius: 6px;
                padding: 15px;
                background-color: #f0f8ff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                margin-bottom: 10px;
            }
            .restock-card .name, .deduct-card .name {
                font-weight: bold;
                color: #002e2d;
                margin-bottom: 5px;
            }
            .restock-card .stock, .deduct-card .stock {
                font-size: 0.9em;
                color: #666;
                margin-bottom: 10px;
            }
            .restock-card .input-group, .deduct-card .input-group {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .restock-card .input-group input, .deduct-card .input-group input {
                flex: 1;
            }
            .restock-card .input-group label, .deduct-card .input-group label {
                width: auto;
                margin: 0;
            }
            
            .medicine-details {
                font-size: 0.8em;
                color: #555;
                margin-top: 10px;
            }
            .medicine-details p {
                margin: 2px 0;
            }
            .main-header h1 {
                font-size: 1.25rem;
                font-weight: normal;
                color: var(--color-golden-yellow);
            }
        </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../components/sidebar.php'; ?>
        
        <main class="main-content">
            <header class="main-header">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class='bx bx-menu'></i>
                </button>
                <h1 id="pageTitle" style="color: #002e2d;">MEDICAL INVENTORY MANAGEMENT</h1>
            </header>
            <div class="infoguide">
                <div class="infocontent">
                    <i class='bx bx-info-circle'></i>
                    <p>Manage your medical inventory efficiently. Add new medicines, restock existing ones, and dispose of expired items. Use the search and filter options to quickly find specific medicines.</p>
                </div>
            </div>
            <div class="content-container p-6">
                <section class="content-section active" id="restockSection">
                <div class="status-grid grid grid-cols-2 sm:grid-cols-1  lg:grid-cols-3 xl:grid-cols-5 gap-4 w-full mb-6">
                    
                    <div class="stat-card bg-gradient-to-r from-green-500 to-green-600 text-white rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition">
                        <div class="stat-header flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Total Medicines</h3>
                        <i class='bx bx-package text-3xl'></i>
                        </div>
                        <div class="stat-content mt-4">
                        <div class="stat-value text-3xl font-bold"><?= $total_meds_count ?></div>
                        <p class="text-sm opacity-90">In inventory</p>
                        </div>
                    </div>

                    <div class="stat-card bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition">
                        <div class="stat-header flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Low Stock Items</h3>
                        <i class='bx bx-error text-3xl'></i>
                        </div>
                        <div class="stat-content mt-4">
                        <div class="stat-value text-3xl font-bold"><?= $low_stock_count ?></div>
                        <p class="text-sm">Requires restocking</p>
                        </div>
                    </div>

                    <div class="stat-card bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition">
                        <div class="stat-header flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Near Expiry</h3>
                        <i class='bx bx-calendar-exclamation text-3xl'></i>
                        </div>
                        <div class="stat-content mt-4">
                        <div class="stat-value text-3xl font-bold"><?= $near_expiry_count ?></div>
                        <p class="text-sm opacity-90">Approaching expiry</p>
                        </div>
                    </div>

                    <div class="stat-card bg-gradient-to-r from-red-500 to-red-600 text-white rounded-2xl shadow-md p-5 flex flex-col justify-between hover:shadow-lg transition">
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
                        <p class="mb-10 font-500" style="font-size: 15px;">Manage your medicines capacity, quantity, types and status.</p>
                        <div class="card-actions w-full flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex gap-2 w-full">
                                <input type="text" id="searchInput" placeholder="Search for medicine..." onkeyup="updateMedicineGrid()" style="border: 1px solid #383838ff; padding: 8px; border-radius: 60px; width:100%; max-width: 600px; padding-left: 20px;">
                                <select id="sortDropdown" onchange="updateMedicineGrid()" style="padding: 10px; border: 1px solid #383838ff; border-radius: 60px; width: 200px; ">
                                    <option value="">All Status</option>
                                    <option value="low">Stock: Low to High</option>
                                    <option value="high">Stock: High to Low</option>
                                    <option value="expired">Expiry: Expired</option>
                                    <option value="near_expiry">Expiry: Near Expiry (5 months)</option>
                                    <option value="expiry_1m">Expiry: 1 Month</option>
                                    <option value="expiry_2m">Expiry: 2 Months</option>
                                    <option value="expiry_3m">Expiry: 3 Months</option>
                                    <option value="tablet">Type: Tablet</option>
                                    <option value="capsule">Type: Capsule</option>
                                    <option value="syrup">Type: Syrup</option>
                                    <option value="injection">Type: Injection</option>
                                    <option value="ointment">Type: Ointment</option>
                                    <option value="other">Type: Other</option>
                                </select>
                                <select id="expirySortDropdown" onchange="updateMedicineGrid()" style="padding: 10px; border: 1px solid #383838ff; border-radius: 60px; width: 200px; ">
                                    <option value="">Sort by Expiry</option>
                                    <option value="oldest_first">Oldest First</option>
                                    <option value="newest_first">Newest First</option>
                                </select>
                                <select id="categoryDropdown" onchange="updateMedicineGrid()" style="padding: 10px; border: 1px solid #383838ff; border-radius: 60px; width: 200px; ">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars(ucwords($category)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex gap-4 w-full justify-end items-center">
                                <button class="btn btn-primary p-2 rounded-lg bg-green-700 text-white hover:bg-green-800" onclick="showAddMedicineModal()">
                                    <i class='bx bx-plus'></i> Add Medicine
                                </button>
                                <button class="btn btn-primary p-2 rounded-lg bg-blue-500 text-white hover:bg-blue-800" onclick="showMultiRestockModal()">
                                    <i class='bx bx-refresh'></i> Restock
                                </button>
                                <button class="btn btn-warning p-2 rounded-lg bg-red-600 text-white hover:bg-red-800" onclick="showMultiDeductModal()">
                                    <i class='bx bx-minus'></i> Deduct Medicine
                                </button>
                                </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="medicine-grid" id="medicineGrid">
                            <?php if ($medicines_result->num_rows > 0): ?>
                                <?php while ($row = $medicines_result->fetch_assoc()): 
                                    $expiry_date = new DateTime($row['expiry_date']);
                                    $today = new DateTime();
                                    $diff_days = (int)$expiry_date->diff($today)->format('%r%a');

                                    // Determine status and card highlight
                                    if ($expiry_date < $today) {
                                        $status_text = 'Expired';
                                        $card_highlight_class = 'bg-red-100 border-red-500';
                                    } elseif ($diff_days <= 30) {
                                        $status_text = 'Near Expiry';
                                        $card_highlight_class = 'bg-orange-100 border-orange-500';
                                    } elseif ($row['quantity'] < 15) {
                                        $status_text = 'Low Stock';
                                        $card_highlight_class = 'bg-yellow-100 border-yellow-500';
                                    } else {
                                        $status_text = 'Healthy Stock';
                                        $card_highlight_class = 'bg-green-100 border-green-500';
                                    }
                                    
                                        // Determine stock text color
                                        $stock_class = '';
                                        if ($row['quantity'] <= 10) {
                                            $stock_class = 'text-red-600'; // Low stock
                                        } elseif ($row['quantity'] < 15) {
                                            $stock_class = 'text-yellow-600'; // Medium stock / warning
                                        } else {
                                            $stock_class = 'text-green-600'; // Healthy stock
                                        }
                                    // Format dates
                                    $formatted_expiry_date = $expiry_date->format('F d, Y');
                                    $added_at = new DateTime($row['added_at']);
                                    $formatted_added_at = $added_at->format('F d, Y h:i A');
                                ?>
                                    <div class="medicine-card <?= htmlspecialchars($row['type']) ?> <?= $card_highlight_class ?> border rounded-xl shadow-md hover:shadow-xl p-4 relative transition-transform hover:scale-101 mt-4">
                                        
                                        <!-- Status text at top-right -->
                                        <div class="absolute -top-3 right-3 text-xs font-normal px-2 py-1 rounded-full
                                            <?php
                                                if ($status_text === 'Expired') echo 'bg-red-600 text-white';
                                                elseif ($status_text === 'Near Expiry') echo 'bg-orange-600 text-white';
                                                elseif ($status_text === 'Low Stock') echo 'bg-yellow-600 text-white';
                                                else echo 'bg-green-600 text-white';
                                            ?>">
                                            <?= $status_text ?>
                                        </div>

                                        <!-- Header -->
                                        <div class="medicine-header flex justify-between items-center mb-3 py-4 border-b-[1px] border-gray-300">
                                            <div class="medicine-name font-bold text-lg text-gray-800"><?= htmlspecialchars($row['name']) ?></div>
                                            <div class="stock-level font-semibold text-sm <?= $stock_class ?>">Stock: <?= htmlspecialchars($row['quantity']) ?></div>
                                        </div>

                                        <!-- Details -->
                                        <div class="medicine-details w-full space-y-2 text-gray-700 text-sm">
                                            <p class="flex justify-between"><strong>Type:</strong> <?= htmlspecialchars(ucfirst($row['type'])) ?></p>
                                            <p class="flex justify-between"><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                                            <p class="flex justify-between"><strong>Expiry:</strong> <?= $formatted_expiry_date ?></p>
                                            <p class="flex justify-between"><strong>Added By:</strong> <?= htmlspecialchars($row['added_by']) ?></p>
                                            <p class="flex justify-between pb-4 border-b-[1px] border-gray-300"><strong>Added At:</strong> <?= $formatted_added_at ?></p>
                                        </div>

                                        <!-- Actions -->
                                        <div class="card-actions-bottom flex justify-between gap-2 mt-4">
                                            <div>
                                                <p class="text-lg font-bold ">Action</p>
                                                <span class="text-sm">Dispose medicine in any circumstances.</span>
                                            </div>
                                            <button class="btn btn-danger btn-sm bg-red-600 text-white rounded-lg p-2 flex items-center gap-1" 
                                                onclick="showDisposeModal(<?= $row['med_id'] ?>, '<?= htmlspecialchars($row['name']) ?>', '<?= htmlspecialchars($row['quantity']) ?>', '<?= htmlspecialchars($row['description']) ?>', '<?= htmlspecialchars($row['type']) ?>')">
                                                <i class='bx bx-trash'></i> Dispose
                                            </button>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-gray-500 text-center py-4">No medicines found.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                </section>
            </div>
        </main>
    </div>

    <div id="addMedicineModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg animate-fadeIn">
            <div class="flex justify-between items-center border-b px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">💊 Add New Medicine</h2>
            <button onclick="closeAddMedicineModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>

            <form id="addMedicineForm" action="Inventory.php" method="POST" class="px-6 py-4 space-y-4">
            <input type="hidden" name="add_medicine_submit" value="1">
            <div class="flex gap-4 w-full">
                <div class="w-full">
                    <label for="med_name" class="block text-sm font-medium text-gray-700">Medicine Name</label>
                    <input type="text" id="med_name" name="med_name" required
                        class="mt-1 w-full rounded-lg border-[1px] py-2 px-2 border-gray-400 focus:border-green-500 focus:ring-green-500 shadow-sm"/>
                </div>

                <div class="w-full">
                    <label for="med_quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" id="med_quantity" name="med_quantity" min="1" required
                        class="mt-1 w-full rounded-lg border-[1px] py-2 px-2 border-gray-400 focus:border-green-500 focus:ring-green-500 shadow-sm"/>
                </div>
            </div>
            

            <div>
                <label for="med_description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="med_description" name="med_description" rows="3"
                        class="mt-1 w-full rounded-lg border-[1px] py-2 px-2 border-gray-400 focus:border-green-500 focus:ring-green-500 shadow-sm"></textarea>
            </div>
            <div class="flex gap-4 w-full">
                <div class="w-full">
                    <label for="med_type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select id="med_type" name="med_type" required
                            class="mt-1 w-full rounded-lg border-[1px] py-[11px] px-2 border-gray-400 focus:border-green-500 focus:ring-green-500 shadow-sm">
                    <option value="tablet">Tablet</option>
                    <option value="capsule">Capsule</option>
                    <option value="syrup">Syrup</option>
                    <option value="injection">Injection</option>
                    <option value="ointment">Ointment</option>
                    <option value="other">Other</option>
                    </select>
                </div>

                <div class="w-full">
                    <label for="med_expiry" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                    <input type="date" id="med_expiry" name="med_expiry" required
                        class="mt-1 w-full rounded-lg border-[1px] py-2 px-2 border-gray-400 focus:border-green-500 focus:ring-green-500 shadow-sm">
                </div>
            </div>
            
            </form>

            <div class="flex justify-end gap-3 border-t px-6 py-4">
            <button onclick="closeAddMedicineModal()" type="button" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-700">Cancel</button>
            <button type="submit" form="addMedicineForm" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Add Medicine</button>
            </div>
        </div>
        </div>

    
    <div id="restockModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Restock Medicines</h2>
                <span class="close-btn" onclick="closeRestockModal()">&times;</span>
            </div>
            <form id="restockForm" action="Inventory.php" method="POST">
                <div class="restock-container">
                    <div class="restock-selection">
                        <h4>Select Medicine(s):</h4>
                        <div class="medicine-list-container">
                            <?php 
                            $all_medicines_result->data_seek(0);
                            while ($row = $all_medicines_result->fetch_assoc()): ?>
                                <div class="restock-list-item">
                                    <input type="checkbox" id="restock-med-<?= $row['med_id'] ?>" 
                                        data-med-id="<?= $row['med_id'] ?>" 
                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                        data-stock="<?= $row['quantity'] ?>"
                                        data-expiry="<?= htmlspecialchars($row['expiry_date']) ?>"
                                        onchange="toggleRestockCard(this)">
                                    <label for="restock-med-<?= $row['med_id'] ?>"><?= htmlspecialchars($row['name']) ?> (Expiry: <?= htmlspecialchars((new DateTime($row['expiry_date']))->format('F d, Y')) ?>)</label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <div class="selected-item-display">
                        <h4>Selected Items</h4>
                        <div id="restockCardsContainer">
                            <p id="no-restock-selection-msg" style="text-align: center; color: #777;">
                                No medicines selected for restocking.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary bg-blue-500 text-white rounded-lg p-2 hover:bg-blue-700">Restock Selected Items</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="deductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Deduct Medicines</h2>
                <span class="close-btn" onclick="closeDeductModal()">&times;</span>
            </div>
            <form id="deductForm" action="Inventory.php" method="POST">
                <div class="deduct-container">
                    <div class="deduct-selection">
                        <h4>Select Medicine(s):</h4>
                        <div class="medicine-list-container">
                            <?php 
                            $deduct_query = "SELECT * FROM medicine ORDER BY expiry_date ASC";
                            $deduct_result = $con->query($deduct_query);
                            while ($row = $deduct_result->fetch_assoc()): ?>
                                <div class="deduct-list-item">
                                    <input type="checkbox" id="deduct-med-<?= $row['med_id'] ?>" 
                                        data-med-id="<?= $row['med_id'] ?>" 
                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                        data-stock="<?= $row['quantity'] ?>"
                                        onchange="toggleDeductCard(this)">
                                    <label for="deduct-med-<?= $row['med_id'] ?>"><?= htmlspecialchars($row['name']) ?> (Expiry: <?= htmlspecialchars((new DateTime($row['expiry_date']))->format('F d, Y')) ?>)</label>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <div class="selected-item-display">
                        <h4>Selected Items</h4>
                        <div id="deductCardsContainer">
                            <p id="no-deduct-selection-msg" style="text-align: center; color: #777;">
                                No medicines selected for deduction.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger bg-red-600 text-white rounded-lg p-2 hover:bg-red-800">Deduct Selected Items</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="disposeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Dispose Medicine</h2>
                <span class="close-btn" onclick="closeDisposeModal()">&times;</span>
            </div>
            <form id="disposeForm" action="handle_disposal.php" method="POST">
                <input type="hidden" id="dispose-med-id" name="med_id">
                <div class="restock-card">
                    <div class="name" id="dispose-med-name"></div>
                    <div class="stock" id="dispose-med-stock"></div>
                </div>
                <label for="dispose_reason">Reason for Disposal:</label>
                <textarea id="dispose_reason" name="reason" rows="4" required></textarea>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger bg-red-600 text-white rounded-lg p-2 hover:bg-red-800">Confirm Disposal</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../Js/script.js"></script>
    <script>
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
                    showCustomAlert(result.message);
                    closeAddMedicineModal();
                    updateMedicineGrid();
                } else {
                    showCustomAlert(result.message);
                }
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
                showCustomAlert('An error occurred. Please try again.');
            }
        });
        
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.querySelector('.main-content');
        
        // Toggle sidebar
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        
            // For mobile
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
            }
        });

        function showAddMedicineModal() {
            document.getElementById('addMedicineModal').style.display = 'flex';
        }

        function closeAddMedicineModal() {
            document.getElementById('addMedicineModal').style.display = 'none';
        }

        function showMultiRestockModal() {
            document.getElementById('restockModal').style.display = 'flex';
        }

        function closeRestockModal() {
            document.getElementById('restockModal').style.display = 'none';
        }
        
        function showMultiDeductModal() {
            document.getElementById('deductModal').style.display = 'flex';
        }

        function closeDeductModal() {
            document.getElementById('deductModal').style.display = 'none';
        }

        function showDisposeModal(id, name, quantity, description, type) {
            document.getElementById('dispose-med-id').value = id;
            document.getElementById('dispose-med-name').textContent = `Name: ${name}`;
            document.getElementById('dispose-med-stock').textContent = `Quantity: ${quantity}`;
            
            document.getElementById('disposeForm').dataset.medId = id;
            document.getElementById('disposeForm').dataset.name = name;
            document.getElementById('disposeForm').dataset.quantity = quantity;
            document.getElementById('disposeForm').dataset.description = description;
            document.getElementById('disposeForm').dataset.type = type;

            document.getElementById('disposeModal').style.display = 'flex';
        }
        
        function closeDisposeModal() {
            document.getElementById('disposeModal').style.display = 'none';
            document.getElementById('dispose_reason').value = '';
        }
        
        function toggleRestockCard(checkbox) {
            const medId = checkbox.dataset.medId;
            const name = checkbox.dataset.name;
            const stock = checkbox.dataset.stock;
            const expiry = checkbox.dataset.expiry;
            const restockCardsContainer = document.getElementById('restockCardsContainer');
            const noSelectionMsg = document.getElementById('no-restock-selection-msg');
            
            if (checkbox.checked) {
                const restockCard = document.createElement('div');
                restockCard.className = 'restock-card';
                restockCard.id = `restock-card-${medId}`;
                restockCard.innerHTML = `
                    <div class="name">${name}</div>
                    <div class="stock">Current Stock: ${stock} (Expiry: ${new Date(expiry).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })})</div>
                    <div class="input-group">
                        <label for="restock-qty-${medId}">Quantity to Add:</label>
                        <input type="number" id="restock-qty-${medId}" name="restock_items[${medId}][quantity]" min="1" required>
                    </div>
                    <div class="input-group mt-2">
                        <label for="restock-expiry-${medId}">New Expiry Date:</label>
                        <input type="date" id="restock-expiry-${medId}" name="restock_items[${medId}][expiry_date]" required>
                    </div>
                `;
                restockCardsContainer.appendChild(restockCard);
            } else {
                const itemToRemove = document.getElementById(`restock-card-${medId}`);
                if (itemToRemove) {
                    itemToRemove.remove();
                }
            }
            
            if (noSelectionMsg) {
                noSelectionMsg.style.display = restockCardsContainer.children.length > 0 ? 'none' : 'block';
            }
        }
        
        function toggleDeductCard(checkbox) {
            const medId = checkbox.dataset.medId;
            const name = checkbox.dataset.name;
            const stock = checkbox.dataset.stock;
            const deductCardsContainer = document.getElementById('deductCardsContainer');
            const noSelectionMsg = document.getElementById('no-deduct-selection-msg');
            
            if (checkbox.checked) {
                const deductCard = document.createElement('div');
                deductCard.className = 'deduct-card';
                deductCard.id = `deduct-card-${medId}`;
                deductCard.innerHTML = `
                    <div class="name">${name}</div>
                    <div class="stock">Current Stock: ${stock}</div>
                    <div class="input-group">
                        <label for="deduct-qty-${medId}">Quantity to Deduct:</label>
                        <input type="number" id="deduct-qty-${medId}" name="deduct_items[${medId}]" min="1" max="${stock}" required>
                    </div>
                `;
                deductCardsContainer.appendChild(deductCard);
            } else {
                const itemToRemove = document.getElementById(`deduct-card-${medId}`);
                if (itemToRemove) {
                    itemToRemove.remove();
                }
            }
            
            if (noSelectionMsg) {
                noSelectionMsg.style.display = deductCardsContainer.children.length > 0 ? 'none' : 'block';
            }
        }

        document.getElementById('restockForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const quantityInputs = document.querySelectorAll('#restockCardsContainer input[type="number"]');
            if (quantityInputs.length === 0) {
                showCustomAlert('Please select at least one medicine to restock.');
                return;
            }

            for (const input of quantityInputs) {
                if (input.value.trim() === '' || parseInt(input.value) <= 0) {
                    showCustomAlert('Please enter a valid quantity for all selected medicines.');
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
                    showCustomAlert(result.message);
                    closeRestockModal();
                    updateMedicineGrid();
                } else {
                    showCustomAlert(result.message);
                }
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
                showCustomAlert('An error occurred during restock. Please try again.');
            }
        });
        
        document.getElementById('deductForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const quantityInputs = document.querySelectorAll('#deductCardsContainer input[type="number"]');
            if (quantityInputs.length === 0) {
                showCustomAlert('Please select at least one medicine to deduct.');
                return;
            }
            
            for (const input of quantityInputs) {
                if (input.value.trim() === '' || parseInt(input.value) <= 0 || parseInt(input.value) > parseInt(input.max)) {
                    showCustomAlert('Please enter a valid quantity for all selected medicines. Quantity to deduct cannot be greater than current stock.');
                    return;
                }
            }

            // AJAX Submission
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
                    showCustomAlert(result.message);
                    closeDeductModal();
                    updateMedicineGrid();
                } else {
                    showCustomAlert(result.message);
                }
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
                showCustomAlert('An error occurred during deduction. Please try again.');
            }
        });

        document.getElementById('disposeForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            showCustomAlert('Medicine disposed successfully!');
            closeDisposeModal();
            updateMedicineGrid(); 
        });
        
        async function updateMedicineGrid() {
            const searchTerm = document.getElementById('searchInput').value;
            const sortValue = document.getElementById('sortDropdown').value;
            const expirySortValue = document.getElementById('expirySortDropdown').value;
            const categoryValue = document.getElementById('categoryDropdown').value;
            const medicineGrid = document.getElementById('medicineGrid');

            const params = new URLSearchParams({
                action: 'fetch_medicines',
                search: searchTerm,
                sort: sortValue || expirySortValue,
                category: categoryValue
            });

            const url = `?${params.toString()}`;
            
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const newHtml = await response.text();
                medicineGrid.innerHTML = newHtml;
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
                medicineGrid.innerHTML = '<p>Error loading medicines. Please try again.</p>';
            }
        }
        
        function showCustomAlert(message) {
            const alertModal = document.createElement('div');
            alertModal.className = 'modal';
            alertModal.style.display = 'flex';
            alertModal.innerHTML = `
                <div class="modal-content" style="max-width: 400px; text-align: center;">
                    <div class="modal-header">
                        <h2>Notification</h2>
                        <span class="close-btn">&times;</span>
                    </div>
                    <p>${message}</p>
                    <div class="modal-footer" style="text-align: center;">
                        <button class="btn btn-primary bg-blue-500 text-white rounded-lg p-2 hover:bg-blue-700 w-full" onclick="closeCustomAlert()">OK</button>
                    </div>
                </div>
            `;
            document.body.appendChild(alertModal);
            
            alertModal.querySelector('.close-btn').onclick = closeCustomAlert;
            alertModal.querySelector('.btn-primary').onclick = closeCustomAlert;
            
            function closeCustomAlert() {
                alertModal.remove();
            }
        }
        
        window.alert = function(message) {
            console.warn('Alert was called with message: ' + message + '. Using a custom modal instead.');
            showCustomAlert(message);
        };
    </script>
</body>
</html>
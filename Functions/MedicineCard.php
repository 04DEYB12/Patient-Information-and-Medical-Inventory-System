<?php
include '../Landing Repository/Connection.php';


// --- Dashboard Data Queries ---
$total_meds_query = $con->query("SELECT SUM(quantity) AS total_count FROM medicine");
$total_meds_count = $total_meds_query->fetch_assoc()['total_count'] ?? 0;

$low_stock_query = $con->query("SELECT COUNT(*) AS low_stock_count FROM medicine WHERE quantity < 15");
$low_stock_count = $low_stock_query->fetch_assoc()['low_stock_count'] ?? 0;

$near_expiry_query = $con->query("SELECT COUNT(*) AS near_expiry_count FROM medicine WHERE expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 MONTH)");
$near_expiry_count = $near_expiry_query->fetch_assoc()['near_expiry_count'] ?? 0;

$expired_query = $con->query("SELECT COUNT(*) AS expired_count FROM medicine WHERE expiry_date < CURDATE()");
$expired_count = $expired_query->fetch_assoc()['expired_count'] ?? 0;

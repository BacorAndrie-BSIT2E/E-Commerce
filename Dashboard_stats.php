<?php
include 'db.php';

$earnings = $conn->query("SELECT IFNULL(SUM(total_amount),0) as total FROM orders WHERE status='completed'")->fetch_assoc()['total'];
$products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$customers = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='customer'")->fetch_assoc()['total'];
$employees = $conn->query("SELECT COUNT(*) as total FROM employees")->fetch_assoc()['total'];
$messages = $conn->query("SELECT COUNT(*) as total FROM messages")->fetch_assoc()['total'];

$current_month = date('Y-m');
$revenue_month = $conn->query("SELECT IFNULL(SUM(total_amount),0) as total FROM orders WHERE status='completed' AND DATE_FORMAT(created_at, '%Y-%m')='$current_month'")->fetch_assoc()['total'];

echo json_encode([
    'earnings' => $earnings,
    'products' => $products,
    'orders' => $orders,
    'customers' => $customers,
    'employees' => $employees,
    'messages' => $messages,
    'revenue_month' => $revenue_month
]);
?>
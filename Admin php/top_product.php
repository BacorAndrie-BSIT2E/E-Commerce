<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'ecommerce_db'; // Replace with your actual DB name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get top 5 selling products (adjust table and column names as needed)
$sql = "
    SELECT product_name, SUM(quantity) as total_sold
    FROM sales
    GROUP BY product_name
    ORDER BY total_sold DESC
    LIMIT 5
";

$result = $conn->query($sql);

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['product_name'];
    $data[] = (int)$row['total_sold'];
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);
?>
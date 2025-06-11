<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'ecommerce_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get top 5 selling products by REVENUE
$sql = "
    SELECT
        p.name AS product_name,
        SUM(o.quantity * p.price) AS total_revenue
    FROM
        orders o
    JOIN
        products p ON o.product_id = p.id
    WHERE
        o.status = 'completed'
    GROUP BY
        p.name
    ORDER BY
        total_revenue DESC
    LIMIT 5";

$result = $conn->query($sql);

$labels = [];
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['product_name'];
        $data[] = (float)$row['total_revenue'];
    }
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);

$conn->close();
?>
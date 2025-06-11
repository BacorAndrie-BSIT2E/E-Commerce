<?php
include 'db.php'; // make sure this connects to your database

$sql = "SELECT p.name AS product_name, SUM(o.quantity) AS total_sold
        FROM orders o
        JOIN products p ON o.product_id = p.id
        GROUP BY o.product_id
        ORDER BY total_sold DESC
        LIMIT 5"; // Top 5 products

$result = $conn->query($sql);

$labels = [];
$values = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['product_name'];
    $values[] = (int)$row['total_sold'];
}

echo json_encode([
    'labels' => $labels,
    'values' => $values
]);
?>

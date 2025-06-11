<?php
include 'db.php';
header('Content-Type: application/json');

$sql = "
    SELECT p.name AS product_name, SUM(o.quantity * p.price) AS total_revenue
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.status = 'completed'
    GROUP BY p.name
    ORDER BY total_revenue DESC
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
echo json_encode(['labels' => $labels, 'data' => $data]);
$conn->close();
?>
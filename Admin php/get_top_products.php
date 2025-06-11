<?php
include 'db.php';

$sql = "SELECT p.name, COUNT(*) AS total 
        FROM orders o 
        JOIN products p ON o.product_id = p.id 
        GROUP BY o.product_id 
        ORDER BY total DESC 
        LIMIT 10";

$result = $conn->query($sql);

$data = ["labels" => [], "data" => []];

while ($row = $result->fetch_assoc()) {
    $data['labels'][] = $row['name'];
    $data['data'][] = $row['total'];
}

echo json_encode($data);
?>

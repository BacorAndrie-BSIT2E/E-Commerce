<?php
include 'db.php';
header('Content-Type: application/json');
$sql = "
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month_key, SUM(total_amount) AS monthly_total_revenue
    FROM orders
    WHERE status = 'completed'
    GROUP BY month_key
    ORDER BY month_key ASC";
$result = $conn->query($sql);
$monthly_revenue_data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $monthly_revenue_data[$row['month_key']] = (float)$row['monthly_total_revenue'];
    }
}
$labels = [];
$data = [];
$current_year = date('Y');
for ($m = 1; $m <= 12; $m++) {
    $month_name = date('M', mktime(0, 0, 0, $m, 1));
    $month_key = $current_year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
    $labels[] = $month_name;
    $data[] = $monthly_revenue_data[$month_key] ?? 0;
}
echo json_encode(['labels' => $labels, 'data' => $data]);
$conn->close();
?>
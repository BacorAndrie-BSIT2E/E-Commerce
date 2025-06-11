<?php
header('Content-Type: application/json');

// Database connection
// Make sure this connects to your database ($conn)
// You can either include 'db.php' or paste the connection details here.
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'ecommerce_db'; // Replace with your actual DB name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// SQL to get monthly revenue
// This assumes your 'orders' table has 'total_amount' and 'created_at' columns.
// If your 'orders' table only has 'product_id' and 'quantity' per row (like a sales item),
// you'd need to SUM(quantity * price) and join with the products table as we did for the pie chart.
$sql = "
    SELECT
        DATE_FORMAT(created_at, '%Y-%m') AS month_key, -- Formats date to YYYY-MM (e.g., '2025-01')
        SUM(total_amount) AS monthly_total_revenue -- Sums up the total amount for each order
    FROM
        orders
    WHERE
        status = 'completed' -- IMPORTANT: Only count completed/paid orders for revenue
        -- OR status = 'paid' -- Add other relevant statuses if needed
    GROUP BY
        month_key
    ORDER BY
        month_key ASC"; // Order by month chronologically

$result = $conn->query($sql);

$monthly_revenue_data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $monthly_revenue_data[$row['month_key']] = (float)$row['monthly_total_revenue'];
    }
}

// Generate labels for all 12 months of the current year (or a chosen range)
$labels = [];
$data = [];
$current_year = date('Y'); // Get the current year

for ($m = 1; $m <= 12; $m++) {
    $month_name = date('M', mktime(0, 0, 0, $m, 1)); // e.g., Jan, Feb
    $month_key = $current_year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT); // e.g., 2025-01

    $labels[] = $month_name;
    // Use the fetched revenue if available, otherwise 0
    $data[] = $monthly_revenue_data[$month_key] ?? 0;
}

echo json_encode([
    'labels' => $labels,
    'data' => $data
]);

$conn->close();
?>

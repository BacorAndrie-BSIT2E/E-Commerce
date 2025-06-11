<?php
include 'db.php'; // Make sure this connects to your database ($conn)

header('Content-Type: application/json'); // Set header to return JSON

// --- Get Top Selling Products (by Revenue) ---
$sql_top_products = "
    SELECT
        p.name AS product_name,
        SUM(o.quantity * p.price) AS total_revenue_from_product
    FROM
        orders o
    JOIN
        products p ON o.product_id = p.id
    WHERE
        o.status = 'completed' -- Or 'paid', depending on your order statuses
    GROUP BY
        p.name
    ORDER BY
        total_revenue_from_product DESC
    LIMIT 5";

$result_top_products = $conn->query($sql_top_products);

$top_products_labels = [];
$top_products_data = [];

if ($result_top_products && $result_top_products->num_rows > 0) {
    while ($row = $result_top_products->fetch_assoc()) {
        $top_products_labels[] = $row['product_name'];
        $top_products_data[] = (float)$row['total_revenue_from_product']; // Cast to float for Chart.js
    }
}

// --- Get Monthly Revenue ---
$sql_monthly_revenue = "
    SELECT
        DATE_FORMAT(o.created_at, '%Y-%m') AS month_year,
        SUM(o.total_amount) AS monthly_total_revenue
    FROM
        orders o
    WHERE
        o.status = 'completed' -- Or 'paid', only count completed/paid orders
    GROUP BY
        month_year
    ORDER BY
        month_year ASC";

$result_monthly_revenue = $conn->query($sql_monthly_revenue);

$monthly_revenue_labels = [];
$monthly_revenue_data = [];

if ($result_monthly_revenue && $result_monthly_revenue->num_rows > 0) {
    $current_year = date('Y'); // Get current year for filtering or display
    $data_map = []; // To store revenue for each month, filling in zeros for missing months

    while ($row = $result_monthly_revenue->fetch_assoc()) {
        $data_map[$row['month_year']] = (float)$row['monthly_total_revenue'];
    }

    // Generate labels for all 12 months of the current year (or a range you prefer)
    for ($m = 1; $m <= 12; $m++) {
        $month_label = date('M', mktime(0, 0, 0, $m, 1)); // e.g., Jan, Feb
        $month_year_key = $current_year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT); // e.g., 2025-01

        $monthly_revenue_labels[] = $month_label;
        $monthly_revenue_data[] = $data_map[$month_year_key] ?? 0; // Use 0 if no data for the month
    }
}


// Combine all data into a single JSON response
echo json_encode([
    'topProducts' => [
        'labels' => $top_products_labels,
        'data' => $top_products_data
    ],
    'monthlyRevenue' => [
        'labels' => $monthly_revenue_labels,
        'data' => $monthly_revenue_data
    ]
]);

$conn->close();
?>
<?php
header('Content-Type: application/json');

// 1. Connect to your database
$host = 'localhost';
$user = 'root';       // change if needed
$pass = '';           // change if needed
$dbname = 'ecommerce_db'; // replace with your actual DB name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// 2. Initialize array with 0 revenue for each month
$months = [
    'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0,
    'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0,
    'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0
];

// 3. Query to get total revenue per month
$sql = "
    SELECT 
        MONTH(sales_date) as month_num, 
        SUM(amount) as total
    FROM sales
    GROUP BY MONTH(sales_date)
";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $monthNum = (int)$row['month_num'];
    $monthName = date('M', mktime(0, 0, 0, $monthNum, 10));
    $months[$monthName] = (float)$row['total'];
}

// 4. Output as JSON
echo json_encode($months);
?>

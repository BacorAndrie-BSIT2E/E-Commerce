<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "ecommerce_db"; // ❗ Palitan ito sa actual database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$notifications = [];

// 1️⃣ LOW STOCK (stock < 5)
$lowStock = $conn->query("SELECT name, stock FROM products WHERE stock < 5 AND stock > 0");
while ($row = $lowStock->fetch_assoc()) {
    $notifications[] = "⚠️ Low stock: <strong>{$row['name']}</strong> ({$row['stock']} left)";
}

// 2️⃣ OUT OF STOCK (stock = 0)
$outOfStock = $conn->query("SELECT name FROM products WHERE stock = 0");
while ($row = $outOfStock->fetch_assoc()) {
    $notifications[] = "❌ Out of stock: <strong>{$row['name']}</strong>";
}

// 3️⃣ NEW PRODUCTS TODAY
$newToday = $conn->query("SELECT name FROM products WHERE DATE(created_at) = CURDATE()");
while ($row = $newToday->fetch_assoc()) {
    $notifications[] = "🆕 New product added today: <strong>{$row['name']}</strong>";
}

// 4️⃣ SALES TODAY (needs orders table)
$salesQuery = $conn->query("SELECT COUNT(*) as total_orders, SUM(total_amount) as total_sales FROM orders WHERE DATE(created_at) = CURDATE()");
if ($salesQuery && $salesQuery->num_rows > 0) {
    $salesData = $salesQuery->fetch_assoc();
    if ($salesData['total_orders'] > 0) {
        $salesFormatted = number_format($salesData['total_sales'], 2);
        $notifications[] = "💸 Today’s Sales: <strong>₱{$salesFormatted}</strong> from <strong>{$salesData['total_orders']}</strong> orders.";
    }
}

$conn->close();

// Output
if (count($notifications) > 0) {
    foreach ($notifications as $note) {
        echo "<div class='notification'>{$note}</div>";
    }
} else {
    echo "<div class='notification'>✅ All systems normal. No alerts.</div>";
}
?>




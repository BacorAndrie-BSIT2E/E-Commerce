<?php include 'db.php'; ?> 
<!DOCTYPE html> 
<html> 
<head> 
  <title>Orders</title> 
  <link rel="stylesheet" href="style.css"> 
</head>
<body> 

<div class="sidebar"> 
  <h2>Admin</h2> 
  <a href="index.php">Dashboard</a> 
  <a href="orders.php">Orders</a>
  <a href="employees.php">Employees</a>
  <a href="customers.php">Customers</a>
  <a href="messages.php">Messages</a>
  <a href="products.php">Products</a>
  <a href="#">Settings</a>
  <a href="#">Log Out</a>
</div>

<div class="main">
  <h1>Orders</h1>
  <table>
    <tr>
      <th>Order ID</th>
      <th>Customer Name</th>
      <th>Product</th>
      <th>Total Amount</th>
      <th>Status</th>
      <th>Location</th>
      <th>Date</th>
    </tr>

<?php
$sql = "SELECT 
            o.id AS order_id,
            u.username AS customer_name,
            p.name AS product_name,
            o.total_amount,
            o.status,
            o.location,
            o.created_at
        FROM orders o
        LEFT JOIN users u ON o.customer_id = u.id
        LEFT JOIN products p ON o.product_id = p.id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['customer_name']}</td>
                <td>{$row['product_name']}</td>
                <td>₱" . number_format($row['total_amount'], 2) . "</td>
                <td>{$row['status']}</td>
                <td>{$row['location']}</td> 
                <td>{$row['created_at']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No orders found.</td></tr>";
}
?>

  </table>
</div>

</body>
</html>


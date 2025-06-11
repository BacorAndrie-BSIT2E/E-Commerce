<?php include 'db.php'; 
?> 
<!DOCTYPE html> 
<html> 
<head> <title>Orders</title> 
<link rel="stylesheet" href="style.css"> 
<style> 
body { 
	margin: 0; 
	font-family: Arial, 
	sans-serif; 
} 

.sidebar { 
	position: fixed; 
	width: 200px; 
	height: 100%; 
	background: #2c3e50; 
	color: white; 
	padding-top: 20px; 
} 
	
.sidebar h2 { 
	text-align: 
	center; 
} 

.sidebar a { 
	display: block; 
	color: white; 
	padding: 12px; 
	text-decoration: none; 
} 

.sidebar a:hover { 
	background: #34495e; 
} 

.main { 
	margin-left: 200px; 
	padding: 20px; 
} 

table { 
	width: 100%;
	border-collapse: collapse;
	margin-top: 15px;
} 

th, td {
	border: 1px solid #ddd;
	padding: 10px; text-align: center;
} 

th { 
	background-color: #3CCFAD;
	color: white;
} 
</style> 
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
 <a href="#">Settings</a> <a href="#">Log Out</a></div>
 
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
                    c.name AS customer_name,
                    p.product_name,
                    o.total_amount,
                    o.status,
                    o.location,
                    o.created_at
                FROM orders o
                LEFT JOIN customers c ON o.customer_id = c.id
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
            echo "<tr><td colspan='7'>No orders found</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

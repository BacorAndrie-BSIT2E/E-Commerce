<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
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
	<a href="products.php">Settings</a>
	<a href="products.php">Log Out</a>
</div>

<div class="main">
    <h1>Product List</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price (₱)</th>
                <th>Stock</th>
                <th>Added On</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['price']}</td>
                            <td>{$row['stock']}</td>
                            <td>{$row['created_at']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
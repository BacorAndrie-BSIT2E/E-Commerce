<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
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
        <h1>Messages</h1>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr><th>ID</th><th>Name</th><th>Message</th></tr>
            <?php
            $messages = $conn->query("SELECT * FROM messages");
            while ($row = $messages->fetch_assoc()) {
                echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['content']}</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

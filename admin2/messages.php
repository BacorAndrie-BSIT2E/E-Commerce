<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ecommerce_db";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar">
    <h2>Admin</h2>
    <a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo 'active'; ?>">Dashboard</a>
    <a href="orders.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='orders.php') echo 'active'; ?>">Orders</a>
    <a href="employees.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='employees.php') echo 'active'; ?>">Employees</a>
    <a href="customers.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='customers.php') echo 'active'; ?>">Customers</a>
    <a href="messages.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='messages.php') echo 'active'; ?>">Messages</a>
    <a href="products.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='products.php') echo 'active'; ?>">Products</a>
    <div class="sidebar-bottom">
        <a href="settings.php" class="settings-btn<?php if(basename($_SERVER['PHP_SELF'])=='settings.php') echo ' active'; ?>">Settings</a>
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
</div>
<div class="main">
    <h1>Messages</h1>
    <!-- Add Message Form -->
    <form method="POST" style="margin-bottom:20px;">
        <input type="text" name="sender" placeholder="Sender" required>
        <input type="text" name="message" placeholder="Message" required>
        <button type="submit" name="add">Add Message</button>
    </form>
    <?php
    // Add
    if(isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO messages (sender, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $_POST['sender'], $_POST['message']);
        $stmt->execute();
        echo "<script>location.href='messages.php';</script>";
    }

    if(isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stock=? WHERE id=?");
        $stmt->bind_param("sdii", $_POST['name'], $_POST['price'], $_POST['stock'], $_POST['id']);
        $stmt->execute();
        echo "<script>location.href='products.php';</script>";
    }
    
    // Delete
    if(isset($_GET['delete'])) {
        $conn->query("DELETE FROM messages WHERE id=".$_GET['delete']);
        echo "<script>location.href='messages.php';</script>";
    }
    ?>
    <!-- Messages Table -->
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th><th>Sender</th><th>Message</th><th>Date</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['sender']}</td>
                <td>{$row['message']}</td>
                <td>{$row['created_at']}</td>
                <td>
                    <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this message?')\">Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "ecommerce_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT products.id, products.name, products.price, products.stock, users.username
        FROM products
        JOIN users ON products.id = users.id
        ORDER BY products.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Marketplace</title>
    <link rel="stylesheet" href="userdashboard.css">
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

<div class="profile-dropdown">
    <button class="dropbtn"><?php echo htmlspecialchars($_SESSION['username']); ?> ▼</button>
    <div class="dropdown-content">
        <a href="updateprofile.php">Update Info</a>
        <a href="orders.php">My Orders</a>
        <a href="login.php">Logout</a>
    </div>
</div>

<h3>Marketplace Items for Sale</h3>

<div class="items-container">
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="item">
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <div class="item-title"><?php echo htmlspecialchars($row['name']); ?></div>
            <div class="item-price">$<?php echo number_format($row['price'], 2); ?></div>
            <div class="stocks"><?php echo nl2br(htmlspecialchars($row['stock'])); ?></div>
            <!-- ✅ Use item_id instead of id -->
            <form method="post" action="buy_item.php" style="margin-top:10px;">
                <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                <button type="submit">Buy</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No items for sale right now.</p>
<?php endif; ?>
</div>

</body>
</html>

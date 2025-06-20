<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ecommerce_db";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- AUTO NOTIFICATION LOGIC ---
// Check for low stock or out of stock products and insert a message if not yet notified
$low_stock_limit = 5; // threshold for low stock
$products = $conn->query("SELECT id, name, stock FROM products");
while ($prod = $products->fetch_assoc()) {
    $pid = $prod['id'];
    $pname = $prod['name'];
    $stock = $prod['stock'];
    // Check if already notified for this product and stock status
    $notif_check = $conn->query("SELECT id FROM messages WHERE content='stock' AND message LIKE '%$pname%' AND message LIKE '%$stock%'");
    if ($stock == 0 && $notif_check->num_rows == 0) {
        // Out of stock notification
        $msg = "Product <b>$pname</b> is <b>out of stock</b>!";
        $stmt = $conn->prepare("INSERT INTO messages (content, message) VALUES ('stock', ?)");
        $stmt->bind_param("s", $msg);
        $stmt->execute();
        $stmt->close();
    } elseif ($stock > 0 && $stock <= $low_stock_limit && $notif_check->num_rows == 0) {
        // Low stock notification
        $msg = "Product <b>$pname</b> is low on stock (only $stock left)!";
        $stmt = $conn->prepare("INSERT INTO messages (content, message) VALUES ('stock', ?)");
        $stmt->bind_param("s", $msg);
        $stmt->execute();
        $stmt->close();
    }
}
// --- END AUTO NOTIFICATION LOGIC ---
?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .notif-bell {
            position: absolute;
            top: 24px;
            right: 32px;
            font-size: 1.7em;
            color: #2ee9c6;
            cursor: pointer;
            z-index: 100;
        }
        .notif-dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 32px;
            background: #23272b;
            color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 32px #2ee9c655;
            min-width: 320px;
            max-width: 90vw;
            z-index: 101;
        }
        .notif-dropdown.active {
            display: block;
        }
        .notif-dropdown h4 {
            margin: 0;
            padding: 14px 18px 8px 18px;
            color: #2ee9c6;
            font-size: 1.1em;
            border-bottom: 1px solid #2ee9c6;
        }
        .notif-item {
            padding: 12px 18px;
            border-bottom: 1px solid #2ee9c655;
            font-size: 1em;
        }
        .notif-item:last-child {
            border-bottom: none;
        }
        .notif-empty {
            padding: 18px;
            color: #aaa;
            text-align: center;
        }
        .notif-badge {
            position: absolute;
            top: 16px;
            right: 28px;
            background: #e74c3c;
            color: #fff;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.95em;
            text-align: center;
            line-height: 20px;
            font-weight: bold;
            border: 2px solid #23272b;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Beans Appetite</h2>
    <a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo 'active'; ?>">Dashboard</a>
    <a href="orders.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='orders.php') echo 'active'; ?>">Orders</a>
    <a href="customers.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='customers.php') echo 'active'; ?>">Customers</a>
    <a href="messages.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='messages.php') echo 'active'; ?>">Messages</a>
    <a href="products.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='products.php') echo 'active'; ?>">Products</a>
    <div class="sidebar-bottom">
        <a href="settings.php" class="settings-btn<?php if(basename($_SERVER['PHP_SELF'])=='settings.php') echo ' active'; ?>">Settings</a>
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
</div>
<div class="main" style="position:relative;">
    <!-- Notification Bell -->
    <?php
    // Count unread/stock notifications
    $notif_sql = "SELECT * FROM messages WHERE content='stock' ORDER BY id DESC LIMIT 10";
    $notif_res = $conn->query($notif_sql);
    $notif_count = $notif_res->num_rows;
    ?>
    <span class="notif-bell" onclick="toggleNotif()">
        &#128276;
        <?php if($notif_count > 0): ?>
            <span class="notif-badge"><?= $notif_count ?></span>
        <?php endif; ?>
    </span>
    <div class="notif-dropdown" id="notifDropdown">
        <h4>Notifications</h4>
        <?php
        if($notif_count == 0) {
            echo '<div class="notif-empty">No notifications.</div>';
        } else {
            while($notif = $notif_res->fetch_assoc()) {
                echo '<div class="notif-item">'. $notif['message'] .'</div>';
            }
        }
        ?>
    </div>
    <h1>Messages</h1>
    <?php
    // Delete
    if(isset($_GET['delete'])) {
        $conn->query("DELETE FROM messages WHERE id=".$_GET['delete']);
        echo "<script>location.href='messages.php';</script>";
    }
    ?>
    <!-- Messages Table -->
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Content</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM messages ORDER BY date DESC");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['content']}</td>
                <td>{$row['message']}</td>
                <td>{$row['date']}</td>
                <td>
                    <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this message?')\">Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
<script>
function toggleNotif() {
    var dd = document.getElementById('notifDropdown');
    dd.classList.toggle('active');
    // Close when clicking outside
    document.addEventListener('click', function handler(e) {
        if (!dd.contains(e.target) && !e.target.classList.contains('notif-bell')) {
            dd.classList.remove('active');
            document.removeEventListener('click', handler);
        }
    });
}
</script>
</body>
</html>
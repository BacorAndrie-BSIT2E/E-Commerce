<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
<script>
if(localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
    document.body.classList.add('dark');
}
</script>
    <title>Orders</title>
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
        <a href="settings.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='settings.php') echo 'active'; ?>">Settings</a>
        <a href="logout.php">Log Out</a>
    </div>
</div>
<div class="main">
    <h1>Orders</h1>
    <?php
    // Fetch order for editing
    $edit = null;
    if(isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $edit = $conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();
    }
    ?>
    <!-- Add/Edit Order Form -->
    <form method="POST" style="margin-bottom:20px;">
        <input type="hidden" name="id" value="<?php echo $edit ? $edit['id'] : ''; ?>">
        <select name="customer_id" required>
            <option value="">Select Customer</option>
            <?php
            $customers = $conn->query("SELECT id, username FROM users WHERE role='customer'");
            while($c = $customers->fetch_assoc()) {
                $selected = $edit && $edit['customer_id'] == $c['id'] ? 'selected' : '';
                echo "<option value='{$c['id']}' $selected>{$c['username']}</option>";
            }
            ?>
        </select>
        <select name="product_id" required>
            <option value="">Select Product</option>
            <?php
            $products = $conn->query("SELECT id, name FROM products");
            while($p = $products->fetch_assoc()) {
                $selected = $edit && $edit['product_id'] == $p['id'] ? 'selected' : '';
                echo "<option value='{$p['id']}' $selected>{$p['name']}</option>";
            }
            ?>
        </select>
        <input type="number" name="quantity" placeholder="Quantity" min="1" required value="<?php echo $edit ? $edit['quantity'] : ''; ?>">
        <input type="text" name="location" placeholder="Location" required value="<?php echo $edit ? htmlspecialchars($edit['location']) : ''; ?>">
        <select name="status" required>
            <option value="pending" <?php echo ($edit && $edit['status']=='pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="completed" <?php echo ($edit && $edit['status']=='completed') ? 'selected' : ''; ?>>Completed</option>
        </select>
        <button type="submit" name="<?php echo $edit ? 'update' : 'add'; ?>">
            <?php echo $edit ? 'Update Order' : 'Add Order'; ?>
        </button>
        <?php if($edit): ?>
            <a href="orders.php" style="margin-left:10px;color:#3CCFAD;text-decoration:underline;">Cancel</a>
        <?php endif; ?>
    </form>
    <?php
    // Add
    if(isset($_POST['add'])) {
        $pid = $_POST['product_id'];
        $qty = $_POST['quantity'];
        $price = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc()['price'];
        $total = $price * $qty;
        $stmt = $conn->prepare("INSERT INTO orders (customer_id, product_id, quantity, total_amount, status, location) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidss", $_POST['customer_id'], $pid, $qty, $total, $_POST['status'], $_POST['location']);
        $stmt->execute();
        echo "<script>location.href='orders.php';</script>";
    }
    // Update
    if(isset($_POST['update'])) {
        $pid = $_POST['product_id'];
        $qty = $_POST['quantity'];
        $price = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc()['price'];
        $total = $price * $qty;
        $stmt = $conn->prepare("UPDATE orders SET customer_id=?, product_id=?, quantity=?, total_amount=?, status=?, location=? WHERE id=?");
        $stmt->bind_param("iiidssi", $_POST['customer_id'], $pid, $qty, $total, $_POST['status'], $_POST['location'], $_POST['id']);
        $stmt->execute();
        echo "<script>location.href='orders.php';</script>";
    }
    // Delete
    if(isset($_GET['delete'])) {
        $conn->query("DELETE FROM orders WHERE id=".intval($_GET['delete']));
        echo "<script>location.href='orders.php';</script>";
    }
    ?>
    <!-- Orders Table -->
    <table>
        <tr>
            <th>ID</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th><th>Status</th><th>Location</th><th>Date</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT o.*, u.username, p.name as product_name FROM orders o
            LEFT JOIN users u ON o.customer_id = u.id
            LEFT JOIN products p ON o.product_id = p.id
            ORDER BY o.created_at DESC");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['total_amount']}</td>
                <td>{$row['status']}</td>
                <td>{$row['location']}</td>
                <td>{$row['created_at']}</td>
                <td>
                    <a href='?edit={$row['id']}'>Edit</a> | 
                    <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this order?')\">Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
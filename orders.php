<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <link rel="stylesheet" href="style.css">
     <style>
        .orders-table th,
        .orders-table td {
            padding: 8px 10px;
            font-size: 0.98em;
            white-space: nowrap;
        }
        .orders-table {
            font-size: 0.97em;
            table-layout: auto;
        }
        @media (max-width: 1200px) {
            .orders-table th,
            .orders-table td {
                font-size: 0.93em;
                padding: 6px 6px;
            }
            .orders-table {
                font-size: 0.93em;
            }
        }
        .main-header-bar {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 24px;
            margin-right: 48px;
            margin-bottom: 18px;
        }
        @media (max-width: 900px) {
            .main {
                margin-left: 10;
                padding: 20px 0 0 0;
            }
            .orders-table-container {
                margin-left: 10;
                margin-top: 12px;
            }
            .orders-table {
                width: 98vw;
                min-width: 80px;
            }
            .main-header-bar {
                margin-right: 8px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
        .orders-table-container {
            width: 100%;
            margin-left: 0px;
            margin-top: 24px;
            display: block;
        }
        .orders-table {
            width: calc(100% - 32px);
            min-width: 600px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px #2228;
            background: #181a1b;
            margin: 10;
        }
        .edit-tab-bg {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.45); /* semi-transparent black */
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .edit-tab {
            background: rgba(34, 34, 34, 0.85);
            border: 1.5px solid #3CCFAD;
            padding: 28px 28px 20px 28px;
            border-radius: 16px;
            max-width: 400px;
            width: 100%;
            min-width: 280px;
            box-shadow: 0 8px 32px 0 rgba(60, 207, 173, 0.25);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
            position: fixed; /* ✅ ito ang mahalaga */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center */
            z-index: 1001;
        }
        @media (max-width: 500px) {
            .edit-tab {
                max-width: 95vw;
                padding: 18px 6vw 18px 6vw;
                min-width: unset;
            }
        }
        .edit-tab input, .edit-tab select {
            margin-bottom: 14px;
            width: 100%;
            padding: 10px;
            background: #23272b;
            color: #fff;
            border: 1.5px solid #3CCFAD;
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box;
        }
        .edit-tab button {
            background: linear-gradient(90deg, #2ee9c6 60%, #3CCFAD 100%);
            border: none;
            padding: 10px 24px;
            color: #23272b;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
            transition: background 0.2s, color 0.2s;
            margin-top: 6px;
        }
        .edit-tab button:hover {
            background: linear-gradient(90deg, #3CCFAD 60%, #2ee9c6 100%);
            color: #222;
        }
        .edit-tab a {
            color: #3CCFAD;
            margin-left: 10px;
            text-decoration: underline;
        }
        .edit-tab-close {
            position: absolute;
            top: 10px;
            right: 16px;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            background: none;
            border: none;
            font-weight: bold;
        }
    </style>
</head>
<div class="sidebar">
    <h2>Beans Appetite</h2>
    <a href="index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php') echo 'active'; ?>">Dashboard</a>
    <a href="orders.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='orders.php') echo 'active'; ?>">Orders</a>
    <a href="customers.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='customers.php') echo 'active'; ?>">Customers</a>
    <a href="messages.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='messages.php') echo 'active'; ?>">Messages</a>
    <a href="products.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='products.php') echo 'active'; ?>">Products</a>
    <div class="sidebar-bottom">
        <a href="settings.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='settings.php') echo 'active'; ?>">Settings</a>
        <a href="logout.php">Log Out</a>
    </div>
</div>
<div class="main">
    <div class="main-header-bar">
        <h1 style="margin:0;">Orders</h1>
        <form method="GET" style="margin-bottom:0; display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" placeholder="Search by Customer or Product" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <select name="filter_product">
                <option value="">All Products</option>
                <?php
                $products = $conn->query("SELECT id, name FROM products");
                while($p = $products->fetch_assoc()) {
                    $selected = (isset($_GET['filter_product']) && $_GET['filter_product'] == $p['id']) ? 'selected' : '';
                    echo "<option value='{$p['id']}' $selected>{$p['name']}</option>";
                }
                ?>
            </select>
            <button type="submit">Filter</button>
            <?php if(isset($_GET['search']) || isset($_GET['filter_product'])): ?>
                <a href="orders.php" style="margin-left:10px;color:#3CCFAD;text-decoration:underline;">Clear Filter</a>
            <?php endif; ?>
        </form>
    </div>

    <?php
    $edit = null;
    if(isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $edit = $conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();
    }

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

    if(isset($_GET['delete'])) {
        $conn->query("DELETE FROM orders WHERE id=".intval($_GET['delete']));
        echo "<script>location.href='orders.php';</script>";
    }
    ?>

    <?php if($edit): ?>
    <div class="edit-tab-bg" onclick="window.location='orders.php'"></div>
    <div class="edit-tab">
        <form method="POST">
            <button type="button" class="edit-tab-close" onclick="window.location='orders.php'">&times;</button>
            <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
            <label>Customer:</label>
            <select name="customer_id" required>
                <?php
                $customers = $conn->query("SELECT id, username FROM users WHERE role='customer'");
                while($c = $customers->fetch_assoc()) {
                    $selected = $edit['customer_id'] == $c['id'] ? 'selected' : '';
                    echo "<option value='{$c['id']}' $selected>{$c['username']}</option>";
                }
                ?>
            </select>
            <label>Product:</label>
            <select name="product_id" required>
                <?php
                $products = $conn->query("SELECT id, name FROM products");
                while($p = $products->fetch_assoc()) {
                    $selected = $edit['product_id'] == $p['id'] ? 'selected' : '';
                    echo "<option value='{$p['id']}' $selected>{$p['name']}</option>";
                }
                ?>
            </select>
            <label>Quantity:</label>
            <input type="number" name="quantity" min="1" required value="<?php echo $edit['quantity']; ?>">
            <label>Location:</label>
            <input type="text" name="location" required value="<?php echo htmlspecialchars($edit['location']); ?>">
            <label>Status:</label>
            <select name="status" required>
                <option value="pending" <?php echo ($edit['status']=='pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="completed" <?php echo ($edit['status']=='completed') ? 'selected' : ''; ?>>Completed</option>
            </select>
            <button type="submit" name="update">Update Order</button>
            <a href="orders.php">Cancel</a>
        </form>
    </div>
    <?php endif; ?>

    <div class="orders-table-container">
        <table class="orders-table">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>Location</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT 
                        o.id, 
                        u.username AS customer, 
                        p.name AS product, 
                        o.quantity, 
                        o.total_amount, 
                        o.status, 
                        o.location, 
                        o.created_at 
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN products p ON o.product_id = p.id
                    ORDER BY o.id DESC";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>".htmlspecialchars($row['customer'])."</td>
                    <td>".htmlspecialchars($row['product'])."</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['total_amount']}</td>
                    <td>{$row['status']}</td>
                    <td>".htmlspecialchars($row['location'])."</td>
                    <td>{$row['created_at']}</td>
                    <td>
                        <a href='orders.php?edit={$row['id']}'>Edit</a> |
                        <a href=\"orders.php?delete={$row['id']}\" onclick=\"return confirm('Are you sure?')\">Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
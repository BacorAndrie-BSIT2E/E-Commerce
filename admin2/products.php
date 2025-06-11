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
    <h1>Products</h1>
    <?php
    // Fetch product for editing
    $edit = null;
    if(isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $edit = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
    }
    ?>
    <!-- Add/Edit Product Form -->
    <form method="POST" style="margin-bottom:20px;">
        <input type="hidden" name="id" value="<?php echo $edit ? $edit['id'] : ''; ?>">
        <input type="text" name="name" placeholder="Product Name" required value="<?php echo $edit ? htmlspecialchars($edit['name']) : ''; ?>">
        <input type="number" step="0.01" name="price" placeholder="Price" required value="<?php echo $edit ? $edit['price'] : ''; ?>">
        <input type="number" name="stock" placeholder="Stock" required value="<?php echo $edit ? $edit['stock'] : ''; ?>">
        <button type="submit" name="<?php echo $edit ? 'update' : 'add'; ?>">
            <?php echo $edit ? 'Update Product' : 'Add Product'; ?>
        </button>
        <?php if($edit): ?>
            <a href="products.php" style="margin-left:10px;color:#3CCFAD;text-decoration:underline;">Cancel</a>
        <?php endif; ?>
    </form>
    <?php
    // Add
    if(isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $_POST['name'], $_POST['price'], $_POST['stock']);
        $stmt->execute();
        echo "<script>location.href='products.php';</script>";
    }
    // Update
    if(isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stock=? WHERE id=?");
        $stmt->bind_param("sdii", $_POST['name'], $_POST['price'], $_POST['stock'], $_POST['id']);
        $stmt->execute();
        echo "<script>location.href='products.php';</script>";
    }
    // Delete
    if(isset($_GET['delete'])) {
        $conn->query("DELETE FROM products WHERE id=".intval($_GET['delete']));
        echo "<script>location.href='products.php';</script>";
    }
    ?>
    <!-- Products Table -->
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM products");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>".htmlspecialchars($row['name'])."</td>
                <td>₱{$row['price']}</td>
                <td>{$row['stock']}</td>
                <td>
                    <a href='?edit={$row['id']}'>Edit</a> | 
                    <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this product?')\">Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
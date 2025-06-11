<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
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
    <h1>Customers</h1>
    <?php
    $edit = null;
    if(isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $edit = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
    }
    ?>
    <!-- Add/Edit Customer Form -->
    <form method="POST" style="margin-bottom:20px;">
        <input type="hidden" name="id" value="<?php echo $edit ? $edit['id'] : ''; ?>">
        <input type="text" name="username" placeholder="Username" required value="<?php echo $edit ? htmlspecialchars($edit['username']) : ''; ?>">
        <input type="password" name="password" placeholder="Password" <?php echo $edit ? '' : 'required'; ?>>
        <button type="submit" name="<?php echo $edit ? 'update' : 'add'; ?>">
            <?php echo $edit ? 'Update Customer' : 'Add Customer'; ?>
        </button>
        <?php if($edit): ?>
            <a href="customers.php" style="margin-left:10px;color:#3CCFAD;text-decoration:underline;">Cancel</a>
        <?php endif; ?>
    </form>
    <?php
    // Add
    if(isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'customer')");
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $_POST['username'], $hash);
        $stmt->execute();
        echo "<script>location.href='customers.php';</script>";
    }
    // Update
    if(isset($_POST['update'])) {
        if(!empty($_POST['password'])) {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username=?, password=? WHERE id=?");
            $stmt->bind_param("ssi", $_POST['username'], $hash, $_POST['id']);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
            $stmt->bind_param("si", $_POST['username'], $_POST['id']);
        }
        $stmt->execute();
        echo "<script>location.href='customers.php';</script>";
    }
    // Delete
    if(isset($_GET['delete'])) {
        $conn->query("DELETE FROM users WHERE id=".intval($_GET['delete']));
        echo "<script>location.href='customers.php';</script>";
    }
    ?>
    <!-- Customers Table -->
    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM users WHERE role='customer'");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>".htmlspecialchars($row['username'])."</td>
                <td>
                    <a href='?edit={$row['id']}'>Edit</a> | 
                    <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this customer?')\">Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
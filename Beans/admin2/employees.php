<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Employees</title>
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
    <h1>Employees</h1>
    <?php
    $edit = null;
    if(isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $edit = $conn->query("SELECT * FROM employees WHERE id=$id")->fetch_assoc();
    }
    ?>
    <!-- Add/Edit Employee Form -->
    <form method="POST" style="margin-bottom:20px;">
        <input type="hidden" name="id" value="<?php echo $edit ? $edit['id'] : ''; ?>">
        <input type="text" name="name" placeholder="Name" required value="<?php echo $edit ? htmlspecialchars($edit['name']) : ''; ?>">
        <input type="text" name="position" placeholder="Position" required value="<?php echo $edit ? htmlspecialchars($edit['position']) : ''; ?>">
        <input type="text" name="contact_number" placeholder="Contact Number" required value="<?php echo $edit ? htmlspecialchars($edit['contact_number']) : ''; ?>">
        <input type="number" name="salary" placeholder="Salary" required value="<?php echo $edit ? $edit['salary'] : ''; ?>">
        <button type="submit" name="<?php echo $edit ? 'update' : 'add'; ?>">
            <?php echo $edit ? 'Update Employee' : 'Add Employee'; ?>
        </button>
        <?php if($edit): ?>
            <a href="employees.php" style="margin-left:10px;color:#3CCFAD;text-decoration:underline;">Cancel</a>
        <?php endif; ?>
    </form>
    <?php
    // Add
    if(isset($_POST['add'])) {
        $stmt = $conn->prepare("INSERT INTO employees (name, position, contact_number, salary) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $_POST['name'], $_POST['position'], $_POST['contact_number'], $_POST['salary']);
        $stmt->execute();
        echo "<script>location.href='employees.php';</script>";
    }
    // Update
    if(isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE employees SET name=?, position=?, contact_number=?, salary=? WHERE id=?");
        $stmt->bind_param("sssdi", $_POST['name'], $_POST['position'], $_POST['contact_number'], $_POST['salary'], $_POST['id']);
        $stmt->execute();
        echo "<script>location.href='employees.php';</script>";
    }
    // Delete
    if(isset($_GET['delete'])) {
        $conn->query("DELETE FROM employees WHERE id=".intval($_GET['delete']));
        echo "<script>location.href='employees.php';</script>";
    }
    ?>
    <!-- Employees Table -->
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Position</th><th>Contact Number</th><th>Salary</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM employees");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>".htmlspecialchars($row['name'])."</td>
                <td>".htmlspecialchars($row['position'])."</td>
                <td>".htmlspecialchars($row['contact_number'])."</td>
                <td>{$row['salary']}</td>
                <td>
                    <a href='?edit={$row['id']}'>Edit</a> | 
                    <a href='?delete={$row['id']}' onclick=\"return confirm('Delete this employee?')\">Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
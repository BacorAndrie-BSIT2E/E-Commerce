<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
    <link rel="stylesheet" href="style.css">
</head>

<style>
        .settings-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(60,60,60,0.06);
            padding: 32px 32px 24px 32px;
            max-width: 500px;
            margin: 40px auto;
        }
        .settings-container h2 {
            color: #3CCFAD;
            margin-bottom: 20px;
            text-align: center;
        }
        .settings-form label {
            display: block;
            margin-bottom: 8px;
            color: #5c4033;
            font-weight: 600;
        }
        .settings-form input[type="password"], .settings-form input[type="text"] {
            width: 100%;
            padding: 8px 12px;
            margin-bottom: 18px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        .settings-form button {
            background: #3CCFAD;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 24px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .settings-form button:hover {
            background: #2c3e50;
        }
    </style>
</style>
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
<button class="theme-toggle-btn" id="themeToggleBtn">🌙 Switch to Dark Mode</button>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('themeToggleBtn');
    function setTheme(mode) {
        if(mode === 'dark') {
            document.body.classList.add('dark');
            btn.textContent = '☀️ Switch to Light Mode';
        } else {
            document.body.classList.remove('dark');
            btn.textContent = '🌙 Switch to Dark Mode';
        }
        localStorage.setItem('theme', mode);
    }
    let theme = localStorage.getItem('theme') || 'light';
    setTheme(theme);

    btn.onclick = function() {
        theme = (theme === 'light') ? 'dark' : 'light';
        setTheme(theme);
    };
});
</script>
    <div class="settings-container">
        <h2>Admin Settings</h2>
        <form class="settings-form" method="POST" action=>
            <label for="current_pass">Current Password</label>
            <input type="password" name="current_pass" id="current_pass" required>
            <label for="new_pass">New Password</label>
            <input type="password" name="new_pass" id="new_pass" required>
            <label for="confirm_pass">Confirm New Password</label>
            <input type="password" name="confirm_pass" id="confirm_pass" required>
            <button type="submit" name="change_pass">Change Password</button>
        </form>
        <?php
        if(isset($_POST['change_pass'])) {
            $admin = $conn->query("SELECT * FROM users WHERE id=1")->fetch_assoc();
            if(password_verify($_POST['current_pass'], $admin['password'])) {
                if($_POST['new_pass'] === $_POST['confirm_pass']) {
                    $hash = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
                    $conn->query("UPDATE users SET password='$hash' WHERE id=1");
                    echo "<div style='color:green;margin-top:10px;'>Password changed successfully!</div>";
                } else {
                    echo "<div style='color:red;margin-top:10px;'>New passwords do not match.</div>";
                }
            } else {
                echo "<div style='color:red;margin-top:10px;'>Current password incorrect.</div>";
            }
        }
        ?>
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
</div>
</body>
</html>
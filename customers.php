<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .center-table {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 30px;
        }
        .modal-bg {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0; width: 100vw; height: 100vh;
            background: rgba(44, 62, 80, 0.7);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: rgba(34, 40, 49, 0.95);
            padding: 30px 40px;
            border-radius: 12px;
            min-width: 320px;
            color: #fff;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
            position: relative;
        }
        .modal-content h2 {
            margin-top: 0;
            color: #2ee9c6;
        }
        .close-modal {
            position: absolute;
            top: 12px;
            right: 18px;
            font-size: 1.5em;
            color: #2ee9c6;
            cursor: pointer;
        }
        .filter-bar {
            display: flex;
            justify-content: center;
            margin: 30px 0 10px 0;
        }
        .filter-bar input[type="text"] {
            padding: 8px 12px;
            border-radius: 6px 0 0 6px;
            border: 1px solid #2ee9c6;
            outline: none;
            width: 220px;
            background: #23272b;
            color: #fff;
        }
        .filter-bar button {
            padding: 8px 18px;
            border-radius: 0 6px 6px 0;
            border: none;
            background: #2ee9c6;
            color: #23272b;
            font-weight: bold;
            cursor: pointer;
        }
        .action-link {
            color: #2ee9c6;
            cursor: pointer;
            text-decoration: underline;
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
<div class="main">
    <h1 style="text-align:center;">Customers</h1>
    <div class="filter-bar">
        <form method="get" style="display:flex;">
            <input type="text" name="search" placeholder="Search username..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Filter</button>
        </form>
    </div>
    <div class="center-table">
        <table>
            <tr>
                <th>ID</th><th>Username</th><th>Action</th>
            </tr>
            <?php
            $where = "WHERE role='customer'";
            if (!empty($_GET['search'])) {
                $search = $conn->real_escape_string($_GET['search']);
                $where .= " AND username LIKE '%$search%'";
            }
            $result = $conn->query("SELECT * FROM users $where");
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>".htmlspecialchars($row['username'])."</td>
                    <td>
                        <span class='action-link' onclick='viewCustomer({$row['id']})'>View Info</span>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
    <!-- Modal -->
    <div class="modal-bg" id="modal-bg">
        <div class="modal-content" id="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <div id="customer-info"></div>
        </div>
    </div>
</div>
<script>
function viewCustomer(id) {
    fetch('view_customer.php?id=' + id)
        .then(res => res.text())
        .then(html => {
            document.getElementById('customer-info').innerHTML = html;
            document.getElementById('modal-bg').style.display = 'flex';
        });
}
function closeModal() {
    document.getElementById('modal-bg').style.display = 'none';
}
window.onclick = function(event) {
    var modal = document.getElementById('modal-bg');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
</body>
</html>
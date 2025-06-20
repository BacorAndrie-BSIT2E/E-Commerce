<?php
include 'db.php';
$error = $success = "";
if(isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $imgName = '';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed)) {
            $imgName = uniqid('prod_', true) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$imgName");
        } else {
            $error = "Invalid image type!";
        }
    }
    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, stock, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", $name, $price, $stock, $imgName);
        $stmt->execute();
        $stmt->close();
        $success = "Product added!";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                let btn = document.querySelector('.add-btn');
                btn.classList.add('pulse');
                setTimeout(()=>{btn.classList.remove('pulse');}, 800);
            });
            setTimeout(function(){ location.href='products.php'; }, 900);
        </script>";
    }
}

$edit = null;
if(isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
}

if(isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $imgName = $_POST['current_image'];
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed)) {
            $imgName = uniqid('prod_', true) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$imgName");
        } else {
            $error = "Invalid image type!";
        }
    }
    if (!$error) {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stock=?, image=? WHERE id=?");
        $stmt->bind_param("sdssi", $name, $price, $stock, $imgName, $id);
        $stmt->execute();
        $stmt->close();
        $success = "Product updated!";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                let rows = document.querySelectorAll('tr');
                if(rows[$id]) rows[$id].classList.add('highlight');
            });
            setTimeout(function(){ location.href='products.php'; }, 900);
        </script>";
    }
}

if(isset($_GET['delete'])) {
    $conn->query("DELETE FROM products WHERE id=".intval($_GET['delete']));
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            let btns = document.querySelectorAll('.delete-btn');
            btns.forEach(btn => btn.classList.add('shake'));
        });
        setTimeout(function(){ location.href='products.php'; }, 700);
    </script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .edit-tab, .add-tab {
            background: rgba(34, 34, 34, 0.85);
            border: 1px solid #3CCFAD;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1001;
            box-shadow: 0 8px 32px 0 rgba(60, 207, 173, 0.2);
            color: #fff;
            animation: fadeIn .4s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -60%);}
            to { opacity: 1; transform: translate(-50%, -50%);}
        }
        .edit-tab input, .edit-tab select, .add-tab input, .add-tab select {
            margin-bottom: 10px;
            width: 100%;
            padding: 6px;
            background: #222;
            color: #fff;
            border: 1px solid #3CCFAD;
            border-radius: 4px;
        }
        .edit-tab button, .add-tab button {
            background: #3CCFAD;
            border: none;
            padding: 8px 16px;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }
        .edit-tab button:hover, .add-tab button:hover {
            background: #2ee9c6;
            transform: scale(1.06);
        }
        .edit-tab a, .add-tab a {
            color: #3CCFAD;
            margin-left: 10px;
            text-decoration: underline;
        }
        .edit-tab-close, .add-tab-close {
            position: absolute;
            top: 8px;
            right: 12px;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            background: none;
            border: none;
        }
        .edit-tab-bg, .add-tab-bg {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1000;
            animation: fadeInBg .3s;
        }
        @keyframes fadeInBg {
            from { opacity: 0;}
            to { opacity: 1;}
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #3CCFAD;
        }
        .add-btn {
            background: #3CCFAD;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 4px;
            margin-bottom: 18px;
            cursor: pointer;
            font-size: 16px;
            transition: background .2s, transform .2s;
        }
        .add-btn:hover {
            background: #2ee9c6;
            transform: scale(1.08);
        }
        .add-btn.pulse {
            animation: pulse .7s;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 #2ee9c6; }
            70% { box-shadow: 0 0 0 10px rgba(46,233,198,0);}
            100% { box-shadow: 0 0 0 0 rgba(46,233,198,0);}
        }
        .delete-btn.shake {
            animation: shake .5s;
        }
        @keyframes shake {
            0% { transform: translateX(0);}
            25% { transform: translateX(-4px);}
            50% { transform: translateX(4px);}
            75% { transform: translateX(-4px);}
            100% { transform: translateX(0);}
        }
        tr.highlight {
            animation: highlightRow .8s;
        }
        @keyframes highlightRow {
            0% { background: #2ee9c6; color: #23272b;}
            100% { background: none; color: inherit;}
        }
        .msg-success {
            background: #2ee9c6;
            color: #23272b;
            padding: 10px 0;
            border-radius: 8px;
            margin-bottom: 12px;
            font-weight: bold;
            text-align: center;
        }
        .msg-error {
            background: #e74c3c;
            color: #fff;
            padding: 10px 0;
            border-radius: 8px;
            margin-bottom: 12px;
            font-weight: bold;
            text-align: center;
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
    <h1>Products</h1>
    <?php if($success): ?><div class="msg-success"><?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="msg-error"><?= $error ?></div><?php endif; ?>
    <button class="add-btn" onclick="document.getElementById('addProductModal').style.display='block';document.getElementById('addProductBg').style.display='block';">Add Product</button>
    <div id="addProductBg" class="add-tab-bg" style="display:none;" onclick="document.getElementById('addProductModal').style.display='none';this.style.display='none';"></div>
    <div id="addProductModal" class="add-tab" style="display:none;">
        <form method="POST" enctype="multipart/form-data">
            <button type="button" class="add-tab-close" onclick="document.getElementById('addProductModal').style.display='none';document.getElementById('addProductBg').style.display='none';">&times;</button>
            <h3 style="margin-top:0;">Add Product</h3>
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" name="add">Add Product</button>
        </form>
    </div>

    <?php if($edit): ?>
    <div class="edit-tab-bg" onclick="window.location='products.php'"></div>
    <div class="edit-tab">
        <form method="POST" enctype="multipart/form-data">
            <button type="button" class="edit-tab-close" onclick="window.location='products.php'">&times;</button>
            <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($edit['image']); ?>">
            <h3 style="margin-top:0;">Edit Product</h3>
            <input type="text" name="name" placeholder="Product Name" required value="<?php echo htmlspecialchars($edit['name']); ?>">
            <input type="number" step="0.01" name="price" placeholder="Price" required value="<?php echo $edit['price']; ?>">
            <input type="number" name="stock" placeholder="Stock" required value="<?php echo $edit['stock']; ?>">
            <label>Current Image:</label><br>
            <img src="uploads/<?php echo htmlspecialchars($edit['image']); ?>" class="product-img" alt="Product Image"><br>
            <input type="file" name="image" accept="image/*">
            <button type="submit" name="update">Update Product</button>
            <a href="products.php">Cancel</a>
        </form>
    </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>Image</th><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Action</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM products");
        while($row = $result->fetch_assoc()) {
            $img = htmlspecialchars($row['image']);
            $img_path = "uploads/" . $img;
            if (empty($img) || !file_exists($img_path)) {
                $img_path = "uploads/default.png";
            }
            echo "<tr>
                <td><img src='$img_path' class='product-img' alt='Product Image'></td>
                <td>{$row['id']}</td>
                <td>".htmlspecialchars($row['name'])."</td>
                <td>₱{$row['price']}</td>
                <td>{$row['stock']}</td>
                <td>
                    <a href='?edit={$row['id']}'>Edit</a> | 
                    <a href='?delete={$row['id']}' class='delete-btn' onclick=\"return confirm('Delete this product?')\">Delete</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
<script>
    document.addEventListener('keydown', function(e) {
        if(e.key === "Escape") {
            document.getElementById('addProductModal').style.display='none';
            document.getElementById('addProductBg').style.display='none';
        }
    });
</script>
</body>
</html>
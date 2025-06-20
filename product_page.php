<?php
session_start();
include 'db.php';

$user_address = '';
if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $res = $conn->query("SELECT address FROM users WHERE id=$uid");
    if ($row = $res->fetch_assoc()) {
        $user_address = $row['address'];
    }
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['quantity']);
    if ($qty > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $qty;
        } else {
            $_SESSION['cart'][$product_id] = $qty;
        }
        $cart_msg = "Added to cart!";
    }
}

if (isset($_GET['remove_cart'])) {
    $pid = intval($_GET['remove_cart']);
    unset($_SESSION['cart'][$pid]);
}

$order_success = $order_error = "";
if (isset($_POST['place_order']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart = $_SESSION['cart'];
    $location = $user_address;
    $has_error = false;

    if ($location === '') {
        $order_error = "Your address is missing. Please update your profile.";
        $has_error = true;
    }

    if (!$has_error) {
        foreach ($cart as $pid => $qty) {
            $prod = $conn->query("SELECT stock FROM products WHERE id=$pid")->fetch_assoc();
            if (!$prod || $prod['stock'] < $qty || $qty < 1) {
                $order_error = "Not enough stock for one or more items.";
                $has_error = true;
                break;
            }
        }
    }
    if (!$has_error) {
        foreach ($cart as $pid => $qty) {
            $prod = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc();
            $total = $prod ? $prod['price'] * $qty : 0;
            $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_amount, status, location) VALUES (?, ?, ?, ?, ?, ?)");
            $status = "pending";
            $stmt->bind_param("iiidss", $user_id, $pid, $qty, $total, $status, $location);
            $stmt->execute();
            $stmt->close();
            $conn->query("UPDATE products SET stock = stock - $qty WHERE id = $pid");
        }
        $_SESSION['cart'] = [];
        $order_success = "Order placed!";

        $user = '';
        $user_res = $conn->query("SELECT username FROM users WHERE id=$user_id");
        if ($user_row = $user_res->fetch_assoc()) {
            $user = $user_row['username'];
        }
        $feedback_msg = "User <b>$user</b> placed an order. Ask for feedback about their order experience.";
        $conn->query("INSERT INTO messages (content, message) VALUES ('feedback', '$feedback_msg')");
    }
}
$products = [];
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$cart_products = [];
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $res = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $res->fetch_assoc()) {
        $cart_products[$row['id']] = $row;
    }
}
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beans Appetite - Products</title>
    <style>
        body {
            margin: 0;
            background: url('bg.jpg.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
        }
        .header {
            background: rgba(44, 26, 23, 0.98);
            color: #F5F5DC;
            padding: 24px 0 16px 0;
            text-align: center;
            font-size: 2.2em;
            letter-spacing: 2px;
            font-weight: bold;
            box-shadow: 0 2px 12px rgba(30,30,50,0.16);
            position: relative;
        }
        .nav-btns {
            position: absolute;
            left: 32px;
            top: 18px;
            display: flex;
            gap: 12px;
        }
        .nav-btn {
            background: #F5F5DC;
            color: #7B3F00;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            font-size: 1.3em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(123,63,0,0.4);
            transition: background 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-btn:hover {
            background: #7B3F00;
            color: #fff;
            transform: scale(1.1);
        }
        .cart-btn {
            position: absolute;
            right: 32px;
            top: 24px;
            background: #F5F5DC;
            color: #7B3F00;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            font-size: 1.6em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(123,63,0,0.4);
            transition: background 0.2s, transform 0.2s;
            z-index: 10;
        }
        .cart-btn:hover {
            background: #7B3F00;
            color: #fff;
            transform: scale(1.1);
        }
        .cart-count {
            position: absolute;
            top: 18px;
            right: 26px;
            background: #b22222;
            color: #fff;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 1em;
            text-align: center;
            line-height: 22px;
            font-weight: bold;
            border: 2px solid #fff;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
            margin-top: 30px;
        }
        .product-card {
            background: rgba(44, 26, 23, 0.93);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.18);
            width: 270px;
            padding: 22px 18px 18px 18px;
            color: #F5F5DC;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
            cursor: pointer;
            animation: fadeInUp 0.7s;
        }
        .product-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 16px 40px 0 rgba(123,63,0,0.4);
        }
        .product-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 14px;
            border: 2px solid #7B3F00;
            background: #D7CCC8;
        }
        .product-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #7B3F00;
            margin-bottom: 8px;
        }
        .product-desc {
            font-size: 1em;
            color: #D7CCC8;
            margin-bottom: 10px;
            min-height: 48px;
        }
        .product-price {
            font-size: 1.1em;
            color: #F5F5DC;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .product-stock {
            font-size: 0.98em;
            color: #7B3F00;
            margin-bottom: 12px;
        }
        .add-cart-form button {
            background: linear-gradient(90deg, #7B3F00 65%, #4B2E2B 100%);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .add-cart-form button:hover {
            background: linear-gradient(90deg, #4B2E2B 65%, #7B3F00 100%);
            color: #fff;
        }
        .msg-success {
            background: #7B3F00;
            color: #fff;
            padding: 10px 0;
            border-radius: 8px;
            margin-bottom: 12px;
            font-weight: bold;
            text-align: center;
        }
        .msg-error {
            background: #b22222;
            color: #fff;
            padding: 10px 0;
            border-radius: 8px;
            margin-bottom: 12px;
            font-weight: bold;
            text-align: center;
        }
        .cart-modal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            display: none;
            z-index: 999;
        }

        .cart-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #2c1a17;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            color: #f5f5dc;
            z-index: 1000;
            display: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }

        .close-cart {
            position: absolute;
            top: 10px;
            right: 14px;
            background: none;
            border: none;
            color: #f5f5dc;
            font-size: 1.6em;
            cursor: pointer;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            border-bottom: 1px solid #444;
            padding-bottom: 8px;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #7B3F00;
        }
        .cart-item-title {
            flex: 1;
            font-weight: bold;
        }
        .cart-item-qty {
            color: #aaa;
        }
        .cart-item-remove {
            color: #ff4d4d;
            font-size: 1.2em;
            text-decoration: none;
        }
        .cart-item-remove:hover {
            color: #ff0000;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 14px;
            text-align: right;
        }
        .cart-modal button[name="place_order"] {
            background: linear-gradient(90deg, #7B3F00 65%, #4B2E2B 100%);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }
        .cart-modal button[name="place_order"]:hover {
            background: linear-gradient(90deg, #4B2E2B 65%, #7B3F00 100%);
        }
    </style>
</head>
<body>
    <div class="header">
        ☕ Beans Appetite Products
        <div class="nav-btns">
            <a href="user.php" class="nav-btn" title="Home">
                <span style="font-size:1.2em;">🏠</span>
            </a>
            <a href="product_page.php" class="nav-btn" title="Products">
                <span style="font-size:1.2em;">🛍️</span>
            </a>
            <a href="aboutus.php" class="nav-btn" title="About Us">
                <span style="font-size:1.2em;">ℹ️</span>
            </a>
            <?php if ($is_admin): ?>
            <a href="admin_products.php" class="nav-btn" title="Admin Product Control">
                <span style="font-size:1.2em;">🛠️</span>
            </a>
            <?php endif; ?>
        </div>
        <?php if (!$is_admin && isset($_SESSION['user_id'])): ?>
        <button class="cart-btn" onclick="showCart()">
            🛒
            <?php if (!empty($_SESSION['cart'])): ?>
                <span class="cart-count"><?= array_sum($_SESSION['cart']) ?></span>
            <?php endif; ?>
        </button>
        <?php endif; ?>
    </div>
    <div class="container">

        <?php if (!empty($cart_msg)): ?><div class="msg-success"><?= $cart_msg ?></div><?php endif; ?>
        <?php if ($order_success): ?><div class="msg-success"><?= $order_success ?></div><?php endif; ?>
        <?php if ($order_error): ?><div class="msg-error"><?= $order_error ?></div><?php endif; ?>

        <div class="products-grid">
            <?php foreach ($products as $prod): 
                $img = htmlspecialchars($prod['image']);
                $img_path = "uploads/" . $img;
                if (empty($img) || !file_exists($img_path)) {
                    $img_path = "uploads/default.png";
                }
            ?>
            <div class="product-card">
                <img src="<?= $img_path ?>" alt="Product Image" class="product-img">
                <div class="product-title"><?= htmlspecialchars($prod['name']) ?></div>
                <div class="product-desc"><?= htmlspecialchars($prod['description']) ?></div>
                <div class="product-price">₱<?= number_format($prod['price'],2) ?></div>
                <div class="product-stock">Stock: <?= (int)$prod['stock'] ?></div>
                <?php if (!$is_admin && isset($_SESSION['user_id'])): ?>
                <form method="post" class="add-cart-form" onsubmit="return animateCart(this)">
                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                    <input type="number" name="quantity" min="1" max="<?= (int)$prod['stock'] ?>" value="1" required>
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
                <div style="color:#fff;font-size:1.2em;">No products available.</div>
            <?php endif; ?>
        </div>
    </div>
    <?php if (!$is_admin && isset($_SESSION['user_id'])): ?>
    <div class="cart-modal-bg" id="cartBg" onclick="hideCart()"></div>
    <div class="cart-modal" id="cartModal">
        <button class="close-cart" onclick="hideCart()">&times;</button>
        <h3>Your Cart</h3>
        <?php if (empty($_SESSION['cart'])): ?>
            <div style="text-align:center;color:#fff;">Cart is empty.</div>
        <?php else: ?>
            <?php $total = 0; ?>
            <?php foreach ($_SESSION['cart'] as $pid => $qty): 
                $prod = isset($cart_products[$pid]) ? $cart_products[$pid] : null;
                if (!$prod) continue;
                $img = htmlspecialchars($prod['image']);
                $img_path = "uploads/" . $img;
                if (empty($img) || !file_exists($img_path)) {
                    $img_path = "uploads/default.png";
                }
                $subtotal = $prod['price'] * $qty;
                $total += $subtotal;
            ?>
            <div class="cart-item">
                <img src="<?= $img_path ?>" alt="Product">
                <div class="cart-item-title"><?= htmlspecialchars($prod['name']) ?></div>
                <div class="cart-item-qty">x<?= $qty ?></div>
                <a href="?remove_cart=<?= $pid ?>" class="cart-item-remove" title="Remove">&times;</a>
            </div>
            <?php endforeach; ?>
            <div class="cart-total">Total: ₱<?= number_format($total,2) ?></div>
            <form method="post">
                <div style="margin-bottom:10px;color:#2ee9c6;font-weight:bold;">
                    Location: <?= htmlspecialchars($user_address) ?>
                    <?php if ($user_address == ''): ?>
                        <span style="color:#e74c3c;">(Please update your address in your profile!)</span>
                    <?php endif; ?>
                </div>
                <button type="submit" name="place_order" <?= $user_address == '' ? 'disabled' : '' ?>>Place Order</button>
            </form>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <script>
        function showCart() {
            document.getElementById('cartBg').style.display = 'block';
            document.getElementById('cartModal').style.display = 'block';
        }
        function hideCart() {
            document.getElementById('cartBg').style.display = 'none';
            document.getElementById('cartModal').style.display = 'none';
        }
        function animateCart(form) {
            let btn = form.querySelector('button[type="submit"]');
            btn.style.transform = "scale(1.15)";
            btn.style.background = "linear-gradient(90deg,#3CCFAD 60%,#2ee9c6 100%)";
            setTimeout(() => {
                btn.style.transform = "";
                btn.style.background = "";
            }, 300);
            return true;
        }
    </script>
</body>
</html>
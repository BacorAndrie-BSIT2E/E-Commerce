<?php
include 'db.php';
$notif = [];
// Example: Out of stock products
$res = $conn->query("SELECT name FROM products WHERE stock <= 0");
while($row = $res->fetch_assoc()) {
    $notif[] = "<div style='background:#ffe9b3;padding:8px;margin-bottom:4px;border-radius:5px;color:#a94442;'><b>Out of stock:</b> {$row['name']}</div>";
}
// Example: New orders today
$res = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE DATE(created_at)=CURDATE()");
$row = $res->fetch_assoc();
if ($row['cnt'] > 0) {
    $notif[] = "<div style='background:#e3ffe3;padding:8px;margin-bottom:4px;border-radius:5px;color:#31708f;'>Today's Orders: <b>{$row['cnt']}</b></div>";
}
echo implode("", $notif);
?>
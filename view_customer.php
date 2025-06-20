<?php
include 'db.php';
$id = intval($_GET['id']);
$q = $conn->query("SELECT * FROM users WHERE id=$id AND role='customer'");
if($q->num_rows) {
    $row = $q->fetch_assoc();
    echo "<h2>Customer Info</h2>";
    echo "<p><b>ID:</b> {$row['id']}</p>";
    echo "<p><b>Username:</b> ".htmlspecialchars($row['username'])."</p>";
    echo "<p><b>Email:</b> ".htmlspecialchars($row['email'] ?? 'N/A')."</p>";
    echo "<p><b>Phone Number:</b> ".htmlspecialchars($row['phone'] ?? 'N/A')."</p>";
    echo "<p><b>Address:</b> ".htmlspecialchars($row['address'] ?? 'N/A')."</p>";
} else {
    echo "<p>Customer not found.</p>";
}
?>
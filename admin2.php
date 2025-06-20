<?php
$conn = new mysqli("localhost", "root", "", "ecommerce_db");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

$message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ANIMAPLEX Dashboard</title>
  <link rel="stylesheet" href="admindash.css" />
</head>
<body>

<div class="sidebar">
  <h2>Ecommerce</h2>
  <nav>
    <a data-section="products">Products</a>
    <a data-section="orders" class="active">Orders</a>
    <a data-section="messages">Messages</a>
    <a data-section="users">Users</a>
<form action="logout.php" method="post">
  <button type="submit" class="button">Logout</button>
</form>

  </nav>
</div>

<div class="main">

<div id="orders">
  <h1>Orders</h1>
  <input type="text" class="section-search" placeholder="Search orders...">
  <div id="delete">
  <form method="POST">
  <button type="submit" name="delete_cancelled">Delete Cancelled Orders</button>
</form>
</div>
  <table>
    <thead>
      <tr>
        <th>Order ID</th>
        <th>customer ID</th>
        <th>product ID</th>
        <th>Quantity</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>location</th>
        <th>created at</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM orders";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>#".$row['id']."</td>";
          echo "<td>".htmlspecialchars($row['customer_id'])."</td>";
          echo "<td>".htmlspecialchars($row['product_id'])."</td>";
          echo "<td>".htmlspecialchars($row['quantity'])."</td>";
          echo "<td>".htmlspecialchars($row['total_amount'])."</td>";
          echo "<td>".htmlspecialchars($row['status'])."</td>";
          echo "<td>".htmlspecialchars($row['location'])."</td>";
          echo "<td>".htmlspecialchars($row['created_at'])."</td>";

          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='11'>No orders found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<div id="messages" class="hidden">
  <h1>Messages</h1>
  <input type="text" class="section-search" placeholder="Search messages...">
  <table>
    <thead>
      <tr>
        <th>Message ID</th>
        <th>Username</th>
        <th>Message</th>
        <th>Sent At</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM messages ORDER BY created_at DESC";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>#".htmlspecialchars($row['id'])."</td>";
          echo "<td>".htmlspecialchars($row['sender'])."</td>";
          echo "<td>".nl2br(htmlspecialchars($row['message']))."</td>";
          echo "<td>".htmlspecialchars($row['created_at'])."</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='4'>No messages found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<div id="users" class="hidden">
  <h1>Users</h1>
  <input type="text" class="section-search" placeholder="Search users...">
  <table>
    <thead><tr><th>User ID</th><th>Username</th><th>Password</th></tr></thead>
    <tbody>
      <?php
        $sql = "SELECT id, username, password FROM users WHERE role = 'customer'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['id']}</td><td>".htmlspecialchars($row['username'])."</td><td>".htmlspecialchars($row['password'])."</td></tr>";
          }
        } else { echo "<tr><td colspan='3'>No users found.</td></tr>"; }
      ?>
    </tbody>
  </table>
</div>

<div id="Products" class="hidden">
  <h1>Products</h1>
  <input type="text" class="section-search" placeholder="Search products...">
  <table>
    <thead>
      <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Stock</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM products";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>".htmlspecialchars($row['id'])."</td>";
          echo "<td>".htmlspecialchars($row['name'])."</td>";
          echo "<td>".htmlspecialchars($row['price'])."</td>";
          echo "<td>".htmlspecialchars($row['stock'])."</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='4'>No products found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script>
document.querySelectorAll('.sidebar nav a').forEach(link => {
  link.addEventListener('click', () => {
    document.querySelectorAll('.sidebar nav a').forEach(a => a.classList.remove('active'));
    link.classList.add('active');
    document.querySelectorAll('.main > div').forEach(div => div.classList.add('hidden'));
    const section = link.getAttribute('data-section');
    if(section){
      document.getElementById(section).classList.remove('hidden');
    }
  });
});

document.getElementById('toggleAddMovieBtn').addEventListener('click', () => {
  const formContainer = document.getElementById('addMovieFormContainer');
  formContainer.classList.toggle('hidden');
});

// Search bar filtering functionality
document.querySelectorAll('.section-search').forEach(searchInput => {
  searchInput.addEventListener('input', () => {
    const section = searchInput.closest('div');
    const filter = searchInput.value.toLowerCase();
    const table = section.querySelector('table');
    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(row => {
      const rowText = row.textContent.toLowerCase();
      if (rowText.indexOf(filter) > -1) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
});
</script>
</body>
</html>
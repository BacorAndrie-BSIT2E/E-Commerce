<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="chart.js" defer></script>
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

  <!-- Main Content -->
  <div class="main">
<div class="dashboard-cards">
  <div class="cards-row">
    <div class="dashboard-card">
      <div class="card-title">Earnings</div>
      <div class="card-value" id="earnings">₱0</div>
    </div>
    <div class="dashboard-card">
      <div class="card-title">Products</div>
      <div class="card-value" id="products-count">0</div>
    </div>
    <div class="dashboard-card">
      <div class="card-title">Orders</div>
      <div class="card-value" id="orders-count">0</div>
    </div>
    <div class="dashboard-card">
      <div class="card-title">Revenue Update</div>
      <div class="card-value" id="revenue-update">₱0 This Month</div>
    </div>
    <div class="dashboard-card">
      <div class="card-title">Customers</div>
      <div class="card-value" id="customers-count">0</div>
    </div>
  </div>
  <div class="cards-row">
    <div class="dashboard-card">
      <div class="card-title">Employees</div>
      <div class="card-value" id="employees-count">0</div>
    </div>
    <div class="dashboard-card">
      <div class="card-title">Messages</div>
      <div class="card-value" id="messages-count">0</div>
    </div>
  </div>
</div>

    <!-- Charts -->
    <div class="dashboard-charts">
      <div class="chart-box">
        <h3 style="text-align: center; color: #2c3e50;">Revenue In Month</h3>
        <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
      </div>
<div class="chart-box">
  <h3 style="text-align: center; color: #2c3e50;">Top Selling Products</h3>
  <canvas id="topProductsChart" width="400" height="260"></canvas>
</div>
    </div>
    <div id="datetime" style="font-weight: bold; margin-top: 30px;"></div>
  </div>

<script>
  // DateTime Display
  function updateDateTime() {
    const now = new Date();
    const datetimeElement = document.getElementById('datetime');
    const options = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    };
    datetimeElement.textContent = now.toLocaleDateString('en-PH', options);
  }
  setInterval(updateDateTime, 1000);
  updateDateTime();

  // Notification Loader
  function loadNotification() {
    fetch("notification.php")
      .then(res => res.text())
      .then(html => {
        document.getElementById("notification-bar").innerHTML = html;
      });
  }
  loadNotification();
  setInterval(loadNotification, 15000);

  // Dynamic dashboard cards
  function loadStats() {
    fetch('dashboard_stats.php')
      .then(res => res.json())
      .then(stats => {
        document.getElementById('earnings').textContent = '₱' + Number(stats.earnings).toLocaleString();
        document.getElementById('products-count').textContent = stats.products;
        document.getElementById('orders-count').textContent = stats.orders;
        document.getElementById('customers-count').textContent = stats.customers;
        document.getElementById('employees-count').textContent = stats.employees;
        document.getElementById('messages-count').textContent = stats.messages;
        document.getElementById('revenue-update').textContent = '₱' + Number(stats.revenue_month).toLocaleString() + ' This Month';
      });
  }
  loadStats();
  setInterval(loadStats, 15000);
</script>
</body>
</html>
<?php
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="chart.js" defer></script>
  <style>
    .logout-btn, .settings-btn {
      display: inline-block;
      margin: 10px 0 0 0;
      padding: 8px 18px;
      background: #3CCFAD;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.2s;
      text-decoration: none;
    }
    .logout-btn:hover, .settings-btn:hover {
      background: #2c3e50;
      color: #fff;
    }
    .dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }
    .dashboard-header h1 {
      margin: 0;
      font-size: 2rem;
      color: #5c4033;
    }
    .dashboard-actions {
      display: flex;
      gap: 10px;
    }
    .notification-bar {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="index.php">Dashboard</a>
    <a href="orders.php">Orders</a>
    <a href="employees.php">Employees</a>
    <a href="customers.php">Customers</a>
    <a href="messages.php">Messages</a>
    <a href="products.php">Products</a>
    <a href="settings.php" class="settings-btn">Settings</a>
    <a href="logout.php" class="logout-btn">Log Out</a>
  </div>

  <!-- Main Content -->
  <div class="main">
    <div class="dashboard-header">
      <h1>Dashboard</h1>
      <div class="dashboard-actions">
        <a href="settings.php" class="settings-btn">Settings</a>
        <a href="logout.php" class="logout-btn">Log Out</a>
      </div>
    </div>

    <div class="notification-bar" id="notification-bar"></div>

    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
      <div class="card-container">
        <div class="card">
          <h3>Earnings</h3>
          <p class="value" id="earnings">₱100,000</p>
        </div>
        <div class="card">
          <h3>Products</h3>
          <p class="value" id="products-count">2</p>
        </div>
        <div class="card">
          <h3>Orders</h3>
          <p class="value" id="orders-count">3</p>
        </div>
        <div class="card">
          <h3>Revenue Update</h3>
          <p class="value" id="revenue-update">₱0 This Month</p>
        </div>
        <div class="card">
          <h3>Customers</h3>
          <p class="value" id="customers-count">2</p>
        </div>
      </div>
      <div class="card-container">
        <div class="card">
          <h3>Employees</h3>
          <p class="value" id="employees-count">0</p>
        </div>
        <div class="card">
          <h3>Messages</h3>
          <p class="value" id="messages-count">0</p>
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
        <canvas id="topProductsChart" width="400" height="400"></canvas>
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

  // (Optional) Dynamic card values via AJAX (if you want to make them dynamic)
  // Example: fetch('dashboard_stats.php').then(...);
</script>
</body>
</html>




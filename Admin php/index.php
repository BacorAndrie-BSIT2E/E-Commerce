<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="piechart.js" defer></script>
  <style>
    .notification-wrapper {
      position: absolute;
      top: 10px;
      right: 20px;
    }
    .notification-btn {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
    }
    .notification-dropdown {
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 10px;
      width: 200px;
      position: absolute;
      top: 30px;
      right: 0;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .notification-dropdown.hidden {
      display: none;
    }
    .notification-dropdown ul {
      padding-left: 16px;
    }
    .notification-dropdown ul li {
      font-size: 14px;
      margin: 6px 0;
    }
  </style>
</head>
<body>

  <button class="menu-toggle" onclick="toggleSidebar()">☰</button>

  <!-- Notification Bell -->
  <div class="notification-wrapper">
    <button class="notification-btn" onclick="toggleNotifications()">🔔</button>
    <div class="notification-dropdown hidden" id="notificationDropdown">
      <p><strong>Notifications</strong></p>
      <ul>
        <li>New order received</li>
        <li>Customer message</li>
        <li>Stock low: Latte</li>
      </ul>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="index.php">Dashboard</a>
    <a href="orders.php">Orders</a>
    <a href="employees.php">Employees</a>
    <a href="customers.php">Customers</a>
    <a href="messages.php">Messages</a>
    <a href="products.php">Products</a>
    <a href="#">Settings</a>
    <a href="#">Log Out</a>
  </div>

  <!-- Main Content -->
  <div class="main">
    <h1>Dashboard</h1>

    <div class="card-container">
      <div class="card">
        <h3>Earnings</h3>
        <p>₱100,000</p>
      </div>
      <div class="card">
        <h3>Products</h3>
        <?php
          $result = $conn->query("SELECT COUNT(*) AS total FROM products");
          $row = $result->fetch_assoc();
          echo "<p>" . $row['total'] . "</p>";
        ?>
      </div>
      <div class="card">
        <h3>Orders</h3>
        <?php
          $result = $conn->query("SELECT COUNT(*) AS total FROM orders");
          $row = $result->fetch_assoc();
          echo "<p>" . $row['total'] . "</p>";
        ?>
      </div>
      <div class="card">
        <h3>Revenue Update</h3>
        <p id="revenue-update">₱0 This Month</p>
      </div>
      <div class="card">
        <h3>Customers</h3>
        <?php
          $result = $conn->query("SELECT COUNT(*) AS total FROM customers");
          $row = $result->fetch_assoc();
          echo "<p>" . $row['total'] . "</p>";
        ?>
      </div>
      <div class="card">
        <h3>Employees</h3>
        <?php
          $result = $conn->query("SELECT COUNT(*) AS total FROM employees");
          $row = $result->fetch_assoc();
          echo "<p>" . $row['total'] . "</p>";
        ?>
      </div>
      <div class="card">
        <h3>Messages</h3>
        <?php
          $result = $conn->query("SELECT COUNT(*) AS total FROM messages");
          $row = $result->fetch_assoc();
          echo "<p>" . $row['total'] . "</p>";
        ?>
      </div>
    </div>

    <!-- Charts -->
    <div class="dashboard-charts">
      <div class="chart-box">
        <h3 style="text-align: center; color: #2c3e50;">Revenue In Month</h3>
        <canvas id="monthlyRevenueChart"></canvas>
      </div>
      <div class="chart-box">
        <h3 style="text-align: center; color: #2c3e50;">Top Selling Products</h3>
        <canvas id="topProductsChart"></canvas>
      </div>
    </div>
  </div>
  <!-- JavaScript for Charts and Revenue Update -->
<div id="datetime" style="font-weight: bold;"></div>
<div id="revenue-update">Loading revenue...</div>
<canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
<div id="notification-container"></div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // REVENUE CHART
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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

    fetch('monthly_revenue.php')
      .then(response => response.json())
      .then(data => {
        const monthlyRevenueData = labels.map(month => data[month] || 0);
        const now = new Date();
        const currentMonth = now.getMonth();
        const revenueValue = monthlyRevenueData[currentMonth];
        document.getElementById('revenue-update').textContent = `₱${revenueValue.toLocaleString()} This Month`;

        const barCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(barCtx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Monthly Revenue',
              data: monthlyRevenueData,
              backgroundColor: '#3CCFAD',
              borderRadius: 5
            }]
          },
          options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: true, ticks: { stepSize: 1000 } }
            }
          }
        });
      })
      .catch(error => {
        console.error('Error fetching revenue data:', error);
        document.getElementById('revenue-update').textContent = 'Failed to load revenue.';
      });

    // PIE CHART
    const pieCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: ['Espresso', 'Latte', 'Cappuccino', 'Cold Brew', 'Mocha'],
        datasets: [{
          data: [25, 20, 22, 18, 15],
          backgroundColor: ['#00C49F', '#02B5A5', '#38D9A9', '#3C99DC', '#A259FF'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' }
        }
      }
    });

    // SIDEBAR TOGGLE
    window.toggleSidebar = function () {
      document.querySelector('.sidebar').classList.toggle('active');
    };

    window.toggleNotifications = function () {
      document.getElementById("notificationDropdown").classList.toggle("hidden");
    };
  });
</script>
<script>
  setInterval(function() {
    fetch("notification.php")
      .then(res => res.text())
      .then(html => {
        document.querySelector(".notification-bar").innerHTML = html;
      });
  }, 5000); // refresh every 5 seconds
</script>
<script>
function loadNotification() {
  fetch("notification.php")
    .then(res => res.text())
    .then(html => {
      const container = document.getElementById("notification-container");
      container.innerHTML = html;

      // Optional: auto-hide after 10 seconds
      setTimeout(() => { container.innerHTML = ""; }, 10000);
    });
}

// Load initially
loadNotification();

// Refresh every 15 seconds
setInterval(loadNotification, 15000);
</script>
</body>
</html>




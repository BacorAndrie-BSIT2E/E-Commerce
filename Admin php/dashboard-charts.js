document.addEventListener('DOMContentLoaded', () => {
  // Monthly Revenue Bar Chart
  const monthlyRevenueChart = new Chart(document.getElementById('monthlyRevenueChart'), {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Monthly Revenue',
        data: [1200, 1850, 2900, 2450, 2750, 3500],
        backgroundColor: '#3CCFAD',
        borderRadius: 8
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 500
          }
        }
      }
    }
  });

  // Top Products Pie Chart
  const topProductsChart = new Chart(document.getElementById('topProductsChart'), {
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
        legend: {
          position: 'top'
        }
      }
    }
  });
});
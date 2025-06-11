document.addEventListener('DOMContentLoaded', () => {
  // Monthly Revenue Bar Chart
  fetch('monthly_revenue.php')
    .then(response => response.json())
    .then(data => {
      new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Monthly Revenue (₱)',
            data: data.data,
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
                callback: function(value) {
                  return '₱' + value.toLocaleString();
                }
              }
            }
          }
        }
      });
    });

  // Top Products Pie Chart
  fetch('top_product.php')
    .then(response => response.json())
    .then(data => {
      new Chart(document.getElementById('topProductsChart'), {
        type: 'pie',
        data: {
          labels: data.labels,
          datasets: [{
            data: data.data,
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
});
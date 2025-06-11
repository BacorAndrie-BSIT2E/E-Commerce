fetch('top_products.php')
  .then(response => response.json())
  .then(data => {
    const ctx = document.getElementById("topProductsChart").getContext("2d");
    new Chart(ctx, {
      type: "pie",
      data: {
        labels: data.labels,
        datasets: [{
          label: "Top Products",
          data: data.values,
          backgroundColor: ["#1abc9c", "#16a085", "#2ecc71", "#3498db", "#9b59b6"]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });
  })
  .catch(error => {
    console.error("Failed to load top products chart:", error);
  });
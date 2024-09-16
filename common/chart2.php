<?php
// Get the current year
$current_year = date("Y");

// Prepare the SQL query to select invoices and their corresponding total sell amounts for each month of the current year
$sql = "SELECT DATE_FORMAT(invoices.date, '%Y-%m') AS month, SUM(invoices_details.sell) AS total_sell
        FROM invoices 
        INNER JOIN invoices_details ON invoices.id = invoices_details.invoices_id 
        WHERE DATE_FORMAT(invoices.date, '%Y') = ?
        GROUP BY month
        ORDER BY month ASC";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $param_year);

// Set the parameter to the current year
$param_year = $current_year;

// Execute the statement
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$dataset = [];
$labelset = [];

// Prepare the data for Chart.js
while ($row = mysqli_fetch_array($result)) {
    $labelset[] = date("F", strtotime($row['month'])); // Convert 'YYYY-MM' to the full month name
    $dataset[] = $row['total_sell'];
}
?>

<div>
    <div class="container-fluid mt-2">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <canvas class="my-4" id="myChart" width="28" height="10"></canvas>
            </main>
        </div>
    </div>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labelset); ?>,
                datasets: [{
                    data: <?php echo json_encode($dataset); ?>,
                    lineTension: 0,
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
                    borderWidth: 4,
                    pointBackgroundColor: '#007bff'
                }]
            },
            options: {
              scales: {
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'  // Horizontal axis label
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Total Sell'  // Vertical axis label
                    },
                    ticks: {
                        beginAtZero: false
                    }
                }]
              },
              legend: {
                  display: true,
                  position: 'right', // Adjust the position
                  labels: {
                      fontColor: '#333', // Customize legend label color
                      fontSize: 12, // Customize legend label size
                      fontStyle: 'bold' // Make legend labels bold
                  }
              }
            }
        });
    </script>
</div>

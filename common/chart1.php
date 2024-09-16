<?php
// Get the current month in the format YYYY-MM
$current_month = date("Y-m");

// Prepare the SQL query to select invoices and their corresponding sell amounts for the current month
$sql = "SELECT invoices.date, invoices_details.sell 
        FROM invoices 
        INNER JOIN invoices_details ON invoices.id = invoices_details.invoices_id 
        WHERE DATE_FORMAT(invoices.date, '%Y-%m') = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $param_current);

// Set the parameter to the current month
$param_current = $current_month;

// Execute the statement
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$dataset = [];
$labelset = [];
$daily_sales = [];

// Calculate daily sales
while ($row = mysqli_fetch_array($result)) {
    $date = date("Y-m-d", strtotime($row['date']));
    if (!isset($daily_sales[$date])) {
        $daily_sales[$date] = 0;
    }
    $daily_sales[$date] += $row['sell'];
}

// Sort the sales data by date
ksort($daily_sales);

// Prepare the data for Chart.js
foreach ($daily_sales as $date => $sell_amount) {
    $labelset[] = $date;
    $dataset[] = $sell_amount;
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
                        labelString: 'Date'  // Horizontal axis label
                    }
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Sell'  // Vertical axis label
                    },
                    ticks: {
                        beginAtZero: false
              }
    }]
},

                
                legend: {
                    display: false
                }
            }
        });
    </script>
</div>

<?php 

// Include the file containing database connection code segement
require_once '../../common/config.php';

// Include the file containing login function code segement
require_once '../../common/functions.php';
    
// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Total Sales Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="background-image: url(../../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">

 <!-- Navbar -->
 <?php 
        //require_once '../../common/navbar.php'; 
        include "../../common/header.php"; 
    ?>

<canvas id="salesChart"></canvas>

<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line', // You can use 'bar', 'pie', etc.
        data: {
            labels: labels, // Days of the week
            datasets: [{
                label: 'Total Sales (after returns)',
                data: salesData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php
// Assuming $link is your database connection
$week_start = date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime('sunday this week'));

$sales_query = "SELECT DATE(date) as sale_date, SUM(total_amount) as total_sales 
                FROM invoices 
                WHERE status = 1 AND dlt = 0 AND DATE(date) BETWEEN '$week_start' AND '$week_end'
                GROUP BY DATE(date)";

$sales_result = mysqli_query($link, $sales_query);

$returns_query = "SELECT DATE(date) as return_date, SUM(total_amount) as total_returns 
                  FROM purchase_return 
                  WHERE status = 1 AND dlt = 0 AND DATE(date) BETWEEN '$week_start' AND '$week_end'
                  GROUP BY DATE(date)";

$returns_result = mysqli_query($link, $returns_query);

$sales_data = [];
while ($row = mysqli_fetch_assoc($sales_result)) {
    $sales_data[$row['sale_date']] = $row['total_sales'];
}

$returns_data = [];
while ($row = mysqli_fetch_assoc($returns_result)) {
    $returns_data[$row['return_date']] = $row['total_returns'];
}

// Prepare data for each day of the week
$total_sales_by_day = [];
for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime("$week_start +$i day"));
    $total_sales = isset($sales_data[$date]) ? $sales_data[$date] : 0;
    $total_returns = isset($returns_data[$date]) ? $returns_data[$date] : 0;
    $total_sales_by_day[$date] = $total_sales - $total_returns;
}
?>

<script>
// Pass PHP data to JavaScript
var salesData = <?php echo json_encode(array_values($total_sales_by_day)); ?>;
var labels = <?php echo json_encode(array_keys($total_sales_by_day)); ?>;
</script>

?>

</body>
</html>

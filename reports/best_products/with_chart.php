<?php
// Include the file containing database connection code segment
require_once '../../common/config.php';

// Include the file containing login function code segment
require_once '../../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../../login.php');
    exit;
}

// To validate date selectors not to select dates later than today's date
$maxdate = date("Y-m-d", strtotime('today'));

// Initialize variables
$product_quantities = [];

if (isset($_POST['se'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];

    // Prepare the SQL query to fetch invoices within the date range
    $sql = "SELECT id FROM invoices WHERE date BETWEEN ? AND ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $from, $to);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            
            // Process each invoice
            while ($row = mysqli_fetch_array($result)) {
                $invoice_id = $row['id'];
                $count = "SELECT product, SUM(quantity) AS total_quantity FROM invoices_details WHERE invoices_id = $invoice_id GROUP BY product";

                foreach ($link->query($count) as $row1) {
                    $product_id = $row1['product'];
                    $quantity = $row1['total_quantity'];

                    if (!isset($product_quantities[$product_id])) {
                        $product_quantities[$product_id] = 0;
                    }
                    $product_quantities[$product_id] += $quantity;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Best Selling Products</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Load Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Product', 'Quantity Sold'],
                <?php
                if (!empty($product_quantities)) {
                    foreach ($product_quantities as $product_id => $quantity) {
                        $product_name_query = "SELECT name FROM products WHERE id = $product_id";
                        $product_name_result = $link->query($product_name_query);
                        $product_name_row = $product_name_result->fetch_assoc();
                        echo "['" . $product_name_row['name'] . "', " . $quantity . "],";
                    }
                }
                ?>
            ]);

            var options = {
                title: 'Best Selling Products',
                pieHole: 0.4
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>

    <?php include "../../common/header.php"; ?>
</head>

<body style="background-image: url(../../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
    <!-- Navbar -->
    <?php require_once '../../common/navbar.php'; ?>

    <br/>
    <br/>
    <br/>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-sm-10 mx-auto">
                <div class="page-header clearfix">
                    <h2 class="pull-left">Best Selling Products</h2>
                </div>
                <br/>
                <div class="row">
                    <form class="form-inline mx-auto" action="#" method="post">
                        <label for="from">From :</label>&nbsp;&nbsp;&nbsp;
                        <input type="date" name="from" class="form-control" max="<?php echo $maxdate; ?>" value="<?php echo $from; ?>"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="to">To :</label>&nbsp;&nbsp;&nbsp;
                        <input type="date" name="to" class="form-control" max="<?php echo $maxdate; ?>" value="<?php echo $to; ?>"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="submit" name="se" value="search" class="btn btn-success"/>
                    </form>
                </div>
                <br/>


                <!-- Pie Chart Container -->
                 <div class="col-lg-3">
                <div id="piechart_3d" style="width: 1000px; height: 400px;"></div>
                </div>
                <div class="container-fluid">
                    <br/>
                    <?php
                    if (!empty($product_quantities)) {
                        echo '<a href="print_new.php?from=' . htmlspecialchars($from) . '&to=' . htmlspecialchars($to) . '" class="btn btn-primary pull-left" target="_blank" style="margin-bottom: 10px;"><i class="fa fa-print"></i>&nbsp; Print Result</a>';

                        echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Product ID</th>";
                        echo "<th>Product Name</th>";
                        echo "<th>Sold Quantity</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        foreach ($product_quantities as $product_id => $quantity) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($product_id) . "</td>";

                            $product_name_query = "SELECT name FROM products WHERE id = $product_id";
                            $product_name_result = $link->query($product_name_query);
                            $product_name_row = $product_name_result->fetch_assoc();
                            echo "<td>" . htmlspecialchars($product_name_row['name']) . "</td>";

                            echo "<td>" . htmlspecialchars($quantity) . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table></div>";
                    }
                    ?>
                    <br/>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "order": [[0, "desc"]]
        });
    });
    </script>
</body>
</html>

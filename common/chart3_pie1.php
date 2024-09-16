<?php
$product_quantities = [];

    $from = date("Y-m-d", strtotime("-30 days"));
    $to = date("Y-m-d");

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

?>

                <!-- Pie Chart Container -->

                <div id="piechart_3d" style="width: 800px; height: 500px;"></div>

                

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
            pieHole: 0.4,
            titleTextStyle: {
            fontSize: 18,       // Adjust the font size
            bold: true,         // Make the title bold
            alignment: 'center' // Center alignment (supported in later versions)
    }
};


            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>

    
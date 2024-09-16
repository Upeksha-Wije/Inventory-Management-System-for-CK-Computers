<?php
require_once '../../common/config.php';
require_once '../../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../../login.php');
    exit;
}

$maxdate = date("Y-m-d", strtotime('today'));
$product_quantities = [];

if (isset($_POST['se'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];

    $sql = "SELECT id FROM invoices WHERE date BETWEEN ? AND ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $from, $to);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
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

// Handle PDF generation
if (isset($_POST['chartImage']) && !empty($_POST['chartImage'])) {
    $chartImage = $_POST['chartImage'];
    $chartImage = str_replace('data:image/png;base64,', '', $chartImage);
    $chartImage = str_replace(' ', '+', $chartImage);
    $chartData = base64_decode($chartImage);

    $file = 'chart.png';
    file_put_contents($file, $chartData);

    require('../../common/fpdf/fpdf.php');

    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 14);
            $this->cell(0, 15, 'CK Computers', 0, 0, 'C');
            $this->Ln(7);
            $this->cell(0, 15, 'Sales & Services', 0, 0, 'C');
            $this->Ln(7);
            $this->cell(0, 15, 'All Types Of Computer, Laptop & Accessorie', 0, 0, 'C');
            $this->Ln(7);
            $this->cell(0, 15, 'Oruthota Road, Gampaha', 0, 0, 'C');
            $this->Ln(7);
            $this->cell(0, 15, '0767729250 / 0778565800', 0, 1, 'C');
            $this->SetFont('Arial', 'BU', 16);
            $this->cell(0, 15, 'Best Selling Products Chart', 0, 1, 'L');
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    $pdf = new PDF('L', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->Image($file, 10, 50, 250, 0, 'PNG');
    $pdf->Output();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Best Selling Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

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

            // Convert chart to image and set hidden input value
            google.visualization.events.addListener(chart, 'ready', function () {
                var chartImg = chart.getImageURI();
                document.getElementById('chartImage').value = chartImg; // Set the hidden input value
            });
        }

        function generatePDF() {
            document.getElementById('pdfForm').submit(); // Submit the form to generate the PDF
        }
    </script>
</head>

<body style="background-image: url(../../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
    <?php require_once '../../common/navbar.php'; ?>

    <br/><br/><br/>
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
                        <input type="hidden" id="chartImage" name="chartImage" />
                        <input type="submit" name="se" value="search" class="btn btn-success"/>
                    </form>
                </div>
                <br/>

                <div class="col-lg-5">
                    <div class="col-lg-2">
                        <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
                    </div>
                </div>

                <br/>
                <!-- Print Button -->
                <form id="pdfForm" action="" method="post">
                    <input type="hidden" id="chartImage" name="chartImage" />
                    <button class="btn btn-primary" type="button" onclick="generatePDF()">Print PDF</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

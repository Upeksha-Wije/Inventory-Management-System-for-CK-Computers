<?php
// Include the file containing database connection code segment
require_once '../common/config.php';

// Include the file containing login function code segment
require_once '../common/functions.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header('location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports Management</title>
    <link rel="stylesheet" type="text/css" href="../common/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="/bit/images/Logo1.png">
    <style>
        .report-tile {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        .report-tile:hover {
            background-color: #e9ecef;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .report-tile a {
            color: #007bff;
            text-decoration: none;
        }
        .report-tile a:hover {
            text-decoration: underline;
        }
        .container {
            margin-top: 30px;
        }
        .page-header h2 {
            font-size: 1.75rem;
            font-weight: bold;
        }
    </style>
</head>
<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">

    <!-- Navbar -->
    <?php 
        require_once '../common/navbar.php'; 
        include "../common/header.php"; 
    ?>

<div class="container col-lg-8 mt-2">
    <div class="row mb-3">
        <div class="mx-auto">
            <div class="page-header">
                <h2>Reports Management</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/best_products/without_table.php">1. Products sold during a time range - pie chart</a>

            </div>
        </div>
        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/best_products/best_product.php">2. Product sold during a time range - Table</a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/sales_chart/sales_by_date_range.php">3. Total Sales By Date Range</a>

            </div>
        </div>
        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/by_invoice/invoices_by_date_range.php">4. Invoices by Date Range report</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/purchase_all.php">5. Purchase Orders Report</a>
            </div>
        </div>

        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/grn_date_range.php">6. GRNs by Date Range Report</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/staff_print_all.php">7. Print All staff Details</a>
            </div>
        </div>

        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/best_products/with_chart.php">8. Best Products Chart with Table </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-md-6 col-lg-5 mb-4">
            <div class="report-tile">
            <a href="/bit/reports/by_invoice/by_selected_user.php">9. Invoices list of selected user</a>
            </div>
        </div>
    </div>
            
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

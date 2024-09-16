<?php
// Include the file containing database connection code segment
require_once '../../common/config.php';

// Include the file containing login function code segment
require_once '../../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../../login.php');
}

// To validate date selectors not to select dates later than today's date
$maxdate = date("Y-m-d", strtotime('today'));

// Initialize the number of Invoice value
$number = 0;

// When the search button is clicked
if (isset($_POST['se'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];

    ///// WHEN STATUS NOT SELECTED
    /* Both the From and To date selected */
    if ($_POST['from'] != "" && $_POST['to'] != "") {

        // Prepare a select statement with DISTINCT
        $sql = "SELECT DISTINCT * FROM invoices WHERE date BETWEEN ? AND ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_from, $param_to);

            // Set parameters
            $param_from = $from;
            $date = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to)));
            $param_to = $date;
        }

    /* Only the From date selected */
    } elseif ($_POST['from'] != "" && $_POST['to'] == "") {

        // Prepare a select statement with DISTINCT
        $sql = "SELECT DISTINCT * FROM invoices WHERE date BETWEEN ? AND ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_from, $param_to);

            // Set parameters
            $param_from = $from;
            $date = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($from)));
            $param_to = $date;
        }

    /* Only the To date selected */
    } elseif ($_POST['from'] == "" && $_POST['to'] != "") {

        // Prepare a select statement with DISTINCT
        $sql = "SELECT DISTINCT * FROM invoices WHERE date BETWEEN 0 AND ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_to);

            // Set parameters
            $date = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to)));
            $param_to = $date;
        }

    } else {
        // Other conditions...
    }
}

if (isset($_POST['se']) && $stmt != "") {
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $number = mysqli_num_rows($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Best Selling Products</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
                    <h2 class="pull-left"> Product sold during a time range - Table</h2>
                </div>
                <br/>
                <div class="row">
                    <form class="form-inline mx-auto" action="#" method="post">
                        <label for="from">From :</label>&nbsp;&nbsp;&nbsp;
                        <input type="date" name="from" class="form-control" onchange="getDateTo(this.value)" max="<?php echo $maxdate; ?>" value="<?php echo $from; ?>"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="to">To :</label>&nbsp;&nbsp;&nbsp;
                        <span id="datet">
                            <input type="date" name="to" class="form-control" min="<?php echo $from; ?>" max="<?php echo $maxdate; ?>" value="<?php echo $to; ?>"/>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span>
                        <input type="submit" name="se" value="search" class="btn btn-success"/>
                    </form>
                </div>
                <br/>

                <?php if (isset($_POST['se'])) { ?>
                <div class="row">
                    <div class="col">From : <?php echo $_POST['from']; ?></div> 
                    <div class="col">To: <?php echo $_POST['to'] ?></div>
                </div>
                <?php } ?>

                <div class="container-fluid">
                   
                    <br/>
                    <?php
                    if (isset($_POST['se']) && $stmt != "") {
                        if (mysqli_stmt_execute($stmt)) {
                            $result = mysqli_stmt_get_result($stmt);

                            if (mysqli_num_rows($result) > 0) {
                                echo '<a href="print_new.php?from='.$from.'&to='.$to.'" class="btn btn-primary pull-left" target="_blank" style="margin-bottom: 10px;"><i class="fa fa-print"></i>&nbsp; Print Result</a>';

                                echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>Product ID</th>";
                                echo "<th>Product Name</th>";
                                echo "<th>Sold Quantity</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $total = 0;
                                $product_quantities = [];

                                while ($row = mysqli_fetch_array($result)) {
                                    $invoice_id = $row['id'];
                                    $count = "SELECT product, SUM(quantity) AS total_quantity FROM invoices_details WHERE invoices_id = $invoice_id GROUP BY product"; // SQL with GROUP BY

                                    foreach ($link->query($count) as $row1) {
                                        $product_id = $row1['product'];
                                        $quantity = $row1['total_quantity'];

                                        if (!isset($product_quantities[$product_id])) {
                                            $product_quantities[$product_id] = 0;
                                        }
                                        $product_quantities[$product_id] += $quantity;
                                    }
                                }

                                foreach ($product_quantities as $product_id => $quantity) {
                                    echo "<tr>";
                                    echo "<td>$product_id</td>";

                                    $product_name_query = "SELECT name FROM products WHERE id = $product_id";
                                    $product_name_result = $link->query($product_name_query);
                                    $product_name_row = $product_name_result->fetch_assoc();
                                    echo "<td>".$product_name_row['name']."</td>";

                                    echo "<td>";
                                    $product_quantity = 0;
                                    $count="SELECT * FROM invoices_details WHERE product=$product_id"; // SQL to get records 
                                    foreach ($link->query($count) as $row1) {
                                        $product_quantity += $row1['quantity'];  
                                    }
                                    echo $product_quantity; 
                                    echo "</td>";
                                    echo "</tr>";
                                }

                                

                                // Free result set
                                mysqli_free_result($result);

                            } else {
                                echo "<br/><div class='alert alert-danger'><em> No Invoices were found.</em></div>";
                            }

                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
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


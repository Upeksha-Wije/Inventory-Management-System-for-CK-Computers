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

// To validate date selectors not to select dates later than today date
$maxdate = date("Y-m-d", strtotime('today'));

// Initialize the number of GRNs value
$number = 0;

// When the search button is clicked
if (isset($_POST['se'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];

    // Prepare SQL based on date inputs
    if ($from != "" && $to != "") {
        // Both "From" and "To" dates are selected
        $sql = "SELECT * FROM invoices WHERE date BETWEEN ? AND ? AND status=1";
    } elseif ($from != "" && $to == "") {
        // Only "From" date is selected
        $sql = "SELECT * FROM invoices WHERE date >= ? AND status=1";
    } elseif ($from == "" && $to != "") {
        // Only "To" date is selected
        $sql = "SELECT * FROM invoices WHERE date <= ? AND status=1";
    } else {
        // Neither date is selected, display all invoices
        $sql = "SELECT * FROM invoices WHERE status=1";
    }

    // Prepare and execute the statement
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameters based on the SQL
        if ($from != "" && $to != "") {
            mysqli_stmt_bind_param($stmt, "ss", $param_from, $param_to);
            $param_from = $from;
            $param_to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to)));
        } elseif ($from != "" && $to == "") {
            mysqli_stmt_bind_param($stmt, "s", $param_from);
            $param_from = $from;
        } elseif ($from == "" && $to != "") {
            mysqli_stmt_bind_param($stmt, "s", $param_to);
            $param_to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to)));
        }

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $number = mysqli_num_rows($result);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoices by Date Range Report</title>
    <link rel="stylesheet" type="text/css" href="https:/cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php include "../../common/header.php"; ?>
</head>

<body style="background-image: url(../../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
    <!-- Navbar -->
    <?php require_once '../../common/navbar.php'; ?>
    
    <br/><br/><br/>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-sm-10 mx-auto">
                <div class="page-header clearfix">
                    <h2 class="pull-left">Invoices by Date Range Report</h2>
                </div>
                <br/>
                <div class="row">
                    <form class="form-inline mx-auto" action="#" method="post">
                        <label for="from">From :</label>&nbsp;&nbsp;&nbsp;
                        <input type="date" id="from" name="from" class="form-control" onchange="updateToDate(this.value)" max="<?php echo $maxdate; ?>" value="<?php echo $from; ?>"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        <label for="to">To :</label>&nbsp;&nbsp;&nbsp;
                        <input type="date" id="to" name="to" class="form-control" max="<?php echo $maxdate; ?>" value="<?php echo $to; ?>"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="submit" name="se" value="Search" class="btn btn-success"/>
                    </form>
                </div>

                <br/>

                <!--to hide the table-->
                <?php if(isset($_POST['se'])){ ?>
                <div class="row">
                    <div class="col">From : <?php echo $_POST['from']; ?></div>
                    <div class="col">To: <?php echo $_POST['to'] ?></div>
                    <div class="col">Number of Invoices : <?php echo $number ?></div>
                </div>
                <?php } ?>

                <div class="container-fluid">
                <br/>

                <?php
                if(isset($_POST['se'])){
                    if(mysqli_stmt_execute($stmt)){
                        $result = mysqli_stmt_get_result($stmt);

                        if(mysqli_num_rows($result) > 0){
                            echo '<a href="invoice_print.php?from='.$from.'&to='.$to.'" class="btn btn-primary pull-left" target="_blank" style="margin-bottom: 10px;"><i class="fa fa-print"></i>&nbsp; Print Result</a>';

                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>Invoice ID</th>";
                            echo "<th>Date</th>";
                            echo "<th>Customer ID</th>";
                            echo "<th>Customer Name</th>";
                            echo "<th>Status</th>";      
                            echo "<th class='text-right'>Action</th>";             
                            echo "</tr>";  
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = mysqli_fetch_array($result)){
                                echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['date'] . "</td>";
                                    echo "<td>" . $row['customer'] . "</td>";
    
                                    echo "<td>";   
                                    $customer=$row['customer']; 
                                    $count = "SELECT * FROM customers WHERE id=$customer";
                                    foreach ($link->query($count) as $row1) {
                                        echo $row1['firstname'] . " " . $row1['lastname'];  
                                    }       
                                    echo "</td>";

                                    $status = $row['status'];
                                    if($status == 1){
                                        echo "<td><div class='badge badge-success'>Active</div></td>";
                                    } else {
                                        echo "<td><div class='badge badge-danger'>Inactive</div></td>";
                                    }

                                    echo "<td class='text-right'>";
                                        echo "<a href='invoices_date_range_print.php?id=". $row['id'] ."' title='Print Record' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                                        echo "<a href='invoices_date_range_view.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip' target='_blank'><i class='fa fa-eye'></i>&nbsp;</a>";
                                    echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";

                            // Free result set
                            mysqli_free_result($result);
                        } else {
                            echo "<br/><div class='alert alert-danger'><em>No Invoices were found.</em></div>";
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                } else {
                    echo '<br/><div class="alert alert-danger text-center">Please Select Dates</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>

<script>
    // Update the "To" date's minimum value based on the "From" date selection
    function updateToDate(fromDate){
        const toDate = document.getElementById('to');
        toDate.min = fromDate;
    }
</script>

</html>

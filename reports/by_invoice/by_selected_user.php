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

// To validate date selectors not to select dates later than today date
$maxdate = date("Y-m-d", strtotime('today'));

// Initialize variables
$number = 0;
$status = 1;
$customer_id = "";

// When the search button is clicked
if (isset($_POST['se'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $status = $_POST['status'];
    $customer_id = $_POST['customer'];

    // Prepare SQL statement based on selected filters
    $conditions = [];
    $params = [];
    $types = '';

    if ($from) {
        $conditions[] = 'date >= ?';
        $params[] = $from;
        $types .= 's';
    }
    if ($to) {
        $conditions[] = 'date <= ?';
        $params[] = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to)));
        $types .= 's';
    }
    if ($status !== "") {
        $conditions[] = 'status = ?';
        $params[] = $status;
        $types .= 'i';
    }
    if ($customer_id) {
        $conditions[] = 'customer = ?';
        $params[] = $customer_id;
        $types .= 'i';
    }

    $sql = "SELECT * FROM invoices";
    if (count($conditions) > 0) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
}

if (isset($_POST['se']) && $stmt != "") {
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $number = mysqli_num_rows($result);
    }
}

// Retrieve customers for dropdown
$customer_query = "SELECT id, firstname, lastname FROM customers";
$customers = mysqli_query($link, $customer_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoices by Date Range Report</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
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
                    <h2 class="pull-left">Invoices Report</h2>
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

                        <label for="status">Status :</label>&nbsp;&nbsp;&nbsp; 
                        <select name="status" class="form-control" id="status">
                            <option value="<?php echo $status; ?>"><?php if($status == "") { echo "All"; } elseif ($status == 0) { echo "Inactive"; } elseif ($status == 1) { echo "Active"; } ?></option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>                                
                            <option value="">All</option> 
                        </select>&nbsp;&nbsp;&nbsp;  

                        <label for="customer">Customer :</label>&nbsp;&nbsp;&nbsp; 
                        <select name="customer" class="form-control" id="customer">
                            <option value="">All</option>
                            <?php while ($row = mysqli_fetch_assoc($customers)) { ?>
                                <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $customer_id) echo 'selected'; ?>>
                                    <?php echo $row['firstname'] . ' ' . $row['lastname']; ?>
                                </option>
                            <?php } ?>
                        </select>&nbsp;&nbsp;&nbsp;

                        <input type="submit" name="se" value="Search" class="btn btn-success"/>
                    </form>
                </div>
                
                <br/>

                <!-- Display results -->
                <?php if (isset($_POST['se'])) { ?>
                    <div class="row">
                        <div class="col">From : <?php echo $_POST['from']; ?></div> 
                        <div class="col">To: <?php echo $_POST['to'] ?></div>
                        <div class="col">Number of Invoices : <?php echo $number ?></div>
                    </div>
                <?php } ?>                 
                </div>

                <div class="container-fluid">
                <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        "order": [[ 0, "desc" ]]
                    });
                });
                </script>   

                <br/>

                <?php
                if (isset($_POST['se']) && $stmt != "") {
                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            echo '<a href="invoices_print_all.php?from=' . $from . '&to=' . $to . '&status=' . $status . '&customer=' . $customer_id . '" class="btn btn-primary pull-left" target="_blank" style="margin-bottom: 10px;"><i class="fa fa-print"></i>&nbsp; Print Result</a>';

                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Date</th>";
                            echo "<th>Customer ID</th>";
                            echo "<th>Customer Name</th>";
                            echo "<th>Status</th>";      
                            echo "<th>Action</th>";             
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['customer'] . "</td>";

                                echo "<td>";   
                                $customer = $row['customer']; 
                                $count = "SELECT * FROM customers WHERE id=$customer"; // SQL to get records 
                                foreach ($link->query($count) as $row1) {
                                    echo $row1['firstname'] . " " . $row1['lastname'];  
                                }       
                                echo "</td>";        
                                
                                $status = $row['status'];

                                if ($status == 1) {
                                    echo "<td>" . "<div class='badge badge-success'>Active</div>" .

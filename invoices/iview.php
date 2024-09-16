<?php

// Include the file containing database connection code segment
require_once '../common/config.php';

// Include the file containing login function code segment
require_once '../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
    exit(); // Make sure to exit after a redirect
}

// Define variables and initialize with empty values
$customer = $product = $subtotal = $total = "";
$total = 0; // Initialize total to zero to start accumulating subtotals

// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

    // Prepare a select statement
    $sql = "SELECT * FROM invoices WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = $_GET["id"];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $date = $row["date"];
                $supplier = $row["customer"];
                $status = $row["status"];

            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <?php include "../common/header.php"; ?>
</head>

<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
        
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

    <br/>
    <br/>
    <br/>


    <div class="container mx-auto col-lg-7">
                
        <div class="page-header">
            <h1>Create Invoice</h1>
        </div>
      
        <div class="row">
            <div class="col-lg-12"></div>
            <div class="col-sm-1"><label class="font-weight-bold">Invoice: ID</label></div>
            <div class="col-sm-1"><p class="form-control-static"><?php echo $_GET["id"]; ?></p></div>
            <div class="col-sm-1"><label class="font-weight-bold">Date:</label></div>
            <div class="col-sm-3"><p class="form-control-static"><?php echo htmlspecialchars($row["date"]); ?></p></div>
            <div class="col-sm-1">
                <label class="font-weight-bold">Customer ID:</label>
            </div>
            <div class="col-sm-1">
                <p class="form-control-static col-sm-1"><?php echo htmlspecialchars($row["customer"]); ?></p>
            </div>
            <div class="col-sm-2">
                <label class="font-weight-bold">Customer Name:</label>
            </div>
            
            <?php
                echo "<div class='col-sm-2'>";   
                $z = $row['customer']; 
                $count = "SELECT * FROM customers WHERE id = $z"; // SQL to get records 
                foreach ($link->query($count) as $row1) {
                    echo htmlspecialchars($row1['firstname']) . " " . htmlspecialchars($row1['lastname']);  
                }       
                echo "</div>";  
            ?>                      
        </div>

        <div class="form-group">
            <a href="add.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-primary pull-left"><i class="fa fa-user-plus"></i>&nbsp; Add New Item</a>
        </div>

        <br/>
        <?php
            // Attempt select query execution
            $sql = "SELECT * FROM invoices_details WHERE invoices_id = $param_id AND status = 1 ORDER BY `id` DESC";
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<div class='bg-white p-1'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th class='text-right'>Item ID</th>";
                                echo "<th class='text-right'>Product ID</th>";
                                echo "<th class='text-center'>Product Name</th>";
                                echo "<th class='text-right'>Available Stock</th>";
                                echo "<th class='text-right'>Least Selling Price</th>";
                                echo "<th class='text-right'>Unit Selling Price</th>";
                                echo "<th class='text-right'>Quantity</th>"; 
                                echo "<th class='text-right'>Sub Total</th>"; 
                                echo "<th class='text-center'>Action</th>";             
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                                echo "<td class='text-right'>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td class='text-right'>" . htmlspecialchars($row['product']) . "</td>";
                                echo "<td class='text-center'>";   

                                $product = $row['product']; 
                                $count = "SELECT * FROM products WHERE id = $product"; // SQL to get records 
                                foreach ($link->query($count) as $row1) {
                                    echo htmlspecialchars($row1['name']);
                                    $price = $row1['price'];

                                    $sell = $row['sell'];
                                    $quantity = $row['quantity'];
                                    $subtotal = $sell * $quantity;                        
                                    $total += $subtotal; // Add subtotal to total
                                }   

                                echo "</td>";

                                $count = "SELECT * FROM stock WHERE pid = $product"; // SQL to get records 
                                foreach ($link->query($count) as $row1) {
                                    $stock = $row1['quantity'];  
                                }       
                                            
                                echo "<td class='text-right'>" . htmlspecialchars($stock) . "</td>";

                                echo "<td class='text-right'>" . htmlspecialchars($price) . "</td>";

                                echo "<td class='text-right'>" . htmlspecialchars($row['sell']) . "</td>";
                                                                    
                                echo "<td class='text-right'>" . htmlspecialchars($row['quantity']) . "</td>";

                                echo "<td class='text-right'>" . htmlspecialchars($subtotal) . "</td>";
                                echo "<td class='text-center'>";
                                    echo "<a href='iupdate.php?id=". htmlspecialchars($row['id']) ."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                    echo "<a href='idelete.php?id=". htmlspecialchars($row['id']) ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-remove'></i></a>";
                                echo "</td>"; 
                            echo "</tr>";
                        }           
                            echo "</tr>";
                                echo "<td colspan='7' class='font-weight-bold text-right'>TOTAL</td>";
                                echo "<td class='font-weight-bold text-right'>=&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$total</td>";
                            echo "<tr>";
                        echo "</tbody>";                            
                    echo "</table>";

                    // Free result set
                    mysqli_free_result($result);
                } else {
                    echo "<p class='lead'><em>No items are added yet.</em></p>";
                }
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($link);
            }

            // Close connection
            mysqli_close($link);
        ?>
                    
        <div>
            <a href="index.php" class="btn btn-success">Finish</a>
            <a href="delete.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-danger">Cancel</a>
        </div>
                
    </div>

</body>
</html>

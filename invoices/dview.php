<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

//Define variables and initialize with empty values
$customer = $product = $subtotal = $total = 0;


// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM invoices WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
     
                $date = $row["date"];
                $supplier = $row["customer"];
                $status = $row["status"];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    //mysqli_stmt_close($stmt);
    
    // Close connection
    //mysqli_close($link);

    
}// ID not emty 

else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Deleted Invoice</title>
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

    
        <div class="container col-sm-8">

                <div class="col-lg-10 mx-auto">
                    <div class="page-header mb-3">
                        <h1>View Deleted Invoice</h1>
                    </div>

            <div class="row">
                        <div class="col-sm-1"><label class="font-weight-bold">Invoice ID:</label></div>
                        <div class="col-sm-1"><p class="form-control-static"><?php echo $_GET["id"]; ?></p></div>
                        <div class="col-sm-1"><label class="font-weight-bold">Date:</label></div>
                        <div class="col-sm-3"><p class="form-control-static"><?php echo $row["date"]; ?></p></div>
                        <div class="col-sm-1">
                            <label class="font-weight-bold">Customer ID:</label>
                        </div>
                        <div class="col-sm-1">
                            <p class="form-control-static col-sm-1"><?php echo $row["customer"]; ?></p>
                        </div>
                        <div class="col-sm-2">
                            <label class="font-weight-bold">Customer Name:</label>
                        </div>
                        
                        <?php
                            echo "<div class='col-sm-2'>";   
                            $customer=$row['customer']; 
                            $count="SELECT * FROM customers WHERE id=$customer"; // SQL to get records 
                            foreach ($link->query($count) as $row1) {
                            echo $row1['firstname']." ".$row1['lastname'];  
                               }       
                            echo "</div>";  
                         ?>                      
            </div>

                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM invoices_details WHERE invoices_id=$param_id && dlt=0 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th class='text-right'>Item ID</th>";
                                        //echo "<th>Purchases ID</th>";
                                        //echo "<th>Date</th>";
                                        //echo "<th>Supplier</th>";
                                        echo "<th class='text-right'>Product ID</th>";
                                        echo "<th class='text-center'>Product Name</th>";
                                        echo "<th class='text-right'>Unit Selling Price</th>";
                                        echo "<th class='text-right'>Quantity</th>";  
                                        echo "<th class='text-right'>Sub Total</th>"; 
                                        //echo "<th>status</th>";      
                                        //echo "<th>Action</th>";             
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td class='text-right'>" . $row['id'] . "</td>";
                                        //echo "<td>" . $row['invoices_id'] . "</td>";
                                        //echo "<td>" . $row['supplier'] . "</td>";
                                        echo "<td class='text-right'>" . $row['product'] . "</td>";

                                        $quantity = $row['quantity'];

                                        echo "<td class='text-center'>";   
                                        $product=$row['product']; 
                                        $count="SELECT * FROM products WHERE id=$product"; // SQL to select product
                                        foreach ($link->query($count) as $row1) {
                                            echo $row1['name']; 
                                            $price = $row1['price'];
                                            $sell = $row['sell'];
                                            $subtotal = $sell*$quantity;                        
                                            $total+=$subtotal;
                                        }       
                                        echo "</td>";   
                                                                                                          
                                        echo "<td class='text-right'>" . $sell . "</td>";

                                        echo "<td class='text-right'>" . $quantity . "</td>";

                                        echo "<td class='text-right'>" . $subtotal . "</td>";

                                
                                    echo "</tr>";
                                }

                                    echo "</tr>";
                                        echo "<td colspan='5' class='font-weight-bold text-right'>TOTAL</td>";
                                        echo "<td class='font-weight-bold text-right'>=&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$total</td>";
                                    echo "<tr>";

                            /*
                                    if($total>50000){
                                        $tax=($total*0.04);                
                                
                                    }else if($total>20000){ 
                                
                                        $tax=($total*0.02);

                                    } else{ 
                                        $tax=0;
                                    }         

                                    echo "</tr>";
                                        echo "<td colspan='5' class='font-weight-bold text-right'>TAX</td>";
                                        echo "<td class='font-weight-bold text-right'>=&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$tax</td>";
                                    echo "<tr>";
                                    $a=array($total,$tax);
                                    $final=array_sum($a);

                                    echo "</tr>";
                                        echo "<td colspan='5' class='font-weight-bold text-right'>Final Total</td>";
                                        echo "<td class='font-weight-bold text-right'>=&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$final</td>";
                                    echo "<tr>";                                    
                            */
                                echo "</tbody>";                            
                            echo "</table>";

                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No items are added yet.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);

                    ?>

                    <a href="dindex.php" class="btn btn-primary">Back</a>

                    <?php
                    if($status==1){
                        echo "<a href='print.php?id=$_GET[id]' title='Print Record' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                    }
                    ?>

                </div>               
        </div>
    
</body>
</html>
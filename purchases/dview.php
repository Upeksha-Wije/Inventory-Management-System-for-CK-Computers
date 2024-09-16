<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM purchases WHERE id = ?";
    
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
                $supplier = $row["supplier"];
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
    <title>View Deleted Purchase Order</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        
      
    <?php include "../common/header.php"; ?>     
</head>

<body>
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

    <br/>
    <br/>
    <br/>

        <div class="container col-lg-8">
           <div class="mx-auto">
                    <div class="page-header">
                        <h1>View Deleted Purchase Order</h1>
                        <br>
                    </div>

            <div class="row">
                        <div class="col-sm-1"><label class="font-weight-bold">Purchase: Order ID</label></div>
                        <div class="col-sm-1"><p class="form-control-static">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_GET["id"]; ?></p></div>
                        <div class="col-sm-1"><label class="font-weight-bold">Date:</label></div>
                        <div class="col-sm-3"><p class="form-control-static"><?php echo $row["date"]; ?></p></div>
                        <div class="col-sm-1">
                            <label class="font-weight-bold">Supplier:</label>
                        </div>
                        <div class="col-sm-1">
                            <p class="form-control-static col-sm-1"><?php echo $row["supplier"]; ?></p>
                        </div>
                        <div class="col-sm-1">
                            <label class="font-weight-bold">Name:</label>
                        </div>
                        
                        <?php
                            echo "<div class='col-sm-3'>";   
                            $z=$row['supplier']; 
                            $count="SELECT * FROM suppliers WHERE id=$z"; // SQL to get records 
                            foreach ($link->query($count) as $row1) {
                            echo $row1['firstname']." ".$row1['lastname'];  
                               }       
                            echo "</div>";  
                         ?>                      
            </div>

                <div class="row col-lg-12">
                <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM purchases_details WHERE purchases_id=$param_id && dlt=0 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>ID</th>";
                                        //echo "<th>Purchases ID</th>";
                                        //echo "<th>Date</th>";
                                        //echo "<th>Supplier</th>";
                                        echo "<th>Product ID</th>";
                                        echo "<th>Product Name</th>";
                                        //echo "<th>Unit Price</th>";
                                        echo "<th>Quantity</th>"; 
                                        //echo "<th>Total</th>"; 
                                        //echo "<th>status</th>";      
                                        //echo "<th>Action</th>";             
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        //echo "<td>" . $row['purchases_id'] . "</td>";
                                        //echo "<td>" . $row['supplier'] . "</td>";
                                        echo "<td>" . $row['product'] . "</td>";

                                        echo "<td>";   
                                        $z=$row['product']; 
                                        $count="SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                            echo $row1['name'];  
                                        }       
                                        echo "</td>";        
                                                                        
                                        echo "<td>" . $row['quantity'] . "</td>";
                                        //echo "<td>" . $row['stock'] . "</td>";
                                        //echo "<td>" . $row['total'] . "</td>";
                                        //echo "<td>" . $row['status'] . "</td>";
                                        /*echo "<td>";
                                            echo "<a href='print.php?id=". $row['id'] ."' title='Print Record' data-toggle='tooltip'><i class='fa fa-print'></i>&nbsp;</a>";
                                            echo "<a href='view.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-remove'></i></a>";
                                        echo "</td>"; */
                                    echo "</tr>";
                                }
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

                    <p><a href="dindex.php" class="btn btn-primary">Back</a>
                        <?php
                        if($status==1){
                            echo "<a href='print.php?id=$_GET[id]' title='Print Record' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                        }
                        ?>                    
                    </p>

                </div>  
                </div>                
        </div>
    
</body>
</html>
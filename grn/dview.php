<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

//check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

$total = $buy = "";

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM grn WHERE id = ?";
    
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
                
                $purchases = $row["purchases"];
                $date = $row["date"];

                $sql2 = "SELECT * FROM purchases WHERE id=$purchases";
                $result = mysqli_query($link, $sql2);
                $row0 = mysqli_fetch_array($result);
                $supplier = $row0['supplier'];

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
    <title>View GRN</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include "../common/header.php";?> 
          
</head>
<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

    <br/>
    <br/>
    <br/>

    <div class="container col-lg-7">
        <div class="mx-auto mb-3">
                    <div class="page-header">
                        <h1>View GRN</h1>
                    </div>

            <div class="row">
                        <div class="col-sm-3"><label class="font-weight-bold">GRN ID:</label></div>
                        <div class="col-sm-1"><div class="form-control-static"><?php echo $_GET["id"]; ?></div></div>
                        <div class="col-sm-2"><label class="font-weight-bold">Date:</label></div>
                        <div class="col-sm-3"><div class="form-control-static"><?php echo $row["date"]; ?></div></div>               
            </div>

            <div class="row">
                        <div class="col-sm-3"><label class="font-weight-bold">Purchase Order ID:</label></div>
                        <div class="col-sm-1"><div class="form-control-static"><?php echo $purchases; ?></div></div>            
                        <div class="col-sm-2">
                            <label class="font-weight-bold">Supplier ID:</label>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-control-static col-sm-1"><?php echo $supplier; ?></div>
                        </div>
                        <div class="col-sm-2">
                            <label class="font-weight-bold">Supplier Name:</label>
                        </div>
                        <?php
                            echo "<div class='col-sm-3'>";   
                            $count="SELECT * FROM suppliers WHERE id=$supplier"; // SQL to get records 
                            foreach ($link->query($count) as $row1) {
                            echo $row1['firstname']." ".$row1['lastname'];  
                               }       
                            echo "</div>";  
                         ?>                
            </div>

                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM grn_details WHERE grn_id=$param_id && dlt=0 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th class='text-right'>Item ID</th>";
                                        echo "<th class='text-right'>Product ID</th>";
                                        echo "<th class='text-center'>Product Name</th>";
                                        echo "<th class='text-right'>Unit Price</th>"; 
                                        echo "<th class='text-right'>Quantity</th>";
                                        echo "<th class='text-right'>Sub Total</th>";      
                                        //echo "<th>Action</th>";             
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td class='text-right'>" . $row['id'] . "</td>";
                                        echo "<td class='text-right'>" . $row['product'] . "</td>";
                                        echo "<td class='text-center'>";   
                                            $z=$row['product']; 
                                            $count="SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                            foreach ($link->query($count) as $row1) {
                                                echo $row1['name'];  
                                            }       
                                        echo "</td>";
                                        echo "<td class='text-right'>" . $b=$row['buy'] . "</td>";
                                        echo "<td class='text-right'>" . $q=$row['quantity'] . "</td>";

                                        (int)$sub=(int)$q*(int)$b;
                                        echo "<td class='text-right'>" . $sub . "</td>";

                                        (int)$total+(int)$sub;

                                        //echo "<td>";
                                            //echo "<a href='print.php?id=". $row['id'] ."' title='Print Record' data-toggle='tooltip'><i class='fa fa-print'></i>&nbsp;</a>";
                                            //echo "<a href='view.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";
                                            /*
                                            echo "<a href='iupdate.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                            echo "<a href='idelete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
                                            
                                        echo "</td>"; */
                                    echo "</tr>";                                                                   
                                }

                                echo "<tr>";
                                    echo "<td colspan='5' class='font-weight-bold text-right'>TOTAL</td>";
                                    echo "<td class='font-weight-bold text-right'>=&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$total</td>";
                                echo "</tr>";                                    
                                echo "</tbody>";                            
                            echo "</table>";

                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<div class='lead'><em>No items are added yet.</em></div>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);

                    ?>

                    <div>
                        <a href="dindex.php" class="btn btn-primary">Back</a>
                        <?php
                        if($status==1){
                            echo "<a href='print.php?id=$_GET[id]' title='Print Record' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                        }
                        ?>
                    </div>
                </div> 
        </div>
    </div>
    
</body>
</html>
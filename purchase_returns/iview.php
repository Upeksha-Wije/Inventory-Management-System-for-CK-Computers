<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

// Check if the user is logged in
if(!isset($_SESSION['user'])) {
    header('location: ../login.php');
}


$tquantity = $total = 0;


// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM purchase_return WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = $_GET["id"];
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $purchase_return = $_GET["id"];
     
                $date = $row["date"];

                $grn = $row["grn"];

                $sql2 = "SELECT * FROM grn WHERE id=$grn";
                $result = mysqli_query($link, $sql2);
                $row0 = mysqli_fetch_array($result);

                $purchases = $row0['purchases'];

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

    
}// ID not empty 


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
    <title>Create purchase_return</title>
    
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

    <div class="row">

        <div class="col-lg-2"></div>
        <div class="container mx-auto">
        
                <div class="page-header">
                    <h1>Create Purchase Return</h1>
                </div>

                    <div class="row">
                                <div class="col-sm-3"><label class="font-weight-bold">Purchase Return ID:</label></div>
                                <div class="col-sm-1"><div class="form-control-static"><?php echo $_GET["id"]; ?></div></div>
                                <div class="col-sm-1"><label class="font-weight-bold">Date:</label></div>
                                <div class="col-sm-3"><div class="form-control-static"><?php echo $row["date"]; ?></div></div>     
                                <div class="col-sm-2"><label class="font-weight-bold">GRN ID:</label></div>
                                <div class="col-sm-1"><div class="form-control-static"><?php echo $grn; ?></div></div>            
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
                                    foreach (mysqli_query($link,$count) as $row1) {
                                    echo $row1['firstname']." ".$row1['lastname'];  
                                    }       
                                    echo "</div>";  
                                ?>                
                    </div>


                            <br/>
                                    
                    
                    
                        <h5>GRN & Purchase Return</h5>

                            <?php
                            // Attempt select query execution
                            $sql = "SELECT DISTINCT product FROM grn_details WHERE grn_id=$grn && status=1 ORDER BY `id` DESC";
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) > 0){
                                    echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>Purchase Return Item ID</th>";
                                                echo "<th>Product ID</th>";
                                                echo "<th>Product Name</th>";
                                                echo "<th>Purchase Order Quantity</th>";
                                                echo "<th>GRN Quantity</th>"; 
                                                echo "<th>Total Purchase Return Quantity</th>"; 
                                                echo "<th>Purchase Return Quantity</th>"; 
                                                //echo "<th>status</th>";      
                                                echo "<th>Action</th>";             
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        $id="";
                                        while($row = mysqli_fetch_array($result)){//starts for each product
                                            $id="";
                                            echo "<tr>";
                                                    $product=$row['product']; 

                                                    $count="SELECT * FROM products WHERE id=$product"; // SQL to get records 
                                                    foreach ($link->query($count) as $row1) {
                                                        $pname = $row1['name'];  
                                                    }  

                                                    $product=$row['product']; 
                                                    $count1="SELECT id FROM purchase_return_details WHERE product=$product && purchase_return_id=$purchase_return && status=1"; // SQL to get records 
                                                    foreach ($link->query($count1) as $row01) {
                                                        $id = $row01['id'];  
                                                    }                                               

                                                echo "<td>" . $id . "</td>";
                                                echo "<td>" . $product . "</td>";

                                                echo "<td>" . $pname . "</td>";

                                                echo "<td>";   
                                                    // Purchase Order Quantity
                                                    $count2="SELECT SUM(quantity) AS Total FROM purchases_details WHERE product=$product && purchases_id=$purchases && status=1"; 
                                                    foreach ($link->query($count2) as $row2) {
                                                        echo $po = $row2["Total"];
                                                    }   
                                                echo "</td>"; 

                                                echo "<td>";   
                                                    // GRN Quantity
                                                    $count3="SELECT SUM(quantity) AS Total FROM grn_details WHERE product=$product && grn_id=$grn && status=1"; // SQL to select product
                                                    foreach ($link->query($count3) as $row3) {
                                                        echo $tgrn = $row3["Total"];
                                                    }   
                                                echo "</td>"; 

                                                echo "<td>";   
                                                    // Total Purchase Return Quantity
                                                    $count4="SELECT SUM(quantity) AS Total FROM purchase_return_details WHERE product=$product && purchase_return_id IN (SELECT id FROM purchase_return WHERE grn=$grn && status=1) && status=1"; // SQL to select product
                                                    foreach ($link->query($count4) as $row4) {
                                                        echo $tqty = $row4["Total"];
                                                    }   
                                                echo "</td>";   

                                                echo "<td>";   
                                                    // Purchase Return Quantity
                                                    $count5="SELECT SUM(quantity) AS Total FROM purchase_return_details WHERE product=$product && purchase_return_id=$purchase_return && status=1"; // SQL to select product
                                                    foreach ($link->query($count5) as $row5) {
                                                        echo $qty = $row5["Total"];
                                                    }   

                                                    if ($qty==""&&$tqty!==$tgrn){
                                                        echo "<a href='add.php?id=".$_GET["id"]."&pid=".$product."&po=".$po."&tgrn=".$tgrn."&tqty=".$tqty."' title='Add Record' data-toggle='tooltip' class='btn btn-primary btn-sm col-sm-12'>&nbsp; Add Item &nbsp;</a>";           

                                                    }elseif ($qty==""&&$tqty==$tgrn){
                                                        echo "Returned Already";
                                                    }
                                                echo "</td>"; 


                                                //echo "<td>" . $tquantity . "</td>";

                                                //echo "<td>" . $row['quantity'] . "</td>";
                                                //echo "<td>" . $row['status'] . "</td>";
                                                echo "<td>";
                                                if (!$qty==""){
                                                    echo "<a href='iupdate.php?id=". $id ."&po=".$po."&tgrn=".$tgrn."&tqty=".$tqty."&qty=".$qty."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                                    echo "<a href='idelete.php?id=". $id ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-remove'></i></a>";
                                                }

                                                    //echo "<a href='print.php?id=". $row['id'] ."' title='Print Record' data-toggle='tooltip'><i class='fa fa-print'></i>&nbsp;</a>";
                                                    //echo "<a href='view.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";
                                                    //echo "<a href='iupdate.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                                    //echo "<a href='idelete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-remove'></i></a>";
                                                echo "</td>"; 
                                            echo "</tr>";
                                        }//while ends
                                        echo "</tbody>";                            
                                    echo "</table>";


                                    // Free result set
                                    //mysqli_free_result($result);
                                } else{
                                    echo "<div class='lead'><em>No items are added yet.</em></div>";
                                }
                            } else{
                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                            }
        
                            // Close connection
                            //mysqli_close($link);
                            ?>

                            <br/>


                            <h5>Purchase Return</h5>

                            <?php
                            // Attempt select query execution
                            $sql = "SELECT * FROM purchase_return_details WHERE purchase_return_id=$param_id && dlt=0 ORDER BY `id` DESC";
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) > 0){
                                    echo "<table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th class='text-right'>Item ID</th>";
                                                echo "<th class='text-right'>Product ID</th>";
                                                echo "<th class='text-center'>Product Name</th>";
                                                echo "<th class='text-right'>GRN Unit Product Price</th>"; 
                                                echo "<th class='text-right'>Quantity</th>"; 
                                                echo "<th class='text-right'>Sub Total</th>";      
                                                //echo "<th>Action</th>";             
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                      // Inside the while loop for calculating subtotal
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<tr>";
                                            echo "<td class='text-right'>" . $row['id'] . "</td>";
                                            echo "<td class='text-right'>" . $row['product'] . "</td>";
                                            echo "<td class='text-center'>";   
                                                $z = $row['product']; 
                                                $count = "SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                                foreach ($link->query($count) as $row1) {
                                                    echo $row1['name'];  
                                                }       
                                            echo "</td>";

                                            echo "<td class='text-right'>";   
                                                // GRN Product Buying Price
                                                $count7 = "SELECT * FROM grn_details WHERE product=$z && grn_id=$grn"; // SQL to select
                                                foreach ($link->query($count7) as $row7) {
                                                    $buy = (float)$row7["buy"];
                                                }   
                                                echo $buy;
                                            echo "</td>";   

                                            $quantity = (float)$row['quantity'];
                                            echo "<td class='text-right'>" . $quantity . "</td>";

                                            $sub = $buy * $quantity;
                                            echo "<td class='text-right'>" . $sub . "</td>";

                                            $total += $sub;

                                            echo "</tr>";
                                        }

                                        
                                        echo "<tr class='text-right'>";
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
                            </div>     
                        
                            <div>
                            <a href="index.php" class="btn btn-success">Finish</a>
                            <a href="delete.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-danger">Cancel</a>
                            </div>
                            
        </div>               
    </div>

</body>
</html>
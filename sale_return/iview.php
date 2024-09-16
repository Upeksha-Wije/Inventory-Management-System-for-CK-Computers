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
$total=0;

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

    // Prepare a select statement
    $sql = "SELECT * FROM sale_returns WHERE id = ?";
    
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
     
                $date = $row["date"];

                $invoice = $row["invoice"];

                $sql2 = "SELECT * FROM invoices WHERE id=$invoice";
                $result2 = mysqli_query($link, $sql2);
                $row2 = mysqli_fetch_array($result2);

                $customer = $row2['customer'];     

                $status = $row["status"];

                $sr = $_GET["id"];

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
    <title>Create Sales Return</title>
   
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


    <div class="container mx-auto">
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-10">
                
        <div class="page-header">
            <h1>Create Sales Return</h1>
        </div>

            <div class="row">
                        <div class="col-sm-3"><label class="font-weight-bold">Sales Return ID:</label></div>
                        <div class="col-sm-1"><div class="form-control-static"><?php echo $_GET["id"]; ?></div></div>
                        <div class="col-sm-1"><label class="font-weight-bold">Date:</label></div>
                        <div class="col-sm-3"><div class="form-control-static"><?php echo $date; ?></div></div>               
            </div>

                <div class="row">
                        <div class="col-sm-3"><label class="font-weight-bold">Invoice ID:</label></div>
                        <div class="col-sm-1"><div class="form-control-static"><?php echo $invoice; ?></div></div>            
                        <div class="col-sm-2">
                            <label class="font-weight-bold">Customer ID:</label>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-control-static col-sm-1"><?php echo $customer; ?></div>
                        </div>
                        <div class="col-sm-2">
                            <label class="font-weight-bold">Customer: Name</label>
                        </div>
                        <?php
                            echo "<div class='col-sm-3'>";   
                            $count="SELECT * FROM customers WHERE id=$customer"; // SQL to get records 
                            foreach ($link->query($count) as $row1) {
                            echo $row1['firstname']." ".$row1['lastname'];  
                               }       
                            echo "</div>";  
                         ?>                
                </div>

            
                <h5 ><div class="row mt-2"><div class="row mt-3 mb-2">Invoice & Sales Return</div></div></h5>

                    <?php
                    // Attempt select query execution
                    $sql = "SELECT DISTINCT product FROM invoices_details WHERE invoices_id=$invoice && status=1 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Sales Return Item ID</th>";
                                        echo "<th>Product ID</th>";
                                        echo "<th>Product Name</th>";
                                        echo "<th>Invoice Quantity</th>"; 
                                        echo "<th>Total Sales Return</th>"; 
                                        echo "<th>Sales Return Quantity</th>"; 
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
                                            $count1="SELECT id FROM sale_returns_details WHERE product=$product && sale_returns_id=$sr && status=1"; // SQL to get records 
                                            foreach ($link->query($count1) as $row01) {
                                                $id = $row01['id'];  
                                            }                                               

                                        echo "<td>" . $id . "</td>";

                                        echo "<td>" . $product . "</td>";

                                        echo "<td>" . $pname . "</td>";

                                        echo "<td>";   
                                            // Invoice Quantity
                                            $count2="SELECT SUM(quantity) AS Total FROM invoices_details WHERE product=$product && invoices_id=$invoice && status=1"; // SQL to select product
                                            foreach ($link->query($count2) as $row2) {
                                                echo $tinvoice = $row2["Total"];
                                            }   
                                        echo "</td>"; 

                                        echo "<td>";   
                                            // Total Sales Return Quantity
                                            $count3="SELECT SUM(quantity) AS Total FROM sale_returns_details WHERE product=$product && sale_returns_id IN (SELECT id FROM sale_returns WHERE invoice=$invoice && status=1) && status=1"; // SQL to select product
                                            foreach ($link->query($count3) as $row3) {
                                                echo $tqty = $row3["Total"];
                                            }   
                                        echo "</td>"; 


                                        echo "<td>";   
                                            // Sales Return Quantity
                                            $count4="SELECT SUM(quantity) AS Total FROM sale_returns_details WHERE product=$product && sale_returns_id=$sr && status=1"; // SQL to select product
                                            foreach ($link->query($count4) as $row4) {
                                                echo $qty = $row4["Total"];
                                            }   

                                            if ($qty==""&&$tinvoice!==$tqty){
                                                echo "<a href='add.php?id=".$_GET["id"]."&pid=".$product."&tinvoice=".$tinvoice."&tqty=".$tqty."' title='Add Record' data-toggle='tooltip' class='btn btn-primary btn-sm col-sm-12'>&nbsp; Add Item &nbsp;</a>";           

                                            }elseif ($qty==""&&$tinvoice==$tqty){
                                                echo "Returned Already";
                                            }
                                        echo "</td>"; 


                                        //echo "<td>" . $tquantity . "</td>";

                                        //echo "<td>" . $row['quantity'] . "</td>";
                                        //echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>";
                                        if (!$row4["Total"]==""){
                                            echo "<a href='iupdate.php?id=". $id ."&tinvoice=".$tinvoice."&tqty=".$tqty."&qty=".$qty."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
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

                    <h5><div class="mb-2 mt-3">Sales Return</div></h5>

                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM sale_returns_details WHERE sale_returns_id=$param_id && status=1 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th class='text-right'>ID</th>";
                                        echo "<th class='text-right'>Product ID</th>";
                                        echo "<th class='text-center'>Product Name</th>";
                                        echo "<th class='text-right'>Quantity</th>"; 
                                        echo "<th class='text-right' >Sold Price</th>";
                                        echo "<th class='text-right'>Sub Total</th>";      
                                        //echo "<th>Action</th>";             
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td class='text-right'>" . $row['id'] . "</td>";
                                        echo "<td class='text-right'>" . $row['product'] . "</td>";
                                        echo "<td  class='text-center'>";   
                                            $z=$row['product']; 
                                            $count="SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                            foreach ($link->query($count) as $row1) {
                                                echo $row1['name'];  
                                            }       
                                        echo "</td>";
                                        echo "<td  class='text-right'>" . $row['quantity'] . "</td>";

                                        echo "<td  class='text-right'>";   
                                        $count2="SELECT * FROM invoices_details WHERE invoices_id=$invoice && product=$z"; // SQL to get records 
                                        foreach ($link->query($count2) as $row2) {
                                            echo $row2['sell'];  
                                            $subtotal = $row2['sell']*$row['quantity'];                        
                                            (int)$total=(int)$total+(int)$subtotal;
                                          }
                                        echo "</td>";

                                        echo "<td  class='text-right'>" . $subtotal . "</td>";
                                        //echo "<td>";
                                            //echo "<a href='print.php?id=". $row['id'] ."' title='Print Record' data-toggle='tooltip'><i class='fa fa-print'></i>&nbsp;</a>";
                                            //echo "<a href='view.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";
                                            /*
                                            echo "<a href='iupdate.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                            echo "<a href='idelete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-remove'></i></a>";
                                            
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
                            echo "<div class='lead'><em><br/>No items are added yet.</em></div>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                     

                <div>
                <a href="index.php" class="btn btn-success">Finish</a>
                <a href="delete.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-danger">Cancel</a>
                </div>
                
                    
    </div>
    </div>
                
    </div>

</body>
</html>
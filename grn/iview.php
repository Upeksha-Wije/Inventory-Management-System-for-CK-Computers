<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

//check if the user is logged in
if(!isset($_SESSION['user'])) {
    header('location: ../login.php');
}

$tquantity = $total = "";


// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM grn WHERE id = ?";
    
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
                
                $purchases = $row["purchases"];
                $date = $row["date"];

                $sql2 = "SELECT * FROM purchases WHERE id=$purchases";
                $result = mysqli_query($link, $sql2);
                $row0 = mysqli_fetch_array($result);

                $supplier = $row0['supplier'];
                $status = $row["status"];

                $grn = $_GET["id"];

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
    <title>Create GRN</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include "../common/header.php"; ?>
</head>      
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
            <div class="page-header mb-3">
                <h1>Create GRN</h1>
            </div>
    </div>
            <div class="row">
                <div class="col-lg-2"></div>
                        <div class="col-lg-1"><label class="font-weight-bold">GRN ID:</label></div>
                        <div class="col-lg-2"><div class="form-control-static"><?php echo $grn ?></div></div>
                        <div class="col-lg-1"><label class="font-weight-bold">Date:</label></div>
                        <div class="col-lg-2"><div class="form-control-static"><?php echo $row["date"]; ?></div></div>               
            </div>

            <div class="row">
                <div class="col-lg-2"></div>
                        <div class="col-lg-2"><label class="font-weight-bold">Purchase Order ID:</label></div>
                        <div class="col-lg-1"><div class="form-control-static"><?php echo $purchases; ?></div></div>            
                        <div class="col-lg-2">
                            <label class="font-weight-bold">Supplier ID:</label>
                        </div>
                        <div class="col-lg-1">
                            <div class="form-control-static"><?php echo $supplier ?></div>
                        </div>
                        <div class="col-lg-2">
                            <label class="font-weight-bold">Supplier Name:</label>
                        </div>
                        <?php
                            echo "<div class='col-lg-2'>";   
                            $count="SELECT * FROM suppliers WHERE id=$supplier"; // SQL to get records 
                            foreach ($link->query($count) as $row1) {
                            echo $row1['firstname']." ".$row1['lastname'];  
                               }       
                            echo "</div>";  
                         ?>                
            </div>

                    <br/>
                            
            <div class="row">
                <div class="col-lg-2 mb-5 ml-2"></div>
                    <h5>Purchase Order & GRN</h5>
            </div>

            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-10 mx-auto">
                        <?php
                        // Attempt select query execution
                        $sql = "SELECT DISTINCT product FROM purchases_details WHERE purchases_id=$purchases && status=1 ORDER BY `id` DESC";
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>GRN Item ID</th>";
                                            echo "<th>Product ID</th>";
                                            echo "<th>Product Name</th>";
                                            echo "<th>Purchase Order Quantity</th>"; 
                                            echo "<th>Total GRN</th>"; 
                                            echo "<th>GRN Quantity</th>"; 
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
                                                $count1="SELECT * FROM grn_details WHERE product=$product && grn_id=$grn && status=1"; // SQL to get records 
                                                foreach ($link->query($count1) as $row01) {
                                                    $id = $row01['id']; 

                                                }                                               

                                            echo "<td>" . $id . "</td>";
                                            echo "<td>" . $product . "</td>";

                                            echo "<td>" . $pname . "</td>";

                                            echo "<td>";   
                                                $count2="SELECT SUM(quantity) AS Total FROM purchases_details WHERE product=$product && purchases_id=$purchases && status=1"; // SQL to select product
                                                foreach ($link->query($count2) as $row2) {
                                                    echo $po = $row2["Total"];
                                                }   
                                            echo "</td>"; 

                                            echo "<td>";   
                                                $count3="SELECT SUM(quantity) AS Total FROM grn_details WHERE product=$product && grn_id IN (SELECT id FROM grn WHERE purchases=$purchases && status=1) && status=1"; // SQL to select product
                                                foreach ($link->query($count3) as $row3) {
                                                    echo $tgrn = $row3["Total"];
                                                }   
                                            echo "</td>"; 


                                            echo "<td>";   
                                                $count4="SELECT SUM(quantity) AS Total FROM grn_details WHERE product=$product && grn_id=$grn && status=1"; // SQL to select product
                                                foreach ($link->query($count4) as $row4) {
                                                    echo $qty = $row4["Total"];
                                                }   

                                                if ($row4["Total"]==""&&$po!==$tgrn){
                                                    echo "<a href='add.php?id=".$_GET["id"]."&pid=".$product."&po=".$po."&tgrn=".$tgrn."' title='Add Record' data-toggle='tooltip' class='btn btn-primary btn-sm col-sm-12'>&nbsp; Add Item &nbsp;</a>";           

                                                }elseif ($row4["Total"]==""&&$po==$tgrn){
                                                    echo "Received Already";
                                                }
                                            echo "</td>"; 


                                            //echo "<td>" . $tquantity . "</td>";

                                            //echo "<td>" . $row['quantity'] . "</td>";
                                            //echo "<td>" . $row['status'] . "</td>";
                                            echo "<td>";
                                            if (!$row4["Total"]==""){
                                                echo "<a href='iupdate.php?id=". $id ."&po=".$po."&tgrn=".$tgrn."&qty=".$qty."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                                echo "<a href='idelete.php?id=". $id ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
                                            }

                                                //echo "<a href='print.php?id=". $row['id'] ."' title='Print Record' data-toggle='tooltip'><i class='fa fa-print'></i>&nbsp;</a>";
                                                //echo "<a href='view.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";
                                                //echo "<a href='iupdate.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                                //echo "<a href='idelete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
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

                            <h5>GRN</h5>
                    <br/>
                
                
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM grn_details WHERE grn_id=$param_id && status=1 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th class='text-right'>Item ID</th>";
                                        echo "<th class='text-right'>Product ID</th>";
                                        echo "<th class='text-center'>Product Name</th>";
                                        echo "<th class='text-right'>Unit Buying Price</th>"; 
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

                                        $sub=(int)$q*(int)$b;
                                        echo "<td class='text-right'>" . $sub . "</td>";

                                        (int)$total=(int)$total+(int)$sub;

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
                            echo "<br/><div class='lead'><em><br/>No items are added yet.</em></div>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>


                    <div class="row">
                        <div class="col-sm-12 mx-auto"></div>

                        <a href="index.php" class="btn btn-success">Finish</a> &nbsp;
                        
                        <a href="delete.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-danger">Cancel</a>
                    </div>

                </div>     
                
                
                
                   
            
    
</body>
</html>
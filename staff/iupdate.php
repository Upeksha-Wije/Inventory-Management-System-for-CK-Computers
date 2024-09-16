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

// Define variables and initialize with empty values
$quantity = $product = $status = "";
$quantity_err = $product_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM purchase_return_details WHERE id = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $x = $row["purchase_return_id"]; 
                    $Oproduct = $row["product"]; 
                    $Oquantity = $row["quantity"]; 
                    //$status = $row["status"];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
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
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
 
    $quantity = $Oquantity;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //process product    
    $product = test_input($_POST["product"]);     

    // Validate quantity
    $input_quantity = test_input($_POST["quantity"]);

    // Getting the values passed through the url
    $po = trim($_GET["po"]);
    $tgrn = trim($_GET["tgrn"]);
    $tqty = trim($_GET["tqty"]);
    $qty = trim($_GET["qty"]);
    
    // Finding the maximim allowable quantity
    $b=array($tgrn,-$tqty,$qty);
    $max = array_sum($b);

    if(empty($input_quantity)){
        $quantity_err = "Please enter the returned product quantity";     
    } elseif(!ctype_digit($input_quantity)){
        $quantity_err = 'Please enter a positive integer value';
        $quantity = $input_quantity;
    }elseif($input_quantity>$max){
        $quantity_err = 'Please enter a value that will not exceed your GRN quantity. Maximum Allowable value is '.$max.".";
        $quantity = $input_quantity;    
    } else{
        $quantity = $input_quantity;
    }    

//process status
    $status = test_input($_POST["status"]);

    // Check input errors before inserting to database
    if(empty($quantity_err)){

        //UPDATE STOCK TABLE FOR OLD VALUES
        $sql3 = "SELECT * FROM stock WHERE pid=$Oproduct";
        $result3 = mysqli_query($link, $sql3);
        $row0 = mysqli_fetch_array($result3);
        $Ostock = $row0['quantity'];
    
        $sql4 = "UPDATE `stock` SET `quantity`=? WHERE pid=?";        
    
        if($stmt4 = mysqli_prepare($link, $sql4)){   
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt4, "ii", $param_quantity, $param_pid);
            
            // Set parameters             
            $param_quantity = $Ostock+$Oquantity;
            $param_pid = $Oproduct; 
    
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt4)){
                // Records created successfully. Redirect to landing page
                //header("location: iview.php?id=$x");
                //exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
    
        }  

        //UPDATE PURCHASE RETURN DETAILS TABLE           
        $sql2 = "UPDATE `purchase_return_details` SET `product`=?,`quantity`=? WHERE id=?;";       

        if($stmt2 = mysqli_prepare($link, $sql2)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt2, "iii", $param_product, $param_quantity, $param_id);
            
            // Set parameters
            $param_product = $product;            
            $param_quantity = $quantity;
            $param_id = trim($_GET["id"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt2)){

                // Records created successfully. Redirect to landing page
                //header("location: iview.php?id=$x");
                //exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }

        }
        
        //UPDATE STOCK TABLE FOR NEW VALUES
        $sql = "SELECT * FROM stock WHERE pid=$product";
        $result = mysqli_query($link, $sql);
        $row0 = mysqli_fetch_array($result);
        $stock = $row0['quantity'];
 
        $sql2 = "UPDATE `stock` SET `quantity`=? WHERE pid=?";        
 
        if($stmt2 = mysqli_prepare($link, $sql2)){   
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt2, "ii", $param_quantity, $param_pid);
            
            // Set parameters             
            $param_quantity = $stock-$quantity;
            $param_pid = $product; 
 
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt2)){
                // Records created successfully. Redirect to landing page
                header("location: iview.php?id=$x");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
 
        }        
        

    }//ERR ENDS
    
        // Close connection
        //mysqli_close($link);

        // Close statement
        //mysqli_stmt_close($stmt);

}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Item</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include "../common/header.php"; ?>
    
</head>


<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">  
      
        <!-- Navbar -->
        <?php require_once '../common/navbar.html'; ?>
        
    <br/>
    <br/>
    <br/>

        <div class="container">
            <div class="row">
                <div class="mx-auto">
                    <div class="page-header">
                        <h2>Update Item</h2>
                    </div>
                    <p>Please fill this form and submit to update items in the Purchase Return.
                    (<code> * Compulsary fields</code> )</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">

                        <div class="form-group">
                            <label class="font-weight-bold">Select Product</label>
                            <select name="product" class="form-control" id="product">     
            
                                    <?php $count=" SELECT * FROM products WHERE status=1 && id=$Oproduct"; // SQL to get records 
                                    foreach (mysqli_query($link,$count) as $row) {?>
                                <option value=<?php echo $row['id']; ?>><?php echo $row['name'] ?> </option>                                   
                                    <?php } ?>

                                    <?php
                                    /*
                                    $count2=" SELECT * FROM products WHERE status=1"; // SQL to get records 
                                    foreach ($link->query($count2) as $row) {?>
                                <option value=<?php echo $row['id']; ?>><?php echo $row['name'] ?> </option>                                   
                                    <?php } 
                                    */
                                    ?>
                            </select>
                        </div>     

                        <div class="form-group">
                            <label class="font-weight-bold">Quantity<code>*</code></label>
                            <input type="text" name="quantity" class="form-control" value="<?php echo $quantity; ?>" placeholder="Enter returned product quantity">
                            <code><span class="help-block"><?php echo $quantity_err;?></span></code>
                        </div>  

                        <input type="hidden" name="status" value="1">

                        <button type="submit" class="btn btn-success" name="register_btn"> + Update Item</button>                        
                        <a href="iview.php?id=<?php echo $x; ?>" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        
        <br/>

</body>
</html>
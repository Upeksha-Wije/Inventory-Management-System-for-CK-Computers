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
$price = $product = $quantity = $sell = $status = "";
$quantity_err = $sell_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //process product    
    $product = test_input($_POST["product"]); 
    
    $count="SELECT * FROM products WHERE id=$product"; // SQL to get records 
    foreach ($link->query($count) as $row1) {
    $price = $row1['price'];  
       }       

       
    $sql = "SELECT * FROM stock WHERE pid=$product";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    $stock = $row['quantity'];

// Validate quantity
    $input_quantity = test_input($_POST["quantity"]);
    if(empty($input_quantity)){
        $quantity_err = "Please enter the product quantity";     
    } elseif(!ctype_digit($input_quantity)){
        $quantity_err = 'Please enter a positive integer value';
        $quantity = $input_quantity;
    }  elseif($input_quantity>$stock){
        $quantity_err = 'Please enter a quantity available in the stock. Available stock quantity is'." ".$stock;
        $quantity = $input_quantity;
    } else{
        $quantity = $input_quantity;
    } 
    
    // Validate selling price
    $input_sell = test_input($_POST["sell"]);
    if(empty($input_sell)){
        $sell_err = "Please enter the Unit Selling Price";     
    } elseif(!ctype_digit($input_sell)){
        $sell_err = 'Please enter a positive integer value';
        $sell = $input_sell;
    }  elseif($price>$input_sell){
        $sell_err = 'Please enter a price larger than the Least Selling Price. Least Selling Price is'." ".$price;
        $sell = $input_sell;
    } else{
        $sell = $input_sell;
    }       

//process status
    $status = test_input($_POST["status"]);

    // Check input errors before inserting in database
    if(empty($quantity_err)&&empty($sell_err)){

        //TABLE 2           
       
        $sql2 = "INSERT INTO invoices_details (invoices_id, product, quantity, sell, status) VALUES (?, ?, ?, ?, ?)";

        if($stmt2 = mysqli_prepare($link, $sql2)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt2, "isiii", $param_invoices_id, $param_product, $param_quantity, $param_sell, $param_status);
            
            // Set parameters
            //$param_id = trim($_GET["id"]);
            $param_invoices_id = trim($_GET["id"]);
            $param_product = $product;            
            $param_quantity = $quantity;
            $param_sell = $sell;
            $param_status = $status;


            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt2)){

                // Records created successfully. Redirect to landing page
                //header("location: iview.php?id=$_GET[id]");
                //exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }        


        //UPDATE STOCK TABLE         
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
                header("location: iview.php?id=$_GET[id]");
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


}//FORM SUBMITTED ENDS

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Item</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    
     
    <?php include "../common/header.php"; ?>
</head>

<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
        
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

        
    <br/>
    <br/>
    <br/>

        <div class="container">
            <div class="row">
                <div class="mx-auto">
                    <div class="page-header">
                        <h2>Add Item</h2>
                    </div>
                    <p>Please fill this form and submit to add items to the Invoice.
                    (<code> * Compulsary fields</code> )</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">

                        <div class="form-group">
                            <label class="font-weight-bold">Select Product</label>
                            <select name="product" class="form-control" id="product">

    <?php 
    // First, select the specific product to mark as selected
    $query = "SELECT * FROM products WHERE status=1 AND id=?";
    
    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $product); // Bind the product ID to the query
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . htmlspecialchars($row['id']) . '" selected>' . htmlspecialchars($row['name']) . '</option>';
        }

        mysqli_stmt_close($stmt);
    }

    // Now, select all products, making sure to not duplicate the selected one
    $query2 = "SELECT * FROM products WHERE status=1 ORDER BY id DESC";
    
    if ($result2 = mysqli_query($link, $query2)) {
        while ($row2 = mysqli_fetch_assoc($result2)) {
            if ($row2['id'] != $product) {
                echo '<option value="' . htmlspecialchars($row2['id']) . '">' . htmlspecialchars($row2['name']) . '</option>';
            }
        }
    }
    ?>

</select>

                        </div>     

                        <div class="form-group">
                            <label class="font-weight-bold">Quantity<code>*</code></label>
                            <input type="text" name="quantity" class="form-control" value="<?php echo $quantity; ?>" placeholder="Enter the product quantity">
                            <code><span class="help-block"><?php echo $quantity_err;?></span></code>
                        </div>  
                        
                        <div class="form-group">
                            <label class="font-weight-bold">Selling Price<code>*</code></label>
                            <input type="text" name="sell" class="form-control" value="<?php echo $sell; ?>" placeholder="Enter the unit selling price">
                            <code><span class="help-block"><?php echo $sell_err;?></span></code>
                        </div>                          

                        <input type="hidden" name="status" value="1">

                        <button type="submit" class="btn btn-success" name="register_btn"> + Add Item</button>                        
                        <a href="iview.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        
        <br/>

</body>
</html>
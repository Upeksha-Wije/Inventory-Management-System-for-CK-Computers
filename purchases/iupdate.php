<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

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
        $sql = "SELECT * FROM purchases_details WHERE id = ?";
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

                    $x = $row["purchases_id"]; 
                    $product = $row["product"]; 
                    //$parent_cat_name = $row["parent_cat_name"]; 
                    $quantity = $row["quantity"]; 
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

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

//process price    
    $product = test_input($_POST["product"]);     

    // Validate Stock
    $input_quantity = test_input($_POST["quantity"]);
    if(empty($input_quantity)){
        $quantity_err = "Please enter the required product quantity";     
    } elseif(!ctype_digit($input_quantity)){
        $quantity_err = 'Please enter a positive integer value';
        $quantity = $input_quantity;
    } else{
        $quantity = $input_quantity;
    }    

//process status
    $status = test_input($_POST["status"]);

    // Check input errors before inserting in database
    if(empty($name_err) && empty($quantity_err)){

        //TABLE 2           
        $sql2 = "UPDATE `purchases_details` SET `product`=?,`quantity`=? WHERE id=?;";       

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


}else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM purchases_details WHERE id = ?";
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

                    //$product = $row["product"]; 
                    //$parent_cat_name = $row["parent_cat_name"]; 
                    $quantity = $row["quantity"]; 
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
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Item</title>
    
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

        <div class="container">
            <div class="row">
                <div class="mx-auto">
                    <div class="page-header">
                        <h2>Update Item</h2>
                    </div>
                    <p>Please fill this form and submit to update items in the Purchase Order.
                    (<code> * Compulsary fields</code> )</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">

                        <div class="form-group">
                            <label class="font-weight-bold">Select Product</label>
                            
                            <select name="product" class="form-control" id="product">     

                                    <?php $count=" SELECT * FROM products WHERE status=1 && id=$product"; // SQL to get records 
                                    foreach (mysqli_query($link,$count) as $row) {?>
                                <option value=<?php echo $row['id']; ?>><?php echo $row['name'] ?> </option>                                   
                                    <?php } ?>

                                    <?php $count2="select * from products WHERE status=1"; // SQL to get records 
                                    foreach ($link->query($count2) as $row) {?>
                                <option value=<?php echo $row['id']; ?>><?php echo $row['name'] ?> </option>                                   
                                    <?php } ?>
                            </select>
                        </div>     

                        <div class="form-group">
                            <label class="font-weight-bold">Quantity<code>*</code></label>
                            <input type="text" name="quantity" class="form-control" value="<?php echo $quantity; ?>" placeholder="Enter required product quantity">
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
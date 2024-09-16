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
$category = $name = $price = $reorder = $last_id = $status = $quantity = $description = "";
$category_err = $name_err = $price_err = $reorder_err = "";

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
        $sql = "SELECT * FROM products WHERE id = ?";
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

                    $category = $row["category"]; 
                    $name = $row["name"]; 
                    $price = $row["price"]; 
                    $reorder = $row["reorder"]; 
                    $description = $row["description"];   
                    $status = $row["status"];

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
        mysqli_stmt_close($stmt);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate category
    $input_category = test_input($_POST["category"]);
    if(empty($input_category)){
        $category_err = "Please select a category";
    } elseif(!ctype_digit($input_category)) {
        $category_err = 'Please enter a positive integer value';
        $category = $input_category;
    } else{
        $category = $input_category;
    }  

    // Validate name
    $input_name = test_input($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name";
    } /*elseif(!preg_match("/^[a-zA-Z ]*$/",$input_name)) {
        $name_err = 'Only letters and white space allowed';
        $name = $input_name;
    }*/ else{
        $name = $input_name;
    }
    
    // Validate price
    $input_price = test_input($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter a price";
    } else{
        $price = $input_price;
    }

    
    
    // Validate reorder
    $input_reorder = test_input($_POST["reorder"]);
    if(empty($input_reorder)){
        $reorder_err = "Please enter a reorder count";     
    }  else{
        $reorder = $input_reorder;
    }

    // Validate Description
    $input_description = test_input($_POST["description"]);
    if(empty($input_description)){
        $description_err = 'Please enter an description';     
    } else{
        $description = $input_description;
    }

   
    $status = $_POST["status"];
 

    // Check input errors before inserting in database
    if(empty($category_err) && empty($name_err) && empty($price_err) && empty($reorder_err)){
        // Get the id of the record
        $id =  $_GET["id"];
        // Prepare an update statementto pdate the record
        $sql = "UPDATE `products` SET `category`=?,`name`=?,`price`=?,`reorder`=?,`description`=?,`status`=? WHERE id=$id;";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_category, $param_name, $param_price, $param_reorder, $param_description, $param_status);
            
            // Set parameters
            $param_category = $category;            
            $param_name = $name;
            $param_price = $price;
            $param_reorder = $reorder;
            $param_description = $description;            
            $param_status = $status;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    
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
             <div class="mx-auto col-lg-5">
                 <div class="page-header">
                     <h2>Create Product</h2>
                 </div>
                 <div>Please fill this form and submit to add products to the database.
                 (<code> * Compulsary fields</code> )</div>
                 <br/>
                 <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
                     <div class="form-group">
                         <label class="font-weight-bold">Category</label>

                         <select name="category" class="form-control" id="category">
                                                     
                                 <?php $count="select * from category WHERE status=1 && id!=0"; // SQL to get records 
                                 foreach ($link->query($count) as $row) {?>
                             <option value=<?php echo $row['id'] ?>><?php echo $row['category_name'] ?> </option>                         
                                 <?php } ?>

                                 <code><span class="help-block"><?php echo $category_err;?></span></code>
                         </select>                         
                     </div>                     
                     <div class="form-group">
                         <label class="font-weight-bold">Product Name<code>*</code></label>
                         <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" placeholder="Enter Product Name">
                         <code><span class="help-block"><?php echo $name_err;?></span></code>
                     </div>

                     <div class="form-group">
                         <label class="font-weight-bold">Unit Selling Price<code>*</code></label>
                         <input type="text" name="price" class="form-control" value="<?php echo $price; ?>" placeholder="Enter Unit Selling Price">
                         <code><span class="help-block"><?php echo $price_err;?></span></code>
                     </div>

                     <div class="form-group">
                         <label class="font-weight-bold">Reorder Level<code>*</code></label>
                         <input type="text" name="reorder" class="form-control" value="<?php echo $reorder; ?>" placeholder="Enter product Reorder Level">
                         <code><span class="help-block"><?php echo $reorder_err;?></span></code>
                     </div>   

                     <div class="form-group">
                             <label class="font-weight-bold">Description</label>
                             <textarea name="description" class="form-control" placeholder="Enter Description"><?php echo $description; ?></textarea>
                     </div>                                                                     

                     <input type="hidden" name="status" value="1">

                     <button type="submit" class="btn btn-primary" name="register_btn"> Update Record</button>                        
                     <a href="index.php" class="btn btn-danger">Cancel</a>
                 </form>
             </div>
         </div>        
     </div>
     
<br>    
</body>
</html>

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

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate category
    $input_category_name = test_input($_POST["category"]);
    if(empty($input_category_name)){
        $category_name_err = "Please select a category";
    
    } else{
        $category_name = $input_category_name;
    }  

    // Validate product name
    $input_name = test_input($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a product name";
    }else{
        $name = $input_name;
    } 
    
    // Validate unit price
    $input_price = test_input($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter unit product selling price";
    } elseif(!ctype_digit($input_price)) {
        $price_err = 'Please enter a positive integer value';
        $price = $input_price;
    } else{
        $price = $input_price;
    }        

    // Validate reorder level
    $input_reorder = test_input($_POST["reorder"]);
    if(empty($input_reorder)){
        $reorder_err = "Please enter the product reorder level";
    } elseif(!ctype_digit($input_reorder)) {
        $reorder_err = 'Please enter a positive integer value';
        $reorder = $input_reorder;
    } else{
        $reorder = $input_reorder;
    }      

    //validate description
    $description = test_input($_POST["description"]);

    //process status
    $status = $_POST["status"];

    
    // Check input errors before inserting in to database
    if(empty($category_err) && empty($name_err) && empty($price_err) && empty($reorder_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO products (category, name, price, reorder, description, status) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isiisi", $param_category, $param_name, $param_price, $param_reorder, $param_description, $param_status);
            
            // Set parameters
            $param_category = $category_name;            
            $param_name = $name;
            $param_price = $price;
            $param_reorder = $reorder;
            $param_description = $description;            
            $param_status = $status;
                      
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                //header("location: index.php");
                //exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);

        //CREATE A RECORD FOR PRODUCT IN THE STOCK TABLE         
        $last_id = mysqli_insert_id($link);           
            
        $sql2 = "INSERT INTO stock (pid, quantity) VALUES (?, ?)";

        if($stmt2 = mysqli_prepare($link, $sql2)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt2, "ii", $param_id, $param_quantity);
            
            // Set parameters
            $param_id = $last_id;               
            $param_quantity = $quantity;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt2)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                //header("location: view.php?id=$last_id");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }        
    }
    
    // Close statement
    //mysqli_stmt_close($stmt2);

    // Close connection
    //mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

   

</head>

<body style="background-image: url(../images//Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;"></body>
        <?php include "../common/header.php"; ?>
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
                            <label class="font-weight-bold">Minimum Unit Selling Price<code>*</code></label>
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

                        <button type="submit" class="btn btn-primary" name="register_btn"> + Create Product</button>                        
                        <a href="index.php" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        
        <br/>
    </div>
</body>
</html>
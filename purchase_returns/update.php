<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if (!isAdmin()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

 
// Define variables and initialize with empty values
$id = $parent_cat = $category_name = $status = "";
$parent_cat_err = $category_name_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){


    // Validate category name name
    $input_category_name = test_input($_POST["category_name"]);
    if(empty($input_category_name)){
        $category_name_err = "Please enter a category name";
    } elseif(!preg_match("/^[a-zA-Z ]*$/",$input_category_name)) {
        $category_name_err = 'Only letters and white space allowed';
        $category_name = $input_category_name;
    } else{
        $category_name = $input_category_name;
    }

//process parent cat    
    $parent_cat = $_POST["parent_cat"];    


//process status
    $status = $_POST["status"];


    // Check input errors before inserting in database
    if(empty($category_name_err) && empty($parent_cat_err)){

    // Get the id of the record
    $id =  $_GET["id"];
 
        // Prepare an update statement to pdate the record
        $sql = "UPDATE `categories` SET `parent_cat`=?,`category_name`=?,`status`=? WHERE id=?;";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_parent_cat, $param_category_name, $param_status, $param_id);
            
            // Set parameters
            $param_parent_cat = $parent_cat;
            //$param_parent_cat_name = $parent_cat_name;
            $param_category_name = $category_name;
            $param_status = $status;
            $param_id = $id;
            
            
            
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
        //mysqli_stmt_close($stmt);
    }
    
    // Close connection
    //mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM categories WHERE id = ?";
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

                    $parent_cat = $row["parent_cat"]; 
                    //$parent_cat_name = $row["parent_cat_name"]; 
                    $category_name = $row["category_name"]; 
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
    <title>Update Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to categories to the database.
                    (<code> * Compulsary fields</code> )</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
                        <div class="form-group">
                            <label class="font-weight-bold">Parent Category</label>

                            <select name="parent_cat" class="form-control" id="parent_cat">
                                <option value="<?php echo $row['parent_cat'] ?>"><?php echo $row['parent_cat'] ?></option>
                                <option value="">Root</option>                            
                                    <?php $count="select * from categories WHERE parent_cat=''&&status=1"; // SQL to get records 
                                    foreach ($link->query($count) as $row) {?>
                                <option value=<?php echo $row['category_name'] ?>><?php echo $row['category_name'] ?> </option>
                                    
                                    <?php } ?>

                            </select>
                        </div>                     
                        <div class="form-group">
                            <label class="font-weight-bold">Category Name<code>*</code></label>
                            <input type="text" name="category_name" class="form-control" value="<?php echo $category_name; ?>" placeholder="Enter Category Name">
                            <code><span class="help-block"><?php echo $category_name_err;?></span></code>
                        </div>

                        <input type="hidden" name="status" value="1">

                        <button type="submit" class="btn btn-success" name="register_btn"> + Create Category</button>                        
                        <a href="index.php" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        
        </br>


<br>    
</body>
</html>

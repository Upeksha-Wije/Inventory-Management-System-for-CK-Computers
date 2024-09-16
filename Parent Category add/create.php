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
$parent_cat_name = "";
$parent_cat_name_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){   


    // Validate category name
    $input_parent_cat_name = test_input($_POST["parent_cat_name"]);
    if(empty($input_parent_cat_name)){
        $parent_cat_name_err = "Please enter a parent category name";
    } /*elseif(!preg_match("/^[a-zA-Z ]*$/",$input_category_name)) {
        $category_name_err = 'Only letters and white space allowed';
        $category_name = $input_category_name;
    }*/ else{
        $parent_cat_name = $input_parent_cat_name;
    }

    //process status
    //$status = test_input($_POST["status"]);


    // Check input errors before inserting in database
    if(empty($parent_cat_name_err) ){
        // Prepare an insert statement
        $sql = "INSERT INTO p_category (parent_cat_name) VALUES (?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_parent_cat_name);
            
            // Set parameters
            $param_parent_cat_name = $parent_cat_name;
            //$param_status = $status;
                       
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: ../category/create.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
       // mysqli_stmt_close($stmt);
    }
    
     //Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Category</title>
    
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
    

        <div class="container col-lg-5">
        <div class="row">
           <div class="mx-auto">
                    <div class="page-header">
                        <h2>Create Parent Category</h2>
                    </div>
                    <div>Please fill this form and submit to add parent category to the database.
                    (<code> * Compulsary fields</code> )</div>
                    <br/>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
                      

                        <div class="form-group">
                            <label class="font-weight-bold">Parent Category Name<code>*</code></label>
                            <input type="text" name="parent_cat_name" class="form-control" value="<?php echo $parent_cat_name; ?>" placeholder="Enter Parent Category Name">
                            <code><span class="help-block"><?php echo $parent_cat_name_err;?></span></code>
                        </div>

                        <input type="hidden" name="status" value="1">

                        <button type="submit" class="btn btn-primary" name="register_btn"> + Create Parent Category</button>                        
                            <a href="index.php" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        
        <br/>

</body>
</html>
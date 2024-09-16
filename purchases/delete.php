<?php
// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if(!isset($_SESSION['user'])) {
    header('location: ../login.php');
}




// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Include config file
    require_once "../common/config.php";
    
    // Prepare a delete statement
    $sql = "UPDATE purchases SET status = 0 WHERE id = ?";

    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement 
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     

    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
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
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">

    <?php include "../common/header.php"; ?>
    <!-- Navbar -->
    <?php require_once '../common/navbar.php'; ?>
    

        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mt-5 mb-3">Delete Record</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="alert alert-danger">
                                <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                                <p>Are you sure you want to change the status of this employee record?</p>
                                <p>
                                    <input type="submit" value="Yes" class="btn btn-danger">
                                    <a href="index.php" class="btn btn-primary">No</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>        
            </div>
        </div>
</body>
</html>
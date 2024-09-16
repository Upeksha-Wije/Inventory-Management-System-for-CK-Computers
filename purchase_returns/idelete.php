<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';
    
//check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

$id =  trim($_GET["id"]);
        
// Prepare a select statement
$sql = "SELECT * FROM grn_details WHERE id = ?";

if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);
    
    // Set parameters
    $param_id = $id;
    
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) == 1){
            // Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            // Retrieve individual field value
            $x = $row["grn_id"]; 
            $product = $row["product"]; 
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


// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    
    // Prepare a delete statement
    $sql2 = "UPDATE grn_details SET status=0, dlt=1 WHERE id = ?";
    
    if($stmt2 = mysqli_prepare($link, $sql2)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt2, "i", $param_id);
        
        // Set parameters
        $param_id = $_POST["id"];
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt2)){
            // Records deleted successfully. Redirect to landing page
            //header("location: iview.php?id=$x");
            //exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    $sql3 = "SELECT * FROM stock WHERE pid=$product";
    $result3 = mysqli_query($link, $sql3);
    $row0 = mysqli_fetch_array($result3);
    $stock = $row0['quantity'];

    //Update stock table           
    $sql4 = "UPDATE `stock` SET `quantity`=? WHERE pid=?";        

    if($stmt4 = mysqli_prepare($link, $sql4)){   
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt4, "ii", $param_quantity, $param_pid);
        
        // Set parameters             
        $param_quantity = $stock-$quantity;
        $param_pid = $product; 

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt4)){
            // Records created successfully. Redirect to landing page
            header("location: iview.php?id=$x");
            exit();
        } else{
            echo "Something went wrong. Please try again later.";
        }

    }        
    // Close statement
    //mysqli_stmt_close($stmt);
    
    // Close connection
    //mysqli_close($link);
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
    <title>Delete Item</title>
    
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
    <br/>
    
        <div class="container col-sm-4 mx-auto">
            <div class="row">
                <div class="mx-auto">
                    <h1>Delete Item ?</h1>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
                    
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this Item?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="iview.php?id=<?php echo $x; ?>" class="btn btn-primary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
</body>
</html>

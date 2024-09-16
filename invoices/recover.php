<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

// check is the logged in user is admin 
if (!isAdmin()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}


// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){

    // Prepare a delete statement fro invoices_details table
    $sql = "UPDATE invoices_details SET status=1 WHERE dlt=0 && invoices_id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            //header("location: index.php");
            //exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Attempt select query execution. Select invoices items which are not deleted
    $sql2 = "SELECT * FROM invoices_details WHERE invoices_id=$param_id && dlt=0";
    if($result2 = mysqli_query($link, $sql2)){
        while($row2 = mysqli_fetch_array($result2)){
            $product = $row2["product"];

            $sql3 = "SELECT * FROM stock WHERE pid=$product";
            $result3 = mysqli_query($link, $sql3);
            $row3 = mysqli_fetch_array($result3);
            $stock = $row3['quantity'];

            $sql4 = "SELECT * FROM invoices_details WHERE invoices_id=$param_id && dlt=0 && product=$product";
            $result4 = mysqli_query($link, $sql4);
            $row4 = mysqli_fetch_array($result4);
            $quantity = $row4['quantity'];     
        
            //Update stock table           
            $sql5 = "UPDATE `stock` SET `quantity`=? WHERE pid=?";        
        
            if($stmt5 = mysqli_prepare($link, $sql5)){   
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt5, "ii", $param_quantity, $param_pid);
                
                // Set parameters             
                $param_quantity = $stock-$quantity;
                $param_pid = $product; 
        
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt5)){
                    // Records created successfully. Redirect to landing page
                    //header("location: iview.php?id=$x");
                    //exit();
                } else{
                    echo "Something went wrong. Please try again later.";
                }
        
            }        
        }
    }    

    // Prepare a delete statement for invoices table
    $sql6 = "UPDATE invoices SET status=1, dlt=0 WHERE id = ?";
    
    if($stmt6 = mysqli_prepare($link, $sql6)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt6, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt6)){
            // Records deleted successfully. Redirect to landing page
            header("location: dindex.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
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
    <title>Recover Invoice</title>
    
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
                    <h1>Recover ?</h1>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">        
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <div>Are you sure you want to recover this Invoice?</div><br>
                            <div>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="dindex.php" class="btn btn-primary">No</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
</body>
</html>

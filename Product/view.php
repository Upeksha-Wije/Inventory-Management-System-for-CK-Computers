<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

    // Prepare a select statement
    $sql = "SELECT * FROM products WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $category = $row["category"]; 
                $name = $row["name"]; 
                $price = $row["price"]; 
                $reorder = $row["reorder"]; 
                $description = $row["description"];   
                $status = $row["status"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Staff</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">


        <?php include "../common/header.php"; ?>
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

    <br/>
    <br/>
    <br/>

    <div class="container">
    <div class="text">
        <div class="row">       
            <div class="mx-auto col-sm-6">
                <div class="page-header">
                        <h2>View Products</h2>
                        <br>
                </div>
            
                <div class="row">
                    <div class="form group col-sm-4">
                        <label class="font-weight-bold">Product ID :</label>
                    </div>
                    <div class="form group col-sm-8">
                        <div class="form-control-static"><?php echo $_GET["id"]; ?></div>
                    </div>  
                </div> 

                <div class="row">   
                    <div class="form group col-sm-4">
                        <label class="font-weight-bold">Category :</label>
                    </div>
                    <div class="form group col-sm-8">
                        <div class="form-control-static"><?php echo $row["category"]; ?></div>
                    </div> 
                </div>              

                <div class="row">                                             
                    <div class="form group col-sm-4">
                        <label class="font-weight-bold">Product Name :</label>
                    </div>
                    <div class="form group col-sm-8">                        
                        <div class="form-control-static"><?php echo $row["name"]; ?></div>
                    </div>
                </div>  

                <div class="row">
                    <div class="form group col-sm-4">
                        <label class="font-weight-bold">Product Price :</label>
                    </div>
                    <div class="form group col-sm-8">                         
                        <div class="form-control-static"><?php echo $row["price"]; ?></div>
                    </div>   
                </div>

                <div class="row">
                    <div class="form group col-sm-4">
                        <label class="font-weight-bold">Reorder Level :</label>
                    </div>
                    <div class="form group col-sm-8">  
                        <div class="form-control-static"><?php echo $row["reorder"]; ?></div>
                    </div>
                </div>

                <div class="row">
                    <div class="form group col-sm-4">
                        <label class="font-weight-bold">Description :</label>
                    </div>
                    <div class="form group col-sm-8">  
                        <div class="form-control-static"><?php echo $row["description"]; ?></div>
                    </div>
                </div>

                
                <!-- <div class="row"> 
                    <div class="form group col-sm-6">
                        <label class="font-weight-bold">Image :</label>
                    </div>
                    <div class="form group col-sm-6">  
                        <div class="form-control-static"><?php echo $row["image"]; ?></div>
                    </div>   
                </div> -->

                
                <br>

                    <a href="index.php" class="btn btn-primary mr-3 mb-4">Back </a>                   
                    <a href="print.php?id=<?php echo $_GET["id"]; ?>" title=" Print Record" data-toggle="tooltip" target="_blank"><i class="fa fa-print"></i>&nbsp;</a>
                </div>
            </div>
     
        </div>
    </div>
</body>
</html>
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

// Define variables and initialize with empty values
$purchases = $supplier = $last_id = $status = "";
$supplier_err = $product_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //process purchases id 
    $purchases = test_input($_POST["purchases"]);             

    //process status
    $status = test_input($_POST["status"]);

    // Check input errors before inserting in database
    if(empty($name_err) && empty($price_err)){

        //INSERT INTO GRN TABLE
        // Prepare an insert statement
        $sql = "INSERT INTO grn (purchases, status) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_purchases, $param_status);
            
            // Set parameters
            $param_purchases = $purchases;
            $param_status = $status;
  
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $last_id = mysqli_insert_id($link);          
                // Records created successfully. Redirect to landing page
                //header("location: index.php");
                header("location: iview.php?id=$last_id");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }

        }//STMT ENDS

    }//ERR ENDS
    
        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);

}//FORM SUBMITTED ENDS

?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create GRN</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include "../common/header.php"; ?>     
</head>

<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
    <!-- Navbar -->
    <?php require_once '../common/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="mx-auto">
                <div class="page-header">
                    <h2>Create GRN</h2>
                </div>
                <p>Please select the Purchase Order ID to create a new GRN. (<code>* Compulsory fields</code>)</p>
                <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">

                    <div class="form-group">
                        <label class="font-weight-bold">Purchase Order ID</label><code>*</code>
                        <select name="purchases" class="form-control" id="purchases">                                                        
                                <?php 
                                $count = "SELECT * FROM purchases WHERE status=1 ORDER BY id DESC"; // SQL to get records 
                                foreach ($link->query($count) as $row) { ?>
                            <option value=<?php echo $row['id'] ?>><?php echo $row['id'] ?></option>                                   
                                <?php } ?>
                        </select>
                    </div>                                                            

                    <input type="hidden" name="status" value="1">

                    <button type="submit" class="btn btn-success" name="register_btn"> + Create GRN</button>                        
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </form>
            </div>
        </div>        
    </div>

</body>
</html>

<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// Define variables and initialize with empty values
$quantity = $last_id = $customer = $product = $price = $stock = $total = $status = "";
$supplier_err = $product_err = $price_err = $stock_err = $total_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //process invoice    
    $invoice = $_POST["invoice"];        

    //process status
    $status = $_POST["status"];

    // Check input errors before inserting in database
    if(empty($name_err) && empty($price_err)){

        //TABLE1
        // Prepare an insert statement
        $sql = "INSERT INTO sale_returns (invoice, status) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii",$param_invoice, $param_status);
            
            // Set parameters
            $param_invoice = $invoice;                 
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
    
    // Close connection
    //mysqli_close($link);

        // Close statement
        //mysqli_stmt_close($stmt);


}//FORM SUBMITTED ENDS

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Sales Return</title>
    
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
                        <h2>Create Sales Return</h2>
                    </div>
                    <p>Please select the Invoice to create a new Sales Return.
                    (<code> * Compulsary fields</code> )</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">

                        <div class="form-group">
                            <label class="font-weight-bold">Invoice ID:</label><code> *</code>
                            <select name="invoice" class="form-control" id="invoice">                                                        
                                    <?php $count="SELECT * FROM invoices WHERE status=1 ORDER BY id DESC"; // SQL to get records 
                                    foreach ($link->query($count) as $row) {?>
                                <option value=<?php echo $row['id'] ?>><?php echo $row['id'] ?> </option>                                   
                                    <?php } ?>
                            </select>
                        </div>       

                        <input type="hidden" name="status" value="1">

                        <button type="submit" class="btn btn-success" name="register_btn"> + Create Sales Return</button>                        
                        <a href="index.php" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        
        <br/>

</body>
</html>
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
$last_id = $supplier = $status = "";
$supplier_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //process price    
    $supplier = $_POST["supplier"];       

    //process status
    $status = $_POST["status"];

    // Check input errors before inserting in database
    if(empty($supplier_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO invoices (customer, status) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_supplier, $param_status);
            
            // Set parameters
            $param_supplier = $supplier;             
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
    //mysqli_stmt_close($stmt);

    // Close connection
    //mysqli_close($link);

}//FORM SUBMITTED ENDS

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>

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
                    <p>Please select a customer to create a new Invoice.
                    (<code> * Compulsary fields</code> )</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
                        <div class="form-group">
                            <label class="font-weight-bold">Customer</label><code> *</code>
                            <select name="supplier" class="form-control" id="supplier">                                                        
                                    <?php $count="SELECT * from customers WHERE status=1 ORDER BY id DESC"; // SQL to get records 
                                    foreach ($link->query($count) as $row) {?>
                                <option value=<?php echo $row['id'] ?>><?php echo $row['firstname']." ".$row['lastname'] ?> </option>                                   
                                    <?php } ?>
                            </select>
                        </div>                                                               

                        <input type="hidden" name="status" value="1">

                        <button type="submit" class="btn btn-success" name="register_btn"> + Create Invoice</button>                        
                        <a href="index.php" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        
        <br/>

</body>
</html>
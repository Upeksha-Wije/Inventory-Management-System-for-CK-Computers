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
$product = $quantity = $status = "";
$quantity_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //process product    
    $product = test_input($_POST["product"]);     

    // Validate quantity
    $input_quantity = test_input($_POST["quantity"]);

    // Getting the values passed through the url
    $tinvoice = (int)trim($_GET["tinvoice"]);
    $tqty = (int)trim($_GET["tqty"]);

    // Finding the maximim allowable quantity
    $b=array($tinvoice,-$tqty);
    $max = array_sum($b);

    if(empty($input_quantity)){
        $quantity_err = "Please enter the received product quantity";     
    } elseif(!ctype_digit($input_quantity)){
        $quantity_err = 'Please enter a positive integer value';
        $quantity = $input_quantity;
    }elseif($input_quantity>$max){
        $quantity_err = 'Please enter a value that will not exceed your Invoice quantity. Maximum allowable value is '.$max.".";
        $quantity = $input_quantity;    
    } else{
        $quantity = $input_quantity;
    }    

    //process status
    $status = test_input($_POST["status"]);


    // Check input errors before inserting in database
    if(empty($quantity_err)){

        //INSERTO TO GRN DETAILS TABLE               
        $sql = "INSERT INTO sale_returns_details (sale_returns_id, product, quantity, status) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isii", $param_sale_returns_id, $param_product, $param_quantity, $param_status);
            
            // Set parameters
            $param_sale_returns_id = trim($_GET["id"]);
            $param_product = $_GET["pid"];
            
            $param_quantity = $quantity;
            $param_status = $status;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                //header("location: iview.php?id=$_GET[id]");
                //exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }        

        $sql = "SELECT * FROM stock WHERE pid=$product";
        $result = mysqli_query($link, $sql);
        $row0 = mysqli_fetch_array($result);
        $stock = $row0['quantity'];

        //UPDATE STOCK TABLE         
        $sql2 = "UPDATE `stock` SET `quantity`=? WHERE pid=?";        

        if($stmt2 = mysqli_prepare($link, $sql2)){   
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt2, "ii", $param_quantity, $param_pid);
            
            // Set parameters             
            $param_quantity = $stock+$quantity;
            $param_pid = $product; 

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt2)){
                // Records created successfully. Redirect to landing page
                header("location: iview.php?id=$_GET[id]");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }

        }        

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
    <title>Add Item</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include "../common/header.php"; ?>

</head>

<body>
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

        <body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
        
    <br/>
    <br/>
    <br/>

        <div class="container">
            <div class="row">
                <div class="mx-auto">
                    <div class="page-header">
                        <h2>Add Item</h2>
                    </div>
                    <p>Please fill this form and submit to add items to the GRN.
                    (<code> * Compulsary fields</code> )</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">

                        <div class="form-group">
                            <label class="font-weight-bold">Select Product</label>
                            <select name="product" class="form-control" id="product">        
                                    <?php 
                                    $pid = $_GET["pid"];

                                    $count="SELECT * FROM products WHERE id=$pid"; // SQL to get records 
                                    foreach (mysqli_query($link,$count) as $row) {?>
                                <option value=<?php echo $row['id']; ?>><?php echo $row['name'] ?> </option>                                   
                                    <?php } ?>

                                    <?php 
                                    /*
                                    $count2="SELECT * FROM products WHERE status=1"; // SQL to get records 
                                    foreach ($link->query($count2) as $row) {?>
                                <option value=<?php echo $row['id']; ?>><?php echo $row['name'] ?> </option>                                   
                                    <?php } 
                                    */
                                    ?>

                            </select>
                        </div>     

                        <div class="form-group">
                            <label class="font-weight-bold">Quantity<code>*</code></label>
                            <input type="text" name="quantity" class="form-control" value="<?php echo $quantity; ?>" placeholder="Enter the received item quantity">
                            <code><span class="help-block" style="background-color: white; padding: 5px; border-radius: 3px;"><?php echo $quantity_err;?></span></code>

                        </div>  

                        <input type="hidden" name="status" value="1">

                        <button type="submit" class="btn btn-success" name="register_btn"> + Add Item</button>                        
                        <a href="iview.php?id=<?php echo $_GET["id"]; ?>" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        
        <br/>

</body>
</html>
<?php

// Include the file containing the database connection code segment
require_once '../common/config.php';

// Include the file containing login function code segment
require_once '../common/functions.php';



// Define variables and initialize with empty values
$product = $quantity = $status = "";
$quantity_err = "";

// Function to sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Processing form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Process product    
    $product = test_input($_POST["product"]);

    // Validate quantity
    $input_quantity = test_input($_POST["quantity"]);
    if (empty($input_quantity)) {
        $quantity_err = "Please enter the required product quantity";
    } elseif (!ctype_digit($input_quantity)) {
        $quantity_err = 'Please enter a positive integer value';
        $quantity = $input_quantity;
    } else {
        $quantity = $input_quantity;
    }

    // Process status
    $status = test_input($_POST["status"]);

    // Check input errors before inserting in database
    if (empty($quantity_err)) {

        // SQL to insert into purchases_details table
        $sql2 = "INSERT INTO purchases_details (purchases_id, product, quantity, status, dlt) VALUES (?, ?, ?, ?, ?)";

        if ($stmt2 = mysqli_prepare($link, $sql2)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt2, "isiii", $param_purchases_id, $param_product, $param_quantity, $param_status, $param_dlt);

            // Set parameters
            $param_purchases_id = trim($_GET["id"]);
            $param_product = $product;
            $param_quantity = $quantity;
            $param_status = $status;
            $param_dlt = 0;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt2)) {
                // Records created successfully. Redirect to landing page
                header("location: iview.php?id=" . $_GET["id"]);
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt2);
        }
    }
    
    // Close connection
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Item</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
    <!-- Navbar -->
    <?php require_once '../common/navbar.php'; ?>
    <?php include "../common/header.php"; ?>
    
    <br/>
    <br/>
    <br/>

    <div class="container col-lg-5">
        <div class="row">
            <div class="mx-auto">
                <div class="page-header">
                    <h2>Add Item</h2>
                </div>
                <p>Please fill this form and submit to add items to the Purchase Order.
                (<code> * Compulsary fields</code>)</p>
                <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">

                    <div class="form-group">
                        <label class="font-weight-bold">Select Product</label>
                        <select name="product" class="form-control" id="product"> 

                            <?php 
                            $count = "SELECT * FROM products WHERE status=1 ORDER BY id DESC"; // SQL to get records
                            $result = mysqli_query($link, $count);
                            while ($row = mysqli_fetch_assoc($result)) { 
                                $selected = ($row['id'] == $product) ? 'selected' : ''; // Pre-select option if product is set
                                echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>     

                    <div class="form-group">
                        <label class="font-weight-bold">Quantity<code>*</code></label>
                        <input type="text" name="quantity" class="form-control" value="<?php echo $quantity; ?>" placeholder="Enter required product quantity">
                        <code><span class="help-block"><?php echo $quantity_err; ?></span></code>
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

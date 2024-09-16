<?php

// Include the file containing database connection code segment
require_once '../common/config.php';

// Include the file containing login function code segment
require_once '../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// Define variables and initialize with empty values
$product = $quantity = $buy = $status = "";
$quantity_err = $buy_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Process product
    $product = test_input($_POST["product"]);

    // Validate quantity
    $input_quantity = test_input($_POST["quantity"]);

    // Getting the values passed through the URL
    $po = trim($_GET["po"]);
    $tgrn = trim($_GET["tgrn"]);

    // Calculate the maximum allowable quantity
    $a = array((int)$po, -(int)$tgrn);  // Ensure numeric types
    $max = array_sum($a);

    if (empty($input_quantity)) {
        $quantity_err = "Please enter the received product quantity";
    } elseif (!ctype_digit($input_quantity)) {
        $quantity_err = 'Please enter a positive integer value';
        $quantity = $input_quantity;
    } elseif ($input_quantity > $max) {
        $quantity_err = 'Please enter a value that will not exceed your Purchase Order quantity. Maximum allowable value is ' . $max . ".";
        $quantity = $input_quantity;
    } else {
        $quantity = (int)$input_quantity;  // Convert to integer to avoid multiplication error
    }

    // Validate buying price
    $input_buy = test_input($_POST["buy"]);
    if (empty($input_buy)) {
        $buy_err = "Please enter the unit buying price of the product";
    } elseif (!ctype_digit($input_buy)) {
        $buy_err = 'Please enter a positive integer value';
        $buy = $input_buy;
    } else {
        $buy = (int)$input_buy;  // Convert to integer to ensure numeric operations
    }

    // Process status
    $status = test_input($_POST["status"]);

    // Check input errors before inserting into the database
    if (empty($quantity_err) && empty($buy_err)) {

        // Insert into GRN DETAILS TABLE
        $sql = "INSERT INTO grn_details (grn_id, product, quantity, buy, status) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isiii", $param_grn_id, $param_product, $param_quantity, $param_buy, $param_status);

            // Set parameters
            $param_grn_id = trim($_GET["id"]);
            $param_product = $_GET["pid"];
            $param_quantity = $quantity;
            $param_buy = $buy;
            $param_status = $status;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                // header("location: iview.php?id=$_GET[id]");
                // exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
 
        $sql = "SELECT * FROM stock WHERE pid=$product";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        $stock = $row['quantity'];

        // UPDATE STOCK TABLE
        $sql2 = "UPDATE `stock` SET `quantity`=? WHERE pid=?";

        if ($stmt2 = mysqli_prepare($link, $sql2)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt2, "ii", $param_quantity, $param_pid);

            // Set parameters
            $param_quantity = (int)$stock + (int)$quantity;  // Ensure numeric operations
            $param_pid = $product;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt2)) {
                // Records updated successfully. Redirect to landing page
                header("location: iview.php?id=$_GET[id]");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    } // ERR ENDS

    // Close connection
    // mysqli_close($link);

    // Close statement
    // mysqli_stmt_close($stmt);

} // FORM SUBMITTED ENDS
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

    <?php include "../common/header.php"; ?>
</head>

<body>
<!-- Navbar -->
<?php require_once '../common/navbar.php'; ?>

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
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name'] ?> </option>
                        <?php } ?>
                       


                    </select>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Quantity<code>*</code></label>
                    <input type="text" name="quantity" class="form-control" value="<?php echo $quantity; ?>"
                           placeholder="Enter received item quantity">
                    <code><span class="help-block"><?php echo $quantity_err;?></span></code>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Buying Price<code>*</code></label>
                    <input type="text" name="buy" class="form-control" value="<?php echo $buy; ?>"
                           placeholder="Enter unit buying price">
                    <code><span class="help-block"><?php echo $buy_err;?></span></code>
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

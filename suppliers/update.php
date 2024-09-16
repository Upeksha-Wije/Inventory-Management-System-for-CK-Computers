<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if(!isset($_SESSION['user'])) {
    header('location: ../login.php');
}
 
// Define variables and initialize with empty values
$title = $firstname = $lastname =  $address =$email = $mobile =$office_tel = $nic = $description = $status = "";
$firstname_err = $lastname_err = $address_err = $email_err = $mobile_err = $office_tel_err = $nic_err = $description_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }


      // Check existence of id parameter before processing further
      if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM suppliers WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value

                    $title = $row["title"]; 
                    $firstname = $row["firstname"]; 
                    $lastname = $row["lastname"]; 
                    $address = $row["address"];  
                    $email = $row["email"]; 
                    $mobile = $row["mobile"]; 
                    $office_tel = $row["office_tel"];  
                    $nic = $row["nic"]; 
                    $description = $row["description"]; 
                    $status = $row["status"];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //validate role
    $title = test_input($_POST["title"]);  

    // Validate name
    $input_firstname = test_input($_POST["firstname"]);
    if(empty($input_firstname)){
        $name_err = "Please enter a name";
    } elseif(!preg_match("/^[a-zA-Z ]*$/",$input_firstname)) {
        $firstname_err = 'Only letters and white space allowed';
        $firstname = $input_firstname;
    } else{
        $firstname = $input_firstname;
    }
    
    // Validate name2
    $input_lastname = test_input($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter a name";
    }  else{
        $lastname = $input_lastname;
    }

    // Validate address
    $input_address = test_input($_POST["address"]);
    if(empty($input_address)){
        $address_err = 'Please enter an address';     
    } else{
        $address = $input_address;
    }

    
    // Validate email address
    $input_email = test_input($_POST["email"]);
    if(!empty($input_email)){
       /* $email_err = 'Please enter an email address';     
    } else{ */
        $email = $input_email;
    }

    // Validate mobile no
    $input_mobile = test_input($_POST["mobile"]);
    if(empty($input_mobile)){
        $mobile_err = "Please enter a mobile no";     
    } elseif(!preg_match('/^[0-9]{10}$/', $input_mobile)){
        $mobile_err = 'Please enter a valid mobile no';
        $mobile = $input_mobile;
    } else{
        $mobile = $input_mobile;
    }

      // Validate office_tel
    $input_office_tel = test_input($_POST["office_tel"]);
    if(empty($input_office_tel)){
        $office_tel_err = "Please enter a mobile no";     
    } elseif(!preg_match('/^[0-9]{10}$/', $input_office_tel)){
        $office_tel_err = 'Please enter a valid mobile no';
        $office_tel = $input_office_tel;
    } else{
        $office_tel = $input_office_tel;
    }


    // Validate NIC
    $input_nic = test_input($_POST["nic"]);
    if(empty($input_nic)){
        $nic_err = "Please enter NIC no";  
    } elseif(!preg_match('/^[0-9]{12}$/', $input_nic)){
        $nic_err = 'Please enter a valid NIC no';
        $nic = $input_nic;
    } else{
        $nic = $input_nic;
    }      


    
    // Validate Description
    $input_description = test_input($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter Description";  
    } else{
        $description = $input_description;
    }      

    
    $status = $_POST["status"];
 

    // Check input errors before inserting in database
    if(empty($firstname_err) && empty($lastname_err) && empty($gender_err) && empty($address_err) && empty($mobile_err) && empty($office_tel_err)&& empty($nic_err)&& empty($description_err)){
        // Get the id of the record
        $id =  $_GET["id"];
        // Prepare an update statementto pdate the record
        $sql = "UPDATE `suppliers` SET `title`=?,`firstname`=?,`lastname`=?,`gender`=?,`address`=?,`email`=?,`mobile`=?,`office_tel`=?,`nic`=?,`description`=?,`status`=? WHERE id=$id;";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssi", $param_title, $param_firstname, $param_lastname, $param_gender, $param_address, $param_email, $param_mobile, $param_office_tel, $param_nic,$param_description, $param_status);
            
            // Set parameters
            $param_title = $title;
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_gender = $gender;
            $param_address = $address;
            $param_email = $email;
            $param_mobile = $mobile;
            $param_office_tel = $office_tel;
            $param_nic = $nic;
            $param_description = $description;
            $param_status = $status;
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    
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

        <div class="container col-lg-6">
            <div class="row">
                <div class="mx-auto">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <div>Please edit the input values and submit to update the record.</div>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
    
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Title</label>
                        </div>
                        <div class="form-group col-sm-6">
                            <select name="title" class="form-control" id="title" >
                                <option value="<?php echo $title; ?>"><?php echo $title;  ?></option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Ms">Ms</option>
                            </select>
                        </div>           
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">First Name<code>*</code></label>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>" placeholder="Enter First Name">
                            <code><span class="help-block"><?php echo $firstname_err;?></span></code>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Last Name<code>*</code></label>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>" placeholder="Enter Last Name">
                            <code><span class="help-block"><?php echo $lastname_err;?></span></code>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Address<code>*</code></label>
                        </div>
                        <div class="form-group col-sm-6">
                            <textarea name="address" class="form-control" placeholder="Enter Address"><?php echo $address; ?></textarea>
                            <code><span class="help-block"><?php echo $address_err;?></span></code>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Email</label>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter Email">
                            <code><span class="help-block"><?php echo $email_err;?></span></code>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Mobile no<code>*</code></label>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile no" value="<?php echo $mobile; ?>">
                            <code><span class="help-block"><?php echo $mobile_err;?></span></code>
                        </div>     
                    </div>


                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Office Tel</label>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="office_tel" class="form-control" placeholder="Office Tel no" value="<?php echo $office_tel;  ?>" />
                            <code><span class="help-block"><?php echo $office_tel_err;?></span></code>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">NIC<code>*</code></label>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="nic" class="form-control" value="<?php echo $nic; ?>" placeholder="Enter NIC no" />
                            <code><span class="help-block"><?php echo $nic_err;?></span></code>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Description</label>
                        </div>
                        <div class="form-group col-sm-6">
                            <textarea name="description" class="form-control" placeholder="Enter Description"><?php echo $description; ?></textarea>
                            <code><span class="help-block"><?php echo $description_err;?></span></code>
                        </div>
                    </div>

                        <input type="hidden" class="btn btn-success" name="status" value="1">
                        <input type="submit" class="btn btn-success" value="Update">
                        <a href="index.php" class="btn btn-danger">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
<br>    
</body>
</html>

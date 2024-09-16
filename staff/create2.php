<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
//require_once '../common/functions.php';

/*
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}
*/

$maxdate=date("Y-m-d",strtotime('- 18 years'));
$mindate=date("Y-m-d",strtotime('- 100 years'));

// Define variables and initialize with empty values
$title = $name = $name2 = $gender = $address = $email = $mobile = $dob = $nic = $user_type = $username = $password_1 = $password_2 = $status = "";
$name_err = $name2_err = $gender_err = $address_err = $email_err = $mobile_err = $dob_err = $nic_err = $salary_err = $user_type_err = $password_1_err = $password_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //validate role
    $title = test_input($_POST["title"]);  

    // Validate name
    $input_name = test_input($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name";
    } elseif(!preg_match("/^[a-zA-Z]*$/",$input_name)) {
        $name_err = 'Only letters and white space allowed';
        $name = $input_name;
    } else{
        $name = $input_name;
    }
    
    // Validate name2
    $input_name2 = test_input($_POST["name2"]);
    if(empty($input_name2)){
        $name2_err = "Please enter a name";
    } elseif(!preg_match("/^[a-zA-Z ]*$/",$input_name2)) {
        $name2_err = 'Only letters and white space allowed';
        $name2 = $input_name2;
    } else{
        $name2 = $input_name2;
    }

    //validate gender
    if (empty($_POST["gender"])) {
        $gender_err = "Gender is required";
      } else {
        $gender = test_input($_POST["gender"]); 
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

    // Validate mobile telephone no
    $input_mobile = test_input($_POST["mobile"]);
    if(empty($input_mobile)){
        $mobile_err = "Please enter a mobile no";     
    } elseif(!preg_match('/^[0-9]{10}$/', $input_mobile)){
        $mobile_err = 'Please enter a valid mobile no';
        $mobile = $input_mobile;
    } else{
        $mobile = $input_mobile;
    }

  
    // Validate dob to a compatible format with sql
    $rawdate = htmlentities($_POST['dob']);
    $dob = date('Y-m-d', strtotime($rawdate));

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

    //validate role
    $user_type = test_input($_POST["user_type"]);
    
    //validate username
    $username = test_input($_POST["username"]);

    //validate role
    $password_1 = test_input($_POST["password_1"]);

    //validate role
    $password_2 = test_input($_POST["password_2"]);

    if ($password_1 != $password_2) {
        $password_err = "The two passwords do not match";
    }

    $status = test_input($_POST["status"]);

    // Check input errors before inserting in database
    if(empty($name_err) && empty($name2_err) && empty($gender_err) && empty($address_err) && empty($postal_code_err) && empty($country_err) && empty($mobile_err) && empty($home_err) && empty($office_err) && empty($nic_err) &&  empty($salary_err) && empty($password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO staff (title, firstname, lastname, gender, address,email, mobile,dob, nic,user_type, username, password, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssi", $param_title, $param_name, $param_name2, $param_gender, $param_address, $param_email, $param_mobile, $param_dob, $param_nic,$param_user_type, $param_username, $param_password, $param_status);
            
            // Set parameters
            $param_title = $title;
            $param_name = $name;
            $param_name2 = $name2;
            $param_gender = $gender;
            $param_address = $address;
            $param_email = $email;
            $param_mobile = $mobile;
            $param_dob = $dob;
            $param_nic = $nic;
            $param_user_type = $user_type;
            $param_username = $username;
            $param_password = md5($password_1); //encrypt the password before saving in DB
            $param_status = $status;
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                //header("location: index.php");
                //exit();

                $showModal = "true";

                			
                if(!empty($showModal)) {
                    // CALL MODAL HERE
                    echo '<script type="text/javascript">
                        $(document).ready(function(){
                            $("#myModal").modal("show");
                        });
                    </script>';
                } 
            

            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    <?php include "../common/header.php"; ?>
   
    <style>
    
	.modal-confirm {		
		color: #636363;
		width: 325px;
		margin: 30px auto;
	}
	.modal-confirm .modal-content {
		padding: 20px;
		border-radius: 5px;
		border: none;
	}
	.modal-confirm .modal-header {
		border-bottom: none;   
        position: relative;
	}
	.modal-confirm h4 {
		text-align: center;
		font-size: 26px;
		margin: 30px 0 -15px;
	}
	.modal-confirm .form-control, .modal-confirm .btn {
		min-height: 40px;
		border-radius: 3px; 
	}
	.modal-confirm .close {
        position: absolute;
		top: -5px;
		right: -5px;
	}	
	.modal-confirm .modal-footer {
		border: none;
		text-align: center;
		border-radius: 5px;
		font-size: 13px;
	}	
	.modal-confirm .icon-box {
		color: #fff;		
		position: absolute;
		margin: 0 auto;
		left: 0;
		right: 0;
		top: -70px;
		width: 95px;
		height: 95px;
		border-radius: 50%;
		z-index: 9;
		background: #82ce34;
		padding: 15px;
		text-align: center;
		box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
	}
	.modal-confirm .icon-box i {
		font-size: 58px;
		position: relative;
		top: 3px;
	}
	.modal-confirm.modal-dialog {
		margin-top: 80px;
	}
    .modal-confirm .btn {
        color: #fff;
        border-radius: 4px;
		background: #82ce34;
		text-decoration: none;
		transition: all 0.4s;
        line-height: normal;
        border: none;
    }
	.modal-confirm .btn:hover, .modal-confirm .btn:focus {
		background: #6fb32b;
		outline: none;
	}
	.trigger-btn {
		display: inline-block;
		margin: 100px auto;
	}
</style>
</head>

<body>

<?php require_once '../common/navbar.php'; ?>
   
<body style="background-color:#e5ccff">

        <div class="container mt-3">
            <div class="row">
            <div class="col-lg-3"></div>

            <div class="col-lg-6">
                <div class="mx-auto">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    
                    Please fill this form and submit to add staff record to the database.
                    (<code> * Compulsary fields</code> )
                
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Title
                        </div>
                        
                        <div class="form-group col-sm-6">
                            <select name="title" class="form-control" id="title" >
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Ms">Ms</option>
                            </select>
                        </div>  
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">First Name<code>*</code>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" placeholder="Enter First Name">
                            <code><span class="help-block"><?php echo $name_err;?></span></code>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Last Name<code>*</code>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="name2" class="form-control" value="<?php echo $name2; ?>" placeholder="Enter Last Name">
                            <code><span class="help-block"><?php echo $name2_err;?></span></code>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Gender :<code>*</code> &nbsp;
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male"> Male &nbsp;
                            <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female"> Female &nbsp;
                            <code><span class="help-block"><?php echo $gender_err;?></span></code>
                        </div>   
                    </div>    

                    <div class="row">                                                                         
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Address<code>*</code>
                        </div>
                        <div class="form-group col-sm-6">
                            <textarea name="address" class="form-control" placeholder="Enter Address"><?php echo $address; ?></textarea>
                            <code><span class="help-block"><?php echo $address_err;?></span></code>
                        </div>
                    </div>  

                    <div class="row">                                             
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Email
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter Email">
                            <code><span class="help-block"><?php echo $email_err;?></span></code>
                        </div>
                    </div>   

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Mobile no<code>*</code>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile no" value="<?php echo $mobile; ?>">
                            <code><span class="help-block"><?php echo $mobile_err;?></span></code>
                        </div>
                    </div>  

 
                    <div class="row">                   
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">DOB
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="date" name="dob" class="form-control" min="<?php echo $mindate; ?>" max="<?php echo $maxdate; ?>" value="<?php echo $dob; ?>" />
                            <code><span class="help-block"><?php echo $dob_err;?></span></code>
                        </div>
                    </div>   

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">NIC <code>*</code>
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="nic" class="form-control" value="<?php echo $nic; ?>" placeholder="Enter NIC no" />
                            <code><span class="help-block"><?php echo $nic_err;?></span></code>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">User type
                        </div>
                        <div class="form-group col-sm-6">
                            <select name="user_type" class="form-control" id="user_type" >
                                <option value="<?php echo $user_type; ?>"><?php echo $user_type; ?></option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>  
                    </div>  

                    <div class="row">  
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Username
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                        </div>
                    </div>  

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Password
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="password" name="password_1" class="form-control" value="<?php echo $password_1; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <code><?php echo $password_err; ?></code>
                        <div class="form-group col-sm-6">
                            <label class="font-weight-bold">Confirm password
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="password" name="password_2" class="form-control" value="<?php echo $password_2; ?>">
                            <input type="hidden" name="status" value="1">
                        </div>     
                    </div>   

                    <div class="row">
                        <div class="form-group col-sm-6">
                           
                            <button type="submit" class="btn btn-primary " name="register_btn">  Create user</button>  
                        </div>

                        <div class="form-group col-sm-6">                      
                            <a href="../common/home.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>

                    </form>
                </div>
            </div>
            </div>        
        </div>

        <br/>

        <?php include "successalert.php"; ?>
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

        
</body>
</html>
<?php 
	session_start();

	// variable declaration
	$username = "";
	$password_1 = "";
	$password_2 = "";
	$errors   = array(); 

	// call the login() function if register_btn is clicked
	if (isset($_POST['login_btn'])) {
		login();
	}

	// call the logout function if logout buttonn is clicked	
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['user']);
		header("location: ../login.php");//Redirected to the login page
	}

    //some parts are missing here

    // LOGIN USER
	function login(){
		global $link, $username, $errors, $password;

		// grap form values
		$username = e($_POST['username']);
		$password = e($_POST['password']);

		// make sure form is filled properly. If errors are found, they are stored in $errors varaible
		if (empty($username)) {
			array_push($errors, "* Please enter username");
		}
		if (empty($password)) {
			array_push($errors, "* Please enter your password");
		}

		// attempt login if no errors on form
		if (count($errors) == 0) {
			$password = md5($password);// password is encrpted using md5
			$query = "SELECT * FROM staff WHERE username='$username' AND password='$password' AND status='1' LIMIT 1";
			$results = mysqli_query($link, $query);

			if (mysqli_num_rows($results)== 1) { // user found
				// check if user is admin or user
				$logged_in_user = mysqli_fetch_assoc($results);
				if ($logged_in_user['user_type'] == 'admin') {
					//if the logged is user has admin role
					$_SESSION['user'] = $logged_in_user['username'];
					$_SESSION['user_type'] = $logged_in_user['user_type'];
					$_SESSION['success']  = "Login Successful as ";
					header('location: common/home.php');//directed to admin home page		  
				}else{ 		
					//if the logged is user has user role
					$_SESSION['user'] = $logged_in_user['username'];
					$_SESSION['user_type'] = $logged_in_user['user_type'];
					$_SESSION['success']  = "Login Succesful as ";
					header('location: common/home.php');//directed to user home page
				}
			}else {
				array_push($errors, "Invalid username or password");// error message is saved to the $errors varable
			}
		}
	}

	//Check if the logged in user has the role type as user
	function isLoggedIn()
	{
		if(isset($_SESSION['user'])) {
			return true;
		}
		else{
			return false;
		}
	}

	//Check if the logged in user has the role type as SUPER admin
	/*function isSAdmin()
	{
		if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' && $_SESSION['user']['id'] == '0' ) {
			return true;
		}else{
			return false;
		}
	}*/	

	//Check if the logged in user has the role type as admin
	function isAdmin()
	{
		if ($_SESSION['user_type'] == 'admin') {
			return true;
		}else{
			return false;
		}
	}

	// escape string
	function e($val){
		global $link;
		return mysqli_real_escape_string($link, trim($val));
	}

	//If errors are found, they are displayed to the sytem user
	function display_error() {
		global $errors;
		if (count($errors) > 0){
			echo '<div class="Oops! Something went wrong. Please try again later.">';
				foreach ($errors as $error){
					echo $error .'<br>';
				}
			echo '</div>';
		}
	}

?>
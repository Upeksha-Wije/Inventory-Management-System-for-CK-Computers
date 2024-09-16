<?php
// call the logout function if logout buttonn is clicked	
if(isset($_GET['id'])){
	session_destroy();
    unset($_SESSION['user']);
    header("Location: login.php");
}

?>
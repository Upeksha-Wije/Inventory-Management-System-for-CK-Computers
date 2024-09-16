<?php 

// Include the file containing database connection code segement
require_once 'common/config.php';

// Include the file containing login function code segement
require_once 'common/functions.php';

?>

<!doctype html>
<html lang="en">

  <!-- This is login page -->
  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.101.0">
    <title>CK Computers</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">

    <!--Login CDN link-->
    <!-- CSS only -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!--Bootstrap Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Domine:wght@600&display=swap" rel="stylesheet">

    <!--Login Icon CDN-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    

    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vollkorn:ital,wght@1,500&display=swap" rel="stylesheet">
  
    <!-- Login Icon-->
    <link rel="icon" type="image/png" href="images/Logo1.png">

  </head>


  <body style="background-image: url(images/bg1.jpg); background-size: cover; background-repeat:no-repeat;">
    
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5">
      <form method="post" action="">
      
      
        <div class="card text-center" >
          <div class="card-header ">
          <h6 class ="mt-2"><i>Web Based Inventory Management System For</i></h6>
            <h1 class="h1 mb-4 mt-3 fw-normal"><i class="CK_color mr-2" style="color:red; font-family: 'Vollkorn', serif;">CK </i> Computers</h1>
          </div>
            <div class="card-body">
              <!-- <img class="mb-4" src="images/login.icon.jpg" alt="Icon" width="72" height="57"> -->
              <i class=" h1 mb-5 bi bi-person-circle"></i><br>
              <h6 class="h6 mt-3 mb-3 fw-normal">  Please login </h6>

                <?php 
                    if(!empty($login_err)){
                        echo '<div class="alert alert-danger">' . $login_err . '</div>';
                    }        
                    ?>

                <div class="form-row">
                <!--<label for="floatingInput">username</label>-->
                  <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Enter username">
                 
                  
                </div>

                <div class="form-row mt-2">
                <!--<label for="floatingPassword">Password</label>-->
                  <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Enter Password">
                  
                  
                </div>

                  <code>
                    <div class="alert-danger">
                    <?php 
                      if (isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                      }
                      echo display_error();
                    ?>
                    </div>
                  </code>
              

            <input type="checkbox" onclick="myFunction()"> Show Password
              <script>
              function myFunction() {
                var x = document.getElementById("floatingPassword");
                if (x.type === "password") {
                  x.type = "text";
                } else {
                  x.type = "password";
                }
              }
              </script>
            <br>
            <br>
        
            <button class="w-100 btn btn-lg btn-primary" type="submit" name="login_btn"> Login</button>

            
        </div>
        </div>
      </form>
    
      </div>
      </div>

    </div>
    </div>
</body>


</html>

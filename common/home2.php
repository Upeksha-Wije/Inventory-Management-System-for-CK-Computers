<?php 

// Include the file containing login function code segement
require_once '../common/functions.php';
require_once '../common/config.php';

isLoggedIn();

?>
<?php 
    if(isset($_POST['delete'])){
        $delete_id = $_POST['delete_id'];
        
        $sql = "UPDATE category SET status = 0 WHERE id= '$delete_id'";
        $results = mysqli_query($link, $sql);

    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>CK Computers Home Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include "../common/header.php"; ?>

  </head>

    <body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
        <style>
           /* body {
            background-color: #f8f8f8;
            background-image: url('../images/card6.jpg');
            background-repeat: no-repeat;
            background-size: auto;
            }*/


        </style>
        <!-- Navbar  -->
        <?php require_once '../common/navbar.php'; ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2"></div>
                    <!-- notification message -->
                    <?php if (isset($_SESSION['success'])) : ?>

                        <div class="col-lg-10 mt-3 alert alert-primary alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <?php 
                                echo $_SESSION['success']; 
                                echo $_SESSION['user'];
                                unset($_SESSION['success']);
                            ?>
                        </div> 

                    <!--<div class="col-lg-10 alert alert-success alert-dismissible mt-3"> 
                        <button type="button" class="close" data-dismiss="alert">&times;</button>     
                    </div>-->  
            
                <?php endif ?>
            </div>


       
        <!-- Page top Cards -->
        <div class="row align-items-center">
            <div class="col-lg-2 "></div>
            <div class="col-lg-2">
                <div class="card text-white   mb-3 mt-3 shadow-lg card-color1" >
                    <div class="card-header align-items-center  d-flex justify-content-center">
                        <a href= "../staff/index.php" class="btn btn-color1 btn-sm text-white"><b>Total Staff</b> </a>
                    </div>
                    <div class="card-body align-items-center  d-flex justify-content-center">
                        <p class="card-text">
                            <h3>
                                <?php
  
                                    // localhost is localhost
                                    // servername is root
                                    // password is empty
                                    // database name is bit
                                    $con = mysqli_connect("localhost","root","","bit");
                                      
                                        // SQL query to display row count
                                        // in building table
                                        $sql = "SELECT * from staff where status=1";
                                      
                                        if ($result = mysqli_query($con, $sql)) {
                                      
                                        // Return the number of rows in result set
                                        $rowcount = mysqli_num_rows( $result );
                                          
                                        // Display result
                                        printf(" %d\n", $rowcount);
                                    }
                                      
                                    // Close the connection
                                    //mysqli_close($con);
                                      
                                                                
                                ?>    
                                <!--<i class="bi bi-currency-dollar"></i>-->
                                <i class="bi bi-person-lines-fill"></i>
                            </h3>
                        </p>
                    </div>
                </div>
            </div>
            
                
                <div class="col-lg-2">
                    <div class="card text-white card-color2 mb-3 mt-3 shadow-lg" >
                        <div class="card-header align-items-center  d-flex justify-content-center">
                            <a href= "../purchases/index.php" class="btn btn-color2 btn-sm text-white"><b>Total Prchase Orders </b></a>
                        </div>
                        <div class="card-body align-items-center  d-flex justify-content-center">
                            <p class="card-text">
                            <h3>
                            <?php 
                                        
                                        // SQL query to display row count
                                        // in building table
                                        $sql = "SELECT * from purchases where status=1";
                                        
                                        if ($result = mysqli_query($con, $sql)) {
                                        
                                        // Return the number of rows in result set
                                        $rowcount = mysqli_num_rows( $result );
                                            
                                        // Display result
                                        printf( $rowcount);
                                    }
                                   
                                ?> 
                              <i class="bi bi-graph-up"></i> </h3></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card text-white card-color3 mb-3 mt-3 shadow-lg" >
                        <div class="card-header align-items-center  d-flex justify-content-center">
                            <a href= "../Customers/index.php" class="btn btn-color3 btn-sm text-white"><b>Total Customers</b></a>
                        </div>
                        <div class="card-body align-items-center  d-flex justify-content-center">
                            <p class="card-text"></p>
                                <h3>
                                <?php
  
                                            // SQL query to display row count
                                            // in building table
                                            $sql = "SELECT * from customers where status=1";
                                            
                                            if ($result = mysqli_query($con, $sql)) {
                                            
                                            // Return the number of rows in result set
                                            $rowcount = mysqli_num_rows( $result );
                                                
                                            // Display result
                                            printf($rowcount);
                                        }
                                          
                                ?>    
                                
                            <i class="bi bi-person-lines-fill"></i></h3></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card text-white card-color4 mb-3 mt-3 shadow" >
                        <div class="card-header align-items-center  d-flex justify-content-center">
                            <a href= "../suppliers/index.php" class="btn btn-color4 btn-sm text-white"><b>Total Suppliers</b></a>
                        </div>
                        <div class="card-body align-items-center  d-flex justify-content-center">
                            <p class="card-text">
                                <h3>
                                <?php
                                        
                                        // SQL query to display row count
                                        // in building table
                                        $sql = "SELECT * from suppliers where status=1";
                                        
                                        if ($result = mysqli_query($con, $sql)) {
                                        
                                        // Return the number of rows in result set
                                        $rowcount = mysqli_num_rows( $result );
                                            
                                        // Display result
                                        printf( $rowcount);
                                    }
                                   
                                
                                    // Close the connection
                                    mysqli_close($con);
                                        
                                                            
                                ?>   
                                
                            
                            
                            
                            <i class="bi bi-person-lines-fill"></i></h3></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="card text-white bg-dark mb-3 mt-3 shadow-lg card-color5 " >
                        <div class="card-header align-items-center  d-flex justify-content-center ">
                            <?php $mydate=getdate(date("U"));
                                echo "<h5>$mydate[weekday]</h5>". "<br>";
                            ?> 
                        </div>
                        <div class="card-body align-items-center  d-flex justify-content-center shadow-lg text-white">
                            <p class="card-text">
                                <?php
                                $date=date("M d, Y ");
                                // Prints the month,date
                                    echo "<h4>$date</h4>";

                                    /*date_default_timezone_set('Asia/colombo');
                                $date = date('h:i:s');
                                echo "$date"."<br>";*/
                                    
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
        </div>
    
        <div class="row">
            <div class="col-lg-2"></div>
                <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card " style="width: 900px; border:1px solid #d2d2d2; box-shadow:0px 4px 20px 0 #d2d2d2;">
                        <div class="card-body justify-content-center mt-2 text-center">
                            <h5 class="card-title">Best Selling Products</h5>
                            <?php  include "../common/chart3_pie1.php"; ?>
                        </div>
                    </div>
                </div>   
                </div>
        </div>
    </div>


    
<!-- JavaScript Bundle with Popper -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
             
        <br>
        <div class="row">
            <div class="col-lg-3 mb-3"></div>
                <div class="card mt-4 " style="width:780px; height:500; border:1px; box-shadow:0px 2px 10px 0 ">
                    <div class="chart-body">
                        <h5 class="chart-title  justify-content-center mt-3 text-center">Daily Sales By Date</h5>
                            <div>
                                <?php  include "../common/chart1.php"; ?>
                            </div>
                        
                    </div>
                </div>
        </div>
     
            
        </div>
        </div>
</body>
    
    
</html>

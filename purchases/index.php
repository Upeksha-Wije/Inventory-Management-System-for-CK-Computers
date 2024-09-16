<?php 

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';
   
//check if the user is logged in
if(!isset($_SESSION['user'])) {
    header('location: ../login.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
    
	.modal-confirm {		
		color: #636363;
		width: 400px;
		margin: 30px auto;
	}
	.modal-confirm .modal-content {
		padding: 20px;
		border-radius: 5px;
		border: none;
        text-align: center;
		font-size: 14px;
	}
	.modal-confirm .modal-header {
		border-bottom: none;   
        position: relative;
	}
	.modal-confirm h4 {
		text-align: center;
		font-size: 26px;
		margin: 30px 0 -10px;
	}
	.modal-confirm .close {
        position: absolute;
		top: -5px;
		right: -2px;
	}
	.modal-confirm .modal-body {
		color: #999;
	}
	.modal-confirm .modal-footer {
		border: none;
		text-align: center;		
		border-radius: 5px;
		font-size: 13px;
		padding: 10px 15px 25px;
	}
	.modal-confirm .modal-footer a {
		color: #999;
	}		
	.modal-confirm .icon-box {
		width: 80px;
		height: 80px;
		margin: 0 auto;
		border-radius: 50%;
		z-index: 9;
		text-align: center;
		border: 3px solid #f15e5e;
	}
	.modal-confirm .icon-box i {
		color: #f15e5e;
		font-size: 46px;
		display: inline-block;
		margin-top: 13px;
	}
    .modal-confirm .btn {
        color: #fff;
        border-radius: 4px;
		background: #60c7c1;
		text-decoration: none;
		transition: all 0.4s;
        line-height: normal;
		min-width: 120px;
        border: none;
		min-height: 40px;
		border-radius: 3px;
		margin: 0 5px;
		outline: none !important;
    }
	.modal-confirm .btn-info {
        background: #c1c1c1;
    }
    .modal-confirm .btn-info:hover, .modal-confirm .btn-info:focus {
        background: #a8a8a8;
    }
    .modal-confirm .btn-danger {
        background: #f15e5e;
    }
    .modal-confirm .btn-danger:hover, .modal-confirm .btn-danger:focus {
        background: #ee3535;
    }
	.trigger-btn {
		display: inline-block;
		margin: 100px auto;
	}
</style>


        <script>
                            function myFunction() {
                              var input, filter, table, tr, td, i;
                              input = document.getElementById("myInput");
                              filter = input.value.toUpperCase();
                              table = document.getElementById("myTable");
                              tr = table.getElementsByTagName("tr");
                              for (i = 0; i < tr.length; i++) {
                                td = tr[i].getElementsByTagName("td")[3];
                                if (td) {
                                  if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                    tr[i].style.display = "";
                                  } else {
                                    tr[i].style.display = "none";
                                  }
                                }       
                              }
                            }
                            </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
    <?php include "../common/header.php"; ?>

</head>

<body style="background-image: url(../images/BG100.jpg); background-size: cover; background-repeat:no-repeat;"></body>
    
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>
        

      <br/>
      <br/>
      <br/>
        <div class="container-fluid">
            <div class="row">
            <div class="col-lg-2"></div>
                <div class="col-9 mx-auto">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Purchase Order Management</h2>
                        <?php
                            if ($_SESSION['user_type']=='admin') {
                                echo '<a href="dindex.php" class="btn btn-info pull-right"><i class="fa fa-trash-o"></i>&nbsp; Deleted List</a>';
                            }                       
                        ?>             

                    </div>
                   
                    <a href="create.php" class="btn btn-primary pull-left"><i class="fa fa-cart-plus"></i>&nbsp; Add New Purchase Order</a>

                    <br/>
                    <br/>
 

                    <?php           
                    // Attempt select query execution
                    $sql = "SELECT * FROM purchases WHERE dlt=0 ORDER BY `id` DESC";
                    
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Purchase Order ID</th>";
                                        echo "<th>Date</th>";
                                        echo "<th>Supplier ID</th>";
                                        echo "<th>Supplier Name</th>";
                                        echo "<th>status</th>";      
                                        echo "<th>Action</th>";             
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['date'] . "</td>";
                                        echo "<td>" . $row['supplier'] . "</td>";

                                        echo "<td>";   
                                            $z=$row['supplier']; 
                                            $count="SELECT * FROM suppliers WHERE id=$z"; // SQL to get records 
                                            foreach ($link->query($count) as $row1) {
                                                echo $row1['firstname']." ".$row1['lastname'];  
                                            }       
                                        echo "</td>";
                                        
                                        echo "<td>";
                                            $status = $row['status'];
                                            if($status==1){
                                                echo "<a href='delete.php?id=". $row['id'] ."' class='btn btn-success btn-sm col-sm-12' role='button'>Active</a>";                                                
                                            }else{
                                                echo "<a href='delete2.php?id=". $row['id'] ."' class='btn btn-danger btn-sm col-sm-12' role='button'>Deactive</a>";
                                            }
                                        echo "</td>";

                                        echo "<td class='text-right'>";   
                                            if($status==1){
                                                echo "<a href='print.php?id=". $row['id'] ."' title='Print Purhase Order' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                                                echo "<a href='view.php?id=". $row['id'] ."' title='View Purhase Order' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                                                echo "<a href='delete3.php?id=". $row['id'] ."' title='Delete Purhase Order' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
                                            }else{
                                                echo "<a href='view.php?id=". $row['id'] ."' title='View Purhase Order' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                                                echo "<a href='delete3.php?id=". $row['id'] ."' title='Delete Purhase Order' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
                                            }      
                                        echo "</td>";

                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";


                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                    
                </div>
            </div>        
        </div>
        
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
        
        <script>
                    $(document).ready(function() {
                        $('#myTable').DataTable( {
                            "order": [[ 0, "desc" ]]
                        } );
                    } );
                    </script>  

    </body>
</html>
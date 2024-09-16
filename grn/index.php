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
    <meta charset="UTF-8">
    <title>GRN Management</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
       
        <?php include "../common/header.php"; ?>
       
                        <script>
                            function myFunction() {
                              var input, filter, table, tr, td, i;
                              input = document.getElementById("myInput");
                              filter = input.value.toUpperCase();
                              table = document.getElementById("myTable");
                              tr = table.getElementsByTagName("tr");
                              for (i = 0; i < tr.length; i++) {
                                td = tr[i].getElementsByTagName("td")[4];
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
</head>
<body style="background-image: url(../images/BG100.jpg); background-size: cover; background-repeat:no-repeat;">
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

        <style>
        body {
        background-color: #f8f8f8;
        }
    </style>
      
      <br/>
      <br/>
      <br/>
      <div class="row">
        <div class="col-lg-2"></div>

        <div class="container-fluid col-lg-9">
            <div class="row">
                <div class="col-sm-12 mx-auto">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">GRN Management</h2>
                            <?php
                                if (isAdmin()) {
                                    echo '<a href="dindex.php" class="btn btn-info pull-right"><i class="fa fa-trash-o"></i>&nbsp; Deleted List</a>';
                                }                       
                            ?>
                    </div>
                   
                    <a href="create.php" class="btn btn-primary pull-left"><i class="fa fa-cart-arrow-down"></i>&nbsp; Add New GRN</a>

                    

                    <br/>
                    <br/>

                    <script>
                    $(document).ready(function() {
                        $('#myTable').DataTable( {
                            "order": [[ 0, "desc" ]]
                        } );
                    } );
                    </script>               

                    <?php                 
                    // Attempt select query execution
                    $sql = "SELECT * FROM grn WHERE dlt=0 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>GRN ID</th>";
                                        echo "<th>Date</th>";
                                        echo "<th>Purchase Order ID</th>";
                                        echo "<th>Supplier ID</th>";
                                        echo "<th>Supplier Name</th>";
                                        echo "<th>Status</th>";      
                                        echo "<th class='text-right'>Action</th>";             
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        $grn = $row['id'];
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['date'] . "</td>";

                                        $sql2 = "SELECT * FROM grn WHERE id=$grn";
                                        $result2 = mysqli_query($link, $sql2);
                                        $row2 = mysqli_fetch_array($result2);
                                        $purchases = $row2['purchases'];

                                        $sql3 = "SELECT * FROM purchases WHERE id=$purchases";
                                        $result3 = mysqli_query($link, $sql3);
                                        $row3 = mysqli_fetch_array($result3);
                                        $supplier = $row3['supplier'];

                                        echo "<td>" . $purchases . "</td>";

                                        echo "<td>" . $supplier . "</td>";

                                        echo "<td>";   
                                        $count="SELECT * FROM suppliers WHERE id=$supplier"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                            echo $row1['firstname']." ".$row1['lastname'];  
                                        }       
                                        echo "</td>";

                                        echo "<td>";
                                            $status = $row['status'];
                                            if($status==1){
                                                echo "<a href='delete.php?id=". $row['id'] ."' class='btn btn-success btn-sm col-sm-12' role='button' data-toggle='tooltip' title='Deactivate'>Active</a>";                                                
                                            }else{
                                                echo "<a href='delete2.php?id=". $row['id'] ."' class='btn btn-danger btn-sm col-sm-12' role='button' data-toggle='tooltip' title='Activate'>Deactive</a>";
                                            }
                                        echo "</td>";                                        

                                        echo "<td class='text-right'>";   
                                            if($status==1){
                                                echo "<a href='print.php?id=". $row['id'] ."' title='Print GRN' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                                                echo "<a href='view.php?id=". $row['id'] ."' title='View GRN' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                                                echo "<a href='delete3.php?id=". $row['id'] ."' title='Delete GRN' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
                                            }else{
                                                echo "<a href='view.php?id=". $row['id'] ."' title='View GRN' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                                                echo "<a href='delete3.php?id=". $row['id'] ."' title='Delete GRN' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
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
 
</body>
</html>
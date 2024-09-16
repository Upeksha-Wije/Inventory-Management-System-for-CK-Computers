<?php 

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deleted Invoices Management</title>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
         
        
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
<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
        
    <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

      
      <br/>
      <br/>
      <br/>
        <div class="container-fluid col-lg-8">
            <div class="row">
                <div class="col-sm-12 mx-auto">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Deleted Invoices Management</h2>                        
                    </div>
                   
                    <a href="index.php" class="btn btn-primary pull-right">&nbsp;<i class="fa fa-arrow-circle-left"></i> Invoices Management</a>
                    

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
                    $sql = "SELECT * FROM invoices WHERE dlt=1 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Invoice ID</th>";
                                        echo "<th>Date</th>";
                                        echo "<th>Customer ID</th>";
                                        echo "<th>Customer Name</th>";
                                        echo "<th>Status</th>";      
                                        echo "<th>Action</th>";             
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['date'] . "</td>";
                                        echo "<td>" . $row['customer'] . "</td>";

                                        echo "<td>";   
                                        $z=$row['customer']; 
                                        $count="SELECT * FROM customers WHERE id=$z"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                            echo $row1['firstname']." ".$row1['lastname'];  
                                        }       
                                        echo "</td>";

                                        //display status
                                        $status = $row['status'];
                                        if($status==1){
                                            echo "<td>" . "<div class='badge badge-success'>Active</div>" . "</td>";/* display Active badge */
                                        }else{
                                            echo "<td>" . "<div class='badge badge-danger'>Inactive</div>" . "</td>";/* display Inactive badge */
                                        }

                                        echo "<td class='text-right'>";   
                                            if($status==1){
                                                echo "<a href='print.php?id=". $row['id'] ."' title='Print Invoice' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                                                echo "<a href='dview.php?id=". $row['id'] ."' title='View Invoice' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                                                echo "<a href='recover.php?id=". $row['id'] ."' title='Recover' data-toggle='tooltip'>&nbsp;<i class='fa fa-recycle'></i>                                                </a>";
                                            }else{
                                                echo "<a href='dview.php?id=". $row['id'] ."' title='View Invoice' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>";    
                                                echo "<a href='recover.php?id=". $row['id'] ."' class='btn btn-success' title='Recover' data-toggle='tooltip'><i class='fa fa-recycle'>&nbsp; Recover</a>";
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
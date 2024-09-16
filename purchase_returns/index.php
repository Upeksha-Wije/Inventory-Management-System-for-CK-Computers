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
    <title>Purchase Returns Management</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    

                        <script>
                            function myFunction() {
                              var input, filter, table, tr, td, i;
                              input = document.getElementById("myInput");
                              filter = input.value.toUpperCase();
                              table = document.getElementById("myTable");
                              tr = table.getElementsByTagName("tr");
                              for (i = 0; i < tr.length; i++) {
                                td = tr[i].getElementsByTagName("td")[5];
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

<body style="background-image: url(../images/BG100.jpg); background-size: cover; background-repeat:no-repeat;">
        
        <!-- Navbar -->
        <?php require_once '../common/navbar.php'; ?>

       
      
      <br/>
      <br/>
      <br/>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2"></div>
                <div class="col-lg-10">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Purchase Returns Management</h2>
                        <?php
                            if (isAdmin()) {
                                echo '<a href="dindex.php" class="btn btn-info pull-right"><i class="fa fa-trash-o"></i>&nbsp; Deleted List</a>';
                            }                       
                        ?>                   
                    </div>
                   
                    <a href="create.php" class="btn btn-primary pull-left"><i class="fa fa-opencart"></i>&nbsp; Add New Purchase Return</a>
                    
                    <br/>
                    <br/>
                    
                    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
                    <script>
                    $(document).ready(function() {
                        $('#myTable').DataTable( {
                            "order": [[ 0, "desc" ]]
                        } );
                    } );
                    </script>   

                    <?php
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM purchase_return WHERE dlt=0 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-2'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Purchase Return ID</th>";
                                        echo "<th>Date</th>";
                                        echo "<th>GRN ID</th>";
                                        echo "<th>Purchase Order ID</th>";
                                        echo "<th>Supplier ID</th>";
                                        echo "<th>Supplier Name</th>";
                                        echo "<th>Status</th>";      
                                        echo "<th>Action</th>";             
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['date'] . "</td>";

                                        $grn = $row["grn"];

                                        echo "<td>" . $grn . "</td>";

                                        $sql2 = "SELECT * FROM grn WHERE id=$grn";
                                        $result2 = mysqli_query($link, $sql2);
                                        $row2 = mysqli_fetch_array($result2);
                                        $purchases = $row2['purchases'];     
                                        
                                        echo "<td>" . $purchases . "</td>";
                        
                                        $sql3 = "SELECT * FROM purchases WHERE id=$purchases";
                                        $result3 = mysqli_query($link, $sql3);
                                        $row3 = mysqli_fetch_array($result3);
                                        $supplier = $row3['supplier'];

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
                                                echo "<a href='delete.php?id=". $row['id'] ."' class='btn btn-success btn-sm col-sm-12' role='button'>Active</a>";                                                
                                            }else{
                                                echo "<a href='delete2.php?id=". $row['id'] ."' class='btn btn-danger btn-sm col-sm-12' role='button'>Deactive</a>";
                                            }
                                        echo "</td>";

                                        echo "<td class='text-right'>";   
                                            if($status==1){
                                                echo "<a href='iview.php?id=". $row['id'] ."' title='Update Purchase Return' data-toggle='tooltip' target='_blank'><i class='fa fa-pencil'></i>&nbsp;</a>";
                                                echo "<a href='print.php?id=". $row['id'] ."' title='Print Purchase Return' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                                                echo "<a href='view.php?id=". $row['id'] ."' title='View Purchase Return' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                                                echo "<a href='delete3.php?id=". $row['id'] ."' title='Delete Purchase Return' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
                                            }else{
                                                echo "<a href='view.php?id=". $row['id'] ."' title='View Purchase Return' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                                                echo "<a href='delete3.php?id=". $row['id'] ."' title='Delete Purchase Return' data-toggle='tooltip'>&nbsp;<i class='fa fa-trash'></i></a>";
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
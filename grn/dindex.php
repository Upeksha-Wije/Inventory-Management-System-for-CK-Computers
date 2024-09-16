<?php 

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

// check is the logged in user is admin 
if (!isAdmin()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deleted GRN Management</title>
        
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
       
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <?php include "../common/header.php"; ?>
       
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
</head>

<body style="background-image: url(../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
        
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
                        <h2 class="pull-left">Deleted GRN Management</h2>
                    
                        <a href="index.php" class="btn btn-primary pull-right">&nbsp;<i class="fa fa-arrow-circle-left"></i> GRN Management</a>
                    </div>
                
                
                    
                    

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
                    $sql = "SELECT * FROM grn WHERE dlt=1 ORDER BY `id` DESC";
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

                                        //display status
                                        $status = $row['status'];
                                        if($status==1){
                                            echo "<td>" . "<div class='badge badge-success'>Active</div>" . "</td>";/* display Active badge */
                                        }else{
                                            echo "<td>" . "<div class='badge badge-danger'>Inactive</div>" . "</td>";/* display Inactive badge */
                                        }

                                        echo "<td class='text-right'>";   
                                            if($status==1){
                                                echo "<a href='print.php?id=". $row['id'] ."' title='Print GRN' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                                                echo "<a href='dview.php?id=". $row['id'] ."' title='View GRN' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                                                echo "<a href='recover.php?id=". $row['id'] ."' title='Recover' data-toggle='tooltip'>&nbsp;<i class='fa fa-recycle'></i>                                                </a>";
                                            }else{
                                                echo "<a href='dview.php?id=". $row['id'] ."' title='View GRN' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>";    
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
            </div>        
        </div>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
</body>
</html>
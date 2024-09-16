<?php 
// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}


// Define variables and initialize with empty values
$status = "";
$status_err="";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Products by Category</title>

        
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
                                td = tr[i].getElementsByTagName("td")[1];
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
        <?php include_once("../common/navbar.php"); ?>
        <?php include "../common/header.php"; ?>
      
        <br/>
        <br/>
        <br/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col mx-auto">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">View Products by Category <!--<a href='print_all.php' title='Print All Supplier Details' data-toggle='tooltip'><i class='fa fa-print'></i>--> </h2>
                    </div>
        <br/>
                <div class="row">
                    <div class="form-group col-sm-2"><label class="font-weight-bold">Category ID:</label></div>
                        <div class="form-group col-sm-1"><div class="form-control-static"><?php echo $_GET["id"]; ?></div></div>
                            <div class="form-group col-sm-2"><label class="font-weight-bold">Category Name:</label></div>
                                    <?php
                                        echo "<div class='form-group col-sm-3'>";   
                                        $z=$_GET["id"]; 
                                        $count="SELECT * FROM category WHERE id=$z"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                        echo $row1['category_name'];  
                                        }       
                                        echo "</div>";  
                                    ?>         
                    
                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM products WHERE category=$z ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Product ID</th>";
                                        echo "<th>Product Name</th>";
                                        echo "<th>Status</th>";     
                                        /*echo "<th>parent ID</th>";
                                        echo "<th>parent Category</th>";
                                        echo "<th>Category Name</th>";
                                        echo "<th>Action</th>";  */           
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                             
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";

                                        $status = $row['status'];
                                        if($status==1){
                                            echo "<td>" . "<div class='badge badge-success'>Active</div>" . "</td>";/* display Active badge */
                                        }else{
                                            echo "<td>" . "<div class='badge badge-danger'>Inactive</div>" . "</td>";/* display Inactive badge */
                                        }

                                       /* echo "<td>" . $row['parent_cat'] . "</td>";
                                        
                                        echo "<td>";   
                                        $z=$row['parent_cat']; 
                                        $count="SELECT category_name FROM categories WHERE id=$z"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                            echo $row1['category_name'];  
                                        }       
                                        echo "</td>";

                                        echo "<td>" . $row['category_name'] . "</td>";
                                        echo "<td>";
                                            //echo "<a href='print.php?id=". $row['cid'] ."' title='Print Record' data-toggle='tooltip'><i class='fa fa-print'></i>&nbsp;</a>";
                                            echo "<a href='view.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-edit'></i>&nbsp;</a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'>&nbsp;<i class='fa fa-remove'></i></a>";
                                        echo "</td>"; */
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";


                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<div class='lead'><em>No records were found.</em></div>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>

                    <div>

                    <a href="index.php" class="btn btn-primary">Back</a>
                    
                    <?php
                    if($status==1){
                        echo "<a href='print.php?id=$_GET[id]' title='Print Record' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                    }
                    ?>
                    </div>
                </div>
            </div>        
        </div>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
 
</body>
</html>
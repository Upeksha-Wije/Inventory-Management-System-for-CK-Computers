<?php 
// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

	if (!isAdmin()) {
		echo $_SESSION['msg'] = "You must log in first";
		header('location: ../login.php');
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Management</title>

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
                                td = tr[i].getElementsByTagName("td")[2];
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 mx-auto">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Stock Management <!--<a href='print_all.php' title='Print All Supplier Details' data-toggle='tooltip'><i class='fa fa-print'></i>--> </h2>
                    </div>
                    

                    <br/>
                    <br/>
                    <?php           
                    // Attempt select query execution
                    $sql = "SELECT * FROM stock WHERE 1 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Stock ID</th>";
                                        echo "<th>Product ID</th>";
                                        echo "<th>Product Name</th>";
                                        echo "<th>Stock Quantity</th>";
                                        echo "<th>Reorder Level</th>";
                                        echo "<th>Stock Level</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    $z=$row['pid'];
                                    $quantity=$row['quantity'];

                                    $count="SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                    foreach ($link->query($count) as $row1) {
                                        $reorder=$row1['reorder'];  
                                    }       

                                                                            
                                    if($quantity>$reorder){ 
                                        echo "<tr class='table-success'>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['pid'] . "</td>";

                                        echo "<td>";   
                                        $z=$row['pid']; 
                                        $count="SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                            echo $row1['name'];  
                                        }       
                                        echo "</td>";

                                        echo "<td>" . $quantity=$row['quantity'] . "</td>";

                                        echo "<td>";   
                                         $count="SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                            echo $reorder=$row1['reorder'];  
                                        }       
                                        echo "</td>";
                                        
                                    if($quantity>$reorder){ 
                                        echo "<td>" . "<div class='badge badge-pill badge-success'>Sufficient</div>" . "</td>";/* display Sufficient badge */
                                    }else{
                                        echo "<td>" . "<div class='badge badge-pill badge-danger'>Insufficient</div>" . "</td>"; /*display insufficient badge*/                                
                                    }

                                    echo "</tr>";


                                        
                                    }else{
                                        echo "<tr class='table-danger'>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['pid'] . "</td>";

                                        echo "<td>";   
                                        $z=$row['pid']; 
                                        $count="SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                            echo $row1['name'];  
                                        }       
                                        echo "</td>";

                                        echo "<td>" . $quantity=$row['quantity'] . "</td>";

                                        echo "<td>";   
                                         $count="SELECT * FROM products WHERE id=$z"; // SQL to get records 
                                        foreach ($link->query($count) as $row1) {
                                            echo $reorder=$row1['reorder'];  
                                        }       
                                        echo "</td>";

                                        echo "<td>" . "<div class='badge badge-pill badge-danger'>Insufficient</div>" . "</td>"; /*display insufficient badge*/                                

                                    echo "</tr>";
    
                                      
                                      
                                      
                                      
                                        //echo "<td>" . "<div class='badge badge-pill badge-danger'>Insufficient</div>" . "</td>"; /*display insufficient badge*/                                
                                    }

                                    
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
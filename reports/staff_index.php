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
    <title>Staff Reports</title>

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
                <div class="col-lg-2"></div>
                <div class="col-sm-10 mx-auto">
                    <div class="page-header clearfix">
                        <h2 class="pull-left mb-3">Staff Reports</h2>
                    </div>
                   
                    <a href="staff_print_all.php" class="btn btn-primary pull-left"><i class="fa fa-print"></i>&nbsp; Print All Staff Details</a>
                    

                    <br/>
                    <br/>
                  
                    <script>
                        $(document).ready( function () {
                            $('#myTable').DataTable();
                        } );
                    </script>

                    <?php
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM staff WHERE 1 ORDER BY `id` DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<div class='bg-white p-3'><table class='table table-bordered table-striped table-hover table-responsive-sm' id='myTable'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        //
                                        echo "<th>Staff ID</th>";                                        
                                        echo "<th>Title</th>";
                                        echo "<th>Staff Name</th>";
                                        //echo "<th>Last Name</th>";
                                        //echo "<th>Gender</th>";
                                        echo "<th>Address</th>";
                                        //echo "<th>Postal Code</th>";
                                        //echo "<th>Country</th>";
                                        //echo "<th>Email</th>";
                                        echo "<th>Mobile no</th>";
                                        /*echo "<th>Home Tel</th>";
                                        echo "<th>Office Tel</th>";
                                        echo "<th>DOB</th>";*/
                                        echo "<th>NIC</th>";
                                        /*echo "<th>Image</th>";
                                        echo "<th>Description</th>";
                                        echo "<th>Salary</th>";
                                        echo "<th>User Type</th>";
                                        echo "<th>User Name</th>";*/
                                        //echo "<th>Status</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        //echo "<td>" ."<img src='". $row['image'] . "'/>"."</td>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['title'] . "</td>";
                                        echo "<td>" . $row['firstname'] ." ". $row['lastname'] . "</td>";
                                        //echo "<td>" . $row['lastname'] . "</td>";
                                        //echo "<td>" . $row['gender'] . "</td>";
                                        echo "<td>" . $row['address'] . "</td>";
                                        //echo "<td>" . $row['postal_code'] . "</td>";
                                        //echo "<td>" . $row['country'] . "</td>";
                                        //echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['mobile'] . "</td>";
                                        //echo "<td>" . $row['home_tel'] . "</td>";
                                        //echo "<td>" . $row['office_tel'] . "</td>";
                                        //echo "<td>" . $row['dob'] . "</td>";
                                        echo "<td>" . $row['nic'] . "</td>";
                                        /*echo "<td>" . $row['image'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $row['salary'] . "</td>";
                                        echo "<td>" . $row['user_type'] . "</td>";
                                        echo "<td>" . $row['username'] . "</td>";*/

 
                                        echo "<td>";
                                            echo "<a href='staff_print.php?id=". $row['id'] ."' title='Print Record' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
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
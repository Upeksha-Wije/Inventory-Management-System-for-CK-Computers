<?php 

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';
   
//check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// To validate date selectors not to select dates later than today date
$maxdate=date("Y-m-d",strtotime('today'));

//To initialize the number of GRNs value
$number=0;

// When the search button is clicked
if(isset($_POST['se'])){
    $from = $_POST['from'];
    $to = $_POST['to'];


    /* Both the From and To date selected */
    if($_POST['from'] != "" && $_POST['to'] != "") {

    // Prepare a select statement
    $sql = "SELECT * FROM grn WHERE date between ? and ? AND status=1";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_from,$param_to);
        
        // Set parameters
        $param_from = $from;

        $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
        $param_to = $date;
    }


    /* Only the From date selected */
    }else if($_POST['from'] !="" && $_POST['to'] ==""){ 

    // Prepare a select statement
    $sql = "SELECT * FROM grn WHERE date between ? and ? AND status=1";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_from,$param_to);
        
        // Set parameters
        $param_from = $from;

        $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($from)));
        $param_to = $date;
    }    


    /* Only the To date selected */
    }else if($_POST['from'] =="" && $_POST['to'] !=""){ 

    // Prepare a select statement
    $sql = "SELECT * FROM grn WHERE date between 0 and ? AND status=1";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_to);
        
        // Set parameters
        $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
        $param_to = $date;
    }
    
    
    /* None of dates selected */
    } else{ 
        $stmt = "";
    }

}

if(isset($_POST['se'])&&$stmt!=""){
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        $number=mysqli_num_rows($result);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GRNs by Date Range Report</title>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>    
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

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
                        <h2 class="pull-left">GRNs by Date Range Report</h2>
                    </div>
                    <br/>
                        <div class="row">
                            <form class="form-inline mx-auto" action="#" method="post">
                                <label for="from">From :</label>     &nbsp;&nbsp;&nbsp;                 
                                <input type="date" name="from" class="form-control" onchange="getDateTo(this.value)" max="<?php echo $maxdate; ?>" value="<?php echo $from; ?>"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                <label for="to">To :</label>&nbsp;&nbsp;&nbsp; 
                                <span id="datet">                              
                                <input type="date" name="to" class="form-control" min="<?php echo $from; ?>" max="<?php echo $maxdate; ?>" value="<?php echo $to; ?>"/>&nbsp;&nbsp;&nbsp;
                                </span>
                                          
                                <input type="submit" name="se" value="search" class="btn btn-success"/>
                            </form>
                        </div>
                    
                    <br/>

                    <!--to hide the table-->
                    <?php if(isset($_POST['se'])){ ?>
                        
                        <div class="row">
                            <div class="col">From : <?php echo $_POST['from']; ?></div> 
                            <div class="col">To: <?php echo $_POST['to'] ?></div>
                            <div class="col">Number of GRNs : <?php echo $number ?></div>
                                                        
                    <?php } ?>                 
                        </div>


        <div class="container-fluid">

       
        
        <script>
        $(document).ready(function() {
            $('#myTable').DataTable( {
                "order": [[ 0, "desc" ]]
            } );
        } );
        </script>   

        <br/>     

        <?php
        if(isset($_POST['se'])&&$stmt!=""){
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) > 0){
                    echo '<a href="grn_date_range_print_all.php?from='.$from.'&to='.$to.'" class="btn btn-primary pull-left" target="_blank" style="margin-bottom: 10px;"><i class="fa fa-print"></i>&nbsp; Print Result</a>';

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

                            $status = $row['status'];

                            if($status==1){
                                echo "<td>" . "<div class='badge badge-success'>Active</div>" . "</td>";/* display Active badge */
                            }else{
                                echo "<td>" . "<div class='badge badge-danger'>Inactive</div>" . "</td>";/* display Inactive badge */
                            }

                            echo "<td class='text-right'>";   
                                    echo "<a href='grn_date_range_print.php?id=". $row['id'] ."' title='Print GRN' data-toggle='tooltip' target='_blank'><i class='fa fa-print'></i>&nbsp;</a>";
                                    //echo "<a href='grn_date_range_view.php?id=". $row['id'] ."' title='View GRN' data-toggle='tooltip' target='_blank'>&nbsp;<i class='fa fa-eye'></i>&nbsp;</a>";    
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";


                // Free result set
                mysqli_free_result($result);


                } else{
                    echo "<br/><div class='alert alert-danger'><em>No GRNs were found.</em></div>";
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

        }else{
            echo '<br/><div class="alert alert-danger text-center">Please Select Dates</div>';
        }

        ?>

        </div>

              
                </div>
            </div>        
        </div>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
 
</body>

    <script>
        // should select a To date greater than the From date
        function getDateTo(t){
            document.getElementById('datet').innerHTML='<input type="date" name="to" class="form-control" min="'+t+'" max="<?php echo $maxdate; ?>" value="<?php echo $to; ?>"/>&nbsp;&nbsp;&nbsp;';           
        }
    </script>

</html>
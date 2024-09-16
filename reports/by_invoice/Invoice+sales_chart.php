<?php 

// Include the file containing database connection code segement
require_once '../../common/config.php';

// Include the file containing login function code segement
require_once '../../common/functions.php';
   
//check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../../login.php');
}

// To validate date selectors not to select dates later than today date
$maxdate=date("Y-m-d",strtotime('today'));

//To initialize the number of Invoice values
$number=0;
$status=1;

// When the search button is clicked
if(isset($_POST['se'])){
    $from = $_POST['from'];
    $to = $_POST['to'];
    $status = $_POST['status'];

    ///// WHEN STATUS NOT SELECTED
    /* Both the From and To date selected */
    if($_POST['from'] != "" && $_POST['to'] != "" && $_POST['status'] == "") {

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE date between ? and ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_from,$param_to);
            
            // Set parameters
            $param_from = $from;
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;
        }


    /* Only the From date selected */
    }else if($_POST['from'] !="" && $_POST['to'] =="" && $_POST['status'] == ""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE date between ? and ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_from,$param_to);
            
            // Set parameters
            $param_from = $from;
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($from)));
            $param_to = $date;
        }    


    /* Only the To date selected */
    }else if($_POST['from'] =="" && $_POST['to'] !="" && $_POST['status'] == ""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE date between 0 and ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_to);
            
            // Set parameters
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;
        }

 
    ///// WHEN STATUS SELECTED
    /* Both the From and To date selected */    
    }else if($_POST['from'] != "" && $_POST['to'] != "" && $_POST['status'] !="") {

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE date between ? and ? AND status=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_from,$param_to,$param_status);
            
            // Set parameters
            $param_from = $from;
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;
            $param_status = $status; 
        }


    /* Only the From date selected */
    }else if($_POST['from'] !="" && $_POST['to'] =="" && $_POST['status'] !=""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE date between ? and ? AND status=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_from,$param_to,$param_status);
            
            // Set parameters
            $param_from = $from;

            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($from)));
            $param_to = $date;
            $param_status = $status; 
        }    


    /* Only the To date selected */
    }else if($_POST['from'] =="" && $_POST['to'] !="" && $_POST['status'] !=""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE date between 0 and ? AND status=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_to,$param_status);
            
            // Set parameters
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;
            $param_status = $status; 
        }
 
    
    }else if($_POST['from'] =="" && $_POST['to'] =="" && $_POST['status'] !=""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE status=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_status);
            
            // Set parameters
            $param_status = $status;
        }
 
    
    }else if($_POST['from'] =="" && $_POST['to'] =="" && $_POST['status'] ==""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_status);
            
            // Set parameters
            $param_status = 1;
        }
 
    
    }  else{
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
    <title>Purchase Orders Report</title>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <?php include "../../common/header.php"; ?>         
</head>

    <body style="background-image: url(../../images/Background/Copy-conL4.jpg); background-size: cover; background-repeat:no-repeat;">
        
    <!-- Navbar -->
        <?php require_once '../../common/navbar.php'; ?>

      <br/>
      <br/>
      <br/>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2"></div>
                <div class="col-sm-10 mx-auto">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Total Sales by Date Range Chart</h2>
                    </div>

                    <br/>
                    
                        <div class="row">
                            <form class="form-inline mx-auto" action="#" method="post">
                                <label for="from">From :</label>     &nbsp;&nbsp;&nbsp;                 
                                <input type="date" name="from" class="form-control" onchange="getDateTo(this.value)" max="<?php echo $maxdate; ?>" value="<?php echo $from; ?>"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                <label for="to">To :</label>&nbsp;&nbsp;&nbsp; 
                                <span id="datet">                              
                                <input type="date" name="to" class="form-control" min="<?php echo $from; ?>" max="<?php echo $maxdate; ?>" value="<?php echo $to; ?>"/>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </span>

                                <label for="status">Status :</label> &nbsp;&nbsp;&nbsp; 
                                <select name="status" class="form-control" id="status">
                                    <option value="<?php echo $status; ?>"><?php if($status==""){echo "All";}elseif($status==0){echo "Inactive";}elseif($status==1){echo "Active";} ?></option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>                                
                                    <option value="">All</option> 
                                </select> &nbsp;&nbsp;&nbsp;  
                                          
                                <input type="submit" name="se" value="search" class="btn btn-success"/>
                            </form>
                        </div>
                    
                    <br/>

                    <!--to hide the table-->
                    <?php if(isset($_POST['se'])){ ?>
                        
                        <div class="row">
                            <div class="col">From : <?php echo $_POST['from']; ?></div> 
                            <div class="col">To: <?php echo $_POST['to'] ?></div>
                            <div class="col">Number of Sales : <?php echo $number ?></div>
                                                        
                    <?php } ?>                 
                        </div>

        <div class="container-fluid">

        <?php 
            $con = new mysqli('localhost','root','','test');
            $query = $con->query("
                SELECT 
                MONTHNAME(created) as monthname,
                    SUM(amount) as amount
                FROM transactions
                GROUP BY monthname
            ");

            foreach($query as $data)
            {
                $month[] = $data['monthname'];
                $amount[] = $data['amount'];
            }

            ?>


            <div style="width: 500px;">
            <canvas id="myChart"></canvas>
            </div>
            
            <script>
            // === include 'setup' then 'config' above ===
            const labels = <?php echo json_encode($month) ?>;
            const data = {
                labels: labels,
                datasets: [{
                label: 'My First Dataset',
                data: <?php echo json_encode($amount) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                scales: {
                    y: {
                    beginAtZero: true
                    }
                }
                },
            };

            var myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
            </script>

</body>
</html> 
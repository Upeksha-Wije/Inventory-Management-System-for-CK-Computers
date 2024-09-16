<?php
//Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';
?>

<?php include "../common/header.php"; ?>

 <!-- Navbar -->  
 <?php require_once '../common/navbar.php'; ?> 
<?php 
    if(isset($_POST['delete'])){
        $delete_id = $_POST['delete_id'];
        
        $sql = "UPDATE Products SET status = 0 WHERE id= '$delete_id'";
        $results = mysqli_query($link, $sql);

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

    </head>
<body>

    <style>/*
        body {
        background-color: #f8f8f8;
        }*/
    </style>

    <body style="background-image: url(../images/BG100.jpg); background-size: cover; background-repeat:no-repeat;">

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-10">
                        <div class="mt-5 mb-3 clearfix">
                            <div class="row">
                            <h2 class="pull-left">Product Management</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-10"></div>
                            <a href="create.php" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Product</a>
                            </div>
                        </div>
                        <?php
    // Attempt select query execution
    $sql = "SELECT products.id, products.name, products.category, products.price, products.reorder, products.status, category.category_name
            FROM products
            LEFT JOIN category ON products.category = category.id";

    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo '<div class="bg-white p-1"><table class="table table-bordered table-striped" id="myTable">';
                echo "<thead>";
                    echo "<tr>";
                        echo "<th>Product ID</th>";
                        echo "<th>Product Name</th>";
                        echo "<th>Category ID</th>";
                        echo "<th>Category Name</th>";
                        echo "<th>Unit Selling Price</th>";
                        echo "<th>Reorder Level</th>";
                        echo "<th>Status</th>";
                        echo "<th>Action</th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while($row = mysqli_fetch_array($result)){
                    echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>" . $row['category_name'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['reorder'] . "</td>"; 
                        echo "<td>";
                            $status = $row['status'];
                            if($status==1){
                                echo "<a href='delete.php?id=". $row['id'] ."' class='btn btn-success btn-sm col-sm-12' role='button'>Active</a>";                                                
                            }else{
                                echo "<a href='delete2.php?id=". $row['id'] ."' class='btn btn-danger btn-sm col-sm-12' role='button'>Deactive</a>";
                            }
                        echo "</td>";   
                        echo "<td>";
                            echo '<a href="view.php?id='. $row['id'] .'" class=" icon" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?id='. $row['id'] .'" class=" icon" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?id='. $row['id'] .'" class=" icon" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo '<a href="print.php?id='. $row['id'] .'" class=" icon" title="print Record" data-toggle="tooltip"><span class="fa fa-print"></span></a>';
                        echo "</td>";
                    echo "</tr>";

                    // Modal HTML
                    ?>
                        <div id="myModal<?php echo $row['id'];?>" class="modal fade">
                            <div class="modal-dialog modal-confirm">
                                <form method="POST" action="">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="icon-box">
                                            <i class="material-icons">&#xE5CD;</i>
                                        </div>				
                                        <h4 class="modal-title">Are you sure?</h4>	
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Do you really want to delete these records? 
                                            
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="delete_id" id="delete_id" value="<?php echo $row['id'];?>">
                                        <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger" name="delete" >Delete</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>     
                    <?php
                }
                echo "</tbody>";                            
                echo "</table>";
                // Free result set
                mysqli_free_result($result);
            } else{
                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

    // Close connection
    mysqli_close($link);
    ?>

                    </div>       
            </div>
        </div>
    </div>
    
    <script>   $(document).ready( function () {
            $('#myTable').DataTable({
                order: [[0, 'desc']]
            });
            } );
        </script>

    <script type="text/javascript">
        <?php
        if ($_GET['success'] =='true'){
            echo '$(function() {
                $( "#myModal" ).dialog();
            });';
        }
        ?>
    </script>
     <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>

</body>
</html>
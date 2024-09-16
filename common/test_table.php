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
        
        $sql = "UPDATE category SET status = 0 WHERE id= '$delete_id'";
        $results = mysqli_query($link, $sql);

    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    </head>


<body style="background-image: url(../images/BG100.jpg); background-size: cover; background-repeat:no-repeat;">
    
    <div class="wrapper">
        <div class="container-fluid"></div>
            <div class="row">
            <div class="col-lg-3"></div>

                <div class="col-md-8">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Category Details</h2>
                        <a href="create.php" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Category</a>
                    </div>
                    
                    <?php
                    
                    // Attempt select query execution
                    $sql = "SELECT category.id,category.category_name,p_category.p_cat_id,p_category.parent_cat_name,category.status FROM category LEFT JOIN p_category ON category.p_cat_id=p_category.p_cat_id";
                    
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){ ?>

                            <div class="bg-white p-3"><table class="table table-bordered table-striped" id="myTable" >
                            <thead>
                                    <tr>
                                        <th>Parent ID</th>
                                        <th>Category ID</th>
                                        <th>Parent Category</th>
                                        <th>Category Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                            
                                   
                            </thead>
                                <tbody>
                                <?php
                                    while($row = mysqli_fetch_array($result)){ ?>
                                    <tr>
                                        <td> <?php echo  $row['p_cat_id'] ?></td>
                                        <td> <?php echo  $row['id']  ?></td>  
                                        <td> <?php echo  $row['parent_cat_name'] ?></td>
                                        <td> <?php echo  $row['category_name'] ?></td>

                                        <td>
                                        <?php $status = $row['status'];
                                        if($status==1){ ?>
                                            <a href='delete.php?id="<?php echo $row['id'] ?>"' class='btn btn-success btn-sm col-sm-12' role='button'>Active</a>                                             
                                        <?php }else{ ?>
                                            <a href='delete2.php?id="<?php echo $row['id'] ?>"' class='btn btn-danger btn-sm col-sm-12' role='button'>Deactive</a>
                                        <?php } ?>
                                        </td>


                                        <td>
                                        <a href="view.php?id='<?php echo $row['id']?>'" class=" icon" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                        <a href="update.php?id='<?php echo $row['id']?>'" class=" icon" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                                        <!--<a href="delete.php?id='<?php echo $row['id']?>'" class=" icon" title="delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span> </a> -->
                                        <a href="print.php?id='<?php echo $row['id']?>'" class=" icon" title="print Record" data-toggle="tooltip"><span class="fa fa-print"></span></a>

                                        </td>
                                    </tr>S

                           
                                <?php } ?>
                                </tbody>                          
                                </table>
                                // Free result set
                               <?php mysqli_free_result($result);
                            } else{ ?>
                            <div class="alert alert-danger"><em>No records were found.</em></div>
                            <?php }
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
    

    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>

         <script>   $(document).ready( function () {
            $('#myTable').DataTable();
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

</body>
</html>
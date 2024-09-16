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

$total = 0;

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM purchase_return WHERE id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
     
                $id = $row["id"];
                $date = $row["date"];
                $grn = $row["grn"];

                $sql2 = "SELECT * FROM grn WHERE id=$grn";
                $result2 = mysqli_query($link, $sql2);
                $row2 = mysqli_fetch_array($result2);
                $purchases = $row2['purchases'];                

                $sql3 = "SELECT * FROM purchases WHERE id=$purchases";
                $result3 = mysqli_query($link, $sql3);
                $row3 = mysqli_fetch_array($result3);
                $supplier = $row3['supplier'];

                $count="SELECT * FROM suppliers WHERE id=$supplier"; // SQL to get supplier name 
                foreach ($link->query($count) as $row0) {
                    $supplier_name = $row0['firstname']." ".$row0['lastname'];  
                }       

                $status = $row["status"];

                $sql2 = "SELECT * FROM purchase_return_details WHERE purchase_return_id=$id && status=1 ORDER BY `id` DESC";

                //use fpdf to print
                require('../common/fpdf/fpdf.php');

                class PDF extends FPDF
                {
                // Page header
                function Header()
                {
                    {
                        $this->SetFont('Arial','B',14);
                        $this->cell(0,15,'CK Computers',0,0,'C');
                        $this->Ln(7);
                        $this->cell(0,15,'Sales & Services',0,0,'C');
                        $this->Ln(7);
                        $this->cell(0,15,'All Types Of Computer, Laptop & Accessorie',0,0,'C');
                        $this->Ln(7);
                        $this->cell(0,15,'Oruthota Road, Gampaha',0,0,'C');
                        $this->Ln(7);
                        $this->cell(0,15,'0767729250 / 0778565800',0,1,'C');
                        $this->Ln(7);
    
                    }
    

                    $this->SetFont('Arial','BU',16);
                    $this->cell(0,15,'Purchase Return',0,1,'L');
                }

                // Page footer
                function Footer()
                {
                    // Position at 1.5 cm from bottom
                    $this->SetY(-15);
                    // Arial italic 8
                    $this->SetFont('Arial','I',8);
                    // Page number
                    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
                }
                }

                $pdf = new PDF('L','mm','A4'); 
                $pdf->AliasNbPages();
                $pdf->AddPage();

                $pdf->SetFont('Arial','B',14);
                $pdf->cell(60,15,'Purchase Return ID :',0,0,'L');
                $pdf->SetFont('Arial','',14);
                $pdf->cell(2,15,$id,0,0,'R');
                $pdf->SetFont('Arial','B',14);
                $pdf->cell(108,15,'Date :',0,0,'R');
                $pdf->SetFont('Arial','',14);
                $pdf->cell(60,15,$date,0,0,'R');
                $pdf->Ln(7);
                $pdf->SetFont('Arial','B',14);
                $pdf->cell(60,15,'Supplier ID :',0,0,'L');
                $pdf->SetFont('Arial','',14);
                $pdf->cell(2,15,$supplier,0,0,'R');
                $pdf->SetFont('Arial','B',14);
                $pdf->cell(108,15,'Supplier Name :',0,0,'R');
                $pdf->SetFont('Arial','',14);
                $pdf->cell(60,15,$supplier_name,0,0,'R');
                $pdf->Ln(7);
                $pdf->SetFont('Arial','B',14);
                $pdf->cell(60,15,'GRN ID :',0,0,'L');
                $pdf->SetFont('Arial','',14);
                $pdf->cell(2,15,$grn,0,0,'R');
                $pdf->SetFont('Arial','B',14);
                $pdf->cell(108,15,'Purchase Order ID :',0,0,'R');
                $pdf->SetFont('Arial','',14);
                $pdf->cell(60,15,$purchases,0,1,'C');
                $pdf->Ln(5);

                $pdf->SetFont('Arial','B',14);
                $width_cell=array(30,30,82,50,35,50);//277 mm total
                $pdf->SetFillColor(193,229,252); // Background color of header 
                
                // Header starts /// 
                $pdf->Cell($width_cell[0],10,'Item ID',1,0,"R",true); // First header column 
                $pdf->Cell($width_cell[1],10,'Product ID',1,0,"R",true); // Second header column 
                $pdf->Cell($width_cell[2],10,'Product Name',1,0,"C",true); // Third header column 
                $pdf->Cell($width_cell[3],10,'GRN Product Price',1,0,"R",true); // Fourth header column
                $pdf->Cell($width_cell[4],10,'Quantity',1,0,"R",true); // Fourth header column
                $pdf->Cell($width_cell[5],10,'Sub Total',1,1,"R",true); // Fourth header column
                //// header ends ///////
                
                $pdf->SetFont('Arial','',14);
                $pdf->SetFillColor(235,236,236); // Background color of row
                $fill=false; // to give alternate background fill color to rows 
                
                /// each record is one row  ///
                foreach ($link->query($sql2) as $row2) {

                    $iid=$row2['id'];
                    $product=$row2['product'];

                    $z=$product; 
                    $count2="SELECT * FROM products WHERE id=$z"; // SQL to get product
                    foreach ($link->query($count2) as $row1) {
                        $product_name = $row1['name'];  
                    }       
                   
                    $quantity = $row2['quantity'];

                    $count7="SELECT * FROM grn_details WHERE product=$z && grn_id=$grn"; // SQL to select
                    foreach ($link->query($count7) as $row7) {
                        $buy = $row7["buy"];
                    }   

                    $sub = $buy*$quantity;
                    $total+=$sub;

                    $pdf->Cell($width_cell[0],10,$iid,1,0,"R",$fill);
                    $pdf->Cell($width_cell[1],10,$product,1,0,"R",$fill);
                    $pdf->Cell($width_cell[2],10,$product_name,1,0,"C",$fill);
                    $pdf->Cell($width_cell[3],10,$buy,1,0,"R",$fill);
                    $pdf->Cell($width_cell[4],10,$quantity,1,0,"R",$fill);
                    $pdf->Cell($width_cell[5],10,$sub,1,1,"R",$fill); // Fourth header column
                    $fill = !$fill; // to give alternate background fill  color to rows
                /// end of records ///   
                }              
                $pdf->SetFont('Arial','B',14);
                $pdf->cell(246,15,'Total :',0,0,'R');
                $pdf->SetFont('Arial','B',14);
                $pdf->cell(31,15,$total,0,0,'R');
                $pdf->Ln(7);

                $pdf->Output();
                                      
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);

    
}// ID not emty 

else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}

?>


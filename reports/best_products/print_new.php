<?php

// Include the file containing database connection code segement
require_once '../../common/config.php';

// Include the file containing login function code segement
require_once '../../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../../login.php');
}

// get the FROM date and TO date
    $from = $_GET['from'];
    $to = $_GET['to'];


    ///// WHEN STATUS NOT SELECTED
    /* Both the From and To date selected */
    if($_GET['from'] != "" && $_GET['to'] != "") {

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
    }else if($_GET['from'] !="" && $_GET['to'] ==""){ 

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
    }else if($_GET['from'] =="" && $_GET['to'] !=""){ 

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
    }else if($_GET['from'] != "" && $_GET['to'] != "") {

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE date between ? and ? ";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_from,$param_to);
            
            // Set parameters
            $param_from = $from;
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;
        }


    /* Only the From date selected */
    }else if($_GET['from'] !="" && $_GET['to'] ==""){ 

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
    }else if($_GET['from'] =="" && $_GET['to'] !="" ){ 

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE date between 0 and ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_to);
            
            // Set parameters
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;

        }
 
    
    }else if($_GET['from'] =="" && $_GET['to'] ==""){ 
        $status = '';
        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE status=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_status);
            
            // Set parameters
            $param_status = $status;
        }
 
    
    }else if($_GET['from'] =="" && $_GET['to'] ==""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM invoices WHERE ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_status);
            
            // Set parameters
            $param_status = 1;
        }
 
    
    }else{ 
        $stmt = "";
    }

    if($stmt!=""){
        // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        $number=mysqli_num_rows($result);





//use fpdf to print
require('../../common/fpdf/fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
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
    $this->SetFont('Arial','BU',16);
    $this->cell(0,15,'Best Selling Products',0,1,'L');
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
$pdf->cell(30,15,'From :',0,0,'L');
$pdf->SetFont('Arial','',14);
$pdf->cell(2,15,$from,0,0,'L');
$pdf->SetFont('Arial','B',14);
$pdf->cell(68,15,'To :',0,0,'R');
$pdf->SetFont('Arial','',14);
$pdf->cell(40,15,$to,0,1,'R');
// $pdf->cell(100,15,'Number of Products :',0,0,'R');
// $pdf->SetFont('Arial','',14);
// $pdf->cell(20,15,$number,0,1,'R');


$width_cell=array(90,110,77);//277mm total
$pdf->SetFont('Arial','B',14);

$pdf->SetFillColor(193,229,252); // Background color of header 
// Header starts /// 
$pdf->Cell($width_cell[0],10,'Product ID',1,0,"C",true); // First header column 
$pdf->Cell($width_cell[1],10,'Product Name',1,0,"C",true); // Third header column                     
$pdf->Cell($width_cell[2],10,'Quantity',1,1,"C",true); // Second header column

//// header ends ///////

$pdf->SetFont('Arial','',14);
$pdf->SetFillColor(235,236,236); // Background color of header 
$fill=false; // to give alternate background fill color to rows 

/// each record is one row  ///
if(mysqli_stmt_execute($stmt)){
    $result = mysqli_stmt_get_result($stmt);{

    /// each record is one row  ///
    foreach ($result as $row) {
        $invoice_id = $row['id'];
        $count = "SELECT product, SUM(quantity) AS total_quantity FROM invoices_details WHERE invoices_id = $invoice_id GROUP BY product"; // SQL with GROUP BY

        foreach ($link->query($count) as $row1) {
            $product_id = $row1['product'];
            $quantity = $row1['total_quantity'];

            if (!isset($product_quantities[$product_id])) {
                $product_quantities[$product_id] = 0;
            }
            $product_quantities[$product_id] += $quantity;
        }

    }

    foreach ($product_quantities as $product_id => $quantity) {
        $pdf->Cell($width_cell[0],10,$product_id,1,0,"C",$fill);

        $product_name_query = "SELECT name FROM products WHERE id = $product_id";
        $product_name_result = $link->query($product_name_query);
        $product_name_row = $product_name_result->fetch_assoc();
        $pdf->Cell($width_cell[1],10,$product_name_row['name'],1,0,"L",$fill);

        $product_quantity = 0;
        $count="SELECT * FROM invoices_details WHERE product=$product_id"; // SQL to get records 
        foreach ($link->query($count) as $row1) {
            $product_quantity += $row1['quantity'];  
        }
        $pdf->Cell($width_cell[2],10,$product_quantity,1,1,"C",$fill);

    }
                   
$fill = !$fill; // to give alternate background fill  color to rows
/// end of records /// 

}          
}    

}
}
$pdf->Output();

?>

<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// get the FROM date and TO date
    $from = $_GET['from'];
    $to = $_GET['to'];
    $status = $_GET['status'];


    ///// WHEN STATUS NOT SELECTED
    /* Both the From and To date selected */
    if($_GET['from'] != "" && $_GET['to'] != "" && $_GET['status'] == "") {

        // Prepare a select statement
        $sql = "SELECT * FROM purchases WHERE date between ? and ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_from,$param_to);
            
            // Set parameters
            $param_from = $from;
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;
        }


    /* Only the From date selected */
    }else if($_GET['from'] !="" && $_GET['to'] =="" && $_GET['status'] == ""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM purchases WHERE date between ? and ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_from,$param_to);
            
            // Set parameters
            $param_from = $from;
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($from)));
            $param_to = $date;
        }    


    /* Only the To date selected */
    }else if($_GET['from'] =="" && $_GET['to'] !="" && $_GET['status'] == ""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM purchases WHERE date between 0 and ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_to);
            
            // Set parameters
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;
        }

 
    ///// WHEN STATUS SELECTED
    /* Both the From and To date selected */    
    }else if($_GET['from'] != "" && $_GET['to'] != "" && $_GET['status'] !="") {

        // Prepare a select statement
        $sql = "SELECT * FROM purchases WHERE date between ? and ? AND status=?";

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
    }else if($_GET['from'] !="" && $_GET['to'] =="" && $_GET['status'] !=""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM purchases WHERE date between ? and ? AND status=?";

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
    }else if($_GET['from'] =="" && $_GET['to'] !="" && $_GET['status'] !=""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM purchases WHERE date between 0 and ? AND status=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_to,$param_status);
            
            // Set parameters
            $date = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($to)));
            $param_to = $date;
            $param_status = $status; 
        }
 
    
    }else if($_GET['from'] =="" && $_GET['to'] =="" && $_GET['status'] !=""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM purchases WHERE status=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_status);
            
            // Set parameters
            $param_status = $status;
        }
 
    
    }else if($_GET['from'] =="" && $_GET['to'] =="" && $_GET['status'] ==""){ 

        // Prepare a select statement
        $sql = "SELECT * FROM purchases WHERE ?";

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
require('../common/fpdf/fpdf.php');

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
    $this->cell(0,15,'Purchase Orders by Date Range Report',0,1,'L');
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
$pdf->cell(40,15,$to,0,0,'R');
$pdf->cell(100,15,'Number of Purchase Orders :',0,0,'R');
$pdf->SetFont('Arial','',14);
$pdf->cell(20,15,$number,0,1,'R');


$width_cell=array(55,55,55,50,90,27);//277mm total
$pdf->SetFont('Arial','B',14);

$pdf->SetFillColor(193,229,252); // Background color of header 
// Header starts /// 
$pdf->Cell($width_cell[0],10,'Purchase Order ID',1,0,"R",true); // First header column 
$pdf->Cell($width_cell[1],10,'Date',1,0,"C",true); // Third header column 
//$pdf->Cell($width_cell[2],10,'Purchase Order ID',1,0,"R",true); // First header column                      
$pdf->Cell($width_cell[3],10,'Supplier ID',1,0,"R",true); // Second header column
$pdf->Cell($width_cell[4],10,'Supplier Name',1,0,"C",true); // Fourth header column
$pdf->Cell($width_cell[5],10,'Status',1,1,"C",true); // Third header column 
//$pdf->Cell($width_cell[6],10,'Status',1,1,"",true); // Third header column 
//// header ends ///////

$pdf->SetFont('Arial','',14);
$pdf->SetFillColor(235,236,236); // Background color of header 
$fill=false; // to give alternate background fill color to rows 

/// each record is one row  ///
if(mysqli_stmt_execute($stmt)){
    $result = mysqli_stmt_get_result($stmt);{

    /// each record is one row  ///
    foreach ($result as $row) {
        $id = $row["id"];
        $date = $row["date"];
        //$purchases = $row["purchases"];
    /*
        $sql2 = "SELECT * FROM purchases WHERE id=$purchases";
        $result = mysqli_query($link, $sql2);
        $row0 = mysqli_fetch_array($result);
        */

        $supplier = $row['supplier'];

    
        $count="SELECT * FROM suppliers WHERE id=$supplier"; // SQL to get supplier name 
        foreach ($link->query($count) as $row0) {
            $supplier_name = $row0['firstname']." ".$row0['lastname'];  
        }       
    
        $status = $row["status"];
      
        $pdf->Cell($width_cell[0],10,$id,1,0,"R",$fill);
        $pdf->Cell($width_cell[1],10,$date,1,0,"C",$fill);
        //$pdf->Cell($width_cell[2],10,$purchases,1,0,"R",$fill);
        $pdf->Cell($width_cell[3],10,$supplier,1,0,"R",$fill);
        $pdf->Cell($width_cell[4],10,$supplier_name,1,0,"C",$fill);
        //$pdf->Cell($width_cell[5],10,$status,1,0,"",$fill);
        if($status==1){
            $pdf->Cell($width_cell[5],10,'Active',1,1,"C",$fill);
        }else{
            $pdf->Cell($width_cell[5],10,'Deactive',1,1,"C",$fill);
        }                
                  
    $fill = !$fill; // to give alternate background fill  color to rows
    }
/// end of records /// 

}          
}    

}
}
$pdf->Output();

?>

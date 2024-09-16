<?Php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

$sql="SELECT * FROM stock WHERE 1 ORDER BY `id` DESC"; // SQL to get all records 

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
                    $this->Ln(7);


    $this->SetFont('Arial','BU',16);
    $this->cell(0,15,'All Stock Details',0,1,'L');
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

$pdf = new PDF('P','mm','A4'); 
$pdf->AliasNbPages();
$pdf->AddPage();



$width_cell=array(20,20,75,20,20,30);//190mm
$pdf->SetFont('Arial','B',12);

$pdf->SetFillColor(193,229,252); // Background color of header 
// Header starts /// 
$pdf->Cell($width_cell[0],10,'Stock ID',1,0,"R",true); // First header column 
$pdf->Cell($width_cell[1],10,'Product ID',1,0,"R",true); // Third header column 
$pdf->Cell($width_cell[2],10,'Product Name',1,0,"C",true); // First header column                      
$pdf->Cell($width_cell[3],10,'Stock Quantity',1,0,"",true); // Second header column
$pdf->Cell($width_cell[4],10,'Reorder Level',1,0,"",true); // Fourth header column
$pdf->Cell($width_cell[5],10,'Stock Level',1,1,"",true); // Third header column 
//$pdf->Cell($width_cell[6],10,'Status',1,1,"",true); // Third header column 

//// header ends ///////

$pdf->SetFont('Arial','',14);
$pdf->SetFillColor(235,236,236); // Background color of header 
$fill=false; // to give alternate background fill color to rows 

/// each record is one row  ///
foreach ($link->query($sql) as $row) {

    $id = $row['id'];
    $pid = $row['pid'];

    $count="SELECT * FROM products WHERE id=$pid"; // SQL to get records 
    foreach ($link->query($count) as $row1) {
        $pname = $row1['name']; 
        $reorder = $row1['reorder']; 
    }       

    $quantity = $row['quantity'];   

    $pdf->SetFont('Arial','',11);

    $pdf->Cell($width_cell[0],10,$id,1,0,"R",$fill);
    $pdf->Cell($width_cell[1],10,$pid,1,0,"R",$fill);
    $pdf->Cell($width_cell[2],10,$pname,1,0,"C",$fill);
    $pdf->Cell($width_cell[3],10,$quantity,1,0,"R",$fill);
    $pdf->Cell($width_cell[4],10,$reorder,1,0,"R",$fill);
    //$pdf->Cell($width_cell[5],10,$row['reorder'],1,0,"",$fill);
    if($quantity>$reorder){
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell($width_cell[5],10,'Sufficient',1,1,"C",$fill);
    }else{
        $pdf->SetFont('Arial','I',11);
        $pdf->Cell($width_cell[5],10,'Insufficient',1,1,"C",$fill);
    }                

    $fill = !$fill; // to give alternate background fill  color to rows
    }
/// end of records /// 

$pdf->Output();

?>

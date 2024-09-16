<?php

// Include the file containing database connection code segement
require_once '../common/config.php';

// Include the file containing login function code segement
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    
    // Prepare a select statement
    $sql = "SELECT * FROM products WHERE id = ?";
    
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
                    $this->cell(0,15,'Product Details',0,1,'L');

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



                $pdf->SetFont('Arial','B',10);
                $width_cell=array(20,20,60,20,25,120);//277 mm total
                $pdf->SetFillColor(153, 153, 255); // Background color of header 

                // Header starts /// 
                $pdf->Cell($width_cell[0],10,'Id',1,0,"",true); // First header column 
                $pdf->Cell($width_cell[1],10,'Category',1,0,"",true); // Second header column 
                $pdf->Cell($width_cell[2],10,'Name',1,0,"C",true); // Third header column
                $pdf->Cell($width_cell[3],10,'Price',1,0,"",true); // Fourth header column 
                $pdf->Cell($width_cell[4],10,'Reorder Level',1,0,"",true); // fifth header column 
                $pdf->Cell($width_cell[5],10,'Description',1,1,"C",true); // Sixth header column 

                //// header ends ///////
                
                $pdf->SetFont('Arial','',10);
                $pdf->SetFillColor(235,236,236); // Background color of row
                $fill=false; // to give alternate background fill color to rows 

                /// each record is one row  ///
                $pdf->Cell($width_cell[0],10,$row['id'],1,0,"",$fill);
                $pdf->Cell($width_cell[1],10,$row['category'],1,0,"",$fill);
                $pdf->Cell($width_cell[2],10,$row['name'],1,0,"",$fill);
                $pdf->Cell($width_cell[3],10,$row['price'],1,0,"",$fill);
                $pdf->Cell($width_cell[4],10,$row['reorder'],1,0,"",$fill);
                $pdf->Cell($width_cell[5],10,$row['description'],1,0,"",$fill);
                
                //$pdf->Cell($width_cell[14],10,$row['description'],1,0,"",$fill);
                //$pdf->Cell($width_cell[14],10,$row['salary'],1,1,"",$fill);
                $fill = !$fill; // to give alternate background fill  color to rows
                /// end of records ///   
                
            

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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>


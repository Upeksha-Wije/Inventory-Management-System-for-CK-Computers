<?php

// Include the file containing database connection code
require_once '../common/config.php';

// Include the file containing login function code
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
    exit();
}

// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    
    // Prepare a select statement
    $sql = "SELECT * FROM suppliers WHERE id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
    
            if (mysqli_num_rows($result) == 1) {
                // Fetch result row as an associative array
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Use FPDF to print
                require('../common/fpdf/fpdf.php');

                class PDF extends FPDF
                {
                    // Page header
                    function Header()
                    {
                        $this->SetFont('Arial', 'B', 14);
                        $this->Cell(0, 15, 'CK Computers', 0, 0, 'C');
                        $this->Ln(7);
                        $this->Cell(0, 15, 'Sales & Services', 0, 0, 'C');
                        $this->Ln(7);
                        $this->Cell(0, 15, 'All Types Of Computer, Laptop & Accessorie', 0, 0, 'C');
                        $this->Ln(7);
                        $this->Cell(0, 15, 'Oruthota Road, Gampaha', 0, 0, 'C');
                        $this->Ln(7);
                        $this->Cell(0, 15, '0767729250 / 0778565800', 0, 1, 'C');
                        $this->Ln(7);

                        $this->SetFont('Arial', 'BU', 16);
                        $this->Cell(0, 15, 'Supplier Details', 0, 1, 'L');
                    }

                    // Page footer
                    function Footer()
                    {
                        $this->SetY(-15);
                        $this->SetFont('Arial', 'I', 8);
                        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
                    }
                }

                $pdf = new PDF('L', 'mm', 'A4'); 
                $pdf->AliasNbPages();
                $pdf->AddPage();

                $pdf->SetFont('Arial', 'B', 10);
                $width_cell = array(10, 15, 30, 30, 35, 30, 30, 30, 30, 40); // Adjusted widths
                $pdf->SetFillColor(153, 153, 255); // Background color of header 

                // Header starts 
                $pdf->Cell($width_cell[0], 10, 'Id', 1, 0, 'C', true);
                $pdf->Cell($width_cell[1], 10, 'Title', 1, 0, 'C', true);
                $pdf->Cell($width_cell[2], 10, 'First Name', 1, 0, 'C', true);
                $pdf->Cell($width_cell[3], 10, 'Last Name', 1, 0, 'C', true);
                $pdf->Cell($width_cell[4], 10, 'Address', 1, 0, 'C', true);
                $pdf->Cell($width_cell[5], 10, 'Email', 1, 0, 'C', true);
                $pdf->Cell($width_cell[6], 10, 'Mobile', 1, 0, 'C', true);
                $pdf->Cell($width_cell[7], 10, 'Office Tel', 1, 0, 'C', true);
                $pdf->Cell($width_cell[8], 10, 'NIC', 1, 0, 'C', true);
                $pdf->Cell($width_cell[9], 10, 'Description', 1, 1, 'C', true);
                
                // Header ends

                $pdf->SetFont('Arial', '', 10);
                $pdf->SetFillColor(235, 236, 236); // Background color of row
                $fill = false; // to give alternate background fill color to rows 

                // Each record is one row 
                $pdf->Cell($width_cell[0], 10, $row['id'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[1], 10, $row['title'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[2], 10, $row['firstname'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[3], 10, $row['lastname'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[4], 10, $row['address'], 1, 0, 'L', $fill);
                $pdf->Cell($width_cell[5], 10, $row['email'], 1, 0, 'L', $fill);
                $pdf->Cell($width_cell[6], 10, $row['mobile'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[7], 10, $row['office_tel'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[8], 10, $row['nic'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[9], 10, $row['description'], 1, 1, 'L', $fill);
                
                $fill = !$fill; // to give alternate background fill color to rows

                // Output the PDF
                $pdf->Output();

            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
     
    // Close connection
    mysqli_close($link);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

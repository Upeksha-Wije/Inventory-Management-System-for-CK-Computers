<?php

// Include the file containing the database connection code segment
require_once '../common/config.php';

// Include the file containing login function code segment
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

$sql = "SELECT * FROM staff"; // SQL to get all records 

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
        $this->Cell(0, 15, 'All Types Of Computer, Laptop & Accessories', 0, 0, 'C');
        $this->Ln(7);
        $this->Cell(0, 15, 'Oruthota Road, Gampaha', 0, 0, 'C');
        $this->Ln(7);
        $this->Cell(0, 15, '0767729250 / 0778565800', 0, 1, 'C');
        $this->Ln(7);

        $this->SetFont('Arial', 'BU', 16);
        $this->Cell(0, 15, 'All Staff Details', 0, 1, 'L');
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 10);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('L', 'mm', 'A4'); 
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$width_cell = array(15, 30, 40, 40, 50, 50, 20); // Column widths
$pdf->SetFillColor(193, 229, 252); // Background color of header

// Header starts
$pdf->Cell($width_cell[0], 10, 'Id', 1, 0, 'C', true);
$pdf->Cell($width_cell[1], 10, 'Title', 1, 0, 'C', true);
$pdf->Cell($width_cell[2], 10, 'First Name', 1, 0, 'C', true);
$pdf->Cell($width_cell[3], 10, 'Last Name', 1, 0, 'C', true);
$pdf->Cell($width_cell[4], 10, 'NIC', 1, 0, 'C', true);
$pdf->Cell($width_cell[5], 10, 'Mobile', 1, 0, 'C', true);
$pdf->Cell($width_cell[6], 10, 'Status', 1, 1, 'C', true);
// Header ends

$pdf->SetFont('Arial', '', 12);
$pdf->SetFillColor(235, 236, 236); // Background color of rows
$fill = false; // Alternate row colors

// Each record is one row
foreach ($link->query($sql) as $row) {
    $pdf->Cell($width_cell[0], 10, $row['id'], 1, 0, 'C', $fill);
    $pdf->Cell($width_cell[1], 10, $row['title'], 1, 0, 'C', $fill);
    $pdf->Cell($width_cell[2], 10, $row['firstname'], 1, 0, 'C', $fill);
    $pdf->Cell($width_cell[3], 10, $row['lastname'], 1, 0, 'C', $fill);
    $pdf->Cell($width_cell[4], 10, $row['nic'], 1, 0, 'C', $fill);
    $pdf->Cell($width_cell[5], 10, $row['mobile'], 1, 0, 'C', $fill);
    $pdf->Cell($width_cell[6], 10, $row['status'], 1, 1, 'C', $fill);

    $fill = !$fill; // Toggle fill color
}

$pdf->Output();

?>

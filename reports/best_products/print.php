<?php
// Include the file containing database connection code segment
require_once '../../common/config.php';

// Include the file containing login function code segment
require_once '../../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../../login.php');
    exit;
}

// Get the FROM date, TO date, and status from the query parameters
$from = $_GET['from'] ?? "";
$to = $_GET['to'] ?? "";
$status = $_GET['status'] ?? "";

// Initialize the SQL query and parameters based on the provided input
if ($from && $to && !$status) {
    $sql = "SELECT * FROM invoices WHERE date BETWEEN ? AND ?";
    $params = [$from, date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to)))];
} elseif ($from && !$to && !$status) {
    $sql = "SELECT * FROM invoices WHERE date BETWEEN ? AND ?";
    $params = [$from, date('Y-m-d H:i:s', strtotime('+1 day', strtotime($from)))];
} elseif (!$from && $to && !$status) {
    $sql = "SELECT * FROM invoices WHERE date <= ?";
    $params = [date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to)))];
} elseif ($from && $to && $status) {
    $sql = "SELECT * FROM invoices WHERE date BETWEEN ? AND ? AND status = ?";
    $params = [$from, date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to))), $status];
} elseif ($from && !$to && $status) {
    $sql = "SELECT * FROM invoices WHERE date BETWEEN ? AND ? AND status = ?";
    $params = [$from, date('Y-m-d H:i:s', strtotime('+1 day', strtotime($from))), $status];
} elseif (!$from && $to && $status) {
    $sql = "SELECT * FROM invoices WHERE date <= ? AND status = ?";
    $params = [date('Y-m-d H:i:s', strtotime('+1 day', strtotime($to))), $status];
} elseif (!$from && !$to && $status) {
    $sql = "SELECT * FROM invoices WHERE status = ?";
    $params = [$status];
} else {
    $sql = "SELECT * FROM invoices";
    $params = [];
}

// Prepare the SQL statement and execute it
$stmt = $link->prepare($sql);
if (!empty($params)) {
    $types = str_repeat('s', count($params) - 1) . 'i'; // Set parameter types
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$number = $result->num_rows;

// Use FPDF to generate a PDF
require('../../common/fpdf/fpdf.php');

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
        $this->SetFont('Arial', 'BU', 16);
        $this->Cell(0, 15, 'Best Selling Products Report', 0, 1, 'L');
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Create PDF document
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

// Add report details
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(30, 15, 'From :', 0, 0, 'L');
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(2, 15, $from, 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(68, 15, 'To :', 0, 0, 'R');
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(40, 15, $to, 0, 0, 'R');
$pdf->Cell(100, 15, 'Number of Products:', 0, 0, 'R');
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(20, 15, $number, 0, 1, 'R');

// Add table header
$width_cell = [60,60,60]; // Total: 277mm
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetFillColor(193, 229, 252); // Background color of header
$pdf->Cell($width_cell[0], 10, 'Product ID', 1, 0, "R", true);
$pdf->Cell($width_cell[1], 10, 'Product Name', 1, 0, "C", true);
$pdf->Cell($width_cell[3], 10, 'Sold Quantity', 1, 0, "R", true);


// Add table data
$pdf->SetFont('Arial', '', 14);
$pdf->SetFillColor(235, 236, 236); // Background color for alternate rows
$fill = false;

if (isset($_POST['se']) && $stmt != "") {
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
$total = 0;
    $product_quantities = [];

    while ($row = mysqli_fetch_array($result)) {
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
        echo "<tr>";
        echo "<td>$product_id</td>";

        $product_name_query = "SELECT name FROM products WHERE id = $product_id";
        $product_name_result = $link->query($product_name_query);
        $product_name_row = $product_name_result->fetch_assoc();
        echo "<td>".$product_name_row['name']."</td>";

        echo "<td>";
        $product_quantity = 0;
        $count="SELECT * FROM invoices_details WHERE product=$product_id"; // SQL to get records 
        foreach ($link->query($count) as $row1) {
            $product_quantity += $row1['quantity'];  
        }
        echo $product_quantity; 

    $pdf->Cell($width_cell[0], 10, $row['id'], 1, 0, "R", $fill);
    $pdf->Cell($width_cell[1], 10, $row['name'], 1, 0, "C", $fill);
    $pdf->Cell($width_cell[3], 10, $row['quantity'], 1, 0, "R", $fill);


    $fill = !$fill;
}

// Output the PDF
$pdf->Output();
?>

<?php

// Include the file containing database connection code segment
require_once '../common/config.php';

// Include the file containing login function code segment
require_once '../common/functions.php';

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {

    // Prepare a select statement
    $sql = "SELECT * FROM customers WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Use FPDF to generate the PDF
                require('../common/fpdf/fpdf.php');

                class PDF extends FPDF
                {
                    // Page header
                    function Header()
                    {
                        $this->SetFont('Arial', 'B', 14);
                        $this->Cell(0, 10, 'CK Computers', 0, 1, 'C');
                        $this->SetFont('Arial', '', 12);
                        $this->Cell(0, 10, 'Sales & Services', 0, 1, 'C');
                        $this->Cell(0, 10, 'All Types Of Computer, Laptop & Accessories', 0, 1, 'C');
                        $this->Cell(0, 10, 'Oruthota Road, Gampaha', 0, 1, 'C');
                        $this->Cell(0, 10, '0767729250 / 0778565800', 0, 1, 'C');
                        $this->Ln(10);
                        $this->SetFont('Arial', 'BU', 16);
                        $this->Cell(0, 10, 'Customer Details', 0, 1, 'L');
                    }

                    // Page footer
                    function Footer()
                    {
                        // Position at 1.5 cm from bottom
                        $this->SetY(-15);
                        // Arial italic 8
                        $this->SetFont('Arial', 'I', 8);
                        // Page number
                        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
                    }
                }

                $pdf = new PDF('L', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Set font for the table header
                $pdf->SetFont('Arial', 'B', 10);
                $width_cell = array(10, 12, 30, 30, 53, 50, 30, 30, 40); // Adjusted column widths
                $pdf->SetFillColor(153, 153, 255); // Background color of header

                // Table header
                $pdf->Cell($width_cell[0], 10, 'Id', 1, 0, 'C', true);
                $pdf->Cell($width_cell[1], 10, 'Title', 1, 0, 'C', true);
                $pdf->Cell($width_cell[2], 10, 'First Name', 1, 0, 'C', true);
                $pdf->Cell($width_cell[3], 10, 'Last Name', 1, 0, 'C', true);
                $pdf->Cell($width_cell[4], 10, 'Address', 1, 0, 'C', true);
                $pdf->Cell($width_cell[5], 10, 'Email', 1, 0, 'C', true);
                $pdf->Cell($width_cell[6], 10, 'Mobile', 1, 0, 'C', true);
                $pdf->Cell($width_cell[7], 10, 'NIC', 1, 0, 'C', true);
                $pdf->Cell($width_cell[8], 10, 'Description', 1, 1, 'C', true); // '1' as the last parameter for a new line

                // Set font for table data
                $pdf->SetFont('Arial', '', 10);
                $pdf->SetFillColor(235, 236, 236); // Background color of rows
                $fill = false; // To alternate row colors

                // Table row (record)
                $pdf->Cell($width_cell[0], 10, $row['id'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[1], 10, $row['title'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[2], 10, $row['firstname'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[3], 10, $row['lastname'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[4], 10, $row['address'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[5], 10, $row['email'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[6], 10, $row['mobile'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[7], 10, $row['nic'], 1, 0, 'C', $fill);
                $pdf->Cell($width_cell[8], 10, $row['description'], 1, 1, 'C', $fill); // '1' as the last parameter for a new line

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
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}

?>

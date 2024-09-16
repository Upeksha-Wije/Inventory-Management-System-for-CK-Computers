<?php

// Include the file containing database connection code segment
require_once '../common/config.php';

// Include the file containing login function code segment
require_once '../common/functions.php';

// Check if the user is logged in
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
    exit();
}

$total = 0;

// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    
    // Prepare a select statement
    $sql = "SELECT * FROM grn WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
    
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
     
                $id = $row["id"];
                $date = $row["date"];
                $purchases = $row["purchases"];

                // Get supplier information
                $sql2 = "SELECT * FROM purchases WHERE id = ?";
                if ($stmt2 = mysqli_prepare($link, $sql2)) {
                    mysqli_stmt_bind_param($stmt2, "i", $purchases);
                    mysqli_stmt_execute($stmt2);
                    $result2 = mysqli_stmt_get_result($stmt2);
                    $row0 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
                    $supplier = $row0['supplier'];

                    // Get supplier name
                    $sql3 = "SELECT * FROM suppliers WHERE id = ?";
                    if ($stmt3 = mysqli_prepare($link, $sql3)) {
                        mysqli_stmt_bind_param($stmt3, "i", $supplier);
                        mysqli_stmt_execute($stmt3);
                        $result3 = mysqli_stmt_get_result($stmt3);
                        if ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
                            $supplier_name = $row3['firstname'] . " " . $row3['lastname'];
                        } else {
                            $supplier_name = "Unknown";
                        }
                    } else {
                        $supplier_name = "Error fetching supplier";
                    }

                    // Get GRN details
                    $sql4 = "SELECT * FROM grn_details WHERE grn_id = ? AND status = 1 ORDER BY id DESC";
                    if ($stmt4 = mysqli_prepare($link, $sql4)) {
                        mysqli_stmt_bind_param($stmt4, "i", $id);
                        mysqli_stmt_execute($stmt4);
                        $result4 = mysqli_stmt_get_result($stmt4);

                        // Use FPDF to print
                        require('../common/fpdf/fpdf.php');

                        class PDF extends FPDF
                        {
                            // Page header
                            function Header()
                            {
                                $this->SetFont('Arial','B',14);
                                $this->Cell(0,15,'CK Computers',0,0,'C');
                                $this->Ln(7);
                                $this->Cell(0,15,'Sales & Services',0,0,'C');
                                $this->Ln(7);
                                $this->Cell(0,15,'All Types Of Computer, Laptop & Accessorie',0,0,'C');
                                $this->Ln(7);
                                $this->Cell(0,15,'Oruthota Road, Gampaha',0,0,'C');
                                $this->Ln(7);
                                $this->Cell(0,15,'0767729250 / 0778565800',0,1,'C');
                                $this->Ln(7);

                                $this->SetFont('Arial','BU',16);
                                $this->Cell(0,15,'GRN',0,1,'L');
                            }

                            // Page footer
                            function Footer()
                            {
                                $this->SetY(-15);
                                $this->SetFont('Arial','I',8);
                                $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
                            }
                        }

                        $pdf = new PDF('P','mm','A4'); 
                        $pdf->AliasNbPages();
                        $pdf->AddPage();

                        $pdf->SetFont('Arial','B',14);
                        $pdf->Cell(60,15,'GRN ID :',0,0,'L');
                        $pdf->SetFont('Arial','',14);
                        $pdf->Cell(2,15,$id,0,0,'R');
                        $pdf->SetFont('Arial','B',14);
                        $pdf->Cell(68,15,'Date :',0,0,'R');
                        $pdf->SetFont('Arial','',14);
                        $pdf->Cell(60,15,$date,0,0,'R');
                        $pdf->Ln(7);
                        $pdf->SetFont('Arial','B',14);
                        $pdf->Cell(60,15,'Supplier ID :',0,0,'L');
                        $pdf->SetFont('Arial','',14);
                        $pdf->Cell(2,15,$supplier,0,0,'R');
                        $pdf->SetFont('Arial','B',14);
                        $pdf->Cell(68,15,'Supplier Name :',0,0,'R');
                        $pdf->SetFont('Arial','',14);
                        $pdf->Cell(60,15,$supplier_name,0,0,'R');
                        $pdf->Ln(7);
                        $pdf->SetFont('Arial','B',14);
                        $pdf->Cell(60,15,'Purchase Order ID :',0,0,'L');
                        $pdf->SetFont('Arial','',14);
                        $pdf->Cell(2,15,$purchases,0,1,'R');

                        $pdf->Ln(5);

                        $pdf->SetFont('Arial','B',10);
                        $width_cell = array(20,30,55,22,30,33);
                        $pdf->SetFillColor(193,229,252);

                        // Header
                        $pdf->Cell($width_cell[0],10,'Item ID',1,0,"R",true);
                        $pdf->Cell($width_cell[1],10,'Product ID',1,0,"R",true);
                        $pdf->Cell($width_cell[2],10,'Product Name',1,0,"C",true);
                        $pdf->Cell($width_cell[4],10,'Unit Price',1,0,"R",true);
                        $pdf->Cell($width_cell[3],10,'Quantity',1,0,"R",true);
                        $pdf->Cell($width_cell[5],10,'Sub Total',1,1,"R",true);

                        $pdf->SetFont('Arial','',11);
                        $pdf->SetFillColor(235,236,236);
                        $fill = false;

                        // Data rows
                        while ($row2 = mysqli_fetch_array($result4, MYSQLI_ASSOC)) {
                            $iid = $row2['id'];
                            $product = $row2['product'];

                            // Get product name
                            $sql5 = "SELECT * FROM products WHERE id = ?";
                            if ($stmt5 = mysqli_prepare($link, $sql5)) {
                                mysqli_stmt_bind_param($stmt5, "i", $product);
                                mysqli_stmt_execute($stmt5);
                                $result5 = mysqli_stmt_get_result($stmt5);
                                if ($row1 = mysqli_fetch_array($result5, MYSQLI_ASSOC)) {
                                    $product_name = $row1['name'];
                                } else {
                                    $product_name = "Unknown";
                                }
                            } else {
                                $product_name = "Error fetching product";
                            }

                            $quantity = $row2['quantity'];
                            $buy = $row2['buy'];
                            $sub = $quantity * $buy;
                            $total += $sub;

                            $pdf->Cell($width_cell[0],10,$iid,1,0,"R",$fill);
                            $pdf->Cell($width_cell[1],10,$product,1,0,"R",$fill);
                            $pdf->Cell($width_cell[2],10,$product_name,1,0,"C",$fill);
                            $pdf->Cell($width_cell[4],10,$buy,1,0,"R",$fill);
                            $pdf->Cell($width_cell[3],10,$quantity,1,0,"R",$fill);
                            $pdf->Cell($width_cell[5],10,$sub,1,1,"R",$fill);
                            $fill = !$fill;
                        }

                        // Total
                        $pdf->SetFont('Arial','B',14);
                        $pdf->Cell(159,15,'Total :',0,0,'R');
                        $pdf->Cell(31,15,$total,0,0,'R');
                        $pdf->Ln(7);

                        $pdf->Output();
                    } else {
                        echo "Error preparing statement: " . mysqli_error($link);
                    }
                } else {
                    echo "Error preparing statement: " . mysqli_error($link);
                }
            } else {
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($link);
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);

} else {
    header("location: error.php");
    exit();
}

?>

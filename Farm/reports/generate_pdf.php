<?php
require_once('../tcpdf/tcpdf.php'); // Adjust path based on your project structure
require_once('../config/db.php'); // Include your database connection

// Create a new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle('Dairy Management System Report');
$pdf->SetHeaderData('', 0, 'Dairy Management Report', 'Generated on: ' . date('Y-m-d H:i:s'));
$pdf->setHeaderFont(Array('helvetica', '', 10));
$pdf->setFooterFont(Array('helvetica', '', 8));
$pdf->SetMargins(10, 30, 10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

// Function to add section titles
function addSectionTitle($pdf, $title) {
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, $title, 0, 1, 'C');
    $pdf->Ln(3);
}

// Function to generate tables
function generateTable($pdf, $headers, $data) {
    $pdf->SetFont('helvetica', 'B', 10);
    foreach ($headers as $col) {
        $pdf->Cell(45, 7, $col, 1, 0, 'C', true);
    }
    $pdf->Ln();

    $pdf->SetFont('helvetica', '', 10);
    foreach ($data as $row) {
        foreach ($row as $cell) {
            $pdf->Cell(45, 7, $cell, 1, 0, 'C');
        }
        $pdf->Ln();
    }
    $pdf->Ln(5);
}

// Get Cows Data
addSectionTitle($pdf, 'Cows Information');
$query = $conn->query("SELECT id, name, breed, dob, status FROM cows");
$cows = [];
while ($row = $query->fetch_assoc()) {
    $cows[] = [$row['id'], $row['name'], $row['breed'], $row['dob'], $row['status']];
}
generateTable($pdf, ['ID', 'Name', 'Breed', 'DOB', 'Status'], $cows);

// Get Milk Records
addSectionTitle($pdf, 'Milk Records');
$query = $conn->query("SELECT id, cow_id, quantity, date FROM milk_records");
$milk = [];
while ($row = $query->fetch_assoc()) {
    $milk[] = [$row['id'], $row['cow_id'], $row['quantity'] . ' L', $row['date']];
}
generateTable($pdf, ['ID', 'Cow ID', 'Quantity', 'Date'], $milk);

// Get Feed Records
addSectionTitle($pdf, 'Feed Records');
$query = $conn->query("SELECT id, cow_id, feed_type, amount, date FROM feed_records");
$feeds = [];
while ($row = $query->fetch_assoc()) {
    $feeds[] = [$row['id'], $row['cow_id'], $row['feed_type'], $row['amount'] . ' kg', $row['date']];
}
generateTable($pdf, ['ID', 'Cow ID', 'Feed Type', 'Amount', 'Date'], $feeds);

// Get Health Records
addSectionTitle($pdf, 'Health Records');
$query = $conn->query("SELECT id, cow_id, diagnosis, treatment, date FROM health_records");
$health = [];
while ($row = $query->fetch_assoc()) {
    $health[] = [$row['id'], $row['cow_id'], $row['diagnosis'], $row['treatment'], $row['date']];
}
generateTable($pdf, ['ID', 'Cow ID', 'Diagnosis', 'Treatment', 'Date'], $health);

// Output PDF
$pdf->Output('Dairy_Management_Report.pdf', 'D');
?>

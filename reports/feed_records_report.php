<?php
require_once '../functions.php'; // Corrected relative path
requireLogin();
require_once '../config/database.php'; // Corrected relative path
require_once '../models/FeedRecord.php'; // Corrected relative path
require_once('../TCPDF/tcpdf.php'); // Corrected relative path, adjust if needed

$feedRecord = new FeedRecord($pdo);
$feeds = $feedRecord->getAllFeedRecords();

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Feed Records Report (Plain)');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Create plain HTML (no borders)
$html = '
<h1>Feed Records</h1>
<style>
table, th, td {
  border: none;
}
</style>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Cow ID</th>
            <th>Feed Type</th>
            <th>Quantity (kg)</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>';

foreach ($feeds as $feedData) {
    $html .= '
        <tr>
            <td>' . htmlspecialchars($feedData['id']) . '</td>
            <td>' . htmlspecialchars($feedData['cow_id']) . '</td>
            <td>' . htmlspecialchars($feedData['feed_type']) . '</td>
            <td>' . htmlspecialchars($feedData['quantity']) . '</td>
            <td>' . htmlspecialchars($feedData['date']) . '</td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF
$pdf->Output('feed_records_report_plain.pdf', 'I');
?>
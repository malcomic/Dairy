<?php
// generate_milk_report.php
require_once 'config/database.php';
require_once 'models/MilkProduction.php';
require_once 'TCPDF/tcpdf.php'; // Adjust path if needed

try {
    // Get data
    $milkProduction = new MilkProduction($pdo);
    $milkRecords = $milkProduction->getMilkRecords();

    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name/Company');
    $pdf->SetTitle('Milk Production Report');
    $pdf->SetSubject('Milk Production Data');
    $pdf->SetKeywords('Milk, Production, Report');

    // Set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Milk Production Report', date('Y-m-d'));

    // Set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Create table
    $html = '<table border="1" cellpadding="5">';
    $html .= '<tr><th>Cow ID</th><th>Date</th><th>Milk Yield (Liters)</th><th>Notes</th></tr>';
    foreach ($milkRecords as $record) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($record['cow_id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($record['date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($record['milk_yield']) . '</td>';
        $html .= '<td>' . htmlspecialchars($record['notes']) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('milk_production_report.pdf', 'I'); // 'I' for inline display, 'D' for download

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die("PDF generation error: " . $e->getMessage());
}
?>
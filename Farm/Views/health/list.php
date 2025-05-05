<?php
require_once '../../functions.php';
requireLogin();

require_once '../../config/database.php';
require_once '../../models/HealthRecord.php';

$healthRecord = new HealthRecord($pdo);

// Retrieve all health records without filters
$records = $healthRecord->getAllHealthRecords();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; }
        h2 { color: #333; margin-bottom: 20px; }
        table { width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-secondary { margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Health Records</h2>

        <table class="table table-bordered table-responsive mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cow ID</th>
                    <th>Date</th>
                    <th>Health Status</th>
                    <th>Treatment</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr><td colspan="6">No health records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['id']); ?></td>
                            <td><?php echo htmlspecialchars($record['cow_id']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($record['date'])); ?></td>
                            <td><?php echo htmlspecialchars($record['health_status']); ?></td>
                            <td><?php echo htmlspecialchars($record['treatment']); ?></td>
                            <td><?php echo htmlspecialchars($record['notes']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <button onclick="printReport()" class="btn btn-secondary mt-3">Print Report</button>
        <a href="javascript:history.back()" class="btn btn-secondary mt-3">Back</a>

        <script>
            function printReport() {
                var printWindow = window.open('../../reports/health_records_report.php', '_blank');
                printWindow.onload = function() {
                    printWindow.print();
                };
            }
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
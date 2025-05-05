<?php
require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/FeedRecord.php';

$feedRecord = new FeedRecord($pdo);
$feeds = $feedRecord->getAllFeedRecords();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; }
        h2 { color: #333; margin-bottom: 20px; }
        table { width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Feed List</h2>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cow ID</th>
                    <th>Feed Type</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feeds as $feedData): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedData['id']); ?></td>
                        <td><?php echo htmlspecialchars($feedData['cow_id']); ?></td>
                        <td><?php echo htmlspecialchars($feedData['feed_type']); ?></td>
                        <td><?php echo htmlspecialchars($feedData['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($feedData['unit']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($feedData['date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <button onclick="printReport()" class="btn btn-secondary mt-3">Print Report</button>
        <a href="javascript:history.back()" class="btn btn-secondary mt-3">Back</a>

        <script>
            function printReport() {
                var printWindow = window.open('../../reports/feed_records_report.php', '_blank');
                printWindow.onload = function() {
                    printWindow.print();
                };
            }
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/MilkProduction.php';

$milkProduction = new MilkProduction($pdo);

// Get filter parameters from the URL
$cowId = $_GET['cow_id'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

// Retrieve records, passing filter parameters
$records = $milkProduction->getMilkRecords($cowId, $startDate, $endDate);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; }
        h2 { color: #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-secondary { margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Milk Records</h2>

        <form method="get" action="list.php" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="cow_id" class="form-label">Cow ID:</label>
                    <input type="number" name="cow_id" id="cow_id" class="form-control" value="<?php echo htmlspecialchars($cowId ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                    <a href="http://localhost/Dairy/" class="btn btn-secondary mt-4">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-responsive mt-4">
            <thead>
                <tr>
                    <th>Cow ID</th>
                    <th>Date</th>
                    <th>Milk Yield</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr><td colspan="4">No milk records found.</td></tr>
                <?php else: ?>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['cow_id']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($record['date'])); ?></td>
                            <td><?php echo htmlspecialchars($record['milk_yield']); ?></td>
                            <td><?php echo htmlspecialchars($record['notes']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

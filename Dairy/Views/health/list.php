<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/HealthRecord.php';

$healthRecord = new HealthRecord($pdo);

// Get filter parameters from the query string
$cowId = isset($_GET['cow_id']) ? (int)$_GET['cow_id'] : null;
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;

$records = $healthRecord->getHealthRecords($cowId, $startDate, $endDate);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your existing styles */
    </style>
</head>
<body>
    <div class="container">
        <h2>Health Records List</h2>
        <a href="../../index.php" class="btn btn-primary mb-3">Back to Dashboard</a> <form method="get" action="list.php" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="cow_id" class="form-label">Cow ID:</label>
                    <input type="number" name="cow_id" class="form-control" id="cow_id" value="<?php echo htmlspecialchars($cowId ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" name="start_date" class="form-control" id="start_date" value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date:</label>
                    <input type="date" name="end_date" class="form-control" id="end_date" value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                </div>
            </div>
        </form>

        <?php if (empty($records)): ?>
            <p>No health records found.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cow ID</th>
                        <th>Date</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Veterinarian</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['id']); ?></td>
                            <td><?php echo htmlspecialchars($record['cow_id']); ?></td>
                            <td><?php echo htmlspecialchars($record['date']); ?></td>
                            <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                            <td><?php echo htmlspecialchars($record['treatment']); ?></td>
                            <td><?php echo htmlspecialchars($record['veterinarian']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo htmlspecialchars($record['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete.php?id=<?php echo htmlspecialchars($record['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="add.php" class="btn btn-secondary">Add New Record</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

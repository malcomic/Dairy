<?php
require_once '../config.php'; // Ensure this file contains the database connection setup
require_once '../functions.php';
requireLogin();

// Fetch Health Records
$stmt = $pdo->query("SELECT * FROM health_records ORDER BY date DESC");
$healthRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Milk Production Records
$stmt = $pdo->query("SELECT * FROM milk_production ORDER BY date DESC");
$milkRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Feed Consumption Records
$stmt = $pdo->query("SELECT * FROM feed_consumption ORDER BY date DESC");
$feedRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Reports</h2>
        <ul class="nav nav-tabs" id="reportTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#health">Health Records</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#milk">Milk Production</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#feed">Feed Consumption</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="health">
                <h4>Health Records</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Cow ID</th>
                            <th>Diagnosis</th>
                            <th>Treatment</th>
                            <th>Veterinarian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($healthRecords as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['cow_id']); ?></td>
                                <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                                <td><?php echo htmlspecialchars($record['treatment']); ?></td>
                                <td><?php echo htmlspecialchars($record['veterinarian']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="milk">
                <h4>Milk Production</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Cow ID</th>
                            <th>Quantity (Liters)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($milkRecords as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['cow_id']); ?></td>
                                <td><?php echo htmlspecialchars($record['quantity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="feed">
                <h4>Feed Consumption</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Cow ID</th>
                            <th>Feed Type</th>
                            <th>Quantity (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feedRecords as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['cow_id']); ?></td>
                                <td><?php echo htmlspecialchars($record['feed_type']); ?></td>
                                <td><?php echo htmlspecialchars($record['quantity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

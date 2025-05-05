<?php
require_once('../config/database.php');
require_once('../functions.php');
require_once('../models/MilkProduction.php');
require_once('../models/FeedRecord.php');
require_once('../models/HealthRecord.php');
require_once('../models/Cow.php');

requireLogin();

$milkProduction = new MilkProduction($pdo);
$feedRecord = new FeedRecord($pdo);
$healthRecord = new HealthRecord($pdo);
$cow = new Cow($pdo);

$milkRecords = $milkProduction->getMilkRecords();
$feedRecords = $feedRecord->getFeedRecords();
$healthRecords = $healthRecord->getHealthRecords();
$cows = $cow->getCowsFiltered();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dairy Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-area, .print-area * {
                visibility: visible;
            }
            .print-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }
            table th, table td {
                border: 1px solid black;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Dairy Reports</h2>
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#milk">Milk Production</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#feed">Feed Records</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#health">Health Records</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#cows">Cow Details</a></li>
    </ul>

    <div class="tab-content mt-3 print-area">
        <div class="tab-pane fade show active" id="milk">
            <h4>Milk Production Report</h4>
            <table class="table table-bordered">
                <thead><tr><th>Date</th><th>Cow ID</th><th>Milk Yield (L)</th></tr></thead>
                <tbody>
                    <?php foreach ($milkRecords as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['date']) ?></td>
                            <td><?= htmlspecialchars($record['cow_id']) ?></td>
                            <td><?= htmlspecialchars($record['milk_yield']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="feed">
            <h4>Feed Records Report</h4>
            <table class="table table-bordered">
                <thead><tr><th>Date</th><th>Cow ID</th><th>Feed Type</th><th>Quantity</th></tr></thead>
                <tbody>
                    <?php foreach ($feedRecords as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['date']) ?></td>
                            <td><?= htmlspecialchars($record['cow_id']) ?></td>
                            <td><?= htmlspecialchars($record['feed_type']) ?></td>
                            <td><?= htmlspecialchars($record['quantity']) ?> <?= htmlspecialchars($record['unit']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="health">
            <h4>Health Records Report</h4>
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
                            <td><?= htmlspecialchars($record['date']) ?></td>
                            <td><?= htmlspecialchars($record['cow_id']) ?></td>
                            <td><?= htmlspecialchars($record['diagnosis']) ?></td>
                            <td><?= htmlspecialchars($record['treatment']) ?></td>
                            <td><?= htmlspecialchars($record['veterinarian']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="cows">
            <h4>Cow Details Report</h4>
             <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Cow ID</th>
                            <th>Name</th>
                            <th>Breed</th>
                            <th>Date of Birth</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cows as $cow_data): ?>
                            <tr>
                                 <td><?= htmlspecialchars($cow_data['id'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($cow_data['name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($cow_data['breed'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($cow_data['date_of_birth'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    </div>

    <button class="btn btn-primary mt-3" onclick="window.print()">Print Report</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

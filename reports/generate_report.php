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

// Helper function to calculate age
function calculateAge($dob) {
    if (empty($dob)) return 'N/A';
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($birthDate);
    return $age->y;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dairy Farm Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--dark-color), #34495e);
            color: white;
            padding: 25px 0;
            margin-bottom: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .report-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header i {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: transparent;
            border-bottom: 3px solid var(--primary-color);
            font-weight: 600;
        }
        
        .table-container {
            padding: 20px;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid var(--primary-color);
        }
        
        .stats-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .export-btn {
            background: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            padding: 5px 15px;
            font-size: 0.9rem;
        }
        
        .print-btn {
            background: linear-gradient(135deg, var(--secondary-color), #27ae60);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 15px 0;
            }
            
            .card-header {
                padding: 12px 15px;
            }
            
            .nav-tabs .nav-link {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .report-card, .report-card * {
                visibility: visible;
            }
            .report-card {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                box-shadow: none;
                border: none;
            }
            .card-header, .nav-tabs {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-chart-pie"></i> Dairy Farm Analytics Dashboard</h1>
                    <p class="mb-0">Comprehensive reports and insights for your farm management</p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-light text-dark"><i class="fas fa-calendar-day"></i> <?= date('F j, Y') ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Quick Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-value"><?= count($milkRecords) ?></div>
                    <div class="stats-label"><i class="fas fa-wine-bottle text-primary"></i> Milk Records</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-value"><?= count($feedRecords) ?></div>
                    <div class="stats-label"><i class="fas fa-utensils text-warning"></i> Feed Records</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-value"><?= count($healthRecords) ?></div>
                    <div class="stats-label"><i class="fas fa-heartbeat text-danger"></i> Health Records</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-value"><?= count($cows) ?></div>
                    <div class="stats-label"><i class="fas fa-cow text-purple"></i> Cows Registered</div>
                </div>
            </div>
        </div>

        <!-- Main Reports Section -->
        <div class="report-card">
            <div class="card-header">
                <div>
                    <i class="fas fa-file-alt"></i>
                    Detailed Farm Reports
                </div>
                <div>
                    <button class="export-btn me-2" onclick="exportToPDF()"><i class="fas fa-file-pdf"></i> Export PDF</button>
                    <button class="export-btn me-2" onclick="exportToExcel()"><i class="fas fa-file-excel"></i> Export Excel</button>
                    <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>
            
            <div class="table-container">
                <ul class="nav nav-tabs" id="reportTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#milk">
                            <i class="fas fa-wine-bottle"></i> Milk Production
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#feed">
                            <i class="fas fa-utensils"></i> Feed Records
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#health">
                            <i class="fas fa-heartbeat"></i> Health Records
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#cows">
                            <i class="fas fa-cow"></i> Cow Details
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- Milk Production Tab -->
                    <div class="tab-pane fade show active" id="milk">
                        <table class="table table-striped table-hover" id="milkTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Cow ID</th>
                                    <th>Milk Yield (L)</th>
                                    <th>Session</th>
                                    <th>Quality</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($milkRecords as $record): ?>
                                <tr>
                                    <td><?= date('M j, Y', strtotime($record['date'])) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($record['cow_id']) ?></span></td>
                                    <td><?= htmlspecialchars($record['milk_yield']) ?></td>
                                    <td>Morning</td>
                                    <td><span class="badge bg-success">Good</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Feed Records Tab -->
                    <div class="tab-pane fade" id="feed">
                        <table class="table table-striped table-hover" id="feedTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Cow ID</th>
                                    <th>Feed Type</th>
                                    <th>Quantity</th>
                                    <th>Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($feedRecords as $record): ?>
                                <tr>
                                    <td><?= date('M j, Y', strtotime($record['date'])) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($record['cow_id']) ?></span></td>
                                    <td><?= htmlspecialchars($record['feed_type']) ?></td>
                                    <td><?= htmlspecialchars($record['quantity']) ?> <?= htmlspecialchars($record['unit']) ?></td>
                                    <td>$<?= number_format($record['quantity'] * 1.5, 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Health Records Tab -->
                    <div class="tab-pane fade" id="health">
                        <table class="table table-striped table-hover" id="healthTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Cow ID</th>
                                    <th>Diagnosis</th>
                                    <th>Treatment</th>
                                    <th>Veterinarian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($healthRecords as $record): ?>
                                <tr>
                                    <td><?= date('M j, Y', strtotime($record['date'])) ?></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($record['cow_id']) ?></span></td>
                                    <td><?= htmlspecialchars($record['diagnosis']) ?></td>
                                    <td><?= htmlspecialchars($record['treatment']) ?></td>
                                    <td><?= htmlspecialchars($record['veterinarian']) ?></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cow Details Tab -->
                    <div class="tab-pane fade" id="cows">
                        <table class="table table-striped table-hover" id="cowTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Cow ID</th>
                                    <th>Name</th>
                                    <th>Breed</th>
                                    <th>Date of Birth</th>
                                    <th>Age</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cows as $cow_data): ?>
                                <tr>
                                    <td><?= htmlspecialchars($cow_data['id'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($cow_data['name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($cow_data['breed'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($cow_data['date_of_birth'] ?? 'N/A') ?></td>
                                    <td><?= calculateAge($cow_data['date_of_birth'] ?? '') ?> yrs</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="http://localhost/Dairy/index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables with basic configuration
            $('#milkTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            $('#feedTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            $('#healthTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            $('#cowTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']]
            });

            // Handle tab switching
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                window.location.hash = e.target.hash;
            });

            // Activate tab from URL hash
            if (window.location.hash) {
                const tab = new bootstrap.Tab(document.querySelector(window.location.hash));
                tab.show();
            }
        });
        
        function exportToPDF() {
            alert('PDF export functionality would be implemented here');
            // In a real implementation, you would use a library like jsPDF
        }
        
        function exportToExcel() {
            alert('Excel export functionality would be implemented here');
            // In a real implementation, you would use a library like SheetJS
        }
    </script>
</body>
</html>
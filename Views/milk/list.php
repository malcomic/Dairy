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

// Calculate statistics
$totalYield = 0;
$averageYield = 0;
$recordCount = count($records);

if ($recordCount > 0) {
    $totalYield = array_sum(array_column($records, 'milk_yield'));
    $averageYield = $totalYield / $recordCount;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Production Dashboard | Dairy Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --milk-color: #f6c23e;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }
        
        body {
            background-color: var(--light-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-image: linear-gradient(180deg, var(--light-color) 10%, #e9f5fb 100%);
            min-height: 100vh;
        }
        
        .dashboard-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin: 3rem auto;
            max-width: 1200px;
        }
        
        .page-header {
            color: var(--dark-color);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .page-header h2 {
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .page-header h2 i {
            color: var(--milk-color);
            margin-right: 10px;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--milk-color);
        }
        
        .stats-card h5 {
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .stats-card h5 i {
            margin-right: 10px;
            color: var(--milk-color);
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--dark-color);
            opacity: 0.8;
        }
        
        .filter-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .filter-card h5 {
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .filter-card h5 i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
        }
        
        .btn-secondary {
            background-color: #858796;
            border: none;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }
        
        .table tbody tr {
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e3e6f0;
        }
        
        .yield-high {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .yield-low {
            color: var(--milk-color);
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--dark-color);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #d1d3e2;
            margin-bottom: 1rem;
        }
        
        .empty-state h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .badge-date {
            background-color: #f8f9fc;
            color: var(--dark-color);
            font-weight: 600;
            padding: 0.35rem 0.65rem;
            border-radius: 50px;
            border: 1px solid #e3e6f0;
        }
        
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-header .btn {
                margin-top: 1rem;
            }
            
            .stats-row .col-md-4 {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="page-header">
            <h2><i class="fas fa-jug"></i> Milk Production Records</h2>
            <a href="add.php" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add New Record
            </a>
        </div>

        <div class="row stats-row">
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-list"></i> Total Records</h5>
                    <div class="stat-value"><?php echo $recordCount; ?></div>
                    <div class="stat-label">Milk production entries</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-weight-hanging"></i> Total Yield</h5>
                    <div class="stat-value"><?php echo number_format($totalYield, 2); ?> L</div>
                    <div class="stat-label">Across all records</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-calculator"></i> Average Yield</h5>
                    <div class="stat-value"><?php echo $recordCount > 0 ? number_format($averageYield, 2) : '0.00'; ?> L</div>
                    <div class="stat-label">Per milking session</div>
                </div>
            </div>
        </div>

        <div class="filter-card">
            <h5><i class="fas fa-filter"></i> Filter Records</h5>
            <form method="get" action="list.php">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="cow_id" class="form-label">Cow ID</label>
                        <input type="number" name="cow_id" id="cow_id" class="form-control" 
                               placeholder="Enter cow ID" 
                               value="<?php echo htmlspecialchars($cowId ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" 
                               value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" 
                               value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-2"></i> Apply Filters
                        </button>
                        <a href="list.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-2"></i>Cow ID</th>
                        <th><i class="fas fa-calendar-day me-2"></i>Date</th>
                        <th><i class="fas fa-weight-hanging me-2"></i>Milk Yield (L)</th>
                        <th><i class="fas fa-sticky-note me-2"></i>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-jug"></i>
                                    <h4>No Milk Records Found</h4>
                                    <p>Try adjusting your filters or add new records.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($record['cow_id']); ?></strong></td>
                                <td>
                                    <span class="badge-date">
                                        <?php echo date('M j, Y', strtotime($record['date'])); ?>
                                    </span>
                                </td>
                                <td class="<?php echo $record['milk_yield'] > $averageYield ? 'yield-high' : 'yield-low'; ?>">
                                    <?php echo number_format($record['milk_yield'], 2); ?> L
                                </td>
                                <td><?php echo htmlspecialchars($record['notes'] ?: '—'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button onclick="printReport()" class="btn btn-secondary">
                <i class="fas fa-print me-2"></i> Print Report
            </button>
            <a href="http://localhost/Dairy/index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set default date range to last 30 days if no dates are selected
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            
            if (!startDateInput.value && !endDateInput.value) {
                const today = new Date();
                const lastMonth = new Date();
                lastMonth.setDate(today.getDate() - 30);
                
                endDateInput.valueAsDate = today;
                startDateInput.valueAsDate = lastMonth;
            }
        });
    </script>
</body>
</html>
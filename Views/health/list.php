<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/HealthRecord.php';
require_once '../../models/Cow.php';

$healthRecord = new HealthRecord($pdo);
$cow = new Cow($pdo);

// Get filter parameters from the query string
$cowId = isset($_GET['cow_id']) ? (int)$_GET['cow_id'] : null;
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;

$records = $healthRecord->getHealthRecords($cowId, $startDate, $endDate);

// Get cow names for display
$cowNames = [];
try {
    $stmt = $pdo->query("SELECT id, cow_id, name FROM cows");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cowNames[$row['id']] = $row['name'] . ' (ID: ' . $row['cow_id'] . ')';
    }
} catch (PDOException $e) {
    error_log("Error fetching cow names: " . $e->getMessage());
}

// Calculate statistics
$recordCount = count($records);
$recentRecords = array_slice($records, 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records Dashboard | Dairy Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --danger-color: #e74a3b;
            --health-color: #36b9cc;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }
        
        body {
            background-color: var(--light-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-image: linear-gradient(180deg, var(--light-color) 10%, #e8f7fa 100%);
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
            color: var(--health-color);
            margin-right: 10px;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--health-color);
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
            color: var(--health-color);
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
        
        .btn-health {
            background-color: var(--health-color);
            border: none;
            color: white;
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
            background-color: var(--health-color);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }
        
        .table tbody tr {
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(54, 185, 204, 0.05);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e3e6f0;
        }
        
        .badge-date {
            background-color: #f8f9fc;
            color: var(--dark-color);
            font-weight: 600;
            padding: 0.35rem 0.65rem;
            border-radius: 50px;
            border: 1px solid #e3e6f0;
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
        
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            margin-right: 5px;
        }
        
        .diagnosis-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .diagnosis-urgent {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--danger-color);
        }
        
        .diagnosis-routine {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--secondary-color);
        }
        
        .treatment-preview {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            
            .treatment-preview {
                max-width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="page-header">
            <h2><i class="fas fa-heartbeat"></i> Health Records Dashboard</h2>
            <a href="add.php" class="btn btn-health">
                <i class="fas fa-plus-circle me-2"></i> Add New Record
            </a>
        </div>

        <div class="row stats-row">
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-list"></i> Total Records</h5>
                    <div class="stat-value"><?php echo $recordCount; ?></div>
                    <div class="stat-label">Health records in system</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-clock"></i> Recent Activity</h5>
                    <div class="stat-value"><?php echo min(5, $recordCount); ?></div>
                    <div class="stat-label">Most recent records</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-cow"></i> Cows Tracked</h5>
                    <div class="stat-value">
                        <?php 
                        if ($recordCount > 0) {
                            $uniqueCows = array_unique(array_column($records, 'cow_id'));
                            echo count($uniqueCows);
                        } else {
                            echo '0';
                        }
                        ?>
                    </div>
                    <div class="stat-label">With health records</div>
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
                        <th><i class="fas fa-hashtag me-2"></i>Record ID</th>
                        <th><i class="fas fa-cow me-2"></i>Cow</th>
                        <th><i class="fas fa-calendar-day me-2"></i>Date</th>
                        <th><i class="fas fa-diagnoses me-2"></i>Diagnosis</th>
                        <th><i class="fas fa-pills me-2"></i>Treatment</th>
                        <th><i class="fas fa-user-md me-2"></i>Veterinarian</th>
                        <th><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-heartbeat"></i>
                                    <h4>No Health Records Found</h4>
                                    <p>Try adjusting your filters or add new records.</p>
                                    <a href="add.php" class="btn btn-health mt-2">
                                        <i class="fas fa-plus-circle me-2"></i> Add New Record
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['id']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($cowNames[$record['cow_id']] ?? $record['cow_id']); ?></strong>
                                </td>
                                <td>
                                    <span class="badge-date">
                                        <?php echo date('M j, Y', strtotime($record['date'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="diagnosis-badge <?php echo strpos(strtolower($record['diagnosis']), 'urgent') !== false ? 'diagnosis-urgent' : 'diagnosis-routine'; ?>">
                                        <?php echo htmlspecialchars($record['diagnosis']); ?>
                                    </span>
                                </td>
                                <td class="treatment-preview" title="<?php echo htmlspecialchars($record['treatment']); ?>">
                                    <?php echo htmlspecialchars(substr($record['treatment'], 0, 50) . (strlen($record['treatment']) > 50 ? '...' : '')); ?>
                                </td>
                                <td><?php echo htmlspecialchars($record['veterinarian']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit.php?id=<?php echo $record['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $record['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this health record?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="view.php?id=<?php echo $record['id']; ?>" class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
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
            
            // Add tooltips for treatment previews
            const treatmentPreviews = document.querySelectorAll('.treatment-preview');
            treatmentPreviews.forEach(el => {
                if (el.scrollWidth > el.offsetWidth) {
                    el.setAttribute('data-bs-toggle', 'tooltip');
                }
            });
            
            // Initialize Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>
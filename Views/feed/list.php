<?php
require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/FeedRecord.php';
require_once '../../models/Cow.php';

$feedRecord = new FeedRecord($pdo);
$cow = new Cow($pdo);

$feeds = $feedRecord->getFeedRecords();

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
$totalRecords = count($feeds);
$totalQuantity = array_sum(array_column($feeds, 'quantity'));
$uniqueFeedTypes = array_unique(array_column($feeds, 'feed_type'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed Management Dashboard | Dairy System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --feed-color: #f6c23e;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }
        
        body {
            background-color: var(--light-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-image: linear-gradient(180deg, var(--light-color) 10%, #f5f9e8 100%);
            min-height: 100vh;
        }
        
        .dashboard-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin: 3rem auto;
            max-width: 1200px;
            border-top: 5px solid var(--feed-color);
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
            color: var(--feed-color);
            margin-right: 10px;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--feed-color);
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
            color: var(--feed-color);
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
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--feed-color);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }
        
        .table tbody tr {
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(246, 194, 62, 0.05);
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
        
        .feed-type-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            background-color: rgba(246, 194, 62, 0.1);
            color: var(--dark-color);
        }
        
        .quantity-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--secondary-color);
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
        
        .btn-feed {
            background-color: var(--feed-color);
            border: none;
            color: #333;
            font-weight: 600;
        }
        
        .btn-feed:hover {
            background-color: #e0b52e;
            color: #333;
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
            <h2><i class="fas fa-utensils"></i> Feed Management Dashboard</h2>
            <a href="add.php" class="btn btn-feed">
                <i class="fas fa-plus-circle me-2"></i> Add New Record
            </a>
        </div>

        <div class="row stats-row">
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-list"></i> Total Records</h5>
                    <div class="stat-value"><?php echo $totalRecords; ?></div>
                    <div class="stat-label">Feed records in system</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-weight-hanging"></i> Total Quantity</h5>
                    <div class="stat-value"><?php echo number_format($totalQuantity, 2); ?></div>
                    <div class="stat-label">Total feed distributed</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-seedling"></i> Feed Types</h5>
                    <div class="stat-value"><?php echo count($uniqueFeedTypes); ?></div>
                    <div class="stat-label">Different types used</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-2"></i>Record ID</th>
                        <th><i class="fas fa-cow me-2"></i>Cow</th>
                        <th><i class="fas fa-seedling me-2"></i>Feed Type</th>
                        <th><i class="fas fa-weight me-2"></i>Quantity</th>
                        <th><i class="fas fa-ruler me-2"></i>Unit</th>
                        <th><i class="fas fa-calendar-day me-2"></i>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($feeds)): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-utensils"></i>
                                    <h4>No Feed Records Found</h4>
                                    <p>Add new feed records to get started.</p>
                                    <a href="add.php" class="btn btn-feed mt-2">
                                        <i class="fas fa-plus-circle me-2"></i> Add New Record
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($feeds as $feedData): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($feedData['id']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($cowNames[$feedData['cow_id']] ?? $feedData['cow_id']); ?></strong>
                                </td>
                                <td>
                                    <span class="feed-type-badge">
                                        <?php echo htmlspecialchars($feedData['feed_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="quantity-badge">
                                        <?php echo htmlspecialchars($feedData['quantity']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($feedData['unit']); ?></td>
                                <td>
                                    <span class="badge-date">
                                        <?php echo date('M j, Y', strtotime($feedData['date'])); ?>
                                    </span>
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
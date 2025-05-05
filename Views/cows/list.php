<?php
require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/Cow.php';

$cow = new Cow($pdo);

$search = $_GET['search'] ?? null;
$breed = $_GET['breed'] ?? null;
$ageMin = $_GET['age_min'] ?? null;
$ageMax = $_GET['age_max'] ?? null;

$cows = $cow->getCowsFiltered($search, $breed, $ageMin, $ageMax);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cow Management System | Herd List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
        }
        
        body {
            background-color: var(--light-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-image: linear-gradient(180deg, var(--light-color) 10%, #dbe5f8 100%);
            background-size: cover;
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
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-header h2 {
            margin: 0;
            font-weight: 700;
        }
        
        .page-header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 3px;
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
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            transform: translateX(2px);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e3e6f0;
        }
        
        .health-status {
            display: inline-block;
            padding: 0.35rem 0.5rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .health-status.healthy {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--secondary-color);
        }
        
        .health-status.sick {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--danger-color);
        }
        
        .health-status.injured {
            background-color: rgba(246, 194, 62, 0.1);
            color: var(--warning-color);
        }
        
        .health-status.other {
            background-color: rgba(133, 135, 150, 0.1);
            color: var(--dark-color);
        }
        
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            margin-right: 5px;
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
        
        .badge-age {
            background-color: #f8f9fc;
            color: var(--dark-color);
            font-weight: 600;
            padding: 0.35rem 0.65rem;
            border-radius: 50px;
            border: 1px solid #e3e6f0;
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="page-header">
            <h2><i class="fas fa-list"></i> Herd Management</h2>
            <a href="add.php" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Add New Cow
            </a>
        </div>

        <div class="filter-card">
            <h5><i class="fas fa-filter"></i> Filter Herd</h5>
            <form method="get" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Search by ID or Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Enter cow ID or name..." 
                                   value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="breed" class="form-label">Filter by Breed</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dna"></i></span>
                            <input type="text" name="breed" id="breed" class="form-control" 
                                   placeholder="Enter breed name..." 
                                   value="<?php echo htmlspecialchars($breed ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="age_min" class="form-label">Minimum Age</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-minus"></i></span>
                            <input type="number" name="age_min" id="age_min" class="form-control" 
                                   placeholder="Min" min="0" max="30"
                                   value="<?php echo htmlspecialchars($ageMin ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="age_max" class="form-label">Maximum Age</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                            <input type="number" name="age_max" id="age_max" class="form-control" 
                                   placeholder="Max" min="0" max="30"
                                   value="<?php echo htmlspecialchars($ageMax ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end mb-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-2"></i> Apply Filters
                        </button>
                        <a href="list.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-2"></i>ID</th>
                        <th><i class="fas fa-id-card me-2"></i>Cow ID</th>
                        <th><i class="fas fa-cow me-2"></i>Name</th>
                        <th><i class="fas fa-dna me-2"></i>Breed</th>
                        <th><i class="fas fa-birthday-cake me-2"></i>Date of Birth</th>
                        <th><i class="fas fa-calendar-alt me-2"></i>Age</th>
                        <th><i class="fas fa-heartbeat me-2"></i>Health Status</th>
                        <th><i class="fas fa-cog me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cows)): ?>
                        <?php foreach ($cows as $aCow): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($aCow['id']); ?></td>
                                <td><strong><?php echo htmlspecialchars($aCow['cow_id']); ?></strong></td>
                                <td><?php echo htmlspecialchars($aCow['name'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($aCow['breed']); ?></td>
                                <td><?php echo htmlspecialchars($aCow['date_of_birth']); ?></td>
                                <td><span class="badge-age"><?php echo htmlspecialchars($aCow['age']); ?> yrs</span></td>
                                <td>
                                    <span class="health-status <?php echo strtolower(htmlspecialchars($aCow['health_status'])); ?>">
                                        <i class="fas <?php 
                                            echo $aCow['health_status'] === 'Healthy' ? 'fa-heartbeat' : 
                                                 ($aCow['health_status'] === 'Sick' ? 'fa-hospital' : 
                                                 ($aCow['health_status'] === 'Injured' ? 'fa-bandaid' : 'fa-question-circle')); 
                                        ?> me-1"></i>
                                        <?php echo htmlspecialchars($aCow['health_status']); ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="edit.php?id=<?php echo $aCow['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $aCow['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this cow?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="view.php?id=<?php echo $aCow['id']; ?>" class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-cow"></i>
                                    <h4>No Cows Found</h4>
                                    <p>Try adjusting your search filters or add a new cow to the herd.</p>
                                    <a href="add.php" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus-circle me-2"></i> Add New Cow
                                    </a>
                                </div>
                            </td>
                        </tr>
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
        // Enhance user experience with small animations
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('.table tbody tr');
            
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>
</html>
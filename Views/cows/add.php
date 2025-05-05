<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';

// Fetch breeds from the database
$breeds = [];
try {
    $stmt = $pdo->query("SELECT breed_name FROM breeds");
    $breeds = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    error_log("Database error (breeds): " . $e->getMessage());
    echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cow_id = trim($_POST['cow_id']);
    $name = trim($_POST['name']);
    $breed = trim($_POST['breed']);
    $age = (int)$_POST['age'];
    $health_status = trim($_POST['health_status']);

    // Validation
    if (empty($cow_id) || empty($breed) || $age <= 0 || empty($health_status)) {
        echo "<div class='alert alert-warning'>Please fill in all fields correctly.</div>";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO cows (cow_id, name, breed, age, health_status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$cow_id, $name, $breed, $age, $health_status]);

            echo "<div class='alert alert-success'>Cow added successfully! Redirecting...</div>";
            echo "<script>setTimeout(function(){ window.location.href = 'list.php'; }, 1500);</script>";
        } catch (PDOException $e) {
            error_log("Database error (add cow): " . $e->getMessage());
            echo "<div class='alert alert-danger'>Error adding cow: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Cow | Livestock Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
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
        
        .form-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin: 3rem auto;
            max-width: 700px;
            transition: all 0.3s;
        }
        
        .form-container:hover {
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .page-header {
            color: var(--dark-color);
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .page-header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            transform: translateY(-2px);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .icon-input {
            position: relative;
        }
        
        .icon-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #b7b9cc;
        }
        
        .icon-input input, .icon-input select {
            padding-left: 45px;
        }
        
        .brand-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .brand-logo img {
            height: 60px;
        }
        
        .health-status-option {
            display: flex;
            align-items: center;
            padding: 8px 12px;
        }
        
        .health-status-icon {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .healthy { color: #1cc88a; }
        .sick { color: #e74a3b; }
        .injured { color: #f6c23e; }
        .other { color: #858796; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="brand-logo">
                <img src="https://via.placeholder.com/200x60?text=Livestock+Tracker" alt="Livestock Tracker Logo">
            </div>
            
            <h1 class="page-header">Add New Cow</h1>
            
            <form method="post" action="add.php">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="cow_id" class="form-label">Cow ID</label>
                        <div class="icon-input">
                            <i class="fas fa-hashtag"></i>
                            <input type="text" name="cow_id" class="form-control" id="cow_id" placeholder="Enter unique ID" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="name" class="form-label">Cow Name</label>
                        <div class="icon-input">
                            <i class="fas fa-cow"></i>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Optional name">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="breed" class="form-label">Breed</label>
                        <div class="icon-input">
                            <i class="fas fa-dna"></i>
                            <select name="breed" id="breed" class="form-select" required>
                                <option value="">Select Breed</option>
                                <?php if (!empty($breeds)): ?>
                                    <?php foreach ($breeds as $breed): ?>
                                        <option value="<?php echo htmlspecialchars($breed); ?>"><?php echo htmlspecialchars($breed); ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No breeds found</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <label for="age" class="form-label">Age (years)</label>
                        <div class="icon-input">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="number" name="age" id="age" class="form-control" min="0" max="30" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="health_status" class="form-label">Health Status</label>
                    <select name="health_status" id="health_status" class="form-select" required>
                        <option value="Healthy">
                            <div class="health-status-option">
                                <i class="fas fa-heartbeat health-status-icon healthy"></i>
                                <span>Healthy</span>
                            </div>
                        </option>
                        <option value="Sick">
                            <div class="health-status-option">
                                <i class="fas fa-hospital health-status-icon sick"></i>
                                <span>Sick</span>
                            </div>
                        </option>
                        <option value="Injured">
                            <div class="health-status-option">
                                <i class="fas fa-bandaid health-status-icon injured"></i>
                                <span>Injured</span>
                            </div>
                        </option>
                        <option value="Other">
                            <div class="health-status-option">
                                <i class="fas fa-question-circle health-status-icon other"></i>
                                <span>Other</span>
                            </div>
                        </option>
                    </select>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Add Cow to Herd
                    </button>
                    <a href="list.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Cow List
                    </a>
                </div>
            </form>
            <div class="d-flex justify-content-between mt-4">
            <button onclick="printReport()" class="btn btn-secondary">
                <i class="fas fa-print me-2"></i> Print Report
            </button>
            <a href="http://localhost/Dairy/index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhance select elements with icons
        document.querySelectorAll('.form-select').forEach(select => {
            select.addEventListener('change', function() {
                const icon = this.previousElementSibling;
                if (this.value === 'Healthy') {
                    icon.className = 'fas fa-heartbeat health-status-icon healthy';
                } else if (this.value === 'Sick') {
                    icon.className = 'fas fa-hospital health-status-icon sick';
                } else if (this.value === 'Injured') {
                    icon.className = 'fas fa-bandaid health-status-icon injured';
                } else {
                    icon.className = 'fas fa-question-circle health-status-icon other';
                }
            });
        });
    </script>
</body>
</html>
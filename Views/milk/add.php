<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/MilkProduction.php';

$milkProduction = new MilkProduction($pdo);

// Fetch cows for dropdown using cow_id as the value
$cows = [];
try {
    $stmt = $pdo->query("SELECT cow_id, name FROM cows ORDER BY name");
    $cows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error (fetching cows): " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cowId = $_POST['cow_id'];
    $date = $_POST['date'];
    $milkYield = $_POST['milk_yield'];
    $notes = $_POST['notes'];

    if (empty($cowId) || empty($date) || empty($milkYield) || $milkYield < 0) {
        $error = "Please fill in all fields correctly. Milk yield must be 0 or greater.";
    } else {
        // Debug output
        error_log("Attempting to add milk record - CowID: $cowId, Date: $date, Yield: $milkYield");
        
        if ($milkProduction->addMilkRecord($cowId, $date, $milkYield, $notes)) {
            $success = "Milk record added successfully!";
            echo "<script>setTimeout(function(){ window.location.href = 'list.php'; }, 1500);</script>";
        } else {
            $error = "Error adding milk record. Please check the cow ID exists.";
            error_log("Failed to add milk record for cow ID: $cowId");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Milk Record | Dairy Management</title>
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
        
        .milk-form-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin: 3rem auto;
            max-width: 700px;
            transition: all 0.3s;
            border-top: 5px solid var(--milk-color);
        }
        
        .milk-form-container:hover {
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
            background: var(--milk-color);
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--milk-color);
            box-shadow: 0 0 0 0.25rem rgba(246, 194, 62, 0.25);
        }
        
        .btn-milk {
            background-color: var(--milk-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #333;
        }
        
        .btn-milk:hover {
            background-color: #e0b52e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #333;
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
        
        .alert-success {
            background-color: rgba(28, 200, 138, 0.1);
            border-color: rgba(28, 200, 138, 0.3);
            color: var(--secondary-color);
        }
        
        .alert-danger {
            background-color: rgba(231, 74, 59, 0.1);
            border-color: rgba(231, 74, 59, 0.3);
            color: var(--danger-color);
        }
        
        .milk-graphic {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .milk-graphic i {
            font-size: 3rem;
            color: var(--milk-color);
            background: rgba(246, 194, 62, 0.2);
            padding: 1.5rem;
            border-radius: 50%;
        }
        
        .input-group-text {
            background-color: #f8f9fc;
            border-color: #d1d3e2;
        }
        
        .notes-counter {
            font-size: 0.8rem;
            text-align: right;
            color: #858796;
            margin-top: -10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="milk-form-container">
            <div class="milk-graphic">
                <i class="fas fa-jug"></i>
            </div>
            
            <h2 class="page-header">Add Milk Production Record</h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <form method="post" action="add.php">
                <div class="mb-4">
                    <label for="cow_id" class="form-label">Select Cow</label>
                    <div class="icon-input">
                        <i class="fas fa-cow"></i>
                        <select name="cow_id" id="cow_id" class="form-select" required>
                            <option value="">-- Select Cow --</option>
                            <?php foreach ($cows as $cow): ?>
                                <option value="<?php echo htmlspecialchars($cow['cow_id']); ?>" <?php echo isset($_POST['cow_id']) && $_POST['cow_id'] == $cow['cow_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cow['name'] . ' (ID: ' . $cow['cow_id'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="date" class="form-label">Date of Production</label>
                    <div class="icon-input">
                        <i class="fas fa-calendar-day"></i>
                        <input type="date" name="date" class="form-control" id="date" required 
                               value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : date('Y-m-d'); ?>" 
                               min="2020-01-01" max="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="milk_yield" class="form-label">Milk Yield (Liters)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-weight"></i></span>
                        <input type="number" name="milk_yield" class="form-control" id="milk_yield" 
                               step="0.01" min="0" required placeholder="Enter amount in liters"
                               value="<?php echo isset($_POST['milk_yield']) ? htmlspecialchars($_POST['milk_yield']) : ''; ?>">
                        <span class="input-group-text">liters</span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" id="notes" rows="3" 
                              placeholder="Any special observations about this milk production..."><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                    <div class="notes-counter"><span id="notes-count">0</span>/200 characters</div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-milk">
                        <i class="fas fa-save me-2"></i> Save Milk Record
                    </button>
                    <a href="list.php" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i> View All Records
                    </a>
                </div>
            </form>
            <div class="d-flex justify-content-between mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-2" style="color: var(--milk-color);"></i>
            
            <a href="http://localhost/Dairy/index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Character counter for notes
        const notesTextarea = document.getElementById('notes');
        const notesCounter = document.getElementById('notes-count');
        
        notesTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            notesCounter.textContent = currentLength;
            
            if (currentLength > 200) {
                notesCounter.style.color = 'red';
                this.value = this.value.substring(0, 200);
            } else {
                notesCounter.style.color = '#858796';
            }
        });
        
        // Set today's date as default if not already set
        if (!document.getElementById('date').value) {
            document.getElementById('date').valueAsDate = new Date();
        }
    </script>
</body>
</html>
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

// Fetch cows for dropdown using cow_id
$cows = [];
try {
    $stmt = $pdo->query("SELECT cow_id, name FROM cows ORDER BY name");
    $cows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error (fetching cows): " . $e->getMessage());
    $error = "Error loading cow data. Please try again later.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cowId = $_POST['cow_id'];
    $date = $_POST['date'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $veterinarian = $_POST['veterinarian'];

    // Validate required fields
    if (empty($cowId) || empty($date) || empty($diagnosis) || empty($treatment) || empty($veterinarian)) {
        $error = "Please fill in all required fields.";
    } else {
        try {
            // Verify cow exists
            $stmt = $pdo->prepare("SELECT cow_id FROM cows WHERE cow_id = ?");
            $stmt->execute([$cowId]);
            if (!$stmt->fetch()) {
                throw new Exception("Selected cow does not exist");
            }

            // Add health record
            $result = $healthRecord->addHealthRecord(
                $cowId,
                $date,
                $diagnosis,
                $treatment,
                $veterinarian
            );

            if ($result) {
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'message' => 'Health record added successfully!'
                ];
                header("Location: list.php");
                exit();
            } else {
                throw new Exception("Failed to add health record. Check database logs for details.");
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            error_log("Health record PDO error: " . $e->getMessage());
        } catch (Exception $e) {
            $error = $e->getMessage();
            error_log("Health record error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Health Record | Dairy Management</title>
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
        
        .health-form-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin: 3rem auto;
            max-width: 800px;
            border-top: 5px solid var(--health-color);
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
            background: var(--health-color);
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
            border-color: var(--health-color);
            box-shadow: 0 0 0 0.25rem rgba(54, 185, 204, 0.25);
        }
        
        .btn-health {
            background-color: var(--health-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }
        
        .btn-health:hover {
            background-color: #2a9faf;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
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
        
        .health-graphic {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .health-graphic i {
            font-size: 3rem;
            color: var(--health-color);
            background: rgba(54, 185, 204, 0.2);
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
        
        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="health-form-container">
            <div class="health-graphic">
                <i class="fas fa-heartbeat"></i>
            </div>
            
            <h2 class="page-header">Add Health Record</h2>
            
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show">
                    <i class="fas fa-<?php echo $_SESSION['flash_message']['type'] === 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                    <?php echo htmlspecialchars($_SESSION['flash_message']['message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <form method="post" action="add.php">
                <div class="form-section">
                    <h5><i class="fas fa-cow me-2"></i> Cow Information</h5>
                    <div class="mb-4">
                        <label for="cow_id" class="form-label">Select Cow</label>
                        <div class="icon-input">
                            <i class="fas fa-hashtag"></i>
                            <select name="cow_id" id="cow_id" class="form-select" required>
                                <option value="">-- Select Cow --</option>
                                <?php foreach ($cows as $cow): ?>
                                    <option value="<?php echo htmlspecialchars($cow['cow_id']); ?>"
                                        <?php echo isset($_POST['cow_id']) && $_POST['cow_id'] == $cow['cow_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cow['name'] . ' (ID: ' . $cow['cow_id'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h5><i class="fas fa-calendar-day me-2"></i> Examination Details</h5>
                    <div class="mb-4">
                        <label for="date" class="form-label">Date of Examination</label>
                        <div class="icon-input">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" name="date" class="form-control" id="date" required 
                                   value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : date('Y-m-d'); ?>" 
                                   min="2020-01-01" max="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="veterinarian" class="form-label">Veterinarian</label>
                        <div class="icon-input">
                            <i class="fas fa-user-md"></i>
                            <input type="text" name="veterinarian" class="form-control" id="veterinarian" 
                                   required placeholder="Enter veterinarian's name"
                                   value="<?php echo isset($_POST['veterinarian']) ? htmlspecialchars($_POST['veterinarian']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h5><i class="fas fa-diagnoses me-2"></i> Medical Information</h5>
                    <div class="mb-4">
                        <label for="diagnosis" class="form-label">Diagnosis</label>
                        <div class="icon-input">
                            <i class="fas fa-stethoscope"></i>
                            <input type="text" name="diagnosis" class="form-control" id="diagnosis" 
                                   required placeholder="Enter diagnosis"
                                   value="<?php echo isset($_POST['diagnosis']) ? htmlspecialchars($_POST['diagnosis']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="treatment" class="form-label">Treatment Plan</label>
                        <textarea name="treatment" class="form-control" id="treatment" rows="5" 
                                  required placeholder="Describe the treatment plan..."><?php echo isset($_POST['treatment']) ? htmlspecialchars($_POST['treatment']) : ''; ?></textarea>
                        <div class="notes-counter"><span id="treatment-count">0</span>/500 characters</div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-health">
                        <i class="fas fa-save me-2"></i> Save Health Record
                    </button>
                    <a href="list.php" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i> View All Records
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Character counter for treatment
        const treatmentTextarea = document.getElementById('treatment');
        const treatmentCounter = document.getElementById('treatment-count');
        
        treatmentTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            treatmentCounter.textContent = currentLength;
            
            if (currentLength > 500) {
                treatmentCounter.style.color = 'red';
                this.value = this.value.substring(0, 500);
            } else {
                treatmentCounter.style.color = '#858796';
            }
        });
        
        // Set today's date as default if not already set
        if (!document.getElementById('date').value) {
            document.getElementById('date').valueAsDate = new Date();
        }
    </script>
</body>
</html>
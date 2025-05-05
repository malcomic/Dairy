<?php 
require_once '../../functions.php'; 
requireLogin(); 
require_once '../../config/database.php';
require_once '../../models/Cow.php';
require_once '../../models/FeedRecord.php';

// Initialize models
$cow = new Cow($pdo);
$feedRecord = new FeedRecord($pdo);

// Fetch cows for dropdown
$cows = $cow->getCowsFiltered();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $requiredFields = ['cow_id', 'date', 'feed_type', 'quantity', 'unit'];
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (!empty($missingFields)) {
            throw new Exception("Missing required fields: " . implode(', ', $missingFields));
        }

        // Validate and sanitize inputs
        $feedData = [
            'cow_id' => filter_var($_POST['cow_id'], FILTER_VALIDATE_INT),
            'date' => htmlspecialchars($_POST['date']),
            'feed_type' => htmlspecialchars($_POST['feed_type']),
            'quantity' => filter_var($_POST['quantity'], FILTER_VALIDATE_FLOAT),
            'unit' => htmlspecialchars($_POST['unit'])
        ];

        if ($feedData['cow_id'] === false || $feedData['quantity'] === false) {
            throw new Exception("Invalid numeric values provided");
        }

        // Add record
        $result = $feedRecord->addFeedRecord(
            $feedData['cow_id'],
            $feedData['date'],
            $feedData['feed_type'],
            $feedData['quantity'],
            $feedData['unit']
        );

        if ($result) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'message' => 'Feed record added successfully!'
            ];
            header("Location: list.php");
            exit();
        } else {
            throw new Exception("Failed to add feed record to database");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Feed record error: " . $error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Feed Record | Dairy Management</title>
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
        
        .feed-form-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin: 3rem auto;
            max-width: 700px;
            border-top: 5px solid var(--feed-color);
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
            background: var(--feed-color);
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
            border-color: var(--feed-color);
            box-shadow: 0 0 0 0.25rem rgba(246, 194, 62, 0.25);
        }
        
        .btn-feed {
            background-color: var(--feed-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #333;
        }
        
        .btn-feed:hover {
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
        
        .feed-graphic {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .feed-graphic i {
            font-size: 3rem;
            color: var(--feed-color);
            background: rgba(246, 194, 62, 0.2);
            padding: 1.5rem;
            border-radius: 50%;
        }
        
        .input-group-text {
            background-color: #f8f9fc;
            border-color: #d1d3e2;
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
        
        .alert-danger {
            background-color: rgba(231, 74, 59, 0.1);
            border-color: rgba(231, 74, 59, 0.3);
            color: #e74a3b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="feed-form-container">
            <div class="feed-graphic">
                <i class="fas fa-utensils"></i>
            </div>
            
            <h2 class="page-header">Add Feed Record</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="form-section">
                    <h5><i class="fas fa-cow me-2"></i> Cow Information</h5>
                    <div class="mb-4">
                        <label for="cow_id" class="form-label">Select Cow</label>
                        <div class="icon-input">
                            <i class="fas fa-hashtag"></i>
                            <select name="cow_id" id="cow_id" class="form-select" required>
                                <option value="">-- Select Cow --</option>
                                <?php foreach ($cows as $c): ?>
                                    <option value="<?php echo htmlspecialchars($c['cow_id']); ?>"
                                        <?php echo isset($_POST['cow_id']) && $_POST['cow_id'] == $c['cow_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['name'] . ' (ID: ' . $c['cow_id'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h5><i class="fas fa-calendar-day me-2"></i> Feeding Details</h5>
                    <div class="mb-4">
                        <label for="date" class="form-label">Date of Feeding</label>
                        <div class="icon-input">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" name="date" class="form-control" id="date" required 
                                   value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : date('Y-m-d'); ?>"
                                   min="2020-01-01" max="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="feed_type" class="form-label">Feed Type</label>
                        <select name="feed_type" id="feed_type" class="form-select" required>
                            <option value="">-- Select Feed Type --</option>
                            <option value="Hay" <?php echo isset($_POST['feed_type']) && $_POST['feed_type'] == 'Hay' ? 'selected' : ''; ?>>Hay</option>
                            <option value="Silage" <?php echo isset($_POST['feed_type']) && $_POST['feed_type'] == 'Silage' ? 'selected' : ''; ?>>Silage</option>
                            <option value="Grain" <?php echo isset($_POST['feed_type']) && $_POST['feed_type'] == 'Grain' ? 'selected' : ''; ?>>Grain</option>
                            <option value="Concentrates" <?php echo isset($_POST['feed_type']) && $_POST['feed_type'] == 'Concentrates' ? 'selected' : ''; ?>>Concentrates</option>
                            <option value="Grass" <?php echo isset($_POST['feed_type']) && $_POST['feed_type'] == 'Grass' ? 'selected' : ''; ?>>Grass</option>
                            <option value="Other" <?php echo isset($_POST['feed_type']) && $_POST['feed_type'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-section">
                    <h5><i class="fas fa-weight me-2"></i> Quantity Details</h5>
                    <div class="mb-4">
                        <label for="quantity" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
                            <input type="number" name="quantity" class="form-control" id="quantity" 
                                   step="0.01" min="0" required placeholder="Enter amount"
                                   value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>">
                            <span class="input-group-text">
                                <select name="unit" class="form-select" id="unit" style="border: none; background: transparent;">
                                    <option value="kg" <?php echo isset($_POST['unit']) && $_POST['unit'] == 'kg' ? 'selected' : ''; ?>>kg</option>
                                    <option value="lbs" <?php echo isset($_POST['unit']) && $_POST['unit'] == 'lbs' ? 'selected' : ''; ?>>lbs</option>
                                    <option value="liters" <?php echo isset($_POST['unit']) && $_POST['unit'] == 'liters' ? 'selected' : ''; ?>>liters</option>
                                    <option value="gallons" <?php echo isset($_POST['unit']) && $_POST['unit'] == 'gallons' ? 'selected' : ''; ?>>gallons</option>
                                </select>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-feed">
                        <i class="fas fa-save me-2"></i> Save Feed Record
                    </button>
                    <a href="list.php" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i> View All Records
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
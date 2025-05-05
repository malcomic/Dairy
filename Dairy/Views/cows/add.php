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
    echo "<p>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cow_id = trim($_POST['cow_id']);
    $breed = trim($_POST['breed']);
    $age = (int)$_POST['age'];
    $health_status = trim($_POST['health_status']);

    // Validation
    if (empty($cow_id) || empty($breed) || $age <= 0 || empty($health_status)) {
        echo "<script>alert('Please fill in all fields correctly.');</script>";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO cows (cow_id, breed, age, health_status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$cow_id, $breed, $age, $health_status]);

            echo "<script>alert('Cow added successfully!'); window.location.href = 'list.php';</script>";
        } catch (PDOException $e) {
            error_log("Database error (add cow): " . $e->getMessage());
            echo "<script>alert('Error adding cow: " . htmlspecialchars($e->getMessage()) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Cow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
            font-family: Arial, sans-serif;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Cow</h2>
        <form method="post" action="add.php">
            <div class="mb-3">
                <label for="cow_id" class="form-label">Cow ID</label>
                <input type="text" name="cow_id" class="form-control" id="cow_id" required>
            </div>

            <div class="mb-3">
                <label for="breed" class="form-label">Breed</label>
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

            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" name="age" id="age" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="health_status" class="form-label">Health Status</label>
                <select name="health_status" id="health_status" class="form-select" required>
                    <option value="Healthy">Healthy</option>
                    <option value="Sick">Sick</option>
                    <option value="Injured">Injured</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Add Cow</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
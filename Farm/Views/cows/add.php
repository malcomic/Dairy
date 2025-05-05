<?php
require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php'; // Include your database connection

// Fetch breeds from the database
try {
    $stmt = $pdo->query("SELECT breed_name FROM breeds");
    $breeds = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $breeds = []; // Default to empty array on error
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
        /* Add any custom styles here */
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Cow</h2>
        <form method="post" action="../../index.php?action=add_cow">
            <div class="mb-3">
                <label for="cow_id" class="form-label">Cow ID</label>
                <input type="text" name="cow_id" class="form-control" id="cow_id" required>
                <small class="form-text">Enter a unique identifier for the cow.</small>
            </div>

            <div class="mb-3">
                <label for="breed" class="form-label">Breed</label>
                <select name="breed" id="breed" class="form-select" required>
                    <option value="">Select Breed</option>
                    <?php foreach ($breeds as $breed): ?>
                        <option value="<?php echo htmlspecialchars($breed); ?>"><?php echo htmlspecialchars($breed); ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text">Specify the breed of the cow.</small>
            </div>

            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" name="age" id="age" class="form-control" required>
                <small class="form-text">Enter the cow's age.</small>
            </div>

            <div class="mb-3">
                <label for="health_status" class="form-label">Health Status</label>
                <select name="health_status" id="health_status" class="form-select" required>
                    <option value="Healthy">Healthy</option>
                    <option value="Sick">Sick</option>
                    <option value="Injured">Injured</option>
                    <option value="Other">Other</option>
                </select>
                <small class="form-text">Describe the current health status of the cow.</small>
            </div>

            <button type="submit" class="btn btn-primary">Add Cow</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
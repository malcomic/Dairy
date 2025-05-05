<?php
require_once '../../functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Milk Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; }
        h2 { color: #333; margin-bottom: 20px; }
        .form-control { margin-bottom: 15px; }
        .btn-primary { background-color: #007bff; border: none; }
        .btn-primary:hover { background-color: #0056b3; }
        .error-message { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Milk Record</h2>
        <form method="post" action="../../index.php?action=add_milk">
            <div class="mb-3">
                <label for="cow_id" class="form-label">Cow ID:</label>
                <input type="number" name="cow_id" class="form-control" id="cow_id" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date:</label>
                <input type="date" name="date" class="form-control" id="date" required value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="mb-3">
                <label for="milk_yield" class="form-label">Milk Yield (Liters):</label>
                <input type="number" name="milk_yield" class="form-control" id="milk_yield" step="0.01" min="0" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes:</label>
                <textarea name="notes" class="form-control" id="notes"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Record</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
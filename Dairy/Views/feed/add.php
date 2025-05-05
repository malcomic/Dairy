<?php require_once '../../functions.php'; requireLogin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Feed Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Feed Record</h2>
        <form method="post" action="../../index.php?action=add_feed">
            <div class="mb-3">
                <label for="cow_id" class="form-label">Cow ID:</label>
                <input type="number" name="cow_id" class="form-control" id="cow_id" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date:</label>
                <input type="date" name="date" class="form-control" id="date" required value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="mb-3">
                <label for="feed_type" class="form-label">Feed Type:</label>
                <select name="feed_type" id="feed_type" class="form-select" required>
                    <option value="">Select Feed Type</option>
                    <option value="Hay">Hay</option>
                    <option value="Silage">Silage</option>
                    <option value="Grain">Grain</option>
                    <option value="Concentrates">Concentrates</option>
                    <option value="Grass">Grass</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" class="form-control" id="quantity" step="0.01" min="0" required>
            </div>

            <div class="mb-3">
                <label for="unit" class="form-label">Unit:</label>
                <input type="text" name="unit" class="form-control" id="unit" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Record</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

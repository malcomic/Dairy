<?php require_once '../../functions.php'; requireLogin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Health Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ... your existing styles ... */
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Health Record</h2>
        <form method="post" action="../../index.php?action=add_health">
            <div class="mb-3">
                <label for="cow_id" class="form-label">Cow ID:</label>
                <input type="number" name="cow_id" class="form-control" id="cow_id" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date:</label>
                <input type="date" name="date" class="form-control" id="date" required value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="mb-3">
                <label for="diagnosis" class="form-label">Diagnosis:</label>
                <input type="text" name="diagnosis" class="form-control" id="diagnosis" required>
            </div>

            <div class="mb-3">
                <label for="treatment" class="form-label">Treatment:</label>
                <textarea name="treatment" class="form-control" id="treatment" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="veterinarian" class="form-label">Veterinarian:</label>
                <input type="text" name="veterinarian" class="form-control" id="veterinarian" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Record</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
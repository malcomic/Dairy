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
    <title>Cow List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px; margin-top: 50px; }
        h2 { color: #333; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-control { margin-bottom: 15px; }
        .btn-primary { background-color: #007bff; border: none; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-secondary { margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cow List</h2>

        <form method="get" action="">
            <div class="row mb-3">
                <div class="col">
                    <label for="search">Search Cow ID:</label>
                    <input type="text" name="search" id="search" class="form-control" value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
                <div class="col">
                    <label for="breed">Filter by Breed:</label>
                    <input type="text" name="breed" id="breed" class="form-control" value="<?php echo htmlspecialchars($breed ?? ''); ?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="age_min">Minimum Age:</label>
                    <input type="number" name="age_min" id="age_min" class="form-control" value="<?php echo htmlspecialchars($ageMin ?? ''); ?>">
                </div>
                <div class="col">
                    <label for="age_max">Maximum Age:</label>
                    <input type="number" name="age_max" id="age_max" class="form-control" value="<?php echo htmlspecialchars($ageMax ?? ''); ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Filter/Search</button>
            <a href="list.php" class="btn btn-secondary">Clear Filters</a>
        </form>

        <table class="table table-bordered table-responsive mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cow ID</th>
                    <th>Breed</th>
                    <th>Age</th>
                    <th>Health Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cows as $aCow): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($aCow['id']); ?></td>
                        <td><?php echo htmlspecialchars($aCow['cow_id']); ?></td>
                        <td><?php echo htmlspecialchars($aCow['breed']); ?></td>
                        <td><?php echo htmlspecialchars($aCow['age']); ?></td>
                        <td><?php echo htmlspecialchars($aCow['health_status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Back Button -->
        <a href="../../index.php" class="btn btn-secondary mt-3">Back</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

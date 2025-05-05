<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
require_once '../../models/HealthRecord.php';

$healthRecord = new HealthRecord($pdo);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $record = null;
    $records = $healthRecord->getHealthRecords(); //get all records
    foreach($records as $rec){
        if($rec['id'] == $id){
            $record = $rec;
            break;
        }
    }

    if (!$record) {
        echo "<script>alert('Record not found.'); window.location.href = 'list.php';</script>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cowId = $_POST['cow_id'];
        $date = $_POST['date'];
        $diagnosis = $_POST['diagnosis'];
        $treatment = $_POST['treatment'];
        $veterinarian = $_POST['veterinarian'];

        // Validation
        if (empty($cowId) || empty($date) || empty($diagnosis) || empty($treatment) || empty($veterinarian)) {
            echo "<script>alert('Please fill in all fields.'); window.location.href = 'edit.php?id=$id';</script>";
            exit();
        }

        try {
            $result = $healthRecord->updateHealthRecord($id, $cowId, $date, $diagnosis, $treatment, $veterinarian);
            if ($result === true) {
                echo "<script>alert('Health record updated successfully!'); window.location.href = 'list.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error: Health record NOT updated. Please check the database and error logs.'); window.location.href = 'edit.php?id=$id';</script>";
                exit();
            }
        } catch (PDOException $e) {
            echo "<script>alert('Database error: " . $e->getMessage() . ". Please check the database and error logs.'); window.location.href = 'edit.php?id=$id';</script>";
            exit();
        }  catch (Exception $e) {
            echo "<script>alert('General error: " . $e->getMessage() . ". Please check the database and error logs.'); window.location.href = 'edit.php?id=$id';</script>";
            exit();
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Health Record</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* Your existing styles */
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Edit Health Record</h2>
            <form method="post" action="edit.php?id=<?php echo htmlspecialchars($id); ?>">
                <div class="mb-3">
                    <label for="cow_id" class="form-label">Cow ID:</label>
                    <input type="number" name="cow_id" class="form-control" id="cow_id" required value="<?php echo htmlspecialchars($record['cow_id']); ?>">
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" name="date" class="form-control" id="date" required value="<?php echo htmlspecialchars($record['date']); ?>">
                </div>
                <div class="mb-3">
                    <label for="diagnosis" class="form-label">Diagnosis:</label>
                    <input type="text" name="diagnosis" class="form-control" id="diagnosis" required value="<?php echo htmlspecialchars($record['diagnosis']); ?>">
                </div>
                <div class="mb-3">
                    <label for="treatment" class="form-label">Treatment:</label>
                    <textarea name="treatment" class="form-control" id="treatment" rows="4" required><?php echo htmlspecialchars($record['treatment']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="veterinarian" class="form-label">Veterinarian:</label>
                    <input type="text" name="veterinarian" class="form-control" id="veterinarian" required value="<?php echo htmlspecialchars($record['veterinarian']); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Record</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'list.php';</script>";
    exit();
}
?>
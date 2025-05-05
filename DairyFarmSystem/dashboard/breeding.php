<?php
require '../connection.php'; // Database connection file

// Fetch breeding records from the database
$sql = "SELECT breeding_records.*, animals.name AS animal_name 
        FROM breeding_records 
        LEFT JOIN animals ON breeding_records.animal_id = animals.id";
$result = $conn->query($sql);

// Handle form submission for adding a new breeding record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = $_POST['animal_id'];
    $breeding_date = $_POST['breeding_date'];
    $breeding_method = $_POST['breeding_method'];
    $sire_id = $_POST['sire_id'];
    $dam_id = $_POST['dam_id'];
    $heat_date = $_POST['heat_date'];
    $insemination_date = $_POST['insemination_date'];
    $pregnancy_status = $_POST['pregnancy_status'];
    $pregnancy_confirmation_date = $_POST['pregnancy_confirmation_date'];
    $expected_calving_date = $_POST['expected_calving_date'];
    $remarks = $_POST['remarks'];

    // Insert new breeding record into the database
    $stmt = $conn->prepare("INSERT INTO breeding_records (animal_id, breeding_date, breeding_method, sire_id, dam_id, heat_date, insemination_date, pregnancy_status, pregnancy_confirmation_date, expected_calving_date, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssissssss", $animal_id, $breeding_date, $breeding_method, $sire_id, $dam_id, $heat_date, $insemination_date, $pregnancy_status, $pregnancy_confirmation_date, $expected_calving_date, $remarks);

    if ($stmt->execute()) {
        echo "<script>alert('Breeding record added successfully!'); window.location.href='breedingrecords.php';</script>";
    } else {
        echo "<script>alert('Error adding breeding record.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breeding Records</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="../js/breeding_records.js" defer></script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="p-3">  
                    <h4 class="text-center">Farm Dashboard</h4>
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="Dashboard.php">Home</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="./animalrecord.php">Animal Records</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="../dashboard/milkrecords.php">Milking Records</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="../dashboard/breeding.php">Breeding</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="../dashboard/farmfinance.php">Farm Finance</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="../dashboard/milksales.php">Milk Sales</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="../dashboard/stockfeeds.php">Stock Feed</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="../logout.php">Log Out</a>
                        </li>
                    </ul>
                </div>
            </nav>


        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="pt-3 pb-2 mb-3">
                <h1 class="h2">Breeding Records</h1>
            </div>

            <!-- Search Box -->
            <div class="mb-3">
                <input type="text" id="searchBreeding" class="form-control" placeholder="Search for breeding records...">
            </div>

            <!-- Add Breeding Record Button -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addBreedingModal">Add Breeding Record</button>

            <!-- Breeding Records Table -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Animal ID</th>
                        <th>Animal Name</th>
                        <th>Breeding Date</th>
                        <th>Breeding Method</th>
                        <th>Sire ID</th>
                        <th>Dam ID</th>
                        <th>Pregnancy Status</th>
                        <th>Expected Calving Date</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="breedingTable">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['animal_id']; ?></td>
                        <td><?php echo $row['animal_name']; ?></td>
                        <td><?php echo $row['breeding_date']; ?></td>
                        <td><?php echo $row['breeding_method']; ?></td>
                        <td><?php echo $row['sire_id']; ?></td>
                        <td><?php echo $row['dam_id']; ?></td>
                        <td><?php echo $row['pregnancy_status']; ?></td>
                        <td><?php echo $row['expected_calving_date']; ?></td>
                        <td><?php echo $row['remarks']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['id']; ?>">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<!-- Add Breeding Record Modal -->
<div class="modal fade" id="addBreedingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Breeding Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addBreedingForm" method="POST">
                    <div class="mb-3">
                        <label>Animal ID:</label>
                        <input type="number" name="animal_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Breeding Date:</label>
                        <input type="date" name="breeding_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Breeding Method:</label>
                        <select name="breeding_method" class="form-control" required>
                            <option value="Natural Mating">Natural Mating</option>
                            <option value="Artificial Insemination">Artificial Insemination</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Sire ID:</label>
                        <input type="number" name="sire_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Dam ID:</label>
                        <input type="number" name="dam_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Heat Date:</label>
                        <input type="date" name="heat_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Insemination Date:</label>
                        <input type="date" name="insemination_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Pregnancy Status:</label>
                        <select name="pregnancy_status" class="form-control">
                            <option value="Pregnant">Pregnant</option>
                            <option value="Not Pregnant">Not Pregnant</option>
                            <option value="Unknown">Unknown</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Pregnancy Confirmation Date:</label>
                        <input type="date" name="pregnancy_confirmation_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Expected Calving Date:</label>
                        <input type="date" name="expected_calving_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Remarks:</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Breeding Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
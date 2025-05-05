<?php
require '../connection.php'; // Database connection file

// Fetch milk records from the database
$sql = "SELECT milk.*, animals.name AS animal_name 
        FROM milk 
        LEFT JOIN animals ON milk.animal_name = animals.id";
$result = $conn->query($sql);

// Handle form submission for adding a new milk record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $animal_name = $_POST['animal_name'];
    $milking_session = $_POST['milking_session'];
    $milk_yield = $_POST['milk_yield'];
    $remarks = $_POST['remarks'];

    // Insert new milk record into the database
    $stmt = $conn->prepare("INSERT INTO milk (date, animal_name, milking_session, milk_yield, remarks) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisds", $date, $animal_name, $milking_session, $milk_yield, $remarks);

    if ($stmt->execute()) {
        echo "<script>alert('Milk record added successfully!'); window.location.href='milkrecords.php';</script>";
    } else {
        echo "<script>alert('Error adding milk record.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Records</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="../js/milk.js" defer></script>
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
                            <a class="nav-link" href="../animalrecord.php">Animal Records</a>
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
                <h1 class="h2">Milk Records</h1>
            </div>

            <!-- Search Box -->
            <div class="mb-3">
                <input type="text" id="searchMilk" class="form-control" placeholder="Search for milk records...">
            </div>

            <!-- Add Milk Record Button -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addMilkModal">Add Milk Record</button>

            <!-- Milk Records Table -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <!--th>ID</th -->
                        <th>Date</th>
                        <th>Animal ID</th>
                        <th>Animal Name</th>
                        <th>Milking Session</th>
                        <th>Milk Yield (L)</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="milkTable">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!--td><?php //echo $row['id']; ?></td -->
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['animal_name']; ?></td>
                        <td><?php echo $row['animal_name']; ?></td>
                        <td><?php echo $row['milking_session']; ?></td>
                        <td><?php echo $row['milk_yield']; ?></td>
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

<!-- Add Milk Record Modal -->
<div class="modal fade" id="addMilkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Milk Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addMilkForm" method="POST">
                    <div class="mb-3">
                        <label>Date:</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Animal ID:</label>
                        <input type="number" name="animal_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Milking Session:</label>
                        <select name="milking_session" class="form-control" required>
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Milk Yield (Liters):</label>
                        <input type="number" step="0.01" name="milk_yield" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Remarks:</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Milk Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
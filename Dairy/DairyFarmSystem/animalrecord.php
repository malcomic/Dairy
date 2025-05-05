<?php
require 'C:\Users\MALCOM\OneDrive\Desktop\xmp\xammp\htdocs\DairyFarmSystem\connection.php'; // Database connection file

// Fetch animal records from the database
$sql = "SELECT * FROM animals";
$result = $conn->query($sql);

// Handle form submission for adding a new animal record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $color_markings = $_POST['color_markings'];
    $sire_id = $_POST['sire_id'];
    $dam_id = $_POST['dam_id'];
    $source = $_POST['source'];
    $current_location = $_POST['current_location'];
    $status = $_POST['status'];
    $weight = $_POST['weight'];
    $body_condition = $_POST['body_condition'];
    $last_vaccination = $_POST['last_vaccination'];
    $last_treatment = $_POST['last_treatment'];
    $lactation_status = $_POST['lactation_status'];
    $milk_yield = $_POST['milk_yield'];
    $feed_type = $_POST['feed_type'];
    $notes = $_POST['notes'];

    // Insert new animal record into the database
    $stmt = $conn->prepare("INSERT INTO animals (name, breed, date_of_birth, gender, color_markings, sire_id, dam_id, source, current_location, status, weight, body_condition, last_vaccination, last_treatment, lactation_status, milk_yield, feed_type, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiissssdsssdss", $name, $breed, $date_of_birth, $gender, $color_markings, $sire_id, $dam_id, $source, $current_location, $status, $weight, $body_condition, $last_vaccination, $last_treatment, $lactation_status, $milk_yield, $feed_type, $notes);

    if ($stmt->execute()) {
        echo "<script>alert('Animal record added successfully!'); window.location.href='animalrecords.php';</script>";
    } else {
        echo "<script>alert('Error adding animal record.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Records</title>
    <link rel="stylesheet" href="./CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="../js/animal_records.js" defer></script>
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
                        <a class="nav-link" href="./dashboard/animalrecord.php">Animal Records</a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link" href="./dashboard/milkrecords.php">Milk Records</a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link" href="./dashboard/breedingrecords.php">Breeding Records</a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link" href="./dashboard/farmfinance.php">Farm Finance</a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link" href="./dashboard/milksales.php">Milk Sales</a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link" href="./dashboard/stockfeeds.php">Stock Feeds</a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link" href="./logout.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="pt-3 pb-2 mb-3">
                <h1 class="h2">Animal Records</h1>
            </div>

            <!-- Search Box -->
            <div class="mb-3">
                <input type="text" id="searchAnimal" class="form-control" placeholder="Search for animals...">
            </div>

            <!-- Add Animal Button -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addAnimalModal">Add Animal</button>

            <!-- Animal Records Table -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Breed</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Color/Markings</th>
                        <th>Sire ID</th>
                        <th>Dam ID</th>
                        <th>Source</th>
                        <th>Current Location</th>
                        <th>Status</th>
                        <th>Weight (kg)</th>
                        <th>Body Condition</th>
                        <th>Last Vaccination</th>
                        <th>Last Treatment</th>
                        <th>Lactation Status</th>
                        <th>Milk Yield (L)</th>
                        <th>Feed Type</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="animalTable">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['breed']; ?></td>
                        <td><?php echo $row['date_of_birth']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['color_markings']; ?></td>
                        <td><?php echo $row['sire_id']; ?></td>
                        <td><?php echo $row['dam_id']; ?></td>
                        <td><?php echo $row['source']; ?></td>
                        <td><?php echo $row['current_location']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['weight']; ?></td>
                        <td><?php echo $row['body_condition']; ?></td>
                        <td><?php echo $row['last_vaccination']; ?></td>
                        <td><?php echo $row['last_treatment']; ?></td>
                        <td><?php echo $row['lactation_status']; ?></td>
                        <td><?php echo $row['milk_yield']; ?></td>
                        <td><?php echo $row['feed_type']; ?></td>
                        <td><?php echo $row['notes']; ?></td>
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

<!-- Add Animal Modal -->
<div class="modal fade" id="addAnimalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Animal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAnimalForm" method="POST">
                    <div class="mb-3">
                        <label>Name:</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Breed:</label>
                        <input type="text" name="breed" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Date of Birth:</label>
                        <input type="date" name="date_of_birth" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Gender:</label>
                        <select name="gender" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Neutered">Neutered</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Color/Markings:</label>
                        <input type="text" name="color_markings" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Sire ID:</label>
                        <input type="number" name="sire_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Dam ID:</label>
                        <input type="number" name="dam_id" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Source:</label>
                        <input type="text" name="source" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Current Location:</label>
                        <input type="text" name="current_location" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Status:</label>
                        <input type="text" name="status" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Weight (kg):</label>
                        <input type="number" step="0.01" name="weight" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Body Condition (1-5):</label>
                        <input type="number" name="body_condition" class="form-control" min="1" max="5">
                    </div>
                    <div class="mb-3">
                        <label>Last Vaccination:</label>
                        <input type="date" name="last_vaccination" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Last Treatment:</label>
                        <input type="date" name="last_treatment" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Lactation Status:</label>
                        <input type="text" name="lactation_status" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Milk Yield (L):</label>
                        <input type="number" step="0.01" name="milk_yield" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Feed Type:</label>
                        <input type="text" name="feed_type" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Notes:</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Animal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
require '../connection.php'; // Database connection file

// Fetch feed inventory records from the database
$inventory_sql = "SELECT * FROM feed_inventory";
$inventory_result = $conn->query($inventory_sql);

// Fetch feed purchase records from the database
$purchases_sql = "SELECT feed_purchases.*, feed_inventory.feed_name 
                  FROM feed_purchases 
                  LEFT JOIN feed_inventory ON feed_purchases.feed_id = feed_inventory.feed_id";
$purchases_result = $conn->query($purchases_sql);

// Fetch feed usage records from the database
$usage_sql = "SELECT feed_usage.*, feed_inventory.feed_name, animals.name AS animal_name 
              FROM feed_usage 
              LEFT JOIN feed_inventory ON feed_usage.feed_id = feed_inventory.feed_id 
              LEFT JOIN animals ON feed_usage.animal_id = animals.id";
$usage_result = $conn->query($usage_sql);

// Handle form submission for adding a new feed inventory record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_inventory'])) {
    $feed_name = $_POST['feed_name'];
    $quantity_in_stock = $_POST['quantity_in_stock'];
    $unit_of_measurement = $_POST['unit_of_measurement'];
    $storage_location = $_POST['storage_location'];
    $expiry_date = $_POST['expiry_date'];

    // Insert new feed inventory record into the database
    $stmt = $conn->prepare("INSERT INTO feed_inventory (feed_name, quantity_in_stock, unit_of_measurement, storage_location, expiry_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $feed_name, $quantity_in_stock, $unit_of_measurement, $storage_location, $expiry_date);

    if ($stmt->execute()) {
        echo "<script>alert('Feed inventory record added successfully!'); window.location.href='stockfeeds.php';</script>";
    } else {
        echo "<script>alert('Error adding feed inventory record.');</script>";
    }
    $stmt->close();
}

// Handle form submission for adding a new feed purchase record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_purchase'])) {
    $feed_id = $_POST['feed_id'];
    $purchase_date = $_POST['purchase_date'];
    $supplier = $_POST['supplier'];
    $quantity_purchased = $_POST['quantity_purchased'];
    $unit_price = $_POST['unit_price'];
    $total_cost = $quantity_purchased * $unit_price;
    $payment_method = $_POST['payment_method'];
    $remarks = $_POST['remarks'];

    // Insert new feed purchase record into the database
    $stmt = $conn->prepare("INSERT INTO feed_purchases (feed_id, purchase_date, supplier, quantity_purchased, unit_price, total_cost, payment_method, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issddsss", $feed_id, $purchase_date, $supplier, $quantity_purchased, $unit_price, $total_cost, $payment_method, $remarks);

    if ($stmt->execute()) {
        echo "<script>alert('Feed purchase record added successfully!'); window.location.href='stockfeeds.php';</script>";
    } else {
        echo "<script>alert('Error adding feed purchase record.');</script>";
    }
    $stmt->close();
}

// Handle form submission for adding a new feed usage record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_usage'])) {
    $feed_id = $_POST['feed_id'];
    $date_of_usage = $_POST['date_of_usage'];
    $quantity_used = $_POST['quantity_used'];
    $animal_id = $_POST['animal_id'];
    $purpose = $_POST['purpose'];
    $remarks = $_POST['remarks'];

    // Insert new feed usage record into the database
    $stmt = $conn->prepare("INSERT INTO feed_usage (feed_id, date_of_usage, quantity_used, animal_id, purpose, remarks) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdiss", $feed_id, $date_of_usage, $quantity_used, $animal_id, $purpose, $remarks);

    if ($stmt->execute()) {
        echo "<script>alert('Feed usage record added successfully!'); window.location.href='stockfeeds.php';</script>";
    } else {
        echo "<script>alert('Error adding feed usage record.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Feeds</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="../js/stock_feeds.js" defer></script>
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
                            <a class="nav-link" href="../Dashboard.php">Home</a>
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
                <h1 class="h2">Stock Feeds</h1>
            </div>

            <!-- Tabs for Feed Inventory, Purchases, and Usage -->
            <ul class="nav nav-tabs" id="stockFeedsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="true">Feed Inventory</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button" role="tab" aria-controls="purchases" aria-selected="false">Feed Purchases</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="usage-tab" data-bs-toggle="tab" data-bs-target="#usage" type="button" role="tab" aria-controls="usage" aria-selected="false">Feed Usage</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="stockFeedsTabsContent">
                <!-- Feed Inventory Tab -->
                <div class="tab-pane fade show active" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                    <h3 class="mt-3">Feed Inventory</h3>
                    <!-- Add Feed Inventory Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addInventoryModal">Add Feed Inventory</button>
                    <!-- Feed Inventory Table -->
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Feed Name</th>
                                <th>Quantity in Stock</th>
                                <th>Unit of Measurement</th>
                                <th>Storage Location</th>
                                <th>Expiry Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="inventoryTable">
                            <?php while ($row = $inventory_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['feed_id']; ?></td>
                                <td><?php echo $row['feed_name']; ?></td>
                                <td><?php echo $row['quantity_in_stock']; ?></td>
                                <td><?php echo $row['unit_of_measurement']; ?></td>
                                <td><?php echo $row['storage_location']; ?></td>
                                <td><?php echo $row['expiry_date']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['feed_id']; ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['feed_id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Feed Purchases Tab -->
                <div class="tab-pane fade" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                    <h3 class="mt-3">Feed Purchases</h3>
                    <!-- Add Feed Purchase Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addPurchaseModal">Add Feed Purchase</button>
                    <!-- Feed Purchases Table -->
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Feed Name</th>
                                <th>Purchase Date</th>
                                <th>Supplier</th>
                                <th>Quantity Purchased</th>
                                <th>Unit Price</th>
                                <th>Total Cost</th>
                                <th>Payment Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="purchasesTable">
                            <?php while ($row = $purchases_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['purchase_id']; ?></td>
                                <td><?php echo $row['feed_name']; ?></td>
                                <td><?php echo $row['purchase_date']; ?></td>
                                <td><?php echo $row['supplier']; ?></td>
                                <td><?php echo $row['quantity_purchased']; ?></td>
                                <td><?php echo $row['unit_price']; ?></td>
                                <td><?php echo $row['total_cost']; ?></td>
                                <td><?php echo $row['payment_method']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['purchase_id']; ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['purchase_id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Feed Usage Tab -->
                <div class="tab-pane fade" id="usage" role="tabpanel" aria-labelledby="usage-tab">
                    <h3 class="mt-3">Feed Usage</h3>
                    <!-- Add Feed Usage Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUsageModal">Add Feed Usage</button>
                    <!-- Feed Usage Table -->
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Feed Name</th>
                                <th>Date of Usage</th>
                                <th>Quantity Used</th>
                                <th>Animal Name</th>
                                <th>Purpose</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usageTable">
                            <?php while ($row = $usage_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['usage_id']; ?></td>
                                <td><?php echo $row['feed_name']; ?></td>
                                <td><?php echo $row['date_of_usage']; ?></td>
                                <td><?php echo $row['quantity_used']; ?></td>
                                <td><?php echo $row['animal_name']; ?></td>
                                <td><?php echo $row['purpose']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['usage_id']; ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['usage_id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add Feed Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Feed Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addInventoryForm" method="POST">
                    <div class="mb-3">
                        <label>Feed Name:</label>
                        <input type="text" name="feed_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Quantity in Stock:</label>
                        <input type="number" step="0.01" name="quantity_in_stock" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Unit of Measurement:</label>
                        <select name="unit_of_measurement" class="form-control" required>
                            <option value="kg">kg</option>
                            <option value="tons">tons</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Storage Location:</label>
                        <input type="text" name="storage_location" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Expiry Date:</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                    <button type="submit" name="add_inventory" class="btn btn-primary">Save Feed Inventory</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Feed Purchase Modal -->
<div class="modal fade" id="addPurchaseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Feed Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPurchaseForm" method="POST">
                    <div class="mb-3">
                        <label>Feed ID:</label>
                        <input type="number" name="feed_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Purchase Date:</label>
                        <input type="date" name="purchase_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Supplier:</label>
                        <input type="text" name="supplier" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Quantity Purchased:</label>
                        <input type="number" step="0.01" name="quantity_purchased" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Unit Price:</label>
                        <input type="number" step="0.01" name="unit_price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Payment Method:</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="M-Pesa">M-Pesa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Remarks:</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                    <button type="submit" name="add_purchase" class="btn btn-primary">Save Feed Purchase</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Feed Usage Modal -->
<div class="modal fade" id="addUsageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Feed Usage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addUsageForm" method="POST">
                    <div class="mb-3">
                        <label>Feed ID:</label>
                        <input type="number" name="feed_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Date of Usage:</label>
                        <input type="date" name="date_of_usage" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Quantity Used:</label>
                        <input type="number" step="0.01" name="quantity_used" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Animal ID:</label>
                        <input type="number" name="animal_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Purpose:</label>
                        <input type="text" name="purpose" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Remarks:</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                    <button type="submit" name="add_usage" class="btn btn-primary">Save Feed Usage</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
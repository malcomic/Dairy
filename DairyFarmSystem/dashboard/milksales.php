<?php
require '../connection.php'; // Database connection file

// Fetch milk sales records from the database
$sql = "SELECT milk_sales.*, customers.name AS customer_name 
        FROM milk_sales 
        LEFT JOIN customers ON milk_sales.customer_id = customers.customer_id";
$result = $conn->query($sql);

// Handle form submission for adding a new milk sale
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $customer_id = $_POST['customer_id'];
    $quantity_sold = $_POST['quantity_sold'];
    $price_per_unit = $_POST['price_per_unit'];
    $total_amount = $quantity_sold * $price_per_unit;
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];
    $batch_number = $_POST['batch_number'];
    $fat_content = $_POST['fat_content'];
    $protein_content = $_POST['protein_content'];
    $lactose_content = $_POST['lactose_content'];
    $somatic_cell_count = $_POST['somatic_cell_count'];
    $delivery_date = $_POST['delivery_date'];
    $delivery_address = $_POST['delivery_address'];
    $delivery_status = $_POST['delivery_status'];
    $remarks = $_POST['remarks'];

    // Insert new milk sale record into the database
    $stmt = $conn->prepare("INSERT INTO milk_sales (date, customer_id, quantity_sold, price_per_unit, total_amount, payment_method, payment_status, batch_number, fat_content, protein_content, lactose_content, somatic_cell_count, delivery_date, delivery_address, delivery_status, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisddsssddddisss", $date, $customer_id, $quantity_sold, $price_per_unit, $total_amount, $payment_method, $payment_status, $batch_number, $fat_content, $protein_content, $lactose_content, $somatic_cell_count, $delivery_date, $delivery_address, $delivery_status, $remarks);

    if ($stmt->execute()) {
        echo "<script>alert('Milk sale record added successfully!'); window.location.href='milksales.php';</script>";
    } else {
        echo "<script>alert('Error adding milk sale record.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Sales</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="../js/milk_sales.js" defer></script>
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
                <h1 class="h2">Milk Sales</h1>
            </div>

            <!-- Search Box -->
            <div class="mb-3">
                <input type="text" id="searchMilkSales" class="form-control" placeholder="Search for milk sales...">
            </div>

            <!-- Add Milk Sale Button -->
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addMilkSaleModal">Add Milk Sale</button>

            <!-- Milk Sales Table -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Quantity Sold (L)</th>
                        <th>Price per Unit (Ksh)</th>
                        <th>Total Amount (Ksh)</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Batch Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="milkSalesTable">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['sale_id']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['quantity_sold']; ?></td>
                        <td><?php echo $row['price_per_unit']; ?></td>
                        <td><?php echo $row['total_amount']; ?></td>
                        <td><?php echo $row['payment_method']; ?></td>
                        <td><?php echo $row['payment_status']; ?></td>
                        <td><?php echo $row['batch_number']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['sale_id']; ?>">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['sale_id']; ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<!-- Add Milk Sale Modal -->
<div class="modal fade" id="addMilkSaleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Milk Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addMilkSaleForm" method="POST">
                    <div class="mb-3">
                        <label>Date:</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Customer ID:</label>
                        <input type="number" name="customer_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Quantity Sold (liters):</label>
                        <input type="number" step="0.01" name="quantity_sold" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Price per Unit (Ksh):</label>
                        <input type="number" step="0.01" name="price_per_unit" class="form-control" required>
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
                        <label>Payment Status:</label>
                        <select name="payment_status" class="form-control" required>
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Batch Number:</label>
                        <input type="text" name="batch_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Fat Content (%):</label>
                        <input type="number" step="0.01" name="fat_content" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Protein Content (%):</label>
                        <input type="number" step="0.01" name="protein_content" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Lactose Content (%):</label>
                        <input type="number" step="0.01" name="lactose_content" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Somatic Cell Count (SCC):</label>
                        <input type="number" name="somatic_cell_count" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Delivery Date:</label>
                        <input type="date" name="delivery_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Delivery Address:</label>
                        <textarea name="delivery_address" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Delivery Status:</label>
                        <select name="delivery_status" class="form-control">
                            <option value="Delivered">Delivered</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Remarks:</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Milk Sale</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
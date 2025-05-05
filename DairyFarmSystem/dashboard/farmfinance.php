<?php
require '../connection.php'; // Database connection file

// Fetch income records from the database
$income_sql = "SELECT * FROM income";
$income_result = $conn->query($income_sql);

// Fetch expense records from the database
$expenses_sql = "SELECT * FROM expenses";
$expenses_result = $conn->query($expenses_sql);

// Fetch budget records from the database
$budgets_sql = "SELECT * FROM budgets";
$budgets_result = $conn->query($budgets_sql);

// Fetch loan records from the database
$loans_sql = "SELECT * FROM loans";
$loans_result = $conn->query($loans_sql);

// Handle form submission for adding a new income record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_income'])) {
    $date = $_POST['date'];
    $source = $_POST['source'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $payment_method = $_POST['payment_method'];

    // Insert new income record into the database
    $stmt = $conn->prepare("INSERT INTO income (date, source, amount, description, payment_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $date, $source, $amount, $description, $payment_method);

    if ($stmt->execute()) {
        echo "<script>alert('Income record added successfully!'); window.location.href='farmfinance.php';</script>";
    } else {
        echo "<script>alert('Error adding income record.');</script>";
    }
    $stmt->close();
}

// Handle form submission for adding a new expense record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    $date = $_POST['date'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $payment_method = $_POST['payment_method'];

    // Insert new expense record into the database
    $stmt = $conn->prepare("INSERT INTO expenses (date, category, amount, description, payment_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $date, $category, $amount, $description, $payment_method);

    if ($stmt->execute()) {
        echo "<script>alert('Expense record added successfully!'); window.location.href='farmfinance.php';</script>";
    } else {
        echo "<script>alert('Error adding expense record.');</script>";
    }
    $stmt->close();
}

// Handle form submission for adding a new budget record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_budget'])) {
    $budget_name = $_POST['budget_name'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $remarks = $_POST['remarks'];

    // Insert new budget record into the database
    $stmt = $conn->prepare("INSERT INTO budgets (budget_name, category, amount, start_date, end_date, remarks) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsss", $budget_name, $category, $amount, $start_date, $end_date, $remarks);

    if ($stmt->execute()) {
        echo "<script>alert('Budget record added successfully!'); window.location.href='farmfinance.php';</script>";
    } else {
        echo "<script>alert('Error adding budget record.');</script>";
    }
    $stmt->close();
}

// Handle form submission for adding a new loan record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_loan'])) {
    $loan_date = $_POST['loan_date'];
    $lender = $_POST['lender'];
    $loan_amount = $_POST['loan_amount'];
    $interest_rate = $_POST['interest_rate'];
    $repayment_period = $_POST['repayment_period'];
    $remaining_balance = $_POST['remaining_balance'];
    $remarks = $_POST['remarks'];

    // Insert new loan record into the database
    $stmt = $conn->prepare("INSERT INTO loans (loan_date, lender, loan_amount, interest_rate, repayment_period, remaining_balance, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddsss", $loan_date, $lender, $loan_amount, $interest_rate, $repayment_period, $remaining_balance, $remarks);

    if ($stmt->execute()) {
        echo "<script>alert('Loan record added successfully!'); window.location.href='farmfinance.php';</script>";
    } else {
        echo "<script>alert('Error adding loan record.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Finance</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="../js/farm_finance.js" defer></script>
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
                <h1 class="h2">Farm Finance</h1>
            </div>

            <!-- Tabs for Income, Expenses, Budgets, and Loans -->
            <ul class="nav nav-tabs" id="financeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="income-tab" data-bs-toggle="tab" data-bs-target="#income" type="button" role="tab" aria-controls="income" aria-selected="true">Income</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab" aria-controls="expenses" aria-selected="false">Expenses</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="budgets-tab" data-bs-toggle="tab" data-bs-target="#budgets" type="button" role="tab" aria-controls="budgets" aria-selected="false">Budgets</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="loans-tab" data-bs-toggle="tab" data-bs-target="#loans" type="button" role="tab" aria-controls="loans" aria-selected="false">Loans</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="financeTabsContent">
                <!-- Income Tab -->
                <div class="tab-pane fade show active" id="income" role="tabpanel" aria-labelledby="income-tab">
                    <h3 class="mt-3">Income Records</h3>
                    <!-- Add Income Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addIncomeModal">Add Income</button>
                    <!-- Income Records Table -->
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Source</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Payment Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="incomeTable">
                            <?php while ($row = $income_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['source']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['payment_method']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['id']; ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Expenses Tab -->
                <div class="tab-pane fade" id="expenses" role="tabpanel" aria-labelledby="expenses-tab">
                    <h3 class="mt-3">Expense Records</h3>
                    <!-- Add Expense Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addExpenseModal">Add Expense</button>
                    <!-- Expense Records Table -->
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Payment Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="expenseTable">
                            <?php while ($row = $expenses_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['category']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['payment_method']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['id']; ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Budgets Tab -->
                <div class="tab-pane fade" id="budgets" role="tabpanel" aria-labelledby="budgets-tab">
                    <h3 class="mt-3">Budget Records</h3>
                    <!-- Add Budget Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addBudgetModal">Add Budget</button>
                    <!-- Budget Records Table -->
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Budget Name</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="budgetTable">
                            <?php while ($row = $budgets_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['budget_name']; ?></td>
                                <td><?php echo $row['category']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['start_date']; ?></td>
                                <td><?php echo $row['end_date']; ?></td>
                                <td><?php echo $row['remarks']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['id']; ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Loans Tab -->
                <div class="tab-pane fade" id="loans" role="tabpanel" aria-labelledby="loans-tab">
                    <h3 class="mt-3">Loan Records</h3>
                    <!-- Add Loan Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addLoanModal">Add Loan</button>
                    <!-- Loan Records Table -->
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Loan Date</th>
                                <th>Lender</th>
                                <th>Loan Amount</th>
                                <th>Interest Rate</th>
                                <th>Repayment Period</th>
                                <th>Remaining Balance</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="loanTable">
                            <?php while ($row = $loans_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['loan_date']; ?></td>
                                <td><?php echo $row['lender']; ?></td>
                                <td><?php echo $row['loan_amount']; ?></td>
                                <td><?php echo $row['interest_rate']; ?></td>
                                <td><?php echo $row['repayment_period']; ?></td>
                                <td><?php echo $row['remaining_balance']; ?></td>
                                <td><?php echo $row['remarks']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['id']; ?>">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
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

<!-- Add Income Modal -->
<div class="modal fade" id="addIncomeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Income</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addIncomeForm" method="POST">
                    <div class="mb-3">
                        <label>Date:</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Source:</label>
                        <input type="text" name="source" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Amount:</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description:</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Payment Method:</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="M-Pesa">M-Pesa</option>
                        </select>
                    </div>
                    <button type="submit" name="add_income" class="btn btn-primary">Save Income</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addExpenseForm" method="POST">
                    <div class="mb-3">
                        <label>Date:</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Category:</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Amount:</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description:</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Payment Method:</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="M-Pesa">M-Pesa</option>
                        </select>
                    </div>
                    <button type="submit" name="add_expense" class="btn btn-primary">Save Expense</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Budget Modal -->
<div class="modal fade" id="addBudgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Budget</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addBudgetForm" method="POST">
                    <div class="mb-3">
                        <label>Budget Name:</label>
                        <input type="text" name="budget_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Category:</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Amount:</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Start Date:</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>End Date:</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Remarks:</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                    <button type="submit" name="add_budget" class="btn btn-primary">Save Budget</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Loan Modal -->
<div class="modal fade" id="addLoanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Loan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addLoanForm" method="POST">
                    <div class="mb-3">
                        <label>Loan Date:</label>
                        <input type="date" name="loan_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Lender:</label>
                        <input type="text" name="lender" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Loan Amount:</label>
                        <input type="number" step="0.01" name="loan_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Interest Rate:</label>
                        <input type="number" step="0.01" name="interest_rate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Repayment Period:</label>
                        <input type="text" name="repayment_period" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Remaining Balance:</label>
                        <input type="number" step="0.01" name="remaining_balance" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Remarks:</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                    <button type="submit" name="add_loan" class="btn btn-primary">Save Loan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 

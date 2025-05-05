<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's bills
$user_id = $_SESSION['user_id'];
$bills_sql = "SELECT * FROM bills WHERE user_id=?";
$stmt = $conn->prepare($bills_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bills_result = $stmt->get_result();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles */
        .container {
            margin-top: 50px; /* Add some top margin */
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Payments</h2>

    <table class="table table-striped table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($bills_result->num_rows > 0) {
                while($row = $bills_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>"; // Prevent XSS
                    echo "<td>" . htmlspecialchars($row["amount"]) . "</td>"; // Prevent XSS
                    echo "<td>" . htmlspecialchars($row["due_date"]) . "</td>"; // Prevent XSS
                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>"; // Prevent XSS
                    echo "<td>";
                    if ($row["status"] === 'pending') {
                        echo "<a href='mpesa_payment.php?bill_id=" . $row["id"] . "&amount=" . $row["amount"] . "' class='btn btn-success btn-sm'>Pay with M-Pesa</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No bills found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <button class="back-button">
  ← Back
</button>
<style>
    .back-button {
  padding: 10px 15px 10px 10px;
  background-color: transparent;
  border: none;
  color: #4285f4;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
}

.back-button:hover {
background-color: #3367d6;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
</style>
<script>
    document.querySelector('.back-button').addEventListener('click', () => {
  window.history.back();
});
</script>
</div>

<!-- Bootstrap JS and dependencies (optional) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
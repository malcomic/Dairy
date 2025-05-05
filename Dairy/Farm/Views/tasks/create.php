<?php
// views/tasks/create.php

require_once '../../config/database.php';
require_once '../../models/User.php';
require_once '../../email/send_mail.php';

// Function to fetch users
function getUsers($pdo) {
    try {
        return User::getUsersByRole($pdo, ['farmer', 'veterinary']);
    } catch (PDOException $e) {
        return ['error' => "Database error: " . $e->getMessage()];
    }
}

// Function to send task notifications.
function sendTaskNotificationWrapper($pdo,$userId, $title, $description, $deadline){
    try {
        $user = User::getUserById($pdo, $userId);
        if ($user && isset($user['email'])) {
            return sendTaskNotification($user['email'], $title, $description, $deadline);
        } else {
            return "User email not found.";
        }
    } catch (PDOException $e) {
        return "Database error: " . $e->getMessage();
    }
}

$users = getUsers($pdo);
$message = ''; // For feedback messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $deadline = $_POST['deadline'];

    // Basic Validation
    if (empty($title) || empty($description) || empty($deadline)) {
        $message = '<div class="alert alert-danger">Please fill in all fields.</div>';
    } else {

        //Email Sending
        $emailResult = sendTaskNotificationWrapper($pdo, $userId, $title, $description, $deadline);

        if($emailResult === true){
            $message = '<div class="alert alert-success">Task created and notification sent!</div>';
        } else if (is_string($emailResult)){
            $message = '<div class="alert alert-warning">Task created, but email failed: ' . $emailResult . '</div>';
        } else {
            $message = '<div class="alert alert-danger">Error sending email.</div>';
        }
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-5">
    <div class="card shadow-lg p-4 border-0" style="background: linear-gradient(135deg, #6a11cb, #2575fc); color: white; border-radius: 15px;">
        <h2 class="text-center mb-4">🚀 Create New Task</h2>
        <?php echo $message; ?>
        <form method="post" action="create.php">
            <div class="mb-3">
                <label for="user_id" class="form-label">👤 Assign To:</label>
                <select name="user_id" id="user_id" class="form-select border-0 shadow-sm">
                    <?php if (isset($users) && is_array($users) && !isset($users['error'])) : ?>
                        <?php foreach($users as $user): ?>
                            <option value="<?php echo $user['id']?>" style="color: black;"><?php echo $user['username']?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">
                            <?php echo isset($users['error']) ? $users['error'] : 'No users found'; ?>
                        </option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">📌 Title:</label>
                <input type="text" name="title" id="title" class="form-control border-0 shadow-sm" placeholder="Enter task title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">📝 Description:</label>
                <textarea name="description" id="description" class="form-control border-0 shadow-sm" rows="3" placeholder="Enter task description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="deadline" class="form-label">📅 Deadline:</label>
                <input type="date" name="deadline" id="deadline" class="form-control border-0 shadow-sm" required>
            </div>
            <button type="submit" class="btn btn-light w-100 fw-bold" style="color: #2575fc; border-radius: 50px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
                ✅ Create Task
            </button>
        </form>
    </div>
    <footer class="text-center mt-4 p-3" style="background: rgba(0, 0, 0, 0.1); border-radius: 10px; color: #333;">
        <p class="mb-0">© <?php echo date("Y"); ?> Malcom | Dairy Farm Management System</p>
    </footer>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let today = new Date().toISOString().split("T")[0];
        document.getElementById("deadline").setAttribute("min", today);
    });
</script>
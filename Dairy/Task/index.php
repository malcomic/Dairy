<?php
// Include database configuration and User model
require_once '../config/database.php'; // Adjust path as needed
require_once '../models/User.php';    // Adjust path as needed

// Fetch users for the dropdown
try {
    $users = User::getUsersByRole($pdo, ['farmer', 'veterinary']); // Adjust roles as needed
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task</title>
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: linear-gradient(135deg, #6e6e6e, #4e4e4e);
            color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 900px;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        .icon-container {
            font-size: 50px;
            color: #fff;
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            font-size: 18px;
        }
        select, textarea {
            background: #fff;
            color: #333;
            font-size: 16px;
            padding: 10px;
        }
        textarea {
            height: 150px;
        }
        button {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            color: white;
            font-size: 18px;
            padding: 12px;
            border-radius: 30px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="icon-container">
            <i class="fas fa-envelope"></i>
        </div>
        <h2>📩 Assign Task</h2>
        <form action="send_mail.php" method="POST">
            <div class="form-group mb-4">
                <label for="email">👤 Select Recipient:</label>
                <select name="email" id="email" class="form-control shadow-sm">
                    <?php if (isset($users) && is_array($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <?php if (isset($user['email'])) : ?>
                                <option value="<?php echo $user['email']; ?>">
                                    <?php echo $user['username'] . ' (' . $user['email'] . ')'; ?>
                                </option>
                            <?php else : ?>
                                <option value="">User email missing</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <option value="">No users found</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="message">📝 Message:</label>
                <textarea name="message" id="message" class="form-control shadow-sm" placeholder="Enter the message to send here"></textarea>
            </div>

            <button type="submit" name="submit" class="btn w-100 fw-bold">
                <i class="fas fa-paper-plane"></i> Send Task
            </button>
        </form>
    </div>

    <script src="js/init.js"></script>
    <script src="js/jquery.dataTables.js"></script>
</body>
</html>

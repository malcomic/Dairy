

<?php
session_start();
require 'connection.php';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $action == 'register') {
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : 'farmer'; // Default user type

    // Input validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: login.php?action=register");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: login.php?action=register");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: login.php?action=register");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if full_name or email already exists
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE full_name = :full_name OR email = :email");
        $stmt->execute(['full_name' => $full_name, 'email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Full Name or Email already taken.";
            header("Location: login.php?action=register");
            exit();
        }

        // Insert new user with user_type
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, user_type) VALUES (:full_name, :email, :password, :user_type)");
        $stmt->execute(['full_name' => $full_name, 'email' => $email, 'password' => $hashed_password, 'user_type' => $user_type]);

        $_SESSION['success'] = "Registration successful! You can now login.";
        header("Location: login.php?action=login");
        exit();
    
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: login.php?action=register");
        exit();
    }
}



// login process
 

//session_start();
//require 'connection.php'; 
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Input validation
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: login.php");
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: Dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link rel="stylesheet" href="./CSS/login.css">
</head>
<body>

<div class="container">
    <div class="form-container">
        <div class="toggle-btns">
            <button class="toggle" id="showLogin">Login</button>
            <button class="toggle" id="showRegister">Register</button>
        </div>

        <!-- Login Form -->
        <form action="Dashboard.php" method="POST" class="form <?= $action == 'register' ? 'hidden' : '' ?>" id="loginForm">
            <h2>Login</h2>
            <input type="text" name="full_name" placeholder="Full Name or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="submit-btn">Login</button>
            <p class="toggle-link">Don't have an account? <a href="#" id="switchToRegister">Register</a></p>
            <p><a href="landing.php">Home</a></p>
        </form>

        <!-- Registration Form -->
        <form action="register_process.php" method="POST" class="form <?= $action == 'login' ? 'hidden' : '' ?>" id="registerForm">
            <h2>Register</h2>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <select name="user_type" required>
                <option value="farmer">Farmer</option>
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" class="submit-btn">Register</button>
            <p class="toggle-link">Already have an account? <a href="#" id="switchToLogin">Login</a></p>
            <p><a href="landing.php">Home</a></p>
        </form>
    </div>
</div>

<script src="./js/login.js"></script>
<script>
    function switchForm(action) {
        window.location.href = "login.php?action=" + action;
    }
</script>

</body>
</html>
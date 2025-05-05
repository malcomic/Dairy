<?php
// Database connection details (replace with your actual credentials)
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "dairy_farm";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $login_error = "Username and password are required.";
    } else {
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = strtolower($user['role']);
                header("Location: ../../index.php");
                exit;
            } else {
                $login_error = "Incorrect password.";
            }
        } else {
            $login_error = "User not found.";
        }
        $stmt->close();
    }
}

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validate inputs
    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $register_error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $register_error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $register_error = "Password must be at least 8 characters long.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $register_error = "Username already exists.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fullname, $email, $username, $hashed_password, $role);
            
            if ($stmt->execute()) {
                $register_success = "Registration successful! You can now login.";
                // Clear form
                $_POST = array();
            } else {
                $register_error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy Farm Management - Login/Register</title>
    <link rel="icon" type="png" href="../../DairyFarmSystem/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .container {
            width: 400px;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            z-index: 1;
            transition: all 0.5s ease;
        }

        .container:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transform: translateY(-5px);
        }

        .form-container {
            position: relative;
        }

        .toggle-btns {
            display: flex;
            justify-content: space-around;
            margin-bottom: 25px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
        }

        .toggle {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            font-weight: 500;
            color: #555;
            padding: 8px 20px;
            position: relative;
            transition: all 0.3s ease;
        }

        .toggle.active {
            color: #2d6a4f;
            font-weight: 600;
        }

        .toggle.active::after {
            content: '';
            position: absolute;
            bottom: -11px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 3px;
            background: #2d6a4f;
            border-radius: 3px;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .hidden {
            display: none;
        }

        h2 {
            color: #2d6a4f;
            margin-bottom: 20px;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f9f9f9;
        }

        input:focus, select:focus {
            border-color: #2d6a4f;
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
            outline: none;
            background: #fff;
        }

        .submit-btn {
            background: #2d6a4f;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
            font-weight: 500;
            margin-top: 15px;
            letter-spacing: 0.5px;
        }

        .submit-btn:hover {
            background: #245c43;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .toggle-link {
            font-size: 14px;
            color: #666;
            margin-top: 15px;
        }

        .toggle-link a {
            color: #2d6a4f;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .toggle-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(231, 76, 60, 0.1);
            border-radius: 5px;
            border-left: 3px solid #e74c3c;
        }

        .success-message {
            color: #27ae60;
            font-size: 14px;
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(39, 174, 96, 0.1);
            border-radius: 5px;
            border-left: 3px solid #27ae60;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2d6a4f;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .back-link:hover {
            text-decoration: underline;
            transform: translateX(-3px);
        }

        .back-link i {
            margin-right: 5px;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="toggle-btns">
            <button class="toggle active" id="showLogin">Login</button>
            <button class="toggle" id="showRegister">Register</button>
        </div>

        <!-- Login Form -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form" id="loginForm">
            <h2>Welcome Back</h2>
            <?php if (isset($login_error)): ?>
                <p class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $login_error; ?></p>
            <?php endif; ?>
            <div style="position: relative;">
                <input type="text" placeholder="Username" id="username" name="username" required>
                <i class="fas fa-user" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777;"></i>
            </div>
            <div style="position: relative;">
                <input type="password" placeholder="Password" id="password" name="password" required>
                <i class="fas fa-lock" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777;"></i>
            </div>
            <button type="submit" name="login" class="submit-btn">Login <i class="fas fa-sign-in-alt"></i></button>
            <p class="toggle-link">Don't have an account? <a href="#" id="switchToRegister">Create one</a></p>
            <a href="../../DairyFarmSystem/landing.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to home page</a>
        </form>

        <!-- Registration Form (hidden by default) -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form hidden" id="registerForm">
            <h2>Create Account</h2>
            <?php if (isset($register_error)): ?>
                <p class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $register_error; ?></p>
            <?php endif; ?>
            <?php if (isset($register_success)): ?>
                <p class="success-message"><i class="fas fa-check-circle"></i> <?php echo $register_success; ?></p>
            <?php endif; ?>
            <div style="position: relative;">
                <input type="char" placeholder="Full Name" name="fullname" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" required>
                <i class="fas fa-user-tag" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777;"></i>
            </div>
            <div style="position: relative;">
                <input type="email" placeholder="Email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                <i class="fas fa-envelope" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777;"></i>
            </div>
            <div style="position: relative;">
        <input type="text" pattern="[A-Za-z]+" title="Only alphabetical characters are allowed" placeholder="Username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
        <i class="fas fa-user" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777;"></i>
    </div>
            <div style="position: relative;">
                <input type="password" placeholder="Password" name="password" required>
                <i class="fas fa-key" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777;"></i>
            </div>
            <div style="position: relative;">
                <input type="password" placeholder="Confirm Password" name="confirm_password" required>
                <i class="fas fa-key" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777;"></i>
            </div>
            <div style="position: relative;">
                <select name="role" required>
                    <option value="admin" disabled selected>Select your role</option>
                    <option value="farmer" <?php echo (isset($_POST['role']) && $_POST['role'] == 'farmer') ? 'selected' : ''; ?>>Farmer</option>
                    <option value="employee" <?php echo (isset($_POST['role']) && $_POST['role'] == 'employee') ? 'selected' : ''; ?>>Vet</option>
                </select>
                <i class="fas fa-user-tie" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777; pointer-events: none;"></i>
            </div>
            <button type="submit" name="register" class="submit-btn">Register <i class="fas fa-user-plus"></i></button>
            <p class="toggle-link">Already have an account? <a href="#" id="switchToLogin">Sign in</a></p>
            <a href="../../DairyFarmSystem/landing.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to home page</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const showLogin = document.getElementById('showLogin');
        const showRegister = document.getElementById('showRegister');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');

        function showLoginForm() {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            showLogin.classList.add('active');
            showRegister.classList.remove('active');
        }

        function showRegisterForm() {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            showLogin.classList.remove('active');
            showRegister.classList.add('active');
        }

        showLogin.addEventListener('click', showLoginForm);
        showRegister.addEventListener('click', showRegisterForm);
        switchToRegister.addEventListener('click', function(e) {
            e.preventDefault();
            showRegisterForm();
        });
        switchToLogin.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginForm();
        });

        // Show register form if there are register messages
        <?php if (isset($register_error) || isset($register_success)): ?>
            showRegisterForm();
        <?php endif; ?>

        // Password strength indicator (optional)
        const passwordInput = document.querySelector('input[name="password"]');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                // You can add password strength meter here
            });
        }
    });
</script>
</body>
</html>
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } else {
        $sql = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                header("Location: ../../index.php"); // Adjust path if needed
                exit;
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "User not found.";
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
    <title>Login</title>
    <link rel="icon" type="png" href="../../DairyFarmSystem/images/logo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #2d6a4f, #495057);
        }

        .container {
            width: 350px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container {
            position: relative;
        }

        .toggle-btns {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .toggle {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            color: #2d6a4f;
            padding: 5px 15px;
        }

        .toggle.active {
            border-bottom: 2px solid #2d6a4f;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .hidden {
            display: none;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #adb5bd;
            border-radius: 5px;
            font-size: 14px;
        }

        .submit-btn {
            background: #2d6a4f;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 16px;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #1b4332;
        }

        .toggle-link {
            font-size: 14px;
            color: #495057;
        }

        .toggle-link a {
            color: #2d6a4f;
            text-decoration: none;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-bottom: 10px;
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
            <h2>Login</h2>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <input type="text" placeholder="Username" id="username" name="username" required>
            <input type="password" placeholder="Password" id="password" name="password" required>
            <button type="submit" class="submit-btn">Login</button>
            <p class="toggle-link">Don't have an account? <a href="#" id="switchToRegister">Register</a></p>
            <p class="toggle-link"> <a href="../../DairyFarmSystem/landing.php" ><b>Back to home page</b></a></p>
        </form>

        <!-- Registration Form (hidden by default) -->
        <form action="#" class="form hidden" id="registerForm">
            <h2>Register</h2>
            <input type="text" placeholder="Full Name" required>
            <input type="email" placeholder="Email" required>
            <input type="password" placeholder="Password" required>
            <input type="password" placeholder="Confirm Password" required>
            <select required style="width: 100%; padding: 10px; border: 1px solid #adb5bd; border-radius: 5px; font-size: 14px;">
                <option value="farmer">Farmer</option>
                <option value="employee">Employee</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" class="submit-btn">Register</button>
            <p class="toggle-link">Already have an account? <a href="#" id="switchToLogin">Login</a></p>
            <p class="toggle-link"> <a href="../../DairyFarmSystem/landing.php" ><b>Back to home page</b></a></p>
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
    });
</script>
</body>
</html>
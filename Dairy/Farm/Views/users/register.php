<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
        }
        .container {
            background-color: white; /* White background for the form */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            padding: 20px; /* Padding around the form */
            margin-top: 50px; /* Margin from the top */
        }
        h2 {
            color: #333; /* Darker color for the heading */
            margin-bottom: 20px; /* Space below the heading */
        }
        .form-control, .form-select {
            margin-bottom: 15px; /* Space between form fields */
        }
        .btn-primary {
            background-color: #007bff; /* Primary button color */
            border: none; /* Remove border */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="post" action="/index.php?action=register">
            <div class="form-outline mb-4">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="form-outline mb-4">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-outline mb-4">
                <label for="role" class="form-label">Role:</label>
                <select name="role" class="form-select" required>
                    <option value="manager">Manager</option>
                    <option value="employee">Employee</option>
                    <option value="vet">Veterinarian</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <br>
        <a href="login.php" class="btn btn-link">Login</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
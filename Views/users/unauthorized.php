<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Unauthorized Access</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
    <div class="w3-container w3-display-middle w3-card-4 w3-light-grey" style="max-width:600px">
        <div class="w3-panel w3-red">
            <h3>Access Denied</h3>
            <p>You don't have permission to view this page.</p>
        </div>
        <p>Your role: <?= strtoupper($_SESSION['user_role'] ?? 'guest') ?></p>
        <p>Required role: <?= isset($allowedRoles) ? implode(' or ', $allowedRoles) : 'Unknown' ?></p>
        <a href="../../DairyFarmSystem/landing.php" class="w3-button w3-blue">Return to Dashboard</a>
    </div>
</body>
</html>
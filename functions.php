<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function checkRole($allowedRoles) {
    if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $allowedRoles)) {
        header("Location: /Dairy/views/users/unauthorized.php");
        exit();
    }
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: /Dairyviews/users/login.php");
        exit();
    }
}
?>
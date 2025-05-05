<?php
//session_start();
//if (!isset($_SESSION['user_id'])) {
  //  header("Location: Dashboard.php");
    //exit();
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy Farm Management Dashboard</title>
    <link rel="stylesheet" href="./CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="#">Animal Records</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="#">Milking Records</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="#">Breeding</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="#">Farm Finance</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="#">Milk Sales</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="#">Stock Feed</a>
                        </li>
                    </ul>
                </div>
            </nav>
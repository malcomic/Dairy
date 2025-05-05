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
                            <a class="nav-link" href="Dashboard.php">Home</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="./animalrecord.php">Animal Records</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="./dashboard/milkrecords.php">Milking Records</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="./dashboard/breeding.php">Breeding</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="./dashboard/farmfinance.php">Farm Finance</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="./dashboard/milksales.php">Milk Sales</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="./dashboard/stockfeeds.php">Stock Feed</a>
                        </li>
                        <li class="nav-item mb-3">
                            <a class="nav-link" href="./logout.php">Log Out</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="pt-3 pb-2 mb-3">
                    <h1 class="h2">Dashboard Overview</h1>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Total Animals</h5>
                                <p class="fs-4">120</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Milk Production (L)</h5>
                                <p class="fs-4">450</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Revenue (Ksh)</h5>
                                <p class="fs-4">75,000</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5>Health Alerts</h5>
                                <p class="fs-4">3</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">Milk Production Trend</div>
                            <div class="card-body">
                                <canvas id="milkTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">Revenue vs Expenses</div>
                            <div class="card-body">
                                <canvas id="revenueExpensesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications and Recent Activities -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">Notifications</div>
                            <div class="card-body">
                                <ul>
                                    <li>Stock feed running low!</li>
                                    <li>Next milking session at 4 PM.</li>
                                    <li>Health checkup scheduled for cow #45.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">Recent Activities</div>
                            <div class="card-body">
                                <ul>
                                    <li>Milking record updated for today.</li>
                                    <li>New animal added to the database.</li>
                                    <li>Farm revenue updated.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/dashboard.js"></script>
    <?php
        include('footer.php'); // Include footer.php
    ?> 
</body>
</html>

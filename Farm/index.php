<?php
require_once 'config/database.php';
require_once 'functions.php';
require_once 'models/User.php';
require_once 'models/Cow.php';
require_once 'models/MilkProduction.php';
require_once 'models/FeedRecord.php';
require_once 'models/HealthRecord.php';

requireLogin(); // Ensure the user is logged in

// Check user role
$userRole = $_SESSION['user_role'];

$cow = new Cow($pdo);
$milkProduction = new MilkProduction($pdo);
$feedRecord = new FeedRecord($pdo);
$healthRecord = new HealthRecord($pdo);

// Initialize variables
$totalCows = 0;
$todayMilk = 0;
$averageAge = 0;

// Data fetching logic
$cows = $cow->getCowsFiltered();
$totalCows = count($cows);
$today = date('Y-m-d');
$todayRecords = $milkProduction->getMilkRecords(null, $today, $today);
foreach ($todayRecords as $record) {
    $todayMilk += $record['milk_yield'];
}

$totalAge = 0;
if ($totalCows > 0) {
    foreach ($cows as $aCow) {
        $totalAge += $aCow['age'];
    }
    $averageAge = $totalAge / $totalCows;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dairy Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ... (Your existing styles) ... */
    </style>
</head>
<body class="w3-light-grey">

<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i> Menu</button>
    <span class="w3-bar-item w3-right">Malcom||Dairy System</span>
</div>

<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
    <div class="w3-container">
        <h5>Dashboard</h5>
    </div>
    <div class="w3-bar-block">
        <a href="index.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i> Overview</a>

        <?php if ($userRole === 'admin' || $userRole === 'farmer') : ?>
            <a href="#" class="w3-bar-item w3-button w3-padding" data-bs-toggle="modal" data-bs-target="#addCowModal"><i class="fa fa-plus fa-fw"></i> Add Cow</a>
            <a href="views/cows/list.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-list fa-fw"></i> Cow List</a>
            <a href="#" class="w3-bar-item w3-button w3-padding" data-bs-toggle="modal" data-bs-target="#addMilkModal"><i class="fa fa-plus fa-fw"></i> Add Milk Record</a>
            <a href="views/milk/list.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-list fa-fw"></i> Milk Records</a>
            <a href="#" class="w3-bar-item w3-button w3-padding" data-bs-toggle="modal" data-bs-target="#addFeedModal"><i class="fa fa-plus fa-fw"></i> Add Feed Record</a>
            <a href="views/feed/list.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-list fa-fw"></i> Feed Records</a>
        <?php endif; ?>

        <?php if ($userRole === 'admin' || $userRole === 'vet') : ?>
            <a href="#" class="w3-bar-item w3-button w3-padding" data-bs-toggle="modal" data-bs-target="#addHealthModal"><i class="fa fa-plus fa-fw"></i> Add Health Record</a>
            <a href="views/health/list.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-list fa-fw"></i> Health Records</a>
        <?php endif; ?>

        <a href="Dairy/index.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-comment fa-fw"></i> Chatbot</a>

        <?php if ($userRole === 'admin') : ?>
            <a href="reports/generate_report.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-download fa-fw"></i> Download Report</a>
            <a href="http://localhost/Dairy/Task/" class="w3-bar-item w3-button w3-padding"><i class="fa fa-tasks fa-fw"></i> Assign Task</a>
        <?php endif; ?>

        <a href="views/users/login.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
    </div>
</nav>

<div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-dashboard"></i> My Dashboard</b></h5>
    </header>

    <?php
    // Example: Display content based on the user's role
    if ($userRole === 'admin') {
        echo "<h1>Welcome, Admin!</h1>";
        // Display admin-specific content
    } elseif ($userRole === 'vet') {
        echo "<h1>Welcome, Vet!</h1>";
        // Display vet-specific content
    } elseif ($userRole === 'farmer') {
        echo "<h1>Welcome, Farmer!</h1>";
        // Display farmer-specific content
    }
    ?>

    <div class="w3-row-padding w3-margin-bottom">
        <div class="w3-quarter">
            <div class="w3-container w3-red w3-padding-16">
                <div class="w3-left"><i class="fa fa-cow w3-xxxlarge"></i></div>
                <div class="w3-right">
                    <h3><?php echo htmlspecialchars($totalCows); ?></h3>
                </div>
                <div class="w3-clear"></div>
                <h4>Total Cows</h4>
            </div>
        </div>
        <div class="w3-quarter">
            <div class="w3-container w3-blue w3-padding-16">
                <div class="w3-left"><i class="fa fa-tint w3-xxxlarge"></i></div>
                <div class="w3-right">
                    <h3><?php echo htmlspecialchars(number_format($todayMilk, 2)); ?></h3>
                </div>
                <div class="w3-clear"></div>
                <h4>Milk Today (Litres)</h4>
            </div>
        </div>
        <div class="w3-quarter">
            <div class="w3-container w3-teal w3-padding-16">
                <div class="w3-left"><i class="fa fa-calendar w3-xxxlarge"></i></div>
                <div class="w3-right">
                    <h3><?php echo htmlspecialchars(number_format($averageAge, 1)); ?></h3>
                </div>
                <div class="w3-clear"></div>
                <h4>Average Cow Age (Years)</h4>
            </div>
        </div>
    </div>

    <div class="w3-panel">
        <div class="w3-row-padding" style="margin:0 -16px">
            <div class="w3-twothird">
                <h5>Milk Production Chart</h5>
                <canvas id='milkChart' width='400' height='200'></canvas>
            </div>
        </div>
    </div>

    <script>
        try {
            const ctx = document.getElementById('milkChart').getContext('2d');
            const milkChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [<?php
                        $milkData = $milkProduction->getMilkRecords();
                        $labels = array_unique(array_column($milkData, 'date'));
                        echo "'" . implode("','", $labels) . "'";
                        ?>],
                    datasets: [{
                        label: 'Milk Production (Liters)',
                        data: [<?php
                            $milkData = $milkProduction->getMilkRecords();
                            $milkValues = array();
                            foreach ($labels as $date) {
                                $totalMilk = 0;
                                foreach ($milkData as $milkRecord) {
                                    if ($milkRecord['date'] === $date) {
                                        $totalMilk += $milkRecord['milk_yield'];
                                    }
                                }
                                $milkValues[] = $totalMilk;
                            }
                            echo implode(',', $milkValues);
                            ?>],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            console.error("Error creating milk chart:", error);
        }
    </script>
</div>

<div class="modal fade" id="addCowModal" tabindex="-1" aria-labelledby="addCowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCowModalLabel">Add Cow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="index.php?action=add_cow">
                    <div class="mb-3">
                        <label for="cow_id" class="form-label">Cow ID</label>
                        <input type="text" class="form-control" id="cow_id" name="cow_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="breed" class="form-label">Breed</label>
                        <input type="text" class="form-control" id="breed" name="breed" required>
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age" required>
                    </div>
                    <div class="mb-3">
                        <label for="health_status" class="form-label">Health Status</label>
                        <input type="text" class="form-control" id="health_status" name="health_status" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Cow</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addFeedModal" tabindex="-1" aria-labelledby="addFeedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFeedModalLabel">Add Feed Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="index.php?action=add_feed">
                    <div class="mb-3">
                        <label for="cow_id" class="form-label">Cow ID</label>
                        <select class="form-select" id="cow_id" name="cow_id" required>
                            <?php if (!empty($cows)): ?>
                                <?php foreach ($cows as $aCow): ?>
                                    <option value="<?php echo htmlspecialchars($aCow['id']); ?>"><?php echo htmlspecialchars($aCow['cow_id']); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No cows found</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="feed_type" class="form-label">Feed Type</label>
                        <input type="text" class="form-control" id="feed_type" name="feed_type" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" class="form-control" id="unit" name="unit" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Feed Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMilkModal" tabindex="-1" aria-labelledby="addMilkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMilkModalLabel">Add Milk Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="index.php?action=add_milk">
                    <div class="mb-3">
                        <label for="cow_id" class="form-label">Cow ID</label>
                        <select class="form-select" id="cow_id" name="cow_id" required>
                            <?php if (!empty($cows)): ?>
                                <?php foreach ($cows as $aCow): ?>
                                    <option value="<?php echo htmlspecialchars($aCow['id']); ?>"><?php echo htmlspecialchars($aCow['cow_id']); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No cows found</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="milk_yield" class="form-label">Milk Yield (Liters)</label>
                        <input type="number" step="0.01" class="form-control" id="milk_yield" name="milk_yield" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Milk Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addHealthModal" tabindex="-1" aria-labelledby="addHealthModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHealthModalLabel">Add Health Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="index.php?action=add_health">
                    <div class="mb-3">
                        <label for="cow_id" class="form-label">Cow ID</label>
                        <select class="form-select" id="cow_id" name="cow_id" required>
                            <?php if (!empty($cows)): ?>
                                <?php foreach ($cows as $aCow): ?>
                                    <option value="<?php echo htmlspecialchars($aCow['id']); ?>"><?php echo htmlspecialchars($aCow['cow_id']); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No cows found</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="health_status" class="form-label">Health Status</label>
                        <input type="text" class="form-control" id="health_status" name="health_status" required>
                    </div>
                    <div class="mb-3">
                        <label for="diagnosis" class="form-label">Diagnosis</label>
                        <input type="text" class="form-control" id="diagnosis" name="diagnosis" required>
                    </div>
                    <div class="mb-3">
                        <label for="treatment" class="form-label">Treatment</label>
                        <input type="text" class="form-control" id="treatment" name="treatment" required>
                    </div>
                    <div class="mb-3">
                        <label for="vet" class="form-label">Veterinarian</label>
                        <input type="text" class="form-control" id="vet" name="vet" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Health Record</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var mySidebar = document.getElementById("mySidebar");
    var overlayBg = document.getElementById("myOverlay");

    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }
    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
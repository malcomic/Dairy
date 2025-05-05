<?php
// Start session and error reporting at the very beginning
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in before proceeding
if (!isset($_SESSION['user_id'])) {
    header("Location: views/users/login.php");
    exit();
}

// Database and model includes
require_once 'config/database.php';
require_once 'functions.php';
require_once 'models/User.php';
require_once 'models/Cow.php';
require_once 'models/MilkProduction.php';
require_once 'models/FeedRecord.php';
require_once 'models/HealthRecord.php';

// Validate and set user role with default fallback
$validRoles = ['admin', 'farmer', 'vet'];
$userRole = isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], $validRoles) 
    ? $_SESSION['user_role'] 
    : 'admin'; // Default role if not set or invalid

// Initialize models
try {
    $cow = new Cow($pdo);
    $milkProduction = new MilkProduction($pdo);
    $feedRecord = new FeedRecord($pdo);
    $healthRecord = new HealthRecord($pdo);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize dashboard variables
$dashboardData = [
    'totalCows' => 0,
    'todayMilk' => 0,
    'averageAge' => 0,
    'healthPercentage' => 96
];

// Data fetching logic with error handling
try {
    $cows = $cow->getCowsFiltered();
    $dashboardData['totalCows'] = count($cows);
    
    $today = date('Y-m-d');
    $todayRecords = $milkProduction->getMilkRecords(null, $today, $today);
    
    foreach ($todayRecords as $record) {
        $dashboardData['todayMilk'] += $record['milk_yield'];
    }

    $totalAge = 0;
    if ($dashboardData['totalCows'] > 0) {
        foreach ($cows as $aCow) {
            $totalAge += $aCow['age'];
        }
        $dashboardData['averageAge'] = $totalAge / $dashboardData['totalCows'];
    }
} catch (Exception $e) {
    error_log("Dashboard data error: " . $e->getMessage());
    $errorMessage = "Could not load all dashboard data. Please try again later.";
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && $_GET['action'] === 'add_feed') {
        try {
            // Validate required fields
            $requiredFields = ['cow_id', 'date', 'feed_type', 'quantity', 'unit'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                throw new Exception("Missing required fields: " . implode(', ', $missingFields));
            }

            // Validate and sanitize inputs
            $feedData = [
                'cow_id' => filter_var($_POST['cow_id'], FILTER_VALIDATE_INT),
                'date' => htmlspecialchars($_POST['date']),
                'feed_type' => htmlspecialchars($_POST['feed_type']),
                'quantity' => filter_var($_POST['quantity'], FILTER_VALIDATE_FLOAT),
                'unit' => htmlspecialchars($_POST['unit'])
            ];

            if ($feedData['cow_id'] === false || $feedData['quantity'] === false) {
                throw new Exception("Invalid numeric values provided");
            }

            // Add record
            $result = $feedRecord->addFeedRecord(
                $feedData['cow_id'],
                $feedData['date'],
                $feedData['feed_type'],
                $feedData['quantity'],
                $feedData['unit']
            );

            if ($result) {
                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'message' => 'Feed record added successfully!'
                ];
                header("Location: views/feed/list.php");
                exit();
            } else {
                throw new Exception("Failed to add feed record to database");
            }
        } catch (Exception $e) {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
            header("Location: views/feed/add.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy Farm Management System</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="./logo.png">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            overflow-x: hidden;
        }

        .w3-main {
            margin-left: var(--sidebar-width);
            margin-top: 60px;
            padding: 20px;
            transition: all 0.3s;
        }

        /* Top Navigation */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .top-nav .logo {
            height: 40px;
            margin-right: 15px;
        }

        .top-nav .system-title {
            font-weight: 600;
            font-size: 1.2rem;
            margin-right: auto;
        }

        .top-nav .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - 60px);
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            z-index: 900;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .sidebar-menu {
            padding: 10px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #555;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }

        .sidebar-menu a.active {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
        }

        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Dashboard Cards */
        .dashboard-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .dashboard-card .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .dashboard-card .card-value {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .dashboard-card .card-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .dashboard-card .card-progress {
            height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
            margin-top: 15px;
            overflow: hidden;
        }

        .dashboard-card .card-progress-bar {
            height: 100%;
            background: white;
            border-radius: 2px;
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }

        .content-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .content-card .card-title {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .content-card .card-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .w3-main {
                margin-left: 0;
            }

            .top-nav .menu-toggle {
                display: block;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Utility Classes */
        .text-primary { color: var(--primary-color) !important; }
        .bg-primary { background-color: var(--primary-color) !important; }
        .text-success { color: var(--success-color) !important; }
        .bg-success { background-color: var(--success-color) !important; }
        .text-warning { color: var(--warning-color) !important; }
        .bg-warning { background-color: var(--warning-color) !important; }
        .text-danger { color: var(--danger-color) !important; }
        .bg-danger { background-color: var(--danger-color) !important; }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Welcome Panel */
        .welcome-panel {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(67, 97, 238, 0.2);
        }

        /* Task Item */
        .task-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            transition: all 0.2s;
        }

        .task-item:hover {
            background-color: #f8f9fa;
        }

        .task-item:last-child {
            border-bottom: none;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        /* Flash Messages */
        .flash-message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .flash-message.success {
            background-color: rgba(76, 201, 240, 0.1);
            border-left: 4px solid var(--success-color);
            color: #155724;
        }

        .flash-message.error {
            background-color: rgba(247, 37, 133, 0.1);
            border-left: 4px solid var(--danger-color);
            color: #721c24;
        }

        .flash-message .close-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
        }

        .flash-message .close-btn:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <img src="./logo.png" alt="System Logo" class="logo">
        <div class="system-title">Dairy Farm Management System</div>
    </div>

    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5>Dashboard Menu</h5>
        </div>
        <div class="sidebar-menu">
            <a href="index.php" class="active">
                <i class="fas fa-home"></i> Overview
            </a>

            <?php if ($userRole === 'admin' || $userRole === 'farmer') : ?>
                <a href="views/cows/add.php">
                    <i class="fas fa-plus"></i> Add Cow
                </a>
                <a href="views/cows/list.php">
                    <i class="fas fa-list"></i> Cow List
                </a>
                <a href="views/milk/add.php">
                    <i class="fas fa-plus"></i> Add Milk Record
                </a>
                <a href="views/milk/list.php">
                    <i class="fas fa-list"></i> Milk Records
                </a>
                <a href="views/feed/add.php">
                    <i class="fas fa-plus"></i> Add Feed Record
                </a>
                <a href="views/feed/list.php">
                    <i class="fas fa-list"></i> Feed Records
                </a>
            <?php endif; ?>

            <?php if ($userRole === 'admin' || $userRole === 'vet') : ?>
                <a href="views/health/add.php">
                    <i class="fas fa-plus"></i> Add Health Record
                </a>
                <a href="views/health/list.php">
                    <i class="fas fa-list"></i> Health Records
                </a>
            <?php endif; ?>

            <?php if ($userRole === 'admin') : ?>
                <a href="reports/generate_report.php">
                    <i class="fas fa-download"></i> Download Report
                </a>
                <a href="http://localhost/Dairy/Task/">
                    <i class="fas fa-tasks"></i> Assign Task
                </a>
            <?php endif; ?>
            
            <a href="Dairy/index.php">
                <i class="fas fa-comment"></i> Chatbot
            </a>
            
            <a href="./logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="w3-main">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_message'])) : ?>
            <div class="flash-message <?= $_SESSION['flash_message']['type'] ?> animate-fade-in">
                <div>
                    <i class="fas fa-<?= $_SESSION['flash_message']['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= htmlspecialchars($_SESSION['flash_message']['message']) ?>
                </div>
                <button class="close-btn" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <!-- Error Message (if any) -->
        <?php if (isset($errorMessage)) : ?>
            <div class="flash-message error animate-fade-in">
                <div>
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
                <button class="close-btn" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>

        <!-- Welcome Message -->
        <div class="welcome-panel animate-fade-in">
            <h3>
                <?php 
                switch($userRole) {
                    case 'admin': echo "Welcome, Administrator!"; break;
                    case 'vet': echo "Welcome, Veterinarian!"; break;
                    case 'farmer': echo "Welcome, Farmer!"; break;
                    default: echo "Welcome to Dairy Farm Management System!";
                }
                ?>
            </h3>
            <p>Today is <?= date('l, F j, Y') ?></p>
        </div>

        <!-- Dashboard Cards -->
        <div class="w3-row-padding">
            <div class="w3-col l3 m6 s12 animate-fade-in" style="animation-delay: 0.1s">
                <div class="dashboard-card" style="background: linear-gradient(135deg, #FF6B6B, #FF8E8E);">
                    <div class="card-icon">
                        <i class="fas fa-cow"></i>
                    </div>
                    <div class="card-value"><?= htmlspecialchars($dashboardData['totalCows']) ?></div>
                    <div class="card-label">Total Cows</div>
                    <div class="card-progress">
                        <div class="card-progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <div class="w3-col l3 m6 s12 animate-fade-in" style="animation-delay: 0.2s">
                <div class="dashboard-card" style="background: linear-gradient(135deg, #4ECDC4, #88D8C0);">
                    <div class="card-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="card-value"><?= htmlspecialchars(number_format($dashboardData['todayMilk'], 2)) ?></div>
                    <div class="card-label">Milk Today (Liters)</div>
                    <div class="card-progress">
                        <div class="card-progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <div class="w3-col l3 m6 s12 animate-fade-in" style="animation-delay: 0.3s">
                <div class="dashboard-card" style="background: linear-gradient(135deg, #FF9A8B, #FF6B95);">
                    <div class="card-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="card-value"><?= htmlspecialchars(number_format($dashboardData['averageAge'], 1)) ?></div>
                    <div class="card-label">Average Cow Age</div>
                    <div class="card-progress">
                        <div class="card-progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <div class="w3-col l3 m6 s12 animate-fade-in" style="animation-delay: 0.4s">
                <div class="dashboard-card" style="background: linear-gradient(135deg, #A18CD1, #FBC2EB);">
                    <div class="card-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="card-value"><?= htmlspecialchars($dashboardData['healthPercentage']) ?>%</div>
                    <div class="card-label">Herd Health</div>
                    <div class="card-progress">
                        <div class="card-progress-bar" style="width: <?= $dashboardData['healthPercentage'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content Sections -->
        <div class="w3-row-padding">
            <!-- Top Producing Animals -->
            <div class="w3-col l4 m12 s12">
                <div class="content-card animate-fade-in" style="animation-delay: 0.2s">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-trophy"></i> Top Producing Animals</h5>
                    </div>
                    
                    <?php
                    try {
                        // Verify database connection
                        if (!$pdo) {
                            throw new Exception("Database connection not established");
                        }

                        // Fetch top 3 milk-producing cows from the database
                        $sql = "SELECT 
                                    c.id, 
                                    c.name, 
                                    c.tag_number, 
                                    c.health_status,
                                    SUM(m.milk_yield) as total_milk,
                                    MAX(m.date) as last_milked
                                FROM cows c
                                LEFT JOIN milk_production m ON c.id = m.cow_id
                                GROUP BY c.id
                                ORDER BY total_milk DESC
                                LIMIT 3";
                        
                        $stmt = $pdo->query($sql);
                        
                        if (!$stmt) {
                            $errorInfo = $pdo->errorInfo();
                            throw new Exception("SQL Error: " . $errorInfo[2]);
                        }
                        
                        $topCows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if (count($topCows) > 0) {
                            foreach ($topCows as $cow) {
                                // Skip cows with no milk records
                                if ($cow['total_milk'] === null) continue;
                                
                                // Health status with fallback
                                $healthStatus = $cow['health_status'] ?? 'unknown';
                                $healthClass = 'badge bg-secondary'; // default
                                
                                if ($healthStatus == 'good') {
                                    $healthClass = 'badge bg-success';
                                } elseif ($healthStatus == 'critical') {
                                    $healthClass = 'badge bg-danger';
                                } elseif ($healthStatus == 'fair') {
                                    $healthClass = 'badge bg-warning';
                                }
                                
                                // Format last milked time
                                $lastMilkedText = 'Never';
                                if ($cow['last_milked']) {
                                    $lastMilked = new DateTime($cow['last_milked']);
                                    $now = new DateTime();
                                    $interval = $lastMilked->diff($now);
                                    
                                    if ($interval->d > 0) {
                                        $lastMilkedText = $interval->d . 'd ago';
                                    } elseif ($interval->h > 0) {
                                        $lastMilkedText = $interval->h . 'h ago';
                                    } else {
                                        $lastMilkedText = $interval->i . 'm ago';
                                    }
                                }
                                ?>
                                <div class="task-item">
                                    <div class="w3-row">
                                        <div class="w3-col s8">
                                            <strong><?= htmlspecialchars($cow['name'] ?? 'Unknown') ?></strong> 
                                            (<?= htmlspecialchars($cow['tag_number'] ?? 'N/A') ?>)
                                        </div>
                                        <div class="w3-col s4 w3-right-align">
                                            <span class="<?= $healthClass ?>">
                                                <?= ucfirst($healthStatus) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w3-small">
                                        <?= number_format($cow['total_milk'] ?? 0, 2) ?> L | 
                                        Last milked: <?= $lastMilkedText ?>
                                    </div>
                                </div>
                                <?php
                            }
                            
                            // Show message if all cows had no milk records
                            if (count(array_filter($topCows, fn($cow) => $cow['total_milk'] !== null)) === 0) {
                                echo '<div class="w3-panel w3-pale-yellow">No milk production records found for any animals.</div>';
                            }
                        } else {
                            echo '<div class="w3-panel w3-pale-yellow">No animals found in database.</div>';
                        }
                    } catch (Exception $e) {
                        error_log("Top Producing Animals Error: " . $e->getMessage());
                        echo '<div class="w3-panel w3-pale-red">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    }
                    ?>
                    
                    <div class="w3-center w3-padding">
                        <a href="views/cows/list.php" class="w3-button w3-light-grey">View All Animals</a>
                    </div>
                </div>
            </div>

            <!-- Milk Production Chart -->
            <div class="w3-col l4 m12 s12">
    <div class="content-card animate-fade-in" style="animation-delay: 0.3s">
        <div class="card-header">
            <h5 class="card-title"><i class="fas fa-chart-bar"></i> Milk Production Analytics</h5>
            <div class="chart-toggle">
                <button class="w3-button w3-small w3-round chart-type-btn active" data-type="bar">Bar</button>
                <button class="w3-button w3-small w3-round chart-type-btn" data-type="line">Line</button>
                <button class="w3-button w3-small w3-round chart-type-btn" data-type="pie">Pie</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="milkChart"></canvas>
        </div>
        <div class="w3-padding w3-center">
            <div class="w3-dropdown-hover">
                <button class="w3-button w3-light-grey">Time Period <i class="fas fa-caret-down"></i></button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="#" class="w3-bar-item w3-button period-btn" data-period="week">This Week</a>
                    <a href="#" class="w3-bar-item w3-button period-btn" data-period="month">This Month</a>
                    <a href="#" class="w3-bar-item w3-button period-btn" data-period="year">This Year</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chart-toggle {
        display: flex;
        gap: 5px;
    }
    
    .chart-type-btn {
        padding: 2px 8px;
        background-color: #f1f1f1;
        transition: all 0.3s;
    }
    
    .chart-type-btn.active {
        background-color: var(--primary-color);
        color: white;
    }
    
    .chart-type-btn:hover {
        background-color: #ddd;
    }
    
    .chart-type-btn.active:hover {
        background-color: var(--secondary-color);
    }
    
    .period-btn {
        text-align: left;
        padding: 8px 16px;
    }
    
    .period-btn:hover {
        background-color: #f1f1f1;
    }
</style>

<script>
    // Milk Production Chart with Multiple Views
    document.addEventListener('DOMContentLoaded', function() {
        // Sample data for different time periods
        const chartData = {
            week: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                values: [120, 135, 130, 125, 140, 110, 105],
                total: 865
            },
            month: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                values: [865, 920, 890, 950],
                total: 3625
            },
            year: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                values: [3500, 3200, 3800, 3700, 4000, 4200, 4100, 4300, 4400, 4500, 4600, 4700],
                total: 47000
            }
        };

        let currentChartType = 'bar';
        let currentPeriod = 'week';
        let milkChart = null;

        // Initialize chart
        function initChart() {
            const ctx = document.getElementById('milkChart').getContext('2d');
            const data = chartData[currentPeriod];
            
            if (milkChart) {
                milkChart.destroy();
            }

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Poppins',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleFont: {
                            family: 'Poppins',
                            size: 12
                        },
                        bodyFont: {
                            family: 'Poppins',
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== undefined) {
                                    label += context.parsed.y ? context.parsed.y + ' L' : context.raw + ' L';
                                }
                                return label;
                            }
                        }
                    }
                }
            };

            if (currentChartType === 'bar') {
                milkChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Milk Production',
                            data: data.values,
                            backgroundColor: 'rgba(67, 97, 238, 0.7)',
                            borderColor: 'rgba(67, 97, 238, 1)',
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Liters',
                                    font: {
                                        family: 'Poppins',
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                },
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: currentPeriod === 'week' ? 'Day' : 
                                          currentPeriod === 'month' ? 'Week' : 'Month',
                                    font: {
                                        family: 'Poppins',
                                        size: 12
                                    }
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            } 
            else if (currentChartType === 'line') {
                milkChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Milk Production',
                            data: data.values,
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            borderColor: 'rgba(67, 97, 238, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgba(67, 97, 238, 1)',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: {
                                beginAtZero: false,
                                title: {
                                    display: true,
                                    text: 'Liters',
                                    font: {
                                        family: 'Poppins',
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                },
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: currentPeriod === 'week' ? 'Day' : 
                                          currentPeriod === 'month' ? 'Week' : 'Month',
                                    font: {
                                        family: 'Poppins',
                                        size: 12
                                    }
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            } 
            else if (currentChartType === 'pie') {
                // Calculate percentages for pie chart
                const total = data.values.reduce((a, b) => a + b, 0);
                const backgroundColors = [
                    'rgba(67, 97, 238, 0.7)',
                    'rgba(103, 114, 229, 0.7)',
                    'rgba(139, 131, 221, 0.7)',
                    'rgba(175, 148, 212, 0.7)',
                    'rgba(211, 165, 204, 0.7)',
                    'rgba(247, 182, 195, 0.7)',
                    'rgba(255, 199, 187, 0.7)'
                ];

                milkChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Milk Production',
                            data: data.values,
                            backgroundColor: backgroundColors,
                            borderColor: 'rgba(255, 255, 255, 0.8)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        const percentage = Math.round((value / total) * 100);
                                        return `${context.label}: ${value} L (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Initialize with default chart
        initChart();

        // Chart type toggle buttons
        document.querySelectorAll('.chart-type-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentChartType = this.dataset.type;
                initChart();
            });
        });

        // Period selection buttons
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                currentPeriod = this.dataset.period;
                initChart();
            });
        });
    });
</script>

            <!-- Farm Analytics -->
            <div class="w3-col l4 m12 s12">
                <div class="content-card animate-fade-in" style="animation-delay: 0.4s">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-chart-pie"></i> Farm Analytics</h5>
                    </div>
                    <div class="w3-panel">
                        <p class="w3-small">Performance Metrics</p>
                        <div class="w3-light-grey w3-round">
                            <div class="w3-container w3-blue w3-round" style="height:24px;width:74%">74% Feed Efficiency</div>
                        </div>
                        <div class="w3-light-grey w3-round" style="margin-top:8px">
                            <div class="w3-container w3-blue w3-round" style="height:24px;width:88%">88% Reproduction Rate</div>
                        </div>
                        <div class="w3-light-grey w3-round" style="margin-top:8px">
                            <div class="w3-container w3-blue w3-round" style="height:24px;width:92%">92% Milk Quality</div>
                        </div>
                    </div>
                    <div class="w3-center">
                        <a href="reports/generate_report.php" class="w3-button w3-light-grey">View Detailed Reports</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Tasks -->
        <div class="content-card animate-fade-in" style="animation-delay: 0.5s">
    <div class="card-header">
        <h5 class="card-title"><i class="fas fa-tasks"></i> Today's Tasks</h5>
    </div>
    
    <?php
    // Include database configuration
    require_once 'config/database.php';
    
    try {
        // Get today's date
        $today = date('Y-m-d');
        
        // Query to get today's tasks
        $stmt = $pdo->prepare("
            SELECT t.*, u.username 
            FROM tasks t
            JOIN users u ON t.assigned_to = u.email
            WHERE t.deadline = :today OR t.created_at = :today
            ORDER BY 
                CASE t.priority
                    WHEN 'urgent' THEN 1
                    WHEN 'high' THEN 2
                    WHEN 'medium' THEN 3
                    ELSE 4
                END,
                t.deadline ASC
            LIMIT 5
        ");
        $stmt->execute([':today' => $today]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($tasks) > 0) {
            foreach ($tasks as $task) {
                // Determine badge class based on priority
                $badgeClass = 'bg-secondary';
                if ($task['priority'] === 'urgent') {
                    $badgeClass = 'bg-danger';
                } elseif ($task['priority'] === 'high') {
                    $badgeClass = 'bg-warning';
                } elseif ($task['priority'] === 'medium') {
                    $badgeClass = 'bg-primary';
                }
                
                // Format the deadline display
                $deadlineText = 'Today';
                if ($task['deadline'] === $today) {
                    $deadlineText = 'Today';
                } else {
                    $deadlineText = date('M j', strtotime($task['deadline']));
                }
                ?>
                <div class="task-item">
                    <div class="w3-row">
                        <div class="w3-col s8">
                            <strong><?= htmlspecialchars($task['message']) ?></strong>
                            <div class="w3-small">
                                Assigned to: <?= htmlspecialchars($task['username']) ?>
                            </div>
                        </div>
                        <div class="w3-col s4 w3-right-align">
                            <span class="badge <?= $badgeClass ?>">
                                <?= ucfirst($task['priority']) ?>
                            </span>
                            <span class="w3-small">Due: <?= $deadlineText ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="w3-panel w3-pale-yellow w3-padding">No tasks for today.</div>';
        }
    } catch (PDOException $e) {
        error_log("Database error fetching tasks: " . $e->getMessage());
        echo '<div class="w3-panel w3-pale-red">Error loading tasks. Please try again later.</div>';
    }
    ?>
    
    <div class="w3-center w3-padding">
        <a href="http://localhost/Dairy/Task/" class="w3-button w3-light-grey">Assign Tasks</a>
    </div>
</div>

    <!-- JavaScript Section -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        window.onclick = function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (window.innerWidth <= 992 && 
                !event.target.matches('.menu-toggle') && 
                !event.target.matches('.menu-toggle *') &&
                !sidebar.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }

        // Milk Production Chart
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const ctx = document.getElementById('milkChart').getContext('2d');
                const milkChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Milk Production (Liters)',
                            data: [120, 135, 130, 125, 140, 110, 105],
                            backgroundColor: 'rgba(67, 97, 238, 0.7)',
                            borderColor: 'rgba(67, 97, 238, 1)',
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: {
                                        family: 'Poppins',
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleFont: {
                                    family: 'Poppins',
                                    size: 12
                                },
                                bodyFont: {
                                    family: 'Poppins',
                                    size: 12
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw + ' L';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Liters',
                                    font: {
                                        family: 'Poppins',
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.05)'
                                },
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Day of Week',
                                    font: {
                                        family: 'Poppins',
                                        size: 12
                                    }
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        family: 'Poppins',
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error("Error creating milk chart:", error);
            }
        });
    </script>
</body>
</html>
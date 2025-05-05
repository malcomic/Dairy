<?php
require_once '../../functions.php';
requireLogin();
require_once '../../config/database.php';
//require_once '../../models/Cow.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list.php');
    exit();
}

$cow = new Cow($pdo);
$cowData = $cow->getCowById($_GET['id']);

if (!$cowData) {
    header('Location: list.php');
    exit();
}

// Calculate age from date of birth if available
$age = $cowData['age'];
if (!empty($cowData['date_of_birth'])) {
    $dob = new DateTime($cowData['date_of_birth']);
    $now = new DateTime();
    $age = $dob->diff($now)->y;
}

?>
<?php
class Cow {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add this method to your Cow class
    public function getCowById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cows WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Keep your existing methods here...
    public function getCowsFiltered($search = null, $breed = null, $ageMin = null, $ageMax = null) {
        // Your existing implementation...
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cow | <?php echo htmlspecialchars($cowData['name'] ?: $cowData['cow_id']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166d67;
            --accent-color: #ff914d;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --healthy-color: #28a745;
            --sick-color: #dc3545;
            --injured-color: #fd7e14;
            --unknown-color: #6c757d;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .cow-profile-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 2rem 0;
            position: relative;
        }
        
        .cow-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            position: relative;
        }
        
        .cow-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="rgba(255,255,255,0.1)"><path d="M30,20 Q50,0 70,20 T90,40 Q100,60 80,70 T60,90 Q40,100 30,80 T10,60 Q0,40 20,30 T30,20"></path></svg>');
            background-size: contain;
            opacity: 0.2;
        }
        
        .cow-avatar {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .cow-avatar i {
            font-size: 3.5rem;
            color: var(--primary-color);
        }
        
        .cow-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2.2rem;
        }
        
        .cow-id {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .cow-details {
            padding: 2rem;
        }
        
        .detail-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--accent-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .detail-card h3 {
            color: var(--primary-color);
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px dashed #f0f0f0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--dark-color);
            width: 40%;
            min-width: 120px;
        }
        
        .detail-value {
            color: #555;
            width: 60%;
        }
        
        .badge-age {
            background-color: #e3f2fd;
            color: var(--primary-color);
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .health-status {
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
        }
        
        .health-status.healthy {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--healthy-color);
        }
        
        .health-status.sick {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--sick-color);
        }
        
        .health-status.injured {
            background-color: rgba(253, 126, 20, 0.15);
            color: var(--injured-color);
        }
        
        .health-status.unknown {
            background-color: rgba(108, 117, 125, 0.15);
            color: var(--unknown-color);
        }
        
        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #eee;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent-color);
            border: 2px solid white;
            z-index: 1;
        }
        
        .timeline-date {
            font-size: 0.85rem;
            color: #777;
            margin-bottom: 0.3rem;
        }
        
        .timeline-content {
            background: #f9f9f9;
            padding: 0.8rem;
            border-radius: 6px;
            border-left: 3px solid var(--accent-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #777;
        }
        
        .empty-state i {
            font-size: 2.5rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        
        .empty-state h5 {
            color: #555;
            margin-bottom: 0.5rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }
        
        .btn-edit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-edit:hover {
            background-color: #3a5a8a;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-delete:hover {
            background-color: #bb2d3b;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-back {
            background-color: white;
            color: var(--dark-color);
            border: 1px solid #ddd;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background-color: #f8f9fa;
            color: var(--dark-color);
            transform: translateY(-2px);
            border-color: #ccc;
        }
        
        @media (max-width: 767.98px) {
            .cow-header {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }
            
            .cow-avatar {
                margin-right: 0;
                margin-bottom: 1rem;
                width: 80px;
                height: 80px;
            }
            
            .cow-avatar i {
                font-size: 2.5rem;
            }
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label, .detail-value {
                width: 100%;
            }
            
            .detail-label {
                margin-bottom: 0.3rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .btn-back {
                order: -1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cow-profile-container">
            <div class="cow-header d-flex align-items-center">
                <div class="cow-avatar">
                    <i class="fas fa-cow"></i>
                </div>
                <div>
                    <h1>
                        <?php echo htmlspecialchars($cowData['name'] ?: 'Unnamed Cow'); ?>
                    </h1>
                    <div class="cow-id">
                        <i class="fas fa-hashtag"></i> <?php echo htmlspecialchars($cowData['cow_id']); ?>
                        <span class="badge-age ms-2"><?php echo htmlspecialchars($age); ?> years old</span>
                    </div>
                </div>
            </div>
            
            <div class="cow-details">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-card">
                            <h3><i class="fas fa-info-circle me-2"></i> Basic Information</h3>
                            
                            <div class="detail-row">
                                <div class="detail-label">Cow ID</div>
                                <div class="detail-value"><?php echo htmlspecialchars($cowData['cow_id']); ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Name</div>
                                <div class="detail-value"><?php echo htmlspecialchars($cowData['name'] ?: 'Not specified'); ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Breed</div>
                                <div class="detail-value"><?php echo htmlspecialchars($cowData['breed']); ?></div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Date of Birth</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['date_of_birth']) ? htmlspecialchars($cowData['date_of_birth']) : 'Unknown'; ?>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">
                                    <span class="badge-age">
                                        <?php echo htmlspecialchars($age); ?> years
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <h3><i class="fas fa-heartbeat me-2"></i> Health Information</h3>
                            
                            <div class="detail-row">
                                <div class="detail-label">Health Status</div>
                                <div class="detail-value">
                                    <span class="health-status <?php echo strtolower(htmlspecialchars($cowData['health_status'])); ?>">
                                        <i class="fas <?php 
                                            echo $cowData['health_status'] === 'Healthy' ? 'fa-heartbeat' : 
                                                 ($cowData['health_status'] === 'Sick' ? 'fa-hospital' : 
                                                 ($cowData['health_status'] === 'Injured' ? 'fa-bandaid' : 'fa-question-circle')); 
                                        ?> me-1"></i>
                                        <?php echo htmlspecialchars($cowData['health_status']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Last Checkup</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['last_checkup']) ? htmlspecialchars($cowData['last_checkup']) : 'No records'; ?>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Vaccinations</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['vaccinations']) ? htmlspecialchars($cowData['vaccinations']) : 'No records'; ?>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Medical Notes</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['medical_notes']) ? htmlspecialchars($cowData['medical_notes']) : 'No notes available'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-card">
                            <h3><i class="fas fa-clipboard-list me-2"></i> Additional Details</h3>
                            
                            <div class="detail-row">
                                <div class="detail-label">Weight</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['weight']) ? htmlspecialchars($cowData['weight']) . ' kg' : 'Not recorded'; ?>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Color/Markings</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['color']) ? htmlspecialchars($cowData['color']) : 'Not specified'; ?>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Mother ID</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['mother_id']) ? htmlspecialchars($cowData['mother_id']) : 'Unknown'; ?>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Father ID</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['father_id']) ? htmlspecialchars($cowData['father_id']) : 'Unknown'; ?>
                                </div>
                            </div>
                            
                            <div class="detail-row">
                                <div class="detail-label">Date Added</div>
                                <div class="detail-value">
                                    <?php echo !empty($cowData['created_at']) ? htmlspecialchars($cowData['created_at']) : 'Unknown'; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-card">
                            <h3><i class="fas fa-history me-2"></i> Recent Activity</h3>
                            
                            <?php if (!empty($cowData['activity_log'])): ?>
                                <div class="timeline">
                                    <?php 
                                    // Sample activity data - replace with actual database query if needed
                                    $activities = [
                                        ['date' => date('Y-m-d', strtotime('-1 week')), 'action' => 'Health checkup', 'details' => 'Routine examination - all normal'],
                                        ['date' => date('Y-m-d', strtotime('-1 month')), 'action' => 'Vaccination', 'details' => 'Administered annual booster shot'],
                                        ['date' => date('Y-m-d', strtotime('-2 months')), 'action' => 'Weight measurement', 'details' => 'Recorded weight: '.($cowData['weight'] ?? '450').'kg'],
                                    ];
                                    
                                    foreach ($activities as $activity): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-date">
                                                <i class="far fa-calendar-alt me-1"></i> <?php echo $activity['date']; ?>
                                            </div>
                                            <div class="timeline-content">
                                                <strong><?php echo $activity['action']; ?></strong>
                                                <p class="mb-0"><?php echo $activity['details']; ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-clock"></i>
                                    <h5>No Recent Activity</h5>
                                    <p>No activity records found for this cow.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="edit.php?id=<?php echo $cowData['id']; ?>" class="btn btn-edit">
                        <i class="fas fa-edit me-1"></i> Edit Cow
                    </a>
                    <a href="delete.php?id=<?php echo $cowData['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this cow? This action cannot be undone.')">
                        <i class="fas fa-trash-alt me-1"></i> Delete Cow
                    </a>
                    <a href="list.php" class="btn btn-back">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhanced animation for the cow avatar
        document.addEventListener('DOMContentLoaded', function() {
            const cowAvatar = document.querySelector('.cow-avatar');
            
            cowAvatar.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
                this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
                this.style.transition = 'all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1)';
            });
            
            cowAvatar.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0)';
                this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
            });
            
            // Add animation to detail cards when they come into view
            const detailCards = document.querySelectorAll('.detail-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            detailCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>
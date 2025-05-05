<?php
// Include database configuration and User model
require_once '../config/database.php';
require_once '../models/User.php';

// Fetch users for the dropdown
try {
    $users = User::getUsersByRole($pdo, ['farmer', 'veterinary']);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task - Dairy Farm Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --gradient-start: #6a11cb;
            --gradient-end: #2575fc;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .task-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }
        
        .task-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
        }
        
        .task-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .task-icon {
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 15px;
        }
        
        .task-title {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .task-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 10px;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
            box-shadow: none;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .btn-assign {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border: none;
            color: white;
            padding: 12px 25px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(106, 17, 203, 0.3);
        }
        
        .btn-assign:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(106, 17, 203, 0.4);
            color: white;
        }
        
        .user-option {
            display: flex;
            align-items: center;
            padding: 8px 12px;
        }
        
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 500;
        }
        
        .user-email {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .task-container {
                padding: 30px 20px;
            }
            
            .task-icon {
                font-size: 2.5rem;
            }
            
            .task-title {
                font-size: 1.5rem;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>
<body>
    <div class="task-container animate-fade-in">
        <div class="task-header">
            <div class="task-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <h1 class="task-title">Assign New Task</h1>
            <p class="task-subtitle">Delegate responsibilities to your team members</p>
        </div>
        
        <form action="send_mail.php" method="POST">
            <div class="mb-4">
                <label for="email" class="form-label">
                    <i class="fas fa-user"></i> Select Team Member
                </label>
                <select name="email" id="email" class="form-select">
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $user) : ?>
                            <?php if (isset($user['email']) && isset($user['username'])) : ?>
                                <option value="<?= htmlspecialchars($user['email']) ?>">
                                    <div class="user-option">
                                        <div class="user-avatar">
                                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                        </div>
                                        <div class="user-details">
                                            <span class="user-name"><?= htmlspecialchars($user['username']) ?></span>
                                            <span class="user-email"><?= htmlspecialchars($user['email']) ?></span>
                                        </div>
                                    </div>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <option value="">No team members available</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="message" class="form-label">
                    <i class="fas fa-align-left"></i> Task Details
                </label>
                <textarea name="message" id="message" class="form-control" 
                          placeholder="Describe the task in detail, including any deadlines or special instructions..."></textarea>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">
                    <i class="fas fa-exclamation-circle"></i> Priority Level
                </label>
                <select name="priority" id="priority" class="form-select">
                    <option value="low">Low Priority</option>
                    <option value="medium" selected>Medium Priority</option>
                    <option value="high">High Priority</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="deadline" class="form-label">
                    <i class="fas fa-calendar-day"></i> Deadline (optional)
                </label>
                <input type="date" name="deadline" id="deadline" class="form-control">
            </div>

            <button type="submit" name="submit" class="btn btn-assign">
                <i class="fas fa-paper-plane"></i> Assign Task
            </button>
            <a href="http://localhost/Dairy/index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enhance select elements with user avatars (would need a select2 or custom select implementation)
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today for deadline
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('deadline').min = today;
            
            // Add animation to form elements
            const formElements = document.querySelectorAll('.form-control, .form-select, .btn-assign');
            formElements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.animation = `fadeIn 0.5s ease-out forwards ${index * 0.1 + 0.3}s`;
            });
        });
    </script>
</body>
</html>



<button class="back-button">
  ← Back
</button>
<style>
    .back-button {
  padding: 10px 15px 10px 10px;
  background-color: transparent;
  border: none;
  color: #4285f4;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
}

.back-button:hover {
background-color: #3367d6;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
</style>
<script>
    document.querySelector('.back-button').addEventListener('click', () => {
  window.history.back();
});
</script>
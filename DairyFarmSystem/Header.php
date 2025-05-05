<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy Farm Management System | Smart Dairy</title>
    <link rel="stylesheet" href="./CSS/landing.css">
    <link rel="stylesheet" href="./CSS/about.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="png" href="./../DairyFarmSystem/images/logo.png">
    <style>
        :root {
            --primary-color:rgb(48, 117, 84);
            --secondary-color: #166d67;
            --accent-color: #ff914d;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background: rgba(33, 97, 48, 0.95) !important;
            backdrop-filter: blur(10px);
            padding: 0.3rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .navbar-brand img {
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover img {
            transform: rotate(10deg);
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            margin: 0 8px;
            padding: 8px 15px !important;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:not(.btn):hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link:not(.btn)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: all 0.3s ease;
        }
        
        .nav-link:not(.btn):hover::after {
            width: 70%;
            left: 15%;
        }
        
        .nav-item .btn {
            margin-left: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border-width: 2px;
        }
        
        .btn-outline-primary {
            color: white;
            border-color: white;
        }
        
        .btn-outline-primary:hover {
            background: white;
            color: var(--primary-color);
        }
        
        .btn-outline-success {
            color: white;
            border-color: white;
        }
        
        .btn-outline-success:hover {
            background: white;
            color: var(--secondary-color);
        }
        
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(255, 255, 255, 0.8)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(74, 111, 165, 0.98);
                padding: 20px;
                border-radius: 10px;
                margin-top: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            
            .nav-item {
                margin: 5px 0;
            }
            
            .nav-item .btn {
                margin-left: 0;
                margin-top: 10px;
                display: block;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="landing.php">
                <img src="./images/logo.png" alt="Smart Dairy Logo" width="40" height="40" class="d-inline-block align-top me-2">
                Smart Dairy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="landing.php"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about-us.php"><i class="fas fa-info-circle me-1"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php"><i class="fas fa-envelope me-1"></i> Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary me-lg-2" href="../Views/users/login.php?action=login"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-success" href="../Views/users/login.php?action=register"><i class="fas fa-user-plus me-1"></i> Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.classList.remove('scrolled');
                navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            }
        });
    </script>
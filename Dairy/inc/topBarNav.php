<nav class="main-header navbar navbar-expand-md navbar-dark bg-gradient-primary shadow-lg">
    <div class="container">
        <!-- Brand Logo with Animation -->
        <a href="../../Dairy/index.php" class="navbar-brand d-flex align-items-center">
            <div class="brand-logo-container pulse-animation">
                <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="width: 2.5rem; height: 2.5rem;">
            </div>
            <span class="brand-text font-weight-bold ml-2"><?php echo (!isMobileDevice()) ? $_settings->info('name'):$_settings->info('short_name'); ?></span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Main Navigation -->
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="../../Dairy/index.php" class="nav-link active-link">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                </li>
                <!-- Add more menu items here -->
            </ul>
        </div>

        <!-- Right Navigation -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <!-- Search Bar with Modern Toggle -->
            <li class="nav-item search-item">
                <a class="nav-link search-toggle" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block animated-search">
                    <form class="form-inline">
                        <div class="input-group input-group-lg">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search..." aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar close-search" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <!-- Fullscreen Toggle -->
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>

            <!-- Logout Button -->
            <li class="nav-item">
                <a class="nav-link logout-btn" href="javascript:void(0)" onclick="location.replace('<?php echo base_url.'/classes/Login.php?f=elogout' ?>')" role="button" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </li>

            <!-- Control Sidebar Toggle -->
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" title="Settings">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<style>
    /* Navbar Styling */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .navbar {
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .navbar-brand {
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .brand-logo-container {
        padding: 0.25rem;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .brand-image {
        border: 2px solid rgba(255,255,255,0.3);
    }
    
    .nav-link {
        padding: 0.75rem 1rem;
        margin: 0 0.25rem;
        border-radius: 50px;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .nav-link:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-2px);
    }
    
    .active-link {
        background: rgba(255,255,255,0.1);
        font-weight: 600;
    }
    
    /* Search Bar Animation */
    .navbar-search-block {
        position: absolute;
        right: 0;
        top: 100%;
        width: 100%;
        max-width: 400px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
    }
    
    .animated-search.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .form-control-navbar {
        border: none;
        background: rgba(255,255,255,0.9);
    }
    
    /* Button Effects */
    .logout-btn:hover {
        color: #ff6b6b !important;
    }
    
    /* Animations */
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .navbar-brand {
            font-size: 1.2rem;
        }
        
        .nav-link {
            padding: 0.5rem 0.75rem;
            margin: 0.25rem 0;
        }
        
        .navbar-search-block {
            position: static;
            width: 100%;
            margin-top: 0.5rem;
        }
    }
</style>

<script>
    // Enhanced search toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchToggles = document.querySelectorAll('[data-widget="navbar-search"]');
        
        searchToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const searchBlock = this.closest('.search-item').querySelector('.navbar-search-block');
                searchBlock.classList.toggle('show');
                
                if (searchBlock.classList.contains('show')) {
                    searchBlock.querySelector('input').focus();
                }
            });
        });
        
        // Close search when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-item')) {
                document.querySelectorAll('.navbar-search-block').forEach(block => {
                    block.classList.remove('show');
                });
            }
        });
    });
</script>
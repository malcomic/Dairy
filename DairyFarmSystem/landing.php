<!-- landing.php -->
<?php include('header.php'); ?>

<header class="hero-section" style="
    background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    position: relative;
    overflow: hidden;
">
    <div class="container text-center text-white" data-aos="fade-up" data-aos-duration="1000">
        <h1 class="display-3 fw-bold mb-4" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.8);">Welcome to Dairy Farm Management System</h1>
        <p class="lead fs-3 mb-5" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.8);">Efficiently manage your farm operations with our comprehensive solution</p>
        <a href="../Views/users/login.php" class="btn btn-success btn-lg px-5 py-3 fs-4 shadow-lg" style="
            border-radius: 50px;
            background-color: #2d6a4f;
            border: 2px solid #fff;
            transition: all 0.3s ease;
            font-weight: 600;
        ">Get Started <i class="fas fa-arrow-right ms-2"></i></a>
    </div>
    
    <div class="scroll-down">
        <a href="#features" class="text-white">
            <i class="fas fa-chevron-down fa-2x bounce"></i>
        </a>
    </div>
</header>

<section id="features" class="features-section py-5 bg-light">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-3">Our Powerful Features</h2>
        <p class="lead mb-5">Everything you need to optimize your dairy farm operations</p>
        
        <div class="row g-4 mt-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card p-5 h-100 shadow-sm bg-white rounded-3">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-cow fa-3x text-success"></i>
                    </div>
                    <h3 class="h4 mb-3">Animal Records</h3>
                    <p class="text-muted">Comprehensive tracking of livestock health, breeding, and history in one centralized system.</p>
                </div>
            </div>
            
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card p-5 h-100 shadow-sm bg-white rounded-3">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-wine-bottle fa-3x text-success"></i>
                    </div>
                    <h3 class="h4 mb-3">Milk Production</h3>
                    <p class="text-muted">Real-time monitoring of milk yields with analytics to identify trends and optimize production.</p>
                </div>
            </div>
            
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card p-5 h-100 shadow-sm bg-white rounded-3">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-chart-line fa-3x text-success"></i>
                    </div>
                    <h3 class="h4 mb-3">Farm Finances</h3>
                    <p class="text-muted">Detailed financial tracking with reports to help you maximize profitability and control costs.</p>
                </div>
            </div>
        </div>
        
        <div class="row g-4 mt-2">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card p-5 h-100 shadow-sm bg-white rounded-3">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-calendar-alt fa-3x text-success"></i>
                    </div>
                    <h3 class="h4 mb-3">Scheduling</h3>
                    <p class="text-muted">Automated reminders for vaccinations, breeding cycles, and other critical farm events.</p>
                </div>
            </div>
            
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card p-5 h-100 shadow-sm bg-white rounded-3">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-warehouse fa-3x text-success"></i>
                    </div>
                    <h3 class="h4 mb-3">Inventory</h3>
                    <p class="text-muted">Track feed, medications, and supplies with automated low-stock alerts.</p>
                </div>
            </div>
            
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-card p-5 h-100 shadow-sm bg-white rounded-3">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-mobile-alt fa-3x text-success"></i>
                    </div>
                    <h3 class="h4 mb-3">Mobile Access</h3>
                    <p class="text-muted">Manage your farm from anywhere with our responsive mobile-friendly interface.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="testimonial-section py-5 bg-success text-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-4">Trusted by Dairy Farmers Worldwide</h2>
                <p class="lead">Join thousands of farmers who have transformed their operations with our system.</p>
                <a href="#" class="btn btn-outline-light btn-lg mt-3">Learn More</a>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="card bg-white text-dark p-4 shadow-lg rounded-3">
                    <div class="card-body">
                        <div class="d-flex mb-4">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle me-3" width="60" height="60" alt="Farmer">
                            <div>
                                <h5 class="mb-1">John Peterson</h5>
                                <p class="text-muted mb-0">Dairy Farmer, Wisconsin</p>
                            </div>
                        </div>
                        <p class="lead">"This system has revolutionized how we manage our 200-head dairy operation. The milk production tracking alone has helped us increase yields by 15%."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>

<!-- Add these in your header or before </body> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init();
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>

<style>
    .hero-section {
        position: relative;
    }
    
    .scroll-down {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        animation: pulse 2s infinite;
    }
    
    .bounce {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-20px);}
        60% {transform: translateY(-10px);}
    }
    
    @keyframes pulse {
        0% {opacity: 1;}
        50% {opacity: 0.5;}
        100% {opacity: 1;}
    }
    
    .feature-card {
        transition: all 0.3s ease;
        border-top: 4px solid transparent;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        border-top: 4px solid #2d6a4f;
    }
    
    .feature-icon {
        color: #2d6a4f;
    }
</style>
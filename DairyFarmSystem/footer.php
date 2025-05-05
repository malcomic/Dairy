<!-- Footer -->
<footer class="footer mt-auto py-5 bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5 class="text-uppercase mb-4" style="color: var(--accent-color);">
                    <img src="./images/logo.png" alt="Smart Dairy Logo" width="40" height="40" class="me-2">
                    Smart Dairy
                </h5>
                <p class="text-muted">Revolutionizing dairy farm management with innovative technology solutions for modern farmers.</p>
                <div class="mt-4">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="landing.php" class="text-muted text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="about-us.php" class="text-muted text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Services</a></li>
                    <li class="mb-2"><a href="contact.php" class="text-muted text-decoration-none">Contact</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-4">Contact Info</h5>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 089 Farm Road, Kericho</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> +254 725-606605</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@smartdairy.com</li>
                    <li class="mb-2"><i class="fas fa-clock me-2"></i> Mon-Fri: 9AM - 5PM</li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase mb-4">Newsletter</h5>
                <p class="text-muted">Subscribe to our newsletter for the latest updates.</p>
                <form class="mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your Email" aria-label="Your Email">
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </div>
                </form>
                <div class="d-flex">
                    <img src="https://via.placeholder.com/120x40?text=App+Store" alt="App Store" class="img-fluid me-2" style="max-height: 40px;">
                    <img src="https://via.placeholder.com/120x40?text=Google+Play" alt="Google Play" class="img-fluid" style="max-height: 40px;">
                </div>
            </div>
        </div>
        
        <hr class="my-4 bg-secondary">
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-muted">© 2023 <span class="text-white">Smart Dairy</span>. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-muted text-decoration-none me-3">Terms of Service</a>
                <a href="#" class="text-muted text-decoration-none">Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<style>
    footer {
        background: linear-gradient(135deg, var(--dark-color), var(--secondary-color)) !important;
    }
    
    footer a {
        transition: all 0.3s ease;
    }
    
    footer a:hover {
        color: var(--accent-color) !important;
        text-decoration: underline !important;
    }
    
    .social-icons a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        margin-right: 8px;
        transition: all 0.3s ease;
    }
    
    .social-icons a:hover {
        background: var(--accent-color);
        transform: translateY(-3px);
    }
    
    .input-group button {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }
    
    .input-group button:hover {
        background-color: #e07e3d;
        border-color: #e07e3d;
    }
    
    @media (max-width: 767.98px) {
        .text-md-start, .text-md-end {
            text-align: center !important;
        }
        
        .col-md-6 {
            margin-bottom: 1rem;
        }
    }
</style>
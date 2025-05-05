<!-- landing.php -->
<?php
    include('header.php'); // Include header.php            // style="background-image: url('./images/dairyland.png'); no-repeat center center/cover"
?>

<header class="hero-section" style="
    background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
">
    <div class="container text-center text-white">
        <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Welcome to Dairy Farm Management System</h1>
        <p style="font-size: 1.5rem; margin-bottom: 2rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">Efficiently manage your farm operations with ease.</p>
        <a href="../Views/users/login.php" class="btn btn-primary btn-lg" style="
            padding: 12px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
            background-color: #2d6a4f;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        ">Get Started</a>
    </div>
</header>

<section id="features" class="features-section py-5">
    <div class="container text-center">
        <h2>Features</h2>
        <p class="lead">Explore the powerful tools we offer to streamline your farm management.</p>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="feature-card p-4">
                    <h5>Animal Records</h5>
                    <p>Keep detailed records of your livestock.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4">
                    <h5>Milk Production</h5>
                    <p>Track milk production trends effortlessly.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4">
                    <h5>Farm Finances</h5>
                    <p>Monitor expenses and revenue for your farm.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    include('footer.php'); // Include footer.php
?>

<!-- contact.php -->
<?php include('header.php'); ?>

<section class="contact-hero py-5" style="
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1526779259212-939e64788e3c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
    background-size: cover;
    background-position: center;
    color: white;
">
    <div class="container py-5 text-center">
        <h1 class="display-4 fw-bold mb-3">Get In Touch</h1>
        <p class="lead">We'd love to hear from you! Reach out with any questions or inquiries.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
            <h2 class="display-5 fw-bold mb-4 text-success">Contact Us</h2>
            
            <?php
            if (isset($_POST['submit'])) {
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'dairy_farm');

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Collect and sanitize input
                $name = $conn->real_escape_string($_POST['name']);
                $email = $conn->real_escape_string($_POST['email']);
                $subject = $conn->real_escape_string($_POST['subject']);
                $message = $conn->real_escape_string($_POST['message']);

                // Insert into database
                $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
                if ($conn->query($sql) === TRUE) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Your message has been submitted successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> ' . $conn->error . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }

                // Close connection
                $conn->close();
            }
            ?>
            
            <form method="POST" action="contact.php" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control p-3" id="name" required>
                    <div class="invalid-feedback">
                        Please provide your name.
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control p-3" id="email" required>
                    <div class="invalid-feedback">
                        Please provide a valid email.
                    </div>
                </div>
                <div class="mb-4">
                    <label for="subject" class="form-label fw-bold">Subject</label>
                    <input type="text" name="subject" class="form-control p-3" id="subject" required>
                    <div class="invalid-feedback">
                        Please provide a subject.
                    </div>
                </div>
                <div class="mb-4">
                    <label for="message" class="form-label fw-bold">Your Message</label>
                    <textarea name="message" class="form-control p-3" id="message" rows="5" required></textarea>
                    <div class="invalid-feedback">
                        Please write your message.
                    </div>
                </div>
                <button type="submit" name="submit" class="btn btn-success btn-lg w-100 py-3 fw-bold">
                    <i class="fas fa-paper-plane me-2"></i> Send Message
                </button>
            </form>
        </div>
        
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-0">
                    <div class="map-container" style="height: 100%; min-height: 400px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.808477395287!2d36.82175631475397!3d-1.286385835980925!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f10d664fec5cf%3A0x7c3c1a88778a6b5d!2sNairobi%20City%20Hall!5e0!3m2!1sen!2ske!4v1623943476681!5m2!1sen!2ske" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                        </iframe>
                    </div>
                </div>
                <div class="card-footer bg-success text-white p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h5 class="fw-bold"><i class="fas fa-map-marker-alt me-2"></i> Our Location</h5>
                            <p>City Hall Way, Nairobi, Kenya</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold"><i class="fas fa-clock me-2"></i> Working Hours</h5>
                            <p>Mon-Fri: 8:00 AM - 5:00 PM<br>Sat: 9:00 AM - 1:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Info Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4 text-center" data-aos="fade-up">
                <div class="p-4 rounded-3 h-100 bg-white shadow-sm">
                    <div class="icon-container bg-success text-white rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; line-height: 70px;">
                        <i class="fas fa-phone-alt fa-2x"></i>
                    </div>
                    <h4 class="h5 fw-bold">Call Us</h4>
                    <p class="mb-0">+254 700 123456</p>
                    <p class="mb-0">+254 733 987654</p>
                </div>
            </div>
            <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 rounded-3 h-100 bg-white shadow-sm">
                    <div class="icon-container bg-success text-white rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; line-height: 70px;">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                    <h4 class="h5 fw-bold">Email Us</h4>
                    <p class="mb-0">info@dairyfarm.co.ke</p>
                    <p class="mb-0">support@dairyfarm.co.ke</p>
                </div>
            </div>
            <div class="col-md-4 text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 rounded-3 h-100 bg-white shadow-sm">
                    <div class="icon-container bg-success text-white rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; line-height: 70px;">
                        <i class="fas fa-globe-africa fa-2x"></i>
                    </div>
                    <h4 class="h5 fw-bold">Visit Us</h4>
                    <p class="mb-0">P.O. Box 12345-00100</p>
                    <p class="mb-0">Nairobi, Kenya</p>
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
    
    // Form validation
    (function () {
        'use strict'
        
        var forms = document.querySelectorAll('.needs-validation')
        
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

<style>
    .contact-hero {
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .map-container {
        border-radius: 0.3rem 0.3rem 0 0;
        overflow: hidden;
    }
    
    .icon-container {
        transition: all 0.3s ease;
    }
    
    .icon-container:hover {
        transform: rotate(15deg) scale(1.1);
    }
    
    .form-control {
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #2d6a4f;
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.25);
    }
    
    textarea.form-control {
        min-height: 150px;
    }
</style>
<!-- contact.php -->
<?php
    include('header.php'); // Include the header
?>
 <div class="container py-5">
        <h2 class="text-center mb-4">Contact Us</h2>
        <?php
        if (isset($_POST['submit'])) {
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'dairy');

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
                echo '<div class="alert alert-success">Your message has been submitted successfully.</div>';
            } else {
                echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
            }

            // Close connection
            $conn->close();
        }
        ?>
        <form method="POST" action="contact.php">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" name="subject" class="form-control" id="subject" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" class="form-control" id="message" rows="5" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php
    include('footer.php'); // Include the footer
?>
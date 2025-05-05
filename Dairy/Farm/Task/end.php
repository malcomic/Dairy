<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'path/to/vendor/autoload.php';

if(isset($_POST['register_volunteer'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $interests = implode(",", $_POST['interests']);
    $availability = $_POST['availability'];

    // Prepare the SQL statement
    $sql = "INSERT INTO volunteers (fullname, email, phone, location, interests, availability)
            VALUES ('$fullname', '$email', '$phone', '$location', '$interests', '$availability')";

    // Execute the SQL statement
    if ($con->query($sql) === TRUE) {
        // Send email notification
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'obeyiasillas578@gmail.com'; // SMTP username
            $mail->Password = 'tght nafa xoqr cvjd'; // SMTP password
            $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_SMTPS'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            //Recipients
            $mail->setFrom('your_email@example.com', 'secure streets');
            $mail->addAddress($email, $fullname); // Add a recipient

            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Volunteer Registration Successful';
            $mail->Body = 'Thank you for registering as a volunteer. Your registration details are:<br><br>
                           Full Name: ' . $fullname . '<br>
                           Email: ' . $email . '<br>
                           Phone: ' . $phone . '<br>
                           Location: ' . $location . '<br>
                           Interests: ' . $interests . '<br>
                           Availability: ' . $availability;

            $mail->send();
            echo "Volunteer registration successful and a confirmation email has been sent to $email";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
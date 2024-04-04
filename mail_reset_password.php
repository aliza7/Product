<?php
include('mail_detail.php');
include_once "db_connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php'; // Include PHPMailer autoload file



// Check if email is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    // Retrieve email from the form
    $email = $_POST['email'];


    $reset_code = bin2hex(random_bytes(16));

    
    // Update the reset_code in the database
    $updateSql = "UPDATE user_details SET reset_code = '$reset_code' WHERE email = '$email'";

    if ($conn->query($updateSql) === TRUE) {
        // Send email with the new password
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/Exception.php';
        require 'PHPMailer/SMTP.php';
        global $mail_id;
        global $mail_pass;
        global $mail_refname;

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $mail_id;                     //SMTP username
            $mail->Password   = $mail_pass;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Recipients
            $mail->setFrom($mail_id, $mail_refname);
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Password Reset';
            $mail->Body =
            "Password has been reset Click the link below to set new password 
        <a href='http://localhost:81/project2/reset_password.php?email=$email&reset_code=$reset_code'>Reset</a>";

            $mail->send();
            echo 'Password reset email has been sent.';
        } catch (Exception $e) {
            echo 'Error sending email: ' . $mail->ErrorInfo;
        }
    } else {
        // Failed to update password
        echo "Error: Unable to update password.";
    }
} else {
    // Handle case where email is not submitted
    echo "Email not provided.";
}

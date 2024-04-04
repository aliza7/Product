<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    include_once "db_connection.php";
    include('mail_detail.php');

    


    // Define variables and initialize with empty values
    $name = $email = $contact = $country = $sport = $password = $confirm_password = "";

    // Assign form data to variables
    $name = $_POST["name"];
    $email = $_POST["email"];
    $contact = $_POST["contact"];
    $country = $_POST["country"];
    $sport = $_POST["sport"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate password and confirm password
    if ($password != $confirm_password) {
        echo "Error: Passwords do not match.";
        exit();
    }
    $v_code = bin2hex(random_bytes(16));
    $is_varified = 0;
    $query2 = "SELECT * FROM `user_details` WHERE `email`='$email'";
    $result2 = mysqli_query($conn, $query2);
    if (mysqli_num_rows($result2) > 0) {
        echo "<script>alert('Email is already registered before.');
                        window.location.href='index.php';
                        </script>";
    } else {

        // Prepare and bind SQL statement
        $sql = "INSERT INTO user_details (name, email, contact, country, sport, password, verification_code, verified_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $name, $email, $contact, $country, $sport, $password, $v_code, $is_varified);

       // Execute the SQL statement
if ($stmt->execute()) {
    // Registration successful, now send email
    if (sendmail($email, $v_code)) {
        echo "<script>alert('User registered successfully. Please check your email for verification.');
                    window.location.href='index.php';
                    </script>";
    } else {
        echo "Error: Unable to send verification email.";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


        // Close statement and database connection
        $stmt->close();
        $conn->close();
    }
} else {
    // Redirect back to signup page if accessed directly
    header("Location: index.php");
    exit();
}

function sendmail($email, $v_code)
{
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/Exception.php';
    require 'PHPMailer/SMTP.php';
    global $mail_id;
    global $mail_pass;
    global $mail_refname;

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $mail_id;                     //SMTP username
        $mail->Password   = $mail_pass;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($mail_id, $mail_refname);
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Verification from Product Development';
        $mail->Body    = "Thanks for registration! Click the link below to verify the email 
        <a href='http://localhost:81/project2/verify.php?email=$email&v_code=$v_code'>Verify</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $errors[] = "Message could not be sent.";
        return false;
    }
}

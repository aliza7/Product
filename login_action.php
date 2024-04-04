<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    include_once "db_connection.php";

    // Define variables and initialize with empty values
    $email = $password = "";

    // Assign form data to variables
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and bind SQL statement
    $sql = "SELECT * FROM user_details WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    // Execute the SQL statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch user data
        $row = $result->fetch_assoc();
        // Verify password
        if ($password == $row["password"]) {
            // Password is correct, start a new session
            if ($row['verified_status'] == 0) {
                echo "<script>
                alert('Your account has not varifyed. please varify through the mail we have sent.');
                window.location.href='index.php';
                </script>";
            } else {
                session_regenerate_id();
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["user_name"] = $row["name"];
                $_SESSION["user_email"] = $row["email"];
                $_SESSION["user_role"] = $row["user_role"];

                // Redirect to home page
                header("Location: home.php");
                exit();
            }
        } else {
            // Password is not correct
            echo "Invalid password.";
        }
    } else {
        // No user found with the provided email
        echo "No user found with this email.";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect back to login page if accessed directly
    header("Location: login.php");
    exit();
}

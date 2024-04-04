<?php
include_once "db_connection.php";
session_start();

// Check if email and reset code are provided in the URL
if (isset($_GET['email']) && isset($_GET['reset_code'])) {
    $email = $_GET['email'];
    $resetCode = $_GET['reset_code'];

    // Query to check if the email and reset code match
    $query = "SELECT * FROM `user_details` WHERE `email`='$email' AND `reset_code`='$resetCode'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $password = $_POST['password'];
                $confirmPassword = $_POST['cpassword'];

                // Check if passwords match
                if ($password === $confirmPassword) {
                    // Hash the new password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Update the password in the database
                    $updateQuery = "UPDATE `user_details` SET `password`='$hashedPassword', `reset_code`=NULL WHERE `email`='$email'";
                    $updateResult = mysqli_query($conn, $updateQuery);

                    if ($updateResult) {
                        echo "<script>alert('Password updated successfully.');</script>";
                        echo "<script>window.location.href='index.php';</script>";
                    } else {
                        echo "<script>alert('Failed to update password. Please try again.');</script>";
                    }
                } else {
                    echo "<script>alert('Passwords do not match. Please try again.');</script>";
                }
            }
        } else {
            echo "<script>alert('Invalid reset code.');</script>";
        }
    } else {
        echo "<script>alert('Error: Unable to run query.');</script>";
    }
} else {
    echo "<script>alert('Email and reset code not provided.');</script>";
    echo "<script>window.location.href='index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            background-image: url('assets/wall2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;

            font-family: Arial, sans-serif;
            color: #343a40;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            margin: auto;
            margin-top: 50px;
        }

        .container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333333;
        }

        .form-control {
            border-radius: 6px;
            padding: 10px;
        }

        #password_strength,
        #password_match {
            display: block;
            margin-top: 10px;
            padding-left: 60px;
        }

        button[type="submit"] {
            width: 100%;
            margin-top: 20px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#password").on("keyup", function() {
                var password = $(this).val();
                var strength = 0;
                if (password.length >= 8) {
                    strength += 1;
                }
                if (password.match(/([a-z])/)) {
                    strength += 1;
                }
                if (password.match(/([A-Z])/)) {
                    strength += 1;
                }
                if (password.match(/([0-9])/)) {
                    strength += 1;
                }
                if (password.match(/([!@#$%^&*()])/)) {
                    strength += 1;
                }
                if (strength >= 1) {
                    const passwordStrength = document.getElementById('password_strength');
                    passwordStrength.style.paddingLeft = '60px';
                }
                if (strength == 1) {
                    $("#password_strength").html("very");
                    $("#password_strength").css("color", "dark red");
                } else if (strength == 2) {
                    $("#password_strength").html("Weak");
                    $("#password_strength").css("color", "red");
                } else if (strength == 3) {
                    $("#password_strength").html("Moderate");
                    $("#password_strength").css("color", "orange");
                } else if (strength == 4) {
                    $("#password_strength").html("Strong");
                    $("#password_strength").css("color", "green");
                } else if (strength == 5) {
                    $("#password_strength").html("Very Strong");
                    $("#password_strength").css("color", "darkgreen");
                } else {
                    $("#password_strength").html("");
                }
            });
            $("#cpassword").on("keyup", function() {
                const passwordMatch = document.getElementById('password_match');
                passwordMatch.style.paddingLeft = '60px';
                if ($(this).val() == $("#password").val()) {
                    $("#password_match").html("Passwords match");
                    $("#password_match").css("color", "green");
                } else {
                    $("#password_match").html("Passwords do not match");
                    $("#password_match").css("color", "red");
                }
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="#" method="post">
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <span id="password_strength"></span>
            <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword" required>
            </div>
            <span id="password_match"></span>
            <div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
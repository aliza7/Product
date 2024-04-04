<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-image: url('assets/backkk.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            font-family: Arial, sans-serif;
            color: #343a40;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 40px;
        }

        .card-title {
            color: #333;
        }

        .form-control {
            border-radius: 20px;
            border: 2px solid #ddd;
        }

        .btn-primary {
            border-radius: 20px;
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        a {
            color: #007bff;
        }

        a:hover {
            color: #0056b3;
            text-decoration: none;
        }
    </style>
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
                    $("#password_strength").html("Very Weak");
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
            $("#confirm_password").on("keyup", function() {
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">Sign Up</h2>
                        <form action="signup_action.php" method="post" id="signupForm">
                            <div class="form-group">
                                <input type="text" placeholder="Name" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Contact" class="form-control" id="contact" name="contact" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Country" class="form-control" id="country" name="country" required>
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="Sport" class="form-control" id="sport" name="sport" required>
                            </div>
                            <div class="form-group">
                                <input type="password" placeholder="Password" class="form-control" id="password" name="password" required>
                            </div>
                            <span id="password_strength"></span>
                            <div class="form-group">
                                <input type="password" placeholder="Confirm Password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <span id="password_match"></span>
                            <div style="color:red">
                                <?php
                                if (isset($_SESSION['errors'])) {
                                    foreach ($_SESSION['errors'] as $error) {
                                        echo "<p><b>*</b>  $error</p>";
                                    }
                                    unset($_SESSION['errors']);
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                            </div>
                        </form>
                        <p class="text-center">Already have an account? <a href="index.php">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional, for certain components) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
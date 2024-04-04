<?php

session_start();
$userid = $_SESSION['user_id'];
include_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_live'])) {

    $link = $_POST['link'];
    $title = htmlspecialchars($_POST['title']);
    $start_On = htmlspecialchars($_POST['startTime']);
    // Use prepared statements to prevent SQL injection
    $sql = $conn->prepare("INSERT INTO live_video (user_id, link, title, starts_on) 
               VALUES (?,?,?,?)");

    $sql->bind_param("isss", $userid, $link, $title, $start_On);
    if ($sql->execute()) {
        echo "<script>
        alert('Live video details uploaded successfully.');
        window.location.href='home.php';
        </script>";
    } else {
        $em = "Error uploading live video details.";
        echo "<script>
        alert('$em');
        window.location.href='home.php';
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Live Video</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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

        .navbar {
            background-color: #343a40;
            /* Background color */
        }

        .navbar-brand {
            color: #ffffff;
            /* Text color */
        }

        .navbar-nav .nav-link {
            color: #ffffff;
            /* Text color */
        }

        .navbar-nav .nav-link:hover {
            color: #ffffff;
            /* Text color on hover */
        }

        .navbar-toggler {
            border-color: #ffffff;
            /* Border color */
        }

        .navbar-toggler-icon {
            background-color: #ffffff;
            /* Toggle icon color */
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="home.php">Sport</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Live</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Events</a>
                    </li>
                    <?php
                    if ($_SESSION["user_role"] == 1) {
                        echo "<li class='nav-item'>
                        <a class='nav-link active' aria-current='page' href='manage_post.php'>Manage post</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='manage_user.php'>Manage Users</a>
                    </li>";
                    }
                    ?>

                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="padding-top: 90px;">
        <h1 class="text-center mb-4">Post Live Video</h1>
        <form action="add_live_video.php" method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="link">YouTube Video Link:</label>
                <input type="text" id="link" name="link" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="startTime">Schedule Start Time:</label>
                <input type="datetime-local" id="startTime" name="startTime" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_live">Post Live Video</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
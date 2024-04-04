<?php
session_start();

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit();
}
// Include database connection file
include_once "db_connection.php";
$currentDateTime = date('Y-m-d H:i:s');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('assets/eventback.jpg');
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

        .upcoming-events-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .list-group {
            max-height: 80vh;
            /* Limit height to 80% of viewport height */
            overflow-y: auto;
            /* Enable scrolling for overflow content */
        }

        .upcoming-events-heading {
            text-align: center;
            margin-bottom: 20px;
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
                        <a class="nav-link" href="live_posts.php">Live</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="events.php">Events</a>
                    </li>
                    <?php
                    if ($_SESSION["user_role"] == 1) {
                        echo "<li class='nav-item'>
                        <a class='nav-link' href='manage_post.php'>Manage post</a>
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

    <div class="container" style="padding-top: 100px;">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                <div class="upcoming-events-container">
                    <h3 class="upcoming-events-heading">Upcoming Events</h3>
                    <div class="list-group">
                        <?php
                        // Fetch upcoming events from the live_video table
                        $upcoming_sql = "SELECT * FROM live_video WHERE starts_on > '$currentDateTime' ORDER BY starts_on ASC";
                        $upcoming_result = $conn->query($upcoming_sql);

                        if ($upcoming_result->num_rows > 0) {
                            while ($upcoming_row = $upcoming_result->fetch_assoc()) {
                                echo '<div class="card mb-3">';
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title">' . htmlspecialchars($upcoming_row['title']) . '</h5>';
                                echo '<p class="card-text">Scheduled Time: ' . htmlspecialchars($upcoming_row['starts_on']) . '</p>';
                                // Display other details of the upcoming live video
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo "<p class='text-muted text-center'>No upcoming events.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and your custom JS if needed -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
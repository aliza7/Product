<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
                        <a class="nav-link" href="live_posts.php">Live</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php">Events</a>
                    </li>
                    <?php
                    session_start();
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

    <div class="container" style="padding-top: 80px;">
        <h1 class="mt-5">Add Post</h1>
        <form action="upload_post.php" method="post" enctype="multipart/form-data" class="mt-3">
            <div class="form-group">
                <label for="media">Photo or Video:</label>
                <input type="file" class="form-control-file" id="media" name="media" accept="image/*, video/*" required>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="sport">Sport Name:</label>
                <input type="text" class="form-control" id="sport" name="sport" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS (optional, for certain components) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
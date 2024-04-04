<?php
include('db_connection.php');
session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
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


    <div class="container" style="padding-top: 30px;">
        <h2 class='mt-5 mb-4'>Live Video</h2>
        <a href="add_live_video.php" class="btn btn-primary my-2">Add live video</a>
        <div class='row'>
            <?php
            // Prepare the SQL query based on user role
            if ($_SESSION["user_role"] == 1) {
                // Admin user, fetch all posts
                $sql = "SELECT live_video.id, live_video.title, live_video.time, live_video.user_id, user_details.name FROM live_video INNER JOIN user_details ON live_video.user_id = user_details.id ORDER BY live_video.time DESC";
                $result = $conn->query($sql);

                // Execute the SQL query and display posts

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $postId = $row['id'];
                        $postTitle = $row['title'];
                        $postDateTime = $row['time'];
                        $postedBy = ($_SESSION["user_id"] == $row['user_id']) ? $row['name'] : "You"; // Display user's name or "You"

                        // Display the post card
            ?>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $postTitle; ?></h5>
                                    <p class="card-text">Posted by: <?php echo $postedBy; ?> on <?php echo $postDateTime; ?></p>

                                    <!-- Form to manage comments -->
                                    <form action="manage_live_comment.php" method="post" class="mt-2">
                                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                                        <button type="submit" class="btn btn-primary" name="manage_live_comment">Manage Comments</button>
                                    </form>

                                    <!-- Form to delete post -->
                                    <form action="delete_live_post.php" method="post" class="mt-2">
                                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>

            <?php

                    }
                } else {
                    echo "<p class='text-muted' style='padding-left:20px;'>No live post found.</p>";
                }

                echo "</div>";
            }
            ?>

            <h2 class="mt-3">Manage Posts</h2>
            <a href="add_post.php" class="btn btn-primary my-2">Add Post</a>
            <div class="row">
                <?php
                // Prepare the SQL query based on user role
                if ($_SESSION["user_role"] == 1) {
                    // Admin user, fetch all posts
                    $sql = "SELECT post_details.id, post_details.title, post_details.post_on, user_details.name, post_details.user_id 
                        FROM post_details 
                        INNER JOIN user_details ON post_details.user_id = user_details.id 
                        ORDER BY post_details.post_on DESC";
                    $result = $conn->query($sql);
                }

                // Execute the SQL query and display posts

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $postId = $row['id'];
                        $postTitle = $row['title'];
                        $postDateTime = $row['post_on'];
                        $postedBy = ($_SESSION["user_id"] == $row['user_id']) ? $row['name'] : "You"; // Display user's name or "You"

                        // Display the post card
                ?>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $postTitle; ?></h5>
                                    <p class="card-text">Posted by: <?php echo $postedBy; ?> on <?php echo $postDateTime; ?></p>

                                    <!-- Form to manage comments -->
                                    <form action="manage_comments.php" method="post" class="mt-2">
                                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                                        <button type="submit" class="btn btn-primary" name="manage_comment">Manage Comments</button>
                                    </form>

                                    <!-- Form to delete post -->
                                    <form action="deletepost.php" method="post" class="mt-2">
                                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='text-muted'>No posts found.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Bootstrap JS (optional, for certain components) -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
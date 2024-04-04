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

        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: #343a40;
        }

        .comment-container {
            max-height: 400px;
            /* Set maximum height */
            overflow-y: auto;
            /* Enable vertical scrollbar when content exceeds the height */
        }

        .comment-content {
            color: #6c757d;
        }

        .form-group label {
            color: #343a40;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .card-text {
            color: #6c757d;
            /* Limit the text to 3 lines */
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
        }

        .card-text.show-all {
            max-height: unset;
            /* Allow full height when toggled */
            overflow-y: auto;
            /* Enable scrolling for long text */
        }

        .see-more {
            color: #007bff;
            cursor: pointer;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
            /* Ensures the card body fills its container */
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
                        <a class="nav-link active" aria-current="page" href="live_posts.php">Live</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php">Events</a>
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

    <div class="container" style="padding-top: 80px;">
        <div class="row ">

            <div class="col-md-14 mx-auto">
                <h2 style="font-weight: bold;">Live</h2>
                <div class="post-container mt-2">
                    <?php

                    $live_sql = "SELECT * FROM live_video WHERE starts_on <= '$currentDateTime'";
                    $live_result = $conn->query($live_sql);

                    function extractIframeSrc($iframeTag)
                    {
                        // Use regex to extract the src attribute from the iframe tag
                        preg_match('/src="([^"]+)"/', $iframeTag, $matches);

                        // Check if src attribute was found
                        if (isset($matches[1])) {
                            return $matches[1]; // Return the src attribute value
                        } else {
                            return ''; // Return empty string if src attribute not found
                        }
                    }


                    if ($live_result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $live_result->fetch_assoc()) {
                            $videoLink = $row["link"];
                            $videoId = extractIframeSrc($videoLink);
                    ?>
                            <div class="card col-mb-8 mb-4 post-card" style="width: 1300px;">
                                <div class="row">
                                    <div class="col-md-7 post-media">
                                        <?php
                                        echo "<iframe width='100%' height='480px' src='{$videoId}' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' allowfullscreen></iframe>";
                                        ?>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="card-body d-flex flex-column">
                                            <div class="mt-2">
                                                <h5 class="card-title"><?php echo $row["title"]; ?></h5>
                                            </div>
                                            <div class="text-muted mt-auto">

                                            </div>
                                            <div class="card mt-3 comment-container flex-grow-1 p-2">
                                                <h6>Comments</h6>
                                                <?php
                                                // Fetch and display comments for this post
                                                $postId = $row['id'];
                                                $commentsSql = "SELECT live_comment.*, user_details.name AS user_name FROM live_comment INNER JOIN user_details ON live_comment.user_id = user_details.id WHERE live_comment.live_id = $postId ORDER BY live_comment.post_on DESC";
                                                $commentsResult = $conn->query($commentsSql);

                                                if ($commentsResult->num_rows > 0) {
                                                    while ($commentRow = $commentsResult->fetch_assoc()) {
                                                ?>
                                                        <div class="comment">
                                                            <p class="comment-content">
                                                                <strong><?php echo $commentRow['user_name']; ?>:</strong>
                                                                <?php echo htmlspecialchars($commentRow['comment']); ?>
                                                            </p>
                                                        </div>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<p>No comments yet.</p>";
                                                }
                                                ?>

                                            </div>
                                            <div class="mt-3">
                                                <form action="add_live_comment.php" method="post" class="form-row">
                                                    <div class="form-group col-md-9">
                                                        <label for="comment" class="sr-only">Add a comment:</label>
                                                        <input type="text" placeholder="Add comment" class="form-control" id="comment" name="comment" required>
                                                        <input type="hidden" name="live_id" value="<?php echo $postId; ?>">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                    <?php

                        }
                    } else {
                        echo "<p>No posts found.</p>";
                    }

                    // Close database connection
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional, for certain components) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.card-text').each(function() {
                var contentHeight = $(this)[0].scrollHeight;
                var visibleHeight = $(this).height();

                if (contentHeight > visibleHeight) {
                    $(this).addClass('has-overflow'); // Corrected typo, no extra space
                    $(this).after('<span class="see-more"> See more</span>');
                }
            });

            $(document).on('click', '.see-more', function() {
                var cardText = $(this).prev('.card-text');
                cardText.toggleClass('show-all');
                if (cardText.hasClass('show-all')) {
                    $(this).text(' See less');
                } else {
                    $(this).text(' See more');
                }
            });
        });
    </script>
</body>

</html>
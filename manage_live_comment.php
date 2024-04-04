<?php
// Include database connection file
include_once "db_connection.php";
session_start();
$userid = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['manage_live_comment'])) {
    if (isset($_POST['post_id'])) {
        $postId = $_POST['post_id'];

        // Fetch post details
        $sql = $conn->prepare("SELECT * FROM live_video WHERE id = ?");
        $sql->bind_param("i", $postId);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $postTitle = $row['title'];
            // Fetch comments based on user role

            $commentsSql = $conn->prepare("SELECT live_comment.*, user_details.name AS username FROM live_comment INNER JOIN user_details ON live_comment.user_id = user_details.id WHERE live_id = ?");
            $commentsSql->bind_param("i", $postId);

            $commentsSql->execute();
            $commentsResult = $commentsSql->get_result();
        } else {
            // Handle case where post does not exist
            echo ("live video not found.");
            exit();
        }
    } else {
        // Handle case where post_id is not set
        echo ("live ID not set.");
        exit();
    }
}

// Delete comment if delete button is clicked
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_live_comment'])) {
    $commentId = $_POST['live_comment_id'];

    // Delete comment from database
    $deleteSql = $conn->prepare("DELETE FROM live_comment WHERE id = ?");
    $deleteSql->bind_param("i", $commentId);

    if ($deleteSql->execute()) {
        // Comment deleted successfully
        header("Location: manage_post.php");
        exit();
    } else {
        // Failed to delete comment
        echo "Error: Unable to delete comment.";
    }
} else {
    // Handle case where request method is not POST
    echo ("Invalid request method.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
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

    <div class="container" style="padding-top:60px;">
        <h2 class="mt-5 mb-4">Manage Live Comments for <?php echo $postTitle; ?></h2>
        <div class="row">
            <?php
            if ($commentsResult->num_rows > 0) {
                while ($commentRow = $commentsResult->fetch_assoc()) {
                    // Display each comment
                    $commentId = $commentRow['id'];
                    $commentText = $commentRow['comment'];
                    $commentUser = $commentRow['username']; // Fetched username
            ?>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="card-text"><strong><?php echo $commentUser; ?>:</strong> <?php echo $commentText; ?></p>
                                <!-- Add form to delete each comment -->
                                <form action="manage_live_comment.php" method="post">
                                    <input type="hidden" name="live_comment_id" value="<?php echo $commentId; ?>">
                                    <button type="submit" class="btn btn-danger" name="delete_live_comment">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-muted'>No Live comments found for this video.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
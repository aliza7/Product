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
            background-color: #f8f9fa;
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

    <div class="upcoming-live-video position-fixed top-0 end-0 mt-5 ">
        <!-- <h3>Upcoming Live Video</h3> -->
        <div class="upcoming-events-container mt-5 me-2">
            <h3 class="text-center mb-3">Upcoming Events</h3>
            <div class="list-group">
                <?php
                // Fetch upcoming events from the live_video table
                $upcoming_sql = "SELECT * FROM live_video WHERE starts_on > '$currentDateTime' ORDER BY starts_on ASC";
                $upcoming_result = $conn->query($upcoming_sql);

                if ($upcoming_result->num_rows > 0) {
                    while ($upcoming_row = $upcoming_result->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($upcoming_row['title']) . '</h5>';
                        echo '<p class="card-text">Scheduled Time: ' . htmlspecialchars($upcoming_row['starts_on']) . '</p>';
                        // Display other details of the upcoming live video
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-muted'>No upcoming events.</p>";
                }
                ?>
            </div>
        </div>
    </div>

</body>

</html>
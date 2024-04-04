<?php
include('db_connection.php');
session_start();

if (isset($_POST['post_id']) && isset($_POST['comment'])) {
    // Sanitize inputs
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Prepare and execute SQL statement to insert comment
    $sql = $conn->prepare("INSERT INTO post_comments (user_id, post_id, comment) VALUES (?, ?, ?)");
    $sql->bind_param("iis", $user_id, $post_id, $comment);
    if ($sql->execute()) {
        // Comment added successfully
        header("Location: home.php"); // Redirect to dashboard or wherever appropriate
        exit();
    } else {
        // Error occurred while adding comment
        echo "Error: " . $sql->error;
    }
} else {
    // Redirect user if they accessed this page without proper parameters
    header("Location: home.php");
    exit();
}

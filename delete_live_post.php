<?php
// Include database connection or initialization file
include('db_connection.php');
session_start();
 
 
// Check if the form has been submitted and the comment ID is provided
if (isset($_POST['post_id'])) {
    // Sanitize the input to prevent SQL injection
    $postId = mysqli_real_escape_string($conn, $_POST['post_id']);
 
    // SQL query to delete the live_post
    $sql = "DELETE FROM live_video WHERE id = $postId";
 
    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Comment deleted successfully
        header("Location: home.php"); // Redirect back to the manage comments page
        exit();
    } else {
        // Error occurred while deleting the comment
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If the form was not submitted properly, redirect to the manage comments page
    header("Location: home.php");
    exit();
}
 
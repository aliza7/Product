<?php
// Include database connection or initialization file
include('db_connection.php');
session_start();
$userid = $_SESSION['user_id'];
// Check if the form is submitted with a post ID
if (isset($_POST['post_id'])) {
    // Sanitize and store the post ID
    $post_id = $_POST['post_id'];
 
    // Prepare SQL statement to fetch file address of the post
    $fetch_file_address_sql = $conn->prepare("SELECT file_location FROM post_details WHERE id = ?");
    $fetch_file_address_sql->bind_param("i", $post_id);
    $fetch_file_address_sql->execute();
    $fetch_file_address_result = $fetch_file_address_sql->get_result();
    $file_row = $fetch_file_address_result->fetch_assoc();
    $file_address = $file_row['file_location'];
 
    // Check if the file address exists
    if (!empty($file_address)) {
        // Construct the file path
        $file_path = "assets/uploads/{$userid}/{$file_address}";
 
        // Check if the file exists
        if (file_exists($file_path)) {
            // Attempt to delete the file
            if (!unlink($file_path)) {
                // Failed to delete the file
                echo "<script>alert('Failed to delete post media file');</script>";
                // Redirect back to the manage posts page
                header("Location: home.php");
                exit();
            }
        }
    }
 
    // Prepare SQL statement to delete comments related to the post
    $delete_comments_sql = $conn->prepare("DELETE FROM post_comments WHERE post_id = ?");
    $delete_comments_sql->bind_param("i", $post_id);
 
    // Prepare SQL statement to delete the post from the database
    $delete_post_sql = $conn->prepare("DELETE FROM post_details WHERE id = ?");
    $delete_post_sql->bind_param("i", $post_id);
 
    // Execute the delete comments query
    $delete_comments_success = $delete_comments_sql->execute();
 
    // Execute the delete post query
    $delete_post_success = $delete_post_sql->execute();
 
    // Check if both delete operations were successful
    if ($delete_comments_success && $delete_post_success) {
        // Both comments and post deleted successfully
        echo "<script>alert('Post and associated comments deleted successfully');</script>";
        // Redirect back to the manage posts page
        header("Location: home.php");
        exit();
    } else {
        // Failed to delete post or comments
        echo "<script>alert('Failed to delete post or associated comments');</script>";
        // Redirect back to the manage posts page
        header("Location: home.php");
        exit();
    }
} else {
    // If the form is not submitted with a post ID, redirect back to the manage posts page
    header("Location: home.php");
    exit();
}
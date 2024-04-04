<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_SESSION['user_id'];
    $description = htmlspecialchars($_POST['description']);
    $title = htmlspecialchars($_POST['title']);
    $sport = htmlspecialchars($_POST['sport']);

    if (isset($_FILES['media'])) {
        include "db_connection.php"; // Include database connection file

        // Retrieve file information
        $media_name = $_FILES['media']['name'];
        $media_size = $_FILES['media']['size'];
        $tmp_name = $_FILES['media']['tmp_name'];
        $error = $_FILES['media']['error'];

        if ($error === 0) {
            if ($media_size < 1) {
                $em = "Sorry, your file is too large.";
                echo ($em);
                exit;
            } else {
                $media_ex = pathinfo($media_name, PATHINFO_EXTENSION);
                $media_ex_lc = strtolower($media_ex);
                $allowed_exs = array("jpg", "jpeg", "png", "mp4");

                if (in_array($media_ex_lc, $allowed_exs)) {
                    $upath = 'assets/uploads/' . $userid;
                    if (!file_exists($upath)) {
                        // Create the directory
                        if (!mkdir($upath, 0777, true)) {
                            $em = "Error creating directory.";
                            echo ($em);
                            exit;
                        }
                    }
                    $new_media_name = uniqid("Media-", true) . '.' . $media_ex_lc;
                    $media_upload_path = $upath . '/' . $new_media_name;

                    // Move uploaded file to destination
                    if (move_uploaded_file($tmp_name, $media_upload_path)) {
                        // Use prepared statements to prevent SQL injection
                        $sql = $conn->prepare("INSERT INTO post_details (user_id, file_location, title, description, sport) 
                                                VALUES (?,?,?,?,?)");
                        $sql->bind_param("issss", $userid, $media_upload_path, $title, $description, $sport);
                        if ($sql->execute()) {
                            echo "<script>
                                    alert('Uploaded successfully.');
                                    window.location.href='home.php';
                                  </script>";
                            exit;
                        } else {
                            $em = "Error executing SQL statement.";
                        }
                    } else {
                        $em = "Error moving file to destination.";
                    }
                } else {
                    $em = "You can't upload files of this type.";
                }
            }
        } else {
            $em = "Unknown error occurred!";
        }
    } else {
        $em = "No file uploaded.";
    }
} else {
    header("Location: home.php");
}
echo "<script>
        alert('$em');
        window.location.href='home.php';
      </script>";

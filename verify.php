<?php
// Include database connection file
include_once "db_connection.php";
session_start();
if (isset($_GET['email']) && isset($_GET['v_code'])) {

    $query = "SELECT * FROM `user_details` WHERE `email`='$_GET[email]' AND `verification_code`='$_GET[v_code]'";

    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $result_fetch = mysqli_fetch_assoc($result);
            if ($result_fetch['verified_status'] == 0) {
                $update = "UPDATE `user_details` SET `verified_status`='1' WHERE `email`='$result_fetch[email]'";
                if (mysqli_query($conn, $update)) {
                    echo "
                    <script>
                    alert('Email verification sucessfull');
                    window.location.href='index.php';
                    </script>
                    ";
                } else {
                    echo "
                    <script>
                    alert('Email already verified');
                    window.location.href='index.php';
                    </script>
                    ";
                }
            } else {
                echo "
                <script>
                alert('Email already varified');
                window.location.href='index.php';
                </script>
                ";
            }
        }
    } else {
        echo "
    <script>
    alert('Cannot run query');
    window.location.href='index.php';
    </script>
    ";
    }
}

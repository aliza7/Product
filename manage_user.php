<?php
include('db_connection.php');
session_start();

// Check if user is logged in and has admin privileges (user_role = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: login.php"); // Redirect unauthorized users
    exit();
}

// Fetch users from the database
$sql = "SELECT * FROM user_details";
$result = $conn->query($sql);

// Handle delete user request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    $deleteSql = "DELETE FROM user_details WHERE id = $userId";
    if ($conn->query($deleteSql) === TRUE) {
        // User deleted successfully
        header("Location: manage_user.php");
        exit();
    } else {
        // Failed to delete user
        echo "Error: Unable to delete user.";
    }
}

// Handle block/unblock user request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['block_user'])) {
    $userId = $_POST['user_id'];
    $disable =  $_POST['disable'];
    if ($disable == 0) {
        $disable = 1;
    } else {
        $disable = 0;
    }
    $blockSql = "UPDATE user_details SET disable = $disable WHERE id = $userId";
    if ($conn->query($blockSql) === TRUE) {
        // User status updated successfully
        header("Location: manage_user.php");
        exit();
    } else {
        // Failed to update user status
        echo "Error: Unable to update user status.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $country = $_POST['country'];
    $role = $_POST['role'];

    $updateSql = "UPDATE user_details SET name = '$name', email = '$email', contact = '$contact', country = '$country', user_role = $role WHERE id = $userId";
    if ($conn->query($updateSql) === TRUE) {
        // User details updated successfully
        header("Location: manage_user.php");
        exit();
    } else {
        // Failed to update user details
        echo "Error: Unable to update user details.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        .body_container {
            margin-top: 70px;
            padding-right: 50px;
            padding-left: 50px;

            /* Adjust top margin to accommodate the fixed navbar */
        }

        .table th,
        .table td {
            vertical-align: middle;
            background-color: #ffffff;
        }

        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .modal-dialog {
            max-width: 600px;
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
                        <a class='nav-link' href='manage_post.php'>Manage post</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link active' aria-current='page' href='manage_user.php'>Manage Users</a>
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

    <div class="body_container">
        <h2 class="mt-5 mb-4">Manage Users</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Country</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['contact'] . "</td>";
                        echo "<td>" . $row['country'] . "</td>";
                        echo "<td>" . ($row['user_role'] == 1 ? 'Admin' : 'User') . "</td>";
                        echo "<td>";
                        echo "<form action='manage_user.php' method='post'>";
                        echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                        echo "<input type='hidden' name='blocked' value='" . $row['disable'] . "'>";
                        echo "<button type='submit' class='btn btn-danger mr-2' name='delete_user'>Delete</button>";
                        echo "<button type='button' class='btn btn-primary mr-2' data-bs-toggle='modal' data-bs-target='#editModal" . $row['id'] . "'>Edit</button>";
                        echo "<button type='submit' class='btn btn-warning ms-1' name='block_user' value='" . ($row['disable'] == 1 ? 0 : 1) . "'>" . ($row['disable'] == 1 ? 'Unblock' : 'Block') . "</button>";
                        echo "</form>";
                        // Reset Password Button
                        echo "<form action='mail_reset_password.php' method='post'>";
                        echo "<input type='hidden' name='email' value='" . $row['email'] . "'>";
                        echo "<button type='submit' class='btn btn-info mt-2' name='reset_password' value='" . $row['email'] . "'>Reset Password</button>";
                        echo "</form>";
                        echo "</td>";
                        // Edit User Modal
                        echo "<div class='modal fade' id='editModal" . $row['id'] . "' tabindex='-1' aria-labelledby='editModalLabel" . $row['id'] . "' aria-hidden='true'>";
                        echo "<div class='modal-dialog'>";
                        echo "<div class='modal-content'>";
                        echo "<div class='modal-header'>";
                        echo "<h5 class='modal-title' id='editModalLabel" . $row['id'] . "'>Edit User</h5>";
                        echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                        echo "</div>";
                        echo "<div class='modal-body'>";
                        echo "<form action='manage_user.php' method='post'>";
                        echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                        echo "<div class='mb-3'>";
                        echo "<label for='name' class='form-label'>Name</label>";
                        echo "<input type='text' class='form-control' id='name' name='name' value='" . $row['name'] . "' required>";
                        echo "</div>";
                        echo "<div class='mb-3'>";
                        echo "<label for='email' class='form-label'>Email</label>";
                        echo "<input type='email' class='form-control' id='email' name='email' value='" . $row['email'] . "' required>";
                        echo "</div>";
                        echo "<div class='mb-3'>";
                        echo "<label for='contact' class='form-label'>Contact</label>";
                        echo "<input type='text' class='form-control' id='contact' name='contact' value='" . $row['contact'] . "' required>";
                        echo "</div>";
                        echo "<div class='mb-3'>";
                        echo "<label for='country' class='form-label'>Country</label>";
                        echo "<input type='text' class='form-control' id='country' name='country' value='" . $row['country'] . "' required>";
                        echo "</div>";
                        echo "<div class='mb-3'>";
                        echo "<label for='role' class='form-label'>Role</label>";
                        echo "<select class='form-select' id='role' name='role' required>";
                        echo "<option value='1'" . ($row['user_role'] == 1 ? ' selected' : '') . ">Admin</option>";
                        echo "<option value='2'" . ($row['user_role'] == 2 ? ' selected' : '') . ">User</option>";
                        echo "</select>";
                        echo "</div>";
                        echo "<button type='submit' class='btn btn-primary' name='edit_user' >Save changes</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "<div class='modal-footer'>";
                        echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
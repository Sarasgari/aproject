<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Project - AProject</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .navbar {
            background-color: #007bff;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #fff;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            text-align: center;
        }
        .message {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
    <a href="index.php">Home</a>
        <a href="login.php">Log In</a>
        <a href="register.php">Register</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Delete Project</h2>
        <div class="message">
            <?php
            // Start session to manage user authentication
            session_start();

            // Include database connection file
            require_once 'db_connect.php';

            // Check if project ID is provided via GET or POST request
            if (isset($_GET['pid'])) {
                $pid = $_GET['pid'];
            } elseif (isset($_POST['pid'])) {
                $pid = $_POST['pid'];
            } else {
                exit("Project ID not provided.");
            }

            // Check if the user owns the project
            $stmt = $pdo->prepare("SELECT uid FROM projects WHERE pid = :pid");
            $stmt->execute(['pid' => $pid]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || $row['uid'] != $_SESSION['user_id']) {
                echo "You do not have permission to delete this project.";
            } else {
                // Delete the project from the database
                $stmt = $pdo->prepare("DELETE FROM projects WHERE pid = :pid");
                $stmt->execute(['pid' => $pid]);
                echo "Project deleted successfully.";
            }
            ?>
        </div>
    </div>
</body>
</html>

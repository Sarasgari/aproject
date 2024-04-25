<?php
// Start session to manage user authentication
session_start();

// Check if the user is not logged in, redirect to login page if so
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include database connection file
require_once 'db_connect.php';

// Check if project ID is provided via GET request
if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
} else {
    exit("Project ID not provided.");
}

// Fetch project details from the database
try {
    $stmt = $pdo->prepare("SELECT pid, title, start_date, end_date, phase, description FROM projects WHERE pid = :pid AND uid = :uid");
    $stmt->execute(['pid' => $pid, 'uid' => $_SESSION['user_id']]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        exit("Project not found.");
    }
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Project - AProject</title>
    <!-- CSS styles -->
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
        .view-project-container {
            max-width: 600px;
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
        p {
            margin-bottom: 10px;
        }
        strong {
            font-weight: bold;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="#home">Home</a>
        <a href="login.php">Log In</a>
        <a href="register.php">Register</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="add_project.php">Add Project</a>
        <a href="search_project.php">Search Projects</a>
    </div>

    <div class="view-project-container">
        <h2>Project Details</h2>
        <div>
            <p><strong>Title:</strong> <?php echo htmlspecialchars($project['title']); ?></p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($project['start_date']); ?></p>
            <p><strong>End Date:</strong> <?php echo htmlspecialchars($project['end_date']); ?></p>
            <p><strong>Phase:</strong> <?php echo htmlspecialchars($project['phase']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($project['description']); ?></p>
        </div>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php
// Start session to manage user authentication
session_start();

// Check if the user is not logged in, redirect to login page if so
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include database connection file
require_once 'db_connect.php';

// Define variables to store project details and error messages
$pid = $title = $start_date = $end_date = $phase = $description = '';
$titleErr = $start_dateErr = $end_dateErr = $phaseErr = $descriptionErr = $updateProjectErr = '';

// Check if project ID is provided via GET request
if(isset($_GET['pid'])) {
    // Fetch project details from the database
    try {
        $pid = $_GET['pid'];
        $stmt = $pdo->prepare("SELECT title, start_date, end_date, phase, description FROM projects WHERE pid = :pid AND uid = :uid");
        $stmt->execute(['pid' => $pid, 'uid' => $_SESSION['user_id']]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$project) {
            exit("Project not found.");
        }

        // Assign fetched project details to variables
        $title = $project['title'];
        $start_date = $project['start_date'];
        $end_date = $project['end_date'];
        $phase = $project['phase'];
        $description = $project['description'];
    } catch (PDOException $e) {
        exit("Error: " . $e->getMessage());
    }
}

// Process form submission on project update attempt
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate title
    if(empty(trim($_POST['title']))) {
        $titleErr = "Please enter a title for the project.";
    } else {
        $title = trim($_POST['title']);
    }

    // Validate start date
    if(empty(trim($_POST['start_date']))) {
        $start_dateErr = "Please enter the start date of the project.";
    } else {
        $start_date = trim($_POST['start_date']);
    }

    // Validate end date
    if(empty(trim($_POST['end_date']))) {
        $end_dateErr = "Please enter the end date of the project.";
    } else {
        $end_date = trim($_POST['end_date']);
    }

    // Validate phase
    if(empty(trim($_POST['phase']))) {
        $phaseErr = "Please select a phase for the project.";
    } else {
        $phase = trim($_POST['phase']);
    }

    // Validate description
    if(empty(trim($_POST['description']))) {
        $descriptionErr = "Please enter a description for the project.";
    } else {
        $description = trim($_POST['description']);
    }

    // Check input errors before updating project in the database
    if(empty($titleErr) && empty($start_dateErr) && empty($end_dateErr) && empty($phaseErr) && empty($descriptionErr)) {
        // Prepare SQL statement to update project record in the database
        $sql = "UPDATE projects SET title = :title, start_date = :start_date, end_date = :end_date, phase = :phase, description = :description WHERE pid = :pid AND uid = :uid";

        if($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":start_date", $start_date, PDO::PARAM_STR);
            $stmt->bindParam(":end_date", $end_date, PDO::PARAM_STR);
            $stmt->bindParam(":phase", $phase, PDO::PARAM_STR);
            $stmt->bindParam(":description", $description, PDO::PARAM_STR);
            $stmt->bindParam(":pid", $pid, PDO::PARAM_INT);
            $stmt->bindParam(":uid", $_SESSION['user_id'], PDO::PARAM_INT);

            // Attempt to execute the prepared statement
            if($stmt->execute()) {
                // Redirect user to dashboard page after successful project update
                header("Location: dashboard.php");
            } else {
                $updateProjectErr = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project - AProject</title>
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
        .update-project-container {
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
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
        }
        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
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

    <div class="update-project-container">
        <h2>Update Project</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
                <span class="error"><?php echo $titleErr; ?></span>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                <span class="error"><?php echo $start_dateErr; ?></span>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                <span class="error"><?php echo $end_dateErr; ?></span>
            </div>
            <div class="form-group">
                <label for="phase">Phase:</label>
                <select id="phase" name="phase">
                    <option value="" disabled>Select Phase</option>
                    <option value="design" <?php if($phase == 'design') echo 'selected'; ?>>Design</option>
                    <option value="development" <?php if($phase == 'development') echo 'selected'; ?>>Development</option>
                    <option value="testing" <?php if($phase == 'testing') echo 'selected'; ?>>Testing</option>
                    <option value="deployment" <?php if($phase == 'deployment') echo 'selected'; ?>>Deployment</option>
                    <option value="complete" <?php if($phase == 'complete') echo 'selected'; ?>>Complete</option>
                </select>
                <span class="error"><?php echo $phaseErr; ?></span>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
                <span class="error"><?php echo $descriptionErr; ?></span>
            </div>
            <div class="form-group">
                <button type="submit">Update Project</button>
            </div>
            <span class="error"><?php echo $updateProjectErr; ?></span>
        </form>
    </div>
</body>
</html>

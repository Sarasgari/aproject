<?php

session_start();

require_once 'db_connect.php';


$title = $start_date = $end_date = $phase = $description = '';
$titleErr = $start_dateErr = $end_dateErr = $phaseErr = $descriptionErr = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (empty(trim($_POST['title']))) {
        $titleErr = "Please enter a title for the project.";
    } else {
        $title = trim($_POST['title']);
    }

    
    if (empty(trim($_POST['start_date']))) {
        $start_dateErr = "Please enter the start date for the project.";
    } else {
        $start_date = trim($_POST['start_date']);
    }

    
    if (empty(trim($_POST['end_date']))) {
        $end_dateErr = "Please enter the end date for the project.";
    } else {
        $end_date = trim($_POST['end_date']);
    }

    
    if (empty($_POST['phase'])) {
        $phaseErr = "Please select the phase of the project.";
    } else {
        $phase = $_POST['phase'];
    }

    
    if (empty(trim($_POST['description']))) {
        $descriptionErr = "Please enter a description for the project.";
    } else {
        $description = trim($_POST['description']);
    }

    
    if (empty($titleErr) && empty($start_dateErr) && empty($end_dateErr) && empty($phaseErr) && empty($descriptionErr)) {
        try {
            
            $stmt = $pdo->prepare("INSERT INTO projects (title, start_date, end_date, phase, description, uid) VALUES (:title, :start_date, :end_date, :phase, :description, :uid)");

            
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
            $stmt->bindParam(':phase', $phase, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':uid', $_SESSION['user_id'], PDO::PARAM_INT);

            
            if ($stmt->execute()) {
                
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        } catch (PDOException $e) {
            exit("Error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project - AProject</title>
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
        .add-project-container {
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
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: calc(100% - 40px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position-x: 100%;
            background-position-y: 50%;
            background-size: 16px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
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

    <div class="add-project-container">
        <h2>Add New Project</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
                <span class="error"><?php echo $titleErr; ?></span>
            </div>
            <div>
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                <span class="error"><?php echo $start_dateErr; ?></span>
            </div>
            <div>
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                <span class="error"><?php echo $end_dateErr; ?></span>
            </div>
            <div>
                <label for="phase">Phase:</label>
                <select id="phase" name="phase">
                    <option value="" selected disabled>Select Phase</option>
                    <option value="Planning" <?php echo ($phase == 'Planning') ? 'selected' : ''; ?>>Planning</option>
                    <option value="Execution" <?php echo ($phase == 'Execution') ? 'selected' : ''; ?>>Execution</option>
                    <option value="Monitoring" <?php echo ($phase == 'Monitoring') ? 'selected' : ''; ?>>Monitoring</option>
                    <option value="Closure" <?php echo ($phase == 'Closure') ? 'selected' : ''; ?>>Closure</option>
                </select>
                <span class="error"><?php echo $phaseErr; ?></span>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
                <span class="error"><?php echo $descriptionErr; ?></span>
            </div>
            <div>
                <button type="submit">Add Project</button>
            </div>
        </form>
    </div>
</body>
</html>

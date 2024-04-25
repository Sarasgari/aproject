<?php
// Start session to manage user authentication
session_start();

// Include database connection file
require_once 'db_connect.php';

// Define variables to store search query and error message
$search = '';
$searchErr = '';

// Process form submission on search attempt
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate search query
    if (empty(trim($_POST['search']))) {
        $searchErr = "Please enter a search query.";
    } else {
        $search = trim($_POST['search']);
    }

    // If search query is not empty, perform search
    if (!empty($search)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM projects WHERE title LIKE :search OR start_date LIKE :search");
            $stmt->execute(['search' => '%' . $search . '%']);
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Search Projects - AProject</title>
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
        .search-container {
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
        input[type="text"] {
            width: calc(100% - 40px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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

    <div class="search-container">
        <h2>Search Projects</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="search">Search by Title or Start Date:</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
            <span class="error"><?php echo $searchErr; ?></span>
        </form>

        <?php if (!empty($projects)) : ?>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Phase</th>
                    <th>Description</th>
                </tr>
                <?php foreach ($projects as $project) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['title']); ?></td>
                        <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($project['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($project['phase']); ?></td>
                        <td><?php echo htmlspecialchars($project['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

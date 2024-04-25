<?php
// Include database connection file
require_once 'db_connect.php';

// Fetch all projects from the database
try {
    $stmt = $pdo->prepare("SELECT pid, title, start_date, end_date, phase, description FROM projects");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AProject</title>
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
        .dashboard-container {
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        .toolbar {
            text-align: center;
            margin-bottom: 20px;
        }
        .toolbar button {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .toolbar button:hover {
            background-color: #0056b3;
        }
        .add-project-link {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }
        .view-project-link {
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
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

    <div class="dashboard-container">
        <h2>Welcome to your Dashboard</h2>
        <div class="toolbar">
            <button onclick="window.location.href='add_project.php'">Add Project</button>
            <button onclick="window.location.href='search_project.php'">Search Project</button>
            <button onclick="window.location.href='delete_project.php'">Delete Project</button>
            <button onclick="window.location.href='update_project.php'">update Project</button>
        </div>
        <div class="project-list">
            <?php if (empty($projects)) : ?>
                <p>No projects found.</p>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Phase</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project) : ?>
                            <tr>
                                <td class="view-project-link" data-pid="<?php echo $project['pid']; ?>"><?php echo htmlspecialchars($project['title']); ?></td>
                                <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($project['end_date']); ?></td>
                                <td><?php echo htmlspecialchars($project['phase']); ?></td>
                                <td><?php echo htmlspecialchars($project['description']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Add click event listener to project titles to view project details
        const projectLinks = document.querySelectorAll('.view-project-link');
        projectLinks.forEach(link => {
            link.addEventListener('click', () => {
                const projectId = link.getAttribute('data-pid');
                window.location.href = `view_project.php?pid=${projectId}`;
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AProject - Home</title>
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
        h1 {
            color: #007bff;
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto;
            border-radius: 8px;
        }
        .links {
            text-align: center;
        }
        .links a {
            display: inline-block;
            margin: 10px;
            text-decoration: none;
        }
        .links img {
            width: 64px;
            height: 64px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="login.php">Log In</a>
        <a href="register.php">Register</a>
        <a href="dashboard.php">Dashboard</a>
    </div>

    <div class="container">
        <h1>Welcome to Aston project management website</h1>
        <p>Aston project management is a dynamic database-driven web application for managing software projects for Aston students.</p>
        <img src="project_management_image.jpg" alt="Project Management" width="600">
        <div class="links">
            <a href="login.php"><img src="login_icon.png" alt="Log In"></a>
            <a href="register.php"><img src="register_icon.png" alt="Register"></a>
            <a href="dashboard.php"><img src="dashboard_icon.png" alt="Dashboard"></a>
            <a href="add_project.php"><img src="add_project_icon.png" alt="Add Project"></a>
            <a href="search_project.php"><img src="search_project_icon.png" alt="Search Projects"></a>
        </div>
    </div>
</body>
</html>

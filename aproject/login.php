<?php

session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}


require_once 'db_connect.php';


$username = $password = '';
$usernameErr = $passwordErr = $loginErr = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (empty(trim($_POST['username']))) {
        $usernameErr = "Please enter your username.";
    } else {
        $username = trim($_POST['username']);
    }

    
    if (empty(trim($_POST['password']))) {
        $passwordErr = "Please enter your password.";
    } else {
        $password = trim($_POST['password']);
    }

    
    if (empty($usernameErr) && empty($passwordErr)) {
        
        $sql = "SELECT uid, username, password FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
            
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            
            $param_username = $username;

            
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $uid = $row['uid'];
                        $username = $row['username'];
                        $hashed_password = $row['password'];
                        if (password_verify($password, $hashed_password)) {
                            
                            session_start();

                            
                            $_SESSION['user_id'] = $uid;
                            $_SESSION['username'] = $username;

                            
                            header("Location: dashboard.php");
                        } else {
                            
                            $loginErr = "Invalid username or password.";
                        }
                    }
                } else {
                    
                    $loginErr = "Invalid username or password.";
                }
            } else {
                
                $loginErr = "Oops! Something went wrong. Please try again later.";
            }

            
            unset($stmt);
        }
    }

    
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AProject</title>
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
        .login-container {
            max-width: 400px;
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
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .error {
            color: red;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        p {
            text-align: center;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
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

    <div class="login-container">
        <h2>Login to Aston project Management</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <span class="error"><?php echo $usernameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span class="error"><?php echo $passwordErr; ?></span>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
            <span class="error"><?php echo $loginErr; ?></span>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html>

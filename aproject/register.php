<?php

session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}


require_once 'db_connect.php';


$username = $password = $confirm_password = $email = '';
$usernameErr = $passwordErr = $confirm_passwordErr = $emailErr = $registerErr = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (empty(trim($_POST['username']))) {
        $usernameErr = "Please enter a username.";
    } else {
        
        $sql = "SELECT uid FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
            
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            
            $param_username = trim($_POST['username']);

            
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $usernameErr = "This username is already taken.";
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                $registerErr = "Oops! Something went wrong. Please try again later.";
            }

            
            unset($stmt);
        }
    }

    
    if (empty(trim($_POST['password']))) {
        $passwordErr = "Please enter a password.";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $passwordErr = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST['password']);
    }

   
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_passwordErr = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_passwordErr = "Password did not match.";
        }
    }

    
    if (empty(trim($_POST['email']))) {
        $emailErr = "Please enter an email address.";
    } elseif (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email address format.";
    } else {
        $email = trim($_POST['email']);
    }

    
    if (empty($usernameErr) && empty($passwordErr) && empty($confirm_passwordErr) && empty($emailErr)) {
        
        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";

        if ($stmt = $pdo->prepare($sql)) {
            
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash password for security
            $param_email = $email;

            
            if ($stmt->execute()) {
                
                header("Location: login.php");
            } else {
                $registerErr = "Oops! Something went wrong. Please try again later.";
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
    <title>Register - AProject</title>
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
        .register-container {
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
        input[type="password"],
        input[type="email"] {
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

    <div class="register-container">
        <h2>Register for Aston project managment</h2>
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
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span class="error"><?php echo $confirm_passwordErr; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
            <span class="error"><?php echo $registerErr; ?></span>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>

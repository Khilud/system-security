<?php
header('Cache-Control: no-cache, must-revalidate');
require_once 'DBUtils.php';
session_start();
if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
}

function checkValidPassword(string $username, string $password): bool {
    $connection = new DBConnection();
    $result = $connection->selectUserByUsername($username);
    if (count($result) == 0) {
        return false;
    } else {
        if ($result[0]["password"] === $password) {
            return true;
        } else {
            return false;
        }
    }
}

if (isset($_POST['loginButton'])) {
    $errors = 0;
    $fields = array("username", "password");
    foreach ($fields as $key => $field) {
        if (!isset($_POST[$field]) && empty($_POST[$field])) {
            $errors++;
        }
    }

    if ($errors <= 0) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (checkValidPassword($username, $password)) {
            $_SESSION['username'] = $username;
            header("Location: home.php");
        } else {
            $_SESSION['login-error'] = "Invalid username and/or password! Try again!";
        }
    } else {
        $_SESSION['login-error'] = "Invalid username and/or password! Try again!";
    }
}

if (isset($_POST['indexPage'])) {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2c3e50, #bdc3c7);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        .form-control {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            width: 100%;
            font-size: 16px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            color: white;
            margin-bottom: 15px;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #842029;
        }
        .text-center {
            text-align: center;
        }
        .text-primary {
            color: #3498db !important;
            text-decoration: none;
        }
        .text-primary:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (isset($_SESSION['login-error'])): ?>
            <div class="alert">
                <p>Invalid username and/or password! Try again!</p>
            </div>
        <?php unset($_SESSION['login-error']); endif; ?>

        <form id="login-form" method="post" action="login.php">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>

            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>

            <button type="submit" class="btn btn-primary" name="loginButton">Login</button>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </form>

        <div class="text-center mt-3">
            <p>Don't have an account? <a href="signup.php" class="text-primary">Sign Up</a></p>
        </div>
    </div>
</body>
</html>

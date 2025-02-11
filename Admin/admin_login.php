<?php
header('Cache-Control: no-cache, must-revalidate');
require_once '../DBUtils.php';
session_start();

if (isset($_SESSION['admin_username'])) {
    unset($_SESSION['admin_username']);
}

function checkValidAdminPassword(string $username, string $password): bool {
    $connection = new DBConnection();
    $result = $connection->selectAdminByUsername($username); // Query from 'admins' table
    if (count($result) == 0) {
        return false;
    } else {
        return $result[0]["password"] === $password;
    }
}

if (isset($_POST['loginButton'])) {
    $errors = 0;
    $fields = array("username", "password");
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errors++;
        }
    }

    if ($errors <= 0) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (checkValidAdminPassword($username, $password)) {
            $_SESSION['admin_username'] = $username;
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php"); // Redirect to admin panel
            exit();
        } else {
            $_SESSION['login-error'] = "Invalid username and/or password! Try again!";
        }
    } else {
        $_SESSION['login-error'] = "Invalid username and/or password! Try again!";
    }
}

if (isset($_POST['indexPage'])) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Admin Login</title> 
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, rgb(84, 153, 199), rgb(46, 64, 83));
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
            color: #333;
        }
        .btn-primary {
            background-color: rgb(46, 134, 222);
            border: none;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
        }
        .btn-primary:hover {
            background-color: rgb(52, 152, 219);
        }
        .error-message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form id="login-form" method="post" action="admin_login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Admin Username:</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
            </div>
            <input type="submit" class="btn btn-primary" name="loginButton" value="Login">
            <input type="submit" class="btn btn-primary" name="indexPage" value="Go to Index Page">
            <?php if (isset($_SESSION['login-error'])): ?>
                <div class="error-message"><?= $_SESSION['login-error']; ?></div>
                <?php unset($_SESSION['login-error']); ?>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>

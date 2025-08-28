<?php
require_once 'DBUtils.php';
require_once 'utils.php'; // for generateConnectToken()
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        try {
            $db = new DBConnection();

            // Check if user already exists
            $existingUser = $db->selectUserByUsername($username);
            if (!empty($existingUser)) {
                $errors[] = "Username already taken.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into database
                $success = $db->insertUser($username, $email, $hashed_password);
                if ($success) {

                    $secretKey = generateSecretKey(); // function from utils.php
                    $db->updateUserSecretKey($username, $secretKey);
                    // Generate connect token for Telegram linking
                    $token = generateConnectToken();
                    $expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                    $db->updateConnectToken($username, $token, $expires);

                    // Store token in session to display instructions
                    $_SESSION['connect_token'] = $token;
                    $_SESSION['new_user'] = $username;

                    // Redirect to Telegram linking instructions page
                    header("Location: link_telegram_instructions.php");
                    exit;
                } else {
                    $errors[] = "Error inserting user.";
                }
            }
        } catch (Exception $e) {
            $errors[] = "System error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
            margin-bottom: 15px; /* Added margin between buttons */
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
        <h2>Sign Up</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert">
                <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="signup.php">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>

            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>

            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>

            <label for="confirm_password" class="form-label">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>

            <!-- Added margin-bottom to "Sign Up" button -->
            <button type="submit" class="btn btn-primary">Sign Up</button>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </form>

        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php" class="text-primary">Log In</a></p>
        </div>
    </div>
</body>
</html>

<?php
session_start();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Link Your Telegram</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);}
        h2 { text-align: center; }
        .btn { display: block; width: 100%; padding: 10px; background: #3498db; color: #fff; border: none; border-radius: 5px; font-size: 16px; margin-top: 20px; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .error { color: red; margin-bottom: 15px; }
        p { text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Link Your Telegram Account</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <p>
            To enable secure login, please check your email for instructions on how to connect your Telegram account.<br>
            If you did not receive the email, please check your spam folder or contact support.
        </p>
        <p>
            <strong>If you have successfully registered with our Telegram bot, please try to log in again to receive your OTP.</strong>
        </p>
        <form action="login.php" method="get">
            <button type="submit" class="btn">Back to Login</button>
        </form>
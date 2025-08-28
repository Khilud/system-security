<?php
session_start();

// Retrieve any error message and the connect token
$error = $_SESSION['error'] ?? null;
$connect_token = $_SESSION['connect_token'] ?? null;



// unsetting the error not token
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Link Your Telegram</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4; 
        }
        .container { 
            max-width: 400px; 
            margin: 60px auto; 
            background: #fff; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2, p { 
            text-align: center; 
        }
        .btn { 
            display: block; 
            width: 100%; 
            padding: 10px; 
            background: #3498db; 
            color: #fff; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            margin-top: 20px; 
            cursor: pointer; 
        }
        .btn:hover { 
            background: #2980b9; 
        }
        .error { 
            color: red; 
            margin-bottom: 15px; 
        }
        .token-box {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
            font-family: monospace;
            font-size: 16px;
            word-break: break-all;
        }
        ol {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Link Your Telegram Account</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($connect_token): ?>
            <p>To enable secure login, please follow these steps:</p>
            <ol>
                <li>Open our Telegram bot: <a href="https://t.me/KhiludAuthbot" target="_blank">@KhiludAuthbot</a></li>
                <li>Send the following command to the bot:</li>
            </ol>
            <div class="token-box">
                /connect <?php echo htmlspecialchars($connect_token); ?>
            </div>
            <p><strong>⚠️ This token will expire in 10 minutes.</strong></p>
            <p>After linking, you can <strong>log in with your username and OTP</strong>.</p>
        <?php else: ?>
            <p>No connection token found. Please try signing up again.</p>
        <?php endif; ?>

        <form action="login.php" method="get">
            <button type="submit" class="btn">Back to Login</button>
        </form>
    </div>
</body>
</html>

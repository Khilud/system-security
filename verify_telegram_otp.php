<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];
    if (
        isset($_SESSION['otp'], $_SESSION['otp_expiry']) &&
        time() < $_SESSION['otp_expiry'] &&
        $entered_otp == $_SESSION['otp']
    ) {
        $_SESSION['mfa_verified'] = true;
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);
        header('Location: home.php');
        exit;
    } else {
        $error = "Invalid or expired OTP.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
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
        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #842029;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTP Verification</h2>
        <?php if (isset($error)): ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="otp" class="form-label">Enter OTP from Telegram:</label>
            <input type="text" id="otp" name="otp" class="form-control" placeholder="Enter OTP" required>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>
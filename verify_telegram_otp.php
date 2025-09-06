<?php
session_start();
require_once 'DBUtils.php';
require_once 'utils.php';

// Set up AES master key for encryption/decryption
$masterKey = getenv('AES_MASTER_KEY');
if (!$masterKey) {
    die("AES master key not set in environment.");
}
$masterKey = hex2bin(trim($masterKey));
if ($masterKey === false || strlen($masterKey) !== 32) {
    die("Invalid AES master key format.");
}
$GLOBALS['AES_MASTER_KEY'] = $masterKey;

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new DBConnection();
    $user = $db->selectUserByUsername($_SESSION['username']);

    if (!$user || isAccountLocked($user)) {
        $error = "❌ Account is locked. Please contact support.";
    } 
    elseif (empty($user['secret_key'])) {
        $error = "❌ Telegram not properly linked. Please set up MFA first.";
    }
    else {
        // Use the secret key directly (already decrypted by selectUserByUsername)
        $secretKey = $user['secret_key'];
        $enteredOtp = trim($_POST['otp'] ?? '');

        if (verifyOTP($secretKey, $enteredOtp)) {
            // Reset failed attempts on success
            $resetResult = $db->resetFailedAttempts($_SESSION['username']);
            error_log("Reset failed attempts result: " . ($resetResult ? 'success' : 'failed'));
            
            // Update last successful login
            $loginUpdateResult = $db->updateLastLogin($_SESSION['username'], $_SERVER['REMOTE_ADDR']);
            error_log("Update last login result: " . ($loginUpdateResult ? 'success' : 'failed'));
            error_log("Username: " . $_SESSION['username'] . ", IP: " . $_SERVER['REMOTE_ADDR']);
            
            // Set MFA session flag
            $_SESSION['mfa_verified'] = true;
            unset($_SESSION['otp_expiry']);

            // Optional: unset connect token if it's no longer needed
            unset($_SESSION['connect_token']);

            header('Location: home.php');
            exit;
        } else {
            // Increment failed attempts
            $db->incrementFailedAttempts($_SESSION['username']);
            $failedAttempts = $db->getFailedAttempts($_SESSION['username']);

            if ($failedAttempts >= 5) {
                // Lock account for 10 minutes
                $lockUntil = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                $db->lockAccount($_SESSION['username'], $lockUntil);
                $error = "❌ Too many failed attempts. Account locked for 10 minutes.";
            } else {
                $error = "❌ Invalid OTP. Attempts remaining: " . (5 - $failedAttempts);
            }
        }
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
</body>
</html>
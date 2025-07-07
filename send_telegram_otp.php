<?php
session_start();
require_once 'DBUtils.php'; 

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

$db = new DBConnection();
$userArr = $db->selectUserByUsername($username);
$user = $userArr[0] ?? null;

if (!$user || empty($user['telegram_chat_id'])) {
    // Optionally, set a session message to show on the next page
    $_SESSION['error'] = "Telegram not linked. Please check your email for instructions to connect your Telegram account.";
    header('Location: link_telegram_instructions.php');
    exit;
}

$chat_id = $user['telegram_chat_id'];

// Generate OTP
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_expiry'] = time() + 300; // 5 minutes

// Send OTP via Telegram Bot API
$bot_token = '7619090164:AAGBSATpACMOaEUGGd7yG_DYbf0pB4ICvrA';
$message = "Your Hotel Booking OTP is: $otp";
file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message));

// Redirect to OTP verification page
header('Location: verify_telegram_otp.php');
exit;
?>
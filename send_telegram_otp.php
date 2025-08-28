<?php
session_start();
require_once 'DBUtils.php'; 
require_once 'utils.php';

// Add error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];


$db = new DBConnection();
$userArr = $db->selectUserByUsername($username);
$user = $userArr[0] ?? null;

if (!$user || empty($user['telegram_chat_id'])) {
    $_SESSION['error'] = "Telegram not linked. Please link your telegram account.";
    header('Location: link_telegram_instructions.php');
    exit;
}

$chat_id = $user['telegram_chat_id'];

// Fetch user's secret key from DB
$secretKey = $user['secret_key'] ?? null;


if (!$secretKey) {
    // Secret key not found, user must link Telegram first
    $_SESSION['error'] = "Telegram not linked yet. Please link your account first.";
    header('Location: link_telegram_instructions.php'); // redirect to token page
    exit;
}


// Generate OTP using secret key
$otp = generateOTP($secretKey);

// Send OTP via Telegram Bot API
$bot_token = '7619090164:AAGBSATpACMOaEUGGd7yG_DYbf0pB4ICvrA';
$message = "Your Hotel Booking OTP is: $otp";
file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message));

// Redirect to OTP verification page
header('Location: verify_telegram_otp.php');
exit;
?>
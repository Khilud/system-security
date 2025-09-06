<?php
session_start();
require_once 'DBUtils.php'; 
require_once 'utils.php';

// error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set up AES master key for encryption/decryption
$AES_MASTER_KEY = getenv('AES_MASTER_KEY');
if (!$AES_MASTER_KEY) {
    die("AES master key not set in environment.");
}
$AES_MASTER_KEY = hex2bin(trim($AES_MASTER_KEY));
if ($AES_MASTER_KEY === false || strlen($AES_MASTER_KEY) !== 32) {
    die("Invalid AES master key format.");
}
$GLOBALS['AES_MASTER_KEY'] = $AES_MASTER_KEY;

// Checking if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];


$db = new DBConnection();
$userArr = $db->selectUserByUsername($username);
$user = $userArr ?? null;

// Check if Telegram is linked
if (!$user || empty($user['telegram_chat_id'])) {
    $_SESSION['error'] = "Telegram not linked. Please link your telegram account.";
    header('Location: link_telegram_instructions.php');
    exit;
}
$chat_id = $user['telegram_chat_id']; // Already decrypted by selectUserByUsername
error_log("Using chat_id: $chat_id"); // Log the chat_id

// Fetch user's secret key (already decrypted)
$secretKey = $user['secret_key'] ?? null;
if (!$secretKey) {
    $_SESSION['error'] = "Telegram not linked yet. Please link your account first.";
    header('Location: link_telegram_instructions.php');
    exit;
}

// Generate OTP using decrypted secret key
$otp = generateOTP($secretKey);

// Send OTP via Telegram Bot API
$bot_token = getenv('BOT_TOKEN');
$message = "Your Hotel Booking OTP is: $otp";
$url = "https://api.telegram.org/bot$bot_token/sendMessage";

// Prepare data
$data = [
    'chat_id' => $chat_id,
    'text'    => $message
];

// Log the request
error_log("Sending Telegram message to chat_id=$chat_id with text=$message");

// Use cURL instead of file_get_contents
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if ($response === false) {
    error_log("cURL error: " . curl_error($ch));
}
curl_close($ch);

// Parse response
$responseData = json_decode($response, true);
error_log("Telegram API response: " . $response);

if (!$responseData || !$responseData['ok']) {
    $_SESSION['error'] = "Failed to send OTP via Telegram. Please try again later.";
    header('Location: login.php');
    exit;
}

// Redirect to OTP verification page
header('Location: verify_telegram_otp.php');
exit;
?>

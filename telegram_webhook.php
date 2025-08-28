<?php
// telegram_webhook.php
file_put_contents('webhook.log', date('c')." ".file_get_contents("php://input")."\n", FILE_APPEND);


require_once 'DBUtils.php';
require_once 'utils.php';

// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add logging
function logWebhook($message) {
    file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
}

// Bot configuration
const BOT_TOKEN = '7619090164:AAGBSATpACMOaEUGGd7yG_DYbf0pB4ICvrA';

// Get raw POST data sent by Telegram
$content = file_get_contents("php://input");
$update = json_decode($content, true);

logWebhook("Received update: " . json_encode($update));

if (!isset($update['message'])) {
    logWebhook("No message received");
    exit("No message received");
}

// Extract data
$message = $update['message'];
$chat_id = $message['chat']['id'];
$text = trim($message['text']);

if (strpos($text, "/connect ") === 0) {
    $token = trim(substr($text, 9));
    logWebhook("Processing connect command with token: $token");

    $db = new DBConnection();
    $user = $db->getUserByConnectToken($token);

    if ($user) {
        try {
            // Generate a new secret key for OTP
            $secretKey = generateSecretKey();
            
            // Update user with Telegram chat_id and secret key
            $db->updateTelegramChatId($token, $chat_id);
            $db->updateUserSecretKey($user['username'], $secretKey);

            $msg = "✅ Telegram linked successfully! You can now log in with OTP.";
            logWebhook("Successfully linked user {$user['username']} with chat_id $chat_id");
        } catch (Exception $e) {
            $msg = "❌ Error linking Telegram. Please try again.";
            logWebhook("Error linking user: " . $e->getMessage());
        }
    } else {
        $msg = "❌ Invalid or expired token. Please try again.";
        logWebhook("Invalid token attempt: $token");
    }

    // Send response to user
    $response = file_get_contents("https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage?chat_id=$chat_id&text=" . urlencode($msg));
    logWebhook("Telegram API response: " . $response);
}
?>


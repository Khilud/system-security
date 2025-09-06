<?php
// telegram_webhook.php

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

// Add cURL function for sending Telegram messages
function sendTelegramMessage($chat_id, $msg, $bot_token) {
    $url = "https://api.telegram.org/bot$bot_token/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $msg
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if ($response === false) {
        logWebhook('cURL error: ' . curl_error($ch));
        curl_close($ch);
        return false;
    }
    curl_close($ch);

    $apiResponse = json_decode($response, true);
    if (!$apiResponse['ok']) {
        logWebhook("Telegram API error: " . $apiResponse['description']);
        // If error is 403 or 404, return a special message
        if (isset($apiResponse['error_code']) && in_array($apiResponse['error_code'], [403, 404])) {
            return [
                'ok' => false,
                'error_code' => $apiResponse['error_code'],
                'description' => $apiResponse['description'],
            ];
        }
    }
    return $apiResponse;
}

// Read AES master key from environment
$AES_MASTER_KEY = getenv('AES_MASTER_KEY');
if (!$AES_MASTER_KEY) {
    logWebhook("AES master key not set or file missing.");
    exit("AES master key not set.");
}
$AES_MASTER_KEY = hex2bin(trim($AES_MASTER_KEY));
if ($AES_MASTER_KEY === false || strlen($AES_MASTER_KEY) !== 32) {
    logWebhook("Invalid AES master key format. Must be 64 hex chars.");
    exit("Invalid AES master key format.");
}

// Set the global variable so encryptData/decryptData functions can access it
$GLOBALS['AES_MASTER_KEY'] = $AES_MASTER_KEY;
// Bot configuration - get from environment
$BOT_TOKEN = getenv('BOT_TOKEN');


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

logWebhook("Received message: " . json_encode($message) . " from $chat_id");

// Always handle /start command first
if ($text === '/start') {
    $welcomeMsg = "👋 Welcome! Your bot is now active. You can now link your account and receive messages. If you have already linked, you will receive OTPs and notifications here.";
    $response = sendTelegramMessage($chat_id, $welcomeMsg, $BOT_TOKEN);
    if (is_array($response) && isset($response['error_code'])) {
        logWebhook("Telegram API error on /start: " . $response['description']);
    }
    logWebhook("Telegram API response on /start: " . json_encode($response));
    logWebhook("Sent welcome message to chat_id $chat_id on /start");
    // Do not process other commands if /start is received
    return;
}

if (strpos($text, "/connect ") === 0) {
    $token = trim(substr($text, 9));
    logWebhook("Processing connect command with token: $token");

    $db = new DBConnection();
    $user = $db->getUserByConnectToken($token);

    if ($user) {
        try {
            // Generate a new secret key for OTP
            $secretKey = generateSecretKey();
            logWebhook("Generated secret key: " . $secretKey);
            
            // Encrypt before saving
            $encryptedSecretKey = encryptData($secretKey);
            logWebhook("Encrypted secret key length: " . strlen($encryptedSecretKey));

            // Encrypt chat_id before saving
            $encryptedChatId = encryptData((string)$chat_id);
            logWebhook("Encrypted chat_id length: " . strlen($encryptedChatId));

            // Update user with encrypted Telegram chat_id and secret key in a single transaction
            $pdo = $db->getPDO();
            $pdo->beginTransaction();
            logWebhook("Started transaction");
            
            $stmt = $pdo->prepare("UPDATE users SET telegram_chat_id = ?, secret_key = ?, connect_token = NULL, token_expires_at = NULL WHERE connect_token = ?");
            $success = $stmt->execute([$encryptedChatId, $encryptedSecretKey, $token]);
            logWebhook("Query executed. Success: " . ($success ? 'true' : 'false') . ", Row count: " . $stmt->rowCount());
            
            if ($success && $stmt->rowCount() > 0) {
                $pdo->commit();
                logWebhook("Transaction committed successfully");
                $msg = "✅ Telegram linked successfully! You can now log in with OTP.";
                logWebhook("Successfully linked user {$user['username']} with chat_id $chat_id");
            } else {
                $pdo->rollback();
                logWebhook("Transaction rolled back");
                $msg = "❌ Error linking Telegram. Please try again.";
                logWebhook("Failed to update user record for token: $token");
            }
        } catch (Exception $e) {
            $pdo->rollback();
            $msg = "❌ Error linking Telegram. Please try again.";
            logWebhook("Error linking user: " . $e->getMessage());
        }
    } else {
        $msg = "❌ Invalid or expired token. Please try again.";
        logWebhook("Invalid token attempt: $token");
    }

    // Send response to user
    try {
    $response = sendTelegramMessage($chat_id, $msg, $BOT_TOKEN);
        if (is_array($response) && isset($response['error_code']) && in_array($response['error_code'], [403, 404])) {
            // Telegram message delivery failed, inform user in log and provide instructions
            logWebhook("Telegram message delivery failed: " . $response['description']);
            // Optionally, you can send a fallback message via web/app UI or email
            // Add instructions for user to start the bot
            // Save a flag or message in DB if needed
        }
        logWebhook("Telegram API response: " . json_encode($response));
    } catch (Exception $e) {
        logWebhook("Exception when sending Telegram message: " . $e->getMessage());
    }
}

// Add user-facing instructions (for web/app integration)
// If you display a message after linking, add:
// echo "<div style='color: red; font-weight: bold;'>If you do not receive a message from the bot, please make sure you have started the bot on Telegram by searching for your bot username and clicking 'Start'.</div>";
?>


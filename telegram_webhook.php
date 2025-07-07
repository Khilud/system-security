<?php
// telegram_webhook.php
file_put_contents('webhook.log', date('c')." ".file_get_contents("php://input")."\n", FILE_APPEND);


require_once 'DBUtils.php'; 

// Get raw POST data sent by Telegram
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!isset($update['message'])) {
    exit("No message received");
}

// Extract data
$message = $update['message'];
$chat_id = $message['chat']['id'];
$text = trim($message['text']);

if (strpos($text, "/connect ") === 0) {
    $email = trim(substr($text, 9));

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $db = new DBConnection();

        // select user by email
        $userArr = $db->selectUserByEmail($email); 
        $user = $userArr[0] ?? null;

        if ($user) {
            $pdo = $db->getPDO();
            $update_stmt = $pdo->prepare("UPDATE users SET telegram_chat_id = ? WHERE email = ?");
            $update_stmt->execute([$chat_id, $email]);

            $bot_token = '7619090164:AAGBSATpACMOaEUGGd7yG_DYbf0pB4ICvrA';
            $msg = "Telegram linked successfully! You can now log in with OTP.";
            file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($msg));
        } else {
            $bot_token = '7619090164:AAGBSATpACMOaEUGGd7yG_DYbf0pB4ICvrA';
            $msg = "No user found with that email. Please check and try again.";
            file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($msg));
        }
    } else {
        $bot_token = '7619090164:AAGBSATpACMOaEUGGd7yG_DYbf0pB4ICvrA';
        $msg = "❗ Please send a valid email. Example:\n/connect your_email@example.com";
        file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($msg));
    }
}
?>


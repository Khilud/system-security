<?php

// Load AES master key from environment Docker secret
$AES_MASTER_KEY = getenv('AES_MASTER_KEY');

if (!$AES_MASTER_KEY) {
    throw new Exception("AES master key not set in environment");
}

//  32 bytes (256-bit)
$AES_MASTER_KEY = hex2bin(trim($AES_MASTER_KEY));
if ($AES_MASTER_KEY === false || strlen($AES_MASTER_KEY) !== 32) {
    throw new Exception("Invalid AES master key format. Must be 64 hex chars.");
}
// token
function generateConnectToken(): string {
    return bin2hex(random_bytes(16)); // 32 hex chars
}

// per-user secret key
function generateSecretKey(): string {
    return bin2hex(random_bytes(32)); // 64 hex chars (256-bit)
}

// TOTP using the secret key
function generateOTP(string $secretKey): string {
    $key = hex2bin($secretKey);
    $timeSlice = floor(time() / 30); // 30-second window
    $timeData = pack('N*', 0) . pack('N*', $timeSlice); // 64-bit time

    $hash = hash_hmac('sha1', $timeData, $key, true);
    $offset = ord(substr($hash, -1)) & 0x0F;
    $truncatedHash = substr($hash, $offset, 4);
    $otp = unpack('N', $truncatedHash)[1] & 0x7FFFFFFF;

    return str_pad((string)($otp % 1000000), 6, '0', STR_PAD_LEFT); // 6-digit OTP
}


function verifyOTP(string $secretKey, string $userOTP): bool {
    if ($userOTP === generateOTP($secretKey)) {
        return true;
    }

    // Previous time slice
    $prevTime = time() - 30;
    $timeSlice = floor($prevTime / 30);
    $key = hex2bin($secretKey);
    $timeData = pack('N*', 0) . pack('N*', $timeSlice);
    $hash = hash_hmac('sha1', $timeData, $key, true);
    $offset = ord(substr($hash, -1)) & 0x0F;
    $truncatedHash = substr($hash, $offset, 4);
    $otp = unpack('N', $truncatedHash)[1] & 0x7FFFFFFF;
    $prevOTP = str_pad((string)($otp % 1000000), 6, '0', STR_PAD_LEFT);

    return $userOTP === $prevOTP;
}

// Check if an account is locked
function isAccountLocked(array $user): bool {
    if (!isset($user['locked_until'])) {
        return false;
    }

    $lockedUntil = strtotime($user['locked_until']);
    return $lockedUntil && $lockedUntil > time();
}

// Encrypt sensitive data 
function encryptData(string $data): string {
    global $AES_MASTER_KEY;
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $AES_MASTER_KEY, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

// Decrypt sensitive data from DB
function decryptData(string $encryptedData): string {
    global $AES_MASTER_KEY;
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $AES_MASTER_KEY, OPENSSL_RAW_DATA, $iv);
}


// Format error messages for display
function formatError(string $message): string {
    return "❌ " . htmlspecialchars($message);
}

// Format success messages
function formatSuccess(string $message): string {
    return "✅ " . htmlspecialchars($message);
}

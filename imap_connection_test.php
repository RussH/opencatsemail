<?php
// Basic IMAP connection test using OAuth2 tokens stored in .env

require_once __DIR__ . '/config.php';

function getAccessToken($clientId, $clientSecret, $refreshToken, $tenant)
{
    $url = "https://login.microsoftonline.com/{$tenant}/oauth2/v2.0/token";
    $postData = http_build_query([
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'scope' => 'https://outlook.office365.com/.default',
        'refresh_token' => $refreshToken,
        'grant_type' => 'refresh_token',
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
        CURLOPT_POSTFIELDS => $postData,
    ]);
    $response = curl_exec($ch);
    if ($response === false) {
        fwrite(STDERR, "Failed to get token: " . curl_error($ch) . PHP_EOL);
        curl_close($ch);
        return null;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode !== 200) {
        fwrite(STDERR, "Token request failed with HTTP $httpCode: $response" . PHP_EOL);
        return null;
    }
    $data = json_decode($response, true);
    if (!isset($data['access_token'])) {
        fwrite(STDERR, "Access token missing in response: $response" . PHP_EOL);
        return null;
    }
    return $data['access_token'];
}

// Acquire access token using refresh token
$token = getAccessToken($oauth_client_id, $oauth_client_secret, $oauth_refresh_token, $oauth_tenant);
if (!$token) {
    exit(1);
}

$mbox = @imap_open("{" . $imap_host . $imap_flags . "}INBOX", $imap_user, $token);
if ($mbox) {
    echo "IMAP connection successful\n";
    imap_close($mbox);
    exit(0);
}
$err = imap_last_error();
if ($err) {
    fwrite(STDERR, "IMAP connection failed: $err\n");
} else {
    fwrite(STDERR, "IMAP connection failed for unknown reasons\n");
}
exit(1);
?>

<?php

// Load environment variables from .env when present
if (file_exists(__DIR__ . '/.env')) {
    foreach (file(__DIR__ . '/.env') as $line) {
        if (!preg_match('/^\s*([^#=]+)\s*=\s*(.*)\s*$/', $line, $m)) {
            continue;
        }
        $name  = $m[1];
        $value = $m[2];
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

$mysql_host = isset($_ENV['MYSQL_HOST']) ? $_ENV['MYSQL_HOST'] : 'localhost'; // MySQL host address
$mysql_user = isset($_ENV['MYSQL_USER']) ? $_ENV['MYSQL_USER'] : ''; // MySQL username
$mysql_pass = isset($_ENV['MYSQL_PASS']) ? $_ENV['MYSQL_PASS'] : ''; // MySQL password
$mysql_db   = isset($_ENV['MYSQL_DB']) ? $_ENV['MYSQL_DB'] : ''; // MySQL database name

$imap_host  = isset($_ENV['IMAP_HOST']) ? $_ENV['IMAP_HOST'] : 'outlook.office365.com:993'; // IMAP host address

if (strpos($imap_host, ':') === false) {
    $imap_host .= ':993';
}

$imap_flags = isset($_ENV['IMAP_FLAGS']) ? $_ENV['IMAP_FLAGS'] : '/imap/ssl/auth=xoauth2'; // IMAP Flags for OAuth2
$imap_user  = ""; // IMAP username

// OAuth2 settings for Microsoft
$oauth_client_id     = isset($_ENV['OAUTH_CLIENT_ID']) ? $_ENV['OAUTH_CLIENT_ID'] : '';
$oauth_client_secret = isset($_ENV['OAUTH_CLIENT_SECRET']) ? $_ENV['OAUTH_CLIENT_SECRET'] : '';
$oauth_tenant        = isset($_ENV['OAUTH_TENANT_ID']) ? $_ENV['OAUTH_TENANT_ID'] : 'common'; // or your tenant ID
$oauth_refresh_token = isset($_ENV['OAUTH_REFRESH_TOKEN']) ? $_ENV['OAUTH_REFRESH_TOKEN'] : ''; // Refresh token used to obtain access tokens

$file_store = "files"; // Folder where file attachments are saved
$grab_type  = "fetch"; // Type of mail grab - "pipe" or "fetch"

<?php

require_once __DIR__ . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->load();

$mysql_host = $_ENV['MYSQL_HOST']; // MySQL host address
$mysql_user = $_ENV['MYSQL_USER']; // MySQL username
$mysql_pass = $_ENV['MYSQL_PASS']; // MySQL password
$mysql_db   = $_ENV['MYSQL_DB'];   // MySQL database name

$imap_host  = $_ENV['IMAP_HOST'] . ':993'; // IMAP host address
$imap_flags = "/imap/ssl/auth=xoauth2";      // IMAP Flags for OAuth2
$imap_user  = $_ENV['IMAP_USER'];            // IMAP username

// OAuth2 settings for Microsoft
$oauth_client_id     = $_ENV['OAUTH_CLIENT_ID'];
$oauth_client_secret = $_ENV['OAUTH_CLIENT_SECRET'];
$oauth_tenant        = $_ENV['OAUTH_TENANT_ID']; // or your tenant ID
$oauth_refresh_token = $_ENV['OAUTH_REFRESH_TOKEN']; // Refresh token used to obtain access tokens

$file_store = $_ENV['FILE_STORE'] ?? 'files';    // Folder where file attachments are saved
$grab_type  = $_ENV['GRAB_TYPE'] ?? 'fetch';     // Type of mail grab - "pipe" or "fetch"

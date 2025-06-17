<?php

$mysql_host = ""; // MySQL host address
$mysql_user = ""; // MySQL username
$mysql_pass = ""; // MySQL password
$mysql_db   = ""; // MySQL database name

$imap_host  = isset($_ENV['IMAP_HOST']) ? $_ENV['IMAP_HOST'] : 'outlook.office365.com:993'; // IMAP host address

if (strpos($imap_host, ':') === false) {
    $imap_host .= ':993';
}

$imap_flags = isset($_ENV['IMAP_FLAGS']) ? $_ENV['IMAP_FLAGS'] : '/imap/ssl/auth=xoauth2'; // IMAP Flags for OAuth2
$imap_user  = ""; // IMAP username

// OAuth2 settings for Microsoft
$oauth_client_id     = "";
$oauth_client_secret = "";
$oauth_tenant        = "common"; // or your tenant ID
$oauth_refresh_token = ""; // Refresh token used to obtain access tokens

$file_store = "files"; // Folder where file attachments are saved
$grab_type  = "fetch"; // Type of mail grab - "pipe" or "fetch"

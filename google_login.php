<?php
// Read credentials from .env file (no Composer needed)
$env = parse_ini_file(__DIR__ . "/.env");

$client_id    = $env['GOOGLE_CLIENT_ID'];
$redirect_uri = $env['GOOGLE_REDIRECT_URI'];

$params = array(
    'client_id'     => $client_id,
    'redirect_uri'  => $redirect_uri,
    'response_type' => 'code',
    'scope'         => 'email profile',
    'access_type'   => 'online'
);

$auth_url = "https://accounts.google.com/o/oauth2/auth?" . http_build_query($params);

header("Location: " . $auth_url);
exit;
?>

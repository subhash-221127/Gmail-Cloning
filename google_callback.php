<?php
session_start();

// Read credentials from .env file (no Composer needed)
$env = parse_ini_file(__DIR__ . "/.env");

$client_id     = $env['GOOGLE_CLIENT_ID'];
$client_secret = $env['GOOGLE_CLIENT_SECRET'];
$redirect_uri  = $env['GOOGLE_REDIRECT_URI'];

if (isset($_GET['code'])) {

    // Step 1: Exchange code for access token
    $token_data = array(
        'code'          => $_GET['code'],
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri'  => $redirect_uri,
        'grant_type'    => 'authorization_code'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://oauth2.googleapis.com/token");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $token_response = curl_exec($ch);
    curl_close($ch);

    $token = json_decode($token_response, true);

    if (isset($token['error'])) {
        die("Google error: " . $token['error'] . " <a href='login.html'>Go back</a>");
    }

    // Step 2: Get user info
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v2/userinfo");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $token['access_token']));
    $user_response = curl_exec($ch);
    curl_close($ch);

    $user = json_decode($user_response, true);

    if (!isset($user['email'])) {
        die("Could not get user info. <a href='login.html'>Go back</a>");
    }

    // Step 3: Save session and redirect
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name']  = $user['name'];

    header("Location: index.html");
    exit;

} else {
    header("Location: login.html");
    exit;
}
?>

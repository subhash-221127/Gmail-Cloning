<?php
session_start();

// Load credentials from .env file
$env = parse_ini_file(__DIR__ . "/.env");
$client_id     = $env['GOOGLE_CLIENT_ID'];
$client_secret = $env['GOOGLE_CLIENT_SECRET'];
$redirect_uri  = $env['GOOGLE_REDIRECT_URI'];

if (isset($_GET['code'])) {

    $token_url  = "https://oauth2.googleapis.com/token";
    $token_data = array(
        'code'          => $_GET['code'],
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri'  => $redirect_uri,
        'grant_type'    => 'authorization_code'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $token_response = curl_exec($ch);
    curl_close($ch);

    $token = json_decode($token_response, true);

    if (isset($token['error'])) {
        die("Google login error: " . $token['error'] . ". <a href='login.html'>Go back</a>");
    }

    $access_token = $token['access_token'];

    $user_info_url = "https://www.googleapis.com/oauth2/v2/userinfo";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $user_info_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $access_token));
    $user_response = curl_exec($ch);
    curl_close($ch);

    $user = json_decode($user_response, true);

    if (!isset($user['email'])) {
        die("Could not get user info from Google. <a href='login.html'>Go back</a>");
    }

    // Save user details to MongoDB
    require_once __DIR__ . '/vendor/autoload.php';

    try {
        $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
        $db          = $mongoClient->gmail_clone;
        $users       = $db->users;

        // Check if user already exists
        $existingUser = $users->findOne(['email' => $user['email']]);

        if (!$existingUser) {
            // Insert new user
            $users->insertOne([
                'name'       => $user['name'],
                'email'      => $user['email'],
                'picture'    => $user['picture'] ?? '',
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);
        } else {
            // Update last login time
            $users->updateOne(
                ['email' => $user['email']],
                ['$set' => ['last_login' => new MongoDB\BSON\UTCDateTime()]]
            );
        }
    } catch (Exception $e) {
        // Log error but don't block login
        error_log("MongoDB error: " . $e->getMessage());
    }

    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name']  = $user['name'];

    header("Location: index.html");
    exit;

} else {
    header("Location: login.html");
    exit;
}
?>

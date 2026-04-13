<?php
// MongoDB connection using MongoDB\Client (requires Composer)
// Run once in your project folder: composer require mongodb/mongodb

require_once __DIR__ . '/vendor/autoload.php';

$env = parse_ini_file(__DIR__ . "/.env");

$mongo_uri = $env['MONGO_URI'];  // mongodb://localhost:27017
$mongo_db  = $env['MONGO_DB'];   // gmail_clone

$client = new MongoDB\Client($mongo_uri);
$db     = $client->$mongo_db;

// Collections
$users_collection = $db->users;
?>

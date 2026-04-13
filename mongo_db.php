<?php
// MongoDB connection using the PHP MongoDB extension (no Composer needed)
// Requirements: Install MongoDB PHP extension for XAMPP
// Download from: https://pecl.php.net/package/mongodb
// Then add "extension=mongodb" in C:\xampp\php\php.ini

$env = parse_ini_file(__DIR__ . "/.env");

$mongo_uri = $env['MONGO_URI'];   // mongodb://localhost:27017
$mongo_db  = $env['MONGO_DB'];    // gmail_clone

try {
    $manager = new MongoDB\Driver\Manager($mongo_uri);
} catch (Exception $e) {
    die("MongoDB Connection Failed: " . $e->getMessage());
}

function mongo_insert($manager, $db, $collection, $data) {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->insert($data);
    $manager->executeBulkWrite("$db.$collection", $bulk);
}

function mongo_find_one($manager, $db, $collection, $filter) {
    $query  = new MongoDB\Driver\Query($filter, ['limit' => 1]);
    $cursor = $manager->executeQuery("$db.$collection", $query);
    $result = $cursor->toArray();
    return count($result) > 0 ? (array)$result[0] : null;
}
?>

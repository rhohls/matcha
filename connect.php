<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './config/database.php';

// CONNECTING
try {
    $pdo = new PDO("mysql:host=$DB_DSN", $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query("use `$DB_NAME`");
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    die();
}

require_once 'twig.php';
?>
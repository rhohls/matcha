<?php

require_once 'database.php';

// CONNECTING
try {
    $pdo = new PDO("mysql:host=$DB_DSN", $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully\n" . PHP_EOL;
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    die();
    }



try{
    $pdo->query("DROP DATABASE IF EXISTS `$DB_NAME`");
    echo "Deleted database" . PHP_EOL;

}
catch(PDOException $e)
{
    echo "Failed to delete database: " . $e->getMessage() . PHP_EOL;
    die();
}

try{
    $image_files = array_diff(scandir("../imgs"), array('..', '.'));

    foreach ($image_files as $image){
        $image_loc = "../imgs/".$image;
        unlink($image_loc);
        }
    echo "Deleted all images";
    }

catch(PDOException $e)
    {
        echo "Failed to delete images";
        die();
    }
    


?>
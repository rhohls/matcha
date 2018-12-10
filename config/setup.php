<?php
require_once '../generic_functions.php';
require_once 'database.php';

// CONNECTING
try {
    $pdo = new PDO("mysql:host=$DB_DSN", $DB_USER, $DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully\n" . PHP_EOL;
    }
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    die();
}


// CREATE DB
try{
    $pdo->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME`");
    $pdo->query("use `$DB_NAME`");


    // User table
    $user_table = "CREATE TABLE IF NOT EXISTS `users`
    (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        user_name VARCHAR(20) NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        first_name VARCHAR(32) NOT NULL,
        last_name VARCHAR(32) NOT NULL,
        confirmed INT DEFAULT 0,
        admin INT DEFAULT 0,
        active INT DEFAULT 1,
        verification VARCHAR(32),
        num_notifications INT DEFAULT 0,
        profile_img_loc VARCHAR(255) NOT NULL DEFAULT './page_imgs/blank_profile_picture.png',
        bio TEXT,
        sex_pref VARCHAR(12) NOT NULL DEFAULT 'Bisexual',
        gender VARCHAR(8) NOT NULL DEFAULT 'none',
        last_online DATE DEFAULT '1990-01-01',
        birthdate DATE DEFAULT '1990-01-01',
        images VARCHAR(255),
        latitude FLOAT,
        longitude FLOAT

    );";
    $pdo->query($user_table);
    // adding admin
    if (!userExist($pdo, 'admin')){
        $pw = hashPW('root');
        $query = 'INSERT INTO `users` (user_name, password, email, first_name, last_name, confirmed, admin, active)
        VALUES ("admin", "'.$pw.'", "none", "none", "none", 1, 1, 1)';
        $pdo->query($query);
    }
    



    // Image Table
    $img_table = "CREATE TABLE IF NOT EXISTS `images`
    (
        img_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        image_location VARCHAR(255) NOT NULL

    );";
    $pdo->query($img_table);

    // Views/likes Table
    $img_table = "CREATE TABLE IF NOT EXISTS `view_like`
    (
        user_from INT NOT NULL,
        user_to INT NOT NULL,
        liked INT DEFAULT 0,
        viewed INT DEFAULT 0,
        connected INT DEFAULT 0
    );";
    $pdo->query($img_table);

    // Message table
    $comment_table = "CREATE TABLE IF NOT EXISTS `messages`
    (
        from_id INT NOT NULL,
        to_id INT NOT NULL,
        comment TEXT NOT NULL,
        sent TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
    );";
    $pdo->query($comment_table);

    // Blocked
    $user_table = "CREATE TABLE IF NOT EXISTS `blocked`
    (
        user_id INT NOT NULL,
        blocked_id INT NOT NULL,
        fake INT NOT NULL DEFAULT 0
    );";
    $pdo->query($user_table);


    // notification
    $user_table = "CREATE TABLE IF NOT EXISTS `users`
    (
        user_id INT NOT NULL ,
        blocked_id INT
    );";
    $pdo->query($user_table);

    echo "Databse created successfully!" . PHP_EOL;
}


catch(PDOException $e)
{
    echo "Failed to initialize database: " . $e->getMessage() . PHP_EOL;
    die();
}

mkdir("../imgs");
mkdir("../stickers");

?>
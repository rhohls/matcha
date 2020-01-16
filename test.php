<?php
session_start();
require_once 'require.php';
require_once './functions/adjust_func.php';

require_once 'logged_in.php';

$error = 0;

$adjust_info = array();
$uid = $_SESSION['uid'];

$user_id = $uid;
$res_user_id = 41;

$query = "SELECT * FROM `user_tag` WHERE user_id=$user_id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$user_tags = $stmt->fetchAll();

$query = "SELECT * FROM `user_tag` WHERE user_id=$res_user_id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$match_tags = $stmt->fetchAll();

var_dump($user_tags);
echo("                .                  .             .           ");
var_dump($match_tags);

$count = 0;
foreach ($user_tags as $ut){
    foreach ($match_tags as $mt){
        if ($ut['tag_id'] == $mt['tag_id'])
            $count += 1;
    }
}

var_dump($count);
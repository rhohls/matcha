<?php
session_start();
require_once 'require.php';
require_once './functions/adjust_func.php';

require_once 'logged_in.php';

$error = 0;

$adjust_info = array();
$uid = $_SESSION['uid'];



$query = "SELECT * FROM `user_tag` WHERE user_id=$uid";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usertags = $stmt->fetchAll();
var_dump($usertags);

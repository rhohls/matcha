<?php
session_start();
require_once 'require.php';

require_once 'logged_in.php';

$uid = $_SESSION['uid'];

$query = "UPDATE `users` SET num_notifications=0 WHERE id=$uid";
$stmt = $pdo->prepare($query);
$stmt->execute();

require_once 'header.php'; //this updates the base array from require.php

echo $twig->render('notifications.html.twig', array(
	'base' => $base_array
));
?>
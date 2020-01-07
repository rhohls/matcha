<?php
session_start();
require_once 'connect.php';

require_once 'logged_in.php';

$uid = $_SESSION['uid'];

$query = "UPDATE `users` SET num_notifications=0 WHERE id=$uid";
$stmt = $pdo->prepare($query);
$stmt->execute();

require_once 'header.php'; //this updates the base array from require.php




// $query = "SELECT ID, FirstName, LastName FROM table GROUP BY(FirstName) LIMIT 10";

// Likes
$query =   "SELECT DISTINCT user_from, first_name, last_name FROM `view_like`
			JOIN users ON user_from=users.id
			WHERE user_to=:id AND liked=1
			LIMIT 10";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $uid]);
	
$likes = $stmt->fetchAll();

// Views
$query =   "SELECT DISTINCT user_from, first_name, last_name FROM `view_like`
			JOIN users ON user_from=users.id
			WHERE user_to=:id AND viewed=1
			LIMIT 10";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $uid]);
	
$views = $stmt->fetchAll();

// New messages
$query =   "SELECT DISTINCT from_id, first_name, last_name FROM `messages`
			JOIN users ON from_id=users.id
			WHERE to_id=:id
			LIMIT 10";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $uid]);
	
$messages = $stmt->fetchAll();

// connections
$query =   "SELECT DISTINCT user_from, first_name, last_name FROM `view_like`
			JOIN users ON user_from=users.id
			WHERE user_to=:id AND connected=1
			LIMIT 10";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $uid]);
	
$connects = $stmt->fetchAll();

// TODO remove blocked??
// $messages = removed_blocked($messages, $uid, $pdo);
// $views = removed_blocked($views, $uid, $pdo);
// $likes = removed_blocked($likes, $uid, $pdo);
// $connects = removed_blocked($connects, $uid, $pdo);


echo $twig->render('notifications.html.twig', array(
	'messages' => $messages,
	'views' => $views,
	'likes' => $likes,
	'connects' => $connects,
	'base' => $base_array
));
?>
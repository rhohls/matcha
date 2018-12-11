<?php 
session_start();
require_once 'connect.php';

function getNotifications($uid, $pdo){

	$query = "SELECT num_notifications FROM `users` WHERE id=$uid";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

	$res = $stmt->fetch();

	return ($res['num_notifications']);
}

function make_header_array($pdo){
	if (isset($_SESSION['uid'])){
		$uid = $_SESSION['uid'];
		$username = $_SESSION['user_name'];
		$loggedin = true;
		$notifications = getNotifications($uid, $pdo);
	}
	else{
		$uid = -1;
		$username = "Guest";
		$loggedin = false;
		$notifications = 0;
	}

	return (array(
		'uid' => $uid,
		'username' => $username,
		'loggedin' => $loggedin,
		'notifications' => $notifications
	));
}



$base_array = make_header_array($pdo);



?>

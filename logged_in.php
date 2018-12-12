<?php
function setLastOnline($uid, $pdo){
	$query = "UPDATE users set last_online=CURRENT_TIMESTAMP WHERE id=:id";

	$stmt = $pdo->prepare($query);
	$stmt->execute(['id' => $uid]);
}

session_start();
require_once 'connect.php';
require_once 'generic_functions.php';

if (!isset($_SESSION['uid'])){
	alert("You need to be logged in to do that", "login.php");
	die();
}
else{
	setLastOnline($_SESSION['uid'], $pdo);
}
?>
<?php

require_once "generic_functions.php";

function sendNotification($profile_id, $uid, $pdo){
	// TO-DO
}

function checkConnection($profile_id, $uid, $pdo){
	// for chat
}

// This functions works, dont read to much into the variable names
function profileBlocked($id_tocheck, $id_of_request, $pdo){
	$query = "SELECT * FROM `blocked` WHERE user_id=$id_of_request AND blocked_id=$id_tocheck";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

	$blocked_users = $stmt->fetchAll();

	if ($blocked_users)
		return(true);
	else
		return (false);
}

function removeLike($profile_id, $uid, $pdo){
	$query = "UPDATE `view_like` SET liked=0 WHERE user_to=$profile_id AND user_from=$uid ;";
	$stmt = $pdo->prepare($query);
	$stmt->execute();
}

function addLike($profile_id, $uid, $pdo){
	$query = "SELECT * FROM `view_like` WHERE user_to=$profile_id AND user_from=$uid AND liked=0";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

	$liked = $stmt->fetchAll();

	if (!$liked){
		$query = "INSERT INTO `view_like` ('user_to', 'user_from', 'liked') VALUES ($profile_id, $uid, 1)";
		$stmt = $pdo->prepare($query);
		$stmt->execute();

	}
	else{
		$query = "UPDATE `view_like` SET liked=1 WHERE user_to=$profile_id AND user_from=$uid ;";
		$stmt = $pdo->prepare($query);
		$stmt->execute();
	}

	sendNotification($profile_id, $uid, $pdo);
	checkConnection($profile_id, $uid, $pdo);
}


function addView($profile_id, $uid, $pdo){
	$query = "SELECT * FROM `view_like` WHERE user_to=$profile_id AND user_from=$uid AND viewed=1";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

	$viewed = $stmt->fetchAll();

	if (!$viewed){
		$query = "INSERT INTO `view_like` (`user_from`, `user_to`, `liked`, `viewed`) VALUES ('$uid', '$profile_id', '0', '1');";
		$stmt = $pdo->prepare($query);
		$stmt->execute();

		sendNotification($profile_id, $uid, $pdo);
	}
}

function addBlocked($profile_id, $uid, $pdo){
	if (!profileBlocked($profile_id, $uid, $pdo)){
		$query = "INSERT INTO `blocked` (`user_id`, `blocked_id`, `fake`) VALUES ($uid, $profile_id, 0)";
		$stmt = $pdo->prepare($query);
		$stmt->execute();
		alert_info("you have blocked the user");
	}
}

function addFake($profile_id, $uid, $pdo){
	if (!profileBlocked($profile_id, $uid, $pdo)){
		$query = "INSERT INTO `blocked` (`user_id`, `blocked_id`, `fake`) VALUES ($uid, $profile_id, 1)";
		$stmt = $pdo->prepare($query);
		$stmt->execute();
	}
	else{
		$query = "UPDATE `blocked` SET fake=1 WHERE blocked_id=$profile_id AND user_id=$uid ;";
		$stmt = $pdo->prepare($query);
		$stmt->execute();
	}
}
?>
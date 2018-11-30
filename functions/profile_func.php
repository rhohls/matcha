<?php

function sendNotification($profile_id, $uid, $pdo){
	// TO-DO
}

function checkConnection($profile_id, $uid, $pdo){

}

function profileBlocked($profile_id, $uid, $pdo){
	$query = "SELECT * FROM `blocked` WHERE user_id=$profile_id AND blocked_id=$uid";
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
		// $query = "INSERT INTO `view_like` ('user_to', 'user_from', 'viewed', 'liked') VALUES ($profile_id, $uid, 1, 0)";
		$query = "INSERT INTO `view_like` (`user_from`, `user_to`, `liked`, `viewed`) VALUES ('$uid', '$profile_id', '0', '1');";
		$stmt = $pdo->prepare($query);
		// var_dump($stmt);
		$stmt->execute();

		sendNotification($profile_id, $uid, $pdo);
	}
	
}


?>
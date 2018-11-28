<?php

require_once 'connect.php';

function profileBlocked($profile_id, $uid, $pdo){
	$query = "SELECT * FROM `blocked` WHERE user_id=:profile_id AND blocked_id=:visitor";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["profile_id" => $profile_id, "visitor" => $uid]);

	$blocked_users = $stmt->fetchAll();

	if ($blocked_users)
		return(true);
	else
		return (false);
}

function removeLike($profile_id, $uid, $pdo){
	$query = "UPDATE `view_like` SET liked=0 WHERE user_to=:profile_id AND user_from=:visitor ;";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["user_to" => $profile_id, "user_from" => $uid]);
}

function addLike($profile_id, $uid, $pdo){
	$query = "SELECT * FROM `view_like` WHERE user_to=:profile_id AND user_from=:visitor AND liked=0";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["user_to" => $profile_id, "user_from" => $uid]);

	$liked = $stmt->fetchAll();

	if (!$liked){
		$query = "INSERT INTO `view_like` ('user_to', 'user_from', 'liked') VALUES (:profile_id, :visitor, 1)";
		$stmt = $pdo->prepare($query);
		$stmt->execute(["user_to" => $profile_id, "user_from" => $uid]);

	}
	else{
		$query = "UPDATE `view_like` SET liked=1 WHERE user_to=:profile_id AND user_from=:visitor ;";
		$stmt = $pdo->prepare($query);
		$stmt->execute(["user_to" => $profile_id, "user_from" => $uid]);
	}

	sendNotification($profile_id, $uid, $pdo);
}


function addView($profile_id, $uid, $pdo){
	$query = "SELECT * FROM `view_like` WHERE user_to=:profile_id AND user_from=:visitor AND visited=1";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["user_to" => $profile_id, "user_from" => $uid]);

	$visited = $stmt->fetchAll();

	if (!$visited){
		$query = "INSERT INTO `view_like` ('user_to', 'user_from', 'visited') VALUES (:profile_id, :visitor, 1)";
		$stmt = $pdo->prepare($query);
		$stmt->execute(["user_to" => $profile_id, "user_from" => $uid]);

		sendNotification($profile_id, $uid, $pdo);
	}
	
}


?>
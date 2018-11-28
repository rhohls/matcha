<?php

require_once 'connect.php';

function profileBlocked($profile_id, $uid, $pdo){
	$query = "SELECT * FROM `blocked` WHERE user_id=:uid";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["uid" => $profile_id]);

	$blocked_users = $stmt->fetchAll();

	foreach ($blocked_users as $user){
		if ($user['user_id'] == $uid)
			return(true);
	}
	return (false);
}

function removeLike($profile_id, $uid, $pdo){
	
}

function addLike($profile_id, $uid, $pdo){
	
}


function addView($profile_id, $uid, $pdo){
	
}


// $query = "SELECT * FROM `images` JOIN `users` ON images.user_id=users.id WHERE img_id=:id";
// $stmt = $pdo->prepare($query);
// $stmt->execute(["id" => $img_id]);

// $image = $stmt->fetch();


// $query = "SELECT * FROM `comments` JOIN `users` ON comments.commentator_id=users.id WHERE comments.img_id=:id;";
// $stmt = $pdo->prepare($query);
// $stmt->execute(["id" => $img_id]);

// $comments = $stmt->fetchAll();
?>
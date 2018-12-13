<?php

function accountCompleted($pdo, $uid){
	$query = "SELECT * FROM `users` WHERE id=$uid";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

	$user = $stmt->fetch();

	if ($user['profile_img_loc'] != './page_imgs/blank_profile_picture.png' &&
		$user['bio'] != '' &&
		$user['gender'] != 'none' &&
		$user['birthdate'] != '1888-01-01'
		){
			$query = "UPDATE `users` SET comlete=1 WHERE id=:uid;";

			$stmt = $pdo->prepare($query);
			$stmt->execute(['uid' => $uid]);
		}
}

function sqlUpdate($adjust_info, $pdo, $error, $uid){
	// Sql update
	$adjust_str =  urldecode(http_build_query($adjust_info,'\'',', '));
 	if ($adjust_str != "" && $error == 0){
		$query = "UPDATE `users` SET $adjust_str WHERE id=:uid;";

		$stmt = $pdo->prepare($query);
		$stmt->execute(['uid' => $uid]);

		$changed = array_keys($adjust_info);
		if (isset($adjust_info["user_name"]))
			$_SESSION['user_name'] = trim($adjust_info['user_name'], '\'');
		alert_info('The following account info has been changed:\n'. implode(", ", $changed));

		accountCompleted($pdo, $uid);

	 }
	 else if ($error != 1){
		alert_info('Please enter information to change');
	 }
}


function arrayOldImages($pdo, $uid){
	$query = "SELECT * FROM `users` WHERE id=$uid";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

	$profile_info = $stmt->fetch();
	return (unserialize($profile_info['images']));
}
<?php

function accountCompleted(){
	//check all info in account is there
	// set sql complete variable
	// alert user what information is needed
}

function sqlUpdate($adjust_info, $pdo, $error, $uid){
	// Sql update
	var_dump($adjust_info);
	$adjust_str =  urldecode(http_build_query($adjust_info,'\'',', '));
 	if ($adjust_str != "" && $error == 0){
		$query = "UPDATE `users` SET $adjust_str WHERE id=:uid;";

		$stmt = $pdo->prepare($query);
		$stmt->execute(['uid' => $uid]);

		$changed = array_keys($adjust_info);
		if (isset($adjust_info["user_name"]))
			$_SESSION['user_name'] = trim($adjust_info['user_name'], '\'');
		alert_info('The following account info has been changed:\n'. implode(", ", $changed));

		accountCompleted();

	 }
	 else if ($error != 1){
		alert_info('Please enter information to change');
	 }
}

<?php 
session_start();
function getNotifications($uid){
	return (7);
}

function make_header_array(){
	if (isset($_SESSION['uid'])){
		$uid = $_SESSION['uid'];
		$username = $_SESSION['user_name'];
		$loggedin = true;
		$notifications = getNotifications($uid);
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





$base_array = make_header_array();



?>

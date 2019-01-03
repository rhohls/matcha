<?php
session_start();
require_once 'require.php';
require_once 'logged_in.php';

// TO-DO html user you chatting with

function isConnected($pdo, $uid, $partner){
	$query =   "SELECT * FROM `view_like` WHERE connected=1	AND user_to=:id AND user_from=:id2";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => $uid, "id2" => $partner]);

	$res = $stmt->fetch();

	if ($res)
		return true;
	else
		return false;
}
// Variables
$partner = $_GET['usr_id'];
$uid = $_SESSION['uid'];

if (isset($_POST['submit']) && $_POST['submit'] == 'Send'){
	$message = sanitize($_POST['message']);
	$query = "INSERT INTO `messages` (`from_id`, `to_id`, `comment`, `sent`) VALUES (:uid, :partner, :message, CURRENT_TIMESTAMP);";
	$stmt = $pdo->prepare($query);
	$stmt->execute(['message' => $message, 'uid' => $uid, 'partner' => $partner]);

	// TO-DO send notification --check if shows up on notification page

	sendNotification($partner, $uid, $pdo);
}

//  -----
// If no chat specified
//  -----
if (!isset($_GET['usr_id'])){

	$query =   "SELECT user_to, first_name, last_name FROM `view_like`
				JOIN users ON view_like.user_to=users.id
				WHERE connected=1
				AND user_from=:id";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => $uid]);
	
	$all_con_users = $stmt->fetchAll();

	echo $twig->render('chat_list.html.twig', array(
		'base'			=>	$base_array,
		'connections'	=>	$all_con_users
	));
} 

//  -----
// specific user for chat
//  -----
else{
	if (!isConnected($pdo, $uid, $partner)){
		alert("You are not connected to that user", "chat.php");
	}
	
	$query = "SELECT * FROM `messages` WHERE from_id=:id OR to_id=:id
				ORDER BY sent";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => $uid]);
	
	$all_messages = $stmt->fetchAll();

	echo $twig->render('chat.html.twig', array(
		'base'		=>	$base_array,
		'messages'	=>	$all_messages,
		'partner'	=>	$partner
	));
}

?>
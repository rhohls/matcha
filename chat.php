<?php
session_start();
require_once 'require.php';
require_once 'logged_in.php';


// Variables
$partner = $_GET['usr_id'];
$uid = $_SESSION['uid'];

//message sending & notification
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

	$query =   "SELECT id, user_to, first_name, last_name FROM `view_like`
				JOIN users ON view_like.user_to=users.id
				WHERE connected=1
				AND user_from=:id";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => $uid]);
	
	$all_con_users = $stmt->fetchAll();
	$all_con_users = remove_duplicate($all_con_users);
	$con_users =  remove_blocked($all_con_users, $uid, $pdo);
	// var_dump($all_con_users);
	// $con_users = $all_con_users;

	echo $twig->render('chat_list.html.twig', array(
		'base'			=>	$base_array,
		'connections'	=>	$con_users
	));
} 

//  -----
// specific user for chat
//  -----
else{
	if (!isConnected($pdo, $uid, $partner)){
		alert("You are not connected to that user", "chat.php");
	}

	$query = "SELECT * FROM `users` WHERE id=$partner";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

	$profile_info = $stmt->fetch();
	
	$all_messages = fetchMessages($pdo, $uid, $partner);

	echo $twig->render('chat.html.twig', array(
		'base'		=>	$base_array,
		'profile'	=>	$profile_info,
		'messages'	=>	$all_messages,
		'partner'	=>	$partner
	));
}

?>
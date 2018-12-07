<?php
session_start();
require_once 'require.php';


require_once 'logged_in.php';

$profile_id = $_GET['usr_id'];
$uid = $_SESSION['uid'];

//  -----
// If no chat specified
//  -----
if (!isset($_GET['usr_id'])){

	
	
	
	
	
	echo $twig->render('chat_list.html.twig', array(
		'base'			=>	$base_array,
		'connections'	=>	$all_con_users
	));
} 


//  -----
// specific user for chat
//  -----
else{


	$query = "SELECT * FROM `users` WHERE id=$profile_id";
	$stmt = $pdo->prepare($query);
	$stmt->execute();
	
	$profile_info = $stmt->fetch();



	echo $twig->render('chat.html.twig', array(
		'base'		=>	$base_array,
		'messages'	=>	$all_messages
	));
}








// $query = "SELECT * FROM `users` WHERE id=$profile_id";
// $stmt = $pdo->prepare($query);
// $stmt->execute();

// $profile_info = $stmt->fetch();
// $profile_images = unserialize($profile_info['images']);

// if (!$profile_info){
// 	alert("User does not exist","index.php");
// 	die();	
// }



?>
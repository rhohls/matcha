<?php
session_start();
require_once 'require.php';


require_once 'logged_in.php';

$partner = $_GET['usr_id'];
$uid = $_SESSION['uid'];

//  -----
// If no chat specified
//  -----
if (!isset($_GET['usr_id'])){


	$query =   "SELECT user_to, first_name, last_name FROM `view_like`
				JOIN users ON view_like.user_to=users.id
				WHERE connected=1";
	$stmt = $pdo->prepare($query);
	$stmt->execute();
	
	$all_con_users = $stmt->fetchAll();

	// var_dump($all_con_users);





	echo $twig->render('chat_list.html.twig', array(
		'base'			=>	$base_array,
		'connections'	=>	$all_con_users
	));
} 


//  -----
// specific user for chat
//  -----
else{


	$query = "SELECT * FROM `messages` WHERE from_id=:id OR to_id=:id
				ORDER BY sent";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => $uid]);
	
	$all_messages = $stmt->fetchAll();

	var_dump($all_messages);



	echo $twig->render('chat.html.twig', array(
		'base'		=>	$base_array,
		'messages'	=>	$all_messages
	));
}








// $query = "SELECT * FROM `users` WHERE id=$partner";
// $stmt = $pdo->prepare($query);
// $stmt->execute();

// $profile_info = $stmt->fetch();
// $profile_images = unserialize($profile_info['images']);

// if (!$profile_info){
// 	alert("User does not exist","index.php");
// 	die();	
// }



?>
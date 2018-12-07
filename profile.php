<?php
session_start();
require_once 'require.php';
require_once './functions/profile_func.php';

require_once 'logged_in.php';

$profile_id = $_GET['usr_id'];
$uid = $_SESSION['uid'];

if(!isset($_GET['usr_id']) ||
	profileBlocked($profile_id, $uid, $pdo)){

	alert("User does not exist","index.php");
	die();
}

if ($profile_id != $uid){
	if (userExist_id($pdo, $profile_id))
		addView($profile_id, $uid, $pdo);
}

if (isset($_POST['submit'])){
	if (profileComplete()){
		if ($_POST['submit'] == 'Like')
			addLike($profile_id, $uid, $pdo);
		else if ($_POST['submit'] == 'Un-like')
			removeLike($profile_id, $uid, $pdo);
		else if ($_POST['submit'] == 'Report Fake')
			addFake($profile_id, $uid, $pdo);
		else if ($_POST['submit'] == 'Block')
			addBlocked($profile_id, $uid, $pdo);
	}
	else{
		alert_info("You\'re profile is incomplete.\n Please complete it to do that action");
	}
}

$query = "SELECT * FROM `users` WHERE id=$profile_id";
$stmt = $pdo->prepare($query);
$stmt->execute();

$profile_info = $stmt->fetch();
$profile_images = unserialize($profile_info['images']);

if (!$profile_info){
	alert("User does not exist","index.php");
	die();	
}

echo $twig->render('profile.html.twig', array(
	'base'		=>	$base_array,
	'profile'	=>	$profile_info,
	'profile_images' => $profile_images
));

?>
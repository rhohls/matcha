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
	addView($profile_id, $uid, $pdo);
}

if (isset($_POST['submit'])){
	if ($_POST['submit'] == 'Like')
		addLike($profile_id, $uid, $pdo);
	if ($_POST['submit'] == 'Un-like')
		removeLike($profile_id, $uid, $pdo);
}

$query = "SELECT * FROM `users` WHERE id=$profile_id";
$stmt = $pdo->prepare($query);
$stmt->execute();

$profile_info = $stmt->fetch();
$profile_images = unserialize($profile_info['images']);

$im = (serialize(array('./page_imgs/rand.png', './page_imgs/rand.png', './page_imgs/rand.png')));

// $query = "UPDATE `users` SET images='$im' WHERE id=$profile_id";
// $stmt = $pdo->prepare($query);
// $stmt->execute();



// var_dump($profile_info);
echo $twig->render('profile.html.twig', array(
	'base'		=>	$base_array,
	'profile'	=>	$profile_info,
	'profile_images' => $profile_images
));




?>




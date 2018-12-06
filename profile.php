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
	if ($_POST['submit'] == 'Like')
		addLike($profile_id, $uid, $pdo);
	else if ($_POST['submit'] == 'Un-like')
		removeLike($profile_id, $uid, $pdo);
	else if ($_POST['submit'] == 'Report Fake')
		addFake($profile_id, $uid, $pdo);
	else if ($_POST['submit'] == 'Block')
		addBlocked($profile_id, $uid, $pdo);	
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

// $im = (serialize(array('./page_imgs/rand.png', './page_imgs/rand.png', './page_imgs/rand.png')));
// $query = "UPDATE `users` SET images='$im' WHERE id=$profile_id";
// $stmt = $pdo->prepare($query);
// $stmt->execute();
// var_dump($profile_info);



// profileBlocked($id_tocheck, $id_of_request, $pdo)
echo " to check: " .$profile_id ."requested from: ".$uid . "<br>";
echo "res ";
if (profileBlocked($profile_id, $uid, $pdo))
	echo "true";
else
	echo "false";
echo " <br>";


echo $twig->render('profile.html.twig', array(
	'base'		=>	$base_array,
	'profile'	=>	$profile_info,
	'profile_images' => $profile_images
));




?>




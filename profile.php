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

echo $twig->render('profile.html.twig', array(
	'base' => $base_array
));




?>




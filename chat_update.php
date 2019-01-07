<?php
session_start();
require_once 'require.php';
require_once 'logged_in.php';


// Variables
$partner = $_GET['usr_id'];
$uid = $_SESSION['uid'];

// echo "uid: ". $uid . "<br>";
// echo "pt: ". $partner . "<br>";
// var_dump($_GET);

$all_messages = fetchMessages($pdo, $uid, $partner);

// var_dump($all_messages);
echo $twig->render('chat_window.html.twig', array(
'base'		=>	$base_array,
'messages'	=>	$all_messages,
));

?>
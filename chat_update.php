<?php
session_start();
require_once 'require.php';

require_once 'logged_in.php';


// Variables
$partner = $_GET['usr_id'];
$uid = $_SESSION['uid'];



$query = "SELECT * FROM `messages` WHERE from_id=:id OR to_id=:id
ORDER BY sent";
$stmt = $pdo->prepare($query);
$stmt->execute(["id" => $uid]);

$all_messages = $stmt->fetchAll();

echo $twig->render('chat_window.html.twig', array(
'base'		=>	$base_array,
'messages'	=>	$all_messages,
));

?>
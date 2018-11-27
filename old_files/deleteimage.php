<?php
session_start();
require_once 'connect.php';


// var_dump($_GET);

$img_id = $_POST['id'];
$loggedin = $_SESSION['uid'];

$query = "SELECT * FROM `images` WHERE img_id=:id AND user_id=:owner";
$stmt = $pdo->prepare($query);
$stmt->execute(["id" => (int)$img_id, "owner" => (int)$loggedin]);

$image = $stmt->fetch();

if ($image){
	$query = "DELETE FROM `images` WHERE img_id=:id AND user_id=:owner";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => (int)$img_id, "owner" => (int)$loggedin]);

	$img_loc = $image['image_location'];
	unlink($img_loc);

	$query = "DELETE FROM `comments` WHERE img_id=:id";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => (int)$img_id]);
}
?>
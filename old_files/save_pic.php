<?php

require_once 'connect.php';
require_once 'generic_functions.php';


function mergeImages($img_base, $img_top){

	$src_str = trim($img_base);
	$dest = imagecreatefrompng($src_str);

	$file_path = './imgs/temp.png';

	file_put_contents($file_path, $img_top);
	$image = imagecreatefrompng($file_path);

	imagealphablending($image, true);
	imagesavealpha($image, true);
	imagecopy($dest, $image, 0, 0, 0, 0, 400, 300);
	
	imagedestroy($image);

	return ($dest);
}


if(isset($_POST["img"]))  
{
	$uploads_dir = "./imgs";
	$img = $_POST['img'];

	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$file = base64_decode($img);
	// date("r",hexdec(substr(uniqid(),0,8))); //this converts uniqid into time
	$type = "png";
	$name = uniqid() . "." . $type;
	$store_location = "$uploads_dir/$name";
	// use finfo_open to verify type

	file_put_contents($store_location, $file);

	$uid = $_SESSION['uid'];
	$query = "INSERT INTO `images` (user_id, image_location, original) VALUES (:uid, :loc, 1)";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["uid" => $uid, "loc" => $store_location]); //use this for security
}

if(isset($_POST["edit"]) )  
{
	$uploads_dir = "./imgs";
	$img = $_POST['edit'];

	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);

	$stickers = base64_decode($img);

	$type = "png";
	$name = uniqid() . "." . $type;
	$store_location = "$uploads_dir/$name";
	
	$file = mergeImages($_POST['base'], $stickers);

	imagepng($file, $store_location);

	$uid = $_SESSION['uid'];
	$query = "INSERT INTO `images` (user_id, image_location, original) VALUES (:uid, :loc, 0)";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["uid" => $uid, "loc" => $store_location]); //use this for security
}

?>
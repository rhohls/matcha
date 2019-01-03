<?php

session_start();
require_once 'require.php';
require_once './functions/adjust_func.php';

require_once 'logged_in.php';

$error = 0;

$adjust_info = array();
$uid = $_SESSION['uid'];
$uploads_dir = "./imgs";

var_dump($_POST);
// account info change
if (isset($_POST["submit"]) && ($_POST["submit"] == "Update account"))
{

	if ($_POST["passwd"] !== "")
	{
		$pwd_error = checkPassword($_POST["passwd"]);
		
		if ($_POST["passwd"] !== $_POST["checkpasswd"]){
			alert_info("Passwords do not match");
			$error = 1;
		}
		
		// password security level
		else if ($pwd_error){
			$error = 1;
			alert_info($pwd_error);
		}
		else {
			$hashedpwd = hashPW($_POST["passwd"]);
			$adjust_info["password"] = addQuotes($hashedpwd);
		}
	}
	
	if ($_POST["login"] !== "")	{
		if (userExist($pdo, $_POST["login"])){
			$error = 1;
			alert_info("Username already taken");
		}
		$adjust_info["user_name"] = addQuotes($_POST["login"]);
	}

	if ($_POST["email"] !== "")	{
		$adjust_info["email"] = addQuotes($_POST["email"]);
	}
	sqlUpdate($adjust_info, $pdo, $error, $uid);
}


// TO-DO birthdate (check all)


// Other account info
if (isset($_POST["submit"]) && ($_POST["submit"] == "Update profile"))
{

	if ($_POST["sex_pref"] !== "no_change")	{
		$adjust_info["sex_pref"] = addQuotes($_POST["sex_pref"]);
	}
	
	if ($_POST["gender"] !== "no_change")	{
		$adjust_info["gender"] = addQuotes($_POST["gender"]);
	}
	
	if ($_POST["bio"] !== "")	{
		$adjust_info["bio"] = addQuotes(sanitize($_POST["bio"]));
	}

	if ($_POST["latitude"] !== "")	{
		$pos = $_POST["latitude"];
		if ($pos < -90 || $pos > 90){
			$error = 1;
			alert_info("Please enter a valid latitude");
		}
		$adjust_info["latitude"] = addQuotes(sanitize($pos));
	}

	if ($_POST["longitude"] !== "")	{
		$pos = $_POST["longitude"];
		if ($pos < -90 || $pos > 90){
			$error = 1;
			alert_info("Please enter a valid longitude");
		}
		$adjust_info["longitude"] = addQuotes(sanitize($pos));
	}

	// if ($_POST["birthday"] !== "")	{
	// 	$adjust_info["bio"] = addQuotes(sanitize($_POST["bio"]));
	// }

	sqlUpdate($adjust_info, $pdo, $error, $uid);
}

// image upload
if(isset($_POST["insert"]))  
{ 
	$file = $_FILES["image"]["tmp_name"];

	if ($file){
		$type = explode('/', $_FILES["image"]["type"]);
		$name = uniqid() . "." . $type[1];
		$store_location = "$uploads_dir/$name";
		// use finfo_open to verify type

		move_uploaded_file($file, $store_location);

		$query = "INSERT INTO `images` (user_id, image_location) VALUES (:uid, :loc)";
		$stmt = $pdo->prepare($query);
		$stmt->execute(["uid" => $uid, "loc" => $store_location]); //use this for security
		
		alert_info("Please choose a file to upload");

		if($_POST["insert"] = "Change profile picture"){
			$query = "UPDATE `users` SET (profile_img) VALUES (:img_loc) WHERE id=:uid;";
			
			$stmt = $pdo->prepare($query);
			$stmt->execute(["uid" => $uid, "img_loc" => $store_location]); 
		}
		else {
			
		}
	}
	else{
		alert_info("Please choose a file to upload");
	}
}




echo $twig->render('adjust.html.twig', array(
	'base' => $base_array
));

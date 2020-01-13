<?php

session_start();
require_once 'require.php';
require_once './functions/adjust_func.php';

require_once 'logged_in.php';

$error = 0;

$adjust_info = array();
$uid = $_SESSION['uid'];
$uploads_dir = "./imgs";


//getting user selected tags
$query = "SELECT * FROM `user_tag` WHERE user_id=$uid;";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usertags = $stmt->fetchAll();
$isloated_usertag = isolateUsertag($usertags);


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

	if ($_POST["birthdate"] !== "")	{
		$adjust_info["birthdate"] = addQuotes(sanitize($_POST["birthdate"]));
	}

	sqlUpdate($adjust_info, $pdo, $error, $uid);
}


//Tags
if (isset($_POST["submit"]) && ($_POST["submit"] == "Update tags"))
{
	$selected_tags = array_map(function($value) {return intval($value);}, $_POST['tagSelected']);
	
	//usertags at top of page

	//add new
	foreach ($selected_tags as $tag){
		if (!tagInUsertag($usertags, $tag)){
			$tagid = $tag['id'];
			$query = "INSERT INTO `user_tag`(`user_id`, `tag_id`) VALUES ($uid, $tag)";	
			$stmt = $pdo->prepare($query);
			$stmt->execute();			
		}
	}

	//remove unselected
	foreach ($usertags as $tag){
		if (!in_array($tag['tag_id'], $selected_tags)){
			$tagid = $tag['id'];
			$query = "DELETE FROM `user_tag` WHERE id=$tagid";	
			$stmt = $pdo->prepare($query);
			$stmt->execute();			
		}
	}
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

		if($_POST["insert"] == "Change profile picture"){
			$query = "UPDATE `users` SET profile_img_loc='$store_location' WHERE id=$uid;";
			
			$stmt = $pdo->prepare($query);
			$stmt->execute(); 
		}
		else {
			//fetch array of images
			$query = "SELECT * FROM `users` WHERE id=$uid ";
			$stmt = $pdo->prepare($query);
			$stmt->execute();

			$profile_info = $stmt->fetch();
			$profile_images = unserialize($profile_info['images']);

			//limit to 5 images
			if (!$profile_images){
				$profile_images = [];
			}
			else if (sizeof($profile_images) > 5){
				array_shift($profile_images);
			}
			//add image and update table
			array_push($profile_images, $store_location);
			$info = serialize($profile_images);

			$query = "UPDATE `users` SET images='$info' WHERE id=$uid;";	
			$stmt = $pdo->prepare($query);
			$stmt->execute();
		}
	}
	else{
		alert_info("Please choose a file to upload");
	}
}



$stmt = $pdo->query("SELECT * FROM `tags`");
$all_tags = $stmt->fetchAll();

//update variables for display
$query = "SELECT * FROM `user_tag` WHERE user_id=$uid;";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usertags = $stmt->fetchAll();
$isloated_usertag = isolateUsertag($usertags);

echo $twig->render('adjust.html.twig', array(
	'base' => $base_array,
	'all_tags' => $all_tags,
	'isloated_usertag' => $isloated_usertag
));

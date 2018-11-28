<?php

require_once 'connect.php';
require_once 'generic_functions.php';
session_start();

require_once 'logged_in.php';

$error = 0;

$adjust_info = array();
$uid = $_SESSION['uid'];
$uploads_dir = "./imgs";

// account info change
if (isset($_POST["submit"]) && ($_POST["submit"] == "OK"))
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

	if ($_POST["sex_pref"] !== "no_change")	{
		$adjust_info["sex_pref"] = addQuotes($_POST["sex_pref"]);
	}
	
	if ($_POST["gender"] !== "no_change")	{
		$adjust_info["gender"] = addQuotes($_POST["gender"]);
	}
	
	if ($_POST["bio"] !== "")	{
		$adjust_info["bio"] = addQuotes(sanitize($_POST["bio"]));
	}	

	$adjust_str =  urldecode(http_build_query($adjust_info,'\'',', '));

 	if ($adjust_str != ""){
		$query = "UPDATE `users` SET $adjust_str WHERE id=:uid;";

		$stmt = $pdo->prepare($query);
		$stmt->execute(['uid' => $uid]); //use this for security

		$changed = array_keys($adjust_info);
		if (isset($adjust_info["user_name"]))
			$_SESSION['user_name'] = trim($adjust_info['user_name'], '\'');
		alert_info('The following account info has been changed:\n'. implode(", ", $changed));
	 }
	 else if ($error != 1){
		alert_info('Please enter information to change');
	 }
}
// image upload
else if(isset($_POST["insert"]))  
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

	if(isset($_POST["Change profile"])){
		$query = "UPDATE `users` SET (profile_img) VALUES (:img_loc) WHERE id=:uid;";
		
		$stmt = $pdo->prepare($query);
		$stmt->execute(["uid" => $uid, "img_loc" => $store_location]); 
	}


	}
	else{
		alert_info("Please choose a file to upload");
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/style.css">
	<title>Matcha</title>
</head>
<body>
	<div class="main_wrapper">
		<!-- Header --><?php require_once('header.php'); ?>
		<div class="content_wrapper">
			

			<!-- Main content -->
			<div id="items">
				<h2>Adjust Basic account info</h2>
				<br>
				<form action="#" method="POST">
					<table class="form_table">
						<tr>
							<td>New username:</td>
							<td><input type="text" name="login" value=""/></td>
						</tr>
						<tr>
							<td>New password:</td>
							<td><input type="password" name="passwd" value=""/></td>
						</tr>
						<tr>
							<td>Retype new password:</td>
							<td><input type="password" name="checkpasswd" value=""/></td>
						</tr>
						<tr>
							<td>New email adress:</td>
							<td><input type="email" name="email" value=""/></td>
						</tr>						
						<tr>
							<td>Notify on comment:</td>
							<td><select name="notify">
								<option value='no_change' >No Change</option>
								<option value='yes' >Yes</option>
								<option value='no' >No</option>
								</select>
							</td>
						</tr>

						<tr align="right">
							<td><input type="submit" name="submit" value="OK"/></td>
						</tr>
					</table>
				</form>

				<h2>Adjust other info</h2>
				<br>
				<form action="#" method="POST">
					<table class="form_table">
						<tr>
							<td>Gender:</td>
							<td><select name="gender">
								<option value='no_change' >No Change</option>
								<option value='male' >Male</option>
								<option value='female' >Female</option>
								</select>
							</td>
						</tr>
						
						<tr>
							<td>Bio:</td>
							<td><textarea name="bio" ></textarea></td>
						</tr>
						<tr>
							<td>Sexual prefrence:</td>
							<td><select name="sex_pref">
								<option value='no_change' >No Change</option>
								<option value='hetrosexual' >Hetrosexual</option>
								<option value='bisexual' >Bi-sexual</option>
								<option value='homosexual' >Homosexual</option>
								</select>
							</td>
						</tr>				
						<tr>
							<td>Interests:</td>
							<td><input type="text" name="login" value=""/></td>
						</tr>


						<tr align="right">
							<td><input type="submit" name="submit" value="OK"/></td>
						</tr>
					</table>
				</form>


				<h2> Upload an Image:</h2>
					<div id="upload-img">
						<form method="POST"  enctype="multipart/form-data">  
							<input type="file" name="image" accept="image/*" />  
							<br /> 
							<br>
							<input type="submit" name="insert" value="Change profile"/>
							<br>
							<input type="submit" name="insert" value="Upload to gallery"/> 
						</form> 
					</div>



				
			</div>
			<!-- End main contents -->


		<!-- Sidebar --><?php require_once('sidebar.php'); ?>

		<div id="clear"></div>

		</div>
		<!-- <br> -->
		<!-- footer -->
	</div>
	<?php require_once('footer.php'); ?>
</body>
</html>

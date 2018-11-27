<?php
session_start();
require_once 'connect.php';
require_once 'generic_functions.php';


if (!isset($_SESSION['uid'])){
	header('Location: login.php');
}

$uid = $_SESSION['uid'];
$uploads_dir = "./imgs";

if(isset($_POST["insert"]))  
{ 
	$file = $_FILES["image"]["tmp_name"];

	if ($file){

	// date("r",hexdec(substr(uniqid(),0,8))); //this converts uniqid into time
	$type = explode('/', $_FILES["image"]["type"]);
	$name = uniqid() . "." . $type[1];
	$store_location = "$uploads_dir/$name";
	// use finfo_open to verify type

	move_uploaded_file($file, $store_location);

	$uid = $_SESSION['uid'];
	$query = "INSERT INTO `images` (user_id, image_location, original) VALUES (:uid, :loc, 1)";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["uid" => $uid, "loc" => $store_location]); //use this for security

	header("Location: user_images.php?usr_id=$uid");
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
	<title>Camagru</title>
</head>
<body>
	<div class="main_wrapper">
		<!-- Header --><?php require_once('header.php'); ?>
		<div class="content_wrapper">
			

			<!-- Main content -->
			<div id="items">
				<div>
					<h1>Take or Upload an image</h1>
					<h2> Video:</h2>

						<video autoplay=true id='video_player' height='300' width='400'></video>
						<br>
						<a href='#' id="capture" class="pic_btn"><button>Take picture </button></a>
					<h2> Picture:</h2>
						<canvas id='canvas' height="300" width="400"></canvas>
					<div id="photo_buttons">
						<button id="save_button" type="button" onclick="sendData();" disabled>Save picture for editing</button>	
					</div>

					<h2> Upload an Image:</h2>
					<div id="upload">
						<form method="POST"  enctype="multipart/form-data">  
							<input type="file" name="image" accept="image/*" />  
							<br />  
							<input type="submit" name="insert" value="Upload picture for editing"/>  
						</form> 
					</div>
				</div>
				<script src='javascript/photo.js'></script>
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


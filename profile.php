<?php
session_start();
require_once 'connect.php';
require_once 'generic_functions.php';

if(!isset($_GET['usr_id'])){
	alert("User does not exit","index.php");
	die();
}
$uid = $_GET['usr_id'];

// $query = "SELECT * FROM `images` JOIN `users` ON images.user_id=users.id WHERE img_id=:id";
// $stmt = $pdo->prepare($query);
// $stmt->execute(["id" => $img_id]);

// $image = $stmt->fetch();


// $query = "SELECT * FROM `comments` JOIN `users` ON comments.commentator_id=users.id WHERE comments.img_id=:id;";
// $stmt = $pdo->prepare($query);
// $stmt->execute(["id" => $img_id]);

// $comments = $stmt->fetchAll();



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
			

			<div id="items">
				<!-- Main content -->

				<div class="profile-header">
					<img src="./page_imgs/blank_profile_picture.png" id="profile-image" height="300px"> <!-- float left -->

					<div id="personal-info">
						birthday
						<br>
						etc
						<br>
					</div>


				</div>

				<h3>Bio</h3>

				<article>
					User bio here
				</article>


			
			<!-- End main contents -->
			</div>

		<!-- Sidebar --><?php require_once('sidebar.php'); ?>

		<div id="clear"></div>

		</div>
		<!-- <br> -->
		<!-- footer -->
	</div>
	<?php require_once('footer.php'); ?>
</body>
</html>



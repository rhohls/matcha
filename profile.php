<?php
session_start();
require_once 'connect.php';
require_once 'generic_functions.php';
require_once './functions/profile_func.php';

require_once 'logged_in.php';

$profile_id = $_GET['usr_id'];
$uid = $_SESSION['uid'];

if(!isset($_GET['usr_id']) ||
	profileBlocked($profile_id, $uid, $pdo)){
	alert("User does not exit","index.php");
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
				<?php var_dump($_POST); ?>

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


			<!-- disable button  -->
			<!-- or -- you have liked this profile -->
			<form action="#" method="POST">
				<input type="submit" name="submit" value="Like"/>
				<input type="submit" name="submit" value="Un-like"/>
			</form>
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



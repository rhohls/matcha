<?php
session_start();
require_once 'connect.php';
require_once 'generic_functions.php';

require_once 'logged_in.php';

$uid = $_SESSION['uid'];



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
			<h2>People who viewed your profile</h2>

			<ul>
				<li><a href="profile.php?usr_id=0">First last</a></li>
			</ul>
				
			<h2>People who liked your profile</h2>

			<ul>
				<li><a href="profile.php?usr_id=0">First last</a></li>
			</ul>
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



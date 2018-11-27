<?php
session_start();
require_once 'connect.php';
require_once 'generic_functions.php';

if(!isset($_GET['img_id'])){
	alert_info("Error");
	die();
}
$img_id = $_GET['img_id'];

$query = "SELECT * FROM `images` JOIN `users` ON images.user_id=users.id WHERE img_id=:id";
$stmt = $pdo->prepare($query);
$stmt->execute(["id" => $img_id]);

$image = $stmt->fetch();


$query = "SELECT * FROM `comments` JOIN `users` ON comments.commentator_id=users.id WHERE comments.img_id=:id;";
$stmt = $pdo->prepare($query);
$stmt->execute(["id" => $img_id]);

$comments = $stmt->fetchAll();



if ($image['original'] == 1 && ($image['user_id'] != $_SESSION['uid'])){
	alert('Unauthorized', 'index.php');
}
else{
if ((isset($_SESSION['uid'])) && (isset($_POST['like']) || isset($_POST['dislike']) || isset($_POST['comment_txt']) ))
{
	if (isset($_POST['like'])){

		addLike($pdo, $img_id);
		header("Refresh:0");
	}
	elseif (isset($_POST['dislike'])){
		addDislike($pdo, $img_id);
		header("Refresh:0");
	}
	elseif (isset($_POST['comment_txt'])){
		if ($_POST['comment_txt'] == ''){
			alert_info("Please dont leave the comment blank");
		}else{
			$query = "INSERT INTO `comments` (commentator_id, comment, img_id) VALUES (:commentator_id, :comment, :img_id);";
			$stmt = $pdo->prepare($query);
			$comment = sanitize($_POST['comment_txt']);
			$stmt->execute(["commentator_id" => $_SESSION['uid'], "comment" => $comment, "img_id" => $img_id]);

			if ($image['notify'])
				sendNotificationEmail($image);

			header("Refresh:0");
			
		}
	}
}
else if ((!isset($_SESSION['uid'])) && (isset($_POST['like']) || isset($_POST['dislike']) || isset($_POST['comment_txt']) )) {
	alert_info('Please log in');
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

			<div class="profile_header">
				<img src="profile image" id="profileimage"> <!-- float left -->

				<div>
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


		<!-- Sidebar --><?php require_once('sidebar.php'); ?>

		<div id="clear"></div>

		</div>
		<!-- <br> -->
		<!-- footer -->
	</div>
	<?php require_once('footer.php'); ?>
</body>
</html>



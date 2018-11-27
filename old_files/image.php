<?php

function sendNotificationEmail($img_usr){
	$user_name = $img_usr['user_name'];
	$image_id = $img_usr['img_id'];

	$to = $img_usr['email'];
	$subject = "New comment";
	$headers = "From: noresponse@camagru.co.za";
	
	$txt = "Dear $user_name

	You have a new comment on one of your images:
	http://localhost:8080/cama/image.php?img_id=$image_id

	Kind Regards
	Camagru";
	mail($to,$subject,$txt,$headers);
}

function addLike($pdo, $img_id){
	$query = "UPDATE `images` SET likes=likes+1 WHERE img_id=:id;";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => $img_id]);
}
function addDislike($pdo, $img_id){
	$query = "UPDATE `images` SET dislikes=dislikes+1 WHERE img_id=:id;";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => $img_id]);
}



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
	<title>Matcha</title>
</head>
<body>
	<div class="main_wrapper">
		<!-- Header --><?php require_once('header.php'); ?>
		<div class="content_wrapper">
			

			<!-- Main content -->

			<!-- if images doesnt exist -->
			<div id="items">
				<?php
					if (!file_exists($image['image_location']))
						echo 'Image deleted';
					else{
					echo '<img src="'.$image['image_location'].'" height="300" width="400"/> ';
					echo ' <p> number of likes:'. $image['likes'] .' number of dislikes: '. $image['dislikes'] .'</p>';
				?>
				
				<form method="POST" id="image_form">
						<input type="submit" name="like" value="Like"/>  
						<input type="submit" name="dislike" value="Dislike"/>
						<br>
						<br>
						<textarea name="comment_txt"  ></textarea>
						<br>
						<input type="submit" name="comment" value="comment"/>
				</form>

				<table id="comments">
				<?php
				foreach($comments as $comment)
				{
					$txt = $comment['comment'];
					$usr_name = $comment['user_name'];
					$usr_id = $comment['commentator_id'];
					echo '<tr><td>
							<a href=user_images.php?usr_id='.$usr_id.' ><p> '.$usr_name .':</p> <a>
							<p> '.$txt .'</p>
						  </td></tr>';
				}
				}?>
				</table>
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



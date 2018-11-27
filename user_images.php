<?php

session_start();
require_once 'connect.php';
require_once 'generic_functions.php';

// if (isset($_SESSION['uid']) && ){

// }
// else
// 	header('Location login.php');
// }

if(!isset( $_GET['usr_id'])){
	alert_info("Error");
	die();
}
if(isset($_GET['pg_num']) && $_GET['pg_num']>1){
	$pg_num = $_GET['pg_num'];
} else {
	$pg_num = 1;
}

$usr_img_id = $_GET['usr_id'];
$img_per_page = 5;
$img_start = ($pg_num - 1) * $img_per_page;

if (isset($_SESSION['uid']) && ($_SESSION['uid'] == $usr_img_id))
	$owner = 1;
else
	$owner = 0;


if ($owner)
	$query = "SELECT * FROM `images` WHERE user_id=:id ORDER BY date_created DESC LIMIT $img_start,$img_per_page; ";
else
	$query = "SELECT * FROM `images` WHERE user_id=:id AND original=0 ORDER BY date_created DESC LIMIT $img_start,$img_per_page; ";

$stmt = $pdo->prepare($query);
$stmt->execute(["id" => $usr_img_id]);

$images = $stmt->fetchAll();
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
				<h1> All images for user </h1>
				<table>
				<?php
					// var_dump($images);
					if (!$images){
						echo("No more images found for user");
					}
					else foreach($images as $row)
					{
						$img_loc = $row['image_location'];
						$img_id = $row['img_id'];
						if (file_exists($img_loc)){
							echo '<tr><td>  
										<a href="image.php?img_id='.$img_id.'"><img src="'.$img_loc.'" height="300" width="400"/> </a>
								</td>';
							}
						if ($owner){
							echo '<td>
									<button onclick="deleteImage('.$img_id.')">Delete Image</button>
								</td></tr>';
						}else{
							echo '</tr>';
						}


					}
				?>
				</table>

				<div id="pagination">
					<?php 

						$_GET['pg_num'] = $pg_num - 1;
						$get_array = array();
						foreach ($_GET as $key => $val){
							$str = $key . '=' . $val;
							array_push($get_array, $str);
						}
						if ($_GET['pg_num'] > 0)
							echo "<a href='user_images.php?" . implode('&', $get_array) . "'> previous page</a>";

						$_GET['pg_num'] = $pg_num + 1;
						$get_array = array();
						foreach ($_GET as $key => $val){
							$str = $key . '=' . $val;
							array_push($get_array, $str);
						}
						if (count($images) >= 5)
							echo "<a href='user_images.php?" . implode('&', $get_array) . "'> next page</a>";
					?>
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



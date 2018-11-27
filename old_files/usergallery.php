<?php

function pageNation(){
	$_GET['pg_num'] = $pg_num - 1;
	$get_array = array();
	foreach ($_GET as $key => $val){
		$str = $key . '=' . $val;
		array_push($get_array, $str);
	}
	if ($_GET['pg_num'] > 0)
		echo "<a href='index.php?" . implode('&', $get_array) . "'> previous page</a>";

	$_GET['pg_num'] = $pg_num + 1;
	$get_array = array();
	foreach ($_GET as $key => $val){
		$str = $key . '=' . $val;
		array_push($get_array, $str);
	}
	if (count($images) >= 5)
		echo "<a href='index.php?" . implode('&', $get_array) . "'> next page</a>";
}




session_start();
require_once 'connect.php';
require_once 'generic_functions.php';


if(isset($_GET['pg_num']) && $_GET['pg_num']>1){
	$pg_num = $_GET['pg_num'];
} else {
	$pg_num = 1;
}

$img_per_page = 5;
$img_start = ($pg_num - 1) * $img_per_page;

$query = "SELECT * FROM `images` WHERE original=0 ORDER BY date_created DESC LIMIT $img_start,$img_per_page; ";

$stmt = $pdo->prepare($query);
$stmt->execute();

$images = $stmt->fetchAll();
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
				<h1> All images</h1>

				<div id="index_images">
				<?php
					if (!$images){
						echo("No more images found");
					}
					else foreach($images as $row)
					{
						$img_loc = $row['image_location'];
						$img_id = $row['img_id'];
						if (file_exists($img_loc)){
							echo '<div>  
										<a href="image.php?img_id='.$img_id.'"><img src="'.$img_loc.'" height="300" width="400"/> </a>
								</div>';
						}
					}
				?>
				</div>

				<div id="pagination">
					<?php 
							$_GET['pg_num'] = $pg_num - 1;
							$get_array = array();
							foreach ($_GET as $key => $val){
								$str = $key . '=' . $val;
								array_push($get_array, $str);
							}
							if ($_GET['pg_num'] > 0)
								echo "<a href='index.php?" . implode('&', $get_array) . "'> previous page</a>";
						
							$_GET['pg_num'] = $pg_num + 1;
							$get_array = array();
							foreach ($_GET as $key => $val){
								$str = $key . '=' . $val;
								array_push($get_array, $str);
							}
							if (count($images) >= 5)
								echo "<a href='index.php?" . implode('&', $get_array) . "'> next page</a>";
					?>
				</div>



			</div>
			<!-- End main contents -->
			<!-- Sidebar --><?php require_once('sidebar.php'); ?>
			
			
			<div id="clear"></div>
			

		</div>
	</div>
	<?php require_once('footer.php'); ?>
</body>
</html>



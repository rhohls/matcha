<?php
session_start();

function getSidebarLinks(){
if (isset($_SESSION['uid'])){
	$id = $_SESSION['uid'];
	echo "<li><a href=profile.php?usr_id=$id>My Profile</a></li>";
	echo "<li><a href=views.php>Views and likes</a></li>";
}
else
	echo "Log in to see fancy links";
}

function getSidebarImages(){

	// echo "list of stickes to overlay";
}

?>




<!-- side bar -->
<div id="side_bar">
	<!-- hyperlink categories to a cat page? -->
	<div id="categories_title">Cool links</div>
	<ul id="cat_list">
							
		<?php getSidebarLinks() ?>

	</ul>
	<div class="padding"></div>
	<!-- <div id="categories_title2">Furniture Type</div>
	<ul id="cat_list">
		
		<?php getSidebarImages() ?>
	
	</ul> -->
</div>
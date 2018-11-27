<?php 
function getUserName()
{
	if (isset($_SESSION['user_name']))
		echo $_SESSION['user_name'];
	else
		echo "Guest";
}
?>

<script type='text/javascript' src='javascript/scripts.js'></script>
<link rel="shortcut icon" href="favicon.ico">

<!-- This is ther header section -->
<div id="header_wrapper">
	<img id="logo" src="page_imgs/logo.png" />
	<a id="name">Matcha</a>

	<div id="login_stuff">
		<a>Logged in as: <?php getUserName() ?></a>
		<?php
		if (isset($_SESSION['user_name']))
			echo '<a><button onclick="logOut();">Log Out</button></a>';
		?>
	</div>
</div>

<!-- This is the menubar -->		
<div class="menu_bar">
	<ul>
		<li><a href="index.php">Home</a></li>
		<li><a href="#">???</a></li>
		<li><a href="#">!!!</a></li>

		
		<?php
		if (isset($_SESSION['uid'])){
			echo "<li><a href='adjust.php'>My Account</a></li>";
		}else{
			echo "<li><a href='register.php'>Register</a></li>";
			echo "<li><a href='login.php'>Login</a></li>";
		}
		?>

	</ul>
</div>
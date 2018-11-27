<?php
require_once 'connect.php';
require_once 'generic_functions.php';
session_start();

$ref = 'login.php';
$index = 'index.php';

if ($_POST["submit"] == "OK")
{
	if ($_POST["login"] !== "" && $_POST["passwd"] !== "")
	{
		$login = $_POST["login"];
		$uid = -1;

		$query = "SELECT * FROM `users` WHERE user_name=:login";
		$stmt = $pdo->prepare($query);
		$stmt->execute(['login' => $login]);
		$user = $stmt->fetch();
		// user_name, password, email, first_name, last_name, confirmed, admin, active
		if (!$user){
			alert("Details incorrect", $ref);
		}
		$hashedpwd = hashPW($_POST["passwd"]);

		if ($hashedpwd != $user['password']){
			alert("Details incorrect", $ref);
		}
		elseif ($user['confirmed'] == 0){
			alert("Please confirm your account", $ref);
		}
		elseif ($user['active'] == 0){
			alert("Your account has been deactivated\nPlease contact an admin", $ref);
		}
		else{
			$_SESSION['uid'] = $user['id'];
			$_SESSION['user_name'] = $user['user_name'];
			$_SESSION['admin'] = $user['admin'];
			alert("You have been logged in", 'index.php');
		}
		alert("Something went wrong", 'index.php');
	}
	else{
		alert("Please don't leave any field blank", $ref);
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
			<div id="items">
				<h1>Login</h1>
				<form action="./login.php" method="POST">
					<table class="form_table">
						<tr>
							<td>Username:</td>
							<td><input type="text" name="login" value=""/></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><input type="password" name="passwd" value=""/></td>
						</tr>
						<tr align="right">
							<td><input type="submit" name="submit" value="OK"/></td>
						</tr>
					</table>
				</form>
				<br>
				<h2>Forgot details</h2>
				<table class="form_table">
					<tr>
						<td><a href='reset.php'><button>Forgot Password</button></a></td>
						<td><a href='reset.php'><button>Resend verfication email</button></a></td>
					</tr>
					
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


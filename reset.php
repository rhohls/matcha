<?php

require_once 'connect.php';
require_once 'generic_functions.php';
session_start();

$redirect = 'reset.php';

if (isset($_POST["type"])){
	if ($_POST["login"] !== "" && $_POST["email"] !== "")
	{
		$login = $_POST["login"];
		$input_email = $_POST["email"];

		$query = "SELECT * FROM `users` WHERE user_name=:login";
		$stmt = $pdo->prepare($query);
		$stmt->execute(['login' => $login]);
		$user = $stmt->fetch();
		// echo $login.'</br>';
		// var_dump($user);
		if (!$user || $user['email'] !== $input_email){
			alert("Details incorrect", $redirect);
		}
		$uid = $user['id'];

	
		$code = hash('md5', $login.uniqid());

		$query = "UPDATE `users` set verification=:new_code WHERE id=:uid";
		$stmt = $pdo->prepare($query);
		$stmt->execute(['uid' => $uid, 'new_code' => $code]);
	

		if ($_POST["type"] == 'resend'){
			$to = $input_email;
			$subject = "My subject";
			$headers = "From: noresponse@camagru.co.za";
			$txt = "Dear $login
			
			Thank you for registering to Camagru please go to the following link to activate your account:
			http://localhost:8080/cama/verify.php?usr_name=$login&code=$code&verify=true
			
			Kind Regards
			Camagru";

			mail($to,$subject,$txt,$headers);
			alert("A new email with a verification link has been sent", $redirect);
		}
		elseif ($_POST["type"] == 'reset' && $user['confirmed'] == 1){
			$to = $input_email;
			$subject = "Password Reset";
			$headers = "From: noresponse@camagru.co.za";
			$txt = "Dear $login
			
			You have attempted to reset your password. To do this goto the following link:
			http://" . $_SERVER['HTTP_HOST'] ."/verify.php?usr_name=$login&code=$code&reset_pw=true
			
			Kind Regards
			Camagru";

			mail($to,$subject,$txt,$headers);
			alert("An email with a reset link has been sent", $redirect);
		}
	}
	else {
		alert("Please dont leave any field blank", $redirect);
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
			<div id="items">
				<br>
				<form action="#" method="POST">
					<table class="form_table">
						<tr>
							<td>Username:</td>
							<td><input type="text" name="login" value=""/></td>
						</tr>
						<tr>
							<td>Email:</td>
							<td><input type="email" name="email" value=""/></td>
						</tr>
					</table>
					<br>
				<table class="form_table">
					<tr align="right">
						<td>
							<button type="submit" name="type" value="reset">Reset Password</button>
							<button type="submit" name="type" value="resend">Resend verification email</button>
						</td>
					</tr>
				</table>
				</form>
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


<?php

require_once 'connect.php';
require_once 'generic_functions.php';
session_start();

$redirect = 'register.php';
$index = 'index.php';

if ($_POST["submit"] == "OK")
{
	if ($_POST["login"] !== "" && $_POST["passwd"] !== "" && $_POST["checkpasswd"] !== "" && $_POST["first_name"] !== "" && $_POST["last_name"] !== "" && $_POST["email"] !== "")
	{
		if ($_POST["passwd"] !== $_POST["checkpasswd"])
			alert("Passwords do not match", $redirect);

		$login = sanitize($_POST["login"]);
		if (userExist($pdo, $login)){
			alert("username taken", $redirect);
		}

		// length
		if (strlen($login) > 19){
			alert("username to long", $redirect);
		}
		if (strlen($_POST["passwd"]) > 254){
			alert("password to long", $redirect);
		}
		// password security level
		$pwd_error = checkPassword($_POST["passwd"]);
		if ($pwd_error){
			alert("Passwords not secure enough: " . $pwd_error , $redirect);
		}

		$hashedpwd = hashPW($_POST["passwd"]);
		$first_name = $_POST["first_name"];
		$last_name = $_POST["last_name"];
		$email = $_POST["email"];
		$code = hash('md5', $login.uniqid());
		// SQL stuff
		$query = "INSERT INTO `users` (user_name, password, email, first_name, last_name, verification)
					VALUES (:usr_name, :password, :email, :first, :last, :code)";
		$stmt = $pdo->prepare($query);
		$stmt->execute(['usr_name' => $login, 'password' => $hashedpwd, 'email' => $email, 'first' => $first_name, 'last' => $last_name, 'code' => $code]); //use this for security

		// verification emaily
		$to = $email;
		$subject = "Registration";
		$headers = "From: noresponse@camagru.co.za";
		$txt = "Dear $login

		Thank you for registering to Camagru please go to the following link to activate your account:
		http://" . server_url($_SERVER) ."/verify.php?usr_name=$login&code=$code&verify=true

		Kind Regards
		Camagru";
		mail($to,$subject,$txt,$headers);

		alert("You have been registered! Please check your email", $index);
	}
	else
		alert("Please don't leave any field blank", $redirect);
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
				<h1>Register</h1>
				<br>
				<form action="#" method="POST">
					<table class="form_table">
						<tr>
							<td>Username:</td>
							<td><input type="text" name="login" value=""/></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><input type="password" name="passwd" value=""/></td>
						</tr>
						<tr>
							<td>Retype password:</td>
							<td><input type="password" name="checkpasswd" value=""/></td>
						</tr>
						<tr>
							<td>Email adress:</td>
							<td><input type="email" name="email" value=""/></td>
						</tr>
						<tr>
							<td>First Name:</td>
							<td><input type="text" name="first_name" value=""/></td>
						</tr>
						<tr>
							<td>Last Name:</td>
							<td><input type="text" name="last_name" value=""/></td>
						</tr>
						<tr align="right">
							<td><input type="submit" name="submit" value="OK"/></td>
						</tr>
					</table>
				</form>
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

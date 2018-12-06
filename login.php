<?php
session_start();

require_once 'require.php';

$ref = 'login.php';
$index = 'index.php';

if (isset($_POST["submit"]) && $_POST["submit"] == "OK")
{
	if ((isset($_POST["login"]) && isset($_POST["passwd"])) && 
			($_POST["login"] !== "" && $_POST["passwd"] !== ""))
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
	else
	{
		alert_info("Please don\'t leave any field blank");
	}
}


echo $twig->render('login.html.twig', array(
	'base' => $base_array
));
?>
<?php

require_once 'require.php';

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

		$coords = getLocationIP();
		$hashedpwd = hashPW($_POST["passwd"]);
		$first_name = $_POST["first_name"];
		$last_name = $_POST["last_name"];
		$email = $_POST["email"];
		$code = hash('md5', $login.uniqid());
		// SQL stuff
		$query = "INSERT INTO `users` (user_name, password, email, first_name, last_name, verification, latitude, longitude)
					VALUES (:usr_name, :password, :email, :first, :last, :code, :lat, :lon)";
		$stmt = $pdo->prepare($query);
		$stmt->execute(['usr_name' => $login, 'password' => $hashedpwd, 'email' => $email, 'first' => $first_name,
						'last' => $last_name, 'code' => $code, 'lat' => $coords['lat'], 'lon' => $coords['long']]); //use this for security

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


function getLocationIP(){
	global $DB_LOC;
	$location = array();

	$ip = $_SERVER['REMOTE_ADDR'];

	if ($ip == "127.0.0.1" ||
			(substr_compare($ip, "10.", 0, 3) == 0)){

		$location = $DB_LOC;
	
	}else{
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

		$loc = explode(",", $details->loc);
		$location["lat"] = $loc[0];
		$location["long"] = $loc[1];
	}
	return ($location);
}

echo $twig->render('register.html.twig', array(
	'base' => $base_array
));

?>
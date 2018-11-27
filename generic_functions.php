<?php

function userExist($pdo, $user_name){

	$query = "SELECT user_name FROM `users` WHERE user_name=:user";
	$stmt = $pdo->prepare($query);
	$stmt->execute(['user' => $user_name]);
	// print_r($stmt);
	$result = $stmt->fetch();
	// $result = ($pdo->query($stmt))->fetch();

	// echo $result;

	if ($result)
		return true;
	else
		return false;
}

function alert($str, $redirect)
{
	// echo "redirecting to " . $redirect;
	echo "<script type='text/javascript'>
	alert('$str');
	window.location.href = '$redirect'; 
	</script>";
	die();
}
function alert_info($str)
{
	echo "<script type='text/javascript'>
	alert('$str');
	</script>";
}

function addQuotes($str){
	return ('\''.$str.'\'');
}

function hashPW($pw){
	$hashedpwd = hash('Whirlpool', $pw);
	
	return($hashedpwd);
	// return($pw);
}

function checkPassword($pwd) {
    if (strlen($pwd) < 1) {
		alert_info("pw length ". strlen($pwd) );
        return "Password too short!";
    }
    if (!preg_match("#[0-9]+#", $pwd)) {
        return "Password must include at least one number!";
    }
    if (!preg_match("#[a-zA-Z]+#", $pwd)) {
        return "Password must include at least one letter!";
    }     
    return "";
}

function exit_()
{
	echo "An error occured";
	die();
}


function sanitize($str){
	$new = htmlspecialchars($str , ENT_QUOTES);
	return $new;
}

function server_url($server){
	$folder = explode("/",$server['REQUEST_URI']);
	array_pop($folder);
	$folder1 = implode("/", $folder);
	$host = $server['HTTP_HOST'];

	return ($host . $folder1);
}

?>
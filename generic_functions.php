<?php

function profileBlocked($id_tocheck, $id_of_request, $pdo){
	$query = "SELECT * FROM `blocked` WHERE user_id=$id_of_request AND blocked_id=$id_tocheck";
	$stmt = $pdo->prepare($query);
	$stmt->execute();

	$blocked_users = $stmt->fetchAll();

	if ($blocked_users)
		return(true);
	else
		return (false);
}

function sendNotification($profile_id, $uid, $pdo){
	//dont send notification if user has blocked profile
	if (profileBlocked($uid, $profile_id, $pdo))
		return;
	$query = "UPDATE `users` SET num_notifications =  num_notifications + 1 WHERE id=$profile_id";
	$stmt = $pdo->prepare($query);
	$stmt->execute();
}

function fetchMessages($pdo, $uid, $partner){
	$query = "	SELECT * FROM `messages` WHERE 
	from_id=:partner AND to_id=:uid OR
	from_id=:uid AND to_id=:partner
	ORDER BY sent";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["uid" => $uid, "partner" => $partner]);

	$all_messages = $stmt->fetchAll();

	return ($all_messages);
}

function isConnected($pdo, $uid, $partner){
	$query =   "SELECT * FROM `view_like` WHERE connected=1	AND user_to=:id AND user_from=:id2";
	$stmt = $pdo->prepare($query);
	$stmt->execute(["id" => $uid, "id2" => $partner]);

	$res = $stmt->fetch();

	if ($res)
		return true;
	else
		return false;
}

function userExist($pdo, $user_name){

	$query = "SELECT user_name FROM `users` WHERE user_name=:user";
	$stmt = $pdo->prepare($query);
	$stmt->execute(['user' => $user_name]);
	$result = $stmt->fetch();

	if ($result)
		return true;
	else
		return false;
}

function userExist_id($pdo, $user_id){

	$query = "SELECT user_name FROM `users` WHERE id=:user";
	$stmt = $pdo->prepare($query);
	$stmt->execute(['user' => $user_id]);
	$result = $stmt->fetch();

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

function random_profile($uid, $pdo){
	$query = "SELECT id FROM `users` 
				LEFT JOIN blocked ON blocked.user_id=:id AND blocked.blocked_id=users.id
				WHERE blocked.blocked_id IS NULL 
				AND users.id<>:id
				AND users.id<>1  /* no admin */
				ORDER BY RAND()";

	$stmt = $pdo->prepare($query);
	$stmt->execute(['id' => $uid]);
	$res = $stmt->fetchAll();

	$ret_id = -1;
	if (count($res) >= 1){
		$ret_id = $res[0]['id'];
	}
	return ($ret_id);
}

function remove_duplicate($array){
	$new_array = array();
	$users = array();

	foreach ($array as $person) {
		if (!in_array($person["id"], $users)){
			array_push($new_array, $person);
			array_push($users, $person["id"]);
		}
	}

	return $new_array;
}

// not working ???
function remove_blocked($array, $uid, $pdo){
	$new_array = array();

	foreach ($array as $person) {
		if (!(profileBlocked($person["id"], $uid, $pdo))){
			array_push($new_array, $person);
		}
	}

	return $new_array;
}
?>
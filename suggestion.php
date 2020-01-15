<?php
session_start();
require_once 'require.php';
$uid = $_SESSION['uid'];


$query = "SELECT * FROM `users` WHERE user_id=$uid";
$stmt = $pdo->prepare($query);
$stmt->execute();
$user = $stmt->fetch();

//sex prefrence
$sex_search = "`gender`=";
if ($user['sex_pref'] == 'Homosexual'){
	$gender = "'" . $user['gender'] . "'";
	$sex_search .= $gender;
}
else if ($user['sex_pref'] == 'Heterosexual'){
	if ($user['gender'] == 'Male')
		$sex_search .= "'Female'";
	else
		$sex_search .= "'Male'";
}
else{
	$sex_search .= "'Male' OR `gender`='Female'";
}

//Age
$age_gap = 5;
$age_gap = $age_gap * 365;
$user_birdate = $user['birthdate'];
$age_search = "DATEDIFF(`birthdate`, $user_birdate)<$age_gap";


//Fame
$fame_gap = 5;
$fame_min = 10;
$fame_search = "`fame`>$fame_min";


//location
//do it in post

//Name
$name = "";



echo $twig->render('suggestion.html.twig', array(
	'base'		=>	$base_array

));



?>
<?php
session_start();
require_once 'require.php';
require_once './functions/search_functions.php';
$uid = $_SESSION['uid'];

/*
Isolate category using sql
sex pref
age gap
fame gap
name

then after list return isolate by:
distance
tags
*/



$query = "SELECT * FROM `users` WHERE id=$uid";
$stmt = $pdo->prepare($query);
$stmt->execute();
$user = $stmt->fetch();

//Defaults
$sex_pref = $user['sex_pref'];
$age_gap = 2;
$fame_gap = 5;
$location_gap = 1000000;
$min_tags = 0;
$name = "";


//sex prefrence
$sex_search = "`gender`=";
if ($sex_pref == 'Homosexual'){
	$gender = "'" . $user['gender'] . "'";
	$sex_search .= $gender;
}
else if ($sex_pref == 'Heterosexual'){
	if ($user['gender'] == 'Male')
		$sex_search .= "'Female'";
	else
		$sex_search .= "'Male'";
}
else{
	$sex_search = "(" . $sex_search . "'Male' OR `gender`='Female')";
}

//Age
$age_gap = $age_gap * 365;
$user_birdate = $user['birthdate'];
$age_search = "ABS(DATEDIFF(`birthdate`, '$user_birdate'))<$age_gap";


//Fame
// $fame_min = 10;
$user_fame = $user['fame'];
$fame_search = "`fame`-$user_fame>$fame_gap";

//Name
if ($name)
	$name_search = "(`first_name` LIKE '%$name%' OR `last_name` LIKE '%$name%')";
else
	$name_search = NULL;


//SQL
$results = profileSearch($pdo, $sex_search, $age_search, $fame_search, $name_search);

//location
//tags
$new_arr = array();
foreach ($results as $res_user){
	$res_user['distance'] = floor(getDistance($user['latitude'], $user['longitude'], 
										$res_user['latitude'], $res_user['longitude']));

	$res_user['tags_matching'] = matchingTags($pdo, $uid, $res_user['id']);
	array_push($new_arr, $res_user);
}
$results = $new_arr;


//blocked user 
//tang and location filter
$filtered_results = array();
foreach ($results as $res_user){
	if (inDistance($res_user, $location_gap) && 
		matchTags($res_user, $min_tags) &&
		!(profileBlocked($res_user['id'], $uid, $pdo)))

			array_push($filtered_results, $res_user);
}


//ordering for list
if (isset($_GET['orderby'])){
	$order = $_GET['orderby'];
	if ($order == 'name'){
		function OrderBy($a, $b) {
			return strcmp($a['first_name'], $b['first_name']);
		}
	}
	elseif ($order == 'gender'){
		function OrderBy($a, $b) {
			return strcmp($a['gender'], $b['gender']);
		}
	}
	elseif ($order == 'sex_pref'){
		function OrderBy($a, $b) {
			return strcmp($a['sex_pref'], $b['sex_pref']);
		}
	}
	elseif ($order == 'fame'){
		function OrderBy($a, $b) {
			return $a['fame'] - $b['fame'];
		}
	}
	elseif ($order == 'age'){
		function OrderBy($a, $b) {
			return $a['age'] - $b['age'];
		}
	}
	elseif ($order == 'distance'){
		function OrderBy($a, $b) {
			return $a['distance'] - $b['distance'];
		}
	}
	elseif ($order == 'tags_matching'){
		function OrderBy($a, $b) {
			return $a['tags_matching'] - $b['tags_matching'];
		}
	}

	usort($filtered_results, 'OrderBy');
}



$search_results = $filtered_results;
echo $twig->render('searchresults.html.twig', array(
	'base'		=>	$base_array,
	'users'		=>	$search_results

));



?>
<?php

require_once 'require.php';
require_once "./config/database.php";



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

getLocationIP();
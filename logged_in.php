<?php
session_start();
require_once 'connect.php';
require_once 'generic_functions.php';

if (!isset($_SESSION['uid'])){
	alert("You need to be logged in to do that", "login.php");
	die();
}
?>
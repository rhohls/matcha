<?php

session_start();

require_once 'require.php';

$rand = -1;
if ($base_array['loggedin'])
	$rand = random_profile($_SESSION['uid'], $pdo);

echo $twig->render('index.html.twig', array(
	'base' => $base_array,
	'rand_id'=> $rand
));
<?php

session_start();

require_once 'require.php';

echo $twig->render('index.html.twig', array(
	'location' => 'world',
	'base' => $base_array
));
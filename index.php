<?php

require_once 'twig.php';


echo $twig->render('index.html.twig', array(
	'location' => 'world',
	'header' => array(
		'message' => 'who me?'
	)
));
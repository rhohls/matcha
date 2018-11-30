<?php
session_start();
require_once 'require.php';

require_once 'logged_in.php';

echo $twig->render('views.html.twig', array(
	'base' => $base_array
));

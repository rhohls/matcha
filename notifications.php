<?php
session_start();
require_once 'require.php';

require_once 'logged_in.php';


// set notifications to 0
echo $twig->render('notifications.html.twig', array(
	'base' => $base_array
));
?>
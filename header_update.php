
<?php
session_start();

require_once 'connect.php';

$uid = $_SESSION['uid'];

$query = "SELECT num_notifications FROM `users` WHERE id=$uid";
$stmt = $pdo->prepare($query);
$stmt->execute();

$res = $stmt->fetch();

echo "Notifications(" . $res['num_notifications'] . ")";


?>
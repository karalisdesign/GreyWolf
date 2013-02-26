<?php
require_once('../gw_system.php');
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	if(isset($_POST['unique']) && $_POST['unique'] == true) { $unique = true; } else { $unique = null; }
	add_file($_POST['id'],true,$unique);
}
?>
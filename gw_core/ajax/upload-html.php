<?php
require_once('../gw_system.php');
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
$nome = $_POST['name'];
$att = $_POST['att']; // ID dell'allegato
$id = $_POST['post']; // ID del post
if ($_POST['token'] == $verifyToken) { 
	echo get_meta_box($att,$nome,$id);
} 
?>
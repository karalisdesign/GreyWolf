<?php
require_once('../gw_system.php');
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
$att = $_POST['att']; // ID dell'allegato
if ($_POST['token'] == $verifyToken) { 
	$html = '<a href="'.get_image($att).'" class="lightbox">
			<img src="'.get_image($att,'thumb').'" alt="'.get_image_title($att).'" rel="img[gallery]" />
		</a>
		<a href="javascript:void(0);" class="btn btn-danger btn-mini del_meta-gal" rel="delete=meta_id&id='.$att.'">x</a>
		<div class="clearfix"></div>';
	echo $html;
} 
?>
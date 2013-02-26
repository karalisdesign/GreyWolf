<?php
require_once('gw_core/gw_system.php');
if(!get_option('active') == 1) {
	header('location: /gw_admin/install.php');
	exit;
}
$theme = get_option('gw_theme');
include('gw_themes/'.$theme.'/index.php'); 
?>
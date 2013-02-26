<?php
require_once('../gw_core/gw_system.php');
gw_check_posttype();
	
	$array = array('title'=>' ','type'=>$_GET['gw_type']);
	$id = add_content($array);
	if(isset($id) && check_content($id)) {
		header('location: edit.php?action=new&id='.$id);
	}
?>

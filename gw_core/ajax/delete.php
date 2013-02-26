<?php
require_once('../gw_system.php');
$tab_content = TAB_CONTENT; 
$tab_meta = TAB_META; 
$tab_users = TAB_USERS;
$tab_options = TAB_OPTIONS;


if(isset($_POST['id'])) $id = $_POST['id'];
if(isset($_POST['delete']) && isset($id)) {
	if($_POST['delete'] == 'user') { 
		$table = $tab_users; 
		delete_content($id,$table,true);
	}
	elseif($_POST['delete'] == 'content') {
		$table = $tab_content; 
		delete_post_meta_att($id);
		delete_content($id,$table,true);
	}
	elseif($_POST['delete'] == 'meta') {
		$table = $tab_content_meta; 
		$key = $_POST['key'];
		delete_meta($id,$key);
		echo $id;
	}
	elseif($_POST['delete'] == 'users') {
		$table = $tab_users; ;
		delete_user($id);
	}
	elseif($_POST['delete'] == 'meta_id') {
		$table = $tab_content_meta; 
		$meta_id = $_POST['id'];
		delete_meta_by_id($meta_id);
		echo $meta_id;
	}
	elseif(in_array($_POST['delete'],array('lingue','posttype'))) {
		$delete = $_POST['delete'];
		$values = unserialize(get_option($delete));
		unset($values[$id]);
		$value = serialize($values);
		$db = new DataBase();
		// Scrivo la query e...
		$query ="UPDATE `$tab_options` SET `option_value` = '$value' WHERE `option_name` = '$delete'";
		// ... la eseguo!
		$result = $db->Query($query);
		echo $id;
	}
}
?>
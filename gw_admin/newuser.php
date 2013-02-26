<?php
require_once('../gw_core/gw_system.php');
if(count($_POST['user']) > 0) {
$array = array();
foreach($_POST['user'] as $key => $value){
	$array[$key] = $value;
}

	if(isset($array['email']) && isset($array['name'])) {
		$user = add_user($array);
		if(isset($user)) {
			header('location: user.php?action=new&id='.$user);
		}
	} else {
		print_r($array);
		//header('location: allusers.php?action=new-user&note=true&return=error&msg=103');
	}

}
?>
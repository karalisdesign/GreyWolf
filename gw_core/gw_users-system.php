<?php

class user {

    var $user;
    var $password;
    var $ok;

    function salt(){
        return FB_SECRET;
    }

    function crypt($password){
    	$seckey = md5($password.$this->salt());
    	return $seckey;
    }

    function login($user,$password,$redirect = null){
    	$tab_users = TAB_USERS;
        $db = new DataBase();
        $query = "SELECT *
		FROM `$tab_users`
		WHERE `email` = '$user'";
		// ... la eseguo!
		$result = $db->GetRow($query);

		$gw_user = $result['email'];
		$gw_pass = $result['seckey'];

		$getpass = $this->crypt($password);

		if(!isset($redirect)) { $redirect = gw_url().'/?action=welcome'; }

		if($user == $gw_user && $getpass == $gw_pass) {
			$_SESSION['user'] = $gw_user;
			header('location:'.$redirect);
			exit;
		} 
		else {
			return true;
		}
    }

    function data($user = null){
		if(isset($_SESSION['user']) || isset($user)) {
		$db = new DataBase();
		if(isset($user) && is_numeric($user)) {
			$id = $user;
			$query = "SELECT * FROM gw_users WHERE id ='$id'";
		} elseif(isset($_SESSION['user'])){
			$email = $_SESSION['user'];
			$query = "SELECT * FROM gw_users WHERE email ='$email'";
		}
		$user = $db->GetRow($query);
		//$user = (object) $user;
		return $user;
		}
	}

}
?>